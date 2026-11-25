<?php
/**
 * «Cancel an order» (25.11.2026, «Юрист» docs/legal/dokumenty-saita-2026-11-25.md → «Задачи» → Тестировщик за Разработчика).
 * EU withdrawal button (Directive 2023/2673, from 19.06.2026): page /cancel-order/ with order number + e-mail + one button
 * «Withdraw from contract here» → e-mail confirming receipt (date and time) to the buyer, a note in the order, e-mail to the shop.
 * No new legal promises: the texts only repeat Terms of Sale / footer («Services: cancel within 14 days»).
 * Safety: the confirmation goes only to the e-mail of a real order with that number (nobody can use the form to mail others);
 * honeypot + 6 tries per hour per visitor. The order status is NOT changed — the shop decides.
 * Off: option ss_cancel_off = 1 (the page then shows «e-mail us»).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* the page itself: created once (slug cancel-order), content = shortcode */
add_action( 'init', function () {
	if ( get_option( 'ss_cancel_page' ) ) return;
	$p = get_page_by_path( 'cancel-order' );
	$id = $p ? $p->ID : wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Cancel an order', 'post_name' => 'cancel-order', 'post_content' => '[ss_cancel_order]' ) );
	if ( $id && ! is_wp_error( $id ) ) update_option( 'ss_cancel_page', (int) $id, false );
}, 30 );

function ss_cancel_mail_shop() { return get_option( 'woocommerce_email_from_address' ) ?: get_option( 'admin_email' ); }

