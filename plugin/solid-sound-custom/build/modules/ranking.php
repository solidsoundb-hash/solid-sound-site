<?php
/**
 * Quality ranking (docs/ranking-system.md).
 *
 * Signals: paid sales in the last 90 days (WooCommerce analytics table), existing counters _ss_likes / _ss_plays,
 * new counters _ss_carts (add to cart) and _ss_views (product page views, 1 per visitor per day),
 * customer rating (Bayesian average of WooCommerce reviews), refunds (penalty).
 * Daily score -> _ss_score, tier -> _ss_tier: featured | best | trending | catalog | new | hidden.
 * Owner controls: WooCommerce "Featured" star, "Hide from home" checkbox, Ranking page (WooCommerce -> Ranking).
 * Home "Best products" gets its list from here (window.SS_CATALOG).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_rk_settings() {
	$d = array( 'w_sales' => 10, 'w_carts' => 3, 'w_likes' => 2, 'w_plays' => 1, 'w_views' => 0.05, 'refund_penalty' => 20,
		'best_top' => 10, 'best_min_sales' => 3, 'best_min_rating' => 4.0, 'trend_min_rating' => 3.8, 'hide_rating' => 3.0, 'hide_min_reviews' => 5,
		'prior_rating' => 4.0, 'prior_weight' => 5 );
	return wp_parse_args( (array) get_option( 'ss_rk_settings', array() ), $d );
}

/* ---------- counters ---------- */
add_action( 'woocommerce_add_to_cart', function ( $key, $pid ) {
	$pid = (int) $pid; if ( ! $pid ) return;
	$c = isset( $_COOKIE['ss_rk_c'] ) ? (string) $_COOKIE['ss_rk_c'] : '';
	if ( false !== strpos( ',' . $c . ',', ',' . $pid . ',' ) ) return; /* once per visitor per day */
	update_post_meta( $pid, '_ss_carts', (int) get_post_meta( $pid, '_ss_carts', true ) + 1 );
	if ( ! headers_sent() ) setcookie( 'ss_rk_c', trim( $c . ',' . $pid, ',' ), time() + DAY_IN_SECONDS, '/', '', is_ssl(), true );
}, 10, 2 );

add_action( 'template_redirect', function () {
	if ( ! function_exists( 'is_product' ) || ! is_product() || is_user_logged_in() ) return;
	$ua = isset( $_SERVER['HTTP_USER_AGENT'] ) ? (string) $_SERVER['HTTP_USER_AGENT'] : '';
	if ( '' === $ua || preg_match( '/bot|crawl|spider|slurp|preview|lighthouse|headless/i', $ua ) ) return;
	$pid = (int) get_queried_object_id();
	$c = isset( $_COOKIE['ss_rk_v'] ) ? (string) $_COOKIE['ss_rk_v'] : '';
	if ( false !== strpos( ',' . $c . ',', ',' . $pid . ',' ) ) return;
	update_post_meta( $pid, '_ss_views', (int) get_post_meta( $pid, '_ss_views', true ) + 1 );
	$ids = array_slice( array_filter( explode( ',', $c . ',' . $pid ) ), -60 );
	if ( ! headers_sent() ) setcookie( 'ss_rk_v', implode( ',', $ids ), time() + DAY_IN_SECONDS, '/', '', is_ssl(), true );
}, 5 );

