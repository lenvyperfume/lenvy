<?php
/**
 * Homepage — featured products row.
 *
 * Renders up to 8 ACF-selected products as a 4-column grid.
 * This uses an inline product card; it will be replaced by the
 * product-card.php component in Phase 7.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

if ( ! function_exists( 'wc_get_product' ) ) {
	return;
}

$product_ids = lenvy_field( 'lenvy_featured_products' );

if ( empty( $product_ids ) ) {
	return;
}

$shop_url = function_exists( 'wc_get_page_permalink' )
	? wc_get_page_permalink( 'shop' )
	: get_post_type_archive_link( 'product' );
?>

<section class="py-16 lg:py-24 bg-neutral-50">
	<div class="lenvy-container">

		<!-- Section header -->
		<div class="flex items-center justify-between mb-8 lg:mb-12">
			<h2 class="text-xs font-medium uppercase tracking-widest text-neutral-500">
				<?php esc_html_e( 'Featured', 'lenvy' ); ?>
			</h2>
			<a
				href="<?php echo esc_url( $shop_url ?: home_url( '/shop/' ) ); ?>"
				class="flex items-center gap-1.5 text-xs font-medium uppercase tracking-widest text-neutral-400 hover:text-black transition-colors duration-150"
			>
				<?php esc_html_e( 'View all', 'lenvy' ); ?>
				<?php lenvy_icon( 'arrow-right', '', 'xs' ); ?>
			</a>
		</div>

		<!-- Product grid -->
		<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-10">

			<?php foreach ( (array) $product_ids as $product_id ) :

				$product = wc_get_product( (int) $product_id );
				if ( ! $product || 'publish' !== $product->get_status() ) {
					continue;
				}

				$image_id    = $product->get_image_id();
				$price_html  = $product->get_price_html();
				$product_url = get_permalink( $product_id );
				$is_on_sale  = $product->is_on_sale();

				// Brand name from product_brand taxonomy
				$brand_terms = get_the_terms( (int) $product_id, 'product_brand' );
				$brand_name  = ( $brand_terms && ! is_wp_error( $brand_terms ) )
					? $brand_terms[0]->name
					: '';
			?>

			<article>
				<a href="<?php echo esc_url( $product_url ); ?>" class="group block">

					<!-- Product image -->
					<div class="relative overflow-hidden bg-white aspect-[3/4] mb-4">
						<?php if ( $image_id ) : ?>
							<?php
							echo wp_get_attachment_image( $image_id, 'woocommerce_thumbnail', false, [
								'class'   => 'w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105',
								'loading' => 'lazy',
								'alt'     => esc_attr( $product->get_name() ),
							] );
							?>
						<?php elseif ( function_exists( 'wc_placeholder_img' ) ) : ?>
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — WC placeholder is trusted
							echo wc_placeholder_img( 'woocommerce_thumbnail', [ 'class' => 'w-full h-full object-cover' ] );
							?>
						<?php endif; ?>

						<!-- Sale badge -->
						<?php if ( $is_on_sale ) : ?>
							<div class="absolute top-3 left-3">
								<?php
								get_template_part( 'template-parts/components/badge', null, [
									'text'    => __( 'Sale', 'lenvy' ),
									'variant' => 'sale',
								] );
								?>
							</div>
						<?php endif; ?>
					</div>

					<!-- Product info -->
					<div>
						<?php if ( $brand_name ) : ?>
							<p class="text-[11px] font-medium uppercase tracking-widest text-neutral-400 mb-1">
								<?php echo esc_html( $brand_name ); ?>
							</p>
						<?php endif; ?>

						<h3 class="text-sm text-neutral-800 leading-snug line-clamp-2 mb-1.5 group-hover:text-black transition-colors duration-150">
							<?php echo esc_html( $product->get_name() ); ?>
						</h3>

						<div class="text-sm font-medium text-neutral-700">
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — WC price HTML is trusted
							echo $price_html;
							?>
						</div>
					</div>

				</a>
			</article>

			<?php endforeach; ?>

		</div>

	</div>
</section>
