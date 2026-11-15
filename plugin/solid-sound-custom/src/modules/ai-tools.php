<?php
/**
 * (15.11.2026 owner) AI tools for creators — free Cloudflare Workers AI account.
 * WooCommerce -> Solid Sound AI: Account ID + API token (stored in wp_options, never printed back; only the last 4 characters).
 * "Test connection" asks the text model for one short line, so the owner sees at once that the key works.
 * ss_cf_ai( $model, $payload ) is the single place that talks to Cloudflare; the creator tools use it.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_cf_ai( $model, $payload, $timeout = 40 ) {
	$acc = trim( (string) get_option( 'ss_cf_account', '' ) );
	$tok = trim( (string) get_option( 'ss_cf_token', '' ) );
	if ( ! $acc || ! $tok ) return new WP_Error( 'ss_ai_off', 'AI is not set up yet.' );
	$r = wp_remote_post( 'https://api.cloudflare.com/client/v4/accounts/' . rawurlencode( $acc ) . '/ai/run/' . $model, array(
		'timeout' => $timeout,
		'headers' => array( 'Authorization' => 'Bearer ' . $tok, 'Content-Type' => 'application/json' ),
		'body'    => wp_json_encode( $payload ),
	) );
	if ( is_wp_error( $r ) ) return $r;
	$code = wp_remote_retrieve_response_code( $r );
	$ct   = (string) wp_remote_retrieve_header( $r, 'content-type' );
	$body = wp_remote_retrieve_body( $r );
	if ( 0 === strpos( $ct, 'image/' ) ) return array( 'image' => $body, 'type' => $ct ); /* some image models answer with raw bytes */
	$j = json_decode( $body, true );
	if ( 200 !== $code || empty( $j['success'] ) ) {
		$m = isset( $j['errors'][0]['message'] ) ? $j['errors'][0]['message'] : ( 'HTTP ' . $code );
		return new WP_Error( 'ss_ai_err', $m );
	}
	return $j['result'];
}

add_action( 'admin_menu', function () {
	add_submenu_page( 'woocommerce', 'Solid Sound AI', 'Solid Sound AI', 'manage_woocommerce', 'ss-ai', 'ss_ai_admin_page' );
} );

function ss_ai_admin_page() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) return;
	$msg = '';
	if ( isset( $_POST['ss_ai_save'] ) && check_admin_referer( 'ss_ai_save' ) ) {
		update_option( 'ss_cf_account', sanitize_text_field( wp_unslash( $_POST['ss_cf_account'] ?? '' ) ), false );
		$t = trim( sanitize_text_field( wp_unslash( $_POST['ss_cf_token'] ?? '' ) ) );
		if ( '' !== $t ) update_option( 'ss_cf_token', $t, false );
		if ( ! empty( $_POST['ss_cf_forget'] ) ) delete_option( 'ss_cf_token' );
		$msg = '<div class="notice notice-success"><p>Saved.</p></div>';
	}
	if ( isset( $_POST['ss_ai_test'] ) && check_admin_referer( 'ss_ai_save' ) ) {
		$r = ss_cf_ai( '@cf/meta/llama-3.1-8b-instruct', array( 'messages' => array( array( 'role' => 'user', 'content' => 'Reply with one short friendly sentence for music producers: the AI connection works.' ) ), 'max_tokens' => 40 ) );
		$msg = is_wp_error( $r )
			? '<div class="notice notice-error"><p><b>Not working:</b> ' . esc_html( $r->get_error_message() ) . '</p></div>'
			: '<div class="notice notice-success"><p><b>Works ✓</b> AI says: “' . esc_html( isset( $r['response'] ) ? $r['response'] : wp_json_encode( $r ) ) . '”</p></div>';
	}
	$acc = (string) get_option( 'ss_cf_account', '' );
	$tok = (string) get_option( 'ss_cf_token', '' );
	?>
	<div class="wrap"><h1>Solid Sound AI (Cloudflare Workers AI, free)</h1><?php echo $msg; ?>
	<p>Used for the creator tools: product description writer and cover background generator. Free plan: about 10,000 “neurons” a day.</p>
	<ol><li>Open <a href="https://dash.cloudflare.com/" target="_blank" rel="noopener">dash.cloudflare.com</a> → <b>AI → Workers AI</b> → <b>Use REST API</b>.</li>
	<li>Copy the <b>Account ID</b> into the first field.</li>
	<li>Click <b>Create a Workers AI API Token</b> → <b>Create API Token</b>, copy the token into the second field, press <b>Save</b>, then <b>Test connection</b>.</li></ol>
	<form method="post"><?php wp_nonce_field( 'ss_ai_save' ); ?>
	<table class="form-table"><tr><th><label for="ss_cf_account">Account ID</label></th><td><input id="ss_cf_account" name="ss_cf_account" class="regular-text" value="<?php echo esc_attr( $acc ); ?>" autocomplete="off"></td></tr>
	<tr><th><label for="ss_cf_token">API token</label></th><td><input id="ss_cf_token" name="ss_cf_token" type="password" class="regular-text" value="" autocomplete="new-password" placeholder="<?php echo $tok ? esc_attr( 'saved •••• ' . substr( $tok, -4 ) . ' — paste a new one to replace' ) : 'paste the token here'; ?>">
	<?php if ( $tok ) : ?><p><label><input type="checkbox" name="ss_cf_forget" value="1"> Remove the saved token</label></p><?php endif; ?></td></tr></table>
	<p><button class="button button-primary" name="ss_ai_save" value="1">Save</button> <button class="button" name="ss_ai_test" value="1" <?php disabled( ! $acc || ! $tok ); ?>>Test connection</button></p>
	</form></div>
	<?php
}
