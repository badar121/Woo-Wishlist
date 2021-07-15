<?php
/**
 * 
 * @package  Woo Wishlist
 */
namespace Includes\Base;

use Includes\Base\Helper;

if ( ! class_exists( 'Enqueue' ) ) {
	class Enqueue extends Helper {
		// public $add_css = '';

		public function register() {
			add_action( 'wp_enqueue_scripts', array( $this, 'wwl_enqueue' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'wwl_enqueue' ) );
		}
		
		function wwl_enqueue() {
			// enqueue all our scripts
			wp_enqueue_style( 'wwl-style', $this->plugin_url . 'assets/wwl-style.css' );
			wp_enqueue_script( 'wwl-script', $this->plugin_url . 'assets/wwl-script.js', [], '1.0', true );

			wp_localize_script( 'wwl-script', 'opts', array( 
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'userId' => get_current_user_id(),
				)
			);

			// $this->add_css = wl_get_option( 's&c_custom_css', 'woo_wishlist_stylecolor' );

			// wp_add_inline_style( 'mypluginstyle', $this->add_css );
		}
	}
}