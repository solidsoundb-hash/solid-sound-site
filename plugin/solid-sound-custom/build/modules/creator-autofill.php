<?php
/**
 * (16.11.2026 owner: "лучше чем у Вимейка", "чтобы у них была возможность принимать выбор")
 * Add Product: when the creator drops the project ZIP, the browser reads it locally (no upload, no library:
 * zip central directory + built-in DecompressionStream) and opens the Ableton .als inside. Found facts are shown as
 * chips — name ideas, DAW version, BPM, project scale, plugins used, pack contents. NOTHING is applied until the
 * creator taps a chip (or "Use all"); every field stays editable. Runs only on the Add Product page.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() || ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return;
	global $wp; if ( ! array_key_exists( 'wcfm-ss-add-product', (array) $wp->query_vars ) ) return;
	?>
<style id="ss-zf-css">
#ss-zf{display:none;margin:12px 0 4px;padding:14px 16px;border:1px solid rgba(245,158,11,.35);border-radius:12px;background:#0b1220;color:#e2e8f0;font:14px/1.4 Roboto,sans-serif}
#ss-zf.on{display:block}#ss-zf .h{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;margin-bottom:6px}
#ss-zf .h b{font-size:15px;color:#fff}#ss-zf .n{color:#94a3b8;font-size:12.5px;margin:0 0 8px}
#ss-zf .r{display:flex;flex-wrap:wrap;align-items:center;gap:6px;margin:7px 0}#ss-zf .l{min-width:92px;color:#94a3b8;font-size:12.5px}
#ss-zf .c{border:1px solid rgba(148,163,184,.4);background:transparent;color:#e2e8f0;border-radius:999px;padding:5px 12px;font-size:13px;cursor:pointer;line-height:1.3}
#ss-zf .c:hover{border-color:#f59e0b}#ss-zf .c.on{background:#f59e0b;border-color:#f59e0b;color:#111;font-weight:700}
#ss-zf .c.x{opacity:.55;cursor:help}#ss-zf .all{background:#f59e0b;color:#111;border:0;border-radius:9px;height:34px;padding:0 14px;font-weight:800;cursor:pointer}
#ss-zf .sp{display:inline-block;width:14px;height:14px;border:2px solid #334155;border-top-color:#f59e0b;border-radius:50%;animation:sszf 1s linear infinite;vertical-align:-2px;margin-right:6px}@keyframes sszf{to{transform:rotate(360deg)}}
</style>
<script>(function(){
var NOTES=['C','C#','D','D#','E','F','F#','G','G#','A','A#','B'];
function u16(d,o){return d.getUint16(o,true)}function u32(d,o){return d.getUint32(o,true)}
function rd(file,a,b){return file.slice(a,b).arrayBuffer()}
/* --- zip: central directory (with zip64) --- */
function entries(file){var L=Math.min(file.size,66000);return rd(file,file.size-L,file.size).then(function(buf){var d=new DataView(buf),i=buf.byteLength-22;for(;i>=0;i--)if(u32(d,i)===0x06054b50)break;if(i<0)throw 'not a zip';
 var n=u16(d,i+10),cs=u32(d,i+12),co=u32(d,i+16);
 var z64=(co===0xffffffff||n===0xffff)&&i>=20&&u32(d,i-20)===0x07064b50?Number(d.getBigUint64(i-20+8,true)):0;
 var p=z64?rd(file,z64,z64+56).then(function(b){var e=new DataView(b);return [Number(e.getBigUint64(32,true)),Number(e.getBigUint64(40,true)),Number(e.getBigUint64(48,true))]}):Promise.resolve([n,cs,co]);
 return p.then(function(x){if(x[1]>40e6)throw 'too big';return rd(file,x[2],x[2]+x[1]).then(function(cd){var v=new DataView(cd),o=0,out=[],td=new TextDecoder();
  while(o+46<=cd.byteLength&&u32(v,o)===0x02014b50){var m=u16(v,o+10),c=u32(v,o+20),s=u32(v,o+24),nl=u16(v,o+28),el=u16(v,o+30),cl=u16(v,o+32),lo=u32(v,o+42);
   var nm=td.decode(new Uint8Array(cd,o+46,nl));var e=o+46+nl,ee=e+el;while(e+4<=ee){var id=u16(v,e),sz=u16(v,e+2),q=e+4;if(id===1){if(s===0xffffffff){s=Number(v.getBigUint64(q,true));q+=8}if(c===0xffffffff){c=Number(v.getBigUint64(q,true));q+=8}if(lo===0xffffffff){lo=Number(v.getBigUint64(q,true))}}e+=4+sz}
   out.push({name:nm,method:m,csize:c,size:s,off:lo});o+=46+nl+el+cl}return out})})})}
