<?php
/**
 * Desktop / phone notifications (Web Push), self-hosted, free (owner 21.11.2026: «уведомления на рабочий стол для форума
 * или ответов с чата» → «делай всё бесплатное»). No third-party service: our own VAPID keys, our own service worker.
 *  - Logged-in users turn it on with one tap after they post on the forum (or on the forum / client dashboard pages).
 *  - A reply in a forum topic → the topic author and everyone who replied in it get a notification; admins get every new post.
 *  - The push carries no data (no encryption library needed): the service worker asks the site what is new and shows it.
 *  - iPhone: works only when the site is added to the Home Screen (Apple's rule).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_push_b64u( $s ) { return rtrim( strtr( base64_encode( $s ), '+/', '-_' ), '=' ); }
/* the site's VAPID key pair: private PEM + public key (65-byte uncompressed point, base64url) */
function ss_push_keys() {
	$k = get_option( 'ss_push_vapid' );
	if ( is_array( $k ) && ! empty( $k['pem'] ) && ! empty( $k['pub'] ) ) return $k;
	if ( ! function_exists( 'openssl_pkey_new' ) ) return null;
	$r = openssl_pkey_new( array( 'curve_name' => 'prime256v1', 'private_key_type' => OPENSSL_KEYTYPE_EC ) ); if ( ! $r ) return null;
	openssl_pkey_export( $r, $pem ); $d = openssl_pkey_get_details( $r );
	$pad = function ( $x ) { return str_pad( $x, 32, "\0", STR_PAD_LEFT ); };
	$k = array( 'pem' => $pem, 'pub' => ss_push_b64u( "\x04" . $pad( $d['ec']['x'] ) . $pad( $d['ec']['y'] ) ) );
	update_option( 'ss_push_vapid', $k, false );
	return $k;
}
/* VAPID header for one push service (ES256 JWT) */
function ss_push_vapid_header( $endpoint ) {
	$k = ss_push_keys(); if ( ! $k ) return '';
	$p = wp_parse_url( $endpoint ); if ( empty( $p['host'] ) ) return '';
	$h = ss_push_b64u( wp_json_encode( array( 'typ' => 'JWT', 'alg' => 'ES256' ) ) );
	$c = ss_push_b64u( wp_json_encode( array( 'aud' => $p['scheme'] . '://' . $p['host'], 'exp' => time() + 12 * HOUR_IN_SECONDS, 'sub' => home_url( '/' ) ) ) );
	if ( ! openssl_sign( "$h.$c", $der, $k['pem'], OPENSSL_ALGO_SHA256 ) ) return '';
	/* DER (SEQUENCE{INTEGER r, INTEGER s}) → raw 64 bytes r||s */
	$o = 3; if ( ord( $der[1] ) & 0x80 ) $o += ord( $der[1] ) & 0x7f;
	$rl = ord( $der[ $o ] ); $r = substr( $der, $o + 1, $rl ); $o += 1 + $rl + 1;
	$sl = ord( $der[ $o ] ); $s = substr( $der, $o + 1, $sl );
	$fix = function ( $x ) { return str_pad( ltrim( $x, "\0" ), 32, "\0", STR_PAD_LEFT ); };
	return 'vapid t=' . "$h.$c." . ss_push_b64u( $fix( $r ) . $fix( $s ) ) . ', k=' . $k['pub'];
}

/* queue a notification for a user and wake their devices (sent in the background, the poster does not wait) */
function ss_push_notify( $uid, $title, $body, $url, $tag = '' ) {
	$uid = (int) $uid; if ( ! $uid || ! get_user_meta( $uid, '_ss_push_subs', true ) ) return;
	$q = (array) get_user_meta( $uid, '_ss_push_q', true ); $q = array_filter( $q, 'is_array' );
	$q[] = array( 't' => wp_strip_all_tags( $title ), 'b' => wp_strip_all_tags( $body ), 'u' => esc_url_raw( $url ), 'g' => $tag, 'ts' => time() );
	update_user_meta( $uid, '_ss_push_q', array_slice( $q, -10 ) );
	if ( ! wp_next_scheduled( 'ss_push_send', array( $uid ) ) ) wp_schedule_single_event( time(), 'ss_push_send', array( $uid ) );
}
add_action( 'ss_push_send', function ( $uid ) {
	$subs = (array) get_user_meta( $uid, '_ss_push_subs', true ); $keep = array();
	foreach ( $subs as $ep ) {
		if ( ! is_string( $ep ) || 0 !== strpos( $ep, 'https://' ) ) continue;
		$auth = ss_push_vapid_header( $ep ); if ( ! $auth ) { $keep[] = $ep; continue; }
		$r = wp_remote_post( $ep, array( 'timeout' => 8, 'headers' => array( 'TTL' => '86400', 'Urgency' => 'normal', 'Authorization' => $auth, 'Content-Length' => '0' ), 'body' => '' ) );
		$code = is_wp_error( $r ) ? 0 : (int) wp_remote_retrieve_response_code( $r );
		if ( 404 !== $code && 410 !== $code ) $keep[] = $ep; /* gone = the user turned notifications off or reinstalled the browser */
	}
	update_user_meta( $uid, '_ss_push_subs', array_values( array_unique( $keep ) ) );
} );

