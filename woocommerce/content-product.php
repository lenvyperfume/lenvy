<?php
/**
 * Product loop item — delegates to product-card component.
 *
 * Overrides woocommerce/content-product.php
 *
 * @package Lenvy
 * @see     WC templates/content-product.php
 */

defined('ABSPATH') || exit();

get_template_part('template-parts/components/product-card', null, [
	'product_id' => get_the_ID(),
]);
