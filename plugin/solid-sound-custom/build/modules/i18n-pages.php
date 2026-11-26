<?php
/**
 * (22.11.2026 owner: «делаем сейчас, переводы и тексты»; «Копирайтер») Pages on the way to a purchase in the visitor's language
 * WITHOUT copies of the pages: /de/services-prices/ shows the same English page (one source — edits to the English page show up
 * in every language at once), and the server swaps the visible texts by a dictionary before sending the HTML (no flash of English,
 * nothing extra for weak phones). Texts printed later by scripts (calculator, filters, header) are swapped by a small walker
 * (MutationObserver + requestAnimationFrame, no timers) with the same dictionary.
 *  - routing: custom.php (ss_lang_url block) strips /de/ and forces the language, like /de/product/…;
 *  - dictionaries: modules/i18n-dict-<lang>.php (JSON inside: "English text": "translation"); only languages listed in
 *    ss_vp_langs() are switched on — others keep going to the English page;
 *  - SEO: hreflang to every switched-on language + canonical to itself.
 * Prices, product names and brands are never in the dictionaries.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_vp_langs() { return array( 'de', 'fr', 'es' ); }
function ss_vp_slugs() { return 'all-products|services-prices|studio-calculator|services-calculator|mastering-album-calculator|free|sell|faq|why-solid-sound|get-heard|free-demo-feedback|terms|refund_returns|license|privacy-policy|legal-notice|about|creator-terms'; }
/* the English pages behind the slugs (for links printed by WordPress: get_permalink → /de/…) */
function ss_vp_page_ids() { return array( 2, 1947, 1260, 1498, 2108, 1970, 567, 1709, 2169, 35, 385, 3, 2170, 2617, 1972 ); }
function ss_vp_lang() { return empty( $GLOBALS['ss_vp'] ) ? '' : (string) $GLOBALS['ss_vp']; }
function ss_vp_is_page() { return '' !== ss_vp_lang(); }
/* language switcher URL on a translated page: the same page in that language, English, or the language's home page */
function ss_vp_url( $slug, $path ) {
	if ( ! ss_vp_is_page() ) return null;
	$home = untrailingslashit( get_option( 'home' ) );
	if ( 'en' === $slug ) return $home . '/en' . $path;
	if ( in_array( $slug, ss_vp_langs(), true ) ) return $home . '/' . $slug . $path;
	return function_exists( 'pll_home_url' ) ? pll_home_url( $slug ) : $home . '/' . $slug . '/';
}
function ss_vp_dict( $l ) {
	static $c = array();
	if ( ! isset( $c[ $l ] ) ) { $f = __DIR__ . '/i18n-dict-' . preg_replace( '/[^a-z]/', '', $l ) . '.php'; $c[ $l ] = is_readable( $f ) ? (array) include $f : array(); }
	return $c[ $l ];
}

/* language of any page that is one of ours: /de/<slug>/ (virtual) */
add_filter( 'pll_check_canonical_url', function ( $u ) { return ss_vp_keep_lang() ? false : $u; }, 99 );
add_filter( 'redirect_canonical', function ( $u ) { return ss_vp_keep_lang() ? false : $u; }, 99 );
/* (24.11, live check: /de/services-prices/ → 301 /en/ «x-redirect-by: Polylang») Polylang compares the page language (en) with the
   current one (de) on 'wp' and redirects — pages are translatable, products are not, so /de/product/ never hit it. Off for our pages. */
/* Same for /de/cart/, /de/checkout/, /de/my-account/ (WooCommerce pages are translatable pages too → they went to /en/). */
function ss_vp_keep_lang() {
	if ( ss_vp_is_page() ) return true;
	$f = (string) ( $GLOBALS['ss_forced_lang'] ?? '' );
	return '' !== $f && in_array( $f, ss_vp_langs(), true ) && preg_match( '#^/(cart|checkout|my-account)(/|\?|$)#', (string) ( $GLOBALS['ss_req_path'] ?? '' ) );
}
add_action( 'wp', function () {
	if ( ! ss_vp_keep_lang() || ! function_exists( 'PLL' ) ) return;
	$p = PLL();
	if ( isset( $p->choose_lang ) && is_object( $p->choose_lang ) ) { remove_action( 'wp', array( $p->choose_lang, 'check_language_code_in_url' ) ); remove_action( 'template_redirect', array( $p->choose_lang, 'check_language_code_in_url' ) ); }
}, 1 );

