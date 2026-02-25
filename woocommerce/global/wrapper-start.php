<?php
/**
 * WooCommerce main content wrapper — open.
 *
 * This template is loaded by woocommerce_output_content_wrapper(), which is
 * hooked to woocommerce_before_main_content at priority 10.
 *
 * In this theme that hook is REMOVED and replaced with a direct action in
 * inc/woocommerce.php, so this file is never called during normal operation.
 * It exists to prevent WooCommerce's default <div id="primary"> output in
 * the event the hook is accidentally re-registered by a plugin.
 *
 * @package Lenvy
 * @see     WC templates/global/wrapper-start.php
 * @see     inc/woocommerce.php — remove_action / add_action replacement
 */

defined( 'ABSPATH' ) || exit();
