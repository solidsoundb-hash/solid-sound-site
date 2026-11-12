<?php
/**
 * License (owner 11.11.2026, idea from BeatStars):
 * 1) a short license line right under the buy button (wording = our existing FAQ / license page, nothing new promised);
 * 2) a personal license certificate per purchased product (opens in the browser, "Save as PDF" button),
 *    linked from My account -> Orders and from the order page.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_lic_url() { return home_url( '/license/' ); }

/* 1) under the buy button */
add_action( 'woocommerce_single_product_summary', function () {
	global $product;
	if ( ! $product ) return;
	$fmt = function_exists( 'ss_pd_format' ) ? ss_pd_format( $product->get_id() ) : 'templates';
	$what = 'music' === $fmt ? 'Royalty-free for videos, podcasts and business projects' : 'Royalty-free for your releases';
	echo '<div class="ss-lic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/></svg>'
		. '<span><b>' . esc_html( $what ) . '</b> · personal license certificate after purchase. '
		. '<a href="' . esc_url( ss_lic_url() ) . '" target="_blank" rel="noopener">License details</a></span></div>';
}, 32 );

add_action( 'wp_head', function () {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) return;
	echo '<style id="ss-lic-css">.ss-lic{display:flex;gap:8px;align-items:flex-start;margin:10px 0 14px;padding:10px 12px;border:1px solid rgba(52,211,153,.25);background:rgba(52,211,153,.06);border-radius:10px;color:#CBD5E1;font-size:13px;line-height:1.45}'
		. '.ss-lic svg{flex:none;color:#34D399;margin-top:1px}.ss-lic b{color:#fff;font-weight:700}.ss-lic a{color:#34D399!important;text-decoration:underline}</style>';
}, 60 );

/* 2) certificate */
function ss_lic_link( $order, $item_id ) {
	return add_query_arg( array( 'ss_license' => $order->get_id(), 'item' => (int) $item_id, 'k' => $order->get_order_key() ), home_url( '/' ) );
}
function ss_lic_order_ok( $order ) {
	return $order && in_array( $order->get_status(), array( 'completed', 'processing' ), true );
}

add_filter( 'woocommerce_my_account_my_orders_actions', function ( $actions, $order ) {
	if ( ! ss_lic_order_ok( $order ) ) return $actions;
	$items = $order->get_items();
	if ( 1 === count( $items ) ) {
		$actions['ss_license'] = array( 'url' => ss_lic_link( $order, key( $items ) ), 'name' => 'License' );
	} elseif ( $items ) {
		$actions['ss_license'] = array( 'url' => $order->get_view_order_url() . '#ss-licenses', 'name' => 'Licenses' );
	}
	return $actions;
}, 10, 2 );

add_action( 'woocommerce_order_details_after_order_table', function ( $order ) {
	if ( ! ss_lic_order_ok( $order ) ) return;
	echo '<section id="ss-licenses" style="margin:18px 0"><h2 style="font-size:20px">License certificates</h2><ul style="list-style:none;padding:0;margin:0">';
	foreach ( $order->get_items() as $iid => $it ) {
		echo '<li style="margin:6px 0"><a href="' . esc_url( ss_lic_link( $order, $iid ) ) . '" target="_blank" rel="noopener" style="color:#F59E0B">' . esc_html( $it->get_name() ) . ' — open license (PDF)</a></li>';
	}
	echo '</ul></section>';
} );

