<?php
/**
 * (18.11.2026 owner: «позитивное настроение для авторов и клиентов… чувство что ты нужен, шутки, поддержка», «Делай и выкладывай»)
 * Mockup: mockups/positive-*.png
 *  1. Welcome: forum section "Say hi"; a new creator (sign-up) or a new buyer (first paid order) gets a short intro topic there,
 *     the community host (user 1 "Solid Sound") answers warmly in a minute or two (Cloudflare AI, template if AI is off).
 *     Anyone who starts a topic in Say hi gets the same welcome. "New face" badge for 14 days, "Community host" badge.
 *     Reactions 👋 🔥 🙌 😂 under every forum post (signed-in members; guests see the counts).
 *  2. Thank the author: 🙏 Thanks / 🔥 Fire / 💡 Inspired me (+ optional one line, contacts hidden) on every product page.
 *     The author sees them in the creator space ("Someone from Germany thinks Kratos is fire!").
 *  3. Small wins in the creator space: first product, first 10 plays, first thanks, first sale, first review, 100 plays —
 *     a confetti card once per new win + the ladder. Monday e-mail: plays and thanks of the week (only when there is
 *     something; one click turns it off).
 * Only real numbers. Off switch: option ss_kudos_off = 1.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_kd_on() { return ! get_option( 'ss_kudos_off' ); }
function ss_kd_table() { global $wpdb; return $wpdb->prefix . 'ss_kudos'; }
define( 'SS_KD_HOST', 1 ); /* the admin account, shown as "Solid Sound" on the forum */

/* ---------- storage (once) ---------- */
add_action( 'init', function () {
	if ( '2' === get_option( 'ss_kudos_db' ) ) return;
	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( 'CREATE TABLE ' . ss_kd_table() . " (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		kind varchar(8) NOT NULL,
		obj bigint(20) unsigned NOT NULL,
		owner bigint(20) unsigned NOT NULL DEFAULT 0,
		who varchar(40) NOT NULL,
		type varchar(12) NOT NULL,
		msg varchar(200) NOT NULL DEFAULT '',
		cc char(2) NOT NULL DEFAULT '',
		created datetime NOT NULL,
		PRIMARY KEY  (id),
		UNIQUE KEY one (kind,obj,who,type),
		KEY own (owner,created),
		KEY ob (kind,obj)
	) " . $wpdb->get_charset_collate() . ';' );
	update_option( 'ss_kudos_db', '2', true );
}, 5 );

function ss_kd_who() {
	$u = get_current_user_id();
	if ( $u ) return 'u' . $u;
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) $_SERVER['REMOTE_ADDR'] : '';
	return 'i' . substr( hash_hmac( 'sha256', $ip, wp_salt( 'nonce' ) ), 0, 30 );
}
function ss_kd_cc() {
	$c = isset( $_SERVER['HTTP_CF_IPCOUNTRY'] ) ? strtoupper( substr( (string) $_SERVER['HTTP_CF_IPCOUNTRY'], 0, 2 ) ) : '';
	if ( ! preg_match( '/^[A-Z]{2}$/', $c ) || 'XX' === $c || 'T1' === $c ) $c = '';
	if ( ! $c && class_exists( 'WC_Geolocation' ) ) { try { $g = WC_Geolocation::geolocate_ip( WC_Geolocation::get_ip_address(), true, false ); $c = strtoupper( (string) ( $g['country'] ?? '' ) ); } catch ( \Throwable $e ) {} }
	if ( ! $c && get_current_user_id() ) $c = strtoupper( (string) get_user_meta( get_current_user_id(), 'billing_country', true ) );
	return preg_match( '/^[A-Z]{2}$/', $c ) ? $c : '';
}
function ss_kd_country( $cc ) {
	if ( ! $cc || ! function_exists( 'WC' ) || ! WC()->countries ) return '';
	$l = WC()->countries->get_countries();
	$n = isset( $l[ $cc ] ) ? html_entity_decode( $l[ $cc ] ) : '';
	return preg_replace( '/\s*\(.*\)$/', '', $n );
}
function ss_kd_limit( $key, $max ) {
	$k = 'ss_kd_rl_' . md5( $key ); $n = (int) get_transient( $k );
	if ( $n >= $max ) return false;
	set_transient( $k, $n + 1, HOUR_IN_SECONDS ); return true;
}
function ss_kd_clean_msg( $s ) {
	$s = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $s ) ) );
	if ( function_exists( 'mb_substr' ) ) $s = mb_substr( $s, 0, 140 ); else $s = substr( $s, 0, 140 );
	return function_exists( 'ss_fr_mask' ) ? ss_fr_mask( $s ) : $s;
}
function ss_kd_author_name( $uid ) {
	$n = '';
	if ( function_exists( 'wcfm_get_vendor_store_name' ) ) $n = wp_strip_all_tags( (string) wcfm_get_vendor_store_name( $uid ) );
	if ( ! $n ) { $u = get_userdata( $uid ); $n = $u ? ( $u->first_name ?: $u->display_name ) : ''; }
	return $n ?: 'the author';
}
$GLOBALS['ss_kd_types'] = array( 'thanks' => array( '🙏', 'Thanks' ), 'fire' => array( '🔥', 'Fire' ), 'inspired' => array( '💡', 'Inspired me' ) );
$GLOBALS['ss_kd_rx']    = array( 'wave' => '👋', 'fire' => '🔥', 'clap' => '🙌', 'lol' => '😂' );

