<?php
/**
 * Page template — M3 Expressive editorial design.
 *
 * @package Mrittika
 */

get_header();

while ( have_posts() ) :
	the_post();
	$has_thumb   = has_post_thumbnail();
	$has_excerpt = has_excerpt();
?>

<main id="primary" class="site-main">

	<?php if ( $has_thumb ) : ?>
	<!-- ── Hero image ───────────────────────────────────────── -->
	<div class="page-hero">
		<?php echo wp_get_attachment_image( get_post_thumbnail_id(), 'mrittika-wide', false, array(
			'class'   => 'page-hero-img',
			'loading' => 'eager',
			'decoding'=> 'async',
		) ); ?>
		<div class="page-hero-overlay">
			<h1 class="page-hero-title"><?php the_title(); ?></h1>
		</div>
	</div>
	<?php endif; ?>

	<!-- ── Page body ────────────────────────────────────────── -->
	<div class="wrap">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry-page' ); ?>>

			<?php if ( ! $has_thumb ) : ?>
			<header class="entry-page-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<?php if ( $has_excerpt ) : ?>
				<p class="entry-page-standfirst"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</header>
			<?php elseif ( $has_excerpt ) : ?>
			<div class="wrap">
				<p class="entry-page-standfirst" style="max-width:var(--content-width);margin-inline:auto;padding-top:var(--space-3);">
					<?php echo esc_html( get_the_excerpt() ); ?>
				</p>
			</div>
			<?php endif; ?>

			<div class="entry-content">
				<?php
				the_content();
				wp_link_pages( array(
					'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'mrittika' ) . '">',
					'after'  => '</nav>',
				) );
				?>
			</div>

		</article>
	</div>

	<?php if ( comments_open() || get_comments_number() ) : ?>
	<div class="wrap">
		<?php comments_template(); ?>
	</div>
	<?php endif; ?>

</main>

<?php
endwhile;
get_footer();
