<?php
/**
 * Product card — shop grid card with tags, wishlist, quick-add.
 *
 * Visual order: Image (with floating tags/wishlist/quick-add) → Brand → Title → Variant → Price
 *
 * Usage:
 *   get_template_part('template-parts/components/product-card', null, [
 *     'product_id' => 42,
 *     'show_brand' => true,
 *     'image_size' => 'woocommerce_thumbnail',
 *   ]);
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$product_id = (int) ($args['product_id'] ?? get_the_ID());
$show_brand = $args['show_brand'] ?? true;
$image_size = $args['image_size'] ?? 'woocommerce_thumbnail';

$product = wc_get_product($product_id);

if (!$product || !$product->is_visible()) {
	return;
}

$permalink  = get_permalink($product_id);
$title      = $product->get_name();
$is_sale    = $product->is_on_sale();
$is_oos     = !$product->is_in_stock();
$price_html = $product->get_price_html();

// ── Badges ──────────────────────────────────────────────────────────────────
$custom_badge = lenvy_field('lenvy_product_badge_text', $product_id);
$is_new = !$is_oos && !empty($custom_badge);

// ── Brand ────────────────────────────────────────────────────────────────────
$brand_name = '';
if ($show_brand) {
	$brands = get_the_terms($product_id, 'product_brand');
	if ($brands && !is_wp_error($brands)) {
		$brand_name = $brands[0]->name;
	}
}

// ── Variant label (concentration + size) ────────────────────────────────────
$concentration = $product->get_attribute('concentration');
$variant_text  = '';
$cheapest_size = '';
$is_variable   = $product->is_type('variable');

if ($is_variable) {
	$prices = $product->get_variation_prices(true);
	if (!empty($prices['price'])) {
		$min_var_id = array_keys($prices['price'])[0];
		$min_price  = $prices['price'][$min_var_id];
		$price_html = wc_price($min_price);
		$min_var    = wc_get_product($min_var_id);
		if ($min_var) {
			$cheapest_size = $min_var->get_attribute('size');
		}
	}
}

$variant_parts = array_filter([$concentration, $cheapest_size]);
if ($variant_parts) {
	$variant_text = implode(' · ', $variant_parts);
}

// ── Image ────────────────────────────────────────────────────────────────────
$image_id = (int) $product->get_image_id();

$image_html = $image_id
	? wp_get_attachment_image($image_id, $image_size, false, [
		'class'   => 'lenvy-card__img-el',
		'loading' => 'lazy',
		'alt'     => esc_attr($title),
	])
	: wc_placeholder_img($image_size, ['class' => 'lenvy-card__img-el']);

// ── Add-to-cart data ─────────────────────────────────────────────────────────
$is_purchasable = $product->is_purchasable() && $product->is_in_stock();
$is_simple      = 'simple' === $product->get_type();
$atc_url        = $product->add_to_cart_url();
?>

<article
	class="lenvy-card"
	data-product-id="<?php echo esc_attr($product_id); ?>"
>

	<a
		href="<?php echo esc_url($permalink); ?>"
		class="lenvy-card__img"
		tabindex="-1"
		aria-hidden="true"
	>
		<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $image_html; ?>

		<div class="lenvy-card__tags">
			<?php if ($is_new): ?>
				<span class="lenvy-tag lenvy-tag--new"><?php echo esc_html((string) $custom_badge); ?></span>
			<?php endif; ?>
			<?php if ($is_sale && !$is_oos): ?>
				<span class="lenvy-tag lenvy-tag--sale"><?php esc_html_e('Sale', 'lenvy'); ?></span>
			<?php endif; ?>
			<?php if ($is_oos): ?>
				<span class="lenvy-tag lenvy-tag--oos"><?php esc_html_e('Uitverkocht', 'lenvy'); ?></span>
			<?php endif; ?>
		</div>

		<?php if (!$is_oos): ?>
		<button
			type="button"
			class="lenvy-card__wish"
			data-wishlist-toggle
			aria-label="<?php echo esc_attr(sprintf(__('%s aan verlanglijst toevoegen', 'lenvy'), $title)); ?>"
		>
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
		</button>
		<?php endif; ?>

		<?php if ($is_purchasable && $is_simple): ?>
		<button
			type="button"
			class="lenvy-card__quick-add"
			data-quick-add
			data-product-id="<?php echo esc_attr($product_id); ?>"
			data-add-to-cart-url="<?php echo esc_url($atc_url); ?>"
			aria-label="<?php echo esc_attr(sprintf(__('%s toevoegen aan winkelwagen', 'lenvy'), $title)); ?>"
		>
			<?php esc_html_e('Snel toevoegen', 'lenvy'); ?>
		</button>
		<?php elseif ($is_purchasable): ?>
		<a
			href="<?php echo esc_url($permalink); ?>"
			class="lenvy-card__quick-add"
			aria-label="<?php echo esc_attr(sprintf(__('Bekijk opties voor %s', 'lenvy'), $title)); ?>"
		>
			<?php esc_html_e('Bekijk opties', 'lenvy'); ?>
		</a>
		<?php endif; ?>

		<?php if ($is_oos): ?>
		<span class="lenvy-card__oos-overlay" aria-hidden="true"></span>
		<?php endif; ?>
	</a>

	<?php if ($brand_name): ?>
	<p class="lenvy-card__brand"><?php echo esc_html($brand_name); ?></p>
	<?php endif; ?>

	<h3 class="lenvy-card__name">
		<a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
	</h3>

	<?php if ($variant_text): ?>
	<p class="lenvy-card__variant"><?php echo esc_html($variant_text); ?></p>
	<?php endif; ?>

	<?php if ($price_html): ?>
	<div class="lenvy-card__price">
		<?php if ($is_variable): ?>
			<span class="lenvy-card__price-prefix"><?php esc_html_e('Vanaf', 'lenvy'); ?></span>
		<?php endif; ?>
		<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $price_html; ?>
	</div>
	<?php endif; ?>

</article>
