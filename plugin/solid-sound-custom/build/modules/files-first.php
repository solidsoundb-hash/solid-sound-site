<?php
/**
 * (19.11.2026 owner: «в начале файлы грузить — с них возьмём всю информацию и на основе неё предложим что делать;
 *  подключай все AI инструменты»; «наша задача — упростить задачу авторам»)
 * Add Product, step 1 = drop the files. The existing drop zone (ZIP → project, MP3 → demo, JPG → cover) moves to the top.
 * Then everything we can learn is read and put into the form:
 *  - ZIP: the project type (Ableton .als / FL .flp / Logic / Cubase / Studio One / Bitwig / Reason) → format + DAW (+ version
 *    and tempo for Ableton and FL), presets / samples inside → format; Ableton details come from creator-autofill.php;
 *  - MP3 (read in the browser, never sent anywhere): length, tempo and the character of the sound (key only from the
 *    Ableton project: from audio it was right 1 time of 5 on our own previews, so we do not guess it);
 *  - AI (Cloudflare, ai-creator.php): genres, moods and tags chosen ONLY from the site's lists, then the description
 *    (the existing 3-version writer) if it is still empty.
 * Then description (the «Detailed» AI version), price (the middle of similar products) and, with no cover, an AI cover are
 * filled too — owner: «чтобы автор мог сделать всё в два клика». Filled fields get a small "auto" mark; the creator checks and changes anything. Nothing is sent before "Send for review".
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() || ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return;
	global $wp; if ( ! array_key_exists( 'wcfm-ss-add-product', (array) $wp->query_vars ) ) return;
	?>
<style id="ss-ff-css">
#solid-sound-upload-form .ss-wm-drop.ss-ff{margin:0 0 6px;padding:26px 18px;border:2px dashed rgba(245,158,11,.55);border-radius:16px;background:linear-gradient(180deg,rgba(245,158,11,.08),rgba(245,158,11,.02))}
#solid-sound-upload-form .ss-wm-drop.ss-ff .ss-ff-step{display:inline-block;margin:0 0 8px;padding:3px 10px;border-radius:999px;background:#F59E0B;color:#111;font:800 12px "Exo 2",sans-serif;letter-spacing:.06em}
#solid-sound-upload-form .ss-wm-drop.ss-ff>b{display:block;font-size:20px!important;color:#fff}
#solid-sound-upload-form .ss-wm-drop.ss-ff>small{display:block;max-width:560px;margin:6px auto 0;color:#CBD5E1;font-size:14.5px;line-height:1.5}
#ss-ff-st{display:none;margin:10px 0 0;padding:12px 14px;border-radius:12px;background:#0b1220;border:1px solid rgba(255,255,255,.08);color:#CBD5E1;font-size:14px}
#ss-ff-st.on{display:block}#ss-ff-st li{list-style:none;margin:3px 0}#ss-ff-st li[data-k=ready]{margin-top:8px;color:#fff;font-weight:700}#ss-ff-st ul{margin:0;padding:0}
#ss-ff-st .ok:before{content:"✓ ";color:#34D399;font-weight:800}#ss-ff-st .wt:before{content:"";display:inline-block;width:12px;height:12px;margin-right:6px;border:2px solid #334155;border-top-color:#F59E0B;border-radius:50%;animation:ssff 1s linear infinite;vertical-align:-2px}
#solid-sound-upload-form .ss-ff-go{animation:ssffgo 1.6s ease-in-out 3}@keyframes ssffgo{50%{box-shadow:0 0 0 6px rgba(245,158,11,.35)}}
#ss-ff-st .no:before{content:"– ";color:#64748B}@keyframes ssff{to{transform:rotate(360deg)}}
#solid-sound-upload-form .ss-ff-auto>.acf-label label:after,#solid-sound-upload-form p.ss-ff-auto .ss-wm-l:after{content:"auto";margin-left:8px;padding:1px 7px;border-radius:6px;background:rgba(52,211,153,.14);color:#34D399;font:700 11px "Exo 2",sans-serif;vertical-align:2px}
</style>
<script id="ss-ff-js">(function(){
var AJAX=<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>,N=<?php echo wp_json_encode( wp_create_nonce( 'ss_aic' ) ); ?>;
var form,st,facts={},done={zip:false,mp3:false},aiRan=false,lastZipName='';
function q(s,r){return (r||form).querySelector(s)}function qa(s,r){return [].slice.call((r||form).querySelectorAll(s))}
function F(n){return q('.ss-ap-'+n)}
function norm(s){return String(s||'').toLowerCase().replace(/[^a-z0-9#+]+/g,' ').trim()}
function txt(i){var l=i.closest('label');return norm(l?l.textContent:i.value)}
function mark(w){if(w)w.classList.add('ss-ff-auto')}
function pick(n,test,multi){var w=F(n);if(!w||w.classList.contains('ss-ap-off')&&n!=='format')return false;var hit=false;
 qa('input[type=radio],input[type=checkbox]',w).forEach(function(i){if(hit&&!multi)return;if(test(txt(i))){if(!i.checked)i.click();hit=true}});
 if(!hit){var s=q('select',w);if(s)[].forEach.call(s.options,function(o){if((!hit||multi)&&o.value&&test(norm(o.textContent))){o.selected=true;hit=true;s.dispatchEvent(new Event('change',{bubbles:true}));if(window.jQuery)jQuery(s).trigger('change')}})}
 if(hit)mark(w);return hit}
function empty(n){var w=F(n);if(!w)return false;return !qa('input:checked',w).length&&!qa('select option:checked',w).some(function(o){return o.value})&&!qa('input[type=text],input[type=number]',w).some(function(i){return i.value})}
function setText(n,v){var w=F(n);if(!w||w.classList.contains('ss-ap-off'))return false;var i=q('input[type=text],input[type=number],input:not([type])',w);if(i&&!i.value){i.value=v;i.dispatchEvent(new Event('input',{bubbles:true}));i.dispatchEvent(new Event('change',{bubbles:true}));mark(w);return true}
 return pick(n,function(t){return t===norm(v)})}
function line(k,cls,t){var u=q('ul',st);var li=st.querySelector('[data-k='+k+']');if(!li){li=document.createElement('li');li.setAttribute('data-k',k);u.appendChild(li)}li.className=cls;li.textContent=t;st.classList.add('on')}
/* ---------- name (owner 19.11: «продукт нейм — автоматом из файлов, с мп3 превью») ---------- */
function cleanName(s){s=String(s||'').replace(/\.(mp3|wav|zip|rar|7z|als|flp)$/i,'').replace(/[_]+/g,' ').replace(/(\w)-(?=\w)/g,'$1 ')
 .replace(/\b\d{2,3}\s?k?bps\b/ig,'').replace(/\b\d{2,3}\s?bpm\b/ig,'').replace(/\b(demo|preview|watermark(ed)?|wm|master(ed)?|final|mixdown|export|copy|snippet|teaser|v\d+|\d{2,3})\b/ig,'')
 .replace(/\(\s*\)|\[\s*\]/g,'').replace(/\s{2,}/g,' ').replace(/^[\s\-–—.]+|[\s\-–—.]+$/g,'').trim();
 return s.replace(/\b([a-z])/g,function(a){return a.toUpperCase()})}
