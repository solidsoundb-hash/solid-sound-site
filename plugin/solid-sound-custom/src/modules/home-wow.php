<?php
/**
 * (16.11.2026 owner chose mockup B "wall of covers": «Выкладывай, только предыдущую версию где-то сохрани»)
 * Home: a slowly moving wall of our real product covers behind the shopping assistant (structure unchanged), our
 * slogan "Turn Your Ideas into Solid Sound · Learn · Create · Release" above it, tap a cover = play its demo,
 * and a live "N templates match" hint in the first card (genre / BPM / key you pick).
 * The previous home is kept: ?ss_wow=0 shows it, option ss_wow_off turns this off for everyone, git tag
 * home-before-wow-2026-11-16. Loads after the page (idle), pauses when off screen, no motion for reduced-motion users.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_wow_on() {
	if ( isset( $_GET['ss_wow'] ) ) return '0' !== $_GET['ss_wow'];
	return ! get_option( 'ss_wow_off' );
}

function ss_wow_items() {
	$c = get_transient( 'ss_wow_items2' );
	if ( is_array( $c ) ) return $c;
	$keys = function_exists( 'ss_ap_keys' ) ? ss_ap_keys() : array();
	$val  = function ( $k, $pid ) use ( $keys ) {
		if ( empty( $keys[ $k ] ) || ! function_exists( 'get_field' ) ) return '';
		$v = get_field( $keys[ $k ], $pid );
		$o = array();
		foreach ( (array) $v as $x ) $o[] = is_object( $x ) && isset( $x->name ) ? $x->name : ( is_scalar( $x ) ? (string) $x : '' );
		return trim( implode( ', ', array_filter( $o ) ) );
	};
	$out = array();
	$q   = new WP_Query( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 24, 'no_found_rows' => true, 'meta_key' => '_thumbnail_id', 'orderby' => 'date', 'order' => 'DESC' ) );
	foreach ( $q->posts as $p ) {
		$img = get_the_post_thumbnail_url( $p->ID, 'woocommerce_thumbnail' );
		if ( ! $img || get_post_meta( $p->ID, '_ss_hide_home', true ) ) continue;
		$mp3 = '';
		$txt = $p->post_excerpt . ' ' . $p->post_content . ' ' . ( ! empty( $keys['mp3'] ) && function_exists( 'get_field' ) ? (string) get_field( $keys['mp3'], $p->ID, false ) : '' );
		if ( preg_match( '#https?://[^\s"\'\]]+?\.mp3#i', $txt, $m ) ) $mp3 = $m[0];
		$g = wp_get_post_terms( $p->ID, 'product-genre', array( 'fields' => 'names' ) );
		$f = wp_get_post_terms( $p->ID, 'product-format', array( 'fields' => 'slugs' ) ); /* 19.11: music / templates / presets — the DJ card shows music */
		$out[] = array(
			'p' => $p->ID, 't' => get_the_title( $p ), 'i' => $img, 'u' => get_permalink( $p ), 's' => $mp3,
			'g' => is_wp_error( $g ) ? '' : strtolower( implode( '|', $g ) ),
			'b' => (int) preg_replace( '/\D.*/', '', $val( 'bpm', $p->ID ) ), 'k' => $val( 'key', $p->ID ),
			'f' => is_wp_error( $f ) ? '' : implode( '|', $f ),
		);
	}
	set_transient( 'ss_wow_items2', $out, HOUR_IN_SECONDS );
	return $out;
}
add_action( 'save_post_product', function () { delete_transient( 'ss_wow_items2' ); } );

