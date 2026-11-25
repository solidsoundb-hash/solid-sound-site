<?php
/**
 * (19.11.2026 owner: «пройди все вкладки автора … что улучшить … как конкуренты, сделай ещё лучше»)
 * Creator sub-tabs (Products, Earnings, Payouts, Reviews, Profile, Help — rendered by ss_cp_render() in custom.php)
 * get the same look as the Creator space overview: page title + one-line subtitle + main action on the right,
 * one empty state for products, a clear payout status (method, balance to €25, next payout date),
 * phone-friendly product list, live preview on Profile, search in Help. Overview and Add product are not touched here.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_ct_tab() {
	global $wp;
	foreach ( array_keys( (array) ( $wp->query_vars ?? array() ) ) as $k ) if ( preg_match( '/^wcfm-(ss-(products|earnings|payouts|reviews|settings|help))$/', $k, $m ) ) return $m[1];
	return '';
}
function ss_ct_head( $s ) {
	$add = function_exists( 'ss_ep_url' ) ? ss_ep_url() : ss_cp_url( 'ss-add-product' );
	$pub = '';
	if ( function_exists( 'wcfm_get_option' ) ) { $u = wp_get_current_user(); $pub = home_url( '/' . wcfm_get_option( 'wcfm_store_url', 'store' ) . '/' . $u->user_nicename . '/' ); }
	$H = array(
		'ss-products' => array( 'My products', 'Everything you sell, with its status and what it earned.', array( 'Add product', $add, 'a' ) ),
		'ss-earnings' => array( 'Earnings', 'Your share of every sale, month by month.', array( 'Payout settings', ss_cp_url( 'ss-payouts' ), '' ) ),
		'ss-payouts'  => array( 'Payouts', 'Payouts start once our payment setup for creators is live — we will email you.', null ),
		'ss-reviews'  => array( 'Reviews', 'What buyers say about your products. Reply to build trust.', null ),
		'ss-settings' => array( 'Profile', 'Your public page: photo, cover, bio and where to listen.', $pub ? array( 'View my page ↗', $pub, '' ) : null ),
		'ss-help'     => array( 'Help', 'Short answers about selling on Solid Sound.', array( 'Ask the team', '#chat', '' ) ),
	);
	if ( ! isset( $H[ $s ] ) ) return '';
	$h = $H[ $s ];
	$btn = $h[2] ? '<a class="sscp-tb' . ( 'a' === $h[2][2] ? ' a' : '' ) . '" href="' . esc_url( $h[2][1] ) . '"' . ( '#chat' === $h[2][1] ? ' data-chat="1"' : '' ) . ( 0 === strpos( $h[2][1], 'http' ) && false !== strpos( $h[2][0], '↗' ) ? ' target="_blank" rel="noopener"' : '' ) . '>' . esc_html( $h[2][0] ) . '</a>' : '';
	return '<div class="sscp-top"><div><h1>' . esc_html( $h[0] ) . '</h1><p>' . esc_html( $h[1] ) . '</p></div>' . $btn . '</div>';
}
function ss_ct_empty_products() {
	$add = function_exists( 'ss_ep_url' ) ? ss_ep_url() : ss_cp_url( 'ss-add-product' );
	return '<div class="ssw-card sscp-none sscp-first"><span class="ic">♪</span><b>Your first product starts here</b><p>Templates, presets, sample packs or music — free to list, non-exclusive.</p>'
		. '<ol class="sscp-steps"><li><b>Choose the type</b><span>and upload your ZIP</span></li><li><b>Add a cover and MP3 preview</b><span>buyers listen before they buy</span></li><li><b>We check it</b><span>every product, before it goes live</span></li></ol>'
		. '<a class="sscp-tb a" href="' . esc_url( $add ) . '">Add your first product</a></div>';
}
function ss_ct_payout_status( $id, $mth, $pay ) {
	$st = function_exists( 'ss_cp_stats' ) ? ss_cp_stats( $id ) : array( 'available' => 0 );
	$av = (float) ( $st['available'] ?? 0 );
	/* 25.11 (Юрист/владелец): no payout amounts or dates until creator payments are set up */
	if ( 'paypal' === $mth && ! empty( $pay['paypal']['email'] ) ) {
		$e = $pay['paypal']['email']; $at = strpos( $e, '@' );
		$who = 'PayPal · ' . ( $at > 2 ? substr( $e, 0, 2 ) . str_repeat( '•', max( 1, $at - 2 ) ) . substr( $e, $at ) : $e );
	} elseif ( 'bank_transfer' === $mth && ( ! empty( $pay['bank']['iban'] ) || ! empty( $pay['bank']['ac_number'] ) ) ) {
		$n = preg_replace( '/\s+/', '', (string) ( $pay['bank']['iban'] ?? $pay['bank']['ac_number'] ?? '' ) );
		$who = 'Bank transfer · •••• ' . substr( $n, -4 );
	} else $who = '';
	return '<div class="ssw-card sscp-ps"><div class="m"><span>Payout method</span>' . ( $who ? '<b>' . esc_html( $who ) . ' <i class="ok">Active</i></b>' : '<b class="no">Not set yet <i>You can add one below</i></b>' ) . '</div>'
		. '<div class="m"><span>Earned, not yet paid</span><b>' . wp_kses_post( ss_cp_money( $av ) ) . '</b></div>'
		. '<div class="m"><span>Next payout</span><b>Coming soon</b><small>Payouts start once our payment setup for creators is live — we will email you.</small></div></div>';
}

