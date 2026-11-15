<?php
/**
 * (15.11.2026 owner) Product page: the buy button always in reach + a cart icon on every buy button.
 * Like big shops do it: a slim bar appears only when the page's own BUY button has scrolled out of view
 * (desktop: slides down under the header; phone: above the bottom menu and the player) and hides again when it is back.
 * The bar's button clicks the real one, so the price, the mixing checkbox and the checkout logic stay in one place.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_footer', function () {
	if ( is_admin() || ! function_exists( 'is_product' ) || ! is_product() ) return;
	$id  = get_queried_object_id();
	$img = get_the_post_thumbnail_url( $id, 'thumbnail' );
	?>
<style>
.ss-cic{display:inline-block;width:20px;height:20px;margin:-3px 8px 0 0;vertical-align:middle;fill:none;stroke:currentColor;stroke-width:2.2;stroke-linecap:round;stroke-linejoin:round;flex:none}
.ss-sb{position:fixed;left:0;right:0;z-index:9400;display:flex;align-items:center;gap:12px;padding:8px max(16px,calc((100vw - 1360px)/2 + 24px));background:rgba(15,20,32,.97);-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px);border:0 solid rgba(255,255,255,.08);box-shadow:0 8px 24px rgba(0,0,0,.35);transition:transform .22s ease,opacity .22s ease;opacity:0;pointer-events:none;font-family:Roboto,sans-serif}
.ss-sb.on{opacity:1;pointer-events:auto;transform:none!important}
.ss-sb img{width:44px;height:44px;border-radius:7px;object-fit:cover;flex:none}
.ss-sb-t{flex:1 1 auto;min-width:0;display:flex;flex-direction:column;gap:1px}
.ss-sb-t b{color:#fff;font-size:15px;line-height:1.25;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.ss-sb-t span{color:#f59e0b;font-size:14px;font-weight:700}.ss-sb-t .ss-sb-old{color:#94a3b8;font-weight:400;margin-right:6px;text-decoration:line-through}.ss-sb-old:before,.ss-sb-old:after{display:none!important}
.ss-sb-b{flex:none;display:inline-flex;align-items:center;justify-content:center;height:44px;padding:0 22px;border:0;border-radius:10px;background:#f59e0b;color:#111;font:800 15px/1 Roboto,sans-serif;letter-spacing:.02em;cursor:pointer;white-space:nowrap}
.ss-sb-b:hover{background:#fbbf24}
@media(min-width:768px){.ss-sb{top:var(--ss-sb-top,72px);border-bottom-width:1px;transform:translateY(-110%)}}
#ssb{transition:transform .22s ease}@media(max-width:767px){body.ss-sb-on #ssb{transform:translateY(-62px)}.ss-sb{bottom:var(--ss-sb-bot,0px);padding:8px 12px;border-top-width:1px;box-shadow:0 -8px 24px rgba(0,0,0,.35);transform:translateY(110%)}.ss-sb img{width:40px;height:40px}.ss-sb-b{padding:0 16px}}
</style>
<script>(function(){
var CART='<svg class="ss-cic" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3h2l2.4 12.2a2 2 0 0 0 2 1.6h8.8a2 2 0 0 0 2-1.6L22 7H6"/><circle cx="9" cy="21" r="1"/><circle cx="18" cy="21" r="1"/></svg>';
var IMG=<?php echo wp_json_encode( $img ? $img : '' ); ?>;
function icon(b){if(!b||b.querySelector('.ss-cic'))return;b.insertAdjacentHTML('afterbegin',CART)}
function init(){
  var btn=document.querySelector('form.cart .single_add_to_cart_button');if(!btn)return false;
  icon(btn);
  var t=(document.querySelector('.product_title,h1')||{}).textContent||'';
  var pr=document.querySelector('.summary .price, .ss-buyrow .price');
  var bar=document.createElement('div');bar.className='ss-sb';bar.setAttribute('aria-hidden','true');
  bar.innerHTML=(IMG?'<img src="'+IMG+'" alt="" width="44" height="44">':'')+'<span class="ss-sb-t"><b></b><span></span></span><button type="button" class="ss-sb-b" tabindex="-1"></button>';
  bar.querySelector('b').textContent=t.trim();
  var bb=bar.querySelector('.ss-sb-b'),ps=bar.querySelector('.ss-sb-t span');
  function sync(){bb.innerHTML=CART+btn.textContent.trim().replace(/^\s+/,'');if(pr){var d=pr.querySelector('del'),i=pr.querySelector('ins');ps.innerHTML='';if(d&&i){var x=document.createElement('s');x.className='ss-sb-old';x.textContent=d.textContent.trim();ps.appendChild(x);ps.appendChild(document.createTextNode(i.textContent.trim()))}else ps.textContent=pr.textContent.replace(/\s+/g,' ').trim()}}
  sync();new MutationObserver(function(){requestAnimationFrame(sync)}).observe(btn,{childList:true,characterData:true,subtree:true});
  bb.addEventListener('click',function(){btn.click()});
  document.body.appendChild(bar);
  /* keep clear of the header (desktop) and of the bottom menu + player (phone) */
  function place(){
    var h=document.getElementById('ssg')||document.querySelector('header.l-header');
    if(h){var r=h.getBoundingClientRect();bar.style.setProperty('--ss-sb-top',Math.max(0,Math.round(r.bottom))+'px')}
    var top=innerHeight;['ssg-bar','ssp'].forEach(function(id){var e=document.getElementById(id);if(!e)return;var r=e.getBoundingClientRect();if(r.height&&getComputedStyle(e).display!=='none'&&getComputedStyle(e).visibility!=='hidden'&&r.top<innerHeight&&r.top>innerHeight/2)top=Math.min(top,r.top)});
    bar.style.setProperty('--ss-sb-bot',Math.max(0,Math.round(innerHeight-top))+'px');
  }
  var shown=false,q=0;
  function show(on){if(on===shown)return;shown=on;if(on)place();bar.classList.toggle('on',on);document.body.classList.toggle('ss-sb-on',on);bar.setAttribute('aria-hidden',on?'false':'true');bb.tabIndex=on?0:-1}
  /* show only after the real button has been scrolled past (not before the visitor reaches it) */
  new IntersectionObserver(function(es){es.forEach(function(e){show(!e.isIntersecting&&e.boundingClientRect.top<0)})},{rootMargin:'-80px 0px 0px 0px'}).observe(btn);
  addEventListener('resize',function(){if(shown&&!q)q=requestAnimationFrame(function(){q=0;place()})},{passive:true});
  document.addEventListener('ssp:state',function(){if(shown)requestAnimationFrame(place)});
  return true;
}
function go(){if(init())return;var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(init())mo.disconnect()})});mo.observe(document.body,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},10000)}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',go);else go();
})();</script>
	<?php
}, 45 );
