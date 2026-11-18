<?php
if ( ! defined( 'ABSPATH' ) ) exit;
//Вывод рейтинга в звездах в каталог
add_filter('woocommerce_product_get_rating_html', 'your_get_rating_html', 10, 2);
  function your_get_rating_html($rating_html, $rating) {
    /* 23.10.2026: multilingual rating text (was hardcoded Russian) */
    $ssl = function_exists('pll_current_language') ? pll_current_language() : 'en';
    $ss_ofm = array('ua'=>'з','de'=>'von','fr'=>'sur','pt'=>'de');
    $ss_of = isset($ss_ofm[$ssl]) ? $ss_ofm[$ssl] : 'out of';
    $ss_rt = array('ua'=>'Оцінка','de'=>'Bewertung','fr'=>'Note','pt'=>'Avaliação');
    $ss_nr = array('ua'=>'Ще немає оцінок','de'=>'Noch nicht bewertet','fr'=>'Pas encore noté','pt'=>'Ainda sem avaliação');
    if ( $rating > 0 ) {
      $title = ( isset($ss_rt[$ssl]) ? $ss_rt[$ssl] : 'Rated' ) . ' ' . $rating . ' ' . $ss_of . ' 5';
    } else {
      $title = isset($ss_nr[$ssl]) ? $ss_nr[$ssl] : 'Not yet rated';
      $rating = 0;
    }
    $rating_html  = '<div class="star-rating" title="' . $title . '">';
    $rating_html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"><strong class="rating">' . $rating . '</strong> ' . $ss_of . ' 5' . '</span>';
    $rating_html .= '</div>';
    return $rating_html;
  }
  
function rating_and_sidebar() {
    remove_menu_page('us-theme-options');
}
add_action('admin_menu', 'rating_and_sidebar', 999);

function wph_new_toolbar() {
global $wp_admin_bar;
 
$wp_admin_bar->remove_menu('us-theme-options');
 
}
add_action('wp_before_admin_bar_render', 'wph_new_toolbar');

add_filter( 'add_to_cart_text', 'woo_custom_single_add_to_cart_text' );                // < 2.1
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_single_add_to_cart_text' );  // 2.1 +
  
  
/* (13.11.2026 owner: price on every buy button) label = "Add to cart · <price>", sale price included */
function ss_cart_price_txt( $product ) {
	if ( ! $product && isset( $GLOBALS['product'] ) ) $product = $GLOBALS['product'];
	if ( ! is_object( $product ) || ! method_exists( $product, 'get_price' ) ) return '';
	$p = wc_get_price_to_display( $product );
	if ( $p <= 0 ) return '';
	return trim( html_entity_decode( wp_strip_all_tags( wc_price( $p, array( 'decimals' => 0 ) ) ), ENT_QUOTES, 'UTF-8' ) );
}
function ss_cart_btn_label( $base, $product = null ) {
	$pr = ss_cart_price_txt( $product );
	return $pr ? $base . ' · ' . $pr : $base;
}
function woo_custom_single_add_to_cart_text() {
	$word = function_exists( 'ss_pd2_t' ) ? ss_pd2_t( 'buy' ) : __( 'Buy', 'woocommerce' );
	return ss_cart_btn_label( $word );
}
/* catalog / shop loop buttons: same "Add to cart · price" (only for products that add straight to the cart) */
add_filter( 'woocommerce_product_add_to_cart_text', function ( $text, $product ) {
	if ( is_object( $product ) && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
		return ss_cart_btn_label( __( 'Add to cart', 'woocommerce' ), $product );
	}
	return $text;
}, 20, 2 );


//Calc Mixing
//
// === Solid Sound: Calculator AJAX Mail ===
add_action('wp_ajax_ss_calc_send','ss_calc_send_handler');
add_action('wp_ajax_nopriv_ss_calc_send','ss_calc_send_handler');

function ss_calc_send_handler(){
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_send_json_error(['message'=>'Bad request'],405);
    }

    // Honeypot
    $hp = isset($_POST['hp']) ? trim((string)$_POST['hp']) : '';
    if ($hp !== '') { wp_send_json_success(['message'=>'ok']); }

    $name    = sanitize_text_field($_POST['name'] ?? '');
    $email   = sanitize_email($_POST['email'] ?? '');
    $msg_raw = isset($_POST['message']) ? (string)$_POST['message'] : '';

    $package = sanitize_text_field($_POST['package_label'] ?? '');
    $vocal   = sanitize_text_field($_POST['vocal_tuning'] ?? '');
    $addons  = sanitize_text_field($_POST['addons_text'] ?? '');
    $total   = sanitize_text_field($_POST['total_eur'] ?? '');
    $quote   = sanitize_textarea_field($_POST['quote_text'] ?? '');

    if ($name === '' || !is_email($email)) {
        wp_send_json_error(['message'=>'Please provide a valid name and email.']);
    }

    $to       = 'solidsoundb@gmail.com';
    $subject  = 'Mix/Mastering order — ' . $name;
    $host     = parse_url(home_url(), PHP_URL_HOST);
    $from     = 'no-reply@' . $host;

    $headers = [
        'From: Solid Sound <' . $from . '>',
        'Reply-To: ' . $name . ' <' . $email . '>',
        'Content-Type: text/html; charset=UTF-8',
    ];

    $name_h   = esc_html($name);
    $email_h  = esc_html($email);
    $msg_h    = nl2br(esc_html($msg_raw));
    $package_h= esc_html($package);
    $vocal_h  = esc_html($vocal);
    $addons_h = esc_html($addons);
    $total_h  = esc_html($total);
    $quote_h  = esc_html($quote);
    $home_h   = esc_url(home_url());

    // HTML лист через HEREDOC (без проблем з лапками)
    $body = <<<HTML
<html><body style="font-family:Arial,Helvetica,sans-serif;background:#0b1620;color:#e7eef5;">
<div style="max-width:720px;margin:0 auto;padding:16px;background:#101923;border:1px solid #203040;border-radius:12px">
  <h2 style="margin:0 0 12px">New Mix/Mastering request</h2>
  <p><strong>Package:</strong> {$package_h}</p>
  <p><strong>Vocal tuning:</strong> {$vocal_h}</p>
  <p><strong>Add-ons:</strong> {$addons_h}</p>
  <p><strong>Total:</strong> € {$total_h}</p>
  <hr style="border:none;border-top:1px solid #2a3a4a;margin:12px 0" />
  <p><strong>Name:</strong> {$name_h}<br /><strong>Email:</strong> {$email_h}</p>
HTML;

    if ($msg_raw !== '') {
        $body .= "<p><strong>Message:</strong><br />{$msg_h}</p>";
    }

    $body .= <<<HTML
  <hr style="border:none;border-top:1px solid #2a3a4a;margin:12px 0" />
  <p style="margin:0 0 6px;color:#9fb2c7">Plain text quote:</p>
  <pre style="white-space:pre-wrap;background:#0c151f;padding:10px;border-radius:8px;color:#dce6f2">{$quote_h}</pre>
  <p style="color:#9fb2c7">Sent from {$home_h}</p>
</div></body></html>
HTML;

    $sent = wp_mail($to, $subject, $body, $headers);
    if ($sent) { wp_send_json_success(['message'=>'sent']); }
    wp_send_json_error(['message'=>'Mail failed. Configure SMTP (WP Mail SMTP) or hosting.']);
}

// Лог деталей помилки від PHPMailer (видно в debug.log при WP_DEBUG=true)
add_action('wp_mail_failed', function($err){
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[wp_mail_failed] '.$err->get_error_message());
    }
});



//
// Calc Mastering Album


/**
 * Album Mastering Mailer (AJAX)
 * Recommended: save as wp-content/mu-plugins/album-mastering-mailer.php
 * Alternatively paste into functions.php (then REMOVE this opening <?php line).
 */

// AJAX endpoints
add_action('wp_ajax_album_mastering_quote', 'album_mastering_quote_handler');
add_action('wp_ajax_nopriv_album_mastering_quote', 'album_mastering_quote_handler');

// Log any mailer errors into wp-content/debug.log (enable WP_DEBUG & WP_DEBUG_LOG)
add_action('wp_mail_failed', function($error){
    error_log('[MAIL ERROR] ' . $error->get_error_message());
});

if (!function_exists('album_mastering_quote_handler')) {
    function album_mastering_quote_handler() {
        // Sanitize inputs
        $name      = sanitize_text_field($_POST['name'] ?? '');
        $email     = sanitize_email($_POST['email'] ?? '');
        $message   = sanitize_textarea_field($_POST['message'] ?? '');
        $breakdown = wp_kses_post($_POST['breakdown'] ?? '');
        $total     = sanitize_text_field($_POST['total'] ?? '');
        $tracks    = intval($_POST['tracks'] ?? 0);

        if (!$name || !is_email($email) || $tracks <= 0) {
            wp_send_json_error(['message' => 'Please fill in all required fields.']);
        }

        // Recipient — owner only (best deliverability). Adjust if needed.
        $owner_email = 'solidsoundb@gmail.com';
        $to = $owner_email;

        $subject = 'Album Mastering Quote Request';

        $body_lines = [
            'Name: ' . $name,
            'Email: ' . $email,
            'Tracks in album: ' . $tracks,
            '',
            'Breakdown:',
            $breakdown,
            '',
            'Total: €' . $total,
        ];
        if ($message) {
            $body_lines[] = '';
            $body_lines[] = 'Message:';
            $body_lines[] = $message;
        }
        $body = implode("\n", $body_lines);

        // Safe From (same domain) + Reply-To customer
        $domain = preg_replace('/^www\./', '', parse_url(home_url(), PHP_URL_HOST) ?: 'localhost');
        $from_addr = 'no-reply@' . $domain;
        $headers = [
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . $from_addr . '>',
            'Reply-To: ' . $name . ' <' . $email . '>',
        ];

        $sent = wp_mail($to, $subject, $body, $headers);

        if ($sent) {
            error_log('[MAIL OK] Sent to ' . $to . '; Reply-To ' . $email);
            wp_send_json_success(['message' => 'Thanks! Your request has been sent. We will contact you shortly.']);
        } else {
            error_log('[MAIL FAIL] Could not send to ' . $to);
            wp_send_json_error(['message' => 'Sending failed. Please try again later.']);
        }
    }
}




///////////////////////////////////////////////////////////////////////


// ДОДАЄ МОЖЛИВІСЬ ЗМІНЮВАТИ АВТОРІВ
/**
 * Enable "Author" box for WooCommerce products
 */
add_action('init', function () {
  // вмикаємо підтримку автора для CPT "product"
  add_post_type_support('product', 'author');
}, 20);

/**
 * (Необов'язково) Показати стовпчик "Author" у списку продуктів
 */
add_filter('manage_edit-product_columns', function ($cols) {
  // вставимо після колонки "name"
  $new = [];
  foreach ($cols as $key => $label) {
    $new[$key] = $label;
    if ($key === 'name') {
      $new['author'] = __('Author');
    }
  }
  return $new;
});

add_action('manage_product_posts_custom_column', function ($column, $post_id) {
  if ($column === 'author') {
    $user = get_userdata(get_post_field('post_author', $post_id));
    echo $user ? esc_html($user->display_name) : '—';
  }
}, 10, 2);



/////////////////////////////////////////////////////////////////////////////////////////////////








// PAGE ADD-PRODUCT

// //✅ Що робить цей код:

// додає повну форму додавання продукту на фронті через [solid_sound_add_product_form];

// рендерить твої ACF-поля (group_61de909636770);

// створює продукт у статусі Draft;

// підтягує select2-фікси (висота, хрестики);

// автоматично синхронізує вибрані таксономії у глобальні WooCommerce атрибути (pa_*), щоб вони з’являлись у вкладці “Additional information”.



/**
 * 🔴🔵🟢 SOLID SOUND — FRONTEND ADD PRODUCT + ACF + DYNAMIC TAX→pa_* SYNC
 * 🟥 RED: Форма + рендер ACF групи (group_61de909636770)
 * 🟩 GREEN: ACF скрипти + select2 фікси
 * 🟦 BLUE: Хендлер — створення Draft + збереження ACF + синхронізація атрибутів
 */

if ( ! defined('ABSPATH') ) exit;

/* ──────────────────────────────────────────────────────────────
   🟩 ACF СКРИПТИ + SELECT2 ФІКСИ (хрестик не обрізається)
   ────────────────────────────────────────────────────────────── */
add_action('wp_enqueue_scripts', function () {
    // Solid Sound perf (2026-10-11): guests never see ACF forms, so skip ACF/select2/datepicker/color-picker for them.
    if ( ! is_user_logged_in() ) return;
    if ( function_exists('acf_enqueue_scripts') ) acf_enqueue_scripts();
});

/* ══════════════════════════════════════════════════════════════
   ⭐ Solid Sound: Ratings (likes + plays + sales) & Top Tracks
   score = total_sales*10 + likes*3 + plays
   Meta: _ss_likes, _ss_plays. Shortcode: [ss_top_tracks limit="5" format="templates,presets" title="Top Tracks"]
   ══════════════════════════════════════════════════════════════ */
if ( ! function_exists( 'ss_rt_products' ) ) {

	function ss_rt_visitor_key() {
		$uid = get_current_user_id();
		if ( $uid ) return 'u' . $uid;
		$vid = isset( $_POST['vid'] ) ? preg_replace( '/[^a-zA-Z0-9]/', '', (string) wp_unslash( $_POST['vid'] ) ) : '';
		if ( strlen( $vid ) < 8 ) return '';
		return 'g' . substr( md5( $vid ), 0, 16 );
	}

	function ss_rt_client_ip() {
		$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';
		if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$parts = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
			$ip = trim( $parts[0] );
		}
		return $ip;
	}

	/* max N actions per IP per hour (anti-spam) */
	function ss_rt_throttle( $type, $max ) {
		$k = 'ss_rt_th_' . $type . '_' . md5( ss_rt_client_ip() );
		$n = (int) get_transient( $k );
		if ( $n >= $max ) return false;
		set_transient( $k, $n + 1, HOUR_IN_SECONDS );
		return true;
	}

	function ss_rt_valid_product( $pid ) {
		return $pid > 0 && get_post_type( $pid ) === 'product' && get_post_status( $pid ) === 'publish';
	}

	/* All products with counters (cached 10 min, cleared on like/play/order) */
	function ss_rt_products() {
		$data = get_transient( 'ss_rt_cache' );
		if ( is_array( $data ) ) return $data;
		$data = array();
		$ids = get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids' ) );
		foreach ( $ids as $id ) {
			$likes = (int) get_post_meta( $id, '_ss_likes', true );
			$plays = (int) get_post_meta( $id, '_ss_plays', true );
			$sales = (int) get_post_meta( $id, 'total_sales', true );
			$mp3 = '';
			$txt = get_post_field( 'post_excerpt', $id ) . ' ' . get_post_field( 'post_content', $id );
			if ( preg_match( '#https?://[^\s"\'\]]+?\.mp3#i', $txt, $m ) ) $mp3 = $m[0];
			$genre = '';
			$g = get_the_terms( $id, 'product-genre' );
			if ( $g && ! is_wp_error( $g ) ) $genre = $g[0]->name;
			$formats = array();
			$f = get_the_terms( $id, 'product-format' );
			if ( $f && ! is_wp_error( $f ) ) foreach ( $f as $t ) $formats[] = $t->slug;
			/* author: WCFM vendor store or Solid Sound */
			$author = 'Solid Sound';
			$uid = (int) get_post_field( 'post_author', $id );
			if ( $uid && function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor( $uid ) && function_exists( 'wcfm_get_vendor_store_name' ) ) {
				$sn = wp_strip_all_tags( (string) wcfm_get_vendor_store_name( $uid ) );
				if ( $sn !== '' ) $author = $sn;
			}
			/* precomputed waveform (valid only for the current mp3) */
			$peaks = '';
			$pdur = 0;
			if ( $mp3 && get_post_meta( $id, '_ss_peaks_src', true ) === basename( (string) parse_url( $mp3, PHP_URL_PATH ) ) ) {
				$peaks = (string) get_post_meta( $id, '_ss_peaks', true );
				$pdur = (float) get_post_meta( $id, '_ss_dur', true );
			}
			$data[ $id ] = array(
				'author'  => $author,
				'peaks'   => $peaks,
				'pdur'    => $pdur,
				'id'      => $id,
				'title'   => get_the_title( $id ),
				'url'     => get_permalink( $id ),
				'img'     => get_the_post_thumbnail_url( $id, 'thumbnail' ),
				'mp3'     => $mp3,
				'genre'   => $genre,
				'formats' => $formats,
				'likes'   => $likes,
				'plays'   => $plays,
				'sales'   => $sales,
				'score'   => $sales * 10 + $likes * 3 + $plays,
				'date'    => get_post_time( 'U', true, $id ),
			);
		}
		set_transient( 'ss_rt_cache', $data, 10 * MINUTE_IN_SECONDS );
		return $data;
	}

	function ss_rt_flush() { delete_transient( 'ss_rt_cache' ); }
	add_action( 'woocommerce_order_status_completed', 'ss_rt_flush' );
	add_action( 'woocommerce_order_status_processing', 'ss_rt_flush' );
	add_action( 'save_post_product', 'ss_rt_flush' );

	/* AJAX: like / unlike (guests allowed) */
	function ss_rt_ajax_like() {
		$pid = isset( $_POST['pid'] ) ? absint( $_POST['pid'] ) : 0;
		$key = ss_rt_visitor_key();
		if ( ! ss_rt_valid_product( $pid ) || ! $key ) wp_send_json_error( 'bad', 400 );
		if ( ! ss_rt_throttle( 'like', 60 ) ) wp_send_json_error( 'slow down', 429 );
		$t = 'ss_rt_l_' . $pid . '_' . $key;
		$likes = (int) get_post_meta( $pid, '_ss_likes', true );
		if ( get_transient( $t ) ) {
			delete_transient( $t );
			$likes = max( 0, $likes - 1 );
			$liked = false;
		} else {
			set_transient( $t, 1, YEAR_IN_SECONDS );
			$likes++;
			$liked = true;
		}
		update_post_meta( $pid, '_ss_likes', $likes );
		ss_rt_flush();
		wp_send_json_success( array( 'liked' => $liked, 'likes' => $likes ) );
	}
	add_action( 'wp_ajax_ss_rt_like', 'ss_rt_ajax_like' );
	add_action( 'wp_ajax_nopriv_ss_rt_like', 'ss_rt_ajax_like' );

	/* AJAX: play counter (1 per visitor per track per 30 min) */
	function ss_rt_ajax_play() {
		$pid = isset( $_POST['pid'] ) ? absint( $_POST['pid'] ) : 0;
		$key = ss_rt_visitor_key();
		if ( ! ss_rt_valid_product( $pid ) || ! $key ) wp_send_json_error( 'bad', 400 );
		$t = 'ss_rt_p_' . $pid . '_' . $key;
		$plays = (int) get_post_meta( $pid, '_ss_plays', true );
		if ( ! get_transient( $t ) && ss_rt_throttle( 'play', 120 ) ) {
			set_transient( $t, 1, 30 * MINUTE_IN_SECONDS );
			$plays++;
			update_post_meta( $pid, '_ss_plays', $plays );
			do_action( 'ss_play_counted', $pid ); /* creator space: plays per day */
			ss_rt_flush();
		}
		wp_send_json_success( array( 'plays' => $plays ) );
	}
	add_action( 'wp_ajax_ss_rt_play', 'ss_rt_ajax_play' );
	add_action( 'wp_ajax_nopriv_ss_rt_play', 'ss_rt_ajax_play' );

	/* AJAX: save precomputed waveform (only users who can edit this product) */
	function ss_rt_ajax_peaks() {
		$pid = isset( $_POST['pid'] ) ? absint( $_POST['pid'] ) : 0;
		if ( ! $pid || get_post_type( $pid ) !== 'product' || ! current_user_can( 'edit_post', $pid ) ) wp_send_json_error( 'forbidden', 403 );
		check_ajax_referer( 'ss_rt_peaks', 'nonce' );
		$p = isset( $_POST['peaks'] ) ? preg_replace( '/[^0-9a-z]/', '', strtolower( (string) wp_unslash( $_POST['peaks'] ) ) ) : '';
		$d = isset( $_POST['dur'] ) ? round( (float) $_POST['dur'], 1 ) : 0;
		$f = isset( $_POST['file'] ) ? sanitize_file_name( wp_unslash( $_POST['file'] ) ) : '';
		if ( strlen( $p ) < 50 || strlen( $p ) > 400 || $d <= 0 || $f === '' ) wp_send_json_error( 'bad', 400 );
		update_post_meta( $pid, '_ss_peaks', $p );
		update_post_meta( $pid, '_ss_dur', $d );
		update_post_meta( $pid, '_ss_peaks_src', $f );
		ss_rt_flush();
		wp_send_json_success();
	}
	add_action( 'wp_ajax_ss_rt_peaks', 'ss_rt_ajax_peaks' );

	/* Data for the front-end script */
	add_action( 'wp_footer', function () {
		if ( is_admin() ) return;
		$out = array();
		foreach ( ss_rt_products() as $p ) {
			$out[ $p['id'] ] = array( 'l' => $p['likes'], 'm' => $p['mp3'] ? basename( parse_url( $p['mp3'], PHP_URL_PATH ) ) : '', 's' => $p['mp3'], 'u' => $p['url'], 't' => $p['title'], 'a' => isset( $p['author'] ) ? $p['author'] : 'Solid Sound', 'i' => $p['img'], 'w' => isset( $p['peaks'] ) ? $p['peaks'] : '', 'd' => isset( $p['pdur'] ) ? $p['pdur'] : 0 );
		}
		$rt = array( 'ajax' => admin_url( 'admin-ajax.php' ), 'p' => $out );
		if ( is_user_logged_in() && current_user_can( 'edit_products' ) ) $rt['n'] = wp_create_nonce( 'ss_rt_peaks' );
		echo '<script>window.SS_RT=' . wp_json_encode( $rt ) . ';</script>' . "\n";
	}, 5 );

	/* [ss_top_tracks limit="3" format="presets" title="Top Presets"]
	   [ss_top_tracks limit="3" title="Top Tracks" tabs="All:|Templates:templates,reconstructed-templates|Presets:presets|Music:music"] */
	function ss_rt_top_items( $formats, $limit ) {
		$list = array_values( array_filter( ss_rt_products(), function ( $p ) use ( $formats ) {
			return ! $formats || array_intersect( $formats, $p['formats'] );
		} ) );
		usort( $list, function ( $x, $y ) {
			if ( $x['score'] === $y['score'] ) return $y['date'] - $x['date'];
			return $y['score'] - $x['score'];
		} );
		return array_slice( $list, 0, max( 1, (int) $limit ) );
	}
	function ss_rt_top_type( $p ) {
		$map = array( 'presets' => 'Preset', 'music' => 'Music', 'templates' => 'Template', 'reconstructed-templates' => 'Template' );
		foreach ( $map as $slug => $label ) if ( in_array( $slug, $p['formats'], true ) ) return $label;
		return '';
	}
	function ss_rt_top_ol( $list, $tab = '', $show_type = false ) {
		$heart = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7.5-4.6-10-9.3C.4 8.4 2.3 4.5 6 4.1c2-.2 3.6.8 6 3.1 2.4-2.3 4-3.3 6-3.1 3.7.4 5.6 4.3 4 7.6C19.5 16.4 12 21 12 21z"/></svg>';
		$h = '<ol class="ss-top-l"' . ( $tab !== '' ? ' data-tab="' . esc_attr( $tab ) . '"' : '' ) . '>';
		foreach ( $list as $i => $p ) {
			$sub = array();
			if ( $show_type && ss_rt_top_type( $p ) ) $sub[] = '<span class="ss-top-type">' . esc_html( ss_rt_top_type( $p ) ) . '</span>';
			if ( $p['genre'] ) $sub[] = esc_html( $p['genre'] );
			$h .= '<li class="ss-top-i" data-pid="' . (int) $p['id'] . '"' . ( $p['mp3'] ? ' data-src="' . esc_url( $p['mp3'] ) . '"' : '' ) . '>';
			$h .= '<span class="ss-top-n">' . ( $i + 1 ) . '</span>';
			$h .= '<span class="ss-top-c">' . ( $p['img'] ? '<img src="' . esc_url( $p['img'] ) . '" alt="" width="44" height="44" loading="lazy">' : '' );
			if ( $p['mp3'] ) $h .= '<button type="button" class="ss-top-play" aria-label="Play"><svg class="ssw-i-play" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg><svg class="ssw-i-pause" viewBox="0 0 24 24"><path d="M6 5h4v14H6zM14 5h4v14h-4z"/></svg></button>';
			$h .= '</span><span class="ss-top-m"><a class="ss-top-t" href="' . esc_url( $p['url'] ) . '">' . esc_html( $p['title'] ) . '</a>';
			$h .= '<span class="ss-top-s">' . implode( ' · ', $sub ) . ( $sub ? '<span class="ss-top-sep"> · </span>' : '' ) . '<span class="ss-top-lk">' . $heart . '<b data-likes="' . (int) $p['id'] . '">' . (int) $p['likes'] . '</b></span>' . '</span></span></li>';
		}
		return $h . '</ol>';
	}
	function ss_rt_top_shortcode( $atts ) {
		$a = shortcode_atts( array( 'limit' => 3, 'format' => '', 'title' => 'Best Sellers', 'tabs' => '' ), $atts );
		$split = function ( $s ) { return array_values( array_filter( array_map( 'trim', explode( ',', $s ) ) ) ); };
		if ( $a['tabs'] === '' ) {
			$list = ss_rt_top_items( $split( $a['format'] ), $a['limit'] );
			if ( ! $list ) return '';
			return '<div class="ss-top"><div class="ss-top-hd"><span class="ss-top-h">' . esc_html( $a['title'] ) . '</span></div>' . ss_rt_top_ol( $list ) . '</div>';
		}
		$btns = ''; $lists = ''; $n = 0;
		foreach ( explode( '|', $a['tabs'] ) as $tab ) {
			$parts = explode( ':', $tab, 2 );
			$label = trim( $parts[0] );
			$spec = isset( $parts[1] ) ? trim( $parts[1] ) : '';
			$mix = strpos( $spec, '@mix=' ) === 0;
			if ( $mix ) {
				/* best of each group first (e.g. 1 template + 1 preset + 1 music), then fill by score */
				$picked = array();
				foreach ( explode( ';', substr( $spec, 5 ) ) as $grp ) {
					foreach ( ss_rt_top_items( $split( $grp ), 99 ) as $p ) { if ( ! isset( $picked[ $p['id'] ] ) ) { $picked[ $p['id'] ] = $p; break; } }
				}
				foreach ( ss_rt_top_items( array(), 99 ) as $p ) { if ( count( $picked ) >= (int) $a['limit'] ) break; if ( ! isset( $picked[ $p['id'] ] ) ) $picked[ $p['id'] ] = $p; }
				$list = array_values( $picked );
				usort( $list, function ( $x, $y ) { return $y['score'] - $x['score']; } );
				$list = array_slice( $list, 0, max( 1, (int) $a['limit'] ) );
			} else {
				$list = ss_rt_top_items( $split( $spec ), $a['limit'] );
			}
			if ( ! $list || $label === '' ) continue;
			$key = sanitize_title( $label );
			$btns .= '<button type="button" class="ss-top-tab' . ( $n ? '' : ' is-active' ) . '" data-tab="' . esc_attr( $key ) . '" role="tab" aria-selected="' . ( $n ? 'false' : 'true' ) . '">' . esc_html( $label ) . '</button>';
			$lists .= ss_rt_top_ol( $list, $key, $mix );
			$n++;
		}
		if ( ! $n ) return '';
		return '<div class="ss-top has-tabs"><div class="ss-top-hd"><span class="ss-top-h">' . esc_html( $a['title'] ) . '</span></div><div class="ss-top-tabs" role="tablist">' . $btns . '</div>' . $lists . '</div>';
	}
	add_shortcode( 'ss_top_tracks', 'ss_rt_top_shortcode' );
}

/* ══════════════════════════════════════════════════════════════
   🔎 Solid Sound: SEO (titles, meta descriptions, schema.org)
   Helps Google understand key sections (sitelinks) and site name.
   ══════════════════════════════════════════════════════════════ */
if ( ! function_exists( 'ss_seo_pages' ) ) {

	/* key pages: slug => [title, description] */
	function ss_seo_pages() {
		return array(
			'__front' => array( 'Ableton Templates, Presets & Mixing/Mastering | Solid Sound', 'Royalty-free Ableton Live templates, Serum presets and music for video. Learn by reverse engineering pro tracks or order online mixing & mastering.' ),
			'templates' => array( 'Ableton Live Templates – Royalty-Free | Solid Sound', 'Royalty-free Ableton Live templates in techno and tech house. Fully mixed and mastered projects with native plugins – learn, remix and release.' ),
			'presets' => array( 'Serum Presets & Sound Packs | Solid Sound', 'Serum presets for melodic techno and more. Ready-to-use sounds by Solid Sound – instant download, royalty-free.' ),
			'music' => array( 'Royalty-Free Music for Video & Business | Solid Sound', 'Royalty-free music for videos, documentaries and business projects. Cinematic piano and more – instant download with a clear license.' ),
			'services-calculator' => array( 'Online Mixing & Mastering – Price Calculator | Solid Sound', 'Get an instant quote for online mixing & mastering. Choose the number of tracks, vocal tuning and extras – prices in EUR, send your request in one click.' ),
			'mastering-album-calculator' => array( 'Album Mastering – Price Calculator | Solid Sound', 'Album mastering price calculator: choose the number of tracks, tags & cover art and rush delivery. Free demo of the first track for group orders.' ),
			'forum' => array( 'Music Production Forum | Solid Sound', 'Solid Sound community forum: ideas, questions and discussions about music production, Ableton Live, mixing and mastering.' ),
			'faq' => array( 'FAQ – Payments, Licenses & Downloads | Solid Sound', 'Answers about payments, licenses, downloads and using Solid Sound templates in your own productions.' ),
			'license' => array( 'License Agreement – Royalty-Free Use | Solid Sound', 'What you can do with Solid Sound templates and template-based music: personal, educational and commercial use explained.' ),
			'services-prices' => array( 'Mixing, Mastering & Production Prices | Solid Sound', 'Online mixing from 60€, mastering from 15€, remixes, remakes, ghost production, co-production and audio post production. Compare packages and order online.' ),
			'contacts' => array( 'Contact Solid Sound Studio', 'Contact Solid Sound for questions about Ableton templates, presets, mixing and mastering.' ),
			'privacy-policy' => array( 'Privacy Policy | Solid Sound', 'How Solid Sound collects, uses and protects your personal data when you buy templates, presets and music or use our mixing and mastering services.' ),
			'refund_returns' => array( 'Refund Policy for Digital Products | Solid Sound', 'Refund rules for Solid Sound digital downloads: Ableton templates, Serum presets and royalty-free music. When and how you can request a refund.' ),
			'creator-terms' => array( 'Creator Terms – Sell Your Templates & Presets | Solid Sound', 'Terms for producers selling on Solid Sound: what you can sell, approval, pricing, your 70% share and monthly payouts.' ),
			'vendor-membership' => array( 'Become a Creator – Sell on Solid Sound', 'Sell your Ableton templates, presets, sample packs and music on Solid Sound. Keep 70% of every sale, monthly payouts.' ),
			'vendor-register' => array( 'Creator Registration | Solid Sound', 'Create your Solid Sound creator account to upload and sell Ableton templates, presets, sample packs and music.' ),
		);
	}

	function ss_seo_clean( $text, $len = 155 ) {
		$text = strip_shortcodes( (string) $text );
		$text = preg_replace( '#\[/?audio[^\]]*\]#i', '', $text );
		$text = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $text ) ) );
		$text = preg_replace( '/^description\s+/i', '', $text );
		if ( function_exists( 'mb_strlen' ) && mb_strlen( $text ) > $len ) {
			$cut = mb_substr( $text, 0, $len );
			$sp = mb_strrpos( $cut, ' ' );
			$text = rtrim( $sp ? mb_substr( $cut, 0, $sp ) : $cut, " ,.;:-–" ) . '…';
		}
		return $text;
	}

	/* returns [title, description] for the current request or null */
	function ss_seo_current() {
		if ( is_admin() ) return null;
		$ss_ml = function_exists( 'ss_seo_ml' ) ? ss_seo_ml() : null;
		if ( $ss_ml && $ss_ml[1] ) return $ss_ml;
		$ss_tx = function_exists( 'ss_seo_tax' ) ? ss_seo_tax() : null;
		if ( $ss_tx ) return $ss_tx;
		if ( function_exists( 'is_shop' ) && is_shop() ) return array( 'Shop – All Products | Solid Sound', 'All Solid Sound products: royalty-free Ableton Live templates, Serum presets and music for video. Listen to demos, instant download.' );
		$map = ss_seo_pages();
		if ( is_front_page() ) return $map['__front'];
		if ( function_exists( 'is_product' ) && is_product() ) {
			$id = get_queried_object_id();
			$type = '';
			$labels = array( 'presets' => 'Serum Presets', 'music' => 'Royalty-Free Music', 'templates' => 'Ableton Live Template', 'reconstructed-templates' => 'Ableton Live Template' );
			$f = get_the_terms( $id, 'product-format' );
			if ( $f && ! is_wp_error( $f ) ) foreach ( $labels as $slug => $lab ) { foreach ( $f as $t ) if ( $t->slug === $slug ) { $type = $lab; break 2; } }
			$g = get_the_terms( $id, 'product-genre' );
			$genre = ( $g && ! is_wp_error( $g ) ) ? $g[0]->name : '';
			$title = get_the_title( $id ) . ( $type ? ' – ' . trim( $genre . ' ' . $type ) : '' ) . ' | Solid Sound';
			$desc = ss_seo_clean( get_post_field( 'post_excerpt', $id ) );
			if ( ! $desc ) $desc = ss_seo_clean( get_post_field( 'post_content', $id ) );
			return array( $title, $desc );
		}
		if ( is_page() ) {
			$slug = strtolower( get_post_field( 'post_name', get_queried_object_id() ) );
			if ( isset( $map[ $slug ] ) ) return $map[ $slug ];
		}
		return null;
	}

	add_filter( 'pre_get_document_title', function ( $t ) {
		$s = ss_seo_current();
		return ( $s && $s[0] ) ? $s[0] : $t;
	}, 20 );

	add_action( 'wp_head', function () {
		$s = ss_seo_current();
		if ( $s && $s[1] ) {
			echo '<meta name="description" content="' . esc_attr( $s[1] ) . '">' . "\n";
		}
		if ( is_front_page() ) {
			$home = home_url( '/' );
			$logo = get_site_icon_url( 512 );
			$graph = array(
				array(
					'@type' => 'Organization',
					'@id' => $home . '#organization',
					'name' => 'Solid Sound',
					'url' => $home,
					'logo' => $logo ? $logo : null,
					'sameAs' => array(
						'https://www.instagram.com/solid.sound.studio',
						'https://www.facebook.com/SolidSoundStudio1',
						'https://www.youtube.com/channel/UCeU2yrebmva2mZE4cME4-pg',
						'https://www.tiktok.com/@solid.sound.studio',
					),
				),
				array(
					'@type' => 'WebSite',
					'@id' => $home . '#website',
					'name' => 'Solid Sound',
					'url' => $home,
					'publisher' => array( '@id' => $home . '#organization' ),
					'potentialAction' => array(
						'@type' => 'SearchAction',
						'target' => array( '@type' => 'EntryPoint', 'urlTemplate' => $home . '?s={search_term_string}&post_type=product' ),
						'query-input' => 'required name=search_term_string',
					),
				),
			);
			if ( ! $logo ) unset( $graph[0]['logo'] );
			echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
		}
	}, 2 );

	/* Impreza (UpSolution Core) prints its own meta description from the excerpt. Use ours instead. */
	add_filter( 'us_meta_tags', function ( $tags ) {
		$s = ss_seo_current();
		if ( $s && $s[1] && is_array( $tags ) ) {
			foreach ( array_keys( $tags ) as $k ) {
				if ( strtolower( (string) $k ) === 'description' ) unset( $tags[ $k ] );
			}
		}
		return $tags;
	} );

	/* WooCommerce product schema: no [audio] shortcode in description */
	add_filter( 'woocommerce_structured_data_product', function ( $markup, $product ) {
		if ( isset( $markup['description'] ) ) {
			$d = ss_seo_clean( $product->get_short_description() ? $product->get_short_description() : $product->get_description(), 300 );
			if ( $d ) $markup['description'] = $d;
		}
		return $markup;
	}, 10, 2 );
}

add_action('wp_head', function () { ?>
<style>
.acf-field,.acf-field .acf-input{overflow:visible}
.acf-field .select2-container .select2-selection--multiple{min-height:38px;padding:4px 6px;border-radius:4px;box-sizing:border-box}
.acf-field .select2-selection__choice{position:relative;margin:3px 6px 3px 0;padding:4px 26px 4px 10px;border-radius:4px}
.acf-field .select2-selection__choice__remove{position:absolute;right:8px;top:50%;transform:translateY(-50%)}
.acf-field .select2-search__field{height:28px;line-height:28px}
.select2-container .select2-results__options{max-height:240px;overflow-y:auto}
.select2-container{z-index:9999}.select2-dropdown{z-index:99999}
</style>
<?php });

/* ──────────────────────────────────────────────────────────────
   🟥 ШОРТКОД ФОРМИ: [solid_sound_add_product_form]
   ────────────────────────────────────────────────────────────── */
add_action('init', function () {
    add_shortcode('solid_sound_add_product_form', 'solid_sound_add_product_form_callback');
});
function solid_sound_add_product_form_callback() {
    if ( ! is_user_logged_in() ) return '<p>'.esc_html__('Please log in to upload a product.','solid-sound').'</p>';
    if ( function_exists('acf_enqueue_scripts') ) acf_enqueue_scripts();

    ob_start(); ?>
    <form id="solid-sound-upload-form" method="post" enctype="multipart/form-data"
          action="" style="max-width:1200px"><?php /* 16.11: post to this page — WCFM sends creators away from /wp-admin/ (admin-post.php), so their products were never created */ ?>
        <?php wp_nonce_field('solid_sound_add_product_nonce','solid_sound_nonce'); ?>
        <input type="hidden" name="action" value="solid_sound_add_product">

        <p><label><strong><?php esc_html_e('Product Name','solid-sound'); ?></strong></label><br>
           <input type="text" name="product_name" required style="width:100%"></p>

        <p><label><strong><?php esc_html_e('Product Description','solid-sound'); ?></strong></label><br>
           <textarea name="product_description" rows="4" style="width:100%"></textarea></p>

        <p><label><strong><?php esc_html_e('Price (€)','solid-sound'); ?></strong></label><br>
           <input type="number" name="product_price" min="0" step="0.01" required style="width:100%"></p>

        <p><label><strong><?php esc_html_e('Product Image','solid-sound'); ?></strong></label><br>
           <input type="file" name="product_image" accept="image/*"></p>

        <p><canvas id="productImagePreview" width="300" height="300" style="border:1px solid #ccc;display:block"></canvas></p>

        <p><label><strong><?php esc_html_e('Downloadable File (zip or audio)','solid-sound'); ?></strong></label><br>
           <input type="file" name="product_file" accept=".zip,audio/*"></p>

        <!-- 🟥 ACF GROUP (group_61de909636770) -->
        <?php solid_sound_render_front_acf_group_fields('group_61de909636770'); ?>

        <p style="margin-top:16px"><button type="submit" class="button button-primary">
            <?php esc_html_e('Upload Product','solid-sound'); ?></button></p>
    </form>

    <script>
    (function(){
      var f=document.querySelector('[name="product_image"]'),c=document.getElementById('productImagePreview');
      if(!f||!c)return;var x=c.getContext('2d');
      f.addEventListener('change',function(e){
        var file=e.target.files[0]; if(!file){x.clearRect(0,0,c.width,c.height);return;}
        var r=new FileReader(); r.onload=function(ev){ var img=new Image();
          img.onload=function(){ var t=Math.min(c.width/img.width,c.height/img.height),
            w=img.width*t,h=img.height*t,dx=(c.width-w)/2,dy=(c.height-h)/2;
            x.clearRect(0,0,c.width,c.height); x.drawImage(img,dx,dy,w,h); };
          img.src=ev.target.result; };
        r.readAsDataURL(file);
      });
    })();
    </script>
    <?php
    return ob_get_clean();
}

/* ──────────────────────────────────────────────────────────────
   🟥 РЕНДЕР ACF ГРУПИ У ФОРМІ
   ────────────────────────────────────────────────────────────── */
function solid_sound_render_front_acf_group_fields($group_key){
    if ( ! function_exists('acf_get_fields') || ! function_exists('acf_render_field_wrap') ) {
        echo '<p><em>ACF inactive.</em></p>'; return;
    }
    $fields = acf_get_fields($group_key);
    if ( empty($fields) ) { echo '<p><em>ACF group empty: '.esc_html($group_key).'</em></p>'; return; }
    echo '<fieldset style="margin-top:18px;padding:12px;border:1px solid #e5e7eb;border-radius:6px">';
    echo '<legend><strong>Additional Product Details</strong></legend>';
    foreach($fields as $f){
        $f['name']  = 'acf['.$f['key'].']';
        $f['id']    = 'acf-'.$f['key'];
        $f['value'] = null;
        acf_render_field_wrap($f);
    }
    echo '</fieldset>';
}

/* ──────────────────────────────────────────────────────────────
   🟦 ХЕНДЛЕР САБМІТУ ФОРМИ
   ────────────────────────────────────────────────────────────── */
add_action('admin_post_solid_sound_add_product', 'solid_sound_handle_product_upload');
/* 16.11: the form now posts to the page itself (front end) — run the same admin_post handlers there */
add_action( 'init', function () {
    if ( is_admin() || 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) || ( $_POST['action'] ?? '' ) !== 'solid_sound_add_product' || empty( $_POST['solid_sound_nonce'] ) ) return;
    do_action( 'admin_post_solid_sound_add_product' );
    exit;
}, 99 );
add_action('admin_post_nopriv_solid_sound_add_product', 'solid_sound_handle_product_upload');

function solid_sound_handle_product_upload(){
    if ( empty($_POST['solid_sound_nonce']) || ! wp_verify_nonce($_POST['solid_sound_nonce'],'solid_sound_add_product_nonce') )
        wp_die( esc_html__('Security check failed.','solid-sound') );
    if ( ! is_user_logged_in() ) wp_die( esc_html__('You must be logged in to upload a product.','solid-sound') );

    $name  = sanitize_text_field($_POST['product_name'] ?? '');
    $desc  = wp_kses_post($_POST['product_description'] ?? '');
    $price = floatval($_POST['product_price'] ?? 0);

    require_once ABSPATH.'wp-admin/includes/file.php';
    require_once ABSPATH.'wp-admin/includes/image.php';
    require_once ABSPATH.'wp-admin/includes/media.php';

    // image
    $img_id = 0;
    if ( ! empty($_FILES['product_image']['name']) ) {
        $u = wp_handle_upload($_FILES['product_image'], ['test_form'=>false]);
        if ( empty($u['error']) && ! empty($u['file']) ) {
            $att = ['post_mime_type'=>$u['type'],'post_title'=>sanitize_file_name($u['file']),
                    'post_content'=>'','post_status'=>'inherit'];
            $img_id = wp_insert_attachment($att, $u['file']);
            $meta   = wp_generate_attachment_metadata($img_id, $u['file']);
            wp_update_attachment_metadata($img_id, $meta);
        }
    }

    // downloadable
    $dl_url = '';
    if ( ! empty($_FILES['product_file']['name']) ) {
        $f = wp_handle_upload($_FILES['product_file'], ['test_form'=>false]);
        if ( empty($f['error']) && ! empty($f['url']) ) $dl_url = $f['url'];
    }

    // create product (draft)
    $p = new WC_Product_Simple();
    $p->set_name($name);
    $p->set_description($desc);
    $p->set_regular_price($price);
    $p->set_catalog_visibility('visible');
    $p->set_status( current_user_can('manage_options') ? 'draft' : 'pending' ); /* 16.11: WCFM creators also have manage_woocommerce — only real admins get a draft */
    if ($img_id) $p->set_image_id($img_id);
    if ($dl_url){
        $p->set_downloadable(true); $p->set_virtual(true);
        $d=new WC_Product_Download(); $id=md5($dl_url);
        $d->set_id($id); $d->set_name($name.' Download'); $d->set_file($dl_url);
        $p->set_downloads([$id=>$d]);
    }
    $pid = $p->save();

    if ($pid){
        wp_update_post(['ID'=>$pid,'post_author'=>get_current_user_id()]);
        // save ACF
        if ( ! empty($_POST['acf']) && is_array($_POST['acf']) ) {
            if ( function_exists('acf_save_post') ) acf_save_post($pid);
            else foreach($_POST['acf'] as $k=>$v){ if(function_exists('update_field')) update_field($k,$v,$pid); }
        }
        // sync all taxonomies → pa_* attributes (dynamic)
        ss_sync_pa_attributes($pid);
    }

    /* 16.11: creators go to their product list (wp_get_referer() is empty when the form posts to its own page) */
    $back = ( ! current_user_can('manage_options') && function_exists('get_wcfm_url') ) ? trailingslashit( get_wcfm_url() ) . 'ss-products/' : ( wp_get_referer() ?: home_url('/') );
    wp_safe_redirect( add_query_arg('product_uploaded','1', $back ) );
    exit;
}

/* ──────────────────────────────────────────────────────────────
   🟣 TAX→pa_* ДИНАМІЧНА СИНХРОНІЗАЦІЯ (всі існуючі та майбутні такси)
   ────────────────────────────────────────────────────────────── */

/** Збираємо ВСІ релевантні таксономії продукту (з ACF-групи + фактично прив'язані) */
function ss_get_dynamic_tax_map( $product_id ) : array {
    $map = [];

    // ACF поля типу taxonomy з твоєї групи
    $acf_group_key = 'group_61de909636770';
    if ( function_exists('acf_get_fields') ) {
        $fields = acf_get_fields($acf_group_key);
        if ( is_array($fields) ) {
            foreach ($fields as $f) {
                if ( ($f['type'] ?? '') === 'taxonomy' && ! empty($f['taxonomy']) ) {
                    $tax   = $f['taxonomy'];
                    $label = ( $obj = get_taxonomy($tax) ) ? $obj->labels->name : ucfirst(str_replace(['-','_'],' ',$tax));
                    $map[$tax] = $label;
                }
            }
        }
    }

    // Таксономії, де у продукту вже є терміни
    $exclude = ['product_type','product_visibility','product_cat','product_tag','product_shipping_class'];
    $all = get_object_taxonomies('product');
    foreach ($all as $tax) {
        if ( in_array($tax, $exclude, true) ) continue;
        $terms = wp_get_post_terms($product_id, $tax, ['fields'=>'ids']);
        if ( is_wp_error($terms) || empty($terms) ) continue;

        if ( strpos($tax,'pa_') === 0 && function_exists('wc_attribute_label') ) {
            $map[$tax] = wc_attribute_label($tax);
        } else {
            $obj = get_taxonomy($tax);
            $map[$tax] = $obj ? $obj->labels->name : ucfirst(str_replace(['-','_'],' ',$tax));
        }
    }
    return $map;
}

/** Гарантуємо існування глобального атрибута `pa_*` (slug без `pa_`) */
function ss_ensure_attribute( string $slug, string $label ) : ?array {
    if ( ! function_exists('wc_create_attribute') ) return null;
    $taxonomy = 'pa_' . wc_sanitize_taxonomy_name($slug);
    $attr_id  = wc_attribute_taxonomy_id_by_name($taxonomy);
    if ( ! $attr_id ) {
        $attr_id = wc_create_attribute([
            'name'=>$label,'slug'=>$slug,'type'=>'select','order_by'=>'menu_order','has_archives'=>false,
        ]);
        register_taxonomy($taxonomy, ['product'], ['hierarchical'=>false]);
        delete_transient('wc_attribute_taxonomies');
    }
    return ['id'=>(int)$attr_id, 'taxonomy'=>$taxonomy];
}

/** Синхронізуємо ВСІ знайдені такси → глобальні атрибути pa_* (таксономічні атрибути, не кастомні) */
function ss_sync_pa_attributes( int $product_id ) : void {
    $product = wc_get_product($product_id); if ( ! $product ) return;

    $map = ss_get_dynamic_tax_map($product_id); // slug => label
    $new = [];

    foreach ($map as $slug => $label) {
        // Якщо вже pa_*, отримай базовий slug
        if ( strpos($slug,'pa_') === 0 ) {
            $base_slug = substr($slug, 3);
            $pa_tax = $slug;
            if ( function_exists('wc_attribute_label') ) $label = wc_attribute_label($slug);
        } else {
            $base_slug = $slug;
            $pa_tax = null;
        }

        // Гарантуємо глобальний атрибут
        $ens = ss_ensure_attribute($base_slug, $label);
        if ( empty($ens['taxonomy']) || empty($ens['id']) ) continue;
        if ( ! $pa_tax ) $pa_tax = $ens['taxonomy']; // напр. 'pa_mood'

        // Терміни: спершу з pa_*, інакше з оригінальної такси
        $terms = wp_get_post_terms($product_id, $pa_tax, ['fields'=>'all']);
        if ( empty($terms) ) $terms = wp_get_post_terms($product_id, $slug, ['fields'=>'all']);
        if ( empty($terms) || is_wp_error($terms) ) continue;

        // Гарантуємо такі самі терміни у pa_* і збираємо їх IDs
        $ids = [];
        foreach ($terms as $t) {
            $maybe = term_exists($t->name, $pa_tax);
            if ( ! $maybe ) {
                $created = wp_insert_term($t->name, $pa_tax, ['slug'=>sanitize_title($t->slug)]);
                if ( ! is_wp_error($created) && ! empty($created['term_id']) ) $ids[] = (int)$created['term_id'];
            } else {
                $ids[] = (int)( is_array($maybe) ? $maybe['term_id'] : $maybe );
            }
        }
        if ( empty($ids) ) continue;

        // Прив'язка термінів + формування ТАКСОНОМІЧНОГО атрибуту (ID обов'язково > 0)
        wp_set_object_terms($product_id, $ids, $pa_tax, false);

        $attr = new WC_Product_Attribute();
        $attr->set_id( (int)$ens['id'] );      // ✅ не 0 — це таксономічний атрибут
        $attr->set_name( $pa_tax );            // 'pa_*'
        $attr->set_options( array_map('intval', $ids) );
        $attr->set_visible( true );
        $attr->set_variation( false );

        $new[$pa_tax] = $attr;
    }

    // Злити з існуючими
    $existing = $product->get_attributes();
    foreach ($existing as $k => $v) if ( ! isset($new[$k]) ) $new[$k] = $v;

    $product->set_attributes( array_values($new) );
    $product->save();
}



//////////////////////////////

/* ═══ Solid Sound: trust block under the Buy button (digital products) ═══ */
if ( ! function_exists( 'ss_trust_block' ) ) {
	function ss_trust_block() {
		global $product;
		if ( ! $product || ! is_object( $product ) ) return;
		if ( ! $product->is_downloadable() && ! $product->is_virtual() ) return;
		echo '<ul class="ss-trust">'
			. '<li>Instant download after payment</li>'
			/* royalty-free / license: shown once, in the green license box (modules/license.php) */
			. '<li>Secure payment: card &amp; PayPal</li>'
			. '<li><a href="' . esc_url( home_url( '/refund_returns/' ) ) . '">Refund policy</a> &middot; <a href="' . esc_url( home_url( '/contacts/' ) ) . '">Questions?</a></li>'
			. '</ul>';
	}
	add_action( 'woocommerce_after_add_to_cart_form', 'ss_trust_block' );
}

/* ═══ Solid Sound: cookie consent (EU) — Meta Pixel only after "Accept" ═══ */
if ( ! function_exists( 'ss_consent_head' ) ) {
	function ss_consent_head() {
		if ( is_admin() ) return;
		?>
<script>
/* Load Meta Pixel base first, then revoke consent until the visitor accepts. Plugin snippets reuse this fbq. */
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
(function(){var c=(document.cookie.match(/(?:^|;\s*)ss_consent=(yes|no)/)||[])[1];window.ssConsent=c||'';if(c!=='yes')fbq('consent','revoke');})();
</script>
<style>.l-cookie{display:none!important}
.ss-cc{position:fixed;left:16px;right:16px;bottom:16px;z-index:99999;max-width:560px;margin:0 auto;background:#16141f;color:#dfe3ee;border:1px solid rgba(255,255,255,.12);border-radius:14px;box-shadow:0 12px 40px rgba(0,0,0,.55);padding:18px 18px 16px;font-size:14px;line-height:1.5}
.ss-cc[hidden]{display:none}
.ss-cc b{display:block;color:#fff;font-size:15px;margin-bottom:6px}
.ss-cc p{margin:0 0 14px}
.ss-cc a{color:#f5a400!important;text-decoration:underline}
.ss-cc-row{display:flex;gap:10px;flex-wrap:wrap}
.ss-cc button{flex:1 1 160px;margin:0;padding:11px 16px;border-radius:10px;font:inherit;font-weight:700;cursor:pointer;border:1.5px solid #f5a400}
.ss-cc .ss-cc-yes{background:#f5a400;color:#111}
.ss-cc .ss-cc-no{background:transparent;color:#f5a400}
body.ssp-open .ss-cc{bottom:88px}
.ss-cc-open{cursor:pointer;color:inherit!important;opacity:.7;text-decoration:underline;font-size:.9em}
/* (13.11.2026, QA bot: the banner covered BUY on phones) compact on phones, above the bottom menu bar */
.ss-cc{z-index:2147483646}@media(max-width:600px){.ss-cc{left:8px;right:8px;bottom:76px;padding:10px 12px;font-size:12.5px;line-height:1.4;border-radius:12px}.ss-cc b{display:none}.ss-cc p{margin:0 0 8px}.ss-cc-row{flex-wrap:nowrap;gap:8px}.ss-cc button{flex:1 1 0;padding:8px 10px;font-size:13px}body.ssp-open .ss-cc{bottom:140px}body:has(.ss-cc:not([hidden])) .ssb-fab{visibility:hidden}}</style>
		<?php
	}
	add_action( 'wp_head', 'ss_consent_head', -1000 );

	function ss_consent_footer() {
		if ( is_admin() ) return;
		$pp = get_privacy_policy_url();
		?>
<div class="ss-cc" id="ss-cc" role="dialog" aria-live="polite" aria-label="Cookie consent" hidden>
<b>We value your privacy</b>
<p>We use essential cookies to run this site (cart, login, checkout). With your permission we also use marketing cookies (Meta Pixel) to measure and improve our ads.<?php if ( $pp ) : ?> <a href="<?php echo esc_url( $pp ); ?>">Privacy Policy</a><?php endif; ?></p>
<div class="ss-cc-row"><button type="button" class="ss-cc-no">Reject non-essential</button><button type="button" class="ss-cc-yes">Accept all</button></div>
</div>
<script>
(function(){
var box=document.getElementById('ss-cc');if(!box)return;
function set(v){var d=new Date();d.setTime(d.getTime()+180*864e5);document.cookie='ss_consent='+v+';expires='+d.toUTCString()+';path=/;SameSite=Lax'+(location.protocol==='https:'?';Secure':'');window.ssConsent=v}
function hide(){box.hidden=true}
box.querySelector('.ss-cc-yes').addEventListener('click',function(){var was=window.ssConsent;set('yes');hide();if(window.fbq&&was!=='yes'){fbq('consent','grant');fbq('track','PageView')}});
box.querySelector('.ss-cc-no').addEventListener('click',function(){set('no');hide();if(window.fbq)fbq('consent','revoke')});
if(!window.ssConsent)box.hidden=false;
/* "Cookie settings" link in the footer to change the choice later */
var f=document.querySelector('.l-footer');if(f&&!document.querySelector('.ss-cc-open')){var ps=f.querySelectorAll('p');if(!ps.length)ps=[f];[].forEach.call(ps,function(p){var a=document.createElement('a');a.className='ss-cc-open';a.textContent='Cookie settings';a.setAttribute('role','button');a.tabIndex=0;a.addEventListener('click',function(){box.hidden=false});p.appendChild(document.createTextNode(' · '));p.appendChild(a)})}
})();
</script>
		<?php
	}
	add_action( 'wp_footer', 'ss_consent_footer', 5 );
}

/* Solid Sound: keep the static front page when grid filter params are in the URL (/?filter_...) */
if ( ! function_exists( 'ss_front_filter_request' ) ) {
	function ss_front_filter_request( $qv ) {
		if ( is_admin() || wp_doing_ajax() ) return $qv;
		$path = trim( (string) parse_url( isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '', PHP_URL_PATH ), '/' );
		if ( $path !== '' ) return $qv;
		if ( get_option( 'show_on_front' ) !== 'page' ) return $qv;
		$front = (int) get_option( 'page_on_front' );
		if ( ! $front ) return $qv;
		$has = false;
		foreach ( array_keys( $_GET ) as $k ) { if ( strpos( (string) $k, 'filter_' ) === 0 ) { $has = true; break; } }
		if ( ! $has || isset( $_GET['s'] ) ) return $qv;
		return array( 'page_id' => $front );
	}
	add_filter( 'request', 'ss_front_filter_request', 1 );
}


/* =====================================================================
 * Solid Sound — 12.10.2026
 * 1) Mobile filters fix (sticky toolbar .sstb), 2) Best Products: always
 * open "All", 3) Best Products on narrow phones, 4) Sign-in modal with
 * 3 tabs + registration fields, 5) /sell/ page for producers [ss_sell].
 * ===================================================================== */
if ( ! function_exists( 'ss_auth_user_types' ) ) :

function ss_auth_user_types() {
	return array(
		'producer' => 'Producer / beatmaker',
		'dj'       => 'DJ',
		'learning' => 'Learning music production',
		'creator'  => 'Video / content creator',
		'artist'   => 'Artist who needs mixing & mastering',
	);
}
function ss_auth_terms_url() {
	$p = get_page_by_path( 'license' );
	return $p ? get_permalink( $p ) : home_url( '/license/' );
}
function ss_auth_privacy_url() {
	$u = get_privacy_policy_url();
	return $u ? $u : home_url( '/privacy-policy/' );
}
function ss_auth_icon( $k ) {
	$p = array(
		'login' => '<rect x="4.5" y="10.5" width="15" height="10" rx="2.5"/><path d="M8 10.5V7.5a4 4 0 0 1 8 0v3"/><circle class="acc" cx="12" cy="15.5" r="1.3"/>',
		'reg'   => '<circle cx="10" cy="8" r="3.6"/><path d="M3.5 20v-.8A5.7 5.7 0 0 1 9.2 13.5h1.6c1 0 1.9.25 2.7.7"/><path class="acc" d="M18 14v6M15 17h6"/>',
		'sell'  => '<circle cx="10" cy="8" r="3.6"/><path d="M3.5 20v-.8A5.7 5.7 0 0 1 9.2 13.5h1.6c1 0 1.9.25 2.7.7"/><path class="acc" d="M18 21v-6.5M15.4 17 18 14.4l2.6 2.6"/>',
	);
	return '<span class="ssam-ico"><svg viewBox="0 0 24 24" aria-hidden="true">' . $p[ $k ] . '</svg></span>';
}

/* ---------- CSS (+ Best Products: never remember the last tab) ---------- */
add_action( 'wp_head', 'ss_2610_head', 120 );
function ss_2610_head() {
	if ( is_admin() ) return;
	?>
<script>try{localStorage.removeItem('ss_top_tab')}catch(e){}</script>
<style id="ss-2610">
/* mobile filters: a backdrop-filter on the sticky toolbar traps the fixed filter popup inside it */
@media (max-width:1024px){.sstb{-webkit-backdrop-filter:none!important;backdrop-filter:none!important;background:#181614!important}}
.sstb.ss-fopen,.sstb:has(.w-filter.open){z-index:100000!important;-webkit-backdrop-filter:none!important;backdrop-filter:none!important;transform:none!important}
body.ss-fopen #tidio-chat{display:none!important}
/* Best Products on phones */
body .vc_col-sm-3 .ss-top-sep{display:none}
@media (max-width:767px){
 body .vc_col-sm-3 .ss-top{margin-left:21px!important;margin-right:21px!important;max-width:calc(100vw - 42px);box-sizing:border-box}
}
@media (max-width:420px){
 body .vc_col-sm-3 .ss-top{padding:12px 10px 2px!important}
 body .vc_col-sm-3 .ss-top-tabs{gap:2px!important}
 body .vc_col-sm-3 .ss-top-tab{padding:6px 3px!important;font-size:11.5px!important}
 body .vc_col-sm-3 .ss-top-i{gap:8px!important;padding:8px 0!important}
 body .vc_col-sm-3 .ss-top-c,body .vc_col-sm-3 .ss-top-c img,body .vc_col-sm-3 .ss-top-play{width:44px!important;height:44px!important;flex:0 0 44px!important}
 body .vc_col-sm-3 .ss-top-t{font-size:13.5px!important}
 body .vc_col-sm-3 .ss-top-s{font-size:11px!important}
}
@media (max-width:340px){body .vc_col-sm-3 .ss-top{margin-left:12px!important;margin-right:12px!important;max-width:calc(100vw - 24px)}}

/* ---------- Sign-in modal: 3 tabs ---------- */
.w-popup-box:has(.ssam){background:transparent!important;box-shadow:none!important;padding:0!important}
body .w-popup-box-content:has(.ssam){padding:0!important;background:transparent!important;box-shadow:none!important}
body .w-popup-box:has(.ssam){max-width:576px!important}
.w-popup-box-content:has(.ssam)>p:empty{display:none}
.ssam{--or:#e09900;--or2:#f5ad12;--mut:#9b9690;--line:rgba(255,255,255,.09);position:relative;width:100%;max-width:560px;margin:0 auto;background:#1c1a18;border:1px solid var(--line);border-radius:16px;box-shadow:0 30px 80px rgba(0,0,0,.6);overflow:hidden;color:#ececec;text-align:left;box-sizing:border-box}
.ssam *{box-sizing:border-box}
.ssam-tabs{display:grid;grid-template-columns:repeat(3,1fr);gap:4px;padding:6px;background:rgba(255,255,255,.04);border-bottom:1px solid var(--line)}
.ssam .ssam-tab{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;min-width:0;margin:0!important;padding:12px 6px 11px!important;border:1px solid transparent!important;border-radius:12px!important;background:none!important;box-shadow:none!important;color:var(--mut)!important;font:700 14px/1.2 Jura,Roboto,sans-serif!important;letter-spacing:.02em!important;text-transform:none!important;cursor:pointer;transition:background .2s,color .2s,border-color .2s}
.ssam-ico{display:flex;align-items:center;justify-content:center;width:42px;height:42px;border-radius:12px;background:linear-gradient(180deg,rgba(255,255,255,.09),rgba(255,255,255,.03));box-shadow:inset 0 0 0 1px rgba(255,255,255,.08);transition:.2s}
.ssam-ico svg{width:22px;height:22px;stroke:currentColor;fill:none;stroke-width:1.7;stroke-linecap:round;stroke-linejoin:round}
.ssam-ico svg .acc{stroke:var(--or)}
.ssam .ssam-tab:hover{color:#fff!important;background:rgba(255,255,255,.04)!important}
.ssam .ssam-tab:hover .ssam-ico{box-shadow:inset 0 0 0 1px rgba(224,153,0,.45)}
.ssam .ssam-tab[aria-selected=true]{color:#fff!important;background:rgba(224,153,0,.10)!important;border-color:rgba(224,153,0,.55)!important}
.ssam .ssam-tab[aria-selected=true] .ssam-ico{background:linear-gradient(180deg,#f5ad12,#d98f00);color:#151311;box-shadow:0 6px 18px rgba(224,153,0,.35)}
.ssam .ssam-tab[aria-selected=true] .ssam-ico svg .acc{stroke:#151311}
.ss-pw{position:relative!important;display:block!important;width:100%}
.ssam .ss-pw input,.sssell-form .ss-pw input{padding-right:46px!important}
.ss-pw .ss-eye{position:absolute;right:6px;top:50%;transform:translateY(-50%);display:flex!important;align-items:center;justify-content:center;width:34px!important;height:32px!important;min-height:0!important;margin:0!important;padding:0!important;border:0!important;border-radius:7px!important;background:none!important;box-shadow:none!important;color:#9b9690!important;cursor:pointer}
.ss-pw .ss-eye:hover{color:#fff!important;background:rgba(255,255,255,.06)!important}
.ss-pw .ss-eye.on{color:#e09900!important}
.ss-eye svg{width:18px;height:18px;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.ss-pwmsg{display:block;min-height:18px;margin:-4px 0 8px;font:500 12px/1.4 Roboto,sans-serif}
.ss-pwmsg.ok{color:#3fd786}.ss-pwmsg.bad{color:#f87171}
.ssam-body{padding:24px 26px 26px}
.ssam #customer_login{display:block!important;width:auto!important;margin:0!important;padding:0!important}
.ssam #customer_login:before,.ssam #customer_login:after{display:none!important}
.ssam #customer_login>.u-column1,.ssam #customer_login>.u-column2{float:none!important;width:100%!important;max-width:none!important;margin:0!important;padding:0!important;border:0!important}
.ssam #customer_login>div>h2{display:none!important}
.ssam[data-t=login] #customer_login>.u-column2,.ssam[data-t=reg] #customer_login>.u-column1,.ssam[data-t=sell] #customer_login{display:none!important}
.ssam-sell{display:none}.ssam[data-t=sell] .ssam-sell{display:block}
.ssam form.login,.ssam form.register{margin:0!important;padding:0!important;border:0!important;background:none!important;box-shadow:none!important}
.ssam .ssam-h{margin:0 0 4px!important;padding:0!important;font:700 22px/1.25 Jura,Roboto,sans-serif!important;color:#fff!important;text-transform:none!important;letter-spacing:0!important}
.ssam .ssam-sub{margin:0 0 18px!important;color:var(--mut)!important;font-size:14px!important;line-height:1.5!important}
.ssam .form-row{display:block!important;width:auto!important;float:none!important;margin:0 0 12px!important;padding:0!important}
.ssam label{display:block;margin:0 0 6px!important;font:600 13px/1.4 Roboto,sans-serif!important;color:#cfcac4!important;text-transform:none!important;letter-spacing:0!important}
.ssam .required{color:var(--or)!important;border:0!important;text-decoration:none!important}
.ssam input.input-text,.ssam input[type=text],.ssam input[type=email],.ssam input[type=password],.ssam select{display:block;width:100%!important;height:auto!important;min-height:44px;margin:0!important;padding:11px 12px!important;border:1px solid rgba(255,255,255,.12)!important;border-radius:9px!important;background:#151311!important;color:#fff!important;font:15px/1.3 Roboto,sans-serif!important;box-shadow:none!important}
.ssam select{-webkit-appearance:auto;appearance:auto}
.ssam input:focus,.ssam select:focus{outline:none!important;border-color:var(--or)!important;box-shadow:0 0 0 3px rgba(224,153,0,.18)!important}
.ssam .password-input{display:block;position:relative;width:100%}
.ssam .show-password-input{display:none!important}
@media (max-width:600px){body .w-popup-box-content:has(.ssam){padding:0 8px!important}}
.ssam-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.ssam .ssam-chk,.ssam .woocommerce-form-login__rememberme{display:flex!important;align-items:flex-start;gap:9px;margin:4px 0 10px!important;font:400 13px/1.45 Roboto,sans-serif!important;color:#cfcac4!important;cursor:pointer}
.ssam .ssam-chk input,.ssam .woocommerce-form-login__rememberme input{flex:0 0 16px;width:16px;height:16px;margin:1px 0 0!important;accent-color:var(--or)}
.ssam .ssam-chk a{color:var(--or)!important}
.ssam button[type=submit],.ssam .ssam-btn{display:flex!important;align-items:center;justify-content:center;gap:8px;width:100%!important;float:none!important;margin:8px 0 0!important;padding:13px 18px!important;border:0!important;border-radius:10px!important;background:var(--or)!important;color:#111!important;font:800 15px/1.2 Roboto,sans-serif!important;letter-spacing:.03em!important;text-transform:uppercase!important;text-decoration:none!important;cursor:pointer}
.ssam button[type=submit]:hover,.ssam .ssam-btn:hover{background:var(--or2)!important}
.ssam .woocommerce-LostPassword,.ssam .wcmp_become_vendor_link,.ssam .wcfmmp_become_vendor_link{display:none!important}
body:has(.w-popup-overlay.active) #tidio-chat{display:none!important}
.ssam-foot{display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px 14px;margin-top:12px;font-size:13px;line-height:1.4}
.ssam-foot a{color:var(--or)!important;text-decoration:none}
.ssam-foot a:hover{text-decoration:underline}
.ssam .woocommerce-error,.ssam .woocommerce-message,.ssam .woocommerce-info{margin:0 0 16px!important;padding:12px 14px!important;border-radius:10px;font-size:14px;list-style:none}
.ssam-perks{list-style:none!important;display:grid;gap:10px;margin:0 0 18px!important;padding:0!important}
.ssam-perks li{display:flex;align-items:flex-start;gap:10px;margin:0!important;padding:10px 12px!important;border-radius:10px;background:rgba(255,255,255,.04);font-size:14px;line-height:1.45;color:#cfcac4}
.ssam-perks li:before{display:none!important}
.ssam-perks b{color:#fff}
.ssam-perks i{display:flex;align-items:center;justify-content:center;flex:0 0 26px;height:26px;border-radius:50%;background:rgba(224,153,0,.15);color:var(--or);font-style:normal;font-weight:800;font-size:13px}
@media (max-width:560px){.ssam-row{grid-template-columns:1fr;gap:0}.ssam-body{padding:20px 16px}.ssam .ssam-tab{font-size:12.5px!important;padding:10px 4px!important}.ssam-ico{width:38px;height:38px}}
</style>
	<?php
}

/* ---------- small JS: modal tabs, popup forms, filters fallback, /sell/ chips ---------- */
add_action( 'wp_footer', 'ss_2610_footer', 120 );
function ss_2610_footer() {
	if ( is_admin() ) return;
	$acc = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
	/* page data stays inline (tiny); the code below is static, so the build serves it as a cached file */
	echo '<script>window.SS_FT=' . wp_json_encode( array( 'acc' => $acc, 'terms' => home_url( '/sell/#terms' ), 'priv' => ss_auth_privacy_url() ) ) . ';</script>';
	?>
<script id="ss-foot-js">
(function(){
'use strict';
function go(box,t){
  box.setAttribute('data-t',t);
  box.querySelectorAll('.ssam-tab').forEach(function(b){b.setAttribute('aria-selected',b.getAttribute('data-t')===t?'true':'false')});
}
document.addEventListener('click',function(e){
  var b=e.target.closest&&e.target.closest('.ssam .ssam-tab,.ssam [data-go]');
  if(b){var box=b.closest('.ssam');e.preventDefault();go(box,b.getAttribute('data-t')||b.getAttribute('data-go'));return}
  /* filters: keep the toolbar above everything while the mobile filter window is open */
  setTimeout(function(){var open=false;document.querySelectorAll('.sstb').forEach(function(t){var o=!!t.querySelector('.w-filter.open');t.classList.toggle('ss-fopen',o);if(o)open=true});document.body.classList.toggle('ss-fopen',open)},60);
},true);
function ssEye(i){if(i.dataset.eye)return;i.dataset.eye=1;var w=i.parentNode;if(!(w.classList&&w.classList.contains('password-input'))){w=document.createElement('span');i.parentNode.insertBefore(w,i);w.appendChild(i)}w.classList.add('ss-pw');var b=document.createElement('button');b.type='button';b.className='ss-eye';b.setAttribute('aria-label','Show password');b.innerHTML='<svg viewBox="0 0 24 24"><path d="M2 12s3.6-6.5 10-6.5S22 12 22 12s-3.6 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>';b.addEventListener('click',function(e){e.preventDefault();e.stopPropagation();var s=i.type==='password';i.type=s?'text':'password';b.classList.toggle('on',s);b.setAttribute('aria-label',s?'Hide password':'Show password')});w.appendChild(b)}
function ssMatch(p1,p2){if(!p1||!p2||p2.dataset.pm)return;p2.dataset.pm=1;var m=document.createElement('span');m.className='ss-pwmsg';m.setAttribute('aria-live','polite');var an=p2.closest('.ssam-row')||p2.closest('.form-row')||p2.parentNode;an.parentNode.insertBefore(m,an.nextSibling);function u(){if(!p2.value){m.textContent='';m.className='ss-pwmsg';return}var ok=p1.value===p2.value&&p1.value.length>=8;m.textContent=p1.value!==p2.value?'Passwords do not match':(p1.value.length<8?'At least 8 characters':'\u2713 Passwords match');m.className='ss-pwmsg '+(ok?'ok':'bad')}p1.addEventListener('input',u);p2.addEventListener('input',u)}
function initAuth(){
  /* forms inside the header popup post to My Account, so errors are shown on a full page */
  var acc=(window.SS_FT||{}).acc;
  document.querySelectorAll('.w-popup .ssam form.login,.w-popup .ssam form.register,.w-popup-box .ssam form.login,.w-popup-box .ssam form.register').forEach(function(f){f.setAttribute('action',acc)});
  document.querySelectorAll('.ssam form.register button[type=submit]').forEach(function(b){b.textContent='Create account'});
  document.querySelectorAll('.ssam #reg_password').forEach(function(i){i.setAttribute('placeholder','8+ characters');i.setAttribute('minlength','8')});
  document.querySelectorAll('.ssam #username').forEach(function(i){var l=i.parentNode.querySelector('label');if(l&&/username/i.test(l.firstChild&&l.firstChild.nodeValue||''))l.firstChild.nodeValue='Email\u00a0';i.setAttribute('placeholder','you@example.com')});
  document.querySelectorAll('.ssam input[type=password]').forEach(ssEye);
  document.querySelectorAll('.ssam label[for=reg_email]').forEach(function(l){if(l.firstChild&&l.firstChild.nodeType===3)l.firstChild.nodeValue='Email\u00a0'});
  document.querySelectorAll('.ssam form.register').forEach(function(f){var a=f.querySelector('#reg_password'),b=f.querySelector('#reg_password2');if(!a||!b)return;var ra=a.closest('.form-row'),rb=b.closest('.form-row');if(ra&&rb&&!ra.parentNode.classList.contains('ssam-row')){var row=document.createElement('div');row.className='ssam-row';ra.parentNode.insertBefore(row,ra);row.appendChild(ra);row.appendChild(rb)}ssMatch(a,b)});
}
/* /sell/: "What do you make?" chips write into the WCFM text field */
function initChips(){
  var root=document.querySelector('.sssell-form');if(!root)return;
  var opts=['Ableton templates','Other DAW templates','Synth presets','Sample packs','MIDI','Music / stems'];
  root.querySelectorAll('p.wcfm_title, .wcfm_title').forEach(function(t){
    if(!/what do you make/i.test(t.textContent))return;
    var inp=t.nextElementSibling;while(inp&&!(inp.matches&&inp.matches('input[type=text]')))inp=inp.nextElementSibling;
    if(!inp||inp.dataset.chips)return;inp.dataset.chips=1;inp.classList.add('sssell-hidden');
    var wrap=document.createElement('div');wrap.className='sssell-chips';
    var cur=(inp.value||'').split(/\s*,\s*/);
    opts.forEach(function(o){var l=document.createElement('label'),c=document.createElement('input');c.type='checkbox';c.value=o;c.checked=cur.indexOf(o)>-1;l.appendChild(c);l.appendChild(document.createTextNode(o));wrap.appendChild(l)});
    wrap.addEventListener('change',function(){inp.value=[].slice.call(wrap.querySelectorAll('input:checked')).map(function(c){return c.value}).join(', ');inp.dispatchEvent(new Event('change',{bubbles:true}))});
    inp.parentNode.insertBefore(wrap,inp);
  });
}
/* /sell/: tidy the WCFM registration form */
function esc(s){return String(s).replace(/[&<>"]/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]})}
function initWcfm(){
  var f=document.querySelector('.sssell-form #wcfm_membership_registration_form');if(!f||f.dataset.ss)return;f.dataset.ss=1;
  var TERMS=(window.SS_FT||{}).terms,PRIV=(window.SS_FT||{}).priv;
  function title(inp){var t=(inp.closest('.ss-pw')||inp).previousElementSibling;while(t&&!(t.classList&&t.classList.contains('wcfm_title')))t=t.previousElementSibling;return t}
  [['first_name','First name','given-name'],['last_name','Last name','family-name']].forEach(function(a){var i=f.querySelector('[name="'+a[0]+'"]');if(!i)return;i.setAttribute('data-required','1');i.setAttribute('data-required_message',a[1]+': This field is required.');i.setAttribute('autocomplete',a[2]);var t=title(i);if(t)t.innerHTML='<strong>'+a[1]+'</strong><span class="required">*</span>'});
  var sn=f.querySelector('[name="store_name"]');if(sn){var st=title(sn),s=st&&st.querySelector('strong');if(s&&s.firstChild&&s.firstChild.nodeType===3)s.firstChild.nodeValue='Artist name';else if(st)st.insertAdjacentHTML('afterbegin','');var p1=f.querySelector('[name=passoword]'),p2=f.querySelector('[name=confirm_pwd]');if(p2){var ct=title(p2),cs=ct&&ct.querySelector('strong');if(cs&&cs.firstChild&&cs.firstChild.nodeType===3)cs.firstChild.nodeValue='Confirm password'}if(p1)p1.setAttribute('placeholder','8+ characters');if(p2)p2.setAttribute('placeholder','Repeat password');f.querySelectorAll('input[type=password]').forEach(ssEye);ssMatch(p1,p2);var sb=f.querySelector('input[type=submit]');if(sb)sb.value='Send application';var vb=f.querySelector('[name=wcfm_email_verified_button]');if(vb&&/re-?send/i.test(vb.value))vb.value='Send code';sn.setAttribute('placeholder','e.g. Grenør')}
  var ph={'link-to-your-work':'SoundCloud, YouTube, Spotify, Beatport, your site…','second-link-optional':'Another link (optional)','anything-we-should-know':'What you produce, who it’s for, what you want to sell first.'};
  /* (15.11.2026, like WeMake) one "Links to your work" block, optional, up to two links */
  (function(){var l1=f.querySelector('[name="wcfmvm_custom_infos[link-to-your-work]"]'),l2=f.querySelector('[name="wcfmvm_custom_infos[second-link-optional]"]');
    if(l1){var t1=title(l1);if(t1)t1.innerHTML='<strong>Links to your work</strong> <small style="font-weight:400;color:#94A3B8">optional · SoundCloud, YouTube, Spotify, your site — up to two</small>';}
    if(l2){var t2=title(l2);if(t2)t2.style.setProperty('display','none','important');l2.style.marginTop='8px';}
    var nt=f.querySelector('[name="wcfmvm_custom_infos[anything-we-should-know]"]');if(nt){nt.rows=3;var t3=title(nt);if(t3)t3.innerHTML='<strong>Anything we should know?</strong> <small style="font-weight:400;color:#94A3B8">optional</small>';}
  })();
  Object.keys(ph).forEach(function(k){var i=f.querySelector('[name="wcfmvm_custom_infos['+k+']"]');if(i)i.setAttribute('placeholder',ph[k])});
  f.querySelectorAll('.wcfm_title .img_tip').forEach(function(tip){var t=tip.closest('.wcfm_title'),txt=tip.getAttribute('data-tip')||'';tip.parentNode.removeChild(tip);if(t&&txt&&t.classList.contains('checkbox-title'))t.setAttribute('data-sub',txt)});
  f.querySelectorAll('input[type=checkbox].wcfm-checkbox').forEach(function(c){
    if(c.closest('.sssell-chk'))return;
    var lab=document.createElement('label');lab.className='sssell-chk';
    if(c.id==='terms'){
      var tt=c.nextElementSibling;while(tt&&!(tt.classList&&tt.classList.contains('terms_title')))tt=tt.nextElementSibling;if(tt)tt.style.display='none';
      c.parentNode.insertBefore(lab,c);lab.appendChild(c);
      lab.insertAdjacentHTML('beforeend','<span>I agree to the <a href="'+esc(TERMS)+'" target="_blank">Creator Terms</a> and <a href="'+esc(PRIV)+'" target="_blank" rel="noopener">Privacy Policy</a>&nbsp;<span class="required">*</span></span>');
    }else{
      var t=title(c),text=t?t.textContent.trim():'',sub=t&&t.getAttribute('data-sub');if(t)t.style.setProperty('display','none','important'); /* theme CSS forces .wcfm_title visible -> it showed twice */
      c.parentNode.insertBefore(lab,c);lab.appendChild(c);
      lab.insertAdjacentHTML('beforeend','<span>'+esc(text)+(sub?'<small>'+esc(sub)+'</small>':'')+'</span>');
    }
  });
  /* (15.11.2026, like WeMake) compact: First | Last | Email in one row, links and passwords side by side */
  var em=f.querySelector('[name=user_email]');if(em){em.type='email';em.setAttribute('autocomplete','email');em.setAttribute('placeholder','you@example.com');em.setAttribute('inputmode','email')}
  var fn=f.querySelector('[name=first_name]'),ln=f.querySelector('[name=last_name]');if(fn)fn.setAttribute('placeholder','First name');if(ln)ln.setAttribute('placeholder','Last name');
  var nt2=f.querySelector('[name="wcfmvm_custom_infos[anything-we-should-know]"]');if(nt2)nt2.rows=2;
  function isBound(n){return n.matches&&(n.matches('p.wcfm_title,label.sssell-chk,.sssell-row,.sssell-cell'))}
  function cell(inp){var t=title(inp);if(!t)return null;var c=document.createElement('div');c.className='sssell-cell';t.parentNode.insertBefore(c,t);var n=t;do{var nx=n.nextSibling;c.appendChild(n);n=nx}while(n&&!(n.nodeType===1&&isBound(n)));return c}
  function row(names,cls){var ins=names.map(function(nm){return f.querySelector('[name="'+nm+'"]')}).filter(Boolean);if(ins.length<2)return;var r=document.createElement('div');r.className='sssell-row '+cls;var first=title(ins[0]);if(!first)return;first.parentNode.insertBefore(r,first);ins.forEach(function(i){var c=cell(i);if(c)r.appendChild(c)})}
  /* names first: move First/Last before Email, then group */
  if(em&&fn&&ln){var cf=cell(fn),cl=cell(ln),ce=cell(em);if(cf&&cl&&ce){var r=document.createElement('div');r.className='sssell-row sssell-r3';(ce.parentNode).insertBefore(r,ce);r.appendChild(cf);r.appendChild(cl);r.appendChild(ce)}}
  row(['wcfmvm_custom_infos[link-to-your-work]','wcfmvm_custom_infos[second-link-optional]'],'sssell-r2 sssell-end');
  row(['passoword','confirm_pwd'],'sssell-r2');
}
function init(){initAuth();initChips();initWcfm()}
if(document.readyState!=='loading')init();else document.addEventListener('DOMContentLoaded',init);
})();
</script>
	<?php
}

/* ---------- Sign-in modal markup (wraps WooCommerce login/register) ---------- */
add_action( 'woocommerce_before_customer_login_form', 'ss_auth_open', 5 );
function ss_auth_open() {
	$GLOBALS['ss_in_auth'] = true;
	$t = 'login';
	if ( ! empty( $_POST['register'] ) ) $t = 'reg';
	elseif ( isset( $_GET['ss_tab'] ) && in_array( $_GET['ss_tab'], array( 'login', 'reg', 'sell' ), true ) ) $t = sanitize_key( $_GET['ss_tab'] );
	$tabs = array(
		'login' => array( 'Log in', 'I have an account' ),
		'reg'   => array( 'Create account', 'To buy & download' ),
		'sell'  => array( 'Become a vendor', 'For producers' ),
	);
	echo '<div class="ssam" data-t="' . esc_attr( $t ) . '"><div class="ssam-tabs" role="tablist">';
	foreach ( $tabs as $k => $v ) {
		echo '<button type="button" class="ssam-tab" role="tab" data-t="' . esc_attr( $k ) . '" aria-selected="' . ( $k === $t ? 'true' : 'false' ) . '">' . ss_auth_icon( $k ) . '<span class="ssam-lbl">' . esc_html( $v[0] ) . '</span></button>';
	}
	echo '</div><div class="ssam-body">';
}
add_action( 'woocommerce_after_customer_login_form', 'ss_auth_close', 50 );
function ss_auth_close() {
	if ( empty( $GLOBALS['ss_in_auth'] ) ) return;
	$GLOBALS['ss_in_auth'] = false;
	$sell = home_url( '/sell/' );
	?>
	<div class="ssam-sell" role="tabpanel">
		<h3 class="ssam-h">Sell on Solid Sound</h3>
		<p class="ssam-sub">Ableton templates, presets, sample packs, MIDI, music.</p>
		<ul class="ssam-perks">
			<li><i>%</i><span><b>Keep up to 85%</b> &mdash; Founding Creators, first 20 producers</span></li>
			<li><i>&#9733;</i><span><b>Featured from day one</b> &mdash; homepage &amp; our socials</span></li>
			<li><i>&#9889;</i><span><b>Approved in 24 h</b> &middot; no fees &middot; non-exclusive</span></li>
		</ul>
		<?php /* (owner 15.11) no Google sign-up for creators: we need store name, links and the creator terms from the form */ ?>
		<a class="ssam-btn" href="<?php echo esc_url( $sell ); ?>">Become a vendor &rarr;</a>
		<div class="ssam-foot"><a href="<?php echo esc_url( $sell ); ?>">See all benefits</a><a href="#" data-go="login">Already a vendor? Log in</a></div>
	</div>
	</div></div>
	<?php
}
add_action( 'woocommerce_login_form_start', function () {
	if ( empty( $GLOBALS['ss_in_auth'] ) ) return;
	echo '<h3 class="ssam-h">Welcome back</h3><p class="ssam-sub">Log in to your downloads, orders and favourites.</p>';
} );
add_action( 'woocommerce_login_form_end', function () {
	if ( empty( $GLOBALS['ss_in_auth'] ) ) return;
	echo '<div class="ssam-foot"><a href="' . esc_url( wp_lostpassword_url() ) . '">Forgot password?</a><a href="#" data-go="reg">New here? Create account</a></div>';
} );

add_action( 'woocommerce_register_form_end', function () {
	if ( empty( $GLOBALS['ss_in_auth'] ) ) return;
	echo '<div class="ssam-foot"><span></span><a href="#" data-go="login">Already have an account? Log in</a></div>';
}, 50 );

/* ---------- Registration: password field + extra fields ---------- */
add_filter( 'pre_option_woocommerce_registration_generate_password', function () { return 'no'; } );
add_action( 'init', function () {
	remove_action( 'woocommerce_register_form', 'wc_registration_privacy_policy_text', 20 );
}, 20 );

add_action( 'woocommerce_register_form_start', 'ss_reg_fields_top' );
function ss_reg_fields_top() {
	if ( ! empty( $GLOBALS['ss_in_auth'] ) ) echo '<h3 class="ssam-h">Create your account</h3><p class="ssam-sub">Free. Instant access to your purchases and free packs.</p>';
	$f = isset( $_POST['ss_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['ss_first_name'] ) ) : '';
	$l = isset( $_POST['ss_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['ss_last_name'] ) ) : '';
	?>
	<div class="ssam-row">
		<p class="woocommerce-form-row form-row"><label for="ss_first_name">First name&nbsp;<span class="required">*</span></label><input type="text" class="woocommerce-Input input-text" name="ss_first_name" id="ss_first_name" autocomplete="given-name" value="<?php echo esc_attr( $f ); ?>" required></p>
		<p class="woocommerce-form-row form-row"><label for="ss_last_name">Last name&nbsp;<span class="required">*</span></label><input type="text" class="woocommerce-Input input-text" name="ss_last_name" id="ss_last_name" autocomplete="family-name" value="<?php echo esc_attr( $l ); ?>" required></p>
	</div>
	<?php
}
add_action( 'woocommerce_register_form', 'ss_reg_fields_bottom', 5 );
function ss_reg_fields_bottom() {
	$type = isset( $_POST['ss_user_type'] ) ? sanitize_key( wp_unslash( $_POST['ss_user_type'] ) ) : '';
	echo '<p class="woocommerce-form-row form-row"><label for="ss_user_type">I am a&hellip;</label><select name="ss_user_type" id="ss_user_type"><option value="">Choose (optional)</option>';
	foreach ( ss_auth_user_types() as $k => $v ) echo '<option value="' . esc_attr( $k ) . '"' . selected( $type, $k, false ) . '>' . esc_html( $v ) . '</option>';
	echo '</select></p>';
	echo '<label class="ssam-chk"><input type="checkbox" name="ss_newsletter" value="1"' . checked( ! empty( $_POST['ss_newsletter'] ), true, false ) . '><span>Send me free packs, new releases and discounts (max 2 emails / month). Unsubscribe anytime.</span></label>';
	echo '<label class="ssam-chk"><input type="checkbox" name="ss_terms" value="1" required' . checked( ! empty( $_POST['ss_terms'] ), true, false ) . '><span>I agree to the <a href="' . esc_url( ss_auth_terms_url() ) . '" target="_blank" rel="noopener">Terms</a> and <a href="' . esc_url( ss_auth_privacy_url() ) . '" target="_blank" rel="noopener">Privacy Policy</a>&nbsp;<span class="required">*</span></span></label>';
}
add_action( 'woocommerce_register_form', function () {
	if ( 'no' !== get_option( 'woocommerce_registration_generate_password' ) ) return;
	echo '<p class="woocommerce-form-row form-row"><label for="reg_password2">Confirm password&nbsp;<span class="required">*</span></label><input type="password" class="woocommerce-Input input-text" name="password2" id="reg_password2" autocomplete="new-password" placeholder="Repeat password" required></p>';
}, 1 );
add_filter( 'woocommerce_registration_errors', 'ss_reg_validate', 10, 3 );
function ss_reg_validate( $errors, $username, $email ) {
	if ( ! isset( $_POST['ss_first_name'] ) ) return $errors; /* checkout etc. */
	if ( trim( wp_unslash( $_POST['ss_first_name'] ) ) === '' ) $errors->add( 'ss_first_name', '<strong>First name</strong> is required.' );
	if ( ! isset( $_POST['ss_last_name'] ) || trim( wp_unslash( $_POST['ss_last_name'] ) ) === '' ) $errors->add( 'ss_last_name', '<strong>Last name</strong> is required.' );
	if ( isset( $_POST['password'] ) && strlen( (string) wp_unslash( $_POST['password'] ) ) < 8 ) $errors->add( 'ss_password', 'Password must be at least <strong>8 characters</strong>.' );
	if ( isset( $_POST['password2'] ) && (string) wp_unslash( $_POST['password2'] ) !== ( isset( $_POST['password'] ) ? (string) wp_unslash( $_POST['password'] ) : '' ) ) $errors->add( 'ss_password2', '<strong>Passwords</strong> do not match.' );
	if ( empty( $_POST['ss_terms'] ) ) $errors->add( 'ss_terms', 'Please accept the <strong>Terms and Privacy Policy</strong>.' );
	return $errors;
}
add_action( 'woocommerce_created_customer', 'ss_reg_save', 10, 1 );
function ss_reg_save( $uid ) {
	if ( ! isset( $_POST['ss_first_name'] ) ) return;
	$f = sanitize_text_field( wp_unslash( $_POST['ss_first_name'] ) );
	$l = isset( $_POST['ss_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['ss_last_name'] ) ) : '';
	$types = ss_auth_user_types();
	$type = isset( $_POST['ss_user_type'] ) ? sanitize_key( wp_unslash( $_POST['ss_user_type'] ) ) : '';
	if ( ! isset( $types[ $type ] ) ) $type = '';
	wp_update_user( array( 'ID' => $uid, 'first_name' => $f, 'last_name' => $l, 'display_name' => trim( $f . ' ' . $l ) ) );
	update_user_meta( $uid, 'billing_first_name', $f );
	update_user_meta( $uid, 'billing_last_name', $l );
	update_user_meta( $uid, 'ss_user_type', $type );
	update_user_meta( $uid, 'ss_newsletter', empty( $_POST['ss_newsletter'] ) ? 'no' : 'yes' );
	update_user_meta( $uid, 'ss_terms_accepted', current_time( 'mysql' ) );
}

/* English privacy text (the saved WooCommerce text is in Russian) */
add_filter( 'woocommerce_get_privacy_policy_text', function ( $text, $type ) {
	if ( ! preg_match( '/\p{Cyrillic}/u', (string) $text ) || strpos( determine_locale(), 'en' ) !== 0 ) return $text;
	return 'registration' === $type
		? 'Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our [privacy_policy].'
		: 'Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our [privacy_policy].';
}, 10, 2 );

/* ---------- Admin: show sign-up answers ---------- */
function ss_user_signup_box( $user ) {
	$types = ss_auth_user_types();
	$t = get_user_meta( $user->ID, 'ss_user_type', true );
	$n = get_user_meta( $user->ID, 'ss_newsletter', true );
	$c = get_user_meta( $user->ID, 'ss_terms_accepted', true );
	if ( '' === $t && '' === $n && '' === $c ) return;
	echo '<h2>Solid Sound sign-up</h2><table class="form-table" role="presentation">';
	echo '<tr><th>I am a&hellip;</th><td>' . esc_html( isset( $types[ $t ] ) ? $types[ $t ] : '—' ) . '</td></tr>';
	echo '<tr><th>Newsletter</th><td>' . ( 'yes' === $n ? 'Yes, subscribed' : 'No' ) . '</td></tr>';
	echo '<tr><th>Terms accepted</th><td>' . esc_html( $c ? $c : '—' ) . '</td></tr></table>';
}
add_action( 'show_user_profile', 'ss_user_signup_box' );
add_action( 'edit_user_profile', 'ss_user_signup_box' );
add_filter( 'manage_users_columns', function ( $cols ) { $cols['ss_signup'] = 'Sign-up'; return $cols; } );
add_filter( 'manage_users_custom_column', function ( $out, $col, $uid ) {
	if ( 'ss_signup' !== $col ) return $out;
	$types = ss_auth_user_types();
	$t = get_user_meta( $uid, 'ss_user_type', true );
	$parts = array();
	if ( isset( $types[ $t ] ) ) $parts[] = esc_html( $types[ $t ] );
	if ( 'yes' === get_user_meta( $uid, 'ss_newsletter', true ) ) $parts[] = '<span style="color:#1a7f37">&#9993; newsletter</span>';
	return $parts ? implode( '<br>', $parts ) : '—';
}, 10, 3 );
/* Users screen: export newsletter subscribers (CSV) */
add_action( 'restrict_manage_users', function ( $which ) {
	if ( 'top' !== $which || ! current_user_can( 'list_users' ) ) return;
	echo '<a class="button" style="margin-left:6px" href="' . esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=ss_newsletter_csv' ), 'ss_newsletter_csv' ) ) . '">Export newsletter CSV</a>';
} );
add_action( 'admin_post_ss_newsletter_csv', function () {
	if ( ! current_user_can( 'list_users' ) || ! check_admin_referer( 'ss_newsletter_csv' ) ) wp_die( 'Not allowed' );
	$users = get_users( array( 'meta_key' => 'ss_newsletter', 'meta_value' => 'yes', 'fields' => array( 'ID', 'user_email', 'user_registered' ) ) );
	$types = ss_auth_user_types();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=solid-sound-newsletter-' . gmdate( 'Y-m-d' ) . '.csv' );
	$o = fopen( 'php://output', 'w' );
	fputcsv( $o, array( 'email', 'first_name', 'last_name', 'i_am_a', 'registered' ) );
	foreach ( $users as $u ) {
		$t = get_user_meta( $u->ID, 'ss_user_type', true );
		fputcsv( $o, array( $u->user_email, get_user_meta( $u->ID, 'first_name', true ), get_user_meta( $u->ID, 'last_name', true ), isset( $types[ $t ] ) ? $types[ $t ] : '', $u->user_registered ) );
	}
	fclose( $o );
	exit;
} );

/* ---------- /sell/ page: [ss_sell] ---------- */
add_shortcode( 'ss_sell', 'ss_sell_page' );
function ss_sell_page() {
	$contact = home_url( '/contacts/' );
	$terms   = home_url( '/sell/#terms' );
	$faq = array(
		array( 'Is it exclusive?', 'No. You keep all rights and can sell the same products anywhere else.' ),
		array( 'How and when do I get paid?', 'Monthly, once your balance reaches $25, by PayPal or bank transfer. You see every sale in your dashboard.' ),
		array( 'What is a Founding Creator?', 'The first 20 approved producers get 85% per sale for 12 months, a Founding Creator badge and priority promotion on the homepage and our socials. After 12 months the standard 70% applies.' ),
		array( 'Do I get a discount on mixing and mastering?', 'Yes. Every approved creator gets 20% off our mixing and mastering services.' ),
		array( 'Can I use third-party samples?', 'Only royalty-free samples you are allowed to redistribute inside a project. No copyrighted loops or vocals.' ),
		array( 'What if a buyer doesn\'t have my plugins?', 'You list the plugins used; they appear on the product page and as a filter, so buyers know before they purchase.' ),
	);
	$ic = function ( $d ) { return '<div class="sssell-ic"><svg viewBox="0 0 24 24" aria-hidden="true">' . $d . '</svg></div>'; };
	ob_start();
	?>
<div class="sssell">
<style>
.sssell{--or:#e09900;--or2:#f5ad12;--mut:#9b9690;--line:rgba(255,255,255,.09);--panel:#1c1a18;color:#ececec;font-size:15px;line-height:1.55}
.sssell *{box-sizing:border-box}
.sssell a{color:var(--or)}
.sssell .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:14px 22px;border:0;border-radius:10px;background:var(--or);color:#111!important;font:800 15px/1.2 Roboto,sans-serif;letter-spacing:.03em;text-transform:uppercase;text-decoration:none!important;cursor:pointer;transition:background .15s}
.sssell .btn:hover{background:var(--or2)}
.sssell .btn.ghost{background:none;border:1.5px solid var(--or);color:var(--or)!important}
.sssell-hero{display:grid;grid-template-columns:1.2fr .8fr;gap:28px;align-items:center;padding:40px 34px;border-radius:18px;background:radial-gradient(circle at 85% 20%,rgba(224,153,0,.18),transparent 55%),linear-gradient(135deg,#1f1b17,#121010);border:1px solid var(--line)}
.sssell-kick{display:inline-block;padding:5px 12px;border:1px solid rgba(224,153,0,.45);border-radius:999px;color:var(--or);font:700 12px Roboto,sans-serif;letter-spacing:.1em;text-transform:uppercase}
.sssell h1{margin:14px 0 12px!important;padding:0!important;font:700 clamp(30px,4.6vw,50px)/1.08 Jura,Roboto,sans-serif!important;color:#fff!important;text-transform:none!important;letter-spacing:0!important}
.sssell h1 em,.sssell h2 em{font-style:normal;color:var(--or)}
.sssell-hero p{margin:0 0 22px;color:#cfcac4;font-size:17px}
.sssell-ctas{display:flex;flex-wrap:wrap;gap:10px}
.sssell-big{padding:24px;border-radius:14px;background:rgba(0,0,0,.35);border:1px solid var(--line);text-align:center}
.sssell-big .n{font:700 68px/1 Jura,Roboto,sans-serif;color:var(--or)}
.sssell-big .l{margin-top:6px;color:#cfcac4}
.sssell-big .s{margin-top:10px;color:var(--mut);font-size:13px}
.sssell-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin:14px 0 0}
.sssell-stat{padding:14px;border-radius:12px;background:var(--panel);border:1px solid var(--line);text-align:center}
.sssell-stat b{display:block;font:700 22px/1.3 Jura,Roboto,sans-serif;color:#fff}.sssell-stat span{color:var(--mut);font-size:12.5px}
.sssell h2{margin:52px 0 6px!important;padding:0!important;font:700 clamp(24px,3.2vw,34px)/1.2 Jura,Roboto,sans-serif!important;text-align:center;color:#fff!important;text-transform:none!important;letter-spacing:0!important}
.sssell-lead{max-width:640px;margin:0 auto 22px!important;text-align:center;color:var(--mut)}
.sssell-g3{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.sssell-card{padding:22px;border-radius:14px;background:var(--panel);border:1px solid var(--line)}
.sssell-card h3{margin:12px 0 6px!important;padding:0!important;font:700 17px/1.3 Jura,Roboto,sans-serif!important;color:#fff!important;text-transform:none!important}
.sssell-card p{margin:0;color:#bdb8b1;font-size:14px}
.sssell-ic{display:flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:10px;background:rgba(224,153,0,.14)}
.sssell-ic svg{width:22px;height:22px;stroke:var(--or);fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.sssell-table{width:100%;margin:0!important;border-collapse:separate;border-spacing:0;background:var(--panel);border:1px solid var(--line);border-radius:14px;overflow:hidden;font-size:14.5px}
.sssell-table th,.sssell-table td{padding:13px 16px!important;border:0!important;border-bottom:1px solid var(--line)!important;text-align:left;vertical-align:top;background:none}
.sssell-table th{width:34%;color:#cfcac4;font-weight:600}
.sssell-table td{color:#fff;font-weight:700;background:rgba(224,153,0,.07)!important}
.sssell-table td b{color:var(--or)}
.sssell-table tr:last-child th,.sssell-table tr:last-child td{border-bottom:0!important}
.sssell-steps{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-top:20px;counter-reset:s}
.sssell-step{padding:18px;border-radius:14px;background:var(--panel);border:1px solid var(--line)}
.sssell-step:before{counter-increment:s;content:counter(s);display:flex;align-items:center;justify-content:center;width:32px;height:32px;margin-bottom:10px;border-radius:50%;background:var(--or);color:#111;font-weight:800}
.sssell-step b{display:block;margin-bottom:4px;color:#fff}.sssell-step span{color:#bdb8b1;font-size:14px}
.sssell-apply{display:grid;grid-template-columns:.85fr 1.15fr;gap:22px;align-items:start;scroll-margin-top:90px}
.sssell-perks{list-style:none!important;display:grid;gap:10px;margin:12px 0 16px!important;padding:0!important}
.sssell-perks li{display:flex;align-items:flex-start;gap:10px;margin:0!important;padding:10px 12px!important;border-radius:10px;background:rgba(255,255,255,.04);font-size:14px;color:#cfcac4}
.sssell-perks li:before{display:none!important}
.sssell-perks i{display:flex;align-items:center;justify-content:center;flex:0 0 26px;height:26px;border-radius:50%;background:rgba(224,153,0,.15);color:var(--or);font-style:normal;font-weight:800}
.sssell-faq{max-width:820px;margin:18px auto 0}
.sssell details{margin-bottom:10px;border:1px solid var(--line);border-radius:12px;background:var(--panel)}
.sssell summary{display:flex;justify-content:space-between;gap:12px;padding:15px 18px;font-weight:700;color:#fff;cursor:pointer;list-style:none}
.sssell summary::-webkit-details-marker{display:none}
.sssell summary:after{content:'+';color:var(--or);font-size:20px;line-height:1}
.sssell details[open] summary:after{content:'\2013'}
.sssell details p{margin:0;padding:0 18px 16px;color:#bdb8b1;font-size:14.5px}
/* WCFM registration form */
.sssell-form .sssell-chk{display:flex!important;align-items:flex-start;gap:9px;margin:16px 0 4px!important;font:400 13.5px/1.45 Roboto,sans-serif!important;color:#cfcac4!important;cursor:pointer}
.sssell-form .sssell-chk input{flex:0 0 16px;margin:2px 0 0!important}
.sssell-form .sssell-chk small{display:block;margin-top:2px;color:var(--mut);font-size:12.5px}
.sssell-form .sssell-chk a{color:var(--or)}
.sssell-form .img_tip{display:none!important}
.sssell-form p.terms_title,.sssell-form p.wcfm_title.terms_title{display:none!important}
.sssell-form .screen-reader-text{position:absolute!important;width:1px!important;height:1px!important;overflow:hidden!important;clip:rect(0 0 0 0)!important;margin:-1px!important;padding:0!important;border:0!important}
.sssell-form{padding:24px}
.sssell-row{display:grid;gap:10px;align-items:start}.sssell-r3{grid-template-columns:1fr 1fr 1.3fr}.sssell-r2{grid-template-columns:1fr 1fr}.sssell-end{align-items:end}
.sssell-cell{min-width:0}
.sssell .sssell-form .wcfm-membership-wrapper,.sssell .sssell-form .wcfm-container,.sssell .sssell-form #wcfm-content{margin:0!important;padding:0!important;border:0!important;background:none!important;box-shadow:none!important}
.sssell-form input:-webkit-autofill,.sssell-form input:-webkit-autofill:hover,.sssell-form input:-webkit-autofill:focus{-webkit-box-shadow:0 0 0 60px #151311 inset!important;-webkit-text-fill-color:#fff!important;caret-color:#fff;transition:background-color 9999s}
.sssell-form #password_strength:empty{display:none}
@media (max-width:640px){.sssell-r3{grid-template-columns:1fr 1fr}.sssell-r3>.sssell-cell:nth-child(3){grid-column:1/-1}.sssell-end{grid-template-columns:1fr}}
.sssell-form #wcfm_membership_container,.sssell-form .wcfm-container,.sssell-form .wcfm-content{margin:0!important;padding:0!important;border:0!important;background:none!important;box-shadow:none!important}
.sssell-form h2,.sssell-form .wcfm-page-headig,.sssell-form #wcfm_membership_registration_form_expander>h2{display:none!important}
.sssell-form .wcfm_title,.sssell-form p.wcfm_title{display:block!important;float:none!important;width:100%!important;margin:12px 0 6px!important;padding:0!important;font:600 13px/1.4 Roboto,sans-serif!important;color:#cfcac4!important}
.sssell-form .wcfm_title strong{font-weight:600!important}
.sssell-form .required{color:var(--or)!important}
.sssell-form input.wcfm-text,.sssell-form input[type=text],.sssell-form input[type=email],.sssell-form input[type=password],.sssell-form select,.sssell-form textarea{display:block!important;float:none!important;width:100%!important;max-width:none!important;height:auto!important;min-height:44px;margin:0!important;padding:11px 12px!important;border:1px solid rgba(255,255,255,.12)!important;border-radius:9px!important;background:#151311!important;color:#fff!important;font:15px/1.3 Roboto,sans-serif!important;box-shadow:none!important}
.sssell-form input:focus,.sssell-form select:focus,.sssell-form textarea:focus{outline:none!important;border-color:var(--or)!important;box-shadow:0 0 0 3px rgba(224,153,0,.18)!important}
.sssell-form .description,.sssell-form .wcfm_page_options_desc,.sssell-form .instructions{display:block;float:none!important;width:100%!important;margin:5px 0 0!important;color:var(--mut)!important;font-size:12.5px!important}
.sssell-form input[type=checkbox]{accent-color:var(--or);width:16px;height:16px;margin:0 8px 0 0!important;vertical-align:middle}
.sssell-form input[type=submit],.sssell-form input[type=button],.sssell-form .wcfm_submit_button{display:inline-flex!important;align-items:center;justify-content:center;float:none!important;width:100%!important;margin:16px 0 0!important;padding:14px 18px!important;border:0!important;border-radius:10px!important;background:var(--or)!important;color:#111!important;font:800 15px/1.2 Roboto,sans-serif!important;letter-spacing:.03em!important;text-transform:uppercase!important;cursor:pointer;height:auto!important}
.sssell-form input[type=button]{width:auto!important;margin:8px 0 0!important;padding:10px 16px!important;font-size:13px!important}
.sssell-form .wcfm-clearfix{clear:both}
.sssell-form .wcfm-message{margin:12px 0 0!important;border-radius:10px}
.sssell-hidden{position:absolute!important;width:1px!important;height:1px!important;min-height:0!important;padding:0!important;border:0!important;opacity:0;pointer-events:none}
.sssell-chips{display:flex;flex-wrap:wrap;gap:6px}
.sssell-chips label{display:inline-flex!important;align-items:center;margin:0!important;padding:7px 11px;border-radius:8px;border:1px solid rgba(255,255,255,.14);background:#151311;color:#ececec;font:13px/1.2 Roboto,sans-serif;cursor:pointer;user-select:none}
.sssell-chips input{position:absolute;opacity:0;width:0!important;height:0!important}
.sssell-chips label:has(input:checked){background:var(--or);border-color:var(--or);color:#111;font-weight:700}
@media (max-width:900px){.sssell-hero,.sssell-apply{grid-template-columns:1fr}.sssell-g3,.sssell-steps,.sssell-stats{grid-template-columns:1fr 1fr}}
@media (max-width:560px){.sssell-g3,.sssell-steps{grid-template-columns:1fr}.sssell-hero{padding:26px 18px}.sssell-form{padding:18px 16px}.sssell-ctas .btn{width:100%}.sssell-table th{width:42%}.sssell-table th,.sssell-table td{padding:11px 12px!important;font-size:13.5px}}
</style>

<section class="sssell-hero">
	<div>
		<span class="sssell-kick">For producers &amp; sound designers</span>
		<h1>Sell your Ableton templates.<br><em>Keep up to 85%.</em></h1>
		<p>A marketplace made for producers and DJs: waveform previews, BPM &amp; key filters, a player that lets buyers compare tracks. No fees, non-exclusive, you set the price.</p>
		<div class="sssell-ctas"><a class="btn" href="#apply">Apply now &mdash; takes 2 minutes</a><a class="btn ghost" href="#how">How it works</a></div>
	</div>
	<div class="sssell-big"><div class="n">85%</div><div class="l">to you for every sale</div><div class="s">Founding Creator rate &middot; first 20 producers &middot; locked for 12 months<br>Standard rate: 70%</div></div>
</section>
<div class="sssell-stats">
	<div class="sssell-stat"><b>$0</b><span>listing fees</span></div>
	<div class="sssell-stat"><b>24 h</b><span>application review</span></div>
	<div class="sssell-stat"><b>$25</b><span>minimum payout</span></div>
	<div class="sssell-stat"><b>Non-exclusive</b><span>sell anywhere else too</span></div>
</div>

<h2>Why sell <em>here</em></h2>
<p class="sssell-lead">Big marketplaces list thousands of products &mdash; new creators get buried. We put your work in front of buyers from day one.</p>
<div class="sssell-g3">
	<div class="sssell-card"><?php echo $ic( '<path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>' ); ?><h3>Higher share</h3><p>Up to 85% for Founding Creators, 70% standard. Most marketplaces start at 50%.</p></div>
	<div class="sssell-card"><?php echo $ic( '<polygon points="12 2 15 9 22 9 16.5 13.5 18.5 21 12 16.5 5.5 21 7.5 13.5 2 9 9 9"/>' ); ?><h3>Featured, not buried</h3><p>New releases go to the homepage, Best Products and our Instagram, TikTok and YouTube &mdash; with your name.</p></div>
	<div class="sssell-card"><?php echo $ic( '<path d="M2 12h3l3-8 4 16 3-8h7"/>' ); ?><h3>Waveform previews</h3><p>Buyers hear your track instantly, add it to a queue and compare &mdash; built for DJs and producers.</p></div>
	<div class="sssell-card"><?php echo $ic( '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>' ); ?><h3>Found by BPM &amp; key</h3><p>Genre, BPM, key, Ableton version and plugins as filters &mdash; the right buyer finds your template in seconds.</p></div>
	<div class="sssell-card"><?php echo $ic( '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M17 8l-5-5-5 5"/><path d="M12 3v12"/>' ); ?><h3>We upload for you</h3><p>Have 5+ products? Send the files &mdash; we create the listings, covers and descriptions for free.</p></div>
	<div class="sssell-card"><?php echo $ic( '<path d="M21 11.5a8.4 8.4 0 0 1-9 8.4 8.6 8.6 0 0 1-3.9-.9L3 21l2-5a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 8.4-8.4 8.4 8.4 0 0 1 8.5 7.7z"/>' ); ?><h3>Talk to a human</h3><p>Direct contact with the founder, not a ticket queue. Plus 20% off our mixing &amp; mastering.</p></div>
</div>

<h2 id="terms" style="scroll-margin-top:90px">The <em>terms</em>, in one table</h2>
<p class="sssell-lead">No small print.</p>
<table class="sssell-table">
	<tr><th>Your share per sale</th><td><b>85%</b> Founding Creators (first 20, 12 months) &middot; <b>70%</b> standard</td></tr>
	<tr><th>Listing / membership fee</th><td>None</td></tr>
	<tr><th>Exclusivity</th><td>Non-exclusive &mdash; keep selling anywhere</td></tr>
	<tr><th>Who sets the price</th><td>You</td></tr>
	<tr><th>Payouts</th><td>Monthly, from $25 &middot; PayPal or bank transfer</td></tr>
	<tr><th>Application review</th><td>Within 24 hours</td></tr>
	<tr><th>Creator perk</th><td>20% off mixing &amp; mastering</td></tr>
	<tr><th>What you can sell</th><td>Ableton / DAW templates, synth presets, sample packs, MIDI, stems, royalty-free music</td></tr>
</table>

<h2 id="how">How it <em>works</em></h2>
<div class="sssell-steps">
	<div class="sssell-step"><b>Apply</b><span>2-minute form with links to your work.</span></div>
	<div class="sssell-step"><b>Get approved</b><span>We reply within 24 h.</span></div>
	<div class="sssell-step"><b>Upload</b><span>Add products in your dashboard &mdash; or let us do it.</span></div>
	<div class="sssell-step"><b>Get paid</b><span>Monthly payouts, live sales stats.</span></div>
</div>

<h2 id="apply" style="scroll-margin-top:90px">Apply as a <em>creator</em></h2>
<p class="sssell-lead">Takes 2 minutes. You can upload products right after approval.</p>
<div class="sssell-apply">
	<div class="sssell-card">
		<h3 style="margin-top:0!important">What we look for</h3>
		<ul class="sssell-perks">
			<li><i>&#10003;</i><span>Full projects or packs you made yourself</span></li>
			<li><i>&#10003;</i><span>Plugins listed (native-only is a plus)</span></li>
			<li><i>&#10003;</i><span>Cleared samples &mdash; no copyrighted material</span></li>
			<li><i>&#10003;</i><span>A short audio preview (MP3)</span></li>
		</ul>
		<p style="color:var(--mut);font-size:13.5px">Questions first? <a href="<?php echo esc_url( $contact ); ?>">Message us</a> &mdash; we answer personally. Full <a href="<?php echo esc_url( $terms ); ?>">Creator Terms</a>.</p>
	</div>
	<div class="sssell-card sssell-form">
		<?php
		if ( is_user_logged_in() && function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor() ) {
			echo '<h3 style="margin-top:0!important">You are already a creator</h3><p>Go to your <a href="' . esc_url( home_url( '/store-manager/' ) ) . '">dashboard</a> to add products.</p>';
		} else {
			echo do_shortcode( '[wcfm_vendor_registration]' );
		}
		?>
	</div>
</div>

<h2>Questions</h2>
<div class="sssell-faq">
	<?php foreach ( $faq as $q ) echo '<details><summary>' . esc_html( $q[0] ) . '</summary><p>' . esc_html( $q[1] ) . '</p></details>'; ?>
</div>
<?php
	$ld = array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array() );
	foreach ( $faq as $q ) $ld['mainEntity'][] = array( '@type' => 'Question', 'name' => $q[0], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $q[1] ) );
	echo '<script type="application/ld+json">' . wp_json_encode( $ld ) . '</script>';
?>
</div>
	<?php
	return ob_get_clean();
}

endif; /* ss_auth_user_types */

/* Solid Sound: allow administrators to save WCFM global settings (commission, withdrawals). Approved by site owner 14.10.2026. */
add_action( 'admin_init', function () {
	$r = get_role( 'administrator' );
	if ( $r && ! $r->has_cap( 'access_wcfm_site_setup' ) ) $r->add_cap( 'access_wcfm_site_setup' );
} );

/* ═══ Solid Sound — 14.10.2026: creator upload form (/add-product/), header account button ═══ */
if ( ! function_exists( 'ss_ap_keys' ) ) {
	function ss_ap_keys() {
		return array(
			'cat'     => 'field_685463b3dc03d',
			'format'  => 'field_683f513c6d237',
			'daw'     => 'field_61de92b2aab40',
			'version' => 'field_683f53739611e',
			'genre'   => 'field_683f5d5f8537b',
			'mood'    => 'field_6840d71075491',
			'key'     => 'field_683f502fbc39b',
			'bpm'     => 'field_61debd35469c2',
			'length'  => 'field_61debd4cce8b0',
			'ext'     => 'field_683f4a2fa9df4',
			'dl'      => 'field_683f563766a36',
			'tag'     => 'field_6840c5afc64ab',
			'mp3'     => 'field_61de97709eed0',
		);
	}

	/* Front-end form only: our own per-format logic replaces ACF conditions, cleaner labels, no new terms for fixed lists */
	add_filter( 'acf/prepare_field', function ( $field ) {
		if ( is_admin() || empty( $field['key'] ) ) return $field;
		$k = array_search( $field['key'], ss_ap_keys(), true );
		if ( false === $k ) return $field;
		$field['conditional_logic'] = 0;
		$field['wrapper']['class']  = trim( ( isset( $field['wrapper']['class'] ) ? $field['wrapper']['class'] : '' ) . ' ss-ap-f ss-ap-' . $k );
		$field['wrapper']['width']  = '';
		if ( in_array( $k, array( 'format', 'daw', 'version' ), true ) ) { $field['field_type'] = 'radio'; $field['allow_null'] = 0; }
		if ( in_array( $k, array( 'genre', 'mood', 'ext', 'dl' ), true ) ) $field['field_type'] = 'checkbox';
		$labels = array(
			'cat' => 'Category', 'format' => 'Product format', 'daw' => 'DAW', 'version' => 'DAW version', 'genre' => 'Genre',
			'mood' => 'Mood', 'key' => 'Key', 'bpm' => 'BPM', 'length' => 'Length (m:ss)', 'ext' => 'Plugins used',
			'dl' => 'Pack contents', 'tag' => 'Tags (optional)', 'mp3' => 'MP3 preview (128 kbps)',
		);
		$help = array(
			'format' => 'Templates: your own original projects. Reconstructed Templates: remakes of existing tracks.',
			'genre'  => 'Pick from the list or add your own.',
			'key'    => 'Main key of the track, e.g. Am, F#m, C.',
			'bpm'    => 'Pick from the list or type a number from 50 to 220.',
			'length' => 'Type the length as m:ss, e.g. 5:30.',
			'ext'    => 'Choose "No External Plugins" if you only use stock DAW plugins.',
			'dl'     => 'What is inside: loops, one-shots, MIDI, presets…',
			'mp3'    => 'Click "Add Media" and upload an MP3 (128 kbps, 1–2 min). Buyers hear it in the player.',
		);
		if ( isset( $labels[ $k ] ) ) $field['label'] = $labels[ $k ];
		if ( isset( $help[ $k ] ) ) $field['instructions'] = $help[ $k ];
		if ( in_array( $k, array( 'format', 'genre', 'mp3' ), true ) ) $field['required'] = 1;
		if ( in_array( $k, array( 'cat', 'format' ), true ) ) { $field['add_term'] = 0; } elseif ( 'mp3' !== $k ) { $field['add_term'] = 1; }
		if ( 'cat' === $k ) {
			$field['wrapper']['class'] .= ' ss-ap-hide';
			if ( empty( $field['value'] ) ) $field['value'] = 115; /* Music Production */
		}
		return $field;
	}, 20 );

	/* Creators can add new terms only where it makes sense, in a clean form */
	add_filter( 'pre_insert_term', function ( $term, $tax ) {
		if ( is_wp_error( $term ) || current_user_can( 'manage_options' ) ) return $term;
		$watched = array( 'product_cat', 'product-format', 'daw', 'product-version', 'pa_root-key', 'pa_bpm', 'pa_length', 'product-genre', 'pa_mood', 'external-plugin', 'pa_download-include', 'product_tag' );
		if ( ! in_array( $tax, $watched, true ) ) return $term;
		$t = trim( preg_replace( '/\s+/u', ' ', (string) $term ) );
		if ( in_array( $tax, array( 'product_cat', 'product-format' ), true ) ) {
			return new WP_Error( 'ss_fixed_list', 'Please pick a value from the list.' );
		}
		if ( 'pa_bpm' === $tax ) {
			if ( ! preg_match( '/^\d{2,3}$/', $t ) || (int) $t < 50 || (int) $t > 220 ) return new WP_Error( 'ss_bpm', 'BPM must be a whole number from 50 to 220.' );
			return (string) (int) $t;
		}
		if ( 'pa_length' === $tax ) {
			if ( ! preg_match( '/^(\d{1,2}):([0-5]\d)$/', $t, $m ) ) return new WP_Error( 'ss_len', 'Please type the length as m:ss, e.g. 5:30.' );
			return (int) $m[1] . ':' . $m[2];
		}
		if ( '' === $t || mb_strlen( $t ) > 40 ) return new WP_Error( 'ss_len', 'Please use 1–40 characters.' );
		if ( 'product_tag' === $tax ) return mb_strtolower( $t );
		if ( $t === mb_strtolower( $t ) ) $t = mb_convert_case( $t, MB_CASE_TITLE, 'UTF-8' );
		if ( get_term_by( 'name', $t, $tax ) ) return new WP_Error( 'ss_dupe', '"' . $t . '" already exists. Please pick it from the list.' );
		return $t;
	}, 10, 2 );

	/* Server-side checks before the upload handler runs; protected folder for paid files */
	function ss_ap_protected_dir( $p ) {
		$p['subdir'] = '/woocommerce_uploads' . $p['subdir'];
		$p['path']   = $p['basedir'] . $p['subdir'];
		$p['url']    = $p['baseurl'] . $p['subdir'];
		return $p;
	}
	function ss_ap_fail( $msg ) {
		wp_die( '<p>' . esc_html( $msg ) . '</p><p><a href="javascript:history.back()">&larr; Back to the form</a></p>', 'Please check your product', array( 'response' => 200 ) );
	}
	add_action( 'admin_post_solid_sound_add_product', function () {
		if ( ! is_user_logged_in() ) return;
		$keys = ss_ap_keys();
		$acf  = ( isset( $_POST['acf'] ) && is_array( $_POST['acf'] ) ) ? $_POST['acf'] : array();
		$val  = function ( $k ) use ( $acf, $keys ) { return isset( $acf[ $keys[ $k ] ] ) ? $acf[ $keys[ $k ] ] : ''; };
		if ( empty( $acf[ $keys['cat'] ] ) ) $_POST['acf'][ $keys['cat'] ] = 115;
		if ( current_user_can( 'manage_options' ) ) { /* 16.11: creators have manage_woocommerce too */
			/* admins: keep the old behaviour, only add the protected folder */
		} else {
			if ( '' === trim( (string) ( isset( $_POST['product_name'] ) ? $_POST['product_name'] : '' ) ) ) ss_ap_fail( 'Please add a product name.' );
			if ( ! array_filter( (array) $val( 'format' ) ) ) ss_ap_fail( 'Please choose the product format.' );
			if ( ! array_filter( (array) $val( 'genre' ) ) ) ss_ap_fail( 'Please choose at least one genre.' );
			if ( false === stripos( (string) $val( 'mp3' ), '.mp3' ) ) ss_ap_fail( 'Please add an MP3 preview so buyers can listen to your product.' );
			if ( empty( $_FILES['product_image']['name'] ) ) ss_ap_fail( 'Please add a cover image.' );
			if ( empty( $_FILES['product_file']['name'] ) ) ss_ap_fail( 'Please add the file buyers will download (zip or audio).' );
		}
		add_filter( 'wp_handle_upload_prefilter', function ( $file ) {
			if ( ! empty( $_FILES['product_file']['tmp_name'] ) && isset( $file['tmp_name'] ) && $file['tmp_name'] === $_FILES['product_file']['tmp_name'] ) {
				add_filter( 'upload_dir', 'ss_ap_protected_dir' );
			}
			return $file;
		} );
		add_filter( 'wp_handle_upload', function ( $u ) { remove_filter( 'upload_dir', 'ss_ap_protected_dir' ); return $u; } );
	}, 1 );

	/* Email the owner when a creator sends a product for review */
	add_action( 'transition_post_status', function ( $new, $old, $post ) {
		if ( 'pending' !== $new || 'pending' === $old || 'product' !== $post->post_type ) return;
		$u = get_userdata( $post->post_author );
		wp_mail( get_option( 'admin_email' ), 'New product for review: ' . $post->post_title,
			'Creator: ' . ( $u ? $u->display_name . ' <' . $u->user_email . '>' : '—' ) . "\n\nReview and publish:\n" . admin_url( 'post.php?post=' . $post->ID . '&action=edit' ) );
	}, 10, 3 );

	/* Creators who click "Add product" in the dashboard get our form with the filter fields */
	add_action( 'template_redirect', function () {
		if ( ! is_user_logged_in() || current_user_can( 'manage_options' ) ) return;
		global $wp;
		if ( isset( $wp->query_vars['wcfm-products-manage'] ) && '' === (string) $wp->query_vars['wcfm-products-manage'] ) {
			wp_safe_redirect( function_exists( 'ss_ep_url' ) ? ss_ep_url() : home_url( '/add-product/' ) );
			exit;
		}
	} );

	/* Form layout + per-format fields + checks (only on pages with the upload form) */
	add_action( 'wp_footer', function () {
		if ( ! is_user_logged_in() ) return;
		$sym = function_exists( 'get_woocommerce_currency_symbol' ) ? html_entity_decode( get_woocommerce_currency_symbol() ) : '$';
		?>
<style id="ss-ap">
#solid-sound-upload-form{max-width:1180px!important;margin:0 auto;background:#fff;color:#2b2f3a;border-radius:12px;padding:0 0 24px;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,sans-serif;font-size:14px}
#solid-sound-upload-form *{box-sizing:border-box}
#solid-sound-upload-form .ss-wm-top{position:sticky;top:0;z-index:20;display:flex;justify-content:space-between;align-items:center;gap:10px;background:#f6f7f9;border-bottom:1px solid #e6e8ee;padding:14px 22px;border-radius:12px 12px 0 0}
#solid-sound-upload-form .ss-wm-top h2{margin:0;font-size:20px;font-weight:400;color:#2b2f3a}
#solid-sound-upload-form .ss-wm-top button{background:#e9eaee;color:#555b6b;border:0;border-radius:4px;padding:10px 16px;font-size:16px;cursor:pointer}
#solid-sound-upload-form .ss-wm-top button.pri{background:#1f6feb;color:#fff}
#solid-sound-upload-form .ss-wm-body{padding:8px 22px 0}
#solid-sound-upload-form .ss-wm-sec{margin:22px 0}
#solid-sound-upload-form .ss-wm-row{display:grid;grid-template-columns:repeat(3,1fr);gap:10px 14px;margin:18px 0}
#solid-sound-upload-form .ss-wm-row2{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:14px 18px;margin:18px 0}
#solid-sound-upload-form fieldset{border:0!important;padding:0!important;margin:0!important;min-width:0}
#solid-sound-upload-form fieldset>legend,#solid-sound-upload-form .ss-wm-empty{display:none}
#solid-sound-upload-form .acf-field{width:auto!important;float:none!important;clear:none!important;margin:0!important;padding:0!important;border:0!important;min-height:0!important}
#solid-sound-upload-form .ss-ap-off,#solid-sound-upload-form .ss-ap-hide{display:none!important}
#solid-sound-upload-form .acf-label{margin:0 0 8px}
#solid-sound-upload-form .acf-label label,#solid-sound-upload-form .ss-wm-l{font-weight:700;color:#1d2130;font-size:15px;text-transform:none;display:inline}
#solid-sound-upload-form .ss-wm-max{color:#8a90a0;font-size:12px;font-weight:400;margin-left:4px}
#solid-sound-upload-form .ss-wm-cnt{display:inline-block;background:#eef0f4;color:#6b7183;font-size:12px;padding:5px 9px;border-radius:4px;margin:6px 0 0}
#solid-sound-upload-form .ss-wm-cnt b{color:#1f6feb}
#solid-sound-upload-form .acf-label .description,#solid-sound-upload-form .acf-input>.description{color:#8a90a0;font-size:12px;margin-top:4px}
#solid-sound-upload-form .acf-required,#solid-sound-upload-form .ss-req{color:#e53935}
#solid-sound-upload-form .acf-checkbox-list,#solid-sound-upload-form .acf-radio-list{display:grid!important;grid-template-columns:repeat(auto-fill,minmax(185px,1fr));gap:8px;border:1px solid #e6e8ee;border-radius:6px;padding:10px;max-height:330px;overflow:auto;background:#fafbfc;list-style:none!important;margin:0!important}
#solid-sound-upload-form .acf-checkbox-list li,#solid-sound-upload-form .acf-radio-list li{list-style:none!important;margin:0!important;padding:0!important;float:none!important;width:auto!important}
#solid-sound-upload-form .acf-checkbox-list li:before,#solid-sound-upload-form .acf-radio-list li:before{content:none!important}
#solid-sound-upload-form .acf-checkbox-list label,#solid-sound-upload-form .acf-radio-list label{display:flex!important;align-items:center;gap:10px;min-height:44px;padding:8px 12px;background:#fff;border:1px solid #e3e6ec;border-radius:4px;color:#3a3f4d;font-weight:400;font-size:14px;cursor:pointer;line-height:1.25;margin:0}
#solid-sound-upload-form .acf-checkbox-list label.selected,#solid-sound-upload-form .acf-radio-list label.selected,#solid-sound-upload-form .acf-checkbox-list label:has(input:checked),#solid-sound-upload-form .acf-radio-list label:has(input:checked){border-color:#1f6feb;background:#eef4ff;color:#1d2130}
#solid-sound-upload-form .acf-checkbox-list input,#solid-sound-upload-form .acf-radio-list input{width:16px;height:16px;margin:0;flex:none;accent-color:#1f6feb}
#solid-sound-upload-form .acf-taxonomy-field .acf-actions,#solid-sound-upload-form a.acf-button[data-name=add],#solid-sound-upload-form .acf-icon[data-name=add]{position:static}
#solid-sound-upload-form a[data-name=add]{display:inline-block;margin-top:6px;color:#1f6feb!important;font-size:13px}
#solid-sound-upload-form input[type=text],#solid-sound-upload-form input[type=number],#solid-sound-upload-form textarea,#solid-sound-upload-form .select2-selection{width:100%;background:#eef0f3!important;border:1px solid #e3e6ec!important;border-radius:3px!important;color:#1d2130!important;padding:9px 10px;font-size:14px;min-height:38px;box-shadow:none!important}
#solid-sound-upload-form .select2-selection__rendered{list-style:none!important;margin:0!important;padding-left:4px!important;color:#1d2130!important}
#solid-sound-upload-form .select2-selection__rendered li{list-style:none!important;margin-left:0!important}
#solid-sound-upload-form .select2-selection__rendered li:before{content:none!important}
#solid-sound-upload-form .select2-selection__choice{background:#e2e8f5!important;border:0!important;color:#1d2130!important}
#solid-sound-upload-form .ss-wm-ai{background:linear-gradient(110deg,#6a7ee8 0%,#7458c0 60%,#7a4fae 100%);border-radius:10px;padding:16px;color:#fff;margin:24px 0}
#solid-sound-upload-form .ss-wm-ai .in{background:rgba(255,255,255,.14);border-radius:6px;padding:12px}
#solid-sound-upload-form .ss-wm-ai .hd{display:flex;justify-content:space-between;font-size:13px;margin-bottom:8px}
#solid-sound-upload-form .ss-wm-ai .bar{height:5px;background:rgba(255,255,255,.3);border-radius:9px;overflow:hidden;margin-bottom:10px}
#solid-sound-upload-form .ss-wm-ai .bar i{display:block;height:100%;background:#fff;transition:width .3s}
#solid-sound-upload-form .ss-wm-ai .chips span{display:inline-block;font-size:12px;padding:3px 8px;border-radius:4px;margin:0 6px 4px 0;background:rgba(255,255,255,.12);color:rgba(255,255,255,.55)}
#solid-sound-upload-form .ss-wm-ai .chips span.ok{background:#fff;color:#5a46a8;font-weight:600}
#solid-sound-upload-form .ss-wm-ai .chips span.ok:before{content:"✓ "}
#solid-sound-upload-form .ss-wm-drop{border:2px dashed #1f6feb;border-radius:12px;background:linear-gradient(180deg,#f4f6f9,#e9edf2);padding:38px 20px;text-align:center;cursor:pointer;margin:26px 0 10px;transition:background .2s}
#solid-sound-upload-form .ss-wm-drop.over{background:#e3edff}
#solid-sound-upload-form .ss-wm-drop .ic{font-size:48px;line-height:1}
#solid-sound-upload-form .ss-wm-drop b{display:block;font-size:20px;color:#2b2f3a;margin:10px 0 4px}
#solid-sound-upload-form .ss-wm-drop small{color:#6b7183;font-size:14px}
#solid-sound-upload-form .ss-wm-chips{display:flex;justify-content:center;flex-wrap:wrap;gap:10px;margin-top:16px}
#solid-sound-upload-form .ss-wm-chips span{border:1.5px solid;border-radius:99px;padding:6px 14px;font-size:13px;background:#fff}
#solid-sound-upload-form .ss-wm-chips .c{color:#9b27b0;border-color:#9b27b0}
#solid-sound-upload-form .ss-wm-chips .z{color:#f08c00;border-color:#f08c00}
#solid-sound-upload-form .ss-wm-chips .m{color:#2e9e48;border-color:#2e9e48}
#solid-sound-upload-form .ss-wm-chips span.ok{color:#fff!important}
#solid-sound-upload-form .ss-wm-chips .c.ok{background:#9b27b0}#solid-sound-upload-form .ss-wm-chips .z.ok{background:#f08c00}#solid-sound-upload-form .ss-wm-chips .m.ok{background:#2e9e48}
#solid-sound-upload-form .ss-wm-files{margin:8px 0 0;color:#4b5163;font-size:13px}
#solid-sound-upload-form .ss-wm-files div{margin:4px 0}
#solid-sound-upload-form .ss-wm-files audio{height:32px;vertical-align:middle;margin-left:8px}
#solid-sound-upload-form .ss-wm-files img{width:64px;height:64px;object-fit:cover;border-radius:6px;vertical-align:middle;margin-right:8px}
#solid-sound-upload-form .ss-wm-hidden{position:absolute!important;left:-9999px!important;width:1px;height:1px;overflow:hidden}
#solid-sound-upload-form .ss-wm-alt{display:inline-block;margin-top:6px;color:#1f6feb;font-size:12px;cursor:pointer;text-decoration:underline}
#solid-sound-upload-form .ss-ap-err{color:#e53935;font-size:13px;margin-top:6px}
#solid-sound-upload-form .ss-ap-bad .acf-checkbox-list,#solid-sound-upload-form .ss-ap-bad .acf-radio-list,#solid-sound-upload-form .ss-ap-bad .select2-selection,#solid-sound-upload-form input.ss-ap-bad,#solid-sound-upload-form .ss-wm-drop.ss-ap-bad{border-color:#e53935!important}
#solid-sound-upload-form .ss-wm-note{color:#6b7183;font-size:13px;margin:14px 0 0}
#solid-sound-upload-form .ss-wm-submit{display:flex;align-items:center;gap:14px;flex-wrap:wrap;margin-top:18px}
#solid-sound-upload-form .ss-wm-submit button{background:#1f6feb!important;color:#fff!important;border:0!important;border-radius:6px!important;padding:12px 26px!important;font-weight:700;font-size:15px}
#solid-sound-upload-form #productImagePreview{display:none!important}
@media(max-width:700px){#solid-sound-upload-form .ss-wm-row{grid-template-columns:1fr}#solid-sound-upload-form .acf-checkbox-list,#solid-sound-upload-form .acf-radio-list{grid-template-columns:1fr 1fr}#solid-sound-upload-form .ss-wm-top h2{font-size:17px}}

#solid-sound-upload-form .acf-checkbox-list:before,#solid-sound-upload-form .acf-checkbox-list:after,#solid-sound-upload-form .acf-radio-list:before,#solid-sound-upload-form .acf-radio-list:after{display:none!important;content:none!important}
#solid-sound-upload-form .acf-checkbox-list>li,#solid-sound-upload-form .acf-radio-list>li{display:flex!important;align-items:center;gap:10px;min-height:44px;padding:8px 12px!important;background:#fff;border:1px solid #e3e6ec;border-radius:4px;color:#3a3f4d;font-size:14px;cursor:pointer;line-height:1.25}
#solid-sound-upload-form .acf-checkbox-list>li:has(input:checked),#solid-sound-upload-form .acf-radio-list>li:has(input:checked){border-color:#1f6feb;background:#eef4ff;color:#1d2130}
#solid-sound-upload-form .acf-checkbox-list>li>label,#solid-sound-upload-form .acf-radio-list>li>label{border:0!important;padding:0!important;min-height:0!important;background:transparent!important;width:100%}
#solid-sound-upload-form .ss-wm-top h2{font-style:normal!important}

/* 15.11.2026 owner: one dark look with the rest of the site (was a light WeMake copy mixed with dark theme bits) */
body #solid-sound-upload-form{background:#131b2e!important;color:#e2e8f0!important;border:1px solid rgba(255,255,255,.08);font-family:Roboto,system-ui,sans-serif}
body #solid-sound-upload-form .ss-wm-top{background:#0f1626!important;border-bottom:1px solid rgba(255,255,255,.08)!important}
body #solid-sound-upload-form .ss-wm-top h2{color:#fff!important;font-weight:700!important}
body #solid-sound-upload-form .ss-wm-top button.pri,body #solid-sound-upload-form .ss-wm-submit button{background:#f59e0b!important;color:#111!important;border-radius:10px!important;font-weight:800!important;text-transform:none!important;letter-spacing:.01em}
body #solid-sound-upload-form .ss-wm-top button.pri:hover,body #solid-sound-upload-form .ss-wm-submit button:hover{background:#fbbf24!important}
body #solid-sound-upload-form .acf-label label,body #solid-sound-upload-form .ss-wm-l{color:#fff!important}
body #solid-sound-upload-form .ss-wm-max,body #solid-sound-upload-form .acf-label .description,body #solid-sound-upload-form .acf-input>.description,body #solid-sound-upload-form p.description{color:#94a3b8!important;font-style:normal!important}
body #solid-sound-upload-form .ss-wm-cnt{background:rgba(255,255,255,.06)!important;color:#94a3b8!important}body #solid-sound-upload-form .ss-wm-cnt b{color:#f59e0b!important}
body #solid-sound-upload-form .acf-required,body #solid-sound-upload-form .ss-req{color:#f59e0b!important}
body #solid-sound-upload-form .acf-checkbox-list,body #solid-sound-upload-form .acf-radio-list{background:rgba(255,255,255,.02)!important;border:1px solid rgba(255,255,255,.08)!important;border-radius:10px!important}
body #solid-sound-upload-form .acf-checkbox-list>li,body #solid-sound-upload-form .acf-radio-list>li,body #solid-sound-upload-form .acf-checkbox-list label,body #solid-sound-upload-form .acf-radio-list label{background:#0b1220!important;border-color:rgba(255,255,255,.12)!important;color:#e2e8f0!important;border-radius:8px!important}
body #solid-sound-upload-form .acf-checkbox-list>li>label,body #solid-sound-upload-form .acf-radio-list>li>label{background:transparent!important}
body #solid-sound-upload-form .acf-checkbox-list>li:has(input:checked),body #solid-sound-upload-form .acf-radio-list>li:has(input:checked),body #solid-sound-upload-form .acf-checkbox-list label:has(input:checked),body #solid-sound-upload-form .acf-radio-list label:has(input:checked){border-color:#f59e0b!important;background:rgba(245,158,11,.12)!important;color:#fff!important}
body #solid-sound-upload-form .acf-checkbox-list input,body #solid-sound-upload-form .acf-radio-list input{accent-color:#f59e0b}
body #solid-sound-upload-form input[type=text],body #solid-sound-upload-form input[type=number],body #solid-sound-upload-form input[type=url],body #solid-sound-upload-form textarea,body #solid-sound-upload-form select,body #solid-sound-upload-form .select2-selection{background:#0b1220!important;border:1px solid rgba(255,255,255,.14)!important;border-radius:9px!important;color:#fff!important;min-height:46px!important;box-shadow:none!important;font-size:15px!important}
body #solid-sound-upload-form input::placeholder,body #solid-sound-upload-form textarea::placeholder{color:#64748b!important}
body #solid-sound-upload-form input:focus,body #solid-sound-upload-form textarea:focus,body #solid-sound-upload-form .select2-container--focus .select2-selection,body #solid-sound-upload-form .select2-container--open .select2-selection{border-color:#f59e0b!important;outline:none!important;box-shadow:0 0 0 3px rgba(245,158,11,.15)!important}
body #solid-sound-upload-form .select2-container{width:100%!important}
body #solid-sound-upload-form .select2-selection__rendered,body #solid-sound-upload-form .select2-selection__placeholder{color:#94a3b8!important;line-height:44px!important}
body #solid-sound-upload-form .select2-selection--single .select2-selection__rendered{color:#fff!important}
body #solid-sound-upload-form .select2-selection__arrow{height:44px!important}body #solid-sound-upload-form .select2-selection__arrow b{border-color:#f59e0b transparent transparent!important}
body #solid-sound-upload-form .select2-selection__choice{background:rgba(245,158,11,.15)!important;color:#fff!important;border:1px solid rgba(245,158,11,.4)!important;border-radius:6px!important}
body #solid-sound-upload-form .select2-search__field{color:#fff!important}
body:has(#solid-sound-upload-form) .select2-dropdown{background:#0f1626!important;border-color:rgba(255,255,255,.14)!important;color:#e2e8f0!important}
body:has(#solid-sound-upload-form) .select2-results__option{color:#e2e8f0!important}
body:has(#solid-sound-upload-form) .select2-results__option--highlighted{background:#f59e0b!important;color:#111!important}
body:has(#solid-sound-upload-form) .select2-search--dropdown .select2-search__field{background:#0b1220!important;color:#fff!important;border:1px solid rgba(255,255,255,.14)!important}
body #solid-sound-upload-form .ss-wm-row{align-items:end!important}
body #solid-sound-upload-form .ss-wm-row .acf-field{display:flex;flex-direction:column}
body #solid-sound-upload-form .ss-wm-row .acf-input{display:flex;align-items:center;gap:6px}
body #solid-sound-upload-form .ss-wm-row .acf-input>*:not(.acf-actions):not(a){flex:1 1 auto;min-width:0}
body #solid-sound-upload-form a[data-name=add],body #solid-sound-upload-form .acf-icon[data-name=add]{color:#f59e0b!important}
body #solid-sound-upload-form .ss-wm-ai{background:#0f1626!important;border:1px solid rgba(255,255,255,.08)}body #solid-sound-upload-form .ss-wm-ai .in{background:transparent!important}
body #solid-sound-upload-form .ss-wm-ai .bar{background:rgba(255,255,255,.08)!important}body #solid-sound-upload-form .ss-wm-ai .bar i{background:#f59e0b!important}
body #solid-sound-upload-form .ss-wm-ai .chips span{background:rgba(255,255,255,.05)!important;color:#94a3b8!important}body #solid-sound-upload-form .ss-wm-ai .chips span.ok{background:rgba(52,211,153,.15)!important;color:#34d399!important}
body #solid-sound-upload-form .ss-wm-drop{background:#0b1220!important;border:2px dashed rgba(245,158,11,.55)!important}
body #solid-sound-upload-form .ss-wm-drop:hover,body #solid-sound-upload-form .ss-wm-drop.over{background:rgba(245,158,11,.06)!important;border-color:#f59e0b!important}
body #solid-sound-upload-form .ss-wm-drop b{color:#fff!important}body #solid-sound-upload-form .ss-wm-drop small{color:#94a3b8!important}
body #solid-sound-upload-form .ss-wm-chips span{background:rgba(255,255,255,.04)!important}
body #solid-sound-upload-form .ss-wm-chips .c{color:#d8b4fe!important;border-color:#a855f7!important}body #solid-sound-upload-form .ss-wm-chips .z{color:#fcd34d!important;border-color:#f59e0b!important}body #solid-sound-upload-form .ss-wm-chips .m{color:#86efac!important;border-color:#22c55e!important}
body #solid-sound-upload-form .ss-wm-chips span.ok{color:#111!important}body #solid-sound-upload-form .ss-wm-chips .c.ok{background:#c084fc!important}body #solid-sound-upload-form .ss-wm-chips .z.ok{background:#f59e0b!important}body #solid-sound-upload-form .ss-wm-chips .m.ok{background:#4ade80!important}
body #solid-sound-upload-form .ss-wm-files{color:#cbd5e1!important}
body #solid-sound-upload-form .ss-wm-alt{color:#f59e0b!important}body #solid-sound-upload-form .ss-wm-note{color:#94a3b8!important}
</style>
<script>
(function(){
var form=document.getElementById('solid-sound-upload-form');if(!form||form.dataset.ssap)return;form.dataset.ssap='1';
var SYM=<?php echo wp_json_encode( $sym ); ?>;
function q(s,r){return (r||form).querySelector(s)}function qa(s,r){return [].slice.call((r||form).querySelectorAll(s))}
function el(t,c,h){var e=document.createElement(t);if(c)e.className=c;if(h!=null)e.innerHTML=h;return e}
function P(sel){var e=q(sel);return e?e.closest('p'):null}
function F(n){return q('.ss-ap-'+n)}
/* plain inputs */
var name=q('[name=product_name]'),desc=q('[name=product_description]'),price=q('[name=product_price]'),img=q('[name=product_image]'),file=q('[name=product_file]');
var pName=P('[name=product_name]'),pDesc=P('[name=product_description]'),pPrice=P('[name=product_price]'),pImg=P('[name=product_image]'),pCanvas=P('#productImagePreview'),pFile=P('[name=product_file]');
var submitBtn=q('button[type=submit]'),submitP=submitBtn.closest('p');
function relabel(p,t,req){if(!p)return;var s=p.querySelector('label strong')||p.querySelector('label');if(s)s.outerHTML='<span class="ss-wm-l">'+t+(req?' <span class="ss-req">*</span>':'')+'</span>'}
relabel(pName,'Product Name:',1);relabel(pDesc,'Description:',1);relabel(pPrice,'Price ('+SYM+'):',1);
[pName,pDesc,pPrice].forEach(function(p){if(p)p.classList.add('ss-wm-sec')});
if(desc){desc.rows=8;desc.required=true}
/* layout */
var top=el('div','ss-wm-top','<h2>Add Product</h2><div><button type="button" class="pri ss-wm-save">Send for review</button></div>');
var body=el('div','ss-wm-body');
var ai=el('div','ss-wm-ai','<div class="in"><div class="hd"><span>Content Completeness</span><b class="pc">0%</b></div><div class="bar"><i></i></div><div class="chips"></div></div>');
var drop=el('div','ss-wm-drop','<div class="ic">📂</div><b>Drop files here</b><small>or click to browse</small><div class="ss-wm-chips"><span class="c">🖼 JPG 1000×1000+ → Cover</span><span class="z">📦 ZIP → Project</span><span class="m">🎵 MP3 → Demo</span></div>');
var files=el('div','ss-wm-files');
var multi=el('input','ss-wm-hidden');multi.type='file';multi.multiple=true;multi.accept='image/*,.zip,.rar,.7z,audio/*';
var row3=el('div','ss-wm-row'),row2=el('div','ss-wm-row2');
form.insertBefore(top,form.firstChild);form.insertBefore(body,top.nextSibling);
function add(x,parent){if(x)(parent||body).appendChild(x)}
add(F('format'));add(F('genre'));add(ai);add(pName);add(pDesc);
add(row2);['daw','version','ext','mood','dl'].forEach(function(n){add(F(n),row2)});
add(pPrice);add(row3);['length','bpm','key'].forEach(function(n){add(F(n),row3)});
add(F('tag'));add(drop);add(files);add(multi);
[pImg,pCanvas,pFile].forEach(function(p){if(p)p.classList.add('ss-wm-hidden')});
var mp3=F('mp3');if(mp3){mp3.classList.add('ss-wm-hidden');var alt=el('span','ss-wm-alt','Prefer the media library for the MP3 demo?');alt.onclick=function(){mp3.classList.toggle('ss-wm-hidden');add(mp3)};add(alt)}
var fs=q('fieldset');if(fs&&!fs.querySelector('.acf-field:not(.ss-ap-hide)'))fs.classList.add('ss-wm-empty');
add(submitP);submitP.className='ss-wm-submit';submitBtn.textContent='Send for review';
var note=el('p','ss-wm-note','After you send it, we review the product (usually within 24 hours) and then publish it.');submitP.appendChild(note);
top.querySelector('.ss-wm-save').onclick=function(){if(form.requestSubmit)form.requestSubmit(submitBtn);else submitBtn.click()};
/* max hints + counters */
function maxify(n,max,label){var w=F(n);if(!w)return;var lab=w.querySelector('.acf-label label');if(lab&&label)lab.firstChild.nodeValue=label;if(lab&&max&&!w.querySelector('.ss-wm-max'))lab.insertAdjacentHTML('afterend','<span class="ss-wm-max">(max '+max+')</span>');if(max){var c=el('div','ss-wm-cnt','<b>0</b> / '+max+' selected');w.querySelector('.acf-label').appendChild(c);w.dataset.max=max}}
maxify('format',0,'Format:');maxify('genre',3,'Select Genre(s)');maxify('ext',4,'Plugins / Synths');maxify('mood',3,'Mood');
form.addEventListener('change',function(e){var w=e.target.closest&&e.target.closest('.acf-field');if(!w||!w.dataset.max||e.target.type!=='checkbox')return;var on=qa('input[type=checkbox]:checked',w);if(on.length>+w.dataset.max){e.target.checked=false;on.pop()}var c=w.querySelector('.ss-wm-cnt b');if(c)c.textContent=qa('input[type=checkbox]:checked',w).length});
/* per-format fields */
var FM={132:'daw version genre key bpm ext tag mp3',139:'daw version genre key bpm ext tag mp3',133:'genre mood ext tag mp3',145:'genre mood key bpm length tag mp3',sample:'genre mood key bpm dl tag mp3'};
var RQ={132:'daw version genre mp3',139:'daw version genre mp3',133:'genre ext mp3',145:'genre mp3',sample:'genre dl mp3'};
var ALL='daw version genre mood key bpm length ext dl tag mp3'.split(' ');
function fmtKey(){var w=F('format');if(!w)return '';var r=q('input[type=radio]:checked',w);var v='',t='';if(r){v=r.value;t=(r.closest('label')||{}).textContent||''}else{var s=q('select',w);if(s&&s.value){v=s.value;t=s.options[s.selectedIndex].text}}if(!v)return '';if(/sample/i.test(t))return 'sample';return FM[v]?v:'sample'}
function apply(){var k=fmtKey(),on=k?FM[k].split(' '):[],req=k?RQ[k].split(' '):[];ALL.forEach(function(n){var w=F(n);if(!w)return;var show=on.indexOf(n)>-1;if(n!=='mp3')w.classList.toggle('ss-ap-off',!show);qa('select,input,textarea',w).forEach(function(i){if(n==='mp3')return;i.disabled=!show});w.dataset.ssreq=req.indexOf(n)>-1?'1':'';var lab=w.querySelector('.acf-label label');if(lab){var star=lab.querySelector('.acf-required,.ss-req');if(req.indexOf(n)>-1&&!star){lab.appendChild(el('span','ss-req',' *'))}else if(req.indexOf(n)<0&&star&&star.classList.contains('ss-req'))star.remove()}});ss()}
form.addEventListener('change',function(e){if(e.target.closest&&e.target.closest('.ss-ap-format'))apply()});
if(window.jQuery)jQuery(form).on('change','.ss-ap-format select',apply);
/* files: one drop zone routes cover / project / demo */
function setFiles(input,f){try{var dt=new DataTransfer();dt.items.add(f);input.files=dt.files;input.dispatchEvent(new Event('change',{bubbles:true}))}catch(e){}}
var st={c:'',z:'',m:''};
function showFiles(){var h='';if(img&&img.files.length){h+='<div>🖼 Cover: <img src="'+URL.createObjectURL(img.files[0])+'" alt=""> '+img.files[0].name+'</div>'}if(file&&file.files.length)h+='<div>📦 Project: '+file.files[0].name+' ('+(file.files[0].size/1048576).toFixed(1)+' MB)</div>';if(st.m)h+='<div>🎵 Demo: '+st.m+'</div>';files.innerHTML=h;drop.querySelector('.c').classList.toggle('ok',!!(img&&img.files.length));drop.querySelector('.z').classList.toggle('ok',!!(file&&file.files.length));drop.querySelector('.m').classList.toggle('ok',/\.mp3/i.test(mp3Val()));ss()}
var nonce=null;function getNonce(){if(nonce)return Promise.resolve(nonce);return fetch('/wp-admin/admin-ajax.php?action=rest-nonce',{credentials:'same-origin'}).then(function(r){return r.text()}).then(function(t){nonce=t.trim();return nonce})}
function setMp3(url){var w=q('.ss-ap-mp3 textarea');var v='[audio mp3="'+url+'"][/audio]';if(!w)return;if(window.tinymce&&tinymce.get(w.id))tinymce.get(w.id).setContent(v);w.value=v}
function uploadMp3(f){st.m='uploading '+f.name+'…';showFiles();getNonce().then(function(n){return fetch('/wp-json/wp/v2/media',{method:'POST',credentials:'same-origin',headers:{'X-WP-Nonce':n,'Content-Disposition':'attachment; filename="'+f.name.replace(/[^\w.\-]+/g,'_')+'"','Content-Type':f.type||'audio/mpeg'},body:f})}).then(function(r){return r.json()}).then(function(j){if(j&&j.source_url){setMp3(j.source_url);st.m=f.name+' <audio controls preload="none" src="'+j.source_url+'"></audio>'}else{st.m='upload failed: '+((j&&j.message)||'error')+'. Use the media library link below.'}showFiles()}).catch(function(){st.m='upload failed. Use the media library link below.';showFiles()})}
function route(list){[].forEach.call(list,function(f){var n=f.name.toLowerCase();if(/^image\//.test(f.type)){setFiles(img,f)}else if(/\.mp3$/.test(n)){uploadMp3(f)}else{setFiles(file,f)}});showFiles()}
drop.onclick=function(){multi.click()};multi.onchange=function(){route(multi.files);multi.value=''};
['dragenter','dragover'].forEach(function(t){drop.addEventListener(t,function(e){e.preventDefault();drop.classList.add('over')})});
['dragleave','drop'].forEach(function(t){drop.addEventListener(t,function(e){e.preventDefault();drop.classList.remove('over')})});
drop.addEventListener('drop',function(e){if(e.dataTransfer&&e.dataTransfer.files)route(e.dataTransfer.files)});
if(img)img.required=false;if(file)file.required=false;
/* completeness */
function mp3Val(){var w=q('.ss-ap-mp3 textarea');if(!w)return '';if(window.tinymce&&tinymce.get(w.id))return tinymce.get(w.id).getContent();return w.value}
function has(n){var w=F(n);if(!w)return true;if(n==='mp3')return /\.mp3/i.test(mp3Val());var s=w.querySelector('select');if(s)return [].some.call(s.options,function(o){return o.selected&&o.value});return qa('input:not([type=hidden])',w).some(function(i){return i.checked||(i.type!='checkbox'&&i.type!='radio'&&i.value)})}
function ss(){var items=[['Name',!!(name&&name.value.trim())],['Format',!!fmtKey()],['Genre',has('genre')],['BPM',has('bpm')],['Key',has('key')],['Description',!!(desc&&desc.value.trim().length>20)],['Price',!!(price&&price.value)],['Cover',!!(img&&img.files.length)],['ZIP',!!(file&&file.files.length)],['MP3',has('mp3')]];var ok=items.filter(function(x){return x[1]}).length,p=Math.round(ok/items.length*100);var hh=items.map(function(x){return '<span'+(x[1]?' class="ok"':'')+'>'+x[0]+'</span>'}).join('');if(ai._h===hh)return;ai._h=hh;/* 16.11: only on change (was an endless re-render via ssOnDom) */ai.querySelector('.pc').textContent=p+'%';ai.querySelector('.bar i').style.width=p+'%';ai.querySelector('.chips').innerHTML=hh}
form.addEventListener('click',function(e){var li=e.target.closest&&e.target.closest('.acf-checkbox-list>li,.acf-radio-list>li');if(!li||e.target.tagName==='INPUT'||e.target.closest('label'))return;var i=li.querySelector('input');if(i&&!i.disabled)i.click()});form.addEventListener('input',ss);form.addEventListener('change',ss);window.ssOnDom?ssOnDom(ss):setInterval(ss,1500);apply();
/* checks before sending */
form.addEventListener('submit',function(e){
  qa('.ss-ap-err').forEach(function(x){x.remove()});qa('.ss-ap-bad').forEach(function(x){x.classList.remove('ss-ap-bad')});
  var bad=[];if(!fmtKey())bad.push([F('format'),'Please choose a format.']);
  ALL.forEach(function(n){var w=F(n);if(w&&w.dataset.ssreq==='1'&&!has(n))bad.push([n==='mp3'?drop:w,n==='mp3'?'Please add an MP3 demo.':'Please fill this in.'])});
  if(!(img&&img.files.length))bad.push([drop,'Please add a cover image (JPG).']);
  if(!(file&&file.files.length))bad.push([drop,'Please add the project file (ZIP).']);
  bad=bad.filter(function(b){return b[0]});
  if(bad.length){e.preventDefault();e.stopImmediatePropagation();bad.forEach(function(b){b[0].classList.add('ss-ap-bad');var m=el('div','ss-ap-err');m.textContent=b[1];(b[0]===drop?files:(b[0].querySelector('.acf-input')||b[0])).appendChild(m)});(bad[0][0]).scrollIntoView({behavior:'smooth',block:'center'})}
},true);
})();
</script>
		<?php
	}, 50 );

	/* Header Sign-in popup: logged-in users see account links; creators and admins also get "Creator Dashboard" */
	add_action( 'wp_footer', function () {
		if ( ! is_user_logged_in() ) return;
		$u    = wp_get_current_user();
		$dash = ( in_array( 'wcfm_vendor', (array) $u->roles, true ) || current_user_can( 'manage_woocommerce' ) ) && function_exists( 'get_wcfm_url' ) ? get_wcfm_url() : '';
		$acc  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
		$out  = wp_logout_url( home_url( '/' ) );
		$name = $u->first_name ? $u->first_name : $u->display_name;
		?>
<style>.ss-acc-pop{padding:6px 4px;text-align:center;color:#dfe3ee}.ss-acc-pop h3{margin:0 0 4px;color:#fff;font-size:20px}.ss-acc-pop p{margin:0 0 16px;color:#8f93a3;font-size:14px}.ss-acc-pop a{display:flex;align-items:center;justify-content:center;gap:8px;padding:13px 16px;border-radius:10px;font-weight:700;margin:0 0 10px;text-decoration:none!important}.ss-acc-pop a.d{background:#f5a400;color:#111!important}.ss-acc-pop a.m{border:1.5px solid #f5a400;color:#f5a400!important}.ss-acc-pop a.o{color:#8f93a3!important;font-weight:500;padding:8px}.ss-acc-hide{display:none!important}</style>
<script>
(function(){var D=<?php echo wp_json_encode( $dash ? esc_url( $dash ) : '' ); ?>,A=<?php echo wp_json_encode( esc_url( $acc ) ); ?>,O=<?php echo wp_json_encode( esc_url( $out ) ); ?>,N=<?php echo wp_json_encode( $name ); ?>;[].forEach.call(document.querySelectorAll('.l-header .w-popup .w-popup-box-content'),function(box){if(box.querySelector('.ss-acc-pop'))return;[].forEach.call(box.children,function(c){c.classList.add('ss-acc-hide')});var w=document.createElement('div');w.className='ss-acc-pop';w.innerHTML='<h3></h3><p>You are signed in.</p>'+(D?'<a class="d" href="'+D+'"><i class="fas fa-chart-line"></i> Creator Dashboard</a>':'')+'<a class="m" href="'+A+'"><i class="fas fa-user"></i> My account &amp; downloads</a><a class="o" href="'+O+'">Log out</a>';w.querySelector('h3').textContent='Hi, '+N;box.insertBefore(w,box.firstChild)});[].forEach.call(document.querySelectorAll('.l-header .w-popup-trigger'),function(b){var l=b.querySelector('.w-btn-label');if(l&&/sign/i.test(l.textContent))l.textContent='Account';var i=b.querySelector('i');if(i)i.className='fas fa-user'})})();
</script>
		<?php
	}, 5 );
}

/* Solid Sound 14.10.2026: narrow phones header (logo vs social icons) */

/* ═══ Solid Sound — 14.10.2026: WCFM dashboard reskin (WeMake-style) + creator Get Started ═══ */
if ( ! function_exists( 'ss_wd_is_page' ) ) {
	function ss_wd_is_page() { return function_exists( 'is_wcfm_page' ) && is_wcfm_page(); }

	function ss_wd_checklist() {
		if ( ! ( ( function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor() ) || current_user_can( 'manage_woocommerce' ) ) ) return '';
		global $wpdb;
		$id   = get_current_user_id();
		$prof = (array) get_user_meta( $id, 'wcfmmp_profile_settings', true );
		$pay  = isset( $prof['payment'] ) ? (array) $prof['payment'] : array();
		$s1 = ! empty( $prof['gravatar'] ) && ( ! empty( $prof['shop_description'] ) || ! empty( $prof['banner'] ) );
		$s2 = (bool) get_posts( array( 'post_type' => 'product', 'author' => $id, 'post_status' => array( 'publish', 'pending', 'draft', 'private' ), 'posts_per_page' => 1, 'fields' => 'ids' ) ); /* same list as Products (was count_user_posts: said Done with an empty list) */
		$s3 = ! empty( $pay['method'] ) && ( ! empty( $pay['paypal']['email'] ) || ! empty( $pay['bank']['iban'] ) || ! empty( $pay['bank']['ac_number'] ) || 'paypal' !== $pay['method'] );
		$t  = $wpdb->prefix . 'wcfm_marketplace_orders';
		$s4 = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$t} WHERE vendor_id = %d AND is_trashed = 0", $id ) ) > 0;
		$url = function_exists( 'get_wcfm_url' ) ? get_wcfm_url() : home_url( '/store-manager/' );
		$steps = array(
			array( $s1, 'Complete your profile', 'Logo, banner and a short bio build trust with buyers.', 'Edit profile', function_exists( 'ss_cp_url' ) ? ss_cp_url( 'ss-settings' ) : trailingslashit( $url ) . 'profile/' ),
			array( $s2, 'Add your first product', 'Upload files and an MP3 preview. We review it within 24 hours.', 'Add product', function_exists( 'ss_ep_url' ) ? ss_ep_url() : home_url( '/add-product/' ) ),
			array( $s3, 'Set up payouts', 'Add your PayPal email so we can pay you monthly (from $25).', 'Set up payouts', function_exists( 'ss_cp_url' ) ? ss_cp_url( 'ss-payouts' ) : trailingslashit( $url ) . 'profile/' ),
			array( $s4, 'Make your first sale', 'Share your store link: in your bio, under YouTube videos, in Discord.', '', '' ),
		);
		$done = count( array_filter( wp_list_pluck( $steps, 0 ) ) );
		$store = function_exists( 'wcfmmp_get_store_url' ) ? wcfmmp_get_store_url( $id ) : '';
		ob_start();
		if ( $done < 4 ) { ?>
<div class="ssw-card ssw-start"><div class="ssw-h"><b>Get Started</b><span><?php echo (int) $done; ?>/4 complete</span></div>
<div class="ssw-bar"><i style="width:<?php echo (int) ( $done * 25 ); ?>%"></i></div>
<?php foreach ( $steps as $i => $s ) { ?>
<div class="ssw-step<?php echo $s[0] ? ' done' : ''; ?>"><span class="ssw-ic"><?php echo $s[0] ? '✓' : ( $i + 1 ); ?></span><div><b><?php echo esc_html( $s[1] ); ?></b><p><?php echo esc_html( $s[2] ); ?></p></div><?php if ( $s[0] ) { ?><span class="ssw-done">Done</span><?php } elseif ( $s[3] ) { ?><a class="ssw-btn" href="<?php echo esc_url( $s[4] ); ?>"><?php echo esc_html( $s[3] ); ?></a><?php } ?></div>
<?php } ?></div>
<?php }
		if ( $store ) { ?>
<div class="ssw-card ssw-share"><div><b>Your profile page</b><p>Share it in your bio, under videos, in Discord — fans see only your products. Every sale from it counts.</p></div><input readonly value="<?php echo esc_attr( $store ); ?>" onclick="this.select()"><button type="button" class="ssw-btn" onclick="var i=this.previousElementSibling;i.select();try{navigator.clipboard.writeText(i.value)}catch(e){document.execCommand('copy')}this.textContent='Copied ✓'">Copy link</button><a class="ssw-btn ghost" href="<?php echo esc_url( $store ); ?>" target="_blank">View my profile</a></div>
<?php }
		$top = get_posts( array( 'post_type' => 'product', 'author' => $id, 'post_status' => 'publish', 'posts_per_page' => 3, 'meta_key' => 'total_sales', 'orderby' => 'meta_value_num', 'order' => 'DESC' ) );
		if ( $top ) { ?>
<div class="ssw-card"><div class="ssw-h"><b>Your Best Sellers</b></div><div class="ssw-tip">💡 Pro tip: update your best sellers regularly: refresh descriptions, add new previews or adjust prices.</div><div class="ssw-top">
<?php foreach ( $top as $n => $p ) { $pr = wc_get_product( $p->ID ); if ( ! $pr ) continue; ?>
<div class="ssw-prod<?php echo 0 === $n ? ' first' : ''; ?>"><?php if ( 0 === $n ) { ?><em>#1 BEST SELLER</em><?php } ?><?php echo $pr->get_image( array( 56, 56 ) ); ?><div><b><?php echo esc_html( $pr->get_name() ); ?></b><p><b style="display:inline"><?php echo (int) $pr->get_total_sales(); ?></b> sold · <strong><?php echo wp_kses_post( wc_price( $pr->get_price() ) ); ?></strong></p></div><a class="ssw-btn ghost" href="<?php echo esc_url( trailingslashit( $url ) . 'products-manage/' . $p->ID . '/' ); ?>">Edit</a></div>
<?php } ?></div></div>
<?php }
		?>
<div class="ssw-card"><div class="ssw-h"><b>Quick links</b></div><div class="ssw-tiles">
<a class="ssw-tile" href="<?php echo esc_url( trailingslashit( $url ) . 'ss-products/' ); ?>"><i>🎵</i><div><b>Products</b><span>Manage your catalog</span></div></a>
<a class="ssw-tile" href="<?php echo esc_url( trailingslashit( $url ) . 'ss-payouts/' ); ?>"><i>💳</i><div><b>Payouts</b><span>Get paid for your sales</span></div></a>
<a class="ssw-tile" href="<?php echo esc_url( trailingslashit( $url ) . 'ss-settings/' ); ?>"><i>👤</i><div><b>Profile</b><span>Edit your profile</span></div></a>
</div></div>
<?php
		return ob_get_clean();
	}

	add_action( 'wp_head', function () {
		if ( ! ss_wd_is_page() ) return; ?>
<style id="ss-wcfm-skin">
#wcfm-main-contentainer{margin-top:28px!important;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,sans-serif}
#wcfm-main-contentainer #wcfm-content{background:#0F1626!important;border-radius:14px;overflow:hidden}
#wcfm-main-contentainer .wcfm-collapse,#wcfm-main-contentainer .wcfm-collapse-content{background:#0F1626!important;color:#CBD5E1}
#wcfm_menu{width:104px!important;min-width:104px!important;flex:none!important;background:linear-gradient(180deg,#232b47 0%,#151a2d 100%)!important;border:0!important}
#wcfm_menu .wcfm_menu_logo{background:transparent!important;padding:14px 0!important;text-align:center;border:0!important}
#wcfm_menu .wcfm_menu_logo h4,#wcfm_menu .wcfm_menu_logo .wcfm_store_name,#wcfm_menu .wcfm_menu_logo a:not(.wcfm_store_logo_icon){display:none!important}
#wcfm_menu .wcfm_menu_logo img{width:52px!important;height:52px!important;border-radius:50%;background:#131B2E;object-fit:cover;margin:0 auto;display:block}
#wcfm_menu .wcfm_menu_items{background:transparent!important;border:0!important;margin:0!important;width:100%!important}
#wcfm_menu .wcfm_menu_items a.wcfm_menu_item{display:flex!important;flex-direction:column;align-items:center;justify-content:center;gap:6px;padding:14px 4px!important;text-align:center;background:transparent!important;color:rgba(255,255,255,.1)!important;border:0!important;height:auto!important;line-height:1.2!important}
#wcfm_menu .wcfm_menu_items a.wcfm_menu_item span.wcfmfa{width:38px!important;height:38px!important;line-height:36px!important;border:1.5px solid rgba(255,255,255,.55);border-radius:50%;font-size:16px!important;color:#fff!important;margin:0!important;display:inline-block!important;text-align:center;background:transparent!important}
#wcfm_menu .wcfm_menu_items a.wcfm_menu_item span.text{font-size:10px!important;font-weight:700!important;letter-spacing:.06em;text-transform:uppercase;color:rgba(255,255,255,.1)!important;margin:0!important;padding:0!important;display:block!important;white-space:normal}
#wcfm_menu .wcfm_menu_items a.wcfm_menu_item:hover,#wcfm_menu .wcfm_menu_items a.wcfm_menu_item.active{background:rgba(255,255,255,.1)!important;box-shadow:inset 4px 0 0 #fff}
#wcfm_menu .wcfm_menu_items a.wcfm_menu_item.active span.text,#wcfm_menu .wcfm_menu_items a.wcfm_menu_item:hover span.text{color:#fff!important}
#wcfm-main-contentainer .wcfm-page-headig{background:#131B2E!important;border-bottom:1px solid rgba(255,255,255,.1)!important;color:#E5E7EB!important;padding:18px 24px!important;height:auto!important;box-shadow:none!important}
#wcfm-main-contentainer .wcfm-page-headig .wcfm-page-heading-text{font-size:22px!important;font-weight:800!important;color:#E5E7EB!important}
#wcfm-main-contentainer .wcfm-page-headig>span.wcfmfa,#wcfm-main-contentainer .wcfm-page-headig .wcfm_menu_toggler{display:none!important}
#wcfm-main-contentainer .wcfm-page-headig .wcfm_header_panel a,#wcfm-main-contentainer .wcfm-page-headig .wcfm_header_panel span{color:#E5E7EB!important}
#wcfm-main-contentainer .wcfm-container,#wcfm-main-contentainer .wcfm_welcomebox_header,#wcfm-main-contentainer .page_collapsible,#wcfm-main-contentainer .wcfm-content,#wcfm-main-contentainer .wcfm_dashboard_wc_reports_sales,#wcfm-main-contentainer .wcfm_dashboard_wc_status .wcfm-container{background:#131B2E!important;border:1px solid rgba(255,255,255,.1)!important;border-radius:12px!important;box-shadow:0 1px 2px rgba(16,24,40,.04)!important;color:#CBD5E1!important}
#wcfm-main-contentainer h1,#wcfm-main-contentainer h2,#wcfm-main-contentainer h3{color:#E5E7EB!important}
.wcfm_welcomebox_header{display:none!important}
.wcfm_dashboard_stats{display:grid!important;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;margin:0 0 18px!important}
.wcfm_dashboard_stats>.wcfm_dashboard_stats_block,.wcfm_dashboard_stats .wcfm_dashboard_stats_block{width:auto!important;margin:0!important;float:none!important;background:#131B2E!important;border:1px solid rgba(255,255,255,.1)!important;border-radius:12px!important;box-shadow:0 1px 2px rgba(16,24,40,.04)!important;overflow:hidden;min-height:96px}
.wcfm_dashboard_stats_block>a{display:flex!important;align-items:center;gap:14px;padding:18px!important;background:#131B2E!important;text-decoration:none!important}
.wcfm_dashboard_stats_block span.wcfmfa{width:46px!important;height:46px!important;line-height:46px!important;border-radius:50%!important;background:rgba(245,158,11,.12)!important;color:#c98a00!important;font-size:18px!important;text-align:center;flex:none;margin:0!important;padding:0!important}
.wcfm_dashboard_stats_block>a>div{text-align:left!important;color:#94A3B8!important;font-size:12px!important;text-transform:uppercase;letter-spacing:.04em;padding:0!important;background:transparent!important}
.wcfm_dashboard_stats_block strong,.wcfm_dashboard_stats_block .amount{display:block;color:#E5E7EB!important;font-size:24px!important;font-weight:800!important;text-transform:none;letter-spacing:0}
.wcfm_dashboard_stats_block.wcfm_dashboard_stats_block_clear{display:none}
#wcfm-main-contentainer .wcfm_submit_button,#wcfm-main-contentainer a.add_new_wcfm_ele_dashboard{background:#D97706!important;color:#fff!important;border-color:#D97706!important;border-radius:8px!important}
.ssw{margin:0 0 18px;display:block!important;width:100%!important}
.ssw-btn,.ssw-done{white-space:nowrap}
#wcfm-main-contentainer a.ssw-btn,#wcfm-main-contentainer button.ssw-btn{width:auto!important;min-width:max-content!important;max-width:none!important;height:auto!important;padding:10px 18px!important;font-size:14px!important;line-height:1.3!important;text-align:center}
.ssw-card{background:#131B2E;border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:20px;margin:0 0 18px;color:#CBD5E1;box-shadow:0 1px 2px rgba(16,24,40,.04)}
.ssw-start{background:linear-gradient(120deg,#6a7ee8 0%,#7458c0 60%,#7a4fae 100%)!important;border:0!important;color:#fff;padding:26px 30px!important;border-radius:16px!important}
.ssw-h{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px}.ssw-h b{font-size:20px;color:#E5E7EB;font-weight:800}.ssw-h span{color:#94A3B8;font-size:14px}
.ssw-start .ssw-h b{font-size:26px;color:#fff}.ssw-start .ssw-h span{color:#fff;font-weight:600}
.ssw-bar{height:8px;background:rgba(255,255,255,.3);border-radius:9px;margin-bottom:18px;overflow:hidden}.ssw-bar i{display:block;height:100%;background:#131B2E;border-radius:9px}
.ssw-step{display:flex;align-items:center;gap:16px;padding:16px 20px;background:rgba(255,255,255,.14);border-radius:10px;margin-bottom:10px}
.ssw-step>div{flex:1;min-width:0}.ssw-step b{color:#fff;display:block;font-size:17px}.ssw-step p{margin:2px 0 0;color:rgba(255,255,255,.85);font-size:14px}
.ssw-ic{width:44px;height:44px;flex:none;border-radius:50%;display:grid;place-items:center;background:rgba(255,255,255,.22);color:#fff;font-weight:800;font-size:16px}
.ssw-step.done .ssw-ic{background:#2e9e68;color:#fff}.ssw-step.done b{text-decoration:line-through;opacity:.6}.ssw-step.done p{opacity:.6}
.ssw-step .ssw-done{flex:none;background:rgba(255,255,255,.18);color:rgba(255,255,255,.75);border-radius:8px;padding:9px 18px;font-weight:700;font-size:14px}
a.ssw-btn,button.ssw-btn,span.ssw-btn{flex:none;display:inline-block;background:#D97706!important;color:#fff!important;border:0;border-radius:8px;padding:9px 16px;font-weight:700;font-size:14px;cursor:pointer;text-decoration:none!important;line-height:1.3}
.ssw-start a.ssw-btn{background:#131B2E!important;color:#5a46a8!important}
a.ssw-btn.ghost{background:#131B2E!important;color:#D97706!important;border:1.5px solid #D97706}
.ssw-share{display:flex;flex-wrap:wrap;gap:10px;align-items:center}.ssw-share>div{flex:1 1 260px}.ssw-share b{color:#E5E7EB;font-size:17px}.ssw-share p,.ssw-prod p{margin:2px 0 0;color:#94A3B8;font-size:13px}
.ssw-share input{flex:1 1 240px;background:#0F1626!important;border:1px solid #e0e3ea!important;color:#CBD5E1!important;border-radius:8px;padding:9px 10px;font-size:13px}
.ssw-tip{background:#0F1626;border-radius:8px;padding:12px 14px;color:#CBD5E1;font-size:13px;margin:0 0 14px}
.ssw-top{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:12px}
.ssw-prod{position:relative;display:flex;align-items:center;gap:12px;background:#0F1626;border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:12px}
.ssw-prod.first{background:rgba(245,158,11,.12);border-color:#f5c542}
.ssw-prod img{width:58px;height:58px;border-radius:6px;object-fit:cover;flex:none}.ssw-prod>div{flex:1;min-width:0}.ssw-prod b{color:#E5E7EB;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.ssw-prod p strong{color:#1f9d55}
.ssw-prod em{position:absolute;top:-9px;left:10px;background:#f5c542;color:#3a2a00;font-style:normal;font-size:10px;font-weight:800;padding:2px 7px;border-radius:5px}
.ssw-prod a.ssw-btn{background:#0d5bd7!important;color:#fff!important;border:0;padding:10px 12px;font-size:11px;text-transform:uppercase}
.ssw-tiles{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px}
.ssw-tile{display:flex;align-items:center;gap:14px;background:#0F1626;border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:16px;text-decoration:none!important}
.ssw-tile i{font-size:28px;font-style:normal}.ssw-tile b{display:block;color:#E5E7EB;font-size:16px}.ssw-tile span{color:#94A3B8;font-size:13px}
@media(max-width:700px){#wcfm_menu{width:76px!important;min-width:76px!important}.ssw-step{flex-wrap:wrap}.ssw-step .ssw-btn{width:100%;text-align:center}.ssw-start{padding:18px!important}}
/* 15.11.2026 owner review */
#wcfm_menu .wcfm_menu_items a.wcfm_menu_item span.text{color:rgba(255,255,255,.62)!important}
.ssw-start{background:#131B2E!important;border:1px solid rgba(245,158,11,.35)!important}
.ssw-start .ssw-h span{color:#f59e0b!important}
.ssw-bar{background:rgba(255,255,255,.08)!important}.ssw-bar i{background:#f59e0b!important;min-width:8px}
.ssw-step{background:rgba(255,255,255,.04)!important;border:1px solid rgba(255,255,255,.06)}
.ssw-step>div,.ssw-step>div *{background:transparent!important}
.ssw-ic{background:rgba(245,158,11,.15)!important;color:#f59e0b!important}
.ssw-step.done .ssw-ic{background:#16a34a!important;color:#fff!important}
.ssw-start a.ssw-btn{background:#f59e0b!important;color:#111!important}
#wcfm-main-contentainer a.ssw-btn,#wcfm-main-contentainer button.ssw-btn,a.ssw-btn,button.ssw-btn{background:#f59e0b!important;color:#111!important}
@media(max-width:700px){.ssw-step{flex-wrap:wrap!important;padding:14px!important;gap:12px!important}.ssw-step>div{flex:1 1 calc(100% - 60px)!important}#wcfm-main-contentainer .ssw-step a.ssw-btn,.ssw-step .ssw-done{flex:1 1 100%!important;width:100%!important;text-align:center}.ssw-start .ssw-h b{font-size:21px!important}}
#wcfm-main-contentainer{margin-top:8px!important}
.l-main:has(#wcfm-main-contentainer)>.l-section:first-child,.l-main:has(#wcfm-main-contentainer) .l-section.height_auto:first-child{padding-top:12px!important}
#wcfm-main-contentainer .sscp-set label{display:block!important;width:100%!important;max-width:none!important;float:none!important}
#wcfm-main-contentainer .sscp-set textarea{width:100%!important;max-width:none!important;min-height:120px}
#wcfm-main-contentainer .sscp-set input:not([type=file]),#wcfm-main-contentainer .sscp-set select{width:100%!important;max-width:none!important;min-height:44px;border-radius:8px!important}
#wcfm-main-contentainer .sscp-up .cv.ph{display:flex!important;align-items:center;justify-content:center;border:1.5px dashed rgba(255,255,255,.2);color:#64748b;font-size:12px;font-weight:600}
#wcfm-main-contentainer .sscp-up .btnf{color:#e2e8f0!important;background:rgba(255,255,255,.04)}
#wcfm-main-contentainer .sscp-up .btnf:hover{border-color:#f59e0b!important;color:#fff!important}
</style>
	<?php } );

	add_action( 'wp_footer', function () {
		if ( ! ss_wd_is_page() ) return;
		$html = ss_wd_checklist();
		if ( ! $html ) return; ?>
<template id="ssw-tpl"><div class="ssw"><?php echo $html; ?></div></template>
<script>(function(){var t=document.getElementById('ssw-tpl'),w=document.querySelector('.wcfm_welcomebox_header'),st=document.querySelector('.wcfm_dashboard_stats');if(!t||!w||document.querySelector('.ssw'))return;var f=t.content.cloneNode(true),box=f.querySelector('.ssw');w.parentNode.insertBefore(f,w.nextSibling);box=document.querySelector('.ssw');var start=box.querySelector('.ssw-start');if(st){var after=document.createElement('div');after.className='ssw';[].slice.call(box.children).forEach(function(c){if(c!==start)after.appendChild(c)});st.parentNode.insertBefore(after,st.nextSibling)}var h=document.querySelector('.wcfm-page-heading-text');if(h&&/dashboard/i.test(h.textContent))h.textContent='Marketplace Dashboard'})();</script>
	<?php }, 30 );
}

/* 15.11.2026 owner review: clearer stat labels for producers; no empty sales chart (its axis went below zero) */
add_action( 'wp_footer', function () {
	if ( ! function_exists( 'ss_wd_is_page' ) || ! ss_wd_is_page() ) return; ?>
<script>(function(){var lab=false,chart=false,zero=null;
function go(){var st=document.querySelector('.wcfm_dashboard_stats');if(!st)return false;
 if(!lab){var map=[[/commission in last 7 days/i,'Your earnings · last 7 days'],[/gross sales in last 7 days/i,'Sales · last 7 days'],[/sold in last 7 days/i,'items sold · last 7 days'],[/received in last 7 days/i,'orders · last 7 days']];
  var w=document.createTreeWalker(st,NodeFilter.SHOW_TEXT),n;while((n=w.nextNode())){map.forEach(function(m){if(m[0].test(n.nodeValue))n.nodeValue=n.nodeValue.replace(m[0],m[1])})}lab=true}
 if(zero===null){var t=st.textContent;zero=!/[1-9]/.test((t.match(/\$\s?[\d.,]+/g)||[]).join(''))&&!/\b[1-9]\d*\s*orders?/i.test(t)}
 if(false){/* 15.11 removed: hiding the WCFM Chart.js chart made browsers run Out of Memory */var cv=document.querySelector('#wcfm-main-contentainer canvas');if(cv){var c=cv.closest('.wcfm_dashboard_wc_reports_sales')||cv.closest('.wcfm-container')||cv.parentNode;c.style.display='none';var h=c.previousElementSibling;if(h&&/collapsible|wcfm_dashboard_wc_reports_sales_heading/.test(h.className))h.style.display='none';chart=true}}
 return lab}
if(!go()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(go())mo.disconnect()})});mo.observe(document.body,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},12000)}})();</script>
<?php }, 40 );

/* ═══ Solid Sound — 14.10.2026: "Add Product" inside the WCFM dashboard (WeMake-style) ═══ */
if ( ! function_exists( 'ss_ep_url' ) ) {
	function ss_ep_url() { return function_exists( 'get_wcfm_url' ) ? trailingslashit( get_wcfm_url() ) . 'ss-add-product/' : home_url( '/add-product/' ); }
	add_filter( 'wcfm_query_vars', function ( $q ) { $q['wcfm-ss-add-product'] = 'ss-add-product'; return $q; }, 50 );
	add_filter( 'wcfm_endpoint_title', function ( $t, $ep ) { return 'wcfm-ss-add-product' === $ep ? 'Add Product' : $t; }, 50, 2 );
	add_action( 'init', function () {
		add_rewrite_endpoint( 'ss-add-product', EP_ROOT | EP_PAGES );
		if ( '2' !== get_option( 'ss_wcfm_ep_v' ) ) { flush_rewrite_rules( false ); update_option( 'ss_wcfm_ep_v', '2' ); }
	}, 99 );
	add_filter( 'wcfm_menus', function ( $m ) {
		return array( 'ss-add-product' => array( 'label' => 'Add Product', 'url' => ss_ep_url(), 'icon' => 'plus-circle', 'priority' => 1 ) ) + (array) $m;
	}, 50 );
	add_action( 'wcfm_load_views', function ( $ep ) {
		if ( 'wcfm-ss-add-product' !== $ep || ! function_exists( 'solid_sound_add_product_form_callback' ) ) return;
		echo '<div class="collapse wcfm-collapse" id="wcfm_ss_add_product"><div class="wcfm-page-headig"><span class="wcfm-page-heading-text">Marketplace Add New Product</span></div><div class="wcfm-collapse-content">';
		echo solid_sound_add_product_form_callback(); // phpcs:ignore
		echo '</div></div>';
	}, 50 );
	add_action( 'template_redirect', function () {
		if ( is_user_logged_in() && function_exists( 'get_wcfm_url' ) && is_page( 'add-product' ) ) { wp_safe_redirect( ss_ep_url() ); exit; }
	}, 5 );
}

/* ═══ Solid Sound — 14.10.2026: WCFM side menu order like WeMake ═══ */
if ( ! function_exists( 'ss_menu_order_js' ) ) {
	function ss_menu_order_js() {
		if ( ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return; ?>
<style>
#wcfm_menu .wcfm_menu_items a.wcfm_menu_item span.wcfmfa{border:0!important;background:rgba(255,255,255,.1)!important;width:44px!important;height:44px!important;line-height:44px!important;font-size:18px!important}
#wcfm_menu .wcfm_menu_items a.wcfm_menu_item.active,#wcfm_menu .wcfm_menu_items.ss-cur a.wcfm_menu_item{background:linear-gradient(135deg,#F59E0B,#D97706)!important;box-shadow:inset -5px 0 0 #fff!important}
#wcfm_menu .wcfm_menu_items a.wcfm_menu_item.active span.wcfmfa{background:rgba(255,255,255,.22)!important}
#wcfm_menu .wcfm_menu_items a.wcfm_menu_item:hover{box-shadow:none}
#wcfm_menu .wcfm_menu_items.ss-soon a.wcfm_menu_item{opacity:.55}
</style>
<script>
(function(){var m=document.getElementById('wcfm_menu');if(!m)return;var box=m.querySelector('.wcfm_menu_items')&&m.querySelector('.wcfm_menu_items').parentNode;if(!box)return;
var items=[].slice.call(box.querySelectorAll(':scope > .wcfm_menu_items'));
function slug(d){var a=d.querySelector('a');if(!a)return '?';var h=(a.getAttribute('href')||'');if(/logout/.test(h))return 'logout';var x=h.split('/store-manager/')[1];if(x===undefined)return '?';return x.replace(/\/.*$/,'')}
var by={};items.forEach(function(d){by[slug(d)]=d});
function pick(keys){for(var i=0;i<keys.length;i++)if(by[keys[i]])return by[keys[i]];return null}
function lab(d,t,ic){if(!d)return;var s=d.querySelector('.text');if(s)s.textContent=t;if(ic){var i=d.querySelector('span.wcfmfa');if(i)i.className='wcfmfa '+ic}}
function mk(t,ic,href,soon){var d=tpl.cloneNode(true);d.classList.remove('active');var a=d.querySelector('a');a.classList.remove('active');a.href=href;lab(d,t,ic);if(soon){d.classList.add('ss-soon');a.title='Coming soon';a.addEventListener('click',function(e){e.preventDefault();alert(t+': coming soon')})}return d}
var tpl=pick(['reviews','settings','products','']);if(!tpl)return;
var seq=[[['ss-add-product'],'Add Product','fa-plus-circle'],[[''],'Dashboard','fa-home'],['stub','Products','fa-play-circle','/store-manager/ss-products/'],['stub','Earnings','fa-dollar-sign','/store-manager/ss-earnings/'],['stub','Payouts','fa-credit-card','/store-manager/ss-payouts/'],['stub','Reviews','fa-star','/store-manager/ss-reviews/'],['stub','Settings','fa-cog','/store-manager/ss-settings/'],['stub','Help / FAQ','fa-question-circle','/store-manager/ss-help/']];
var used=[];seq.forEach(function(o){var d;if(o[0]==='stub')d=mk(o[1],o[2],o[3],false);else if(o[0]==='soon')d=mk(o[1],o[2],'#',true);else if(o[0]==='help'){d=mk(o[1],o[2],'/sell/#faq',false);d.querySelector('a').target='_blank'}else{d=pick(o[0]);if(!d&&o[3])d=mk(o[1],o[2],o[3],false);if(!d)return;lab(d,o[1],o[2])}box.appendChild(d);used.push(d)});
items.forEach(function(d){if(used.indexOf(d)>-1||slug(d)==='logout')return;var sl=slug(d),t=d.querySelector('.text');if(t&&['products','payments','reviews','settings','profile','reports-sales-by-date','withdrawal','withdrawal-requests'].indexOf(sl)>-1&&!/^WCFM /.test(t.textContent))t.textContent='WCFM '+t.textContent.trim();box.appendChild(d)});
if(by.logout)box.appendChild(by.logout);var path=location.pathname.replace(/\/+$/,'');[].forEach.call(box.querySelectorAll(':scope > .wcfm_menu_items'),function(d){var a=d.querySelector('a'),hp=a?(a.pathname||'').replace(/\/+$/,''):'';if(hp&&(/\/store-manager$/.test(hp)?path===hp:path.indexOf(hp)===0)){d.classList.add('ss-cur');[].forEach.call(box.querySelectorAll('a.active'),function(x){if(x!==a)x.classList.remove('active')});a.classList.add('active')}});
})();
</script>
	<?php }
	add_action( 'wp_footer', 'ss_menu_order_js', 40 );
}

/* ═══ Solid Sound — 14.10.2026: placeholder dashboard pages (Bundles, Albums, Videos) ═══ */
if ( ! function_exists( 'ss_stub_pages' ) ) {
	function ss_stub_pages() {
		return array(
			'ss-bundles' => array( 'My Bundles', '+ Create new bundle', 'Package several of your products into one bundle at a lower price. Buyers get more, you sell more.' ),
			'ss-albums'  => array( 'My Albums', '+ Create new album', 'Group your tracks or sample packs into an album with one cover and one page.' ),
			'ss-videos'  => array( 'My Videos', '+ Create promo video', 'Turn a product into a short promo video for YouTube, Instagram and TikTok in one click.' ),
		);
	}
	add_filter( 'wcfm_query_vars', function ( $q ) { foreach ( array_keys( ss_stub_pages() ) as $s ) { $q[ 'wcfm-' . $s ] = $s; } return $q; }, 50 );
	add_action( 'init', function () {
		foreach ( array_keys( ss_stub_pages() ) as $s ) add_rewrite_endpoint( $s, EP_ROOT | EP_PAGES );
		if ( '1' !== get_option( 'ss_wcfm_stub_v' ) ) { flush_rewrite_rules( false ); update_option( 'ss_wcfm_stub_v', '1' ); }
	}, 99 );
	add_action( 'wcfm_load_views', function ( $ep ) {
		$pages = ss_stub_pages();
		$s = preg_replace( '/^wcfm-/', '', (string) $ep );
		if ( ! isset( $pages[ $s ] ) ) return;
		$p = $pages[ $s ];
		echo '<div class="collapse wcfm-collapse"><div class="wcfm-page-headig"><span class="wcfm-page-heading-text">' . esc_html( $p[0] ) . '</span></div><div class="wcfm-collapse-content"><div class="ssw-card" style="max-width:900px;margin:24px auto"><div class="ssw-h"><b>' . esc_html( $p[0] ) . '</b><span class="ssw-btn" style="opacity:.5;cursor:default">' . esc_html( $p[1] ) . '</span></div><p style="color:#94A3B8;margin:0 0 18px">' . esc_html( $p[2] ) . '</p><div style="border:1px dashed rgba(255,255,255,.1);border-radius:10px;padding:34px;text-align:center;color:#94A3B8"><b style="display:block;color:#E5E7EB;font-size:17px;margin-bottom:4px">Coming soon</b>We are building this feature. You will get an email when it is ready.</div></div></div></div>';
	}, 50 );
}

/* ═══ Solid Sound — 15.10.2026: WeMake-style creator pages inside WCFM (Products, Earnings, Payouts, Reviews, Settings, Help) ═══ */
if ( ! function_exists( 'ss_cp_pages' ) ) {
	function ss_cp_pages() {
		return array(
			'ss-products' => 'Marketplace Product List',
			'ss-earnings' => 'Payout History',
			'ss-payouts'  => 'Payouts',
			'ss-reviews'  => 'My Reviews',
			'ss-settings' => 'Marketplace Edit Seller Profile',
			'ss-help'     => 'Help / FAQ',
		);
	}
	function ss_cp_url( $s ) { return function_exists( 'get_wcfm_url' ) ? trailingslashit( get_wcfm_url() ) . $s . '/' : home_url( '/' ); }
	function ss_cp_can() { return is_user_logged_in() && ( ( function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor() ) || current_user_can( 'manage_woocommerce' ) ); }
	function ss_cp_money( $v ) { return function_exists( 'wc_price' ) ? wc_price( (float) $v ) : '$' . number_format( (float) $v, 2 ); }
	function ss_cp_prof( $id ) { $p = get_user_meta( $id, 'wcfmmp_profile_settings', true ); return is_array( $p ) ? $p : array(); }

	add_filter( 'wcfm_query_vars', function ( $q ) { foreach ( array_keys( ss_cp_pages() ) as $s ) { $q[ 'wcfm-' . $s ] = $s; } return $q; }, 50 );
	add_action( 'init', function () {
		foreach ( array_keys( ss_cp_pages() ) as $s ) add_rewrite_endpoint( $s, EP_ROOT | EP_PAGES );
		if ( '1' !== get_option( 'ss_wcfm_cp_v' ) ) { flush_rewrite_rules( false ); update_option( 'ss_wcfm_cp_v', '1' ); }
	}, 99 );

	/* commission data from WCFM Marketplace */
	function ss_cp_cols() {
		static $c = null; if ( null !== $c ) return $c;
		global $wpdb; $t = $wpdb->prefix . 'wcfm_marketplace_orders';
		$c = array();
		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $t ) ) === $t ) { foreach ( (array) $wpdb->get_results( "SHOW COLUMNS FROM {$t}" ) as $r ) $c[] = $r->Field; }
		return $c;
	}
	function ss_cp_stats( $id, $from = '', $to = '' ) {
		global $wpdb; $t = $wpdb->prefix . 'wcfm_marketplace_orders'; $c = ss_cp_cols();
		$o = array( 'total' => 0, 'available' => 0, 'pending' => 0, 'paid' => 0, 'gross' => 0, 'count' => 0 );
		if ( ! $c ) return $o;
		$com  = in_array( 'total_commission', $c, true ) ? 'total_commission' : 'commission_amount';
		$item = in_array( 'item_total', $c, true ) ? 'item_total' : ( in_array( 'item_sub_total', $c, true ) ? 'item_sub_total' : $com );
		$w = $wpdb->prepare( 'vendor_id = %d', $id );
		if ( in_array( 'is_trashed', $c, true ) ) $w .= ' AND is_trashed = 0';
		if ( in_array( 'is_refunded', $c, true ) ) $w .= ' AND is_refunded = 0';
		if ( $from && in_array( 'created', $c, true ) ) $w .= $wpdb->prepare( ' AND created >= %s AND created < %s', $from, $to );
		$ws = in_array( 'withdraw_status', $c, true );
		$r = $wpdb->get_row( "SELECT COUNT(*) n, SUM({$com}) total, SUM({$item}) gross"
			. ( $ws ? ", SUM(CASE WHEN withdraw_status IN ('pending','') THEN {$com} ELSE 0 END) avail, SUM(CASE WHEN withdraw_status IN ('requested','processing') THEN {$com} ELSE 0 END) pend, SUM(CASE WHEN withdraw_status = 'completed' THEN {$com} ELSE 0 END) paid" : '' )
			. " FROM {$t} WHERE {$w}" );
		if ( $r ) { $o = array( 'total' => (float) $r->total, 'gross' => (float) $r->gross, 'count' => (int) $r->n, 'available' => (float) ( $ws ? $r->avail : 0 ), 'pending' => (float) ( $ws ? $r->pend : 0 ), 'paid' => (float) ( $ws ? $r->paid : 0 ) ); }
		return $o;
	}
	function ss_cp_sold( $pid ) {
		global $wpdb; $t = $wpdb->prefix . 'wcfm_marketplace_orders'; $c = ss_cp_cols();
		if ( ! $c ) return array( (int) get_post_meta( $pid, 'total_sales', true ), 0 );
		$com = in_array( 'total_commission', $c, true ) ? 'total_commission' : 'commission_amount';
		$qty = in_array( 'quantity', $c, true ) ? 'SUM(quantity)' : 'COUNT(*)';
		$r = $wpdb->get_row( $wpdb->prepare( "SELECT {$qty} q, SUM({$com}) e FROM {$t} WHERE product_id = %d" . ( in_array( 'is_trashed', $c, true ) ? ' AND is_trashed = 0' : '' ), $pid ) );
		return array( $r ? (int) $r->q : 0, $r ? (float) $r->e : 0 );
	}
	function ss_cp_payout_set( $id ) {
		$p = ss_cp_prof( $id ); $pay = isset( $p['payment'] ) ? (array) $p['payment'] : array();
		$m = isset( $pay['method'] ) ? $pay['method'] : '';
		if ( 'paypal' === $m ) return ! empty( $pay['paypal']['email'] );
		if ( 'bank_transfer' === $m ) return ! empty( $pay['bank']['iban'] ) || ! empty( $pay['bank']['ac_number'] );
		return false;
	}

	/* saving: payouts, settings, review replies */
	add_action( 'template_redirect', function () {
		if ( empty( $_POST['ss_cp_action'] ) || ! ss_cp_can() ) return;
		$act = sanitize_key( $_POST['ss_cp_action'] );
		if ( ! isset( $_POST['_sscp'] ) || ! wp_verify_nonce( $_POST['_sscp'], 'ss_cp_' . $act ) ) return;
		$id = get_current_user_id(); $p = ss_cp_prof( $id ); $back = wp_get_referer() ? wp_get_referer() : home_url( '/' );
		if ( 'payout' === $act ) {
			$m = 'bank_transfer' === ( $_POST['method'] ?? '' ) ? 'bank_transfer' : 'paypal';
			$pay = isset( $p['payment'] ) ? (array) $p['payment'] : array();
			$pay['method'] = $m;
			if ( 'paypal' === $m ) {
				$e = sanitize_email( wp_unslash( $_POST['paypal_email'] ?? '' ) );
				if ( ! is_email( $e ) ) { wp_safe_redirect( add_query_arg( 'ss_msg', 'bademail', $back ) ); exit; }
				$pay['paypal'] = array( 'email' => $e );
			} else {
				$b = isset( $pay['bank'] ) ? (array) $pay['bank'] : array();
				foreach ( array( 'ac_name', 'bank_name', 'iban', 'swift' ) as $k ) $b[ $k ] = sanitize_text_field( wp_unslash( $_POST[ 'bank_' . $k ] ?? '' ) );
				$pay['bank'] = $b;
			}
			$p['payment'] = $pay; update_user_meta( $id, 'wcfmmp_profile_settings', $p );
			wp_safe_redirect( add_query_arg( 'ss_msg', 'saved', remove_query_arg( 'ss_msg', $back ) ) ); exit;
		}
		if ( 'settings' === $act ) {
			$name = sanitize_text_field( wp_unslash( $_POST['store_name'] ?? '' ) );
			if ( $name ) { $p['store_name'] = $name; update_user_meta( $id, 'store_name', $name ); update_user_meta( $id, 'wcfmmp_store_name', $name ); }
			$p['shop_description'] = wp_kses_post( wp_unslash( $_POST['bio'] ?? '' ) );
			$p['phone'] = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
			$addr = isset( $p['address'] ) ? (array) $p['address'] : array(); $addr['country'] = sanitize_text_field( wp_unslash( $_POST['country'] ?? '' ) ); $p['address'] = $addr;
			$soc = isset( $p['social'] ) ? (array) $p['social'] : array();
			foreach ( array( 'instagram', 'youtube', 'soundcloud', 'spotify', 'tiktok', 'fb' ) as $k ) $soc[ $k ] = esc_url_raw( wp_unslash( $_POST[ 'social_' . $k ] ?? '' ) );
			$p['social'] = $soc;
			$seo = isset( $p['store_seo'] ) ? (array) $p['store_seo'] : array();
			$seo['wcfmmp-seo-meta-desc'] = sanitize_text_field( wp_unslash( $_POST['meta_desc'] ?? '' ) );
			$seo['wcfmmp-seo-meta-keywords'] = sanitize_text_field( wp_unslash( $_POST['meta_keys'] ?? '' ) );
			$p['store_seo'] = $seo;
			require_once ABSPATH . 'wp-admin/includes/file.php'; require_once ABSPATH . 'wp-admin/includes/image.php'; require_once ABSPATH . 'wp-admin/includes/media.php';
			foreach ( array( 'gravatar' => 'avatar_file', 'banner' => 'cover_file' ) as $key => $f ) {
				if ( empty( $_FILES[ $f ]['name'] ) ) continue;
				$aid = media_handle_upload( $f, 0 );
				if ( ! is_wp_error( $aid ) ) $p[ $key ] = $aid;
			}
			update_user_meta( $id, 'wcfmmp_profile_settings', $p );
			wp_safe_redirect( add_query_arg( 'ss_msg', 'saved', remove_query_arg( 'ss_msg', $back ) ) ); exit;
		}
		if ( 'reply' === $act ) {
			$cid = absint( $_POST['comment_id'] ?? 0 ); $c = get_comment( $cid ); $txt = trim( wp_kses_post( wp_unslash( $_POST['reply'] ?? '' ) ) );
			if ( $c && $txt && ( (int) get_post_field( 'post_author', $c->comment_post_ID ) === $id || current_user_can( 'manage_woocommerce' ) ) ) {
				$u = wp_get_current_user();
				wp_insert_comment( array( 'comment_post_ID' => $c->comment_post_ID, 'comment_parent' => $cid, 'comment_content' => $txt, 'user_id' => $id, 'comment_author' => $u->display_name, 'comment_author_email' => $u->user_email, 'comment_approved' => 1, 'comment_type' => 'review' ) );
			}
			wp_safe_redirect( add_query_arg( 'ss_msg', 'replied', remove_query_arg( 'ss_msg', $back ) ) ); exit;
		}
	}, 1 );

	function ss_cp_notice() {
		$m = isset( $_GET['ss_msg'] ) ? sanitize_key( $_GET['ss_msg'] ) : '';
		$t = array( 'saved' => 'Saved.', 'replied' => 'Your reply is published.', 'bademail' => 'Please enter a valid PayPal email.' );
		return isset( $t[ $m ] ) ? '<div class="sscp-msg' . ( 'bademail' === $m ? ' err' : '' ) . '">' . esc_html( $t[ $m ] ) . '</div>' : '';
	}
	function ss_cp_cards( $s ) {
		return '<div class="sscp-cards">'
			. '<div class="sscp-c g"><span>Total earnings</span><b>' . ss_cp_money( $s['total'] ) . '</b><em>Your share of all sales</em></div>'
			. '<div class="sscp-c d"><span>Available balance</span><b>' . ss_cp_money( $s['available'] ) . '</b><em>Earned — not yet paid. Min. $25 for payout</em></div>'
			. '<div class="sscp-c y"><span>Pending balance</span><b>' . ss_cp_money( $s['pending'] ) . '</b><em>Requested — awaiting payment</em></div>'
			. '<div class="sscp-c b"><span>Total paid out</span><b>' . ss_cp_money( $s['paid'] ) . '</b><em>Lifetime payments received</em></div></div>';
	}
	function ss_cp_payout_cta( $id ) {
		if ( ss_cp_payout_set( $id ) ) return '';
		return '<div class="sscp-warn"><b>🔒 Set up your payouts</b><p>Add your PayPal email or bank details so we can pay you for your sales.</p><a class="ssw-btn" href="' . esc_url( ss_cp_url( 'ss-payouts' ) ) . '">Set Up Payouts</a></div>';
	}

	function ss_cp_render( $s ) {
		$id = get_current_user_id();
		$o = ss_cp_notice();
		if ( 'ss-products' === $s ) {
			$ids = get_posts( array( 'post_type' => 'product', 'author' => $id, 'post_status' => array( 'publish', 'pending', 'draft', 'private' ), 'posts_per_page' => 200, 'fields' => 'ids' ) );
			$rows = array();
			foreach ( $ids as $pid ) { $pr = wc_get_product( $pid ); if ( ! $pr ) continue; list( $q, $e ) = ss_cp_sold( $pid ); $rows[] = array( 'p' => $pr, 'q' => $q, 'e' => $e, 'st' => get_post_status( $pid ) ); }
			$best = $rows; usort( $best, function ( $a, $b ) { return $b['q'] <=> $a['q']; } ); $best = array_slice( array_filter( $best, function ( $r ) { return 'publish' === $r['st']; } ), 0, 6 );
			$o .= '<div class="ssw-card"><div class="ssw-h"><b>Your Best Sellers</b><a class="ssw-btn" href="' . esc_url( ss_cp_url( 'ss-add-product' ) ) . '">+ Add Product</a></div>';
			if ( $best ) { $o .= '<div class="sscp-best">'; $n = 0; foreach ( $best as $r ) { $n++; $o .= '<div class="sscp-bi"><span class="rk r' . min( $n, 4 ) . '">' . $n . '</span>' . $r['p']->get_image( array( 80, 80 ) ) . '<div><b>' . esc_html( $r['p']->get_name() ) . '</b><p>Uploaded: <i>' . esc_html( get_the_date( 'M j, Y', $r['p']->get_id() ) ) . '</i> · Sold: <i class="bl">' . (int) $r['q'] . '</i> · Earned: <i class="gr">' . wp_strip_all_tags( ss_cp_money( $r['e'] ) ) . '</i></p></div></div>'; } $o .= '</div>'; }
			else $o .= '<p class="sscp-empty">No sales yet. Your best sellers will appear here.</p>';
			$o .= '</div><div class="ssw-card sscp-tw"><table class="sscp-t"><thead><tr><th>Thumbnail</th><th>Name</th><th>Status</th><th>Price</th><th>Qty sold</th><th>Earned</th><th>Actions</th></tr></thead><tbody>';
			if ( ! $rows ) $o .= '<tr><td colspan="7" class="sscp-empty">You have no products yet. <a href="' . esc_url( ss_cp_url( 'ss-add-product' ) ) . '">Add your first product</a>.</td></tr>';
			$lbl = array( 'publish' => 'Live', 'pending' => 'In review', 'draft' => 'Draft', 'private' => 'Hidden' );
			foreach ( $rows as $r ) { $p = $r['p']; $o .= '<tr><td>' . $p->get_image( array( 48, 48 ) ) . '</td><td><b>' . esc_html( $p->get_name() ) . '</b></td><td><span class="sscp-st ' . esc_attr( $r['st'] ) . '">' . esc_html( $lbl[ $r['st'] ] ?? $r['st'] ) . '</span>' . ( function_exists( 'ss_cr_status_note' ) ? ss_cr_status_note( $p->get_id(), $r['st'] ) : '' ) . '</td><td>' . wp_kses_post( wc_price( $p->get_price() ) ) . '</td><td>' . (int) $r['q'] . '</td><td class="gr">' . wp_kses_post( ss_cp_money( $r['e'] ) ) . '</td><td><a class="ssw-btn sm" href="' . esc_url( trailingslashit( get_wcfm_url() ) . 'products-manage/' . $p->get_id() . '/' ) . '">Edit</a> ' . ( 'publish' === $r['st'] ? '<a class="ssw-btn ghost sm" target="_blank" href="' . esc_url( get_permalink( $p->get_id() ) ) . '">View</a>' : '' ) . '</td></tr>'; }
			$o .= '</tbody></table></div>';
		}
		if ( 'ss-earnings' === $s ) {
			$o .= ss_cp_cards( ss_cp_stats( $id ) ) . ss_cp_payout_cta( $id );
			$ym = isset( $_GET['m'] ) && preg_match( '/^\d{4}-\d{2}$/', $_GET['m'] ) ? $_GET['m'] : current_time( 'Y-m' );
			$from = $ym . '-01 00:00:00'; $to = gmdate( 'Y-m-01 00:00:00', strtotime( $from . ' +1 month' ) );
			$prev = gmdate( 'Y-m', strtotime( $from . ' -1 month' ) ); $next = gmdate( 'Y-m', strtotime( $from . ' +1 month' ) );
			$m = ss_cp_stats( $id, $from, $to );
			$o .= '<div class="ssw-card"><div class="ssw-h"><b>Sales</b><span class="sscp-nav"><a href="' . esc_url( add_query_arg( 'm', $prev ) ) . '">← Previous</a><strong>' . esc_html( date_i18n( 'F Y', strtotime( $from ) ) ) . '</strong>' . ( $next <= current_time( 'Y-m' ) ? '<a href="' . esc_url( add_query_arg( 'm', $next ) ) . '">Next →</a>' : '<a class="off">Next →</a>' ) . '</span></div>'
				. '<div class="sscp-mini"><div><span>Sold this period</span><b>' . ss_cp_money( $m['gross'] ) . '</b></div><div><span>Your earnings</span><b>' . ss_cp_money( $m['total'] ) . '</b></div><div><span>Sales</span><b>' . (int) $m['count'] . '</b></div></div>'
				. ( $m['count'] ? '' : '<p class="sscp-empty">No sales in this period.</p>' ) . '</div>';
		}
		if ( 'ss-payouts' === $s ) {
			$p = ss_cp_prof( $id ); $pay = isset( $p['payment'] ) ? (array) $p['payment'] : array(); $mth = $pay['method'] ?? '';
			$n = wp_nonce_field( 'ss_cp_payout', '_sscp', true, false ) . '<input type="hidden" name="ss_cp_action" value="payout">';
			$o .= '<div class="ssw-card"><div class="ssw-h"><b>Select Payment Method</b></div><p class="sscp-sub">Choose how you want to receive your payouts. You can only have one active payout method at a time. We pay once a month when your balance is at least $25.</p><div class="sscp-pm">'
				. '<form method="post" class="sscp-pmc' . ( 'paypal' === $mth ? ' on' : '' ) . '">' . $n . '<input type="hidden" name="method" value="paypal"><h3>PayPal</h3><p class="h">PayPal — Monthly</p><span class="tag">Paid within 30 days</span><p>We pay your PayPal on the 1st of each month for everything you are owed (from $25).</p><input type="email" name="paypal_email" placeholder="your@paypal.com" value="' . esc_attr( $pay['paypal']['email'] ?? '' ) . '" required><button class="sscp-sel">' . ( 'paypal' === $mth ? 'Selected ✓ — Save' : 'Select' ) . '</button></form>'
				. '<form method="post" class="sscp-pmc' . ( 'bank_transfer' === $mth ? ' on' : '' ) . '">' . $n . '<input type="hidden" name="method" value="bank_transfer"><h3>Bank transfer</h3><p class="h">Bank Transfer — Monthly</p><span class="tag">Paid within 30 days</span><p>We send a bank transfer on the 1st of each month for everything you are owed (from $25).</p>'
				. '<input name="bank_ac_name" placeholder="Account holder name" value="' . esc_attr( $pay['bank']['ac_name'] ?? '' ) . '" required><input name="bank_bank_name" placeholder="Bank name" value="' . esc_attr( $pay['bank']['bank_name'] ?? '' ) . '"><input name="bank_iban" placeholder="IBAN" value="' . esc_attr( $pay['bank']['iban'] ?? '' ) . '" required><input name="bank_swift" placeholder="SWIFT / BIC" value="' . esc_attr( $pay['bank']['swift'] ?? '' ) . '"><button class="sscp-sel">' . ( 'bank_transfer' === $mth ? 'Selected ✓ — Save' : 'Select' ) . '</button></form>'
				. '</div></div><p class="sscp-sub"><a href="' . esc_url( trailingslashit( get_wcfm_url() ) . 'payments/' ) . '">Payment history →</a></p>';
		}
		if ( 'ss-reviews' === $s ) {
			$cs = get_comments( array( 'post_author' => $id, 'post_type' => 'product', 'status' => 'approve', 'parent' => 0, 'number' => 100 ) );
			$o .= '<div class="ssw-card"><div class="ssw-h"><b>My Reviews</b></div>';
			if ( ! $cs ) $o .= '<p class="sscp-sub"><b>No reviews yet</b><br>When a buyer reviews one of your products it shows up here, and we email you. You can reply and your reply appears publicly under the review.</p>';
			foreach ( $cs as $c ) {
				$r = (int) get_comment_meta( $c->comment_ID, 'rating', true );
				$rep = get_comments( array( 'parent' => $c->comment_ID, 'status' => 'approve' ) );
				$o .= '<div class="sscp-rv"><div class="t"><b>' . esc_html( get_the_title( $c->comment_post_ID ) ) . '</b><span class="st">' . str_repeat( '★', $r ) . str_repeat( '☆', max( 0, 5 - $r ) ) . '</span><em>' . esc_html( $c->comment_author ) . ' · ' . esc_html( get_comment_date( 'M j, Y', $c ) ) . '</em></div><p>' . esc_html( $c->comment_content ) . '</p>';
				foreach ( $rep as $x ) $o .= '<div class="sscp-rp"><b>Your reply</b><p>' . esc_html( $x->comment_content ) . '</p></div>';
				if ( ! $rep ) $o .= '<form method="post" class="sscp-rf">' . wp_nonce_field( 'ss_cp_reply', '_sscp', true, false ) . '<input type="hidden" name="ss_cp_action" value="reply"><input type="hidden" name="comment_id" value="' . (int) $c->comment_ID . '"><textarea name="reply" rows="2" placeholder="Write a public reply…" required></textarea><button class="ssw-btn sm">Reply</button></form>';
				$o .= '</div>';
			}
			$o .= '</div>';
		}
		if ( 'ss-settings' === $s ) {
			$p = ss_cp_prof( $id ); $u = wp_get_current_user(); $soc = isset( $p['social'] ) ? (array) $p['social'] : array(); $seo = isset( $p['store_seo'] ) ? (array) $p['store_seo'] : array();
			$av = ! empty( $p['gravatar'] ) ? wp_get_attachment_image_url( $p['gravatar'], 'thumbnail' ) : get_avatar_url( $id );
			$cv = ! empty( $p['banner'] ) ? wp_get_attachment_image_url( $p['banner'], 'medium_large' ) : '';
			$name = ! empty( $p['store_name'] ) ? $p['store_name'] : $u->display_name;
			$countries = function_exists( 'WC' ) ? WC()->countries->get_countries() : array();
			$cc = $p['address']['country'] ?? '';
			$opt = '<option value="">Select country</option>'; foreach ( $countries as $k => $v ) $opt .= '<option value="' . esc_attr( $k ) . '"' . selected( $cc, $k, false ) . '>' . esc_html( $v ) . '</option>';
			$f = function ( $k, $lbl, $ph ) use ( $soc ) { return '<label>' . esc_html( $lbl ) . '<input type="url" name="social_' . $k . '" placeholder="' . esc_attr( $ph ) . '" value="' . esc_attr( $soc[ $k ] ?? '' ) . '"></label>'; };
			$o .= '<form method="post" enctype="multipart/form-data" class="sscp-set">' . wp_nonce_field( 'ss_cp_settings', '_sscp', true, false ) . '<input type="hidden" name="ss_cp_action" value="settings">'
				. '<div class="ssw-card"><div class="ssw-h"><b>👤 Profile &amp; Branding</b><button class="ssw-btn">💾 Save</button></div>'
				. '<label>Artist / Producer Name<input name="store_name" value="' . esc_attr( $name ) . '"><small>This is how you’ll appear across the marketplace</small></label>'
				. '<div class="sscp-up"><img src="' . esc_url( $av ) . '" class="av" alt=""><label class="btnf">⬆ Upload New Photo<input type="file" name="avatar_file" accept="image/*"></label><small>Recommended: square image, at least 400×400px</small></div>'
				. '<div class="sscp-up">' . ( $cv ? '<img src="' . esc_url( $cv ) . '" class="cv" alt="">' : '<span class="cv ph">No cover yet</span>' ) . '<label class="btnf">⬆ Upload Cover Image<input type="file" name="cover_file" accept="image/*"></label><small>Recommended: 1200×400px for best results</small></div>'
				. '<label>Bio<textarea name="bio" rows="5">' . esc_textarea( $p['shop_description'] ?? '' ) . '</textarea></label></div>'
				. '<div class="ssw-card"><div class="ssw-h"><b>📍 Location (Optional)</b></div><label>Country<select name="country">' . $opt . '</select></label></div>'
				. '<div class="ssw-card"><div class="ssw-h"><b>🔗 Social Media</b></div><div class="sscp-g2">' . $f( 'instagram', 'Instagram', 'https://instagram.com/…' ) . $f( 'youtube', 'YouTube', 'https://youtube.com/…' ) . $f( 'soundcloud', 'SoundCloud', 'https://soundcloud.com/…' ) . $f( 'spotify', 'Spotify', 'https://open.spotify.com/…' ) . $f( 'tiktok', 'TikTok', 'https://tiktok.com/@…' ) . $f( 'fb', 'Facebook', 'https://facebook.com/…' ) . '</div></div>'
				. '<div class="ssw-card"><div class="ssw-h"><b>☎ Contact Information</b></div><label>Contact Number (Optional)<input name="phone" value="' . esc_attr( $p['phone'] ?? '' ) . '"></label><label>Email Address<input value="' . esc_attr( $u->user_email ) . '" disabled></label></div>'
				. '<div class="ssw-card"><div class="ssw-h"><b>💳 Payment Information</b></div><p class="sscp-sub">' . ( ss_cp_payout_set( $id ) ? 'Your payout method is set. ' : 'No payout method yet. ' ) . '<a href="' . esc_url( ss_cp_url( 'ss-payouts' ) ) . '">Manage payouts →</a></p></div>'
				/* SEO keywords card removed (owner 15.11: nothing extra for producers) */
				. '<p><button class="ssw-btn">💾 Save</button></p></form>';
		}
		if ( 'ss-help' === $s ) {
			$qa = array(
				array( 'How much do I earn?', 'You keep 70% of every sale. The first 20 Founding Creators keep 85% for their first 12 months.' ),
				array( 'When and how do I get paid?', 'Once a month, by PayPal or bank transfer, when your available balance is at least $25. Set it up in Payouts.' ),
				array( 'How long does product review take?', 'Usually within 24 hours. You get an email when your product is live.' ),
				array( 'Is it exclusive?', 'No. You can sell the same products anywhere else.' ),
				array( 'What files do I need?', 'A cover image (JPG, 1000×1000+), the project or pack as a ZIP, and an MP3 demo for the player.' ),
				array( 'Can I use third-party samples?', 'Only if their licence allows redistribution. If in doubt, leave them out.' ),
				array( 'How do buyers find my products?', 'Through the shop filters (format, genre, BPM, key, plugins). Fill in these fields carefully when you add a product.' ),
				array( 'Do I get a discount on mixing and mastering?', 'Yes, approved creators get 20% off our mixing and mastering services.' ),
			);
			$o .= '<div class="ssw-card"><div class="ssw-h"><b>Help / FAQ</b><a class="ssw-btn ghost" href="' . esc_url( home_url( '/creator-terms/' ) ) . '" target="_blank">Creator Terms</a></div>';
			foreach ( $qa as $x ) $o .= '<details class="sscp-faq"><summary>' . esc_html( $x[0] ) . '</summary><p>' . esc_html( $x[1] ) . '</p></details>';
			$o .= '<p class="sscp-sub">Still need help? <a href="' . esc_url( home_url( '/contacts/' ) ) . '">Contact us</a>.</p></div>';
		}
		return $o;
	}

	add_action( 'wcfm_load_views', function ( $ep ) {
		$s = preg_replace( '/^wcfm-/', '', (string) $ep ); $pages = ss_cp_pages();
		if ( ! isset( $pages[ $s ] ) || ! ss_cp_can() ) return;
		echo '<div class="collapse wcfm-collapse"><div class="wcfm-page-headig"><span class="wcfm-page-heading-text">' . esc_html( $pages[ $s ] ) . '</span></div><div class="wcfm-collapse-content sscp">' . ss_cp_render( $s ) . '</div></div>'; // phpcs:ignore
	}, 50 );

	add_action( 'wp_head', function () {
		if ( ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return; ?>
<style id="ss-cp">
.sscp{padding:20px!important}
.sscp .ssw-card{max-width:none}
.sscp-msg{background:rgba(52,211,153,.12);color:#1d6b3a;border-radius:8px;padding:10px 14px;margin:0 0 14px}.sscp-msg.err{background:rgba(239,68,68,.12);color:#a12a2a}
.sscp-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;margin:0 0 16px}
.sscp-c{background:#131B2E;border:1px solid rgba(255,255,255,.1);border-left:4px solid #2e9e48;border-radius:10px;padding:18px;text-align:center}
.sscp-c.d{border-left-color:#CBD5E1}.sscp-c.y{border-left-color:#e0a800}.sscp-c.b{border-left-color:#D97706}
.sscp-c>span{display:block;font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:#94A3B8}
.sscp-c b{display:block;font-size:28px;margin:6px 0;color:#E5E7EB}.sscp-c.g b{color:#2e9e48}.sscp-c.y b{color:#e0a800}.sscp-c.b b{color:#D97706}
.sscp-c em{font-style:normal;font-size:12px;color:#94A3B8}
.sscp-warn{background:rgba(245,158,11,.12);border:1px solid #f3dfa0;border-radius:10px;padding:18px;margin:0 0 16px}.sscp-warn b{display:block;color:#E5E7EB;font-size:16px}.sscp-warn p{margin:4px 0 12px;color:#CBD5E1}
.sscp-nav{display:flex;align-items:center;gap:10px}.sscp-nav a{border:1px solid rgba(255,255,255,.1);border-radius:6px;padding:5px 10px;font-size:13px;color:#CBD5E1;text-decoration:none}.sscp-nav a.off{opacity:.4;pointer-events:none}.sscp-nav strong{color:#E5E7EB}
.sscp-mini{display:flex;gap:40px;flex-wrap:wrap;margin:6px 0 10px}.sscp-mini>div>span{display:block;font-size:12px;color:#94A3B8}.sscp-mini b{font-size:20px;color:#E5E7EB}
.sscp-empty{color:#94A3B8;text-align:center;padding:20px}
.sscp-sub{color:#94A3B8;margin:0 0 14px}
.sscp-best{display:grid;grid-template-columns:repeat(auto-fit,minmax(360px,1fr));gap:12px}
.sscp-bi{display:flex;align-items:center;gap:14px;border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:14px;background:#131B2E}
.sscp-bi img{width:80px;height:80px;object-fit:cover;border-radius:4px;flex:none}.sscp-bi b{color:#E5E7EB;font-size:15px}.sscp-bi p{margin:4px 0 0;font-size:13px;color:#94A3B8}.sscp-bi i{font-style:normal;color:#CBD5E1}.sscp-bi i.bl{color:#D97706}.sscp-bi i.gr,.sscp .gr{color:#2e9e48;font-weight:700}
.sscp-bi .rk{width:30px;height:30px;flex:none;border-radius:50%;display:grid;place-items:center;font-weight:800;background:rgba(255,255,255,.1);color:#94A3B8}
.sscp-bi .rk.r1{background:linear-gradient(135deg,#ffb347,#e67e22);color:#fff}.sscp-bi .rk.r2{background:linear-gradient(135deg,#c9ced6,#9aa1ab);color:#fff}.sscp-bi .rk.r3{background:linear-gradient(135deg,#d39a6a,#a0602f);color:#fff}
.sscp-tw{overflow-x:auto}.sscp-t{width:100%;border-collapse:collapse;font-size:14px}.sscp-t th{text-align:left;font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:#94A3B8;background:#0F1626;padding:10px;border-bottom:1px solid rgba(255,255,255,.1)}.sscp-t td{padding:10px;border-bottom:1px solid #0F1626;color:#CBD5E1;vertical-align:middle}.sscp-t img{width:48px;height:48px;object-fit:cover;border-radius:4px}
.sscp-st{font-size:12px;padding:3px 8px;border-radius:99px;background:#0F1626;color:#CBD5E1}.sscp-st.publish{background:rgba(52,211,153,.12);color:#1d6b3a}.sscp-st.pending{background:rgba(245,158,11,.12);color:#8a6200}
a.ssw-btn.sm,button.ssw-btn.sm{padding:6px 12px!important;font-size:12px!important}
.sscp-pm{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:16px}
.sscp-pmc{border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:22px;text-align:center;background:#131B2E;display:flex;flex-direction:column;gap:8px}.sscp-pmc.on{border-color:#0f7a8a;box-shadow:0 0 0 2px rgba(15,122,138,.15)}
.sscp-pmc h3{margin:0;font-size:26px;color:#E5E7EB}.sscp-pmc .h{margin:0;font-weight:600;color:#E5E7EB}.sscp-pmc .tag{align-self:center;background:#0F1626;border-radius:99px;font-size:12px;padding:3px 10px;color:#94A3B8}.sscp-pmc p{margin:0;color:#94A3B8;font-size:13px}
.sscp-pmc input,.sscp-rf textarea{background:#0F1626!important;border:1px solid rgba(255,255,255,.1)!important;color:#E5E7EB!important;border-radius:4px;padding:9px 10px;font-size:14px;width:100%}
.sscp-sel{background:#0f7a8a;color:#fff;border:0;border-radius:4px;padding:12px;font-weight:700;cursor:pointer;margin-top:6px}
.sscp-rv{border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:14px;margin:0 0 12px}.sscp-rv .t{display:flex;gap:12px;align-items:center;flex-wrap:wrap}.sscp-rv .st{color:#f5a400}.sscp-rv em{font-style:normal;color:#94A3B8;font-size:13px}.sscp-rv p{margin:8px 0;color:#CBD5E1}
.sscp-rp{background:#0F1626;border-left:3px solid #D97706;border-radius:6px;padding:10px 12px;margin-top:8px}.sscp-rp p{margin:4px 0 0}
.sscp-rf{display:flex;gap:10px;align-items:flex-start}.sscp-rf textarea{flex:1;background:#0F1626;border:1px solid rgba(255,255,255,.1);border-radius:4px;padding:8px}
.sscp-set label{display:block;font-weight:700;color:#E5E7EB;margin:0 0 14px;font-size:14px}.sscp-set label small,.sscp-up small{display:block;font-weight:400;color:#94A3B8;font-size:12px;margin-top:4px}
.sscp-set input:not([type=file]),.sscp-set textarea,.sscp-set select{display:block;width:100%!important;max-width:none!important;margin-top:6px;background:#0F1626!important;border:1px solid rgba(255,255,255,.1)!important;border-radius:4px;padding:9px 10px;font-size:14px;font-weight:400;color:#E5E7EB!important}
.sscp-up{display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin:0 0 16px}.sscp-up .av{width:120px;height:120px;border-radius:50%;object-fit:cover}.sscp-up .cv{width:240px;height:80px;object-fit:cover;border-radius:6px;background:rgba(255,255,255,.1);display:block}
.sscp-up .btnf{display:inline-block;border:1px solid rgba(255,255,255,.1);border-radius:6px;padding:9px 14px;font-weight:600;cursor:pointer;margin:0}.sscp-up .btnf input{display:none}
.sscp-g2{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:0 16px}
.sscp-faq{border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:12px 14px;margin:0 0 8px}.sscp-faq summary{cursor:pointer;font-weight:700;color:#E5E7EB}.sscp-faq p{margin:8px 0 0;color:#CBD5E1}
</style>
	<?php } );
}

/* ═══ Solid Sound — 15.10.2026: anti-spam registrations + "Likely spam" users filter ═══ */
if ( ! function_exists( 'ss_spam_ids' ) ) {
	/* every registration form gets a JS token + honeypot; bots posting directly are rejected */
	add_action( 'wp_footer', function () {
		if ( is_user_logged_in() ) return; ?>
<script>(function(){function add(f){if(f.querySelector('[name=ss_h]'))return;var a=document.createElement('input');a.type='hidden';a.name='ss_js';a.value='ok-'+Date.now();f.appendChild(a);var h=document.createElement('input');h.type='text';h.name='ss_h';h.tabIndex=-1;h.autocomplete='off';h.style.cssText='position:absolute;left:-9999px;width:1px;height:1px';f.appendChild(h)}function scan(){[].forEach.call(document.querySelectorAll('form'),function(f){if(f.querySelector('[name="woocommerce-register-nonce"],[name="wpfreg"],[name="user_email"][id*=reg],[name="wcfm_membership_registration_form"],#wcfm_membership_container input'))add(f)})}scan();setTimeout(scan,1500);document.addEventListener('focusin',function(e){var f=e.target.form;if(f&&f.querySelector('[type=password]'))add(f)})})();</script>
	<?php }, 99 );
	function ss_spam_check() {
		if ( ! empty( $_POST['ss_h'] ) ) return 'Registration failed. Please try again.';
		$t = isset( $_POST['ss_js'] ) ? (string) $_POST['ss_js'] : '';
		if ( 0 !== strpos( $t, 'ok-' ) ) return 'Please enable JavaScript to create an account.';
		return '';
	}
	add_filter( 'woocommerce_process_registration_errors', function ( $err ) {
		$m = ss_spam_check(); if ( $m ) $err->add( 'ss_spam', $m ); return $err;
	}, 5 );
	add_filter( 'registration_errors', function ( $err ) {
		$m = ss_spam_check(); if ( $m ) $err->add( 'ss_spam', $m ); return $err;
	}, 5 );

	/* admin: Users → "Likely spam" = customers with no orders, no reviews, no forum posts, no sign-up consent */
	function ss_spam_ids() {
		global $wpdb;
		$cap = $wpdb->prefix . 'capabilities';
		$sql = "SELECT u.ID FROM {$wpdb->users} u JOIN {$wpdb->usermeta} c ON c.user_id = u.ID AND c.meta_key = '{$cap}' AND c.meta_value LIKE '%customer%' AND c.meta_value NOT LIKE '%administrator%' AND c.meta_value NOT LIKE '%vendor%' AND c.meta_value NOT LIKE '%shop_manager%'
			WHERE u.user_registered < DATE_SUB(NOW(), INTERVAL 1 DAY)
			AND NOT EXISTS ( SELECT 1 FROM {$wpdb->usermeta} t WHERE t.user_id = u.ID AND t.meta_key IN ('ss_terms_accepted','paying_customer') AND t.meta_value <> '' AND t.meta_value <> '0' )
			AND NOT EXISTS ( SELECT 1 FROM {$wpdb->postmeta} pm WHERE pm.meta_key = '_customer_user' AND pm.meta_value = u.ID )
			AND NOT EXISTS ( SELECT 1 FROM {$wpdb->comments} cm WHERE cm.user_id = u.ID )";
		$hpos = $wpdb->prefix . 'wc_orders';
		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $hpos ) ) === $hpos ) $sql .= " AND NOT EXISTS ( SELECT 1 FROM {$hpos} o WHERE o.customer_id = u.ID )";
		foreach ( array( 'wpforo_posts', 'wpforo_topics' ) as $tb ) {
			$t = $wpdb->prefix . $tb;
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $t ) ) === $t ) $sql .= " AND NOT EXISTS ( SELECT 1 FROM {$t} f WHERE f.userid = u.ID )";
		}
		return array_map( 'intval', (array) $wpdb->get_col( $sql ) );
	}
	add_filter( 'views_users', function ( $v ) {
		if ( ! current_user_can( 'list_users' ) ) return $v;
		$ids = get_transient( 'ss_spam_ids' );
		if ( false === $ids ) { $ids = ss_spam_ids(); set_transient( 'ss_spam_ids', $ids, 10 * MINUTE_IN_SECONDS ); }
		$cur = ! empty( $_GET['ss_spam'] ) ? ' class="current"' : '';
		$v['ss_spam'] = '<a href="' . esc_url( admin_url( 'users.php?ss_spam=1' ) ) . '"' . $cur . ' style="color:#b32d2e">Likely spam <span class="count">(' . count( $ids ) . ')</span></a>';
		return $v;
	} );
	add_action( 'pre_get_users', function ( $q ) {
		if ( ! is_admin() || empty( $_GET['ss_spam'] ) || ! current_user_can( 'list_users' ) ) return;
		global $pagenow; if ( 'users.php' !== $pagenow ) return;
		$ids = get_transient( 'ss_spam_ids' ); if ( false === $ids ) $ids = ss_spam_ids();
		$q->set( 'include', $ids ? $ids : array( 0 ) );
	} );
	add_action( 'admin_notices', function () {
		global $pagenow; if ( 'users.php' !== $pagenow || empty( $_GET['ss_spam'] ) ) return;
		echo '<div class="notice notice-warning"><p><b>Likely spam accounts:</b> customers registered more than a day ago with no orders, no reviews, no forum posts and no Solid Sound sign-up consent. Check a few, then select all → Bulk actions → Delete. Set "Number of items per page" in Screen Options to 999 to delete faster.</p></div>';
	} );
}

/* Solid Sound 15.10.2026: one cover size everywhere (square, cropped) */
if ( ! function_exists( 'ss_square_covers' ) ) {
	function ss_square_covers() { echo '<style id="ss-sq">.w-grid .post_image img,.w-grid .w-post-elm.post_image img,.ss-top img,.woocommerce-product-gallery img{aspect-ratio:1/1!important;object-fit:cover!important;height:auto!important}</style>'; }
	add_action( 'wp_head', 'ss_square_covers', 99 );
}

/* Solid Sound 15.10.2026: mobile catalog cards - price, like, info, cart aligned right */
if ( ! function_exists( 'ss_mobile_card_row' ) ) {
	function ss_mobile_card_row() { echo '<style id="ss-mrow">@media(max-width:767px){html body .w-grid.layout_195 .w-grid-item-h>.usg_hwrapper_5{justify-content:flex-end!important;gap:10px!important;padding-right:10px!important}html body .w-grid.layout_195 .usg_hwrapper_5>*{margin:0!important}html body .w-grid.layout_195 .w-grid-item-h>.usg_hwrapper_5{position:static!important}html body .w-grid.layout_195 .w-grid-item-h>.usg_hwrapper_5>.product_field.price{position:absolute!important;top:12px!important;right:18px!important;margin:0!important}html body .w-grid.layout_195 .usg_post_title_1{padding-right:78px!important}html body .w-grid.layout_195 .w-grid-item-h>.usg_hwrapper_5>.product_field.price{margin:0!important;padding:0!important;min-width:0!important;width:auto!important}html body .w-grid.layout_195 .usg_hwrapper_5 .add_to_cart_button{min-width:52px!important;justify-content:center}html body .w-grid.layout_195 .usg_hwrapper_5 .w-btn.usg_btn_2,html body .w-grid.layout_195 .usg_hwrapper_5 .ss-like{min-width:52px!important;justify-content:center}}</style>'; }
	add_action( 'wp_head', 'ss_mobile_card_row', 99 );
}

/* Solid Sound 15.10.2026: mobile cards - player above buttons, play button on the right (thumb reach) */
if ( ! function_exists( 'ss_mobile_player_first' ) ) {
	function ss_mobile_player_first() { echo '<style id="ss-mplay">@media(max-width:767px){html body .w-grid.layout_195 .w-grid-item-h{grid-template-areas:"img title" "img meta" "player player" "buy buy" "plug plug"!important}html body .w-grid.layout_195 .usg_post_custom_field_1 .ssw{flex-direction:row-reverse!important}}</style>'; }
	add_action( 'wp_head', 'ss_mobile_player_first', 100 );
}

/* Solid Sound 15.10.2026: bottom stack - player at the bottom, chat above it, back-to-top arrow on top */
if ( ! function_exists( 'ss_bottom_stack' ) ) {
	function ss_bottom_stack() { ?>
<script>(function(){function fix(){var p=document.querySelector('.ssp');var ph=(document.body.classList.contains('ssp-open')&&p&&p.offsetHeight)?p.offsetHeight:0;var h=document.getElementById('tidio-chat');if(h){h.style.setProperty('top','auto','important');h.style.setProperty('bottom',ph+'px','important');h.style.setProperty('height','calc(100% - '+ph+'px)','important')}var t=document.querySelector('.w-toplink');if(!t)return;var b=h&&h.shadowRoot&&h.shadowRoot.getElementById('button');var br=b?b.getBoundingClientRect():null;var off=(br&&br.height)?(window.innerHeight-br.top+10):(ph+10);t.style.setProperty('bottom',Math.round(off)+'px','important')}window.ssOnDom?ssOnDom(fix):setInterval(fix,600);})();</script>
	<?php }
	add_action( 'wp_footer', 'ss_bottom_stack', 99 );
}

/* ═══ Solid Sound — 15.10.2026: languages (Polylang) — translated key pages, header flags, translated links ═══ */
if ( ! function_exists( 'ss_i18n_dict' ) ) {
	function ss_i18n_dict() {
		$k = array(
			'Turn Your Ideas into Solid Sound', 'Mixing &amp; Mastering', 'Templates &amp; Presets', 'Music for Videos &amp; Business', 'Learn • Create • Release',
			'FILTER BY', 'BROWSE PRODUCTS', 'Best Products', 'We Create Ableton Live Templates',
			'All the projects are royalty free so you can freely edit and re-arrange any of them, more over you can use them as a mighty starting point for your new production',
			'BROWSE TEMPLATES', 'Contact Us',
			'Questions about Ableton templates, presets, mixing or mastering? Send us a message and we will get back to you by email. Looking for service prices? See',
			'Services &amp; Prices',
		);
		$t = array(
			'uk' => array( 'Перетворіть свої ідеї на Solid Sound', 'Зведення та мастеринг', 'Шаблони та пресети', 'Музика для відео та бізнесу', 'Навчайтеся • Створюйте • Випускайте',
				'ФІЛЬТРИ', 'ДИВИТИСЯ ТОВАРИ', 'Найкращі товари', 'Ми створюємо шаблони Ableton Live',
				'Усі проєкти без роялті: ви можете вільно редагувати й переаранжовувати будь-який із них та використовувати як потужну основу для своєї нової продукції',
				'ДИВИТИСЯ ШАБЛОНИ', 'Зв’яжіться з нами',
				'Питання щодо шаблонів Ableton, пресетів, зведення чи мастерингу? Надішліть нам повідомлення, і ми відповімо електронною поштою. Шукаєте ціни на послуги? Дивіться',
				'Послуги та ціни' ),
			'de' => array( 'Verwandle deine Ideen in Solid Sound', 'Mixing &amp; Mastering', 'Templates &amp; Presets', 'Musik für Videos &amp; Business', 'Lernen • Erstellen • Veröffentlichen',
				'FILTERN NACH', 'PRODUKTE ANSEHEN', 'Beste Produkte', 'Wir erstellen Ableton Live Templates',
				'Alle Projekte sind lizenzfrei: Du kannst sie frei bearbeiten, neu arrangieren und als starke Grundlage für deine neue Produktion nutzen',
				'TEMPLATES ANSEHEN', 'Kontakt',
				'Fragen zu Ableton-Templates, Presets, Mixing oder Mastering? Schreib uns eine Nachricht, wir antworten per E-Mail. Du suchst die Preise unserer Services? Siehe',
				'Services &amp; Preise' ),
			'fr' => array( 'Transformez vos idées en Solid Sound', 'Mixage &amp; Mastering', 'Templates &amp; Presets', 'Musique pour vidéos &amp; entreprises', 'Apprendre • Créer • Publier',
				'FILTRER PAR', 'VOIR LES PRODUITS', 'Meilleurs produits', 'Nous créons des templates Ableton Live',
				'Tous les projets sont libres de droits : vous pouvez les modifier et les réarranger librement, et les utiliser comme base solide pour votre nouvelle production',
				'VOIR LES TEMPLATES', 'Contactez-nous',
				'Des questions sur les templates Ableton, les presets, le mixage ou le mastering ? Envoyez-nous un message, nous vous répondrons par e-mail. Vous cherchez nos tarifs ? Voir',
				'Services &amp; tarifs' ),
			'pt' => array( 'Transforme as suas ideias em Solid Sound', 'Mistura &amp; Masterização', 'Templates &amp; Presets', 'Música para vídeos &amp; empresas', 'Aprender • Criar • Lançar',
				'FILTRAR POR', 'VER PRODUTOS', 'Melhores produtos', 'Criamos templates para Ableton Live',
				'Todos os projetos são livres de royalties: pode editá-los e reorganizá-los livremente e usá-los como uma base sólida para a sua nova produção',
				'VER TEMPLATES', 'Contacte-nos',
				'Dúvidas sobre templates Ableton, presets, mistura ou masterização? Envie-nos uma mensagem e responderemos por e-mail. Procura os preços dos serviços? Veja',
				'Serviços &amp; preços' ),
			'es' => array( 'Convierte tus ideas en Solid Sound', 'Mezcla y masterización', 'Plantillas y presets', 'Música para vídeos y empresas', 'Aprende • Crea • Publica', 'FILTRAR POR', 'VER PRODUCTOS', 'Los más vendidos', 'Creamos plantillas de Ableton Live', 'Todos los proyectos están libres de royalties: puedes editarlos y reorganizarlos a tu gusto y usarlos como un punto de partida potente para tu próxima producción', 'VER PLANTILLAS', 'Contáctanos', '¿Dudas sobre plantillas de Ableton, presets, mezcla o masterización? Escríbenos y te responderemos por email. ¿Buscas los precios de los servicios? Mira', 'Servicios y precios' ),
			'it' => array( 'Trasforma le tue idee in Solid Sound', 'Mix e mastering', 'Template e preset', 'Musica per video e aziende', 'Impara • Crea • Pubblica', 'FILTRA PER', 'VEDI I PRODOTTI', 'I più venduti', 'Creiamo template per Ableton Live', 'Tutti i progetti sono royalty-free: puoi modificarli e riarrangiarli liberamente e usarli come un solido punto di partenza per la tua prossima produzione', 'VEDI I TEMPLATE', 'Contattaci', 'Domande su template Ableton, preset, mix o mastering? Scrivici e ti risponderemo via email. Cerchi i prezzi dei servizi? Guarda', 'Servizi e prezzi' ),
		);
		$o = array(); foreach ( $t as $l => $v ) $o[ $l ] = array_combine( $k, $v );
		return $o;
	}
	function ss_i18n_titles() {
		return array(
			2    => array( 'uk' => 'Головна', 'de' => 'Startseite', 'fr' => 'Accueil', 'pt' => 'Início', 'es' => 'Inicio', 'it' => 'Home' ),
			1115 => array( 'uk' => 'Шаблони', 'de' => 'Templates', 'fr' => 'Templates', 'pt' => 'Templates', 'es' => 'Plantillas', 'it' => 'Template' ),
			1121 => array( 'uk' => 'Пресети', 'de' => 'Presets', 'fr' => 'Presets', 'pt' => 'Presets', 'es' => 'Presets', 'it' => 'Preset' ),
			1547 => array( 'uk' => 'Музика', 'de' => 'Musik', 'fr' => 'Musique', 'pt' => 'Música', 'es' => 'Música', 'it' => 'Musica' ),
			399  => array( 'uk' => 'Контакти', 'de' => 'Kontakt', 'fr' => 'Contact', 'pt' => 'Contactos', 'es' => 'Contacto', 'it' => 'Contatti' ),
		);
	}
	/* one-time builder: /wp-admin/?ss_make_tr=1 (admins only, safe to run again) */
	add_action( 'admin_init', function () {
		if ( empty( $_GET['ss_make_tr'] ) || ! current_user_can( 'manage_options' ) || ! function_exists( 'pll_set_post_language' ) ) return;
		$dict = ss_i18n_dict(); $log = array();
		foreach ( ss_i18n_titles() as $src => $titles ) {
			$p = get_post( $src ); if ( ! $p ) continue;
			if ( ! pll_get_post_language( $src ) ) pll_set_post_language( $src, 'en' );
			$tr = pll_get_post_translations( $src ); $tr['en'] = $src;
			foreach ( $titles as $lang => $title ) {
				if ( ! empty( $tr[ $lang ] ) && get_post( $tr[ $lang ] ) ) { $log[] = "$src/$lang exists"; continue; }
				$content = strtr( $p->post_content, $dict[ $lang ] );
				$id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $title, 'post_name' => $p->post_name . '-' . $lang, 'post_content' => wp_slash( $content ), 'post_parent' => $p->post_parent, 'menu_order' => $p->menu_order ) );
				if ( ! $id || is_wp_error( $id ) ) { $log[] = "$src/$lang FAIL"; continue; }
				foreach ( get_post_meta( $src ) as $mk => $mv ) { if ( in_array( $mk, array( '_edit_lock', '_edit_last' ), true ) ) continue; foreach ( $mv as $v ) add_post_meta( $id, $mk, maybe_unserialize( $v ) ); }
				pll_set_post_language( $id, $lang );
				$tr[ $lang ] = $id; $log[] = "$src/$lang => $id";
			}
			pll_save_post_translations( $tr );
		}
		wp_die( '<pre>' . esc_html( implode( "\n", $log ) ) . '</pre>' );
	} );

	/* header: flags on the right + keep visitors in their language when a translated page exists */
	add_action( 'wp_footer', function () {
		if ( ! function_exists( 'pll_the_languages' ) || is_admin() ) return;
		$langs = pll_the_languages( array( 'raw' => 1, 'hide_if_empty' => 0 ) );
		if ( empty( $langs ) ) return;
		$cur = pll_current_language(); $def = pll_default_language();
		$map = array();
		if ( $cur && $cur !== $def ) {
			foreach ( array_keys( ss_i18n_titles() ) as $src ) { $t = pll_get_post( $src, $cur ); if ( $t ) $map[ untrailingslashit( get_permalink( $src ) ) ] = get_permalink( $t ); }
		}
		$items = array();
		foreach ( $langs as $l ) $items[] = array( 'u' => ( function_exists( 'ss_untr_ctx' ) && ss_untr_ctx() ) ? ss_lang_url( $l['slug'] ) : $l['url'], 'f' => $l['flag'], 'n' => $l['name'], 's' => $l['slug'], 'c' => ! empty( $l['current_lang'] ) );
		?>
<style id="ss-flags">
.ss-flags{display:inline-flex;align-items:center;gap:4px;margin:0 10px;position:relative}
.ss-flags a{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;border:1px solid rgba(255,255,255,.14);opacity:.6;transition:opacity .15s}
.ss-flags a:hover,.ss-flags a.on{opacity:1;border-color:#f5a400}
.ss-flags img{width:20px!important;height:auto!important;display:block;border-radius:2px}
.ss-flags{gap:0;margin:0 8px}.ss-flags a:not(.on){display:none}.ss-flags a.on{padding-right:14px;position:relative;width:auto;min-width:30px}.ss-flags a.on:after{content:'';position:absolute;right:5px;top:50%;margin-top:-2px;border:4px solid transparent;border-top-color:#f5a400}.ss-flags.open{position:relative}.ss-flags.open .ss-fl-list{display:flex}.ss-fl-list{display:none;position:absolute;top:calc(100% + 6px);right:0;background:#16141f;border:1px solid rgba(255,255,255,.12);padding:6px;border-radius:8px;flex-direction:column;gap:4px;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,.5)}.ss-fl-list a{display:inline-flex!important}
</style>
<script data-ssp-rerun=".l-header .ss-flags">
(function(){var L=<?php echo wp_json_encode( $items ); ?>,M=<?php echo wp_json_encode( $map ); ?>;window.ssTrMap=M;
function box(){var w=document.createElement('span');w.className='ss-flags';L.forEach(function(l){var a=document.createElement('a');a.href=l.u;a.title=l.n;a.setAttribute('hreflang',l.s);a.setAttribute('aria-label',l.n);if(l.c)a.className='on';var im=document.createElement('img');im.src=l.f;im.alt=l.n;im.width=20;a.appendChild(im);w.appendChild(a)});var lst=document.createElement('span');lst.className='ss-fl-list';[].slice.call(w.querySelectorAll('a:not(.on)')).forEach(function(x){lst.appendChild(x)});w.appendChild(lst);w.addEventListener('click',function(e){if(e.target.closest('a.on')){e.preventDefault();w.classList.toggle('open')}});document.addEventListener('click',function(e){if(!w.contains(e.target))w.classList.remove('open')});return w}
[].forEach.call(document.querySelectorAll('.l-header .w-socials, .l-header .w-cart'),function(anchor,i){if(i>0||document.querySelector('.l-header .ss-flags'))return;var cart=document.querySelector('.l-header .w-cart');var tgt=cart||anchor;tgt.parentNode.insertBefore(box(),tgt)});
if(Object.keys(M).length){[].forEach.call(document.querySelectorAll('a[href]'),function(a){var h=a.href.split('#')[0].replace(/\/$/,'');if(M[h]&&!a.closest('.ss-flags')&&!a.hasAttribute('hreflang'))a.href=M[h]})}
})();
</script>
		<?php
	}, 20 );
}

/* ═══ Solid Sound — 15.10.2026: UI strings translation for non-English languages (exact-match dictionary) ═══ */
if ( ! function_exists( 'ss_ui_dict' ) ) {
	function ss_ui_dict() {
		static $d = null; if ( null === $d ) $d = json_decode( <<<'SSJSON'
{"uk": {"Main": "Головна", "MAIN": "ГОЛОВНА", "PRODUCTS": "ТОВАРИ", "TEMPLATES": "ШАБЛОНИ", "PRESETS": "ПРЕСЕТИ", "MUSIC": "МУЗИКА", "FORUM": "ФОРУМ", "CONTACTS": "КОНТАКТИ", "PRIVACY POLICY": "ПОЛІТИКА КОНФІДЕНЦІЙНОСТІ", "REFUND POLICY": "ПОВЕРНЕННЯ КОШТІВ", "LICENSE AGREEMENT": "ЛІЦЕНЗІЙНА УГОДА", "WRITE TO US": "НАПИШІТЬ НАМ", "Filters": "Фільтри", "Product Format": "Формат", "Reset": "Скинути", "Product Versions": "Версія", "Additional Plugins": "Плагіни", "Genres": "Жанри", "Genre": "Жанр", "Root key": "Тональність", "Key": "Тональність", "LENGTH": "ТРИВАЛІСТЬ", "Tag": "Тег", "Apply": "Застосувати", "Ableton Live Versions": "Версії Ableton Live", "Best Products": "Найкращі товари", "BEST PRODUCTS": "НАЙКРАЩІ ТОВАРИ", "Newest": "Нові", "Top Sellers": "Хіти продажу", "Popular": "Популярні", "Highest Price ▲": "Найдорожчі ▲", "Lowest Price ▼": "Найдешевші ▼", "GENRE:": "ЖАНР:", "LENGTH:": "ТРИВАЛІСТЬ:", "KEY:": "ТОНАЛЬНІСТЬ:", "Details": "Детальніше", "Add to cart": "У кошик", "Rating:": "Рейтинг:", "has been added to your cart.": "додано до кошика.", "Play All": "Слухати все", "Search": "Пошук", "Active:": "Обрано:", "Clear all": "Скинути все", "All": "Усі", "Templates": "Шаблони", "Presets": "Пресети", "Music": "Музика", "sign-in": "увійти", "Account": "Акаунт", "You are signed in.": "Ви увійшли в акаунт.", "Creator Dashboard": "Кабінет автора", "My account & downloads": "Мій акаунт і завантаження", "Log out": "Вийти", "We value your privacy": "Ми цінуємо вашу приватність", "We use essential cookies to run this site (cart, login, checkout). With your permission we also use marketing cookies (Meta Pixel) to measure and improve our ads.": "Ми використовуємо необхідні cookies для роботи сайту (кошик, вхід, оформлення замовлення). З вашого дозволу також використовуємо маркетингові cookies (Meta Pixel), щоб оцінювати та покращувати рекламу.", "Privacy Policy": "Політика конфіденційності", "Reject non-essential": "Відхилити необов’язкові", "Accept all": "Прийняти всі", "Cookie settings": "Налаштування cookies", "Log in": "Увійти", "Create account": "Створити акаунт", "Become a vendor": "Стати автором", "Password": "Пароль", "Confirm password": "Підтвердіть пароль", "Remember me": "Запам’ятати мене", "Lost your password?": "Забули пароль?", "First name": "Ім’я", "Last name": "Прізвище", "Description": "Опис", "Reviews": "Відгуки", "Related products": "Схожі товари", "Instant download after payment": "Миттєве завантаження після оплати"}, "de": {"Main": "Start", "MAIN": "START", "PRODUCTS": "PRODUKTE", "TEMPLATES": "TEMPLATES", "PRESETS": "PRESETS", "MUSIC": "MUSIK", "FORUM": "FORUM", "CONTACTS": "KONTAKT", "PRIVACY POLICY": "DATENSCHUTZ", "REFUND POLICY": "RÜCKERSTATTUNG", "LICENSE AGREEMENT": "LIZENZVEREINBARUNG", "WRITE TO US": "SCHREIB UNS", "Filters": "Filter", "Product Format": "Format", "Reset": "Zurücksetzen", "Product Versions": "Versionen", "Additional Plugins": "Plugins", "Genres": "Genres", "Genre": "Genre", "Root key": "Tonart", "Key": "Tonart", "LENGTH": "LÄNGE", "Tag": "Schlagwort", "Apply": "Anwenden", "Ableton Live Versions": "Ableton Live Versionen", "Best Products": "Beste Produkte", "BEST PRODUCTS": "BESTE PRODUKTE", "Newest": "Neueste", "Top Sellers": "Bestseller", "Popular": "Beliebt", "Highest Price ▲": "Höchster Preis ▲", "Lowest Price ▼": "Niedrigster Preis ▼", "GENRE:": "GENRE:", "LENGTH:": "LÄNGE:", "KEY:": "TONART:", "Details": "Details", "Add to cart": "In den Warenkorb", "Rating:": "Bewertung:", "has been added to your cart.": "wurde in den Warenkorb gelegt.", "Play All": "Alle abspielen", "Search": "Suchen", "Active:": "Aktive Filter:", "Clear all": "Alle zurücksetzen", "All": "Alle", "Templates": "Templates", "Presets": "Presets", "Music": "Musik", "sign-in": "Anmelden", "Account": "Konto", "You are signed in.": "Du bist angemeldet.", "Creator Dashboard": "Creator-Dashboard", "My account & downloads": "Mein Konto & Downloads", "Log out": "Abmelden", "We value your privacy": "Wir schätzen deine Privatsphäre", "We use essential cookies to run this site (cart, login, checkout). With your permission we also use marketing cookies (Meta Pixel) to measure and improve our ads.": "Wir verwenden notwendige Cookies für den Betrieb der Seite (Warenkorb, Login, Kasse). Mit deiner Zustimmung nutzen wir auch Marketing-Cookies (Meta Pixel), um unsere Werbung zu messen und zu verbessern.", "Privacy Policy": "Datenschutzerklärung", "Reject non-essential": "Nicht notwendige ablehnen", "Accept all": "Alle akzeptieren", "Cookie settings": "Cookie-Einstellungen", "Log in": "Anmelden", "Create account": "Konto erstellen", "Become a vendor": "Creator werden", "Password": "Passwort", "Confirm password": "Passwort bestätigen", "Remember me": "Angemeldet bleiben", "Lost your password?": "Passwort vergessen?", "First name": "Vorname", "Last name": "Nachname", "Description": "Beschreibung", "Reviews": "Bewertungen", "Related products": "Ähnliche Produkte", "Instant download after payment": "Sofortiger Download nach der Zahlung"}, "fr": {"Main": "Accueil", "MAIN": "ACCUEIL", "PRODUCTS": "PRODUITS", "TEMPLATES": "TEMPLATES", "PRESETS": "PRESETS", "MUSIC": "MUSIQUE", "FORUM": "FORUM", "CONTACTS": "CONTACT", "PRIVACY POLICY": "CONFIDENTIALITÉ", "REFUND POLICY": "REMBOURSEMENT", "LICENSE AGREEMENT": "CONTRAT DE LICENCE", "WRITE TO US": "ÉCRIVEZ-NOUS", "Filters": "Filtres", "Product Format": "Format", "Reset": "Réinitialiser", "Product Versions": "Versions", "Additional Plugins": "Plugins", "Genres": "Genres", "Genre": "Genre", "Root key": "Tonalité", "Key": "Tonalité", "LENGTH": "DURÉE", "Tag": "Tag", "Apply": "Appliquer", "Ableton Live Versions": "Versions d’Ableton Live", "Best Products": "Meilleurs produits", "BEST PRODUCTS": "MEILLEURS PRODUITS", "Newest": "Nouveautés", "Top Sellers": "Meilleures ventes", "Popular": "Populaires", "Highest Price ▲": "Prix décroissant ▲", "Lowest Price ▼": "Prix croissant ▼", "GENRE:": "GENRE :", "LENGTH:": "DURÉE :", "KEY:": "TONALITÉ :", "Details": "Détails", "Add to cart": "Ajouter au panier", "Rating:": "Note :", "has been added to your cart.": "a été ajouté à votre panier.", "Play All": "Tout écouter", "Search": "Rechercher", "Active:": "Filtres actifs :", "Clear all": "Tout effacer", "All": "Tous", "Templates": "Templates", "Presets": "Presets", "Music": "Musique", "sign-in": "connexion", "Account": "Compte", "You are signed in.": "Vous êtes connecté.", "Creator Dashboard": "Espace créateur", "My account & downloads": "Mon compte & téléchargements", "Log out": "Se déconnecter", "We value your privacy": "Nous respectons votre vie privée", "We use essential cookies to run this site (cart, login, checkout). With your permission we also use marketing cookies (Meta Pixel) to measure and improve our ads.": "Nous utilisons des cookies essentiels au fonctionnement du site (panier, connexion, paiement). Avec votre accord, nous utilisons aussi des cookies marketing (Meta Pixel) pour mesurer et améliorer nos publicités.", "Privacy Policy": "Politique de confidentialité", "Reject non-essential": "Refuser les non essentiels", "Accept all": "Tout accepter", "Cookie settings": "Paramètres des cookies", "Log in": "Connexion", "Create account": "Créer un compte", "Become a vendor": "Devenir créateur", "Password": "Mot de passe", "Confirm password": "Confirmer le mot de passe", "Remember me": "Se souvenir de moi", "Lost your password?": "Mot de passe oublié ?", "First name": "Prénom", "Last name": "Nom", "Description": "Description", "Reviews": "Avis", "Related products": "Produits similaires", "Instant download after payment": "Téléchargement immédiat après paiement"}, "pt": {"Main": "Início", "MAIN": "INÍCIO", "PRODUCTS": "PRODUTOS", "TEMPLATES": "TEMPLATES", "PRESETS": "PRESETS", "MUSIC": "MÚSICA", "FORUM": "FÓRUM", "CONTACTS": "CONTACTOS", "PRIVACY POLICY": "PRIVACIDADE", "REFUND POLICY": "REEMBOLSOS", "LICENSE AGREEMENT": "CONTRATO DE LICENÇA", "WRITE TO US": "ESCREVA-NOS", "Filters": "Filtros", "Product Format": "Formato", "Reset": "Repor", "Product Versions": "Versões", "Additional Plugins": "Plugins", "Genres": "Géneros", "Genre": "Género", "Root key": "Tom", "Key": "Tom", "LENGTH": "DURAÇÃO", "Tag": "Etiqueta", "Apply": "Aplicar", "Ableton Live Versions": "Versões do Ableton Live", "Best Products": "Melhores produtos", "BEST PRODUCTS": "MELHORES PRODUTOS", "Newest": "Mais recentes", "Top Sellers": "Mais vendidos", "Popular": "Populares", "Highest Price ▲": "Preço mais alto ▲", "Lowest Price ▼": "Preço mais baixo ▼", "GENRE:": "GÉNERO:", "LENGTH:": "DURAÇÃO:", "KEY:": "TOM:", "Details": "Detalhes", "Add to cart": "Adicionar ao carrinho", "Rating:": "Avaliação:", "has been added to your cart.": "foi adicionado ao carrinho.", "Play All": "Ouvir tudo", "Search": "Pesquisar", "Active:": "Filtros ativos:", "Clear all": "Limpar tudo", "All": "Todos", "Templates": "Templates", "Presets": "Presets", "Music": "Música", "sign-in": "entrar", "Account": "Conta", "You are signed in.": "Sessão iniciada.", "Creator Dashboard": "Painel do criador", "My account & downloads": "A minha conta e downloads", "Log out": "Terminar sessão", "We value your privacy": "Valorizamos a sua privacidade", "We use essential cookies to run this site (cart, login, checkout). With your permission we also use marketing cookies (Meta Pixel) to measure and improve our ads.": "Usamos cookies essenciais para o funcionamento do site (carrinho, login, pagamento). Com a sua autorização, usamos também cookies de marketing (Meta Pixel) para medir e melhorar os nossos anúncios.", "Privacy Policy": "Política de privacidade", "Reject non-essential": "Rejeitar não essenciais", "Accept all": "Aceitar todos", "Cookie settings": "Definições de cookies", "Log in": "Entrar", "Create account": "Criar conta", "Become a vendor": "Tornar-se criador", "Password": "Palavra-passe", "Confirm password": "Confirmar palavra-passe", "Remember me": "Lembrar-me", "Lost your password?": "Esqueceu-se da palavra-passe?", "First name": "Nome", "Last name": "Apelido", "Description": "Descrição", "Reviews": "Avaliações", "Related products": "Produtos relacionados", "Instant download after payment": "Download imediato após o pagamento"}}
SSJSON
		, true );
		return $d;
	}
	add_action( 'wp_footer', function () {
		if ( ! function_exists( 'pll_current_language' ) ) return;
		$l = pll_current_language(); if ( 'ua' === $l ) $l = 'uk'; $d = ss_ui_dict();
		if ( ! $l || empty( $d[ $l ] ) ) return;
		$items = array( 'uk' => array( 'товар', 'товари' ), 'de' => array( 'Artikel', 'Artikel' ), 'fr' => array( 'article', 'articles' ), 'pt' => array( 'item', 'itens' ) );
		?>
<script>
(function(){var D=<?php echo wp_json_encode( $d[ $l ] ); ?>,IT=<?php echo wp_json_encode( $items[ $l ] ); ?>;
var SKIP={SCRIPT:1,STYLE:1,TEXTAREA:1,NOSCRIPT:1,CODE:1};
function tr(n){var v=n.nodeValue,t=v.trim();if(!t)return;var r=D[t];if(!r){var m=t.match(/^(\d+) items?$/);if(m)r=m[1]+' '+(m[1]==='1'?IT[0]:IT[1])}if(r&&r!==t)n.nodeValue=v.replace(t,r)}
function walk(root){if(!root)return;if(root.nodeType===3){if(root.parentNode&&!SKIP[root.parentNode.nodeName])tr(root);return}if(root.nodeType!==1||SKIP[root.nodeName]||root.closest&&root.closest('#wpadminbar'))return;var w=document.createTreeWalker(root,NodeFilter.SHOW_TEXT,null),n;while(n=w.nextNode()){if(n.parentNode&&!SKIP[n.parentNode.nodeName])tr(n)}[].forEach.call(root.querySelectorAll?root.querySelectorAll('[placeholder]'):[],function(e){var p=e.getAttribute('placeholder').trim();if(D[p])e.setAttribute('placeholder',D[p])})}
walk(document.body);var busy=false;new MutationObserver(function(ms){if(busy)return;busy=true;ms.forEach(function(m){if(m.type==='characterData')tr(m.target);else [].forEach.call(m.addedNodes,walk)});busy=false}).observe(document.body,{childList:true,subtree:true,characterData:true});
})();
</script>
		<?php
	}, 99 );
}

/* Solid Sound 15.10.2026: clean language URLs — /ua/ instead of /uk/, localized page slugs without "-uk"/"-de" (run /wp-admin/?ss_fix_slugs=1) */
add_action( 'admin_init', function () {
	if ( empty( $_GET['ss_fix_slugs'] ) || ! current_user_can( 'manage_options' ) || ! function_exists( 'pll_get_post' ) ) return;
	$log = array();
	$slugs = array(
		2    => array( 'uk' => 'holovna', 'de' => 'startseite', 'fr' => 'accueil', 'pt' => 'inicio' ),
		1115 => array( 'uk' => 'shablony', 'de' => 'vorlagen', 'fr' => 'modeles', 'pt' => 'modelos' ),
		1121 => array( 'uk' => 'presety', 'de' => 'voreinstellungen', 'fr' => 'prereglages', 'pt' => 'predefinicoes' ),
		1547 => array( 'uk' => 'muzyka', 'de' => 'musik', 'fr' => 'musique', 'pt' => 'musica' ),
		399  => array( 'uk' => 'kontakty', 'de' => 'kontakt', 'fr' => 'contact', 'pt' => 'contactos' ),
	);
	foreach ( $slugs as $src => $m ) foreach ( $m as $l => $s ) {
		$id = pll_get_post( $src, $l ); if ( ! $id ) continue;
		$r = wp_update_post( array( 'ID' => $id, 'post_name' => $s ), true );
		$log[] = "$l $src -> " . ( is_wp_error( $r ) ? 'ERR' : get_post_field( 'post_name', $id ) );
	}
	if ( function_exists( 'PLL' ) && isset( PLL()->model ) && method_exists( PLL()->model, 'update_language' ) ) {
		$lang = PLL()->model->get_language( 'uk' );
		if ( $lang ) {
			$res = PLL()->model->update_language( array( 'lang_id' => $lang->term_id, 'name' => $lang->name, 'slug' => 'ua', 'locale' => $lang->locale, 'rtl' => 0, 'term_group' => $lang->term_group, 'flag' => 'ua' ) );
			$log[] = 'lang uk -> ua: ' . ( is_wp_error( $res ) ? $res->get_error_message() : 'ok' );
		} else { $log[] = 'lang uk not found (maybe already ua)'; }
	}
	flush_rewrite_rules( false );
	wp_die( '<pre>' . esc_html( implode( "\n", $log ) ) . '</pre>' );
} );

/* ═══ Solid Sound — 15.10.2026: product short descriptions translated (ua/de/fr/pt), exact paragraph match ═══ */
if ( ! function_exists( 'ss_prod_dict' ) ) {
	function ss_prod_dict() {
		return json_decode( <<<'SSPJ'
{
"Time Goes Fast Documentary Piano": {
"ua": "«Time Goes Fast Documentary Piano» — задумлива й емоційна фортепіанна композиція, що передає швидкоплинність часу. Ніжні мелодії та кінематографічна атмосфера ідеально підходять для документальних фільмів, сторітелінгу, проєктів про життя людей і зворушливих моментів спогадів та роздумів.",
"de": "„Time Goes Fast Documentary Piano“ ist eine nachdenkliche, emotionale Klavierkomposition, die die Vergänglichkeit der Zeit einfängt. Sanfte Melodien und eine filmische Atmosphäre machen sie ideal für Dokumentationen, Storytelling, Projekte über menschliche Lebensgeschichten und berührende Momente der Erinnerung.",
"fr": "« Time Goes Fast Documentary Piano » est une composition pour piano émouvante et contemplative qui saisit la fuite du temps. Ses mélodies douces et son atmosphère cinématographique en font un choix idéal pour les documentaires, le storytelling, les projets sur la vie humaine et les moments de souvenir.",
"pt": "«Time Goes Fast Documentary Piano» é uma composição de piano reflexiva e emotiva que capta a natureza efémera do tempo. Melodias suaves e uma atmosfera cinematográfica tornam-na ideal para documentários, storytelling, projetos sobre a vida humana e momentos tocantes de memória e reflexão."},
"The Solid Sound Melodic Techno Serum Preset Collection contains 4 professionally designed sounds that lift your track from the very first notes. Load them into Serum by Xfer Records and start creating. Change wavetables, filters and effects to fit your music, or transform the sounds completely and make them your own.": {
"ua": "Колекція пресетів Solid Sound Melodic Techno для Serum містить 4 професійно створені звуки, які піднімають трек із перших нот. Завантажте їх у Serum від Xfer Records і починайте творити. Змінюйте вейвтейбли, фільтри та ефекти під свою музику або повністю перетворіть звуки й зробіть їх своїми.",
"de": "Die Solid Sound Melodic Techno Serum Preset Collection enthält 4 professionell designte Sounds, die deinen Track vom ersten Ton an aufwerten. Lade sie in Serum von Xfer Records und leg los. Passe Wavetables, Filter und Effekte an deine Musik an oder verwandle die Sounds komplett und mach sie zu deinen eigenen.",
"fr": "La collection de presets Serum Solid Sound Melodic Techno contient 4 sons conçus par des pros qui subliment votre morceau dès les premières notes. Chargez-les dans Serum de Xfer Records et lancez-vous. Modifiez wavetables, filtres et effets selon votre musique, ou transformez totalement les sons pour vous les approprier.",
"pt": "A coleção de presets Solid Sound Melodic Techno para Serum contém 4 sons criados profissionalmente que elevam a sua faixa desde as primeiras notas. Carregue-os no Serum da Xfer Records e comece a criar. Altere wavetables, filtros e efeitos para a sua música ou transforme os sons por completo e torne-os seus."},
"Pitch is a techno template inspired by the legendary Dino Maggiorana. Use it to learn professional techniques and tricks built entirely with native Ableton plugins. The full-length arrangement is easy to customize, so you can shape the sound exactly the way you want.": {
"ua": "Pitch — техно-шаблон, натхненний легендарним Dino Maggiorana. Вивчайте на ньому професійні прийоми та трюки, побудовані виключно на вбудованих плагінах Ableton. Повну аранжировку легко налаштувати, тож ви сформуєте звук саме так, як хочете.",
"de": "Pitch ist ein Techno-Template, inspiriert vom legendären Dino Maggiorana. Lerne damit professionelle Techniken und Tricks, die komplett mit nativen Ableton-Plugins umgesetzt sind. Das vollständige Arrangement lässt sich leicht anpassen, sodass du den Sound genau nach deinen Vorstellungen formst.",
"fr": "Pitch est un template techno inspiré du légendaire Dino Maggiorana. Utilisez-le pour apprendre des techniques et astuces professionnelles réalisées uniquement avec les plugins natifs d’Ableton. L’arrangement complet est facile à personnaliser pour façonner le son exactement comme vous le souhaitez.",
"pt": "Pitch é um template de techno inspirado no lendário Dino Maggiorana. Use-o para aprender técnicas e truques profissionais feitos apenas com plugins nativos do Ableton. O arranjo completo é fácil de personalizar, para moldar o som exatamente como quiser."},
"Mutualism is a fresh take on dark techno, inspired by Adam Beyer and Vitaly Mc’lay. Driving drums, a deep bass line and sharp percussive shots create a dark, hypnotic atmosphere. Open the project to see how every element is built, then use it as a starting point for your own tracks.": {
"ua": "Mutualism — свіжий погляд на дарк-техно, натхненний Adam Beyer і Vitaly Mc’lay. Драйвові ударні, глибокий бас і різкі перкусійні шоти створюють темну гіпнотичну атмосферу. Відкрийте проєкт, щоб побачити, як побудований кожен елемент, і використайте його як основу для власних треків.",
"de": "Mutualism ist eine frische Interpretation von Dark Techno, inspiriert von Adam Beyer und Vitaly Mc’lay. Treibende Drums, eine tiefe Bassline und scharfe Percussion-Shots erzeugen eine dunkle, hypnotische Atmosphäre. Öffne das Projekt, um zu sehen, wie jedes Element gebaut ist, und nutze es als Ausgangspunkt für deine eigenen Tracks.",
"fr": "Mutualism est une approche fraîche de la dark techno, inspirée par Adam Beyer et Vitaly Mc’lay. Batterie entraînante, basse profonde et percussions tranchantes créent une ambiance sombre et hypnotique. Ouvrez le projet pour voir comment chaque élément est construit, puis servez-vous-en comme point de départ pour vos morceaux.",
"pt": "Mutualism é uma nova abordagem ao dark techno, inspirada em Adam Beyer e Vitaly Mc’lay. Bateria enérgica, uma linha de baixo profunda e percussões incisivas criam uma atmosfera escura e hipnótica. Abra o projeto para ver como cada elemento é construído e use-o como ponto de partida para as suas faixas."},
"Intricate is a full-length dark techno template for Ableton Live 11, produced by Grenør. It uses native Ableton plugins only, including FM synthesis for the kick and hi-hats. The arrangement is easy to customize: recreate the sound or reshape it into your own track.": {
"ua": "Intricate — повноцінний дарк-техно шаблон для Ableton Live 11 від Grenør. Використовує лише вбудовані плагіни Ableton, зокрема FM-синтез для кіка та хай-хетів. Аранжировку легко налаштувати: відтворіть звук або перетворіть його на власний трек.",
"de": "Intricate ist ein vollständiges Dark-Techno-Template für Ableton Live 11, produziert von Grenør. Es nutzt ausschließlich native Ableton-Plugins, inklusive FM-Synthese für Kick und Hi-Hats. Das Arrangement ist leicht anpassbar: Bau den Sound nach oder forme daraus deinen eigenen Track.",
"fr": "Intricate est un template dark techno complet pour Ableton Live 11, produit par Grenør. Il n’utilise que des plugins natifs d’Ableton, dont la synthèse FM pour le kick et les hi-hats. L’arrangement est facile à personnaliser : recréez le son ou transformez-le en votre propre morceau.",
"pt": "Intricate é um template completo de dark techno para Ableton Live 11, produzido por Grenør. Usa apenas plugins nativos do Ableton, incluindo síntese FM para o kick e os hi-hats. O arranjo é fácil de personalizar: recrie o som ou transforme-o na sua própria faixa."},
"Infinity is a full-length Ableton Live 11 template produced by Grenør. This driving 130 BPM track in A minor is built with native Ableton plugins only, so no external plugins are needed. It is packed with samples and polyrhythmic, syncopated grooves made for the dance floor. Take it apart to learn how it is made, then use it as a starting point for your own productions. Preview: MP3, 128 kbps.": {
"ua": "Infinity — повноцінний шаблон для Ableton Live 11 від Grenør. Драйвовий трек 130 BPM у ля мінорі зроблений лише на вбудованих плагінах Ableton, тож сторонні плагіни не потрібні. Він насичений семплами та поліритмічними синкопованими грувами для танцполу. Розберіть його, щоб зрозуміти, як він зроблений, і використайте як основу для власних продакшнів. Прев’ю: MP3, 128 kbps.",
"de": "Infinity ist ein vollständiges Ableton-Live-11-Template, produziert von Grenør. Der treibende Track mit 130 BPM in a-Moll ist ausschließlich mit nativen Ableton-Plugins gebaut, externe Plugins sind also nicht nötig. Er steckt voller Samples und polyrhythmischer, synkopierter Grooves für den Dancefloor. Nimm ihn auseinander, um zu lernen, wie er gemacht ist, und nutze ihn als Ausgangspunkt für deine eigenen Produktionen. Vorschau: MP3, 128 kbps.",
"fr": "Infinity est un template complet pour Ableton Live 11, produit par Grenør. Ce morceau entraînant à 130 BPM en la mineur est réalisé uniquement avec des plugins natifs d’Ableton : aucun plugin externe n’est nécessaire. Il regorge de samples et de grooves polyrythmiques et syncopés pensés pour le dancefloor. Décortiquez-le pour apprendre comment il est fait, puis utilisez-le comme point de départ pour vos productions. Aperçu : MP3, 128 kbps.",
"pt": "Infinity é um template completo para Ableton Live 11, produzido por Grenør. Esta faixa enérgica a 130 BPM em lá menor é feita apenas com plugins nativos do Ableton, por isso não precisa de plugins externos. Está repleta de samples e grooves polirrítmicos e sincopados feitos para a pista de dança. Desmonte-a para aprender como foi feita e use-a como ponto de partida para as suas produções. Pré-visualização: MP3, 128 kbps."},
"Kratos is a full-length dark techno template for Ableton Live 10, produced by Grenør. Built with native Ableton plugins only, it is easy to customize: learn how the sound is made, then reshape it into your own track.": {
"ua": "Kratos — повноцінний дарк-техно шаблон для Ableton Live 10 від Grenør. Зроблений лише на вбудованих плагінах Ableton і легко налаштовується: дізнайтеся, як створено звук, і перетворіть його на власний трек.",
"de": "Kratos ist ein vollständiges Dark-Techno-Template für Ableton Live 10, produziert von Grenør. Komplett mit nativen Ableton-Plugins gebaut und leicht anpassbar: Lerne, wie der Sound entsteht, und forme daraus deinen eigenen Track.",
"fr": "Kratos est un template dark techno complet pour Ableton Live 10, produit par Grenør. Réalisé uniquement avec des plugins natifs d’Ableton, il est facile à personnaliser : découvrez comment le son est créé, puis transformez-le en votre propre morceau.",
"pt": "Kratos é um template completo de dark techno para Ableton Live 10, produzido por Grenør. Feito apenas com plugins nativos do Ableton, é fácil de personalizar: aprenda como o som é criado e transforme-o na sua própria faixa."},
"Protos is a full-length techno template for Ableton Live 10, produced by Grenør. Built with native Ableton plugins only, it is packed with samples and polyrhythmic, syncopated grooves made for the dance floor. Use the project as a starting point for your own hits. Preview: MP3, 128 kbps.": {
"ua": "Protos — повноцінний техно-шаблон для Ableton Live 10 від Grenør. Зроблений лише на вбудованих плагінах Ableton і насичений семплами та поліритмічними синкопованими грувами для танцполу. Використайте проєкт як основу для власних хітів. Прев’ю: MP3, 128 kbps.",
"de": "Protos ist ein vollständiges Techno-Template für Ableton Live 10, produziert von Grenør. Komplett mit nativen Ableton-Plugins gebaut, steckt es voller Samples und polyrhythmischer, synkopierter Grooves für den Dancefloor. Nutze das Projekt als Ausgangspunkt für deine eigenen Hits. Vorschau: MP3, 128 kbps.",
"fr": "Protos est un template techno complet pour Ableton Live 10, produit par Grenør. Réalisé uniquement avec des plugins natifs d’Ableton, il regorge de samples et de grooves polyrythmiques et syncopés pensés pour le dancefloor. Utilisez le projet comme point de départ pour vos propres hits. Aperçu : MP3, 128 kbps.",
"pt": "Protos é um template completo de techno para Ableton Live 10, produzido por Grenør. Feito apenas com plugins nativos do Ableton, está repleto de samples e grooves polirrítmicos e sincopados feitos para a pista de dança. Use o projeto como ponto de partida para os seus próprios hits. Pré-visualização: MP3, 128 kbps."}
}
SSPJ
		, true );
	}
	add_action( 'wp_footer', function () {
		if ( ! function_exists( 'pll_current_language' ) ) return;
		$l = pll_current_language(); if ( ! $l || 'en' === $l ) return;
		$m = array(); foreach ( (array) ss_prod_dict() as $en => $tr ) if ( ! empty( $tr[ $l ] ) ) $m[ $en ] = $tr[ $l ];
		if ( ! $m ) return;
		?>
<script>
(function(){var P=<?php echo wp_json_encode( $m ); ?>,K=Object.keys(P);
function norm(s){return s.replace(/\s+/g,' ').trim()}
function fix(root){if(!root||!root.querySelectorAll)return;[].forEach.call(root.querySelectorAll('p,div.woocommerce-product-details__short-description,.w-post-elm.post_content,.usg_post_content_1'),function(e){if(e.children.length&&e.tagName!=='P')return;var t=norm(e.textContent);if(!t)return;if(P[t]){e.textContent=P[t];return}
for(var i=0;i<K.length;i++){var k=K[i];if(t.length>40&&/(…|\.\.\.)$/.test(t)&&k.indexOf(t.replace(/(…|\.\.\.)$/,'').trim())===0){var r=P[k];e.textContent=r.slice(0,t.length-1).replace(/\s+\S*$/,'')+'…';return}
if(t.indexOf('“'+k+'”')===0&&P[k]){e.textContent=P[k];return}}})}
fix(document.body);new MutationObserver(function(ms){ms.forEach(function(m){[].forEach.call(m.addedNodes,function(n){if(n.nodeType===1)fix(n)})})}).observe(document.body,{childList:true,subtree:true});
})();
</script>
		<?php
	}, 98 );
}

/* ═══ Solid Sound — 15.10.2026: products/cart/account (not translatable) follow visitor's chosen language via ss_lang cookie ═══ */
if ( ! function_exists( 'ss_untr_ctx' ) ) {
	function ss_untr_ctx() {
		if ( is_admin() ) return false;
		if ( is_singular( 'product' ) ) return true;
		return function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() );
	}
	add_action( 'template_redirect', function () {
		if ( empty( $_GET['ss_lang'] ) || ! function_exists( 'PLL' ) ) return;
		$s = sanitize_key( wp_unslash( $_GET['ss_lang'] ) );
		if ( PLL()->model->get_language( $s ) ) setcookie( 'ss_lang', $s, time() + YEAR_IN_SECONDS, '/', '', is_ssl(), false );
		wp_safe_redirect( remove_query_arg( 'ss_lang' ) ); exit;
	}, 0 );
	add_action( 'wp', function () {
		if ( ! function_exists( 'PLL' ) || empty( $GLOBALS['ss_forced_lang'] ) ) return;
		$lang = PLL()->model->get_language( $GLOBALS['ss_forced_lang'] );
		if ( $lang ) PLL()->curlang = $lang;
	}, 5 );
	add_action( 'wp_footer', function () {
		if ( ! function_exists( 'pll_current_language' ) || ss_untr_ctx() ) return;
		$l = pll_current_language(); if ( ! $l ) return;
		echo '<script>document.cookie="ss_lang=' . esc_js( $l ) . ';path=/;max-age=31536000;SameSite=Lax"</script>';
	}, 5 );
}

/* ═══ Solid Sound — 15.10.2026: single product / cart UI strings (ua/de/fr/pt) ═══ */
if ( ! function_exists( 'ss_ui_dict2' ) ) {
	function ss_ui_dict2() {
		return json_decode( <<<'SSJ2'
{
"Home":{"ua":"Головна","de":"Start","fr":"Accueil","pt":"Início"},
"Music Production":{"ua":"Музичне продюсування","de":"Musikproduktion","fr":"Production musicale","pt":"Produção musical"},
"customer review)":{"ua":"відгук покупця)","de":"Kundenbewertung)","fr":"avis client)","pt":"avaliação de cliente)"},
"customer reviews)":{"ua":"відгуків покупців)","de":"Kundenbewertungen)","fr":"avis clients)","pt":"avaliações de clientes)"},
"BUY":{"ua":"КУПИТИ","de":"KAUFEN","fr":"ACHETER","pt":"COMPRAR"},
"Buy":{"ua":"Купити","de":"Kaufen","fr":"Acheter","pt":"Comprar"},
"Royalty-free –":{"ua":"Без роялті –","de":"Lizenzfrei –","fr":"Libre de droits –","pt":"Livre de royalties –"},
"license terms":{"ua":"умови ліцензії","de":"Lizenzbedingungen","fr":"conditions de licence","pt":"termos da licença"},
"Secure payment: card & PayPal":{"ua":"Безпечна оплата: картка та PayPal","de":"Sichere Zahlung: Karte & PayPal","fr":"Paiement sécurisé : carte & PayPal","pt":"Pagamento seguro: cartão e PayPal"},
"Refund policy":{"ua":"Повернення коштів","de":"Rückerstattung","fr":"Remboursement","pt":"Reembolsos"},
"Questions?":{"ua":"Питання?","de":"Fragen?","fr":"Des questions ?","pt":"Dúvidas?"},
"Category:":{"ua":"Категорія:","de":"Kategorie:","fr":"Catégorie :","pt":"Categoria:"},
"Categories:":{"ua":"Категорії:","de":"Kategorien:","fr":"Catégories :","pt":"Categorias:"},
"Additional information":{"ua":"Додаткова інформація","de":"Zusätzliche Informationen","fr":"Informations complémentaires","pt":"Informação adicional"},
"Audio channels":{"ua":"Аудіоканали","de":"Audiokanäle","fr":"Pistes audio","pt":"Canais de áudio"},
"MIDI channels":{"ua":"MIDI-канали","de":"MIDI-Kanäle","fr":"Pistes MIDI","pt":"Canais MIDI"},
"Send channels":{"ua":"Send-канали","de":"Send-Kanäle","fr":"Pistes d’envoi","pt":"Canais de envio"},
"1 review for":{"ua":"1 відгук про","de":"1 Bewertung für","fr":"1 avis pour","pt":"1 avaliação para"},
"Only logged in customers who have purchased this product may leave a review.":{"ua":"Залишити відгук можуть лише покупці, які придбали цей товар і увійшли в акаунт.","de":"Nur angemeldete Kunden, die dieses Produkt gekauft haben, können eine Bewertung abgeben.","fr":"Seuls les clients connectés ayant acheté ce produit peuvent laisser un avis.","pt":"Apenas clientes com sessão iniciada que compraram este produto podem deixar uma avaliação."},
"No products in the cart.":{"ua":"Кошик порожній.","de":"Keine Produkte im Warenkorb.","fr":"Votre panier est vide.","pt":"Não há produtos no carrinho."},
"Cart":{"ua":"Кошик","de":"Warenkorb","fr":"Panier","pt":"Carrinho"},
"Checkout":{"ua":"Оформити замовлення","de":"Zur Kasse","fr":"Commander","pt":"Finalizar compra"},
"View cart":{"ua":"Переглянути кошик","de":"Warenkorb ansehen","fr":"Voir le panier","pt":"Ver carrinho"},
"Subtotal:":{"ua":"Проміжний підсумок:","de":"Zwischensumme:","fr":"Sous-total :","pt":"Subtotal:"},
"Subtotal":{"ua":"Проміжний підсумок","de":"Zwischensumme","fr":"Sous-total","pt":"Subtotal"},
"Total":{"ua":"Разом","de":"Gesamt","fr":"Total","pt":"Total"},
"Product":{"ua":"Товар","de":"Produkt","fr":"Produit","pt":"Produto"},
"Price":{"ua":"Ціна","de":"Preis","fr":"Prix","pt":"Preço"},
"Quantity":{"ua":"Кількість","de":"Menge","fr":"Quantité","pt":"Quantidade"},
"Proceed to checkout":{"ua":"Перейти до оформлення","de":"Weiter zur Kasse","fr":"Valider la commande","pt":"Finalizar compra"},
"Cart totals":{"ua":"Сума кошика","de":"Warenkorb-Summe","fr":"Total panier","pt":"Total do carrinho"},
"Coupon code":{"ua":"Код купона","de":"Gutscheincode","fr":"Code promo","pt":"Código de cupão"},
"Apply coupon":{"ua":"Застосувати купон","de":"Gutschein anwenden","fr":"Appliquer le code","pt":"Aplicar cupão"},
"Your cart is currently empty.":{"ua":"Ваш кошик зараз порожній.","de":"Dein Warenkorb ist derzeit leer.","fr":"Votre panier est actuellement vide.","pt":"O seu carrinho está vazio."},
"Return to shop":{"ua":"Повернутися до магазину","de":"Zurück zum Shop","fr":"Retour à la boutique","pt":"Voltar à loja"},
"Billing details":{"ua":"Платіжні дані","de":"Rechnungsdetails","fr":"Détails de facturation","pt":"Dados de faturação"},
"Your order":{"ua":"Ваше замовлення","de":"Deine Bestellung","fr":"Votre commande","pt":"A sua encomenda"},
"Place order":{"ua":"Підтвердити замовлення","de":"Kostenpflichtig bestellen","fr":"Commander","pt":"Fazer encomenda"},
"Email address":{"ua":"Електронна пошта","de":"E-Mail-Adresse","fr":"Adresse e-mail","pt":"Endereço de e-mail"},
"Country / Region":{"ua":"Країна / регіон","de":"Land / Region","fr":"Pays / région","pt":"País / região"},
"Dashboard":{"ua":"Панель","de":"Übersicht","fr":"Tableau de bord","pt":"Painel"},
"Orders":{"ua":"Замовлення","de":"Bestellungen","fr":"Commandes","pt":"Encomendas"},
"Downloads":{"ua":"Завантаження","de":"Downloads","fr":"Téléchargements","pt":"Downloads"},
"Addresses":{"ua":"Адреси","de":"Adressen","fr":"Adresses","pt":"Moradas"},
"Account details":{"ua":"Дані акаунта","de":"Kontodetails","fr":"Détails du compte","pt":"Detalhes da conta"},
"Username or email address":{"ua":"Ім’я користувача або email","de":"Benutzername oder E-Mail-Adresse","fr":"Identifiant ou adresse e-mail","pt":"Nome de utilizador ou e-mail"},
"Login":{"ua":"Вхід","de":"Anmelden","fr":"Connexion","pt":"Entrar"},
"Register":{"ua":"Реєстрація","de":"Registrieren","fr":"S’inscrire","pt":"Registar"},
"No external plugins":{"ua":"Без сторонніх плагінів","de":"Keine externen Plugins","fr":"Aucun plugin externe","pt":"Sem plugins externos"},
"No External Plugins":{"ua":"Без сторонніх плагінів","de":"Keine externen Plugins","fr":"Aucun plugin externe","pt":"Sem plugins externos"},
"Services":{"ua":"Послуги","de":"Leistungen","fr":"Services","pt":"Serviços"},
"SERVICES":{"ua":"ПОСЛУГИ","de":"LEISTUNGEN","fr":"SERVICES","pt":"SERVIÇOS"}
}
SSJ2
		, true );
	}
	add_action( 'wp_footer', function () {
		if ( ! function_exists( 'pll_current_language' ) ) return;
		$l = pll_current_language(); if ( ! $l || 'en' === $l ) return;
		$m = array(); foreach ( (array) ss_ui_dict2() as $en => $tr ) if ( ! empty( $tr[ $l ] ) ) $m[ $en ] = $tr[ $l ];
		?>
<script>
(function(){var D=<?php echo wp_json_encode( $m ); ?>,SK={SCRIPT:1,STYLE:1,TEXTAREA:1,NOSCRIPT:1};
function tr(n){if(!n.parentNode||SK[n.parentNode.nodeName])return;var v=n.nodeValue,t=v.trim();if(t&&D[t])n.nodeValue=v.replace(t,D[t])}
function walk(r){if(!r)return;if(r.nodeType===3){tr(r);return}if(r.nodeType!==1||SK[r.nodeName])return;var w=document.createTreeWalker(r,NodeFilter.SHOW_TEXT),n;while(n=w.nextNode())tr(n);[].forEach.call(r.querySelectorAll('input[type=submit],button[value]'),function(b){var x=(b.value||'').trim();if(D[x])b.value=D[x]})}
walk(document.body);new MutationObserver(function(ms){ms.forEach(function(m){[].forEach.call(m.addedNodes,walk)})}).observe(document.body,{childList:true,subtree:true});
})();
</script>
		<?php
	}, 99 );
}

/* Solid Sound 15.10.2026: UA pages — consistent formal register (run once: /wp-admin/?ss_fix_ua=1) */
add_action( 'admin_init', function () {
	if ( empty( $_GET['ss_fix_ua'] ) || ! current_user_can( 'manage_options' ) || ! function_exists( 'pll_get_post' ) ) return;
	$r = array( 'Перетвори свої ідеї на Solid Sound' => 'Перетворіть свої ідеї на Solid Sound', 'ти можеш вільно редагувати й перебудовувати' => 'ви можете вільно редагувати й переаранжовувати', 'Вчися • Створюй • Випускай' => 'Навчайтеся • Створюйте • Випускайте' );
	$log = array();
	foreach ( array( 2, 1115, 1121, 1547, 399 ) as $src ) { $id = pll_get_post( $src, 'ua' ); if ( ! $id ) continue; $c = get_post_field( 'post_content', $id ); $n = strtr( $c, $r ); if ( $n !== $c ) { wp_update_post( array( 'ID' => $id, 'post_content' => wp_slash( $n ) ) ); $log[] = "$id updated"; } else $log[] = "$id same"; }
	wp_die( '<pre>' . esc_html( implode( "\n", $log ) ) . '</pre>' );
} );

/* ═══ Solid Sound — 15.10.2026: language prefix for products / cart / checkout / account: /ua/product/protos/ ═══ */
if ( ! function_exists( 'ss_lang_url' ) ) {
	$GLOBALS['ss_forced_lang'] = '';
	$GLOBALS['ss_req_path']    = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';
	if ( isset( $_SERVER['REQUEST_URI'] ) && preg_match( '#^/(ua|de|fr|pt|es|it)(/(?:product|product-category|product-tag|shop|cart|checkout|my-account)(?:/|\?|$).*)$#', $_SERVER['REQUEST_URI'], $ssm ) ) {
		$GLOBALS['ss_forced_lang'] = $ssm[1];
		$GLOBALS['ss_req_path']    = $ssm[2];
		$_SERVER['REQUEST_URI']    = $ssm[2];
	}
	function ss_lang_url( $slug ) {
		$p = preg_replace( '#[?&]ss_lang=[^&]*#', '', $GLOBALS['ss_req_path'] );
		return untrailingslashit( get_option( 'home' ) ) . ( 'en' === $slug ? '' : '/' . $slug ) . $p;
	}
	function ss_add_prefix( $url ) {
		if ( is_admin() && ! wp_doing_ajax() ) return $url;
		$l = function_exists( 'pll_current_language' ) ? pll_current_language() : '';
		if ( ! $l || 'en' === $l ) return $url;
		$home = untrailingslashit( get_option( 'home' ) );
		if ( 0 !== strpos( $url, $home . '/' ) ) return $url;
		$path = substr( $url, strlen( $home ) );
		if ( preg_match( '#^/(ua|de|fr|pt|es|it)/#', $path ) ) return $url;   /* already has a language prefix */
		$path = preg_replace( '#^/en(?=/)#', '', $path );                     /* a leading /en/ is the default language — replace it, don't stack /ua/en/ (13.11.2026) */
		return $home . '/' . $l . $path;
	}
	add_filter( 'post_type_link', function ( $url, $post ) { return 'product' === $post->post_type ? ss_add_prefix( $url ) : $url; }, 99, 2 );
	add_filter( 'term_link', function ( $url, $term, $tax ) { return in_array( $tax, array( 'product_cat', 'product_tag' ), true ) || 0 === strpos( $tax, 'pa_' ) ? ss_add_prefix( $url ) : $url; }, 99, 3 );
	add_filter( 'page_link', function ( $url, $id ) {
		if ( ! function_exists( 'wc_get_page_id' ) ) return $url;
		return in_array( (int) $id, array( wc_get_page_id( 'cart' ), wc_get_page_id( 'checkout' ), wc_get_page_id( 'myaccount' ), wc_get_page_id( 'shop' ) ), true ) ? ss_add_prefix( $url ) : $url;
	}, 99, 2 );
}

/* Solid Sound 15.10.2026: keep forced language on WooCommerce pages (Polylang sets page language later) */
add_action( 'template_redirect', function () {
	if ( empty( $GLOBALS['ss_forced_lang'] ) || ! function_exists( 'PLL' ) ) return;
	$lang = PLL()->model->get_language( $GLOBALS['ss_forced_lang'] ); if ( $lang ) PLL()->curlang = $lang;
}, -100 );
foreach ( array( 'wp_head', 'wp_footer', 'wp_body_open' ) as $ss_h ) add_action( $ss_h, function () {
	if ( empty( $GLOBALS['ss_forced_lang'] ) || ! function_exists( 'PLL' ) ) return;
	$lang = PLL()->model->get_language( $GLOBALS['ss_forced_lang'] ); if ( $lang ) PLL()->curlang = $lang;
}, -100 );
add_filter( 'language_attributes', function ( $o ) {
	if ( empty( $GLOBALS['ss_forced_lang'] ) || ! function_exists( 'PLL' ) ) return $o;
	$lang = PLL()->model->get_language( $GLOBALS['ss_forced_lang'] );
	return $lang ? preg_replace( '/lang="[^"]*"/', 'lang="' . esc_attr( str_replace( '_', '-', $lang->locale ) ) . '"', $o ) : $o;
}, 99 );

/* ═══ Solid Sound — 16.10.2026: PRIVACY FIX — emails imported by "Post via email" were published as public posts (orders, contact messages). Hide them everywhere + stop wp-mail.php ═══ */
if ( ! function_exists( 'ss_leak_ids' ) ) {
	add_action( 'wp-mail.php', function () { wp_die( 'Post via email is disabled on this site.', '', array( 'response' => 403 ) ); } );
	add_filter( 'enable_post_by_email_configuration', '__return_false' );
	function ss_leak_ids() {
		$ids = get_transient( 'ss_leak_ids' );
		if ( false !== $ids ) return $ids;
		global $wpdb;
		$ids = array_map( 'intval', $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type='post' AND ( post_content LIKE '%#header_wrapper%' OR post_content LIKE '%received a message from%' OR ID=182 )" ) );
		set_transient( 'ss_leak_ids', $ids, HOUR_IN_SECONDS );
		return $ids;
	}
	add_action( 'pre_get_posts', function ( $q ) {
		if ( is_admin() || current_user_can( 'edit_others_posts' ) ) return;
		$ids = ss_leak_ids(); if ( ! $ids ) return;
		$q->set( 'post__not_in', array_merge( (array) $q->get( 'post__not_in' ), $ids ) );
	} );
	add_action( 'template_redirect', function () {
		if ( current_user_can( 'edit_others_posts' ) ) return;
		$o = get_queried_object();
		if ( ( is_singular( 'post' ) && $o && in_array( (int) $o->ID, ss_leak_ids(), true ) ) || is_author() || is_category() || is_tag() && false ) {
			global $wp_query; $wp_query->set_404(); status_header( 404 ); nocache_headers();
		}
	}, 1 );
	add_filter( 'rest_prepare_post', function ( $r, $post ) {
		return ( in_array( (int) $post->ID, ss_leak_ids(), true ) && ! current_user_can( 'edit_others_posts' ) ) ? new WP_Error( 'rest_post_invalid_id', 'Invalid post ID.', array( 'status' => 404 ) ) : $r;
	}, 10, 2 );
	add_filter( 'wp_sitemaps_posts_query_args', function ( $a, $type ) { if ( 'post' === $type ) $a['post__not_in'] = ss_leak_ids(); return $a; }, 10, 2 );
	add_filter( 'wp_sitemaps_add_provider', function ( $p, $name ) { return 'users' === $name ? false : $p; }, 10, 2 );
	add_filter( 'wp_sitemaps_taxonomies', function ( $t ) { unset( $t['category'], $t['post_format'] ); return $t; } );
	add_filter( 'wp_sitemaps_post_types', function ( $t ) { unset( $t['post'] ); return $t; } );
}

/* ═══ Solid Sound — 16.10.2026: localized SEO titles/descriptions, product hreflang, technical cleanups ═══ */
if ( ! function_exists( 'ss_seo_ml' ) ) {
	function ss_seo_ml_map() {
		return array(
			'es' => array(
				2    => array( 'Plantillas Ableton, presets, mezcla y masterización | Solid Sound', 'Plantillas de Ableton Live libres de royalties, presets de Serum y música para vídeos. Aprende con proyectos profesionales o encarga mezcla y masterización online.' ),
				1115 => array( 'Plantillas de Ableton Live libres de royalties | Solid Sound', 'Plantillas de Ableton Live de techno y tech house. Proyectos completos, mezclados y masterizados con plugins nativos: estudia, reorganiza y publica.' ),
				1121 => array( 'Presets de Serum y sound packs | Solid Sound', 'Presets de Serum para melodic techno y más. Sonidos listos para usar de Solid Sound: descarga inmediata, sin royalties.' ),
				1547 => array( 'Música libre de royalties para vídeos y empresas | Solid Sound', 'Música libre de royalties para vídeos, documentales y proyectos de empresa. Escucha las demos y compra la licencia online.' ),
				399  => array( 'Contacto | Solid Sound', 'Contacta con Solid Sound sobre plantillas Ableton, presets, mezcla y masterización. Respondemos por email.' ),
			),
			'it' => array(
				2    => array( 'Template Ableton, preset, mix e mastering | Solid Sound', 'Template Ableton Live royalty-free, preset per Serum e musica per video. Impara dai progetti professionali o richiedi mix e mastering online.' ),
				1115 => array( 'Template Ableton Live royalty-free | Solid Sound', 'Template Ableton Live techno e tech house. Progetti completi, mixati e masterizzati con plugin nativi: studia, riarrangia, pubblica.' ),
				1121 => array( 'Preset per Serum e sound pack | Solid Sound', 'Preset per Serum per melodic techno e altri generi. Suoni pronti di Solid Sound: download immediato, royalty-free.' ),
				1547 => array( 'Musica royalty-free per video e aziende | Solid Sound', 'Musica royalty-free per video, documentari e progetti aziendali. Ascolta le demo e acquista la licenza online.' ),
				399  => array( 'Contatti | Solid Sound', 'Contatta Solid Sound per template Ableton, preset, mix e mastering. Rispondiamo via email.' ),
			),
			'ua' => array(
				2    => array( 'Шаблони Ableton, пресети, зведення та мастеринг | Solid Sound', 'Шаблони Ableton Live без роялті, пресети Serum і музика для відео. Вчіться на професійних проєктах або замовте онлайн-зведення та мастеринг.' ),
				1115 => array( 'Шаблони Ableton Live без роялті | Solid Sound', 'Шаблони Ableton Live у стилях техно та тек-хаус. Повністю зведені й відмастерені проєкти на вбудованих плагінах: вивчайте, переробляйте, випускайте.' ),
				1121 => array( 'Пресети Serum і саунд-паки | Solid Sound', 'Пресети Serum для мелодійного техно та інших жанрів. Готові звуки від Solid Sound: миттєве завантаження, без роялті.' ),
				1547 => array( 'Музика без роялті для відео та бізнесу | Solid Sound', 'Музика без роялті для відео, документальних фільмів і бізнес-проєктів. Слухайте демо та купуйте ліцензію онлайн.' ),
				399  => array( 'Контакти | Solid Sound', 'Зв’яжіться з Solid Sound щодо шаблонів Ableton, пресетів, зведення та мастерингу. Відповідаємо електронною поштою.' ),
			),
			'de' => array(
				2    => array( 'Ableton Templates, Presets, Mixing & Mastering | Solid Sound', 'Lizenzfreie Ableton Live Templates, Serum-Presets und Musik für Videos. Lerne an Profi-Projekten oder buche Online-Mixing und -Mastering.' ),
				1115 => array( 'Ableton Live Templates – lizenzfrei | Solid Sound', 'Lizenzfreie Ableton Live Templates für Techno und Tech House. Fertig gemischte und gemasterte Projekte mit Stock-Plugins: lernen, remixen, veröffentlichen.' ),
				1121 => array( 'Serum-Presets & Sound Packs | Solid Sound', 'Serum-Presets für Melodic Techno und mehr. Sofort einsetzbare Sounds von Solid Sound – direkter Download, lizenzfrei.' ),
				1547 => array( 'Lizenzfreie Musik für Videos & Unternehmen | Solid Sound', 'Lizenzfreie Musik für Videos, Dokumentationen und Business-Projekte. Demos anhören und die Lizenz online kaufen.' ),
				399  => array( 'Kontakt | Solid Sound', 'Kontaktiere Solid Sound bei Fragen zu Ableton-Templates, Presets, Mixing und Mastering. Wir antworten per E-Mail.' ),
			),
			'fr' => array(
				2    => array( 'Templates Ableton, presets, mixage & mastering | Solid Sound', 'Templates Ableton Live libres de droits, presets Serum et musique pour vidéos. Apprenez avec des projets pro ou commandez un mixage et mastering en ligne.' ),
				1115 => array( 'Templates Ableton Live libres de droits | Solid Sound', 'Templates Ableton Live techno et tech house libres de droits. Projets mixés et masterisés avec les plugins natifs : apprenez, remixez, publiez.' ),
				1121 => array( 'Presets Serum & sound packs | Solid Sound', 'Presets Serum pour la melodic techno et plus encore. Des sons prêts à l’emploi par Solid Sound : téléchargement immédiat, libres de droits.' ),
				1547 => array( 'Musique libre de droits pour vidéos & entreprises | Solid Sound', 'Musique libre de droits pour vidéos, documentaires et projets d’entreprise. Écoutez les démos et achetez la licence en ligne.' ),
				399  => array( 'Contact | Solid Sound', 'Contactez Solid Sound pour toute question sur les templates Ableton, les presets, le mixage et le mastering. Réponse par e-mail.' ),
			),
			'pt' => array(
				2    => array( 'Templates Ableton, presets, mistura e masterização | Solid Sound', 'Templates Ableton Live livres de royalties, presets Serum e música para vídeos. Aprenda com projetos profissionais ou encomende mistura e masterização online.' ),
				1115 => array( 'Templates Ableton Live livres de royalties | Solid Sound', 'Templates Ableton Live de techno e tech house livres de royalties. Projetos misturados e masterizados com plugins nativos: aprenda, remisture, lance.' ),
				1121 => array( 'Presets Serum e sound packs | Solid Sound', 'Presets Serum para melodic techno e muito mais. Sons prontos a usar da Solid Sound: download imediato, livres de royalties.' ),
				1547 => array( 'Música livre de royalties para vídeos e empresas | Solid Sound', 'Música livre de royalties para vídeos, documentários e projetos empresariais. Ouça as demos e compre a licença online.' ),
				399  => array( 'Contactos | Solid Sound', 'Contacte a Solid Sound com dúvidas sobre templates Ableton, presets, mistura e masterização. Respondemos por e-mail.' ),
			),
		);
	}
	function ss_seo_ml() {
		if ( ! function_exists( 'pll_current_language' ) ) return null;
		$l = pll_current_language(); if ( ! $l || 'en' === $l ) return null;
		$map = ss_seo_ml_map(); if ( empty( $map[ $l ] ) ) return null;
		if ( is_page() ) {
			$id = get_queried_object_id();
			foreach ( $map[ $l ] as $src => $v ) if ( (int) pll_get_post( $src, $l ) === (int) $id || (int) $src === (int) $id ) return $v;
			return null;
		}
		if ( function_exists( 'is_product' ) && is_product() && function_exists( 'ss_prod_dict' ) ) {
			$id    = get_queried_object_id();
			$types = array( 'ua' => array( 'Шаблон Ableton Live', 'Пресети Serum', 'Музика без роялті' ), 'de' => array( 'Ableton Live Template', 'Serum-Presets', 'Lizenzfreie Musik' ), 'fr' => array( 'Template Ableton Live', 'Presets Serum', 'Musique libre de droits' ), 'pt' => array( 'Template Ableton Live', 'Presets Serum', 'Música livre de royalties' ), 'es' => array( 'Plantilla Ableton Live', 'Presets de Serum', 'Música libre de royalties' ), 'it' => array( 'Template Ableton Live', 'Preset per Serum', 'Musica royalty-free' ) );
			$f = get_the_terms( $id, 'product-format' ); $k = 0;
			if ( $f && ! is_wp_error( $f ) ) foreach ( $f as $t ) { if ( 'presets' === $t->slug ) $k = 1; elseif ( 'music' === $t->slug ) $k = 2; }
			$g = get_the_terms( $id, 'product-genre' ); $genre = ( $g && ! is_wp_error( $g ) ) ? $g[0]->name . ' ' : '';
			$title = get_the_title( $id ) . ' – ' . ( 2 === $k ? $types[ $l ][2] : $genre . $types[ $l ][ $k ] ) . ' | Solid Sound';
			$raw = wp_strip_all_tags( wptexturize( strip_shortcodes( preg_replace( '#\[/?audio[^\]]*\]#i', '', get_post_field( 'post_excerpt', $id ) ) ) ) );
			$raw = trim( preg_replace( '/\s+/u', ' ', html_entity_decode( $raw, ENT_QUOTES, 'UTF-8' ) ) );
			$desc = '';
			foreach ( (array) ss_prod_dict() as $en => $tr ) if ( ! empty( $tr[ $l ] ) && ( false !== strpos( $raw, $en ) || false !== strpos( $en, mb_substr( $raw, 0, 60 ) ) || false !== strpos( $raw, '“' . $en . '”' ) ) ) { $desc = $tr[ $l ]; break; }
			if ( mb_strlen( $desc ) > 155 ) $desc = rtrim( mb_substr( $desc, 0, 152 ), " ,.;:" ) . '…';
			return array( $title, $desc ? $desc : null );
		}
		return null;
	}
	add_action( 'wp_head', function () {
		if ( ! function_exists( 'is_product' ) || ! is_product() || ! function_exists( 'pll_languages_list' ) ) return;
		$home = untrailingslashit( get_option( 'home' ) );
		$path = wp_parse_url( get_post_permalink( get_queried_object_id() ), PHP_URL_PATH );
		$path = preg_replace( '#^/(ua|de|fr|pt|es|it)/#', '/', $path );
		$loc  = array( 'en' => 'en', 'ua' => 'uk', 'de' => 'de', 'fr' => 'fr', 'pt' => 'pt', 'es' => 'es', 'it' => 'it' );
		foreach ( pll_languages_list() as $s ) {
			$u = $home . ( 'en' === $s ? '' : '/' . $s ) . $path;
			echo '<link rel="alternate" hreflang="' . esc_attr( isset( $loc[ $s ] ) ? $loc[ $s ] : $s ) . '" href="' . esc_url( $u ) . "\" />\n";
		}
		echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $home . $path ) . "\" />\n";
	}, 2 );
	add_filter( 'wp_sitemaps_posts_query_args', function ( $a, $type ) { if ( 'product' === $type ) $a['post__not_in'] = array_merge( isset( $a['post__not_in'] ) ? (array) $a['post__not_in'] : array(), array( 1971 ) ); return $a; }, 10, 2 );
	add_filter( 'wp_robots', function ( $r ) { if ( is_singular() && 1971 === (int) get_queried_object_id() ) { $r['noindex'] = true; $r['follow'] = true; } return $r; } );
	add_action( 'template_redirect', function () {
		if ( is_tax( 'post_format' ) && ! current_user_can( 'edit_others_posts' ) ) { global $wp_query; $wp_query->set_404(); status_header( 404 ); nocache_headers(); }
	}, 1 );
}

/* ═══ Solid Sound — 16.10.2026: small SEO/UX fixes (taxonomy meta, x-default, shop noindex, tap targets) + one-time fixer /wp-admin/?ss_fix_misc=1 ═══ */
if ( ! function_exists( 'ss_seo_tax' ) ) {
	function ss_seo_tax() {
		if ( ! ( is_tax() || is_category() || is_tag() ) ) return null;
		$t = get_queried_object(); if ( ! $t || empty( $t->name ) ) return null;
		$labels = array( 'product-format' => 'Format', 'product-genre' => 'Genre', 'product-version' => 'Ableton Live Version', 'daw' => 'DAW', 'external-plugin' => 'Plugins', 'product_cat' => 'Category', 'product_tag' => 'Tag', 'pa_mood' => 'Mood', 'mood' => 'Mood' );
		$lab = isset( $labels[ $t->taxonomy ] ) ? $labels[ $t->taxonomy ] : 'Products';
		$title = $t->name . ' – Ableton Templates, Presets & Music | Solid Sound';
		$desc = trim( wp_strip_all_tags( (string) $t->description ) );
		if ( ! $desc ) $desc = 'Browse ' . $t->name . ' (' . strtolower( $lab ) . ') at Solid Sound: royalty-free Ableton Live templates, Serum presets and music. Listen to demos, instant download.';
		if ( mb_strlen( $desc ) > 158 ) $desc = rtrim( mb_substr( $desc, 0, 155 ), ' ,.;:' ) . '…';
		return array( $title, $desc );
	}
	add_filter( 'pll_rel_hreflang_attributes', function ( $h ) { if ( is_array( $h ) && ! empty( $h['en'] ) && empty( $h['x-default'] ) ) $h['x-default'] = $h['en']; return $h; } );
	add_filter( 'wp_robots', function ( $r ) { if ( function_exists( 'is_shop' ) && is_shop() ) { $r['noindex'] = true; $r['follow'] = true; } return $r; } );
	add_action( 'wp_head', function () { ?>
<style id="ss-tap">@media(max-width:767px){html body .w-grid.layout_195 .w-grid-item .w-post-elm:not(.post_image) a:not(.w-btn){display:inline-block;padding:7px 2px;margin:-7px 0}}</style>
	<?php }, 99 );
	add_action( 'admin_init', function () {
		if ( empty( $_GET['ss_fix_misc'] ) || ! current_user_can( 'manage_options' ) ) return;
		$log = array();
		if ( function_exists( 'ss_leak_ids' ) ) { delete_transient( 'ss_leak_ids' ); $n = 0; foreach ( ss_leak_ids() as $id ) { if ( 'publish' === get_post_status( $id ) ) { wp_update_post( array( 'ID' => $id, 'post_status' => 'private' ) ); $n++; } } $log[] = "posts -> private: $n of " . count( ss_leak_ids() ); }
		$pg = function ( $slug ) { $p = get_page_by_path( $slug ); return $p ? $p : null; };
		$edit = function ( $slug, $cb ) use ( $pg, &$log ) { $p = $pg( $slug ); if ( ! $p ) { $log[] = "$slug: not found"; return; } $c = $p->post_content; $n = $cb( $c ); if ( $n !== $c ) { wp_update_post( array( 'ID' => $p->ID, 'post_content' => wp_slash( $n ) ) ); $log[] = "$slug: updated"; } else $log[] = "$slug: no change"; };
		$drop_empty_h1 = function ( $c ) { $c = preg_replace( '#<!-- wp:heading \{[^}]*"level":1[^}]*\} -->\s*<h1[^>]*>\s*</h1>\s*<!-- /wp:heading -->#', '', $c ); return preg_replace( '#<h1[^>]*>\s*(&nbsp;)?\s*</h1>#', '', $c ); };
		$edit( 'privacy-policy', $drop_empty_h1 );
		$edit( 'refund_returns', $drop_empty_h1 );
		$edit( 'license', function ( $c ) use ( $drop_empty_h1 ) { $c = $drop_empty_h1( $c ); return preg_replace( '#<h1([^>]*)>(\s*Licensing FAQ\s*)</h1>#', '<h2$1>$2</h2>', $c ); } );
		$edit( 'faq', function ( $c ) { return preg_replace( '#(\[us_post_title[^\]]*?)tag="h2"#', '$1tag="h1"', $c, 1 ); } );
		$add_h1 = function ( $txt ) { return function ( $c ) use ( $txt ) { return false !== stripos( $c, '<h1' ) ? $c : '<h1 class="ss-page-h1">' . $txt . '</h1>' . "\n" . $c; }; };
		$edit( 'creator-terms', $add_h1( 'Solid Sound Creator Terms' ) );
		$edit( 'vendor-membership', $add_h1( 'Become a Solid Sound Creator' ) );
		$edit( 'vendor-register', $add_h1( 'Creator Registration' ) );
		wp_die( '<pre>' . esc_html( implode( "\n", $log ) ) . '</pre>' );
	} );
}

/* Solid Sound 16.10.2026: heading fixes at render time (empty H1 blocks, FAQ title as H1) */
add_action( 'template_redirect', function () {
	if ( ! is_page( array( 'faq', 'privacy-policy', 'refund_returns', 'license' ) ) ) return;
	ob_start( function ( $html ) {
		$html = preg_replace( '#<h1[^>]*>(\s|&nbsp;|\x{00A0}|<br\s*/?>)*</h1>#iu', '', $html );
		if ( is_page( 'faq' ) ) $html = preg_replace( '#<h2(\s[^>]*class="[^"]*post_title[^"]*"[^>]*)>(.*?)</h2>#s', '<h1$1>$2</h1>', $html, 1 );
		return $html;
	} );
}, 50 );

/* ═══ Solid Sound — 16.10.2026: Add Product — BPM / Key / Length suggestions from the demo MP3 (browser-side analysis, creator chooses) ═══ */
if ( ! function_exists( 'ss_az_is_form' ) ) {
	function ss_az_is_form() {
		global $wp;
		return ( isset( $wp->query_vars ) && array_key_exists( 'ss-add-product', (array) $wp->query_vars ) ) || is_page( 'add-product' );
	}
	/* find or create the BPM / Key / Length term chosen by the creator */
	add_action( 'wp_ajax_ss_az_term', function () {
		check_ajax_referer( 'ss_az', 'nonce' );
		if ( ! is_user_logged_in() || ! ( current_user_can( 'edit_products' ) || current_user_can( 'manage_woocommerce' ) || ( function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor() ) ) ) wp_send_json_error( 'denied', 403 );
		$kind = sanitize_key( wp_unslash( $_POST['kind'] ?? '' ) );
		$val  = trim( sanitize_text_field( wp_unslash( $_POST['value'] ?? '' ) ) );
		$fkey = sanitize_text_field( wp_unslash( $_POST['field_key'] ?? '' ) );
		$keys = function_exists( 'ss_ap_keys' ) ? ss_ap_keys() : array();
		$map  = array( 'bpm' => 'bpm', 'key' => 'key', 'len' => 'length' );
		if ( ! isset( $map[ $kind ] ) || empty( $keys[ $map[ $kind ] ] ) || $keys[ $map[ $kind ] ] !== $fkey ) wp_send_json_error( 'field' );
		$field = function_exists( 'acf_get_field' ) ? acf_get_field( $fkey ) : null;
		$tax   = $field && ! empty( $field['taxonomy'] ) ? $field['taxonomy'] : '';
		if ( ! $tax || ! taxonomy_exists( $tax ) ) wp_send_json_error( 'tax' );
		$names = array();
		if ( 'bpm' === $kind ) {
			if ( ! is_numeric( $val ) || $val < 50 || $val > 220 ) wp_send_json_error( 'bpm' );
			$v = (float) $val; $names[] = ( floor( $v ) == $v ) ? (string) (int) $v : rtrim( rtrim( number_format( $v, 1, '.', '' ), '0' ), '.' );
		} elseif ( 'key' === $kind ) {
			if ( ! preg_match( '/^([A-G])(#|b)?(m)?$/', $val, $mm ) ) wp_send_json_error( 'key' );
			$flat2sharp = array( 'Db' => 'C#', 'Eb' => 'D#', 'Gb' => 'F#', 'Ab' => 'G#', 'Bb' => 'A#' );
			$n = $mm[1] . ( $mm[2] ?? '' ); if ( isset( $flat2sharp[ $n ] ) ) $n = $flat2sharp[ $n ];
			$names[] = $n . ( ! empty( $mm[3] ) ? 'm' : '' );
		} else {
			if ( ! preg_match( '/^(\d{1,2}):(\d{2})$/', $val, $mm ) || (int) $mm[2] > 59 ) wp_send_json_error( 'len' );
			$names[] = sprintf( '%02d:%02d', (int) $mm[1], (int) $mm[2] );
			$names[] = sprintf( '%d:%02d', (int) $mm[1], (int) $mm[2] );
		}
		foreach ( $names as $nm ) {
			$t = get_term_by( 'name', $nm, $tax );
			if ( $t && ! is_wp_error( $t ) ) wp_send_json_success( array( 'id' => (int) $t->term_id, 'text' => $t->name, 'created' => false ) );
		}
		remove_all_filters( 'pre_insert_term', 10 );
		$r = wp_insert_term( $names[0], $tax );
		if ( is_wp_error( $r ) ) {
			$id = $r->get_error_data( 'term_exists' );
			if ( $id ) wp_send_json_success( array( 'id' => (int) $id, 'text' => $names[0], 'created' => false ) );
			wp_send_json_error( $r->get_error_message() );
		}
		wp_send_json_success( array( 'id' => (int) $r['term_id'], 'text' => $names[0], 'created' => true ) );
	} );
	add_action( 'wp_footer', function () {
		if ( ! is_user_logged_in() || ! ss_az_is_form() ) return;
		echo '<script>window.ssAz=' . wp_json_encode( array( 'ajax' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'ss_az' ) ) ) . ';</script>' . "\n";
		echo "<script id=\"ss-az-js\">\n";
		echo <<<'SSAZ'
(function (g) {
  'use strict';
  var NOTES = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];
  var KK_MAJ = [6.35, 2.23, 3.48, 2.33, 4.38, 4.09, 2.52, 5.19, 2.39, 3.66, 2.29, 2.88];
  var KK_MIN = [6.33, 2.68, 3.52, 5.38, 2.60, 3.53, 2.54, 4.75, 3.98, 2.69, 3.34, 3.17];
  var CAMELOT_MAJ = ['8B', '3B', '10B', '5B', '12B', '7B', '2B', '9B', '4B', '11B', '6B', '1B'];
  var CAMELOT_MIN = ['5A', '12A', '7A', '2A', '9A', '4A', '11A', '6A', '1A', '8A', '3A', '10A'];
  function fft(re, im) {
    var n = re.length, i, j = 0, k, m, t;
    for (i = 1; i < n; i++) { k = n >> 1; while (j & k) { j ^= k; k >>= 1; } j |= k; if (i < j) { t = re[i]; re[i] = re[j]; re[j] = t; t = im[i]; im[i] = im[j]; im[j] = t; } }
    for (m = 2; m <= n; m <<= 1) {
      var h = m >> 1, a = -2 * Math.PI / m, wr = Math.cos(a), wi = Math.sin(a);
      for (i = 0; i < n; i += m) {
        var cr = 1, ci = 0;
        for (k = 0; k < h; k++) {
          var p = i + k, q = p + h, xr = re[q] * cr - im[q] * ci, xi = re[q] * ci + im[q] * cr;
          re[q] = re[p] - xr; im[q] = im[p] - xi; re[p] += xr; im[p] += xi;
          t = cr * wr - ci * wi; ci = cr * wi + ci * wr; cr = t;
        }
      }
    }
  }
  function hann(n) { var w = new Float32Array(n); for (var i = 0; i < n; i++) w[i] = 0.5 - 0.5 * Math.cos(2 * Math.PI * i / (n - 1)); return w; }
  function corr(a, b) { var ma = 0, mb = 0, n = a.length, i, sa = 0, sb = 0, s = 0; for (i = 0; i < n; i++) { ma += a[i]; mb += b[i]; } ma /= n; mb /= n; for (i = 0; i < n; i++) { s += (a[i] - ma) * (b[i] - mb); sa += (a[i] - ma) * (a[i] - ma); sb += (b[i] - mb) * (b[i] - mb); } return s / Math.sqrt(sa * sb || 1); }
  function tempo(x, sr) {
    var N = 1024, H = 256, w = hann(N), nb = N / 2, frames = Math.floor((x.length - N) / H);
    if (frames < 64) return { bpm: 0, conf: 0 };
    var prev = new Float32Array(nb), env = new Float32Array(frames), re = new Float32Array(N), im = new Float32Array(N);
    var maxBin = Math.floor(8000 / (sr / N));
    for (var f = 0; f < frames; f++) {
      var o = f * H, i;
      for (i = 0; i < N; i++) { re[i] = x[o + i] * w[i]; im[i] = 0; }
      fft(re, im);
      var flux = 0;
      for (i = 1; i < maxBin; i++) { var mag = Math.log(1 + 100 * Math.sqrt(re[i] * re[i] + im[i] * im[i])), d = mag - prev[i]; if (d > 0) flux += d * (i < 12 ? 2 : 1); prev[i] = mag; }
      env[f] = flux;
    }
    var fps = sr / H, win = Math.round(fps * 0.4), cs = new Float64Array(frames + 1), e2 = new Float32Array(frames);
    for (f = 0; f < frames; f++) cs[f + 1] = cs[f] + env[f];
    for (f = 0; f < frames; f++) { var a0 = Math.max(0, f - win), b0 = Math.min(frames, f + win + 1), m = (cs[b0] - cs[a0]) / (b0 - a0); e2[f] = Math.max(0, env[f] - m); }
    function ac(lag) { var s = 0, L = Math.floor(lag), fr = lag - L; for (var t = 0; t + L + 1 < frames; t++) s += e2[t] * (e2[t + L] * (1 - fr) + e2[t + L + 1] * fr); return s; }
    var best = 0, bestBpm = 0, scores = [];
    for (var bpm = 60; bpm <= 200; bpm += 0.25) {
      var lag = 60 * fps / bpm;
      var s = ac(lag) + 0.5 * ac(2 * lag) + 0.25 * ac(4 * lag);
      var prior = Math.exp(-0.5 * Math.pow(Math.log2(bpm / 125) / 0.45, 2));
      scores.push([bpm, s * prior]);
      if (s * prior > best) { best = s * prior; bestBpm = bpm; }
    }
    var vals = scores.map(function (p) { return p[1]; }).sort(function (a, b) { return a - b; });
    var conf = best / (vals[Math.floor(vals.length / 2)] || 1);
    var fb = bestBpm, fs = -1;
    for (var bb = bestBpm - 0.6; bb <= bestBpm + 0.6; bb += 0.02) { var lg = 60 * fps / bb, ss = 0; for (var kk = 1; kk <= 16; kk *= 2) ss += ac(kk * lg) / Math.sqrt(kk); if (ss > fs) { fs = ss; fb = bb; } }
    var r = Math.round(fb * 2) / 2;
    if (Math.abs(r - Math.round(r)) < 0.3) r = Math.round(r);
    return { bpm: r, conf: conf };
  }
  function key(x, sr) {
    var N = 8192, H = 2048, w = hann(N), frames = Math.floor((x.length - N) / H);
    var chroma = new Float64Array(12), re = new Float32Array(N), im = new Float32Array(N), binHz = sr / N;
    var lo = Math.ceil(50 / binHz), hi = Math.floor(2000 / binHz), nb = hi - lo + 1, pc = new Int8Array(hi + 1), wt = new Float32Array(hi + 1);
    for (var b = lo; b <= hi; b++) { var midi = 69 + 12 * Math.log2(b * binHz / 440), r = Math.round(midi); pc[b] = ((r % 12) + 12) % 12; wt[b] = Math.exp(-Math.pow(midi - r, 2) / 0.08); }
    if (frames < 4) return null;
    var S = new Float32Array(frames * nb), f, i;
    for (f = 0; f < frames; f++) {
      var o = f * H;
      for (i = 0; i < N; i++) { re[i] = x[o + i] * w[i]; im[i] = 0; }
      fft(re, im);
      for (b = lo; b <= hi; b++) S[f * nb + b - lo] = Math.sqrt(re[b] * re[b] + im[b] * im[b]);
    }
    for (f = 0; f < frames; f++) {
      var fc = new Float64Array(12), tot = 0, row = f * nb;
      for (b = 1; b < nb - 1; b++) {
        var v = S[row + b];
        if (v > S[row + b - 1] && v >= S[row + b + 1]) { v *= wt[b + lo]; fc[pc[b + lo]] += v; tot += v; }
      }
      if (tot > 0) for (i = 0; i < 12; i++) chroma[i] += fc[i] / tot;
    }
    var cands = [];
    for (var t = 0; t < 12; t++) {
      var rot = [];
      for (var k = 0; k < 12; k++) rot.push(chroma[(k + t) % 12]);
      cands.push({ tonic: t, scale: 'major', s: corr(rot, KK_MAJ) }, { tonic: t, scale: 'minor', s: corr(rot, KK_MIN) });
    }
    cands.sort(function (p, q) { return q.s - p.s; });
    var out = cands.slice(0, 3).map(function (c) {
      var mi = c.scale === 'minor';
      return { key: NOTES[c.tonic], scale: c.scale, label: NOTES[c.tonic] + (mi ? ' minor' : ' major'), short: NOTES[c.tonic] + (mi ? 'm' : ''), camelot: (mi ? CAMELOT_MIN : CAMELOT_MAJ)[c.tonic], score: c.s };
    });
    out[0].conf = cands[0].s - cands[1].s;
    out[0].alternatives = out.slice(1);
    return out[0];
  }
  function mmss(sec) { sec = Math.round(sec); var m = Math.floor(sec / 60), s = sec % 60; return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s; }
  g.ssAnalyzeAudio = function (file) {
    var SR = 22050;
    return file.arrayBuffer().then(function (buf) {
      var AC = g.AudioContext || g.webkitAudioContext, ctx = new AC();
      return ctx.decodeAudioData(buf).then(function (ab) { try { ctx.close(); } catch (e) {} return ab; });
    }).then(function (ab) {
      var dur = ab.duration, start = dur > 120 ? dur * 0.2 : 0, len = Math.min(dur - start, 100);
      var off = new OfflineAudioContext(1, Math.ceil(len * SR), SR), src = off.createBufferSource();
      src.buffer = ab; src.connect(off.destination); src.start(0, start, len);
      return off.startRendering().then(function (r) {
        var x = r.getChannelData(0);
        return new Promise(function (res) { setTimeout(function () {
          var t = tempo(x, SR), k = key(x, SR);
          var alt = [t.bpm / 2, t.bpm * 2].filter(function (v) { return v >= 60 && v <= 200; }).map(function (v) { return Math.round(v * 2) / 2; });
          res({ duration: dur, length: mmss(dur), bpm: t.bpm, bpmConf: t.conf, bpmAlternatives: alt, key: k });
        }, 30); });
      });
    });
  };
})(window);
(function () {
  'use strict';
  if (!window.ssAnalyzeAudio || document.getElementById('ss-az')) return;
  var af = document.querySelector('.acf-field'), form = document.querySelector('form.acf-form, #ss-ap-form, .ss-ap form, form#post') || (af && (af.closest('form') || af.parentNode));
  if (!form) return;
  function fieldBy(re) {
    var f = [].filter.call(document.querySelectorAll('.acf-field'), function (el) {
      var l = el.querySelector(':scope > .acf-label label, :scope > .acf-label');
      return l && re.test(l.textContent.replace(/\*/g, '').trim());
    });
    return f[0] || null;
  }
  var F = {
    bpm: function () { return document.querySelector('.acf-field[data-name^="bpm"]') || fieldBy(/^\s*bpm/i); },
    key: function () { return document.querySelector('.acf-field[data-name^="key"]') || fieldBy(/^\s*(root\s*)?key/i); },
    len: function () { return document.querySelector('.acf-field[data-name^="length"]') || fieldBy(/^\s*(length|duration)/i); }
  };
  function norm(s) { return String(s).toLowerCase().replace(/♯/g, '#').replace(/♭/g, 'b').replace(/\s+/g, ' ').trim(); }
  var FLAT = { 'C#': 'Db', 'D#': 'Eb', 'F#': 'Gb', 'G#': 'Ab', 'A#': 'Bb' };
  function keyAliases(k) {
    var n = k.key, mi = k.scale === 'minor', out = [];
    [n, FLAT[n]].forEach(function (x) {
      if (!x) return;
      if (mi) out.push(x + ' minor', x + 'm', x + ' min', x + 'min', x + ' moll'); else out.push(x + ' major', x, x + ' maj', x + 'maj', x + ' dur');
    });
    out.push(k.camelot);
    return out.map(norm);
  }
  function fire(el) { ['input', 'change'].forEach(function (t) { el.dispatchEvent(new Event(t, { bubbles: true })); }); if (window.jQuery) jQuery(el).trigger('change'); }
  function setField(field, values) {
    if (!field) return false;
    var vals = values.map(norm);
    var sel = field.querySelector('select');
    if (sel) {
      var opt = [].filter.call(sel.options, function (o) { return vals.indexOf(norm(o.textContent)) > -1 || vals.indexOf(norm(o.value)) > -1; })[0];
      if (!opt) return false;
      if (sel.multiple) opt.selected = true; else sel.value = opt.value;
      fire(sel); return true;
    }
    var choices = field.querySelectorAll('input[type=radio], input[type=checkbox]');
    if (choices.length) {
      var hit = [].filter.call(choices, function (c) {
        var lab = c.closest('label'); var txt = lab ? lab.textContent : (c.nextSibling ? c.nextSibling.textContent : '');
        return vals.indexOf(norm(txt)) > -1 || vals.indexOf(norm(c.value)) > -1;
      })[0];
      if (!hit) return false;
      if (!hit.checked) hit.click();
      return true;
    }
    var inp = field.querySelector('input[type=text], input[type=number], input:not([type])');
    if (inp) { inp.value = values[0]; fire(inp); return true; }
    return false;
  }
  var css = document.createElement('style');
  css.textContent = '#ss-az{margin:14px 0 18px;width:100%;box-sizing:border-box;padding:14px 16px;border:1px solid #d9e1ec;border-radius:12px;background:#f6f9fc;font-size:14px;color:#1d2433}' +
    '#ss-az .az-h{display:flex;align-items:center;gap:8px;margin-bottom:8px;font-weight:600}' +
    '#ss-az .az-row{display:flex;flex-wrap:wrap;align-items:center;gap:6px;margin:6px 0}#ss-az .az-l{min-width:64px;color:#5b6577}' +
    '#ss-az button.az-c{border:1px solid #c7d2e2;background:#fff;border-radius:999px;padding:4px 12px;cursor:pointer;font-size:13px;color:#1d2433;line-height:1.4}' +
    '#ss-az button.az-c:hover{border-color:#2563eb}#ss-az button.az-c.on{background:#2563eb;border-color:#2563eb;color:#fff}' +
    '#ss-az button.az-c.main{font-weight:600}#ss-az .az-n{color:#8a93a3;font-size:12px;font-weight:400}' +
    '#ss-az .az-a{margin-top:10px;display:flex;gap:8px;align-items:center;flex-wrap:wrap}#ss-az .az-go{background:#2563eb;color:#fff;border:0;border-radius:8px;padding:8px 14px;cursor:pointer;font-weight:600}' +
    '#ss-az .az-msg{color:#5b6577;font-size:12px}#ss-az .az-spin{width:14px;height:14px;border:2px solid #c7d2e2;border-top-color:#2563eb;border-radius:50%;animation:azs 1s linear infinite}@keyframes azs{to{transform:rotate(360deg)}}';
  document.head.appendChild(css);
  var box = document.createElement('div'); box.id = 'ss-az'; box.style.display = 'none';
  var drop = document.querySelector('.ss-wm-drop'), anchor = F.bpm() || F.key() || F.len();
  if (drop) drop.parentNode.insertBefore(box, drop.nextSibling); else if (anchor) anchor.parentNode.insertBefore(box, anchor); else form.insertBefore(box, form.firstChild);
  var R = null, pick = {};
  function chip(kind, val, label, main, note) {
    var b = document.createElement('button'); b.type = 'button'; b.className = 'az-c' + (main ? ' main' : '');
    b.textContent = label; if (note) b.title = note;
    b.addEventListener('click', function () {
      pick[kind] = val;
      [].forEach.call(box.querySelectorAll('button.az-c[data-k="' + kind + '"]'), function (x) { x.classList.remove('on'); });
      b.classList.add('on'); apply([kind]);
    });
    b.setAttribute('data-k', kind); return b;
  }
  function setTax(field, kind, value) {
    var sel = field && field.querySelector('select[data-ajax="1"]');
    if (!sel || !window.ssAz) return null;
    var fd = new FormData();
    fd.append('action', 'ss_az_term'); fd.append('nonce', window.ssAz.nonce);
    fd.append('field_key', field.getAttribute('data-key')); fd.append('kind', kind); fd.append('value', value);
    return fetch(window.ssAz.ajax, { method: 'POST', body: fd, credentials: 'same-origin' }).then(function (r) { return r.json(); }).then(function (j) {
      if (!j || !j.success) return false;
      var id = String(j.data.id), $ = window.jQuery;
      if (![].some.call(sel.options, function (o) { return o.value === id; })) sel.appendChild(new Option(j.data.text, id, false, false));
      if ($) $(sel).val([id]).trigger('change'); else { [].forEach.call(sel.options, function (o) { o.selected = o.value === id; }); fire(sel); }
      return true;
    }).catch(function () { return false; });
  }
  function apply(kinds) {
    var msg = [], jobs = [];
    kinds.forEach(function (k) {
      var field = k === 'bpm' ? F.bpm() : k === 'key' ? F.key() : F.len();
      var val = k === 'bpm' ? String(pick.bpm) : k === 'key' ? pick.key.short : R.length;
      var name = k === 'len' ? 'Length' : k === 'key' ? 'Key' : 'BPM';
      var p = setTax(field, k, val);
      if (p === null) {
        var ok = k === 'bpm' ? setField(field, [String(pick.bpm), String(Math.round(pick.bpm))])
          : k === 'key' ? setField(field, keyAliases(pick.key).concat([pick.key.label]))
          : setField(field, [R.length, R.length.replace(/^0/, '')]);
        p = Promise.resolve(ok);
      }
      jobs.push(p.then(function (ok) { if (!ok) msg.push(name + ': could not set — please choose it manually'); }));
    });
    var m = box.querySelector('.az-msg'); m.textContent = 'Applying…';
    Promise.all(jobs).then(function () { m.textContent = msg.length ? msg.join(' · ') : 'Applied. You can still change any value below.'; });
  }
  function render() {
    box.innerHTML = '<div class="az-h">🎧 Detected from your demo <span class="az-n">— suggestions only, nothing is changed until you choose</span></div>';
    var r1 = document.createElement('div'); r1.className = 'az-row'; r1.innerHTML = '<span class="az-l">BPM</span>';
    r1.appendChild(chip('bpm', R.bpm, String(R.bpm), true));
    R.bpmAlternatives.forEach(function (v) { r1.appendChild(chip('bpm', v, String(v), false, v < R.bpm ? 'half-time' : 'double-time')); });
    var r2 = document.createElement('div'); r2.className = 'az-row'; r2.innerHTML = '<span class="az-l">Key</span>';
    [R.key].concat(R.key.alternatives || []).forEach(function (k, i) { r2.appendChild(chip('key', k, k.label + ' · ' + k.camelot, i === 0)); });
    var r3 = document.createElement('div'); r3.className = 'az-row'; r3.innerHTML = '<span class="az-l">Length</span>';
    r3.appendChild(chip('len', R.length, R.length, true));
    var a = document.createElement('div'); a.className = 'az-a';
    var go = document.createElement('button'); go.type = 'button'; go.className = 'az-go'; go.textContent = 'Use suggested values';
    go.addEventListener('click', function () {
      pick = { bpm: R.bpm, key: R.key, len: R.length };
      [].forEach.call(box.querySelectorAll('button.az-c.main'), function (x) { x.classList.add('on'); });
      apply(['bpm', 'len']);
    });
    var m = document.createElement('span'); m.className = 'az-msg';
    m.textContent = 'Key is not detected automatically yet — please set it by ear.';
    a.appendChild(go); a.appendChild(m);
    [r1, r3, a].forEach(function (x) { box.appendChild(x); });
  }
  var busy = false;
  function analyze(file) {
    if (busy || !file || !/audio|mpeg|mp3|wav|aiff|flac|ogg/i.test(file.type + ' ' + file.name)) return;
    busy = true; box.style.display = '';
    box.innerHTML = '<div class="az-h"><span class="az-spin"></span> Analyzing ' + file.name.replace(/</g, '') + '… (BPM, length)</div>';
    window.ssAnalyzeAudio(file).then(function (r) { R = r; pick = {}; render(); })
      .catch(function () { box.innerHTML = '<div class="az-h">Could not analyze this file — please fill BPM and Length manually.</div>'; })
      .then(function () { busy = false; });
  }
  window.ssAzAnalyze = analyze;
  function pickAudio(list) { return [].filter.call(list || [], function (f) { return /audio|mpeg|mp3|wav|aiff|flac|ogg/i.test(f.type + ' ' + f.name); })[0]; }
  document.addEventListener('change', function (e) { if (e.target && e.target.type === 'file') analyze(pickAudio(e.target.files)); }, true);
  document.addEventListener('drop', function (e) { if (e.dataTransfer) analyze(pickAudio(e.dataTransfer.files)); }, true);
})();
SSAZ;
		echo "\n</script>\n";
	}, 99 );
}

/* ═══ Solid Sound — 16.10.2026: product grid — click on cover opens the product's YouTube video in a popup (no video → normal link) ═══ */
if ( ! function_exists( 'ss_yt_map' ) ) {
	function ss_yt_map() {
		$m = get_transient( 'ss_yt_map' );
		if ( false !== $m ) return $m;
		$m = array();
		$ids = get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids' ) );
		foreach ( $ids as $id ) {
			$c = get_post_field( 'post_content', $id ) . ' ' . get_post_field( 'post_excerpt', $id );
			if ( preg_match( '~(?:youtube(?:-nocookie)?\.com/(?:embed/|watch\?v=|shorts/)|youtu\.be/)([\w-]{11})~', $c, $mm ) ) $m[ $id ] = $mm[1];
		}
		set_transient( 'ss_yt_map', $m, 6 * HOUR_IN_SECONDS );
		return $m;
	}
	add_action( 'save_post_product', function () { delete_transient( 'ss_yt_map' ); } );
	add_action( 'wp_footer', function () {
		if ( is_admin() ) return;
		$m = ss_yt_map(); if ( ! $m ) return;
		?>
<style id="ss-yt">
.w-grid-item.ss-has-yt .post_image{position:relative}
html body .w-grid.layout_195 .w-grid-item-h{grid-template-columns:172px minmax(0,1fr) auto!important}
html body .w-grid.layout_195 .usg_post_image_1{width:172px!important;min-width:172px!important;max-width:172px!important;height:auto!important;min-height:0!important;max-height:none!important;overflow:visible!important;border-radius:0!important}
html body .w-grid.layout_195 .usg_post_image_1 a{display:block;width:100%}
html body .w-grid.layout_195 .usg_post_image_1 img{width:100%!important;height:auto!important;aspect-ratio:1/1;object-fit:cover;border-radius:8px;max-width:none!important}
@media(max-width:767px){html body .w-grid.layout_195 .w-grid-item-h{grid-template-columns:118px minmax(0,1fr)!important}html body .w-grid.layout_195 .usg_post_image_1{width:118px!important;min-width:118px!important;max-width:118px!important}}
.ss-yt-badge{position:static;margin-top:6px;width:max-content;display:flex;align-items:center;gap:5px;padding:4px 8px 4px 6px;border-radius:6px;background:#1c1c1c;border:1px solid rgba(255,255,255,.12);color:#fff;font:600 11px/1 sans-serif;pointer-events:none;letter-spacing:.02em}
.ss-yt-badge i{display:block;width:16px;height:11px;border-radius:3px;background:#f00;position:relative}
.ss-yt-badge i:after{content:"";position:absolute;left:6px;top:3px;border-left:5px solid #fff;border-top:3px solid transparent;border-bottom:3px solid transparent}
.w-grid-item.ss-has-yt .post_image a{cursor:pointer}
#ss-ytm{position:fixed;inset:0;z-index:2147483000;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,.82);padding:16px}
#ss-ytm.open{display:flex}
#ss-ytm .ss-ytm-box{position:relative;width:min(1100px,100%,calc((100vh - 140px) * 16 / 9));margin-top:40px;aspect-ratio:16/9;background:#000;border-radius:10px;box-shadow:0 20px 60px rgba(0,0,0,.6)}
#ss-ytm iframe{position:absolute;inset:0;width:100%;height:100%;border:0;border-radius:10px}
#ss-ytm .ss-ytm-x{position:absolute;top:-44px;right:0;width:36px;height:36px;border:0;border-radius:50%;background:#fff;color:#111;font:700 20px/36px sans-serif;cursor:pointer}
@media(max-width:600px){#ss-ytm .ss-ytm-x{top:-46px}}
.w-grid-item .post_image .ss-yt-badge{position:absolute!important;left:auto!important;right:5px!important;top:5px!important;bottom:auto!important;margin:0!important;padding:0!important;background:none!important;border:0!important;width:auto!important;z-index:2}
.ss-yt-badge i{width:22px!important;height:16px!important;border-radius:5px!important;box-shadow:0 0 0 2px #1a1a1a,0 1px 4px rgba(0,0,0,.6)}
.ss-yt-badge i:after{left:8px!important;top:4px!important;border-left:7px solid #fff!important;border-top:4px solid transparent!important;border-bottom:4px solid transparent!important}
</style>
<script>
(function(){var M=<?php echo wp_json_encode( $m ); ?>,modal,box,last;window.ssYtOpen=function(i,t){open(i,t)};
function build(){modal=document.createElement('div');modal.id='ss-ytm';modal.setAttribute('role','dialog');modal.setAttribute('aria-modal','true');modal.innerHTML='<div class="ss-ytm-box"><button type="button" class="ss-ytm-x" aria-label="Close">×</button></div>';box=modal.firstChild;document.body.appendChild(modal);
modal.addEventListener('click',function(e){if(e.target===modal||e.target.classList.contains('ss-ytm-x'))close()});document.addEventListener('keydown',function(e){if(e.key==='Escape'&&modal.classList.contains('open'))close()})}
function open(id,title){if(!modal)build();[].forEach.call(document.querySelectorAll('audio,video'),function(a){try{a.pause()}catch(e){}});document.dispatchEvent(new CustomEvent('ss:pause-audio'));
var f=document.createElement('iframe');f.src='https://www.youtube-nocookie.com/embed/'+id+'?autoplay=1&rel=0&modestbranding=1&playsinline=1';f.title=title||'Video';f.allow='autoplay; encrypted-media; picture-in-picture; fullscreen';f.allowFullscreen=true;box.appendChild(f);last=document.activeElement;modal.classList.add('open');document.documentElement.style.overflow='hidden';box.querySelector('.ss-ytm-x').focus()}
function close(){var f=box.querySelector('iframe');if(f)f.remove();modal.classList.remove('open');document.documentElement.style.overflow='';if(last&&last.focus)last.focus()}
function mark(root){[].forEach.call((root||document).querySelectorAll('.w-grid.layout_195 .w-grid-item.type-product:not(.ss-yt-done)'),function(it){it.classList.add('ss-yt-done');var m=it.className.match(/\bpost-(\d+)\b/);if(!m||!M[m[1]])return;var pi=it.querySelector('.post_image');if(!pi)return;it.classList.add('ss-has-yt');var b=document.createElement('span');b.className='ss-yt-badge';b.innerHTML='<i></i>';b.title='Video';pi.appendChild(b);b.style.pointerEvents='auto';b.style.cursor='pointer';b.addEventListener('click',function(e){e.preventDefault();e.stopPropagation();var t=it.querySelector('.post_title');open(M[m[1]],t?t.textContent.trim():'')});var a=pi.querySelector('a');/* 17.10.2026: cover click now opens the product page; video via the YouTube button in the player row */})}
mark();var tq;new MutationObserver(function(ms){if(tq)return;for(var i=0;i<ms.length;i++){if(ms[i].addedNodes.length){tq=setTimeout(function(){tq=0;mark()},250);break}}}).observe(document.body,{childList:true,subtree:true});
})();
</script>
		<?php
	}, 60 );
}

/* ═══ Solid Sound — 16.10.2026: product page layout (tags, big BUY, "What you'll learn" + specs/requirements, video, FAQ). Works for every product automatically. ═══ */
if ( ! function_exists( 'ss_pd_t' ) ) {
	function ss_pd_t( $k ) {
		static $T = null;
		if ( null === $T ) $T = array(
			'learn'    => array( 'en' => 'What you’ll learn', 'ua' => 'Чому ви навчитеся', 'de' => 'Was du lernst', 'fr' => 'Ce que vous apprendrez', 'pt' => 'O que vai aprender' ),
			'get'      => array( 'en' => 'What you get', 'ua' => 'Що ви отримаєте', 'de' => 'Was du bekommst', 'fr' => 'Ce que vous obtenez', 'pt' => 'O que recebe' ),
			'for'      => array( 'en' => 'Perfect for', 'ua' => 'Ідеально для', 'de' => 'Ideal für', 'fr' => 'Idéal pour', 'pt' => 'Ideal para' ),
			'specs'    => array( 'en' => 'Specs & requirements', 'ua' => 'Характеристики та вимоги', 'de' => 'Details & Anforderungen', 'fr' => 'Caractéristiques et prérequis', 'pt' => 'Especificações e requisitos' ),
			'video'    => array( 'en' => 'Video walkthrough', 'ua' => 'Відеоогляд', 'de' => 'Video-Walkthrough', 'fr' => 'Présentation vidéo', 'pt' => 'Apresentação em vídeo' ),
			'faq'      => array( 'en' => 'FAQ', 'ua' => 'Часті запитання', 'de' => 'FAQ', 'fr' => 'FAQ', 'pt' => 'Perguntas frequentes' ),
			'orpay'    => array( 'en' => 'or pay with', 'ua' => 'або оплатіть через', 'de' => 'oder bezahle mit', 'fr' => 'ou payez avec', 'pt' => 'ou pague com' ),
			'format'   => array( 'en' => 'Format', 'ua' => 'Формат', 'de' => 'Format', 'fr' => 'Format', 'pt' => 'Formato' ),
			'daw'      => array( 'en' => 'Requires', 'ua' => 'Потрібно', 'de' => 'Benötigt', 'fr' => 'Nécessite', 'pt' => 'Requer' ),
			'orlater'  => array( 'en' => 'or later', 'ua' => 'або новіша', 'de' => 'oder neuer', 'fr' => 'ou plus récent', 'pt' => 'ou posterior' ),
			'plugins'  => array( 'en' => 'Plugins', 'ua' => 'Плагіни', 'de' => 'Plugins', 'fr' => 'Plugins', 'pt' => 'Plugins' ),
			'native'   => array( 'en' => 'None — Ableton built-in devices only', 'ua' => 'Не потрібні — лише вбудовані пристрої Ableton', 'de' => 'Keine — nur Ableton-eigene Devices', 'fr' => 'Aucun — uniquement les devices intégrés d’Ableton', 'pt' => 'Nenhum — apenas dispositivos nativos do Ableton' ),
			'os'       => array( 'en' => 'Compatible', 'ua' => 'Сумісність', 'de' => 'Kompatibel', 'fr' => 'Compatible', 'pt' => 'Compatível' ),
			'genre'    => array( 'en' => 'Genre', 'ua' => 'Жанр', 'de' => 'Genre', 'fr' => 'Genre', 'pt' => 'Género' ),
			't1' => array( 'en' => 'How the full arrangement is built, from intro to breakdown and drop', 'ua' => 'Як побудована повна аранжировка — від інтро до брейкдауну й дропу', 'de' => 'Wie das komplette Arrangement aufgebaut ist – vom Intro über den Breakdown bis zum Drop', 'fr' => 'Comment l’arrangement complet est construit, de l’intro au break et au drop', 'pt' => 'Como o arranjo completo é construído, da intro ao breakdown e ao drop' ),
			't2' => array( 'en' => 'Drum programming and groove: kick, hats and percussion', 'ua' => 'Програмування ударних і грув: кік, хети, перкусія', 'de' => 'Drum-Programming und Groove: Kick, Hi-Hats und Percussion', 'fr' => 'Programmation de la batterie et groove : kick, charley et percussions', 'pt' => 'Programação de bateria e groove: kick, hi-hats e percussão' ),
			't3n' => array( 'en' => 'Bass and synth sound design with Ableton’s built-in devices', 'ua' => 'Саунд-дизайн басу й синтів на вбудованих пристроях Ableton', 'de' => 'Bass- und Synth-Sounddesign mit Ableton-eigenen Devices', 'fr' => 'Sound design de basse et de synthés avec les devices intégrés d’Ableton', 'pt' => 'Design de som de baixo e sintetizadores com os dispositivos nativos do Ableton' ),
			't3p' => array( 'en' => 'Bass and synth sound design with %s', 'ua' => 'Саунд-дизайн басу й синтів у %s', 'de' => 'Bass- und Synth-Sounddesign mit %s', 'fr' => 'Sound design de basse et de synthés avec %s', 'pt' => 'Design de som de baixo e sintetizadores com %s' ),
			't4' => array( 'en' => 'Effect chains, sends and automation', 'ua' => 'Ланцюги ефектів, посили та автоматизація', 'de' => 'Effektketten, Sends und Automation', 'fr' => 'Chaînes d’effets, envois et automations', 'pt' => 'Cadeias de efeitos, envios e automação' ),
			't5' => array( 'en' => 'Mix processing on channels and groups', 'ua' => 'Обробка зведення на каналах і групах', 'de' => 'Mix-Processing auf Kanälen und Gruppen', 'fr' => 'Traitement du mix sur les pistes et les groupes', 'pt' => 'Processamento de mistura em canais e grupos' ),
			't6' => array( 'en' => 'A ready starting point you can edit, remix and release', 'ua' => 'Готова основа, яку можна змінювати, переробляти й випускати', 'de' => 'Ein fertiger Ausgangspunkt zum Bearbeiten, Remixen und Veröffentlichen', 'fr' => 'Une base prête à modifier, remixer et publier', 'pt' => 'Um ponto de partida pronto para editar, remisturar e lançar' ),
			'p1' => array( 'en' => 'Ready-to-use presets for %s', 'ua' => 'Готові пресети для %s', 'de' => 'Sofort einsetzbare Presets für %s', 'fr' => 'Presets prêts à l’emploi pour %s', 'pt' => 'Presets prontos a usar para %s' ),
			'p2' => array( 'en' => 'Designed for %s', 'ua' => 'Створено для %s', 'de' => 'Gemacht für %s', 'fr' => 'Conçus pour la %s', 'pt' => 'Criados para %s' ),
			'p3' => array( 'en' => 'Fully editable sounds — make them your own', 'ua' => 'Звуки повністю редагуються — зробіть їх своїми', 'de' => 'Komplett editierbare Sounds – mach sie zu deinen eigenen', 'fr' => 'Sons entièrement modifiables — appropriez-vous-les', 'pt' => 'Sons totalmente editáveis — torne-os seus' ),
			'p4' => array( 'en' => 'Royalty-free for your releases', 'ua' => 'Без роялті для ваших релізів', 'de' => 'Lizenzfrei für deine Releases', 'fr' => 'Libres de droits pour vos sorties', 'pt' => 'Livres de royalties para os seus lançamentos' ),
			'p5' => array( 'en' => 'Instant download after payment', 'ua' => 'Миттєве завантаження після оплати', 'de' => 'Sofortiger Download nach der Zahlung', 'fr' => 'Téléchargement immédiat après paiement', 'pt' => 'Download imediato após o pagamento' ),
			'm1' => array( 'en' => 'Videos, documentaries and storytelling', 'ua' => 'Відео, документальні фільми та сторітелінг', 'de' => 'Videos, Dokumentationen und Storytelling', 'fr' => 'Vidéos, documentaires et storytelling', 'pt' => 'Vídeos, documentários e storytelling' ),
			'm2' => array( 'en' => 'Ads, presentations and business projects', 'ua' => 'Реклама, презентації та бізнес-проєкти', 'de' => 'Werbung, Präsentationen und Business-Projekte', 'fr' => 'Publicités, présentations et projets d’entreprise', 'pt' => 'Anúncios, apresentações e projetos empresariais' ),
			'm3' => array( 'en' => 'YouTube, podcasts and social media', 'ua' => 'YouTube, подкасти та соцмережі', 'de' => 'YouTube, Podcasts und Social Media', 'fr' => 'YouTube, podcasts et réseaux sociaux', 'pt' => 'YouTube, podcasts e redes sociais' ),
			's1' => array( 'en' => 'Royalty-free samples ready to drop into any DAW', 'ua' => 'Семпли без роялті, готові до будь-якої DAW', 'de' => 'Lizenzfreie Samples für jede DAW', 'fr' => 'Samples libres de droits pour n’importe quel DAW', 'pt' => 'Samples livres de royalties para qualquer DAW' ),
			'q1' => array( 'en' => 'What can I do with it?', 'ua' => 'Що з цим можна робити?', 'de' => 'Was kann ich damit machen?', 'fr' => 'Que puis-je en faire ?', 'pt' => 'O que posso fazer com isto?' ),
			'a1' => array( 'en' => 'Learn from it by taking the project apart, reuse the samples, presets and effect racks in your own productions, or use it as a ready starting point for a new track.', 'ua' => 'Вчіться, розбираючи проєкт, використовуйте семпли, пресети й ланцюги ефектів у власних треках або беріть його як готову основу для нового треку.', 'de' => 'Lerne, indem du das Projekt zerlegst, nutze Samples, Presets und Effekt-Racks in deinen eigenen Produktionen oder verwende es als fertigen Ausgangspunkt für einen neuen Track.', 'fr' => 'Apprenez en décortiquant le projet, réutilisez les samples, presets et racks d’effets dans vos productions ou servez-vous-en comme base prête pour un nouveau morceau.', 'pt' => 'Aprenda ao desmontar o projeto, reutilize samples, presets e racks de efeitos nas suas produções ou use-o como ponto de partida pronto para uma nova faixa.' ),
			'q6' => array( 'en' => 'Can I release music made with it?', 'ua' => 'Чи можна випускати музику, створену на його основі?', 'de' => 'Darf ich damit erstellte Musik veröffentlichen?', 'fr' => 'Puis-je publier la musique créée avec ?', 'pt' => 'Posso lançar música feita com isto?' ),
			'a6' => array( 'en' => 'Yes. Samples, presets, drum patterns, effects and processing are royalty-free for your own releases. To release a track based on a template, change all the main melodies — then you can put it out on labels, Spotify, YouTube and more. Details are in the license agreement.', 'ua' => 'Так. Семпли, пресети, патерни ударних, ефекти й обробка — без роялті для ваших релізів. Щоб випустити трек на основі шаблону, змініть усі основні мелодії — після цього його можна видавати на лейблах, Spotify, YouTube тощо. Деталі — в ліцензійній угоді.', 'de' => 'Ja. Samples, Presets, Drum-Patterns, Effekte und Processing sind für deine eigenen Releases lizenzfrei. Um einen Track auf Basis eines Templates zu veröffentlichen, ändere alle Hauptmelodien – dann kannst du ihn auf Labels, Spotify, YouTube und mehr veröffentlichen. Details stehen in der Lizenzvereinbarung.', 'fr' => 'Oui. Samples, presets, patterns de batterie, effets et traitements sont libres de droits pour vos sorties. Pour publier un morceau basé sur un template, modifiez toutes les mélodies principales — vous pourrez alors le sortir sur des labels, Spotify, YouTube, etc. Détails dans le contrat de licence.', 'pt' => 'Sim. Samples, presets, padrões de bateria, efeitos e processamento são livres de royalties para os seus lançamentos. Para lançar uma faixa baseada num template, altere todas as melodias principais — depois pode lançá-la em editoras, Spotify, YouTube e mais. Detalhes no contrato de licença.' ),
			'q2' => array( 'en' => 'What exactly do I get?', 'ua' => 'Що саме я отримаю?', 'de' => 'Was genau bekomme ich?', 'fr' => 'Qu’est-ce que je reçois exactement ?', 'pt' => 'O que recebo exatamente?' ),
			'a2' => array( 'en' => 'A ZIP archive with everything listed above — for templates, the Ableton Live project with all the samples it uses. The download link appears right after payment on the order page, comes by email and stays in your account.', 'ua' => 'ZIP-архів з усім, що вказано вище: для шаблонів — проєкт Ableton Live з усіма семплами, які в ньому використані. Посилання на завантаження з’являється одразу після оплати на сторінці замовлення, приходить на пошту й зберігається в акаунті.', 'de' => 'Ein ZIP-Archiv mit allem, was oben aufgeführt ist – bei Templates das Ableton-Live-Projekt mit allen verwendeten Samples. Der Download-Link erscheint direkt nach der Zahlung auf der Bestellseite, kommt per E-Mail und bleibt in deinem Konto.', 'fr' => 'Une archive ZIP avec tout ce qui est indiqué ci-dessus — pour les templates, le projet Ableton Live avec tous les samples utilisés. Le lien de téléchargement apparaît juste après le paiement sur la page de commande, arrive par e-mail et reste dans votre compte.', 'pt' => 'Um arquivo ZIP com tudo o que está indicado acima — nos templates, o projeto Ableton Live com todos os samples usados. O link de download aparece logo após o pagamento na página da encomenda, chega por e-mail e fica na sua conta.' ),
			'q3' => array( 'en' => 'Will it open in my version of Ableton Live?', 'ua' => 'Чи відкриється він у моїй версії Ableton Live?', 'de' => 'Öffnet es sich in meiner Ableton-Live-Version?', 'fr' => 'S’ouvrira-t-il dans ma version d’Ableton Live ?', 'pt' => 'Abre na minha versão do Ableton Live?' ),
			'a3' => array( 'en' => 'Yes, if your version is the one listed in Tech specs or newer — Ableton Live opens projects made in earlier versions without problems. Older versions can’t open projects saved in newer ones.', 'ua' => 'Так, якщо ваша версія така, як у технічних характеристиках, або новіша — Ableton Live без проблем відкриває проєкти зі старіших версій. Старіші версії не відкривають проєкти, збережені в новіших.', 'de' => 'Ja, wenn deine Version der in den technischen Daten angegebenen entspricht oder neuer ist – Ableton Live öffnet Projekte aus älteren Versionen problemlos. Ältere Versionen können Projekte aus neueren nicht öffnen.', 'fr' => 'Oui, si votre version est celle indiquée dans les caractéristiques ou plus récente — Ableton Live ouvre sans problème les projets créés dans des versions antérieures. Les anciennes versions ne peuvent pas ouvrir les projets enregistrés dans des versions plus récentes.', 'pt' => 'Sim, se a sua versão for a indicada nas especificações ou mais recente — o Ableton Live abre sem problemas projetos de versões anteriores. Versões mais antigas não abrem projetos guardados em versões mais recentes.' ),
			'q4' => array( 'en' => 'What if I don’t have the required plugins?', 'ua' => 'Що робити, якщо в мене немає потрібних плагінів?', 'de' => 'Was, wenn ich die benötigten Plugins nicht habe?', 'fr' => 'Et si je n’ai pas les plugins requis ?', 'pt' => 'E se eu não tiver os plugins necessários?' ),
			'a4' => array( 'en' => 'Just write to us after your purchase — we’ll freeze or bounce the synth tracks to audio, so the project sounds exactly like the demo without those plugins. Products marked “No external plugins” work with Ableton’s built-in devices only.', 'ua' => 'Просто напишіть нам після покупки — ми заморозимо або відрендеримо синт-доріжки в аудіо, і проєкт звучатиме точно як демо без цих плагінів. Товари з позначкою «No external plugins» працюють лише на вбудованих пристроях Ableton.', 'de' => 'Schreib uns einfach nach dem Kauf – wir freezen oder bouncen die Synth-Spuren als Audio, damit das Projekt auch ohne diese Plugins genau wie die Demo klingt. Produkte mit „No external plugins“ funktionieren nur mit Ableton-eigenen Devices.', 'fr' => 'Écrivez-nous simplement après l’achat — nous figerons ou exporterons les pistes de synthé en audio, pour que le projet sonne exactement comme la démo sans ces plugins. Les produits « No external plugins » fonctionnent uniquement avec les devices intégrés d’Ableton.', 'pt' => 'Basta escrever-nos após a compra — congelamos ou exportamos as faixas de sintetizador para áudio, para que o projeto soe exatamente como a demo sem esses plugins. Os produtos com «No external plugins» funcionam apenas com dispositivos nativos do Ableton.' ),
			'q5' => array( 'en' => 'Can I get a refund?', 'ua' => 'Чи можна повернути кошти?', 'de' => 'Kann ich eine Rückerstattung bekommen?', 'fr' => 'Puis-je être remboursé ?', 'pt' => 'Posso pedir reembolso?' ),
			'a5' => array( 'en' => 'Digital products can be refunded in the cases described in our refund policy — for example, if a file is damaged and we can’t replace it.', 'ua' => 'Кошти за цифрові товари повертаються у випадках, описаних у політиці повернення — наприклад, якщо файл пошкоджений і ми не можемо його замінити.', 'de' => 'Digitale Produkte werden in den in unserer Rückerstattungsrichtlinie beschriebenen Fällen erstattet – zum Beispiel, wenn eine Datei beschädigt ist und wir sie nicht ersetzen können.', 'fr' => 'Les produits numériques sont remboursables dans les cas décrits dans notre politique de remboursement — par exemple si un fichier est endommagé et que nous ne pouvons pas le remplacer.', 'pt' => 'Os produtos digitais podem ser reembolsados nos casos descritos na nossa política de reembolso — por exemplo, se um ficheiro estiver danificado e não o pudermos substituir.' ),
		);
		$l = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';
		if ( ! $l || empty( $T[ $k ][ $l ] ) ) $l = 'en';
		return isset( $T[ $k ][ $l ] ) ? $T[ $k ][ $l ] : $k;
	}
	function ss_pd_terms( $id, $tax ) { $t = get_the_terms( $id, $tax ); return ( $t && ! is_wp_error( $t ) ) ? $t : array(); }
	function ss_pd_format( $id ) { $f = ss_pd_terms( $id, 'product-format' ); foreach ( $f as $t ) { if ( in_array( $t->slug, array( 'presets', 'music', 'sample-packs' ), true ) ) return $t->slug; } return 'templates'; }
	function ss_pd_native( $id ) { foreach ( ss_pd_terms( $id, 'external-plugin' ) as $t ) if ( false !== strpos( $t->slug, 'no-' ) ) return true; return ! ss_pd_terms( $id, 'external-plugin' ); }
	function ss_pd_plugins( $id ) { return array_values( array_filter( wp_list_pluck( ss_pd_terms( $id, 'external-plugin' ), 'name' ), function ( $n ) { return false === stripos( $n, 'no ' ) && false === stripos( $n, 'no-' ); } ) ); }
	add_action( 'woocommerce_single_product_summary', function () {
		global $product; if ( ! $product ) return; $id = $product->get_id(); $chips = array();
		foreach ( array( 'product-genre', 'product-version' ) as $tx ) foreach ( ss_pd_terms( $id, $tx ) as $t ) $chips[] = array( $t->name, function_exists( 'ss_cat_url' ) ? ss_cat_url( $t ) : '' );
		if ( 'templates' === ss_pd_format( $id ) || 'presets' === ss_pd_format( $id ) ) {
			if ( ss_pd_native( $id ) ) $chips[] = array( 'No external plugins', '' ); else foreach ( ss_pd_plugins( $id ) as $n ) $chips[] = array( $n, '' );
		}
		foreach ( array( 'pa_bpm' => ' BPM', 'pa_root-key' => '' ) as $tx => $suf ) foreach ( ss_pd_terms( $id, $tx ) as $t ) $chips[] = array( $t->name . $suf, '' );
		if ( ! $chips ) return;
		echo '<div class="ss-chips">';
		foreach ( $chips as $c ) echo $c[1] && ! is_wp_error( $c[1] ) ? '<a class="ss-chip" href="' . esc_url( $c[1] ) . '">' . esc_html( $c[0] ) . '</a>' : '<span class="ss-chip">' . esc_html( $c[0] ) . '</span>';
		echo '</div>';
	}, 6 );
	add_action( 'woocommerce_after_single_product_summary', function () {
		global $product; if ( ! $product ) return; $id = $product->get_id(); $fmt = ss_pd_format( $id );
		if ( defined( 'SS_PD3' ) ) return;
		$genre = implode( ', ', wp_list_pluck( ss_pd_terms( $id, 'product-genre' ), 'name' ) );
		$custom = trim( (string) get_post_meta( $id, 'ss_learn', true ) );
		if ( $custom ) { $items = array_filter( array_map( 'trim', preg_split( '/\r?\n/', $custom ) ) ); $title = 'presets' === $fmt || 'sample-packs' === $fmt ? ss_pd_t( 'get' ) : ( 'music' === $fmt ? ss_pd_t( 'for' ) : ss_pd_t( 'learn' ) ); }
		elseif ( 'presets' === $fmt ) { $pl = ss_pd_plugins( $id ); $title = ss_pd_t( 'get' ); $items = array( sprintf( ss_pd_t( 'p1' ), $pl ? implode( ', ', $pl ) : 'your synth' ) ); if ( $genre ) $items[] = sprintf( ss_pd_t( 'p2' ), $genre ); array_push( $items, ss_pd_t( 'p3' ), ss_pd_t( 'p4' ), ss_pd_t( 'p5' ) ); }
		elseif ( 'music' === $fmt ) { $title = ss_pd_t( 'for' ); $items = array( ss_pd_t( 'm1' ), ss_pd_t( 'm2' ), ss_pd_t( 'm3' ), ss_pd_t( 'p4' ), ss_pd_t( 'p5' ) ); }
		elseif ( 'sample-packs' === $fmt ) { $title = ss_pd_t( 'get' ); $items = wp_list_pluck( ss_pd_terms( $id, 'pa_download-include' ), 'name' ); array_push( $items, ss_pd_t( 's1' ), ss_pd_t( 'p5' ) ); }
		else { $pl = ss_pd_plugins( $id ); $title = ss_pd_t( 'learn' ); $items = array( ss_pd_t( 't1' ), ss_pd_t( 't2' ), ss_pd_native( $id ) || ! $pl ? ss_pd_t( 't3n' ) : sprintf( ss_pd_t( 't3p' ), implode( ', ', $pl ) ), ss_pd_t( 't4' ), ss_pd_t( 't5' ), ss_pd_t( 't6' ) ); }
		$rows = array();
		$f = ss_pd_terms( $id, 'product-format' ); if ( $f ) $rows[] = array( ss_pd_t( 'format' ), $f[0]->name );
		if ( $genre ) $rows[] = array( ss_pd_t( 'genre' ), $genre );
		if ( in_array( $fmt, array( 'templates', 'presets' ), true ) ) {
			$v = wp_list_pluck( ss_pd_terms( $id, 'product-version' ), 'name' );
			if ( $v ) { natsort( $v ); $rows[] = array( ss_pd_t( 'daw' ), reset( $v ) . ' ' . ss_pd_t( 'orlater' ) ); }
			$pl = ss_pd_plugins( $id ); $rows[] = array( ss_pd_t( 'plugins' ), ( ss_pd_native( $id ) || ! $pl ) ? ( 'presets' === $fmt ? '—' : ss_pd_t( 'native' ) ) : implode( ', ', $pl ) );
			$rows[] = array( ss_pd_t( 'os' ), 'Windows & macOS' );
		}
		foreach ( $product->get_attributes() as $a ) {
			if ( ! $a->get_visible() ) continue;
			$val = $a->is_taxonomy() ? implode( ', ', wc_get_product_terms( $id, $a->get_name(), array( 'fields' => 'names' ) ) ) : implode( ', ', $a->get_options() );
			if ( '' === trim( $val ) ) continue;
			$rows[] = array( wc_attribute_label( $a->get_name() ), $val );
		}
		echo '<section class="ss-pd"><div class="ss-pd-col"><h2>' . esc_html( $title ) . '</h2><ul class="ss-learn">';
		foreach ( $items as $it ) echo '<li>' . esc_html( $it ) . '</li>';
		echo '</ul></div><div class="ss-pd-col"><h2>' . esc_html( ss_pd_t( 'specs' ) ) . '</h2><table class="ss-specs">';
		foreach ( $rows as $r ) echo '<tr><th>' . esc_html( $r[0] ) . '</th><td>' . esc_html( $r[1] ) . '</td></tr>';
		echo '</table></div></section>';
		do_action( 'ss_pd_after_specs', $id, $fmt );
		$content = get_post_field( 'post_content', $id );
		if ( preg_match( '~(?:youtube(?:-nocookie)?\.com/(?:embed/|watch\?v=|shorts/)|youtu\.be/)([\w-]{11})~', $content, $m ) ) {
			if ( false ) echo '<section class="ss-pd-video"><h2>' . esc_html( ss_pd_t( 'video' ) ) . '</h2><div class="ss-pd-vbox" data-yt="' . esc_attr( $m[1] ) . '" role="button" tabindex="0" aria-label="Play video"><img loading="lazy" src="https://i.ytimg.com/vi/' . esc_attr( $m[1] ) . '/hqdefault.jpg" alt=""><span class="ss-pd-play"></span></div></section>';
		}
		$qs = 'music' === $fmt ? array( 1, 2, 5 ) : array( 1, 2, 3, 4, 5 );
		if ( 'presets' === $fmt || 'sample-packs' === $fmt ) $qs = array( 1, 2, 5 );
		echo '<section class="ss-pd-faq"><h2>' . esc_html( ss_pd_t( 'faq' ) ) . '</h2>';
		foreach ( $qs as $n ) {
			$a = ss_pd_t( 'a' . $n );
			if ( 1 === $n ) $a .= ' <a href="' . esc_url( home_url( '/license/' ) ) . '">→</a>';
			if ( 5 === $n ) $a .= ' <a href="' . esc_url( home_url( '/refund_returns/' ) ) . '" aria-label="Refund policy">→</a>';
			echo '<details><summary>' . esc_html( ss_pd_t( 'q' . $n ) ) . '</summary><p>' . wp_kses_post( $a ) . '</p></details>';
		}
		echo '</section>';
	}, 5 );
	add_filter( 'woocommerce_product_tabs', function ( $tabs ) { unset( $tabs['description'], $tabs['additional_information'] ); return $tabs; }, 98 );
	add_action( 'wp_head', function () {
		if ( ! function_exists( 'is_product' ) || ! is_product() ) return;
		/* (17.11 eyes check) the label only when the express buttons really rendered (Stripe draws them in an iframe) — no lonely "or pay with" */
		echo '<style>.single-product .wcpay-express-checkout-wrapper:before{content:"' . esc_js( ss_pd_t( 'orpay' ) ) . '"}.single-product .wcpay-express-checkout-wrapper:not(:has(iframe)):before{content:none;display:none}</style>'; ?>
<style id="ss-pd-css">
.single-product .ss-chips{display:flex;flex-wrap:wrap;gap:6px;margin:4px 0 14px}
.single-product .ss-chip{display:inline-block;padding:4px 10px;border:1px solid #423c36;border-radius:999px;font-size:13px;line-height:1.3;color:#ccc4bc;text-decoration:none}
.single-product a.ss-chip:hover{border-color:#e09900;color:#e09900}
.single-product form.cart{display:block!important;margin:14px 0 10px!important}
.single-product form.cart .single_add_to_cart_button{width:100%!important;height:52px!important;font-size:17px!important;letter-spacing:.06em;border-radius:6px!important;float:none!important;margin:0!important}
.single-product .wcpay-express-checkout-wrapper{margin:6px 0 4px!important}
.single-product .wcpay-express-checkout-wrapper:before{display:block;text-align:center;font-size:12px;color:#8a8079;margin:0 0 6px}
.ss-pd{display:grid;grid-template-columns:1fr 1fr;gap:28px;margin:34px 0 10px;clear:both}
.ss-pd h2,.ss-pd-video h2,.ss-pd-faq h2{font-size:20px;margin:0 0 14px}
.ss-learn{list-style:none;margin:0;padding:0}
.ss-learn li{position:relative;padding:9px 0 9px 28px;border-bottom:1px solid #2c2722;line-height:1.45}
.ss-learn li:before{content:"";position:absolute;left:4px;top:15px;width:10px;height:6px;border-left:2px solid #e09900;border-bottom:2px solid #e09900;transform:rotate(-45deg)}
.ss-specs{width:100%;border-collapse:collapse;margin:0}
.ss-specs th,.ss-specs td{padding:9px 0;border-bottom:1px solid #2c2722;text-align:left;font-weight:400;vertical-align:top}
.ss-specs th{color:#a39a92;width:42%;padding-right:12px}
.ss-pd-video{margin:34px 0 10px;clear:both}
.ss-pd-vbox{position:relative;max-width:820px;aspect-ratio:16/9;border-radius:10px;overflow:hidden;cursor:pointer;background:#000}
.ss-pd-vbox img{width:100%;height:100%;object-fit:cover;display:block;opacity:.85}
.ss-pd-vbox iframe{position:absolute;inset:0;width:100%;height:100%;border:0}
.ss-pd-play{position:absolute;left:50%;top:50%;width:68px;height:48px;margin:-24px 0 0 -34px;background:#f00;border-radius:12px}
.ss-pd-play:after{content:"";position:absolute;left:27px;top:14px;border-left:18px solid #fff;border-top:10px solid transparent;border-bottom:10px solid transparent}
.ss-pd-faq{margin:34px 0 24px;max-width:820px;clear:both}
.ss-pd-faq details{border-bottom:1px solid #2c2722}
.ss-pd-faq summary{cursor:pointer;padding:12px 28px 12px 0;list-style:none;position:relative;font-weight:600}
.ss-pd-faq summary::-webkit-details-marker{display:none}
.ss-pd-faq summary:after{content:"+";position:absolute;right:4px;top:9px;font-size:20px;color:#e09900}
.ss-pd-faq details[open] summary:after{content:"–"}
.ss-pd-faq p{margin:0 0 14px;color:#ccc4bc;line-height:1.55}
@media(max-width:767px){.ss-pd{grid-template-columns:1fr;gap:22px;margin-top:24px}.single-product form.cart .single_add_to_cart_button{height:50px!important}}
</style>
<script>document.addEventListener('click',function(e){var b=e.target.closest&&e.target.closest('.ss-pd-vbox');if(!b||b.querySelector('iframe'))return;[].forEach.call(document.querySelectorAll('audio'),function(a){try{a.pause()}catch(x){}});var f=document.createElement('iframe');f.src='https://www.youtube-nocookie.com/embed/'+b.getAttribute('data-yt')+'?autoplay=1&rel=0&playsinline=1';f.allow='autoplay; encrypted-media; picture-in-picture; fullscreen';f.allowFullscreen=true;b.appendChild(f)});</script>
		<?php
	}, 50 );
}

/* ═══ Solid Sound — 16.10.2026: no bare WooCommerce archives — category/format/genre/BPM… archives go to the designed catalog with the same filter applied ═══ */
if ( ! function_exists( 'ss_cat_url' ) ) {
	function ss_cat_url( $t ) {
		$home = function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
		$pages = array( 'templates' => 1115, 'reconstructed-templates' => 1115, 'presets' => 1121, 'music' => 1547 );
		/* (13.11.2026, QA bot: genre/plugin links landed on the home page) categories → All products, other filters → search results for that word */
		if ( 'product_cat' === $t->taxonomy ) return home_url( '/en/all-products/' );
		if ( 'product-format' === $t->taxonomy && isset( $pages[ $t->slug ] ) ) {
			$pid = $pages[ $t->slug ];
			if ( function_exists( 'pll_get_post' ) && function_exists( 'pll_current_language' ) ) { $tr = pll_get_post( $pid, pll_current_language() ); if ( $tr ) $pid = $tr; }
			$u = get_permalink( $pid );
			return 'reconstructed-templates' === $t->slug ? add_query_arg( 'filter_product-format', $t->slug, $u ) : $u;
		}
		return add_query_arg( 's', rawurlencode( $t->name ), home_url( '/' ) );
	}
	add_action( 'template_redirect', function () {
		if ( is_admin() || wp_doing_ajax() ) return;
		if ( function_exists( 'is_shop' ) && is_shop() ) { wp_safe_redirect( home_url( '/en/all-products/' ), 301 ); exit; } /* shop = the catalog page (13.11.2026) */
		if ( ! is_tax() && ! ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) ) return;
		$t = get_queried_object();
		if ( ! $t || empty( $t->taxonomy ) || ! is_object_in_taxonomy( 'product', $t->taxonomy ) ) return;
		wp_safe_redirect( ss_cat_url( $t ), 301 ); exit;
	}, 2 );
	/* breadcrumbs on product pages: Home / Templates / Pitch instead of the bare category */
	add_filter( 'woocommerce_get_breadcrumb', function ( $crumbs ) {
		if ( ! function_exists( 'is_product' ) || ! is_product() || count( $crumbs ) < 3 ) return $crumbs;
		$f = get_the_terms( get_queried_object_id(), 'product-format' );
		if ( $f && ! is_wp_error( $f ) ) { $crumbs[1] = array( $f[0]->name, ss_cat_url( $f[0] ) ); $crumbs = array( $crumbs[0], $crumbs[1], end( $crumbs ) ); }
		if ( function_exists( 'pll_home_url' ) ) $crumbs[0][1] = pll_home_url();
		return $crumbs;
	}, 20 );
	/* archives are redirects now — keep them out of the sitemap */
	add_filter( 'wp_sitemaps_taxonomies', function ( $t ) { foreach ( array_keys( $t ) as $k ) if ( is_object_in_taxonomy( 'product', $k ) ) unset( $t[ $k ] ); return $t; }, 20 );
}

/* ═══ Solid Sound — 16.10.2026: product page v2 — "How you can use it", "What's inside", "How it works", sticky buy bar, readable text, video from the cover ═══ */
if ( ! function_exists( 'ss_pd2_t' ) ) {
	function ss_pd2_t( $k ) {
		static $T = null;
		if ( null === $T ) $T = array(
			'use'   => array( 'en' => 'How you can use it', 'ua' => 'Як це можна використати', 'de' => 'Wofür du es nutzen kannst', 'fr' => 'Comment l’utiliser', 'pt' => 'Como pode usar' ),
			'inside'=> array( 'en' => 'What’s inside', 'ua' => 'Що всередині', 'de' => 'Was enthalten ist', 'fr' => 'Contenu', 'pt' => 'O que está incluído' ),
			'how'   => array( 'en' => 'How it works', 'ua' => 'Як це працює', 'de' => 'So funktioniert’s', 'fr' => 'Comment ça marche', 'pt' => 'Como funciona' ),
			'buy'   => array( 'en' => 'Buy', 'ua' => 'Купити', 'de' => 'Kaufen', 'fr' => 'Acheter', 'pt' => 'Comprar' ),
			'u1t' => array( 'en' => 'Learn music production on a real project', 'ua' => 'Вчіться продюсуванню на реальному проєкті', 'de' => 'Musikproduktion an einem echten Projekt lernen', 'fr' => 'Apprenez la production musicale sur un vrai projet', 'pt' => 'Aprenda produção musical num projeto real' ),
			'u1d' => array( 'en' => 'Open the full Ableton Live project and see how a release-ready track is built: drum programming, bass and synth sound design, effect chains, automation and the mix. The fastest way to learn how pro tracks are made.', 'ua' => 'Відкрийте повний проєкт Ableton Live і подивіться, як зроблений готовий до релізу трек: програмування ударних, саунд-дизайн басу й синтів, ланцюги ефектів, автоматизація та зведення. Найшвидший спосіб зрозуміти, як створюються професійні треки.', 'de' => 'Öffne das komplette Ableton-Live-Projekt und sieh, wie ein release-fertiger Track entsteht: Drum-Programming, Bass- und Synth-Sounddesign, Effektketten, Automation und Mix. Der schnellste Weg zu verstehen, wie Profi-Tracks gemacht werden.', 'fr' => 'Ouvrez le projet Ableton Live complet et découvrez comment un morceau prêt à sortir est construit : programmation de la batterie, sound design des basses et synthés, chaînes d’effets, automations et mix. Le moyen le plus rapide de comprendre comment sont faits les morceaux pros.', 'pt' => 'Abra o projeto Ableton Live completo e veja como é construída uma faixa pronta a lançar: programação de bateria, design de som de baixo e sintetizadores, cadeias de efeitos, automação e mistura. A forma mais rápida de perceber como se fazem faixas profissionais.' ),
			'u2t' => array( 'en' => 'Turn it into your own track', 'ua' => 'Зробіть на його основі власний трек', 'de' => 'Mach daraus deinen eigenen Track', 'fr' => 'Faites-en votre propre morceau', 'pt' => 'Transforme-o na sua própria faixa' ),
			'u2d' => array( 'en' => 'All parts are in MIDI, so it’s easy to rearrange the structure, swap sounds, write new melodies and build your own music on top of a finished, mixed project — then release it.', 'ua' => 'Усі партії записані в MIDI, тож легко змінити структуру, замінити звуки, написати нові мелодії й створити власну музику на основі готового зведеного проєкту — а потім випустити її.', 'de' => 'Alle Spuren liegen als MIDI vor: Struktur umbauen, Sounds tauschen, neue Melodien schreiben und auf einem fertig gemischten Projekt deine eigene Musik erschaffen – und veröffentlichen.', 'fr' => 'Toutes les parties sont en MIDI : changez facilement la structure, remplacez les sons, écrivez de nouvelles mélodies et créez votre propre musique à partir d’un projet déjà mixé — puis publiez-la.', 'pt' => 'Todas as partes estão em MIDI, por isso é fácil mudar a estrutura, trocar sons, escrever novas melodias e criar a sua própria música a partir de um projeto já misturado — e depois lançá-la.' ),
			'u3t' => array( 'en' => 'Remix and rearrange', 'ua' => 'Переробляйте й переаранжовуйте', 'de' => 'Remixen und neu arrangieren', 'fr' => 'Remixez et réarrangez', 'pt' => 'Remisture e reorganize' ),
			'u3d' => array( 'en' => 'Swap sounds, change the structure and try new ideas over a finished arrangement.', 'ua' => 'Міняйте звуки, структуру й пробуйте нові ідеї на готовій аранжировці.', 'de' => 'Tausche Sounds, ändere die Struktur und teste neue Ideen auf einem fertigen Arrangement.', 'fr' => 'Changez les sons, la structure et testez de nouvelles idées sur un arrangement fini.', 'pt' => 'Troque sons, mude a estrutura e experimente novas ideias sobre um arranjo pronto.' ),
			'u4t' => array( 'en' => 'Practice mixing', 'ua' => 'Тренуйте зведення', 'de' => 'Mixing üben', 'fr' => 'Entraînez-vous au mixage', 'pt' => 'Pratique mistura' ),
			'u4d' => array( 'en' => 'Train your ears on a full multitrack project before you mix your own music.', 'ua' => 'Тренуйте слух на повному багатодоріжковому проєкті, перш ніж зводити власну музику.', 'de' => 'Trainiere deine Ohren an einem kompletten Multitrack-Projekt, bevor du eigene Musik mischst.', 'fr' => 'Entraînez votre oreille sur un projet multipiste complet avant de mixer votre musique.', 'pt' => 'Treine o ouvido num projeto multipista completo antes de misturar a sua música.' ),
			'u5t' => array( 'en' => 'Grow your sound library', 'ua' => 'Поповніть бібліотеку звуків', 'de' => 'Erweitere deine Sound-Library', 'fr' => 'Enrichissez votre banque de sons', 'pt' => 'Aumente a sua biblioteca de sons' ),
			'u5d' => array( 'en' => 'Every sample, preset and effect rack is royalty-free. Save them to your Ableton library and use them in your own productions for years to come.', 'ua' => 'Усі семпли, пресети й ланцюги ефектів — без роялті. Збережіть їх у бібліотеку Ableton і використовуйте у власних треках роками.', 'de' => 'Alle Samples, Presets und Effekt-Racks sind lizenzfrei. Speichere sie in deiner Ableton-Library und nutze sie über Jahre in deinen eigenen Produktionen.', 'fr' => 'Tous les samples, presets et racks d’effets sont libres de droits. Ajoutez-les à votre bibliothèque Ableton et utilisez-les dans vos productions pendant des années.', 'pt' => 'Todos os samples, presets e racks de efeitos são livres de royalties. Guarde-os na sua biblioteca do Ableton e use-os nas suas produções durante anos.' ),
			'u6t' => array( 'en' => 'Play it out', 'ua' => 'Грайте наживо', 'de' => 'Spiel es live', 'fr' => 'Jouez-le en live', 'pt' => 'Toque ao vivo' ),
			'u6d' => array( 'en' => 'Play it at home, in clubs, at festivals or in live performances.', 'ua' => 'Грайте вдома, у клубах, на фестивалях чи в живих виступах.', 'de' => 'Spiel es zu Hause, im Club, auf Festivals oder bei Live-Auftritten.', 'fr' => 'Jouez-le chez vous, en club, en festival ou en live.', 'pt' => 'Toque em casa, em clubes, festivais ou atuações ao vivo.' ),
			'v1t' => array( 'en' => 'Drop into your tracks', 'ua' => 'Додайте у свої треки', 'de' => 'Direkt in deine Tracks', 'fr' => 'Directement dans vos morceaux', 'pt' => 'Direto nas suas faixas' ),
			'v1d' => array( 'en' => 'Load a preset and get a finished, mix-ready sound in seconds.', 'ua' => 'Завантажте пресет і за секунди отримайте готовий звук.', 'de' => 'Preset laden und in Sekunden einen fertigen Sound haben.', 'fr' => 'Chargez un preset et obtenez un son fini en quelques secondes.', 'pt' => 'Carregue um preset e tenha um som pronto em segundos.' ),
			'v2t' => array( 'en' => 'Learn sound design', 'ua' => 'Вивчайте саунд-дизайн', 'de' => 'Sounddesign lernen', 'fr' => 'Apprenez le sound design', 'pt' => 'Aprenda design de som' ),
			'v2d' => array( 'en' => 'See how each sound is built: oscillators, filters, modulation and effects.', 'ua' => 'Подивіться, як побудований кожен звук: осцилятори, фільтри, модуляція, ефекти.', 'de' => 'Sieh, wie jeder Sound aufgebaut ist: Oszillatoren, Filter, Modulation und Effekte.', 'fr' => 'Découvrez comment chaque son est construit : oscillateurs, filtres, modulation et effets.', 'pt' => 'Veja como cada som é construído: osciladores, filtros, modulação e efeitos.' ),
			'v3t' => array( 'en' => 'Make it your own', 'ua' => 'Зробіть своїм', 'de' => 'Mach es zu deinem Sound', 'fr' => 'Personnalisez-le', 'pt' => 'Torne-o seu' ),
			'v3d' => array( 'en' => 'Tweak the presets and save them as your signature sounds.', 'ua' => 'Змінюйте пресети й зберігайте як власні фірмові звуки.', 'de' => 'Passe die Presets an und speichere sie als deine Signature-Sounds.', 'fr' => 'Modifiez les presets et enregistrez-les comme vos sons signature.', 'pt' => 'Ajuste os presets e guarde-os como os seus sons de assinatura.' ),
			'w1t' => array( 'en' => 'Make beats faster', 'ua' => 'Робіть біти швидше', 'de' => 'Schneller Beats bauen', 'fr' => 'Faites vos beats plus vite', 'pt' => 'Faça beats mais rápido' ),
			'w1d' => array( 'en' => 'Drag ready sounds into any DAW and build ideas in minutes.', 'ua' => 'Перетягуйте готові звуки в будь-яку DAW і збирайте ідеї за хвилини.', 'de' => 'Zieh fertige Sounds in jede DAW und bau Ideen in Minuten.', 'fr' => 'Glissez des sons prêts dans n’importe quel DAW et montez vos idées en quelques minutes.', 'pt' => 'Arraste sons prontos para qualquer DAW e crie ideias em minutos.' ),
			'w2t' => array( 'en' => 'Layer and resample', 'ua' => 'Нашаровуйте й ресемплюйте', 'de' => 'Layern und resamplen', 'fr' => 'Superposez et resamplez', 'pt' => 'Sobreponha e reamostre' ),
			'w2d' => array( 'en' => 'Combine, chop and process the samples into new sounds.', 'ua' => 'Поєднуйте, нарізайте й обробляйте семпли в нові звуки.', 'de' => 'Kombiniere, schneide und bearbeite Samples zu neuen Sounds.', 'fr' => 'Combinez, découpez et traitez les samples pour créer de nouveaux sons.', 'pt' => 'Combine, corte e processe os samples em novos sons.' ),
			'i_tpl' => array( 'en' => 'Ableton Live project (Live Set), ready to open', 'ua' => 'Проєкт Ableton Live (Live Set), готовий до відкриття', 'de' => 'Ableton-Live-Projekt (Live Set), sofort startklar', 'fr' => 'Projet Ableton Live (Live Set) prêt à ouvrir', 'pt' => 'Projeto Ableton Live (Live Set) pronto a abrir' ),
			'i_smp' => array( 'en' => 'Samples used in the project', 'ua' => 'Семпли, використані в проєкті', 'de' => 'Im Projekt verwendete Samples', 'fr' => 'Samples utilisés dans le projet', 'pt' => 'Samples usados no projeto' ),
			'i_pre' => array( 'en' => 'Preset files for %s', 'ua' => 'Файли пресетів для %s', 'de' => 'Preset-Dateien für %s', 'fr' => 'Fichiers de presets pour %s', 'pt' => 'Ficheiros de presets para %s' ),
			'i_mus' => array( 'en' => 'Full-length track', 'ua' => 'Повна версія треку', 'de' => 'Track in voller Länge', 'fr' => 'Morceau en version complète', 'pt' => 'Faixa completa' ),
			'i_lic' => array( 'en' => 'Royalty-free license', 'ua' => 'Ліцензія без роялті', 'de' => 'Lizenzfreie Nutzung', 'fr' => 'Licence libre de droits', 'pt' => 'Licença livre de royalties' ),
			's1' => array( 'en' => 'Listen to the demo and buy', 'ua' => 'Послухайте демо й купіть', 'de' => 'Demo anhören und kaufen', 'fr' => 'Écoutez la démo et achetez', 'pt' => 'Ouça a demo e compre' ),
			's2' => array( 'en' => 'Download instantly — on the order page, by email and in your account', 'ua' => 'Завантажте одразу — на сторінці замовлення, з листа або в акаунті', 'de' => 'Sofort herunterladen – auf der Bestellseite, per E-Mail und im Konto', 'fr' => 'Téléchargez immédiatement — page de commande, e-mail et compte', 'pt' => 'Faça o download de imediato — na página da encomenda, por e-mail e na conta' ),
			's3t' => array( 'en' => 'Open it in Ableton Live and start creating', 'ua' => 'Відкрийте в Ableton Live і творіть', 'de' => 'In Ableton Live öffnen und loslegen', 'fr' => 'Ouvrez-le dans Ableton Live et créez', 'pt' => 'Abra no Ableton Live e comece a criar' ),
			's3p' => array( 'en' => 'Load the presets and start creating', 'ua' => 'Завантажте пресети й творіть', 'de' => 'Presets laden und loslegen', 'fr' => 'Chargez les presets et créez', 'pt' => 'Carregue os presets e comece a criar' ),
			's3m' => array( 'en' => 'Add it to your project', 'ua' => 'Додайте у свій проєкт', 'de' => 'In dein Projekt einfügen', 'fr' => 'Ajoutez-le à votre projet', 'pt' => 'Adicione ao seu projeto' ),
		);
		$l = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';
		if ( ! $l || empty( $T[ $k ][ $l ] ) ) $l = 'en';
		return isset( $T[ $k ][ $l ] ) ? $T[ $k ][ $l ] : $k;
	}
	function ss_pd2_icon( $n ) {
		$p = array(
			'learn' => '<path d="M3 7l9-4 9 4-9 4-9-4z"/><path d="M7 9v5c0 1.7 2.2 3 5 3s5-1.3 5-3V9"/>',
			'start' => '<path d="M5 3v18l15-9z"/>',
			'remix' => '<path d="M4 7h11l-3-3M20 17H9l3 3"/>',
			'mix'   => '<path d="M6 4v16M12 4v16M18 4v16"/><circle cx="6" cy="9" r="2"/><circle cx="12" cy="15" r="2"/><circle cx="18" cy="7" r="2"/>',
			'lib'   => '<path d="M4 5h4v14H4zM10 5h4v14h-4z"/><path d="M16 6l3.5-1 3 13-3.5 1z"/>',
			'live'  => '<circle cx="12" cy="12" r="3"/><path d="M6.3 6.3a8 8 0 000 11.4M17.7 6.3a8 8 0 010 11.4"/>',
			'drop'  => '<path d="M12 3v12M7 10l5 5 5-5M5 21h14"/>',
			'wave'  => '<path d="M2 12h3l2-6 3 12 3-9 2 6 2-3h5"/>',
			'tune'  => '<path d="M4 6h10M18 6h2M4 12h4M12 12h8M4 18h12"/><circle cx="16" cy="6" r="2"/><circle cx="10" cy="12" r="2"/><circle cx="18" cy="18" r="2"/>',
		);
		return '<svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ( isset( $p[ $n ] ) ? $p[ $n ] : '' ) . '</svg>';
	}
	add_action( 'woocommerce_after_single_product_summary', function () {
		global $product; if ( ! $product || ! function_exists( 'ss_pd_format' ) ) return; $fmt = ss_pd_format( $product->get_id() );
		if ( 'music' === $fmt ) return;
		$map = array(
			'templates'    => array( array( 'learn', 'u1' ), array( 'lib', 'u5' ), array( 'start', 'u2' ) ),
			'presets'      => array( array( 'drop', 'v1' ), array( 'wave', 'v2' ), array( 'tune', 'v3' ) ),
			'sample-packs' => array( array( 'drop', 'w1' ), array( 'remix', 'w2' ), array( 'live', 'u6' ) ),
		);
		$list = isset( $map[ $fmt ] ) ? $map[ $fmt ] : $map['templates'];
		echo '<section class="ss-use"><h2>' . esc_html( ss_pd2_t( 'use' ) ) . '</h2><div class="ss-use-grid">';
		foreach ( $list as $u ) echo '<div class="ss-use-i"><span class="ss-use-ic">' . ss_pd2_icon( $u[0] ) . '</span><h3>' . esc_html( ss_pd2_t( $u[1] . 't' ) ) . '</h3><p>' . esc_html( ss_pd2_t( $u[1] . 'd' ) ) . '</p></div>';
		echo '</div></section>';
	}, 4 );
	add_action( 'ss_pd_after_specs', function ( $id, $fmt ) {
		$inside = function_exists( 'ss_pd_terms' ) ? wp_list_pluck( ss_pd_terms( $id, 'pa_download-include' ), 'name' ) : array();
		if ( ! $inside ) {
			if ( 'presets' === $fmt ) { $pl = function_exists( 'ss_pd_plugins' ) ? ss_pd_plugins( $id ) : array(); $inside = array( sprintf( ss_pd2_t( 'i_pre' ), $pl ? implode( ', ', $pl ) : 'your synth' ) ); }
			elseif ( 'music' === $fmt ) $inside = array( ss_pd2_t( 'i_mus' ) );
			elseif ( 'sample-packs' === $fmt ) $inside = array();
			else $inside = array( ss_pd2_t( 'i_tpl' ), ss_pd2_t( 'i_smp' ) );
		}
		$inside[] = ss_pd2_t( 'i_lic' );
		$s3 = 'presets' === $fmt ? 's3p' : ( 'music' === $fmt || 'sample-packs' === $fmt ? 's3m' : 's3t' );
		echo '<section class="ss-pd ss-pd2"><div class="ss-pd-col"><h2>' . esc_html( ss_pd2_t( 'inside' ) ) . '</h2><ul class="ss-inside">';
		foreach ( $inside as $it ) echo '<li>' . esc_html( $it ) . '</li>';
		echo '</ul></div><div class="ss-pd-col"><h2>' . esc_html( ss_pd2_t( 'how' ) ) . '</h2><ol class="ss-steps">';
		foreach ( array( ss_pd2_t( 's1' ), ss_pd2_t( 's2' ), ss_pd2_t( $s3 ) ) as $s ) echo '<li>' . esc_html( $s ) . '</li>';
		echo '</ol></div></section>';
	}, 10, 2 );
	add_action( 'woocommerce_after_single_product', function () {
		if ( ! function_exists( 'is_product' ) || ! is_product() ) return;
		global $product; if ( ! $product ) $product = wc_get_product( get_queried_object_id() ); if ( ! $product ) return;
		$img = wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' );
		$ytm = function_exists( 'ss_yt_map' ) ? ss_yt_map() : array(); $yt = isset( $ytm[ $product->get_id() ] ) ? $ytm[ $product->get_id() ] : '';
		?>
<div id="ss-sbar" aria-hidden="true"><div class="ss-sbar-in"><?php if ( $img ) echo '<img src="' . esc_url( $img ) . '" alt="">'; ?><span class="ss-sbar-t"><?php echo esc_html( $product->get_name() ); ?></span><span class="ss-sbar-p"><?php echo wp_kses_post( $product->get_price_html() ); ?></span><button type="button" class="ss-sbar-b"><?php echo esc_html( ss_pd2_t( 'buy' ) ); ?></button></div></div>
<style id="ss-pd2-css">
.single-product .ss-use,.single-product .ss-pd,.single-product .ss-pd-video,.single-product .ss-pd-faq{font-family:system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif}
.single-product .woocommerce-product-details__short-description p{font-family:system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif;font-size:16px;line-height:1.65;color:#ddd5cd;max-width:62ch}
.single-product .ss-use h2,.single-product .ss-pd h2,.single-product .ss-pd-video h2,.single-product .ss-pd-faq h2{font-family:inherit;font-weight:700;letter-spacing:-.01em}
.ss-use{margin:40px 0 8px;clear:both}
.ss-use h2{font-size:22px;margin:0 0 16px}
.ss-use-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.ss-use-i{background:#1d1916;border:1px solid #2c2722;border-radius:10px;padding:16px 16px 14px}
.ss-use-ic{display:inline-flex;width:42px;height:42px;align-items:center;justify-content:center;border-radius:10px;background:rgba(224,153,0,.12);color:#e09900;margin-bottom:10px}
.ss-use-i h3{font-size:16px;margin:0 0 6px;font-weight:600;color:#f2ece6;font-family:inherit}
.ss-use-i p{margin:0;font-size:14.5px;line-height:1.55;color:#bdb4ab}
.ss-learn li,.ss-specs th,.ss-specs td,.ss-inside li,.ss-steps li{font-size:15.5px}
.ss-learn li{color:#e6dfd8}
.ss-inside{list-style:none;margin:0;padding:0}
.ss-inside li{position:relative;padding:9px 0 9px 28px;border-bottom:1px solid #2c2722;color:#e6dfd8}
.ss-inside li:before{content:"";position:absolute;left:5px;top:17px;width:9px;height:9px;background:#e09900;border-radius:2px;transform:rotate(45deg)}
.ss-steps{list-style:none;margin:0;padding:0;counter-reset:s}
.ss-steps li{position:relative;padding:10px 0 10px 44px;border-bottom:1px solid #2c2722;color:#e6dfd8;counter-increment:s;min-height:28px}
.ss-steps li:before{content:counter(s);position:absolute;left:0;top:8px;width:28px;height:28px;border-radius:50%;background:#e09900;color:#1a1612;font-weight:700;display:flex;align-items:center;justify-content:center;font-size:14px}
.ss-pd2{margin-top:30px}
.ss-pd-faq summary{font-size:16px}
.ss-pd-faq p{font-size:15.5px}
.woocommerce-product-gallery .ss-yt-pd{position:absolute;top:12px;right:12px;z-index:6;cursor:pointer;display:block;line-height:0}
.woocommerce-product-gallery .ss-yt-pd i{display:block;width:46px;height:32px;border-radius:9px;background:#f00;position:relative;box-shadow:0 2px 10px rgba(0,0,0,.5)}
.woocommerce-product-gallery .ss-yt-pd i:after{content:"";position:absolute;left:18px;top:9px;border-left:13px solid #fff;border-top:7px solid transparent;border-bottom:7px solid transparent}
.woocommerce-product-gallery.ss-has-video .woocommerce-product-gallery__image a{cursor:pointer}
#ss-sbar{position:fixed;left:0;right:0;top:var(--ss-hh,0px);z-index:90;background:rgba(22,19,17,.97);border-bottom:1px solid #2c2722;transform:translateY(-100%);opacity:0;visibility:hidden;transition:transform .2s,opacity .2s,visibility 0s .2s;backdrop-filter:blur(6px)}
#ss-sbar.on{transform:none;opacity:1;visibility:visible;transition:transform .2s,opacity .2s}
.ss-sbar-in{max-width:1360px;margin:0 auto;padding:8px 16px;display:flex;align-items:center;gap:12px}
.ss-sbar-in img{width:40px;height:40px;object-fit:cover;border-radius:4px}
.ss-sbar-t{flex:1;min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:600;color:#f2ece6}
.ss-sbar-p{color:#e09900;font-weight:700;white-space:nowrap}
.ss-sbar-b{background:#e09900;color:#1a1612;border:0;border-radius:6px;padding:10px 22px;font-weight:700;cursor:pointer;text-transform:uppercase;letter-spacing:.05em}
@media(max-width:900px){.ss-use-grid{grid-template-columns:1fr 1fr}}
@media(max-width:600px){.ss-use-grid{grid-template-columns:1fr;gap:10px}.ss-use-i{padding:14px;display:grid;grid-template-columns:40px 1fr;column-gap:12px}.ss-use-ic{width:40px;height:40px;margin:0;grid-row:span 2}.ss-use-i h3{font-size:15px}.ss-use-i p{font-size:13.5px;line-height:1.5}.ss-sbar-in img{display:none}.ss-sbar-b{padding:9px 16px}}
</style>
<?php echo '<script>window.SS_PDYT=' . wp_json_encode( $yt ) . ';</script>'; ?>
<script id="ss-sbar-js">
function ssSbarInit(){var bar=document.getElementById('ss-sbar'),btn=document.querySelector('form.cart .single_add_to_cart_button');if(!bar||!btn||bar.dataset.init)return;bar.dataset.init=1;
function hh(){var h=document.querySelector('.l-header');var b=h?h.getBoundingClientRect().bottom:0;bar.style.setProperty('--ss-hh',Math.max(0,b)+'px')}
bar.querySelector('.ss-sbar-b').addEventListener('click',function(){var x=document.querySelector('form.cart .single_add_to_cart_button');if(x)x.click()});
if('IntersectionObserver' in window){new IntersectionObserver(function(es){es.forEach(function(e){var show=!e.isIntersecting&&e.boundingClientRect.top<0;bar.classList.toggle('on',show);bar.setAttribute('aria-hidden',show?'false':'true');if(show)hh()})}).observe(btn)}
window.addEventListener('resize',hh);window.addEventListener('scroll',function(){requestAnimationFrame(hh)},{passive:true});hh();}
if(document.readyState==='complete')ssSbarInit();else window.addEventListener('load',ssSbarInit);document.addEventListener('DOMContentLoaded',ssSbarInit);
(function(){var Y=window.SS_PDYT;if(!Y)return;var g=document.querySelector('.woocommerce-product-gallery');if(!g)return;var im=g.querySelector('.woocommerce-product-gallery__image a')||g.querySelector('.woocommerce-product-gallery__image')||g;im.style.position='relative';im.style.display='inline-block';im.style.maxWidth='100%';g.classList.add('ss-has-video');var b=document.createElement('span');b.className='ss-yt-pd';b.setAttribute('role','button');b.setAttribute('aria-label','Play video');b.innerHTML='<i></i>';im.appendChild(b);
function open(){var m=document.getElementById('ss-ytm');if(!m){m=document.createElement('div');m.id='ss-ytm';m.setAttribute('role','dialog');m.setAttribute('aria-modal','true');m.innerHTML='<div class="ss-ytm-box"><button type="button" class="ss-ytm-x" aria-label="Close">×</button></div>';document.body.appendChild(m);m.addEventListener('click',function(e){if(e.target===m||e.target.classList.contains('ss-ytm-x'))close()});document.addEventListener('keydown',function(e){if(e.key==='Escape')close()})}
[].forEach.call(document.querySelectorAll('audio,video'),function(a){try{a.pause()}catch(x){}});var box=m.querySelector('.ss-ytm-box');if(!box.querySelector('iframe')){var f=document.createElement('iframe');f.src='https://www.youtube-nocookie.com/embed/'+Y+'?autoplay=1&rel=0&playsinline=1';f.allow='autoplay; encrypted-media; picture-in-picture; fullscreen';f.allowFullscreen=true;box.appendChild(f)}m.classList.add('open')}
function close(){var m=document.getElementById('ss-ytm');if(!m)return;var f=m.querySelector('iframe');if(f)f.remove();m.classList.remove('open')}
window.ssPdOpen=open;if(window.ssPdPending){window.ssPdPending=0;open()}
g.addEventListener('click',function(e){if(e.target.closest('.woocommerce-product-gallery__trigger'))return;if(e.target.closest('.woocommerce-product-gallery__image')||e.target.closest('.ss-yt-pd')){e.preventDefault();e.stopPropagation();e.stopImmediatePropagation();open()}},true);
var tx=0,ty=0,tm=0;g.addEventListener('touchstart',function(e){var t=e.touches[0];tx=t.clientX;ty=t.clientY;tm=Date.now()},{passive:true});
g.addEventListener('touchend',function(e){if(e.target.closest('.woocommerce-product-gallery__trigger'))return;if(!e.target.closest('.woocommerce-product-gallery__image')&&!e.target.closest('.ss-yt-pd'))return;var t=e.changedTouches&&e.changedTouches[0];if(!t||Math.abs(t.clientX-tx)>12||Math.abs(t.clientY-ty)>12||Date.now()-tm>700)return;e.preventDefault();open()},{passive:false});})();
</script>
		<?php
	}, 60 );
}

/* ═══ Solid Sound — 17.10.2026: product page v3 — Abletunes-style block layout (hero card, tech specs, description card, use cases, steps, FAQ band, also bought) ═══ */
if ( ! defined( 'SS_PD3' ) ) {
	define( 'SS_PD3', 1 );
	function ss_pd3_t( $k ) {
		static $T = array(
			'specs'   => array( 'en' => 'Tech specs', 'ua' => 'Технічні характеристики', 'de' => 'Technische Daten', 'fr' => 'Caractéristiques techniques', 'pt' => 'Especificações técnicas' ),
			'royalty' => array( 'en' => 'All files are royalty-free', 'ua' => 'Усі файли без роялті', 'de' => 'Alle Dateien lizenzfrei', 'fr' => 'Tous les fichiers sont libres de droits', 'pt' => 'Todos os ficheiros sem royalties' ),
			'need'    => array( 'en' => 'What you need', 'ua' => 'Що потрібно', 'de' => 'Was du brauchst', 'fr' => 'Ce qu’il vous faut', 'pt' => 'O que precisa' ),
			'tpl'     => array( 'en' => 'Template', 'ua' => 'Шаблон', 'de' => 'Template', 'fr' => 'Template', 'pt' => 'Template' ),
			'license' => array( 'en' => 'License details', 'ua' => 'Умови ліцензії', 'de' => 'Lizenzdetails', 'fr' => 'Détails de la licence', 'pt' => 'Detalhes da licença' ),
			'instant' => array( 'en' => 'Instant download', 'ua' => 'Миттєве завантаження', 'de' => 'Sofort-Download', 'fr' => 'Téléchargement immédiat', 'pt' => 'Download imediato' ),
			'l_audio' => array( 'en' => 'Audio tracks', 'ua' => 'Аудіодоріжки', 'de' => 'Audiospuren', 'fr' => 'Pistes audio', 'pt' => 'Faixas de áudio' ),
			'l_midi'  => array( 'en' => 'MIDI tracks', 'ua' => 'MIDI-доріжки', 'de' => 'MIDI-Spuren', 'fr' => 'Pistes MIDI', 'pt' => 'Faixas MIDI' ),
			'l_send'  => array( 'en' => 'Return tracks', 'ua' => 'Return-канали', 'de' => 'Return-Spuren', 'fr' => 'Pistes de retour', 'pt' => 'Faixas de retorno' ),
			'l_bpm'   => array( 'en' => 'BPM', 'ua' => 'BPM', 'de' => 'BPM', 'fr' => 'BPM', 'pt' => 'BPM' ),
			'l_key'   => array( 'en' => 'Key', 'ua' => 'Тональність', 'de' => 'Tonart', 'fr' => 'Tonalité', 'pt' => 'Tom' ),
			'l_len'   => array( 'en' => 'Length', 'ua' => 'Тривалість', 'de' => 'Länge', 'fr' => 'Durée', 'pt' => 'Duração' ),
			'l_size'  => array( 'en' => 'Size', 'ua' => 'Розмір', 'de' => 'Größe', 'fr' => 'Taille', 'pt' => 'Tamanho' ),
			'desc'    => array( 'en' => 'Description', 'ua' => 'Опис', 'de' => 'Beschreibung', 'fr' => 'Description', 'pt' => 'Descrição' ),
			'any'     => array( 'en' => 'Any DAW', 'ua' => 'Будь-яка DAW', 'de' => 'Jede DAW', 'fr' => 'Tout DAW', 'pt' => 'Qualquer DAW' ),
		);
		$l = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';
		if ( ! $l || empty( $T[ $k ][ $l ] ) ) $l = 'en';
		return isset( $T[ $k ][ $l ] ) ? $T[ $k ][ $l ] : $k;
	}
	function ss_pd3_ic( $n ) {
		$p = array(
			'tpl'   => '<rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/>',
			'plug'  => '<path d="M9 3v5M15 3v5M7 8h10v3a5 5 0 01-10 0zM12 16v5"/>',
			'pc'    => '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/>',
			'audio' => '<path d="M3 12h2M7 8v8M11 5v14M15 9v6M19 11v2"/>',
			'midi'  => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M8 5v8M12 5v8M16 5v8"/>',
			'send'  => '<path d="M9 14l-5-5 5-5"/><path d="M4 9h10a6 6 0 010 12h-2"/>',
			'key'   => '<path d="M9 18V6l10-2v12"/><circle cx="6.5" cy="18" r="2.5"/><circle cx="16.5" cy="16" r="2.5"/>',
			'bpm'   => '<path d="M9 3h6l3 18H6z"/><path d="M12 15l4-8"/>',
			'len'   => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
			'size'  => '<path d="M12 3v12M7 10l5 5 5-5M5 21h14"/>',
			'genre' => '<path d="M4 4h7l9 9-7 7-9-9z"/><circle cx="8" cy="8" r="1.5"/>',
			'file'  => '<path d="M6 3h8l4 4v14H6z"/><path d="M14 3v4h4"/>',
		);
		return '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ( isset( $p[ $n ] ) ? $p[ $n ] : $p['file'] ) . '</svg>';
	}
	function ss_pd3_items( $id, $fmt ) {
		$genre = implode( ', ', wp_list_pluck( ss_pd_terms( $id, 'product-genre' ), 'name' ) );
		$custom = trim( (string) get_post_meta( $id, 'ss_learn', true ) );
		$title = ( 'presets' === $fmt || 'sample-packs' === $fmt ) ? ss_pd_t( 'get' ) : ( 'music' === $fmt ? ss_pd_t( 'for' ) : ss_pd_t( 'learn' ) );
		if ( $custom ) return array( $title, array_filter( array_map( 'trim', preg_split( '/\r?\n/', $custom ) ) ) );
		$pl = ss_pd_plugins( $id );
		if ( 'presets' === $fmt ) { $it = array( sprintf( ss_pd_t( 'p1' ), $pl ? implode( ', ', $pl ) : 'your synth' ) ); if ( $genre ) $it[] = sprintf( ss_pd_t( 'p2' ), $genre ); array_push( $it, ss_pd_t( 'p3' ), ss_pd_t( 'p4' ) ); return array( $title, $it ); }
		if ( 'music' === $fmt ) return array( $title, array( ss_pd_t( 'm1' ), ss_pd_t( 'm2' ), ss_pd_t( 'm3' ), ss_pd_t( 'p4' ) ) );
		if ( 'sample-packs' === $fmt ) return array( $title, array( ss_pd_t( 's1' ), ss_pd_t( 'p4' ) ) );
		return array( $title, array( ss_pd_t( 't1' ), ss_pd_t( 't2' ), ss_pd_native( $id ) || ! $pl ? ss_pd_t( 't3n' ) : sprintf( ss_pd_t( 't3p' ), implode( ', ', $pl ) ), ss_pd_t( 't4' ), ss_pd_t( 't5' ), ss_pd_t( 't6' ) ) );
	}
	add_action( 'wp', function () {
		if ( ! function_exists( 'is_product' ) || ! is_product() ) return;
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		add_action( 'woocommerce_single_product_summary', function () { echo '<div class="ss-buyrow">'; woocommerce_template_single_price(); }, 26 );
		add_action( 'woocommerce_single_product_summary', function () { echo '</div>'; }, 31 );
	} );
	$GLOBALS['ss_pd3_text'] = '';
	add_filter( 'woocommerce_short_description', function ( $ex ) {
		if ( ! function_exists( 'is_product' ) || ! is_product() || ! did_action( 'woocommerce_before_single_product_summary' ) || did_action( 'woocommerce_after_single_product_summary' ) ) return $ex;
		preg_match_all( '#\[audio[^\]]*\](?:\s*\[/audio\])?#i', $ex, $m );
		$text = trim( preg_replace( '#\[audio[^\]]*\](?:\s*\[/audio\])?#i', '', $ex ) );
		if ( '' === $GLOBALS['ss_pd3_text'] ) $GLOBALS['ss_pd3_text'] = $text;
		return implode( "\n", $m[0] );
	}, 5 );
	add_action( 'woocommerce_after_single_product_summary', function () {
		global $product; if ( ! $product ) return; $id = $product->get_id(); $fmt = ss_pd_format( $id ); $cells = array();
		if ( in_array( $fmt, array( 'templates', 'presets' ), true ) ) {
			$v = wp_list_pluck( ss_pd_terms( $id, 'product-version' ), 'name' ); natsort( $v );
			if ( $v && 'templates' === $fmt ) $cells[] = array( 'tpl', ss_pd3_t( 'tpl' ), reset( $v ) . ' (' . ss_pd_t( 'orlater' ) . ')' );
			$pl = ss_pd_plugins( $id );
			$cells[] = array( 'plug', ss_pd_t( 'plugins' ), ( ss_pd_native( $id ) || ! $pl ) ? 'No external plugins' : implode( ', ', $pl ) );
			$cells[] = array( 'pc', ss_pd_t( 'os' ), 'PC & Mac' );
		} else {
			$g = wp_list_pluck( ss_pd_terms( $id, 'product-genre' ), 'name' ); if ( $g ) $cells[] = array( 'genre', ss_pd_t( 'genre' ), implode( ', ', $g ) );
			$cells[] = array( 'pc', ss_pd_t( 'os' ), ss_pd3_t( 'any' ) );
		}
		$ic = array( 'audio' => 'audio', 'midi' => 'midi', 'send' => 'send', 'return' => 'send', 'key' => 'key', 'bpm' => 'bpm', 'length' => 'len', 'size' => 'size' );
		foreach ( $product->get_attributes() as $a ) {
			if ( ! $a->get_visible() ) continue;
			$val = $a->is_taxonomy() ? implode( ', ', wc_get_product_terms( $id, $a->get_name(), array( 'fields' => 'names' ) ) ) : implode( ', ', $a->get_options() );
			if ( '' === trim( $val ) ) continue;
			$label = wc_attribute_label( $a->get_name() ); $icon = 'file';
			foreach ( $ic as $kw => $i ) if ( false !== stripos( $label, $kw ) ) { $icon = $i; break; }
			$lmap = array( 'audio' => 'l_audio', 'midi' => 'l_midi', 'send' => 'l_send', 'return' => 'l_send', 'bpm' => 'l_bpm', 'key' => 'l_key', 'length' => 'l_len', 'size' => 'l_size' ); $lab = ucfirst( strtolower( $label ) );
			foreach ( $lmap as $kw => $lk ) if ( false !== stripos( $label, $kw ) ) { $lab = ss_pd3_t( $lk ); break; }
			$cells[] = array( $icon, $lab, $val );
		}
		if ( ! empty( $GLOBALS['ss_pd3_specx'] ) ) {
			$sl = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';
			$sd = array(
				'fmt' => array( 'en' => 'Format', 'ua' => 'Формат', 'uk' => 'Формат', 'de' => 'Format', 'fr' => 'Format', 'pt' => 'Formato' ),
				'num' => array( 'en' => 'Presets', 'ua' => 'Пресетів', 'uk' => 'Пресетів', 'de' => 'Presets', 'fr' => 'Presets', 'pt' => 'Presets' ),
			);
			foreach ( $GLOBALS['ss_pd3_specx'] as $sx ) {
				$kl = strtolower( $sx[0] ); $sv = preg_replace( '/\((?:or higher|or later|or newer)\)/i', '(' . ss_pd_t( 'orlater' ) . ')', $sx[1] );
				if ( preg_match( '/compat|platform|\bos\b|system/', $kl ) ) continue;
				if ( preg_match( '/plugin|synth|required/', $kl ) ) { foreach ( $cells as $ci => $cc ) if ( 'plug' === $cc[0] ) $cells[ $ci ][2] = $sv; continue; }
				if ( preg_match( '/size|розмір/u', $kl ) ) { $cells[] = array( 'size', ss_pd3_t( 'l_size' ), $sv ); continue; }
				if ( preg_match( '/number|count|кільк/u', $kl ) ) { $cells[] = array( 'tpl', isset( $sd['num'][ $sl ] ) ? $sd['num'][ $sl ] : $sd['num']['en'], $sv ); continue; }
				if ( preg_match( '/pack|format|формат/u', $kl ) ) { $cells[] = array( 'file', isset( $sd['fmt'][ $sl ] ) ? $sd['fmt'][ $sl ] : $sd['fmt']['en'], $sv ); continue; }
				$cells[] = array( 'file', $sx[0], $sv );
			}
		}
		echo '<section class="ss-card ss-specs3"><div class="ss-card-h"><h2>' . esc_html( ss_pd3_t( 'specs' ) ) . '</h2><a class="ss-rf" href="' . esc_url( home_url( '/license/' ) ) . '">' . esc_html( ss_pd3_t( 'royalty' ) ) . '</a></div><div class="ss-spec-row">';
		foreach ( $cells as $c ) echo '<div class="ss-spec"><span class="ss-spec-l">' . ss_pd3_ic( $c[0] ) . esc_html( $c[1] ) . '</span><span class="ss-spec-v">' . esc_html( $c[2] ) . '</span></div>';
		echo '</div></section>';
	}, 2 );
	add_action( 'woocommerce_after_single_product_summary', function () {
		global $product; if ( ! $product ) return; $id = $product->get_id(); $fmt = ss_pd_format( $id );
		list( $lt, $li ) = ss_pd3_items( $id, $fmt );
		$need = array();
		if ( in_array( $fmt, array( 'templates', 'presets' ), true ) ) {
			$v = wp_list_pluck( ss_pd_terms( $id, 'product-version' ), 'name' ); natsort( $v );
			if ( 'templates' === $fmt ) $need[] = ( $v ? reset( $v ) : 'Ableton Live' ) . ' ' . ss_pd_t( 'orlater' );
			$pl = ss_pd_plugins( $id );
			$need[] = ( ss_pd_native( $id ) || ! $pl ) ? ( 'templates' === $fmt ? ss_pd_t( 'native' ) : '—' ) : implode( ', ', $pl );
			$need[] = 'Windows / macOS';
		} else { $need[] = ss_pd3_t( 'any' ); }
		$inside = wp_list_pluck( ss_pd_terms( $id, 'pa_download-include' ), 'name' );
		if ( ! $inside ) {
			if ( 'presets' === $fmt ) { $pl = ss_pd_plugins( $id ); $inside = array( sprintf( ss_pd2_t( 'i_pre' ), $pl ? implode( ', ', $pl ) : 'your synth' ) ); }
			elseif ( 'music' === $fmt ) $inside = array( ss_pd2_t( 'i_mus' ) );
			elseif ( 'templates' === $fmt ) $inside = array( ss_pd2_t( 'i_tpl' ), ss_pd2_t( 'i_smp' ) );
		}
		$inside[] = ss_pd2_t( 'i_lic' );
		$text = $GLOBALS['ss_pd3_text'];
		$long = preg_replace( array( '#<iframe.*?</iframe>#is', '#<figure[^>]*>.*?</figure>#is', '#\[embed\].*?\[/embed\]#is', '#https?://(www\.)?(youtube\.com|youtu\.be)/\S+#i', '#\[/?[a-z_]+[^\]]*\]#i' ), '', (string) get_post_field( 'post_content', $id ) );
		if ( '' !== trim( wp_strip_all_tags( $long ) ) ) $text .= "\n\n" . $long;
		echo '<section class="ss-card ss-desc3"><h2>' . esc_html( ss_pd3_t( 'desc' ) ) . '</h2>';
		if ( trim( wp_strip_all_tags( $text ) ) ) echo '<div class="ss-desc-text">' . wpautop( wp_kses_post( do_shortcode( $text ) ) ) . '</div>';
		echo '<div class="ss-desc-cols"><div><h3>' . esc_html( $lt ) . '</h3><ul class="ss-ul ss-ul-check">';
		foreach ( $li as $x ) echo '<li>' . esc_html( $x ) . '</li>';
		echo '</ul></div><div><h3>' . esc_html( ss_pd3_t( 'need' ) ) . '</h3><ul class="ss-ul">';
		foreach ( $need as $x ) echo '<li>' . esc_html( $x ) . '</li>';
		echo '</ul><h3>' . esc_html( ss_pd2_t( 'inside' ) ) . '</h3><ul class="ss-ul">';
		foreach ( $inside as $x ) echo '<li>' . esc_html( $x ) . '</li>';
		echo '</ul></div></div>';
		$ytm = function_exists( 'ss_yt_map' ) ? ss_yt_map() : array();
		if ( ! empty( $ytm[ $id ] ) ) echo '<div class="ss-inside-video"><h2>' . esc_html( ss_pd2_t( 'inside' ) ) . '</h2><div class="ss-pd-vbox" data-yt="' . esc_attr( $ytm[ $id ] ) . '" role="button" tabindex="0" aria-label="Play video"><img loading="lazy" src="https://i.ytimg.com/vi/' . esc_attr( $ytm[ $id ] ) . '/hqdefault.jpg" alt=""><span class="ss-pd-play"></span></div></div>';
		echo '</section>';
	}, 3 );
	add_action( 'woocommerce_after_single_product_summary', function () {
		global $product; if ( ! $product ) return; $fmt = ss_pd_format( $product->get_id() );
		$s3 = 'presets' === $fmt ? 's3p' : ( 'music' === $fmt || 'sample-packs' === $fmt ? 's3m' : 's3t' );
		echo '<section class="ss-card ss-steps3"><h2>' . esc_html( ss_pd2_t( 'how' ) ) . '</h2><ol>';
		foreach ( array( 's1', 's2', $s3 ) as $i => $k ) echo '<li><span>' . ( $i + 1 ) . '</span>' . esc_html( ss_pd2_t( $k ) ) . '</li>';
		echo '</ol></section>';
	}, 6 );
	add_action( 'woocommerce_after_single_product_summary', function () {
		global $product; if ( ! $product ) return; $fmt = ss_pd_format( $product->get_id() );
		$qs = in_array( $fmt, array( 'music', 'presets', 'sample-packs' ), true ) ? array( 1, 6, 2, 5 ) : array( 1, 6, 2, 3, 4, 5 );
		echo '<section class="ss-faq3"><h2>' . esc_html( ss_pd_t( 'faq' ) ) . '</h2><div class="ss-faq3-in">';
		foreach ( $qs as $i => $n ) {
			$a = ss_pd_t( 'a' . $n );
			if ( 6 === $n ) $a .= ' <a href="' . esc_url( home_url( '/license/' ) ) . '">' . esc_html( ss_pd3_t( 'license' ) ) . ' →</a>';
			if ( 5 === $n ) $a .= ' <a href="' . esc_url( home_url( '/refund_returns/' ) ) . '" aria-label="Refund policy">→</a>';
			echo '<details><summary><b>' . sprintf( '%02d', $i + 1 ) . '</b>' . esc_html( ss_pd_t( 'q' . $n ) ) . '<i></i></summary><p>' . wp_kses_post( $a ) . '</p></details>';
		}
		echo '</div></section>';
	}, 7 );
	add_action( 'wp_head', function () {
		if ( ! function_exists( 'is_product' ) || ! is_product() ) return;
		if ( ! (int) get_comments_number( get_queried_object_id() ) ) echo '<style>.single-product .wcz-product-reviews{display:none!important}</style>'; ?>
<style id="ss-pd3-css">
.single-product .ss-pd,.single-product .ss-pd-video,.single-product .ss-pd-faq,.single-product .wcz-product-long-desc{display:none!important}
.single-product .ss-use-i h3{margin:0 0 6px!important;padding:0!important;line-height:1.35!important}
.single-product .ss-use-ic{display:flex!important}
.single-product .ss-use-i p{font-size:15px!important;line-height:1.6!important}
.single-product div.product>.wcfm_buttons{background:transparent!important;overflow:hidden;height:auto!important;margin-bottom:6px}
.single-product div.product{position:relative;--ss-card:#1c1815;--ss-line:#2e2823;--ss-mut:#a39a92;--ss-acc:#e09900;font-family:system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif}
.single-product div.product:before{content:"";position:absolute;z-index:0;left:50%;width:100vw;transform:translateX(-50%);top:-40px;height:330px;background:linear-gradient(120deg,#2a1803 0%,#5a3500 45%,#e09900 120%);pointer-events:none}
.single-product div.product>*{position:relative;z-index:1}
.single-product .woocommerce-product-gallery{background:transparent}
.single-product .woocommerce-product-gallery__image img{border-radius:16px;box-shadow:0 18px 40px rgba(0,0,0,.45)}
.single-product .summary{background:var(--ss-card);border-radius:16px;padding:26px 28px!important;box-shadow:0 18px 40px rgba(0,0,0,.35);box-sizing:border-box}
.single-product .summary .woocommerce-breadcrumb{font-size:13px;margin-bottom:8px}
.single-product .summary .product_title{font-size:28px!important;line-height:1.2;margin:0 0 10px!important}
.single-product .ss-chips{margin:0 0 16px}
.single-product .ss-chip{background:rgba(224,153,0,.1);border-color:rgba(224,153,0,.35)!important;color:#f0c46a!important}
.single-product .woocommerce-product-details__short-description{margin:0 0 6px}
.single-product .woocommerce-product-details__short-description p{display:none}
.ss-buyrow{display:grid;grid-template-columns:auto 1fr;align-items:center;gap:12px 18px;border-top:1px solid var(--ss-line);padding-top:18px;margin-top:14px}
.ss-buyrow>.price{margin:0!important;font-size:30px!important;font-weight:700;white-space:nowrap}
.ss-buyrow>form.cart{margin:0!important}
.ss-buyrow>*:not(.price):not(form.cart){grid-column:1/-1}
.ss-buyrow form.cart .single_add_to_cart_button{height:54px!important;border-radius:6px!important;font-size:16px!important}
.ss-buyrow .ss-trust{margin:0!important;background:transparent!important;border:0!important;padding:0!important}
.single-product .summary .product_meta{display:none}
.ss-card{background:var(--ss-card);border:1px solid var(--ss-line);border-radius:16px;padding:22px 26px;margin:22px 0 0;clear:both}
.ss-card h2{font-size:18px!important;font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin:0 0 16px!important;font-family:inherit!important}
.ss-card-h{display:flex;justify-content:space-between;align-items:baseline;gap:10px;flex-wrap:wrap}
.ss-rf{font-size:13px;color:var(--ss-acc)!important;text-decoration:none}
.ss-spec-row{display:flex;flex-wrap:wrap;gap:16px 0}
.ss-spec{flex:1 1 0;min-width:110px;padding:0 16px;border-left:1px solid var(--ss-line)}
.ss-spec:first-child{border-left:0;padding-left:0}
.ss-spec-l{display:flex;align-items:center;gap:6px;font-size:12.5px;color:var(--ss-mut);margin-bottom:5px;white-space:nowrap}
.ss-spec-l svg{color:var(--ss-acc);flex:0 0 auto}
.ss-spec-v{display:block;font-size:15px;font-weight:600;color:#f2ece6}
.ss-desc-text p{font-size:16.5px;line-height:1.7;color:#e6dfd8;margin:0 0 12px;max-width:none}
.ss-desc-text p:last-child{margin-bottom:0}
.ss-desc3 .ss-desc-cols{display:none!important}
.ss-inside-video{margin-top:26px;padding-top:22px;border-top:1px solid var(--ss-line)}
.ss-inside-video .ss-pd-vbox{max-width:900px;width:100%}
.ss-desc-cols{display:grid;grid-template-columns:1.3fr 1fr;gap:10px 40px;margin-top:6px}
.ss-desc3 h3{font-size:15px!important;font-weight:700;margin:14px 0 8px!important;color:#fff;font-family:inherit!important}
.ss-ul{margin:0;padding:0;list-style:none}
.ss-ul li{position:relative;padding:5px 0 5px 20px;font-size:15.5px;line-height:1.5;color:#e6dfd8}
.ss-ul li:before{content:"";position:absolute;left:3px;top:13px;width:6px;height:6px;border-radius:50%;background:var(--ss-acc)}
.ss-ul-check li{padding-left:26px}
.ss-ul-check li:before{width:10px;height:5px;border-radius:0;background:none;border-left:2px solid var(--ss-acc);border-bottom:2px solid var(--ss-acc);transform:rotate(-45deg);top:11px;left:4px}
.single-product .ss-use{background:var(--ss-card);border:1px solid var(--ss-line);border-radius:16px;padding:22px 26px;margin:22px 0 0}
.single-product .ss-use h2{font-size:18px!important;text-transform:uppercase;letter-spacing:.04em}
.single-product .ss-use-i{background:#14110f}
.ss-steps3 ol{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin:0;padding:0;list-style:none}
.ss-steps3 li{display:flex;gap:12px;align-items:flex-start;font-size:15.5px;line-height:1.5;color:#e6dfd8;background:#14110f;border-radius:12px;padding:14px}
.ss-steps3 li span{flex:0 0 30px;height:30px;border-radius:50%;background:var(--ss-acc);color:#1a1612;font-weight:800;display:flex;align-items:center;justify-content:center}
.ss-faq3{position:relative;margin:40px 0 0;padding:44px 0 50px;clear:both}
.ss-faq3:before{content:"";position:absolute;z-index:-1;left:50%;top:0;bottom:0;width:100vw;transform:translateX(-50%);background:linear-gradient(120deg,#2a1803 0%,#5a3500 50%,#c88400 120%)}
.ss-faq3 h2{text-align:center;font-size:30px!important;margin:0 0 22px!important;font-family:inherit!important;color:#fff}
.ss-faq3-in{max-width:880px;margin:0 auto;display:flex;flex-direction:column;gap:12px}
.ss-faq3 details{background:#16130f;border-radius:14px;box-shadow:0 8px 24px rgba(0,0,0,.25)}
.ss-faq3 summary{list-style:none;cursor:pointer;display:flex;align-items:center;gap:14px;padding:18px 20px;font-weight:600;font-size:16px;color:#f2ece6}
.ss-faq3 summary::-webkit-details-marker{display:none}
.ss-faq3 summary b{color:var(--ss-acc);font-weight:700;min-width:24px}
.ss-faq3 summary i{margin-left:auto;flex:0 0 30px;height:30px;border-radius:50%;background:rgba(224,153,0,.15);position:relative}
.ss-faq3 summary i:after{content:"";position:absolute;left:11px;top:9px;width:7px;height:7px;border-right:2px solid var(--ss-acc);border-bottom:2px solid var(--ss-acc);transform:rotate(45deg);transition:transform .2s}
.ss-faq3 details[open] summary i:after{transform:rotate(-135deg);top:13px}
.ss-faq3 details p{margin:0;padding:0 20px 18px 58px;font-size:15.5px;line-height:1.6;color:#d6cec6}
.ss-faq3 details p a{color:var(--ss-acc)}
.single-product section.related{clear:both;margin-top:40px}
.single-product section.related>h2{font-size:26px!important;font-family:inherit!important;margin-bottom:18px!important}
.single-product section.related ul.products li.product{background:var(--ss-card);border:1px solid var(--ss-line);border-radius:14px;padding:10px 10px 14px!important;overflow:hidden}
.single-product section.related ul.products li.product img{border-radius:10px}
.single-product .woocommerce-tabs{clear:both}
.single-product .wcz-product-reviews{background:var(--ss-card);border:1px solid var(--ss-line);border-radius:16px;padding:22px 26px;margin-top:22px;clear:both}
@media(max-width:1024px){.ss-desc-cols{grid-template-columns:1fr}}
@media(max-width:767px){
.single-product div.product:before{height:260px;top:-20px}
.single-product .summary{padding:20px 18px!important;margin-top:16px}
.single-product .summary .product_title{font-size:24px!important}
.ss-buyrow{grid-template-columns:auto 1fr;gap:10px 14px}
.ss-buyrow>.price{font-size:24px!important}
.ss-card,.single-product .ss-use{padding:18px 16px;border-radius:14px;margin-top:16px}
.ss-spec-row{display:grid;grid-template-columns:1fr 1fr;gap:14px 12px}
.ss-spec{border-left:0;padding:0;min-width:0}
.ss-steps3 ol{grid-template-columns:1fr}
.ss-faq3{padding:32px 0 36px}
.ss-faq3 h2{font-size:24px!important}
.ss-faq3 summary{padding:15px 14px;font-size:15px}
.ss-faq3 details p{padding:0 14px 16px 14px}
.single-product section.related ul.products{display:flex!important;overflow-x:auto;gap:12px;scroll-snap-type:x mandatory;padding-bottom:8px}
.single-product section.related ul.products li.product{flex:0 0 62%;scroll-snap-align:start;margin:0!important;width:auto!important}
}
</style>
		<?php
	}, 60 );
}

/* Solid Sound 17.10.2026: product page — compact spacing, softer reading colors (text size unchanged) */
add_action( 'wp_head', function () {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) return; ?>
<style id="ss-pd3-compact">
.single-product .l-section.for_shop{padding-top:22px!important;padding-bottom:30px!important}
.single-product div.product:before{top:-22px;height:300px}
.single-product .summary{padding:20px 24px!important}
.single-product .summary .woocommerce-breadcrumb{margin-bottom:4px!important}
.single-product .summary .product_title{margin:0 0 8px!important}
.single-product .ss-chips{margin:0 0 12px!important}
.ss-buyrow{padding-top:14px!important;margin-top:10px!important;gap:10px 16px!important}
.single-product .wcpay-express-checkout-wrapper{margin:2px 0 0!important}
.ss-card,.single-product .ss-use{padding:18px 22px!important;margin-top:14px!important}
.ss-card h2,.single-product .ss-use h2,.ss-inside-video h2{margin:0 0 10px!important}
.ss-inside-video{margin-top:16px!important;padding-top:16px!important}
.single-product .ss-use-grid{gap:12px!important}
.single-product .ss-use-i{padding:14px 16px!important}
.single-product .ss-use-ic{margin-bottom:8px!important}
.ss-steps3 ol{gap:12px!important}
.ss-steps3 li{padding:12px 14px!important}
.ss-faq3{margin-top:24px!important;padding:28px 0 32px!important}
.ss-faq3 h2{margin:0 0 16px!important}
.ss-faq3-in{gap:10px!important}
.ss-faq3 summary{padding:14px 18px!important}
.single-product section.related{margin-top:24px!important}
.single-product section.related>h2{margin-bottom:12px!important}
/* reading colors: warm off-white text on warm dark, no pure white */
.single-product div.product{--ss-card:#1d1915;--ss-line:#332c26;--ss-mut:#aaa198}
.ss-desc-text p,.single-product .ss-use-i p,.ss-steps3 li,.ss-faq3 details p,.ss-ul li{color:#ddd5cc!important}
.single-product .summary .product_title,.ss-card h2,.single-product .ss-use h2,.single-product .ss-use-i h3,.ss-faq3 summary,.ss-spec-v,.ss-inside-video h2{color:#f3ede6!important}
.ss-desc-text p{line-height:1.7!important}
/* centered layout */
.ss-card h2,.single-product .ss-use h2,.ss-inside-video h2{text-align:center!important}
.ss-card-h{justify-content:center!important;flex-direction:column;align-items:center;gap:2px!important}
.ss-card-h h2{margin-bottom:4px!important}
.ss-specs3 .ss-card-h{margin-bottom:12px}
.ss-desc-text{max-width:900px;margin:0 auto;text-align:center}
.ss-inside-video .ss-pd-vbox{margin:0 auto!important}
.ss-spec-row{justify-content:center}
@media(min-width:768px){.ss-spec{flex:1 1 auto!important;min-width:0!important;padding:0 12px!important}.ss-spec:first-child{padding-left:0!important}.ss-spec-v{white-space:nowrap}}
@media(max-width:767px){
.single-product .l-section.for_shop{padding-top:12px!important}
.single-product .summary{padding:16px 16px!important;margin-top:12px!important}
.ss-card,.single-product .ss-use{padding:16px 14px!important;margin-top:12px!important}
.ss-faq3{padding:22px 0 26px!important}
}
</style>
<?php }, 61 );

/* ═══ Solid Sound — 17.10.2026: product cover — water ripple effect under the cursor (WebGL, runs only while moving) ═══ */
add_action( 'after_setup_theme', function () { remove_theme_support( 'wc-product-gallery-zoom' ); }, 100 );
add_filter( 'woocommerce_single_product_zoom_enabled', '__return_false' );
/* ═══ Solid Sound — 17.10.2026: product grid — hover on cover plays muted YouTube preview (desktop only); click still opens popup with sound ═══ */
add_action( 'wp_footer', function () {
	return; /* 17.10.2026: hover preview switched off at the owner's request — cover click opens the video popup */
	$m = ss_yt_map(); if ( ! $m ) return;
	?>
<style id="ss-ythv-css">
.ss-ythv{position:absolute;top:0;left:0;right:0;bottom:0;overflow:hidden;border-radius:8px;opacity:0;transition:opacity .15s;pointer-events:none;z-index:1;background:#000}
.ss-ythv.on{opacity:1}
.ss-ythv iframe{position:absolute;top:50%;left:50%;width:214%;height:120%;transform:translate(-50%,-50%);border:0;pointer-events:none}
</style>
<link rel="preconnect" href="https://www.youtube-nocookie.com"><link rel="preconnect" href="https://i.ytimg.com"><link rel="preconnect" href="https://www.google.com">
<script id="ss-ythv-js">
(function(){
if(!window.matchMedia||!matchMedia('(hover:hover) and (pointer:fine)').matches)return;
var M=<?php echo wp_json_encode( $m ); ?>,CARD='.w-grid.layout_195 .w-grid-item.ss-has-yt',S='.post_image',pool=[],cur=null,vis=new Set(),MAX=4,FRESH=14;
function idOf(it){var m=it.className.match(/\bpost-(\d+)\b/);return m&&M[m[1]]}
function cmd(w,f,a){try{w._f.contentWindow.postMessage(JSON.stringify({event:'command',func:f,args:a||[]}),'*')}catch(e){}}
function snd(w){var ua=navigator.userActivation;if(ua&&!ua.hasBeenActive)return;[].forEach.call(document.querySelectorAll('audio,video'),function(a){try{a.pause()}catch(e){}});document.dispatchEvent(new CustomEvent('ss:pause-audio'));cmd(w,'unMute');cmd(w,'setVolume',[100])}
function on(w){if(cur===w&&w._ready){w.classList.add('on');snd(w)}}
function kill(w){var k=pool.indexOf(w);if(k>-1)pool.splice(k,1);if(w._it)w._it._w=null;w.remove()}
function make(it){var pi=it.querySelector(S),id=idOf(it);if(!pi||!id)return null;if(it._w)return it._w;
var w=document.createElement('div');w.className='ss-ythv';var f=document.createElement('iframe');
f.src='https://www.youtube-nocookie.com/embed/'+id+'?autoplay=1&mute=1&controls=0&playsinline=1&rel=0&iv_load_policy=3&disablekb=1&fs=0&cc_load_policy=0&modestbranding=1&enablejsapi=1&origin='+encodeURIComponent(location.origin);
f.allow='autoplay; encrypted-media';f.tabIndex=-1;f.setAttribute('aria-hidden','true');f.title='';w._f=f;w._pi=pi;w._it=it;w._t=0;it._w=w;
f.onload=function(){try{f.contentWindow.postMessage(JSON.stringify({event:'listening',id:id}),'*')}catch(e){}setTimeout(function(){if(!w._ready){w._ready=1;on(w)}},4500)};
w.appendChild(f);var im=pi.querySelector('img');if(im){w.style.top=im.offsetTop+'px';w.style.left=im.offsetLeft+'px';w.style.width=im.offsetWidth+'px';w.style.height=im.offsetHeight+'px';w.style.right='auto';w.style.bottom='auto'}
pi.appendChild(w);pool.push(w);trim(it);return w}
function trim(keep){while(pool.length>MAX+1){var o=null,i,x;for(i=0;i<pool.length;i++){x=pool[i];if(x!==cur&&x._it!==keep&&!vis.has(x._it)){o=x;break}}if(!o)for(i=0;i<pool.length;i++){x=pool[i];if(x!==cur&&x._it!==keep){o=x;break}}if(!o)break;kill(o)}}
function renew(w){var it=w._it;kill(w);if(vis.has(it))setTimeout(function(){if(!it._w)make(it)},60)}
window.addEventListener('message',function(ev){if(!/youtube/.test(ev.origin))return;var j;try{j=JSON.parse(ev.data)}catch(x){return}if(!j||!j.info)return;
for(var i=0;i<pool.length;i++){var w=pool[i];if(w._f.contentWindow!==ev.source)continue;var ct=j.info.currentTime;
if(j.info.playerState===0){if(cur===w){cmd(w,'seekTo',[0,true]);cmd(w,'playVideo')}else renew(w);return}
if(typeof ct==='number'){w._t=ct;if(!w._ready&&ct>2.4){w._ready=1;on(w)}else if(cur!==w&&w._ready&&ct>FRESH)renew(w)}return}});
function show(it){var w=it._w||make(it);if(!w)return;if(cur&&cur!==w)hide();cur=w;on(w)}
function hide(){if(!cur)return;var w=cur;cur=null;w.classList.remove('on');cmd(w,'mute');renew(w)}
function warm(){if(!('IntersectionObserver' in window))return;var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting)vis.add(e.target);else vis.delete(e.target)});
var list=Array.from(vis).filter(function(it){return !it._w});list.slice(0,Math.max(0,MAX-pool.length)).forEach(function(it,i){setTimeout(function(){if(vis.has(it)&&!it._w)make(it)},i*350)})},{rootMargin:'100px 0px'});
[].forEach.call(document.querySelectorAll(CARD),function(it){it.classList.add('ss-ythv-o');io.observe(it)});
new MutationObserver(function(){[].forEach.call(document.querySelectorAll(CARD+':not(.ss-ythv-o)'),function(it){it.classList.add('ss-ythv-o');io.observe(it)})}).observe(document.body,{childList:true,subtree:true})}
function start(){setTimeout(warm,800)}
if(document.readyState==='complete')start();else window.addEventListener('load',start);
document.addEventListener('mouseover',function(e){var it=e.target.closest&&e.target.closest(CARD);if(!it)return;if(e.target.closest(S)){if(!cur||cur._it!==it)show(it)}else{if(!it._w)make(it);if(cur&&cur._it===it)hide()}});
document.addEventListener('mouseout',function(e){if(!cur)return;if(e.relatedTarget&&cur._pi.contains(e.relatedTarget))return;if(cur._pi.contains(e.target))hide()});
document.addEventListener('click',function(e){if(cur&&cur._pi.contains(e.target))hide()},true);
})();
</script>
<?php }, 100 );

/* ═══ Solid Sound — 17.10.2026: "Tech specs" block written inside a product description → moved into the Tech specs card, removed from the description text ═══ */
add_filter( 'woocommerce_short_description', function ( $ex ) {
	if ( ! function_exists( 'is_product' ) || ! is_product() || ! did_action( 'woocommerce_before_single_product_summary' ) || did_action( 'woocommerce_after_single_product_summary' ) ) return $ex;
	$re = '#<h[1-6][^>]*>\s*(?:<[^>]+>\s*)*(?:tech(?:nical)?\s*spec(?:ification)?s?|технічні\s+характеристики|технические\s+характеристики)\s*:?\s*(?:</[^>]+>\s*)*</h[1-6]>\s*(<pre[^>]*>.*?</pre>|<ul[^>]*>.*?</ul>|(?:<p[^>]*>[^<]{2,60}:[^<]*</p>\s*)+)#isu';
	if ( ! preg_match( $re, $ex, $m ) ) return $ex;
	$txt = wp_strip_all_tags( preg_replace( '#<br\s*/?>|</p>|</li>#i', "\n", $m[1] ) );
	$out = array();
	foreach ( preg_split( '/\R+/u', $txt ) as $ln ) {
		$ln = trim( html_entity_decode( $ln, ENT_QUOTES, 'UTF-8' ) );
		if ( preg_match( '/^([^:]{2,40}):\s*(.+)$/u', $ln, $mm ) ) $out[] = array( trim( $mm[1] ), trim( $mm[2] ) );
	}
	if ( ! $out ) return $ex;
	$GLOBALS['ss_pd3_specx'] = $out;
	return trim( str_replace( $m[0], '', $ex ) );
}, 4 );

/* ═══ Solid Sound — 17.10.2026: product hero — wider info card, more compact buy area (font sizes unchanged) ═══ */
add_action( 'wp_head', function () {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) return;
	?>
<style id="ss-hero-wide">
.ss-buyrow form.cart{display:flex!important;align-items:center;margin:0!important}
.ss-buyrow form.cart .quantity{display:none!important}
.ss-buyrow form.cart .single_add_to_cart_button{flex:1}
@media(max-width:767px){html body.single-product div.product .woocommerce-product-gallery{float:none!important;width:min(82vw,340px)!important;max-width:340px!important;margin:4px auto 14px!important}html body.single-product div.product .woocommerce-product-gallery img{border-radius:14px}}
@media(min-width:1025px){
html body.single-product div.product .woocommerce-product-gallery{width:400px!important;max-width:400px!important}
html body.single-product div.product .summary.entry-summary{width:calc(100% - 424px)!important;min-height:400px;display:flex!important;flex-direction:column;justify-content:center}
.single-product div.product .summary .woocommerce-breadcrumb{margin-bottom:2px!important}
.single-product div.product .summary .product_title{margin-bottom:6px!important}
.single-product div.product .summary .ss-chips{margin-bottom:8px!important}
.single-product div.product .summary .woocommerce-product-rating{margin:0 0 8px!important}
.single-product div.product .summary .woocommerce-product-details__short-description{margin-bottom:4px!important}
.single-product div.product .summary .ss-buyrow{margin-top:6px!important;padding-top:10px!important}
.ss-buyrow .ss-trust{margin-top:8px!important}
.ss-buyrow .wcpay-express-checkout-wrapper{flex-direction:row!important;align-items:center;gap:16px}
.ss-buyrow .wcpay-express-checkout-wrapper::before{width:auto!important;flex:none;white-space:nowrap;margin:0!important}
.ss-buyrow .wcpay-express-checkout-wrapper .StripeElement{flex:1;min-width:0;padding-top:6px!important;padding-bottom:6px!important}
}
</style>
<?php
}, 99 );

/* ═══ Solid Sound — 17.10.2026: product cover never opens the raw .jpg — clicks made before the page finishes loading are queued and open the video once ready ═══ */
add_action( 'woocommerce_product_thumbnails', function () {
	echo '<script>(function(){var s=document.currentScript,g=s&&s.closest(".woocommerce-product-gallery");if(!g)return;g.addEventListener("click",function(e){if(window.ssPdOpen||e.target.closest(".woocommerce-product-gallery__trigger"))return;if(e.target.closest(".woocommerce-product-gallery__image")){e.preventDefault();window.ssPdPending=1}},true)})();</script>';
}, 1 );

/* ═══ Solid Sound — 17.10.2026: AJAX page switches of the SSP player only re-run scripts inside #page-content. Footer scripts marked data-ssp-rerun="<selector to remove first>" are re-run from the new page too (header language flags) ═══ */
add_action( 'wp_head', function () {
	if ( is_admin() ) return;
	echo '<script>(function(){if(!window.DOMParser)return;var P=DOMParser.prototype.parseFromString;DOMParser.prototype.parseFromString=function(s,t){var d=P.call(this,s,t);if(t==="text/html")window.__ssLastDoc=d;return d};document.addEventListener("ssp:navigated",function(){var d=window.__ssLastDoc;window.__ssLastDoc=null;if(!d)return;var m=d.getElementById("page-content");[].forEach.call(d.querySelectorAll("script[data-ssp-rerun]"),function(s){if(m&&m.contains(s))return;var sel=s.getAttribute("data-ssp-rerun");if(sel)[].forEach.call(document.querySelectorAll(sel),function(x){x.remove()});try{var n=document.createElement("script");n.text=s.textContent;document.body.appendChild(n);n.remove()}catch(e){}})})})();</script>';
}, 1 );

/* ═══ Solid Sound — 17.10.2026: wider site content on desktop (1360 → 1440 px, smaller side margins) ═══ */
add_action( 'wp_head', function () {
	if ( is_admin() ) return;
	echo '<style id="ss-wide">@media(min-width:1025px){html:root{--site-content-width:1520px}html body .l-section-h,html body .l-subheader-h{max-width:1520px!important}html body .l-section,html body .l-subheader{padding-left:24px;padding-right:24px}html body .l-section-h>.g-cols>.has_bg_color:first-child>.vc_column-inner{margin-left:24px}html body .l-section-h>.g-cols>.has_bg_color:last-child>.vc_column-inner{margin-right:24px}}</style>';
}, 99 );

/* ═══ Solid Sound — 17.10.2026: "Browse products / templates" buttons (#templates etc.) scroll straight to the product grid toolbar (Play All · search · filters), not to the Best Products box ═══ */
add_action( 'wp_footer', function () {
	if ( is_admin() ) return;
	?>
<script id="ss-goto-js">(function(){function tgt(h){if(!h||h.length<2)return null;var s;try{s=document.querySelector(h)}catch(e){return null}if(!s)return null;return s.querySelector(".sstb")||s.querySelector(".w-grid.layout_195")}function go(el,smooth){var hd=document.querySelector(".l-header");var off=(hd&&getComputedStyle(hd).position!=="static"?hd.getBoundingClientRect().height:0)+(document.getElementById("wpadminbar")?document.getElementById("wpadminbar").offsetHeight:0)+12;window.scrollTo({top:el.getBoundingClientRect().top+window.pageYOffset-off,behavior:smooth?"smooth":"auto"})}document.addEventListener("click",function(e){var a=e.target.closest&&e.target.closest("a[href*=\"#\"]");if(!a)return;var u;try{u=new URL(a.href,location.href)}catch(x){return}if(u.pathname!==location.pathname||!u.hash)return;var el=tgt(u.hash);if(!el)return;e.preventDefault();e.stopImmediatePropagation();go(el,true);try{history.replaceState(null,"",u.hash)}catch(x){}},true);function onLoad(){var el=tgt(location.hash);if(el)setTimeout(function(){go(el,false)},60)}if(document.readyState==="complete")onLoad();else window.addEventListener("load",onLoad)})();</script>
<?php
}, 99 );

/* ═══ Solid Sound — 17.10.2026: iPhone/iPad — the YouTube popup starts muted (Safari blocks autoplay with sound); a big "Tap for sound" button unmutes it ═══ */
add_action( 'wp_footer', function () {
	if ( is_admin() ) return;
	?>
<style id="ss-snd-css">#ss-ytm .ss-ytm-box{position:relative}#ss-ytm .ss-snd{position:absolute;left:50%;bottom:16%;transform:translateX(-50%);z-index:5;display:flex;align-items:center;gap:8px;background:#e09900;color:#111;border:0;border-radius:999px;padding:12px 20px;font:600 16px/1 inherit;box-shadow:0 6px 24px rgba(0,0,0,.5);cursor:pointer;white-space:nowrap}#ss-ytm .ss-snd svg{width:20px;height:20px}</style>
<script id="ss-snd-js">
(function(){var IOS=/iP(hone|ad|od)/.test(navigator.userAgent)||(navigator.platform==='MacIntel'&&navigator.maxTouchPoints>1);if(!IOS)return;
var L={en:'Tap for sound',uk:'Увімкнути звук',ua:'Увімкнути звук',de:'Ton einschalten',fr:'Activer le son',pt:'Ativar o som'};var lg=(document.documentElement.lang||'en').slice(0,2).toLowerCase();var T=L[lg]||L.en;
function cmd(f,n,a){try{f.contentWindow.postMessage(JSON.stringify({event:'command',func:n,args:a||[]}),'*')}catch(e){}}
function add(box,f){if(f._ssSnd||box.querySelector('.ss-snd'))return;var b=document.createElement('button');b.type='button';b.className='ss-snd';b.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5L6 9H3v6h3l5 4z"/><path d="M15.5 8.5a5 5 0 010 7M18.5 5.5a9 9 0 010 13"/></svg><span></span>';b.querySelector('span').textContent=T;
b.addEventListener('click',function(e){e.preventDefault();e.stopPropagation();f._ssSnd=1;cmd(f,'unMute');cmd(f,'setVolume',[100]);cmd(f,'playVideo');b.remove()});box.appendChild(b)}
function check(){var m=document.getElementById('ss-ytm');if(!m)return;var box=m.querySelector('.ss-ytm-box'),f=m.querySelector('iframe');if(!box)return;if(f&&/[?&]mute=1/.test(f.src))add(box,f);else{var o=box.querySelector('.ss-snd');if(o)o.remove()}}
new MutationObserver(check).observe(document.body,{childList:true,subtree:true});})();
</script>
<?php
}, 100 );

/* ═══ Solid Sound — 17.10.2026: product grid — the YouTube button moves from the cover into the player row (play · + · YouTube); cover click opens the product page ═══ */
add_action( 'wp_footer', function () {
	if ( is_admin() ) return;
	?>
<style id="ss-ytb-css">
.w-grid .ssw .ss-yt-badge{position:static!important;top:auto!important;right:auto!important;width:30px!important;height:30px!important;flex:none;display:inline-flex!important;align-items:center;justify-content:center;border:.8px solid rgba(255,60,60,.6);border-radius:50%;margin:0 2px 0 0;background:transparent;cursor:pointer;box-shadow:none!important;transition:background .2s,border-color .2s}
.w-grid .ssw .ss-yt-badge:hover{background:rgba(255,0,0,.14);border-color:#f33}
.w-grid .ssw .ss-yt-badge svg{display:block;width:17px;height:17px}
.w-grid .ssw .ss-yt-badge i{display:none!important}
</style>
<script id="ss-ytb-js">
(function(){var ICO='<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="1.5" y="5" width="21" height="14" rx="4" fill="#f00"/><path d="M10 9.2l5 2.8-5 2.8z" fill="#fff"/></svg>';
function place(){[].forEach.call(document.querySelectorAll('.w-grid .w-grid-item.ss-has-yt'),function(it){var w=it.querySelector('.ssw');if(!w)return;var b=it.querySelector('.ss-yt-badge');if(!b||b.parentNode===w)return;var add=w.querySelector('.ssw-add');w.insertBefore(b,add?add.nextSibling:(w.children[1]||null));b.innerHTML=ICO;b.setAttribute('role','button');b.setAttribute('tabindex','0');b.setAttribute('aria-label','Watch video');b.title='Watch video'})}
place();var q=0;new MutationObserver(function(){if(q)return;q=setTimeout(function(){q=0;place()},150)}).observe(document.body,{childList:true,subtree:true});
document.addEventListener('keydown',function(e){if((e.key==='Enter'||e.key===' ')&&e.target.classList&&e.target.classList.contains('ss-yt-badge')){e.preventDefault();e.target.click()}});})();
</script>
<?php
}, 100 );

/* ═══ Solid Sound — 17.10.2026: Services & Prices page — section anchors (#svc-mixing, #svc-remix, #svc-ghost-producing …) for the hero service tiles ═══ */
add_action( 'wp_footer', function () {
	if ( is_admin() || ! is_page( 1947 ) ) return;
	echo '<script data-ssp-rerun="">(function(){function off(){var hd=document.querySelector(".l-header");return (hd?hd.getBoundingClientRect().height:0)+(document.getElementById("wpadminbar")?document.getElementById("wpadminbar").offsetHeight:0)+16}function go(){var id=decodeURIComponent(location.hash.slice(1));if(!/^svc-/.test(id))return;var k=id.slice(4);var tabs=[].slice.call(document.querySelectorAll(".pr-tabs [data-t]"));var tb=tabs.filter(function(a){return a.getAttribute("data-t")===k})[0]||tabs.filter(function(a){return a.textContent.trim().toLowerCase().replace(/[^a-z0-9]+/g,"-").indexOf(k)===0})[0];if(!tb)return;tb.click();var bar=tb.closest(".pr-tabs")||tb;window.scrollTo({top:bar.getBoundingClientRect().top+window.pageYOffset-off(),behavior:"auto"})}go();setTimeout(go,300);window.addEventListener("load",function(){setTimeout(go,60)});window.addEventListener("hashchange",go)})();</script>';
}, 99 );

/* ═══ Solid Sound — 17.10.2026: FREE zone (/free/) — free products (category "free", price 0) unlocked by subscribing: name + email + who you are. No checkout: one click download. ═══ */
if ( ! function_exists( 'ss_free_t' ) ) {
	function ss_free_lang() {
		$l = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';
		if ( 'uk' === $l ) $l = 'ua';
		return in_array( $l, array( 'en', 'ua', 'de', 'fr', 'pt' ), true ) ? $l : 'en';
	}
	function ss_free_t( $k ) {
		static $T = array(
			'kicker' => array( 'en' => 'Free zone', 'ua' => 'Безкоштовна зона', 'de' => 'Free Zone', 'fr' => 'Zone gratuite', 'pt' => 'Zona grátis' ),
			'h1' => array( 'en' => 'Free tools for producers', 'ua' => 'Безкоштовні інструменти для продюсерів', 'de' => 'Kostenlose Tools für Produzenten', 'fr' => 'Outils gratuits pour producteurs', 'pt' => 'Ferramentas grátis para produtores' ),
			'lead' => array( 'en' => 'Ableton templates, presets and samples — free for subscribers. New drops regularly.', 'ua' => 'Шаблони Ableton, пресети та семпли — безкоштовно для підписників. Нові релізи регулярно.', 'de' => 'Ableton-Templates, Presets und Samples — kostenlos für Abonnenten. Regelmäßig neue Drops.', 'fr' => 'Templates Ableton, presets et samples — gratuits pour les abonnés. Nouveautés régulières.', 'pt' => 'Templates Ableton, presets e samples — grátis para inscritos. Novidades com frequência.' ),
			'form_h' => array( 'en' => 'Get free access', 'ua' => 'Отримати безкоштовний доступ', 'de' => 'Kostenlosen Zugang erhalten', 'fr' => 'Accès gratuit', 'pt' => 'Acesso grátis' ),
			'form_sub' => array( 'en' => 'One form — instant access to every free download.', 'ua' => 'Одна форма — миттєвий доступ до всіх безкоштовних файлів.', 'de' => 'Ein Formular — sofortiger Zugang zu allen Gratis-Downloads.', 'fr' => 'Un formulaire — accès immédiat à tous les téléchargements gratuits.', 'pt' => 'Um formulário — acesso imediato a todos os downloads grátis.' ),
			'name' => array( 'en' => 'Name', 'ua' => "Ім'я", 'de' => 'Name', 'fr' => 'Prénom', 'pt' => 'Nome' ),
			'email' => array( 'en' => 'Email', 'ua' => 'Email', 'de' => 'E-Mail', 'fr' => 'E-mail', 'pt' => 'E-mail' ),
			'who' => array( 'en' => 'I am a…', 'ua' => 'Я…', 'de' => 'Ich bin…', 'fr' => 'Je suis…', 'pt' => 'Eu sou…' ),
			'consent' => array( 'en' => 'Send me free packs, new releases and offers (max 2 emails / month). Unsubscribe anytime.', 'ua' => 'Надсилайте мені безкоштовні паки, новинки та пропозиції (до 2 листів на місяць). Відписатися можна будь-коли.', 'de' => 'Schickt mir Gratis-Packs, Neuheiten und Angebote (max. 2 E-Mails / Monat). Abmeldung jederzeit.', 'fr' => 'Envoyez-moi des packs gratuits, nouveautés et offres (2 e-mails / mois max). Désinscription à tout moment.', 'pt' => 'Envie-me packs grátis, novidades e ofertas (máx. 2 e-mails / mês). Cancele quando quiser.' ),
			'privacy' => array( 'en' => 'Privacy policy', 'ua' => 'Політика конфіденційності', 'de' => 'Datenschutz', 'fr' => 'Confidentialité', 'pt' => 'Privacidade' ),
			'btn' => array( 'en' => 'Unlock free downloads', 'ua' => 'Відкрити безкоштовні файли', 'de' => 'Gratis-Downloads freischalten', 'fr' => 'Débloquer les téléchargements', 'pt' => 'Liberar downloads grátis' ),
			'has' => array( 'en' => 'You have free access — download anything below.', 'ua' => 'У тебе є безкоштовний доступ — завантажуй усе нижче.', 'de' => 'Du hast kostenlosen Zugang — lade alles unten herunter.', 'fr' => 'Tu as l’accès gratuit — télécharge tout ci-dessous.', 'pt' => 'Você tem acesso grátis — baixe tudo abaixo.' ),
			'dl' => array( 'en' => 'Download', 'ua' => 'Завантажити', 'de' => 'Herunterladen', 'fr' => 'Télécharger', 'pt' => 'Baixar' ),
			'lock' => array( 'en' => 'Get free access', 'ua' => 'Отримати доступ', 'de' => 'Zugang holen', 'fr' => 'Obtenir l’accès', 'pt' => 'Obter acesso' ),
			'empty' => array( 'en' => 'The first free drop is coming soon. Subscribe now and get it first.', 'ua' => 'Перший безкоштовний реліз уже скоро. Підпишись зараз і отримай його першим.', 'de' => 'Der erste Gratis-Drop kommt bald. Jetzt abonnieren und als Erste/r bekommen.', 'fr' => 'Le premier drop gratuit arrive bientôt. Abonne-toi pour l’avoir en premier.', 'pt' => 'O primeiro drop grátis chega em breve. Inscreva-se e receba primeiro.' ),
			'ok' => array( 'en' => 'Done! Unlocking your downloads…', 'ua' => 'Готово! Відкриваємо завантаження…', 'de' => 'Fertig! Downloads werden freigeschaltet…', 'fr' => 'C’est fait ! Déblocage…', 'pt' => 'Pronto! Liberando downloads…' ),
			'err' => array( 'en' => 'Please enter your name and a valid email, and tick the box.', 'ua' => "Вкажи ім'я, правильний email і постав галочку.", 'de' => 'Bitte Name und gültige E-Mail eingeben und das Kästchen ankreuzen.', 'fr' => 'Indique ton prénom, un e-mail valide et coche la case.', 'pt' => 'Informe nome, um e-mail válido e marque a caixa.' ),
			'free' => array( 'en' => 'FREE', 'ua' => 'FREE', 'de' => 'FREE', 'fr' => 'FREE', 'pt' => 'FREE' ),
		);
		$l = ss_free_lang();
		return isset( $T[ $k ][ $l ] ) ? $T[ $k ][ $l ] : ( isset( $T[ $k ]['en'] ) ? $T[ $k ]['en'] : $k );
	}
	function ss_free_roles() {
		return array(
			'producer' => array( 'en' => 'Producer / beatmaker', 'ua' => 'Продюсер / бітмейкер', 'de' => 'Produzent:in / Beatmaker', 'fr' => 'Producteur / beatmaker', 'pt' => 'Produtor / beatmaker' ),
			'dj' => array( 'en' => 'DJ', 'ua' => 'DJ', 'de' => 'DJ', 'fr' => 'DJ', 'pt' => 'DJ' ),
			'learning' => array( 'en' => 'Learning music production', 'ua' => 'Вчуся продакшну', 'de' => 'Lerne Musikproduktion', 'fr' => 'J’apprends la production', 'pt' => 'Aprendendo produção' ),
			'creator' => array( 'en' => 'Video / content creator', 'ua' => 'Відео / контент-мейкер', 'de' => 'Video / Content Creator', 'fr' => 'Créateur vidéo / contenu', 'pt' => 'Criador de vídeo / conteúdo' ),
			'artist' => array( 'en' => 'Artist (need mixing & mastering)', 'ua' => 'Артист (потрібне зведення та мастеринг)', 'de' => 'Artist (brauche Mixing & Mastering)', 'fr' => 'Artiste (mixage & mastering)', 'pt' => 'Artista (mixagem e masterização)' ),
			'other' => array( 'en' => 'Other', 'ua' => 'Інше', 'de' => 'Andere', 'fr' => 'Autre', 'pt' => 'Outro' ),
		);
	}

	/* access: logged-in user OR signed cookie set after subscribing */
	function ss_free_sig( $email ) { return hash_hmac( 'sha256', strtolower( $email ), wp_salt( 'auth' ) . 'ss_free' ); }
	function ss_free_has_access() {
		if ( is_user_logged_in() ) return true;
		if ( empty( $_COOKIE['ss_free'] ) ) return false;
		$p = explode( '|', base64_decode( (string) $_COOKIE['ss_free'] ) );
		return 2 === count( $p ) && is_email( $p[0] ) && hash_equals( ss_free_sig( $p[0] ), $p[1] );
	}

	/* one-time setup: category + page */
	add_action( 'init', function () {
		if ( get_option( 'ss_free_setup_v1' ) ) return;
		if ( ! term_exists( 'free', 'product_cat' ) ) wp_insert_term( 'Free', 'product_cat', array( 'slug' => 'free' ) );
		if ( ! get_page_by_path( 'free' ) ) {
			wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Free', 'post_name' => 'free', 'post_content' => '[ss_free]' ) );
		}
		update_option( 'ss_free_setup_v1', 1 );
	}, 30 );

	/* free products stay out of the shop, grids, search and New arrivals — only on /free/ */
	add_action( 'pre_get_posts', function ( $q ) {
		if ( $q->get( 'ss_free_q' ) ) return;
		$ajax_grid = wp_doing_ajax() && isset( $_REQUEST['action'] ) && false !== strpos( (string) $_REQUEST['action'], 'grid' );
		if ( is_admin() && ! $ajax_grid ) return;
		if ( $q->is_main_query() && $q->is_singular() ) return;
		$pt = $q->get( 'post_type' );
		$is_prod = ( 'product' === $pt ) || ( is_array( $pt ) && in_array( 'product', $pt, true ) ) || ( $q->is_main_query() && ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) );
		if ( ! $is_prod ) return;
		$tq = (array) $q->get( 'tax_query' );
		$tq[] = array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => array( 'free' ), 'operator' => 'NOT IN' );
		$q->set( 'tax_query', $tq );
	}, 20 );

	/* subscribe */
	function ss_free_join() {
		check_ajax_referer( 'ss_free_join', 'n' );
		if ( ! empty( $_POST['website'] ) ) wp_send_json_error( array( 'm' => 'spam' ) );
		$name = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
		$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
		$role = sanitize_key( $_POST['role'] ?? '' );
		$ok = ! empty( $_POST['consent'] );
		if ( '' === $name || ! is_email( $email ) || ! $ok ) wp_send_json_error( array( 'm' => ss_free_t( 'err' ) ) );
		$roles = ss_free_roles();
		if ( ! isset( $roles[ $role ] ) ) $role = 'other';
		$uid = email_exists( $email );
		if ( ! $uid ) {
			$login = sanitize_user( current( explode( '@', $email ) ), true );
			if ( '' === $login || username_exists( $login ) ) $login .= wp_rand( 100, 9999 );
			$uid = wp_insert_user( array( 'user_login' => $login, 'user_email' => $email, 'user_pass' => wp_generate_password( 20 ), 'first_name' => $name, 'display_name' => $name, 'role' => get_role( 'customer' ) ? 'customer' : 'subscriber' ) );
			if ( is_wp_error( $uid ) ) wp_send_json_error( array( 'm' => $uid->get_error_message() ) );
			update_user_meta( $uid, 'ss_signup_source', 'free_zone' );
			wp_new_user_notification( $uid, null, 'user' );
		}
		update_user_meta( $uid, 'ss_who', $role );
		update_user_meta( $uid, 'ss_free_optin', current_time( 'mysql' ) );
		update_user_meta( $uid, 'ss_free_lang', ss_free_lang() );
		$val = base64_encode( strtolower( $email ) . '|' . ss_free_sig( $email ) );
		setcookie( 'ss_free', $val, array( 'expires' => time() + 2 * YEAR_IN_SECONDS, 'path' => '/', 'secure' => is_ssl(), 'httponly' => true, 'samesite' => 'Lax' ) );
		wp_send_json_success( array( 'm' => ss_free_t( 'ok' ) ) );
	}
	add_action( 'wp_ajax_ss_free_join', 'ss_free_join' );
	add_action( 'wp_ajax_nopriv_ss_free_join', 'ss_free_join' );

	/* download (no checkout) */
	function ss_free_dl() {
		$pid = absint( $_GET['pid'] ?? 0 );
		$p = $pid ? wc_get_product( $pid ) : null;
		if ( ! $p || ! has_term( 'free', 'product_cat', $pid ) || 'publish' !== get_post_status( $pid ) ) wp_die( 'Not found', '', array( 'response' => 404 ) );
		if ( ! ss_free_has_access() ) { wp_safe_redirect( get_permalink( get_page_by_path( 'free' ) ) . '#ss-free-form' ); exit; }
		$files = $p->get_downloads();
		if ( ! $files ) wp_die( 'No file attached yet', '', array( 'response' => 404 ) );
		$f = reset( $files );
		update_post_meta( $pid, 'ss_free_downloads', (int) get_post_meta( $pid, 'ss_free_downloads', true ) + 1 );
		if ( class_exists( 'WC_Download_Handler' ) ) { WC_Download_Handler::download( $f->get_file(), $pid ); exit; }
		wp_redirect( $f->get_file() ); exit;
	}
	add_action( 'wp_ajax_ss_free_dl', 'ss_free_dl' );
	add_action( 'wp_ajax_nopriv_ss_free_dl', 'ss_free_dl' );

	/* the page */
	add_shortcode( 'ss_free', function () {
		$acc = ss_free_has_access();
		$q = new WP_Query( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 60, 'ss_free_q' => 1, 'tax_query' => array( array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => array( 'free' ) ) ) ) );
		$l = ss_free_lang();
		ob_start(); ?>
<div class="ssf">
	<div class="ssf-hd">
		<span class="ssf-k"><?php echo esc_html( ss_free_t( 'kicker' ) ); ?></span>
		<h1 class="ssf-h1"><?php echo esc_html( ss_free_t( 'h1' ) ); ?></h1>
		<p class="ssf-lead"><?php echo esc_html( ss_free_t( 'lead' ) ); ?></p>
	</div>
	<?php if ( $acc ) : ?>
		<div class="ssf-has"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12l5 5 9-10"/></svg><?php echo esc_html( ss_free_t( 'has' ) ); ?></div>
	<?php else : ?>
		<form class="ssf-form" id="ss-free-form" novalidate>
			<h2><?php echo esc_html( ss_free_t( 'form_h' ) ); ?></h2>
			<p class="ssf-sub"><?php echo esc_html( ss_free_t( 'form_sub' ) ); ?></p>
			<div class="ssf-row">
				<label><span><?php echo esc_html( ss_free_t( 'name' ) ); ?></span><input name="name" autocomplete="given-name" required></label>
				<label><span><?php echo esc_html( ss_free_t( 'email' ) ); ?></span><input name="email" type="email" autocomplete="email" required></label>
			</div>
			<fieldset class="ssf-who"><legend><?php echo esc_html( ss_free_t( 'who' ) ); ?></legend>
				<?php $first = true; foreach ( ss_free_roles() as $k => $r ) : ?>
					<label class="ssf-chip"><input type="radio" name="role" value="<?php echo esc_attr( $k ); ?>"<?php echo $first ? ' checked' : ''; ?>><span><?php echo esc_html( isset( $r[ $l ] ) ? $r[ $l ] : $r['en'] ); ?></span></label>
				<?php $first = false; endforeach; ?>
			</fieldset>
			<label class="ssf-ck"><input type="checkbox" name="consent" value="1" required><span><?php echo esc_html( ss_free_t( 'consent' ) ); ?> <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( ss_free_t( 'privacy' ) ); ?></a></span></label>
			<input type="text" name="website" tabindex="-1" autocomplete="off" class="ssf-hp" aria-hidden="true">
			<button type="submit" class="ssf-btn"><?php echo esc_html( ss_free_t( 'btn' ) ); ?></button>
			<p class="ssf-msg" role="status" aria-live="polite"></p>
		</form>
	<?php endif; ?>
	<?php if ( $q->have_posts() ) : ?>
		<div class="ssf-grid">
		<?php while ( $q->have_posts() ) : $q->the_post(); $p = wc_get_product( get_the_ID() ); if ( ! $p ) continue;
			$img = get_the_post_thumbnail_url( get_the_ID(), 'woocommerce_thumbnail' );
			$fmt = wp_get_post_terms( get_the_ID(), 'product-format', array( 'fields' => 'names' ) );
			$dl = admin_url( 'admin-ajax.php?action=ss_free_dl&pid=' . get_the_ID() ); ?>
			<article class="ssf-card<?php echo $acc ? '' : ' is-locked'; ?>">
				<a class="ssf-cov" href="<?php the_permalink(); ?>"><?php if ( $img ) : ?><img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy"><?php endif; ?><span class="ssf-badge"><?php echo esc_html( ss_free_t( 'free' ) ); ?></span></a>
				<div class="ssf-body">
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php if ( ! is_wp_error( $fmt ) && $fmt ) : ?><span class="ssf-tag"><?php echo esc_html( $fmt[0] ); ?></span><?php endif; ?>
					<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( strip_shortcodes( $p->get_short_description() ) ), 18 ) ); ?></p>
					<?php if ( $acc ) : ?>
						<a class="ssf-btn ssf-dl" href="<?php echo esc_url( $dl ); ?>" rel="nofollow"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4v11M7 10l5 5 5-5M5 20h14"/></svg><?php echo esc_html( ss_free_t( 'dl' ) ); ?></a>
					<?php else : ?>
						<a class="ssf-btn ssf-ghost" href="#ss-free-form"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg><?php echo esc_html( ss_free_t( 'lock' ) ); ?></a>
					<?php endif; ?>
				</div>
			</article>
		<?php endwhile; wp_reset_postdata(); ?>
		</div>
	<?php else : ?>
		<p class="ssf-empty"><?php echo esc_html( ss_free_t( 'empty' ) ); ?></p>
	<?php endif; ?>
</div>
<style id="ss-free-css">
.ssf{max-width:1120px;margin:0 auto;padding:28px 0 56px;color:#EDE7DF;font-family:Jura,sans-serif}
.ssf-hd{text-align:center;margin:0 auto 28px;max-width:760px}
.ssf-k{display:inline-block;font-weight:700;font-size:13px;letter-spacing:2.4px;text-transform:uppercase;color:#E09900;margin-bottom:12px}
.ssf-h1{font-size:clamp(32px,4vw,52px)!important;line-height:1.08!important;margin:0 0 14px!important;color:#F4EFE8!important}
.ssf-lead{font-size:17px;line-height:1.55;color:#B9AFA3;margin:0}
.ssf-form{max-width:640px;margin:0 auto 40px;background:rgba(24,22,20,.96);border:.8px solid rgba(255,255,255,.08);border-radius:12px;padding:28px 28px 22px;box-sizing:border-box}
.ssf-form h2{font-size:24px!important;margin:0 0 6px!important;text-align:center;color:#F4EFE8!important}
.ssf-sub{text-align:center;color:#9B9288;margin:0 0 20px;font-size:15px}
.ssf-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px}
.ssf-form label>span{display:block;font-size:13px;font-weight:700;color:#CFC6BA;margin-bottom:6px}
.ssf-form input[type=text],.ssf-form input[type=email],.ssf-form input:not([type]){width:100%;height:46px;box-sizing:border-box;border-radius:8px;border:.8px solid rgba(255,255,255,.14);background:#141210;color:#F4EFE8;padding:0 14px;font:600 15px Jura,sans-serif}
.ssf-form input:focus{outline:none;border-color:#E09900}
.ssf-who{border:0;margin:0 0 16px;padding:0;display:flex;flex-wrap:wrap;gap:8px}
.ssf-who legend{font-size:13px;font-weight:700;color:#CFC6BA;margin-bottom:8px;padding:0}
.ssf-chip{position:relative;cursor:pointer}
.ssf-chip input{position:absolute;opacity:0;pointer-events:none}
.ssf-chip span{display:inline-flex;align-items:center;height:36px;padding:0 14px;border-radius:8px;border:.8px solid rgba(255,255,255,.14);background:rgba(255,255,255,.03);font-size:14px;font-weight:700;color:#DAD2C7!important;margin:0!important;transition:border-color .2s,background .2s}
.ssf-chip input:checked+span{border-color:#E09900;background:rgba(224,153,0,.14);color:#F4EFE8!important}
.ssf-chip input:focus-visible+span{outline:2px solid #E09900;outline-offset:2px}
.ssf-ck{display:flex;gap:10px;align-items:flex-start;font-size:13.5px;line-height:1.45;color:#B9AFA3;margin-bottom:18px;cursor:pointer}
.ssf-ck input{margin-top:3px;accent-color:#E09900;width:16px;height:16px;flex:none}
.ssf-ck span{display:inline!important;font-weight:600!important;color:#B9AFA3!important;margin:0!important}
.ssf-hp{position:absolute!important;left:-9999px!important}
.ssf-btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;height:48px;padding:0 22px;border-radius:8px;border:0;background:#E09900;color:#111!important;font:700 16px Jura,sans-serif;text-decoration:none!important;cursor:pointer;transition:background .2s}
.ssf-btn:hover{background:#f2a800}
.ssf-btn svg{width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2.2;stroke-linecap:round;stroke-linejoin:round}
.ssf-form .ssf-btn{width:100%}
.ssf-btn[disabled]{opacity:.6;cursor:wait}
.ssf-msg{min-height:20px;margin:12px 0 0;text-align:center;font-size:14px;font-weight:700}
.ssf-msg.ok{color:#4CC46A}.ssf-msg.bad{color:#FF7A6B}
.ssf-has{max-width:640px;margin:0 auto 32px;display:flex;align-items:center;justify-content:center;gap:10px;padding:14px 18px;border-radius:10px;background:rgba(76,196,106,.1);border:.8px solid rgba(76,196,106,.35);color:#CFEFD7;font-weight:700}
.ssf-has svg{width:20px;height:20px;fill:none;stroke:#4CC46A;stroke-width:2.8;stroke-linecap:round;stroke-linejoin:round;flex:none}
.ssf-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:18px}
.ssf-card{background:rgba(24,22,20,.96);border:.8px solid rgba(255,255,255,.08);border-radius:12px;overflow:hidden;display:flex;flex-direction:column}
.ssf-cov{position:relative;display:block;aspect-ratio:1/1;background:#1a1816}
.ssf-cov img{width:100%;height:100%;object-fit:cover;display:block}
.ssf-badge{position:absolute;left:10px;top:10px;background:#E09900;color:#111;font-weight:700;font-size:12px;letter-spacing:1.4px;border-radius:6px;padding:5px 8px}
.ssf-card.is-locked .ssf-cov img{filter:saturate(.75) brightness(.8)}
.ssf-body{padding:14px 16px 16px;display:flex;flex-direction:column;gap:8px;flex:1}
.ssf-body h3{margin:0!important;font-size:18px!important;line-height:1.25!important}
.ssf-body h3 a{color:#F4EFE8!important;text-decoration:none!important}
.ssf-tag{align-self:flex-start;font-size:11.5px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:#E09900;background:rgba(224,153,0,.12);border-radius:6px;padding:4px 8px}
.ssf-body p{margin:0;font-size:14px;line-height:1.5;color:#A39A8F;flex:1}
.ssf-body .ssf-btn{margin-top:6px;width:100%}
.ssf-ghost{background:rgba(255,255,255,.04)!important;border:.8px solid rgba(255,255,255,.14)!important;color:#EDE7DF!important}
.ssf-ghost:hover{border-color:#E09900!important;background:rgba(224,153,0,.08)!important}
.ssf-empty{text-align:center;color:#9B9288;font-size:16px;padding:28px;border:.8px dashed rgba(255,255,255,.14);border-radius:12px;max-width:640px;margin:0 auto}
@media(max-width:600px){.ssf{padding:16px 0 40px}.ssf-form{padding:20px 16px}.ssf-row{grid-template-columns:1fr}}
</style>
<script id="ss-free-js">
(function(){var f=document.getElementById('ss-free-form');if(!f||f.dataset.on)return;f.dataset.on=1;
f.addEventListener('submit',function(e){e.preventDefault();var m=f.querySelector('.ssf-msg'),b=f.querySelector('.ssf-btn');var fd=new FormData(f);
if(!fd.get('name')||!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(fd.get('email')||'')||!fd.get('consent')){m.className='ssf-msg bad';m.textContent=<?php echo wp_json_encode( ss_free_t( 'err' ) ); ?>;return}
fd.append('action','ss_free_join');fd.append('n',<?php echo wp_json_encode( wp_create_nonce( 'ss_free_join' ) ); ?>);b.disabled=true;
fetch(<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>,{method:'POST',body:fd,credentials:'same-origin'}).then(function(r){return r.json()}).then(function(j){if(j&&j.success){m.className='ssf-msg ok';m.textContent=j.data.m;setTimeout(function(){location.reload()},700)}else{m.className='ssf-msg bad';m.textContent=(j&&j.data&&j.data.m)||'Error';b.disabled=false}}).catch(function(){m.className='ssf-msg bad';m.textContent='Error';b.disabled=false})});
})();
</script>
<?php
		return ob_get_clean();
	} );

	/* admin: who subscribed */
	add_filter( 'manage_users_columns', function ( $c ) { $c['ss_who'] = 'Free zone'; return $c; } );
	add_filter( 'manage_users_custom_column', function ( $v, $col, $uid ) {
		if ( 'ss_who' !== $col ) return $v;
		$w = get_user_meta( $uid, 'ss_who', true ); $d = get_user_meta( $uid, 'ss_free_optin', true );
		if ( ! $w && ! $d ) return '—';
		$r = ss_free_roles();
		return esc_html( ( isset( $r[ $w ]['en'] ) ? $r[ $w ]['en'] : $w ) . ( $d ? ' · ' . mysql2date( 'd.m.Y', $d ) : '' ) );
	}, 10, 3 );
}

/* ═══ Solid Sound — 17.10.2026: HOME first screen v2 (minimal): title · Try it for free (lead magnets) · New arrivals carousel + Browse products · Services list + All prices. Replaces the old WPBakery hero section (hidden on the front page). Printed inside the page content, so it also works after the player's AJAX page switches. ═══ */
if ( ! function_exists( 'ss_h3_t' ) ) {
	function ss_h3_t( $k ) {
		static $T = array(
			'h1' => array( 'en' => 'Turn Your Ideas into %s', 'ua' => 'Перетворіть свої ідеї на %s', 'de' => 'Mach aus deinen Ideen %s', 'fr' => 'Transforme tes idées en %s', 'pt' => 'Transforme suas ideias em %s' ),
			'try' => array( 'en' => 'Try it for free', 'ua' => 'Спробуй безкоштовно', 'de' => 'Kostenlos testen', 'fr' => 'Essaie gratuitement', 'pt' => 'Experimente grátis' ),
			'fb' => array( 'en' => 'Track feedback', 'ua' => 'Розбір треку', 'de' => 'Track-Feedback', 'fr' => 'Avis sur ton morceau', 'pt' => 'Feedback da faixa' ),
			'tpl' => array( 'en' => 'Ableton templates', 'ua' => 'Шаблони Ableton', 'de' => 'Ableton-Templates', 'fr' => 'Templates Ableton', 'pt' => 'Templates Ableton' ),
			'new' => array( 'en' => 'What’s new', 'ua' => 'Що нового', 'de' => 'Was ist neu', 'fr' => 'Quoi de neuf', 'pt' => 'O que há de novo' ),
			'best' => array( 'en' => 'Best products', 'ua' => 'Найкращі товари', 'de' => 'Beste Produkte', 'fr' => 'Meilleurs produits', 'pt' => 'Melhores produtos' ),
			'svc' => array( 'en' => 'Services', 'ua' => 'Послуги', 'de' => 'Services', 'fr' => 'Services', 'pt' => 'Serviços' ),
			'browse' => array( 'en' => 'Browse products', 'ua' => 'Дивитися товари', 'de' => 'Produkte ansehen', 'fr' => 'Voir les produits', 'pt' => 'Ver produtos' ),
			'prices' => array( 'en' => 'All prices', 'ua' => 'Усі ціни', 'de' => 'Alle Preise', 'fr' => 'Tous les prix', 'pt' => 'Todos os preços' ),
			'from' => array( 'en' => 'from', 'ua' => 'від', 'de' => 'ab', 'fr' => 'dès', 'pt' => 'a partir de' ),
			'fb_s' => array( 'en' => 'Pro notes on your mix', 'ua' => 'Професійні нотатки щодо зведення', 'de' => 'Profi-Notizen zu deinem Mix', 'fr' => 'Conseils pro sur ton mix', 'pt' => 'Notas profissionais sobre sua mix' ),
			'tpl_s' => array( 'en' => 'Download & learn inside', 'ua' => 'Завантаж і вчися зсередини', 'de' => 'Herunterladen & von innen lernen', 'fr' => 'Télécharge et apprends de l’intérieur', 'pt' => 'Baixe e aprenda por dentro' ),
			'prev' => array( 'en' => 'Previous', 'ua' => 'Назад', 'de' => 'Zurück', 'fr' => 'Précédent', 'pt' => 'Anterior' ),
			'next' => array( 'en' => 'Next', 'ua' => 'Далі', 'de' => 'Weiter', 'fr' => 'Suivant', 'pt' => 'Próximo' ),
			'templates' => array( 'en' => 'Template', 'ua' => 'Шаблон', 'de' => 'Template', 'fr' => 'Template', 'pt' => 'Template' ),
			'presets' => array( 'en' => 'Presets', 'ua' => 'Пресети', 'de' => 'Presets', 'fr' => 'Presets', 'pt' => 'Presets' ),
			'music' => array( 'en' => 'Music', 'ua' => 'Музика', 'de' => 'Musik', 'fr' => 'Musique', 'pt' => 'Música' ),
			's_mixing' => array( 'en' => 'Mixing', 'ua' => 'Зведення', 'de' => 'Mixing', 'fr' => 'Mixage', 'pt' => 'Mixagem' ),
			's_mastering' => array( 'en' => 'Mastering', 'ua' => 'Мастеринг', 'de' => 'Mastering', 'fr' => 'Mastering', 'pt' => 'Masterização' ),
			's_remix' => array( 'en' => 'Remix', 'ua' => 'Ремікс', 'de' => 'Remix', 'fr' => 'Remix', 'pt' => 'Remix' ),
			's_remake' => array( 'en' => 'Remake', 'ua' => 'Рімейк', 'de' => 'Remake', 'fr' => 'Remake', 'pt' => 'Remake' ),
			's_ghost' => array( 'en' => 'Ghost producing', 'ua' => 'Гост-продакшн', 'de' => 'Ghost-Production', 'fr' => 'Ghost production', 'pt' => 'Ghost production' ),
			's_coproducing' => array( 'en' => 'Co-producing', 'ua' => 'Ко-продакшн', 'de' => 'Co-Production', 'fr' => 'Coproduction', 'pt' => 'Coprodução' ),
			's_post' => array( 'en' => 'Post production', 'ua' => 'Постпродакшн', 'de' => 'Postproduktion', 'fr' => 'Post-production', 'pt' => 'Pós-produção' ),
		);
		$l = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';
		if ( 'uk' === $l ) $l = 'ua';
		return isset( $T[ $k ][ $l ] ) ? $T[ $k ][ $l ] : ( isset( $T[ $k ]['en'] ) ? $T[ $k ]['en'] : $k );
	}

	/* price for the "Best products" cards: "old struck  new" on sale, plain otherwise — never the screen-reader text
	   ("Original price was: … Current price is: …") that WooCommerce adds for blind users */
	if ( ! function_exists( 'ss_h3_price' ) ) {
		function ss_h3_price( $p ) {
			$h = preg_replace( '#<span class="screen-reader-text">.*?</span>#s', '', (string) $p->get_price_html() );
			$t = function ( $x ) { return esc_html( trim( html_entity_decode( wp_strip_all_tags( $x ), ENT_QUOTES, 'UTF-8' ) ) ); };
			if ( preg_match( '#<del[^>]*>(.*?)</del>#s', $h, $d ) && preg_match( '#<ins[^>]*>(.*?)</ins>#s', $h, $i ) ) return '<del>' . $t( $d[1] ) . '</del> <ins>' . $t( $i[1] ) . '</ins>';
			return $t( $h );
		}
	}
	function ss_h3_render( $mode = 'best' ) {
		$sp = get_permalink( 1947 ); if ( ! $sp ) $sp = home_url( '/services-prices/' );
		$fb = get_permalink( 1709 ); if ( ! $fb ) $fb = home_url( '/free-demo-feedback/' );
		$fp = get_page_by_path( 'free' ); $free = $fp ? get_permalink( $fp ) : home_url( '/free/' );
		$svc = array( array( 'mixing', '60€' ), array( 'mastering', '15€' ), array( 'remix', '150€' ), array( 'remake', '120€' ), array( 'ghost', '300€' ), array( 'coproducing', '40€' ), array( 'post', '40€' ) );
		/* (11.11.2026 owner) "Best products" = order of the quality rating (WooCommerce -> Ranking); newest if no rating yet */
		if ( 'new' === $mode ) $q = new WP_Query( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 14, 'orderby' => 'date', 'order' => 'DESC', 'no_found_rows' => true ) );
		else $q = new WP_Query( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 14, 'meta_key' => '_ss_score', 'orderby' => 'meta_value_num', 'order' => 'DESC', 'no_found_rows' => true ) );
		if ( 'new' !== $mode && $q->post_count < 4 ) $q = new WP_Query( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 14, 'orderby' => 'date', 'order' => 'DESC', 'no_found_rows' => true ) );
		$ssh_rank = 0;
		$arrow = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
		$chev = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>';
		ob_start(); ?>
<section class="l-section ssh" aria-label="Solid Sound"><div class="l-section-h i-cf">
	<div class="ssh-in">
		<h1 class="ssh-h1"><?php printf( esc_html( ss_h3_t( 'h1' ) ), '<span>Solid Sound</span>' ); ?></h1>
		<?php $ssl = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en'; if ( 'uk' === $ssl ) $ssl = 'ua';
		$ssub = array( 'en' => array( 'Ableton templates · Presets · Mixing & Mastering', 'Royalty-free', 'Instant download after payment', 'Secure payment' ), 'ua' => array( 'Шаблони Ableton · Пресети · Зведення й мастеринг', 'Без роялті', 'Миттєве завантаження після оплати', 'Безпечна оплата' ), 'de' => array( 'Ableton-Templates · Presets · Mixing & Mastering', 'Lizenzfrei', 'Sofortiger Download nach Zahlung', 'Sichere Zahlung' ), 'fr' => array( 'Templates Ableton · Presets · Mixage & mastering', 'Libre de droits', 'Téléchargement immédiat après paiement', 'Paiement sécurisé' ), 'pt' => array( 'Templates Ableton · Presets · Mixagem & masterização', 'Livre de royalties', 'Download imediato após o pagamento', 'Pagamento seguro' ) );
		$ssub = isset( $ssub[ $ssl ] ) ? $ssub[ $ssl ] : $ssub['en'];
		$sck = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>'; ?>
		<p class="ssh-sub"><span class="ssh-sub-t"><?php echo esc_html( $ssub[0] ); ?></span><span class="ssh-tr"><?php echo $sck . esc_html( $ssub[1] ); ?></span><span class="ssh-tr"><?php $sp2 = preg_split( '/\s+/u', $ssub[2], 3 ); echo $sck . esc_html( $sp2[0] . ( isset( $sp2[1] ) ? ' ' . $sp2[1] : '' ) ); if ( isset( $sp2[2] ) ) echo '<span class="ssh-tr-x">' . esc_html( $sp2[2] ) . '</span>'; ?></span><span class="ssh-tr"><?php echo $sck . esc_html( $ssub[3] ); ?></span></p>
		<div class="ssh-cols">
			<div class="ssh-col ssh-col-new">
				<h2 class="ssh-h2"><?php echo esc_html( ss_h3_t( 'new' === $mode ? 'new' : 'best' ) ); ?></h2>
				<div class="ssh-car">
					<button type="button" class="ssh-a ssh-a-prev" data-d="-1" aria-label="<?php echo esc_attr( ss_h3_t( 'prev' ) ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg></button>
					<button type="button" class="ssh-a ssh-a-next" data-d="1" aria-label="<?php echo esc_attr( ss_h3_t( 'next' ) ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg></button>
					<div class="ssh-track">
					<?php while ( $q->have_posts() ) : $q->the_post(); $pid = get_the_ID(); $p = wc_get_product( $pid ); if ( ! $p || ! $p->is_visible() || ! has_post_thumbnail( $pid ) || get_post_meta( $pid, '_ss_hide_home', true ) ) continue;
						$img = get_the_post_thumbnail_url( $pid, 'woocommerce_single' );
						$fmts = wp_get_post_terms( $pid, 'product-format', array( 'fields' => 'slugs' ) ); $fmt = '';
						if ( ! is_wp_error( $fmts ) ) foreach ( array( 'templates', 'presets', 'music' ) as $f ) if ( in_array( $f, $fmts, true ) ) { $fmt = $f; break; } ?>
						<?php $smp3 = ''; $stxt = get_post_field( 'post_excerpt', $pid ) . ' ' . get_post_field( 'post_content', $pid ); if ( preg_match( '#https?://[^\s"\'\]]+?\.mp3#i', $stxt, $smm ) ) $smp3 = $smm[0]; ?>
						<a class="ssh-card direct-link post-<?php echo (int) $pid; ?>" href="<?php the_permalink(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" data-pid="<?php echo (int) $pid; ?>"<?php if ( $smp3 ) : ?> data-src="<?php echo esc_url( $smp3 ); ?>" data-t="<?php echo esc_attr( get_the_title() ); ?>" data-i="<?php echo esc_url( get_the_post_thumbnail_url( $pid, 'thumbnail' ) ); ?>"<?php endif; ?>>
							<span class="ssh-cov"><img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" <?php $ssh_i = isset( $ssh_i ) ? $ssh_i + 1 : 0; echo $ssh_i < 2 ? 'loading="eager"' . ( 0 === $ssh_i ? ' fetchpriority="high"' : '' ) : 'loading="lazy"'; ?> decoding="async" width="560" height="560"><?php $ssh_rank++; $ssh_tier = (string) get_post_meta( $pid, '_ss_tier', true ); ?><?php if ( 'new' === $mode ) : ?><?php if ( $ssh_rank <= 3 ) : ?><em class="ssh-rk">New</em><?php endif; ?><?php else : ?><em class="ssh-rk">#<?php echo (int) $ssh_rank; ?></em><?php endif; ?><?php if ( 'best' === $ssh_tier || 'trending' === $ssh_tier ) : ?><em class="ssh-tier ssh-tier-<?php echo esc_attr( $ssh_tier ); ?>"><?php echo 'best' === $ssh_tier ? 'Best seller' : 'Trending'; ?></em><?php endif; ?></span>
							<span class="ssh-meta"><span class="ssh-tt"><?php the_title(); ?></span><?php $price = ss_h3_price( $p ); if ( $price ) : ?><span class="ssh-pr"><?php echo $price; /* escaped in ss_h3_price */ ?></span><?php endif; ?></span>
							<?php if ( $smp3 ) : ?><span class="ssh-wv"><audio class="wp-audio-shortcode" preload="none" src="<?php echo esc_url( $smp3 ); ?>"></audio></span><?php endif; ?>
							<span class="ssh-row"><?php if ( $fmt ) : ?><span class="ssh-tag ssh-tag-<?php echo esc_attr( $fmt ); ?>"><?php echo esc_html( ss_h3_t( $fmt ) ); ?></span><?php endif; ?><span class="ssh-acts"><?php $syt = function_exists( 'ss_yt_map' ) ? ss_yt_map() : array(); if ( ! empty( $syt[ $pid ] ) ) : ?><span class="ssh-yt" role="button" tabindex="0" aria-label="Video" title="Video" data-yt="<?php echo esc_attr( $syt[ $pid ] ); ?>"><i></i></span><?php endif; if ( $smp3 ) : ?><span class="ssh-add" role="button" tabindex="0" aria-label="Add to queue" title="Add to queue"><svg viewBox="0 0 24 24" aria-hidden="true"><path class="ssh-i-plus" d="M12 6v12M6 12h12"/><path class="ssh-i-ok" d="M6 12.5l4 4L18 8"/></svg></span><?php endif; ?><?php if ( $smp3 ) : ?><span class="ssh-play" role="button" tabindex="0" aria-label="Play"><svg class="ssh-i-pl" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg><svg class="ssh-i-pa" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 5h4v14H7zM13 5h4v14h-4z"/></svg></span><?php endif; ?></span></span>
						</a>
					<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</div>
				<a class="ssh-btn" href="#templates"><?php echo esc_html( ss_h3_t( 'browse' ) ); ?><?php echo $arrow; ?></a>
			</div>
		</div>
	</div>
</div></section>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sora:wght@700;800&display=swap" media="print" onload="this.media='all'">
<script>(function(){if(window.__sshPlay)return;window.__sshPlay=1;
function tr(a){return{pid:a.getAttribute('data-pid'),src:a.getAttribute('data-src'),t:a.getAttribute('data-t'),a:'Solid Sound',i:a.getAttribute('data-i'),u:a.href}}
function act(e){var y=e.target.closest&&e.target.closest('.ssh-yt,.ssh-add');if(!y)return false;e.preventDefault();e.stopPropagation();var a=y.closest('.ssh-card');if(y.classList.contains('ssh-yt')){if(window.ssYtOpen)ssYtOpen(y.getAttribute('data-yt'),a.getAttribute('data-t')||a.title);else location.href='https://www.youtube.com/watch?v='+y.getAttribute('data-yt');return true}
var src=a.getAttribute('data-src'),ok=false;var w=[].slice.call(document.querySelectorAll('.ssw[data-src]'));for(var i=0;i<w.length;i++){if(w[i].getAttribute('data-src')===src){var ab=w[i].querySelector('.ssw-add');if(ab){ab.click();ok=true;break}}}
if(!ok&&window.SSP){var st=SSP.state;if(st&&st.cur&&st.queue){st.queue.push(tr(a))}else{try{SSP.play(tr(a),[tr(a)],null)}catch(x){}}}
y.classList.add('is-ok');setTimeout(function(){y.classList.remove('is-ok')},1400);return true}
function go(e){
if(e.type==='click'){var cd=e.target.closest&&e.target.closest('.ssh-card');
 if(cd){if(e.target.closest('.ssw')){e.preventDefault();return}
  if(!e.target.closest('.ssh-yt,.ssh-add,.ssh-play')){if(e.metaKey||e.ctrlKey||e.shiftKey||e.button)return;e.preventDefault();e.stopPropagation();var h=cd.href;if(!(window.sspGo&&window.sspGo(h)))location.href=h;return}}}
if(act(e))return;var b=e.target.closest&&e.target.closest('.ssh-play');if(!b)return;e.preventDefault();e.stopPropagation();var a=b.closest('.ssh-card');if(!a||!window.SSP)return;var all=[].slice.call(document.querySelectorAll('.ssh-card[data-src]'));var q=all.map(tr);var t=tr(a);var k=all.indexOf(a);try{SSP.play(q[k]||t,q,null)}catch(x){}setTimeout(upd,120)}
document.addEventListener('click',go,true);
/* the theme treats a link with a picture as an image for its lightbox (magnificPopup) — not for product cards */
function unlb(){[].forEach.call(document.querySelectorAll('.ssh-card[ref],.ssh-card.inited'),function(a){if(window.jQuery)jQuery(a).off('click.magnificPopup');a.removeAttribute('ref')})}
unlb();window.addEventListener('load',function(){unlb();setTimeout(unlb,1500)});if(window.ssOnDom)ssOnDom(unlb);
document.addEventListener('keydown',function(e){if((e.key==='Enter'||e.key===' ')&&e.target.classList&&(e.target.classList.contains('ssh-play')||e.target.classList.contains('ssh-add')||e.target.classList.contains('ssh-yt')))go(e)},true);
function playing(){var s=window.SSP&&SSP.state&&SSP.state.cur;var b=document.querySelector('.ssp.is-playing');return s&&b?String(s.src):''}
function upd(){var p=playing();var cs=document.querySelectorAll('.ssh-card[data-src]');for(var i=0;i<cs.length;i++){cs[i].classList.toggle('is-pl',!!p&&cs[i].getAttribute('data-src')===p)}}
window.ssOnDom?ssOnDom(upd):setInterval(upd,600);document.addEventListener('ssp:state',upd);
})();</script>
<style id="ssh-css">
.l-main .l-section:has(.type_carousel):has(a[href="#templates"]):not(.ssh){display:none!important}
.ssh{background:linear-gradient(rgba(0,0,0,.8),rgba(0,0,0,.8)),url(/wp-content/uploads/solid_bg_optimized.jpg) 50% 50%/cover no-repeat fixed #0b0a09;padding-bottom:22px!important;color:#EDE7DF;font-family:Jura,sans-serif}
.ssh>.l-section-h{padding-bottom:0!important}
.ssh-in{max-width:1240px;margin:0 auto;padding-top:2px}
.ssh-h1{font-family:Roboto,sans-serif!important;font-size:56px!important;line-height:1.05!important;font-weight:700!important;letter-spacing:-1.3px!important;color:#F4EFE8!important;margin:0 0 12px!important;text-align:center}
.ssh-h1 span{color:#E09900}
.ssh-cols{display:grid;grid-template-columns:260px minmax(0,480px) 380px;justify-content:center;column-gap:36px;align-items:stretch}
.ssh-col{display:flex;flex-direction:column;gap:8px;min-width:0}
.ssh-h2{font-family:Roboto,sans-serif!important;font-size:22px!important;line-height:30px!important;height:30px;font-weight:700!important;letter-spacing:-.3px!important;color:#E09900!important;margin:0!important;text-align:center}
.ssh-lead{position:relative;flex:1 1 0;display:flex;flex-direction:column;justify-content:center;gap:6px;padding:16px 18px;border-radius:8px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.03);color:#F4EFE8!important;text-decoration:none!important;transition:border-color .2s,background .2s}
.ssh-lead:hover{border-color:#E09900;background:rgba(224,153,0,.08)}
.ssh-lead b{align-self:flex-start;font-size:11px;letter-spacing:1.4px;background:#E09900;color:#111;border-radius:6px;padding:5px 7px;font-weight:700}
.ssh-lt{font-size:18px;font-weight:700;line-height:1.2}
.ssh-ls{font-size:14px;font-weight:600;color:#9B9288;line-height:1.35}
.ssh-lead i{position:absolute;right:14px;bottom:14px;width:30px;height:30px;border-radius:50%;border:1px solid rgba(224,153,0,.5);color:#E09900;display:flex;align-items:center;justify-content:center;transition:background .2s,color .2s}
.ssh-lead:hover i{background:#E09900;color:#111}
.ssh svg{width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2.4;stroke-linecap:round;stroke-linejoin:round}
.ssh-car{position:relative;min-width:0}
.ssh-track{display:flex;gap:16px;overflow-x:auto;scroll-snap-type:x mandatory;scroll-behavior:smooth;scrollbar-width:none;-webkit-overflow-scrolling:touch}
.ssh-track::-webkit-scrollbar{display:none}
.ssh-card{flex:0 0 calc(50% - 8px);scroll-snap-align:start;display:flex;flex-direction:column;gap:6px;text-decoration:none!important;color:#F4EFE8!important;min-width:0}
.ssh-cov{position:relative;display:block;aspect-ratio:1/1;overflow:hidden;background:#1a1816}
.ssh-cov img{width:100%;height:100%;object-fit:cover;display:block}
.ssh-meta{display:flex;align-items:baseline;justify-content:space-between;gap:10px;margin-top:4px;min-width:0}.ssh-tt{font-size:17px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.ssh-pr{font-size:15px;font-weight:700;color:#E09900;flex:none}
.ssh-tag{align-self:flex-start;font-size:11.5px;font-weight:700;letter-spacing:1.4px;text-transform:uppercase;border-radius:6px;padding:5px 8px;color:#E09900;background:rgba(224,153,0,.12)}
.ssh-tag-presets{color:#C9A7FF;background:rgba(160,110,255,.14)}
.ssh-tag-music{color:#8FD3C7;background:rgba(80,190,170,.14)}
.ssh-a{position:absolute;top:0;z-index:3;height:0;padding:0;border:0;background:none;color:#F4EFE8;cursor:pointer;width:34px;aspect-ratio:auto;display:flex;align-items:center;justify-content:center}
.ssh-a{top:calc((100% - 70px)/2 - 20px);height:40px;filter:drop-shadow(0 2px 6px rgba(0,0,0,.8))}
.ssh-a svg{width:26px;height:26px;stroke-width:2.6}
.ssh-a:hover{color:#E09900}
.ssh-a-prev{left:2px}.ssh-a-next{right:2px}
.ssh-btn{height:44px;box-sizing:border-box;border-radius:8px;display:flex;align-items:center;justify-content:center;gap:10px;background:#E09900;color:#111!important;font:700 16px/1 Jura,sans-serif;text-decoration:none!important;transition:background .2s;margin-top:4px}
.ssh-btn:hover{background:#f2a800}
.ssh-svc{list-style:none!important;margin:0!important;padding:0!important;display:flex;flex-direction:column;gap:6px;flex:1}
.ssh-svc li{flex:1 1 0;display:flex;margin:0!important;padding:0!important;min-height:38px}
.ssh-svc li:before{display:none!important}
.ssh-svc a{flex:1;display:flex;align-items:center;justify-content:space-between;box-sizing:border-box;padding:0 14px;border-radius:8px;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.025);color:#F4EFE8!important;text-decoration:none!important;transition:border-color .2s,background .2s}
.ssh-svc a:hover{border-color:rgba(224,153,0,.6);background:rgba(224,153,0,.06)}
.ssh-sn{font-size:16px;font-weight:700}
.ssh-sp{display:flex;align-items:center;gap:10px;font-size:14px;font-weight:600;color:#9B9288}
.ssh-sp svg{color:#6F675E;transition:transform .2s,color .2s}
.ssh-svc a:hover .ssh-sp svg{transform:translateX(3px);color:#E09900}
.ssh-svc-all a{border-color:#E09900}
.ssh-svc-all .ssh-sp svg{color:#E09900}
@media(max-width:1180px){.ssh-cols{grid-template-columns:220px minmax(0,1fr) 320px;column-gap:24px}}
@media(min-width:700px) and (max-width:1180px){
.ssh-cols{grid-template-columns:minmax(0,.8fr) minmax(0,1.5fr) minmax(0,1.25fr)!important;column-gap:14px!important;row-gap:14px}
.ssh-h1{font-size:44px!important}
.ssh-h2{font-size:20px!important}
.ssh-lead{padding:12px 12px 14px;min-height:0}
.ssh-lt{font-size:15px}.ssh-ls{font-size:12px}
.ssh-lead i{display:none}
.ssh-tt{font-size:14px}
.ssh-svc a{padding:9px 10px;font-size:13px;gap:6px}.ssh-sn{white-space:nowrap;overflow:hidden;text-overflow:ellipsis;min-width:0}.ssh-sp{white-space:nowrap;font-size:11.5px;flex:none}.ssh-sp svg{display:none}.ssh-meta{flex-direction:column;align-items:flex-start!important;gap:2px}.ssh-tt{white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%}
}
@media(max-width:699px){
.ssh-in{padding-top:14px}
.ssh-h1{font-size:34px!important;letter-spacing:-.8px!important;margin-bottom:16px!important}
.ssh-cols{grid-template-columns:minmax(0,1fr);row-gap:22px}
.ssh-h2{font-size:20px!important}
.ssh-col-free{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.ssh-col-free .ssh-h2{grid-column:1/-1}
.ssh-lead{padding:12px;min-height:108px}
.ssh-lt{font-size:16px}.ssh-ls{font-size:13px}
.ssh-lead i{display:none}
.ssh-tt{font-size:16px}
.ssh-col-free{order:1}.ssh-col-new{order:2}.ssh-col-svc{order:3}
.ssh-lead{min-height:0;padding:12px 12px 14px}
.ssh-svc{display:grid!important;grid-template-columns:1fr 1fr;gap:6px!important}
.ssh-svc li{min-height:0}
.ssh-svc a{flex-direction:column;align-items:flex-start;justify-content:center;gap:2px;padding:9px 12px;min-height:54px}
.ssh-svc .ssh-sp svg{display:none}
.ssh-sn{font-size:15px}.ssh-sp{font-size:13px}
.ssh-svc-all a{flex-direction:row;align-items:center;justify-content:space-between}
.ssh-svc-all .ssh-sp svg{display:block}
}
/*ssh-solid*/.l-section.ssh{background:#080808!important;padding-bottom:30px!important}
/*ssh-sora*/.ssh .ssh-h1{font-family:Sora,Roboto,sans-serif!important;font-weight:700!important;letter-spacing:-1px!important}@media(max-width:699px){.ssh .ssh-h1{font-size:28px!important;letter-spacing:-.6px!important}}
/*ssh-gap*/.ssh .ssh-in{padding-top:22px!important}@media(max-width:699px){.ssh .ssh-in{padding-top:16px!important}}
/*ssh-pal*/.ssh .ssh-lead,.ssh .ssh-svc a{background:#121212!important;border:1px solid #272727!important}
.ssh .ssh-lead:hover,.ssh .ssh-svc a:hover{border-color:#E09900!important;background:#161410!important}
.ssh .ssh-svc-all a{background:#121212!important;border:1px solid #2f2f2f!important}
.ssh .ssh-svc-all a:hover{border-color:#E09900!important}
.ssh .ssh-a{background:rgba(8,8,8,.75)!important;border:1px solid #2f2f2f!important}
/*ssh-plus*/.ssh .ssh-h1{margin-bottom:6px!important}
.ssh-sub{display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:6px 18px;margin:0 0 14px!important;padding:0;font:500 15px/1.35 Jura,sans-serif;color:#a8a29b;text-align:center}
.ssh-sub-t{color:#cfc9c2}
.ssh-tr{display:inline-flex;align-items:center;gap:5px;color:#a8a29b}
.ssh-tr svg{width:15px;height:15px;fill:none;stroke:#E09900;stroke-width:2.6;stroke-linecap:round;stroke-linejoin:round}
.ssh-cov{position:relative}
.ssh-row{display:flex;align-items:center;justify-content:space-between;gap:8px;width:100%}.ssh-row .ssh-tag{margin-top:0!important}.ssh-play{position:relative;flex:none;margin-left:auto;width:38px;height:38px;border-radius:50%;background:#E09900;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 4px 14px rgba(0,0,0,.45);transition:transform .15s,background .15s;z-index:3}
.ssh-play:hover{transform:scale(1.08);background:#f2a800}
.ssh-play svg{width:20px;height:20px;fill:#111}
.ssh-play .ssh-i-pa,.ssh-card.is-pl .ssh-play .ssh-i-pl{display:none}
.ssh-card.is-pl .ssh-play .ssh-i-pa{display:block}
.ssh .ssh-in{padding-top:16px!important}
@media(max-width:699px){.ssh-sub{font-size:11.5px;gap:2px 9px;margin-bottom:10px!important}.ssh-tr{gap:3px}.ssh-sub-t{flex-basis:100%}.ssh-tr svg{width:13px;height:13px}.ssh-play{width:26px;height:26px}.ssh-row{margin-top:4px}.ssh-play svg{width:14px;height:14px}.ssh .ssh-in{padding-top:12px!important}}
@media(min-width:700px) and (max-width:1180px){.ssh-sub{font-size:13.5px}.ssh-play{width:34px;height:34px}}
/*ssh-acts*/.ssh-acts{display:flex;align-items:center;gap:6px;margin-left:auto;flex:none}
.ssh-acts .ssh-play{margin-left:0}
.ssh-yt,.ssh-add{width:32px;height:32px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;flex:none;transition:background .2s,border-color .2s}
.ssh-yt{border:1px solid rgba(255,60,60,.6)}.ssh-yt:hover{background:rgba(255,0,0,.14);border-color:#f33}
.ssh-yt i{display:block;width:15px;height:10px;border-radius:3px;background:#f00;position:relative}
.ssh-yt i:after{content:"";position:absolute;left:6px;top:3px;border-left:4px solid #fff;border-top:2.5px solid transparent;border-bottom:2.5px solid transparent}
.ssh-add{border:1px solid #3a3a3a;color:#cfc9c2}.ssh-add:hover{border-color:#E09900;color:#E09900}
.ssh-add svg{width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2.4;stroke-linecap:round;stroke-linejoin:round}
.ssh-add .ssh-i-ok,.ssh-add.is-ok .ssh-i-plus{display:none}.ssh-add.is-ok .ssh-i-ok{display:block}.ssh-add.is-ok{border-color:#3fd786;color:#3fd786}
@media(max-width:1180px){.ssh-row{flex-wrap:wrap;row-gap:6px}.ssh-acts{width:100%;justify-content:flex-start;margin-left:0}.ssh-acts .ssh-play{margin-left:auto}}
@media(min-width:700px) and (max-width:1180px){.ssh-yt,.ssh-add{width:30px;height:30px}}
@media(max-width:699px){.ssh-yt,.ssh-add{width:26px;height:26px}.ssh-yt i{width:12px;height:8px}.ssh-yt i:after{left:5px;top:2.5px;border-left-width:3.5px;border-top-width:1.5px;border-bottom-width:1.5px}.ssh-add svg{width:12px;height:12px}.ssh-row{row-gap:5px}}
/*ssh-mob2*/@media(max-width:699px){
.ssh .ssh-h1{font-size:26px!important}
.ssh-sub{font-size:11.5px!important;gap:2px 9px!important}.ssh-tr-x{display:none}
.ssh-sub-t{flex-basis:100%}
.ssh-tr{gap:2px!important;white-space:nowrap}
.ssh-tr svg{width:11px!important;height:11px!important}
.ssh .ssh-lead{padding:7px 9px!important;gap:6px!important}
.ssh .ssh-lead b{font-size:8px!important;padding:2px 5px!important}
.ssh .ssh-lt{font-size:13px!important}
.ssh-row{margin-top:-22px!important}
.ssh .ssh-svc a{flex-direction:row!important;align-items:center!important;justify-content:space-between!important;gap:4px!important;padding:9px 8px 9px 10px!important;min-height:0!important}
.ssh .ssh-svc{grid-template-columns:minmax(0,1fr) minmax(0,1fr)!important}.ssh .ssh-svc li{min-width:0}
.ssh .ssh-sn{font-size:12.5px!important;line-height:15px!important;white-space:normal;min-width:0}
.ssh .ssh-sp{font-size:10.5px!important;line-height:16px!important;white-space:nowrap;flex:none}
.ssh .ssh-sp svg{display:none!important}
.ssh .ssh-svc a{padding:9px 7px 9px 9px!important}.ssh .ssh-from{font-size:9px;opacity:.75}
.ssh .ssh-col-free .ssh-h2{display:none!important}
.ssh-row .ssh-tag{margin-left:auto!important;font-size:10px!important;padding:3px 6px!important;line-height:13px!important}
}
.ssh-tr-x{margin-left:.3em}
@media(max-width:374px){.ssh-sub{font-size:10.5px!important;gap:2px 7px!important}}
.ssh .ssh-lead b{font-family:Sora,Roboto,sans-serif!important;font-weight:800!important;color:#000!important;background:#E09900!important;letter-spacing:1px!important}
/*ssh-3col*/@media(min-width:1181px){
.ssh-in{max-width:1420px!important}
.ssh-cols{grid-template-columns:minmax(0,1fr) minmax(0,2.25fr) minmax(0,1fr)!important;column-gap:40px!important;justify-content:center}
.ssh-track{gap:14px!important}
.ssh-card{flex:0 0 calc((100% - 28px)/3)!important;width:calc((100% - 28px)/3)!important;min-width:0!important}
.ssh-meta{gap:8px!important}
.ssh-tt{min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.ssh-pr{flex:none}
}
/*ssh-fit*/@media(max-width:699px){
.ssh-in{padding-top:0!important}
.ssh-h1{font-family:Roboto,sans-serif!important;font-size:31px!important;line-height:1.1!important;letter-spacing:-.6px!important;margin-bottom:12px!important}.ssh-h1 span{display:block}
.ssh-cols{row-gap:12px!important}
.ssh-h2{font-size:16px!important;line-height:22px!important;height:22px!important;margin-bottom:6px!important}
.ssh-col-free{gap:8px!important}
.ssh-lead{min-height:0!important;padding:8px 10px!important;gap:4px!important}
.ssh-lead b{font-size:9px!important;padding:3px 6px!important;letter-spacing:1px!important}
.ssh-lt{font-size:14px!important;line-height:1.2!important}.ssh-ls{display:none!important}
.ssh-track{gap:8px!important}
.ssh-card{flex:0 0 calc((100% - 16px)/3)!important;width:calc((100% - 16px)/3)!important;min-width:0!important}
.ssh-meta{flex-direction:column!important;align-items:flex-start!important;gap:0!important;margin-top:4px!important}
.ssh-tt{font-size:12px!important;line-height:16px!important;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%}
.ssh-pr{font-size:12px!important;line-height:16px!important}
.ssh-tag{font-size:10px!important;letter-spacing:1px!important;padding:3px 6px!important;margin-top:4px!important;line-height:12px!important}
.ssh-a{width:26px!important;height:30px!important}
.ssh-btn{height:38px!important;min-height:0!important;margin-top:8px!important;font-size:14px!important}
.ssh-svc{gap:5px!important}
.ssh-svc a{min-height:0!important;padding:5px 10px!important;gap:0!important}
.ssh-sn{font-size:13px!important;line-height:17px!important}.ssh-sp{font-size:11px!important;line-height:15px!important}
.ssh-svc-all{min-height:0!important}
.ssh-lead{flex-direction:row!important;align-items:center!important;justify-content:flex-start!important;gap:8px!important;padding:9px 10px!important}
.ssh-lead b{line-height:12px!important;flex:none;align-self:center!important}
.ssh-cols{row-gap:10px!important}
.ssh-svc a{padding:4px 10px!important}
}

/*ssh-best 11.11.2026: one wide "Best products" carousel*/
/*12.11.2026 speed: these were applied by the menu script after the first paint and made the page jump*/
.ssh br{display:none!important}
/*15.11.2026 owner: 3 products per view, home-style cards; title + carousel + Browse button fit one screen (card size follows the screen height)*/
@media(min-width:700px){.ssh .ssh-col-new{max-width:min(1100px,max(700px,calc((100vh - 600px) * 3 + 100px)))!important;margin:0 auto!important}
.ssh .ssh-h1{margin-bottom:4px!important}.ssh .ssh-sub{margin-bottom:10px!important}.ssh .ssh-h2{margin:4px 0 10px!important}
.ssh .ssh-card{gap:7px!important;padding:9px 9px 11px!important}
}
.ssh .ssh-row{display:flex!important;flex-wrap:nowrap!important;align-items:center!important;justify-content:space-between;gap:0;min-height:30px}
.ssh .ssh-row .ssh-acts{margin-left:auto}
.ssh .ssh-tag{font-size:10.5px!important;padding:6px 9px!important;letter-spacing:.12em!important;flex:none}
@media(max-width:699px){.ssh .ssh-card{flex:0 0 80%!important}}
.ssh a.ssh-btn{display:flex!important;width:max-content!important;margin:14px auto 0!important;align-self:center!important}
/* YouTube button = the YouTube logo (red rounded rectangle, white triangle), everywhere */
.ssh .ssh-yt,.w-grid .ssw .ss-yt-badge,.ss-yt-badge{width:34px!important;height:24px!important;border:0!important;border-radius:0!important;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 28 20'%3E%3Cpath fill='%23FF0000' d='M27.4 3.1A3.5 3.5 0 0 0 25 .6C22.8 0 14 0 14 0S5.2 0 3 .6A3.5 3.5 0 0 0 .6 3.1C0 5.3 0 10 0 10s0 4.7.6 6.9A3.5 3.5 0 0 0 3 19.4C5.2 20 14 20 14 20s8.8 0 11-.6a3.5 3.5 0 0 0 2.4-2.5c.6-2.2.6-6.9.6-6.9s0-4.7-.6-6.9z'/%3E%3Cpath fill='%23fff' d='M11.2 14.3 18.5 10l-7.3-4.3z'/%3E%3C/svg%3E") center/contain no-repeat!important;box-shadow:none!important;transition:transform .15s,filter .15s!important}
.ssh .ssh-yt:hover,.w-grid .ssw .ss-yt-badge:hover,.ss-yt-badge:hover{transform:scale(1.08);filter:brightness(1.1);background-color:transparent!important}
.ssh .ssh-yt i,.w-grid .ssw .ss-yt-badge svg,.w-grid .ssw .ss-yt-badge i,.ss-yt-badge i,.ss-yt-badge svg{display:none!important}
.ss-yt-pd i{width:56px!important;height:40px!important;border-radius:0!important;box-shadow:none!important;filter:drop-shadow(0 2px 6px rgba(0,0,0,.5));background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 28 20'%3E%3Cpath fill='%23FF0000' d='M27.4 3.1A3.5 3.5 0 0 0 25 .6C22.8 0 14 0 14 0S5.2 0 3 .6A3.5 3.5 0 0 0 .6 3.1C0 5.3 0 10 0 10s0 4.7.6 6.9A3.5 3.5 0 0 0 3 19.4C5.2 20 14 20 14 20s8.8 0 11-.6a3.5 3.5 0 0 0 2.4-2.5c.6-2.2.6-6.9.6-6.9s0-4.7-.6-6.9z'/%3E%3Cpath fill='%23fff' d='M11.2 14.3 18.5 10l-7.3-4.3z'/%3E%3C/svg%3E") center/contain no-repeat!important}
.ss-yt-pd i:after{display:none!important}
/*15.11.2026 owner: Best products sidebar back next to the catalog (desktop), What's new carousel on top; toolbar like the old one, no search*/@media(min-width:1025px){.l-main .g-cols>.vc_col-sm-3:has(.ss-top){display:block!important;flex:0 0 24%!important;max-width:24%!important;width:24%!important}.l-main .g-cols>.vc_col-sm-3:has(.ss-top)~.vc_col-sm-9{flex:0 0 76%!important;max-width:76%!important;width:76%!important}}.sstb .sstb-lead .sstb-play{background:#e09900!important;color:#111!important;border:0!important;height:38px!important;padding:0 16px!important;border-radius:8px!important}.sstb .sstb-lead .sstb-play:hover{background:#f5ad12!important}.sstb .sstb-lead .sstb-play svg{fill:#111!important;stroke:none!important}/*toolbar 2 rows: Play All + product types + sort / filters (owner 15.11)*/@media(min-width:768px){.sstb .sstb-filters,.sstb form.w-filter,.sstb .ssf-bar.state_desktop .w-filter-list{display:contents!important}.sstb .sstb-lead{order:1!important}.sstb .ssf-bar.state_desktop .ssf-fmt{order:2!important;flex:0 1 auto!important;display:flex!important;margin:0!important;padding:0!important;border:0!important;min-width:0}.sstb>.w-order{order:3!important;margin-left:auto!important;flex:0 0 160px!important;width:160px!important}.sstb::after{content:'';order:4;flex:0 0 100%;height:0}.sstb .ssf-bar.state_desktop .w-filter-item:not(.ssf-fmt){order:5!important}.sstb .sstb-active{order:6!important}.sstb{row-gap:10px!important;column-gap:8px!important}.sstb .ssf-fmt .w-filter-item-values{flex-wrap:wrap!important;gap:6px!important}}.sstb .ssf-fmt .w-form-checkbox{display:none!important}.sstb .ssf-fmt .w-filter-item-value label::before,.sstb .ssf-fmt .w-filter-item-value label::after{display:none!important;content:none!important}.sstb .ssf-fmt .w-filter-item-value label{gap:0!important;height:38px!important;padding:0 14px!important;border-radius:8px!important;background:rgba(255,255,255,.08)!important;border:1px solid rgba(255,255,255,.16)!important;color:#f1f1f1!important;font:600 14px/1 Roboto,sans-serif!important;letter-spacing:.01em}.sstb .ssf-fmt .w-filter-item-value label:hover{border-color:#e09900!important;color:#fff!important}.sstb .ssf-fmt .w-filter-item-value:has(input:checked) label{background:#e09900!important;border-color:#e09900!important;color:#111!important}.sstb .ssf-fmt .w-filter-item-value-label{color:inherit!important}.sstb .ssf-fmt .w-filter-item-value-label{margin:0!important;padding:0!important}.sstb .ssf-fmt .w-filter-item-value input{position:absolute!important}
.ssh .ssh-cols{display:block!important}
.ssh .ssh-col-new{max-width:1240px;margin:0 auto;gap:14px}
.ssh .ssh-h2{font-size:28px!important;line-height:36px!important;height:auto;color:#F4EFE8!important}
.ssh .ssh-car{padding:4px 2px}
.ssh .ssh-track{gap:18px!important;padding:6px 4px 10px}
.ssh .ssh-card{flex:0 0 calc(33.333% - 12px)!important;width:auto!important;padding:10px 10px 12px;border-radius:16px;background:linear-gradient(180deg,rgba(255,255,255,.06),rgba(255,255,255,.02));border:1px solid rgba(255,255,255,.08);transition:transform .25s ease,border-color .25s,box-shadow .25s}
.ssh .ssh-card:hover{transform:translateY(-4px);border-color:rgba(224,153,0,.55);box-shadow:0 14px 34px rgba(0,0,0,.55),0 0 0 1px rgba(224,153,0,.25),0 0 28px rgba(224,153,0,.18)}
.ssh .ssh-cov{border-radius:12px}
.ssh .ssh-cov img{transition:transform .5s ease}.ssh .ssh-card:hover .ssh-cov img{transform:scale(1.05)}
.ssh .ssh-rk{position:absolute;left:8px;top:8px;z-index:1;min-width:30px;height:26px;padding:0 7px;border-radius:8px;background:rgba(0,0,0,.72);color:#E09900;font:800 13px/26px Roboto,sans-serif;font-style:normal;text-align:center;backdrop-filter:blur(4px)}
.ssh .ssh-tier{position:absolute;right:8px;top:8px;z-index:1;padding:4px 8px;border-radius:999px;font:800 10.5px/1.2 Roboto,sans-serif;font-style:normal;letter-spacing:.04em;text-transform:uppercase}
.ssh .ssh-tier-best{background:#E09900;color:#111}.ssh .ssh-tier-trending{background:#34D399;color:#04261a}
.ssh .ssh-a{width:46px;height:46px;border-radius:50%!important;background:rgba(20,20,20,.85)!important;border:1px solid rgba(255,255,255,.18)!important;z-index:3;box-shadow:0 6px 18px rgba(0,0,0,.5)}
.ssh .ssh-a:hover{border-color:#E09900!important}
.ssh .ssh-btn{align-self:center;width:auto!important;display:inline-flex!important;padding:0 28px!important;border-radius:6px!important;margin-top:6px!important}
.ssh .ssh-card{justify-content:flex-start!important;gap:8px!important;height:auto!important}.ssh .ssh-track{align-items:flex-start!important}
.ssh .ssh-cov{margin:0!important}.ssh .ssh-meta{margin-top:2px!important}.ssh .ssh-row{margin-top:2px!important}
@media(max-width:1180px){.ssh .ssh-card{flex:0 0 calc(33.333% - 12px)!important}}
@media(max-width:699px){.ssh .ssh-card{flex:0 0 62%!important}.ssh .ssh-h2{font-size:22px!important}.ssh .ssh-a{display:none}}
/* Best products: sale price, waveform player like the product grid (owner 13.11.2026) */
.ssh .ssh-pr del{opacity:.55;font-weight:500;margin-right:3px;color:inherit;position:relative;text-decoration:line-through}.ssh .ssh-pr del::before,.ssh .ssh-pr del::after{display:none!important}.ssh .ssh-pr ins{text-decoration:none}
.ssh .ssh-card>br{display:none}
.ssh .ssh-wv{display:block;width:100%;margin:2px 0 0}.ssh .ssh-wv .ssw{margin:0!important;width:100%}
.ssh .ssh-card:has(.ssh-wv .ssw) .ssh-play,.ssh .ssh-card:has(.ssh-wv .ssw) .ssh-add{display:none!important}
</style>
<script id="ssh-js">
(function(){var root=document.querySelector('.ssh');if(!root||root.dataset.on)return;root.dataset.on=1;
if(!(window.CSS&&CSS.supports&&CSS.supports('selector(:has(a))'))){[].forEach.call(document.querySelectorAll('.l-main .l-section'),function(x){if(x!==root&&x.querySelector('.type_carousel')&&x.querySelector('a[href="#templates"]'))x.style.display='none'})}
var tr=root.querySelector('.ssh-track');if(!tr)return;
function step(){var k=tr.querySelectorAll('.ssh-card');if(k.length>1)return k[1].offsetLeft-k[0].offsetLeft;return k[0]?k[0].offsetWidth:300}
function go(d){var s=step(),max=tr.scrollWidth-tr.clientWidth,i=Math.round(tr.scrollLeft/s)+d,x=i*s;if(d>0&&tr.scrollLeft>=max-2)x=0;else if(d<0&&tr.scrollLeft<=2)x=max;tr.scrollTo({left:Math.max(0,Math.min(max,x)),behavior:'smooth'})}
[].forEach.call(root.querySelectorAll('.ssh-a'),function(b){b.addEventListener('click',function(e){e.preventDefault();go(+b.getAttribute('data-d'));hold=Date.now()})});
var hold=0,over=false,car=root.querySelector('.ssh-car');car.addEventListener('mouseenter',function(){over=true});car.addEventListener('mouseleave',function(){over=false});tr.addEventListener('touchstart',function(){hold=Date.now()},{passive:true});
if(!(window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches)){setInterval(function(){if(!document.body.contains(root)||over||document.hidden||Date.now()-hold<6000)return;var r=root.getBoundingClientRect();if(r.bottom<0||r.top>innerHeight)return;go(1)},4000)}
})();
</script>
<?php
		return ob_get_clean();
	}

	add_filter( 'the_content', function ( $content ) {
		static $done = false;
		if ( $done || is_admin() || ! is_front_page() || ! in_the_loop() || ! is_main_query() ) return $content;
		$done = true;
		return ss_h3_render() . $content;
	}, 98 );
}

/* ═══ Solid Sound — 17.10.2026: FREE — menu item (one-time) + free product pages: no cart, a "Get it free" button to /free/ ═══ */
add_action( 'init', function () {
	if ( get_option( 'ss_free_menu_v1' ) || ! function_exists( 'wp_get_nav_menus' ) ) return;
	$fp = get_page_by_path( 'free' ); if ( ! $fp ) return;
	foreach ( wp_get_nav_menus() as $m ) {
		$items = wp_get_nav_menu_items( $m->term_id ); if ( ! $items ) continue;
		$has_products = false; $has_free = false; $pos = 0; $contacts_pos = 0;
		foreach ( $items as $it ) {
			if ( (int) $it->menu_item_parent ) continue;
			$t = strtoupper( trim( wp_strip_all_tags( $it->title ) ) );
			if ( 'PRODUCTS' === $t ) $has_products = true;
			if ( 'FREE' === $t ) $has_free = true;
			if ( 'CONTACTS' === $t || 'FORUM' === $t ) { if ( ! $contacts_pos ) $contacts_pos = (int) $it->menu_order; }
			$pos = max( $pos, (int) $it->menu_order );
		}
		if ( ! $has_products || $has_free ) continue;
		wp_update_nav_menu_item( $m->term_id, 0, array(
			'menu-item-title' => 'FREE',
			'menu-item-object' => 'page',
			'menu-item-object-id' => $fp->ID,
			'menu-item-type' => 'post_type',
			'menu-item-status' => 'publish',
			'menu-item-position' => $contacts_pos ? $contacts_pos : $pos + 1,
		) );
	}
	update_option( 'ss_free_menu_v1', 1 );
}, 40 );

add_filter( 'woocommerce_is_purchasable', function ( $ok, $product ) {
	if ( $product && has_term( 'free', 'product_cat', $product->get_id() ) ) return false;
	return $ok;
}, 20, 2 );

add_action( 'woocommerce_single_product_summary', function () {
	global $product;
	if ( ! $product || ! has_term( 'free', 'product_cat', $product->get_id() ) ) return;
	$fp = get_page_by_path( 'free' ); $u = $fp ? get_permalink( $fp ) : home_url( '/free/' );
	$L = array( 'en' => 'Get it free', 'ua' => 'Отримати безкоштовно', 'de' => 'Kostenlos holen', 'fr' => 'L’obtenir gratuitement', 'pt' => 'Pegar grátis' );
	$l = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en'; if ( 'uk' === $l ) $l = 'ua';
	echo '<a class="ss-getfree" href="' . esc_url( $u ) . '">' . esc_html( isset( $L[ $l ] ) ? $L[ $l ] : $L['en'] ) . '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>';
	echo '<style>.ss-getfree{display:flex;align-items:center;justify-content:center;gap:10px;height:54px;border-radius:999px;background:#E09900;color:#111!important;font:700 17px/1 Jura,sans-serif;text-decoration:none!important;margin:14px 0}.ss-getfree:hover{background:#f2a800}.ss-getfree svg{width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2.4;stroke-linecap:round;stroke-linejoin:round}#ss-sbar{display:none!important}</style>';
}, 31 );

/* ═══ Solid Sound — 17.10.2026: slimmer header on desktop — 64 px, the same height before and after scrolling (WeMake-like) ═══ */
add_action( 'wp_head', function () {
	if ( is_admin() ) return;
	echo '<style id="ss-hdr64">@media(min-width:1025px){html:root{--header-height:64px!important;--header-sticky-height:64px!important}.l-subheader.at_middle,.l-header.sticky .l-subheader.at_middle{height:64px!important;line-height:64px!important}.l-header .l-subheader.at_middle .w-image img,.l-header .l-subheader.at_middle .w-image-h img{max-height:42px!important;width:auto!important;height:42px!important}}</style>';
}, 99 );

/* ═══ Solid Sound — 18.10.2026: SS Assistant — free on-site helper bot (buttons + keyword answers, lead form → saved + emailed). Tidio stays as "talk to a person" ═══ */
if ( ! function_exists( 'ss_bot_texts' ) ) {

	function ss_bot_lang() {
		$l = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';
		if ( 'uk' === $l ) $l = 'ua';
		return in_array( $l, array( 'en', 'ua', 'de', 'fr', 'pt' ), true ) ? $l : 'en';
	}

	function ss_bot_url( $slug_or_id ) {
		$id = is_numeric( $slug_or_id ) ? (int) $slug_or_id : 0;
		if ( ! $id ) { $p = get_page_by_path( $slug_or_id ); $id = $p ? $p->ID : 0; }
		if ( ! $id ) return home_url( '/' . ( is_numeric( $slug_or_id ) ? '' : $slug_or_id . '/' ) );
		if ( function_exists( 'pll_get_post' ) && function_exists( 'pll_current_language' ) ) {
			$t = pll_get_post( $id, pll_current_language() );
			if ( $t ) $id = $t;
		}
		return get_permalink( $id );
	}

	function ss_bot_texts() {
		$U = array(
			'svc'  => ss_bot_url( 1947 ),
			'fb'   => ss_bot_url( 1709 ),
			'free' => ss_bot_url( 'free' ),
			'lic'  => ss_bot_url( 'license' ),
			'faq'  => ss_bot_url( 'faq' ),
			'ref'  => ss_bot_url( 'refund_returns' ),
			'dl'   => function_exists( 'wc_get_account_endpoint_url' ) ? wc_get_account_endpoint_url( 'downloads' ) : home_url( '/my-account/' ),
			'shop' => home_url( '/#templates' ),
		);
		$T = array();
		/* ---------- EN ---------- */
		$T['en'] = array(
			'title' => 'Solid Sound', 'sub' => 'The team replies here or by email',
			'hi' => 'Hi! Write your question below — it goes straight to the Solid Sound team. Or pick a quick topic:',
			'ph' => 'Type your question…', 'send' => 'Send', 'open' => 'Questions? Ask here', 'close' => 'Close',
			'menu' => 'Main menu', 'human' => 'Ask a person', 'live' => 'Live chat',
			'nomatch' => 'I’m not sure I got that. Pick a topic below or leave your question for the team.',
			'more' => 'Anything else?',
			'f_title' => 'Leave your question — the answer will come to your email.',
			'f_name' => 'Your name', 'f_email' => 'Email *', 'f_msg' => 'Your question *', 'f_send' => 'Send question',
			'f_ok' => 'Thanks! Your question has been sent. The answer will come to your email, usually within a day.',
			'f_err' => 'Please enter a valid email and your question.', 'f_fail' => 'Couldn’t send right now. Please try again in a minute.',
			'topics' => array(
				'prices'  => array( 'Prices', 'Studio services (all prices in EUR):<br>• Mixing — from 60€ (mastering included)<br>• Mastering — from 15€<br>• Remix — from 150€<br>• Remake — from 120€<br>• Ghost production — from 300€<br>• Track review / co-producing — from 40€<br>• Post-production — from 40€', array( 'Compare all packages', $U['svc'] ) ),
				'order'   => array( 'Order mixing / mastering', 'How it works: open Services & Prices, compare the packages and press <b>Choose</b> — the order form guides you through the rest.<br>• Revisions are included in every package<br>• You get WAV 24-bit + MP3 320<br>• Full rights stay with you<br>• Mixing turnaround: 2–3, 5–7 or 7–9 days depending on the package (rush +30€)', array( 'Choose a package', $U['svc'] ) ),
				'feedback'=> array( 'Free track feedback', 'Send us your demo and get free professional feedback: what to fix in the arrangement, mix and master.', array( 'Get free feedback', $U['fb'] ) ),
				'free'    => array( 'Free packs', 'Free Ableton templates, presets and samples are in the Free zone. Sign up once with your name and email — and download instantly.', array( 'Open the Free zone', $U['free'] ) ),
				'license' => array( 'License', 'Templates are royalty-free: you can use the samples, presets, racks and processing in your own commercial tracks.<br>To release a track built on a template, change all the main melodies — drums, structure, effects and presets can stay.', array( 'Read the license', $U['lic'] ) ),
				'download'=> array( 'Download problem', 'After payment your download links are in the order email and in <b>My account → Downloads</b>. Please check the spam folder too.<br>Still no file? Leave your question below and we’ll fix it.', array( 'My downloads', $U['dl'] ) ),
				'tech'    => array( 'Template sounds wrong / plugin missing', '• Live can’t find Serum, Sylenth1 or Massive: install or activate the <b>VST</b> version and enable the VST folder in Live’s preferences (File/Folder).<br>• Sounds different from the preview: update the synths to the latest version or load the presets manually — they’re in a separate folder in the zip.', array( 'More answers in FAQ', $U['faq'] ) ),
				'payment' => array( 'Payment methods', 'We accept Visa, Mastercard, Apple Pay, Google Pay and PayPal. Payments are processed securely by Stripe (WooPayments) and PayPal.', null ),
				'refund'  => array( 'Refunds', 'Templates and presets are digital downloads, so they can’t be refunded once downloaded. Please check the tech specs before buying. If a file is broken, write to us and we’ll sort it out.', array( 'Refund policy', $U['ref'] ) ),
			),
		);
		/* ---------- UA ---------- */
		$T['ua'] = array(
			'title' => 'Solid Sound', 'sub' => 'Команда відповідає тут або на email',
			'hi' => 'Привіт! Напишіть запитання нижче — воно одразу прийде команді Solid Sound. Або оберіть тему:',
			'ph' => 'Напишіть запитання…', 'send' => 'Надіслати', 'open' => 'Є питання? Пишіть', 'close' => 'Закрити',
			'menu' => 'Головне меню', 'human' => 'Запитати людину', 'live' => 'Онлайн-чат',
			'nomatch' => 'Не впевнений, що зрозумів. Оберіть тему нижче або залиште запитання команді.',
			'more' => 'Ще щось?',
			'f_title' => 'Залиште запитання — відповідь прийде вам на email.',
			'f_name' => 'Ваше ім’я', 'f_email' => 'Email *', 'f_msg' => 'Ваше запитання *', 'f_send' => 'Надіслати запитання',
			'f_ok' => 'Дякуємо! Запитання надіслано. Відповідь прийде на email, зазвичай протягом доби.',
			'f_err' => 'Вкажіть правильний email і саме запитання.', 'f_fail' => 'Зараз не вдалося надіслати. Спробуйте ще раз за хвилину.',
			'topics' => array(
				'prices'  => array( 'Ціни', 'Послуги студії (ціни в EUR):<br>• Зведення — від 60€ (мастеринг включено)<br>• Мастеринг — від 15€<br>• Ремікс — від 150€<br>• Ремейк — від 120€<br>• Гост-продакшн — від 300€<br>• Розбір треку / копродакшн — від 40€<br>• Постпродакшн — від 40€', array( 'Порівняти всі пакети', $U['svc'] ) ),
				'order'   => array( 'Замовити зведення / мастеринг', 'Як це працює: відкрийте «Послуги та ціни», порівняйте пакети й натисніть <b>Choose</b> — далі форма замовлення все підкаже.<br>• Правки входять у кожен пакет<br>• Ви отримуєте WAV 24-bit + MP3 320<br>• Усі права лишаються за вами<br>• Термін зведення: 2–3, 5–7 або 7–9 днів залежно від пакета (терміново +30€)', array( 'Обрати пакет', $U['svc'] ) ),
				'feedback'=> array( 'Безкоштовний розбір треку', 'Надішліть демо й отримайте безкоштовний професійний фідбек: що покращити в аранжуванні, зведенні та мастерингу.', array( 'Отримати фідбек', $U['fb'] ) ),
				'free'    => array( 'Безкоштовні паки', 'Безкоштовні шаблони Ableton, пресети й семпли — у Free zone. Один раз вкажіть ім’я та email — і завантажуйте одразу.', array( 'Відкрити Free zone', $U['free'] ) ),
				'license' => array( 'Ліцензія', 'Шаблони royalty-free: семпли, пресети, ракі та обробку можна використовувати у власних комерційних треках.<br>Щоб випустити трек на основі шаблону, змініть усі головні мелодії — барабани, структуру, ефекти й пресети можна лишити.', array( 'Читати ліцензію', $U['lic'] ) ),
				'download'=> array( 'Проблема із завантаженням', 'Після оплати посилання на файли є в листі із замовленням і в <b>Мій акаунт → Завантаження</b>. Перевірте також папку «Спам».<br>Файлу все одно немає? Залиште запитання нижче — розберемося.', array( 'Мої завантаження', $U['dl'] ) ),
				'tech'    => array( 'Шаблон звучить не так / немає плагіна', '• Live не бачить Serum, Sylenth1 чи Massive: встановіть або активуйте <b>VST</b>-версію та увімкніть папку VST у налаштуваннях Live (File/Folder).<br>• Звучить інакше, ніж у прев’ю: оновіть синтезатори до останньої версії або завантажте пресети вручну — вони в окремій папці в архіві.', array( 'Більше відповідей у FAQ', $U['faq'] ) ),
				'payment' => array( 'Способи оплати', 'Приймаємо Visa, Mastercard, Apple Pay, Google Pay і PayPal. Платежі безпечно обробляють Stripe (WooPayments) і PayPal.', null ),
				'refund'  => array( 'Повернення коштів', 'Шаблони й пресети — цифрові товари, тож після завантаження кошти не повертаються. Перевіряйте технічні вимоги перед покупкою. Якщо файл пошкоджений — напишіть нам, ми все виправимо.', array( 'Політика повернення', $U['ref'] ) ),
			),
		);
		/* ---------- DE ---------- */
		$T['de'] = array(
			'title' => 'Solid Sound', 'sub' => 'Das Team antwortet hier oder per E-Mail',
			'hi' => 'Hallo! Schreib deine Frage unten — sie geht direkt an das Solid Sound Team. Oder wähle ein Thema:',
			'ph' => 'Deine Frage…', 'send' => 'Senden', 'open' => 'Fragen? Hier fragen', 'close' => 'Schließen',
			'menu' => 'Hauptmenü', 'human' => 'Einen Menschen fragen', 'live' => 'Live-Chat',
			'nomatch' => 'Das habe ich nicht ganz verstanden. Wähle unten ein Thema oder hinterlasse deine Frage für das Team.',
			'more' => 'Sonst noch etwas?',
			'f_title' => 'Hinterlasse deine Frage — die Antwort kommt per E-Mail.',
			'f_name' => 'Dein Name', 'f_email' => 'E-Mail *', 'f_msg' => 'Deine Frage *', 'f_send' => 'Frage senden',
			'f_ok' => 'Danke! Deine Frage wurde gesendet. Die Antwort kommt per E-Mail, meist innerhalb eines Tages.',
			'f_err' => 'Bitte gib eine gültige E-Mail und deine Frage ein.', 'f_fail' => 'Senden hat gerade nicht geklappt. Bitte versuche es in einer Minute erneut.',
			'topics' => array(
				'prices'  => array( 'Preise', 'Studio-Services (Preise in EUR):<br>• Mixing — ab 60€ (Mastering inklusive)<br>• Mastering — ab 15€<br>• Remix — ab 150€<br>• Remake — ab 120€<br>• Ghost Production — ab 300€<br>• Track-Review / Co-Producing — ab 40€<br>• Postproduktion — ab 40€', array( 'Alle Pakete vergleichen', $U['svc'] ) ),
				'order'   => array( 'Mixing / Mastering bestellen', 'So geht’s: Öffne Services & Preise, vergleiche die Pakete und klicke auf <b>Choose</b> — das Bestellformular führt dich durch den Rest.<br>• Revisionen sind in jedem Paket enthalten<br>• Du bekommst WAV 24-bit + MP3 320<br>• Alle Rechte bleiben bei dir<br>• Mixing dauert 2–3, 5–7 oder 7–9 Tage je nach Paket (Express +30€)', array( 'Paket wählen', $U['svc'] ) ),
				'feedback'=> array( 'Kostenloses Track-Feedback', 'Schick uns dein Demo und erhalte kostenloses Profi-Feedback: was du an Arrangement, Mix und Master verbessern kannst.', array( 'Feedback holen', $U['fb'] ) ),
				'free'    => array( 'Kostenlose Packs', 'Kostenlose Ableton-Templates, Presets und Samples findest du in der Free Zone. Einmal mit Name und E-Mail anmelden — und sofort herunterladen.', array( 'Zur Free Zone', $U['free'] ) ),
				'license' => array( 'Lizenz', 'Templates sind lizenzfrei: Samples, Presets, Racks und Processing darfst du in eigenen kommerziellen Tracks nutzen.<br>Um einen Track auf Basis eines Templates zu veröffentlichen, ändere alle Hauptmelodien — Drums, Struktur, Effekte und Presets dürfen bleiben.', array( 'Lizenz lesen', $U['lic'] ) ),
				'download'=> array( 'Download-Problem', 'Nach der Zahlung findest du die Download-Links in der Bestell-E-Mail und unter <b>Mein Konto → Downloads</b>. Schau auch im Spam-Ordner nach.<br>Immer noch keine Datei? Hinterlasse unten deine Frage — wir lösen das.', array( 'Meine Downloads', $U['dl'] ) ),
				'tech'    => array( 'Template klingt falsch / Plugin fehlt', '• Live findet Serum, Sylenth1 oder Massive nicht: installiere oder aktiviere die <b>VST</b>-Version und aktiviere den VST-Ordner in Lives Einstellungen (File/Folder).<br>• Klingt anders als die Vorschau: aktualisiere die Synths oder lade die Presets manuell — sie liegen in einem eigenen Ordner im Zip.', array( 'Mehr im FAQ', $U['faq'] ) ),
				'payment' => array( 'Zahlungsmethoden', 'Wir akzeptieren Visa, Mastercard, Apple Pay, Google Pay und PayPal. Die Zahlungen werden sicher über Stripe (WooPayments) und PayPal abgewickelt.', null ),
				'refund'  => array( 'Rückerstattung', 'Templates und Presets sind digitale Downloads und können nach dem Download nicht erstattet werden. Bitte prüfe vor dem Kauf die technischen Anforderungen. Ist eine Datei defekt, schreib uns — wir kümmern uns darum.', array( 'Rückerstattungsrichtlinie', $U['ref'] ) ),
			),
		);
		/* ---------- FR ---------- */
		$T['fr'] = array(
			'title' => 'Solid Sound', 'sub' => 'L’équipe répond ici ou par e-mail',
			'hi' => 'Salut ! Écris ta question en bas — elle arrive directement à l’équipe Solid Sound. Ou choisis un sujet :',
			'ph' => 'Ta question…', 'send' => 'Envoyer', 'open' => 'Une question ? Demande ici', 'close' => 'Fermer',
			'menu' => 'Menu principal', 'human' => 'Demander à une personne', 'live' => 'Chat en direct',
			'nomatch' => 'Je ne suis pas sûr d’avoir compris. Choisis un sujet ci-dessous ou laisse ta question à l’équipe.',
			'more' => 'Autre chose ?',
			'f_title' => 'Laisse ta question — la réponse arrivera par e-mail.',
			'f_name' => 'Ton nom', 'f_email' => 'E-mail *', 'f_msg' => 'Ta question *', 'f_send' => 'Envoyer la question',
			'f_ok' => 'Merci ! Ta question a été envoyée. La réponse arrivera par e-mail, généralement sous 24 h.',
			'f_err' => 'Indique un e-mail valide et ta question.', 'f_fail' => 'Envoi impossible pour le moment. Réessaie dans une minute.',
			'topics' => array(
				'prices'  => array( 'Prix', 'Services du studio (prix en EUR) :<br>• Mixage — dès 60€ (mastering inclus)<br>• Mastering — dès 15€<br>• Remix — dès 150€<br>• Remake — dès 120€<br>• Ghost production — dès 300€<br>• Avis sur ton morceau / coproduction — dès 40€<br>• Post-production — dès 40€', array( 'Comparer les offres', $U['svc'] ) ),
				'order'   => array( 'Commander mixage / mastering', 'Comment ça marche : ouvre Services & Prix, compare les offres et clique sur <b>Choose</b> — le formulaire de commande te guide pour la suite.<br>• Les révisions sont incluses dans chaque offre<br>• Tu reçois du WAV 24-bit + MP3 320<br>• Tous les droits te restent<br>• Délai de mixage : 2–3, 5–7 ou 7–9 jours selon l’offre (express +30€)', array( 'Choisir une offre', $U['svc'] ) ),
				'feedback'=> array( 'Avis gratuit sur ton morceau', 'Envoie-nous ta démo et reçois un avis pro gratuit : quoi améliorer dans l’arrangement, le mix et le master.', array( 'Obtenir un avis', $U['fb'] ) ),
				'free'    => array( 'Packs gratuits', 'Templates Ableton, presets et samples gratuits dans la Free zone. Inscris-toi une fois avec ton nom et ton e-mail — et télécharge tout de suite.', array( 'Ouvrir la Free zone', $U['free'] ) ),
				'license' => array( 'Licence', 'Les templates sont libres de droits : tu peux utiliser les samples, presets, racks et traitements dans tes propres morceaux commerciaux.<br>Pour sortir un morceau basé sur un template, change toutes les mélodies principales — batterie, structure, effets et presets peuvent rester.', array( 'Lire la licence', $U['lic'] ) ),
				'download'=> array( 'Problème de téléchargement', 'Après le paiement, les liens sont dans l’e-mail de commande et dans <b>Mon compte → Téléchargements</b>. Vérifie aussi les spams.<br>Toujours rien ? Laisse ta question ci-dessous, on règle ça.', array( 'Mes téléchargements', $U['dl'] ) ),
				'tech'    => array( 'Le template sonne mal / plugin manquant', '• Live ne trouve pas Serum, Sylenth1 ou Massive : installe ou active la version <b>VST</b> et active le dossier VST dans les préférences de Live (File/Folder).<br>• Le son diffère de l’aperçu : mets les synthés à jour ou charge les presets à la main — ils sont dans un dossier séparé du zip.', array( 'Plus de réponses (FAQ)', $U['faq'] ) ),
				'payment' => array( 'Moyens de paiement', 'Nous acceptons Visa, Mastercard, Apple Pay, Google Pay et PayPal. Les paiements sont traités en toute sécurité par Stripe (WooPayments) et PayPal.', null ),
				'refund'  => array( 'Remboursements', 'Les templates et presets sont des téléchargements numériques : ils ne sont pas remboursables une fois téléchargés. Vérifie la configuration requise avant l’achat. Si un fichier est défectueux, écris-nous et on s’en occupe.', array( 'Politique de remboursement', $U['ref'] ) ),
			),
		);
		/* ---------- PT ---------- */
		$T['pt'] = array(
			'title' => 'Solid Sound', 'sub' => 'A equipe responde aqui ou por e-mail',
			'hi' => 'Olá! Escreva sua pergunta abaixo — ela vai direto para a equipe Solid Sound. Ou escolha um tema:',
			'ph' => 'Digite sua pergunta…', 'send' => 'Enviar', 'open' => 'Dúvidas? Pergunte aqui', 'close' => 'Fechar',
			'menu' => 'Menu principal', 'human' => 'Falar com uma pessoa', 'live' => 'Chat ao vivo',
			'nomatch' => 'Não tenho certeza se entendi. Escolha um tema abaixo ou deixe sua pergunta para a equipe.',
			'more' => 'Mais alguma coisa?',
			'f_title' => 'Deixe sua pergunta — a resposta chegará por e-mail.',
			'f_name' => 'Seu nome', 'f_email' => 'E-mail *', 'f_msg' => 'Sua pergunta *', 'f_send' => 'Enviar pergunta',
			'f_ok' => 'Obrigado! Sua pergunta foi enviada. A resposta chegará por e-mail, normalmente em até um dia.',
			'f_err' => 'Informe um e-mail válido e sua pergunta.', 'f_fail' => 'Não foi possível enviar agora. Tente de novo em um minuto.',
			'topics' => array(
				'prices'  => array( 'Preços', 'Serviços do estúdio (preços em EUR):<br>• Mixagem — a partir de 60€ (masterização incluída)<br>• Masterização — a partir de 15€<br>• Remix — a partir de 150€<br>• Remake — a partir de 120€<br>• Ghost production — a partir de 300€<br>• Análise da faixa / coprodução — a partir de 40€<br>• Pós-produção — a partir de 40€', array( 'Comparar pacotes', $U['svc'] ) ),
				'order'   => array( 'Pedir mixagem / masterização', 'Como funciona: abra Serviços & Preços, compare os pacotes e clique em <b>Choose</b> — o formulário do pedido guia você no resto.<br>• Revisões incluídas em todos os pacotes<br>• Você recebe WAV 24-bit + MP3 320<br>• Todos os direitos ficam com você<br>• Prazo da mixagem: 2–3, 5–7 ou 7–9 dias conforme o pacote (urgente +30€)', array( 'Escolher pacote', $U['svc'] ) ),
				'feedback'=> array( 'Feedback grátis da faixa', 'Envie sua demo e receba um feedback profissional grátis: o que melhorar no arranjo, mix e master.', array( 'Pedir feedback', $U['fb'] ) ),
				'free'    => array( 'Packs grátis', 'Templates Ableton, presets e samples grátis estão na Free zone. Cadastre-se uma vez com nome e e-mail — e baixe na hora.', array( 'Abrir a Free zone', $U['free'] ) ),
				'license' => array( 'Licença', 'Os templates são livres de royalties: você pode usar samples, presets, racks e processamento nas suas faixas comerciais.<br>Para lançar uma faixa feita a partir de um template, mude todas as melodias principais — bateria, estrutura, efeitos e presets podem ficar.', array( 'Ler a licença', $U['lic'] ) ),
				'download'=> array( 'Problema com download', 'Após o pagamento, os links estão no e-mail do pedido e em <b>Minha conta → Downloads</b>. Confira também a pasta de spam.<br>Ainda sem o arquivo? Deixe sua pergunta abaixo e resolvemos.', array( 'Meus downloads', $U['dl'] ) ),
				'tech'    => array( 'Template soa diferente / falta plugin', '• O Live não encontra Serum, Sylenth1 ou Massive: instale ou ative a versão <b>VST</b> e ative a pasta VST nas preferências do Live (File/Folder).<br>• Soa diferente da prévia: atualize os sintetizadores ou carregue os presets manualmente — estão numa pasta separada no zip.', array( 'Mais respostas no FAQ', $U['faq'] ) ),
				'payment' => array( 'Formas de pagamento', 'Aceitamos Visa, Mastercard, Apple Pay, Google Pay e PayPal. Os pagamentos são processados com segurança pela Stripe (WooPayments) e PayPal.', null ),
				'refund'  => array( 'Reembolsos', 'Templates e presets são downloads digitais, por isso não são reembolsáveis após o download. Confira os requisitos técnicos antes de comprar. Se um arquivo estiver com defeito, escreva para nós e resolvemos.', array( 'Política de reembolso', $U['ref'] ) ),
			),
		);
		return $T;
	}

	/* keywords → topic (all languages, lowercase stems) */
	function ss_bot_keys() {
		return array(
			'prices'   => array( 'price', 'cost', 'how much', 'rate', 'eur', '€', 'ціна', 'цін', 'вартіст', 'скільки', 'цена', 'стоим', 'сколько', 'preis', 'kost', 'prix', 'combien', 'tarif', 'preço', 'preco', 'quanto' ),
			'order'    => array( 'order', 'book', 'mixing', 'mix ', 'master', 'remix', 'remake', 'ghost', 'co-produc', 'stems', 'turnaround', 'deadline', 'замов', 'звед', 'мастер', 'ремікс', 'гост', 'термін', 'заказ', 'свед', 'срок', 'bestell', 'misch', 'commande', 'mixage', 'délai', 'pedido', 'encomend', 'mixagem', 'prazo' ),
			'feedback' => array( 'feedback', 'review', 'opinion', 'listen to my', 'фідбек', 'фидбек', 'розбір', 'разбор', 'оцін', 'оцен', 'послухай', 'послушай', 'bewert', 'avis', 'opinião', 'opiniao' ),
			'free'     => array( 'free', 'gratis', 'freebie', 'безкошт', 'бесплат', 'халяв', 'kostenlos', 'gratuit', 'grátis' ),
			'license'  => array( 'licen', 'royalty', 'copyright', 'release', 'label', 'spotify', 'commercial', 'ліценз', 'лиценз', 'авторськ', 'авторск', 'реліз', 'релиз', 'lizenz', 'veröffentlich', 'droits', 'sortir', 'licença', 'lançar' ),
			'download' => array( 'download', 'link', 'didn\'t get', 'did not get', 'not receive', 'haven\'t received', 'email with', 'завантаж', 'скач', 'посилання', 'ссылк', 'не прийш', 'не приш', 'herunterlad', 'nicht erhalten', 'télécharg', 'pas reçu', 'baixar', 'não recebi' ),
			'tech'     => array( 'plugin', 'vst', ' au ', 'serum', 'sylenth', 'massive', 'sounds different', 'doesn\'t sound', 'missing', 'error', 'mac', 'ableton', 'live 1', 'плагін', 'плагин', 'звучить', 'звучит', 'помилк', 'ошибк', 'fehlt', 'klingt', 'manquant', 'sonne', 'erreur', 'falta', 'erro' ),
			'payment'  => array( 'pay', 'paypal', 'card', 'visa', 'mastercard', 'apple pay', 'google pay', 'stripe', 'оплат', 'картк', 'карт', 'zahl', 'karte', 'paiement', 'carte', 'pagament', 'cartão', 'cartao' ),
			'refund'   => array( 'refund', 'money back', 'return', 'cancel', 'поверн', 'возврат', 'верн', 'скасув', 'отмен', 'rückerstatt', 'erstatt', 'rembours', 'annul', 'reembols', 'devolu', 'cancel' ),
		);
	}

	/* storage: private post type for questions */
	add_action( 'init', function () {
		register_post_type( 'ss_lead', array(
			'labels'          => array( 'name' => 'Chat questions', 'singular_name' => 'Chat question', 'menu_name' => 'Chat questions' ),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_icon'       => 'dashicons-format-chat',
			'menu_position'   => 26,
			'supports'        => array( 'title', 'editor' ),
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
		) );
	} );
	add_filter( 'manage_ss_lead_posts_columns', function ( $c ) {
		return array( 'cb' => $c['cb'], 'title' => 'Question', 'ss_email' => 'Email', 'ss_lang' => 'Lang', 'ss_page' => 'Page', 'date' => $c['date'] );
	} );
	add_action( 'manage_ss_lead_posts_custom_column', function ( $col, $id ) {
		if ( 'ss_email' === $col ) { $e = get_post_meta( $id, 'ss_email', true ); echo '<a href="mailto:' . esc_attr( $e ) . '">' . esc_html( $e ) . '</a>'; }
		if ( 'ss_lang' === $col ) echo esc_html( get_post_meta( $id, 'ss_lang', true ) );
		if ( 'ss_page' === $col ) { $p = get_post_meta( $id, 'ss_page', true ); if ( $p ) echo '<a href="' . esc_url( $p ) . '" target="_blank" rel="noopener">' . esc_html( wp_parse_url( $p, PHP_URL_PATH ) ?: '/' ) . '</a>'; }
	}, 10, 2 );

	function ss_bot_lead() {
		$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		$rk = 'ss_bot_rl_' . md5( $ip );
		$n  = (int) get_transient( $rk );
		if ( $n >= 5 ) wp_send_json_error( array( 'm' => 'rate' ), 429 );
		if ( ! empty( $_POST['website'] ) ) wp_send_json_success(); /* honeypot: pretend ok */
		$el = isset( $_POST['el'] ) ? (int) $_POST['el'] : 99;
		if ( $el < 2 ) wp_send_json_success(); /* filled faster than a human */
		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$msg   = isset( $_POST['msg'] ) ? sanitize_textarea_field( wp_unslash( $_POST['msg'] ) ) : '';
		$name  = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$page  = isset( $_POST['page'] ) ? esc_url_raw( wp_unslash( $_POST['page'] ) ) : '';
		$lang  = isset( $_POST['lang'] ) ? sanitize_key( wp_unslash( $_POST['lang'] ) ) : '';
		$hist  = isset( $_POST['hist'] ) ? sanitize_textarea_field( wp_unslash( $_POST['hist'] ) ) : '';
		if ( ! is_email( $email ) || mb_strlen( $msg ) < 2 ) wp_send_json_error( array( 'm' => 'invalid' ), 400 );
		$msg  = mb_substr( $msg, 0, 3000 );
		$name = mb_substr( $name, 0, 80 );
		$hist = mb_substr( $hist, 0, 1500 );
		if ( $page && 0 !== strpos( $page, home_url() ) ) $page = '';
		set_transient( $rk, $n + 1, HOUR_IN_SECONDS );
		$title = ( $name ? $name . ' — ' : '' ) . mb_substr( preg_replace( '/\s+/', ' ', $msg ), 0, 70 );
		$id = wp_insert_post( array(
			'post_type'    => 'ss_lead',
			'post_status'  => 'private',
			'post_title'   => $title,
			'post_content' => $msg . ( $hist ? "\n\n— Chat history —\n" . $hist : '' ),
		), true );
		if ( is_wp_error( $id ) ) wp_send_json_error( array( 'm' => 'save' ), 500 );
		update_post_meta( $id, 'ss_email', $email );
		update_post_meta( $id, 'ss_name', $name );
		update_post_meta( $id, 'ss_lang', $lang );
		update_post_meta( $id, 'ss_page', $page );
		$to   = get_option( 'admin_email' );
		$body = "New question from the website assistant\n\nName: " . ( $name ? $name : '—' ) . "\nEmail: $email\nLanguage: $lang\nPage: $page\n\nQuestion:\n$msg\n" . ( $hist ? "\nChat history:\n$hist\n" : '' ) . "\nReply to this email to answer the customer directly.\nAll questions: " . admin_url( 'edit.php?post_type=ss_lead' );
		$headers = array( 'Reply-To: ' . ( $name ? str_replace( array( "\r", "\n", '<', '>', ',' ), '', $name ) . ' ' : '' ) . '<' . $email . '>' );
		wp_mail( $to, '[Solid Sound] Question: ' . mb_substr( preg_replace( '/\s+/', ' ', $msg ), 0, 60 ), $body, $headers );
		do_action( 'ss_bot_lead_saved', $id, array( 'email' => $email, 'name' => $name, 'lang' => $lang, 'msg' => $msg, 'page' => $page ) );
		wp_send_json_success();
	}
	add_action( 'wp_ajax_ss_bot_lead', 'ss_bot_lead' );
	add_action( 'wp_ajax_nopriv_ss_bot_lead', 'ss_bot_lead' );

	/* widget */
	add_action( 'wp_footer', function () {
		if ( is_admin() || ( function_exists( 'is_checkout' ) && is_checkout() ) ) return;
		$T = ss_bot_texts();
		$l = ss_bot_lang();
		$D = array(
			't'    => $T[ $l ],
			'k'    => ss_bot_keys(),
			'lang' => $l,
			'ajax' => admin_url( 'admin-ajax.php' ),
			'tg'   => ( function_exists( 'ss_chat_ready' ) && ss_chat_ready() ) ? 1 : 0,
			'em'   => is_user_logged_in() ? wp_get_current_user()->user_email : '',
			'nm'   => is_user_logged_in() ? wp_get_current_user()->display_name : '',
		);
		?>
<div id="ssb" class="ssb" data-ssp-keep="1">
	<button type="button" class="ssb-fab" aria-label="<?php echo esc_attr( $T[ $l ]['open'] ); ?>" aria-expanded="false"><svg class="ssb-i-chat" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5.5A2.5 2.5 0 016.5 3h11A2.5 2.5 0 0120 5.5v8a2.5 2.5 0 01-2.5 2.5H10l-4.2 3.6c-.5.4-1.3.1-1.3-.6V16A2.5 2.5 0 014 13.5z"/></svg><svg class="ssb-i-x" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
	<div class="ssb-panel" role="dialog" aria-label="<?php echo esc_attr( $T[ $l ]['title'] ); ?>" hidden>
		<div class="ssb-hd"><div><b><?php echo esc_html( $T[ $l ]['title'] ); ?></b><span><?php echo esc_html( $T[ $l ]['sub'] ); ?></span></div><button type="button" class="ssb-x" aria-label="<?php echo esc_attr( $T[ $l ]['close'] ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg></button></div>
		<div class="ssb-log" aria-live="polite"></div>
		<form class="ssb-in" autocomplete="off"><textarea rows="1" maxlength="1500" enterkeyhint="send" autocomplete="off" autocorrect="on" spellcheck="true" placeholder="<?php echo esc_attr( $T[ $l ]['ph'] ); ?>" aria-label="<?php echo esc_attr( $T[ $l ]['ph'] ); ?>"></textarea><button type="submit" aria-label="<?php echo esc_attr( $T[ $l ]['send'] ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12h15M13 6l6 6-6 6"/></svg></button></form>
	</div>
</div>
<style id="ssb-css">
html body #tidio-chat{display:none!important}
html body.ssb-live #tidio-chat{display:block!important}
html body.ssb-live .ssb{display:none!important}
.ssb{--b:20px;position:fixed;right:20px;bottom:var(--b);z-index:2147482000;font-family:Jura,Roboto,sans-serif;transition:bottom .25s}
.ssb-fab{width:56px;height:56px;border-radius:50%;border:0;background:#E09900;color:#111;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 6px 20px rgba(0,0,0,.45);transition:transform .15s,background .15s;padding:0}
.ssb-fab:hover{background:#f2a800;transform:scale(1.05)}
.ssb-fab svg{width:26px;height:26px;fill:none;stroke:#111;stroke-width:2;stroke-linejoin:round;stroke-linecap:round}
.ssb-fab .ssb-i-x,.ssb.is-open .ssb-fab .ssb-i-chat{display:none}.ssb.is-open .ssb-fab .ssb-i-x{display:block}
.ssb-panel{position:absolute;right:0;bottom:68px;width:370px;max-width:calc(100vw - 32px);height:min(560px,calc(100vh - var(--b) - 100px));display:flex;flex-direction:column;background:#121212;border:1px solid #272727;border-radius:14px;box-shadow:0 18px 50px rgba(0,0,0,.6);overflow:hidden;color:#F4EFE8}
.ssb-panel[hidden]{display:none}
.ssb-hd{display:flex;align-items:center;justify-content:space-between;padding:14px 14px 12px 16px;border-bottom:1px solid #272727;background:#0d0d0d}
.ssb-hd b{display:block;font:700 15px/1.2 Sora,Roboto,sans-serif;color:#fff}
.ssb-hd span{display:block;font-size:12px;color:#8f8a84;margin-top:2px}
.ssb-hd span:before{content:"";display:inline-block;width:7px;height:7px;border-radius:50%;background:#3fd786;margin-right:6px;vertical-align:1px}
.ssb-x{width:34px;height:34px;border:0;border-radius:50%;background:transparent;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0}
.ssb-x:hover{background:#1f1f1f}
.ssb-x svg{width:18px;height:18px;stroke:#cfc9c2;stroke-width:2.2;fill:none;stroke-linecap:round}
.ssb-log{flex:1;overflow-y:auto;padding:14px;display:flex;flex-direction:column;gap:10px;overscroll-behavior:contain}
.ssb-m{max-width:88%;padding:10px 12px;border-radius:12px;font-size:14px;line-height:1.45;word-wrap:break-word}
.ssb-m.b{align-self:flex-start;background:#1c1b1a;border:1px solid #272727;border-top-left-radius:4px}
.ssb-m.u{align-self:flex-end;background:#E09900;color:#111;border-top-right-radius:4px;font-weight:600}
.ssb-m b{color:#fff}.ssb-m.u b{color:#111}
.ssb-go{display:inline-flex;align-items:center;gap:6px;margin-top:8px;padding:7px 12px;border-radius:999px;background:#E09900;color:#111!important;font-weight:700;font-size:13px;text-decoration:none!important}
.ssb-go:hover{background:#f2a800}
.ssb-chips{display:flex;flex-wrap:wrap;gap:6px}
.ssb-chip{border:1px solid #333;background:#161616;color:#F4EFE8;border-radius:999px;padding:7px 11px;font:600 13px/1.2 Jura,Roboto,sans-serif;cursor:pointer;text-align:left}
.ssb-chip:hover{border-color:#E09900;color:#E09900}
.ssb-chip.alt{border-color:#E09900;color:#E09900}
.ssb-f{display:flex;flex-direction:column;gap:7px;align-self:stretch;background:#1c1b1a;border:1px solid #272727;border-radius:12px;padding:12px}
.ssb-f p{margin:0 0 2px;font-size:13px;color:#cfc9c2}
.ssb-f input,.ssb-f textarea{width:100%;box-sizing:border-box;background:#0d0d0d;border:1px solid #333;border-radius:8px;color:#F4EFE8;padding:9px 10px;font:14px/1.35 Jura,Roboto,sans-serif;margin:0}
.ssb-f textarea{min-height:76px;resize:vertical}
.ssb-f input:focus,.ssb-f textarea:focus,.ssb-in input:focus{outline:none;border-color:#E09900}
.ssb-f .ssb-hp{position:absolute!important;left:-9999px!important;width:1px!important;height:1px!important;opacity:0}
.ssb-f button{border:0;border-radius:8px;background:#E09900;color:#111;font:700 14px/1 Jura,Roboto,sans-serif;padding:11px;cursor:pointer}
.ssb-f button[disabled]{opacity:.6}
.ssb-f .ssb-err{color:#f87171;font-size:12px;min-height:0}
.ssb-in{display:flex;gap:8px;padding:10px;border-top:1px solid #272727;background:#0d0d0d;margin:0}
.ssb-in input{flex:1;min-width:0;background:#161616;border:1px solid #333;border-radius:999px;color:#F4EFE8;padding:10px 14px;font:14px/1.2 Jura,Roboto,sans-serif;margin:0;height:auto}
.ssb-in button{width:40px;height:40px;flex:none;border:0;border-radius:50%;background:#E09900;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0}
.ssb-in button svg{width:18px;height:18px;stroke:#111;stroke-width:2.4;fill:none;stroke-linecap:round;stroke-linejoin:round}
@media(max-width:600px){
.ssb{right:12px}
.ssb-fab{width:52px;height:52px}
.ssb.is-open{left:0;right:0;bottom:0!important}
.ssb.is-open .ssb-fab{display:none}
.ssb-panel{position:fixed;left:0;right:0;bottom:0;width:100%;max-width:none;height:85vh;height:85dvh;border-radius:16px 16px 0 0;border-bottom:0}
.ssb-in input{font-size:16px}
.ssb-f input,.ssb-f textarea{font-size:16px}
}
.ssb-fab{position:relative}
.ssb.has-new .ssb-fab:after{content:"";position:absolute;top:1px;right:1px;width:14px;height:14px;border-radius:50%;background:#ff3b30;border:2px solid #121212}
.ssb-m.op{border-color:rgba(224,153,0,.55)}
.ssb-who{display:block;font-size:11px;color:#E09900;font-weight:700;margin-bottom:3px;letter-spacing:.3px}
.ssb-m.sys{align-self:stretch;max-width:none;background:transparent;border:1px dashed #333;color:#a8a29b;font-size:12.5px;text-align:center}
.ssb-in textarea{flex:1;min-width:0;background:#161616;border:1px solid #333;border-radius:20px;color:#F4EFE8;padding:10px 14px;font:14px/1.35 Jura,Roboto,sans-serif;margin:0;height:42px;max-height:110px;resize:none;overflow-y:hidden;box-shadow:none!important;outline:none!important;-webkit-appearance:none;appearance:none;display:block}
.ssb-in textarea:focus{border-color:#E09900;box-shadow:none!important;outline:none!important}
.ssb-in textarea::placeholder{color:#7d7872}
.ssb-in{align-items:flex-end}
.ssb-panel{position:absolute}
.ssb-f.ssb-sheet{position:absolute;left:10px;right:10px;bottom:64px;z-index:5;padding-top:14px;box-shadow:0 -12px 34px rgba(0,0,0,.7);border-color:#3a3a3a;max-height:calc(100% - 130px);overflow:auto;animation:ssbUp .18s ease-out}
.ssb-f.ssb-sheet p{padding-right:26px}
.ssb-f .ssb-sheet-x{position:absolute;top:6px;right:6px;width:30px;height:30px;padding:0!important;background:transparent!important;color:#8f8a84!important;font:400 22px/1 Arial,sans-serif!important;border-radius:50%}
.ssb-f .ssb-sheet-x:hover{color:#fff!important;background:#262626!important}
@keyframes ssbUp{from{transform:translateY(12px);opacity:0}to{transform:none;opacity:1}}
@media(max-width:600px){.ssb-in textarea{font-size:16px}.ssb-panel{position:fixed}}
.ssb-panel,.ssb-m,.ssb-chip,.ssb-f,.ssb-in textarea,.ssb-f input,.ssb-f textarea{font-weight:600!important}
.ssb-m{font-size:15px!important;line-height:1.5!important;color:#fff}
.ssb-m.u{color:#111}
.ssb-m.sys{color:#e3ddd5!important;font-size:13.5px!important;border-color:#4a4a4a!important}
.ssb-hd span{color:#d6d0c8!important;font-size:12.5px!important}
.ssb-chip{color:#fff!important;font-size:14px!important;border-color:#444!important}
.ssb-f p{color:#f1ebe3!important;font-size:14px!important}
.ssb-in textarea::placeholder,.ssb-f input::placeholder,.ssb-f textarea::placeholder{color:#b3ada5!important;opacity:1}
.ssb-in textarea,.ssb-f input,.ssb-f textarea{color:#fff!important}
.ssb-f input:-webkit-autofill,.ssb-f input:-webkit-autofill:hover,.ssb-f input:-webkit-autofill:focus,.ssb-f input:autofill{-webkit-box-shadow:0 0 0 60px #0d0d0d inset!important;box-shadow:0 0 0 60px #0d0d0d inset!important;-webkit-text-fill-color:#fff!important;caret-color:#fff;background-color:#0d0d0d!important;transition:background-color 9999s}
.ssb-f textarea{min-height:64px!important}
</style>
<?php echo '<script>window.SS_SSB=' . wp_json_encode( $D ) . ';</script>'; ?>
<script id="ssb-js">
(function(){if(window.__ssb)return;window.__ssb=1;
var D=window.SS_SSB;if(!D)return;var T=D.t,root=document.getElementById('ssb');if(!root)return;
var fab=root.querySelector('.ssb-fab'),panel=root.querySelector('.ssb-panel'),log=root.querySelector('.ssb-log'),form=root.querySelector('.ssb-in'),inp=form.querySelector('textarea,input'),hist=[],started=false,t0=0;
function ss(k,v){try{if(v===undefined)return sessionStorage.getItem(k);sessionStorage.setItem(k,v)}catch(e){return null}}
function esc(s){return String(s).replace(/[&<>"]/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]})}
function scroll(){log.scrollTop=log.scrollHeight}
function msg(html,who){var d=document.createElement('div');d.className='ssb-m '+(who||'b');d.innerHTML=html;log.appendChild(d);scroll();return d}
function chips(list){var w=document.createElement('div');w.className='ssb-chips';list.forEach(function(c){var b=document.createElement('button');b.type='button';b.className='ssb-chip'+(c.alt?' alt':'');b.textContent=c.l;b.addEventListener('click',function(){c.f()});w.appendChild(b)});log.appendChild(w);scroll();return w}
function menu(){var L=[];Object.keys(T.topics).forEach(function(k){L.push({l:T.topics[k][0],f:function(){ask(k,true)}})});L.push({l:T.human,alt:1,f:human});chips(L)}
function after(){chips([{l:T.menu,f:function(){menu()}},{l:T.human,alt:1,f:human}])}
function answer(k){var t=T.topics[k];var h=t[1];if(t[2])h+='<br><a class="ssb-go" href="'+esc(t[2][1])+'">'+esc(t[2][0])+' →</a>';msg(h,'b');hist.push('Bot: ['+k+']');after()}
function ask(k,fromChip){if(fromChip){msg(esc(T.topics[k][0]),'u');hist.push('Click: '+T.topics[k][0])}setTimeout(function(){answer(k)},250)}
function match(q){q=' '+q.toLowerCase()+' ';var best=null,score=0;Object.keys(D.k).forEach(function(k){var s=0;D.k[k].forEach(function(w){if(q.indexOf(w)>-1)s+=w.length>3?2:1});if(s>score){score=s;best=k}});return score?best:null}
function human(q){if(SID){msg(esc(LT.on),'b sys');return}if(PF&&PF.isConnected){if(typeof q==='string'&&q){var ta=PF.querySelector('textarea');ta.value=(ta.value?ta.value+'\n':'')+q}var e0=PF.querySelector('input[name=email]');if(e0)e0.focus();return}if(typeof q!=='string'){q='';msg(esc(T.human),'u');hist.push('Click: '+T.human)}if(D.tg&&D.em&&q){autoStart(q);return}var f=document.createElement('form');f.className='ssb-f';f.noValidate=true;
f.innerHTML='<p>'+esc(T.f_title)+'</p><input name="name" type="text" maxlength="80" placeholder="'+esc(T.f_name)+'" autocomplete="name"><input name="email" type="email" maxlength="120" placeholder="'+esc(T.f_email)+'" autocomplete="email" required><textarea name="msg" maxlength="3000" placeholder="'+esc(T.f_msg)+'" required></textarea><input class="ssb-hp" name="website" tabindex="-1" autocomplete="off" aria-hidden="true"><div class="ssb-err"></div><button type="submit">'+esc(T.f_send)+'</button>';
var last=hist.filter(function(h){return h.indexOf('User: ')===0}).slice(-1)[0];if(last){f.querySelector('textarea').value=last.replace(/^User: /,'')}
log.appendChild(f);scroll();t0=Date.now();PF=f;if(D.tg){f.querySelector('p').textContent=LT.em;if(D.em)f.elements['email'].value=D.em;if(D.nm)f.elements['name'].value=D.nm;f.classList.add('ssb-sheet');panel.appendChild(f);var cx=document.createElement('button');cx.type='button';cx.className='ssb-sheet-x';cx.setAttribute('aria-label','Close');cx.textContent='×';cx.addEventListener('click',function(){var t=f.elements['msg'].value.trim();f.remove();PF=null;if(t&&!inp.value){inp.value=t;grow()}});f.appendChild(cx);setTimeout(function(){var e=f.elements['email'];if(e&&!e.value)e.focus()},30)}
f.addEventListener('submit',function(e){e.preventDefault();var er=f.querySelector('.ssb-err'),em=f.elements['email'].value.trim(),m=f.elements['msg'].value.trim();if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em)||m.length<2){er.textContent=T.f_err;return}
var b=f.querySelector('button');b.disabled=true;er.textContent='';var fd=new FormData();fd.append('action',D.tg?'ss_chat_start':'ss_bot_lead');fd.append('name',f.elements['name'].value.trim());fd.append('email',em);fd.append('msg',m);fd.append('website',f.elements['website'].value);fd.append('page',location.href.split('#')[0]);fd.append('lang',D.lang);fd.append('el',Math.round((Date.now()-t0)/1000));fd.append('hist',hist.slice(-12).join('\n'));
fetch(D.ajax,{method:'POST',body:fd,credentials:'same-origin'}).then(function(r){return r.json()}).then(function(j){if(j&&j.success){f.remove();if(j.data&&j.data.sid){goLive(j.data.sid,j.data.i,m,em,false)}else{msg(esc(T.f_ok),'b');hist.push('Lead sent');after()}}else{b.disabled=false;er.textContent=(j&&j.data&&j.data.m==='invalid')?T.f_err:T.f_fail}}).catch(function(){b.disabled=false;er.textContent=T.f_fail})});
if(window.tidioChatApi&&!D.tg){chips([{l:T.live,alt:1,f:live}])}
setTimeout(function(){var x=f.querySelector(last?'input[name=email]':'input[name=name]');if(x&&matchMedia('(hover:hover)').matches)x.focus()},50)}
function live(){var a=window.tidioChatApi;if(!a)return;document.body.classList.add('ssb-live');close();try{if(a.display)a.display(true);if(a.show)a.show();a.open()}catch(e){}}
function start(){if(started)return;started=true;msg(esc(T.hi),'b');if(SID){hist0()}else{menu()}}
function open(){setTimeout(fitVV,80);root.classList.add('is-open');badge(false);if(SID)schedule(400);panel.hidden=false;fab.setAttribute('aria-expanded','true');start();ss('ssb_open','1');setTimeout(function(){if(matchMedia('(hover:hover)').matches)inp.focus()},60);if(matchMedia('(max-width:600px)').matches)document.documentElement.style.overflow='hidden'}
function close(){setTimeout(fitVV,0);root.classList.remove('is-open');panel.hidden=true;fab.setAttribute('aria-expanded','false');ss('ssb_open','0');document.documentElement.style.overflow=''}
var LT=({en:{on:'You’re now chatting with the Solid Sound team. We usually reply within a day — here, and by email if you leave the site.',team:'Solid Sound',fail:'Message not sent. Please try again.'},ua:{on:'Ви на зв’язку з командою Solid Sound. Зазвичай відповідаємо протягом доби — тут і на email, якщо ви підете з сайту.',team:'Solid Sound',fail:'Повідомлення не надіслано. Спробуйте ще раз.'},de:{on:'Du schreibst jetzt mit dem Solid Sound Team. Wir antworten meist innerhalb eines Tages — hier und per E-Mail, falls du die Seite verlässt.',team:'Solid Sound',fail:'Nachricht nicht gesendet. Bitte versuche es erneut.'},fr:{on:'Tu es maintenant en contact avec l’équipe Solid Sound. On répond généralement sous 24 h — ici, et par e-mail si tu quittes le site.',team:'Solid Sound',fail:'Message non envoyé. Réessaie.'},pt:{on:'Agora você está falando com a equipe Solid Sound. Costumamos responder em até um dia — aqui e por e-mail se você sair do site.',team:'Solid Sound',fail:'Mensagem não enviada. Tente novamente.'}})[D.lang];if(!LT)LT={on:'',team:'Solid Sound',fail:'Error'};
var LX={en:{on:'Message received ✓ We usually join within a couple of minutes — please wait here.',away:'Nobody is online right now. We’ll answer you by email%s, usually within a few hours — you can close this window.',em:'Your email — if we can’t reply in the chat in time, the answer comes to your inbox.'},
ua:{on:'Повідомлення отримано ✓ Зазвичай підключаємось за пару хвилин — зачекайте тут.',away:'Зараз нікого немає в мережі. Ми відповімо вам на email%s, зазвичай протягом кількох годин — вікно можна закрити.',em:'Ваш email — якщо не встигнемо відповісти в чаті, відповідь прийде на пошту.'},
de:{on:'Nachricht erhalten ✓ Wir sind meist in ein paar Minuten da — bitte warte hier.',away:'Gerade ist niemand online. Wir antworten dir per E-Mail%s, meist innerhalb weniger Stunden — du kannst das Fenster schließen.',em:'Deine E-Mail — falls wir im Chat nicht rechtzeitig antworten, kommt die Antwort per Mail.'},
fr:{on:'Message reçu ✓ On arrive généralement en quelques minutes — reste ici.',away:'Personne n’est en ligne pour le moment. On te répond par e-mail%s, généralement en quelques heures — tu peux fermer cette fenêtre.',em:'Ton e-mail — si on ne répond pas à temps dans le chat, la réponse arrive dans ta boîte.'},
pt:{on:'Mensagem recebida ✓ Normalmente entramos em poucos minutos — aguarde aqui.',away:'Ninguém está online agora. Vamos responder por e-mail%s, normalmente em poucas horas — você pode fechar esta janela.',em:'Seu e-mail — se não conseguirmos responder no chat a tempo, a resposta chega na sua caixa.'}};
(function(){var x=LX[D.lang]||LX.en;for(var k in x)LT[k]=x[k]})();
var PF=null,GOT=0,AT=null;
inp.addEventListener('keydown',function(e){if(e.key==='Enter'&&!e.shiftKey&&!e.isComposing){e.preventDefault();if(form.requestSubmit)form.requestSubmit();else form.dispatchEvent(new Event('submit',{cancelable:true}))}});
function grow(){inp.style.height='auto';var h=inp.scrollHeight;inp.style.height=Math.min(Math.max(h,42),110)+'px';inp.style.overflowY=h>110?'auto':'hidden'}
inp.addEventListener('input',grow);form.addEventListener('submit',function(){setTimeout(grow,0)});
function fitVV(){var v=window.visualViewport,m=matchMedia('(max-width:600px)').matches;if(!v||!m||!root.classList.contains('is-open')){panel.style.height='';panel.style.bottom='';return}var kb=v.height<innerHeight*0.8;panel.style.height=Math.round(kb?v.height:v.height*0.85)+'px';panel.style.bottom=Math.max(0,Math.round(innerHeight-v.height-v.offsetTop))+'px';scroll()}
if(window.visualViewport){visualViewport.addEventListener('resize',fitVV);visualViewport.addEventListener('scroll',fitVV)}

function awayT(ms){if(AT)clearTimeout(AT);AT=setTimeout(function(){AT=null;if(SID&&!GOT){var e=ls('ssb_em')||'';msg(esc(LT.away.replace('%s',e?' ('+e+')':'')),'b sys')}},ms)}
function autoStart(q){var fd=new FormData();fd.append('action','ss_chat_start');fd.append('name',D.nm||'');fd.append('email',D.em);fd.append('msg',q);fd.append('page',location.href.split('#')[0]);fd.append('lang',D.lang);fd.append('hist',hist.slice(-12).join('\n'));fetch(D.ajax,{method:'POST',body:fd,credentials:'same-origin'}).then(function(r){return r.json()}).then(function(j){if(j&&j.success&&j.data&&j.data.sid){goLive(j.data.sid,j.data.i,q,D.em,1)}else{msg(esc(LT.fail),'b sys')}}).catch(function(){msg(esc(LT.fail),'b sys')})}

function ls(k,v){try{if(v===undefined)return localStorage.getItem(k);if(v===null)localStorage.removeItem(k);else localStorage.setItem(k,String(v))}catch(e){return null}}
var SID=D.tg?ls('ssb_sid'):null,LAST=+(ls('ssb_last')||0),pt=null;
function opMsg(t,i){GOT=1;if(AT){clearTimeout(AT);AT=null}var d=msg('<small class="ssb-who">'+esc(LT.team)+'</small>'+esc(t).replace(/\n/g,'<br>'),'b op');if(i)d.setAttribute('data-i',i);return d}
function applyDE(j){if(!j)return;(j.del||[]).forEach(function(i){var e=log.querySelector('.ssb-m[data-i="'+i+'"]');if(e)e.remove()});(j.ed||[]).forEach(function(x){var e=log.querySelector('.ssb-m[data-i="'+x[0]+'"]');if(e)e.innerHTML='<small class="ssb-who">'+esc(LT.team)+'</small>'+esc(x[1]).replace(/\n/g,'<br>')})}
function setLast(i){if(i>LAST){LAST=i;ls('ssb_last',i)}}
function badge(on){root.classList.toggle('has-new',!!on)}
function gone(){SID=null;ls('ssb_sid',null);ls('ssb_last',null);LAST=0;root.classList.remove('is-live')}
function schedule(ms){if(pt)clearTimeout(pt);pt=null;if(!SID)return;var o=root.classList.contains('is-open');pt=setTimeout(poll,ms||(document.hidden?60000:(o?4000:25000)))}
function post(a,extra){var fd=new FormData();fd.append('action',a);fd.append('sid',SID);for(var k in extra)fd.append(k,extra[k]);return fetch(D.ajax,{method:'POST',body:fd,credentials:'same-origin'}).then(function(r){return r.json()})}
function poll(){pt=null;if(!SID)return;var o=root.classList.contains('is-open');post('ss_chat_poll',{after:LAST,open:o?1:0}).then(function(j){if(!j||!j.success){if(j&&j.data&&j.data.m==='gone')gone();return}var n=j.data.msgs||[];if(started)applyDE(j.data);if(n.length){if(started){n.forEach(function(x){opMsg(x.t,x.i);setLast(x.i)})}if(!root.classList.contains('is-open'))badge(true)}}).catch(function(){}).then(function(){schedule()})}
function hist0(){post('ss_chat_poll',{hist:1,open:1}).then(function(j){if(!j||!j.success){gone();menu();return}var mx=0;(j.data.msgs||[]).forEach(function(x){mx=Math.max(mx,x.i);if(x.w==='op')opMsg(x.t,x.i);else msg(esc(x.t),'u')});setLast(mx);msg(esc(LT.on),'b sys');badge(false);schedule()}).catch(function(){menu()})}
function goLive(sid,i,m,em,shown){SID=sid;ls('ssb_sid',sid);setLast(i||0);root.classList.add('is-live');if(!shown){msg(esc(m),'u');hist.push('User: '+m)}msg(esc(LT.on),'b sys');if(em)ls('ssb_em',em);GOT=0;awayT(120000);schedule(2500)}
function sendLive(q){post('ss_chat_send',{msg:q}).then(function(j){if(j&&j.success){schedule(3000)}else{if(j&&j.data&&j.data.m==='gone')gone();msg(esc(LT.fail),'b sys')}}).catch(function(){msg(esc(LT.fail),'b sys')})}
if(SID){root.classList.add('is-live');schedule(2000)}
document.addEventListener('visibilitychange',function(){if(!document.hidden&&SID)schedule(500)});
fab.addEventListener('click',function(){root.classList.contains('is-open')?close():open()});
root.querySelector('.ssb-x').addEventListener('click',close);
document.addEventListener('keydown',function(e){if(e.key==='Escape'&&root.classList.contains('is-open'))close()});
form.addEventListener('submit',function(e){e.preventDefault();var q=inp.value.trim();if(!q)return;inp.value='';if(D.tg&&!SID&&!D.em){hist.push('User: '+q);human(q);return}msg(esc(q),'u');hist.push('User: '+q);if(SID){sendLive(q);return}if(D.tg){human(q);return}var k=match(q);setTimeout(function(){if(k)answer(k);else{msg(esc(T.nomatch),'b');hist.push('Bot: [nomatch]');menu()}},300)});
/* Tidio: hidden by default (CSS), shown only after "Live chat"; hide again when it is closed */
function tidio(){var a=window.tidioChatApi;if(!a||a.__ssb)return;a.__ssb=1;try{a.on('close',function(){document.body.classList.remove('ssb-live')})}catch(e){}}
document.addEventListener('tidioChat-ready',tidio);tidio();
/* keep above the bottom audio player */
function lift(){var p=document.querySelector('.ssp.is-open,.ssp-open .ssp');var h=0;if(p){var r=p.getBoundingClientRect();if(r.height&&r.top<innerHeight)h=innerHeight-r.top}root.style.setProperty('--b',(h?h+14:20)+'px')}
lift();window.ssOnDom?ssOnDom(lift):setInterval(lift,800);addEventListener('resize',lift);
document.querySelectorAll('a[href*="#ss-ask"],[data-ss-ask]').forEach(function(a){a.addEventListener('click',function(e){e.preventDefault();open();human()})});
})();
</script>
		<?php
	}, 70 );
}

/* ═══ Solid Sound — 18.10.2026: Clients Chat — site chat ⇄ Telegram group topic (free, unlimited). Settings: Settings → Clients Chat ═══ */
if ( ! function_exists( 'ss_tg_api' ) ) {

	function ss_tg_token() { return (string) get_option( 'ss_tg_token', '' ); }
	function ss_tg_target() { return (array) get_option( 'ss_tg_target', array() ); }
	function ss_chat_ready() { $t = ss_tg_target(); return ss_tg_token() && ! empty( $t['chat'] ); }

	function ss_tg_api( $method, $args = array() ) {
		$tok = ss_tg_token();
		if ( ! $tok ) return null;
		$r = wp_remote_post( 'https://api.telegram.org/bot' . $tok . '/' . $method, array(
			'timeout' => 12,
			'headers' => array( 'Content-Type' => 'application/json' ),
			'body'    => wp_json_encode( $args ),
		) );
		if ( is_wp_error( $r ) ) return null;
		$j = json_decode( wp_remote_retrieve_body( $r ), true );
		return is_array( $j ) ? $j : null;
	}

	/* send to the connected group topic; returns Telegram message_id or 0 */
	function ss_tg_send( $text, $reply_to = 0 ) {
		$t = ss_tg_target();
		if ( empty( $t['chat'] ) ) return 0;
		$a = array( 'chat_id' => $t['chat'], 'text' => mb_substr( $text, 0, 4000 ), 'disable_web_page_preview' => true );
		if ( ! empty( $t['thread'] ) ) $a['message_thread_id'] = (int) $t['thread'];
		if ( $reply_to ) $a['reply_parameters'] = array( 'message_id' => (int) $reply_to, 'allow_sending_without_reply' => true );
		$j = ss_tg_api( 'sendMessage', $a );
		return ( $j && ! empty( $j['ok'] ) ) ? (int) $j['result']['message_id'] : 0;
	}

	/* chat sessions */
	add_action( 'init', function () {
		register_post_type( 'ss_chat', array(
			'labels'          => array( 'name' => 'Clients Chat', 'singular_name' => 'Chat', 'menu_name' => 'Clients Chat' ),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_icon'       => 'dashicons-format-chat',
			'menu_position'   => 26,
			'supports'        => array( 'title' ),
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
		) );
	} );
	add_filter( 'manage_ss_chat_posts_columns', function ( $c ) {
		return array( 'cb' => $c['cb'], 'title' => 'Client', 'ss_n' => 'Messages', 'ss_last' => 'Last message', 'ss_page' => 'Page', 'date' => $c['date'] );
	} );
	add_action( 'manage_ss_chat_posts_custom_column', function ( $col, $id ) {
		$m = get_post_meta( $id, 'ss_msgs', true );
		$m = is_array( $m ) ? $m : array();
		if ( 'ss_n' === $col ) echo (int) count( $m );
		if ( 'ss_last' === $col && $m ) { $l = end( $m ); echo esc_html( ( 'op' === $l['w'] ? 'Team: ' : '' ) . mb_substr( $l['t'], 0, 90 ) ); }
		if ( 'ss_page' === $col ) { $p = get_post_meta( $id, 'ss_page', true ); if ( $p ) echo '<a href="' . esc_url( $p ) . '" target="_blank" rel="noopener">' . esc_html( wp_parse_url( $p, PHP_URL_PATH ) ?: '/' ) . '</a>'; }
	}, 10, 2 );
	add_action( 'add_meta_boxes_ss_chat', function () {
		add_meta_box( 'ss_chat_log', 'Conversation', function ( $post ) {
			$m = get_post_meta( $post->ID, 'ss_msgs', true );
			echo '<p><b>Email:</b> <a href="mailto:' . esc_attr( get_post_meta( $post->ID, 'ss_email', true ) ) . '">' . esc_html( get_post_meta( $post->ID, 'ss_email', true ) ) . '</a> &nbsp; <b>Lang:</b> ' . esc_html( get_post_meta( $post->ID, 'ss_lang', true ) ) . '</p>';
			if ( is_array( $m ) ) foreach ( $m as $x ) {
				echo '<div style="margin:6px 0;padding:8px 10px;border-radius:8px;max-width:640px;background:' . ( 'op' === $x['w'] ? '#fff7e0' : '#f1f1f1' ) . '"><small style="color:#777">' . ( 'op' === $x['w'] ? 'Team' : 'Client' ) . ' · ' . esc_html( wp_date( 'd.m.Y H:i', (int) $x['d'] ) ) . '</small><br>' . nl2br( esc_html( $x['t'] ) ) . '</div>';
			}
		}, 'ss_chat', 'normal', 'high' );
	} );

	function ss_chat_by_sid( $sid ) {
		if ( ! preg_match( '/^[a-f0-9]{32}$/', (string) $sid ) ) return 0;
		$q = get_posts( array( 'post_type' => 'ss_chat', 'post_status' => 'private', 'numberposts' => 1, 'fields' => 'ids', 'meta_key' => 'ss_sid', 'meta_value' => $sid, 'no_found_rows' => true ) );
		return $q ? (int) $q[0] : 0;
	}
	function ss_chat_add( $id, $who, $text ) {
		$m = get_post_meta( $id, 'ss_msgs', true );
		$m = is_array( $m ) ? $m : array();
		$i = (int) get_post_meta( $id, 'ss_seq', true ) + 1;
		$m[] = array( 'i' => $i, 'w' => $who, 't' => $text, 'd' => time() );
		if ( count( $m ) > 300 ) $m = array_slice( $m, -300 );
		update_post_meta( $id, 'ss_msgs', $m );
		update_post_meta( $id, 'ss_seq', $i );
		return $i;
	}
	function ss_chat_ip_key( $p ) {
		$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		return $p . md5( $ip );
	}

	/* visitor: start a live chat */
	function ss_chat_start() {
		if ( ! ss_chat_ready() ) wp_send_json_error( array( 'm' => 'off' ), 503 );
		if ( ! empty( $_POST['website'] ) ) wp_send_json_error( array( 'm' => 'invalid' ), 400 );
		$el = isset( $_POST['el'] ) ? (int) $_POST['el'] : 99;
		if ( $el < 2 ) wp_send_json_error( array( 'm' => 'invalid' ), 400 );
		$rk = ss_chat_ip_key( 'ss_chat_rs_' );
		$n  = (int) get_transient( $rk );
		if ( $n >= 6 ) wp_send_json_error( array( 'm' => 'rate' ), 429 );
		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$msg   = isset( $_POST['msg'] ) ? trim( sanitize_textarea_field( wp_unslash( $_POST['msg'] ) ) ) : '';
		$name  = isset( $_POST['name'] ) ? mb_substr( sanitize_text_field( wp_unslash( $_POST['name'] ) ), 0, 80 ) : '';
		$page  = isset( $_POST['page'] ) ? esc_url_raw( wp_unslash( $_POST['page'] ) ) : '';
		$lang  = isset( $_POST['lang'] ) ? sanitize_key( wp_unslash( $_POST['lang'] ) ) : '';
		if ( ! is_email( $email ) || mb_strlen( $msg ) < 2 ) wp_send_json_error( array( 'm' => 'invalid' ), 400 );
		$msg = mb_substr( $msg, 0, 2000 );
		if ( $page && 0 !== strpos( $page, home_url() ) ) $page = '';
		set_transient( $rk, $n + 1, HOUR_IN_SECONDS );
		$sid = bin2hex( random_bytes( 16 ) );
		$id  = wp_insert_post( array( 'post_type' => 'ss_chat', 'post_status' => 'private', 'post_title' => ( $name ? $name . ' · ' : '' ) . $email ), true );
		if ( is_wp_error( $id ) ) wp_send_json_error( array( 'm' => 'save' ), 500 );
		update_post_meta( $id, 'ss_sid', $sid );
		update_post_meta( $id, 'ss_email', $email );
		update_post_meta( $id, 'ss_name', $name );
		update_post_meta( $id, 'ss_lang', $lang );
		update_post_meta( $id, 'ss_page', $page );
		update_post_meta( $id, 'ss_seen', time() );
		$i = ss_chat_add( $id, 'u', $msg );
		$path = $page ? ( wp_parse_url( $page, PHP_URL_PATH ) ?: '/' ) : '';
		$txt = "🌐 SITE CHAT · new client #" . $id . "\n👤 " . ( $name ? $name . ' · ' : '' ) . $email . "\n🌐 " . strtoupper( $lang ? $lang : '—' ) . ( $path ? ' · ' . $path : '' ) . "\n\n" . $msg . "\n\n↩️ Reply to this message to answer.";
		$mid = ss_tg_send( $txt );
		if ( $mid ) {
			add_post_meta( $id, 'ss_tg_mid', $mid );
			update_post_meta( $id, 'ss_tg_root', $mid );
		} else {
			wp_mail( get_option( 'admin_email' ), '[Solid Sound] Chat: ' . mb_substr( $msg, 0, 60 ), "Telegram delivery failed — new chat message\n\n" . $txt . "\n\nAll chats: " . admin_url( 'edit.php?post_type=ss_chat' ), array( 'Reply-To: <' . $email . '>' ) );
		}
		do_action( 'ss_chat_started', $id, array( 'email' => $email, 'name' => $name, 'lang' => $lang ) );
		wp_send_json_success( array( 'sid' => $sid, 'i' => $i ) );
	}
	add_action( 'wp_ajax_ss_chat_start', 'ss_chat_start' );
	add_action( 'wp_ajax_nopriv_ss_chat_start', 'ss_chat_start' );

	/* visitor: next message in the same chat */
	function ss_chat_send() {
		$id = ss_chat_by_sid( isset( $_POST['sid'] ) ? sanitize_text_field( wp_unslash( $_POST['sid'] ) ) : '' );
		if ( ! $id ) wp_send_json_error( array( 'm' => 'gone' ), 404 );
		$msg = isset( $_POST['msg'] ) ? trim( sanitize_textarea_field( wp_unslash( $_POST['msg'] ) ) ) : '';
		if ( mb_strlen( $msg ) < 1 ) wp_send_json_error( array( 'm' => 'invalid' ), 400 );
		$rk = 'ss_chat_rm_' . $id;
		$n  = (int) get_transient( $rk );
		if ( $n >= 30 ) wp_send_json_error( array( 'm' => 'rate' ), 429 );
		set_transient( $rk, $n + 1, 10 * MINUTE_IN_SECONDS );
		$msg = mb_substr( $msg, 0, 2000 );
		$i   = ss_chat_add( $id, 'u', $msg );
		update_post_meta( $id, 'ss_seen', time() );
		$name = get_post_meta( $id, 'ss_name', true );
		$mid  = ss_tg_send( '🌐 SITE #' . $id . ' ' . ( $name ? $name : get_post_meta( $id, 'ss_email', true ) ) . ":\n" . $msg, (int) get_post_meta( $id, 'ss_tg_root', true ) );
		if ( $mid ) add_post_meta( $id, 'ss_tg_mid', $mid );
		wp_send_json_success( array( 'i' => $i ) );
	}
	add_action( 'wp_ajax_ss_chat_send', 'ss_chat_send' );
	add_action( 'wp_ajax_nopriv_ss_chat_send', 'ss_chat_send' );

	/* visitor: fetch new team replies (or full history) */
	function ss_chat_poll() {
		$id = ss_chat_by_sid( isset( $_POST['sid'] ) ? sanitize_text_field( wp_unslash( $_POST['sid'] ) ) : '' );
		if ( ! $id ) wp_send_json_error( array( 'm' => 'gone' ), 404 );
		$after = isset( $_POST['after'] ) ? (int) $_POST['after'] : 0;
		$hist  = ! empty( $_POST['hist'] );
		if ( ! empty( $_POST['open'] ) ) update_post_meta( $id, 'ss_seen', time() );
		$m   = get_post_meta( $id, 'ss_msgs', true );
		$out = array();
		if ( is_array( $m ) ) foreach ( $m as $x ) {
			if ( $hist || ( 'op' === $x['w'] && (int) $x['i'] > $after ) ) $out[] = array( 'i' => (int) $x['i'], 'w' => $x['w'], 't' => $x['t'] );
		}
		$ed = array();
		if ( is_array( $m ) ) foreach ( $m as $x ) { if ( ! empty( $x['e'] ) && 'op' === $x['w'] ) $ed[] = array( (int) $x['i'], $x['t'] ); }
		$del = get_post_meta( $id, 'ss_del', true );
		wp_send_json_success( array( 'msgs' => $out, 'del' => is_array( $del ) ? array_map( 'intval', $del ) : array(), 'ed' => $ed ) );
	}
	add_action( 'wp_ajax_ss_chat_poll', 'ss_chat_poll' );
	add_action( 'wp_ajax_nopriv_ss_chat_poll', 'ss_chat_poll' );

	/* Telegram webhook */
	add_action( 'rest_api_init', function () {
		register_rest_route( 'ss-chat/v1', '/tg', array(
			'methods'             => 'POST',
			'permission_callback' => '__return_true',
			'callback'            => 'ss_tg_webhook',
		) );
	} );
	function ss_tg_webhook( WP_REST_Request $req ) {
		$sec = (string) get_option( 'ss_tg_secret', '' );
		if ( ! $sec || ! hash_equals( $sec, (string) $req->get_header( 'x_telegram_bot_api_secret_token' ) ) ) return new WP_REST_Response( array( 'ok' => false ), 403 );
		$u = $req->get_json_params();
		if ( ! empty( $u['callback_query'] ) && function_exists( 'ss_cr_tg_callback' ) ) return ss_cr_tg_callback( $u['callback_query'] );
		if ( ! empty( $u['edited_message'] ) && function_exists( 'ss_tg_edit' ) ) { ss_tg_edit( $u['edited_message'] ); return array( 'ok' => true ); }
		$m = isset( $u['message'] ) ? $u['message'] : null;
		if ( ! $m || empty( $m['chat']['id'] ) ) return array( 'ok' => true );
		$chat = (string) $m['chat']['id'];
		$text = isset( $m['text'] ) ? (string) $m['text'] : ( isset( $m['caption'] ) ? (string) $m['caption'] : '' );
		$thr  = ! empty( $m['is_topic_message'] ) && ! empty( $m['message_thread_id'] ) ? (int) $m['message_thread_id'] : 0;

		/* pairing: /connect inside the group topic, only while the pairing window is open */
		if ( preg_match( '#^/connect(@\w+)?\b#i', $text ) ) {
			if ( time() < (int) get_option( 'ss_tg_pair_until', 0 ) && in_array( $m['chat']['type'], array( 'group', 'supergroup', 'private' ), true ) ) {
				update_option( 'ss_tg_target', array( 'chat' => $chat, 'thread' => $thr, 'title' => isset( $m['chat']['title'] ) ? sanitize_text_field( $m['chat']['title'] ) : ( 'private' === $m['chat']['type'] ? 'private chat with the bot' : '' ) ), false );
				update_option( 'ss_tg_pair_until', 0, false );
				ss_tg_send( "✅ Connected. Messages from the solid-sound.net chat will arrive here.\nTo answer a client, use Reply on their message." );
			}
			return array( 'ok' => true );
		}

		$t = ss_tg_target();
		if ( empty( $t['chat'] ) || $chat !== (string) $t['chat'] ) return array( 'ok' => true );
		if ( empty( $m['reply_to_message']['message_id'] ) ) return array( 'ok' => true );
		$rid = (int) $m['reply_to_message']['message_id'];
		$q   = get_posts( array( 'post_type' => 'ss_chat', 'post_status' => 'private', 'numberposts' => 1, 'fields' => 'ids', 'meta_key' => 'ss_tg_mid', 'meta_value' => $rid, 'no_found_rows' => true ) );
		if ( ! $q ) return array( 'ok' => true );
		$id = (int) $q[0];
		if ( preg_match( '#^/(del|delete)(@\w+)?\b#i', trim( $text ) ) && function_exists( 'ss_tg_del' ) ) { ss_tg_del( $id, $rid, (int) $m['message_id'] ); return array( 'ok' => true ); }
		if ( '' === trim( $text ) ) {
			ss_tg_send( '⚠️ Only text replies reach the client for now.', (int) $m['message_id'] );
			return array( 'ok' => true );
		}
		$text = mb_substr( trim( $text ), 0, 3000 );
		$oi = ss_chat_add( $id, 'op', $text );
		$omap = get_post_meta( $id, 'ss_opmap', true ); $omap = is_array( $omap ) ? $omap : array(); $omap[ (int) $m['message_id'] ] = $oi; update_post_meta( $id, 'ss_opmap', $omap );
		add_post_meta( $id, 'ss_tg_mid', (int) $m['message_id'] );
		ss_tg_api( 'setMessageReaction', array( 'chat_id' => $chat, 'message_id' => (int) $m['message_id'], 'reaction' => array( array( 'type' => 'emoji', 'emoji' => '👍' ) ) ) );
		$ch = get_post_meta( $id, 'ss_ch', true );
		if ( ( 'fb' === $ch || 'ig' === $ch ) && function_exists( 'ss_meta_reply' ) ) {
			$res = ss_meta_reply( $id, $text );
			if ( true !== $res ) ss_tg_send( '⚠️ Not delivered to ' . ( 'ig' === $ch ? 'Instagram' : 'Facebook' ) . ': ' . $res, (int) $m['message_id'] );
			return array( 'ok' => true );
		}
		/* client not on the site right now → also send the answer by email */
		if ( true ) { /* always email the answer too: the client gets the full thread by email */
			$email = get_post_meta( $id, 'ss_email', true );
			$page  = get_post_meta( $id, 'ss_page', true );
			$name  = get_post_meta( $id, 'ss_name', true );
			if ( is_email( $email ) ) {
				$body = ( $name ? 'Hi ' . $name . ",\n\n" : "Hi,\n\n" ) . $text . "\n\n— Solid Sound\n" . ( $page ? "\nContinue the chat on the site: " . $page . "\n" : '' ) . 'You can also simply reply to this email.';
				wp_mail( $email, function_exists( 'ss_chat_subj' ) ? 'Re: ' . ss_chat_subj( $id ) : 'Solid Sound: reply to your question', $body, array( 'Reply-To: Solid Sound <' . ( function_exists( 'ss_reply_email' ) ? ss_reply_email() : get_option( 'admin_email' ) ) . '>', 'Bcc: ' . ( function_exists( 'ss_reply_email' ) ? ss_reply_email() : get_option( 'admin_email' ) ) ) );
			}
		}
		return array( 'ok' => true );
	}

	/* settings page */
	add_action( 'admin_menu', function () {
		add_options_page( 'Clients Chat', 'Clients Chat', 'manage_options', 'ss-clients-chat', 'ss_tg_settings_page' );
	} );
	function ss_tg_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) return;
		$note = '';
		if ( isset( $_POST['ss_tg_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ss_tg_nonce'] ) ), 'ss_tg_save' ) ) {
			$act = isset( $_POST['ss_act'] ) ? sanitize_key( $_POST['ss_act'] ) : '';
			if ( 'save' === $act ) {
				$tok = isset( $_POST['ss_tg_token'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['ss_tg_token'] ) ) ) : '';
				if ( preg_match( '/^\d{6,12}:[A-Za-z0-9_-]{30,}$/', $tok ) ) {
					update_option( 'ss_tg_token', $tok, false );
					$sec = wp_generate_password( 40, false, false );
					update_option( 'ss_tg_secret', $sec, false );
					$me = ss_tg_api( 'getMe' );
					if ( $me && ! empty( $me['ok'] ) ) {
						update_option( 'ss_tg_bot', sanitize_text_field( $me['result']['username'] ), false );
						$wh = ss_tg_api( 'setWebhook', array( 'url' => rest_url( 'ss-chat/v1/tg' ), 'secret_token' => $sec, 'allowed_updates' => array( 'message', 'edited_message', 'callback_query' ), 'drop_pending_updates' => true ) );
						ss_tg_api( 'setMyCommands', array( 'commands' => array( array( 'command' => 'connect', 'description' => 'Receive solid-sound.net chat messages here' ) ), 'scope' => array( 'type' => 'all_group_chats' ) ) );
						update_option( 'ss_tg_pair_until', time() + 30 * MINUTE_IN_SECONDS, false );
						$note = ( $wh && ! empty( $wh['ok'] ) ) ? 'ok' : 'webhook';
					} else {
						delete_option( 'ss_tg_token' );
						$note = 'badtoken';
					}
				} else {
					$note = 'format';
				}
			} elseif ( 'pair' === $act ) {
				update_option( 'ss_tg_pair_until', time() + 30 * MINUTE_IN_SECONDS, false );
				$note = 'pair';
			} elseif ( 'test' === $act ) {
				$note = ss_tg_send( '🔔 Test from solid-sound.net — Clients Chat works.' ) ? 'testok' : 'testfail';
			} elseif ( 'off' === $act ) {
				if ( ss_tg_token() ) ss_tg_api( 'deleteWebhook', array() );
				delete_option( 'ss_tg_token' ); delete_option( 'ss_tg_target' ); delete_option( 'ss_tg_secret' ); delete_option( 'ss_tg_bot' );
				$note = 'off';
			}
		}
		$bot = get_option( 'ss_tg_bot', '' );
		$t   = ss_tg_target();
		$pair = (int) get_option( 'ss_tg_pair_until', 0 ) > time();
		$msgs = array(
			'ok'       => array( 'success', 'Bot connected. Now open your Telegram group, go to the “Clients Chat” topic and send the command /connect (tap “/” and choose it). Then reload this page.' ),
			'webhook'  => array( 'warning', 'Token accepted, but Telegram did not confirm the webhook. Press Save again in a minute.' ),
			'badtoken' => array( 'error', 'Telegram rejected this token. Copy it again from BotFather.' ),
			'format'   => array( 'error', 'This does not look like a bot token. It should look like 123456789:ABC… from BotFather.' ),
			'pair'     => array( 'info', 'Pairing is open for 30 minutes: send /connect in the topic where messages should arrive.' ),
			'testok'   => array( 'success', 'Test message sent to Telegram.' ),
			'testfail' => array( 'error', 'Could not send the test message. Check that the bot is still in the group.' ),
			'off'      => array( 'info', 'Disconnected. The site chat falls back to email.' ),
		);
		echo '<div class="wrap"><h1>Clients Chat (Telegram)</h1>';
		if ( $note && isset( $msgs[ $note ] ) ) echo '<div class="notice notice-' . esc_attr( $msgs[ $note ][0] ) . '"><p>' . esc_html( $msgs[ $note ][1] ) . '</p></div>';
		echo '<table class="form-table"><tr><th>Status</th><td>';
		if ( ss_chat_ready() ) {
			echo '<b style="color:#1a7f37">● Working</b> — bot @' . esc_html( $bot ) . ' → ' . esc_html( $t['title'] ? $t['title'] : 'group' ) . ( ! empty( $t['thread'] ) ? ' (topic #' . (int) $t['thread'] . ')' : '' );
		} elseif ( ss_tg_token() ) {
			echo '<b style="color:#b26200">● Waiting for /connect</b> — bot @' . esc_html( $bot ) . ( $pair ? ' · pairing open' : ' · pairing closed, press “Pair again”' );
		} else {
			echo '<b style="color:#888">● Not connected</b>';
		}
		echo '</td></tr></table>';
		echo '<form method="post">';
		wp_nonce_field( 'ss_tg_save', 'ss_tg_nonce' );
		echo '<h2>1. Bot token</h2><p>Paste the token from @BotFather. It is stored only on this site and never shown again.</p>';
		echo '<input type="text" name="ss_tg_token" autocomplete="off" data-lpignore="true" data-1p-ignore spellcheck="false" style="width:460px;max-width:100%;-webkit-text-security:disc;text-security:disc" placeholder="' . ( ss_tg_token() ? '•••••••• (saved — paste a new one to replace)' : '123456789:ABC…' ) . '"> ';
		echo '<button class="button button-primary" name="ss_act" value="save">Save &amp; connect</button>';
		echo '<h2>2. Group topic</h2><p>Add the bot to your group, open the “Clients Chat” topic and send <code>/connect</code>.</p>';
		echo '<button class="button" name="ss_act" value="pair">Pair again</button> ';
		if ( ss_chat_ready() ) echo '<button class="button" name="ss_act" value="test">Send test message</button> ';
		if ( ss_tg_token() ) echo '<button class="button" name="ss_act" value="off" onclick="return confirm(\'Disconnect Telegram?\')">Disconnect</button>';
		echo '</form>';
		echo '<h2>How it works</h2><ul style="list-style:disc;padding-left:20px"><li>A visitor presses “Ask a person” in the site chat → the message appears in the topic.</li><li>Answer with <b>Reply</b> on that message → the answer appears in the visitor’s chat window (👍 on your message = delivered).</li><li>If the visitor has left the site, the answer is also emailed to them.</li><li>All conversations are saved in <a href="' . esc_url( admin_url( 'edit.php?post_type=ss_chat' ) ) . '">Clients Chat</a>.</li></ul></div>';
	}
}

/* ═══ Solid Sound — 18.10.2026: Free track feedback — quick form (link + email) → Telegram "Clients Chat"; replies reach the client by email / site chat ═══ */
if ( ! function_exists( 'ss_fb_t' ) ) {

	function ss_fb_t() {
		$L = array(
			'en' => array( 'h' => 'Free track feedback', 's' => 'Drop a link to your track — get honest pro notes on arrangement, mix and master. Free, no strings attached.', 'link' => 'Link to your track *', 'link_ph' => 'SoundCloud, Google Drive, Dropbox, WeTransfer…', 'email' => 'Email *', 'name' => 'Name or artist name', 'genre' => 'Genre', 'want' => 'What should we focus on? (optional)', 'want_ph' => 'e.g. low end, vocals, does the drop hit hard enough…', 'btn' => 'Get free feedback', 'ok' => 'Got it! We’ll listen to your track and send our notes to your email. Keep an eye on the spam folder too.', 'err' => 'Please add a link to your track and a valid email.', 'fail' => 'Couldn’t send right now — please try again in a minute.', 'note' => 'We reply by email. No spam.', 'b1' => 'Arrangement', 'b2' => 'Mix', 'b3' => 'Master' ),
			'ua' => array( 'h' => 'Безкоштовний розбір треку', 's' => 'Надішліть посилання на трек — отримайте чесний професійний фідбек щодо аранжування, зведення та мастерингу. Безкоштовно і без зобов’язань.', 'link' => 'Посилання на трек *', 'link_ph' => 'SoundCloud, Google Drive, Dropbox, WeTransfer…', 'email' => 'Email *', 'name' => 'Ім’я або псевдонім', 'genre' => 'Жанр', 'want' => 'На що звернути увагу? (необов’язково)', 'want_ph' => 'наприклад: низ, вокал, чи досить сильний дроп…', 'btn' => 'Отримати фідбек', 'ok' => 'Готово! Ми послухаємо трек і надішлемо нотатки на ваш email. Перевірте також папку «Спам».', 'err' => 'Додайте посилання на трек і правильний email.', 'fail' => 'Зараз не вдалося надіслати — спробуйте ще раз за хвилину.', 'note' => 'Відповідаємо на email. Без спаму.', 'b1' => 'Аранжування', 'b2' => 'Зведення', 'b3' => 'Мастеринг' ),
			'de' => array( 'h' => 'Kostenloses Track-Feedback', 's' => 'Schick uns den Link zu deinem Track — und erhalte ehrliches Profi-Feedback zu Arrangement, Mix und Master. Kostenlos und unverbindlich.', 'link' => 'Link zu deinem Track *', 'link_ph' => 'SoundCloud, Google Drive, Dropbox, WeTransfer…', 'email' => 'E-Mail *', 'name' => 'Name oder Künstlername', 'genre' => 'Genre', 'want' => 'Worauf sollen wir achten? (optional)', 'want_ph' => 'z. B. Low End, Vocals, knallt der Drop genug…', 'btn' => 'Feedback holen', 'ok' => 'Danke! Wir hören uns deinen Track an und schicken dir unsere Notizen per E-Mail. Schau auch im Spam-Ordner nach.', 'err' => 'Bitte gib einen Link zu deinem Track und eine gültige E-Mail an.', 'fail' => 'Senden hat gerade nicht geklappt — bitte in einer Minute erneut versuchen.', 'note' => 'Wir antworten per E-Mail. Kein Spam.', 'b1' => 'Arrangement', 'b2' => 'Mix', 'b3' => 'Master' ),
			'fr' => array( 'h' => 'Avis gratuit sur ton morceau', 's' => 'Envoie le lien de ton morceau — reçois un avis pro honnête sur l’arrangement, le mix et le master. Gratuit et sans engagement.', 'link' => 'Lien vers ton morceau *', 'link_ph' => 'SoundCloud, Google Drive, Dropbox, WeTransfer…', 'email' => 'E-mail *', 'name' => 'Nom ou nom d’artiste', 'genre' => 'Genre', 'want' => 'Sur quoi se concentrer ? (facultatif)', 'want_ph' => 'ex. basses, voix, le drop tape-t-il assez fort…', 'btn' => 'Recevoir mon avis', 'ok' => 'C’est reçu ! On écoute ton morceau et on t’envoie nos notes par e-mail. Pense à vérifier les spams.', 'err' => 'Ajoute le lien de ton morceau et un e-mail valide.', 'fail' => 'Envoi impossible pour le moment — réessaie dans une minute.', 'note' => 'Réponse par e-mail. Pas de spam.', 'b1' => 'Arrangement', 'b2' => 'Mix', 'b3' => 'Master' ),
			'pt' => array( 'h' => 'Feedback grátis da sua faixa', 's' => 'Envie o link da sua faixa — receba um feedback profissional e honesto sobre arranjo, mix e master. Grátis e sem compromisso.', 'link' => 'Link da sua faixa *', 'link_ph' => 'SoundCloud, Google Drive, Dropbox, WeTransfer…', 'email' => 'E-mail *', 'name' => 'Nome ou nome artístico', 'genre' => 'Gênero', 'want' => 'No que devemos focar? (opcional)', 'want_ph' => 'ex.: graves, vocal, o drop está forte o suficiente…', 'btn' => 'Quero feedback grátis', 'ok' => 'Recebido! Vamos ouvir sua faixa e enviar nossas notas por e-mail. Confira também a pasta de spam.', 'err' => 'Adicione o link da faixa e um e-mail válido.', 'fail' => 'Não foi possível enviar agora — tente de novo em um minuto.', 'note' => 'Respondemos por e-mail. Sem spam.', 'b1' => 'Arranjo', 'b2' => 'Mix', 'b3' => 'Master' ),
		);
		$l = function_exists( 'ss_bot_lang' ) ? ss_bot_lang() : 'en';
		return isset( $L[ $l ] ) ? $L[ $l ] : $L['en'];
	}

	function ss_fb_form_html() {
		$T = ss_fb_t();
		$l = function_exists( 'ss_bot_lang' ) ? ss_bot_lang() : 'en';
		ob_start(); ?>
<section id="ss-fb" class="ssfb"><div class="ssfb-in">
	<div class="ssfb-hd">
		<span class="ssfb-free">FREE</span>
		<h2><?php echo esc_html( $T['h'] ); ?></h2>
		<p><?php echo esc_html( $T['s'] ); ?></p>
		<div class="ssfb-bul"><span><?php echo esc_html( $T['b1'] ); ?></span><span><?php echo esc_html( $T['b2'] ); ?></span><span><?php echo esc_html( $T['b3'] ); ?></span></div>
	</div>
	<form class="ssfb-f" novalidate data-lang="<?php echo esc_attr( $l ); ?>">
		<label class="ssfb-wide"><span><?php echo esc_html( $T['link'] ); ?></span><input name="link" type="url" inputmode="url" maxlength="500" placeholder="<?php echo esc_attr( $T['link_ph'] ); ?>" required></label>
		<label><span><?php echo esc_html( $T['email'] ); ?></span><input name="email" type="email" maxlength="120" autocomplete="email" required></label>
		<label><span><?php echo esc_html( $T['name'] ); ?></span><input name="name" type="text" maxlength="80" autocomplete="name"></label>
		<label><span><?php echo esc_html( $T['genre'] ); ?></span><input name="genre" type="text" maxlength="60" placeholder="Techno, House, DnB…"></label>
		<label class="ssfb-wide"><span><?php echo esc_html( $T['want'] ); ?></span><textarea name="want" maxlength="1500" rows="2" placeholder="<?php echo esc_attr( $T['want_ph'] ); ?>"></textarea></label>
		<input class="ssfb-hp" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
		<div class="ssfb-wide ssfb-row"><button type="submit"><?php echo esc_html( $T['btn'] ); ?> <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button><small><?php echo esc_html( $T['note'] ); ?></small></div>
		<div class="ssfb-wide ssfb-msg" role="status" aria-live="polite"></div>
	</form>
</div></section>
<style>
.ssfb{margin:28px auto 28px;max-width:1240px;padding:0 24px;box-sizing:border-box;font-family:Jura,Roboto,sans-serif;text-align:left}
.ssfb *{text-transform:none!important}
.ssfb .ssfb-hd h2{text-align:left!important}
.ssfb .ssfb-free{text-transform:uppercase!important}
.ssfb .ssfb-f button{letter-spacing:.2px!important}
.ssfb-in{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.25fr);gap:32px;align-items:center;background:#121212;border:1px solid #272727;border-radius:14px;padding:28px 30px}
.ssfb-free{display:inline-block;background:#E09900;color:#000;font:800 11px/1 Sora,Roboto,sans-serif;letter-spacing:1px;padding:5px 8px;border-radius:5px}
.ssfb-hd h2{font:700 30px/1.15 Sora,Roboto,sans-serif!important;color:#fff!important;margin:12px 0 10px!important;letter-spacing:-.5px}
.ssfb-hd p{color:#cfc9c2;font-size:15px;line-height:1.5;margin:0 0 14px}
.ssfb-bul{display:flex;flex-wrap:wrap;gap:8px}
.ssfb-bul span{border:1px solid #333;border-radius:999px;padding:5px 11px;font-size:13px;color:#F4EFE8}
.ssfb-f{display:grid;grid-template-columns:1fr 1fr;gap:12px 14px;margin:0}
.ssfb-wide{grid-column:1/-1}
.ssfb-f label{display:flex;flex-direction:column;gap:5px;margin:0}
.ssfb-f label span{font-size:12.5px;color:#a8a29b}
.ssfb-f input,.ssfb-f textarea{width:100%;box-sizing:border-box;background:#0b0b0b;border:1px solid #333;border-radius:8px;color:#F4EFE8;padding:11px 12px;font:15px/1.35 Jura,Roboto,sans-serif;margin:0;height:auto}
.ssfb-f textarea{resize:vertical;min-height:52px}
.ssfb-f input:focus,.ssfb-f textarea:focus{outline:none;border-color:#E09900}
.ssfb-hp{position:absolute!important;left:-9999px!important;width:1px!important;height:1px!important;opacity:0}
.ssfb-row{display:flex;align-items:center;gap:14px;flex-wrap:wrap}
.ssfb-f button{display:inline-flex;align-items:center;gap:8px;border:0;border-radius:8px;background:#E09900;color:#111;font:700 16px/1 Jura,Roboto,sans-serif;padding:14px 22px;cursor:pointer}
.ssfb-f button:hover{background:#f2a800}
.ssfb-f button[disabled]{opacity:.6;cursor:default}
.ssfb-f button svg{width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2.4;stroke-linecap:round;stroke-linejoin:round}
.ssfb-row small{color:#8f8a84;font-size:12.5px}
.ssfb-msg{font-size:14px;min-height:0}
.ssfb-msg.ok{color:#3fd786;background:rgba(63,215,134,.08);border:1px solid rgba(63,215,134,.35);border-radius:8px;padding:12px 14px}
.ssfb-msg.err{color:#f87171}
@media(max-width:900px){.ssfb-in{grid-template-columns:1fr;gap:20px;padding:22px 18px}.ssfb-hd h2{font-size:24px!important}}
@media(max-width:600px){.ssfb{padding:0 12px}.ssfb-f{grid-template-columns:1fr}.ssfb-f input,.ssfb-f textarea{font-size:16px}.ssfb-f button{width:100%;justify-content:center}}
</style>
<script>
(function(){var root=document.getElementById('ss-fb');if(!root||root.dataset.on)return;root.dataset.on=1;var f=root.querySelector('form'),msg=f.querySelector('.ssfb-msg'),t0=Date.now();
var T=<?php echo wp_json_encode( array( 'ok' => $T['ok'], 'err' => $T['err'], 'fail' => $T['fail'] ) ); ?>;
f.addEventListener('submit',function(e){e.preventDefault();var E=f.elements,link=E['link'].value.trim(),em=E['email'].value.trim();if(link&&!/^https?:\/\//i.test(link))link='https://'+link;
if(!/^https?:\/\/[^\s.]+\.[^\s]{2,}/i.test(link)||!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em)){msg.className='ssfb-wide ssfb-msg err';msg.textContent=T.err;return}
var b=f.querySelector('button');b.disabled=true;msg.className='ssfb-wide ssfb-msg';msg.textContent='';
var fd=new FormData();fd.append('action','ss_fb_submit');fd.append('link',link);fd.append('email',em);fd.append('name',E['name'].value.trim());fd.append('genre',E['genre'].value.trim());fd.append('want',E['want'].value.trim());fd.append('website',E['website'].value);fd.append('lang',f.getAttribute('data-lang'));fd.append('page',location.href.split('#')[0]);fd.append('el',Math.round((Date.now()-t0)/1000));
fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',{method:'POST',body:fd,credentials:'same-origin'}).then(function(r){return r.json()}).then(function(j){if(j&&j.success){if(j.data&&j.data.sid){try{localStorage.setItem('ssb_sid',j.data.sid);localStorage.setItem('ssb_last',j.data.i||1)}catch(x){}}f.querySelectorAll('label,.ssfb-row').forEach(function(x){x.style.display='none'});msg.className='ssfb-wide ssfb-msg ok';msg.textContent=T.ok}else{b.disabled=false;msg.className='ssfb-wide ssfb-msg err';msg.textContent=(j&&j.data&&j.data.m==='invalid')?T.err:T.fail}}).catch(function(){b.disabled=false;msg.className='ssfb-wide ssfb-msg err';msg.textContent=T.fail})});
/* long page: "Write us now" buttons → this form */
document.querySelectorAll('a.btn').forEach(function(a){if(/write us now|get a free demo feedback/i.test(a.textContent)){a.setAttribute('href','#ss-fb');a.removeAttribute('target');a.addEventListener('click',function(ev){ev.preventDefault();root.scrollIntoView({behavior:'smooth',block:'start'});setTimeout(function(){f.elements['link'].focus({preventScroll:true})},500)})}})
})();
</script>
		<?php
		return ob_get_clean();
	}

	function ss_fb_submit() {
		if ( ! empty( $_POST['website'] ) ) wp_send_json_error( array( 'm' => 'invalid' ), 400 );
		$el = isset( $_POST['el'] ) ? (int) $_POST['el'] : 99;
		if ( $el < 3 ) wp_send_json_error( array( 'm' => 'invalid' ), 400 );
		$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		$rk = 'ss_fb_rl_' . md5( $ip );
		$n  = (int) get_transient( $rk );
		if ( $n >= 4 ) wp_send_json_error( array( 'm' => 'rate' ), 429 );
		$link  = isset( $_POST['link'] ) ? esc_url_raw( wp_unslash( $_POST['link'] ), array( 'http', 'https' ) ) : '';
		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$name  = isset( $_POST['name'] ) ? mb_substr( sanitize_text_field( wp_unslash( $_POST['name'] ) ), 0, 80 ) : '';
		$genre = isset( $_POST['genre'] ) ? mb_substr( sanitize_text_field( wp_unslash( $_POST['genre'] ) ), 0, 60 ) : '';
		$want  = isset( $_POST['want'] ) ? mb_substr( sanitize_textarea_field( wp_unslash( $_POST['want'] ) ), 0, 1500 ) : '';
		$lang  = isset( $_POST['lang'] ) ? sanitize_key( wp_unslash( $_POST['lang'] ) ) : '';
		$page  = isset( $_POST['page'] ) ? esc_url_raw( wp_unslash( $_POST['page'] ) ) : '';
		if ( ! $link || ! is_email( $email ) ) wp_send_json_error( array( 'm' => 'invalid' ), 400 );
		if ( $page && 0 !== strpos( $page, home_url() ) ) $page = '';
		set_transient( $rk, $n + 1, HOUR_IN_SECONDS );
		$body = "🎧 FREE FEEDBACK REQUEST\nTrack: " . $link . ( $genre ? "\nGenre: " . $genre : '' ) . ( $want ? "\nFocus: " . $want : '' );
		$sid = bin2hex( random_bytes( 16 ) );
		$id  = wp_insert_post( array( 'post_type' => 'ss_chat', 'post_status' => 'private', 'post_title' => '🎧 Feedback · ' . ( $name ? $name . ' · ' : '' ) . $email ), true );
		if ( is_wp_error( $id ) ) wp_send_json_error( array( 'm' => 'save' ), 500 );
		foreach ( array( 'ss_sid' => $sid, 'ss_email' => $email, 'ss_name' => $name, 'ss_lang' => $lang, 'ss_page' => $page, 'ss_kind' => 'feedback', 'ss_link' => $link, 'ss_seen' => 0 ) as $k => $v ) update_post_meta( $id, $k, $v );
		$i = function_exists( 'ss_chat_add' ) ? ss_chat_add( $id, 'u', $body ) : 1;
		$txt = "🎧 SITE · free feedback request #" . $id . "\n👤 " . ( $name ? $name . ' · ' : '' ) . $email . "\n🌐 " . strtoupper( $lang ? $lang : '—' ) . ( $genre ? ' · ' . $genre : '' ) . "\n🔗 " . $link . ( $want ? "\n💬 " . $want : '' ) . "\n\n↩️ Reply to this message — your notes go to the client by email.";
		$mid = ( function_exists( 'ss_chat_ready' ) && ss_chat_ready() ) ? ss_tg_send( $txt ) : 0;
		if ( $mid ) {
			add_post_meta( $id, 'ss_tg_mid', $mid );
			update_post_meta( $id, 'ss_tg_root', $mid );
		} else {
			wp_mail( get_option( 'admin_email' ), '[Solid Sound] Free feedback request: ' . ( $name ? $name : $email ), $txt . "\n\nAll requests: " . admin_url( 'edit.php?post_type=ss_chat' ), array( 'Reply-To: <' . $email . '>' ) );
		}
		/* confirmation to the producer */
		$T = ss_fb_t();
		wp_mail( $email, $T['h'] . ' — Solid Sound', ( $name ? $name . ",\n\n" : '' ) . $T['ok'] . "\n\nTrack: " . $link . "\n\n— Solid Sound\n" . home_url( '/' ), array( 'Reply-To: <' . get_option( 'admin_email' ) . '>' ) );
		do_action( 'ss_fb_submitted', $id, array( 'email' => $email, 'name' => $name, 'lang' => $lang, 'genre' => $genre ) );
		wp_send_json_success( array( 'sid' => $sid, 'i' => $i ) );
	}
	add_action( 'wp_ajax_ss_fb_submit', 'ss_fb_submit' );
	add_action( 'wp_ajax_nopriv_ss_fb_submit', 'ss_fb_submit' );

	add_shortcode( 'ss_feedback_form', 'ss_fb_form_html' );

	/* place it: top of the long feedback page, bottom of the FREE page */
	add_filter( 'the_content', function ( $content ) {
		static $done = false;
		if ( $done || is_admin() || ! in_the_loop() || ! is_main_query() || false !== strpos( $content, 'id="ss-fb"' ) ) return $content;
		$pid = get_queried_object_id();
		$fp  = get_page_by_path( 'free' );
		if ( 1709 === (int) $pid ) { $done = true; return ss_fb_form_html() . $content; }
		if ( $fp && (int) $pid === (int) $fp->ID ) { $done = true; return $content . ss_fb_form_html(); }
		return $content;
	}, 97 );
}

/* ═══ Solid Sound — 18.10.2026: Clients Chat — Facebook Messenger + Instagram DMs ⇄ Telegram "Clients Chat". Settings: Settings → Clients Chat: Meta ═══ */
if ( ! function_exists( 'ss_meta_token' ) ) {

	function ss_meta_token() { return (string) get_option( 'ss_meta_page_token', '' ); }
	function ss_meta_verify() {
		return 'solidsound-chat'; /* simple to type; incoming POSTs are still protected by the App Secret signature */
		$v = (string) get_option( 'ss_meta_verify', '' );
		if ( ! $v ) { $v = 'ss_' . wp_generate_password( 24, false, false ); update_option( 'ss_meta_verify', $v, false ); }
		return $v;
	}
	function ss_meta_graph( $path, $args = array(), $method = 'GET' ) {
		$tok = ss_meta_token();
		if ( ! $tok ) return null;
		$url = 'https://graph.facebook.com/v21.0/' . ltrim( $path, '/' );
		if ( 'GET' === $method ) {
			$r = wp_remote_get( add_query_arg( array_merge( $args, array( 'access_token' => $tok ) ), $url ), array( 'timeout' => 12 ) );
		} else {
			$r = wp_remote_post( add_query_arg( array( 'access_token' => $tok ), $url ), array( 'timeout' => 12, 'headers' => array( 'Content-Type' => 'application/json' ), 'body' => wp_json_encode( $args ) ) );
		}
		if ( is_wp_error( $r ) ) return array( 'error' => array( 'message' => $r->get_error_message() ) );
		$j = json_decode( wp_remote_retrieve_body( $r ), true );
		return is_array( $j ) ? $j : null;
	}

	/* webhook: GET = verification, POST = messages */
	add_action( 'rest_api_init', function () {
		register_rest_route( 'ss-chat/v1', '/meta', array(
			array( 'methods' => 'GET', 'permission_callback' => '__return_true', 'callback' => function ( WP_REST_Request $r ) {
				if ( 'subscribe' === $r->get_param( 'hub_mode' ) && hash_equals( ss_meta_verify(), (string) $r->get_param( 'hub_verify_token' ) ) ) {
					header( 'Content-Type: text/plain' );
					echo esc_html( (string) $r->get_param( 'hub_challenge' ) );
					exit;
				}
				return new WP_REST_Response( 'forbidden', 403 );
			} ),
			array( 'methods' => 'POST', 'permission_callback' => '__return_true', 'callback' => 'ss_meta_webhook' ),
		) );
	} );

	function ss_meta_webhook( WP_REST_Request $req ) {
		$secret = (string) get_option( 'ss_meta_app_secret', '' );
		$raw    = $req->get_body();
		$sig    = (string) $req->get_header( 'x_hub_signature_256' );
		if ( ! $secret || ! $sig || ! hash_equals( 'sha256=' . hash_hmac( 'sha256', $raw, $secret ), $sig ) ) return new WP_REST_Response( array( 'ok' => false ), 403 );
		$u   = json_decode( $raw, true );
		$obj = isset( $u['object'] ) ? $u['object'] : '';
		$ch  = 'instagram' === $obj ? 'ig' : ( 'page' === $obj ? 'fb' : '' );
		if ( ! $ch || empty( $u['entry'] ) ) return array( 'ok' => true );
		foreach ( (array) $u['entry'] as $e ) {
			foreach ( (array) ( isset( $e['messaging'] ) ? $e['messaging'] : array() ) as $ev ) {
				if ( empty( $ev['message'] ) || ! empty( $ev['message']['is_echo'] ) || empty( $ev['sender']['id'] ) ) continue;
				$from = (string) $ev['sender']['id'];
				$text = isset( $ev['message']['text'] ) ? (string) $ev['message']['text'] : '';
				if ( ! empty( $ev['message']['attachments'] ) ) {
					foreach ( (array) $ev['message']['attachments'] as $a ) {
						$text .= ( $text ? "\n" : '' ) . '[' . ( isset( $a['type'] ) ? $a['type'] : 'file' ) . ']' . ( ! empty( $a['payload']['url'] ) ? ' ' . $a['payload']['url'] : '' );
					}
				}
				if ( '' === trim( $text ) ) continue;
				ss_meta_incoming( $ch, $from, mb_substr( $text, 0, 3000 ) );
			}
		}
		return array( 'ok' => true );
	}

	function ss_meta_incoming( $ch, $from, $text ) {
		$q  = get_posts( array( 'post_type' => 'ss_chat', 'post_status' => 'private', 'numberposts' => 1, 'fields' => 'ids', 'no_found_rows' => true, 'meta_query' => array( array( 'key' => 'ss_ext', 'value' => $ch . ':' . $from ) ) ) );
		$id = $q ? (int) $q[0] : 0;
		$label = 'ig' === $ch ? 'Instagram' : 'Facebook';
		if ( ! $id ) {
			$name = '';
			$p = ss_meta_graph( $from, array( 'fields' => 'ig' === $ch ? 'name,username' : 'first_name,last_name' ) );
			if ( is_array( $p ) && empty( $p['error'] ) ) {
				$name = 'ig' === $ch ? trim( ( isset( $p['name'] ) ? $p['name'] : '' ) . ( ! empty( $p['username'] ) ? ' @' . $p['username'] : '' ) ) : trim( ( isset( $p['first_name'] ) ? $p['first_name'] : '' ) . ' ' . ( isset( $p['last_name'] ) ? $p['last_name'] : '' ) );
			}
			$id = wp_insert_post( array( 'post_type' => 'ss_chat', 'post_status' => 'private', 'post_title' => $label . ' · ' . ( $name ? $name : $from ) ), true );
			if ( is_wp_error( $id ) ) return;
			foreach ( array( 'ss_sid' => bin2hex( random_bytes( 16 ) ), 'ss_ch' => $ch, 'ss_ext' => $ch . ':' . $from, 'ss_name' => $name, 'ss_seen' => 0 ) as $k => $v ) update_post_meta( $id, $k, $v );
			ss_chat_add( $id, 'u', $text );
			$mid = ss_tg_send( ( 'ig' === $ch ? '📸' : '📘' ) . ' ' . strtoupper( $label ) . ' · new message #' . $id . "\n👤 " . ( $name ? $name : 'ID ' . $from ) . "\n\n" . $text . "\n\n↩️ Reply to this message to answer in " . $label . '.' );
			if ( $mid ) { add_post_meta( $id, 'ss_tg_mid', $mid ); update_post_meta( $id, 'ss_tg_root', $mid ); }
			return;
		}
		ss_chat_add( $id, 'u', $text );
		$name = get_post_meta( $id, 'ss_name', true );
		$mid  = ss_tg_send( ( 'ig' === $ch ? '📸' : '📘' ) . ' ' . strtoupper( $label ) . ' #' . $id . ' ' . ( $name ? $name : '' ) . ":\n" . $text, (int) get_post_meta( $id, 'ss_tg_root', true ) );
		if ( $mid ) add_post_meta( $id, 'ss_tg_mid', $mid );
	}

	/* team reply from Telegram → Messenger / Instagram */
	function ss_meta_reply( $id, $text ) {
		$ext = (string) get_post_meta( $id, 'ss_ext', true );
		if ( ! $ext || false === strpos( $ext, ':' ) ) return 'no recipient';
		list( , $to ) = explode( ':', $ext, 2 );
		$j = ss_meta_graph( 'me/messages', array( 'recipient' => array( 'id' => $to ), 'messaging_type' => 'RESPONSE', 'message' => array( 'text' => mb_substr( $text, 0, 1900 ) ) ), 'POST' );
		if ( is_array( $j ) && ! empty( $j['message_id'] ) ) return true;
		return ( is_array( $j ) && ! empty( $j['error']['message'] ) ) ? $j['error']['message'] : 'unknown error';
	}

	/* settings page */
	add_action( 'admin_menu', function () {
		add_options_page( 'Clients Chat: Meta', 'Clients Chat: Meta', 'manage_options', 'ss-clients-chat-meta', 'ss_meta_settings_page' );
	} );
	function ss_meta_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) return;
		$note = ''; $err = '';
		if ( isset( $_POST['ss_meta_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ss_meta_nonce'] ) ), 'ss_meta_save' ) ) {
			$act = isset( $_POST['ss_act'] ) ? sanitize_key( $_POST['ss_act'] ) : '';
			if ( 'save' === $act ) {
				$sec = isset( $_POST['ss_meta_app_secret'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['ss_meta_app_secret'] ) ) ) : '';
				$tok = isset( $_POST['ss_meta_page_token'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['ss_meta_page_token'] ) ) ) : '';
				if ( $sec ) update_option( 'ss_meta_app_secret', $sec, false );
				if ( $tok ) update_option( 'ss_meta_page_token', $tok, false );
				if ( ss_meta_token() ) {
					$me = ss_meta_graph( 'me', array( 'fields' => 'id,name' ) );
					if ( is_array( $me ) && ! empty( $me['id'] ) ) {
						update_option( 'ss_meta_page', array( 'id' => $me['id'], 'name' => isset( $me['name'] ) ? $me['name'] : '', 'ig' => isset( $me['instagram_business_account']['username'] ) ? $me['instagram_business_account']['username'] : '' ), false );
						$sub = ss_meta_graph( $me['id'] . '/subscribed_apps', array( 'subscribed_fields' => 'messages' ), 'POST' );
						$note = ( is_array( $sub ) && ! empty( $sub['success'] ) ) ? 'ok' : 'subfail';
						if ( 'subfail' === $note && ! empty( $sub['error']['message'] ) ) $err = $sub['error']['message'];
					} else {
						/* /me needs pages_read_engagement (App Review) - fall back to the known page id */
						$fpid = '101772505299485';
						update_option( 'ss_meta_page', array( 'id' => $fpid, 'name' => 'Solid Sound Studio', 'ig' => '' ), false );
						$sub = ss_meta_graph( $fpid . '/subscribed_apps', array( 'subscribed_fields' => 'messages,messaging_postbacks' ), 'POST' );
						$note = ( is_array( $sub ) && ! empty( $sub['success'] ) ) ? 'ok' : 'subfail';
						if ( 'subfail' === $note ) $err = ! empty( $sub['error']['message'] ) ? $sub['error']['message'] : ( ! empty( $me['error']['message'] ) ? $me['error']['message'] : '' );
					}
				} elseif ( $sec ) {
					$note = 'secret';
				}
			} elseif ( 'off' === $act ) {
				delete_option( 'ss_meta_page_token' ); delete_option( 'ss_meta_page' ); delete_option( 'ss_meta_app_secret' );
				$note = 'off';
			}
		}
		$pg = (array) get_option( 'ss_meta_page', array() );
		$ok = ss_meta_token() && get_option( 'ss_meta_app_secret' ) && ! empty( $pg['id'] );
		$N = array(
			'ok'       => array( 'success', 'Connected. Facebook page subscribed to messages.' ),
			'subfail'  => array( 'warning', 'Token works, but subscribing the page to messages failed: ' ),
			'badtoken' => array( 'error', 'Meta rejected this Page token: ' ),
			'secret'   => array( 'success', 'App Secret saved.' ),
			'off'      => array( 'info', 'Disconnected.' ),
		);
		echo '<div class="wrap"><h1>Clients Chat: Facebook &amp; Instagram</h1>';
		if ( $note && isset( $N[ $note ] ) ) echo '<div class="notice notice-' . esc_attr( $N[ $note ][0] ) . '"><p>' . esc_html( $N[ $note ][1] . $err ) . '</p></div>';
		echo '<table class="form-table"><tr><th>Status</th><td>' . ( $ok ? '<b style="color:#1a7f37">● Connected</b> — Page: ' . esc_html( $pg['name'] ) . ( ! empty( $pg['ig'] ) ? ' · Instagram: @' . esc_html( $pg['ig'] ) : ' · Instagram account not linked to this Page' ) : '<b style="color:#888">● Not connected</b>' ) . '</td></tr>';
		echo '<tr><th>Callback URL</th><td><input type="text" readonly value="' . esc_attr( rest_url( 'ss-chat/v1/meta' ) ) . '" style="width:460px;max-width:100%" onclick="this.select()"></td></tr>';
		echo '<tr><th>Verify token</th><td><input type="text" readonly value="' . esc_attr( ss_meta_verify() ) . '" style="width:460px;max-width:100%" onclick="this.select()"><p class="description">Copy both into Meta → your app → Webhooks (Page and Instagram).</p></td></tr></table>';
		echo '<form method="post">';
		wp_nonce_field( 'ss_meta_save', 'ss_meta_nonce' );
		$mask = 'style="width:460px;max-width:100%;-webkit-text-security:disc" autocomplete="off" data-lpignore="true" data-1p-ignore spellcheck="false"';
		echo '<table class="form-table"><tr><th>App Secret</th><td><input type="text" name="ss_meta_app_secret" ' . $mask . ' placeholder="' . ( get_option( 'ss_meta_app_secret' ) ? '•••••••• saved' : 'Meta → App settings → Basic → App secret' ) . '"></td></tr>';
		echo '<tr><th>Page access token</th><td><input type="text" name="ss_meta_page_token" ' . $mask . ' placeholder="' . ( ss_meta_token() ? '•••••••• saved' : 'Messenger API settings → Generate token' ) . '"></td></tr></table>';
		echo '<p><button class="button button-primary" name="ss_act" value="save">Save &amp; connect</button> ';
		if ( ss_meta_token() ) echo '<button class="button" name="ss_act" value="off" onclick="return confirm(\'Disconnect Facebook/Instagram?\')">Disconnect</button>';
		echo '</p></form>';
		echo '<h2>How it works</h2><ul style="list-style:disc;padding-left:20px"><li>New Messenger / Instagram DMs appear in the Telegram “Clients Chat” group (📘 Facebook, 📸 Instagram).</li><li>Answer with <b>Reply</b> on that message → the answer is sent back in Messenger / Instagram (within Meta’s 24-hour window).</li><li>Telegram setup: <a href="' . esc_url( admin_url( 'options-general.php?page=ss-clients-chat' ) ) . '">Settings → Clients Chat</a>.</li></ul></div>';
	}
}

/* ═══ Solid Sound — 18.10.2026: Clients Chat — delete (/del) and edit sync for team replies ═══ */
if ( ! function_exists( 'ss_tg_del' ) ) {
	function ss_tg_del( $id, $tgmid, $cmd_mid ) {
		$ch = get_post_meta( $id, 'ss_ch', true );
		if ( 'fb' === $ch || 'ig' === $ch ) {
			ss_tg_send( '⚠️ ' . ( 'ig' === $ch ? 'Instagram' : 'Facebook' ) . ' does not let us delete a sent message automatically. Use “Unsend” in Meta Business Suite / the Instagram app.', $cmd_mid );
			return;
		}
		$map = get_post_meta( $id, 'ss_opmap', true );
		if ( ! is_array( $map ) || empty( $map[ $tgmid ] ) ) { ss_tg_send( '⚠️ Reply /del to YOUR answer (the message you want to remove).', $cmd_mid ); return; }
		$i = (int) $map[ $tgmid ];
		$m = get_post_meta( $id, 'ss_msgs', true );
		$m = array_values( array_filter( is_array( $m ) ? $m : array(), function ( $x ) use ( $i ) { return (int) $x['i'] !== $i; } ) );
		update_post_meta( $id, 'ss_msgs', $m );
		$d = get_post_meta( $id, 'ss_del', true ); $d = is_array( $d ) ? $d : array(); $d[] = $i;
		update_post_meta( $id, 'ss_del', array_values( array_unique( $d ) ) );
		$t = ss_tg_target();
		ss_tg_api( 'setMessageReaction', array( 'chat_id' => $t['chat'], 'message_id' => (int) $cmd_mid, 'reaction' => array( array( 'type' => 'emoji', 'emoji' => '👌' ) ) ) );
		ss_tg_send( '🗑 Removed from the client’s site chat.' . ( (int) get_post_meta( $id, 'ss_seen', true ) < time() - 90 ? ' (If it was already emailed, the email cannot be recalled.)' : '' ), $cmd_mid );
	}
	function ss_tg_edit( $e ) {
		$t = ss_tg_target();
		if ( empty( $t['chat'] ) || empty( $e['chat']['id'] ) || (string) $e['chat']['id'] !== (string) $t['chat'] ) return;
		$mid  = (int) $e['message_id'];
		$text = isset( $e['text'] ) ? trim( (string) $e['text'] ) : '';
		if ( '' === $text ) return;
		$q = get_posts( array( 'post_type' => 'ss_chat', 'post_status' => 'private', 'numberposts' => 1, 'fields' => 'ids', 'meta_key' => 'ss_tg_mid', 'meta_value' => $mid, 'no_found_rows' => true ) );
		if ( ! $q ) return;
		$id  = (int) $q[0];
		$map = get_post_meta( $id, 'ss_opmap', true );
		if ( ! is_array( $map ) || empty( $map[ $mid ] ) ) return;
		$ch = get_post_meta( $id, 'ss_ch', true );
		if ( 'fb' === $ch || 'ig' === $ch ) { ss_tg_send( '⚠️ Edits are not sent to ' . ( 'ig' === $ch ? 'Instagram' : 'Facebook' ) . ' — send a new reply instead.', $mid ); return; }
		$i = (int) $map[ $mid ];
		$m = get_post_meta( $id, 'ss_msgs', true );
		if ( ! is_array( $m ) ) return;
		foreach ( $m as $k => $x ) { if ( (int) $x['i'] === $i ) { $m[ $k ]['t'] = mb_substr( $text, 0, 3000 ); $m[ $k ]['e'] = 1; } }
		update_post_meta( $id, 'ss_msgs', $m );
	}
	add_action( 'admin_init', function () {
		if ( (int) get_option( 'ss_tg_wh_v', 0 ) >= 2 || ! function_exists( 'ss_tg_token' ) || ! ss_tg_token() ) return;
		$r = ss_tg_api( 'setWebhook', array( 'url' => rest_url( 'ss-chat/v1/tg' ), 'secret_token' => (string) get_option( 'ss_tg_secret', '' ), 'allowed_updates' => array( 'message', 'edited_message', 'callback_query' ) ) );
		if ( $r && ! empty( $r['ok'] ) ) update_option( 'ss_tg_wh_v', 2, false );
	} );
}

/* ═══ Solid Sound — 18.10.2026: Clients Chat — email thread for every chat + one reply address ═══ */
if ( ! function_exists( 'ss_reply_email' ) ) {
	function ss_reply_email() { return 'info@solid-sound.net'; } /* copies go to solidsoundb@gmail.com (adm.tools redirect) */
	function ss_chat_subj( $id ) { return 'Solid Sound — your question #' . (int) $id; }
	function ss_chat_mail_t( $l ) {
		$T = array(
			'en' => array( 'Hi', 'We got your message and will answer here as soon as possible — usually within a few hours.', 'Your message', 'You can simply reply to this email to add details or files.', 'Continue the chat on the site' ),
			'ua' => array( 'Привіт', 'Ми отримали ваше повідомлення і відповімо в цьому листі якнайшвидше — зазвичай протягом кількох годин.', 'Ваше повідомлення', 'Можете просто відповісти на цей лист, щоб додати деталі чи файли.', 'Продовжити чат на сайті' ),
			'de' => array( 'Hallo', 'Wir haben deine Nachricht erhalten und antworten hier so schnell wie möglich — meist innerhalb weniger Stunden.', 'Deine Nachricht', 'Du kannst einfach auf diese E-Mail antworten, um Details oder Dateien zu ergänzen.', 'Chat auf der Website fortsetzen' ),
			'fr' => array( 'Salut', 'On a bien reçu ton message et on te répond ici au plus vite — généralement en quelques heures.', 'Ton message', 'Tu peux simplement répondre à cet e-mail pour ajouter des détails ou des fichiers.', 'Continuer le chat sur le site' ),
			'pt' => array( 'Olá', 'Recebemos sua mensagem e vamos responder aqui o quanto antes — normalmente em poucas horas.', 'Sua mensagem', 'Você pode simplesmente responder a este e-mail para adicionar detalhes ou arquivos.', 'Continuar o chat no site' ),
		);
		return isset( $T[ $l ] ) ? $T[ $l ] : $T['en'];
	}
	/* new chat → confirmation email to the client = the email thread already exists */
	add_action( 'ss_chat_started', function ( $id, $d ) {
		$email = isset( $d['email'] ) ? $d['email'] : '';
		if ( ! is_email( $email ) || get_post_meta( $id, 'ss_mail_thread', true ) ) return;
		$m   = get_post_meta( $id, 'ss_msgs', true );
		$txt = ( is_array( $m ) && ! empty( $m[0]['t'] ) ) ? $m[0]['t'] : '';
		$T    = ss_chat_mail_t( isset( $d['lang'] ) ? $d['lang'] : '' );
		$name = isset( $d['name'] ) ? $d['name'] : '';
		$page = get_post_meta( $id, 'ss_page', true );
		$body = $T[0] . ( $name ? ' ' . $name : '' ) . ",\n\n" . $T[1] . "\n\n" . $T[2] . ":\n> " . str_replace( "\n", "\n> ", $txt ) . "\n\n" . $T[3] . "\n" . ( $page ? $T[4] . ': ' . $page . "\n" : '' ) . "\n— Solid Sound\n" . home_url( '/' );
		update_post_meta( $id, 'ss_mail_thread', 1 );
		/* send after the AJAX answer is flushed, so the chat opens instantly */
		add_action( 'shutdown', function () use ( $email, $id, $body ) {
			if ( function_exists( 'fastcgi_finish_request' ) ) fastcgi_finish_request();
			wp_mail( $email, ss_chat_subj( $id ), $body, array( 'Reply-To: Solid Sound <' . ss_reply_email() . '>', 'Bcc: ' . ss_reply_email() ) );
		} );
	}, 10, 2 );
}

/* ═══ Solid Sound — 18.10.2026: GA4 + key events (demo play, cart, purchase, leads), consent-aware ═══ */
if ( ! function_exists( 'ss_ga4_id' ) ) {
	function ss_ga4_id() {
		$id = strtoupper( trim( (string) get_option( 'ss_ga4_id', '' ) ) );
		return preg_match( '/^G-[A-Z0-9]{4,15}$/', $id ) ? $id : '';
	}

	/* Settings → Analytics */
	add_action( 'admin_menu', function () {
		add_options_page( 'Analytics', 'Analytics (GA4)', 'manage_options', 'ss-analytics', function () {
			if ( isset( $_POST['ss_ga4_id'] ) && check_admin_referer( 'ss_ga4' ) ) {
				update_option( 'ss_ga4_id', sanitize_text_field( wp_unslash( $_POST['ss_ga4_id'] ) ) );
				echo '<div class="notice notice-' . ( ss_ga4_id() ? 'success' : 'warning' ) . '"><p>' . ( ss_ga4_id() ? 'Saved. Google Analytics is active.' : 'Saved, but the ID must look like G-XXXXXXXXXX.' ) . '</p></div>';
			}
			echo '<div class="wrap"><h1>Google Analytics 4</h1><form method="post">';
			wp_nonce_field( 'ss_ga4' );
			echo '<table class="form-table"><tr><th><label for="ss_ga4_id">Measurement ID</label></th><td><input id="ss_ga4_id" name="ss_ga4_id" class="regular-text" placeholder="G-XXXXXXXXXX" value="' . esc_attr( get_option( 'ss_ga4_id', '' ) ) . '"><p class="description">analytics.google.com → Admin → Data streams → your web stream → Measurement ID.</p></td></tr></table>';
			echo '<p>Status: <b>' . ( ss_ga4_id() ? '● Active' : '○ Not set' ) . '</b></p>';
			submit_button( 'Save' );
			echo '<h2>What is tracked</h2><ul style="list-style:disc;padding-left:20px"><li>page views, view_item (product page)</li><li><b>play_demo</b> — audio preview started (also Meta Pixel “PlayDemo”)</li><li>add_to_cart, begin_checkout, purchase (with revenue)</li><li><b>generate_lead</b> — site chat started / free feedback request / free pack (also Meta Pixel “Lead”)</li></ul><p>Respects the cookie banner: nothing is stored until the visitor accepts.</p></form></div>';
		} );
	} );

	add_action( 'wp_head', function () {
		if ( is_admin() ) return;
		$id = ss_ga4_id();
		$cur = function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() : 'USD';
		$vi = null;
		if ( function_exists( 'is_product' ) && is_product() ) {
			$p = wc_get_product( get_queried_object_id() );
			if ( $p ) $vi = array( 'item_id' => (string) $p->get_id(), 'item_name' => $p->get_name(), 'price' => (float) $p->get_price() );
		}
		?>
<script id="ss-ga4">
window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}
(function(){var ok=/(?:^|;\s*)ss_consent=yes/.test(document.cookie);
gtag('consent','default',{analytics_storage:ok?'granted':'denied',ad_storage:ok?'granted':'denied',ad_user_data:ok?'granted':'denied',ad_personalization:ok?'granted':'denied',wait_for_update:500});
var ID=<?php echo wp_json_encode( $id ); ?>,CUR=<?php echo wp_json_encode( $cur ); ?>,VI=<?php echo wp_json_encode( $vi ); ?>;
if(ID){var s=document.createElement('script');s.async=1;s.src='https://www.googletagmanager.com/gtag/js?id='+ID;document.head.appendChild(s);gtag('js',new Date());gtag('config',ID);if(VI)gtag('event','view_item',{currency:CUR,value:VI.price,items:[VI]})}
function ev(n,p){try{if(ID)gtag('event',n,p||{})}catch(e){}}
function px(n,p,c){try{if(window.fbq)fbq(c?'trackCustom':'track',n,p||{})}catch(e){}}
window.ssTrack=ev;
/* consent banner click → update */
document.addEventListener('click',function(e){if(e.target.closest&&e.target.closest('.ss-cc button')){setTimeout(function(){var y=/(?:^|;\s*)ss_consent=yes/.test(document.cookie);gtag('consent','update',{analytics_storage:y?'granted':'denied',ad_storage:y?'granted':'denied',ad_user_data:y?'granted':'denied',ad_personalization:y?'granted':'denied'})},150)}},true);
/* demo plays (SSP player) — once per track per page */
var played={};function wrapSSP(){var S=window.SSP;if(!S||!S.play||S.__ssga)return!!(S&&S.__ssga);var o=S.play;S.play=function(t){try{var k=t&&(t.pid||t.src);if(k&&!played[k]){played[k]=1;ev('play_demo',{item_id:t.pid?String(t.pid):'',item_name:t.t||'',content_type:'audio_preview'});px('PlayDemo',{content_ids:t.pid?[String(t.pid)]:[],content_name:t.t||''},1)}}catch(e){}return o.apply(this,arguments)};S.__ssga=1;return true}
if(!wrapSSP()){if(window.ssOnDom){ssOnDom(wrapSSP)}else{var tries=0,iv=setInterval(function(){if(wrapSSP()||++tries>40)clearInterval(iv)},500)}}
/* add to cart: AJAX buttons + single product form */
document.addEventListener('DOMContentLoaded',function(){if(window.jQuery)jQuery(document.body).on('added_to_cart',function(e,f,h,b){var id=b&&b.data?String(b.data('product_id')||''):'';ev('add_to_cart',{currency:CUR,items:[{item_id:id}]})});
document.querySelectorAll('form.cart').forEach(function(f){f.addEventListener('submit',function(){ev('add_to_cart',{currency:CUR,value:VI?VI.price:undefined,items:VI?[VI]:[]})})});
if(document.body.classList.contains('woocommerce-checkout')&&!document.body.classList.contains('woocommerce-order-received'))ev('begin_checkout',{currency:CUR})});
/* leads: site chat / free feedback form */
if(window.fetch&&!window.fetch.__ssga){var F=window.fetch;window.fetch=function(u,o){var a='';try{if(o&&o.body&&o.body.get)a=o.body.get('action')||''}catch(e){}var r=F.apply(this,arguments);if(a==='ss_chat_start'||a==='ss_fb_submit'||a==='ss_bot_lead'){r.then(function(x){return x.clone().json()}).then(function(j){if(j&&j.success){var m=a==='ss_fb_submit'?'free_feedback':'site_chat';ev('generate_lead',{method:m});px('Lead',{content_name:m})}}).catch(function(){})}return r};window.fetch.__ssga=1}
})();
</script>
		<?php
	}, 2 );

	/* purchase — once per order, on the thank-you page */
	add_action( 'woocommerce_thankyou', function ( $oid ) {
		if ( ! $oid || ! ss_ga4_id() ) return;
		$o = wc_get_order( $oid );
		if ( ! $o || $o->get_meta( '_ss_ga4_sent' ) ) return;
		$items = array();
		foreach ( $o->get_items() as $it ) $items[] = array( 'item_id' => (string) $it->get_product_id(), 'item_name' => $it->get_name(), 'quantity' => (int) $it->get_quantity(), 'price' => (float) $o->get_item_total( $it, false ) );
		$d = array( 'transaction_id' => (string) $o->get_order_number(), 'value' => (float) $o->get_total(), 'currency' => $o->get_currency(), 'items' => $items );
		echo '<script>if(window.ssTrack)ssTrack("purchase",' . wp_json_encode( $d ) . ');</script>';
		$o->update_meta_data( '_ss_ga4_sent', 1 );
		$o->save();
	}, 20 );
}

/* ═══ Solid Sound — 18.10.2026: FREE zone → Brevo list "Free Pack" + 3-email welcome series (sent via info@ SMTP) ═══ */
if ( ! function_exists( 'ss_brevo_key' ) ) {
	function ss_brevo_key() {
		$own = (string) get_option( 'ss_brevo_key', '' );
		if ( $own ) return $own;
		$o = get_option( 'sib_main_option' );
		if ( is_array( $o ) && ! empty( $o['access_key'] ) ) return $o['access_key'];
		foreach ( array( 'sib_access_key', 'brevo_api_key' ) as $k ) { $v = get_option( $k ); if ( $v && is_string( $v ) ) return $v; }
		return '';
	}
	function ss_brevo_api( $method, $path, $body = null ) {
		$key = ss_brevo_key();
		if ( ! $key ) return array( 'code' => 0, 'body' => null );
		$a = array( 'method' => $method, 'timeout' => 15, 'headers' => array( 'api-key' => $key, 'accept' => 'application/json', 'content-type' => 'application/json' ) );
		if ( null !== $body ) $a['body'] = wp_json_encode( $body );
		$r = wp_remote_request( 'https://api.brevo.com/v3/' . ltrim( $path, '/' ), $a );
		if ( is_wp_error( $r ) ) return array( 'code' => 0, 'body' => $r->get_error_message() );
		return array( 'code' => (int) wp_remote_retrieve_response_code( $r ), 'body' => json_decode( wp_remote_retrieve_body( $r ), true ) );
	}
	/* list "Free Pack" (created once) */
	function ss_brevo_free_list() {
		$id = (int) get_option( 'ss_brevo_list_free', 0 );
		if ( $id ) return $id;
		$f = ss_brevo_api( 'GET', 'contacts/folders?limit=10&offset=0' );
		$fid = ! empty( $f['body']['folders'][0]['id'] ) ? (int) $f['body']['folders'][0]['id'] : 0;
		if ( ! $fid ) { $c = ss_brevo_api( 'POST', 'contacts/folders', array( 'name' => 'Solid Sound' ) ); $fid = ! empty( $c['body']['id'] ) ? (int) $c['body']['id'] : 0; }
		if ( ! $fid ) return 0;
		$l = ss_brevo_api( 'POST', 'contacts/lists', array( 'name' => 'Free Pack', 'folderId' => $fid ) );
		$id = ! empty( $l['body']['id'] ) ? (int) $l['body']['id'] : 0;
		if ( $id ) update_option( 'ss_brevo_list_free', $id, false );
		return $id;
	}

	/* 10% for returning clients only: coupon CLIENT10 restricted to emails of everyone who already paid an order */
	function ss_client_emails() {
		if ( ! function_exists( 'wc_get_orders' ) ) return array();
		$ids = wc_get_orders( array( 'status' => array( 'wc-completed', 'wc-processing' ), 'limit' => -1, 'return' => 'ids' ) );
		$e = array();
		foreach ( $ids as $id ) { $o = wc_get_order( $id ); if ( $o && is_email( $o->get_billing_email() ) ) $e[ strtolower( $o->get_billing_email() ) ] = 1; }
		return array_keys( $e );
	}
	function ss_client_coupon() {
		if ( ! class_exists( 'WC_Coupon' ) ) return array( 'CLIENT10', 0 );
		$code = 'SOLID-SOUND-10';
		$id = wc_get_coupon_id_by_code( $code );
		if ( ! $id ) $id = wc_get_coupon_id_by_code( 'CLIENT10' );
		$c = new WC_Coupon( $id ? $id : 0 );
		$c->set_code( $code );
		if ( ! $id ) {
			$c->set_discount_type( 'percent' );
			$c->set_amount( 10 );
			$c->set_individual_use( true );
			$c->set_description( 'Returning clients — 10% off templates, presets and services (email-restricted, auto-updated)' );
		}
		/* keep emails added by hand in the coupon (Usage restriction → Allowed emails) */
		$emails = array_values( array_unique( array_merge( array_diff( array_map( 'strtolower', (array) $c->get_email_restrictions() ), array( 'nobody@solid-sound.net' ) ), ss_client_emails() ) ) );
		$c->set_email_restrictions( $emails ? $emails : array( 'nobody@solid-sound.net' ) );
		$c->save();
		return array( $code, count( $emails ) );
	}
	/* every new paid order → buyer becomes a "returning client" for next time */
	add_action( 'woocommerce_order_status_completed', function () { if ( ! get_transient( 'ss_cc_lock' ) ) { set_transient( 'ss_cc_lock', 1, 60 ); ss_client_coupon(); } } );

	/* magic link: opens free downloads on any device */
	function ss_free_magic( $email ) { return add_query_arg( 'ssk', rawurlencode( base64_encode( strtolower( $email ) . '|' . ss_free_sig( $email ) ) ), home_url( '/free/' ) ); }
	function ss_unsub_link( $uid ) { return add_query_arg( array( 'ss_unsub' => (int) $uid, 't' => substr( hash_hmac( 'sha256', 'unsub' . (int) $uid, wp_salt( 'auth' ) ), 0, 20 ) ), home_url( '/' ) ); }
	add_action( 'init', function () {
		if ( ! empty( $_GET['ssk'] ) && function_exists( 'ss_free_sig' ) ) {
			$v = sanitize_text_field( wp_unslash( $_GET['ssk'] ) );
			$p = explode( '|', (string) base64_decode( $v ) );
			if ( 2 === count( $p ) && is_email( $p[0] ) && hash_equals( ss_free_sig( $p[0] ), $p[1] ) ) {
				setcookie( 'ss_free', $v, array( 'expires' => time() + 2 * YEAR_IN_SECONDS, 'path' => '/', 'secure' => is_ssl(), 'httponly' => true, 'samesite' => 'Lax' ) );
				$_COOKIE['ss_free'] = $v;
			}
			wp_safe_redirect( remove_query_arg( 'ssk' ) );
			exit;
		}
		if ( isset( $_GET['ss_unsub'], $_GET['t'] ) ) {
			$uid = (int) $_GET['ss_unsub'];
			if ( $uid && hash_equals( substr( hash_hmac( 'sha256', 'unsub' . $uid, wp_salt( 'auth' ) ), 0, 20 ), (string) $_GET['t'] ) ) {
				update_user_meta( $uid, 'ss_unsub', current_time( 'mysql' ) );
				$u = get_userdata( $uid );
				if ( $u ) ss_brevo_api( 'PUT', 'contacts/' . rawurlencode( $u->user_email ), array( 'emailBlacklisted' => true ) );
				wp_die( '<h2>You are unsubscribed.</h2><p>You will not receive Solid Sound emails anymore. Your free downloads stay available.</p><p><a href="' . esc_url( home_url( '/' ) ) . '">solid-sound.net</a></p>', 'Unsubscribed', array( 'response' => 200 ) );
			}
		}
	}, 1 );

	/* emails */
	function ss_drip_t( $step, $lang ) {
		$T = array(
			1 => array(
				'en' => array( 'Your free Solid Sound downloads are unlocked', "Your free access is active. Every free template, preset and sample pack is waiting for you — and new drops land there first.", 'Open free downloads', 'Tip: save this email — the button opens your free zone on any device.' ),
				'ua' => array( 'Безкоштовні файли Solid Sound відкрито', 'Твій безкоштовний доступ активний. Усі безкоштовні шаблони, пресети та семпли вже чекають — і нові релізи з’являються там першими.', 'Відкрити безкоштовні файли', 'Порада: збережи цей лист — кнопка відкриває безкоштовну зону з будь-якого пристрою.' ),
			),
			2 => array(
				'en' => array( 'One mix trick that makes tracks louder (without distortion)', "Quick one: before you push the limiter, cut everything below 30 Hz on every channel except kick and bass, and keep the kick and bass peaks from hitting at the same time (short sidechain, 2–4 dB). You'll get 1–3 dB of clean loudness for free.\n\nWant ears on your track? We give free feedback on mix, arrangement and sound.", 'Get free track feedback', '' ),
				'ua' => array( 'Один прийом, щоб трек звучав гучніше (без перевантаження)', "Коротко: перед лімітером зріж усе нижче 30 Гц на всіх каналах, крім бочки й басу, і не давай пікам бочки та басу збігатися (короткий сайдчейн, 2–4 дБ). Отримаєш 1–3 дБ чистої гучності.\n\nХочеш, щоб твій трек послухали? Даємо безкоштовний фідбек щодо зведення, аранжування та звуку.", 'Отримати безкоштовний фідбек', '' ),
			),
			3 => array(
				'en' => array( 'Ready for the next step? Templates & studio services', "If the free stuff helped — our full Ableton templates show exactly how finished, release-ready tracks are built: every channel, every effect, every automation.\n\nAnd if your own track needs a pro finish, we do mixing & mastering from 15 €.", 'See templates & services', 'New free drops always land in your free zone first.' ),
				'ua' => array( 'Готовий до наступного кроку? Шаблони та студійні послуги', "Якщо безкоштовне допомогло — повні шаблони Ableton показують, як зроблені готові до релізу треки: кожен канал, кожен ефект, кожна автоматизація.\n\nА якщо твоєму треку потрібен професійний звук — робимо зведення та мастеринг від 15 €.", 'Шаблони та послуги', 'Нові безкоштовні релізи завжди з’являються першими у твоїй безкоштовній зоні.' ),
			),
		);
		return isset( $T[ $step ][ $lang ] ) ? $T[ $step ][ $lang ] : $T[ $step ]['en'];
	}
	function ss_drip_send( $uid, $step ) {
		$u = get_userdata( $uid );
		if ( ! $u || get_user_meta( $uid, 'ss_unsub', true ) || get_user_meta( $uid, 'ss_drip_' . $step, true ) ) return;
		$lang = get_user_meta( $uid, 'ss_free_lang', true );
		$t = ss_drip_t( $step, 'ua' === $lang ? 'ua' : 'en' );
		$url = 1 === $step ? ss_free_magic( $u->user_email ) : ( 2 === $step ? home_url( '/free-demo-feedback/' ) : home_url( '/templates/' ) );
		$name = $u->first_name ? $u->first_name : $u->display_name;
		$hi = ( 'ua' === $lang ? 'Привіт' : 'Hi' ) . ( $name ? ', ' . esc_html( $name ) : '' ) . '!';
		$html = '<div style="background:#0d0d0d;padding:28px 12px;font-family:Arial,Helvetica,sans-serif"><div style="max-width:560px;margin:0 auto;background:#161616;border:1px solid #2a2a2a;border-radius:14px;padding:28px;color:#eee">'
			. '<div style="font-weight:700;letter-spacing:2px;color:#E09900;font-size:13px;margin-bottom:18px">SØLID SØUND STUDIO</div>'
			. '<p style="font-size:16px;margin:0 0 14px">' . $hi . '</p>'
			. '<p style="font-size:15px;line-height:1.6;margin:0 0 22px">' . nl2br( esc_html( $t[1] ) ) . '</p>'
			. '<p style="margin:0 0 22px"><a href="' . esc_url( $url ) . '" style="display:inline-block;background:#E09900;color:#111;text-decoration:none;font-weight:700;padding:13px 22px;border-radius:10px">' . esc_html( $t[2] ) . ' →</a></p>'
			. ( $t[3] ? '<p style="font-size:13px;color:#9a948d;margin:0 0 6px">' . esc_html( $t[3] ) . '</p>' : '' )
			. '</div><p style="text-align:center;font-size:12px;color:#777;margin:16px 0 0">Solid Sound Studio · <a style="color:#999" href="' . esc_url( home_url( '/' ) ) . '">solid-sound.net</a> · <a style="color:#999" href="' . esc_url( ss_unsub_link( $uid ) ) . '">' . ( 'ua' === $lang ? 'Відписатися' : 'Unsubscribe' ) . '</a></p></div>';
		$h = array( 'Content-Type: text/html; charset=UTF-8', 'List-Unsubscribe: <' . ss_unsub_link( $uid ) . '>' );
		if ( function_exists( 'ss_reply_email' ) ) $h[] = 'Reply-To: Solid Sound <' . ss_reply_email() . '>';
		if ( wp_mail( $u->user_email, $t[0], $html, $h ) ) update_user_meta( $uid, 'ss_drip_' . $step, current_time( 'mysql' ) );
	}
	add_action( 'ss_drip', 'ss_drip_send', 10, 2 );

	/* trigger: FREE zone opt-in (meta ss_free_optin written by ss_free_join) */
	$ss_optin = function ( $mid, $uid, $key ) {
		if ( 'ss_free_optin' !== $key || get_user_meta( $uid, 'ss_drip_started', true ) ) return;
		update_user_meta( $uid, 'ss_drip_started', current_time( 'mysql' ) );
		add_action( 'shutdown', function () use ( $uid ) {
			if ( function_exists( 'fastcgi_finish_request' ) ) fastcgi_finish_request();
			$u = get_userdata( $uid );
			if ( $u && ss_brevo_key() ) {
				$lid = ss_brevo_free_list();
				$c = array( 'email' => $u->user_email, 'attributes' => array( 'FIRSTNAME' => (string) $u->first_name ), 'updateEnabled' => true );
				if ( $lid ) $c['listIds'] = array( $lid );
				$r = ss_brevo_api( 'POST', 'contacts', $c );
				update_user_meta( $uid, 'ss_brevo', (int) $r['code'] );
			}
			ss_drip_send( $uid, 1 );
			wp_schedule_single_event( time() + 2 * DAY_IN_SECONDS, 'ss_drip', array( $uid, 2 ) );
			wp_schedule_single_event( time() + 5 * DAY_IN_SECONDS, 'ss_drip', array( $uid, 3 ) );
		} );
	};
	add_action( 'added_user_meta', $ss_optin, 10, 3 );
	add_action( 'updated_user_meta', $ss_optin, 10, 3 );

	/* status: Settings → Free Pack funnel */
	add_action( 'admin_menu', function () {
		add_options_page( 'Free Pack funnel', 'Free Pack funnel', 'manage_options', 'ss-free-funnel', function () {
			if ( isset( $_POST['ss_brevo_key'] ) && check_admin_referer( 'ss_brevo_key' ) ) { $nk = trim( sanitize_text_field( wp_unslash( $_POST['ss_brevo_key'] ) ) ); if ( $nk ) update_option( 'ss_brevo_key', $nk, false ); }
			$key = ss_brevo_key();
			$acc = $key ? ss_brevo_api( 'GET', 'account' ) : array( 'code' => 0 );
			$lid = $key && 200 === $acc['code'] ? ss_brevo_free_list() : 0;
			$cnt = $lid ? ss_brevo_api( 'GET', 'contacts/lists/' . $lid ) : null;
			$cc = ss_client_coupon();
			$subs = count( get_users( array( 'meta_key' => 'ss_free_optin', 'fields' => 'ID' ) ) );
			echo '<div class="wrap"><h1>Free Pack funnel</h1><table class="widefat striped" style="max-width:720px"><tbody>';
			echo '<tr><td>Brevo connection</td><td><b>' . ( 200 === $acc['code'] ? '● Connected (' . esc_html( $acc['body']['email'] ?? '' ) . ')' : '○ Not connected (code ' . (int) $acc['code'] . ')' ) . '</b></td></tr>';
			echo '<tr><td>Brevo list “Free Pack”</td><td>' . ( $lid ? 'ID ' . $lid . ' · ' . (int) ( $cnt['body']['uniqueSubscribers'] ?? $cnt['body']['totalSubscribers'] ?? 0 ) . ' contacts' : '—' ) . '</td></tr>';
			echo '<tr><td>FREE zone subscribers on the site</td><td>' . (int) $subs . '</td></tr>';
			echo '<tr><td>Welcome series</td><td>#1 instantly: free downloads link · #2 after 2 days: mix tip + free feedback · #3 after 5 days: templates &amp; studio services (no discount)</td></tr>';
			echo '<tr><td>Returning clients −10%</td><td>Code <b>' . esc_html( $cc[0] ) . '</b> · works only for ' . (int) $cc[1] . ' emails that already paid an order (list updates automatically)</td></tr>';
			echo '<tr><td>Sender</td><td>Solid Sound &lt;info@solid-sound.net&gt; (FluentSMTP) · every email has an unsubscribe link</td></tr>';
			echo '</tbody></table>';
			echo '<form method="post" style="margin-top:18px;max-width:720px"><h2>Brevo API key</h2><p>Brevo → SMTP &amp; API → API Keys → Generate (Full access). Paste it here once.</p>';
			wp_nonce_field( 'ss_brevo_key' );
			echo '<input type="text" name="ss_brevo_key" autocomplete="off" style="width:100%;-webkit-text-security:disc" placeholder="' . ( get_option( 'ss_brevo_key' ) ? '•••••••• saved' : 'xkeysib-…' ) . '"> ';
			submit_button( 'Save & check' );
			echo '</form></div>';
		} );
	} );
}

/* ═══ Solid Sound — 18.10.2026: FREE zone — follow block (Instagram + YouTube) shown after access is unlocked ═══ */
if ( ! function_exists( 'ss_free_follow_html' ) ) {
	function ss_free_follow_html() {
		$l = function_exists( 'ss_free_lang' ) ? ss_free_lang() : 'en';
		$T = array(
			'en' => array( 'More free stuff on our socials', 'Behind-the-scenes, quick mixing tips, new free drops and track breakdowns — they land on Instagram and YouTube first.', 'Follow on Instagram', 'Subscribe on YouTube' ),
			'ua' => array( 'Більше корисного — у наших соцмережах', 'Закулісся студії, швидкі поради зі зведення, нові безкоштовні релізи та розбори треків — спочатку в Instagram і на YouTube.', 'Підписатися в Instagram', 'Підписатися на YouTube' ),
			'de' => array( 'Mehr Gratis-Content auf unseren Kanälen', 'Studio-Einblicke, schnelle Mixing-Tipps, neue Free-Drops und Track-Breakdowns — zuerst auf Instagram und YouTube.', 'Auf Instagram folgen', 'YouTube abonnieren' ),
			'fr' => array( 'Encore plus de contenu gratuit sur nos réseaux', 'Coulisses du studio, astuces de mixage, nouveaux packs gratuits et analyses de tracks — d’abord sur Instagram et YouTube.', 'Suivre sur Instagram', 'S’abonner sur YouTube' ),
			'pt' => array( 'Mais conteúdo grátis nas nossas redes', 'Bastidores do estúdio, dicas rápidas de mixagem, novos drops grátis e análises de faixas — primeiro no Instagram e no YouTube.', 'Seguir no Instagram', 'Inscrever-se no YouTube' ),
		);
		$t = isset( $T[ $l ] ) ? $T[ $l ] : $T['en'];
		$ig = 'https://www.instagram.com/solid.sound.studio/';
		$yt = 'https://www.youtube.com/channel/UCeU2yrebmva2mZE4cME4-pg?sub_confirmation=1';
		ob_start(); ?>
<div class="ssfw"><div class="ssfw-t"><b><?php echo esc_html( $t[0] ); ?></b><span><?php echo esc_html( $t[1] ); ?></span></div><div class="ssfw-b">
<a class="ssfw-ig" href="<?php echo esc_url( $ig ); ?>" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg><?php echo esc_html( $t[2] ); ?></a>
<a class="ssfw-yt" href="<?php echo esc_url( $yt ); ?>" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="4"/><path d="M10 9l5 3-5 3z" fill="currentColor" stroke="none"/></svg><?php echo esc_html( $t[3] ); ?></a></div></div>
<style>.ssfw{display:flex;align-items:center;justify-content:space-between;gap:18px;flex-wrap:wrap;max-width:1100px;margin:0 auto 28px;padding:18px 20px;border:1px solid #2c2c2c;border-radius:14px;background:linear-gradient(90deg,rgba(224,153,0,.10),rgba(255,255,255,.02))}.ssfw-t{flex:1 1 320px}.ssfw-t b{display:block;font:700 17px/1.3 Sora,Roboto,sans-serif;color:#fff;margin-bottom:4px}.ssfw-t span{color:#cfc9c2;font-size:14px;line-height:1.5}.ssfw-b{display:flex;gap:10px;flex-wrap:wrap}.ssfw-b a{display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border-radius:999px;font-weight:700;font-size:14px;text-decoration:none!important;color:#fff!important;transition:transform .15s}.ssfw-b a:hover{transform:translateY(-1px)}.ssfw-b svg{width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2}.ssfw-ig{background:linear-gradient(45deg,#f58529,#dd2a7b,#8134af)}.ssfw-yt{background:#e62117}@media(max-width:600px){.ssfw-b{width:100%}.ssfw-b a{flex:1;justify-content:center}}</style>
		<?php
		return ob_get_clean();
	}
	add_filter( 'the_content', function ( $content ) {
		if ( is_admin() || ! in_the_loop() || ! is_main_query() || ! function_exists( 'ss_free_has_access' ) || ! ss_free_has_access() ) return $content;
		$fp = get_page_by_path( 'free' );
		$pid = get_queried_object_id();
		$tr = ( $fp && function_exists( 'pll_get_post_translations' ) ) ? array_map( 'intval', (array) pll_get_post_translations( $fp->ID ) ) : array();
		if ( ! $fp || ( (int) $pid !== (int) $fp->ID && ! in_array( (int) $pid, $tr, true ) ) ) return $content;
		return ss_free_follow_html() . $content;
	}, 96 );
}

/* ═══ Solid Sound — 18.10.2026: Brevo list "Clients" (from coupon SOLID-SOUND-10 allowed emails) + draft campaign (owner sends it in Brevo) ═══ */
if ( ! function_exists( 'ss_clients_prepare' ) ) {
	function ss_clients_prepare() {
		$out = array();
		$id = function_exists( 'wc_get_coupon_id_by_code' ) ? wc_get_coupon_id_by_code( 'SOLID-SOUND-10' ) : 0;
		if ( ! $id ) return array( 'No coupon SOLID-SOUND-10' );
		$c = new WC_Coupon( $id );
		$emails = array_values( array_filter( array_map( 'strtolower', (array) $c->get_email_restrictions() ), function ( $e ) { return is_email( $e ) && 'nobody@solid-sound.net' !== $e; } ) );
		/* first names from WooCommerce orders when known */
		$names = array();
		if ( function_exists( 'wc_get_orders' ) ) {
			foreach ( wc_get_orders( array( 'limit' => -1, 'status' => array( 'wc-completed', 'wc-processing' ) ) ) as $o ) {
				$e = strtolower( $o->get_billing_email() );
				if ( $e && $o->get_billing_first_name() ) $names[ $e ] = $o->get_billing_first_name();
			}
		}
		/* list */
		$lid = (int) get_option( 'ss_brevo_list_clients', 0 );
		if ( ! $lid ) {
			$f = ss_brevo_api( 'GET', 'contacts/folders?limit=10&offset=0' );
			$fid = ! empty( $f['body']['folders'][0]['id'] ) ? (int) $f['body']['folders'][0]['id'] : 0;
			$l = ss_brevo_api( 'POST', 'contacts/lists', array( 'name' => 'Clients', 'folderId' => $fid ) );
			$lid = ! empty( $l['body']['id'] ) ? (int) $l['body']['id'] : 0;
			if ( $lid ) update_option( 'ss_brevo_list_clients', $lid, false );
		}
		if ( ! $lid ) return array( 'Could not create Brevo list' );
		$n = 0;
		foreach ( $emails as $e ) {
			$b = array( 'email' => $e, 'listIds' => array( $lid ), 'updateEnabled' => true );
			if ( ! empty( $names[ $e ] ) ) $b['attributes'] = array( 'FIRSTNAME' => $names[ $e ] );
			$r = ss_brevo_api( 'POST', 'contacts', $b );
			if ( in_array( $r['code'], array( 201, 204 ), true ) ) $n++;
		}
		$out[] = 'List "Clients" ID ' . $lid . ': ' . $n . ' of ' . count( $emails ) . ' contacts added';
		/* sender */
		$acc = ss_brevo_api( 'GET', 'account' );
		$snd = ss_brevo_api( 'GET', 'senders' );
		$from = '';
		foreach ( (array) ( $snd['body']['senders'] ?? array() ) as $s ) { if ( ! empty( $s['active'] ) && 'info@solid-sound.net' === strtolower( $s['email'] ) ) $from = $s['email']; }
		if ( ! $from ) {
			$ns = ss_brevo_api( 'POST', 'senders', array( 'name' => 'Solid Sound', 'email' => 'info@solid-sound.net' ) );
			$snd = ss_brevo_api( 'GET', 'senders' );
			foreach ( (array) ( $snd['body']['senders'] ?? array() ) as $s ) { if ( ! empty( $s['active'] ) && 'info@solid-sound.net' === strtolower( $s['email'] ) ) $from = $s['email']; }
			if ( ! $from ) $out[] = 'Sender info@ not active yet (' . (int) $ns['code'] . ')';
		}
		if ( ! $from ) $from = $acc['body']['email'] ?? 'solidsoundb@gmail.com';
		$url = home_url( '/' );
		$html = '<div style="background:#0d0d0d;padding:28px 12px;font-family:Arial,Helvetica,sans-serif"><div style="max-width:560px;margin:0 auto;background:#161616;border:1px solid #2a2a2a;border-radius:14px;padding:28px;color:#eee">'
			. '<div style="font-weight:700;letter-spacing:2px;color:#E09900;font-size:13px;margin-bottom:18px">SØLID SØUND STUDIO</div>'
			. '<p style="font-size:16px;margin:0 0 14px">Hi{% if contact.FIRSTNAME %} {{ contact.FIRSTNAME }}{% endif %}!</p>'
			. '<p style="font-size:15px;line-height:1.6;margin:0 0 14px">Thank you for trusting Solid Sound with your music. As a returning client, you now get <b style="color:#fff">10% off everything</b>: Ableton templates, presets and studio services (mixing, mastering, production).</p>'
			. '<p style="font-size:15px;margin:0 0 6px">Your code:</p><p style="font-size:22px;font-weight:700;letter-spacing:1px;color:#E09900;margin:0 0 8px">SOLID-SOUND-10</p>'
			. '<p style="font-size:13px;color:#9a948d;margin:0 0 22px">It works only with this email address — it\'s yours.</p>'
			. '<p style="margin:0 0 22px"><a href="' . esc_url( $url ) . '" style="display:inline-block;background:#E09900;color:#111;text-decoration:none;font-weight:700;padding:13px 22px;border-radius:10px">Templates &amp; studio services →</a></p>'
			. '<p style="font-size:15px;line-height:1.6;margin:0">Got a track in progress? Just reply to this email — happy to help.</p>'
			. '<p style="font-size:15px;margin:18px 0 0">— Solid Sound Studio</p>'
			. '</div><p style="text-align:center;font-size:12px;color:#777;margin:16px 0 0">Solid Sound Studio · <a style="color:#999" href="https://solid-sound.net">solid-sound.net</a> · <a style="color:#999" href="{{ unsubscribe }}">Unsubscribe</a></p></div>';
		$cid = (int) get_option( 'ss_brevo_camp_clients', 0 );
		$body = array(
			'name'        => 'Returning clients — SOLID-SOUND-10',
			'subject'     => 'Thank you — 10% off your next order at Solid Sound',
			'sender'      => array( 'name' => 'Solid Sound', 'email' => $from ),
			'replyTo'     => 'info@solid-sound.net',
			'htmlContent' => $html,
			'recipients'  => array( 'listIds' => array( $lid ) ),
		);
		$r = $cid ? ss_brevo_api( 'PUT', 'emailCampaigns/' . $cid, $body ) : ss_brevo_api( 'POST', 'emailCampaigns', $body );
		if ( ! $cid && ! empty( $r['body']['id'] ) ) { $cid = (int) $r['body']['id']; update_option( 'ss_brevo_camp_clients', $cid, false ); }
		$out[] = $cid && $r['code'] < 300 ? 'Draft campaign ID ' . $cid . ' ready (sender ' . $from . ') — NOT sent' : 'Campaign error ' . $r['code'] . ': ' . wp_json_encode( $r['body'] );
		return $out;
	}
	add_action( 'admin_post_ss_clients_prepare', function () {
		if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'ss_clients_prepare' ) ) wp_die( 'no' );
		set_transient( 'ss_clients_res', ss_clients_prepare(), 600 );
		wp_safe_redirect( admin_url( 'options-general.php?page=ss-free-funnel' ) );
		exit;
	} );
	add_action( 'admin_notices', function () {
		if ( ! isset( $_GET['page'] ) || 'ss-free-funnel' !== $_GET['page'] ) return;
		$r = get_transient( 'ss_clients_res' );
		if ( $r ) { delete_transient( 'ss_clients_res' ); echo '<div class="notice notice-info"><p id="ss-clients-res">' . esc_html( implode( ' · ', (array) $r ) ) . '</p></div>'; }
		echo '<div class="notice"><form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="ss_clients_prepare">';
		wp_nonce_field( 'ss_clients_prepare' );
		echo '<p><b>Returning clients email:</b> sync coupon emails → Brevo list “Clients” and prepare a draft campaign (does not send). <button class="button button-primary" id="ss-clients-btn">Prepare draft</button></p></form></div>';
	} );
}

/* Speed 19.10: grid images (us_600_600_crop) — realistic sizes; lazy on the front page (hidden old carousel loaded a 1.9 MB original) */
add_filter( 'wp_get_attachment_image_attributes', function ( $attr, $att, $size ) {
	if ( is_admin() || 'us_600_600_crop' !== $size ) return $attr;
	$attr['sizes'] = '(max-width: 767px) 50vw, 300px';
	if ( is_front_page() ) { $attr['loading'] = 'lazy'; unset( $attr['fetchpriority'] ); }
	return $attr;
}, 20, 3 );
add_filter( 'wp_content_img_tag', function ( $html ) {
	if ( is_admin() || ! is_front_page() || false === strpos( $html, 'attachment-us_600_600_crop' ) ) return $html;
	$html = preg_replace( '/\s(loading|fetchpriority)="[^"]*"/', '', $html );
	return preg_replace( '/^<img\s/', '<img loading="lazy" ', $html );
}, 20 );

/* Speed 19.10: ACF form assets only where the ACF form is used (add-product + WCFM dashboard) */
add_action( 'wp_enqueue_scripts', function () {
	if ( is_admin() ) return;
	if ( is_page( 857 ) || is_page( 'add-product' ) || is_page( 'store-manager' ) || ( function_exists( 'is_wcfm_page' ) && is_wcfm_page() ) ) return;
	foreach ( array( 'acf-global', 'acf-input', 'acf-datepicker', 'acf-timepicker', 'wp-color-picker' ) as $h ) wp_dequeue_style( $h );
	foreach ( array( 'acf-input', 'acf', 'acf-timepicker', 'acf-color-picker-alpha', 'wp-color-picker', 'iris' ) as $h ) wp_dequeue_script( $h );
}, 999 );

/* Speed 19.10b: render-blocking CSS — plugin styles only where they are used, Google Fonts non-blocking */
function ss_speed_dequeue_css() {
	if ( is_admin() ) return;
	if ( ! ( function_exists( 'is_wpforo_page' ) && is_wpforo_page() ) ) {
		wp_dequeue_style( 'wpforo-widgets' );
		wp_dequeue_style( 'wpforo-dynamic-style' );
	}
	$wcfm = is_page( array( 'sell', 'vendor-register', 'vendor-membership', 'store-manager', 'add-product' ) ) || ( function_exists( 'is_wcfm_page' ) && is_wcfm_page() ) || ( function_exists( 'wcfmmp_is_store_page' ) && wcfmmp_is_store_page() ) || ( function_exists( 'is_product' ) && is_product() );
	if ( ! $wcfm ) {
		wp_dequeue_style( 'wcfm_core_css' );
		wp_dequeue_style( 'wcfm_fa_icon_css' );
	}
	wp_dequeue_style( 'sib-front-css' );
}
add_action( 'wp_enqueue_scripts', 'ss_speed_dequeue_css', 1000 );
add_action( 'wp_print_styles', 'ss_speed_dequeue_css', 1 );
add_action( 'wp_head', function () {
	if ( ! is_admin() ) echo '<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1 );
add_filter( 'style_loader_tag', function ( $tag, $handle ) {
	if ( is_admin() || 'us-fonts' !== $handle || false !== strpos( $tag, 'onload=' ) ) return $tag;
	$tag = preg_replace( "/media=(['\"])all\\1/", "media='print' onload=\"this.media='all'\"", $tag, 1 );
	return $tag . '<noscript>' . str_replace( array( "media='print' ", "onload=\"this.media='all'\"" ), array( "media='all' ", '' ), $tag ) . '</noscript>';
}, 10, 2 );

/* Google Search Console verification (URL prefix https://solid-sound.net/, account solidsoundb) */
add_action( 'wp_head', function () {
	if ( is_front_page() ) echo '<meta name="google-site-verification" content="Vpzw7aOfQg7PmFsFGB5io7K_VvI4o18HY5rNGVvAtHc" />' . "\n";
}, 1 );

/* ═══ Solid Sound — 24.10.2026: ES + IT — translation layer for custom blocks (hero, chat, FREE zone, follow block, UI) ═══
 * Our dictionaries fall back to EN on /es/ and /it/; this layer swaps the EN strings in the final HTML (plain, HTML-escaped and JSON-escaped forms).
 * Single words are only swapped as whole element text (>Word<) so code, classes and URLs are never touched. */
if ( ! function_exists( 'ss_esit_map' ) ) {
	function ss_esit_map( $l ) {
		$M = array(
			/* hero */
			'Try it for free' => array( 'Pruébalo gratis', 'Provalo gratis' ),
			'Track feedback' => array( 'Feedback de tu track', 'Feedback sulla tua traccia' ),
			'Ableton templates' => array( 'Plantillas Ableton', 'Template Ableton' ),
			'New arrivals' => array( 'Novedades', 'Novità' ),
			'What’s new' => array( 'Novedades', 'Novità' ),
			'Browse products' => array( 'Ver productos', 'Vedi i prodotti' ),
			'All prices' => array( 'Todos los precios', 'Tutti i prezzi' ),
			'Ghost producing' => array( 'Ghost production', 'Ghost production' ),
			'Co-producing' => array( 'Coproducción', 'Co-produzione' ),
			'Post production' => array( 'Postproducción', 'Post-produzione' ),
			'Instant download after payment' => array( 'Descarga inmediata tras el pago', 'Download immediato dopo il pagamento' ),
			'Secure payment' => array( 'Pago seguro', 'Pagamento sicuro' ),
			'Royalty-free' => array( 'Libre de royalties', 'Royalty-free' ),
			/* site strings (ss_i18n_dict sources) */
			'Turn Your Ideas into Solid Sound' => array( 'Convierte tus ideas en Solid Sound', 'Trasforma le tue idee in Solid Sound' ),
			'Turn Your Ideas into' => array( 'Convierte tus ideas en', 'Trasforma le tue idee in' ),
			'Templates & Presets' => array( 'Plantillas y presets', 'Template e preset' ),
			'Music for Videos & Business' => array( 'Música para vídeos y empresas', 'Musica per video e aziende' ),
			'Learn • Create • Release' => array( 'Aprende • Crea • Publica', 'Impara • Crea • Pubblica' ),
			'FILTER BY' => array( 'FILTRAR POR', 'FILTRA PER' ),
			'BROWSE PRODUCTS' => array( 'VER PRODUCTOS', 'VEDI I PRODOTTI' ),
			'Best Products' => array( 'Los más vendidos', 'I più venduti' ),
			'We Create Ableton Live Templates' => array( 'Creamos plantillas de Ableton Live', 'Creiamo template per Ableton Live' ),
			'BROWSE TEMPLATES' => array( 'VER PLANTILLAS', 'VEDI I TEMPLATE' ),
			'Contact Us' => array( 'Contáctanos', 'Contattaci' ),
			'No external plugins' => array( 'Sin plugins externos', 'Nessun plugin esterno' ),
			'No External Plugins' => array( 'Sin plugins externos', 'Nessun plugin esterno' ),
			/* FREE zone */
			'Free tools for producers' => array( 'Herramientas gratis para productores', 'Strumenti gratuiti per produttori' ),
			'Ableton templates, presets and samples — free for subscribers. New drops regularly.' => array( 'Plantillas de Ableton, presets y samples — gratis para suscriptores. Nuevos lanzamientos con frecuencia.', 'Template Ableton, preset e campioni — gratis per gli iscritti. Nuove uscite regolari.' ),
			'Get free access' => array( 'Accede gratis', 'Accesso gratuito' ),
			'One form — instant access to every free download.' => array( 'Un solo formulario: acceso inmediato a todas las descargas gratuitas.', 'Un solo modulo: accesso immediato a tutti i download gratuiti.' ),
			'Send me free packs, new releases and offers (max 2 emails / month). Unsubscribe anytime.' => array( 'Envíame packs gratis, novedades y ofertas (máx. 2 emails al mes). Puedes darte de baja cuando quieras.', 'Inviami pack gratuiti, novità e offerte (max 2 email al mese). Puoi disiscriverti in qualsiasi momento.' ),
			'Privacy policy' => array( 'Política de privacidad', 'Privacy policy' ),
			'Unlock free downloads' => array( 'Desbloquear descargas gratis', 'Sblocca i download gratuiti' ),
			'You have free access — download anything below.' => array( 'Ya tienes acceso gratuito: descarga lo que quieras abajo.', 'Hai l’accesso gratuito: scarica tutto qui sotto.' ),
			'The first free drop is coming soon. Subscribe now and get it first.' => array( 'El primer lanzamiento gratuito llega pronto. Suscríbete ahora y recíbelo antes que nadie.', 'La prima uscita gratuita arriva presto. Iscriviti ora e ricevila per primo.' ),
			'Done! Unlocking your downloads…' => array( '¡Listo! Desbloqueando tus descargas…', 'Fatto! Sblocco dei download in corso…' ),
			'Please enter your name and a valid email, and tick the box.' => array( 'Escribe tu nombre y un email válido, y marca la casilla.', 'Inserisci nome ed email validi e spunta la casella.' ),
			'Producer / beatmaker' => array( 'Productor / beatmaker', 'Produttore / beatmaker' ),
			'Learning music production' => array( 'Aprendiendo producción musical', 'Sto imparando produzione musicale' ),
			'Video / content creator' => array( 'Creador de vídeo / contenido', 'Creator video / contenuti' ),
			'Artist (need mixing & mastering)' => array( 'Artista (necesito mezcla y masterización)', 'Artista (mi serve mix e mastering)' ),
			'Free zone' => array( 'Zona gratuita', 'Zona gratuita' ),
			'I am a…' => array( 'Soy…', 'Sono…' ),
			/* follow block */
			'More free stuff on our socials' => array( 'Más contenido gratis en nuestras redes', 'Altri contenuti gratuiti sui nostri social' ),
			'Behind-the-scenes, quick mixing tips, new free drops and track breakdowns — they land on Instagram and YouTube first.' => array( 'Detrás de cámaras del estudio, trucos rápidos de mezcla, nuevos packs gratis y análisis de tracks: primero en Instagram y YouTube.', 'Dietro le quinte dello studio, trucchi rapidi di mix, nuovi pack gratuiti e analisi delle tracce: prima su Instagram e YouTube.' ),
			'Follow on Instagram' => array( 'Seguir en Instagram', 'Segui su Instagram' ),
			'Subscribe on YouTube' => array( 'Suscribirse en YouTube', 'Iscriviti su YouTube' ),
			/* chat widget */
			'The team replies here or by email' => array( 'El equipo responde aquí o por email', 'Il team risponde qui o via email' ),
			'Hi! Write your question below — it goes straight to the Solid Sound team. Or pick a quick topic:' => array( '¡Hola! Escribe tu pregunta abajo: llega directamente al equipo de Solid Sound. O elige un tema:', 'Ciao! Scrivi la tua domanda qui sotto: arriva direttamente al team di Solid Sound. Oppure scegli un argomento:' ),
			'Type your question…' => array( 'Escribe tu pregunta…', 'Scrivi la tua domanda…' ),
			'Questions? Ask here' => array( '¿Dudas? Pregunta aquí', 'Domande? Chiedi qui' ),
			'Main menu' => array( 'Menú principal', 'Menu principale' ),
			'Ask a person' => array( 'Hablar con una persona', 'Parla con una persona' ),
			'Anything else?' => array( '¿Algo más?', 'Altro?' ),
			'I’m not sure I got that. Pick a topic below or leave your question for the team.' => array( 'No estoy seguro de haberte entendido. Elige un tema o deja tu pregunta al equipo.', 'Non sono sicuro di aver capito. Scegli un argomento o lascia la tua domanda al team.' ),
			'Your name' => array( 'Tu nombre', 'Il tuo nome' ),
			'Your question *' => array( 'Tu pregunta *', 'La tua domanda *' ),
			'Send question' => array( 'Enviar pregunta', 'Invia domanda' ),
			'Thanks! Your question has been sent. The answer will come to your email, usually within a day.' => array( '¡Gracias! Hemos recibido tu pregunta. Te responderemos por email, normalmente en un día.', 'Grazie! Abbiamo ricevuto la tua domanda. Ti risponderemo via email, di solito entro un giorno.' ),
			'Please enter a valid email and your question.' => array( 'Escribe un email válido y tu pregunta.', 'Inserisci un’email valida e la tua domanda.' ),
			'Couldn’t send right now. Please try again in a minute.' => array( 'No se pudo enviar. Inténtalo de nuevo en un minuto.', 'Invio non riuscito. Riprova tra un minuto.' ),
			'Your email — if we can’t reply in the chat in time, the answer comes to your inbox.' => array( 'Tu email: si no llegamos a responder en el chat, la respuesta llegará a tu bandeja.', 'La tua email: se non riusciamo a rispondere in chat, la risposta arriverà nella tua casella.' ),
			'Message received ✓ We usually join within a couple of minutes — please wait here.' => array( 'Mensaje recibido ✓ Normalmente nos conectamos en un par de minutos: espera aquí.', 'Messaggio ricevuto ✓ Di solito ci colleghiamo in un paio di minuti: resta qui.' ),
			'Nobody is online right now. We’ll answer you by email%s, usually within a few hours — you can close this window.' => array( 'Ahora no hay nadie conectado. Te responderemos por email%s, normalmente en unas horas; puedes cerrar esta ventana.', 'Al momento non c’è nessuno online. Ti risponderemo via email%s, di solito entro qualche ora; puoi chiudere questa finestra.' ),
			'Order mixing / mastering' => array( 'Encargar mezcla / masterización', 'Ordina mix / mastering' ),
			'Free track feedback' => array( 'Feedback gratis de tu track', 'Feedback gratuito sulla traccia' ),
			'Free packs' => array( 'Packs gratis', 'Pack gratuiti' ),
			'Download problem' => array( 'Problema con la descarga', 'Problema con il download' ),
			'Template sounds wrong / plugin missing' => array( 'La plantilla suena distinto / falta un plugin', 'Il template suona diverso / manca un plugin' ),
			'Payment methods' => array( 'Métodos de pago', 'Metodi di pagamento' ),
			'Compare all packages' => array( 'Comparar paquetes', 'Confronta i pacchetti' ),
			'Choose a package' => array( 'Elegir paquete', 'Scegli il pacchetto' ),
			'Get free feedback' => array( 'Pedir feedback gratis', 'Ricevi feedback gratuito' ),
			'Open the Free zone' => array( 'Abrir la Zona gratuita', 'Apri la Zona gratuita' ),
			'Read the license' => array( 'Leer la licencia', 'Leggi la licenza' ),
			'My downloads' => array( 'Mis descargas', 'I miei download' ),
			'More answers in FAQ' => array( 'Más respuestas en FAQ', 'Altre risposte nelle FAQ' ),
			'Refund policy' => array( 'Política de reembolso', 'Politica di rimborso' ),
			'Studio services (all prices in EUR):' => array( 'Servicios de estudio (precios en EUR):', 'Servizi di studio (prezzi in EUR):' ),
			'Send us your demo and get free professional feedback: what to fix in the arrangement, mix and master.' => array( 'Envíanos tu demo y recibe feedback profesional gratis: qué mejorar en arreglo, mezcla y master.', 'Mandaci la tua demo e ricevi un feedback professionale gratuito: cosa migliorare in arrangiamento, mix e master.' ),
			'Free Ableton templates, presets and samples are in the Free zone. Sign up once with your name and email — and download instantly.' => array( 'Las plantillas, presets y samples gratis están en la Zona gratuita. Regístrate una vez con nombre y email y descarga al instante.', 'Template, preset e campioni gratuiti sono nella Zona gratuita. Registrati una volta con nome ed email e scarica subito.' ),
			'We accept Visa, Mastercard, Apple Pay, Google Pay and PayPal. Payments are processed securely by Stripe (WooPayments) and PayPal.' => array( 'Aceptamos Visa, Mastercard, Apple Pay, Google Pay y PayPal. Los pagos se procesan de forma segura con Stripe (WooPayments) y PayPal.', 'Accettiamo Visa, Mastercard, Apple Pay, Google Pay e PayPal. I pagamenti sono gestiti in modo sicuro da Stripe (WooPayments) e PayPal.' ),
			'Templates and presets are digital downloads, so they can’t be refunded once downloaded. Please check the tech specs before buying. If a file is broken, write to us and we’ll sort it out.' => array( 'Las plantillas y presets son descargas digitales, por lo que no se reembolsan una vez descargados. Revisa las especificaciones técnicas antes de comprar. Si un archivo está dañado, escríbenos y lo resolvemos.', 'Template e preset sono download digitali, quindi non sono rimborsabili dopo il download. Controlla i requisiti tecnici prima dell’acquisto. Se un file è danneggiato, scrivici e lo risolviamo.' ),
		);
		/* single words: only as whole element text */
		$W = array(
			'Services' => array( 'Servicios', 'Servizi' ), 'Prices' => array( 'Precios', 'Prezzi' ), 'License' => array( 'Licencia', 'Licenza' ), 'Refunds' => array( 'Reembolsos', 'Rimborsi' ),
			'Previous' => array( 'Anterior', 'Precedente' ), 'Next' => array( 'Siguiente', 'Successivo' ), 'Download' => array( 'Descargar', 'Scarica' ), 'Send' => array( 'Enviar', 'Invia' ),
			'Close' => array( 'Cerrar', 'Chiudi' ), 'Other' => array( 'Otro', 'Altro' ), 'Name' => array( 'Nombre', 'Nome' ), 'Music' => array( 'Música', 'Musica' ),
			'Mixing' => array( 'Mezcla', 'Mix' ), 'Mastering' => array( 'Masterización', 'Mastering' ), 'Template' => array( 'Plantilla', 'Template' ), 'from' => array( 'desde', 'da' ),
		);
		$n = 'es' === $l ? 0 : 1;
		$r = array();
		foreach ( $M as $en => $t ) {
			$tr = $t[ $n ];
			$r[ $en ] = $tr;
			$h = esc_html( $en ); if ( $h !== $en ) $r[ $h ] = esc_html( $tr );
			$j = substr( wp_json_encode( $en ), 1, -1 ); if ( $j !== $en ) $r[ $j ] = substr( wp_json_encode( $tr ), 1, -1 );
		}
		foreach ( $W as $en => $t ) { $r[ '>' . $en . '<' ] = '>' . $t[ $n ] . '<'; $r[ '> ' . $en . ' <' ] = '> ' . $t[ $n ] . ' <'; }
		return $r;
	}
	add_action( 'template_redirect', function () {
		if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ! function_exists( 'pll_current_language' ) ) return;
		$l = pll_current_language();
		if ( 'es' !== $l && 'it' !== $l ) return;
		ob_start( function ( $html ) use ( $l ) {
			if ( ! is_string( $html ) || false === stripos( $html, '<html' ) ) return $html;
			return strtr( $html, ss_esit_map( $l ) );
		} );
	}, 1 );
}

/* ═══ Solid Sound: EU consumer law — checkout consent (digital content / services), privacy notes on forms, legal footer links (01.11.2026) ═══ */
if ( ! function_exists( 'ss_wd_cart_types' ) ) {
	function ss_wd_cart_types() {
		$t = array( 'digital' => false, 'service' => false );
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return $t;
		}
		foreach ( WC()->cart->get_cart() as $item ) {
			$p = isset( $item['data'] ) ? $item['data'] : null;
			if ( ! $p || ! is_a( $p, 'WC_Product' ) ) {
				continue;
			}
			if ( $p->is_downloadable() ) {
				$t['digital'] = true;
			} elseif ( $p->is_virtual() ) {
				$t['service'] = true;
			}
		}
		return $t;
	}

	function ss_wd_texts() {
		return array(
			'digital' => 'I want immediate access to the digital content and I acknowledge that I lose my 14-day right of withdrawal once the download is made available.',
			'service' => 'I ask Solid Sound to start the service before the 14-day withdrawal period ends. I understand that if I cancel I pay for the work already done, and that I lose the right of withdrawal once the service is fully delivered.',
		);
	}

	add_action( 'woocommerce_review_order_before_submit', function () {
		$t      = ss_wd_cart_types();
		$x      = ss_wd_texts();
		$posted = array();
		if ( isset( $_POST['post_data'] ) ) {
			parse_str( wp_unslash( $_POST['post_data'] ), $posted );
		} else {
			$posted = $_POST;
		}
		echo '<input type="hidden" name="ss_wd_shown" value="1">';
		foreach ( array( 'digital', 'service' ) as $k ) {
			if ( empty( $t[ $k ] ) ) {
				continue;
			}
			$checked = ! empty( $posted[ 'ss_wd_' . $k ] );
			echo '<p class="form-row validate-required ss-wd-row"><label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">'
				. '<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="ss_wd_' . esc_attr( $k ) . '" value="1"' . checked( $checked, true, false ) . '> '
				. '<span>' . esc_html( $x[ $k ] ) . '</span>&nbsp;<abbr class="required" title="required">*</abbr></label></p>';
		}
	}, 9 );

	add_action( 'woocommerce_after_checkout_validation', function ( $data, $errors ) {
		if ( empty( $_POST['ss_wd_shown'] ) ) {
			return; // express payment buttons: checkbox not shown
		}
		$t = ss_wd_cart_types();
		if ( $t['digital'] && empty( $_POST['ss_wd_digital'] ) ) {
			$errors->add( 'ss_wd_digital', 'Please confirm immediate access to the digital content to complete your order.' );
		}
		if ( $t['service'] && empty( $_POST['ss_wd_service'] ) ) {
			$errors->add( 'ss_wd_service', 'Please confirm that we can start your service.' );
		}
	}, 10, 2 );

	add_action( 'woocommerce_checkout_create_order', function ( $order ) {
		$x   = ss_wd_texts();
		$log = array();
		foreach ( array( 'digital', 'service' ) as $k ) {
			if ( ! empty( $_POST[ 'ss_wd_' . $k ] ) ) {
				$log[] = $x[ $k ];
			}
		}
		if ( $log ) {
			$order->update_meta_data( '_ss_wd_consent', implode( ' | ', $log ) );
			$order->update_meta_data( '_ss_wd_consent_time', gmdate( 'c' ) );
		} elseif ( empty( $_POST['ss_wd_shown'] ) ) {
			$order->update_meta_data( '_ss_wd_consent', 'not collected (express checkout)' );
		}
	} );

	add_action( 'woocommerce_admin_order_data_after_billing_address', function ( $order ) {
		$c = $order->get_meta( '_ss_wd_consent' );
		if ( $c ) {
			echo '<p><strong>Withdrawal consent:</strong> ' . esc_html( $c ) . ' <small>' . esc_html( $order->get_meta( '_ss_wd_consent_time' ) ) . '</small></p>';
		}
	} );

	add_action( 'woocommerce_email_order_meta', function ( $order, $sent_to_admin, $plain_text ) {
		$c = $order->get_meta( '_ss_wd_consent' );
		if ( ! $c || 0 === strpos( $c, 'not collected' ) ) {
			return;
		}
		if ( $plain_text ) {
			echo "\nYour confirmation at checkout: " . $c . "\n";
		} else {
			echo '<p style="font-size:12px;color:#777">Your confirmation at checkout: ' . esc_html( $c ) . '</p>';
		}
	}, 20, 3 );

	add_action( 'wp_footer', function () {
		if ( is_admin() ) {
			return;
		}
		$priv  = get_privacy_policy_url() ? get_privacy_policy_url() : home_url( '/privacy-policy/' );
		$terms = home_url( '/terms/' );
		$legal = home_url( '/legal-notice/' );
		?>
<script>(function(){
var PRIV=<?php echo wp_json_encode( esc_url( $priv ) ); ?>,TERMS=<?php echo wp_json_encode( esc_url( $terms ) ); ?>,LEGAL=<?php echo wp_json_encode( esc_url( $legal ) ); ?>,LIC=<?php echo wp_json_encode( esc_url( home_url( '/license/' ) ) ); ?>,REF=<?php echo wp_json_encode( esc_url( home_url( '/refund_returns/' ) ) ); ?>;
function forms(){document.querySelectorAll('form').forEach(function(f){
if(f.dataset.ssPriv)return;
if(!f.querySelector('input[type=email],input[name*=email]'))return;
if(f.querySelector('input[type=password]'))return;
if(f.querySelector('a[href*="privacy"]'))return;
if(f.closest('.woocommerce-checkout'))return;
f.dataset.ssPriv='1';var p=document.createElement('p');p.className='ss-priv-note';p.style.cssText='font-size:12px;opacity:.7;margin:8px 0 0';
p.innerHTML='By submitting, you agree to our <a href="'+PRIV+'" target="_blank" rel="noopener" style="color:inherit;text-decoration:underline">Privacy Policy</a>.';f.appendChild(p);});}
function footer(){if(document.querySelector('[data-ss-legal]'))return;
var c=document.querySelector('a.ss-cc-open');if(!c||!c.parentNode)return;
var p=c.parentNode;
[[LEGAL,'Legal Notice'],[TERMS,'Terms of Sale'],[LIC,'License'],[REF,'Refund Policy'],[PRIV,'Privacy Policy']].forEach(function(d){
var a=document.createElement('a');a.href=d[0];a.textContent=d[1];a.setAttribute('data-ss-legal','1');a.style.cssText='color:inherit;text-decoration:underline';
p.insertBefore(a,c);p.insertBefore(document.createTextNode(' \u00b7 '),c);});}
function wiki(){var m=location.pathname.match(/^\/(de|fr|es|it|pt|ua)(\/|$)/);if(!m)return;var l=m[1];
document.querySelectorAll('.w-nav a[href$="/wiki/"],.w-nav a[href$="/wiki"]').forEach(function(a){a.href='/'+l+'/wiki-'+l+'/';});}
function run(){forms();footer();wiki();}
if(document.readyState!=='loading')run();else document.addEventListener('DOMContentLoaded',run);setTimeout(run,1500);})();</script>
		<?php
	}, 99 );
}

/* ═══ Solid Sound: old /wikisound/ address -> /wiki/ (01.11.2026) ═══ */
if ( ! function_exists( 'ss_wikisound_redirect' ) ) {
	function ss_wikisound_redirect() {
		$path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
		if ( $path && preg_match( '#^/wikisound(/.*)?$#i', $path, $m ) ) {
			$rest = isset( $m[1] ) ? $m[1] : '/';
			wp_safe_redirect( home_url( '/wiki' . $rest ), 301 );
			exit;
		}
	}
	add_action( 'template_redirect', 'ss_wikisound_redirect', 0 );
	add_action( 'parse_request', 'ss_wikisound_redirect', 0 );
}

/* ss-core: one shared DOM watcher instead of many setInterval loops.
   ssOnDom(fn) runs fn now and again (max once per animation frame) when the page changes:
   nodes added/removed, <body> class change (player open, menus), media play/pause/ended, resize, load. */
add_action( 'wp_head', function () {
	if ( is_admin() ) return;
	?><script id="ss-core">(function(w,d){if(w.ssOnDom)return;var q=[],p=0,last=0;function run(){p=0;last=Date.now();for(var i=0;i<q.length;i++){try{q[i]()}catch(e){}}}function tick(){if(p)return;p=1;var g=Date.now()-last;g<120?setTimeout(function(){requestAnimationFrame(run)},120-g):requestAnimationFrame(run)}w.ssOnDom=function(f){q.push(f);try{f()}catch(e){}};new MutationObserver(tick).observe(d.documentElement,{childList:true,subtree:true});function body(){new MutationObserver(tick).observe(d.body,{attributes:true,attributeFilter:['class']})}d.body?body():d.addEventListener('DOMContentLoaded',body);['play','pause','ended','loadedmetadata'].forEach(function(e){d.addEventListener(e,tick,true)});w.addEventListener('resize',tick,{passive:true});w.addEventListener('load',tick)})(window,document);</script><?php
}, 0 );

/* Global menu (#ssg) as a normal cached file (src/ss-global.js), instead of the gzip+base64+localStorage+new Function
   loader in footer page block 52. The flags make the old loader and the old logo script stand down.
   Not on the front page: the home app has its own header. */
add_action( 'wp_head', function () {
	/* (12.11.2026) only the home APP has its own header. The other-language home pages (/de/, /ua/ …) are the old
	   layout without a header: is_front_page() left them with no menu at all */
	if ( is_admin() || ( function_exists( 'ss_home_is_app_page' ) ? ss_home_is_app_page() : is_front_page() ) ) return;
	echo '<script>window.__ssg=1;window.__sslb=1;</script>';
	/* (12.11.2026 speed) the old theme header is hidden from the first paint (it used to flash and pull in 3 icon fonts,
	   ~450 KB) and the new menu's place is reserved before the script adds it (the whole page jumped down: CLS 0.54) */
	$promo = time() < strtotime( '2026-11-30 23:59:59 +0100' ) ? 36 : 0;
	echo '<style id="ss-ssg-reserve">.l-header,.l-subheader,.w-toplink{display:none!important}'
		. '.l-canvas:not(:has(> #ssg))::before{content:"";display:block;height:' . ( 73 + $promo ) . 'px}'
		. '@media(max-width:760px){.l-canvas:not(:has(> #ssg))::before{height:61px}}</style>';
	ss_asset( 'ss-global', 'defer' );
}, 2 );

/* Client accounts and vendor accounts are separate. The client dashboard (page 2561) belongs to client accounts:
   - first item "Dashboard" in WooCommerce My Account menu (clients only)
   - after login/registration from the account page: client -> dashboard, vendor -> Producer space (WCFM)
     (logins from checkout or with an explicit redirect keep their redirect) */
function ss_is_vendor_user( $user = null ) {
	$uid = $user ? $user->ID : get_current_user_id();
	return ( function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor( $uid ) ) || user_can( $uid, 'manage_woocommerce' );
}
function ss_dashboard_url() { return wc_get_page_permalink( 'myaccount' ); }
function ss_after_auth_redirect( $redirect, $user ) {
	if ( ! empty( $_REQUEST['redirect'] ) || ( function_exists( 'is_checkout' ) && is_checkout() ) ) return $redirect;
	if ( $redirect && false === strpos( $redirect, 'my-account' ) ) return $redirect;
	return ss_dashboard_url(); /* one account, two roles (owner 11.11.2026): Producer space is linked from My account */
}
add_filter( 'woocommerce_login_redirect', 'ss_after_auth_redirect', 20, 2 );
add_filter( 'woocommerce_registration_redirect', function ( $redirect ) { return ss_after_auth_redirect( $redirect, wp_get_current_user() ); }, 20 );

/* Modules (separate files) */
require_once __DIR__ . '/modules/account.php';
require_once __DIR__ . '/modules/home.php';

/* Who is looking (for the account menu): guest / customer / vendor / admin, plus a working log-out link */
add_action( 'wp_head', function () {
	if ( is_admin() ) return;
	$role = ! is_user_logged_in() ? 'guest' : ( current_user_can( 'manage_woocommerce' ) ? 'admin' : ( function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor() ? 'vendor' : 'customer' ) );
	$out  = is_user_logged_in() && function_exists( 'wc_logout_url' ) ? wc_logout_url() : '';
	echo '<script>window.ssRole=' . wp_json_encode( $role ) . ';window.ssLogout=' . wp_json_encode( $out ) . ';</script>';
}, 1 );
require_once __DIR__ . '/modules/all-products.php';
require_once __DIR__ . '/modules/ranking.php';
require_once __DIR__ . '/modules/social-login.php';
require_once __DIR__ . '/modules/footer.php';
require_once __DIR__ . '/modules/similar.php';
require_once __DIR__ . '/modules/license.php';
require_once __DIR__ . '/modules/bonus.php';
require_once __DIR__ . '/modules/vendor.php';
require_once __DIR__ . '/modules/creators.php';
require_once __DIR__ . '/modules/studio-calc.php';
require_once __DIR__ . '/modules/perf.php';
require_once __DIR__ . '/modules/cleanup.php';
require_once __DIR__ . '/modules/sale.php';
require_once __DIR__ . '/modules/search.php';
require_once __DIR__ . '/modules/wiki.php';
require_once __DIR__ . '/modules/seo.php';
require_once __DIR__ . '/modules/creator-apply.php';
require_once __DIR__ . '/modules/mix-upsell.php';
require_once __DIR__ . '/modules/sticky-buy.php';
require_once __DIR__ . '/modules/free-demo.php';
require_once __DIR__ . '/modules/ai-tools.php';
require_once __DIR__ . '/modules/creator-space.php';
require_once __DIR__ . '/modules/ai-creator.php';
require_once __DIR__ . '/modules/creator-autofill.php';
require_once __DIR__ . '/modules/creator-preview.php';
require_once __DIR__ . '/modules/home-wow.php';
require_once __DIR__ . '/modules/forum.php';
require_once __DIR__ . '/modules/kudos.php'; /* 18.11 owner: positive mood — welcome, thank the author, small wins */
require_once __DIR__ . '/modules/css-files.php';
require_once __DIR__ . '/modules/antispam.php';
require_once __DIR__ . '/modules/family.php';
require_once __DIR__ . '/modules/prices.php';
require_once __DIR__ . '/modules/ajax-cart.php';
