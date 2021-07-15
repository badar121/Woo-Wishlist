<?php
/**
 * 
 * @package  Woo Wishlist
 */

 /**
 * Passed with ajax request to add a product to Wishlist.
 * 
 * @package SbWl
 * @since 1.0
 */
function sbwl_add_to_wishlist() {
	$user_id	= (int) $_POST['user_id'];
	$product_id	= (int) $_POST['product_id'];
	$meta		= get_user_meta( $user_id, 'wishlist', true );

	if( empty( $meta ) ) {
		$meta = array();
	}
	if ( in_array( $product_id, $meta ) ) {
		wp_send_json_success( 'Alread Added' );
	}
	array_push( $meta, $product_id );
	update_user_meta( $user_id, 'wishlist', $meta );
	wp_send_json_success( $meta );
}
add_action( 'wp_ajax_sbwl_add_to_wishlist', 'sbwl_add_to_wishlist' );


/**
 * Passed with ajax request to remove a product from Wishlist.
 * 
 * @package SbWl
 * @since 1.0
 */
function sbwl_remove_from_wishlist() {
	$user_id		= (int) $_POST['user_id'];
	$product_id		= (int) $_POST['product_id'];
	$sbwl_user_wl	= get_user_meta( $user_id, 'wishlist', true );
	$sbwl_item		= array_search( $product_id, $sbwl_user_wl );

	if ( ! empty( $sbwl_item ) || 0 === $sbwl_item ) {
		unset( $sbwl_user_wl[$sbwl_item] );
		$sbwl_user_wl = array_values( $sbwl_user_wl );
		update_user_meta( $user_id, 'wishlist', $sbwl_user_wl );
	}
	wp_send_json_success( $sbwl_user_wl );
}
add_action( 'wp_ajax_sbwl_remove_from_wishlist', 'sbwl_remove_from_wishlist' );