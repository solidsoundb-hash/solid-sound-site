<?php
/**
 * Copy protection (owner 19.11.2026: «защиту от копий делай»).
 * Every product preview gets an audio fingerprint (SSAudio.fingerprint — made in the browser, the sound is never uploaded for it).
 *  - Add Product: the creator's audio is fingerprinted and compared with every product on the site; a match with someone
 *    else's product (≥ 15 % shared hashes; copies score 26–71 %, different tracks ≤ 8 % in our tests) shows a warning, and on
 *    saving the product is marked for review (_ss_fp_match) and the admin gets an email.
 *  - Existing products: Tools → Fingerprints → «Build missing» (runs in the admin's browser, one preview after another).
 * Index: option ss_fp_index = { product id: base64 of the sorted uint32 hashes }.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

const SS_FP_LIMIT = 0.15;

function ss_fp_pack( $arr ) { $arr = array_values( array_filter( array_map( 'intval', (array) $arr ), function ( $v ) { return $v >= 0; } ) ); sort( $arr ); $arr = array_slice( array_unique( $arr ), 0, 3000 ); return base64_encode( pack( 'V*', ...$arr ) ); }
function ss_fp_unpack( $s ) { $b = base64_decode( (string) $s ); return $b ? array_values( unpack( 'V*', $b ) ) : array(); }
function ss_fp_match( $a, $b ) {
	$i = $j = $n = 0; $la = count( $a ); $lb = count( $b );
	while ( $i < $la && $j < $lb ) { if ( $a[ $i ] === $b[ $j ] ) { $n++; $i++; $j++; } elseif ( $a[ $i ] < $b[ $j ] ) $i++; else $j++; }
	$m = min( $la, $lb ); return $m ? $n / $m : 0;
}
/* fingerprint 2 (20.11, owner «важно»): survives key and tempo changes — SSAudio.fingerprint2, flat [hash, t<<16|f<<8|span, …].
   A copy is ≥ SS_FP2_HITS hashes agreeing on one shift/stretch/offset (disguised copies 10–800, different tracks ≤ 3 in tests). */
const SS_FP2_HITS = 8;
function ss_fp2_clean( $a ) { $a = array_values( array_map( 'intval', (array) $a ) ); if ( count( $a ) % 2 ) array_pop( $a ); return array_slice( $a, 0, 24000 ); }
function ss_fp2_pack( $a ) { $a = ss_fp2_clean( $a ); return $a ? base64_encode( pack( 'V*', ...$a ) ) : ''; }
function ss_fp2_match( $a, $b ) {
	$idx = array(); $nb = count( $b );
	for ( $i = 0; $i < $nb; $i += 2 ) $idx[ $b[ $i ] ][] = $b[ $i + 1 ];
	$bins = array(); $best = 0; $na = count( $a );
	for ( $i = 0; $i < $na; $i += 2 ) {
		if ( ! isset( $idx[ $a[ $i ] ] ) ) continue;
		$ea = $a[ $i + 1 ]; $ta = ( $ea >> 16 ) & 0xFFFF; $fa = ( $ea >> 8 ) & 255; $sa = $ea & 255;
		foreach ( $idx[ $a[ $i ] ] as $eb ) {
			$tb = ( $eb >> 16 ) & 0xFFFF; $fb = ( $eb >> 8 ) & 255; $sb = $eb & 255; if ( ! $sb ) continue; $s = $sa / $sb; if ( $s < 0.9 || $s > 1.1 ) continue;
			$k = ( $fa - $fb ) . ':' . (int) floor( $s * 25 + 0.5 ) . ':' . (int) floor( ( $ta - $s * $tb ) / 40 + 0.5 ); /* = JS Math.round */
			$bins[ $k ] = ( $bins[ $k ] ?? 0 ) + 1; if ( $bins[ $k ] > $best ) $best = $bins[ $k ];
		}
	}
	return $best;
}
function ss_fp2_ids() { return array_map( 'intval', (array) get_option( 'ss_fp2_ids', array() ) ); }
function ss_fp2_store( $pid, $fp2 ) { $p = ss_fp2_pack( $fp2 ); if ( ! $p ) return; update_post_meta( $pid, '_ss_fp2', $p ); $ids = ss_fp2_ids(); if ( ! in_array( (int) $pid, $ids, true ) ) { $ids[] = (int) $pid; update_option( 'ss_fp2_ids', $ids, false ); } }
function ss_fp_in( $key ) { $v = json_decode( wp_unslash( $_POST[ $key ] ?? '[]' ), true ); return is_array( $v ) ? $v : array(); }
function ss_fp_find( $fp, $skip_author = 0, $skip_id = 0, $fp2 = array() ) {
	$out = array(); $seen = array();
	foreach ( (array) get_option( 'ss_fp_index', array() ) as $pid => $packed ) {
		if ( (int) $pid === (int) $skip_id ) continue;
		$s = ss_fp_match( $fp, ss_fp_unpack( $packed ) );
		if ( $s < SS_FP_LIMIT ) continue;
		$p = get_post( $pid ); if ( ! $p || 'trash' === $p->post_status ) continue;
		$out[] = array( 'id' => (int) $pid, 't' => html_entity_decode( get_the_title( $pid ) ), 'u' => get_permalink( $pid ), 'own' => $skip_author && (int) $p->post_author === (int) $skip_author, 's' => round( $s * 100 ) ); $seen[ (int) $pid ] = 1;
	}
	$fp2 = ss_fp2_clean( $fp2 );
	if ( count( $fp2 ) >= 60 ) foreach ( ss_fp2_ids() as $pid ) {
		if ( $pid === (int) $skip_id || isset( $seen[ $pid ] ) ) continue;
		$b = base64_decode( (string) get_post_meta( $pid, '_ss_fp2', true ) ); if ( ! $b ) continue;
		$n = ss_fp2_match( $fp2, array_values( unpack( 'V*', $b ) ) ); if ( $n < SS_FP2_HITS ) continue;
		$p = get_post( $pid ); if ( ! $p || 'trash' === $p->post_status ) continue;
		$out[] = array( 'id' => $pid, 't' => html_entity_decode( get_the_title( $pid ) ), 'u' => get_permalink( $pid ), 'own' => $skip_author && (int) $p->post_author === (int) $skip_author, 's' => min( 99, 20 + $n ), 'shift' => 1 );
	}
	usort( $out, function ( $a, $b ) { return $b['s'] - $a['s']; } );
	return array_slice( $out, 0, 3 );
}
function ss_fp_preview_url( $pid ) {
	$keys = function_exists( 'ss_ap_keys' ) ? ss_ap_keys() : array();
	$p = get_post( $pid ); if ( ! $p ) return '';
	$txt = $p->post_excerpt . ' ' . $p->post_content . ' ' . ( ! empty( $keys['mp3'] ) && function_exists( 'get_field' ) ? (string) get_field( $keys['mp3'], $pid, false ) : '' );
	return preg_match( '#https?://[^\s"\'\]]+?\.mp3#i', $txt, $m ) ? $m[0] : '';
}

