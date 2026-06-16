<?php
/**
 * Post card for listings. Variant via query var 'mrittika_card_variant'.
 * Variants: card (default), feature (large hero), secondary (compact hero).
 *
 * @package Mrittika
 */

$variant = get_query_var( 'mrittika_card_variant' );
$variant = $variant ? $variant : 'card';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card post-card--' . esc_attr( $variant ) ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="card-media">
			<?php
			$size = ( 'feature' === $variant ) ? 'mrittika-wide' : 'mrittika-card';
			mrittika_post_thumbnail( $size );
			?>
		</div>
	<?php endif; ?>

	<div class="card-body">
		<?php mrittika_category_pills(); ?>

		<h2 class="card-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h2>

		<?php if ( 'card' === $variant || 'feature' === $variant ) : ?>
			<p class="card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 'feature' === $variant ? 34 : 22 ) ); ?></p>
		<?php endif; ?>

		<div class="card-meta">
			<?php mrittika_posted_on(); ?>
			<span class="meta-sep" aria-hidden="true">·</span>
			<span class="reading-time"><?php echo esc_html( mrittika_reading_time() ); ?></span>
		</div>
	</div>
</article>
