/* other languages: translated label -> English (window.SS_EN comes with home-app-<lang>.js), so text matchers keep working */
window.ssT=function(s){var m=window.SS_TR||{};return m[s]||s;};
window.ssEN=function(s){s=String(s||'').trim();var m=window.SS_EN||{};return m[s]||s;};
(function(){
  /* arrived via the player's no-reload navigation: the theme header / site footer of the previous page are hidden by CSS
     (body.ss-newhome) instead of reloading, so the music keeps playing (owner 12.11.2026) */
  document.body.classList.add('ss-newhome');
  /* play icon = the triangle from the SD logo, same on every page */
  window.SS_TRI='M6.87 3.80L6.80 20.23L6.95 20.30L19.40 12.49L19.47 11.98L19.32 11.83L7.09 3.80ZM9.23 8.07L15.57 11.98L15.57 12.27L15.57 12.57L15.34 12.71L9.16 16.32L9.08 15.73L9.08 8.22Z';
  window.ssTri=function(sz){ sz=sz||18; return '<svg width="'+sz+'" height="'+sz+'" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" fill-rule="evenodd" d="'+window.SS_TRI+'"></path></svg>'; };
  /* one shared DOM watcher instead of timers: runs fixes once per frame, only when the page changes */
  if(!window.ssOnDom){ var ssQ=[], ssP=0; window.ssOnDom=function(f){ ssQ.push(f); try{ f(); }catch(x){} }; new MutationObserver(function(){ if(ssP) return; ssP=1; requestAnimationFrame(function(){ ssP=0; ssQ.forEach(function(f){ try{ f(); }catch(x){} }); }); }).observe(document.documentElement,{childList:true,subtree:true}); }
  window.ssOnDom(function(){ document.querySelectorAll('path[d="M8 5v14l11-7z"],path[d="M7 4l13 8-13 8z"]').forEach(function(p){ p.setAttribute('d',window.SS_TRI); p.setAttribute('fill-rule','evenodd'); }); });
  /* account links (Sign in, Favorites, Saved, Account) → My account: login with 3 tabs for guests,
     the client dashboard for clients, the Producer space for vendors (server decides) */
  window.ssOnDom(function(){ document.querySelectorAll('#ss-app a[href="#top"], #ss-app a[href="#shop"]').forEach(function(a){ var t=(ssEN(a.textContent)+' '+(a.getAttribute('aria-label')||'')).trim(); if(/Sign in|Favorites|Saved/i.test(t)) a.setAttribute('href','https://solid-sound.net/en/my-account/'+(/Favorites|Saved/i.test(t)?'#favourites':'')); }); });
  /* open dropdowns in the header close on any click outside them */
  document.addEventListener('click',function(e){
    var t=e.target; document.querySelectorAll('#ss-app header button[aria-expanded="true"]').forEach(function(b){
      if(b.getAttribute('aria-label')==='Main menu') return;
      var w=b.parentElement; if(w ? !w.contains(t) : false){ b.click(); }
    });
  },true);
  /* (12.11.2026) links from home now switch pages WITHOUT reload (music keeps playing); other pages build their own header */
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
      /* phones: no floating back-to-top (it covered text next to the chat bubble; Home is in the bottom bar) */
      t.style.display=(window.scrollY > 500 && window.innerWidth > 760) ? 'flex' : 'none';
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
    if(/^\s*Find\s*$/.test(ssEN(f.textContent))){ e.preventDefault(); ssSearch((document.getElementById('top-q')||{}).value); return; }
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
    if(b.closest('.ss-ab,.ssx')) return; /* examples and the first screen have their own players */
    var art=b.closest('article'); if(art && art.querySelector('.ss-pw .ssw-btn')) return; /* product cards play through the site player */
    /* the product cover must be in the same card: stop as soon as a parent holds more than one cover (= a list of cards) */
    var n=b, cov=null, lv=0; while(n && lv++<8){ if(n.querySelectorAll){ var cs=n.querySelectorAll('.ss-cov'); if(cs.length>1) break; if(cs.length===1){ cov=cs[0]; break; } } n=n.parentElement; }
    var name=cov?(cov.getAttribute('aria-label')||'').replace(/^Open /,''):'';
    if(!name){ if(/Pause|Stop/i.test(lab)){AU.pause();CUR='';} return; }
    /* demos of ranked products (window.SS_CATALOG, printed by the server); added here because the app data loads later */
    if(window.SS_CATALOG){ window.SS_MP3=window.SS_MP3||{}; window.SS_CATALOG.forEach(function(c){ if(c.mp3 && !window.SS_MP3[c.name]) window.SS_MP3[c.name]=c.mp3; }); }
    var key=null; for(var k in window.SS_MP3){ if(name.indexOf(k)===0) key=k; }
    if(!key) return;
    if(CUR===key){ AU.pause(); CUR=''; return; }
    var f=window.SS_MP3[key]; AU.src=/^https?:/.test(f) ? f : 'https://solid-sound.net/wp-content/uploads/'+f; AU.play(); CUR=key;
    try{if(window.gtag)gtag('event','play_demo',{item_name:key,source:'new_home'});}catch(x){}
  },true);
  var FREE='https://solid-sound.net/en/free/';
  document.addEventListener('click',function(e){
    if(!e.target.closest) return;
    var b=e.target.closest('#ss-app button,#ss-app a'); if(!b) return;
    var t=ssEN(b.textContent), form=b.closest('form'), mail=form?form.querySelector('input[type=email]'):null;
    if(mail?/pack|coupon|starter|demo|send|download/i.test(t):false){
      e.preventDefault(); var v=(mail.value||'').trim();
      if(!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(v)){mail.focus();mail.style.outline='2px solid #F59E0B';return;}
      try{if(window.gtag)gtag('event','generate_lead',{method:'new_home',cta:t});}catch(x){}
      location.href=FREE+'?email='+encodeURIComponent(v)+String.fromCharCode(38)+'src=new-home'; return;
    }
    if(/Upload demo|Drop or choose|Send for free feedback/i.test(t)){e.preventDefault();location.href=FREE;}
  },true);
})();
/* Account menu on the home app: "Sign in" (guest) / "My account" (logged in) opens a small menu */
(function(){
  var MYA='https://solid-sound.net/en/my-account/', VND='https://solid-sound.net/en/store-manager/';
  var R=window.ssRole||'guest', OUT=window.ssLogout||MYA+'customer-logout/';
  var P={user:'M16 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 21c1.5-4 4.5-6 8-6s6.5 2 8 6',sell:'M12 15V3m0 0L8 7m4-4l4 4M4 14v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5',
    bag:'M6 7h12l1 14H5zM9 7a3 3 0 0 1 6 0',dl:'M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2',doc:'M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9zM14 3v6h6',out:'M10 17l5-5-5-5M15 12H3M14 3h6v18h-6'};
  var client=[['My account','user',MYA],['Purchases','bag',MYA+'orders/'],['Downloads','dl',MYA+'downloads/'],['Settings','doc',MYA+'edit-account/']];
  var items=R==='guest' ? [['Log in','user',MYA+'?ss_tab=login'],['Create account','user',MYA+'?ss_tab=reg'],['Become a vendor','sell',MYA+'?ss_tab=sell']]
    : client.concat(R==='vendor' || R==='admin' ? [['Producer space','sell',VND]] : [['Start selling','sell','https://solid-sound.net/sell/']],[['Log out','out',OUT]]);
  var st=document.createElement('style');
  st.textContent='.ss-pop{position:fixed;z-index:2147483001;min-width:220px;padding:6px;background:#131B2E;border:1px solid rgba(255,255,255,.08);border-radius:14px;box-shadow:0 18px 40px rgba(0,0,0,.6);font-family:"Exo 2",system-ui,sans-serif}.ss-pop a{display:flex;align-items:center;gap:10px;padding:10px;border-radius:10px;color:#E5E7EB;font-weight:600;font-size:14px;text-decoration:none}.ss-pop a:hover{background:#1A2540;color:#fff}.ss-pop svg{color:#F59E0B;flex:none}';
  document.head.appendChild(st);
  var box=null;
  function close(){ if(box){ box.remove(); box=null; } }
  function open(a){
    close(); box=document.createElement('div'); box.id='ss-acc'; box.className='ss-pop'; box.setAttribute('role','menu');
    box.innerHTML=items.map(function(i){ return '<a role="menuitem" href="'+i[2]+'"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="'+P[i[1]]+'"></path></svg>'+ssT(i[0])+'</a>'; }).join('');
    document.body.appendChild(box);
    var r=a.getBoundingClientRect(), w=box.offsetWidth, h=box.offsetHeight;
    var left=Math.min(Math.max(8,r.right-w), innerWidth-w-8), top=r.bottom+6;
    if(top+h>innerHeight-8) top=Math.max(8,r.top-h-6); /* bottom bar on phones: open upwards */
    box.style.left=left+'px'; box.style.top=top+'px';
  }
  document.addEventListener('click',function(e){
    var a=e.target.closest ? e.target.closest('#ss-app a[href="#account"]') : null;
    if(a){ e.preventDefault(); e.stopPropagation(); box ? close() : open(a); return; }
    if(box && !box.contains(e.target)) close();
  },true);
  addEventListener('scroll',close,{passive:true}); addEventListener('resize',close,{passive:true});
  document.addEventListener('keydown',function(e){ if(e.key==='Escape') close(); });
})();
/* Language menu on the home app: the "EN" button lists every language of this page (Polylang hreflang links), as text */
(function(){
  var N={en:'English',uk:'Українська',de:'Deutsch',fr:'Français',es:'Español',it:'Italiano',pt:'Português',pl:'Polski',ru:'Русский'};
  function code(c){ c=(c||'').toLowerCase().slice(0,2); return c==='uk' ? 'UA' : c.toUpperCase(); }
  var L=[]; document.querySelectorAll('link[rel="alternate"][hreflang]').forEach(function(l){ var k=(l.getAttribute('hreflang')||'').toLowerCase().slice(0,2); if(!k||k==='x-'||L.some(function(x){ return x.k===k; })) return; L.push({k:k,h:l.href}); });
  var cur=code(document.documentElement.lang||'en');
  window.ssOnDom && ssOnDom(function(){ var b=document.querySelector('#ss-app button[aria-label^="Language"]'); if(b && b.textContent!==cur) b.textContent=cur; });
  if(L.length<2) return;
  var box=null;
  function close(){ if(box){ box.remove(); box=null; } }
  document.addEventListener('click',function(e){
    var b=e.target.closest ? e.target.closest('#ss-app button[aria-label^="Language"]') : null;
    if(b){
      e.preventDefault(); e.stopPropagation(); if(box){ close(); return; }
      box=document.createElement('div'); box.className='ss-pop'; box.setAttribute('role','menu');
      box.innerHTML=L.map(function(l){ var c=code(l.k); return '<a role="menuitem" hreflang="'+l.k+'" href="'+l.h+'"'+(c===cur?' style="background:#1A2540"':'')+'><b style="min-width:28px;color:#F59E0B;font-weight:800">'+c+'</b>'+(N[l.k]||c)+'</a>'; }).join('');
      document.body.appendChild(box);
      var r=b.getBoundingClientRect(), w=box.offsetWidth;
      box.style.left=Math.min(Math.max(8,r.right-w),innerWidth-w-8)+'px'; box.style.top=(r.bottom+6)+'px';
      return;
    }
    if(box && !box.contains(e.target)) close();
  },true);
  addEventListener('scroll',close,{passive:true}); addEventListener('resize',close,{passive:true});
})();
/* Precise scroll to a home section: its heading lands just under the sticky header, so the whole block fits */
function ssGoTo(sec){
  var h=sec.querySelector('h2') || sec, hdr=document.querySelector('#ss-app header'), bar=document.getElementById('wpadminbar');
  var off=(hdr ? hdr.getBoundingClientRect().height : 90) + (bar ? bar.offsetHeight : 0) + 14;
  var y=window.scrollY + h.getBoundingClientRect().top - off;
  try{ window.scrollTo({top:Math.max(0,y),behavior:'smooth'}); }catch(x){ window.scrollTo(0,Math.max(0,y)); }
}
window.ssGoTo=ssGoTo;
/* Home menu (desktop with a mouse): hover opens the dropdown; a click on "Products" / "Services" itself scrolls
   to that section of the home page; the dropdown items lead to the full pages. Touch screens keep tap = open. */
