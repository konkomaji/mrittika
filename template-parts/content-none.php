<?php
/**
 * "No content" fallback.
 *
 * @package Mrittika
 */

?>
<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing here yet', 'mrittika' ); ?></h1>
	</header>
	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p>
				<?php
				printf(
					wp_kses(
						/* translators: %s: new post URL */
						__( 'Ready to publish your first story? <a href="%s">Create a post</a>.', 'mrittika' ),
						array( 'a' => array( 'href' => array() ) )
					),
					esc_url( admin_url( 'post-new.php' ) )
				);
				?>
			</p>
		<?php elseif ( is_search() ) : ?>
			<p><?php esc_html_e( 'No results matched your search. Try different keywords.', 'mrittika' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'It seems we can not find what you are looking for. Try a search.', 'mrittika' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
