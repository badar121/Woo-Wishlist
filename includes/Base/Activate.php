<?php
/**
 * 
 * @package  Woo Wishlist
 */
namespace Includes\Base;

if ( ! class_exists( 'Activate' ) ) {
	class Activate {

		public static function activate() {

			self::set_default_setting();
			global $wpdb;
			$wowl_query = "SELECT * FROM `wp_posts` WHERE `post_content` = '[wwl_wishlist_shortcode]'";
		
			// Create Wishlist page if it don't exist.
			if ( null === $wpdb->get_row( $wowl_query ) ) {
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

		/**
 * Default settings of the plugin.
 *
 * @package Woo Wishlist
 * @since 1.0.0
 */
public static function get_default_settings() {
	$settings = array();

	// Add to wishlist options.
	$settings['wowl_add_section']['after_product_added']			= 'wl-btn-hide';
	$settings['wowl_add_section']['loop_settings']					= 'off';
	$settings['wowl_add_section']['product_page_settings']			= 'after_add_to_cart';
	$settings['wowl_add_section']['text_customization_field_1']		= 'Add to wishlist';
	$settings['wowl_add_section']['text_customization_field_2']		= 'Product added!';
	$settings['wowl_add_section']['text_customization_field_3']		= 'Browse Wishlist';
	$settings['wowl_add_section']['text_customization_field_4']		= 'The product is already in your wishlist!';

	// Style and color.
	$settings['wowl_stylecolor_section']['snc_add_wishlist_style']			= 'wl-btn-btn';
	$settings['wowl_stylecolor_section']['s&c_add_wishlist_icon']			= 'cart';
	$settings['wowl_stylecolor_section']['s&c_added_wishlist_icon']			= 'cart';
	$settings['wowl_stylecolor_section']['s&c_custom_css']					= '';

	// Wishlist page options.
	$settings['wowl_page_opt_section']['page_option_select']			= '';
	$settings['wowl_page_opt_section']['page_option_table_show']		= array('price' => 'price', 'stock' => 'stock', 'add_to_cart' => 'add_to_cart');
	$settings['wowl_page_opt_section']['page_option_redirect']			= 'off';
	$settings['wowl_page_opt_section']['page_option_remove']			= 'off';
	$settings['wowl_page_opt_section']['page_option_social_share']		= 'off';
	$settings['wowl_page_opt_section']['page_option_share']				= array( 'facebook' => 'facebook', 'twitter' => 'twitter', 'pinterest' => 'pinterest' );
	$settings['wowl_page_opt_section']['page_option_share_title']		= 'My Wishlist';
	$settings['wowl_page_opt_section']['page_option_share_link']		= '';
	$settings['wowl_page_opt_section']['page_option_wishlist_name']		= 'My Wishlist';
	$settings['wowl_page_opt_section']['page_option_cart_name']			= 'Add to cart';

	return $settings;
}


	/**
	 * Set default setting
	 *
	 * @package Woo Wishlist
	 * @since 1.0.0
	 */
	public static function set_default_setting() {
		if ( !get_option( 'wowl_add_section' ) ) {
			$set_defaults = self::get_default_settings();

			foreach ( $set_defaults as $key => $value ) {
				if ( 'wowl_add_section' === $key ) {
					update_option( $key, $value );
				}
				if ( 'wowl_stylecolor_section' === $key ) {
					update_option( $key, $value );
				}
				if ( 'wowl_page_opt_section' === $key ) {
					update_option( $key, $value );
				}
			}

		}
	}


	/**
	 * Reset Default settings
	 *
	 * @package Woo Wishlist
	 * @since 1.0.0
	 */
	public static function reset_settings() {
		$default_settings = self::get_default_settings();
		update_option( 'wowl_options', $default_settings );
		wp_send_json(
			array(
				'success' => true,
				'data'    => $default_settings,
			)
		);
	}

	}
}