(function(){
  if(!(window.matchMedia && matchMedia('(hover:hover) and (pointer:fine)').matches)) return;
  var TARGET={Products:'shop',Services:'services'};
  function label(b){ var s=b.querySelector('span'); return s ? (s.textContent||'').trim() : ''; }
  function menuBtn(el){ return el && el.closest ? el.closest('#ss-app header nav[aria-label="Main"] button[aria-expanded]') : null; }
  /* real clicks on Products/Services: scroll instead of toggling (synthetic clicks from hover still toggle) */
  document.addEventListener('click',function(e){
    var b=menuBtn(e.target); if(!b || !e.isTrusted) return;
    var id=TARGET[label(b)]; if(!id) return;
    var sec=document.getElementById(id); if(!sec) return;
    e.preventDefault(); e.stopPropagation();
    if(b.getAttribute('aria-expanded')==='true') b.click();
    ssGoTo(sec);
  },true);
  /* hover opens / leaving closes (small delay so the pointer can travel into the dropdown) */
  var timer=null;
  document.addEventListener('mouseover',function(e){
    var nav=e.target.closest ? e.target.closest('#ss-app header nav[aria-label="Main"]') : null; if(!nav) return;
    /* the top-level item under the pointer (a link like Home/Wiki, or a dropdown wrapper) */
    var item=e.target; while(item && item.parentElement && !item.parentElement.matches('nav[aria-label="Main"], nav[aria-label="Main"] > sc-for, nav[aria-label="Main"] > div:not([style*="position: relative"])')) item=item.parentElement;
    var btn=item && item.querySelector ? item.querySelector('button[aria-expanded]') : null;
    if(btn && btn.closest('[role="menu"], div[style*="position: absolute"]')) btn=null;
    clearTimeout(timer);
    nav.querySelectorAll('button[aria-expanded="true"]').forEach(function(o){ if(o!==btn) o.click(); });
    if(btn && btn.getAttribute('aria-expanded')!=='true') btn.click();
  });
  document.addEventListener('mouseout',function(e){
    var nav=e.target.closest ? e.target.closest('#ss-app header nav[aria-label="Main"]') : null; if(!nav) return;
    if(e.relatedTarget && nav.contains(e.relatedTarget)) return;
    clearTimeout(timer);
    timer=setTimeout(function(){ nav.querySelectorAll('button[aria-expanded="true"]').forEach(function(o){ o.click(); }); },250);
  });
})();
/* Scroll-spy: while the products / services section is on screen, its menu item lights up (and Home dims).
   IntersectionObserver only — no scroll polling. */