/* ---------- 2. Thank the author (product page) ---------- */
add_action( 'wp_ajax_ss_kd_give', 'ss_kd_give' );
add_action( 'wp_ajax_nopriv_ss_kd_give', 'ss_kd_give' );
function ss_kd_give() {
	global $wpdb;
	if ( ! ss_kd_on() ) wp_send_json_error( 'off' );
	$pid = (int) ( $_POST['p'] ?? 0 ); $t = sanitize_key( $_POST['t'] ?? '' );
	$p = get_post( $pid );
	if ( ! $p || 'product' !== $p->post_type || 'publish' !== $p->post_status || ! isset( $GLOBALS['ss_kd_types'][ $t ] ) ) wp_send_json_error( 'bad' );
	$who = ss_kd_who();
	if ( ! ss_kd_limit( 'give' . $who, 30 ) ) wp_send_json_error( 'Too many — thank you for the love! Try again in an hour.' );
	$msg = ss_kd_clean_msg( wp_unslash( $_POST['m'] ?? '' ) );
	$row = array( 'kind' => 'pk', 'obj' => $pid, 'owner' => (int) $p->post_author, 'who' => $who, 'type' => $t, 'cc' => ss_kd_cc(), 'created' => current_time( 'mysql' ) );
	$ex = $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM ' . ss_kd_table() . " WHERE kind='pk' AND obj=%d AND who=%s AND type=%s", $pid, $who, $t ) );
	if ( $ex ) { if ( '' !== $msg ) $wpdb->update( ss_kd_table(), array( 'msg' => $msg ), array( 'id' => $ex ) ); }
	else { $row['msg'] = $msg; $wpdb->insert( ss_kd_table(), $row ); delete_transient( 'ss_kd_list_' . (int) $p->post_author ); }
	wp_send_json_success( array( 'name' => ss_kd_author_name( (int) $p->post_author ) ) );
}

