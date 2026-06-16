<?php
/**
 * 404 template — Bengal abstract art edition.
 *
 * @package Mrittika
 */

get_header();
?>

<main id="primary" class="site-main error-404-page">

	<!-- ── Abstract West Bengal art hero ───────────────────── -->
	<div class="e404-hero" aria-hidden="true">
		<div class="e404-canvas">
			<svg class="e404-art" viewBox="0 0 900 340" fill="none" xmlns="http://www.w3.org/2000/svg" role="presentation" focusable="false">

				<!-- Mustard-field wave layers -->
				<path class="wb-wave wb-wave--3" d="M0 280 C220 250,380 310,540 280 S780 250,900 280 L900 340 L0 340 Z"/>
				<path class="wb-wave wb-wave--2" d="M0 300 C160 268,320 328,480 300 S740 268,900 300 L900 340 L0 340 Z"/>
				<path class="wb-wave wb-wave--1" d="M0 320 C200 290,360 348,520 320 S760 290,900 320 L900 340 L0 340 Z"/>

				<!-- Kantha dots (scattered, background texture) -->
				<g class="wb-dots" fill="var(--wb-terracotta,#C4622D)" opacity="0.22">
					<circle cx="75"  cy="55"  r="3.5"/>
					<circle cx="115" cy="95"  r="2"/>
					<circle cx="155" cy="38"  r="3"/>
					<circle cx="38"  cy="140" r="2.5"/>
					<circle cx="195" cy="155" r="2"/>
					<circle cx="65"  cy="195" r="3"/>
					<circle cx="830" cy="70"  r="4"/>
					<circle cx="775" cy="115" r="2.5"/>
					<circle cx="845" cy="150" r="2"/>
					<circle cx="795" cy="195" r="3.5"/>
					<circle cx="855" cy="38"  r="2"/>
					<circle cx="745" cy="55"  r="3"/>
					<circle cx="455" cy="28"  r="2.5"/>
					<circle cx="510" cy="52"  r="2"/>
					<circle cx="395" cy="48"  r="3"/>
					<circle cx="340" cy="175" r="2"/>
					<circle cx="580" cy="165" r="2.5"/>
				</g>

				<!-- Left Bishnupur terracotta diamonds -->
				<g class="wb-geo" fill="none" stroke="var(--wb-terracotta,#C4622D)">
					<polygon points="30,100 52,78 74,100 52,122" stroke-width="1.5" opacity="0.55"/>
					<polygon points="41,100 52,89 63,100 52,111" fill="var(--wb-terracotta,#C4622D)" opacity="0.12" stroke="none"/>
					<polygon points="30,142 52,120 74,142 52,164" stroke-width="1.2" opacity="0.35"/>
					<polygon points="30,184 52,162 74,184 52,206" stroke-width="1" opacity="0.20"/>
					<circle cx="52" cy="100" r="2.5" fill="var(--wb-mustard,#C49A22)" opacity="0.6"/>
				</g>

				<!-- Right Bishnupur terracotta diamonds -->
				<g class="wb-geo" fill="none" stroke="var(--wb-terracotta,#C4622D)" transform="translate(822,0)">
					<polygon points="30,100 52,78 74,100 52,122" stroke-width="1.5" opacity="0.55"/>
					<polygon points="41,100 52,89 63,100 52,111" fill="var(--wb-terracotta,#C4622D)" opacity="0.12" stroke="none"/>
					<polygon points="30,142 52,120 74,142 52,164" stroke-width="1.2" opacity="0.35"/>
					<circle cx="52" cy="100" r="2.5" fill="var(--wb-mustard,#C49A22)" opacity="0.6"/>
				</g>

				<!-- Central alpona mandala -->
				<g transform="translate(450,148)">
					<!-- Outer petal ring (8 petals) -->
					<g opacity="0.65">
						<ellipse cx="0" cy="-58" rx="9" ry="21" fill="var(--wb-terracotta,#C4622D)" transform="rotate(0)"/>
						<ellipse cx="0" cy="-58" rx="9" ry="21" fill="var(--wb-terracotta,#C4622D)" transform="rotate(45)"/>
						<ellipse cx="0" cy="-58" rx="9" ry="21" fill="var(--wb-terracotta,#C4622D)" transform="rotate(90)"/>
						<ellipse cx="0" cy="-58" rx="9" ry="21" fill="var(--wb-terracotta,#C4622D)" transform="rotate(135)"/>
						<ellipse cx="0" cy="-58" rx="9" ry="21" fill="var(--wb-terracotta,#C4622D)" transform="rotate(180)"/>
						<ellipse cx="0" cy="-58" rx="9" ry="21" fill="var(--wb-terracotta,#C4622D)" transform="rotate(225)"/>
						<ellipse cx="0" cy="-58" rx="9" ry="21" fill="var(--wb-terracotta,#C4622D)" transform="rotate(270)"/>
						<ellipse cx="0" cy="-58" rx="9" ry="21" fill="var(--wb-terracotta,#C4622D)" transform="rotate(315)"/>
					</g>
					<!-- Mustard dot ring -->
					<g opacity="0.55">
						<circle cx="0" cy="-40" r="3.5" fill="var(--wb-mustard,#C49A22)" transform="rotate(0)"/>
						<circle cx="0" cy="-40" r="3.5" fill="var(--wb-mustard,#C49A22)" transform="rotate(45)"/>
						<circle cx="0" cy="-40" r="3.5" fill="var(--wb-mustard,#C49A22)" transform="rotate(90)"/>
						<circle cx="0" cy="-40" r="3.5" fill="var(--wb-mustard,#C49A22)" transform="rotate(135)"/>
						<circle cx="0" cy="-40" r="3.5" fill="var(--wb-mustard,#C49A22)" transform="rotate(180)"/>
						<circle cx="0" cy="-40" r="3.5" fill="var(--wb-mustard,#C49A22)" transform="rotate(225)"/>
						<circle cx="0" cy="-40" r="3.5" fill="var(--wb-mustard,#C49A22)" transform="rotate(270)"/>
						<circle cx="0" cy="-40" r="3.5" fill="var(--wb-mustard,#C49A22)" transform="rotate(315)"/>
					</g>
					<!-- Inner rings -->
					<circle cx="0" cy="0" r="30" fill="none" stroke="var(--wb-terracotta,#C4622D)" stroke-width="1.5" opacity="0.55"/>
					<circle cx="0" cy="0" r="21" fill="var(--wb-mustard,#C49A22)" opacity="0.14"/>
					<circle cx="0" cy="0" r="13" fill="var(--wb-terracotta,#C4622D)" opacity="0.30"/>
					<circle cx="0" cy="0" r="5"  fill="var(--wb-mustard,#C49A22)" opacity="0.85"/>
					<!-- Inner small petal ring -->
					<g opacity="0.35">
						<ellipse cx="0" cy="-17" rx="4" ry="8" fill="var(--wb-terracotta,#C4622D)" transform="rotate(0)"/>
						<ellipse cx="0" cy="-17" rx="4" ry="8" fill="var(--wb-terracotta,#C4622D)" transform="rotate(60)"/>
						<ellipse cx="0" cy="-17" rx="4" ry="8" fill="var(--wb-terracotta,#C4622D)" transform="rotate(120)"/>
						<ellipse cx="0" cy="-17" rx="4" ry="8" fill="var(--wb-terracotta,#C4622D)" transform="rotate(180)"/>
						<ellipse cx="0" cy="-17" rx="4" ry="8" fill="var(--wb-terracotta,#C4622D)" transform="rotate(240)"/>
						<ellipse cx="0" cy="-17" rx="4" ry="8" fill="var(--wb-terracotta,#C4622D)" transform="rotate(300)"/>
					</g>
				</g>

				<!-- Abstract ilish fish (right) -->
				<g transform="translate(670,112)">
					<path d="M0,0 C28,-14 76,-18 108,-4 C138,9 138,29 108,44 C76,58 28,54 0,39 C-18,30 -18,9 0,0 Z"
						fill="none" stroke="var(--wb-terracotta,#C4622D)" stroke-width="1.8" opacity="0.42"/>
					<path d="M32,5 C37,0 47,0 52,5 C47,10 37,10 32,5" fill="none" stroke="var(--wb-terracotta,#C4622D)" stroke-width="1" opacity="0.32"/>
					<path d="M58,2 C63,-3 73,-3 78,2 C73,7 63,7 58,2"  fill="none" stroke="var(--wb-terracotta,#C4622D)" stroke-width="1" opacity="0.28"/>
					<path d="M38,24 C43,19 53,19 58,24 C53,29 43,29 38,24" fill="none" stroke="var(--wb-terracotta,#C4622D)" stroke-width="1" opacity="0.28"/>
					<path d="M64,21 C69,16 79,16 84,21 C79,26 69,26 64,21" fill="none" stroke="var(--wb-terracotta,#C4622D)" stroke-width="1" opacity="0.24"/>
					<path d="M0,0 C-18,-10 -38,-4 -33,19 C-38,19 -24,35 0,39"
						fill="none" stroke="var(--wb-terracotta,#C4622D)" stroke-width="1.8" opacity="0.42"/>
					<circle cx="93" cy="19" r="4.5" fill="var(--wb-terracotta,#C4622D)" opacity="0.30"/>
					<circle cx="93" cy="19" r="2"   fill="var(--wb-terracotta,#C4622D)" opacity="0.65"/>
				</g>

				<!-- Left lotus abstract (mirror of fish, other side) -->
				<g transform="translate(130,140)">
					<path d="M0,-30 C6,-22 6,-8 0,0 C-6,-8 -6,-22 0,-30 Z" fill="var(--wb-mustard,#C49A22)" opacity="0.40"/>
					<path d="M0,-30 C6,-22 6,-8 0,0 C-6,-8 -6,-22 0,-30 Z" fill="var(--wb-mustard,#C49A22)" opacity="0.40" transform="rotate(30)"/>
					<path d="M0,-30 C6,-22 6,-8 0,0 C-6,-8 -6,-22 0,-30 Z" fill="var(--wb-mustard,#C49A22)" opacity="0.40" transform="rotate(60)"/>
					<path d="M0,-30 C6,-22 6,-8 0,0 C-6,-8 -6,-22 0,-30 Z" fill="var(--wb-mustard,#C49A22)" opacity="0.40" transform="rotate(-30)"/>
					<path d="M0,-30 C6,-22 6,-8 0,0 C-6,-8 -6,-22 0,-30 Z" fill="var(--wb-mustard,#C49A22)" opacity="0.40" transform="rotate(-60)"/>
					<circle cx="0" cy="0" r="5" fill="var(--wb-terracotta,#C4622D)" opacity="0.50"/>
				</g>

				<!-- Alpona dashed border line -->
				<g opacity="0.30">
					<line x1="180" y1="240" x2="720" y2="240" stroke="var(--wb-mustard,#C49A22)" stroke-width="1" stroke-dasharray="4 10"/>
					<circle cx="180" cy="240" r="3" fill="var(--wb-mustard,#C49A22)"/>
					<circle cx="360" cy="240" r="2" fill="var(--wb-mustard,#C49A22)"/>
					<circle cx="450" cy="240" r="3.5" fill="var(--wb-terracotta,#C4622D)"/>
					<circle cx="540" cy="240" r="2" fill="var(--wb-mustard,#C49A22)"/>
					<circle cx="720" cy="240" r="3" fill="var(--wb-mustard,#C49A22)"/>
				</g>

			</svg>

			<!-- Animated kantha floating circles -->
			<div class="e404-float e404-float--1" aria-hidden="true"></div>
			<div class="e404-float e404-float--2" aria-hidden="true"></div>
			<div class="e404-float e404-float--3" aria-hidden="true"></div>
		</div>
	</div>

	<!-- ── Page content ─────────────────────────────────────── -->
	<div class="e404-body wrap">

		<div class="e404-numeral" aria-hidden="true">404</div>

		<div class="e404-message">
			<p class="e404-bengali" lang="bn">হারানো পথ</p>
			<h1 class="e404-title"><?php esc_html_e( 'Lost in the fields', 'mrittika' ); ?></h1>
			<p class="e404-lead"><?php esc_html_e( 'The page you are looking for has wandered off — like a boat adrift on the Padma in monsoon. Try a search, or explore what is here.', 'mrittika' ); ?></p>
		</div>

		<div class="e404-search">
			<?php get_search_form(); ?>
		</div>

		<div class="e404-suggestions">
			<div class="e404-col">
				<h2 class="e404-col-heading"><?php esc_html_e( 'Recent stories', 'mrittika' ); ?></h2>
				<ul class="e404-recent">
					<?php
					$recent = new WP_Query( array( 'posts_per_page' => 5, 'ignore_sticky_posts' => true ) );
					while ( $recent->have_posts() ) :
						$recent->the_post();
						printf(
							'<li class="e404-recent-item"><a href="%1$s">%2$s</a></li>',
							esc_url( get_permalink() ),
							esc_html( get_the_title() )
						);
					endwhile;
					wp_reset_postdata();
					?>
				</ul>
			</div>

			<div class="e404-col">
				<h2 class="e404-col-heading"><?php esc_html_e( 'Browse topics', 'mrittika' ); ?></h2>
				<div class="e404-topics">
					<?php
					wp_list_categories( array(
						'title_li'   => '',
						'orderby'    => 'count',
						'order'      => 'DESC',
						'number'     => 12,
						'show_count' => false,
					) );
					?>
				</div>
			</div>
		</div>

		<a class="e404-home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
				<path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
			<?php esc_html_e( 'Back to home', 'mrittika' ); ?>
		</a>

	</div>

</main>

<?php get_footer(); ?>