/* service worker at the site root (scope «/») and the «what's new» endpoint it calls */
add_action( 'init', function () {
	if ( isset( $_GET['ss_sw'] ) ) {
		nocache_headers(); header( 'Content-Type: application/javascript; charset=utf-8' ); header( 'Service-Worker-Allowed: /' );
		echo "self.addEventListener('install',function(){self.skipWaiting()});self.addEventListener('activate',function(e){e.waitUntil(self.clients.claim())});\n"
			. "self.addEventListener('push',function(e){e.waitUntil(fetch('/?ss_push_last=1',{credentials:'include',cache:'no-store'}).then(function(r){return r.json()}).catch(function(){return {}}).then(function(n){n=n||{};return self.registration.showNotification(n.t||'Solid Sound',{body:n.b||'You have something new on Solid Sound.',icon:n.i||'/wp-content/uploads/cropped-favicon-192x192.png',badge:n.i||undefined,tag:n.g||'ss',renotify:true,data:{u:n.u||'/'}})}))});\n"
			. "self.addEventListener('notificationclick',function(e){e.notification.close();var u=(e.notification.data||{}).u||'/';e.waitUntil(self.clients.matchAll({type:'window',includeUncontrolled:true}).then(function(cs){for(var i=0;i<cs.length;i++){if(cs[i].url===u&&'focus' in cs[i])return cs[i].focus()}return self.clients.openWindow(u)}))});\n";
		exit;
	}
	if ( isset( $_GET['ss_push_last'] ) ) {
		nocache_headers(); header( 'Content-Type: application/json; charset=utf-8' );
		$uid = get_current_user_id(); $n = array();
		if ( $uid ) { $q = array_values( array_filter( (array) get_user_meta( $uid, '_ss_push_q', true ), 'is_array' ) ); if ( $q ) { $n = array_shift( $q ); update_user_meta( $uid, '_ss_push_q', $q ); } }
		$icon = get_site_icon_url( 192 ); if ( $icon ) $n['i'] = $icon;
		echo wp_json_encode( $n ); exit;
	}
}, 1 );

/* save / remove this device */
add_action( 'wp_ajax_ss_push_sub', function () {
	check_ajax_referer( 'ss_push', 'n' );
	$ep = esc_url_raw( wp_unslash( $_POST['ep'] ?? '' ) ); if ( 0 !== strpos( $ep, 'https://' ) ) wp_send_json_error();
	$uid = get_current_user_id(); $s = (array) get_user_meta( $uid, '_ss_push_subs', true );
	if ( ! empty( $_POST['off'] ) ) $s = array_diff( $s, array( $ep ) ); else $s[] = $ep;
	update_user_meta( $uid, '_ss_push_subs', array_slice( array_values( array_unique( array_filter( $s, 'is_string' ) ) ), -5 ) );
	wp_send_json_success();
} );

/* forum (wpForo): a reply → topic author + people who replied in the topic; every new post → admins */
add_action( 'wpforo_after_add_post', function ( $post, $topic = array() ) {
	$post = (array) $post; $topic = (array) $topic; $by = (int) ( $post['userid'] ?? 0 ); $tid = (int) ( $post['topicid'] ?? 0 ); if ( ! $tid ) return;
	$url = function_exists( 'wpforo_post' ) ? (string) wpforo_post( (int) ( $post['postid'] ?? 0 ), 'url' ) : '';
	if ( ! $url ) $url = function_exists( 'wpforo_topic' ) ? (string) wpforo_topic( $tid, 'url' ) : home_url( '/en/forum/' );
	$who = $by ? get_the_author_meta( 'display_name', $by ) : 'Someone';
	$tt = wp_strip_all_tags( (string) ( $topic['title'] ?? ( function_exists( 'wpforo_topic' ) ? wpforo_topic( $tid, 'title' ) : 'your topic' ) ) );
	$body = mb_substr( trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( (string) ( $post['body'] ?? '' ) ) ) ), 0, 140 );
	$to = array(); if ( ! empty( $topic['userid'] ) ) $to[] = (int) $topic['userid'];
	global $wpdb; if ( function_exists( 'WPF' ) && ! empty( WPF()->tables->posts ) ) $to = array_merge( $to, array_map( 'intval', (array) $wpdb->get_col( $wpdb->prepare( 'SELECT DISTINCT userid FROM ' . WPF()->tables->posts . ' WHERE topicid = %d LIMIT 50', $tid ) ) ) );
	foreach ( get_users( array( 'role' => 'administrator', 'fields' => 'ID' ) ) as $a ) $to[] = (int) $a;
	foreach ( array_unique( $to ) as $u ) if ( $u && $u !== $by ) ss_push_notify( $u, $who . ' replied: ' . $tt, $body, $url, 'forum-' . $tid );
}, 20, 2 );
add_action( 'wpforo_after_add_topic', function ( $topic ) {
	$topic = (array) $topic; $by = (int) ( $topic['userid'] ?? 0 ); $url = (string) ( $topic['topicurl'] ?? '' );
	if ( ! $url ) $url = function_exists( 'wpforo_topic' ) ? (string) wpforo_topic( (int) ( $topic['topicid'] ?? 0 ), 'url' ) : home_url( '/en/forum/' );
	foreach ( get_users( array( 'role' => 'administrator', 'fields' => 'ID' ) ) as $a ) if ( (int) $a !== $by ) ss_push_notify( (int) $a, 'New forum topic: ' . wp_strip_all_tags( (string) ( $topic['title'] ?? '' ) ), $by ? get_the_author_meta( 'display_name', $by ) : '', $url, 'forum-new' );
}, 20 );

