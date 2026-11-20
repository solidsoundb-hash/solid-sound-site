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
	if ( false === strpos( (string) $src, 'tidio.co' ) ) return $tag;
	return ss_lite_no_tidio() ? '' : ss_lite_late( $tag );
}, 99, 3 );
/* (20.11 speed robot: Tidio = ~450 KB of scripts + fonts and the phone's processor busy on EVERY page) while Tidio is used
   (Telegram not set up) it loads at the visitor's first touch / scroll / key / mouse move — or after 10 s — not with the page */
function ss_lite_late( $html ) {
	$GLOBALS['ss_lite_late'] = 1;
	$html = preg_replace( '#<script\b([^>]*)\bsrc=(["\'])([^"\']*code\.tidio\.co/[^"\']+)\2([^>]*)>#i', '<script type="text/plain" data-ss-late="$3"$1$4>', $html );
	/* the Tidio plugin's own inline loader («on window load → add the script») waits for the first action instead */
	return str_replace( array( 'window.addEventListener("load", asyncLoad, false);', "window.addEventListener('load', asyncLoad, false);" ), 'window.ssLate?ssLate(asyncLoad):window.addEventListener("load", asyncLoad, false);', $html );
}
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
/* while Tidio is used, its loader (printed at the very end of the page, after wp_footer) waits for the first action: the whole
   page passes through here once — only a text replace when the loader is there */
add_action( 'template_redirect', function () {
	if ( is_admin() || wp_doing_ajax() || ss_lite_no_tidio() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) return;
	ob_start( function ( $h ) {
		if ( false === strpos( $h, 'tidio' ) || false === stripos( $h, '</body>' ) ) return $h . ( strlen( $h ) > 2000 ? '<!-- ss-late: ' . ( false === strpos( $h, 'tidio' ) ? 'no-tidio' : 'no-body' ) . ' -->' : '' );
		$h = ss_lite_late( $h );
		if ( false === strpos( $h, 'ssLate(asyncLoad)' ) && false === strpos( $h, 'data-ss-late=' ) ) return $h . '<!-- ss-late: no-loader-match -->';
		/* ssLate(fn): run fn at the first touch / scroll / key / mouse move, or after 10 s; also wakes the delayed script tags */
		$js = '<script>(function(){var q=[],d=0,E=["pointerdown","keydown","scroll","touchstart","mousemove"];function go(){if(d)return;d=1;document.querySelectorAll("script[data-ss-late]").forEach(function(o){var s=document.createElement("script");s.src=o.getAttribute("data-ss-late");s.async=true;o.parentNode.replaceChild(s,o)});q.forEach(function(f){try{f()}catch(e){}})}window.ssLate=function(f){d?f():q.push(f)};E.forEach(function(n){addEventListener(n,go,{capture:true,passive:true,once:true})});setTimeout(go,10000)})();</script>';
		return preg_replace( '#<body\b[^>]*>#i', '$0' . $js, $h, 1 );
	} );
}, 1 );

/* ── Creator area (owner 19.11: «всё, что касается авторов, тяжело грузится») ──
 *  - WCFM's old dashboard (sales/commission reports for the month + Chart.js) was built on /store-manager/ and then hidden
 *    behind our Creator space. It is not built at all now, and its chart scripts are not loaded.
 *  - WCFM's floating "quick add product" popup (the teal cube, ~40 database queries + its form on every creator page) is off;
 *    products are added on our Add Product page. */
add_filter( 'wcfm_is_pref_product_popup', '__return_false' );
/* owner 19.11 «убери всё ненужное, а не скрывай»: WCFM's old side menu is not printed at all while our tab row is there */
add_filter( 'wcfm_is_allow_show_menu', function ( $m ) { return ( function_exists( 'ss_cs_ok' ) && ss_cs_ok() ) ? false : $m; }, 99 );
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

/* Add Product: the only rich-text field (MP3 fallback) is plain text now — TinyMCE is not loaded there */
add_action( 'wp_print_scripts', function () {
	if ( ! function_exists( 'ss_cs_ok' ) || ! ss_cs_ok() || false === strpos( (string) ( $_SERVER['REQUEST_URI'] ?? '' ), 'ss-add-product' ) ) return;
	foreach ( array( 'wp-tinymce', 'wp-tinymce-root', 'wp-tinymce-lists' ) as $h ) wp_dequeue_script( $h );
}, 3 );
