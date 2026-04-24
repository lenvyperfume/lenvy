<?php
/**
 * Desktop filter sidebar — HARDCODED options from placeholder-data.php.
 *
 * No-op form: toggles render but don't mutate state. To be re-wired once
 * the shop moves off placeholder data.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$shop_data = $args['shop_data'] ?? null;
if (!$shop_data) {
	$shop_data = require get_theme_file_path('template-parts/shop/placeholder-data.php');
}

$hide_brand_filter = (bool) ($args['hide_brand_filter'] ?? false);

// Convert ml sizes to "30ml" labels.
$size_options = array_map(static fn($s) => $s . 'ml', $shop_data['sizes']);
?>

<aside
	class="lenvy-filters hidden lg:block"
	aria-label="<?php esc_attr_e('Productfilters', 'lenvy'); ?>"
	data-filter-sidebar
>
	<div class="lenvy-filters__inner">

		<form method="GET" action="" data-filter-form id="lenvy-filter-form">

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
				'open'    => true,
			]);

			if (!$hide_brand_filter) {
				get_template_part('template-parts/shop/filter-taxonomy', null, [
					'name'       => 'brand',
					'label'      => __('Merk', 'lenvy'),
					'options'    => $shop_data['brands'],
					'open'       => true,
					'searchable' => true,
				]);
			}

			get_template_part('template-parts/shop/filter-price', null, [
				'label' => __('Prijs', 'lenvy'),
				'open'  => true,
				'price' => $shop_data['price'],
			]);

			get_template_part('template-parts/shop/filter-taxonomy', null, [
				'name'    => 'size',
				'label'   => __('Grootte', 'lenvy'),
				'options' => $size_options,
				'open'    => true,
			]);
			?>

		</form>
	</div>
</aside>
