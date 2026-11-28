<?php
/**
 * Solid Stats — our own tiny, private site counter (owner 21.11.2026: «аналитика — подключи что сможешь сам»).
 * Why: hosting logs are not reachable over FTP, the Supermetrics trial ended, GA4 is only in its own panel.
 * How: after the page has loaded (idle), one beacon per page view → daily totals in the option `ss_stats`:
 * sessions, page views, country (from the browser's time zone — no IP lookup), language, source (first page of a
 * session: referrer host or utm_source), top pages, phone / computer. Nothing personal is stored: no IP, no cookie,
 * no user agent; "session" = a flag in sessionStorage (gone when the tab closes). Admins and bots are not counted.
 * Funnel (25.11, Analyst): add_to_cart / checkout_start / pay_click / pay_error / order — once per session each, in day['e'].
 * Server side (25.11): day['e']['add'] / ['co'] — real add-to-cart and checkout form, see the bottom of this file.
 * Robots: time zone UTC, HeadlessChrome, UA «SolidSoundQA» / crawlers (server check, 27.11) are not counted; a plain GET «?add-to-cart=» link
 * is not an «add» (crawlers follow it; real clicks use AJAX, 27.11).
 * Human visits only (27.11, Analyst): a page view / session is counted after the first human sign — pointerdown, touchstart, keydown,
 * scroll > 50 px, or the tab visible with window focus for 8 s; then the rest of that session counts at once. Every page load is also
 * written to day['raw'] = { s, v } (crawlers that run JS included) to compare the volume. Countries / devices / pages = counted visits only.
 * Read: WP admin → Tools → Site stats, or the option `ss_stats` (kept 120 days). Off: option ss_stats_off = 1.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_stats_tz_country( $tz ) {
	static $m = array(
		'Europe/Paris' => 'France', 'Europe/Kyiv' => 'Ukraine', 'Europe/Kiev' => 'Ukraine', 'Europe/Berlin' => 'Germany', 'Europe/London' => 'United Kingdom',
		'Europe/Madrid' => 'Spain', 'Atlantic/Canary' => 'Spain', 'Europe/Rome' => 'Italy', 'Europe/Lisbon' => 'Portugal', 'Europe/Amsterdam' => 'Netherlands',
		'Europe/Brussels' => 'Belgium', 'Europe/Zurich' => 'Switzerland', 'Europe/Vienna' => 'Austria', 'Europe/Warsaw' => 'Poland', 'Europe/Prague' => 'Czechia',
		'Europe/Stockholm' => 'Sweden', 'Europe/Oslo' => 'Norway', 'Europe/Copenhagen' => 'Denmark', 'Europe/Helsinki' => 'Finland', 'Europe/Dublin' => 'Ireland',
		'Europe/Athens' => 'Greece', 'Europe/Bucharest' => 'Romania', 'Europe/Sofia' => 'Bulgaria', 'Europe/Budapest' => 'Hungary', 'Europe/Belgrade' => 'Serbia',
		'Europe/Zagreb' => 'Croatia', 'Europe/Ljubljana' => 'Slovenia', 'Europe/Bratislava' => 'Slovakia', 'Europe/Vilnius' => 'Lithuania', 'Europe/Riga' => 'Latvia',
		'Europe/Tallinn' => 'Estonia', 'Europe/Chisinau' => 'Moldova', 'Europe/Minsk' => 'Belarus', 'Europe/Moscow' => 'Russia', 'Europe/Istanbul' => 'Turkey',
		'Europe/Luxembourg' => 'Luxembourg', 'Europe/Monaco' => 'Monaco', 'Asia/Tbilisi' => 'Georgia', 'Asia/Yerevan' => 'Armenia', 'Asia/Almaty' => 'Kazakhstan',
		'Asia/Dubai' => 'UAE', 'Asia/Jerusalem' => 'Israel', 'Asia/Tel_Aviv' => 'Israel', 'Asia/Kolkata' => 'India', 'Asia/Calcutta' => 'India', 'Asia/Tokyo' => 'Japan',
		'Asia/Seoul' => 'South Korea', 'Asia/Shanghai' => 'China', 'Asia/Singapore' => 'Singapore', 'Asia/Bangkok' => 'Thailand', 'Asia/Jakarta' => 'Indonesia',
		'Asia/Manila' => 'Philippines', 'Asia/Ho_Chi_Minh' => 'Vietnam', 'Australia/Sydney' => 'Australia', 'Australia/Melbourne' => 'Australia', 'Australia/Brisbane' => 'Australia',
		'Australia/Perth' => 'Australia', 'Pacific/Auckland' => 'New Zealand', 'America/Sao_Paulo' => 'Brazil', 'America/Argentina/Buenos_Aires' => 'Argentina',
		'America/Buenos_Aires' => 'Argentina', 'America/Mexico_City' => 'Mexico', 'America/Bogota' => 'Colombia', 'America/Santiago' => 'Chile', 'America/Lima' => 'Peru',
		'America/Toronto' => 'Canada', 'America/Vancouver' => 'Canada', 'America/Montreal' => 'Canada', 'Africa/Johannesburg' => 'South Africa', 'Africa/Cairo' => 'Egypt',
		'Africa/Lagos' => 'Nigeria', 'Africa/Casablanca' => 'Morocco',
	);
	if ( isset( $m[ $tz ] ) ) return $m[ $tz ];
	if ( 0 === strpos( $tz, 'America/' ) && preg_match( '#^America/(New_York|Chicago|Denver|Los_Angeles|Phoenix|Detroit|Anchorage|Indiana|Kentucky|Boise)#', $tz ) ) return 'United States';
	if ( 0 === strpos( $tz, 'Pacific/Honolulu' ) ) return 'United States';
	$r = strtok( $tz, '/' );
	return $r ? 'Other: ' . $r : '?';
}

/* our QA robots (UA «SolidSoundQA»), crawlers, headless browsers — not counted (27.11: also for page views, was only for the funnel) */
function ss_stats_is_bot() {
	$ua = isset( $_SERVER['HTTP_USER_AGENT'] ) ? (string) $_SERVER['HTTP_USER_AGENT'] : '';
	return '' === $ua || (bool) preg_match( '~SolidSound|HeadlessChrome|Lighthouse|bot|crawl|spider|slurp|curl|wget|python|php~i', $ua );
}

