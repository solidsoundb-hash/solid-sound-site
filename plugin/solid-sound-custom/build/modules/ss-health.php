<?php
/**
 * (21.11.2026 owner: «роботы: подмена файлов, база данных») Site health, computed ON the server once a day (WP cron):
 *  1. file integrity — WordPress core and every active wordpress.org plugin compared with the official checksums
 *     (changed / missing files, unknown PHP files in wp-admin and wp-includes = signs of a hack);
 *  2. PHP files inside uploads (must be none — a classic backdoor place);
 *  3. database health — size, biggest tables, autoloaded options (loaded on EVERY page), revisions, auto-drafts, trash,
 *     expired transients, orphan post meta, spam comments, old Action Scheduler rows, Woo sessions.
 * READ-ONLY: it never changes or deletes anything. Result: option ss_health (not autoloaded).
 * Read by the robot tools/sitebots/health.js as the test admin: /wp-admin/admin-ajax.php?action=ss_health (&run=1 = now).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', function () {
	if ( ! wp_next_scheduled( 'ss_health_daily' ) ) wp_schedule_event( time() + 600, 'daily', 'ss_health_daily' );
} );
add_action( 'ss_health_daily', 'ss_health_run' );

function ss_health_md5_ok( $file, $want ) {
	$h = @md5_file( $file );
	return $h && in_array( $h, (array) $want, true );
}

function ss_health_run() {
	@set_time_limit( 300 );
	global $wpdb, $wp_version;
	$t0 = microtime( true );
	$r = array( 'at' => gmdate( 'c' ), 'wp' => $wp_version, 'core' => array(), 'plugins' => array(), 'uploads_php' => array(), 'db' => array() );

	/* 1a. core */
	if ( ! function_exists( 'get_core_checksums' ) ) require_once ABSPATH . 'wp-admin/includes/update.php';
	$sums = get_core_checksums( $wp_version, 'en_US' );
	if ( is_array( $sums ) ) {
		$changed = $missing = array();
		foreach ( $sums as $f => $md5 ) {
			if ( 0 === strpos( $f, 'wp-content/' ) ) continue;
			$p = ABSPATH . $f;
			if ( ! file_exists( $p ) ) { $missing[] = $f; continue; }
			if ( ! ss_health_md5_ok( $p, $md5 ) ) $changed[] = $f;
		}
		$unknown = array();
		foreach ( array( 'wp-admin', 'wp-includes' ) as $dir ) {
			$it = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( ABSPATH . $dir, FilesystemIterator::SKIP_DOTS ) );
			foreach ( $it as $file ) {
				if ( 'php' !== strtolower( $file->getExtension() ) ) continue;
				$rel = ltrim( str_replace( '\\', '/', substr( $file->getPathname(), strlen( ABSPATH ) ) ), '/' );
				if ( ! isset( $sums[ $rel ] ) ) $unknown[] = $rel;
				if ( count( $unknown ) > 50 ) break 2;
			}
		}
		$r['core'] = array( 'checked' => count( $sums ), 'changed' => array_slice( $changed, 0, 50 ), 'missing' => array_slice( $missing, 0, 50 ), 'unknown_php' => $unknown );
	} else {
		$r['core'] = array( 'error' => 'no checksums from wordpress.org for ' . $wp_version );
	}

	/* 1b. active plugins from wordpress.org */
	if ( ! function_exists( 'get_plugins' ) ) require_once ABSPATH . 'wp-admin/includes/plugin.php';
	$all = get_plugins();
	foreach ( (array) get_option( 'active_plugins', array() ) as $pf ) {
		if ( ! isset( $all[ $pf ] ) || false === strpos( $pf, '/' ) ) continue;
		$slug = dirname( $pf ); $ver = $all[ $pf ]['Version'];
		if ( 'solid-sound-custom' === $slug ) continue; // ours: checked by the deploy
		$res = wp_remote_get( "https://downloads.wordpress.org/plugin-checksums/{$slug}/{$ver}.json", array( 'timeout' => 20 ) );
		if ( 200 !== wp_remote_retrieve_response_code( $res ) ) { $r['plugins'][ $slug ] = array( 'v' => $ver, 'skip' => 'not on wordpress.org (paid/private) — not checked' ); continue; }
		$j = json_decode( wp_remote_retrieve_body( $res ), true );
		$changed = $missing = array(); $n = 0;
		foreach ( (array) ( $j['files'] ?? array() ) as $f => $h ) {
			$n++;
			$p = WP_PLUGIN_DIR . '/' . $slug . '/' . $f;
			if ( ! file_exists( $p ) ) { if ( preg_match( '/\.php$/', $f ) ) $missing[] = $f; continue; }
			if ( preg_match( '/\.(php|js)$/', $f ) && ! ss_health_md5_ok( $p, $h['md5'] ?? '' ) ) $changed[] = $f;
		}
		$r['plugins'][ $slug ] = array( 'v' => $ver, 'checked' => $n, 'changed' => array_slice( $changed, 0, 20 ), 'missing' => array_slice( $missing, 0, 20 ) );
		if ( microtime( true ) - $t0 > 200 ) { $r['plugins']['…'] = array( 'skip' => 'time limit, the rest next day' ); break; }
	}

	/* 2. PHP inside uploads */
	$up = wp_get_upload_dir();
	if ( is_dir( $up['basedir'] ) ) {
		$it = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $up['basedir'], FilesystemIterator::SKIP_DOTS ) );
		foreach ( $it as $file ) {
			if ( preg_match( '/\.(php\d?|phtml|phar)$/i', $file->getFilename() ) && 'index.php' !== $file->getFilename() ) $r['uploads_php'][] = substr( $file->getPathname(), strlen( $up['basedir'] ) );
			if ( count( $r['uploads_php'] ) > 30 ) break;
		}
	}

	/* 3. database */
	$tables = $wpdb->get_results( $wpdb->prepare( 'SELECT table_name AS t, ROUND((data_length+index_length)/1048576,1) AS mb, table_rows AS n FROM information_schema.tables WHERE table_schema=%s ORDER BY (data_length+index_length) DESC', DB_NAME ), ARRAY_A );
	$db = array( 'total_mb' => round( array_sum( wp_list_pluck( (array) $tables, 'mb' ) ), 1 ), 'tables' => array_slice( (array) $tables, 0, 10 ), 'table_count' => count( (array) $tables ) );
	$db['autoload_kb'] = (int) round( $wpdb->get_var( "SELECT SUM(LENGTH(option_value)) FROM {$wpdb->options} WHERE autoload IN ('yes','on','auto-on','auto')" ) / 1024 );
	$db['autoload_top'] = $wpdb->get_results( "SELECT option_name AS name, ROUND(LENGTH(option_value)/1024) AS kb FROM {$wpdb->options} WHERE autoload IN ('yes','on','auto-on','auto') ORDER BY LENGTH(option_value) DESC LIMIT 10", ARRAY_A );
	$db['revisions'] = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type='revision'" );
	$db['auto_drafts'] = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status='auto-draft'" );
	$db['trash'] = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status='trash'" );
	$db['transients_expired'] = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name LIKE %s AND option_value < %d", $wpdb->esc_like( '_transient_timeout_' ) . '%', time() ) );
	$db['transients_all'] = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like( '_transient_' ) . '%' ) );
	$db['orphan_postmeta'] = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->postmeta} pm LEFT JOIN {$wpdb->posts} p ON p.ID=pm.post_id WHERE p.ID IS NULL" );
	$db['spam_comments'] = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_approved IN ('spam','trash')" );
	$as = $wpdb->prefix . 'actionscheduler_actions';
	if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $as ) ) === $as ) {
		$db['as_done'] = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$as} WHERE status IN ('complete','canceled')" );
		$db['as_failed'] = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$as} WHERE status='failed'" );
		$db['as_pending_late'] = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$as} WHERE status='pending' AND scheduled_date_gmt < %s", gmdate( 'Y-m-d H:i:s', time() - 3600 ) ) );
	}
	$ws = $wpdb->prefix . 'woocommerce_sessions';
	if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $ws ) ) === $ws ) $db['woo_sessions'] = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$ws}" );
	$cron = _get_cron_array(); $late = 0; foreach ( (array) $cron as $ts => $x ) if ( $ts < time() - 3600 ) $late += count( $x );
	$db['cron_late'] = $late;
	$r['db'] = $db;

	/* 4. shop: payment methods switched on, orders that failed this week, reviews (counts only — no customer data) */
	$shop = array();
	if ( function_exists( 'WC' ) && WC()->payment_gateways() ) {
		foreach ( WC()->payment_gateways()->payment_gateways() as $id => $g ) if ( 'yes' === $g->enabled ) $shop['gateways'][] = $id;
	}
	if ( function_exists( 'wc_get_orders' ) ) {
		$since = gmdate( 'Y-m-d', time() - 7 * DAY_IN_SECONDS );
		foreach ( array( 'completed', 'processing', 'failed', 'pending', 'cancelled' ) as $st ) $shop['orders_7d'][ $st ] = count( wc_get_orders( array( 'status' => $st, 'date_created' => '>=' . $since, 'limit' => 200, 'return' => 'ids' ) ) );
		$last = wc_get_orders( array( 'status' => array( 'completed', 'processing' ), 'limit' => 1, 'orderby' => 'date', 'order' => 'DESC' ) );
		$shop['last_paid_order'] = $last ? $last[0]->get_date_created()->date( 'Y-m-d' ) : null;
	}
	$shop['reviews_total'] = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_type='review' AND comment_approved='1'" );
	$shop['reviews_pending'] = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_type='review' AND comment_approved='0'" );
	$shop['reviews_7d'] = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_type='review' AND comment_date_gmt >= %s", gmdate( 'Y-m-d H:i:s', time() - 7 * DAY_IN_SECONDS ) ) );
	$shop['comments_pending'] = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_approved='0' AND comment_type IN ('comment','')" );
	$r['shop'] = $shop;
	$r['sec'] = round( microtime( true ) - $t0, 1 );
	update_option( 'ss_health', $r, false );
	return $r;
}

add_action( 'wp_ajax_ss_health', function () {
	if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'admins only', 403 );
	$r = ! empty( $_GET['run'] ) ? ss_health_run() : get_option( 'ss_health' );
	if ( ! $r ) $r = ss_health_run();
	wp_send_json_success( $r );
} );
