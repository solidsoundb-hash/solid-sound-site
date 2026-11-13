<?php
/**
 * Solid Wiki articles (owner 13.11.2026): "headings in a clear order, easy to read, icons — everything intuitive";
 * the before/after audio in the vocal article sounded bad → removed.
 * Works on every wiki article built with <article class="ssa"> (the text stays in WordPress, this only changes how
 * it is shown): icon on every section heading, contents as icon cards, "the rules at a glance" strip from
 * "Rule N: …" headings, check icons in checklists, +/− on FAQ. ?ss_wiki=0 shows the article as before (admins).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_wiki_icon( $name ) {
	$p = array(
		'bulb'   => 'M9 18h6M10 21h4M12 3a6 6 0 0 0-4 10.5c.7.7 1 1.5 1 2.5h6c0-1 .3-1.8 1-2.5A6 6 0 0 0 12 3z',
		'ruler'  => 'M3 17L17 3l4 4L7 21zM7 13l2 2M10 10l2 2M13 7l2 2',
		'angle'  => 'M4 20h16M4 20L16 6M9 20a6 6 0 0 0-1.6-4.1',
		'lock'   => 'M6 11h12v10H6zM8 11V7a4 4 0 0 1 8 0v4M12 15v2',
		'mic'    => 'M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3zM19 10v2a7 7 0 0 1-14 0v-2M12 19v3M8 22h8',
		'home'   => 'M3 11l9-8 9 8M5 10v10h14V10M10 20v-6h4v6',
		'sliders'=> 'M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6',
		'check'  => 'M4 4h16v16H4zM8 12l3 3 5-6',
		'flag'   => 'M5 21V4M5 4h11l-2 4 2 4H5',
		'help'   => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18zM9.5 9a2.5 2.5 0 1 1 3.5 2.3c-.6.3-1 .9-1 1.6V14M12 17h.01',
		'wave'   => 'M2 12h2M6 8v8M10 5v14M14 8v8M18 10v4M22 12h-2',
		'book'   => 'M4 4h6a2 2 0 0 1 2 2v14a2 2 0 0 0-2-2H4zM20 4h-6a2 2 0 0 0-2 2v14a2 2 0 0 1 2-2h6z',
		'bolt'   => 'M13 2L4 14h7l-1 8 9-12h-7z',
		'dot'    => 'M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0-6 0',
	);
	$d = isset( $p[ $name ] ) ? $p[ $name ] : $p['dot'];
	return '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' . $d . '"/></svg>';
}
/* which icon fits a heading: by its id first, then by words in it */
function ss_wiki_pick( $id, $text ) {
	$by_id = array( 'why' => 'bulb', 'distance' => 'ruler', 'angle' => 'angle', 'consistency' => 'lock', 'microphone' => 'mic', 'mic' => 'mic',
		'room' => 'home', 'mix' => 'sliders', 'checklist' => 'check', 'faq' => 'help', 'summary' => 'flag' );
	if ( $id && isset( $by_id[ $id ] ) ) return $by_id[ $id ];
	$t = mb_strtolower( wp_strip_all_tags( $text ) );
	$words = array( 'faq' => 'help', 'question' => 'help', 'checklist' => 'check', 'bottom line' => 'flag', 'summary' => 'flag', 'conclusion' => 'flag',
		'why' => 'bulb', 'distance' => 'ruler', 'angle' => 'angle', 'consisten' => 'lock', 'microphone' => 'mic', 'mic ' => 'mic', 'vocal' => 'mic',
		'room' => 'home', 'acoustic' => 'home', 'mix' => 'sliders', 'eq' => 'sliders', 'compress' => 'sliders', 'master' => 'sliders',
		'sound' => 'wave', 'step' => 'bolt', 'quick' => 'bolt', 'learn' => 'book', 'guide' => 'book' );
	foreach ( $words as $w => $ic ) if ( false !== mb_strpos( $t, $w ) ) return $ic;
	return 'dot';
}

