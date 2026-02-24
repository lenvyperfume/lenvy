<?php
/**
 * Template helper functions.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// ─── ACF field helpers ─────────────────────────────────────────────────────────

/**
 * Get an ACF field value with a null fallback when ACF is not active.
 *
 * @param  string          $key     Field name or key.
 * @param  int|string|null $post_id Post ID, 'options', or null for current post.
 * @return mixed
 */
function lenvy_field( string $key, int|string|null $post_id = null ): mixed {
	if ( ! function_exists( 'get_field' ) ) {
		return null;
	}

	return get_field( $key, $post_id ?? false );
}

/**
 * Echo an ACF text field value, HTML-escaped.
 * Only outputs string values — silently skips arrays, objects, etc.
 *
 * @param  string          $key     Field name or key.
 * @param  int|string|null $post_id Post ID, 'options', or null for current post.
 */
function lenvy_the_field( string $key, int|string|null $post_id = null ): void {
	$value = lenvy_field( $key, $post_id );

	if ( empty( $value ) || ! is_string( $value ) ) {
		return;
	}

	echo esc_html( $value );
}

// ─── Image helper ─────────────────────────────────────────────────────────────

/**
 * Return a wp_get_attachment_image() string, or an empty string if the
 * attachment ID is falsy.
 *
 * @param  int|false $attachment_id WordPress attachment ID.
 * @param  string    $size          Image size name or [width, height] array.
 * @param  string    $class         Additional class attribute value.
 * @return string
 */
function lenvy_get_image( int|false $attachment_id, string $size = 'thumbnail', string $class = '' ): string {
	if ( ! $attachment_id ) {
		return '';
	}

	$attrs = $class ? [ 'class' => $class ] : [];

	return wp_get_attachment_image( $attachment_id, $size, false, $attrs ) ?: '';
}

// ─── Icon helper ──────────────────────────────────────────────────────────────

/**
 * Render the icon template part inline.
 *
 * @param  string $name  Icon name (must match a file in assets/icons/).
 * @param  string $class Additional Tailwind classes (e.g. 'text-neutral-700').
 * @param  string $size  xs|sm|md|lg|xl
 * @param  string $label Accessible label — required for standalone icon buttons.
 */
function lenvy_icon( string $name, string $class = '', string $size = 'md', string $label = '' ): void {
	get_template_part( 'template-parts/components/icon', null, [
		'name'  => $name,
		'class' => $class,
		'size'  => $size,
		'label' => $label,
	] );
}

// ─── Breadcrumb helper ────────────────────────────────────────────────────────

/**
 * Return the breadcrumb trail as an indexed array of [name, url] pairs.
 *
 * Delegates to WooCommerce's wc_get_breadcrumb() on WC pages, falls back to
 * a manual implementation for standard WordPress pages.
 *
 * @return array<int, array{0: string, 1: string}>
 */
function lenvy_get_breadcrumb_items(): array {
	// WooCommerce provides a ready-made breadcrumb array.
	if ( function_exists( 'wc_get_breadcrumb' ) ) {
		return (array) wc_get_breadcrumb();
	}

	$crumbs   = [];
	$crumbs[] = [ get_bloginfo( 'name' ), home_url( '/' ) ];

	if ( is_singular() ) {
		global $post;

		if ( $post->post_parent ) {
			$ancestors = array_reverse( get_post_ancestors( $post->ID ) );
			foreach ( $ancestors as $ancestor_id ) {
				$crumbs[] = [ get_the_title( $ancestor_id ), (string) get_permalink( $ancestor_id ) ];
			}
		}

		$crumbs[] = [ get_the_title(), '' ];

	} elseif ( is_tax() || is_category() || is_tag() ) {
		$crumbs[] = [ (string) single_term_title( '', false ), '' ];

	} elseif ( is_post_type_archive() ) {
		$crumbs[] = [ (string) post_type_archive_title( '', false ), '' ];

	} elseif ( is_search() ) {
		$crumbs[] = [ sprintf( __( 'Search: %s', 'lenvy' ), get_search_query() ), '' ];

	} elseif ( is_404() ) {
		$crumbs[] = [ __( 'Page not found', 'lenvy' ), '' ];
	}

	return $crumbs;
}

// ─── Archive title helper ─────────────────────────────────────────────────────

/**
 * Return a clean archive page title — without WordPress's default
 * "Category: ", "Tag: ", "Author: " prefixes.
 *
 * @return string
 */
function lenvy_archive_title(): string {
	if ( is_search() ) {
		return sprintf(
			/* translators: %s: search term */
			__( 'Results for: %s', 'lenvy' ),
			'<em>' . esc_html( get_search_query() ) . '</em>'
		);
	}

	if ( is_tax() || is_category() || is_tag() ) {
		return (string) single_term_title( '', false );
	}

	if ( is_post_type_archive() ) {
		return (string) post_type_archive_title( '', false );
	}

	return (string) get_the_archive_title();
}

// ─── Pagination helper ────────────────────────────────────────────────────────

/**
 * Render the pagination template part.
 * Handles both WooCommerce and standard WordPress archives.
 */
function lenvy_pagination(): void {
	get_template_part( 'template-parts/components/pagination' );
}
