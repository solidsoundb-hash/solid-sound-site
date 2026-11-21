<?php
/**
 * Mini cart on hover (owner 22.11.2026: «сделай аджакс: когда на корзину наводишь — чтобы видно было, что там, и крестик,
 * как у магазина типа Rozetka»). Desktop (mouse) only: hovering «Cart» in the header opens a panel with the items
 * (cover, name, price, × remove without reload), the total and «View cart» / «Checkout». Phones keep the tap → cart page.
 * Data: WooCommerce Store API (/wp-json/wc/store/v1/cart), loaded only on the first hover.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_footer', function () {
	if ( is_admin() || ! function_exists( 'wc_get_cart_url' ) ) return;
	if ( ( function_exists( 'is_cart' ) && is_cart() ) || ( function_exists( 'is_checkout' ) && is_checkout() ) ) return;
	$api = home_url( '/wp-json/wc/store/v1/cart' );
	$all = get_page_by_path( 'all-products' ); ?>
<style id="ss-mc-css">
#ss-mc{position:fixed;z-index:100001;width:380px;max-width:calc(100vw - 24px);box-sizing:border-box;border-radius:14px;background:#111827;border:1px solid rgba(255,255,255,.12);box-shadow:0 18px 50px rgba(0,0,0,.55);color:#E2E8F0;font:400 14px/1.4 Roboto,sans-serif;opacity:0;transform:translateY(-6px);transition:opacity .15s,transform .15s;pointer-events:none}
#ss-mc.on{opacity:1;transform:none;pointer-events:auto}#ss-mc:before{content:'';position:absolute;top:-7px;right:var(--ar,40px);width:12px;height:12px;background:#111827;border-left:1px solid rgba(255,255,255,.12);border-top:1px solid rgba(255,255,255,.12);transform:rotate(45deg)}
#ss-mc .h{display:flex;justify-content:space-between;align-items:baseline;padding:14px 16px 10px;font:700 16px "Exo 2",sans-serif;color:#fff}#ss-mc .h small{color:#94A3B8;font:500 13px Roboto,sans-serif}
#ss-mc ul{list-style:none;margin:0!important;padding:0 8px!important;max-height:min(360px,55vh);overflow-y:auto}
#ss-mc li{display:grid;grid-template-columns:56px minmax(0,1fr) auto 28px;align-items:center;gap:10px;margin:0!important;padding:8px!important;border-radius:10px;transition:background .15s,opacity .25s}#ss-mc li:before{display:none!important}#ss-mc li:hover{background:rgba(255,255,255,.04)}
#ss-mc li img{width:56px;height:56px;border-radius:8px;object-fit:cover;display:block;background:#1f2937}
#ss-mc li a.n{color:#F1F5F9!important;font-weight:600;text-decoration:none!important;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}#ss-mc li a.n:hover{color:#F59E0B!important}#ss-mc li .q{display:block;color:#94A3B8;font-size:12.5px;margin-top:2px}
#ss-mc li .p{font:700 15px "Exo 2",sans-serif;color:#F59E0B;white-space:nowrap}#ss-mc li .p del{display:block;color:#64748B;font:500 12px Roboto,sans-serif;text-align:right}
#ss-mc li .x{width:28px;height:28px;border-radius:8px;border:0;background:none;color:#94A3B8;font:400 20px/1 sans-serif;cursor:pointer;padding:0}#ss-mc li .x:hover{background:rgba(239,68,68,.15);color:#F87171}
#ss-mc li.out{opacity:0}#ss-mc .t{display:flex;justify-content:space-between;align-items:center;margin:6px 16px 0;padding:12px 0;border-top:1px solid rgba(255,255,255,.1);font:600 15px Roboto,sans-serif}#ss-mc .t b{font:800 20px "Exo 2",sans-serif;color:#fff}
#ss-mc .f{display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:0 16px 16px}#ss-mc .f a{height:44px;display:flex;align-items:center;justify-content:center;border-radius:8px;font:700 15px "Exo 2",sans-serif;text-decoration:none!important}
#ss-mc .f .v{border:1px solid rgba(255,255,255,.2);color:#fff!important}#ss-mc .f .v:hover{border-color:#F59E0B;color:#F59E0B!important}#ss-mc .f .c{background:#F59E0B;color:#111!important}#ss-mc .f .c:hover{background:#FBBF24}
#ss-mc .e{padding:26px 16px 20px;text-align:center;color:#94A3B8}#ss-mc .e a{display:inline-block;margin-top:12px;padding:10px 18px;border-radius:8px;background:#F59E0B;color:#111!important;font:700 14px "Exo 2",sans-serif;text-decoration:none!important}
#ss-mc .ld{padding:28px;text-align:center;color:#94A3B8}
</style>
<script id="ss-mc-js">(function(){
if(!window.matchMedia||!matchMedia('(hover:hover) and (pointer:fine)').matches||!window.fetch)return;
var API=<?php echo wp_json_encode( $api ); ?>,CART=<?php echo wp_json_encode( wc_get_cart_url() ); ?>,CO=<?php echo wp_json_encode( wc_get_checkout_url() ); ?>,SHOP=<?php echo wp_json_encode( $all ? get_permalink( $all ) : home_url( '/all-products/' ) ); ?>;
var P,nonce='',hideT,anchor,busy;
function esc(s){return String(s==null?'':s).replace(/[&<>"']/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]})}
function dec(s){var d=document.createElement('textarea');d.innerHTML=String(s||'').replace(/<[^>]+>/g,'');return d.value}
function money(v,c){var m=+(c.currency_minor_unit||2),n=(+v)/Math.pow(10,m),s=n.toFixed(m);return (c.currency_prefix||'')+s+(c.currency_suffix||'')}
function panel(){if(P)return P;P=document.createElement('div');P.id='ss-mc';P.setAttribute('role','dialog');P.setAttribute('aria-label','Cart');document.body.appendChild(P);
 P.addEventListener('mouseenter',function(){clearTimeout(hideT)});P.addEventListener('mouseleave',hide);
 P.addEventListener('click',function(e){var x=e.target.closest('.x');if(!x)return;e.preventDefault();remove(x.closest('li'))});return P}
function place(){if(!anchor||!P)return;var r=anchor.getBoundingClientRect(),w=P.offsetWidth||380,left=Math.min(innerWidth-w-12,Math.max(12,r.right-w+20));P.style.top=(r.bottom+10)+'px';P.style.left=left+'px';P.style.setProperty('--ar',Math.max(16,Math.min(w-28,r.left+r.width/2-left-6))+'px')}
function load(){return fetch(API,{credentials:'include',headers:{Accept:'application/json'}}).then(function(r){nonce=r.headers.get('Nonce')||r.headers.get('X-WC-Store-API-Nonce')||nonce;return r.json()})}
function paint(c){var items=(c.items||[]),n=c.items_count||0;if(window.ssCartPaint)ssCartPaint(n);
 if(!items.length){P.innerHTML='<div class="e">Your cart is empty.<br><a href="'+esc(SHOP)+'">Browse products</a></div>';place();return}
 var h='<div class="h">Cart <small>'+n+(n===1?' item':' items')+'</small></div><ul>';
 items.forEach(function(it){var pr=it.prices||{},img=(it.images&&it.images[0])?(it.images[0].thumbnail||it.images[0].src):'',reg=+pr.regular_price,now=+pr.price;
  h+='<li data-k="'+esc(it.key)+'">'+(img?'<img src="'+esc(img)+'" alt="" width="56" height="56" loading="lazy">':'<img alt="">')+'<div><a class="n" href="'+esc(it.permalink)+'">'+esc(dec(it.name))+'</a>'+(it.quantity>1?'<span class="q">× '+it.quantity+'</span>':'')+'</div><span class="p">'+(reg>now?'<del>'+money(reg*it.quantity,pr)+'</del>':'')+money(now*it.quantity,pr)+'</span><button type="button" class="x" aria-label="Remove '+esc(dec(it.name))+'" title="Remove">×</button></li>'});
 var t=c.totals||{};h+='</ul><div class="t"><span>Total</span><b>'+money(t.total_price,t)+'</b></div><div class="f"><a class="v" href="'+esc(CART)+'">View cart</a><a class="c" href="'+esc(CO)+'">Checkout</a></div>';
 P.innerHTML=h;place()}
function remove(li){if(!li||busy)return;busy=1;li.classList.add('out');
 fetch(API+'/remove-item',{method:'POST',credentials:'include',headers:{'Content-Type':'application/json',Accept:'application/json',Nonce:nonce},body:JSON.stringify({key:li.getAttribute('data-k')})})
 .then(function(r){nonce=r.headers.get('Nonce')||nonce;return r.json()}).then(function(c){busy=0;if(c&&c.items)paint(c);else return load().then(paint);if(window.jQuery)jQuery(document.body).trigger('wc_fragment_refresh')})
 .catch(function(){busy=0;li.classList.remove('out')})}
function show(a){clearTimeout(hideT);anchor=a;panel();if(!P.classList.contains('on')){P.innerHTML='<div class="ld">Loading…</div>';place();P.classList.add('on');load().then(paint).catch(function(){location.href=CART})}else place()}
function hide(){clearTimeout(hideT);hideT=setTimeout(function(){if(P)P.classList.remove('on')},280)}
function cartLink(t){var it=t.closest?t.closest('#ssg .g-r-it,.l-header .w-cart'):null;if(!it)return null;if(it.closest('#ss-mc'))return null;if(it.classList.contains('g-r-it')){var l=it.querySelector('.g-l');if(!/cart/i.test(l?l.textContent:''))return null}return it}
document.addEventListener('mouseover',function(e){var a=cartLink(e.target);if(a)show(a)});
document.addEventListener('mouseout',function(e){var a=cartLink(e.target);if(!a)return;var to=e.relatedTarget;if(to&&(a.contains(to)||(P&&P.contains(to))))return;hide()});
addEventListener('scroll',function(){if(P&&P.classList.contains('on'))place()},{passive:true});addEventListener('resize',function(){if(P)place()});
document.addEventListener('keydown',function(e){if(e.key==='Escape'&&P)P.classList.remove('on')});
if(window.jQuery)jQuery(document.body).on('added_to_cart',function(){if(P&&P.classList.contains('on'))load().then(paint)});
})();</script>
	<?php
}, 70 );
