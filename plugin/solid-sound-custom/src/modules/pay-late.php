<?php
/**
 * (20.11.2026 owner: «давай попробуем 3») Express payment buttons (Google Pay / Apple Pay through WooPayments + Stripe) on the
 * PRODUCT page load right after the page has finished loading (owner 20.11: middle option «2»), or at the first touch / scroll —
 * not together with the page.
 * They pulled ~2.7 MB (Stripe, Google Pay, hCaptcha) on every product view, before anyone wanted to pay.
 * Same scripts, same order, same buttons: they appear a moment later. Cart and checkout are untouched.
 * Compare: add ?ss_pay_now=1 to a product address (old behaviour). Off for good: option ss_paylate_off = 1.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_paylate_on() {
	static $on = null;
	if ( null === $on ) $on = ! is_admin() && function_exists( 'is_product' ) && is_product() && ! get_option( 'ss_paylate_off' ) && empty( $_GET['ss_pay_now'] );
	return $on;
}
add_filter( 'script_loader_tag', function ( $tag, $handle, $src ) {
	if ( ! ss_paylate_on() ) return $tag;
	if ( ! preg_match( '#js\.stripe\.com|/woocommerce-payments/dist/(express-checkout|product-details|frontend-tracks)\.js#', (string) $src ) ) return $tag;
	$GLOBALS['ss_paylate'] = 1;
	/* keep the tag where it is (and its inline -extra / -before parts), but the browser does not run it yet */
	return preg_replace( '#<script\b([^>]*)\bsrc=(["\'])([^"\']+)\2([^>]*)>#i', '<script type="text/plain" data-ss-pay="$3"$1$4>', $tag, 1 );
}, 99, 3 );
add_action( 'wp_footer', function () {
	if ( empty( $GLOBALS['ss_paylate'] ) ) return; ?>
<script>(function(){var d=0,E=["pointerdown","keydown","scroll","touchstart","mousemove","wheel"];function go(){if(d)return;d=1;E.forEach(function(n){removeEventListener(n,go,true)});
var l=[].slice.call(document.querySelectorAll("script[data-ss-pay]"));(function next(i){if(i>=l.length)return;var o=l[i],s=document.createElement("script");if(o.id)s.id=o.id;s.src=o.getAttribute("data-ss-pay");s.onload=s.onerror=function(){next(i+1)};o.parentNode.replaceChild(s,o)})(0)} /* one after another: Stripe first, then the buttons */
E.forEach(function(n){addEventListener(n,go,{capture:true,passive:true})});setTimeout(go,8000);
/* (20.11 owner chose «2»: «средний вариант») no touch needed: right after the page has fully loaded, when the browser is idle */
function idle(){(window.requestIdleCallback||function(f){setTimeout(f,300)})(go,{timeout:1500})}if(document.readyState==="complete")idle();else addEventListener("load",idle);
/* the place of the buttons is kept while they load — nothing jumps; freed 3 s after they arrive */
var h=document.documentElement;h.classList.add("ss-pay-wait");addEventListener("load",function(){setTimeout(function(){h.classList.remove("ss-pay-wait")},6000)})})();</script>
<style>.ss-pay-wait #wcpay-express-checkout-wrapper{min-height:56px}</style>
<?php }, 99 );
