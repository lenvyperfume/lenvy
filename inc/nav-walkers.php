<?php
/**
 * Custom nav walkers.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// ─── Footer walker ────────────────────────────────────────────────────────────

class Lenvy_Footer_Nav_Walker extends Walker_Nav_Menu {
	public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {
		$item   = $data_object;
		$url    = esc_url($item->url);
		$title  = apply_filters('nav_menu_item_title', $item->title, $item, $args, $depth);
		$target = $item->target ? ' target="' . esc_attr($item->target) . '" rel="noopener"' : '';

		$output .= '<li>';
		$output .=
			'<a href="' . $url . '"' . $target .
			' class="text-sm font-light text-neutral-600 hover:text-neutral-950 transition-colors duration-200">' .
			esc_html($title) .
			'</a>';
	}

	public function end_el(&$output, $data_object, $depth = 0, $args = null) {
		$output .= '</li>';
	}
}
