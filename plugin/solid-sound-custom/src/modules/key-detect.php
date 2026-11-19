<?php
/**
 * Add Product: key from the audio, with our own analyser (src/audio/ss-audio.js — owner 19.11.2026 «разработай наш Essentia.js»).
 * When the creator drops an MP3/WAV, the browser decodes it (nothing is uploaded for this) and SS Audio finds the key.
 *  - the Ableton project's scale always wins: if the Key field is already filled, we only show what we heard;
 *  - SS Audio lets 10 methods vote; from 50 % of the votes the key is filled in (owner 19.11: «автоматом, автор заменит»), marked "from the audio — check";
 *  - fairly sure (≥ 50 % of the votes): a hint under the field «Sounds like Am (or C) — Use Am»;
 *  - not sure: nothing (a wrong key is worse than none).
 * Customer-facing texts in English.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() || ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return;
	global $wp; if ( ! array_key_exists( 'wcfm-ss-add-product', (array) $wp->query_vars ) ) return;
	if ( ! function_exists( 'ss_asset' ) || ! ss_asset_has( 'ss-audio' ) ) return;
	ss_asset( 'ss-audio', 'defer' );
	?>
<style id="ss-kd-css">#ss-kd{margin:8px 0 0;padding:10px 12px;border-radius:10px;border:1px solid rgba(245,158,11,.35);background:rgba(245,158,11,.07);color:#E2E8F0;font:400 14px/1.45 "Exo 2",sans-serif}
#ss-kd b{color:#fff}#ss-kd small{display:block;margin-top:2px;color:#94A3B8;font-size:12.5px}#ss-kd.wt:before{content:"";display:inline-block;width:11px;height:11px;margin-right:7px;border:2px solid #334155;border-top-color:#F59E0B;border-radius:50%;animation:sskd 1s linear infinite;vertical-align:-1px}
#ss-kd button{margin:6px 8px 0 0;padding:5px 12px;border-radius:8px;border:1px solid #F59E0B;background:#F59E0B;color:#111;font:700 13px "Exo 2",sans-serif;cursor:pointer}#ss-kd button.o{background:transparent;color:#F59E0B}@keyframes sskd{to{transform:rotate(360deg)}}</style>
<script id="ss-kd-js">(function(){
var form,busy=0,last=null;
function box(){var w=form.querySelector('.ss-ap-key');if(!w)return null;var b=document.getElementById('ss-kd');if(!b){b=document.createElement('div');b.id='ss-kd';b.setAttribute('aria-live','polite');(w.querySelector('.acf-input')||w).appendChild(b)}return b}
function field(){var w=form.querySelector('.ss-ap-key');if(!w)return null;return w.querySelector('select')||w.querySelector('input[type=text]')||w.querySelector('input:not([type=hidden])')}
function cur(){var f=field();if(!f)return '';if(f.tagName==='SELECT')return f.value&&f.selectedIndex>0?f.options[f.selectedIndex].text:'';return f.value.trim()}
/* the site's key list may say "A minor", "Am" or "A Minor": match any of them */
function variants(k){var m=/m$/.test(k),t=k.replace(/m$/,''),fl={'C#':'Db','D#':'Eb','F#':'Gb','G#':'Ab','A#':'Bb'}[t];var o=[];[t,fl].forEach(function(x){if(!x)return;o.push(x+(m?'m':''),x+(m?' minor':' major'),x+(m?' Minor':' Major'),x+(m?'min':'maj'))});if(!m)o.push(t);return o.map(function(s){return s.toLowerCase()})}
function setKey(k){var f=field();if(!f)return false;var v=variants(k);if(f.tagName==='SELECT'){for(var i=0;i<f.options.length;i++)if(v.indexOf(f.options[i].text.trim().toLowerCase())>-1){f.selectedIndex=i;f.dispatchEvent(new Event('change',{bubbles:true}));return true}return false}f.value=k;f.dispatchEvent(new Event('input',{bubbles:true}));f.dispatchEvent(new Event('change',{bubbles:true}));return true}
function show(r){var b=box();if(!b)return;b.className='';var have=cur();
 if(!r||r.confidence<0.5){b.innerHTML='<b>Key:</b> the audio does not make the key clear — please choose it.';return}
 var alt=r.alt_close<0.3?' <small>Could also be '+r.alt+' (same notes) — trust your ears.</small>':'';
 if(have){b.innerHTML='<b>From your audio:</b> sounds like '+r.key+'.'+(variants(r.key).indexOf(have.toLowerCase())>-1?' Matches your project ✓':' Your project says '+have+' — keep it if you are sure.')+alt;return}
 if(setKey(r.key)){b.innerHTML='<b>Key '+r.key+'</b> — filled in from your audio'+(r.confidence<0.8?' (fairly sure)':'')+'. Wrong? Just change it.'+alt+(r.alt_close<0.3?'<br><button type="button" class="o" data-k="'+r.alt+'">Use '+r.alt+'</button>':'');return}
 b.innerHTML='<b>Sounds like '+r.key+'</b> — please check.'+alt+'<br><button type="button" data-k="'+r.key+'">Use '+r.key+'</button>'+(r.alt_close<0.3?'<button type="button" class="o" data-k="'+r.alt+'">Use '+r.alt+'</button>':'')}
function run(f){if(!window.SSAudio||!window.AudioContext&&!window.webkitAudioContext)return;if(f===last)return;last=f;var b=box();if(!b)return;b.className='wt';b.textContent='Listening for the key…';busy++;
 f.arrayBuffer().then(function(ab){var C=window.AudioContext||window.webkitAudioContext,ctx=new C();return ctx.decodeAudioData(ab).then(function(buf){try{ctx.close()}catch(e){}return buf})}).then(function(buf){
  /* the middle 3 minutes are enough and keep the phone responsive */
  var x=SSAudio.mono(buf),sr=buf.sampleRate,max=180*sr;if(x.length>max){var s=Math.floor((x.length-max)/2);x=x.subarray(s,s+max)}
  setTimeout(function(){var r=null;try{r=SSAudio.key(x,sr)}catch(e){}busy--;show(r)},30)}).catch(function(){busy--;var b2=box();if(b2){b2.className='';b2.textContent=''}})}
function pick(list){var a=null;[].forEach.call(list||[],function(f){var n=(f.name||'').toLowerCase();if(/-preview\.mp3$/.test(n))return;if(/\.(wav|aiff?|flac)$/.test(n))a=f;else if(!a&&/\.(mp3|m4a|ogg)$/.test(n))a=f});if(a)run(a)} /* the full WAV is better than the preview */
function init(){form=document.getElementById('solid-sound-upload-form');if(!form||form.dataset.kd)return !!form;form.dataset.kd=1;
 form.addEventListener('change',function(e){if(e.target&&e.target.type==='file')pick(e.target.files)},true);
 form.addEventListener('drop',function(e){if(e.dataTransfer)pick(e.dataTransfer.files)},true);
 form.addEventListener('click',function(e){var k=e.target.closest&&e.target.closest('#ss-kd button[data-k]');if(!k)return;e.preventDefault();var key=k.getAttribute('data-k');var b=box();if(setKey(key)&&b)b.innerHTML='<b>Key '+key+'</b> — set from your audio ✓'});return true}
if(!init()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(init())mo.disconnect()})});mo.observe(document.body,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},20000)}
})();</script>
	<?php
}, 60 );
