<?php
/**
 * Central options store.
 *
 * All Mrittika settings live under a single option array (`mrittika_settings`)
 * with strict defaults and per-field sanitization. Read with mrittika_get_option().
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MRITTIKA_OPTION_KEY', 'mrittika_settings' );

/**
 * Default values for every setting.
 *
 * @return array
 */
function mrittika_default_options() {
	return array(
		// General.
		'enable_webfonts'      => true,
		'show_reading_time'    => true,
		'show_breadcrumbs'     => true,
		'show_related'         => true,
		'show_share'           => true,
		'show_toc'             => true,
		'footer_text'          => '',
		// Homepage.
		'home_show_hero'       => true,
		'home_hero_count'      => 3,        // featured posts in the hero (1–5).
		'home_show_topics'     => true,
		'home_topics_count'    => 24,       // categories shown in the slider (4–40).
		'home_topics_title'    => '',       // blank → "Explore our Topics".
		'home_start_title'     => '',       // blank → "Start to Read".
		'home_show_archives'   => true,
		// Design.
		'default_scheme'       => 'auto',   // auto|light|dark.
		'accent_mode'          => 'mono',   // mono only (brand constraint), reserved.
		'card_style'           => 'soft',   // soft|outline|flat.
		// Ads (Google AdSense).
		'adsense_publisher_id' => '',        // ca-pub-XXXXXXXXXXXXXXXX.
		'adsense_auto_ads'     => false,
		'ad_after_header'      => '',        // raw ad unit HTML.
		'ad_in_content'        => '',
		'ad_in_content_after'  => 3,         // inject after Nth paragraph.
		'ad_after_post'        => '',
		'ad_sidebar'           => '',
		// SEO.
		'seo_enable'           => true,      // auto-off under SEO plugins regardless.
		'default_share_image'  => '',
		'twitter_site'         => '',        // @handle.
		'verify_google'        => '',        // google-site-verification token.
		'verify_bing'          => '',
		// Performance.
		'defer_scripts'        => true,
		'remove_emoji'         => true,
		'lazy_iframes'         => true,
		// Security.
		'sec_headers'          => true,
		'sec_disable_xmlrpc'   => true,
		'sec_block_user_enum'  => true,
		'sec_hide_version'     => true,
		'sec_comment_links'    => true,     // strip URLs from comment author display.
	);
}

/**
 * Get a single option with default fallback.
 *
 * @param string $key     Option key.
 * @param mixed  $default Optional override default.
 * @return mixed
 */
function mrittika_get_option( $key, $default = null ) {
	static $cache = null;
	if ( null === $cache ) {
		$defaults = mrittika_default_options();
		$stored   = get_option( MRITTIKA_OPTION_KEY, array() );
		$cache    = wp_parse_args( is_array( $stored ) ? $stored : array(), $defaults );
	}
	if ( isset( $cache[ $key ] ) ) {
		return $cache[ $key ];
	}
	if ( null !== $default ) {
		return $default;
	}
	$defaults = mrittika_default_options();
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : null;
}

/**
 * Sanitize the full settings array on save.
 *
 * @param array $input Raw input.
 * @return array
 */
function mrittika_sanitize_options( $input ) {
	$defaults = mrittika_default_options();
	$out      = array();
	$input    = is_array( $input ) ? $input : array();

	// Booleans.
	$bools = array(
		'enable_webfonts', 'show_reading_time', 'show_breadcrumbs', 'show_related',
		'show_share', 'show_toc', 'adsense_auto_ads', 'seo_enable', 'defer_scripts',
		'remove_emoji', 'lazy_iframes', 'sec_headers', 'sec_disable_xmlrpc',
		'sec_block_user_enum', 'sec_hide_version', 'sec_comment_links',
		'home_show_hero', 'home_show_topics', 'home_show_archives',
	);
	foreach ( $bools as $b ) {
		$out[ $b ] = ! empty( $input[ $b ] );
	}

	// Homepage numeric + text.
	$out['home_topics_count'] = max( 4, min( 40, absint( $input['home_topics_count'] ?? 24 ) ) );
	$out['home_hero_count']   = max( 1, min( 5, absint( $input['home_hero_count'] ?? 3 ) ) );
	$out['home_topics_title'] = sanitize_text_field( $input['home_topics_title'] ?? '' );
	$out['home_start_title']  = sanitize_text_field( $input['home_start_title'] ?? '' );

	// Enums.
	$out['default_scheme'] = in_array( ( $input['default_scheme'] ?? '' ), array( 'auto', 'light', 'dark' ), true ) ? $input['default_scheme'] : 'auto';
	$out['card_style']     = in_array( ( $input['card_style'] ?? '' ), array( 'soft', 'outline', 'flat' ), true ) ? $input['card_style'] : 'soft';
	$out['accent_mode']    = 'mono';

	// Text / numeric.
	$out['footer_text']         = wp_kses_post( $input['footer_text'] ?? '' );
	$out['ad_in_content_after'] = max( 1, min( 20, absint( $input['ad_in_content_after'] ?? 3 ) ) );

	// AdSense publisher ID — strict format ca-pub-#########.
	$pub = trim( $input['adsense_publisher_id'] ?? '' );
	$out['adsense_publisher_id'] = preg_match( '/^ca-pub-\d{10,20}$/', $pub ) ? $pub : '';

	// Ad unit HTML — allow script/ins (AdSense markup) but sanitize attributes.
	$ad_fields = array( 'ad_after_header', 'ad_in_content', 'ad_after_post', 'ad_sidebar' );
	foreach ( $ad_fields as $f ) {
		$out[ $f ] = mrittika_sanitize_ad_html( $input[ $f ] ?? '' );
	}

	// URLs / tokens.
	$out['default_share_image'] = esc_url_raw( $input['default_share_image'] ?? '' );
	$out['twitter_site']        = sanitize_text_field( $input['twitter_site'] ?? '' );
	$out['verify_google']       = sanitize_text_field( $input['verify_google'] ?? '' );
	$out['verify_bing']         = sanitize_text_field( $input['verify_bing'] ?? '' );

	return wp_parse_args( $out, $defaults );
}

/**
 * Sanitize ad-unit HTML. Permits the AdSense <ins>/<script> shape only,
 * with a constrained attribute set. Only users with `unfiltered_html` may
 * save raw script; otherwise scripts are stripped.
 *
 * @param string $html Raw HTML.
 * @return string
 */
function mrittika_sanitize_ad_html( $html ) {
	$html = (string) $html;
	if ( '' === trim( $html ) ) {
		return '';
	}
	if ( ! current_user_can( 'unfiltered_html' ) ) {
		// Strip scripts for low-privilege users; keep <ins> markup.
		$allowed = array(
			'ins' => array( 'class' => array(), 'style' => array(), 'data-ad-client' => array(), 'data-ad-slot' => array(), 'data-ad-format' => array(), 'data-full-width-responsive' => array(), 'data-ad-layout' => array(), 'data-ad-layout-key' => array() ),
			'div' => array( 'class' => array(), 'style' => array() ),
		);
		return wp_kses( $html, $allowed );
	}
	return $html; // Trusted admin: store verbatim.
}
