<?php
/**
 * Template helper functions.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// ─── ACF field helpers ─────────────────────────────────────────────────────────

/**
 * Get an ACF field value with a null fallback when ACF is not active.
 *
 * @param  string          $key     Field name or key.
 * @param  int|string|null $post_id Post ID, 'options', or null for current post.
 * @return mixed
 */
function lenvy_field(string $key, int|string|null $post_id = null): mixed {
	if (!function_exists('get_field')) {
		return null;
	}

	return get_field($key, $post_id ?? false);
}

/**
 * Echo an ACF text field value, HTML-escaped.
 * Only outputs string values — silently skips arrays, objects, etc.
 *
 * @param  string          $key     Field name or key.
 * @param  int|string|null $post_id Post ID, 'options', or null for current post.
 */
function lenvy_the_field(string $key, int|string|null $post_id = null): void {
	$value = lenvy_field($key, $post_id);

	if (empty($value) || !is_string($value)) {
		return;
	}

	echo esc_html($value);
}

// ─── Image helper ─────────────────────────────────────────────────────────────

/**
 * Return a wp_get_attachment_image() string, or an empty string if the
 * attachment ID is falsy.
 *
 * Accepts an ACF image array (return format "Array") or a plain integer ID.
 *
 * @param  int|array|false $attachment_id WordPress attachment ID or ACF image array.
 * @param  string          $size          Image size name or [width, height] array.
 * @param  string          $class         Additional class attribute value.
 * @param  string          $alt           Alt text override. When empty, falls back to the attachment's alt field.
 * @return string
 */
function lenvy_get_image(int|array|false $attachment_id, string $size = 'thumbnail', string $class = '', string $alt = ''): string {
	if (is_array($attachment_id)) {
		$attachment_id = (int) ($attachment_id['ID'] ?? 0);
	}

	if (!$attachment_id) {
		return '';
	}

	$attrs = [];

	if ($class) {
		$attrs['class'] = $class;
	}

	if ('' !== $alt) {
		$attrs['alt'] = $alt;
	}

	return wp_get_attachment_image($attachment_id, $size, false, $attrs) ?: '';
}

// ─── Icon helper ──────────────────────────────────────────────────────────────

/**
 * Render the icon template part inline.
 *
 * @param  string $name  Icon name (must match a file in assets/icons/).
 * @param  string $class Additional Tailwind classes (e.g. 'text-neutral-700').
 * @param  string $size  xs|sm|md|lg|xl
 * @param  string $label Accessible label — required for standalone icon buttons.
 */
function lenvy_icon(string $name, string $class = '', string $size = 'md', string $label = ''): void {
	get_template_part('template-parts/components/icon', null, [
		'name' => $name,
		'class' => $class,
		'size' => $size,
		'label' => $label,
	]);
}

// ─── Breadcrumb helper ────────────────────────────────────────────────────────

/**
 * Return the breadcrumb trail as an indexed array of [name, url] pairs.
 *
 * Delegates to WooCommerce's wc_get_breadcrumb() on WC pages, falls back to
 * a manual implementation for standard WordPress pages.
 *
 * @return array<int, array{0: string, 1: string}>
 */
function lenvy_get_breadcrumb_items(): array {
	// WooCommerce provides a ready-made breadcrumb array.
	if (function_exists('wc_get_breadcrumb')) {
		return (array) wc_get_breadcrumb();
	}

	$crumbs = [];
	$crumbs[] = [get_bloginfo('name'), home_url('/')];

	if (is_singular()) {
		global $post;

		if ($post->post_parent) {
			$ancestors = array_reverse(get_post_ancestors($post->ID));
			foreach ($ancestors as $ancestor_id) {
				$crumbs[] = [get_the_title($ancestor_id), (string) get_permalink($ancestor_id)];
			}
		}

		$crumbs[] = [get_the_title(), ''];
	} elseif (is_tax() || is_category() || is_tag()) {
		$crumbs[] = [(string) single_term_title('', false), ''];
	} elseif (is_post_type_archive()) {
		$crumbs[] = [(string) post_type_archive_title('', false), ''];
	} elseif (is_search()) {
		$crumbs[] = [sprintf(__('Search: %s', 'lenvy'), get_search_query()), ''];
	} elseif (is_404()) {
		$crumbs[] = [__('Page not found', 'lenvy'), ''];
	}

	return $crumbs;
}

// ─── Archive title helper ─────────────────────────────────────────────────────

