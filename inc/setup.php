<?php
/**
 * Theme setup.
 *
 * @package Lenvy
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sets up theme defaults and registers WordPress features.
 */
function lenvy_setup(): void {
	// Allow WordPress to manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails.
	add_theme_support( 'post-thumbnails' );

	// HTML5 markup for core features.
	add_theme_support(
		'html5',
		[
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		]
	);

	// WooCommerce.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Custom logo.
	add_theme_support(
		'custom-logo',
		[
			'height'      => 80,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		]
	);

	// Navigation menus.
	register_nav_menus(
		[
			'primary' => esc_html__( 'Primary Navigation', 'lenvy' ),
			'footer'  => esc_html__( 'Footer Navigation', 'lenvy' ),
		]
	);

	// Make theme available for translation.
	load_theme_textdomain( 'lenvy', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'lenvy_setup' );

/**
 * Set the content width in pixels, based on the theme's design.
 */
function lenvy_content_width(): void {
	$GLOBALS['content_width'] = apply_filters( 'lenvy_content_width', 1280 );
}
add_action( 'after_setup_theme', 'lenvy_content_width', 0 );
