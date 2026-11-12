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
		array( 'Plugins: tell the truth', 'List every third-party plugin the project uses. Choose "No External Plugins" only if the project uses Ableton\'s own devices — our robot checks this. Using Serum, Sylenth and co.? Also include the bounced (frozen) audio of those tracks and keep the MIDI, so every buyer can use the project.' ),
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
	$kinds = array( 'Ableton projects' => '#\.als$#', 'audio files' => '#\.(wav|aif|aiff|flac|mp3)$#', 'MIDI files' => '#\.(mid|midi)$#', 'presets' => '#\.(fxp|fxb|serumpreset|vital|adv|adg|h2p|nmsv|vstpreset|aupreset)$#', 'guides (PDF/TXT)' => '#\.(pdf|txt|rtf|nfo)$#' );
	$inside = array();
	foreach ( $kinds as $label => $re ) { $c = 0; foreach ( array_keys( $names ) as $n ) if ( preg_match( $re, $n ) && ( 'Ableton projects' !== $label || false === strpos( $n, 'backup' ) ) ) $c++; if ( $c ) $inside[ $label ] = $c; }
	$GLOBALS['ss_cr_inside'] = $inside;
	if ( in_array( $fmt, array( 'templates', 'reconstructed-templates' ), true ) ) {
		if ( ! $als ) { $err[] = 'No Ableton project (.als) found in the zip. In Ableton use File → Collect All and Save, then zip the whole project folder.'; $z->close(); return; }
		usort( $als, function ( $a, $b ) { return substr_count( $a, '/' ) - substr_count( $b, '/' ); } );
		$raw = $z->getFromName( $als[0] );
		$xml = $raw ? @gzdecode( $raw ) : false;
		if ( ! $xml ) { $rep[] = 'als: could not read ' . basename( $als[0] ); $z->close(); return; }
		list( $plugins, $files ) = ss_cr_read_als( $xml );
		$spec = array(
			'Audio tracks'  => preg_match_all( '#<AudioTrack\b#', $xml ),
			'MIDI tracks'   => preg_match_all( '#<MidiTrack\b#', $xml ),
			'Return tracks' => preg_match_all( '#<ReturnTrack\b#', $xml ),
			'Group tracks'  => preg_match_all( '#<GroupTrack\b#', $xml ),
		);
		if ( preg_match( '#Creator="Ableton Live ([0-9.]+)#', $xml, $mv ) ) $spec['Made in'] = 'Ableton Live ' . $mv[1];
		if ( preg_match( '#<MasterTrack>.*?<Tempo>.*?<Manual Value="([0-9.]+)"#s', $xml, $mt ) ) $spec['Tempo'] = round( (float) $mt[1] );
		$GLOBALS['ss_cr_spec'] = $spec;
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
			$GLOBALS['ss_cr_demo_len'] = $len;
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
	$spec = isset( $GLOBALS['ss_cr_spec'] ) ? $GLOBALS['ss_cr_spec'] : array();
	if ( ! empty( $_FILES['product_file']['size'] ) ) $spec['Download size'] = size_format( (int) $_FILES['product_file']['size'], 1 );
	if ( ! empty( $GLOBALS['ss_cr_demo_len'] ) ) $spec['Length'] = gmdate( 'i:s', (int) $GLOBALS['ss_cr_demo_len'] );
	set_transient( 'ss_cr_spec_' . $uid, $spec, HOUR_IN_SECONDS );
	if ( ! empty( $GLOBALS['ss_cr_inside'] ) ) set_transient( 'ss_cr_inside_' . $uid, $GLOBALS['ss_cr_inside'], HOUR_IN_SECONDS );
}, 0 );

