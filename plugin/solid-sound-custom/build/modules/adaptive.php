<?php
/**
 * Adaptive loading (owner 19.11.2026: «распредели часть нагрузки — пускай у пользователей загружается, при условии что
 * у них норм девайс и не глючит»).
 * A tiny inline script (~0.6 KB) looks at the visitor's device: memory, CPU cores, network, data saver.
 *  - Strong device + fast network → <html class="ss-strong"> and the browser starts loading a page of this site as soon as the
 *    visitor points at its link (hover ~0.2 s on a computer, finger down on a phone) — the next page opens almost at once.
 *    Chrome's Speculation Rules ("moderate"); other browsers simply ignore it. Never: cart, checkout, account, admin,
 *    log-out, add-to-cart, links with "?" (they may change something).
 *  - Weak device / slow network / data saver → <html class="ss-lite">: nothing is fetched ahead (CSS can use the class
 *    to drop heavy effects).
 * Off: option ss_adaptive_off = 1.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_head', function () {
	if ( is_admin() || get_option( 'ss_adaptive_off' ) ) return;
	$rules = array( 'prefetch' => array( array(
		'where'     => array( 'and' => array(
			array( 'href_matches' => '/*' ),
			array( 'not' => array( 'href_matches' => array( '/wp-*', '/*cart*', '/*checkout*', '/*my-account*', '/*store-manager*', '/*logout*', '/*add-to-cart*', '/*client-dashboard*', '/*/feed/*', '/*.zip', '/*.mp3', '/*.wav' ) ) ),
			array( 'not' => array( 'selector_matches' => '[rel~=nofollow],[download],[target=_blank],.add_to_cart_button,[href*="?"],[href*="#"]' ) ),
		) ),
		'eagerness' => 'moderate',
	) ) );
	?>
<script id="ss-adaptive">(function(){var n=navigator,c=n.connection||{},m=n.deviceMemory||4,k=n.hardwareConcurrency||4,t=c.effectiveType||'4g',h=document.documentElement;
var lite=c.saveData||m<=2||k<=2||/(^|-)2g|3g/.test(t),strong=!lite&&m>=4&&k>=4&&t==='4g';h.classList.add(lite?'ss-lite':strong?'ss-strong':'ss-mid');
if(strong&&HTMLScriptElement.supports&&HTMLScriptElement.supports('speculationrules')){var s=document.createElement('script');s.type='speculationrules';s.textContent=<?php echo wp_json_encode( wp_json_encode( $rules, JSON_UNESCAPED_SLASHES ) ); ?>;document.head.appendChild(s)}})();</script>
	<?php
}, 2 );
