<?php
/**
 * Mrittika functions and definitions.
 *
 * @package Mrittika
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MRITTIKA_VERSION', '1.0.0' );
define( 'MRITTIKA_DIR', get_template_directory() );
define( 'MRITTIKA_URI', get_template_directory_uri() );

/**
 * Load theme modules.
 */
$mrittika_includes = array(
	'/inc/setup.php',          // Theme supports, menus, image sizes.
	'/inc/enqueue.php',        // Styles & scripts.
	'/inc/template-tags.php',  // Reusable display helpers.
	'/inc/template-functions.php', // Body classes, filters, tweaks.
	'/inc/breadcrumbs.php',    // Accessible breadcrumb trail + schema.
	'/inc/seo.php',            // SEO meta (auto-disables under Yoast/Rank Math).
	'/inc/schema.php',         // JSON-LD structured data.
	'/inc/customizer.php',     // Theme options.
	'/inc/widgets.php',        // Sidebar / footer widget areas.
);

foreach ( $mrittika_includes as $mrittika_file ) {
	require_once MRITTIKA_DIR . $mrittika_file;
}
