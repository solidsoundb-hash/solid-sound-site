<?php
/**
 * (19.11.2026 owner: «прошерсти сайт, улучши что сможешь») Small fixes from the site-wide QA report (qa/latest/report.md):
 *  - chat button no longer covers "Add to cart" on the catalog (computers: it sits bottom-left where the list has no buttons);
 *  - buttons with the browser's 5px corners get the site's 10px (free pack, free page, calculators);
 *  - catalog sort box gets a label (screen readers) and 16px text on phones (iPhone no longer zooms in);
 *  - links that open a new tab get rel="noopener" (forum and user content);
 *  - content images without size: width/height, smaller file for the screen (srcset) and lazy loading
 *    (free-demo-feedback was 3.5 MB) — WordPress does this itself once it knows which upload the image is;
 *  - /add-product/ and /links/ are not for Google (noindex).
 * Off switch: option ss_polish_off = 1.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
if ( get_option( 'ss_polish_off' ) ) return;

add_action( 'wp_head', function () {
	if ( is_admin() ) return;
	echo '<style id="ss-polish">'
		. 'button.cta,button.go,button.ssf-btn,.ssf button,.ssfb-f button,form.ssf-form button{border-radius:10px!important}'
		/* 23.11 owner: «чат слева, а нужно справа» — the chat stays bottom-right on the catalog too (was moved left 19.11) */
		. '@media(max-width:767px){select[name=orderby],select[name=order],.w-filter select,.w-filter input{font-size:16px!important}}'
		. '</style>';
}, 90 );

add_action( 'wp_footer', function () {
	if ( is_admin() ) return; ?>
<script id="ss-polish-js">(function(){
function fix(root){
 (root||document).querySelectorAll('select[name=order]:not([aria-label]),select[name=orderby]:not([aria-label])').forEach(function(s){s.setAttribute('aria-label','Sort products')});
 (root||document).querySelectorAll('a[target=_blank]').forEach(function(a){var r=(a.getAttribute('rel')||'');if(!/noopener/.test(r))a.setAttribute('rel',(r+' noopener').trim())});
}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',function(){fix()});else fix();
})();</script>
<?php }, 99 );

/* content images: tell WordPress which upload each <img> is, so it adds width/height, srcset and lazy loading itself */
add_filter( 'the_content', function ( $html ) {
	if ( is_admin() || false === strpos( $html, '<img' ) ) return $html;
	$up = wp_get_upload_dir(); $base = $up['baseurl'];
	return preg_replace_callback( '#<img\b[^>]*>#i', function ( $m ) use ( $base ) {
		$t = $m[0];
		if ( preg_match( '/\bwp-image-\d+/', $t ) || ! preg_match( '/\ssrc=["\']([^"\']+)["\']/', $t, $s ) ) return $t;
		$src = html_entity_decode( $s[1] );
		$src = preg_replace( '#^https?://[^/]+#', '', $src );
		$bpath = preg_replace( '#^https?://[^/]+#', '', $base );
		if ( 0 !== strpos( $src, $bpath ) ) return $t;
		$key = 'ss_img_' . md5( $src );
		$id = get_transient( $key );
		if ( false === $id ) {
			$full = preg_replace( '/-\d+x\d+(\.[a-z0-9]+)$/i', '$1', $src );
			$id = (int) attachment_url_to_postid( home_url( $full ) );
			if ( ! $id && $full !== $src ) $id = (int) attachment_url_to_postid( home_url( $src ) );
			set_transient( $key, $id, WEEK_IN_SECONDS );
		}
		if ( ! $id ) return $t;
		if ( preg_match( '/\sclass=["\']/', $t ) ) $t = preg_replace( '/\sclass=(["\'])/', ' class=$1wp-image-' . $id . ' ', $t, 1 );
		else $t = str_replace( '<img', '<img class="wp-image-' . $id . '"', $t );
		return $t;
	}, $html );
}, 11 ); /* before wp_filter_content_tags (priority 12) */

add_filter( 'wp_robots', function ( $r ) {
	if ( is_page( array( 'add-product', 'links' ) ) ) { $r['noindex'] = true; $r['follow'] = true; }
	return $r;
} );
