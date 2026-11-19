<?php
/**
 * Author (vendor) dashboard — fixes found by the 12.11.2026 audit (robot screenshots + page structure).
 * Phones: the WCFM side menu was an absolute 76 px column ON TOP of the content and cut off the left part
 * of every page. Now it is a sticky row of icons under the site menu and the content uses the full width.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_head', function () {
	if ( is_admin() || ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return;
	if ( function_exists( 'ss_cs_ok' ) && ss_cs_ok() ) return; /* 19.11: our Creator space has its own tab row — the old WCFM icon row is not needed (it showed as a second menu on phones) */
	echo '<style id="ss-vendor-mobile">@media(max-width:767px){'
		/* menu: one horizontal row of icons, sticky under the site header, scrolls sideways */
		. '#wcfm-content #wcfm_menu{position:sticky!important;top:var(--ssg-h,61px)!important;left:0!important;width:auto!important;height:auto!important;min-height:0!important;'
		. 'display:flex!important;flex-direction:row!important;overflow-x:auto!important;overflow-y:hidden!important;-webkit-overflow-scrolling:touch;scrollbar-width:none;'
		. 'margin:0 0 10px!important;padding:4px!important;border-radius:12px!important;z-index:50!important;box-shadow:0 6px 18px rgba(0,0,0,.35)}'
		. '#wcfm-content #wcfm_menu::-webkit-scrollbar{display:none}'
		. '#wcfm-content #wcfm_menu .wcfm_menu_logo{display:none!important}'
		/* WCFM hides the items on phones (collapsed "toggle" state) — show them as the icon row */
		. '#wcfm-main-contentainer #wcfm-content #wcfm_menu.wcfm_menu_toggle,#wcfm-main-contentainer #wcfm-content #wcfm_menu{width:100%!important;max-width:100%!important;box-sizing:border-box}'
		. '#wcfm-main-contentainer #wcfm-content #wcfm_menu .wcfm_menu_items,#wcfm-main-contentainer #wcfm-content #wcfm_menu.wcfm_menu_toggle .wcfm_menu_items{display:block!important;visibility:visible!important;opacity:1!important}'
		. '#wcfm-main-contentainer #wcfm-content #wcfm_menu .wcfm_menu_items a.wcfm_menu_item{visibility:visible!important;opacity:1!important}'
		. '#wcfm-content #wcfm_menu .wcfm_menu_items{flex:0 0 auto!important;width:auto!important;height:auto!important;margin:0!important;float:none!important}'
		. '#wcfm-content #wcfm_menu .wcfm_menu_items a.wcfm_menu_item{width:74px!important;min-width:74px!important;height:58px!important;padding:6px 2px!important;display:flex!important;flex-direction:column!important;align-items:center!important;justify-content:center!important;gap:3px;box-shadow:none!important;border-radius:10px!important}'
		. '#wcfm-content #wcfm_menu .wcfm_menu_items a.wcfm_menu_item .text{display:block!important;font-size:10px!important;line-height:1.1!important;white-space:normal!important;text-align:center;position:static!important;opacity:1!important;visibility:visible!important;width:auto!important;background:none!important;padding:0!important}'
		. '#wcfm-content #wcfm_menu .wcfm_menu_items a.wcfm_menu_item .wcfmfa,#wcfm-content #wcfm_menu .wcfm_menu_items a.wcfm_menu_item i{font-size:18px!important;margin:0!important}'
		/* content: full width, no left offset left by the old column */
		. '#wcfm-content .wcfm-collapse,#wcfm-content #wcfm_dashboard{margin-left:0!important;padding-left:0!important;width:100%!important;max-width:100%!important}'
		. '#wcfm-content .wcfm-collapse .wcfm-collapse-content,#wcfm-content .wcfm-page-headig{margin-left:0!important;padding-left:12px!important;padding-right:12px!important;width:auto!important}'
		. '#wcfm-content .wcfm-container,#wcfm-content .wcfm-content{max-width:100%!important;overflow-x:auto}'
		. '}</style>';
}, 99 );

