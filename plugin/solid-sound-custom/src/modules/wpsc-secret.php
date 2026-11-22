<?php
/**
 * WP Super Cache secret — new value, once (owner 22.11.2026: «да» to «сменить секрет WP Super Cache»).
 * The old secret ($cache_page_secret) was in an old copy of wp-cache-config.php pulled into the public repository (20.11).
 * Here a new random secret is written into wp-content/wp-cache-config.php through WP Super Cache's own function, once.
 * The value is never printed or logged; only the date goes to the option ss_wpsc_secret (done marker).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', function () {
	if ( get_option( 'ss_wpsc_secret' ) || get_transient( 'ss_wpsc_secret_try' ) ) return;
	set_transient( 'ss_wpsc_secret_try', 1, HOUR_IN_SECONDS );
	$new = md5( wp_generate_password( 64, true, true ) . microtime( true ) . wp_rand() );
	$ok  = false;
	if ( function_exists( 'wp_cache_setting' ) ) {
		wp_cache_setting( 'cache_page_secret', $new );
		$ok = true;
	} elseif ( function_exists( 'wp_cache_replace_line' ) && ! empty( $GLOBALS['wp_cache_config_file'] ) ) {
		$ok = (bool) wp_cache_replace_line( '^ *\$cache_page_secret', "\$cache_page_secret = '" . $new . "';", $GLOBALS['wp_cache_config_file'] );
	}
	if ( $ok ) { $GLOBALS['cache_page_secret'] = $new; update_option( 'ss_wpsc_secret', gmdate( 'c' ), false ); }
}, 99 );
