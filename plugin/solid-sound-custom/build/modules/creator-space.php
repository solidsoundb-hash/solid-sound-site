<?php
/**
 * (15.11.2026 owner) Creator space: the producer dashboard in the client-account look, "very advanced":
 * top tabs instead of the icon sidebar, stat tiles with 7-day sparklines and change vs the week before,
 * 30-day chart (earnings / sales / plays), Get started with a progress bar, payout progress to the $25 minimum,
 * best sellers (sales + plays), profile strength ring with what to add, products with status.
 * The OLD dashboard is kept: WooCommerce -> Solid Sound AI page has "Use the old creator dashboard", or open
 * /store-manager/?ss_cs=0 once. Data: WCFM commission table (sales/earnings per day), product counters
 * _ss_plays/_ss_likes/_ss_views, and plays per day (_ss_plays_d, written from today on).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_cs_on() {
	if ( isset( $_GET['ss_cs'] ) ) return '1' === $_GET['ss_cs'];
	return false; /* (15.11.2026) owner: "кабинет криетора упал" — old dashboard by default until the new one is verified; preview with ?ss_cs=1 */
}
function ss_cs_ok() { return ss_cs_on() && function_exists( 'is_wcfm_page' ) && is_wcfm_page() && function_exists( 'ss_cp_can' ) && ss_cp_can(); }
function ss_cs_is_root() {
	global $wp;
	foreach ( array_keys( (array) $wp->query_vars ) as $k ) if ( 0 === strpos( $k, 'wcfm-' ) && 'wcfm-dashboard' !== $k ) return false;
	return true;
}

/* plays per day (kept 62 days) */
add_action( 'ss_play_counted', function ( $pid ) {
	$d = get_post_meta( $pid, '_ss_plays_d', true ); if ( ! is_array( $d ) ) $d = array();
	$k = current_time( 'Y-m-d' ); $d[ $k ] = ( isset( $d[ $k ] ) ? (int) $d[ $k ] : 0 ) + 1;
	if ( count( $d ) > 62 ) { ksort( $d ); $d = array_slice( $d, -62, null, true ); }
	update_post_meta( $pid, '_ss_plays_d', $d );
} );

add_filter( 'body_class', function ( $c ) {
	if ( ss_cs_ok() ) { $c[] = 'ss-cs'; if ( ss_cs_is_root() ) $c[] = 'ss-cs-root'; }
	return $c;
} );

function ss_cs_ic( $k, $s = 18 ) {
	static $I = array(
		'dash' => 'M3 3h8v8H3zM13 3h8v5h-8zM13 10h8v11h-8zM3 13h8v8H3z', 'music' => 'M9 18V5l12-2v13M9 18a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM21 16a3 3 0 1 1-6 0 3 3 0 0 1 6 0z',
		'dollar' => 'M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6', 'card' => 'M3 6h18v12H3zM3 10h18', 'star' => 'M12 3l2.8 5.7 6.2.9-4.5 4.4 1 6.2L12 17.3 6.5 20.2l1-6.2L3 9.6l6.2-.9z',
		'user' => 'M16 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 21c1.5-4 4.5-6 8-6s6.5 2 8 6', 'help' => 'M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20zM9.1 9a3 3 0 0 1 5.8 1c0 2-3 2-3 4M12 17h.01',
		'out' => 'M14 3h6v18h-6M10 17l-5-5 5-5M5 12h12', 'up' => 'M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12', 'check' => 'M5 12.5l4.5 4.5L19 7.5',
		'chart' => 'M3 3v18h18M7 15l4-4 3 3 6-6', 'link' => 'M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.7 1.7M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.7-1.7',
		'chat' => 'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z', 'bag' => 'M6 7h12l1 14H5zM9 7a3 3 0 0 1 6 0',
	);
	return '<svg width="' . (int) $s . '" height="' . (int) $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' . $I[ $k ] . '"/></svg>';
}

