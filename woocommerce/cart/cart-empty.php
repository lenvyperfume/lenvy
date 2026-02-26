<?php
/**
 * Empty cart page — on-brand empty state.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Lenvy
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

// Preserve hook for plugin compatibility (output suppressed).
ob_start();
do_action( 'woocommerce_cart_is_empty' );
ob_end_clean();
?>

<div class="lenvy-container py-16 lg:py-24">
	<div class="flex flex-col items-center text-center max-w-xs mx-auto">

		<!-- Icon -->
		<div class="mb-6 text-neutral-200">
			<svg width="56" height="56" viewBox="0 0 56 56" fill="none" stroke="currentColor" stroke-width="1.25" aria-hidden="true">
				<path d="M14 18h28l-4 20H18L14 18z"/>
				<path d="M10 12h36"/>
				<circle cx="22" cy="44" r="2" fill="currentColor" stroke="none"/>
				<circle cx="34" cy="44" r="2" fill="currentColor" stroke="none"/>
			</svg>
		</div>

		<h2 class="text-2xl font-serif italic text-neutral-900 mb-3">
			<?php esc_html_e( 'Je winkelwagen is leeg', 'lenvy' ); ?>
		</h2>
		<p class="text-sm text-neutral-500 mb-8 leading-relaxed">
			<?php esc_html_e( 'Ontdek ons assortiment parfums en voeg je favorieten toe.', 'lenvy' ); ?>
		</p>

		<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
			<a
				href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"
				class="text-sm font-medium tracking-wide bg-primary text-black px-8 py-3 hover:bg-primary-hover transition-colors duration-150"
			>
				<?php esc_html_e( 'Bekijk onze collectie', 'lenvy' ); ?>
			</a>
		<?php endif; ?>

	</div>
</div>
