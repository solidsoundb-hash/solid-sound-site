<?php
/**
 * Add Product — price guide for creators (owner 20.11.2026: «ориентир по ценникам, за сколько трек реально можно продать —
 * алгоритм в кабинете при добавлении товара»).
 * Under the price field: a suggested price, a range, why (each factor with its effect), what the creator earns per sale, «Use €29».
 * The math runs in the browser from what is already in the form (format, plugins, length, genre) + three numbers from the server:
 * the creator's published products, their sales, and the median price of similar products on the site.
 * Bases are market prices (WMDM, Production Music Live, preset/sample stores, 2026). Customer-facing texts in English.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() || ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return;
	global $wp; if ( ! array_key_exists( 'wcfm-ss-add-product', (array) $wp->query_vars ) ) return;
	$uid = get_current_user_id();
	$ids = get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'author' => $uid, 'numberposts' => 200, 'fields' => 'ids' ) );
	$sales = 0; foreach ( $ids as $id ) $sales += (int) get_post_meta( $id, 'total_sales', true );
	$cut = 0.70;
	$me = array( 'products' => count( $ids ), 'sales' => $sales, 'cut' => $cut,
		'cur' => html_entity_decode( function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '€' ) );
	?>
<style id="ss-pg-css">#ss-pg{margin:10px 0 0;padding:14px 16px;border-radius:12px;border:1px solid rgba(52,211,153,.3);background:rgba(52,211,153,.06);color:#E2E8F0;font:400 14px/1.5 Roboto,sans-serif}
#ss-pg .h{display:flex;flex-wrap:wrap;align-items:baseline;gap:6px 12px}#ss-pg .h b{color:#fff;font:800 22px "Exo 2",sans-serif}#ss-pg .h span{color:#94A3B8}
#ss-pg ul{margin:8px 0 0;padding:0;list-style:none}#ss-pg li{display:flex;justify-content:space-between;gap:10px;padding:3px 0;border-bottom:1px dashed rgba(255,255,255,.06);color:#CBD5E1;font-size:13.5px}#ss-pg li i{font-style:normal;font-weight:700;white-space:nowrap}#ss-pg li i.p{color:#34D399}#ss-pg li i.m{color:#FBBF24}
#ss-pg .e{margin-top:8px;color:#94A3B8;font-size:13px}#ss-pg button{margin:10px 8px 0 0;padding:7px 14px;border-radius:9px;border:0;background:#34D399;color:#062014;font:800 14px "Exo 2",sans-serif;cursor:pointer}#ss-pg button.o{background:transparent;border:1px solid rgba(255,255,255,.2);color:#E2E8F0}</style>
<script id="ss-pg-js">(function(){var ME=<?php echo wp_json_encode( $me ); ?>;
/* market bases [low, mid, high] in the shop currency */
var BASE={'templates':[19,29,45],'reconstructed templates':[12,19,29],'inspired':[19,29,39],'presets':[7,12,25],'sample packs':[9,19,35],'music':[15,29,49]};
var HOT=/techno|house|afro|drum|bass|trance|hard/i;
var form,box,price,similar=null;
function q(s){return form.querySelector(s)}
function texts(cls){var w=q('.ss-ap-'+cls);if(!w)return [];var a=[];w.querySelectorAll('input:checked').forEach(function(i){var l=i.closest('label'),sp=i.nextElementSibling;a.push(((l?l.textContent:(sp?sp.textContent:i.value))||'').trim())});w.querySelectorAll('select').forEach(function(s){[].forEach.call(s.selectedOptions||[],function(o){if(o.value)a.push(o.text.trim())})});return a.filter(Boolean)}
function lenSec(){if(window.__ssAlsLen)return +window.__ssAlsLen;var t=texts('length')[0]||'',m=t.match(/(\d+):(\d{2})/);return m?(+m[1])*60+(+m[2]):0}
function nice(v){v=Math.max(5,v);var c=[5,7,9,12,15,19,24,29,34,39,45,49,59,69,79,89,99];for(var i=0;i<c.length;i++)if(v<=c[i]+2)return c[i];return Math.round(v/10)*10-1}
function calc(){var f=(texts('format')[0]||'').toLowerCase();if(/inspired/.test(f))f='inspired';var b=BASE[f];if(!b)return null;
 var mid=b[1],why=[['Market price for '+(f==='reconstructed templates'?'remake templates (learning only)':f)+': '+ME.cur+b[1],null]];
 function adj(p,t){mid*=1+p;why.push([t,p])}
 var ext=texts('ext').join(' ').toLowerCase();if(/no external/.test(ext))adj(.10,'Stock plugins only — more buyers can open it');else if(ext)adj(-.05,'Needs third-party plugins');
 if(f==='templates'||f==='reconstructed templates'||f==='inspired'||f==='music'){var L=lenSec();if(L>=240)adj(.10,'Full-length arrangement');else if(L&&L<120)adj(-.15,'Short track (under 2 min)')}
 if(HOT.test(texts('genre').join(' ')))adj(.05,'Genre in high demand');
 if(!ME.products)adj(-.15,'Your first product — a launch price brings the first sales and reviews');else if(ME.sales>=10)adj(.10,'You already have '+ME.sales+' sales');
 if(similar&&similar.n>=3){var s=+similar.mid;why.push(['Similar products on Solid Sound sell around '+ME.cur+Math.round(s),null]);mid=(mid+s)/2}
 var p=nice(mid);return {p:p,lo:nice(Math.max(b[0],mid*.8)),hi:nice(Math.min(b[2]*1.2,mid*1.25)),why:why}}
