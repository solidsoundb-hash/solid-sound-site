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
body.ss-wow .ssx .track{align-items:stretch}
body.ss-wow .ssx .card .left:not(:has(>.ssw-match)){display:flex!important;flex-direction:column!important;height:100%}
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
</style>
<script>(function(){
var W=<?php echo wp_json_encode( $items ); ?>,H1=<?php echo wp_json_encode( sprintf( esc_html( $h1 ), '<span>Solid Sound</span>' ) ); ?>;
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
 match(ssx);sides(ssx)}
/* "N templates match" in the first card; updates only on the visitor's own clicks/typing (no observers) */
function match(ssx){var card=ssx.querySelector('.card .left');if(!card)return;var acts=card.querySelector('.acts');var box=document.createElement('div');box.className='ssw-match';box.setAttribute('role','button');box.tabIndex=0;if(acts)card.insertBefore(box,acts);else card.appendChild(box);var last='';
 function pick(){var fs=card.querySelectorAll('.f');var g=(fs[0]&&fs[0].querySelector('.chips .on')||{}).textContent||'';var bi=card.querySelector('input[type=number]');var b=+(bi&&(bi.value||bi.getAttribute('data-v')))||0;var kc=null;[].forEach.call(card.querySelectorAll('.f.row>div'),function(d){var l=d.querySelector('b');if(!kc&&l&&/^\s*(key|тональн|tonart|tonali|clave|tom\b)/i.test(l.textContent))kc=d.querySelector('.chips')});if(!kc)kc=card.querySelectorAll('.f.row .chips')[0]; /* by its label, not by position (owner 19.11 moved Key) */var k=(kc&&kc.querySelector('.on')||{}).textContent||'';return {g:g.trim().toLowerCase(),b:b,k:k.trim()}}
 function run(){var s=pick(),ok=W.filter(function(x){return (!s.g||x.g.split('|').indexOf(s.g)>-1)&&(!s.b||!x.b||Math.abs(x.b-s.b)<=6)&&(!s.k||s.k==='Any'||!x.k||x.k.toLowerCase().indexOf(s.k.toLowerCase())===0)});
  var close=!ok.length;var list=close?W.filter(function(x){return !s.g||x.g.split('|').indexOf(s.g)>-1}):ok;if(!list.length)list=W;var top=list.slice(0,3);
  var h='<span class="cv">'+top.map(function(x,i){return '<span class="mx" data-k="'+i+'" role="button" tabindex="0" title="'+esc(x.t)+'" aria-label="Play '+esc(x.t)+'"><img src="'+esc(x.i)+'" alt="" width="38" height="38"><i aria-hidden="true"></i></span>'}).join('')+'</span><span><b>'+(close?TR.cl:ok.length+(ok.length===1?TR.one:TR.many))+'</b><small>'+esc([s.g&&s.g.replace(/\b\w/g,function(c){return c.toUpperCase()}),s.b&&s.b+' BPM',s.k].filter(Boolean).join(' · '))+TR.tap+'</small></span>';
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
  left.addEventListener('click',function(){requestAnimationFrame(run)});run()})(L[1]);
 /* Releasing (owner 19.11: «кнопки должны нажиматься, квадратик справа показывает, что клиент выбрал») */
 (function(left){if(!left||left.querySelector('.ssw-match'))return;var f=left.querySelector('.f .chips');if(!f)return;var acts=left.querySelector('.acts');
  var IC={'mix':'M4 6h10M18 6h2M4 12h4M12 12h8M4 18h12M20 18h0','master':'M12 3v18M5 8l7-5 7 5','stem':'M3 12h3l3-8 4 16 3-8h5','instr':'M9 18V5l11-2v13','call':'M15 10l5-3v10l-5-3zM3 7h12v10H3z'};
  function ic(t){var k=/master/i.test(t)?'master':/mix/i.test(t)?'mix':/stem/i.test(t)?'stem':/instr/i.test(t)?'instr':'call';return '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="'+IC[k]+'"/></svg>'}
  var box=document.createElement('div');box.className='ssw-match ssw-rel';box.setAttribute('aria-live','polite');if(acts)left.insertBefore(box,acts);else left.appendChild(box);
  function run(){var all=[].slice.call(f.querySelectorAll('span')),sel=all.filter(function(x){return x.classList.contains('on')});
   box.innerHTML='<b>Your release pack</b><small>'+sel.length+' of '+all.length+' selected</small><span class="rl">'+all.map(function(x){var o=x.classList.contains('on');return '<span class="ri'+(o?' on':'')+'">'+ic(x.textContent)+'<em>'+esc(x.textContent)+'</em><i aria-hidden="true">'+(o?'✓':'+')+'</i></span>'}).join('')+'</span>';
   var a=left.querySelector('.acts a.cta');if(a){var u=a.href.split('#')[0].replace(/([?&])inc=[^&]*/,'').replace(/[?&]$/,'');a.href=u+(u.indexOf('?')>-1?'&':'?')+'inc='+encodeURIComponent(sel.map(function(x){return x.textContent.trim()}).join(','))}}
  box.addEventListener('click',function(e){var r=e.target.closest&&e.target.closest('.ri');if(!r)return;var t=r.querySelector('em').textContent;all();function all(){[].forEach.call(f.querySelectorAll('span'),function(x){if(x.textContent===t)x.classList.toggle('on')})}run()});
  left.addEventListener('click',function(){requestAnimationFrame(run)});run()})(L[2]);
 /* DJ set: music by genre / BPM / key, like the templates card */
 side(L[3],function(){var M=W.filter(function(x){return x.s&&/music/.test(x.f||'')}).concat(W.filter(function(x){return x.s&&!/music|presets/.test(x.f||'')}));if(!M.length)return null; /* music first, then templates (full tracks) */var g=on(L[3],0).trim().toLowerCase(),bi=L[3].querySelector('input[type=number]'),b=+(bi&&(bi.value||bi.getAttribute('data-v')))||0,kc=null;
  [].forEach.call(L[3].querySelectorAll('.f.row>div'),function(d){var l=d.querySelector('b');if(!kc&&l&&/^\s*key/i.test(l.textContent))kc=d.querySelector('.chips')});var k=((kc&&kc.querySelector('.on'))||{}).textContent||'';k=k.trim();
  var ok=M.filter(function(x){return (!g||(x.g||'').indexOf(g)>-1)&&(!b||!x.b||Math.abs(x.b-b)<=6)&&(!k||k==='Any'||!x.k||x.k.toLowerCase().indexOf(k.toLowerCase())===0)});
  var close=!ok.length,l=(close?M.filter(function(x){return !g||(x.g||'').indexOf(g)>-1}):ok);if(!l.length)l=M;
  return {b:close?'Closest to your choice':(ok.length+(ok.length===1?' track matches':' tracks match')),s:[g&&g.replace(/\b\w/g,function(c){return c.toUpperCase()}),b&&b+' BPM',k&&k!=='Any'&&k].filter(Boolean).join(' · ')+' — tap to listen',items:l.slice(0,3).map(function(x){return {t:x.t,i:x.i,go:function(){if(!play(x))location.href=x.u}}})}});
 /* Video & ads: our work for picture — opens the video */
 side(L[4],function(){var v=AB.filter(function(e){return e.yt});if(!v.length)return null;
  /* (owner 19.11 «визуализируй, что выбирают кнопки») the card shows the visitor's choice and the closest of our videos first */
  var pick=[].concat.apply([],[].map.call(L[4].querySelectorAll('.f'),function(f){return [].map.call(f.querySelectorAll('.chips .on'),function(x){return x.textContent.trim()})}));
  var KW={'YouTube':/youtube|video/i,'Ad spot':/ads?\b|tv/i,'Film / trailer':/film|showreel/i,'Podcast':/cleanup|restoration|dialogue/i,'Clean dialogue':/cleanup|restoration/i,'Sound design':/sound design|showreel/i,'Music':/showreel|music/i,'Final mix':/mix|showreel|tv/i};
  var sc=function(e){var t=(e.title||'')+' '+(e.service||'')+' '+(e.genre||'');return pick.reduce(function(n,k){return n+(KW[k]&&KW[k].test(t)?1:0)},0)};
  var l=v.slice().sort(function(a,b){return sc(b)-sc(a)}).slice(0,3);
  return {verb:'Watch',b:pick.length?pick.join(' · '):'Our work for video',s:'Closest of our work — tap to watch',items:l.map(function(e){var id=String(e.yt).split('/').pop().split('?')[0];return {t:e.title,i:'https://i.ytimg.com/vi/'+id+'/hqdefault.jpg',go:function(){if(window.ssYtOpen)ssYtOpen(id,e.title);else window.open('https://www.youtube.com/watch?v='+id,'_blank','noopener')}}})}});
 /* "Before / after" in the Video card -> the Examples section, its video tab (owner 19.11) */
 (function(left){var a=left&&[].filter.call(left.querySelectorAll('.acts a'),function(x){return /before/i.test(x.textContent)})[0];if(!a)return;
  a.addEventListener('click',function(e){var ex=document.getElementById('examples');if(!ex)return;e.preventDefault();ex.scrollIntoView({behavior:'smooth',block:'start'});
   setTimeout(function(){var t=[].filter.call(ex.querySelectorAll('button,[role=tab]'),function(x){return /video/i.test(x.textContent)})[0];if(t)t.click()},450)})})(L[4]);
}
function init(){var s=document.querySelector('#ss-app .ssx');if(!s)return false;build(s);return true}
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
