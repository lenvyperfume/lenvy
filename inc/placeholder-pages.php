<?php
/**
 * Placeholder routes — temporary landing pages for designs that haven't yet
 * been wired up to real CPT content.
 *
 * Currently:
 *   - /parfum-voorbeeld/  → templates/product-placeholder.php
 *   - /merken/            → templates/brands-placeholder.php
 *
 * Hooks into `template_redirect` so we don't have to register rewrite rules
 * (and ask the user to flush permalinks). Remove this file once the designs
 * are powered by real WC products / posts.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

/**
 * Map of placeholder slug → template file (relative to the theme root).
 *
 * @return array<string,string>
 */
function lenvy_placeholder_routes(): array
{
	return [
		'parfum-voorbeeld' => 'templates/product-placeholder.php',
		'merken'           => 'templates/brands-placeholder.php',
		'winkelwagen'      => 'templates/cart-placeholder.php',
		'afrekenen'        => 'templates/checkout-placeholder.php',
		'bedankt'          => 'templates/thankyou-placeholder.php',
		'mijn-account'     => 'templates/account-placeholder.php',
	];
}

/**
 * URL helper — placeholder product preview page.
 */
function lenvy_placeholder_product_url(): string
{
	return home_url('/parfum-voorbeeld/');
}

/**
 * URL helper — placeholder brands index page.
 */
function lenvy_placeholder_brands_url(): string
{
	return home_url('/merken/');
}

/**
 * URL helper — placeholder cart page.
 */
function lenvy_placeholder_cart_url(): string
{
	return home_url('/winkelwagen/');
}

/**
 * URL helper — placeholder checkout page.
 */
function lenvy_placeholder_checkout_url(): string
{
	return home_url('/afrekenen/');
}

/**
 * URL helper — placeholder thank-you / order-received page.
 */
function lenvy_placeholder_thankyou_url(): string
{
	return home_url('/bedankt/');
}

/**
 * URL helper — account / my-account page.
 *
 * Returns the WC My Account page if it's configured (recommended slug:
 * `mijn-account`), or the placeholder URL while the WC page isn't set
 * up yet. Always points at /mijn-account/ — the canonical account URL
 * across the site.
 */
function lenvy_account_url(): string
{
	$wc_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : '';
	return $wc_url ?: home_url('/mijn-account/');
}

/**
 * Repoint WooCommerce's cart URL to the placeholder cart while we don't
 * have real WC pages set up. Removed once /cart/ is wired up properly.
 */
add_filter('woocommerce_get_cart_url', static fn() => lenvy_placeholder_cart_url(), 5);

/**
 * Repoint WooCommerce's checkout URL to the placeholder while we don't
 * have real WC pages set up. Removed once /afrekenen/ is wired up properly.
 */
add_filter('woocommerce_get_checkout_url', static fn() => lenvy_placeholder_checkout_url(), 5);

/**
 * After logout, send users to /mijn-account/ regardless of where the WC
 * My Account page lives. If the WC page slug is renamed to `mijn-account`
 * this matches WC's default behaviour anyway; until then, it forces
 * consistency.
 */
add_filter('woocommerce_logout_default_redirect_url', static fn() => lenvy_account_url());

/**
 * Intercept the matching request and load our template.
 *
 * Priority 30 so it runs *after* WooCommerce's form handlers
 * (`WC_Form_Handler::process_login` / `process_registration`) at
 * priority 20. Lets WC handle form POSTs first — success redirects
 * before we run, failure adds a notice and falls through.
 */
add_action('template_redirect', static function () {
	$path = trim((string) wp_parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH), '/');

	$routes = lenvy_placeholder_routes();
	if (!isset($routes[$path])) {
		return;
	}

	// Self-healing: if the WC My Account page has been configured at the
	// same URL the placeholder owns, step aside and let WC render its
	// page (which uses our form-login.php / my-account.php overrides).
	if ($path === 'mijn-account' && function_exists('wc_get_page_permalink')) {
		$wc_url  = wc_get_page_permalink('myaccount');
		$wc_path = $wc_url ? trim((string) wp_parse_url($wc_url, PHP_URL_PATH), '/') : '';
		if ($wc_path === $path) {
			return;
		}
	}

	// Clear the 404 state so get_header()/get_footer() don't render an error
	// page. We deliberately *don't* flip is_page / is_singular — there is no
	// real $post backing this URL, and WP core helpers (body_class, etc.)
	// would emit warnings trying to read $post->ID / ->post_type.
	global $wp_query;
	if ($wp_query instanceof WP_Query) {
		$wp_query->is_404 = false;
	}
	status_header(200);
	nocache_headers();

	$template = get_theme_file_path($routes[$path]);
	if (file_exists($template)) {
		include $template;
		exit;
	}
}, 30);
