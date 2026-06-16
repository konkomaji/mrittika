<?php
/**
 * Single post template.
 *
 * @package Mrittika
 */

get_header();
?>

<main id="primary" class="site-main wrap">

	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/content', 'single' );

		// Author bio.
		if ( get_the_author_meta( 'description' ) ) {
			get_template_part( 'template-parts/author-bio' );
		}

		// Related posts.
		get_template_part( 'template-parts/related-posts' );

		// Post navigation.
		the_post_navigation(
			array(
				'prev_text' => '<span class="nav-label">' . esc_html__( 'Previous', 'mrittika' ) . '</span> <span class="nav-title">%title</span>',
				'next_text' => '<span class="nav-label">' . esc_html__( 'Next', 'mrittika' ) . '</span> <span class="nav-title">%title</span>',
				'class'     => 'post-nav',
			)
		);

		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	endwhile;
	?>

</main>

<?php
get_footer();
