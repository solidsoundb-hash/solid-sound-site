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
add_filter( 'wp_robots', function ( $r ) {
	$p = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
	if ( preg_match( '#^/([a-z]{2}/)?(forum|community)(/|$)#', $p ) ) { $r['noindex'] = true; $r['nofollow'] = true; }
	return $r;
} );
