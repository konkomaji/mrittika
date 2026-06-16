<?php
/**
 * Custom search form.
 *
 * @package Mrittika
 */

$mrittika_sf_id = 'search-' . wp_unique_id();
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $mrittika_sf_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'mrittika' ); ?></label>
	<div class="search-field-wrap">
		<svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
		<input
			type="search"
			id="<?php echo esc_attr( $mrittika_sf_id ); ?>"
			class="search-field"
			placeholder="<?php esc_attr_e( 'Search prices, districts, guides…', 'mrittika' ); ?>"
			value="<?php echo get_search_query(); ?>"
			name="s"
			autocomplete="off"
		/>
		<button type="submit" class="search-submit"><?php esc_html_e( 'Search', 'mrittika' ); ?></button>
	</div>
</form>
