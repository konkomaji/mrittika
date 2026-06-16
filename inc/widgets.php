<?php
/**
 * Widget areas.
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register sidebars.
 */
function mrittika_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'mrittika' ),
		'id'            => 'sidebar-main',
		'description'   => __( 'Shown beside posts and archives.', 'mrittika' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar( array(
			/* translators: %d: footer column number */
			'name'          => sprintf( __( 'Footer %d', 'mrittika' ), $i ),
			'id'            => 'footer-' . $i,
			'description'   => __( 'Footer widget column.', 'mrittika' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}
}
add_action( 'widgets_init', 'mrittika_widgets_init' );
