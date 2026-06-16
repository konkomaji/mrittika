<?php
/**
 * Customizer — kept minimal. Full theme options live in the dedicated
 * Mrittika settings dashboard (Appearance is for live-preview niceties only).
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enable postMessage live preview for the site title/description and add a
 * pointer to the dedicated settings page.
 */
function mrittika_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->add_section( 'mrittika_pointer', array(
		'title'       => __( 'Mrittika Options', 'mrittika' ),
		'priority'    => 30,
		'description' => sprintf(
			/* translators: %s: settings page URL */
			__( 'Theme options (design, ads, SEO, performance, security) live on the dedicated <a href="%s">Mrittika settings page</a>.', 'mrittika' ),
			esc_url( admin_url( 'admin.php?page=mrittika-settings' ) )
		),
	) );
}
add_action( 'customize_register', 'mrittika_customize_register' );

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
