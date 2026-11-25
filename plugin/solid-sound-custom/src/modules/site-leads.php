<?php
/**
 * Requests kept on the site (25.11.2026, idea of «Поддержка» → «Задачи»): until now studio orders and form messages
 * lived only in the mailbox. Every request e-mail the site sends to the shop is now also saved in the admin:
 * «Requests» (date, form, name, e-mail, text) — admins only. The e-mails go out exactly as before.
 * How: a wp_mail filter that only reads (returns the mail unchanged). A mail counts as a request when it goes to the shop
 * AND either has the visitor's Reply-To (studio calculator, mix/mastering order, album quote, get heard, exclusive licence,
 * questions, free feedback, any other form plugin) or is a known request subject (Cancel an order).
 * System mails (price changes, copy alerts, product reviews) have no visitor Reply-To and are not stored.
 * Robots: GET /wp-json/ss/v1/leads?days=N (admins) — form, date, test flag only; &full=1 adds name, e-mail, text
 * (personal data: never save it into the public repository).
 * Kept 365 days, then deleted. Off: option ss_leads_off = 1.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', function () {
	register_post_type( 'ss_lead', array(
		'labels'          => array( 'name' => 'Requests', 'singular_name' => 'Request', 'menu_name' => 'Requests', 'all_items' => 'All requests', 'edit_item' => 'Request', 'search_items' => 'Search requests', 'not_found' => 'No requests yet' ),
		'public'          => false, 'publicly_queryable' => false, 'exclude_from_search' => true, 'show_in_rest' => false,
		'show_ui'         => true, 'show_in_menu' => true, 'menu_position' => 56, 'menu_icon' => 'dashicons-email-alt',
		'supports'        => array( 'title' ),
		'capability_type' => 'post', 'map_meta_cap' => false,
		'capabilities'    => array(
			'edit_post' => 'manage_options', 'read_post' => 'manage_options', 'delete_post' => 'manage_options',
			'edit_posts' => 'manage_options', 'edit_others_posts' => 'manage_options', 'delete_posts' => 'manage_options',
			'publish_posts' => 'manage_options', 'read_private_posts' => 'manage_options', 'create_posts' => 'do_not_allow',
			'delete_others_posts' => 'manage_options', 'delete_private_posts' => 'manage_options', 'edit_private_posts' => 'manage_options',
		),
	) );
} );

function ss_leads_shop_addrs() {
	$a = array( get_option( 'admin_email' ), get_option( 'woocommerce_email_from_address' ) );
	if ( function_exists( 'ss_reply_email' ) ) $a[] = ss_reply_email();
	return array_filter( array_unique( array_map( 'strtolower', array_map( 'trim', $a ) ) ) );
}

/* which form: by the subject the handler already uses */
function ss_leads_form( $subj, $only_known = false ) {
	$map = array(
		'~^Studio request~i' => 'Studio calculator', '~^Mix/Mastering order~i' => 'Mix/Mastering order', '~^Album Mastering Quote~i' => 'Album mastering quote',
		'~^New set:~i' => 'Get your set heard', '~^Exclusive license request~i' => 'Exclusive licence', '~Question:~i' => 'Question (contact)',
		'~Free feedback request~i' => 'Free feedback', '~\] Chat:~i' => 'Chat', '~Cancellation request~i' => 'Cancel an order',
	);
	foreach ( $map as $re => $name ) if ( preg_match( $re, $subj ) ) return $name;
	if ( $only_known ) return '';
	$s = trim( preg_replace( '~^\[[^\]]*\]\s*~', '', $subj ) );
	return mb_substr( trim( preg_split( '~[:—–]~u', $s )[0] ), 0, 60 ) ?: 'Form';
}

add_filter( 'wp_mail', function ( $m ) {
	try {
		if ( get_option( 'ss_leads_off' ) || ! is_array( $m ) || ! post_type_exists( 'ss_lead' ) ) return $m;
		$to = $m['to'] ?? ''; $to = is_array( $to ) ? implode( ',', $to ) : (string) $to;
		$shop = ss_leads_shop_addrs(); $hit = false;
		foreach ( $shop as $s ) if ( false !== stripos( $to, $s ) ) { $hit = true; break; }
		$subj = (string) ( $m['subject'] ?? '' );
		$known = '' !== ss_leads_form( $subj, true ) && ! preg_match( '~^Re:|We received~i', $subj ); /* a known request form (some send to an address written in code) */
		if ( ! $hit && ! $known ) return $m;
		$h = $m['headers'] ?? array(); $h = is_array( $h ) ? $h : preg_split( "~\r?\n~", (string) $h );
		$name = ''; $email = '';
		foreach ( $h as $line ) {
			if ( ! preg_match( '~^\s*Reply-To:\s*(.*)$~i', (string) $line, $x ) ) continue;
			if ( preg_match( '~^(.*?)<([^>]+)>~', $x[1], $y ) ) { $name = trim( $y[1], " \"'" ); $email = trim( $y[2] ); } else $email = trim( $x[1] );
		}
		if ( $email && in_array( strtolower( $email ), $shop, true ) ) { $email = ''; $name = ''; }
		$form = ss_leads_form( $subj );
		if ( ! $email && ! $known ) return $m; /* system mail, not a request */
		$body = wp_strip_all_tags( (string) ( $m['message'] ?? '' ) );
		if ( ! $email && preg_match( '~[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}~i', $body, $z ) && ! in_array( strtolower( $z[0] ), $shop, true ) ) $email = $z[0];
		if ( ! $name && preg_match( '~^\s*Name:\s*(.+)$~mi', $body, $z ) ) $name = trim( $z[1] );
		$dk = 'ss_lead_' . md5( $subj . $body ); if ( get_transient( $dk ) ) return $m; set_transient( $dk, 1, 120 );
		$test = (bool) preg_match( '~\bTEST\b|@example\.(com|org|net)~', $subj . ' ' . $name . ' ' . $email . ' ' . mb_substr( $body, 0, 2000 ) );
		$id = wp_insert_post( array(
			'post_type' => 'ss_lead', 'post_status' => 'private', 'post_title' => mb_substr( ( $test ? '[TEST] ' : '' ) . $subj, 0, 200 ),
			'post_content' => mb_substr( $body, 0, 20000 ), 'post_author' => 0,
		), true );
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_ss_form', $form ); update_post_meta( $id, '_ss_name', mb_substr( $name, 0, 120 ) );
			update_post_meta( $id, '_ss_email', sanitize_email( $email ) ); update_post_meta( $id, '_ss_page', esc_url_raw( wp_get_referer() ?: '' ) );
			if ( $test ) update_post_meta( $id, '_ss_test', 1 );
		}
	} catch ( \Throwable $e ) {} /* never break the e-mail */
	return $m;
}, 999 );

