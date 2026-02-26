<?php
/**
 * Cart Page
 *
 * Two-column layout: item list (left) + sticky order summary (right).
 * All WooCommerce hooks are preserved for plugin compatibility.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Lenvy
 * @version 10.1.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="lenvy-container py-12 lg:py-16">

	<?php do_action( 'woocommerce_before_cart' ); ?>

	<!-- Page heading -->
	<div class="mb-8">
		<?php get_template_part( 'template-parts/components/breadcrumb' ); ?>
		<h1 class="mt-3 text-2xl font-serif italic text-neutral-900">
			<?php esc_html_e( 'Winkelwagen', 'lenvy' ); ?>
		</h1>
	</div>

	<div class="flex flex-col lg:flex-row gap-12 items-start">

		<!-- ── Left: cart items ───────────────────────────────────────────── -->
		<div class="flex-1 min-w-0">

			<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

				<?php do_action( 'woocommerce_before_cart_table' ); ?>

				<div class="divide-y divide-neutral-100">

					<?php do_action( 'woocommerce_before_cart_contents' ); ?>

					<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) : ?>
						<?php
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
						$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<div class="flex gap-4 py-5 <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

							<!-- Thumbnail -->
							<div class="cart-item-thumb w-20 h-24 shrink-0 bg-neutral-50 overflow-hidden">
								<?php
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
								if ( ! $product_permalink ) {
									echo $thumbnail; // PHPCS: XSS ok.
								} else {
									printf( '<a href="%s" class="block w-full h-full">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
								}
								?>
							</div>

							<!-- Product info + controls -->
							<div class="flex-1 min-w-0 flex flex-col justify-between gap-2">

								<div>
									<?php if ( ! $product_permalink ) : ?>
										<p class="text-sm font-medium text-neutral-900"><?php echo wp_kses_post( $product_name ); ?></p>
									<?php else : ?>
										<a href="<?php echo esc_url( $product_permalink ); ?>" class="text-sm font-medium text-neutral-900 hover:underline underline-offset-2">
											<?php echo wp_kses_post( $_product->get_name() ); ?>
										</a>
									<?php endif; ?>

									<?php do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key ); ?>
									<?php echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok. ?>

									<?php if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) : ?>
										<p class="text-xs text-neutral-500 mt-1"><?php esc_html_e( 'Available on backorder', 'woocommerce' ); ?></p>
									<?php endif; ?>
								</div>

								<!-- Price / Qty / Subtotal / Remove -->
								<div class="flex items-center flex-wrap gap-3">

									<!-- Unit price -->
									<span class="text-sm text-neutral-500">
										<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok. ?>
									</span>

									<!-- Quantity -->
									<?php
									if ( $_product->is_sold_individually() ) {
										$min_quantity = 1;
										$max_quantity = 1;
									} else {
										$min_quantity = 0;
										$max_quantity = $_product->get_max_purchase_quantity();
									}

									$product_quantity = woocommerce_quantity_input(
										[
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $max_quantity,
											'min_value'    => $min_quantity,
											'product_name' => $product_name,
										],
										$_product,
										false
									);

									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
									?>

									<!-- Subtotal -->
									<span class="ml-auto text-sm font-semibold text-neutral-900">
										<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok. ?>
									</span>

									<!-- Remove -->
									<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a role="button" href="%s" class="remove text-neutral-400 hover:text-neutral-900 transition-colors duration-150" aria-label="%s" data-product_id="%s" data-product_sku="%s">' .
											'<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M1 1l12 12M13 1L1 13"/></svg>' .
											'</a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											/* translators: %s is the product name */
											esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() )
										),
										$cart_item_key
									);
									?>

								</div><!-- /controls -->

							</div><!-- /product info -->

						</div><!-- /cart_item -->
						<?php endif; ?>
					<?php endforeach; ?>

					<?php do_action( 'woocommerce_cart_contents' ); ?>

				</div><!-- /divide-y -->

				<!-- Coupon + Update cart -->
				<div class="flex flex-wrap items-center gap-3 pt-5 border-t border-neutral-100 mt-1">

					<?php if ( wc_coupons_enabled() ) : ?>
						<div class="flex gap-2 flex-1 min-w-[200px]">
							<label for="coupon_code" class="sr-only"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>
							<input
								type="text"
								name="coupon_code"
								id="coupon_code"
								class="input-text flex-1 border border-neutral-300 px-3 py-2 text-sm placeholder:text-neutral-400 focus:outline-none focus:border-black"
								value=""
								placeholder="<?php esc_attr_e( 'Kortingscode', 'lenvy' ); ?>"
							>
							<button
								type="submit"
								class="button shrink-0 text-xs font-medium uppercase tracking-widest border border-neutral-900 text-neutral-900 px-4 py-2 hover:bg-neutral-900 hover:text-white transition-colors duration-150"
								name="apply_coupon"
								value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"
							><?php esc_html_e( 'Toepassen', 'lenvy' ); ?></button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php endif; ?>

					<button
						type="submit"
						class="button text-xs font-medium uppercase tracking-widest border border-neutral-200 text-neutral-500 px-4 py-2 hover:border-neutral-900 hover:text-neutral-900 transition-colors duration-150"
						name="update_cart"
						value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"
					><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

					<?php do_action( 'woocommerce_cart_actions' ); ?>
					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

				</div><!-- /coupon + update -->

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
				<?php do_action( 'woocommerce_after_cart_table' ); ?>

			</form><!-- /woocommerce-cart-form -->

		</div><!-- /left -->

		<!-- ── Right: order summary ───────────────────────────────────────── -->
		<div class="w-full lg:w-80 xl:w-96 shrink-0">
			<div class="lg:sticky lg:top-[calc(var(--header-height,68px)+2rem)]">
				<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
				<?php woocommerce_cart_totals(); ?>
			</div>
		</div><!-- /right -->

	</div><!-- /flex row -->

	<?php do_action( 'woocommerce_after_cart' ); ?>

</div><!-- /lenvy-container -->
