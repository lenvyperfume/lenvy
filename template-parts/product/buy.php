<?php
/**
 * PDP buy column — brand · title · intro · meters · sizes · CTA · service.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$product = $args['product'] ?? null;
if (!$product) {
	return;
}

// Find the active size for the initial price display.
$active_size = null;
foreach ($product['sizes'] as $s) {
	if ($s['on']) {
		$active_size = $s;
		break;
	}
}
$active_size = $active_size ?? $product['sizes'][0];

$service_icons = [
	'truck'  => 'M1 3h15v13H1zM16 8h4l3 3v5h-7zM5.5 18.5a2.5 2.5 0 1 1 0 .01zM18.5 18.5a2.5 2.5 0 1 1 0 .01z',
	'shield' => 'M3 12a9 9 0 1 0 18 0 9 9 0 0 0-18 0zM9 12l2 2 4-4',
	'gift'   => 'M20 12v8H4V4h8M15 3h6v6M21 3l-9 9',
	'return' => 'M3 12a9 9 0 1 0 3-6.7L3 8M3 3v5h5',
];
?>

<aside class="lenvy-pdp__buy" aria-label="<?php esc_attr_e('Productinformatie en aankoop', 'lenvy'); ?>">
	<div class="lenvy-pdp__buy-inner">

		<span class="lenvy-pdp__brand"><?php echo esc_html($product['brand']); ?></span>
		<h1 class="lenvy-pdp__title"><?php echo esc_html($product['name']); ?></h1>
		<div class="lenvy-pdp__subtitle"><?php echo esc_html($product['subtitle']); ?></div>

		<p class="lenvy-pdp__intro"><?php echo esc_html($product['intro']); ?></p>

		<div class="lenvy-pdp__meters">
			<?php foreach ($product['meters'] as $m): ?>
				<div class="lenvy-pdp__meter">
					<div class="lenvy-pdp__meter-line">
						<span class="lenvy-pdp__meter-lbl"><?php echo esc_html($m['label']); ?></span>
						<span class="lenvy-pdp__meter-val"><?php echo esc_html($m['value']); ?></span>
					</div>
					<div class="lenvy-pdp__meter-bar" aria-hidden="true">
						<?php for ($i = 1; $i <= 5; $i++): ?>
							<i class="<?php echo $i <= (int) $m['fill'] ? 'is-on' : ''; ?>"></i>
						<?php endfor; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="lenvy-pdp__sizes">
			<h3 class="lenvy-pdp__sizes-head"><?php esc_html_e('Formaat', 'lenvy'); ?></h3>
			<div class="lenvy-pdp__size-tiles" data-pdp-sizes>
				<?php foreach ($product['sizes'] as $s): ?>
					<button
						type="button"
						class="lenvy-pdp__size-tile<?php echo $s['on'] ? ' is-active' : ''; ?>"
						data-pdp-size
						data-size="<?php echo esc_attr($s['ml']); ?>"
						data-price="<?php echo esc_attr($s['price']); ?>"
					>
						<span class="s1"><?php echo esc_html($s['ml']); ?> ml</span>
						<span class="s2">€ <?php echo esc_html(number_format_i18n($s['price'], 0)); ?>,00</span>
						<?php if (!empty($s['badge'])): ?>
							<span class="s3"><?php echo esc_html($s['badge']); ?></span>
						<?php endif; ?>
					</button>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="lenvy-pdp__price-row">
			<span class="lenvy-pdp__price" data-pdp-price>
				€ <?php echo esc_html(number_format_i18n($active_size['price'], 0)); ?>,00
			</span>
			<span class="lenvy-pdp__price-per" data-pdp-price-per>
				€ <?php echo esc_html(number_format_i18n($active_size['price'] / max(1, $active_size['ml']), 2)); ?> / ml
			</span>
		</div>

		<div class="lenvy-pdp__cta-row">
			<div class="lenvy-pdp__qty" data-lenvy-qty>
				<button type="button" data-qty-minus aria-label="<?php esc_attr_e('Verminder', 'lenvy'); ?>">−</button>
				<input type="number" class="qty" value="1" min="1" max="9">
				<button type="button" data-qty-plus aria-label="<?php esc_attr_e('Verhoog', 'lenvy'); ?>">+</button>
			</div>
			<button type="button" class="lenvy-pdp__cta">
				<?php esc_html_e('In winkelwagen', 'lenvy'); ?>
			</button>
			<button
				type="button"
				class="lenvy-pdp__wish"
				data-wishlist-toggle
				aria-label="<?php esc_attr_e('Aan verlanglijst toevoegen', 'lenvy'); ?>"
			>
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
					<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
				</svg>
			</button>
		</div>

		<div class="lenvy-pdp__service">
			<?php foreach ($product['service'] as $row):
				$path = $service_icons[$row['icon']] ?? '';
			?>
				<div class="lenvy-pdp__service-row">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="<?php echo esc_attr($path); ?>"/>
					</svg>
					<span>
						<span class="t1"><?php echo esc_html($row['title']); ?></span>
						<span class="t2"><?php echo esc_html($row['subtitle']); ?></span>
					</span>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="lenvy-pdp__specs">
			<?php foreach ($product['specs'] as $spec): ?>
				<span><b><?php echo esc_html($spec['label']); ?></b><?php echo esc_html($spec['value']); ?></span>
			<?php endforeach; ?>
		</div>

	</div>
</aside>
