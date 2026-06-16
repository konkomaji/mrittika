<?php
/**
 * Blog home — magazine hero + Explore Topics + infinite-scroll stories + Archives.
 *
 * @package Mrittika
 */

get_header();

global $wp_query;
$max_pages = (int) $wp_query->max_num_pages;
$base_url  = esc_url( home_url( '/' ) );
?>

<main id="primary" class="site-main">

<?php if ( have_posts() ) : ?>

	<!-- ── 1. Magazine hero: first 3 posts ─────────────────── -->
	<section class="home-hero wrap" aria-label="<?php esc_attr_e( 'Latest stories', 'mrittika' ); ?>">
		<?php
		$mrittika_count = 0;
		while ( have_posts() && $mrittika_count < 3 ) :
			the_post();
			$variant = ( 0 === $mrittika_count ) ? 'feature' : 'secondary';
			set_query_var( 'mrittika_card_variant', $variant );
			if ( 1 === $mrittika_count ) {
				echo '<div class="hero-secondary-stack">';
			}
			get_template_part( 'template-parts/content', 'card' );
			$mrittika_count++;
		endwhile;
		if ( $mrittika_count > 1 ) {
			echo '</div>';
		}
		?>
	</section>

	<!-- ── 2. Explore our Topics ──────────────────────────── -->
	<?php
	$explore_cats = get_categories( array(
		'orderby'    => 'count',
		'order'      => 'DESC',
		'hide_empty' => true,
		'number'     => 24,
	) );

	$wb_palette = array(
		array( 'from' => '#C4622D', 'to' => '#8B4513' ),
		array( 'from' => '#2C3E6B', 'to' => '#1a2a4a' ),
		array( 'from' => '#C49A22', 'to' => '#8B6914' ),
		array( 'from' => '#7A9E7E', 'to' => '#4a6b4e' ),
		array( 'from' => '#8B5E3C', 'to' => '#5a3c24' ),
		array( 'from' => '#4A4A4A', 'to' => '#2a2a2a' ),
		array( 'from' => '#A0522D', 'to' => '#6B3A1E' ),
		array( 'from' => '#3D5A80', 'to' => '#2a3f5a' ),
	);

	if ( ! empty( $explore_cats ) ) :
	?>
	<section class="topics-explore" aria-labelledby="topics-explore-heading">
		<div class="topics-explore-inner wrap">

			<div class="topics-explore-header">
				<h2 class="topics-explore-title" id="topics-explore-heading">
					<?php esc_html_e( 'Explore our Topics', 'mrittika' ); ?>
				</h2>
				<p class="topics-explore-lead">
					<?php esc_html_e( 'Browse stories by subject', 'mrittika' ); ?>
				</p>
			</div>

			<div class="cat-cubes-grid">
				<?php foreach ( $explore_cats as $cat ) :
					$palette_item = $wb_palette[ $cat->term_id % count( $wb_palette ) ];
					$img_url      = mrittika_get_cat_image_url( $cat->term_id );
					$initial      = mb_strtoupper( mb_substr( $cat->name, 0, 1 ) );
					$count        = sprintf(
						_n( '%s story', '%s stories', $cat->count, 'mrittika' ),
						number_format_i18n( $cat->count )
					);
				?>
				<a
					class="cat-cube"
					href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
					aria-label="<?php echo esc_attr( $cat->name . ' — ' . $count ); ?>"
					data-cat-nav="1"
				>
					<?php if ( $img_url ) : ?>
					<img
						class="cat-cube-img"
						src="<?php echo esc_url( $img_url ); ?>"
						alt=""
						loading="lazy"
						decoding="async"
					>
					<?php else : ?>
					<div
						class="cat-cube-bg"
						style="background: linear-gradient(135deg, <?php echo esc_attr( $palette_item['from'] ); ?>, <?php echo esc_attr( $palette_item['to'] ); ?>);"
						aria-hidden="true"
					>
						<span class="cat-cube-initial"><?php echo esc_html( $initial ); ?></span>
					</div>
					<?php endif; ?>

					<div class="cat-cube-overlay">
						<p class="cat-cube-name"><?php echo esc_html( $cat->name ); ?></p>
						<p class="cat-cube-count"><?php echo esc_html( $count ); ?></p>
					</div>
				</a>
				<?php endforeach; ?>
			</div>

		</div>
	</section>
	<?php endif; ?>

	<!-- ── 3. Start to Read — infinite-scroll grid ─────────── -->
	<div class="wrap" id="start-to-read">
		<div class="content-area is-full">

			<header class="section-heading home-start-heading">
				<h2><?php esc_html_e( 'Start to Read', 'mrittika' ); ?></h2>
			</header>

			<div
				class="post-grid"
				id="infinite-post-grid"
				data-max-pages="<?php echo esc_attr( $max_pages ); ?>"
				data-current-page="1"
				data-base-url="<?php echo esc_url( $base_url ); ?>"
			>
				<?php
				while ( have_posts() ) :
					the_post();
					set_query_var( 'mrittika_card_variant', 'card' );
					get_template_part( 'template-parts/content', 'card' );
				endwhile;
				?>
			</div>

			<!-- Infinite scroll controls -->
			<div class="infinite-loader" id="infinite-loader" aria-hidden="true" aria-label="<?php esc_attr_e( 'Loading more stories', 'mrittika' ); ?>">
				<div class="infinite-spinner"></div>
			</div>
			<div class="infinite-sentinel" id="infinite-sentinel" aria-hidden="true"></div>
			<div class="infinite-end" id="infinite-end" aria-live="polite">
				<?php esc_html_e( "You've read everything", 'mrittika' ); ?>
				<span aria-hidden="true"> ✦</span>
				<a href="#primary"><?php esc_html_e( 'Back to top', 'mrittika' ); ?></a>
			</div>

		</div>
	</div>

<?php else : ?>
	<div class="wrap"><?php get_template_part( 'template-parts/content', 'none' ); ?></div>
<?php endif; ?>

</main>

<!-- ── Our Archives ──────────────────────────────────────── -->
<div class="home-archives-row">
	<div class="home-archives-inner wrap">
		<p class="home-archives-label"><?php esc_html_e( 'Looking for older stories?', 'mrittika' ); ?></p>
		<a class="archives-btn" href="<?php echo esc_url( get_year_link( gmdate( 'Y' ) ) ); ?>">
			<?php esc_html_e( 'Our Archives', 'mrittika' ); ?>
			<svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
				<path d="M4 10h12M11 5l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</a>
	</div>
</div>

<?php get_footer(); ?>
