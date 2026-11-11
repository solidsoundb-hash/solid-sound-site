<?php exit; ?>
<!-- wp:html -->
<style>
#ssd{max-width:1180px;margin:0 auto;padding:28px 20px 60px;color:#E5E7EB;font-family:"Exo 2",system-ui,sans-serif;box-sizing:border-box}
#ssd *{box-sizing:border-box}
#ssd a{color:#F59E0B;text-decoration:none}
#ssd h1{margin:0;font-size:30px;font-weight:800;color:#fff;line-height:1.15}
#ssd h2{display:flex;align-items:center;gap:10px;margin:0 0 14px;font-size:19px;font-weight:800;color:#fff}
#ssd .t{flex:none;width:34px;height:34px;border-radius:10px;background:rgba(245,158,11,.14);display:flex;align-items:center;justify-content:center;color:#F59E0B}
#ssd .sub{margin:6px 0 0;color:#94A3B8;font-size:15px}
#ssd .top{display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:14px;margin-bottom:22px}
#ssd .stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-bottom:22px}
#ssd .st{background:#131B2E;border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:14px;box-shadow:0 10px 30px rgba(0,0,0,.35)}
#ssd .st b{display:block;font-size:24px;color:#fff}
#ssd .st span{color:#94A3B8;font-size:13px}
#ssd .grid{display:grid;grid-template-columns:minmax(0,1.6fr) minmax(0,1fr);gap:18px;align-items:start}
#ssd .p{background:#131B2E;border:1px solid rgba(255,255,255,.07);border-radius:16px;padding:18px;box-shadow:0 10px 30px rgba(0,0,0,.4),0 2px 8px rgba(0,0,0,.25);margin-bottom:18px}
#ssd .empty{color:#94A3B8;font-size:14px;line-height:1.5;padding:6px 0}
#ssd .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;height:42px;padding:0 16px;border-radius:10px;border:0;background:#F59E0B;color:#111!important;font-weight:700;font-size:14px;cursor:pointer;font-family:inherit}
#ssd .btn.g{background:#1A2540;color:#E5E7EB!important;border:1px solid rgba(255,255,255,.08)}
#ssd .proj{border:1px solid rgba(255,255,255,.07);border-radius:14px;background:#0F1626;padding:14px;margin-bottom:12px}
#ssd .ph{display:flex;flex-wrap:wrap;justify-content:space-between;gap:8px;margin-bottom:12px}
#ssd .ph b{color:#fff;font-size:16px}
#ssd .ph small{color:#94A3B8}
#ssd .badge{display:inline-flex;align-items:center;height:24px;padding:0 9px;border-radius:999px;font-size:12px;font-weight:700;background:rgba(52,211,153,.14);color:#34D399}
#ssd .badge.w{background:rgba(245,158,11,.14);color:#F59E0B}
#ssd .steps{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:6px;margin:0 0 12px;padding:0;list-style:none}
#ssd .steps li{font-size:12px;color:#64748B;text-align:center}
#ssd .steps li i{display:block;height:6px;border-radius:3px;background:#1E293B;margin-bottom:6px}
#ssd .steps li.on{color:#E5E7EB}#ssd .steps li.on i{background:#F59E0B}
#ssd .note{font-size:13px;color:#CBD5E1;border-left:2px solid rgba(245,158,11,.5);padding:2px 0 2px 10px;margin:8px 0}
#ssd .note small{display:block;color:#64748B;font-size:11px}
#ssd .rev{margin-top:12px;border-radius:12px;background:#090E17;box-shadow:inset 0 2px 5px rgba(0,0,0,.6);padding:12px}
#ssd .rv-h{display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:8px;font-size:13px;color:#94A3B8}
#ssd .rv-c{display:flex;align-items:center;gap:10px}
#ssd .rv-play{flex:none;width:44px;height:44px;border:0;background:transparent;color:#F59E0B;cursor:pointer;padding:0;display:flex;align-items:center;justify-content:center}
#ssd canvas{display:block;width:100%;height:64px;cursor:pointer;flex:1 1 auto;min-width:0}
#ssd .rv-t{font-variant-numeric:tabular-nums;color:#CBD5E1;font-size:12px;flex:none}
#ssd .cm{display:flex;gap:8px;margin-top:10px}
#ssd .cm input{flex:1 1 auto;min-width:0;height:42px;border-radius:10px;border:1px solid rgba(0,0,0,.4);background:#131B2E;color:#fff;padding:0 12px;font-size:15px;font-family:inherit}
#ssd .cms{list-style:none;margin:10px 0 0;padding:0}
#ssd .cms li{display:flex;gap:8px;align-items:center;font-size:13px;color:#E5E7EB;padding:6px 0;border-top:1px solid rgba(255,255,255,.05)}
#ssd .cms li b{color:#F59E0B;cursor:pointer;flex:none}
#ssd .cms li span{flex:1 1 auto}
#ssd .cms li button{flex:none;border:0;background:transparent;color:#64748B;cursor:pointer;font-size:16px}
#ssd .row{display:flex;align-items:center;gap:12px;padding:10px 0;border-top:1px solid rgba(255,255,255,.05)}
#ssd .row:first-of-type{border-top:0}
#ssd .row .cv{flex:none;width:48px;height:48px;border-radius:10px;background:#090E17 center/cover no-repeat}
#ssd .row .nm{flex:1 1 auto;min-width:0}
#ssd .row .nm b{display:block;color:#fff;font-size:14px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
#ssd .row .nm small{color:#94A3B8;font-size:12px}
#ssd .acts a{display:flex;align-items:center;gap:12px;padding:11px 0;border-top:1px solid rgba(255,255,255,.05);color:#E5E7EB}
#ssd .acts a:first-child{border-top:0}
#ssd .acts a svg{color:#F59E0B;flex:none}
#ssd .acts a span{flex:1}
#ssd .acts a small{display:block;color:#94A3B8;font-size:12px}
#ssd .guest{max-width:640px;margin:20px auto;text-align:center}
#ssd .guest ul{list-style:none;padding:0;margin:18px 0;text-align:left;display:grid;gap:10px}
#ssd .guest li{display:flex;gap:10px;align-items:flex-start;color:#CBD5E1;font-size:15px}
#ssd .guest li svg{flex:none;color:#F59E0B;margin-top:2px}
#ssd .sk{height:16px;border-radius:6px;background:linear-gradient(90deg,#1A2540,#22304F,#1A2540);background-size:200% 100%;animation:ssdSk 1.2s infinite;margin:10px 0}
@keyframes ssdSk{0%{background-position:0 0}100%{background-position:-200% 0}}
@media (max-width:900px){#ssd .grid{grid-template-columns:minmax(0,1fr)}#ssd .stats{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media (max-width:600px){#ssd{padding:18px 12px 40px}#ssd h1{font-size:24px}#ssd .steps li{font-size:10px}#ssd .p{padding:14px}#ssd .cm input{font-size:16px}}
</style>
<div id="ssd" aria-live="polite"><div class="sk" style="width:40%"></div><div class="sk"></div><div class="sk" style="width:70%"></div></div>
<script>
(function(){
  var D=document, root=D.getElementById('ssd'); if(!root) return;
  var S='https://solid-sound.net', L=S+'/en', ACC=L+'/my-account/';
  var TRI='M6.87 3.80L6.80 20.23L6.95 20.30L19.40 12.49L19.47 11.98L19.32 11.83L7.09 3.80ZM9.23 8.07L15.57 11.98L15.57 12.27L15.57 12.57L15.34 12.71L9.16 16.32L9.08 15.73L9.08 8.22Z';
  function ic(d,s){ s=s||18; return '<svg width="'+s+'" height="'+s+'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="'+d+'"></path></svg>'; }
  function tri(s){ return '<svg width="'+s+'" height="'+s+'" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" fill-rule="evenodd" d="'+TRI+'"></path></svg>'; }
  var PAUSE='<svg width="26" height="26" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M7 5h4v14H7zM13 5h4v14h-4z"></path></svg>';
  var I={dash:'M3 3h8v8H3zM13 3h8v5h-8zM13 10h8v11h-8zM3 13h8v8H3z',serv:'M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6',down:'M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2',fav:'M12 20s-7-4.4-9.2-8.6C1.2 8.2 3 4.5 6.6 4.5c2.1 0 3.4 1.2 5.4 3.2 2-2 3.3-3.2 5.4-3.2 3.6 0 5.4 3.7 3.8 6.9C19 15.6 12 20 12 20z',calc:'M6 2h12a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zM8 6h8v4H8zM8 14h.01M12 14h.01M16 14h.01M8 18h.01',chat:'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z',head:'M3 14v-2a9 9 0 0 1 18 0v2M3 14a2 2 0 0 1 2-2h1v7H5a2 2 0 0 1-2-2zM21 14a2 2 0 0 0-2-2h-1v7h1a2 2 0 0 0 2-2z',user:'M16 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 21c1.5-4 4.5-6 8-6s6.5 2 8 6',out:'M14 3h6v18h-6M10 17l-5-5 5-5M5 12h12',free:'M4.5 8h15A1.5 1.5 0 0 1 21 9.5v10a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 19.5v-10A1.5 1.5 0 0 1 4.5 8zM12 8v13M3 12h18',check:'M5 12.5l4.5 4.5L19 7.5',bag:'M6 7h12l1 14H5zM9 7a3 3 0 0 1 6 0'};
  function esc(s){ return String(s==null ? '' : s).replace(/[<>"]/g,''); }
  function mmss(t){ t=Math.max(0,Math.floor(t||0)); var m=Math.floor(t/60), s=t%60; return m+':'+(s<10 ? '0' : '')+s; }
  function head(title,icon){ return '<h2><span class="t">'+ic(icon,18)+'</span>'+title+'</h2>'; }
  function get(url){ return fetch(url,{credentials:'same-origin'}).then(function(r){ return r.text(); }).then(function(h){ return new DOMParser().parseFromString(h,'text/html'); }); }
  var logged=D.body.classList.contains('logged-in'), NL=String.fromCharCode(10);

  /* ---------- guest ---------- */
  if(!logged){
    root.innerHTML='<div class="guest p"><span class="t" style="margin:0 auto 12px;width:52px;height:52px">'+ic(I.dash,26)+'</span><h1>Client dashboard</h1><p class="sub">Everything about your music in one place.</p><ul>'+
      ['Track your mix and master: received, in work, revision ready, delivered','Listen to each revision and leave notes right on the waveform','Re-download every template and preset you bought','Your saved favourites and free packs']
      .map(function(t){ return '<li>'+ic(I.check,18)+'<span>'+t+'</span></li>'; }).join('')+
      '</ul><a class="btn" href="'+ACC+'">Sign in or create account</a></div>';
    return;
  }

  /* ---------- data ---------- */
  var SVC=/(mix|master|vocal|ghost|produc|remix|remake|post|stem|tuning|feedback|session|service|sound design|foley)/i;
  function parseOrders(doc){
    var rows=[].slice.call(doc.querySelectorAll('table.woocommerce-orders-table tbody tr, table.shop_table.my_account_orders tbody tr'));
    return rows.map(function(tr){
      var a=tr.querySelector('a[href*="view-order"]'), st=tr.querySelector('[data-title="Status"], .woocommerce-orders-table__cell-order-status'), dt=tr.querySelector('time'), tot=tr.querySelector('.woocommerce-orders-table__cell-order-total, [data-title="Total"]');
      return a ? {url:a.href, num:(a.textContent||'').replace(/[^0-9]/g,''), status:(st ? st.textContent : '').trim(), date:dt ? dt.textContent.trim() : '', total:tot ? tot.textContent.replace(/[ ]+/g,' ').trim().split(' for ')[0] : ''} : null;
    }).filter(Boolean);
  }
  function parseOrder(doc,o){
    o.items=[].slice.call(doc.querySelectorAll('.woocommerce-table--order-details tbody .product-name, .order_details tbody .product-name')).map(function(td){ var a=td.querySelector('a'); return (a ? a.textContent : td.textContent).trim(); });
    o.notes=[].slice.call(doc.querySelectorAll('.woocommerce-OrderUpdates li, ol.commentlist.notes li')).map(function(li){
      var d=li.querySelector('.woocommerce-OrderUpdate-description, .description'), m=li.querySelector('.woocommerce-OrderUpdate-meta, .meta');
      var links=[].slice.call((d||li).querySelectorAll('a')).map(function(a){ return a.href; });
      var txt=(d||li).textContent.trim();
      var urls=links.concat((txt.match(/https?:[^ ]+?[.](mp3|wav|m4a|flac)/ig)||[]));
      return {text:txt, date:m ? m.textContent.trim() : '', audio:urls.filter(function(u){ return /[.](mp3|wav|m4a|flac)([?#]|$)/i.test(u); })};
    });
    o.service=o.items.some(function(n){ return SVC.test(n); });
    return o;
  }
  function parseDownloads(doc){
    return [].slice.call(doc.querySelectorAll('table.woocommerce-table--order-downloads tbody tr, table.order_details tbody tr')).map(function(tr){
      var p=tr.querySelector('.download-product a, .download-product'), f=tr.querySelector('.download-file a, a.woocommerce-MyAccount-downloads-file'), r=tr.querySelector('.download-remaining'), e=tr.querySelector('.download-expires');
      return f ? {name:(p ? p.textContent : f.textContent).trim(), url:f.href, product:p ? p.href : '', left:r ? r.textContent.trim() : '', exp:e ? e.textContent.trim() : ''} : null;
    }).filter(Boolean);
  }

  /* ---------- project card ---------- */
  var STEPS=['Ordered','Received','In work','Revision','Delivered'];
  function stepOf(o){
    var s=o.status.toLowerCase(), rev=o.notes.some(function(n){ return n.audio.length; });
    if(/complet/.test(s)) return 4;
    if(rev) return 3;
    if(/process/.test(s)) return o.notes.length ? 2 : 1;
    return 0;
  }
  function projCard(o,k){
    var step=stepOf(o), done=step===4;
    var h='<div class="proj"><div class="ph"><div><b>'+esc(o.items.join(', ')||('Order #'+o.num))+'</b><br><small>Order #'+esc(o.num)+' · '+esc(o.date)+(o.total ? ' · '+esc(o.total) : '')+'</small></div><span class="badge'+(done ? '' : ' w')+'">'+STEPS[step]+'</span></div>';
    h+='<ol class="steps" aria-label="Project progress">'+STEPS.map(function(s,i){ return '<li class="'+(i<=step ? 'on' : '')+'"><i></i>'+s+'</li>'; }).join('')+'</ol>';
    var revs=[]; o.notes.slice().reverse().forEach(function(n){ n.audio.forEach(function(u){ revs.push({url:u, note:n}); }); });
    o.notes.slice(0,3).forEach(function(n){ h+='<div class="note">'+esc(n.text.replace(/https?:[^ ]+/g,'').trim()||'Update')+'<small>'+esc(n.date)+'</small></div>'; });
    if(revs.length){ var r=revs[revs.length-1]; h+=revBox(o, r.url, revs.length, k); }
    else if(!done){ h+='<div class="empty">Your engineer will post the first version here. You will also get an email.</div>'; }
    return h+'</div>';
  }
  function revBox(o,url,n,k){
    return '<div class="rev" data-k="'+k+'" data-url="'+esc(url)+'" data-order="'+esc(o.num)+'" data-rev="'+n+'"><div class="rv-h"><span>Revision '+n+' · tap the wave to jump, add notes at any moment</span><a href="'+esc(url)+'" download>Download</a></div>'+
      '<div class="rv-c"><button type="button" class="rv-play" aria-label="Play revision">'+tri(30)+'</button><canvas aria-label="Waveform"></canvas><span class="rv-t">0:00</span></div>'+
      '<div class="cm"><input type="text" maxlength="200" placeholder="Note at this moment, e.g. bass a bit lower"><button type="button" class="btn g rv-add">Add</button></div><ul class="cms"></ul>'+
      '<div style="margin-top:10px;text-align:right"><button type="button" class="btn rv-send">Send notes to engineer</button></div></div>';
  }

  /* ---------- revision player ---------- */
  var cur=null;
  function initRev(box){
    var url=box.getAttribute('data-url'), au=new Audio(); au.preload='metadata'; au.src=url;
    var cv=box.querySelector('canvas'), tt=box.querySelector('.rv-t'), pb=box.querySelector('.rv-play'), peaks=null;
    var key='ssd:'+url, notes=[]; try{ notes=JSON.parse(localStorage.getItem(key)||'[]'); }catch(e){}
    function save(){ try{ localStorage.setItem(key,JSON.stringify(notes)); }catch(e){} }
    function draw(){
      var w=cv.clientWidth, h=cv.clientHeight, dpr=window.devicePixelRatio||1; if(!w) return;
      cv.width=w*dpr; cv.height=h*dpr; var c=cv.getContext('2d'); c.scale(dpr,dpr); c.clearRect(0,0,w,h);
      var n=Math.max(40,Math.floor(w/3)), pr=au.duration ? au.currentTime/au.duration : 0;
      for(var i=0;i<n;i++){ var v=peaks ? peaks[Math.floor(i/n*peaks.length)] : 0.25+0.2*Math.abs(Math.sin(i*0.7)); var bh=Math.max(2,v*h*0.9); c.fillStyle=(i/n)<pr ? '#F59E0B' : '#334155'; c.fillRect(i*3,(h-bh)/2,2,bh); }
      if(au.duration){ c.fillStyle='#34D399'; notes.forEach(function(m){ c.fillRect(m.t/au.duration*w-1,0,2,h); }); }
      tt.textContent=mmss(au.currentTime)+(au.duration ? ' / '+mmss(au.duration) : '');
    }
    function list(){
      notes.sort(function(a,b){ return a.t-b.t; });
      box.querySelector('.cms').innerHTML=notes.map(function(m,i){ return '<li><b data-t="'+m.t+'">⚑ '+mmss(m.t)+'</b><span>'+esc(m.x)+'</span><button type="button" data-del="'+i+'" aria-label="Delete note">×</button></li>'; }).join('');
      draw();
    }
    fetch(url).then(function(r){ return r.arrayBuffer(); }).then(function(ab){
      var AC=window.AudioContext || window.webkitAudioContext; if(!AC) return; var ctx=new AC();
      return new Promise(function(ok,no){ var p=ctx.decodeAudioData(ab,ok,no); if(p ? p.then : false) p.then(ok,no); }).then(function(b){
        var d=b.getChannelData(0), N=600, step=Math.floor(d.length/N), out=[], mx=0;
        for(var i=0;i<N;i++){ var m=0; for(var j=i*step;j<(i+1)*step;j+=16){ var v=Math.abs(d[j]); if(v>m) m=v; } out.push(m); if(m>mx) mx=m; }
        peaks=out.map(function(v){ return mx ? v/mx : 0; }); try{ ctx.close(); }catch(e){} draw();
      });
    }).catch(function(){});
    au.addEventListener('loadedmetadata',draw); au.addEventListener('timeupdate',draw);
    au.addEventListener('ended',function(){ pb.innerHTML=tri(30); });
    pb.addEventListener('click',function(){
      if(cur ? cur!==au : false){ cur.pause(); }
      if(au.paused){ au.play(); cur=au; pb.innerHTML=PAUSE; try{ if(window.gtag) gtag('event','revision_play',{order:box.getAttribute('data-order')}); }catch(e){} }
      else { au.pause(); pb.innerHTML=tri(30); }
    });
    au.addEventListener('pause',function(){ pb.innerHTML=tri(30); });
    cv.addEventListener('click',function(e){ if(!au.duration) return; var r=cv.getBoundingClientRect(); au.currentTime=(e.clientX-r.left)/r.width*au.duration; draw(); });
    var inp=box.querySelector('.cm input');
    function add(){ var x=inp.value.trim(); if(!x) return; notes.push({t:Math.floor(au.currentTime||0), x:x}); inp.value=''; save(); list(); }
    box.querySelector('.rv-add').addEventListener('click',add);
    inp.addEventListener('keydown',function(e){ if(e.key==='Enter'){ e.preventDefault(); add(); } });
    box.querySelector('.cms').addEventListener('click',function(e){
      var t=e.target.closest('[data-t]'); if(t){ au.currentTime=+t.getAttribute('data-t'); draw(); return; }
      var d=e.target.closest('[data-del]'); if(d){ notes.splice(+d.getAttribute('data-del'),1); save(); list(); }
    });
    box.querySelector('.rv-send').addEventListener('click',function(){
      if(!notes.length){ inp.focus(); inp.placeholder='Add at least one note first'; return; }
      var msg='Order #'+box.getAttribute('data-order')+' · revision '+box.getAttribute('data-rev')+' notes:'+NL+notes.map(function(m){ return mmss(m.t)+' — '+m.x; }).join(NL);
      sendChat(msg);
      try{ if(window.gtag) gtag('event','revision_notes',{order:box.getAttribute('data-order'),count:notes.length}); }catch(e){}
    });
    window.addEventListener('resize',draw,{passive:true});
    list();
  }
  function sendChat(msg){
    var fab=D.querySelector('#ssb .ssb-fab, .ssb-fab, #ssb button');
    if(fab){ fab.click(); var n=0, iv=setInterval(function(){ var ta=D.querySelector('#ssb textarea, .ssb-in textarea'); n++; if(ta){ clearInterval(iv); ta.value=msg; ta.dispatchEvent(new Event('input',{bubbles:true})); ta.focus(); } if(n>30) clearInterval(iv); },150); }
    else { try{ navigator.clipboard.writeText(msg); }catch(e){} alertBox('Notes copied. Paste them in the chat or email to info@solid-sound.net'); }
  }
  function alertBox(t){ var d=D.createElement('div'); d.textContent=t; d.style.cssText='position:fixed;left:50%;bottom:90px;transform:translateX(-50%);z-index:99999;background:#F59E0B;color:#111;font-weight:700;padding:10px 14px;border-radius:10px;max-width:90vw'; D.body.appendChild(d); setTimeout(function(){ d.remove(); },4000); }

  /* ---------- render ---------- */
  function render(orders,downs,favs,name){
    var proj=orders.filter(function(o){ return o.service; }), active=proj.filter(function(o){ return stepOf(o)<4; });
    var h='<div class="top"><div><h1>Client dashboard</h1><p class="sub">'+(name ? 'Hi '+esc(name)+'! ' : '')+'Your projects, revisions and downloads.</p></div><a class="btn" href="'+S+'/Services-Calculator/">'+ic(I.calc,18)+'Order a mix or master</a></div>';
    h+='<div class="stats">'+[[active.length,'Active projects'],[proj.length-active.length,'Delivered'],[downs.length,'Downloads'],[favs.length,'Favourites']].map(function(s){ return '<div class="st"><b>'+s[0]+'</b><span>'+s[1]+'</span></div>'; }).join('')+'</div>';
    h+='<div class="grid"><div>';
    h+='<section class="p">'+head('My projects',I.serv);
    h+=proj.length ? proj.slice(0,6).map(projCard).join('') : '<div class="empty">No studio projects yet. Order mixing or mastering and follow every step here: received, in work, revision, delivered.</div><a class="btn" href="'+S+'/Services-Calculator/">Get a price in 1 minute</a>';
    h+='</section>';
    h+='<section class="p">'+head('My downloads',I.down);
    h+=downs.length ? downs.map(function(d){ return '<div class="row"><span class="t" style="width:48px;height:48px">'+ic(I.down,22)+'</span><div class="nm"><b>'+esc(d.name)+'</b><small>'+esc([d.left ? 'Downloads left: '+d.left : '', d.exp ? 'Expires: '+d.exp : ''].filter(Boolean).join(' · '))+'</small></div><a class="btn g" href="'+esc(d.url)+'">'+ic(I.down,16)+'Download</a></div>'; }).join('') : '<div class="empty">Templates, presets and packs you buy appear here, ready to download again anytime.</div><a class="btn g" href="'+L+'/all-products/">Browse products</a>';
    h+='</section></div><div>';
    h+='<section class="p">'+head('Favourites',I.fav);
    h+=favs.length ? favs.map(function(p){ return '<a class="row" href="'+esc(p.permalink)+'"><span class="cv" style="background-image:url('+esc((p.images[0]||{}).thumbnail||'')+')"></span><div class="nm"><b>'+esc(p.name)+'</b><small>'+esc(p.prices ? (p.prices.currency_symbol+Math.round(p.prices.price/Math.pow(10,p.prices.currency_minor_unit))) : '')+'</small></div></a>'; }).join('') : '<div class="empty">Tap the heart on any product to save it here.</div>';
    h+='</section>';
    h+='<section class="p acts">'+head('Quick actions',I.dash)+
      [[S+'/Services-Calculator/',I.calc,'Order mixing or mastering','Price in 1 minute'],[L+'/free-demo-feedback/',I.head,'Free feedback on your track','Pro notes from our engineer'],[L+'/free/',I.free,'Free packs','Templates and presets'],['#chat',I.chat,'Ask the team','We reply in minutes'],[ACC+'orders/',I.bag,'Orders and invoices','All purchases'],[ACC+'edit-account/',I.user,'Account details','Email and password'],[ACC+'customer-logout/',I.out,'Sign out','']]
      .map(function(a){ return '<a href="'+a[0]+'"'+(a[0]==='#chat' ? ' data-chat="1"' : '')+'>'+ic(a[1],20)+'<span>'+a[2]+(a[3] ? '<small>'+a[3]+'</small>' : '')+'</span></a>'; }).join('')+'</section>';
    h+='</div></div>';
    root.innerHTML=h;
    root.querySelectorAll('.rev').forEach(initRev);
    var c=root.querySelector('[data-chat]'); if(c) c.addEventListener('click',function(e){ e.preventDefault(); sendChat(''); });
    try{ if(window.gtag) gtag('event','dashboard_view',{projects:proj.length,downloads:downs.length}); }catch(e){}
  }

  var liked=[]; try{ liked=JSON.parse(localStorage.getItem('ss_liked')||'[]').map(String).filter(function(x){ return /^[0-9]+$/.test(x); }); }catch(e){}
  Promise.all([
    get(ACC+'orders/').then(function(doc){ var os=parseOrders(doc).slice(0,8); return Promise.all(os.map(function(o){ return get(o.url).then(function(d){ return parseOrder(d,o); }).catch(function(){ o.items=[]; o.notes=[]; o.service=false; return o; }); })); }).catch(function(){ return []; }),
    get(ACC+'downloads/').then(parseDownloads).catch(function(){ return []; }),
    liked.length ? fetch('/wp-json/wc/store/v1/products?include='+liked.slice(0,12).join(',')).then(function(r){ return r.json(); }).catch(function(){ return []; }) : Promise.resolve([]),
    get(ACC+'edit-account/').then(function(doc){ var f=doc.querySelector('#account_first_name, #account_display_name'); return f ? f.value : ''; }).catch(function(){ return ''; })
  ]).then(function(r){ render(r[0],r[1],Array.isArray(r[2]) ? r[2] : [],r[3]); });
})();
</script>
<!-- /wp:html -->