add_shortcode( 'ss_cancel_order', function () {
	$terms = home_url( '/en/terms/' ); $refund = home_url( '/refund_returns/' ); $shop = ss_cancel_mail_shop();
	$css = '<style id="ss-cxo-css">.ss-cxo{max-width:560px;margin:0 auto 40px;color:#dfe3ee;font-size:15px;line-height:1.55}.ss-cxo p{margin:0 0 12px}.ss-cxo a{color:#F59E0B}'
		. '.ss-cxo form{margin-top:16px;padding:18px;border:1px solid rgba(255,255,255,.1);border-radius:14px;background:rgba(255,255,255,.03)}'
		. '.ss-cxo label{display:block;margin:0 0 12px;font-weight:600;font-size:14px}.ss-cxo input,.ss-cxo textarea{display:block;width:100%;box-sizing:border-box;margin-top:6px;padding:11px 12px;border-radius:10px;border:1px solid rgba(255,255,255,.15);background:#0B1220;color:#fff;font:inherit}'
		. '.ss-cxo textarea{min-height:80px}.ss-cxo .hp{position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden}'
		. '.ss-cxo button{width:100%;min-height:48px;margin-top:6px;border:0;border-radius:10px;background:#F59E0B;color:#111;font:inherit;font-weight:800;font-size:16px;cursor:pointer}'
		. '.ss-cxo .msg{padding:12px 14px;border-radius:10px;margin:0 0 14px}.ss-cxo .ok{background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.4)}.ss-cxo .err{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.4)}'
		. '.ss-cxo small{display:block;color:#94A3B8;font-size:13px;margin-top:10px}@media(max-width:600px){.ss-cxo{font-size:14px}.ss-cxo form{padding:14px}}</style>';
	$intro = '<p>To cancel (withdraw from) an order, enter your order number and the e-mail you used at checkout, then press the button. We confirm receipt by e-mail right away.</p>'
		. '<p><b>Studio services:</b> you can cancel within 14 days of ordering. <b>Downloads:</b> if at checkout you asked for immediate access, the right of withdrawal ends once the download is made available — but a faulty file is fixed, replaced or refunded within 30 days. Details: <a href="' . esc_url( $terms ) . '">Terms of Sale</a> and <a href="' . esc_url( $refund ) . '">Refund Policy</a>.</p>';
	if ( get_option( 'ss_cancel_off' ) || ! function_exists( 'wc_get_order' ) ) return $css . '<div class="ss-cxo">' . $intro . '<p>Please e-mail <a href="mailto:' . esc_attr( $shop ) . '">' . esc_html( $shop ) . '</a> with your order number.</p></div>';

	$msg = ''; $v = array( 'order' => '', 'email' => '', 'why' => '' ); $done = false;
	if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) && isset( $_POST['ss_cxo'] ) ) {
		$v['order'] = preg_replace( '/\D/', '', (string) wp_unslash( $_POST['order'] ?? '' ) );
		$v['email'] = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
		$v['why']   = mb_substr( sanitize_textarea_field( wp_unslash( $_POST['why'] ?? '' ) ), 0, 1000 );
		$ip = (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ); $rk = 'ss_cxo_' . substr( md5( $ip . wp_salt() ), 0, 12 ); $n = (int) get_transient( $rk );
		if ( ! empty( $_POST['website'] ) ) { $done = true; $msg = '<div class="msg ok">Thank you — your request has been received.</div>'; } /* bot: pretend */
		elseif ( $n >= 6 ) $msg = '<div class="msg err">Too many tries. Please wait an hour or e-mail <a href="mailto:' . esc_attr( $shop ) . '">' . esc_html( $shop ) . '</a>.</div>';
		else {
			set_transient( $rk, $n + 1, HOUR_IN_SECONDS );
			$o = $v['order'] ? wc_get_order( (int) $v['order'] ) : null;
			if ( ! $o || ! is_a( $o, 'WC_Order' ) || 'shop_order' !== $o->get_type() || ! $v['email'] || 0 !== strcasecmp( trim( $o->get_billing_email() ), $v['email'] ) ) {
				$msg = '<div class="msg err">We could not find an order with this number and e-mail. Please check both (the number is in your order e-mail), or e-mail <a href="mailto:' . esc_attr( $shop ) . '">' . esc_html( $shop ) . '</a>.</div>';
			} else {
				$when  = gmdate( 'Y-m-d H:i' ) . ' UTC';
				$items = array(); foreach ( $o->get_items() as $it ) $items[] = $it->get_name() . ' × ' . $it->get_quantity();
				$o->add_order_note( 'Withdrawal request (Cancel an order form) ' . $when . ( $v['why'] ? '. Reason: ' . $v['why'] : '' ) );
				$o->update_meta_data( '_ss_withdraw_req', $when ); $o->save();
				$num  = $o->get_order_number();
				$body = "Hello,\n\nWe have received your request to cancel (withdraw from) order #" . $num . ".\n\nReceived: " . $when . "\nOrder: #" . $num . "\nItems: " . implode( ', ', $items ) . ( $v['why'] ? "\nYour message: " . $v['why'] : '' )
					. "\n\nThis e-mail confirms that we received your request. We will contact you about your order.\n\nTerms of Sale: " . $terms . "\nRefund Policy: " . $refund . "\n\nSolid Sound\n" . home_url( '/' ) . "\n";
				wp_mail( $o->get_billing_email(), 'We received your cancellation request — order #' . $num, $body );
				wp_mail( $shop, '[Solid Sound] Cancellation request — order #' . $num, "Withdrawal request via /cancel-order/\n\nOrder: #" . $num . ' (' . $o->get_status() . ', ' . $o->get_formatted_order_total( '', false ) . ")\nItems: " . implode( ', ', $items ) . "\nReceived: " . $when . "\nReason: " . ( $v['why'] ?: '—' ) . "\n\nOpen: " . admin_url( 'post.php?post=' . $o->get_id() . '&action=edit' ) . "\nThe order status was not changed — please answer the customer.\n" );
				$done = true;
				$msg  = '<div class="msg ok"><b>Request received</b> (' . esc_html( $when ) . '). We sent a confirmation to ' . esc_html( $o->get_billing_email() ) . ' and will contact you about order #' . esc_html( $num ) . '.</div>';
			}
		}
	}
	$h = $css . '<div class="ss-cxo">' . $msg;
	if ( ! $done ) {
		$h .= $intro . '<form method="post" action="">'
			. '<input type="hidden" name="ss_cxo" value="1">'
			. '<label>Order number<input name="order" inputmode="numeric" autocomplete="off" required value="' . esc_attr( $v['order'] ) . '" placeholder="e.g. 12345"></label>'
			. '<label>E-mail used at checkout<input type="email" name="email" autocomplete="email" required value="' . esc_attr( $v['email'] ) . '"></label>'
			. '<label>Which items, or why (optional)<textarea name="why" maxlength="1000">' . esc_textarea( $v['why'] ) . '</textarea></label>'
			. '<label class="hp" aria-hidden="true">Website<input name="website" tabindex="-1" autocomplete="off"></label>'
			. '<button type="submit">Withdraw from contract here</button>'
			. '<small>Prefer e-mail? Write to <a href="mailto:' . esc_attr( $shop ) . '">' . esc_html( $shop ) . '</a> with your order number.</small></form>';
	}
	return $h . '</div>';
} );

/* not in caches / search: a form page */
add_action( 'template_redirect', function () {
	$id = (int) get_option( 'ss_cancel_page' );
	if ( $id && is_page( $id ) ) { if ( ! defined( 'DONOTCACHEPAGE' ) ) define( 'DONOTCACHEPAGE', true ); nocache_headers(); }
}, 1 );
add_filter( 'wp_robots', function ( $r ) { $id = (int) get_option( 'ss_cancel_page' ); if ( $id && is_page( $id ) ) $r['noindex'] = true; return $r; } );