/* ---------- receive ---------- */
add_action( 'wp_ajax_nopriv_ss_hit', 'ss_stats_hit' );
add_action( 'wp_ajax_ss_hit', 'ss_stats_hit' );
function ss_stats_hit() {
	if ( get_option( 'ss_stats_off' ) || current_user_can( 'manage_options' ) || ss_stats_is_bot() ) wp_die( '', '', array( 'response' => 204 ) );
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) $_SERVER['REMOTE_ADDR'] : '';
	$rk = 'ss_hit_' . substr( md5( $ip . wp_salt() ), 0, 12 ); $n = (int) get_transient( $rk );
	if ( $n > 300 ) wp_die( '', '', array( 'response' => 204 ) ); /* one browser cannot flood the counter */
	set_transient( $rk, $n + 1, HOUR_IN_SECONDS );
	$s = function ( $k, $max = 80 ) { return isset( $_POST[ $k ] ) ? mb_substr( sanitize_text_field( wp_unslash( $_POST[ $k ] ) ), 0, $max ) : ''; };
	/* (25.11, Analyst «воронка») robots: GitHub runners report the time zone UTC — not counted */
	if ( 'UTC' === $s( 'tz', 60 ) || 'Etc/UTC' === $s( 'tz', 60 ) ) wp_die( '', '', array( 'response' => 204 ) );
	$rw = $s( 'raw', 1 );
	if ( '' !== $rw ) { /* 27.11: raw page load before any human sign — volume only, nothing else */
		$day = gmdate( 'Y-m-d' ); $all = get_option( 'ss_stats', array() ); if ( ! is_array( $all ) ) $all = array();
		if ( ! isset( $all[ $day ] ) ) $all[ $day ] = array( 's' => 0, 'v' => 0, 'c' => array(), 'r' => array(), 'p' => array(), 'm' => array(), 'l' => array() );
		$r = isset( $all[ $day ]['raw'] ) && is_array( $all[ $day ]['raw'] ) ? $all[ $day ]['raw'] : array( 's' => 0, 'v' => 0 );
		$r['v']++; if ( '1' === $rw ) $r['s']++;
		$all[ $day ]['raw'] = $r;
		update_option( 'ss_stats', $all, false );
		wp_die( '', '', array( 'response' => 204 ) );
	}
	$ev = $s( 'e', 20 );
	if ( $ev ) { /* funnel step, once per session per step: add_to_cart → checkout_start → pay_click → pay_error / order */
		if ( ! in_array( $ev, array( 'add_to_cart', 'checkout_start', 'pay_click', 'pay_error', 'order' ), true ) ) wp_die( '', '', array( 'response' => 204 ) );
		$day = gmdate( 'Y-m-d' ); $all = get_option( 'ss_stats', array() ); if ( ! is_array( $all ) ) $all = array();
		if ( ! isset( $all[ $day ] ) ) $all[ $day ] = array( 's' => 0, 'v' => 0, 'c' => array(), 'r' => array(), 'p' => array(), 'm' => array(), 'l' => array() );
		if ( ! isset( $all[ $day ]['e'] ) || ! is_array( $all[ $day ]['e'] ) ) $all[ $day ]['e'] = array();
		$all[ $day ]['e'][ $ev ] = ( isset( $all[ $day ]['e'][ $ev ] ) ? $all[ $day ]['e'][ $ev ] : 0 ) + 1;
		update_option( 'ss_stats', $all, false );
		wp_die( '', '', array( 'response' => 204 ) );
	}
	$path = preg_replace( '~[?#].*$~', '', $s( 'p', 120 ) ); $path = $path ? $path : '/';
	$new  = '1' === $s( 'n', 1 );
	$day  = gmdate( 'Y-m-d' );
	$all  = get_option( 'ss_stats', array() ); if ( ! is_array( $all ) ) $all = array();
	$d = isset( $all[ $day ] ) ? $all[ $day ] : array( 's' => 0, 'v' => 0, 'c' => array(), 'r' => array(), 'p' => array(), 'm' => array(), 'l' => array() );
	$inc = function ( &$arr, $k, $cap ) { if ( ! isset( $arr[ $k ] ) && count( $arr ) >= $cap ) $k = '(other)'; $arr[ $k ] = ( isset( $arr[ $k ] ) ? $arr[ $k ] : 0 ) + 1; };
	$d['v']++;
	$inc( $d['p'], $path, 80 );
	if ( $new ) {
		$d['s']++;
		$inc( $d['c'], ss_stats_tz_country( $s( 'tz', 60 ) ), 60 );
		$ref = strtolower( $s( 'u', 40 ) );
		if ( ! $ref ) { $h = strtolower( (string) wp_parse_url( $s( 'r', 200 ), PHP_URL_HOST ) ); $h = preg_replace( '#^(www\.|m\.|l\.|lm\.)#', '', $h ); $ref = ( $h && false === strpos( $h, 'solid-sound.net' ) ) ? $h : '(direct)'; }
		$inc( $d['r'], $ref, 40 );
		$inc( $d['m'], '1' === $s( 'm', 1 ) ? 'phone' : 'computer', 3 );
		$inc( $d['l'], strtolower( substr( $s( 'lg', 10 ), 0, 2 ) ) ?: '?', 30 );
	}
	$all[ $day ] = $d;
	if ( count( $all ) > 120 ) { ksort( $all ); $all = array_slice( $all, -120, null, true ); }
	update_option( 'ss_stats', $all, false );
	wp_die( '', '', array( 'response' => 204 ) );
}

