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
		$id  = get_queried_object_id();
		$raw = get_post_field( 'post_excerpt', $id );
		if ( ! $raw ) $raw = get_post_field( 'post_content', $id );
		$raw = preg_replace( '/\[[^\]]*\]/', ' ', (string) $raw );   /* drop [vc_row], [shortcodes] and page-builder markup */
		$d   = wp_strip_all_tags( strip_shortcodes( $raw ) );
		if ( mb_strlen( trim( $d ) ) < 40 ) $d = '';                 /* builder page with no real text -> brand default */
	}
	if ( ! $d ) $d = 'Solid Sound — royalty-free Ableton Live templates, Serum presets, music for video, and online mixing & mastering by our engineers.';
	$d = trim( preg_replace( '/\s+/', ' ', $d ) );
	if ( mb_strlen( $d ) > 160 ) $d = mb_substr( $d, 0, 157 ) . '…';
	echo '<meta name="description" content="' . esc_attr( $d ) . '">' . "\n";
}, 3 );

/* 19.11.2026 (analytics report): the prices page and the studio calculator had no structured data, so Google and AI search
   could not read the studio offer. Service markup with the same "from" prices the page itself shows (60 € mixing, 15 € mastering). */
add_action( 'wp_head', function () {
	if ( is_admin() || ! is_page() ) return;
	$slug = strtolower( (string) get_post_field( 'post_name', get_queried_object_id() ) );
	if ( ! in_array( $slug, array( 'services-prices', 'studio-calculator' ), true ) ) return;
	$url = get_permalink();
	$offer = function ( $name, $from ) use ( $url ) {
		return array( '@type' => 'Offer', 'name' => $name, 'url' => $url, 'priceCurrency' => 'EUR',
			'priceSpecification' => array( '@type' => 'PriceSpecification', 'minPrice' => $from, 'priceCurrency' => 'EUR' ) );
	};
	$data = array(
		'@context' => 'https://schema.org', '@type' => 'Service',
		'name' => 'Online mixing & mastering', 'serviceType' => 'Audio mixing and mastering',
		'provider' => array( '@type' => 'Organization', 'name' => 'Solid Sound', 'url' => home_url( '/' ) ),
		'areaServed' => 'Worldwide', 'url' => $url, 'availableLanguage' => array( 'English', 'German', 'French', 'Spanish', 'Italian', 'Portuguese', 'Ukrainian' ),
		'hasOfferCatalog' => array( '@type' => 'OfferCatalog', 'name' => 'Studio services',
			/* 21.11: the current «from» prices of the page tabs (mid-segment prices of 21.11) */
			'itemListElement' => array( $offer( 'Online mixing', 69 ), $offer( 'Mastering', 35 ), $offer( 'Mix + master bundle', 99 ), $offer( 'Remix', 190 ), $offer( 'Remake', 120 ), $offer( 'Custom production', 350 ), $offer( 'Post production (voice, ads, film)', 90 ), $offer( 'Release: cover art & distribution', 49 ) ) ),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}, 4 );

/* 21.11.2026 owner «поработать над SEO-выдачей»: breadcrumbs for Google (helps it see the sections → sitelinks, and shows
   «solid-sound.net › All products › …» instead of a bare URL). Products: Home › All products › product; pages/posts: Home › page. */
add_action( 'wp_head', function () {
	if ( is_admin() || is_front_page() || is_404() || is_search() ) return;
	if ( function_exists( 'is_wpforo_page' ) && is_wpforo_page() ) return;
	$home  = function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
	$items = array( array( 'Solid Sound', $home ) );
	if ( function_exists( 'is_product' ) && is_product() ) {
		$all = get_page_by_path( 'all-products' ); $aid = $all ? $all->ID : 0;
		if ( $aid && function_exists( 'pll_get_post' ) && pll_get_post( $aid ) ) $aid = pll_get_post( $aid );
		$items[] = array( 'All products', $aid ? get_permalink( $aid ) : home_url( '/all-products/' ) );
		$items[] = array( wp_strip_all_tags( get_the_title() ), get_permalink() );
	} elseif ( is_singular() ) {
		$id = get_queried_object_id();
		foreach ( array_reverse( get_post_ancestors( $id ) ) as $a ) $items[] = array( wp_strip_all_tags( get_the_title( $a ) ), get_permalink( $a ) );
		$items[] = array( wp_strip_all_tags( get_the_title( $id ) ), get_permalink( $id ) );
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$t = get_queried_object(); if ( ! $t || empty( $t->name ) ) return;
		$l = get_term_link( $t ); if ( is_wp_error( $l ) ) return;
		$items[] = array( $t->name, $l );
	} else {
		return;
	}
	$list = array();
	foreach ( $items as $it ) if ( '' !== trim( (string) $it[0] ) ) $list[] = array( '@type' => 'ListItem', 'position' => count( $list ) + 1, 'name' => $it[0], 'item' => $it[1] );
	if ( count( $list ) < 2 ) return;
	echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $list ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}, 5 );
