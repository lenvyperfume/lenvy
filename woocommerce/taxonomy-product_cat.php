<?php
/**
 * Product category archive — banner + ACF enhancements + filter/grid layout.
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
$banner_image = lenvy_field('lenvy_cat_banner_image', 'term_' . $term->term_id);
$banner_heading = lenvy_field('lenvy_cat_banner_heading', 'term_' . $term->term_id) ?: $term->name;
$banner_image_id = is_array($banner_image) ? (int) ($banner_image['ID'] ?? 0) : (int) $banner_image;

if ($banner_image_id):
	$banner_img = wp_get_attachment_image($banner_image_id, 'full', false, [
		'class' => 'w-full h-full object-cover',
		'fetchpriority' => 'high',
		'loading' => 'eager',
		'alt' => esc_attr($banner_heading),
	]); ?>
	<div class="relative h-40 md:h-56 overflow-hidden bg-neutral-900">
		<?php echo $banner_img;
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
		<div class="absolute inset-0 bg-neutral-900/50 flex items-end">
			<div class="lenvy-container pb-8">
				<h1 class="text-3xl md:text-4xl font-serif italic text-white">
					<?php echo esc_html($banner_heading); ?>
				</h1>
			</div>
		</div>
	</div>
<?php
else:
	 ?>
	<div class="py-10 border-b border-neutral-100">
		<div class="lenvy-container">
			<h1 class="text-2xl font-serif italic text-neutral-900">
				<?php echo esc_html($banner_heading); ?>
			</h1>
		</div>
	</div>
<?php
endif;
?>

<main id="primary" class="py-8 lg:py-12">
	<div class="lenvy-container">

		<?php get_template_part('template-parts/components/breadcrumb'); ?>

		<div class="flex gap-8 mt-6">

			<?php get_template_part('template-parts/shop/filter-sidebar'); ?>

			<div class="flex-1 min-w-0">

				<?php get_template_part('template-parts/shop/sort-bar'); ?>

				<?php if (lenvy_is_filtered()): ?>
					<?php get_template_part('template-parts/shop/filter-active'); ?>
				<?php endif; ?>

				<?php if (woocommerce_product_loop()): ?>

					<?php do_action('woocommerce_before_shop_loop'); ?>

					<div
					class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-8 mt-6"
					data-product-grid
					data-taxonomy="product_cat"
					data-term="<?php echo esc_attr($term->slug); ?>"
				>
						<?php while (have_posts()):
      	the_post(); ?>
							<?php get_template_part('template-parts/components/product-card', null, [
       	'product_id' => get_the_ID(),
       ]); ?>
						<?php
      endwhile; ?>
					</div>

					<?php do_action('woocommerce_after_shop_loop'); ?>

					<?php lenvy_pagination(); ?>

				<?php else: ?>

					<?php get_template_part('woocommerce/loop/no-products-found'); ?>

				<?php endif; ?>

				<?php
    // ── ACF SEO text below the grid ───────────────────────────────────
    $seo_text = lenvy_field('lenvy_cat_seo_text', 'term_' . $term->term_id);
    if ($seo_text): ?>
					<div class="mt-16 pt-10 border-t border-neutral-100 prose prose-sm max-w-none text-neutral-600">
						<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — ACF WYSIWYG returns safe HTML.
      echo wp_kses_post($seo_text); ?>
					</div>
				<?php endif;
    ?>

			</div><!-- .flex-1 -->

		</div><!-- .flex -->

	</div><!-- .lenvy-container -->
</main>

<?php get_template_part('template-parts/shop/filter-drawer'); ?>

<?php get_footer(); ?>