function id3title(buf){var d=new DataView(buf);if(buf.byteLength<10||d.getUint8(0)!==73||d.getUint8(1)!==68||d.getUint8(2)!==51)return '';var v=d.getUint8(3),end=10+((d.getUint8(6)&127)<<21|(d.getUint8(7)&127)<<14|(d.getUint8(8)&127)<<7|(d.getUint8(9)&127)),o=10;
 while(o+10<Math.min(end,buf.byteLength)){var id=String.fromCharCode(d.getUint8(o),d.getUint8(o+1),d.getUint8(o+2),d.getUint8(o+3));var sz=v===4?((d.getUint8(o+4)&127)<<21|(d.getUint8(o+5)&127)<<14|(d.getUint8(o+6)&127)<<7|(d.getUint8(o+7)&127)):d.getUint32(o+4);if(!sz||/[^A-Z0-9]/.test(id))break;
  if(id==='TIT2'){var enc=d.getUint8(o+10),bytes=new Uint8Array(buf,o+11,Math.max(0,sz-1)),t='';try{t=new TextDecoder(enc===1?'utf-16':(enc===2?'utf-16be':(enc===3?'utf-8':'latin1'))).decode(bytes)}catch(e){}return t.replace(/\u0000/g,'').trim()}o+=10+sz}return ''}
