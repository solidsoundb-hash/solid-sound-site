<?php
/**
 * Bonus balance (owner 11.11.2026):
 * - every completed order earns 3% of what was paid for the items (all products, vendors' too); bonuses never expire;
 * - bonuses can pay up to 50% of the owner's products and studio services in the cart (not vendors' products);
 * - cancelled / refunded orders give spent bonuses back and take earned bonuses away.
 * Balance = user meta _ss_bonus (shop currency). Spending = a negative cart fee "Bonus".
 */
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'SS_BONUS_RATE', 0.03 );
define( 'SS_BONUS_MAX_SHARE', 0.5 );

function ss_bonus_get( $uid ) { return max( 0, round( (float) get_user_meta( $uid, '_ss_bonus', true ), 2 ) ); }
function ss_bonus_add( $uid, $amount ) {
	if ( ! $uid || ! $amount ) return;
	update_user_meta( $uid, '_ss_bonus', max( 0, round( ss_bonus_get( $uid ) + $amount, 2 ) ) );
}
function ss_bonus_is_owner_product( $pid ) {
	$p = wc_get_product( $pid );
	if ( $p && $p->get_parent_id() ) $pid = $p->get_parent_id();
	$uid = (int) get_post_field( 'post_author', $pid );
	return ! ( $uid && function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor( $uid ) );
}
function ss_bonus_money( $v ) { return trim( html_entity_decode( wp_strip_all_tags( wc_price( $v ) ), ENT_QUOTES, 'UTF-8' ) ); }

/* ---------- earning ---------- */
add_action( 'woocommerce_order_status_completed', function ( $oid ) {
	$o = wc_get_order( $oid );
	if ( ! $o || $o->get_meta( '_ss_bonus_earned' ) ) return;
	$uid = (int) $o->get_customer_id();
	if ( ! $uid ) return;
	$paid = 0;
	foreach ( $o->get_items() as $it ) $paid += (float) $it->get_total();
	$paid -= (float) $o->get_meta( '_ss_bonus_used' ); /* bonuses spent do not earn bonuses */
	$earn = round( max( 0, $paid ) * SS_BONUS_RATE, 2 );
	if ( $earn <= 0 ) return;
	ss_bonus_add( $uid, $earn );
	$o->update_meta_data( '_ss_bonus_earned', $earn );
	$o->save();
	$o->add_order_note( sprintf( 'Bonus: +%s added to the customer balance (3%%).', ss_bonus_money( $earn ) ) );
} );

/* ---------- cancelled / failed / refunded: give back what was spent, take back what was earned ---------- */
function ss_bonus_reverse( $oid ) {
	$o = wc_get_order( $oid );
	if ( ! $o ) return;
	$uid = (int) $o->get_customer_id();
	if ( ! $uid ) return;
	$used = (float) $o->get_meta( '_ss_bonus_used' );
	if ( $used > 0 && ! $o->get_meta( '_ss_bonus_returned' ) ) {
		ss_bonus_add( $uid, $used );
		$o->update_meta_data( '_ss_bonus_returned', $used );
		$o->add_order_note( sprintf( 'Bonus: %s returned to the customer balance.', ss_bonus_money( $used ) ) );
	}
	$earned = (float) $o->get_meta( '_ss_bonus_earned' );
	if ( $earned > 0 && ! $o->get_meta( '_ss_bonus_revoked' ) ) {
		ss_bonus_add( $uid, -$earned );
		$o->update_meta_data( '_ss_bonus_revoked', $earned );
		$o->add_order_note( sprintf( 'Bonus: %s earned for this order removed.', ss_bonus_money( $earned ) ) );
	}
	$o->save();
}
foreach ( array( 'cancelled', 'refunded' ) as $st ) /* not 'failed': a failed payment can be retried */ add_action( 'woocommerce_order_status_' . $st, 'ss_bonus_reverse' );

/* ---------- spending ---------- */
function ss_bonus_cart_eligible() {
	if ( ! WC()->cart ) return 0;
	$sum = 0;
	foreach ( WC()->cart->get_cart() as $ci ) {
		if ( ss_bonus_is_owner_product( $ci['product_id'] ) ) $sum += (float) $ci['line_total'];
	}
	return $sum;
}
function ss_bonus_cart_amount() {
	$uid = get_current_user_id();
	if ( ! $uid || ! WC()->session || ! WC()->session->get( 'ss_bonus_use' ) ) return 0;
	return round( min( ss_bonus_get( $uid ), ss_bonus_cart_eligible() * SS_BONUS_MAX_SHARE ), 2 );
}

/* toggle: ?ss_bonus=1 / 0 on cart or checkout */
add_action( 'wp_loaded', function () {
	if ( ! isset( $_GET['ss_bonus'] ) || ! is_user_logged_in() || ! function_exists( 'WC' ) || ! WC()->session ) return;
	WC()->session->set( 'ss_bonus_use', '1' === $_GET['ss_bonus'] ? 1 : 0 );
	wp_safe_redirect( remove_query_arg( 'ss_bonus' ) );
	exit;
} );

