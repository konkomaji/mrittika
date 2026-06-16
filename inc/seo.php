<?php
/**
 * Lightweight SEO meta output.
 *
 * Auto-disables when a dedicated SEO plugin (Yoast, Rank Math, SEOPress, AIOSEO)
 * is active, so there is never duplicate meta. Mrittika is fully Yoast-friendly:
 * it complements those plugins rather than fighting them.
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'mrittika_seo_plugin_active' ) ) {
	/**
	 * Detect a known SEO plugin.
	 *
	 * @return bool
	 */
	function mrittika_seo_plugin_active() {
		return (
			defined( 'WPSEO_VERSION' )          // Yoast SEO.
			|| defined( 'RANK_MATH_VERSION' )   // Rank Math.
			|| defined( 'SEOPRESS_VERSION' )    // SEOPress.
			|| defined( 'AIOSEO_VERSION' )      // All in One SEO.
			|| class_exists( 'WPSEO_Frontend' )
		);
	}
}

/**
 * Output meta description, canonical, and Open Graph / Twitter tags.
 */
function mrittika_seo_meta() {
	if ( mrittika_seo_plugin_active() ) {
		return; // SEO plugin handles everything.
	}

	$desc      = mrittika_meta_description();
	$canonical = mrittika_canonical_url();
	$title     = wp_get_document_title();
	$site      = get_bloginfo( 'name' );
	$og_type   = is_singular( 'post' ) ? 'article' : 'website';
	$image     = mrittika_share_image();

	echo "\n<!-- Mrittika SEO -->\n";

	if ( $desc ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	}
	if ( $canonical ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $canonical ) );
	}

	// Robots: keep search/paged archives lean.
	if ( is_search() || is_404() ) {
		echo '<meta name="robots" content="noindex,follow">' . "\n";
	}

	// Open Graph.
	printf( '<meta property="og:type" content="%s">' . "\n", esc_attr( $og_type ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( $site ) );
	if ( $canonical ) {
		printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $canonical ) );
	}
	if ( $desc ) {
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
	}
	if ( $image ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	}
	$locale = get_locale();
	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( $locale ) );

	if ( 'article' === $og_type ) {
		printf( '<meta property="article:published_time" content="%s">' . "\n", esc_attr( get_the_date( DATE_W3C ) ) );
		printf( '<meta property="article:modified_time" content="%s">' . "\n", esc_attr( get_the_modified_date( DATE_W3C ) ) );
		foreach ( get_the_category() as $cat ) {
			printf( '<meta property="article:section" content="%s">' . "\n", esc_attr( $cat->name ) );
		}
	}

	// Twitter / X.
	printf( '<meta name="twitter:card" content="%s">' . "\n", $image ? 'summary_large_image' : 'summary' );
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	if ( $desc ) {
		printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $desc ) );
	}
	if ( $image ) {
		printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) );
	}

	echo "<!-- /Mrittika SEO -->\n";
}
add_action( 'wp_head', 'mrittika_seo_meta', 2 );

if ( ! function_exists( 'mrittika_meta_description' ) ) {
	/**
	 * Resolve a meta description for the current view.
	 *
	 * @return string
	 */
	function mrittika_meta_description() {
		$desc = '';
		if ( is_singular() ) {
			$post = get_queried_object();
			if ( has_excerpt( $post ) ) {
				$desc = get_the_excerpt( $post );
			} else {
				$desc = wp_strip_all_tags( get_post_field( 'post_content', $post ) );
			}
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$desc = term_description();
		} elseif ( is_author() ) {
			$desc = get_the_author_meta( 'description' );
		} elseif ( is_home() || is_front_page() ) {
			$desc = get_bloginfo( 'description' );
		}
		$desc = wp_strip_all_tags( $desc );
		$desc = preg_replace( '/\s+/', ' ', $desc );
		return trim( mb_substr( $desc, 0, 158 ) );
	}
}

if ( ! function_exists( 'mrittika_canonical_url' ) ) {
	/**
	 * Best-effort canonical URL.
	 *
	 * @return string
	 */
	function mrittika_canonical_url() {
		if ( is_singular() ) {
			return get_permalink();
		}
		if ( is_category() || is_tag() || is_tax() ) {
			return get_term_link( get_queried_object() );
		}
		if ( is_author() ) {
			return get_author_posts_url( get_queried_object_id() );
		}
		if ( is_front_page() ) {
			return home_url( '/' );
		}
		if ( is_home() ) {
			return get_permalink( get_option( 'page_for_posts' ) );
		}
		return '';
	}
}

if ( ! function_exists( 'mrittika_share_image' ) ) {
	/**
	 * Social share image URL.
	 *
	 * @return string
	 */
	function mrittika_share_image() {
		if ( is_singular() && has_post_thumbnail() ) {
			$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'mrittika-wide' );
			if ( $img ) {
				return $img[0];
			}
		}
		$custom = get_theme_mod( 'mrittika_default_share_image' );
		return $custom ? $custom : '';
	}
}
