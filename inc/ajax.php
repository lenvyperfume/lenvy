<?php
/**
 * AJAX handlers.
 *
 * Registers wp_ajax / wp_ajax_nopriv actions for:
 *   lenvy_add_to_cart     — quick add to cart from product cards
 *   lenvy_filter_products — server-rendered product grid for AJAX filters
 *
 * Nonce: window.lenvyAjax.nonce (injected via inc/enqueue.php).
 * All handlers verify nonce before processing.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// ─── Shared: build WP_Query args from filter params ───────────────────────────

/**
 * Build a standalone WP_Query args array from a flat params array.
 * Used by both the AJAX handler and (optionally) pre_get_posts in shop.php.
 *
 * @param  array  $p        Params (e.g. $_GET or $_POST slice).
 * @param  string $taxonomy Taxonomy slug if on an archive (e.g. 'product_cat').
 * @param  string $term     Term slug if on an archive (e.g. 'men-perfume').
 * @param  int    $paged    Page number.
 * @return array
 */
function lenvy_build_shop_query_args(array $p, string $taxonomy = '', string $term = '', int $paged = 1): array {
	$tax_query = [];
	$meta_query = [];

	$taxonomy_map = [
		'filter_brand' => 'product_brand',
		'filter_cat' => 'product_cat',
		'filter_gender' => 'pa_gender',
		'filter_family' => 'pa_fragrance_family',
		'filter_conc' => 'pa_concentration',
		'filter_volume' => 'pa_volume_ml',
	];

	foreach ($taxonomy_map as $var => $tax) {
		$raw = $p[$var] ?? '';

		if (is_array($raw)) {
			$slugs = array_values(array_filter(array_map('sanitize_title', $raw)));
		} else {
			$raw = sanitize_text_field((string) $raw);
			$slugs = $raw !== '' ? array_values(array_filter(array_map('sanitize_title', explode(',', $raw)))) : [];
		}

		if (!empty($slugs)) {
			$tax_query[] = [
				'taxonomy' => $tax,
				'field' => 'slug',
				'terms' => $slugs,
				'operator' => 'IN',
			];
		}
	}

	// Taxonomy archive constraint.
	if ($taxonomy && $term) {
		$tax_query[] = [
			'taxonomy' => sanitize_key($taxonomy),
			'field' => 'slug',
			'terms' => sanitize_title($term),
		];
	}

	if (count($tax_query) > 1) {
		$tax_query['relation'] = 'AND';
	}

	// Price range.
	$min = isset($p['min_price']) && $p['min_price'] !== '' ? (float) $p['min_price'] : null;
	$max = isset($p['max_price']) && $p['max_price'] !== '' ? (float) $p['max_price'] : null;

	if (null !== $min || null !== $max) {
		$clause = ['key' => '_price', 'type' => 'NUMERIC'];

		if (null !== $min && null !== $max) {
			$clause['value'] = [$min, $max];
			$clause['compare'] = 'BETWEEN';
		} elseif (null !== $min) {
			$clause['value'] = $min;
			$clause['compare'] = '>=';
		} else {
			$clause['value'] = $max;
			$clause['compare'] = '<=';
		}

		$meta_query[] = $clause;
	}

	// In stock.
	if (!empty($p['filter_available'])) {
		$meta_query[] = ['key' => '_stock_status', 'value' => 'instock'];
	}

	if (count($meta_query) > 1) {
		$meta_query['relation'] = 'AND';
	}

	// On sale.
	$post__in = [];
	if (!empty($p['filter_onsale'])) {
		$sale_ids = array_map('absint', wc_get_product_ids_on_sale());
		$post__in = !empty($sale_ids) ? $sale_ids : [0];
	}

	// Sorting.
	$orderby = sanitize_key($p['orderby'] ?? get_option('woocommerce_default_catalog_orderby', 'menu_order'));

	$order_args = match ($orderby) {
		'popularity' => ['orderby' => 'meta_value_num', 'meta_key' => 'total_sales', 'order' => 'DESC'],
		'rating' => ['orderby' => 'meta_value_num', 'meta_key' => '_wc_average_rating', 'order' => 'DESC'],
		'date' => ['orderby' => 'date', 'order' => 'DESC'],
		'price' => ['orderby' => 'meta_value_num', 'meta_key' => '_price', 'order' => 'ASC'],
		'price-desc' => ['orderby' => 'meta_value_num', 'meta_key' => '_price', 'order' => 'DESC'],
		default => ['orderby' => 'menu_order title', 'order' => 'ASC'],
	};

	$args = array_merge(
		[
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => (int) get_option('posts_per_page', 12),
			'paged' => max(1, $paged),
		],
		$order_args,
	);

	if (!empty($tax_query)) {
		$args['tax_query'] = $tax_query;
	}
	if (!empty($meta_query)) {
		$args['meta_query'] = $meta_query;
	}
	if (!empty($post__in)) {
		$args['post__in'] = $post__in;
	}

	return $args;
}

