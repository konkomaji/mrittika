<?php
/**
 * Related posts — same category, excluding current.
 *
 * @package Mrittika
 */

$cats = wp_get_post_categories( get_the_ID() );
if ( empty( $cats ) ) {
	return;
}

$related = new WP_Query( array(
	'category__in'        => $cats,
	'post__not_in'        => array( get_the_ID() ),
	'posts_per_page'      => 3,
	'ignore_sticky_posts' => true,
	'orderby'             => 'date',
	'no_found_rows'       => true,
) );

if ( ! $related->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section class="related-posts" aria-label="<?php esc_attr_e( 'Related stories', 'mrittika' ); ?>">
	<h2 class="related-heading"><?php esc_html_e( 'Related stories', 'mrittika' ); ?></h2>
	<div class="related-grid">
		<?php
		while ( $related->have_posts() ) :
			$related->the_post();
			?>
			<article class="related-card">
				<?php if ( has_post_thumbnail() ) : ?>
					<a class="related-thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
						<?php the_post_thumbnail( 'mrittika-card', array( 'loading' => 'lazy' ) ); ?>
					</a>
				<?php endif; ?>
				<h3 class="related-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<div class="related-meta"><?php echo esc_html( get_the_date() ); ?></div>
			</article>
		<?php endwhile; ?>
	</div>
</section>
<?php
wp_reset_postdata();
