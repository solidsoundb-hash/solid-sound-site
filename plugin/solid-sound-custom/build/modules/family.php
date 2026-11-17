<?php
/**
 * "Solid family" — warmth where decisions are made (owner 17.11.2026: «позитивная радостная атмосфера, уют, семья»).
 *  1. After a purchase (order received page): a warm welcome into the family, a little confetti, the next steps.
 *  2. Home page: a personal note from Grenor next to the reviews — real people behind the store.
 * Customer texts in English (owner rule). Tiny, no libraries; confetti respects "reduce motion".
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_family_css() {
	return '.ssfam{max-width:760px;margin:22px auto;padding:22px 22px 20px;border-radius:14px;background:linear-gradient(135deg,rgba(245,158,11,.14),rgba(245,158,11,.04) 60%),#131B2E;border:1px solid rgba(245,158,11,.35);color:#E5E7EB;font:400 15.5px/1.6 Roboto,system-ui,sans-serif;box-sizing:border-box}'
		. '.ssfam h2,.ssfam h3{margin:0 0 8px;color:#fff;font:800 24px/1.25 "Exo 2",system-ui,sans-serif}.ssfam h2 span,.ssfam h3 span{color:#F59E0B}'
		. '.ssfam p{margin:0 0 12px;color:#CBD5E1}.ssfam .sig{color:#F59E0B;font:700 15px "Exo 2",system-ui,sans-serif;margin:4px 0 14px}'
		. '.ssfam .row{display:flex;gap:10px;flex-wrap:wrap}.ssfam a.b{display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:0 18px;border-radius:6px;font:700 15px "Exo 2",system-ui,sans-serif;text-decoration:none!important}'
		. '.ssfam a.b1{background:#F59E0B;color:#111!important}.ssfam a.b2{border:1px solid rgba(255,255,255,.18);color:#fff!important;background:transparent}'
		. '.ssfam .hd{display:flex;align-items:center;gap:14px;margin:0 0 10px}.ssfam .hd h3{margin:0}.ssfam .av{flex:none;width:52px;height:52px;border-radius:50%;background:#F59E0B;color:#111;display:flex;align-items:center;justify-content:center;font:800 24px "Exo 2",sans-serif}'
		. '@media(max-width:600px){.ssfam{margin:16px 12px;padding:18px 16px}.ssfam h2,.ssfam h3{font-size:21px}.ssfam a.b{flex:1 1 100%}}';
}

/* ---------- 1. after a purchase ---------- */
add_action( 'woocommerce_thankyou', function ( $oid ) {
	if ( ! $oid ) return;
	$o = wc_get_order( $oid );
	if ( ! $o || $o->has_status( array( 'failed', 'cancelled' ) ) ) return;
	$name = trim( (string) $o->get_billing_first_name() );
	$dl   = wc_get_account_endpoint_url( 'downloads' );
	$forum = home_url( '/forum/' );
	echo '<style>' . ss_family_css() . '</style>';
	echo '<div class="ssfam" role="status"><h2>Welcome to the Solid family' . ( $name ? ', <span>' . esc_html( $name ) . '</span>' : '' ) . ' 🧡</h2>'
		. '<p>Thank you — you just supported a small independent studio, and it truly means a lot to us. Your files are ready: download them below or anytime in <b>My account → Downloads</b>.</p>'
		. '<p>Stuck on something, or want to show us what you made with it? Write to us or post it in the community — a real person answers, not a bot. We love hearing your tracks.</p>'
		. '<p class="sig">— the Solid Sound team</p>'
		. '<div class="row"><a class="b b1" href="' . esc_url( $dl ) . '">My downloads</a><a class="b b2" href="' . esc_url( $forum ) . '">Say hi in the community</a></div></div>';
	/* a short burst of confetti, once (skipped for "reduce motion") */
	echo '<script>(function(){if(matchMedia("(prefers-reduced-motion: reduce)").matches)return;try{if(sessionStorage.getItem("ss_conf_' . (int) $oid . '"))return;sessionStorage.setItem("ss_conf_' . (int) $oid . '","1")}catch(e){}'
		. 'var c=document.createElement("canvas"),x=c.getContext("2d");if(!x)return;c.style.cssText="position:fixed;inset:0;width:100%;height:100%;pointer-events:none;z-index:2147483000";document.body.appendChild(c);'
		. 'var W=c.width=innerWidth,H=c.height=innerHeight,C=["#F59E0B","#FBBF24","#fff","#34D399","#60A5FA","#F472B6"],P=[];for(var i=0;i<140;i++)P.push({x:W/2+(Math.random()-.5)*W*.3,y:H*.35,vx:(Math.random()-.5)*12,vy:-Math.random()*13-4,s:4+Math.random()*5,r:Math.random()*6,c:C[i%C.length]});'
		. 'var t0=performance.now();function f(t){var k=(t-t0)/1000;x.clearRect(0,0,W,H);P.forEach(function(p){p.vy+=.35;p.x+=p.vx;p.y+=p.vy;p.r+=.1;x.save();x.translate(p.x,p.y);x.rotate(p.r);x.globalAlpha=Math.max(0,1-k/2.6);x.fillStyle=p.c;x.fillRect(-p.s/2,-p.s/4,p.s,p.s/2);x.restore()});if(k<2.6)requestAnimationFrame(f);else c.remove()}requestAnimationFrame(f)})();</script>';
}, 5 );

/* ---------- 2. home: a personal note next to the reviews ----------
   OFF (owner 17.11.2026: «Про меня ничего не нужно пока что»). Kept for later: set SS_FAMILY_NOTE true. */
add_action( 'wp_footer', function () {
	if ( ! defined( 'SS_FAMILY_NOTE' ) || ! SS_FAMILY_NOTE ) return;
	if ( ! function_exists( 'ss_home_is_app_page' ) || ! ss_home_is_app_page() ) return;
	$forum = home_url( '/forum/' );
	$about = 'https://solid-sound.net/en/contacts/';
	$html = '<div class="ssfam" id="ss-family"><div class="hd"><div class="av" aria-hidden="true">G</div><h3>Hi, I’m <span>Grenor</span> 👋</h3></div>'
		. '<p>Solid Sound is not a faceless shop — it’s a small independent studio and a family of producers. I’ve been making music and mixing for 13+ years, and every template here is a real project you can open, learn from and make your own.</p>'
		. '<p>Got a question, a track you’re proud of, or just want to say hi? Write to us — a real person answers. Welcome to the family.</p>'
		. '<p class="sig">— Grenor, founder of Solid Sound</p>'
		. '<div class="row"><a class="b b1" href="' . esc_url( $forum ) . '">Join the community</a><a class="b b2" href="' . esc_url( $about ) . '">Write to us</a></div></div>';
	?>
<style id="ss-family-css"><?php echo ss_family_css(); ?>#ss-family{clear:both}#ss-family::after{content:"";display:block;clear:both}</style>
<script id="ss-family-js">(function(){var H=<?php echo wp_json_encode( $html ); ?>;
function put(){if(document.getElementById('ss-family'))return true;var r=document.querySelector('#ss-app #reviews');if(!r)return false;var d=document.createElement('div');d.innerHTML=H;r.appendChild(d.firstChild);return true}
if(put())return;var app=document.getElementById('ss-app')||document.body,raf=0,mo=new MutationObserver(function(){if(raf)return;raf=requestAnimationFrame(function(){raf=0;if(put())mo.disconnect()})});mo.observe(app,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},20000);})();</script>
	<?php
}, 40 );
