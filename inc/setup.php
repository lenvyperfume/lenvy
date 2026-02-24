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
	add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
	add_theme_support('woocommerce');
	add_theme_support('wc-product-gallery-zoom');
	add_theme_support('wc-product-gallery-lightbox');
	add_theme_support('wc-product-gallery-slider');
	add_theme_support('custom-logo');
	load_theme_textdomain('lenvy', get_template_directory() . '/languages');

	register_nav_menus([
		'primary'          => __('Primary Navigation', 'lenvy'),
		'mobile'           => __('Mobile Navigation', 'lenvy'),
		'footer'           => __('Footer Navigation', 'lenvy'),
		'footer-secondary' => __('Footer Secondary Navigation', 'lenvy'),
	]);
}
add_action('after_setup_theme', 'lenvy_setup');

function lenvy_content_width(): void {
	$GLOBALS['content_width'] = apply_filters('lenvy_content_width', 1280);
}
add_action('after_setup_theme', 'lenvy_content_width', 0);
