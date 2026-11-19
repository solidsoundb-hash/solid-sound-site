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
		'areaServed' => 'Worldwide', 'url' => $url,
		'hasOfferCatalog' => array( '@type' => 'OfferCatalog', 'name' => 'Studio services',
			'itemListElement' => array( $offer( 'Online mixing', 60 ), $offer( 'Mastering', 15 ) ) ),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}, 4 );
