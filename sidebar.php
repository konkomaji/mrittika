<?php
/**
 * Main sidebar.
 *
 * @package Mrittika
 */

if ( ! is_active_sidebar( 'sidebar-main' ) ) {
	return;
}
?>
<aside id="secondary" class="widget-area sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'mrittika' ); ?>">
	<?php dynamic_sidebar( 'sidebar-main' ); ?>
</aside>