/* ---------- daily score ---------- */
function ss_rk_recalc() {
	global $wpdb;
	$s = ss_rk_settings();
	$lookup = $wpdb->prefix . 'wc_order_product_lookup';
	$sales = $refunds = array();
	if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $lookup ) ) === $lookup ) {
		foreach ( $wpdb->get_results( "SELECT product_id, SUM(GREATEST(product_qty,0)) s, SUM(product_qty<0) r FROM $lookup WHERE date_created >= DATE_SUB(NOW(), INTERVAL 90 DAY) GROUP BY product_id" ) as $r ) {
			$sales[ (int) $r->product_id ] = (int) $r->s; $refunds[ (int) $r->product_id ] = (int) $r->r;
		}
	}
	$rows = array();
	foreach ( get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids' ) ) as $id ) {
		$p = wc_get_product( $id ); if ( ! $p ) continue;
		$n = (int) $p->get_review_count(); $avg = (float) $p->get_average_rating();
		$rating = ( $s['prior_weight'] * $s['prior_rating'] + $avg * $n ) / ( $s['prior_weight'] + $n );
		$sl = isset( $sales[ $id ] ) ? $sales[ $id ] : 0;
		$views = (int) get_post_meta( $id, '_ss_views', true );
		$raw = $sl * $s['w_sales'] + (int) get_post_meta( $id, '_ss_carts', true ) * $s['w_carts'] + (int) get_post_meta( $id, '_ss_likes', true ) * $s['w_likes']
			+ (int) get_post_meta( $id, '_ss_plays', true ) * $s['w_plays'] + $views * $s['w_views'];
		$conv = $views > 20 ? min( 1.3, 1 + ( $sl / $views ) * 3 ) : 1;
		$score = round( $raw * ( $rating / 4 ) * $conv - ( isset( $refunds[ $id ] ) ? $refunds[ $id ] : 0 ) * $s['refund_penalty'], 2 );
		$prev = (float) get_post_meta( $id, '_ss_score', true );
		$uid = (int) get_post_field( 'post_author', $id );
		$rows[ $id ] = array( 'score' => $score, 'sales' => $sl, 'rating' => $rating, 'reviews' => $n, 'avg' => $avg, 'refunds' => isset( $refunds[ $id ] ) ? $refunds[ $id ] : 0,
			'trusted' => user_can( $uid, 'manage_woocommerce' ), 'featured' => $p->is_featured(), 'hide_home' => (bool) get_post_meta( $id, '_ss_hide_home', true ),
			'growth' => $prev > 0 ? ( $score - $prev ) / $prev : ( $score > 0 ? 1 : 0 ) );
	}
	uasort( $rows, function ( $a, $b ) { return $b['score'] <=> $a['score']; } );
	$rank = 0;
	foreach ( $rows as $id => $r ) {
		$rank++;
		if ( $r['reviews'] >= $s['hide_min_reviews'] && $r['avg'] < $s['hide_rating'] ) $tier = 'hidden';
		elseif ( $r['featured'] ) $tier = 'featured';
		elseif ( $rank <= $s['best_top'] && $r['sales'] >= $s['best_min_sales'] && $r['rating'] >= $s['best_min_rating'] ) $tier = 'best';
		elseif ( $r['growth'] >= 0.3 && $r['score'] > 0 && $r['rating'] >= $s['trend_min_rating'] && ( $r['sales'] > 0 || $r['trusted'] ) ) $tier = 'trending';
		elseif ( ! $r['trusted'] && $r['sales'] < $s['best_min_sales'] ) $tier = 'new';
		else $tier = 'catalog';
		update_post_meta( $id, '_ss_score', $r['score'] );
		update_post_meta( $id, '_ss_tier', $tier );
		update_post_meta( $id, '_ss_rank', $rank );
	}
	update_option( 'ss_rk_last', time(), false );
	delete_transient( 'ss_rk_home' );
	return count( $rows );
}
add_action( 'ss_rk_daily', 'ss_rk_recalc' );
add_action( 'init', function () {
	if ( ! wp_next_scheduled( 'ss_rk_daily' ) ) wp_schedule_event( time() + 300, 'daily', 'ss_rk_daily' );
} );

