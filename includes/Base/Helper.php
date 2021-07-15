<?php
/**
 * 
 * @package  Woo Wishlist
 */
namespace Includes\Base;

if ( ! class_exists( 'Helper' ) ) {
	class Helper {
		public $plugin_path;

		public $plugin_url;
	
		public $plugin;
	
		public function __construct() {
		
			$this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
	
			$this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
	
			$this->plugin = plugin_basename( dirname( __FILE__, 3 ) . '/woo-wishlist.php' );
		}
	}
}