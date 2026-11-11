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
  window.ssOnDom(function(){ document.querySelectorAll('#ss-app a[href="#top"], #ss-app a[href="#shop"], #ss-app a[href="#dashboard"]').forEach(function(a){ var t=((a.textContent||'')+' '+(a.getAttribute('aria-label')||'')).trim(); if(/Sign in|Favorites|Saved|Client dashboard|Account/i.test(t)) a.setAttribute('href','https://solid-sound.net/en/my-account/'+(/Favorites|Saved/i.test(t)?'#favourites':'')); }); });
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