(function(){
  if(!window.IntersectionObserver) return;
  var MAP={shop:'Products',services:'Services'}, cur='';
  var st=document.createElement('style');
  st.textContent='#ss-app header .ss-spy{color:#F59E0B!important}#ss-app header .ss-spy-off{color:#94A3B8!important}';
  document.head.appendChild(st);
  function paint(){
    var nav=document.querySelector('#ss-app header nav[aria-label="Main"]'); if(!nav) return;
    nav.querySelectorAll('button[aria-expanded]').forEach(function(b){ var s=b.querySelector('span'); var on=!!cur && s && ssEN(s.textContent)===MAP[cur]; b.classList.toggle('ss-spy',on); });
    var home=nav.querySelector('a[href$="/en/"],a[href$="/ua/"],a[href$="/de/"],a[href$="/fr/"],a[href$="/es/"],a[href$="/it/"],a[href$="/pt/"]'); if(home) home.classList.toggle('ss-spy-off',!!cur);
  }
  var seen={};
  var io=new IntersectionObserver(function(es){
    es.forEach(function(e){ seen[e.target.id]=e.isIntersecting; });
    var n=''; ['shop','services'].forEach(function(id){ if(seen[id]) n=n||id; });
    if(n!==cur){ cur=n; paint(); }
  },{rootMargin:'-40% 0px -50% 0px'});
  var watched={};
  (window.ssOnDom||function(f){f();})(function(){
    ['shop','services'].forEach(function(id){ var el=document.getElementById(id); if(el && watched[id]!==el){ watched[id]=el; io.observe(el); } });
    paint();
  });
})();
/* Logo = Home button with a press effect (same as on the other pages) */
(function(){
  var st=document.createElement('style');
  st.textContent='#ss-app header a[aria-label^="Solid Sound"]{-webkit-tap-highlight-color:transparent;cursor:pointer}#ss-app header a[aria-label^="Solid Sound"] svg{transform-origin:50% 60%;transition:transform .34s cubic-bezier(.34,1.8,.5,1),filter .22s}#ss-app header a.ss-press[aria-label^="Solid Sound"] svg{transform:scale(.86) translateY(1px);filter:brightness(.75);transition:transform .07s ease-out,filter .07s}';
  document.head.appendChild(st);
  function lg(t){ return t && t.closest ? t.closest('#ss-app header a[aria-label^="Solid Sound"]') : null; }
  document.addEventListener('pointerdown',function(e){ var a=lg(e.target); if(a) a.classList.add('ss-press'); },true);
  ['pointerup','pointercancel','pointerleave'].forEach(function(n){ document.addEventListener(n,function(e){ var a=lg(e.target); if(a) setTimeout(function(){ a.classList.remove('ss-press'); },60); },true); });
  document.addEventListener('click',function(e){
    var a=lg(e.target); if(!a || e.metaKey || e.ctrlKey) return;
    e.preventDefault(); a.classList.add('ss-press'); setTimeout(function(){ a.classList.remove('ss-press'); },110);
    setTimeout(function(){ try{ window.scrollTo({top:0,behavior:'smooth'}); }catch(x){ window.scrollTo(0,0); } },120);
  },true);
})();
/* Same precise scroll for in-page links to the products / services blocks (bottom bar Shop/Studio, buttons) */
document.addEventListener('click',function(e){
  var a=e.target.closest ? e.target.closest('#ss-app a[href="#shop"], #ss-app a[href="#services"], #ss-app a[href="#examples"], #ss-app a[href="#reviews"], #ss-app a[href="#dashboard"]') : null; if(!a || e.defaultPrevented) return;
  var sec=document.getElementById(a.getAttribute('href').slice(1)); if(!sec || !window.ssGoTo) return;
  e.preventDefault();
  /* close an open dropdown / menu sheet first, then scroll */
  document.querySelectorAll('#ss-app header button[aria-expanded="true"]').forEach(function(b){ b.click(); });
  window.ssGoTo(sec);
});
/* Arriving from another page with /en/#examples etc.: scroll once the block has rendered */
(function(){
  var id=(location.hash||'').slice(1); if(!/^(shop|services|examples|reviews|dashboard)$/.test(id)) return;
  var done=0;
  window.ssOnDom(function(){ if(done) return; var s=document.getElementById(id); if(!s || !s.offsetHeight || !window.ssGoTo) return; done=1; setTimeout(function(){ window.ssGoTo(s); },300); });
})();
/* Shopping assistant on the first screen: task list → the slider moves to that task's card.
   Event delegation (the block is rendered by the home app), choice remembered, swipe on phones. */
