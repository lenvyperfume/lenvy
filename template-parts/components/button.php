<?php
/**
 * Reusable button component.
 *
 * Pass args via get_template_part():
 *   'label'   (string) — button text (required).
 *   'url'     (string) — href; renders <a> when provided, <button> otherwise.
 *   'variant' (string) — 'primary' (default) | 'secondary' | 'outline'.
 *   'classes' (string) — extra Tailwind classes.
 *   'attrs'   (array)  — additional HTML attributes [ 'name' => 'value' ].
 *
 * @package Lenvy
 */

defined( 'ABSPATH' ) || exit;

$label   = isset( $args['label'] ) ? wp_kses_post( $args['label'] ) : '';
$url     = isset( $args['url'] ) ? esc_url( $args['url'] ) : '';
$variant = $args['variant'] ?? 'primary';
$classes = $args['classes'] ?? '';
$attrs   = $args['attrs'] ?? [];

if ( ! $label ) {
	return;
}

$base = 'inline-flex items-center justify-center font-semibold text-sm uppercase tracking-widest transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 px-6 py-3';

$variants = [
	'primary'   => 'bg-neutral-900 text-white hover:bg-neutral-700 focus-visible:ring-neutral-900',
	'secondary' => 'bg-neutral-100 text-neutral-900 hover:bg-neutral-200 focus-visible:ring-neutral-400',
	'outline'   => 'border border-neutral-900 text-neutral-900 hover:bg-neutral-900 hover:text-white focus-visible:ring-neutral-900',
];

$variant_class = $variants[ $variant ] ?? $variants['primary'];
$full_class    = trim( $base . ' ' . $variant_class . ( $classes ? ' ' . $classes : '' ) );

// Build extra attribute string.
$extra = '';
foreach ( $attrs as $attr_name => $attr_value ) {
	$extra .= ' ' . esc_attr( $attr_name ) . '="' . esc_attr( $attr_value ) . '"';
}

if ( $url ) {
	printf(
		'<a href="%1$s" class="%2$s"%3$s>%4$s</a>',
		esc_url( $url ),
		esc_attr( $full_class ),
		$extra, // Already escaped above.
		$label
	);
} else {
	printf(
		'<button type="button" class="%1$s"%2$s>%3$s</button>',
		esc_attr( $full_class ),
		$extra,
		$label
	);
}
