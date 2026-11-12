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
