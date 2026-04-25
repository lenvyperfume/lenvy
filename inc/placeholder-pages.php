<?php
/**
 * Placeholder routes — temporary landing pages for designs that haven't yet
 * been wired up to real CPT content.
 *
 * Currently:
 *   - /parfum-voorbeeld/  → templates/product-placeholder.php
 *
 * Hooks into `template_redirect` so we don't have to register rewrite rules
 * (and ask the user to flush permalinks). Remove this file once the designs
 * are powered by real WC products / posts.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

/**
 * Slug for the placeholder product preview page.
 *
 * @return string
 */
function lenvy_placeholder_product_slug(): string
{
	return 'parfum-voorbeeld';
}

/**
 * URL of the placeholder product preview page.
 *
 * @return string
 */
function lenvy_placeholder_product_url(): string
{
	return home_url('/' . lenvy_placeholder_product_slug() . '/');
}

/**
 * Intercept the matching request and load our template.
 */
add_action('template_redirect', static function () {
	$path = trim((string) wp_parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH), '/');

	if ($path !== lenvy_placeholder_product_slug()) {
		return;
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

	$template = get_theme_file_path('templates/product-placeholder.php');
	if (file_exists($template)) {
		include $template;
		exit;
	}
});
