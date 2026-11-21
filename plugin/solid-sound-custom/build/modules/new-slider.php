<?php
/**
 * All products: «What's new» — one product per slide (owner 21.11.2026: «слайдер с одним товаром, в котором видно обложку,
 * описание, цену и всё самое главное — по сути чтобы карточка товара показывалась, по одному новому товару чередовалось,
 * слайдер ездил»). Replaces the 3-card carousel of ss_h3_render('new') on /all-products/ only (the home page is not touched).
 * Auto-advance every 6 s; stops on hover / focus / touch / while a preview plays / when the tab is hidden.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_ns_desc( $pid ) {
	$t = get_post_field( 'post_excerpt', $pid ); if ( '' === trim( wp_strip_all_tags( strip_shortcodes( $t ) ) ) ) $t = get_post_field( 'post_content', $pid );
	$t = preg_replace( '#\[[^\]]*\]#', ' ', $t ); $t = html_entity_decode( wp_strip_all_tags( $t ), ENT_QUOTES, 'UTF-8' );
	$t = trim( preg_replace( '/\s+/u', ' ', preg_replace( '/^\s*Tech specs.*?(?=\b(The|This|A|An|Solid Sound)\b)/su', '', $t ) ) );
	if ( mb_strlen( $t ) > 230 ) { $t = mb_substr( $t, 0, 230 ); $c = max( mb_strrpos( $t, '. ' ), 0 ); $t = $c > 120 ? mb_substr( $t, 0, $c + 1 ) : rtrim( mb_substr( $t, 0, mb_strrpos( $t, ' ' ) ), ',;:' ) . '…'; }
	return $t;
}
/* the main specs: attributes first, then «Label: value» pairs from the «Tech specs» text */
function ss_ns_specs( $pid, $p, $fmt ) {
	$o = array(); $at = function ( $a ) use ( $p ) { $v = trim( (string) $p->get_attribute( $a ) ); return false !== strpos( $v, ',' ) ? trim( strstr( $v, ',', true ) ) : $v; };
	$t = html_entity_decode( wp_strip_all_tags( preg_replace( '#\[[^\]]*\]#', ' ', get_post_field( 'post_excerpt', $pid ) . ' ' . get_post_field( 'post_content', $pid ) ) ), ENT_QUOTES, 'UTF-8' );
	$t = preg_replace( '/\s+/u', ' ', $t ); $tx = array();
	$L = 'Presets pack|Compatible|Required plugins|Number of presets|Size|DAW|Ableton version|Ableton Live version|Version|BPM|Tempo|Key|Length|Format|Synth|Plugins';
	if ( preg_match_all( '/\b(' . $L . '):\s*(.{1,60}?)(?=\s+(?:' . $L . '):|\s+(?:The|This|A|An|Solid Sound|Use|Load)\s|\s*$)/u', $t, $m, PREG_SET_ORDER ) ) foreach ( $m as $x ) if ( ! isset( $tx[ $x[1] ] ) ) $tx[ $x[1] ] = trim( $x[2], ' .,;' );
	$daw = $at( 'pa_daw' ); if ( ! $daw ) { $d = ss_ns_terms( $pid, 'daw', 1 ); $daw = $d ? $d[0] : ( $tx['DAW'] ?? ( $tx['Ableton version'] ?? ( $tx['Ableton Live version'] ?? '' ) ) ); }
	if ( 'presets' === $fmt ) {
		foreach ( array( 'Synth' => $tx['Required plugins'] ?? ( $tx['Synth'] ?? '' ), 'Presets' => $tx['Number of presets'] ?? '', 'Format' => $tx['Presets pack'] ?? ( $tx['Format'] ?? '' ), 'Works on' => $tx['Compatible'] ?? '', 'Size' => $tx['Size'] ?? '' ) as $k => $v ) if ( $v ) $o[ $k ] = $v;
	} else {
		if ( 'templates' === $fmt && $daw ) $o['DAW'] = $daw;
		foreach ( array( 'BPM' => $at( 'pa_bpm' ) ?: ( $tx['BPM'] ?? ( $tx['Tempo'] ?? '' ) ), 'Key' => $at( 'pa_root-key' ) ?: ( $tx['Key'] ?? '' ), 'Length' => $at( 'pa_length' ) ?: ( $tx['Length'] ?? '' ) ) as $k => $v ) if ( $v ) $o[ $k ] = $v;
		if ( 'templates' === $fmt ) { $e = ss_ns_terms( $pid, 'external-plugin', 2 ); $o['Plugins'] = $e ? implode( ', ', $e ) : 'Native only'; }
		if ( 'music' === $fmt ) { $mo = $at( 'pa_mood' ); if ( $mo ) $o['Mood'] = $mo; $o['License'] = 'Royalty-free'; }
	}
	/* (owner 22.11: «нужна полная инфа — размер, версия DAW и т. д. — как на странице продукта») the same tech specs as the
	   product page (custom.php «product page v3»): version, plugins, OS, then every visible product attribute */
	$full = array(); $t3 = function ( $k, $d ) { return function_exists( 'ss_pd3_t' ) ? ss_pd3_t( $k ) : $d; }; $tp = function ( $k, $d ) { return function_exists( 'ss_pd_t' ) ? ss_pd_t( $k ) : $d; };
	if ( in_array( $fmt, array( 'templates', 'presets' ), true ) && function_exists( 'ss_pd_terms' ) ) {
		$v = wp_list_pluck( ss_pd_terms( $pid, 'product-version' ), 'name' ); natsort( $v );
		if ( $v && 'templates' === $fmt ) $full['DAW'] = reset( $v ) . ' (' . $tp( 'orlater', 'or later' ) . ')';
		if ( function_exists( 'ss_pd_plugins' ) ) { $pl = ss_pd_plugins( $pid ); $full[ $tp( 'plugins', 'Plugins' ) ] = ( ( function_exists( 'ss_pd_native' ) && ss_pd_native( $pid ) ) || ! $pl ) ? 'No external plugins' : implode( ', ', $pl ); }
		$full[ $tp( 'os', 'Works on' ) ] = 'PC & Mac';
	}
	$lm = array( 'audio' => array( 'l_audio', 'Audio tracks' ), 'midi' => array( 'l_midi', 'MIDI tracks' ), 'send' => array( 'l_send', 'Return tracks' ), 'return' => array( 'l_send', 'Return tracks' ), 'bpm' => array( 'l_bpm', 'BPM' ), 'key' => array( 'l_key', 'Key' ), 'length' => array( 'l_len', 'Length' ), 'size' => array( 'l_size', 'Size' ) );
	foreach ( $p->get_attributes() as $a ) {
		if ( ! $a->get_visible() ) continue;
		$val = $a->is_taxonomy() ? implode( ', ', wc_get_product_terms( $pid, $a->get_name(), array( 'fields' => 'names' ) ) ) : implode( ', ', $a->get_options() );
		if ( '' === trim( $val ) ) continue;
		$label = wc_attribute_label( $a->get_name() ); $lab = ucfirst( strtolower( $label ) );
		foreach ( $lm as $kw => $lk ) if ( false !== stripos( $label, $kw ) ) { $lab = $t3( $lk[0], $lk[1] ); break; }
		$full[ $lab ] = $val;
	}
	/* what only the description says (size, presets count, synth, format …) — when not already there */
	foreach ( $o as $k => $v ) { $dup = false; foreach ( $full as $fk => $fv ) if ( 0 === strcasecmp( $fk, $k ) || ( 'DAW' === $k && isset( $full['DAW'] ) ) || ( 'Plugins' === $k && false !== stripos( $fk, 'plugin' ) ) || ( 'Works on' === $k && 'PC & Mac' === $fv ) ) $dup = true; if ( ! $dup ) $full[ $k ] = $v; }
	if ( ! empty( $tx['Size'] ) && ! isset( $full[ $t3( 'l_size', 'Size' ) ] ) && ! isset( $full['Size'] ) ) $full[ $t3( 'l_size', 'Size' ) ] = $tx['Size'];
	/* presets: «Synth: Serum v1.363» says more than «Plugins: Serum» — keep one, with the version */
	if ( isset( $full['Synth'] ) ) foreach ( array_keys( $full ) as $fk ) if ( 'Synth' !== $fk && false !== stripos( $fk, 'plugin' ) ) { $syn = $full['Synth']; unset( $full['Synth'] ); $full[ $fk ] = $syn; $full = array( $fk => $syn ) + $full; break; }
	return array_slice( $full ? $full : $o, 0, 10, true );
}
function ss_ns_terms( $pid, $tax, $n = 2 ) {
	$t = wp_get_post_terms( $pid, $tax, array( 'fields' => 'names' ) ); return is_wp_error( $t ) ? array() : array_slice( $t, 0, $n );
}
function ss_ns_render() {
	$q = new WP_Query( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 12, 'orderby' => 'date', 'order' => 'DESC', 'no_found_rows' => true ) );
	$yt = function_exists( 'ss_yt_map' ) ? ss_yt_map() : array();
	$s = array(); $fl = array( 'templates' => 'Template', 'presets' => 'Presets', 'music' => 'Music' );
	while ( $q->have_posts() && count( $s ) < 8 ) { $q->the_post(); $pid = get_the_ID(); $p = wc_get_product( $pid );
		if ( ! $p || ! $p->is_visible() || ! has_post_thumbnail( $pid ) || get_post_meta( $pid, '_ss_hide_home', true ) ) continue;
		$fmts = wp_get_post_terms( $pid, 'product-format', array( 'fields' => 'slugs' ) ); $fmt = '';
		if ( ! is_wp_error( $fmts ) ) foreach ( $fl as $k => $v ) if ( in_array( $k, $fmts, true ) ) { $fmt = $k; break; }
		$mp3 = ''; if ( preg_match( '#https?://[^\s"\'\]]+?\.mp3#i', get_post_field( 'post_excerpt', $pid ) . ' ' . get_post_field( 'post_content', $pid ), $m ) ) $mp3 = $m[0];
		$chips = ss_ns_terms( $pid, 'product-genre', 3 );
		$s[] = array( 'id' => $pid, 't' => get_the_title(), 'u' => get_permalink(), 'img' => get_the_post_thumbnail_url( $pid, 'woocommerce_single' ), 'th' => get_the_post_thumbnail_url( $pid, 'thumbnail' ),
			'fmt' => $fmt, 'fl' => $fmt ? $fl[ $fmt ] : '', 'chips' => $chips, 'yt' => isset( $yt[ $pid ] ) ? (string) $yt[ $pid ] : '', 'sp' => ss_ns_specs( $pid, $p, $fmt ), 'd' => ss_ns_desc( $pid ), 'lk' => (int) get_post_meta( $pid, '_ss_likes', true ), 'pr' => function_exists( 'ss_h3_price' ) ? ss_h3_price( $p ) : esc_html( wp_strip_all_tags( $p->get_price_html() ) ),
			'mp3' => $mp3, 'buy' => $p->is_purchasable() && $p->is_in_stock() && $p->is_type( 'simple' ), 'age' => ( time() - (int) get_post_time( 'U', true, $pid ) ) < 60 * DAY_IN_SECONDS ? human_time_diff( get_post_time( 'U', true, $pid ), time() ) : '' );
	}
	wp_reset_postdata();
	if ( ! $s ) return '';
	$n = count( $s ); ob_start(); ?>
