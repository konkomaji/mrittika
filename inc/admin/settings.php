<?php
/**
 * Mrittika — dedicated theme settings dashboard.
 *
 * A tabbed admin page (General · Design · Ads · SEO · Performance · Security)
 * backed by the Settings API. All input runs through mrittika_sanitize_options().
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the single option with its sanitizer.
 */
function mrittika_register_settings() {
	register_setting(
		'mrittika_settings_group',
		MRITTIKA_OPTION_KEY,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'mrittika_sanitize_options',
			'default'           => mrittika_default_options(),
		)
	);
}
add_action( 'admin_init', 'mrittika_register_settings' );

/**
 * Add the top-level menu + dashicon.
 */
function mrittika_admin_menu() {
	$hook = add_menu_page(
		__( 'Mrittika Theme', 'mrittika' ),
		__( 'Mrittika', 'mrittika' ),
		'manage_options',
		'mrittika-settings',
		'mrittika_render_settings_page',
		'dashicons-admin-customizer',
		61
	);
	add_action( "admin_print_styles-{$hook}", 'mrittika_admin_assets' );
}
add_action( 'admin_menu', 'mrittika_admin_menu' );

/**
 * Enqueue admin CSS/JS only on the settings screen.
 */
function mrittika_admin_assets() {
	wp_enqueue_style( 'mrittika-admin', MRITTIKA_URI . '/assets/admin/admin.css', array(), MRITTIKA_VERSION );
	wp_enqueue_media();
	wp_enqueue_script( 'mrittika-admin', MRITTIKA_URI . '/assets/admin/admin.js', array( 'jquery' ), MRITTIKA_VERSION, true );
}

/**
 * Field helpers.
 */
function mrittika_field_name( $key ) {
	return MRITTIKA_OPTION_KEY . '[' . $key . ']';
}

function mrittika_checkbox( $key, $label, $desc = '' ) {
	$val = mrittika_get_option( $key );
	printf(
		'<label class="m-toggle"><input type="checkbox" name="%1$s" value="1" %2$s><span class="m-switch"></span><span class="m-toggle-text"><strong>%3$s</strong>%4$s</span></label>',
		esc_attr( mrittika_field_name( $key ) ),
		checked( (bool) $val, true, false ),
		esc_html( $label ),
		$desc ? '<em>' . esc_html( $desc ) . '</em>' : ''
	);
}

function mrittika_text( $key, $label, $placeholder = '', $desc = '' ) {
	$val = mrittika_get_option( $key );
	printf(
		'<div class="m-field"><label for="%1$s"><strong>%2$s</strong></label><input type="text" id="%1$s" name="%3$s" value="%4$s" placeholder="%5$s" class="regular-text">%6$s</div>',
		esc_attr( $key ),
		esc_html( $label ),
		esc_attr( mrittika_field_name( $key ) ),
		esc_attr( $val ),
		esc_attr( $placeholder ),
		$desc ? '<p class="m-desc">' . esc_html( $desc ) . '</p>' : ''
	);
}

function mrittika_textarea( $key, $label, $desc = '', $rows = 4 ) {
	$val = mrittika_get_option( $key );
	printf(
		'<div class="m-field"><label for="%1$s"><strong>%2$s</strong></label><textarea id="%1$s" name="%3$s" rows="%4$d" class="large-text code">%5$s</textarea>%6$s</div>',
		esc_attr( $key ),
		esc_html( $label ),
		esc_attr( mrittika_field_name( $key ) ),
		(int) $rows,
		esc_textarea( $val ),
		$desc ? '<p class="m-desc">' . wp_kses_post( $desc ) . '</p>' : ''
	);
}

function mrittika_select( $key, $label, $choices, $desc = '' ) {
	$val = mrittika_get_option( $key );
	echo '<div class="m-field"><label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $label ) . '</strong></label>';
	echo '<select id="' . esc_attr( $key ) . '" name="' . esc_attr( mrittika_field_name( $key ) ) . '">';
	foreach ( $choices as $cval => $clabel ) {
		printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $cval ), selected( $val, $cval, false ), esc_html( $clabel ) );
	}
	echo '</select>';
	if ( $desc ) {
		echo '<p class="m-desc">' . esc_html( $desc ) . '</p>';
	}
	echo '</div>';
}

