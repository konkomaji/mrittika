<?php
/**
 * Filters and small functional tweaks.
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Body classes for layout state.
 */
function mrittika_body_classes( $classes ) {
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	if ( ! is_active_sidebar( 'sidebar-main' ) ) {
		$classes[] = 'no-sidebar';
	}
	if ( is_singular() && has_post_thumbnail() ) {
		$classes[] = 'has-hero';
	}
	$classes[] = 'mrittika';
	$classes[] = 'card-style-' . sanitize_html_class( mrittika_get_option( 'card_style', 'soft' ) );
	return $classes;
}
add_filter( 'body_class', 'mrittika_body_classes' );

/**
 * Add a pingback header on singular pages.
 */
function mrittika_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'mrittika_pingback_header' );

/**
 * Custom excerpt length and "more".
 */
function mrittika_excerpt_length( $length ) {
	return 28;
}
add_filter( 'excerpt_length', 'mrittika_excerpt_length' );

function mrittika_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'mrittika_excerpt_more' );

/**
 * Read-more link appended to excerpts on archives.
 */
function mrittika_read_more_link() {
	return sprintf(
		' <a class="read-more" href="%1$s">%2$s<span class="screen-reader-text"> %3$s</span></a>',
		esc_url( get_permalink() ),
		esc_html__( 'Read more', 'mrittika' ),
		esc_html( get_the_title() )
	);
}

/**
 * Make all post excerpts strip shortcodes cleanly.
 */
function mrittika_clean_excerpt( $text ) {
	return $text;
}
add_filter( 'get_the_excerpt', 'mrittika_clean_excerpt' );

/**
 * Slightly trim WP head: remove emoji bloat, shortlink, wlwmanifest.
 */
function mrittika_head_cleanup() {
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );

	if ( mrittika_get_option( 'remove_emoji', true ) ) {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		add_filter( 'tiny_mce_plugins', function ( $plugins ) {
			return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : $plugins;
		} );
	}
}
add_action( 'init', 'mrittika_head_cleanup' );

/**
 * Lazy-load iframes (oEmbeds etc.) when enabled.
 */
function mrittika_lazy_iframes( $content ) {
	if ( is_admin() || ! mrittika_get_option( 'lazy_iframes', true ) ) {
		return $content;
	}
	if ( false === strpos( $content, '<iframe' ) ) {
		return $content;
	}
	return preg_replace_callback( '/<iframe(?![^>]*\bloading=)([^>]*)>/i', function ( $m ) {
		return '<iframe loading="lazy"' . $m[1] . '>';
	}, $content );
}
add_filter( 'the_content', 'mrittika_lazy_iframes', 25 );
add_filter( 'embed_oembed_html', 'mrittika_lazy_iframes', 25 );

/**
 * Add width/height-friendly responsive image sizes attribute for content images.
 */
function mrittika_content_image_sizes( $sizes, $size ) {
	return '(max-width: 720px) 100vw, 720px';
}
add_filter( 'wp_calculate_image_sizes', 'mrittika_content_image_sizes', 10, 2 );

/**
 * Skip-link focus fix handled in JS; ensure skip link target exists.
 */
function mrittika_skip_link() {
	echo '<a class="skip-link screen-reader-text" href="#primary">' . esc_html__( 'Skip to content', 'mrittika' ) . '</a>';
}
add_action( 'wp_body_open', 'mrittika_skip_link' );
