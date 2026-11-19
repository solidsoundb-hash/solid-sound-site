<?php
/**
 * Speed: files the browser downloads for nothing (owner 19.11.2026: «облегчи всё, мёртвый код удали, тяжело грузится»).
 * Measured by the robot tools/sm-speed.js (checks/sm-speed/report.md).
 *  - Tidio chat (~1 MB of scripts on EVERY page): our own chat button talks to the team through Telegram; Tidio is only
 *    used when Telegram is not set up (then our chat offers "Talk to a person" via Tidio). While Telegram works, Tidio's
 *    script is not loaded at all (it was always hidden by CSS anyway). Telegram off → Tidio comes back by itself.
 * Off: define( 'SS_SPEED_LITE_OFF', true ) in wp-config.php.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
if ( defined( 'SS_SPEED_LITE_OFF' ) && SS_SPEED_LITE_OFF ) return;

function ss_lite_no_tidio() {
	static $r = null;
	if ( null === $r ) $r = ! is_admin() && function_exists( 'ss_chat_ready' ) && ss_chat_ready();
	return $r;
}
add_filter( 'script_loader_tag', function ( $tag, $handle, $src ) {
	return ( false !== strpos( (string) $src, 'tidio.co' ) && ss_lite_no_tidio() ) ? '' : $tag;
}, 99, 3 );
add_action( 'wp_print_scripts', function () {
	if ( ! ss_lite_no_tidio() ) return;
	global $wp_scripts;
	if ( ! $wp_scripts ) return;
	foreach ( (array) $wp_scripts->registered as $h => $s ) if ( ! empty( $s->src ) && false !== strpos( $s->src, 'tidio' ) ) wp_dequeue_script( $h );
}, 1 );
/* the Tidio plugin may also print its loader directly into the footer: catch only that one tag */
add_action( 'wp_footer', function () { if ( ss_lite_no_tidio() ) ob_start(); }, -9999 );
add_action( 'wp_footer', function () {
	if ( ! ss_lite_no_tidio() ) return;
	$h = ob_get_clean();
	echo preg_replace( '#<script\b[^>]*src=["\'][^"\']*code\.tidio\.co/[^"\']+["\'][^>]*>\s*</script>#i', '', (string) $h );
}, 9999 );

/* ── Creator area (owner 19.11: «всё, что касается авторов, тяжело грузится») ──
 *  - WCFM's old dashboard (sales/commission reports for the month + Chart.js) was built on /store-manager/ and then hidden
 *    behind our Creator space. It is not built at all now, and its chart scripts are not loaded.
 *  - WCFM's floating "quick add product" popup (the teal cube, ~40 database queries + its form on every creator page) is off;
 *    products are added on our Add Product page. */
add_filter( 'wcfm_is_pref_product_popup', '__return_false' );
function ss_lite_cs_root() { return function_exists( 'ss_cs_ok' ) && ss_cs_ok() && function_exists( 'ss_cs_is_root' ) && ss_cs_is_root(); }
add_filter( 'wcfm_locate_template', function ( $t, $name ) {
	if ( 0 === strpos( (string) $name, 'dashboard/wcfm-view-' ) && false === strpos( $name, 'welcome' ) && ss_lite_cs_root() ) return __DIR__ . '/wcfm-empty-view.php';
	if ( 0 === strpos( (string) $name, 'dashboard/wcfm-view-dashboard-welcome-box' ) && ss_lite_cs_root() ) return __DIR__ . '/wcfm-empty-view.php';
	return $t;
}, 99, 2 );
add_action( 'wp_print_scripts', function () {
	if ( ! ss_lite_cs_root() ) return;
	global $wp_scripts;
	if ( ! $wp_scripts ) return;
	foreach ( (array) $wp_scripts->queue as $h ) {
		$s = isset( $wp_scripts->registered[ $h ] ) ? (string) $wp_scripts->registered[ $h ]->src : '';
		if ( false !== strpos( $s, '/chart-js/' ) || false !== strpos( $s, 'dashboard/wcfm-script-dashboard' ) ) wp_dequeue_script( $h );
	}
}, 2 );
