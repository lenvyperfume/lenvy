<?php
/**
 * Compact horizontal product card — for related/upsell rows.
 *
 * Usage:
 *   get_template_part('template-parts/components/product-card-mini', null, [
 *     'product_id' => 42,
 *   ]);
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$product_id = (int) ( $args['product_id'] ?? get_the_ID() );

$product = wc_get_product( $product_id );

if ( ! $product || ! $product->is_visible() ) {
	return;
}

$permalink  = get_permalink( $product_id );
$title      = $product->get_name();
$price_html = $product->get_price_html();
$is_sale    = $product->is_on_sale();

$image_html = wp_get_attachment_image(
	(int) $product->get_image_id(),
	'woocommerce_thumbnail',
	false,
	[
		'class'   => 'w-full h-full object-cover',
		'loading' => 'lazy',
		'alt'     => esc_attr( $title ),
	]
);

if ( ! $image_html ) {
	$image_html = wc_placeholder_img( 'woocommerce_thumbnail', [ 'class' => 'w-full h-full object-cover' ] );
}
?>

<article class="flex items-center gap-4">
	<a
		href="<?php echo esc_url( $permalink ); ?>"
		class="shrink-0 w-20 h-20 overflow-hidden bg-neutral-50"
		tabindex="-1"
		aria-hidden="true"
	>
		<?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</a>

	<div class="flex-1 min-w-0">
		<a
			href="<?php echo esc_url( $permalink ); ?>"
			class="text-sm font-medium text-neutral-900 hover:text-black transition-colors duration-150 line-clamp-2 leading-snug"
		>
			<?php echo esc_html( $title ); ?>
		</a>

		<?php if ( $price_html ) : ?>
		<div class="mt-1 text-sm text-neutral-700">
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $price_html;
			?>
		</div>
		<?php endif; ?>
	</div>
</article>