/* ---------- send: after load, when the browser is idle; real browsers only ---------- */
add_action( 'wp_footer', function () {
	if ( is_admin() || get_option( 'ss_stats_off' ) || current_user_can( 'manage_options' ) ) return;
	$u = esc_url( admin_url( 'admin-ajax.php' ) );
	echo '<script>(function(){if(navigator.webdriver||!navigator.sendBeacon||/HeadlessChrome/.test(navigator.userAgent))return;function go(){try{var s=sessionStorage,n=s.getItem("ssS")?"0":"1";s.setItem("ssS","1");'
		. 'var q=new URLSearchParams(location.search),f=new FormData();f.append("action","ss_hit");f.append("p",location.pathname);f.append("n",n);f.append("tz",(Intl.DateTimeFormat().resolvedOptions().timeZone)||"");'
		. 'if(n==="1"){f.append("r",document.referrer||"");f.append("u",q.get("utm_source")||"");'
		. 'f.append("m",/Mobi|Android|iPhone/.test(navigator.userAgent)?"1":"0");f.append("lg",navigator.language||"")}navigator.sendBeacon("' . $u . '",f)}catch(e){}}'
		/* 27.11: raw load now; the counted visit only after a human sign (see the header) */
		. 'function raw(){try{var s=sessionStorage,n=s.getItem("ssR")?"0":"1";s.setItem("ssR","1");var f=new FormData();f.append("action","ss_hit");f.append("raw",n);f.append("tz",(Intl.DateTimeFormat().resolvedOptions().timeZone)||"");navigator.sendBeacon("' . $u . '",f)}catch(e){}}'
		. 'var H=0,L=0,T,E=["pointerdown","touchstart","keydown"],D=document;function vis(){return D.visibilityState==="visible"&&D.hasFocus()}'
		. 'function off(){E.forEach(function(e){removeEventListener(e,hu,true)});removeEventListener("scroll",sc);removeEventListener("focus",ar);removeEventListener("blur",bl);D.removeEventListener("visibilitychange",ar);clearTimeout(T)}'
		. 'function hu(){if(H)return;H=1;off();try{sessionStorage.setItem("ssH","1")}catch(e){}if(L)go()}function sc(){if((window.scrollY||0)>50)hu()}function bl(){clearTimeout(T)}'
		. 'function ar(){clearTimeout(T);if(vis())T=setTimeout(function(){if(vis())hu()},8000)}'
		. 'try{if(sessionStorage.getItem("ssH"))H=1}catch(e){}if(!H){E.forEach(function(e){addEventListener(e,hu,{capture:true,passive:true})});addEventListener("scroll",sc,{passive:true});addEventListener("focus",ar);addEventListener("blur",bl);D.addEventListener("visibilitychange",ar);ar()}'
		. 'function st(){L=1;raw();if(H)go()}var w=window.requestIdleCallback||function(c){setTimeout(c,1500)};if(D.readyState==="complete")w(st);else addEventListener("load",function(){w(st)});'
		/* funnel events (25.11): once per session per step; robots (time zone UTC, HeadlessChrome) skipped */
		. 'var TZ=(Intl.DateTimeFormat().resolvedOptions().timeZone)||"";if(TZ==="UTC"||TZ==="Etc/UTC"||/HeadlessChrome/.test(navigator.userAgent))return;'
		. 'function ev(e){try{var k="ssE_"+e;if(sessionStorage.getItem(k))return;sessionStorage.setItem(k,"1");var f=new FormData();f.append("action","ss_hit");f.append("e",e);f.append("tz",TZ);navigator.sendBeacon("' . $u . '",f)}catch(x){}}window.ssEv=ev;'
		. 'var F=window.fetch;if(F)window.fetch=function(a,o){var r=F.apply(this,arguments);try{var u=typeof a==="string"?a:(a.url||"");if(/cart\/add-item|add_to_cart/.test(u))r.then(function(x){if(x.ok)ev("add_to_cart")},function(){})}catch(x){}return r};'
		. 'if(window.jQuery){jQuery(document.body).on("added_to_cart",function(){ev("add_to_cart")}).on("checkout_error",function(){ev("pay_error")})}'
		. 'var b=document.body.className;if(/woocommerce-order-received/.test(b))ev("order");else if(/woocommerce-checkout/.test(b))ev("checkout_start");'
		. 'document.addEventListener("click",function(x){var t=x.target.closest?x.target.closest("#place_order,.wc-block-components-checkout-place-order-button"):null;if(!t)return;ev("pay_click");'
		. 'var n=document.querySelector(".wc-block-checkout,form.checkout");if(n&&window.MutationObserver){var m=new MutationObserver(function(){if(n.querySelector(".wc-block-components-notice-banner.is-error,.woocommerce-error")){ev("pay_error");m.disconnect()}});m.observe(n,{childList:true,subtree:true});setTimeout(function(){m.disconnect()},20000)}},true)'
		. '})();</script>';
}, 99 );

