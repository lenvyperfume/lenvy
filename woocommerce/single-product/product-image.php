<?php
/**
 * Single product gallery — Embla carousel + vertical thumbnail strip.
 *
 * Desktop: [slider] [vertical thumbs on right]
 * Mobile:  [slider] [dots below]
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

$has_gallery = count($all_ids) > 1;
?>

<div class="<?php echo $has_gallery ? 'lg:flex lg:gap-4' : ''; ?>">

	<?php if ($has_gallery): ?>
	<!-- Vertical thumbnail strip (desktop, left side) -->
	<div class="hidden lg:flex lg:flex-col gap-3 lg:w-20 shrink-0" data-gallery-thumbs>
		<?php foreach ($all_ids as $i => $img_id): ?>
		<button
			type="button"
			class="w-20 aspect-square overflow-hidden bg-neutral-50 border-b-2 transition-colors duration-200 <?php echo 0 === $i
				? 'border-primary'
				: 'border-transparent hover:border-neutral-300'; ?>"
			data-gallery-thumb="<?php echo (int) $i; ?>"
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

	<!-- Slider -->
	<div class="relative lg:flex-1 lg:min-w-0" data-gallery-slider>

		<!-- Embla viewport -->
		<div class="overflow-hidden" data-gallery-viewport>
			<div class="flex" data-gallery-container>
				<?php foreach ($all_ids as $i => $img_id): ?>
				<div class="flex-[0_0_100%] min-w-0 bg-neutral-50" data-gallery-slide>
					<div class="aspect-square flex items-center justify-center">
						<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo wp_get_attachment_image($img_id, 'woocommerce_single', false, [
							'class'         => 'max-h-full max-w-full object-contain',
							'draggable'     => 'false',
							'fetchpriority' => 0 === $i ? 'high' : 'auto',
							'loading'       => 0 === $i ? 'eager' : 'lazy',
							'alt'           => esc_attr($product->get_name()),
						]); ?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ($product->is_on_sale()): ?>
		<span class="absolute top-4 left-4 z-10 pointer-events-none">
			<?php get_template_part('template-parts/components/badge', null, [
				'text'    => __('Sale', 'lenvy'),
				'variant' => 'sale',
			]); ?>
		</span>
		<?php endif; ?>

		<?php if (!$product->is_in_stock()): ?>
		<span class="absolute inset-0 bg-white/60 flex items-center justify-center pointer-events-none z-10">
			<span class="text-[11px] font-medium uppercase tracking-widest text-neutral-500">
				<?php esc_html_e('Uitverkocht', 'lenvy'); ?>
			</span>
		</span>
		<?php endif; ?>

		<?php if ($has_gallery): ?>
		<!-- Dot indicators (mobile) -->
		<div class="flex items-center justify-center gap-2 mt-3 lg:hidden" data-gallery-dots>
			<?php foreach ($all_ids as $i => $img_id): ?>
			<button
				type="button"
				class="lenvy-gallery-dot<?php echo 0 === $i ? ' is-active' : ''; ?>"
				data-gallery-dot="<?php echo (int) $i; ?>"
				aria-label="<?php echo esc_attr(sprintf(__('View image %d', 'lenvy'), $i + 1)); ?>"
			></button>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>

</div>