function stream(buf,fmt){return new Response(new Blob([buf]).stream().pipeThrough(new DecompressionStream(fmt))).arrayBuffer()}
function readEntry(file,e){return rd(file,e.off,e.off+30).then(function(h){var d=new DataView(h);var start=e.off+30+u16(d,26)+u16(d,28);return rd(file,start,start+e.csize)}).then(function(b){return e.method===8?stream(b,'deflate-raw'):(e.method===0?b:Promise.reject('method '+e.method))})}
/* --- Ableton .als (gzip XML) --- */
function als(buf){var u=new Uint8Array(buf);var p=(u[0]===0x1f&&u[1]===0x8b)?stream(buf,'gzip'):Promise.resolve(buf);return p.then(function(x){return new TextDecoder().decode(x)})}
function cleanPlug(s){return s.replace(/\.(vst3?|component|dll|aaxplugin)$/i,'').replace(/[ _-]?\(?x(64|86)\)?$/i,'').replace(/_x64$/i,'').trim()}
function parseAls(x){var r={};var m=x.match(/Creator="Ableton Live ([\d.]+)/);if(m)r.live=m[1];
 m=x.match(/<Tempo>[\s\S]{0,4000}?<Manual Value="([\d.]+)"/);if(m)r.bpm=Math.round(parseFloat(m[1])*100)/100;
 var pl={},re=/<(VstPluginInfo|Vst3PluginInfo|AuPluginInfo)\b[\s\S]*?<\/\1>/g,b;while((b=re.exec(x))){var nm=b[0].match(/<PlugName Value="([^"]+)"/)||b[0].match(/<Name Value="([^"]+)"/);if(nm){var c=cleanPlug(nm[1]);if(c)pl[c.toLowerCase()]=c}}
 r.plugins=Object.keys(pl).map(function(k){return pl[k]}).slice(0,20);
 /* 19.11 files-first.php: samples the project uses (for the «missing samples» check); Ableton's own libraries are on every computer */
 var fl={},rr=/<SampleRef>[\s\S]*?<\/SampleRef>/g,sr;while((sr=rr.exec(x))){var pm=sr[0].match(/<Path Value="([^"]+)"/)||sr[0].match(/<RelativePath Value="([^"]+)"/);if(!pm)continue;var pth=pm[1].replace(/\\/g,'/');if(/(Core Library|\/Packs\/|Ableton\/Factory|Live Recordings)/i.test(pth))continue;var bn=pth.split('/').pop();if(bn)fl[bn.toLowerCase()]=bn}r.files=Object.keys(fl).map(function(k){return fl[k]});
 m=x.match(/<ScaleInformation>\s*<Root(?:Note)? Value="(\d+)"\s*\/>\s*<Name Value="([^"]+)"/);if(m){var nm2=m[2];r.scale=NOTES[+m[1]%12]+(/minor/i.test(nm2)?'m':(/major/i.test(nm2)?'':' '+nm2))}
 return r}
