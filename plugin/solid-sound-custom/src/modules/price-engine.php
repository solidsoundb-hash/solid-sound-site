<?php
/**
 * Price engine — prices follow demand (owner 20.11.2026: «ценники сами подстраиваются по аналитике, как курс доллара»;
 * «можно и не плавно»; «для новых клиентов на основе спроса»).
 *  - Base prices: ss_sc_prices_base() in studio-calc.php (competitor prices −40 %, see docs/prices.md).
 *  - Each service has a demand factor (0.7–1.3, steps of 10 %) and there is a global factor (to change the discount for all).
 *  - Every week: demand of the last 7 days (calculator opens + 10 × requests) is compared with the 7 days before;
 *    up by 30 %+ → +10 %, down by 30 %+ → −10 %. At least 5 points of data, otherwise nothing changes.
 *  - Prices up to €50 never move. Rounded to …9. Every change is logged and emailed to the admin; Tools → Prices to see / reset.
 *  - Loyal clients: a logged-in client who already ordered keeps the price of their first order (ss_pe_loyal()).
 * The calculator, the Services & Prices tables (ss_pe_table) and the home slider (ss_pe_slider) all read the same prices.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_pe_services() { return array( 'mixing', 'release', 'mastering', 'remix', 'remake', 'ghost', 'copro', 'post' ); }
function ss_pe_factor( $svc ) {
	if ( ! get_option( 'ss_pe_on' ) ) return 1.0; /* owner 20.11: «автоцену пока не юзать» — off until ss_pe_on is set */
	if ( function_exists( 'ss_pe_loyal' ) && ss_pe_loyal() ) return 1.0;
	$f = (array) get_option( 'ss_pe_factor', array() ); $g = (float) get_option( 'ss_pe_global', 1 );
	$x = isset( $f[ $svc ] ) ? (float) $f[ $svc ] : 1.0; if ( $g <= 0 ) $g = 1.0;
	return max( 0.5, min( 1.6, $x * $g ) );
}
function ss_pe_nice( $v ) { $r = (int) round( $v ); $hi = (int) ( floor( $r / 10 ) * 10 + 9 ); $lo = $hi - 10; return ( $hi - $r ) < ( $r - $lo ) ? $hi : max( 9, $lo ); }
function ss_pe_price( $v, $svc ) {
	$v = (float) $v; if ( $v <= 50 ) return $v;
	$f = ss_pe_factor( $svc ); if ( abs( $f - 1 ) < 0.001 ) return $v;
	return (float) ss_pe_nice( $v * $f );
}
function ss_pe_apply( $P ) {
	foreach ( ss_pe_services() as $s ) {
		if ( empty( $P[ $s ] ) ) continue;
		if ( ! empty( $P[ $s ]['tiers'] ) ) foreach ( $P[ $s ]['tiers'] as $i => $t ) $P[ $s ]['tiers'][ $i ][2] = ss_pe_price( $t[2], $s );
		if ( ! empty( $P[ $s ]['pkgs'] ) ) foreach ( $P[ $s ]['pkgs'] as $i => $t ) $P[ $s ]['pkgs'][ $i ][1] = ss_pe_price( $t[1], $s );
		if ( ! empty( $P[ $s ]['types'] ) ) foreach ( $P[ $s ]['types'] as $k => $t ) $P[ $s ]['types'][ $k ][1] = ss_pe_price( $t[1], $s );
		if ( ! empty( $P[ $s ]['sub'] ) ) $P[ $s ]['sub'][1] = ss_pe_price( $P[ $s ]['sub'][1], $s );
	}
	return $P;
}
/* the list of (base, now) prices of a service, in table order */
function ss_pe_pairs( $svc ) {
	if ( ! function_exists( 'ss_sc_prices_base' ) ) return array();
	$B = ss_sc_prices_base(); $N = ss_pe_apply( $B ); $o = array();
	foreach ( array( 'tiers' => 2, 'pkgs' => 1, 'types' => 1 ) as $k => $ix ) if ( ! empty( $B[ $svc ][ $k ] ) ) foreach ( $B[ $svc ][ $k ] as $i => $t ) $o[] = array( $t[ $ix ], $N[ $svc ][ $k ][ $i ][ $ix ] );
	return $o;
}
/* Services & Prices tables: swap the base numbers for today's ones inside each service's section and its tab */
function ss_pe_table( $html ) {
	$map = array( 'mixing' => 'mixing', 'release' => 'bundle', 'mastering' => 'mastering', 'remix' => 'remix', 'remake' => 'remake', 'ghost' => 'ghost', 'copro' => 'coproducing', 'post' => 'post' );
	foreach ( $map as $svc => $id ) {
		$pairs = array_filter( ss_pe_pairs( $svc ), function ( $p ) { return abs( $p[0] - $p[1] ) > 0.001; } );
		if ( ! $pairs ) continue;
		$sw = function ( $t ) use ( $pairs ) { foreach ( $pairs as $p ) $t = preg_replace( '/(?<![\d.])' . preg_quote( (string) (int) $p[0], '/' ) . '€/u', '{{' . (int) $p[1] . '}}€', $t ); return preg_replace( '/\{\{(\d+)\}\}/', '$1', $t ); };
		$html = preg_replace_callback( '#<section class="pr-sec" id="pr-' . $id . '".*?</section>#s', function ( $m ) use ( $sw ) { return $sw( $m[0] ); }, $html );
		$html = preg_replace_callback( '#<a role="tab" href="\#pr-' . $id . '".*?</a>#s', function ( $m ) use ( $sw ) { return $sw( $m[0] ); }, $html );
	}
	return $html;
}
/* the home slider's «Finishing» descriptions */
function ss_pe_slider() {
	$P = function_exists( 'ss_sc_prices' ) ? ss_sc_prices() : array();
	$g = function ( $s, $k, $i, $ix ) use ( $P ) { return isset( $P[ $s ][ $k ][ $i ][ $ix ] ) ? (int) $P[ $s ][ $k ][ $i ][ $ix ] : 0; };
	return array( 'mix' => $g( 'mixing', 'tiers', 0, 2 ), 'bundle' => $g( 'release', 'tiers', 0, 2 ), 'master' => isset( $P['mastering']['types']['stereo'][1] ) ? (int) $P['mastering']['types']['stereo'][1] : 25,
		'rework' => $g( 'copro', 'pkgs', 1, 1 ), 'live' => isset( $P['extras']['live'][1] ) ? (int) $P['extras']['live'][1] : 39 );
}

