<?php
/**
 * One studio price calculator for every service (owner 12.11.2026; see docs/services-v2.md).
 * Page: /studio-calculator/ (+ /en/… etc.) — rendered by the plugin, not linked from menus until the owner approves the prices.
 * All prices live in ss_sc_prices() — change them in ONE place. The request goes to the owner by email + Telegram.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* today's prices = base × demand (price-engine.php); base = docs/prices.md */
function ss_sc_prices() { $P = ss_sc_prices_base(); return function_exists( 'ss_pe_apply' ) ? ss_pe_apply( $P ) : $P; }
function ss_sc_prices_base() {
	return array(
		'mixing'    => array( 'name' => 'Mixing (+ Stereo Mastering Pro)', 'unit' => 'tracks', 'ask' => 'How many tracks (stems) are in your project?', 'min' => 2, 'max' => 52, 'def' => 12,
			'tiers' => array( array( 8, 'Mixing Start', 69, 3, '2–3 days' ), array( 31, 'Mixing Pro', 129, 5, '5–7 days' ), array( 52, 'Mixing Ultra', 279, 10, '7–9 days' ) ) ),
		'release'   => array( 'name' => 'Mix & Master bundle (mix + stem master + everything for release)', 'unit' => 'tracks', 'ask' => 'How many tracks (stems)?', 'min' => 2, 'max' => 52, 'def' => 12, 'plus' => 0,
			'note' => 'Mixing + stem-level mastering, streaming + club masters, stems, instrumental, 30-min video consultation, 4 revisions.',
			'tiers' => array( array( 8, 'Mix & Master S', 99, 4, '4–5 days' ), array( 31, 'Mix & Master M', 179, 4, '6–8 days' ), array( 52, 'Mix & Master L', 329, 10, '8–10 days' ) ) ),
		'mastering' => array( 'name' => 'Mastering', 'unit' => 'songs', 'ask' => 'How many songs?', 'min' => 1, 'max' => 30, 'def' => 1,
			'types' => array( 'stereo' => array( 'Stereo Mastering Pro — 1 stereo file', 35, 2, '24–48 h' ), 'stem' => array( 'Stem Mastering — up to 8 stems', 59, 3, '3–5 days' ) ),
			'album' => array( 5, 0.2 ), 'note' => '5+ songs: −20% (album / EP). The first song of a group order is a free demo.' ),
		'remix'     => array( 'name' => 'Remix', 'pkgs' => array( array( 'Radio Remix', 190, 2, '5–7 days' ), array( 'Club Remix', 290, 3, '7–10 days' ), array( 'Remix Ultra (stems + Ableton project)', 450, 5, '10–14 days' ) ) ),
		'remake'    => array( 'name' => 'Remake (for learning)', 'note' => 'A faithful remake of a reference track for study — the sounds are made by us, the song belongs to its writers. Add «New melody» and you can release it as your own track.', 'pkgs' => array( array( 'Remake Basic (up to 8 parts)', 120, 2, '3–5 days' ), array( 'Remake Standard (up to 16 parts)', 200, 3, '5–7 days' ), array( 'Remake Full (up to 32 parts + project)', 350, 5, '7–10 days' ) ) ),
		'ghost'     => array( 'name' => 'Custom production', 'pkgs' => array( array( 'Custom Start', 350, 3, '7–10 days' ), array( 'Custom Pro (stems + project)', 650, 5, '10–14 days' ), array( 'Custom Ultra (label package + video walkthrough)', 990, 8, '14–21 days' ), array( 'Online Collaboration (3 × 2 h live sessions + everything in Pro)', 1290, 6, '20–30 days' ) ), 'note' => 'Pay in 2–3 parts if you like. 100 % copyright is yours.' ),
		'copro'     => array( 'name' => 'Project rework', 'note' => 'Your first track review is free.', 'pkgs' => array( array( 'Track Review (written + video feedback)', 29, 0, '2–3 days' ), array( 'Rework Standard (we work in your project, 1 × 30 min call)', 190, 2, '5–7 days' ), array( 'Rework Release Ready (2 × 60 min calls, mix + master, stems)', 290, 5, '10–14 days' ) ) ),
		'post'      => array( 'name' => 'Post production (video, podcast, film)', 'unit' => 'minutes', 'ask' => 'How long is the video / episode (minutes)?', 'min' => 1, 'max' => 120, 'def' => 10,
			'pkgs' => array( array( 'Video — YouTube, reels, promo (+ sound design, music to picture)', 90, 3, '3–5 days', 10, 25 ), array( 'Film & Ads (+ separate stems)', 250, 5, '7–10 days', 30, 60 ) ),
			'sub' => array( 'YouTube Channel — 4 videos up to 10 min every month', 199 ) ),
		'extras'    => array(
			'stems'    => array( 'Stems', 10, 'once', array( 'mixing', 'mastering', 'remix' ) ),
			'tuning'   => array( 'Manual vocal tuning', 12, 'per vocal track', array( 'mixing', 'release' ) ),
			'live'     => array( 'Live online session, 60 min (screen + sound)', 39, 'once', array( 'mixing', 'release', 'remix', 'remake', 'ghost', 'copro' ) ),
			'project'  => array( 'Your Ableton project back', 49, 'once', array( 'remix', 'ghost' ) ),
			'rev'      => array( 'Extra revision', 15, 'each', array( 'mixing', 'mastering', 'remix', 'remake', 'ghost', 'post' ) ),
			'newmel'   => array( 'New melody — release it as your own track', 20, 'once', array( 'remake' ) ),
			'rush'     => array( 'Rush order', 30, 'once', array( 'mixing', 'release', 'mastering', 'remix', 'remake', 'copro', 'post' ) ),
			'rushg'    => array( 'Rush order', 50, 'once', array( 'ghost' ) ),
		),
	);
}

