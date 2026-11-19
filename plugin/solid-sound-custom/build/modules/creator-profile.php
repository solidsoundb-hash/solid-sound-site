<?php
/**
 * (19.11.2026 owner: «View my profile ведёт на all products… что за колхоз, сделай всё круто»)
 * Public creator page /store/<slug>/ in the site style. WCFM's own store template does not work with our theme
 * (it answered 404), so we print our own page: cover, photo, name, country, bio, music links, share, products.
 * Data: WCFM profile settings (wcfmmp_profile_settings) — the creator fills them in Creator space → Profile.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_cpf_user() {
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
	$base = function_exists( 'wcfm_get_option' ) ? wcfm_get_option( 'wcfm_store_url', 'store' ) : 'store';
	if ( ! preg_match( '#^(?:[a-z]{2}/)?' . preg_quote( $base, '#' ) . '/([^/]+)(?:/page/\d+)?/?$#', $path, $m ) ) return null;
	$u = get_user_by( 'slug', sanitize_title( urldecode( $m[1] ) ) );
	if ( ! $u ) return false;
	$is = ( function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor( $u->ID ) ) || user_can( $u, 'manage_woocommerce' );
	return $is ? $u : false;
}
function ss_cpf_img( $v, $size ) {
	if ( ! $v ) return '';
	if ( is_numeric( $v ) ) { $u = wp_get_attachment_image_url( (int) $v, $size ); return $u ? $u : ''; }
	return esc_url_raw( (string) $v );
}

add_action( 'template_redirect', function () {
	if ( is_admin() || wp_doing_ajax() ) return;
	$u = ss_cpf_user();
	if ( null === $u ) return;
	if ( false === $u ) { global $wp_query; $wp_query->set_404(); status_header( 404 ); return; }
	global $wp_query;
	$wp_query->is_404 = false;
	status_header( 200 );
	$id   = $u->ID;
	$pr   = function_exists( 'ss_cp_prof' ) ? ss_cp_prof( $id ) : array();
	$name = ! empty( $pr['store_name'] ) ? $pr['store_name'] : $u->display_name;
	$bio  = ! empty( $pr['shop_description'] ) ? wp_kses_post( wpautop( $pr['shop_description'] ) ) : '';
	$ava  = ss_cpf_img( $pr['gravatar'] ?? '', 'thumbnail' );
	if ( ! $ava ) $ava = get_avatar_url( $id, array( 'size' => 240, 'default' => '404' ) );
	$cov  = ss_cpf_img( $pr['banner'] ?? '', 'full' );
	$cc   = strtoupper( (string) ( $pr['address']['country'] ?? '' ) );
	$country = ( $cc && function_exists( 'WC' ) && isset( WC()->countries->countries[ $cc ] ) ) ? WC()->countries->countries[ $cc ] : '';
	$ids  = get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'author' => $id, 'numberposts' => 60, 'fields' => 'ids', 'lang' => '', 'suppress_filters' => false ) );
	$own  = get_current_user_id() === $id;
	$url  = home_url( '/' . ( function_exists( 'wcfm_get_option' ) ? wcfm_get_option( 'wcfm_store_url', 'store' ) : 'store' ) . '/' . $u->user_nicename . '/' );
	/* music links: whatever the creator filled in */
	$NET = array( 'soundcloud' => 'SoundCloud', 'spotify' => 'Spotify', 'youtube' => 'YouTube', 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'twitter' => 'X', 'fb' => 'Facebook', 'facebook' => 'Facebook', 'beatport' => 'Beatport', 'bandcamp' => 'Bandcamp', 'website' => 'Website', 'pinterest' => 'Pinterest', 'linkdin' => 'LinkedIn' );
	$links = array();
	foreach ( (array) ( $pr['social'] ?? array() ) as $k => $v ) {
		$v = trim( (string) $v ); if ( '' === $v ) continue;
		if ( ! preg_match( '#^https?://#i', $v ) ) $v = 'https://' . ltrim( $v, '/@' );
		$links[] = array( $NET[ $k ] ?? ucfirst( $k ), $v );
	}
	$fl = array( 'templates' => 'Template', 'presets' => 'Presets', 'music' => 'Music', 'sample-packs' => 'Samples' );

	add_filter( 'pre_get_document_title', function () use ( $name ) { return $name . ' — creator on Solid Sound'; }, 99999 );
	add_filter( 'body_class', function ( $c ) { return array_values( array_diff( $c, array( 'error404' ) ) ); } );
	add_action( 'wp_head', function () use ( $name, $bio, $ava, $url ) {
		echo '<meta property="og:title" content="' . esc_attr( $name . ' on Solid Sound' ) . '"><meta property="og:description" content="' . esc_attr( wp_trim_words( wp_strip_all_tags( $bio ), 30 ) ) . '">' . ( $ava ? '<meta property="og:image" content="' . esc_url( $ava ) . '">' : '' ) . '<link rel="canonical" href="' . esc_url( $url ) . '">';
		echo '<style id="ss-cpf">.ss-cpf{max-width:1200px;margin:0 auto;padding:18px 16px 56px;color:#E2E8F0;font-family:"Exo 2",system-ui,sans-serif}'
			. '.ss-cpf .cov{position:relative;height:clamp(150px,24vw,280px);border-radius:20px;overflow:hidden;background:radial-gradient(80% 120% at 20% 0%,rgba(59,130,246,.45),transparent 60%),radial-gradient(70% 120% at 100% 100%,rgba(245,158,11,.35),transparent 60%),linear-gradient(180deg,#0e1a36,#0a0f1f);border:1px solid rgba(255,255,255,.07)}'
			. '.ss-cpf .cov img{width:100%;height:100%;object-fit:cover;display:block}.ss-cpf .cov:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,transparent 45%,rgba(7,10,18,.75))}'
			. '.ss-cpf .hd{display:flex;align-items:flex-end;gap:22px;margin:-64px 0 0;padding:0 28px;position:relative;z-index:1}'
			. '.ss-cpf .av{flex:none;width:132px;height:132px;border-radius:50%;border:4px solid #0b1226;background:#1A2540 center/cover no-repeat;box-shadow:0 12px 30px rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center;color:#F59E0B;font:800 48px "Exo 2",sans-serif}'
			. '.ss-cpf .who{flex:1;min-width:0;padding-bottom:8px}.ss-cpf h1{margin:0;color:#fff;font:800 clamp(26px,3.4vw,40px)/1.1 "Exo 2",sans-serif;overflow-wrap:anywhere}'
			. '.ss-cpf .meta{display:flex;flex-wrap:wrap;gap:8px;margin:10px 0 0}.ss-cpf .chip{white-space:nowrap;display:inline-flex;align-items:center;gap:6px;height:30px;padding:0 12px;border-radius:999px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);color:#CBD5E1;font-size:13.5px}.ss-cpf .chip.cr{background:rgba(245,158,11,.12);border-color:rgba(245,158,11,.35);color:#FBBF24;font-weight:700}'
			. '.ss-cpf .acts{display:flex;gap:10px;padding-bottom:10px}.ss-cpf .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;height:44px;padding:0 18px;border-radius:12px;border:1px solid rgba(255,255,255,.14);background:#1A2540;color:#fff!important;font:700 14.5px "Exo 2",sans-serif;text-decoration:none!important;cursor:pointer;white-space:nowrap}.ss-cpf .btn:hover{border-color:#F59E0B}.ss-cpf .btn.a{background:#F59E0B;border-color:#F59E0B;color:#111!important}'
			. '.ss-cpf .own{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:0 0 14px;padding:10px 14px;border-radius:12px;background:rgba(52,211,153,.08);border:1px solid rgba(52,211,153,.3);color:#A7F3D0;font-size:14px}.ss-cpf .own a{color:#34D399!important;font-weight:700;white-space:nowrap}'
			. '.ss-cpf .body{display:grid;grid-template-columns:minmax(0,1fr) 320px;gap:24px;margin:28px 0 0}.ss-cpf .bio{color:#CBD5E1;font:400 16px/1.7 Roboto,sans-serif}.ss-cpf .bio p{margin:0 0 10px}'
			. '.ss-cpf .side{display:flex;flex-direction:column;gap:10px}.ss-cpf .card{padding:18px;border-radius:16px;background:#131B2E;border:1px solid rgba(255,255,255,.07)}.ss-cpf .card h2{margin:0 0 12px;color:#fff;font:700 16px "Exo 2",sans-serif}'
			. '.ss-cpf .lk{display:flex;align-items:center;justify-content:space-between;height:42px;padding:0 14px;margin:0 0 8px;border-radius:10px;background:#1A2540;color:#E2E8F0!important;text-decoration:none!important;font-weight:600}.ss-cpf .lk:hover{color:#F59E0B!important}.ss-cpf .lk:after{content:"↗";color:#F59E0B}'
			. '.ss-cpf h2.t{margin:36px 0 14px;color:#fff;font:800 24px "Exo 2",sans-serif}.ss-cpf h2.t small{margin-left:8px;color:#94A3B8;font:500 15px Roboto,sans-serif}'
			. '.ss-cpf .gr{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px}.ss-cpf .it{display:flex;flex-direction:column;border-radius:16px;overflow:hidden;background:#131B2E;border:1px solid rgba(255,255,255,.07);color:#E2E8F0!important;text-decoration:none!important;transition:transform .15s,border-color .15s}.ss-cpf .it:hover{transform:translateY(-3px);border-color:rgba(245,158,11,.5)}'
			. '.ss-cpf .it img{width:100%;aspect-ratio:1;object-fit:cover;display:block;background:#0b1226}.ss-cpf .it .tx{display:flex;flex-direction:column;gap:6px;padding:12px 14px 14px}.ss-cpf .it .f{color:#94A3B8;font-size:12.5px;letter-spacing:.06em;text-transform:uppercase}.ss-cpf .it b{color:#fff;font:700 16px/1.3 "Exo 2",sans-serif}.ss-cpf .it .p{color:#F59E0B;font-weight:800}.ss-cpf .it .p del{color:#64748B;font-weight:400;margin-right:6px}'
			. '.ss-cpf .none{padding:28px;border-radius:16px;background:#131B2E;border:1px dashed rgba(255,255,255,.14);color:#94A3B8;text-align:center}'
			. '@media(max-width:900px){.ss-cpf .body{grid-template-columns:1fr}.ss-cpf .hd{flex-wrap:wrap;padding:0 12px;gap:14px}.ss-cpf .av{width:104px;height:104px;font-size:38px}.ss-cpf .acts{width:100%}.ss-cpf .acts .btn{flex:1}}'
			. '@media(max-width:600px){.ss-cpf .hd{flex-direction:column;align-items:center;text-align:center;margin-top:-56px}.ss-cpf .who{padding:0}.ss-cpf .meta{justify-content:center}.ss-cpf .own{flex-direction:column;align-items:flex-start;gap:6px}.ss-cpf{padding-top:10px}.ss-cpf .cov{border-radius:14px}.ss-cpf .hd{margin-top:-52px}.ss-cpf .gr{grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.ss-cpf .it .tx{padding:10px}.ss-cpf .it b{font-size:14.5px}}</style>';
	} );
	get_header();
	echo '<main id="page-content" class="l-main"><section class="l-section"><div class="l-section-h"><div class="ss-cpf">';
	if ( $own ) echo '<div class="own"><span>This is your public page. Buyers see exactly this.</span><a href="' . esc_url( function_exists( 'ss_cp_url' ) ? ss_cp_url( 'ss-settings' ) : home_url( '/store-manager/' ) ) . '">Edit profile →</a></div>';
	echo '<div class="cov">' . ( $cov ? '<img src="' . esc_url( $cov ) . '" alt="" fetchpriority="high">' : '' ) . '</div>';
	echo '<div class="hd"><div class="av"' . ( $ava ? ' style="background-image:url(' . esc_url( $ava ) . ')"' : '' ) . ' role="img" aria-label="' . esc_attr( $name ) . '">' . ( $ava ? '' : esc_html( mb_strtoupper( mb_substr( $name, 0, 1 ) ) ) ) . '</div>';
	echo '<div class="who"><h1>' . esc_html( $name ) . '</h1><div class="meta"><span class="chip cr">Creator</span>' . ( $country ? '<span class="chip">📍 ' . esc_html( $country ) . '</span>' : '' ) . '<span class="chip">' . count( $ids ) . ' product' . ( 1 === count( $ids ) ? '' : 's' ) . '</span><span class="chip">Since ' . esc_html( date_i18n( 'Y', strtotime( $u->user_registered ) ) ) . '</span></div></div>';
	echo '<div class="acts"><button type="button" class="btn" data-share="' . esc_attr( $url ) . '">Share</button>' . ( $ids ? '<a class="btn a" href="#products">See products</a>' : '' ) . '</div></div>';
	if ( $bio || $links ) {
		echo '<div class="body"><div class="bio">' . ( $bio ? $bio : '<p>' . esc_html( $name ) . ' makes sounds for producers on Solid Sound.</p>' ) . '</div><div class="side">';
		if ( $links ) { echo '<div class="card"><h2>Listen &amp; follow</h2>'; foreach ( $links as $l ) echo '<a class="lk" href="' . esc_url( $l[1] ) . '" target="_blank" rel="noopener nofollow">' . esc_html( $l[0] ) . '</a>'; echo '</div>'; }
		echo '</div></div>';
	}
	echo '<h2 class="t" id="products">Products<small>' . count( $ids ) . '</small></h2>';
	if ( $ids ) {
		echo '<div class="gr">';
		foreach ( $ids as $pid ) {
			$p = wc_get_product( $pid ); if ( ! $p || ! $p->is_visible() ) continue;
			$img = get_the_post_thumbnail_url( $pid, 'woocommerce_thumbnail' );
			$f = function_exists( 'ss_pd_format' ) ? ss_pd_format( $pid ) : '';
			echo '<a class="it" href="' . esc_url( get_permalink( $pid ) ) . '">' . ( $img ? '<img src="' . esc_url( $img ) . '" alt="' . esc_attr( $p->get_name() ) . '" width="300" height="300" loading="lazy">' : '<img alt="">' ) . '<span class="tx">' . ( isset( $fl[ $f ] ) ? '<span class="f">' . esc_html( $fl[ $f ] ) . '</span>' : '' ) . '<b>' . esc_html( $p->get_name() ) . '</b><span class="p">' . wp_kses_post( $p->get_price_html() ) . '</span></span></a>';
		}
		echo '</div>';
	} else {
		echo '<div class="none">' . ( $own ? 'Your first product will appear here. <a href="' . esc_url( function_exists( 'ss_ep_url' ) ? ss_ep_url() : home_url( '/store-manager/' ) ) . '" style="color:#F59E0B">Add a product →</a>' : 'New products are on the way.' ) . '</div>';
	}
	echo '</div></div></section></main>';
	echo '<script>document.addEventListener("click",function(e){var b=e.target.closest&&e.target.closest("[data-share]");if(!b)return;var u=b.getAttribute("data-share");if(navigator.share){navigator.share({title:document.title,url:u}).catch(function(){});return}(navigator.clipboard?navigator.clipboard.writeText(u):Promise.reject()).then(function(){b.textContent="Link copied ✓";setTimeout(function(){b.textContent="Share"},1800)}).catch(function(){prompt("Copy the link",u)})});</script>';
	get_footer();
	exit;
}, 0 );
