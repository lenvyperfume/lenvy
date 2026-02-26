<?php
/**
 * Product card component — minimal, image-dominant card for product grids.
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

// ── Badge — OOS takes priority, then custom ACF, then sale ──────────────────
$badge_text    = '';
$badge_variant = 'custom';

$custom_badge = lenvy_field('lenvy_product_badge_text', $product_id);

if ($is_oos) {
	$badge_text    = __('Uitverkocht', 'lenvy');
	$badge_variant = 'oos';
} elseif ($custom_badge) {
	$badge_text    = (string) $custom_badge;
	$badge_variant = 'new';
} elseif ($is_sale) {
	$badge_text    = __('Sale', 'lenvy');
	$badge_variant = 'sale';
}

// ── Brand ────────────────────────────────────────────────────────────────────
$brand_name = '';
if ($show_brand) {
	$brands = get_the_terms($product_id, 'product_brand');
	if ($brands && !is_wp_error($brands)) {
		$brand_name = $brands[0]->name;
	}
}

// ── Image ────────────────────────────────────────────────────────────────────
$image_id = (int) $product->get_image_id();

$image_html = $image_id
	? wp_get_attachment_image($image_id, $image_size, false, [
		'class'   => 'w-full h-full object-cover transition-opacity duration-300 group-hover:opacity-90',
		'loading' => 'lazy',
		'alt'     => esc_attr($title),
	])
	: wc_placeholder_img($image_size, ['class' => 'w-full h-full object-cover']);

// ── Add-to-cart data ─────────────────────────────────────────────────────────
$is_purchasable = $product->is_purchasable() && $product->is_in_stock();
$is_simple      = 'simple' === $product->get_type();
$atc_url        = $product->add_to_cart_url();
$atc_text       = $product->add_to_cart_text();
?>

<article
	class="group relative flex flex-col"
	data-product-id="<?php echo esc_attr($product_id); ?>"
>
	<!-- Image -->
	<a
		href="<?php echo esc_url($permalink); ?>"
		class="relative block overflow-hidden bg-neutral-50 aspect-product"
		tabindex="-1"
		aria-hidden="true"
	>
		<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — wp_get_attachment_image output
		echo $image_html; ?>

		<?php if ($badge_text): ?>
		<span class="absolute top-3 left-3 z-10">
			<?php get_template_part('template-parts/components/badge', null, [
				'text'    => $badge_text,
				'variant' => $badge_variant,
			]); ?>
		</span>
		<?php endif; ?>

		<?php if ($is_oos): ?>
		<span class="absolute inset-0 bg-white/60 z-[5]"></span>
		<?php endif; ?>

		<?php if ($is_simple && $is_purchasable): ?>
		<div class="absolute inset-x-0 bottom-0 z-10 opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-200 ease-out">
			<button
				type="button"
				class="w-full bg-primary text-black text-[11px] font-medium uppercase tracking-widest py-3 hover:bg-primary-hover transition-colors"
				data-quick-add
				data-product-id="<?php echo esc_attr($product_id); ?>"
				data-add-to-cart-url="<?php echo esc_url($atc_url); ?>"
				aria-label="<?php echo esc_attr(sprintf(__('Add %s to cart', 'lenvy'), $title)); ?>"
			>
				<?php echo esc_html($atc_text); ?>
			</button>
		</div>
		<?php elseif (!$is_simple && $is_purchasable): ?>
		<div class="absolute inset-x-0 bottom-0 z-10 opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-200 ease-out">
			<a
				href="<?php echo esc_url($permalink); ?>"
				class="block w-full bg-primary text-black text-[11px] font-medium uppercase tracking-widest py-3 text-center hover:bg-primary-hover transition-colors"
			>
				<?php esc_html_e('Opties bekijken', 'lenvy'); ?>
			</a>
		</div>
		<?php endif; ?>
	</a>

	<!-- Details -->
	<div class="pt-4 flex flex-col gap-0.5">

		<?php if ($brand_name): ?>
		<span class="text-[11px] uppercase tracking-[0.1em] text-neutral-400 line-clamp-1">
			<?php echo esc_html($brand_name); ?>
		</span>
		<?php endif; ?>

		<a
			href="<?php echo esc_url($permalink); ?>"
			class="text-sm font-medium text-neutral-800 leading-snug line-clamp-2 mt-0.5 transition-colors duration-150 hover:text-neutral-900"
		>
			<?php echo esc_html($title); ?>
		</a>

		<?php if ($price_html): ?>
		<div class="mt-1.5 lenvy-card-price">
			<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — WC returns safe price HTML
			echo $price_html; ?>
		</div>
		<?php endif; ?>

	</div>
</article>