add_action( 'template_redirect', function () {
	if ( empty( $_GET['ss_license'] ) ) return;
	$order = wc_get_order( absint( $_GET['ss_license'] ) );
	$key   = isset( $_GET['k'] ) ? sanitize_text_field( wp_unslash( $_GET['k'] ) ) : '';
	$iid   = isset( $_GET['item'] ) ? absint( $_GET['item'] ) : 0;
	if ( ! ss_lic_order_ok( $order ) || ! hash_equals( (string) $order->get_order_key(), $key ) ) { wp_die( 'License not found.', 'License', array( 'response' => 404 ) ); }
	$item = $order->get_item( $iid );
	if ( ! $item ) { wp_die( 'License not found.', 'License', array( 'response' => 404 ) ); }
	$name  = trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() );
	if ( '' === $name ) $name = $order->get_billing_email();
	$date  = $order->get_date_paid() ? $order->get_date_paid() : $order->get_date_created();
	$num   = 'SS-' . $order->get_order_number() . '-' . $iid;
	$pid   = $item->get_product_id();
	$fmt   = function_exists( 'ss_pd_format' ) ? ss_pd_format( $pid ) : 'templates';
	$seller = 'Solid Sound';
	$uid = (int) get_post_field( 'post_author', $pid );
	if ( $uid && function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor( $uid ) && function_exists( 'wcfm_get_vendor_store_name' ) ) { $sn = wp_strip_all_tags( (string) wcfm_get_vendor_store_name( $uid ) ); if ( $sn ) $seller = $sn; }
	if ( 'music' === $fmt ) {
		$rights = array( 'Use in videos, podcasts, ads and business projects, online and offline.', 'Royalty-free: no further payments for this use.', 'Not allowed: reselling or sharing the files themselves, or claiming the music as your own composition.' );
	} else {
		$rights = array( 'Samples, presets, drum patterns, effects and processing are royalty-free for your own releases.', 'To release a track based on a template, change all the main melodies — then you can release it on labels, Spotify, YouTube and more.', 'Not allowed: reselling or sharing the project files, presets or samples themselves.' );
	}
	nocache_headers();
	header( 'Content-Type: text/html; charset=utf-8' );
	header( 'X-Robots-Tag: noindex, nofollow' );
	$e = function ( $s ) { return esc_html( $s ); };
	echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex"><title>License ' . $e( $num ) . ' — Solid Sound</title><style>'
		. 'body{margin:0;background:#eef0f4;font:15px/1.55 system-ui,-apple-system,Segoe UI,Roboto,sans-serif;color:#111}'
		. '.pg{max-width:760px;margin:24px auto;background:#fff;padding:44px 48px;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,.08)}'
		. 'h1{margin:0 0 4px;font-size:26px}.sub{color:#555;margin:0 0 26px}.k{color:#666;font-size:12px;text-transform:uppercase;letter-spacing:.06em}'
		. 'table{width:100%;border-collapse:collapse;margin:0 0 22px}td{padding:8px 0;border-bottom:1px solid #eee;vertical-align:top}td:first-child{width:38%;color:#555}'
		. 'ul{padding-left:20px}.ft{margin-top:26px;font-size:12px;color:#666}.bt{display:inline-block;margin:0 0 18px;padding:10px 18px;border-radius:10px;background:#F59E0B;color:#111;font-weight:700;border:0;cursor:pointer;font-size:15px}'
		. '@media print{body{background:#fff}.pg{box-shadow:none;margin:0;padding:0}.bt{display:none}}@media(max-width:600px){.pg{margin:0;border-radius:0;padding:28px 20px}}'
		. '</style></head><body><div class="pg"><button class="bt" onclick="window.print()">Save as PDF / Print</button>'
		. '<div class="k">Solid Sound · solid-sound.net</div><h1>License certificate</h1><p class="sub">This certifies that the licensee below bought a license for the product.</p><table>'
		. '<tr><td>License number</td><td><b>' . $e( $num ) . '</b></td></tr>'
		. '<tr><td>Licensee</td><td>' . $e( $name ) . '</td></tr>'
		. '<tr><td>Product</td><td>' . $e( $item->get_name() ) . '</td></tr>'
		. '<tr><td>Seller</td><td>' . $e( $seller ) . '</td></tr>'
		. '<tr><td>Order</td><td>#' . $e( $order->get_order_number() ) . '</td></tr>'
		. '<tr><td>Date</td><td>' . $e( $date ? $date->date_i18n( 'j F Y' ) : '' ) . '</td></tr>'
		. '</table>'
		. '<div class="k">What you can do</div><ul>';
	foreach ( $rights as $r ) echo '<li>' . $e( $r ) . '</li>';
	echo '</ul><p class="ft">Full terms: <a href="' . esc_url( ss_lic_url() ) . '">' . esc_html( ss_lic_url() ) . '</a>. The full License Agreement prevails over this summary.</p></div></body></html>';
	exit;
}, 1 );