/* the product exists now (pending): keep the robot report and the fingerprints for duplicate checks */
add_action( 'transition_post_status', function ( $new, $old, $post ) {
	if ( 'pending' !== $new || 'pending' === $old || 'product' !== $post->post_type ) return;
	$uid = (int) $post->post_author;
	$inside = get_transient( 'ss_cr_inside_' . $uid );
	if ( $inside ) { update_post_meta( $post->ID, '_ss_inside', $inside ); delete_transient( 'ss_cr_inside_' . $uid ); }
	$spec = get_transient( 'ss_cr_spec_' . $uid );
	if ( $spec && function_exists( 'wc_get_product' ) && ( $p = wc_get_product( $post->ID ) ) ) {
		delete_transient( 'ss_cr_spec_' . $uid );
		$attrs = $p->get_attributes(); $have = array();
		foreach ( $attrs as $a ) $have[] = strtolower( $a->is_taxonomy() ? wc_attribute_label( $a->get_name() ) : $a->get_name() );
		$auto = array();
		foreach ( array( 'Audio tracks', 'MIDI tracks', 'Return tracks', 'Download size', 'Length' ) as $k ) {
			if ( ! isset( $spec[ $k ] ) || '' === (string) $spec[ $k ] ) continue;
			$dup = false; foreach ( $have as $h ) if ( false !== strpos( $h, strtolower( strtok( $k, ' ' ) ) ) ) $dup = true;
			if ( $dup ) continue;
			$na = new WC_Product_Attribute(); $na->set_name( $k ); $na->set_options( array( (string) $spec[ $k ] ) ); $na->set_visible( true ); $na->set_variation( false );
			$attrs[ sanitize_title( $k ) ] = $na; $auto[] = $k;
		}
		if ( $auto ) { $p->set_attributes( $attrs ); $p->save(); }
		update_post_meta( $post->ID, '_ss_spec_auto', $spec );
	}
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

/* ======================================================================
   Telegram moderation: one tap from the phone (owner 12.11.2026 — no reviewers yet)
   ====================================================================== */
function ss_cr_reasons() {
	return array(
		'sound'   => array( 'Sound quality', "The mix or master is not at release level yet (balance, low end, loudness or clarity). Compare your demo with 2–3 tracks you love in the same genre, fix the biggest difference and send it again." ),
		'files'   => array( 'Files / project', "The project does not open cleanly or is not complete. In Ableton use File → Collect All and Save, check that every track plays on another computer, name and colour the tracks, then zip the whole folder again." ),
		'origin'  => array( 'Not original', "The product looks too close to an existing release or contains material you may not own (samples, loops or presets from paid packs). Please send only your own original work." ),
		'listing' => array( 'Cover / description', "The cover or the description needs work: a clear square cover (1000×1000+) and an honest description with genre, BPM, key and what is inside sell much better." ),
		'other'   => array( 'Other', "The team has a question about this product. Please reply to this email." ),
	);
}

function ss_cr_author_mail( $post, $subject, $body ) {
	$u = get_userdata( (int) $post->post_author );
	if ( ! $u || ! $u->user_email ) return;
	wp_mail( $u->user_email, $subject, "Hi " . ( $u->display_name ?: 'there' ) . ",\n\n" . $body . "\n\nCreator Guidelines: " . ss_cr_guidelines_url() . "\nYour dashboard: " . home_url( '/en/store-manager/' ) . "\n\n— Solid Sound" );
}

/* new product waiting for review -> Telegram card with buttons */
add_action( 'transition_post_status', function ( $new, $old, $post ) {
	if ( 'pending' !== $new || 'pending' === $old || 'product' !== $post->post_type ) return;
	if ( ! function_exists( 'ss_tg_api' ) || ! function_exists( 'ss_tg_target' ) ) return;
	$t = ss_tg_target(); if ( empty( $t['chat'] ) ) return;
	$id = $post->ID; $u = get_userdata( (int) $post->post_author );
	$p = function_exists( 'wc_get_product' ) ? wc_get_product( $id ) : null;
	$cap = "🆕 New product for review\n\n" . $post->post_title . "\nby " . ( $u ? $u->display_name : '—' ) . ( $p && $p->get_price() !== '' ? ' · $' . $p->get_price() : '' )
		. "\n\n🤖 Robot: passed\n" . trim( (string) get_post_meta( $id, '_ss_robot', true ) ) . "\n\nPreview: " . get_preview_post_link( $id );
	$kb = array( 'inline_keyboard' => array( array( array( 'text' => '✅ Publish', 'callback_data' => 'ssok:' . $id ), array( 'text' => '🌱 Decline…', 'callback_data' => 'ssno:' . $id ) ) ) );
	$base = array( 'chat_id' => $t['chat'] ); if ( ! empty( $t['thread'] ) ) $base['message_thread_id'] = (int) $t['thread'];
	$img = get_the_post_thumbnail_url( $id, 'large' );
	$r = $img ? ss_tg_api( 'sendPhoto', $base + array( 'photo' => $img, 'caption' => mb_substr( $cap, 0, 1000 ), 'reply_markup' => $kb ) ) : null;
	if ( ! $r || empty( $r['ok'] ) ) $r = ss_tg_api( 'sendMessage', $base + array( 'text' => mb_substr( $cap, 0, 4000 ), 'reply_markup' => $kb, 'disable_web_page_preview' => true ) );
	/* demo as a playable audio message */
	$txt = (string) get_post_field( 'post_content', $id ) . ' ' . (string) get_post_field( 'post_excerpt', $id );
	foreach ( (array) get_post_meta( $id ) as $k => $v ) if ( is_array( $v ) ) foreach ( $v as $x ) if ( is_string( $x ) && false !== stripos( $x, '.mp3' ) ) $txt .= ' ' . $x;
	if ( preg_match( '#https?://[^\s"\'\]]+?\.mp3#i', $txt, $m ) ) ss_tg_api( 'sendAudio', $base + array( 'audio' => $m[0], 'title' => $post->post_title, 'performer' => $u ? $u->display_name : 'Solid Sound' ) );
}, 20, 3 );

/* button presses (routed from ss_tg_webhook) */
function ss_cr_tg_callback( $cq ) {
	$t = ss_tg_target();
	$chat = isset( $cq['message']['chat']['id'] ) ? (string) $cq['message']['chat']['id'] : '';
	if ( empty( $t['chat'] ) || $chat !== (string) $t['chat'] ) { ss_tg_api( 'answerCallbackQuery', array( 'callback_query_id' => $cq['id'] ) ); return array( 'ok' => true ); }
	$data = isset( $cq['data'] ) ? (string) $cq['data'] : '';
	$who  = isset( $cq['from']['first_name'] ) ? sanitize_text_field( $cq['from']['first_name'] ) : 'team';
	$mid  = isset( $cq['message']['message_id'] ) ? (int) $cq['message']['message_id'] : 0;
	$done = function ( $note ) use ( $cq, $chat, $mid ) {
		$old = isset( $cq['message']['caption'] ) ? $cq['message']['caption'] : ( isset( $cq['message']['text'] ) ? $cq['message']['text'] : '' );
		$k = isset( $cq['message']['caption'] ) ? 'editMessageCaption' : 'editMessageText';
		$a = array( 'chat_id' => $chat, 'message_id' => $mid, 'reply_markup' => array( 'inline_keyboard' => array() ) );
		$a[ 'editMessageCaption' === $k ? 'caption' : 'text' ] = mb_substr( $old . "\n\n" . $note, 0, 'editMessageCaption' === $k ? 1000 : 4000 );
		ss_tg_api( $k, $a );
	};
	if ( ! preg_match( '#^ss(ok|no|rs):(\d+)(?::([a-z]+))?$#', $data, $m ) ) { ss_tg_api( 'answerCallbackQuery', array( 'callback_query_id' => $cq['id'] ) ); return array( 'ok' => true ); }
	$post = get_post( (int) $m[2] );
	if ( ! $post || 'product' !== $post->post_type ) { ss_tg_api( 'answerCallbackQuery', array( 'callback_query_id' => $cq['id'], 'text' => 'Product not found' ) ); return array( 'ok' => true ); }
	if ( 'ok' === $m[1] ) {
		wp_update_post( array( 'ID' => $post->ID, 'post_status' => 'publish' ) );
		ss_cr_author_mail( $post, 'Your product is live: ' . $post->post_title, "Great news — \"{$post->post_title}\" is now live on Solid Sound:\n" . get_permalink( $post->ID ) . "\n\nShare the link on your socials: products that get plays and likes in the first days move up to Trending and Best products." );
		$done( '✅ Published by ' . $who );
		ss_tg_api( 'answerCallbackQuery', array( 'callback_query_id' => $cq['id'], 'text' => 'Published' ) );
	} elseif ( 'no' === $m[1] ) {
		$rows = array(); foreach ( ss_cr_reasons() as $k => $r ) $rows[] = array( array( 'text' => $r[0], 'callback_data' => 'ssrs:' . $post->ID . ':' . $k ) );
		ss_tg_api( 'editMessageReplyMarkup', array( 'chat_id' => $chat, 'message_id' => $mid, 'reply_markup' => array( 'inline_keyboard' => $rows ) ) );
		ss_tg_api( 'answerCallbackQuery', array( 'callback_query_id' => $cq['id'], 'text' => 'Choose the reason' ) );
	} else {
		$rs = ss_cr_reasons(); $r = isset( $rs[ $m[3] ] ) ? $rs[ $m[3] ] : $rs['other'];
		wp_update_post( array( 'ID' => $post->ID, 'post_status' => 'draft' ) );
		update_post_meta( $post->ID, '_ss_declined', $m[3] );
		ss_cr_author_mail( $post, 'About your product: ' . $post->post_title, "Thank you for sending \"{$post->post_title}\". It is not published yet — here is what to improve:\n\n" . $r[1] . "\n\nWhen it is ready, open the product in your dashboard (Products), update it and send it for review again. Free guides: " . home_url( '/en/wiki/' ) . "\nWant a detailed review of your project by our studio? Just reply to this email." );
		$done( '🌱 Declined (' . $r[0] . ') by ' . $who );
		ss_tg_api( 'answerCallbackQuery', array( 'callback_query_id' => $cq['id'], 'text' => 'Declined, the creator got an email' ) );
	}
	return array( 'ok' => true );
}

/* the webhook must also receive button presses */
add_action( 'admin_init', function () {
	if ( (int) get_option( 'ss_tg_wh_v', 0 ) >= 3 || ! function_exists( 'ss_tg_token' ) || ! ss_tg_token() ) return;
	$r = ss_tg_api( 'setWebhook', array( 'url' => rest_url( 'ss-chat/v1/tg' ), 'secret_token' => (string) get_option( 'ss_tg_secret', '' ), 'allowed_updates' => array( 'message', 'edited_message', 'callback_query' ) ) );
	if ( $r && ! empty( $r['ok'] ) ) update_option( 'ss_tg_wh_v', 3, false );
} );

/* "What's inside" on the product page — filled by the robot from the uploaded zip (no work for the creator) */
add_action( 'woocommerce_after_single_product_summary', function () {
	global $product;
	if ( ! $product ) return;
	$in = get_post_meta( $product->get_id(), '_ss_inside', true );
	$sp = get_post_meta( $product->get_id(), '_ss_spec_auto', true );
	if ( ! $in && empty( $sp['Made in'] ) ) return;
	$parts = array();
	foreach ( (array) $in as $k => $n ) $parts[] = '<li><b>' . (int) $n . '</b> ' . esc_html( $k ) . '</li>';
	if ( ! empty( $sp['Made in'] ) ) $parts[] = '<li>Made in <b>' . esc_html( $sp['Made in'] ) . '</b></li>';
	if ( ! empty( $sp['Group tracks'] ) ) $parts[] = '<li><b>' . (int) $sp['Group tracks'] . '</b> group tracks</li>';
	echo '<section class="ss-card ss-inside" style="margin:16px 0;padding:16px 18px;border:1px solid rgba(255,255,255,.08);border-radius:14px;background:#131B2E;color:#CBD5E1">'
		. '<h2 style="margin:0 0 8px;font-size:20px;color:#fff">What\'s inside the download</h2><ul style="display:flex;flex-wrap:wrap;gap:8px 18px;margin:0;padding:0;list-style:none">' . implode( '', $parts ) . '</ul>'
		. '<p style="margin:8px 0 0;font-size:12px;color:#94A3B8">Checked automatically when the creator uploaded the files.</p></section>';
}, 8 );


/* dashboard Products list: what happens with each product, in plain words */
function ss_cr_status_note( $pid, $st ) {
	$s = 'display:block;margin-top:4px;font-size:12px;line-height:1.35;color:#94A3B8;max-width:260px';
	if ( 'pending' === $st ) return '<small style="' . $s . '">Robot check passed · usually live within 24 h</small>';
	$d = get_post_meta( $pid, '_ss_declined', true );
	if ( 'draft' === $st && $d ) {
		$rs = ss_cr_reasons(); $r = isset( $rs[ $d ] ) ? $rs[ $d ] : $rs['other'];
		return '<small style="' . $s . ';color:#F59E0B">Needs changes — ' . esc_html( $r[0] ) . '. ' . esc_html( $r[1] ) . ' <a href="' . esc_url( ss_cr_guidelines_url() ) . '" target="_blank" rel="noopener">Guidelines</a></small>';
	}
	return '';
}
/* a product sent again after "needs changes" goes back to review normally */
add_action( 'transition_post_status', function ( $new, $old, $post ) {
	if ( 'pending' === $new && 'product' === $post->post_type ) delete_post_meta( $post->ID, '_ss_declined' );
}, 1, 3 );
