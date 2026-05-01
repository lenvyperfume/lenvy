<?php
/**
 * Thank-you — sticky order summary recap.
 *
 * Mirrors the checkout summary visually but switches the head to a
 * "3 artikelen" count, drops the promo input, and labels the grand
 * total as "Betaald" (the order is already paid).
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$cart     = (array) ($args['cart'] ?? []);
$items    = (array) ($cart['items'] ?? []);
$vat_rate = (float) ($cart['vat_rate'] ?? 0.21);

$subtotal = 0.0;
$count    = 0;
foreach ($items as $i) {
	$subtotal += (float) ($i['priceValue'] ?? 0) * (int) ($i['qty'] ?? 1);
	$count    += (int) ($i['qty'] ?? 1);
}
$grand = $subtotal; // free shipping for now
$btw   = ($grand * $vat_rate) / (1 + $vat_rate);

$eur = static fn($n) => '€ ' . number_format_i18n((float) $n, 2);
?>

<aside class="lenvy-checkout__summary-col">
	<section class="lenvy-checkout__summary">
		<div class="lenvy-checkout__summary-head">
			<h3><?php esc_html_e('Jouw bestelling', 'lenvy'); ?></h3>
			<span class="lenvy-thankyou__summary-count">
				<?php
				/* translators: %d: item count */
				echo esc_html(sprintf(_n('%d artikel', '%d artikelen', $count, 'lenvy'), $count));
				?>
			</span>
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

		<div class="lenvy-checkout__totals">
			<div class="lenvy-checkout__totals-line">
				<span><?php esc_html_e('Subtotaal', 'lenvy'); ?></span>
				<span class="v"><?php echo esc_html($eur($subtotal)); ?></span>
			</div>
			<div class="lenvy-checkout__totals-line">
				<span><?php esc_html_e('Verzending', 'lenvy'); ?></span>
				<span class="v lenvy-checkout__totals-free"><?php esc_html_e('Gratis', 'lenvy'); ?></span>
			</div>
			<div class="lenvy-checkout__totals-line">
				<span><?php esc_html_e('BTW (21%)', 'lenvy'); ?></span>
				<span class="v"><?php echo esc_html($eur($btw)); ?></span>
			</div>
		</div>

		<div class="lenvy-checkout__grand">
			<span class="lenvy-checkout__grand-lbl"><?php esc_html_e('Betaald', 'lenvy'); ?></span>
			<span class="lenvy-checkout__grand-v">
				<?php echo esc_html($eur($grand)); ?>
				<small><?php esc_html_e('incl. btw', 'lenvy'); ?></small>
			</span>
		</div>
	</section>
</aside>
