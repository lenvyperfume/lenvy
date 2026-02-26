<?php
/**
 * Compact horizontal product card — for upsells, cross-sells, and mini lists.
 *
 * Usage:
 *   get_template_part('template-parts/components/product-card-mini', null, [
 *     'product_id' => 42,
 *   ]);
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$product_id = (int) ($args['product_id'] ?? get_the_ID());

$product = wc_get_product($product_id);

if (!$product || !$product->is_visible()) {
	return;
}

$permalink  = get_permalink($product_id);
$title      = $product->get_name();
$price_html = $product->get_price_html();

// Brand.
$brands     = get_the_terms($product_id, 'product_brand');
$brand_name = ($brands && !is_wp_error($brands)) ? $brands[0]->name : '';

// Image — portrait thumbnail.
$image_id   = (int) $product->get_image_id();
$image_html = $image_id
	? wp_get_attachment_image($image_id, 'woocommerce_thumbnail', false, [
		'class'   => 'w-full h-full object-cover',
		'loading' => 'lazy',
		'alt'     => esc_attr($title),
	])
	: wc_placeholder_img('woocommerce_thumbnail', ['class' => 'w-full h-full object-cover']);
?>

<article class="flex items-center gap-4">
	<a
		href="<?php echo esc_url($permalink); ?>"
		class="shrink-0 w-16 aspect-product overflow-hidden bg-neutral-50"
		tabindex="-1"
		aria-hidden="true"
	>
		<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $image_html; ?>
	</a>

	<div class="flex-1 min-w-0">
		<?php if ($brand_name): ?>
		<span class="text-[10px] uppercase tracking-[0.1em] text-neutral-400 line-clamp-1">
			<?php echo esc_html($brand_name); ?>
		</span>
		<?php endif; ?>

		<a
			href="<?php echo esc_url($permalink); ?>"
			class="text-sm font-medium text-neutral-800 hover:text-neutral-900 transition-colors duration-200 line-clamp-2 leading-snug"
		>
			<?php echo esc_html($title); ?>
		</a>

		<?php if ($price_html): ?>
		<div class="mt-1 lenvy-card-price">
			<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $price_html; ?>
		</div>
		<?php endif; ?>
	</div>
</article>
