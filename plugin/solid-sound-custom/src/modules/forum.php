<?php
/**
 * (16.11.2026 owner: «Делай сейчас, мы должны создать очень сильное комьюнити!», «выкладывай форум»)
 * Community forum on wpForo (already installed, was hidden on 12.11):
 *  1. sections people come for (created once): Rate my track, Ableton help, Sound design & presets,
 *     Collabs & vocals, Releasing & promo, Lounge — next to the existing Ideas & Suggestions;
 *  2. no language barrier: everyone writes in their own language and reads in theirs. Posts are translated on demand
 *     in the browser (page loads instantly with the original), once per post + language, cached; music terms are kept
 *     (sidechain, drop, BPM, Serum…); "Show original" under every translated post. Free Cloudflare AI (ss_cf_ai);
 *  3. e-mails and phone numbers are hidden in posts (nicknames stay — owner 16.11), so deals stay on the site;
 *  4. dark look in the site's style.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_fr_ok() { return function_exists( 'WPF' ); }

/* ---------- 1. sections (once) ---------- */
add_action( 'wp_loaded', function () {
	if ( ! ss_fr_ok() || get_option( 'ss_forum_setup_v1' ) || get_transient( 'ss_forum_setup_lock' ) ) return;
	set_transient( 'ss_forum_setup_lock', 1, 120 );
	try {
		$t   = WPF()->tables->forums;
		$cat = (int) WPF()->db->get_var( "SELECT `forumid` FROM {$t} WHERE `is_cat` = 1 ORDER BY `forumid` LIMIT 1" );
		if ( ! $cat ) return;
		$have = array_map( 'strtolower', (array) WPF()->db->get_col( "SELECT `title` FROM {$t}" ) );
		$list = array(
			array( 'Rate my track', 'Post your track or a work in progress and get honest feedback from producers. Give feedback, get feedback.', 'fas fa-headphones' ),
			array( 'Ableton help', 'Stuck in Ableton Live? Routing, devices, workflow, crashes — ask here, someone has solved it.', 'fas fa-sliders-h' ),
			array( 'Sound design & presets', 'Synths, Serum, Vital, resampling, drums, FX. Share tricks and ask how a sound is made.', 'fas fa-wave-square' ),
			array( 'Collabs & vocals', 'Looking for a vocalist, a remix, a collab or a co-producer? Find each other here.', 'fas fa-handshake' ),
			array( 'Releasing & promo', 'Mixing, mastering, labels, distribution, playlists and promo — get your music out.', 'fas fa-rocket' ),
			array( 'Lounge', 'Say hi, introduce yourself, share your setup and talk about anything music.', 'fas fa-mug-hot' ),
		);
		$o = 1;
		foreach ( $list as $f ) {
			if ( in_array( strtolower( $f[0] ), $have, true ) ) continue;
			WPF()->forum->add( array( 'title' => $f[0], 'description' => $f[1], 'parentid' => $cat, 'icon' => $f[2], 'order' => $o++, 'is_cat' => 0 ), false );
		}
		update_option( 'ss_forum_setup_v1', 1, false );
	} catch ( \Throwable $e ) { error_log( 'ss forum setup: ' . $e->getMessage() ); }
}, 30 );

/* v2: the category shows its sections (layout 1 = extended forum list, it was 4 = recent threads only) and gets a real name */
add_action( 'wp_loaded', function () {
	if ( ! ss_fr_ok() || ! get_option( 'ss_forum_setup_v1' ) || get_option( 'ss_forum_setup_v2' ) ) return;
	try {
		$t   = WPF()->tables->forums;
		$cat = (int) WPF()->db->get_var( "SELECT `forumid` FROM {$t} WHERE `is_cat` = 1 ORDER BY `forumid` LIMIT 1" );
		if ( $cat ) {
			WPF()->db->update( $t, array( 'layout' => 1, 'title' => 'Solid Sound Community', 'description' => 'Producers, DJs and creators from all over the world — write in your language, read in yours.' ), array( 'forumid' => $cat ) );
			WPF()->db->query( "UPDATE {$t} SET `layout` = 1 WHERE `parentid` = {$cat}" );
			if ( function_exists( 'wpforo_clean_cache' ) ) wpforo_clean_cache();
		}
		update_option( 'ss_forum_setup_v2', 1, false );
	} catch ( \Throwable $e ) { error_log( 'ss forum setup v2: ' . $e->getMessage() ); }
}, 31 );

