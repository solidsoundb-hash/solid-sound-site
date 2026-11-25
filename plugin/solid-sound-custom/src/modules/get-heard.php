<?php
/**
 * (19.11.2026 owner) Two plugin pages:
 *  1. /get-heard/ (+ /en/…): "Get your set heard" — a DJ or producer sends one set/track link; the owner listens and
 *     passes the best ones to club and festival managers he knows. Free, no promised bookings. Next to the form:
 *     free feedback, mixing & mastering, templates; links to the Solid Wiki promotion guides.
 *     Submissions: private post type "ss_set" (WP admin → Set submissions), e-mail to the owner, Telegram, and a short
 *     "we got it" e-mail to the artist.
 *  2. /why-solid-sound/ (+ /en/…): every advantage of Solid Sound in one list, each with its link.
 * Live for everyone since 19.11.2026 (owner: «все публикуй»); option ss_gh_live = 0 hides them again (admins and
 * ?ss_preview=1 still see them, with noindex).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_gh_route() {
	$p = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' );
	if ( preg_match( '#^([a-z]{2}/)?get-heard$#', $p ) ) return 'heard';
	if ( preg_match( '#^([a-z]{2}/)?why-solid-sound$#', $p ) ) return 'why';
	return '';
}
function ss_gh_live() { return '0' !== (string) get_option( 'ss_gh_live', '1' ); } /* owner 19.11: «все публикуй» */
function ss_gh_visible() { return ss_gh_live() || current_user_can( 'manage_options' ) || isset( $_GET['ss_preview'] ); }

/* ---------- submissions: private post type, visible in WP admin ---------- */
add_action( 'init', function () {
	register_post_type( 'ss_set', array(
		'label' => 'Set submissions', 'public' => false, 'show_ui' => true, 'show_in_menu' => true, 'menu_icon' => 'dashicons-format-audio',
		'supports' => array( 'title', 'editor' ), 'capabilities' => array( 'create_posts' => 'do_not_allow' ), 'map_meta_cap' => true,
		'capability_type' => 'page',
	) );
} );
add_filter( 'manage_ss_set_posts_columns', function ( $c ) {
	return array( 'cb' => $c['cb'], 'title' => 'Artist', 'ss_link' => 'Set', 'ss_genre' => 'Genre', 'ss_city' => 'City', 'ss_goals' => 'Looking for', 'ss_email' => 'Email', 'date' => 'Date' );
} );
add_action( 'manage_ss_set_posts_custom_column', function ( $col, $id ) {
	$v = (string) get_post_meta( $id, '_' . $col, true );
	if ( 'ss_link' === $col ) { echo '<a href="' . esc_url( $v ) . '" target="_blank" rel="noopener">Listen ↗</a>'; return; }
	if ( 'ss_email' === $col ) { echo '<a href="mailto:' . esc_attr( $v ) . '">' . esc_html( $v ) . '</a>'; return; }
	echo esc_html( $v );
}, 10, 2 );

