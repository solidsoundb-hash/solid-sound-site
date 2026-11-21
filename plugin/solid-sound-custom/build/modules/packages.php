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
			array( 'MASTERING', 'Professional Master', 'Mastering for streaming and clubs: EQ, limiting, stereo. Stem mastering too.', array( $m[2] . ' rounds of revisions', '5+ songs: −20 %' ), $m[1], $m[3], $calc . '?s=mastering', '' ),
			array( 'BUNDLE', 'Mix & Master Bundle', 'Stems to release-ready: mix, stem mastering, streaming + club masters, stems, instrumental and a 30-min video call.', array( $rel[3] . ' rounds of revisions', 'Video call included' ), $rel[2] + $relPlus, $rel[4], $calc . '?s=release', 'Best value' ),
		) ),
		'rework' => array( 'Project Rework', 'Send us your project. We finish it inside your own DAW session — and talk it through with you.', 'M21 12a9 9 0 1 1-3-6.7L21 8M21 3v5h-5', array(
			array( 'FEEDBACK', 'Track Review', 'Not sure what your track needs? An engineer listens and tells you what to fix first — written + video.', array( 'First review free', 'Clear next steps' ), 0, $review[3], home_url( '/free-demo-feedback/' ), 'Free' ),
			array( 'STANDARD', 'Rework — Standard', 'We work inside your project: arrangement, sound design and mix improvements. Project file comes back.', array( $copro[2] . ' rounds of revisions', '1 × 30-min call' ), $copro[1], $copro[3], $calc . '?s=copro', '' ),
			array( 'RELEASE READY', 'Rework — Release Ready', 'Everything in Standard plus final mix and master, two 60-min calls. Project file + stems, ready to release.', array( $finish[2] . ' rounds of revisions', '2 × 60-min calls' ), $finish[1], $finish[3], $calc . '?s=copro', 'Most popular' ),
			array( 'VIDEO CALL', 'Live Session', 'An engineer joins you live — screen and sound — and works on your track with you.', array( '60 minutes', 'Any DAW' ), $live[1], 'book any day', $calc . '?s=copro', '' ),
		) ),
		'custom' => array( 'Custom Production', 'Only an idea or a reference? We produce the whole track for you — 100 % yours. Pay in 2–3 parts if you like.', 'M12 3l2.6 5.3 5.9.9-4.3 4.1 1 5.8L12 16.4 6.8 19.1l1-5.8L3.5 9.2l5.9-.9z', array(
			array( 'START', $p( 'ghost', 0 )[0], 'A full original track from your idea and references. All rights are yours.', array( $p( 'ghost', 0 )[2] . ' rounds of revisions', 'Master included' ), $p( 'ghost', 0 )[1], $p( 'ghost', 0 )[3], $calc . '?s=ghost', '' ),
			array( 'PRO', 'Custom Pro', 'Everything in Start plus stems and the full project file.', array( $p( 'ghost', 1 )[2] . ' rounds of revisions', 'Stems + project' ), $p( 'ghost', 1 )[1], $p( 'ghost', 1 )[3], $calc . '?s=ghost', 'Most popular' ),
			array( 'ULTRA', 'Custom Ultra', 'Label-ready package with a video walkthrough of the project.', array( $p( 'ghost', 2 )[2] . ' rounds of revisions', 'Video walkthrough' ), $p( 'ghost', 2 )[1], $p( 'ghost', 2 )[3], $calc . '?s=ghost', '' ),
			array( 'LIVE', 'Online Collaboration', 'Three 2-hour live sessions with the producer, screen and sound — plus everything in Pro.', array( $p( 'ghost', 3 )[2] . ' rounds of revisions', '3 × 2 h live' ), $p( 'ghost', 3 )[1], $p( 'ghost', 3 )[3], $calc . '?s=ghost', '' ),
		) ),
		'release' => array( 'Release', 'Your track is done — artwork, short videos and a plan, so it goes out and gets heard.', 'M5 15c-1 1-2 4-2 6 2 0 5-1 6-2M14 4c3-1 6-1 6-1s0 3-1 6l-7 7-5-5zM15 9h.01', array(
			array( 'ARTWORK', 'Cover artwork', 'A 3000 × 3000 cover for Spotify, Apple Music and Beatport.', array( '2 rounds of revisions', 'Store-ready file' ), 49, '3–5 days', home_url( '/contacts/?subject=Release' ), '' ),
			array( 'VIDEO', 'Canvas & clips', 'A Spotify Canvas loop and 3 short clips for Reels, TikTok and Shorts.', array( '9:16, ready to post', '2 rounds of revisions' ), 49, '5–7 days', home_url( '/contacts/?subject=Release' ), '' ),
			array( 'KIT', 'Release Kit', 'Cover, Canvas + 3 clips, upload help with metadata, playlist pitch text and a release plan.', array( 'Everything for release day', 'Save over €40' ), 119, '7–10 days', home_url( '/contacts/?subject=Release%20Kit' ), 'Best value' ),
			array( 'PITCH', 'Playlist pitching', 'Your Spotify for Artists pitch and a list of curators for your genre. No paid placements.', array( 'Honest outreach', 'No fake streams' ), 59, '5–7 days', home_url( '/contacts/?subject=Playlist%20pitching' ), '' ),
		) ),
	);
}