(function(){
  function el(){ return document.querySelector('#ss-app .ssx'); }
  function idx(b){ return +(b.getAttribute('data-i')||0); }
  function go(i, quiet){
    var r=el(); if(!r) return;
    var tabs=r.querySelectorAll('.tasks button'), cards=r.querySelectorAll('.card'), dots=r.querySelectorAll('.dots i');
    i=Math.max(0,Math.min(cards.length-1,i));
    r.querySelector('.track').style.transform='translateX('+(-100*i)+'%)';
    r.querySelector('.vp').style.height=cards[i].offsetHeight+'px';
    tabs.forEach(function(t,k){ t.classList.toggle('on',k===i); });
    dots.forEach(function(d,k){ d.classList.toggle('on',k===i); });
    r.setAttribute('data-cur',i);
    if(!quiet){ try{ localStorage.setItem('ss_task',i); }catch(e){} var tl=r.querySelector('.tasks'); if(tabs[i] && tl){ var L=tabs[i].offsetLeft-tl.offsetLeft, R=L+tabs[i].offsetWidth; if(L<tl.scrollLeft || R>tl.scrollLeft+tl.clientWidth) tl.scrollTo({left:Math.max(0,L-12),behavior:'smooth'}); }
      try{ if(window.gtag) gtag('event','select_task',{task:(tabs[i]||{}).textContent}); }catch(x){} }
  }
  document.addEventListener('click',function(e){
    var r=el(); if(!r || !r.contains(e.target)) return;
    var t=e.target.closest('.tasks button'); if(t){ e.preventDefault(); go(idx(t)); return; }
    var d=e.target.closest('.dots i'); if(d){ go([].indexOf.call(d.parentNode.children,d)); return; }
    var c=e.target.closest('.chips span'); if(!c || c.parentNode.classList.contains('fixed')) return;
    if(!c.parentNode.classList.contains('one')){ c.classList.toggle('on'); return; }
    [].forEach.call(c.parentNode.children,function(x){ x.classList.toggle('on',x===c); });
    var k=c.closest('.card'), h=c.getAttribute('data-h'); if(!k||!h) return;
    k.querySelectorAll('[data-link]').forEach(function(a){ a.href=h; });
    var ti=k.querySelector('[data-ti]'), pr=k.querySelector('[data-pr]');
    if(ti) ti.textContent=c.getAttribute('data-t'); if(pr) pr.textContent=c.getAttribute('data-p');
  });
  /* swipe (14.11.2026, Samsung A31 / iPhone): only a clearly sideways swipe changes the card — scrolling the page with a
     slightly diagonal finger used to flip it. Direction is decided on the first ~10 px of movement and then locked. */
  var x0=null, y0=0, dir='';
  document.addEventListener('touchstart',function(e){ var r=el(); x0=null; if(r && e.touches.length===1 && e.target.closest && e.target.closest('#ss-app .ssx .track') && !e.target.closest('.chips,input,select,textarea')){ x0=e.touches[0].clientX; y0=e.touches[0].clientY; dir=''; } },{passive:true});
  document.addEventListener('touchmove',function(e){ if(x0===null || dir) return; var dx=Math.abs(e.touches[0].clientX-x0), dy=Math.abs(e.touches[0].clientY-y0); if(dx<10 && dy<10) return; dir = dx>dy*1.5 ? 'x' : 'y'; },{passive:true});
  document.addEventListener('touchend',function(e){ if(x0===null) return; var t=e.changedTouches[0], dx=t.clientX-x0, dy=t.clientY-y0; x0=null; var r=el();
    if(r && dir==='x' && Math.abs(dx)>50 && Math.abs(dx)>Math.abs(dy)*1.5) go(+(r.getAttribute('data-cur')||0)+(dx<0?1:-1)); },{passive:true});
  document.addEventListener('touchcancel',function(){ x0=null; },{passive:true});
  var done=null;
  (window.ssOnDom||function(f){f();})(function(){ var r=el(); if(!r || r===done) return; done=r; var s=0; try{ s=+localStorage.getItem('ss_task')||0; }catch(e){} go(s,true); });
  var lastW=innerWidth; addEventListener('resize',function(){ if(innerWidth===lastW) return; lastW=innerWidth; var r=el(); if(r) go(+(r.getAttribute('data-cur')||0),true); },{passive:true});
  if('ResizeObserver' in window){ var ro=new ResizeObserver(function(){ var r=el(); if(!r) return; var i=+(r.getAttribute('data-cur')||0), c=r.querySelectorAll('.card')[i], vp=r.querySelector('.vp'); if(c && vp && Math.abs(vp.offsetHeight-c.offsetHeight)>2) vp.style.height=c.offsetHeight+'px'; });
    (window.ssOnDom||function(f){f();})(function(){ var r=el(); if(r && !r.__ro){ r.__ro=1; r.querySelectorAll('.card').forEach(function(c){ ro.observe(c); }); } }); }
})();