/* --- form helpers (apply only on the creator's tap) --- */
var form;function W(n){return form.querySelector('.ss-ap-'+n)}
function norm(s){return String(s||'').toLowerCase().replace(/[^a-z0-9#+]+/g,' ').trim()}
function fire(el){['input','change'].forEach(function(t){el.dispatchEvent(new Event(t,{bubbles:true}))});if(window.jQuery)jQuery(el).trigger('change')}
function choice(w,test){if(!w)return null;var hit=null;w.querySelectorAll('input[type=radio],input[type=checkbox]').forEach(function(i){if(hit)return;var l=i.closest('label');if(test(norm(l?l.textContent:i.value),i))hit=i});if(hit)return {set:function(){if(!hit.checked)hit.click()},on:function(){return hit.checked}};
 var s=w.querySelector('select');if(s){var o=[].filter.call(s.options,function(o){return o.value&&test(norm(o.textContent),o)})[0];if(o)return {set:function(){if(s.multiple)o.selected=true;else s.value=o.value;fire(s)},on:function(){return o.selected}}}return null}
function textField(w,val){if(!w)return null;var s=w.querySelector('select');if(s){var c=choice(w,function(t){return t===norm(val)});if(c)return c;if(s.getAttribute('data-tags')==='1'||s.multiple)return {set:function(){var o=new Option(val,val,true,true);s.appendChild(o);fire(s)},on:function(){return false}};return null}
 var i=w.querySelector('input[type=text],input[type=number],input:not([type])');if(!i)return null;return {set:function(){i.value=val;fire(i)},on:function(){return i.value==String(val)}}}
/* --- UI --- */
var box;function el(t,c,h){var e=document.createElement(t);if(c)e.className=c;if(h!=null)e.textContent=h;return e}
function row(label,items){if(!items.length)return;var r=el('div','r');r.appendChild(el('span','l',label));items.forEach(function(it){var b=el('button','c'+(it.act?'':' x'),it.text);b.type='button';if(!it.act){b.title=it.why||'Not in the list — type it in Tags if you want it shown';r.appendChild(b);return}b._act=it.act;b.addEventListener('click',function(){it.act.set();b.classList.add('on');if(it.only)[].forEach.call(r.querySelectorAll('.c.on'),function(o){if(o!==b)o.classList.remove('on')})});r.appendChild(b)});box.appendChild(r)}
function niceName(s){s=s.replace(/\.(zip|als|rar|7z)$/i,'').replace(/[_\-.]+/g,' ').replace(/\b\d{2,3}\s?bpm\b/ig,'').replace(/\b(final|master|v\d+|copy|project)\b/ig,'').replace(/\s{2,}/g,' ').trim();return s.replace(/\b([a-z])/g,function(a){return a.toUpperCase()})}
function show(zipName,es,r){box.textContent='';var h=el('div','h');h.appendChild(el('b',null,'📦 Found in your project'));var all=el('button','all','Use all suggestions');all.type='button';h.appendChild(all);box.appendChild(h);
 box.appendChild(el('p','n','Nothing changes until you tap. Tap a suggestion to put it into the form — you can still edit every field.'));
 var nameIn=form.querySelector('[name=product_name]');var names={};[zipName,r.alsName].forEach(function(s){var n=s&&niceName(s);if(n&&n.length>2)names[n.toLowerCase()]=n});
 row('Name',Object.keys(names).map(function(k){var v=names[k];return {text:v,only:1,act:{set:function(){nameIn.value=v;fire(nameIn)}}}}));
 if(r.live){var maj=r.live.split('.')[0];row('DAW',[{text:'Ableton Live',act:choice(W('daw'),function(t){return /ableton/.test(t)})}].concat([{text:'Live '+maj+' ('+r.live+')',only:1,act:choice(W('version'),function(t){return new RegExp('(^|\\D)'+maj+'(\\D|$)').test(t)})}]))}
 if(r.bpm)row('BPM',[{text:String(Math.round(r.bpm)),only:1,act:textField(W('bpm'),String(Math.round(r.bpm)))}]);
 if(r.scale)row('Key',[{text:r.scale+' (project scale)',only:1,act:choice(W('key'),function(t){var k=norm(r.scale);return t===k||t===k.replace(/m$/,' minor')||t===k+' major'})||textField(W('key'),r.scale)}]);
 var ext=W('ext');if(r.plugins){if(r.plugins.length)row('Plugins',r.plugins.map(function(p){var np=norm(p).replace(/ /g,'');return {text:p,act:choice(ext,function(t){t=t.replace(/ /g,'');return t===np||(t.length>3&&(t.indexOf(np)===0||np.indexOf(t)===0))})}}));else row('Plugins',[{text:'No external plugins',act:choice(ext,function(t){return /no (external|3rd|third)|stock|native|none/.test(t)})}])}
 var cnt={wav:0,mid:0,pre:0,als:0};es.forEach(function(e){var n=e.name.toLowerCase();if(/__macosx|\/\._/.test(n))return;if(/\.(wav|aif|aiff|flac)$/.test(n))cnt.wav++;else if(/\.midi?$/.test(n))cnt.mid++;else if(/\.(fxp|fxb|vital|vitalbank|nmsv|adv|adg|h2p|serumpreset)$/.test(n))cnt.pre++;else if(/\.als$/.test(n)&&!/backup\//.test(n))cnt.als++});
 var dl=W('dl'),pk=[];if(cnt.als)pk.push({text:'Ableton project',act:choice(dl,function(t){return /ableton|project|als/.test(t)})});if(cnt.wav)pk.push({text:'Samples ('+cnt.wav+')',act:choice(dl,function(t){return /sample|wav|audio|stem/.test(t)})});if(cnt.mid)pk.push({text:'MIDI ('+cnt.mid+')',act:choice(dl,function(t){return /midi/.test(t)})});if(cnt.pre)pk.push({text:'Presets ('+cnt.pre+')',act:choice(dl,function(t){return /preset/.test(t)})});
 row('Inside',pk);
 all.addEventListener('click',function(){[].forEach.call(box.querySelectorAll('.r'),function(rw){var bs=[].filter.call(rw.querySelectorAll('.c'),function(b){return b._act});(rw.querySelector('.c.on')?[]:(bs.length&&bs[0].parentNode.querySelector('.l').textContent==='Name'?bs.slice(0,1):(/^(BPM|Key)$/.test(rw.querySelector('.l').textContent)?bs.slice(0,1):bs))).forEach(function(b){b.click()})});all.textContent='✓ Added — check and edit';});
 box.classList.add('on');
 /* 19.11 files-first.php adds its rows (format, other DAWs, MP3, AI) and applies everything */
 try{document.dispatchEvent(new CustomEvent('ss:zip',{detail:{file:zipName,entries:es,als:r,box:box,row:row,choice:choice,textField:textField,W:W,norm:norm,readEntry:readEntry,zip:window.__ssZipFile}}))}catch(e){}}
function run(file){window.__ssZipFile=file;if(!/\.zip$/i.test(file.name))return;if(!('DecompressionStream' in window))return;box.classList.add('on');box.innerHTML='<span class="sp"></span>Reading your project…';
 entries(file).then(function(es){var a=es.filter(function(e){var n=e.name.toLowerCase();return /\.als$/.test(n)&&!/__macosx|(^|\/)\._|backup\//.test(n)&&e.size<200e6}).sort(function(x,y){return x.name.split('/').length-y.name.split('/').length||y.size-x.size})[0];
  if(!a)return show(file.name,es,{});return readEntry(file,a).then(als).then(function(x){var r=parseAls(x);r.alsName=a.name.split('/').pop();show(file.name,es,r)}).catch(function(){show(file.name,es,{})})})
 .catch(function(){box.classList.remove('on')})}
function init(){form=document.getElementById('solid-sound-upload-form');var inp=form&&form.querySelector('[name=product_file]');var drop=form&&form.querySelector('.ss-wm-files');if(!inp||!drop||form.dataset.zf)return !!(form&&drop);form.dataset.zf=1;
 box=document.createElement('div');box.id='ss-zf';drop.parentNode.insertBefore(box,drop.nextSibling);inp.addEventListener('change',function(){if(inp.files&&inp.files[0])run(inp.files[0])});return true}
if(!init()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(init())mo.disconnect()})});mo.observe(document.documentElement,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},12000)}
})();</script>
	<?php
}, 61 );
