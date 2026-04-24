<?php
/**
 * Filter drawer — HARDCODED slide-out panel (all breakpoints).
 *
 * Mirrors filter-sidebar but lives in a left-slide drawer for mobile.
 * Uses the same placeholder-data source.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$shop_data = $args['shop_data'] ?? null;
if (!$shop_data) {
	$shop_data = require get_theme_file_path('template-parts/shop/placeholder-data.php');
}

$hide_brand_filter = (bool) ($args['hide_brand_filter'] ?? false);
$size_options      = array_map(static fn($s) => $s . 'ml', $shop_data['sizes']);
?>

<div
	data-filter-drawer-backdrop
	class="fixed inset-0 z-[45] bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300"
	aria-hidden="true"
></div>

<div
	id="lenvy-filter-drawer"
	data-filter-drawer
	class="fixed inset-y-0 left-0 z-[50] w-[400px] max-w-[calc(100vw-3rem)] bg-white overflow-y-auto -translate-x-full transition-transform duration-300 flex flex-col"
	aria-hidden="true"
	role="dialog"
	aria-label="<?php esc_attr_e('Productfilters', 'lenvy'); ?>"
>
	<div class="flex items-center justify-between px-7 h-16 border-b border-neutral-100 shrink-0">
		<span class="text-xs font-semibold uppercase tracking-widest text-neutral-800">
			<?php esc_html_e('Filters', 'lenvy'); ?>
		</span>
		<button
			type="button"
			data-filter-drawer-close
			class="p-2 -mr-2 text-neutral-400 hover:text-black transition-colors duration-200"
			aria-label="<?php esc_attr_e('Filters sluiten', 'lenvy'); ?>"
		>
			<?php lenvy_icon('close', '', 'sm'); ?>
		</button>
	</div>

	<div class="flex-1 overflow-y-auto px-7">
		<form method="GET" action="" id="lenvy-filter-drawer-form" data-filter-form>

			<?php
			get_template_part('template-parts/shop/filter-taxonomy', null, [
				'name'    => 'collection',
				'label'   => __('Collectie', 'lenvy'),
				'options' => $shop_data['collections'],
				'open'    => true,
			]);

			get_template_part('template-parts/shop/filter-taxonomy', null, [
				'name'    => 'gender',
				'label'   => __('Geslacht', 'lenvy'),
				'options' => $shop_data['genders'],
				'open'    => true,
			]);

			get_template_part('template-parts/shop/filter-taxonomy', null, [
				'name'    => 'family',
				'label'   => __('Geurfamilie', 'lenvy'),
				'options' => $shop_data['families'],
				'open'    => false,
			]);

			if (!$hide_brand_filter) {
				get_template_part('template-parts/shop/filter-taxonomy', null, [
					'name'       => 'brand',
					'label'      => __('Merk', 'lenvy'),
					'options'    => $shop_data['brands'],
					'open'       => false,
					'searchable' => true,
				]);
			}

			get_template_part('template-parts/shop/filter-price', null, [
				'label' => __('Prijs', 'lenvy'),
				'open'  => false,
				'price' => $shop_data['price'],
			]);

			get_template_part('template-parts/shop/filter-taxonomy', null, [
				'name'    => 'size',
				'label'   => __('Grootte', 'lenvy'),
				'options' => $size_options,
				'open'    => false,
			]);
			?>

		</form>
	</div>

	<div class="shrink-0 px-7 py-5 border-t border-neutral-100 flex gap-3">
		<button
			type="submit"
			form="lenvy-filter-drawer-form"
			class="flex-1 inline-flex items-center justify-center h-11 bg-black text-white text-[11px] font-medium uppercase tracking-widest hover:bg-neutral-800 transition-colors"
		>
			<?php esc_html_e('Toepassen', 'lenvy'); ?>
		</button>
	</div>

</div>
