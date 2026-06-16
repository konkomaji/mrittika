<?php
/**
 * Search result row.
 *
 * @package Mrittika
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="result-thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'mrittika-thumb', array( 'loading' => 'lazy' ) ); ?>
		</a>
	<?php endif; ?>
	<div class="result-body">
		<?php mrittika_category_pills( 1 ); ?>
		<h2 class="result-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="result-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26 ) ); ?></p>
		<div class="result-meta"><?php mrittika_posted_on(); ?></div>
	</div>
</article>
