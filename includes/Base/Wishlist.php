<?php
/**
 * 
 * @package  Woo Wishlist
 */

namespace Includes\Base;

if ( ! class_exists( 'Wishlist' ) ) {
	class Wishlist {
		public $wowl_btn		= '';
		public $wowl_btn_icon 	= '';
		public $wowl_btn_text 	= '';
		public $wowl_btn_pos 	= '';
		public $wowl_page_id 	= '';

		/**
		 * This function stores all the code that will run when register_services()
		 * will be called.
		 */
		public function register() {
			$this->wowl_btn			= wowl_get_option( 'snc_add_wishlist_style', 'wowl_stylecolor_section', 'wwl-btn-link' );
			$this->wowl_btn_icon	= wowl_get_option( 's&c_add_wishlist_icon', 'wowl_stylecolor_section', 'cart' );
			$this->wowl_btn_text	= wowl_get_option( 'text_customization_field_1', 'wowl_add_section', 'Add to wishlist');
			$this->wowl_btn_pos		= wowl_get_option( 'product_page_settings', 'wowl_add_section', 'Add to wishlist');
			$this->wowl_page_id		= wowl_get_option( 'page_option_select', 'woo_page_opt_section' );
			$this->wowl_user_id		= get_current_user_id();

			// Enable/Disable wishlist button in shop loop.
			if ( 'on' === wowl_get_option( 'loop_settings', 'wowl_add_section' ) ) {
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'wowl_wishlist_button' ) );
			}

			// Location of wishlist button on product's page.
			if ( 'after_add_to_cart' ===  $this->wowl_btn_pos ) {
				add_action( 'woocommerce_after_add_to_cart_form', array( $this, 'wowl_wishlist_button' ) );
			}

			if ( 'after_thumbnail' ===  $this->wowl_btn_pos ) {
				add_action( 'woocommerce_product_thumbnails', array( $this, 'wowl_wishlist_button' ) );
			}

			if ( 'after_summary' ===  $this->wowl_btn_pos ) {
				add_action( 'woocommerce_product_after_tabs', array( $this, 'wowl_wishlist_button' ) );
			}
		}

		/**
		 * Render the wishlist button on the product page.
		 * 
		 * @since 1.0
		 * @package wowl
		 */
		public function wowl_wishlist_button() {
			global $product;
			$wowl_page_link = get_permalink( $this->wowl_page_id );
			$wowl_user_wl 	= get_user_meta( get_current_user_id(), 'wishlist', true );
			// var_dump($wowl_user_wl);
            // die;
			//if ( ! in_array( $product->get_id(), $wowl_user_wl ) ) :
			?>
				<a class="wishlist-toggle <?php esc_attr_e( $this->wowl_btn ); ?>" data-product="<?php esc_attr_e( $product->get_id() ); ?>" href="#">
					<span class="dashicons dashicons-<?php esc_attr_e( $this->wowl_btn_icon ); ?>"></span>
					&nbsp;&nbsp;<?php esc_html_e( $this->wowl_btn_text ); ?>
				</a>
			<?php
			// endif;
			//if ( in_array( $product->get_id(), $wowl_user_wl ) ) :
			?>
				<div class="wl-already-added wwl-hide">
					<?php esc_html_e( wowl_get_option( 'text_customization_field_4', 'wowl_add_section' ) ); ?>
					<a href="<?php echo esc_url( $wowl_page_link ); ?>">
						&nbsp;<?php esc_html_e( wowl_get_option( 'text_customization_field_3', 'wowl_add_section' ) ); ?>
					</a>
				</div>
			<?php
			//endif;
			?>
				<div class="wl-new-added wwl-hide <?php esc_attr_e( wowl_get_option( 'after_product_added', 'wowl_add_section' ) ); ?>">
					<?php esc_html_e( wowl_get_option( 'text_customization_field_2', 'wowl_add_section' ) ); ?>
					<a href="<?php echo esc_url( $wowl_page_link ); ?>">
						&nbsp;<?php esc_html_e( wowl_get_option( 'text_customization_field_3', 'wowl_add_section' ) ); ?>
					</a>
				</div>
			<?php
		}
	}
}