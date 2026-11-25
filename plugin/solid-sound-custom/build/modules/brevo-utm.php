<?php
/**
 * (25.11.2026 owner via «Задачи», Black Month campaign of the «Маркетолог») FREE sign-ups → Brevo, with the campaign:
 * 1) UTM: read from the address of the page the form was sent from (?utm_source=reddit&utm_campaign=…) — field «page»
 *    or the Referer. No cookie, nothing kept in the browser. First campaign wins: user meta ss_utm + Brevo attributes
 *    UTM_SOURCE / UTM_MEDIUM / UTM_CAMPAIGN (created once in Brevo if missing). Works for /free/, «Try it free» and feedback.
 * 2) /free-demo-feedback/: optional box «Send me free packs… (max 2 emails / month)» (same text as /free/) → Brevo list
 *    "Free Pack" with UTM. No tick → nothing goes to Brevo (as before).
 * 3) One-off resync: FREE subscribers on the site that never reached Brevo are sent once; result in option ss_brevo_resync.
 * Off: option ss_brevo_utm_off = 1.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_utm_from_req() {
	$u = isset( $_POST['page'] ) ? (string) wp_unslash( $_POST['page'] ) : '';
	if ( false === strpos( $u, 'utm_' ) && ! empty( $_SERVER['HTTP_REFERER'] ) ) $u = (string) wp_unslash( $_SERVER['HTTP_REFERER'] );
	$q = (string) wp_parse_url( $u, PHP_URL_QUERY );
	if ( '' === $q ) return array();
	parse_str( $q, $a ); $o = array();
	foreach ( array( 'source', 'medium', 'campaign' ) as $k ) {
		if ( ! empty( $a[ 'utm_' . $k ] ) && is_string( $a[ 'utm_' . $k ] ) ) $o[ $k ] = mb_substr( sanitize_text_field( $a[ 'utm_' . $k ] ), 0, 100 );
	}
	return $o;
}

/* Brevo attributes UTM_* — created once if the account does not have them */
function ss_brevo_utm_ready() {
	if ( get_option( 'ss_brevo_utm_attrs' ) ) return true;
	if ( ! function_exists( 'ss_brevo_api' ) || ! ss_brevo_key() ) return false;
	$g = ss_brevo_api( 'GET', 'contacts/attributes' );
	if ( 200 !== $g['code'] ) return false;
	$have = array();
	foreach ( (array) ( $g['body']['attributes'] ?? array() ) as $at ) if ( ! empty( $at['name'] ) ) $have[ $at['name'] ] = 1;
	foreach ( array( 'UTM_SOURCE', 'UTM_MEDIUM', 'UTM_CAMPAIGN' ) as $n ) {
		if ( isset( $have[ $n ] ) ) continue;
		$r = ss_brevo_api( 'POST', 'contacts/attributes/normal/' . $n, array( 'type' => 'text' ) );
		if ( ! in_array( $r['code'], array( 200, 201, 204 ), true ) ) return false;
	}
	update_option( 'ss_brevo_utm_attrs', 1, false );
	return true;
}

/* create / update a Brevo contact in "Free Pack" (+ UTM, + first name) — after the answer is sent, so the form stays fast */
function ss_brevo_push_later( $email, $first, $utm, $done = null ) {
	add_action( 'shutdown', function () use ( $email, $first, $utm, $done ) {
		if ( function_exists( 'fastcgi_finish_request' ) ) fastcgi_finish_request();
		if ( ! function_exists( 'ss_brevo_api' ) || ! ss_brevo_key() ) return;
		$at = array();
		if ( $first ) $at['FIRSTNAME'] = (string) $first;
		if ( $utm && ss_brevo_utm_ready() ) foreach ( $utm as $k => $v ) $at[ 'UTM_' . strtoupper( $k ) ] = $v;
		$c = array( 'email' => $email, 'updateEnabled' => true );
		if ( $at ) $c['attributes'] = $at;
		$lid = ss_brevo_free_list();
		if ( $lid ) $c['listIds'] = array( $lid );
		$r = ss_brevo_api( 'POST', 'contacts', $c );
		if ( $done ) call_user_func( $done, (int) $r['code'] );
	}, 20 );
}

