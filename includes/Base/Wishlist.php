<?php
/**
 * 
 * @package  Woo Wishlist
 */

namespace Includes\Base;

if ( ! class_exists( 'Wishlist' ) ) {
	class Wishlist {
		public $sbwl_btn		= '';
		public $sbwl_btn_icon 	= '';
		public $sbwl_btn_text 	= '';
		public $sbwl_btn_pos 	= '';
		public $sbwl_page_id 	= '';

		/**
		 * This function stores all the code that will run when register_services()
		 * will be called.
		 */
		public function register() {
			$this->sbwl_btn			= sbwl_get_option( 'snc_add_wishlist_style', 'sbwl_stylecolor_section', 'wwl-btn-link' );
			$this->sbwl_btn_icon	= sbwl_get_option( 's&c_add_wishlist_icon', 'sbwl_stylecolor_section', 'cart' );
			$this->sbwl_btn_text	= sbwl_get_option( 'text_customization_field_1', 'sbwl_add_section', 'Add to wishlist');
			$this->sbwl_btn_pos		= sbwl_get_option( 'product_page_settings', 'sbwl_add_section', 'Add to wishlist');
			$this->sbwl_page_id		= sbwl_get_option( 'page_option_select', 'woo_page_opt_section' );
			$this->sbwl_user_id		= get_current_user_id();

			// Enable/Disable wishlist button in shop loop.
			if ( 'on' === sbwl_get_option( 'loop_settings', 'sbwl_add_section' ) ) {
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'sbwl_wishlist_button' ) );
			}

			// Location of wishlist button on product's page.
			if ( 'after_add_to_cart' ===  $this->sbwl_btn_pos ) {
				add_action( 'woocommerce_after_add_to_cart_form', array( $this, 'sbwl_wishlist_button' ) );
			}

			if ( 'after_thumbnail' ===  $this->sbwl_btn_pos ) {
				add_action( 'woocommerce_product_thumbnails', array( $this, 'sbwl_wishlist_button' ) );
			}

			if ( 'after_summary' ===  $this->sbwl_btn_pos ) {
				add_action( 'woocommerce_product_after_tabs', array( $this, 'sbwl_wishlist_button' ) );
			}
		}

		/**
		 * Render the wishlist button on the product page.
		 * 
		 * @since 1.0
		 * @package SbWl
		 */
		public function sbwl_wishlist_button() {
			global $product;
			$sbwl_page_link = get_permalink( $this->sbwl_page_id );
			$sbwl_user_wl 	= get_user_meta( get_current_user_id(), 'wishlist', true );
			// var_dump($sbwl_user_wl);
            // die;
			//if ( ! in_array( $product->get_id(), $sbwl_user_wl ) ) :
			?>
				<a class="wishlist-toggle <?php esc_attr_e( $this->sbwl_btn ); ?>" data-product="<?php esc_attr_e( $product->get_id() ); ?>" href="#">
					<span class="dashicons dashicons-<?php esc_attr_e( $this->sbwl_btn_icon ); ?>"></span>
					&nbsp;&nbsp;<?php esc_html_e( $this->sbwl_btn_text ); ?>
				</a>
			<?php
			// endif;
			//if ( in_array( $product->get_id(), $sbwl_user_wl ) ) :
			?>
				<div class="wl-already-added wwl-hide">
					<?php esc_html_e( sbwl_get_option( 'text_customization_field_4', 'sbwl_add_section' ) ); ?>
					<a href="<?php echo esc_url( $sbwl_page_link ); ?>">
						&nbsp;<?php esc_html_e( sbwl_get_option( 'text_customization_field_3', 'sbwl_add_section' ) ); ?>
					</a>
				</div>
			<?php
			//endif;
			?>
				<div class="wl-new-added wwl-hide <?php esc_attr_e( sbwl_get_option( 'after_product_added', 'sbwl_add_section' ) ); ?>">
					<?php esc_html_e( sbwl_get_option( 'text_customization_field_2', 'sbwl_add_section' ) ); ?>
					<a href="<?php echo esc_url( $sbwl_page_link ); ?>">
						&nbsp;<?php esc_html_e( sbwl_get_option( 'text_customization_field_3', 'sbwl_add_section' ) ); ?>
					</a>
				</div>
			<?php
		}
	}
}