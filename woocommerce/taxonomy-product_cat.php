<?php
/**
 * Product category archive — banner + full-width grid with drawer filters.
 *
 * Overrides woocommerce/taxonomy-product_cat.php
 *
 * @package Lenvy
 * @see     WC templates/taxonomy-product_cat.php
 */

defined('ABSPATH') || exit();

get_header();

$term = get_queried_object();
?>

<?php
// ── Category banner ───────────────────────────────────────────────────────────
$banner_image   = lenvy_field('lenvy_cat_banner_image', 'term_' . $term->term_id);
$banner_heading = lenvy_field('lenvy_cat_banner_heading', 'term_' . $term->term_id) ?: $term->name;
$banner_image_id = is_array($banner_image) ? (int) ($banner_image['ID'] ?? 0) : (int) $banner_image;

if ($banner_image_id):
	$banner_img = wp_get_attachment_image($banner_image_id, 'full', false, [
		'class'         => 'w-full h-full object-cover',
		'fetchpriority' => 'high',
		'loading'       => 'eager',
		'alt'           => esc_attr($banner_heading),
	]); ?>
	<div class="relative h-40 md:h-56 overflow-hidden bg-neutral-900">
		<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $banner_img; ?>
		<div class="absolute inset-0 bg-neutral-900/50 flex items-end">
			<div class="lenvy-container pb-8">
				<h1 class="text-3xl md:text-4xl font-serif italic text-white">
					<?php echo esc_html($banner_heading); ?>
				</h1>
			</div>
		</div>
	</div>
<?php else: ?>
	<div class="py-10 border-b border-neutral-100">
		<div class="lenvy-container">
			<h1 class="text-3xl font-serif italic text-neutral-900">
				<?php echo esc_html($banner_heading); ?>
			</h1>
		</div>
	</div>
<?php endif; ?>

<main id="primary" class="py-10 lg:py-16">
	<div class="lenvy-container">

		<?php get_template_part('template-parts/components/breadcrumb'); ?>

		<?php get_template_part('template-parts/shop/sort-bar'); ?>

		<?php if (lenvy_is_filtered()): ?>
			<?php get_template_part('template-parts/shop/filter-active'); ?>
		<?php endif; ?>

		<?php if (woocommerce_product_loop()): ?>

			<?php do_action('woocommerce_before_shop_loop'); ?>

			<div class="mt-8">
				<?php get_template_part('template-parts/components/product-grid', null, [
					'taxonomy' => 'product_cat',
					'term'     => $term->slug,
				]); ?>
			</div>

			<?php do_action('woocommerce_after_shop_loop'); ?>

			<?php lenvy_pagination(); ?>

		<?php else: ?>

			<?php get_template_part('woocommerce/loop/no-products-found'); ?>

		<?php endif; ?>

		<?php
		$seo_text = lenvy_field('lenvy_cat_seo_text', 'term_' . $term->term_id);
		if ($seo_text): ?>
		<div class="mt-20 pt-12 border-t border-neutral-100 entry-content">
			<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — ACF WYSIWYG
			echo wp_kses_post($seo_text); ?>
		</div>
		<?php endif; ?>

	</div>
</main>

<?php get_template_part('template-parts/shop/filter-drawer'); ?>

<?php get_footer(); ?>