add_action( 'admin_post_nopriv_ss_get_heard', 'ss_gh_submit' );
add_action( 'admin_post_ss_get_heard', 'ss_gh_submit' );
function ss_gh_submit() {
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/en/get-heard/' );
	$back = remove_query_arg( array( 'sent', 'err' ), $back );
	$go = function ( $k, $v ) use ( $back ) { wp_safe_redirect( add_query_arg( $k, $v, $back ) . '#ssgh-form' ); exit; };
	if ( ! empty( $_POST['website'] ) ) $go( 'sent', '1' ); /* honeypot */
	if ( (int) ( $_POST['el'] ?? 99 ) < 3 ) $go( 'sent', '1' ); /* faster than a human */
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$rk = 'ss_gh_rl_' . md5( $ip );
	if ( (int) get_transient( $rk ) >= 4 ) $go( 'err', 'rate' );
	$f = function ( $n, $max = 200 ) { return isset( $_POST[ $n ] ) ? mb_substr( trim( sanitize_text_field( wp_unslash( $_POST[ $n ] ) ) ), 0, $max ) : ''; };
	$email  = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$link   = esc_url_raw( trim( wp_unslash( $_POST['link'] ?? '' ) ) );
	$artist = $f( 'artist', 80 );
	if ( '' === $artist || ! is_email( $email ) ) $go( 'err', 'fields' );
	if ( ! $link || ! preg_match( '#^https?://#i', $link ) ) $go( 'err', 'link' );
	if ( empty( $_POST['consent'] ) ) $go( 'err', 'consent' );
	set_transient( $rk, (int) get_transient( $rk ) + 1, HOUR_IN_SECONDS );
	$allowed = array( 'Club gigs', 'Festivals', 'Label release', 'Feedback first' );
	$goals = array_values( array_intersect( $allowed, array_map( 'sanitize_text_field', (array) wp_unslash( $_POST['goals'] ?? array() ) ) ) );
	$d = array(
		'ss_link' => $link, 'ss_email' => $email, 'ss_genre' => $f( 'genre', 60 ), 'ss_city' => $f( 'city', 80 ),
		'ss_goals' => implode( ', ', $goals ), 'ss_social' => $f( 'social', 120 ),
	);
	$note = isset( $_POST['note'] ) ? mb_substr( sanitize_textarea_field( wp_unslash( $_POST['note'] ) ), 0, 1500 ) : '';
	$id = wp_insert_post( array( 'post_type' => 'ss_set', 'post_status' => 'private', 'post_title' => $artist, 'post_content' => $note ) );
	if ( is_wp_error( $id ) || ! $id ) $go( 'err', 'save' );
	foreach ( $d as $k => $v ) update_post_meta( $id, '_' . $k, $v );
	$txt = "New set for «Get heard»\n\nArtist: {$artist}\nSet: {$link}\nGenre: {$d['ss_genre']}\nCity: {$d['ss_city']}\nLooking for: {$d['ss_goals']}\nSocials: {$d['ss_social']}\nEmail: {$email}\n\n{$note}\n\nAll submissions: " . admin_url( 'edit.php?post_type=ss_set' );
	wp_mail( get_option( 'admin_email' ), 'New set: ' . $artist . ( $d['ss_genre'] ? ' (' . $d['ss_genre'] . ')' : '' ), $txt, array( 'Reply-To: ' . $email ) );
	if ( function_exists( 'ss_tg_send' ) ) ss_tg_send( '🎧 ' . $txt );
	$hi = "Hi {$artist},\n\nThanks for sending your set to Solid Sound. We got it, and a sound engineer will listen to it personally.\n\n"
		. "If it fits a venue we work with, we'll ask you before we share it. If it isn't ready yet, we'll tell you what to work on. Usually within two weeks.\n\n"
		. "While you wait, these free guides may help:\n" . home_url( '/en/wiki/' ) . "\n\nKeep making noise,\nSolid Sound";
	wp_mail( $email, 'We got your set — Solid Sound', $hi );
	$go( 'sent', '1' );
}

/* ---------- pages ---------- */
add_action( 'template_redirect', function () {
	$r = ss_gh_route();
	if ( ! $r ) return;
	if ( ! ss_gh_visible() ) return; /* not live yet: normal 404 for visitors */
	global $wp_query;
	$wp_query->is_404 = false;
	status_header( 200 );
	$title = 'heard' === $r ? 'Get Your DJ Set Heard by Club & Festival Managers | Solid Sound' : 'Why Solid Sound — Everything You Get | Solid Sound';
	$desc  = 'heard' === $r ? 'Send your best DJ set or track. A sound engineer listens to every one and passes the standouts to club and festival managers. Free.'
		: 'Free starter pack, free feedback, a live client dashboard, 3% bonus back, royalty-free licenses and free listing for creators. Everything in one list.';
	add_filter( 'pre_get_document_title', function () use ( $title ) { return $title; } );
	add_action( 'wp_head', function () use ( $desc, $r ) {
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		echo '<link rel="canonical" href="' . esc_url( home_url( '/en/' . ( 'heard' === $r ? 'get-heard' : 'why-solid-sound' ) . '/' ) ) . '">' . "\n";
		if ( ! ss_gh_live() ) echo '<meta name="robots" content="noindex,nofollow">' . "\n";
		echo '<style>' . ss_gh_css() . '</style>';
	}, 1 );
	get_header();
	echo '<main id="page-content" class="l-main"><section class="l-section"><div class="l-section-h">' . ( 'heard' === $r ? ss_gh_html() : ss_why_html() ) . '</div></section></main>';
	get_footer();
	exit;
}, 1 );

