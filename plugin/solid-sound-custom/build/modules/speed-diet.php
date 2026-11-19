<?php
/**
 * Speed: plugin diet (owner 19.11.2026: «повырезать то, чем мы не пользуемся или что лишнее — со всем так»).
 * A tiny must-use plugin (loads before all plugins) leaves out, on ordinary page views (GET, not admin / AJAX / REST / cron),
 * plugins that page does not use:
 *  - wpForo (forum): only on forum pages (/forum…, /community…). Our own code checks function_exists( 'WPF' ) everywhere.
 *  - AI Engine: our AI runs on Cloudflare (modules/ai-*.php); nothing on the pages uses AI Engine.
 *  - Duplicate Post: an admin tool.
 * In wp-admin, AJAX, REST, cron, form posts and logins nothing changes. Off: define( 'SS_DIET_OFF', true ) in wp-config.php,
 * or delete wp-content/mu-plugins/ss-diet.php.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

const SS_DIET_VER = 1;
function ss_diet_install() {
	$f = WPMU_PLUGIN_DIR . '/ss-diet.php';
	if ( defined( 'SS_DIET_OFF' ) ) { if ( file_exists( $f ) ) @unlink( $f ); return; }
	if ( defined( 'SS_DIET_MU' ) && SS_DIET_MU >= SS_DIET_VER ) return;
	if ( get_transient( 'ss_diet_try' . SS_DIET_VER ) ) return;
	set_transient( 'ss_diet_try' . SS_DIET_VER, 1, HOUR_IN_SECONDS );
	if ( ! is_dir( WPMU_PLUGIN_DIR ) ) @mkdir( WPMU_PLUGIN_DIR, 0755, true );
	$code = <<<'PHP'
<?php
/* Solid Sound plugin diet — installed by plugins/solid-sound-custom/src/modules/speed-diet.php. Off: define SS_DIET_OFF. */
define( 'SS_DIET_MU', 1 );
if ( ! defined( 'SS_DIET_OFF' ) && ! defined( 'WP_CLI' ) && ! defined( 'DOING_CRON' ) && ! defined( 'WP_ADMIN' ) && ! defined( 'DOING_AJAX' ) && ! defined( 'XMLRPC_REQUEST' )
	&& isset( $_SERVER['REQUEST_METHOD'] ) && in_array( $_SERVER['REQUEST_METHOD'], array( 'GET', 'HEAD' ), true ) ) {
	$u = strtolower( (string) ( $_SERVER['REQUEST_URI'] ?? '' ) );
	if ( false === strpos( $u, '/wp-json' ) && false === strpos( $u, 'rest_route' ) && false === strpos( $u, 'wp-login' ) && false === strpos( $u, 'wc-api' ) && false === strpos( $u, 'wp-cron' ) ) {
		$GLOBALS['ss_diet_off'] = array( 'ai-engine/', 'duplicate-post/' );
		if ( ! preg_match( '#/(forum|community|participant|wpforo)#', $u ) ) $GLOBALS['ss_diet_off'][] = 'wpforo/';
		add_filter( 'option_active_plugins', function ( $list ) {
			if ( ! is_array( $list ) ) return $list;
			return array_values( array_filter( $list, function ( $p ) { foreach ( $GLOBALS['ss_diet_off'] as $o ) if ( 0 === strpos( $p, $o ) ) return false; return true; } ) );
		}, 1 );
		/* safety: if anything saves the plugin list during such a page view, the left-out plugins are kept in it */
		add_filter( 'pre_update_option_active_plugins', function ( $new ) {
			global $wpdb;
			$raw = maybe_unserialize( $wpdb->get_var( "SELECT option_value FROM {$wpdb->options} WHERE option_name = 'active_plugins'" ) );
			foreach ( (array) $raw as $p ) foreach ( $GLOBALS['ss_diet_off'] as $o ) if ( 0 === strpos( $p, $o ) && ! in_array( $p, (array) $new, true ) ) $new[] = $p;
			return $new;
		}, 1 );
	}
}
PHP;
	@file_put_contents( WPMU_PLUGIN_DIR . '/ss-diet.php', $code );
}
add_action( 'init', 'ss_diet_install', 99 );
