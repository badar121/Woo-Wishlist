<?php
/**
 * 
 * @package  Woo Wishlist
 */


/**
* Get the value of a settings field.
*
* @param string $option settings field name
* @param string $section the section name this field belongs to
* @param string $default default text if it's not found
* @return mixed
*/
function sbwl_get_option( $option, $section, $default = '' ) {
	$options = get_option( $section );
	if ( isset( $options[$option] ) ) {
	return $options[$option];
	}
	return $default;
}


/**
 * This function will run when "Add to Cart" is clicked. It will check the URL parameters
 * and will perform functionality according parameter's values.
 * 
 * @since 1.0
 * @package SbWl 
 */
function sbwl_add_to_cart ( $cart_item_key, $product_id ) {
	if ( 'on' === $_GET['remove-from-wishlist'] ) {
		$sbwl_user_wl	= get_user_meta( get_current_user_id(), 'wishlist', true );
		$sbwl_item		= array_search( $product_id, $sbwl_user_wl );

		if ( ! empty( $sbwl_item ) || 0 === $sbwl_item  ) {
			unset( $sbwl_user_wl[$sbwl_item] );
			$sbwl_user_wl = array_values( $sbwl_user_wl );
			update_user_meta( get_current_user_id(), 'wishlist', $sbwl_user_wl );
		}
	}

	if ( 'on' === $_GET['redirect-to-cart'] ) {
		wp_safe_redirect( wc_get_cart_url() );
	}
}
add_action( 'woocommerce_add_to_cart', 'sbwl_add_to_cart', 10, 2 );


/**
 * Renders the wishlist page content and is hooked into the add_shortcode() hook.
 * 
 * @since 1.0
 * @package SbWl 
 */
function wwl_wishlist_shortcode() {
	ob_start();
	$sbwl_user_wl		= get_user_meta( get_current_user_id(), 'wishlist', true );
	$sbwl_table_opts	= sbwl_get_option( 'page_option_table_show', 'woo_page_opt_section' );

	if ( empty( $sbwl_user_wl ) ) {
		echo '<h2>Wishlist is empty.</h2>';
		return;
	}
	?>
	<table class="wwl-table">
		<tr class="wwl-table-titles">
		<?php if ( !empty( $sbwl_table_opts['remove_left'] ) ) : ?>
			<td>Remove</td>
		<?php endif; ?>

			<td>Image</td>
			<td>Name</td>

		<?php if ( !empty( $sbwl_table_opts['price'] ) ) : ?>
			<td>Price</td>
		<?php endif; ?>

		<?php if ( !empty( $sbwl_table_opts['stock'] ) ) : ?>
			<td>Stock</td>
		<?php endif; ?>

		<?php if ( !empty( $sbwl_table_opts['remove_right'] ) ) : ?>
			<td>Remove</td>
		<?php endif; ?>

		<?php if ( !empty( $sbwl_table_opts['add_to_cart'] ) ) : ?>
			<td>Add to Cart</td>
		<?php endif; ?>
		</tr>

		<?php 
			foreach ( $sbwl_user_wl as $wl_item ) :
				$sbwl_product = wc_get_product( $wl_item );
		?>

		<tr id="<?php esc_attr_e( $sbwl_product->get_id() ); ?>" >
		<?php if ( !empty( $sbwl_table_opts['remove_left'] ) ) : ?>
			<td>X</td>
		<?php endif; ?>
		
			<td><?php echo $sbwl_product->get_image(); ?></td>

			<td><?php echo $sbwl_product->get_name(); ?></td>
		
		<?php if ( !empty( $sbwl_table_opts['price'] ) ) : ?>
			<td><?php echo $sbwl_product->get_price(); ?></td>
		<?php endif; ?>
		
		<?php if ( !empty( $sbwl_table_opts['stock'] ) ) : ?>
			<td><?php echo $sbwl_product->get_stock_status(); ?></td>
		<?php endif; ?>

		<?php if ( !empty( $sbwl_table_opts['remove_right'] ) ) :
		_e('<td><a class="wwl-rfc wwl-rfc-btn" data-product='. esc_attr( $sbwl_product->get_id() ) .' href="">Remove</a></td>');
		?>

		<?php endif; ?>
		
		<?php if ( !empty( $sbwl_table_opts['add_to_cart'] ) ) : ?>
			<?php if ( 'simple' == $sbwl_product->get_type() ) : ?>
				<td>
				<?php 
					echo '<a class="wwl-atc" href="'. $sbwl_product->add_to_cart_url() .'&redirect-to-cart='. sbwl_get_option( 'page_option_redirect', 'woo_page_opt_section' ) .'&remove-from-wishlist='. sbwl_get_option( 'page_option_remove', 'woo_page_opt_section' ) .'">Add to Cart</a>';
				?>
				</td>
			<?php endif; ?>

			<?php if ( 'external' == $sbwl_product->get_type() ) :
				$url = $sbwl_product->product_url;
			?>
				<td>
				<?php 
					echo '<a class="wwl-atc" href="'. $url .'?remove-from-wishlist='. sbwl_get_option( 'page_option_remove', 'woo_page_opt_section' ) .'">Add to Cart</a>';
				?>
				</td>
			<?php endif; ?>

			<?php if ( 'variable' == $sbwl_product->get_type() || 'grouped' == $sbwl_product->get_type() ) : 
				$url = get_permalink( $sbwl_product->get_id() );	
			?>
				<td>
				<?php 
					echo '<a class="wwl-atc" href="'. $url .'?redirect-to-cart='. sbwl_get_option( 'page_option_redirect', 'woo_page_opt_section' ) .'&remove-from-wishlist='. sbwl_get_option( 'page_option_remove', 'woo_page_opt_section' ) .'">Add to Cart</a>';
				?>
				</td>
			<?php endif; ?>
		<?php endif; ?>
		</tr>

		<?php endforeach; ?>

	</table>
	<?php
	return ob_get_clean();
}
add_shortcode( 'wwl_wishlist_shortcode', 'wwl_wishlist_shortcode' );