<?php
/**
 * Adaptive loading (owner 19.11.2026: «распредели часть нагрузки — пускай у пользователей загружается, при условии что
 * у них норм девайс и не глючит»).
 * A tiny inline script (~0.6 KB) looks at the visitor's device: memory, CPU cores, network, data saver.
 *  - Strong device + fast network → <html class="ss-strong"> and the browser starts loading a page of this site as soon as the
 *    visitor points at its link (hover ~0.2 s on a computer, finger down on a phone) — the next page opens almost at once.
 *    Chrome's Speculation Rules ("moderate"); other browsers simply ignore it. Never: cart, checkout, account, admin,
 *    log-out, add-to-cart, links with "?" (they may change something).
 *  - Weak device (phone with ≤4 GB, or frame rate too low after load) / slow network / data saver → <html class="ss-lite">: nothing is fetched ahead (CSS can use the class
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
var ph=matchMedia('(pointer:coarse)').matches,lite=c.saveData||m<=2||k<=2||(ph&&m<=4)||/(^|-)2g|3g/.test(t),strong=!lite&&m>=4&&k>=4&&t==='4g';h.classList.add(lite?'ss-lite':strong?'ss-strong':'ss-mid');
if(strong&&HTMLScriptElement.supports&&HTMLScriptElement.supports('speculationrules')){var s=document.createElement('script');s.type='speculationrules';s.textContent=<?php echo wp_json_encode( wp_json_encode( $rules, JSON_UNESCAPED_SLASHES ) ); ?>;document.head.appendChild(s)}
/* (20.11 owner, Samsung A31: «телефон слабый, реально тормозит, на айфоне норм») a phone that cannot keep the frame rate gets the light mode too: 40 frames after load, median over 28 ms */
if(ph&&!lite&&window.requestAnimationFrame)addEventListener('load',function(){var f=[],p=0;function s(n){if(p)f.push(n-p);p=n;if(f.length<40)requestAnimationFrame(s);else{f.sort(function(a,b){return a-b});if(f[20]>28){h.classList.remove('ss-strong','ss-mid');h.classList.add('ss-lite')}}}setTimeout(function(){requestAnimationFrame(s)},1500)})})();</script>
<style id="ss-lite-css">html.ss-lite *,html.ss-lite *:before,html.ss-lite *:after{-webkit-backdrop-filter:none!important;backdrop-filter:none!important;text-shadow:none!important}html.ss-lite .ssx,html.ss-lite .ssx .card,html.ss-lite .ssw-match,html.ss-lite .ss-why .w,html.ss-lite .ss-why2 .w{box-shadow:none!important}html.ss-lite .ssw-wall{transition:none!important}html.ss-lite .ssw-wall.stage{background:linear-gradient(180deg,#0e1a36,#0a0f1f)!important}html.ss-lite .ssx .vp{transition:none!important}html.ss-lite body.ss-wow .ssx.ss-auto .tasks button.on:after{animation:none!important;display:none!important}html.ss-lite #ssg{box-shadow:0 2px 8px rgba(0,0,0,.35)!important}@media(max-width:600px){#ssg .g-in{min-height:56px!important;padding:6px 12px!important;gap:8px!important}#ssg .g-logo{width:44px!important}#ssg .g-b,#ssg .g-burger{width:38px!important;height:38px!important;min-width:38px!important}}</style><?php /* (20.11 owner «шапка поуже, кнопки чуть меньше» — all pages on phones) */ ?>
	<?php
}, 2 );
