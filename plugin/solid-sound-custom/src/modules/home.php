<?php
/**
 * Home page (2433 and its translations): the app is delivered as normal cached files.
 *
 * Before: the page content loaded React from a CDN, downloaded a gzip+base64 text file, unpacked it in the
 * browser and patched its text on the fly, while the theme layout showed underneath.
 * Now: body class + home CSS come with the HTML (no flash of the old layout), React is self-hosted,
 * the template is pre-patched at build time (src/home/patches.js), all scripts are deferred and cached.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* Other languages (owner 17.11.2026): the home page of UA/DE/FR/ES/IT/PT shows the same new app, translated at
   build time (src/home/i18n/<lang>.json → asset home-app-<lang>). Test first: ?ss_newl=1; then for everyone
   with the option ss_home_l10n = 1. Returns the language slug, or '' (English / old page). */
function ss_home_lang() {
	static $l = null;
	if ( null !== $l ) return $l;
	$l = '';
	if ( is_admin() || ! function_exists( 'pll_current_language' ) || ! is_front_page() ) return $l;
	$s = (string) pll_current_language( 'slug' );
	if ( '' === $s || 'en' === $s || ! function_exists( 'ss_asset_has' ) || ! ss_asset_has( 'home-app-' . $s ) ) return $l;
	if ( isset( $_GET['ss_newl'] ) ? '1' === $_GET['ss_newl'] : '1' === (string) get_option( 'ss_home_l10n', '0' ) ) $l = $s;
	return $l;
}

function ss_home_is_app_page() {
	if ( is_admin() || ! is_singular( 'page' ) ) return false;
	if ( '' !== ss_home_lang() ) return true;
	$p = get_post();
	return $p && false !== strpos( $p->post_content, 'ss-newhome-app-gz' );
}

add_filter( 'body_class', function ( $c ) {
	if ( ss_home_is_app_page() ) $c[] = 'ss-newhome';
	return $c;
} );

/* the page content becomes just the mount point; everything else comes from the plugin */
add_filter( 'the_content', function ( $c ) {
	if ( '' !== ss_home_lang() && in_the_loop() && is_main_query() ) return '<div id="ss-app"></div>';
	if ( false === strpos( $c, 'ss-newhome-app-gz' ) ) return $c;
	return '<div id="ss-app"></div>';
}, 0 );

add_action( 'wp_head', function () {
	if ( ! ss_home_is_app_page() ) return;
	ss_asset( 'home-css' );
}, 5 );

add_action( 'wp_footer', function () {
	if ( ! ss_home_is_app_page() ) return;
	$app = '' !== ss_home_lang() ? 'home-app-' . ss_home_lang() : 'home-app';
	foreach ( array( 'react', 'react-dom', 'home-js', $app, 'home-ai' ) as $n ) ss_asset( $n, 'defer' );
}, 20 );
