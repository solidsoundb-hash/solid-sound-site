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
function ss_acc_is_client_view() { /* one account, two roles: everyone gets the client view */
	return is_user_logged_in() && function_exists( 'is_account_page' ) && is_account_page();
}
function ss_acc_is_vendor() { return function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor(); }

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
	/* 19.11 owner: the tab bar also on the overview, so tabs switch like a slider (account/cab.js) */
	$tabs = array(
		array( 'My account', 'dash', $root, '' === $ep ),
		array( 'Purchases', 'bag', ss_acc_myaccount_url( 'orders' ), in_array( $ep, array( 'orders', 'view-order' ), true ) ),
		array( 'Downloads', 'down', ss_acc_myaccount_url( 'downloads' ), 'downloads' === $ep ),
		array( 'Settings', 'set', ss_acc_myaccount_url( 'edit-account' ), $settings ),
	);
	echo '<nav class="ssa-nav" aria-label="My account">';
	foreach ( $tabs as $t ) {
		echo '<a href="' . esc_url( $t[2] ) . '"' . ( $t[3] ? ' class="on" aria-current="page"' : '' ) . '>' . ss_acc_icon( $i[ $t[1] ] ) . '<span>' . esc_html( $t[0] ) . '</span></a>';
	}
	echo '<a class="out" href="' . esc_url( wc_logout_url() ) . '">' . ss_acc_icon( $i['out'] ) . '<span>Log out</span></a></nav>';
	if ( $settings ) {
		$sub = array( array( 'Account details', 'edit-account' ), array( 'Billing details', 'edit-address' ), array( 'Payment methods', 'payment-methods' ) );
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
		try { $data = ss_acc_data(); } catch ( \Throwable $e ) { $data = array( 'name' => '', 'orders' => array(), 'downloads' => array(), 'error' => $e->getMessage() ); }
		$json = wp_json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR );
		echo '<script>window.SSD=' . ( $json ? $json : '{"name":"","orders":[],"downloads":[]}' ) . ';</script>';
		echo ss_acc_render( $data );
	} );
	add_action( 'wp_head', function () { ss_asset( 'ss-account-css' ); }, 50 );
	add_action( 'wp_footer', function () { if ( '' === WC()->query->get_current_endpoint() ) ss_asset( 'ss-account-js', 'defer' ); ss_asset( 'ss-cab', 'defer' ); }, 50 );
} );

