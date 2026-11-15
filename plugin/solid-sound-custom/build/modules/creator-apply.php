<?php
/**
 * Creator application form, made like WeMake's /join (owner 15.11.2026):
 *  - no e-mail verification code step (WCFM's own switch, also off on the server check);
 *  - no "Country" and no "How many products are ready?" (asked later, in the dashboard / payout settings);
 *  - "Link to your work" and "What do you make?" are optional (as at WeMake);
 *  - new optional "Anything we should know?" text box, before the "please upload for me" checkbox.
 * Only the visitor's form is changed (the /sell/ page and its submit request). The WCFM settings page is never
 * affected, so saving the settings there can't store this edited list. Answers are saved by WCFM as before.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_apply_ctx() {
	if ( wp_doing_ajax() ) {
		return isset( $_POST['controller'] ) && 'wcfm-memberships-registration' === $_POST['controller']; /* the form's submit */
	}
	if ( is_admin() ) return false;
	$p = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
	return ! preg_match( '#store-manager|wcfm|wp-admin#', $p );
}

add_filter( 'wcfm_is_allow_email_verification', function ( $on ) { return ss_apply_ctx() ? false : $on; }, 20 );

add_filter( 'option_wcfmvm_registration_custom_fields', function ( $fields ) {
	if ( ! is_array( $fields ) || ! ss_apply_ctx() ) return $fields;
	$out = array(); $has_note = false;
	foreach ( $fields as $f ) {
		$l = strtolower( trim( isset( $f['label'] ) ? (string) $f['label'] : '' ) );
		if ( 'country' === $l || false !== strpos( $l, 'how many products' ) ) continue;
		if ( false !== strpos( $l, 'link to your work' ) || false !== strpos( $l, 'what do you make' ) ) unset( $f['required'] );
		if ( false !== strpos( $l, 'anything we should know' ) ) $has_note = true;
		$out[] = $f;
	}
	if ( ! $has_note ) {
		$note = array( 'enable' => 'yes', 'type' => 'textarea', 'label' => 'Anything we should know?', 'help_text' => '', 'options' => '' );
		$pos  = count( $out );
		foreach ( $out as $i => $f ) { if ( false !== strpos( isset( $f['label'] ) ? (string) $f['label'] : '', '5+' ) ) { $pos = $i; break; } }
		array_splice( $out, $pos, 0, array( $note ) );
	}
	return $out;
}, 20 );