/**
 * Return a clean archive page title — without WordPress's default
 * "Category: ", "Tag: ", "Author: " prefixes.
 *
 * @return string
 */
function lenvy_archive_title(): string {
	if (is_search()) {
		return sprintf(
			/* translators: %s: search term */
			__('Results for: %s', 'lenvy'),
			'<em>' . esc_html(get_search_query()) . '</em>',
		);
	}

	if (is_tax() || is_category() || is_tag()) {
		return (string) single_term_title('', false);
	}

	if (is_post_type_archive()) {
		return (string) post_type_archive_title('', false);
	}

	return (string) get_the_archive_title();
}

// ─── Pagination helper ────────────────────────────────────────────────────────

/**
 * Render the pagination template part.
 * Handles both WooCommerce and standard WordPress archives.
 */
function lenvy_pagination(): void {
	get_template_part('template-parts/components/pagination');
}

// ─── Shop / filter helpers ────────────────────────────────────────────────────

/**
 * Read a filter query var that may arrive as a PHP array (name="var[]") or as
 * a comma-separated string (hand-crafted URL). Returns sanitized slug array.
 *
 * @param  string $var  Query var name, e.g. 'filter_cat'.
 * @return string[]
 */
function lenvy_parse_filter_slugs(string $var): array {
	// phpcs:ignore WordPress.Security.NonceVerification
	$raw = $_GET[$var] ?? '';

	if (is_array($raw)) {
		return array_values(array_filter(array_map('sanitize_title', $raw)));
	}

	$raw = sanitize_text_field(wp_unslash((string) $raw));

	if ('' === $raw) {
		return [];
	}

	return array_values(array_filter(array_map('sanitize_title', explode(',', $raw))));
}

/**
 * Return [min_price, max_price] from all published products.
 * Result is cached for 6 hours via a transient.
 *
 * @return array{float, float}
 */
function lenvy_get_min_max_price(): array {
	$cached = get_transient('lenvy_min_max_price');

	if (false !== $cached && is_array($cached)) {
		return $cached;
	}

	global $wpdb;

	// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$row = $wpdb->get_row(
		"SELECT MIN(CAST(meta_value AS DECIMAL(10,2))), MAX(CAST(meta_value AS DECIMAL(10,2)))
		 FROM {$wpdb->postmeta}
		 INNER JOIN {$wpdb->posts} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
		 WHERE meta_key = '_price'
		   AND meta_value != ''
		   AND {$wpdb->posts}.post_status = 'publish'
		   AND {$wpdb->posts}.post_type = 'product'",
		ARRAY_N,
	);

	$result = [(float) ($row[0] ?? 0), (float) ($row[1] ?? 0)];

	set_transient('lenvy_min_max_price', $result, 6 * HOUR_IN_SECONDS);

	return $result;
}

// Invalidate price cache when a product is saved.
add_action('save_post_product', function (): void {
	delete_transient('lenvy_min_max_price');
});

/**
 * Return terms for a filter taxonomy, cached in the WP object cache.
 *
 * Uses the `lenvy` cache group (compatible with Redis / Memcached). Falls back
 * to a 12-hour transient when a persistent object cache is unavailable.
 * Cache is invalidated automatically on term create/edit/delete.
 *
 * @param  string $taxonomy  Taxonomy slug (e.g. 'product_brand').
 * @return WP_Term[]
 */
function lenvy_get_filter_terms(string $taxonomy): array {
	$cache_key = 'filter_terms_' . $taxonomy;
	$group = 'lenvy';

	$cached = wp_cache_get($cache_key, $group);

	if (false !== $cached) {
		return (array) $cached;
	}

	$terms = get_terms([
		'taxonomy' => $taxonomy,
		'hide_empty' => true,
		'orderby' => 'name',
		'order' => 'ASC',
	]);

	if (is_wp_error($terms) || !is_array($terms)) {
		return [];
	}

	wp_cache_set($cache_key, $terms, $group, 12 * HOUR_IN_SECONDS);

	return $terms;
}

// Invalidate filter term cache when any term is created, edited, or deleted.
add_action('created_term', 'lenvy_flush_filter_term_cache', 10, 3);
add_action('edited_term', 'lenvy_flush_filter_term_cache', 10, 3);
add_action('delete_term', 'lenvy_flush_filter_term_cache', 10, 3);

