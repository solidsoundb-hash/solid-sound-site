<?php
/**
 * Creator application form, made like WeMake's /join (owner 15.11.2026):
 *  - no e-mail verification code step (WCFM's own switch, also off on the server check);
 *  - no "Country" and no "How many products are ready?" (asked later, in the dashboard / payout settings);
 *  - "Link to your work" and "What do you make?" are optional (as at WeMake);
 *  - new optional "Anything we should know?" text box, before the "please upload for me" checkbox.
 * Only the visitor's form is changed (the /sell/ page and its submit request). The WCFM settings page is never
 * affected, so saving the settings there can't store this edited list. Answers are saved by WCFM as before.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_apply_ctx() {
	if ( wp_doing_ajax() ) {
		return isset( $_POST['controller'] ) && 'wcfm-memberships-registration' === $_POST['controller']; /* the form's submit */
	}
	if ( is_admin() ) return false;
	$p = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
	return ! preg_match( '#store-manager|wcfm|wp-admin#', $p );
}

add_filter( 'wcfm_is_allow_email_verification', function ( $on ) { return ss_apply_ctx() ? false : $on; }, 20 );

add_filter( 'option_wcfmvm_registration_custom_fields', function ( $fields ) {
	if ( ! is_array( $fields ) || ! ss_apply_ctx() ) return $fields;
	$out = array(); $has_note = false;
	foreach ( $fields as $f ) {
		$l = strtolower( trim( isset( $f['label'] ) ? (string) $f['label'] : '' ) );
		if ( 'country' === $l || false !== strpos( $l, 'how many products' ) ) continue;
		if ( false !== strpos( $l, 'link to your work' ) || false !== strpos( $l, 'what do you make' ) ) unset( $f['required'] );
		if ( false !== strpos( $l, 'anything we should know' ) ) $has_note = true;
		$out[] = $f;
	}
	if ( ! $has_note ) {
		$note = array( 'enable' => 'yes', 'type' => 'textarea', 'label' => 'Anything we should know?', 'help_text' => '', 'options' => '' );
		$pos  = count( $out );
		foreach ( $out as $i => $f ) { if ( false !== strpos( isset( $f['label'] ) ? (string) $f['label'] : '', '5+' ) ) { $pos = $i; break; } }
		array_splice( $out, $pos, 0, array( $note ) );
	}
	return $out;
}, 20 );

/* (15.11.2026, found by the end-to-end robot) The plugin loads its registration script only on ITS registration page
   (is_wcfm_registration_page: the page set in WCFM or a page whose content has [wcfm_vendor_registration]).
   Our /sell/ page prints the form from inside [ss_sell], so the script was missing: "Send application" just reloaded
   the page with the answers in the address bar and no account was created. Load it here exactly like the plugin does. */
add_action( 'wp_enqueue_scripts', function () {
	global $WCFM, $WCFMvm;
	if ( ! is_singular() || empty( $WCFMvm ) || empty( $WCFM ) ) return;
	if ( ! has_shortcode( (string) get_post_field( 'post_content', get_queried_object_id() ), 'ss_sell' ) ) return;
	if ( wp_script_is( 'wcfm_membership_registration_js', 'enqueued' ) ) return;
	if ( isset( $WCFM->library ) && method_exists( $WCFM->library, 'load_select2_lib' ) ) $WCFM->library->load_select2_lib();
	wp_enqueue_script( 'wc-country-select' );
	wp_enqueue_script( 'wcfm_membership_registration_js', $WCFMvm->library->js_lib_url_min . 'wcfmvm-script-membership-registration.js', array( 'jquery' ), $WCFMvm->version, true );
	wp_localize_script( 'wcfm_membership_registration_js', 'wcfm_registration_params', array(
		'your_store'        => __( 'your_store', 'wc-multivendor-membership' ),
		'is_strength_check' => apply_filters( 'wcfm_is_allow_password_strength_check', true ),
		'short'             => __( 'Too short', 'wc-frontend-manager' ),
		'weak'              => __( 'Weak', 'wc-frontend-manager' ),
		'good'              => __( 'Good', 'wc-frontend-manager' ),
		'strong'            => __( 'Strong', 'wc-frontend-manager' ),
		'password_failed'   => __( 'Password strength should be atleast "Good".', 'wc-frontend-manager' ),
		'choose_select2'    => __( 'Choose ', 'wc-frontend-manager' ),
	) );
}, 30 );

/* the form must never fall back to a plain GET (it would put the password in the address bar) */
add_action( 'wp_footer', function () {
	if ( ! is_singular() || ! has_shortcode( (string) get_post_field( 'post_content', get_queried_object_id() ), 'ss_sell' ) ) return;
	echo '<script>(function(){var f=document.getElementById("wcfm_membership_registration_form");if(!f)return;f.setAttribute("method","post");f.setAttribute("action","#apply");f.addEventListener("submit",function(e){e.preventDefault()})})();</script>';
}, 99 );

/* (16.11.2026 owner: «сразу в кабинет») No manual approval of creator ACCOUNTS: during the sign-up request only, WCFM
   Membership sees required_approval = no, so it runs its own full register_vendor() (store, role, membership) and logs
   the creator in. Every PRODUCT is still reviewed before it is published. The settings page itself is not changed. */
add_filter( 'option_wcfm_membership_options', function ( $o ) {
	if ( ! wp_doing_ajax() || ! ss_apply_ctx() || ! is_array( $o ) ) return $o;
	if ( ! isset( $o['membership_reject_rules'] ) || ! is_array( $o['membership_reject_rules'] ) ) $o['membership_reject_rules'] = array();
	$o['membership_reject_rules']['required_approval'] = 'no';
	return $o;
}, 20 );
add_filter( 'get_post_metadata', function ( $v, $id, $key ) {
	return ( 'required_approval' === $key && wp_doing_ajax() && ss_apply_ctx() ) ? array( 'no' ) : $v;
}, 20, 3 );
/* after sign-up: straight to the creator dashboard instead of the "thank you, wait for approval" step */
add_filter( 'wcfm_registration_thankyou_url', function ( $u ) {
	if ( ! wp_doing_ajax() || ! ss_apply_ctx() ) return $u;
	return function_exists( 'get_wcfm_url' ) ? get_wcfm_url() : home_url( '/store-manager/' );
}, 99 );
/* WCFM only makes the account a vendor when a membership plan is stored for the user (temp_wcfm_membership); our
   site has no free plan, so the account stayed "subscriber". Finish it here with WCFM's own register_vendor();
   if that still leaves no vendor role, give the role directly (what approving in the admin does). */
add_action( 'wcfm_membership_registration', function ( $member_id ) {
	if ( ! wp_doing_ajax() || ! ss_apply_ctx() || ! $member_id ) return;
	$is_v = function () use ( $member_id ) { $u = get_userdata( $member_id ); return $u && in_array( 'wcfm_vendor', (array) $u->roles, true ); };
	if ( $is_v() ) return;
	if ( ! get_user_meta( $member_id, 'temp_wcfm_membership', true ) ) update_user_meta( $member_id, 'temp_wcfm_membership', '-1' );
	global $WCFMvm;
	if ( $WCFMvm && method_exists( $WCFMvm, 'register_vendor' ) ) { ob_start(); try { $WCFMvm->register_vendor( $member_id ); } catch ( \Throwable $e ) { error_log( 'ss auto-approve: ' . $e->getMessage() ); } ob_end_clean(); }
	if ( ! $is_v() ) { $u = new WP_User( $member_id ); $u->set_role( 'wcfm_vendor' ); }
}, 20 );
