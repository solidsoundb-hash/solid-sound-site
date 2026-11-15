<?php
/**
 * Sign in with Google (Google Identity Services), built in — no third-party plugin.
 *
 * - "Continue with Google" button on the Log in / Create account tabs and on checkout (guests only),
 *   One Tap prompt on the account page.
 * - The browser gets a Google ID token; the server checks it with Google (tokeninfo), requires a verified
 *   email and our client ID, then logs the user in or creates a customer account (no password needed).
 * - Accounts that can manage the site (admins, shop managers) cannot sign in with Google: password only.
 * - Vendors land in the Producer space, clients in My account (or back on checkout).
 * Google Cloud project "Solid Sound": JS origin https://solid-sound.net.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'SS_GOOGLE_CLIENT_ID' ) ) define( 'SS_GOOGLE_CLIENT_ID', '152337347722-trdd5avbmpcqplnrd3un7p5g78vm18ko.apps.googleusercontent.com' );

/* ---------- server: verify the Google token, log in or create the customer ---------- */
add_action( 'rest_api_init', function () {
	register_rest_route( 'ss/v1', '/google', array(
		'methods' => 'POST', 'permission_callback' => '__return_true', 'callback' => 'ss_google_signin',
	) );
} );

function ss_google_signin( WP_REST_Request $r ) {
	$fail = function ( $msg, $code = 400 ) { return new WP_REST_Response( array( 'ok' => false, 'message' => $msg ), $code ); };
	if ( is_user_logged_in() ) return array( 'ok' => true, 'redirect' => wc_get_page_permalink( 'myaccount' ) );
	$token = (string) $r->get_param( 'credential' );
	if ( ! $token || strlen( $token ) > 4096 ) return $fail( 'Missing sign-in data.' );

	/* light rate limit per IP */
	$k = 'ss_g_' . md5( isset( $_SERVER['REMOTE_ADDR'] ) ? (string) $_SERVER['REMOTE_ADDR'] : '' );
	$n = (int) get_transient( $k );
	if ( $n > 20 ) return $fail( 'Too many attempts, try again in a few minutes.', 429 );
	set_transient( $k, $n + 1, 10 * MINUTE_IN_SECONDS );

	$res = wp_remote_get( 'https://oauth2.googleapis.com/tokeninfo?id_token=' . rawurlencode( $token ), array( 'timeout' => 10 ) );
	if ( is_wp_error( $res ) || 200 !== (int) wp_remote_retrieve_response_code( $res ) ) return $fail( 'Google could not confirm the sign-in. Please try again.' );
	$t = json_decode( (string) wp_remote_retrieve_body( $res ), true );
	if ( ! is_array( $t ) || ( $t['aud'] ?? '' ) !== SS_GOOGLE_CLIENT_ID || ! in_array( $t['iss'] ?? '', array( 'accounts.google.com', 'https://accounts.google.com' ), true )
		|| (int) ( $t['exp'] ?? 0 ) < time() || 'true' !== (string) ( $t['email_verified'] ?? '' ) || ! is_email( $t['email'] ?? '' ) ) {
		return $fail( 'This Google account could not be used.' );
	}
	$email = sanitize_email( $t['email'] );
	$user  = get_user_by( 'email', $email );
	if ( $user && ( user_can( $user, 'manage_options' ) || user_can( $user, 'manage_woocommerce' ) ) ) {
		return $fail( 'For security, staff accounts sign in with their password.', 403 );
	}
	$new = false;
	if ( ! $user ) {
		$uid = function_exists( 'wc_create_new_customer' )
			? wc_create_new_customer( $email, function_exists( 'wc_create_new_customer_username' ) ? wc_create_new_customer_username( $email, array( 'first_name' => (string) ( $t['given_name'] ?? '' ), 'last_name' => (string) ( $t['family_name'] ?? '' ) ) ) : '', wp_generate_password( 24 ), array( 'first_name' => sanitize_text_field( $t['given_name'] ?? '' ), 'last_name' => sanitize_text_field( $t['family_name'] ?? '' ) ) )
			: wp_create_user( sanitize_user( strstr( $email, '@', true ) . wp_rand( 100, 999 ), true ), wp_generate_password( 24 ), $email );
		if ( is_wp_error( $uid ) ) return $fail( $uid->get_error_message() );
		$user = get_user_by( 'id', $uid ); $new = true;
	}
	update_user_meta( $user->ID, 'ss_google_sub', sanitize_text_field( $t['sub'] ?? '' ) );
	wp_clear_auth_cookie();
	wp_set_current_user( $user->ID );
	wp_set_auth_cookie( $user->ID, true, is_ssl() );
	do_action( 'wp_login', $user->user_login, $user );

	$back = esc_url_raw( (string) $r->get_param( 'back' ) );
	$to = wc_get_page_permalink( 'myaccount' ); /* one account, two roles: My account first */
	if ( 'vendor' === $r->get_param( 'as' ) && ! ( function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor( $user->ID ) ) ) $to = home_url( '/sell/' );
	elseif ( $back && false !== strpos( $back, 'checkout' ) && 0 === strpos( $back, home_url() ) ) $to = $back;
	return array( 'ok' => true, 'new' => $new, 'redirect' => $to );
}

