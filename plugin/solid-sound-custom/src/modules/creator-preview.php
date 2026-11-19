<?php
/**
 * (16.11.2026 owner) Add Product:
 *  - "👁 Preview" button: shows how the product will look on the site — as a catalog card and as the product page —
 *    from what is in the form right now (cover, name, price, genre, BPM, key, description, demo). Nothing is saved.
 *  - Readable form: bigger, higher-contrast text in every field, label and hint ("чтобы авторы не прищуривались").
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() || ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return;
	global $wp; if ( ! array_key_exists( 'wcfm-ss-add-product', (array) $wp->query_vars ) ) return;
	$u = wp_get_current_user();
	$artist = function_exists( 'ss_cp_prof' ) ? ( ss_cp_prof( $u->ID )['store_name'] ?? '' ) : '';
	if ( ! $artist ) $artist = $u->display_name;
	$cur = html_entity_decode( function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '$' );
	?>
<style id="ss-pv-css">
/* readable form (owner 16.11): 16px fields, brighter labels and hints */
body #solid-sound-upload-form{font-size:16px!important}
body #solid-sound-upload-form input[type=text],body #solid-sound-upload-form input[type=number],body #solid-sound-upload-form input[type=url],body #solid-sound-upload-form textarea,body #solid-sound-upload-form select,body #solid-sound-upload-form .select2-selection__rendered,body #solid-sound-upload-form .select2-search__field{font-size:16px!important;color:#f8fafc!important;letter-spacing:.005em}
body #solid-sound-upload-form textarea{line-height:1.6!important;padding:12px 14px!important}
body #solid-sound-upload-form input::placeholder,body #solid-sound-upload-form textarea::placeholder{color:#8a99ad!important}
body #solid-sound-upload-form .acf-label label,body #solid-sound-upload-form .ss-wm-l{font-size:16px!important;font-weight:700!important;color:#fff!important}
body #solid-sound-upload-form .acf-checkbox-list label,body #solid-sound-upload-form .acf-radio-list label{font-size:15px!important;color:#f1f5f9!important}
body #solid-sound-upload-form .ss-wm-max,body #solid-sound-upload-form .acf-label .description,body #solid-sound-upload-form .acf-input>.description,body #solid-sound-upload-form p.description,body #solid-sound-upload-form .ss-wm-note,body #solid-sound-upload-form .ss-aic-n,body #ss-zf .n,body #ss-zf .l,body .ss-aic-chips .l{font-size:14px!important;color:#c2cddb!important}
body #solid-sound-upload-form .select2-selection__rendered,body #solid-sound-upload-form .select2-selection__placeholder{color:#c2cddb!important}
body .ss-aic-card .t{font-size:14.5px!important;line-height:1.55!important;color:#f1f5f9!important}
body .ss-aic-c,body #ss-zf .c{font-size:14px!important}
/* "+ Add your own" visible and labelled (owner 16.11: so the creator doesn't guess whether adding is allowed) */
body #solid-sound-upload-form .acf-field:not(.ss-wm-row .acf-field)>.acf-input{display:flex!important;flex-direction:column!important}
body #solid-sound-upload-form .acf-field:not(.ss-wm-row .acf-field)>.acf-input>.acf-actions{order:5}
body #solid-sound-upload-form .acf-actions{position:static!important;display:block!important;opacity:1!important;visibility:visible!important;margin:10px 0 0!important;padding:0!important;text-align:left!important;transform:none!important}
body #solid-sound-upload-form .ss-wm-row .acf-actions{margin:0!important;flex:none!important}
body #solid-sound-upload-form a.acf-icon[data-name=add]{position:static!important;display:inline-flex!important;align-items:center!important;gap:6px;width:auto!important;height:38px!important;padding:0 16px!important;margin:0!important;border:1.5px dashed #f59e0b!important;border-radius:999px!important;background:rgba(245,158,11,.1)!important;color:#fbbf24!important;font:700 14.5px/1 Roboto,sans-serif!important;text-decoration:none!important;box-shadow:none!important;transform:none!important;white-space:nowrap}
body #solid-sound-upload-form a.acf-icon[data-name=add]:hover{background:rgba(245,158,11,.2)!important;color:#fff!important}
body #solid-sound-upload-form a.acf-icon[data-name=add]::before{content:"+"!important;position:static!important;display:inline!important;width:auto!important;height:auto!important;margin:0!important;background:none!important;-webkit-mask:none!important;mask:none!important;font:800 20px/1 Roboto,sans-serif!important;color:inherit!important;transform:none!important}
body #solid-sound-upload-form a.acf-icon[data-name=add]::after{content:"Add your own";position:static!important;display:inline!important;background:none!important;-webkit-mask:none!important;mask:none!important;width:auto!important;height:auto!important;color:inherit!important;transform:none!important}
body #solid-sound-upload-form .ss-wm-row a.acf-icon[data-name=add]::after{content:"Add"}
/* preview */
.ss-pv-b{display:inline-flex;align-items:center;gap:6px;height:40px;padding:0 16px;margin-right:8px;border:1px solid rgba(255,255,255,.25);border-radius:10px;background:transparent;color:#fff;font:700 15px Roboto,sans-serif;cursor:pointer}.ss-pv-b:hover{border-color:#f59e0b}
#ss-pv{position:fixed;inset:0;z-index:100000;display:none;background:rgba(2,6,15,.97);overflow:auto;padding:24px 16px}#ss-pv.on{display:block}
#ss-pv .in{max-width:1100px;margin:0 auto;color:#e2e8f0;font-family:"Exo 2",Roboto,sans-serif}
#ss-pv .hd{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:14px}#ss-pv .hd b{font-size:18px;color:#fff}#ss-pv .hd small{display:block;color:#94a3b8;font-size:13px;font-weight:400}
#ss-pv .x{width:40px;height:40px;border-radius:50%;border:0;background:#fff;color:#111;font:700 22px/40px sans-serif;cursor:pointer;flex:none}
#ss-pv .tabs{display:flex;gap:8px;margin:0 0 16px}#ss-pv .tabs button{height:36px;padding:0 14px;border-radius:999px;border:1px solid rgba(255,255,255,.2);background:transparent;color:#e2e8f0;font-weight:700;cursor:pointer}#ss-pv .tabs button.on{background:#f59e0b;border-color:#f59e0b;color:#111}
#ss-pv .pane{display:none}#ss-pv .pane.on{display:block}
#ss-pv .cards{display:grid;grid-template-columns:repeat(3,minmax(0,260px));gap:18px}@media(max-width:700px){#ss-pv .cards{grid-template-columns:1fr 1fr}}
#ss-pv .card{background:#0f1626;border:1px solid rgba(255,255,255,.08);border-radius:12px;overflow:hidden}#ss-pv .card.dim{opacity:.35}
#ss-pv .cv{aspect-ratio:1;background:linear-gradient(135deg,#1e293b,#0b1220);display:flex;align-items:center;justify-content:center;color:#64748b;font-size:13px;overflow:hidden}#ss-pv .cv img{width:100%;height:100%;object-fit:cover;display:block}
#ss-pv .card .b{padding:10px 12px 12px}#ss-pv .card h4{margin:0 0 4px;font-size:15px;color:#fff;line-height:1.3}#ss-pv .meta{color:#94a3b8;font-size:12.5px}
#ss-pv .row{display:flex;justify-content:space-between;align-items:center;margin-top:10px}#ss-pv .pr{color:#f59e0b;font-weight:800;font-size:16px}
#ss-pv .buy{background:#f59e0b;color:#111;border:0;border-radius:8px;height:34px;padding:0 12px;font-weight:800}
#ss-pv .page{display:grid;grid-template-columns:minmax(0,440px) minmax(0,1fr);gap:28px;background:#0b0f17;border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:22px}@media(max-width:800px){#ss-pv .page{grid-template-columns:1fr}}
#ss-pv .page .cv{border-radius:12px}#ss-pv .page h1{margin:0 0 6px;font-size:28px;line-height:1.2;color:#fff}#ss-pv .by{color:#94a3b8;margin-bottom:12px}
#ss-pv .tags{display:flex;flex-wrap:wrap;gap:6px;margin:10px 0 14px}#ss-pv .tags span{border:1px solid rgba(255,255,255,.15);border-radius:999px;padding:4px 10px;font-size:13px;color:#e2e8f0}
#ss-pv .page .pr{font-size:26px}#ss-pv .page .buy{height:46px;padding:0 22px;font-size:16px;margin-left:12px}
#ss-pv .desc{margin-top:18px;font-family:Roboto,sans-serif;font-size:15.5px;line-height:1.65;color:#e2e8f0}#ss-pv .desc ul{margin:6px 0 0 20px}#ss-pv .desc p{margin:0 0 10px}
#ss-pv audio{width:100%;margin-top:14px}#ss-pv .miss{margin-top:16px;padding:10px 12px;border-radius:10px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.35);color:#fde68a;font-size:14px}
/* 19.11 owner («что за колхоз, сделай круто»): the "Add your own" window in the site style */
body .acf-popup{position:fixed!important;inset:0!important;z-index:100001!important;display:flex!important;align-items:center!important;justify-content:center!important;padding:16px}
body .acf-popup>.bg{position:absolute!important;inset:0!important;z-index:0!important;background:rgba(2,6,15,.78)!important;-webkit-backdrop-filter:blur(4px);backdrop-filter:blur(4px)}
body .acf-popup-box{position:relative!important;z-index:1!important;box-sizing:border-box!important;top:auto!important;left:auto!important;right:auto!important;margin:0!important;transform:none!important;width:min(440px,100%)!important;min-width:0!important;max-height:none!important;background:#131B2E!important;border:1px solid rgba(245,158,11,.35)!important;border-radius:18px!important;box-shadow:0 30px 80px rgba(0,0,0,.65)!important;color:#E2E8F0!important;overflow:visible!important;animation:ssAp .18s ease-out}
@keyframes ssAp{from{opacity:0;transform:translateY(8px) scale(.98)}to{opacity:1;transform:none}}
body .acf-popup-box .title{position:relative!important;display:flex!important;align-items:center!important;min-height:0!important;margin:0!important;padding:22px 64px 4px 24px!important;background:transparent!important;border:0!important}
body .acf-popup-box .title h3{margin:0!important;padding:0!important;color:#fff!important;font:800 21px/1.25 "Exo 2",sans-serif!important}
body .acf-popup-box .title h3:empty::before{content:"Add your own"}
body .acf-popup-box .title .acf-icon{position:absolute!important;top:16px!important;right:16px!important;left:auto!important;width:36px!important;height:36px!important;margin:0!important;border-radius:50%!important;border:1px solid rgba(255,255,255,.14)!important;background:#1A2540!important;color:#CBD5E1!important}
body .acf-popup-box .title .acf-icon:hover{border-color:#F59E0B!important;color:#F59E0B!important}
body .acf-popup-box .inner{padding:6px 24px 24px!important;background:transparent!important}
body .acf-popup-box .ss-ap-sub{margin:0 0 14px;color:#94A3B8;font:400 14px/1.5 Roboto,sans-serif}
body .acf-popup-box .acf-field{padding:0!important;margin:0 0 14px!important;border:0!important;background:transparent!important}
body .acf-popup-box .acf-field[data-name=term_parent],body .acf-popup-box .acf-field[data-name=term_parent]+.acf-field[data-name=term_parent]{display:none!important}
body .acf-popup-box .acf-label{margin:0 0 6px!important}
body .acf-popup-box .acf-label label{color:#CBD5E1!important;font:600 13px "Exo 2",sans-serif!important;letter-spacing:.04em;text-transform:uppercase}
body .acf-popup-box input[type=text]{box-sizing:border-box!important;width:100%!important;height:50px!important;padding:0 16px!important;border-radius:12px!important;border:1px solid rgba(255,255,255,.14)!important;background:#090E17!important;color:#fff!important;font:500 16px Roboto,sans-serif!important;box-shadow:none!important;outline:0!important;transition:border-color .15s,box-shadow .15s}
body .acf-popup-box input[type=text]:focus{border-color:#F59E0B!important;box-shadow:0 0 0 3px rgba(245,158,11,.18)!important}
body .acf-popup-box input[type=text]::placeholder{color:#64748B!important}
body .acf-popup-box .acf-submit{margin:4px 0 0!important;padding:0!important;display:block!important}
body .acf-popup-box .acf-submit .acf-submit-button,body .acf-popup-box .acf-submit button{width:100%!important;height:50px!important;border:0!important;border-radius:12px!important;background:#F59E0B!important;color:#111!important;font:800 16px "Exo 2",sans-serif!important;text-shadow:none!important;box-shadow:0 8px 24px rgba(245,158,11,.25)!important;cursor:pointer}
body .acf-popup-box .acf-submit .acf-submit-button:hover,body .acf-popup-box .acf-submit button:hover{background:#FBBF24!important}
body .acf-popup-box .acf-submit .acf-spinner{display:none!important}
body .acf-popup-box .loading{position:absolute!important;inset:0!important;border-radius:18px!important;background:rgba(19,27,46,.8)!important}
body .acf-popup-box .acf-error-message{margin:0 0 12px!important;border-radius:10px!important;background:rgba(239,68,68,.12)!important;border:1px solid rgba(239,68,68,.4)!important;color:#FCA5A5!important}
body .acf-popup-box .acf-error-message p{color:#FCA5A5!important;margin:8px 12px!important}
</style>
<script>/* 19.11 owner («расположи версии в алфавитном порядке»): DAW versions A→Z, numbers in natural order (Ableton Live 9, 10, 11, 12) */
(function(){function sort(){var w=document.querySelector('#solid-sound-upload-form .ss-ap-version ul.acf-radio-list,#solid-sound-upload-form .ss-ap-version ul.acf-checkbox-list');if(!w||w.dataset.sorted)return!!w;var li=[].slice.call(w.children);li.sort(function(a,b){return a.textContent.trim().localeCompare(b.textContent.trim(),'en',{numeric:true,sensitivity:'base'})});li.forEach(function(x){w.appendChild(x)});w.dataset.sorted='1';return true}
if(!sort()){var mo=new MutationObserver(function(){if(sort())mo.disconnect()});mo.observe(document.documentElement,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},15000)}})();</script>
<script>/* 19.11: name the "Add your own" window after its field, placeholder, one-line hint, focus */
(function(){var last='';document.addEventListener('click',function(e){var a=e.target.closest?e.target.closest('a.acf-icon[data-name=add]'):null;if(!a)return;var f=a.closest('.acf-field'),l=f?f.querySelector('.acf-label label'):null;last=l?(l.textContent||'').replace(/\*|\(.*?\)/g,'').trim():'';},true);
new MutationObserver(function(){var b=document.querySelector('.acf-popup-box:not([data-ss])');if(!b)return;b.setAttribute('data-ss','1');var n=last.replace(/^select\s+/i,'').replace(/\(s\)$/i,'').trim()||'item';var low=n.toLowerCase();
var h=b.querySelector('.title h3');if(h)h.textContent='Add a '+low;var inp=b.querySelector('.acf-field[data-name=term_name] input');var lb=b.querySelector('.acf-field[data-name=term_name] .acf-label label');if(lb)lb.textContent=n+' name';
if(inp){inp.placeholder=/genre/.test(low)?'e.g. Afro House':'Type a name';setTimeout(function(){inp.focus()},60)}
var inn=b.querySelector('.inner');if(inn&&!inn.querySelector('.ss-ap-sub')){var p=document.createElement('p');p.className='ss-ap-sub';p.textContent='Not in the list? Add it here. It shows up in your list right away, and we check it together with your product.';inn.insertBefore(p,inn.firstChild)}
var sb=b.querySelector('.acf-submit-button,.acf-submit button');if(sb)sb.textContent='Add '+low;}).observe(document.body,{childList:true,subtree:true});})();</script>
<script>(function(){
var ARTIST=<?php echo wp_json_encode( $artist ); ?>,CUR=<?php echo wp_json_encode( $cur ); ?>;
function esc(s){return String(s==null?'':s).replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]})}
function picked(f,cls){var w=f.querySelector('.ss-ap-'+cls);if(!w||w.classList.contains('ss-ap-off'))return [];var a=[];w.querySelectorAll('input:checked').forEach(function(i){var l=i.closest('label');a.push((l?l.textContent:i.value).trim())});w.querySelectorAll('select').forEach(function(s){[].forEach.call(s.selectedOptions||[],function(o){if(o.value)a.push(o.text.trim())})});w.querySelectorAll('input[type=text],input[type=number]').forEach(function(i){if(i.value)a.push(i.value.trim())});return a}
function descHtml(t){t=String(t||'').trim();if(!t)return '';var out='',list=false;t.split(/\n/).forEach(function(l){l=l.trim();if(/^[-•*]\s+/.test(l)){if(!list){out+='<ul>';list=true}out+='<li>'+esc(l.replace(/^[-•*]\s+/,''))+'</li>';return}if(list){out+='</ul>';list=false}if(l)out+='<p>'+esc(l)+'</p>'});return out+(list?'</ul>':'')}
var M,url=null;
function data(f){var q=function(s){return f.querySelector(s)};var img=q('[name=product_image]');if(url){URL.revokeObjectURL(url);url=null}if(img&&img.files&&img.files[0])url=URL.createObjectURL(img.files[0]);
 var mp3='',w=q('.ss-ap-mp3 textarea');if(w){var v=(window.tinymce&&tinymce.get(w.id))?tinymce.get(w.id).getContent():w.value;var m=v.match(/mp3="([^"]+)"/);if(m)mp3=m[1]}
 var price=parseFloat((q('[name=product_price]')||{}).value||'');
 return {name:((q('[name=product_name]')||{}).value||'').trim(),desc:(q('[name=product_description]')||{}).value||'',price:isNaN(price)?'':CUR+(price%1?price.toFixed(2):price.toFixed(0)),cover:url,mp3:mp3,
  format:picked(f,'format')[0]||'',genre:picked(f,'genre'),bpm:picked(f,'bpm')[0]||'',key:picked(f,'key')[0]||'',len:picked(f,'length')[0]||'',daw:picked(f,'version')[0]||picked(f,'daw')[0]||'',plugins:picked(f,'ext')}}
function cover(d){return '<div class="cv">'+(d.cover?'<img src="'+d.cover+'" alt="">':'No cover yet')+'</div>'}
function render(f){var d=data(f),meta=[d.genre[0],d.bpm&&d.bpm+' BPM',d.key].filter(Boolean).join(' · ');
 var card='<div class="card">'+cover(d)+'<div class="b"><h4>'+esc(d.name||'Product name')+'</h4><div class="meta">'+esc(meta||d.format)+'</div><div class="row"><span class="pr">'+esc(d.price||CUR+'—')+'</span><button class="buy" type="button">Add to cart</button></div></div></div>';
 var other='<div class="card dim"><div class="cv"></div><div class="b"><h4>Another product</h4><div class="meta">Techno · 128 BPM</div><div class="row"><span class="pr">'+CUR+'19</span><button class="buy" type="button">Add to cart</button></div></div></div>';
 var tags=[d.format].concat(d.genre,[d.bpm&&d.bpm+' BPM',d.key,d.len,d.daw],d.plugins).filter(Boolean).map(function(t){return '<span>'+esc(t)+'</span>'}).join('');
 var miss=[!d.cover&&'a cover',!d.name&&'a name',!d.price&&'a price',d.desc.trim().length<20&&'a description',!d.mp3&&'an MP3 demo'].filter(Boolean);
 M.querySelector('.p1').innerHTML='<div class="cards">'+other+card+other+'</div><p class="meta" style="margin-top:12px">This is how your product appears in the catalog and on the home page.</p>';
 M.querySelector('.p2').innerHTML='<div class="page">'+cover(d)+'<div><h1>'+esc(d.name||'Product name')+'</h1><div class="by">by '+esc(ARTIST)+'</div><div class="tags">'+tags+'</div><div><span class="pr">'+esc(d.price||CUR+'—')+'</span><button class="buy" type="button">Buy · '+esc(d.price||'')+'</button></div>'+(d.mp3?'<audio controls preload="none" src="'+esc(d.mp3)+'"></audio>':'')+'<div class="desc">'+(descHtml(d.desc)||'<p style="color:#64748b">Your description will appear here.</p>')+'</div>'+(miss.length?'<div class="miss">Still missing: '+miss.join(', ')+'.</div>':'')+'</div></div>'}
function open(f){if(!M){M=document.createElement('div');M.id='ss-pv';M.setAttribute('role','dialog');M.setAttribute('aria-modal','true');M.innerHTML='<div class="in"><div class="hd"><b>Preview<small>How it will look on Solid Sound. Nothing is saved — close it and keep editing.</small></b><button type="button" class="x" aria-label="Close preview">×</button></div><div class="tabs"><button type="button" class="on" data-p="p2">Product page</button><button type="button" data-p="p1">Catalog card</button></div><div class="pane p2 on"></div><div class="pane p1"></div></div>';document.body.appendChild(M);
  M.addEventListener('click',function(e){if(e.target===M||e.target.classList.contains('x'))close();var t=e.target.closest('.tabs button');if(t){[].forEach.call(M.querySelectorAll('.tabs button'),function(b){b.classList.toggle('on',b===t)});[].forEach.call(M.querySelectorAll('.pane'),function(p){p.classList.toggle('on',p.classList.contains(t.dataset.p))})}});
  document.addEventListener('keydown',function(e){if(e.key==='Escape'&&M.classList.contains('on'))close()})}
 render(f);M.classList.add('on');document.documentElement.style.overflow='hidden';M.querySelector('.x').focus()}
function close(){M.classList.remove('on');document.documentElement.style.overflow='';var a=M.querySelector('audio');if(a)a.pause()}
function init(){var f=document.getElementById('solid-sound-upload-form'),top=f&&f.querySelector('.ss-wm-top .pri');if(!top||f.dataset.pv)return !!top;f.dataset.pv=1;
 /* 3D format: kept for the future (owner 16.11), hidden from creators until it gets its own fields */
 f.querySelectorAll('.ss-ap-format input[type=radio]').forEach(function(r){var l=r.closest('li')||r.closest('label');if(l&&/\b3\s?d\b/i.test(l.textContent)&&!r.checked)l.style.display='none'});
 f.querySelectorAll('.ss-ap-format option').forEach(function(o){if(/\b3\s?d\b/i.test(o.textContent)&&!o.selected)o.hidden=o.disabled=true});
 [top,f.querySelector('.ss-wm-submit button')].forEach(function(t){if(!t)return;var b=document.createElement('button');b.type='button';b.className='ss-pv-b';b.textContent='👁 Preview';b.addEventListener('click',function(){open(f)});t.parentNode.insertBefore(b,t)});return true}
if(!init()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(init())mo.disconnect()})});mo.observe(document.documentElement,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},12000)}
})();</script>
	<?php
}, 62 );
