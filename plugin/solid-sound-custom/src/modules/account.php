<?php
/**
 * Client "My account" = the client dashboard.
 *
 * - Clients: WooCommerce My Account shows the dashboard (Overview) plus Orders, Downloads and Settings
 *   in the same design, with one tab bar. The data for the Overview is printed with the page (window.SSD),
 *   so the dashboard no longer loads 10+ pages in the background.
 * - Vendors use the WCFM "Producer space"; their My Account root goes there.
 * - The old /client-dashboard/ page (2561) redirects here.
 * - Page 34 content: the stray "ADD PRODUCT" link/button are dropped and the column is full width.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_acc_myaccount_url( $endpoint = '' ) {
	$u = wc_get_page_permalink( 'myaccount' );
	return $endpoint ? wc_get_endpoint_url( $endpoint, '', $u ) : $u;
}
function ss_acc_is_vendor_only() {
	return is_user_logged_in() && function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor() && ! current_user_can( 'manage_woocommerce' );
}
function ss_acc_is_client_view() {
	return is_user_logged_in() && ! ss_acc_is_vendor_only() && function_exists( 'is_account_page' ) && is_account_page();
}

/* ---------- redirects ---------- */
add_action( 'template_redirect', function () {
	if ( ! function_exists( 'is_account_page' ) ) return;
	$ids = function_exists( 'pll_get_post_translations' ) ? array_values( (array) pll_get_post_translations( 2561 ) ) : array();
	$ids[] = 2561;
	if ( is_page( $ids ) ) {
		$hash = '';
		wp_safe_redirect( ss_acc_myaccount_url() . $hash, 301 );
		exit;
	}
	if ( is_account_page() && ss_acc_is_vendor_only() && ! is_wc_endpoint_url() ) {
		wp_safe_redirect( function_exists( 'get_wcfm_url' ) ? get_wcfm_url() : home_url( '/en/store-manager/' ) );
		exit;
	}
} );

/* ---------- page 34: no "ADD PRODUCT", full-width column ---------- */
add_filter( 'the_content', function ( $c ) {
	if ( false === strpos( $c, '[woocommerce_my_account]' ) ) return $c;
	$c = preg_replace( '#<a [^>]*add-product[^>]*>\s*ADD PRODUCT\s*</a>#i', '', $c );
	$c = preg_replace( '#\[us_btn [^\]]*ADD PRODUCT[^\]]*\]#i', '', $c );
	$c = str_replace( array( '[vc_column width="1/6"][/vc_column]', '[vc_column width="2/3"]' ), array( '', '[vc_column]' ), $c );
	return $c;
}, 1 );

/* ---------- client shell: tab bar + dashboard on the root endpoint ---------- */
add_filter( 'body_class', function ( $c ) {
	if ( ss_acc_is_client_view() ) $c[] = 'ssa-on';
	return $c;
} );

function ss_acc_icon( $d ) {
	return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' . esc_attr( $d ) . '"/></svg>';
}
function ss_acc_nav() {
	$ep   = WC()->query->get_current_endpoint();
	$root = ss_acc_myaccount_url();
	$i = array(
		'dash' => 'M3 3h8v8H3zM13 3h8v5h-8zM13 10h8v11h-8zM3 13h8v8H3z',
		'serv' => 'M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6',
		'down' => 'M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2',
		'bag'  => 'M6 7h12l1 13H5zM9 7a3 3 0 0 1 6 0',
		'fav'  => 'M12 20s-7-4.4-9.2-8.6C1.2 8.2 3 4.5 6.6 4.5c2.1 0 3.4 1.2 5.4 3.2 2-2 3.3-3.2 5.4-3.2 3.6 0 5.4 3.7 3.8 6.9C19 15.6 12 20 12 20z',
		'set'  => 'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z',
		'out'  => 'M10 17l5-5-5-5M15 12H3M14 3h6v18h-6',
	);
	$settings = in_array( $ep, array( 'edit-account', 'edit-address', 'payment-methods', 'add-payment-method' ), true );
	$tabs = array(
		array( 'Overview', 'dash', $root, '' === $ep ),
		array( 'Projects', 'serv', $root . '#projects', false ),
		array( 'Downloads', 'down', ss_acc_myaccount_url( 'downloads' ), 'downloads' === $ep ),
		array( 'Orders', 'bag', ss_acc_myaccount_url( 'orders' ), in_array( $ep, array( 'orders', 'view-order' ), true ) ),
		array( 'Favourites', 'fav', $root . '#favourites', false ),
		array( 'Settings', 'set', ss_acc_myaccount_url( 'edit-account' ), $settings ),
	);
	echo '<nav class="ssa-nav" aria-label="My account">';
	foreach ( $tabs as $t ) {
		echo '<a href="' . esc_url( $t[2] ) . '"' . ( $t[3] ? ' class="on" aria-current="page"' : '' ) . '>' . ss_acc_icon( $i[ $t[1] ] ) . '<span>' . esc_html( $t[0] ) . '</span></a>';
	}
	echo '<a class="out" href="' . esc_url( wc_logout_url() ) . '">' . ss_acc_icon( $i['out'] ) . '<span>Log out</span></a></nav>';
	if ( $settings ) {
		$sub = array( array( 'Account details', 'edit-account' ), array( 'Addresses', 'edit-address' ), array( 'Payment methods', 'payment-methods' ) );
		echo '<nav class="ssa-sub" aria-label="Settings">';
		foreach ( $sub as $s ) {
			$on = $ep === $s[1] || ( 'payment-methods' === $s[1] && 'add-payment-method' === $ep );
			echo '<a href="' . esc_url( ss_acc_myaccount_url( $s[1] ) ) . '"' . ( $on ? ' class="on"' : '' ) . '>' . esc_html( $s[0] ) . '</a>';
		}
		echo '</nav>';
	}
}

