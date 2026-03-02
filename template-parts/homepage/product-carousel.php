<?php
/**
 * Homepage — reusable product carousel.
 *
 * Args:
 *   eyebrow    (string)       Small uppercase label above the heading.
 *   title      (string)       Section heading (serif italic).
 *   products   (WC_Product[]) Array of WC_Product objects.
 *   link_url   (string)       "View all" destination URL.
 *   link_label (string)       "View all" link text.
 *   bg_class   (string)       Optional background class for alternating sections.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$eyebrow    = $args['eyebrow'] ?? '';
$title      = $args['title'] ?? '';
$products   = $args['products'] ?? [];
$link_url   = $args['link_url'] ?? '';
$link_label = $args['link_label'] ?? __('Alles bekijken', 'lenvy');
$bg_class   = $args['bg_class'] ?? '';

if (empty($products)) {
	return;
}
?>

<section class="py-16 lg:py-24 <?php echo esc_attr($bg_class); ?>" data-product-carousel>
	<div class="lenvy-section">

		<!-- Section header — two-tier: eyebrow + serif heading -->
		<div class="flex items-end justify-between mb-10 lg:mb-14">
			<div>
				<?php if ($eyebrow): ?>
				<p class="text-[11px] uppercase tracking-widest text-neutral-400 mb-3">
					<?php echo esc_html($eyebrow); ?>
				</p>
				<?php endif; ?>
				<?php if ($title): ?>
				<h2 class="text-2xl md:text-3xl font-serif italic text-neutral-900 leading-tight">
					<?php echo esc_html($title); ?>
				</h2>
				<?php endif; ?>
			</div>

			<div class="flex items-center gap-4">
				<?php if ($link_url): ?>
				<a
					href="<?php echo esc_url($link_url); ?>"
					class="hidden sm:flex items-center gap-1.5 text-[13px] font-medium text-neutral-400 hover:text-black transition-colors duration-200"
				>
					<?php echo esc_html($link_label); ?>
					<?php lenvy_icon('arrow-right', '', 'xs'); ?>
				</a>
				<?php endif; ?>

				<!-- Carousel arrows -->
				<div class="hidden md:flex items-center gap-1.5">
					<button
						type="button"
						class="lenvy-product-carousel__arrow"
						data-carousel-prev
						aria-label="<?php esc_attr_e('Vorige', 'lenvy'); ?>"
						disabled
					>
						<?php lenvy_icon('chevron-left', '', 'sm'); ?>
					</button>
					<button
						type="button"
						class="lenvy-product-carousel__arrow"
						data-carousel-next
						aria-label="<?php esc_attr_e('Volgende', 'lenvy'); ?>"
					>
						<?php lenvy_icon('chevron-right', '', 'sm'); ?>
					</button>
				</div>
			</div>
		</div>

		<!-- Carousel track -->
		<div class="lenvy-product-carousel__track scrollbar-hide" data-carousel-track>
			<?php foreach ($products as $product): ?>
			<div class="lenvy-product-carousel__item">
				<?php get_template_part('template-parts/components/product-card', null, [
					'product_id' => $product->get_id(),
					'show_brand' => true,
				]); ?>
			</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
