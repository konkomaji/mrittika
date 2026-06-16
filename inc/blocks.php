<?php
/**
 * Block style variations & pattern category.
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Material Expressive block styles.
 */
function mrittika_register_block_styles() {

	register_block_style( 'core/group', array(
		'name'  => 'm3-card',
		'label' => __( 'M3 Card', 'mrittika' ),
	) );
	register_block_style( 'core/group', array(
		'name'  => 'm3-outline',
		'label' => __( 'M3 Outline', 'mrittika' ),
	) );

	register_block_style( 'core/image', array(
		'name'  => 'm3-rounded',
		'label' => __( 'Expressive Rounded', 'mrittika' ),
	) );

	register_block_style( 'core/button', array(
		'name'  => 'm3-tonal',
		'label' => __( 'Tonal', 'mrittika' ),
	) );
	register_block_style( 'core/button', array(
		'name'  => 'm3-text',
		'label' => __( 'Text', 'mrittika' ),
	) );

	register_block_style( 'core/quote', array(
		'name'  => 'm3-pull',
		'label' => __( 'Pull Quote', 'mrittika' ),
	) );

	register_block_style( 'core/separator', array(
		'name'  => 'm3-dotted',
		'label' => __( 'Dotted', 'mrittika' ),
	) );

	register_block_style( 'core/list', array(
		'name'  => 'm3-checklist',
		'label' => __( 'Checklist', 'mrittika' ),
	) );
}
add_action( 'init', 'mrittika_register_block_styles' );

/**
 * Register a pattern category for property-data layouts.
 */
function mrittika_register_pattern_category() {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'mrittika', array(
			'label' => __( 'Mrittika', 'mrittika' ),
		) );
	}
}
add_action( 'init', 'mrittika_register_pattern_category' );