<div class="ssn" id="ssn" aria-roledescription="carousel" aria-label="What’s new">
	<div class="ssn-hd"><h2 class="ssh-h2">What’s new</h2><div class="ssn-nav"><span class="ssn-n">1 / <?php echo (int) $n; ?></span><button type="button" class="ssn-pv" aria-label="Previous product">‹</button><button type="button" class="ssn-nx" aria-label="Next product">›</button></div></div>
	<div class="ssn-tr">
	<?php foreach ( $s as $i => $r ) : ?>
		<article class="ssn-s" aria-roledescription="slide" aria-label="<?php echo esc_attr( ( $i + 1 ) . ' of ' . $n . ': ' . $r['t'] ); ?>" data-pid="<?php echo (int) $r['id']; ?>"<?php if ( $r['mp3'] ) : ?> data-src="<?php echo esc_url( $r['mp3'] ); ?>" data-t="<?php echo esc_attr( $r['t'] ); ?>" data-i="<?php echo esc_url( $r['th'] ); ?>" data-u="<?php echo esc_url( $r['u'] ); ?>"<?php endif; ?>>
			<a class="ssn-cov<?php echo $r['yt'] ? ' has-yt' : ''; ?>" href="<?php echo esc_url( $r['yt'] ? 'https://www.youtube.com/watch?v=' . $r['yt'] : $r['u'] ); ?>"<?php if ( $r['yt'] ) : ?> data-yt="<?php echo esc_attr( $r['yt'] ); ?>" aria-label="Watch the video: <?php echo esc_attr( $r['t'] ); ?>"<?php else : ?> tabindex="-1"<?php endif; ?>><img src="<?php echo esc_url( $r['img'] ); ?>" alt="<?php echo esc_attr( $r['t'] ); ?>" width="560" height="560" decoding="async" <?php echo 0 === $i ? 'fetchpriority="high"' : 'loading="lazy"'; ?>></a>
			<div class="ssn-b">
				<div class="ssn-top"><em class="ssn-new">New</em><?php if ( $r['fl'] ) : ?><span class="ssn-fmt ssn-fmt-<?php echo esc_attr( $r['fmt'] ); ?>"><?php echo esc_html( $r['fl'] ); ?></span><?php endif; ?><?php if ( $r['age'] ) : ?><span class="ssn-age">added <?php echo esc_html( $r['age'] ); ?> ago</span><?php endif; ?></div>
				<h3 class="ssn-t"><a href="<?php echo esc_url( $r['u'] ); ?>"><?php echo esc_html( $r['t'] ); ?></a></h3>
				<?php if ( $r['chips'] ) : ?><div class="ssn-ch"><?php foreach ( $r['chips'] as $c ) echo '<span>' . esc_html( $c ) . '</span>'; ?></div><?php endif; ?>
				<?php if ( $r['d'] ) : ?><p class="ssn-d"><?php echo esc_html( $r['d'] ); ?></p><?php endif; ?>
				<?php if ( $r['sp'] ) : ?><dl class="ssn-sp"><?php foreach ( $r['sp'] as $k => $v ) echo '<div><dt>' . esc_html( $k ) . '</dt><dd>' . esc_html( $v ) . '</dd></div>'; ?></dl><?php endif; ?>
				<?php if ( $r['mp3'] ) : ?><div class="ssn-wv"><audio class="wp-audio-shortcode" preload="none" src="<?php echo esc_url( $r['mp3'] ); ?>"></audio></div><?php endif; ?>
				<div class="ssn-f">
					<button type="button" class="ss-like ssn-lk" data-pid="<?php echo (int) $r['id']; ?>" aria-label="Like" title="Like"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20.5s-7.5-4.6-7.5-10.2A4.3 4.3 0 0 1 12 7.6a4.3 4.3 0 0 1 7.5 2.7c0 5.6-7.5 10.2-7.5 10.2z"/></svg><span data-likes="<?php echo (int) $r['id']; ?>"><?php echo $r['lk'] ? (int) $r['lk'] : ''; ?></span></button>
					<a class="ssn-i" href="<?php echo esc_url( $r['u'] ); ?>" aria-label="Details: <?php echo esc_attr( $r['t'] ); ?>" title="Details">i</a>
					<?php $ssn_old = preg_match( '#<del>(.*?)</del>#', (string) $r['pr'], $ssn_m ) ? $ssn_m[1] : ''; $ssn_now = trim( preg_replace( '#<del>.*?</del>|</?ins>#', '', (string) $r['pr'] ) ); if ( $ssn_old ) : ?><del class="ssn-old"><?php echo $ssn_old; /* escaped in ss_h3_price */ ?></del><?php endif; ?><?php if ( $r['buy'] ) : ?><a class="ssn-cart add_to_cart_button ajax_add_to_cart" href="<?php echo esc_url( add_query_arg( 'add-to-cart', $r['id'], $r['u'] ) ); ?>" data-product_id="<?php echo (int) $r['id']; ?>" data-quantity="1" rel="nofollow" aria-label="Add <?php echo esc_attr( $r['t'] ); ?> to cart"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 4h2l2.4 11h10.2L20 7H7M10 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm7 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg><span class="ssn-cp"><?php echo $ssn_now; ?></span></a><?php else : ?><a class="ssn-cart" href="<?php echo esc_url( $r['u'] ); ?>"><span class="ssn-cp"><?php echo $ssn_now; ?></span></a><?php endif; ?>
				</div>
			</div>
		</article>
	<?php endforeach; ?>
	</div>
	<div class="ssn-dots" role="tablist"><?php for ( $i = 0; $i < $n; $i++ ) echo '<button type="button" role="tab" aria-label="Product ' . ( $i + 1 ) . '"' . ( 0 === $i ? ' aria-selected="true"' : '' ) . '></button>'; ?></div>