var names=[],nameAuto='',namePri=0,PRI={tag:3,mp3:2,zip:1};
function offerName(v,src){v=cleanName(v);if(!v||v.length<2||names.some(function(n){return n[0].toLowerCase()===v.toLowerCase()}))return;names.push([v,src]);
 var nm=q('[name=product_name]');if(!nm)return;
 if(!nm.value.trim()||(nm.value===nameAuto&&PRI[src]>namePri)){nm.value=v;nameAuto=v;namePri=PRI[src];nm.dispatchEvent(new Event('input',{bubbles:true}));nm.dispatchEvent(new Event('change',{bubbles:true}));mark(nm.closest('p'))}
 var li=st.querySelector('[data-k=name]');line('name','ok','Name: ');li=st.querySelector('[data-k=name]');li.textContent='Name: ';
 names.forEach(function(n){var b=document.createElement('button');b.type='button';b.textContent=n[0]+(n[1]==='mp3'?' (from MP3)':(n[1]==='tag'?' (MP3 title)':' (from ZIP)'));b.style.cssText='margin:2px 6px 2px 0;padding:3px 10px;border-radius:999px;border:1px solid '+(nm.value===n[0]?'#F59E0B':'rgba(148,163,184,.4)')+';background:'+(nm.value===n[0]?'#F59E0B':'transparent')+';color:'+(nm.value===n[0]?'#111':'#e2e8f0')+';font-size:13px;cursor:pointer';
  b.onclick=function(){nm.value=n[0];nameAuto=n[0];nm.dispatchEvent(new Event('input',{bubbles:true}));offerName.redraw()};li.appendChild(b)})}
offerName.redraw=function(){var keep=names.slice();names=[];keep.forEach(function(n){names.push(n)});var nm=q('[name=product_name]');var li=st.querySelector('[data-k=name]');if(!li)return;[].forEach.call(li.querySelectorAll('button'),function(b){var on=b.textContent.indexOf(nm.value+' (')===0;b.style.background=on?'#F59E0B':'transparent';b.style.color=on?'#111':'#e2e8f0';b.style.borderColor=on?'#F59E0B':'rgba(148,163,184,.4)'})};
/* ---------- MP3: length, tempo, key (in the browser) ---------- */
function tempo(x,sr){var hop=128,win=1024,env=[];for(var i=0;i+win<x.length;i+=hop){var e=0;for(var j=0;j<win;j+=2)e+=x[i+j]*x[i+j];env.push(Math.log(1e-6+e))}
 var on=new Float64Array(env.length);for(var i=1;i<env.length;i++)on[i]=Math.max(0,env[i]-env[i-1]);var m=0;for(var i=0;i<on.length;i++)m+=on[i];m/=on.length;for(var i=0;i<on.length;i++)on[i]=Math.max(0,on[i]-m);
 var fps=sr/hop;function ac(l){var s=0,li=Math.floor(l),fr=l-li;for(var i=0;i+li+1<on.length;i++)s+=on[i]*(on[i+li]*(1-fr)+on[i+li+1]*fr);return s}
 function sc(b){var l=fps*60/b;return ac(l)+0.5*ac(2*l)+0.5*ac(4*l)+0.25*ac(l/2)}
 var res=[];for(var b=80;b<=180;b+=0.25)res.push([b,sc(b)]);res.sort(function(a,c){return c[1]-a[1]});var best=res[0];
 /* prefer the club range 100-150 when the double/half is nearly as strong */
 var alt=[best[0]/2,best[0]*2,best[0]*2/3,best[0]*3/2].filter(function(b){return b>=100&&b<=150});if(best[0]<100||best[0]>150){alt.forEach(function(b){var s=sc(b);if(s>=best[1]*0.7)best=[b,s]})}
 return Math.round(best[0])}