function ss_gh_icon( $d ) {
	return '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' . $d . '"/></svg>';
}

function ss_gh_css() {
	return '.ssgh{--p:#131B2E;--p2:#0F1626;--ln:rgba(255,255,255,.08);--or:#F59E0B;--mu:#94A3B8;max-width:1120px;margin:0 auto;padding:28px 16px 64px;color:#E5E7EB;font-size:16px;line-height:1.55}'
		. '.ssgh *{box-sizing:border-box}.ssgh a{color:var(--or)}'
		. '.ssgh .kick{display:inline-block;padding:4px 12px;border:1px solid rgba(245,158,11,.5);border-radius:999px;color:var(--or);font-size:12.5px;font-weight:700;letter-spacing:.12em;text-transform:uppercase}'
		. '.ssgh h1{font-size:clamp(32px,5vw,52px);line-height:1.1;margin:14px 0 12px;color:#fff;font-weight:800}.ssgh h1 span{color:var(--or)}'
		. '.ssgh .lead{font-size:19px;color:#CBD5E1;max-width:720px;margin:0 0 24px}'
		. '.ssgh .hero{display:grid;grid-template-columns:minmax(0,1.1fr) minmax(0,1fr);gap:32px;align-items:start}'
		. '.ssgh .steps{display:grid;gap:12px;margin:0 0 20px}.ssgh .step{display:flex;gap:14px;align-items:flex-start;background:var(--p2);border:1px solid var(--ln);border-radius:14px;padding:14px 16px}'
		. '.ssgh .step .n{flex:none;width:36px;height:36px;border-radius:10px;background:rgba(245,158,11,.14);border:1px solid rgba(245,158,11,.4);color:var(--or);font-weight:800;display:flex;align-items:center;justify-content:center}'
		. '.ssgh .step b{color:#fff;display:block}.ssgh .step span{color:var(--mu);font-size:15px}'
		. '.ssgh .note{font-size:14px;color:var(--mu);border-left:3px solid var(--or);padding:4px 0 4px 12px}'
		. '.ssgh form{background:var(--p);border:1px solid var(--ln);border-radius:18px;box-shadow:0 10px 30px rgba(0,0,0,.5);padding:22px;scroll-margin-top:100px}'
		. '.ssgh form h2{margin:0 0 4px;color:#fff;font-size:22px}.ssgh form p.s{margin:0 0 6px;color:var(--mu);font-size:14.5px}'
		. '.ssgh label.l{display:block;font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:var(--mu);margin:12px 0 6px;font-weight:700}'
		. '.ssgh input[type=text],.ssgh input[type=email],.ssgh input[type=url],.ssgh textarea{width:100%;height:44px;border-radius:10px;border:1px solid var(--ln);background:var(--p2);color:#fff;font:inherit;font-size:15px;padding:0 12px;margin:0}'
		. '.ssgh textarea{height:84px;padding:10px 12px;resize:vertical}.ssgh input:focus,.ssgh textarea:focus{outline:2px solid var(--or);outline-offset:1px}'
		. '.ssgh .row{display:grid;grid-template-columns:1fr 1fr;gap:10px}'
		. '.ssgh .chips{display:flex;flex-wrap:wrap;gap:8px}.ssgh .chips label{position:relative;cursor:pointer}.ssgh .chips input{position:absolute;opacity:0;width:1px;height:1px}'
		. '.ssgh .chips span{display:inline-flex;align-items:center;height:38px;padding:0 14px;border-radius:999px;border:1px solid var(--ln);background:var(--p2);color:#CBD5E1;font-weight:600;font-size:14px}'
		. '.ssgh .chips input:checked+span{background:rgba(245,158,11,.14);border-color:rgba(245,158,11,.55);color:var(--or)}.ssgh .chips input:focus-visible+span{outline:2px solid var(--or)}'
		. '.ssgh .ck{display:flex;gap:10px;align-items:flex-start;margin:14px 0 0;font-size:14px;color:#CBD5E1;cursor:pointer}.ssgh .ck input{flex:none;width:20px;height:20px;margin:1px 0 0;accent-color:#F59E0B}'
		. '.ssgh .btn{display:flex;align-items:center;justify-content:center;width:100%;height:50px;margin-top:18px;border:0;border-radius:12px;background:var(--or);color:#111;font:inherit;font-weight:800;font-size:16px;cursor:pointer}'
		. '.ssgh .msg{padding:14px 16px;border-radius:12px;margin:12px 0 0;font-size:15px}.ssgh .msg.ok{background:rgba(52,211,153,.12);border:1px solid rgba(52,211,153,.45);color:#d1fae5}.ssgh .msg.er{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.45);color:#fecaca}'
		. '.ssgh .hp{position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden}'
		. '.ssgh h3.sec{font-size:clamp(24px,3vw,32px);color:#fff;margin:56px 0 6px;line-height:1.2}.ssgh p.secs{color:var(--mu);margin:0 0 18px}'
		. '.ssgh .grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}'
		. '.ssgh .card{display:flex;flex-direction:column;background:var(--p2);border:1px solid var(--ln);border-radius:14px;padding:18px;text-decoration:none;color:#E5E7EB}.ssgh .card:hover{border-color:var(--or)}'
		. '.ssgh .ic{flex:none;width:40px;height:40px;border-radius:10px;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.3);color:var(--or);display:flex;align-items:center;justify-content:center;margin-bottom:10px}'
		. '.ssgh .card b{color:#fff;display:block;font-size:17px;margin-bottom:4px}.ssgh .card span{color:var(--mu);font-size:14.5px}.ssgh .card em{display:block;margin-top:auto;padding-top:10px;font-style:normal;color:var(--or);font-weight:700;font-size:14.5px}'
		. '.ssgh details{border:1px solid var(--ln);border-radius:12px;background:var(--p2);margin:10px 0}.ssgh summary{cursor:pointer;padding:14px 18px;font-weight:700;color:#fff}.ssgh details p{padding:0 18px 6px;margin:0 0 10px;color:#CBD5E1}'
		. '.ssgh .tag{display:inline-block;margin-left:6px;padding:1px 8px;border-radius:999px;background:rgba(52,211,153,.15);color:#34D399;font-size:11.5px;font-weight:800;letter-spacing:.06em;vertical-align:middle}'
		. '.ssgh .cta2{display:flex;flex-wrap:wrap;gap:10px;margin-top:22px}.ssgh .cta2 a{display:inline-flex;align-items:center;height:48px;padding:0 20px;border-radius:12px;background:var(--or);color:#111;font-weight:800;text-decoration:none}.ssgh .cta2 a.o{background:transparent;border:1px solid var(--or);color:var(--or)}'
		. '@media(max-width:860px){.ssgh .hero{grid-template-columns:1fr}.ssgh .grid{grid-template-columns:1fr}}@media(max-width:480px){.ssgh .row{grid-template-columns:1fr}}';
}

