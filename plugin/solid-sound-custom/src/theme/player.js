
(function(){
'use strict';
try{
/* ============================================================
   Solid Sound Player (SSP) v5
   - one global audio engine + bottom player bar (queue, volume, next/prev)
   - card waveforms (own canvas renderer, precomputed peaks from server)
   - AJAX navigation while the player is open (music keeps playing)
   - likes / plays / Best Sellers tabs / compact filters / tidy cards
   ============================================================ */
var RT=window.SS_RT||{p:{}};
var A36='0123456789abcdefghijklmnopqrstuvwxyz';
var ICON={
  play:'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>',
  pause:'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 5h4v14H6zM14 5h4v14h-4z"/></svg>',
  prev:'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6h2v12H6zm3.5 6 8.5 6V6z"/></svg>',
  next:'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 6h2v12h-2zM6 18l8.5-6L6 6z"/></svg>',
  vol:'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3A4.5 4.5 0 0 0 14 7.97v8.05A4.47 4.47 0 0 0 16.5 12zM14 3.23v2.06a7 7 0 0 1 0 13.42v2.06A9 9 0 0 0 14 3.23z"/></svg>',
  mute:'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 9v6h4l5 5V4L7 9H3zm13.59 3L19 9.59 17.59 8.17 15.17 10.6 12.76 8.17 11.34 9.59 13.76 12l-2.42 2.41 1.42 1.42 2.41-2.42 2.42 2.42L19 14.41z"/></svg>',
  queue:'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h13v2H3zm0 5h13v2H3zm0 5h9v2H3zm15-1V8h3v2h-1v7a2.5 2.5 0 1 1-2-2.45z"/></svg>',
  close:'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.3 5.71 12 12l6.3 6.29-1.41 1.42L10.59 13.4 4.3 19.71 2.88 18.3 9.17 12 2.88 5.71 4.3 4.29l6.29 6.3 6.3-6.3z"/></svg>',
  heart:'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7.5-4.6-10-9.3C.4 8.4 2.3 4.5 6 4.1c2-.2 3.6.8 6 3.1 2.4-2.3 4-3.3 6-3.1 3.7.4 5.6 4.3 4 7.6C19.5 16.4 12 21 12 21z"/></svg>'
};

/* ---------- utils ---------- */
function ls(k,v){try{if(v===undefined)return localStorage.getItem(k);if(v===null)localStorage.removeItem(k);else localStorage.setItem(k,v)}catch(e){return null}}
function ss(k,v){try{if(v===undefined)return sessionStorage.getItem(k);if(v===null)sessionStorage.removeItem(k);else sessionStorage.setItem(k,v)}catch(e){return null}}
function fmt(s){s=Math.max(0,Math.floor(s||0));return Math.floor(s/60)+':'+('0'+s%60).slice(-2)}
function fileOf(u){try{return decodeURIComponent(new URL(u,location.href).pathname.split('/').pop())}catch(e){return ''}}
function decodePeaks(p){if(!p)return null;var a=[];for(var i=0;i<p.length;i++)a.push(Math.max(0,A36.indexOf(p[i]))/35);return a}
function encodePeaks(arr){return arr.map(function(v){return A36[Math.max(1,Math.round(Math.max(0,Math.min(1,v))*35))]}).join('')}
function esc(s){return String(s==null?'':s).replace(/[&<>"']/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]})}
function el(tag,cls,html){var e=document.createElement(tag);if(cls)e.className=cls;if(html!=null)e.innerHTML=html;return e}
function pidOfFile(f){if(!f)return '';for(var k in RT.p){if(RT.p[k].m===f)return k}return ''}
function pidOfCard(card){if(!card)return '';var m=String(card.className).match(/\bpost-(\d+)\b/);return m&&RT.p[m[1]]?m[1]:''}
var localPeaks={};

/* ---------- tracks ---------- */
function trackFromPid(pid){var d=RT.p[pid];if(!d||!d.s)return null;return {pid:String(pid),src:d.s,t:d.t||'',a:d.a||'Solid Sound',i:d.i||'',u:d.u||'',w:d.w||'',d:+d.d||0}}
function trackFor(src,ctx){
  var pid=pidOfFile(fileOf(src))||pidOfCard(ctx&&ctx.closest&&ctx.closest('.w-grid-item'));
  var t=pid?trackFromPid(pid):null;
  if(t&&fileOf(t.src)===fileOf(src))return t;
  var title='';var card=ctx&&ctx.closest&&ctx.closest('.w-grid-item, .product, article');
  if(card){var h=card.querySelector('h1,h2,h3,.post_title');if(h)title=h.textContent.trim()}
  return {pid:pid||'',src:src,t:title||fileOf(src).replace(/\.mp3$/i,'').replace(/[_-]+/g,' '),a:'Solid Sound',i:'',u:location.href,w:'',d:0};
}
function peaksOf(t){return t.w?decodePeaks(t.w):(localPeaks[fileOf(t.src)]||null)}

/* ---------- canvas waveform ---------- */
function Wave(host,opt){
  this.host=host;this.peaks=opt.peaks||null;this.p=0;this.onSeek=opt.onSeek;
  this.c=el('canvas','ssw-cv');host.appendChild(this.c);this.ctx=this.c.getContext('2d');
  var self=this;
  if(window.ResizeObserver){this.ro=new ResizeObserver(function(){self.draw()});this.ro.observe(host)}
  host.addEventListener('click',function(e){var r=host.getBoundingClientRect();if(!r.width)return;var x=Math.min(1,Math.max(0,(e.clientX-r.left)/r.width));self.onSeek&&self.onSeek(x)});
  this.draw();
}
Wave.prototype.setPeaks=function(p){this.peaks=p;this.draw()};
Wave.prototype.setProgress=function(p){p=isFinite(p)?Math.max(0,Math.min(1,p)):0;if(Math.abs(p-this.p)<0.0015)return;this.p=p;this.draw()};
Wave.prototype.draw=function(){
  var w=this.host.clientWidth,h=this.host.clientHeight;if(!w||!h)return;
  var dpr=Math.min(window.devicePixelRatio||1,2),W=Math.round(w*dpr),H=Math.round(h*dpr);
  if(this.c.width!==W||this.c.height!==H){this.c.width=W;this.c.height=H;this.c.style.width=w+'px';this.c.style.height=h+'px'}
  var ctx=this.ctx;ctx.setTransform(dpr,0,0,dpr,0,0);ctx.clearRect(0,0,w,h);
  var bw=2,step=4,n=Math.max(1,Math.floor((w+2)/step)),pk=this.peaks,px=this.p*w;
  for(var i=0;i<n;i++){
    var v=0.12;
    if(pk&&pk.length){var a=Math.floor(i*pk.length/n),b=Math.max(a+1,Math.floor((i+1)*pk.length/n));v=0;for(var j=a;j<b&&j<pk.length;j++)if(pk[j]>v)v=pk[j]}
    var bh=Math.max(2,Math.round(v*h)),x=i*step;
    ctx.fillStyle=(x+bw/2<=px)?'#e09900':(pk?'#5a5a5a':'#3a3a3a');
    ctx.fillRect(x,Math.round((h-bh)/2),bw,bh);
  }
  if(this.p>0&&this.p<1){ctx.fillStyle='#f5ad12';ctx.fillRect(Math.min(w-1,Math.round(px)),0,1,h)}
};

/* compute peaks in the browser for tracks without stored waveform */
var computing={};
function computePeaks(t,cb){
  var f=fileOf(t.src);if(localPeaks[f])return cb(localPeaks[f]);
  if(computing[f]){computing[f].push(cb);return}computing[f]=[cb];
  var AC=window.OfflineAudioContext||window.webkitOfflineAudioContext;if(!AC||!window.fetch)return;
  fetch(t.src).then(function(r){return r.arrayBuffer()}).then(function(buf){
    var ctx=new AC(1,1,44100);
    return new Promise(function(res,rej){var p=ctx.decodeAudioData(buf,res,rej);if(p&&p.then)p.then(res,rej)});
  }).then(function(a){
    var N=200,chs=[],k;for(k=0;k<a.numberOfChannels;k++)chs.push(a.getChannelData(k));
    var len=a.length,step=len/N,r=[],mx=0;
    for(var i=0;i<N;i++){var s=0,n=0,b=Math.floor(i*step),e=Math.floor((i+1)*step);for(var j=b;j<e;j+=4){for(k=0;k<chs.length;k++){s+=chs[k][j]*chs[k][j];n++}}var v=n?Math.sqrt(s/n):0;r.push(v);if(v>mx)mx=v}
    var out=r.map(function(v){return Math.pow(v/(mx||1),1.6)});
    var enc=encodePeaks(out);localPeaks[f]=decodePeaks(enc);
    if(t.pid&&RT.p[t.pid]){RT.p[t.pid].w=enc;RT.p[t.pid].d=Math.round(a.duration*10)/10}
    if(RT.n&&t.pid){post('ss_rt_peaks',{pid:t.pid,peaks:enc,dur:Math.round(a.duration*10)/10,file:f,nonce:RT.n}).catch(function(){})}
    (computing[f]||[]).forEach(function(fn){try{fn(localPeaks[f],a.duration)}catch(e){}});delete computing[f];
  }).catch(function(){delete computing[f]});
}

/* ---------- server calls ---------- */
var vid=ls('ss_vid');if(!vid){vid=(Math.random().toString(36).slice(2)+Date.now().toString(36)).replace(/[^a-z0-9]/g,'');ls('ss_vid',vid)}
function post(action,data){var fd=new FormData();fd.append('action',action);fd.append('vid',vid);for(var k in data)fd.append(k,data[k]);return fetch(RT.ajax||'/wp-admin/admin-ajax.php',{method:'POST',body:fd,credentials:'same-origin'}).then(function(r){return r.json()})}
var counted={};
function countPlay(pid){if(!pid||counted[pid])return;counted[pid]=1;post('ss_rt_play',{pid:pid}).catch(function(){})}

/* ---------- likes ---------- */
var liked=[];try{liked=JSON.parse(ls('ss_liked')||'[]').map(String)}catch(e){}
function likesOf(pid){return (RT.p[pid]||{}).l||0}
function paintLike(pid,on,count){
  document.querySelectorAll('.ss-like[data-pid="'+pid+'"]').forEach(function(b){b.classList.toggle('is-liked',on)});
  document.querySelectorAll('[data-likes="'+pid+'"]').forEach(function(s){s.textContent=count||''});
  if(RT.p[pid])RT.p[pid].l=count;
}
function toggleLike(pid){
  pid=String(pid);var was=liked.indexOf(pid)>-1,guess=Math.max(0,likesOf(pid)+(was?-1:1));
  if(was)liked.splice(liked.indexOf(pid),1);else liked.push(pid);ls('ss_liked',JSON.stringify(liked));
  paintLike(pid,!was,guess);
  document.querySelectorAll('.ss-like[data-pid="'+pid+'"]').forEach(function(b){b.classList.add('pop');setTimeout(function(){b.classList.remove('pop')},200)});
  post('ss_rt_like',{pid:pid}).then(function(r){if(!r||!r.success)return;var on=!!r.data.liked,i=liked.indexOf(pid);if(on&&i<0)liked.push(pid);if(!on&&i>-1)liked.splice(i,1);ls('ss_liked',JSON.stringify(liked));paintLike(pid,on,r.data.likes)}).catch(function(){});
}
function likeBtn(pid){var b=el('button','ss-like',ICON.heart+'<span data-likes="'+pid+'">'+(likesOf(pid)||'')+'</span>');b.type='button';b.dataset.pid=pid;b.setAttribute('aria-label','Like');b.title='Like';if(liked.indexOf(String(pid))>-1)b.classList.add('is-liked');return b}
document.addEventListener('click',function(e){var b=e.target.closest&&e.target.closest('.ss-like[data-pid]');if(!b)return;e.preventDefault();e.stopPropagation();toggleLike(b.dataset.pid)});

/* ============================================================
   PLAYER ENGINE
   ============================================================ */
var audio=new Audio();audio.preload='none';
var P={queue:[],idx:-1,cur:null,pendingSeek:null};
var vol=parseFloat(ls('ssp_vol'));if(!(vol>=0&&vol<=1))vol=0.9;audio.volume=vol;
var muted=ls('ssp_mute')==='1';audio.muted=muted;

function durOf(){var d=audio.duration;return (isFinite(d)&&d>0)?d:((P.cur&&P.cur.d)||0)}
function isCur(src){return P.cur&&fileOf(P.cur.src)===fileOf(src)}
function load(t,queue,opts){
  opts=opts||{};
  queue=(queue&&queue.length)?queue:[t];
  var idx=-1;for(var i=0;i<queue.length;i++){if(fileOf(queue[i].src)===fileOf(t.src)){idx=i;break}}
  if(idx<0){queue=[t].concat(queue);idx=0}
  P.queue=queue;P.idx=idx;P.cur=queue[idx];P.pendingSeek=null;
  audio.src=P.cur.src;
  if(opts.at!=null)P.pendingSeek=opts.at;
  Bar.show();Bar.render();syncAll();media();save();
}
function play(){if(!P.cur)return;setLoading(true);var p=audio.play();if(p&&p.catch)p.catch(function(){setLoading(false);Bar.el.classList.add('ssp-resume')})}
function pause(){audio.pause()}
function toggle(){if(!P.cur)return;if(audio.paused)play();else pause()}
function pageNext(){if(!P.cur)return null;var pq=pageQueue();for(var i=0;i<pq.length-1;i++){if(fileOf(pq[i].src)===fileOf(P.cur.src)){var n=pq[i+1];for(var k=0;k<P.queue.length;k++){if(fileOf(P.queue[k].src)===fileOf(n.src))return null}return n}}return null}
function hasNext(){return P.idx<P.queue.length-1||!!pageNext()}
function next(){if(P.idx<P.queue.length-1){load(P.queue[P.idx+1],P.queue);play();return}var n=pageNext();if(n){var q=P.queue.slice();q.push(n);load(n,q);play()}}
function prev(){if(audio.currentTime>3||P.idx<=0){audio.currentTime=0;return}load(P.queue[P.idx-1],P.queue);play()}
function seekFrac(f){var d=durOf();if(audio.readyState>=1&&isFinite(audio.duration)){audio.currentTime=f*audio.duration}else{P.pendingSeek=f*(d||0)}tick(true)}
function rmQ(i){if(i<0||i>=P.queue.length||i===P.idx)return;P.queue.splice(i,1);if(i<P.idx)P.idx--;Bar.render();Bar.renderQueue();save()}
function qToast(msg){var t=document.querySelector('.ssp-toast');if(!t){t=el('div','ssp-toast');t.setAttribute('role','status');document.body.appendChild(t)}t.textContent=msg;t.classList.add('is-on');clearTimeout(t._h);t._h=setTimeout(function(){t.classList.remove('is-on')},1800);var q=document.querySelector('.ssp-q');if(q){q.classList.remove('ssp-bump');void q.offsetWidth;q.classList.add('ssp-bump')}}
function addToQueue(t){if(!P.cur){load(t,[t]);qToast('Added to player');return true}for(var i=0;i<P.queue.length;i++){if(fileOf(P.queue[i].src)===fileOf(t.src)){qToast('Already in queue');return false}}P.queue.push(t);Bar.render();save();qToast('Added to queue \u00b7 '+P.queue.length+' tracks');return true}
function playTrack(t,queue,frac){
  if(P.cur&&isCur(t.src)){if(frac!=null){seekFrac(frac);if(audio.paused)play()}else toggle();return}
  if(!queue){queue=P.queue.slice();var ex=-1;for(var qi=0;qi<queue.length;qi++){if(fileOf(queue[qi].src)===fileOf(t.src)){ex=qi;break}}if(ex<0)queue.splice(P.idx+1,0,t)}
  load(t,queue,{at:frac!=null&&t.d?frac*t.d:null});
  if(frac!=null&&!t.d)P.pendingFrac=frac;
  play();
}
function setLoading(on){Bar.el&&Bar.el.classList.toggle('is-loading',on);document.querySelectorAll('.ssw').forEach(function(b){b.classList.toggle('is-loading',on&&isCur(b.dataset.src))});document.querySelectorAll('.ss-top-i').forEach(function(li){li.classList.toggle('is-loading',on&&isCur(li.dataset.src||''))})}
audio.addEventListener('loadedmetadata',function(){
  if(P.pendingFrac!=null&&isFinite(audio.duration)){audio.currentTime=P.pendingFrac*audio.duration;P.pendingFrac=null}
  else if(P.pendingSeek!=null){try{audio.currentTime=P.pendingSeek}catch(e){}P.pendingSeek=null}
  if(P.cur&&!P.cur.d)P.cur.d=audio.duration;Bar.render();
});
audio.addEventListener('playing',function(){setLoading(false)});
audio.addEventListener('waiting',function(){setLoading(true)});
audio.addEventListener('canplay',function(){setLoading(false)});
audio.addEventListener('play',function(){Bar.el.classList.remove('ssp-resume');syncAll();countPlay(P.cur&&P.cur.pid);save();if('mediaSession' in navigator)navigator.mediaSession.playbackState='playing'});
audio.addEventListener('pause',function(){setLoading(false);syncAll();save();if('mediaSession' in navigator)navigator.mediaSession.playbackState='paused'});
audio.addEventListener('ended',function(){var c=P.cur;next();if(P.cur===c){audio.currentTime=0;syncAll()}});
audio.addEventListener('timeupdate',function(){tick(false)});
audio.addEventListener('volumechange',function(){ls('ssp_vol',String(audio.volume));ls('ssp_mute',audio.muted?'1':'0');Bar.vol()});
var lastSave=0;
function tick(force){
  if(!P.cur)return;var d=durOf(),t=P.pendingSeek!=null?P.pendingSeek:audio.currentTime,f=d?t/d:0;
  Bar.progress(f,t,d);
  document.querySelectorAll('.ssw').forEach(function(b){if(isCur(b.dataset.src)){b._wave&&b._wave.setProgress(f);var c=b.querySelector('.ssw-cur');if(c)c.textContent=fmt(t)}});
  var now=Date.now();if(force||now-lastSave>1500){lastSave=now;save()}
}
function syncAll(){
  var playing=P.cur&&!audio.paused;
  document.querySelectorAll('.ssw').forEach(function(b){var on=isCur(b.dataset.src);b.classList.toggle('is-current',!!on);b.classList.toggle('is-playing',!!(on&&playing));if(on)b.classList.add('was-played');else if(b._wave&&b._wave.p){b._wave.setProgress(0);var c=b.querySelector('.ssw-cur');if(c)c.textContent='0:00'}var bt=b.querySelector('.ssw-btn');if(bt)bt.setAttribute('aria-label',on&&playing?'Pause':'Play')});
  document.querySelectorAll('.ss-top-i').forEach(function(li){li.classList.toggle('is-playing',!!(playing&&isCur(li.dataset.src||'')))});
  Bar.state();
}
function save(){
  if(!P.cur){ss('ssp_state',null);return}
  ss('ssp_state',JSON.stringify({q:P.queue,i:P.idx,t:audio.currentTime||P.pendingSeek||0,pl:!audio.paused}));
}
function media(){
  if(!('mediaSession' in navigator)||!P.cur||!window.MediaMetadata)return;
  try{
    navigator.mediaSession.metadata=new MediaMetadata({title:P.cur.t,artist:P.cur.a,album:'Solid Sound',artwork:P.cur.i?[{src:P.cur.i,sizes:'150x150',type:'image/jpeg'}]:[]});
    var h={play:play,pause:pause,previoustrack:prev,nexttrack:next,seekto:function(d){if(d&&d.seekTime!=null)audio.currentTime=d.seekTime}};
    for(var k in h){try{navigator.mediaSession.setActionHandler(k,h[k])}catch(e){}}
  }catch(e){}
}

/* ============================================================
   BOTTOM BAR
   ============================================================ */
var Bar={el:null,wave:null,
  build:function(){
    if(this.el)return;
    var b=el('div','ssp');b.id='ssp';b.setAttribute('role','region');b.setAttribute('aria-label','Audio player');
    b.innerHTML='<div class="ssp-mprog"><i></i></div>'+
      '<a class="ssp-cover" href="#"><img alt="" width="52" height="52"></a>'+
      '<div class="ssp-meta"><a class="ssp-title" href="#"></a><span class="ssp-author"></span></div>'+
      '<span class="ssp-like-slot"></span>'+
      '<div class="ssp-wv"><span class="ssp-cur">0:00</span><div class="ssp-wave"></div><span class="ssp-dur">0:00</span></div>'+
      '<div class="ssp-ctrl"><button type="button" class="ssp-b ssp-prev" aria-label="Previous">'+ICON.prev+'</button><button type="button" class="ssp-b ssp-play" aria-label="Play">'+ICON.play+ICON.pause+'</button><button type="button" class="ssp-b ssp-next" aria-label="Next">'+ICON.next+'</button></div>'+
      '<div class="ssp-vol"><button type="button" class="ssp-b ssp-mute" aria-label="Mute">'+ICON.vol+ICON.mute+'</button><input type="range" min="0" max="1" step="0.01" aria-label="Volume"></div>'+
      '<div class="ssp-q"><button type="button" class="ssp-b ssp-qbtn" aria-label="Queue" aria-expanded="false">'+ICON.queue+'<b class="ssp-qn">0</b></button><div class="ssp-ql" hidden><div class="ssp-qh">Up next</div><ol></ol></div></div>'+
      '<button type="button" class="ssp-b ssp-close" aria-label="Close player">'+ICON.close+'</button>';
    document.body.appendChild(b);this.el=b;
    var self=this;
    this.wave=new Wave(b.querySelector('.ssp-wave'),{onSeek:function(x){seekFrac(x);if(audio.paused)play()}});
    b.querySelector('.ssp-mprog').addEventListener('click',function(e){var r=this.getBoundingClientRect();seekFrac((e.clientX-r.left)/r.width)});
    b.querySelector('.ssp-play').addEventListener('click',toggle);
    b.querySelector('.ssp-next').addEventListener('click',next);
    b.querySelector('.ssp-prev').addEventListener('click',prev);
    b.querySelector('.ssp-mute').addEventListener('click',function(){audio.muted=!audio.muted});
    var rng=b.querySelector('.ssp-vol input');rng.addEventListener('input',function(){audio.volume=parseFloat(this.value);if(audio.muted&&audio.volume>0)audio.muted=false});
    var qb=b.querySelector('.ssp-qbtn'),ql=b.querySelector('.ssp-ql');
    qb.addEventListener('click',function(e){e.stopPropagation();var open=ql.hasAttribute('hidden');if(open){self.renderQueue();ql.removeAttribute('hidden')}else ql.setAttribute('hidden','');qb.setAttribute('aria-expanded',open?'true':'false')});
    document.addEventListener('click',function(e){if(!ql.hasAttribute('hidden')&&!e.target.closest('.ssp-q')){ql.setAttribute('hidden','');qb.setAttribute('aria-expanded','false')}});
    ql.addEventListener('click',function(e){var x=e.target.closest('.ssp-qx');if(x){e.stopPropagation();rmQ(+x.dataset.x);return}var li=e.target.closest('li[data-i]');if(!li)return;load(P.queue[+li.dataset.i],P.queue);play();self.renderQueue()});
    b.querySelector('.ssp-close').addEventListener('click',close);
    this.vol();
  },
  show:function(){this.build();this.el.classList.add('is-open');document.body.classList.add('ssp-open')},
  hide:function(){if(!this.el)return;this.el.classList.remove('is-open');document.body.classList.remove('ssp-open')},
  render:function(){
    if(!this.el||!P.cur)return;var t=P.cur,b=this.el;
    var img=b.querySelector('.ssp-cover img');if(t.i){img.src=t.i;img.style.visibility=''}else{img.removeAttribute('src');img.style.visibility='hidden'}
    b.querySelector('.ssp-cover').href=t.u||'#';
    var ti=b.querySelector('.ssp-title');ti.textContent=t.t;ti.href=t.u||'#';ti.title=t.t;
    b.querySelector('.ssp-author').textContent=t.a||'';
    var slot=b.querySelector('.ssp-like-slot');slot.innerHTML='';if(t.pid&&RT.p[t.pid])slot.appendChild(likeBtn(t.pid));
    var pk=peaksOf(t);this.wave.setPeaks(pk);
    if(!pk)computePeaks(t,function(p){if(P.cur&&fileOf(P.cur.src)===fileOf(t.src))Bar.wave.setPeaks(p);refreshCardPeaks(t)});
    b.querySelector('.ssp-dur').textContent=fmt(durOf());
    b.querySelector('.ssp-qn').textContent=P.queue.length;
    b.querySelector('.ssp-prev').disabled=false;b.querySelector('.ssp-next').disabled=!hasNext();
    this.state();tick(true);
    if(!b.querySelector('.ssp-ql').hasAttribute('hidden'))this.renderQueue();
  },
  renderQueue:function(){var ol=this.el.querySelector('.ssp-ql ol');ol.innerHTML=P.queue.map(function(t,i){return '<li data-i="'+i+'"'+(i===P.idx?' class="is-cur"':'')+'><span class="ssp-qi">'+(t.i?'<img src="'+esc(t.i)+'" alt="" width="36" height="36" loading="lazy">':'')+'</span><span class="ssp-qm"><b>'+esc(t.t)+'</b><small>'+esc(t.a)+'</small></span><span class="ssp-qd">'+(t.d?fmt(t.d):'')+'</span>'+(i!==P.idx?'<button type="button" class="ssp-qx" data-x="'+i+'" aria-label="Remove from queue" title="Remove">&times;</button>':'')+'</li>'}).join('')},
  state:function(){if(!this.el)return;var pl=P.cur&&!audio.paused;this.el.classList.toggle('is-playing',!!pl);this.el.querySelector('.ssp-play').setAttribute('aria-label',pl?'Pause':'Play')},
  progress:function(f,t,d){if(!this.el)return;this.wave.setProgress(f);this.el.querySelector('.ssp-cur').textContent=fmt(t);this.el.querySelector('.ssp-dur').textContent=fmt(d);this.el.querySelector('.ssp-mprog i').style.width=(Math.max(0,Math.min(1,f))*100)+'%'},
  vol:function(){if(!this.el)return;this.el.classList.toggle('is-muted',audio.muted||audio.volume===0);var r=this.el.querySelector('.ssp-vol input');r.value=audio.muted?0:audio.volume;r.style.setProperty('--v',(audio.muted?0:audio.volume)*100+'%')}
};
function close(){audio.pause();audio.removeAttribute('src');try{audio.load()}catch(e){}P.cur=null;P.queue=[];P.idx=-1;ss('ssp_state',null);Bar.hide();syncAll()}

/* ============================================================
   CARD PLAYERS (replace default WP audio players)
   ============================================================ */
function refreshCardPeaks(t){document.querySelectorAll('.ssw').forEach(function(b){if(fileOf(b.dataset.src)===fileOf(t.src)&&b._wave){var pk=peaksOf(b._track)||localPeaks[fileOf(t.src)];if(pk)b._wave.setPeaks(pk);var d=b.querySelector('.ssw-dur');if(d&&RT.p[t.pid]&&RT.p[t.pid].d)d.textContent=fmt(RT.p[t.pid].d)}})}
function pageQueue(){var seen={},q=[];document.querySelectorAll('.ssw[data-src]').forEach(function(b){var f=fileOf(b.dataset.src);if(seen[f]||!b._track)return;seen[f]=1;q.push(b._track)});return q}
function build(audioEl){
  if(audioEl.dataset.ssw)return;
  var src=(audioEl.querySelector('source')||{}).src||audioEl.getAttribute('src')||audioEl.currentSrc;if(!src)return;
  src=src.split('?_=')[0];audioEl.dataset.ssw=1;try{audioEl.pause();audioEl.preload='none'}catch(e){}
  var holder=audioEl.closest('.mejs-container')||audioEl;
  var t=trackFor(src,holder);
  var box=el('div','ssw','<button type="button" class="ssw-btn" aria-label="Play">'+ICON.play.replace('<svg','<svg class="ssw-i-play"')+ICON.pause.replace('<svg','<svg class="ssw-i-pause"')+'</button><button type="button" class="ssw-add" aria-label="Add to queue" title="Add to queue">+</button><span class="ssw-cur">0:00</span><div class="ssw-wave"></div><span class="ssw-dur">'+(t.d?fmt(t.d):'--:--')+'</span>');
  box.dataset.src=src;box._track=t;
  holder.parentNode.insertBefore(box,holder);
  var pk=peaksOf(t);
  box._wave=new Wave(box.querySelector('.ssw-wave'),{peaks:pk,onSeek:function(x){playTrack(t,null,x)}});
  if(!pk){
    var go=function(){computePeaks(t,function(p,d){box._wave.setPeaks(p);if(d&&!t.d){t.d=d;box.querySelector('.ssw-dur').textContent=fmt(d)}})};
    if('IntersectionObserver' in window){var io=new IntersectionObserver(function(e){if(e[0].isIntersecting){io.disconnect();go()}},{rootMargin:'200px'});io.observe(box)}else go();
  }
  box.querySelector('.ssw-btn').addEventListener('click',function(){playTrack(t,null)});
  var addB=box.querySelector('.ssw-add');if(addB)addB.addEventListener('click',function(){var ok=addToQueue(t);addB.textContent=ok?'\u2713':'\u2713';addB.classList.add('is-added');setTimeout(function(){addB.textContent='+';addB.classList.remove('is-added')},1400)});
}

/* ---------- tidy cards: hide empty fields, stars -> like ---------- */
function tidy(root){(root||document).querySelectorAll('[data-likes]').forEach(function(s){if(s.textContent.trim()==='0')s.textContent=''});
  root.querySelectorAll('.w-grid-item .w-hwrapper').forEach(function(row){
    var kids=[].slice.call(row.children),vws=kids.filter(function(c){return c.classList.contains('w-vwrapper')&&c.querySelector('.w-text-value')});
    if(!vws.length)return;
    vws.forEach(function(v){var l=v.querySelector('.w-text-value');var rest=v.textContent.replace(l.textContent,'').trim();v.classList.toggle('ssw-empty',!rest)});
    var seen=false,pending=null;
    kids.forEach(function(c){
      if(c.classList.contains('w-image')&&!c.querySelector('a')){c.classList.add('ssw-div-off');if(seen&&!pending)pending=c}
      else if(c.classList.contains('w-vwrapper')&&!c.classList.contains('ssw-empty')){if(pending){pending.classList.remove('ssw-div-off');pending=null}seen=true}
    });
  });
  /* broken custom fields ('Unsupported format') shown on phones */
  root.querySelectorAll('.w-grid-item .w-post-elm.post_custom_field').forEach(function(f){if(/^\s*unsupported format\s*$/i.test(f.textContent))f.classList.add('ssw-empty')});
  root.querySelectorAll('.w-grid-item').forEach(function(card){
    var pid=pidOfCard(card);if(!pid)return;
    card.querySelectorAll('.star-rating').forEach(function(st){
      var pe=st.closest('.w-post-elm')||st.parentNode,par=pe.parentNode;if(!par||pe.dataset.ssLike)return;pe.dataset.ssLike=1;
      if(par.classList.contains('w-hwrapper')&&par.children.length<=4){[].forEach.call(par.children,function(c){c.classList.add('ss-like-hide')});par.appendChild(likeBtn(pid));return}
      pe.classList.add('ss-like-hide');
      [].forEach.call(par.children,function(c){if(c.classList.contains('w-text')&&/^\s*rating:?\s*$/i.test(c.textContent))c.classList.add('ss-like-hide')});
      pe.insertAdjacentElement('afterend',likeBtn(pid));
    });
  });
}

/* ---------- Best Sellers: tabs + mini play ---------- */
function initTop(root){
  root.querySelectorAll('.ss-top.has-tabs:not(.tabbed)').forEach(function(box){
    box.classList.add('tabbed');
    var btns=[].slice.call(box.querySelectorAll('.ss-top-tab')),lists=[].slice.call(box.querySelectorAll('.ss-top-l'));
    function act(key){btns.forEach(function(b){var on=b.dataset.tab===key;b.classList.toggle('is-active',on);b.setAttribute('aria-selected',on?'true':'false')});lists.forEach(function(l){l.classList.toggle('is-active',l.dataset.tab===key)})}
    var saved=ls('ss_top_tab');act(saved&&btns.some(function(b){return b.dataset.tab===saved})?saved:(btns[0]&&btns[0].dataset.tab));
    btns.forEach(function(b){b.addEventListener('click',function(){act(b.dataset.tab);ls('ss_top_tab',b.dataset.tab)})});
  });
  root.querySelectorAll('.ss-top-i[data-src]:not([data-ok])').forEach(function(li){
    li.dataset.ok=1;var btn=li.querySelector('.ss-top-play');if(!btn)return;
    btn.addEventListener('click',function(e){
      e.preventDefault();
      var ol=li.closest('.ss-top-l'),q=[];
      (ol?ol.querySelectorAll('.ss-top-i[data-src]'):[li]).forEach(function(x){var t=trackFromPid(x.dataset.pid)||trackFor(x.dataset.src,x);q.push(t)});
      var t=trackFromPid(li.dataset.pid)||trackFor(li.dataset.src,li);
      playTrack(t,q);
    });
  });
}

/* ---------- compact filters + tag chips ---------- */
var OPEN_DEFAULT=/^product format$/i;
function initFilters(root){
  var st={};try{st=JSON.parse(ls('ssf_state')||'{}')}catch(e){}
  root.querySelectorAll('.w-filter.layout_ver:not(.ssf)').forEach(function(f){
    f.classList.add('ssf');
    var hw=f.previousElementSibling;if(hw&&hw.classList.contains('w-hwrapper'))hw.classList.add('ssf-oldhead');
    var first=f.querySelector('.w-filter-item');
    if(first&&!f.querySelector('.ssf-head')){var SV='stroke="currentColor" stroke-width="2" fill="none"';var head=el('div','ssf-head','<span class="ssf-ht"><svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path d="M3 6h10M17 6h4M3 12h4M11 12h10M3 18h12M19 18h2" '+SV+' stroke-linecap="round"/><circle cx="15" cy="6" r="2" '+SV+'/><circle cx="9" cy="12" r="2" '+SV+'/><circle cx="17" cy="18" r="2" '+SV+'/></svg>Filter by</span><a class="ssf-clear" href="'+location.pathname+'">Clear all</a>');first.parentNode.insertBefore(head,first);
      var clr=head.querySelector('.ssf-clear');var updClr=function(){clr.classList.toggle('is-on',!!f.querySelector('input:checked'))};updClr();f.addEventListener('change',updClr);f.addEventListener('click',function(){setTimeout(updClr,80)});}
    f.querySelectorAll('.w-filter-item').forEach(function(it){
      var t=it.querySelector('.w-filter-item-title');if(!t)return;
      var name=t.textContent.trim(),vals=it.querySelectorAll('.w-filter-item-value');
      if(/^product format$/i.test(name)){it.classList.add('ssf-fmt');return}
      if(/^tags?$/i.test(name)||vals.length>12){
        it.classList.add('ssf-chips');
        if(vals.length>12){var more=el('button','ssf-more');more.type='button';
          var lab=function(){more.textContent=it.classList.contains('ssf-all')?'Show less':'Show all ('+vals.length+')'};lab();
          more.addEventListener('click',function(){it.classList.toggle('ssf-all');lab()});
          (it.querySelector('.w-filter-item-values')||it).insertAdjacentElement('afterend',more);}
      }
      var badge=el('span','ssf-count');t.appendChild(badge);
      var upd=function(){var n=it.querySelectorAll('input:checked').length;badge.textContent=n;badge.style.display=n?'':'none';return n};
      var checked=upd(),open=name in st?st[name]:OPEN_DEFAULT.test(name);if(checked)open=true;
      it.classList.toggle('ssf-closed',!open);t.setAttribute('role','button');t.setAttribute('aria-expanded',open?'true':'false');
      t.addEventListener('click',function(e){e.preventDefault();e.stopPropagation();var c=it.classList.toggle('ssf-closed');t.setAttribute('aria-expanded',c?'false':'true');st[name]=!c;ls('ssf_state',JSON.stringify(st))},true);
      it.addEventListener('change',upd);it.addEventListener('click',function(){setTimeout(upd,80)});
    });
  });
}

/* ---------- catalog toolbar: Play All, count, search, sort, active chips, sticky ---------- */
function sstbTop(){var h=document.querySelector('.l-header'),ab=document.getElementById('wpadminbar'),t=0;if(h&&getComputedStyle(h).position==='fixed')t+=h.offsetHeight;if(ab&&getComputedStyle(ab).position==='fixed')t+=ab.offsetHeight;return t}
function initToolbar(root){
  root.querySelectorAll('.w-hwrapper:not(.sstb)').forEach(function(hw){
    if(!hw.querySelector('form.w-order'))return;
    var grid=hw.parentElement&&hw.parentElement.querySelector('.w-grid');if(!grid)return;
    hw.classList.add('sstb');
    var lead=el('div','sstb-lead','<button type="button" class="sstb-play" aria-label="Play all tracks">'+ICON.play+'<span>Play All</span></button><span class="sstb-count" aria-live="polite"></span>');
    hw.insertBefore(lead,hw.firstChild);
    var act=el('div','sstb-active','<span class="sstb-al">Active:</span><span class="sstb-chips"></span><a href="'+location.pathname+'" class="sstb-clear">Clear all</a>');
    act.hidden=true;hw.appendChild(act);
    /* move the sidebar filter into the toolbar as dropdowns */
    var mf=[].filter.call(document.querySelectorAll('form.w-filter.ssf'),function(f){return f.querySelectorAll('.w-filter-item').length>1})[0];
    if(mf&&!mf.classList.contains('ssf-bar')){
      var fw=el('div','sstb-filters');hw.insertBefore(fw,act);fw.appendChild(mf);mf.classList.add('ssf-bar');
      var items=function(){return mf.querySelectorAll('.w-filter-item:not(.ssf-fmt)')};
      var closeAll=function(keep){[].forEach.call(items(),function(o){if(o===keep)return;o.classList.add('ssf-closed');var tt=o.querySelector('.w-filter-item-title');if(tt)tt.setAttribute('aria-expanded','false')})};
      closeAll(null);
      [].forEach.call(mf.querySelectorAll('.ssf-chips'),function(c){c.classList.add('ssf-all')});
      mf.addEventListener('click',function(e){if(!mf.classList.contains('state_desktop'))return;var t=e.target.closest&&e.target.closest('.w-filter-item-title');if(!t)return;var it=t.closest('.w-filter-item');if(it&&it.classList.contains('ssf-closed'))closeAll(it)},true);
      document.addEventListener('click',function(e){if(!document.body.contains(mf)||!mf.classList.contains('state_desktop')||mf.contains(e.target))return;closeAll(null)});
      document.addEventListener('keydown',function(e){if(e.key==='Escape'&&document.body.contains(mf))closeAll(null)});
    }

    lead.querySelector('.sstb-play').addEventListener('click',function(){var q=pageQueue();if(q.length)playTrack(q[0],q)});
    var cnt=lead.querySelector('.sstb-count'),chips=act.querySelector('.sstb-chips');
    function upd(){
      if(!document.body.contains(hw))return;
      var n=grid.querySelectorAll('.w-grid-item').length;cnt.textContent=n+(n===1?' item':' items');
      chips.innerHTML='';var any=0,f=document.querySelector('.w-filter');
      if(f)f.querySelectorAll('input:checked').forEach(function(inp){
        var v=inp.closest('.w-filter-item-value');if(!v)return;
        var lab=(v.querySelector('.w-filter-item-value-label')||v).textContent.trim();if(!lab)return;
        var b=el('button','sstb-chip');b.type='button';b.textContent=lab;var x=el('i',null,'×');x.setAttribute('aria-hidden','true');b.appendChild(x);
        b.setAttribute('aria-label','Remove filter: '+lab);
        b.addEventListener('click',function(){(v.querySelector('label')||v).click()});
        chips.appendChild(b);any++;
      });
      act.hidden=!any;hw.classList.toggle('has-active',!!any);try{cardUI(grid)}catch(e){}
    }
    var deb=function(){clearTimeout(hw._t);hw._t=setTimeout(upd,120)};
    upd();setTimeout(upd,500);setTimeout(upd,1500);
    try{new MutationObserver(deb).observe(grid.querySelector('.w-grid-list')||grid,{childList:true})}catch(e){}
    document.addEventListener('change',function(e){if(e.target.closest&&e.target.closest('.w-filter'))deb()});
    var lastTop=-1;
    function onS(){
      if(!document.body.contains(hw)){window.removeEventListener('scroll',onS);return}
      var t=sstbTop();if(t!==lastTop){lastTop=t;hw.style.setProperty('--sstb-top',t+'px')}
      hw.classList.toggle('is-stuck',window.pageYOffset>80&&hw.getBoundingClientRect().top<=t+1);
    }
    window.addEventListener('scroll',onS,{passive:true});window.addEventListener('resize',onS);onS();
  });
}

/* ---------- card buttons: price+cart, info, like ---------- */
var ICO_INFO='<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 10.5v6.5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/><circle cx="12" cy="6.8" r="1.6" fill="currentColor"/></svg>';
var ICO_CART='<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 4h2l2.4 10.2a1 1 0 0 0 1 .8h8.9a1 1 0 0 0 1-.8L20 8H6.2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9.5" cy="19" r="1.5" fill="currentColor"/><circle cx="17" cy="19" r="1.5" fill="currentColor"/></svg>';
function cardUI(root){
  (root||document).querySelectorAll('.w-grid.layout_195 .w-grid-item').forEach(function(it){
    var b=it.querySelector('.usg_hwrapper_5');if(!b)return;
    var lk=it.querySelector('.usg_hwrapper_2 .ss-like')||it.querySelector('.ss-like');if(lk&&lk.parentNode!==b)b.appendChild(lk);
    if(it.classList.contains('ss-ui'))return;it.classList.add('ss-ui');
    var det=b.querySelector('.usg_btn_2');if(det){det.classList.add('ss-ib');det.setAttribute('aria-label','Details');det.title='Details';det.insertAdjacentHTML('afterbegin',ICO_INFO)}
    var cart=b.querySelector('.add_to_cart_button');
    if(cart){cart.classList.add('ss-cartbtn');cart.title='Add to cart';cart.setAttribute('aria-label',(cart.getAttribute('aria-label')||'Add to cart'));cart.insertAdjacentHTML('afterbegin',ICO_CART)}
  });
}

/* ---------- scan (initial + dynamic content) ---------- */
function scan(root){
  root=root||document;
  try{initFilters(root)}catch(e){}
  try{initToolbar(root)}catch(e){}
  try{initTop(root)}catch(e){}
  try{tidy(root)}catch(e){}
  try{cardUI(root)}catch(e){}
  root.querySelectorAll('audio.wp-audio-shortcode, .woocommerce audio, .product audio').forEach(function(a){try{build(a)}catch(e){}});
  syncAll();
}

/* ============================================================
   AJAX NAVIGATION (only while the player is open)
   ============================================================ */
/* (12.11.2026 owner) music must not stop on ANY page: only admin, login/logout, payment (checkout) and the vendor panel still load fully */
var NO_AJAX=/^\/([a-z]{2}\/)?(wp-admin|wp-login|wp-json|checkout|add-product|store-manager|feed|wcfm|lost-password|my-account\/customer-logout)/i;
var loadBar=null;
function progressBar(on){if(!loadBar){loadBar=el('div','ssp-loadbar');document.body.appendChild(loadBar)}loadBar.classList.toggle('is-on',on)}
function canAjax(a,e){
  if(!P.cur||!window.fetch||!window.history||!history.pushState||!window.DOMParser)return false;
  if(e&&(e.defaultPrevented||e.button!==0||e.metaKey||e.ctrlKey||e.shiftKey||e.altKey))return false;
  if(!a||!a.href||(a.target&&a.target!=='_self')||a.hasAttribute('download')||a.classList.contains('no-ajax'))return false;
  var href=a.getAttribute('href')||'';if(!href||href.charAt(0)==='#'||/^(javascript|mailto|tel):/i.test(href))return false;
  var u;try{u=new URL(a.href,location.href)}catch(x){return false}
  if(u.origin!==location.origin)return false;
  if(NO_AJAX.test(u.pathname)||/add-to-cart|add_to_wishlist|wc-ajax|action=|customize|preview=/i.test(u.search))return false;
  if(/\.(mp3|wav|zip|rar|pdf|jpe?g|png|gif|webp|svg|xml|txt|als)$/i.test(u.pathname))return false;
  if(u.pathname===location.pathname&&u.search===location.search&&u.hash)return false;
  if(a.closest('#wpadminbar,.w-filter,.w-order,.g-pagination,.g-loadmore,.w-tabs-list,.owl-nav,.owl-dots,.ssp,.ssw,.ss-top-tabs,.woocommerce-product-gallery,.wcfm-store-page,.w-popup-wrap'))return false;
  return u;
}
var loadedSrc={};document.querySelectorAll('script[src]').forEach(function(s){loadedSrc[s.src]=1});
function loadScript(src){return new Promise(function(res){if(loadedSrc[src])return res();var s=document.createElement('script');s.src=src;s.async=false;s.onload=s.onerror=function(){res()};loadedSrc[src]=1;document.body.appendChild(s)})}
function runInline(code){try{var s=document.createElement('script');s.text=code;document.body.appendChild(s);s.parentNode.removeChild(s)}catch(e){}}
function syncHead(doc){
  doc.head.querySelectorAll('link[rel="stylesheet"]').forEach(function(l){
    var href=l.getAttribute('href');if(!href)return;
    if(l.id){var cur=document.getElementById(l.id);if(cur){if(cur.getAttribute('href')!==href)cur.setAttribute('href',href);return}}
    if(!document.querySelector('link[rel="stylesheet"][href="'+href.replace(/"/g,'\\"')+'"]'))document.head.appendChild(l.cloneNode(true));
  });
  doc.head.querySelectorAll('style').forEach(function(s){
    if(s.id){var cur=document.getElementById(s.id);if(cur){if(cur.textContent!==s.textContent)cur.textContent=s.textContent;return}document.head.appendChild(s.cloneNode(true));return}
    var txt=s.textContent,dup=false;document.head.querySelectorAll('style:not([id])').forEach(function(x){if(x.textContent===txt)dup=true});
    if(!dup)document.head.appendChild(s.cloneNode(true));
  });
  var d=doc.querySelector('meta[name="description"]'),cd=document.querySelector('meta[name="description"]');
  if(d&&cd)cd.setAttribute('content',d.getAttribute('content'));
  var cn=doc.querySelector('link[rel="canonical"]'),cc=document.querySelector('link[rel="canonical"]');if(cn&&cc)cc.setAttribute('href',cn.getAttribute('href'));
}
function newScriptsFrom(doc,main){
  /* external scripts that this page needs but are not loaded yet (+ their inline data) */
  var list=[];
  doc.querySelectorAll('script').forEach(function(s){
    if(main.contains(s))return;
    var src=s.getAttribute('src');
    if(src){var abs=new URL(src,location.href).href;if(!loadedSrc[abs]){var h=(s.id||'').replace(/-js$/,'');list.push({src:abs,before:h?(doc.getElementById(h+'-js-extra')||{}).textContent||'':'' ,b2:h?(doc.getElementById(h+'-js-before')||{}).textContent||'':'',after:h?(doc.getElementById(h+'-js-after')||{}).textContent||'':''})}}
    else if(/window\.SS_RT=/.test(s.textContent)){try{var m=s.textContent.match(/window\.SS_RT=(\{[\s\S]*\});/);if(m){var nr=JSON.parse(m[1]);for(var k in nr.p)RT.p[k]=nr.p[k];if(nr.n)RT.n=nr.n}}catch(e){}}
  });
  return list;
}
function reinitTheme(main){
  var $=window.jQuery;if(!$)return;var $m=$(main);
  var map={'.w-grid':'wGrid','.w-filter':'wGridFilter','.w-order':'wGridOrder','.w-tabs':'wTabs','.w-popup':'wPopup','.w-form':'wForm','.w-dropdown':'wDropdown','.w-counter':'wCounter','.w-itext':'wItext','.w-slider':'wSlider','.w-flipbox':'wFlipBox','.w-progbar':'wProgbar','.w-video':'wVideo','.w-search':'wSearch'};
  for(var sel in map){var fn=map[sel];if($.fn[fn]){$m.find(sel).each(function(){try{$(this)[fn]()}catch(e){}})}}
  try{$m.find('.woocommerce-product-gallery').each(function(){if($.fn.wc_product_gallery)$(this).wc_product_gallery()})}catch(e){}
  try{$m.find('.wc-tabs-wrapper, .woocommerce-tabs, #rating').trigger('init')}catch(e){}
  try{$m.find('[class*="us_animate_"]').addClass('start')}catch(e){}
  try{if(window.vc_js)window.vc_js()}catch(e){}
  try{$(window).trigger('resize').trigger('scroll')}catch(e){}
  try{$(document.body).trigger('post-load')}catch(e){}
}
function markMenu(){document.querySelectorAll('#page-header .menu-item').forEach(function(li){var a=li.querySelector(':scope > a');li.classList.toggle('current-menu-item',!!(a&&a.href&&a.href.split('#')[0].replace(/\/$/,'')===location.href.split('#')[0].split('?')[0].replace(/\/$/,'')))})}
var navToken=0,homeRuns=0;
function navigate(url,push){
  var my=++navToken;progressBar(true);
  return fetch(url,{credentials:'same-origin',headers:{'X-SSP-Ajax':'1'}}).then(function(r){
    var ct=r.headers.get('content-type')||'';
    if(!r.ok||ct.indexOf('text/html')<0)throw new Error('bad');
    var finalUrl=r.url||url;var fu=new URL(finalUrl);if(NO_AJAX.test(fu.pathname))throw new Error('excluded');
    return r.text().then(function(html){return {html:html,url:finalUrl}});
  }).then(function(res){
    if(my!==navToken)return;
    var doc=new DOMParser().parseFromString(res.html,'text/html');
    var nm=doc.getElementById('page-content'),cm=document.getElementById('page-content');
    if(!nm||!cm)throw new Error('nomain');
    var scripts=newScriptsFrom(doc,nm);
    syncHead(doc);
    document.title=doc.title;
    /* keep classes added by scripts (player, site menu, theme state, Woo js flag); take the rest from the new page */
    var keep=[].filter.call(document.body.classList,function(c){return /^(ssp-|ssg-|ss-|state_|woocommerce-js$)/.test(c)&&c!=='ss-newhome'});
    document.body.className=doc.body.className;
    if(keep.some(function(c){return c.indexOf('state_')===0}))[].slice.call(document.body.classList).forEach(function(c){if(c.indexOf('state_')===0)document.body.classList.remove(c)});
    if(keep.indexOf('woocommerce-js')>=0)document.body.classList.remove('woocommerce-no-js');
    keep.forEach(function(c){document.body.classList.add(c)});
    var imported=document.importNode(nm,true);
    cm.parentNode.replaceChild(imported,cm);
    if(push)history.pushState({ssp:1},'',res.url);
    var u=new URL(res.url);
    if(u.hash&&document.getElementById(u.hash.slice(1)))document.getElementById(u.hash.slice(1)).scrollIntoView();else window.scrollTo(0,0);
    markMenu();try{document.dispatchEvent(new CustomEvent('ssp:url',{detail:{url:res.url}}))}catch(e){}
    /* run new external scripts in order, then inline scripts inside the new content */
    var chain=Promise.resolve();
    scripts.forEach(function(s){chain=chain.then(function(){if(s.before)runInline(s.before);if(s.b2)runInline(s.b2);return loadScript(s.src).then(function(){if(s.after)runInline(s.after)})})});
    return chain.then(function(){
      imported.querySelectorAll('script').forEach(function(old){
        var type=old.getAttribute('type');if(type&&!/javascript|module/i.test(type))return;
        if(old.src){loadScript(old.src);return}
        runInline(old.textContent);
      });
      /* home page app: mount it (the first visit loads its scripts above; later visits run the app script again) */
      var app=imported.querySelector('#ss-app');
      if(app&&!app.firstChild){var hs=[].filter.call(doc.querySelectorAll('script[src]'),function(x){return /home-app/.test(x.getAttribute('src'))})[0];
        if(hs){var ns=document.createElement('script');ns.src=new URL(hs.getAttribute('src'),location.href).href+'#r'+(++homeRuns);document.body.appendChild(ns)}}
      reinitTheme(imported);scan(imported);
      try{if(window.fbq)window.fbq('track','PageView')}catch(e){}
      try{document.dispatchEvent(new CustomEvent('ssp:navigated',{detail:{url:res.url}}))}catch(e){}
      progressBar(false);
    });
  }).catch(function(err){try{console.warn('[ssp] no-reload navigation failed, loading the page normally:',err&&err.message)}catch(x){}if(my===navToken){location.href=url}});
}
/* used by the site menu (logo, Products/Services buttons): switch page without stopping the music */
window.sspGo=function(url){var a=document.createElement('a');a.href=url;var u=canAjax(a);if(!u)return false;navigate(u.href,true);return true};
document.addEventListener('click',function(e){
  var a=e.target.closest&&e.target.closest('a');if(!a)return;
  var u=canAjax(a,e);if(!u)return;
  e.preventDefault();navigate(u.href,true);
});
window.addEventListener('popstate',function(e){if(e.state&&e.state.ssp&&P.cur){navigate(location.href,false)}else if(e.state&&e.state.ssp){location.reload()}});
if(history.replaceState&&!(history.state&&history.state.ssp)){try{history.replaceState({ssp:1},'',location.href)}catch(e){}}

/* ---------- restore after full page load ---------- */
function restore(){
  var st;try{st=JSON.parse(ss('ssp_state')||'null')}catch(e){}
  if(!st||!st.q||!st.q.length||st.i<0||!st.q[st.i])return;
  st.q.forEach(function(t){if(t.pid&&RT.p[t.pid]){var f=trackFromPid(t.pid);if(f&&fileOf(f.src)===fileOf(t.src)){t.w=f.w;t.d=f.d||t.d;t.i=f.i;t.t=f.t;t.a=f.a}}});
  load(st.q[st.i],st.q,{at:st.t||0});
  if(st.pl)play();
}

/* ---------- start ---------- */
function start(){
  Bar.build();scan(document);restore();
  var t,t0=0;new MutationObserver(function(muts){
    var rel=false;for(var i=0;i<muts.length&&!rel;i++){var m=muts[i];var tg=m.target;if(tg&&tg.closest&&(tg.closest('.ssp')||tg.closest('.ssw')||tg===document.body&&[].every.call(m.addedNodes,function(n){return n.classList&&(n.classList.contains('ssp')||n.classList.contains('ssp-loadbar'))})))continue;rel=true}
    if(rel){var nw=Date.now();if(!t0)t0=nw;clearTimeout(t);if(nw-t0>400){t0=0;scan(document)}else{t=setTimeout(function(){t0=0;scan(document)},150)}}
  }).observe(document.body,{childList:true,subtree:true});
  window.addEventListener('pagehide',save);
}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',start);else start();
window.SSP={play:playTrack,toggle:toggle,next:next,prev:prev,close:close,state:P,navigate:navigate};
}catch(err){document.documentElement.classList.add('ssw-fail');if(window.console)console.error('SSP',err)}
})();

