<?php
/**
 * Translator for creators (owner 20.11.2026: «переводчик прям в окне описания, с ИИ, как гугл; авторы пишут на своих языках,
 * а сайт автоматом переводит — как AliExpress»).
 *  1) Add Product: under the description — From [Auto] → To [English] · Translate · Undo. AI keeps product names, BPM, keys,
 *     DAW and plugin names as they are. A hint shows up when the text is not in English.
 *  2) On saving a product with a non-English description: the original is kept (_ss_desc_orig, _ss_desc_lang) and the English
 *     translation becomes the description (background job, the creator does not wait).
 *  3) Product page: «Translated from Spanish · Show original».
 * AI: Cloudflare Workers AI through ss_cf_ai() (ai-tools.php). Customer-facing texts in English.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_tr_langs() { return array( 'English', 'Spanish', 'German', 'French', 'Italian', 'Portuguese', 'Ukrainian', 'Russian', 'Polish', 'Turkish', 'Dutch', 'Czech', 'Romanian', 'Swedish', 'Japanese', 'Korean', 'Chinese', 'Arabic', 'Hindi', 'Indonesian' ); }
/* returns array( 'lang' => source language name, 'text' => translation ) or WP_Error */
function ss_tr_run( $text, $to = 'English', $from = '' ) {
	if ( ! function_exists( 'ss_cf_ai' ) ) return new WP_Error( 'ss_tr', 'AI is not available.' );
	$text = trim( (string) $text ); if ( '' === $text ) return new WP_Error( 'ss_tr', 'Nothing to translate.' );
	$sys = 'You are a professional translator for a music production marketplace. Translate the user text into ' . $to . ( $from ? ' from ' . $from : '' ) . '. '
		. 'Keep product and artist names, BPM, musical keys (Am, F#m), DAW and plugin names, numbers and line breaks exactly as they are. Do not add or remove facts. '
		. 'Answer in exactly this format: first line "LANG: <the language of the original text in English, e.g. Spanish>", then an empty line, then only the translation.';
	$r = ss_cf_ai( '@cf/meta/llama-3.3-70b-instruct-fp8-fast', array( 'messages' => array( array( 'role' => 'system', 'content' => $sys ), array( 'role' => 'user', 'content' => mb_substr( $text, 0, 6000 ) ) ), 'max_tokens' => 1500, 'temperature' => 0.2 ), 60 );
	if ( is_wp_error( $r ) ) $r = ss_cf_ai( '@cf/meta/llama-3.1-8b-instruct', array( 'messages' => array( array( 'role' => 'system', 'content' => $sys ), array( 'role' => 'user', 'content' => mb_substr( $text, 0, 6000 ) ) ), 'max_tokens' => 1500, 'temperature' => 0.2 ), 60 );
	if ( is_wp_error( $r ) ) return $r;
	$out = trim( (string) ( $r['response'] ?? '' ) ); $lang = $from;
	if ( preg_match( '/^\s*LANG:\s*([^\n]+)\n+/i', $out, $m ) ) { $lang = trim( $m[1], " .\t" ); $out = trim( substr( $out, strlen( $m[0] ) ) ); }
	if ( '' === $out ) return new WP_Error( 'ss_tr', 'Could not translate — please try again.' );
	return array( 'lang' => $lang ? $lang : 'Unknown', 'text' => $out );
}
/* rough check: is this text English? (letters outside a–z, or few common English words) */
function ss_tr_is_en( $t ) {
	$t = wp_strip_all_tags( (string) $t ); if ( mb_strlen( $t ) < 25 ) return true;
	$letters = preg_match_all( '/\p{L}/u', $t ); $latin = preg_match_all( '/[a-z]/i', $t );
	if ( $letters && $latin / $letters < 0.85 ) return false;
	$w = preg_match_all( '/\b(the|and|with|for|your|this|you|of|to|in|is|it|a)\b/i', $t ); $n = max( 1, str_word_count( $t ) );
	return $w / $n > 0.08;
}

/* 1) the creator's translator */
add_action( 'wp_ajax_ss_tr', function () {
	check_ajax_referer( 'ss_tr', 'n' );
	if ( ! is_user_logged_in() || ( function_exists( 'ss_cp_can' ) && ! ss_cp_can() ) ) wp_send_json_error( array( 'm' => 'Please log in as a creator.' ), 403 );
	$k = 'ss_tr_' . get_current_user_id() . '_' . gmdate( 'Ymd' ); $used = (int) get_transient( $k );
	if ( $used >= 60 ) wp_send_json_error( array( 'm' => 'Daily translation limit reached — try again tomorrow.' ), 429 );
	$to = sanitize_text_field( wp_unslash( $_POST['to'] ?? 'English' ) ); $from = sanitize_text_field( wp_unslash( $_POST['from'] ?? '' ) );
	if ( ! in_array( $to, ss_tr_langs(), true ) ) $to = 'English'; if ( $from && ! in_array( $from, ss_tr_langs(), true ) ) $from = '';
	$r = ss_tr_run( wp_unslash( $_POST['text'] ?? '' ), $to, $from );
	if ( is_wp_error( $r ) ) wp_send_json_error( array( 'm' => $r->get_error_message() ), 500 );
	set_transient( $k, $used + 1, DAY_IN_SECONDS );
	wp_send_json_success( $r );
} );
add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() || ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return;
	global $wp; if ( ! array_key_exists( 'wcfm-ss-add-product', (array) $wp->query_vars ) && ! array_key_exists( 'wcfm-products-manage', (array) $wp->query_vars ) ) return;
	 ?>