add_action( 'wp_footer', function () {
	if ( ! ss_kd_on() || ! function_exists( 'is_product' ) || ! is_product() ) return;
	$p = get_post( get_queried_object_id() ); if ( ! $p || 'publish' !== $p->post_status ) return;
	$n = ss_kd_author_name( (int) $p->post_author );
	$b = ''; foreach ( $GLOBALS['ss_kd_types'] as $k => $v ) $b .= '<button type="button" class="ss-kd-b' . ( 'thanks' === $k ? ' o' : '' ) . '" data-t="' . esc_attr( $k ) . '"><span aria-hidden="true">' . $v[0] . '</span> ' . esc_html( $v[1] ) . '</button>';
	?>
<template id="ss-kd-tpl"><div class="ss-kd" data-p="<?php echo (int) $p->ID; ?>"><p class="h"><b>Enjoyed it? Say thanks to <?php echo esc_html( $n ); ?></b> — it means more than you think 🙏</p>
<div class="r"><?php echo $b; /* escaped above */ ?></div>
<div class="m" hidden><input type="text" maxlength="140" placeholder="Add a few words for <?php echo esc_attr( $n ); ?> (optional)" aria-label="Message to the author"><button type="button" class="ss-kd-s">Send</button></div>
<p class="ok" hidden role="status"></p></div></template>
<style>.ss-kd{margin:18px 0;padding:16px;border-radius:12px;background:linear-gradient(135deg,rgba(245,158,11,.12),rgba(245,158,11,.03));border:1px solid rgba(245,158,11,.28);color:#d4dbea;font-size:15px;line-height:1.5}
.ss-kd .h{margin:0 0 10px}.ss-kd .h b{color:#fff}.ss-kd .r{display:flex;flex-wrap:wrap;gap:8px}
.ss-kd .ss-kd-b{display:inline-flex;align-items:center;gap:7px;height:42px;padding:0 16px;border-radius:6px;border:1px solid rgba(255,255,255,.14);background:#0f1729;color:#fff;font:700 14.5px/1 inherit;cursor:pointer}
.ss-kd .ss-kd-b.o{background:#f59e0b;border-color:#f59e0b;color:#0b1220}.ss-kd .ss-kd-b.on{border-color:#f59e0b;box-shadow:0 0 0 2px rgba(245,158,11,.35)}
.ss-kd .m{display:flex;gap:8px;margin-top:10px}.ss-kd .m input{flex:1;min-width:0;height:42px;border-radius:6px;border:1px solid rgba(255,255,255,.14);background:#0b1220;color:#fff;padding:0 12px;font-size:15px}
.ss-kd .ss-kd-s{height:42px;padding:0 16px;border-radius:6px;border:0;background:#f59e0b;color:#0b1220;font-weight:800;cursor:pointer}
.ss-kd .ok{margin:10px 0 0;color:#6ee7a8;font-weight:700}</style>
<script>(function(){var A=<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>;
function mount(){var t=document.getElementById('ss-kd-tpl');if(!t||document.querySelector('.ss-kd'))return;var at=document.querySelector('.ss-desc3')||document.querySelector('form.cart');if(!at)return;var n=t.content.firstElementChild.cloneNode(true);at.parentNode.insertBefore(n,at.nextSibling);wire(n)}
function send(box,t,m){var f=new FormData();f.append('action','ss_kd_give');f.append('p',box.dataset.p);f.append('t',t);if(m)f.append('m',m);return fetch(A,{method:'POST',body:f,credentials:'same-origin'}).then(function(r){return r.json()})}
function wire(box){var ok=box.querySelector('.ok'),mb=box.querySelector('.m'),last='';
 box.addEventListener('click',function(e){var b=e.target.closest('.ss-kd-b');if(b){last=b.dataset.t;if(!b.classList.contains('on'))b.classList.add('on');send(box,last).then(function(j){ok.hidden=false;ok.textContent=j&&j.success?'Sent! '+j.data.name+' will see it — thank you 🧡':((j&&j.data)||'Could not send, try again');if(j&&j.success)mb.hidden=false});return}
  var s=e.target.closest('.ss-kd-s');if(s){var i=mb.querySelector('input');if(!i.value.trim())return;send(box,last||'thanks',i.value).then(function(j){if(j&&j.success){mb.hidden=true;ok.textContent='Message sent to '+j.data.name+' 🙌'}})}});}
if(document.readyState!=='loading')mount();else document.addEventListener('DOMContentLoaded',mount);})();</script>
	<?php
}, 30 );

/* ---------- creator space: thanks + wins ---------- */
function ss_kd_recent( $uid, $n = 5 ) {
	global $wpdb; $k = 'ss_kd_list_' . $uid; $r = get_transient( $k );
	if ( ! is_array( $r ) ) {
		$r = (array) $wpdb->get_results( $wpdb->prepare( 'SELECT obj,type,msg,cc,created FROM ' . ss_kd_table() . " WHERE kind='pk' AND owner=%d ORDER BY id DESC LIMIT 20", $uid ), ARRAY_A );
		set_transient( $k, $r, 10 * MINUTE_IN_SECONDS );
	}
	return array_slice( $r, 0, $n );
}
function ss_kd_count( $uid, $days = 0 ) {
	global $wpdb;
	$w = $days ? $wpdb->prepare( ' AND created >= %s', gmdate( 'Y-m-d H:i:s', current_time( 'timestamp' ) - $days * DAY_IN_SECONDS ) ) : '';
	return (int) $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . ss_kd_table() . " WHERE kind='pk' AND owner=%d", $uid ) . $w );
}
function ss_kd_line( $r ) {
	$t = get_the_title( (int) $r['obj'] ); $c = ss_kd_country( $r['cc'] );
	$who = $c ? 'Someone from ' . $c : 'Someone';
	if ( '' !== (string) $r['msg'] ) return array( '🙏', '“' . $r['msg'] . '”', ( $c ? 'Listener from ' . $c : 'A listener' ) . ' · ' . $t );
	if ( 'fire' === $r['type'] ) return array( '🔥', $who . ' thinks ' . $t . ' is fire!', '' );
	if ( 'inspired' === $r['type'] ) return array( '💡', $t . ' inspired ' . lcfirst( $who ), '' );
	return array( '🙏', $who . ' says thank you for ' . $t . '!', '' );
}
function ss_kd_wins( $uid, $d ) {
	global $wpdb;
	$rows = isset( $d['rows'] ) ? (array) $d['rows'] : array();
	$plays = array_sum( wp_list_pluck( $rows, 'plays' ) ); $sales = array_sum( wp_list_pluck( $rows, 'q' ) );
	$ids = wp_list_pluck( $rows, 'id' ); $rev = 0;
	if ( $ids ) $rev = (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->comments . " WHERE comment_approved='1' AND comment_type='review' AND comment_post_ID IN (" . implode( ',', array_map( 'intval', $ids ) ) . ')' );
	$k = ss_kd_count( $uid );
	return array(
		'product' => array( count( $rows ) > 0, 'First product', '🎛', 'Your first product is on Solid Sound!', 'You did the hardest part — you shared your work with the world. Proud of you!' ),
		'plays10' => array( $plays >= 10, '10 plays', '🎧', 'Your first 10 plays!', '10 times someone pressed play on your sound. That\'s real people listening. Keep going!' ),
		'thanks'  => array( $k > 0, 'First thanks', '🙏', 'Your first thank you!', 'A listener took a moment to thank you. Your music matters to someone.' ),
		'sale'    => array( $sales > 0, 'First sale', '💸', 'Your first sale!', 'Someone paid for your work. This is a big day — celebrate it!' ),
		'review'  => array( $rev > 0, 'First review', '⭐', 'Your first review!', 'A buyer shared what they think. Every review helps the next person decide.' ),
		'plays100'=> array( $plays >= 100, '100 plays', '🚀', '100 plays!', 'Your sounds were played 100 times. You are building an audience.' ),
	);
}
add_filter( 'ss_cs_after_stats', function ( $h, $uid, $d ) {
	if ( ! ss_kd_on() ) return $h;
	try {
		$w = ss_kd_wins( $uid, $d ); $seen = (array) get_user_meta( $uid, 'ss_kd_seen', true ); $new = '';
		foreach ( $w as $k => $x ) if ( $x[0] && ! in_array( $k, $seen, true ) ) $new = $k; /* the latest one reached */
		$lad = ''; $next = true;
		foreach ( $w as $k => $x ) { $cls = $x[0] ? 'ok' : ( $next ? 'now' : '' ); if ( ! $x[0] ) $next = false; $lad .= '<span class="' . $cls . '">' . ( $x[0] ? '✓ ' : '' ) . esc_html( $x[1] ) . '</span>'; }
		if ( $new ) {
			$x = $w[ $new ]; $cf = '';
			foreach ( array( array( 8, 14, '#f59e0b', 20 ), array( 18, 62, '#6ee7a8', -30 ), array( 84, 16, '#c4b5fd', 40 ), array( 91, 66, '#f59e0b', -12 ), array( 30, 8, '#fbbf24', 60 ), array( 88, 40, '#6ee7a8', 15 ) ) as $c )
				$cf .= '<i class="cf" style="left:' . $c[0] . '%;top:' . $c[1] . '%;background:' . $c[2] . ';transform:rotate(' . $c[3] . 'deg)"></i>';
			$h .= '<section class="p ss-kd-win" data-k="' . esc_attr( implode( ',', array_keys( array_filter( $w, function ( $x ) { return $x[0]; } ) ) ) ) . '">' . $cf . '<button type="button" class="x" aria-label="Close">×</button><div class="big" aria-hidden="true">' . $x[2] . '</div><h3>' . esc_html( $x[3] ) . '</h3><p>' . esc_html( $x[4] ) . '</p><button type="button" class="btn sh">Share the win</button><div class="lad">' . $lad . '</div></section>';
		} else {
			$h .= '<div class="ss-kd-lad"><span class="l">Your wins</span>' . $lad . '</div>';
		}
	} catch ( \Throwable $e ) { error_log( 'ss kudos wins: ' . $e->getMessage() ); }
	return $h;
}, 10, 3 );
add_filter( 'ss_cs_side_top', function ( $h, $uid ) {
	if ( ! ss_kd_on() ) return $h;
	$l = ss_kd_recent( $uid );
	$h .= '<section class="p ss-kd-list"><h2><span class="t" aria-hidden="true">🧡</span>Love from listeners</h2>';
	if ( ! $l ) $h .= '<div class="empty">When listeners tap 🙏 Thanks, 🔥 Fire or 💡 Inspired me on your products, it shows up here.</div>';
	foreach ( $l as $r ) { $x = ss_kd_line( $r ); $h .= '<div class="kn"><span class="i" aria-hidden="true">' . $x[0] . '</span><div><b>' . esc_html( $x[1] ) . '</b><small>' . esc_html( ( $x[2] ? $x[2] . ' · ' : '' ) . human_time_diff( strtotime( $r['created'] ), current_time( 'timestamp' ) ) . ' ago' ) . '</small></div></div>'; }
	return $h . '</section>';
}, 10, 2 );
add_action( 'wp_ajax_ss_kd_seen', function () {
	$u = get_current_user_id(); if ( ! $u ) wp_send_json_error();
	$k = array_filter( array_map( 'sanitize_key', explode( ',', (string) ( $_POST['k'] ?? '' ) ) ) );
	update_user_meta( $u, 'ss_kd_seen', array_values( array_unique( array_merge( (array) get_user_meta( $u, 'ss_kd_seen', true ), $k ) ) ) );
	wp_send_json_success();
} );
add_action( 'wp_footer', function () {
	if ( ! ss_kd_on() || ! function_exists( 'ss_cs_ok' ) || ! ss_cs_ok() ) return; ?>
<style>.ss-kd-win{position:relative;overflow:hidden;text-align:center;padding:26px 16px!important;background:radial-gradient(80% 90% at 50% 0%,rgba(245,158,11,.22),transparent 70%),rgba(255,255,255,.03)!important;border:1px solid rgba(245,158,11,.35)!important;margin-bottom:16px}
.ss-kd-win .big{font-size:44px;line-height:1.1}.ss-kd-win h3{margin:6px 0 4px;font-size:22px;color:#fff}.ss-kd-win p{margin:0 auto 14px;max-width:440px;color:#cdd6e8}
.ss-kd-win .cf{position:absolute;width:8px;height:14px;border-radius:2px;opacity:.9}.ss-kd-win .x{position:absolute;right:10px;top:8px;width:34px;height:34px;border:0;background:transparent;color:#94a3b8;font-size:22px;cursor:pointer}
.ss-kd-win .lad,.ss-kd-lad{display:flex;gap:8px;justify-content:center;flex-wrap:wrap;margin-top:14px}.ss-kd-lad{justify-content:flex-start;align-items:center;margin:0 0 16px}.ss-kd-lad .l{color:#94a3b8;font-weight:700;margin-right:4px}
.ss-kd-win .lad span,.ss-kd-lad span:not(.l){font:700 12.5px/1 inherit;padding:6px 10px;border-radius:6px;background:#0f1729;color:#8ea0c2;border:1px solid rgba(255,255,255,.08)}
.ss-kd-win .lad span.ok,.ss-kd-lad span.ok{color:#6ee7a8;border-color:#245c40}.ss-kd-win .lad span.now,.ss-kd-lad span.now{color:#f59e0b;border-color:#f59e0b}
.ss-kd-list .kn{display:flex;gap:12px;align-items:flex-start;padding:10px 12px;border-radius:10px;background:#0f1729;border:1px solid rgba(255,255,255,.07);margin-top:8px}.ss-kd-list .kn .i{font-size:20px;line-height:1.2}.ss-kd-list .kn b{color:#fff;display:block}.ss-kd-list .kn small{color:#8ea0c2}
.ss-kd-list .empty{color:#94a3b8}</style>
<script>(function(){var A=<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>,S=<?php echo wp_json_encode( function_exists( 'wcfmmp_get_store_url' ) ? wcfmmp_get_store_url( get_current_user_id() ) : home_url( '/' ) ); ?>;
function seen(w){var f=new FormData();f.append('action','ss_kd_seen');f.append('k',w.dataset.k||'');fetch(A,{method:'POST',body:f,credentials:'same-origin'})}
document.addEventListener('click',function(e){var w=e.target.closest&&e.target.closest('.ss-kd-win');if(!w)return;
 if(e.target.closest('.x')){seen(w);w.remove();return}
 if(e.target.closest('.sh')){var t=w.querySelector('h3').textContent+' 🎉 My sounds on Solid Sound: '+S;seen(w);if(navigator.share){navigator.share({text:t,url:S}).catch(function(){})}else if(navigator.clipboard){navigator.clipboard.writeText(t);e.target.textContent='Copied — paste it anywhere ✓'}}});})();</script>
<?php }, 40 );

/* ---------- 1. forum: Say hi section, reactions, badges, host welcome ---------- */
add_action( 'wp_loaded', function () {
	if ( ! ss_kd_on() || ! function_exists( 'ss_fr_ok' ) || ! ss_fr_ok() || ! get_option( 'ss_forum_setup_v2' ) || get_option( 'ss_kd_sayhi' ) || get_transient( 'ss_kd_sayhi_lock' ) ) return;
	set_transient( 'ss_kd_sayhi_lock', 1, 120 );
	try {
		$t = WPF()->tables->forums;
		$id = (int) WPF()->db->get_var( "SELECT `forumid` FROM {$t} WHERE `title` = 'Say hi' LIMIT 1" );
		if ( ! $id ) {
			$cat = (int) WPF()->db->get_var( "SELECT `forumid` FROM {$t} WHERE `is_cat` = 1 ORDER BY `forumid` LIMIT 1" );
			if ( ! $cat ) return;
			WPF()->forum->add( array( 'title' => 'Say hi', 'description' => 'New here? Introduce yourself — who you are, what you make, your favourite plugin. Everyone gets a warm welcome 👋', 'parentid' => $cat, 'icon' => 'fas fa-hand-paper', 'order' => 0, 'is_cat' => 0 ), false );
			$id = (int) WPF()->db->get_var( "SELECT `forumid` FROM {$t} WHERE `title` = 'Say hi' LIMIT 1" );
		}
		if ( $id ) {
			WPF()->db->query( "UPDATE {$t} SET `order` = `order` + 1 WHERE `is_cat` = 0 AND `forumid` <> {$id}" );
			WPF()->db->update( $t, array( 'order' => 0 ), array( 'forumid' => $id ) );
			if ( function_exists( 'wpforo_clean_cache' ) ) wpforo_clean_cache();
			update_option( 'ss_kd_sayhi', $id, false );
		}
	} catch ( \Throwable $e ) { error_log( 'ss say hi: ' . $e->getMessage() ); }
}, 33 );

/* run wpForo code as another member (cron has no user) */
function ss_kd_as( $uid, $fn ) {
	$old = get_current_user_id();
	wp_set_current_user( $uid ); if ( isset( WPF()->member ) && method_exists( WPF()->member, 'init_current_user' ) ) WPF()->member->init_current_user();
	$ok = function ( $a ) { if ( is_array( $a ) ) $a['status'] = 0; return $a; };
	add_filter( 'wpforo_add_topic_data_filter', $ok, 99 ); add_filter( 'wpforo_add_post_data_filter', $ok, 99 );
	try { $r = $fn(); } catch ( \Throwable $e ) { error_log( 'ss kudos forum: ' . $e->getMessage() ); $r = false; }
	remove_filter( 'wpforo_add_topic_data_filter', $ok, 99 ); remove_filter( 'wpforo_add_post_data_filter', $ok, 99 );
	wp_set_current_user( $old ); if ( isset( WPF()->member ) && method_exists( WPF()->member, 'init_current_user' ) ) WPF()->member->init_current_user();
	return $r;
}

/* a new creator (sign-up) or a new buyer (first paid order) gets an intro topic; the host answers */
add_action( 'user_register', function ( $uid ) { if ( ss_kd_on() ) wp_schedule_single_event( time() + 90, 'ss_kd_intro', array( (int) $uid ) ); }, 50 );
add_action( 'woocommerce_order_status_completed', function ( $oid ) {
	if ( ! ss_kd_on() || ! function_exists( 'wc_get_order' ) ) return;
	$o = wc_get_order( $oid ); $u = $o ? (int) $o->get_customer_id() : 0;
	if ( $u && ! get_user_meta( $u, 'ss_kd_intro', true ) ) wp_schedule_single_event( time() + 90, 'ss_kd_intro', array( $u ) );
} );
add_action( 'ss_kd_intro', function ( $uid ) {
	$sh = (int) get_option( 'ss_kd_sayhi' ); $u = get_userdata( $uid );
	if ( ! $sh || ! $u || get_user_meta( $uid, 'ss_kd_intro', true ) || ! function_exists( 'WPF' ) ) return;
	$creator = in_array( 'wcfm_vendor', (array) $u->roles, true );
	$buyer = function_exists( 'wc_get_customer_order_count' ) && wc_get_customer_order_count( $uid ) > 0;
	if ( ! $creator && ! $buyer ) return; /* plain sign-ups (often bots) don't get a topic */
	update_user_meta( $uid, 'ss_kd_intro', time() );
	$name = $u->first_name ?: $u->display_name; $c = ss_kd_country( strtoupper( (string) get_user_meta( $uid, 'billing_country', true ) ) );
	$body = '<p>Hi everyone! I\'m ' . esc_html( $name ) . ( $c ? ' from ' . esc_html( $c ) : '' ) . '. Just joined Solid Sound as ' . ( $creator ? 'a creator' : 'a music maker' ) . ' 🎧 Happy to be here!</p>'
		. '<p><em>(This intro was posted automatically when you joined — feel free to edit it or tell us more.)</em></p>';
	ss_kd_as( $uid, function () use ( $sh, $name, $body ) { return WPF()->topic->add( array( 'forumid' => $sh, 'title' => 'Hi, I\'m ' . $name . ' 👋', 'body' => $body, 'is_ai_generated' => 1 ) ); } );
} );
add_action( 'wpforo_after_add_topic', function ( $topic ) {
	if ( ! ss_kd_on() || ! is_array( $topic ) || (int) ( $topic['forumid'] ?? 0 ) !== (int) get_option( 'ss_kd_sayhi' ) || SS_KD_HOST === (int) ( $topic['userid'] ?? 0 ) ) return;
	wp_schedule_single_event( time() + 75, 'ss_kd_host', array( (int) $topic['topicid'] ) );
}, 20 );
add_action( 'ss_kd_host', function ( $tid ) {
	if ( ! function_exists( 'WPF' ) ) return;
	$t = WPF()->topic->get_topic( $tid, false ); if ( ! $t || get_option( 'ss_kd_hosted_' . $tid ) ) return;
	update_option( 'ss_kd_hosted_' . $tid, 1, false );
	$first = WPF()->post->get_post( (int) $t['first_postid'], false ); $u = get_userdata( (int) $t['userid'] );
	$name = $u ? ( $u->first_name ?: $u->display_name ) : 'friend';
	$intro = $first ? wp_strip_all_tags( (string) $first['body'] ) : '';
	$txt = '';
	if ( function_exists( 'ss_cf_ai' ) ) {
		$r = ss_cf_ai( '@cf/meta/llama-3.1-8b-instruct', array( 'max_tokens' => 160, 'messages' => array(
			array( 'role' => 'system', 'content' => 'You are the warm, funny community host of Solid Sound, a marketplace and forum for music producers (Ableton templates, presets, mixing). Write a short welcome reply (2-3 sentences, max 60 words) in English to a new member. Use their name, react to something specific they said, add one light producer joke or emoji, and invite them to post a track in "Rate my track" or ask in "Ableton help". No links, no e-mails, no prices, no promises. Plain text only.' ),
			array( 'role' => 'user', 'content' => 'Name: ' . $name . "\nTheir intro: " . mb_substr( $intro, 0, 600 ) ),
		) ), 30 );
		if ( ! is_wp_error( $r ) && ! empty( $r['response'] ) ) $txt = trim( wp_strip_all_tags( (string) $r['response'] ), " \"\n" );
	}
	if ( ! $txt || strlen( $txt ) > 600 ) $txt = 'Welcome, ' . $name . '! 🎉 Great to have you here. Drop a track in Rate my track — people give one compliment + one tip — or ask anything in Ableton help. And tell us: which plugin can\'t you live without? 😄';
	ss_kd_as( SS_KD_HOST, function () use ( $t, $txt ) { return WPF()->post->add( array( 'topicid' => (int) $t['topicid'], 'forumid' => (int) $t['forumid'], 'body' => '<p>' . esc_html( $txt ) . '</p>', 'is_ai_generated' => 1 ) ); } );
} );

/* reactions under every post + who is new / host (badges) */
$GLOBALS['ss_kd_users'] = array();
add_filter( 'wpforo_content_after', function ( $content, $post = array() ) {
	if ( ! ss_kd_on() || ! is_array( $post ) || empty( $post['postid'] ) ) return $content;
	global $wpdb; $pid = (int) $post['postid']; $uid = (int) ( $post['userid'] ?? 0 );
	if ( $uid ) $GLOBALS['ss_kd_users'][ $uid ] = 1;
	$c = array(); $mine = array(); $who = is_user_logged_in() ? ss_kd_who() : '';
	foreach ( (array) $wpdb->get_results( $wpdb->prepare( 'SELECT type,who FROM ' . ss_kd_table() . " WHERE kind='rx' AND obj=%d", $pid ), ARRAY_A ) as $r ) { $c[ $r['type'] ] = ( $c[ $r['type'] ] ?? 0 ) + 1; if ( $who && $r['who'] === $who ) $mine[ $r['type'] ] = 1; }
	$b = '';
	foreach ( $GLOBALS['ss_kd_rx'] as $k => $e ) $b .= '<button type="button" class="ss-rx-b' . ( isset( $mine[ $k ] ) ? ' on' : '' ) . '" data-t="' . $k . '" aria-pressed="' . ( isset( $mine[ $k ] ) ? 'true' : 'false' ) . '"><span aria-hidden="true">' . $e . '</span><i>' . ( $c[ $k ] ?? '' ) . '</i></button>';
	return $content . '<div class="ss-rx" data-pid="' . $pid . '">' . $b . '</div>';
}, 25, 2 );
add_action( 'wp_ajax_ss_kd_rx', function () {
	global $wpdb;
	$pid = (int) ( $_POST['p'] ?? 0 ); $t = sanitize_key( $_POST['t'] ?? '' );
	if ( ! $pid || ! isset( $GLOBALS['ss_kd_rx'][ $t ] ) || ! function_exists( 'WPF' ) ) wp_send_json_error();
	$post = WPF()->post->get_post( $pid, false ); if ( ! $post ) wp_send_json_error();
	$who = ss_kd_who(); if ( ! ss_kd_limit( 'rx' . $who, 120 ) ) wp_send_json_error();
	$ex = $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM ' . ss_kd_table() . " WHERE kind='rx' AND obj=%d AND who=%s AND type=%s", $pid, $who, $t ) );
	if ( $ex ) $wpdb->delete( ss_kd_table(), array( 'id' => $ex ) );
	else $wpdb->insert( ss_kd_table(), array( 'kind' => 'rx', 'obj' => $pid, 'owner' => (int) $post['userid'], 'who' => $who, 'type' => $t, 'cc' => '', 'created' => current_time( 'mysql' ) ) );
	wp_send_json_success( array( 'on' => ! $ex, 'n' => (int) $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . ss_kd_table() . " WHERE kind='rx' AND obj=%d AND type=%s", $pid, $t ) ) ) );
} );
add_action( 'wp_ajax_nopriv_ss_kd_rx', function () { wp_send_json_error( 'login' ); } );
add_action( 'wp_footer', function () {
	if ( ! ss_kd_on() || ! function_exists( 'ss_fr_is_page' ) || ! ss_fr_is_page() ) return;
	$new = array();
	foreach ( array_keys( $GLOBALS['ss_kd_users'] ) as $u ) { $d = get_userdata( $u ); if ( $d && strtotime( $d->user_registered ) > time() - 14 * DAY_IN_SECONDS && SS_KD_HOST !== $u ) $new[] = $u; }
	?>
<style>#wpforo .ss-rx{display:flex;gap:6px;flex-wrap:wrap;margin:12px 0 2px}#wpforo .ss-rx-b{display:inline-flex;align-items:center;gap:5px;height:32px;padding:0 10px;border-radius:6px;border:1px solid rgba(255,255,255,.1);background:#0f1729;color:#cdd6e8;font:600 13px/1 inherit;cursor:pointer}
#wpforo .ss-rx-b i{font-style:normal;min-width:0}#wpforo .ss-rx-b.on{border-color:#f59e0b;color:#f59e0b}#wpforo .ss-rx-b:hover{border-color:rgba(245,158,11,.6)}
.ss-bdg{display:inline-block;font:700 11px/1.4 Inter,sans-serif;padding:2px 8px;border-radius:6px;margin-left:6px;vertical-align:1px;white-space:nowrap}.ss-bdg.n{background:#1f3b2c;color:#6ee7a8}.ss-bdg.h{background:#2a2350;color:#c4b5fd}</style>
<script>(function(){var A=<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>,N=<?php echo wp_json_encode( $new ); ?>,H=<?php echo (int) SS_KD_HOST; ?>,L=<?php echo wp_json_encode( wp_login_url( isset( $_SERVER['REQUEST_URI'] ) ? home_url( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : home_url( '/' ) ) ); ?>,IN=<?php echo is_user_logged_in() ? 'true' : 'false'; ?>;
function badges(){document.querySelectorAll('#wpforo .post-wrap[data-userid]').forEach(function(p){var u=+p.getAttribute('data-userid'),n=p.querySelector('.author-name');if(!n||n.querySelector('.ss-bdg'))return;var b=null;if(u===H){b=document.createElement('span');b.className='ss-bdg h';b.textContent='Community host'}else if(N.indexOf(u)>-1){b=document.createElement('span');b.className='ss-bdg n';b.textContent='New face'}if(b)n.appendChild(b)})}
document.addEventListener('click',function(e){var b=e.target.closest&&e.target.closest('#wpforo .ss-rx-b');if(!b)return;if(!IN){location.href=L;return}var box=b.closest('.ss-rx'),f=new FormData();f.append('action','ss_kd_rx');f.append('p',box.dataset.pid);f.append('t',b.dataset.t);
 fetch(A,{method:'POST',body:f,credentials:'same-origin'}).then(function(r){return r.json()}).then(function(j){if(!j||!j.success)return;b.classList.toggle('on',j.data.on);b.setAttribute('aria-pressed',j.data.on?'true':'false');b.querySelector('i').textContent=j.data.n||''})});
var q=0;function later(){if(q)return;q=requestAnimationFrame(function(){q=0;badges()})}
function go(){badges();var r=document.getElementById('wpforo');if(r&&window.MutationObserver)new MutationObserver(later).observe(r,{childList:true,subtree:true})}
if(document.readyState!=='loading')go();else document.addEventListener('DOMContentLoaded',go);})();</script>
<?php }, 40 );

/* ---------- 3. Monday e-mail for creators ---------- */
add_action( 'init', function () {
	if ( ! ss_kd_on() || wp_next_scheduled( 'ss_kd_weekly' ) ) return;
	$t = strtotime( 'next monday 09:00', current_time( 'timestamp' ) ) - (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
	wp_schedule_event( $t, 'weekly', 'ss_kd_weekly' );
} );
function ss_kd_off_url( $uid ) { return add_query_arg( array( 'ss_kd_off' => $uid, 'k' => substr( hash_hmac( 'sha256', 'off' . $uid, wp_salt( 'auth' ) ), 0, 20 ) ), home_url( '/' ) ); }
add_action( 'template_redirect', function () {
	if ( empty( $_GET['ss_kd_off'] ) ) return;
	$u = (int) $_GET['ss_kd_off'];
	if ( hash_equals( substr( hash_hmac( 'sha256', 'off' . $u, wp_salt( 'auth' ) ), 0, 20 ), (string) ( $_GET['k'] ?? '' ) ) ) {
		update_user_meta( $u, 'ss_kd_mail_off', 1 );
		wp_die( 'Done — you will not get the weekly summary any more. You can still see everything in your creator space.', 'Solid Sound', array( 'response' => 200 ) );
	}
} );
add_action( 'ss_kd_weekly', function () {
	if ( ! ss_kd_on() ) return;
	global $wpdb;
	$vendors = get_users( array( 'role__in' => array( 'wcfm_vendor' ), 'fields' => array( 'ID', 'user_email', 'display_name' ), 'number' => 300 ) );
	$days = array(); for ( $i = 1; $i <= 7; $i++ ) $days[] = gmdate( 'Y-m-d', current_time( 'timestamp' ) - $i * DAY_IN_SECONDS );
	foreach ( $vendors as $v ) {
		if ( get_user_meta( $v->ID, 'ss_kd_mail_off', true ) ) continue;
		$ids = get_posts( array( 'post_type' => 'product', 'author' => $v->ID, 'post_status' => 'publish', 'posts_per_page' => 60, 'fields' => 'ids' ) );
		if ( ! $ids ) continue;
		$per = array(); $tip = '';
		foreach ( $ids as $pid ) {
			$d = get_post_meta( $pid, '_ss_plays_d', true ); $n = 0;
			if ( is_array( $d ) ) foreach ( $days as $k ) $n += (int) ( $d[ $k ] ?? 0 );
			if ( $n ) $per[ get_the_title( $pid ) ] = $n;
			if ( ! $tip && strlen( wp_strip_all_tags( (string) get_post_field( 'post_excerpt', $pid ) ) ) < 60 ) $tip = get_the_title( $pid ) . ' has almost no short description — add 2–3 lines about what is inside, buyers read it before they press play.';
		}
		arsort( $per ); $plays = array_sum( $per );
		$rows = (array) $wpdb->get_results( $wpdb->prepare( 'SELECT type,cc FROM ' . ss_kd_table() . " WHERE kind='pk' AND owner=%d AND created >= %s", $v->ID, gmdate( 'Y-m-d H:i:s', current_time( 'timestamp' ) - 7 * DAY_IN_SECONDS ) ), ARRAY_A );
		if ( ! $plays && ! $rows ) continue; /* only when there is something real to tell */
		$th = count( array_filter( $rows, function ( $r ) { return 'fire' !== $r['type']; } ) ); $fi = count( $rows ) - $th;
		$cs = array_unique( array_filter( array_map( function ( $r ) { return ss_kd_country( $r['cc'] ); }, $rows ) ) );
		$li = array();
		if ( $plays ) $li[] = array( '📈', 'Your sounds were played ' . $plays . ' time' . ( 1 === $plays ? '' : 's' ) . ' this week', implode( ' · ', array_map( function ( $k, $n ) { return $k . ' ' . $n; }, array_slice( array_keys( $per ), 0, 3 ), array_slice( array_values( $per ), 0, 3 ) ) ) );
		if ( $rows ) $li[] = array( '🙌', trim( ( $th ? $th . ' thank' . ( 1 === $th ? '' : 's' ) : '' ) . ( $th && $fi ? ' and ' : '' ) . ( $fi ? $fi . ' “fire”' : '' ) ), $cs ? 'From ' . implode( ', ', array_slice( $cs, 0, 4 ) ) : '' );
		if ( $tip ) $li[] = array( '💡', 'Tip of the week', $tip );
		$h = '<div style="background:#0b1220;padding:24px 12px;font-family:Arial,sans-serif"><div style="max-width:520px;margin:0 auto;background:#131b2e;border-radius:12px;padding:22px;color:#d4dbea">'
			. '<p style="margin:0 0 4px;color:#f59e0b;font-weight:800;letter-spacing:.1em;font-size:12px">SOLID SOUND · YOUR WEEK</p><h1 style="margin:0 0 16px;color:#fff;font-size:22px">Hi ' . esc_html( $v->display_name ) . ', people are listening 🎧</h1>';
		foreach ( $li as $x ) $h .= '<div style="padding:12px;border-radius:10px;background:#0f1729;margin:0 0 8px"><b style="color:#fff">' . $x[0] . ' ' . esc_html( $x[1] ) . '</b>' . ( $x[2] ? '<br><span style="color:#8ea0c2;font-size:14px">' . esc_html( $x[2] ) . '</span>' : '' ) . '</div>';
		$h .= '<p style="margin:16px 0"><a href="' . esc_url( function_exists( 'get_wcfm_url' ) ? get_wcfm_url() : home_url( '/' ) ) . '" style="display:inline-block;background:#f59e0b;color:#0b1220;font-weight:800;text-decoration:none;padding:12px 18px;border-radius:6px">Open my creator space</a></p>'
			. '<p style="margin:0;color:#8ea0c2;font-size:12px">Only real numbers. <a href="' . esc_url( ss_kd_off_url( $v->ID ) ) . '" style="color:#8ea0c2">Turn off this weekly e-mail</a></p></div></div>';
		wp_mail( $v->user_email, 'Your week on Solid Sound: ' . ( $plays ? $plays . ' plays' : count( $rows ) . ' thanks' ) . ' 🎧', $h, array( 'Content-Type: text/html; charset=UTF-8' ) );
	}
} );
