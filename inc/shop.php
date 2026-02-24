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
 *   filter_brand    → product_brand taxonomy slug(s), comma-separated
 *   filter_cat      → product_cat taxonomy slug(s), comma-separated
 *   filter_gender   → pa_gender attribute slug(s), comma-separated
 *   filter_family   → pa_fragrance_family slug(s), comma-separated
 *   filter_conc     → pa_concentration slug(s), comma-separated
 *   filter_volume   → pa_volume_ml slug(s), comma-separated
 *   filter_available → 1 = in stock only
 *   filter_onsale   → 1 = on sale only
 *   min_price       → minimum price (float)
 *   max_price       → maximum price (float)
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();
