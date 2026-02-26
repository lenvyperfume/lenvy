<?php
/**
 * Homepage — featured categories grid.
 *
 * Renders up to 6 ACF-selected product_cat terms as portrait image cards.
 * Image priority: ACF banner (lenvy_cat_banner_image) → WC thumbnail_id meta.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$cat_ids = lenvy_field('lenvy_featured_categories');

if (empty($cat_ids)) {
	return;
}

// Build valid term objects from the ID array
$cats = [];
foreach ((array) $cat_ids as $id) {
	$term = get_term((int) $id, 'product_cat');
	if ($term && !is_wp_error($term)) {
		$cats[] = $term;
	}
}

if (empty($cats)) {
	return;
}

$count = count($cats);

// Responsive grid class adapts to how many categories are shown
$grid_cols = match (true) {
	$count <= 2 => 'grid-cols-2',
	$count === 3 => 'grid-cols-2 md:grid-cols-3',
	$count <= 4 => 'grid-cols-2 md:grid-cols-4',
	$count === 5 => 'grid-cols-2 md:grid-cols-3 lg:grid-cols-5',
	default => 'grid-cols-2 md:grid-cols-3 lg:grid-cols-6',
};

$shop_url = function_exists('wc_get_page_permalink')
	? wc_get_page_permalink('shop')
	: get_post_type_archive_link('product');
?>

<section class="py-16 lg:py-24">
	<div class="lenvy-section">

		<!-- Section header — two-tier -->
		<div class="flex items-end justify-between mb-10 lg:mb-14">
			<div>
				<p class="text-[11px] uppercase tracking-widest text-neutral-400 mb-3">
					<?php esc_html_e('Categorieën', 'lenvy'); ?>
				</p>
				<h2 class="text-2xl md:text-3xl font-serif italic text-neutral-900 leading-tight">
					<?php esc_html_e('Shop by Category', 'lenvy'); ?>
				</h2>
			</div>
			<a
				href="<?php echo esc_url($shop_url ?: home_url('/shop/')); ?>"
				class="hidden sm:flex items-center gap-1.5 text-[13px] font-medium text-neutral-400 hover:text-black transition-colors duration-200"
			>
				<?php esc_html_e('Alles bekijken', 'lenvy'); ?>
				<?php lenvy_icon('arrow-right', '', 'xs'); ?>
			</a>
		</div>

		<!-- Category grid -->
		<div class="grid <?php echo esc_attr($grid_cols); ?> gap-4 md:gap-5">
			<?php foreach ($cats as $term):

			// Image priority: ACF banner → WC thumbnail meta
			$acf_img = lenvy_field('lenvy_cat_banner_image', "term_{$term->term_id}");
			$image_id = is_array($acf_img) ? $acf_img['ID'] ?? 0 : 0;

			if (!$image_id) {
				$image_id = (int) get_term_meta($term->term_id, 'thumbnail_id', true);
			}

			$term_url = get_term_link($term, 'product_cat');
			$term_url = is_wp_error($term_url) ? ($shop_url ?: home_url('/shop/')) : $term_url;
			?>
			<a
				href="<?php echo esc_url($term_url); ?>"
				class="group relative block overflow-hidden bg-neutral-100 aspect-[3/4]"
				aria-label="<?php echo esc_attr($term->name); ?>"
			>

				<!-- Category image -->
				<?php if ($image_id): ?>
					<?php echo wp_get_attachment_image($image_id, 'medium_large', false, [
					'class' =>
						'absolute inset-0 w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.03]',
					'loading' => 'lazy',
					'alt' => esc_attr($term->name),
				]); ?>
				<?php else: ?>
					<div class="absolute inset-0 flex items-center justify-center">
						<span class="text-xs uppercase tracking-widest text-neutral-300">
							<?php echo esc_html($term->name); ?>
						</span>
					</div>
				<?php endif; ?>

				<!-- Bottom gradient + label -->
				<div class="absolute inset-0 bg-gradient-to-t from-neutral-950/65 via-transparent to-transparent"></div>
				<div class="absolute bottom-0 left-0 right-0 p-4">
					<p class="text-sm font-medium text-white leading-snug">
						<?php echo esc_html($term->name); ?>
					</p>
					<?php if ($term->count > 0): ?>
						<p class="text-[11px] text-white/50 mt-0.5">
							<?php echo esc_html(
							sprintf(
								/* translators: %d: product count */
								_n('%d product', '%d products', $term->count, 'lenvy'),
								$term->count,
							),
						); ?>
						</p>
					<?php endif; ?>
				</div>

			</a>
			<?php endforeach; ?>
		</div>

	</div>
</section>