add_action( 'wp_footer', function () {
	if ( ! function_exists( 'ss_home_is_app_page' ) || ! ss_home_is_app_page() || ! ss_wow_on() ) return;
	$items = ss_wow_items();
	if ( count( $items ) < 3 ) return;
	$h1 = function_exists( 'ss_h3_t' ) ? ss_h3_t( 'h1' ) : 'Turn Your Ideas into %s';
	?>
<style id="ss-wow-css">
.ssw-hero{position:relative;z-index:1;text-align:center;padding:26px 12px 18px;margin:0 0 -4px}
.ssw-hero .k{position:relative;z-index:2;display:inline-flex;gap:7px;align-items:center;font:700 11.5px Roboto,sans-serif;letter-spacing:.14em;text-transform:uppercase;color:#fbbf24;padding:5px 11px;border-radius:999px;background:rgba(11,16,32,.7);border:1px solid rgba(245,158,11,.35)}
.ssw-hero .k i{width:7px;height:7px;border-radius:50%;background:#22c55e;box-shadow:0 0 0 4px rgba(34,197,94,.2)}
.ssw-hero .slg{position:relative;z-index:2;margin:0 auto 12px;max-width:820px;font:800 clamp(30px,6.4vw,52px)/1.06 "Exo 2",sans-serif;color:#fff;text-shadow:0 2px 4px rgba(0,0,0,.8),0 6px 40px rgba(0,0,0,.9)}
.ssw-hero .slg span{color:#f59e0b} /* owner 16.11: Solid Sound always brand orange */
.ssw-wall{position:absolute;left:-12px;right:-12px;top:0;z-index:0;overflow:hidden;border-radius:24px;pointer-events:none;opacity:0;transition:opacity 1.2s}
.ssw-wall.on{opacity:1}
/* owner 19.11: «фон замени на сине-чёрный, сцена» — no covers: a dark blue stage with soft blue spotlights (static, no images, no effects) */
.ssw-wall.stage{background:radial-gradient(55% 70% at 50% -10%,rgba(59,130,246,.30),transparent 70%),radial-gradient(35% 55% at 12% 0%,rgba(37,99,235,.22),transparent 72%),radial-gradient(35% 55% at 88% 0%,rgba(29,78,216,.22),transparent 72%),radial-gradient(70% 40% at 50% 100%,rgba(30,58,138,.28),transparent 75%),linear-gradient(180deg,#0e1a36 0%,#0b1226 55%,#0a0f1f 100%);border:1px solid rgba(96,165,250,.10)}
.ssw-wall.stage:after{display:none}
.ssw-wall.stage:before{content:"";position:absolute;left:8%;right:8%;top:0;height:1px;background:linear-gradient(90deg,transparent,rgba(147,197,253,.45),transparent)}
.ssw-wall .in{position:absolute;inset:-60px -60px;transform:rotate(-6deg)} /* owner 19.11: static background (no 3D, no motion) — focus on the slider, faster site */
.ssw-row{display:flex;gap:12px;margin-bottom:12px;width:max-content}
.ssw-row:nth-child(2n){margin-left:-110px}.ssw-row:nth-child(3n){margin-left:-50px}
.ssw-row a{pointer-events:auto;position:relative;display:block;width:132px;height:132px;border-radius:14px;overflow:hidden;flex:none;opacity:.55}
.ssw-row img{width:100%;height:100%;object-fit:cover;display:block}
.ssw-row a:after{content:"▶";position:absolute;inset:auto auto 8px 8px;width:30px;height:30px;border-radius:50%;background:#f59e0b;color:#111;display:grid;place-items:center;font-size:12px;opacity:0;transition:opacity .2s}
.ssw-row a:hover:after,.ssw-row a.pl:after{opacity:1}
.ssw-wall:after{content:"";position:absolute;inset:0;background:radial-gradient(60% 140px at 50% 120px,rgba(11,16,32,.8),transparent 100%),linear-gradient(180deg,rgba(11,16,32,.55),rgba(11,16,32,.78) 40%,rgba(11,16,32,.9))}
/* owner 16.11: «2 заголовка конфликтуют» — the slogan is the headline; the assistant title becomes a small card label */
body.ss-wow .ssx h1.ssx-t,body.ss-wow #top .ssx h1.ssx-t,#top body.ss-wow .ssx h1.ssx-t,body.ss-wow#top .ssx h1.ssx-t{font:800 17px/1.3 "Exo 2",sans-serif!important;letter-spacing:.06em;text-transform:uppercase;color:#fff!important;margin:0 0 10px!important}
body.ss-wow .ssx .sub{display:none}
body.ss-wow .ssx{position:relative;z-index:2;background:#111214!important;border-color:rgba(245,158,11,.45)!important;box-shadow:0 30px 80px rgba(0,0,0,.7),0 0 0 1px rgba(245,158,11,.15)}
/* owner 19.11: colour «Графит» (variant 2) */
body.ss-wow .ssx .card{background:#17181b!important;border-color:rgba(255,255,255,.07)!important}body.ss-wow .ssx .tasks button{background:#1b1c20!important}body.ss-wow .ssx .tasks button.on{background:rgba(245,158,11,.14)!important}
/* owner 19.11 «верни размер обратно и пересобери… логично, эргономично, интуитивно»: width back to 1200;
   the picker reads top to bottom: 1) genre, 2) BPM · key · Ableton on one line, 3) result and buttons on one line */
@media(min-width:1000px){
 body.ss-wow .ssx .card .left:has(>.f.row){grid-template-columns:minmax(0,1.55fr) minmax(0,1fr)!important;column-gap:36px!important}
 body.ss-wow .ssx .card .left>.f.row{display:flex!important;flex-wrap:nowrap!important;align-items:flex-end!important;gap:28px!important;margin-bottom:16px!important}
 body.ss-wow .ssx .card .left>.f.row>*{margin:0!important}
 body.ss-wow .ssx .card .left>.ssw-match{grid-column:1!important;grid-row:5;margin:4px 0 0!important;align-self:stretch}
 body.ss-wow .ssx .card .left:has(>.ssw-match)>.acts{grid-column:2!important;grid-row:5;align-self:end;justify-content:flex-end;margin:4px 0 0!important}
 body.ss-wow .ssx .card .left>.acts:not(:has(~ .acts)),body.ss-wow .ssx .card .left>.cta{justify-content:flex-end;align-self:flex-end}
}
/* owner 19.11 «сортировка странная, особенно первый слайдер»: the template picker as one tidy filter grid —
   genre on top, then three equal columns BPM | Key | Ableton (+ «No external plugins» under Ableton), result left, buttons right */
@media(min-width:1000px){
 body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match){grid-template-columns:repeat(3,minmax(0,1fr))!important;column-gap:32px!important;row-gap:0!important}
 body.ss-wow .ssx .card .left:has(>.ssw-match)>.f.row{display:contents!important}
 body.ss-wow .ssx .card .left:has(>.ssw-match)>.f.row>div{grid-column:auto!important;margin:0!important;min-width:0}
 body.ss-wow .ssx .card .left:has(>.ssw-match)>.f.row>.chk{grid-column:3!important;margin:10px 0 0!important}
 body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>:not(.f.row):not(.ssw-match):not(.acts){grid-column:1 / -1!important}
 body.ss-wow .ssx .card .left:has(>.ssw-match)>.f:not(.row){margin-bottom:18px!important}
 body.ss-wow .ssx .card .left:has(>.ssw-match) .rng{display:flex;align-items:center;gap:10px}
 body.ss-wow .ssx .card .left:has(>.ssw-match) .rng input[type=range]{flex:1;min-width:0}
 body.ss-wow .ssx .card .left:has(>.ssw-match)>.ssw-match{grid-column:1 / span 2!important;grid-row:auto!important;margin:18px 0 0!important}
 body.ss-wow .ssx .card .left:has(>.ssw-match)>.acts{grid-column:3!important;grid-row:auto!important;margin:18px 0 0!important;justify-content:flex-end;align-self:center}
}
/* owner 19.11 «как под линеечку, а не хаотично»: on computers every slide is a neat form — one column of labels
   (GENRE, BPM, KEY, ABLETON, WHAT DO YOU NEED…) and every control starts on the same vertical line; the result strip
   starts on that line too; the buttons sit bottom-right. Overrides the column layouts above. */
@media(min-width:1000px){
 body.ss-wow #ss-app .ssx .card .left{display:flex!important;flex-direction:column!important;--lbl:150px}
 body.ss-wow #ss-app .ssx .card .left>.f.row{display:block!important;margin:0!important}
 body.ss-wow #ss-app .ssx .card .left>.f:not(.row),body.ss-wow #ss-app .ssx .card .left>.f.row>div{display:grid!important;grid-template-columns:var(--lbl) minmax(0,1fr);align-items:center;column-gap:18px;margin:0 0 14px!important}
 body.ss-wow #ss-app .ssx .card .left>.f:not(.row)>b,body.ss-wow #ss-app .ssx .card .left>.f.row>div>b{margin:0!important;line-height:1.25}
 body.ss-wow #ss-app .ssx .card .left>.f.row>.chk{margin:-4px 0 14px calc(var(--lbl) + 18px)!important;display:flex!important}
 body.ss-wow #ss-app .ssx .card .left .rng{display:flex;align-items:center;gap:12px;max-width:420px}
 body.ss-wow #ss-app .ssx .card .left .rng input[type=range]{flex:1;min-width:0}
 body.ss-wow #ss-app .ssx .card .left>.f~.d{margin-left:calc(var(--lbl) + 18px)!important}
 body.ss-wow #ss-app .ssx .card .left>.ssw-match{margin:4px 0 0 calc(var(--lbl) + 18px)!important;grid-column:auto!important;grid-row:auto!important}
 /* rows of two label/control pairs (BPM | KEY, ABLETON | No external plugins): both columns start on fixed lines, so it
    stays «под линеечку» and the slider fits a 1366×768 laptop */
 body.ss-wow #ss-app .ssx .card .left>.f.row{display:grid!important;grid-template-columns:minmax(0,1fr) minmax(0,1fr);column-gap:28px;align-items:center;margin:0 0 12px!important}
 body.ss-wow #ss-app .ssx .card .left>.f.row>div{margin:0!important}
 body.ss-wow #ss-app .ssx .card .left>.f.row>div:nth-child(2){grid-template-columns:70px minmax(0,1fr)}
 body.ss-wow #ss-app .ssx .card .left>.f.row>.chk{grid-column:2!important;justify-self:start;margin:0 0 0 calc(70px + 18px)!important;align-self:center}
 body.ss-wow #ss-app .ssx .card .left>.f:not(.row){margin:0 0 12px!important}
 body.ss-wow #ss-app .ssx .card .left>.acts:not(:has(~ .acts)),body.ss-wow #ss-app .ssx .card .left>.cta{margin-top:auto!important;padding-top:16px;justify-content:flex-end;align-self:flex-end;grid-column:auto!important;grid-row:auto!important}
}
/* owner 19.11 «каждый раздел — кнопки примерно одинаково расположены, однородно»: every slide has the same frame —
   title + subtitle on top, the choices in the middle, the buttons in one place at the bottom-right (owner: «неудобно когда кнопки слева») (cards are as tall
   as the tallest one, so the buttons line up on every tab) */
/* (owner 19.11 evening: «слайдер кривой… подгони, чтобы всё было компактно») each card is only as tall as its own content
   (no empty space under short tabs, no gap inside the tall one); the window follows the open card smoothly (home.js go()) */
body.ss-wow .ssx .track{align-items:flex-start}
body.ss-wow .ssx .card .left:not(:has(>.ssw-match)){display:flex!important;flex-direction:column!important}
body.ss-wow .ssx .card .left:not(:has(>.ssw-match))>.acts:not(:has(~ .acts)),body.ss-wow .ssx .card .left:not(:has(>.ssw-match))>.cta{margin-top:auto!important}
body.ss-wow .ssx .card .left>.acts:not(:has(~ .acts)),body.ss-wow .ssx .card .left>.cta{padding-top:14px}
body.ss-wow .ssx .card .left .acts{display:flex;flex-wrap:wrap;gap:10px}
body.ss-wow .ssx .card .left form.acts .inp{flex:1 1 240px;min-width:0;min-height:48px}body.ss-wow .ssx .card .left form.acts .cta{flex:1 1 auto}body.ss-wow .ssx .card .left form.acts .ss-priv-note{flex-basis:100%}
@media(max-width:600px){body.ss-wow #ss-app .ssx .card .left form.acts{display:flex!important;flex-wrap:wrap!important}body.ss-wow #ss-app .ssx .card .left form.acts .inp{flex:1 1 100%!important;width:100%!important}body.ss-wow #ss-app .ssx .card .left form.acts .cta{flex:1 1 100%!important;width:100%!important;max-width:none!important;padding:0 16px!important}}
body.ss-wow .ssx .card .left .cta,body.ss-wow .ssx .card .left .gbtn{min-height:48px;padding:0 22px!important;display:inline-flex;align-items:center;justify-content:center}
/* owner 19.11: the slider turns by itself — a thin progress line under the active task; stops for good once the visitor touches the slider */
body.ss-wow .ssx .tasks button{position:relative}
body.ss-wow .ssx.ss-auto .tasks button.on:after{content:"";position:absolute;left:10px;right:10px;bottom:3px;height:2px;border-radius:2px;background:#f59e0b;transform-origin:0 50%;animation:ssxbar var(--ssx-t,7s) linear forwards}
body.ss-wow .ssx.ss-hold .tasks button.on:after{animation-play-state:paused}
@keyframes ssxbar{from{transform:scaleX(0)}to{transform:scaleX(1)}}
/* laptops (short screens): compact hero so the card and its button fit the first screen */
@media(min-width:900px) and (max-height:860px){.ssw-hero{padding:18px 12px 12px!important}.ssw-hero .slg{font-size:40px!important;margin-bottom:8px}}
/* owner 19.11 «на моём ноуте нифига не помещается»: on wide screens the picker rows (BPM/key and Ableton) sit side by side,
   the tallest card is ~100px lower and the whole slider fits the first screen */
@media(min-width:1000px){body.ss-wow .ssx .card .left{display:grid;grid-template-columns:1fr 1fr;column-gap:32px}body.ss-wow .ssx .card .left>*{grid-column:1/-1}body.ss-wow .ssx .card .left>.f.row{grid-column:auto}}
@media(min-width:1000px) and (max-height:900px){.ssw-hero{padding:14px 12px 8px!important}.ssw-hero .slg{font-size:38px!important;margin-bottom:6px}body.ss-wow .ssx{padding-top:16px!important;padding-bottom:16px!important}body.ss-wow .ssx .tasks{margin-bottom:12px!important}}
/* tablets */
@media(min-width:601px) and (max-width:1024px){.ssw-hero .slg{font-size:44px!important}.ssw-row a{width:120px;height:120px}}
.ssw-match{display:flex;align-items:center;gap:10px;margin:12px 0 0;padding:9px 10px;border-radius:12px;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.28);cursor:pointer;text-align:left}
.ssw-match .cv{display:flex;flex:none}.ssw-match .cv img{width:38px;height:38px;border-radius:8px;object-fit:cover;border:2px solid #0b1020;margin-left:-10px;background:#1b2338}.ssw-match .cv img:first-child{margin-left:0}
.ssw-match b{display:block;color:#fff;font:700 14.5px "Exo 2",sans-serif}.ssw-match small{color:#a3b1c6;font:400 12.5px Roboto,sans-serif}
/* owner 16.11 «Адаптируй нормально» (fast deploy ok): phones — compact hero so the assistant and its button stay on the first screen */
@media(max-width:600px){
 .ssw-hero{padding:12px 6px 10px}
 .ssw-hero .slg{font-size:36px!important;line-height:1.04;margin:0 auto 8px;letter-spacing:-.3px}
 .ssw-hero .k{font-size:12.5px;letter-spacing:.13em;padding:6px 14px;gap:7px}.ssw-hero .k i{width:6px;height:6px;box-shadow:0 0 0 3px rgba(34,197,94,.2)}
 .ssw-row a{width:96px;height:96px;border-radius:12px}.ssw-row{gap:8px;margin-bottom:8px}
 body.ss-wow .ssx h1.ssx-t,body.ss-wow#top .ssx h1.ssx-t{font-size:17px!important;margin:0 0 8px!important}
 .ssw-match{gap:8px;padding:7px 9px;margin-top:10px}.ssw-match .cv img{width:30px;height:30px;margin-left:-8px}
 .ssw-match b{font-size:13.5px}.ssw-match small{font-size:11.5px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:calc(100vw - 200px)}.ssw-match>span:last-child{min-width:0;flex:1}
}
/* owner 16.11: previous card look, only the template picker (genre / BPM / key / Ableton) a touch bigger on phones */
@media(max-width:600px){
 body.ss-wow .ssx .card .chips span{height:34px!important;padding:0 16px!important;font-size:14.5px!important}
 body.ss-wow .ssx .tasks button,body.ss-wow #top .ssx [role=tablist] button,#top .ssx [role=tablist] button{padding:0 16px!important}body.ss-wow .ssx .cta,body.ss-wow .ssx .gbtn{padding:0 20px!important} /* owner 16.11: air on the sides of button text */
 body.ss-wow .ssx .card .f b{font-size:12px!important}
 body.ss-wow .ssx .rng .bpm input{height:35px!important;font-size:16px!important}
 body.ss-wow .ssx .chk{font-size:13.5px!important;white-space:nowrap;gap:5px!important}
 body.ss-wow .ssx .f.row:has(.chk){flex-wrap:nowrap!important;align-items:flex-end;gap:8px!important}@media(max-width:600px){body.ss-wow .ssx .f.row:has(.chk){flex-wrap:wrap!important;row-gap:10px!important}body.ss-wow .ssx .f.row:has(.chk)>.chk{flex:1 0 100%}} /* (19.11) Key + checkbox: the checkbox on its own line on phones */body.ss-wow .ssx .f.row:has(.chk) .chips span{padding:0 12px!important}body.ss-wow .ssx .f.row:has(.chk) .chips{gap:5px!important}
}
@media(max-width:380px){.ssw-hero .slg{font-size:34px!important}}
/* (20.11 owner, Samsung A31: «не влазит полностью») phones — the template picker was 628 px tall: BPM / Key / Ableton with the label beside the buttons, slimmer buttons and gaps */
@media(max-width:600px){
 body.ss-wow #ss-app .ssx .card .left:has(input[type=range])>.f{margin:0 0 10px!important}
 body.ss-wow #ss-app .ssx .card .left:has(input[type=range])>.f>b{margin-bottom:5px!important}
 body.ss-wow #ss-app .ssx .card .left:has(input[type=range])>.f.row>div{display:flex!important;align-items:center;gap:10px;margin:0!important}
 body.ss-wow #ss-app .ssx .card .left:has(input[type=range])>.f.row>div>b{flex:0 0 62px;margin:0!important}
 body.ss-wow #ss-app .ssx .card .left:has(input[type=range])>.f.row>div>.chips,body.ss-wow #ss-app .ssx .card .left:has(input[type=range])>.f.row>div>.rng{flex:1 1 auto;min-width:0;margin:0!important;padding:0!important}
 body.ss-wow #ss-app .ssx .card .left:has(input[type=range])>.f.row>.chk{margin:8px 0 0 72px!important;flex:none!important}
 body.ss-wow #ss-app .ssx .card .left:has(input[type=range]) .chips span{height:31px!important;padding:0 12px!important}
 body.ss-wow #ss-app .ssx .card .left:has(input[type=range]) .chips{gap:6px!important}
 body.ss-wow #ss-app .ssx .card .left:has(input[type=range]) .rng .bpm input{height:31px!important}
 body.ss-wow #ss-app .ssx .card .left:has(input[type=range])>.acts{margin-top:10px!important}
 /* (20.11 owner: «меню с заголовком чуть уже, большие кнопки чуть меньше, большие расстояния чуть меньше») */
 body.ss-wow #ss-app header .ss-mh-btn,body.ss-wow #ss-app header .ss-mh-cart,body.ss-wow #ss-app header a[aria-label="Account"],body.ss-wow #ss-app header a[aria-label="Sign in"],body.ss-wow #ss-app header a[aria-label="My account"],body.ss-wow #ss-app header button[aria-label="Main menu"]{width:38px!important;height:38px!important;min-width:38px!important}
 body.ss-wow .ssw-hero{padding:8px 6px 6px!important}
 body.ss-wow .ssw-hero .slg{font-size:30px!important;margin:0 auto 6px!important}
 body.ss-wow .ssw-hero .slg span{white-space:nowrap;display:inline-block}
 /* (20.11 owner, A31: «не влазит целый блок Get everything free — глобально оптимизацию») the whole slider on the first screen: no pill, slimmer Free tiles and card */
 body.ss-wow .ssw-hero .k{display:none!important}
 body.ss-wow .ssw-hero .slg{font-size:24px!important;margin:0 auto 4px!important;padding:0 14px!important}
 /* (20.11 owner «Writing a track — чтобы было нужно») on phones only what is needed to pick a template: no «Add to it» row (extras are on the product page) */
 body.ss-wow #ss-app .ssx .card .left:has(input[type=range])>.f:has(.chips.addon){display:none!important}
 /* (20.11 owner: one slider height that fits the A31 screen) the two tallest tabs made short — Video & ads (468 px) and Just starting (455 px) */
 body.ss-wow #ss-app .ssx .card .chips span{height:31px!important}
 body.ss-wow #ss-app .ssx .ssw-vd{padding:10px!important;margin-top:8px!important}
 body.ss-wow #ss-app .ssx .ssw-vd .rl{display:grid!important;grid-template-columns:1fr 1fr!important;gap:5px!important}
 body.ss-wow #ss-app .ssx .ssw-vd .ri,body.ss-wow #ss-app .ssx .ssw-vd .ri.on{width:auto!important;min-width:0!important;padding:6px 8px!important}
 body.ss-wow #ss-app .ssx .ssw-vd .ri em{font-size:12px!important;white-space:nowrap!important;overflow:hidden;text-overflow:ellipsis;line-height:1.3}
 body.ss-wow #ss-app .ssx .card .left.fp>.fp-links,body.ss-wow #ss-app .ssx .card .left.fp>.fp-cover{display:none!important}
 body.ss-wow #ss-app .ssx .card .left.fp .fp-list{gap:5px!important;margin:0 0 10px!important}
 body.ss-wow #ss-app .ssx .card .left.fp .fp-list li{font-size:14px!important}
 body.ss-wow #ss-app .ssx .card .left.fp>.fp-or{margin:6px 0 4px!important}
 body.ss-wow #ss-app .ssx .card .left.fp>.fp-note{margin-top:4px!important}
 body.ss-wow #ss-app .ssx{padding-bottom:10px!important}
 body.ss-wow #ss-app .ssx h1.ssx-t{margin:0 0 8px!important}
 body.ss-wow #ss-app .ssx .tasks{margin-bottom:10px!important}
 body.ss-wow #ss-app .ssx .fz-tiles{gap:6px!important}
 body.ss-wow #ss-app .ssx .fz-t{padding:7px 12px!important;gap:10px!important;border-radius:10px!important}
 body.ss-wow #ss-app .ssx .fz-t .i{flex-basis:32px!important;width:32px!important;height:32px!important;border-radius:8px!important}
 body.ss-wow #ss-app .ssx .fz-t .i svg{width:18px;height:18px}
 body.ss-wow #ss-app .ssx .fz-t b{font-size:15px!important}
 body.ss-wow #ss-app .ssx .fz-t small{font-size:12.5px!important;line-height:1.3!important}
 body.ss-wow #ss-app .ssx .card .left.hp>.hp-card{padding:12px 14px!important;gap:5px!important;margin-top:8px!important}
 body.ss-wow #ss-app .ssx .hp-card b{font-size:18px!important}
 body.ss-wow #ss-app .ssx .hp-card small{font-size:12.5px!important;line-height:1.35!important}
 body.ss-wow #ss-app .ssx .hp-card .cta{height:40px!important;margin-top:4px!important}
 body.ss-wow #ss-app .ssx .card .left.hp{padding:10px!important}
 body.ss-wow .ssw-hero .k{font-size:11.5px!important;padding:4px 12px!important}
 body.ss-wow #ss-app .ssx{padding-top:12px!important}
 body.ss-wow #ss-app .ssx .tasks button{height:38px!important;padding:0 12px!important}
 body.ss-wow #ss-app .ssx .card .left:has(input[type=range]) .chips.addon{display:flex!important;flex-wrap:wrap!important;gap:6px!important}
 body.ss-wow #ss-app .ssx .card .left:has(input[type=range]) .chips.addon span{padding:0 9px!important;font-size:12.5px!important;flex:none!important;width:auto!important}
}
@media(min-width:900px){.ssw-hero{padding:34px 12px 22px}.ssw-row a{width:168px;height:168px}}
@media(prefers-reduced-motion:reduce){body.ss-wow .ssx.ss-auto .tasks button.on:after{display:none}}
/* (owner 19.11 «как у профи сделай») computer only: text menu in one line, compact search, tabs as an underline row,
   one surface instead of boxes in boxes. Phones keep their own layout. */
@media(min-width:1001px){
body.ss-wow #ss-app header form{max-width:440px!important;height:42px!important;border-radius:10px!important}
body.ss-wow #ss-app .ssx{border:0!important;box-shadow:0 30px 80px rgba(0,0,0,.45)!important;background:linear-gradient(180deg,#141C2E,#0F1524)!important;border-radius:16px!important}
body.ss-wow #ss-app .ssx .tasks{gap:4px!important;border-bottom:1px solid rgba(255,255,255,.08)!important;padding-bottom:0!important}
body.ss-wow #ss-app .ssx .tasks button{background:transparent!important;border:0!important;border-bottom:2px solid transparent!important;border-radius:0!important;padding:12px 16px!important;color:#94A3B8!important;box-shadow:none!important}
body.ss-wow #ss-app .ssx .tasks button.on{color:#fff!important;border-bottom-color:#F59E0B!important}
body.ss-wow #ss-app .ssx.ss-auto .tasks button.on{border-bottom-color:rgba(245,158,11,.25)!important}
body.ss-wow #ss-app .ssx.ss-auto .tasks button.on:after{left:0!important;right:0!important;bottom:-2px!important;border-radius:0!important}
body.ss-wow #ss-app .ssx .tasks button.on i,body.ss-wow #ss-app .ssx .tasks button.on svg{color:#F59E0B!important}
body.ss-wow #ss-app .ssx .card{border:0!important;background:transparent!important;box-shadow:none!important}
}
/* (20.11 owner: «на телефоне стиль слайдера не поменялся, отличается от ПК») phones/tablets — the same look as the computer: flat tabs with an orange line, same panel */
@media(max-width:1000px){
body.ss-wow #ss-app .ssx{border:0!important;background:linear-gradient(180deg,#141C2E,#0F1524)!important;border-radius:14px!important}
body.ss-wow #ss-app .ssx .tasks{gap:0!important;border-bottom:1px solid rgba(255,255,255,.08)!important;padding-bottom:0!important}
body.ss-wow #ss-app .ssx .tasks button{background:transparent!important;border:0!important;border-bottom:2px solid transparent!important;border-radius:0!important;padding:0 11px!important;height:40px!important;color:#94A3B8!important;box-shadow:none!important}
body.ss-wow #ss-app .ssx .tasks button.on{color:#fff!important;border-bottom-color:#F59E0B!important}
body.ss-wow #ss-app .ssx.ss-auto .tasks button.on{border-bottom-color:rgba(245,158,11,.25)!important}
body.ss-wow #ss-app .ssx.ss-auto .tasks button.on:after{left:0!important;right:0!important;bottom:-2px!important;border-radius:0!important}
body.ss-wow #ss-app .ssx .tasks button.on i,body.ss-wow #ss-app .ssx .tasks button.on svg{color:#F59E0B!important}
body.ss-wow #ss-app .ssx .card{border:0!important;background:transparent!important;box-shadow:none!important}
}
/* (owner 19.11) computer: filters on the left (Genre, BPM, Key, then Ableton + No external plugins),
   the "templates match" card square on the right, the buttons under it */
@media(min-width:1001px){
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match){display:grid!important;grid-template-columns:minmax(0,1fr) 280px!important;column-gap:36px!important;row-gap:0!important;align-items:start!important;grid-auto-flow:row!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>*{grid-column:1!important;grid-row:auto!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>.f.row{display:flex!important;align-items:center!important;gap:28px!important;margin:0 0 14px!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>.f.row>div{display:grid!important;grid-template-columns:var(--lbl,190px) auto!important;align-items:center!important;margin:0!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>.f.row>.chk{margin:0!important;grid-column:auto!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>.ssw-match{grid-column:2!important;grid-row:1 / span 6!important;align-self:start!important;aspect-ratio:1/1!important;margin:0!important;flex-direction:column!important;align-items:stretch!important;justify-content:center!important;gap:12px!important;padding:16px!important;text-align:center!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>.ssw-match .cv{display:grid!important;grid-template-columns:repeat(3,1fr)!important;gap:8px!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>.ssw-match .cv img{width:100%!important;height:auto!important;aspect-ratio:1!important;margin:0!important;border-radius:10px!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>.ssw-match b{font-size:17px!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>.acts{grid-column:2!important;grid-row:7!important;display:flex!important;gap:10px!important;margin:12px 0 0!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>.acts>a{flex:1 1 auto!important;justify-content:center!important;text-align:center!important;white-space:nowrap!important;font-size:14.5px!important;padding:0 14px!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>:nth-child(1):not(.ssw-match):not(.acts){grid-row:1!important;order:0!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>:nth-child(2):not(.ssw-match):not(.acts){grid-row:2!important;order:0!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>:nth-child(3):not(.ssw-match):not(.acts){grid-row:3!important;order:0!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>:nth-child(4):not(.ssw-match):not(.acts){grid-row:4!important;order:0!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>:nth-child(5):not(.ssw-match):not(.acts){grid-row:5!important;order:0!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>:nth-child(6):not(.ssw-match):not(.acts){grid-row:6!important;order:0!important}
}
/* (owner 19.11) covers in the match card are buttons: a play mark on hover, an orange ring on the one playing */
body.ss-wow #ss-app .ssx .ssw-match .mx{position:relative;display:block;cursor:pointer;border-radius:10px;line-height:0}
body.ss-wow #ss-app .ssx .ssw-match .mx:not(:first-child){margin-left:-8px}
body.ss-wow #ss-app .ssx .ssw-match .mx img{margin:0!important}
body.ss-wow #ss-app .ssx .ssw-match .mx i{position:absolute;inset:0;border-radius:inherit;background:rgba(0,0,0,.45) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23F59E0B' d='M8 5v14l11-7z'/%3E%3C/svg%3E") center/42% no-repeat;opacity:0;transition:opacity .15s}
body.ss-wow #ss-app .ssx .ssw-match .mx:hover i,body.ss-wow #ss-app .ssx .ssw-match .mx:focus-visible i,body.ss-wow #ss-app .ssx .ssw-match .mx.pl i{opacity:1}
body.ss-wow #ss-app .ssx .ssw-match .mx.pl{box-shadow:0 0 0 2px #F59E0B}
@media(min-width:1001px){body.ss-wow #ss-app .ssx .ssw-match .mx:not(:first-child){margin-left:0}body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>.ssw-match .mx img{width:100%!important;height:auto!important;aspect-ratio:1!important}}
@media(hover:none){body.ss-wow #ss-app .ssx .ssw-match .mx i{opacity:.85;background-size:48%}}
/* (owner 19.11) background blocks: square corners, and every section below gets the same panel as the first one */
.ssw-wall{border-radius:0!important}
body.ss-wow #ss-app :is(#shop,#services,#examples,#dashboard,#reviews,#free){position:relative;margin:16px -12px 0!important;padding-left:12px!important;padding-right:12px!important;background:radial-gradient(55% 70% at 50% -10%,rgba(59,130,246,.30),transparent 70%),radial-gradient(35% 55% at 12% 0%,rgba(37,99,235,.22),transparent 72%),radial-gradient(35% 55% at 88% 0%,rgba(29,78,216,.22),transparent 72%),radial-gradient(70% 40% at 50% 100%,rgba(30,58,138,.28),transparent 75%),linear-gradient(180deg,#0e1a36 0%,#0b1226 55%,#0a0f1f 100%);border:1px solid rgba(96,165,250,.10);border-radius:0}
body.ss-wow #ss-app :is(#shop,#services,#examples,#dashboard,#reviews,#free):before{content:"";position:absolute;left:8%;right:8%;top:0;height:1px;background:linear-gradient(90deg,transparent,rgba(147,197,253,.45),transparent);pointer-events:none}
/* Finishing card: A/B players inside the square card */
body.ss-wow #ss-app .ssx .ssw-match.ssw-ab{cursor:default;text-align:left!important;justify-content:flex-start!important;gap:8px!important}
body.ss-wow #ss-app .ssx .ssw-match.ssw-ab .abt{display:block;margin-top:6px;color:#fff;font:600 13.5px "Exo 2",sans-serif}
body.ss-wow #ss-app .ssx .ssw-match.ssw-ab .ss-ab{width:100%;margin:0}
body.ss-wow #ss-app .ssx .ssw-match.ssw-ab>small{margin-bottom:4px}
@media(min-width:1001px){body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>.ssw-match.ssw-ab{aspect-ratio:auto!important}}
/* Releasing: the pack the visitor builds, live */
body.ss-wow #ss-app .ssx .ssw-match.ssw-rel{cursor:default;text-align:left!important;justify-content:flex-start!important;gap:6px!important}
body.ss-wow #ss-app .ssx .ssw-rel .rl{display:flex;flex-direction:column;gap:6px;margin-top:6px}
body.ss-wow #ss-app .ssx .ssw-rel .ri{display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:8px;border:1px solid rgba(255,255,255,.08);color:#64748B;cursor:pointer;transition:background .15s,color .15s}
body.ss-wow #ss-app .ssx .ssw-rel .ri em{flex:1;font:600 13.5px "Exo 2",sans-serif;font-style:normal}
body.ss-wow #ss-app .ssx .ssw-rel .ri i{font-style:normal;font-weight:800}
body.ss-wow #ss-app .ssx .ssw-rel .ri.on{color:#fff;background:rgba(245,158,11,.10);border-color:rgba(245,158,11,.35)}
body.ss-wow #ss-app .ssx .ssw-rel .ri.on svg,body.ss-wow #ss-app .ssx .ssw-rel .ri.on i{color:#F59E0B}
@media(min-width:1001px){body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>.ssw-match.ssw-rel{aspect-ratio:auto!important}}
/* no tall empty rows when a card is shorter than the slider window */
@media(min-width:1001px){body.ss-wow #ss-app .ssx .card .left,body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match){align-content:start!important}}
/* Just starting (owner 19.11): a cover for the free pack on the right, Google + email under it; what's inside on the left */
body.ss-wow #ss-app .ssx .fp-list{list-style:none;margin:6px 0 14px;padding:0;display:grid;gap:8px}
body.ss-wow #ss-app .ssx .fp-list li{position:relative;padding-left:26px;color:#CBD5E1;font:500 15px Roboto,sans-serif}
body.ss-wow #ss-app .ssx .fp-list li:before{content:"✓";position:absolute;left:0;top:0;color:#34D399;font-weight:800}
body.ss-wow #ss-app .ssx .fp-cover{position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:flex-end;gap:4px;height:180px;max-width:420px;margin:0 0 14px;padding:16px 18px;border-radius:12px;border:1px solid rgba(245,158,11,.35);background:radial-gradient(120% 90% at 100% 0%,rgba(245,158,11,.55),transparent 60%),radial-gradient(90% 80% at 0% 100%,rgba(59,130,246,.35),transparent 65%),linear-gradient(160deg,#1a2440,#0d1424)}
body.ss-wow #ss-app .ssx .fp-cover .fp-b{position:absolute;top:14px;left:16px;padding:3px 9px;border-radius:6px;background:#34D399;color:#062014;font:800 12px "Exo 2",sans-serif;letter-spacing:.1em}
body.ss-wow #ss-app .ssx .fp-cover .fp-t{color:#fff;font:800 30px/1 "Exo 2",sans-serif;letter-spacing:-.01em;position:relative;z-index:1}
body.ss-wow #ss-app .ssx .fp-cover .fp-s{color:#F5C77A;font:600 13px "Exo 2",sans-serif;position:relative;z-index:1}
body.ss-wow #ss-app .ssx .fp-cover .fp-w{position:absolute;right:14px;top:16px;width:46%;height:56px;opacity:.85;background:repeating-linear-gradient(90deg,#F59E0B 0 3px,transparent 3px 6px);-webkit-mask:linear-gradient(180deg,transparent,#000 20%,#000 80%,transparent),url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 40' preserveAspectRatio='none'%3E%3Cpath d='M0 20 Q5 2 10 20 T20 20 T30 20 Q35 6 40 20 T50 20 Q55 0 60 20 T70 20 Q75 10 80 20 T90 20 Q95 4 100 20 L100 40 L0 40Z'/%3E%3C/svg%3E") center/100% 100%;mask:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 40' preserveAspectRatio='none'%3E%3Cpath d='M0 20 Q5 2 10 20 T20 20 T30 20 Q35 6 40 20 T50 20 Q55 0 60 20 T70 20 Q75 10 80 20 T90 20 Q95 4 100 20 Q95 36 90 20 T80 20 Q75 32 70 20 T60 20 Q55 40 50 20 T40 20 Q35 34 30 20 T20 20 Q15 38 10 20 T0 20Z'/%3E%3C/svg%3E") center/100% 100%}
/* phones: a slim banner, so the slider keeps its height */
@media(max-width:1000px){body.ss-wow #ss-app .ssx .fp-cover{height:88px;padding:10px 14px;margin-bottom:10px}body.ss-wow #ss-app .ssx .fp-cover .fp-t{font-size:22px}body.ss-wow #ss-app .ssx .fp-cover .fp-b{top:10px;left:auto;right:14px}body.ss-wow #ss-app .ssx .fp-cover .fp-w{display:none}body.ss-wow #ss-app .ssx .fp-list{gap:4px;margin:2px 0 10px}body.ss-wow #ss-app .ssx .fp-list li{font-size:14px}}
@media(min-width:1001px){
body.ss-wow #ss-app .ssx .card .left.fp{display:grid!important;grid-template-columns:minmax(0,1fr) 300px!important;column-gap:36px!important;align-content:start!important;align-items:start!important}
body.ss-wow #ss-app .ssx .card .left.fp>*{grid-column:1!important;margin-left:0!important}
body.ss-wow #ss-app .ssx .card .left.fp>.fp-cover,body.ss-wow #ss-app .ssx .card .left.fp>.acts,body.ss-wow #ss-app .ssx .card .left.fp>.fp-or,body.ss-wow #ss-app .ssx .card .left.fp>form,body.ss-wow #ss-app .ssx .card .left.fp>.fp-note{grid-column:2!important;width:100%!important;max-width:none!important}
body.ss-wow #ss-app .ssx .card .left.fp>.fp-cover{grid-row:1 / span 3!important;height:130px!important;margin:0 0 10px!important}
body.ss-wow #ss-app .ssx .card .left.fp>.fp-or{margin:4px 0!important}body.ss-wow #ss-app .ssx .card .left.fp>form{gap:8px!important}body.ss-wow #ss-app .ssx .card .left.fp>.fp-note{margin-top:6px!important}
body.ss-wow #ss-app .ssx .card .left.fp>.acts .gbtn{width:100%!important;justify-content:center!important;box-sizing:border-box}
body.ss-wow #ss-app .ssx .card .left.fp>.fp-or{margin:8px 0!important;text-align:center}
}
/* DJ set pack: two columns of items so it stays compact */
body.ss-wow #ss-app .ssx .ssw-dj .ri{padding:6px 9px}
body.ss-wow #ss-app .ssx .ssw-dj .ri em{font-size:13px}
body.ss-wow #ss-app .ssx .fp-links{display:flex;flex-wrap:wrap;gap:6px 18px;margin:0 0 10px}
body.ss-wow #ss-app .ssx .fp-links a{color:#F59E0B;font-weight:600;font-size:14px;text-decoration:none}
body.ss-wow #ss-app .ssx .fp-links a:hover{text-decoration:underline}
/* Writing a track: add-ons the visitor ticked, under "N templates match" */
body.ss-wow #ss-app .ssx .ssw-match .adds{display:flex;flex-wrap:wrap;justify-content:center;gap:6px;margin-top:10px}
body.ss-wow #ss-app .ssx .ssw-match .adds em{font-style:normal;font:600 12.5px "Exo 2",sans-serif;color:#F59E0B;background:rgba(245,158,11,.10);border:1px solid rgba(245,158,11,.35);border-radius:6px;padding:4px 8px}
/* phones (QA 19.11): the new cards stay inside the screen and short */
@media(max-width:1000px){
body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-ab,body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-rel{margin:10px 0 0!important;width:100%!important;max-width:100%!important;min-width:0!important;box-sizing:border-box!important;padding:10px!important}
body.ss-wow #ss-app .ssx .ssw-rel .rl{flex-direction:row;flex-wrap:wrap;gap:6px;margin-top:4px}
body.ss-wow #ss-app .ssx .ssw-rel .ri{padding:5px 8px;gap:6px}
body.ss-wow #ss-app .ssx .ssw-rel .ri svg{display:none}
body.ss-wow #ss-app .ssx .ssw-rel .ri em{flex:0 1 auto;min-width:0;white-space:normal;font-size:12.5px}
body.ss-wow #ss-app .ssx .ssw-vd .vd-ex{display:none!important} /* phones: the examples are one tap away under «Before / after» */
body.ss-wow #ss-app .ssx .ssw-vd .ri{width:auto}body.ss-wow #ss-app .ssx .ssw-vd .ri.on{width:100%}body.ss-wow #ss-app .ssx .ssw-vd .ri em{flex:1 1 auto}
body.ss-wow #ss-app .ssx .ssw-vd .vd-ex .mx{width:100%!important;min-width:0}
body.ss-wow #ss-app .ssx .ssw-rel:not(.ssw-vd) .ri{padding:4px 8px!important;gap:5px!important;border-radius:6px}body.ss-wow #ss-app .ssx .ssw-rel:not(.ssw-vd) .ri em{font-size:12px!important;white-space:nowrap!important}body.ss-wow #ss-app .ssx .ssw-rel:not(.ssw-vd) .ri i{font-size:11px}body.ss-wow #ss-app .ssx .ssw-rel:not(.ssw-vd) .rl{gap:5px!important}body.ss-wow #ss-app .ssx .ssw-rel>b{font-size:15px!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-dj) .f .chips.one{flex-wrap:wrap!important;overflow:visible!important}body.ss-wow #ss-app .ssx .ssw-vd .vd-ex img{width:100%!important;height:auto!important}
body.ss-wow #ss-app .ssx .ssw-ab .ss-ab{max-width:100%;min-width:0}
body.ss-wow #ss-app .ssx .ssw-ab .abt:nth-of-type(n+2),body.ss-wow #ss-app .ssx .ssw-ab .ss-ab:nth-of-type(n+2){display:none}
body.ss-wow #ss-app .ssx .ssw-ab>small{display:none}
/* Releasing / DJ set on phones: the items are toggled in the card itself — no second copy of them on the left */
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-rel)>.f:has(>.chips:not(.one)){display:none!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-vd) .f .chips{flex-wrap:wrap!important;overflow:visible!important;white-space:normal!important}
body.ss-wow #ss-app .ssx .ssw-rel .ri{max-width:100%;box-sizing:border-box}body.ss-wow #ss-app .ssx .ssw-rel .ri em{white-space:normal;min-width:0}
}
/* Video: two example videos under "Your fix" */
@media(min-width:1001px){ /* owner 19.11 «слайдер длинный стал»: Video was the tallest tab (689px) — compact fix list, wrap the pain chips */
body.ss-wow #ss-app#ss-app .ssx .card .left:has(>.ssw-vd)>.f:not(.row){grid-column:1!important;grid-template-columns:var(--lbl,150px) minmax(0,1fr)!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-vd)>.f.row>div{grid-template-columns:var(--lbl,190px) minmax(0,1fr)!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-vd) .f .chips{flex-wrap:wrap!important;overflow:visible!important;row-gap:8px!important}
body.ss-wow #ss-app .ssx .ssw-vd{gap:8px!important;padding:14px!important}
body.ss-wow #ss-app .ssx .ssw-vd .rl{gap:5px!important}
body.ss-wow #ss-app .ssx .ssw-vd .ri{padding:5px 10px!important;min-height:0!important}body.ss-wow #ss-app .ssx .ssw-vd .ri em{font-size:12.5px!important}
body.ss-wow #ss-app .ssx .ssw-vd .vd-ex{display:none!important} /* examples open from «Before / after» */}
body.ss-wow #ss-app .ssx .ssw-vd .vd-ex{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:10px}
body.ss-wow #ss-app .ssx .ssw-vd .vd-ex .mx{margin:0!important}
body.ss-wow #ss-app .ssx .ssw-vd .vd-ex img{width:100%!important;height:auto!important;aspect-ratio:16/9!important;object-fit:cover;border-radius:8px}
body.ss-wow #ss-app .ssx .ssw-vd .ri em{font-size:12.5px}
/* Get help — the first tab (owner 19.11): the biggest pains, each opens with the answer and one step */
body.ss-wow #ss-app .ssx .hp-acc{display:flex;flex-direction:column;gap:6px;margin:4px 0 0}
body.ss-wow #ss-app .ssx .hp-acc details{border:1px solid rgba(255,255,255,.08);border-radius:8px;background:rgba(255,255,255,.02);overflow:hidden}
body.ss-wow #ss-app .ssx .hp-acc details[open]{border-color:rgba(245,158,11,.35);background:rgba(245,158,11,.06)}
body.ss-wow #ss-app .ssx .hp-acc summary{list-style:none;cursor:pointer;display:flex;align-items:center;justify-content:space-between;gap:10px;padding:10px 12px;color:#fff;font:600 14.5px "Exo 2",sans-serif}
body.ss-wow #ss-app .ssx .hp-acc summary::-webkit-details-marker{display:none}
body.ss-wow #ss-app .ssx .hp-acc summary:after{content:"+";color:#F59E0B;font-weight:800;font-size:18px;line-height:1}
body.ss-wow #ss-app .ssx .hp-acc details[open] summary:after{content:"–"}
body.ss-wow #ss-app .ssx .hp-acc p{margin:0;padding:0 12px 6px;color:#CBD5E1;font:400 14px/1.5 Roboto,sans-serif}
body.ss-wow #ss-app .ssx .hp-acc .hp-go{display:inline-block;margin:0 12px 10px;color:#F59E0B;font:700 14px "Exo 2",sans-serif;text-decoration:none}
body.ss-wow #ss-app .ssx .hp-acc .hp-go:hover{text-decoration:underline}
/* "Why producers work with us" — 4 advantages */
.ss-why{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin:18px 0 22px}
.ss-why .w{display:flex;flex-direction:column;gap:6px;padding:16px;border-radius:12px;border:1px solid rgba(255,255,255,.08);background:linear-gradient(180deg,rgba(245,158,11,.08),rgba(255,255,255,.02))}
.ss-why .i{display:flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:10px;background:rgba(245,158,11,.14);color:#F59E0B;margin-bottom:4px}
.ss-why b{color:#fff;font:800 16px/1.25 "Exo 2",sans-serif}
.ss-why small{color:#94A3B8;font:400 14px/1.45 Roboto,sans-serif}
@media(max-width:1000px){.ss-why{grid-template-columns:1fr 1fr}}
@media(max-width:520px){.ss-why{gap:8px}.ss-why .w{padding:12px}.ss-why b{font-size:14.5px}.ss-why small{font-size:13px}.ss-why .i{width:38px;height:38px}}
.ss-why2{margin:56px 0 0}
.ss-why2 h2.t{margin:0 0 6px;color:#fff;font:700 32px/1.2 "Exo 2",sans-serif}
.ss-why2 h3.g{margin:22px 0 12px;color:#fff;font:400 26px/1.2 Roboto,sans-serif}
.ss-why2 .gr{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}
.ss-why2 .w{display:flex;flex-direction:column;gap:8px;padding:22px;border-radius:14px;border:1px solid rgba(255,255,255,.08);background:#0f1729;text-decoration:none!important;transition:border-color .2s}
.ss-why2 .w:hover{border-color:#F59E0B}
.ss-why2 .i{display:flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:10px;border:1px solid rgba(245,158,11,.35);background:rgba(245,158,11,.12);color:#cbd5e1;margin-bottom:8px}
.ss-why2 b{color:#fff;font:600 19px/1.4 "Exo 2",sans-serif;letter-spacing:.2px}
.ss-why2 b i{display:inline-block;margin-left:4px;padding:1px 9px;border-radius:999px;background:rgba(52,211,153,.14);color:#34d399;font:italic 600 13px/1.6 "Exo 2",sans-serif;letter-spacing:.5px;vertical-align:2px}
.ss-why2 small{flex:1;color:#94A3B8;font:400 15.5px/1.6 "Exo 2",sans-serif}
.ss-why2 em{margin-top:10px;color:#F59E0B;font:500 17px/1.3 "Exo 2",sans-serif;font-style:normal}
.ss-why2 .all{margin:22px 0 0;text-align:center}.ss-why2 .all a{color:#F59E0B!important;font-weight:700;text-decoration:none}
@media(max-width:1000px){.ss-why2 .gr{grid-template-columns:1fr 1fr}}
@media(max-width:600px){.ss-why2{margin-top:36px}.ss-why2 h2.t{font-size:24px}.ss-why2 h3.g{font-size:20px;margin:18px 0 10px}.ss-why2 .gr{grid-template-columns:1fr;gap:10px}.ss-why2 .w{padding:16px;gap:6px}.ss-why2 .i{width:40px;height:40px}.ss-why2 b{font-size:16.5px}.ss-why2 small{font-size:14px}.ss-why2 em{font-size:15px;margin-top:6px}}
/* Writing a track: «Only an idea? Custom production →» sits in the buttons row (no extra line — the slider stays short) */
body.ss-wow #ss-app .ssx .card .left .acts{flex-wrap:wrap}
body.ss-wow #ss-app .ssx .card .left .acts .ss-cp{flex:1 1 auto;align-self:center;margin-right:auto;color:#94A3B8;font:500 14px Roboto,sans-serif;text-decoration:none;min-width:0}
body.ss-wow #ss-app .ssx .card .left .acts .ss-cp b{color:#F59E0B;font-weight:700}body.ss-wow #ss-app .ssx .card .left .acts .ss-cp:hover b{text-decoration:underline}
@media(max-width:600px){body.ss-wow #ss-app .ssx .card .left .acts .ss-cp{flex-basis:100%;order:5;text-align:center;font-size:13px}}
/* Finishing: the description box on the right (replaces the before/after box) */
body.ss-wow #ss-app .ssx .ssw-match.ssw-desc{cursor:default;display:flex!important;flex-direction:column!important;align-items:flex-start!important;justify-content:flex-start!important;text-align:left!important;gap:6px!important;aspect-ratio:auto!important;padding:18px!important}
body.ss-wow #ss-app .ssx .ssw-desc b{color:#fff;font:800 19px/1.25 "Exo 2",sans-serif}body.ss-wow #ss-app .ssx .ssw-desc .pp{color:#F59E0B;font:700 15px "Exo 2",sans-serif}
body.ss-wow #ss-app .ssx .ssw-desc p{margin:4px 0 0;color:#CBD5E1;font:400 14px/1.5 Roboto,sans-serif}
body.ss-wow #ss-app .ssx .ssw-desc ul{margin:6px 0 0;padding:0;list-style:none}body.ss-wow #ss-app .ssx .ssw-desc li{position:relative;padding-left:18px;margin:3px 0;color:#94A3B8;font:400 13px Roboto,sans-serif}body.ss-wow #ss-app .ssx .ssw-desc li:before{content:"✓";position:absolute;left:0;color:#34D399;font-weight:800}body.ss-wow #ss-app .ssx .ssw-desc .pay{margin-top:6px;color:#94A3B8;font:400 12.5px/1.4 Roboto,sans-serif}
/* Free — the first tab (owner 19.11): 3 free blocks + the "Get everything free" card */
body.ss-wow #ss-app .ssx .card .left.hp{display:grid!important;grid-template-columns:minmax(0,1fr) 300px!important;gap:12px 20px!important;align-items:stretch!important;align-content:center!important}
body.ss-wow #ss-app .ssx .card .left.hp>.fz-tiles{grid-column:1!important;grid-row:1!important}
body.ss-wow #ss-app .ssx .card .left.hp>.hp-card{grid-column:2!important;grid-row:1!important;align-self:center!important;display:flex!important;flex-direction:column;justify-content:center;gap:8px;padding:20px;border-radius:12px;border:1px solid rgba(245,158,11,.35);background:radial-gradient(120% 90% at 100% 0%,rgba(245,158,11,.35),transparent 60%),#17181b}
body.ss-wow #ss-app .ssx .hp-card .hp-b{align-self:flex-start;padding:3px 9px;border-radius:6px;background:#34D399;color:#062014;font:800 12px "Exo 2",sans-serif;letter-spacing:.1em}
body.ss-wow #ss-app .ssx .hp-card b{color:#fff;font:800 22px/1.2 "Exo 2",sans-serif}
body.ss-wow #ss-app .ssx .hp-card small{color:#CBD5E1;font:400 14px/1.45 Roboto,sans-serif}
body.ss-wow #ss-app .ssx .hp-card .cta{margin-top:6px;justify-content:center;text-align:center}
body.ss-wow #ss-app .ssx .fz-tiles{display:grid!important;grid-template-columns:1fr!important;grid-auto-rows:min-content;align-content:center;align-self:center!important;gap:10px;margin:0}
body.ss-wow #ss-app .ssx .fz-t{display:flex!important;flex-direction:row!important;align-items:center!important;justify-content:flex-start!important;text-align:left!important;min-height:0!important;height:auto!important;gap:14px;padding:14px 16px;border-radius:12px;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.02);text-decoration:none!important;transition:border-color .15s,background .15s}
body.ss-wow #ss-app .ssx .fz-t:hover{border-color:rgba(245,158,11,.45);background:rgba(245,158,11,.06)}
body.ss-wow #ss-app .ssx .fz-t .i{flex:0 0 40px;margin:0!important;display:flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:10px;background:rgba(52,211,153,.14);color:#34D399}
body.ss-wow #ss-app .ssx .fz-t .fz-x{display:flex;flex-direction:column;align-items:flex-start;gap:3px;min-width:0;flex:1}
body.ss-wow #ss-app .ssx .fz-t b{color:#fff;font:700 16px/1.25 "Exo 2",sans-serif}
body.ss-wow #ss-app .ssx .fz-t b i{display:inline-block;margin-right:6px;padding:1px 7px;border-radius:5px;background:#34D399;color:#062014;font:800 11px/1.5 "Exo 2",sans-serif;font-style:normal;letter-spacing:.08em;vertical-align:2px}
body.ss-wow #ss-app .ssx .fz-t small{color:#94A3B8;font:400 13.5px/1.35 Roboto,sans-serif}
body.ss-wow #ss-app .ssx .fz-t:after{content:"→"!important;position:static!important;margin-left:auto;color:#F59E0B;font:700 18px "Exo 2",sans-serif}
@media(min-width:1001px){
body.ss-wow #ss-app .ssx .card .left.hp{grid-template-columns:minmax(0,1fr) 340px!important;gap:16px 24px!important;align-content:stretch!important;align-items:stretch!important}
body.ss-wow #ss-app .ssx .card .left.hp>.fz-tiles{grid-auto-rows:1fr!important;align-self:stretch!important;gap:16px}
body.ss-wow #ss-app .ssx .card .left.hp>.hp-card{align-self:stretch!important;padding:30px;gap:12px}
body.ss-wow #ss-app .ssx .fz-t{padding:20px 26px;gap:22px;border-radius:14px}
body.ss-wow #ss-app .ssx .fz-t .i{flex-basis:60px;width:60px;height:60px;border-radius:14px}
body.ss-wow #ss-app .ssx .fz-t .i svg{width:28px;height:28px}
body.ss-wow #ss-app .ssx .fz-t b{font-size:22px}
body.ss-wow #ss-app .ssx .fz-t b i{font-size:12.5px;padding:2px 9px;vertical-align:3px}
body.ss-wow #ss-app .ssx .fz-t small{font-size:15.5px}
body.ss-wow #ss-app .ssx .fz-t:after{font-size:24px}
body.ss-wow #ss-app .ssx .hp-card b{font-size:30px}
body.ss-wow #ss-app .ssx .hp-card small{font-size:16px}
body.ss-wow #ss-app .ssx .hp-card .cta{padding:16px!important;font-size:17px!important}
}
/* owner 20.11 «на главной тоже не влазит на ноуте»: laptops (short screens) — slimmer Free tiles and card, the whole tab on the first screen */
@media(min-width:1001px) and (max-height:1000px){
body.ss-wow #ss-app .ssx .card .left.hp>.fz-tiles{gap:10px}
body.ss-wow #ss-app .ssx .fz-t{padding:11px 22px;gap:18px}
body.ss-wow #ss-app .ssx .fz-t .i{flex-basis:44px;width:44px;height:44px;border-radius:11px}
body.ss-wow #ss-app .ssx .fz-t .i svg{width:22px;height:22px}
body.ss-wow #ss-app .ssx .fz-t b{font-size:19px}
body.ss-wow #ss-app .ssx .fz-t small{font-size:14.5px}
body.ss-wow #ss-app .ssx .fz-t:after{font-size:20px}
body.ss-wow #ss-app .ssx .card .left.hp>.hp-card{padding:20px 24px;gap:8px}
body.ss-wow #ss-app .ssx .hp-card b{font-size:24px}
body.ss-wow #ss-app .ssx .hp-card small{font-size:14.5px}
body.ss-wow #ss-app .ssx .hp-card .cta{padding:12px!important;font-size:16px!important}
.ssw-hero .slg{font-size:34px!important;margin-bottom:4px!important}
/* (owner 20.11 «все секции адаптируй») every tab ≤ ~410 px: the right column decided the height */
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-match)>.ssw-match:not(.ssw-ab):not(.ssw-rel){aspect-ratio:auto!important}
body.ss-wow #ss-app .ssx .ssw-ab .abt:nth-of-type(n+2),body.ss-wow #ss-app .ssx .ssw-ab .ss-ab:nth-of-type(n+2),body.ss-wow #ss-app .ssx .ssw-ab>small{display:none!important}
body.ss-wow #ss-app .ssx .card .left:has(input[type=range])>.f:has(.chips.addon){display:none!important}
body.ss-wow #ss-app .ssx .card .left.fp>h2{grid-row:1!important}body.ss-wow #ss-app .ssx .card .left.fp>h2+p.d{grid-row:2!important}
body.ss-wow #ss-app .ssx .card .left.fp>.fp-list{grid-row:3 / span 2!important;margin-bottom:0!important}body.ss-wow #ss-app .ssx .card .left.fp>.fp-links{grid-row:5!important;align-self:start!important}
body.ss-wow #ss-app .ssx .card .left.fp>.fp-cover{grid-row:1 / span 2!important}body.ss-wow #ss-app .ssx .card .left.fp>div.acts{grid-row:3!important}
body.ss-wow #ss-app .ssx .card .left.fp>.fp-or{grid-row:4!important}body.ss-wow #ss-app .ssx .card .left.fp>form{grid-row:5!important}body.ss-wow #ss-app .ssx .card .left.fp>.fp-note{grid-row:6!important}
body.ss-wow #ss-app .ssx .card .left.fp>.fp-cover{height:96px!important;padding:10px 16px!important}
body.ss-wow #ss-app .ssx .card .left.fp>.fp-cover .fp-t{font-size:24px!important}
body.ss-wow #ss-app .ssx .card .left.fp>.fp-or{margin:2px 0!important}
}
/* laptops — the slider does not jump and every tab fits the first screen (owner 20.11).
   Owner's laptop: 1920 px at 125 % Windows scale = 1536×704 in the browser, 672 with the admin bar. */
@media(min-width:1001px){
 /* every card as tall as the tallest one: the window keeps one height, nothing below moves when the tabs turn */
 body.ss-wow #ss-app .ssx .track{align-items:stretch!important}
 body.ss-wow #ss-app .ssx .card .left.fp{align-content:start!important}
}
@media(min-width:1001px) and (max-height:1000px){
 /* hero: one line, no pill; the "What are you working on?" line stays for search engines and screen readers only */
 /* (20.11 owner: «слайдер съехал вверх и перекрыл заголовок, и там ещё было 3 слова — верни как было») title, the pill, then the slider */
 body.ss-wow .ssw-hero{padding:14px 12px 12px!important}
 body.ss-wow .ssw-hero .slg{font-size:36px!important;margin:0 auto 8px!important;line-height:1.1!important}
 body.ss-wow #ss-app .ssx{padding-top:6px!important;padding-bottom:4px!important}
 body.ss-wow #ss-app .ssx h1.ssx-t{margin:0 0 6px!important;font-size:16px!important} /* (20.11 owner: «вернуть надпись What are you working on») */
 body.ss-wow #ss-app .ssx .tasks{margin-bottom:8px!important}
 body.ss-wow #ss-app .ssx .tasks button{padding-top:8px!important;padding-bottom:8px!important}
 body.ss-wow #ss-app .ssx .card{padding-top:10px!important;padding-bottom:10px!important}
 body.ss-wow #ss-app .ssx .card .left>h2{margin-bottom:2px!important}
 body.ss-wow #ss-app .ssx .card .left>.f:not(.row){margin:0 0 8px!important}
 /* packs (Releasing / DJ set / Video): slim rows */
 body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-rel{padding:8px 12px!important;margin-top:2px!important;gap:2px!important}
 body.ss-wow #ss-app .ssx .ssw-rel .rl{gap:6px!important;margin-top:4px!important}
 body.ss-wow #ss-app .ssx .ssw-rel .ri{padding:4px 10px!important;min-height:0!important}
 body.ss-wow #ss-app .ssx .card .left>.acts:not(:has(~ .acts)),body.ss-wow #ss-app .ssx .card .left>.cta{padding-top:8px!important}
 /* Just starting: no cover picture on short screens (as on phones) — Google / email start at the top */
 body.ss-wow #ss-app .ssx .card .left.fp>.fp-cover{display:none!important}
 body.ss-wow#ss-app .ssx .card .left.fp,#ss-app#ss-app .ssx .card .left.fp{row-gap:8px!important}
 #ss-app#ss-app .ssx .card .left.fp>h2{grid-row:1!important}#ss-app#ss-app .ssx .card .left.fp>h2+p.d{grid-row:2!important}
 #ss-app#ss-app .ssx .card .left.fp>.fp-list{grid-row:3 / span 2!important}#ss-app#ss-app .ssx .card .left.fp>.fp-links{grid-row:5!important}
 #ss-app#ss-app .ssx .card .left.fp>div.acts{grid-row:1 / span 2!important;align-self:start!important}#ss-app#ss-app .ssx .card .left.fp>.fp-or{grid-row:3!important}
 #ss-app#ss-app .ssx .card .left.fp>form{grid-row:4!important}#ss-app#ss-app .ssx .card .left.fp>.fp-note{grid-row:5!important}
 /* buttons a little lower in height */
 body.ss-wow #ss-app .ssx .card .left .cta,body.ss-wow #ss-app .ssx .card .left .gbtn,body.ss-wow #ss-app .ssx .card .left form.acts .inp{min-height:42px!important;height:42px!important}
 body.ss-wow #ss-app .ssx .card .left.fp>.fp-or{margin:0!important}
}
@media(max-width:1000px){body.ss-wow #ss-app .ssx .card .left.hp{grid-template-columns:1fr!important;align-content:start!important}body.ss-wow #ss-app .ssx .card .left.hp>.hp-card{grid-column:1!important;grid-row:2!important;padding:16px}body.ss-wow #ss-app .ssx .hp-card b{font-size:19px}}
@media(max-width:600px){body.ss-wow #ss-app .ssx .fz-tiles{gap:8px}body.ss-wow #ss-app .ssx .fz-t{padding:11px 12px;gap:12px}body.ss-wow #ss-app .ssx .fz-t .i{flex-basis:36px;width:36px;height:36px}body.ss-wow #ss-app .ssx .fz-t b{font-size:15px}body.ss-wow #ss-app .ssx .fz-t small{font-size:12.5px}body.ss-wow #ss-app .ssx .hp-card small{display:none}}
/* owner 20.11 «туго листается, косо, всё съехало»: every row of choices wraps inside its own column — on a PC a long row
   (Releasing: «Metadata & ISRC») ran under the box on the right, on a phone rows ran off the edge and scrolled sideways,
   so a finger on them moved the row instead of the slide */
body.ss-wow #ss-app .ssx .card .left .f .chips{flex-wrap:wrap!important;overflow:visible!important;margin-left:0!important;margin-right:0!important;padding-left:0!important;padding-right:0!important;min-width:0!important;max-width:100%!important;row-gap:8px!important}
body.ss-wow #ss-app .ssx .card .left .f .chips span{flex:0 1 auto!important;max-width:100%!important;white-space:normal!important}
body.ss-wow #ss-app .ssx .card .left .f{min-width:0!important}
/* the same items are switched on/off in the box on the right («Your release pack» / set / video pack) — the duplicate row on the
   left is not shown on any screen (on PCs it ran under the box) */
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-rel)>.f:has(>.chips:not(.one)){display:none!important}
/* owner 20.11 «компактно»: on PCs the pack (Releasing / DJ set / Video) goes under the title in 3 columns instead of a tall
   narrow box on the right with an empty left half; the buttons sit under it on the right */
@media(min-width:1001px){
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-rel){grid-template-columns:minmax(0,1fr)!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-rel)>.ssw-match.ssw-rel{grid-column:1!important;grid-row:auto!important;aspect-ratio:auto!important;margin:14px 0 0!important;padding:14px 16px!important;text-align:left!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-rel)>.ssw-match.ssw-rel .rl{display:grid!important;grid-template-columns:repeat(3,minmax(0,1fr))!important;gap:8px!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-rel)>.acts{grid-column:1!important;grid-row:auto!important;justify-content:flex-end!important;margin:14px 0 0!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-rel)>.acts>a{flex:0 0 auto!important;min-width:220px}
}
/* owner 20.11 (A31 screenshot: «на телефоне косо каждая вкладка, слайдер не влазит»): on phones and tablets everything in a card is
   ONE column — the pack box (Releasing / DJ set / Video) and the before/after box stand as a block: title on top, items under it
   in two columns, nothing cut; the labels (GENRE, BPM…) stand above their buttons instead of a column on the left */
@media(max-width:1000px){
body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-rel,body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-ab{display:flex!important;flex-direction:column!important;align-items:stretch!important;justify-content:flex-start!important;text-align:left!important;aspect-ratio:auto!important;height:auto!important;padding:12px!important;gap:6px!important;width:100%!important;box-sizing:border-box!important}
body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-rel>b,body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-rel>small,body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-ab>b,body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-ab>small{display:block!important;width:auto!important;max-width:none!important;white-space:normal!important;overflow:visible!important;text-overflow:clip!important;text-align:left!important}
body.ss-wow #ss-app .ssx .ssw-rel .rl{display:grid!important;grid-template-columns:repeat(2,minmax(0,1fr))!important;gap:6px!important;width:100%!important;margin:4px 0 0!important}
body.ss-wow #ss-app .ssx .ssw-rel .ri{display:flex!important;align-items:center!important;justify-content:space-between!important;gap:6px!important;min-width:0!important;max-width:none!important;width:auto!important;padding:8px 10px!important;box-sizing:border-box!important}
body.ss-wow #ss-app .ssx .ssw-rel .ri em{flex:1 1 auto!important;min-width:0!important;white-space:normal!important;overflow:visible!important;text-overflow:clip!important;overflow-wrap:anywhere!important;font-size:13px!important;line-height:1.25!important;text-align:left!important}
body.ss-wow #ss-app .ssx .ssw-rel .ri i{flex:none!important}
body.ss-wow #ss-app .ssx .ssw-ab .ss-ab{width:100%!important;max-width:100%!important}
body.ss-wow #ss-app .ssx .card .left>.f:not(.row),body.ss-wow #ss-app .ssx .card .left>.f.row>div{display:block!important}
body.ss-wow #ss-app .ssx .card .left>.f:not(.row)>b,body.ss-wow #ss-app .ssx .card .left>.f.row>div>b{display:block!important;margin:0 0 6px!important}
body.ss-wow #ss-app .ssx .card .left>.f.row{display:block!important}
body.ss-wow #ss-app .ssx .card .left>.f.row>div{margin:0 0 12px!important}
body.ss-wow #ss-app .ssx .card .left>.f.row>.chk{margin:0 0 12px!important}
}
@media(max-width:1000px){
/* long items wrap to a second line instead of running under the ✓ (an older rule kept them on one line) */
body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-rel .rl .ri em{white-space:normal!important;overflow:visible!important;text-overflow:clip!important;font-size:12.5px!important}
/* before / after switch: two equal halves, full width */
body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-ab .ss-abs{display:grid!important;grid-template-columns:1fr 1fr!important;width:100%!important;min-width:0!important}
/* «не влазит»: smaller choice buttons on phones so a card fits the screen */
body.ss-wow #ss-app .ssx .card .left .f .chips{gap:6px!important;row-gap:6px!important}
body.ss-wow #ss-app .ssx .card .left .f .chips span{height:34px!important;min-height:34px!important;padding:0 11px!important;font-size:13.5px!important}
body.ss-wow #ss-app .ssx .card .left>.f:not(.row),body.ss-wow #ss-app .ssx .card .left>.f.row>div{margin:0 0 10px!important}
}
@media(max-width:360px){body.ss-wow #ss-app .ssx .ssw-rel .rl{grid-template-columns:minmax(0,1fr)!important}}
/* (20.11 owner: «при наведении все такие кнопки» — the theme paints hovered links orange, so the text vanished on the orange buttons) */
#ss-app .ssx a.cta:not(.g):hover,#ss-app .ssx a.cta:not(.g):focus,#ss-app .ssx button.cta:hover,#ss-app .ssx .hp-card a.cta:hover{color:#111!important;filter:brightness(1.07)}
#ss-app .ssx a.cta.g:hover,#ss-app .ssx a.cta.g:focus{color:#fff!important;filter:brightness(1.15)}
/* (20.11 owner, screenshot: «наехало — перенеси под заголовок») computer: in a card with the before/after player on the right the
   choice row («What do you need?») stands UNDER its label and wraps inside the left column instead of running under the player */
@media(min-width:1001px){
#ss-app#ss-app .ssx .card .left:has(>.ssw-ab)>.f{display:block!important;grid-column:1!important;min-width:0!important}
#ss-app#ss-app .ssx .card .left:has(>.ssw-ab)>.f>b{display:block!important;margin:0 0 8px!important}
#ss-app#ss-app .ssx .card .left:has(>.ssw-ab)>.f>.chips{display:flex!important;flex-wrap:wrap!important;overflow:visible!important;white-space:normal!important;gap:8px!important;margin:0!important;max-width:100%!important}
#ss-app#ss-app .ssx .card .left:has(>.ssw-ab)>.f~.d,#ss-app#ss-app .ssx .card .left:has(>.ssw-ab)>p.d{margin-left:0!important;grid-column:1!important}
}
/* (20.11 owner: «наехало» — any tab, whatever the right-hand box is) on the computer every row of choice buttons wraps inside the left
   column: the row never runs under the box on the right */
@media(min-width:1001px){
#ss-app#ss-app .ssx .card .left>.f,#ss-app#ss-app .ssx .card .left>.f.row>div{min-width:0!important;max-width:100%!important}
#ss-app#ss-app .ssx .card .left>.f .chips,#ss-app#ss-app .ssx .card .left>.f .chips.one{flex-wrap:wrap!important;overflow:visible!important;white-space:normal!important;min-width:0!important;max-width:100%!important}
}
#ss-app#ss-app .ssx .card .left.fp>.fz-tiles{grid-column:1!important;grid-row:3 / span 2!important;align-self:start!important;margin:0 0 8px!important}
@media(min-width:1001px){ /* Free tiles inside Just starting: compact on the computer */
#ss-app#ss-app .ssx .card .left.fp>.fz-tiles{gap:8px!important;grid-auto-rows:auto!important;align-self:start!important}
#ss-app#ss-app .ssx .card .left.fp .fz-t{padding:10px 14px!important;gap:12px!important;border-radius:10px!important}
#ss-app#ss-app .ssx .card .left.fp .fz-t .i{flex-basis:36px!important;width:36px!important;height:36px!important;border-radius:9px!important}
#ss-app#ss-app .ssx .card .left.fp .fz-t .i svg{width:18px!important;height:18px!important}
#ss-app#ss-app .ssx .card .left.fp .fz-t b{font-size:16px!important}#ss-app#ss-app .ssx .card .left.fp .fz-t small{font-size:13px!important}
}
#ss-app#ss-app .ssx .card .left.fp>.fp-links a{margin-right:14px}
/* (20.11 owner, A31: one slider height that fits the screen) LAST in the file on purpose — wins over the phone rules above:
   slimmer choice buttons, «Your fix» items on one line each (Video & ads was the tallest tab, 468 px) */
@media(max-width:600px){
body.ss-wow #ss-app .ssx .card .left .f .chips span{height:31px!important;min-height:31px!important;padding:0 10px!important;font-size:13px!important}
body.ss-wow #ss-app .ssx .card .left>.f:not(.row),body.ss-wow #ss-app .ssx .card .left>.f.row>div{margin:0 0 8px!important}
body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-rel.ssw-vd{padding:10px!important;margin-top:6px!important}
body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-rel.ssw-vd .rl{gap:5px!important}
body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-rel.ssw-vd .rl .ri{padding:6px 8px!important}
body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-rel.ssw-vd .rl .ri em{white-space:normal!important;overflow:visible!important;text-overflow:clip!important;font-size:12px!important;line-height:1.25!important}
body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-rel.ssw-vd>b{display:inline!important;margin:0 6px 0 0!important;font-size:15px!important}
body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-rel.ssw-vd>small{display:inline!important;font-size:12px!important}
body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-rel.ssw-vd{display:block!important}
body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-rel.ssw-vd .rl{display:grid!important;margin-top:8px!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-vd) .f .chips.one span,body.ss-wow #ss-app .ssx .card .left:has(>.ssw-vd) .f .chips span{padding:0 8px!important;font-size:12.5px!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-vd) .f .chips{gap:5px!important}
body.ss-wow #ss-app .ssx .card .left:has(input[type=range])>.f.row:has(>.chk){display:flex!important;flex-wrap:wrap!important;align-items:center!important;gap:8px 14px!important;margin:0 0 8px!important}
body.ss-wow #ss-app .ssx .card .left:has(input[type=range])>.f.row:has(>.chk)>div{margin:0!important}
body.ss-wow #ss-app .ssx .card .left:has(input[type=range])>.f.row>.chk{margin:0!important;font-size:13px!important;flex:none!important}
body.ss-wow #ss-app .ssx .card .left:has(>.ssw-vd)>.f>b{display:none!important}
body.ss-wow #ss-app .ssx .card .left>.ssw-match.ssw-rel.ssw-vd .rl .ri{padding:5px 8px!important}
body.ss-wow #ss-app .ssx{padding-top:8px!important}body.ss-wow #ss-app .ssx h1.ssx-t{margin:0 0 6px!important}
body.ss-wow #ss-app .ssx .dots{display:none!important}
}
</style>
<script>(function(){
var W=<?php echo wp_json_encode( $items ); ?>,H1=<?php echo wp_json_encode( sprintf( esc_html( $h1 ), '<span>Solid Sound</span>' ) ); ?>;
var WHY=<?php $wi = function_exists( 'ss_why_items' ) ? ss_why_items() : array(); $wo = array(); foreach ( array( 'For producers & DJs', 'For creators who sell', 'Learn & connect' ) as $g ) { if ( empty( $wi[ $g ] ) ) continue; $wo[] = array( esc_html( $g ), array_map( function ( $x ) { $x[3] = esc_url( $x[3] ); return array_map( 'esc_html', $x ); }, $wi[ $g ] ) ); } echo wp_json_encode( $wo ); ?>,WHYU=<?php echo wp_json_encode( esc_url( home_url( '/en/why-solid-sound/' ) ) ); ?>;
var TR=<?php echo wp_json_encode( array( 'lcr' => ss_home_t( 'Learn · Create · Release' ), 'cl' => ss_home_t( 'Closest to your choice' ), 'one' => ss_home_t( ' template matches' ), 'many' => ss_home_t( ' templates match' ), 'tap' => ss_home_t( ' — tap to listen' ) ) ); ?>;
function esc(s){return String(s||'').replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]})}
function track(x){return {pid:String(x.p),src:x.s,t:x.t,a:'Solid Sound',i:x.i,u:x.u}}
function play(x,a){if(x.s&&window.SSP){var q=W.filter(function(y){return y.s}).map(track);try{SSP.play(track(x),q,null);if(a){var o=document.querySelector('.ssw-row a.pl');if(o)o.classList.remove('pl');a.classList.add('pl')}return true}catch(e){}}return false}
function build(ssx){if(document.querySelector('.ssw-hero'))return;document.body.classList.add('ss-wow');
 var hero=document.createElement('div');hero.className='ssw-hero';hero.innerHTML='<p class="slg" role="heading" aria-level="2">'+H1+'</p><span class="k"><i></i>'+TR.lcr+'</span>';/* owner 16.11: slogan first, then the pill */
 ssx.parentNode.insertBefore(hero,ssx);
 var wall=document.createElement('div');wall.className='ssw-wall';wall.setAttribute('aria-hidden','true');var inr=document.createElement('div');inr.className='in';wall.appendChild(inr);hero.appendChild(wall);
 function size(){wall.style.height=(ssx.getBoundingClientRect().bottom-hero.getBoundingClientRect().top+30)+'px'}size();
 if('ResizeObserver' in window)new ResizeObserver(size).observe(ssx);window.addEventListener('resize',size,{passive:true});
 var go0=function(){var sz=innerWidth>=900?180:(innerWidth>600?132:104),n=Math.min(W.length*2,Math.ceil((wall.offsetWidth+260)/sz)+1);for(var r=0;r<4;r++){var row=document.createElement('div');row.className='ssw-row';var h='';for(var k=0;k<n;k++){var x=W[(k+r*3)%W.length];h+='<a href="'+esc(x.u)+'" data-n="'+((k+r*3)%W.length)+'" tabindex="-1"><img src="'+esc(x.i)+'" alt="" loading="lazy" decoding="async" width="168" height="168"></a>'}row.innerHTML=h;inr.appendChild(row)}
  requestAnimationFrame(function(){wall.classList.add('on')});
  wall.addEventListener('click',function(e){var a=e.target.closest('a[data-n]');if(!a)return;if(play(W[+a.getAttribute('data-n')],a))e.preventDefault()});
};
 wall.classList.add('stage');requestAnimationFrame(function(){wall.classList.add('on')}); /* owner 19.11: blue stage instead of the cover wall (go0 keeps the old wall code) */
 match(ssx);sides(ssx);freeIn(ssx)}
/* (20.11 owner: «Фри мы переносим в just starting и заменяем только то, что там есть, то чего нема — добавляем») the Free tab is gone:
   its three tiles (Track feedback, Promotion advice, Templates & presets) take the place of Just starting's list and its «Free feedback»
   link (the same things); the «Get everything free» page becomes a link next to Wiki; Google / email sign-up stay */
function freeIn(ssx){var cards=ssx.querySelectorAll('.card'),c0=cards[0],h=c0&&c0.querySelector('.left.hp'),fp=ssx.querySelector('.card .left.fp');if(!h||!fp||ssx.dataset.ssFree)return;ssx.dataset.ssFree='1';
 var tiles=h.querySelector('.fz-tiles'),hc=h.querySelector('.hp-card .cta'),list=fp.querySelector('.fp-list'),links=fp.querySelector('.fp-links');
 if(tiles){if(list)fp.replaceChild(tiles,list);else fp.insertBefore(tiles,fp.querySelector('.acts'))}
 if(links){[].forEach.call(links.querySelectorAll('a'),function(a){if(/feedback/i.test(a.textContent))a.remove()});if(hc){var a=document.createElement('a');a.href=hc.href;a.textContent='All free downloads →';links.appendChild(a)}}
 var t0=ssx.querySelector('.tasks button[data-i="0"]'),on=!!(t0&&t0.classList.contains('on'));if(t0)t0.remove();c0.remove();
 [].forEach.call(ssx.querySelectorAll('.tasks button'),function(b,k){b.setAttribute('data-i',k)});
 var d=ssx.querySelectorAll('.dots i');if(d.length>ssx.querySelectorAll('.card').length)d[0].remove();
 var cur=+(ssx.getAttribute('data-cur')||0),b=ssx.querySelectorAll('.tasks button')[on?0:Math.max(0,cur-1)];if(b)b.click()}
/* "N templates match" in the first card; updates only on the visitor's own clicks/typing (no observers) */
function match(ssx){var card=[].filter.call(ssx.querySelectorAll('.card .left'),function(l){return l.querySelector('input[type=range]')&&!l.classList.contains('hp')})[0]; /* the templates card (no longer the first: «Get help» is) */if(!card)return;var acts=card.querySelector('.acts');var box=document.createElement('div');box.className='ssw-match';box.setAttribute('role','button');box.tabIndex=0;if(acts)card.insertBefore(box,acts);else card.appendChild(box);var last='';
 function pick(){var fs=card.querySelectorAll('.f');var g=(fs[0]&&fs[0].querySelector('.chips .on')||{}).textContent||'';var bi=card.querySelector('input[type=number]');var b=+(bi&&(bi.value||bi.getAttribute('data-v')))||0;var kc=null;[].forEach.call(card.querySelectorAll('.f.row>div'),function(d){var l=d.querySelector('b');if(!kc&&l&&/^\s*(key|тональн|tonart|tonali|clave|tom\b)/i.test(l.textContent))kc=d.querySelector('.chips')});if(!kc)kc=card.querySelectorAll('.f.row .chips')[0]; /* by its label, not by position (owner 19.11 moved Key) */var k=(kc&&kc.querySelector('.on')||{}).textContent||'';return {g:g.trim().toLowerCase(),b:b,k:k.trim()}}
 function run(){var s=pick(),ok=W.filter(function(x){return (!s.g||x.g.split('|').indexOf(s.g)>-1)&&(!s.b||!x.b||Math.abs(x.b-s.b)<=6)&&(!s.k||s.k==='Any'||!x.k||x.k.toLowerCase().indexOf(s.k.toLowerCase())===0)});
  var close=!ok.length;var list=close?W.filter(function(x){return !s.g||x.g.split('|').indexOf(s.g)>-1}):ok;if(!list.length)list=W;var top=list.slice(0,3);
  var h='<span class="cv">'+top.map(function(x,i){return '<span class="mx" data-k="'+i+'" role="button" tabindex="0" title="'+esc(x.t)+'" aria-label="Play '+esc(x.t)+'"><img src="'+esc(x.i)+'" alt="" width="38" height="38"><i aria-hidden="true"></i></span>'}).join('')+'</span><span><b>'+(close?TR.cl:ok.length+(ok.length===1?TR.one:TR.many))+'</b><small>'+esc([s.g&&s.g.replace(/\b\w/g,function(c){return c.toUpperCase()}),s.b&&s.b+' BPM',s.k].filter(Boolean).join(' · '))+TR.tap+'</small>'+(function(){var ad=[].map.call(card.querySelectorAll('.chips.addon .on'),function(x){return x.textContent.trim()});return ad.length?'<span class="adds">'+ad.map(function(x){return '<em>+ '+esc(x)+'</em>'}).join('')+'</span>':''})()+'</span>';
  if(h!==last){last=h;box.innerHTML=h}box._x=top[0];box._top=top}
 function later(){requestAnimationFrame(run)}card.addEventListener('click',later);card.addEventListener('input',later);card.addEventListener('change',later);
 /* (owner 19.11) each cover plays its own template in the main player; the rest of the card plays the first one */
 box.addEventListener('click',function(e){var m=e.target.closest&&e.target.closest('.mx');var x=m&&box._top?box._top[+m.getAttribute('data-k')]:box._x;if(m)e.stopPropagation();if(!x)return;
  [].forEach.call(box.querySelectorAll('.mx'),function(y){y.classList.toggle('pl',y===m||(!m&&y.getAttribute('data-k')==='0'))});if(!play(x))location.href=x.u});box.addEventListener('keydown',function(e){if(e.key==='Enter'||e.key===' '){e.preventDefault();var m=e.target.closest&&e.target.closest('.mx');(m||box).click()}});run()}
/* (owner 19.11 «как первую страницу слайдера — для всех вкладок, где уместно») the same square card on the right for
   Finishing (client before/after examples), DJ set (music by genre / BPM / key) and Video (our video work).
   Covers are buttons: audio plays in the main player, video opens the video window. Releasing / Just starting / More: not needed. */
function wave(p){p=String(p||'');var b='',n=Math.min(p.length,48);for(var i=0;i<n;i++){var h=Math.max(3,(p.charCodeAt(i)-48)*0.9);b+='<rect x="'+(i*5+6)+'" y="'+((60-h)/2+20)+'" width="3" height="'+h+'" rx="1.5"/>'}
 return 'data:image/svg+xml,'+encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '+(n*5+12)+' 100" preserveAspectRatio="none"><rect width="100%" height="100%" fill="#101827"/><g fill="#F59E0B">'+b+'</g></svg>')}
function side(left,data){if(!left||left.querySelector('.ssw-match'))return;var acts=left.querySelector('.acts');var box=document.createElement('div');box.className='ssw-match';box.setAttribute('role','button');box.tabIndex=0;if(acts)left.insertBefore(box,acts);else left.appendChild(box);var last='',cur=[];
 function run(){var d=data();if(!d||!d.items.length){box.style.display='none';return}box.style.display='';cur=d.items;
  var h='<span class="cv">'+d.items.map(function(x,i){return '<span class="mx" data-k="'+i+'" role="button" tabindex="0" title="'+esc(x.t)+'" aria-label="'+esc((d.verb||'Play')+' '+x.t)+'"><img src="'+esc(x.i)+'" alt="" width="38" height="38" style="object-fit:cover"><i aria-hidden="true"></i></span>'}).join('')+'</span><span><b>'+esc(d.b)+'</b><small>'+esc(d.s)+'</small></span>';
  if(h!==last){last=h;box.innerHTML=h}}
 box.addEventListener('click',function(e){var m=e.target.closest&&e.target.closest('.mx');if(m)e.stopPropagation();var k=m?+m.getAttribute('data-k'):0,x=cur[k];if(!x)return;
  [].forEach.call(box.querySelectorAll('.mx'),function(y){y.classList.toggle('pl',+y.getAttribute('data-k')===k)});x.go()});
 box.addEventListener('keydown',function(e){if(e.key==='Enter'||e.key===' '){e.preventDefault();var m=e.target.closest&&e.target.closest('.mx');(m||box).click()}});
 function later(){requestAnimationFrame(run)}left.addEventListener('click',later);left.addEventListener('input',later);left.addEventListener('change',later);run()}
function sides(ssx){var L=ssx.querySelectorAll('.card .left'),AB=window.SS_AB||[];
 var on=function(left,i){var f=left.querySelectorAll('.f')[i];return ((f&&f.querySelector('.chips .on'))||{}).textContent||''};
 /* Finishing a track (owner 19.11 «примеры только с переключателями before / after»): the same A/B players as in Examples,
    filtered by the service chosen; the home app's own A/B code plays them and paints the waves */
 (function(left){if(!left||left.querySelector('.ssw-match'))return;var ex=AB.filter(function(e){return e.mp3});if(!ex.length)return;var acts=left.querySelector('.acts');
  var box=document.createElement('div');box.className='ssw-match ssw-ab';if(acts)left.insertBefore(box,acts);else left.appendChild(box);var last='';
  function run(){var c=on(left,0),g=/^master/i.test(c)?'master':(/mix/i.test(c)?'mix':'');var l=ex.filter(function(e){return !g||e.g===g});ex.forEach(function(e){if(l.length<2&&l.indexOf(e)<0)l.push(e)});l=l.slice(0,2);
   var key=l.map(function(e){return e.id}).join();if(key===last)return;last=key;
   box.innerHTML='<b>Hear before → after</b><small>Client tracks — switch BEFORE / AFTER while it plays</small>'+l.map(function(e){var t=String(e.title||'').replace(/\s*—\s*client track$/i,'');return '<span class="abt">'+esc(t)+'</span>'+'<div class="ss-ab" data-src="'+esc(e.mp3)+'" data-title="'+esc(t)+'" data-pb="'+esc(e.pb||'')+'" data-pa="'+esc(e.pa||'')+'"><div class="ss-abp"><button type="button" class="ss-abb" aria-label="Play '+esc(t)+'" aria-pressed="false"><svg class="pl" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 4l13 8-13 8z"></path></svg><svg class="pa" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 4h4v16H6zM14 4h4v16h-4z"></path></svg></button><div class="ss-abw" role="slider" aria-label="Seek" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><i class="b"></i><i class="a"></i><u><i class="b"></i><i class="a"></i></u></div></div><button type="button" class="ss-abs" role="switch" aria-checked="false" aria-label="Before or after — '+esc(t)+'"><i aria-hidden="true"></i><span>BEFORE</span><span>AFTER</span></button></div>'}).join('')}
  left.addEventListener('click',function(){requestAnimationFrame(run)});run()})(null); /* 20.11 owner: the before/after box made way for a description of the chosen service — examples are one tap away under it */
 /* Finishing (owner 20.11 «вместо примеров в квадратике — описание каждой кнопки, под квадратиком кнопки на примеры и на услуги») */
 (function(left){if(!left||left.querySelector('.ssw-desc'))return;var acts=left.querySelector('.acts');
  var D={'not sure what it needs':['Free track review','Free','An engineer listens and tells you what to fix first — written and video notes. Then you know exactly what to order.',['Free for your first track','Answer in 2–3 days']],
   'arrangement not finished':['Rework in your project','from €120','We open your project in your own DAW and finish it with you: arrangement, sound design, mix. The project file comes back.',['Calls with the engineer','5–14 days']],
   'ready to mix':['Mixing — or the Mix & Master bundle','from €60','Your stems mixed by our engineers: balance, EQ, compression, space. Stereo master included. Bundle (from €109): + stem master, platform masters, stems, instrumental, 30-min call.',['3–10 revisions','2–9 days']],
   'mixed, needs a master':['Mastering','from €15','A loud, clean master for Spotify, Beatport and clubs — stereo, stem or multitrack.',['2 revisions','24–48 h']],
   'want to work live':['Live video call','€30 / 60 min','An engineer joins you live — screen and sound — and works on your track together with you.',['Any DAW','Book any day']]};
  var box=document.createElement('div');box.className='ssw-match ssw-desc';if(acts)left.insertBefore(box,acts);else left.appendChild(box);
  if(acts){var ex=document.createElement('a');ex.className='cta g';ex.href='#examples';ex.textContent='Hear examples';var g=acts.querySelector('a.cta.g');if(g)acts.replaceChild(ex,g);else acts.appendChild(ex);
   ex.addEventListener('click',function(e){var t=document.getElementById('examples');if(t){e.preventDefault();t.scrollIntoView({behavior:'smooth',block:'start'})}})}
  var last='';function run(){var c=left.querySelector('.chips .on'),k=c?c.textContent.trim().toLowerCase():'',d=D[k];if(!d||k===last)return;last=k;
   box.innerHTML='<b>'+esc(d[0])+'</b><span class="pp">'+esc(d[1])+'</span><p>'+esc(d[2])+'</p><ul>'+d[3].map(function(x){return '<li>'+esc(x)+'</li>'}).join('')+'</ul>'+(d[1]==='Free'?'':'<small class="pay">Pay 50 % to start — the rest only when you love the result.</small>')}
  left.addEventListener('click',function(){requestAnimationFrame(run)});run()})(L[2]);
 /* Releasing (owner 19.11: «кнопки должны нажиматься, квадратик справа показывает, что клиент выбрал») */
 (function(left){if(!left||left.querySelector('.ssw-match'))return;var f=left.querySelector('.f .chips');if(!f)return;var acts=left.querySelector('.acts');
  var IC={'mix':'M4 6h10M18 6h2M4 12h4M12 12h8M4 18h12M20 18h0','master':'M12 3v18M5 8l7-5 7 5','stem':'M3 12h3l3-8 4 16 3-8h5','instr':'M9 18V5l11-2v13','call':'M15 10l5-3v10l-5-3zM3 7h12v10H3z','meta':'M4 4h10l6 6v10H4zM8 13h8M8 17h5'};
  function ic(t){var k=/meta|isrc/i.test(t)?'meta':/master/i.test(t)?'master':/mix/i.test(t)?'mix':/stem/i.test(t)?'stem':/instr/i.test(t)?'instr':'call';return '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="'+IC[k]+'"/></svg>'}
  var box=document.createElement('div');box.className='ssw-match ssw-rel';box.setAttribute('aria-live','polite');if(acts)left.insertBefore(box,acts);else left.appendChild(box);
  function run(){var all=[].slice.call(f.querySelectorAll('span')),sel=all.filter(function(x){return x.classList.contains('on')});
   box.innerHTML='<b>Your release pack</b><small>'+sel.length+' of '+all.length+' selected</small><span class="rl">'+all.map(function(x){var o=x.classList.contains('on');return '<span class="ri'+(o?' on':'')+'">'+ic(x.textContent)+'<em>'+esc(x.textContent)+'</em><i aria-hidden="true">'+(o?'✓':'+')+'</i></span>'}).join('')+'</span>';
   var a=left.querySelector('.acts a.cta');if(a){var u=a.href.split('#')[0].replace(/([?&])inc=[^&]*/,'').replace(/[?&]$/,'');a.href=u+(u.indexOf('?')>-1?'&':'?')+'inc='+encodeURIComponent(sel.map(function(x){return x.textContent.trim()}).join(','))}}
  box.addEventListener('click',function(e){var r=e.target.closest&&e.target.closest('.ri');if(!r)return;var t=r.querySelector('em').textContent;all();function all(){[].forEach.call(f.querySelectorAll('span'),function(x){if(x.textContent===t)x.classList.toggle('on')})}run()});
  left.addEventListener('click',function(){requestAnimationFrame(run)});run()})(null); /* 20.11 owner «как у WMDM»: «Release a track» is now a one-choice service card (like Finishing), no pack box */
 /* DJ set (owner 19.11: DJs who play their OWN music): tracks in the set + what they need -> "Your set pack" on the right */
 (function(left){if(!left||left.querySelector('.ssw-match'))return;var fs=left.querySelectorAll('.f .chips');var cnt=fs[0],need=fs[1];if(!need)return;var acts=left.querySelector('.acts');
  var IC={loud:'M3 12h2M7 8v8M11 5v14M15 8v8M19 11v2',club:'M4 12h4l2-5 4 10 2-5h4',key:'M9 18V5l11-2v13M9 18a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM20 16a3 3 0 1 1-6 0 3 3 0 0 1 6 0z',live:'M4 5h16v14H4zM4 10h16M9 10v9M14 10v9',mix:'M4 6h10M18 6h2M4 12h4M12 12h8M4 18h12',tools:'M5 19L19 5M14 5h5v5'};
  function ic(t){var k=/loud/i.test(t)?'loud':/club|extend/i.test(t)?'club':/key|bpm/i.test(t)?'key':/live|stem/i.test(t)?'live':/mix/i.test(t)?'mix':'tools';return '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="'+IC[k]+'"/></svg>'}
  var box=document.createElement('div');box.className='ssw-match ssw-rel ssw-dj';box.setAttribute('aria-live','polite');if(acts)left.insertBefore(box,acts);else left.appendChild(box);
  function run(){var all=[].slice.call(need.querySelectorAll('span')),sel=all.filter(function(x){return x.classList.contains('on')}),n=((cnt&&cnt.querySelector('.on'))||{}).textContent||'';
   box.innerHTML='<b>Your set pack</b><small>'+(n?esc(n)+' tracks · ':'')+sel.length+' of '+all.length+' selected · price in 1 minute</small><span class="rl">'+all.map(function(x){var o=x.classList.contains('on');return '<span class="ri'+(o?' on':'')+'">'+ic(x.textContent)+'<em>'+esc(x.textContent)+'</em><i aria-hidden="true">'+(o?'✓':'+')+'</i></span>'}).join('')+'</span>';
   var a=left.querySelector('.acts a.cta');if(a){var u=a.href.split('#')[0].split('?')[0];a.href=u+'?set='+encodeURIComponent(n)+'&inc='+encodeURIComponent(sel.map(function(x){return x.textContent.trim()}).join(','))}}
  box.addEventListener('click',function(e){var r=e.target.closest&&e.target.closest('.ri');if(!r)return;var t=r.querySelector('em').textContent;[].forEach.call(need.querySelectorAll('span'),function(x){if(x.textContent===t)x.classList.toggle('on')});run()});
  left.addEventListener('click',function(){requestAnimationFrame(run)});run()})(L[4]);
 /* Video & ads: our work for picture — opens the video */
 /* Video & ads (owner 19.11: «на основе вопроса — зачем это клиенту»): where it goes + what's wrong -> "Your fix", the loudness
    we deliver at, and two of our videos */
 (function(left){if(!left||left.querySelector('.ssw-match'))return;var where=left.querySelector('.vd-where'),pain=left.querySelector('.vd-pain');if(!where||!pain)return;var acts=left.querySelector('.acts');
  var FIX={'Noisy or muffled voice':'Clean, clear voice','Echo / room sound':'Echo removed','Music copyright claims':'Royalty-free music, no claims','Too quiet or too loud':'Even, platform-ready loudness','Sounds cheap / flat':'Mix with depth and punch','No sound design':'Sound design & effects'};
  var LU={'YouTube / social':'YouTube / social · −14 LUFS','Ad / TV':'TV broadcast · −23 LUFS (EBU R128)','Film / festival':'Film / festival delivery spec','Podcast':'Podcast standard · −16 LUFS'};
  var V=AB.filter(function(e){return e.yt});
  var box=document.createElement('div');box.className='ssw-match ssw-rel ssw-vd';box.setAttribute('aria-live','polite');if(acts)left.insertBefore(box,acts);else left.appendChild(box);
  function run(){var w=((where.querySelector('.on'))||{}).textContent||'YouTube / social',sel=[].filter.call(pain.querySelectorAll('span'),function(x){return x.classList.contains('on')}).map(function(x){return x.textContent.trim()});
   var vids=V.slice().sort(function(a,b){var r=function(e){var t=(e.title||'')+' '+(e.service||'');return (/cleanup|restor/i.test(t)&&sel.some(function(x){return /voice|echo/i.test(x)})?2:0)+(/design/i.test(t)&&sel.some(function(x){return /design|cheap/i.test(x)})?2:0)+(/tv|ads/i.test(t)&&/TV/.test(w)?1:0)+(/film|showreel/i.test(t)&&/Film/.test(w)?1:0)};return r(b)-r(a)}).slice(0,2);
   box.innerHTML='<b>Your fix</b><small>'+esc(LU[w]||w)+'</small><span class="rl">'+[].map.call(pain.querySelectorAll('span'),function(x){var t=x.textContent.trim(),o=x.classList.contains('on');return '<span class="ri'+(o?' on':'')+'" data-p="'+esc(t)+'" role="button" tabindex="0"><em>'+esc(o?(FIX[t]||t):t)+'</em><i aria-hidden="true">'+(o?'✓':'+')+'</i></span>'}).join('')+'</span>'+
    (vids.length?'<span class="vd-ex">'+vids.map(function(e,k){var id=String(e.yt).split('/').pop().split('?')[0];return '<span class="mx" data-k="'+k+'" role="button" tabindex="0" title="'+esc(e.title)+'" aria-label="Watch '+esc(e.title)+'"><img src="https://i.ytimg.com/vi/'+id+'/mqdefault.jpg" alt="" width="120" height="68" loading="lazy"><i aria-hidden="true"></i></span>'}).join('')+'</span>':'');
   box._v=vids;
   var a=left.querySelector('.acts a.cta');if(a){var u=a.href.split('#')[0].replace(/([?&])(for|fix)=[^&]*/g,'').replace(/[?&]$/,'');a.href=u+(u.indexOf('?')>-1?'&':'?')+'for='+encodeURIComponent(w)+'&fix='+encodeURIComponent(sel.join(','))}}
  box.addEventListener('click',function(e){var r=e.target.closest&&e.target.closest('.ri[data-p]');if(r){var t=r.getAttribute('data-p');[].forEach.call(pain.querySelectorAll('span'),function(x){if(x.textContent.trim()===t)x.classList.toggle('on')});run();return}var m=e.target.closest&&e.target.closest('.mx');if(!m)return;var x=box._v&&box._v[+m.getAttribute('data-k')];if(!x)return;var id=String(x.yt).split('/').pop().split('?')[0];if(window.ssYtOpen)ssYtOpen(id,x.title);else window.open('https://www.youtube.com/watch?v='+id,'_blank','noopener')});
  left.addEventListener('click',function(){requestAnimationFrame(run)});run()})(L[5]);
 /* Get help: one answer open at a time */
 (function(left){if(!left)return;left.addEventListener('toggle',function(e){var d=e.target;if(!d.open||d.tagName!=='DETAILS')return;[].forEach.call(left.querySelectorAll('details[open]'),function(x){if(x!==d)x.open=false})},true)})(L[0]);
 /* More: "Preparing a live set" now has its own tab (DJ set) — go there instead of the price list */
 (function(left){if(!left)return;[].forEach.call(left.querySelectorAll('a'),function(a){if(!/live set/i.test(a.textContent))return;a.addEventListener('click',function(e){var b=[].filter.call(ssx.querySelectorAll('.tasks button'),function(x){return /DJ set/i.test(x.textContent)})[0];if(!b)return;e.preventDefault();b.click();try{ssx.scrollIntoView({behavior:'smooth',block:'start'})}catch(x){}})})})(L[6]);
 /* "Before / after" in the Video card -> the Examples section, its video tab (owner 19.11) */
 (function(left){var a=left&&[].filter.call(left.querySelectorAll('.acts a'),function(x){return /before/i.test(x.textContent)})[0];if(!a)return;
  a.addEventListener('click',function(e){var ex=document.getElementById('examples');if(!ex)return;e.preventDefault();ex.scrollIntoView({behavior:'smooth',block:'start'});
   setTimeout(function(){var t=[].filter.call(ex.querySelectorAll('button,[role=tab]'),function(x){return /video/i.test(x.textContent)})[0];if(t)t.click()},450)})})(L[5]);
}
/* (owner 19.11 «наши преимущества красиво — только самые основные») the Client dashboard section becomes "why us":
   4 advantages with icons above the revision player (the player illustrates the first one) */
function why(){var sec=document.getElementById('dashboard');if(!sec||sec.querySelector('.ss-why'))return !!sec;var h=sec.querySelector('h2');if(!h)return false;
 h.textContent='Why producers work with us';
 var A=[['M4 17l4-6 4 3 4-7 4 5','Notes on the exact second','Comment right on the waveform — nothing gets lost in chats.'],
  ['M4 12a8 8 0 0 1 14-5.3M20 12a8 8 0 0 1-14 5.3M18 3v4h-4M6 21v-4h4','Revisions included','3–10 rounds of changes until it sounds right.'],
  ['M12 3v18M7 7h7a3 3 0 0 1 0 6H9a3 3 0 0 0 0 6h8','Pay 50% to start','The rest only when you’re happy with the result.'],
  ['M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7z M9 12l2 2 4-4','Royalty-free, yours to release','Use our templates and music in your releases — no royalties.']];
 var ic=function(d,s){return '<span class="i"><svg viewBox="0 0 24 24" width="'+s+'" height="'+s+'" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="'+d+'"/></svg></span>'};
 var w=document.createElement('div');w.className='ss-why';w.innerHTML=A.map(function(a){return '<div class="w">'+ic(a[0],26)+'<b>'+a[1]+'</b><small>'+a[2]+'</small></div>'}).join('');
 var p=h.parentNode.querySelector('p');var anchor=p&&p.parentNode===h.parentNode?p:h;anchor.insertAdjacentElement('afterend',w);
 /* owner 19.11 «под этим блоком — наши преимущества, плитки: 1 продюсеры, 2 криэйторы, 3 learn & connect» (tiles from /why-solid-sound/) */
 if(WHY.length){var w2=document.createElement('div');w2.className='ss-why2';w2.innerHTML='<h2 class="t">Our advantages</h2>'+WHY.map(function(g){return '<h3 class="g">'+g[0]+'</h3><div class="gr">'+g[1].map(function(a){return '<a class="w" href="'+a[3]+'">'+ic(a[0],22)+'<b>'+a[1]+(a[5]?' <i>'+a[5]+'</i>':'')+'</b><small>'+a[2]+'</small><em>'+a[4]+' →</em></a>'}).join('')+'</div>'}).join('')+'<p class="all"><a href="'+WHYU+'">Everything you get with Solid Sound →</a></p>';(h.parentNode.parentNode||h.parentNode).appendChild(w2)}
 return true}
function init(){var s=document.querySelector('#ss-app .ssx');if(!s)return false;build(s);return true}
(function(){if(why())return;var m2=new MutationObserver(function(){requestAnimationFrame(function(){if(why())m2.disconnect()})});m2.observe(document.getElementById('ss-app')||document.body,{childList:true,subtree:true});setTimeout(function(){m2.disconnect()},15000)})();
if(!init()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(init())mo.disconnect()})});mo.observe(document.getElementById('ss-app')||document.body,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},15000)}
})();</script>
	<?php
}, 30 );

/* keep the previous home one click away (owner 16.11: «предыдущую версию где-то сохрани») — WooCommerce → Solid Sound AI */
add_action( 'admin_init', function () {
	if ( isset( $_POST['ss_wow_toggle'] ) && current_user_can( 'manage_options' ) && check_admin_referer( 'ss_wow_toggle' ) ) {
		update_option( 'ss_wow_off', ! empty( $_POST['ss_wow_off'] ) ? 1 : 0, false );
	}
} );
add_action( 'ss_ai_admin_page_after', function () {
	echo '<hr><h2>Home page</h2><form method="post">' . wp_nonce_field( 'ss_wow_toggle', '_wpnonce', true, false ) . '<input type="hidden" name="ss_wow_toggle" value="1"><label><input type="checkbox" name="ss_wow_off" value="1"' . checked( (bool) get_option( 'ss_wow_off' ), true, false ) . '> Use the previous home (no wall of covers)</label> <button class="button">Save</button><p class="description">Preview any time: <a href="' . esc_url( home_url( '/en/?ss_wow=0' ) ) . '" target="_blank">previous</a> · <a href="' . esc_url( home_url( '/en/?ss_wow=1' ) ) . '" target="_blank">new</a></p></form>';
} );
