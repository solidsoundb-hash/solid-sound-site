<?php
/**
 * Front-end cleanup found by the site audit (11.11.2026).
 *
 * 1. WordPress emoji: off. It loaded a detection script and swapped every emoji for an image from s.w.org
 *    (5 extra downloads on the home page alone). Browsers show emoji natively.
 * 2. Old theme footer (page block 52): it is hidden and replaced by modules/footer.php, but it still printed
 *    4 old scripts on every page — incl. the old gzip+base64 menu loader that downloaded an archive on the first
 *    visit. The optimized build does not render that footer area at all; if the theme prints it anyway, the old
 *    scripts are cut out of its content.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* 1. emoji */
add_action( 'init', function () {
	if ( is_admin() ) return;
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
	add_filter( 'wp_resource_hints', function ( $urls, $type ) {
		return 'dns-prefetch' === $type ? array_values( array_filter( $urls, function ( $u ) { return false === strpos( is_array( $u ) ? (string) ( $u['href'] ?? '' ) : (string) $u, 's.w.org' ); } ) ) : $urls;
	}, 10, 2 );
} );

/* 2. old footer area */
add_filter( 'us_get_page_area_id', function ( $id, $area = '' ) {
	return 'footer' === $area ? '' : $id;
}, 20, 2 );

function ss_cleanup_old_footer_scripts( $c ) {
	if ( false === strpos( $c, 'ssg-loader' ) && false === strpos( $c, '__ssfl' ) ) return $c;
	$c = preg_replace( '#<script id="ssg-loader">.*?</script>#s', '', $c );
	$c = preg_replace( '#<script id="ss-logo-btn">.*?</script>#s', '', $c );
	$c = preg_replace( '#<style id="ss-logo-btn-css">.*?</style>#s', '', $c );
	$c = preg_replace( '#<script>\(function\(\)\{if\(window\.__ssfl\)return;.*?</script>#s', '', $c );
	return $c;
}
add_filter( 'the_content', 'ss_cleanup_old_footer_scripts', 1 );
add_filter( 'us_page_block_the_content', 'ss_cleanup_old_footer_scripts', 1 );

/* 3. Forum hidden for now (owner 12.11.2026): no links in menus/footers (removed there), not indexed by search engines.
   The forum and its data stay; to bring it back remove this block and add the links again. */
/* 16.11.2026 owner: forum is OPEN again (community) — only the experimental pages stay hidden */
define( 'SS_HIDDEN_PATHS', '#^/([a-z]{2}/)?(forum-experimental|experimental)(/|$)#' );
add_filter( 'wp_robots', function ( $r ) {
	$p = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
	if ( preg_match( SS_HIDDEN_PATHS, $p ) ) { $r['noindex'] = true; $r['nofollow'] = true; }
	return $r;
} );
/* (13.11.2026) a visitor who lands on a hidden/experimental page goes to the home page (admins still see it) */
add_action( 'template_redirect', function () {
	if ( is_admin() || current_user_can( 'manage_options' ) ) return;
	$p = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
	if ( preg_match( '#^/([a-z]{2}/)?(forum-experimental|experimental)(/|$)#', $p ) ) { wp_safe_redirect( home_url( '/en/' ), 302 ); exit; }
}, 1 );

/* 4. (18.11.2026, owner) "Forest Spirits" is a friend's project, not ours: remove it completely.
   Once: every post/page whose slug starts with "forest-spirits" (all languages, with child pages) goes to the WordPress trash.
   Old links (/en/forest-spirits/, /ua/forest-spirits/ …) lead to the home page with a permanent redirect. */
add_action( 'init', function () {
	if ( get_option( 'ss_forest_spirits_removed' ) ) return;
	global $wpdb;
	$ids = $wpdb->get_col( $wpdb->prepare(
		"SELECT ID FROM {$wpdb->posts} WHERE post_name LIKE %s AND post_status NOT IN ('trash','auto-draft','inherit')",
		$wpdb->esc_like( 'forest-spirits' ) . '%'
	) );
	$all = array();
	foreach ( (array) $ids as $id ) {
		$all[] = (int) $id;
		foreach ( get_children( array( 'post_parent' => (int) $id, 'post_type' => 'any', 'fields' => 'ids' ) ) as $c ) $all[] = (int) $c;
	}
	foreach ( array_unique( $all ) as $id ) wp_trash_post( $id );
	update_option( 'ss_forest_spirits_removed', $all ? implode( ',', array_unique( $all ) ) : 'none', false );
}, 30 );
add_action( 'template_redirect', function () {
	$p = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
	if ( preg_match( '#^/([a-z]{2}/)?forest-spirits(-[^/]*)?(/|$)#', $p ) ) { wp_safe_redirect( home_url( '/en/' ), 301 ); exit; }
}, 0 );

/* 5. (19.11.2026, owner «делай», analytics report) Two test reviews from 12.01.2022 ("vhgvhgv", "hghjgj", 5★ on Kratos/Protos)
   were shown as real ratings and went into Google's product markup. Once: move them to the trash (undoable in Comments),
   then let WooCommerce recount the rating. Only reviews whose whole text is one of these test strings are touched. */
add_action( 'init', function () {
	if ( get_option( 'ss_test_reviews_removed' ) ) return;
	$junk = array( 'vhgvhgv', 'hghjgj' );
	$done = array();
	foreach ( get_comments( array( 'post_type' => 'product', 'status' => 'all', 'number' => 200 ) ) as $c ) {
		if ( in_array( strtolower( trim( wp_strip_all_tags( $c->comment_content ) ) ), $junk, true ) ) {
			wp_trash_comment( $c->comment_ID );
			$done[] = (int) $c->comment_ID;
			if ( class_exists( 'WC_Comments' ) ) WC_Comments::clear_transients( (int) $c->comment_post_ID );
		}
	}
	update_option( 'ss_test_reviews_removed', $done ? implode( ',', $done ) : 'none', false );
}, 30 );

/* 3. (20.11.2026 language robot, owner «проверь языки») old duplicate Ukrainian pages → the real ones (301), and out of the sitemap.
 *    The pages themselves stay in WordPress (nothing deleted); remove a line here to bring one back. */
function ss_cleanup_dupes() {
	return array(
		'/ua/contacts-uk/'    => '/ua/kontakty/',
		'/ua/music-uk/'       => '/ua/muzyka/',
		'/ua/presets-uk/'     => '/ua/presety/',
		'/ua/templates-uk/'   => '/ua/shablony/',
		'/ua/sample-page-uk/' => '/ua/',
	);
}
add_action( 'template_redirect', function () {
	$p = strtok( (string) ( $_SERVER['REQUEST_URI'] ?? '' ), '?' );
	$m = ss_cleanup_dupes();
	if ( isset( $m[ $p ] ) ) { wp_safe_redirect( home_url( $m[ $p ] ), 301 ); exit; }
}, 1 );
add_filter( 'wp_sitemaps_posts_query_args', function ( $a, $type ) {
	if ( 'page' !== $type ) return $a;
	$ids = array_filter( array_map( function ( $p ) { return url_to_postid( home_url( $p ) ); }, array_keys( ss_cleanup_dupes() ) ) );
	if ( $ids ) $a['post__not_in'] = array_merge( isset( $a['post__not_in'] ) ? (array) $a['post__not_in'] : array(), $ids );
	return $a;
}, 10, 2 );
