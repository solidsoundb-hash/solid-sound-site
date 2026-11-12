<?php
/**
 * Speed (owner 12.11.2026): plugins load their files only where they are used.
 * Measured on /product/infinity/ and /en/faq/ (mobile): ~400 KB of scripts/styles that those pages never use.
 *  - WCFM (author dashboard plugin): core script, icon font CSS, styles, its jQuery UI + datepicker — only on the
 *    dashboard, vendor store pages, cart and checkout.
 *  - WordPress' old audio player (mediaelement, ~170 KB): our player builds on the plain <audio> tag.
 *  - Brevo plugin front script: our forms send to Brevo on the server; kept only where a Brevo form shortcode is used.
 * Admins see everything as before when they add ?ss_perf=0 to a URL (quick check if something looks broken).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_perf_on() {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) return false;
	if ( isset( $_GET['ss_perf'] ) && '0' === $_GET['ss_perf'] ) return false;
	return true;
}
function ss_perf_wcfm_needed() {
	if ( function_exists( 'is_wcfm_page' ) && is_wcfm_page() ) return true;
	if ( function_exists( 'wcfmmp_is_store_page' ) && wcfmmp_is_store_page() ) return true;
	if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) return true;
	$p = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
	return (bool) preg_match( '#/(store-manager|add-product|vendor-register|store/|sell)#', $p );
}

/* WordPress audio shortcode: plain <audio> (our player replaces it) — no mediaelement library */
add_filter( 'wp_audio_shortcode_library', function ( $lib ) { return ss_perf_on() ? 'none' : $lib; } );

add_action( 'wp_enqueue_scripts', function () {
	if ( ! ss_perf_on() ) return;
	$wcfm = ss_perf_wcfm_needed();
	$brevo = false;
	if ( is_singular() ) { $c = (string) get_post_field( 'post_content', get_queried_object_id() ); $brevo = false !== strpos( $c, '[sibwp_form' ) || false !== strpos( $c, 'sib-form' ); }
	foreach ( array( wp_scripts(), wp_styles() ) as $reg ) {
		foreach ( (array) $reg->queue as $h ) {
			$src = isset( $reg->registered[ $h ] ) ? (string) $reg->registered[ $h ]->src : '';
			$drop = false;
			if ( ! $wcfm && preg_match( '#/plugins/(wc-frontend-manager|wc-multivendor-marketplace|wc-multivendor-membership)/#', $src ) ) $drop = true;
			if ( ! $wcfm && in_array( $h, array( 'jquery-ui-datepicker', 'jquery-ui-style', 'wcfm_jquery_ui_css' ), true ) ) $drop = true;
			if ( ! $wcfm && false !== strpos( $src, 'jquery-ui' ) && false !== strpos( $src, '.css' ) ) $drop = true;
			if ( ! $brevo && false !== strpos( $src, '/plugins/mailin/' ) ) $drop = true;
			if ( in_array( $h, array( 'wp-mediaelement', 'mediaelement' ), true ) ) $drop = true;
			if ( $drop ) { $reg instanceof WP_Scripts ? wp_dequeue_script( $h ) : wp_dequeue_style( $h ); }
		}
	}
}, 9999 );
