<?php
/**
 * Shop archive — full-width product grid with drawer-based filters.
 *
 * Overrides woocommerce/archive-product.php
 *
 * @package Lenvy
 * @see     WC templates/archive-product.php
 */

defined('ABSPATH') || exit();

get_header();
?>

<main id="primary" class="py-10 lg:py-16">
	<div class="lenvy-container">

		<?php get_template_part('template-parts/components/breadcrumb'); ?>

		<?php
		$heading = lenvy_archive_title();
		if ($heading): ?>
		<h1 class="text-3xl font-serif italic text-neutral-900 mt-2 mb-8">
			<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — lenvy_archive_title returns sanitized string
			echo $heading; ?>
		</h1>
		<?php endif; ?>

		<?php get_template_part('template-parts/shop/sort-bar'); ?>

		<?php if (lenvy_is_filtered()): ?>
			<?php get_template_part('template-parts/shop/filter-active'); ?>
		<?php endif; ?>

		<?php if (woocommerce_product_loop()): ?>

			<?php do_action('woocommerce_before_shop_loop'); ?>

			<div class="mt-8">
				<?php get_template_part('template-parts/components/product-grid', null, [
					'taxonomy' => '',
					'term'     => '',
				]); ?>
			</div>

			<?php do_action('woocommerce_after_shop_loop'); ?>

			<?php lenvy_pagination(); ?>

		<?php else: ?>

			<?php get_template_part('woocommerce/loop/no-products-found'); ?>

		<?php endif; ?>

	</div>
</main>

<?php get_template_part('template-parts/shop/filter-drawer'); ?>

<?php get_footer(); ?>
