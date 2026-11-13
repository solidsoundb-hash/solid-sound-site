<?php
/**
 * SEO fallbacks (13.11.2026, QA bot: pages with no meta description / no H1).
 * Only fills the gaps — never overrides a description that ss_seo_current() already prints.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_head', function () {
	if ( is_admin() ) return;
	/* ss_seo_current() already printed a description for mapped pages/products — don't duplicate */
	$has = function_exists( 'ss_seo_current' ) ? ss_seo_current() : null;
	if ( $has && ! empty( $has[1] ) ) return;

	$d = '';
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		$d = 'Your cart at Solid Sound: royalty-free Ableton templates, Serum presets and music. Instant download after a secure checkout.';
	} elseif ( function_exists( 'is_checkout' ) && is_checkout() ) {
		$d = 'Secure checkout at Solid Sound. Pay by card; get your files instantly after purchase. Royalty-free for your releases.';
	} elseif ( function_exists( 'is_account_page' ) && is_account_page() ) {
		$d = 'Your Solid Sound account: orders, downloads and studio projects in one place.';
	} elseif ( is_singular() ) {
		$id = get_queried_object_id();
		$d  = wp_strip_all_tags( get_post_field( 'post_excerpt', $id ) ?: get_post_field( 'post_content', $id ) );
	}
	if ( ! $d ) $d = 'Solid Sound — royalty-free Ableton Live templates, Serum presets, music for video, and online mixing & mastering by our engineers.';
	$d = trim( preg_replace( '/\s+/', ' ', $d ) );
	if ( mb_strlen( $d ) > 160 ) $d = mb_substr( $d, 0, 157 ) . '…';
	echo '<meta name="description" content="' . esc_attr( $d ) . '">' . "\n";
}, 3 );
