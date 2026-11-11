/* Text patches for the home app template, applied ONCE at build time by tools/build-home.mjs.
   Input: a[0] = template HTML, a[1] = runtime. Also defines window.SS_* data used by the patched template. */
module.exports = function (a, window) {
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
    /* (11.11.2026) the "Client dashboard" section on the home page stays: it explains the feature (owner) */
    /* account entry: "Sign in" for guests, "My account" after login; both open the account menu (home.js) */
    a[0]=a[0].split("mk('Sign in', ic.user, '#top')").join("mk(window.ssRole && window.ssRole !== 'guest' ? 'My account' : 'Sign in', ic.user, '#account')")
      .split("T('Sign in', ic.user, '#top')").join("T(window.ssRole && window.ssRole !== 'guest' ? 'My account' : 'Sign in', ic.user, '#account')")
      .split('<a href="#top" aria-label="Sign in"').join('<a href="#account" aria-label="Account"');
    /* (11.11.2026) left sidebar hidden for now (owner); flip showSide to true to bring it back */
    a[0]=a[0].split('isM: isM, isD: !isM,').join('isM: isM, isD: !isM, showSide: false,')
      .split('<sc-if value="{{isD}}" hint-placeholder-val="{{ true }}">\n<aside class="ss-side"').join('<sc-if value="{{showSide}}" hint-placeholder-val="{{ false }}">\n<aside class="ss-side"');
    /* (11.11.2026) the products section shows the best products (quality ranking), not the newest */
    a[0]=a[0].split('New products').join('Best products');
    /* (11.11.2026) Best products come from the quality ranking (window.SS_CATALOG, modules/ranking.php);
       the built-in list stays as a fallback */
    a[0]=a[0].split('var catalog = [').join('var catalog = (window.SS_CATALOG && window.SS_CATALOG.length >= 4) ? window.SS_CATALOG : [');
    /* (11.11.2026) hero slides move sideways like the All products carousel (was a fade in place) */
    a[0]=a[0].split("vis: on ? 'visibility: visible; opacity: 1; transform: none; transition: opacity .5s ease, transform .5s ease; z-index: 1' : 'visibility: hidden; opacity: 0; transform: translateY(8px); transition: opacity .3s ease, transform .3s ease, visibility 0s linear .3s; z-index: 0',").join("vis: on ? 'visibility: visible; opacity: 1; transform: translateX(0); transition: transform .6s cubic-bezier(.22,.61,.36,1), opacity .45s ease; z-index: 1' : 'visibility: hidden; opacity: 0; transform: translateX(' + (i < s.slide ? '-' : '') + '60%); transition: transform .6s cubic-bezier(.22,.61,.36,1), opacity .45s ease, visibility 0s linear .6s; z-index: 0',");
    a[0]=a[0].split('<div style="display: grid">\n    <sc-for list="{{allSlides}}"').join('<div style="display: grid; overflow: hidden; padding: 14px 18px; margin: -14px -18px">\n    <sc-for list="{{allSlides}}"'); /* clip the sliding slides */
    /* (11.11.2026) the seasonal slide (Black Month) shows only while the sale runs; slider adapts to the slide count */
    var SALE_END = '2026-11-30T23:59:59+01:00';
    a[0]=a[0].split("cardHref: '#shop' }\n    ];").join("cardHref: '#shop' }\n    ];\n    if (Date.now() > new Date('" + SALE_END + "').getTime()) slides = slides.filter(function (x) { return !x.timer; });\n    window.SS_NSLIDES = slides.length;")
      .split('self.setState({ slide: (st.slide + 1) % 3 })').join('self.setState({ slide: (st.slide + 1) % (window.SS_NSLIDES || 3) })')
      .split('slide: (i + 3) % 3,').join('slide: (i + (window.SS_NSLIDES || 3)) % (window.SS_NSLIDES || 3),')
      .split("' / 03'").join("' / 0' + (window.SS_NSLIDES || 3)")
      .split("' of 3'").join("' of ' + (window.SS_NSLIDES || 3)");
    /* (11.11.2026) first screen = shopping assistant ("What are you working on?") instead of the promo slider.
       The slider stays in the template, hidden (display: none), as a saved component. */
    var ASSIST = require('fs').readFileSync(require('path').join(__dirname, 'parts', 'assistant.html'), 'utf8');
    var HERO = '<div style="max-width: 1200px; margin: 0 auto; padding: 0 {{side}}">\n   <div style="background: #131B2E; border: 1px solid rgba(255,255,255,0.08); border-radius: 18px; box-shadow: 0 10px 30px rgba(0,0,0,0.5), 0 2px 8px rgba(0,0,0,0.3); padding: {{heroBoxPad}}">';
    if (a[0].split(HERO).length !== 2) throw new Error('home: hero wrapper not found');
    a[0]=a[0].split(HERO).join(ASSIST + '\n' + HERO.replace('<div style="max-width: 1200px;', '<div style="display: none; max-width: 1200px;'));
    /* (11.11.2026) Services menu: Examples / Reviews / How it works scroll to their home blocks (owner) */
    var SV_OLD = "href: 'https://solid-sound.net/mastering-album-calculator/' }],";
    if (a[0].split(SV_OLD).length !== 2) throw new Error('home: services menu not found');
    a[0]=a[0].split(SV_OLD).join("href: 'https://solid-sound.net/mastering-album-calculator/' }, { label: 'Examples', icon: ic.sliders, href: '#examples' }, { label: 'Reviews', icon: ic.chat, href: '#reviews' }, { label: 'How it works', icon: ic.layers, href: '#dashboard' }],");
    var SH_OLD = "T('Album calculator', ic.disc, menus.services[2].href), ";
    if (a[0].split(SH_OLD).length !== 2) throw new Error('home: services sheet not found');
    a[0]=a[0].split(SH_OLD).join(SH_OLD + "T('Examples', ic.sliders, '#examples'), T('Reviews', ic.chat, '#reviews'), T('How it works', ic.layers, '#dashboard'), ");
  return a;
};