/* admin list: Date · Form · Name · E-mail · Text */
add_filter( 'manage_ss_lead_posts_columns', function () {
	return array( 'cb' => '<input type="checkbox">', 'title' => 'Subject', 'ss_form' => 'Form', 'ss_name' => 'Name', 'ss_email' => 'E-mail', 'ss_text' => 'Text', 'date' => 'Date' );
} );
add_action( 'manage_ss_lead_posts_custom_column', function ( $c, $id ) {
	if ( 'ss_form' === $c ) echo esc_html( get_post_meta( $id, '_ss_form', true ) ) . ( get_post_meta( $id, '_ss_test', true ) ? ' <b style="color:#b45309">TEST</b>' : '' );
	elseif ( 'ss_name' === $c ) echo esc_html( get_post_meta( $id, '_ss_name', true ) );
	elseif ( 'ss_email' === $c ) { $e = get_post_meta( $id, '_ss_email', true ); if ( $e ) echo '<a href="mailto:' . esc_attr( $e ) . '">' . esc_html( $e ) . '</a>'; }
	elseif ( 'ss_text' === $c ) echo esc_html( wp_trim_words( get_post_field( 'post_content', $id ), 30 ) );
}, 10, 2 );
add_action( 'add_meta_boxes_ss_lead', function () {
	add_meta_box( 'ss_lead_body', 'Request', function ( $p ) {
		$e = get_post_meta( $p->ID, '_ss_email', true );
		echo '<p><b>' . esc_html( get_post_meta( $p->ID, '_ss_form', true ) ) . '</b> · ' . esc_html( get_the_date( 'Y-m-d H:i', $p ) ) . ' · ' . esc_html( get_post_meta( $p->ID, '_ss_name', true ) )
			. ( $e ? ' · <a href="mailto:' . esc_attr( $e ) . '?subject=' . rawurlencode( 'Re: ' . $p->post_title ) . '">' . esc_html( $e ) . '</a>' : '' ) . '</p>'
			. '<pre style="white-space:pre-wrap;font:13px/1.5 monospace;background:#f6f7f7;padding:12px;max-height:600px;overflow:auto">' . esc_html( $p->post_content ) . '</pre>';
	}, 'ss_lead', 'normal', 'high' );
} );

/* robots: /wp-json/ss/v1/leads?days=N[&full=1] (admins only) */
add_action( 'rest_api_init', function () {
	register_rest_route( 'ss/v1', '/leads', array(
		'methods' => 'GET', 'permission_callback' => function () { return current_user_can( 'manage_options' ); },
		'callback' => function ( $req ) {
			$n = max( 1, min( 365, (int) $req->get_param( 'days' ) ?: 7 ) ); $full = (bool) $req->get_param( 'full' );
			$q = get_posts( array( 'post_type' => 'ss_lead', 'post_status' => 'any', 'numberposts' => 200, 'orderby' => 'date', 'order' => 'DESC',
				'date_query' => array( array( 'after' => gmdate( 'Y-m-d H:i:s', time() - $n * DAY_IN_SECONDS ), 'column' => 'post_date_gmt' ) ) ) );
			$out = array();
			foreach ( $q as $p ) {
				$r = array( 'id' => $p->ID, 'date' => get_post_time( 'c', true, $p ), 'form' => get_post_meta( $p->ID, '_ss_form', true ),
					'test' => (bool) get_post_meta( $p->ID, '_ss_test', true ), 'has_email' => (bool) get_post_meta( $p->ID, '_ss_email', true ) );
				if ( $full ) $r += array( 'subject' => $p->post_title, 'name' => get_post_meta( $p->ID, '_ss_name', true ), 'email' => get_post_meta( $p->ID, '_ss_email', true ), 'text' => $p->post_content );
				$out[] = $r;
			}
			return array( 'days' => $n, 'count' => count( $out ), 'admin' => admin_url( 'edit.php?post_type=ss_lead' ), 'leads' => $out );
		},
	) );
} );

/* keep 365 days */
add_action( 'ss_leads_gc', function () {
	foreach ( get_posts( array( 'post_type' => 'ss_lead', 'post_status' => 'any', 'numberposts' => 100, 'fields' => 'ids',
		'date_query' => array( array( 'before' => '365 days ago' ) ) ) ) as $id ) wp_delete_post( $id, true );
} );
add_action( 'init', function () { if ( ! wp_next_scheduled( 'ss_leads_gc' ) ) wp_schedule_event( time() + 3600, 'daily', 'ss_leads_gc' ); } );