/* «Turn on notifications» bar: logged-in users, forum + client dashboard + account; asks the browser only after a tap */
add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() ) return;
	$forum = function_exists( 'is_wpforo_page' ) && is_wpforo_page();
	$dash = is_page( 2561 ) || ( function_exists( 'is_account_page' ) && is_account_page() );
	if ( ! $forum && ! $dash ) return;
	$k = ss_push_keys(); if ( ! $k ) return; ?>
<div id="ss-push" hidden role="region" aria-label="Notifications"><span>🔔 Get a notification when someone replies to you — even when this site is closed.</span><button type="button" class="on">Turn on</button><button type="button" class="x" aria-label="Not now">×</button></div>
<style>#ss-push{position:fixed;z-index:9990;left:50%;bottom:84px;transform:translateX(-50%);display:flex;align-items:center;gap:10px;max-width:calc(100vw - 24px);box-sizing:border-box;padding:10px 10px 10px 16px;border-radius:12px;background:#131B2E;border:1px solid rgba(245,158,11,.4);box-shadow:0 10px 30px rgba(0,0,0,.45);color:#E2E8F0;font:500 14px/1.4 Roboto,sans-serif}#ss-push[hidden]{display:none}#ss-push .on{flex:none;height:38px;padding:0 16px;border:0;border-radius:8px;background:#F59E0B;color:#111;font:700 14px "Exo 2",sans-serif;cursor:pointer}#ss-push .x{flex:none;width:34px;height:34px;border:0;border-radius:8px;background:none;color:#94A3B8;font-size:22px;cursor:pointer}@media(min-width:900px){#ss-push{bottom:24px}}</style>
<script>(function(){if(!('serviceWorker' in navigator)||!('PushManager' in window)||!window.Notification)return;var B=document.getElementById('ss-push'),K='<?php echo esc_js( $k['pub'] ); ?>',N='<?php echo esc_js( wp_create_nonce( 'ss_push' ) ); ?>',A='<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>';
function key(s){s=s.replace(/-/g,'+').replace(/_/g,'/');while(s.length%4)s+='=';var r=atob(s),a=new Uint8Array(r.length);for(var i=0;i<r.length;i++)a[i]=r.charCodeAt(i);return a}
function save(sub){var f=new FormData();f.append('action','ss_push_sub');f.append('n',N);f.append('ep',sub.endpoint);return fetch(A,{method:'POST',body:f,credentials:'same-origin'})}
function sub(){return navigator.serviceWorker.register('/?ss_sw=1',{scope:'/'}).then(function(r){return navigator.serviceWorker.ready.then(function(){return r.pushManager.getSubscription().then(function(s){return s||r.pushManager.subscribe({userVisibleOnly:true,applicationServerKey:key(K)})})})}).then(save)}
var seen=0;try{seen=+localStorage.getItem('ssPushNo')||0}catch(e){}
if(Notification.permission==='granted'){sub().catch(function(){});return}
if(Notification.permission==='denied')return;if(Date.now()-seen<14*864e5)return;
setTimeout(function(){B.hidden=false},2500);
B.querySelector('.on').onclick=function(){B.hidden=true;Notification.requestPermission().then(function(p){if(p==='granted')sub().catch(function(){})})};
B.querySelector('.x').onclick=function(){B.hidden=true;try{localStorage.setItem('ssPushNo',Date.now())}catch(e){}};
})();</script>
	<?php
}, 80 );
