<?php
/**
 * Header template.
 *
 * @package Mrittika
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta name="theme-color" content="#0a0a0a" media="(prefers-color-scheme: dark)">
	<meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

	<header id="masthead" class="site-header" role="banner">
		<div class="header-inner wrap">

			<div class="site-branding">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php endif; ?>

				<div class="site-identity">
					<?php if ( is_front_page() && is_home() ) : ?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php else : ?>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php endif; ?>

					<?php
					$mrittika_desc = get_bloginfo( 'description', 'display' );
					if ( $mrittika_desc || is_customize_preview() ) :
						?>
						<p class="site-description"><?php echo $mrittika_desc; // phpcs:ignore ?></p>
					<?php endif; ?>
				</div>
			</div><!-- .site-branding -->

			<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary', 'mrittika' ); ?>">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<span class="menu-toggle-bars" aria-hidden="true"><span></span><span></span><span></span></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'mrittika' ); ?></span>
				</button>
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'container'      => false,
							'menu_class'     => 'primary-menu-list',
						)
					);
				}
				?>
			</nav>

			<div class="header-actions">
				<button class="search-toggle" aria-controls="header-search" aria-expanded="false" aria-label="<?php esc_attr_e( 'Search', 'mrittika' ); ?>">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
				</button>
				<button class="scheme-toggle" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'mrittika' ); ?>" data-scheme-toggle>
					<svg class="icon-sun" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
					<svg class="icon-moon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
				</button>
			</div>

		</div><!-- .header-inner -->

		<div id="header-search" class="header-search" hidden>
			<div class="wrap"><?php get_search_form(); ?></div>
		</div>

		<?php if ( has_nav_menu( 'topics' ) ) : ?>
			<nav class="topics-bar" aria-label="<?php esc_attr_e( 'Topics', 'mrittika' ); ?>">
				<div class="wrap">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'topics',
							'container'      => false,
							'menu_class'     => 'topics-list',
							'depth'          => 1,
						)
					);
					?>
				</div>
			</nav>
		<?php endif; ?>
	</header><!-- #masthead -->

	<?php
	/** Fires right after the site header — used for the leaderboard ad slot. */
	do_action( 'mrittika_after_header' );
	?>

	<?php
	if ( ! is_front_page() && mrittika_get_option( 'show_breadcrumbs', true ) ) {
		echo '<div class="wrap breadcrumbs-wrap">';
		mrittika_breadcrumbs();
		echo '</div>';
	}
	?>

	<div id="content" class="site-content">