function feel(x){var s=0,z=0;for(var i=1;i<x.length;i+=7){s+=Math.abs(x[i]);if((x[i]>0)!==(x[i-1]>0))z++}var e=s/(x.length/7),b=z/(x.length/7);return (e>0.18?'loud, energetic':(e>0.09?'medium energy':'calm, quiet'))+', '+(b>0.12?'bright':'warm/dark')+' sound'}
function readMp3(f){line('mp3','wt','Listening to '+f.name+'…');
 f.arrayBuffer().then(function(ab){var tt=id3title(ab.slice(0,Math.min(ab.byteLength,262144)));if(tt)offerName(tt,'tag');offerName(f.name,'mp3');var C=window.AudioContext||window.webkitAudioContext;var ctx=new C();return ctx.decodeAudioData(ab).then(function(buf){ctx.close&&ctx.close();return buf})}).then(function(buf){
  var dur=buf.duration,sr=buf.sampleRate,ch0=buf.getChannelData(0),ch1=buf.numberOfChannels>1?buf.getChannelData(1):ch0;
  var a=Math.floor(Math.min(dur*0.25,30)*sr),b=Math.min(ch0.length,a+Math.floor(45*sr)),x=new Float32Array(b-a);for(var i=a;i<b;i++)x[i-a]=(ch0[i]+ch1[i])/2;
  var bpm=tempo(x,sr),k='',mm=Math.floor(dur/60)+':'+('0'+Math.round(dur%60)).slice(-2);
  facts.preview_length=mm;if(!facts.bpm&&bpm)facts.bpm_from_audio=bpm;facts.sound=feel(x);
  var got=['length '+mm];if(setText('length',mm));
  if(bpm&&empty('bpm')&&setText('bpm',String(bpm)))got.push(bpm+' BPM');
  line('mp3','ok','Preview: '+got.join(' · '));done.mp3=true;ai()}).catch(function(){line('mp3','no','Could not read the MP3 here (it is still attached).');done.mp3=true;ai()})}
/* ---------- ZIP: project type, other DAWs, FL tempo/version ---------- */
var DAW=[[/\.als$/,'Ableton Live',/ableton/],[/\.flp$/,'FL Studio',/fl studio/],[/\.logicx(\/|$)/,'Logic Pro',/logic/],[/\.cpr$/,'Cubase',/cubase/],[/\.song$/,'Studio One',/studio one/],[/\.bwproject$/,'Bitwig Studio',/bitwig/],[/\.reason$/,'Reason',/reason/]];
function flp(buf){var d=new DataView(buf),o=0,r={};if(String.fromCharCode(d.getUint8(0),d.getUint8(1),d.getUint8(2),d.getUint8(3))!=='FLhd')return r;o=8+d.getUint32(4,true);o+=8;
 while(o<buf.byteLength){var id=d.getUint8(o++),v;if(id<64){o+=1}else if(id<128){o+=2}else if(id<192){v=d.getUint32(o,true);o+=4;if(id===156)r.bpm=Math.round(v/1000)}else{var len=0,sh=0,b;do{b=d.getUint8(o++);len|=(b&127)<<sh;sh+=7}while(b&128);if(id===199){var t='';for(var i=0;i<len-1;i++)t+=String.fromCharCode(d.getUint8(o+i));r.ver=t}o+=len}if(r.bpm&&r.ver)break}return r}