/* icon for one checklist item: by meaning (owner 13.11.2026: icons, not identical ticks) */
function ss_wiki_item_icon( $text ) {
	$t = mb_strtolower( wp_strip_all_tags( $text ) );
	$map = array( 'same position' => 'lock', 'every take' => 'lock', 'spot' => 'lock', 'floor' => 'lock', 'shock mount' => 'lock', 'tight' => 'lock',
		'°' => 'angle', 'turn' => 'angle', 'angle' => 'angle', 'beside' => 'angle', 'lip level' => 'angle',
		'back of the mic' => 'mic', 'pop filter' => 'mic',
		'gain' => 'sliders', 'peak' => 'sliders', 'dbfs' => 'sliders', 'level' => 'sliders', 'eq' => 'sliders', 'compress' => 'sliders',
		'cm' => 'ruler', 'distance' => 'ruler', 'close' => 'ruler',
		'room' => 'home', 'soft' => 'home', 'behind you' => 'home', 'noise' => 'home', 'blanket' => 'home',
		'mic' => 'mic', 'microphone' => 'mic',
		'headphone' => 'wave', 'listen' => 'wave', 'monitor' => 'wave', 'reference' => 'wave', 'export' => 'bolt', 'save' => 'bolt', 'backup' => 'bolt' );
	foreach ( $map as $w => $ic ) if ( false !== mb_strpos( $t, $w ) ) return $ic;
	return 'check';
}

function ss_wiki_transform( $html ) {
	/* 1. the simulated before/after audio (sounded bad) — whole block with its note */
	$html = preg_replace( '#<h3>\s*Hear the difference\s*</h3>\s*<div class="ab">.*?</div>\s*</div>\s*(<p class="ab-note">.*?</p>)?#s', '', $html );
	/* 2. icon on every h2 */
	$rules = array();
	$html = preg_replace_callback( '#<h2(\s+id="([^"]*)")?([^>]*)>(.*?)</h2>#s', function ( $m ) use ( &$rules ) {
		$id = $m[2]; $text = $m[4];
		if ( preg_match( '#^\s*Rule\s+(\d+):\s*(.+?)\s+[—–-]\s+(.+)$#u', wp_strip_all_tags( $text ), $r ) ) $rules[] = array( $r[1], $r[2], $r[3], $id, ss_wiki_pick( $id, $text ) );
		$ic = ss_wiki_pick( $id, $text );
		return '<h2' . $m[1] . $m[3] . ' class="wh"><span class="wi">' . ss_wiki_icon( $ic ) . '</span><span class="wt">' . $text . '</span></h2>';
	}, $html );
	/* 2b. checklist: a meaningful icon on every item */
	$html = preg_replace_callback( '#<ul class="check">(.*?)</ul>#s', function ( $m ) {
		return '<ul class="check check-ic">' . preg_replace_callback( '#<li>(.*?)</li>#s', function ( $l ) {
			return '<li><span class="wi">' . ss_wiki_icon( ss_wiki_item_icon( $l[1] ) ) . '</span><span class="ct">' . $l[1] . '</span></li>';
		}, $m[1] ) . '</ul>';
	}, $html );
	/* 3. contents: icon cards */
	$html = preg_replace_callback( '#<nav class="toc">(.*?)</nav>#s', function ( $m ) {
		$inner = preg_replace_callback( '#<li><a href="\#([^"]*)">(.*?)</a></li>#s', function ( $l ) {
			return '<li><a href="#' . $l[1] . '"><span class="wi">' . ss_wiki_icon( ss_wiki_pick( $l[1], $l[2] ) ) . '</span><span>' . $l[2] . '</span></a></li>';
		}, $m[1] );
		return '<nav class="toc toc-ic" aria-label="In this article">' . $inner . '</nav>';
	}, $html );
	/* 4. "the rules at a glance" right after the quick answer */
	if ( count( $rules ) >= 2 ) {
		$cards = '';
		foreach ( $rules as $r ) {
			$cards .= '<a class="rg" href="#' . esc_attr( $r[3] ) . '"><span class="wi">' . ss_wiki_icon( $r[4] ) . '</span><span class="rn">Rule ' . esc_html( $r[0] ) . '</span><b>' . esc_html( $r[1] ) . '</b><span class="rs">' . esc_html( $r[2] ) . '</span></a>';
		}
		$strip = '<div class="rules-glance" aria-label="The rules at a glance">' . $cards . '</div>';
		$c = 0;
		$html = preg_replace( '#(<div class="box or">.*?</div>)#s', '$1' . $strip, $html, 1, $c );
		if ( ! $c ) $html = preg_replace( '#(<nav class="toc)#', $strip . '$1', $html, 1 );
	}
	return $html;
}

