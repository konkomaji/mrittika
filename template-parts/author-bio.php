<?php
/**
 * Author bio box.
 *
 * @package Mrittika
 */

?>
<aside class="author-bio" aria-label="<?php esc_attr_e( 'About the author', 'mrittika' ); ?>">
	<div class="author-avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 96 ); ?></div>
	<div class="author-detail">
		<p class="author-eyebrow"><?php esc_html_e( 'Written by', 'mrittika' ); ?></p>
		<h2 class="author-name">
			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a>
		</h2>
		<p class="author-description"><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p>
	</div>
</aside>