/* ---------- read: Tools → Site stats ---------- */
add_action( 'admin_menu', function () {
	add_management_page( 'Site stats', 'Site stats', 'manage_options', 'ss-stats', function () {
		$all = get_option( 'ss_stats', array() ); $all = is_array( $all ) ? $all : array(); krsort( $all );
		$days = array_slice( $all, 0, 30, true ); $sum = array( 'c' => array(), 'r' => array(), 'p' => array(), 'm' => array(), 'l' => array() ); $S = $V = 0;
		foreach ( $days as $d ) { $S += $d['s']; $V += $d['v']; foreach ( $sum as $k => $_ ) foreach ( (array) $d[ $k ] as $x => $n ) $sum[ $k ][ $x ] = ( isset( $sum[ $k ][ $x ] ) ? $sum[ $k ][ $x ] : 0 ) + $n; }
		echo '<div class="wrap"><h1>Site stats — last 30 days</h1><p>Our own private counter: no cookies, no IP addresses; admins and bots are not counted. Sessions: <b>' . (int) $S . '</b> · page views: <b>' . (int) $V . '</b></p>';
		echo '<table class="widefat striped" style="max-width:520px"><thead><tr><th>Day</th><th>Sessions</th><th>Views</th><th>Raw loads (incl. crawlers)</th></tr></thead><tbody>';
		foreach ( $days as $k => $d ) echo '<tr><td>' . esc_html( $k ) . '</td><td>' . (int) $d['s'] . '</td><td>' . (int) $d['v'] . '</td><td>' . ( isset( $d['raw']['v'] ) ? (int) $d['raw']['s'] . ' / ' . (int) $d['raw']['v'] : '—' ) . '</td></tr>';
		echo '</tbody></table>';
		$fn = array( 'add' => 'Added to cart (server, real)', 'co' => 'Checkout form shown (server, real)', 'add_to_cart' => 'Added to cart', 'checkout_start' => 'Opened checkout', 'pay_click' => 'Clicked «Place order»', 'pay_error' => 'Payment error', 'order' => 'Order received page' );
		echo '<h2>Purchase funnel (sessions, each step once per session)</h2><table class="widefat striped" style="max-width:520px"><tbody><tr><td>Sessions</td><td>' . (int) $S . '</td></tr>';
		foreach ( $fn as $k => $t ) { $n = 0; foreach ( $days as $d ) $n += isset( $d['e'][ $k ] ) ? (int) $d['e'][ $k ] : 0; echo '<tr><td>' . esc_html( $t ) . '</td><td>' . $n . '</td></tr>'; }
		echo '</tbody></table>';
		foreach ( array( 'c' => 'Countries (by time zone)', 'r' => 'Sources', 'p' => 'Pages', 'm' => 'Devices', 'l' => 'Browser language' ) as $k => $t ) {
			arsort( $sum[ $k ] ); echo '<h2>' . esc_html( $t ) . '</h2><table class="widefat striped" style="max-width:520px"><tbody>';
			foreach ( array_slice( $sum[ $k ], 0, 25, true ) as $x => $n ) echo '<tr><td>' . esc_html( $x ) . '</td><td>' . (int) $n . '</td></tr>';
			echo '</tbody></table>';
		}
		echo '</div>';
	} );
} );

