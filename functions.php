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

define( 'MRITTIKA_VERSION', '1.5.0' );
define( 'MRITTIKA_DIR', get_template_directory() );
define( 'MRITTIKA_URI', get_template_directory_uri() );

/**
 * Load theme modules.
 */
$mrittika_includes = array(
	'/inc/options.php',        // Central options store + get/sanitize helpers.
	'/inc/setup.php',          // Theme supports, menus, image sizes.
	'/inc/enqueue.php',        // Styles & scripts.
	'/inc/template-tags.php',  // Reusable display helpers.
	'/inc/template-functions.php', // Body classes, filters, tweaks.
	'/inc/breadcrumbs.php',    // Accessible breadcrumb trail + schema.
	'/inc/seo.php',            // SEO meta (auto-disables under Yoast/Rank Math).
	'/inc/schema.php',         // JSON-LD structured data.
	'/inc/blocks.php',         // Block style variations + patterns.
	'/inc/security.php',       // Security headers & hardening.
	'/inc/ads.php',            // Google AdSense integration.
	'/inc/customizer.php',     // Customizer live-preview options.
	'/inc/widgets.php',        // Sidebar / footer / ad widget areas.
	'/inc/admin/settings.php', // Dedicated theme settings dashboard.
	'/inc/admin/thumbnails.php', // One-click thumbnail regeneration (AJAX).
	'/inc/faq.php',            // FAQ builder metabox + accordion + FAQPage schema.
);

foreach ( $mrittika_includes as $mrittika_file ) {
	require_once MRITTIKA_DIR . $mrittika_file;
}