function ss_wiki_css() {
	return '<style id="ss-wiki">'
		. '.ssa h2.wh{display:flex;align-items:center;gap:12px;scroll-margin-top:120px;border-left:0;padding-left:0}.ssa h2.wh::before{display:none!important;content:none!important}'
		. '.ssa .wi{flex:none;display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;background:rgba(224,153,0,.12);border:1px solid rgba(224,153,0,.3);color:#e09900}'
		. '.ssa .toc-ic ol{list-style:none;padding:0;margin:12px 0 0;display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:8px}'
		. '.ssa .toc-ic li{margin:0}.ssa .toc-ic a{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:12px;background:#1c1a18;border:1px solid rgba(255,255,255,.08);color:#e6e2dc;text-decoration:none;font-size:15px;line-height:1.3;height:100%;box-sizing:border-box}'
		. '.ssa .toc-ic a:hover{border-color:#e09900}.ssa .toc-ic .wi{width:32px;height:32px}'
		. '.ssa .rules-glance{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;margin:18px 0 22px}'
		. '.ssa .rg{display:flex;flex-direction:column;gap:4px;padding:14px;border-radius:14px;background:#1c1a18;border:1px solid rgba(224,153,0,.28);text-decoration:none;color:#e6e2dc}'
		. '.ssa .rg:hover{border-color:#e09900}.ssa .rg .wi{margin-bottom:6px}.ssa .rg .rn{font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#e09900;font-weight:700}.ssa .rg b{color:#fff;font-size:17px}.ssa .rg .rs{font-size:14px;color:#bdb8b1;line-height:1.35}'
		. '.ssa ul.check-ic{list-style:none;padding:0}.ssa ul.check-ic li{display:flex;align-items:center;gap:12px;padding:10px 12px;margin-left:0;border-bottom:1px solid rgba(255,255,255,.06)}.ssa ul.check-ic li::before{display:none!important;content:none!important}.ssa ul.check-ic .wi{width:34px;height:34px}'
		. '.ssa details summary{list-style:none;display:flex;justify-content:space-between;align-items:center;gap:12px}.ssa details summary::-webkit-details-marker{display:none}'
		. '.ssa details summary::after{content:"+";flex:none;width:26px;height:26px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:rgba(224,153,0,.14);color:#e09900;font-size:20px;line-height:1}'
		. '.ssa details[open] summary::after{content:"−"}'
		. '@media(max-width:700px){.ssa .rules-glance{grid-template-columns:1fr}.ssa .rg{flex-direction:row;flex-wrap:wrap;align-items:center;column-gap:10px}.ssa .rg .wi{margin:0}.ssa .rg .rs{flex-basis:100%}.ssa h2.wh{gap:10px}.ssa .wi{width:34px;height:34px}.ssa .toc-ic ol{grid-template-columns:1fr}}'
		. '</style>';
}

add_filter( 'the_content', function ( $html ) {
	if ( is_admin() || false === strpos( $html, 'class="ssa"' ) ) return $html;
	if ( isset( $_GET['ss_wiki'] ) && '0' === $_GET['ss_wiki'] ) return $html;
	return ss_wiki_css() . ss_wiki_transform( $html );
}, 50 );
