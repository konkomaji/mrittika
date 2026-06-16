<?php
/**
 * Main sidebar.
 *
 * @package Mrittika
 */

$mrittika_has_ad = function_exists( 'mrittika_ads_enabled' ) && mrittika_ads_enabled() && trim( (string) mrittika_get_option( 'ad_sidebar', '' ) ) !== '';

if ( ! is_active_sidebar( 'sidebar-main' ) && ! $mrittika_has_ad ) {
	return;
}
?>
<aside id="secondary" class="widget-area sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'mrittika' ); ?>">
	<?php
	/** Fires at the top of the sidebar — sidebar ad slot. */
	do_action( 'mrittika_sidebar_top' );
	if ( is_active_sidebar( 'sidebar-main' ) ) {
		dynamic_sidebar( 'sidebar-main' );
	}
	?>
</aside>