/**
 * @param int    $term_id   Unused.
 * @param int    $tt_id     Unused.
 * @param string $taxonomy  Taxonomy slug.
 */
function lenvy_flush_filter_term_cache(int $term_id, int $tt_id, string $taxonomy): void {
	wp_cache_delete('filter_terms_' . $taxonomy, 'lenvy');
}

/**
 * Return a structured array describing all currently active filters.
 *
 * Each entry has:
 *   label        — human-readable chip label
 *   remove_args  — assoc array to pass to add_query_arg() to remove this filter
 *
 * @return array<int, array{label: string, remove_args: array<string, mixed>}>
 */
function lenvy_get_active_filters(): array {
	$active = [];

	$taxonomy_map = [
		'filter_brand' => __('Brand', 'lenvy'),
		'filter_cat' => __('Category', 'lenvy'),
		'filter_gender' => __('Gender', 'lenvy'),
		'filter_family' => __('Family', 'lenvy'),
		'filter_conc' => __('Concentration', 'lenvy'),
		'filter_volume' => __('Volume', 'lenvy'),
	];

	foreach ($taxonomy_map as $var => $group_label) {
		$slugs = lenvy_parse_filter_slugs($var);

		if (empty($slugs)) {
			continue;
		}

		foreach ($slugs as $slug) {
			// Rebuild the array with this slug removed for the remove link.
			$remaining = array_values(array_diff($slugs, [$slug]));

			$active[] = [
				'label' => $group_label . ': ' . str_replace('-', ' ', $slug),
				'remove_args' => [$var => $remaining ?: false],
			];
		}
	}

	// phpcs:ignore WordPress.Security.NonceVerification
	if (!empty($_GET['filter_available'])) {
		$active[] = [
			'label' => __('In stock', 'lenvy'),
			'remove_args' => ['filter_available' => false],
		];
	}

	// phpcs:ignore WordPress.Security.NonceVerification
	if (!empty($_GET['filter_onsale'])) {
		$active[] = [
			'label' => __('On sale', 'lenvy'),
			'remove_args' => ['filter_onsale' => false],
		];
	}

	// phpcs:ignore WordPress.Security.NonceVerification
	$min = isset($_GET['min_price']) ? (float) $_GET['min_price'] : null;
	// phpcs:ignore WordPress.Security.NonceVerification
	$max = isset($_GET['max_price']) ? (float) $_GET['max_price'] : null;
	[$global_min, $global_max] = lenvy_get_min_max_price();

	if ((null !== $min && $min > $global_min) || (null !== $max && $max < $global_max)) {
		$active[] = [
			'label' =>
				wp_strip_all_tags(wc_price($min ?? $global_min)) .
				' – ' .
				wp_strip_all_tags(wc_price($max ?? $global_max)),
			'remove_args' => ['min_price' => false, 'max_price' => false],
		];
	}

	return $active;
}

/**
 * Return true if any filter query var is currently active.
 *
 * @return bool
 */
function lenvy_is_filtered(): bool {
	return !empty(lenvy_get_active_filters());
}

// ─── Homepage product queries ─────────────────────────────────────────────

/**
 * Return WC_Product objects for homepage carousels.
 *
 * @param  string $type  'bestsellers' | 'new' | 'sale'
 * @param  int    $limit Maximum products to return.
 * @return WC_Product[]
 */
function lenvy_get_homepage_products(string $type, int $limit = 12): array {
	if (!function_exists('wc_get_products')) {
		return [];
	}

	$args = [
		'status'     => 'publish',
		'visibility' => 'visible',
		'limit'      => $limit,
	];

	switch ($type) {
		case 'bestsellers':
			$args['orderby'] = 'popularity';
			break;

		case 'new':
			$args['orderby'] = 'date';
			$args['order']   = 'DESC';
			break;

		case 'sale':
			$sale_ids = wc_get_product_ids_on_sale();
			if (empty($sale_ids)) {
				return [];
			}
			$args['include'] = $sale_ids;
			$args['orderby'] = 'date';
			$args['order']   = 'DESC';
			break;

		default:
			return [];
	}

	return wc_get_products($args);
}

// ─── Account helpers ──────────────────────────────────────────────────────────

/**
 * Return the URL of the Account Choice page.
 *
 * Uses home_url() so WPML can filter it correctly for the active language.
 *
 * @return string
 */
function lenvy_get_account_choice_url(): string {
	return esc_url( home_url( '/account-choice/' ) );
}
