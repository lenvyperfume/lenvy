<?php
/**
 * Cart page — single line item row.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$item = (array) ($args['item'] ?? []);
if (empty($item['id'])) {
	return;
}

$id            = (int)    $item['id'];
$brand         = (string) ($item['brand']         ?? '');
$name          = (string) ($item['name']          ?? '');
$variant       = (string) ($item['variant']       ?? '');
$price_value   = (float)  ($item['priceValue']    ?? 0);
$qty           = (int)    ($item['qty']           ?? 1);
$variant_class = (string) ($item['variant_class'] ?? 'v1');
$meta          = (array)  ($item['meta']          ?? []);

$line_total = $price_value * $qty;

// Format helper: "€ 128,00".
$eur = static function ($n) {
	return '€ ' . number_format_i18n($n, 2);
};

$href = function_exists('lenvy_placeholder_product_url')
	? lenvy_placeholder_product_url()
	: '#';
?>

<div
	class="lenvy-cart-row"
	data-cart-item
	data-id="<?php echo esc_attr($id); ?>"
	data-price="<?php echo esc_attr($price_value); ?>"
>
	<a href="<?php echo esc_url($href); ?>" class="lenvy-cart-row__img lenvy-cart-row__img--<?php echo esc_attr($variant_class); ?>" tabindex="-1" aria-hidden="true">
		<span class="lenvy-cart-row__cap"></span>
		<span class="lenvy-cart-row__bottle"></span>
	</a>

	<div class="lenvy-cart-row__body">
		<?php if ($brand): ?>
			<span class="lenvy-cart-row__brand"><?php echo esc_html($brand); ?></span>
		<?php endif; ?>
		<a href="<?php echo esc_url($href); ?>" class="lenvy-cart-row__name"><?php echo esc_html($name); ?></a>
		<?php if ($variant): ?>
			<span class="lenvy-cart-row__variant"><?php echo esc_html($variant); ?></span>
		<?php endif; ?>

		<?php if ($meta): ?>
			<div class="lenvy-cart-row__meta">
				<?php foreach ($meta as $m): ?>
					<span><?php echo esc_html((string) $m); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="lenvy-cart-row__actions">
			<div class="lenvy-cart-row__qty" data-cart-qty>
				<button type="button" data-cart-qty-act="dec" aria-label="<?php esc_attr_e('Verminder aantal', 'lenvy'); ?>">−</button>
				<span class="v" data-cart-qty-value><?php echo esc_html($qty); ?></span>
				<button type="button" data-cart-qty-act="inc" aria-label="<?php esc_attr_e('Verhoog aantal', 'lenvy'); ?>">+</button>
			</div>
			<a href="#" data-cart-act="wish"><?php esc_html_e('Verplaats naar verlanglijst', 'lenvy'); ?></a>
			<a href="#" data-cart-act="remove"><?php esc_html_e('Verwijder', 'lenvy'); ?></a>
		</div>
	</div>

	<div class="lenvy-cart-row__price">
		<span class="lenvy-cart-row__price-now" data-cart-line-total><?php echo esc_html($eur($line_total)); ?></span>
		<span class="lenvy-cart-row__price-each" data-cart-each<?php echo $qty > 1 ? '' : ' hidden'; ?>>
			<?php
			/* translators: %s: price per unit */
			echo esc_html(sprintf(__('%s per stuk', 'lenvy'), $eur($price_value)));
			?>
		</span>
		<span class="lenvy-cart-row__stock"><?php esc_html_e('Op voorraad', 'lenvy'); ?></span>
	</div>
</div>
