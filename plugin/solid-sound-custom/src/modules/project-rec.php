<?php
/**
 * «Record your project» — promo video of the real project (owner 19.11: «надо проект снимать»).
 * Creator → Products: choose the product, press Record, share the DAW window/screen (with system audio), play the project.
 * The browser turns it into a vertical 9:16 video (1080×1920): product name on top, the project in the middle
 * (zoom + drag to pick the part of the screen), cover + solid-sound.net at the bottom. Up to 60 s. Nothing is uploaded:
 * the file is made in the creator's browser and downloaded. Desktop browsers only (screen capture). Texts in English.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() || ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return;
	global $wp; if ( ! array_key_exists( 'wcfm-ss-products', (array) $wp->query_vars ) ) return;
	$ids = get_posts( array( 'post_type' => 'product', 'post_status' => array( 'publish', 'pending', 'draft' ), 'author' => get_current_user_id(), 'numberposts' => 100, 'fields' => 'ids', 'orderby' => 'date', 'order' => 'DESC' ) );
	$P = array();
	foreach ( $ids as $id ) {
		$g = wp_get_post_terms( $id, 'product-genre', array( 'fields' => 'names' ) );
		$P[] = array( 'id' => $id, 't' => html_entity_decode( get_the_title( $id ) ), 'c' => (string) get_the_post_thumbnail_url( $id, 'woocommerce_single' ), 'g' => is_array( $g ) && $g ? $g[0] : '' );
	}
	?>
<style id="ss-pr-css">.ss-pr{margin:18px 0;padding:18px;border-radius:16px;background:#0f1729;border:1px solid rgba(255,255,255,.08);font-family:"Exo 2",system-ui,sans-serif;color:#CBD5E1}
.ss-pr h3{margin:0 0 4px;color:#fff;font-size:19px;font-weight:800}.ss-pr>p{margin:0 0 12px;color:#94A3B8;font-size:14px;line-height:1.5}
.ss-pr .grid{display:grid;grid-template-columns:minmax(0,1fr) 270px;gap:18px;align-items:start}@media(max-width:760px){.ss-pr .grid{grid-template-columns:1fr}}
.ss-pr select,.ss-pr input[type=text]{width:100%;box-sizing:border-box;margin:0 0 10px;padding:10px 12px;border-radius:10px;border:1px solid rgba(255,255,255,.12);background:#0b1220;color:#fff;font:400 15px "Exo 2",sans-serif}
.ss-pr label{display:block;margin:0 0 4px;color:#94A3B8;font-size:12.5px;font-weight:600;text-transform:uppercase;letter-spacing:.05em}
.ss-pr .bt{display:flex;flex-wrap:wrap;gap:10px;margin:6px 0 10px}.ss-pr button{min-height:44px;padding:0 18px;border:0;border-radius:10px;background:#F59E0B;color:#111;font:700 15px "Exo 2",sans-serif;cursor:pointer}
.ss-pr button.o{background:transparent;border:1px solid #F59E0B;color:#F59E0B}.ss-pr button.rec{background:#EF4444;color:#fff}.ss-pr button[disabled]{opacity:.45;cursor:default}
.ss-pr canvas{width:270px;height:480px;border-radius:12px;background:#000;border:1px solid rgba(255,255,255,.1);cursor:grab;touch-action:none}
.ss-pr .msg{font-size:14px;line-height:1.5}.ss-pr .msg.er{color:#FCA5A5}.ss-pr .msg.ok{color:#34D399}.ss-pr ol{margin:0 0 10px 18px;padding:0;font-size:14px;line-height:1.6}
.ss-pr input[type=range]{width:100%;accent-color:#F59E0B}.ss-pr a.dl{display:inline-flex;align-items:center;min-height:44px;padding:0 18px;border-radius:10px;background:#34D399;color:#062;font-weight:800;text-decoration:none}</style>
<script id="ss-pr-js">(function(){var PR=<?php echo wp_json_encode( $P ); ?>;
function esc(s){return String(s).replace(/[&<>"]/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]})}
function put(){var host=document.querySelector('#wcfm-content .wcfm-collapse')||document.querySelector('#wcfm-content');if(!host||document.querySelector('.ss-pr'))return !!host;
 var s=document.createElement('section');s.className='ss-pr';
 if(!navigator.mediaDevices||!navigator.mediaDevices.getDisplayMedia||!window.MediaRecorder){s.innerHTML='<h3>Record your project</h3><p>Make a vertical promo video of your project playing in your DAW. Open this page on your computer (Chrome or Edge) to record.</p>';host.appendChild(s);return true}
 s.innerHTML='<h3>Record your project</h3><p>A vertical video (9:16) of your real project playing — for Reels, TikTok and Shorts. Buyers want to see the session, not just hear it.</p>'+
  '<div class="grid"><div><ol><li>Open the project in your DAW, set the view you want to show.</li><li>Press <b>Start</b>, choose the screen or window with the DAW and tick <b>Share system audio</b> (Chrome/Edge: share the entire screen for sound).</li><li>Zoom and drag the preview to the part that matters, press <b>Record</b>, play the project. Up to 60 s.</li></ol>'+
  '<label>Product</label><select class="pp">'+PR.map(function(p,i){return '<option value="'+i+'">'+esc(p.t)+'</option>'}).join('')+'<option value="-1">Other (type a title)</option></select>'+
  '<label>Title on the video</label><input type="text" class="tt" maxlength="40"><label>Line under the title</label><input type="text" class="st" maxlength="50">'+
  '<label>Zoom</label><input type="range" class="zm" min="1" max="3" step="0.05" value="1.4">'+
  '<div class="bt"><button type="button" class="go">Start</button><button type="button" class="rec" disabled>Record</button><button type="button" class="o stop" disabled>Stop</button></div><div class="msg"></div></div>'+
  '<canvas width="1080" height="1920" aria-label="Video preview"></canvas></div>';
 host.appendChild(s);
 var $=function(c){return s.querySelector(c)},cv=$('canvas'),cx=cv.getContext('2d'),vid=document.createElement('video'),stream=null,rec=null,chunks=[],cover=null,zoom=1.4,ox=0.5,oy=0.5,t0=0,raf=0,msg=$('.msg');
 vid.muted=true;vid.playsInline=true;
 function say(t,c){msg.className='msg'+(c?' '+c:'');msg.innerHTML=t}
 function fill(){var i=+$('.pp').value,p=PR[i];if(p){$('.tt').value=p.t.slice(0,40);$('.st').value=(p.g?p.g+' · ':'')+'Ableton Live project';cover=null;if(p.c){var im=new Image();im.onload=function(){cover=im};im.src=p.c}}else{$('.tt').value='';$('.st').value='Ableton Live project';cover=null}}
 $('.pp').onchange=fill;fill();$('.zm').oninput=function(){zoom=+this.value};
 /* drag the preview to choose which part of the screen is shown */
 var drag=null;cv.addEventListener('pointerdown',function(e){drag={x:e.clientX,y:e.clientY,ox:ox,oy:oy};cv.setPointerCapture(e.pointerId)});
 cv.addEventListener('pointermove',function(e){if(!drag)return;ox=Math.max(0,Math.min(1,drag.ox-(e.clientX-drag.x)/270/zoom));oy=Math.max(0,Math.min(1,drag.oy-(e.clientY-drag.y)/480/zoom))});
 cv.addEventListener('pointerup',function(){drag=null});
 function wrap(t,x,y,max,lh){var w=t.split(' '),line='',n=0;for(var i=0;i<w.length;i++){var tst=line+w[i]+' ';if(cx.measureText(tst).width>max&&line){cx.fillText(line.trim(),x,y+n*lh);line=w[i]+' ';n++}else line=tst}cx.fillText(line.trim(),x,y+n*lh);return n+1}
 function draw(){var W=1080,H=1920;cx.fillStyle='#0b1220';cx.fillRect(0,0,W,H);
  var g=cx.createLinearGradient(0,0,0,H);g.addColorStop(0,'rgba(245,158,11,.18)');g.addColorStop(.35,'rgba(11,18,32,0)');cx.fillStyle=g;cx.fillRect(0,0,W,H);
  cx.fillStyle='#fff';cx.textAlign='center';cx.font='800 76px "Exo 2",system-ui,sans-serif';var n=wrap($('.tt').value||' ',W/2,190,960,86);
  cx.fillStyle='#F59E0B';cx.font='600 40px "Exo 2",system-ui,sans-serif';cx.fillText($('.st').value||'',W/2,190+n*86+10);
  /* the project: area 1080×1080 in the middle, cropped from the screen by zoom + drag */
  var AY=420,AH=1080;
  if(vid.videoWidth){var vw=vid.videoWidth,vh=vid.videoHeight,cw=vw/zoom,ch=cw*AH/W;if(ch>vh){ch=vh;cw=ch*W/AH}var sx=(vw-cw)*ox,sy=(vh-ch)*oy;cx.drawImage(vid,sx,sy,cw,ch,0,AY,W,AH)}
  else{cx.fillStyle='#111827';cx.fillRect(0,AY,W,AH);cx.fillStyle='#64748B';cx.font='500 42px "Exo 2",system-ui,sans-serif';cx.fillText('Your project appears here',W/2,AY+AH/2)}
  cx.strokeStyle='rgba(245,158,11,.6)';cx.lineWidth=4;cx.strokeRect(2,AY,W-4,AH);
  /* bottom: cover + where to get it */
  var by=AY+AH+70;if(cover){cx.drawImage(cover,80,by,230,230)}
  cx.textAlign='left';cx.fillStyle='#fff';cx.font='800 50px "Exo 2",system-ui,sans-serif';cx.fillText('Get the project',cover?350:80,by+80);
  cx.fillStyle='#F59E0B';cx.font='700 46px "Exo 2",system-ui,sans-serif';cx.fillText('solid-sound.net',cover?350:80,by+150);
  if(rec&&rec.state==='recording'){var sec=Math.floor((performance.now()-t0)/1000);cx.fillStyle='#EF4444';cx.beginPath();cx.arc(990,80,16,0,7);cx.fill();cx.fillStyle='#fff';cx.font='700 34px system-ui';cx.textAlign='right';cx.fillText(sec+' s',960,92);if(sec>=60)stop()}
  raf=requestAnimationFrame(draw)}
 draw();
 $('.go').onclick=function(){navigator.mediaDevices.getDisplayMedia({video:{frameRate:30},audio:{echoCancellation:false,noiseSuppression:false,autoGainControl:false}}).then(function(st){stream=st;vid.srcObject=st;vid.play();
  var a=st.getAudioTracks().length;say(a?'Screen shared with sound. Pick the part to show, then press <b>Record</b>.':'Screen shared, but <b>no sound</b> — share the entire screen and tick “Share system audio”, or the video will be silent.',a?'ok':'er');
  $('.rec').disabled=false;st.getVideoTracks()[0].addEventListener('ended',function(){stop();$('.rec').disabled=true})}).catch(function(){say('Screen sharing was cancelled.','er')})};
 $('.rec').onclick=function(){if(!stream)return;chunks=[];var out=cv.captureStream(30);stream.getAudioTracks().forEach(function(t){out.addTrack(t)});
  var types=['video/mp4;codecs=avc1.42E01E,mp4a.40.2','video/mp4','video/webm;codecs=vp9,opus','video/webm'],type=types.filter(function(t){return MediaRecorder.isTypeSupported(t)})[0]||'';
  rec=new MediaRecorder(out,{mimeType:type,videoBitsPerSecond:8e6});rec.ondataavailable=function(e){if(e.data.size)chunks.push(e.data)};
  rec.onstop=function(){var mt=(rec.mimeType||type||'video/webm').split(';')[0],b=new Blob(chunks,{type:mt}),u=URL.createObjectURL(b),ext=/mp4/.test(mt)?'mp4':'webm',nm=($('.tt').value||'project').replace(/[^a-z0-9]+/gi,'-').toLowerCase()+'-solid-sound.'+ext;
   say('Done! <a class="dl" href="'+u+'" download="'+nm+'">Download the video</a>'+(ext==='webm'?' <br>WebM: Instagram and TikTok take it on the web; for the phone apps convert to MP4 if needed.':''),'ok')};
  rec.start(1000);t0=performance.now();$('.rec').disabled=true;$('.stop').disabled=false;say('Recording… play your project. Stop any time (max 60 s).')};
 function stop(){if(rec&&rec.state==='recording'){rec.stop();$('.stop').disabled=true;$('.rec').disabled=!stream}}
 $('.stop').onclick=stop;
 return true}
if(!put()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(put())mo.disconnect()})});mo.observe(document.body,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},15000)}
})();</script>
	<?php
}, 72 );