/* v3: the old default forum gets a clear name (it holds the Ideas & Suggestions topic) */
add_action( 'wp_loaded', function () {
	if ( ! ss_fr_ok() || ! get_option( 'ss_forum_setup_v2' ) || get_option( 'ss_forum_setup_v3' ) ) return;
	try {
		$t = WPF()->tables->forums;
		WPF()->db->query( "UPDATE {$t} SET `title` = 'Ideas & feedback', `description` = 'What should Solid Sound add or improve? New products, features, anything — we read everything.', `icon` = 'fas fa-lightbulb' WHERE `is_cat` = 0 AND `title` = 'Main Forum'" );
		if ( function_exists( 'wpforo_clean_cache' ) ) wpforo_clean_cache();
		update_option( 'ss_forum_setup_v3', 1, false );
	} catch ( \Throwable $e ) { error_log( 'ss forum setup v3: ' . $e->getMessage() ); }
}, 32 );

/* ---------- 3. hide e-mails and phone numbers (nicknames stay) ---------- */
function ss_fr_mask( $s ) {
	if ( ! is_string( $s ) || '' === $s ) return $s;
	$s = preg_replace( '/[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}/', '[e-mail hidden — please talk here]', $s );
	$s = preg_replace_callback( '/(?<![\w\/=#.])\+?\d[\d\s().\-]{7,}\d(?![\w\/])/', function ( $m ) {
		return strlen( preg_replace( '/\D/', '', $m[0] ) ) >= 9 ? '[phone hidden — please talk here]' : $m[0];
	}, $s );
	return $s;
}
foreach ( array( 'wpforo_add_post_data_filter', 'wpforo_edit_post_data_filter', 'wpforo_add_topic_data_filter', 'wpforo_edit_topic_data_filter' ) as $h ) {
	add_filter( $h, function ( $a ) {
		if ( is_array( $a ) ) foreach ( array( 'body', 'title' ) as $k ) if ( isset( $a[ $k ] ) ) $a[ $k ] = ss_fr_mask( $a[ $k ] );
		return $a;
	}, 20 );
}

/* ---------- 2. translation ---------- */
add_filter( 'wpforo_content_after', function ( $content, $post = array() ) {
	$id = is_array( $post ) ? (int) ( $post['postid'] ?? 0 ) : 0;
	return $id ? '<div class="ss-fr" data-pid="' . $id . '">' . $content . '</div>' : $content;
}, 20, 2 );

