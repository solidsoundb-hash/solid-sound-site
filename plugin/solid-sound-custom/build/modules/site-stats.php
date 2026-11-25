<?php
/**
 * Solid Stats — our own tiny, private site counter (owner 21.11.2026: «аналитика — подключи что сможешь сам»).
 * Why: hosting logs are not reachable over FTP, the Supermetrics trial ended, GA4 is only in its own panel.
 * How: after the page has loaded (idle), one beacon per page view → daily totals in the option `ss_stats`:
 * sessions, page views, country (from the browser's time zone — no IP lookup), language, source (first page of a
 * session: referrer host or utm_source), top pages, phone / computer. Nothing personal is stored: no IP, no cookie,
 * no user agent; "session" = a flag in sessionStorage (gone when the tab closes). Admins and bots are not counted.
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

/* ---------- receive ---------- */
add_action( 'wp_ajax_nopriv_ss_hit', 'ss_stats_hit' );
add_action( 'wp_ajax_ss_hit', 'ss_stats_hit' );
function ss_stats_hit() {
	if ( get_option( 'ss_stats_off' ) || current_user_can( 'manage_options' ) ) wp_die( '', '', array( 'response' => 204 ) );
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) $_SERVER['REMOTE_ADDR'] : '';
	$rk = 'ss_hit_' . substr( md5( $ip . wp_salt() ), 0, 12 ); $n = (int) get_transient( $rk );
	if ( $n > 150 ) wp_die( '', '', array( 'response' => 204 ) ); /* one browser cannot flood the counter */
	set_transient( $rk, $n + 1, HOUR_IN_SECONDS );
	$s = function ( $k, $max = 80 ) { return isset( $_POST[ $k ] ) ? mb_substr( sanitize_text_field( wp_unslash( $_POST[ $k ] ) ), 0, $max ) : ''; };
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
	echo '<script>(function(){if(navigator.webdriver||!navigator.sendBeacon)return;function go(){try{var s=sessionStorage,n=s.getItem("ssS")?"0":"1";s.setItem("ssS","1");'
		. 'var q=new URLSearchParams(location.search),f=new FormData();f.append("action","ss_hit");f.append("p",location.pathname);f.append("n",n);'
		. 'if(n==="1"){f.append("r",document.referrer||"");f.append("u",q.get("utm_source")||"");f.append("tz",(Intl.DateTimeFormat().resolvedOptions().timeZone)||"");'
		. 'f.append("m",/Mobi|Android|iPhone/.test(navigator.userAgent)?"1":"0");f.append("lg",navigator.language||"")}navigator.sendBeacon("' . $u . '",f)}catch(e){}}'
		. 'var w=window.requestIdleCallback||function(c){setTimeout(c,1500)};if(document.readyState==="complete")w(go);else addEventListener("load",function(){w(go)})})();</script>';
}, 99 );

/* ---------- read: Tools → Site stats ---------- */
add_action( 'admin_menu', function () {
	add_management_page( 'Site stats', 'Site stats', 'manage_options', 'ss-stats', function () {
		$all = get_option( 'ss_stats', array() ); $all = is_array( $all ) ? $all : array(); krsort( $all );
		$days = array_slice( $all, 0, 30, true ); $sum = array( 'c' => array(), 'r' => array(), 'p' => array(), 'm' => array(), 'l' => array() ); $S = $V = 0;
		foreach ( $days as $d ) { $S += $d['s']; $V += $d['v']; foreach ( $sum as $k => $_ ) foreach ( (array) $d[ $k ] as $x => $n ) $sum[ $k ][ $x ] = ( isset( $sum[ $k ][ $x ] ) ? $sum[ $k ][ $x ] : 0 ) + $n; }
		echo '<div class="wrap"><h1>Site stats — last 30 days</h1><p>Our own private counter: no cookies, no IP addresses; admins and bots are not counted. Sessions: <b>' . (int) $S . '</b> · page views: <b>' . (int) $V . '</b></p>';
		echo '<table class="widefat striped" style="max-width:520px"><thead><tr><th>Day</th><th>Sessions</th><th>Views</th></tr></thead><tbody>';
		foreach ( $days as $k => $d ) echo '<tr><td>' . esc_html( $k ) . '</td><td>' . (int) $d['s'] . '</td><td>' . (int) $d['v'] . '</td></tr>';
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
			return array( 'tz' => 'UTC', 'currency' => function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() : '', 'days' => $all, 'orders' => $orders );
		},
	) );
} );
