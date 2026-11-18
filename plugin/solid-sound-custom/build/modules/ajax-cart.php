<?php
/**
 * Add to cart without leaving the page (owner 17.11.2026: «когда товар добавляешь в корзину — не перекидывало на страницу,
 * а просто появлялось количество в корзине сверху»).
 *  - catalog / grids: WooCommerce's own AJAX buttons; the "go to cart after adding" setting is switched off (option);
 *  - product page: the BUY form is sent in the background (Store API), the header cart count goes up, the button says "Added".
 *    Variable products and "Add mixing" (it continues in the calculator on purpose) keep their old behaviour.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* belt and braces: even if the option is switched back on, never jump to the cart after adding */
add_filter( 'option_woocommerce_cart_redirect_after_add', function () { return 'no'; } );

add_action( 'wp_footer', function () {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) return;
	?>
<style id="ss-acart-css">.single_add_to_cart_button.ss-acart-busy{opacity:.75;pointer-events:none}.single_add_to_cart_button.ss-acart-ok{background:#16a34a!important;border-color:#16a34a!important;color:#fff!important}
@keyframes ss-acart-pop{0%{transform:scale(1)}40%{transform:scale(1.35)}100%{transform:scale(1)}}.ss-acart-pop{animation:ss-acart-pop .5s ease}</style>
<script id="ss-acart">(function(){
var API=<?php echo wp_json_encode( home_url( '/wp-json/wc/store/v1/cart' ) ); ?>,nonce=null;
function getNonce(){return fetch(API,{credentials:'include',headers:{Accept:'application/json'}}).then(function(r){nonce=r.headers.get('Nonce')||r.headers.get('X-WC-Store-API-Nonce')||nonce;return r.json()})}
function add(id,q){return fetch(API+'/add-item',{method:'POST',credentials:'include',headers:{'Content-Type':'application/json',Accept:'application/json',Nonce:nonce||''},body:JSON.stringify({id:+id,quantity:q})}).then(function(r){return r.json()})}
function paint(n){try{localStorage.setItem('ss_cart_n',String(n))}catch(e){}if(window.ssCartPaint)ssCartPaint(n);
  document.querySelectorAll('.ss-cart-n,.ss-mh-n,.w-cart-quantity,[class*=cart] [class*=badge]').forEach(function(b){b.classList.remove('ss-acart-pop');void b.offsetWidth;b.classList.add('ss-acart-pop')})}
function plain(f,id){if(!f.querySelector('input[name=add-to-cart]')){var h=document.createElement('input');h.type='hidden';h.name='add-to-cart';h.value=id;f.appendChild(h)}f.submit()} /* the old way, with the product id the button used to send */
function label(btn,t){var tn=[].filter.call(btn.childNodes,function(n){return n.nodeType===3&&n.nodeValue.trim()}).pop();if(tn)tn.nodeValue=t;else btn.textContent=t}
document.addEventListener('submit',function(e){
  var f=e.target;if(!f.matches||!f.matches('form.cart')||f.classList.contains('variations_form')||f.classList.contains('grouped_form'))return;
  if(e.defaultPrevented)return; /* "Add mixing" took it */
  var btn=f.querySelector('.single_add_to_cart_button'),id=(f.querySelector('[name=add-to-cart]')||btn||{}).value;if(!id||!window.fetch)return;
  e.preventDefault();if(btn.classList.contains('ss-acart-busy'))return;
  var q=Math.max(1,+((f.querySelector('input.qty')||{}).value||1)),old=btn.innerHTML;btn.classList.add('ss-acart-busy');
  var back=function(){btn.classList.remove('ss-acart-busy','ss-acart-ok');btn.innerHTML=old};
  (nonce?Promise.resolve():getNonce()).then(function(){return add(id,q)}).then(function(j){
    if(j&&j.code&&!/already/i.test((j.code||'')+(j.message||'')))return getNonce().then(function(){return add(id,q)});return j}).then(function(j){
    if(j&&j.code&&!/already/i.test((j.code||'')+(j.message||''))){back();plain(f,id);return} /* real error: the normal way shows WooCommerce's message */
    var n=j&&typeof j.items_count==='number'?j.items_count:null;
    if(n===null)getNonce().then(function(c){if(c&&typeof c.items_count==='number')paint(c.items_count)});else paint(n);
    btn.classList.remove('ss-acart-busy');btn.classList.add('ss-acart-ok');label(btn,/already/i.test(JSON.stringify(j||{}))?'Already in cart ✓':'Added to cart ✓');
    if(window.jQuery)try{jQuery(document.body).trigger('wc_fragment_refresh')}catch(x){}
    setTimeout(back,2200);
  }).catch(function(){back();plain(f,id)});
},false);
})();</script>
	<?php
}, 45 );

/* Cart page: removing an item takes the server 4–7 s and showed nothing (owner 18.11: «исправь»).
   Now the row fades at once with "Removing…", and the header count follows as soon as WooCommerce answers. */
add_action( 'wp_footer', function () {
	if ( ! function_exists( 'is_cart' ) || ! is_cart() ) return;
	?>
<style id="ss-crm-css">.woocommerce-cart-form tr.ss-removing{opacity:.45;pointer-events:none;transition:opacity .2s}
.woocommerce-cart-form tr.ss-removing td.product-name:after{content:"Removing…";display:block;margin-top:4px;color:#F59E0B;font-weight:700;font-size:13px}
.woocommerce-cart-form tr.ss-removing a.remove{animation:ss-crm-spin .8s linear infinite}@keyframes ss-crm-spin{to{transform:rotate(360deg)}}</style>
<script id="ss-crm">(function(){
document.addEventListener('click',function(e){var a=e.target.closest&&e.target.closest('.woocommerce-cart-form a.remove');if(!a)return;var tr=a.closest('tr');if(tr)tr.classList.add('ss-removing')},true);
function sync(){fetch(<?php echo wp_json_encode( home_url( '/wp-json/wc/store/v1/cart' ) ); ?>,{credentials:'include',headers:{Accept:'application/json'}}).then(function(r){return r.json()}).then(function(j){if(j&&typeof j.items_count==='number'){try{localStorage.setItem('ss_cart_n',String(j.items_count))}catch(x){}if(window.ssCartPaint)ssCartPaint(j.items_count)}}).catch(function(){})}
if(window.jQuery)jQuery(document.body).on('updated_cart_totals removed_from_cart updated_wc_div',sync);
})();</script>
	<?php
}, 46 );
