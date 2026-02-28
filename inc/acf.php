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

// ─── USP bar fields ──────────────────────────────────────────────────────────

add_action('acf/include_fields', function (): void {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key'      => 'group_lenvy_usp_bar',
		'title'    => 'USP / Trust Bar',
		'fields'   => [
			[
				'key'           => 'field_lenvy_usp_bar_enabled',
				'label'         => 'Enable USP bar',
				'name'          => 'lenvy_usp_bar_enabled',
				'type'          => 'true_false',
				'default_value' => 1,
				'ui'            => 1,
				'instructions'  => 'Show the trust-signal strip below the header on every page.',
			],
			[
				'key'               => 'field_lenvy_usp_items',
				'label'             => 'USP items',
				'name'              => 'lenvy_usp_items',
				'type'              => 'repeater',
				'max'               => 5,
				'layout'            => 'table',
				'button_label'      => 'Add USP',
				'instructions'      => 'Leave empty to use the built-in defaults (shipping, originality, returns, payment).',
				'conditional_logic' => [
					[
						[
							'field'    => 'field_lenvy_usp_bar_enabled',
							'operator' => '==',
							'value'    => '1',
						],
					],
				],
				'sub_fields'        => [
					[
						'key'           => 'field_lenvy_usp_icon',
						'label'         => 'Icon',
						'name'          => 'usp_icon',
						'type'          => 'select',
						'choices'       => [
							'truck'   => 'Truck (shipping)',
							'shield'  => 'Shield (authenticity)',
							'refresh' => 'Refresh (returns)',
							'check'   => 'Check (security)',
							'heart'   => 'Heart',
							'star'    => 'Star',
						],
						'default_value' => 'check',
						'return_format' => 'value',
					],
					[
						'key'         => 'field_lenvy_usp_text',
						'label'       => 'Text',
						'name'        => 'usp_text',
						'type'        => 'text',
						'placeholder' => 'e.g. Gratis verzending vanaf €50',
					],
				],
			],
		],
		'location' => [
			[
				[
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'lenvy-theme-settings',
				],
			],
		],
		'menu_order' => 3,
	]);
});

// ─── Editorial moment fields (homepage brand statement) ───────────────────────

add_action('acf/include_fields', function (): void {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key'      => 'group_lenvy_editorial_moment',
		'title'    => 'Homepage — Editorial Moment',
		'fields'   => [
			[
				'key'          => 'field_lenvy_editorial_heading',
				'label'        => 'Heading',
				'name'         => 'lenvy_editorial_heading',
				'type'         => 'text',
				'placeholder'  => 'De Kunst van Geur',
				'instructions' => 'Large display heading shown on the homepage.',
			],
			[
				'key'          => 'field_lenvy_editorial_subheading',
				'label'        => 'Subheading',
				'name'         => 'lenvy_editorial_subheading',
				'type'         => 'textarea',
				'rows'         => 3,
				'placeholder'  => 'Zorgvuldig samengestelde parfums…',
				'instructions' => 'Body text below the heading.',
			],
			[
				'key'          => 'field_lenvy_editorial_cta_label',
				'label'        => 'CTA Label',
				'name'         => 'lenvy_editorial_cta_label',
				'type'         => 'text',
				'placeholder'  => 'Ontdek de Collectie',
				'instructions' => 'Optional link text. Leave empty to hide the CTA.',
			],
			[
				'key'          => 'field_lenvy_editorial_cta_url',
				'label'        => 'CTA URL',
				'name'         => 'lenvy_editorial_cta_url',
				'type'         => 'url',
				'instructions' => 'Destination for the CTA link.',
			],
		],
		'location' => [
			[
				[
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'lenvy-theme-settings',
				],
			],
		],
		'menu_order' => 5,
	]);
});
