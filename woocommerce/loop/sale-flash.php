<?php
/**
 * Sale flash badge — product loop and single product.
 *
 * WC calls this via woocommerce_show_product_loop_sale_flash() and
 * woocommerce_show_product_sale_flash(). In our theme, product-card.php
 * handles loop badges internally so this template only fires when
 * WC's own hooks call it (e.g. on single product pages via third-party
 * plugins, or if WC hooks are re-added).
 *
 * Output uses the native .onsale class so _woocommerce.scss styles apply.
 *
 * @package Lenvy
 * @see     WC templates/loop/sale-flash.php
 */

defined( 'ABSPATH' ) || exit();

global $product;

if ( ! $product || ! $product->is_on_sale() ) {
	return;
}

// Skip variation objects — sale flash belongs on the parent only.
if ( 'variation' === $product->get_type() ) {
	return;
}
?>

<span class="onsale">
	<?php esc_html_e( 'Sale', 'lenvy' ); ?>
</span>