/* creator: compare while filling in the form */
add_action( 'wp_ajax_ss_fp_check', function () {
	check_ajax_referer( 'ss_fp', 'n' );
	$fp = array_map( 'intval', ss_fp_in( 'fp' ) ); sort( $fp ); $fp2 = ss_fp_in( 'fp2' );
	if ( count( $fp ) < 30 && count( $fp2 ) < 60 ) wp_send_json_success( array() );
	wp_send_json_success( ss_fp_find( $fp, get_current_user_id(), 0, $fp2 ) );
} );

/* on saving a product from the form: store its fingerprint, flag a match with someone else's product */
add_action( 'save_post_product', function ( $pid, $post, $update ) {
	if ( wp_is_post_revision( $pid ) || ( empty( $_POST['ss_fp'] ) && empty( $_POST['ss_fp2'] ) ) ) return;
	$fp = array_map( 'intval', ss_fp_in( 'ss_fp' ) ); sort( $fp ); $fp2 = ss_fp_in( 'ss_fp2' );
	if ( count( $fp ) < 30 && count( $fp2 ) < 60 ) return;
	$m = array_values( array_filter( ss_fp_find( $fp, (int) $post->post_author, $pid, $fp2 ), function ( $x ) { return ! $x['own']; } ) );
	if ( count( $fp ) >= 30 ) { $idx = (array) get_option( 'ss_fp_index', array() ); $idx[ $pid ] = ss_fp_pack( $fp ); update_option( 'ss_fp_index', $idx, false ); }
	if ( count( $fp2 ) >= 60 ) ss_fp2_store( $pid, $fp2 );
	if ( $m ) {
		update_post_meta( $pid, '_ss_fp_match', $m );
		$txt = "A new product sounds like a product already on Solid Sound — please check before publishing.\n\nNew: " . get_the_title( $pid ) . "\n" . admin_url( 'post.php?post=' . $pid . '&action=edit' ) . "\n\nLooks like:\n";
		foreach ( $m as $x ) $txt .= '- ' . $x['t'] . ( empty( $x['shift'] ) ? ' (' . $x['s'] . ' % match) ' : ' (same music, key or tempo may be changed) ' ) . $x['u'] . "\n";
		wp_mail( get_option( 'admin_email' ), 'Possible copy: ' . get_the_title( $pid ), $txt );
	} else delete_post_meta( $pid, '_ss_fp_match' );
}, 20, 3 );