if ( get_option( 'ss_brevo_utm_off' ) ) return;

/* 1) /free/ and «Try it free»: both write user meta ss_free_optin inside their AJAX request → keep the campaign */
$ss_utm_optin = function ( $mid, $uid, $key ) {
	if ( 'ss_free_optin' !== $key || ! wp_doing_ajax() || get_user_meta( $uid, 'ss_utm', true ) ) return;
	$utm = ss_utm_from_req();
	if ( ! $utm ) return;
	update_user_meta( $uid, 'ss_utm', $utm );
	if ( get_user_meta( $uid, 'ss_unsub', true ) ) return;
	$u = get_userdata( $uid );
	if ( $u ) ss_brevo_push_later( $u->user_email, $u->first_name, $utm );
};
add_action( 'added_user_meta', $ss_utm_optin, 20, 3 );
add_action( 'updated_user_meta', $ss_utm_optin, 20, 3 );

/* 2) feedback form: with the tick → Brevo "Free Pack" */
add_action( 'ss_fb_submitted', function ( $id, $d ) {
	$utm = ss_utm_from_req();
	if ( $utm ) update_post_meta( $id, 'ss_utm', $utm );
	if ( empty( $_POST['news'] ) || empty( $d['email'] ) ) return;
	update_post_meta( $id, 'ss_news', current_time( 'mysql' ) );
	ss_brevo_push_later( $d['email'], $d['name'] ?? '', $utm, function ( $code ) use ( $id ) { update_post_meta( $id, 'ss_brevo', $code ); } );
}, 10, 2 );

/* 3) one-off: FREE subscribers that never reached Brevo (no key at the time, or signed up again) */
add_action( 'ss_brevo_resync', function () {
	if ( ! function_exists( 'ss_brevo_api' ) || ! ss_brevo_key() ) { update_option( 'ss_brevo_resync', array( 'at' => current_time( 'mysql' ), 'err' => 'no Brevo key' ), false ); return; }
	$lid = ss_brevo_free_list(); $ids = get_users( array( 'meta_key' => 'ss_free_optin', 'fields' => 'ID', 'number' => 500 ) );
	$res = array( 'at' => current_time( 'mysql' ), 'site' => count( $ids ), 'already' => 0, 'unsub' => 0, 'sent' => 0, 'codes' => array() );
	foreach ( $ids as $uid ) {
		if ( get_user_meta( $uid, 'ss_unsub', true ) ) { $res['unsub']++; continue; }
		if ( in_array( (int) get_user_meta( $uid, 'ss_brevo', true ), array( 200, 201, 204 ), true ) ) { $res['already']++; continue; }
		$u = get_userdata( $uid ); if ( ! $u ) continue;
		$c = array( 'email' => $u->user_email, 'updateEnabled' => true );
		if ( $u->first_name ) $c['attributes'] = array( 'FIRSTNAME' => (string) $u->first_name );
		if ( $lid ) $c['listIds'] = array( $lid );
		$r = ss_brevo_api( 'POST', 'contacts', $c );
		update_user_meta( $uid, 'ss_brevo', (int) $r['code'] );
		$res['sent']++; $res['codes'][ (int) $r['code'] ] = ( $res['codes'][ (int) $r['code'] ] ?? 0 ) + 1;
	}
	update_option( 'ss_brevo_resync', $res, false );
} );
add_action( 'init', function () {
	if ( get_option( 'ss_brevo_resync' ) || wp_next_scheduled( 'ss_brevo_resync' ) ) return;
	wp_schedule_single_event( time() + 60, 'ss_brevo_resync' );
} );