function ss_pk_html( $only = null, $hero = true ) {
	$ic = function ( $d, $s = 22 ) { return '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' . esc_attr( $d ) . '"/></svg>'; };
	$h = '<div class="sspk">';
	if ( $hero ) {
	$h .= '<header class="hero"><span class="k">Professional music production services</span><h1>Your music. <em>Finished.</em></h1>'
		. '<p>We work inside your project — in <b>your DAW</b>. Listen to every demo on your private project page, leave timed comments, approve when you love it. You pay half to start, the rest only when you are happy.</p>'
		. '<ul class="trust"><li>' . $ic( 'M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7z', 18 ) . '50 / 50 payment</li><li>' . $ic( 'M20 6L9 17l-5-5', 18 ) . '100 % copyright yours</li><li>' . $ic( 'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z', 18 ) . 'Timed comments on every demo</li><li>' . $ic( 'M14 3H6v18h12V7zM14 3v4h4', 18 ) . 'Project files in your DAW</li><li>' . $ic( 'M9 18V5l12-2v13', 18 ) . 'Free mastering demo</li><li>' . $ic( 'M20 12v9H4v-9M2 7h20v5H2zM12 21V7', 18 ) . 'Loyal clients keep their prices</li></ul>'
		. '<nav class="jump"><a href="#mix">Mixing &amp; Mastering</a><a href="#rework">Project Rework</a><a href="#custom">Custom Production</a><a href="#release">Release</a></nav></header>';
	$h .= '<section class="how"><h2>How it works</h2><ol>'
		. '<li><b>Choose &amp; pay 50 %</b><span>Pick a package. The rest only when you approve.</span></li>'
		. '<li><b>Send your project</b><span>Upload stems or the project, tell us what you need.</span></li>'
		. '<li><b>Review &amp; comment</b><span>Listen on your private page, comment on any second.</span></li>'
		. '<li><b>Approve &amp; download</b><span>Happy? Pay the balance and get every file.</span></li></ol></section>';
	}
	foreach ( ss_pk_cards() as $id => $sec ) {
		if ( $only && ! in_array( $id, $only, true ) ) continue;
		$h .= '<section class="sec" id="' . esc_attr( $id ) . '"><div class="sh"><i>' . $ic( $sec[2], 24 ) . '</i><div><h2>' . esc_html( $sec[0] ) . '</h2><p>' . esc_html( $sec[1] ) . '</p></div></div><div class="cards c' . count( $sec[3] ) . '">';
		foreach ( $sec[3] as $c ) {
			$price = (float) $c[4];
			$h .= '<a class="card' . ( $c[7] ? ' hot' : '' ) . '" href="' . esc_url( $c[6] ) . '" aria-label="' . esc_attr( $c[1] . ( $price > 0 ? ' — €' . round( $price ) : ' — free' ) ) . '">' . ( $c[7] ? '<span class="rib">' . esc_html( $c[7] ) . '</span>' : '' )
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

/* (owner 20.11 «у нас есть Services & Prices в меню») one page: the menu's Services & Prices (page 1947) shows the package cards on top
   and its own detailed comparison tables (new prices) below; /production-services/ redirects there */
function ss_pk_sp_url() { $u = get_permalink( 1947 ); return $u ? $u : home_url( '/services-prices/' ); }

/* (owner 21.11 «сегодня отзывы с примерами — на них строим ценник») proof under the tables: before/after videos + client words */
function ss_pk_proof_data() {
	$U = 'https://solid-sound.net/wp-content/uploads/';
	$ex = array(
		'e1' => array( 'Space Echo', 'Demo turned into a wide, punchy, label-ready mix', 'mp4', $U . 'Mixing-Example-Anouch-Space-Echo.mp4' ),
		'e4' => array( 'Escape', 'Warmth and depth with outboard gear', 'mp4', $U . 'Mixing-Example-Escape.mp4' ),
		'e5' => array( 'Kick and bass', 'Kick and bass working together', 'mp4', $U . '02-Example-Before-After.mp4' ),
		'e6' => array( 'In My Mind', 'Sound design that makes transitions huge', 'mp4', $U . 'Example-In-My-Mind.mp4' ),
		'e3' => array( 'Eco Techno', 'Vocal tuning, compression and space', 'mp4', $U . 'Mixing-Example-Anouch-Eco-Techno.mp4' ),
		'e2' => array( 'Feeling Good', 'Loudness and glue without killing the dynamics', 'mp4', $U . 'Mixing-Cases-Feeling-Good.mp4' ),
		'e7' => array( 'Ava Kedavra', 'Mud and rumble removed, tight low end', 'mp4', $U . 'Mixing-Example-AVA-KEDAVRA.mp4.mp4' ),
		'p1' => array( 'Film & ads showreel', 'Sound design, Foley, music and mix', 'yt', 'lpjlnxQxvAY' ),
		'p2' => array( 'Sound for TV & ads', 'Sound for film, TV and ads', 'yt', 'vYIh0YHw33A' ),
		'p3' => array( 'Sound design for video', 'Atmospheres, effects and final mix', 'yt', 'SEFtbYJUvvo' ),
		'p4' => array( 'Audio cleanup & restoration', 'Cleanup, restoration and mix', 'yt', 'AoHIenCHQY4' ),
	);
	$rv = array(
		'marco'  => array( 'I’ve had time to listen to it in the car and now in the studio. It’s just perfect. It’s exactly as I wanted it.', 'Marco L.', 'DJ & producer · mixing & mastering' ),
		'paul'   => array( 'Just listened to the radio edit and it is perfect. Good job. I have just signed the last track you worked on. There is more job coming.', 'Paul T.', 'producer · 2nd order' ),
		'hector' => array( 'It is very good. The kick is very good.', 'Hector M.', 'artist · came by recommendation' ),
	);
	$by = array(
		'mixing'    => array( array( 'e1', 'e4', 'e5', 'e6', 'e3' ), array( 'marco', 'hector' ) ),
		'mastering' => array( array( 'e2', 'e7' ), array( 'marco', 'paul' ) ),
		'bundle'    => array( array( 'e1', 'e2', 'e6' ), array( 'marco', 'paul' ) ),
		'post'      => array( array( 'p1', 'p2', 'p3', 'p4' ), array() ),
	);
	/* real chat screenshots from the Free feedback page («Client Cases»): M1 = Marco, H1 = Hector, A1 = a third client */
	$chats = array( 'mixing' => array( 'M1' => 6, 'H1' => 5 ), 'mastering' => array( 'A1' => 2, 'M1' => 6 ), 'bundle' => array( 'M1' => 6, 'A1' => 2 ) );
	$who = array( 'M1' => 'Marco L.', 'H1' => 'Hector M.', 'A1' => 'Anouch' );
	foreach ( $chats as $id => $sets ) { $by[ $id ][2] = array(); foreach ( $sets as $p => $n ) for ( $i = 1; $i <= $n; $i++ ) $by[ $id ][2][] = array( $U . $p . '_' . $i . '.jpg', $who[ $p ] ); }
	return array( $ex, $rv, $by );
}
function ss_pk_proof( $html ) {
	list( $ex, $rv, $by ) = ss_pk_proof_data();
	foreach ( $by as $id => $pick ) {
		$b = '<div class="pr-proof"><h3>Hear before <em>→</em> after</h3><div class="pp-ex">';
		foreach ( $pick[0] as $k ) { $e = $ex[ $k ];
			$thumb = 'yt' === $e[2] ? ' style="background-image:url(https://i.ytimg.com/vi/' . esc_attr( $e[3] ) . '/hqdefault.jpg)"' : '';
			$b .= '<button type="button" class="pp-v" data-k="' . esc_attr( $e[2] ) . '" data-s="' . esc_attr( $e[3] ) . '"><span class="pp-p"' . $thumb . '><i>▶</i></span><b>' . esc_html( $e[0] ) . '</b><small>' . esc_html( $e[1] ) . '</small></button>'; }
		$b .= '</div>';
		$nav = '<div class="pp-nav"><span class="pp-n">1 / %d</span><button type="button" class="pp-pv" aria-label="Previous">‹</button><button type="button" class="pp-nx" aria-label="Next">›</button></div>';
		if ( ! empty( $pick[2] ) ) { $b .= '<div class="pp-sh"><h3>Real messages <em>from clients</em></h3>' . sprintf( $nav, count( $pick[2] ) ) . '</div><div class="pp-ch">';
			foreach ( $pick[2] as $c ) $b .= '<button type="button" class="pp-c" data-src="' . esc_url( $c[0] ) . '"><span><img src="' . esc_url( $c[0] ) . '" alt="Message from ' . esc_attr( $c[1] ) . '" loading="lazy" decoding="async" width="576" height="1280"></span><small>' . esc_html( $c[1] ) . '</small></button>';
			$b .= '</div>'; }
		if ( $pick[1] ) { $b .= '<h3>What clients <em>say</em></h3><div class="pp-rv">'; foreach ( $pick[1] as $k ) { $r = $rv[ $k ]; $b .= '<figure><i class="pp-st" aria-label="5 out of 5">★★★★★</i><blockquote>' . esc_html( $r[0] ) . '</blockquote><figcaption><span class="pp-av">' . esc_html( mb_substr( $r[1], 0, 1 ) ) . '</span><span><b>' . esc_html( $r[1] ) . '</b><small>' . esc_html( $r[2] ) . '</small></span></figcaption></figure>'; } $b .= '</div>'; }
		$b .= '</div>';
		$html = preg_replace_callback( '#(<section class="pr-sec" id="pr-' . $id . '".*?)(</section>)#s', function ( $m ) use ( $b ) { return $m[1] . $b . $m[2]; }, $html, 1 );
	}
	return $html;
}
function ss_pk_proof_css() {
	return '.pr-proof{margin:26px 0 0}.pr-proof h3{margin:0 0 12px;color:#fff;font:800 20px/1.2 "Exo 2",sans-serif;text-align:center}.pr-proof h3 em{font-style:normal;color:#F5A400}.pr-proof h3:not(:first-child){margin-top:26px}'
		. '.pp-ex{display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:12px}.pp-v{display:flex;flex-direction:column;align-items:flex-start;gap:4px;padding:0 0 12px;border-radius:14px;border:1px solid rgba(255,255,255,.1);background:#0f1729;color:#E2E8F0;text-align:left;cursor:pointer;overflow:hidden;font:inherit}'
		. '.pp-v:hover{border-color:rgba(245,164,0,.5)}.pp-p{position:relative;display:flex;align-items:center;justify-content:center;width:100%;aspect-ratio:16/9;background:#111827 center/cover;margin-bottom:6px}.pp-p i{display:flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:50%;background:#F5A400;color:#111;font-style:normal;font-size:18px;padding-left:3px}'
		. '.pp-v b,.pp-v small{padding:0 12px}.pp-v b{color:#fff;font:700 15px "Exo 2",sans-serif}.pp-v small{color:#94A3B8;font-size:13px;line-height:1.4}.pp-v video,.pp-v iframe{width:100%;aspect-ratio:16/9;border:0;display:block;margin-bottom:6px;background:#000}'
		. '.pr-proof h3{text-align:left;font-size:22px}.pp-sh{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:30px 0 14px}.pp-sh h3{margin:0!important}.pp-nav{display:flex;align-items:center;gap:8px;flex:none}.pp-n{color:#94A3B8;font:700 14px "Exo 2",sans-serif;margin-right:6px;min-width:46px;text-align:right}'
		. '.pp-nav button{width:42px;height:42px;border-radius:10px;border:1px solid rgba(255,255,255,.12);background:#0f1729;color:#E2E8F0;font:400 26px/1 sans-serif;cursor:pointer;padding:0 0 3px}.pp-nav button:hover{border-color:#F5A400;color:#F5A400}.pp-nav button:disabled{opacity:.35;cursor:default;border-color:rgba(255,255,255,.12);color:#E2E8F0}'
		. '.pp-ch{display:flex;gap:14px;overflow-x:auto;scroll-snap-type:x mandatory;scroll-behavior:smooth;scrollbar-width:none;padding:2px 2px 6px;-webkit-mask-image:linear-gradient(90deg,#000 calc(100% - 40px),transparent);mask-image:linear-gradient(90deg,#000 calc(100% - 40px),transparent)}.pp-ch::-webkit-scrollbar{display:none}.pp-ch.end{-webkit-mask-image:none;mask-image:none}'
		. '.pp-c{flex:none;scroll-snap-align:start;width:190px;padding:7px 7px 10px;border-radius:18px;border:1px solid rgba(255,255,255,.09);background:linear-gradient(180deg,#131c30,#0d1424);cursor:zoom-in;transition:transform .2s,border-color .2s;font:inherit;text-align:center}.pp-c:hover{transform:translateY(-3px);border-color:rgba(245,164,0,.55)}'
		. '.pp-c span{display:block;height:300px;border-radius:12px;overflow:hidden;background:#000}.pp-c img{display:block;width:100%;height:100%;object-fit:cover;object-position:top;color:transparent;font-size:0}.pp-c small{display:block;margin-top:8px;color:#CBD5E1;font:600 13px "Exo 2",sans-serif}'
		. '.pp-rv{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:14px}.pp-rv figure{display:flex;flex-direction:column;margin:0;padding:18px 20px;border-radius:16px;border:1px solid rgba(255,255,255,.09);background:linear-gradient(180deg,#131c30,#0d1424)}.pp-st{font-style:normal;color:#F5A400;letter-spacing:2px;font-size:15px}.pp-rv blockquote{margin:10px 0 16px;color:#E2E8F0;font:400 16px/1.6 Roboto,sans-serif}'
		. '.pp-rv figcaption{display:flex;align-items:center;gap:10px;margin-top:auto}.pp-av{display:flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:50%;background:rgba(245,164,0,.15);color:#F5A400;font:800 16px "Exo 2",sans-serif;flex:none}.pp-rv figcaption b{display:block;color:#fff;font:700 14px "Exo 2",sans-serif}.pp-rv figcaption small{color:#94A3B8;font-size:12.5px}'
		. '.pp-lb{position:fixed;inset:0;z-index:100000;display:flex;align-items:center;justify-content:center;gap:12px;background:rgba(2,6,15,.92);padding:16px}.pp-lb img{max-height:calc(100vh - 32px);max-width:min(560px,calc(100vw - 120px));border-radius:14px}.pp-lb button{width:46px;height:46px;border-radius:50%;border:1px solid rgba(255,255,255,.2);background:#0f1729;color:#fff;font:400 26px/1 sans-serif;cursor:pointer;flex:none}.pp-lb .x{position:absolute;top:14px;right:14px}'
		. '@media(max-width:600px){.pr-proof h3{font-size:19px}.pp-c{width:150px}.pp-c span{height:240px}.pp-nav button{width:36px;height:36px}.pp-n{display:none}.pp-lb{gap:6px;padding:8px}.pp-lb img{max-width:calc(100vw - 100px)}.pp-lb button{width:38px;height:38px}}html.ss-lite .pp-c{transition:none}html.ss-lite .pp-ch{scroll-behavior:auto}';
}
function ss_pk_proof_js() {
	/* a video loads only when tapped (the page stays fast) */
	return "<script>document.addEventListener('click',function(e){if(!e.target.closest)return;var b=e.target.closest('.pp-v');if(!b||b.dataset.on)return;b.dataset.on=1;var p=b.querySelector('.pp-p'),k=b.dataset.k,s=b.dataset.s,el;if(k==='yt'){el=document.createElement('iframe');el.src='https://www.youtube-nocookie.com/embed/'+s+'?autoplay=1';el.allow='autoplay; encrypted-media; fullscreen';el.allowFullscreen=true}else{el=document.createElement('video');el.src=s;el.controls=true;el.autoplay=true;el.playsInline=true}p.replaceWith(el)});"
		/* chats carousel: arrows + counter, tap = big view with prev/next (owner 21.11 «покрасивей, поправить куцость и косость») */
		. "(function(){function upd(c){var n=c.parentNode.querySelector('.pp-sh .pp-n'),k=c.children.length,w=c.children[0]?c.children[0].offsetWidth+14:1,i=Math.min(k,Math.round(c.scrollLeft/w)+1),e=c.scrollLeft+c.clientWidth>=c.scrollWidth-4,nv=c.previousElementSibling;if(n)n.textContent=i+' / '+k;c.classList.toggle('end',e);if(nv?nv.classList.contains('pp-sh'):0){nv.querySelector('.pp-pv').disabled=c.scrollLeft<4;nv.querySelector('.pp-nx').disabled=e}}"
		. "function init(){document.querySelectorAll('.pp-ch').forEach(function(c){if(c.clientWidth)upd(c)})}var t;document.addEventListener('scroll',function(e){var c=e.target;if(!t?(c.classList?c.classList.contains('pp-ch'):0):0)t=requestAnimationFrame(function(){t=0;upd(c)})},{capture:true,passive:true});"
		. "if(document.readyState==='complete')init();else addEventListener('load',init);document.addEventListener('click',function(e){if(e.target.closest?e.target.closest('.pr-tabs'):0)setTimeout(init,60)});"
		. "var lb,L,I;function show(){lb.querySelector('img').src=L[I].dataset.src}function close(){if(lb){lb.remove();lb=null}}"
		. "document.addEventListener('click',function(e){if(!e.target.closest)return;var b=e.target.closest('.pp-pv,.pp-nx');if(b){var c=b.closest('.pp-sh').nextElementSibling;c.scrollBy({left:(b.classList.contains('pp-nx')?1:-1)*Math.max(200,c.clientWidth*.8)});return}"
		. "var a=e.target.closest('.pp-c');if(!a)return;L=[].slice.call(a.parentNode.children);I=L.indexOf(a);close();lb=document.createElement('div');lb.className='pp-lb';lb.setAttribute('role','dialog');lb.innerHTML='<button type=\"button\" class=\"p\" aria-label=\"Previous\">‹</button><img alt=\"Message from a client\"><button type=\"button\" class=\"n\" aria-label=\"Next\">›</button><button type=\"button\" class=\"x\" aria-label=\"Close\">×</button>';"
		. "lb.onclick=function(ev){var t=ev.target;if(t.classList.contains('p'))I=(I+L.length-1)%L.length;else if(t.classList.contains('n'))I=(I+1)%L.length;else if(t.tagName!=='IMG')return close();show()};document.body.appendChild(lb);show()});"
		. "document.addEventListener('keydown',function(e){if(!lb)return;if(e.key==='Escape')close();else if(e.key==='ArrowRight'){I=(I+1)%L.length;show()}else if(e.key==='ArrowLeft'){I=(I+L.length-1)%L.length;show()}})})();</script>";
}
add_filter( 'the_content', function ( $c ) {
	if ( ! is_page( 1947 ) || ! in_the_loop() || ! is_main_query() ) return $c;
	$new = include __DIR__ . '/services-prices-html.php';
	if ( is_string( $new ) && $new && function_exists( 'ss_pe_table' ) ) $new = ss_pe_table( $new ); /* prices follow demand */
	if ( is_string( $new ) && $new ) $new = ss_pk_proof( $new ); /* before/after + client words under the tables */
	if ( is_string( $new ) && $new ) $c = preg_replace( '#\[us_html\].*?\[/us_html\]#s', '[us_html]' . base64_encode( rawurlencode( $new ) ) . '[/us_html]', $c, 1 );
	$css = ss_pk_css() . '.sspk.top{padding-bottom:0}.sspk.top .trust{margin-top:0}';
	$trust = '<div class="sspk top"><ul class="trust"><li>50 / 50 payment</li><li>100 % copyright yours</li><li>Timed comments on every demo</li><li>Project files in your DAW</li><li>Free mastering demo</li><li>Loyal clients keep their prices</li></ul></div>';
	return '<style>' . $css . ss_pk_proof_css() . '</style>' . $c . ss_pk_proof_js(); /* tables + proof (owner 21.11) */
}, 5 );
add_action( 'template_redirect', function () {
	if ( ! ss_pk_path_ok() ) return;
	wp_safe_redirect( ss_pk_sp_url(), 301 ); exit;
}, 1 );
