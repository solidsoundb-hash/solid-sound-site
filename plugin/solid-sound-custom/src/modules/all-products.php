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
	$h = ss_h3_render( 'new' );
	/* owner 21.11: «What's new» = one product per slide (modules/new-slider.php) instead of the 3-card carousel */
	if ( function_exists( 'ss_ns_render' ) ) { $a = strpos( $h, '<div class="ssh-cols">' ); $b = strpos( $h, '</div></section>' );
		if ( false !== $a && false !== $b && $b > $a ) { $ns = ss_ns_render(); if ( $ns ) $h = substr( $h, 0, $a ) . $ns . '</div>' . substr( $h, $b ); } }
	/* 26.11 (QA swarm: 0 visible H1 after the 25.11 SEO change): the page's own <h1> sits in the old hero section, which is hidden
	   (custom.php ssh-css, `.l-section:has(.type_carousel):has(a[href="#templates"])`) — so the visible banner title is the one H1,
	   and the hidden old one becomes a div. Same look (the banner keeps class ssh-h1; the old block is not shown). */
	$h = preg_replace( '~<div class="ssh-h1">(.*?)</div>~s', '<h1 class="ssh-h1">$1</h1>', $h, 1 );
	$c = preg_replace( '~<h1(\s[^>]*)?>(.*?)</h1>~s', '<div class="ss-old-h1"$1>$2</div>', $c );
	return '<div id="ss-old-hero">' . $h . '</div>' . $c;
}, 0 );
