<?php
/**
 * Mobile filter drawer — full-screen panel from the left.
 *
 * Toggle: [data-filter-drawer-toggle] (in sort-bar.php)
 * Close:  [data-filter-drawer-close]
 * Backdrop: [data-filter-drawer-backdrop]
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();
?>

<!-- Backdrop -->
<div
	data-filter-drawer-backdrop
	class="fixed inset-0 z-[45] bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden"
	aria-hidden="true"
></div>

<!-- Drawer -->
<div
	id="lenvy-filter-drawer"
	data-filter-drawer
	class="fixed inset-y-0 left-0 z-[50] w-80 max-w-[calc(100vw-3rem)] bg-white overflow-y-auto -translate-x-full transition-transform duration-300 flex flex-col lg:hidden"
	aria-hidden="true"
	role="dialog"
	aria-label="<?php esc_attr_e( 'Product filters', 'lenvy' ); ?>"
>
	<!-- Drawer header -->
	<div class="flex items-center justify-between px-6 h-14 border-b border-neutral-100 shrink-0">
		<span class="text-xs font-semibold uppercase tracking-widest text-neutral-800">
			<?php esc_html_e( 'Filters', 'lenvy' ); ?>
		</span>
		<button
			type="button"
			data-filter-drawer-close
			class="p-2 text-neutral-600 hover:text-black transition-colors duration-150"
			aria-label="<?php esc_attr_e( 'Close filters', 'lenvy' ); ?>"
		>
			<?php lenvy_icon( 'close', '', 'sm' ); ?>
		</button>
	</div>

	<!-- Filter form (same as sidebar, scrollable) -->
	<div class="flex-1 overflow-y-auto px-6">
		<form method="GET" action="" data-filter-form>

			<?php if ( isset( $_GET['orderby'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification ?>
				<input type="hidden" name="orderby" value="<?php echo esc_attr( sanitize_key( $_GET['orderby'] ) ); // phpcs:ignore WordPress.Security.NonceVerification ?>">
			<?php endif; ?>

			<?php
			get_template_part( 'template-parts/shop/filter-taxonomy', null, [
				'taxonomy'  => 'product_brand',
				'query_var' => 'filter_brand',
				'label'     => __( 'Brand', 'lenvy' ),
				'open'      => true,
			] );

			get_template_part( 'template-parts/shop/filter-taxonomy', null, [
				'taxonomy'  => 'product_cat',
				'query_var' => 'filter_cat',
				'label'     => __( 'Category', 'lenvy' ),
				'open'      => true,
			] );

			get_template_part( 'template-parts/shop/filter-price', null, [
				'label' => __( 'Price', 'lenvy' ),
				'open'  => true,
			] );

			get_template_part( 'template-parts/shop/filter-taxonomy', null, [
				'taxonomy'  => 'pa_gender',
				'query_var' => 'filter_gender',
				'label'     => __( 'Gender', 'lenvy' ),
				'open'      => false,
			] );

			get_template_part( 'template-parts/shop/filter-taxonomy', null, [
				'taxonomy'  => 'pa_fragrance_family',
				'query_var' => 'filter_family',
				'label'     => __( 'Fragrance family', 'lenvy' ),
				'open'      => false,
			] );

			get_template_part( 'template-parts/shop/filter-taxonomy', null, [
				'taxonomy'  => 'pa_concentration',
				'query_var' => 'filter_conc',
				'label'     => __( 'Concentration', 'lenvy' ),
				'open'      => false,
			] );

			get_template_part( 'template-parts/shop/filter-taxonomy', null, [
				'taxonomy'  => 'pa_volume_ml',
				'query_var' => 'filter_volume',
				'label'     => __( 'Volume (ml)', 'lenvy' ),
				'open'      => false,
			] );
			?>

			<div class="border-b border-neutral-100 py-4 space-y-3">
				<?php
				// phpcs:ignore WordPress.Security.NonceVerification
				$available = ! empty( $_GET['filter_available'] );
				// phpcs:ignore WordPress.Security.NonceVerification
				$onsale    = ! empty( $_GET['filter_onsale'] );
				?>
				<label class="flex items-center justify-between cursor-pointer">
					<span class="text-sm text-neutral-700"><?php esc_html_e( 'In stock only', 'lenvy' ); ?></span>
					<input type="checkbox" name="filter_available" value="1" class="w-3.5 h-3.5 accent-black" data-filter-checkbox <?php checked( $available ); ?>>
				</label>
				<label class="flex items-center justify-between cursor-pointer">
					<span class="text-sm text-neutral-700"><?php esc_html_e( 'On sale', 'lenvy' ); ?></span>
					<input type="checkbox" name="filter_onsale" value="1" class="w-3.5 h-3.5 accent-black" data-filter-checkbox <?php checked( $onsale ); ?>>
				</label>
			</div>

		</form>
	</div>

	<!-- Sticky apply / clear footer -->
	<div class="shrink-0 px-6 py-4 border-t border-neutral-100 flex gap-3">
		<?php if ( lenvy_is_filtered() ) : ?>
			<a
				href="<?php echo esc_url( strtok( (string) $_SERVER['REQUEST_URI'], '?' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput ?>"
				class="flex-1 text-center text-xs font-medium border border-neutral-300 text-neutral-700 py-3 hover:border-black hover:text-black transition-colors duration-150"
			>
				<?php esc_html_e( 'Clear all', 'lenvy' ); ?>
			</a>
		<?php endif; ?>
		<button
			type="submit"
			form="lenvy-filter-form"
			class="flex-1 bg-black text-white text-xs font-medium uppercase tracking-widest py-3 hover:bg-neutral-800 transition-colors duration-150"
		>
			<?php esc_html_e( 'Apply', 'lenvy' ); ?>
		</button>
	</div>

</div>
