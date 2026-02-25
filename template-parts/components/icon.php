<?php
/**
 * Icon component — renders an inline SVG from assets/icons/.
 *
 * Usage:
 *   get_template_part('template-parts/components/icon', null, [
 *     'name'  => 'search',          // required — filename without .svg
 *     'size'  => 'md',              // xs|sm|md|lg|xl — maps to Tailwind w/h classes
 *     'class' => 'text-neutral-700', // additional Tailwind classes
 *     'label' => 'Search',          // for standalone icons needing aria-label
 *   ]);
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$name = $args['name'] ?? '';
$size = $args['size'] ?? 'md';
$class = $args['class'] ?? '';
$label = $args['label'] ?? '';

if (empty($name)) {
	return;
}

// Whitelist — only serve files from our own icons directory.
$allowed = [
	'search',
	'cart',
	'menu',
	'close',
	'chevron-down',
	'chevron-right',
	'chevron-left',
	'arrow-right',
	'filter',
	'sort',
	'grid',
	'check',
	'plus',
	'minus',
	'heart',
	'star',
	'star-filled',
	'trash',
	'user',
	'instagram',
	'facebook',
	'tiktok',
	'pinterest',
	'youtube',
	'x',
];

if (!in_array($name, $allowed, true)) {
	return;
}

$file = get_template_directory() . '/assets/icons/' . $name . '.svg';

if (!file_exists($file)) {
	return;
}

// Per-request cache — avoids repeated disk reads for the same icon.
static $cache = [];

if (!isset($cache[$name])) {
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$cache[$name] = (string) file_get_contents($file);
}

$svg = trim($cache[$name]);

if (empty($svg)) {
	return;
}

// Size → Tailwind class mapping.
$sizes = [
	'xs' => 'w-3 h-3',
	'sm' => 'w-4 h-4',
	'md' => 'w-5 h-5',
	'lg' => 'w-6 h-6',
	'xl' => 'w-8 h-8',
];

$size_class = $sizes[$size] ?? $sizes['md'];
$all_classes = trim($size_class . ($class ? ' ' . $class : ''));

// ARIA — decorative icons are hidden; standalone icons get a label.
$aria = $label ? 'role="img" aria-label="' . esc_attr($label) . '"' : 'aria-hidden="true" focusable="false"';

// Inject class and aria into the root <svg> element.
// SVG files in assets/icons/ intentionally have no class or aria attributes.
$svg = (string) preg_replace('/<svg\b/', '<svg class="' . esc_attr($all_classes) . '" ' . $aria, $svg, 1);

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — SVG read from trusted theme directory.
echo $svg;
