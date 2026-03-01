<?php
/**
 * Advanced Custom Fields configuration.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

if (!class_exists('ACF')) {
	return;
}

/**
 * Register the ACF JSON save point so field groups are stored in version
 * control alongside the theme.
 *
 * @param  string $path  Default save path.
 * @return string
 */
function lenvy_acf_json_save_point(string $_path): string {
	return get_template_directory() . '/acf-json';
}
add_filter('acf/settings/save_json', 'lenvy_acf_json_save_point');

/**
 * Register the ACF JSON load point.
 *
 * @param  array<int, string> $paths  Default load paths.
 * @return array<int, string>
 */
function lenvy_acf_json_load_point(array $paths): array {
	// Remove the default path.
	unset($paths[0]);

	$paths[] = get_template_directory() . '/acf-json';

	return $paths;
}
add_filter('acf/settings/load_json', 'lenvy_acf_json_load_point');

/**
 * Register the global Theme Settings options page.
 * Requires ACF Pro.
 */
if (function_exists('acf_add_options_page')) {
	acf_add_options_page([
		'page_title' => __('Theme Settings', 'lenvy'),
		'menu_title' => __('Theme Settings', 'lenvy'),
		'menu_slug' => 'lenvy-theme-settings',
		'capability' => 'manage_options',
		'icon_url' => 'dashicons-admin-appearance',
		'position' => 61,
		'autoload' => true,
	]);
}

// ─── Shop page fields (targets WC shop page dynamically) ────────────────────

add_action('acf/include_fields', function (): void {
	if (!function_exists('acf_add_local_field_group') || !function_exists('wc_get_page_id')) {
		return;
	}

	$shop_page_id = wc_get_page_id('shop');
	if ($shop_page_id <= 0) {
		return;
	}

	acf_add_local_field_group([
		'key'      => 'group_lenvy_shop_page',
		'title'    => 'Shop Page',
		'fields'   => [
			[
				'key'          => 'field_lenvy_sp_banner_image',
				'label'        => 'Banner Image',
				'name'         => 'lenvy_shop_banner_image',
				'type'         => 'image',
				'instructions' => 'Full-width banner for the shop page. Recommended: 1920×400px.',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'library'      => 'all',
				'mime_types'   => 'jpg,jpeg,png,webp',
			],
		],
		'location' => [
			[
				[
					'param'    => 'page',
					'operator' => '==',
					'value'    => (string) $shop_page_id,
				],
			],
		],
		'menu_order' => 0,
	]);
});

