<?php
/**
 * Custom taxonomy registration.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

/**
 * Register the Product Brand taxonomy.
 *
 * Registered as a flat (non-hierarchical) public taxonomy on the
 * `product` post type. Capabilities mirror WooCommerce's own product
 * taxonomy permissions so shop managers can manage brands without
 * needing a separate role.
 */
function lenvy_register_taxonomies(): void {
	register_taxonomy(
		'product_brand',
		'product',
		[
			'labels'            => [
				'name'                       => __('Brands', 'lenvy'),
				'singular_name'              => __('Brand', 'lenvy'),
				'search_items'               => __('Search Brands', 'lenvy'),
				'all_items'                  => __('All Brands', 'lenvy'),
				'edit_item'                  => __('Edit Brand', 'lenvy'),
				'update_item'                => __('Update Brand', 'lenvy'),
				'add_new_item'               => __('Add New Brand', 'lenvy'),
				'new_item_name'              => __('New Brand Name', 'lenvy'),
				'menu_name'                  => __('Brands', 'lenvy'),
				'view_item'                  => __('View Brand', 'lenvy'),
				'popular_items'              => __('Popular Brands', 'lenvy'),
				'separate_items_with_commas' => __('Separate brands with commas', 'lenvy'),
				'add_or_remove_items'        => __('Add or remove brands', 'lenvy'),
				'choose_from_most_used'      => __('Choose from the most used brands', 'lenvy'),
				'not_found'                  => __('No brands found.', 'lenvy'),
				'back_to_items'              => __('← Back to Brands', 'lenvy'),
			],
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_in_rest'      => true,
			'has_archive'       => true,
			'rewrite'           => [
				'slug'       => 'brand',
				'with_front' => false,
			],
			'query_var'         => true,
			'capabilities'      => [
				'manage_terms' => 'manage_woocommerce',
				'edit_terms'   => 'manage_woocommerce',
				'delete_terms' => 'manage_woocommerce',
				'assign_terms' => 'edit_products',
			],
		]
	);
}
add_action('init', 'lenvy_register_taxonomies');
