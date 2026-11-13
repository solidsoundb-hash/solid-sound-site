<?php
/**
 * Site search (13.11.2026, QA bot: "search for 'techno' finds nothing").
 * Before: /?post_type=product&s=… was the shop archive → redirected to the home page (query lost);
 * /en/?s=… searched only posts/pages of the current language → products (no language) never showed.
 * Now every search (?s=…) shows one results page: products (name, description, genre, format, tags) + studio services.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_srch_services() {
	$c = home_url( '/studio-calculator/?s=' );
	return array(
		array( 'Mixing (+ mastering)', 'mixing mix mixdown stems balance mastering', $c . 'mixing', 'from €60' ),
		array( 'Mastering — stereo, stem, multitrack, album', 'mastering master loudness album stem multitrack', $c . 'mastering', 'from €15' ),
		array( 'Release Ready pack', 'release spotify beatport label distribution master', $c . 'release', 'from €109' ),
		array( 'Remix', 'remix club radio dj extended', $c . 'remix', 'from €150' ),
		array( 'Remake', 'remake cover recreate reference', $c . 'remake', 'from €120' ),
		array( 'Ghost producing', 'ghost production producer track', $c . 'ghost', 'from €300' ),
		array( 'Track review & co-producing', 'feedback review co-production coaching lesson', $c . 'copro', 'from €40' ),
		array( 'Post production: video, podcast, film', 'video podcast film youtube dialogue sound design ads reels foley', $c . 'post', 'from €40' ),
	);
}

function ss_srch_products( $q ) {
	$words = array_filter( preg_split( '/\s+/u', mb_strtolower( $q ) ), function ( $w ) { return mb_strlen( $w ) > 1; } );
	if ( ! $words ) return array();
	$ids = get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'numberposts' => 300, 'fields' => 'ids', 'lang' => '', 'suppress_filters' => false ) );
	$hits = array();
	foreach ( $ids as $id ) {
		$terms = wp_get_post_terms( $id, array( 'product_cat', 'product_tag', 'product-format', 'pa_genre', 'genre' ), array( 'fields' => 'names' ) );
		$name = mb_strtolower( get_the_title( $id ) );
		$hay = $name . ' ' . mb_strtolower( wp_strip_all_tags( get_post_field( 'post_excerpt', $id ) . ' ' . get_post_field( 'post_content', $id ) ) ) . ' ' . mb_strtolower( implode( ' ', is_wp_error( $terms ) ? array() : $terms ) );
		$score = 0;
		foreach ( $words as $w ) {
			if ( false === mb_strpos( $hay, $w ) ) { $score = 0; break; } /* every word must be found */
			$score += ( false !== mb_strpos( $name, $w ) ) ? 10 : 1;
		}
		if ( $score ) $hits[ $id ] = $score + (int) get_post_meta( $id, 'total_sales', true ) / 100;
	}
	arsort( $hits );
	return array_keys( $hits );
}

