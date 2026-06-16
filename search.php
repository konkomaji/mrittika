<?php
/**
 * Search results template.
 *
 * @package Mrittika
 */

get_header();
?>

<main id="primary" class="site-main wrap layout-with-sidebar">

	<div class="content-area">
		<header class="page-header search-header">
			<h1 class="page-title">
				<?php
				printf(
					/* translators: %s: search query */
					esc_html__( 'Results for: %s', 'mrittika' ),
					'<span class="search-term">' . esc_html( get_search_query() ) . '</span>'
				);
				?>
			</h1>
			<?php if ( have_posts() ) : ?>
				<p class="search-count">
					<?php
					global $wp_query;
					printf(
						/* translators: %d: result count */
						esc_html( _n( '%d result', '%d results', $wp_query->found_posts, 'mrittika' ) ),
						(int) $wp_query->found_posts
					);
					?>
				</p>
			<?php endif; ?>
			<div class="search-again"><?php get_search_form(); ?></div>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="search-results">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'search' );
				endwhile;
				?>
			</div>
			<?php mrittika_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>

	<?php get_sidebar(); ?>

</main>

<?php
get_footer();
