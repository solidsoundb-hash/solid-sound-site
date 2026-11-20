<?php
/**
 * /production-services/ — studio packages, laid out like We Make Dance Music's services page (owner 20.11: «пакеты как у вимейка,
 * там очень понятно; наши чутка переделаем: микс-мастеринг, реворк, кастом продакшн»). Prices come from the studio calculator
 * (ss_sc_prices in studio-calc.php) — one source; every card orders through the calculator (it stays, with all its extras).
 * Kept from our own work: the free track review, the private project page with timed comments, 50 % to start, files in your DAW.
 * Customer-facing texts in English.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_pk_path_ok() {
	$p = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' );
	return (bool) preg_match( '#^([a-z]{2}/)?production-services$#', $p );
}

function ss_pk_cards() {
	$P = function_exists( 'ss_sc_prices' ) ? ss_sc_prices() : array();
	$calc = home_url( '/studio-calculator/' );
	$t = function ( $k, $i ) use ( $P ) { return isset( $P[ $k ]['tiers'][ $i ] ) ? $P[ $k ]['tiers'][ $i ] : array( 0, '', 0, 0, '' ); };
	$p = function ( $k, $i ) use ( $P ) { return isset( $P[ $k ]['pkgs'][ $i ] ) ? $P[ $k ]['pkgs'][ $i ] : array( '', 0, 0, '' ); };
	$m = isset( $P['mastering']['types']['stereo'] ) ? $P['mastering']['types']['stereo'] : array( '', 15, 2, '24–48 h' );
	$rel = $t( 'release', 0 ); $relPlus = isset( $P['release']['plus'] ) ? (int) $P['release']['plus'] : 49;
	$live = isset( $P['extras']['live'] ) ? $P['extras']['live'] : array( '', 30 );
	$review = $p( 'copro', 0 ); $copro = $p( 'copro', 1 ); $finish = $p( 'copro', 2 );
	return array(
		'mix' => array( 'Mixing & Mastering', 'Release-ready sound. Your stems mixed and mastered by our engineers.', 'M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6', array(
			array( 'MIXING', 'Professional Mixdown', 'Full mix from your stems: EQ, compression, space, automation and balance. Stereo master included.', array( $t( 'mixing', 0 )[3] . '–' . $t( 'mixing', 2 )[3] . ' rounds of revisions', 'Price by the number of tracks' ), $t( 'mixing', 0 )[2], $t( 'mixing', 0 )[4] . ' and up', $calc . '?s=mixing', '' ),
			array( 'MASTERING', 'Professional Master', 'Mastering for streaming and clubs: EQ, limiting, stereo. Stem and multitrack mastering too.', array( $m[2] . ' rounds of revisions', '5+ songs: −20 %' ), $m[1], $m[3], $calc . '?s=mastering', '' ),
			array( 'BUNDLE', 'Mix & Master Bundle', 'Stems to release-ready: mix, stem mastering, streaming + club masters, stems, instrumental and a 30-min video call.', array( $rel[3] . ' rounds of revisions', 'Video call included' ), $rel[2] + $relPlus, $rel[4], $calc . '?s=release', 'Best value' ),
		) ),
		'rework' => array( 'Project Rework', 'Send us your project. We finish it inside your own DAW session — and talk it through with you.', 'M21 12a9 9 0 1 1-3-6.7L21 8M21 3v5h-5', array(
			array( 'FEEDBACK', 'Track Review', 'Not sure what your track needs? An engineer listens and tells you what to fix first — written + video.', array( 'First review free', 'Clear next steps' ), 0, $review[3], home_url( '/free-demo-feedback/' ), 'Free' ),
			array( 'STANDARD', 'Rework — Standard', 'We work inside your project: arrangement, sound design and mix improvements. Project file comes back.', array( $copro[2] . ' rounds of revisions', '1 × 30-min call' ), $copro[1], $copro[3], $calc . '?s=copro', '' ),
			array( 'RELEASE READY', 'Rework — Release Ready', 'Everything in Standard plus final mix and master, two 60-min calls. Project file + stems, ready to release.', array( $finish[2] . ' rounds of revisions', '2 × 60-min calls' ), $finish[1], $finish[3], $calc . '?s=copro', 'Most popular' ),
			array( 'VIDEO CALL', 'Live Session', 'An engineer joins you live — screen and sound — and works on your track with you.', array( '60 minutes', 'Any DAW' ), $live[1], 'book any day', $calc . '?s=copro', '' ),
		) ),
		'custom' => array( 'Custom Production', 'Only an idea or a reference? We produce the whole track for you — 100 % yours.', 'M12 3l2.6 5.3 5.9.9-4.3 4.1 1 5.8L12 16.4 6.8 19.1l1-5.8L3.5 9.2l5.9-.9z', array(
			array( 'START', $p( 'ghost', 0 )[0], 'A full original track from your idea and references. All rights are yours.', array( $p( 'ghost', 0 )[2] . ' rounds of revisions', 'Master included' ), $p( 'ghost', 0 )[1], $p( 'ghost', 0 )[3], $calc . '?s=ghost', '' ),
			array( 'PRO', 'Custom Pro', 'Everything in Start plus stems and the full project file.', array( $p( 'ghost', 1 )[2] . ' rounds of revisions', 'Stems + project' ), $p( 'ghost', 1 )[1], $p( 'ghost', 1 )[3], $calc . '?s=ghost', 'Most popular' ),
			array( 'ULTRA', 'Custom Ultra', 'Label-ready package with a video walkthrough of the project.', array( $p( 'ghost', 2 )[2] . ' rounds of revisions', 'Video walkthrough' ), $p( 'ghost', 2 )[1], $p( 'ghost', 2 )[3], $calc . '?s=ghost', '' ),
			array( 'REMIX', 'Remix or Remake', 'A remix of your track, or a remake of a reference — radio, club or full project.', array( 'From ' . $p( 'remake', 0 )[2] . ' rounds of revisions', 'Ableton project option' ), min( $p( 'remix', 0 )[1], $p( 'remake', 0 )[1] ), $p( 'remake', 0 )[3] . ' and up', $calc . '?s=remix', '' ),
		) ),
	);
}

function ss_pk_html() {
	$ic = function ( $d, $s = 22 ) { return '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' . esc_attr( $d ) . '"/></svg>'; };
	$h = '<div class="sspk"><header class="hero"><span class="k">Professional music production services</span><h1>Your music. <em>Finished.</em></h1>'
		. '<p>We work inside your project — in <b>your DAW</b>. Listen to every demo on your private project page, leave timed comments, approve when you love it. You pay half to start, the rest only when you are happy.</p>'
		. '<ul class="trust"><li>' . $ic( 'M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7z', 18 ) . '50 / 50 payment</li><li>' . $ic( 'M20 6L9 17l-5-5', 18 ) . '100 % copyright yours</li><li>' . $ic( 'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z', 18 ) . 'Timed comments on every demo</li><li>' . $ic( 'M14 3H6v18h12V7zM14 3v4h4', 18 ) . 'Project files in your DAW</li></ul>'
		. '<nav class="jump"><a href="#mix">Mixing &amp; Mastering</a><a href="#rework">Project Rework</a><a href="#custom">Custom Production</a></nav></header>';
	$h .= '<section class="how"><h2>How it works</h2><ol>'
		. '<li><b>Choose &amp; pay 50 %</b><span>Pick a package. The rest only when you approve.</span></li>'
		. '<li><b>Send your project</b><span>Upload stems or the project, tell us what you need.</span></li>'
		. '<li><b>Review &amp; comment</b><span>Listen on your private page, comment on any second.</span></li>'
		. '<li><b>Approve &amp; download</b><span>Happy? Pay the balance and get every file.</span></li></ol></section>';
	foreach ( ss_pk_cards() as $id => $sec ) {
		$h .= '<section class="sec" id="' . esc_attr( $id ) . '"><div class="sh"><i>' . $ic( $sec[2], 24 ) . '</i><div><h2>' . esc_html( $sec[0] ) . '</h2><p>' . esc_html( $sec[1] ) . '</p></div></div><div class="cards c' . count( $sec[3] ) . '">';
		foreach ( $sec[3] as $c ) {
			$price = (float) $c[4];
			$h .= '<a class="card' . ( $c[7] ? ' hot' : '' ) . '" href="' . esc_url( $c[6] ) . '">' . ( $c[7] ? '<span class="rib">' . esc_html( $c[7] ) . '</span>' : '' )
				. '<div class="top"><span class="tag">' . esc_html( $c[0] ) . '</span><span class="d">' . esc_html( $c[5] ) . '</span></div>'
				. '<h3>' . esc_html( $c[1] ) . '</h3><p>' . esc_html( $c[2] ) . '</p><ul>' . implode( '', array_map( function ( $x ) { return '<li>' . esc_html( $x ) . '</li>'; }, $c[3] ) ) . '</ul>'
				. '<div class="pr">' . ( $price > 0 ? ( in_array( $c[0], array( 'MIXING', 'MASTERING', 'BUNDLE', 'REMIX' ), true ) ? '<span>from</span>' : '' ) . '<b>€' . esc_html( (string) round( $price ) ) . '</b><span>· €' . esc_html( fmod( $price / 2, 1 ) ? number_format( $price / 2, 2, '.', '' ) : (string) ( $price / 2 ) ) . ' to start</span>' : '<b>Free</b><span>for your first track</span>' ) . '<i>→</i></div></a>';
		}
		$h .= '</div></section>';
	}
	$h .= '<section class="g50">' . $ic( 'M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7z', 34 ) . '<h2>The 50 / 50 Guarantee</h2><p>Pay half upfront. The rest only when you are completely happy with the result. No fine print. Your project, your approval.</p>'
		. '<div class="acts"><a class="b" href="' . esc_url( home_url( '/studio-calculator/' ) ) . '">Get my exact price →</a><a class="o" href="' . esc_url( home_url( '/free-demo-feedback/' ) ) . '">Free track review first</a></div></section></div>';
	return $h;
}

function ss_pk_css() {
	return '.sspk{max-width:1120px;margin:0 auto;padding:28px 16px 70px;color:#E2E8F0;font-family:"Exo 2",system-ui,sans-serif}'
		. '.sspk .hero{text-align:center;padding:16px 0 8px}.sspk .k{display:inline-block;padding:6px 14px;border:1px solid rgba(245,158,11,.4);border-radius:999px;color:#FBBF24;font:700 12px Roboto,sans-serif;letter-spacing:.12em;text-transform:uppercase}'
		. '.sspk h1{margin:16px 0 10px;color:#fff;font:800 clamp(36px,6vw,64px)/1.05 "Exo 2",sans-serif}.sspk h1 em{font-style:normal;color:#F59E0B}'
		. '.sspk .hero>p{max-width:720px;margin:0 auto;color:#CBD5E1;font:400 17px/1.6 Roboto,sans-serif}.sspk .hero b{color:#fff}'
		. '.sspk .trust{display:flex;flex-wrap:wrap;justify-content:center;gap:10px 26px;margin:22px auto 0;padding:16px 0;list-style:none;border-top:1px solid rgba(255,255,255,.08);border-bottom:1px solid rgba(255,255,255,.08)}'
		. '.sspk .trust li{display:flex;align-items:center;gap:8px;color:#E2E8F0;font:600 15px Roboto,sans-serif}.sspk .trust svg{color:#F59E0B}'
		. '.sspk .jump{display:flex;flex-wrap:wrap;justify-content:center;gap:8px;margin:18px 0 0}.sspk .jump a{padding:8px 16px;border-radius:999px;border:1px solid rgba(255,255,255,.14);color:#E2E8F0;text-decoration:none;font:600 14px Roboto,sans-serif}.sspk .jump a:hover{border-color:#F59E0B;color:#fff}'
		. '.sspk .how{margin:48px 0 10px;text-align:center}.sspk h2{margin:0;color:#fff;font:800 28px/1.2 "Exo 2",sans-serif}'
		. '.sspk .how ol{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin:24px 0 0;padding:0;list-style:none;counter-reset:s}'
		. '.sspk .how li{counter-increment:s;display:flex;flex-direction:column;align-items:center;gap:6px;padding:0 6px}.sspk .how li:before{content:counter(s);display:flex;align-items:center;justify-content:center;width:46px;height:46px;border-radius:50%;border:1.5px solid rgba(245,158,11,.6);color:#FBBF24;font:800 18px "Exo 2",sans-serif;margin-bottom:6px}'
		. '.sspk .how b{color:#fff;font-size:16px}.sspk .how span{color:#94A3B8;font:400 14px/1.5 Roboto,sans-serif}'
		. '.sspk .sec{margin:54px 0 0;scroll-margin-top:90px}.sspk .sh{display:flex;gap:14px;align-items:center;margin:0 0 18px}.sspk .sh i{flex:none;display:flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:14px;background:rgba(245,158,11,.12);color:#F59E0B}'
		. '.sspk .sh p{margin:4px 0 0;color:#94A3B8;font:400 15px/1.5 Roboto,sans-serif}'
		. '.sspk .cards{display:grid;gap:16px}.sspk .c3{grid-template-columns:repeat(3,1fr)}.sspk .c4{grid-template-columns:repeat(4,1fr)}'
		. '.sspk .card{position:relative;overflow:hidden;display:flex;flex-direction:column;padding:20px;border-radius:16px;border:1px solid rgba(255,255,255,.08);background:#0f1729;color:inherit;text-decoration:none;transition:border-color .15s,transform .15s}'
		. '.sspk .card:hover{border-color:rgba(245,158,11,.55);transform:translateY(-2px)}.sspk .card.hot{border-color:rgba(245,158,11,.5);background:radial-gradient(120% 70% at 100% 0%,rgba(245,158,11,.14),transparent 60%),#0f1729}'
		. '.sspk .rib{position:absolute;top:20px;right:-42px;transform:rotate(45deg);width:160px;padding:3px 0;background:#F59E0B;color:#111;text-align:center;font:800 10px Roboto,sans-serif;letter-spacing:.06em;text-transform:uppercase}'
		. '.sspk .top{display:flex;justify-content:space-between;align-items:center;gap:8px;padding-right:44px}.sspk .tag{padding:3px 9px;border-radius:6px;background:rgba(255,255,255,.06);color:#CBD5E1;font:700 11px Roboto,sans-serif;letter-spacing:.1em}.sspk .d{color:#94A3B8;font:500 12.5px Roboto,sans-serif}'
		. '.sspk h3{margin:14px 0 6px;color:#fff;font:700 19px/1.25 "Exo 2",sans-serif}.sspk .card p{margin:0;color:#94A3B8;font:400 14px/1.55 Roboto,sans-serif;flex:1}'
		. '.sspk .card ul{margin:12px 0 0;padding:0;list-style:none}.sspk .card li{position:relative;padding-left:18px;margin:4px 0;color:#CBD5E1;font:400 13.5px Roboto,sans-serif}.sspk .card li:before{content:"✓";position:absolute;left:0;color:#34D399;font-weight:800}'
		. '.sspk .pr{display:flex;align-items:baseline;gap:8px;margin-top:16px;padding-top:14px;border-top:1px solid rgba(255,255,255,.08)}.sspk .pr b{color:#fff;font:800 26px "Exo 2",sans-serif}.sspk .pr span{color:#94A3B8;font:400 13px Roboto,sans-serif}.sspk .pr i{margin-left:auto;font-style:normal;color:#F59E0B;font-size:20px}'
		. '.sspk .g50{margin:60px 0 0;padding:34px 20px;border-radius:20px;border:1px solid rgba(245,158,11,.3);background:radial-gradient(80% 100% at 50% 0%,rgba(245,158,11,.12),transparent 70%),#0f1729;text-align:center}.sspk .g50>svg{color:#F59E0B}'
		. '.sspk .g50 p{max-width:620px;margin:10px auto 0;color:#CBD5E1;font:400 16px/1.6 Roboto,sans-serif}.sspk .acts{display:flex;flex-wrap:wrap;justify-content:center;gap:10px;margin-top:20px}'
		. '.sspk .acts a{display:inline-flex;align-items:center;min-height:46px;padding:0 22px;border-radius:10px;font:700 15px Roboto,sans-serif;text-decoration:none}.sspk .acts .b{background:#F59E0B;color:#111}.sspk .acts .o{border:1px solid rgba(255,255,255,.2);color:#fff}'
		. '@media(max-width:1000px){.sspk .c4{grid-template-columns:repeat(2,1fr)}.sspk .c3{grid-template-columns:1fr 1fr}.sspk .how ol{grid-template-columns:1fr 1fr}}'
		. '@media(max-width:640px){.sspk .c3,.sspk .c4{grid-template-columns:1fr}.sspk .how ol{grid-template-columns:1fr 1fr;gap:16px 10px}.sspk .trust{gap:8px 16px}.sspk .trust li{font-size:13.5px}.sspk h2{font-size:24px}.sspk .sh i{width:44px;height:44px}}';
}

add_action( 'template_redirect', function () {
	if ( ! ss_pk_path_ok() ) return;
	global $wp_query;
	$wp_query->is_404 = false;
	status_header( 200 );
	add_filter( 'pre_get_document_title', function () { return 'Music Production Services – Mixing, Mastering, Rework, Custom Production | Solid Sound'; } );
	add_action( 'wp_head', function () { echo '<meta name="description" content="Mixing, mastering, project rework in your DAW and custom production. Pay 50 % to start, the rest when you love the result. Free track review.">' . "\n" . '<link rel="canonical" href="' . esc_url( home_url( '/production-services/' ) ) . '">' . "\n" . '<style>' . ss_pk_css() . '</style>'; } );
	get_header();
	echo '<main id="page-content" class="l-main"><section class="l-section"><div class="l-section-h">' . ss_pk_html() . '</div></section></main>';
	get_footer();
	exit;
}, 1 );