function ss_fr_lang_name( $l ) {
	$n = array( 'en' => 'English', 'uk' => 'Ukrainian', 'ru' => 'Russian', 'de' => 'German', 'fr' => 'French', 'pt' => 'Portuguese', 'es' => 'Spanish', 'it' => 'Italian', 'pl' => 'Polish', 'nl' => 'Dutch', 'tr' => 'Turkish', 'cs' => 'Czech', 'ro' => 'Romanian', 'ja' => 'Japanese', 'zh' => 'Chinese', 'ko' => 'Korean', 'ar' => 'Arabic', 'he' => 'Hebrew', 'sv' => 'Swedish', 'fi' => 'Finnish', 'el' => 'Greek', 'hu' => 'Hungarian', 'bg' => 'Bulgarian', 'sr' => 'Serbian', 'hr' => 'Croatian', 'lt' => 'Lithuanian', 'lv' => 'Latvian', 'et' => 'Estonian', 'ka' => 'Georgian', 'kk' => 'Kazakh' );
	return $n[ $l ] ?? '';
}
function ss_fr_ajax() {
	$lang = sanitize_key( (string) ( $_POST['lang'] ?? '' ) );
	$name = ss_fr_lang_name( $lang );
	$ids  = array_slice( array_filter( array_map( 'intval', explode( ',', (string) ( $_POST['ids'] ?? '' ) ) ) ), 0, 15 );
	if ( ! $name || ! $ids || ! ss_fr_ok() || ! function_exists( 'ss_cf_ai' ) ) wp_send_json_success( array() );
	$ip = 'ss_fr_rl_' . md5( (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) );
	$n  = (int) get_transient( $ip );
	$out = array();
	foreach ( $ids as $id ) {
		$p = WPF()->post->get_post( $id );
		if ( empty( $p['body'] ) ) continue;
		$txt = trim( html_entity_decode( wp_strip_all_tags( str_replace( array( '<br>', '<br/>', '<br />', '</p>' ), "\n", (string) $p['body'] ) ), ENT_QUOTES ) );
		if ( mb_strlen( $txt ) < 3 ) continue;
		$key = 'ss_fr_' . md5( $lang . '|' . $txt );
		$c   = get_transient( $key );
		if ( false === $c ) {
			if ( $n >= 120 ) break; /* per visitor per hour — the free AI is shared */
			$sys = "You translate posts of a music production forum (Ableton Live, synths, mixing, DJs) into {$name}.\n"
				. "Keep unchanged: music and studio terms producers use in English (sidechain, drop, kick, groove, BPM, EQ, compressor, reverb, delay, sample, loop, preset, stems, mixdown, master, bus, send, return, warp, clip, arrangement, session), plugin, synth, DAW, brand and artist names (Serum, Vital, Ableton Live 12, Valhalla…), numbers, URLs, @nicknames, emojis.\n"
				. "Keep line breaks. Natural, friendly forum tone; do not add anything. If the text is already in {$name}, reply exactly: SAME. Output only the translation.";
			$r = ss_cf_ai( '@cf/meta/llama-3.1-8b-instruct', array( 'messages' => array( array( 'role' => 'system', 'content' => $sys ), array( 'role' => 'user', 'content' => mb_substr( $txt, 0, 4000 ) ) ), 'max_tokens' => 1200, 'temperature' => 0.2 ) );
			$n++;
			if ( is_wp_error( $r ) ) continue;
			$c = trim( (string) ( $r['response'] ?? '' ) );
			if ( '' === $c ) continue;
			if ( 0 === stripos( $c, 'SAME' ) && mb_strlen( $c ) < 12 ) $c = '=';
			set_transient( $key, $c, 60 * DAY_IN_SECONDS );
		}
		if ( '=' !== $c ) $out[ $id ] = $c;
	}
	set_transient( $ip, $n, HOUR_IN_SECONDS );
	wp_send_json_success( $out );
}
add_action( 'wp_ajax_ss_fr_tr', 'ss_fr_ajax' );
add_action( 'wp_ajax_nopriv_ss_fr_tr', 'ss_fr_ajax' );

