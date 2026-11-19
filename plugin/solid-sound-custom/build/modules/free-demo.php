<?php
/**
 * (15.11.2026 owner) "✦ Try it free for your email" on every product, like the home page cards:
 * catalog rows, the all-products carousel and the product page. The visitor gets THAT product, free, for the email
 * (owner): an e-mail with a signed download link (7 days) is sent at once; the person becomes a customer + subscriber.
 * The home page's "Download demo" button (it did nothing before) is wired to the same handler.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ---------- server: email -> signed download link for that product (7 days) ---------- */
function ss_fd_sig( $email, $pid, $exp ) { return hash_hmac( 'sha256', strtolower( $email ) . '|' . (int) $pid . '|' . (int) $exp, wp_salt( 'auth' ) . 'ss_fd' ); }

function ss_fd_get() {
	if ( ! empty( $_POST['website'] ) ) wp_send_json_success(); /* honeypot */
	if ( (int) ( $_POST['el'] ?? 99 ) < 2 ) wp_send_json_success(); /* faster than a human */
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$rk = 'ss_fd_rl_' . md5( $ip ); $n = (int) get_transient( $rk );
	if ( $n >= 6 ) wp_send_json_error( array( 'm' => 'rate' ), 429 );
	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$pid   = absint( $_POST['pid'] ?? 0 );
	$p     = $pid ? wc_get_product( $pid ) : null;
	$consent = ! empty( $_POST['consent'] );
	if ( ! is_email( $email ) ) wp_send_json_error( array( 'm' => 'invalid' ), 400 );
	if ( ! $p || 'publish' !== get_post_status( $pid ) || ! $p->get_downloads() ) wp_send_json_error( array( 'm' => 'nofile' ), 404 );
	set_transient( $rk, $n + 1, HOUR_IN_SECONDS );
	/* the person becomes a customer + subscriber, like the Free page */
	$uid = email_exists( $email );
	if ( ! $uid ) {
		$login = sanitize_user( current( explode( '@', $email ) ), true );
		if ( '' === $login || username_exists( $login ) ) $login .= wp_rand( 100, 9999 );
		$uid = wp_insert_user( array( 'user_login' => $login, 'user_email' => $email, 'user_pass' => wp_generate_password( 20 ), 'role' => get_role( 'customer' ) ? 'customer' : 'subscriber' ) );
		if ( is_wp_error( $uid ) ) wp_send_json_error( array( 'm' => 'user' ), 500 );
		update_user_meta( $uid, 'ss_signup_source', 'try_free' );
	}
	/* subscribers: Brevo list "Free Pack" + welcome series (custom.php, triggered by ss_free_optin);
	   its first e-mail ("free downloads unlocked") is skipped — this person gets the product e-mail below instead */
	if ( $consent && ! get_user_meta( $uid, 'ss_unsub', true ) ) {
		if ( ! get_user_meta( $uid, 'ss_drip_1', true ) ) update_user_meta( $uid, 'ss_drip_1', 'skipped: try_free' );
		if ( ! get_user_meta( $uid, 'ss_free_optin', true ) ) update_user_meta( $uid, 'ss_free_optin', current_time( 'mysql' ) );
	}
	$got = (array) get_user_meta( $uid, 'ss_fd_got', true ); $got[ $pid ] = current_time( 'mysql' ); update_user_meta( $uid, 'ss_fd_got', $got );
	update_post_meta( $pid, 'ss_fd_requests', (int) get_post_meta( $pid, 'ss_fd_requests', true ) + 1 );
	$exp  = time() + 7 * DAY_IN_SECONDS;
	$base = add_query_arg( array( 'action' => 'ss_fd_dl', 'pid' => $pid, 'e' => rawurlencode( base64_encode( strtolower( $email ) ) ), 'x' => $exp, 's' => ss_fd_sig( $email, $pid, $exp ) ), admin_url( 'admin-ajax.php' ) );
	$name = $p->get_name(); $btns = ''; $i = 0;
	$img  = get_the_post_thumbnail_url( $pid, 'woocommerce_single' );
	$fmt  = wp_get_post_terms( $pid, 'product-format', array( 'fields' => 'slugs' ) );
	$tpl  = ! is_wp_error( $fmt ) && array_intersect( array( 'templates', 'reconstructed-templates' ), $fmt );
	$files = $p->get_downloads();
	foreach ( $files as $f ) {
		$lbl = count( $files ) > 1 ? 'Download ' . ( $f->get_name() ? $f->get_name() : $name ) : 'Download ' . $name;
		$btns .= '<tr><td style="padding:0 0 10px"><a href="' . esc_url( add_query_arg( 'f', $i, $base ) ) . '" style="display:block;background:#F59E0B;color:#111111;text-decoration:none;font-weight:700;font-size:16px;padding:15px 20px;border-radius:10px;text-align:center">&#8681;&nbsp; ' . esc_html( $lbl ) . '</a></td></tr>';
		$i++;
	}
	$tips = $tpl
		? array( 'Unzip and open the <b>.als</b> file in Ableton Live.', 'Solo channels to hear how each part is built.', 'Royalty-free: use it in your own releases.' )
		: array( 'Unzip and load the files in your DAW or synth.', 'Try them in your own track right away.', 'Royalty-free: use them in your own releases.' );
	$tl = ''; foreach ( $tips as $n => $t ) $tl .= '<tr><td style="width:26px;vertical-align:top;padding:0 0 8px"><span style="display:inline-block;width:20px;height:20px;border-radius:10px;background:#2a2116;color:#F59E0B;font-size:12px;font-weight:700;text-align:center;line-height:20px">' . ( $n + 1 ) . '</span></td><td style="font-size:14px;line-height:20px;color:#cbd5e1;padding:0 0 8px">' . $t . '</td></tr>';
	$unsub = ( $consent && function_exists( 'ss_unsub_link' ) ) ? ' · <a style="color:#94a3b8" href="' . esc_url( ss_unsub_link( $uid ) ) . '">Unsubscribe</a>' : '';
	$body = '<!doctype html><html><body style="margin:0;padding:0;background:#0b0f19">'
		. '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#0b0f19;padding:28px 12px;font-family:Arial,Helvetica,sans-serif"><tr><td align="center">'
		. '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px">'
		. '<tr><td style="padding:0 0 18px" align="center"><a href="' . esc_url( home_url( '/en/' ) ) . '"><img src="' . esc_url( home_url( '/wp-content/uploads/logo.png' ) ) . '" width="120" alt="Solid Sound" style="display:block;border:0;width:120px;height:auto"></a></td></tr>'
		. '<tr><td style="background:#131b2e;border:1px solid #243049;border-radius:16px;padding:28px">'
		. '<table role="presentation" width="100%" cellspacing="0" cellpadding="0">'
		. '<tr><td style="font-size:12px;font-weight:700;letter-spacing:2px;color:#34D399;padding:0 0 10px">YOUR FREE DOWNLOAD</td></tr>'
		. '<tr><td style="font-size:24px;line-height:30px;font-weight:700;color:#ffffff;padding:0 0 18px">' . esc_html( $name ) . ' is yours</td></tr>'
		. ( $img ? '<tr><td style="padding:0 0 20px"><img src="' . esc_url( $img ) . '" width="504" alt="' . esc_attr( $name ) . '" style="display:block;width:100%;max-width:504px;height:auto;border-radius:12px;border:0"></td></tr>' : '' )
		. $btns
		. '<tr><td style="font-size:12px;color:#94a3b8;padding:2px 0 22px;text-align:center">The link works for 7 days.</td></tr>'
		. '<tr><td style="border-top:1px solid #243049;padding:20px 0 6px;font-size:13px;font-weight:700;letter-spacing:1px;color:#ffffff">HOW TO START</td></tr>'
		. '<tr><td><table role="presentation" width="100%" cellspacing="0" cellpadding="0">' . $tl . '</table></td></tr>'
		. '<tr><td style="border-top:1px solid #243049;padding:18px 0 0;font-size:14px;line-height:21px;color:#cbd5e1">Want the track to sound finished? Our engineers mix and master it for you — <a href="' . esc_url( home_url( '/en/services-prices/' ) ) . '" style="color:#F59E0B;font-weight:700">see prices</a>. More templates and presets: <a href="' . esc_url( home_url( '/en/all-products/' ) ) . '" style="color:#F59E0B;font-weight:700">all products</a>.</td></tr>'
		. '</table></td></tr>'
		. '<tr><td style="padding:16px 8px 0;text-align:center;font-size:12px;line-height:18px;color:#64748b">You get this e-mail because you asked for a free product on solid-sound.net.<br><a style="color:#94a3b8" href="' . esc_url( home_url( '/en/' ) ) . '">solid-sound.net</a>' . $unsub . '</td></tr>'
		. '</table></td></tr></table></body></html>';
	$h = array( 'Content-Type: text/html; charset=UTF-8' );
	if ( $unsub ) $h[] = 'List-Unsubscribe: <' . ss_unsub_link( $uid ) . '>';
	if ( function_exists( 'ss_reply_email' ) ) $h[] = 'Reply-To: Solid Sound <' . ss_reply_email() . '>';
	$ok = wp_mail( $email, 'Your free ' . $name . ' — download inside', $body, $h );
	if ( ! $ok ) wp_send_json_error( array( 'm' => 'mail' ), 500 );
	do_action( 'ss_fd_requested', $uid, $pid, $email );
	wp_send_json_success();
}
add_action( 'wp_ajax_ss_fd_get', 'ss_fd_get' );
add_action( 'wp_ajax_nopriv_ss_fd_get', 'ss_fd_get' );

