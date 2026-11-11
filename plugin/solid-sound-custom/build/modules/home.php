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

function ss_home_is_app_page() {
	if ( is_admin() || ! is_singular( 'page' ) ) return false;
	$p = get_post();
	return $p && false !== strpos( $p->post_content, 'ss-newhome-app-gz' );
}

add_filter( 'body_class', function ( $c ) {
	if ( ss_home_is_app_page() ) $c[] = 'ss-newhome';
	return $c;
} );

/* the page content becomes just the mount point; everything else comes from the plugin */
add_filter( 'the_content', function ( $c ) {
	if ( false === strpos( $c, 'ss-newhome-app-gz' ) ) return $c;
	return '<div id="ss-app"></div>';
}, 0 );

add_action( 'wp_head', function () {
	if ( ! ss_home_is_app_page() ) return;
	ss_asset( 'home-css' );
}, 5 );

add_action( 'wp_footer', function () {
	if ( ! ss_home_is_app_page() ) return;
	foreach ( array( 'react', 'react-dom', 'home-js', 'home-app', 'home-ai' ) as $n ) ss_asset( $n, 'defer' );
}, 20 );
