<?php
/**
 * Speed: settings in one query (owner 19.11.2026: «максимальное быстродействие пока без кеша»).
 * The probe showed ~230 tiny database queries per page: plugins (WooCustomizer, WooPayments, Facebook, Ultimate Addons…)
 * read settings that are not "autoload" one by one — one query each. A tiny must-use plugin (loads before all plugins)
 * reads all of them in ONE query and puts them in WordPress' own per-page memory, so get_option() finds them there.
 * Missing settings are remembered as missing (WordPress' "notoptions"), big ones (>16 KB) are left to load on their own.
 * The list learns itself: on 1 of 25 normal page views (and on every ?ss_prof=1 view) it notes which settings were
 * still asked one by one and adds them (option ss_prime_opts, max 900 names). Nothing is changed in the database
 * except that list. Off: define( 'SS_PRIME_OFF', true ) in wp-config.php, or delete wp-content/mu-plugins/ss-prime.php.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

const SS_PRIME_VER = 1;
function ss_prime_install() {
	$f = WPMU_PLUGIN_DIR . '/ss-prime.php';
	if ( defined( 'SS_PRIME_OFF' ) ) { if ( file_exists( $f ) ) @unlink( $f ); return; }
	if ( defined( 'SS_PRIME_MU' ) && SS_PRIME_MU >= SS_PRIME_VER ) return;
	if ( get_transient( 'ss_prime_try' . SS_PRIME_VER ) ) return;
	set_transient( 'ss_prime_try' . SS_PRIME_VER, 1, HOUR_IN_SECONDS );
	if ( ! is_dir( WPMU_PLUGIN_DIR ) ) @mkdir( WPMU_PLUGIN_DIR, 0755, true );
	$code = <<<'PHP'
<?php
/* Solid Sound: settings in one query — installed by plugins/solid-sound-custom/src/modules/speed-opts.php. */
define( 'SS_PRIME_MU', 1 );
if ( ! defined( 'SS_PRIME_OFF' ) && ! wp_installing() && ! defined( 'WP_CLI' ) ) {
	try { call_user_func( function () {
		global $wpdb;
		$list = get_option( 'ss_prime_opts' );
		if ( is_array( $list ) && $list ) {
			$all = wp_load_alloptions();
			$want = array();
			foreach ( $list as $n ) if ( is_string( $n ) && ! isset( $all[ $n ] ) && false === wp_cache_get( $n, 'options' ) ) $want[ $n ] = true;
			if ( $want ) {
				$in = implode( ',', array_map( function ( $n ) use ( $wpdb ) { return "'" . esc_sql( $n ) . "'"; }, array_keys( $want ) ) );
				$rows = $wpdb->get_results( "SELECT option_name n, IF(LENGTH(option_value) <= 16384, option_value, NULL) v FROM {$wpdb->options} WHERE option_name IN ($in)" );
				if ( is_array( $rows ) ) {
					foreach ( $rows as $r ) { unset( $want[ $r->n ] ); if ( null !== $r->v ) wp_cache_add( $r->n, $r->v, 'options' ); }
					$no = wp_cache_get( 'notoptions', 'options' ); if ( ! is_array( $no ) ) $no = array();
					foreach ( $want as $n => $t ) $no[ $n ] = true;
					wp_cache_set( 'notoptions', $no, 'options' );
				}
			}
		}
		$learn = ( isset( $_GET['ss_prof'] ) && '1' === $_GET['ss_prof'] ) || ( ! is_admin() && ! wp_doing_ajax() && isset( $_SERVER['REQUEST_METHOD'] ) && 'GET' === $_SERVER['REQUEST_METHOD'] && 1 === mt_rand( 1, 25 ) );
		if ( ! $learn ) return;
		$GLOBALS['ss_prime_new'] = array();
		add_filter( 'query', function ( $q ) {
			if ( preg_match( "/^SELECT option_value FROM \\S+ WHERE option_name = '([^']+)' LIMIT 1/", $q, $m ) && 0 !== strpos( $m[1], '_transient_' ) && 0 !== strpos( $m[1], '_site_transient_' ) ) $GLOBALS['ss_prime_new'][ stripslashes( $m[1] ) ] = true;
			return $q;
		} );
		add_action( 'shutdown', function () use ( $list ) {
			$old = is_array( $list ) ? $list : array();
			$new = array_diff( array_keys( $GLOBALS['ss_prime_new'] ), $old, array( 'ss_prime_opts' ) );
			if ( $new ) update_option( 'ss_prime_opts', array_slice( array_values( array_unique( array_merge( $old, $new ) ) ), -900 ), false );
		}, 1 );
	} ); } catch ( \Throwable $e ) {}
}
PHP;
	@file_put_contents( WPMU_PLUGIN_DIR . '/ss-prime.php', $code );
}
add_action( 'init', 'ss_prime_install', 99 );