function ss_cs_data( $id ) {
	global $wpdb;
	$d = array( 'rows' => array(), 'live' => 0, 'review' => 0 );
	$ids = get_posts( array( 'post_type' => 'product', 'author' => $id, 'post_status' => array( 'publish', 'pending', 'draft', 'private' ), 'posts_per_page' => 200, 'fields' => 'ids', 'orderby' => 'date', 'order' => 'DESC' ) );
	$today = current_time( 'Y-m-d' );
	$days = array(); for ( $i = 29; $i >= 0; $i-- ) $days[ gmdate( 'Y-m-d', strtotime( $today . " -$i days" ) ) ] = array( 's' => 0, 'e' => 0.0, 'p' => 0 );
	foreach ( $ids as $pid ) {
		$p = wc_get_product( $pid ); if ( ! $p ) continue;
		$st = get_post_status( $pid );
		if ( 'publish' === $st ) $d['live']++; elseif ( 'pending' === $st ) $d['review']++;
		list( $q, $e ) = function_exists( 'ss_cp_sold' ) ? ss_cp_sold( $pid ) : array( (int) $p->get_total_sales(), 0 );
		$pd = get_post_meta( $pid, '_ss_plays_d', true );
		if ( is_array( $pd ) ) foreach ( $pd as $k => $n ) if ( isset( $days[ $k ] ) ) $days[ $k ]['p'] += (int) $n;
		$d['rows'][] = array( 'id' => $pid, 'name' => $p->get_name(), 'st' => $st, 'price' => (float) $p->get_price(), 'q' => (int) $q, 'e' => (float) $e,
			'plays' => (int) get_post_meta( $pid, '_ss_plays', true ), 'likes' => (int) get_post_meta( $pid, '_ss_likes', true ),
			'img' => get_the_post_thumbnail_url( $pid, 'thumbnail' ), 'url' => get_permalink( $pid ), 'edit' => function_exists( 'ss_cp_url' ) ? ss_cp_url( 'ss-products' ) : '' );
	}
	/* sales + earnings per day from the WCFM commission table */
	$c = function_exists( 'ss_cp_cols' ) ? ss_cp_cols() : array();
	if ( $c && in_array( 'created', $c, true ) ) {
		$t = $wpdb->prefix . 'wcfm_marketplace_orders';
		$com = in_array( 'total_commission', $c, true ) ? 'total_commission' : 'commission_amount';
		$w = $wpdb->prepare( 'vendor_id = %d AND created >= %s', $id, gmdate( 'Y-m-d', strtotime( $today . ' -29 days' ) ) );
		if ( in_array( 'is_trashed', $c, true ) ) $w .= ' AND is_trashed = 0';
		if ( in_array( 'is_refunded', $c, true ) ) $w .= ' AND is_refunded = 0';
		foreach ( (array) $wpdb->get_results( "SELECT DATE(created) d, COUNT(*) n, SUM({$com}) e FROM {$t} WHERE {$w} GROUP BY DATE(created)" ) as $r ) {
			if ( isset( $days[ $r->d ] ) ) { $days[ $r->d ]['s'] = (int) $r->n; $days[ $r->d ]['e'] = round( (float) $r->e, 2 ); }
		}
	}
	$d['days'] = $days;
	$v = array_values( $days );
	$sum = function ( $a, $b, $k ) use ( $v ) { $x = 0; for ( $i = $a; $i < $b; $i++ ) $x += $v[ $i ][ $k ]; return $x; };
	$d['s7'] = $sum( 23, 30, 's' ); $d['s7p'] = $sum( 16, 23, 's' ); $d['e7'] = $sum( 23, 30, 'e' ); $d['e7p'] = $sum( 16, 23, 'e' );
	$d['spark_s'] = array_slice( wp_list_pluck( $v, 's' ), -7 ); $d['spark_e'] = array_slice( wp_list_pluck( $v, 'e' ), -7 );
	$d['all'] = function_exists( 'ss_cp_stats' ) ? ss_cp_stats( $id ) : array( 'total' => 0, 'available' => 0, 'pending' => 0, 'paid' => 0, 'count' => 0 );
	$d['pay_set'] = function_exists( 'ss_cp_payout_set' ) && ss_cp_payout_set( $id );
	/* profile strength */
	$pr = function_exists( 'ss_cp_prof' ) ? ss_cp_prof( $id ) : array();
	$soc = isset( $pr['social'] ) && is_array( $pr['social'] ) ? count( array_filter( $pr['social'] ) ) : 0;
	$chk = array(
		array( ! empty( $pr['gravatar'] ), 20, 'a profile photo' ), array( ! empty( $pr['banner'] ), 15, 'a cover image' ),
		array( ! empty( $pr['shop_description'] ) && strlen( wp_strip_all_tags( $pr['shop_description'] ) ) > 40, 20, 'a short bio' ),
		array( $soc >= 2, 15, ( 2 - min( 2, $soc ) ) . ' link' . ( 2 - min( 2, $soc ) > 1 ? 's' : '' ) . ' to your music' ),
		array( ! empty( $pr['address']['country'] ), 10, 'your country' ), array( $d['pay_set'], 20, 'a payout method' ),
	);
	$d['strength'] = 0; $d['missing'] = array();
	foreach ( $chk as $x ) { if ( $x[0] ) $d['strength'] += $x[1]; else $d['missing'][] = $x[2]; }
	$d['steps'] = array(
		array( ! empty( $pr['gravatar'] ) && ( ! empty( $pr['shop_description'] ) || ! empty( $pr['banner'] ) ), 'Complete your profile', 'Photo, cover and a short bio build trust with buyers.', 'Edit profile', ss_cp_url( 'ss-settings' ) ),
		array( (bool) $d['rows'], 'Add your first product', 'Upload files and an MP3 preview. We review it within 24 hours.', 'Add product', function_exists( 'ss_ep_url' ) ? ss_ep_url() : ss_cp_url( 'ss-add-product' ) ),
		array( $d['pay_set'], 'Set up payouts', 'Add your PayPal email or bank so we can pay you monthly (from $25).', 'Set up payouts', ss_cp_url( 'ss-payouts' ) ),
		array( $d['all']['count'] > 0, 'Make your first sale', 'Share your profile page: in your bio, under videos, in Discord.', '', '' ),
	);
	$d['store'] = function_exists( 'wcfmmp_get_store_url' ) ? wcfmmp_get_store_url( $id ) : '';
	return $d;
}

