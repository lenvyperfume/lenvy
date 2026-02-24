<?php
/**
 * Shop — query filters and WooCommerce archive modifications.
 *
 * Handles:
 * - pre_get_posts filter logic for shop and archive pages
 * - Query var whitelisting for custom filter params
 * - Filter count helpers (cached)
 *
 * Filter query vars (all prefixed to avoid collisions):
 *   filter_brand     → product_brand taxonomy slug(s), comma-separated
 *   filter_cat       → product_cat taxonomy slug(s), comma-separated
 *   filter_gender    → pa_gender attribute slug(s), comma-separated
 *   filter_family    → pa_fragrance_family slug(s), comma-separated
 *   filter_conc      → pa_concentration slug(s), comma-separated
 *   filter_volume    → pa_volume_ml slug(s), comma-separated
 *   filter_available → 1 = in stock only
 *   filter_onsale    → 1 = on sale only
 *   min_price        → minimum price (float)
 *   max_price        → maximum price (float)
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// ─── Register custom query vars ───────────────────────────────────────────────

add_filter( 'query_vars', function ( array $vars ): array {
	$custom = [
		'filter_brand', 'filter_cat', 'filter_gender', 'filter_family',
		'filter_conc', 'filter_volume', 'filter_available', 'filter_onsale',
		'min_price', 'max_price',
	];

	return array_merge( $vars, $custom );
} );

// ─── Modify main query on shop / archive pages ────────────────────────────────

add_action( 'pre_get_posts', function ( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! ( $query->is_post_type_archive( 'product' ) || $query->is_tax( [ 'product_cat', 'product_brand', 'product_tag', 'pa_gender', 'pa_fragrance_family', 'pa_concentration', 'pa_volume_ml' ] ) ) ) {
		return;
	}

	$tax_query  = (array) ( $query->get( 'tax_query' ) ?: [] );
	$meta_query = (array) ( $query->get( 'meta_query' ) ?: [] );

	// ── Taxonomy filters ──────────────────────────────────────────────────────

	$taxonomy_map = [
		'filter_brand'  => 'product_brand',
		'filter_cat'    => 'product_cat',
		'filter_gender' => 'pa_gender',
		'filter_family' => 'pa_fragrance_family',
		'filter_conc'   => 'pa_concentration',
		'filter_volume' => 'pa_volume_ml',
	];

	foreach ( $taxonomy_map as $var => $taxonomy ) {
		$slugs = lenvy_parse_filter_slugs( $var );

		if ( ! empty( $slugs ) ) {
			$tax_query[] = [
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $slugs,
				'operator' => 'IN',
			];
		}
	}

	// ── Price range ───────────────────────────────────────────────────────────

	// phpcs:ignore WordPress.Security.NonceVerification
	$min_price = isset( $_GET['min_price'] ) ? (float) $_GET['min_price'] : null;
	// phpcs:ignore WordPress.Security.NonceVerification
	$max_price = isset( $_GET['max_price'] ) ? (float) $_GET['max_price'] : null;

	if ( null !== $min_price || null !== $max_price ) {
		$price_clause = [
			'key'     => '_price',
			'type'    => 'NUMERIC',
		];

		if ( null !== $min_price && null !== $max_price ) {
			$price_clause['value']   = [ $min_price, $max_price ];
			$price_clause['compare'] = 'BETWEEN';
		} elseif ( null !== $min_price ) {
			$price_clause['value']   = $min_price;
			$price_clause['compare'] = '>=';
		} else {
			$price_clause['value']   = $max_price;
			$price_clause['compare'] = '<=';
		}

		$meta_query[] = $price_clause;
	}

	// ── In stock ──────────────────────────────────────────────────────────────

	// phpcs:ignore WordPress.Security.NonceVerification
	if ( ! empty( $_GET['filter_available'] ) ) {
		$meta_query[] = [
			'key'   => '_stock_status',
			'value' => 'instock',
		];
	}

	// ── On sale ───────────────────────────────────────────────────────────────

	// phpcs:ignore WordPress.Security.NonceVerification
	if ( ! empty( $_GET['filter_onsale'] ) ) {
		$sale_ids = array_map( 'absint', wc_get_product_ids_on_sale() );

		if ( ! empty( $sale_ids ) ) {
			$query->set( 'post__in', $sale_ids );
		} else {
			// No products on sale → force empty result.
			$query->set( 'post__in', [ 0 ] );
		}
	}

	// ── Apply compound queries ────────────────────────────────────────────────

	if ( count( $tax_query ) > 1 ) {
		$tax_query['relation'] = 'AND';
	}

	if ( count( $meta_query ) > 1 ) {
		$meta_query['relation'] = 'AND';
	}

	if ( ! empty( $tax_query ) ) {
		$query->set( 'tax_query', $tax_query );
	}

	if ( ! empty( $meta_query ) ) {
		$query->set( 'meta_query', $meta_query );
	}

	// ── Sorting ───────────────────────────────────────────────────────────────

	// phpcs:ignore WordPress.Security.NonceVerification
	$orderby = isset( $_GET['orderby'] ) ? sanitize_key( $_GET['orderby'] ) : get_option( 'woocommerce_default_catalog_orderby', 'menu_order' );

	switch ( $orderby ) {
		case 'popularity':
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'meta_key', 'total_sales' );
			$query->set( 'order', 'DESC' );
			break;

		case 'rating':
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'meta_key', '_wc_average_rating' );
			$query->set( 'order', 'DESC' );
			break;

		case 'date':
			$query->set( 'orderby', 'date' );
			$query->set( 'order', 'DESC' );
			break;

		case 'price':
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'meta_key', '_price' );
			$query->set( 'order', 'ASC' );
			break;

		case 'price-desc':
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'meta_key', '_price' );
			$query->set( 'order', 'DESC' );
			break;

		default:
			$query->set( 'orderby', 'menu_order title' );
			$query->set( 'order', 'ASC' );
			break;
	}
} );