/* ---------- Overview, rendered on the server (same markup and classes as the client dashboard) ---------- */
function ss_acc_ic( $k, $s = 18 ) {
	static $I = array(
		'dash' => 'M3 3h8v8H3zM13 3h8v5h-8zM13 10h8v11h-8zM3 13h8v8H3z', 'serv' => 'M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6',
		'down' => 'M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2', 'fav' => 'M12 20s-7-4.4-9.2-8.6C1.2 8.2 3 4.5 6.6 4.5c2.1 0 3.4 1.2 5.4 3.2 2-2 3.3-3.2 5.4-3.2 3.6 0 5.4 3.7 3.8 6.9C19 15.6 12 20 12 20z',
		'calc' => 'M6 2h12a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zM8 6h8v4H8zM8 14h.01M12 14h.01M16 14h.01M8 18h.01', 'chat' => 'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z',
		'head' => 'M3 14v-2a9 9 0 0 1 18 0v2M3 14a2 2 0 0 1 2-2h1v7H5a2 2 0 0 1-2-2zM21 14a2 2 0 0 0-2-2h-1v7h1a2 2 0 0 0 2-2z', 'user' => 'M16 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 21c1.5-4 4.5-6 8-6s6.5 2 8 6',
		'out' => 'M14 3h6v18h-6M10 17l-5-5 5-5M5 12h12', 'free' => 'M4.5 8h15A1.5 1.5 0 0 1 21 9.5v10a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 19.5v-10A1.5 1.5 0 0 1 4.5 8zM12 8v13M3 12h18',
		'bag' => 'M6 7h12l1 14H5zM9 7a3 3 0 0 1 6 0', 'home' => 'M3 11l9-7 9 7v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z', 'card' => 'M3 6h18v12H3zM3 10h18',
	);
	return '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' . $I[ $k ] . '"></path></svg>';
}
function ss_acc_step( $o ) {
	$s = strtolower( $o['status'] );
	$rev = false; foreach ( $o['notes'] as $n ) if ( $n['audio'] ) $rev = true;
	if ( false !== strpos( $s, 'complet' ) ) return 4;
	if ( $rev ) return 3;
	if ( false !== strpos( $s, 'process' ) ) return $o['notes'] ? 2 : 1;
	return 0;
}
function ss_acc_render( $d ) {
	$e = 'esc_html'; $a = 'esc_attr';
	$S = 'https://solid-sound.net'; $L = $S . '/en'; $ACC = ss_acc_myaccount_url();
	$svc = '/(mix|master|vocal|ghost|produc|remix|remake|post|stem|tuning|feedback|session|service|sound design|foley)/i';
	$STEPS = array( 'Ordered', 'Received', 'In work', 'Revision', 'Delivered' );
	$proj = array();
	foreach ( $d['orders'] as $o ) { foreach ( $o['items'] as $n ) { if ( preg_match( $svc, $n ) ) { $proj[] = $o; break; } } }
	$active = 0; foreach ( $proj as $o ) if ( ss_acc_step( $o ) < 4 ) $active++;
	$head = function ( $t, $i ) { return '<h2><span class="t">' . ss_acc_ic( $i ) . '</span>' . $t . '</h2>'; };

	$vendor = ss_acc_is_vendor();
	$role = '<span class="ssa-role">' . ( $vendor ? 'Client · Vendor' : 'Client account' ) . '</span>';
	$second = $vendor
		? '<a class="btn g" href="' . esc_url( function_exists( 'get_wcfm_url' ) ? get_wcfm_url() : home_url( '/en/store-manager/' ) ) . '">' . ss_acc_ic( 'serv' ) . 'Producer space</a>'
		: '<a class="btn g" href="' . esc_url( home_url( '/sell/' ) ) . '">' . ss_acc_ic( 'free' ) . 'Start selling</a>';
	$h  = '<div id="ssd" data-ssr="1"><div class="top"><div><h1>My account ' . $role . '</h1><p class="sub">' . ( $d['name'] ? 'Hi ' . esc_html( $d['name'] ) . '! ' : '' ) . 'Your projects, revisions and downloads.' . apply_filters( 'ss_acc_sub_extra', '' ) . '</p></div><div class="top-b">' . $second . '<a class="btn" href="' . $S . '/studio-calculator/?s=mixing">' . ss_acc_ic( 'calc' ) . 'Order a mix or master</a></div></div>';
	$h .= '<div class="stats">';
	foreach ( array( array( $active, 'Active projects', '#projects' ), array( count( $proj ) - $active, 'Delivered', '#projects' ), array( count( $d['downloads'] ), 'Downloads', '#downloads' ), array( 0, 'Favourites', '#favourites' ) ) as $st )
		$h .= '<a class="st" href="' . $st[2] . '"' . ( 'Favourites' === $st[1] ? ' data-favn' : '' ) . '><b>' . (int) $st[0] . '</b><span>' . $st[1] . '</span></a>';
	$h .= '</div><div class="grid"><div>';

	/* projects */
	$h .= '<section class="p" id="projects">' . $head( 'My projects', 'serv' );
	if ( $proj ) {
		foreach ( array_slice( $proj, 0, 6 ) as $k => $o ) {
			$step = ss_acc_step( $o ); $done = 4 === $step;
			$h .= '<div class="proj"><div class="ph"><div><b>' . $e( $o['items'] ? implode( ', ', $o['items'] ) : 'Order #' . $o['num'] ) . '</b><br><small>Order #' . $e( $o['num'] ) . ' · ' . $e( $o['date'] ) . ( $o['total'] ? ' · ' . $e( $o['total'] ) : '' ) . '</small></div><span class="badge' . ( $done ? '' : ' w' ) . '">' . $STEPS[ $step ] . '</span></div>';
			$h .= '<ol class="steps" aria-label="Project progress">';
			foreach ( $STEPS as $i => $sn ) $h .= '<li class="' . ( $i <= $step ? 'on' : '' ) . '"><i></i>' . $sn . '</li>';
			$h .= '</ol>';
			$revs = array(); foreach ( array_reverse( $o['notes'] ) as $n ) foreach ( $n['audio'] as $u ) $revs[] = $u;
			foreach ( array_slice( $o['notes'], 0, 3 ) as $n ) { $t = trim( preg_replace( '#https?://\S+#', '', $n['text'] ) ); $h .= '<div class="note">' . $e( $t ? $t : 'Update' ) . '<small>' . $e( $n['date'] ) . '</small></div>'; }
			if ( $revs ) {
				$u = end( $revs ); $n = count( $revs );
				$h .= '<div class="rev" data-k="' . $k . '" data-url="' . $a( $u ) . '" data-order="' . $a( $o['num'] ) . '" data-rev="' . $n . '"><div class="rv-h"><span>Revision ' . $n . ' · tap the wave to jump, add notes at any moment</span><a href="' . esc_url( $u ) . '" download>Download</a></div>'
					. '<div class="rv-c"><button type="button" class="rv-play" aria-label="Play revision"><svg width="30" height="30" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" fill-rule="evenodd" d="M6.87 3.80L6.80 20.23L6.95 20.30L19.40 12.49L19.47 11.98L19.32 11.83L7.09 3.80ZM9.23 8.07L15.57 11.98L15.57 12.27L15.57 12.57L15.34 12.71L9.16 16.32L9.08 15.73L9.08 8.22Z"></path></svg></button><canvas aria-label="Waveform"></canvas><span class="rv-t">0:00</span></div>'
					. '<div class="cm"><input type="text" maxlength="200" placeholder="Note at this moment, e.g. bass a bit lower"><button type="button" class="btn g rv-add">Add</button></div><ul class="cms"></ul>'
					. '<div style="margin-top:10px;text-align:right"><button type="button" class="btn rv-send">Send notes to engineer</button></div></div>';
			} elseif ( ! $done ) {
				$h .= '<div class="empty">Your engineer will post the first version here. You will also get an email.</div>';
			}
			$h .= '</div>';
		}
	} else {
		$h .= '<div class="empty">No studio projects yet. Order mixing or mastering and follow every step here: received, in work, revision, delivered.</div><a class="btn" href="' . $S . '/studio-calculator/?s=mixing">Get a price in 1 minute</a>';
	}
	$h .= '</section>';

	/* downloads */
	$h .= '<section class="p" id="downloads">' . $head( 'My downloads', 'down' );
	if ( $d['downloads'] ) {
		foreach ( $d['downloads'] as $x ) {
			$meta = implode( ' · ', array_filter( array( '' !== $x['left'] ? 'Downloads left: ' . $x['left'] : '', $x['exp'] ? 'Expires: ' . $x['exp'] : '' ) ) );
			$h .= '<div class="row"><span class="t" style="width:48px;height:48px">' . ss_acc_ic( 'down', 22 ) . '</span><div class="nm"><b>' . $e( $x['name'] ) . '</b><small>' . $e( $meta ) . '</small></div><a class="btn g" href="' . esc_url( $x['url'] ) . '">' . ss_acc_ic( 'down', 16 ) . 'Download</a></div>';
		}
	} else {
		$h .= '<div class="empty">Templates, presets and packs you buy appear here, ready to download again anytime.</div><a class="btn g" href="' . $L . '/all-products/">Browse products</a>';
	}
	$h .= '</section>';

	/* orders */
	$h .= '<section class="p" id="purchases">' . $head( 'Purchases', 'bag' );
	if ( $d['orders'] ) {
		foreach ( array_slice( $d['orders'], 0, 5 ) as $o ) {
			$h .= '<a class="row" href="' . esc_url( $o['url'] ) . '"><span class="t" style="width:48px;height:48px">' . ss_acc_ic( 'bag', 22 ) . '</span><div class="nm"><b>Order #' . $e( $o['num'] ) . ' · ' . $e( $o['total'] ) . '</b><small>' . $e( $o['date'] . ' · ' . implode( ', ', $o['items'] ) ) . '</small></div><span class="badge' . ( false !== stripos( $o['status'], 'complet' ) ? '' : ' w' ) . '">' . $e( $o['status'] ) . '</span></a>';
		}
		$h .= '<a class="btn g" href="' . esc_url( ss_acc_myaccount_url( 'orders' ) ) . '">All purchases &amp; receipts</a>';
	} else {
		$h .= '<div class="empty">Everything you buy appears here, with a receipt for each payment.</div>';
	}
	$h .= '</section></div><div>';

	/* favourites (filled in the browser: they are saved on this device) */
	$h .= '<section class="p" id="favourites">' . $head( 'Favourites', 'fav' ) . '<div class="fav-list"><div class="empty">Tap the heart on any product to save it here.</div></div></section>';

	/* quick actions + account */
	$acts = array(
		array( $S . '/studio-calculator/?s=mixing', 'calc', 'Order mixing or mastering', 'Price in 1 minute' ),
		array( $L . '/free-demo-feedback/', 'head', 'Free feedback on your track', 'Pro notes from our engineer' ),
		array( $L . '/free/', 'free', 'Free packs', 'Templates and presets' ),
		array( '#chat', 'chat', 'Ask the team', 'We reply in minutes' ),
	);
	$h .= '<section class="p acts">' . $head( 'Quick actions', 'dash' );
	foreach ( $acts as $x ) $h .= '<a href="' . $x[0] . '"' . ( '#chat' === $x[0] ? ' data-chat="1"' : '' ) . '>' . ss_acc_ic( $x[1], 20 ) . '<span>' . $x[2] . '<small>' . $x[3] . '</small></span></a>';
	$h .= '</section>';
	$set = array(
		array( ss_acc_myaccount_url( 'edit-account' ), 'user', 'Account details', 'Name, email and password' ),
		array( ss_acc_myaccount_url( 'edit-address' ), 'home', 'Billing details', 'Name, country and company for receipts' ),
		array( ss_acc_myaccount_url( 'payment-methods' ), 'card', 'Payment methods', 'Saved cards' ),
		array( wc_logout_url(), 'out', 'Log out', '' ),
	);
	$h .= '<section class="p acts" id="settings">' . $head( 'Account', 'user' );
	foreach ( $set as $x ) $h .= '<a href="' . esc_url( $x[0] ) . '">' . ss_acc_ic( $x[1], 20 ) . '<span>' . $x[2] . ( $x[3] ? '<small>' . $x[3] . '</small>' : '' ) . '</span></a>';
	$h .= '</section></div></div></div>';
	return $h;
}

/* Digital store wording on account sub pages: billing details only, no shipping address */
add_filter( 'woocommerce_my_account_my_address_title', function () { return 'Billing details'; } );
add_filter( 'woocommerce_my_account_get_addresses', function ( $a ) { return isset( $a['billing'] ) ? array( 'billing' => 'Billing details' ) : $a; } );