function ss_cs_delta( $now, $prev ) {
	if ( $prev <= 0 ) return $now > 0 ? '<span class="dl up">new</span>' : '';
	$p = round( ( $now - $prev ) / $prev * 100 );
	return '<span class="dl ' . ( $p >= 0 ? 'up' : 'dn' ) . '">' . ( $p >= 0 ? '+' : '' ) . $p . '%</span>';
}

function ss_cs_nav() {
	$cur = '';
	global $wp; foreach ( array_keys( (array) $wp->query_vars ) as $k ) if ( 0 === strpos( $k, 'wcfm-ss-' ) ) $cur = substr( $k, 5 );
	$tabs = array( array( 'Overview', 'dash', function_exists( 'get_wcfm_url' ) ? get_wcfm_url() : home_url( '/en/store-manager/' ), '' ),
		array( 'Products', 'music', ss_cp_url( 'ss-products' ), 'ss-products' ), array( 'Add product', 'up', function_exists( 'ss_ep_url' ) ? ss_ep_url() : ss_cp_url( 'ss-add-product' ), 'ss-add-product' ),
		array( 'Earnings', 'dollar', ss_cp_url( 'ss-earnings' ), 'ss-earnings' ), array( 'Payouts', 'card', ss_cp_url( 'ss-payouts' ), 'ss-payouts' ),
		array( 'Reviews', 'star', ss_cp_url( 'ss-reviews' ), 'ss-reviews' ), array( 'Profile', 'user', ss_cp_url( 'ss-settings' ), 'ss-settings' ), array( 'Help', 'help', ss_cp_url( 'ss-help' ), 'ss-help' ) );
	$h = '<nav class="ssa-nav ss-cs-nav" aria-label="Creator space">';
	foreach ( $tabs as $t ) { $on = ( $t[3] === $cur ) && ( '' !== $cur || ss_cs_is_root() ); $h .= '<a href="' . esc_url( $t[2] ) . '"' . ( $on ? ' class="on" aria-current="page"' : '' ) . '>' . ss_cs_ic( $t[1] ) . '<span>' . esc_html( $t[0] ) . '</span></a>'; }
	return $h . '<a class="out" href="' . esc_url( wp_logout_url( home_url( '/' ) ) ) . '">' . ss_cs_ic( 'out' ) . '<span>Log out</span></a></nav>';
}

