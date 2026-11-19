<?php
/**
 * «Tell buyers about an update» (creator tool from the handover: «обновление товара + письмо покупателям»).
 * Creator → Products: pick one of your products, write what changed, see how many buyers get it, press the button.
 * Nothing is sent automatically: only by the creator's own click. Once per product per 7 days, mails go out in batches of 40
 * (WP-Cron every few minutes) so the host is not flooded. Buyers = paid orders (completed/processing) with this product.
 * Customer-facing texts in English.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_un_buyers( $pid ) {
	global $wpdb;
	$lookup = $wpdb->prefix . 'wc_order_product_lookup';
	if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $lookup ) ) !== $lookup ) return array();
	$oids = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT order_id FROM $lookup WHERE product_id = %d AND product_qty > 0", $pid ) );
	$mails = array();
	foreach ( $oids as $oid ) {
		$o = wc_get_order( $oid );
		if ( ! $o || ! in_array( $o->get_status(), array( 'completed', 'processing' ), true ) ) continue;
		$e = sanitize_email( $o->get_billing_email() );
		if ( $e && is_email( $e ) ) $mails[ strtolower( $e ) ] = 1;
	}
	return array_keys( $mails );
}
function ss_un_can( $pid ) {
	$p = get_post( $pid );
	return $p && 'product' === $p->post_type && ( (int) $p->post_author === get_current_user_id() || current_user_can( 'manage_woocommerce' ) );
}

/* count before sending */
add_action( 'wp_ajax_ss_un_count', function () {
	check_ajax_referer( 'ss_un', 'n' );
	$pid = absint( $_POST['pid'] ?? 0 );
	if ( ! ss_un_can( $pid ) ) wp_send_json_error( 'Not your product.' );
	$last = (int) get_post_meta( $pid, '_ss_un_last', true );
	wp_send_json_success( array( 'buyers' => count( ss_un_buyers( $pid ) ), 'wait' => $last && $last > time() - 7 * DAY_IN_SECONDS ? gmdate( 'M j', $last + 7 * DAY_IN_SECONDS ) : '' ) );
} );

/* queue the mails */
add_action( 'wp_ajax_ss_un_send', function () {
	check_ajax_referer( 'ss_un', 'n' );
	$pid  = absint( $_POST['pid'] ?? 0 );
	$note = mb_substr( sanitize_textarea_field( wp_unslash( $_POST['note'] ?? '' ) ), 0, 800 );
	if ( ! ss_un_can( $pid ) ) wp_send_json_error( 'Not your product.' );
	if ( mb_strlen( trim( $note ) ) < 10 ) wp_send_json_error( 'Please write in a sentence what changed.' );
	$last = (int) get_post_meta( $pid, '_ss_un_last', true );
	if ( $last && $last > time() - 7 * DAY_IN_SECONDS ) wp_send_json_error( 'Buyers of this product were told about an update less than a week ago.' );
	$to = ss_un_buyers( $pid );
	if ( ! $to ) wp_send_json_error( 'This product has no buyers yet.' );
	update_post_meta( $pid, '_ss_un_last', time() );
	$q = get_option( 'ss_un_queue', array() ); if ( ! is_array( $q ) ) $q = array();
	$q[] = array( 'pid' => $pid, 'note' => $note, 'to' => $to );
	update_option( 'ss_un_queue', $q, false );
	if ( ! wp_next_scheduled( 'ss_un_tick' ) ) wp_schedule_single_event( time() + 30, 'ss_un_tick' );
	wp_send_json_success( array( 'buyers' => count( $to ) ) );
} );

add_action( 'ss_un_tick', function () {
	$q = get_option( 'ss_un_queue', array() ); if ( ! is_array( $q ) || ! $q ) return;
	$left = 40;
	while ( $q && $left > 0 ) {
		$job = &$q[0]; $p = wc_get_product( $job['pid'] );
		$title = $p ? $p->get_name() : 'your product';
		$subj = 'Update for ' . $title . ' — download the new version';
		$link = wc_get_account_endpoint_url( 'downloads' );
		$body = "Hi,\n\nThe creator of {$title} has released an update:\n\n" . $job['note'] . "\n\nThe new version is already in your account — no extra charge:\n" . $link . "\n\nThanks for making music with Solid Sound.\n";
		while ( $job['to'] && $left > 0 ) { wp_mail( array_shift( $job['to'] ), $subj, $body ); $left--; }
		if ( ! $job['to'] ) array_shift( $q );
		unset( $job );
	}
	update_option( 'ss_un_queue', $q, false );
	if ( $q ) wp_schedule_single_event( time() + 300, 'ss_un_tick' );
} );

