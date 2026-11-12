<?php
/**
 * Creators (authors) — quality gate without human reviewers (owner 12.11.2026).
 *
 * 1. Creator Guidelines: one text, shown in three places — public page /creator-guidelines/, the dashboard Help page
 *    and a short version on top of the upload form (+ required "I agree" checkbox).
 * 2. Upload robot: runs BEFORE the product is created (admin_post_solid_sound_add_product, priority 0) and refuses
 *    the upload with a clear list of what to fix:
 *    - cover: JPG/PNG/WEBP, square, at least 1000×1000, max 10 MB, not a copy of another product's cover;
 *    - demo MP3: 1–10 minutes, at least 128 kbps, not a copy of another product's demo;
 *    - project (templates): a .zip with an Ableton .als inside, every sample the project uses is in the zip,
 *      plugins found in the project must match what the creator declared ("No external plugins" = none);
 *    - presets / sample packs / music: the zip really contains preset / audio files;
 *    - max 5 uploads per day per creator (spam).
 * 3. The robot's report is saved on the product and sent to the owner with the "new product for review" email.
 * Admins (manage_woocommerce) are not checked.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'SS_CR_MAX_ZIP', 2 * 1024 * 1024 * 1024 );
define( 'SS_CR_MAX_COVER', 10 * 1024 * 1024 );

/* ---------- guidelines ---------- */
function ss_cr_guidelines_url() { return home_url( '/creator-guidelines/' ); }

function ss_cr_guidelines_html( $short = false ) {
	$rules = array(
		array( 'Only your own work', 'Sell only projects, presets and sounds you made. No paid third-party samples, presets or loops inside your files — buyers must be able to use everything royalty-free.' ),
		array( 'A finished, release-quality track', 'Templates must be complete tracks (intro → drop → outro), mixed and mastered — not an 8-bar loop or a sketch. Presets must be usable in a real track.' ),
		array( 'A clean project that opens', 'Use "Collect All and Save" in Ableton before zipping: every sample inside the zip, no "missing files". Name and colour your tracks and groups so buyers can learn from them.' ),
		array( 'Plugins: tell the truth', 'List every third-party plugin the project uses. Choose "No External Plugins" only if the project uses Ableton\'s own devices. Our robot checks this.' ),
		array( 'A demo that sells', 'MP3, 1–10 minutes, at least 128 kbps (320 recommended). The demo must be the track from the project — no talking, no watermark noise over the whole track.' ),
		array( 'A clean cover', 'Square, at least 1000×1000 px (JPG/PNG/WEBP). No other brands\' logos, no stolen artwork.' ),
		array( 'Honest description', 'Genre, BPM, key, Ableton version and what is inside (MIDI, audio, effects). No fake "Beatport #1" claims.' ),
	);
	$h = '<div class="ss-cr-g">';
	if ( $short ) {
		$h .= '<b>Before you upload</b><ul>';
		foreach ( $rules as $r ) $h .= '<li><b>' . esc_html( $r[0] ) . '.</b> ' . esc_html( $r[1] ) . '</li>';
		$h .= '</ul><p>Our robot checks the files automatically and tells you exactly what to fix. <a href="' . esc_url( ss_cr_guidelines_url() ) . '" target="_blank" rel="noopener">Full Creator Guidelines</a></p></div>';
		return $h;
	}
	$h .= '<h2>Creator Guidelines</h2><p class="ss-cr-lead">Solid Sound is open to every producer — and every product is checked before buyers see it. These rules keep the marketplace worth buying from, so your products sell.</p><ol>';
	foreach ( $rules as $r ) $h .= '<li><b>' . esc_html( $r[0] ) . '.</b> ' . esc_html( $r[1] ) . '</li>';
	$h .= '</ol><h3>How a product goes live</h3><ol>'
		. '<li><b>Robot check</b> — right after you press "Send for review": files, demo, cover and plugins. If something is wrong you see the exact list and can fix it immediately.</li>'
		. '<li><b>Quick listen</b> — the Solid Sound team listens to the demo and publishes the product, usually within 24 hours.</li>'
		. '<li><b>Buyers decide</b> — new products appear in the catalog with a "New" badge. Plays, likes, sales and reviews move them up to "Trending", "Best products" and the home page.</li></ol>'
		. '<h3>Not ready yet?</h3><p>If a product is declined you get the reason and what to improve. We help producers grow: free guides in the <a href="' . esc_url( home_url( '/en/wiki/' ) ) . '">Solid Sound Wiki</a>, and a detailed review of your project by our studio on request.</p>'
		. '<h3>After publishing</h3><p>If buyers confirm that a product does not open or does not match its description, it is hidden until fixed. Repeated problems close the creator account.</p></div>';
	return $h;
}