function esc(s){return String(s).replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]})}
function draw(){var r=calc();if(!r){box.style.display='none';return}box.style.display='';var C=ME.cur;
 box.innerHTML='<div class="h"><span>Suggested price</span><b>'+C+r.p+'</b><span>sells best between '+C+r.lo+' and '+C+r.hi+'</span></div><ul>'+r.why.map(function(w){return '<li>'+esc(w[0])+(w[1]==null?'':'<i class="'+(w[1]>0?'p':'m')+'">'+(w[1]>0?'+':'−')+Math.round(Math.abs(w[1])*100)+' %</i>')+'</li>'}).join('')+'</ul>'
  +'<div class="e">You earn '+C+(r.p*ME.cut).toFixed(2)+' per sale ('+Math.round(ME.cut*100)+' %). Lower prices sell more copies — you can change it any time.</div>'
  +'<button type="button" data-v="'+r.p+'">Use '+C+r.p+'</button><button type="button" class="o" data-v="'+r.lo+'">Launch at '+C+r.lo+'</button>'}
function later(){requestAnimationFrame(draw)}
function init(){form=document.getElementById('solid-sound-upload-form');if(!form)return false;price=form.querySelector('[name=product_price]');if(!price||document.getElementById('ss-pg'))return true;
 box=document.createElement('div');box.id='ss-pg';box.setAttribute('aria-live','polite');(price.closest('p')||price).parentNode.insertBefore(box,(price.closest('p')||price).nextSibling);
 box.addEventListener('click',function(e){var b=e.target.closest&&e.target.closest('button[data-v]');if(!b)return;e.preventDefault();price.value=(+b.getAttribute('data-v')).toFixed(2);price.dispatchEvent(new Event('input',{bubbles:true}));price.dispatchEvent(new Event('change',{bubbles:true}))});
 form.addEventListener('change',later);form.addEventListener('input',function(e){if(e.target!==price)later()});
 /* the «Similar products» hint (ai-creator.php) already asks the server for the median of this format — read its chips */
 new MutationObserver(function(ms){if(ms.every(function(m){return box.contains(m.target)}))return;if(!ms.some(function(m){return m.target.closest&&m.target.closest('.ss-aic-pr')||(m.target.classList&&m.target.classList.contains('ss-aic-pr'))}))return;var ch=[].map.call(form.querySelectorAll('.ss-aic-pr button'),function(b){return parseFloat(b.textContent.replace(/[^\d.]/g,''))}).filter(function(v){return v>0});similar=ch.length?{n:3,mid:ch[Math.floor(ch.length/2)]}:null;later()}).observe(form,{childList:true,subtree:true,characterData:false});
 draw();return true}
if(!init()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(init())mo.disconnect()})});mo.observe(document.body,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},20000)}
})();</script>
	<?php
}, 62 );