/* the panel on Creator → Products */
add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() || ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return;
	global $wp; if ( ! array_key_exists( 'wcfm-ss-products', (array) $wp->query_vars ) ) return;
	$ids = get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'author' => get_current_user_id(), 'numberposts' => 100, 'fields' => 'ids', 'orderby' => 'title', 'order' => 'ASC' ) );
	if ( ! $ids ) return;
	$opts = array(); foreach ( $ids as $id ) $opts[] = array( $id, html_entity_decode( get_the_title( $id ) ) );
	?>
<style id="ss-un-css">.ss-un{margin:18px 0;padding:18px;border-radius:16px;background:#0f1729;border:1px solid rgba(255,255,255,.08);font-family:"Exo 2",system-ui,sans-serif;color:#CBD5E1}
.ss-un h3{margin:0 0 4px;color:#fff;font-size:19px;font-weight:800}.ss-un>p{margin:0 0 12px;color:#94A3B8;font-size:14px}
.ss-un select,.ss-un textarea{width:100%;box-sizing:border-box;margin:0 0 10px;padding:10px 12px;border-radius:10px;border:1px solid rgba(255,255,255,.12);background:#0b1220;color:#fff;font:400 15px "Exo 2",sans-serif}
.ss-un textarea{min-height:84px;resize:vertical}.ss-un .row{display:flex;flex-wrap:wrap;align-items:center;gap:12px}
.ss-un button{min-height:44px;padding:0 18px;border:0;border-radius:10px;background:#F59E0B;color:#111;font:700 15px "Exo 2",sans-serif;cursor:pointer}.ss-un button[disabled]{opacity:.5;cursor:default}
.ss-un .msg{font-size:14px}.ss-un .msg.ok{color:#34D399}.ss-un .msg.er{color:#FCA5A5}</style>
<script id="ss-un-js">(function(){var O=<?php echo wp_json_encode( $opts ); ?>,AJ=<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>,N=<?php echo wp_json_encode( wp_create_nonce( 'ss_un' ) ); ?>;
function esc(s){return String(s).replace(/[&<>"]/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]})}
function post(a,d){var f=new FormData();f.append('action',a);f.append('n',N);for(var k in d)f.append(k,d[k]);return fetch(AJ,{method:'POST',body:f,credentials:'same-origin'}).then(function(r){return r.json()})}
function put(){var host=document.querySelector('#wcfm-content .wcfm-collapse')||document.querySelector('#wcfm-content');if(!host||document.querySelector('.ss-un'))return !!host;
 var s=document.createElement('section');s.className='ss-un';
 s.innerHTML='<h3>Tell buyers about an update</h3><p>Fixed a bug, added a version for a newer DAW, new presets? Upload the new files to the product first, then let everyone who bought it know — they download it free from their account.</p>'+
  '<select aria-label="Product">'+O.map(function(o){return '<option value="'+o[0]+'">'+esc(o[1])+'</option>'}).join('')+'</select>'+
  '<textarea maxlength="800" placeholder="What changed? e.g. Added an Ableton 12 version and fixed the missing kick sample."></textarea>'+
  '<div class="row"><button type="button">Notify buyers</button><span class="msg"></span></div>';
 host.appendChild(s);var sel=s.querySelector('select'),ta=s.querySelector('textarea'),bt=s.querySelector('button'),m=s.querySelector('.msg');
 function count(){m.className='msg';m.textContent='Counting buyers…';bt.disabled=true;post('ss_un_count',{pid:sel.value}).then(function(r){if(!r.success){m.className='msg er';m.textContent=r.data||'Error';return}
  if(r.data.wait){m.className='msg er';m.textContent='Already sent this week — next possible on '+r.data.wait+'.';return}
  if(!r.data.buyers){m.textContent='No buyers yet for this product.';return}
  m.textContent=r.data.buyers+' buyer'+(r.data.buyers>1?'s':'')+' will get the email.';bt.disabled=false}).catch(function(){m.className='msg er';m.textContent='Could not count — try again.'})}
 sel.onchange=count;count();
 bt.onclick=function(){if(ta.value.trim().length<10){m.className='msg er';m.textContent='Please write in a sentence what changed.';ta.focus();return}
  if(!confirm('Send the update email to the buyers of “'+sel.options[sel.selectedIndex].text+'”?'))return;bt.disabled=true;
  post('ss_un_send',{pid:sel.value,note:ta.value}).then(function(r){if(r.success){m.className='msg ok';m.textContent='Sending to '+r.data.buyers+' buyer'+(r.data.buyers>1?'s':'')+' — done within a few minutes.';ta.value=''}else{m.className='msg er';m.textContent=r.data||'Error';bt.disabled=false}}).catch(function(){m.className='msg er';m.textContent='Could not send — try again.';bt.disabled=false})};
 return true}
if(!put()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(put())mo.disconnect()})});mo.observe(document.body,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},15000)}
})();</script>
	<?php
}, 70 );