/* ---------- demand ---------- */
function ss_pe_bot() { $ua = isset( $_SERVER['HTTP_USER_AGENT'] ) ? (string) $_SERVER['HTTP_USER_AGENT'] : ''; return ! $ua || preg_match( '/bot|crawl|spider|slurp|headless|SolidSound|lighthouse|preview/i', $ua ) || current_user_can( 'manage_options' ); }
function ss_pe_hit( $svc, $kind ) {
	if ( ! in_array( $svc, ss_pe_services(), true ) || ss_pe_bot() ) return;
	$d = (array) get_option( 'ss_pe_demand', array() ); $day = gmdate( 'Y-m-d' );
	if ( ! isset( $d[ $day ] ) ) $d[ $day ] = array();
	$d[ $day ][ $svc ][ $kind ] = ( $d[ $day ][ $svc ][ $kind ] ?? 0 ) + 1;
	ksort( $d ); $d = array_slice( $d, -30, null, true );
	update_option( 'ss_pe_demand', $d, false );
}
/* calculator opened for a service */
add_action( 'template_redirect', function () {
	if ( ! function_exists( 'ss_sc_path_ok' ) || ! ss_sc_path_ok() || empty( $_GET['s'] ) ) return;
	ss_pe_hit( sanitize_key( wp_unslash( $_GET['s'] ) ), 'v' );
}, 0 );
/* a request sent from the calculator (before ss_sc_request redirects) */
add_action( 'admin_post_nopriv_ss_studio_request', 'ss_pe_req', 1 );
add_action( 'admin_post_ss_studio_request', 'ss_pe_req', 1 );
function ss_pe_req() {
	if ( ! empty( $_POST['website'] ) || ! function_exists( 'ss_sc_prices_base' ) ) return;
	$name = strtolower( sanitize_text_field( wp_unslash( $_POST['service'] ?? '' ) ) );
	foreach ( ss_sc_prices_base() as $k => $c ) if ( in_array( $k, ss_pe_services(), true ) && ! empty( $c['name'] ) && 0 === strpos( $name, strtolower( strtok( $c['name'], ' (' ) ) ) ) { ss_pe_hit( $k, 'r' ); break; }
}
function ss_pe_score( $d, $from, $to, $svc ) { $s = 0; foreach ( $d as $day => $row ) if ( $day >= $from && $day < $to && isset( $row[ $svc ] ) ) $s += ( $row[ $svc ]['v'] ?? 0 ) + 10 * ( $row[ $svc ]['r'] ?? 0 ); return $s; }
/* weekly: move the factors */
function ss_pe_week() {
	$d = (array) get_option( 'ss_pe_demand', array() ); $f = (array) get_option( 'ss_pe_factor', array() ); $log = (array) get_option( 'ss_pe_log', array() );
	$t = gmdate( 'Y-m-d' ); $w1 = gmdate( 'Y-m-d', strtotime( '-7 days' ) ); $w2 = gmdate( 'Y-m-d', strtotime( '-14 days' ) ); $lines = array();
	foreach ( ss_pe_services() as $s ) {
		$now = ss_pe_score( $d, $w1, $t, $s ); $prev = ss_pe_score( $d, $w2, $w1, $s );
		if ( $now + $prev < 5 ) continue;
		$ratio = $prev > 0 ? $now / $prev : 2; $old = isset( $f[ $s ] ) ? (float) $f[ $s ] : 1.0; $new = $old;
		if ( $ratio >= 1.3 ) $new = min( 1.3, $old + 0.1 ); elseif ( $ratio <= 0.7 ) $new = max( 0.7, $old - 0.1 );
		if ( abs( $new - $old ) < 0.001 ) continue;
		$f[ $s ] = round( $new, 2 );
		$log[] = array( 't' => time(), 's' => $s, 'old' => $old, 'new' => $f[ $s ], 'now' => $now, 'prev' => $prev );
		$lines[] = sprintf( '%s: %+d %% demand (%d → %d) → prices ×%.2f', $s, round( ( $ratio - 1 ) * 100 ), $prev, $now, $f[ $s ] );
	}
	update_option( 'ss_pe_factor', $f, false ); update_option( 'ss_pe_log', array_slice( $log, -100 ), false );
	if ( $lines ) wp_mail( get_option( 'admin_email' ), 'Solid Sound: studio prices moved with demand', implode( "\n", $lines ) . "\n\nSee or reset: " . admin_url( 'tools.php?page=ss-prices' ) );
}
add_action( 'ss_pe_week', function () { if ( get_option( 'ss_pe_on' ) ) ss_pe_week(); } ); /* off for now (owner 20.11); demand is still counted */
add_action( 'init', function () { if ( ! wp_next_scheduled( 'ss_pe_week' ) ) wp_schedule_event( strtotime( 'next monday 03:17 UTC' ), 'weekly', 'ss_pe_week' ); } );

