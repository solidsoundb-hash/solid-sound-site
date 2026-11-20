<?php
/**
 * (20.11.2026, speed — owner: «займись оптимизацией сайта сам») YouTube videos load only when the visitor presses ▶.
 * A plain YouTube <iframe> in a product description pulls 1–3 MB and dozens of requests before anyone watches
 * (/product/infinity/ weighed 6.7 MB, 213 requests). Here the iframe becomes its own cover picture with a ▶ button,
 * same size; a press puts the real player in its place and it starts at once. No change in look until then.
 * Off: option ss_ytlite_off = 1.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_ytlite_swap( $html ) {
	if ( false === strpos( $html, 'youtube' ) || get_option( 'ss_ytlite_off' ) || is_admin() || wp_doing_ajax() ) return $html;
	return preg_replace_callback( '#<iframe\b[^>]*\bsrc=["\'](?:https?:)?//(?:www\.)?youtube(?:-nocookie)?\.com/embed/([A-Za-z0-9_-]{6,20})([^"\']*)["\'][^>]*>\s*</iframe>#i', function ( $m ) {
		$id = $m[1];
		$GLOBALS['ss_ytl_used'] = 1;
		$h  = 0;
		if ( preg_match( '#\bheight=["\']?(\d+)#i', $m[0], $x ) ) $h = (int) $x[1];
		$t  = preg_match( '#\btitle=["\']([^"\']+)#i', $m[0], $x ) ? $x[1] : 'YouTube video';
		return '<span class="ss-ytl" data-yt="' . esc_attr( $id ) . '" role="button" tabindex="0" aria-label="' . esc_attr( 'Play: ' . $t ) . '"' . ( $h ? ' style="--h:' . $h . 'px"' : '' ) . '>'
			. '<img src="https://i.ytimg.com/vi/' . esc_attr( $id ) . '/hqdefault.jpg" alt="" loading="lazy" decoding="async" width="480" height="360">'
			. '<i aria-hidden="true"></i></span>';
	}, $html );
}
add_filter( 'the_content', 'ss_ytlite_swap', 99 );
add_filter( 'woocommerce_short_description', 'ss_ytlite_swap', 99 );

add_action( 'wp_footer', function () {
	if ( empty( $GLOBALS['ss_ytl_used'] ) ) return; /* only on pages that had a video */ ?>
<style>.ss-ytl{position:relative;display:block;width:100%;aspect-ratio:16/9;max-height:var(--h,none);background:#000;border-radius:8px;overflow:hidden;cursor:pointer}.ss-ytl img{width:100%;height:100%;object-fit:cover;display:block;opacity:.92}.ss-ytl i{position:absolute;left:50%;top:50%;width:68px;height:48px;margin:-24px 0 0 -34px;border-radius:12px;background:#f00;box-shadow:0 4px 14px rgba(0,0,0,.5)}.ss-ytl i:after{content:"";position:absolute;left:27px;top:14px;border-left:18px solid #fff;border-top:10px solid transparent;border-bottom:10px solid transparent}.ss-ytl:hover i,.ss-ytl:focus-visible i{background:#e00;transform:scale(1.06)}.ss-ytl iframe{position:absolute;inset:0;width:100%;height:100%;border:0}</style>
<script>(function(){function go(b){if(b.querySelector('iframe'))return;var f=document.createElement('iframe');f.src='https://www.youtube.com/embed/'+b.getAttribute('data-yt')+'?autoplay=1&rel=0';f.title=b.getAttribute('aria-label')||'YouTube video';f.allow='autoplay; encrypted-media; picture-in-picture; fullscreen';f.allowFullscreen=true;b.appendChild(f);b.removeAttribute('role');b.removeAttribute('tabindex')}
document.addEventListener('click',function(e){var b=e.target.closest&&e.target.closest('.ss-ytl');if(b){e.preventDefault();go(b)}});document.addEventListener('keydown',function(e){if((e.key==='Enter'||e.key===' ')&&e.target.classList&&e.target.classList.contains('ss-ytl')){e.preventDefault();go(e.target)}})})();</script>
<?php }, 50 );
