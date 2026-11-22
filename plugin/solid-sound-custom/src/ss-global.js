/* Solid Sound global header (same menu as the new home), mobile bottom bar + menu sheet, AI search, voice, reference match, triangle play icons */
(function(){
if(window.__ssgS) return; window.__ssgS=window.__ssg=1; /* __ssg also stops the old v7 loader in the footer block */
var D=document, B=D.body, LOGO='M169 2L168 225L170 226L339 120L340 113L338 111L172 2ZM201 60L287 113L287 117L287 121L284 123L200 172L199 164L199 62ZM153 3L140 3L130 19L127 21L120 15L110 10L92 2L83 1L55 2L48 4L34 11L26 18L16 28L11 39L7 53L6 76L12 94L18 104L26 112L34 118L45 123L66 130L109 141L120 146L128 154L132 162L133 181L130 188L126 196L120 203L110 209L104 211L91 213L70 211L62 208L52 202L46 198L37 188L26 170L17 147L4 146L2 148L2 224L4 226L15 226L27 210L30 207L50 219L67 225L100 226L109 225L129 217L141 208L152 197L158 185L163 170L163 139L156 123L148 114L140 108L126 100L102 92L68 84L53 79L46 74L40 66L36 59L35 46L36 38L41 30L47 24L54 20L62 16L72 14L88 16L104 24L110 28L120 38L129 53L138 74L140 78L152 79L153 78Z';
var TRI='M6.87 3.80L6.80 20.23L6.95 20.30L19.40 12.49L19.47 11.98L19.32 11.83L7.09 3.80ZM9.23 8.07L15.57 11.98L15.57 12.27L15.57 12.57L15.34 12.71L9.16 16.32L9.08 15.73L9.08 8.22Z';
var LT='\x3c', GT='>';
function tag(t,attrs,inner){ var s=LT+t; for(var k in attrs){ s+=' '+k+'="'+attrs[k]+'"'; } return s+GT+(inner||'')+LT+'/'+t+GT; }
function ico(d,sz){ return tag('svg',{width:sz||22,height:sz||22,viewBox:'0 0 24 24',fill:'none',stroke:'currentColor','stroke-width':'1.8','stroke-linecap':'round','stroke-linejoin':'round','aria-hidden':'true'},tag('path',{d:d})); }
function triIcon(sz){ return tag('svg',{width:sz||18,height:sz||18,viewBox:'0 0 24 24','aria-hidden':'true'},tag('path',{d:TRI,fill:'currentColor','fill-rule':'evenodd'})); }
window.ssTri=triIcon;
var I={
  home:'M3 11l9-7 9 7v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z',
  prod:'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z',
  serv:'M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6',
  wiki:'M4 4h12a4 4 0 0 1 4 4v12H8a4 4 0 0 1-4-4zM8 9h8M8 13h6',
  faq:'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18zM9.5 9a2.5 2.5 0 1 1 3.5 2.3c-.6.3-1 .9-1 1.6V14M12 17h.01',
  free:'M4.5 8h15A1.5 1.5 0 0 1 21 9.5v10a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 19.5v-10A1.5 1.5 0 0 1 4.5 8zM12 8v13M3 12h18M12 8c-2-4-6-4-6-1s6 1 6 1zm0 0c2-4 6-4 6-1s-6 1-6 1z',
  sell:'M12 15V3m0 0L8 7m4-4l4 4M4 14v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5',
  fav:'M12 20s-7-4.4-9.2-8.6C1.2 8.2 3 4.5 6.6 4.5c2.1 0 3.4 1.2 5.4 3.2 2-2 3.3-3.2 5.4-3.2 3.6 0 5.4 3.7 3.8 6.9C19 15.6 12 20 12 20z',
  cart:'M3 4h2l2.4 11h11.2L21 8H7M10.5 20a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zM19.5 20a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z',
  user:'M16 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 21c1.5-4 4.5-6 8-6s6.5 2 8 6',
  signin:'M10 17l5-5-5-5M15 12H3M14 3h6v18h-6',
  menu:'M4 7h16M4 12h16M4 17h16', close:'M6 6l12 12M18 6L6 18',
  search:'M11 18a7 7 0 1 0 0-14 7 7 0 0 0 0 14zM20 20l-4-4',
  mic:'M12 3a3 3 0 0 0-3 3v6a3 3 0 0 0 6 0V6a3 3 0 0 0-3-3zM5 11a7 7 0 0 0 14 0M12 18v3M8 21h8',
  wave:'M3 12h2M7 8v8M11 5v14M15 9v6M19 7v10M21 12h0',
  layers:'M12 3l9 5-9 5-9-5zM3 13l9 5 9-5M3 17l9 5 9-5',
  knob:'M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0zM12 12l4-4M12 3v2M3 12h2M19 12h2',
  note:'M9 18V5l12-2v13M9 18a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM21 16a3 3 0 1 1-6 0 3 3 0 0 1 6 0z',
  tag:'M3 12V4a1 1 0 0 1 1-1h8l9 9-9 9zM8 8h.01',
  calc:'M6 2h12a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zM8 6h8v4H8zM8 14h.01M12 14h.01M16 14h.01M8 18h.01',
  disc:'M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0zM15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z',
  chat:'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z',
  doc:'M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9zM14 3v6h6M8 13h8M8 17h6',
  forum:'M4 5h16v11H9l-5 4z',
  down:'M6 9l6 6 6-6',
  dl:'M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2',
  /* same icons as the home app (src/home/app.template.html, var ic) */
  grid:'M4.5 3h4A1.5 1.5 0 0 1 10 4.5v4A1.5 1.5 0 0 1 8.5 10h-4A1.5 1.5 0 0 1 3 8.5v-4A1.5 1.5 0 0 1 4.5 3zM15.5 3h4A1.5 1.5 0 0 1 21 4.5v4a1.5 1.5 0 0 1-1.5 1.5h-4A1.5 1.5 0 0 1 14 8.5v-4A1.5 1.5 0 0 1 15.5 3zM4.5 14h4a1.5 1.5 0 0 1 1.5 1.5v4A1.5 1.5 0 0 1 8.5 21h-4A1.5 1.5 0 0 1 3 19.5v-4A1.5 1.5 0 0 1 4.5 14zM15.5 14h4a1.5 1.5 0 0 1 1.5 1.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a1.5 1.5 0 0 1-1.5-1.5v-4a1.5 1.5 0 0 1 1.5-1.5z',
  mail:'M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zM3 7l9 6 9-6',
  refund:'M3 12a9 9 0 1 0 3-6.7L3 8M3 3v5h5',
  shield:'M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z',
  lock:'M6 11h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1zM8 11V7a4 4 0 0 1 8 0v4',
  scale:'M12 3v18M7 21h10M5 7h14M5 7l-3 7a3 3 0 0 0 6 0zM19 7l-3 7a3 3 0 0 0 6 0z',
  globe:'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18zM3 12h18M12 3c2.5 2.7 3.8 5.7 3.8 9s-1.3 6.3-3.8 9c-2.5-2.7-3.8-5.7-3.8-9S9.5 5.7 12 3z'
};
var S='https://solid-sound.net', L=S+'/en';
/* (13.11.2026 owner RULE) a list right after a heading with "checklist" gets the check icons too */
function ssChecklists(){ D.querySelectorAll('h2,h3,h4').forEach(function(h){ if(!/check ?-?list|чек-?лист|контрольн/i.test(h.textContent)) return; var n=h.nextElementSibling; while(n && n.tagName==='P' && !n.textContent.trim()) n=n.nextElementSibling; if(n && (n.tagName==='UL'||n.tagName==='OL') && !n.classList.contains('check')) n.classList.add('check'); }); 
  /* each item gets an icon by meaning (owner: icons, not identical ticks) */
  var CI={sliders:'M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6',angle:'M4 20h16M4 20L16 6M9 20a6 6 0 0 0-1.6-4.1',ruler:'M3 17L17 3l4 4L7 21zM7 13l2 2M10 10l2 2M13 7l2 2',mic:'M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3zM19 10v2a7 7 0 0 1-14 0v-2M12 19v3M8 22h8',lock:'M6 11h12v10H6zM8 11V7a4 4 0 0 1 8 0v4M12 15v2',home:'M3 11l9-8 9 8M5 10v10h14V10M10 20v-6h4v6',wave:'M2 12h2M6 8v8M10 5v14M14 8v8M18 10v4M22 12h-2',bolt:'M13 2L4 14h7l-1 8 9-12h-7z',file:'M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9zM14 3v6h6',cart:'M3 4h2l2.4 10.2a1 1 0 0 0 1 .8h8.9a1 1 0 0 0 1-.8L20 8H6.2',check:'M4 4h16v16H4zM8 12l3 3 5-6'};
  var CW=[[/same position|every take|spot|floor|shock mount|tight/i,'lock'],[/°|turn|angle|beside|lip level/i,'angle'],[/back of the mic|pop filter/i,'mic'],[/gain|peak|dbfs|level|eq\b|compress|loud/i,'sliders'],[/\bcm\b|distance|close/i,'ruler'],[/pop filter|back of the mic|\bmic|microphone|vocal/i,'mic'],[/spot|floor|same position|every take|shock mount|tight/i,'lock'],[/room|soft|behind you|noise|blanket/i,'home'],[/headphone|listen|monitor|reference|stems?\b|wav|mp3/i,'wave'],[/export|save|backup|bounce|render/i,'bolt'],[/file|name|folder|label|license|pdf/i,'file'],[/order|cart|pay|price|buy/i,'cart']];
  D.querySelectorAll('ul.check:not(.check-ic) > li').forEach(function(li){ if(li.querySelector(':scope > .ssg-ci')) return; var t=li.textContent, k='check'; for(var i=0;i<CW.length;i++){ if(CW[i][0].test(t)){ k=CW[i][1]; break; } }
    var sp=D.createElement('span'); sp.className='ssg-ci'; sp.setAttribute('aria-hidden','true'); sp.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="'+CI[k]+'"/></svg>'; var ct=D.createElement('span'); ct.className='ct'; while(li.firstChild) ct.appendChild(li.firstChild); li.appendChild(sp); li.appendChild(ct); });
}
(window.ssOnDom||function(f){f();})(ssChecklists); D.addEventListener('ssp:navigated',ssChecklists);
/* (13.11.2026) menu in the visitor's language: translated pages (map printed by Polylang code in custom.php) and the
   shop pages that work under a language prefix (/de/product/…, /de/cart/ …); everything else stays English */
var LG=(location.pathname.match(/^[/](ua|de|fr|pt|es|it)(?=[/]|$)/)||[])[1]||'';
function tr(h){ if(!LG||!h) return h; var M=window.ssTrMap||{}; var k=h.split('#')[0].replace(/[/]$/,''), hs=h.indexOf('#')>-1 ? h.slice(h.indexOf('#')) : '';
  if(M[k]) return M[k]+hs; if(M[S+k.replace(/^https?:[/][/][^/]+/,'')]) return M[S+k.replace(/^https?:[/][/][^/]+/,'')]+hs;
  var p=k.replace(/^https?:[/][/][^/]+/,''); if(/^[/](en[/])?(product|product-category|shop|cart|checkout|my-account)([/]|$)/.test(p)) return S+'/'+LG+p.replace(/^[/]en/,'')+'/'+hs;
  /* (22.11) pages translated on the server (modules/i18n-pages.php) — only languages switched on there (window.ssVPL) */
  if((window.ssVPL||[]).indexOf(LG)>-1 && /^[/](en[/])?(all-products|services-prices|studio-calculator|services-calculator|mastering-album-calculator|free|sell|faq|why-solid-sound|get-heard|free-demo-feedback|terms|refund_returns|license|privacy-policy|legal-notice|about|creator-terms)([/?]|$)/.test(p)) return S+'/'+LG+p.replace(/^[/]en/,'')+(/[?]/.test(p)?'':'/')+hs;
  if(p===''||p==='/en'||p==='/') return S+'/'+LG+'/'+hs; return h; }
var NAV=[
  {t:'Home',i:'home',h:L+'/'},
  {t:'Products',i:'grid',h:L+'/all-products/',sub:[['All products','prod',L+'/all-products/'],['Templates','layers',S+'/templates'],['Presets','knob',S+'/presets'],['Music','note',S+'/music']]},
  {t:'Services',i:'serv',h:L+'/services-prices/',sub:[['Services & Prices','tag',L+'/services-prices/'],['Mixing & Mastering Calculator','calc',S+'/studio-calculator/?s=mixing'],['Mastering Album Calculator','disc',S+'/studio-calculator/?s=mastering'],['Get your set heard','mic',L+'/get-heard/'],['Examples','wave',L+'/#examples'],['Reviews','chat',L+'/#reviews'],['How it works','layers',L+'/#dashboard']]},
  {t:'Wiki',i:'wiki',h:L+'/wiki/'},
  {t:'Forum',i:'forum',h:L+'/forum/'},  /* owner 17.11: «Форум выведи в меню» */
  {t:'FAQ',i:'faq',sub:[['Why Solid Sound','shield',L+'/why-solid-sound/'],['Common Questions','faq',L+'/faq/'],['Contact Us','mail',S+'/contacts'],['Terms of Sale','doc',L+'/terms/'],['Refund Policy','refund',S+'/refund_returns/'],['License Agreement','shield',S+'/license/'],['Privacy Policy','lock',S+'/privacy-policy/'],['Legal Notice','scale',L+'/legal-notice/']]},
  {t:'Free',i:'free',h:L+'/free/'},
  {t:'Sell',i:'sell',h:L+'/sell/'}
];
if(LG) NAV.forEach(function(n){ if(n.h) n.h=tr(n.h); if(n.sub) n.sub.forEach(function(x){ x[2]=tr(x[2]); }); });
var logged=B.classList.contains('logged-in');
/* Accounts: client -> My account (the client dashboard), vendor -> Producer space (WCFM), admin -> both */
var MYA=L+'/my-account/', VND=L+'/store-manager/', ROLE=window.ssRole||'', VENDOR=ROLE==='vendor';
var DSH=MYA;
/* Sign in = WooCommerce account page with the 3 tabs (Log in / Create account / Become a vendor).
   The client dashboard is separate: shown as its own item only when logged in. */
var ACC=logged ? {t:'My account',i:'user',h:MYA} : {t:'Sign in',i:'user',h:MYA};
var CART={t:'Cart',i:'cart',h:tr(L+'/cart/')};
var FAV={t:'Favorites',i:'fav',h:logged ? MYA+'#favourites' : MYA};
/* current page: the language prefix (/en/, /de/ ...) and case are ignored, so /en/templates/ = /templates */
function normP(p){ p=(p||'/').toLowerCase().replace(/[/]+$/,'')||'/'; return p.replace(/^[/](en|de|fr|es|it|pt|ua|uk|pl|ru)(?=[/]|$)/,'')||'/'; }
var path=normP(location.pathname);
function isHome(h){ try{ return normP(new URL(h,location.href).pathname)==='/'; }catch(e){ return false; } }
function go(h){ if(!(window.sspGo && window.sspGo(h))) location.href=h; }
function isCur(h){ try{ var u=new URL(h,location.href); if(u.hash && u.hash.length>1) return false; return normP(u.pathname)===path; }catch(e){ return false; } }
/* a whole section lights up its menu item (product pages -> Products, calculators -> Services ...) */
var SEC={Products:/^[/](product|product-category|templates|presets|music|all-products|shop)([/]|$)/,Services:/^[/](services-prices|services-calculator|mastering-album-calculator|studio-calculator|get-heard)([/]|$)/,FAQ:/^[/](why-solid-sound|faq|contacts|terms|refund_returns|license|privacy-policy|legal-notice)([/]|$)/,Wiki:/^[/]wiki/,Forum:/^[/](forum|community)([/]|$)/};
function inSec(t){ return !!(SEC[t] && SEC[t].test(path)); }

/* ---------- styles ---------- */
var css=[
'html.ssg-on .l-header,html.ssg-on .l-subheader,html.ssg-on .w-toplink{display:none!important}',
'html.ssg-on .l-canvas,html.ssg-on .l-main{padding-top:0!important}',
'html.ssg-on body{--header-height:0px!important;--header-sticky-height:0px!important}',
'#ssg{position:sticky;top:0;z-index:9000;background:#1E2630;border-bottom:1px solid rgba(255,255,255,.07);box-shadow:0 10px 30px rgba(0,0,0,.5),0 2px 8px rgba(0,0,0,.3);font-family:"Exo 2",system-ui,sans-serif}',
'body.admin-bar #ssg{top:32px}@media (max-width:782px){body.admin-bar #ssg{top:46px}}@media (max-width:600px){body.admin-bar #ssg{top:0}}',
'#ssg *{box-sizing:border-box}',
'.sstb{top:var(--ssg-h,72px)!important}.sstb>.w-search{display:none!important}',
'.l-main{overflow-x:clip}',
/* (13.11.2026 owner RULE) every checklist on the site = orange check icons (like the vocal article) */
'ul.check{list-style:none!important;padding-left:0!important}ul.check li{display:flex;align-items:center;gap:12px;list-style:none!important;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.06)}ul.check li::before{display:none!important;content:none!important}',
'.ssg-ci{flex:none;display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:10px;background:rgba(224,153,0,.12);border:1px solid rgba(224,153,0,.3);color:#e09900}.ssg-ci svg{width:20px;height:20px}',
/* (13.11.2026, QA bot) iPhone zooms the page into any field with text under 16px — keep fields at 16px on phones */
'@media(max-width:600px){input:not([type=checkbox]):not([type=radio]):not([type=range]):not([type=submit]):not([type=button]),select,textarea{font-size:16px!important}}',
/* (13.11.2026, QA bot) readable SALE badge (was 2.4:1 contrast) */
'.onsale{background:#F59E0B!important;color:#111!important;font-weight:800!important}',
/* (13.11.2026, QA bot) scrolling to a button or section leaves room for the sticky header, toolbar and bottom bar */
'html{scroll-padding-top:calc(var(--ssg-h,72px) + 70px);scroll-padding-bottom:96px}',
'body.ss-newhome:not(.ss-one) #ssg,body.ss-newhome:not(.ss-one) #ssg-bar,body.ss-newhome:not(.ss-one) #ssg-promo,body.ss-newhome:not(.ss-one) #ssg-sheet{display:none!important}',
/* closed player must not peek out above the phone bottom bar (it was sliding down only its own height) */
'#ssp:not(.is-open){visibility:hidden!important;pointer-events:none!important}',
'/* vendor registration form: dark, readable, same look as the site */.wcfm-membership-wrapper,#wcfm_membership_container .wcfm-container{background:#131B2E!important;color:#E5E7EB!important;border-radius:16px!important;box-shadow:none!important}.wcfm-membership-wrapper{border:1px solid rgba(255,255,255,.08)!important}#wcfm_membership_container .wcfm-container{border:0!important}#wcfm_membership_container .wcfm_title,#wcfm_membership_container p.wcfm_title,#wcfm_membership_container label{color:#CBD5E1!important;font-style:normal!important;font-weight:600!important}#wcfm_membership_container .description,#wcfm_membership_container .instructions{color:#94A3B8!important;font-style:normal!important}#wcfm_membership_container input[type=text],#wcfm_membership_container input[type=email],#wcfm_membership_container input[type=password],#wcfm_membership_container input[type=number],#wcfm_membership_container input[type=url],#wcfm_membership_container input[type=tel],#wcfm_membership_container select,#wcfm_membership_container textarea{background:#0B1220!important;color:#fff!important;border:1px solid rgba(255,255,255,.16)!important;box-shadow:none!important;border-radius:10px!important;min-height:44px}#wcfm_membership_container a{color:#F59E0B!important}@media(max-width:760px){.page-id-399 .l-section.full_height{min-height:0!important}.page-id-399 .l-section-h{padding-top:24px!important;padding-bottom:24px!important}}',
/* (12.11.2026 QA on Samsung A31 / iPhone) no stray <br> gaps in the All products carousel; catalog toolbar = one compact row on phones; phone selects not clipped; free-zone chips centred */
'@media(max-width:760px){.sstb{flex-wrap:nowrap!important;gap:6px!important;padding:6px 8px!important;align-items:center!important}.sstb .sstb-lead{flex:none!important;margin:0!important}.sstb .sstb-count{display:none!important}.sstb .w-order{flex:1 1 auto!important;min-width:0!important;margin:0!important;order:3}.sstb .sstb-filters{flex:none!important;margin:0!important;padding:0!important;min-height:0!important;order:2}.sstb .sstb-filters *{margin-top:0!important;margin-bottom:0!important}.sstb form.w-filter{padding:0!important;margin:0!important;min-height:0!important}.sstb .sstb-active{flex-basis:100%!important;order:4}select{height:auto!important;min-height:44px;line-height:1.3!important;padding-top:10px!important;padding-bottom:10px!important;text-overflow:ellipsis}.ssf-chip span{line-height:1.2!important}}',
'#ssg .g-in{display:flex;align-items:center;gap:10px;padding:10px 24px;max-width:none;min-height:72px}',
'#ssg a{text-decoration:none!important}',
'#ssg .g-logo{flex:none;display:block;width:52px;margin-right:6px;-webkit-tap-highlight-color:transparent;cursor:pointer}#ssg .g-logo svg{display:block;width:100%;height:auto;transform-origin:50% 60%;transition:transform .34s cubic-bezier(.34,1.8,.5,1),filter .22s}#ssg .g-logo.ss-press svg{transform:scale(.86) translateY(1px);filter:brightness(.75);transition:transform .07s ease-out,filter .07s}',
'#ssg .g-nav{display:flex;align-items:center;gap:2px;flex:none}',
'#ssg .g-it{position:relative}',
'#ssg .g-a{display:flex;flex-direction:column;align-items:center;gap:3px;min-width:56px;padding:4px 6px;border:0;background:transparent;color:#94A3B8;font-weight:600;font-size:12px;line-height:1.1;font-family:inherit;cursor:pointer;border-radius:10px;white-space:nowrap}',
'#ssg .g-a:hover,#ssg .g-it.open > .g-a{color:#fff;background:rgba(255,255,255,.05)}',
'#ssg .g-a.cur{color:#F59E0B}',
'#ssg .g-a .g-l{display:flex;align-items:center;gap:2px}#ssg .g-a .g-l svg{width:10px;height:10px}',
'#ssg .g-dd{display:none;position:absolute;left:0;top:calc(100% + 6px);min-width:250px;padding:6px;background:#131B2E;border:1px solid rgba(255,255,255,.08);border-radius:14px;box-shadow:0 18px 40px rgba(0,0,0,.6);z-index:5}',
'#ssg .g-it.open .g-dd{display:block}',
'#ssg .g-dd a{display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:10px;color:#E5E7EB;font-weight:600;font-size:14px;line-height:1.2;font-family:inherit}',
'#ssg .g-dd a:hover{background:#1A2540;color:#fff}#ssg .g-dd a svg{color:#F59E0B;flex:none}',
'#ssg form{flex:1 1 240px;min-width:0;max-width:600px;display:flex;align-items:center;gap:6px;height:48px;padding:0 4px 0 12px;border-radius:14px;background:#090E17;box-shadow:inset 0 2px 5px rgba(0,0,0,.6);border:1px solid rgba(0,0,0,.4);margin:0 6px}',
'#ssg form > svg{flex:none;color:#94A3B8}',
'#ssg input{flex:1 1 auto;min-width:0;height:38px;border:0!important;background:transparent!important;color:#fff!important;font-size:15px;font-family:inherit;outline:none;box-shadow:none!important;padding:0!important;margin:0!important}',
'#ssg input::placeholder{color:#64748B}',
'#ssg .ss-ai{flex:none;display:inline-flex;align-items:center;padding:2px 6px;border-radius:6px;font-size:11px;font-weight:700;letter-spacing:.06em;cursor:pointer;user-select:none;white-space:nowrap}',
'#ssg .g-b{flex:none;width:40px;height:40px;border-radius:10px;border:1px solid rgba(0,0,0,.4);background:transparent;color:#CBD5E1;display:flex;align-items:center;justify-content:center;padding:0;cursor:pointer}',
'#ssg .g-find{flex:none;height:40px;padding:0 18px;border-radius:10px;border:0;background:#F59E0B;color:#111;font-weight:700;font-size:14px;font-family:inherit;cursor:pointer}',
'#ssg .ss-mic.ss-live{animation:ssgPulse 1.2s ease-out infinite}@keyframes ssgPulse{0%{box-shadow:0 0 0 0 rgba(245,158,11,.65)}100%{box-shadow:0 0 0 10px rgba(245,158,11,0)}}',
'#ssg .g-right{display:flex;align-items:center;gap:2px;margin-left:auto;flex:none}',
'#ssg .g-lang{font-weight:700;font-size:13px;font-family:inherit;color:#fff;padding:0;border-radius:8px;width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;border:1px solid rgba(255,255,255,.1);background:#131B2E;box-sizing:border-box}',
'#ssg .g-cur{width:auto;min-width:40px;padding:0 8px}',
'#ssg .g-lc,#ssg-sheet .g-lc{display:inline-block;min-width:28px;font-weight:800;color:#F59E0B}#ssg .g-dd a.cur{background:#1A2540;color:#F59E0B}.ss-flags{display:none!important}',
'#ssg .g-cnt{position:absolute;top:0;right:10px;min-width:16px;height:16px;padding:0 4px;border-radius:8px;background:#F59E0B;color:#111;font-weight:800;font-size:10px;line-height:16px;font-family:inherit;text-align:center}',
'#ssg .g-burger,#ssg .g-msign,#ssg .g-sbtn{display:none}',
'html,body{overflow-x:clip!important}',
'#ssg-bar{display:none}',
'#ssg-promo{display:flex;align-items:center;justify-content:center;gap:4px 10px;white-space:nowrap;min-height:36px;padding:6px 12px;box-sizing:border-box;background:linear-gradient(180deg,#0D1527,#0B1220);border-bottom:1px solid rgba(255,255,255,.06);color:#CBD5E1!important;text-decoration:none!important;font:13px/1.45 "Exo 2",system-ui,sans-serif;text-align:center}',
'#ssg-promo b{color:#fff;font-variant-numeric:tabular-nums}#ssg-promo .p-b{padding:2px 8px;border-radius:6px;background:#F59E0B;color:#111;font-size:11px;font-weight:800;letter-spacing:.08em}#ssg-promo .p-c{color:#F59E0B;font-weight:700}',
'#ssg-promo .p-sh{display:none}',
'@media (max-width:760px){#ssg-promo{font-size:12.5px;gap:4px 8px;min-height:34px;padding:5px 8px}#ssg-promo .p-long{display:none}#ssg-promo .p-sh{display:inline}#ssg-promo .p-b{font-size:10.5px;padding:2px 6px}}',
'#ssg-sheet{position:fixed;inset:0;z-index:99995;display:none}#ssg-sheet.open{display:block}',
'#ssg-sheet .s-bg{position:absolute;inset:0;background:rgba(0,0,0,.6)}',
'#ssg-sheet .s-p{position:absolute;right:0;top:0;bottom:0;width:min(340px,88vw);background:#131B2E;overflow:auto;padding:14px 12px calc(90px + env(safe-area-inset-bottom));box-shadow:-20px 0 50px rgba(0,0,0,.6);font-family:"Exo 2",system-ui,sans-serif}',
'#ssg-sheet .s-h{display:flex;align-items:center;justify-content:space-between;margin:0 4px 10px;color:#fff;font-weight:800;font-size:16px;font-family:inherit}',
'#ssg-sheet .s-g{margin:12px 4px 4px;color:#64748B;font-weight:700;font-size:11px;font-family:inherit;letter-spacing:.08em;text-transform:uppercase}',
'#ssg-sheet a{display:flex;align-items:center;gap:12px;padding:11px 10px;border-radius:10px;color:#E5E7EB!important;text-decoration:none!important;font-weight:600;font-size:15px;line-height:1.2;font-family:inherit}',
'#ssg-sheet a:hover{background:#1A2540}#ssg-sheet a svg{color:#F59E0B;flex:none}',
'#ssg-sheet .s-x{width:40px;height:40px;border:0;border-radius:10px;background:#1A2540;color:#fff;display:flex;align-items:center;justify-content:center}',
'@media (max-width:1180px){#ssg .g-nav .g-a{min-width:46px;padding:4px 3px}#ssg .g-in{padding:10px 14px}}',
'@media (max-width:1000px){#ssg .g-nav{display:none}#ssg .g-burger{display:flex}}',
'@media (max-width:760px){',
' #ssg .g-in{min-height:60px;padding:8px 10px;gap:8px}',
' #ssg .g-logo{width:44px;margin:0}',
' #ssg .g-right .g-r-it{display:none}',
' #ssg .g-right .g-r-cart{display:flex;position:relative}#ssg .g-right .g-r-cart .g-a{width:44px;height:44px;border-radius:12px;border:1px solid rgba(255,255,255,.12);background:#131B2E;color:#fff;display:flex;align-items:center;justify-content:center;padding:0}#ssg .g-right .g-r-cart .g-l{display:none}#ssg .g-right .g-r-cart svg{width:22px;height:22px}',
' #ssg .g-right .g-r-cart .g-cnt{position:absolute;top:-4px;right:-4px;min-width:17px;height:17px;padding:0 4px;border-radius:9px;background:#F59E0B;color:#111;font-size:11px;font-weight:800;line-height:17px;text-align:center}',
' #ssg form{height:44px;margin:0;gap:3px;padding:0 4px 0 10px}#ssg form > svg{display:none}',
' #ssg input{font-size:16px!important}',
' #ssg .g-b{width:32px;height:32px}#ssg .g-find{display:none}',
' #ssg .ss-ai{font-size:12px;padding:3px 6px}',
' #ssg .g-msign{display:flex;width:44px;height:44px;border-radius:12px;background:#F59E0B;color:#111;align-items:center;justify-content:center}',
' #ssg .g-sbtn{display:flex;width:44px;height:44px;border-radius:12px;border:1px solid rgba(255,255,255,.12);background:#131B2E;color:#fff;align-items:center;justify-content:center;padding:0;cursor:pointer}',
' #ssg .g-in > form{display:none}',
' html.ssg-msearch #ssg .g-in > form{display:flex;position:fixed;left:10px;right:10px;top:var(--ssg-ms-top,70px);z-index:99990;height:52px;padding:0 6px 0 12px;background:#090E17;border:1px solid rgba(255,255,255,.14);box-shadow:0 18px 40px rgba(0,0,0,.6)}',
' html.ssg-msearch #ssg .g-in > form > svg{display:block}',
' #ssg .g-burger{width:44px;height:44px;border-radius:12px;border:1px solid rgba(255,255,255,.12);background:#131B2E;color:#fff;align-items:center;justify-content:center}',
' #ssg-bar{display:flex;position:fixed;left:0;right:0;bottom:0;z-index:9500;background:#1E2630;border-top:1px solid rgba(255,255,255,.08);padding:4px 2px env(safe-area-inset-bottom);font-family:"Exo 2",system-ui,sans-serif}',
' #ssg-bar a,#ssg-bar button{flex:1 1 0;min-width:0;display:flex;flex-direction:column;align-items:center;gap:3px;padding:6px 0;border:0;background:transparent;color:#CBD5E1!important;font-weight:600;font-size:11px;line-height:1.1;font-family:inherit;text-decoration:none!important}',
' #ssg-bar .cur{color:#F59E0B!important}#ssg-bar .gfree{color:#34D399!important}',
' #ssg-bar a{position:relative}#ssg-bar .g-barcnt{position:absolute;top:2px;left:50%;margin-left:4px;min-width:15px;height:15px;padding:0 3px;box-sizing:border-box;border-radius:8px;background:#F59E0B;color:#111;font-size:10px;font-weight:800;line-height:15px;text-align:center}',
' body.ssg-bar-on{padding-bottom:calc(64px + env(safe-area-inset-bottom))}',
' body.ssg-bar-on #ssp{bottom:calc(63px + env(safe-area-inset-bottom))!important}',
' body.ssg-bar-on #ssb{bottom:calc(80px + env(safe-area-inset-bottom))!important;right:16px!important}',
' body.ssg-bar-on.ssp-open #ssb{bottom:calc(146px + env(safe-area-inset-bottom))!important}',
'}',
'.ssg-tri-btn svg{display:block}',
/* (owner 19.11 «как у профи») computer: the main menu is text in one line (no icons above the words), compact search — same as the home page */
'@media (min-width:1001px){#ssg form{max-width:440px;height:42px;border-radius:10px}}', /* (owner 19.11) menu icons stay */
/* (QA 17.11) free demo feedback page: its own "pill" buttons -> the site style (slight radius) */
'.page-id-1709 .btn,.page-id-1709 .btn-outline,.page-id-1709 .btn-purple{border-radius:6px!important}',
'button:has(path[d^="M6.87"]),.ssh-play{background:transparent!important;border-color:transparent!important;box-shadow:none!important;color:#F59E0B!important;transform:none!important}',
'button:has(path[d^="M6.87"]):hover{color:#FBBF24!important}',
'button:has(path[d^="M6.87"]) svg,.ssh-play svg{width:30px!important;height:30px!important;fill:currentColor!important}'
].join('\n');

/* ---------- markup ---------- */
function navHtml(){
  var h='';
  NAV.forEach(function(n){
    if(n.sub){
      var cur=n.sub.some(function(s){ return isCur(s[2]); }) || inSec(n.t);
      var dd=n.sub.map(function(s){ return tag('a',isCur(s[2]) ? {href:s[2],'class':'cur'} : {href:s[2]},ico(I[s[1]],18)+s[0].replace(/&/g,'and')); }).join('');
      h+=tag('div',{'class':'g-it'},tag('button',(function(o){ if(n.h) o['data-h']=n.h; return o; })({type:'button','class':'g-a'+(cur ? ' cur' : ''),'aria-haspopup':'true','aria-expanded':'false'}),ico(I[n.i])+tag('span',{'class':'g-l'},n.t+ico(I.down,10)))+tag('div',{'class':'g-dd',role:'menu'},dd));
    } else {
      h+=tag('div',{'class':'g-it'},tag('a',{'class':'g-a'+(isCur(n.h) || inSec(n.t) ? ' cur' : ''),href:n.h},ico(I[n.i])+tag('span',{'class':'g-l'},n.t)));
    }
  });
  return h;
}
/* Logged in: "Account" dropdown (Dashboard / Producer space / Account settings / Log out). Guest: plain "Sign in" link. */
function accItem(){
  var OUT=window.ssLogout||MYA+'customer-logout/';
  if(!logged){
    var gd=[['Log in','user',MYA+'?ss_tab=login'],['Create account','user',MYA+'?ss_tab=reg'],['Become a vendor','sell',MYA+'?ss_tab=sell']]
      .map(function(s){ return tag('a',{href:s[2],role:'menuitem'},ico(I[s[1]],18)+s[0]); }).join('');
    return tag('div',{'class':'g-it g-r-it'},tag('button',{type:'button','class':'g-a','aria-haspopup':'true','aria-expanded':'false'},ico(I.user)+tag('span',{'class':'g-l'},'Sign in'+ico(I.down,10)))+tag('div',{'class':'g-dd',role:'menu',style:'left:auto;right:0;min-width:210px'},gd));
  }
  /* separate accounts: a vendor account has the Producer space, a client account has the Dashboard */
  var client=[['My account','user',MYA],['Purchases','cart',MYA+'orders/'],['Downloads','dl',MYA+'downloads/'],['Settings','doc',MYA+'edit-account/']];
  /* one account, two roles: everyone has the client part; vendors (and admin) also the Producer space */
  var dd=client.concat(VENDOR || ROLE==='admin' ? [['Producer space','sell',VND]] : [['Start selling','sell',S+'/sell/']])
    .concat([['Log out','signin',OUT]])
    .map(function(s){ return tag('a',{href:s[2],role:'menuitem'},ico(I[s[1]],18)+s[0]); }).join('');
  return tag('div',{'class':'g-it g-r-it'},tag('button',{type:'button','class':'g-a'+(isCur(DSH) ? ' cur' : ''),'aria-haspopup':'true','aria-expanded':'false'},ico(I.user)+tag('span',{'class':'g-l'},'My account'+ico(I.down,10)))+tag('div',{'class':'g-dd',role:'menu',style:'left:auto;right:0;min-width:210px'},dd));
}
function rItem(o,extra,cls){ return tag('div',{'class':'g-it g-r-it'+(cls?' '+cls:'')},tag('a',{'class':'g-a'+((o!==FAV ? isCur(o.h) : false) ? ' cur' : ''),href:o.h,'aria-label':o.t},ico(I[o.i])+tag('span',{'class':'g-l'},o.t))+(extra||'')); }
/* languages: text labels (no flags), taken from the page's hreflang alternates printed by Polylang,
   falling back to the old theme header switcher. Same list and look as on the home page. */
var LN={en:'English',uk:'Українська',de:'Deutsch',fr:'Français',es:'Español',it:'Italiano',pt:'Português',pl:'Polski',ru:'Русский'};
function lcode(c){ c=(c||'').toLowerCase().slice(0,2); return c==='uk' ? 'UA' : c.toUpperCase(); }
var langs=[];
function addLang(code,href){ code=(code||'').toLowerCase(); if(!code||code==='x-default'||!href) return; var k=code.slice(0,2); if(langs.some(function(x){ return x.k===k; })) return; langs.push({k:k,h:href,t:lcode(k),n:LN[k]||lcode(k)}); }
D.querySelectorAll('link[rel="alternate"][hreflang]').forEach(function(l){ addLang(l.getAttribute('hreflang'),l.href); });
D.querySelectorAll('.l-header a[hreflang], .l-header .lang-item a').forEach(function(a){ addLang(a.getAttribute('hreflang')||a.getAttribute('lang'),a.href); });
window.ssLangs=langs;
var lang=lcode(D.documentElement.lang||'en');
var cartN=0; var cq=D.querySelector('.l-header .w-cart-quantity'); if(cq) cartN=parseInt(cq.textContent,10)||0;
/* (21.11 owner: «для каждой страны своя валюта … добавим переключатель») currency button next to the language one; list = WooPayments enabled currencies (window.SSCUR from modules/currency.php) */
var CUR=(window.SSCUR&&SSCUR.l&&SSCUR.l.length>1)?SSCUR:null;
function curUrl(c){ try{ var u=new URL(location.href); u.searchParams.set('currency',c); return u.pathname+u.search+u.hash; }catch(e){ return '?currency='+c; } }
function curLinks(){ return CUR.l.map(function(x){ return tag('a',{href:curUrl(x[0]),'data-cur':x[0],rel:'nofollow','class':x[0]===CUR.c ? 'cur' : ''},tag('b',{'class':'g-lc'},x[1])+x[0]+' · '+x[2]); }).join(''); }
var curHtml=CUR ? tag('div',{'class':'g-it g-r-it'},tag('button',{type:'button','class':'g-a g-lang g-cur','aria-haspopup':'true','aria-label':'Currency: '+CUR.c},CUR.s)+tag('div',{'class':'g-dd',style:'left:auto;right:0;min-width:210px;max-height:70vh;overflow:auto'},curLinks())) : '';
var langHtml=langs.length>1 ? tag('div',{'class':'g-it g-r-it'},tag('button',{type:'button','class':'g-a g-lang','aria-haspopup':'true','aria-label':'Language'},lang)+tag('div',{'class':'g-dd',style:'left:auto;right:0;min-width:190px'},langs.map(function(l){ return tag('a',{href:l.h,hreflang:l.k,'class':l.t===lang ? 'cur' : ''},tag('b',{'class':'g-lc'},l.t)+l.n); }).join(''))) : tag('div',{'class':'g-it g-r-it'},tag('span',{'class':'g-a g-lang'},lang));
var head=tag('div',{'class':'g-in'},
  tag('a',{'class':'g-logo',href:L+'/','aria-label':'Solid Sound home'},tag('svg',{viewBox:'0 0 341 230',role:'img','aria-hidden':'true'},tag('path',{fill:'#F59E0B','fill-rule':'evenodd',d:LOGO})))+
  tag('nav',{'class':'g-nav','aria-label':'Main'},navHtml())+
  tag('form',{role:'search',onsubmit:'return false'},ico(I.search,18)+tag('label',{'for':'top-q',style:'position:absolute;left:-9999px'},'Search')+tag('input',{id:'top-q',type:'search',enterkeyhint:'search',autocomplete:'off',placeholder:'Search'})+tag('span',{'class':'ss-ai',role:'button',tabindex:'0','aria-label':'AI search'},'AI')+tag('button',{type:'button','class':'g-b ss-mic','aria-label':'Voice search'},ico(I.mic,20))+tag('button',{type:'button','class':'g-b g-ref','aria-label':'Match by reference track',title:'Match by reference track'},ico(I.wave,20))+tag('button',{type:'button','class':'g-find'},'Find'))+
  tag('div',{'class':'g-right'},langHtml+curHtml+rItem(FAV,'','g-r-fav')+tag('button',{type:'button','class':'g-sbtn','aria-label':'Search','aria-expanded':'false'},ico(I.search,20))+rItem(CART,cartN ? tag('span',{'class':'g-cnt'},String(cartN)) : '','g-r-cart')+accItem()+tag('a',{'class':'g-msign',href:ACC.h,'aria-label':ACC.t},ico(I.signin,22))+tag('button',{type:'button','class':'g-burger','aria-label':'Main menu','aria-expanded':'false'},ico(I.menu,22)))
);
function sheetHtml(){
  var h=tag('div',{'class':'s-bg'})+LT+'div class="s-p" role="dialog" aria-label="Menu"'+GT+tag('div',{'class':'s-h'},'Menu'+tag('button',{type:'button','class':'s-x','aria-label':'Close menu'},ico(I.close,20)));
  NAV.forEach(function(n){ if(n.sub){ h+=tag('div',{'class':'s-g'},n.t)+n.sub.map(function(s){ return tag('a',{href:s[2]},ico(I[s[1]],20)+s[0].replace(/&/g,'and')); }).join(''); } });
  h+=tag('div',{'class':'s-g'},'More')+tag('a',{href:L+'/wiki/'},ico(I.wiki,20)+'Wiki')+tag('a',{href:L+'/forum/'},ico(I.forum,20)+'Forum')+tag('a',{href:L+'/why-solid-sound/'},ico(I.shield,20)+'Why Solid Sound')+tag('a',{href:L+'/get-heard/'},ico(I.mic,20)+'Get your set heard')+tag('a',{href:L+'/free/'},ico(I.free,20)+'Free pack')+tag('a',{href:L+'/sell/'},ico(I.sell,20)+'Sell your sounds')+tag('a',{href:ACC.h},ico(I.user,20)+ACC.t)+(!logged ? tag('a',{href:MYA+'?ss_tab=reg'},ico(I.user,20)+'Create account')+tag('a',{href:MYA+'?ss_tab=sell'},ico(I.sell,20)+'Become a vendor') : '')+(logged ? (VENDOR || ROLE==='admin' ? tag('a',{href:VND},ico(I.sell,20)+'Producer space') : '')+tag('a',{href:MYA+'orders/'},ico(I.cart,20)+'Purchases')+tag('a',{href:MYA+'edit-account/'},ico(I.doc,20)+'Settings')+tag('a',{href:window.ssLogout||MYA+'customer-logout/'},ico(I.signin,20)+'Log out') : '')+tag('a',{href:CART.h},ico(I.cart,20)+'Cart');
  if(langs.length>1) h+=tag('div',{'class':'s-g'},'Language')+langs.map(function(l){ return tag('a',{href:l.h,hreflang:l.k},tag('b',{'class':'g-lc'},l.t)+l.n); }).join('');
  if(CUR) h+=tag('div',{'class':'s-g'},'Currency')+curLinks();
  return h+LT+'/div'+GT;
}
var BAR=[['Home','home',L+'/'],['Shop','grid',L+'/all-products/'],['Studio','serv',L+'/services-prices/'],['Free','free',L+'/free/'],['Sell','sell',L+'/sell/'],['Saved','fav',FAV.h]];
if(LG) BAR.forEach(function(b){ b[2]=tr(b[2]); });
var barHtml=BAR.map(function(b){ return tag('a',{href:b[2],'class':((b[0]!=='Saved' ? (isCur(b[2]) || (b[0]==='Shop' && inSec('Products')) || (b[0]==='Studio' && inSec('Services'))) : false) ? 'cur' : '')+(b[0]==='Free' ? ' gfree' : '')+(b[0]==='Saved' ? ' gsaved' : '')},ico(I[b[1]],22)+b[0]); }).join('');

/* (13.11.2026) keep the cart badge (header + bottom bar) in sync when WooCommerce updates the mini-cart via AJAX */
window.ssCartPaint=cartPaint;
function cartPaint(n){
  var hd=D.getElementById('ssg'); if(hd){ var c=hd.querySelector('.g-right .g-cnt'), a=[].slice.call(hd.querySelectorAll('.g-right .g-r-it')).filter(function(x){return x.classList.contains('g-r-cart')||/cart/i.test((x.querySelector('.g-l')||{}).textContent||'');})[0];
    if(!c && n>0 && a){ c=D.createElement('span'); c.className='g-cnt'; a.querySelector('.g-a').appendChild(c); }
    if(c){ if(n>0){ c.textContent=String(n); c.style.display=''; } else c.style.display='none'; } }
  var bl=[].slice.call(D.querySelectorAll('#ssg-bar a')).filter(function(x){return /[/]cart/.test(x.getAttribute('href')||'')||/cart/i.test(x.textContent);})[0];
  if(bl){ var bc=bl.querySelector('.g-barcnt'); if(!bc && n>0){ bc=D.createElement('span'); bc.className='g-barcnt'; bl.appendChild(bc); } if(bc){ bc.textContent=n>0?String(n):''; bc.style.display=n>0?'':'none'; } }
  try{ localStorage.setItem('ss_cart_n', String(n)); }catch(e){}
}
var cartBusy=false;
function cartCount(){
  /* if WooCommerce prints a count somewhere, use it; otherwise ask the cart (Store API) — the theme has no cart badge of its own */
  var q=D.querySelector('.l-header .w-cart-quantity, .cart-contents-count, .widget_shopping_cart_content .count');
  if(q){ var m=(q.textContent||'').match(/\d+/); cartPaint(m?+m[0]:0); return; }
  try{ var cached=localStorage.getItem('ss_cart_n'); if(cached!=null) cartPaint(+cached||0); }catch(e){}
  if(cartBusy || !window.fetch) return; cartBusy=true;
  fetch(S+'/wp-json/wc/store/v1/cart',{credentials:'include',headers:{'Accept':'application/json'}})
    .then(function(r){ return r.ok ? r.json() : null; })
    .then(function(j){ cartBusy=false; if(j && typeof j.items_count==='number') cartPaint(j.items_count); })
    .catch(function(){ cartBusy=false; });
}
if(window.jQuery) window.jQuery(document.body).on('added_to_cart removed_from_cart wc_fragments_refreshed wc_fragments_loaded',function(){ cartBusy=false; cartCount(); });
/* (13.11.2026, QA bot) accessibility & SEO polish on every theme page */
function ssA11y(){
  /* inputs without an accessible name borrow their placeholder (so the label doesn't vanish when typing) */
  try{ D.querySelectorAll('input:not([type=hidden]):not([type=submit]):not([type=button]):not([type=checkbox]):not([type=radio]),textarea,select').forEach(function(i){
    if(i.getAttribute('aria-label')||i.getAttribute('aria-labelledby')||i.title) return;
    if(i.id && D.querySelector('label[for="'+(window.CSS&&CSS.escape?CSS.escape(i.id):i.id)+'"]')) return;
    if(i.closest('label')) return;
    var ph=i.getAttribute('placeholder'); if(ph) i.setAttribute('aria-label',ph);
  }); }catch(e){}
  /* (QA 17.11) icon-only links/buttons (forum share icons etc.) get a name from their title or class */
  try{ D.querySelectorAll('a:not([aria-label]),button:not([aria-label])').forEach(function(a){
    if((a.textContent||'').trim()||a.getAttribute('aria-labelledby')||a.querySelector('img[alt]:not([alt=""])')) return;
    var t=a.title||'', m=(a.className&&String(a.className).match(/(?:wpf-sb|share)[-_]?(facebook|twitter|x|telegram|whatsapp|linkedin|reddit|pinterest|vk|email|mail|link|copy)/i));
    if(!t&&m) t='Share: '+m[1].charAt(0).toUpperCase()+m[1].slice(1); if(!t&&/wpf-sb/.test(a.className)) t='Share';
    if(t) a.setAttribute('aria-label',t);
  }); }catch(e){}
}
/* H1 fallback runs LATE (after the theme has rendered its own title) and never duplicates one */
function ssH1(){
  try{ if(D.querySelector('h1')||D.querySelector('.ss-h1x')) return;
    if(D.querySelector('.page-title,.entry-title,.w-post-elm.post_title,.post-title')) return; /* a real title exists, just not as <h1> */
    var t=(D.title||'').split('|')[0].split('–')[0].trim(); if(!t) return;
    var main=D.getElementById('page-content')||D.querySelector('.l-main')||B; var h=D.createElement('h1'); h.className='ss-h1x'; h.textContent=t;
    h.style.cssText='position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap'; main.insertBefore(h,main.firstChild);
  }catch(e){}
}
if(D.readyState==='complete') setTimeout(ssH1,600); else window.addEventListener('load',function(){ setTimeout(ssH1,600); });
D.addEventListener('ssp:navigated',function(){ setTimeout(ssH1,700); });
(window.ssOnDom||function(f){f();})(ssA11y);
D.addEventListener('ssp:navigated',function(){ setTimeout(ssA11y,120); });
D.addEventListener('ssp:navigated',function(){ setTimeout(cartCount,150); });

function mount(){
  if((D.getElementById('ss-app') && !B.classList.contains('ss-one')) || D.getElementById('ssg')) return; /* (20.11 owner) the home page with the shared header (body.ss-one) gets it too */
  if(!D.getElementById('ssg-font')){ var lk=D.createElement('link'); lk.id='ssg-font'; lk.rel='stylesheet'; lk.href='https://fonts.googleapis.com/css2?family=Exo+2:wght@400;600;700;800&display=swap'; D.head.appendChild(lk); }
  var st=D.createElement('style'); st.id='ssg-css'; st.textContent=css; D.head.appendChild(st);
  var hd=D.createElement('header'); hd.id='ssg'; hd.innerHTML=head;
  var canvas=D.querySelector('.l-canvas')||B; canvas.insertBefore(hd,canvas.firstChild);
  promo(canvas,hd);
  var bar=D.createElement('nav'); bar.id='ssg-bar'; bar.setAttribute('aria-label','Quick menu'); bar.innerHTML=barHtml; B.appendChild(bar); B.classList.add('ssg-bar-on');
  var sh=D.createElement('div'); sh.id='ssg-sheet'; sh.innerHTML=sheetHtml(); B.appendChild(sh);
  D.documentElement.classList.add('ssg-on');
  wire(hd,sh); ssgAI(); playIcons(); cartCount();
  /* catalog filters bar sticks right under this header (owner 11.11.2026): its top = header's sticky top + height */
  var hset=function(){ var t=parseFloat(getComputedStyle(hd).top)||0; D.documentElement.style.setProperty('--ssg-h',Math.round(t+hd.offsetHeight)+'px'); };
  hset(); if(window.ResizeObserver) new ResizeObserver(hset).observe(hd); window.addEventListener('resize',hset,{passive:true});
}
/* Promo strip above the menu, same as on the home page (desktop only). Hidden after the sale ends. */
var PROMO_END=new Date('2026-11-30T23:59:59+01:00').getTime();
function promoLeft(){ var t=Math.max(0,PROMO_END-Date.now()), d=Math.floor(t/864e5), h=Math.floor(t%864e5/36e5), m=Math.floor(t%36e5/6e4); return d+'d '+h+'h '+m+'m'; }
function promo(canvas,hd){
  if(Date.now()>PROMO_END) return;
  var p=D.createElement('a'); p.id='ssg-promo'; p.href=L+'/all-products/';
  p.innerHTML=tag('span',{'class':'p-b'},'BLACK MONTH')+tag('span',{},tag('b',{},'\u221250%')+tag('span',{'class':'p-long'},' on all Ableton templates &amp; Serum presets \u00b7 ends in ')+tag('span',{'class':'p-sh'},' \u00b7 ')+tag('b',{'class':'p-t'},promoLeft()))+tag('span',{'class':'p-c'},'Shop'+tag('span',{'class':'p-long'},' the sale')+' \u2192');
  canvas.insertBefore(p,hd);
  setInterval(function(){ var t=p.querySelector('.p-t'); if(t) t.textContent=promoLeft(); },60000);
}
/* (owner 21.11) on the home page Products / Services (and the phone bar's Shop / Studio) scroll to the home's own
   templates (#shop) and services (#services) sections instead of leaving the page */
function homeSec(h){ if(!h||!D.getElementById('ss-app')) return null; var id=/\/all-products\/?$/.test(h) ? 'shop' : /\/services-prices\/?$/.test(h) ? 'services' : ''; return id ? D.getElementById(id) : null; }
function toSec(tg){ var off=(parseFloat(getComputedStyle(D.documentElement).getPropertyValue('--ssg-h'))||72)+8, y=Math.max(0,tg.getBoundingClientRect().top+window.pageYOffset-off);
  try{ window.scrollTo({top:y,behavior:'smooth'}); }catch(x){ window.scrollTo(0,y); } }
function wire(hd,sh){
  D.addEventListener('click',function(e){ var a=e.target.closest&&e.target.closest('#ssg-bar a[href]'), tg=a&&homeSec(a.getAttribute('href')); if(tg){ e.preventDefault(); e.stopPropagation(); toSec(tg); } },true);
  hd.addEventListener('click',function(e){
    var b=e.target.closest('.g-it > button.g-a'), hs=b&&homeSec(b.getAttribute('data-h'));
    if(hs){ e.preventDefault(); toSec(hs); hd.querySelectorAll('.g-it.open').forEach(function(x){ x.classList.remove('open'); }); b.setAttribute('aria-expanded','false'); return; }
    if(b && b.getAttribute('data-h') && matchMedia('(hover:hover) and (pointer:fine)').matches){
      /* already on that page (e.g. Products on All products): scroll to the catalog instead of reloading the same page */
      if(isCur(b.getAttribute('data-h'))){ e.preventDefault(); var tg=D.querySelector('.sstb')||D.querySelector('.w-grid'), off=(parseFloat(getComputedStyle(D.documentElement).getPropertyValue('--ssg-h'))||72)+8;
        var y=tg ? Math.max(0,tg.getBoundingClientRect().top+window.pageYOffset-off) : 0; if(Math.abs(window.pageYOffset-y)<4) y=0;
        try{ window.scrollTo({top:y,behavior:'smooth'}); }catch(x){ window.scrollTo(0,y); }
        hd.querySelectorAll('.g-it.open').forEach(function(x){ x.classList.remove('open'); }); return; }
      go(b.getAttribute('data-h')); return; }
    if(b){ /* hover already opened it: the first click keeps it open (owner/QA 17.11: «FAQ ничего не делает») */ var it=b.parentNode, on=!it.classList.contains('open')||(matchMedia('(hover:hover) and (pointer:fine)').matches&&!it.dataset.clk); it.dataset.clk=on?'1':''; hd.querySelectorAll('.g-it.open').forEach(function(x){ x.classList.remove('open'); }); if(on) it.classList.add('open'); b.setAttribute('aria-expanded',on ? 'true' : 'false'); e.preventDefault(); return; }
    if(e.target.closest('.g-burger')){ openSheet(true); }
    var sb=e.target.closest('.g-sbtn'); if(sb){ e.preventDefault(); msearch(!D.documentElement.classList.contains('ssg-msearch')); }
  });
  hd.querySelectorAll('.g-nav .g-it').forEach(function(it){ if(!it.querySelector('.g-dd')) return; var tm; it.addEventListener('mouseenter',function(){ if(matchMedia('(hover:hover)').matches){ clearTimeout(tm); hd.querySelectorAll('.g-it.open').forEach(function(x){ if(x!==it) x.classList.remove('open'); }); it.classList.add('open'); } }); it.addEventListener('mouseleave',function(){ it.dataset.clk=''; if(matchMedia('(hover:hover)').matches){ tm=setTimeout(function(){ it.classList.remove('open'); },180); } }); });
  /* currency link: remember the choice (the page cache keeps one copy per currency) */
  D.addEventListener('click',function(e){ var c=e.target.closest('a[data-cur]'); if(c) D.cookie='ss_cur='+c.getAttribute('data-cur')+';path=/;max-age=31536000;samesite=lax'; },true);
  D.addEventListener('click',function(e){ if(!e.target.closest('#ssg .g-it')) hd.querySelectorAll('.g-it.open').forEach(function(x){ x.classList.remove('open'); }); });
  function msearch(on){ D.documentElement.classList.toggle('ssg-msearch',on); var sb=hd.querySelector('.g-sbtn'); if(sb){ sb.setAttribute('aria-expanded',on?'true':'false'); sb.setAttribute('aria-label',on?'Close search':'Search'); sb.innerHTML=ico(on?I.close:I.search,20); }
    if(on){ D.documentElement.style.setProperty('--ssg-ms-top',Math.max(8,Math.round(hd.getBoundingClientRect().bottom)+6)+'px'); var q=D.getElementById('top-q'); setTimeout(function(){ try{ q.focus(); }catch(x){} },30); } }
  D.addEventListener('click',function(e){ if(D.documentElement.classList.contains('ssg-msearch') && !e.target.closest('#ssg form,.g-sbtn,#ss-ai-panel')) msearch(false); },true);
  function openSheet(on){ sh.classList.toggle('open',on); D.documentElement.style.overflow=on ? 'hidden' : ''; var bb=hd.querySelector('.g-burger'); if(bb) bb.setAttribute('aria-expanded',on ? 'true' : 'false'); }
  sh.addEventListener('click',function(e){ if(e.target.closest('.s-bg,.s-x')) openSheet(false); else if(e.target.closest('a')) openSheet(false); });
  D.addEventListener('click',function(e){ if(e.target.closest('#ssg-bar .g-sheet-open')) openSheet(true); });
  D.addEventListener('keydown',function(e){ if(e.key==='Escape'){ openSheet(false); msearch(false); hd.querySelectorAll('.g-it.open').forEach(function(x){ x.classList.remove('open'); }); } });
  /* links in our header always do a full load (the player keeps the track) */
  /* menu links switch pages without a reload while the player is playing (music keeps going);
     only the home page (its own app) still loads fully, and the player resumes there */
  /* (12.11.2026) Home links switch without reload too: the player keeps playing everywhere */
  function markCur(){ path=normP(location.pathname); D.querySelectorAll('#ssg a[href],#ssg-bar a[href]').forEach(function(a){ if(a.id==='ssg-promo'||a.classList.contains('g-logo')) return; var it=a.closest('.g-it'), on=isCur(a.getAttribute('href')); a.classList.toggle('cur',on); }); D.querySelectorAll('#ssg .g-nav .g-it').forEach(function(it){ var top=it.querySelector(':scope > .g-a'); if(top && it.querySelector('.g-dd')) top.classList.toggle('cur',!!it.querySelector('.g-dd a.cur')||(function(h){ return !!h && isCur(h); })(top.getAttribute('data-h')||top.getAttribute('href')||'')||inSec(((top.querySelector('.g-l')||{}).textContent||'').trim())); }); }
  D.addEventListener('ssp:url',markCur); D.addEventListener('ssp:navigated',markCur); window.addEventListener('popstate',function(){ setTimeout(markCur,50); });
}

/* ---------- triangle play icons everywhere ---------- */
function playIcons(){
  D.querySelectorAll('path[d="M8 5v14l11-7z"]').forEach(function(p){ p.setAttribute('d',TRI); p.setAttribute('fill-rule','evenodd'); });
  D.querySelectorAll('#ss-ai-panel [data-ai=play]').forEach(function(b){ if(b.textContent==='▶'){ b.innerHTML=triIcon(18); } });
}
window.ssOnDom ? ssOnDom(playIcons) : setInterval(playIcons,800);

function ssgAI(){
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
    var inp=document.getElementById('top-q'), chip=document.querySelector('#ssg .ss-ai'), mic=document.querySelector('#ssg .ss-mic');
    if(inp){ var p=phText(); if(inp.placeholder!==p) inp.placeholder=p; }
    /* 16.11: write only on real change — rewriting the same value fed ssOnDom ~8×/s on every page */
    if(chip){
      sTx(chip, aiOn ? 'AI' : 'AI off');
      sA(chip,'aria-pressed', aiOn ? 'true' : 'false');
      sA(chip,'title', aiOn ? 'AI search is on (tap to turn off)' : 'AI search is off (tap to turn on)');
      setSt(chip,'background', aiOn ? '#F59E0B' : 'transparent'); setSt(chip,'color', aiOn ? '#111111' : '#94A3B8');
      setSt(chip,'border', aiOn ? '1px solid #F59E0B' : '1px solid rgba(148,163,184,0.45)');
    }
    if(mic){
      sA(mic,'aria-pressed', micOn ? 'true' : 'false'); sA(mic,'aria-label', micOn ? 'Voice search on, tap to turn off' : 'Voice search');
      setSt(mic,'background', micOn ? '#F59E0B' : 'transparent'); setSt(mic,'color', micOn ? '#111111' : '#CBD5E1');
      if(mic.classList.contains('ss-live')!==micOn) mic.classList.toggle('ss-live', micOn);
    }
  }
  function sA(el,k,v){ if(el.getAttribute(k)!==v) el.setAttribute(k,v); }
  function sTx(el,v){ if(el.textContent!==v) el.textContent=v; }
  window.ssMic=function(on,msg){ micOn=!!on; micMsg=on ? '' : (msg||micMsg); paint(true); if(micMsg){ setTimeout(function(){ if(!micOn){ micMsg=''; paint(true); } },4000); } };
  window.ssOnDom ? ssOnDom(paint) : setInterval(paint,700); window.addEventListener('resize',function(){ paint(true); },{passive:true});

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
  function plain(q){ var w=words(q)[0]||q; location.href='https://solid-sound.net/?s='+encodeURIComponent(w); }
  function show(q,html){
    close();
    var f=document.querySelector('#ssg form'), r=f ? f.getBoundingClientRect() : {bottom:120,left:12,width:360};
    panel=document.createElement('div'); panel.id='ss-ai-panel';
    var m=innerWidth<761;
    panel.style.cssText='position:fixed;z-index:99990;top:'+Math.round(r.bottom+8)+'px;left:'+(m?10:Math.round(r.left))+'px;'+(m?'right:10px;':'width:'+Math.max(420,Math.round(r.width))+'px;')+'max-height:'+(m?'calc(100vh - '+Math.round(r.bottom+100)+'px)':'70vh')+';overflow:auto;background:#131B2E;border:1px solid rgba(245,158,11,.35);border-radius:16px;box-shadow:0 20px 50px rgba(0,0,0,.7);padding:14px;color:#E5E7EB;font-size:14px;line-height:1.4;font-family:inherit;-webkit-overflow-scrolling:touch';
    panel.innerHTML='<div style="display:flex;align-items:center;gap:8px;margin-bottom:10px"><span style="background:#F59E0B;color:#111;font-weight:800;font-size:11px;padding:3px 7px;border-radius:6px">AI</span><b style="flex:1;font-size:14px;color:#fff;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">'+esc(q)+'</b><button type="button" data-ai="x" aria-label="Close" style="width:36px;height:36px;border:0;border-radius:10px;background:#1A2540;color:#cbd5e1;font-size:20px;line-height:1">×</button></div>'+html;
    document.body.appendChild(panel);
  }
  function card(x){
    var p=x.p;
    return '<div style="display:flex;gap:12px;align-items:center;padding:10px;border-radius:12px;background:#1A2540;margin-bottom:8px">'+
      '<a href="'+p.url+'" style="flex:none;width:64px;height:64px;border-radius:10px;background:#090E17 center/cover no-repeat;background-image:url('+p.img+')" aria-label="'+esc(p.name)+'"></a>'+
      '<a href="'+p.url+'" style="flex:1;min-width:0;color:#fff;text-decoration:none"><b style="display:block;font-size:15px">'+esc(p.name)+'</b><span style="display:block;color:#94A3B8;font-size:12px">'+esc(x.why)+'</span><span style="color:#F59E0B;font-weight:700">'+p.price+'</span></a>'+
      (p.mp3?'<button type="button" data-ai="play" data-mp3="'+p.mp3+'" aria-label="Play demo" style="flex:none;width:42px;height:42px;border-radius:50%;border:0;background:#F59E0B;color:#111;font-size:15px">'+(window.ssTri ? window.ssTri(18) : '▶')+'</button>':'')+
      '</div>';
  }
  /* 19.11 smart search on the server (code + optional AI); any failure → the in-browser ranking below */
  function srv(q){
    var c=window.AbortController ? new AbortController() : null, t=setTimeout(function(){ if(c) c.abort(); },5000);
    return fetch('/wp-json/ss/v1/find?q='+encodeURIComponent(q),c ? {signal:c.signal} : {}).then(function(x){ clearTimeout(t); return x.ok ? x.json() : null; }).then(function(j){
      if(!j || !j.items) return null;
      var r=j.items.map(function(i){ return {p:{name:i.name,url:i.url,img:i.img,price:i.price,mp3:i.mp3}, sc:i.sc, why:i.why}; });
      r.svcs=j.svcs||[]; return r;
    }).catch(function(){ clearTimeout(t); return null; });
  }
  window.ssAI=function(q,lab){
    q=(q||'').trim(); if(!q) return; var hq=lab||q;
    try{ if(window.gtag) gtag('event','search',{search_term:q, search_mode:'ai'}); }catch(e){}
    var inp=document.getElementById('top-q'); if(inp) inp.blur();
    show(hq,'<div style="color:#94A3B8;padding:8px 2px">Finding the best match…</div>');
    (window.ssRefScores ? Promise.resolve(null) : srv(q)).then(function(sr){ return sr || load().then(function(list){ return rank(q,list); }); }).then(function(r){
      var rsx=(window.ssRefScores=null), top=r.filter(function(x){return x.sc>0;}).slice(0,3), svc=SVC.test(q.toLowerCase()), h='';
      if(top.length){ h+='<div style="font-size:12px;color:#94A3B8;margin:0 2px 8px">Best matches</div>'+top.map(card).join(''); }
      else if(!svc){ h+='<div style="font-size:13px;color:#cbd5e1;margin:0 2px 8px">No exact match yet. Popular picks:</div>'+r.slice(0,3).map(card).join(''); }
      if(r.svcs ? r.svcs.length : false){ h+=r.svcs.slice(0,2).map(function(v){ return '<a href="'+v.url+'" style="display:flex;gap:10px;align-items:center;padding:12px;border-radius:12px;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.35);color:#fff;text-decoration:none;margin-bottom:8px"><span style="font-size:20px">🎚️</span><span style="flex:1"><b>'+esc(v.name)+'</b></span><span style="color:#F59E0B;font-weight:700;white-space:nowrap">'+esc(v.price)+' →</span></a>'; }).join(''); svc=false; }
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
    if(!t){ if(panel ? !panel.contains(e.target) : false){ if(!e.target.closest('#ssg form')) close(); } return; }
    if(t.classList.contains('ss-ai')){ e.preventDefault(); e.stopPropagation(); aiOn=!aiOn; try{ localStorage.setItem('ssAiOn', aiOn ? '1' : '0'); }catch(x){} if(!aiOn) close(); paint(true); try{ if(window.gtag) gtag('event','ai_search_toggle',{on:aiOn}); }catch(x){} return; }
    var a=t.getAttribute('data-ai'), q=(document.getElementById('top-q')||{}).value||'';
    if(a==='x') close();
    if(a==='all') plain(q);
    if(a==='chat'){ close(); var b=document.querySelector('.ssb-fab,#ssb button'); if(b) b.click(); }
    if(a==='play'){ var src=t.getAttribute('data-mp3'); if(aud ? aud.src===src : false){ if(aud.paused){ aud.play(); t.textContent='❚❚'; } else { aud.pause(); t.innerHTML=window.ssTri ? window.ssTri(18) : '▶'; } return; } if(aud) aud.pause(); panel.querySelectorAll('[data-ai=play]').forEach(function(b){ b.innerHTML=window.ssTri ? window.ssTri(18) : '▶'; }); aud=new Audio(src); aud.play(); t.textContent='❚❚'; try{ if(window.gtag) gtag('event','play_demo',{src:'ai_search'}); }catch(x){} }
  },true);
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
      var b=tempo(buf), rf=fpOf(buf); try{ ctx.close(); }catch(x){}
      buf=null; part=null; f=null; picker.value='';
      try{ if(window.gtag) gtag('event','reference_match',{step:'done',bpm:b}); }catch(x){}
      ensureFP().then(function(){ window.ssRefScores=cmpFP(rf); window.ssAI('techno '+b+' bpm','Your reference: ~'+b+' BPM'); });
    }).catch(function(){ try{ ctx.close(); }catch(x){} refFail(); });
  }
  picker.addEventListener('change',function(){ refFile(picker.files[0]); });
  document.addEventListener('click',function(e){
    if(e.target.closest ? e.target.closest('#ssg .g-ref') : false){ e.preventDefault(); e.stopPropagation(); picker.value=''; picker.click(); }
  },true);
  document.addEventListener('dragover',function(e){ if(e.target.closest ? e.target.closest('#ssg form') : false) e.preventDefault(); });
  document.addEventListener('drop',function(e){ if(e.target.closest ? e.target.closest('#ssg form') : false){ e.preventDefault(); var f=e.dataTransfer ? e.dataTransfer.files[0] : null; if(f) refFile(f); } });
  /* search + voice */
  function ssSearch(q){ q=(q||'').trim(); if(!q) return; if(aiOn){ window.ssAI(q); return; } try{ if(window.gtag) gtag('event','search',{search_term:q}); }catch(x){} location.href='https://solid-sound.net/?post_type=product'+String.fromCharCode(38)+'s='+encodeURIComponent(q); }
  document.addEventListener('keydown',function(e){ if(e.key==='Enter' ? (e.target.id==='top-q') : false){ e.preventDefault(); ssSearch(e.target.value); } },true);
  document.addEventListener('click',function(e){
    if(!e.target.closest) return;
    if(e.target.closest('#ssg .g-find')){ e.preventDefault(); ssSearch((document.getElementById('top-q')||{}).value); return; }
    var f=e.target.closest('#ssg .ss-mic'); if(!f) return;
    e.preventDefault(); e.stopPropagation(); e.stopImmediatePropagation();
    var inp=document.getElementById('top-q'); var SR=window.SpeechRecognition || window.webkitSpeechRecognition;
    if(!SR){ window.ssMic(false,'Voice not supported here, type instead'); if(inp){ inp.focus(); } return; }
    if(window.ssRec){ try{window.ssRec.stop();}catch(x){} window.ssRec=null; window.ssMic(false); return; }
    var r=new SR(); window.ssRec=r; r.lang=(navigator.language||'en-US'); r.interimResults=true; r.maxAlternatives=1;
    window.ssMic(true);
    r.onresult=function(ev){ var txt=''; for(var i=0;i<ev.results.length;i++){ txt+=ev.results[i][0].transcript; } if(inp){ inp.value=txt; } if(ev.results[ev.results.length-1].isFinal){ try{ r.stop(); }catch(x){} ssSearch(txt); } };
    r.onend=function(){ if(window.ssRec===r) window.ssRec=null; window.ssMic(false); };
    r.onerror=function(ev){ if(window.ssRec===r) window.ssRec=null; window.ssMic(false, (ev ? ev.error==='no-speech' : false) ? 'Didn’t catch that, tap the mic again' : 'Mic blocked, allow microphone or type'); };
    try{ r.start(); }catch(x){ window.ssRec=null; window.ssMic(false); }
  },true);
  document.addEventListener('keydown',function(e){ if(e.key==='Escape') close(); });
  window.addEventListener('scroll',function(){ if(panel ? innerWidth>760 : false) close(); },{passive:true});
}
/* logo = physical Home button: sinks in on press, springs back, then goes home */
function lgOf(t){ return t.closest ? t.closest('#ssg .g-logo') : null; }
D.addEventListener('pointerdown',function(e){ var a=lgOf(e.target); if(a) a.classList.add('ss-press'); },true);
['pointerup','pointercancel','pointerleave'].forEach(function(ev){ D.addEventListener(ev,function(e){ var a=lgOf(e.target); if(a) setTimeout(function(){ a.classList.remove('ss-press'); },60); },true); });
D.addEventListener('click',function(e){ var a=lgOf(e.target); if(!a) return; if(e.metaKey ? true : e.ctrlKey) return; e.preventDefault(); a.classList.add('ss-press'); var h=a.href; setTimeout(function(){ a.classList.remove('ss-press'); },110); setTimeout(function(){ go(h); },230); },true);
if(D.readyState==='loading') D.addEventListener('DOMContentLoaded',mount); else mount();
/* page switched by the player without reload (e.g. from the home app to a shop page): the menu must exist there too */
D.addEventListener('ssp:navigated',function(){ if(!D.getElementById('ssg')) mount(); });
})();
