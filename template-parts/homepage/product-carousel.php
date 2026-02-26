<?php
/**
 * Homepage — reusable product carousel.
 *
 * Args:
 *   title      (string)       Section heading.
 *   products   (WC_Product[]) Array of WC_Product objects.
 *   link_url   (string)       "View all" destination URL.
 *   link_label (string)       "View all" link text.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$title      = $args['title'] ?? '';
$products   = $args['products'] ?? [];
$link_url   = $args['link_url'] ?? '';
$link_label = $args['link_label'] ?? __('Alles bekijken', 'lenvy');

if (empty($products)) {
	return;
}
?>

<section class="py-10 lg:py-14" data-product-carousel>
	<div class="lenvy-container">

		<!-- Section header -->
		<div class="flex items-center justify-between mb-6 lg:mb-8">
			<h2 class="flex items-center gap-3 text-xs font-medium uppercase tracking-widest text-neutral-500">
				<span class="inline-block w-6 h-0.5 bg-primary" aria-hidden="true"></span>
				<?php echo esc_html($title); ?>
			</h2>

			<div class="flex items-center gap-4">
				<?php if ($link_url): ?>
				<a
					href="<?php echo esc_url($link_url); ?>"
					class="flex items-center gap-1.5 text-xs font-medium uppercase tracking-widest text-neutral-400 hover:text-black transition-colors duration-150"
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
						aria-label="<?php esc_attr_e('Previous', 'lenvy'); ?>"
						disabled
					>
						<?php lenvy_icon('chevron-left', '', 'sm'); ?>
					</button>
					<button
						type="button"
						class="lenvy-product-carousel__arrow"
						data-carousel-next
						aria-label="<?php esc_attr_e('Next', 'lenvy'); ?>"
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
