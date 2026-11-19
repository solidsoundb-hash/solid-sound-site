<?php
/**
 * Speed: database (owner 19.11.2026: «максимальное быстродействие пока без кеша»).
 * WordPress reads every "autoload" setting on every page (1.3 MB here, no object cache). Some of them belong to things
 * that are no longer used, so they are only switched to "load when asked" — nothing is deleted:
 *  - stm_ccb_*  — Cost Calculator Builder (the plugin is not active; our calculator is modules/studio-calc.php);
 *  - usof_options_<old name> — copies of the theme settings saved under mistyped theme names (the live one is usof_options_Impreza).
 * Also once: expired transients are removed (WordPress' own cleanup).
 * Done once; the list is kept in the option ss_speed_db_v1. Undo: define( 'SS_SPEED_DB_UNDO', true ) in wp-config.php for one page view.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', function () {
	global $wpdb;
	$done = get_option( 'ss_speed_db_v1' );
	if ( defined( 'SS_SPEED_DB_UNDO' ) && SS_SPEED_DB_UNDO && is_array( $done ) ) {
		foreach ( $done['names'] as $n ) $wpdb->update( $wpdb->options, array( 'autoload' => 'yes' ), array( 'option_name' => $n ) );
		delete_option( 'ss_speed_db_v1' ); wp_cache_delete( 'alloptions', 'options' );
		return;
	}
	if ( $done || defined( 'SS_SPEED_DB_UNDO' ) ) return;
	$auto = "autoload IN ('yes','on','auto-on','auto')";
	$names = array();
	if ( ! is_plugin_active_ss( 'cost-calculator-builder' ) ) {
		$names = array_merge( $names, (array) $wpdb->get_col( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE 'stm\\_ccb\\_%' AND $auto" ) );
	}
	$theme = defined( 'US_THEMENAME' ) ? US_THEMENAME : 'Impreza';
	foreach ( (array) $wpdb->get_col( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE 'usof\\_options\\_%' AND $auto" ) as $n ) {
		if ( 'usof_options_' . $theme !== $n && 'usof_options_Impreza' !== $n ) $names[] = $n;
	}
	$kb = 0;
	foreach ( $names as $n ) {
		$kb += (int) $wpdb->get_var( $wpdb->prepare( "SELECT LENGTH(option_value) FROM {$wpdb->options} WHERE option_name=%s", $n ) );
		$wpdb->update( $wpdb->options, array( 'autoload' => 'no' ), array( 'option_name' => $n ) );
	}
	if ( function_exists( 'delete_expired_transients' ) ) delete_expired_transients( true );
	wp_cache_delete( 'alloptions', 'options' );
	update_option( 'ss_speed_db_v1', array( 'date' => gmdate( 'c' ), 'names' => $names, 'kb' => round( $kb / 1024 ) ), false );
}, 5 );

function is_plugin_active_ss( $slug ) {
	foreach ( (array) get_option( 'active_plugins', array() ) as $p ) if ( 0 === strpos( $p, $slug . '/' ) ) return true;
	return false;
}
