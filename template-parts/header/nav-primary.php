<?php
/**
 * Desktop primary navigation — centred link row.
 *
 * Spec (matches Homepage.html):
 *   gap 36px · 13px text · 0.04em tracking · 16px vertical padding
 *   active / hover → 2px lavender underline at bottom:10px
 *   items with a "sale" CSS class (or titled "Sale") render in burgundy #b8005a
 *
 * When no primary menu is assigned in Appearance → Menus a hardcoded
 * fallback matching the design is rendered.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

if (has_nav_menu('primary')) {
	?>
	<nav class="flex justify-center" aria-label="<?php esc_attr_e('Hoofdnavigatie', 'lenvy'); ?>">
		<?php wp_nav_menu([
			'theme_location' => 'primary',
			'menu_class'     => 'flex items-center justify-center gap-9 m-0 p-0 list-none',
			'container'      => false,
			'walker'         => new Lenvy_Primary_Nav_Walker(),
			'fallback_cb'    => false,
			'depth'          => 2,
		]); ?>
	</nav>
	<?php
	return;
}

/* ── Hardcoded fallback (no menu assigned) ─────────────────────────── */

$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');

$fallback = [
	['label' => __('Nieuw binnen', 'lenvy'), 'url' => add_query_arg('orderby', 'date', $shop_url), 'active' => true],
	['label' => __('Dames',        'lenvy'), 'url' => $shop_url],
	['label' => __('Heren',        'lenvy'), 'url' => $shop_url],
	['label' => __('Unisex',       'lenvy'), 'url' => $shop_url],
	['label' => __('Merken',       'lenvy'), 'url' => $shop_url],
	['label' => __('Niche',        'lenvy'), 'url' => $shop_url],
	['label' => __('Sale',         'lenvy'), 'url' => add_query_arg('filter_onsale', '1', $shop_url), 'sale' => true],
];
?>
<nav class="flex justify-center" aria-label="<?php esc_attr_e('Hoofdnavigatie', 'lenvy'); ?>">
	<ul class="flex items-center justify-center gap-9 m-0 p-0 list-none">
		<?php foreach ($fallback as $item):
			$is_active = ! empty($item['active']);
			$is_sale   = ! empty($item['sale']);

			$link_class = 'lenvy-nav-link';
			if ($is_sale) {
				$link_class .= ' is-sale';
			}
			if ($is_active) {
				$link_class .= ' is-active';
			}
		?>
			<li class="relative">
				<a
					href="<?php echo esc_url($item['url']); ?>"
					class="<?php echo esc_attr($link_class); ?>"
				>
					<?php echo esc_html($item['label']); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
