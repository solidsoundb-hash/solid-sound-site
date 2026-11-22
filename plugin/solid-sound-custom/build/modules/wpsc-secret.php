<?php
/**
 * WP Super Cache secret — new value, once (owner 22.11.2026: «да» to «сменить секрет WP Super Cache»).
 * The old secret ($cache_page_secret) was in an old copy of wp-cache-config.php pulled into the public repository (20.11).
 * Here a new random secret is written into wp-content/wp-cache-config.php through WP Super Cache's own function, once.
 * The value is never printed or logged; only the date goes to the option ss_wpsc_secret (done marker).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', function () {
	if ( get_option( 'ss_wpsc_secret' ) || get_transient( 'ss_wpsc_secret_try2' ) ) return;
	set_transient( 'ss_wpsc_secret_try2', 1, 10 * MINUTE_IN_SECONDS );
	$new = md5( wp_generate_password( 64, true, true ) . microtime( true ) . wp_rand() );
	$ok  = false;
	if ( function_exists( 'wp_cache_setting' ) ) {
		wp_cache_setting( 'cache_page_secret', $new );
		$ok = true;
	} elseif ( function_exists( 'wp_cache_replace_line' ) && ! empty( $GLOBALS['wp_cache_config_file'] ) ) {
		$ok = (bool) wp_cache_replace_line( '^ *\$cache_page_secret', "\$cache_page_secret = '" . $new . "';", $GLOBALS['wp_cache_config_file'] );
	} else {
		/* (22.11) on ordinary pages WP Super Cache's own helpers are not loaded → change the one line in the config file
		   ourselves: only `$cache_page_secret = '…';`, written to a temp file and moved into place, then read back to check */
		$f = ! empty( $GLOBALS['wp_cache_config_file'] ) ? $GLOBALS['wp_cache_config_file'] : WP_CONTENT_DIR . '/wp-cache-config.php';
		$c = ( is_readable( $f ) && is_writable( $f ) ) ? (string) file_get_contents( $f ) : '';
		if ( $c && 1 === preg_match_all( '/^\s*\$cache_page_secret\s*=\s*[\'"][^\'"]*[\'"]\s*;/m', $c ) ) {
			$n = preg_replace( '/^(\s*)\$cache_page_secret\s*=\s*[\'"][^\'"]*[\'"]\s*;/m', '$1$cache_page_secret = \'' . $new . '\';', $c, 1 );
			$t = $f . '.ss-tmp';
			if ( $n && strlen( $n ) > 100 && false !== file_put_contents( $t, $n, LOCK_EX ) && @rename( $t, $f ) ) {
				if ( function_exists( 'opcache_invalidate' ) ) @opcache_invalidate( $f, true );
				$ok = false !== strpos( (string) file_get_contents( $f ), $new );
			}
			if ( file_exists( $t ) ) @unlink( $t );
		}
	}
	if ( $ok ) { $GLOBALS['cache_page_secret'] = $new; update_option( 'ss_wpsc_secret', gmdate( 'c' ), false ); }
}, 99 );
