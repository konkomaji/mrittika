<?php
/**
 * Filters and small functional tweaks.
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Body classes for layout state.
 */
function mrittika_body_classes( $classes ) {
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	if ( ! is_active_sidebar( 'sidebar-main' ) ) {
		$classes[] = 'no-sidebar';
	}
	if ( is_singular() && has_post_thumbnail() ) {
		$classes[] = 'has-hero';
	}
	$classes[] = 'mrittika';
	$classes[] = 'card-style-' . sanitize_html_class( mrittika_get_option( 'card_style', 'soft' ) );
	return $classes;
}
add_filter( 'body_class', 'mrittika_body_classes' );

/**
 * Add a pingback header on singular pages.
 */
function mrittika_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'mrittika_pingback_header' );

/**
 * Custom excerpt length and "more".
 */
function mrittika_excerpt_length( $length ) {
	return 28;
}
add_filter( 'excerpt_length', 'mrittika_excerpt_length' );

function mrittika_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'mrittika_excerpt_more' );

/**
 * Slightly trim WP head: remove emoji bloat, shortlink, wlwmanifest.
 */
function mrittika_head_cleanup() {
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );

	if ( mrittika_get_option( 'remove_emoji', true ) ) {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		add_filter( 'tiny_mce_plugins', function ( $plugins ) {
			return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : $plugins;
		} );
	}
}
add_action( 'init', 'mrittika_head_cleanup' );

/**
 * Lazy-load iframes (oEmbeds etc.) when enabled.
 */
function mrittika_lazy_iframes( $content ) {
	if ( is_admin() || ! mrittika_get_option( 'lazy_iframes', true ) ) {
		return $content;
	}
	if ( false === strpos( $content, '<iframe' ) ) {
		return $content;
	}
	return preg_replace_callback( '/<iframe(?![^>]*\bloading=)([^>]*)>/i', function ( $m ) {
		return '<iframe loading="lazy"' . $m[1] . '>';
	}, $content );
}
add_filter( 'the_content', 'mrittika_lazy_iframes', 25 );
add_filter( 'embed_oembed_html', 'mrittika_lazy_iframes', 25 );

/**
 * Add width/height-friendly responsive image sizes attribute for content images.
 */
function mrittika_content_image_sizes( $sizes, $size ) {
	return '(max-width: 720px) 100vw, 720px';
}
add_filter( 'wp_calculate_image_sizes', 'mrittika_content_image_sizes', 10, 2 );

/**
 * Skip-link focus fix handled in JS; ensure skip link target exists.
 */
function mrittika_skip_link() {
	echo '<a class="skip-link screen-reader-text" href="#primary">' . esc_html__( 'Skip to content', 'mrittika' ) . '</a>';
}
add_action( 'wp_body_open', 'mrittika_skip_link' );

/* ── Search: restrict to posts only (exclude pages) ─────── */
function mrittika_search_posts_only( $query ) {
	if ( $query->is_search() && ! $query->is_admin() && $query->is_main_query() ) {
		$query->set( 'post_type', 'post' );
	}
}
add_action( 'pre_get_posts', 'mrittika_search_posts_only' );

/* ── Category image: admin field ────────────────────────── */

function mrittika_cat_image_field_add() {
	wp_enqueue_media();
	?>
	<div class="form-field term-image-wrap">
		<label for="mrittika-cat-image"><?php esc_html_e( 'Category image', 'mrittika' ); ?></label>
		<div>
			<input type="hidden" id="mrittika-cat-image" name="mrittika_cat_image" value="">
			<button type="button" class="button" id="mrittika-cat-image-btn">
				<?php esc_html_e( 'Upload image (800×800)', 'mrittika' ); ?>
			</button>
		</div>
		<p class="description"><?php esc_html_e( 'Square image, 800×800 px minimum. Shown in the Explore Topics grid on the homepage.', 'mrittika' ); ?></p>
		<?php mrittika_cat_image_media_js(); ?>
	</div>
	<?php
}
add_action( 'category_add_form_fields', 'mrittika_cat_image_field_add' );

function mrittika_cat_image_field_edit( $term ) {
	wp_enqueue_media();
	$image_id = absint( get_term_meta( $term->term_id, 'mrittika_cat_image', true ) );
	$src      = $image_id ? wp_get_attachment_image_src( $image_id, 'thumbnail' ) : false;
	?>
	<tr class="form-field term-image-wrap">
		<th scope="row"><label for="mrittika-cat-image"><?php esc_html_e( 'Category image', 'mrittika' ); ?></label></th>
		<td>
			<?php if ( $src ) : ?>
			<img id="mrittika-cat-image-preview" src="<?php echo esc_url( $src[0] ); ?>" style="max-width:100px;border-radius:8px;display:block;margin-bottom:8px;" alt="">
			<?php endif; ?>
			<input type="hidden" id="mrittika-cat-image" name="mrittika_cat_image" value="<?php echo esc_attr( $image_id ); ?>">
			<button type="button" class="button" id="mrittika-cat-image-btn">
				<?php echo $image_id ? esc_html__( 'Change image', 'mrittika' ) : esc_html__( 'Upload image (800×800)', 'mrittika' ); ?>
			</button>
			<?php if ( $image_id ) : ?>
			<button type="button" class="button-link-delete" id="mrittika-cat-image-remove" style="margin-left:8px;"><?php esc_html_e( 'Remove', 'mrittika' ); ?></button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Square image, 800×800 px minimum. Shown in the Explore Topics grid on the homepage.', 'mrittika' ); ?></p>
			<?php mrittika_cat_image_media_js( true ); ?>
		</td>
	</tr>
	<?php
}
add_action( 'category_edit_form_fields', 'mrittika_cat_image_field_edit' );

function mrittika_cat_image_media_js( $has_remove = false ) {
	?>
	<script>
	(function($){
		var btn  = $('#mrittika-cat-image-btn');
		var inp  = $('#mrittika-cat-image');
		var prev = $('#mrittika-cat-image-preview');
		var rem  = $('#mrittika-cat-image-remove');
		btn.on('click', function(e){
			e.preventDefault();
			var frame = wp.media({ title: '<?php echo esc_js( __( 'Select category image', 'mrittika' ) ); ?>', multiple: false, library: { type: 'image' } });
			frame.on('select', function(){
				var att = frame.state().get('selection').first().toJSON();
				inp.val(att.id);
				var imgSrc = att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url;
				if(prev.length){ prev.attr('src', imgSrc).show(); }
				else { $('<img>',{id:'mrittika-cat-image-preview',src:imgSrc,style:'max-width:100px;border-radius:8px;display:block;margin-bottom:8px;'}).insertBefore(btn); }
				btn.text('<?php echo esc_js( __( 'Change image', 'mrittika' ) ); ?>');
				rem.show();
			});
			frame.open();
		});
		rem.on('click', function(){
			inp.val('');
			prev.remove();
			btn.text('<?php echo esc_js( __( 'Upload image (800×800)', 'mrittika' ) ); ?>');
			$(this).hide();
		});
	})(jQuery);
	</script>
	<?php
}

function mrittika_cat_image_save( $term_id ) {
	if ( ! isset( $_POST['mrittika_cat_image'] ) || ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	$image_id = absint( $_POST['mrittika_cat_image'] );
	if ( $image_id ) {
		update_term_meta( $term_id, 'mrittika_cat_image', $image_id );
	} else {
		delete_term_meta( $term_id, 'mrittika_cat_image' );
	}
}
add_action( 'created_category', 'mrittika_cat_image_save' );
add_action( 'edited_category',  'mrittika_cat_image_save' );
