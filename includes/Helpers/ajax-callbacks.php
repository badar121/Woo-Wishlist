<?php
/**
 * 
 * @package  Woo Wishlist
 */

 /**
 * Passed with ajax request to add a product to Wishlist.
 * 
 * @package woo-wishlist
 * @since 1.0
 */
function wowl_add_to_wishlist() {
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
add_action( 'wp_ajax_wowl_add_to_wishlist', 'wowl_add_to_wishlist' );


/**
 * Passed with ajax request to remove a product from Wishlist.
 * 
 * @package woo-wishlist
 * @since 1.0
 */
function wowl_remove_from_wishlist() {
	$user_id		= (int) $_POST['user_id'];
	$product_id		= (int) $_POST['product_id'];
	$wowl_user_wl	= get_user_meta( $user_id, 'wishlist', true );
	$wowl_item		= array_search( $product_id, $wowl_user_wl );

	if ( ! empty( $wowl_item ) || 0 === $wowl_item ) {
		unset( $wowl_user_wl[$wowl_item] );
		$wowl_user_wl = array_values( $wowl_user_wl );
		update_user_meta( $user_id, 'wishlist', $wowl_user_wl );
	}
	wp_send_json_success( $wowl_user_wl );
}
add_action( 'wp_ajax_wowl_remove_from_wishlist', 'wowl_remove_from_wishlist' );