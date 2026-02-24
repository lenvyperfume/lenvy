<?php
/**
 * Shop sort bar — results count + sort dropdown + mobile filter toggle.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

global $wp_query;

$total   = (int) $wp_query->found_posts;
$current = max( 1, (int) get_query_var( 'paged' ) );
$per     = (int) get_option( 'posts_per_page' );
$from    = ( ( $current - 1 ) * $per ) + 1;
$to      = min( $current * $per, $total );

$orderby_options = [
	'menu_order' => __( 'Default sorting', 'lenvy' ),
	'popularity' => __( 'Popularity', 'lenvy' ),
	'rating'     => __( 'Average rating', 'lenvy' ),
	'date'       => __( 'Newest', 'lenvy' ),
	'price'      => __( 'Price: low to high', 'lenvy' ),
	'price-desc' => __( 'Price: high to low', 'lenvy' ),
];

$current_orderby = (string) ( isset( $_GET['orderby'] ) ? sanitize_key( $_GET['orderby'] ) : get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) ); // phpcs:ignore WordPress.Security.NonceVerification
?>

<div class="flex items-center justify-between gap-4 py-4 border-b border-neutral-100">

	<!-- Mobile filter toggle -->
	<button
		type="button"
		data-filter-drawer-toggle
		class="flex items-center gap-2 text-sm font-medium text-neutral-700 hover:text-black transition-colors duration-150 lg:hidden"
		aria-expanded="false"
		aria-controls="lenvy-filter-drawer"
	>
		<?php lenvy_icon( 'filter', '', 'sm' ); ?>
		<?php esc_html_e( 'Filters', 'lenvy' ); ?>
		<?php if ( lenvy_is_filtered() ) : ?>
			<span class="flex items-center justify-center w-4 h-4 bg-black text-white text-[10px] font-semibold rounded-full leading-none" aria-hidden="true">
				<?php echo esc_html( count( lenvy_get_active_filters() ) ); ?>
			</span>
		<?php endif; ?>
	</button>

	<!-- Results count -->
	<p class="text-xs text-neutral-500 hidden sm:block" data-results-count>
		<?php if ( $total > 0 ) : ?>
			<?php
			echo esc_html(
				sprintf(
					/* translators: 1: from, 2: to, 3: total */
					__( 'Showing %1$s–%2$s of %3$s products', 'lenvy' ),
					$from,
					$to,
					$total
				)
			);
			?>
		<?php else : ?>
			<?php esc_html_e( 'No products found', 'lenvy' ); ?>
		<?php endif; ?>
	</p>

	<!-- Sort dropdown -->
	<div class="ml-auto flex items-center gap-2">
		<label for="lenvy-sort" class="text-xs text-neutral-500 shrink-0 hidden sm:inline">
			<?php esc_html_e( 'Sort by', 'lenvy' ); ?>
		</label>
		<select
			id="lenvy-sort"
			name="orderby"
			class="text-xs border border-neutral-200 bg-white text-neutral-800 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-black cursor-pointer"
			data-sort-select
		>
			<?php foreach ( $orderby_options as $value => $label ) : ?>
				<option
					value="<?php echo esc_attr( $value ); ?>"
					<?php selected( $current_orderby, $value ); ?>
				>
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>

</div>
