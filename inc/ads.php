<?php
/**
 * Google AdSense integration.
 *
 * Policy-conscious: ad slots only in publisher-intended positions (after header,
 * in-content, after post, sidebar), never in navigation. Auto-ads optional.
 * All output is gated on a validated publisher ID.
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether AdSense is configured.
 *
 * @return bool
 */
function mrittika_ads_enabled() {
	$pub = mrittika_get_option( 'adsense_publisher_id', '' );
	return (bool) preg_match( '/^ca-pub-\d{10,20}$/', $pub );
}

/**
 * Output the AdSense loader + Auto Ads in <head>.
 */
function mrittika_adsense_head() {
	if ( ! mrittika_ads_enabled() ) {
		return;
	}
	$pub = mrittika_get_option( 'adsense_publisher_id' );
	printf(
		'<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=%s" crossorigin="anonymous"></script>' . "\n",
		esc_attr( $pub )
	);
	if ( mrittika_get_option( 'adsense_auto_ads', false ) ) {
		printf(
			'<script>(adsbygoogle = window.adsbygoogle || []).push({google_ad_client: "%s", enable_page_level_ads: true});</script>' . "\n",
			esc_js( $pub )
		);
	}
}
add_action( 'wp_head', 'mrittika_adsense_head', 5 );

/**
 * Search-engine / AdSense verification meta tags.
 */
function mrittika_verification_meta() {
	$g = mrittika_get_option( 'verify_google', '' );
	$b = mrittika_get_option( 'verify_bing', '' );
	if ( $g ) {
		printf( '<meta name="google-site-verification" content="%s">' . "\n", esc_attr( $g ) );
	}
	if ( $b ) {
		printf( '<meta name="msvalidate.01" content="%s">' . "\n", esc_attr( $b ) );
	}
}
add_action( 'wp_head', 'mrittika_verification_meta', 1 );

/**
 * Render a named ad slot. Wrapped in a labeled, non-intrusive container.
 *
 * @param string $slot_key Option key holding the ad markup.
 * @param string $position CSS modifier for styling.
 */
function mrittika_ad_slot( $slot_key, $position = '' ) {
	if ( ! mrittika_ads_enabled() ) {
		return;
	}
	$markup = mrittika_get_option( $slot_key, '' );
	if ( empty( trim( (string) $markup ) ) ) {
		return;
	}
	printf(
		'<aside class="mrittika-ad mrittika-ad--%1$s" aria-label="%2$s"><span class="ad-label">%3$s</span><div class="ad-inner">%4$s</div></aside>',
		esc_attr( $position ? $position : sanitize_html_class( $slot_key ) ),
		esc_attr__( 'Advertisement', 'mrittika' ),
		esc_html__( 'Advertisement', 'mrittika' ),
		$markup // Already sanitized on save (mrittika_sanitize_ad_html).
	);
}

/**
 * After-header leaderboard.
 */
function mrittika_ad_after_header() {
	mrittika_ad_slot( 'ad_after_header', 'leaderboard' );
}
add_action( 'mrittika_after_header', 'mrittika_ad_after_header' );

/**
 * After-post block (single posts only).
 */
function mrittika_ad_after_post( $content ) {
	if ( is_singular( 'post' ) && in_the_loop() && is_main_query() ) {
		$markup = mrittika_get_option( 'ad_after_post', '' );
		if ( mrittika_ads_enabled() && trim( (string) $markup ) !== '' ) {
			$content .= sprintf(
				'<aside class="mrittika-ad mrittika-ad--after-post" aria-label="%1$s"><span class="ad-label">%2$s</span><div class="ad-inner">%3$s</div></aside>',
				esc_attr__( 'Advertisement', 'mrittika' ),
				esc_html__( 'Advertisement', 'mrittika' ),
				$markup
			);
		}
	}
	return $content;
}
add_filter( 'the_content', 'mrittika_ad_after_post', 20 );

/**
 * In-content ad: inject after the Nth top-level paragraph of single posts.
 */
function mrittika_ad_in_content( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$markup = mrittika_get_option( 'ad_in_content', '' );
	if ( ! mrittika_ads_enabled() || trim( (string) $markup ) === '' ) {
		return $content;
	}

	$after  = (int) mrittika_get_option( 'ad_in_content_after', 3 );
	$paras  = explode( '</p>', $content );
	if ( count( $paras ) <= $after ) {
		return $content;
	}

	$ad = sprintf(
		'<aside class="mrittika-ad mrittika-ad--in-content" aria-label="%1$s"><span class="ad-label">%2$s</span><div class="ad-inner">%3$s</div></aside>',
		esc_attr__( 'Advertisement', 'mrittika' ),
		esc_html__( 'Advertisement', 'mrittika' ),
		$markup
	);

	$out = '';
	foreach ( $paras as $i => $chunk ) {
		$out .= $chunk;
		if ( '' !== trim( $chunk ) ) {
			$out .= '</p>';
		}
		if ( ( $i + 1 ) === $after ) {
			$out .= $ad;
		}
	}
	return $out;
}
add_filter( 'the_content', 'mrittika_ad_in_content', 15 );

/**
 * Serve a managed ads.txt from the theme settings if the site root lacks one.
 * (Real ads.txt at site root always wins; this is a convenience fallback.)
 */
function mrittika_virtual_ads_txt() {
	if ( ! mrittika_ads_enabled() ) {
		return;
	}
	$request = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
	if ( '/ads.txt' !== $request ) {
		return;
	}
	// Only serve if no physical ads.txt exists at root.
	if ( file_exists( ABSPATH . 'ads.txt' ) ) {
		return;
	}
	$pub = str_replace( 'ca-', '', mrittika_get_option( 'adsense_publisher_id' ) );
	header( 'Content-Type: text/plain; charset=utf-8' );
	echo 'google.com, ' . esc_html( $pub ) . ", DIRECT, f08c47fec0942fa0\n";
	exit;
}
add_action( 'init', 'mrittika_virtual_ads_txt' );

/**
 * Register an "Ad" sidebar slot helper used by sidebar.php / widgets.
 */
function mrittika_sidebar_ad() {
	mrittika_ad_slot( 'ad_sidebar', 'sidebar' );
}
add_action( 'mrittika_sidebar_top', 'mrittika_sidebar_ad' );
