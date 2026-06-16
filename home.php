<?php
/**
 * Blog home — posts index with a magazine-style hero row.
 *
 * @package Mrittika
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php if ( have_posts() ) : ?>

		<?php
		// Magazine hero: first post large, next two stacked beside it.
		$mrittika_count = 0;
		?>
		<section class="home-hero wrap" aria-label="<?php esc_attr_e( 'Latest stories', 'mrittika' ); ?>">
			<?php
			while ( have_posts() && $mrittika_count < 3 ) :
				the_post();
				$variant = ( 0 === $mrittika_count ) ? 'feature' : 'secondary';
				set_query_var( 'mrittika_card_variant', $variant );
				if ( 1 === $mrittika_count ) {
					echo '<div class="hero-secondary-stack">';
				}
				get_template_part( 'template-parts/content', 'card' );
				$mrittika_count++;
			endwhile;
			if ( $mrittika_count > 1 ) {
				echo '</div>';
			}
			?>
		</section>

		<div class="wrap layout-with-sidebar">
			<div class="content-area">
				<header class="section-heading">
					<h2><?php esc_html_e( 'More stories', 'mrittika' ); ?></h2>
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
			</div>

			<?php get_sidebar(); ?>
		</div>

	<?php else : ?>
		<div class="wrap"><?php get_template_part( 'template-parts/content', 'none' ); ?></div>
	<?php endif; ?>

</main>

<?php
get_footer();