/* ---------- front: button + One Tap (guests only, only on account and checkout pages) ---------- */
function ss_google_button( $as = '' ) {
	if ( is_user_logged_in() ) return;
	if ( 'vendor' === $as ) { echo '<div class="ss-gsi" data-as="vendor"><div class="ss-gsi-btn" data-text="signup_with"></div><div class="ss-gsi-or"><span>or</span></div></div>'; return; }
	echo '<div class="ss-gsi"><div class="ss-gsi-btn"></div><div class="ss-gsi-or"><span>or</span></div></div>';
}
add_action( 'woocommerce_login_form_start', function () { ss_google_button(); }, 1 );
add_action( 'woocommerce_register_form_start', function () { ss_google_button(); }, 1 );
add_action( 'woocommerce_before_checkout_form', function () {
	if ( ! is_user_logged_in() ) echo '<div class="ss-gsi ss-gsi-co"><p>Have a Google account? Check out faster:</p><div class="ss-gsi-btn"></div></div>';
}, 4 );

add_action( 'wp_footer', function () {
	if ( is_user_logged_in() || ! function_exists( 'is_account_page' ) || ! ( is_account_page() || is_checkout() ) ) return;
	$cfg = array( 'id' => SS_GOOGLE_CLIENT_ID, 'api' => esc_url_raw( rest_url( 'ss/v1/google' ) ), 'nonce' => wp_create_nonce( 'wp_rest' ), 'onetap' => is_account_page() );
	?>
<style id="ss-gsi-css">.ss-gsi{margin:0 0 16px}.ss-gsi-btn{display:flex;justify-content:center;min-height:44px}.ss-gsi-or{display:flex;align-items:center;gap:12px;margin:16px 0 4px;color:#94A3B8;font-size:13px}.ss-gsi-or:before,.ss-gsi-or:after{content:"";flex:1;height:1px;background:rgba(255,255,255,.1)}.ss-gsi-co{padding:16px;border:1px solid rgba(255,255,255,.08);border-radius:12px;text-align:center}.ss-gsi-co p{margin:0 0 10px;color:#CBD5E1}.ss-gsi-msg{color:#F87171;font-size:13px;text-align:center;margin-top:8px}</style>
<script id="ss-gsi-js">
(function(){
  var C=<?php echo wp_json_encode( $cfg ); ?>, busy=false, intent='';
  /* which button was used: the vendor tab one leads to the vendor application */
  document.addEventListener('pointerdown',function(e){ var g=e.target.closest ? e.target.closest('.ss-gsi') : null; if(g) intent=g.getAttribute('data-as')||''; },true);
  function done(resp){
    if(busy || !resp || !resp.credential) return; busy=true;
    fetch(C.api,{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json','X-WP-Nonce':C.nonce},body:JSON.stringify({credential:resp.credential,back:location.href,as:intent})})
      .then(function(r){ return r.json(); })
      .then(function(j){
        if(j && j.ok){ try{ if(window.gtag) gtag('event', j['new'] ? 'sign_up' : 'login', {method:'Google'}); }catch(x){} location.href=j.redirect; return; }
        busy=false; document.querySelectorAll('.ss-gsi-btn').forEach(function(b){ var m=b.parentNode.querySelector('.ss-gsi-msg')||document.createElement('div'); m.className='ss-gsi-msg'; m.textContent=(j&&j.message)||'Sign-in failed, please try again.'; b.parentNode.appendChild(m); });
      }).catch(function(){ busy=false; });
  }
  function init(){
    if(!window.google || !google.accounts || !google.accounts.id) return;
    google.accounts.id.initialize({client_id:C.id,callback:done,auto_select:false,cancel_on_tap_outside:true,itp_support:true,use_fedcm_for_prompt:true});
    document.querySelectorAll('.ss-gsi-btn').forEach(function(el){
      var w=Math.min(400,Math.max(220,el.offsetWidth||320));
      google.accounts.id.renderButton(el,{theme:'filled_black',size:'large',shape:'pill',text:el.getAttribute('data-text')||'continue_with',logo_alignment:'left',width:w});
    });
    if(C.onetap) google.accounts.id.prompt();
  }
  var s=document.createElement('script'); s.src='https://accounts.google.com/gsi/client'; s.async=true; s.defer=true; s.onload=init; document.head.appendChild(s);
})();
</script>
	<?php
}, 30 );
