<?php
/**
 * 
 * @package  Woo Wishlist
 */
namespace Includes\Base;

if ( ! class_exists( 'Activate' ) ) {
	class Activate {

		public static function activate() {
			global $wpdb;
			$sbwl_query = "SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'woo-wishlist', 'ARRAY_A'";
		
			// Create Wishlist page if it don't exist.
			if ( null === $wpdb->get_row( $sbwl_query ) ) {
				$args = array(
					'post_title'	=>	__( 'Wishlist', 'woo-wishlist' ),
					'post_status'	=>	__( 'publish', 'woo-wishlist' ),
					'post_content'	=>	'[wwl_wishlist_shortcode]',
					'post_author'	=>	get_current_user_id(),
					'post_type'		=>	'page',
				);
				wp_insert_post( $args );
			}
		}
}