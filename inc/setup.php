<?php
/**
 * Theme setup.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

function lenvy_setup(): void {
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	]);
	add_theme_support('woocommerce');
	add_theme_support('wc-product-gallery-zoom');
	add_theme_support('wc-product-gallery-lightbox');
	add_theme_support('wc-product-gallery-slider');
	add_theme_support('custom-logo');
	load_theme_textdomain('lenvy', get_template_directory() . '/languages');

	register_nav_menus([
		'primary' => __('Primary Navigation', 'lenvy'),
		'mobile' => __('Mobile Navigation', 'lenvy'),
		'footer' => __('Footer Navigation', 'lenvy'),
		'footer-secondary' => __('Footer Secondary Navigation', 'lenvy'),
	]);
}
add_action('after_setup_theme', 'lenvy_setup');

function lenvy_content_width(): void {
	$GLOBALS['content_width'] = apply_filters('lenvy_content_width', 1280);
}
add_action('after_setup_theme', 'lenvy_content_width', 0);

/**
 * Register custom image sizes.
 *
 * Perfume bottles are portrait — use a 3:4 ratio throughout.
 * `lenvy_product_portrait` is used in product cards and brand archive grids.
 * WooCommerce's own thumbnail/single sizes are also overridden to portrait.
 */
function lenvy_image_sizes(): void {
	// 3:4 portrait — primary product card thumbnail (shop loop, search, homepage).
	add_image_size( 'lenvy_product_portrait', 540, 720, true );

	// Wide landscape — blog post featured image.
	add_image_size( 'lenvy_post_hero', 1200, 675, true );
}
add_action( 'after_setup_theme', 'lenvy_image_sizes' );

/**
 * Override WooCommerce built-in product image sizes to portrait 3:4.
 *
 * WC reads these at runtime, so changing them here affects all WC-generated
 * thumbnails (product loop, single product, gallery strip).
 *
 * @param  array $size Existing size array with keys: width, height, crop.
 * @return array
 */
add_filter(
	'woocommerce_get_image_size_thumbnail',
	function ( array $size ): array {
		return [
			'width'  => 540,
			'height' => 720,
			'crop'   => 1,
		];
	}
);

add_filter(
	'woocommerce_get_image_size_single',
	function ( array $size ): array {
		return [
			'width'  => 800,
			'height' => 1067,
			'crop'   => 0, // Contain on single product — never crop the hero shot.
		];
	}
);

add_filter(
	'woocommerce_get_image_size_gallery_thumbnail',
	function ( array $size ): array {
		return [
			'width'  => 120,
			'height' => 160,
			'crop'   => 1,
		];
	}
);