function ss_gh_html() {
	$sent = isset( $_GET['sent'] );
	$err  = isset( $_GET['err'] ) ? sanitize_key( $_GET['err'] ) : '';
	$errs = array( 'fields' => 'Please add your artist name and a valid email.', 'link' => 'Please paste a full link to your set (starting with https://).',
		'consent' => 'Please tick the box so we can share your set with venue managers.', 'rate' => 'Too many submissions from here — please try again in an hour.',
		'save' => 'Something went wrong — please try again or write to info@solid-sound.net.' );
	$genres = array( 'Melodic techno', 'Techno', 'Progressive house', 'Deep house', 'Afro house', 'Tech house', 'House', 'Organic house', 'Indie dance', 'Trance', 'Drum & bass', 'Other' );
	$o = '<div class="ssgh"><span class="kick">Solid Sound · Artist support</span><div class="hero"><div>'
		. '<h1>Get your set <span>heard</span> by the right people</h1>'
		. '<p class="lead">Send us your best DJ set or original track. We listen to every submission, and the ones that stand out go to club and festival managers we work with.</p>'
		. '<div class="steps">'
		. '<div class="step"><span class="n">1</span><div><b>Send one link</b><span>SoundCloud, Mixcloud, YouTube or a private download. Your best 30–60 minutes, not everything you’ve ever made.</span></div></div>'
		. '<div class="step"><span class="n">2</span><div><b>We listen, for real</b><span>A sound engineer listens to every set. No algorithms, no pay-to-play.</span></div></div>'
		. '<div class="step"><span class="n">3</span><div><b>The best ones get passed on</b><span>When a set fits a venue’s sound and season, we recommend you to the people who book it. If it isn’t ready yet, we tell you what to work on.</span></div></div>'
		. '</div><p class="note">It’s free, and we don’t promise bookings. Venues make the final call. What we can promise is an honest listen and a real introduction when your sound fits.</p></div>';
	$o .= '<form id="ssgh-form" method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">'
		. '<input type="hidden" name="action" value="ss_get_heard"><input type="hidden" name="el" value="0">'
		. '<div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>'
		. '<h2>Submit your set</h2><p class="s">Takes 2 minutes. You’ll get a reply by email.</p>';
	if ( $sent ) $o .= '<div class="msg ok" role="status"><b>Thanks, we got your set!</b> Check your inbox for a confirmation. We usually reply within two weeks.</div>';
	if ( $err && isset( $errs[ $err ] ) ) $o .= '<div class="msg er" role="alert">' . esc_html( $errs[ $err ] ) . '</div>';
	$o .= '<div class="row"><div><label class="l" for="gh-a">Artist name</label><input id="gh-a" type="text" name="artist" required maxlength="80" placeholder="DJ name or project" autocomplete="nickname"></div>'
		. '<div><label class="l" for="gh-e">Email</label><input id="gh-e" type="email" name="email" required placeholder="you@example.com" autocomplete="email"></div></div>'
		. '<label class="l" for="gh-l">Link to your set or track</label><input id="gh-l" type="url" name="link" required placeholder="https://soundcloud.com/…">'
		. '<div class="row"><div><label class="l" for="gh-g">Main genre</label><input id="gh-g" type="text" name="genre" list="gh-gl" maxlength="60" placeholder="Melodic techno"><datalist id="gh-gl">';
	foreach ( $genres as $g ) $o .= '<option value="' . esc_attr( $g ) . '">';
	$o .= '</datalist></div><div><label class="l" for="gh-c">City / country</label><input id="gh-c" type="text" name="city" maxlength="80" placeholder="Paris, France" autocomplete="address-level2"></div></div>'
		. '<span class="l" style="display:block;font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:#94A3B8;margin:12px 0 6px;font-weight:700">What are you looking for?</span><div class="chips">';
	foreach ( array( 'Club gigs', 'Festivals', 'Label release', 'Feedback first' ) as $i => $g ) $o .= '<label><input type="checkbox" name="goals[]" value="' . esc_attr( $g ) . '"' . ( 0 === $i ? ' checked' : '' ) . '><span>' . esc_html( $g ) . '</span></label>';
	$o .= '</div><label class="l" for="gh-s">Instagram or other socials (optional)</label><input id="gh-s" type="text" name="social" maxlength="120" placeholder="@yourname">'
		. '<label class="l" for="gh-n">Anything we should know? (optional)</label><textarea id="gh-n" name="note" maxlength="1500" placeholder="Past gigs, releases, where you’d love to play…"></textarea>'
		. '<label class="ck"><input type="checkbox" name="consent" value="1" required><span>I made this set / track (or have the rights to share it), and I agree that Solid Sound may share it with venue managers.</span></label>'
		. '<button class="btn" type="submit">Send my set →</button></form></div>';
	$o .= '<h3 class="sec">Make your set ready for a big room</h3><p class="secs">Managers hear hundreds of sets. A clean mix and a loud, balanced master make you stand out in the first 30 seconds.</p><div class="grid">'
		. '<a class="card" href="' . esc_url( home_url( '/free-demo-feedback/' ) ) . '"><span class="ic">' . ss_gh_icon( 'M12 3l2.8 5.7 6.2.9-4.5 4.4 1 6.2L12 17.3 6.5 20.2l1-6.2L3 9.6l6.2-.9z' ) . '</span><b>Free feedback first</b><span>Not sure it’s ready? A sound engineer tells you what to fix, for free.</span><em>Get free feedback →</em></a>'
		. '<a class="card" href="' . esc_url( home_url( '/studio-calculator/?s=mixing' ) ) . '"><span class="ic">' . ss_gh_icon( 'M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6' ) . '</span><b>Mixing &amp; mastering</b><span>Your own tracks, club-ready: punchy low end, clear highs, proper loudness.</span><em>See prices →</em></a>'
		. '<a class="card" href="' . esc_url( home_url( '/en/all-products/' ) ) . '"><span class="ic">' . ss_gh_icon( 'M4 4h16v16H4zM4 9h16M9 9v11' ) . '</span><b>Ableton templates</b><span>Build original tracks faster, from projects made by working producers.</span><em>Browse templates →</em></a></div>';
	$o .= '<h3 class="sec">Want to push yourself too?</h3><p class="secs">Free guides from Solid Wiki: how to start, what venues pay and how to get booked.</p><div class="grid">'
		. '<a class="card" href="' . esc_url( home_url( '/en/wiki/how-to-promote-yourself-as-a-dj/' ) ) . '"><span class="ic">' . ss_gh_icon( 'M5 21V4M5 4h11l-2 4 2 4H5' ) . '</span><b>How to start promoting yourself as a DJ</b><span>The first 90 days: your set, your press kit, your first gigs.</span><em>Read · 6 min →</em></a>'
		. '<a class="card" href="' . esc_url( home_url( '/en/wiki/how-much-do-djs-get-paid/' ) ) . '"><span class="ic">' . ss_gh_icon( 'M17 7a6 6 0 1 0 0 10M4 10h9M4 14h9' ) . '</span><b>How much do DJs get paid?</b><span>Rough fees from bar gigs to festival stages, and what moves you up.</span><em>Read · 5 min →</em></a>'
		. '<a class="card" href="' . esc_url( home_url( '/en/wiki/how-to-get-booked-as-a-dj/' ) ) . '"><span class="ic">' . ss_gh_icon( 'M3 5h18v14H3zM3 5l9 8 9-8' ) . '</span><b>How to get booked at clubs and festivals</b><span>Who books, when they book, and the message that actually gets answered.</span><em>Read · 6 min →</em></a></div>';
	$o .= '<h3 class="sec">Questions</h3>'
		. '<details open><summary>Does it cost anything?</summary><p>No. Submitting a set is free. Our paid services are optional and have no effect on who we recommend.</p></details>'
		. '<details><summary>Will I definitely get a gig?</summary><p>No one can promise that. We pass on the sets we believe in, and the venue decides.</p></details>'
		. '<details><summary>How long until I hear back?</summary><p>Usually within two weeks. If your set fits a specific venue, we ask you before sharing it.</p></details>'
		. '<details><summary>Who will hear my set?</summary><p>First our sound engineer. Then, only if it fits, the managers of the venues we work with. We never publish your set or share your email.</p></details>'
		. '</div><script>(function(){var f=document.getElementById("ssgh-form");if(!f)return;var t=Date.now();f.addEventListener("submit",function(){f.el.value=Math.round((Date.now()-t)/1000)})})();</script>';
	return $o;
}

