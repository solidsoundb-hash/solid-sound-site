<?php
/**
 * (15.11.2026 owner) "Add custom mixing by our engineer (+$65)" — the checkbox from the home page cards,
 * now on every template: catalog rows (/all-products/, search, categories) and the template's own page.
 * Ticking it changes the price on the buy button at once ($13 -> $78). Buying with it ticked puts the template
 * in the cart (Store API, same as the home page) and opens the mixing calculator, where the mix is ordered.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_footer', function () {
	if ( is_admin() ) return;
	$tpl = false;
	if ( function_exists( 'is_product' ) && is_product() ) {
		$tpl = has_term( array( 'templates', 'reconstructed-templates' ), 'product-format', get_queried_object_id() );
		if ( ! $tpl ) return;
	}
	?>
<style>
.ss-mixup{display:flex!important;align-items:center;gap:8px;margin:0!important;padding:8px 0 0!important;border-top:1px solid rgba(255,255,255,.08);font:400 13.5px/1.35 Roboto,sans-serif!important;color:#cbd5e1!important;cursor:pointer;user-select:none}
.ss-mixup input{accent-color:#f59e0b;width:18px!important;height:18px!important;margin:0!important;flex:none}
.ss-mixup b{color:#f59e0b;font-weight:700}
.w-grid.layout_195 .w-grid-item-h>.ss-mixup{grid-column:2/-1}
@media(max-width:767px){.w-grid.layout_195 .w-grid-item-h>.ss-mixup{grid-column:1/-1}}
.ssh .ssh-card .ssh-buyb{display:flex;flex-direction:column;gap:10px;margin-top:auto;padding-top:2px}
.ssh .ssh-mix{border-top:1px solid rgba(255,255,255,.08);padding-top:10px!important}
.ssh .ssh-mix i{flex:none;width:18px;height:18px;border-radius:4px;border:1.5px solid rgba(255,255,255,.5);background:#fff;display:flex;align-items:center;justify-content:center}
.ssh .ssh-mix.on i{background:#f59e0b;border-color:#f59e0b}.ssh .ssh-mix.on i:after{content:'';width:5px;height:9px;border:solid #111;border-width:0 2px 2px 0;transform:rotate(45deg) translate(-1px,-1px)}
.ssh .ssh-buy{display:flex;align-items:center;justify-content:center;min-height:44px;border-radius:10px;background:#f59e0b;color:#111;font:700 15px/1.2 Roboto,sans-serif;cursor:pointer;font-variant-numeric:tabular-nums}
.ssh .ssh-buy:hover{background:#fbbf24}.ssh .ssh-buy.in{background:#e09900}
.single-product .ss-mixup{width:100%;margin:0 0 12px!important;padding:10px 0 0!important}
</style>
<script>(function(){
var MIX=65,CALC=<?php echo wp_json_encode( home_url( '/studio-calculator/?s=mixing' ) ); ?>,API=<?php echo wp_json_encode( home_url( '/wp-json/wc/store/v1/cart' ) ); ?>,TPL=<?php echo $tpl ? 'true' : 'false'; ?>;
var nonce=null,busy=false;
function lab(){var l=document.createElement('label');l.className='ss-mixup';l.innerHTML='<input type="checkbox"><span>Add custom mixing by our engineer <b>(+$'+MIX+')</b></span>';return l}
function price(el,on){ /* "$13" / "Buy · $13" -> +$65 while ticked */
  if(!el)return;if(el.dataset.mx0==null)el.dataset.mx0=el.textContent;var t=el.dataset.mx0;
  el.textContent=on?t.replace(/\$\s?(\d+(?:[.,]\d+)?)/,function(m,n){var v=parseFloat(n.replace(',','.'))+MIX;return '$'+(v%1?v.toFixed(2):v)}):t;
}
function getNonce(){return fetch(API,{credentials:'include',headers:{Accept:'application/json'}}).then(function(r){nonce=r.headers.get('Nonce')||r.headers.get('X-WC-Store-API-Nonce')||nonce;return r.json()})}
function add(pid){return fetch(API+'/add-item',{method:'POST',credentials:'include',headers:{'Content-Type':'application/json',Accept:'application/json',Nonce:nonce||''},body:JSON.stringify({id:+pid,quantity:1})}).then(function(r){return r.json()})}
function buyWithMix(pid,btn){
  if(busy)return;busy=true;if(btn)btn.classList.add('loading');
  var done=function(j){try{if(j&&typeof j.items_count==='number'){localStorage.setItem('ss_cart_n',String(j.items_count));if(window.ssCartPaint)ssCartPaint(j.items_count)}}catch(e){}location.href=CALC};
  (nonce?Promise.resolve():getNonce()).then(function(){return add(pid)}).then(function(j){
    if(j&&j.code&&j.code!=='woocommerce_rest_cart_product_is_already_in_cart'&&!/already/i.test(j.message||''))return getNonce().then(function(){return add(pid)}).then(done);
    done(j);
  }).catch(function(){location.href=CALC});
}
/* catalog rows */
function rows(){
  document.querySelectorAll('.w-grid-item.product-format-templates:not([data-mx]),.w-grid-item.product-format-reconstructed-templates:not([data-mx])').forEach(function(it){
    it.setAttribute('data-mx','1');var h=it.querySelector('.w-grid-item-h');if(!h)return;
    var l=lab();h.appendChild(l);
    l.querySelector('input').addEventListener('change',function(){var b=it.querySelector('.ss-cartbtn .ss-cbp');price(b,this.checked)});
  });
}
window.addEventListener('click',function(e){
  var b=e.target.closest&&e.target.closest('.w-grid-item[data-mx] .ss-cartbtn, .w-grid-item[data-mx] .add_to_cart_button');if(!b)return;
  var it=b.closest('.w-grid-item'),cb=it.querySelector('.ss-mixup input');if(!cb||!cb.checked)return;
  var m=(it.className.match(/\bpost-(\d+)\b/)||[])[1]||b.getAttribute('data-product_id');if(!m)return;
  e.preventDefault();e.stopImmediatePropagation();buyWithMix(m,b);
},true);

