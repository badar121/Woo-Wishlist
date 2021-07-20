<?php
/**
 * 
 * @package  Woo Wishlist
 */
namespace Includes\Pages;

use Includes\Api\SettingsApi;

class Admin {
	public $settings;
	public $pages		= array();
	public $sub_pages	= array();

	/**
	 * This function stores all the code that will run when register_services
	 * will be called.
	 */
	public function register() {
		$this->settings = new SettingsApi();
		$this->set_pages();

		$this->settings->add_pages( $this->pages )->with_sub_pages( 'General Settings' )->register();
		add_action( 'admin_init', array($this, 'admin_init') );
	}

	public function set_pages() {
		$this->pages = array(
		array(
			'page_title'	=> __( 'Woo Wishlist' , 'woo-wishlist'),
			'menu_title'	=> 'Woo Wishlist',
			'capability'	=> 'manage_options',
			'menu_slug'		=> 'woo_wishlist',
			'callback'		=> array( $this, 'plugin_page' ),
			'icon_url'		=> 'dashicons-heart',
			'position'		=> 58,
		)
		);
	}

	/**
	 * Get all the pages
	 *
	 * @return array page names with key value pairs
	 */
	function get_pages() {
		$pages = get_pages();
		$pages_options = array();
		
		if ( $pages ) {
			foreach ($pages as $page) {
				$pages_options[$page->ID] = $page->post_title;
			}
		}
		return $pages_options;
	}

	public function admin_init() {
		$this->settings->set_sections( $this->get_settings_sections() );
		$this->settings->set_fields( $this->get_settings_fields() );

		$this->settings->admin_init();
	}

	/**
	 * Returns all the section fields.
	 * 
	 * @return array section fields
	 */
	public function get_settings_sections() {
		$sections = array(
			[
				'id'    => 'sbwl_general_section',
				'title' => __( 'GENERAL SETTINGS', 'sb-woo-whislist' )
			],
			[
				'id'    => 'sbwl_add_section',
				'title' => __( 'ADD TO WISHLIST OPTIONS', 'sb-woo-whislist' )
			],
			[
				'id'    => 'sbwl_stylecolor_section',
				'title' => __( 'STYLE & COLOR', 'sb-woo-whislist' )
			],
			[
				'id'    => 'woo_page_opt_section',
				'title' => __( 'WISHLIST PAGE OPTIONS', 'sb-woo-whislist' )
			],
		);
		return $sections;
	}