function ss_sc_path_ok() {
	$p = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' );
	return (bool) preg_match( '#^([a-z]{2}/)?studio-calculator$#', $p );
}

/* request -> email + Telegram */
add_action( 'admin_post_nopriv_ss_studio_request', 'ss_sc_request' );
add_action( 'admin_post_ss_studio_request', 'ss_sc_request' );
function ss_sc_request() {
	if ( ! empty( $_POST['website'] ) ) { wp_safe_redirect( home_url( '/studio-calculator/?sent=1' ) ); exit; } /* honeypot */
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) $_SERVER['REMOTE_ADDR'] : '';
	$k = 'ss_sc_rl_' . md5( $ip );
	if ( (int) get_transient( $k ) >= 5 ) wp_die( 'Too many requests — please try again in an hour or write to info@solid-sound.net.' );
	set_transient( $k, (int) get_transient( $k ) + 1, HOUR_IN_SECONDS );
	$f = function ( $n, $max = 2000 ) { return isset( $_POST[ $n ] ) ? mb_substr( sanitize_textarea_field( wp_unslash( $_POST[ $n ] ) ), 0, $max ) : ''; };
	$email = sanitize_email( isset( $_POST['email'] ) ? wp_unslash( $_POST['email'] ) : '' );
	if ( ! is_email( $email ) ) wp_die( 'Please go back and enter a valid email.' );
	$txt = "Studio request (calculator)\n\nName: " . $f( 'name', 120 ) . "\nEmail: " . $email . "\nFiles / links: " . $f( 'links', 1000 ) . "\n\nQuote:\n" . $f( 'quote', 3000 ) . "\n\nMessage:\n" . $f( 'message', 3000 );
	wp_mail( get_option( 'admin_email' ), 'Studio request: ' . $f( 'service', 120 ) . ' — ' . $email, $txt, array( 'Reply-To: ' . $email ) );
	if ( function_exists( 'ss_tg_send' ) ) ss_tg_send( '🎚 ' . $txt );
	wp_safe_redirect( add_query_arg( 'sent', '1', wp_get_referer() ? wp_get_referer() : home_url( '/studio-calculator/' ) ) );
	exit;
}

add_action( 'template_redirect', function () {
	if ( ! ss_sc_path_ok() ) return;
	global $wp_query;
	$wp_query->is_404 = false;
	status_header( 200 );
	add_filter( 'pre_get_document_title', function () { return 'Studio Price Calculator – Mixing, Mastering, Remix | Solid Sound'; } );
	/* 25.11 (SEO → «Копирайтер»): /de/, /fr/, /es/ — canonical to itself and the server-side translation (modules/i18n-pages.php).
	   This page exits here at priority 1, before i18n-pages starts its buffer at 9999, so start it here. */
	$vl = function_exists( 'ss_vp_on' ) ? ss_vp_on() : '';
	add_action( 'wp_head', function () use ( $vl ) { echo '<meta name="description" content="Instant prices for mixing, mastering (stereo, stem, multitrack), remix, ghost producing and post production. Pay 50% to start.">' . "\n" . '<link rel="canonical" href="' . esc_url( home_url( ( $vl ? '/' . $vl : '' ) . '/studio-calculator/' ) ) . '">'; }, 1 );
	add_action( 'wp_head', function () { echo '<style>' . ss_sc_css() . '</style>'; } );
	if ( $vl ) ob_start( function ( $html ) use ( $vl ) { return ss_vp_tr_html( $html, $vl ); } );
	get_header();
	echo '<main id="page-content" class="l-main"><section class="l-section"><div class="l-section-h">' . ss_sc_html() . '</div></section></main>';
	get_footer();
	exit;
}, 1 );