/* (12.11.2026 owner) dashboard in the site's dark look: WCFM's own white blocks, tables and inputs */
add_action( 'wp_head', function () {
	if ( is_admin() || ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return;
	$c = '#wcfm-main-contentainer';
	echo '<style id="ss-vendor-dark">'
		. "$c #wcfm-content,$c .wcfm-collapse,$c .wcfm-collapse-content,$c .wcfm-page-headig{background:transparent!important;color:#E5E7EB!important}"
		. "$c .wcfm-container,$c .wcfm-content,$c .page_collapsible,$c .wcfm_dashboard_stats_block,$c .wcfm-tabWrap,$c .wcfm_welcomebox_header,$c .dataTables_wrapper,$c table.dataTable,$c table.dataTable tbody tr,$c .wcfm-action-icon"
		. "{background:#131B2E!important;color:#E5E7EB!important;border-color:rgba(255,255,255,.08)!important;box-shadow:none!important}"
		. "$c table.dataTable thead th,$c table.dataTable tfoot th{background:#0F1626!important;color:#94A3B8!important;border-color:rgba(255,255,255,.08)!important}"
		. "$c table.dataTable td{border-color:rgba(255,255,255,.06)!important;color:#E5E7EB!important}"
		. "$c h1,$c h2,$c h3,$c h4,$c .wcfm-page-heading-text,$c .wcfm_dashboard_stats_block strong,$c label,$c .wcfm_title{color:#F3F4F6!important}"
		. "$c p,$c .description,$c .instructions,$c small{color:#94A3B8}"
		. "$c input[type=text],$c input[type=email],$c input[type=number],$c input[type=url],$c input[type=password],$c input[type=search],$c input[type=tel],$c select,$c textarea"
		. "{background:#0B1220!important;color:#fff!important;border:1px solid rgba(255,255,255,.16)!important;border-radius:10px!important;box-shadow:none!important}"
		. "$c input::placeholder,$c textarea::placeholder{color:#64748B!important}"
		. "$c a{color:#F59E0B}"
		. "$c .wcfm_submit_button,$c input[type=submit],$c button.wcfm_submit_button,$c a.add_new_wcfm_ele_dashboard{background:#F59E0B!important;color:#111!important;border:0!important;border-radius:10px!important}"
		. "$c #wcfm_menu{background:#0F1626!important}"
		. "$c #wcfm_menu .wcfm_menu_items a.wcfm_menu_item{color:#CBD5E1!important}"
		. "$c #wcfm_menu .wcfm_menu_items a.wcfm_menu_item.active,$c #wcfm_menu .wcfm_menu_items.ss-cur a.wcfm_menu_item{background:linear-gradient(135deg,#F59E0B,#D97706)!important;color:#111!important}"
		. '</style>';
}, 100 );


/* (12.11.2026 owner) readable, calm dashboard: the upload form, "Get started" card, tables and buttons in the site colours */
add_action( 'wp_head', function () {
	if ( is_admin() || ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return;
	$c = '#wcfm-main-contentainer';
	$f = '#solid-sound-upload-form';
	echo '<style id="ss-vendor-read">'
		/* upload form: one dark panel, light readable text */
		. "$f{background:#131B2E!important;border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:16px!important;color:#E5E7EB!important}"
		. "$f div,$f section,$f ul,$f li,$f p,$f fieldset,$f label,$f span,$f .acf-fields,$f .acf-field,$f .acf-input,$f .acf-label{background-color:transparent!important}"
		. "$f,$f p,$f span,$f li,$f label,$f .acf-label label,$f .ss-wm-l,$f strong,$f b,$f h1,$f h2,$f h3{color:#E5E7EB!important}"
		. "$f .description,$f .acf-label p,$f i,$f em,$f small{color:#94A3B8!important}"
		. "$f ul.acf-radio-list li,$f ul.acf-checkbox-list li{border:1px solid rgba(255,255,255,.12)!important;border-radius:10px!important;background:#0F1626!important}"
		. "$f ul.acf-radio-list li:has(input:checked),$f ul.acf-checkbox-list li:has(input:checked){border-color:#F59E0B!important;background:rgba(245,158,11,.1)!important}"
		. "$f input[type=radio],$f input[type=checkbox]{accent-color:#F59E0B}"
		. "$f [type=submit],$f button[type=submit],$c .ss-wm-send,$c .ss-ap-send{background:#F59E0B!important;color:#111!important;border:0!important;font-weight:800!important;border-radius:10px!important}"
		. "$f .ss-req,$f .acf-required{color:#F59E0B!important}"
		/* the white strip with the page title above the form */
		. "$c .wcfm-collapse-content > div:first-child,$c .wcfm-page-headig{background:transparent!important}"
		/* Get started card: dark with an orange edge instead of the purple gradient */
		. "$c .ssw-card.ssw-start{background:#131B2E!important;border:1px solid rgba(245,158,11,.35)!important;color:#E5E7EB!important}"
		. "$c .ssw-card.ssw-start *{color:#E5E7EB}$c .ssw-card.ssw-start small,$c .ssw-card.ssw-start p,$c .ssw-card.ssw-start .ssw-sub{color:#94A3B8!important}"
		. "$c .ssw-card.ssw-start > div > div,$c .ssw-card.ssw-start li{background:rgba(255,255,255,.04)!important;border-color:rgba(255,255,255,.08)!important}"
		. "$c .ssw-card.ssw-start a,$c .ssw-card.ssw-start button{background:#F59E0B!important;color:#111!important;border:0!important}"
		/* tables */
		. "$c table,$c thead,$c tbody,$c tr,$c th,$c td{background-color:transparent!important;color:#E5E7EB!important;border-color:rgba(255,255,255,.08)!important}"
		. "$c thead th{color:#94A3B8!important;font-size:12px!important;letter-spacing:.04em}"
		/* phones: icon labels must not run into each other */
		. '@media(max-width:767px){#wcfm-content #wcfm_menu .wcfm_menu_items a.wcfm_menu_item{width:82px!important;min-width:82px!important}#wcfm-content #wcfm_menu .wcfm_menu_items a.wcfm_menu_item .text{font-size:9.5px!important;letter-spacing:0!important;overflow:hidden;text-overflow:ellipsis;max-width:78px}}'
		. '</style>';
}, 101 );

/* ---------- authors see only their own area ---------- */
function ss_vendor_only() {
	return is_user_logged_in() && function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor() && ! current_user_can( 'manage_woocommerce' );
}
/* no WordPress admin for authors (AJAX and form posts still work) */
add_action( 'admin_init', function () {
	if ( ! ss_vendor_only() || wp_doing_ajax() ) return;
	$f = isset( $_SERVER['SCRIPT_NAME'] ) ? basename( (string) $_SERVER['SCRIPT_NAME'] ) : '';
	if ( in_array( $f, array( 'admin-post.php', 'async-upload.php', 'media-upload.php' ), true ) ) return;
	wp_safe_redirect( function_exists( 'get_wcfm_url' ) ? get_wcfm_url() : home_url( '/en/store-manager/' ) );
	exit;
} );
add_filter( 'show_admin_bar', function ( $show ) { return ss_vendor_only() ? false : $show; } );
/* inside the dashboard: only our sections + editing their own products */
add_action( 'template_redirect', function () {
	if ( ! ss_vendor_only() || ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return;
	global $wp;
	foreach ( array_keys( (array) $wp->query_vars ) as $k ) {
		if ( 0 !== strpos( $k, 'wcfm-' ) ) continue;
		if ( preg_match( '#^wcfm-(ss-[a-z0-9-]+|products-manage|profile|notices|notice-view|messages)$#', $k ) ) continue;
		wp_safe_redirect( function_exists( 'get_wcfm_url' ) ? get_wcfm_url() : home_url( '/en/store-manager/' ) );
		exit;
	}
}, 20 );
/* authors: the menu shows only our sections (the plugin's own leftovers — orders, coupons, articles… — are removed) */
add_action( 'wp_footer', function () {
	if ( ! ss_vendor_only() || ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return;
	echo '<script>(function(){function clean(){var m=document.getElementById("wcfm_menu");if(!m)return;'
		. 'm.querySelectorAll(".wcfm_menu_items").forEach(function(d){var a=d.querySelector("a");var h=a?(a.getAttribute("href")||""):"";'
		. 'if(/store-manager\/?$|store-manager\/ss-|add-product|logout|customer-logout/.test(h))return;d.remove()})}'
		. '(window.ssOnDom||function(f){document.readyState!=="loading"?f():document.addEventListener("DOMContentLoaded",f)})(clean)})();</script>';
}, 99 );

/* (12.11.2026) "Sell" for someone who is already a creator (or the owner): straight to the dashboard, not the sign-up form */
add_action( 'template_redirect', function () {
	if ( ! is_user_logged_in() ) return;
	$p = strtolower( trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' ) );
	if ( ! preg_match( '#^([a-z]{2}/)?(sell|vendor-register|vendor-membership)$#', $p ) ) return;
	$is = ( function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor() ) || current_user_can( 'manage_woocommerce' );
	if ( ! $is ) { $u = wp_get_current_user(); $is = (bool) array_intersect( (array) $u->roles, array( 'wcfm_vendor', 'seller', 'vendor' ) ); }
	if ( ! $is ) return;
	wp_safe_redirect( function_exists( 'get_wcfm_url' ) ? get_wcfm_url() : home_url( '/en/store-manager/' ) );
	exit;
}, 2 );

/* (15.11.2026) one creator sign-up page: the old /vendor-register/ and /vendor-membership/ (menu "Sell", footer, home)
   showed the bare WCFM form -> send them to the compact /sell/ page. WCFM's own steps (?vmstep=…, any query) stay as they are. */
add_action( 'template_redirect', function () {
	if ( is_admin() || wp_doing_ajax() || 'GET' !== ( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : 'GET' ) ) return;
	if ( ! empty( $_GET ) ) return;
	$p = strtolower( trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' ) );
	if ( ! preg_match( '#^([a-z]{2}/)?(vendor-register|vendor-membership)$#', $p ) ) return;
	wp_safe_redirect( home_url( '/sell/' ), 302 );
	exit;
}, 3 );
