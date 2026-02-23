<?php
/**
 * Custom nav walkers.
 *
 * Lenvy_Nav_Walker        — desktop: hover-revealed dropdown panels.
 * Lenvy_Mobile_Nav_Walker — mobile drawer: JS-toggled accordion sub-menus.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// ─── Desktop walker ───────────────────────────────────────────────────────────

class Lenvy_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Open sub-menu panel.
	 */
	public function start_lvl(&$output, $depth = 0, $args = null) {
		if ($depth === 0) {
			$output .= '<ul class="absolute top-full left-0 z-50 min-w-[200px] bg-white border-t-2 border-brand-700 shadow-lg shadow-brand-950/5 py-2 invisible opacity-0 translate-y-1 group-hover/item:visible group-hover/item:opacity-100 group-hover/item:translate-y-0 transition-all duration-150">';
		} else {
			$output .= '<ul class="pl-2">';
		}
	}

	/**
	 * Close sub-menu panel.
	 */
	public function end_lvl(&$output, $depth = 0, $args = null) {
		$output .= '</ul>';
	}

	/**
	 * Output a nav item.
	 */
	public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {
		$item         = $data_object;
		$has_children = in_array('menu-item-has-children', (array) $item->classes, true);
		$is_active    = in_array('current-menu-item', (array) $item->classes, true)
			|| in_array('current-menu-ancestor', (array) $item->classes, true)
			|| in_array('current-menu-parent', (array) $item->classes, true);

		$title  = apply_filters('nav_menu_item_title', $item->title, $item, $args, $depth);
		$url    = esc_url($item->url);
		$target = $item->target ? ' target="' . esc_attr($item->target) . '"' : '';
		$rel    = $item->xfn ? ' rel="' . esc_attr($item->xfn) . '"' : '';

		if ($depth === 0) {
			$output .= '<li class="group/item relative">';

			$color = $is_active
				? 'text-brand-700'
				: 'text-brand-950 hover:text-brand-700';

			// flex-col so the underline sits inline just below the text (gap-1.5 ≈ 6px)
			$output .= '<a href="' . $url . '"' . $target . $rel
				. ' class="flex flex-col items-center gap-1.5 px-4 py-5 text-[11px] uppercase tracking-[0.15em] font-medium transition-colors duration-150 whitespace-nowrap ' . esc_attr($color) . '">';

			// Text row (+ optional chevron)
			$output .= '<span class="flex items-center gap-1">';
			$output .= esc_html($title);
			if ($has_children) {
				$output .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 opacity-50 transition-transform duration-150 group-hover/item:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>';
			}
			$output .= '</span>';

			// Underline — inline, immediately below text
			$scale   = $is_active ? 'scale-x-100' : 'scale-x-0 group-hover/item:scale-x-100';
			$output .= '<span class="block w-full h-px bg-brand-700 origin-left transition-transform duration-200 ' . $scale . '" aria-hidden="true"></span>';

			$output .= '</a>';
		} else {
			$output .= '<li>';

			$link_class = 'block px-5 py-2.5 text-sm transition-colors duration-100 '
				. ($is_active
					? 'text-brand-700 font-medium bg-brand-50'
					: 'text-brand-950 hover:text-brand-700 hover:bg-brand-50');

			$output .= '<a href="' . $url . '"' . $target . $rel
				. ' class="' . esc_attr($link_class) . '">'
				. esc_html($title)
				. '</a>';
		}
	}

	public function end_el(&$output, $data_object, $depth = 0, $args = null) {
		$output .= '</li>';
	}
}

// ─── Mobile walker ────────────────────────────────────────────────────────────

class Lenvy_Mobile_Nav_Walker extends Walker_Nav_Menu {

	public function start_lvl(&$output, $depth = 0, $args = null) {
		$output .= '<ul class="mobile-submenu overflow-hidden bg-brand-50" style="max-height:0;opacity:0;transition:max-height .3s ease,opacity .3s ease" aria-hidden="true">';
	}

	public function end_lvl(&$output, $depth = 0, $args = null) {
		$output .= '</ul>';
	}

	public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {
		$item         = $data_object;
		$has_children = in_array('menu-item-has-children', (array) $item->classes, true);
		$is_active    = in_array('current-menu-item', (array) $item->classes, true)
			|| in_array('current-menu-ancestor', (array) $item->classes, true);

		$title  = apply_filters('nav_menu_item_title', $item->title, $item, $args, $depth);
		$url    = esc_url($item->url);
		$target = $item->target ? ' target="' . esc_attr($item->target) . '"' : '';

		if ($depth === 0) {
			$output .= '<li class="border-b border-neutral-100">';

			$color = $is_active
				? 'text-brand-700'
				: 'text-brand-950 hover:text-brand-700';

			if ($has_children) {
				$output .= '<div class="flex items-center">';
				$output .= '<a href="' . $url . '"' . $target . ' class="flex-1 block px-6 py-4 text-[11px] uppercase tracking-[0.15em] font-medium transition-colors ' . esc_attr($color) . '">' . esc_html($title) . '</a>';
				$output .= '<button type="button" class="shrink-0 px-5 py-4 text-neutral-400 hover:text-brand-700 transition-colors" data-mobile-submenu-toggle aria-expanded="false" aria-label="' . esc_attr__('Expand submenu', 'lenvy') . '">';
				$output .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>';
				$output .= '</button>';
				$output .= '</div>';
			} else {
				$output .= '<a href="' . $url . '"' . $target . ' class="block px-6 py-4 text-[11px] uppercase tracking-[0.15em] font-medium transition-colors ' . esc_attr($color) . '">' . esc_html($title) . '</a>';
			}
		} else {
			$output .= '<li>';
			$output .= '<a href="' . $url . '"' . $target . ' class="block px-8 py-3 text-xs text-brand-800 hover:text-brand-700 transition-colors tracking-wide">' . esc_html($title) . '</a>';
		}
	}

	public function end_el(&$output, $data_object, $depth = 0, $args = null) {
		$output .= '</li>';
	}
}
