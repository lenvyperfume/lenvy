<?php
/**
 * Cart totals — clean summary panel with collapsible coupon.
 *
 * Overrides woocommerce/cart/cart-totals.php
 *
 * @package Lenvy
 * @version 2.3.6
 */

defined('ABSPATH') || exit();
?>

<div class="cart_totals <?php echo (WC()->customer->has_calculated_shipping()) ? 'calculated_shipping' : ''; ?>">

	<?php do_action('woocommerce_before_cart_totals'); ?>

	<?php
	// Free shipping progress bar.
	$free_shipping_min = 0;
	$shipping_zones = WC_Shipping_Zones::get_zones();
	foreach ($shipping_zones as $zone) {
		foreach ($zone['shipping_methods'] as $method) {
			if ('free_shipping' === $method->id && 'yes' === $method->enabled) {
				$free_shipping_min = (float) $method->get_option('min_amount', 0);
				break 2;
			}
		}
	}

	if ($free_shipping_min > 0):
		$cart_total  = (float) WC()->cart->get_displayed_subtotal();
		$remaining   = max(0, $free_shipping_min - $cart_total);
		$progress    = min(100, ($cart_total / $free_shipping_min) * 100);
	?>
	<div class="mb-5">
		<?php if ($remaining > 0): ?>
		<p class="text-xs text-neutral-600 mb-2">
			<?php printf(
				/* translators: %s: remaining amount for free shipping */
				esc_html__('Nog %s voor gratis verzending', 'lenvy'),
				wp_kses_post(wc_price($remaining))
			); ?>
		</p>
		<?php else: ?>
		<p class="text-xs text-neutral-600 mb-2">
			<?php lenvy_icon('check', 'inline text-green-600 -mt-0.5', 'xs'); ?>
			<?php esc_html_e('Je komt in aanmerking voor gratis verzending!', 'lenvy'); ?>
		</p>
		<?php endif; ?>
		<div class="w-full h-1.5 bg-neutral-100 rounded-full overflow-hidden">
			<div class="h-full bg-primary rounded-full transition-all duration-500" style="width:<?php echo esc_attr($progress); ?>%"></div>
		</div>
	</div>
	<?php endif; ?>

	<h2><?php esc_html_e('Overzicht', 'lenvy'); ?></h2>

	<div class="lenvy-cart-summary-rows">

		<div class="lenvy-summary-row">
			<span><?php esc_html_e('Subtotaal', 'lenvy'); ?></span>
			<span><?php wc_cart_totals_subtotal_html(); ?></span>
		</div>

		<?php foreach (WC()->cart->get_coupons() as $code => $coupon): ?>
			<div class="lenvy-summary-row lenvy-summary-discount">
				<span><?php wc_cart_totals_coupon_label($coupon); ?></span>
				<span><?php wc_cart_totals_coupon_html($coupon); ?></span>
			</div>
		<?php endforeach; ?>

		<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()): ?>
			<?php do_action('woocommerce_cart_totals_before_shipping'); ?>
			<?php wc_cart_totals_shipping_html(); ?>
			<?php do_action('woocommerce_cart_totals_after_shipping'); ?>
		<?php elseif (WC()->cart->needs_shipping() && 'yes' === get_option('woocommerce_enable_shipping_calc')): ?>
			<div class="lenvy-summary-row">
				<span><?php esc_html_e('Verzending', 'lenvy'); ?></span>
				<span><?php woocommerce_shipping_calculator(); ?></span>
			</div>
		<?php endif; ?>

		<?php foreach (WC()->cart->get_fees() as $fee): ?>
			<div class="lenvy-summary-row">
				<span><?php echo esc_html($fee->name); ?></span>
				<span><?php wc_cart_totals_fee_html($fee); ?></span>
			</div>
		<?php endforeach; ?>

		<?php
		if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if (WC()->customer->is_customer_outside_base() && !WC()->customer->has_calculated_shipping()) {
				$estimated_text = sprintf(' <small>' . esc_html__('(geschat voor %s)', 'lenvy') . '</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]]);
			}

			if ('itemized' === get_option('woocommerce_tax_total_display')) {
				foreach (WC()->cart->get_tax_totals() as $code => $tax) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					?>
					<div class="lenvy-summary-row">
						<span><?php echo esc_html($tax->label) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span><?php echo wp_kses_post($tax->formatted_amount); ?></span>
					</div>
					<?php
				}
			} else {
				?>
				<div class="lenvy-summary-row">
					<span><?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span><?php wc_cart_totals_taxes_total_html(); ?></span>
				</div>
				<?php
			}
		}
		?>

		<?php do_action('woocommerce_cart_totals_before_order_total'); ?>

		<div class="lenvy-summary-row lenvy-summary-total">
			<span><?php esc_html_e('Totaal', 'lenvy'); ?></span>
			<span><?php wc_cart_totals_order_total_html(); ?></span>
		</div>

		<?php do_action('woocommerce_cart_totals_after_order_total'); ?>

	</div>

	<!-- Collapsible coupon -->
	<?php if (wc_coupons_enabled()): ?>
	<div class="lenvy-coupon-toggle" data-filter-accordion>
		<button
			type="button"
			class="lenvy-coupon-trigger"
			data-filter-accordion-toggle
			aria-expanded="false"
			aria-controls="panel-cart-coupon"
		>
			<?php esc_html_e('Heb je een kortingscode?', 'lenvy'); ?>
		</button>
		<div id="panel-cart-coupon" data-filter-accordion-panel style="display:none;">
			<form class="lenvy-coupon-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
				<div class="flex gap-2">
					<label for="sidebar_coupon_code" class="sr-only"><?php esc_html_e('Kortingscode', 'lenvy'); ?></label>
					<input
						type="text"
						name="coupon_code"
						id="sidebar_coupon_code"
						class="lenvy-coupon-input"
						value=""
						placeholder="<?php esc_attr_e('Code invoeren', 'lenvy'); ?>"
					>
					<button
						type="submit"
						class="lenvy-coupon-btn"
						name="apply_coupon"
						value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"
					><?php esc_html_e('Toepassen', 'lenvy'); ?></button>
				</div>
			</form>
		</div>
	</div>
	<?php endif; ?>

	<div class="wc-proceed-to-checkout">
		<?php do_action('woocommerce_proceed_to_checkout'); ?>
	</div>

	<?php do_action('woocommerce_after_cart_totals'); ?>

</div>
