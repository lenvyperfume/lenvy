<?php
/**
 * Desktop filter sidebar — sticky left panel (lg+).
 *
 * Wraps all filter components in a GET form.
 * Hidden on mobile (filter-drawer.php handles mobile).
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// When on a brand archive, suppress the Brand filter (it's redundant).
$hide_brand_filter = (bool) ($args['hide_brand_filter'] ?? false);
?>

<aside
	class="hidden lg:block w-[280px] shrink-0"
	aria-label="<?php esc_attr_e('Product filters', 'lenvy'); ?>"
	data-filter-sidebar
>
	<div class="sticky" style="top: var(--header-height, 68px);">

		<div class="flex items-center justify-between pb-4 border-b border-neutral-100">
			<h2 class="text-xs font-semibold uppercase tracking-widest text-neutral-800">
				<?php esc_html_e('Filters', 'lenvy'); ?>
			</h2>
			<?php if (lenvy_is_filtered()): ?>
				<a
					href="<?php echo esc_url(strtok((string) $_SERVER['REQUEST_URI'], '?'));
   	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
   	?>"
					class="text-xs text-neutral-500 hover:text-black underline underline-offset-2 transition-colors duration-150"
				>
					<?php esc_html_e('Clear all', 'lenvy'); ?>
				</a>
			<?php endif; ?>
		</div>

		<form method="GET" action="" data-filter-form id="lenvy-filter-form">

			<?php if (isset($_GET['orderby'])):// phpcs:ignore WordPress.Security.NonceVerification
   	 ?>
				<input type="hidden" name="orderby" value="<?php echo esc_attr(sanitize_key($_GET['orderby']));
   	// phpcs:ignore WordPress.Security.NonceVerification
   	?>">
			<?php endif; ?>

			<?php
   if (!$hide_brand_filter):
   	get_template_part('template-parts/shop/filter-taxonomy', null, [
   		'taxonomy' => 'product_brand',
   		'query_var' => 'filter_brand',
   		'label' => __('Brand', 'lenvy'),
   		'open' => true,
   	]);
   endif;

   get_template_part('template-parts/shop/filter-taxonomy', null, [
   	'taxonomy' => 'product_cat',
   	'query_var' => 'filter_cat',
   	'label' => __('Category', 'lenvy'),
   	'open' => true,
   ]);

   get_template_part('template-parts/shop/filter-price', null, [
   	'label' => __('Price', 'lenvy'),
   	'open' => true,
   ]);

   get_template_part('template-parts/shop/filter-taxonomy', null, [
   	'taxonomy' => 'pa_gender',
   	'query_var' => 'filter_gender',
   	'label' => __('Gender', 'lenvy'),
   	'open' => false,
   ]);

   get_template_part('template-parts/shop/filter-taxonomy', null, [
   	'taxonomy' => 'pa_fragrance_family',
   	'query_var' => 'filter_family',
   	'label' => __('Fragrance family', 'lenvy'),
   	'open' => false,
   ]);

   get_template_part('template-parts/shop/filter-taxonomy', null, [
   	'taxonomy' => 'pa_concentration',
   	'query_var' => 'filter_conc',
   	'label' => __('Concentration', 'lenvy'),
   	'open' => false,
   ]);

   get_template_part('template-parts/shop/filter-taxonomy', null, [
   	'taxonomy' => 'pa_volume_ml',
   	'query_var' => 'filter_volume',
   	'label' => __('Volume (ml)', 'lenvy'),
   	'open' => false,
   ]);
   ?>

			<!-- In stock + On sale toggles -->
			<div class="border-b border-neutral-100 py-4 space-y-3">
				<?php
    // phpcs:ignore WordPress.Security.NonceVerification
    $available = !empty($_GET['filter_available']);
    // phpcs:ignore WordPress.Security.NonceVerification
    $onsale = !empty($_GET['filter_onsale']);
    ?>
				<label class="flex items-center justify-between cursor-pointer">
					<span class="text-sm text-neutral-700"><?php esc_html_e('In stock only', 'lenvy'); ?></span>
					<input
						type="checkbox"
						name="filter_available"
						value="1"
						class="w-3.5 h-3.5 accent-primary"
						data-filter-checkbox
						<?php checked($available); ?>
					>
				</label>
				<label class="flex items-center justify-between cursor-pointer">
					<span class="text-sm text-neutral-700"><?php esc_html_e('On sale', 'lenvy'); ?></span>
					<input
						type="checkbox"
						name="filter_onsale"
						value="1"
						class="w-3.5 h-3.5 accent-primary"
						data-filter-checkbox
						<?php checked($onsale); ?>
					>
				</label>
			</div>

			<button
				type="submit"
				class="mt-5 w-full bg-primary text-black text-xs font-medium uppercase tracking-widest py-3 hover:bg-primary-hover transition-colors duration-150"
			>
				<?php esc_html_e('Apply filters', 'lenvy'); ?>
			</button>

		</form>
	</div>
</aside>
