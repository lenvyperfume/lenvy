<?php
/**
 * Single product page — custom two-column layout.
 *
 * Overrides woocommerce/single-product.php
 *
 * @package Lenvy
 * @see     WC templates/single-product.php
 */

defined('ABSPATH') || exit();

get_header();

while ( have_posts() ) :
	the_post();

	$product     = wc_get_product( get_the_ID() );

	if ( ! $product ) {
		continue;
	}

	$brand_terms  = get_the_terms( get_the_ID(), 'product_brand' );
	$brand        = ( $brand_terms && ! is_wp_error( $brand_terms ) ) ? $brand_terms[0] : null;
	$badge_text   = lenvy_field( 'lenvy_product_badge_text' );
	$scent_notes  = lenvy_field( 'lenvy_product_scent_notes' );
	$usage_tips   = lenvy_field( 'lenvy_product_usage_tips' );
	?>

	<main id="primary" class="py-10 lg:py-16">
		<div class="lenvy-container">

			<?php get_template_part( 'template-parts/components/breadcrumb' ); ?>

			<!-- Two-column layout: gallery | details -->
			<div class="mt-8 grid grid-cols-1 lg:grid-cols-[55fr_45fr] gap-10 xl:gap-20">

				<!-- ── Gallery ───────────────────────────────────────────── -->
				<div data-product-gallery>
					<?php wc_get_template_part( 'single-product/product', 'image' ); ?>
				</div>

				<!-- ── Product details ───────────────────────────────────── -->
				<div class="flex flex-col">

					<!-- Brand -->
					<?php if ( $brand ) : ?>
					<a
						href="<?php echo esc_url( get_term_link( $brand ) ); ?>"
						class="text-xs font-medium uppercase tracking-widest text-neutral-400 hover:text-black transition-colors duration-150 mb-2 self-start"
					>
						<?php echo esc_html( $brand->name ); ?>
					</a>
					<?php endif; ?>

					<!-- Title -->
					<h1 class="text-2xl md:text-3xl font-serif italic text-neutral-900 leading-tight">
						<?php the_title(); ?>
					</h1>

					<!-- Rating -->
					<?php woocommerce_template_single_rating(); ?>

					<!-- Price -->
					<div class="mt-4 text-xl font-semibold text-neutral-900 lenvy-product-price">
						<?php woocommerce_template_single_price(); ?>
					</div>

					<!-- Short description -->
					<?php if ( $product->get_short_description() ) : ?>
					<div class="mt-4 text-sm text-neutral-600 leading-relaxed prose prose-sm max-w-none">
						<?php echo wp_kses_post( $product->get_short_description() ); ?>
					</div>
					<?php endif; ?>

					<!-- Add to cart form (handles both simple + variable) -->
					<div class="mt-6 lenvy-atc-form">
						<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
						<?php woocommerce_template_single_add_to_cart(); ?>
						<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
					</div>

					<!-- Custom badge -->
					<?php if ( $badge_text ) : ?>
					<div class="mt-4">
						<?php
						get_template_part( 'template-parts/components/badge', null, [
							'text'    => (string) $badge_text,
							'variant' => 'new',
						] );
						?>
					</div>
					<?php endif; ?>

					<!-- Scent notes -->
					<?php
					$has_notes = $scent_notes && (
						! empty( $scent_notes['top_notes'] ) ||
						! empty( $scent_notes['heart_notes'] ) ||
						! empty( $scent_notes['base_notes'] )
					);
					?>
					<?php if ( $has_notes ) : ?>
					<div class="mt-8 pt-6 border-t border-neutral-100">
						<h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-400 mb-4">
							<?php esc_html_e( 'Scent Notes', 'lenvy' ); ?>
						</h3>
						<div class="grid grid-cols-3 gap-4">
							<?php
							$note_groups = [
								__( 'Top', 'lenvy' )   => $scent_notes['top_notes']   ?? '',
								__( 'Heart', 'lenvy' ) => $scent_notes['heart_notes'] ?? '',
								__( 'Base', 'lenvy' )  => $scent_notes['base_notes']  ?? '',
							];
							foreach ( $note_groups as $group_label => $notes ) :
								if ( empty( $notes ) ) {
									continue;
								}
								?>
								<div>
									<p class="text-[10px] uppercase tracking-widest text-neutral-400 mb-1">
										<?php echo esc_html( $group_label ); ?>
									</p>
									<p class="text-xs text-neutral-700 leading-snug">
										<?php echo esc_html( $notes ); ?>
									</p>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<!-- Usage tips -->
					<?php if ( $usage_tips ) : ?>
					<div class="mt-6 pt-6 border-t border-neutral-100">
						<p class="text-xs text-neutral-500 leading-relaxed italic">
							<?php echo esc_html( $usage_tips ); ?>
						</p>
					</div>
					<?php endif; ?>

					<!-- Meta: SKU, categories, tags -->
					<div class="mt-6 pt-6 border-t border-neutral-100 lenvy-product-meta">
						<?php woocommerce_template_single_meta(); ?>
					</div>

				</div><!-- .details -->

			</div><!-- .grid -->

			<!-- Product tabs (description, attributes, reviews) -->
			<div class="mt-16 pt-10 border-t border-neutral-100 lenvy-product-tabs">
				<?php woocommerce_output_product_data_tabs(); ?>
			</div>

			<!-- Related products -->
			<?php woocommerce_output_related_products(); ?>

		</div><!-- .lenvy-container -->
	</main>

<?php endwhile; ?>

<?php get_footer(); ?>
