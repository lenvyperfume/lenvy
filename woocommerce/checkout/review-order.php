<?php
/**
 * Review order — compact product list + totals.
 *
 * Overrides woocommerce/checkout/review-order.php
 *
 * @package Lenvy
 * @version 5.2.0
 */

defined('ABSPATH') || exit();
?>

<!-- Product list -->
<div class="lenvy-review-items">

	<?php
	do_action('woocommerce_review_order_before_cart_contents');

	foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
		$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

		if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)):
			$product_permalink = $_product->is_visible() ? $_product->get_permalink($cart_item) : '';
		?>
		<div class="lenvy-review-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
			<div class="lenvy-review-item__thumb">
				<?php echo $_product->get_image('woocommerce_gallery_thumbnail'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="lenvy-review-item__info">
				<p class="lenvy-review-item__name">
					<?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)); ?>
					<span class="lenvy-review-item__qty">&times; <?php echo esc_html($cart_item['quantity']); ?></span>
				</p>
				<?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="lenvy-review-item__price">
				<?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
		<?php
		endif;
	}

	do_action('woocommerce_review_order_after_cart_contents');
	?>

</div>

<!-- Totals -->
<div class="lenvy-review-totals">

	<div class="lenvy-review-totals__row">
		<span><?php esc_html_e('Subtotaal', 'lenvy'); ?></span>
		<span><?php wc_cart_totals_subtotal_html(); ?></span>
	</div>

	<?php foreach (WC()->cart->get_coupons() as $code => $coupon): ?>
		<div class="lenvy-review-totals__row lenvy-review-totals__discount">
			<span><?php wc_cart_totals_coupon_label($coupon); ?></span>
			<span><?php wc_cart_totals_coupon_html($coupon); ?></span>
		</div>
	<?php endforeach; ?>

	<?php if (WC()->cart->needs_shipping()): ?>
		<?php
		$packages       = WC()->shipping()->get_packages();
		$chosen_methods = WC()->session->get('chosen_shipping_methods', []);
		$shipping_cost = false;
		foreach ($packages as $i => $package) {
			if (!empty($package['rates']) && !empty($chosen_methods[$i]) && isset($package['rates'][$chosen_methods[$i]])) {
				$shipping_cost = $package['rates'][$chosen_methods[$i]]->cost;
				break;
			}
		}
		?>
		<div class="lenvy-review-totals__row">
			<span><?php esc_html_e('Verzending', 'lenvy'); ?></span>
			<?php if (false !== $shipping_cost): ?>
				<span><?php echo (float) $shipping_cost > 0 ? wp_kses_post(wc_price($shipping_cost)) : esc_html__('Gratis', 'lenvy'); ?></span>
			<?php else: ?>
				<span class="text-xs text-neutral-400"><?php esc_html_e('Vul je adres in', 'lenvy'); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php foreach (WC()->cart->get_fees() as $fee): ?>
		<div class="lenvy-review-totals__row">
			<span><?php echo esc_html($fee->name); ?></span>
			<span><?php wc_cart_totals_fee_html($fee); ?></span>
		</div>
	<?php endforeach; ?>

	<?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()): ?>
		<?php if ('itemized' === get_option('woocommerce_tax_total_display')): ?>
			<?php foreach (WC()->cart->get_tax_totals() as $code => $tax): // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
				<div class="lenvy-review-totals__row">
					<span><?php echo esc_html($tax->label); ?></span>
					<span><?php echo wp_kses_post($tax->formatted_amount); ?></span>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<div class="lenvy-review-totals__row">
				<span><?php echo esc_html(WC()->countries->tax_or_vat()); ?></span>
				<span><?php wc_cart_totals_taxes_total_html(); ?></span>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php do_action('woocommerce_review_order_before_order_total'); ?>

	<div class="lenvy-review-totals__row lenvy-review-totals__total">
		<span><?php esc_html_e('Totaal', 'lenvy'); ?></span>
		<span><?php wc_cart_totals_order_total_html(); ?></span>
	</div>

	<?php do_action('woocommerce_review_order_after_order_total'); ?>

</div>