function mrittika_image_field( $key, $label, $desc = '' ) {
	$val = mrittika_get_option( $key );
	echo '<div class="m-field m-image-field"><label><strong>' . esc_html( $label ) . '</strong></label>';
	echo '<div class="m-image-preview">' . ( $val ? '<img src="' . esc_url( $val ) . '" alt="">' : '' ) . '</div>';
	echo '<input type="url" name="' . esc_attr( mrittika_field_name( $key ) ) . '" value="' . esc_attr( $val ) . '" class="regular-text m-image-url" placeholder="https://…">';
	echo '<button type="button" class="button m-image-pick">' . esc_html__( 'Select image', 'mrittika' ) . '</button>';
	echo '<button type="button" class="button-link m-image-clear">' . esc_html__( 'Clear', 'mrittika' ) . '</button>';
	if ( $desc ) {
		echo '<p class="m-desc">' . esc_html( $desc ) . '</p>';
	}
	echo '</div>';
}

/**
 * Render the page.
 */
function mrittika_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$tabs = array(
		'general'     => __( 'General', 'mrittika' ),
		'design'      => __( 'Design', 'mrittika' ),
		'ads'         => __( 'Ads', 'mrittika' ),
		'seo'         => __( 'SEO', 'mrittika' ),
		'performance' => __( 'Performance', 'mrittika' ),
		'security'    => __( 'Security', 'mrittika' ),
	);
	?>
	<div class="wrap mrittika-admin">
		<header class="mrittika-admin-head">
			<div>
				<h1><span class="m-logo">মৃ</span> <?php esc_html_e( 'Mrittika', 'mrittika' ); ?></h1>
				<p class="m-sub"><?php esc_html_e( 'Theme settings — monochrome editorial, Material Expressive 3.', 'mrittika' ); ?> <span class="m-ver">v<?php echo esc_html( MRITTIKA_VERSION ); ?></span></p>
			</div>
		</header>

		<?php settings_errors(); ?>

		<nav class="mrittika-tabs" role="tablist">
			<?php $first = true; foreach ( $tabs as $id => $title ) : ?>
				<button type="button" class="m-tab<?php echo $first ? ' is-active' : ''; ?>" role="tab" data-tab="<?php echo esc_attr( $id ); ?>" aria-selected="<?php echo $first ? 'true' : 'false'; ?>"><?php echo esc_html( $title ); ?></button>
				<?php $first = false; endforeach; ?>
		</nav>

		<form method="post" action="options.php" class="mrittika-form">
			<?php settings_fields( 'mrittika_settings_group' ); ?>

			<!-- GENERAL -->
			<section class="m-panel is-active" data-panel="general">
				<h2><?php esc_html_e( 'General', 'mrittika' ); ?></h2>
				<?php
				mrittika_checkbox( 'show_reading_time', __( 'Show reading time', 'mrittika' ) );
				mrittika_checkbox( 'show_breadcrumbs', __( 'Show breadcrumbs', 'mrittika' ) );
				mrittika_checkbox( 'show_related', __( 'Show related stories', 'mrittika' ) );
				mrittika_checkbox( 'show_toc', __( 'Show table of contents on long posts', 'mrittika' ) );
				mrittika_checkbox( 'show_share', __( 'Show share buttons', 'mrittika' ) );
				mrittika_textarea( 'footer_text', __( 'Footer text', 'mrittika' ), __( 'Leave blank for the default copyright line. HTML allowed.', 'mrittika' ), 3 );
				?>
			</section>

			<!-- DESIGN -->
			<section class="m-panel" data-panel="design">
				<h2><?php esc_html_e( 'Design', 'mrittika' ); ?></h2>
				<?php
				mrittika_select( 'default_scheme', __( 'Default color scheme', 'mrittika' ), array(
					'auto'  => __( 'Auto (follow device)', 'mrittika' ),
					'light' => __( 'Light', 'mrittika' ),
					'dark'  => __( 'Dark', 'mrittika' ),
				) );
				mrittika_select( 'card_style', __( 'Card style', 'mrittika' ), array(
					'soft'    => __( 'Soft (rounded, subtle shadow)', 'mrittika' ),
					'outline' => __( 'Outline (bordered)', 'mrittika' ),
					'flat'    => __( 'Flat (no border, no shadow)', 'mrittika' ),
				) );
				mrittika_checkbox( 'enable_webfonts', __( 'Load Poppins + Inter webfonts', 'mrittika' ), __( 'Turn off to use the system font stack only (fastest, no third-party request).', 'mrittika' ) );
				?>
				<p class="m-note"><?php esc_html_e( 'The palette is intentionally monochrome (black, white, grays) — the brand constraint. Fine-tune individual tones in assets/css/material-tokens.css.', 'mrittika' ); ?></p>
			</section>

			<!-- ADS -->
			<section class="m-panel" data-panel="ads">
				<h2><?php esc_html_e( 'Google AdSense', 'mrittika' ); ?></h2>
				<?php
				mrittika_text( 'adsense_publisher_id', __( 'Publisher ID', 'mrittika' ), 'ca-pub-0000000000000000', __( 'Your AdSense client ID. Format: ca-pub- followed by digits.', 'mrittika' ) );
				mrittika_checkbox( 'adsense_auto_ads', __( 'Enable Auto Ads', 'mrittika' ), __( 'Let Google place ads automatically (page-level).', 'mrittika' ) );
				mrittika_textarea( 'ad_after_header', __( 'Ad: after header (leaderboard)', 'mrittika' ), __( 'Paste an AdSense ad-unit snippet.', 'mrittika' ) );
				mrittika_textarea( 'ad_in_content', __( 'Ad: in-content', 'mrittika' ), __( 'Injected mid-article on single posts.', 'mrittika' ) );
				mrittika_text( 'ad_in_content_after', __( 'Inject in-content ad after paragraph #', 'mrittika' ), '3' );
				mrittika_textarea( 'ad_after_post', __( 'Ad: after post', 'mrittika' ) );
				mrittika_textarea( 'ad_sidebar', __( 'Ad: sidebar', 'mrittika' ) );
				?>
				<p class="m-note"><?php esc_html_e( 'A virtual /ads.txt is served automatically from your Publisher ID when no physical ads.txt exists at the site root. Ads never render inside navigation, per AdSense policy.', 'mrittika' ); ?></p>
			</section>

			<!-- SEO -->
			<section class="m-panel" data-panel="seo">
				<h2><?php esc_html_e( 'SEO', 'mrittika' ); ?></h2>
				<?php
				mrittika_checkbox( 'seo_enable', __( 'Output built-in SEO meta', 'mrittika' ), __( 'Automatically disabled when Yoast, Rank Math, SEOPress, or AIOSEO is active.', 'mrittika' ) );
				mrittika_text( 'twitter_site', __( 'Twitter / X handle', 'mrittika' ), '@bengalpropertyindex' );
				mrittika_image_field( 'default_share_image', __( 'Default social share image', 'mrittika' ), __( 'Used for Open Graph when a post has no featured image.', 'mrittika' ) );
				mrittika_text( 'verify_google', __( 'Google Search Console verification', 'mrittika' ), '', __( 'The token only (content="…") from the meta-tag method.', 'mrittika' ) );
				mrittika_text( 'verify_bing', __( 'Bing Webmaster verification', 'mrittika' ) );
				?>
			</section>

			<!-- PERFORMANCE -->
			<section class="m-panel" data-panel="performance">
				<h2><?php esc_html_e( 'Performance', 'mrittika' ); ?></h2>
				<?php
				mrittika_checkbox( 'defer_scripts', __( 'Defer theme scripts', 'mrittika' ) );
				mrittika_checkbox( 'remove_emoji', __( 'Remove emoji script bloat', 'mrittika' ) );
				mrittika_checkbox( 'lazy_iframes', __( 'Lazy-load iframes', 'mrittika' ) );
				?>
			</section>

			<!-- SECURITY -->
			<section class="m-panel" data-panel="security">
				<h2><?php esc_html_e( 'Security', 'mrittika' ); ?></h2>
				<?php
				mrittika_checkbox( 'sec_headers', __( 'Send security headers', 'mrittika' ), __( 'X-Content-Type-Options, Referrer-Policy, X-Frame-Options, Permissions-Policy, HSTS (HTTPS).', 'mrittika' ) );
				mrittika_checkbox( 'sec_disable_xmlrpc', __( 'Disable XML-RPC', 'mrittika' ), __( 'Blocks a common brute-force / pingback vector.', 'mrittika' ) );
				mrittika_checkbox( 'sec_block_user_enum', __( 'Block user enumeration', 'mrittika' ), __( 'Stops ?author=N probing and REST user listing for guests.', 'mrittika' ) );
				mrittika_checkbox( 'sec_hide_version', __( 'Hide WordPress version', 'mrittika' ) );
				mrittika_checkbox( 'sec_comment_links', __( 'Harden comment links (nofollow ugc noopener)', 'mrittika' ) );
				?>
				<p class="m-note"><?php esc_html_e( 'These are theme-level hardening defaults. They complement — not replace — a dedicated security plugin and proper server configuration.', 'mrittika' ); ?></p>
			</section>

			<?php submit_button( __( 'Save settings', 'mrittika' ), 'primary m-save' ); ?>
		</form>
	</div>
	<?php
}