function ss_cs_render() {
	$u = wp_get_current_user(); $id = $u->ID; $d = ss_cs_data( $id );
	$name = $u->first_name ? $u->first_name : $u->display_name;
	$head = function ( $t, $i, $x = '' ) { return '<h2><span class="t">' . ss_cs_ic( $i ) . '</span>' . $t . $x . '</h2>'; };
	$money = function ( $v ) { return '$' . number_format( (float) $v, 2 ); };
	$add = function_exists( 'ss_ep_url' ) ? ss_ep_url() : ss_cp_url( 'ss-add-product' );
	$h = '<div id="ssd" class="ss-cs-d"><div class="top"><div><h1>Creator space <span class="ssa-role">Creator account</span></h1><p class="sub">' . ( $name ? 'Hi ' . esc_html( $name ) . '! ' : '' ) . 'Your products, sales and payouts.</p></div><div class="top-b">'
		. ( $d['store'] ? '<a class="btn g" href="' . esc_url( $d['store'] ) . '">' . ss_cs_ic( 'user' ) . 'View my profile</a>' : '' )
		. '<a class="btn" href="' . esc_url( $add ) . '">' . ss_cs_ic( 'up' ) . 'Add product</a></div></div>';
	/* tiles */
	$h .= '<div class="stats">'
		. '<a class="st" href="' . esc_url( ss_cp_url( 'ss-products' ) ) . '"><b>' . (int) $d['live'] . '</b><span>Products live</span></a>'
		. '<a class="st" href="' . esc_url( ss_cp_url( 'ss-products' ) ) . '"><b>' . (int) $d['review'] . '</b><span>In review</span></a>'
		. '<a class="st" href="' . esc_url( ss_cp_url( 'ss-earnings' ) ) . '"><b>' . (int) $d['s7'] . ss_cs_delta( $d['s7'], $d['s7p'] ) . '</b><span>Sales · last 7 days</span><svg class="sp" data-v="' . esc_attr( implode( ',', $d['spark_s'] ) ) . '"></svg></a>'
		. '<a class="st" href="' . esc_url( ss_cp_url( 'ss-earnings' ) ) . '"><b>' . $money( $d['e7'] ) . ss_cs_delta( $d['e7'], $d['e7p'] ) . '</b><span>Your earnings · last 7 days</span><svg class="sp" data-v="' . esc_attr( implode( ',', $d['spark_e'] ) ) . '"></svg></a></div>';
	$h .= '<div class="grid"><div>';
	/* chart */
	$any = array_sum( wp_list_pluck( $d['days'], 's' ) ) + array_sum( wp_list_pluck( $d['days'], 'p' ) );
	$series = array( 'd' => array_keys( $d['days'] ), 's' => array_values( wp_list_pluck( $d['days'], 's' ) ), 'e' => array_values( wp_list_pluck( $d['days'], 'e' ) ), 'p' => array_values( wp_list_pluck( $d['days'], 'p' ) ) );
	$h .= '<section class="p">' . $head( 'Last 30 days', 'chart' );
	if ( $any ) $h .= '<div class="ch-tabs"><button type="button" class="on" data-k="e">Earnings</button><button type="button" data-k="s">Sales</button><button type="button" data-k="p">Plays</button></div><div class="chart" id="ss-cs-ch" data-series="' . esc_attr( wp_json_encode( $series ) ) . '"><svg viewBox="0 0 700 220" preserveAspectRatio="none" role="img" aria-label="Last 30 days chart"></svg><div class="tip"></div></div>';
	else $h .= '<div class="empty">Your chart of earnings, sales and plays appears here after the first plays of your previews and the first sale.</div>';
	$h .= '</section>';
	/* get started */
	$done = count( array_filter( wp_list_pluck( $d['steps'], 0 ) ) );
	if ( $done < 4 ) {
		$h .= '<section class="p"><div class="gs-h"><h2 style="margin:0"><span class="t">' . ss_cs_ic( 'check' ) . '</span>Get started</h2><span>' . $done . ' / 4</span></div><div class="pg" style="margin-top:12px"><i style="width:' . ( $done * 25 ) . '%"></i></div>';
		foreach ( $d['steps'] as $i => $s ) {
			$h .= '<div class="row' . ( $s[0] ? ' done' : '' ) . '"><span class="t n">' . ( $s[0] ? '✓' : ( $i + 1 ) ) . '</span><div class="nm"><b>' . esc_html( $s[1] ) . '</b><small>' . esc_html( $s[2] ) . '</small></div>'
				. ( $s[0] ? '<span class="badge">Done</span>' : ( $s[3] ? '<a class="btn g" href="' . esc_url( $s[4] ) . '">' . esc_html( $s[3] ) . '</a>' : '' ) ) . '</div>';
		}
		$h .= '</section>';
	}
	/* products */
	$h .= '<section class="p">' . $head( 'My products', 'music' );
	if ( $d['rows'] ) {
		foreach ( array_slice( $d['rows'], 0, 6 ) as $r ) {
			$b = 'publish' === $r['st'] ? '<span class="badge">Live</span>' : ( 'pending' === $r['st'] ? '<span class="badge w">In review</span>' : '<span class="badge w">Draft</span>' );
			$h .= '<a class="row" href="' . esc_url( 'publish' === $r['st'] ? $r['url'] : $r['edit'] ) . '">' . ( $r['img'] ? '<img class="th" src="' . esc_url( $r['img'] ) . '" alt="" width="48" height="48" loading="lazy">' : '<span class="t th">' . ss_cs_ic( 'music', 20 ) . '</span>' )
				. '<div class="nm"><b>' . esc_html( $r['name'] ) . '</b><small>' . $money( $r['price'] ) . ' · ' . (int) $r['q'] . ' sale' . ( 1 === $r['q'] ? '' : 's' ) . ' · ' . (int) $r['plays'] . ' plays</small></div>' . $b . '</a>';
		}
		$h .= '<div class="btns"><a class="btn" href="' . esc_url( $add ) . '">' . ss_cs_ic( 'up' ) . 'Add product</a><a class="btn g" href="' . esc_url( ss_cp_url( 'ss-products' ) ) . '">All products</a></div>';
	} else {
		$h .= '<div class="empty">No products yet. Add your first template, preset pack or track — we review it within 24 hours.</div><a class="btn" href="' . esc_url( $add ) . '">' . ss_cs_ic( 'up' ) . 'Add product</a>';
	}
	$h .= '</section></div><div>';
	/* profile link */
	if ( $d['store'] ) $h .= '<section class="p">' . $head( 'Your profile page', 'link' ) . '<p class="sub" style="margin:0">Share it in your bio, under videos, in Discord — fans see only your products. Every sale from it counts.</p><div class="lnk"><input readonly value="' . esc_attr( $d['store'] ) . '" aria-label="Your profile page link"><button type="button" class="btn g ss-cs-copy">Copy</button></div></section>';
	/* payouts */
	$av = (float) $d['all']['available']; $pct = min( 100, $av / 25 * 100 );
	$h .= '<section class="p">' . $head( 'Payouts', 'card' ) . '<div class="row"><div class="nm"><b>' . $money( $av ) . ( $av >= 25 ? ' available' : ' of $25.00' ) . '</b><small>' . ( $av >= 25 ? 'Ready for the next monthly payout' : 'Next payout when you reach $25 · monthly · PayPal or bank' ) . ( $d['all']['paid'] ? ' · paid so far ' . $money( $d['all']['paid'] ) : '' ) . '</small></div><a class="btn g" href="' . esc_url( ss_cp_url( 'ss-payouts' ) ) . '">' . ( $d['pay_set'] ? 'Payouts' : 'Set up payouts' ) . '</a></div><div class="pg"><i style="width:' . round( $pct ) . '%"></i></div></section>';
	/* best sellers */
	$best = array_filter( $d['rows'], function ( $r ) { return 'publish' === $r['st']; } );
	usort( $best, function ( $a, $b ) { return array( $b['q'], $b['plays'] ) <=> array( $a['q'], $a['plays'] ); } );
	$best = array_slice( $best, 0, 3 );
	if ( $best ) {
		$mx = max( 1, max( array_map( function ( $r ) { return max( $r['q'], $r['plays'] / 50 ); }, $best ) ) );
		$h .= '<section class="p">' . $head( 'Best sellers', 'star' );
		foreach ( $best as $r ) $h .= '<div class="tp"><b>' . esc_html( $r['name'] ) . '</b><small>' . (int) $r['q'] . ' sale' . ( 1 === $r['q'] ? '' : 's' ) . ' · ' . (int) $r['plays'] . ' plays · ' . (int) $r['likes'] . ' ♥</small><div class="bar"><i style="width:' . max( 3, round( max( $r['q'], $r['plays'] / 50 ) / $mx * 100 ) ) . '%"></i></div></div>';
		$h .= '<p class="sub" style="margin:4px 0 0">Tip: a product with plays but few sales usually needs a stronger cover or a better MP3 preview.</p></section>';
	}
	/* profile strength */
	$s = (int) $d['strength']; $off = round( 97.4 * ( 1 - $s / 100 ), 1 );
	$h .= '<section class="p">' . $head( 'Profile strength', 'user' ) . '<div class="ring"><svg width="72" height="72" viewBox="0 0 36 36" aria-hidden="true"><circle cx="18" cy="18" r="15.5" fill="none" stroke="rgba(255,255,255,.08)" stroke-width="4"/><circle cx="18" cy="18" r="15.5" fill="none" stroke="#F59E0B" stroke-width="4" stroke-linecap="round" stroke-dasharray="97.4" stroke-dashoffset="' . $off . '" transform="rotate(-90 18 18)"/><text x="18" y="21.5" text-anchor="middle" font-size="9" font-weight="800" fill="#fff">' . $s . '%</text></svg><div>'
		. ( $d['missing'] ? '<b>Add ' . esc_html( implode( ', ', array_slice( $d['missing'], 0, 3 ) ) ) . '</b><p class="sub" style="margin:2px 0 8px">Complete profiles sell more.</p>' : '<b>Your profile is complete</b><p class="sub" style="margin:2px 0 8px">Nice — buyers see a real artist.</p>' )
		. '<a class="btn g" href="' . esc_url( ss_cp_url( 'ss-settings' ) ) . '">Edit profile</a></div></div></section>';
	/* quick actions + account */
	$acts = array( array( $add, 'up', 'Add a product', 'Template, presets or music' ), array( ss_cp_url( 'ss-settings' ), 'user', 'Edit my profile', 'Photo, cover, bio, links' ),
		array( ss_cp_url( 'ss-reviews' ), 'star', 'Reviews', 'What buyers say' ), array( ss_cp_url( 'ss-help' ), 'help', 'Help / FAQ', 'How selling works' ), array( '#chat', 'chat', 'Ask the team', 'We reply in minutes' ) );
	$h .= '<section class="p acts">' . $head( 'Quick actions', 'dash' );
	foreach ( $acts as $x ) $h .= '<a href="' . esc_url( $x[0] ) . '"' . ( '#chat' === $x[0] ? ' data-chat="1"' : '' ) . '>' . ss_cs_ic( $x[1], 20 ) . '<span>' . esc_html( $x[2] ) . '<small>' . esc_html( $x[3] ) . '</small></span></a>';
	$acc = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
	$h .= '</section><section class="p acts">' . $head( 'Account', 'user' ) . '<a href="' . esc_url( $acc ) . '">' . ss_cs_ic( 'bag', 20 ) . '<span>My client account<small>Purchases and downloads</small></span></a><a href="' . esc_url( wp_logout_url( home_url( '/' ) ) ) . '">' . ss_cs_ic( 'out', 20 ) . '<span>Log out</span></a></section>';
	return $h . '</div></div></div>';
}

