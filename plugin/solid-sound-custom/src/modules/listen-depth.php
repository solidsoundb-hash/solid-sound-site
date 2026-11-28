<?php
/**
 * Where listeners stop (creator tool from the handover: «аналитика прослушиваний — где уходят с превью»).
 * The site player (theme/player.js exposes window.ssPlayer) reports, once per play, how far the preview was heard (0–100 %).
 * No personal data: product id + one number. Stored per product in _ss_ld = { n: plays measured, b: [10 counts], s: sum of % }.
 * On the creator's Overview: every product with plays, average % heard, a 10-step "still listening" bar and one tip.
 * 28.11.2026 (Analyst via «Задачи»): only people count — the same rule as ss_stats (a human sign in this tab = sessionStorage
 * «ssH», server skips robots/crawlers/admins via ss_stats_is_bot); per-day copy in option ss_ld_days {day: {pid: {n,s,b,a}}}
 * (last 60 days); «a» = the add-to-cart button of a product pressed within 30 min after its demo was started in this tab
 * (sessionStorage «ssDP», no cookies). Read by the Analyst via /wp-json/ss/v1/stats → «listen».
 * Customer-facing texts in English.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ---------- 1. collect (every page with the player) ---------- */
add_action( 'wp_footer', function () {
	if ( is_admin() ) return; ?>
<script id="ss-ld-js">(function(){var A=null,pid=null,max=0,dur=0,sent=true,url=<?php echo wp_json_encode( admin_url( 'admin-ajax.php?action=ss_ld' ) ); ?>;
function hu(){try{return !!sessionStorage.getItem('ssH')}catch(e){return false}}
function send(){if(sent||!pid||!dur||max<2)return;sent=true;if(!hu())return;var p=Math.max(0,Math.min(100,Math.round(max/dur*100)));var d=new FormData();d.append('pid',pid);d.append('p',p);d.append('d',Math.round(dur));d.append('h','1');try{navigator.sendBeacon(url,d)}catch(e){}}
function dp(){try{return JSON.parse(sessionStorage.getItem('ssDP')||'{}')}catch(e){return {}}}
document.addEventListener('click',function(e){var b=e.target.closest&&e.target.closest('.single_add_to_cart_button,button[name="add-to-cart"],.add_to_cart_button');if(!b||!hu())return;
 var f=b.closest('form'),id=b.getAttribute('data-product_id')||(b.name==='add-to-cart'?b.value:'')||(f&&f.querySelector('[name="add-to-cart"]')?f.querySelector('[name="add-to-cart"]').value:'')||((document.body.className.match(/postid-(\d+)/)||[])[1]||'');
 var m=dp();if(!id||!m[id]||Date.now()-m[id]>18e5||m[id]<0)return;m[id]=-1;try{sessionStorage.setItem('ssDP',JSON.stringify(m))}catch(x){}
 var d=new FormData();d.append('pid',id);d.append('a','1');d.append('h','1');try{navigator.sendBeacon(url,d)}catch(x){}},true);
function hook(){var S=window.ssPlayer;if(!S||!S.audio)return false;A=S.audio;
 A.addEventListener('play',function(){var id=S.pid();if(id!==pid){send();pid=id;max=A.currentTime||0;sent=false}var m=dp();if(id&&!(m[id]<0)){m[id]=Date.now();try{sessionStorage.setItem('ssDP',JSON.stringify(m))}catch(x){}}});
 A.addEventListener('timeupdate',function(){if(A.duration&&isFinite(A.duration))dur=A.duration;if(A.currentTime>max)max=A.currentTime});
 A.addEventListener('ended',function(){max=dur||max;send()});
 window.addEventListener('pagehide',send);document.addEventListener('visibilitychange',function(){if(document.visibilityState==='hidden')send()});return true}
if(!hook()){if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',hook);else window.addEventListener('load',hook)} /* the player script is deferred: it is ready by DOMContentLoaded */
})();</script>
	<?php
}, 99 );

function ss_ld_record() {
	$pid = isset( $_POST['pid'] ) ? absint( $_POST['pid'] ) : 0;
	$p   = isset( $_POST['p'] ) ? max( 0, min( 100, (int) $_POST['p'] ) ) : -1;
	$add = ! empty( $_POST['a'] );
	if ( empty( $_POST['h'] ) || current_user_can( 'manage_options' ) || ( function_exists( 'ss_stats_is_bot' ) && ss_stats_is_bot() ) ) wp_die( '', '', array( 'response' => 204 ) ); /* people only */
	if ( ! $pid || ( ! $add && $p < 0 ) || 'product' !== get_post_type( $pid ) ) wp_die( '', '', array( 'response' => 204 ) );
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	if ( $add ) { /* add to cart after the demo: once per visitor and product per 30 min */
		$ak = 'ss_lda_' . md5( $ip . '|' . $pid );
		if ( ! get_transient( $ak ) ) { set_transient( $ak, 1, 30 * MINUTE_IN_SECONDS ); ss_ld_day( $pid, -1 ); }
		wp_die( '', '', array( 'response' => 204 ) );
	}
	$rk = 'ss_ld_' . md5( $ip . '|' . $pid );
	if ( get_transient( $rk ) ) wp_die( '', '', array( 'response' => 204 ) ); /* one count per visitor and product per 10 min */
	set_transient( $rk, 1, 10 * MINUTE_IN_SECONDS );
	$m = get_post_meta( $pid, '_ss_ld', true );
	if ( ! is_array( $m ) || empty( $m['b'] ) ) $m = array( 'n' => 0, 's' => 0, 'b' => array_fill( 0, 10, 0 ), 'd' => 0 );
	$m['n']++; $m['s'] += $p;
	for ( $i = 0; $i < 10; $i++ ) if ( $p >= ( $i + 1 ) * 10 ) $m['b'][ $i ]++; /* b[i] = still listening at (i+1)·10 % */
	if ( ! empty( $_POST['d'] ) ) $m['d'] = (int) $_POST['d'];
	update_post_meta( $pid, '_ss_ld', $m );
	ss_ld_day( $pid, $p );
	wp_die( '', '', array( 'response' => 204 ) );
}
/* per-day copy: $p = % heard, or -1 = add to cart after the demo */
function ss_ld_day( $pid, $p ) {
	$all = get_option( 'ss_ld_days', array() ); $all = is_array( $all ) ? $all : array();
	$day = gmdate( 'Y-m-d' ); $k = (string) $pid;
	$r = isset( $all[ $day ][ $k ] ) ? $all[ $day ][ $k ] : array( 'n' => 0, 's' => 0, 'b' => array_fill( 0, 10, 0 ), 'a' => 0 );
	if ( $p < 0 ) $r['a']++;
	else { $r['n']++; $r['s'] += $p; for ( $i = 0; $i < 10; $i++ ) if ( $p >= ( $i + 1 ) * 10 ) $r['b'][ $i ]++; }
	$all[ $day ][ $k ] = $r; ksort( $all ); $all = array_slice( $all, -60, null, true );
	update_option( 'ss_ld_days', $all, false );
}
add_action( 'wp_ajax_ss_ld', 'ss_ld_record' );
add_action( 'wp_ajax_nopriv_ss_ld', 'ss_ld_record' );

/* ---------- 2. show (creator Overview) ---------- */
add_action( 'wp_footer', function () {
	if ( ! function_exists( 'ss_cs_ok' ) || ! ss_cs_ok() || ! function_exists( 'ss_cs_is_root' ) || ! ss_cs_is_root() ) return;
	$ids = get_posts( array( 'post_type' => 'product', 'post_status' => array( 'publish', 'pending', 'draft' ), 'author' => get_current_user_id(), 'numberposts' => 60, 'fields' => 'ids' ) );
	$rows = array();
	foreach ( $ids as $id ) {
		$m = get_post_meta( $id, '_ss_ld', true );
		if ( ! is_array( $m ) || empty( $m['n'] ) ) continue;
		$curve = array(); for ( $i = 0; $i < 10; $i++ ) $curve[] = round( 100 * $m['b'][ $i ] / $m['n'] );
		$drop = 0; $dv = -1; $prev = 100; /* the biggest single step down = where most people leave */
		foreach ( $curve as $i => $v ) { if ( $prev - $v > $dv ) { $dv = $prev - $v; $drop = $i; } $prev = $v; }
		$rows[] = array( 't' => html_entity_decode( get_the_title( $id ) ), 'u' => get_permalink( $id ), 'n' => (int) $m['n'], 'avg' => round( $m['s'] / $m['n'] ), 'c' => $curve, 'drop' => $drop, 'd' => (int) ( $m['d'] ?? 0 ) );
	}
	usort( $rows, function ( $a, $b ) { return $b['n'] - $a['n']; } );
	?>
<style id="ss-ld-css">.ss-ld{margin:18px 0;padding:18px;border-radius:16px;background:#0f1729;border:1px solid rgba(255,255,255,.08);font-family:"Exo 2",system-ui,sans-serif}
.ss-ld h3{margin:0 0 4px;color:#fff;font-size:19px;font-weight:800}.ss-ld>p{margin:0 0 12px;color:#94A3B8;font-size:14px}
.ss-ld .r{display:grid;grid-template-columns:minmax(0,1.4fr) 70px 70px minmax(0,1.6fr);gap:6px 14px;align-items:center;padding:10px 0;border-top:1px solid rgba(255,255,255,.06)}
.ss-ld .r a{color:#fff;font-weight:700;text-decoration:none;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.ss-ld .r span{color:#CBD5E1;font-size:14px}.ss-ld .r span b{display:block;color:#64748B;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.05em}
.ss-ld .cv{display:flex;align-items:flex-end;gap:3px;height:34px}.ss-ld .cv i{flex:1;background:#F59E0B;border-radius:2px 2px 0 0;min-height:2px}.ss-ld .cv i.dr{background:#F87171}
.ss-ld .tip{grid-column:1/-1;margin:0;color:#FDE68A;font-size:13.5px}.ss-ld .no{color:#94A3B8;font-size:14px;margin:0}
@media(max-width:640px){.ss-ld .r{grid-template-columns:1fr 1fr}.ss-ld .r a{grid-column:1/-1}.ss-ld .cv{grid-column:1/-1}}</style>
<script id="ss-ld-view">(function(){var R=<?php echo wp_json_encode( $rows ); ?>;
function esc(s){return String(s).replace(/[&<>"]/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]})}
function mmss(s){s=Math.round(s);return Math.floor(s/60)+':'+('0'+s%60).slice(-2)}
function put(){var host=document.querySelector('#wcfm-content .wcfm-collapse')||document.querySelector('#wcfm-content');if(!host||document.querySelector('.ss-ld'))return !!host;
 var h='<h3>Where listeners stop</h3><p>How far people listen to your previews. Bars = share still listening at 10 %, 20 % … 100 % of the preview; red = where most leave.</p>';
 if(!R.length)h+='<p class="no">No data yet — it appears after the first plays of your previews.</p>';
 R.forEach(function(r){var at=r.d?mmss(r.d*(r.drop+1)/10):((r.drop+1)*10)+' %';
  var tip=r.drop<=1?'Most people leave in the first seconds — start the preview with the drop or the hook, not the intro.':r.drop<=4?'Many leave around '+at+' — move the strongest part earlier or shorten the build-up.':'Good: most people listen past the middle.';
  h+='<div class="r"><a href="'+esc(r.u)+'">'+esc(r.t)+'</a><span><b>Plays</b>'+r.n+'</span><span><b>Heard</b>'+r.avg+' %</span><div class="cv" aria-label="Still listening">'+r.c.map(function(v,i){return '<i'+(i===r.drop?' class="dr"':'')+' style="height:'+Math.max(3,v)+'%" title="'+((i+1)*10)+' %: '+v+' % still listening"></i>'}).join('')+'</div><p class="tip">'+tip+'</p></div>'});
 var d=document.createElement('section');d.className='ss-ld';d.innerHTML=h;host.appendChild(d);return true}
if(!put()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(put())mo.disconnect()})});mo.observe(document.body,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},15000)}
})();</script>
	<?php
}, 70 );
