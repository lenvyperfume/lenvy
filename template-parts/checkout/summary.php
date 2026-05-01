<?php
/**
 * Checkout — sticky order summary (items · promo · totals · trust).
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$cart      = (array) ($args['cart'] ?? []);
$items     = (array) ($cart['items'] ?? []);
$vat_rate  = (float) ($cart['vat_rate'] ?? 0.21);
$threshold = (float) ($cart['free_shipping_threshold'] ?? 50);
$shipping  = (float) ($cart['shipping_cost'] ?? 0);
$trust     = (array) ($cart['trust'] ?? []);

$subtotal = 0.0;
foreach ($items as $i) {
	$subtotal += (float) ($i['priceValue'] ?? 0) * (int) ($i['qty'] ?? 1);
}
$ship_now = $subtotal >= $threshold ? 0.0 : $shipping;
$grand    = $subtotal + $ship_now;
$btw      = ($grand * $vat_rate) / (1 + $vat_rate);

$eur = static fn($n) => '€ ' . number_format_i18n((float) $n, 2);

$cart_url = function_exists('lenvy_placeholder_cart_url') ? lenvy_placeholder_cart_url() : '#';

$trust_icons = [
	'shield' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><path d="M12 2 4 5v6c0 5 3.5 9.5 8 11 4.5-1.5 8-6 8-11V5l-8-3z"/><path d="m9 12 2 2 4-4"/></svg>',
	'truck'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
	'return' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>',
];
?>

<aside class="lenvy-checkout__summary-col">
	<section
		class="lenvy-checkout__summary"
		data-checkout-summary
		data-subtotal="<?php echo esc_attr((string) $subtotal); ?>"
		data-vat-rate="<?php echo esc_attr((string) $vat_rate); ?>"
	>
		<div class="lenvy-checkout__summary-head">
			<h3><?php esc_html_e('Jouw bestelling', 'lenvy'); ?></h3>
			<a href="<?php echo esc_url($cart_url); ?>"><?php esc_html_e('Wijzig', 'lenvy'); ?></a>
		</div>

		<div class="lenvy-checkout__summary-items">
			<?php foreach ($items as $item):
				$brand   = (string) ($item['brand']   ?? '');
				$name    = (string) ($item['name']    ?? '');
				$variant = (string) ($item['variant'] ?? '');
				$price   = (float)  ($item['priceValue'] ?? 0);
				$qty     = (int)    ($item['qty']     ?? 1);
				$vclass  = (string) ($item['variant_class'] ?? 'v1');
				$line    = $price * $qty;
			?>
				<div class="lenvy-checkout__sum-item">
					<div class="lenvy-checkout__sum-img lenvy-checkout__sum-img--<?php echo esc_attr($vclass); ?>">
						<span class="lenvy-checkout__sum-qty"><?php echo esc_html((string) $qty); ?></span>
						<span class="lenvy-checkout__sum-cap"></span>
						<span class="lenvy-checkout__sum-bottle"></span>
					</div>
					<div class="lenvy-checkout__sum-body">
						<?php if ($brand): ?>
							<span class="lenvy-checkout__sum-brand"><?php echo esc_html($brand); ?></span>
						<?php endif; ?>
						<span class="lenvy-checkout__sum-name"><?php echo esc_html($name); ?></span>
						<?php if ($variant): ?>
							<span class="lenvy-checkout__sum-variant"><?php echo esc_html($variant); ?></span>
						<?php endif; ?>
					</div>
					<span class="lenvy-checkout__sum-price"><?php echo esc_html($eur($line)); ?></span>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="lenvy-checkout__promo">
			<div class="lenvy-checkout__promo-row" data-promo-row>
				<input
					type="text"
					placeholder="<?php esc_attr_e('Kortingscode', 'lenvy'); ?>"
					data-promo-input
					aria-label="<?php esc_attr_e('Kortingscode', 'lenvy'); ?>"
				/>
				<button type="button" data-promo-btn><?php esc_html_e('Toepassen', 'lenvy'); ?></button>
			</div>
		</div>

		<div class="lenvy-checkout__totals" data-totals>
			<div class="lenvy-checkout__totals-line">
				<span><?php esc_html_e('Subtotaal', 'lenvy'); ?></span>
				<span class="v"><?php echo esc_html($eur($subtotal)); ?></span>
			</div>
			<div class="lenvy-checkout__totals-line">
				<span><?php esc_html_e('Verzending', 'lenvy'); ?></span>
				<span class="v lenvy-checkout__totals-free">
					<?php echo $ship_now === 0.0 ? esc_html__('Gratis', 'lenvy') : esc_html($eur($ship_now)); ?>
				</span>
			</div>
			<div class="lenvy-checkout__totals-line">
				<span><?php esc_html_e('BTW (21%)', 'lenvy'); ?></span>
				<span class="v"><?php echo esc_html($eur($btw)); ?></span>
			</div>
		</div>

		<div class="lenvy-checkout__grand">
			<span class="lenvy-checkout__grand-lbl"><?php esc_html_e('Te betalen', 'lenvy'); ?></span>
			<span class="lenvy-checkout__grand-v" data-grand>
				<?php echo esc_html($eur($grand)); ?>
				<small><?php esc_html_e('incl. btw', 'lenvy'); ?></small>
			</span>
		</div>
	</section>

	<?php if ($trust): ?>
		<section class="lenvy-checkout__trust" aria-label="<?php esc_attr_e('Garanties', 'lenvy'); ?>">
			<?php foreach ($trust as $row):
				$icon = (string) ($row['icon'] ?? '');
				$svg  = $trust_icons[$icon] ?? '';
			?>
				<div class="lenvy-checkout__trust-row">
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
