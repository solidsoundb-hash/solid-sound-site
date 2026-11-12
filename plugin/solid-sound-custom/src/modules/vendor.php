<?php
/**
 * Author (vendor) dashboard — fixes found by the 12.11.2026 audit (robot screenshots + page structure).
 * Phones: the WCFM side menu was an absolute 76 px column ON TOP of the content and cut off the left part
 * of every page. Now it is a sticky row of icons under the site menu and the content uses the full width.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_head', function () {
	if ( is_admin() || ! function_exists( 'is_wcfm_page' ) || ! is_wcfm_page() ) return;
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