/* (12.11.2026) mixing examples: one audio file = BEFORE (0–7.5 s) + AFTER (7.5–15 s), drawn as a waveform.
   The switch jumps to the same moment in the other half; playback loops inside the chosen half. One shared <audio>. */
(function(){
  var CUT=7.5, au=null, cur=null, AFT={};
  function A(){ if(!au){ au=document.createElement('audio'); au.className='ss-abx'; au.preload='none'; document.body.appendChild(au);
    au.addEventListener('timeupdate',tick); au.addEventListener('play',ui); au.addEventListener('pause',ui); } return au; }
  function aft(b){ return b.classList.contains('aft'); }
  function svg(p,c){ var b=''; for(var i=0;i<p.length;i++){ var h=Math.max(2,(p.charCodeAt(i)-48)); b+='<rect x="'+(i*5)+'" y="'+((44-h)/2)+'" width="3" height="'+h+'" rx="1.5"/>'; }
    return 'url("data:image/svg+xml,'+encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '+(p.length*5)+' 44" preserveAspectRatio="none" fill="'+c+'">'+b+'</svg>')+'")'; }
  function paint(){ document.querySelectorAll('#ss-app .ss-ab').forEach(function(b){ var src=b.getAttribute('data-src'), sa=!!AFT[src];
    if(b.classList.contains('aft')!==sa) b.classList.toggle('aft',sa); var on=!!(au && !au.paused && au.getAttribute('src')===src); if(b.classList.contains('on')!==on) b.classList.toggle('on',on);
    var w=b.querySelector('.ss-abw'); if(!w || w.getAttribute('data-k')===b.getAttribute('data-src')) return;
    w.setAttribute('data-k',b.getAttribute('data-src'));
    [['b','pb'],['a','pa']].forEach(function(k){ var p=b.getAttribute('data-'+k[1])||''; var l=w.querySelectorAll('i.'+k[0]); if(l[0]) l[0].style.backgroundImage=svg(p,'#3A4766'); if(l[1]) l[1].style.backgroundImage=svg(p,'#F59E0B'); }); }); }
  (window.ssOnDom||function(f){f();})(paint);
  function ui(){ document.querySelectorAll('#ss-app .ss-ab').forEach(function(b){ var on=!!(au && !au.paused && au.getAttribute('src')===b.getAttribute('data-src')); b.classList.toggle('on', on); var btn=b.querySelector('.ss-abb'); if(btn){ var t=b.getAttribute('data-title')||'this example'; btn.setAttribute('aria-label',(on?'Pause ':'Play ')+t); btn.setAttribute('aria-pressed',on?'true':'false'); } }); }
  function tick(){ if(!cur||!cur.isConnected||cur.getAttribute('data-src')!==au.getAttribute('src')){ if(au&&!au.paused) au.pause(); return; } var t=au.currentTime, d=au.duration||CUT*2, a=aft(cur);
    if(a){ if(t<CUT-0.05) au.currentTime=t=CUT; else if(t>=d-0.25) au.currentTime=t=CUT; }
    else if(t>=CUT-0.1) au.currentTime=t=0;
    var p=Math.max(0,Math.min(100,((a?t-CUT:t)/CUT)*100)); var w=cur.querySelector('.ss-abw'); if(w){ w.style.setProperty('--p',p+'%'); w.setAttribute('aria-valuenow',Math.round(p)); } }
  function start(b,pos){ var x=A(), src=b.getAttribute('data-src');
    if(cur!==b){ if(cur){ var ow=cur.querySelector('.ss-abw'); if(ow) ow.style.setProperty('--p','0%'); } cur=b; if(x.getAttribute('src')!==src){ x.src=src; } }
    var go=function(){ x.currentTime=(aft(b)?CUT:0)+(pos||0); };
    if(x.readyState>=1) go(); else x.addEventListener('loadedmetadata',go,{once:true});
    var p=x.play(); if(p&&p.catch) p.catch(function(){}); }
  document.addEventListener('click',function(e){
    var t=e.target.closest && e.target.closest('#ss-app .ss-ab .ss-abb, #ss-app .ss-ab .ss-abs, #ss-app .ss-ab .ss-abw'); if(!t) return;
    var b=t.closest('.ss-ab'), x=A();
    if(t.classList.contains('ss-abb')){ if(cur===b && !x.paused){ x.pause(); return; } var keep=cur===b ? ((x.currentTime||0)%CUT) : 0; start(b,keep); return; }
    if(t.classList.contains('ss-abw')){ var r=t.getBoundingClientRect(); start(b, Math.max(0,Math.min(CUT-0.2,(e.clientX-r.left)/r.width*CUT))); return; }
    var a=!aft(b); AFT[b.getAttribute('data-src')]=a; b.classList.toggle('aft',a); t.setAttribute('aria-checked',a?'true':'false');
    if(cur===b && !x.paused){ var pos=(x.currentTime||0)%CUT; x.currentTime=(a?CUT:0)+pos; } else start(b,0);
  });
  /* when the home app re-renders and the card disappears, stop */
  document.addEventListener('ssp:navigated',function(){ if(au) au.pause(); });
})();
/* (12.11.2026) assistant BPM: slider and number box move together; type any BPM */
document.addEventListener('input',function(e){
  var r=e.target; if(!r.closest || !r.closest('#ss-app .ssx .rng')) return;
  var box=r.closest('.rng'), rg=box.querySelector('input[type=range]'), nb=box.querySelector('input[type=number]');
  if(r===rg && nb) nb.value=rg.value; else if(r===nb && rg && nb.value!=='') rg.value=Math.max(60,Math.min(200,+nb.value));
});
document.addEventListener('change',function(e){ var r=e.target; if(r.type!=='number' || !r.closest || !r.closest('#ss-app .ssx .rng')) return; if(r.value==='') return; r.value=Math.max(60,Math.min(200,Math.round(+r.value)||128)); });
(window.ssOnDom||function(f){f();})(function(){
  document.querySelectorAll('#ss-app .ssx .rng input[data-v]').forEach(function(r){ if(r.getAttribute('data-i')) return; r.setAttribute('data-i','1'); r.value=r.getAttribute('data-v'); });
});