/* ---------- home "Best products": featured, best sellers, trending; owner's products fill the rest ---------- */
function ss_rk_home_catalog( $limit = 12 ) {
	$cached = get_transient( 'ss_rk_home' );
	if ( is_array( $cached ) ) return $cached;
	$order = array( 'featured' => 0, 'best' => 1, 'trending' => 2, 'catalog' => 3 );
	$items = array();
	foreach ( get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'numberposts' => 80, 'fields' => 'ids', 'meta_key' => '_ss_score', 'orderby' => 'meta_value_num', 'order' => 'DESC' ) ) as $id ) {
		$tier = (string) get_post_meta( $id, '_ss_tier', true );
		if ( ! isset( $order[ $tier ] ) || get_post_meta( $id, '_ss_hide_home', true ) ) continue;
		$uid = (int) get_post_field( 'post_author', $id );
		if ( 'catalog' === $tier && ! user_can( $uid, 'manage_woocommerce' ) ) continue; /* authors reach home only by merit */
		$items[] = array( $order[ $tier ], -(float) get_post_meta( $id, '_ss_score', true ), $id, $tier );
	}
	sort( $items );
	$G = 'linear-gradient(180deg, rgba(7,10,18,0) 70%, rgba(7,10,18,0.55) 100%), ';
	$out = array();
	foreach ( array_slice( $items, 0, $limit ) as $k => $it ) {
		list( , , $id, $tier ) = $it;
		$p = wc_get_product( $id ); if ( ! $p ) continue;
		$fmt = wp_get_post_terms( $id, 'product-format', array( 'fields' => 'slugs' ) );
		$fmt = is_wp_error( $fmt ) ? array() : $fmt;
		$t = in_array( 'presets', $fmt, true ) ? 'presets' : ( in_array( 'music', $fmt, true ) ? 'music' : 'templates' );
		$gen = wp_get_post_terms( $id, 'product-genre', array( 'fields' => 'names' ) );
		$genre = ( ! is_wp_error( $gen ) && $gen ) ? $gen[0] : '';
		$bpm = (int) $p->get_attribute( 'bpm' ); $key = (string) $p->get_attribute( 'key' );
		$meta = array_filter( array( $bpm ? $bpm . ' BPM' : '', $key ? 'Key ' . $key : '', $p->get_review_count() ? '★ ' . number_format( (float) $p->get_average_rating(), 1 ) : '' ) );
		$img = get_the_post_thumbnail_url( $id, 'woocommerce_thumbnail' );
		$txt = get_post_field( 'post_excerpt', $id ) . ' ' . get_post_field( 'post_content', $id );
		$mp3 = preg_match( '#https?://[^\s"\'\]]+?\.mp3#i', $txt, $m ) ? $m[0] : '';
		$tr = function_exists( 'ss_home_t' ) ? 'ss_home_t' : 'strval';
		$badge = array( 'featured' => $tr( "Editor's pick" ), 'best' => $tr( 'Best seller' ), 'trending' => $tr( 'Trending' ) );
		$out[] = array(
			'id' => 'p' . $id, 'pid' => $id, 'name' => $p->get_name(), 't' => $t, 'genre' => $genre ? $genre : ucfirst( $t ),
			'sub' => $genre ? $genre : '', 'meta' => $meta ? implode( ' · ', $meta ) : ( $genre ? $genre : ucfirst( $t ) ),
			'tags' => array_values( array_filter( array( 'templates' === $t ? 'Ableton Live' : ( 'presets' === $t ? 'Serum' : 'WAV' ) ) ) ),
			'bpm' => $bpm, 'key' => $key, 'live' => 0, 'price' => (float) $p->get_price(),
			'bg' => $img ? $G . "url('" . esc_url_raw( $img ) . "') center / cover no-repeat #1b1b1b" : '#1b1b1b',
			'badge' => isset( $badge[ $tier ] ) ? $badge[ $tier ] : '', 'fy' => $k < 4, 'href' => get_permalink( $id ), 'mp3' => $mp3,
		);
	}
	set_transient( 'ss_rk_home', $out, HOUR_IN_SECONDS );
	return $out;
}
add_action( 'wp_head', function () {
	if ( ! function_exists( 'ss_home_is_app_page' ) || ! ss_home_is_app_page() ) return;
	$c = ss_rk_home_catalog();
	if ( count( $c ) >= 4 ) echo '<script>window.SS_CATALOG=' . wp_json_encode( $c, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE ) . ';</script>';
}, 4 );

