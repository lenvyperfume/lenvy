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

// ── Shop banner ──────────────────────────────────────────────────────────────
$shop_page_id    = wc_get_page_id( 'shop' );
$banner_image    = $shop_page_id ? lenvy_field( 'lenvy_shop_banner_image', $shop_page_id ) : null;
$banner_image_id = is_array( $banner_image ) ? (int) ( $banner_image['ID'] ?? 0 ) : (int) $banner_image;
$heading         = lenvy_archive_title();
?>

<?php if ( $banner_image_id ) :
	$banner_img = wp_get_attachment_image( $banner_image_id, 'full', false, [
		'class'         => 'w-full h-full object-cover',
		'fetchpriority' => 'high',
		'loading'       => 'eager',
		'alt'           => esc_attr( $heading ),
	] ); ?>
	<div class="relative h-64 md:h-96 overflow-hidden">
		<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $banner_img; ?>
	</div>
<?php endif; ?>

<?php get_template_part( 'template-parts/homepage/brand-scroller' ); ?>

<main id="primary" class="py-12 lg:py-20">
	<div class="lenvy-container">

		<?php get_template_part('template-parts/components/breadcrumb'); ?>

		<?php if ( $heading ) : ?>
		<h1 class="text-3xl lg:text-4xl font-serif italic text-neutral-900 mt-3 mb-10">
			<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — lenvy_archive_title returns sanitized string
			echo $heading; ?>
		</h1>
		<?php endif; ?>

		<?php get_template_part('template-parts/shop/sort-bar'); ?>

		<?php if (lenvy_is_filtered()): ?>
			<?php get_template_part('template-parts/shop/filter-active'); ?>
		<?php endif; ?>

	</div>

	<?php if (woocommerce_product_loop()): ?>

		<?php do_action('woocommerce_before_shop_loop'); ?>

		<div class="lenvy-section mt-10">
			<?php get_template_part('template-parts/components/product-grid', null, [
				'taxonomy' => '',
				'term'     => '',
			]); ?>
		</div>

		<?php do_action('woocommerce_after_shop_loop'); ?>

		<div class="lenvy-container">
			<?php lenvy_pagination(); ?>
		</div>

	<?php else: ?>

		<div class="lenvy-container">
			<?php get_template_part('woocommerce/loop/no-products-found'); ?>
		</div>

	<?php endif; ?>

</main>

<?php get_template_part('template-parts/shop/filter-drawer'); ?>

<?php get_footer(); ?>
