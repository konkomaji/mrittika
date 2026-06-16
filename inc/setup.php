<?php
/**
 * Theme setup: supports, menus, image sizes, editor styles.
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'mrittika_setup' ) ) {
	/**
	 * Register theme features.
	 */
	function mrittika_setup() {
		load_theme_textdomain( 'mrittika', MRITTIKA_DIR . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'editor-style', 'assets/css/editor.css' );

		add_theme_support(
			'html5',
			array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
		);

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 48,
				'width'       => 220,
				'flex-height' => true,
				'flex-width'  => true,
				'unlink-homepage-logo' => true,
			)
		);

		// Editorial image crops.
		set_post_thumbnail_size( 1200, 800, true );           // 3:2 hero.
		add_image_size( 'mrittika-card', 720, 480, true );    // 3:2 cards.
		add_image_size( 'mrittika-thumb', 160, 160, true );   // square list thumb.
		add_image_size( 'mrittika-wide', 1600, 1067, true );  // 3:2 full-bleed.

		register_nav_menus(
			array(
				'primary'   => __( 'Primary Menu', 'mrittika' ),
				'footer'    => __( 'Footer Menu', 'mrittika' ),
				'social'    => __( 'Social Links', 'mrittika' ),
				'topics'    => __( 'Topics / Categories Bar', 'mrittika' ),
			)
		);
	}
}
add_action( 'after_setup_theme', 'mrittika_setup' );

/**
 * Content width in pixels.
 */
function mrittika_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mrittika_content_width', 720 );
}
add_action( 'after_setup_theme', 'mrittika_content_width', 0 );

/**
 * Add the theme color palette (matches theme.json) to the classic editor too.
 */
function mrittika_editor_color_palette() {
	add_theme_support( 'editor-color-palette', array(
		array( 'name' => __( 'Ink', 'mrittika' ),   'slug' => 'ink',   'color' => '#0a0a0a' ),
		array( 'name' => __( 'Slate', 'mrittika' ), 'slug' => 'slate', 'color' => '#525252' ),
		array( 'name' => __( 'Silver', 'mrittika' ),'slug' => 'silver','color' => '#a3a3a3' ),
		array( 'name' => __( 'Paper', 'mrittika' ), 'slug' => 'paper', 'color' => '#f5f5f5' ),
		array( 'name' => __( 'White', 'mrittika' ), 'slug' => 'white', 'color' => '#ffffff' ),
	) );
}
add_action( 'after_setup_theme', 'mrittika_editor_color_palette' );
