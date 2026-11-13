<?php
/**
 * Black Month sale (owner 13.11.2026: "включи скидку"): −50% on Solid Sound's own Ableton templates and Serum presets
 * until 30.11.2026 23:59 (CET) — exactly what the top banner promises. Music and authors' products keep their price.
 * Works by price filters: no product is edited, nothing to undo after the end date.
 * Admins can preview the normal prices with ?ss_sale=0.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'SS_SALE_END', '2026-11-30 23:59:59 +0100' );
define( 'SS_SALE_OFF', 0.5 );

function ss_sale_on() {
	if ( time() > strtotime( SS_SALE_END ) ) return false;
	if ( isset( $_GET['ss_sale'] ) && '0' === $_GET['ss_sale'] && current_user_can( 'manage_woocommerce' ) ) return false;
	return true;
}
/* templates and presets only (format "music" = no sale); only our own products, not authors' */
function ss_sale_applies( $product ) {
	static $memo = array();
	$id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
	if ( isset( $memo[ $id ] ) ) return $memo[ $id ];
	$fmt = wp_get_post_terms( $id, 'product-format', array( 'fields' => 'slugs' ) );
	$fmt = is_wp_error( $fmt ) ? array() : $fmt;
	$ok = ! in_array( 'music', $fmt, true );
	if ( $ok && function_exists( 'ss_bonus_is_owner_product' ) ) $ok = ss_bonus_is_owner_product( $id );
	if ( $ok ) $ok = (float) $product->get_regular_price( 'edit' ) > 0;
	return $memo[ $id ] = $ok;
}
function ss_sale_price( $product ) {
	return round( (float) $product->get_regular_price( 'edit' ) * ( 1 - SS_SALE_OFF ), 2 );
}

foreach ( array( 'woocommerce_product_get_price', 'woocommerce_product_variation_get_price' ) as $h ) {
	add_filter( $h, function ( $price, $product ) {
		if ( ! ss_sale_on() || ! ss_sale_applies( $product ) ) return $price;
		$own = $product->get_sale_price( 'edit' );
		$s = ss_sale_price( $product );
		return ( '' !== $own && (float) $own < $s ) ? $own : $s; /* never raise a lower existing sale price */
	}, 20, 2 );
}
foreach ( array( 'woocommerce_product_get_sale_price', 'woocommerce_product_variation_get_sale_price' ) as $h ) {
	add_filter( $h, function ( $sale, $product ) {
		if ( ! ss_sale_on() || ! ss_sale_applies( $product ) ) return $sale;
		$s = ss_sale_price( $product );
		return ( '' !== $sale && (float) $sale < $s ) ? $sale : $s;
	}, 20, 2 );
}
add_filter( 'woocommerce_product_is_on_sale', function ( $on, $product ) {
	return ( ss_sale_on() && ss_sale_applies( $product ) ) ? true : $on;
}, 20, 2 );
/* variable products: price ranges are cached by WooCommerce — make the cache key depend on the sale */
add_filter( 'woocommerce_get_variation_prices_hash', function ( $hash ) {
	$hash[] = ss_sale_on() ? 'ss-bm' : 'ss-reg';
	return $hash;
} );
/* cached lists that store prices (home "Best products") are rebuilt once when the sale starts / ends */
add_action( 'init', function () {
	$v = ss_sale_on() ? 'on' : 'off';
	if ( get_option( 'ss_sale_state' ) === $v ) return;
	update_option( 'ss_sale_state', $v, false );
	delete_transient( 'ss_rk_home' );
	delete_transient( 'ss_rt_cache' );
} );
/* product page: a short line under the price */
add_action( 'woocommerce_single_product_summary', function () {
	global $product;
	if ( ! $product || ! ss_sale_on() || ! ss_sale_applies( $product ) ) return;
	echo '<div class="ss-bm" style="margin:-4px 0 12px;font-size:13px;color:#F59E0B;font-weight:700">Black Month −50% · until 30 November</div>';
}, 11 );
