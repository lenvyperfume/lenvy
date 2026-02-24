<?php
/**
 * Single product gallery — main image + thumbnail strip.
 *
 * Overrides woocommerce/single-product/product-image.php
 *
 * @package Lenvy
 * @see     WC templates/single-product/product-image.php
 */

defined('ABSPATH') || exit();

global $product;

$main_id     = $product->get_image_id();
$gallery_ids = $product->get_gallery_image_ids();
$all_ids     = $main_id ? array_merge( [ (int) $main_id ], array_map( 'intval', $gallery_ids ) ) : array_map( 'intval', $gallery_ids );

if ( empty( $all_ids ) ) {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — WC placeholder is safe
	echo wc_placeholder_img( 'woocommerce_single', [ 'class' => 'w-full h-full object-cover' ] );
	return;
}
?>

<!-- Main image -->
<div class="relative overflow-hidden bg-neutral-50 aspect-product">
	<?php
	echo wp_get_attachment_image(
		$all_ids[0],
		'woocommerce_single',
		false,
		[
			'class'              => 'w-full h-full object-cover transition-opacity duration-200',
			'data-gallery-main'  => '',
			'fetchpriority'      => 'high',
			'loading'            => 'eager',
			'alt'                => esc_attr( $product->get_name() ),
		]
	); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>

	<?php if ( $product->is_on_sale() ) : ?>
	<div class="absolute top-4 left-4 z-10">
		<?php
		get_template_part( 'template-parts/components/badge', null, [
			'text'    => __( 'Sale', 'lenvy' ),
			'variant' => 'sale',
		] );
		?>
	</div>
	<?php endif; ?>

	<?php if ( ! $product->is_in_stock() ) : ?>
	<div class="absolute inset-0 bg-white/60 flex items-center justify-center">
		<span class="text-xs font-medium uppercase tracking-widest text-neutral-500 bg-white px-4 py-2">
			<?php esc_html_e( 'Out of stock', 'lenvy' ); ?>
		</span>
	</div>
	<?php endif; ?>
</div>

<!-- Thumbnail strip -->
<?php if ( count( $all_ids ) > 1 ) : ?>
<div class="flex gap-2 mt-3 overflow-x-auto scrollbar-hide" data-gallery-thumbs>
	<?php foreach ( $all_ids as $i => $img_id ) : ?>
		<?php $full_src = wp_get_attachment_image_url( $img_id, 'woocommerce_single' ); ?>
		<button
			type="button"
			class="shrink-0 w-16 h-16 overflow-hidden bg-neutral-50 border-2 transition-colors duration-150 <?php echo 0 === $i ? 'border-black' : 'border-transparent hover:border-neutral-300'; ?>"
			data-gallery-thumb
			data-src="<?php echo esc_url( (string) $full_src ); ?>"
			aria-label="<?php echo esc_attr( sprintf( __( 'View image %d', 'lenvy' ), $i + 1 ) ); ?>"
		>
			<?php
			echo wp_get_attachment_image(
				$img_id,
				[80, 80],
				false,
				[
					'class'   => 'w-full h-full object-cover',
					'loading' => 'lazy',
					'alt'     => '',
				]
			); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</button>
	<?php endforeach; ?>
</div>
<?php endif; ?>