function ss_cr_css() {
	return '.ss-cr-g{background:#131B2E;border:1px solid rgba(255,255,255,.1);border-radius:14px;padding:18px 20px;color:#CBD5E1;font-size:14px;line-height:1.55;margin:0 0 18px}'
		. '.ss-cr-g h2{color:#fff;margin:0 0 6px}.ss-cr-g h3{color:#fff;margin:18px 0 6px;font-size:17px}.ss-cr-g b{color:#F3F4F6}.ss-cr-g a{color:#F59E0B}'
		. '.ss-cr-g ol,.ss-cr-g ul{padding-left:20px;margin:8px 0}.ss-cr-g li{margin:6px 0}.ss-cr-lead{color:#94A3B8}'
		. '.ss-cr-agree{display:flex;gap:10px;align-items:flex-start;margin:14px 0;padding:12px 14px;border:1px solid rgba(245,158,11,.4);border-radius:12px;background:rgba(245,158,11,.08);color:#E5E7EB;font-size:14px}'
		. '.ss-cr-agree input{width:20px;height:20px;flex:none;margin-top:2px;accent-color:#F59E0B}';
}

/* public page /creator-guidelines/ (no WordPress page needed) */
add_action( 'template_redirect', function () {
	$path = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' );
	if ( ! preg_match( '#^([a-z]{2}/)?creator-guidelines$#', $path ) ) return;
	global $wp_query;
	$wp_query->is_404 = false;
	status_header( 200 );
	add_filter( 'pre_get_document_title', function () { return 'Creator Guidelines – Sell on Solid Sound'; } );
	add_action( 'wp_head', function () { echo '<meta name="description" content="Rules for selling Ableton templates, presets and sounds on Solid Sound: what we accept and how products go live."><style>' . ss_cr_css() . '.ss-cr-wrap{max-width:860px;margin:30px auto 60px;padding:0 16px}</style>'; } );
	get_header();
	echo '<main id="page-content" class="l-main"><section class="l-section"><div class="l-section-h ss-cr-wrap">' . ss_cr_guidelines_html() . '<p><a class="ss-cr-btn" style="display:inline-block;background:#F59E0B;color:#111;font-weight:800;padding:12px 20px;border-radius:10px;text-decoration:none" href="' . esc_url( home_url( '/en/vendor-register/' ) ) . '">Become a creator</a></p></div></section></main>';
	get_footer();
	exit;
}, 1 );

/* guidelines + required agreement on the upload form, guidelines on the dashboard Help page */
add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() || current_user_can( 'manage_woocommerce' ) ) return;
	?>
<style><?php echo ss_cr_css(); ?></style>
<script>(function(){
var G=<?php echo wp_json_encode( ss_cr_guidelines_html( true ) ); ?>,F=<?php echo wp_json_encode( ss_cr_guidelines_html( false ) ); ?>,U=<?php echo wp_json_encode( ss_cr_guidelines_url() ); ?>;
function run(){
  var f=document.querySelector('form input[name="action"][value="solid_sound_add_product"]');f=f&&f.form;
  if(f&&!f.querySelector('.ss-cr-g')){var d=document.createElement('div');d.innerHTML=G;f.insertBefore(d.firstChild,f.firstChild);
    var a=document.createElement('label');a.className='ss-cr-agree';a.innerHTML='<input type="checkbox" name="ss_rules" value="1" required> <span>I made this product myself, it contains no paid third-party samples or presets, and I agree to the <a href="'+U+'" target="_blank" rel="noopener">Creator Guidelines</a>.</span>';
    var sb=f.querySelector('[type=submit]');(sb&&sb.parentNode?sb.parentNode:f).insertBefore(a,sb&&sb.parentNode?sb:null)}
  var h=/\/ss-help\/?$/.test(location.pathname)&&document.querySelector('#wcfm-content .wcfm-collapse-content');
  if(h&&!h.querySelector('.ss-cr-g')){var e=document.createElement('div');e.innerHTML=F;h.insertBefore(e.firstChild,h.firstChild)}
}
(window.ssOnDom||function(x){document.readyState!=='loading'?x():document.addEventListener('DOMContentLoaded',x)})(run);
})();</script>
	<?php
}, 60 );

/* ---------- upload robot ---------- */
function ss_cr_format( $acf_val ) {
	foreach ( (array) $acf_val as $v ) {
		if ( ! is_numeric( $v ) ) { if ( 'sample' === $v ) return 'sample-packs'; continue; }
		$t = get_term( (int) $v );
		if ( $t && ! is_wp_error( $t ) ) return $t->slug;
	}
	return '';
}

