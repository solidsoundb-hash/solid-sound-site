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
        h+='<a href="https://solid-sound.net/studio-calculator/?s=mixing" style="display:flex;gap:10px;align-items:center;padding:12px;border-radius:12px;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.35);color:#fff;text-decoration:none;margin-bottom:8px"><span style="font-size:20px">🎚️</span><span style="flex:1"><b>Mixing + mastering by our engineer</b><br><span style="color:#94A3B8;font-size:12px">Get your price in 1 minute</span></span><span style="color:#F59E0B;font-weight:700">→</span></a>';
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
