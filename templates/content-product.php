<?php
/**
 * Template part for displaying a WooCommerce product card.
 *
 * Used on the front-page featured products section and anywhere a
 * manual product loop is rendered outside of WooCommerce's own templates.
 * WooCommerce's own archive/single templates are handled by WooCommerce
 * directly; use /woocommerce/ overrides for those.
 *
 * @package Lenvy
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

global $product;

// Ensure we have a WC product object.
if ( ! $product instanceof WC_Product ) {
	$product = wc_get_product( get_the_ID() );
}

if ( ! $product ) {
	return;
}
?>

<article <?php post_class( 'product-card group relative flex flex-col' ); ?>>

	<!-- Thumbnail -->
	<a href="<?php echo esc_url( get_permalink() ); ?>" class="block overflow-hidden rounded-sm bg-neutral-100 aspect-[3/4] mb-4">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'woocommerce_single', [ 'class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105' ] ); ?>
		<?php else : ?>
			<?php echo wc_placeholder_img( 'woocommerce_single', [ 'class' => 'w-full h-full object-cover' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php endif; ?>

		<?php if ( $product->is_on_sale() ) : ?>
			<?php echo wp_kses_post( wc_get_product_class( '', $product ) ); ?>
			<span class="absolute top-3 left-3 bg-neutral-900 text-white text-[10px] font-bold uppercase tracking-widest px-2 py-1">
				<?php esc_html_e( 'Sale', 'lenvy' ); ?>
			</span>
		<?php endif; ?>
	</a>

	<!-- Info -->
	<div class="flex flex-col flex-1">
		<p class="text-[11px] uppercase tracking-[0.15em] text-neutral-400 mb-1">
			<?php echo wc_get_product_category_list( $product->get_id(), ', ' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</p>

		<h3 class="text-sm font-semibold text-neutral-900 leading-snug mb-2">
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="hover:text-neutral-600 transition-colors">
				<?php the_title(); ?>
			</a>
		</h3>

		<div class="mt-auto flex items-center justify-between gap-2 pt-3">
			<span class="text-sm font-semibold text-neutral-900">
				<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>

			<?php
			woocommerce_template_loop_add_to_cart(
				[
					'quantity' => 1,
					'class'    => implode(
						' ',
						array_filter(
							[
								'button',
								'product_type_' . $product->get_type(),
								$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
								$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
							]
						)
					),
				]
			);
			?>
		</div>
	</div>

</article>
