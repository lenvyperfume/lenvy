<?php
/**
 * Cart Page — two-column layout with sticky summary.
 *
 * Overrides woocommerce/cart/cart.php
 *
 * @package Lenvy
 * @version 10.1.0
 */

defined('ABSPATH') || exit();
?>

<div class="lenvy-container py-10 lg:py-16">

	<?php do_action('woocommerce_before_cart'); ?>

	<!-- Page heading -->
	<div class="mb-8 lg:mb-12">
		<?php get_template_part('template-parts/components/breadcrumb'); ?>
		<h1 class="mt-3 text-2xl md:text-3xl font-serif italic text-neutral-900">
			<?php esc_html_e('Winkelwagen', 'lenvy'); ?>
		</h1>
	</div>

	<div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-10 lg:gap-16 items-start">

		<!-- ── Left: cart items ───────────────────────────────────────── -->
		<div class="min-w-0">

			<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

				<?php do_action('woocommerce_before_cart_table'); ?>

				<?php do_action('woocommerce_before_cart_contents'); ?>

				<?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item): ?>
					<?php
					$_product     = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
					$product_id   = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
					$product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);

					if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)):
						$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);

						// Brand name.
						$brand_terms = get_the_terms($product_id, 'product_brand');
						$brand_name  = ($brand_terms && !is_wp_error($brand_terms)) ? $brand_terms[0]->name : '';

						$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

						if ($_product->is_sold_individually()) {
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
					?>

					<div class="lenvy-cart-row relative group border-b border-neutral-100 <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

						<!-- Remove: appears on hover, top-right -->
						<?php
						echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							'woocommerce_cart_item_remove_link',
							sprintf(
								'<a role="button" href="%s" class="lenvy-cart-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
								esc_url(wc_get_cart_remove_url($cart_item_key)),
								esc_attr(sprintf(__('Verwijder %s uit winkelwagen', 'lenvy'), wp_strip_all_tags($product_name))),
								esc_attr($product_id),
								esc_attr($_product->get_sku())
							),
							$cart_item_key
						);
						?>

						<div class="flex gap-5 py-6">

							<!-- Thumbnail -->
							<div class="cart-item-thumb w-[100px] shrink-0 overflow-hidden aspect-[3/4]">
								<?php
								if (!$product_permalink) {
									echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								} else {
									printf('<a href="%s" class="block w-full h-full">%s</a>', esc_url($product_permalink), $thumbnail); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
								?>
							</div>

							<!-- Info -->
							<div class="flex-1 min-w-0 flex flex-col justify-between">

								<div>
									<?php if ($brand_name): ?>
									<p class="text-[11px] font-medium uppercase tracking-[0.08em] text-neutral-400 mb-0.5">
										<?php echo esc_html($brand_name); ?>
									</p>
									<?php endif; ?>

									<?php if (!$product_permalink): ?>
										<p class="text-sm text-neutral-900 leading-snug"><?php echo wp_kses_post($product_name); ?></p>
									<?php else: ?>
										<a href="<?php echo esc_url($product_permalink); ?>" class="text-sm text-neutral-900 leading-snug hover:underline underline-offset-2 block">
											<?php echo wp_kses_post($_product->get_name()); ?>
										</a>
									<?php endif; ?>

									<?php do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key); ?>
									<?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

									<p class="text-[13px] text-neutral-500 mt-1">
										<?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</p>

									<?php if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])): ?>
										<p class="text-xs text-neutral-400 mt-0.5"><?php esc_html_e('Beschikbaar via backorder', 'lenvy'); ?></p>
									<?php endif; ?>
								</div>

								<!-- Qty + Subtotal -->
								<div class="flex items-center justify-between mt-4">
									<div class="shrink-0">
										<?php echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
									<span class="text-sm font-medium text-neutral-900">
										<?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</span>
								</div>

							</div>

						</div>

					</div>

					<?php endif; ?>
				<?php endforeach; ?>

				<?php do_action('woocommerce_cart_contents'); ?>

				<!-- Hidden: update button (triggered automatically by JS on qty change) + nonce -->
				<div class="sr-only" aria-hidden="true">
					<button
						type="submit"
						name="update_cart"
						value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"
					><?php esc_html_e('Update cart', 'woocommerce'); ?></button>
					<?php do_action('woocommerce_cart_actions'); ?>
					<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
				</div>

				<?php do_action('woocommerce_after_cart_contents'); ?>
				<?php do_action('woocommerce_after_cart_table'); ?>

			</form>

		</div>

		<!-- ── Right: order summary ───────────────────────────────────── -->
		<div class="lg:sticky lg:top-[calc(var(--header-height,68px)+2rem)]">
			<?php do_action('woocommerce_before_cart_collaterals'); ?>
			<?php woocommerce_cart_totals(); ?>
		</div>

	</div>

	<?php do_action('woocommerce_after_cart'); ?>

</div>
