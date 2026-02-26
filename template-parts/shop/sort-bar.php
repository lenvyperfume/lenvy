<?php
/**
 * Shop sort bar — filter toggle + results count + sort dropdown.
 *
 * The filter button opens the filter drawer at all breakpoints
 * (no permanent sidebar).
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

global $wp_query;

$total   = (int) $wp_query->found_posts;
$current = max(1, (int) get_query_var('paged'));
$per     = (int) get_option('posts_per_page');
$from    = ($current - 1) * $per + 1;
$to      = min($current * $per, $total);

$orderby_options = [
	'menu_order'  => __('Standaard', 'lenvy'),
	'popularity'  => __('Populariteit', 'lenvy'),
	'date'        => __('Nieuwste', 'lenvy'),
	'price'       => __('Prijs: laag → hoog', 'lenvy'),
	'price-desc'  => __('Prijs: hoog → laag', 'lenvy'),
];

$current_orderby = (string) (isset($_GET['orderby'])
	? sanitize_key($_GET['orderby'])
	: get_option('woocommerce_default_catalog_orderby', 'menu_order'));

// phpcs:ignore WordPress.Security.NonceVerification
$filter_count = count(lenvy_get_active_filters());
?>

<div class="flex items-center gap-4 py-4 border-b border-neutral-100" data-sort-bar>

	<!-- Filter toggle (all breakpoints) -->
	<button
		type="button"
		data-filter-drawer-toggle
		class="inline-flex items-center gap-2 text-sm font-medium text-neutral-700 hover:text-black transition-colors duration-150"
		aria-expanded="false"
		aria-controls="lenvy-filter-drawer"
	>
		<?php lenvy_icon('filter', '', 'sm'); ?>
		<span><?php esc_html_e('Filter', 'lenvy'); ?></span>
		<?php if ($filter_count > 0): ?>
			<span class="flex items-center justify-center w-5 h-5 bg-black text-white text-[10px] font-semibold rounded-full leading-none" aria-hidden="true">
				<?php echo esc_html($filter_count); ?>
			</span>
		<?php endif; ?>
	</button>

	<!-- Results count -->
	<p class="text-[13px] text-neutral-400 hidden sm:block" data-results-count>
		<?php if ($total > 0): ?>
			<?php echo esc_html(
				sprintf(
					/* translators: %d: total product count */
					_n('%d product', '%d producten', $total, 'lenvy'),
					$total,
				),
			); ?>
		<?php else: ?>
			<?php esc_html_e('Geen producten gevonden', 'lenvy'); ?>
		<?php endif; ?>
	</p>

	<!-- Sort dropdown -->
	<div class="ml-auto">
		<select
			id="lenvy-sort"
			name="orderby"
			class="text-[13px] border border-neutral-200 bg-white text-neutral-600 pl-3 pr-8 py-2 focus:outline-none focus:border-neutral-900 cursor-pointer appearance-none"
			style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23737373' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 0.75rem center;"
			data-sort-select
		>
			<?php foreach ($orderby_options as $value => $label): ?>
				<option
					value="<?php echo esc_attr($value); ?>"
					<?php selected($current_orderby, $value); ?>
				>
					<?php echo esc_html($label); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>

</div>