// ─── Handler: add to cart ─────────────────────────────────────────────────────

function lenvy_ajax_add_to_cart(): void {
	check_ajax_referer('lenvy_ajax', 'nonce');

	$product_id = absint($_POST['product_id'] ?? 0);
	$quantity = max(1, absint($_POST['quantity'] ?? 1));
	$variation_id = absint($_POST['variation_id'] ?? 0);

	if (!$product_id) {
		wp_send_json_error(['message' => __('Invalid product.', 'lenvy')]);
	}

	$added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);

	if (!$added) {
		// WC may have set a notice — grab it.
		$notices = wc_get_notices('error');
		$message = !empty($notices) ? wp_strip_all_tags($notices[0]['notice']) : __('Could not add to cart.', 'lenvy');
		wc_clear_notices();
		wp_send_json_error(['message' => $message]);
	}

	wp_send_json_success([
		'cart_count' => WC()->cart->get_cart_contents_count(),
		// phpcs:ignore WordPress.Security.EscapeOutput — sprintf with esc_html is fine.
		'notice' => sprintf(__('"%s" added to your cart.', 'lenvy'), esc_html(get_the_title($product_id))),
	]);
}

add_action('wp_ajax_lenvy_add_to_cart', 'lenvy_ajax_add_to_cart');
add_action('wp_ajax_nopriv_lenvy_add_to_cart', 'lenvy_ajax_add_to_cart');

// ─── Handler: filter products ─────────────────────────────────────────────────

function lenvy_ajax_filter_products(): void {
	check_ajax_referer('lenvy_ajax', 'nonce');

	$taxonomy = sanitize_key($_POST['taxonomy'] ?? '');
	$term = sanitize_title($_POST['term'] ?? '');
	$paged = max(1, absint($_POST['paged'] ?? 1));

	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput — raw $_POST passed to builder which sanitizes each key.
	$query_args = lenvy_build_shop_query_args($_POST, $taxonomy, $term, $paged);

	$query = new WP_Query($query_args);

	// ── Product grid HTML ──────────────────────────────────────────────────────
	ob_start();

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			get_template_part('template-parts/components/product-card', null, [
				'product_id' => get_the_ID(),
			]);
		}
	} else {
		 ?>
		<div class="col-span-full py-20 text-center">
			<p class="text-sm text-neutral-500"><?php esc_html_e('No products found.', 'lenvy'); ?></p>
		</div>
		<?php
	}

	$grid_html = (string) ob_get_clean();
	wp_reset_postdata();

	// ── Pagination HTML ────────────────────────────────────────────────────────
	$total_pages = (int) $query->max_num_pages;
	$pagination_html = '';

	if ($total_pages > 1) {
		// $_SERVER['REQUEST_URI'] in AJAX context is admin-ajax.php — use page_url sent by JS instead.
		$raw_page_url = isset($_POST['page_url']) ? sanitize_text_field(wp_unslash((string) $_POST['page_url'])) : '/';
		$base_url = strtok($raw_page_url, '?');

		// Rebuild the filter query string from active POST params so pagination links preserve filters.
		$filter_keys = ['filter_brand', 'filter_cat', 'filter_gender', 'filter_family', 'filter_conc', 'filter_volume', 'min_price', 'max_price', 'filter_available', 'filter_onsale', 'orderby'];
		$parts = [];

		foreach ($filter_keys as $k) {
			if (empty($_POST[$k])) {
				continue;
			}
			$val = $_POST[$k];
			if (is_array($val)) {
				foreach ($val as $v) {
					$parts[] = rawurlencode($k . '[]') . '=' . rawurlencode(sanitize_text_field((string) $v));
				}
			} else {
				$parts[] = rawurlencode($k) . '=' . rawurlencode(sanitize_text_field((string) $val));
			}
		}

		$query_str = !empty($parts) ? '?' . implode('&', $parts) : '';
		$current_url = esc_url($base_url . $query_str);

		$pagination_html = (string) paginate_links([
			'base' => $current_url . '%_%',
			'format' => (strpos($current_url, '?') !== false ? '&' : '?') . 'paged=%#%',
			'current' => $paged,
			'total' => $total_pages,
			'prev_text' => '&larr;',
			'next_text' => '&rarr;',
			'type' => 'plain',
		]);

		$pagination_html =
			'<nav class="lenvy-pagination" aria-label="' .
			esc_attr__('Products', 'lenvy') .
			'">' .
			$pagination_html .
			'</nav>';
	}

	// ── Active filters HTML ────────────────────────────────────────────────────
	ob_start();
	get_template_part('template-parts/shop/filter-active');
	$active_html = (string) ob_get_clean();

	wp_send_json_success([
		'html' => $grid_html,
		'count' => (int) $query->found_posts,
		'pagination' => $pagination_html,
		'active' => $active_html,
	]);
}

add_action('wp_ajax_lenvy_filter_products', 'lenvy_ajax_filter_products');
add_action('wp_ajax_nopriv_lenvy_filter_products', 'lenvy_ajax_filter_products');
