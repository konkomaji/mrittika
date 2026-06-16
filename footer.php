<?php
/**
 * Footer template.
 *
 * @package Mrittika
 */

?>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">

		<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
			<div class="footer-widgets wrap">
				<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
					<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
						<div class="footer-column footer-column-<?php echo (int) $i; ?>">
							<?php dynamic_sidebar( 'footer-' . $i ); ?>
						</div>
					<?php endif; ?>
				<?php endfor; ?>
			</div>
		<?php endif; ?>

		<div class="footer-bottom wrap">
			<div class="footer-brand">
				<span class="footer-sitename"><?php bloginfo( 'name' ); ?></span>
				<?php $tagline = get_bloginfo( 'description' ); if ( $tagline ) : ?>
					<span class="footer-tagline"><?php echo esc_html( $tagline ); ?></span>
				<?php endif; ?>
			</div>

			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer', 'mrittika' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'menu_class'     => 'footer-menu-list',
							'depth'          => 1,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<?php if ( has_nav_menu( 'social' ) ) : ?>
				<nav class="social-navigation" aria-label="<?php esc_attr_e( 'Social links', 'mrittika' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'social',
							'container'      => false,
							'menu_class'     => 'social-list',
							'depth'          => 1,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<div class="site-info">
				<?php
				$footer_text = get_theme_mod( 'mrittika_footer_text' );
				if ( $footer_text ) {
					echo wp_kses_post( $footer_text );
				} else {
					printf(
						/* translators: 1: year, 2: site name */
						esc_html__( '© %1$s %2$s. All rights reserved.', 'mrittika' ),
						esc_html( wp_date( 'Y' ) ),
						esc_html( get_bloginfo( 'name' ) )
					);
				}
				?>
			</div>
		</div>

	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
