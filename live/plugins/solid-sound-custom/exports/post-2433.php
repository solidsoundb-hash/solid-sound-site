<?php exit; ?>
<!-- wp:html -->
<style>
body.ss-newhome .l-section-h{max-width:none!important;padding:0!important}
body.ss-newhome .l-section,body.ss-newhome .l-main,body.ss-newhome .l-section-h{padding:0!important;margin:0!important}
html,body.ss-newhome,body.ss-newhome .l-canvas,body.ss-newhome .l-main{background:#070A12!important}
#ss-app{min-height:100vh}
/* phones (Android + iPhone): no compositing tricks on the stacked slides, they caused paint glitches */
@media (max-width:760px){
#ss-app [aria-roledescription=slide]{transition:none!important;transform:none!important;animation:none!important;will-change:auto!important}
#ss-app [aria-roledescription=slide][aria-hidden=true]{visibility:hidden!important;opacity:0!important;pointer-events:none}
#ss-app *{backdrop-filter:none!important;-webkit-backdrop-filter:none!important}
#ss-app [style*="box-shadow"]{box-shadow:none!important}
#ss-app [style*="transition"]{transition:none!important}
#ss-app div[style*="position: sticky; bottom: 0"]{padding-bottom:env(safe-area-inset-bottom)}
#tidio-chat-iframe{bottom:72px!important}
body.ss-newhome #ssb{bottom:calc(80px + env(safe-area-inset-bottom))!important;right:16px!important}
#ss-app input,#ss-app textarea,#ss-app select{font-size:16px!important}
#ss-app header button[aria-label="Match by reference track"]{width:36px!important;height:36px!important}
#ss-app header .ss-mic,#ss-app header button[aria-label="Match by reference track"]{width:32px!important;height:32px!important}
#ss-app header form{gap:3px!important;padding:0 4px 0 10px!important}
#ss-app header form > svg:first-child{display:none!important}
#ss-app{overflow-x:hidden;overflow-x:clip}
#ss-app img,#ss-app video,#ss-app iframe{max-width:100%}
#tidio-chat{position:relative;z-index:5}
}
html,body{-webkit-text-size-adjust:100%;text-size-adjust:100%}
@media (max-width:760px){#ss-app [style*="box-shadow: 0 10px 30px"]{box-shadow:0 4px 12px rgba(0,0,0,0.35)!important}#ss-app header{backdrop-filter:none!important}}
body.ss-newhome section.ssh{display:none!important}
@media (max-width:760px){
body.ss-newhome.ssp-open #ssp{bottom:calc(63px + env(safe-area-inset-bottom))!important}
body.ss-newhome.ssp-open #ssb{bottom:calc(146px + env(safe-area-inset-bottom))!important}
body.ss-newhome.ssp-open .ssp-toast{bottom:140px}
}
body.ss-newhome .w-toplink{display:none!important}
#top .ss-hcard{display:none!important}
#top .ss-art{position:relative;order:2;flex:0 0 400px;display:flex;gap:8px;align-items:center;margin-left:auto;padding-top:14px}
#top .ss-art-i{position:static;display:block;flex:1 1 0;aspect-ratio:1/1;border-radius:12px;background-size:cover;background-position:center;box-shadow:0 10px 24px rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.12);transform:none;filter:none}
#top .ss-art-b{position:absolute;right:-6px;top:0;z-index:3;background:#F59E0B;color:#111;font-weight:800;font-size:16px;line-height:1;padding:6px 10px;border-radius:10px}
@media (max-width:1180px){#top .ss-art{flex-basis:300px}}
@media (max-width:760px){
#top .ss-art{order:-1;flex:1 1 100%!important;max-width:330px;margin:0 auto;gap:6px;padding-top:10px}
#top .ss-art-i{border-radius:10px;box-shadow:0 6px 14px rgba(0,0,0,0.45)}
#top .ss-art-b{font-size:13px;padding:4px 8px;right:-4px}
}
.ss-cta-s{display:none}
@media (max-width:760px){
#top{padding-top:8px!important;padding-bottom:10px!important}
#top h1{font-size:22px!important;line-height:1.14!important;margin:0!important}
#top [aria-roledescription=slide],#top [aria-roledescription=slide] > div{gap:10px!important}
#top div:has(> span > svg[stroke="#34D399"]){display:none!important}
#top div:has(> [aria-roledescription=slide]){grid-template-columns:minmax(0,1fr)!important}
#top [aria-roledescription=slide]{min-width:0!important;width:100%!important}
#top [aria-roledescription=slide] > div{flex:1 1 100%!important;min-width:0!important;max-width:100%!important}
#top [aria-roledescription=slide] span:has(> svg){padding:5px 9px!important;font-size:12px!important}
#top form{flex-wrap:wrap!important;margin-top:0!important;gap:8px!important}
#top form input{height:46px!important;min-width:0!important;flex:1 1 0!important;width:auto!important}
#top form > :not(input):not(button):not(label){flex:1 1 100%!important;font-size:12px!important;margin:0!important}
#top form button{height:46px!important;flex:none!important;padding:0 14px!important;font-size:14px!important;white-space:nowrap}
.ss-cta-l{display:none}.ss-cta-s{display:inline}
#top [role=tablist]{gap:6px!important}
#top [role=tablist] button{font-size:11.5px!important;gap:5px!important;padding:4px 0 0!important}
#top [role=tablist] ~ div{gap:6px!important}
#top [role=tablist] ~ div > span:first-child{display:none!important}
#top [role=tablist] ~ div button{width:36px!important;height:36px!important}
}
#ss-app .ss-cov > span{display:none}
#ss-app .ss-cov{aspect-ratio:1 / 1!important;background-size:cover!important;background-position:center!important}
body.admin-bar #ss-app header{top:32px!important}
@media (max-width:782px){body.admin-bar #ss-app header{top:46px!important}}
@media (max-width:600px){body.admin-bar #ss-app header{top:0!important}}
#ss-app button,#ss-app input,#ss-app textarea{font-family:inherit;line-height:normal;letter-spacing:normal;text-transform:none;box-shadow:none}
</style>
<div id="ss-app"></div>
<script src="https://cdn.jsdelivr.net/npm/react@18.3.1/umd/react.production.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/react-dom@18.3.1/umd/react-dom.production.min.js"></script>
<script>
(function(){
  /* arrived here via the player's AJAX navigation (old theme header still on screen): load the home page properly */
  if(document.querySelector('.l-header')){ location.replace(location.href); return; }
  document.body.classList.add('ss-newhome');
  /* play icon = the triangle from the SD logo, same on every page */
  window.SS_TRI='M6.87 3.80L6.80 20.23L6.95 20.30L19.40 12.49L19.47 11.98L19.32 11.83L7.09 3.80ZM9.23 8.07L15.57 11.98L15.57 12.27L15.57 12.57L15.34 12.71L9.16 16.32L9.08 15.73L9.08 8.22Z';
  window.ssTri=function(sz){ sz=sz||18; return '<svg width="'+sz+'" height="'+sz+'" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" fill-rule="evenodd" d="'+window.SS_TRI+'"></path></svg>'; };
  /* one shared DOM watcher instead of timers: runs fixes once per frame, only when the page changes */
  if(!window.ssOnDom){ var ssQ=[], ssP=0; window.ssOnDom=function(f){ ssQ.push(f); try{ f(); }catch(x){} }; new MutationObserver(function(){ if(ssP) return; ssP=1; requestAnimationFrame(function(){ ssP=0; ssQ.forEach(function(f){ try{ f(); }catch(x){} }); }); }).observe(document.documentElement,{childList:true,subtree:true}); }
  window.ssOnDom(function(){ document.querySelectorAll('path[d="M8 5v14l11-7z"],path[d="M7 4l13 8-13 8z"]').forEach(function(p){ p.setAttribute('d',window.SS_TRI); p.setAttribute('fill-rule','evenodd'); }); });
  /* account links (Sign in, Favorites, Saved, Client dashboard) → the client dashboard */
  window.ssOnDom(function(){ document.querySelectorAll('#ss-app a[href="#top"], #ss-app a[href="#shop"], #ss-app a[href="#dashboard"]').forEach(function(a){ var t=((a.textContent||'')+' '+(a.getAttribute('aria-label')||'')).trim(); if(/Sign in|Favorites|Saved|Client dashboard|Account/i.test(t)) a.setAttribute('href','https://solid-sound.net/en/client-dashboard/'); }); });
  /* open dropdowns in the header close on any click outside them */
  document.addEventListener('click',function(e){
    var t=e.target; document.querySelectorAll('#ss-app header button[aria-expanded="true"]').forEach(function(b){
      if(b.getAttribute('aria-label')==='Main menu') return;
      var w=b.parentElement; if(w ? !w.contains(t) : false){ b.click(); }
    });
  },true);
  /* links from the new home always do a full page load, so other pages get their own header */
  document.addEventListener('click',function(e){ var a=e.target.closest ? e.target.closest('a') : null; if(a) a.classList.add('no-ajax'); },true);
  var BASE='/wp-content/uploads/', V='?v=1';
  Promise.all([fetch(BASE+'ss-newhome-app-gz.txt'+V).then(function(r){return r.text()}).then(function(t){var bin=Uint8Array.from(atob(t.replace(/\s+/g,'')),function(c){return c.charCodeAt(0)});if(window.DecompressionStream){return new Response(new Blob([bin]).stream().pipeThrough(new DecompressionStream('gzip'))).text();} return new Promise(function(ok,no){var s=document.createElement('script');s.src='https://cdn.jsdelivr.net/npm/pako@2.1.0/dist/pako_inflate.min.js';s.onload=function(){try{ok(window.pako.ungzip(bin,{to:'string'}));}catch(x){no(x);}};s.onerror=no;document.head.appendChild(s);});}),fetch(BASE+'ss-newhome-rt.txt'+V).then(function(r){return r.text()})]).then(function(a){
    var U='https://solid-sound.net/wp-content/uploads/', G='linear-gradient(180deg, rgba(7,10,18,0) 70%, rgba(7,10,18,0.55) 100%), ';
    window.SS_COV={infinity:'Infinity_Logo_JPG-400x400.jpg',mutualism:'Mutualism_Logo_JPG-400x400.jpg',mtp:'0621-Обложка-e1755551043214-400x433.jpg',protos:'Protos_Logo_JPG.jpg',pitch:'Pitch_Title_PSD-400x400.jpg',intricate:'Intricate_Logo_JPG-400x400.jpg',kratos:'Kratos_Logo_JPG.jpg',tgf:'619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png'};
    for(var k in window.SS_COV){window.SS_COV[k]=G+'url(\''+U+window.SS_COV[k]+'\') center / cover no-repeat #1b1b1b';}
    a[0]=a[0].split(U+'logo.png').join(U+'Монтажная-область-0_3.png').split('bg: c.bg, href: c.href').join('bg: (window.SS_COV ? (window.SS_COV[c.id] || c.bg) : c.bg), href: c.href');
    var P='https://solid-sound.net/product/';
    window.SS_URL={infinity:P+'infinity/',mutualism:P+'mutualism/',mtp:P+'melodic-techno-presets/',protos:P+'protos/',pitch:P+'pitch/',intricate:P+'intricate/',kratos:P+'kratos/',tgf:P+'time-goes-fast-documentary-piano/'};
    window.SS_MP3={Infinity:'Infinity_Tech_House_128Kbps.mp3',Mutualism:'Mutualism_Dark_Techno_128kbps.mp3','Melodic Techno Presets':'Solid-Sound-Presets-Melodic-Techno-Demo.mp3',Protos:'Protos_128kbps.mp3',Pitch:'Pitch_128_kbps.mp3',Intricate:'Intricate_Dark-Techno_128kbps.mp3',Kratos:'Kratos_Dark_Techno_128kbps.mp3','Time Goes Fast':'Time-Goes-Fast-Documentary-Piano-Watermark.mp3'};
    a[0]=a[0].split('href: c.href, cardStyle').join('href: (window.SS_URL ? (window.SS_URL[c.id] || c.href) : c.href), cardStyle').split('this._end = Date.now() + ((2 * 24 + 14) * 3600 + 23 * 60) * 1000;').join('this._end = new Date(\'2026-11-30T23:59:59+01:00\').getTime();');
    var UP='https://solid-sound.net/wp-content/uploads/', YT='https://www.youtube-nocookie.com/embed/';
    window.SS_AB=[
      {id:'e1',title:'Space Echo — client track',genre:'Before / after',g:'mix',service:'Demo turned into a wide, punchy, label-ready mix',video:true,src:UP+'Mixing-Example-Anouch-Space-Echo.mp4'},
      {id:'e2',title:'Feeling Good — client track',genre:'Before / after',g:'master',service:'Loudness and glue without killing the dynamics',video:true,src:UP+'Mixing-Cases-Feeling-Good.mp4'},
      {id:'e3',title:'Eco Techno — client track',genre:'Before / after',g:'vocal',service:'Vocal tuning, compression and space',video:true,src:UP+'Mixing-Example-Anouch-Eco-Techno.mp4'},
      {id:'e4',title:'Escape — client track',genre:'Before / after',g:'mix',service:'Warmth and depth with outboard gear',video:true,src:UP+'Mixing-Example-Escape.mp4'},
      {id:'e5',title:'Kick and bass — client track',genre:'Before / after',g:'mix',service:'Kick and bass working together',video:true,src:UP+'02-Example-Before-After.mp4'},
      {id:'e6',title:'In My Mind — client track',genre:'Before / after',g:'mix',service:'Sound design that makes transitions huge',video:true,src:UP+'Example-In-My-Mind.mp4'},
      {id:'e7',title:'Ava Kedavra — client track',genre:'Before / after',g:'master',service:'Mud and rumble removed, tight low end',video:true,src:UP+'Mixing-Example-AVA-KEDAVRA.mp4.mp4'},
      {id:'p1',title:'Post-production reel',genre:'Film · Ads · Video',g:'video',service:'Sound design, Foley, music and mix',video:true,yt:YT+'lpjlnxQxvAY'},
      {id:'p2',title:'Post-production reel 2',genre:'Film · Ads · Video',g:'video',service:'Sound for film, TV and ads',video:true,yt:YT+'vYIh0YHw33A'},
      {id:'p3',title:'Sound design for video — after',genre:'Film · Ads · Video',g:'video',service:'Atmospheres, effects and final mix',video:true,yt:YT+'SEFtbYJUvvo'},
      {id:'p4',title:'Audio post — after',genre:'Film · Ads · Video',g:'video',service:'Cleanup, restoration and mix',video:true,yt:YT+'AoHIenCHQY4'}
    ];
    a[0]=a[0].replace(/var abList = \[[\s\S]*?\];/,'var abList = window.SS_AB;')
      .replace("[['all', 'All'], ['techno', 'Techno'], ['melodic', 'Melodic Techno'], ['house', 'House'], ['vocal', 'Vocal / Podcast'], ['video', 'Film · Ads · Video']]","[['all', 'All'], ['mix', 'Mixing'], ['master', 'Mastering'], ['vocal', 'Vocals'], ['video', 'Film · Ads · Video']]")
      .split('title: e.title, genre: e.genre, service: e.service,').join('src: e.src || \'\', yt: e.yt || \'\', isYt: !!e.yt, isMp4: !!e.src, title: e.title, genre: e.genre, service: e.service,')
      .replace(/<sc-if value="\{\{e\.isVideo\}\}"[\s\S]*?(?=<sc-if value="\{\{e\.isAudio\}\}")/,'<sc-if value="{{e.isMp4}}" hint-placeholder-val="{{ false }}"><video src="{{e.src}}" controls preload="metadata" playsinline style="display: block; width: 100%; aspect-ratio: 16 / 9; border-radius: 10px; background: #000"></video></sc-if><sc-if value="{{e.isYt}}" hint-placeholder-val="{{ false }}"><iframe src="{{e.yt}}" title="{{e.title}}" loading="lazy" allow="encrypted-media; picture-in-picture; fullscreen" allowfullscreen style="display: block; width: 100%; aspect-ratio: 16 / 9; border: 0; border-radius: 10px; background: #000"></iframe></sc-if>');
    a[0]=a[0].split("stateLabel: aft ? 'AFTER' : 'BEFORE'").join("stateLabel: e.yt ? 'VIDEO' : (e.src ? 'BEFORE / AFTER' : (aft ? 'AFTER' : 'BEFORE'))");
    a[0]=a[0].split('[Guarantee / refund terms — confirm]').join('Faulty file? Fix, replacement or refund within 30 days. Services: cancel within 14 days.')
      .split("title: 'Your next Spotify release, [done for you]. [40% off].'").join("title: 'Your next release, [done for you]. Template + mix [€69].'")
      .split("sub: 'Stop collecting demos. Each bundle: a pro track template, a preset pack and a custom mix of your version by our engineer.'").join("sub: 'Stop collecting demos. Take a top-selling template like Intricate, build your version, and our engineer mixes it to a release-ready sound.'")
      .split("cta: 'See turnkey bundles', href: '#services'").join("cta: 'Get the bundle', href: 'https://solid-sound.net/Services-Calculator/'")
      .split("cardBg: '#1b1670', cardCover: 'Bundle', cardTag: 'PRODUCT + SERVICE', cardTitle: 'Template + presets + mixing', oldPrice: '€96', newPrice: '€58', cardText: 'Mutualism template + Melodic Techno Presets + mixing of your track.', cardCta: 'Add bundle to cart · €58', cardHref: '#shop'").join("cardBg: '"+G+"url("+U+"Intricate_Logo_JPG-400x400.jpg) center / cover', cardCover: '', cardTag: 'TEMPLATE + MIXING', cardTitle: 'Intricate + mix of your track', oldPrice: '€86', newPrice: '€69', cardText: 'Dark techno project for Ableton Live 11 (native plugins) + a full mix of your version by our engineer.', cardCta: 'Get the bundle · €69', cardHref: 'https://solid-sound.net/Services-Calculator/'")
      .split("cardBg: '#3a1414', cardCover: 'Black Month'").join("cardBg: '"+G+"url("+U+"Mutualism_Logo_JPG-400x400.jpg) center / cover', cardCover: ''")
      .split("chips: [{ i: 'M4 4h16v16H4zM4 9h16M9 9v11', l: 'Pro template' }").join("chips: [{ i: 'M4 4h16v16H4zM4 9h16M9 9v11', l: 'Intricate template' }")
      .split("l: 'Preset pack' }").join("l: 'Or Mutualism / Infinity' }")
      .split("[Track name] — client project").join('Client track');
    a[0]=a[0].split("products: [{ label: 'Templates', icon: ic.layers, href: 'https://solid-sound.net/templates' }").join("products: [{ label: 'All products', icon: 'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z', href: 'https://solid-sound.net/en/all-products/' }, { label: 'Templates', icon: ic.layers, href: 'https://solid-sound.net/templates' }")
      .split("label: 'All products', href: 'https://solid-sound.net/en/'").join("label: 'All products', href: 'https://solid-sound.net/en/all-products/'")
      .split('Template + mix bundle −40%').join('Template + mix · €69').split('Bundle −40%').join('Bundle €69');
    /* perf 10.11: no fixed background repaint, countdown re-render once a minute instead of every second */
    a[0]=a[0].split("background-attachment: fixed; position: relative").join("position: relative").split("self.setState({ tick: Date.now() }); }, 1000").join("self.setState({ tick: Date.now() }); }, 60000").split("p(m) + 'm ' + p(sec) + 's'").join("p(m) + 'm'");
    a[0]=a[0].split("try { window.addEventListener('resize', function () { self.setState({ vw: window.innerWidth }); }); } catch (e) {}").join("try { var lw = window.innerWidth; window.addEventListener('resize', function () { var w = window.innerWidth; if ((w < 760) !== (lw < 760)) { lw = w; self.setState({ vw: w }); } lw = w; }, { passive: true }); } catch (e) {}")
      .split("this._cd = setInterval(function () { self.setState({ tick: Date.now() }); }, 60000);").join("this._cd = setInterval(function () { self.setState({ tick: Date.now() }); }, 1000);")
      .split("this._cd = setInterval(function () { self.setState({ tick: Date.now() }); }, 1000);").join("this._cd = setInterval(function () { var L = self.cd(), S = self.cd(true), n = document.querySelectorAll('#ss-app b, #ss-app strong'); for (var i = 0; i < n.length; i++) { var t = n[i].textContent; if (/^[0-9]+d [0-9]+h [0-9]+m [0-9]+s$/.test(t)) n[i].textContent = L; else if (/^[0-9]+d [0-9]+h [0-9]+m$/.test(t)) n[i].textContent = S; } }, 1000);");
    a[0]=a[0].replace(/(M10 17l5-5-5-5M15 12H3M14 3h6v18h-6"><\/path><\/svg>\s*<\/a>)/,'$1<button type="button" aria-label="Main menu" aria-expanded="{{sheetOpen}}" onClick="{{toggleSheet}}" style="flex: none; order: 5; width: 40px; height: 40px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.12); background: #131B2E; color: #FFFFFF; display: flex; align-items: center; justify-content: center; padding: 0"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" style="display: block"><path d="{{menuIcon}}"></path></svg></button>');
    a[0]=a[0].split('<button type="button" class="ss-sx" aria-label="{{micLabel}}"').join('<button type="button" class="ss-mic" aria-label="Voice search"');
    a[0]=a[0].replace(new RegExp('<'+'img src="[^"]*(logo[.]png|0_3[.]png)" alt="Solid Sound"[^>]*>','g'),'<svg viewBox="0 0 341 230" role="img" aria-label="Solid Sound" style="display: block; width: {{logoWpx}}; height: auto; max-height: 44px"><path fill="#F59E0B" fill-rule="evenodd" d="M169 2L168 225L170 226L339 120L340 113L338 111L172 2ZM201 60L287 113L287 117L287 121L284 123L200 172L199 164L199 62ZM153 3L140 3L130 19L127 21L120 15L110 10L92 2L83 1L55 2L48 4L34 11L26 18L16 28L11 39L7 53L6 76L12 94L18 104L26 112L34 118L45 123L66 130L109 141L120 146L128 154L132 162L133 181L130 188L126 196L120 203L110 209L104 211L91 213L70 211L62 208L52 202L46 198L37 188L26 170L17 147L4 146L2 148L2 224L4 226L15 226L27 210L30 207L50 219L67 225L100 226L109 225L129 217L141 208L152 197L158 185L163 170L163 139L156 123L148 114L140 108L126 100L102 92L68 84L53 79L46 74L40 66L36 59L35 46L36 38L41 30L47 24L54 20L62 16L72 14L88 16L104 24L110 28L120 38L129 53L138 74L140 78L152 79L153 78Z"></path></svg>');
    a[0]=a[0].split("heroH: isM ? '540px' : '470px'").join("heroH: isM ? '0px' : '470px'").split('Get the pack &amp; coupon →').join('<span class="ss-cta-l">Get the pack &amp; coupon →</span><span class="ss-cta-s">Get free pack →</span>');
    var CU='https://solid-sound.net/wp-content/uploads/';
    window.SS_ART=[
      {b:'FREE',c:[CU+'Mutualism_Logo_JPG-400x400.jpg',CU+'0621-Обложка-e1755551043214-400x433.jpg',CU+'Infinity_Logo_JPG-400x400.jpg']},
      {b:'€69',c:[CU+'Mutualism_Logo_JPG-400x400.jpg',CU+'Infinity_Logo_JPG-400x400.jpg',CU+'Intricate_Logo_JPG-400x400.jpg']},
      {b:'−50%',c:[CU+'Pitch_Title_PSD-400x400.jpg',CU+'Kratos_Logo_JPG.jpg',CU+'Protos_Logo_JPG.jpg']}
    ];
    a[0]=a[0].split('titleParts: titleOf(x), trustItems: trustOf(x) });').join('titleParts: titleOf(x), trustItems: trustOf(x), artBadge: (window.SS_ART[i] || {}).b || \'\', art: ((window.SS_ART[i] || {}).c || []).map(function (u, k) { return { st: \'background-image: url(\' + u + \')\', k: \'ss-art-i ss-art-\' + k }; }) });')
      .split('style="grid-area: 1 / 1; display: flex; flex-wrap: wrap; align-items: center; gap: {{heroGap}}; {{sl.vis}}">').join('style="grid-area: 1 / 1; display: flex; flex-wrap: wrap; align-items: center; gap: {{heroGap}}; {{sl.vis}}"><div class="ss-art" aria-hidden="true"><sc-for list="{{sl.art}}" as="im" hint-placeholder-count="3"><span class="{{im.k}}" style="{{im.st}}"></span></sc-for><b class="ss-art-b">{{sl.artBadge}}</b></div>');
    a[0]=a[0].split('<sc-if value="{{isD}}" hint-placeholder-val="{{ true }}"><span class="ss-sx" style="flex: none;').join('<span class="ss-ai" role="button" tabindex="0" aria-label="AI search" style="cursor: pointer; flex: none;').split('AI</span></sc-if>').join('AI</span>').split("isM ? 'Search sounds, services…'").join("isM ? 'Ask AI: dark techno…'");
    a[0]=a[0].split('d="M7 4l13 8-13 8z"').join('d="'+window.SS_TRI+'" fill-rule="evenodd"');
    var box=document.getElementById('ss-app'); box.innerHTML=a[0];
    var old=box.querySelector('script[data-dc-script]'), ns=document.createElement('script');
    for(var i=0;i<old.attributes.length;i++) ns.setAttribute(old.attributes[i].name,old.attributes[i].value);
    ns.textContent=old.textContent; old.parentNode.replaceChild(ns,old);
    var rt=document.createElement('script'); rt.textContent=a[1]; document.body.appendChild(rt);
  });
  /* chat bubble above the bottom app bar on phones; normal position when the chat is open */
  function tdFix(f){ var up=(f.offsetHeight < 300) ? window.innerWidth < 761 : false; var v=up ? 'translateY(-84px)' : 'none'; if(f.style.transform!==v){ f.style.setProperty('transform',v,'important'); } }
  window.ssOnDom(function(){ var f=document.getElementById('tidio-chat-iframe'); if(!f ? true : f.ssRo) return; f.ssRo=1; tdFix(f); if(window.ResizeObserver) new ResizeObserver(function(){ tdFix(f); }).observe(f); });
  /* back-to-top arrow above the chat bubble */
  (function(){
    var t=document.createElement('button'); t.type='button'; t.id='ss-top'; t.setAttribute('aria-label','Back to top');
    t.innerHTML='<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 19V5M5 12l7-7 7 7"></path></svg>';
    t.style.cssText='position:fixed;right:18px;z-index:2147483000;width:48px;height:48px;border-radius:50%;border:1px solid rgba(255,255,255,0.14);background:#1E2630;color:#F59E0B;display:none;align-items:center;justify-content:center;padding:0;cursor:pointer;box-shadow:0 4px 12px rgba(0,0,0,0.45)';
    t.addEventListener('click',function(){ try{ window.scrollTo({top:0,behavior:'smooth'}); }catch(x){} setTimeout(function(){ if(window.scrollY > 0) window.scrollTo(0,0); },700); });
    document.body.appendChild(t);
    function place(){
      var f=document.getElementById('ssb') || document.getElementById('tidio-chat-iframe'), base;
      if(f){ var r=f.getBoundingClientRect(); base=(window.innerHeight-r.top)+6; } else { base=(window.innerWidth < 761) ? 96 : 24; }
      if(base > window.innerHeight*0.6) base=(window.innerWidth < 761) ? 180 : 110;
      t.style.bottom=base+'px';
      t.style.display=(window.scrollY > 500) ? 'flex' : 'none';
    }
    var plP=0; function plQ(){ if(plP) return; plP=1; requestAnimationFrame(function(){ plP=0; place(); }); }
    window.addEventListener('scroll',plQ,{passive:true}); window.addEventListener('resize',plQ,{passive:true}); plQ();
  })();
  /* real search + voice dictation in the header search */
  function ssSearch(q){ q=(q||'').trim(); if(!q) return; if(window.ssAI ? (window.ssAIon ? window.ssAIon() : true) : false){ window.ssAI(q); return; } try{if(window.gtag)gtag('event','search',{search_term:q});}catch(x){} location.href='https://solid-sound.net/?post_type=product'+String.fromCharCode(38)+'s='+encodeURIComponent(q); }
  document.addEventListener('keydown',function(e){ if(e.key==='Enter' ? (e.target.id==='top-q') : false){ e.preventDefault(); ssSearch(e.target.value); } },true);
  document.addEventListener('click',function(e){
    if(!e.target.closest) return;
    var f=e.target.closest('#ss-app header form button'); if(!f) return;
    if(/^\s*Find\s*$/.test(f.textContent||'')){ e.preventDefault(); ssSearch((document.getElementById('top-q')||{}).value); return; }
    if(!f.classList.contains('ss-mic')) return;
    e.preventDefault(); e.stopPropagation(); e.stopImmediatePropagation();
    var inp=document.getElementById('top-q'); var SR=window.SpeechRecognition || window.webkitSpeechRecognition;
    if(!SR){ if(window.ssMic) window.ssMic(false,'Voice not supported here, type instead'); if(inp){ inp.focus(); } return; }
    if(window.ssRec){ try{window.ssRec.stop();}catch(x){} window.ssRec=null; if(window.ssMic) window.ssMic(false); return; }
    var r=new SR(); window.ssRec=r; r.lang=(navigator.language||'en-US'); r.interimResults=true; r.maxAlternatives=1;
    if(window.ssMic) window.ssMic(true);
    r.onresult=function(ev){ var txt=''; for(var i=0;i<ev.results.length;i++){ txt+=ev.results[i][0].transcript; } if(inp){ var set=Object.getOwnPropertyDescriptor(HTMLInputElement.prototype,'value').set; set.call(inp,txt); inp.dispatchEvent(new Event('input',{bubbles:true})); } if(ev.results[ev.results.length-1].isFinal){ try{ r.stop(); }catch(x){} ssSearch(txt); } };
    r.onend=function(){ if(window.ssRec===r) window.ssRec=null; if(window.ssMic) window.ssMic(false); };
    r.onerror=function(ev){ if(window.ssRec===r) window.ssRec=null; if(window.ssMic) window.ssMic(false, (ev ? ev.error==='no-speech' : false) ? 'Didn’t catch that, tap the mic again' : 'Mic blocked, allow microphone or type'); };
    try{ r.start(); }catch(x){ window.ssRec=null; if(window.ssMic) window.ssMic(false); }
  },true);
  var AU=new Audio(); AU.preload='none'; var CUR='';
  document.addEventListener('click',function(e){
    if(!e.target.closest) return;
    var b=e.target.closest('#ss-app button'); if(!b) return;
    if(b.closest('header')) return; var lab=b.getAttribute('aria-label')||''; if(!/^(Play|Pause|Stop)/i.test(lab)) return;
    var n=b, cov=null; while(n){ if(n.querySelector){ cov=n.querySelector('.ss-cov'); if(cov) break; } n=n.parentElement; }
    var name=cov?(cov.getAttribute('aria-label')||'').replace(/^Open /,''):'';
    if(!name){ if(/Pause|Stop/i.test(lab)){AU.pause();CUR='';} return; }
    var key=null; for(var k in window.SS_MP3){ if(name.indexOf(k)===0) key=k; }
    if(!key) return;
    if(CUR===key){ AU.pause(); CUR=''; return; }
    AU.src='https://solid-sound.net/wp-content/uploads/'+window.SS_MP3[key]; AU.play(); CUR=key;
    try{if(window.gtag)gtag('event','play_demo',{item_name:key,source:'new_home'});}catch(x){}
  },true);
  var FREE='https://solid-sound.net/en/free/';
  document.addEventListener('click',function(e){
    if(!e.target.closest) return;
    var b=e.target.closest('#ss-app button,#ss-app a'); if(!b) return;
    var t=(b.textContent||'').trim(), form=b.closest('form'), mail=form?form.querySelector('input[type=email]'):null;
    if(mail?/pack|coupon|starter|demo|send|download/i.test(t):false){
      e.preventDefault(); var v=(mail.value||'').trim();
      if(!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(v)){mail.focus();mail.style.outline='2px solid #F59E0B';return;}
      try{if(window.gtag)gtag('event','generate_lead',{method:'new_home',cta:t});}catch(x){}
      location.href=FREE+'?email='+encodeURIComponent(v)+String.fromCharCode(38)+'src=new-home'; return;
    }
    if(/Upload demo|Drop or choose|Send for free feedback/i.test(t)){e.preventDefault();location.href=FREE;}
  },true);
})();
</script>
<style>button:has(path[d^="M6.87"]),.ssh-play{background:transparent!important;border-color:transparent!important;box-shadow:none!important;color:#F59E0B!important;transform:none!important}button:has(path[d^="M6.87"]):hover{color:#FBBF24!important}button:has(path[d^="M6.87"]) svg,.ssh-play svg{width:30px!important;height:30px!important;fill:currentColor!important}#ss-app .ss-ai{display:inline-flex!important;align-items:center}#ss-app .ss-ai{white-space:nowrap;user-select:none;-webkit-tap-highlight-color:transparent;transition:background .15s,color .15s}#ss-app .ss-mic.ss-live{animation:ssPulse 1.2s ease-out infinite}@keyframes ssPulse{0%{box-shadow:0 0 0 0 rgba(245,158,11,.65)}100%{box-shadow:0 0 0 10px rgba(245,158,11,0)}}@media (max-width:760px){#ss-app .ss-ai{font-size:10px!important;padding:2px 5px!important}}</style>
<script>
(function(){
  var SVC=/(mix|master|vocal|tuning|ghost|feedback|loud|сведен|мастер|свест|вокал)/;
  var SYN=[
    [/(anyma|afterlife|tale of us|mathame|artbat|massano|innellea|melodic)/,'melodic techno presets'],
    [/(adam beyer|amelie lens|charlotte|i hate models|dark|industrial|hard|raw|warehouse|hypnotic|vitaly|темн|тёмн)/,'dark techno'],
    [/(dino|maggiorana|peak|driving|minimal|детройт|detroit)/,'techno pitch'],
    [/(tech house|house|groove|fisher|chris lake)/,'tech house infinity'],
    [/(film|movie|documentary|piano|cinematic|ads|advert|video|youtube|background|calm|relax|emotional|кино|пианино|фон)/,'piano cinematic documentary'],
    [/(serum|preset|synth|patch|пресет)/,'presets serum'],
    [/(template|project|als|ableton|learn|tutorial|шаблон|проект)/,'template ableton']
  ];
  var STOP=' a an the and or for with like me my i need want some sound sounds track tracks song music something + make get from in of to by bpm key ';
  var cache=null, panel=null, aud=null, micOn=false, micMsg='', aiOn=true;
  try{ aiOn=localStorage.getItem('ssAiOn')!=='0'; }catch(x){}
  window.ssAIon=function(){ return aiOn; };
  function phText(){
    var m=innerWidth<761;
    if(micOn) return m ? 'Speak now…' : 'Listening… speak now (tap the mic again to stop)';
    if(micMsg) return micMsg;
    if(aiOn) return m ? 'Ask AI…' : 'AI search on: describe it, e.g. “dark techno, 130 BPM, like Anyma + mastering”';
    return m ? 'Search…' : 'Search products and services (AI off)';
  }
  function setSt(el,k,v){ if(el.style.getPropertyValue(k)!==v) el.style.setProperty(k,v,'important'); }
  function paint(force){
    var inp=document.getElementById('top-q'), chip=document.querySelector('#ss-app .ss-ai'), mic=document.querySelector('#ss-app .ss-mic');
    if(inp){ var p=phText(); if(inp.placeholder!==p) inp.placeholder=p; }
    if(chip){
      var ct=aiOn ? 'AI' : 'AI off'; if(chip.textContent!==ct) chip.textContent=ct;
      chip.setAttribute('aria-pressed', aiOn ? 'true' : 'false');
      chip.title=aiOn ? 'AI search is on (tap to turn off)' : 'AI search is off (tap to turn on)';
      setSt(chip,'background', aiOn ? '#F59E0B' : 'transparent'); setSt(chip,'color', aiOn ? '#111111' : '#94A3B8');
      setSt(chip,'border', aiOn ? '1px solid #F59E0B' : '1px solid rgba(148,163,184,0.45)');
    }
    if(mic){
      mic.setAttribute('aria-pressed', micOn ? 'true' : 'false'); mic.setAttribute('aria-label', micOn ? 'Voice search on, tap to turn off' : 'Voice search');
      setSt(mic,'background', micOn ? '#F59E0B' : 'transparent'); setSt(mic,'color', micOn ? '#111111' : '#CBD5E1');
      if(micOn) mic.classList.add('ss-live'); else mic.classList.remove('ss-live');
    }
  }
  window.ssMic=function(on,msg){ micOn=!!on; micMsg=on ? '' : (msg||micMsg); paint(true); if(micMsg){ setTimeout(function(){ if(!micOn){ micMsg=''; paint(true); } },4000); } };
  window.ssOnDom(paint); window.addEventListener('resize',function(){ paint(true); },{passive:true});
  function load(){
    if(cache) return Promise.resolve(cache);
    return fetch('/wp-json/wc/store/v1/products?per_page=100').then(function(r){return r.json();}).then(function(j){
      cache=j.filter(function(p){ return p.prices ? Number(p.prices.price)>0 : false; }).map(function(p){
        var d=document.createElement('div'); d.innerHTML=(p.short_description||'')+' '+(p.description||'');
        var txt=(d.textContent||'').toLowerCase();
        var mp3=((d.textContent||'').match(/https:[^ ]+?[.]mp3/i)||[''])[0];
        var at={}; (p.attributes||[]).forEach(function(a){ at[a.name.toLowerCase()]=(a.terms||[]).map(function(x){return x.name;}).join('/'); });
        var mu=Math.pow(10,(p.prices.currency_minor_unit==null)?2:p.prices.currency_minor_unit);
        return {name:p.name, url:p.permalink, img:(p.images[0]||{}).thumbnail||'', txt:p.name.toLowerCase()+' '+txt, bpm:parseInt(at.bpm||'0',10), key:(at['root key']||'').toLowerCase(), price:(p.prices.currency_symbol||'$')+Math.round(Number(p.prices.price)/mu), mp3:mp3};
      });
      return cache;
    });
  }
  function words(q){ return q.toLowerCase().replace(/[^a-zа-яё0-9#]+/g,' ').split(' ').filter(function(w){ return w.length>1 ? STOP.indexOf(' '+w+' ')<0 : false; }); }
  function rank(q,list){
    var ql=q.toLowerCase(), ex=ql;
    SYN.forEach(function(s){ if(s[0].test(ql)) ex+=' '+s[1]; });
    var ws=words(ex), ph=[], PH=['melodic techno','dark techno','pitch','tech house','piano','serum','template'];
    SYN.forEach(function(s,n){ if(s[0].test(ql)) ph.push(PH[n]); });
    var bpm=(ql.match(/(^|[^0-9])(1[0-7][0-9])([^0-9]|$)/)||[])[2]; bpm=bpm?parseInt(bpm,10):0;
    var key=(ql.match(/(^|[ ,])([a-g](#|b)?m?)( |,|$)/)||[])[2]||'';
    return list.map(function(p){
      var sc=0, why=[];
      ws.forEach(function(w){ if(p.name.toLowerCase().indexOf(w)>=0) sc+=2; else if(p.txt.indexOf(w)>=0) sc+=1; });
      ph.forEach(function(x){ if(p.txt.indexOf(x)>=0) sc+=6; });
      if(bpm ? p.bpm : false){ var dd=Math.abs(p.bpm-bpm); sc+= dd<=1 ? 7 : (dd<=3 ? 4 : 0); }
      if(key ? p.key===key : false){ sc+=3; }
      if(/dark techno/.test(p.txt)) why.push('Dark techno'); else if(/melodic techno/.test(p.txt)) why.push('Melodic techno'); else if(/tech house/.test(p.txt)) why.push('Tech house'); else if(/piano/.test(p.txt)) why.push('Cinematic piano'); else if(/techno/.test(p.txt)) why.push('Techno');
      if(p.bpm) why.push(p.bpm+' BPM'); if(p.key) why.push(p.key.charAt(0).toUpperCase()+p.key.slice(1));
      if(/native ableton/.test(p.txt)) why.push('native plugins'); else if(/serum/.test(p.txt)) why.push('Serum');
      var rs=window.ssRefScores; if(rs ? (p.name in rs) : false){ var dv=rs[p.name]; sc+=Math.max(0,12-4*dv); why.unshift('Sound match '+Math.round(100*Math.exp(-dv/3))+'%'); }
      return {p:p, sc:sc, why:why.join(' · ')};
    }).sort(function(a,b){ return b.sc-a.sc; });
  }
  function esc(s){ return String(s).replace(/[<>"]/g,''); }
  function close(){ if(panel){ panel.remove(); panel=null; } if(aud){ aud.pause(); } }
  function plain(q){ var w=words(q)[0]||q; location.href='https://solid-sound.net/?post_type=product'+String.fromCharCode(38)+'s='+encodeURIComponent(w); }
  function show(q,html){
    close();
    var f=document.querySelector('#ss-app header form'), r=f ? f.getBoundingClientRect() : {bottom:120,left:12,width:360};
    panel=document.createElement('div'); panel.id='ss-ai-panel';
    var m=innerWidth<761;
    panel.style.cssText='position:fixed;z-index:99990;top:'+Math.round(r.bottom+8)+'px;left:'+(m?10:Math.round(r.left))+'px;'+(m?'right:10px;':'width:'+Math.max(420,Math.round(r.width))+'px;')+'max-height:'+(m?'calc(100vh - '+Math.round(r.bottom+100)+'px)':'70vh')+';overflow:auto;background:#131B2E;border:1px solid rgba(245,158,11,.35);border-radius:16px;box-shadow:0 20px 50px rgba(0,0,0,.7);padding:14px;color:#E5E7EB;font:14px/1.4 inherit;-webkit-overflow-scrolling:touch';
    panel.innerHTML='<div style="display:flex;align-items:center;gap:8px;margin-bottom:10px"><span style="background:#F59E0B;color:#111;font-weight:800;font-size:11px;padding:3px 7px;border-radius:6px">AI</span><b style="flex:1;font-size:14px;color:#fff;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">'+esc(q)+'</b><button type="button" data-ai="x" aria-label="Close" style="width:36px;height:36px;border:0;border-radius:10px;background:#1A2540;color:#cbd5e1;font-size:20px;line-height:1">×</button></div>'+html;
    document.body.appendChild(panel);
  }
  function card(x){
    var p=x.p;
    return '<div style="display:flex;gap:12px;align-items:center;padding:10px;border-radius:12px;background:#1A2540;margin-bottom:8px">'+
      '<a href="'+p.url+'" style="flex:none;width:64px;height:64px;border-radius:10px;background:#090E17 center/cover no-repeat;background-image:url('+p.img+')" aria-label="'+esc(p.name)+'"></a>'+
      '<a href="'+p.url+'" style="flex:1;min-width:0;color:#fff;text-decoration:none"><b style="display:block;font-size:15px">'+esc(p.name)+'</b><span style="display:block;color:#94A3B8;font-size:12px">'+esc(x.why)+'</span><span style="color:#F59E0B;font-weight:700">'+p.price+'</span></a>'+
      (p.mp3?'<button type="button" data-ai="play" data-mp3="'+p.mp3+'" aria-label="Play demo" style="flex:none;width:42px;height:42px;border-radius:50%;border:0;background:#F59E0B;color:#111;font-size:15px">'+window.ssTri(18)+'</button>':'')+
      '</div>';
  }
  window.ssAI=function(q,lab){
    q=(q||'').trim(); if(!q) return; var hq=lab||q;
    try{ if(window.gtag) gtag('event','search',{search_term:q, search_mode:'ai'}); }catch(e){}
    var inp=document.getElementById('top-q'); if(inp) inp.blur();
    show(hq,'<div style="color:#94A3B8;padding:8px 2px">Finding the best match…</div>');
    load().then(function(list){
      var r=rank(q,list), rsx=(window.ssRefScores=null), top=r.filter(function(x){return x.sc>0;}).slice(0,3), svc=SVC.test(q.toLowerCase()), h='';
      if(top.length){ h+='<div style="font-size:12px;color:#94A3B8;margin:0 2px 8px">Best matches</div>'+top.map(card).join(''); }
      else if(!svc){ h+='<div style="font-size:13px;color:#cbd5e1;margin:0 2px 8px">No exact match yet. Popular picks:</div>'+r.slice(0,3).map(card).join(''); }
      if(svc || top.length===0){
        h+='<a href="https://solid-sound.net/Services-Calculator/" style="display:flex;gap:10px;align-items:center;padding:12px;border-radius:12px;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.35);color:#fff;text-decoration:none;margin-bottom:8px"><span style="font-size:20px">🎚️</span><span style="flex:1"><b>Mixing + mastering by our engineer</b><br><span style="color:#94A3B8;font-size:12px">Get your price in 1 minute</span></span><span style="color:#F59E0B;font-weight:700">→</span></a>';
        h+='<a href="https://solid-sound.net/en/free-demo-feedback/" style="display:block;padding:10px 12px;border-radius:12px;background:#1A2540;color:#cbd5e1;text-decoration:none;margin-bottom:8px;font-size:13px">🎧 Free feedback on your track →</a>';
      }
      h+='<div style="display:flex;gap:8px;margin-top:6px"><button type="button" data-ai="all" style="flex:1;height:42px;border-radius:10px;border:1px solid rgba(255,255,255,.12);background:transparent;color:#fff;font-weight:600">All results</button><button type="button" data-ai="chat" style="flex:1;height:42px;border-radius:10px;border:0;background:#F59E0B;color:#111;font-weight:700">Ask in chat</button></div>';
      show(hq,h);
    }).catch(function(){ plain(q); });
  };
  document.addEventListener('click',function(e){
    var t=e.target.closest ? e.target.closest('[data-ai],.ss-ai') : null;
    if(!t){ if(panel ? !panel.contains(e.target) : false){ if(!e.target.closest('#ss-app header form')) close(); } return; }
    if(t.classList.contains('ss-ai')){ e.preventDefault(); e.stopPropagation(); aiOn=!aiOn; try{ localStorage.setItem('ssAiOn', aiOn ? '1' : '0'); }catch(x){} if(!aiOn) close(); paint(true); try{ if(window.gtag) gtag('event','ai_search_toggle',{on:aiOn}); }catch(x){} return; }
    var a=t.getAttribute('data-ai'), q=(document.getElementById('top-q')||{}).value||'';
    if(a==='x') close();
    if(a==='all') plain(q);
    if(a==='chat'){ close(); var b=document.querySelector('.ssb-fab,#ssb button'); if(b) b.click(); }
    if(a==='play'){ var src=t.getAttribute('data-mp3'); if(aud ? aud.src===src : false){ if(aud.paused){ aud.play(); t.textContent='❚❚'; } else { aud.pause(); t.innerHTML=window.ssTri(18); } return; } if(aud) aud.pause(); panel.querySelectorAll('[data-ai=play]').forEach(function(b){ b.innerHTML=window.ssTri(18); }); aud=new Audio(src); aud.play(); t.textContent='❚❚'; try{ if(window.gtag) gtag('event','play_demo',{src:'ai_search'}); }catch(x){} }
  },true);
  /* Match by reference track: tempo detected in the browser, file never leaves the device */
  function tempo(buf){
    var sr=buf.sampleRate, d=buf.getChannelData(0), hop=Math.round(sr/100);
    var st=Math.min(Math.floor(d.length*0.25), sr*30), en=Math.min(d.length, st+sr*40);
    if(en-st < sr*8){ st=0; en=Math.min(d.length, sr*40); }
    var env=[], prev=0;
    for(var i=st;i+hop<en;i+=hop){ var s=0; for(var j=i;j<i+hop;j+=2){ s+=d[j]*d[j]; } var e=Math.sqrt(s); env.push(e>prev ? e-prev : 0); prev=e; }
    var best=0, bb=0;
    for(var b=90;b<=180;b+=0.5){ var c=0; for(var m=1;m<=4;m*=2){ var lag=6000/b*m, li=Math.floor(lag), fr=lag-li; for(var k=0;k+li+1<env.length;k++){ c+=env[k]*(env[k+li]*(1-fr)+env[k+li+1]*fr)/m; } } if(c>best){ best=c; bb=b; } }
    while(bb>160) bb/=2; if(bb<100) bb*=1.5; return Math.round(bb);
  }
  /* sound fingerprint: 16-band spectral profile + zero-crossing rate, compared with our own demos */
  var SS_FP={"Intricate":{"p":[-0.37,-0.39,-0.96,-1.34,-2.07,-2.65,-3.03,-3.06,-3.62,-3.45,-3.4,-3.77,-4.08,-3.84,-3.94,-4.19],"z":1269},"Pitch":{"p":[-0.38,-0.47,-1.03,-1.06,-1.4,-2.05,-2.28,-2.57,-2.75,-3.18,-3.07,-3.31,-3.54,-3.88,-3.52,-3.56],"z":3954},"Infinity":{"p":[-0.3,-0.5,-1.07,-1.26,-1.67,-2.07,-2,-2.57,-3.15,-3.32,-3.48,-3.75,-3.84,-4.26,-4.52,-4.91],"z":1132},"Mutualism":{"p":[-0.43,-0.51,-0.79,-0.98,-1.43,-2.52,-2.03,-2.97,-3.12,-3.1,-3.45,-3.91,-4.16,-4.49,-4.04,-4.38],"z":1644},"Kratos":{"p":[-0.4,-0.49,-0.95,-1.05,-1.38,-1.57,-2.27,-2.77,-2.86,-3.18,-3.26,-3.35,-3.67,-3.7,-4.07,-3.51],"z":1596},"Protos":{"p":[-0.42,-0.52,-0.77,-1.21,-2.06,-2.24,-2.71,-1.33,-3.2,-1.62,-3.67,-4.07,-3.86,-3.42,-3.75,-3.98],"z":2447},"Melodic Techno Presets":{"p":[-0.42,-0.34,-1.28,-1.09,-1.9,-1.77,-2.44,-2.9,-3,-3.47,-3.55,-3.77,-4.09,-4.47,-4.23,-4.69],"z":1532},"Time Goes Fast Documentary Piano":{"p":[-2,-1.27,-0.69,-0.53,-0.87,-0.84,-1.32,-1.01,-2.3,-2.18,-2.52,-2.96,-4.75,-4.8,-4.64,-4.84],"z":1268}};
  function fpOf(buf){
    var sr=buf.sampleRate, d=buf.getChannelData(0), st=Math.min(Math.floor(d.length*0.15),sr*20), en=Math.min(d.length,st+sr*60);
    if(en-st < sr*8){ st=0; en=Math.min(d.length,sr*60); }
    var C=[45,63,90,125,180,250,355,500,710,1000,1400,2000,2800,4000,5600,8000], N=2048, band=[], co=[], w=[], i, n, q;
    for(q=0;q<C.length;q++){ band.push(0); co.push(2*Math.cos(2*Math.PI*C[q]/sr)); }
    for(n=0;n<N;n++) w.push(0.5-0.5*Math.cos(2*Math.PI*n/(N-1)));
    for(var o=st;o+N<=en;o+=8192){ for(q=0;q<C.length;q++){ var c=co[q], s1=0, s2=0; for(n=0;n<N;n++){ var s0=d[o+n]*w[n]+c*s1-s2; s2=s1; s1=s0; } band[q]+=Math.max(0,s1*s1+s2*s2-c*s1*s2); } }
    var tot=0; for(q=0;q<band.length;q++) tot+=band[q]; tot=tot||1;
    var prof=band.map(function(v){ return Math.round(Math.log(v/tot+1e-6)/Math.LN10*100)/100; });
    var zc=0; for(i=st+1;i<en;i++){ if((d[i]>=0)!==(d[i-1]>=0)) zc++; }
    return {p:prof, z:Math.round(zc/((en-st)/sr))||1};
  }
  function cmpFP(rf){
    var out={};
    for(var name in SS_FP){ var f=SS_FP[name], s=0; for(var q=0;q<rf.p.length;q++){ var x=rf.p[q]-f.p[q]; s+=x*x; } out[name]=Math.sqrt(s)+Math.abs(Math.log(rf.z/f.z)); }
    return out;
  }
  /* new products get their fingerprint automatically from their demo MP3 (cached in the browser) */
  function ensureFP(){
    return load().then(function(list){
      var need=list.filter(function(p){ return p.mp3 ? !(p.name in SS_FP) : false; }).slice(0,8);
      var AC=window.AudioContext || window.webkitAudioContext; if(!need.length ? true : !AC) return;
      var ctx=new AC();
      return Promise.all(need.map(function(p){
        var k='ssfp:'+p.mp3, c=null;
        try{ c=localStorage.getItem(k); }catch(e){}
        if(c){ try{ SS_FP[p.name]=JSON.parse(c); return; }catch(e){} }
        return fetch(p.mp3).then(function(r){ return r.arrayBuffer(); }).then(function(ab){
          return new Promise(function(ok,no){ var pr=ctx.decodeAudioData(ab,ok,no); if(pr ? pr.then : false) pr.then(ok,no); });
        }).then(function(b){ var f=fpOf(b); SS_FP[p.name]=f; try{ localStorage.setItem(k,JSON.stringify(f)); }catch(e){} }).catch(function(){});
      })).then(function(){ try{ ctx.close(); }catch(e){} });
    }).catch(function(){});
  }
  var picker=document.createElement('input'); picker.type='file'; picker.accept='.mp3,.wav,.aif,.aiff,.flac,.m4a,audio/mpeg,audio/mp3,audio/wav,audio/x-wav,audio/aiff,audio/flac,audio/mp4'; picker.style.display='none';
  document.body.appendChild(picker);
  function refFail(){ show('Reference track','<div style="color:#cbd5e1;padding:4px 2px 10px">Couldn’t read this file. Try an MP3 or WAV.</div><a href="https://solid-sound.net/en/free-demo-feedback/" style="display:block;padding:10px 12px;border-radius:12px;background:#1A2540;color:#cbd5e1;text-decoration:none;font-size:13px">🎧 Or send it for free feedback →</a>'); }
  function refFile(f){
    if(!f) return;
    show('Reference: '+f.name,'<div style="color:#94A3B8;padding:8px 2px">Listening to your track… detecting tempo</div>');
    try{ if(window.gtag) gtag('event','reference_match',{step:'upload'}); }catch(x){}
    var part=f.size > 40000000 ? f.slice(0,12000000) : f;
    var AC=window.AudioContext || window.webkitAudioContext; if(!AC){ refFail(); return; }
    var ctx=new AC();
    part.arrayBuffer().then(function(ab){
      return new Promise(function(ok,no){ var pr=ctx.decodeAudioData(ab,ok,no); if(pr ? pr.then : false) pr.then(ok,no); });
    }).then(function(buf){
      var b=tempo(buf), k='', rf=fpOf(buf); try{ ctx.close(); }catch(x){}
      /* the file is never uploaded: drop every reference to it right after the analysis */
      buf=null; part=null; f=null; picker.value='';
      try{ if(window.gtag) gtag('event','reference_match',{step:'done',bpm:b,key:k}); }catch(x){}
      var K=k ? k.charAt(0).toUpperCase()+k.slice(1) : '';
      ensureFP().then(function(){ window.ssRefScores=cmpFP(rf); window.ssAI('techno '+b+' bpm '+k,'Your reference: ~'+b+' BPM'+(K ? ' · '+K : '')); });
    }).catch(function(){ try{ ctx.close(); }catch(x){} refFail(); });
  }
  picker.addEventListener('change',function(){ refFile(picker.files[0]); });
  document.addEventListener('click',function(e){
    var b=e.target.closest ? e.target.closest('#ss-app header button') : null; if(!b) return;
    var isRef=b.getAttribute('aria-label')==='Match by reference track';
    if(isRef ? innerWidth < 761 : /Drop/.test(b.textContent||'')){ e.preventDefault(); e.stopPropagation(); picker.value=''; picker.click(); }
  },true);
  document.addEventListener('dragover',function(e){ if(e.target.closest ? e.target.closest('#ss-app header') : false) e.preventDefault(); });
  document.addEventListener('drop',function(e){ if(e.target.closest ? e.target.closest('#ss-app header') : false){ e.preventDefault(); var f=e.dataTransfer ? e.dataTransfer.files[0] : null; if(f) refFile(f); } });
  document.addEventListener('keydown',function(e){ if(e.key==='Escape') close(); });
  window.addEventListener('scroll',function(){ if(panel ? innerWidth>760 : false) close(); },{passive:true});
})();
</script>
<!-- /wp:html -->