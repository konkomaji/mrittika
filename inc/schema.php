<?php
/**
 * JSON-LD structured data: WebSite, Organization, Article.
 * Skips Article/WebSite when an SEO plugin already emits schema.
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Site-wide WebSite + Organization graph (head).
 */
function mrittika_schema_site() {
	if ( mrittika_seo_plugin_active() ) {
		return;
	}

	$site_url = home_url( '/' );
	$name     = get_bloginfo( 'name' );
	$logo     = '';
	$logo_id  = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$src = wp_get_attachment_image_src( $logo_id, 'full' );
		if ( $src ) {
			$logo = $src[0];
		}
	}

	$graph = array(
		array(
			'@type'           => 'WebSite',
			'@id'             => $site_url . '#website',
			'url'             => $site_url,
			'name'            => $name,
			'description'     => get_bloginfo( 'description' ),
			'inLanguage'      => get_bloginfo( 'language' ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => array(
					'@type'       => 'EntryPoint',
					'urlTemplate' => $site_url . '?s={search_term_string}',
				),
				'query-input' => 'required name=search_term_string',
			),
		),
		array_filter( array(
			'@type' => 'Organization',
			'@id'   => $site_url . '#organization',
			'name'  => $name,
			'url'   => $site_url,
			'logo'  => $logo ? array( '@type' => 'ImageObject', 'url' => $logo ) : null,
		) ),
	);

	$data = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);

	echo "\n" . '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'mrittika_schema_site', 3 );

/**
 * Article schema on single posts.
 */
function mrittika_schema_article() {
	if ( ! is_singular( 'post' ) || mrittika_seo_plugin_active() ) {
		return;
	}

	$post_id   = get_the_ID();
	$author_id = (int) get_post_field( 'post_author', $post_id );
	$image     = '';
	if ( has_post_thumbnail() ) {
		$src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'mrittika-wide' );
		if ( $src ) {
			$image = $src[0];
		}
	}

	$data = array_filter( array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'mainEntityOfPage' => array( '@type' => 'WebPage', '@id' => get_permalink() ),
		'headline'         => wp_strip_all_tags( get_the_title() ),
		'description'      => mrittika_meta_description(),
		'image'            => $image ? array( $image ) : null,
		'datePublished'    => get_the_date( DATE_W3C ),
		'dateModified'     => get_the_modified_date( DATE_W3C ),
		'author'           => array(
			'@type' => 'Person',
			'name'  => get_the_author_meta( 'display_name', $author_id ),
			'url'   => get_author_posts_url( $author_id ),
		),
		'publisher'        => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'@id'   => home_url( '/' ) . '#organization',
		),
		'articleSection'   => wp_list_pluck( get_the_category(), 'name' ),
		'wordCount'        => str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) ),
		'inLanguage'       => get_bloginfo( 'language' ),
	) );

	echo "\n" . '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'mrittika_schema_article', 4 );
