<?php
/**
 * Security hardening.
 *
 * Defensive, theme-level measures. Every behavior is toggleable from
 * Mrittika → Security so it never conflicts with a security plugin.
 * None of this replaces a dedicated security plugin or server config —
 * it raises the floor for a default install.
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Send conservative security headers.
 * CSP is intentionally omitted by default (would break AdSense / embeds);
 * the safe, broadly-compatible headers are sent instead.
 */
function mrittika_security_headers( $headers ) {
	if ( ! mrittika_get_option( 'sec_headers', true ) ) {
		return $headers;
	}
	$headers['X-Content-Type-Options']  = 'nosniff';
	$headers['Referrer-Policy']         = 'strict-origin-when-cross-origin';
	$headers['X-Frame-Options']         = 'SAMEORIGIN';
	$headers['Cross-Origin-Opener-Policy'] = 'same-origin';
	$headers['Permissions-Policy']      = 'browsing-topics=(), interest-cohort=(), geolocation=(), microphone=(), camera=()';
	$headers['X-XSS-Protection']        = '0'; // Modern browsers: disable legacy auditor.
	return $headers;
}
add_filter( 'wp_headers', 'mrittika_security_headers' );

/**
 * Add HSTS only over HTTPS (header filter above runs for all requests).
 */
function mrittika_hsts_header() {
	if ( mrittika_get_option( 'sec_headers', true ) && is_ssl() ) {
		header( 'Strict-Transport-Security: max-age=15552000' );
	}
}
add_action( 'send_headers', 'mrittika_hsts_header' );

/**
 * Remove the WordPress version generator (reduces fingerprinting).
 */
function mrittika_remove_version() {
	return '';
}
if ( mrittika_get_option( 'sec_hide_version', true ) ) {
	add_filter( 'the_generator', 'mrittika_remove_version' );
	// Strip ?ver= from core asset URLs that leak the WP version.
	add_filter( 'style_loader_src', 'mrittika_strip_core_ver', 9999 );
	add_filter( 'script_loader_src', 'mrittika_strip_core_ver', 9999 );
}

/**
 * Strip the WP version query arg from core assets.
 *
 * @param string $src Asset URL.
 * @return string
 */
function mrittika_strip_core_ver( $src ) {
	if ( $src && strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) !== false ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}

/**
 * Disable XML-RPC (a common brute-force / pingback amplification vector).
 */
if ( mrittika_get_option( 'sec_disable_xmlrpc', true ) ) {
	add_filter( 'xmlrpc_enabled', '__return_false' );
	add_filter( 'xmlrpc_methods', '__return_empty_array' );
	// Remove RSD/pingback link hints.
	add_filter( 'wp_headers', function ( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}, 11 );
}

/**
 * Block author-enumeration via ?author=N redirect probing on the front end.
 */
function mrittika_block_user_enumeration() {
	if ( is_admin() || ! mrittika_get_option( 'sec_block_user_enum', true ) ) {
		return;
	}
	if ( isset( $_GET['author'] ) && ! is_user_logged_in() ) { // phpcs:ignore WordPress.Security.NonceVerification
		$raw = sanitize_text_field( wp_unslash( $_GET['author'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		if ( preg_match( '/^\d+$/', $raw ) ) {
			wp_safe_redirect( home_url( '/' ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'mrittika_block_user_enumeration' );

/**
 * Remove author info from the REST users endpoint for logged-out visitors
 * (another enumeration surface).
 */
function mrittika_restrict_rest_users( $endpoints ) {
	if ( ! mrittika_get_option( 'sec_block_user_enum', true ) || is_user_logged_in() ) {
		return $endpoints;
	}
	if ( isset( $endpoints['/wp/v2/users'] ) ) {
		unset( $endpoints['/wp/v2/users'] );
	}
	if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}
	return $endpoints;
}
add_filter( 'rest_endpoints', 'mrittika_restrict_rest_users' );

/**
 * Make login errors generic (do not reveal whether the username exists).
 */
function mrittika_generic_login_error() {
	return __( 'Invalid credentials. Please try again.', 'mrittika' );
}
add_filter( 'login_errors', 'mrittika_generic_login_error' );

/**
 * Disable the plugin/theme file editor unless already defined
 * (prevents code execution if an admin account is compromised).
 */
function mrittika_disable_file_edit() {
	if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
		define( 'DISALLOW_FILE_EDIT', true );
	}
}
add_action( 'init', 'mrittika_disable_file_edit' );

/**
 * Add rel="noopener noreferrer nofollow ugc" to comment author / content links.
 */
function mrittika_harden_comment_links( $text ) {
	if ( ! mrittika_get_option( 'sec_comment_links', true ) ) {
		return $text;
	}
	return wp_rel_callback( $text, 'nofollow ugc noopener noreferrer' );
}
add_filter( 'comment_text', 'mrittika_harden_comment_links', 20 );

/**
 * Strip executable/script content from comment data defensively.
 *
 * @param array $commentdata Comment fields.
 * @return array
 */
function mrittika_filter_comment( $commentdata ) {
	if ( isset( $commentdata['comment_content'] ) ) {
		// Core already sanitizes; this removes any stray <script>/<iframe> as belt-and-braces.
		$commentdata['comment_content'] = preg_replace( '#<(script|iframe|object|embed)[^>]*>.*?</\1>#is', '', $commentdata['comment_content'] );
	}
	return $commentdata;
}
add_filter( 'preprocess_comment', 'mrittika_filter_comment' );
