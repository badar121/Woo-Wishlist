<?php
/**
 * 
 * @package  Woo Wishlist
 */
namespace Includes\Base;

if ( ! class_exists( 'Deactivate' ) ) {
    class Deactivate {
        public static function deactivate() {
            flush_rewrite_rules();
        }
    }
}