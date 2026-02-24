<?php
/**
 * Admin enhancements.
 *
 * Handles:
 * - Custom product list columns (thumbnail, brand)
 * - Admin-only styles for product list columns
 *
 * @package Lenvy
 */

defined( 'ABSPATH' ) || exit();

// ─── Product list: add thumbnail + brand columns ───────────────────────────────

/**
 * Register custom columns in the product post-type list table.
 *
 * Inserts "Image" before the title and "Brand" after the title.
 *
 * @param  array $columns Existing column definitions.
 * @return array
 */
add_filter( 'manage_edit-product_columns', static function ( array $columns ): array {
	$new = [];

	foreach ( $columns as $key => $label ) {
		if ( $key === 'name' ) {
			$new['lenvy_thumb'] = __( 'Image', 'lenvy' );
		}

		$new[ $key ] = $label;

		if ( $key === 'name' ) {
			$new['lenvy_brand'] = __( 'Brand', 'lenvy' );
		}
	}

	return $new;
} );

/**
 * Populate custom columns for each product row.
 *
 * @param  string $column  Column key.
 * @param  int    $post_id Current product post ID.
 */
add_action( 'manage_product_posts_custom_column', static function ( string $column, int $post_id ): void {
	switch ( $column ) {
		case 'lenvy_thumb':
			$thumb = get_the_post_thumbnail( $post_id, [ 40, 40 ] );
			if ( $thumb ) {
				echo '<div class="lenvy-admin-thumb">' . $thumb . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput — wp_get_attachment_image is safe
			} else {
				echo '<span aria-hidden="true" style="color:#ccc">—</span>';
			}
			break;

		case 'lenvy_brand':
			$terms = get_the_terms( $post_id, 'product_brand' );

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$links = array_map( static function ( WP_Term $term ): string {
					$edit_url = (string) get_edit_term_link( $term->term_id, 'product_brand', 'product' );
					return '<a href="' . esc_url( $edit_url ) . '">' . esc_html( $term->name ) . '</a>';
				}, $terms );

				echo implode( ', ', $links ); // phpcs:ignore WordPress.Security.EscapeOutput — links already escaped
			} else {
				echo '<span style="color:#999">—</span>';
			}
			break;
	}
}, 10, 2 );

/**
 * Make the brand column sortable.
 *
 * @param  array $sortable Existing sortable columns.
 * @return array
 */
add_filter( 'manage_edit-product_sortable_columns', static function ( array $sortable ): array {
	$sortable['lenvy_brand'] = 'lenvy_brand';
	return $sortable;
} );

/**
 * Inject inline CSS to size the thumbnail column and image consistently.
 */
add_action( 'admin_head', static function (): void {
	$screen = get_current_screen();

	if ( ! $screen || 'edit-product' !== $screen->id ) {
		return;
	}
	?>
	<style>
		.column-lenvy_thumb { width: 52px; }
		.column-lenvy_brand { width: 140px; }
		.lenvy-admin-thumb img {
			width: 40px;
			height: 40px;
			object-fit: cover;
			border-radius: 2px;
			display: block;
		}
	</style>
	<?php
} );
