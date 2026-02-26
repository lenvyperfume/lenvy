<?php
/**
 * Filter drawer — slide-out panel from the left (all breakpoints).
 *
 * Toggle: [data-filter-drawer-toggle] (in sort-bar.php)
 * Close:  [data-filter-drawer-close]
 * Backdrop: [data-filter-drawer-backdrop]
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// When on a brand archive, suppress the Brand filter (it's redundant).
$hide_brand_filter = (bool) ($args['hide_brand_filter'] ?? false);
?>

<!-- Backdrop -->
<div
	data-filter-drawer-backdrop
	class="fixed inset-0 z-[45] bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300"
	aria-hidden="true"
></div>

<!-- Drawer -->
<div
	id="lenvy-filter-drawer"
	data-filter-drawer
	class="fixed inset-y-0 left-0 z-[50] w-[340px] max-w-[calc(100vw-3rem)] bg-white overflow-y-auto -translate-x-full transition-transform duration-300 flex flex-col"
	aria-hidden="true"
	role="dialog"
	aria-label="<?php esc_attr_e('Product filters', 'lenvy'); ?>"
>
	<!-- Header -->
	<div class="flex items-center justify-between px-6 h-14 border-b border-neutral-100 shrink-0">
		<span class="text-[11px] font-semibold uppercase tracking-widest text-neutral-800">
			<?php esc_html_e('Filters', 'lenvy'); ?>
		</span>
		<button
			type="button"
			data-filter-drawer-close
			class="p-2 -mr-2 text-neutral-400 hover:text-black transition-colors duration-150"
			aria-label="<?php esc_attr_e('Close filters', 'lenvy'); ?>"
		>
			<?php lenvy_icon('close', '', 'sm'); ?>
		</button>
	</div>

	<!-- Filter form (scrollable body) -->
	<div class="flex-1 overflow-y-auto px-6">
		<form method="GET" action="" id="lenvy-filter-drawer-form" data-filter-form>

			<?php if (isset($_GET['orderby'])):
				// phpcs:ignore WordPress.Security.NonceVerification
			?>
				<input type="hidden" name="orderby" value="<?php echo esc_attr(sanitize_key($_GET['orderby']));
				// phpcs:ignore WordPress.Security.NonceVerification
				?>">
			<?php endif; ?>

			<?php
			if (!$hide_brand_filter):
				get_template_part('template-parts/shop/filter-taxonomy', null, [
					'taxonomy'  => 'product_brand',
					'query_var' => 'filter_brand',
					'label'     => __('Merk', 'lenvy'),
					'open'      => true,
				]);
			endif;

			get_template_part('template-parts/shop/filter-taxonomy', null, [
				'taxonomy'  => 'product_cat',
				'query_var' => 'filter_cat',
				'label'     => __('Categorie', 'lenvy'),
				'open'      => true,
			]);

			get_template_part('template-parts/shop/filter-price', null, [
				'label' => __('Prijs', 'lenvy'),
				'open'  => true,
			]);

			get_template_part('template-parts/shop/filter-taxonomy', null, [
				'taxonomy'  => 'pa_gender',
				'query_var' => 'filter_gender',
				'label'     => __('Geslacht', 'lenvy'),
				'open'      => false,
			]);

			get_template_part('template-parts/shop/filter-taxonomy', null, [
				'taxonomy'  => 'pa_fragrance_family',
				'query_var' => 'filter_family',
				'label'     => __('Geurfamilie', 'lenvy'),
				'open'      => false,
			]);

			get_template_part('template-parts/shop/filter-taxonomy', null, [
				'taxonomy'  => 'pa_concentration',
				'query_var' => 'filter_conc',
				'label'     => __('Concentratie', 'lenvy'),
				'open'      => false,
			]);

			get_template_part('template-parts/shop/filter-taxonomy', null, [
				'taxonomy'  => 'pa_volume_ml',
				'query_var' => 'filter_volume',
				'label'     => __('Volume (ml)', 'lenvy'),
				'open'      => false,
			]);
			?>

			<div class="border-b border-neutral-100 py-4 space-y-3">
				<?php
				// phpcs:ignore WordPress.Security.NonceVerification
				$available = !empty($_GET['filter_available']);
				// phpcs:ignore WordPress.Security.NonceVerification
				$onsale = !empty($_GET['filter_onsale']);
				?>
				<label class="flex items-center justify-between cursor-pointer">
					<span class="text-sm text-neutral-700"><?php esc_html_e('Op voorraad', 'lenvy'); ?></span>
					<input type="checkbox" name="filter_available" value="1" class="w-3.5 h-3.5 accent-black" data-filter-checkbox <?php checked($available); ?>>
				</label>
				<label class="flex items-center justify-between cursor-pointer">
					<span class="text-sm text-neutral-700"><?php esc_html_e('Sale', 'lenvy'); ?></span>
					<input type="checkbox" name="filter_onsale" value="1" class="w-3.5 h-3.5 accent-black" data-filter-checkbox <?php checked($onsale); ?>>
				</label>
			</div>

		</form>
	</div>

	<!-- Sticky footer -->
	<div class="shrink-0 px-6 py-4 border-t border-neutral-100 flex gap-3">
		<?php if (lenvy_is_filtered()): ?>
			<a
				href="<?php echo esc_url(strtok((string) $_SERVER['REQUEST_URI'], '?'));
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				?>"
				class="flex-1 inline-flex items-center justify-center h-11 text-[11px] font-medium uppercase tracking-widest border border-neutral-300 text-neutral-700 hover:border-neutral-900 hover:text-neutral-900 transition-colors"
			>
				<?php esc_html_e('Wis alles', 'lenvy'); ?>
			</a>
		<?php endif; ?>
		<button
			type="submit"
			form="lenvy-filter-drawer-form"
			class="flex-1 inline-flex items-center justify-center h-11 bg-primary text-black text-[11px] font-medium uppercase tracking-widest hover:bg-primary-hover transition-colors"
		>
			<?php esc_html_e('Toepassen', 'lenvy'); ?>
		</button>
	</div>

</div>
