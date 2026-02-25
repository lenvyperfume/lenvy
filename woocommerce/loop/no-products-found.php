<?php
/**
 * No products found — branded empty state.
 *
 * Called via do_action('woocommerce_no_products_found') when the product
 * loop is empty. Provides a clear-filters CTA when filters are active,
 * or a back-to-shop link otherwise.
 *
 * @package Lenvy
 * @see     WC templates/loop/no-products-found.php
 */

defined( 'ABSPATH' ) || exit();

$is_filtered = function_exists( 'lenvy_is_filtered' ) && lenvy_is_filtered();

// Strip query params from the current URL to build the "clear filters" href.
// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
$base_url = esc_url( strtok( wp_unslash( $_SERVER['REQUEST_URI'] ?? '/' ), '?' ) );
?>

<div class="lenvy-no-products py-24 text-center">

	<p class="text-xs font-medium uppercase tracking-[0.15em] text-neutral-400 mb-3">
		<?php esc_html_e( 'No results', 'lenvy' ); ?>
	</p>

	<p class="text-sm text-neutral-500 mb-10 max-w-xs mx-auto leading-relaxed">
		<?php esc_html_e( 'No products were found matching your selection.', 'lenvy' ); ?>
	</p>

	<?php if ( $is_filtered ) : ?>

		<a
			href="<?php echo $base_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — escaped above. ?>"
			class="inline-block text-xs font-medium uppercase tracking-widest border border-neutral-900 text-neutral-900 px-7 py-3 hover:bg-neutral-900 hover:text-white transition-colors duration-150"
		>
			<?php esc_html_e( 'Clear all filters', 'lenvy' ); ?>
		</a>

	<?php else : ?>

		<a
			href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>"
			class="inline-block text-xs font-medium uppercase tracking-widest border border-neutral-900 text-neutral-900 px-7 py-3 hover:bg-neutral-900 hover:text-white transition-colors duration-150"
		>
			<?php esc_html_e( 'Back to shop', 'lenvy' ); ?>
		</a>

	<?php endif; ?>

</div><!-- .lenvy-no-products -->