/* every advantage, with its link (owner 19.11.2026: «на самом видном месте наши преимущества и перечислить со ссылками») */
function ss_why_items() {
	$u = function ( $p ) { return home_url( $p ); };
	return array(
		'For producers & DJs' => array(
			array( 'M3 8h18v4H3zM5 12v9h14v-9M12 8v13M12 8C10 4 7 4 7 6s3 2 5 2c2 0 5 0 5-2s-3-2-5 2', 'Free Starter Pack', 'A full Ableton template, Serum presets and a free mix audit coupon. No payment.', $u( '/en/free/' ), 'Get it free', 'FREE' ),
			array( 'M12 3l2.8 5.7 6.2.9-4.5 4.4 1 6.2L12 17.3 6.5 20.2l1-6.2L3 9.6l6.2-.9z', 'Free feedback from a sound engineer', 'Send 30 seconds of your track and hear what to fix first.', $u( '/free-demo-feedback/' ), 'Get feedback', 'FREE' ),
			array( 'M3 3h8v8H3zM13 3h8v5h-8zM13 10h8v11h-8zM3 13h8v8H3z', 'Client dashboard', 'Follow every stage of your mix live, leave notes on the waveform at the exact second, download every version.', $u( '/en/client-dashboard/' ), 'See how it works', '' ),
			array( 'M4 18v-2a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v2M12 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM2 21h20', 'Get heard by venue managers', 'Send your best set. We pass the standouts to club and festival managers we work with.', $u( '/en/get-heard/' ), 'Submit your set', 'NEW' ),
			array( 'M20 12v9H4v-9M2 7h20v5H2zM12 22V7M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z', '3% back on every order', 'Bonuses never expire and pay up to 50% of your next template or studio service.', $u( '/en/my-account/' ), 'Your bonus balance', '' ),
			array( 'M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z', 'Royalty-free, with a certificate', 'Release your tracks anywhere. Every purchase comes with a personal license certificate.', 'https://solid-sound.net/license/', 'Read the license', '' ),
			array( 'M4 4h16v16H4zM8 8h2M14 8h2M8 12h2M14 12h2M8 16h8', 'Clear studio prices', 'Mixing, mastering, remix and ghost producing: see the price before you order.', $u( '/studio-calculator/' ), 'Open the calculator', '' ),
			array( 'M12 3v12M7 10l5 5 5-5M4 21h16', 'Instant download', 'Pay and download right away, from any device.', $u( '/en/all-products/' ), 'Browse templates', '' ),
		),
		'Learn & connect' => array(
			array( 'M21 11.5a8.4 8.4 0 0 1-9 8.4 8.6 8.6 0 0 1-3.9-.9L3 21l2-5a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 8.4-8.4 8.4 8.4 0 0 1 8.5 7.7z', 'Community forum in your language', 'Write in your own language, everyone reads it in theirs. Rate my track, Ableton help, collabs.', $u( '/en/forum/' ), 'Join the forum', '' ),
			array( 'M4 4h6a2 2 0 0 1 2 2v14a2 2 0 0 0-2-2H4zM20 4h-6a2 2 0 0 0-2 2v14a2 2 0 0 1 2-2h6z', 'Solid Wiki', 'Free guides by a working engineer: recording, gear, and now promotion and DJ fees.', $u( '/en/wiki/' ), 'Read the guides', 'FREE' ),
			array( 'M12 8V4H8M4 8h16v12H4zM2 14h2M20 14h2M9 13v2M15 13v2', 'Help any time', 'Our AI assistant answers 24/7, and a real person replies every evening.', $u( '/contacts' ), 'Ask a question', '' ),
		),
		'For creators who sell' => array(
			array( 'M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6', 'Sell your templates & presets', 'Free to list, non-exclusive, you set the price. Founding Creator badge for the first 20 producers.', $u( '/en/sell/' ), 'Start selling', '' ),
			array( 'M3 11l18-8-8 18-2-8z', 'Featured, not buried', 'New releases go to our homepage and our Instagram, TikTok and YouTube.', $u( '/en/sell/' ), 'How we promote', '' ),
			array( 'M3 3v18h18M7 15l4-4 3 3 5-6', 'Creator space with AI help', 'Live sales stats, and AI that writes titles, descriptions and covers from your ZIP.', $u( '/en/sell/' ), 'See the creator space', '' ),
		),
	);
}

function ss_why_html() {
	$o = '<div class="ssgh"><span class="kick">Why Solid Sound</span><h1>Everything you get with <span>Solid Sound</span></h1>'
		. '<p class="lead">More than a store: free tools, honest feedback, a dashboard for every order and real help getting your music out. Here is all of it in one list.</p>';
	foreach ( ss_why_items() as $group => $items ) {
		$o .= '<h3 class="sec">' . esc_html( $group ) . '</h3><div class="grid">';
		foreach ( $items as $it ) {
			$tag = $it[5] ? '<i class="tag">' . esc_html( $it[5] ) . '</i>' : '';
			$o .= '<a class="card" href="' . esc_url( $it[3] ) . '"><span class="ic">' . ss_gh_icon( $it[0] ) . '</span><b>' . esc_html( $it[1] ) . $tag . '</b><span>' . esc_html( $it[2] ) . '</span><em>' . esc_html( $it[4] ) . ' →</em></a>';
		}
		$o .= '</div>';
	}
	$o .= '<div class="cta2"><a href="' . esc_url( home_url( '/en/free/' ) ) . '">Start with the free pack →</a><a class="o" href="' . esc_url( home_url( '/en/sell/' ) ) . '">Sell your templates</a></div></div>';
	return $o;
}
