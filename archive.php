<?php
/**
 * Archive template — categories, tags, dates, authors.
 *
 * @package Mrittika
 */

get_header();
?>

<main id="primary" class="site-main wrap layout-with-sidebar">

	<div class="content-area">
		<?php if ( have_posts() ) : ?>

			<header class="page-header archive-header">
				<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
				<?php
				$desc = get_the_archive_description();
				if ( $desc ) :
					?>
					<div class="archive-description"><?php echo wp_kses_post( $desc ); ?></div>
				<?php endif; ?>
			</header>

			<div class="post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					set_query_var( 'mrittika_card_variant', 'card' );
					get_template_part( 'template-parts/content', 'card' );
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