function ss_acc_data() {
	$uid = get_current_user_id();
	$u   = wp_get_current_user();
	$out = array( 'name' => $u->first_name ? $u->first_name : $u->display_name, 'orders' => array(), 'downloads' => array() );
	$money = function ( $o ) { return trim( html_entity_decode( wp_strip_all_tags( wc_price( $o->get_total(), array( 'currency' => $o->get_currency() ) ) ), ENT_QUOTES, 'UTF-8' ) ); };
	foreach ( wc_get_orders( array( 'customer_id' => $uid, 'limit' => 8, 'orderby' => 'date', 'order' => 'DESC' ) ) as $o ) {
		$items = array();
		foreach ( $o->get_items() as $it ) $items[] = $it->get_name();
		$notes = array();
		foreach ( $o->get_customer_order_notes() as $n ) {
			$html = (string) $n->comment_content;
			preg_match_all( '#href=["\']([^"\']+)#i', $html, $a );
			$txt = trim( html_entity_decode( wp_strip_all_tags( $html ), ENT_QUOTES, 'UTF-8' ) );
			preg_match_all( '#https?://\S+?\.(?:mp3|wav|m4a|flac)\b\S*#i', $txt, $b );
			$audio = array_values( array_unique( array_filter( array_merge( $a[1], $b[0] ), function ( $x ) { return preg_match( '#\.(mp3|wav|m4a|flac)([?\#]|$)#i', $x ); } ) ) );
			$notes[] = array( 'text' => $txt, 'date' => date_i18n( wc_date_format(), strtotime( $n->comment_date ) ), 'audio' => $audio );
		}
		$out['orders'][] = array(
			'url' => $o->get_view_order_url(), 'num' => (string) $o->get_order_number(), 'status' => wc_get_order_status_name( $o->get_status() ),
			'date' => wc_format_datetime( $o->get_date_created() ), 'total' => $money( $o ), 'items' => $items, 'notes' => $notes,
		);
	}
	foreach ( wc_get_customer_available_downloads( $uid ) as $d ) {
		$out['downloads'][] = array(
			'name' => $d['product_name'], 'url' => $d['download_url'], 'product' => $d['product_url'],
			'left' => is_numeric( $d['downloads_remaining'] ) ? (string) $d['downloads_remaining'] : '',
			'exp'  => ! empty( $d['access_expires'] ) ? wc_format_datetime( new WC_DateTime( $d['access_expires'] ) ) : '',
		);
	}
	return $out;
}

add_action( 'wp', function () {
	if ( ! ss_acc_is_client_view() ) return;
	remove_action( 'woocommerce_account_navigation', 'woocommerce_account_navigation' );
	add_action( 'woocommerce_account_navigation', 'ss_acc_nav' );
	remove_action( 'woocommerce_account_content', 'woocommerce_account_content' );
	add_action( 'woocommerce_account_content', function () {
		if ( '' !== WC()->query->get_current_endpoint() ) { woocommerce_account_content(); return; }
		echo '<script>window.SSD=' . wp_json_encode( ss_acc_data(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE ) . ';</script>';
		echo '<div id="ssd" aria-live="polite"><div class="sk" style="width:40%"></div><div class="sk"></div><div class="sk" style="width:70%"></div></div>';
	} );
	add_action( 'wp_head', function () { ss_asset( 'ss-account-css' ); }, 50 );
	add_action( 'wp_footer', function () { if ( '' === WC()->query->get_current_endpoint() ) ss_asset( 'ss-account-js', 'defer' ); }, 50 );
} );