add_action( 'wp_head', function () { if ( ss_cs_ok() && function_exists( 'ss_asset' ) ) ss_asset( 'ss-account-css' ); }, 60 );

add_action( 'wp_footer', function () {
	if ( ! ss_cs_ok() ) return;
	$root = ss_cs_is_root(); ?>
<template id="ss-cs-tpl"><div id="ss-cs-wrap"><?php echo ss_cs_nav(); if ( $root ) echo ss_cs_render(); /* escaped inside */ ?></div></template>
<style id="ss-cs-css">
body.ss-cs #wcfm_menu{display:none!important}
body.ss-cs #wcfm-content{width:100%!important;max-width:none!important;margin:0!important;float:none!important;background:transparent!important}
body.ss-cs #wcfm-main-contentainer{background:transparent!important;max-width:1220px;margin-left:auto!important;margin-right:auto!important}
body.ss-cs-root #wcfm-content>*:not(#ss-cs-wrap){display:none!important}
#ss-cs-wrap{font-family:"Exo 2",system-ui,sans-serif;padding:0 0 10px}
#ss-cs-wrap .ssa-nav{margin:6px 0 18px}
#ss-cs-wrap #ssd{padding:4px 0 40px}
#ssd.ss-cs-d .row{display:flex;align-items:center;gap:14px;text-decoration:none!important}
#ssd.ss-cs-d .row .nm{flex:1;min-width:0}#ssd.ss-cs-d .row .nm b{display:block;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}#ssd.ss-cs-d .row .nm small{color:#94A3B8}
#ssd.ss-cs-d .row .btn,#ssd.ss-cs-d .row .badge{flex:none}
#ssd.ss-cs-d .row img.th{width:48px;height:48px;border-radius:8px;object-fit:cover;flex:none}#ssd.ss-cs-d .row .t.th{width:48px;height:48px}
#ssd.ss-cs-d .row .t.n{width:40px;height:40px;border-radius:50%}
#ssd.ss-cs-d .row.done .t.n{background:#16a34a;color:#fff}#ssd.ss-cs-d .row.done b{text-decoration:line-through;opacity:.6}
#ssd.ss-cs-d .btns{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px}
.ss-cs-d .pg{height:8px;border-radius:99px;background:rgba(255,255,255,.08);overflow:hidden;margin:6px 0 14px}.ss-cs-d .pg i{display:block;height:100%;min-width:6px;border-radius:99px;background:linear-gradient(90deg,#F59E0B,#fbbf24)}
.ss-cs-d .gs-h{display:flex;justify-content:space-between;align-items:center}.ss-cs-d .gs-h span{color:#F59E0B;font-weight:800}
.ss-cs-d .st .sp{display:block;margin-top:8px;width:100%;max-width:140px;height:28px}.ss-cs-d .dl{font-size:12px;font-weight:700;margin-left:6px}.ss-cs-d .dl.up{color:#34d399}.ss-cs-d .dl.dn{color:#f87171}
.ss-cs-d .ch-tabs{display:flex;gap:6px;margin:0 0 12px;flex-wrap:wrap}.ss-cs-d .ch-tabs button{height:32px;padding:0 12px;border-radius:8px;border:1px solid rgba(255,255,255,.12);background:transparent;color:#cbd5e1;font:700 13px "Exo 2",sans-serif;cursor:pointer}.ss-cs-d .ch-tabs button.on{background:rgba(245,158,11,.14);border-color:rgba(245,158,11,.5);color:#F59E0B}
.ss-cs-d .chart{position:relative;width:100%;height:220px}.ss-cs-d .chart svg{width:100%;height:100%;display:block}.ss-cs-d .tip{position:absolute;pointer-events:none;background:#0b1220;border:1px solid rgba(255,255,255,.14);border-radius:8px;padding:6px 9px;font-size:12px;color:#fff;white-space:nowrap;transform:translate(-50%,-110%);opacity:0;transition:opacity .1s}
.ss-cs-d .tp{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:4px 12px;align-items:center;margin:0 0 12px}.ss-cs-d .tp b{color:#fff;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.ss-cs-d .tp small{color:#94a3b8;font-size:12px;text-align:right}.ss-cs-d .tp .bar{grid-column:1/-1;height:6px;border-radius:99px;background:rgba(255,255,255,.07);overflow:hidden}.ss-cs-d .tp .bar i{display:block;height:100%;background:#F59E0B;border-radius:99px}
.ss-cs-d .ring{display:flex;align-items:center;gap:14px}.ss-cs-d .ring svg{flex:none}.ss-cs-d .ring b{color:#fff}
.ss-cs-d .lnk{display:flex;flex-wrap:wrap;gap:8px;margin-top:10px}.ss-cs-d .lnk input{flex:1 1 200px;min-width:0;height:42px;border-radius:10px;border:1px solid rgba(255,255,255,.12)!important;background:#0b1220!important;color:#fff!important;padding:0 12px;font:14px "Exo 2",sans-serif}
@media(max-width:700px){#ss-cs-wrap .ssa-nav a span{display:inline}.ss-cs-d .chart{height:180px}}
</style>
<script>(function(){
function mount(){var t=document.getElementById('ss-cs-tpl'),c=document.getElementById('wcfm-content');if(!t||!c)return false;if(document.getElementById('ss-cs-wrap'))return true;c.insertBefore(t.content.cloneNode(true),c.firstChild);init();return true}
function spark(svg){var v=(svg.getAttribute('data-v')||'').split(',').map(Number),w=120,hh=28,m=Math.max.apply(0,v)||1;svg.setAttribute('viewBox','0 0 '+w+' '+hh);svg.setAttribute('preserveAspectRatio','none');if(!v.length)return;var pts=v.map(function(x,i){return (i*w/Math.max(1,v.length-1)).toFixed(1)+','+(hh-2-(x/m)*(hh-6)).toFixed(1)});svg.innerHTML='<polyline points="0,'+hh+' '+pts.join(' ')+' '+w+','+hh+'" fill="rgba(245,158,11,.15)"/><polyline points="'+pts.join(' ')+'" fill="none" stroke="#F59E0B" stroke-width="1.8" vector-effect="non-scaling-stroke"/>'}
function chart(ch){var S=JSON.parse(ch.getAttribute('data-series')),svg=ch.querySelector('svg'),tip=ch.querySelector('.tip'),cur='e',W=700,H=220,L=44,B=24,T=10,n=S.d.length;
 var fmt={e:function(x){return '$'+(+x).toFixed(2)},s:function(x){return x+' sale'+(x==1?'':'s')},p:function(x){return x+' play'+(x==1?'':'s')}};
 function X(i){return L+i*(W-L-8)/(n-1)}
 function draw(k){cur=k;var v=S[k],m=Math.max.apply(0,v),st=k==='e'?5:1,top=Math.max(4*st,Math.ceil(m/(4*st))*4*st),Y=function(x){return T+(H-T-B)*(1-x/top)},g='';
  for(var t=0;t<=4;t++){var val=top*t/4,yy=Y(val);g+='<line x1="'+L+'" x2="'+W+'" y1="'+yy+'" y2="'+yy+'" stroke="rgba(255,255,255,.07)"/><text x="'+(L-8)+'" y="'+(yy+4)+'" text-anchor="end" font-size="11" fill="#94A3B8">'+(k==='e'?'$'+(+val.toFixed(2)):val)+'</text>'}
  [0,10,20,n-1].forEach(function(i){g+='<text x="'+X(i)+'" y="'+(H-6)+'" text-anchor="'+(i===n-1?'end':(i===0?'start':'middle'))+'" font-size="11" fill="#94A3B8">'+(i===n-1?'Today':S.d[i].slice(5).replace('-','.'))+'</text>'});
  var pts=v.map(function(x,i){return X(i).toFixed(1)+','+Y(x).toFixed(1)}).join(' ');
  g+='<polygon points="'+X(0)+','+Y(0)+' '+pts+' '+X(n-1)+','+Y(0)+'" fill="rgba(245,158,11,.14)"/><polyline points="'+pts+'" fill="none" stroke="#F59E0B" stroke-width="2.2" stroke-linejoin="round"/><circle cx="'+X(n-1)+'" cy="'+Y(v[n-1])+'" r="4.5" fill="#F59E0B"/><line class="hl" y1="'+T+'" y2="'+(H-B)+'" stroke="rgba(255,255,255,.25)" opacity="0"/>';
  svg.innerHTML=g}
 var sum=function(k){return S[k].reduce(function(a,b){return a+(+b)},0)},first=sum('e')>0?'e':(sum('s')>0?'s':'p');draw(first);
 var tb=ch.parentNode.querySelector('.ch-tabs');if(tb)tb.querySelectorAll('button').forEach(function(x){x.classList.toggle('on',x.getAttribute('data-k')===first)});
 ch.addEventListener('mousemove',function(e){var b=ch.getBoundingClientRect(),px=(e.clientX-b.left)*W/b.width,i=Math.round((px-L)/((W-L-8)/(n-1)));if(i<0||i>=n){tip.style.opacity=0;return}tip.style.left=(X(i)*b.width/W)+'px';tip.style.top=(e.clientY-b.top)+'px';tip.textContent=(i===n-1?'Today':S.d[i])+': '+fmt[cur](S[cur][i]);tip.style.opacity=1;var hl=svg.querySelector('.hl');hl.setAttribute('x1',X(i));hl.setAttribute('x2',X(i));hl.setAttribute('opacity',1)});
 ch.addEventListener('mouseleave',function(){tip.style.opacity=0;var hl=svg.querySelector('.hl');if(hl)hl.setAttribute('opacity',0)});
 var tabs=ch.parentNode.querySelector('.ch-tabs');if(tabs)tabs.addEventListener('click',function(e){var b=e.target.closest('button');if(!b)return;tabs.querySelectorAll('button').forEach(function(x){x.classList.toggle('on',x===b)});draw(b.getAttribute('data-k'))})}
function init(){document.querySelectorAll('.ss-cs-d svg.sp').forEach(spark);var ch=document.getElementById('ss-cs-ch');if(ch)chart(ch);
 document.querySelectorAll('.ss-cs-copy').forEach(function(b){b.addEventListener('click',function(){var i=b.parentNode.querySelector('input');i.select();var ok=function(){b.textContent='Copied ✓'};if(navigator.clipboard)navigator.clipboard.writeText(i.value).then(ok,function(){try{document.execCommand('copy');ok()}catch(x){}});else{try{document.execCommand('copy');ok()}catch(x){}}})});
 document.querySelectorAll('.ss-cs-d [data-chat]').forEach(function(a){a.addEventListener('click',function(e){var c=document.querySelector('#ssb .ssb-fab, .ssb-fab');if(c){e.preventDefault();c.click()}})})}
if(!mount()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(mount())mo.disconnect()})});mo.observe(document.documentElement,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},10000)}
})();</script>
	<?php
}, 35 );

/* keep the old dashboard one click away (owner: "сохрани на всякий пожарный старую версию") */
add_action( 'admin_init', function () {
	if ( isset( $_POST['ss_cs_toggle'] ) && current_user_can( 'manage_woocommerce' ) && check_admin_referer( 'ss_cs_toggle' ) ) {
		update_option( 'ss_cs_off', ! empty( $_POST['ss_cs_off'] ) ? 1 : 0, false );
	}
} );
add_action( 'ss_ai_admin_page_after', function () {
	echo '<hr><h2>Creator dashboard</h2><form method="post">' . wp_nonce_field( 'ss_cs_toggle', '_wpnonce', true, false ) . '<input type="hidden" name="ss_cs_toggle" value="1"><label><input type="checkbox" name="ss_cs_off" value="1"' . checked( (bool) get_option( 'ss_cs_off' ), true, false ) . '> Use the old creator dashboard (WCFM sidebar look)</label> <button class="button">Save</button><p class="description">One-time preview without saving: add <code>?ss_cs=0</code> (old) or <code>?ss_cs=1</code> (new) to /store-manager/.</p></form>';
} );
