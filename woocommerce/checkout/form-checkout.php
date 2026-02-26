<?php
/**
 * Checkout Form — two-column: billing/shipping left, order review right.
 *
 * Overrides woocommerce/checkout/form-checkout.php
 *
 * @package Lenvy
 * @version 9.4.0
 */

defined('ABSPATH') || exit();

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
	echo '<div class="lenvy-container py-16 text-center">';
	echo '<p class="text-sm text-neutral-600">' . esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('Je moet ingelogd zijn om af te rekenen.', 'lenvy'))) . '</p>';
	echo '</div>';
	return;
}
?>

<div class="lenvy-container py-10 lg:py-16">

	<?php do_action('woocommerce_before_checkout_form', $checkout); ?>

	<!-- Page heading -->
	<div class="mb-8 lg:mb-12">
		<?php get_template_part('template-parts/components/breadcrumb'); ?>
		<h1 class="mt-3 text-2xl md:text-3xl font-serif italic text-neutral-900">
			<?php esc_html_e('Afrekenen', 'lenvy'); ?>
		</h1>
	</div>

	<form
		name="checkout"
		method="post"
		class="checkout woocommerce-checkout"
		action="<?php echo esc_url(wc_get_checkout_url()); ?>"
		enctype="multipart/form-data"
		aria-label="<?php echo esc_attr__('Checkout', 'woocommerce'); ?>"
	>

		<div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-10 lg:gap-16 items-start">

			<!-- ── Left: customer details ─────────────────────────────── -->
			<div class="min-w-0 lenvy-checkout-fields">

				<?php if ($checkout->get_checkout_fields()): ?>

					<?php do_action('woocommerce_checkout_before_customer_details'); ?>

					<div id="customer_details">
						<?php do_action('woocommerce_checkout_billing'); ?>
						<?php do_action('woocommerce_checkout_shipping'); ?>
					</div>

					<?php do_action('woocommerce_checkout_after_customer_details'); ?>

				<?php endif; ?>

			</div>

			<!-- ── Right: order review + payment ─────────────────────── -->
			<div class="lg:sticky lg:top-[calc(var(--header-height,68px)+2rem)]">
				<div class="lenvy-checkout-summary">

					<?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

					<h2 class="lenvy-checkout-summary__heading">
						<?php esc_html_e('Jouw bestelling', 'lenvy'); ?>
					</h2>

					<?php do_action('woocommerce_checkout_before_order_review'); ?>

					<div id="order_review" class="woocommerce-checkout-review-order">
						<?php do_action('woocommerce_checkout_order_review'); ?>
					</div>

					<?php do_action('woocommerce_checkout_after_order_review'); ?>

				</div>
			</div>

		</div>

	</form>

	<?php do_action('woocommerce_after_checkout_form', $checkout); ?>

</div>
