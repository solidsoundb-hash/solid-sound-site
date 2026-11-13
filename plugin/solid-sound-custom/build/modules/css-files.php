<?php
/**
 * Speed (TZ, owner 13.11.2026 "берись"): big inline <style> blocks printed by the theme and WordPress on every page
 * (Impreza theme options ~22 KB, header ~24 KB, product grid ~16 KB, Additional CSS ~9 KB, block styles…) are moved
 * into static CSS files: wp-content/uploads/ss-css/<hash>.css. The <style> is replaced by a <link> at the same
 * place, so the order of styles does not change. Same content = same file name, so the browser keeps it in its cache
 * for a year; when the theme options change, the hash changes and a new file is written automatically.
 *
 * Only blocks of 3 KB+ with no relative url(...) (they would break when moved) and no media attribute.
 * Off: ?ss_cssfiles=0 (for a quick comparison), and always off in the original version (?ss_v2=0).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_cssfiles_on() {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || is_feed() || is_customize_preview() ) return false;
	if ( isset( $_GET['ss_cssfiles'] ) && '0' === $_GET['ss_cssfiles'] ) return false;
	if ( 'GET' !== ( $_SERVER['REQUEST_METHOD'] ?? 'GET' ) ) return false;
	return true;
}

function ss_cssfiles_dir() {
	$u = wp_upload_dir( null, false );
	if ( ! empty( $u['error'] ) ) return null;
	return array( 'dir' => trailingslashit( $u['basedir'] ) . 'ss-css/', 'url' => trailingslashit( set_url_scheme( $u['baseurl'], 'https' ) ) . 'ss-css/' );
}

function ss_cssfiles_rewrite( $html ) {
	if ( strlen( $html ) < 2000 || false === stripos( $html, '<style' ) || false === stripos( $html, '</html>' ) ) return $html;
	$d = ss_cssfiles_dir(); if ( ! $d ) return $html;
	if ( ! is_dir( $d['dir'] ) && ! wp_mkdir_p( $d['dir'] ) ) return $html;
	// never touch text inside <script>…</script> (a script may build CSS in a string)
	$parts = preg_split( '#(<script\b.*?</script>)#is', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
	foreach ( $parts as $i => $p ) {
		if ( 1 === $i % 2 ) continue;
		$parts[ $i ] = ss_cssfiles_part( $p, $d );
	}
	return implode( '', $parts );
}

function ss_cssfiles_part( $html, $d ) {
	return preg_replace_callback( '#<style\b([^>]*)>(.*?)</style>#is', function ( $m ) use ( $d ) {
		list( $all, $attrs, $css ) = $m;
		if ( strlen( $css ) < 3072 ) return $all;
		if ( preg_match( '/\bmedia\s*=/i', $attrs ) ) return $all;
		if ( preg_match( '/url\(\s*[\'"]?(?!data:|https?:|\/\/|\/|#)/i', $css ) ) return $all; // relative url() would break
		$name = substr( md5( $css ), 0, 16 ) . '.css';
		$file = $d['dir'] . $name;
		if ( ! file_exists( $file ) ) {
			$tmp = $file . '.' . wp_rand() . '.tmp';
			if ( false === @file_put_contents( $tmp, $css ) || ! @rename( $tmp, $file ) ) { @unlink( $tmp ); return $all; }
		}
		$id = preg_match( '/\bid\s*=\s*["\']([^"\']+)/i', $attrs, $mm ) ? ' id="' . esc_attr( $mm[1] ) . '"' : '';
		return '<link rel="stylesheet"' . $id . ' href="' . esc_url( $d['url'] . $name ) . '">';
	}, $html );
}

add_action( 'template_redirect', function () {
	if ( ! ss_cssfiles_on() ) return;
	ob_start( 'ss_cssfiles_rewrite' );
}, 0 );