document.addEventListener('ss:zip',function(e){var x=e.detail,es=x.entries||[],names=es.map(function(z){return z.name.toLowerCase()}).filter(function(n){return !/__macosx|(^|\/)\._|backup\//.test(n)});
 var daw=null,proj=null;DAW.forEach(function(d){if(daw)return;var n=names.filter(function(nm){return d[0].test(nm)})[0];if(n){daw=d;proj=n}});
 var cnt={wav:0,pre:0,mid:0};names.forEach(function(n){if(/\.(wav|aif|aiff|flac)$/.test(n))cnt.wav++;else if(/\.(fxp|fxb|vital|vitalbank|nmsv|adv|adg|h2p|serumpreset|ksd|nksf)$/.test(n))cnt.pre++;else if(/\.midi?$/.test(n))cnt.mid++});
 var fmt=daw?'template':(cnt.pre>=3?'preset':(cnt.wav>=5?'sample':''));
 facts.files_in_zip=names.length+' files'+(cnt.wav?', '+cnt.wav+' audio':'')+(cnt.pre?', '+cnt.pre+' presets':'')+(cnt.mid?', '+cnt.mid+' MIDI':'');
 if(daw)facts.daw=daw[1];if(x.als){if(x.als.live)facts.daw_version='Ableton Live '+x.als.live;if(x.als.bpm)facts.bpm=x.als.bpm;if(x.als.scale)facts.key=x.als.scale;if(x.als.plugins)facts.plugins=x.als.plugins.length?x.als.plugins.join(', '):'no external plugins'}
 if(x.file)offerName(x.file,'zip');if(x.als&&x.als.alsName)offerName(x.als.alsName,'zip');
 var got=[];
 if(fmt&&!qa('input:checked',F('format')||form).length){if(pick('format',function(t){return fmt==='template'?/^templates?$/.test(t):(fmt==='preset'?/preset/.test(t):/sample/.test(t))}))got.push(fmt==='template'?'Template':(fmt==='preset'?'Presets':'Sample pack'))}
 if(daw&&pick('daw',function(t){return daw[2].test(t)}))got.push(daw[1]);
 var after=function(){
  if(x.box){[].forEach.call(x.box.querySelectorAll('.r'),function(rw){var l=rw.querySelector('.l');if(l&&l.textContent==='Name')rw.remove()}); /* names are offered in our list (MP3 first) */
   var all=x.box.querySelector('.all');var n=x.box.querySelector('.n');if(n)n.textContent='We filled these in from your project — tap a chip to change it. Every field stays editable.';if(all&&!all._auto){all._auto=1;all.click()}}
  line('zip','ok','Project: '+(got.length?got.join(' · '):'read')+(facts.files_in_zip?' · '+facts.files_in_zip:''));done.zip=true;ai()};
 if(daw&&daw[1]==='FL Studio'&&x.zip&&x.readEntry){var en=es.filter(function(z){return /\.flp$/i.test(z.name)})[0];x.readEntry(x.zip,en).then(function(b){var r=flp(b);if(r.bpm){facts.bpm=r.bpm;if(empty('bpm'))setText('bpm',String(r.bpm))&&got.push(r.bpm+' BPM')}if(r.ver){facts.daw_version='FL Studio '+r.ver;var maj=r.ver.split('.')[0];setTimeout(function(){if(pick('version',function(t){return t.indexOf('fl studio '+maj)===0}))got.push('FL Studio '+maj)},60)}setTimeout(after,120)}).catch(after)}
 else setTimeout(after,60)});
/* ---------- AI: genres, moods, tags, then the description ---------- */
function labels(n){var w=F(n);if(!w)return [];var a=qa('input[type=checkbox],input[type=radio]',w).map(function(i){var l=i.closest('label');return (l?l.textContent:'').trim()});var s=q('select',w);if(s)a=a.concat([].map.call(s.options,function(o){return o.value?o.textContent.trim():''}));return a.filter(Boolean)}
function ai(){if(aiRan||!(done.zip||done.mp3))return;if(!done.zip&&lastZipName)return;if(!done.mp3&&window.__ssFFmp3)return;aiRan=true;
 line('ai','wt','AI is choosing genre, mood and tags…');
 var f=window.ssAicFacts?window.ssAicFacts(form):{};for(var k in facts)if(!f[k])f[k]=facts[k];if(lastZipName)f.zip_name=lastZipName;
 var o={genre:labels('genre'),mood:labels('mood'),tag:labels('tag')};var fd=new FormData();fd.append('action','ss_aic_tags');fd.append('n',N);fd.append('f',JSON.stringify(f));fd.append('o',JSON.stringify(o));
 fetch(AJAX,{method:'POST',credentials:'same-origin',body:fd}).then(function(r){return r.json()}).then(function(j){if(!(j&&j.success)){line('ai','no','AI: '+((j&&j.data&&j.data.m)||'not available now')+' — pick genre and mood yourself.');return}
  var d=j.data,got=[];['genre','mood','tag'].forEach(function(n){(d[n]||[]).forEach(function(v){if(pick(n,function(t){return t===norm(v)},true))got.push(v)})});
  line('ai',got.length?'ok':'no',got.length?'AI picked: '+got.join(', '):'AI was not sure — pick genre and mood yourself.');
  autopilot();
 }).catch(function(){line('ai','no','AI is busy — pick genre and mood yourself.');autopilot()})}
/* ---------- «two clicks» (owner 19.11): description, price and cover are filled too; the creator only checks and sends ---------- */
function waitFor(test,ms){return new Promise(function(res){var t0=Date.now();(function tick(){var r=test();if(r)return res(r);if(Date.now()-t0>ms)return res(null);setTimeout(tick,400)})()})}
var auto=false;
function autopilot(){if(auto)return;auto=true;var jobs=[];
 var desc=q('[name=product_description]'),nm=q('[name=product_name]');
 if(desc&&!desc.value.trim()&&nm&&nm.value.trim()){var b=qa('.ss-aic-b').filter(function(x){return /write with ai|new versions/i.test(x.textContent)})[0];if(b){line('desc','wt','AI is writing the description…');b.click();
  jobs.push(waitFor(function(){var u=qa('.ss-aic-pick .ss-aic-card .use:not([disabled])');return u.length>=2||(u.length&&!qa('.ss-aic-pick .ss-aic-card .t').some(function(t){return t.textContent==='…'}))?u:null},45000).then(function(u){if(!u){line('desc','no','Description: AI is slow — write it or press “Write with AI”.');return}if(desc.value.trim())return;var pick=u.filter(function(x){return /detailed/i.test(x.closest('.ss-aic-card').querySelector('b').textContent)})[0]||u[0];pick.click();mark(desc.closest('p'));line('desc','ok','Description written — 2 other versions are under it if you prefer.')}))}}
 var pr=q('[name=product_price]');if(pr&&!pr.value){jobs.push(waitFor(function(){var c=qa('.ss-aic-pr .ss-aic-c');return c.length?c:null},8000).then(function(c){if(!c||pr.value)return;(c[Math.floor((c.length-1)/2)]||c[0]).click();mark(pr.closest('p'));line('price','ok','Price set like similar products: '+pr.value+' — change it if you want.')}))}
 var img=q('[name=product_image]');if(img&&!(img.files&&img.files.length)){var cb=qa('.ss-aic-b').filter(function(x){return /cover with ai/i.test(x.textContent)})[0];if(cb&&nm&&nm.value.trim()){line('cover','wt','No cover yet — AI is painting one (10–30 s)…');cb.click();
  jobs.push(waitFor(function(){var bx=q('.ss-aic-cv.on');return bx&&bx.querySelector('.th .tb')?bx:null},60000).then(function(bx){if(!bx){line('cover','no','Cover: AI is slow — drop your own picture or try again.');return}if(img.files&&img.files.length)return;setTimeout(function(){var u=bx.querySelector('.use');if(u)u.click();line('cover','ok','AI made a cover — 2 more options and your text are above, or drop your own picture.')},700)}))}}
 Promise.all(jobs).then(function(){line('ready','ok','Ready. Check the form and press “Send for review”.');var sb=q('.ss-wm-save');if(sb)sb.classList.add('ss-ff-go')})}
/* ---------- layout: files first ---------- */
function init(){form=document.getElementById('solid-sound-upload-form');if(!form||form.dataset.ff)return !!form;var drop=q('.ss-wm-drop'),files=q('.ss-wm-files'),body=q('.ss-wm-body');if(!drop||!body)return false;form.dataset.ff=1;
 drop.classList.add('ss-ff');drop.querySelector('b').textContent='Drop your files — we fill in the rest';var sm=drop.querySelector('small');if(sm)sm.textContent='Project ZIP, MP3 preview and cover. We read the DAW, version, tempo, key (Ableton), plugins and length, and AI suggests genre, mood, tags and a description. You check and change anything.';
 var tag=document.createElement('span');tag.className='ss-ff-step';tag.textContent='STEP 1';drop.insertBefore(tag,drop.firstChild);
 st=document.createElement('div');st.id='ss-ff-st';st.innerHTML='<ul></ul>';
 body.insertBefore(drop,body.firstChild);if(files)body.insertBefore(files,drop.nextSibling);body.insertBefore(st,(files||drop).nextSibling);
 var zf=document.getElementById('ss-zf');if(zf)body.insertBefore(zf,st.nextSibling);
 var hint=q('.ss-ds-first');if(hint)hint.textContent='Step 2: check what you are selling (we pick it from your files).';
 /* grab files before the drop zone routes them (capture phase) */
 function seen(list){[].forEach.call(list||[],function(f){var n=f.name.toLowerCase();if(/\.mp3$/.test(n)||/^audio\//.test(f.type)&&!/\.zip$/.test(n)){window.__ssFFmp3=1;done.mp3=false;aiRan=false;readMp3(f)}else if(/\.(zip|rar|7z)$/.test(n)){lastZipName=f.name;done.zip=false;aiRan=false;line('zip','wt','Reading '+f.name+'…');if(!/\.zip$/.test(n)){line('zip','no','RAR/7z cannot be read here — fill DAW and version yourself.');done.zip=true;ai()}else{setTimeout(function(){if(!done.zip){done.zip=true;line('zip','no','Could not read the ZIP here — fill DAW and version yourself.');ai()}},20000)}}else if(/^image\//.test(f.type))line('cover','ok','Cover: '+f.name)})}
 document.addEventListener('change',function(e){var t=e.target;if(t&&t.type==='file'&&form.contains(t)&&t.name!=='product_file'&&t.name!=='product_image')seen(t.files)},true);
 drop.addEventListener('drop',function(e){if(e.dataTransfer)seen(e.dataTransfer.files)},true);
 return true}
if(!init()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(init())mo.disconnect()})});mo.observe(document.documentElement,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},15000)}
})();</script>
	<?php
}, 70 );
