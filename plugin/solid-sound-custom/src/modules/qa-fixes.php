<?php
/**
 * Fixes from the night QA report (owner 23.11.2026: «исправь что можешь»).
 *  1) Customer account sub-pages (/my-account/orders/, /edit-account/…): canonical pointed to /my-account/ — now to the page
 *     itself; they are private pages, so also kept out of Google (noindex, follow).
 *  2) Services & Prices, «Examples · before / after»: the ▶ button turned into ⏸ only by its card's class; the button itself
 *     now also says what it does (aria-label Play ↔ Pause, aria-pressed) — screen readers and the QA robot see the change.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_qf_account_sub() {
	return function_exists( 'is_account_page' ) && is_account_page() && function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url();
}
function ss_qf_self_url() {
	$p = (string) wp_parse_url( (string) ( $_SERVER['REQUEST_URI'] ?? '/' ), PHP_URL_PATH );
	return set_url_scheme( untrailingslashit( home_url() ) . ( '' !== $p ? $p : '/' ), 'https' );
}
add_filter( 'get_canonical_url', function ( $u ) { return ss_qf_account_sub() ? ss_qf_self_url() : $u; }, 100 );
add_filter( 'wpseo_canonical', function ( $u ) { return ss_qf_account_sub() ? ss_qf_self_url() : $u; }, 100 );
add_filter( 'wp_robots', function ( $r ) { if ( ss_qf_account_sub() ) { $r['noindex'] = true; $r['follow'] = true; } return $r; } );

add_action( 'wp_footer', function () {
	if ( ! is_page( 1947 ) ) return; ?>
<script>(function(){function sync(c){var b=c.querySelector('.sab-p');if(!b)return;var on=c.classList.contains('on'),t=(b.getAttribute('aria-label')||'').replace(/^(Play|Pause)\s+/,'');b.setAttribute('aria-label',(on?'Pause ':'Play ')+t);b.setAttribute('aria-pressed',on?'true':'false')}
function go(){var p=document.getElementById('examples');if(!p)return false;new MutationObserver(function(ms){ms.forEach(function(m){if(m.target.classList&&m.target.classList.contains('sab'))sync(m.target)})}).observe(p,{subtree:true,attributes:true,attributeFilter:['class']});return true}
if(!go())document.addEventListener('DOMContentLoaded',go)})();</script>
<?php }, 100 );