add_action( 'woocommerce_cart_calculate_fees', function ( $cart ) {
	if ( is_admin() && ! wp_doing_ajax() ) return;
	$a = ss_bonus_cart_amount();
	if ( $a > 0 ) $cart->add_fee( 'Bonus', -$a, false );
} );

/* order created -> take the bonus from the balance */
function ss_bonus_take( $order ) {
	if ( is_numeric( $order ) ) $order = wc_get_order( $order );
	if ( ! $order || $order->get_meta( '_ss_bonus_used' ) ) return;
	$used = 0;
	foreach ( $order->get_fees() as $f ) if ( 'Bonus' === $f->get_name() ) $used += -(float) $f->get_total();
	if ( $used <= 0 ) return;
	$uid = (int) $order->get_customer_id();
	if ( ! $uid ) return;
	ss_bonus_add( $uid, -$used );
	$order->update_meta_data( '_ss_bonus_used', round( $used, 2 ) );
	$order->save();
	$order->add_order_note( sprintf( 'Bonus: %s paid with the customer bonus balance.', ss_bonus_money( $used ) ) );
	if ( WC()->session ) WC()->session->set( 'ss_bonus_use', 0 );
}
add_action( 'woocommerce_checkout_order_processed', function ( $oid ) { ss_bonus_take( $oid ); }, 20 );
add_action( 'woocommerce_store_api_checkout_order_processed', 'ss_bonus_take', 20 );

/* ---------- where people see it ---------- */
function ss_bonus_box() {
	$uid = get_current_user_id();
	$bal = $uid ? ss_bonus_get( $uid ) : 0;
	$elig = ss_bonus_cart_eligible();
	$earn = WC()->cart ? round( max( 0, (float) WC()->cart->get_subtotal() - ss_bonus_cart_amount() ) * SS_BONUS_RATE, 2 ) : 0;
	$h = '<div class="ss-bonus">';
	if ( ! $uid ) {
		$h .= '<span>Earn <b>3% back</b> as bonus on every order. <a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '">Sign in</a></span>';
	} else {
		$h .= '<span>Your bonus: <b>' . esc_html( ss_bonus_money( $bal ) ) . '</b>' . ( $earn > 0 ? ' · this order earns <b>+' . esc_html( ss_bonus_money( $earn ) ) . '</b>' : '' ) . '</span>';
		if ( $bal > 0 && $elig > 0 ) {
			$on = WC()->session && WC()->session->get( 'ss_bonus_use' );
			$h .= $on
				? ' <a class="ss-bonus-b" href="' . esc_url( add_query_arg( 'ss_bonus', '0' ) ) . '">Don’t use bonus</a>'
				: ' <a class="ss-bonus-b on" href="' . esc_url( add_query_arg( 'ss_bonus', '1' ) ) . '">Use ' . esc_html( ss_bonus_money( min( $bal, $elig * SS_BONUS_MAX_SHARE ) ) ) . '</a>';
		} elseif ( $bal > 0 ) {
			$h .= ' <small>Bonuses pay for Solid Sound products and studio services.</small>';
		}
	}
	return $h . '</div>';
}
add_action( 'woocommerce_before_cart', function () { echo ss_bonus_box(); } );
add_action( 'woocommerce_before_checkout_form', function () { echo ss_bonus_box(); }, 5 );
add_action( 'wp_head', function () {
	if ( ! function_exists( 'is_cart' ) || ! ( is_cart() || is_checkout() || is_product() ) ) return;
	echo '<style id="ss-bonus-css">.ss-bonus{display:flex;flex-wrap:wrap;gap:8px 12px;align-items:center;margin:0 0 16px;padding:12px 14px;border-radius:12px;border:1px solid rgba(245,158,11,.35);background:rgba(245,158,11,.08);color:#E5E7EB;font-size:14px}'
		. '.ss-bonus b{color:#F59E0B}.ss-bonus a{color:#F59E0B!important}.ss-bonus small{color:#94A3B8}.ss-bonus-b{display:inline-block;padding:6px 12px;border-radius:999px;border:1px solid rgba(245,158,11,.55);text-decoration:none!important;font-weight:700}.ss-bonus-b.on{background:#F59E0B;color:#111!important}'
		. '.ss-earn{margin:-6px 0 12px;color:#94A3B8;font-size:13px}.ss-earn b{color:#F59E0B}</style>';
}, 60 );

/* product page: "+X bonus" under the buy button */
add_action( 'woocommerce_single_product_summary', function () {
	global $product;
	if ( ! $product ) return;
	$price = (float) wc_get_price_to_display( $product );
	if ( $price <= 0 ) return;
	echo '<div class="ss-earn">Earn <b>+' . esc_html( ss_bonus_money( round( $price * SS_BONUS_RATE, 2 ) ) ) . '</b> bonus with this purchase</div>';
}, 33 );

/* My account: balance under the greeting */
add_filter( 'ss_acc_sub_extra', function ( $s ) {
	$uid = get_current_user_id();
	return $uid ? $s . ' <span class="ssa-bonus">Bonus balance: <b>' . esc_html( ss_bonus_money( ss_bonus_get( $uid ) ) ) . '</b></span>' : $s;
} );
