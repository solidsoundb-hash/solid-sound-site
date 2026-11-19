<?php
/**
 * Plugin Name: Solid Sound Custom
 * Description: All custom PHP for solid-sound.net (ratings & Best Products, SEO, AJAX player data, trust block, cookie consent, calculators, add-product form, front-page filter fix). Kept outside the theme so theme/plugin updates cannot erase it. Do not deactivate or delete.
 * Version: 1.0.0
 * Author: Solid Sound
 * Requires at least: 6.0
 * Update URI: false
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Release switch.
 *   build/custom.php    = optimized build (made by tools/build.mjs in the git repo; do not edit on the server)
 *   includes/custom.php = original code, kept as instant fallback
 * Visitors get the build when SS_BUILD_DEFAULT is true. Anyone can preview the other version:
 *   ?ss_v2=1  -> optimized build (cookie, 1 day)      ?ss_v2=0 -> original
 * Rollback without FTP: set SS_BUILD_DEFAULT to false (or delete the build/ folder).
 */
if ( ! defined( 'SS_BUILD_DEFAULT' ) ) define( 'SS_BUILD_DEFAULT', true );
function ss_use_build() {
	static $use = null;
	if ( null !== $use ) return $use;
	$use = SS_BUILD_DEFAULT;
	if ( isset( $_GET['ss_v2'] ) ) {
		$use = '1' === $_GET['ss_v2'];
		if ( ! headers_sent() ) setcookie( 'ss_v2', $use ? '1' : '0', time() + DAY_IN_SECONDS, '/', '', is_ssl(), true );
	} elseif ( isset( $_COOKIE['ss_v2'] ) ) {
		$use = '1' === $_COOKIE['ss_v2'];
	}
	if ( $use && ! is_readable( __DIR__ . '/build/custom.php' ) ) $use = false;
	return $use;
}
/* Theme Options -> Custom Code holds the player (84 KB, printed inline on every page). In the optimized build the
   theme gets those two fields empty and the plugin serves the same code as cached files (see the wp_head /
   wp_footer hooks below). The admin screen still shows the original code; nothing in the database changes. */
add_filter( 'option_usof_options_' . ( defined( 'US_THEMENAME' ) ? US_THEMENAME : 'Impreza' ), 'ss_theme_code_from_files' );
add_filter( 'option_usof_options_Impreza', 'ss_theme_code_from_files' );
function ss_theme_code_from_files( $o ) {
	if ( is_admin() || ! is_array( $o ) || ! function_exists( 'ss_use_build' ) || ! ss_use_build() || ! is_readable( __DIR__ . '/build/assets.php' ) ) return $o;
	if ( isset( $o['custom_html_head'] ) && false !== strpos( (string) $o['custom_html_head'], '.ssp{' ) ) $o['custom_html_head'] = '';
	if ( isset( $o['custom_html'] ) && false !== strpos( (string) $o['custom_html'], 'Solid Sound Player' ) ) $o['custom_html'] = '';
	return $o;
}

/* Prints a cached file in place of a big inline <style>/<script> (same position, same conditions). */
function ss_asset( $name, $mode = '' ) {
	static $map = null;
	if ( null === $map ) $map = (array) include __DIR__ . '/build/assets.php';
	if ( empty( $map[ $name ] ) ) return;
	$a   = $map[ $name ];
	$url = plugins_url( 'build/assets/' . $a['f'], __FILE__ );
	$id  = $a['id'] ? ' id="' . esc_attr( $a['id'] ) . '"' : '';
	if ( 'css' === $a['t'] ) echo '<link rel="stylesheet"' . $id . ' href="' . esc_url( $url ) . '">';
	else echo '<script' . $id . ' src="' . esc_url( $url ) . '"' . ( 'defer' === $mode ? ' defer' : '' ) . '></script>';
}

function ss_asset_has( $name ) {
	static $map = null;
	if ( null === $map ) $map = is_readable( __DIR__ . '/build/assets.php' ) ? (array) include __DIR__ . '/build/assets.php' : array();
	return ! empty( $map[ $name ] );
}

/* 19.11 speed: the catalog filter's product counts are remembered — must load before us-core (plugins load alphabetically) */
if ( is_readable( __DIR__ . '/build/modules/speed-grid.php' ) ) require_once __DIR__ . '/build/modules/speed-grid.php';

/* Load after the theme's functions.php. If the old copy is still inside the theme, skip (no duplicate functions). */
add_action( 'after_setup_theme', function () {
	if ( function_exists( 'rating_and_sidebar' ) ) return;
	require_once __DIR__ . ( ss_use_build() ? '/build/custom.php' : '/includes/custom.php' );
}, 0 );

