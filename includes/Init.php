<?php
/**
 * 
 * @package  Woo Wishlist
 */
namespace Includes;

final class Init {
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services() {
		return array(
			Pages\Admin::class,
			Base\Wishlist::class,
			Base\Enqueue::class,
		);
	}

	public static function register_services() {
		foreach ( self::get_services() as $class ) {
			$service = self::instantiate( $class );
			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

	private static function instantiate( $class ) {
		$service = new $class();
		return $service;
	}

}