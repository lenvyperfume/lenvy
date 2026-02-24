<?php
/**
 * AJAX handlers.
 *
 * Registers wp_ajax / wp_ajax_nopriv actions for:
 *   lenvy_filter_products  — filtered product grid HTML + counts (Phase 2)
 *   lenvy_add_to_cart      — quick add to cart (Phase 2)
 *   lenvy_get_mini_cart    — mini cart fragment refresh (Phase 2)
 *
 * Nonce localized as `lenvyAjax.nonce` via inc/enqueue.php.
 * All handlers verify nonce before processing.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();