/* reads an Ableton .als (gzip XML): plugins and referenced audio files */
function ss_cr_read_als( $xml ) {
	$plugins = array();
	foreach ( array( '#<VstPluginInfo\b.*?<PlugName Value="([^"]+)"#s', '#<Vst3PluginInfo\b.*?<Name Value="([^"]+)"#s', '#<AuPluginInfo\b.*?<Name Value="([^"]+)"#s' ) as $re ) {
		if ( preg_match_all( $re, $xml, $m ) ) foreach ( $m[1] as $n ) $plugins[ trim( html_entity_decode( $n ) ) ] = 1;
	}
	$files = array();
	if ( preg_match_all( '#<SampleRef>.*?</SampleRef>#s', $xml, $refs ) ) {
		foreach ( $refs[0] as $r ) {
			$p = '';
			if ( preg_match( '#<RelativePath Value="([^"]*)"#', $r, $m ) && '' !== $m[1] ) $p = $m[1];
			if ( preg_match( '#<Path Value="([^"]*)"#', $r, $m ) && '' !== $m[1] ) $p = $m[1];
			if ( '' === $p ) continue;
			$p = str_replace( '\\', '/', html_entity_decode( $p ) );
			/* Ableton's own libraries are on every buyer's computer */
			if ( preg_match( '#(Core Library|/Packs/|Ableton/Factory|Live Recordings)#i', $p ) ) continue;
			$files[ strtolower( basename( $p ) ) ] = basename( $p );
		}
	}
	return array( array_keys( $plugins ), $files );
}

function ss_cr_check_zip( $tmp, $fmt, $declared_native, $declared_plugins, &$err, &$rep ) {
	if ( ! class_exists( 'ZipArchive' ) ) { $rep[] = 'zip: not checked (server has no ZipArchive)'; return; }
	$z = new ZipArchive();
	if ( true !== $z->open( $tmp ) ) { $err[] = 'The project file is not a valid .zip archive. Re-create it (right click → Compress) and upload again.'; return; }
	$names = array(); $als = array();
	for ( $i = 0; $i < $z->numFiles; $i++ ) {
		$n = (string) $z->getNameIndex( $i );
		if ( false !== strpos( $n, '__MACOSX/' ) || '/' === substr( $n, -1 ) ) continue;
		$names[ strtolower( basename( $n ) ) ] = 1;
		if ( preg_match( '#\.als$#i', $n ) && false === stripos( basename( $n ), 'backup' ) && false === strpos( $n, '/Backup/' ) ) $als[] = $n;
	}
	$rep[] = 'zip: ' . count( $names ) . ' files';
	if ( in_array( $fmt, array( 'templates', 'reconstructed-templates' ), true ) ) {
		if ( ! $als ) { $err[] = 'No Ableton project (.als) found in the zip. In Ableton use File → Collect All and Save, then zip the whole project folder.'; $z->close(); return; }
		usort( $als, function ( $a, $b ) { return substr_count( $a, '/' ) - substr_count( $b, '/' ); } );
		$raw = $z->getFromName( $als[0] );
		$xml = $raw ? @gzdecode( $raw ) : false;
		if ( ! $xml ) { $rep[] = 'als: could not read ' . basename( $als[0] ); $z->close(); return; }
		list( $plugins, $files ) = ss_cr_read_als( $xml );
		$rep[] = 'als: ' . basename( $als[0] ) . ' · plugins: ' . ( $plugins ? implode( ', ', $plugins ) : 'none' ) . ' · samples used: ' . count( $files );
		$missing = array_values( array_diff_key( $files, $names ) );
		if ( $missing ) $err[] = count( $missing ) . ' sample(s) used by the project are not in the zip: ' . implode( ', ', array_slice( $missing, 0, 8 ) ) . ( count( $missing ) > 8 ? ' …' : '' ) . '. In Ableton use File → Collect All and Save, then zip again.';
		if ( $plugins && $declared_native ) $err[] = 'You chose "No External Plugins", but the project uses: ' . implode( ', ', $plugins ) . '. Remove them from the project or list them under "Plugins used".';
		if ( $plugins && ! $declared_native && ! $declared_plugins ) $err[] = 'The project uses third-party plugins (' . implode( ', ', $plugins ) . '). List them under "Plugins used" so buyers know what they need.';
	} elseif ( 'presets' === $fmt ) {
		$ok = false; foreach ( array_keys( $names ) as $n ) if ( preg_match( '#\.(fxp|fxb|serumpreset|vital|vitalbank|adv|adg|h2p|nmsv|vstpreset|aupreset|ksd|spf|xpf|syx|nki|nksf|phaseplant|pigments|ovpreset|nmspresetx)$#i', $n ) ) { $ok = true; break; }
		if ( ! $ok ) $err[] = 'No preset files found in the zip (e.g. .fxp, .SerumPreset, .vital, .adv, .adg, .h2p, .vstpreset).';
	} else {
		$ok = false; foreach ( array_keys( $names ) as $n ) if ( preg_match( '#\.(wav|aif|aiff|flac|mp3|mid|midi)$#i', $n ) ) { $ok = true; break; }
		if ( ! $ok ) $err[] = 'No audio files (WAV/AIFF/FLAC/MP3) found in the zip.';
	}
	$z->close();
}