/* (12.11.2026 owner) Best products: waveform players in the cards (site player, keeps playing across pages).
   The cover play button plays the same track through the site player. */
(function(){
  function rt(pid){ var R=window.SS_RT&&window.SS_RT.p; return R&&R[pid]; }
  function mount(){
    if(!window.SSP||!window.SSP.wave) return;
    document.querySelectorAll('#ss-app .ss-pw[data-pid]').forEach(function(h){
      var pid=h.getAttribute('data-pid'), d=rt(pid); if(!d||!d.s) { h.innerHTML=''; return; }
      var a=h.querySelector('audio');
      if(a && a.getAttribute('data-pid')!==pid){ h.innerHTML=''; a=null; }
      if(!a){ a=document.createElement('audio'); a.className='ss-cardau'; a.preload='none'; a.setAttribute('data-pid',pid); a.src=d.s; h.appendChild(a); }
      window.SSP.wave(a);
    });
  }
  (window.ssOnDom||function(f){f();})(mount);
  document.addEventListener('click',function(e){
    var b=e.target.closest && e.target.closest('#ss-app article button[aria-label^="Play "], #ss-app article button[aria-label^="Pause "]'); if(!b) return;
    var card=b.closest('article'), w=card && card.querySelector('.ss-pw .ssw-btn'); if(!w) return;
    e.preventDefault(); e.stopImmediatePropagation(); w.click();
  },true);
})();

/* (13.11.2026) Black Month −50% (until 30.11, see modules/sale.php): sale price on the first-screen offer */
(window.ssOnDom||function(f){f();})(function(){
  if(Date.now()>new Date('2026-11-30T23:59:59+01:00').getTime()) return;
  document.querySelectorAll('#ss-app .ssx [data-bm]').forEach(function(b){ if(b.textContent===b.getAttribute('data-bm')) return; b.textContent=b.getAttribute('data-bm'); var o=b.parentNode.querySelector('[data-bms]'); if(o) o.hidden=false; });
});