/* loyal clients: someone who already sent a studio request with this account keeps the base prices */
function ss_pe_loyal() { static $v = null; if ( null !== $v ) return $v; $v = is_user_logged_in() && (bool) get_user_meta( get_current_user_id(), '_ss_pe_client', true ); return $v; }
add_action( 'admin_post_ss_studio_request', function () { if ( is_user_logged_in() ) update_user_meta( get_current_user_id(), '_ss_pe_client', time() ); }, 2 );

/* ---------- Tools → Prices ---------- */
add_action( 'admin_menu', function () {
	add_management_page( 'Studio prices', 'Studio prices', 'manage_options', 'ss-prices', function () {
		if ( ! empty( $_POST['ss_pe_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ss_pe_nonce'] ) ), 'ss_pe' ) ) {
			if ( isset( $_POST['global'] ) ) update_option( 'ss_pe_global', max( 0.5, min( 1.6, (float) $_POST['global'] ) ), false );
			if ( ! empty( $_POST['reset'] ) ) update_option( 'ss_pe_factor', array(), false );
			if ( ! empty( $_POST['run'] ) ) ss_pe_week();
			echo '<div class="notice notice-success"><p>Saved.</p></div>';
		}
		$f = (array) get_option( 'ss_pe_factor', array() ); $d = (array) get_option( 'ss_pe_demand', array() ); $t = gmdate( 'Y-m-d' ); $w1 = gmdate( 'Y-m-d', strtotime( '-7 days' ) ); $w2 = gmdate( 'Y-m-d', strtotime( '-14 days' ) );
		echo '<div class="wrap"><h1>Studio prices (demand)</h1><p><b>' . ( get_option( 'ss_pe_on' ) ? 'Auto prices: ON.' : 'Auto prices: OFF — demand is only counted (owner 20.11).' ) . '</b> Prices move ±10 % a week with demand (calculator opens + 10 × requests), within 70–130 % of the base. Up to €50 never moves. Base: docs/prices.md.</p><table class="widefat striped" style="max-width:760px"><thead><tr><th>Service</th><th>Factor</th><th>Last 7 days</th><th>7 days before</th><th>Prices now</th></tr></thead><tbody>';
		foreach ( ss_pe_services() as $s ) { $pp = array_map( function ( $p ) { return '€' . (int) $p[1]; }, ss_pe_pairs( $s ) ); echo '<tr><td>' . esc_html( $s ) . '</td><td>×' . esc_html( number_format( isset( $f[ $s ] ) ? (float) $f[ $s ] : 1, 2 ) ) . '</td><td>' . (int) ss_pe_score( $d, $w1, $t, $s ) . '</td><td>' . (int) ss_pe_score( $d, $w2, $w1, $s ) . '</td><td>' . esc_html( implode( ' / ', $pp ) ) . '</td></tr>'; }
		echo '</tbody></table><form method="post" style="margin-top:16px">' . wp_nonce_field( 'ss_pe', 'ss_pe_nonce', true, false ) . '<p><label>All prices × <input name="global" type="number" step="0.05" min="0.5" max="1.6" value="' . esc_attr( (string) get_option( 'ss_pe_global', 1 ) ) . '" style="width:80px"></label> <button class="button button-primary">Save</button> <button class="button" name="run" value="1">Run the weekly check now</button> <button class="button" name="reset" value="1" onclick="return confirm(\'Reset all demand factors to 1?\')">Reset demand factors</button></p></form>';
		$log = array_reverse( (array) get_option( 'ss_pe_log', array() ) );
		if ( $log ) { echo '<h2>Changes</h2><ul>'; foreach ( array_slice( $log, 0, 30 ) as $l ) echo '<li>' . esc_html( gmdate( 'Y-m-d', $l['t'] ) . ' — ' . $l['s'] . ': ×' . $l['old'] . ' → ×' . $l['new'] . ' (demand ' . $l['prev'] . ' → ' . $l['now'] . ')' ) . '</li>'; echo '</ul>'; }
		echo '</div>';
	} );
} );