function ss_cr_dup( $key, $md5, $exclude_author = 0 ) {
	global $wpdb;
	$id = (int) $wpdb->get_var( $wpdb->prepare( "SELECT pm.post_id FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON p.ID=pm.post_id WHERE pm.meta_key=%s AND pm.meta_value=%s AND p.post_status IN ('publish','pending') LIMIT 1", $key, $md5 ) );
	return $id;
}

add_action( 'admin_post_solid_sound_add_product', function () {
	if ( ! is_user_logged_in() || current_user_can( 'manage_woocommerce' ) ) return;
	$uid = get_current_user_id();
	$keys = function_exists( 'ss_ap_keys' ) ? ss_ap_keys() : array();
	$acf  = ( isset( $_POST['acf'] ) && is_array( $_POST['acf'] ) ) ? wp_unslash( $_POST['acf'] ) : array();
	$val  = function ( $k ) use ( $acf, $keys ) { return ( isset( $keys[ $k ] ) && isset( $acf[ $keys[ $k ] ] ) ) ? $acf[ $keys[ $k ] ] : ''; };
	$err = array(); $rep = array();

	if ( empty( $_POST['ss_rules'] ) ) $err[] = 'Please confirm that the product is your own work and that you agree to the Creator Guidelines.';

	/* spam limit */
	$day = 'ss_cr_day_' . $uid . '_' . gmdate( 'Ymd' );
	if ( (int) get_transient( $day ) >= 5 ) $err[] = 'You can send up to 5 products a day. Please continue tomorrow.';

	/* cover */
	if ( ! empty( $_FILES['product_image']['tmp_name'] ) ) {
		$f = $_FILES['product_image'];
		$info = @getimagesize( $f['tmp_name'] );
		if ( ! $info || ! in_array( $info[2], array( IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP ), true ) ) $err[] = 'The cover must be a JPG, PNG or WEBP image.';
		else {
			list( $w, $h ) = $info;
			$rep[] = "cover: {$w}×{$h}";
			if ( $w < 1000 || $h < 1000 ) $err[] = "The cover is {$w}×{$h} px — it must be at least 1000×1000 px.";
			if ( abs( $w / max( 1, $h ) - 1 ) > 0.02 ) $err[] = "The cover must be square (now {$w}×{$h} px).";
		}
		if ( (int) $f['size'] > SS_CR_MAX_COVER ) $err[] = 'The cover is larger than 10 MB. Please export it smaller (JPG, quality 85).';
		$md5 = @md5_file( $f['tmp_name'] );
		if ( $md5 && ss_cr_dup( '_ss_cover_md5', $md5 ) ) $err[] = 'This cover is already used by another product on Solid Sound. Every product needs its own cover.';
		if ( $md5 ) set_transient( 'ss_cr_cover_' . $uid, $md5, HOUR_IN_SECONDS );
	}

	/* demo mp3 (uploaded earlier by the form into the media library) */
	if ( preg_match( '#https?://[^\s"\'\]]+?\.mp3#i', (string) $val( 'mp3' ), $m ) ) {
		$aid = attachment_url_to_postid( $m[0] );
		$path = $aid ? get_attached_file( $aid ) : '';
		if ( $path && is_readable( $path ) ) {
			if ( ! function_exists( 'wp_read_audio_metadata' ) ) require_once ABSPATH . 'wp-admin/includes/media.php';
			$meta = wp_read_audio_metadata( $path );
			$len = isset( $meta['length'] ) ? (int) $meta['length'] : 0;
			$br  = isset( $meta['bitrate'] ) ? (int) $meta['bitrate'] : 0;
			$rep[] = 'demo: ' . gmdate( 'i:s', $len ) . ', ' . round( $br / 1000 ) . ' kbps';
			if ( $len && $len < 60 ) $err[] = 'The demo is ' . $len . ' seconds — it must be at least 1 minute so buyers can hear the track.';
			if ( $len > 600 ) $err[] = 'The demo is longer than 10 minutes. Please upload a shorter preview.';
			if ( $br && $br < 128000 ) $err[] = 'The demo is ' . round( $br / 1000 ) . ' kbps — export it at 128 kbps or more (320 recommended).';
			$md5 = @md5_file( $path );
			if ( $md5 && ss_cr_dup( '_ss_demo_md5', $md5 ) ) $err[] = 'This demo is already used by another product on Solid Sound.';
			if ( $md5 ) set_transient( 'ss_cr_demo_' . $uid, $md5, HOUR_IN_SECONDS );
		}
	}

	/* product file */
	$fmt = ss_cr_format( $val( 'format' ) );
	if ( ! empty( $_FILES['product_file']['tmp_name'] ) ) {
		$f = $_FILES['product_file'];
		$name = strtolower( (string) $f['name'] );
		$rep[] = 'file: ' . $f['name'] . ' (' . size_format( (int) $f['size'] ) . ')';
		if ( (int) $f['size'] > SS_CR_MAX_ZIP ) $err[] = 'The file is larger than 2 GB.';
		$is_zip = (bool) preg_match( '#\.zip$#', $name );
		if ( in_array( $fmt, array( 'templates', 'reconstructed-templates', 'presets', 'sample-packs' ), true ) && ! $is_zip ) $err[] = 'Please upload the project as one .zip file.';
		if ( 'music' === $fmt && ! preg_match( '#\.(zip|wav|mp3|flac|aif|aiff)$#', $name ) ) $err[] = 'Music must be a .zip, WAV, FLAC, AIFF or MP3 file.';
		if ( $is_zip ) {
			$native = false; $declared = false;
			foreach ( (array) $val( 'ext' ) as $t ) {
				if ( ! is_numeric( $t ) ) continue;
				$tt = get_term( (int) $t );
				if ( ! $tt || is_wp_error( $tt ) ) continue;
				if ( false !== strpos( $tt->slug, 'no-' ) ) $native = true; else $declared = true;
			}
			ss_cr_check_zip( $f['tmp_name'], $fmt, $native, $declared, $err, $rep );
		}
	}

	if ( $err ) {
		$h = '<style>body{background:#070A12!important;color:#E5E7EB;font-family:system-ui,sans-serif}#error-page{background:#131B2E!important;border:1px solid rgba(255,255,255,.1);border-radius:16px;max-width:720px;box-shadow:none}h1{color:#fff;border:0!important}li{margin:8px 0;line-height:1.5}a{color:#F59E0B}</style>'
			. '<h1>Almost there — please fix ' . ( 1 === count( $err ) ? 'this' : 'these ' . count( $err ) . ' things' ) . '</h1><ol>';
		foreach ( $err as $e ) $h .= '<li>' . esc_html( $e ) . '</li>';
		$h .= '</ol><p><a href="javascript:history.back()">&larr; Back to the form</a> · <a href="' . esc_url( ss_cr_guidelines_url() ) . '" target="_blank" rel="noopener">Creator Guidelines</a></p>';
		wp_die( $h, 'Please check your product', array( 'response' => 200 ) ); // phpcs:ignore -- built from escaped strings
	}
	set_transient( $day, (int) get_transient( $day ) + 1, DAY_IN_SECONDS );
	set_transient( 'ss_cr_report_' . $uid, implode( "\n", $rep ), HOUR_IN_SECONDS );
}, 0 );

/* the product exists now (pending): keep the robot report and the fingerprints for duplicate checks */
add_action( 'transition_post_status', function ( $new, $old, $post ) {
	if ( 'pending' !== $new || 'pending' === $old || 'product' !== $post->post_type ) return;
	$uid = (int) $post->post_author;
	foreach ( array( '_ss_robot' => 'ss_cr_report_', '_ss_cover_md5' => 'ss_cr_cover_', '_ss_demo_md5' => 'ss_cr_demo_' ) as $meta => $tk ) {
		$v = get_transient( $tk . $uid );
		if ( $v ) { update_post_meta( $post->ID, $meta, $v ); delete_transient( $tk . $uid ); }
	}
}, 5, 3 );

/* owner email gets the robot report */
add_filter( 'wp_mail', function ( $a ) {
	if ( empty( $a['subject'] ) || 0 !== strpos( (string) $a['subject'], 'New product for review: ' ) ) return $a;
	if ( preg_match( '#post=(\d+)#', (string) $a['message'], $m ) ) {
		$r = get_post_meta( (int) $m[1], '_ss_robot', true );
		if ( $r ) $a['message'] .= "\n\nRobot check (passed):\n" . $r;
	}
	return $a;
} );
