<?php
/**
 * /all-products/ (page 2 and translations): the hero comes with the HTML.
 * Before: a script in the page content downloaded the whole home page (/en/?hero=1), cut the hero out
 * and inserted it at the top after load -> big layout jump (CLS ~1.0) and slow main content (LCP 10 s+).
 * Now: the same hero (ss_h3_render) is printed by the server at the same place; the script is dropped.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'the_content', function ( $c ) {
	if ( false === strpos( $c, 'ssHero1' ) || ! function_exists( 'ss_h3_render' ) ) return $c;
	$c = preg_replace( '#<script>\(function\(\)\{var K=\'ssHero1\'.*?</script>#s', '', $c );
	return '<div id="ss-old-hero">' . ss_h3_render() . '</div>' . $c;
}, 0 );
