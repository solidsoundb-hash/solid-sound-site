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
.ss-dd{position:fixed;z-index:100001;width:380px;max-width:calc(100vw - 24px);box-sizing:border-box;border-radius:14px;background:#111827;border:1px solid rgba(255,255,255,.12);box-shadow:0 18px 50px rgba(0,0,0,.55);color:#E2E8F0;font:400 14px/1.4 Roboto,sans-serif;opacity:0;transform:translateY(-6px);transition:opacity .12s,transform .12s;pointer-events:none}
.ss-dd.on{opacity:1;transform:none;pointer-events:auto}.ss-dd:before{content:'';position:absolute;top:-7px;right:var(--ar,40px);width:12px;height:12px;background:#111827;border-left:1px solid rgba(255,255,255,.12);border-top:1px solid rgba(255,255,255,.12);transform:rotate(45deg)}
.ss-dd .h{display:flex;justify-content:space-between;align-items:baseline;padding:14px 16px 10px;font:700 16px "Exo 2",sans-serif;color:#fff}.ss-dd .h small{color:#94A3B8;font:500 13px Roboto,sans-serif}
.ss-dd ul{list-style:none;margin:0!important;padding:0 8px!important;max-height:min(360px,55vh);overflow-y:auto}
.ss-dd li{display:grid;grid-template-columns:56px minmax(0,1fr) auto 28px;align-items:center;gap:10px;margin:0!important;padding:8px!important;border-radius:10px;transition:background .15s,opacity .25s}.ss-dd li:before{display:none!important}.ss-dd li:hover{background:rgba(255,255,255,.04)}
.ss-dd li img{width:56px;height:56px;border-radius:8px;object-fit:cover;display:block;background:#1f2937}
.ss-dd li a.n{color:#F1F5F9!important;font-weight:600;text-decoration:none!important;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}.ss-dd li a.n:hover{color:#F59E0B!important}.ss-dd li .q{display:block;color:#94A3B8;font-size:12.5px;margin-top:2px}
.ss-dd li .p{font:700 15px "Exo 2",sans-serif;color:#F59E0B;white-space:nowrap}.ss-dd li .p del{display:block;color:#64748B;font:500 12px Roboto,sans-serif;text-align:right}
.ss-dd li .x{width:28px;height:28px;border-radius:8px;border:0;background:none;color:#94A3B8;font:400 20px/1 sans-serif;cursor:pointer;padding:0}.ss-dd li .x:hover{background:rgba(239,68,68,.15);color:#F87171}
.ss-dd li.out{opacity:0}.ss-dd .t{display:flex;justify-content:space-between;align-items:center;margin:6px 16px 0;padding:12px 0;border-top:1px solid rgba(255,255,255,.1);font:600 15px Roboto,sans-serif}.ss-dd .t b{font:800 20px "Exo 2",sans-serif;color:#fff}
.ss-dd .f{display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:0 16px 16px}.ss-dd .f a{height:44px;display:flex;align-items:center;justify-content:center;border-radius:8px;font:700 15px "Exo 2",sans-serif;text-decoration:none!important}
.ss-dd .f .v{border:1px solid rgba(255,255,255,.2);color:#fff!important}.ss-dd .f .v:hover{border-color:#F59E0B;color:#F59E0B!important}.ss-dd .f .c{background:#F59E0B;color:#111!important}.ss-dd .f .c:hover{background:#FBBF24}
.ss-dd .e{padding:26px 16px 20px;text-align:center;color:#94A3B8}.ss-dd .e a{display:inline-block;margin-top:12px;padding:10px 18px;border-radius:8px;background:#F59E0B;color:#111!important;font:700 14px "Exo 2",sans-serif;text-decoration:none!important}
.ss-dd .ld{padding:28px;text-align:center;color:#94A3B8}

.ss-dd .ad{height:32px;padding:0 12px;border:0;border-radius:8px;background:#F59E0B;color:#111;font:700 13px "Exo 2",sans-serif;cursor:pointer;white-space:nowrap}.ss-dd .ad:hover{background:#FBBF24}.ss-dd .ad.ok{background:#16a34a;color:#fff}
#ss-fv li{grid-template-columns:56px minmax(0,1fr) auto 28px}#ss-fv li .p{display:block;margin-top:2px;font-size:13.5px;white-space:normal}#ss-fv li>div{min-width:0}@media(max-width:700px){#ss-fv li .p del{display:block!important}.ss-dd .ad{padding:0 10px}}
.ss-fvn{position:absolute;top:-4px;right:-8px;min-width:18px;height:18px;padding:0 5px;box-sizing:border-box;border-radius:9px;background:#F59E0B;color:#111;font:700 11px/18px Roboto,sans-serif;text-align:center}
@media(max-width:700px),(hover:none){.ss-dd{left:8px!important;right:8px;top:auto!important;bottom:76px;width:auto;max-width:none}.ss-dd:before{display:none}}
</style>
<script id="ss-mc-js">(function(){
if(!window.matchMedia||!matchMedia('(hover:hover) and (pointer:fine)').matches||!window.fetch)return;
var API=<?php echo wp_json_encode( $api ); ?>,CART=<?php echo wp_json_encode( wc_get_cart_url() ); ?>,CO=<?php echo wp_json_encode( wc_get_checkout_url() ); ?>,SHOP=<?php echo wp_json_encode( $all ? get_permalink( $all ) : home_url( '/all-products/' ) ); ?>;
var P,nonce='',hideT,anchor,busy;
function esc(s){return String(s==null?'':s).replace(/[&<>"']/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]})}
function dec(s){var d=document.createElement('textarea');d.innerHTML=String(s||'').replace(/<[^>]+>/g,'');return d.value}
function money(v,c){var m=+(c.currency_minor_unit||2),n=(+v)/Math.pow(10,m),s=n%1?n.toFixed(m):String(Math.round(n));return (c.currency_prefix||'')+s+(c.currency_suffix||'')}
function panel(){if(P)return P;P=document.createElement('div');P.id='ss-mc';P.className='ss-dd';P.setAttribute('role','dialog');P.setAttribute('aria-label','Cart');document.body.appendChild(P);
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
function show(a){clearTimeout(hideT);anchor=a;panel();document.querySelectorAll('.ss-dd.on').forEach(function(o){if(o!==P)o.classList.remove('on')});if(!P.classList.contains('on')){P.innerHTML='<div class="ld">Loading…</div>';place();P.classList.add('on');load().then(paint).catch(function(){location.href=CART})}else place()}
function hide(){clearTimeout(hideT);hideT=setTimeout(function(){if(P)P.classList.remove('on')},160)}
function cartLink(t){var it=t.closest?t.closest('#ssg .g-r-it,.l-header .w-cart'):null;if(!it)return null;if(it.closest('#ss-mc'))return null;if(it.classList.contains('g-r-it')){var l=it.querySelector('.g-l');if(!/cart/i.test(l?l.textContent:''))return null}return it}
document.addEventListener('mouseover',function(e){var a=cartLink(e.target);if(a)show(a)});
document.addEventListener('mouseout',function(e){var a=cartLink(e.target);if(!a)return;var to=e.relatedTarget;if(to&&(a.contains(to)||(P&&P.contains(to))))return;hide()});
addEventListener('scroll',function(){if(P&&P.classList.contains('on'))place()},{passive:true});addEventListener('resize',function(){if(P)place()});
document.addEventListener('keydown',function(e){if(e.key==='Escape'&&P)P.classList.remove('on')});
if(window.jQuery)jQuery(document.body).on('added_to_cart',function(){if(P&&P.classList.contains('on'))load().then(paint)});
})();</script>
<script id="ss-fv-js">(function(){
if(!window.fetch)return;var API=<?php echo wp_json_encode( home_url( '/wp-json/wc/store/v1' ) ); ?>,AJ=<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>,ACC=<?php echo wp_json_encode( is_user_logged_in() && function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) . '#favourites' : '' ); ?>,SHOP=<?php echo wp_json_encode( $all ? get_permalink( $all ) : home_url( '/all-products/' ) ); ?>;
var hoverOk=window.matchMedia?matchMedia('(hover:hover) and (pointer:fine)').matches:false,P,anchor,hideT,nonce='';
function ids(){try{return JSON.parse(localStorage.getItem('ss_liked')||'[]').map(String).filter(function(x){return /^[0-9]+$/.test(x)})}catch(e){return []}}
function setIds(a){try{localStorage.setItem('ss_liked',JSON.stringify(a))}catch(e){}badge()}
function esc(s){return String(s==null?'':s).replace(/[&<>"']/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]})}
function dec(s){var d=document.createElement('textarea');d.innerHTML=String(s||'').replace(/<[^>]+>/g,'');return d.value}
function money(v,c){var m=+(c.currency_minor_unit||2),n=(+v)/Math.pow(10,m);return (c.currency_prefix||'')+(n%1?n.toFixed(m):String(Math.round(n)))+(c.currency_suffix||'')}
function favItem(){var it=[].slice.call(document.querySelectorAll('#ssg .g-r-it')).filter(function(x){var l=x.querySelector('.g-l');return l?/favou?rites/i.test(l.textContent):false})[0];return it||null}
function badge(){var it=favItem(),n=ids().length;if(!it)return;var a=it.querySelector('.g-a')||it,b=a.querySelector('.ss-fvn');if(!b){if(!n)return;b=document.createElement('span');b.className='ss-fvn';a.style.position='relative';a.appendChild(b)}b.textContent=n;b.style.display=n?'':'none'}
function panel(){if(P)return P;P=document.createElement('div');P.id='ss-fv';P.className='ss-dd';P.setAttribute('role','dialog');P.setAttribute('aria-label','Favorites');document.body.appendChild(P);
 P.addEventListener('mouseenter',function(){clearTimeout(hideT)});P.addEventListener('mouseleave',function(){if(hoverOk)hide()});
 P.addEventListener('click',function(e){var x=e.target.closest('.x');if(x){e.preventDefault();unlike(x.closest('li'));return}var ad=e.target.closest('.ad');if(ad){e.preventDefault();add(ad)}});return P}
function place(){if(!P)return;if(!hoverOk||!anchor){P.style.top='';return}var r=anchor.getBoundingClientRect(),w=P.offsetWidth||380,left=Math.min(innerWidth-w-12,Math.max(12,r.right-w+20));P.style.top=(r.bottom+10)+'px';P.style.left=left+'px';P.style.setProperty('--ar',Math.max(16,Math.min(w-28,r.left+r.width/2-left-6))+'px')}
function paint(list){var n=list.length;
 if(!n){P.innerHTML='<div class="e">No favorites yet.<br>Tap ♡ on any product to save it here.<br><a href="'+esc(SHOP)+'">Browse products</a></div>';place();return}
 var h='<div class="h">Favorites <small>'+n+(n===1?' item':' items')+'</small></div><ul>';
 list.forEach(function(p){var pr=p.prices||{},img=(p.images&&p.images[0])?(p.images[0].thumbnail||p.images[0].src):'',reg=+pr.regular_price,now=+pr.price;
  h+='<li data-id="'+p.id+'">'+(img?'<img src="'+esc(img)+'" alt="" width="56" height="56" loading="lazy">':'<img alt="">')+'<div><a class="n" href="'+esc(p.permalink)+'">'+esc(dec(p.name))+'</a><span class="p">'+(reg>now?'<del style="display:inline;margin-right:4px">'+money(reg,pr)+'</del>':'')+money(now,pr)+'</span></div>'+(p.is_purchasable!==false?'<button type="button" class="ad" data-id="'+p.id+'">Add to cart</button>':'<span></span>')+'<button type="button" class="x" aria-label="Remove from favorites" title="Remove">×</button></li>'});
 h+='</ul>'+(ACC?'<div class="f" style="grid-template-columns:1fr;padding-top:12px"><a class="v" href="'+esc(ACC)+'">Open in my account</a></div>':'<div style="height:10px"></div>');P.innerHTML=h;place()}
function load(){var a=ids();if(!a.length){paint([]);return Promise.resolve()}return fetch(API+'/products?per_page=50&include='+a.join(','),{credentials:'include',headers:{Accept:'application/json'}}).then(function(r){return r.json()}).then(function(l){l=Array.isArray(l)?l:[];l.sort(function(x,y){return a.indexOf(String(y.id))-a.indexOf(String(x.id))});paint(l)})}
function unlike(li){if(!li)return;var id=li.getAttribute('data-id'),a=ids().filter(function(x){return x!==id});setIds(a);li.classList.add('out');
 document.querySelectorAll('.ss-like[data-pid="'+id+'"]').forEach(function(b){b.classList.remove('is-liked')});
 var f=new FormData();f.append('action','ss_rt_like');f.append('pid',id);fetch(AJ,{method:'POST',body:f,credentials:'same-origin'}).then(function(r){return r.json()}).then(function(r){if(r&&r.success&&r.data.liked){var f2=new FormData();f2.append('action','ss_rt_like');f2.append('pid',id);fetch(AJ,{method:'POST',body:f2,credentials:'same-origin'})}}).catch(function(){});
 setTimeout(function(){li.remove();if(!P.querySelector('li'))paint([]);else{var s=P.querySelector('.h small'),n=P.querySelectorAll('li').length;if(s)s.textContent=n+(n===1?' item':' items')}},260)}
function add(b){if(b.classList.contains('ok'))return;b.textContent='…';
 fetch(API+'/cart',{credentials:'include',headers:{Accept:'application/json'}}).then(function(r){nonce=r.headers.get('Nonce')||nonce;return r.json()})
 .then(function(){return fetch(API+'/cart/add-item',{method:'POST',credentials:'include',headers:{'Content-Type':'application/json',Accept:'application/json',Nonce:nonce},body:JSON.stringify({id:+b.getAttribute('data-id'),quantity:1})})})
 .then(function(r){return r.json()}).then(function(c){b.textContent='Added ✓';b.classList.add('ok');if(c&&c.items_count!=null){if(window.ssCartPaint)ssCartPaint(c.items_count)}if(window.jQuery)jQuery(document.body).trigger('wc_fragment_refresh')})
 .catch(function(){b.textContent='Add to cart'})}
function show(a){clearTimeout(hideT);anchor=a;panel();document.querySelectorAll('.ss-dd.on').forEach(function(o){if(o!==P)o.classList.remove('on')});if(!P.classList.contains('on')){P.innerHTML='<div class="ld">Loading…</div>';place();P.classList.add('on');load().catch(function(){paint([])})}else place()}
function hide(){clearTimeout(hideT);hideT=setTimeout(function(){if(P)P.classList.remove('on')},160)}
function isFav(t){var it=t.closest?t.closest('#ssg .g-r-it'):null;if(it){var l=it.querySelector('.g-l');if(l?/favou?rites/i.test(l.textContent):false)return it}var bl=t.closest?t.closest('#ssg-bar a'):null;if(bl?/saved/i.test(bl.textContent):false)return bl;return null}
if(hoverOk){document.addEventListener('mouseover',function(e){var a=isFav(e.target);if(a)show(a)});document.addEventListener('mouseout',function(e){var a=isFav(e.target);if(!a)return;var to=e.relatedTarget;if(to?(a.contains(to)||(P?P.contains(to):false)):false)return;hide()})}
/* a tap / click on Favorites opens the list here (guests used to land on the login page) */
document.addEventListener('click',function(e){var a=isFav(e.target);if(!a)return;if(e.metaKey||e.ctrlKey)return;e.preventDefault();if(hoverOk){show(a);return}if(P?P.classList.contains('on'):false){P.classList.remove('on');return}anchor=null;show(null)},true);
document.addEventListener('click',function(e){if(!P||!P.classList.contains('on'))return;if(P.contains(e.target)||isFav(e.target))return;P.classList.remove('on')});
document.addEventListener('keydown',function(e){if(e.key==='Escape'?P:false)P.classList.remove('on')});
addEventListener('scroll',function(){if(P?P.classList.contains('on'):false)place()},{passive:true});
document.addEventListener('click',function(e){if(e.target.closest?e.target.closest('.ss-like[data-pid]'):null)setTimeout(badge,50)});
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',function(){setTimeout(badge,300)});else setTimeout(badge,300);
})();</script>
	<?php
}, 70 );
