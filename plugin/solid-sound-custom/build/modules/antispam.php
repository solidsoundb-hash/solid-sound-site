<?php
/**
 * Spam accounts cleanup (owner 17.11.2026: "делай"). The forum showed ~2,500 "members" — bot sign-ups
 * (Gmail dot-trick names like "ashleysh.eedy90"), all as customers.
 *
 * Admin → Users → "Spam cleanup": shows how many accounts look like spam, examples, how many new ones came in
 * the last 7 days (to see if the sign-up protection works), and deletes them ONLY when the owner presses the button.
 *
 * "Looks like spam" = the existing rule ss_spam_ids() in custom.php (customer, registered > 1 day ago, no orders,
 * no reviews, no forum posts, no sign-up consent) MINUS anyone with a real trace: Google sign-in, "try it free"
 * lead, any WooCommerce order, a vendor/staff role.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_as_ids() {
	if ( ! function_exists( 'ss_spam_ids' ) ) return array();
	$ids = ss_spam_ids();
	if ( ! $ids ) return array();
	global $wpdb;
	$keep = $wpdb->get_col( "SELECT DISTINCT user_id FROM {$wpdb->usermeta} WHERE meta_key IN ('ss_google_sub','ss_signup_source','_wcfm_vendor','wcfm_vendor_store') AND meta_value <> ''" );
	$keep = array_flip( array_map( 'intval', (array) $keep ) );
	return array_values( array_filter( $ids, function ( $id ) use ( $keep ) { return ! isset( $keep[ $id ] ) && ! user_can( $id, 'edit_posts' ); } ) );
}

add_action( 'admin_menu', function () {
	add_users_page( 'Spam cleanup', 'Spam cleanup', 'delete_users', 'ss-spam', 'ss_as_page' );
} );

function ss_as_page() {
	if ( ! current_user_can( 'delete_users' ) ) return;
	global $wpdb;
	$ids = ss_as_ids();
	$total = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->users}" );
	$week = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->users} WHERE user_registered > DATE_SUB(NOW(), INTERVAL 7 DAY)" );
	$sample = $ids ? $wpdb->get_results( 'SELECT ID, user_login, user_registered FROM ' . $wpdb->users . ' WHERE ID IN (' . implode( ',', array_map( 'intval', array_slice( $ids, 0, 25 ) ) ) . ') ORDER BY user_registered DESC' ) : array();
	$range = $ids ? $wpdb->get_row( 'SELECT MIN(user_registered) a, MAX(user_registered) b FROM ' . $wpdb->users . ' WHERE ID IN (' . implode( ',', array_map( 'intval', $ids ) ) . ')' ) : null;
	echo '<div class="wrap"><h1>Spam cleanup</h1>';
	echo '<p style="font-size:15px">All accounts: <b>' . esc_html( number_format_i18n( $total ) ) . '</b> · look like spam: <b style="color:#b32d2e">' . esc_html( number_format_i18n( count( $ids ) ) ) . '</b>'
		. ( $range ? ' (registered ' . esc_html( substr( $range->a, 0, 10 ) ) . ' — ' . esc_html( substr( $range->b, 0, 10 ) ) . ')' : '' )
		. ' · new sign-ups in the last 7 days: <b>' . esc_html( (string) $week ) . '</b></p>';
	echo '<p>Spam = customer account, registered more than a day ago, with no orders, no reviews, no forum posts, no sign-up consent, no Google sign-in and not a "try it free" lead. Staff, vendors and anyone who bought are never touched.</p>';
	if ( $sample ) {
		echo '<table class="widefat striped" style="max-width:640px"><thead><tr><th>Login</th><th>Registered</th></tr></thead><tbody>';
		foreach ( $sample as $u ) echo '<tr><td>' . esc_html( $u->user_login ) . '</td><td>' . esc_html( $u->user_registered ) . '</td></tr>';
		echo '</tbody></table><p><i>Showing 25 of ' . esc_html( number_format_i18n( count( $ids ) ) ) . '.</i></p>';
		echo '<p><button type="button" class="button button-primary button-hero" id="ss-as-go" style="background:#b32d2e;border-color:#b32d2e">Delete ' . esc_html( number_format_i18n( count( $ids ) ) ) . ' spam accounts</button></p>';
		echo '<p id="ss-as-st" style="font-size:15px"></p>';
		$nonce = wp_create_nonce( 'ss_as_del' );
		echo '<script>(function(){var b=document.getElementById("ss-as-go"),st=document.getElementById("ss-as-st"),n=0;b.onclick=function(){if(!confirm("Delete all accounts that look like spam? This cannot be undone."))return;b.disabled=true;step()};'
			. 'function step(){var f=new FormData();f.append("action","ss_as_del");f.append("_wpnonce",' . wp_json_encode( $nonce ) . ');fetch(ajaxurl,{method:"POST",body:f,credentials:"same-origin"}).then(function(r){return r.json()}).then(function(j){if(!j||!j.success){st.textContent="Stopped: "+((j&&j.data)||"error")+". Reload the page and press again.";b.disabled=false;return}n+=j.data.deleted;st.textContent="Deleted "+n+" … left "+j.data.left;if(j.data.left>0&&j.data.deleted>0)setTimeout(step,300);else{st.textContent="Done: deleted "+n+" spam accounts.";}}).catch(function(){st.textContent="Network error — reload and press again.";b.disabled=false})}})();</script>';
	} else {
		echo '<p style="font-size:15px;color:#1d6b3a"><b>No spam accounts found.</b></p>';
	}
	echo '</div>';
}

add_action( 'wp_ajax_ss_as_del', function () {
	if ( ! current_user_can( 'delete_users' ) || ! check_ajax_referer( 'ss_as_del', '_wpnonce', false ) ) wp_send_json_error( 'not allowed' );
	require_once ABSPATH . 'wp-admin/includes/user.php';
	delete_transient( 'ss_spam_ids' );
	$ids = ss_as_ids();
	$done = 0; $t0 = microtime( true );
	foreach ( array_slice( $ids, 0, 150 ) as $id ) {
		if ( (int) $id === get_current_user_id() ) continue;
		if ( wp_delete_user( (int) $id ) ) $done++;
		if ( microtime( true ) - $t0 > 20 ) break; /* stay well inside the host's time limit; the page asks again */
	}
	delete_transient( 'ss_spam_ids' );
	wp_send_json_success( array( 'deleted' => $done, 'left' => max( 0, count( $ids ) - $done ) ) );
} );
