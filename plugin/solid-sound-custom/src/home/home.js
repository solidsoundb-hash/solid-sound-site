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
  /* account links (Sign in, Favorites, Saved, Account) → My account: login with 3 tabs for guests,
     the client dashboard for clients, the Producer space for vendors (server decides) */
  window.ssOnDom(function(){ document.querySelectorAll('#ss-app a[href="#top"], #ss-app a[href="#shop"]').forEach(function(a){ var t=((a.textContent||'')+' '+(a.getAttribute('aria-label')||'')).trim(); if(/Sign in|Favorites|Saved/i.test(t)) a.setAttribute('href','https://solid-sound.net/en/my-account/'+(/Favorites|Saved/i.test(t)?'#favourites':'')); }); });
  /* open dropdowns in the header close on any click outside them */
  document.addEventListener('click',function(e){
    var t=e.target; document.querySelectorAll('#ss-app header button[aria-expanded="true"]').forEach(function(b){
      if(b.getAttribute('aria-label')==='Main menu') return;
      var w=b.parentElement; if(w ? !w.contains(t) : false){ b.click(); }
    });
  },true);
  /* links from the new home always do a full page load, so other pages get their own header */
  document.addEventListener('click',function(e){ var a=e.target.closest ? e.target.closest('a') : null; if(a) a.classList.add('no-ajax'); },true);
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
/* Account menu on the home app: "Sign in" (guest) / "My account" (logged in) opens a small menu */
(function(){
  var MYA='https://solid-sound.net/en/my-account/', VND='https://solid-sound.net/en/store-manager/';
  var R=window.ssRole||'guest', OUT=window.ssLogout||MYA+'customer-logout/';
  var P={user:'M16 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 21c1.5-4 4.5-6 8-6s6.5 2 8 6',sell:'M12 15V3m0 0L8 7m4-4l4 4M4 14v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5',
    bag:'M6 7h12l1 14H5zM9 7a3 3 0 0 1 6 0',dl:'M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2',doc:'M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9zM14 3v6h6',out:'M10 17l5-5-5-5M15 12H3M14 3h6v18h-6'};
  var client=[['My account','user',MYA],['Purchases','bag',MYA+'orders/'],['Downloads','dl',MYA+'downloads/'],['Settings','doc',MYA+'edit-account/']];
  var items=R==='guest' ? [['Log in','user',MYA+'?ss_tab=login'],['Create account','user',MYA+'?ss_tab=reg'],['Become a vendor','sell',MYA+'?ss_tab=sell']]
    : R==='vendor' ? [['Producer space','sell',VND],['Log out','out',OUT]]
    : (R==='admin' ? [['Producer space','sell',VND]] : []).concat(client,[['Log out','out',OUT]]);
  var st=document.createElement('style');
  st.textContent='.ss-pop{position:fixed;z-index:2147483001;min-width:220px;padding:6px;background:#131B2E;border:1px solid rgba(255,255,255,.08);border-radius:14px;box-shadow:0 18px 40px rgba(0,0,0,.6);font-family:"Exo 2",system-ui,sans-serif}.ss-pop a{display:flex;align-items:center;gap:10px;padding:10px;border-radius:10px;color:#E5E7EB;font-weight:600;font-size:14px;text-decoration:none}.ss-pop a:hover{background:#1A2540;color:#fff}.ss-pop svg{color:#F59E0B;flex:none}';
  document.head.appendChild(st);
  var box=null;
  function close(){ if(box){ box.remove(); box=null; } }
  function open(a){
    close(); box=document.createElement('div'); box.id='ss-acc'; box.className='ss-pop'; box.setAttribute('role','menu');
    box.innerHTML=items.map(function(i){ return '<a role="menuitem" href="'+i[2]+'"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="'+P[i[1]]+'"></path></svg>'+i[0]+'</a>'; }).join('');
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
    try{ sec.scrollIntoView({behavior:'smooth',block:'start'}); }catch(x){ sec.scrollIntoView(); }
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
    nav.querySelectorAll('button[aria-expanded]').forEach(function(b){ var s=b.querySelector('span'); var on=!!cur && s && s.textContent.trim()===MAP[cur]; b.classList.toggle('ss-spy',on); });
    var home=nav.querySelector('a[href$="/en/"]'); if(home) home.classList.toggle('ss-spy-off',!!cur);
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
