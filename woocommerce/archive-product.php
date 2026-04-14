<?php
/**
 * Shop archive — Skins-inspired layout.
 *
 * Desktop: always-visible filter sidebar (left) + 3-column product grid (right).
 * Mobile:  full-width grid, filters via slide-out drawer.
 *
 * Overrides woocommerce/archive-product.php
 *
 * @package Lenvy
 * @see     WC templates/archive-product.php
 */

defined('ABSPATH') || exit();

get_header();
?>

<main id="primary" class="py-8 lg:py-12">

	<?php get_template_part('template-parts/shop/shop-intro'); ?>

	<div class="lenvy-container">

		<?php get_template_part('template-parts/shop/sort-bar'); ?>

		<?php if (lenvy_is_filtered()): ?>
			<?php get_template_part('template-parts/shop/filter-active'); ?>
		<?php endif; ?>

	</div>

	<?php if (woocommerce_product_loop()): ?>

		<?php do_action('woocommerce_before_shop_loop'); ?>

		<div class="lenvy-container mt-6">
			<div class="lg:flex lg:gap-10">

				<?php get_template_part('template-parts/shop/filter-sidebar'); ?>

				<div class="flex-1 min-w-0">
					<?php get_template_part('template-parts/components/product-grid', null, [
						'taxonomy' => '',
						'term'     => '',
					]); ?>

					<div class="mt-10">
						<?php lenvy_pagination(); ?>
					</div>
				</div>

			</div>
		</div>

		<?php do_action('woocommerce_after_shop_loop'); ?>

	<?php else: ?>

		<div class="lenvy-container mt-8">
			<?php get_template_part('woocommerce/loop/no-products-found'); ?>
		</div>

	<?php endif; ?>

</main>

<?php get_template_part('template-parts/shop/filter-drawer'); ?>

<?php get_footer(); ?>
