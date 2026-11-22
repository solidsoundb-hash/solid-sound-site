<?php
/**
 * Store currency: euro (owner 21.11.2026: «в евро, но для каждой страны своя валюта»; prices «пересчитать по курсу»).
 * STAGE 1 — once, by itself on the first request after the deploy:
 *  - every product / variation price (regular, sale, active) USD → EUR at the WooPayments rate of 21.11 (1 USD = 0.9115 EUR),
 *    rounded to whole euros ($26 → €24, $17 → €15, $10 → €9). The old USD values are kept in _ss_usd_* meta;
 *  - WooCommerce store currency USD → EUR (WooPayments Multi-Currency then uses EUR as the base, other currencies follow it).
 *  The −50 % Black Month sale is made by modules/sale.php from the regular price, so it follows by itself (€24 → €12).
 * Undo: option ss_eur_rollback = 1 → prices and currency go back to USD from the kept values (once), then the option is removed.
 * Log: option ss_eur_log.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

const SS_EUR_RATE = 0.9115; /* WooPayments cached rate, 21.11.2026 */
const SS_EUR_VER  = 1;

function ss_eur_round( $usd ) {
	if ( '' === $usd || null === $usd ) return '';
	$v = (float) $usd;
	if ( $v <= 0 ) return '0';
	return (string) max( 1, (int) round( $v * SS_EUR_RATE ) );
}

