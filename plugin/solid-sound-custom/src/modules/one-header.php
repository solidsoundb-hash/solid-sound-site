<?php
/**
 * (20.11.2026 owner: «а что, одну шапку нельзя с подвалом?») The home page uses the SAME header, footer and phone bottom bar as
 * every other page (src/ss-global.js + modules/footer.php) instead of its own copies inside the home app — one place to change,
 * nothing differs any more (the Forum icon, the language button…).
 * Preview first: add ?ss_one=1 to the home address. For everyone: option ss_one_header = 1 (after the owner's «да»).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_one_on() {
	static $on = null;
	if ( null === $on ) $on = function_exists( 'ss_home_is_app_page' ) && ss_home_is_app_page() && ( get_option( 'ss_one_header' ) || ! empty( $_GET['ss_one'] ) );
	return $on;
}
add_filter( 'body_class', function ( $c ) { if ( ss_one_on() ) $c[] = 'ss-one'; return $c; } );
add_action( 'wp_head', function () {
	if ( ! ss_one_on() ) return;
	/* the same as every other page (custom.php «Global menu»): the shared menu file + its reserved place, no jump */
	echo '<script>window.__ssg=1;window.__sslb=1;</script>';
	$promo = time() < strtotime( '2026-11-30 23:59:59 +0100' ) ? 36 : 0;
	echo '<style id="ss-one">.l-header,.l-subheader,.w-toplink{display:none!important}'
		. '.l-canvas:not(:has(> #ssg))::before{content:"";display:block;height:' . ( 73 + $promo ) . 'px}'
		. '@media(max-width:760px){.l-canvas:not(:has(> #ssg))::before{height:61px}}'
		/* the home app's own header, footer and phone bottom bar step aside */
		. 'body.ss-one #ss-app header,body.ss-one #ss-app footer,body.ss-one #ss-app nav[aria-label="App"]{display:none!important}'
		/* its own sale strip too — the shared one stays (it was shown twice) */
		. 'body.ss-one #ss-app a[href="#shop"][style*="min-height: 36px"]{display:none!important}'
		. 'body.ss-one #ss-app{padding-top:0!important}</style>';
	if ( function_exists( 'ss_asset' ) ) ss_asset( 'ss-global', 'defer' );
}, 2 );
