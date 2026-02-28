<?php
/**
 * Single product page — editorial two-column layout with accordion details.
 *
 * Overrides woocommerce/single-product.php
 *
 * @package Lenvy
 * @see     WC templates/single-product.php
 */

defined('ABSPATH') || exit();

get_header();

while (have_posts()):

	the_post();

	$product = wc_get_product(get_the_ID());

	if (!$product) {
		continue;
	}

	$brand_terms   = get_the_terms(get_the_ID(), 'product_brand');
	$brand         = $brand_terms && !is_wp_error($brand_terms) ? $brand_terms[0] : null;
	$concentration = $product->get_attribute('concentration');
	$subtitle      = lenvy_field('lenvy_product_subtitle');
	$badge_text  = lenvy_field('lenvy_product_badge_text');
	$scent_notes = lenvy_field('lenvy_product_scent_notes');
	$usage_tips  = lenvy_field('lenvy_product_usage_tips');
	$long_desc   = $product->get_description();
	?>

	<main id="primary" class="py-12 lg:py-20">
		<div class="lenvy-container">

			<?php get_template_part('template-parts/components/breadcrumb'); ?>

			<!-- Two-column: gallery | details -->
			<div class="mt-6 grid grid-cols-1 lg:grid-cols-[55fr_45fr] gap-10 lg:gap-20">

				<!-- ── Gallery ───────────────────────────────────────── -->
				<div data-product-gallery class="lg:sticky lg:top-[calc(var(--header-height,68px)+2rem)] lg:self-start">
					<?php wc_get_template_part('single-product/product', 'image'); ?>
				</div>

				<!-- ── Details ───────────────────────────────────────── -->
				<div class="flex flex-col">

					<!-- Brand -->
					<?php if ($brand): ?>
					<a
						href="<?php echo esc_url(get_term_link($brand)); ?>"
						class="text-[11px] font-medium uppercase tracking-[0.14em] text-neutral-400 hover:text-neutral-900 transition-colors duration-200 mb-4 self-start"
					>
						<?php echo esc_html($brand->name); ?>
					</a>
					<?php endif; ?>

					<!-- Title -->
					<h1 class="text-3xl lg:text-4xl font-serif italic text-neutral-900 leading-tight tracking-[-0.01em]">
						<?php the_title(); ?>
					</h1>

					<!-- Subtitle -->
					<?php if ($subtitle): ?>
					<p class="text-sm italic text-neutral-500 mt-1">
						<?php echo esc_html($subtitle); ?>
					</p>
					<?php endif; ?>

					<!-- Concentration -->
					<?php if ($concentration): ?>
					<p class="text-xs text-neutral-400 mt-1">
						<?php echo esc_html($concentration); ?>
					</p>
					<?php endif; ?>

					<!-- Price — hidden for variable products (tiles show per-option prices) -->
					<?php if (!$product->is_type('variable')): ?>
					<div class="mt-6 text-2xl font-medium text-neutral-900 lenvy-product-price">
						<?php woocommerce_template_single_price(); ?>
					</div>
					<?php endif; ?>

					<!-- Short description -->
					<?php if ($product->get_short_description()): ?>
					<div class="mt-4 text-sm text-neutral-600 leading-relaxed max-w-prose">
						<?php echo wp_kses_post($product->get_short_description()); ?>
					</div>
					<?php endif; ?>

					<!-- Custom badge -->
					<?php if ($badge_text): ?>
					<div class="mt-4">
						<?php get_template_part('template-parts/components/badge', null, [
							'text'    => (string) $badge_text,
							'variant' => 'new',
						]); ?>
					</div>
					<?php endif; ?>

					<div class="border-t border-neutral-100 mt-6"></div>

					<!-- Add to cart form -->
					<div class="mt-6 lenvy-atc-form">
						<?php do_action('woocommerce_before_add_to_cart_form'); ?>
						<?php woocommerce_template_single_add_to_cart(); ?>
						<?php do_action('woocommerce_after_add_to_cart_form'); ?>
					</div>

					<?php get_template_part('template-parts/components/trust-block'); ?>

					<!-- ── Accordion details ─────────────────────────── -->
					<div class="mt-8">

						<?php
						// ── Scent notes ──────────────────────────────────────────
						$has_notes = $scent_notes && (
							!empty($scent_notes['top_notes']) ||
							!empty($scent_notes['heart_notes']) ||
							!empty($scent_notes['base_notes'])
						);

						if ($has_notes): ?>
						<div class="border-t border-neutral-100" data-filter-accordion>
							<button
								id="toggle-scent-notes"
								type="button"
								class="flex items-center justify-between w-full py-5 text-left"
								data-filter-accordion-toggle
								aria-expanded="false"
								aria-controls="panel-scent-notes"
							>
								<span class="text-[13px] font-medium text-neutral-700"><?php esc_html_e('Geurprofiel', 'lenvy'); ?></span>
								<?php lenvy_icon('chevron-down', 'text-neutral-400 transition-transform duration-200', 'xs'); ?>
							</button>
							<div id="panel-scent-notes" role="region" aria-labelledby="toggle-scent-notes" data-filter-accordion-panel style="display:none;">
								<div class="pb-5 grid grid-cols-3 gap-4">
									<?php
									$note_groups = [
										__('Top', 'lenvy')   => $scent_notes['top_notes'] ?? '',
										__('Hart', 'lenvy')  => $scent_notes['heart_notes'] ?? '',
										__('Basis', 'lenvy') => $scent_notes['base_notes'] ?? '',
									];
									foreach ($note_groups as $group_label => $notes):
										if (empty($notes)) {
											continue;
										} ?>
									<div>
										<p class="text-[10px] uppercase tracking-widest text-neutral-400 mb-1">
											<?php echo esc_html($group_label); ?>
										</p>
										<p class="text-sm text-neutral-600 leading-snug">
											<?php echo esc_html($notes); ?>
										</p>
									</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
						<?php endif; ?>

						<?php // ── Usage tips ─────────────────────────────────────────
						if ($usage_tips): ?>
						<div class="border-t border-neutral-100" data-filter-accordion>
							<button
								id="toggle-usage-tips"
								type="button"
								class="flex items-center justify-between w-full py-5 text-left"
								data-filter-accordion-toggle
								aria-expanded="false"
								aria-controls="panel-usage-tips"
							>
								<span class="text-[13px] font-medium text-neutral-700"><?php esc_html_e('Gebruikstips', 'lenvy'); ?></span>
								<?php lenvy_icon('chevron-down', 'text-neutral-400 transition-transform duration-200', 'xs'); ?>
							</button>
							<div id="panel-usage-tips" role="region" aria-labelledby="toggle-usage-tips" data-filter-accordion-panel style="display:none;">
								<div class="pb-5">
									<p class="text-sm text-neutral-600 leading-relaxed">
										<?php echo esc_html($usage_tips); ?>
									</p>
								</div>
							</div>
						</div>
						<?php endif; ?>

						<?php // ── Description ────────────────────────────────────────
						if ($long_desc): ?>
						<div class="border-t border-neutral-100" data-filter-accordion>
							<button
								id="toggle-description"
								type="button"
								class="flex items-center justify-between w-full py-5 text-left"
								data-filter-accordion-toggle
								aria-expanded="false"
								aria-controls="panel-description"
							>
								<span class="text-[13px] font-medium text-neutral-700"><?php esc_html_e('Beschrijving', 'lenvy'); ?></span>
								<?php lenvy_icon('chevron-down', 'text-neutral-400 transition-transform duration-200', 'xs'); ?>
							</button>
							<div id="panel-description" role="region" aria-labelledby="toggle-description" data-filter-accordion-panel style="display:none;">
								<div class="pb-5 text-sm text-neutral-600 leading-relaxed max-w-prose">
									<?php echo wp_kses_post($long_desc); ?>
								</div>
							</div>
						</div>
						<?php endif; ?>

						<?php // ── Product details (SKU, categories, tags) ────────────
						$sku = $product->get_sku();
						$categories = get_the_terms(get_the_ID(), 'product_cat');
						$tags = get_the_terms(get_the_ID(), 'product_tag');
						$has_meta = $sku || ($categories && !is_wp_error($categories)) || ($tags && !is_wp_error($tags));

						if ($has_meta): ?>
						<div class="border-t border-neutral-100" data-filter-accordion>
							<button
								id="toggle-product-details"
								type="button"
								class="flex items-center justify-between w-full py-5 text-left"
								data-filter-accordion-toggle
								aria-expanded="false"
								aria-controls="panel-product-details"
							>
								<span class="text-[13px] font-medium text-neutral-700"><?php esc_html_e('Productdetails', 'lenvy'); ?></span>
								<?php lenvy_icon('chevron-down', 'text-neutral-400 transition-transform duration-200', 'xs'); ?>
							</button>
							<div id="panel-product-details" role="region" aria-labelledby="toggle-product-details" data-filter-accordion-panel style="display:none;">
								<div class="pb-5 text-sm text-neutral-600 space-y-1.5">
									<?php if ($sku): ?>
									<p>
										<span class="text-neutral-400"><?php esc_html_e('SKU:', 'lenvy'); ?></span>
										<?php echo esc_html($sku); ?>
									</p>
									<?php endif; ?>

									<?php if ($categories && !is_wp_error($categories)): ?>
									<p>
										<span class="text-neutral-400"><?php esc_html_e('Categorie:', 'lenvy'); ?></span>
										<?php
										$cat_links = [];
										foreach ($categories as $cat) {
											$cat_links[] = '<a href="' . esc_url(get_term_link($cat)) . '" class="text-neutral-600 underline underline-offset-2 hover:text-neutral-900 transition-colors">' . esc_html($cat->name) . '</a>';
										}
										// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — escaped in loop above
										echo implode(', ', $cat_links);
										?>
									</p>
									<?php endif; ?>

									<?php if ($tags && !is_wp_error($tags)): ?>
									<p>
										<span class="text-neutral-400"><?php esc_html_e('Tags:', 'lenvy'); ?></span>
										<?php
										$tag_links = [];
										foreach ($tags as $tag) {
											$tag_links[] = '<a href="' . esc_url(get_term_link($tag)) . '" class="text-neutral-600 underline underline-offset-2 hover:text-neutral-900 transition-colors">' . esc_html($tag->name) . '</a>';
										}
										// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — escaped in loop above
										echo implode(', ', $tag_links);
										?>
									</p>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<?php endif; ?>

						<div class="border-t border-neutral-100"></div>

					</div><!-- accordion wrapper -->

				</div><!-- details column -->

			</div><!-- two-column grid -->

			</div>

		<!-- Related products — breaks out to full-width -->
		<div class="lenvy-section">
			<?php woocommerce_output_related_products(); ?>
		</div>

		<?php
		// ── Product JSON-LD structured data ───────────────────────────────────
		$schema = [
			'@context'    => 'https://schema.org',
			'@type'       => 'Product',
			'name'        => $product->get_name(),
			'url'         => get_permalink(),
			'description' => wp_strip_all_tags($product->get_short_description() ?: $product->get_description()),
		];

		$schema_image_id = $product->get_image_id();
		if ($schema_image_id) {
			$schema['image'] = wp_get_attachment_url($schema_image_id);
		}

		$schema_sku = $product->get_sku();
		if ($schema_sku) {
			$schema['sku'] = $schema_sku;
		}

		if ($brand) {
			$schema['brand'] = [
				'@type' => 'Brand',
				'name'  => $brand->name,
			];
		}

		$schema['offers'] = [
			'@type'           => 'Offer',
			'url'             => get_permalink(),
			'priceCurrency'   => get_woocommerce_currency(),
			'price'           => $product->get_price(),
			'availability'    => $product->is_in_stock()
				? 'https://schema.org/InStock'
				: 'https://schema.org/OutOfStock',
			'itemCondition'   => 'https://schema.org/NewCondition',
		];

		if ($product->is_type('variable')) {
			$prices = $product->get_variation_prices(true);
			if (!empty($prices['price'])) {
				$schema['offers']['lowPrice']  = min($prices['price']);
				$schema['offers']['highPrice'] = max($prices['price']);
				$schema['offers']['@type']     = 'AggregateOffer';
				$schema['offers']['offerCount'] = count($prices['price']);
				unset($schema['offers']['price']);
			}
		}
		?>
		<script type="application/ld+json"><?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>

	</main>

<?php
endwhile;
?>

<?php get_footer(); ?>
