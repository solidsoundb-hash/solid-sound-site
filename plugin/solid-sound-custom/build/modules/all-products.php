<?php
/**
 * /all-products/ (page 2 and translations): the hero comes with the HTML.
 * Before: a script in the page content downloaded the whole home page (/en/?hero=1), cut the hero out
 * and inserted it at the top after load -> big layout jump (CLS ~1.0) and slow main content (LCP 10 s+).
 * Now: the same hero (ss_h3_render) is printed by the server at the same place; the script is dropped.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'the_content', function ( $c ) {
	if ( false === strpos( $c, 'ssHero1' ) || ! function_exists( 'ss_h3_render' ) ) return $c;
	$c = preg_replace( '#<script>\(function\(\)\{var K=\'ssHero1\'.*?</script>#s', '', $c );
	return '<div id="ss-old-hero">' . ss_h3_render( 'new' ) . '</div>' . $c;
}, 0 );

/* (15.11.2026 owner) Services in the catalog: a "Services" chip next to Templates / Presets / Music shows the studio services
   as catalog rows (price on the button -> the calculator). /all-products/#services opens them directly. */
add_action( 'wp_footer', function () {
	if ( is_admin() || false === strpos( isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '', 'all-products' ) ) return;
	$C = home_url( '/studio-calculator/?s=' );
	$S = array( /* slug, title, text, from, time, icon path */
		array( 'mixing', 'Mixing + mastering', 'Your stems mixed by our engineer, stereo master included. Unlimited revisions.', 60, '3–5 days', '<path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6"/>' ),
		array( 'mastering', 'Mastering', 'Loud, clean and ready for Spotify, Beatport and clubs. Stereo or stem mastering.', 15, '24–48 h', '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>' ),
		array( 'release', 'Release Ready pack', 'Mix + master + everything a label or distributor asks for.', 109, '5–7 days', '<path d="M12 2l3 7h7l-5.5 4.5 2 7.5L12 16.5 5.5 21l2-7.5L2 9h7z"/>' ),
		array( 'remix', 'Remix', 'Radio or club remix of your track, stems and Ableton project on request.', 150, '5–7 days', '<path d="M17 1l4 4-4 4"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><path d="M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/>' ),
		array( 'remake', 'Remake', 'We rebuild a track part by part — learn how it was made.', 120, '3–5 days', '<path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/>' ),
		array( 'ghost', 'Ghost producing', 'A finished, release-ready track under your name. Full rights.', 300, '7–10 days', '<path d="M9 10h.01M15 10h.01"/><path d="M12 2a8 8 0 0 0-8 8v12l3-3 2.5 3 2.5-3 2.5 3 2.5-3 3 3V10a8 8 0 0 0-8-8z"/>' ),
		array( 'copro', 'Track review / co-producing', 'Written + video feedback on your track, or we work in your project with you.', 40, '2–3 days', '<path d="M21 11.5a8.4 8.4 0 0 1-9 8.4 8.6 8.6 0 0 1-3.9-.9L3 21l2-5a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 8.4-8.4 8.4 8.4 0 0 1 8.5 7.7z"/>' ),
		array( 'post', 'Post production', 'Sound for video, podcasts and film: cleanup, mix, loudness.', 40, '2–4 days', '<rect x="2" y="6" width="14" height="12" rx="2"/><path d="M22 8l-6 4 6 4z"/>' ),
	);
	$h = '';
	foreach ( $S as $s ) {
		$h .= '<div class="ss-svc-i"><span class="ss-svc-ic"><svg viewBox="0 0 24 24" aria-hidden="true">' . $s[5] . '</svg></span>'
			. '<span class="ss-svc-m"><b class="ss-svc-t">' . esc_html( $s[1] ) . '</b><span class="ss-svc-d">' . esc_html( $s[2] ) . '</span>'
			. '<span class="ss-svc-x"><span>DELIVERY:</span> ' . esc_html( $s[4] ) . '</span></span>'
			. '<a class="ss-svc-b" href="' . esc_url( $C . $s[0] ) . '"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3h2l2.4 12.2a2 2 0 0 0 2 1.6h8.8a2 2 0 0 0 2-1.6L22 7H6"/><circle cx="9" cy="21" r="1"/><circle cx="18" cy="21" r="1"/></svg>from €' . (int) $s[3] . '</a></div>';
	}
	?>
<template id="ss-svc-tpl"><div class="ss-svc" hidden><?php echo $h; ?><p class="ss-svc-all"><a href="<?php echo esc_url( home_url( '/en/services-prices/' ) ); ?>">All services &amp; prices →</a></p></div></template>
<style>
.ss-svc-chip label{cursor:pointer}.ss-svc-chip.on label{background:#e09900!important;color:#111!important;border-color:#e09900!important}
.ss-svc-on .w-grid,.ss-svc-on .w-grid-list,.ss-svc-on .g-pagination,.ss-svc-on .w-grid-none,.ss-svc-on .sstb-active{display:none!important}
.ss-svc-mb{display:none}@media(max-width:767px){.sstb .ss-svc-mb{display:inline-flex;align-items:center;order:2;flex:0 0 auto;height:38px;padding:0 12px;margin:0;border:1px solid rgba(255,255,255,.14);border-radius:8px;background:rgba(255,255,255,.04);color:#eee;font:600 14px Roboto,sans-serif;cursor:pointer}.sstb .ss-svc-mb.on{background:#e09900;border-color:#e09900;color:#111}}
.ss-svc{display:grid;gap:10px}.ss-svc[hidden]{display:none}
.ss-svc-i{display:grid;grid-template-columns:96px minmax(0,1fr) auto;gap:18px;align-items:center;padding:12px 14px 12px 12px;border-radius:10px;background:#131b2e;border:1px solid rgba(255,255,255,.08)}
.ss-svc-ic{display:flex;align-items:center;justify-content:center;width:96px;height:96px;border-radius:8px;background:radial-gradient(circle at 30% 20%,rgba(224,153,0,.28),rgba(224,153,0,.06))}
.ss-svc-ic svg{width:40px;height:40px;fill:none;stroke:#e09900;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.ss-svc-m{display:flex;flex-direction:column;gap:4px;min-width:0}
.ss-svc-t{font:700 18px/1.25 Roboto,sans-serif;color:#fff}.ss-svc-d{color:#bdb8b1;font-size:14px;line-height:1.45}
.ss-svc-x{font-size:13px;color:#e09900}.ss-svc-x span{font-size:11px;letter-spacing:.04em;color:#9b9690}
.ss-svc-b{display:inline-flex;align-items:center;gap:7px;height:40px;padding:0 14px 0 12px;border-radius:10px;background:#e09900;color:#111!important;font-weight:800;font-size:15px;white-space:nowrap;text-decoration:none!important}
.ss-svc-b:hover{background:#f5ad12}.ss-svc-b svg{width:20px;height:20px;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.ss-svc-all{margin:4px 0 0!important;text-align:right}.ss-svc-all a{color:#e09900;font-weight:700}
@media(max-width:767px){.ss-svc-i{grid-template-columns:64px minmax(0,1fr);gap:12px}.ss-svc-ic{width:64px;height:64px}.ss-svc-ic svg{width:30px;height:30px}.ss-svc-b{grid-column:1/-1;justify-content:center}}
</style>
<script>(function(){
var done=false;
function setup(){
  if(done)return true;
  var tb=document.querySelector('.sstb');if(!tb)return false;
  var col=tb.closest('.vc_column-inner')||tb.parentNode,grid=col.querySelector('.w-grid'),vals=tb.querySelector('.ssf-fmt .w-filter-item-values');
  if(!vals||!grid)return false; done=true;
  var tpl=document.getElementById('ss-svc-tpl'),box=tpl.content.firstElementChild.cloneNode(true);
  grid.parentNode.insertBefore(box,grid);
  var chip=document.createElement('div');chip.className='w-filter-item-value ss-svc-chip';
  chip.innerHTML='<label role="button" tabindex="0" aria-pressed="false"><span class="w-filter-item-value-label">Services</span></label>';
  vals.appendChild(chip);
  var mb=document.createElement('button');mb.type='button';mb.className='ss-svc-mb';mb.setAttribute('aria-pressed','false');mb.textContent='Services';
  var fl=tb.querySelector('.sstb-filters');if(fl)fl.parentNode.insertBefore(mb,fl.nextSibling);else tb.appendChild(mb);
  var cnt=document.querySelector('.sstb-count'),cntTxt='';
  function set(on){
    col.classList.toggle('ss-svc-on',on);box.hidden=!on;chip.classList.toggle('on',on);chip.firstChild.setAttribute('aria-pressed',on?'true':'false');mb.classList.toggle('on',on);mb.setAttribute('aria-pressed',on?'true':'false');
    if(cnt){if(on){cntTxt=cnt.textContent;cnt.textContent=box.querySelectorAll('.ss-svc-i').length+' services'}else if(cntTxt)cnt.textContent=cntTxt}
    if(on&&location.hash!=='#services')history.replaceState(null,'','#services');
    if(!on&&location.hash==='#services')history.replaceState(null,'',location.pathname+location.search);
  }
  function tog(e){e.preventDefault();set(!chip.classList.contains('on'))}
  chip.firstChild.addEventListener('click',tog);mb.addEventListener('click',tog);
  chip.firstChild.addEventListener('keydown',function(e){if(e.key==='Enter'||e.key===' ')tog(e)});
  /* any product filter switches back to products */
  document.querySelector('.sstb').addEventListener('change',function(e){if(e.target.closest('.w-filter'))set(false)});
  if(location.hash==='#services'){set(true);requestAnimationFrame(function(){var t=document.querySelector('.sstb');if(t)t.scrollIntoView({block:'start'})})}
  return true;
}
function go(){if(setup())return;var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(setup())mo.disconnect()})});mo.observe(document.body,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},15000)}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',go);else go();
})();</script>
	<?php
}, 30 );