/* carousel cards (all-products "What's new"): checkbox + Add to cart like the home page. The card is a link,
   so the controls are spans and the clicks are handled here (no navigation). */
function cards(){
  document.querySelectorAll('.ssh-card:not([data-mx])').forEach(function(c){
    c.setAttribute('data-mx','1');var pid=c.getAttribute('data-pid');if(!pid)return;
    var tag=c.querySelector('.ssh-tag'),ok=tag&&/ssh-tag-templates/.test(tag.className);
    var pr=c.querySelector('.ssh-pr ins')||c.querySelector('.ssh-pr');var base=pr?parseFloat((pr.textContent.match(/(\d+(?:[.,]\d+)?)/)||[0,0])[1]):0;
    var box=document.createElement('span');box.className='ssh-buyb';
    box.innerHTML=(ok?'<span class="ss-mixup ssh-mix" role="checkbox" aria-checked="false" tabindex="0"><i></i><span>Add custom mixing by our engineer (+$'+MIX+')</span></span>':'')+'<span class="ssh-buy" role="button" tabindex="0" data-b="'+base+'">Add to cart · $'+base+'</span>';
    c.appendChild(box);
  });
}
function fmt(v){return v%1?v.toFixed(2):String(v)}
function cardAct(e){
  var t=e.target.closest&&e.target.closest('.ssh-card .ssh-mix,.ssh-card .ssh-buy');if(!t)return;
  if(e.type==='keydown'&&e.key!=='Enter'&&e.key!==' ')return;
  e.preventDefault();e.stopPropagation();
  var c=t.closest('.ssh-card'),buy=c.querySelector('.ssh-buy'),mix=c.querySelector('.ssh-mix');
  if(t===mix){var on=mix.getAttribute('aria-checked')!=='true';mix.setAttribute('aria-checked',on?'true':'false');mix.classList.toggle('on',on);if(!buy.classList.contains('in'))buy.textContent='Add to cart · $'+fmt(+buy.getAttribute('data-b')+(on?MIX:0));return}
  if(buy.classList.contains('in')){location.href=<?php echo wp_json_encode( wc_get_cart_url() ); ?>;return}
  var pid=c.getAttribute('data-pid');
  if(mix&&mix.getAttribute('aria-checked')==='true'){buyWithMix(pid,buy);return}
  if(busy)return;busy=true;buy.textContent='Adding…';
  (nonce?Promise.resolve():getNonce()).then(function(){return add(pid)}).then(function(j){if(j&&j.code&&!/already/i.test(j.message||''))return getNonce().then(function(){return add(pid)});return j}).then(function(j){
    busy=false;try{if(j&&typeof j.items_count==='number'){localStorage.setItem('ss_cart_n',String(j.items_count));if(window.ssCartPaint)ssCartPaint(j.items_count)}}catch(x){}
    buy.classList.add('in');buy.textContent='✓ In cart — checkout';
  }).catch(function(){busy=false;buy.textContent='Add to cart · $'+buy.getAttribute('data-b')});
}
window.addEventListener('click',cardAct,true);window.addEventListener('keydown',cardAct,true);
/* the template's own page */
function single(){
  if(!TPL)return true;var f=document.querySelector('form.cart');if(!f)return false;if(f.dataset.mx)return true;f.dataset.mx=1;
  var btn=f.querySelector('.single_add_to_cart_button');if(!btn)return true;
  var l=lab();var row=f.closest('.ss-buyrow,.ss-pp-buy')||f;row.parentNode.insertBefore(l,row);
  l.querySelector('input').addEventListener('change',function(){price(btn,this.checked)});
  f.addEventListener('submit',function(e){if(!l.querySelector('input').checked)return;e.preventDefault();e.stopImmediatePropagation();buyWithMix(btn.value||f.querySelector('[name=add-to-cart]').value,btn)},true);
  return true;
}
function run(){rows();cards();single()}
run();
var q=0,mo=new MutationObserver(function(){if(q)return;q=requestAnimationFrame(function(){q=0;run()})});
mo.observe(document.body,{childList:true,subtree:true});
})();</script>
	<?php
}, 40 );
