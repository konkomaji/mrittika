<?php
/**
 * Theme Customizer options.
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register settings.
 */
function mrittika_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	// ---- Mrittika options panel ----
	$wp_customize->add_section( 'mrittika_options', array(
		'title'    => __( 'Mrittika Options', 'mrittika' ),
		'priority' => 30,
	) );

	// Default color scheme.
	$wp_customize->add_setting( 'mrittika_default_scheme', array(
		'default'           => 'auto',
		'sanitize_callback' => 'mrittika_sanitize_scheme',
	) );
	$wp_customize->add_control( 'mrittika_default_scheme', array(
		'label'   => __( 'Default color scheme', 'mrittika' ),
		'section' => 'mrittika_options',
		'type'    => 'select',
		'choices' => array(
			'auto'  => __( 'Auto (follow device)', 'mrittika' ),
			'light' => __( 'Light', 'mrittika' ),
			'dark'  => __( 'Dark', 'mrittika' ),
		),
	) );

	// Show reading time.
	$wp_customize->add_setting( 'mrittika_show_reading_time', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
	) );
	$wp_customize->add_control( 'mrittika_show_reading_time', array(
		'label'   => __( 'Show reading time', 'mrittika' ),
		'section' => 'mrittika_options',
		'type'    => 'checkbox',
	) );

	// Footer copyright text.
	$wp_customize->add_setting( 'mrittika_footer_text', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'mrittika_footer_text', array(
		'label'   => __( 'Footer text', 'mrittika' ),
		'section' => 'mrittika_options',
		'type'    => 'textarea',
	) );

	// Default share image.
	$wp_customize->add_setting( 'mrittika_default_share_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'mrittika_default_share_image', array(
		'label'       => __( 'Default social share image', 'mrittika' ),
		'description' => __( 'Used for Open Graph when a post has no featured image.', 'mrittika' ),
		'section'     => 'mrittika_options',
	) ) );
}
add_action( 'customize_register', 'mrittika_customize_register' );

/**
 * Sanitize color scheme choice.
 */
function mrittika_sanitize_scheme( $value ) {
	return in_array( $value, array( 'auto', 'light', 'dark' ), true ) ? $value : 'auto';
}

/**
 * Live-preview JS.
 */
function mrittika_customize_preview_js() {
	wp_enqueue_script(
		'mrittika-customizer',
		MRITTIKA_URI . '/assets/js/customizer.js',
		array( 'customize-preview' ),
		MRITTIKA_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'mrittika_customize_preview_js' );
