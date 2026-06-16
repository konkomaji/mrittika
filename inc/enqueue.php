<?php
/**
 * Enqueue styles and scripts.
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Front-end assets.
 */
function mrittika_enqueue_assets() {
	$ver = MRITTIKA_VERSION;

	// Webfonts: Poppins (display) + Inter (body), loaded with display=swap.
	// Can be disabled in Mrittika settings to fall back to the system stack (privacy / speed).
	if ( mrittika_get_option( 'enable_webfonts', true ) ) {
		wp_enqueue_style(
			'mrittika-fonts',
			'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap',
			array(),
			null
		);
	}

	// Design tokens first, then main stylesheet (cascade order matters).
	wp_enqueue_style( 'mrittika-tokens', MRITTIKA_URI . '/assets/css/material-tokens.css', array(), $ver );
	wp_enqueue_style( 'mrittika-main', MRITTIKA_URI . '/assets/css/main.css', array( 'mrittika-tokens' ), $ver );

	// Theme header stylesheet (carries WP theme metadata).
	wp_enqueue_style( 'mrittika-style', get_stylesheet_uri(), array( 'mrittika-main' ), $ver );

	// Scripts (deferred, no jQuery dependency).
	wp_enqueue_script( 'mrittika-theme', MRITTIKA_URI . '/assets/js/theme.js', array(), $ver, true );
	wp_enqueue_script( 'mrittika-navigation', MRITTIKA_URI . '/assets/js/navigation.js', array(), $ver, true );

	if ( mrittika_get_option( 'defer_scripts', true ) ) {
		wp_script_add_data( 'mrittika-theme', 'strategy', 'defer' );
		wp_script_add_data( 'mrittika-navigation', 'strategy', 'defer' );
	}

	// Threaded comments.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Expose small config to JS.
	wp_localize_script( 'mrittika-theme', 'mrittikaConfig', array(
		'themeKey' => 'mrittika-color-scheme',
	) );
}
add_action( 'wp_enqueue_scripts', 'mrittika_enqueue_assets' );

/**
 * Block editor assets (so the editor matches the front end).
 */
function mrittika_block_editor_assets() {
	wp_enqueue_style( 'mrittika-editor', MRITTIKA_URI . '/assets/css/editor.css', array(), MRITTIKA_VERSION );
}
add_action( 'enqueue_block_editor_assets', 'mrittika_block_editor_assets' );

/**
 * Preconnect to Google Fonts hosts when webfonts are enabled (faster first paint).
 */
function mrittika_resource_hints( $hints, $relation ) {
	if ( 'preconnect' === $relation && mrittika_get_option( 'enable_webfonts', true ) ) {
		$hints[] = array( 'href' => 'https://fonts.googleapis.com' );
		$hints[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' );
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'mrittika_resource_hints', 10, 2 );

/**
 * Add a no-flash inline script in <head> to set color scheme before paint.
 */
function mrittika_color_scheme_boot() {
	$default = mrittika_get_option( 'default_scheme', 'auto' );
	?>
	<script>
	(function(){try{var k="mrittika-color-scheme",d="<?php echo esc_js( $default ); ?>",v=localStorage.getItem(k);if(v==="dark"||v==="light"){document.documentElement.setAttribute("data-theme",v);}else if(d==="dark"||d==="light"){document.documentElement.setAttribute("data-theme",d);}}catch(e){}})();
	</script>
	<?php
}
add_action( 'wp_head', 'mrittika_color_scheme_boot', 1 );
