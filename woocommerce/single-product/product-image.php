<?php
/**
 * Single product gallery — main image (contain) + portrait thumbnail strip.
 *
 * Overrides woocommerce/single-product/product-image.php
 *
 * @package Lenvy
 * @see     WC templates/single-product/product-image.php
 */

defined('ABSPATH') || exit();

global $product;

$main_id     = $product->get_image_id();
$gallery_ids = $product->get_gallery_image_ids();
$all_ids     = $main_id
	? array_merge([(int) $main_id], array_map('intval', $gallery_ids))
	: array_map('intval', $gallery_ids);

if (empty($all_ids)) {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — WC placeholder is safe
	echo wc_placeholder_img('woocommerce_single', ['class' => 'w-full h-full object-contain']);
	return;
}
?>

<!-- Main image -->
<div class="relative overflow-hidden bg-neutral-50 aspect-product">
	<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — wp_get_attachment_image output
	echo wp_get_attachment_image($all_ids[0], 'woocommerce_single', false, [
		'class'         => 'w-full h-full object-contain transition-opacity duration-200',
		'data-gallery-main' => '',
		'fetchpriority' => 'high',
		'loading'       => 'eager',
		'alt'           => esc_attr($product->get_name()),
	]); ?>

	<?php if ($product->is_on_sale()): ?>
	<span class="absolute top-4 left-4 z-10">
		<?php get_template_part('template-parts/components/badge', null, [
			'text'    => __('Sale', 'lenvy'),
			'variant' => 'sale',
		]); ?>
	</span>
	<?php endif; ?>

	<?php if (!$product->is_in_stock()): ?>
	<span class="absolute inset-0 bg-white/60 flex items-center justify-center">
		<span class="text-[11px] font-medium uppercase tracking-widest text-neutral-500">
			<?php esc_html_e('Uitverkocht', 'lenvy'); ?>
		</span>
	</span>
	<?php endif; ?>
</div>

<!-- Thumbnail strip -->
<?php if (count($all_ids) > 1): ?>
<div class="flex gap-3 mt-5 overflow-x-auto scrollbar-hide" data-gallery-thumbs>
	<?php foreach ($all_ids as $i => $img_id): ?>
		<?php $full_src = wp_get_attachment_image_url($img_id, 'woocommerce_single'); ?>
		<button
			type="button"
			class="shrink-0 w-20 aspect-product overflow-hidden bg-neutral-50 border-b-2 transition-colors duration-200 <?php echo 0 === $i
				? 'border-neutral-900'
				: 'border-transparent hover:border-neutral-300'; ?>"
			data-gallery-thumb
			data-src="<?php echo esc_url((string) $full_src); ?>"
			aria-label="<?php echo esc_attr(sprintf(__('View image %d', 'lenvy'), $i + 1)); ?>"
		>
			<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo wp_get_attachment_image($img_id, 'woocommerce_thumbnail', false, [
				'class'   => 'w-full h-full object-contain',
				'loading' => 'lazy',
				'alt'     => '',
			]); ?>
		</button>
	<?php endforeach; ?>
</div>
<?php endif; ?>