/* Safety net: if Theme Options > Custom Code got wiped (theme reset / theme change), print the saved copy from /backup. */
function ss_custom_backup_code( $file ) {
	$path = __DIR__ . '/backup/' . $file;
	if ( ! is_readable( $path ) ) return '';
	$c = (string) file_get_contents( $path );
	return preg_replace( '/^<\?php exit; \?>\n/', '', $c );
}
function ss_custom_option( $key ) {
	return function_exists( 'us_get_option' ) ? (string) us_get_option( $key, '' ) : '';
}
/* Player code (styles + "Solid Sound Player"). While it is still pasted in Theme Options the theme prints it and we
   stay silent. Once those two fields are emptied: the optimized build serves it as cached files (src/theme/),
   the original version (?ss_v2=0) prints the saved copy from /backup. */
add_action( 'wp_head', function () {
	if ( is_admin() ) return;
	if ( strpos( ss_custom_option( 'custom_html_head' ), '.ssp{' ) !== false ) return;
	if ( ss_use_build() && is_readable( __DIR__ . '/build/assets.php' ) ) ss_asset( 'theme-player-css' );
	else echo ss_custom_backup_code( 'theme-options-head.php' );
}, 99 );
add_action( 'wp_footer', function () {
	if ( is_admin() ) return;
	if ( strpos( ss_custom_option( 'custom_html' ), 'Solid Sound Player' ) !== false ) return;
	if ( ss_use_build() && is_readable( __DIR__ . '/build/assets.php' ) ) ss_asset( 'theme-player-js' );
	else echo ss_custom_backup_code( 'theme-options-body.php' );
}, 99 );