function ss_sc_css() {
	return '.ssc{max-width:980px;margin:24px auto 60px;padding:0 16px;color:#E5E7EB;font-family:"Exo 2",system-ui,sans-serif}'
		. '.ssc h1{font-size:34px;margin:0 0 6px;color:#fff}.ssc .lead{color:#94A3B8;margin:0 0 18px}'
		. '.ssc .g{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(0,1fr);gap:18px;align-items:start}'
		. '.ssc .card{background:#131B2E;border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:18px}'
		. '.ssc label.t{display:block;font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:#94A3B8;margin:14px 0 8px}'
		. '.ssc .svc{display:flex;flex-wrap:wrap;gap:8px}.ssc .svc button,.ssc .pk button{border:1px solid rgba(255,255,255,.12);background:#0F1626;color:#E5E7EB;border-radius:6px;padding:9px 14px;font-family:inherit;font-weight:600;font-size:14px;cursor:pointer}'
		. '.ssc .svc button.on,.ssc .pk button.on{border-color:#F59E0B;color:#F59E0B;background:rgba(245,158,11,.1)}'
		. '.ssc .pk{display:flex;flex-direction:column;gap:8px}.ssc .pk button{border-radius:12px;text-align:left;display:flex;justify-content:space-between;gap:10px}'
		. '.ssc input[type=range]{width:100%;accent-color:#F59E0B}.ssc .num{font-size:22px;font-weight:800;color:#fff}'
		. '.ssc .ex label{display:flex;gap:10px;align-items:center;justify-content:space-between;padding:10px 12px;border:1px solid rgba(255,255,255,.08);border-radius:12px;margin:6px 0;cursor:pointer}'
		. '.ssc .ex input{width:20px;height:20px;accent-color:#F59E0B}.ssc .ex small{color:#94A3B8}'
		. '.ssc .tot{position:sticky;top:calc(var(--ssg-h,72px) + 12px)}.ssc .big{font-size:40px;font-weight:800;color:#fff;margin:4px 0}'
		. '.ssc .row{display:flex;justify-content:space-between;gap:10px;color:#CBD5E1;font-size:14px;margin:4px 0}.ssc .muted{color:#94A3B8;font-size:13px}'
		. '.ssc .g5050{margin:12px 0;padding:10px 12px;border-radius:12px;background:rgba(52,211,153,.08);border:1px solid rgba(52,211,153,.3);color:#D1FAE5;font-size:13px}'
		. '.ssc form input,.ssc form textarea{width:100%;box-sizing:border-box;background:#0B1220;color:#fff;border:1px solid rgba(255,255,255,.16);border-radius:10px;padding:11px 12px;margin:6px 0;font-family:inherit;font-size:15px}'
		. '.ssc .go{width:100%;border:0;border-radius:12px;background:#F59E0B;color:#111;font-family:inherit;font-weight:800;font-size:16px;padding:14px;cursor:pointer;margin-top:6px}'
		. '.ssc a{color:#F59E0B}.ssc .ok{padding:14px;border-radius:12px;background:rgba(52,211,153,.12);color:#D1FAE5;margin-bottom:14px}'
		. '@media(max-width:760px){.ssc .g{grid-template-columns:1fr}.ssc h1{font-size:26px}.ssc .tot{position:static}}';
}