add_action( 'template_redirect', function () {
	if ( is_admin() || wp_doing_ajax() || ! isset( $_GET['s'] ) ) return;
	$q = trim( sanitize_text_field( wp_unslash( $_GET['s'] ) ) );
	global $wp_query;
	$wp_query->is_404 = false;
	status_header( 200 );
	$ids = '' === $q ? array() : ss_srch_products( $q );
	$lq = mb_strtolower( $q );
	$svc = array_filter( ss_srch_services(), function ( $s ) use ( $lq ) {
		foreach ( preg_split( '/\s+/u', $lq ) as $w ) { if ( mb_strlen( $w ) > 1 && false !== mb_strpos( mb_strtolower( $s[0] . ' ' . $s[1] ), $w ) ) return true; }
		return false;
	} );
	add_filter( 'pre_get_document_title', function () use ( $q ) { return ( '' === $q ? 'Search' : 'Search: ' . $q ) . ' | Solid Sound'; } );
	add_filter( 'wp_robots', function ( $r ) { $r['noindex'] = true; $r['follow'] = true; return $r; } );
	add_action( 'wp_head', function () {
		echo '<style id="ss-srch">.ss-srch{max-width:1200px;margin:0 auto;padding:24px 16px 40px;color:#E5E7EB}.ss-srch h1{font-size:28px;margin:0 0 6px;color:#fff}.ss-srch .sub{color:#94A3B8;margin:0 0 18px}'
			. '.ss-srch form{display:flex;gap:8px;margin:0 0 22px;max-width:560px}.ss-srch input{flex:1;min-width:0;height:46px;padding:0 14px;border-radius:10px;border:1px solid rgba(255,255,255,.12);background:#131B2E;color:#fff;font:inherit;font-size:16px}'
			. '.ss-srch button{height:46px;padding:0 18px;border-radius:10px;border:0;background:#F59E0B;color:#111;font-weight:700;cursor:pointer}'
			. '.ss-srch h2{font-size:18px;margin:22px 0 12px;color:#fff}.ss-srch .gr{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px}'
			. '.ss-srch .it{display:flex;flex-direction:column;gap:8px;padding:10px;border-radius:12px;background:#131B2E;border:1px solid rgba(255,255,255,.08);color:#E5E7EB;text-decoration:none;min-width:0}'
			. '.ss-srch .it img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:8px;display:block}.ss-srch .it b{color:#fff;font-size:15px}.ss-srch .it .price{color:#F59E0B;font-weight:700}.ss-srch .it .price del{color:#94A3B8;font-weight:400;margin-right:6px}'
			. '.ss-srch .sv{display:flex;justify-content:space-between;align-items:center;gap:10px;padding:14px;border-radius:12px;background:#131B2E;border:1px solid rgba(245,158,11,.25);color:#fff;text-decoration:none;margin:0 0 8px}.ss-srch .sv span{color:#F59E0B;font-weight:700;white-space:nowrap}'
			. '.ss-srch .none a{color:#F59E0B}@media(max-width:600px){.ss-srch .gr{grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.ss-srch h1{font-size:22px}}</style>';
	} );
	get_header();
	echo '<main id="page-content" class="l-main"><section class="l-section"><div class="l-section-h"><div class="ss-srch">';
	echo '<h1>' . ( '' === $q ? 'Search' : 'Results for “' . esc_html( $q ) . '”' ) . '</h1>';
	echo '<p class="sub">' . esc_html( count( $ids ) . ' product' . ( 1 === count( $ids ) ? '' : 's' ) . ( $svc ? ' · ' . count( $svc ) . ' studio service' . ( 1 === count( $svc ) ? '' : 's' ) : '' ) ) . '</p>';
	echo '<form role="search" action="' . esc_url( home_url( '/' ) ) . '" method="get"><label for="ss-srch-q" class="screen-reader-text">Search</label><input id="ss-srch-q" type="search" name="s" value="' . esc_attr( $q ) . '" placeholder="Genre, BPM, plugin, service…" enterkeyhint="search"><button type="submit">Search</button></form>';
	if ( $ids ) {
		echo '<h2>Products</h2><div class="gr">';
		foreach ( $ids as $id ) {
			$p = wc_get_product( $id ); if ( ! $p ) continue;
			$img = get_the_post_thumbnail_url( $id, 'woocommerce_thumbnail' );
			echo '<a class="it" href="' . esc_url( get_permalink( $id ) ) . '">' . ( $img ? '<img src="' . esc_url( $img ) . '" alt="' . esc_attr( $p->get_name() ) . '" width="300" height="300" loading="lazy">' : '' ) . '<b>' . esc_html( $p->get_name() ) . '</b><span class="price">' . wp_kses_post( $p->get_price_html() ) . '</span></a>';
		}
		echo '</div>';
	}
	if ( $svc ) {
		echo '<h2>Studio services</h2>';
		foreach ( $svc as $s ) echo '<a class="sv" href="' . esc_url( $s[2] ) . '">' . esc_html( $s[0] ) . '<span>' . esc_html( $s[3] ) . ' →</span></a>';
	}
	if ( ! $ids && ! $svc ) {
		echo '<p class="none">' . ( '' === $q ? 'Type what you are looking for.' : 'Nothing found. Try a genre (techno, house), a plugin (Serum) or see <a href="' . esc_url( home_url( '/en/all-products/' ) ) . '">all products</a> and <a href="' . esc_url( home_url( '/en/services-prices/' ) ) . '">studio services</a>.' ) . '</p>';
	}
	echo '</div></div></section></main>';
	get_footer();
	exit;
}, 1 );

/* (13.11.2026, QA bot knowledge base) link previews in Telegram / Facebook / Instagram: pages without their own image
   get the brand image (the theme prints og:image only for pages with a featured image) */
add_action( 'wp_head', function () {
	if ( is_admin() || ( is_singular() && has_post_thumbnail() ) ) return;
	$img = content_url( '/uploads/' . rawurlencode( 'Монтажная-область-0_3.png' ) );
	echo '<meta property="og:image" content="' . esc_url( $img ) . '">' . "\n" . '<meta name="twitter:card" content="summary_large_image">' . "\n";
}, 99 );
