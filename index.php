<?php
/**
 * Main fallback template — blog / home listing.
 *
 * @package Mrittika
 */

get_header();
?>

<main id="primary" class="site-main wrap layout-with-sidebar">

	<div class="content-area">
		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header class="page-header">
					<h1 class="page-title"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<?php
			$mrittika_index = 0;
			while ( have_posts() ) :
				the_post();
				// First post on the blog home gets a featured treatment.
				$variant = ( 0 === $mrittika_index && ( is_home() || is_archive() ) ) ? 'feature' : 'card';
				set_query_var( 'mrittika_card_variant', $variant );
				get_template_part( 'template-parts/content', 'card' );
				$mrittika_index++;
			endwhile;
			?>

			<?php mrittika_pagination(); ?>

		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>

	<?php get_sidebar(); ?>

</main>

<?php
get_footer();