function ss_fd_dl() {
	$pid = absint( $_GET['pid'] ?? 0 ); $exp = (int) ( $_GET['x'] ?? 0 ); $f = (int) ( $_GET['f'] ?? 0 );
	$email = base64_decode( (string) wp_unslash( $_GET['e'] ?? '' ) );
	$sig = (string) ( $_GET['s'] ?? '' );
	if ( ! $pid || ! is_email( $email ) || ! hash_equals( ss_fd_sig( $email, $pid, $exp ), $sig ) ) wp_die( 'This link is not valid.', '', array( 'response' => 403 ) );
	if ( $exp < time() ) wp_die( 'This link has expired. Ask for the free product again on solid-sound.net.', '', array( 'response' => 410 ) );
	$p = wc_get_product( $pid ); $files = $p ? array_values( $p->get_downloads() ) : array();
	if ( empty( $files[ $f ] ) ) wp_die( 'File not found.', '', array( 'response' => 404 ) );
	update_post_meta( $pid, 'ss_fd_downloads', (int) get_post_meta( $pid, 'ss_fd_downloads', true ) + 1 );
	if ( class_exists( 'WC_Download_Handler' ) ) { WC_Download_Handler::download( $files[ $f ]->get_file(), $pid ); exit; }
	wp_redirect( $files[ $f ]->get_file() ); exit;
}
add_action( 'wp_ajax_ss_fd_dl', 'ss_fd_dl' );
add_action( 'wp_ajax_nopriv_ss_fd_dl', 'ss_fd_dl' );