/* SEO: the page in every switched-on language */
function ss_vp_path() { return preg_replace( '#\?.*$#', '', preg_replace( '#^/(en|' . implode( '|', ss_vp_langs() ) . ')(?=/)#', '', (string) ( $_SERVER['REQUEST_URI'] ?? '/' ) ) ); }
function ss_vp_en_page() {
	if ( ss_vp_is_page() ) return true;
	return is_page() && in_array( (int) get_queried_object_id(), ss_vp_page_ids(), true ) && ( ! function_exists( 'pll_current_language' ) || 'en' === pll_current_language() );
}
add_filter( 'pll_rel_hreflang_attributes', function ( $h ) { return ( ss_vp_en_page() && ! is_front_page() ) ? array() : $h; }, 100 );
add_action( 'wp_head', function () {
	if ( is_front_page() || ! ss_vp_en_page() ) return;
	$home = untrailingslashit( get_option( 'home' ) ); $p = ss_vp_path();
	$loc = array( 'de' => 'de', 'fr' => 'fr', 'es' => 'es', 'ua' => 'uk', 'it' => 'it', 'pt' => 'pt' );
	echo '<link rel="alternate" hreflang="en" href="' . esc_url( $home . '/en' . $p ) . "\" />\n";
	foreach ( ss_vp_langs() as $l ) echo '<link rel="alternate" hreflang="' . esc_attr( $loc[ $l ] ?? $l ) . '" href="' . esc_url( $home . '/' . $l . $p ) . "\" />\n";
	echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $home . '/en' . $p ) . "\" />\n";
}, 2 );
add_filter( 'get_canonical_url', function ( $u ) { return ss_vp_is_page() ? untrailingslashit( get_option( 'home' ) ) . '/' . ss_vp_lang() . ss_vp_path() : $u; }, 99 );
add_filter( 'wpseo_canonical', function ( $u ) { return ss_vp_is_page() ? untrailingslashit( get_option( 'home' ) ) . '/' . ss_vp_lang() . ss_vp_path() : $u; }, 99 );

