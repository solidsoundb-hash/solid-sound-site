<?php
/**
 * Forum: "Stuck? Start here" (owner 19.11.2026: «на форуме это всё тоже должно быть»).
 * The same biggest producer pains as the home page's «Get help» tab, each opens with the answer and one step,
 * plus "Ask the community". Only on the forum's front page, above the forum list. Customer texts in English.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_footer', function () {
	if ( ! function_exists( 'ss_fr_ok' ) || ! ss_fr_ok() || ! function_exists( 'ss_fr_is_page' ) || ! ss_fr_is_page() ) return;
	$base = function_exists( 'wpforo_home_url' ) ? wpforo_home_url() : home_url( '/forum/' );
	$items = array(
		array( 'Is my track good enough? What’s wrong with it?', 'Send it — our engineer listens and tells you what holds it back: arrangement, sound, mix. Free, in a few days.', 'Get free feedback →', home_url( '/en/free-demo-feedback/' ) ),
		array( 'My track sounds quieter and weaker than others', 'That’s mixing and mastering. Hear the difference on real client tracks, then see your price in a minute.', 'Get my price →', home_url( '/studio-calculator/?s=mixing' ) ),
		array( 'I start tracks but can’t finish them', 'Open a finished project in your genre and see how it’s built — or work on your track with us on a video call.', 'See templates →', home_url( '/en/all-products/' ) ),
		array( 'Nobody hears my music', 'Promotion help: a release plan, pitching to playlists and labels, and what to post so people find your track.', 'Ask about promotion →', home_url( '/en/contacts/' ) ),
		array( 'Labels don’t answer my demos', 'We check if your track is label-ready — sound, length, loudness, files and metadata — and fix what’s missing.', 'Make it label-ready →', home_url( '/studio-calculator/?s=release' ) ),
		array( 'I’m just starting — what first?', 'Take a free starter pack and read our short guides. No money needed to begin.', 'Get free stuff →', home_url( '/en/free/' ) ),
	);
	$h = '<section class="ss-fh" aria-label="Start here"><div class="ss-fh-h"><b>Stuck? Start here</b><span>The most common reasons producers get stuck — and what helps right away.</span></div><div class="ss-fh-acc">';
	foreach ( $items as $k => $x ) {
		$h .= '<details' . ( 0 === $k ? ' open' : '' ) . '><summary>' . esc_html( $x[0] ) . '</summary><p>' . esc_html( $x[1] ) . '</p><a href="' . esc_url( $x[3] ) . '">' . esc_html( $x[2] ) . '</a></details>';
	}
	$h .= '</div><p class="ss-fh-ask">Something else? <a href="' . esc_url( $base ) . '">Ask the community</a> — producers and our engineers answer.</p></section>';
	?>
<style id="ss-fh-css">.ss-fh{margin:6px 0 16px;padding:16px;border-radius:12px;border:1px solid rgba(245,158,11,.30);background:radial-gradient(120% 90% at 100% 0%,rgba(245,158,11,.14),transparent 60%),#131b2e;font-family:"Exo 2",system-ui,sans-serif}
.ss-fh-h{display:flex;flex-wrap:wrap;align-items:baseline;gap:4px 12px;margin:0 0 10px}.ss-fh-h b{color:#fff;font-size:20px;font-weight:800}.ss-fh-h span{color:#94a3b8;font-size:14px}
.ss-fh-acc{display:grid;grid-template-columns:1fr 1fr;gap:6px 10px}@media(max-width:760px){.ss-fh-acc{grid-template-columns:1fr}}
.ss-fh details{border:1px solid rgba(255,255,255,.08);border-radius:8px;background:rgba(255,255,255,.02)}.ss-fh details[open]{border-color:rgba(245,158,11,.35);background:rgba(245,158,11,.06)}
.ss-fh summary{list-style:none;cursor:pointer;display:flex;justify-content:space-between;gap:10px;padding:10px 12px;color:#fff;font-weight:600;font-size:14.5px}.ss-fh summary::-webkit-details-marker{display:none}
.ss-fh summary:after{content:"+";color:#f59e0b;font-weight:800}.ss-fh details[open] summary:after{content:"–"}
.ss-fh details p{margin:0;padding:0 12px 6px;color:#cbd5e1;font:400 14px/1.5 Roboto,sans-serif}.ss-fh details a,.ss-fh-ask a{color:#f59e0b!important;font-weight:700;text-decoration:none}
.ss-fh details a{display:inline-block;margin:0 12px 10px}.ss-fh-ask{margin:10px 0 0;color:#94a3b8;font-size:14px}</style>
<script id="ss-fh-js">(function(){var H=<?php echo wp_json_encode( $h ); ?>,BASE=<?php echo wp_json_encode( wp_parse_url( $base, PHP_URL_PATH ) ); ?>;
function norm(p){return String(p||'').replace(/\/+$/,'').replace(/^\/(en|ua|de|fr|es|it|pt)(?=\/)/,'')}
if(norm(location.pathname)!==norm(BASE))return; /* the forum's front page only */
function put(){if(document.querySelector('.ss-fh'))return true;var m=document.querySelector('#wpforo #wpforo-menu');if(!m)return false;var d=document.createElement('div');d.innerHTML=H;var s=d.firstChild;var lang=document.querySelector('.ss-fr-lang');(lang||m).insertAdjacentElement('afterend',s);
 s.addEventListener('toggle',function(e){if(!e.target.open)return;[].forEach.call(s.querySelectorAll('details[open]'),function(x){if(x!==e.target)x.open=false})},true);return true}
if(!put()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(put())mo.disconnect()})});mo.observe(document.body,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},15000)}
})();</script>
	<?php
}, 45 );