add_action( 'wp_footer', function () {
	if ( ! get_option( 'ss_fd_on' ) ) return; /* owner 19.11: «трай фор фри убери» — the button is hidden everywhere (server part kept; option ss_fd_on=1 brings it back) */
	if ( is_admin() || ( function_exists( 'is_checkout' ) && is_checkout() ) || ( function_exists( 'is_cart' ) && is_cart() ) ) return;
	if ( function_exists( 'is_wcfm_page' ) && is_wcfm_page() ) return; /* 15.11: not in the creator area (browser Out of Memory) */
	$single = '';
	if ( function_exists( 'is_product' ) && is_product() ) {
		$id = get_queried_object_id();
		$t  = wp_get_post_terms( $id, 'product-format', array( 'fields' => 'slugs' ) );
		$single = ( ! is_wp_error( $t ) && array_intersect( array( 'templates', 'reconstructed-templates' ), $t ) ) ? 'templates' : ( ( ! is_wp_error( $t ) && in_array( 'presets', $t, true ) ) ? 'presets' : 'music' );
	}
	?>
<style>
.ss-fd-l{display:inline-flex!important;align-items:center;gap:6px;margin:0!important;padding:0!important;border:0!important;background:none!important;color:#34d399!important;font:700 13.5px/1.3 Roboto,sans-serif!important;cursor:pointer;text-decoration:none!important}
.ss-fd-l:hover{color:#6ee7b7!important;text-decoration:underline!important}
.w-grid.layout_195 .w-grid-item-h>.ss-fd-l{grid-column:2/-1;justify-self:start}
@media(max-width:767px){.w-grid.layout_195 .w-grid-item-h>.ss-fd-l{grid-column:1/-1}}
.ssh .ssh-buyb .ss-fd-l{justify-content:center}
.single-product .ss-fd-l{margin:12px 0 0!important}
.ss-fd{position:fixed;inset:0;z-index:100050;display:flex;align-items:center;justify-content:center;padding:16px;background:rgba(0,0,0,.6)}
.ss-fd[hidden]{display:none}
.ss-fd-b{position:relative;width:100%;max-width:400px;padding:22px;border-radius:14px;background:#131b2e;border:1px solid rgba(255,255,255,.1);box-shadow:0 20px 50px rgba(0,0,0,.6);color:#e2e8f0;font-family:Roboto,sans-serif;display:flex;flex-direction:column;gap:10px}
.ss-fd-k{font-size:11px;font-weight:700;letter-spacing:.12em;color:#34d399}
.ss-fd-b b{font-size:19px;line-height:1.25;color:#fff}
.ss-fd-b p{margin:0;font-size:14px;line-height:1.5;color:#cbd5e1}
.ss-fd-b label{font-size:12px;color:#94a3b8;margin-top:2px}
.ss-fd-b input[type=email]{height:46px;padding:0 12px;border-radius:9px;border:1px solid rgba(255,255,255,.14);background:#0b1220;color:#fff;font-size:16px}
.ss-fd-b input[type=email]:focus{outline:none;border-color:#34d399}
.ss-fd-s{min-height:46px;border:0;border-radius:9px;background:#34d399;color:#07130d;font:800 15px Roboto,sans-serif;cursor:pointer}
.ss-fd-s:disabled{opacity:.6}
.ss-fd-x{position:absolute;top:10px;right:10px;width:36px;height:36px;border:0;border-radius:8px;background:none;color:#94a3b8;font-size:22px;line-height:1;cursor:pointer}
.ss-fd-m{min-height:18px;font-size:13px}.ss-fd-m.ok{color:#34d399}.ss-fd-m.bad{color:#f87171}
.ss-fd-c{display:flex!important;gap:9px;align-items:flex-start;font-size:12.5px!important;line-height:1.45;color:#94a3b8!important;cursor:pointer}.ss-fd-c input{accent-color:#34d399;width:16px;height:16px;margin:1px 0 0;flex:none}
.ss-fd-hp{position:absolute!important;left:-9999px!important;width:1px;height:1px;opacity:0}
</style>
<script>(function(){
var AJAX=<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>,SINGLE=<?php echo wp_json_encode( $single ); ?>,PID=<?php echo (int) ( $single ? get_queried_object_id() : 0 ); ?>;
var TXT={templates:['free template','Get the full Ableton project %s free. Enter your email — we’ll send the download link right away.'],presets:['free presets','Get %s free. Enter your email — we’ll send the download link right away.'],music:['free track','Get %s free. Enter your email — we’ll send the download link right away.']};
var dlg,cur={},opened=0;
function build(){
  dlg=document.createElement('div');dlg.className='ss-fd';dlg.hidden=true;
  dlg.innerHTML='<div class="ss-fd-b" role="dialog" aria-modal="true" aria-labelledby="ss-fd-t"><button type="button" class="ss-fd-x" aria-label="Close">×</button><span class="ss-fd-k">FREE FOR YOUR EMAIL</span><b id="ss-fd-t"></b><p></p><label for="ss-fd-e">Email</label><input id="ss-fd-e" type="email" autocomplete="email" inputmode="email" placeholder="you@example.com" required><label class="ss-fd-c"><input type="checkbox" checked><span>Send me new releases and offers too (max 2 emails a month, unsubscribe anytime).</span></label><input class="ss-fd-hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true"><button type="button" class="ss-fd-s">Send me the download link</button><span class="ss-fd-m" aria-live="polite"></span></div>';
  document.body.appendChild(dlg);
  dlg.addEventListener('click',function(e){if(e.target===dlg||e.target.closest('.ss-fd-x'))close()});
  document.addEventListener('keydown',function(e){if(e.key==='Escape'&&!dlg.hidden)close()});
  dlg.querySelector('.ss-fd-s').addEventListener('click',send);
  dlg.querySelector('#ss-fd-e').addEventListener('keydown',function(e){if(e.key==='Enter')send()});
}
function open(name,type,url,pid){
  if(!dlg)build();cur={name:name,type:TXT[type]?type:'music',url:url||location.href,pid:pid};opened=Date.now();
  var t=TXT[cur.type];dlg.querySelector('b').textContent=name+' — free';dlg.querySelector('p').textContent=t[1].replace('%s',name);
  var m=dlg.querySelector('.ss-fd-m');m.textContent='';m.className='ss-fd-m';var s=dlg.querySelector('.ss-fd-s');s.disabled=false;s.hidden=false;
  dlg.hidden=false;document.documentElement.style.overflow='hidden';setTimeout(function(){dlg.querySelector('#ss-fd-e').focus()},30);
}
function close(){dlg.hidden=true;document.documentElement.style.overflow=''}
function post(email,pid,consent,el,hp){
  var f=new FormData();f.append('action','ss_fd_get');f.append('email',email);f.append('pid',String(pid||''));f.append('consent',consent?'1':'');f.append('el',String(el));f.append('website',hp||'');
  return fetch(AJAX,{method:'POST',credentials:'same-origin',body:f}).then(function(r){return r.json().catch(function(){return {success:r.ok}})});
}
function errTxt(j){var m=j&&j.data&&j.data.m;return m==='rate'?'Too many requests — please try again later.':(m==='invalid'?'Please enter a valid email.':(m==='nofile'?'This product can’t be sent automatically — please message us.':'Something went wrong — please try again.'))}
function send(){
  var e=dlg.querySelector('#ss-fd-e'),m=dlg.querySelector('.ss-fd-m'),s=dlg.querySelector('.ss-fd-s');
  if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e.value.trim())){m.textContent='Please enter a valid email.';m.className='ss-fd-m bad';e.focus();return}
  s.disabled=true;m.textContent='Sending…';m.className='ss-fd-m';
  post(e.value.trim(),cur.pid,dlg.querySelector('.ss-fd-c input').checked,Math.round((Date.now()-opened)/1000),dlg.querySelector('.ss-fd-hp').value).then(function(j){
    if(j&&j.success){m.textContent='✓ Done! Check your inbox — the download link for '+cur.name+' is on its way (look in Spam too).';m.className='ss-fd-m ok';s.hidden=true}
    else{s.disabled=false;m.textContent=errTxt(j);m.className='ss-fd-m bad'}
  }).catch(function(){s.disabled=false;m.textContent='Something went wrong — please try again.';m.className='ss-fd-m bad'});
}
function link(tag){var a=document.createElement(tag||'button');if(!tag||tag==='button')a.type='button';else{a.setAttribute('role','button');a.tabIndex=0}a.className='ss-fd-l';a.textContent='✦ Try it free for your email';return a}
function typeOf(cls){return /templates/.test(cls)?'templates':(/presets/.test(cls)?'presets':'music')}
function run(){
  /* (owner 15.11) not on catalog / carousel cards any more — only on the product page and the home cards */
  if(SINGLE){var f=document.querySelector('form.cart');var row=f&&(f.closest('.ss-buyrow')||f);if(row&&!document.querySelector('.single-product .ss-fd-l')){var l=link();l.dataset.n=((document.querySelector('.product_title,h1')||{}).textContent||'').trim();l.dataset.t=SINGLE;l.dataset.u=location.href.split('#')[0];l.dataset.p=PID;row.parentNode.insertBefore(l,row.nextSibling)}}
}
function act(e){
  var l=e.target.closest&&e.target.closest('.ss-fd-l');if(!l)return;
  if(e.type==='keydown'&&e.key!=='Enter'&&e.key!==' ')return;
  e.preventDefault();e.stopPropagation();open(l.dataset.n||'this product',l.dataset.t,l.dataset.u,l.dataset.p);
}
window.addEventListener('click',act,true);window.addEventListener('keydown',act,true);
/* home page cards: "Download demo" on the back side */
window.addEventListener('click',function(e){
  var b=e.target.closest&&e.target.closest('#ss-app button');if(!b||!/^\s*(Download demo|Send me the download link)\s*$/.test(b.textContent))return;
  var box=b.parentNode,inp=box.querySelector('input[type=email]'),hd=box.querySelector('b');if(!inp)return;
  var name=hd?hd.textContent.split(' — ')[0].trim():'product',typ=/presets/i.test(hd?hd.textContent:'')?'presets':(/lite demo/i.test(hd?hd.textContent:'')?'templates':'music');
  var msg=box.querySelector('.ss-fd-m');if(!msg){msg=document.createElement('span');msg.className='ss-fd-m';msg.setAttribute('aria-live','polite');b.parentNode.insertBefore(msg,b.nextSibling)}
  if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test((inp.value||'').trim())){msg.textContent='Please enter a valid email.';msg.className='ss-fd-m bad';inp.focus();return}
  b.disabled=true;msg.textContent='Sending…';msg.className='ss-fd-m';
  var art=b.closest('article');post(inp.value.trim(),art&&art.getAttribute('data-pid'),true,10,'').then(function(j){if(j&&j.success){msg.textContent='✓ Done! Check your inbox — the download link is on its way.';msg.className='ss-fd-m ok';b.hidden=true}else{b.disabled=false;msg.textContent=errTxt(j);msg.className='ss-fd-m bad'}}).catch(function(){b.disabled=false;msg.textContent='Something went wrong — please try again.';msg.className='ss-fd-m bad'});
},true);
run();var q=0;new MutationObserver(function(){if(q)return;q=requestAnimationFrame(function(){q=0;run()})}).observe(document.body,{childList:true,subtree:true});
})();</script>
	<?php
}, 50 );