/* ---------- 4. look + translation script (forum pages only) ---------- */
function ss_fr_is_page() {
	if ( function_exists( 'is_wpforo_page' ) ) return is_wpforo_page();
	$p = (string) wp_parse_url( (string) ( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH );
	return (bool) preg_match( '#^/([a-z]{2}/)?(forum|community)(/|$)#', $p );
}
add_action( 'wp_head', function () {
	if ( ! ss_fr_ok() || ! ss_fr_is_page() ) return; ?>
<style id="ss-forum-css">
#wpforo #wpforo-wrap{--fbg:#0b1020;--fcard:#111a2e;--fcard2:#0f1626;--fline:rgba(255,255,255,.09);--ftx:#e2e8f0;--fmut:#94a3b8;--for:#f59e0b;color:var(--ftx);background:transparent!important;font-family:Roboto,system-ui,sans-serif;font-size:15px}
#wpforo #wpforo-wrap a{color:#fbbf24}#wpforo #wpforo-wrap a:hover{color:#fff}
#wpforo #wpforo-wrap .wpforo-main,#wpforo #wpforo-wrap .wpforo-content,#wpforo #wpforo-wrap .wpforo-subtop,#wpforo #wpforo-wrap .wpf-thread-box,#wpforo #wpforo-wrap .wpf-head-bar,#wpforo #wpforo-wrap .wpforo-section,#wpforo #wpforo-wrap .wpforo-category,#wpforo #wpforo-wrap .wpf-cat-forums,#wpforo #wpforo-wrap .wpforo-post,#wpforo #wpforo-wrap .wpf-forum-item,#wpforo #wpforo-wrap .wpf-threads,#wpforo #wpforo-wrap .wpf-stat-item,#wpforo #wpforo-wrap .wpf-head-box,#wpforo #wpforo-wrap .wpforo-footer,#wpforo #wpforo-wrap .wpf-footer-box,#wpforo #wpforo-wrap .wpf-stat{background:transparent!important;color:var(--ftx)!important;border-color:var(--fline)!important}
#wpforo #wpforo-wrap .wpforo-category,#wpforo #wpforo-wrap .wpforo-head,#wpforo #wpforo-wrap .wpf-head-box,#wpforo #wpforo-wrap .wpforo-footer .wpf-footer-title,#wpforo #wpforo-wrap .wpf-footer-box .wpf-head-box{background:linear-gradient(90deg,rgba(245,158,11,.16),rgba(167,139,250,.12))!important;color:#fff!important;border:1px solid rgba(245,158,11,.25)!important;border-radius:14px}
#wpforo #wpforo-wrap .wpforo-category *{color:#fff!important}
#wpforo #wpforo-wrap .wpf-forum-item,#wpforo #wpforo-wrap .wpforo-post,#wpforo #wpforo-wrap .wpf-thread,#wpforo #wpforo-wrap .wpf-topic{background:var(--fcard)!important;border:1px solid var(--fline)!important;border-radius:14px;margin:8px 0!important}
#wpforo #wpforo-wrap .wpf-forum-item:hover,#wpforo #wpforo-wrap .wpf-thread:hover{border-color:rgba(245,158,11,.4)!important}
#wpforo #wpforo-wrap h1,#wpforo #wpforo-wrap h2,#wpforo #wpforo-wrap h3,#wpforo #wpforo-wrap .wpf-forum-title,#wpforo #wpforo-wrap .wpforo-forum-title{color:#fff!important;font-family:"Exo 2",Roboto,sans-serif}
#wpforo #wpforo-wrap .wpf-forum-desc,#wpforo #wpforo-wrap .wpf-stat-label,#wpforo #wpforo-wrap .wpf-thead-status,#wpforo #wpforo-wrap small,#wpforo #wpforo-wrap .wpf-forum-item-stat{color:var(--fmut)!important}
#wpforo #wpforo-wrap .wpf-forum-icon i,#wpforo #wpforo-wrap .wpf-circle i,#wpforo #wpforo-wrap .wpf-forum-item i.fas{color:var(--for)!important}
#wpforo #wpforo-wrap .wpf-button,#wpforo #wpforo-wrap .wpf-button-primary,#wpforo #wpforo-wrap input[type=submit],#wpforo #wpforo-wrap button.wpf-button{background:var(--for)!important;color:#111!important;border:0!important;border-radius:10px!important;font-weight:800!important;box-shadow:none!important}
#wpforo #wpforo-wrap input[type=text],#wpforo #wpforo-wrap input[type=search],#wpforo #wpforo-wrap textarea,#wpforo #wpforo-wrap select{background:#0b1220!important;color:#f8fafc!important;border:1px solid var(--fline)!important;border-radius:10px!important;font-size:16px!important}
#wpforo #wpforo-wrap .wpf-stat-value,#wpforo #wpforo-wrap .wpf-stat-item i{color:#fff!important}
#wpforo #wpforo-wrap #wpforo-menu,#wpforo #wpforo-wrap #wpforo-stat-header,#wpforo #wpforo-wrap #wpforo-stat-body,#wpforo #wpforo-wrap #wpforo-poweredby{background:#0f1626!important;color:#e2e8f0!important;border-color:rgba(255,255,255,.08)!important}
#wpforo #wpforo-wrap #wpforo-stat-header{border-radius:14px 14px 0 0}#wpforo #wpforo-wrap #wpforo-stat-body{border-radius:0 0 14px 14px}
#wpforo #wpforo-wrap #wpforo-stat-body *{color:#cbd5e1!important}#wpforo #wpforo-wrap #wpforo-stat-body a{color:#fbbf24!important}#wpforo #wpforo-wrap #wpforo-menu a,#wpforo #wpforo-wrap #wpforo-menu *{color:#e2e8f0!important}
#wpforo #wpforo-wrap .wpforo-forum{background:#111a2e!important;border-bottom:1px solid rgba(255,255,255,.08)!important;color:#e2e8f0!important}#wpforo #wpforo-wrap .wpforo-forum:hover{background:#15203a!important}#wpforo #wpforo-wrap .wpforo-forum *{color:inherit}#wpforo #wpforo-wrap .wpforo-forum .wpforo-forum-title a,#wpforo #wpforo-wrap .wpforo-forum h3 a{color:#fbbf24!important;font:700 18px "Exo 2",sans-serif}#wpforo #wpforo-wrap .wpforo-forum-description{color:#a3b1c6!important}#wpforo #wpforo-wrap .wpforo-forum .wpforo-forum-icon i,#wpforo #wpforo-wrap .wpforo-forum i.fas{color:#f59e0b!important}
#wpforo #wpforo-wrap .cat-title{color:#fff!important;font:800 18px "Exo 2",sans-serif!important}
#wpforo #wpforo-wrap .wpforo-post-content,#wpforo #wpforo-wrap .wpforo-post-content *{color:var(--ftx)!important;font-size:16px;line-height:1.6}
#wpforo #wpforo-wrap .wpf-breadcrumb .wpf-item-element,#wpforo #wpforo-wrap .wpf-breadcrumb a{background:var(--fcard2)!important;color:var(--ftx)!important;border-color:var(--fline)!important}
#wpforo #wpforo-wrap .wpf-breadcrumb .wpf-item-element:after,#wpforo #wpforo-wrap .wpf-breadcrumb .wpf-item-element:before{display:none!important}
.ss-fr-bar{display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin:10px 0 0;font:600 12.5px Roboto,sans-serif;color:#94a3b8}
.ss-fr-bar b{color:#a78bfa}.ss-fr-bar button{background:none;border:0;padding:0;color:#fbbf24;font:700 12.5px Roboto,sans-serif;cursor:pointer;text-decoration:underline}
.ss-fr-t{white-space:pre-wrap}
.ss-fr-lang{display:flex;gap:8px;align-items:center;justify-content:flex-end;margin:6px 0 10px;font:600 13px Roboto,sans-serif;color:#94a3b8}
.ss-fr-lang select{background:#0b1220;color:#fff;border:1px solid rgba(255,255,255,.15);border-radius:8px;height:32px;padding:0 8px}
</style>
	<?php
}, 30 );

add_action( 'wp_footer', function () {
	if ( ! ss_fr_ok() || ! ss_fr_is_page() ) return;
	$langs = array( 'en' => 'English', 'uk' => 'Українська', 'ru' => 'Русский', 'de' => 'Deutsch', 'fr' => 'Français', 'pt' => 'Português', 'es' => 'Español', 'it' => 'Italiano', 'pl' => 'Polski', 'nl' => 'Nederlands', 'tr' => 'Türkçe' ); ?>
<script id="ss-forum-js">(function(){
var AJAX=<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>,L=<?php echo wp_json_encode( $langs ); ?>,NAMES={en:'English',uk:'Ukrainian',ru:'Russian',de:'German',fr:'French',pt:'Portuguese',es:'Spanish',it:'Italian',pl:'Polish',nl:'Dutch',tr:'Turkish'};
function get(k){try{return localStorage.getItem(k)}catch(e){return null}}function set(k,v){try{localStorage.setItem(k,v)}catch(e){}}
var lang=get('ss_fr_lang')||(navigator.language||'en').slice(0,2).toLowerCase();if(!L[lang])lang='en';
var off=get('ss_fr_off')==='1';
function bar(box,name){var b=document.createElement('div');b.className='ss-fr-bar';b.innerHTML='<span>🌐 Translated to <b></b> by AI</span><button type="button">Show original</button>';b.querySelector('b').textContent=name;b.querySelector('button').onclick=function(){var t=box.querySelector('.ss-fr-t'),o=box.querySelector('.ss-fr-o');var sh=t.style.display!=='none';t.style.display=sh?'none':'';o.style.display=sh?'':'none';this.textContent=sh?'Show translation':'Show original'};return b}
function apply(map){Object.keys(map).forEach(function(id){var box=document.querySelector('.ss-fr[data-pid="'+id+'"]');if(!box||box.dataset.done)return;box.dataset.done=1;var o=document.createElement('div');o.className='ss-fr-o';while(box.firstChild)o.appendChild(box.firstChild);o.style.display='none';var t=document.createElement('div');t.className='ss-fr-t';t.textContent=map[id];box.appendChild(t);box.appendChild(o);box.appendChild(bar(box,NAMES[lang]||lang))})}
function run(){if(off)return;var ids=[].map.call(document.querySelectorAll('.ss-fr[data-pid]:not([data-done]):not([data-asked])'),function(e){e.dataset.asked=1;return e.getAttribute('data-pid')});
 for(var i=0;i<ids.length;i+=15){var fd=new FormData();fd.append('action','ss_fr_tr');fd.append('lang',lang);fd.append('ids',ids.slice(i,i+15).join(','));fetch(AJAX,{method:'POST',body:fd,credentials:'same-origin'}).then(function(r){return r.json()}).then(function(j){if(j&&j.success)apply(j.data)}).catch(function(){})}}
function picker(){var w=document.querySelector('#wpforo #wpforo-wrap');if(!w||document.querySelector('.ss-fr-lang'))return;var d=document.createElement('div');d.className='ss-fr-lang';var opts=Object.keys(L).map(function(k){return '<option value="'+k+'"'+(k===lang?' selected':'')+'>'+L[k]+'</option>'}).join('');d.innerHTML='<span>🌐 Read the forum in</span><select aria-label="Forum language">'+opts+'<option value="off"'+(off?' selected':'')+'>Original (no translation)</option></select>';d.querySelector('select').onchange=function(){if(this.value==='off'){set('ss_fr_off','1')}else{set('ss_fr_off','0');set('ss_fr_lang',this.value)}location.reload()};w.insertBefore(d,w.firstChild)}
var TR=<?php echo wp_json_encode( (array) include __DIR__ . '/forum-i18n.php' ); ?>;
/* interface in the reader's language: exact texts of menus, sections, labels (owner 17.11: «На форуме не работает перевод») */
function ui(){var d=TR[lang];if(!d||off)return;var root=document.getElementById('wpforo');if(!root)return;
 var w=document.createTreeWalker(root,NodeFilter.SHOW_TEXT,null),n,k;while((n=w.nextNode())){k=n.nodeValue.trim();if(k&&d[k])n.nodeValue=n.nodeValue.replace(k,d[k]);}
 root.querySelectorAll('input[placeholder]').forEach(function(i){var t=d[i.placeholder.trim()];if(t)i.placeholder=t;});}
var uiT=0;function uiLater(){if(uiT)return;uiT=requestAnimationFrame(function(){uiT=0;ui();});}
function go(){picker();run();ui();var r=document.getElementById('wpforo');if(r&&window.MutationObserver)new MutationObserver(uiLater).observe(r,{childList:true,subtree:true});}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',go);else go();
})();</script>
	<?php
}, 40 );

/* ---------- 5. (17.11.2026, owner: "делай") page frame + a real menu bar ----------
   - no big empty gap above the forum, side margins like the rest of the site (it touched the screen edge);
   - the wpForo menu bar was empty (no menu assigned) with an almost invisible search: now it has
     Forums · New posts · Sign in / Join (site account) and a clear "Search the forum" field. */
add_action( 'wp_head', function () {
	if ( ! ss_fr_ok() || ! ss_fr_is_page() ) return;
	echo '<style id="ss-forum-frame">'
		. 'html.is_wpforo_page-1 .l-main>.l-section:first-child{padding-top:14px!important}'
		. '#wpforo{max-width:1180px;margin:0 auto!important;padding:0 16px!important;box-sizing:border-box}'
		. '#wpforo #wpforo-wrap #wpforo-menu{display:flex!important;align-items:center;gap:10px;flex-wrap:wrap;padding:8px 10px!important;border-radius:6px!important}'
		. '#wpforo #wpforo-wrap #wpforo-menu .wpf-left{display:flex!important;gap:6px;flex-wrap:wrap;flex:1 1 auto}'
		. '#wpforo #wpforo-wrap #wpforo-menu .ss-fm{display:inline-flex;align-items:center;height:36px;padding:0 12px;border-radius:6px;border:1px solid rgba(255,255,255,.12);background:#131b2e;color:#e2e8f0!important;font:700 14px "Exo 2",Roboto,sans-serif;text-decoration:none}'
		. '#wpforo #wpforo-wrap #wpforo-menu .ss-fm:hover,#wpforo #wpforo-wrap #wpforo-menu .ss-fm.on{border-color:#f59e0b;color:#f59e0b!important}'
		. '#wpforo #wpforo-wrap #wpforo-menu .ss-fm.pri{background:#f59e0b;border-color:#f59e0b;color:#111!important}'
		. '#wpforo #wpforo-wrap #wpforo-menu .wpf-search{flex:0 1 260px;display:flex!important}'
		. '#wpforo #wpforo-wrap #wpforo-menu .wpf-search-form,#wpforo #wpforo-wrap #wpforo-menu .wpf-search-form form{width:100%;position:relative;display:block}'
		. '#wpforo #wpforo-wrap #wpforo-menu .wpf-search-field{width:100%!important;height:36px!important;margin:0!important;padding:0 10px 0 34px!important;border-radius:6px!important}'
		. '#wpforo #wpforo-wrap #wpforo-menu .wpf-search-form i{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8!important;z-index:1}'
		. '.ss-fr-lang{margin:4px 0 8px!important}'
		. '@media(max-width:600px){#wpforo{padding:0 10px!important}#wpforo #wpforo-wrap #wpforo-menu .wpf-search{flex:1 1 100%}}'
		. '</style>';
}, 31 );
add_action( 'wp_footer', function () {
	if ( ! ss_fr_ok() || ! ss_fr_is_page() ) return;
	$base = function_exists( 'wpforo_home_url' ) ? wpforo_home_url() : home_url( '/community/' );
	$acc  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
	$items = array( array( 'Forums', $base, '' ), array( 'New posts', trailingslashit( $base ) . 'recent/', '' ) );
	if ( is_user_logged_in() ) $items[] = array( 'My account', $acc, '' );
	else { $items[] = array( 'Sign in', add_query_arg( 'ss_tab', 'login', $acc ), '' ); $items[] = array( 'Join the community', add_query_arg( 'ss_tab', 'reg', $acc ), 'pri' ); }
	echo '<script>(function(){var I=' . wp_json_encode( $items ) . ';function go(){var m=document.querySelector("#wpforo #wpforo-menu"),l=m&&m.querySelector(".wpf-left");if(!l||l.querySelector(".ss-fm"))return;var here=location.href.split(/[?#]/)[0].replace(/\/$/,"");I.forEach(function(x){var a=document.createElement("a");a.className="ss-fm"+(x[2]?" "+x[2]:"")+(x[1].split(/[?#]/)[0].replace(/\/$/,"")===here?" on":"");a.href=x[1];a.textContent=x[0];l.appendChild(a)});var s=m.querySelector(".wpf-search-field");if(s&&!s.placeholder)s.placeholder="Search the forum";}if(document.readyState!=="loading")go();else document.addEventListener("DOMContentLoaded",go);})();</script>';
}, 40 );
