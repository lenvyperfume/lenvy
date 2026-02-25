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
			' class="text-sm font-light text-neutral-400 hover:text-white transition-colors duration-200">' .
			esc_html($title) .
			'</a>';
	}

	public function end_el(&$output, $data_object, $depth = 0, $args = null) {
		$output .= '</li>';
	}
}

// ─── Primary nav walker (desktop) ─────────────────────────────────────────────
// Renders a flat link row at depth 0, with a CSS hover/focus-within dropdown
// at depth 1. No JavaScript required — uses Tailwind group utilities.

class Lenvy_Primary_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Opens the submenu <ul> at depth 0 only.
	 * The dropdown is hidden via opacity/pointer-events and revealed on
	 * group-hover / group-focus-within of the parent <li class="group">.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth !== 0 ) {
			return;
		}
		// border-t-2 border-t-primary: lavender top accent line — references the brand primary token.
		$output .= '<ul class="absolute left-0 top-full z-10 min-w-[15rem] bg-white border-x border-b border-neutral-100 border-t-2 border-t-primary py-2 shadow-sm opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto group-focus-within:opacity-100 group-focus-within:pointer-events-auto translate-y-1.5 group-hover:translate-y-0 group-focus-within:translate-y-0 transition-all duration-200">';
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth !== 0 ) {
			return;
		}
		$output .= '</ul>';
	}

	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item         = $data_object;
		$url          = esc_url( $item->url );
		$title        = apply_filters( 'nav_menu_item_title', $item->title, $item, $args, $depth );
		$target       = $item->target ? ' target="' . esc_attr( $item->target ) . '" rel="noopener"' : '';
		$is_current   = in_array( 'current-menu-item', $item->classes, true )
					 || in_array( 'current-menu-ancestor', $item->classes, true );
		$has_children = in_array( 'menu-item-has-children', $item->classes, true );

		if ( $depth === 0 ) {
			$li_class = $has_children ? 'relative group' : 'relative';
			$output  .= '<li class="' . esc_attr( $li_class ) . '">';

			$link_class  = 'flex items-center gap-1.5 py-2 text-sm tracking-[0.02em] transition-colors duration-150';
			$link_class .= $is_current ? ' text-black underline underline-offset-[5px] decoration-neutral-300' : ' text-neutral-600 hover:text-black';
			$output     .= '<a href="' . $url . '"' . $target . ' class="' . esc_attr( $link_class ) . '">';
			$output     .= esc_html( $title );

			if ( $has_children ) {
				$output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 shrink-0 opacity-50 transition-transform duration-200 group-hover:rotate-180 group-focus-within:rotate-180" aria-hidden="true" focusable="false"><polyline points="6 9 12 15 18 9"/></svg>';
			}
			$output .= '</a>';

		} else {
			// Dropdown item
			$output .= '<li>';

			$link_class  = 'block px-4 py-2.5 text-sm tracking-[0.01em] transition-colors duration-150';
			$link_class .= $is_current ? ' text-black font-medium bg-neutral-50' : ' text-neutral-600 hover:bg-neutral-50 hover:text-black';
			$output     .= '<a href="' . $url . '"' . $target . ' class="' . esc_attr( $link_class ) . '">' . esc_html( $title ) . '</a>';
		}
	}

	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}

// ─── Mobile nav walker ────────────────────────────────────────────────────────
// Accordion-style mobile nav. Sub-menus are toggled by accordion.js module via
// [data-mobile-submenu-toggle] buttons. The .mobile-submenu class is the JS hook.

class Lenvy_Mobile_Nav_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<ul class="mobile-submenu overflow-hidden transition-[max-height,opacity] duration-300 ease-in-out" style="max-height:0;opacity:0;" aria-hidden="true">';
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul>';
	}

	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item         = $data_object;
		$url          = esc_url( $item->url );
		$title        = apply_filters( 'nav_menu_item_title', $item->title, $item, $args, $depth );
		$target       = $item->target ? ' target="' . esc_attr( $item->target ) . '" rel="noopener"' : '';
		$is_current   = in_array( 'current-menu-item', $item->classes, true )
					 || in_array( 'current-menu-ancestor', $item->classes, true );
		$has_children = in_array( 'menu-item-has-children', $item->classes, true );

		if ( $depth === 0 ) {
			$output .= '<li class="border-b border-neutral-100">';
			$output .= '<div class="flex items-stretch">';

			$link_class  = 'flex-1 py-4 text-sm font-medium transition-colors duration-150';
			$link_class .= $is_current ? ' text-black' : ' text-neutral-800 hover:text-black';
			$output     .= '<a href="' . $url . '"' . $target . ' class="' . esc_attr( $link_class ) . '">' . esc_html( $title ) . '</a>';

			if ( $has_children ) {
				$btn_label = esc_attr(
					/* translators: %s: menu item title */
					sprintf( __( 'Toggle %s submenu', 'lenvy' ), $title )
				);
				$output .= '<button type="button" data-mobile-submenu-toggle aria-expanded="false" aria-label="' . $btn_label . '" class="px-3 text-neutral-500 hover:text-black transition-colors duration-150">';
				$output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 transition-transform duration-200" aria-hidden="true" focusable="false"><polyline points="6 9 12 15 18 9"/></svg>';
				$output .= '</button>';
			}
			$output .= '</div>';

		} else {
			$output .= '<li>';

			$link_class  = 'block py-3 pl-4 text-sm transition-colors duration-150';
			$link_class .= $is_current ? ' text-black font-medium' : ' text-neutral-600 hover:text-black';
			$output     .= '<a href="' . $url . '"' . $target . ' class="' . esc_attr( $link_class ) . '">' . esc_html( $title ) . '</a>';
		}
	}

	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}