/* admin list: a red mark on flagged products */
add_filter( 'display_post_states', function ( $st, $post ) { if ( 'product' === $post->post_type && get_post_meta( $post->ID, '_ss_fp_match', true ) ) $st['ss_fp'] = '⚠ Possible copy'; return $st; }, 10, 2 );

/* the creator form: send the fingerprint along (key-detect.php computes it from the same decoded audio) */
add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() || ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return;
	global $wp; if ( ! array_key_exists( 'wcfm-ss-add-product', (array) $wp->query_vars ) ) return; ?>
<script id="ss-fp-js">window.ssFP={ajax:<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>,n:<?php echo wp_json_encode( wp_create_nonce( 'ss_fp' ) ); ?>};</script>
	<?php
}, 59 );

/* ---------- Tools → Fingerprints (admin) ---------- */
add_action( 'admin_menu', function () {
	add_management_page( 'Fingerprints', 'Fingerprints', 'manage_options', 'ss-fp', function () {
		$idx = (array) get_option( 'ss_fp_index', array() );
		$all = get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids' ) );
		$todo = array();
		$ids2 = ss_fp2_ids(); foreach ( $all as $id ) { if ( isset( $idx[ $id ] ) && in_array( (int) $id, $ids2, true ) ) continue; $u = ss_fp_preview_url( $id ); if ( $u ) $todo[] = array( $id, get_the_title( $id ), $u ); }
		echo '<div class="wrap"><h1>Fingerprints (copy protection)</h1><p>' . count( $idx ) . ' products have a fingerprint, ' . count( $todo ) . ' with a preview still need one.</p>';
		echo '<p><button class="button button-primary" id="ss-fp-go"' . ( $todo ? '' : ' disabled' ) . '>Build missing</button> <span id="ss-fp-st"></span></p><ol id="ss-fp-log" style="max-height:400px;overflow:auto"></ol></div>';
		if ( function_exists( 'ss_asset' ) ) ss_asset( 'ss-audio' );
		?>
<script>(function(){var T=<?php echo wp_json_encode( $todo ); ?>,AJ=<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>,N=<?php echo wp_json_encode( wp_create_nonce( 'ss_fp_admin' ) ); ?>,go=document.getElementById('ss-fp-go'),st=document.getElementById('ss-fp-st'),log=document.getElementById('ss-fp-log');
function li(t){var l=document.createElement('li');l.textContent=t;log.appendChild(l);return l}
go.onclick=function(){go.disabled=true;var C=window.AudioContext||window.webkitAudioContext,ctx=new C(),i=0,ok=0;
 (function next(){if(i>=T.length){st.textContent='Done: '+ok+' of '+T.length+'.';go.dataset.done='1';return}var t=T[i++],l=li(t[1]+' …');st.textContent=i+' / '+T.length;
  fetch(t[2]).then(function(r){if(!r.ok)throw 0;return r.arrayBuffer()}).then(function(ab){return ctx.decodeAudioData(ab)}).then(function(buf){var mo=SSAudio.mono(buf),fp=SSAudio.fingerprint(mo,buf.sampleRate),fp2=SSAudio.fingerprint2?SSAudio.fingerprint2(mo,buf.sampleRate):[];var f=new FormData();f.append('fp2',JSON.stringify(fp2));f.append('action','ss_fp_save');f.append('n',N);f.append('pid',t[0]);f.append('fp',JSON.stringify(fp));return fetch(AJ,{method:'POST',body:f,credentials:'same-origin'}).then(function(r){return r.json()})}).then(function(r){if(r&&r.success){ok++;l.textContent=t[1]+' ✓'+(r.data&&r.data.length?' — ⚠ sounds like '+r.data.map(function(x){return x.t+' ('+x.s+' %)'}).join(', '):'')}else l.textContent=t[1]+' ✗'}).catch(function(){l.textContent=t[1]+' ✗ (preview not loaded)'}).then(next)})()};
})();</script>
		<?php
	} );
} );
add_action( 'wp_ajax_ss_fp_save', function () {
	check_ajax_referer( 'ss_fp_admin', 'n' );
	if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error();
	$pid = absint( $_POST['pid'] ?? 0 ); $fp = json_decode( wp_unslash( $_POST['fp'] ?? '[]' ), true );
	if ( ! $pid || ! is_array( $fp ) || count( $fp ) < 30 ) wp_send_json_error();
	$fp = array_map( 'intval', $fp ); sort( $fp ); $fp2 = ss_fp_in( 'fp2' );
	$m = ss_fp_find( $fp, 0, $pid, $fp2 ); /* existing products that sound alike: shown in the log for the admin */
	$idx = (array) get_option( 'ss_fp_index', array() ); $idx[ $pid ] = ss_fp_pack( $fp ); update_option( 'ss_fp_index', $idx, false );
	if ( count( $fp2 ) >= 60 ) ss_fp2_store( $pid, $fp2 );
	wp_send_json_success( $m );
} );
