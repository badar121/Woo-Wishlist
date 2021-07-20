<?php
/*
Plugin Name: Woo Wishlist
Description: Woo Wishlist is Wishlist Plugin.
Version: 1.0
Author: Badar Shahbaz
License: GPLv3
Text Domain: woo-wishlist
*/


// Checking if the plugin is not accessed from outside WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

// Defining plugin constants
if	( ! defined( 'WWL'	) )	{
	define( 'WWL', true );
}

define( 'WWL_PLUGIN_VERSION', '1.0' );
define( 'WWL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WWL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );


// Requiring the composer autoload file
if	( file_exists(	dirname( __FILE__ ) . '/vendor/autoload.php' )	) {
		require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

// Code that runs during plugin activaation
function activate_woo_wishlist() {
		Includes\Base\Activate::activate();
}
register_activation_hook(	__FILE__, 'activate_woo_wishlist'	);

// Code that runs during plugin deactivaation
function deactivate_woo_wishlist()	{
	Includes\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_woo_wishlist' );


// Calling the globla custom functions file
require_once WWL_PLUGIN_PATH . 'includes/Helpers/woo-wishlist-functions.php';

// Calling the api callback functions file
require_once WWL_PLUGIN_PATH . 'includes/Helpers/ajax-callbacks.php' ;

/**
 * Init all the files required for plugin to run
 * Launching the Plugin 1,2,3...
 */
if	(	class_exists( 'Includes\\Init' )	)	{
	Includes\Init::register_services();
}