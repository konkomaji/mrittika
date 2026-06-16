<?php
/**
 * 404 template.
 *
 * @package Mrittika
 */

get_header();
?>

<main id="primary" class="site-main wrap">
	<section class="error-404 not-found">
		<header class="page-header">
			<p class="error-code">404</p>
			<h1 class="page-title"><?php esc_html_e( 'Page not found', 'mrittika' ); ?></h1>
			<p class="error-lead"><?php esc_html_e( 'The page you are looking for has moved or never existed. Try a search, or browse the latest stories.', 'mrittika' ); ?></p>
		</header>

		<div class="error-search"><?php get_search_form(); ?></div>

		<div class="error-suggestions">
			<h2><?php esc_html_e( 'Recent posts', 'mrittika' ); ?></h2>
			<ul class="recent-list">
				<?php
				$recent = new WP_Query( array( 'posts_per_page' => 6, 'ignore_sticky_posts' => true ) );
				while ( $recent->have_posts() ) :
					$recent->the_post();
					printf(
						'<li><a href="%1$s">%2$s</a></li>',
						esc_url( get_permalink() ),
						esc_html( get_the_title() )
					);
				endwhile;
				wp_reset_postdata();
				?>
			</ul>

			<h2><?php esc_html_e( 'Browse topics', 'mrittika' ); ?></h2>
			<div class="category-cloud">
				<?php
				wp_list_categories(
					array(
						'title_li'   => '',
						'orderby'    => 'count',
						'order'      => 'DESC',
						'number'     => 16,
						'show_count' => false,
					)
				);
				?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
