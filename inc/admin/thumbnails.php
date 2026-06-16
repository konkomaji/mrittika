<?php
/**
 * Mrittika — one-click thumbnail regeneration.
 *
 * After image-size changes (3:2 crops, category tiles), existing uploads keep
 * their old intermediate sizes. This batches through every image attachment and
 * rebuilds its metadata so all registered sizes exist. Runs over AJAX in small
 * chunks so it never hits the PHP time limit.
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AJAX: count total image attachments.
 */
function mrittika_ajax_regen_count() {
	check_ajax_referer( 'mrittika_regen', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'Permission denied.', 'mrittika' ) ), 403 );
	}

	$ids = mrittika_get_image_attachment_ids();
	wp_send_json_success( array(
		'total' => count( $ids ),
		'ids'   => $ids,
	) );
}
add_action( 'wp_ajax_mrittika_regen_count', 'mrittika_ajax_regen_count' );

/**
 * AJAX: regenerate a single attachment by ID.
 */
function mrittika_ajax_regen_one() {
	check_ajax_referer( 'mrittika_regen', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'Permission denied.', 'mrittika' ) ), 403 );
	}

	$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
	if ( ! $id || 'attachment' !== get_post_type( $id ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid attachment.', 'mrittika' ) ), 400 );
	}

	$file = get_attached_file( $id );
	if ( ! $file || ! file_exists( $file ) ) {
		// Missing source file — skip without aborting the batch.
		wp_send_json_success( array( 'id' => $id, 'skipped' => true, 'title' => get_the_title( $id ) ) );
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';
	$meta = wp_generate_attachment_metadata( $id, $file );
	if ( ! is_wp_error( $meta ) && ! empty( $meta ) ) {
		wp_update_attachment_metadata( $id, $meta );
	}

	wp_send_json_success( array( 'id' => $id, 'skipped' => false, 'title' => get_the_title( $id ) ) );
}
add_action( 'wp_ajax_mrittika_regen_one', 'mrittika_ajax_regen_one' );

/**
 * Collect every image attachment ID.
 *
 * @return int[]
 */
function mrittika_get_image_attachment_ids() {
	return get_posts( array(
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'post_status'    => 'inherit',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'orderby'        => 'ID',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	) );
}