/* ---------- read for our robots: GET /wp-json/ss/v1/stats?days=N (admins only; Analyst robot «stats-daily», 25.11.2026) ----------
 * Returns the raw daily totals plus WooCommerce orders per day (count, paid count, revenue), so a robot can build the
 * daily funnel table: sessions → product pages → cart → checkout → orders. Nothing personal: no names, e-mails or IPs. */
add_action( 'rest_api_init', function () {
	register_rest_route( 'ss/v1', '/stats', array(
		'methods'             => 'GET',
		'permission_callback' => function () { return current_user_can( 'manage_options' ); },
		'callback'            => function ( $req ) {
			$n   = max( 1, min( 120, (int) $req->get_param( 'days' ) ?: 30 ) );
			$all = get_option( 'ss_stats', array() ); $all = is_array( $all ) ? $all : array(); ksort( $all );
			$all = array_slice( $all, -$n, null, true );
			$orders = array();
			if ( function_exists( 'wc_get_orders' ) ) {
				$ids = wc_get_orders( array( 'limit' => 500, 'return' => 'ids', 'type' => 'shop_order', 'status' => array_keys( wc_get_order_statuses() ), 'date_created' => '>=' . gmdate( 'Y-m-d', time() - $n * DAY_IN_SECONDS ) ) );
				foreach ( $ids as $id ) {
					$o = wc_get_order( $id ); if ( ! $o ) continue;
					$d = $o->get_date_created() ? gmdate( 'Y-m-d', $o->get_date_created()->getTimestamp() ) : '?';
					if ( ! isset( $orders[ $d ] ) ) $orders[ $d ] = array( 'n' => 0, 'paid' => 0, 'sum' => 0, 'free' => 0, 'st' => array() );
					$orders[ $d ]['n']++;
					$st = $o->get_status(); $orders[ $d ]['st'][ $st ] = ( isset( $orders[ $d ]['st'][ $st ] ) ? $orders[ $d ]['st'][ $st ] : 0 ) + 1;
					if ( $o->is_paid() ) { $orders[ $d ]['paid']++; $orders[ $d ]['sum'] += (float) $o->get_total(); if ( 0.0 === (float) $o->get_total() ) $orders[ $d ]['free']++; }
				}
			}
			$cut = gmdate( 'Y-m-d', time() - $n * DAY_IN_SECONDS );
			$since = function ( $o ) use ( $cut ) { $o = get_option( $o, array() ); return is_array( $o ) ? array_filter( $o, function ( $d ) use ( $cut ) { return $d >= $cut; }, ARRAY_FILTER_USE_KEY ) : array(); };
			/* listen = demo plays by people per day and product (listen-depth.php); miss = searches with 0 results (smart-search.php) */
			return array( 'tz' => 'UTC', 'currency' => function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() : '', 'days' => $all, 'orders' => $orders, 'listen' => $since( 'ss_ld_days' ), 'miss' => $since( 'ss_search_miss' ) );
		},
	) );
} );