	/**
	 * Returns all the setting fields.
	 * 
	 * @return array setting fields
	 */
	public function get_settings_fields() {
		$setting_fields = array(
			'sbwl_general_section' => array(
				array(
					'name'		=> 'enable_ajax_loading',
					'label'		=> __( 'Enable Ajax loading', 'sb-woo-whislist' ),
					'desc'		=> __( 'Load any cacheable wishlist item via AJAX', 'sb-woo-whislist' ),
					'default'	=> 'off',
					'type'		=> 'checkbox',
				),
				array(
					'name'		=> 'reset_defaults',
					'label'		=> __( 'Reset Defaults', 'sb-woo-whislist' ),
					'desc'		=> __( 'Check this box to reset the default settings', 'sb-woo-whislist' ),
					'type'		=> 'checkbox',
				),
			),
			'sbwl_add_section' => array(
				array(
					'name'		=> 'after_product_added',
					'label'		=> __('After product is added to wishlist', 'sb-woo-whislist'),
					'desc'		=> __('', 'sb-woo-whislist'),
					'type'		=> 'radio',
					'default'	=> 'wwl-btn-hide',
					'options'	=> array(
						'wwl-btn-btn' => 'Show "Add to wishlist" button',
						'wwl-btn-link' => 'Show "View wishlist" link',
						'wwl-btn-hide' => 'Remove wishlist button/link',
					),
				),
				array(
					'name'		=> 'loop_settings_text',
					'label'		=> __('<h2 style="font-weight: 700;">Loop Settings</h2>', 'sb-woo-whislist'),
					'desc'		=> __('Loop options will be visible on Shop page, category pages, product shortcodes,
								 products sliders, and all the other places where the WooCommerce products’ loop is used',
								'sb-woo-whislist'),
					'type'		=> 'html',
				),
				array(
					'name'		=> 'loop_settings',
					'label'		=> __( 'Show "Add to wishlist" in loop', 'sb-woo-whislist' ),
					'desc'		=> __( 'Enable the "Add to wishlist" feature in WooCommerce product\'s loop' ),
					'type'		=> 'checkbox',
				),
				array(
					'name'	=> 'product_page_settings_text',
					'label'	=> __('<h2 style="font-weight: 700;">Product page settings</h2>', 'sb-woo-whislist'),
					'type'	=> 'html',
				),
				array(
					'name'		=> 'product_page_settings',
					'label'		=> __('Position of "Add to wishlist" on product page', 'sb-woo-whislist'),
					'desc'		=> __('Choose where to show "Add to wishlist" button or link on the product page.', 'sb-woo-whislist'),
					'type'		=> 'select',
					'default'	=> 'after_add_to_cart',
					'options' 	=> array(
						'after_add_to_cart'	=> 'After "Add to Cart"',
						'after_thumbnail'	=> 'After thumbnails',
						'after_summary'		=> 'After summary'
					),
				),
				array(
					'name'		=> 'text_customization_text',
					'label'		=> __('<h2 style="font-weight: 700;">Text Customization</h2>', 'sb-woo-whislist'),
					'type'		=> 'html',
				),
				array(
					'name'		=> 'text_customization_field_1',
					'label'		=> __('"Add to wishlist" text', 'sb-woo-whislist'),
					'desc'		=> __('Enter a text for "Add to wishlist" button', 'sb-woo-whislist'),
					'type'		=> 'text',
					'default'	=>'Add to wishlist',
				),
				array(
					'name'		=> 'text_customization_field_2',
					'label'		=> __('"Product added" text', 'sb-woo-whislist'),
					'desc'		=> __('Enter the text of the message displayed when the user adds a product to the wishlist', 'sb-woo-whislist'),
					'type'		=> 'text',
					'default' 	=> 'Product added!',
				),
				array(
					'name'		=> 'text_customization_field_3',
					'label'		=> __('"Browse wishlist" text', 'sb-woo-whislist'),
					'desc'		=> __('Enter a text for the "Browse wishlist" link on the product page', 'sb-woo-whislist'),
					'type'		=> 'text',
					'default'	=> 'Browse Wishlist',
				),
				array(
					'name'		=> 'text_customization_field_4',
					'label'		=> __('"Product already in wishlist" text', 'sb-woo-whislist'),
					'desc'		=> __('Enter the text for the message displayed when the user views a product that is already in the wishlist', 'sb-woo-whislist'),
					'type'		=> 'text',
					'default'	=> 'The product is already in your wishlist!',
				),
			),
			'sbwl_stylecolor_section' => array(
				array(
					'name'		=> 'snc_add_wishlist_style',
					'label'		=> __('Style of "Add to wishlist"', 'sb-woo-whislist'),
					'desc'		=> __('Choose if you want to show a textual "Add to wishlist" link or a button', 'sb-woo-whislist'),
					'type'		=> 'select',
					'default'	=> 'wl-btn-link',
					'options'	=> array(
						'wwl-btn-link' => 'Texual Link',
						'wwl-btn-btn' => 'Button with theme style',
						'wwl-hide' => 'Don\'t show button',
					),
				),
				array(
					'name'		=> 's&c_add_wishlist_icon',
					'label'		=> __('"Add to wishlist" icon', 'sb-woo-whislist'),
					'desc'		=> __('Select an icon for the "Add to wishlist" button (optional)', 'sb-woo-whislist'),
					'type'		=> 'select',
					'default'	=> 'cart',
					'options'	=> array(
						'cart'	=> 'Cart',
						'heart'	=> 'Heart',
						'bell'	=> 'Bell',
					),
				),
				array(
					'name'		=> 's&c_added_wishlist_icon',
					'label'		=> __('"Added to wishlist" icon', 'sb-woo-whislist'),
					'desc'		=> __('Select an icon for the "Added to wishlist" button (optional)', 'sb-woo-whislist'),
					'type'		=> 'select',
					'default'	=> '1',
					'options'	=> array(
						'1'	=> 'Heart',
						'2'	=> 'Button with theme style',
						'3'	=> 'Button with custom style',
					),
				),
				array(
					'name'		=> 's&c_custom_css',
					'label'		=> __( 'Custom CSS', 'sb-woo-whislist' ),
					'desc'		=> __( 'Enter custom CSS to be applied to Wishlist elements (optional)', 'sb-woo-whislist' ),
					'type'		=> 'textarea',
				),
			),
			'woo_page_opt_section' => array(
				array(
					'name'		=> 'page_option_title',
					'label'		=> __('<h2 style="font-weight: 700;">All your wishlists</h2>', 'sb-woo-whislist'),
					'type'		=> 'html',
				),
				array(
					'name'		=> 'page_option_select',
					'label'		=> __('Wishlist page', 'sb-woo-whislist'),
					'desc'		=> __('Pick a page as the main Wishlist page; make sure you add the [wl_shortcode_page] shortcode into the page content', 'sb-woo-whislist'),
					'type'		=> 'select',
					'options'	=> $this->get_pages(),
				),
				array(
					'name'		=> 'page_option_detail_page',
					'label'		=> __('<h2 style="font-weight: 700;">Wishlist Detail Page</h2>', 'sb-woo-whislist'),
					'type'		=> 'html',
				),
				array(
					'name'		=> 'page_option_table_show',
					'label'		=> __('In wishlist table show'),
					'type'		=> 'multicheck',
					'default'	=> array('price' => 'price', 'stock' => 'stock', 'add_to_cart' => 'add_to_cart'),
					'options'	=> array(
						'variation'		=> ' Product variations selected by the user (example: size or color)',
						'price'			=> 'Product price',
						'stock'			=> 'Product stock (show if the product is available or not)',
						'add_to_cart' 	=> 'Add to cart option for each product',
						'remove_left'	=> 'Icon to remove the product from the wishlist - to the left of the product',
						'remove_right'	=> ' Button to remove the product from the wishlist - to the right of the product',
						'date'			=> 'Date on which the product was added to the wishlist',
					),
				),
				array(
					'name'		=> 'page_option_redirect',
					'label'		=> __( 'Redirect to cart', 'sb-woo-whislist' ),
					'desc'		=> __( 'Redirect users to the cart page when they add a product to the cart from the wishlist page', 'sb-woo-whislist' ),
					'default'	=> 'off',
					'type'		=> 'checkbox',
				),
				array(
					'name'		=> 'page_option_remove',
					'label'		=> __( 'Remove if added to the cart', 'sb-woo-whislist' ),
					'desc'		=> __( 'Remove the product from the wishlist after it has been added to the cart', 'sb-woo-whislist' ),
					'default'	=> 'off',
					'type'		=> 'checkbox',
				),
				array(
					'name'		=> 'page_option_social_share',
					'label'		=> __( 'Share wishlist', 'sb-woo-whislist' ),
					'desc'		=> __( 'Enable this option to let users share their wishlist on social media', 'sb-woo-whislist' ),
					'default'	=> 'off',
					'type'		=> 'checkbox',
				),
				array(
					'name'		=> 'page_option_share',
					'label'		=> __('In wishlist table show'),
					'type'		=> 'multicheck',
					// 'default'	=> array( 'facebook' => 'facebook', 'twitter' => 'twitter', 'pinterest' => 'pinterest',  ),
					'options'	=> array(
						'facebook'	=> ' Facebook',
						'twitter'	=> 'Twitter',
						'pinterest'	=> 'Pinterest',
						'email'		=> 'Share by email',
						'whatsapp'	=> 'Share on WhatsApp',
					),
				),
				array(
					'name'		=> 'page_option_share_title',
					'label'		=> __('Sharing title', 'sb-woo-whislist'),
					'desc'		=> __('Wishlist title used for sharing (only used on Twitter and Pinterest)', 'sb-woo-whislist'),
					'type'		=> 'text',
					'default'	=> 'My Wishlist',
				),
				array(
					'name'		=> 'page_option_share_link',
					'label'		=> __('Social image URL', 'sb-woo-whislist'),
					'desc'		=> __('It will be used to pin the wishlist on Pinterest." button', 'sb-woo-whislist'),
					'type'		=> 'url',
				),
				array(
					'name'		=> 'page_option_text_customization',
					'label'		=> __('<h2 style="font-weight: 700;">Text Customization</h2>', 'sb-woo-whislist'),
					'type'		=> 'html',
				),
				array(
					'name'		=> 'page_option_wishlist_name',
					'label'		=> __('Default wishlist name', 'sb-woo-whislist'),
					'desc'		=> __('Enter a name for the default wishlist. This is the wishlist that will be automatically generated for all users if they do not create any custom one', 'sb-woo-whislist'),
					'type'		=> 'text',
					'default'	=> 'My wishlist',
				),
				array(
					'name'		=> 'page_option_cart_name',
					'label'		=> __('"Add to cart" text', 'sb-woo-whislist'),
					'desc'		=> __('Enter a text for the "Add to cart" button', 'sb-woo-whislist'),
					'type'		=> 'text',
					'default'	=> 'Add to cart',
				),
			),
		);

		return $setting_fields;
	}

	/**
	 * Contains the code that will be rendered on admin page.
	 * 
	 * @package SbWl
	 * @since 1.0
	 */
	function plugin_page() {
		echo '<div class="wrap">';
		$this->settings->show_navigation();
		$this->settings->show_forms();
		echo '</div>';
	}
}