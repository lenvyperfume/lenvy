<?php
/**
 * Lenvy Theme Functions
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// Load theme includes.
foreach (['/inc/setup.php', '/inc/enqueue.php', '/inc/acf.php', '/inc/helpers.php', '/inc/woocommerce.php', '/inc/nav-walkers.php'] as $file) {
	require_once get_template_directory() . $file;
}
