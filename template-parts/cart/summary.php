<?php
/**
 * Cart page — sticky summary column.
 *
 * Free-shipping bar, promo input, totals, grand total + CTA, payment options,
 * trust block. All numeric values are placeholders that get re-computed by
 * cart-page.js on every cart mutation.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$cart = (array) ($args['cart'] ?? []);
$threshold = (float) ($cart['free_shipping_threshold'] ?? 50);
$shipping  = (float) ($cart['shipping_cost'] ?? 4.95);
$vat_rate  = (float) ($cart['vat_rate'] ?? 0.21);

// Initial totals — computed server-side so the page paints something
// sensible before the JS rehydrates.
$items    = (array) ($cart['items'] ?? []);
$subtotal = 0.0;
$count    = 0;
foreach ($items as $i) {
	$subtotal += (float) ($i['priceValue'] ?? 0) * (int) ($i['qty'] ?? 1);
	$count    += (int) ($i['qty'] ?? 1);
}
$ship_now = $subtotal >= $threshold ? 0.0 : $shipping;
$grand    = $subtotal + $ship_now;
$btw      = ($subtotal * $vat_rate) / (1 + $vat_rate);

$eur = static fn($n) => '€ ' . number_format_i18n($n, 2);

$trust               = (array) ($cart['trust'] ?? []);
$payment_methods_src = get_template_directory_uri() . '/assets/icons/payments/payment-methods.svg';

$trust_icons = [
	'shield' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><path d="M12 2 4 5v6c0 5 3.5 9.5 8 11 4.5-1.5 8-6 8-11V5l-8-3z"/><path d="m9 12 2 2 4-4"/></svg>',
	'truck'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
	'return' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>',
	'chat'   => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>',
];
?>

<aside class="lenvy-cart__summary-col">
	<section class="lenvy-cart-summary" data-cart-summary aria-label="<?php esc_attr_e('Bestellingsoverzicht', 'lenvy'); ?>">

		<h3 class="lenvy-cart-summary__head"><?php esc_html_e('Overzicht', 'lenvy'); ?></h3>

		<!-- Free-shipping progress bar (filled by JS) -->
		<div class="lenvy-cart-ship" data-cart-ship-bar data-threshold="<?php echo esc_attr($threshold); ?>"></div>

		<!-- Promo code -->
		<div class="lenvy-cart-promo" data-cart-promo>
			<input
				type="text"
				class="lenvy-cart-promo__input"
				placeholder="<?php esc_attr_e('Kortingscode', 'lenvy'); ?>"
				data-cart-promo-input
				aria-label="<?php esc_attr_e('Kortingscode', 'lenvy'); ?>"
			>
			<button type="button" class="lenvy-cart-promo__btn" data-cart-promo-btn>
				<?php esc_html_e('Toepassen', 'lenvy'); ?>
			</button>
		</div>

		<!-- Totals -->
		<div class="lenvy-cart-totals" data-cart-totals>
			<div class="lenvy-cart-totals__line">
				<span><?php echo esc_html(sprintf(_n('Subtotaal (%d artikel)', 'Subtotaal (%d artikelen)', $count, 'lenvy'), $count)); ?></span>
				<span class="v"><?php echo esc_html($eur($subtotal)); ?></span>
			</div>
			<div class="lenvy-cart-totals__line">
				<span><?php esc_html_e('Verzending', 'lenvy'); ?></span>
				<span class="v">
					<?php echo $ship_now === 0.0 ? esc_html__('Gratis', 'lenvy') : esc_html($eur($ship_now)); ?>
				</span>
			</div>
			<div class="lenvy-cart-totals__line">
				<span><?php esc_html_e('BTW (21%)', 'lenvy'); ?></span>
				<span class="v"><?php echo esc_html($eur($btw)); ?></span>
			</div>
		</div>

		<div class="lenvy-cart-grand">
			<span class="lenvy-cart-grand__lbl"><?php esc_html_e('Totaal', 'lenvy'); ?></span>
			<span class="lenvy-cart-grand__v" data-cart-grand>
				<?php echo esc_html($eur($grand)); ?>
				<small><?php esc_html_e('incl. btw', 'lenvy'); ?></small>
			</span>
		</div>

		<?php
		// Logged-in users go straight to checkout; guests land on the
		// /afrekenen/inloggen/ choice page first (login / register /
		// continue as guest) and proceed from there.
		if (is_user_logged_in()) {
			$cta_url = function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/afrekenen/');
		} else {
			$cta_url = function_exists('lenvy_checkout_login_url') ? lenvy_checkout_login_url() : home_url('/afrekenen/inloggen/');
		}
		?>
		<a href="<?php echo esc_url($cta_url); ?>" class="lenvy-cart-summary__cta">
			<?php esc_html_e('Naar betalen', 'lenvy'); ?>
		</a>

		<div class="lenvy-cart-pay">
			<img
				src="<?php echo esc_url($payment_methods_src); ?>"
				alt="<?php esc_attr_e('iDEAL, Maestro, Mastercard, Visa', 'lenvy'); ?>"
				width="168"
				height="26"
				loading="lazy"
				class="lenvy-cart-pay__img"
			/>
		</div>

	</section>

	<?php if ($trust): ?>
		<section class="lenvy-cart-trust" aria-label="<?php esc_attr_e('Garanties', 'lenvy'); ?>">
			<?php foreach ($trust as $row):
				$icon = (string) ($row['icon'] ?? '');
				$svg  = $trust_icons[$icon] ?? '';
			?>
				<div class="lenvy-cart-trust__row">
					<?php echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — static SVG ?>
					<span>
						<span class="t1"><?php echo esc_html($row['title']); ?></span>
						<span class="t2"><?php echo esc_html($row['desc']); ?></span>
					</span>
				</div>
			<?php endforeach; ?>
		</section>
	<?php endif; ?>
</aside>
