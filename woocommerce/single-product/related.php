<?php
/**
 * Related products — grid using product-card component.
 *
 * Overrides woocommerce/single-product/related.php
 *
 * @package Lenvy
 * @see     WC templates/single-product/related.php
 *
 * @var WC_Product[] $related_products
 * @var array        $args
 */

defined('ABSPATH') || exit();

if ( empty( $related_products ) ) {
	return;
}
?>

<section class="mt-16 pt-10 border-t border-neutral-100">

	<h2 class="text-sm font-semibold uppercase tracking-widest text-neutral-800 mb-8">
		<?php esc_html_e( 'You may also like', 'lenvy' ); ?>
	</h2>

	<div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-10">
		<?php foreach ( $related_products as $related_product ) : ?>
			<?php
			get_template_part( 'template-parts/components/product-card', null, [
				'product_id' => $related_product->get_id(),
			] );
			?>
		<?php endforeach; ?>
	</div>

</section>