/* (13.11.2026, QA bot) Best products cover ▶ shows ⏸ while its track plays in the site player */
(function(){
  function upd(){
    var st=window.SSP&&SSP.state&&SSP.state.cur, on=!!(st && document.querySelector('#ssp.is-playing'));
    document.querySelectorAll('#ss-app article').forEach(function(card){
      var w=card.querySelector('.ss-pw .ssw'), b=card.querySelector('button[aria-label^="Play "],button[aria-label^="Pause "]'); if(!w||!b) return;
      var me=on && w.classList.contains('is-playing'); if(b.classList.contains('ss-on')===me) return;
      b.classList.toggle('ss-on',me); b.setAttribute('aria-pressed',me?'true':'false');
      b.setAttribute('aria-label',b.getAttribute('aria-label').replace(/^(Play|Pause) /,me?'Pause ':'Play '));
    });
  }
  document.addEventListener('ssp:state',function(){ setTimeout(upd,30); });
  (window.ssOnDom||function(f){f();})(upd);
})();

/* (13.11.2026 owner: "у нас покупают и услуги") the home "Add to cart" really adds the product to the WooCommerce cart
   (it used to only change the on-screen state). If "add mixing" is ticked, we send them to the studio calculator to order
   the service (mixing is not a shop product — it is quoted in the calculator). */
(function(){
  var S='https://solid-sound.net', nonce=null, adding=false;
  function getNonce(){ return fetch(S+'/wp-json/wc/store/v1/cart',{credentials:'include',headers:{'Accept':'application/json'}}).then(function(r){ nonce=r.headers.get('Nonce')||r.headers.get('X-WC-Store-API-Nonce')||nonce; return r.json(); }); }
  function badge(j){ try{ if(j&&typeof j.items_count==='number'){ localStorage.setItem('ss_cart_n',String(j.items_count)); if(window.ssCartPaint) ssCartPaint(j.items_count); } }catch(e){} }
  function addItem(pid){ return fetch(S+'/wp-json/wc/store/v1/cart/add-item',{method:'POST',credentials:'include',headers:{'Content-Type':'application/json','Accept':'application/json','Nonce':nonce||''},body:JSON.stringify({id:+pid,quantity:1})}).then(function(r){ return r.json(); }); }
  document.addEventListener('click',function(e){
    var btn=e.target.closest && e.target.closest('#ss-app .ss-realadd'); if(!btn) return;
    var pid=btn.getAttribute('data-pid'); if(!pid) return;
    var card=btn.closest('article'), mixBox=card&&card.querySelector('input[type=checkbox]:checked'); var wantMix=!!mixBox;
    if(adding) return; adding=true;
    (nonce?Promise.resolve():getNonce()).then(function(){ return addItem(pid); }).then(function(j){
      adding=false;
      if(j&&j.items_count!=null){ badge(j); }
      else if(j&&j.code){ /* nonce expired: refresh once and retry */ return getNonce().then(function(){ return addItem(pid); }).then(badge); }
      if(wantMix){ var cu=S+'/studio-calculator/?s=mixing'; setTimeout(function(){ if(!(window.sspGo&&window.sspGo(cu))) location.href=cu; },300); }
    }).catch(function(){ adding=false; });
  },true);
})();

/* (13.11.2026 owner: cart in the top-right on phones) on the home app, show the cart in the header (next to Sign in)
   and take it out of the bottom bar — same as the shop pages. */
(function(){
  var CART='https://solid-sound.net/en/cart/';
  function count(){ try{ return +localStorage.getItem('ss_cart_n')||0; }catch(e){ return 0; } }
  function sync(){
    if(innerWidth>=760) return;
    var signin=document.querySelector('#ss-app a[href="#account"], #ss-app a[aria-label="Account"], #ss-app a[aria-label="Sign in"], #ss-app a[aria-label="My account"]');
    var head=signin&&signin.parentNode;
    /* logged in: the orange "sign in" arrow becomes a person icon ("My account") */
    if(signin && window.ssRole && window.ssRole!=='guest' && !signin.ssMe){ signin.ssMe=1; signin.setAttribute('aria-label','My account'); signin.title='My account';
      signin.innerHTML='<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"></path></svg>'; }
    if(head && !head.querySelector('.ss-mh-cart')){
      var a=document.createElement('a'); a.href=CART; a.className='ss-mh-cart'; a.setAttribute('aria-label','Cart');
      a.style.cssText='position:relative;flex:none;order:3;width:40px;height:40px;border-radius:10px;border:1px solid rgba(255,255,255,0.12);background:#131B2E;color:#fff;display:flex;align-items:center;justify-content:center';
      a.innerHTML='<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"></path></svg><span class="ss-mh-n" style="position:absolute;top:-5px;right:-5px;min-width:16px;height:16px;padding:0 4px;border-radius:8px;background:#F59E0B;color:#111;font-size:10px;font-weight:800;line-height:16px;text-align:center;display:none"></span>';
      head.insertBefore(a,signin);
    }
    var n=count(), badge=head&&head.querySelector('.ss-mh-cart .ss-mh-n');
    if(badge){ if(n>0){ badge.textContent=String(n); badge.style.display=''; } else badge.style.display='none'; }
    /* take Cart out of the bottom bar on phones — only if the header cart is in place, so the cart never disappears */
    if(head && head.querySelector('.ss-mh-cart')){
      document.querySelectorAll('#ss-app nav a[href*="/cart/"]').forEach(function(x){
        var nav=x.closest('nav'); if(!nav) return; var r=nav.getBoundingClientRect();
        if(r.bottom>innerHeight-90 && /cart/i.test(ssEN(x.textContent))){ x.style.display='none'; }
      });
    }
  }
  (window.ssOnDom||function(f){f();})(sync);
  addEventListener('resize',sync,{passive:true});
  /* refresh the count from the real cart once */
  try{ if(window.fetch){ fetch('https://solid-sound.net/wp-json/wc/store/v1/cart',{credentials:'include'}).then(function(r){return r.json();}).then(function(j){ if(j&&j.items_count!=null){ try{localStorage.setItem('ss_cart_n',String(j.items_count));}catch(e){} sync(); } }).catch(function(){}); } }catch(e){}
})();

