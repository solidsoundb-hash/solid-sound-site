<?php
/**
 * Free page (owner 19.11.2026: «всё, что фри — на страницу Free: оценка трека, продвижение, материалы и т. д.»).
 * Under the title: everything free at Solid Sound in one list; each item opens with what you get and one step
 * (jumps to the form on this page or goes to the Wiki / forum / chat). Customer texts in English.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_footer', function () {
	if ( ! is_page( 2108 ) ) return;
	$items = array(
		array( 'dl', 'Free templates & presets', 'Starter Ableton projects and preset packs — one form, instant access to every free drop.', 'Get free access ↓', '#free-access' ),
		array( 'fb', 'Free track feedback', 'Our engineer listens to your track and tells you what to fix first — arrangement, sound, mix.', 'Send my track ↓', '#free-feedback' ),
		array( 'pr', 'Free advice on promotion & release', 'Not sure how to release or get heard? Ask — we answer for free: release plan, playlists, labels, what to post.', 'Ask a question →', home_url( '/en/contacts/' ) ),
		array( 'wk', 'Free guides — Solid Wiki', 'Short, practical guides on recording, mixing and Ableton from our studio.', 'Read the guides →', home_url( '/en/wiki/' ) ),
		array( 'cm', 'Community', 'Show your track, ask anything, get answers from producers and our engineers.', 'Join the forum →', home_url( '/forum/' ) ),
		array( 'cp', 'Free mix audit coupon', 'Comes with the free pack: a quick audit of your mix with clear next steps.', 'Get it with the pack ↓', '#free-access' ),
	);
	$ic = array( 'dl' => 'M12 4v11M7 10l5 5 5-5M5 20h14', 'fb' => 'M4 5h16v11H8l-4 4z', 'pr' => 'M3 11l15-6v14L3 13zM7 13v5', 'wk' => 'M4 5h7v14H4zM13 5h7v14h-7z', 'cm' => 'M8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM16 11a3 3 0 1 0 0-6M2 20c0-3 3-5 6-5s6 2 6 5M14 15c3 0 6 2 6 5', 'cp' => 'M4 7h16v4a2 2 0 0 0 0 4v4H4v-4a2 2 0 0 0 0-4zM10 7v12' );
	$h = '<section class="ss-fz" aria-label="Everything free"><h2>Everything free at Solid Sound</h2><div class="ss-fz-g">';
	foreach ( $items as $k => $x ) {
		$h .= '<details' . ( 0 === $k ? ' open' : '' ) . '><summary><span class="i"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' . $ic[ $x[0] ] . '"/></svg></span>' . esc_html( $x[1] ) . '</summary><p>' . esc_html( $x[2] ) . '</p><a href="' . esc_attr( $x[4] ) . '">' . esc_html( $x[3] ) . '</a></details>';
	}
	$h .= '</div></section>';
	?>
<style id="ss-fz-css">.ss-fz{max-width:1000px;margin:0 auto 26px;padding:0 16px;font-family:"Exo 2",system-ui,sans-serif}
.ss-fz h2{margin:0 0 12px;color:#fff;font-size:22px;font-weight:800;text-align:center}
.ss-fz-g{display:grid;grid-template-columns:1fr 1fr;gap:8px 12px}@media(max-width:760px){.ss-fz-g{grid-template-columns:1fr}}
.ss-fz details{border:1px solid rgba(255,255,255,.08);border-radius:10px;background:#131b2e}.ss-fz details[open]{border-color:rgba(245,158,11,.35);background:linear-gradient(180deg,rgba(245,158,11,.08),#131b2e)}
.ss-fz summary{list-style:none;cursor:pointer;display:flex;align-items:center;gap:10px;padding:12px 14px;color:#fff;font-weight:700;font-size:15px}.ss-fz summary::-webkit-details-marker{display:none}
.ss-fz summary:after{content:"+";margin-left:auto;color:#f59e0b;font-weight:800;font-size:18px}.ss-fz details[open] summary:after{content:"–"}
.ss-fz .i{display:flex;align-items:center;justify-content:center;flex:none;width:34px;height:34px;border-radius:8px;background:rgba(52,211,153,.14);color:#34d399}
.ss-fz details p{margin:0;padding:0 14px 8px 58px;color:#cbd5e1;font:400 14px/1.5 Roboto,sans-serif}
.ss-fz details a{display:inline-block;margin:0 14px 12px 58px;color:#f59e0b!important;font-weight:700;text-decoration:none}
@media(max-width:520px){.ss-fz details p,.ss-fz details a{padding-left:0;margin-left:14px}}</style>
<script id="ss-fz-js">(function(){var H=<?php echo wp_json_encode( $h ); ?>;
function byTitle(re){return [].filter.call(document.querySelectorAll('.l-main h2'),function(h){return re.test(h.textContent)})[0]}
function put(){if(document.querySelector('.ss-fz'))return true;var h1=document.querySelector('.l-main h1');if(!h1)return false;
 /* anchors for the two forms on this page */
 var a=byTitle(/get free access|gratis-zugang|accès gratuit/i),f=byTitle(/free track feedback|gratis track-feedback|avis gratuit/i); /* + translated titles (modules/i18n-dict-*.php) */if(a&&!document.getElementById('free-access'))a.id='free-access';if(f&&!document.getElementById('free-feedback'))f.id='free-feedback';
 var d=document.createElement('div');d.innerHTML=H;var s=d.firstChild;
 var box=h1.closest('.wpb_wrapper,.w-text,.vc_column-inner')||h1.parentNode;var after=box.querySelector('p')||h1;after.insertAdjacentElement('afterend',s);
 s.addEventListener('toggle',function(e){if(!e.target.open)return;[].forEach.call(s.querySelectorAll('details[open]'),function(x){if(x!==e.target)x.open=false})},true);
 s.addEventListener('click',function(e){var l=e.target.closest&&e.target.closest('a[href^="#"]');if(!l)return;var t=document.getElementById(l.getAttribute('href').slice(1));if(!t)return;e.preventDefault();t.scrollIntoView({behavior:'smooth',block:'start'})});if(location.hash){var t=document.getElementById(location.hash.slice(1));if(t)setTimeout(function(){t.scrollIntoView({behavior:'smooth',block:'start'})},300)} /* links from the home «Free» tab */
 return true}
if(!put()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(put())mo.disconnect()})});mo.observe(document.body,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},15000)}
})();</script>
	<?php
}, 45 );
