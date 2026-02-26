<?php
/**
 * Thank-you / order received page.
 *
 * Overrides woocommerce/checkout/thankyou.php
 *
 * @package Lenvy
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined('ABSPATH') || exit();
?>

<div class="lenvy-container py-12 lg:py-20">

	<div class="woocommerce-order max-w-2xl mx-auto">

		<?php if ($order): ?>

			<?php do_action('woocommerce_before_thankyou', $order->get_id()); ?>

			<?php if ($order->has_status('failed')): ?>

				<div class="text-center mb-10">
					<h1 class="text-2xl md:text-3xl font-serif italic text-neutral-900 mb-3">
						<?php esc_html_e('Betaling mislukt', 'lenvy'); ?>
					</h1>
					<p class="text-sm text-neutral-600 leading-relaxed max-w-md mx-auto">
						<?php esc_html_e('Helaas kon je bestelling niet worden verwerkt. Probeer het opnieuw of neem contact met ons op.', 'lenvy'); ?>
					</p>
				</div>

				<div class="flex justify-center gap-3">
					<a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="inline-flex items-center justify-center h-12 px-8 text-[11px] font-medium uppercase tracking-widest bg-primary text-black hover:bg-primary-hover transition-colors duration-150">
						<?php esc_html_e('Opnieuw betalen', 'lenvy'); ?>
					</a>
					<?php if (is_user_logged_in()): ?>
						<a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="inline-flex items-center justify-center h-12 px-8 text-[11px] font-medium uppercase tracking-widest border border-neutral-200 text-neutral-900 hover:border-neutral-900 transition-colors duration-150">
							<?php esc_html_e('Mijn account', 'lenvy'); ?>
						</a>
					<?php endif; ?>
				</div>

			<?php else: ?>

				<!-- Success heading -->
				<div class="text-center mb-12">
					<div class="mb-5 text-neutral-300">
						<svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.25" class="mx-auto" aria-hidden="true">
							<circle cx="24" cy="24" r="20"/>
							<path d="M16 24l5 5 11-11" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>
					<h1 class="text-2xl md:text-3xl font-serif italic text-neutral-900 mb-3">
						<?php esc_html_e('Bedankt voor je bestelling', 'lenvy'); ?>
					</h1>
					<p class="text-sm text-neutral-500">
						<?php esc_html_e('We hebben je bestelling ontvangen en gaan deze zo snel mogelijk verwerken.', 'lenvy'); ?>
					</p>
				</div>

				<!-- Order details -->
				<div class="lenvy-thankyou-details">

					<div class="lenvy-thankyou-row">
						<span><?php esc_html_e('Bestelnummer', 'lenvy'); ?></span>
						<strong><?php echo esc_html($order->get_order_number()); ?></strong>
					</div>

					<div class="lenvy-thankyou-row">
						<span><?php esc_html_e('Datum', 'lenvy'); ?></span>
						<strong><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></strong>
					</div>

					<?php if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email()): ?>
					<div class="lenvy-thankyou-row">
						<span><?php esc_html_e('E-mail', 'lenvy'); ?></span>
						<strong><?php echo esc_html($order->get_billing_email()); ?></strong>
					</div>
					<?php endif; ?>

					<div class="lenvy-thankyou-row">
						<span><?php esc_html_e('Totaal', 'lenvy'); ?></span>
						<strong><?php echo wp_kses_post($order->get_formatted_order_total()); ?></strong>
					</div>

					<?php if ($order->get_payment_method_title()): ?>
					<div class="lenvy-thankyou-row">
						<span><?php esc_html_e('Betaalmethode', 'lenvy'); ?></span>
						<strong><?php echo wp_kses_post($order->get_payment_method_title()); ?></strong>
					</div>
					<?php endif; ?>

				</div>

				<!-- Continue shopping -->
				<div class="text-center mt-10">
					<?php if (wc_get_page_id('shop') > 0): ?>
						<a
							href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"
							class="inline-flex items-center justify-center h-12 px-10 text-[11px] font-medium uppercase tracking-widest bg-primary text-black hover:bg-primary-hover transition-colors duration-150"
						>
							<?php esc_html_e('Verder winkelen', 'lenvy'); ?>
						</a>
					<?php endif; ?>
				</div>

			<?php endif; ?>

			<?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
			<?php do_action('woocommerce_thankyou', $order->get_id()); ?>

		<?php else: ?>

			<div class="text-center">
				<h1 class="text-2xl md:text-3xl font-serif italic text-neutral-900 mb-3">
					<?php esc_html_e('Bestelling ontvangen', 'lenvy'); ?>
				</h1>
				<p class="text-sm text-neutral-500">
					<?php esc_html_e('Bedankt. Je bestelling is ontvangen.', 'lenvy'); ?>
				</p>
			</div>

		<?php endif; ?>

	</div>

</div>
