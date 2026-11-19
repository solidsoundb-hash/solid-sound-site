<?php
/**
 * (19.11.2026 owner: «чтобы лишних файлов никаких не оставалось, не захламлять хостинг»)
 * Add Product uploads the MP3 preview as soon as the creator drops it (and again when they move the preview
 * «earlier / later» or leave the form). Once a day we delete such leftovers — conservatively:
 *  - only AUDIO attachments (images, ZIPs and everything else are never touched);
 *  - uploaded by a creator account (not by an admin / shop manager);
 *  - not attached to any post, older than 2 days;
 *  - the file name is not mentioned anywhere in posts or post meta (products keep the preview as [audio mp3="…"]).
 * At most 40 files a day. Log: option ss_janitor_log (last 20 runs). Off: option ss_janitor_off = 1.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', function () {
	if ( ! wp_next_scheduled( 'ss_media_janitor' ) ) wp_schedule_event( time() + DAY_IN_SECONDS, 'daily', 'ss_media_janitor' ) /* first run in 24 h: time to check the dry run */;
} );

add_action( 'ss_media_janitor', 'ss_media_janitor_run' );
function ss_media_janitor_run( $dry = false ) {
	if ( get_option( 'ss_janitor_off' ) ) return array();
	global $wpdb;
	$ids = $wpdb->get_col( $wpdb->prepare(
		"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_parent = 0 AND post_mime_type LIKE %s AND post_date_gmt < %s ORDER BY ID ASC LIMIT 200",
		'audio/%', gmdate( 'Y-m-d H:i:s', time() - 2 * DAY_IN_SECONDS )
	) );
	$gone = array();
	foreach ( $ids as $id ) {
		if ( count( $gone ) >= 40 ) break;
		$author = (int) get_post_field( 'post_author', $id );
		if ( ! $author || user_can( $author, 'manage_woocommerce' ) || user_can( $author, 'edit_others_posts' ) ) continue;
		$file = get_post_meta( $id, '_wp_attached_file', true );
		if ( ! $file ) continue;
		$base = basename( $file );
		$like = '%' . $wpdb->esc_like( $base ) . '%';
		$used = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE ID <> %d AND post_type NOT IN ('revision','attachment') AND post_content LIKE %s", $id, $like ) )
			+ (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE post_id <> %d AND meta_key <> '_wp_attached_file' AND meta_value LIKE %s", $id, $like ) )
			+ (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_value = %s AND meta_key NOT IN ('_wp_attached_file','_wp_attachment_metadata','_edit_lock')", (string) $id ) );
		if ( $used ) continue;
		if ( ! $dry ) wp_delete_attachment( $id, true );
		$gone[] = $base;
	}
	if ( ! $dry ) {
		$log = (array) get_option( 'ss_janitor_log', array() );
		array_unshift( $log, array( gmdate( 'Y-m-d H:i' ), count( $gone ), array_slice( $gone, 0, 10 ) ) );
		update_option( 'ss_janitor_log', array_slice( $log, 0, 20 ), false );
	}
	return $gone;
}

/* WooCommerce → Solid Sound AI: what was cleaned, and a dry run */
add_action( 'ss_ai_admin_page_after', function () {
	if ( ! current_user_can( 'manage_woocommerce' ) ) return;
	echo '<hr><h2>Leftover files (creator uploads)</h2>';
	if ( isset( $_GET['ss_janitor_dry'] ) ) { $g = ss_media_janitor_run( true ); echo '<p>Would delete now: <b>' . count( $g ) . '</b> ' . esc_html( implode( ', ', array_slice( $g, 0, 15 ) ) ) . '</p>'; }
	$log = (array) get_option( 'ss_janitor_log', array() );
	echo '<p>Once a day we delete MP3 previews that creators uploaded but never used (older than 2 days, not in any product). Images and ZIPs are never touched.</p>';
	echo '<p><a class="button" href="' . esc_url( add_query_arg( array( 'page' => 'ss-ai', 'ss_janitor_dry' => 1 ), admin_url( 'admin.php' ) ) ) . '">Show what would be deleted</a></p>';
	if ( $log ) { echo '<ul>'; foreach ( array_slice( $log, 0, 5 ) as $l ) echo '<li>' . esc_html( $l[0] . ' — ' . $l[1] . ' deleted' . ( $l[2] ? ': ' . implode( ', ', $l[2] ) : '' ) ) . '</li>'; echo '</ul>'; }
} );
