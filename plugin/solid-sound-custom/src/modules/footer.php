<?php
/**
 * Footer on all pages except the home app = the same footer as the home page (owner 11.11.2026).
 * Before: the theme footer (page block 52) + scripts that appended legal links and "Cookie settings"
 * to every paragraph -> duplicated links and stray "Cookie settings" lines.
 * Now: the theme footer is hidden and this footer is printed by the server. It carries
 * a.ss-cc-open and [data-ss-legal] so the old scripts see it and add nothing.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_footer_enabled() {
	if ( is_admin() ) return false;
	/* printed on the home page too (hidden there by CSS): the player switches pages without reload, so it must exist when leaving home */
	return true;
}

add_action( 'wp_head', function () {
	if ( ! ss_footer_enabled() ) return;
	echo '<style id="ss-foot-css">.l-footer{display:none!important}body.ss-newhome .ssf{display:none!important}'
		. '.ssf{display:block;width:100%;box-sizing:border-box;clear:both;background:#131B2E;border-top:1px solid rgba(255,255,255,.08);padding:24px 0 28px;color:#E5E7EB;font-family:"Exo 2",system-ui,sans-serif;font-size:14px;line-height:1.4}'
		. '.ssf .w{max-width:1200px;margin:0 auto;padding:0 24px;box-sizing:border-box}'
		. '.ssf .cols{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:18px}'
		. '.ssf .cols>div{display:flex;flex-direction:column;gap:6px}.ssf b{color:#fff}'
		. '.ssf .cols a{color:#94A3B8!important;text-decoration:none!important}.ssf .cols a:hover{color:#F59E0B!important}'
		. '.ssf .note{color:#94A3B8;font-size:13px}.ssf .pay{display:flex;flex-wrap:wrap;gap:6px}'
		. '.ssf .pay span{font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;border:1px solid rgba(255,255,255,.08);color:#CBD5E1}'
		. '.ssf .soc{margin-top:20px;display:flex;flex-wrap:wrap;align-items:center;gap:10px}'
		. '.ssf .soc a{width:40px;height:40px;box-sizing:border-box;border-radius:10px;border:1px solid rgba(255,255,255,.08);background:#1A2540;display:flex;align-items:center;justify-content:center}'
		. '.ssf .soc svg{display:block}'
		. '.ssf .legal{margin-top:18px;display:flex;flex-wrap:wrap;gap:6px 14px;color:#7C8BA1;font-size:13px}'
		. '.ssf .legal a{color:#7C8BA1!important;text-decoration:underline}.ssf .legal a:hover{color:#F59E0B!important}'
		. '@media (max-width:760px){.ssf .w{padding:0 16px}.ssf{padding-bottom:calc(90px + env(safe-area-inset-bottom))}}'
		. '</style>';
}, 50 );

add_action( 'wp_footer', function () {
	if ( ! ss_footer_enabled() ) return;
	$S = 'https://solid-sound.net';
	$col = function ( $title, $links ) {
		$h = '<div><b>' . esc_html( $title ) . '</b>';
		foreach ( $links as $l ) $h .= '<a href="' . esc_url( $l[1] ) . '">' . esc_html( $l[0] ) . '</a>';
		return $h . '</div>';
	};
	$soc = array(
		array( 'Instagram', '#ffffff', 'https://www.instagram.com/solid.sound.studio', 'M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4zM12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM17.5 6.5h.01' ),
		array( 'YouTube', '#ff3b30', 'https://www.youtube.com/channel/UCeU2yrebmva2mZE4cME4-pg', 'M3 8a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3zM10 9l5 3-5 3z' ),
		array( 'TikTok', '#ffffff', 'https://www.tiktok.com/@solid.sound.studio', 'M14 3v11a4 4 0 1 1-4-4M14 3c0 3 2 5 5 5' ),
		array( 'Facebook', '#3b82f6', 'https://www.facebook.com/SolidSoundStudio1', 'M15 3h-2a4 4 0 0 0-4 4v3H7v4h2v7h4v-7h3l1-4h-4V7a1 1 0 0 1 1-1h2z' ),
	);
	$h  = '<footer class="ssf"><div class="w"><div class="cols">';
	$h .= $col( 'Products', array( array( 'Templates', $S . '/templates' ), array( 'Presets', $S . '/presets' ), array( 'Music', $S . '/music' ), array( 'Free', $S . '/en/free/' ) ) );
	$h .= $col( 'Services', array( array( 'Services & Prices', $S . '/en/services-prices/' ), array( 'Mixing & Mastering Calculator', $S . '/studio-calculator/?s=mixing' ), array( 'Mastering Album Calculator', $S . '/studio-calculator/?s=mastering' ), array( 'Become a vendor', $S . '/en/sell/' ) ) );
	$h .= $col( 'Community', array( array( 'Why Solid Sound', $S . '/en/why-solid-sound/' ), array( 'Get your set heard', $S . '/en/get-heard/' ), array( 'Forum', $S . '/en/forum/' ), array( 'Wiki', $S . '/en/wiki/' ), array( 'FAQ', $S . '/en/faq/' ), array( 'Contact us', $S . '/contacts' ) ) );
	$h .= '<div><b>Secure checkout</b><span class="note">Faulty file? Fix, replacement or refund within 30 days. Services: cancel within 14 days.</span><div class="pay">';
	foreach ( array( 'VISA', 'MASTERCARD', 'APPLE PAY', 'GOOGLE PAY', 'STRIPE' ) as $p ) $h .= '<span>' . $p . '</span>';
	$h .= '</div></div></div>';
	$h .= '<div class="soc"><b style="margin-right:4px">Follow us</b>';
	foreach ( $soc as $s ) {
		$h .= '<a href="' . esc_url( $s[2] ) . '" aria-label="' . esc_attr( $s[0] ) . '" style="color:' . $s[1] . '" target="_blank" rel="noopener"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' . esc_attr( $s[3] ) . '"></path></svg></a>';
	}
	$h .= '</div><div class="legal"><span>© 2026 Solid Sound</span>';
	foreach ( array( array( 'Terms of Sale', $S . '/en/terms/' ), array( 'Privacy Policy', $S . '/privacy-policy/' ), array( 'Refund Policy', $S . '/refund_returns/' ), array( 'License Agreement', $S . '/license/' ), array( 'Legal Notice', $S . '/en/legal-notice/' ) ) as $l ) {
		$h .= '<a data-ss-legal="1" href="' . esc_url( $l[1] ) . '">' . esc_html( $l[0] ) . '</a>';
	}
	$h .= '<a class="ss-cc-open" role="button" tabindex="0" onclick="var b=document.getElementById(\'ss-cc\');if(b)b.hidden=false">Cookie settings</a>';
	$h .= '</div></div></footer>';
	echo $h; // phpcs:ignore -- built from escaped constants above
}, 1 );
