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
#ss-ff-st .warn{color:#FDE68A}#ss-ff-st .warn:before{content:"⚠ ";color:#F59E0B}
#ss-ff-pv{display:flex;flex-wrap:wrap;align-items:center;gap:8px;margin:6px 0 0}#ss-ff-pv audio{height:34px;max-width:100%}#ss-ff-pv button{height:32px;padding:0 12px;border-radius:8px;border:1px solid rgba(255,255,255,.18);background:#1A2540;color:#fff;font:600 13px "Exo 2",sans-serif;cursor:pointer}
#ss-ff-st .no:before{content:"– ";color:#64748B}@keyframes ssff{to{transform:rotate(360deg)}}
#solid-sound-upload-form .ss-ff-auto>.acf-label label:after,#solid-sound-upload-form p.ss-ff-auto .ss-wm-l:after{content:"auto";margin-left:8px;padding:1px 7px;border-radius:6px;background:rgba(52,211,153,.14);color:#34D399;font:700 11px "Exo 2",sans-serif;vertical-align:2px}

/* 19.11 owner («переделать Add product, грамотно совместить всё»): one flow in 3 cards */
#solid-sound-upload-form .ss-ff-card{margin:0 0 16px;padding:20px;border-radius:16px;background:#0f1729;border:1px solid rgba(255,255,255,.08)}
#solid-sound-upload-form .ss-ff-h{display:flex;align-items:flex-start;gap:12px;margin:0 0 14px}
#solid-sound-upload-form .ss-ff-h i{flex:none;width:30px;height:30px;border-radius:50%;background:#F59E0B;color:#111;font:800 15px/30px "Exo 2",sans-serif;text-align:center;font-style:normal}
#solid-sound-upload-form .ss-ff-h b{display:block;color:#fff;font:800 19px/1.25 "Exo 2",sans-serif}
#solid-sound-upload-form .ss-ff-h small{display:block;margin-top:3px;color:#94A3B8;font-size:14px;line-height:1.45}
#solid-sound-upload-form.ss-ds-nofmt .ss-wm-body>.ss-ff-card.ss-ff-c1,#solid-sound-upload-form.ss-ds-nofmt .ss-wm-body>.ss-ff-card.ss-ff-c2{display:block!important}
/* card 2 compact: choices as small chips that do not stretch, the small fields in one tight grid */
#solid-sound-upload-form .ss-ff-c2 .acf-checkbox-list,#solid-sound-upload-form .ss-ff-c2 .acf-radio-list{display:flex!important;flex-wrap:wrap!important;gap:6px!important;grid-template-columns:none!important}
#solid-sound-upload-form .ss-ff-c2 .acf-checkbox-list>li,#solid-sound-upload-form .ss-ff-c2 .acf-radio-list>li{flex:0 0 auto!important;width:auto!important;margin:0!important}
#solid-sound-upload-form .ss-ff-c2 .acf-checkbox-list label,#solid-sound-upload-form .ss-ff-c2 .acf-radio-list label{display:inline-flex!important;align-items:center;gap:6px;width:auto!important;min-height:0!important;padding:6px 11px!important;font-size:14px!important;line-height:1.25!important}
#solid-sound-upload-form .ss-ff-c2>.acf-field{margin:0 0 14px!important;padding:0!important}
#solid-sound-upload-form .ss-ff-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:10px 14px;margin:0 0 14px}
#solid-sound-upload-form .ss-ff-grid>.ss-wm-row,#solid-sound-upload-form .ss-ff-grid>.ss-wm-row2{display:contents!important}
#solid-sound-upload-form .ss-ff-grid .acf-field{margin:0!important;padding:0!important}
#solid-sound-upload-form .ss-ff-grid .acf-field.ss-ap-off{display:none!important}
#solid-sound-upload-form .ss-ff-grid .acf-checkbox-list,#solid-sound-upload-form .ss-ff-grid .acf-radio-list{max-height:180px;overflow:auto}
/* card 3: cover */
#solid-sound-upload-form .ss-ff-cov{display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin:0 0 14px}
#solid-sound-upload-form .ss-ff-cov .pv{flex:none;width:120px;height:120px;border-radius:12px;border:1px dashed rgba(255,255,255,.18);background:#0b1220;display:flex;align-items:center;justify-content:center;overflow:hidden;color:#64748B;font-size:12.5px;text-align:center}
#solid-sound-upload-form .ss-ff-cov .pv img{width:100%;height:100%;object-fit:cover}
#solid-sound-upload-form .ss-ff-cov .bt{display:flex;flex-wrap:wrap;gap:10px}
#solid-sound-upload-form .ss-ff-up,#solid-sound-upload-form .ss-ff-cov .bt .ss-aic-b{display:inline-flex!important;align-items:center;justify-content:center;min-height:44px;padding:0 18px!important;border-radius:10px!important;font:700 15px "Exo 2",sans-serif!important;cursor:pointer;margin:0!important}
#solid-sound-upload-form .ss-ff-up{border:0;background:#F59E0B;color:#111}
#solid-sound-upload-form .ss-ff-cov .bt .ss-aic-b{border:1px solid #F59E0B!important;background:transparent!important;color:#F59E0B!important}
#solid-sound-upload-form .ss-ff-c4 .ss-wm-ai{margin:0 0 14px!important}
#solid-sound-upload-form.ss-ds-nofmt .ss-ff-card.ss-ff-c2>*:not(.ss-ap-format):not(.ss-ff-h){display:none!important}
#solid-sound-upload-form .ss-ds-first,#solid-sound-upload-form .ss-wm-drop.ss-ff .ss-ff-step{display:none!important}
#solid-sound-upload-form .ss-wm-drop.ss-ff{margin:0}
@media(max-width:600px){#solid-sound-upload-form .ss-ff-card{padding:14px;border-radius:14px}#solid-sound-upload-form .ss-ff-h b{font-size:17px}}
</style>
<script id="ss-ff-js">(function(){
var FF3=<?php echo get_option( 'ss_ff3_on' ) ? 'true' : 'false'; ?>,AJAX=<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>,N=<?php echo wp_json_encode( wp_create_nonce( 'ss_aic' ) ); ?>;
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
/* ---------- checks before sending (owner 19.11: «проверка перед отправкой») ---------- */
function chk(k,ok,t){line('chk-'+k,ok?'ok':'warn',t)}
/* ---------- name (owner 19.11: «продукт нейм — автоматом из файлов, с мп3 превью») ---------- */
function cleanName(s){s=String(s||'').replace(/\.(mp3|wav|zip|rar|7z|als|flp)$/i,'').replace(/[_]+/g,' ').replace(/(\w)-(?=\w)/g,'$1 ')
 .replace(/\b\d{2,3}\s?k?bps\b/ig,'').replace(/\b\d{2,3}\s?bpm\b/ig,'').replace(/\b(demo|preview|watermark(ed)?|wm|master(ed)?|final|mixdown|export|copy|snippet|teaser|full track|full version|full|stereo|24bit|16bit|44k|48k|v\d+|\d{2,3})\b/ig,'')
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
  line('mp3','ok','Preview: '+got.join(' · '));
  if(!f._ssGen){var pk=0,lead=-1,th=0.01;for(var i=0;i<ch0.length;i+=2){var v=Math.max(Math.abs(ch0[i]),Math.abs(ch1[i]));if(v>pk)pk=v;if(lead<0&&v>th)lead=i/sr}
   chk('clip',pk<0.995,pk<0.995?'Preview level is fine.':'Preview hits 0 dB — it may crackle on phones. Export it at −1 dB, or drop the WAV and we make a clean preview.');
   chk('sil',lead<1.5,lead<1.5?'Preview starts right away.':'Preview starts with '+lead.toFixed(1)+' s of silence — buyers skip it. Trim the start.');
   var kb=Math.round(f.size*8/dur/1000);chk('rate',kb>=128||!/\.mp3$/i.test(f.name),kb>=128||!/\.mp3$/i.test(f.name)?'Preview quality is fine.':'Preview is '+kb+' kbps — use 192–320 kbps so it sounds pro.');
   if(dur<30)chk('len',false,'Preview is only '+Math.round(dur)+' s — 45–90 s sells better.')}done.mp3=true;ai()}).catch(function(){line('mp3','no','Could not read the MP3 here (it is still attached).');done.mp3=true;ai()})}
/* ---------- preview from the full track (owner 19.11: «превью из полного трека») ---------- */
var LAME='https://cdn.jsdelivr.net/npm/lamejs@1.2.1/lame.min.js',lameP=null;
function lame(){return lameP||(lameP=new Promise(function(ok,no){var sc=document.createElement('script');sc.src=LAME;sc.onload=function(){ok(window.lamejs)};sc.onerror=no;document.head.appendChild(sc)}))}
var full=null;
function makePreview(f,shift){line('mp3','wt','Making a 75-second preview from '+f.name+'…');
 (full&&full.f===f?Promise.resolve(full.buf):f.arrayBuffer().then(function(ab){var C=window.AudioContext||window.webkitAudioContext;var ctx=new C();return ctx.decodeAudioData(ab).then(function(b){ctx.close&&ctx.close();return b})})).then(function(buf){
  if(!full||full.f!==f){full={f:f,buf:buf,start:null};offerName(f.name,'mp3');readMp3Buf(buf,f)}
  var sr=buf.sampleRate,L=75,dur=buf.duration,c0=buf.getChannelData(0),c1=buf.numberOfChannels>1?buf.getChannelData(1):c0;
  if(full.start==null){var sec=[];for(var s0=0;s0+1<=dur;s0++){var e=0,a=Math.floor(s0*sr),b=Math.floor((s0+1)*sr);for(var i=a;i<b;i+=8)e+=c0[i]*c0[i]+c1[i]*c1[i];sec.push(e)}
   var best=0,bs=0;for(var st0=Math.floor(dur*0.1);st0+L<=sec.length;st0++){var sum=0;for(var k=0;k<L;k++)sum+=sec[st0+k];if(sum>best){best=sum;bs=st0}}full.start=dur<=L+5?0:bs}
  full.start=Math.max(0,Math.min(Math.max(0,dur-L),full.start+(shift||0)));
  var a0=Math.floor(full.start*sr),n=Math.min(Math.floor(L*sr),c0.length-a0),pk=0;for(var i=0;i<n;i++)pk=Math.max(pk,Math.abs(c0[a0+i]),Math.abs(c1[a0+i]));
  var g=Math.min(2,0.89/(pk||1)),fi=1.5*sr,fo=3*sr;
  return lame().then(function(lj){var enc=new lj.Mp3Encoder(2,sr,192),out=[],B=1152,l=new Int16Array(B),r=new Int16Array(B);
   for(var p=0;p<n;p+=B){var m=Math.min(B,n-p);if(m<B){l=new Int16Array(m);r=new Int16Array(m)}for(var j=0;j<m;j++){var t=p+j,fade=Math.min(1,t/fi,(n-t)/fo),vl=c0[a0+t]*g*fade,vr=c1[a0+t]*g*fade;l[j]=Math.max(-32768,Math.min(32767,vl*32767));r[j]=Math.max(-32768,Math.min(32767,vr*32767))}var mp=enc.encodeBuffer(l,r);if(mp.length)out.push(new Uint8Array(mp))}
   var end=enc.flush();if(end.length)out.push(new Uint8Array(end));
   var nm=(cleanName(f.name)||'preview').replace(/[^\w\-]+/g,'-')+'-preview.mp3',file=new File(out,nm,{type:'audio/mpeg'});file._ssGen=1;
   if(multi){var dt=new DataTransfer();dt.items.add(file);multi._ssff=1;multi.files=dt.files;multi.dispatchEvent(new Event('change',{bubbles:true}));multi._ssff=0}
   var mm=function(x){return Math.floor(x/60)+':'+('0'+Math.floor(x%60)).slice(-2)};
   line('mp3','ok','Preview made from your track: '+mm(full.start)+'–'+mm(full.start+n/sr)+', clean level, soft start and end.');
   var li=st.querySelector('[data-k=mp3]'),box=document.getElementById('ss-ff-pv');if(!box){box=document.createElement('div');box.id='ss-ff-pv';li.appendChild(box)}
   box.innerHTML='';var au=document.createElement('audio');au.controls=true;au.src=URL.createObjectURL(file);box.appendChild(au);
   [['◀ 15 s earlier',-15],['15 s later ▶',15]].forEach(function(x){var b=document.createElement('button');b.type='button';b.textContent=x[0];b.onclick=function(){makePreview(f,x[1])};box.appendChild(b)})})
 }).catch(function(){line('mp3','no','Could not make a preview here — please export a 60–90 s MP3 yourself.')})}
function readMp3Buf(buf,f){/* tempo + length from the full track */var dur=buf.duration,sr=buf.sampleRate,ch0=buf.getChannelData(0),ch1=buf.numberOfChannels>1?buf.getChannelData(1):ch0;var a=Math.floor(Math.min(dur*0.25,30)*sr),b=Math.min(ch0.length,a+Math.floor(45*sr)),x=new Float32Array(b-a);for(var i=a;i<b;i++)x[i-a]=(ch0[i]+ch1[i])/2;
 var bpm=tempo(x,sr);facts.track_length=Math.floor(dur/60)+':'+('0'+Math.round(dur%60)).slice(-2);facts.sound=feel(x);if(bpm&&empty('bpm')&&setText('bpm',String(bpm)))facts.bpm_from_audio=bpm;setText('length',facts.track_length);window.__ssFFmp3=1;done.mp3=true;ai()}
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
 if(fmt==='template'||daw)chk('proj',!!proj,proj?'Project file found ('+proj.split('/').pop()+').':'No project file (.als, .flp…) in the ZIP.');
 if(x.als&&x.als.files){var have={};names.forEach(function(n){have[n.split('/').pop()]=1});var miss=x.als.files.filter(function(b){return !have[b.toLowerCase()]});chk('smp',!miss.length,miss.length?miss.length+' sample'+(miss.length>1?'s':'')+' used in the project '+(miss.length>1?'are':'is')+' not in the ZIP ('+miss.slice(0,3).join(', ')+(miss.length>3?'…':'')+'). Buyers would hear silence — in Ableton use File → Collect All and Save, then zip again.':'All samples the project uses are in the ZIP.')}
 if(x.zip&&x.zip.size>1.5e9)chk('size',false,'The ZIP is '+(x.zip.size/1e9).toFixed(1)+' GB — big downloads fail on slow internet. Remove unused samples or backups.');
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
 drop.classList.add('ss-ff');drop.querySelector('b').textContent='Drop your files — we fill in the rest';var sm=drop.querySelector('small');if(sm)sm.textContent='Project ZIP, the full track (WAV — we cut the preview) or your MP3 preview, and a cover. We read the DAW, version, tempo, key (Ableton), plugins and length, and AI suggests genre, mood, tags and a description. You check and change anything.';
 var tag=document.createElement('span');tag.className='ss-ff-step';tag.textContent='STEP 1';drop.insertBefore(tag,drop.firstChild);
 st=document.createElement('div');st.id='ss-ff-st';st.innerHTML='<ul></ul>';
 body.insertBefore(drop,body.firstChild);if(files)body.insertBefore(files,drop.nextSibling);body.insertBefore(st,(files||drop).nextSibling);
 var zf=document.getElementById('ss-zf');if(zf)body.insertBefore(zf,st.nextSibling);
 /* four cards (owner 19.11: «текст с названием — в самом конце, фильтры компактнее, для своей обложки — нормальная кнопка»):
    1 files → 2 about the sound (compact) → 3 cover & price → 4 name & description (AI writes them from 1–3). For everyone. */
 if(!/[?&]ss_ff4=0/.test(location.search)){
 function card(c,num,t,sub){var d=document.createElement('div');d.className='ss-ff-card '+c;d.innerHTML='<div class="ss-ff-h"><i>'+num+'</i><div><b></b><small></small></div></div>';d.querySelector('b').textContent=t;d.querySelector('small').textContent=sub;return d}
 var c1=card('ss-ff-c1','1','Your files','Drop the project ZIP, the full track (WAV) or your MP3 preview, and a cover. We read them and fill in the rest.'),
     c2=card('ss-ff-c2','2','About the sound','Filled in from your files — check it. No files yet? Choose the type yourself.'),
     c3=card('ss-ff-c3','3','Cover & price','Upload your own cover or let AI make one. Square, at least 1000×1000.'),
     c4=card('ss-ff-c4','4','Name & description','Written by AI from your files and the details above — change anything you like.');
 body.insertBefore(c1,body.firstChild);body.insertBefore(c2,c1.nextSibling);body.insertBefore(c3,c2.nextSibling);body.insertBefore(c4,c3.nextSibling);
 [drop,files,st,zf,document.getElementById('ss-az')].forEach(function(x){if(x)c1.appendChild(x)});
 function P(sel){var e=q(sel);return e?e.closest('p')||e:null}
 var grid=document.createElement('div');grid.className='ss-ff-grid';
 [F('format'),F('daw'),F('version')].forEach(function(x){if(x)c2.appendChild(x)});
 c2.appendChild(grid);[q('.ss-wm-row'),q('.ss-wm-row2')].forEach(function(r){if(r)grid.appendChild(r)}); /* rows stay (other scripts look for them), the grid lays out their fields */
 [F('genre'),F('mood'),F('tag')].forEach(function(x){if(x)c2.appendChild(x)});
 /* card 3: a real «Upload your cover» button (the image goes through the same drop-zone route) + the AI cover button + preview + price */
 var cv=document.createElement('div');cv.className='ss-ff-cov';cv.innerHTML='<div class="pv" aria-live="polite"><span>No cover yet</span></div><div class="bt"><button type="button" class="ss-ff-up">Upload your cover</button></div>';
 var pick=document.createElement('input');pick.type='file';pick.accept='image/jpeg,image/png,image/webp';pick.hidden=true;cv.appendChild(pick);c3.appendChild(cv);
 cv.querySelector('.ss-ff-up').onclick=function(){pick.click()};
 function prev(f){if(!f||!/^image\//.test(f.type))return;var pv=cv.querySelector('.pv');pv.innerHTML='';var im=new Image();im.alt='Cover';im.src=URL.createObjectURL(f);pv.appendChild(im);cv.querySelector('.ss-ff-up').textContent='Change cover'}
 pick.onchange=function(){var f=pick.files&&pick.files[0],mi=q('input[type=file][multiple]');if(!f)return;prev(f);
  if(mi&&window.DataTransfer){var dt=new DataTransfer();dt.items.add(f);mi.files=dt.files;mi.dispatchEvent(new Event('change',{bubbles:true}))}else{var pi=q('[name=product_image]');if(pi&&window.DataTransfer){var d2=new DataTransfer();d2.items.add(f);pi.files=d2.files;pi.dispatchEvent(new Event('change',{bubbles:true}))}}};
 form.addEventListener('change',function(e){var t=e.target;if(t&&t.name==='product_image'&&t.files&&t.files[0])prev(t.files[0])},true);
 form.addEventListener('drop',function(e){var fl=e.dataTransfer&&e.dataTransfer.files;[].forEach.call(fl||[],function(f){if(/^image\//.test(f.type))prev(f)})},true);
 (function(){var mi=q('input[type=file][multiple]');if(mi)mi.addEventListener('change',function(){[].forEach.call(mi.files||[],function(f){if(/^image\//.test(f.type))prev(f)})})})();
 var pp=P('[name=product_price]');if(pp)c3.appendChild(pp);
 [q('.ss-wm-ai'),P('[name=product_name]'),P('[name=product_description]')].forEach(function(x){if(x)c4.appendChild(x)});
 /* the AI cover button and its box are added by another script: bring them next to «Upload your cover» */
 var tries=0;(function cov(){var b=qa('.ss-aic-b').filter(function(x){return /cover/i.test(x.textContent)})[0],bx=q('.ss-aic-cv');if(b&&bx){cv.querySelector('.bt').appendChild(b);cv.parentNode.insertBefore(bx,cv.nextSibling);return}if(++tries<25)setTimeout(cov,300)})();
 /* an AI cover that gets chosen lands in the product image: show it too */
 new MutationObserver(function(){var im=q('.ss-aic-cv .on img,.ss-aic-cv img.sel');if(im){var pv=cv.querySelector('.pv');if(!pv.querySelector('img')||pv.querySelector('img').src!==im.src){pv.innerHTML='';var c=im.cloneNode();pv.appendChild(c)}}}).observe(c3,{subtree:true,attributes:true,attributeFilter:['class']});
 }
 var hint=q('.ss-ds-first');if(hint)hint.textContent='Step 2: check what you are selling (we pick it from your files).';
 /* grab files before the drop zone routes them (capture phase) */
 function seen(list){[].forEach.call(list||[],function(f){var n=f.name.toLowerCase();if(f._ssGen||/-preview\.mp3$/i.test(f.name)&&full)return;if(/\.mp3$/.test(n)||/^audio\//.test(f.type)&&!/\.zip$/.test(n)){window.__ssFFmp3=1;done.mp3=false;aiRan=false;readMp3(f)}else if(/\.(zip|rar|7z)$/.test(n)){lastZipName=f.name;done.zip=false;aiRan=false;line('zip','wt','Reading '+f.name+'…');if(!/\.zip$/.test(n)){line('zip','no','RAR/7z cannot be read here — fill DAW and version yourself.');done.zip=true;ai()}else{setTimeout(function(){if(!done.zip){done.zip=true;line('zip','no','Could not read the ZIP here — fill DAW and version yourself.');ai()}},20000)}}else if(/^image\//.test(f.type)){line('cover','ok','Cover: '+f.name);var im=new Image();im.onload=function(){var w=im.naturalWidth,h=im.naturalHeight;chk('cov',w>=1000&&Math.abs(w-h)<=w*0.02,w>=1000&&Math.abs(w-h)<=w*0.02?'Cover '+w+'×'+h+' — good.':'Cover is '+w+'×'+h+' — use a square image, at least 1000×1000, so it looks sharp in the shop.')};im.src=URL.createObjectURL(f)}})}
 var multi=q('input[type=file][multiple]');
 function isFull(f){return /\.(wav|aiff?|flac)$/i.test(f.name)}
 function reroute(list,e){var full=[].filter.call(list,isFull);if(!full.length||!multi)return false;e.stopPropagation();e.preventDefault();var rest=[].filter.call(list,function(f){return !isFull(f)});
  if(rest.length){var dt=new DataTransfer();rest.forEach(function(f){dt.items.add(f)});multi._ssff=1;multi.files=dt.files;multi.dispatchEvent(new Event('change',{bubbles:true}));multi._ssff=0}
  makePreview(full[0],0);return true}
 document.addEventListener('change',function(e){var t=e.target;if(!t||t.type!=='file'||!form.contains(t)||t.name==='product_file'||t.name==='product_image')return;if(t._ssff){seen(t.files);return}if(!reroute(t.files,e))seen(t.files)},true);
 document.addEventListener('drop',function(e){if(!e.target.closest||!e.target.closest('.ss-wm-drop')||!e.dataTransfer)return;if(!reroute(e.dataTransfer.files,e))seen(e.dataTransfer.files);else drop.classList.remove('over')},true);
 return true}
if(!init()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(init())mo.disconnect()})});mo.observe(document.documentElement,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},15000)}
})();</script>
	<?php
}, 70 );
