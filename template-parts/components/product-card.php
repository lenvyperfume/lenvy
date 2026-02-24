<?php
/**
 * Product card component — image-dominant portrait card with hover quick-add.
 *
 * Usage:
 *   get_template_part('template-parts/components/product-card', null, [
 *     'product_id'   => 42,
 *     'show_brand'   => true,           // show brand name above product name
 *     'image_size'   => 'woocommerce_thumbnail',
 *   ]);
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$product_id = (int) ( $args['product_id'] ?? get_the_ID() );
$show_brand = $args['show_brand'] ?? true;
$image_size = $args['image_size'] ?? 'woocommerce_thumbnail';

$product = wc_get_product( $product_id );

if ( ! $product || ! $product->is_visible() ) {
	return;
}

$permalink  = get_permalink( $product_id );
$title      = $product->get_name();
$is_sale    = $product->is_on_sale();
$is_oos     = ! $product->is_in_stock();
$price_html = $product->get_price_html();

// Badge — OOS wins over sale.
$badge_text    = '';
$badge_variant = 'custom';

$custom_badge = lenvy_field( 'lenvy_product_badge_text', $product_id );

if ( $is_oos ) {
	$badge_text    = __( 'Out of stock', 'lenvy' );
	$badge_variant = 'oos';
} elseif ( $custom_badge ) {
	$badge_text    = (string) $custom_badge;
	$badge_variant = 'new';
} elseif ( $is_sale ) {
	$badge_text    = __( 'Sale', 'lenvy' );
	$badge_variant = 'sale';
}

// Brand.
$brand_name = '';
if ( $show_brand ) {
	$brands = get_the_terms( $product_id, 'product_brand' );
	if ( $brands && ! is_wp_error( $brands ) ) {
		$brand_name = $brands[0]->name;
	}
}

// Image.
$image_html = wp_get_attachment_image(
	(int) $product->get_image_id(),
	$image_size,
	false,
	[
		'class'   => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-105',
		'loading' => 'lazy',
		'alt'     => esc_attr( $title ),
	]
);

if ( ! $image_html ) {
	$image_html = wc_placeholder_img( $image_size, [ 'class' => 'w-full h-full object-cover' ] );
}

// Add-to-cart data.
$add_to_cart_url = $product->add_to_cart_url();
$add_to_cart_txt = $product->add_to_cart_text();
$is_purchasable  = $product->is_purchasable() && $product->is_in_stock();
$product_type    = $product->get_type();
$is_simple       = ( 'simple' === $product_type );
?>

<article
	class="group relative flex flex-col"
	data-product-id="<?php echo esc_attr( $product_id ); ?>"
>
	<!-- Image wrapper -->
	<a
		href="<?php echo esc_url( $permalink ); ?>"
		class="relative block overflow-hidden bg-neutral-50 aspect-product"
		tabindex="-1"
		aria-hidden="true"
	>
		<?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — safe output from wp_get_attachment_image ?>

		<!-- Badges -->
		<?php if ( $badge_text ) : ?>
		<div class="absolute top-3 left-3 z-10">
			<?php
			get_template_part( 'template-parts/components/badge', null, [
				'text'    => $badge_text,
				'variant' => $badge_variant,
			] );
			?>
		</div>
		<?php endif; ?>

		<!-- Quick-add overlay (simple products only, in stock) -->
		<?php if ( $is_simple && $is_purchasable ) : ?>
		<div class="absolute inset-x-0 bottom-0 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out z-10 p-3">
			<button
				type="button"
				class="w-full bg-black text-white text-xs font-medium uppercase tracking-widest py-3 hover:bg-neutral-800 transition-colors duration-150"
				data-quick-add
				data-product-id="<?php echo esc_attr( $product_id ); ?>"
				data-add-to-cart-url="<?php echo esc_url( $add_to_cart_url ); ?>"
				aria-label="<?php echo esc_attr( sprintf( __( 'Add %s to cart', 'lenvy' ), $title ) ); ?>"
			>
				<?php echo esc_html( $add_to_cart_txt ); ?>
			</button>
		</div>
		<?php elseif ( ! $is_simple ) : ?>
		<div class="absolute inset-x-0 bottom-0 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out z-10 p-3">
			<a
				href="<?php echo esc_url( $permalink ); ?>"
				class="block w-full bg-black text-white text-xs font-medium uppercase tracking-widest py-3 text-center hover:bg-neutral-800 transition-colors duration-150"
			>
				<?php esc_html_e( 'Select options', 'lenvy' ); ?>
			</a>
		</div>
		<?php endif; ?>
	</a>

	<!-- Card body -->
	<div class="pt-3 flex flex-col gap-0.5 flex-1">

		<?php if ( $brand_name ) : ?>
		<span class="text-xs text-neutral-400 uppercase tracking-widest line-clamp-1">
			<?php echo esc_html( $brand_name ); ?>
		</span>
		<?php endif; ?>

		<a
			href="<?php echo esc_url( $permalink ); ?>"
			class="text-sm font-medium text-neutral-900 hover:text-black leading-snug line-clamp-2 transition-colors duration-150"
		>
			<?php echo esc_html( $title ); ?>
		</a>

		<?php if ( $price_html ) : ?>
		<div class="mt-1 text-sm font-semibold text-neutral-900">
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — WC returns safe HTML.
			echo $price_html;
			?>
		</div>
		<?php endif; ?>

	</div>
</article>
