<?php
/**
 * Related products — uses product-grid component.
 *
 * Overrides woocommerce/single-product/related.php
 *
 * @package Lenvy
 * @see     WC templates/single-product/related.php
 *
 * @var WC_Product[] $related_products
 */

defined('ABSPATH') || exit();

if (empty($related_products)) {
	return;
}

$product_ids = array_map(function ($p) {
	return $p->get_id();
}, $related_products);
?>

<section class="mt-24 lg:mt-32 pt-14 border-t border-neutral-100">

	<h2 class="text-2xl md:text-3xl font-serif italic text-neutral-900 mb-10">
		<?php esc_html_e('Misschien vind je dit ook leuk', 'lenvy'); ?>
	</h2>

	<?php get_template_part('template-parts/components/product-grid', null, [
		'product_ids' => $product_ids,
		'columns'     => 4,
	]); ?>

</section>