add_action( 'wp_head', function () {
	if ( ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() || ! ss_ct_tab() ) return; ?>
<style id="ss-ct">
body.ss-cs #wcfm_menu,body.ss-cs .wcfm_menu_items_wrapper,body.ss-cs #wcfm-main-contentainer .wcfm_header_panel{display:none!important}
.sscp-top{display:flex;align-items:flex-end;justify-content:space-between;gap:16px;margin:4px 0 18px;padding:0 4px;font-family:"Exo 2",system-ui,sans-serif}
.sscp-top h1{margin:0;color:#fff;font:800 30px/1.15 "Exo 2",sans-serif;letter-spacing:-.01em}
.sscp-top p{margin:6px 0 0;color:#94A3B8;font:400 15px/1.5 "Exo 2",sans-serif}
.sscp-tb{display:inline-flex;align-items:center;justify-content:center;flex:none;height:44px;padding:0 18px;border-radius:12px;border:1px solid rgba(255,255,255,.14);background:#1A2540;color:#fff!important;font:700 14.5px "Exo 2",sans-serif;text-decoration:none!important;white-space:nowrap}
.sscp-tb:hover{border-color:#F59E0B}.sscp-tb.a{background:#F59E0B;border-color:#F59E0B;color:#111!important}
.sscp{padding:0!important;font-family:"Exo 2",Roboto,system-ui,sans-serif!important}
.sscp .ssw-card{background:#131B2E!important;border:1px solid rgba(255,255,255,.07)!important;border-radius:16px!important;padding:20px!important;margin:0 0 14px!important}
.sscp .ssw-h b,.sscp h3{font-family:"Exo 2",sans-serif!important;font-weight:700!important;color:#fff!important;font-size:17px!important}
.sscp-sub{font-size:14.5px!important;line-height:1.55}
.sscp a:not(.ssw-btn):not(.sscp-tb){color:#F59E0B}
.sscp-none{text-align:center;padding:34px 20px!important}.sscp-none .ic{display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:16px;background:rgba(245,158,11,.12);color:#F59E0B;font-size:26px;margin-bottom:10px}
.sscp-none>b{display:block;color:#fff;font:800 20px "Exo 2",sans-serif;margin:0 0 6px}.sscp-none>p{max-width:520px;margin:0 auto 16px;color:#94A3B8;font-size:15px;line-height:1.55}
.sscp-steps{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;max-width:760px;margin:0 auto 20px;padding:0;list-style:none;counter-reset:s;text-align:left}
.sscp-steps li{counter-increment:s;padding:14px 14px 14px 50px;position:relative;border-radius:12px;background:#0F1626;border:1px solid rgba(255,255,255,.06)}
.sscp-steps li:before{content:counter(s);position:absolute;left:14px;top:14px;width:26px;height:26px;border-radius:50%;background:#F59E0B;color:#111;font:800 13px/26px "Exo 2",sans-serif;text-align:center}
.sscp-steps b{display:block;color:#fff;font-size:14.5px}.sscp-steps span{color:#94A3B8;font-size:13px}
/* products: phone = cards */
.sscp-st.publish{color:#34D399!important}.sscp-st.pending{color:#FBBF24!important}
@media(max-width:760px){
 .sscp-tw{overflow:visible!important;padding:8px!important}.sscp-t thead{display:none}.sscp-t,.sscp-t tbody,.sscp-t tr,.sscp-t td{display:block;width:100%}
 .sscp-t tr{display:grid!important;grid-template-columns:56px 1fr auto;gap:4px 12px;align-items:center;padding:12px;border-bottom:1px solid rgba(255,255,255,.07)}
 .sscp-t td{padding:0!important;border:0!important}.sscp-t td:nth-child(1){grid-row:1 / span 3}.sscp-t td:nth-child(2){grid-column:2 / span 2}
 .sscp-t td:nth-child(3){grid-column:2}.sscp-t td:nth-child(4){grid-column:3;grid-row:2;text-align:right;color:#F59E0B;font-weight:700}
 .sscp-t td:nth-child(5),.sscp-t td:nth-child(6){grid-column:2;font-size:12.5px;color:#94A3B8}.sscp-t td:nth-child(5):before{content:"Sold: "}.sscp-t td:nth-child(6):before{content:"Earned: "}
 .sscp-t td:nth-child(7){grid-column:1 / -1;display:flex!important;gap:8px;margin-top:6px}.sscp-t td:nth-child(7) a{flex:1;text-align:center}
 .sscp-best{grid-template-columns:1fr!important}.sscp-steps{grid-template-columns:1fr}
 .sscp-top{flex-direction:column;align-items:stretch}.sscp-top h1{font-size:26px}.sscp-tb{width:100%}
}
/* payouts */
.sscp-ps{display:grid!important;grid-template-columns:1.4fr 1fr 1fr;gap:18px}.sscp-ps .m>span{display:block;color:#94A3B8;font-size:12.5px;letter-spacing:.06em;text-transform:uppercase;margin-bottom:6px}
.sscp-ps .m b{display:block;color:#fff;font:700 18px "Exo 2",sans-serif}.sscp-ps .m b small{color:#94A3B8;font-weight:400;font-size:13px}.sscp-ps .m>small{color:#94A3B8;font-size:13px}
.sscp-ps i.ok{display:inline-block;margin-left:6px;padding:2px 8px;border-radius:999px;background:rgba(52,211,153,.14);color:#34D399;font:700 11.5px "Exo 2";font-style:normal;vertical-align:2px}
.sscp-ps b.no{color:#FBBF24}.sscp-ps b.no i{display:block;color:#94A3B8;font:400 13px Roboto,sans-serif;font-style:normal;margin-top:2px}
.sscp-ps .bar{display:block;height:6px;margin-top:8px;border-radius:99px;background:rgba(255,255,255,.08);overflow:hidden}.sscp-ps .bar i{display:block;height:100%;background:linear-gradient(90deg,#F59E0B,#FBBF24)}
.sscp-pmc{text-align:left!important;border-radius:14px!important}.sscp-pmc .tag{align-self:flex-start!important}.sscp-pmc h3{font-size:22px!important}
.sscp-pmc.on{border-color:rgba(52,211,153,.5)!important;box-shadow:0 0 0 3px rgba(52,211,153,.1)!important}
.sscp-pmc input{height:46px;border-radius:10px!important;padding:0 14px!important;font-size:15px!important}
.sscp-sel{background:#F59E0B!important;color:#111!important;border-radius:10px!important;height:46px;padding:0 14px!important;font:800 15px "Exo 2",sans-serif!important}
/* earnings: calm cards */
.sscp-c{border:1px solid rgba(255,255,255,.07)!important;border-radius:14px!important;text-align:left!important}.sscp-c b{color:#fff!important;font-family:"Exo 2",sans-serif}.sscp-c.g b{color:#34D399!important}
.sscp-warn{border-radius:14px!important;border-color:rgba(245,158,11,.4)!important}
/* reviews */
.sscp-rsum{display:flex;align-items:baseline;gap:10px;margin:0 0 14px}.sscp-rsum b{color:#fff;font:800 34px "Exo 2",sans-serif}.sscp-rsum .st{color:#F59E0B;font-size:20px}.sscp-rsum em{color:#94A3B8;font-style:normal}
.sscp-rv{background:#0F1626}.sscp-rf textarea{border-radius:10px!important}
/* help */
.sscp-hs{width:100%;height:48px;margin:0 0 14px;padding:0 16px;border-radius:12px;border:1px solid rgba(255,255,255,.12);background:#0F1626;color:#fff;font:400 15px Roboto,sans-serif}
.sscp-hs:focus{outline:0;border-color:#F59E0B}.sscp-faq{border-radius:12px!important;background:#0F1626}.sscp-faq summary{font-family:"Exo 2",sans-serif!important;font-weight:600!important}
/* profile */
.sscp-pg{display:grid;grid-template-columns:minmax(0,1fr) 330px;gap:18px;align-items:start}
.sscp-pv{position:sticky;top:90px}.sscp-pvh{display:flex;justify-content:space-between;align-items:center;margin:0 0 10px}.sscp-pvh b{color:#fff;font:700 15px "Exo 2"}.sscp-pvh a{font-size:13.5px;font-weight:700}
.sscp-pvc{border-radius:16px;overflow:hidden;background:#131B2E;border:1px solid rgba(255,255,255,.08);text-align:center;padding-bottom:16px}
.sscp-pvc .cv{height:110px;background:radial-gradient(80% 120% at 20% 0%,rgba(59,130,246,.45),transparent 60%),radial-gradient(70% 120% at 100% 100%,rgba(245,158,11,.35),transparent 60%),#0e1a36 center/cover no-repeat}
.sscp-pvc .av{width:84px;height:84px;margin:-42px auto 8px;border-radius:50%;border:4px solid #131B2E;background:#1A2540 center/cover no-repeat;display:flex;align-items:center;justify-content:center;color:#F59E0B;font:800 32px "Exo 2"}
.sscp-pvc>b{display:block;color:#fff;font:800 19px "Exo 2";padding:0 14px;overflow-wrap:anywhere}.sscp-pvc .cr{display:inline-block;margin:6px 0 8px;padding:2px 10px;border-radius:999px;background:rgba(245,158,11,.12);color:#FBBF24;font:700 12px "Exo 2"}
.sscp-pvc p{margin:0 16px 10px;color:#CBD5E1;font-size:13.5px;line-height:1.5;text-align:left}.sscp-pvc p:empty:before{content:"Your bio appears here.";color:#64748B}
.sscp-pvc .lk{display:flex;flex-wrap:wrap;gap:6px;justify-content:center;padding:0 12px}.sscp-pvc .lk span{padding:4px 10px;border-radius:8px;background:#1A2540;color:#E2E8F0;font-size:12.5px}
.sscp-set .sscp-up{display:flex;align-items:center;gap:16px}.sscp-set .sscp-up .av{flex:none;width:84px;height:84px;border-radius:50%;background:#1A2540 center/cover no-repeat;display:flex;align-items:center;justify-content:center;color:#F59E0B;font:800 30px "Exo 2"}
.sscp-set .sscp-up .cv{flex:none;width:200px;height:68px;border-radius:10px;background:#0e1a36 center/cover no-repeat;border:1px dashed rgba(255,255,255,.15)}
.sscp-set .btnf{display:inline-flex!important;align-items:center;height:40px;padding:0 14px!important;border-radius:10px!important;border:1px solid rgba(255,255,255,.14)!important;background:#1A2540;color:#fff!important;font:700 14px "Exo 2"!important;cursor:pointer}
.sscp-set .btnf{width:auto!important;min-width:0!important;max-width:max-content!important;justify-content:center;margin:0 0 6px!important;line-height:1!important}.sscp-set .btnf:hover{border-color:#F59E0B!important}
.sscp-set input:not([type=file]),.sscp-set textarea,.sscp-set select{border-radius:10px!important;padding:11px 14px!important;font-size:15px!important}
.sscp-set .opt{color:#94A3B8;font-weight:400;font-size:12.5px;margin-left:6px}.sscp-lock{color:#94A3B8;font-size:12.5px}.sscp-lock:before{content:"🔒 "}
.sscp-save{position:sticky;bottom:0;z-index:5;display:flex;align-items:center;justify-content:space-between;gap:12px;margin:6px 0 0;padding:12px 16px;border-radius:14px;background:rgba(11,18,32,.92);-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px);border:1px solid rgba(245,158,11,.3)}
.sscp-save span{color:#94A3B8;font-size:13.5px}.sscp-save.dirty span{color:#FBBF24}.sscp-save.dirty span:before{content:"● "}
@media(max-width:900px){.sscp-pg{grid-template-columns:1fr}.sscp-pv{position:static;order:-1}.sscp-ps{grid-template-columns:1fr}}
@media(max-width:600px){.sscp-set .sscp-up .cv{width:120px}.sscp-save{bottom:64px}.sscp-save span{display:none}.sscp-save .ssw-btn{width:100%}}
</style>
<?php }, 70 );

add_action( 'wp_footer', function () {
	if ( ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() || ! ss_ct_tab() ) return; ?>
<script id="ss-ct-js">(function(){
document.addEventListener('click',function(e){var c=e.target.closest&&e.target.closest('[data-chat]');if(!c)return;e.preventDefault();var b=document.querySelector('.ssb-fab,#ssb button');if(b)b.click();});
/* help search */
var hs=document.querySelector('.sscp-hs');if(hs)hs.addEventListener('input',function(){var q=hs.value.trim().toLowerCase(),n=0;document.querySelectorAll('.sscp-faq').forEach(function(d){var ok=!q||d.textContent.toLowerCase().indexOf(q)>=0;d.hidden=!ok;if(ok)n++;if(q&&ok)d.open=true;});var no=document.querySelector('.sscp-hn');if(no)no.hidden=n>0;});
/* profile: live preview, counter, unsaved marker */
var f=document.querySelector('.sscp-set');if(!f)return;var save=f.querySelector('.sscp-save');
function o(k){return f.querySelector('[data-pvo='+k+']')}
function links(){var l=o('links');if(!l)return;l.innerHTML='';f.querySelectorAll('input[name^=social_]').forEach(function(i){if(i.value.trim()){var s=document.createElement('span');s.textContent=(i.closest('label').firstChild.textContent||'').trim();l.appendChild(s);}});}
function cnt(){var t=f.querySelector('textarea[name=bio]'),c=f.querySelector('.sscp-cnt span');if(t&&c)c.textContent=t.value.length;}
f.addEventListener('input',function(e){var t=e.target;if(save)save.classList.add('dirty');if(save)save.firstChild.textContent='Unsaved changes';
 if(t.name==='store_name'){o('name').textContent=t.value||'Your name';var a=o('av');if(a&&!a.style.backgroundImage)a.textContent=(t.value||'?').charAt(0).toUpperCase();}
 if(t.name==='bio'){o('bio').textContent=t.value.slice(0,260);cnt();}
 if(/^social_/.test(t.name))links();});
f.addEventListener('change',function(e){var t=e.target,k=t.getAttribute('data-pvf');if(!k||!t.files||!t.files[0])return;var u=URL.createObjectURL(t.files[0]);[f.querySelector('[data-pv='+k+']'),o(k)].forEach(function(el){if(el){el.style.backgroundImage='url('+u+')';el.textContent='';}});if(save){save.classList.add('dirty');save.firstChild.textContent='Unsaved changes';}});
window.addEventListener('beforeunload',function(e){if(save&&save.classList.contains('dirty')&&!f.dataset.sending){e.preventDefault();e.returnValue='';}});
f.addEventListener('submit',function(){f.dataset.sending='1';});
links();cnt();
})();</script>
<?php }, 99 );
