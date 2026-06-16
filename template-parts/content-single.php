<?php
/**
 * Single post content.
 *
 * @package Mrittika
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry-single' ); ?>>

	<header class="entry-header single-header">
		<?php mrittika_category_pills( 3 ); ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<?php if ( has_excerpt() ) : ?>
			<p class="entry-standfirst"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>

		<?php mrittika_entry_meta(); ?>
	</header>

	<?php mrittika_post_thumbnail( 'post-thumbnail' ); ?>

	<?php
	if ( mrittika_get_option( 'show_toc', true ) ) {
		mrittika_table_of_contents();
	}
	?>

	<div class="entry-content">
		<?php
		the_content();
		wp_link_pages(
			array(
				'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'mrittika' ) . '">',
				'after'  => '</nav>',
			)
		);
		?>
	</div>

	<?php mrittika_faq_display(); ?>

	<footer class="entry-footer">
		<?php
		$tags = get_the_tag_list( '', '', '' );
		if ( $tags ) :
			?>
			<div class="entry-tags">
				<span class="tags-label"><?php esc_html_e( 'Tagged', 'mrittika' ); ?></span>
				<?php echo wp_kses_post( $tags ); ?>
			</div>
		<?php endif; ?>

		<?php if ( mrittika_get_option( 'show_share', true ) ) : ?>
		<div class="entry-share">
			<span class="share-label"><?php esc_html_e( 'Share', 'mrittika' ); ?></span>
			<?php
			$url   = rawurlencode( get_permalink() );
			$title = rawurlencode( get_the_title() );
			?>
			<a class="share-link" href="https://twitter.com/intent/tweet?url=<?php echo $url; // phpcs:ignore ?>&text=<?php echo $title; // phpcs:ignore ?>" target="_blank" rel="noopener nofollow">X</a>
			<a class="share-link" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; // phpcs:ignore ?>" target="_blank" rel="noopener nofollow">Facebook</a>
			<a class="share-link" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $url; // phpcs:ignore ?>" target="_blank" rel="noopener nofollow">LinkedIn</a>
			<a class="share-link" href="https://api.whatsapp.com/send?text=<?php echo $title; // phpcs:ignore ?>%20<?php echo $url; // phpcs:ignore ?>" target="_blank" rel="noopener nofollow">WhatsApp</a>
			<button class="share-link copy-link" type="button" data-copy-url="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Copy link', 'mrittika' ); ?></button>
		</div>
		<?php endif; ?>
	</footer>
</article>