</div>
<style id="ssn-css">
.ssn{max-width:1000px;margin:6px auto 0;font-family:Roboto,sans-serif}
.ssn-hd{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:10px}.ssn-hd .ssh-h2{text-align:left!important;height:auto}
.ssn-nav{display:flex;align-items:center;gap:8px}.ssn-n{color:#9B9288;font:700 14px "Exo 2",sans-serif;margin-right:4px}
.ssn-nav button{width:40px;height:40px;border-radius:10px;border:1px solid rgba(255,255,255,.14);background:rgba(255,255,255,.04);color:#F4EFE8;font:400 26px/1 sans-serif;padding:0 0 3px;cursor:pointer}.ssn-nav button:hover{border-color:#E09900;color:#E09900}
.ssn-tr{display:flex;overflow-x:auto;scroll-snap-type:x mandatory;scroll-behavior:smooth;scrollbar-width:none;border-radius:16px}.ssn-tr::-webkit-scrollbar{display:none}
.ssn-s{flex:0 0 100%;margin:0!important;scroll-snap-align:start;scroll-snap-stop:always;display:grid;grid-template-columns:300px minmax(0,1fr);gap:26px;box-sizing:border-box;padding:16px;border-radius:16px;overflow:hidden;min-width:0;border:1px solid rgba(255,255,255,.1);background:linear-gradient(135deg,rgba(255,255,255,.05),rgba(255,255,255,.015))}
.ssn-cov{position:relative;display:block;aspect-ratio:1/1;border-radius:12px;overflow:hidden;background:#1a1816}.ssn-cov img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s}.ssn-cov:hover img{transform:scale(1.03)}.ssn-cov iframe{position:absolute;z-index:1;top:50%;left:0;width:100%;height:auto;aspect-ratio:16/9;transform:translateY(-50%);border:0;pointer-events:none;opacity:0;transition:opacity .6s}.ssn-cov iframe.on{opacity:1}.ssn-cov:has(iframe.on){background:#000}.ssn-cov:has(iframe.on) img{opacity:0}.ssn-cov.has-yt{cursor:pointer}
.ssn-b{display:flex;flex-direction:column;gap:10px;min-width:0;padding:4px 0}
.ssn-top{display:flex;align-items:center;flex-wrap:wrap;gap:8px}.ssn-new{font-style:normal;font:800 11.5px "Exo 2",sans-serif;letter-spacing:1.2px;text-transform:uppercase;background:#E09900;color:#111;border-radius:6px;padding:4px 8px}
.ssn-fmt{font:700 11.5px Roboto,sans-serif;letter-spacing:1.2px;text-transform:uppercase;border-radius:6px;padding:4px 8px;color:#E09900;background:rgba(224,153,0,.12)}.ssn-fmt-presets{color:#C9A7FF;background:rgba(160,110,255,.14)}.ssn-fmt-music{color:#8FD3C7;background:rgba(80,190,170,.14)}
.ssn-age{color:#9B9288;font-size:13px}
.ssn-t{margin:0!important;font:800 30px/1.15 "Exo 2",sans-serif!important;letter-spacing:-.3px}.ssn-t a{color:#F4EFE8!important;text-decoration:none!important}.ssn-t a:hover{color:#E09900!important}
.ssn-ch{display:flex;flex-wrap:wrap;gap:6px}.ssn-ch span{font:600 13px Roboto,sans-serif;color:#E2DCD4;padding:5px 10px;border-radius:999px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.03)}
.ssn-sp{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:6px 18px;margin:0!important;padding:10px 0!important;border-top:1px solid rgba(255,255,255,.08);border-bottom:1px solid rgba(255,255,255,.08)}.ssn-sp div{min-width:0}.ssn-sp dt{color:#8A8279;font:600 11.5px Roboto,sans-serif;letter-spacing:1px;text-transform:uppercase;margin:0}.ssn-sp dd{margin:2px 0 0!important;color:#F4EFE8;font:600 14.5px Roboto,sans-serif;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ssn-d{margin:0!important;padding:0!important;color:#B9B1A7;font:400 15px/1.55 Roboto,sans-serif!important;max-height:4.65em;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
.ssn-f{display:flex;align-items:center;flex-wrap:wrap;gap:10px;margin-top:auto}
.ssn-pr{font:800 24px "Exo 2",sans-serif;color:#E09900;margin-right:6px}.ssn-pr del{position:relative;color:#7d756c;font-size:16px;font-weight:600;margin-right:4px}.ssn-pr ins{text-decoration:none}
.ssn-f button,.ssn-f a{height:44px;box-sizing:border-box;display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:0 18px;border-radius:8px;font:700 15px "Exo 2",sans-serif!important;text-decoration:none!important;cursor:pointer;transition:background .2s,border-color .2s,color .2s}
.ssn-pl{border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.05);color:#F4EFE8}.ssn-pl:hover{border-color:#E09900;color:#E09900}.ssn-pl svg{width:16px;height:16px;fill:currentColor}.ssn-pl .b,.ssn-s.is-pl .ssn-pl .a{display:none}.ssn-s.is-pl .ssn-pl .b{display:inline}
.ssn-wv{min-height:40px;min-width:0;overflow:hidden}.ssn-wv .ssw-btn{flex:0 0 44px!important;width:44px!important;height:44px!important;border-radius:50%!important;background:transparent!important;border:0!important;box-shadow:none!important;color:#F59E0B!important}.ssn-wv .ssw-btn:hover{background:rgba(245,158,11,.12)!important}.ssn-wv .ssw-btn .ssw-i-play{display:none!important}.ssn-wv .ssw:not(.is-playing) .ssw-btn:before{content:'';width:28px;height:28px;margin-left:4px;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M7 4.5v15l12.5-7.5z' fill='none' stroke='%23F59E0B' stroke-width='2.6' stroke-linejoin='round'/%3E%3C/svg%3E") center/contain no-repeat}.ssn-wv .ssw-btn svg{fill:#F59E0B!important}.ssn-wv .ssw{margin:0!important;width:100%!important}.ssn-wv audio{width:100%}
.ssn-f{justify-content:flex-start}.ssn-lk,.ssn-i{min-width:52px;height:46px!important;padding:0 14px!important;border-radius:10px!important;border:1px solid rgba(255,255,255,.14)!important;background:rgba(255,255,255,.04)!important;color:#CBD5E1!important;font:600 15px Roboto,sans-serif!important}.ssn-lk svg{width:20px;height:20px;fill:none;stroke:currentColor;stroke-width:2}.ssn-lk.is-liked,.ssn-lk:hover,.ssn-i:hover{color:#F59E0B!important;border-color:rgba(245,158,11,.5)!important}.ssn-lk.is-liked svg{fill:currentColor}.ssn-i{font:800 18px Georgia,serif!important}
.ssn-cart{height:46px!important;padding:0 20px!important;border-radius:10px!important;border:0!important;background:#F59E0B!important;color:#111!important;font:800 18px "Exo 2",sans-serif!important}.ssn-cart:hover{background:#FBBF24!important}.ssn-cart svg{width:21px;height:21px;fill:none;stroke:currentColor;stroke-width:2.2;stroke-linecap:round;stroke-linejoin:round}.ssn-cp del{font-size:13px;opacity:.6;margin-right:4px;font-weight:600}.ssn-cp ins{text-decoration:none}.ssn-old{position:relative;color:#7d756c;font:700 18px "Exo 2",sans-serif;margin:0 2px 0 6px}.ssn-cart.added{background:#16a34a!important;color:#fff!important}.ssn-f .added_to_cart{display:none!important}
.ssn-dots{display:flex;justify-content:center;gap:6px;margin-top:10px}.ssn-dots button{width:8px;height:8px;padding:0;border:0;border-radius:99px;background:rgba(255,255,255,.22);cursor:pointer;transition:width .3s,background .3s}.ssn-dots button[aria-selected=true]{width:24px;background:#E09900}
@media(max-width:1100px){.ssn{max-width:none}}
@media(max-width:700px){.ssn-s{grid-template-columns:108px minmax(0,1fr);gap:10px 12px;padding:12px}.ssn-b{display:contents}.ssn-cov{grid-column:1;grid-row:1/3}.ssn-top{grid-column:2;grid-row:1;align-self:end}.ssn-t{grid-column:2;grid-row:2;align-self:start;font-size:19px!important}
.ssn-ch,.ssn-d,.ssn-sp,.ssn-wv,.ssn-f{grid-column:1/-1}.ssn-sp{grid-template-columns:1fr 1fr;padding:8px 0!important}.ssn-sp dd{font-size:13.5px}.ssn-ch span{font-size:12px;padding:4px 8px}.ssn-d{font-size:14px!important;-webkit-line-clamp:2;max-height:3.1em}.ssn-pr{font-size:20px}.ssn-f{gap:8px}.ssn-f button,.ssn-f a{height:40px;padding:0 14px;font-size:14px!important}.ssn-more{display:none!important}.ssn-n{display:none}.ssn-nav button{width:36px;height:36px}.ssn-age{display:none}}
html.ss-lite .ssn-tr{scroll-behavior:auto}html.ss-lite .ssn-cov img{transition:none}
</style>
<script>(function(){var R=document.getElementById('ssn');if(!R)return;var T=R.querySelector('.ssn-tr'),S=[].slice.call(T.children),D=[].slice.call(R.querySelectorAll('.ssn-dots button')),N=R.querySelector('.ssn-n'),cur=0,hold=0,tm;
function idx(){return Math.round(T.scrollLeft/(T.clientWidth||1))}
var A=String.fromCharCode(38),lite=document.documentElement.classList.contains('ss-lite'),vt;function vid(){clearTimeout(vt);S.forEach(function(x,k){var f=x.querySelector('.ssn-cov iframe');if(f){if(k!==cur)f.remove()}});if(lite)return;if(navigator.connection?navigator.connection.saveData:0)return;var c=S[cur].querySelector('.ssn-cov.has-yt');if(!c)return;if(c.querySelector('iframe'))return;vt=setTimeout(function(){var y=c.getAttribute('data-yt'),f=document.createElement('iframe');f.title='Video preview';f.allow='autoplay; encrypted-media';f.setAttribute('tabindex','-1');f.src='https://www.youtube-nocookie.com/embed/'+y+'?autoplay=1'+A+'mute=1'+A+'loop=1'+A+'playlist='+y+A+'controls=0'+A+'modestbranding=1'+A+'playsinline=1'+A+'rel=0'+A+'disablekb=1';f.onload=function(){setTimeout(function(){f.classList.add('on')},700)};c.appendChild(f)},900)}
function mark(){var i=idx();if(i===cur)return;cur=i;vid();if(N)N.textContent=(i+1)+' / '+S.length;D.forEach(function(d,k){d.setAttribute('aria-selected',k===i?'true':'false')})}
function go(i){i=(i+S.length)%S.length;T.scrollTo({left:i*T.clientWidth})}
var raf;T.addEventListener('scroll',function(){if(!raf)raf=requestAnimationFrame(function(){raf=0;mark()})},{passive:true});
R.addEventListener('click',function(e){var y=e.target.closest('.ssn-cov.has-yt');if(y){if(e.metaKey||e.ctrlKey||e.shiftKey)return;e.preventDefault();e.stopPropagation();hold=Date.now()+60000;var sl=y.closest('.ssn-s');if(window.ssYtOpen)ssYtOpen(y.getAttribute('data-yt'),sl.getAttribute('aria-label').replace(/^.*?: /,''));else location.href=y.href;return}var b=e.target.closest('.ssn-pv,.ssn-nx,.ssn-dots button,.ssn-pl');if(!b)return;hold=Date.now()+20000;
 if(b.classList.contains('ssn-pv'))go(cur-1);else if(b.classList.contains('ssn-nx'))go(cur+1);else if(b.classList.contains('ssn-pl'))play(b.closest('.ssn-s'));else go(D.indexOf(b))});
function tr(s){return{pid:s.getAttribute('data-pid'),src:s.getAttribute('data-src'),t:s.getAttribute('data-t'),a:'Solid Sound',i:s.getAttribute('data-i'),u:s.getAttribute('data-u')}}
var au;function play(s){var on=s.classList.contains('is-pl');S.forEach(function(x){x.classList.remove('is-pl')});
 if(window.SSP){try{if(on){SSP.toggle?SSP.toggle():0}else{var q=S.filter(function(x){return x.getAttribute('data-src')}).map(tr);SSP.play(tr(s),q,null);s.classList.add('is-pl')}}catch(x){}return}
 if(!au)au=new Audio();if(on){au.pause();return}au.src=s.getAttribute('data-src');au.play();s.classList.add('is-pl')}
document.addEventListener('ssp:state',function(){var st=window.SSP?SSP.state:null,src=st?(st.cur?String(st.cur.src):''):'',pl=document.querySelector('.ssp.is-playing');S.forEach(function(x){x.classList.toggle('is-pl',pl?x.getAttribute('data-src')===src:false)})});
['mouseenter','focusin','touchstart'].forEach(function(ev){R.addEventListener(ev,function(){hold=Date.now()+(ev==='touchstart'?20000:8000)},{passive:true})});
R.addEventListener('mousemove',function(){hold=Date.now()+8000},{passive:true});
if(!(window.matchMedia?matchMedia('(prefers-reduced-motion: reduce)').matches:0))tm=setInterval(function(){if(document.hidden)return;if(Date.now()<hold)return;if(R.querySelector('.ssn-s.is-pl'))return;var r=R.getBoundingClientRect();if(r.bottom<0)return;if(r.top>innerHeight)return;if(S[cur].querySelector('.ssn-cov.has-yt')){tk++;if(tk<2)return}tk=0;go(cur+1)},6000);var tk=0;
try{var LK=JSON.parse(localStorage.getItem('ss_liked')||'[]').map(String);R.querySelectorAll('.ssn-lk').forEach(function(b){if(LK.indexOf(b.getAttribute('data-pid'))>-1)b.classList.add('is-liked')})}catch(e){}vid();})();</script>
	<?php return preg_replace( '/>\s+</', '><', preg_replace( '/\n\s*/', ' ', ob_get_clean() ) ); /* one line: wpautop must not add <p>/<br> */
}
