<?php
/**
 * (22.11.2026 owner via «Задачи»: «Meta — оставить Facebook for WooCommerce, второй убрать»; «Brevo — трекер только после Accept»)
 * 1) Brevo web tracker (cdn.brevo.com sdk-loader + Brevo.push init → sibautomation sa.js): printed as type="text/plain"
 *    data-ss-consent — the browser does not run it; the consent script (custom.php ss_consent_*) runs it only when the
 *    visitor has ss_consent=yes or presses «Accept all». Page cache is fine: the HTML is the same for everyone.
 * 2) «Official Facebook Pixel» (Meta pixel for WordPress) — a second copy of Meta next to Facebook for WooCommerce: its
 *    code is removed from the page (one pixel, no double PageView). To remove it for good: WP admin → Plugins → deactivate.
 * Off: option ss_consent_gate_off = 1 (everything prints as before).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_cg_filter( $h ) {
	if ( false === stripos( $h, '</html>' ) ) return $h;
	/* 1) Brevo: freeze until consent */
	$h = preg_replace( '#<script\b(?=[^>]*\bsrc=["\']https://cdn\.brevo\.com/js/sdk-loader\.js)([^>]*?)\btype=["\']text/javascript["\']#i', '<script$1type="text/plain" data-ss-consent="1"', $h );
	$h = preg_replace( '#<script\b(?![^>]*type=)(?=[^>]*\bsrc=["\']https://cdn\.brevo\.com/js/sdk-loader\.js)#i', '<script type="text/plain" data-ss-consent="1"', $h );
	$h = preg_replace( '#<script type=["\']text/javascript["\']>(\s*window\.Brevo\s*=\s*window\.Brevo\s*\|\|)#', '<script type="text/plain" data-ss-consent="1">$1', $h );
	/* 2) Official Facebook Pixel plugin: its signal script, its own base code and its PageView */
	$h = preg_replace( '#<script id="facebook-signal-js-extra">.*?</script>\s*#s', '', $h );
	$h = preg_replace( '#<script id="facebook-signal-js"[^>]*></script>\s*#', '', $h );
	$h = preg_replace( "#<script type='text/javascript'>\s*!function\(f,b,e,v,n,t,s\)\{if\(f\.fbq\)return;.*?fbevents\.js'\);\s*</script>\s*#s", '', $h );
	$h = preg_replace( "#<script type='text/javascript'>\s*fbq\('track', 'PageView', \[\]\);\s*</script>\s*#", '', $h );
	$h = preg_replace( '#<noscript>\s*<img[^>]+facebook\.com/tr\?id=[^>]+>\s*</noscript>\s*#i', '', $h );
	return $h;
}
add_action( 'template_redirect', function () {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || get_option( 'ss_consent_gate_off' ) ) return;
	if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'GET' !== $_SERVER['REQUEST_METHOD'] ) return;
	ob_start( 'ss_cg_filter' );
}, 1 );
