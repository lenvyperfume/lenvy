<?php
/**
 * Homepage — static product grid (no carousel).
 *
 * Displays products in a 2/3/4-column responsive grid. Used for
 * bestsellers where showing everything at once is more impactful
 * than a swipeable carousel.
 *
 * Args:
 *   eyebrow    (string)       Small uppercase label above the heading.
 *   title      (string)       Section heading (serif italic).
 *   products   (WC_Product[]) Array of WC_Product objects.
 *   link_url   (string)       "View all" destination URL.
 *   link_label (string)       "View all" link text.
 *   bg_class   (string)       Optional background class.
 *   columns    (int)          Desktop columns: 3 or 4 (default 4).
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
$columns    = (int) ($args['columns'] ?? 4);

if (empty($products)) {
	return;
}

$grid_class = $columns === 3
	? 'grid-cols-2 md:grid-cols-3'
	: 'grid-cols-2 md:grid-cols-3 lg:grid-cols-4';
?>

<section class="py-16 lg:py-24 <?php echo esc_attr($bg_class); ?>">
	<div class="lenvy-section">

		<!-- Section header -->
		<div class="flex items-end justify-between mb-10 lg:mb-14">
			<div>
				<?php if ($eyebrow): ?>
				<p class="text-[11px] uppercase tracking-widest text-neutral-400 mb-3">
					<?php echo esc_html($eyebrow); ?>
				</p>
				<?php endif; ?>
				<?php if ($title): ?>
				<h2 class="text-2xl md:text-3xl font-medium text-neutral-900 leading-tight">
					<?php echo esc_html($title); ?>
				</h2>
				<?php endif; ?>
			</div>

			<?php if ($link_url): ?>
			<a
				href="<?php echo esc_url($link_url); ?>"
				class="hidden sm:flex items-center gap-1.5 text-[13px] font-medium text-neutral-400 hover:text-black transition-colors duration-200"
			>
				<?php echo esc_html($link_label); ?>
				<?php lenvy_icon('arrow-right', '', 'xs'); ?>
			</a>
			<?php endif; ?>
		</div>

		<!-- Product grid -->
		<div class="grid <?php echo esc_attr($grid_class); ?> gap-4 md:gap-5">
			<?php foreach ($products as $product): ?>
				<?php get_template_part('template-parts/components/product-card', null, [
					'product_id' => $product->get_id(),
					'show_brand' => true,
				]); ?>
			<?php endforeach; ?>
		</div>

		<?php if ($link_url): ?>
		<!-- Mobile "view all" link -->
		<div class="mt-8 text-center sm:hidden">
			<a
				href="<?php echo esc_url($link_url); ?>"
				class="inline-flex items-center gap-1.5 text-sm font-medium text-neutral-500 hover:text-black transition-colors"
			>
				<?php echo esc_html($link_label); ?>
				<?php lenvy_icon('arrow-right', '', 'xs'); ?>
			</a>
		</div>
		<?php endif; ?>

	</div>
</section>
