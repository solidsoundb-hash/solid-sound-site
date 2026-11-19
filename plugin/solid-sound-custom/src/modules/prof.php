<?php
/**
 * Speed probe (owner 19.11.2026: «максимальное быстродействие пока без кеша»).
 * The server spends ~1 s on every page before sending a byte; to see where, a tiny must-use plugin measures how long
 * WordPress, every plugin and every hook take. It does nothing for normal visitors: it works only when a URL has
 * ?ss_prof=1 and then writes the numbers to /wp-content/uploads/ss-prof/<page>.json (no visitor data inside).
 * The file is installed here once (must-use plugins load before all others, so they can time them).
 * Remove: delete wp-content/mu-plugins/ss-prof.php, or define SS_PROF_OFF.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

const SS_PROF_VER = 7;
function ss_prof_install() {
	if ( defined( 'SS_PROF_OFF' ) ) { @unlink( WPMU_PLUGIN_DIR . '/ss-prof.php' ); return; }
	if ( defined( 'SS_PROF_MU' ) && SS_PROF_MU >= SS_PROF_VER ) return;
	if ( get_transient( 'ss_prof_try' . SS_PROF_VER ) ) return;
	set_transient( 'ss_prof_try' . SS_PROF_VER, 1, HOUR_IN_SECONDS );
	if ( ! is_dir( WPMU_PLUGIN_DIR ) ) @mkdir( WPMU_PLUGIN_DIR, 0755, true );
	$code = <<<'PHP'
<?php
/* Solid Sound speed probe — installed by plugins/solid-sound-custom/src/modules/prof.php. Works only with ?ss_prof=1. */
define( 'SS_PROF_MU', 7 );
if ( isset( $_GET['ss_prof'] ) && '1' === $_GET['ss_prof'] && ! defined( 'WP_CLI' ) ) {
	$GLOBALS['ss_prof'] = array( 't0' => $_SERVER['REQUEST_TIME_FLOAT'], 'mu' => microtime( true ), 'plugins' => array(), 'phases' => array(), 'hooks' => array(), 'last' => microtime( true ), 'pl' => microtime( true ), 'cur' => 'core-boot' );
	add_action( 'plugin_loaded', function ( $f ) { $p = &$GLOBALS['ss_prof']; $n = microtime( true ); $p['plugins'][ basename( dirname( $f ) ) ?: basename( $f ) ] = round( ( $n - $p['pl'] ) * 1000, 1 ); $p['pl'] = $n; }, -99999 );
	foreach ( array( 'plugins_loaded', 'setup_theme', 'after_setup_theme', 'init', 'wp_loaded', 'parse_request', 'wp', 'template_redirect', 'wp_head', 'wp_footer', 'shutdown' ) as $h ) {
		add_action( $h, function () use ( $h ) { $GLOBALS['ss_prof']['phases'][ $h ] = round( ( microtime( true ) - $GLOBALS['ss_prof']['t0'] ) * 1000, 1 ); }, -99999 );
	}
	/* who asks the database: first plugin/theme folder in the call stack */
	add_filter( 'query', function ( $q ) { $c = 'core'; $at = ''; foreach ( debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 25 ) as $f ) { if ( ! empty( $f['file'] ) && preg_match( '#/(plugins|mu-plugins|themes)/([^/]+)/(.*)#', $f['file'], $m ) ) { $c = $m[2]; $at = $m[3] . ':' . $f['line']; break; } } if ( preg_match( "/^SELECT option_value FROM \\S+ WHERE option_name = '([^']+)'/", $q, $om ) ) $GLOBALS['ss_prof']['opt'][ $c ][] = $om[1]; $GLOBALS['ss_prof']['at'][ $c . ' ' . $at ] = ( isset( $GLOBALS['ss_prof']['at'][ $c . ' ' . $at ] ) ? $GLOBALS['ss_prof']['at'][ $c . ' ' . $at ] : 0 ) + 1; $GLOBALS['ss_prof']['q'][ $c ] = ( isset( $GLOBALS['ss_prof']['q'][ $c ] ) ? $GLOBALS['ss_prof']['q'][ $c ] : 0 ) + 1; $k = substr( preg_replace( array( '/\\s+/', "/'[^']*'/", '/\\b\\d+\\b/' ), array( ' ', "'?'", 'N' ), $q ), 0, 170 ); $GLOBALS['ss_prof']['qs'][ $c ][ $k ] = ( isset( $GLOBALS['ss_prof']['qs'][ $c ][ $k ] ) ? $GLOBALS['ss_prof']['qs'][ $c ][ $k ] : 0 ) + 1; return $q; } );
	add_action( 'http_api_debug', function ( $r, $ctx, $cls, $args, $url ) { $c = array(); foreach ( debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 40 ) as $f ) { if ( ! empty( $f['file'] ) && preg_match( '#/(plugins|mu-plugins|themes)/([^/]+)/(.*)#', $f['file'], $m ) ) $c[] = $m[2] . '/' . $m[3] . ':' . $f['line']; } $GLOBALS['ss_prof']['http'][] = array( 'url' => preg_replace( '/[?#].*/', '', $url ), 'ms' => isset( $r['http_response'] ) ? null : null, 'by' => array_slice( array_unique( $c ), 0, 6 ) ); }, 10, 5 );
	/* time between hook starts, charged to the hook that was running (rough, but shows the heavy ones) */
	add_action( 'all', function ( $tag ) { $p = &$GLOBALS['ss_prof']; $n = microtime( true ); $k = $p['cur']; $p['hooks'][ $k ] = ( isset( $p['hooks'][ $k ] ) ? $p['hooks'][ $k ] : 0 ) + ( $n - $p['last'] ) * 1000; $p['last'] = $n; $p['cur'] = $tag; } );
	add_action( 'shutdown', function () {
		$p = $GLOBALS['ss_prof']; global $wpdb;
		arsort( $p['hooks'] ); $p['hooks'] = array_map( function ( $v ) { return round( $v, 1 ); }, array_slice( $p['hooks'], 0, 60, true ) );
		arsort( $p['plugins'] );
		$o = function_exists( 'opcache_get_status' ) ? @opcache_get_status( false ) : false;
		$out = array( 'url' => $_SERVER['REQUEST_URI'], 'total_ms' => round( ( microtime( true ) - $p['t0'] ) * 1000, 1 ), 'before_wp_ms' => round( ( $p['mu'] - $p['t0'] ) * 1000, 1 ), 'queries' => $wpdb ? $wpdb->num_queries : 0,
			'mem_mb' => round( memory_get_peak_usage() / 1048576, 1 ), 'php' => PHP_VERSION, 'opcache' => $o ? array( 'on' => $o['opcache_enabled'], 'hit_rate' => round( $o['opcache_statistics']['opcache_hit_rate'], 1 ), 'scripts' => $o['opcache_statistics']['num_cached_scripts'], 'mem_free_mb' => round( $o['memory_usage']['free_memory'] / 1048576, 1 ), 'full' => $o['cache_full'] ) : 'off',
			'object_cache' => wp_using_ext_object_cache(), 'autoload_kb' => $wpdb ? round( (float) $wpdb->get_var( "SELECT SUM(LENGTH(option_value)) FROM {$wpdb->options} WHERE autoload IN ('yes','on','auto-on','auto')" ) / 1024 ) : 0,
			'opcache_ini' => array( 'loaded' => extension_loaded( 'Zend OPcache' ), 'enable' => ini_get( 'opcache.enable' ), 'memory_limit' => ini_get( 'memory_limit' ), 'sapi' => PHP_SAPI ), 'queries_by' => isset( $p['q'] ) ? ( arsort( $p['q'] ) ? $p['q'] : $p['q'] ) : array(), 'http' => isset( $p['http'] ) ? $p['http'] : array(), 'active_plugins' => (array) get_option( 'active_plugins' ), 'speed_db' => get_option( 'ss_speed_db_v1' ), 'single_options' => isset( $p['opt'] ) ? $p['opt'] : array(), 'query_at' => isset( $p['at'] ) ? ( arsort( $p['at'] ) ? array_slice( $p['at'], 0, 40, true ) : array() ) : array(), 'query_samples' => isset( $p['qs'] ) ? array_map( function ( $a ) { arsort( $a ); return array_slice( $a, 0, 8, true ); }, $p['qs'] ) : array(), 'autoload_top' => $wpdb ? $wpdb->get_results( "SELECT option_name n, ROUND(LENGTH(option_value)/1024) kb FROM {$wpdb->options} WHERE autoload IN ('yes','on','auto-on','auto') ORDER BY LENGTH(option_value) DESC LIMIT 25" ) : array(), 'phases' => $p['phases'], 'plugins' => $p['plugins'], 'hooks' => $p['hooks'] );
		$d = WP_CONTENT_DIR . '/uploads/ss-prof'; if ( ! is_dir( $d ) ) @mkdir( $d, 0755, true );
		@file_put_contents( $d . '/' . ( trim( preg_replace( '/[^a-z0-9]+/i', '_', strtok( $_SERVER['REQUEST_URI'], '?' ) ), '_' ) ?: 'root' ) . '.json', wp_json_encode( $out, JSON_PRETTY_PRINT ) );
	}, PHP_INT_MAX );
}
PHP;
	@file_put_contents( WPMU_PLUGIN_DIR . '/ss-prof.php', $code );
}
add_action( 'init', 'ss_prof_install', 99 );
