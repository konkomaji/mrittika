<?php
/**
 * Template Name: Wide / Full-Width Page
 * Template Post Type: page
 *
 * No sidebar, wide content column — for landing pages and data dashboards.
 *
 * @package Mrittika
 */

get_header();
?>

<main id="primary" class="site-main is-wide">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry-page entry-wide' ); ?>>
			<header class="entry-header wrap">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header>
			<div class="entry-content">
				<?php
				the_content();
				wp_link_pages();
				?>
			</div>
		</article>
	<?php endwhile; ?>
</main>

<?php
get_footer();
