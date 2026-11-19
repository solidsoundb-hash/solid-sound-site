<?php
/**
 * "Similar sounds" on the product page (owner 11.11.2026, idea from Splice "Similar Sounds").
 * WooCommerce's own related block stays (same place, same look); only WHICH products it shows changes:
 * same format first, then same genre, close BPM, same root key; popularity breaks ties.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_sim_terms( $id, $tax ) {
	$t = get_the_terms( $id, $tax );
	return ( $t && ! is_wp_error( $t ) ) ? wp_list_pluck( $t, 'slug' ) : array();
}
function ss_sim_bpm( $id ) {
	foreach ( ss_sim_terms( $id, 'pa_bpm' ) as $s ) { if ( preg_match( '/\d+/', $s, $m ) ) return (int) $m[0]; }
	return 0;
}
function ss_sim_format( $id ) {
	return function_exists( 'ss_pd_format' ) ? ss_pd_format( $id ) : implode( ',', ss_sim_terms( $id, 'product-format' ) );
}

function ss_sim_ids( $pid, $limit ) {
	$ck = 'ss_sim_' . $pid . '_' . $limit;
	$c  = get_transient( $ck );
	if ( is_array( $c ) ) return $c;
	$all = get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'numberposts' => 300, 'fields' => 'ids', 'exclude' => array( $pid ), 'suppress_filters' => false ) );
	$fmt = ss_sim_format( $pid );
	$gen = ss_sim_terms( $pid, 'product-genre' );
	$key = ss_sim_terms( $pid, 'pa_root-key' );
	$bpm = ss_sim_bpm( $pid );
	$score = array();
	foreach ( $all as $id ) {
		$p = wc_get_product( $id );
		if ( ! $p || ! $p->is_visible() ) continue;
		$s = 0;
		if ( ss_sim_format( $id ) === $fmt ) $s += 4;
		$s += 3 * count( array_intersect( $gen, ss_sim_terms( $id, 'product-genre' ) ) );
		if ( $key && array_intersect( $key, ss_sim_terms( $id, 'pa_root-key' ) ) ) $s += 2;
		$b = ss_sim_bpm( $id );
		if ( $bpm && $b ) { $d = abs( $bpm - $b ); if ( $d <= 3 ) $s += 2; elseif ( $d <= 8 ) $s += 1; }
		$c = function_exists( 'ss_ssx_cos' ) ? ss_ssx_cos( $pid, $id ) : null; /* 19.11: meaning fingerprints (smart-search.php) */
		if ( null !== $c ) $s += max( 0, ( $c - 0.4 ) / 0.3 ) * 5;
		$s += min( 1, (int) get_post_meta( $id, 'total_sales', true ) / 50 ); /* popularity: tie-breaker only */
		$score[ $id ] = $s;
	}
	arsort( $score );
	$ids = array_slice( array_keys( $score ), 0, $limit );
	set_transient( $ck, $ids, HOUR_IN_SECONDS );
	return $ids;
}

/* replace the default related block (it shuffles) with the same template in our order */
add_action( 'wp', function () {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) return;
	/* only if the default block is really there (the theme might print its own) -> never two blocks */
	$pr = has_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products' );
	if ( false === $pr ) return;
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', $pr );
	add_action( 'woocommerce_after_single_product_summary', function () {
		global $product;
		if ( ! $product ) return;
		$ids = ss_sim_ids( $product->get_id(), 4 );
		$list = array_values( array_filter( array_map( 'wc_get_product', $ids ) ) );
		if ( ! $list ) { woocommerce_output_related_products(); return; }
		wc_set_loop_prop( 'name', 'related' );
		wc_set_loop_prop( 'columns', 4 );
		wc_get_template( 'single-product/related.php', array( 'related_products' => $list, 'columns' => 4, 'posts_per_page' => 4, 'orderby' => 'none' ) );
	}, $pr );
}, 30 );

/* phones: two even columns instead of one narrow column */
add_action( 'wp_head', function () {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) return;
	echo '<style id="ss-sim-css">@media(max-width:760px){.single-product section.related ul.products{display:grid!important;grid-template-columns:1fr 1fr!important;gap:10px!important;overflow:visible!important;padding:0!important}'
		. '.single-product section.related ul.products li.product{width:auto!important;max-width:none!important;flex:none!important;margin:0!important;padding:8px 8px 10px!important}'
		. '.single-product section.related ul.products li.product .button{width:100%!important;text-align:center!important;padding:8px 6px!important;font-size:13px!important}'
		. '.single-product section.related ul.products li.product h2{font-size:14px!important}.single-product section.related>h2{font-size:22px!important}}</style>';
}, 60 );

add_filter( 'woocommerce_product_related_products_heading', function () { return 'Similar sounds you may like'; } );

/* product saved -> forget cached lists */
add_action( 'save_post_product', function () {
	global $wpdb;
	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '\_transient\_ss\_sim\_%' OR option_name LIKE '\_transient\_timeout\_ss\_sim\_%'" );
} );

/* (12.11.2026 speed) the main product image is the largest thing on the page: load it first, never lazily */
add_filter( 'woocommerce_gallery_image_html_attachment_image_params', function ( $p, $id, $size, $main ) {
	if ( $main ) { $p['fetchpriority'] = 'high'; $p['loading'] = 'eager'; $p['decoding'] = 'async'; }
	return $p;
}, 10, 4 );