function ss_sc_html() {
	$sent = ! empty( $_GET['sent'] ) ? '<div class="ok">Thank you! We got your request and will reply by email within 24 hours.</div>' : '';
	return '<div class="ssc">' . $sent
		. '<h1>Studio price calculator</h1><p class="lead">Every service, one place. Pay 50% to start — the rest only when you love the result.</p>'
		. '<div class="g"><div class="card"><label class="t">Service</label><div class="svc" id="sc-svc"></div><div id="sc-body"></div></div>'
		. '<div class="card tot"><div class="muted" id="sc-pkg">—</div><div class="big" id="sc-total">€0</div>'
		. '<div class="row"><span>Pay now (50%)</span><b id="sc-dep">€0</b></div><div class="row"><span>Revisions</span><b id="sc-rev">—</b></div><div class="row"><span>Ready in</span><b id="sc-eta">—</b></div>'
		. '<div class="g5050">✓ 50/50 guarantee — the second half only after you approve.<br>✓ Notes on any second of every version in your dashboard.<br>✓ WAV 24-bit + MP3 320.</div>'
		. '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="ss_studio_request"><input type="hidden" name="quote" id="sc-quote"><input type="hidden" name="service" id="sc-service">'
		. '<input type="text" name="website" value="" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px" aria-hidden="true">'
		. '<input type="text" name="name" placeholder="Your name" aria-label="Your name" autocomplete="name" required><input type="email" name="email" placeholder="Email" aria-label="Email" autocomplete="email" required>'
		. '<input type="text" name="links" placeholder="Link to files (Google Drive, Dropbox, WeTransfer…)" aria-label="Link to files (Google Drive, Dropbox, WeTransfer…)"><textarea name="message" rows="3" placeholder="References, deadline, anything we should know" aria-label="References, deadline, anything we should know"></textarea>'
		. '<button class="go" type="submit">Send request — get a reply in 24 h</button><p class="muted">No payment now. We confirm the price and the date first. By sending you agree to our <a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '">Privacy Policy</a>.</p></form></div></div></div>'
		. '<script>window.SS_SC=' . wp_json_encode( ss_sc_prices() ) . ';</script><script>' . ss_sc_js() . '</script>';
}

