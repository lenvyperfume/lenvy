<?php
/**
 * Shop archive — sidebar + product grid layout.
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
	<div class="lenvy-container">

		<?php
  // ── Page heading ──────────────────────────────────────────────────────
  $heading = lenvy_archive_title();
  if ($heading): ?>
			<h1 class="text-2xl font-serif italic text-neutral-900 mb-6">
				<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — lenvy_archive_title returns sanitized string + optional <em>.
    echo $heading; ?>
			</h1>
			<?php endif;

  // ── Breadcrumb ────────────────────────────────────────────────────────
  get_template_part('template-parts/components/breadcrumb');
  ?>

		<div class="flex gap-8 mt-6">

			<?php get_template_part('template-parts/shop/filter-sidebar'); ?>

			<div class="flex-1 min-w-0">

				<?php get_template_part('template-parts/shop/sort-bar'); ?>

				<?php if (lenvy_is_filtered()): ?>
					<?php get_template_part('template-parts/shop/filter-active'); ?>
				<?php endif; ?>

				<?php if (woocommerce_product_loop()): ?>

					<?php do_action('woocommerce_before_shop_loop'); ?>

					<div class="grid grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-10 mt-6" data-product-grid data-taxonomy="" data-term="">
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

					<div class="mt-16 text-center py-20">
						<p class="text-sm text-neutral-500">
							<?php esc_html_e('No products were found matching your selection.', 'lenvy'); ?>
						</p>
						<?php if (lenvy_is_filtered()): ?>
							<a
								href="<?php echo esc_url(strtok((string) $_SERVER['REQUEST_URI'], '?')); ?>"
								class="inline-block mt-4 text-xs font-medium underline underline-offset-4 hover:text-neutral-600 transition-colors duration-150"
							>
								<?php esc_html_e('Clear all filters', 'lenvy'); ?>
							</a>
						<?php endif; ?>
					</div>

				<?php endif; ?>

			</div><!-- .flex-1 -->

		</div><!-- .flex -->

	</div><!-- .lenvy-container -->
</main>

<?php get_template_part('template-parts/shop/filter-drawer'); ?>

<?php get_footer(); ?>
