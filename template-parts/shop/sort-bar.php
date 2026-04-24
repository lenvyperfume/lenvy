<?php
/**
 * Shop toolbar — HARDCODED sticky bar with results count + sort dropdown.
 *
 * No-op buttons: the sort dropdown and filter toggle render the design but
 * don't mutate state. Re-wire to WC / URL params once real products exist.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$shop_data = $args['shop_data'] ?? null;
if (!$shop_data) {
	$shop_data = require get_theme_file_path('template-parts/shop/placeholder-data.php');
}

$total = (int) ($shop_data['totals']['results'] ?? 0);

$orderby_options = [
	'popular'    => __('Populair', 'lenvy'),
	'new'        => __('Nieuw', 'lenvy'),
	'price-asc'  => __('Prijs: laag → hoog', 'lenvy'),
	'price-desc' => __('Prijs: hoog → laag', 'lenvy'),
	'sale'       => __('Afgeprijsd eerst', 'lenvy'),
];
$current_orderby = 'popular';
?>

<div class="lenvy-toolbar" data-sort-bar>
	<div class="lenvy-container lenvy-toolbar__inner">

		<div class="lenvy-toolbar__left">
			<button
				type="button"
				data-filter-drawer-toggle
				class="lenvy-toolbar__filter-btn lg:hidden"
				aria-expanded="false"
				aria-controls="lenvy-filter-drawer"
			>
				<?php lenvy_icon('filter', '', 'sm'); ?>
				<span><?php esc_html_e('Filter', 'lenvy'); ?></span>
			</button>

			<p class="lenvy-toolbar__count" data-results-count>
				<b><?php echo esc_html(number_format_i18n($total)); ?></b>
				<?php esc_html_e('resultaten', 'lenvy'); ?>
			</p>
		</div>

		<div class="lenvy-toolbar__right">
			<div class="lenvy-sort" data-sort-dropdown data-sort-no-reload>
				<button
					type="button"
					data-sort-trigger
					class="lenvy-sort__trigger"
					aria-expanded="false"
					aria-haspopup="listbox"
				>
					<span>
						<?php esc_html_e('Sorteer:', 'lenvy'); ?>
						<b data-sort-label><?php echo esc_html($orderby_options[$current_orderby]); ?></b>
					</span>
					<svg width="10" height="6" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M1 1l4 4 4-4"/></svg>
				</button>
				<div
					data-sort-options
					class="lenvy-sort__menu"
					role="listbox"
					aria-label="<?php esc_attr_e('Sorteer op', 'lenvy'); ?>"
				>
					<?php foreach ($orderby_options as $value => $label):
						$is_active = $current_orderby === $value;
					?>
					<button
						type="button"
						data-sort-value="<?php echo esc_attr($value); ?>"
						class="lenvy-sort__option<?php echo $is_active ? ' is-active' : ''; ?>"
						role="option"
						<?php if ($is_active): ?>aria-selected="true"<?php endif; ?>
					>
						<span><?php echo esc_html($label); ?></span>
						<?php if ($is_active): ?><span aria-hidden="true">✓</span><?php endif; ?>
					</button>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

	</div>
</div>
