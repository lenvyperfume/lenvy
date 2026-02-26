<?php
/**
 * Product grid component — reusable grid wrapper for product cards.
 *
 * Usage:
 *   get_template_part('template-parts/components/product-grid', null, [
 *     'query'      => $wp_query,        // WP_Query instance (uses current global if omitted)
 *     'product_ids'=> [1, 2, 3],        // alternative: array of product IDs (ignored if query is set)
 *     'show_brand' => true,             // passed through to product-card
 *     'image_size' => 'woocommerce_thumbnail',
 *     'columns'    => 4,                // max columns on desktop (3 or 4)
 *     'taxonomy'   => '',               // data-taxonomy for AJAX filters
 *     'term'       => '',               // data-term for AJAX filters
 *     'class'      => '',               // additional classes on the grid wrapper
 *   ]);
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$query       = $args['query'] ?? null;
$product_ids = $args['product_ids'] ?? [];
$show_brand  = $args['show_brand'] ?? true;
$image_size  = $args['image_size'] ?? 'woocommerce_thumbnail';
$columns     = (int) ($args['columns'] ?? 4);
$taxonomy    = $args['taxonomy'] ?? '';
$term        = $args['term'] ?? '';
$extra_class = $args['class'] ?? '';

// Grid responsive classes based on max columns.
$col_classes = match ($columns) {
	3 => 'grid-cols-2 md:grid-cols-3',
	default => 'grid-cols-2 md:grid-cols-3 lg:grid-cols-4',
};

$grid_classes = trim(
	"grid {$col_classes} gap-x-4 gap-y-8 md:gap-x-6 md:gap-y-10 lg:gap-x-8 lg:gap-y-14 {$extra_class}"
);

$card_args = [
	'show_brand' => $show_brand,
	'image_size' => $image_size,
];
?>

<div
	class="<?php echo esc_attr($grid_classes); ?>"
	data-product-grid
	data-taxonomy="<?php echo esc_attr($taxonomy); ?>"
	data-term="<?php echo esc_attr($term); ?>"
>
	<?php if ($query instanceof WP_Query && $query->have_posts()): ?>
		<?php while ($query->have_posts()):
			$query->the_post(); ?>
			<?php get_template_part('template-parts/components/product-card', null, array_merge(
				$card_args,
				['product_id' => get_the_ID()]
			)); ?>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>

	<?php elseif (!empty($product_ids)): ?>
		<?php foreach ($product_ids as $pid): ?>
			<?php get_template_part('template-parts/components/product-card', null, array_merge(
				$card_args,
				['product_id' => (int) $pid]
			)); ?>
		<?php endforeach; ?>

	<?php elseif (null === $query && have_posts()): ?>
		<?php while (have_posts()):
			the_post(); ?>
			<?php get_template_part('template-parts/components/product-card', null, array_merge(
				$card_args,
				['product_id' => get_the_ID()]
			)); ?>
		<?php endwhile; ?>

	<?php else: ?>
		<div class="col-span-full py-20 text-center">
			<p class="text-sm text-neutral-500"><?php esc_html_e('No products found.', 'lenvy'); ?></p>
		</div>
	<?php endif; ?>
</div>
