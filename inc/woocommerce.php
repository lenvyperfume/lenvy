<?php
/**
 * WooCommerce compatibility & customisation.
 *
 * @package Lenvy
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

// ─── Remove default WooCommerce wrappers ────────────────────────────────────

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Open our custom WooCommerce wrapper.
 */
function lenvy_woocommerce_wrapper_before(): void {
	?>
	<main id="primary" class="site-main woocommerce-main py-12">
		<div class="container mx-auto px-4 max-w-screen-xl">
	<?php
}
add_action( 'woocommerce_before_main_content', 'lenvy_woocommerce_wrapper_before', 10 );

/**
 * Close our custom WooCommerce wrapper.
 */
function lenvy_woocommerce_wrapper_after(): void {
	?>
		</div><!-- .container -->
	</main><!-- #primary -->
	<?php
}
add_action( 'woocommerce_after_main_content', 'lenvy_woocommerce_wrapper_after', 10 );

// ─── Breadcrumbs ────────────────────────────────────────────────────────────

/**
 * Move WooCommerce breadcrumbs above the page title.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
add_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 6 );

// ─── Product loop columns ───────────────────────────────────────────────────

/**
 * Change the number of products displayed per row.
 *
 * @return int
 */
function lenvy_woocommerce_loop_columns(): int {
	return 3;
}
add_filter( 'loop_shop_columns', 'lenvy_woocommerce_loop_columns' );

/**
 * Change the number of products displayed per page.
 *
 * @return int
 */
function lenvy_woocommerce_products_per_page(): int {
	return 12;
}
add_filter( 'loop_shop_per_page', 'lenvy_woocommerce_products_per_page', 20 );

// ─── Sale badge ─────────────────────────────────────────────────────────────

/**
 * Replace the default WooCommerce sale flash.
 *
 * @return string
 */
function lenvy_woocommerce_sale_flash(): string {
	return '<span class="onsale absolute top-2 left-2 bg-black text-white text-xs font-semibold uppercase tracking-widest px-2 py-1 z-10">'
		. esc_html__( 'Sale', 'lenvy' )
		. '</span>';
}
add_filter( 'woocommerce_sale_flash', 'lenvy_woocommerce_sale_flash' );

// ─── Sidebar ─────────────────────────────────────────────────────────────────

/**
 * Remove the default WooCommerce sidebar.
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