/* (14.11.2026 owner: "search as a button, main things on the right, like Rozetka") phone header on the home app:
   the search field hides behind a search button next to cart / account / menu; tapping it opens the full search
   panel under the header (AI, voice, reference — everything as before), the field gets focus; ✕ / Esc / outside tap closes. */
(function(){
  var ICON='<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>';
  var CLOSE='<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>';
  function parts(){
    var h=document.querySelector('#ss-app header'); if(!h) return null;
    var inp=h.querySelector('input[placeholder^="Ask"], input[type="search"], input[placeholder^="Search"]'); if(!inp) return null;
    var w=inp, v=h.querySelector('button[aria-label="Voice search"]');
    while(w.parentElement && w.parentElement!==h && !(v && w.contains(v))) w=w.parentElement;
    /* the search group = the child of the header row that holds the field */
    while(w.parentElement && w.parentElement!==h && w.parentElement.children.length<3) w=w.parentElement;
    return { h:h, w:w, inp:inp };
  }
  function setOpen(on){
    var p=parts(); if(!p) return; document.documentElement.classList.toggle('ss-msearch',on);
    var b=p.h.querySelector('.ss-mh-btn'); if(b){ b.setAttribute('aria-expanded',on?'true':'false'); b.innerHTML=on?CLOSE:ICON; b.setAttribute('aria-label',on?'Close search':'Search'); }
    if(on){ var r=p.h.getBoundingClientRect(); document.documentElement.style.setProperty('--ss-msrch-top',Math.max(8,Math.round(r.bottom)+6)+'px'); setTimeout(function(){ try{ p.inp.focus(); }catch(e){} },30); }
  }
  function sync(){
    var p=parts(); if(!p) return;
    if(innerWidth>=760){ p.w.classList.remove('ss-msrch-wrap'); var ob=p.h.querySelector('.ss-mh-btn'); if(ob) ob.remove(); document.documentElement.classList.remove('ss-msearch'); return; }
    if(!p.w.classList.contains('ss-msrch-wrap')) p.w.classList.add('ss-msrch-wrap');
    if(!p.h.querySelector('.ss-mh-btn')){
      var anchor=p.h.querySelector('.ss-mh-cart, a[aria-label="Account"], a[aria-label="Sign in"], a[aria-label="My account"], button[aria-label="Main menu"]');
      if(!anchor) return;
      var b=document.createElement('button'); b.type='button'; b.className='ss-mh-btn'; b.setAttribute('aria-label','Search'); b.setAttribute('aria-expanded','false'); b.innerHTML=ICON;
      b.addEventListener('click',function(e){ e.preventDefault(); setOpen(!document.documentElement.classList.contains('ss-msearch')); });
      anchor.parentNode.insertBefore(b,anchor);
    }
  }
  document.addEventListener('keydown',function(e){ if(e.key==='Escape' && document.documentElement.classList.contains('ss-msearch')) setOpen(false); });
  document.addEventListener('click',function(e){ if(!document.documentElement.classList.contains('ss-msearch')) return; var p=parts(); if(!p) return; if(p.w.contains(e.target) || e.target.closest('.ss-mh-btn')) return; setOpen(false); },true);
  (window.ssOnDom||function(f){f();})(sync);
  addEventListener('resize',sync,{passive:true});
})();

/* (14.11.2026 owner, Samsung A31 with a bigger system font) the shopping assistant must fit the phone screen above the
   bottom bar — for the tallest card. Real phones differ (browser bars, font size), so measure and tighten step by step. */
(function(){
  var LV=['fit1','fit2','fit3','fit4'];
  function fit(){
    var r=document.querySelector('#ss-app .ssx'); if(!r) return;
    LV.forEach(function(c){ r.classList.remove(c); });
    if(innerWidth>=760) return;
    var nav=document.querySelector('#ss-app nav[aria-label="App"]'), navH=nav ? nav.getBoundingClientRect().height : 64;
    var avail=innerHeight-navH-10;
    function need(){ var vp=r.querySelector('.vp'), cards=r.querySelectorAll('.card'); if(!vp||!cards.length) return 0; var max=0; cards.forEach(function(c){ max=Math.max(max,c.offsetHeight); });
      var top=r.getBoundingClientRect().top+scrollY; return top+r.offsetHeight-vp.offsetHeight+max; }
    for(var i=0;i<LV.length && need()>avail;i++) r.classList.add(LV[i]);
    /* keep the open card's height right after the change */
    var cur=+(r.getAttribute('data-cur')||0), c=r.querySelectorAll('.card')[cur], vp=r.querySelector('.vp'); if(c&&vp) vp.style.height=c.offsetHeight+'px';
  }
  var last=null; (window.ssOnDom||function(f){f();})(function(){ var r=document.querySelector('#ss-app .ssx'); if(r && r!==last){ last=r; fit(); setTimeout(fit,600); } });
  var lw=innerWidth, lh=innerHeight; addEventListener('resize',function(){ if(innerWidth!==lw || Math.abs(innerHeight-lh)>120){ lw=innerWidth; lh=innerHeight; fit(); } },{passive:true});
  if(document.fonts && document.fonts.ready) document.fonts.ready.then(fit);
  addEventListener('load',fit);
})();