/* swap the texts in the finished HTML (only on /de/<slug>/ and on /de/ shop pages: product, cart, checkout, account) */
function ss_vp_on() {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || is_front_page() || is_feed() ) return ''; /* the home app has its own translations */
	$l = ss_vp_lang();
	if ( ! $l && ! empty( $GLOBALS['ss_forced_lang'] ) && in_array( $GLOBALS['ss_forced_lang'], ss_vp_langs(), true ) ) $l = $GLOBALS['ss_forced_lang'];
	if ( ! $l && function_exists( 'pll_current_language' ) && in_array( pll_current_language(), ss_vp_langs(), true ) ) $l = pll_current_language();
	return $l && ss_vp_dict( $l ) ? $l : '';
}
/* "@prefix:from ": "ab " (needs a number after it) and "@start:Add to cart: " (any text after it) — texts like "from €60" / "from 12 €" (a number after the word, printed by scripts with the visitor's currency) */
function ss_vp_prefix( $t, $D ) {
	static $P = null;
	if ( null === $P ) { $P = array(); foreach ( $D as $k => $v ) { if ( 0 === strpos( $k, '@prefix:' ) ) $P[] = array( substr( $k, 8 ), $v, 1 ); elseif ( 0 === strpos( $k, '@start:' ) ) $P[] = array( substr( $k, 7 ), $v, 0 ); } }
	foreach ( $P as $x ) {
		if ( 0 === strpos( $t, $x[0] ) && ( ! $x[2] || preg_match( '/\d/', substr( $t, strlen( $x[0] ) ) ) ) ) return $x[1] . substr( $t, strlen( $x[0] ) );
	}
	return null;
}
function ss_vp_tr_html( $html, $l ) {
	$D = ss_vp_dict( $l ); if ( ! $D || strlen( $html ) < 200 ) return $html;
	$look = function ( $raw ) use ( $D ) {
		$t = trim( preg_replace( '/\s+/u', ' ', html_entity_decode( $raw, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
		if ( '' === $t ) return null;
		if ( isset( $D[ $t ] ) ) return $D[ $t ];
		$r = ss_vp_prefix( $t, $D ); if ( null !== $r ) return $r;
		/* German/French WordPress prints „…“ « … » ‘ ’: dictionary keys use plain quotes (26.11, Copywriter) */
		$q = preg_replace( array( '/«[\s\x{00A0}\x{202F}]*/u', '/[\s\x{00A0}\x{202F}]*»/u' ), '"', $t );
		$q = str_replace( array( '„', '“', '”', '‘', '’', "\u{00A0}" ), array( '"', '"', '"', "'", "'", ' ' ), $q );
		return ( $q !== $t && isset( $D[ $q ] ) ) ? $D[ $q ] : null;
	};
	/* keep scripts, styles and form text areas as they are */
	$parts = preg_split( '#(<(script|style|textarea|noscript)\b[^>]*>.*?</\2>)#is', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
	if ( ! is_array( $parts ) ) return $html; /* regex limit hit: send the page as it is (English) rather than break it */
	$out = '';
	for ( $i = 0, $n = count( $parts ); $i < $n; $i++ ) {
		$s = $parts[ $i ];
		if ( preg_match( '#^<(script|style|textarea|noscript)\b#i', $s ) ) { $out .= $s; $i++; continue; } /* skip the captured tag name */
		/* text between tags */
		$s = preg_replace_callback( '/>([^<>]*[A-Za-z][^<>]*)</u', function ( $m ) use ( $look ) {
			$r = $look( $m[1] ); if ( null === $r ) return $m[0];
			preg_match( '/^\s*/u', $m[1], $a ); preg_match( '/\s*$/u', $m[1], $b );
			return '>' . $a[0] . esc_html( $r ) . $b[0] . '<';
		}, $s );
		/* inside tags: attributes people read (placeholder, aria-label, title, alt; value of buttons only; meta description / og
		   texts) and links to the English versions of the translated pages → this language (not the language switcher: hreflang) */
		$s = preg_replace_callback( '/<(a|link|input|button|img|meta|textarea|select|span|div|i|svg|label|form)\b[^>]*>/i', function ( $m ) use ( $look, $l ) {
			$tag = $m[0]; $name = strtolower( $m[1] );
			$attrs = 'placeholder|aria-label|title|alt';
			if ( 'input' === $name && preg_match( '/\btype=["\']?(submit|button)/i', $tag ) ) $attrs .= '|value';
			if ( 'meta' === $name ) { if ( ! preg_match( '/\b(name|property)=["\'](description|og:title|og:description|twitter:title|twitter:description)["\']/i', $tag ) ) return $tag; $attrs = 'content'; }
			$tag = preg_replace_callback( '/\s(' . $attrs . ')="([^"]*[A-Za-z][^"]*)"/u', function ( $a ) use ( $look ) {
				$r = $look( $a[2] ); return null === $r ? $a[0] : ' ' . $a[1] . '="' . esc_attr( $r ) . '"';
			}, $tag );
			if ( ( 'a' === $name || 'form' === $name ) && false === stripos( $tag, 'hreflang' ) ) {
				$tag = preg_replace( '#((?:href|action)=["\'])(https?://solid-sound\.net)?/(?:en/)?(' . ss_vp_slugs() . ')(?=[/?\#"\'])#', '$1$2/' . $l . '/$3', $tag );
			}
			return $tag;
		}, $s );
		if ( ! is_string( $s ) ) return $html;
		$out .= $s;
	}
	return $out;
}
add_action( 'template_redirect', function () {
	$l = ss_vp_on(); if ( ! $l ) return;
	ob_start( function ( $html ) use ( $l ) { return ss_vp_tr_html( $html, $l ); } );
}, 9999 );

/* texts printed by scripts after load (header, calculator, filters, cart drawer): same dictionary */
add_action( 'wp_footer', function () {
	$l = ss_vp_on(); if ( ! $l ) return;
	$D = ss_vp_dict( $l ); $js = array();
	foreach ( $D as $k => $v ) if ( mb_strlen( $k ) <= 160 ) $js[ $k ] = $v;
	?>
<script id="ss-vp-js">window.ssVPL=<?php echo wp_json_encode( ss_vp_langs() ); ?>;(function(){var D=<?php echo wp_json_encode( $js, JSON_UNESCAPED_UNICODE ); ?>,SK={SCRIPT:1,STYLE:1,TEXTAREA:1,NOSCRIPT:1,CODE:1},A=['placeholder','aria-label','title'];
var P=[];Object.keys(D).forEach(function(k){if(k.indexOf('@prefix:')===0)P.push([k.slice(8),D[k],1]);else if(k.indexOf('@start:')===0)P.push([k.slice(7),D[k],0])});
function f(k){if(D[k])return D[k];var q=k.replace(/«[\s\u00A0\u202F]*|[\s\u00A0\u202F]*»/g,'"').replace(/[„“”]/g,'"').replace(/[‘’]/g,"'").replace(/\u00A0/g,' ');if(q!==k&&D[q])return D[q];for(var i=0;i<P.length;i++)if(k.indexOf(P[i][0])===0&&(!P[i][2]||/\d/.test(k.slice(P[i][0].length))))return P[i][1]+k.slice(P[i][0].length);return ''}
window.ssVPT=function(k){return f(k)||k};
function t(n){var p=n.parentNode;if(!p||SK[p.nodeName])return;var v=n.nodeValue,k=v.replace(/\s+/g,' ').trim(),r=k&&f(k);if(r&&r!==k)n.nodeValue=v.replace(v.trim(),r)}
function at(e){for(var i=0;i<A.length;i++){var x=e.getAttribute(A[i]);if(x){x=x.trim();if(D[x]&&D[x]!==x)e.setAttribute(A[i],D[x])}}if(e.tagName==='INPUT'&&(e.type==='submit'||e.type==='button')&&D[e.value]&&D[e.value]!==e.value)e.value=D[e.value]}
function walk(r){if(!r)return;if(r.nodeType===3){t(r);return}if(r.nodeType!==1||SK[r.nodeName])return;var w=document.createTreeWalker(r,NodeFilter.SHOW_TEXT),n;while(n=w.nextNode())t(n);at(r);[].forEach.call(r.querySelectorAll('[placeholder],[aria-label],[title],input[type=submit],input[type=button]'),at)}
var q=[],busy=0;function run(){busy=0;var a=q;q=[];a.forEach(walk)}
walk(document.body);new MutationObserver(function(ms){ms.forEach(function(m){if(m.type==='characterData')q.push(m.target);else [].forEach.call(m.addedNodes,function(n){q.push(n)})});if(!busy){busy=1;/* one pass per frame, before paint (no English flash) */requestAnimationFrame(run)}}).observe(document.body,{childList:true,subtree:true,characterData:true});
})();</script>
	<?php
}, 100 );

/* 25.11 owner «Исправь»: terms §3 said prices are in US dollars, but the store currency is EUR since 21.11 (modules/currency.php).
 * The page text lives in the database; this swaps the sentence on output (page and the checkout terms box). Undo: delete this block. */
function ss_terms_eur( $c ) {
	return ( is_string( $c ) && false !== strpos( $c, 'Prices are set in US dollars' ) ) ? str_replace( 'Prices are set in US dollars', 'Prices are set in euros (EUR)', $c ) : $c;
}
add_filter( 'the_content', 'ss_terms_eur', 99 );
add_filter( 'woocommerce_format_content', 'ss_terms_eur', 99 );