/* ---------- server-side funnel (25.11, Analyst «19 корзин → 1 оформление»): real WooCommerce events, not page views ----------
 * add = a product really went into the cart (woocommerce_add_to_cart, also from the block cart / Store API),
 * co  = the checkout form was really shown (woocommerce_before_checkout_form). Once per shop session each, in day['e'].
 * Not counted: admins, our QA robots (UA «SolidSoundQA»), crawlers / headless browsers. Nothing personal is stored. */
function ss_stats_srv_event( $k ) {
	if ( get_option( 'ss_stats_off' ) || current_user_can( 'manage_options' ) ) return;
	if ( ss_stats_is_bot() ) return;
	/* 27.11 (Analyst: 90 «add» on 133 visits): crawlers follow the plain links «?add-to-cart=ID» of related products
	   (robots.txt forbids them, bad crawlers ignore it). A real click goes through AJAX / Store API or the product form (POST);
	   with JavaScript those buttons use AJAX (class ajax_add_to_cart), so a plain GET link is not counted. */
	if ( 'add' === $k && 'GET' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) && isset( $_GET['add-to-cart'] ) && ! wp_doing_ajax() && ! ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) return;
	$ses = ( function_exists( 'WC' ) && WC()->session ) ? WC()->session : null;
	if ( $ses ) { if ( $ses->get( 'ss_e_' . $k ) ) return; $ses->set( 'ss_e_' . $k, 1 ); }
	$day = gmdate( 'Y-m-d' ); $all = get_option( 'ss_stats', array() ); if ( ! is_array( $all ) ) $all = array();
	if ( ! isset( $all[ $day ] ) ) $all[ $day ] = array( 's' => 0, 'v' => 0, 'c' => array(), 'r' => array(), 'p' => array(), 'm' => array(), 'l' => array() );
	if ( ! isset( $all[ $day ]['e'] ) || ! is_array( $all[ $day ]['e'] ) ) $all[ $day ]['e'] = array();
	$all[ $day ]['e'][ $k ] = ( isset( $all[ $day ]['e'][ $k ] ) ? (int) $all[ $day ]['e'][ $k ] : 0 ) + 1;
	update_option( 'ss_stats', $all, false );
}
add_action( 'woocommerce_add_to_cart', function () { ss_stats_srv_event( 'add' ); }, 99 );
add_action( 'woocommerce_before_checkout_form', function () { ss_stats_srv_event( 'co' ); }, 1 );
