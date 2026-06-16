<?php
/**
 * Comments template.
 *
 * @package Mrittika
 */

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$count = get_comments_number();
			if ( '1' === $count ) {
				esc_html_e( 'One response', 'mrittika' );
			} else {
				printf(
					/* translators: %s: comment count */
					esc_html( _nx( '%s response', '%s responses', $count, 'comments title', 'mrittika' ) ),
					esc_html( number_format_i18n( $count ) )
				);
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation(
			array(
				'prev_text' => esc_html__( 'Older comments', 'mrittika' ),
				'next_text' => esc_html__( 'Newer comments', 'mrittika' ),
			)
		);

		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'mrittika' ); ?></p>
		<?php endif; ?>

	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_submit'       => 'comment-submit button',
			'title_reply'        => esc_html__( 'Leave a comment', 'mrittika' ),
			'title_reply_to'     => esc_html__( 'Reply to %s', 'mrittika' ),
			'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'Your email address will not be published.', 'mrittika' ) . '</p>',
		)
	);
	?>
</section>