function ss_sc_js() {
	return <<<'JS'
(function(){var P=window.SS_SC,S={svc:'mixing',n:null,pkg:0,type:'stem',ex:{},cnt:{platform:0,tuning:0,rev:0}};
var $=function(i){return document.getElementById(i)},e=function(s){return String(s).replace(/[&<>"]/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]})};
var SV=['mixing','mastering','release','copro','ghost','remix','remake','post'];
function eur(v){return '€'+(Math.round(v*100)/100).toLocaleString('en-US',{minimumFractionDigits:v%1?2:0})}
function svcBtns(){$('sc-svc').innerHTML=SV.map(function(k){return '<button type="button" data-s="'+k+'" class="'+(S.svc===k?'on':'')+'">'+e(P[k].name.replace(/ \(.*/,''))+'</button>'}).join('')}
function body(){var c=P[S.svc],h='';if(c.note)h+='<p class="muted" style="margin:12px 0 0">'+e(c.note)+'</p>';
 if(c.unit){if(S.n===null)S.n=c.def;h+='<label class="t">'+e(c.ask)+'</label><div class="num"><span id="sc-n">'+S.n+'</span> '+c.unit+'</div><input type="range" id="sc-range" min="'+c.min+'" max="'+c.max+'" value="'+S.n+'">'}
 if(c.types){h+='<label class="t">Type</label><div class="pk">'+Object.keys(c.types).map(function(k){var t=c.types[k];return '<button type="button" data-t="'+k+'" class="'+(S.type===k?'on':'')+'"><span>'+e(t[0])+'</span><b>'+eur(t[1])+'</b></button>'}).join('')+'</div>'}
 if(c.pkgs){h+='<label class="t">Package</label><div class="pk">'+c.pkgs.map(function(p,i){return '<button type="button" data-p="'+i+'" class="'+(S.pkg===i?'on':'')+'"><span>'+e(p[0])+'</span><b>from '+eur(p[1])+'</b></button>'}).join('')+(c.sub?'<button type="button" data-p="sub" class="'+(S.pkg==='sub'?'on':'')+'"><span>'+e(c.sub[0])+'</span><b>'+eur(c.sub[1])+'/mo</b></button>':'')+'</div>'}
 var X=P.extras,keys=Object.keys(X).filter(function(k){return X[k][3].indexOf(S.svc)>-1});
 if(keys.length){h+='<label class="t">Extras</label><div class="ex">'+keys.map(function(k){var x=X[k];var cnt=x[2]!=='once';return '<label><span><input type="checkbox" data-x="'+k+'" '+(S.ex[k]?'checked':'')+'> '+e(x[0])+' <small>'+eur(x[1])+(cnt?' '+e(x[2]):'')+'</small></span>'+(cnt&&S.ex[k]?'<input type="number" min="1" max="20" value="'+(S.cnt[k]||1)+'" data-c="'+k+'" style="width:64px;background:#0B1220;color:#fff;border:1px solid rgba(255,255,255,.16);border-radius:8px;padding:4px">':'')+'</label>'}).join('')+'</div>'}
 $('sc-body').innerHTML=h;calc()}
function calc(){var c=P[S.svc],base=0,name='',rev='—',eta='—',lines=[];
 if(c.tiers){var t=c.tiers.filter(function(x){return S.n<=x[0]})[0]||c.tiers[c.tiers.length-1];base=t[2]+(c.plus||0);name=t[1];rev=t[3];eta=t[4]}
 else if(c.types){var t2=c.types[S.type];var n=S.n||1;base=t2[1]*n;if(n>=c.album[0])base=base*(1-c.album[1]);name=t2[0].replace(/ —.*/,'')+(n>1?' × '+n:'');rev=t2[2];eta=t2[3]}
 else if(c.pkgs){if(S.pkg==='sub'){base=c.sub[1];name=c.sub[0];rev='—';eta='monthly'}else{var p=c.pkgs[S.pkg]||c.pkgs[0];base=p[1];name=p[0];rev=p[2]||'—';eta=p[3];if(c.unit&&p[4]&&S.n>p[4])base+=Math.ceil((S.n-p[4])/10)*p[5]}}
 lines.push(name+': '+eur(base));var tot=base,X=P.extras;
 Object.keys(S.ex).forEach(function(k){if(!S.ex[k]||X[k][3].indexOf(S.svc)<0)return;var q=X[k][2]==='once'?1:(S.cnt[k]||1);tot+=X[k][1]*q;lines.push(X[k][0]+(q>1?' × '+q:'')+': '+eur(X[k][1]*q))});
 $('sc-pkg').textContent=name;$('sc-total').textContent=(c.pkgs&&S.pkg!=='sub'?'from ':'')+eur(tot);$('sc-dep').textContent=eur(tot/2);$('sc-rev').textContent=rev;$('sc-eta').textContent=eta;
 $('sc-service').value=c.name;$('sc-quote').value=lines.join('\n')+'\nTotal: '+eur(tot)+' (50% now: '+eur(tot/2)+')'}
document.addEventListener('click',function(ev){var b=ev.target.closest&&ev.target.closest('#sc-svc button,[data-t],[data-p]');if(!b)return;
 if(b.dataset.s){S.svc=b.dataset.s;S.n=null;S.pkg=0;S.ex={};svcBtns();body()}else if(b.dataset.t){S.type=b.dataset.t;body()}else if(b.dataset.p){S.pkg=b.dataset.p==='sub'?'sub':+b.dataset.p;body()}});
document.addEventListener('input',function(ev){var t=ev.target;if(t.id==='sc-range'){S.n=+t.value;$('sc-n').textContent=S.n;calc()}else if(t.dataset&&t.dataset.c){S.cnt[t.dataset.c]=Math.max(1,+t.value||1);calc()}});
document.addEventListener('change',function(ev){var t=ev.target;if(t.dataset&&t.dataset.x){S.ex[t.dataset.x]=t.checked;body()}});
var q=new URLSearchParams(location.search).get('s');if(q&&P[q])S.svc=q;svcBtns();body()})();
JS;
}

/* (12.11.2026) one calculator: the old calculator pages send visitors (and search engines) to the new one */
add_action( 'template_redirect', function () {
	$p = strtolower( rtrim( (string) parse_url( isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '', PHP_URL_PATH ), '/' ) );
	$map = array( '/services-calculator' => 'mixing', '/en/services-calculator' => 'mixing', '/mastering-album-calculator' => 'mastering', '/en/mastering-album-calculator' => 'mastering' );
	if ( isset( $map[ $p ] ) ) { wp_redirect( home_url( '/studio-calculator/?s=' . $map[ $p ] ), 301 ); exit; }
}, 0 );

/* (12.11.2026) technical product "Product limit credit purchase" (WCFM credits, $0) is not shown in the shop, search or carousels */
define( 'SS_HIDDEN_PRODUCTS', '1971' );
add_action( 'pre_get_posts', function ( $q ) {
	if ( is_admin() && ! wp_doing_ajax() ) return;
	$pt = $q->get( 'post_type' );
	if ( 'product' !== $pt && ! ( is_array( $pt ) && in_array( 'product', $pt, true ) ) && ! $q->is_search() && ! $q->get( 'product_cat' ) ) return;
	if ( $q->is_singular() || $q->get( 'p' ) || $q->get( 'post__in' ) ) return;
	$q->set( 'post__not_in', array_merge( (array) $q->get( 'post__not_in' ), array_map( 'intval', explode( ',', SS_HIDDEN_PRODUCTS ) ) ) );
} );