/* === Solid Wiki lead magnet: vocal checklist PDF for email (added 2026-11-03). Remove this block to roll back. === */
function ss_wiki_pdfs() { return array('en'=>'https://solid-sound.net/wp-content/uploads/vocal-recording-checklist-en-1.pdf','de'=>'https://solid-sound.net/wp-content/uploads/vocal-recording-checklist-de-1.pdf','fr'=>'https://solid-sound.net/wp-content/uploads/vocal-recording-checklist-fr-1.pdf','es'=>'https://solid-sound.net/wp-content/uploads/vocal-recording-checklist-es-1.pdf','it'=>'https://solid-sound.net/wp-content/uploads/vocal-recording-checklist-it-1.pdf','pt'=>'https://solid-sound.net/wp-content/uploads/vocal-recording-checklist-pt-1.pdf','ua'=>'https://solid-sound.net/wp-content/uploads/vocal-recording-checklist-ua-1.pdf'); }
add_action( 'rest_api_init', function () {
	register_rest_route( 'ss/v1', '/wiki-lead', array( 'methods' => 'POST', 'permission_callback' => '__return_true', 'callback' => 'ss_wiki_lead_cb' ) );
} );
function ss_wiki_lead_cb( WP_REST_Request $r ) {
	if ( $r->get_param( 'website' ) ) return array( 'ok' => true );
	$email = sanitize_email( (string) $r->get_param( 'email' ) );
	$lang  = preg_replace( '/[^a-z]/', '', (string) $r->get_param( 'lang' ) );
	if ( ! is_email( $email ) || ! $r->get_param( 'consent' ) ) return new WP_REST_Response( array( 'ok' => false ), 400 );
	$k = 'ss_lead_' . md5( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '' );
	$n = (int) get_transient( $k );
	if ( $n > 5 ) return new WP_REST_Response( array( 'ok' => false ), 429 );
	set_transient( $k, $n + 1, HOUR_IN_SECONDS );
	$pdfs = ss_wiki_pdfs();
	$url  = isset( $pdfs[ $lang ] ) ? $pdfs[ $lang ] : $pdfs['en'];
	$opt  = get_option( 'sib_main_option' );
	$key  = ( is_array( $opt ) && ! empty( $opt['access_key'] ) ) ? $opt['access_key'] : get_option( 'sib_api_key_v3', '' );
	$brevo = 'nokey';
	if ( $key ) {
		$res = wp_remote_post( 'https://api.brevo.com/v3/contacts', array( 'timeout' => 10, 'headers' => array( 'api-key' => $key, 'content-type' => 'application/json', 'accept' => 'application/json' ), 'body' => wp_json_encode( array( 'email' => $email, 'listIds' => array( 3 ), 'updateEnabled' => true ) ) ) );
		$brevo = is_wp_error( $res ) ? 'err' : wp_remote_retrieve_response_code( $res );
	}
	$log   = get_option( 'ss_wiki_leads', array() );
	$log[] = array( 'e' => $email, 'l' => $lang, 't' => time(), 'b' => $brevo );
	update_option( 'ss_wiki_leads', array_slice( $log, -500 ), false );
	$t = array(
		'en' => array( 'Your vocal recording checklist', 'Here is your free checklist (PDF). Print it and pin it next to your mic:', 'Want a pro ear on your vocal? Just reply to this email.' ),
		'de' => array( 'Deine Checkliste für Gesangsaufnahmen', 'Hier ist deine kostenlose Checkliste (PDF). Druck sie aus und häng sie neben dein Mikrofon:', 'Willst du ein Profi-Ohr für deinen Gesang? Antworte einfach auf diese E-Mail.' ),
		'fr' => array( 'Ta checklist d’enregistrement vocal', 'Voici ta checklist gratuite (PDF). Imprime-la et affiche-la à côté de ton micro :', 'Tu veux une oreille de pro sur ta voix ? Réponds simplement à cet e-mail.' ),
		'es' => array( 'Tu checklist para grabar voces', 'Aquí tienes tu checklist gratis (PDF). Imprímela y pégala junto a tu micro:', '¿Quieres un oído profesional para tu voz? Responde a este correo.' ),
		'it' => array( 'La tua checklist per registrare la voce', 'Ecco la tua checklist gratuita (PDF). Stampala e appendila accanto al microfono:', 'Vuoi un orecchio professionale sulla tua voce? Rispondi a questa email.' ),
		'pt' => array( 'A tua checklist para gravar voz', 'Aqui está a tua checklist gratuita (PDF). Imprime-a e cola-a ao lado do microfone:', 'Queres um ouvido profissional para a tua voz? Responde a este email.' ),
		'ua' => array( 'Твій чекліст запису вокалу', 'Ось твій безкоштовний чекліст (PDF). Роздрукуй і повісь поруч із мікрофоном:', 'Хочеш професійне вухо для свого вокалу? Просто відповідай на цей лист.' ),
	);
	$m = isset( $t[ $lang ] ) ? $t[ $lang ] : $t['en'];
	wp_mail( $email, $m[0] . ' — Solid Sound', $m[1] . "\n\n" . $url . "\n\n" . $m[2] . "\n\n— Sølid Søund Studio\nhttps://solid-sound.net" );
	return array( 'ok' => true, 'pdf' => $url );
}
add_action( 'wp_footer', function () {
	if ( is_admin() ) return;
	?><script>document.addEventListener('submit',function(e){var f=e.target;if(!f.classList||!f.classList.contains('ss-lead'))return;e.preventDefault();var b=f.querySelector('button'),m=f.querySelector('.ss-lead-msg');b.disabled=true;fetch('/wp-json/ss/v1/wiki-lead',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({email:f.email.value,lang:f.dataset.lang||'en',consent:f.consent.checked?1:0,website:f.website.value})}).then(function(r){return r.json()}).then(function(j){if(j&&j.ok){f.classList.add('done');m.innerHTML=f.dataset.ok+(j.pdf?' <a href="'+j.pdf+'" target="_blank" rel="noopener">PDF ↓</a>':'');try{window.gtag&&gtag('event','generate_lead',{form:'wiki_checklist',lang:f.dataset.lang})}catch(x){}}else{m.textContent=f.dataset.err;b.disabled=false}}).catch(function(){m.textContent=f.dataset.err;b.disabled=false})});</script><?php
}, 100 );

/*
 * One-time export of code stored in the database (Theme Options > Custom Code and page contents),
 * so it can be moved into versioned files. Runs only when the file "export-request" exists here;
 * writes exports/*.php (not web-readable: each starts with <?php exit; ?>) and deletes the request file.
 */
add_action( 'wp_loaded', function () {
	$req = __DIR__ . '/export-request';
	if ( ! file_exists( $req ) ) return;
	@unlink( $req );
	$dir = __DIR__ . '/exports';
	wp_mkdir_p( $dir );
	$put = function ( $name, $data ) use ( $dir ) { file_put_contents( $dir . '/' . $name . '.php', "<?php exit; ?>\n" . $data ); };
	foreach ( array( 'custom_html_head', 'custom_html', 'custom_css' ) as $k ) $put( 'theme-' . $k, ss_custom_option( $k ) );
	foreach ( array( 2433, 2, 52, 2561 ) as $id ) {
		$p = get_post( $id );
		if ( $p ) $put( 'post-' . $id, $p->post_content );
	}
	$put( 'meta', wp_json_encode( array( 'time' => gmdate( 'c' ), 'theme' => get_option( 'stylesheet' ) ) ) );
} );
