<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
		<tr>
			<th class="product-name" colspan="2"><?php esc_html_e( 'Product', 'amely' ); ?></th>
			<th class="product-price"><?php esc_html_e( 'Price', 'amely' ); ?></th>
			<th class="product-quantity"><?php esc_html_e( 'Quantity', 'amely' ); ?></th>
			<th class="product-subtotal"><?php esc_html_e( 'Total', 'amely' ); ?></th>
			<th class="product-remove">&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product',
				$cart_item['data'],
				$cart_item,
				$cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id',
				$cart_item['product_id'],
				$cart_item,
				$cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible',
					true,
					$cart_item,
					$cart_item_key )
			) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink',
					$_product->is_visible() ? $_product->get_permalink( $cart_item ) : '',
					$cart_item,
					$cart_item_key );
				?>
				<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class',
					'cart_item',
					$cart_item,
					$cart_item_key ) ); ?>">

					<td class="product-thumbnail">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail',
							$_product->get_image(),
							$cart_item,
							$cart_item_key );

						if ( ! $product_permalink ) {
							echo( $thumbnail );
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
						}
						?>
					</td>

					<td class="product-name" data-title="<?php esc_html_e( 'Product', 'amely' ); ?>">
						<?php
						if ( ! $product_permalink ) {
							echo apply_filters( 'woocommerce_cart_item_name',
									$_product->get_title(),
									$cart_item,
									$cart_item_key ) . '&nbsp;';
						} else {
							echo apply_filters( 'woocommerce_cart_item_name',
								sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_title() ),
								$cart_item,
								$cart_item_key );
						}

						// Meta data
						echo wc_get_formatted_cart_item_data( $cart_item );

						// Backorder notification
						if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
							echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder',
									'amely' ) . '</p>';
						}
						?>
					</td>

					<td class="product-price" data-title="<?php esc_html_e( 'Price', 'amely' ); ?>">
						<?php
						echo apply_filters( 'woocommerce_cart_item_price',
							WC()->cart->get_product_price( $_product ),
							$cart_item,
							$cart_item_key );
						?>
					</td>

					<td class="product-quantity" data-title="<?php esc_html_e( 'Quantity', 'amely' ); ?>">
						<?php
						if ( $_product->is_sold_individually() ) {
							$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />',
								$cart_item_key );
						} else {
							$product_quantity = woocommerce_quantity_input( array(
								'input_name'   => "cart[{$cart_item_key}][qty]",
								'input_value'  => $cart_item['quantity'],
								'max_value'    => $_product->get_max_purchase_quantity(),
								'min_value'    => '0',
								'product_name' => $_product->get_name(),
							),
								$_product,
								false );
						}

						echo apply_filters( 'woocommerce_cart_item_quantity',
							$product_quantity,
							$cart_item_key,
							$cart_item );
						?>
					</td>

					<td class="product-subtotal" data-title="<?php esc_html_e( 'Total', 'amely' ); ?>">
						<?php
						echo apply_filters( 'woocommerce_cart_item_subtotal',
							WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ),
							$cart_item,
							$cart_item_key );
						?>
					</td>

					<td class="product-remove">
						<?php
						echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
							'<a href="%s" class="remove" title="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
							esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
							__( 'Remove this item', 'amely' ),
							esc_attr( $product_id ),
							esc_attr( $cart_item_key ),
							esc_attr( $_product->get_sku() )
						), $cart_item_key );
						?>
					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_cart_contents' );

		do_action( 'woocommerce_after_cart_contents' ); ?>
		</tbody>
	</table>

	<div class="row cart-actions">
		<div class="col-md-9 col-lg-8">
			<?php if ( wc_coupons_enabled() ) { ?>
				<div class="coupon">

					<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'amely' ); ?></label>
					<input type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
					       placeholder="<?php esc_attr_e( 'Enter your coupon code', 'amely' ); ?>"/>
					<input type="submit" class="button" name="apply_coupon"
					       value="<?php esc_attr_e( 'Apply Coupon', 'amely' ); ?>"/>

					<?php do_action( 'woocommerce_cart_coupon' ); ?>
				</div>
			<?php } ?>
		</div>
		<div class="col-md-3 col-lg-4">
			<button type="submit" class="button" name="update_cart"><?php esc_html_e( 'Update Cart',
					'amely' ); ?></button>

			<?php do_action( 'woocommerce_cart_actions' ); ?>

			<?php wp_nonce_field( 'woocommerce-cart' ); ?>
		</div>
	</div>

	<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<div class="cart-collaterals">

	<?php
	/**
	 * Cart collaterals hook.
	 *
	 * @hooked woocommerce_cross_sell_display
	 * @hooked woocommerce_cart_totals - 10
	 */
	do_action( 'woocommerce_cart_collaterals' ); ?>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