/* ---------- owner controls ---------- */
add_action( 'post_submitbox_misc_actions', function ( $post ) {
	if ( 'product' !== $post->post_type || ! current_user_can( 'manage_woocommerce' ) ) return;
	wp_nonce_field( 'ss_rk_box', 'ss_rk_nonce' );
	$tier = get_post_meta( $post->ID, '_ss_tier', true );
	echo '<div class="misc-pub-section"><label><input type="checkbox" name="ss_hide_home" value="1"' . checked( (bool) get_post_meta( $post->ID, '_ss_hide_home', true ), true, false ) . '> Hide from home page</label>'
		. ( $tier ? '<br><small>Ranking: <b>' . esc_html( $tier ) . '</b> · score ' . esc_html( (string) get_post_meta( $post->ID, '_ss_score', true ) ) . ' · #' . (int) get_post_meta( $post->ID, '_ss_rank', true ) . '</small>' : '' ) . '</div>';
} );
add_action( 'save_post_product', function ( $id ) {
	if ( ! isset( $_POST['ss_rk_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['ss_rk_nonce'] ), 'ss_rk_box' ) || ! current_user_can( 'manage_woocommerce' ) ) return;
	empty( $_POST['ss_hide_home'] ) ? delete_post_meta( $id, '_ss_hide_home' ) : update_post_meta( $id, '_ss_hide_home', 1 );
	delete_transient( 'ss_rk_home' );
} );

add_action( 'admin_menu', function () {
	add_submenu_page( 'woocommerce', 'Ranking', 'Ranking', 'manage_woocommerce', 'ss-ranking', 'ss_rk_admin_page' );
}, 60 );
function ss_rk_admin_page() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) return;
	if ( isset( $_POST['ss_rk_do'] ) && check_admin_referer( 'ss_rk_admin' ) ) {
		if ( 'save' === $_POST['ss_rk_do'] ) {
			$new = array();
			foreach ( ss_rk_settings() as $k => $v ) if ( isset( $_POST[ $k ] ) ) $new[ $k ] = (float) $_POST[ $k ];
			update_option( 'ss_rk_settings', $new, false );
		}
		$n = ss_rk_recalc();
		echo '<div class="notice notice-success"><p>Recalculated ' . (int) $n . ' products.</p></div>';
	}
	$s = ss_rk_settings();
	echo '<div class="wrap"><h1>Ranking</h1><p>Updated daily. Last run: ' . esc_html( get_option( 'ss_rk_last' ) ? wp_date( 'Y-m-d H:i', (int) get_option( 'ss_rk_last' ) ) : 'never' ) . '. Tiers: featured (your ★ in Products), best, trending, catalog, new (authors\' products before ' . (int) $s['best_min_sales'] . ' sales — never on the home page), hidden.</p>';
	echo '<form method="post">'; wp_nonce_field( 'ss_rk_admin' );
	echo '<table class="form-table"><tr>';
	$i = 0;
	foreach ( $s as $k => $v ) { echo '<td><label>' . esc_html( $k ) . '<br><input name="' . esc_attr( $k ) . '" value="' . esc_attr( $v ) . '" size="6"></label></td>'; if ( ++$i % 7 === 0 ) echo '</tr><tr>'; }
	echo '</tr></table><p><button class="button button-primary" name="ss_rk_do" value="save">Save &amp; recalculate</button> <button class="button" name="ss_rk_do" value="run">Recalculate now</button></p></form>';
	echo '<table class="widefat striped"><thead><tr><th>#</th><th>Product</th><th>Author</th><th>Tier</th><th>Score</th><th>Sales 90d</th><th>Rating</th><th>Likes</th><th>Plays</th><th>Carts</th><th>Views</th><th>Home</th></tr></thead><tbody>';
	foreach ( get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'numberposts' => 100, 'fields' => 'ids', 'meta_key' => '_ss_rank', 'orderby' => 'meta_value_num', 'order' => 'ASC' ) ) as $id ) {
		$p = wc_get_product( $id ); if ( ! $p ) continue;
		$au = get_userdata( (int) get_post_field( 'post_author', $id ) );
		echo '<tr><td>' . (int) get_post_meta( $id, '_ss_rank', true ) . '</td><td><a href="' . esc_url( get_edit_post_link( $id ) ) . '">' . esc_html( $p->get_name() ) . '</a></td><td>' . esc_html( $au ? $au->display_name : '' ) . '</td><td><b>' . esc_html( get_post_meta( $id, '_ss_tier', true ) ) . '</b></td><td>' . esc_html( get_post_meta( $id, '_ss_score', true ) ) . '</td><td>' . (int) $p->get_total_sales() . '</td><td>' . esc_html( $p->get_review_count() ? number_format( (float) $p->get_average_rating(), 1 ) . ' (' . $p->get_review_count() . ')' : '—' ) . '</td><td>' . (int) get_post_meta( $id, '_ss_likes', true ) . '</td><td>' . (int) get_post_meta( $id, '_ss_plays', true ) . '</td><td>' . (int) get_post_meta( $id, '_ss_carts', true ) . '</td><td>' . (int) get_post_meta( $id, '_ss_views', true ) . '</td><td>' . ( get_post_meta( $id, '_ss_hide_home', true ) ? 'hidden' : '✓' ) . '</td></tr>';
	}
	echo '</tbody></table></div>';
}