<style id="ss-tr-css">#ss-tr{display:flex;flex-wrap:wrap;align-items:center;gap:8px;margin:8px 0 0;font:500 13.5px Roboto,sans-serif;color:#CBD5E1}#ss-tr select{height:34px;padding:0 8px;border-radius:8px;border:1px solid rgba(255,255,255,.14);background:#0B1220;color:#E2E8F0;font:500 13px Roboto,sans-serif}
#ss-tr button{height:34px;padding:0 14px;border-radius:8px;border:0;background:#3B82F6;color:#fff;font:700 13.5px "Exo 2",sans-serif;cursor:pointer}#ss-tr button.o{background:transparent;border:1px solid rgba(255,255,255,.2);color:#E2E8F0}#ss-tr button:disabled{opacity:.6;cursor:wait}
#ss-tr .m{flex-basis:100%;color:#94A3B8;font-size:13px}#ss-tr .m.hint{color:#93C5FD}</style>
<script id="ss-tr-js">(function(){/* owner 20.11 «пускай на своём пишет — автоматом переведётся»: just a note, the translation happens on save */
function init(){var ta=document.querySelector('#solid-sound-upload-form [name=product_description], [name=description]');if(!ta||document.getElementById('ss-tr'))return !!ta;
 var n=document.createElement('div');n.id='ss-tr';n.innerHTML='<span class="m hint">🌐 Write in any language — we translate it to English for buyers automatically and keep your original.</span>';ta.parentNode.insertBefore(n,ta.nextSibling);return true}
if(!init()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(init())mo.disconnect()})});mo.observe(document.body,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},20000)}
})();</script>
	<?php
}, 63 );

/* 2) auto-translate on save: non-English description → English for buyers, the original kept */
add_action( 'save_post_product', function ( $pid, $post ) {
	if ( wp_is_post_revision( $pid ) || defined( 'SS_TR_SAVING' ) ) return;
	$c = (string) $post->post_content;
	if ( '' === trim( wp_strip_all_tags( $c ) ) ) return;
	if ( ss_tr_is_en( $c ) ) { if ( get_post_meta( $pid, '_ss_desc_orig', true ) && md5( $c ) !== get_post_meta( $pid, '_ss_desc_en_md5', true ) ) { delete_post_meta( $pid, '_ss_desc_orig' ); delete_post_meta( $pid, '_ss_desc_lang' ); } return; }
	if ( ! wp_next_scheduled( 'ss_tr_auto', array( (int) $pid ) ) ) wp_schedule_single_event( time() + 5, 'ss_tr_auto', array( (int) $pid ) );
}, 30, 2 );
add_action( 'ss_tr_auto', function ( $pid ) {
	$p = get_post( $pid ); if ( ! $p || ss_tr_is_en( $p->post_content ) ) return;
	$r = ss_tr_run( $p->post_content, 'English' ); if ( is_wp_error( $r ) ) return;
	update_post_meta( $pid, '_ss_desc_orig', $p->post_content ); update_post_meta( $pid, '_ss_desc_lang', sanitize_text_field( $r['lang'] ) );
	if ( ! defined( 'SS_TR_SAVING' ) ) define( 'SS_TR_SAVING', 1 );
	wp_update_post( array( 'ID' => $pid, 'post_content' => wp_kses_post( $r['text'] ) ) );
	update_post_meta( $pid, '_ss_desc_en_md5', md5( (string) get_post_field( 'post_content', $pid ) ) );
} );

/* 3) product page: «Translated from … · Show original» */
add_filter( 'the_content', function ( $c ) {
	if ( ! is_singular( 'product' ) || ! in_the_loop() || ! is_main_query() ) return $c;
	$id = get_the_ID(); $o = (string) get_post_meta( $id, '_ss_desc_orig', true ); if ( '' === $o ) return $c;
	$lang = (string) get_post_meta( $id, '_ss_desc_lang', true );
	return $c . '<details class="ss-tr-orig" style="margin:14px 0 0;color:#94A3B8;font-size:13.5px"><summary style="cursor:pointer">🌐 Translated from ' . esc_html( $lang ? $lang : 'the creator\'s language' ) . ' · Show original</summary><div lang="" style="margin-top:8px;color:#CBD5E1">' . wpautop( wp_kses_post( $o ) ) . '</div></details>';
}, 20 );