function ss_eur_ids() {
	return get_posts( array( 'post_type' => array( 'product', 'product_variation' ), 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids', 'suppress_filters' => true ) );
}

function ss_eur_migrate() {
	$log = array( 'at' => gmdate( 'c' ), 'rate' => SS_EUR_RATE, 'items' => array() );
	foreach ( ss_eur_ids() as $id ) {
		$row = array();
		foreach ( array( '_regular_price', '_sale_price', '_price' ) as $k ) {
			$old = get_post_meta( $id, $k, true );
			if ( '' === $old && ! metadata_exists( 'post', $id, $k ) ) continue;
			if ( ! metadata_exists( 'post', $id, '_ss_usd' . $k ) ) update_post_meta( $id, '_ss_usd' . $k, $old );
			$new = ss_eur_round( $old );
			update_post_meta( $id, $k, $new );
			$row[ $k ] = $old . ' → ' . $new;
		}
		if ( $row ) $log['items'][ $id ] = $row;
		if ( function_exists( 'wc_delete_product_transients' ) ) wc_delete_product_transients( $id );
	}
	update_option( 'woocommerce_currency', 'EUR' );
	update_option( 'ss_eur_log', $log, false );
	update_option( 'ss_eur_done', SS_EUR_VER );
}

function ss_eur_rollback() {
	$log = array( 'at' => gmdate( 'c' ), 'rollback' => 1, 'items' => array() );
	foreach ( ss_eur_ids() as $id ) {
		foreach ( array( '_regular_price', '_sale_price', '_price' ) as $k ) {
			if ( ! metadata_exists( 'post', $id, '_ss_usd' . $k ) ) continue;
			update_post_meta( $id, $k, get_post_meta( $id, '_ss_usd' . $k, true ) );
			delete_post_meta( $id, '_ss_usd' . $k );
			$log['items'][ $id ][] = $k;
		}
		if ( function_exists( 'wc_delete_product_transients' ) ) wc_delete_product_transients( $id );
	}
	update_option( 'woocommerce_currency', 'USD' );
	update_option( 'ss_eur_log', $log, false );
	delete_option( 'ss_eur_done' );
	delete_option( 'ss_eur_rollback' );
}

add_action( 'init', function () {
	if ( ! function_exists( 'WC' ) ) return;
	$done = (int) get_option( 'ss_eur_done' );
	$back = get_option( 'ss_eur_rollback' );
	if ( $done >= SS_EUR_VER && ! $back ) return;
	if ( get_transient( 'ss_eur_lock' ) ) return;
	set_transient( 'ss_eur_lock', 1, 120 );
	if ( $back ) { if ( $done ) ss_eur_rollback(); else delete_option( 'ss_eur_rollback' ); }
	elseif ( 'USD' === get_option( 'woocommerce_currency' ) ) ss_eur_migrate();
	else update_option( 'ss_eur_done', SS_EUR_VER );
	delete_transient( 'ss_eur_lock' );
	if ( function_exists( 'wp_cache_clear_cache' ) ) wp_cache_clear_cache();
}, 5 );

/* the base mixing price for "Add mixing +€…" (the same as Mixing Start in the studio calculator, so both say one price) */
function ss_mix_addon_price() {
	if ( function_exists( 'ss_sc_prices' ) ) { $p = ss_sc_prices(); if ( isset( $p['mixing']['tiers'][0][2] ) ) return (int) $p['mixing']['tiers'][0][2]; }
	return 69;
}

/* ── STAGE 2: a currency for each country + the switcher in the header (modules on WooPayments Multi-Currency) ──
 * WooPayments already converts every shop price (its own daily rates, 16 enabled currencies). Here:
 *  - window.SSCUR = the visitor's currency (code, symbol, rate from EUR) + the list for the header switcher (src/ss-global.js),
 *    window.ssMoney(eur) for the few prices written in our own code (home page cards, "Add mixing +€69");
 *  - first visit, no choice yet: the browser's time zone → the country's currency (no IP lookup, works with the page cache);
 *    a currency other than EUR → the page reloads once with ?currency=XXX (WooPayments switches the cart / checkout to it);
 *  - the choice lives in the cookie ss_cur (1 year); WP Super Cache keeps one copy of each page per ss_cur value;
 *    on uncached pages (cart, checkout) WooPayments is switched to the cookie's currency.
 * Off: option ss_cur_off = 1 (no auto choice, no switcher; WooPayments stays as it is). */
function ss_cur_mc() {
	if ( get_option( 'ss_cur_off' ) || ! function_exists( 'WC_Payments_Multi_Currency' ) ) return null;
	$mc = WC_Payments_Multi_Currency();
	return ( $mc && method_exists( $mc, 'get_enabled_currencies' ) && method_exists( $mc, 'get_selected_currency' ) ) ? $mc : null;
}
function ss_cur_set_cookie( $c ) {
	if ( headers_sent() || ( isset( $_COOKIE['ss_cur'] ) && $_COOKIE['ss_cur'] === $c ) ) return;
	setcookie( 'ss_cur', $c, array( 'expires' => time() + YEAR_IN_SECONDS, 'path' => '/', 'samesite' => 'Lax', 'secure' => is_ssl() ) );
	$_COOKIE['ss_cur'] = $c;
}
add_action( 'wp_loaded', function () {
	if ( ( is_admin() && ! wp_doing_ajax() ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) return;
	$mc = ss_cur_mc(); if ( ! $mc ) return;
	$en = (array) $mc->get_enabled_currencies();
	$q = isset( $_GET['currency'] ) ? strtoupper( preg_replace( '/[^A-Za-z]/', '', (string) wp_unslash( $_GET['currency'] ) ) ) : '';
	$c = $q ? $q : ( isset( $_COOKIE['ss_cur'] ) ? strtoupper( preg_replace( '/[^A-Za-z]/', '', (string) $_COOKIE['ss_cur'] ) ) : '' );
	if ( ! $c || ! isset( $en[ $c ] ) ) return;
	if ( $q ) ss_cur_set_cookie( $c );
	try { if ( $mc->get_selected_currency()->get_code() !== $c && method_exists( $mc, 'update_selected_currency' ) ) $mc->update_selected_currency( $c ); } catch ( \Throwable $e ) {}
}, 20 );
/* WP Super Cache: a separate cached copy for each ss_cur value (once) */
add_action( 'init', function () {
	if ( get_option( 'ss_cur_wpsc' ) || ! function_exists( 'wpsc_add_cookie' ) || ! ss_cur_mc() ) return;
	wpsc_add_cookie( 'ss_cur' );
	update_option( 'ss_cur_wpsc', 1 );
}, 30 );

add_action( 'wp_head', function () {
	if ( is_admin() ) return;
	$mc = ss_cur_mc();
	$sym = function ( $s ) { return html_entity_decode( (string) $s, ENT_QUOTES, 'UTF-8' ); };
	$cur = array( 'c' => 'EUR', 's' => '€', 'r' => 1, 'z' => 0, 'b' => 'EUR', 'l' => array() );
	if ( $mc ) {
		try {
			$sel = $mc->get_selected_currency();
			$cur['c'] = $sel->get_code(); $cur['s'] = $sym( $sel->get_symbol() ); $cur['r'] = (float) $sel->get_rate();
			$cur['z'] = method_exists( $sel, 'get_is_zero_decimal' ) && $sel->get_is_zero_decimal() ? 1 : 0;
			foreach ( (array) $mc->get_enabled_currencies() as $code => $o ) $cur['l'][] = array( $code, $sym( $o->get_symbol() ), method_exists( $o, 'get_name' ) ? html_entity_decode( (string) $o->get_name() ) : $code );
		} catch ( \Throwable $e ) {}
	}
	/* owner 22.11: «хочу в начале видеть основные валюты» — main ones first, then the rest A–Z */
	$main = array( 'USD', 'EUR', 'GBP', 'CAD', 'AUD', 'CHF' );
	usort( $cur['l'], function ( $a, $b ) use ( $main ) { $x = array_search( $a[0], $main, true ); $y = array_search( $b[0], $main, true ); $x = false === $x ? 99 : $x; $y = false === $y ? 99 : $y; return $x === $y ? strcmp( $a[0], $b[0] ) : $x - $y; } );
	$cur['b'] = function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() : 'EUR';
	$en = wp_list_pluck( $cur['l'], 0 );
	?>
<script>window.SSCUR=<?php echo wp_json_encode( $cur ); ?>;window.SSMIX=<?php echo (int) ss_mix_addon_price(); ?>;
window.ssMoney=function(v){var C=window.SSCUR||{},x=+v*(C.r||1);x=x>0?Math.ceil(x-1e-6):0;var s=C.s||'€';return /^(PLN|SEK|NOK|DKK|UAH)$/.test(C.c)?x+' '+s:s+x};
(function(){try{if(/(^|; )ss_cur=/.test(document.cookie)||navigator.webdriver||/bot|crawl|spider|slurp|lighthouse/i.test(navigator.userAgent))return;
var tz=(Intl.DateTimeFormat().resolvedOptions().timeZone)||'',E=<?php echo wp_json_encode( array_values( $en ) ); ?>,c='EUR',
M={'Europe/London':'GBP','Europe/Belfast':'GBP','Europe/Jersey':'GBP','Europe/Guernsey':'GBP','Europe/Isle_of_Man':'GBP','Europe/Zurich':'CHF','Europe/Vaduz':'CHF','Europe/Warsaw':'PLN','Europe/Kyiv':'UAH','Europe/Kiev':'UAH','Europe/Uzhgorod':'UAH','Europe/Zaporozhye':'UAH','Europe/Copenhagen':'DKK','Europe/Oslo':'NOK','Europe/Stockholm':'SEK','Asia/Kolkata':'INR','Asia/Calcutta':'INR','Asia/Tokyo':'JPY','Asia/Hong_Kong':'HKD','Africa/Johannesburg':'ZAR','Pacific/Auckland':'NZD','Pacific/Chatham':'NZD','America/Toronto':'CAD','America/Vancouver':'CAD','America/Montreal':'CAD','America/Edmonton':'CAD','America/Winnipeg':'CAD','America/Halifax':'CAD','America/Regina':'CAD','America/St_Johns':'CAD','America/Moncton':'CAD','America/Whitehorse':'CAD','America/Yellowknife':'CAD'};
if(M[tz])c=M[tz];else if(/^Australia\//.test(tz))c='AUD';else if(/^(America|Asia|Pacific)\//.test(tz))c='USD';
if(E.indexOf(c)<0)c=E.indexOf('EUR')<0?(window.SSCUR||{}).c:'EUR';
document.cookie='ss_cur='+c+';path=/;max-age=31536000;samesite=lax';
if(c&&c!==(window.SSCUR||{}).c){var u=new URL(location.href);u.searchParams.set('currency',c);location.replace(u.href)}}catch(e){}})();</script>
<?php
}, 1 );
