<?php
/**
 * (15.11.2026 owner) "✦ Try it free for your email" on every product, like the home page cards:
 * catalog rows, the all-products carousel and the product page. One small dialog asks for the email;
 * the request goes to the same place as the chat questions (WP admin -> Chat questions + e-mail to the owner,
 * via the existing ss_bot_lead handler), and the owner sends the free demo.
 * The home page's "Download demo" button (it did nothing before) is wired to the same handler.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_footer', function () {
	if ( is_admin() || ( function_exists( 'is_checkout' ) && is_checkout() ) || ( function_exists( 'is_cart' ) && is_cart() ) ) return;
	$single = '';
	if ( function_exists( 'is_product' ) && is_product() ) {
		$id = get_queried_object_id();
		$t  = wp_get_post_terms( $id, 'product-format', array( 'fields' => 'slugs' ) );
		$single = ( ! is_wp_error( $t ) && array_intersect( array( 'templates', 'reconstructed-templates' ), $t ) ) ? 'templates' : ( ( ! is_wp_error( $t ) && in_array( 'presets', $t, true ) ) ? 'presets' : 'music' );
	}
	?>
<style>
.ss-fd-l{display:inline-flex!important;align-items:center;gap:6px;margin:0!important;padding:0!important;border:0!important;background:none!important;color:#34d399!important;font:700 13.5px/1.3 Roboto,sans-serif!important;cursor:pointer;text-decoration:none!important}
.ss-fd-l:hover{color:#6ee7b7!important;text-decoration:underline!important}
.w-grid.layout_195 .w-grid-item-h>.ss-fd-l{grid-column:2/-1;justify-self:start}
@media(max-width:767px){.w-grid.layout_195 .w-grid-item-h>.ss-fd-l{grid-column:1/-1}}
.ssh .ssh-buyb .ss-fd-l{justify-content:center}
.single-product .ss-fd-l{margin:12px 0 0!important}
.ss-fd{position:fixed;inset:0;z-index:100050;display:flex;align-items:center;justify-content:center;padding:16px;background:rgba(0,0,0,.6)}
.ss-fd[hidden]{display:none}
.ss-fd-b{position:relative;width:100%;max-width:400px;padding:22px;border-radius:14px;background:#131b2e;border:1px solid rgba(255,255,255,.1);box-shadow:0 20px 50px rgba(0,0,0,.6);color:#e2e8f0;font-family:Roboto,sans-serif;display:flex;flex-direction:column;gap:10px}
.ss-fd-k{font-size:11px;font-weight:700;letter-spacing:.12em;color:#34d399}
.ss-fd-b b{font-size:19px;line-height:1.25;color:#fff}
.ss-fd-b p{margin:0;font-size:14px;line-height:1.5;color:#cbd5e1}
.ss-fd-b label{font-size:12px;color:#94a3b8;margin-top:2px}
.ss-fd-b input[type=email]{height:46px;padding:0 12px;border-radius:9px;border:1px solid rgba(255,255,255,.14);background:#0b1220;color:#fff;font-size:16px}
.ss-fd-b input[type=email]:focus{outline:none;border-color:#34d399}
.ss-fd-s{min-height:46px;border:0;border-radius:9px;background:#34d399;color:#07130d;font:800 15px Roboto,sans-serif;cursor:pointer}
.ss-fd-s:disabled{opacity:.6}
.ss-fd-x{position:absolute;top:10px;right:10px;width:36px;height:36px;border:0;border-radius:8px;background:none;color:#94a3b8;font-size:22px;line-height:1;cursor:pointer}
.ss-fd-m{min-height:18px;font-size:13px}.ss-fd-m.ok{color:#34d399}.ss-fd-m.bad{color:#f87171}
.ss-fd-hp{position:absolute!important;left:-9999px!important;width:1px;height:1px;opacity:0}
</style>
<script>(function(){
var AJAX=<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>,SINGLE=<?php echo wp_json_encode( $single ); ?>;
var TXT={templates:['lite demo','We’ll email you a lite demo of %s. Test-drive the project structure before you buy.'],presets:['free presets','We’ll email you a few presets from %s. Load them in Serum and hear them in your own track.'],music:['free preview','We’ll email you a preview of %s to try in your video before you license it.']};
var dlg,cur={},opened=0;
function build(){
  dlg=document.createElement('div');dlg.className='ss-fd';dlg.hidden=true;
  dlg.innerHTML='<div class="ss-fd-b" role="dialog" aria-modal="true" aria-labelledby="ss-fd-t"><button type="button" class="ss-fd-x" aria-label="Close">×</button><span class="ss-fd-k">FREE TEST-DRIVE</span><b id="ss-fd-t"></b><p></p><label for="ss-fd-e">Email</label><input id="ss-fd-e" type="email" autocomplete="email" inputmode="email" placeholder="you@example.com" required><input class="ss-fd-hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true"><button type="button" class="ss-fd-s">Send me the demo</button><span class="ss-fd-m" aria-live="polite"></span></div>';
  document.body.appendChild(dlg);
  dlg.addEventListener('click',function(e){if(e.target===dlg||e.target.closest('.ss-fd-x'))close()});
  document.addEventListener('keydown',function(e){if(e.key==='Escape'&&!dlg.hidden)close()});
  dlg.querySelector('.ss-fd-s').addEventListener('click',send);
  dlg.querySelector('#ss-fd-e').addEventListener('keydown',function(e){if(e.key==='Enter')send()});
}
function open(name,type,url){
  if(!dlg)build();cur={name:name,type:TXT[type]?type:'music',url:url||location.href};opened=Date.now();
  var t=TXT[cur.type];dlg.querySelector('b').textContent=name+' — '+t[0];dlg.querySelector('p').textContent=t[1].replace('%s',name);
  var m=dlg.querySelector('.ss-fd-m');m.textContent='';m.className='ss-fd-m';var s=dlg.querySelector('.ss-fd-s');s.disabled=false;s.hidden=false;
  dlg.hidden=false;document.documentElement.style.overflow='hidden';setTimeout(function(){dlg.querySelector('#ss-fd-e').focus()},30);
}
function close(){dlg.hidden=true;document.documentElement.style.overflow=''}
function post(email,name,type,url,el,hp){
  var f=new FormData();f.append('action','ss_bot_lead');f.append('email',email);f.append('msg','Free demo request ('+type+'): '+name+' — '+url);f.append('page',url);f.append('el',String(el));f.append('website',hp||'');f.append('lang',(document.documentElement.lang||'en').slice(0,2));
  return fetch(AJAX,{method:'POST',credentials:'same-origin',body:f}).then(function(r){return r.json().catch(function(){return {success:r.ok}})});
}
function send(){
  var e=dlg.querySelector('#ss-fd-e'),m=dlg.querySelector('.ss-fd-m'),s=dlg.querySelector('.ss-fd-s');
  if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e.value.trim())){m.textContent='Please enter a valid email.';m.className='ss-fd-m bad';e.focus();return}
  s.disabled=true;m.textContent='Sending…';m.className='ss-fd-m';
  post(e.value.trim(),cur.name,cur.type,cur.url,Math.round((Date.now()-opened)/1000),dlg.querySelector('.ss-fd-hp').value).then(function(j){
    if(j&&j.success){m.textContent='✓ Thanks! We’ll email you the '+TXT[cur.type][0]+' within 24 hours.';m.className='ss-fd-m ok';s.hidden=true}
    else{s.disabled=false;m.textContent=(j&&j.data&&j.data.m==='rate')?'Too many requests — please try again later.':'Something went wrong — please try again.';m.className='ss-fd-m bad'}
  }).catch(function(){s.disabled=false;m.textContent='Something went wrong — please try again.';m.className='ss-fd-m bad'});
}
function link(tag){var a=document.createElement(tag||'button');if(!tag||tag==='button')a.type='button';else{a.setAttribute('role','button');a.tabIndex=0}a.className='ss-fd-l';a.textContent='✦ Try it free for your email';return a}
function typeOf(cls){return /templates/.test(cls)?'templates':(/presets/.test(cls)?'presets':'music')}
function run(){
  document.querySelectorAll('.w-grid.layout_195 .w-grid-item:not([data-fd])').forEach(function(it){
    it.setAttribute('data-fd','1');var h=it.querySelector('.w-grid-item-h');if(!h)return;
    var t=(it.querySelector('.usg_post_title_1, .post_title')||{}).textContent||'',a=it.querySelector('.usg_post_title_1 a, a.w-grid-item-anchor');
    var l=link();l.dataset.n=t.trim();l.dataset.t=typeOf(it.className);l.dataset.u=a?a.href:'';h.appendChild(l);
  });
  document.querySelectorAll('.ssh-card:not([data-fd])').forEach(function(c){
    var box=c.querySelector('.ssh-buyb');if(!box)return;c.setAttribute('data-fd','1');
    var l=link('span');l.dataset.n=(c.getAttribute('title')||'').trim();var tg=c.querySelector('.ssh-tag');l.dataset.t=typeOf(tg?tg.className:'');l.dataset.u=c.href;box.appendChild(l);
  });
  if(SINGLE){var f=document.querySelector('form.cart');var row=f&&(f.closest('.ss-buyrow')||f);if(row&&!document.querySelector('.single-product .ss-fd-l')){var l=link();l.dataset.n=((document.querySelector('.product_title,h1')||{}).textContent||'').trim();l.dataset.t=SINGLE;l.dataset.u=location.href.split('#')[0];row.parentNode.insertBefore(l,row.nextSibling)}}
}
function act(e){
  var l=e.target.closest&&e.target.closest('.ss-fd-l');if(!l)return;
  if(e.type==='keydown'&&e.key!=='Enter'&&e.key!==' ')return;
  e.preventDefault();e.stopPropagation();open(l.dataset.n||'this product',l.dataset.t,l.dataset.u);
}
window.addEventListener('click',act,true);window.addEventListener('keydown',act,true);
/* home page cards: "Download demo" on the back side */
window.addEventListener('click',function(e){
  var b=e.target.closest&&e.target.closest('#ss-app button');if(!b||!/^\s*Download demo\s*$/.test(b.textContent))return;
  var box=b.parentNode,inp=box.querySelector('input[type=email]'),hd=box.querySelector('b');if(!inp)return;
  var name=hd?hd.textContent.split(' — ')[0].trim():'product',typ=/presets/i.test(hd?hd.textContent:'')?'presets':(/lite demo/i.test(hd?hd.textContent:'')?'templates':'music');
  var msg=box.querySelector('.ss-fd-m');if(!msg){msg=document.createElement('span');msg.className='ss-fd-m';msg.setAttribute('aria-live','polite');b.parentNode.insertBefore(msg,b.nextSibling)}
  if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test((inp.value||'').trim())){msg.textContent='Please enter a valid email.';msg.className='ss-fd-m bad';inp.focus();return}
  b.disabled=true;msg.textContent='Sending…';msg.className='ss-fd-m';
  post(inp.value.trim(),name,typ,location.href.split('#')[0],10,'').then(function(j){if(j&&j.success){msg.textContent='✓ Thanks! Check your inbox within 24 hours.';msg.className='ss-fd-m ok';b.hidden=true}else{b.disabled=false;msg.textContent='Something went wrong — please try again.';msg.className='ss-fd-m bad'}}).catch(function(){b.disabled=false;msg.textContent='Something went wrong — please try again.';msg.className='ss-fd-m bad'});
},true);
run();var q=0;new MutationObserver(function(){if(q)return;q=requestAnimationFrame(function(){q=0;run()})}).observe(document.body,{childList:true,subtree:true});
})();</script>
	<?php
}, 50 );
