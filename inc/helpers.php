<?php
/**
 * Template helper functions.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

/**
 * Renders the template-part component: container.
 *
 * @param  callable $content_callback  Callable that echoes the inner content.
 * @param  string   $classes           Additional Tailwind classes.
 * @return void
 */
function lenvy_container(callable $content_callback, string $classes = ''): void {
	get_template_part('template-parts/components/container', null, [
		'callback' => $content_callback,
		'classes' => $classes,
	]);
}

/**
 * Returns a sanitised ACF field value or a fallback string.
 *
 * @param  string $field_name  ACF field name.
 * @param  mixed  $fallback    Value to return when the field is empty.
 * @param  mixed  $post_id     Post ID or false to use current post.
 * @return mixed
 */
function lenvy_field(string $field_name, mixed $fallback = '', mixed $post_id = false): mixed {
	if (!function_exists('get_field')) {
		return $fallback;
	}

	$value = get_field($field_name, $post_id);

	return $value !== null && $value !== '' && $value !== false ? $value : $fallback;
}

/**
 * Outputs an escaped ACF text field.
 *
 * @param  string $field_name  ACF field name.
 * @param  string $fallback    Fallback string.
 * @param  mixed  $post_id     Post ID or false.
 * @return void
 */
function lenvy_the_field(string $field_name, string $fallback = '', mixed $post_id = false): void {
	echo esc_html((string) lenvy_field($field_name, $fallback, $post_id));
}

/**
 * Returns the correct archive page title with WPML-safe translation.
 *
 * @return string
 */
function lenvy_archive_title(): string {
	if (is_category()) {
		return single_cat_title('', false);
	}

	if (is_tag()) {
		return single_tag_title('', false);
	}

	if (is_author()) {
		return '<span class="vcard">' . get_the_author() . '</span>';
	}

	if (is_year()) {
		return get_the_date(_x('Y', 'yearly archives date format', 'lenvy'));
	}

	if (is_month()) {
		return get_the_date(_x('F Y', 'monthly archives date format', 'lenvy'));
	}

	if (is_day()) {
		return get_the_date(_x('F j, Y', 'daily archives date format', 'lenvy'));
	}

	if (is_tax()) {
		return single_term_title('', false);
	}

	if (is_post_type_archive()) {
		return post_type_archive_title('', false);
	}

	return esc_html__('Archives', 'lenvy');
}

/**
 * Outputs pagination for archive pages.
 *
 * @return void
 */
function lenvy_pagination(): void {
	the_posts_pagination([
		'mid_size' => 2,
		'prev_text' => esc_html__('&larr; Previous', 'lenvy'),
		'next_text' => esc_html__('Next &rarr;', 'lenvy'),
		'before_page_number' => '<span class="sr-only">' . esc_html__('Page', 'lenvy') . ' </span>',
	]);
}
