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

// ─── Disable product reviews — complete ──────────────────────────────────────

// Remove star rating output from the shop loop.
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

// Disable WC's own reviews toggle (belt-and-suspenders alongside product_tabs filter above).
add_filter( 'woocommerce_enable_reviews', '__return_false' );

// Close comments and pings on all posts so the review form can never appear.
add_filter( 'comments_open', '__return_false', 20, 2 );
add_filter( 'pings_open', '__return_false', 20, 2 );

// ─── Checkout access control ──────────────────────────────────────────────────
// Non-logged-in users who arrive at checkout without ?guest=1 are redirected to
// the Account Choice page so they must explicitly pick Login / Register / Guest.

add_action(
	'template_redirect',
	function (): void {
		// Only act on the checkout page — never on order-received.
		if ( ! is_checkout() || is_order_received_page() ) {
			return;
		}

		// Logged-in users pass through unchecked.
		if ( is_user_logged_in() ) {
			return;
		}

		// Guest users who explicitly chose guest checkout pass through.
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( ! empty( $_GET['guest'] ) ) {
			return;
		}

		// Never redirect admin or AJAX requests.
		if ( is_admin() || wp_doing_ajax() ) {
			return;
		}

		// Don't intercept WooCommerce's own checkout form submission (POST).
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_POST['woocommerce-process-checkout-nonce'] ) ) {
			return;
		}

		wp_safe_redirect( lenvy_get_account_choice_url() );
		exit();
	},
);

// ─── Guest checkout ───────────────────────────────────────────────────────────
// When ?guest=1 is present, registration is not required at checkout.

add_filter(
	'woocommerce_checkout_registration_required',
	function ( bool $required ): bool {
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( ! empty( $_GET['guest'] ) ) {
			return false;
		}
		return true;
	},
);

// ─── Force classic shortcodes for cart & checkout ────────────────────────────
// WooCommerce 8.3+ uses Gutenberg blocks (wp:woocommerce/cart, wp:woocommerce/checkout)
// by default, which bypass our custom template overrides in woocommerce/cart/ and
// woocommerce/checkout/. Replace block content with classic shortcodes so our
// templates control the layout.

add_filter(
	'the_content',
	function (string $content): string {
		if (function_exists('is_cart') && is_cart()) {
			return '[woocommerce_cart]';
		}
		if (function_exists('is_checkout') && is_checkout() && !is_order_received_page()) {
			return '[woocommerce_checkout]';
		}
		return $content;
	},
	1,
);

// ─── Checkout: remove order notes (additional information) ───────────────────

add_filter('woocommerce_enable_order_notes_field', '__return_false');

// ─── Checkout: remove privacy policy text ────────────────────────────────────

remove_action('woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20);

// ─── Checkout: remove coupon notice at top (coupon is in cart sidebar) ───────

remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);

// ─── Dequeue all WC default styles ──────────────────────────────────────────
// The theme provides comprehensive custom styles for every WC element.
// WC's built-in woocommerce-general.css, woocommerce-layout.css and
// woocommerce-smallscreen.css use high-specificity selectors that fight
// our theme styles (e.g. .woocommerce form .form-row input.input-text).

add_filter('woocommerce_enqueue_styles', '__return_empty_array');

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
