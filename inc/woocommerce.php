<?php
/**
 * WooCommerce compatibility — hook removals, loop config, wrapper overrides.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// ─── Remove default WC wrappers ───────────────────────────────────────────────

remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

// ─── Remove default sidebar ───────────────────────────────────────────────────

remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// ─── Remove default breadcrumb ────────────────────────────────────────────────

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// ─── Remove default results count and sort bar ────────────────────────────────

remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

// ─── Loop columns ────────────────────────────────────────────────────────────

add_filter('loop_shop_columns', function (): int {
	return 3;
});

// ─── Products per page ────────────────────────────────────────────────────────

add_filter(
	'loop_shop_per_page',
	function (): int {
		return 12;
	},
	20,
);

// ─── Disable product reviews ─────────────────────────────────────────────────

// Remove the Reviews tab from the single product tabs.
add_filter('woocommerce_product_tabs', function (array $tabs): array {
	unset($tabs['reviews']);
	return $tabs;
});

// Disable the WC reviews comment type so the form never appears.
add_filter('woocommerce_product_reviews_enabled', '__return_false');

// ─── Dequeue WC block styles ─────────────────────────────────────────────────

add_action(
	'wp_enqueue_scripts',
	function (): void {
		wp_dequeue_style('wc-blocks-style');
	},
	200,
);

// ─── Custom wrappers (add_action to match the removed hooks) ─────────────────

add_action(
	'woocommerce_before_main_content',
	function (): void {
		echo '<div id="primary" class="lenvy-wc-main">';
	},
	10,
);

add_action(
	'woocommerce_after_main_content',
	function (): void {
		echo '</div>';
	},
	10,
);
