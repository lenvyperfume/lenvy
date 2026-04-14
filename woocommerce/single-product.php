<?php
/**
 * Single product page — sticky image left, scrollable details right.
 *
 * The product image stays fixed in the viewport while the user scrolls
 * through purchase info, accordions, and product details on the right.
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
	$badge_text    = lenvy_field('lenvy_product_badge_text');
	$scent_notes   = lenvy_field('lenvy_product_scent_notes');
	$usage_tips    = lenvy_field('lenvy_product_usage_tips');
	$long_desc     = $product->get_description();

	$main_image_id = (int) $product->get_image_id();
	$gallery_ids   = $product->get_gallery_image_ids();
	$all_image_ids = $main_image_id
		? array_merge([$main_image_id], array_map('intval', $gallery_ids))
		: array_map('intval', $gallery_ids);
	$has_multiple  = count($all_image_ids) > 1;
	?>

	<main id="primary">

		<!-- ═══ Two-column: sticky image | scrollable details ═════════════ -->
		<div class="lg:flex">

			<!-- ── Left: Sticky image ────────────────────────────── -->
			<div class="relative lg:w-[55%] lg:shrink-0" style="background:#FAF9F8;" data-product-hero-gallery>

				<div class="lg:sticky lg:top-[var(--header-height,72px)] lg:h-[calc(100svh-var(--header-height,72px))] relative overflow-hidden">

					<!-- Breadcrumb overlaid -->
					<div class="absolute top-4 left-4 lg:top-6 lg:left-6 z-10">
						<?php get_template_part('template-parts/components/breadcrumb'); ?>
					</div>

					<!-- Product images — horizontal scroll -->
					<div class="flex w-full h-full overflow-x-auto scrollbar-hide" style="scroll-behavior:smooth;" data-hero-image-wrap>
						<?php if (!empty($all_image_ids)): ?>
							<?php foreach ($all_image_ids as $i => $img_id): ?>
								<div class="flex-[0_0_100%] min-w-0 flex items-center justify-center p-12 lg:p-20" data-hero-slide="<?php echo (int) $i; ?>">
									<?php echo wp_get_attachment_image($img_id, 'woocommerce_single', false, [
										'class'         => 'max-h-full max-w-full object-contain',
										'fetchpriority' => 0 === $i ? 'high' : 'auto',
										'loading'       => 0 === $i ? 'eager' : 'lazy',
										'alt'           => esc_attr($product->get_name()),
									]); ?>
								</div>
							<?php endforeach; ?>
						<?php else: ?>
							<div class="flex-[0_0_100%] min-w-0 flex items-center justify-center p-12 lg:p-20">
								<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo wc_placeholder_img('woocommerce_single', ['class' => 'max-h-full max-w-full object-contain']); ?>
							</div>
						<?php endif; ?>
					</div>

					<?php if ($has_multiple): ?>
					<!-- Prev / Next arrows -->
					<button type="button" data-hero-prev class="absolute left-4 lg:left-6 top-1/2 -translate-y-1/2 z-10 w-10 h-10 flex items-center justify-center text-neutral-400 hover:text-neutral-900 transition-colors" aria-label="<?php esc_attr_e('Vorige afbeelding', 'lenvy'); ?>">
						<?php lenvy_icon('chevron-left', '', 'md'); ?>
					</button>
					<button type="button" data-hero-next class="absolute right-4 lg:right-6 top-1/2 -translate-y-1/2 z-10 w-10 h-10 flex items-center justify-center text-neutral-400 hover:text-neutral-900 transition-colors" aria-label="<?php esc_attr_e('Volgende afbeelding', 'lenvy'); ?>">
						<?php lenvy_icon('chevron-right', '', 'md'); ?>
					</button>

					<!-- Image counter -->
					<div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-10 text-xs text-neutral-400">
						<span data-hero-current>1</span> / <?php echo count($all_image_ids); ?>
					</div>
					<?php endif; ?>

					<?php if ($product->is_on_sale()): ?>
					<span class="absolute top-4 right-4 lg:top-6 lg:right-6 z-10 pointer-events-none">
						<?php get_template_part('template-parts/components/badge', null, [
							'text'    => __('Sale', 'lenvy'),
							'variant' => 'sale',
						]); ?>
					</span>
					<?php endif; ?>

				</div>

			</div>

			<!-- ── Right: Scrollable details ─────────────────────── -->
			<div class="lg:w-[45%] px-6 py-10 sm:px-10 lg:px-14 xl:px-20 lg:py-16">

				<!-- Brand -->
				<?php if ($brand): ?>
				<a
					href="<?php echo esc_url(get_term_link($brand)); ?>"
					class="text-xs font-semibold uppercase tracking-[0.1em] text-neutral-900 hover:text-neutral-500 transition-colors duration-200 mb-3 inline-block"
				>
					<?php echo esc_html($brand->name); ?>
				</a>
				<?php endif; ?>

				<!-- Title -->
				<h1 class="text-2xl sm:text-3xl lg:text-4xl font-medium text-neutral-900 leading-tight">
					<?php the_title(); ?>
				</h1>

				<!-- Subtitle + Concentration -->
				<?php if ($subtitle || $concentration): ?>
				<p class="text-sm text-neutral-400 mt-2">
					<?php if ($subtitle): echo esc_html($subtitle); endif; ?>
					<?php if ($subtitle && $concentration): ?> — <?php endif; ?>
					<?php if ($concentration): echo esc_html($concentration); endif; ?>
				</p>
				<?php endif; ?>

				<!-- Price -->
				<?php if (!$product->is_type('variable')): ?>
				<div class="mt-5 text-xl font-semibold text-neutral-900 lenvy-product-price">
					<?php woocommerce_template_single_price(); ?>
				</div>
				<?php endif; ?>

				<!-- Short description -->
				<?php if ($product->get_short_description()): ?>
				<div class="mt-4 text-sm text-neutral-500 leading-relaxed max-w-prose">
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

				<!-- Add to cart form -->
				<div class="mt-6 lenvy-atc-form">
					<?php do_action('woocommerce_before_add_to_cart_form'); ?>
					<?php woocommerce_template_single_add_to_cart(); ?>
					<?php do_action('woocommerce_after_add_to_cart_form'); ?>
				</div>

				<?php get_template_part('template-parts/components/trust-block'); ?>

				<!-- ── Accordion details ──────────────────────────── -->
				<div class="mt-10">

					<?php
					$has_notes = $scent_notes && (
						!empty($scent_notes['top_notes']) ||
						!empty($scent_notes['heart_notes']) ||
						!empty($scent_notes['base_notes'])
					);

					if ($has_notes): ?>
					<div class="border-t border-neutral-100" data-filter-accordion>
						<button id="toggle-scent-notes" type="button" class="flex items-center justify-between w-full py-5 text-left" data-filter-accordion-toggle aria-expanded="true" aria-controls="panel-scent-notes">
							<span class="text-sm font-medium text-neutral-900"><?php esc_html_e('Geurprofiel', 'lenvy'); ?></span>
							<?php lenvy_icon('chevron-down', 'text-neutral-400 transition-transform duration-200 rotate-180', 'xs'); ?>
						</button>
						<div id="panel-scent-notes" role="region" aria-labelledby="toggle-scent-notes" data-filter-accordion-panel>
							<div class="pb-6 grid grid-cols-3 gap-6">
								<?php
								$note_groups = [
									__('Top', 'lenvy')   => $scent_notes['top_notes'] ?? '',
									__('Hart', 'lenvy')  => $scent_notes['heart_notes'] ?? '',
									__('Basis', 'lenvy') => $scent_notes['base_notes'] ?? '',
								];
								foreach ($note_groups as $group_label => $notes):
									if (empty($notes)) continue;
								?>
								<div>
									<p class="text-[10px] uppercase tracking-widest text-neutral-400 mb-1.5"><?php echo esc_html($group_label); ?></p>
									<p class="text-sm text-neutral-600 leading-relaxed"><?php echo esc_html($notes); ?></p>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<?php endif; ?>

					<?php if ($usage_tips): ?>
					<div class="border-t border-neutral-100" data-filter-accordion>
						<button id="toggle-usage-tips" type="button" class="flex items-center justify-between w-full py-5 text-left" data-filter-accordion-toggle aria-expanded="false" aria-controls="panel-usage-tips">
							<span class="text-sm font-medium text-neutral-900"><?php esc_html_e('Gebruikstips', 'lenvy'); ?></span>
							<?php lenvy_icon('chevron-down', 'text-neutral-400 transition-transform duration-200', 'xs'); ?>
						</button>
						<div id="panel-usage-tips" role="region" aria-labelledby="toggle-usage-tips" data-filter-accordion-panel>
							<div class="pb-6 text-sm text-neutral-500 leading-relaxed"><?php echo wp_kses_post($usage_tips); ?></div>
						</div>
					</div>
					<?php endif; ?>

					<?php if ($long_desc): ?>
					<div class="border-t border-neutral-100" data-filter-accordion>
						<button id="toggle-description" type="button" class="flex items-center justify-between w-full py-5 text-left" data-filter-accordion-toggle aria-expanded="false" aria-controls="panel-description">
							<span class="text-sm font-medium text-neutral-900"><?php esc_html_e('Beschrijving', 'lenvy'); ?></span>
							<?php lenvy_icon('chevron-down', 'text-neutral-400 transition-transform duration-200', 'xs'); ?>
						</button>
						<div id="panel-description" role="region" aria-labelledby="toggle-description" data-filter-accordion-panel>
							<div class="pb-6 text-sm text-neutral-500 leading-relaxed max-w-prose"><?php echo wp_kses_post($long_desc); ?></div>
						</div>
					</div>
					<?php endif; ?>

					<?php
					$sku = $product->get_sku();
					$categories = get_the_terms(get_the_ID(), 'product_cat');
					$tags = get_the_terms(get_the_ID(), 'product_tag');
					$has_meta = $sku || ($categories && !is_wp_error($categories)) || ($tags && !is_wp_error($tags));

					if ($has_meta): ?>
					<div class="border-t border-neutral-100" data-filter-accordion>
						<button id="toggle-product-details" type="button" class="flex items-center justify-between w-full py-5 text-left" data-filter-accordion-toggle aria-expanded="false" aria-controls="panel-product-details">
							<span class="text-sm font-medium text-neutral-900"><?php esc_html_e('Productdetails', 'lenvy'); ?></span>
							<?php lenvy_icon('chevron-down', 'text-neutral-400 transition-transform duration-200', 'xs'); ?>
						</button>
						<div id="panel-product-details" role="region" aria-labelledby="toggle-product-details" data-filter-accordion-panel>
							<div class="pb-6 text-sm text-neutral-500 space-y-2">
								<?php if ($sku): ?>
								<p><span class="text-neutral-400"><?php esc_html_e('SKU:', 'lenvy'); ?></span> <?php echo esc_html($sku); ?></p>
								<?php endif; ?>
								<?php if ($categories && !is_wp_error($categories)): ?>
								<p>
									<span class="text-neutral-400"><?php esc_html_e('Categorie:', 'lenvy'); ?></span>
									<?php
									$cat_links = [];
									foreach ($categories as $cat) {
										$cat_links[] = '<a href="' . esc_url(get_term_link($cat)) . '" class="text-neutral-600 underline underline-offset-2 hover:text-neutral-900 transition-colors">' . esc_html($cat->name) . '</a>';
									}
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									echo implode(', ', $tag_links);
									?>
								</p>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php endif; ?>

					<div class="border-t border-neutral-100"></div>

				</div>

				<?php
				// ── Auto-generated SEO text ─────────────────────────────────
				// Combines product data into a natural-language paragraph.
				// Always visible (not in an accordion) so Google indexes it.
				$seo_product_name = $product->get_name();
				$seo_brand_name   = $brand ? $brand->name : '';
				$seo_cats         = get_the_terms(get_the_ID(), 'product_cat');
				$seo_cat_name     = ($seo_cats && !is_wp_error($seo_cats)) ? $seo_cats[0]->name : '';

				$seo_parts = [];

				if ($seo_brand_name && $concentration) {
					$seo_parts[] = sprintf(
						/* translators: 1: product name, 2: brand name, 3: concentration */
						__('%1$s van %2$s is een %3$s', 'lenvy'),
						$seo_product_name,
						$seo_brand_name,
						$concentration,
					);
				} elseif ($seo_brand_name) {
					$seo_parts[] = sprintf(
						/* translators: 1: product name, 2: brand name */
						__('%1$s van %2$s', 'lenvy'),
						$seo_product_name,
						$seo_brand_name,
					);
				} else {
					$seo_parts[] = $seo_product_name;
				}

				if ($seo_cat_name) {
					$seo_parts[] = sprintf(
						/* translators: %s: category name */
						__('uit onze collectie %s', 'lenvy'),
						$seo_cat_name,
					);
				}

				$seo_parts[] = __('Bestel vandaag bij Lenvy en ontvang gratis samples bij je bestelling. Gratis verzending vanaf €50.', 'lenvy');

				$has_notes_seo = $scent_notes && (!empty($scent_notes['top_notes']) || !empty($scent_notes['heart_notes']) || !empty($scent_notes['base_notes']));
				if ($has_notes_seo) {
					$note_list = array_filter([
						$scent_notes['top_notes'] ?? '',
						$scent_notes['heart_notes'] ?? '',
						$scent_notes['base_notes'] ?? '',
					]);
					if ($note_list) {
						$seo_parts[] = sprintf(
							/* translators: %s: comma-separated scent notes */
							__('Deze geur bevat noten van %s.', 'lenvy'),
							implode(', ', $note_list),
						);
					}
				}

				$seo_text = implode(' ', $seo_parts);
				?>
				<div class="mt-10 pt-6 border-t border-neutral-100">
					<h2 class="text-sm font-medium text-neutral-900 mb-2">
						<?php echo esc_html(sprintf(
							/* translators: %s: product name */
							__('Over %s', 'lenvy'),
							$seo_product_name,
						)); ?>
					</h2>
					<p class="text-xs leading-relaxed text-neutral-400">
						<?php echo esc_html($seo_text); ?>
					</p>
				</div>

			</div>

		</div>

		<!-- Related products — full-width -->
		<div class="lenvy-section">
			<?php woocommerce_output_related_products(); ?>
		</div>

		<?php
		$schema = [
			'@context'    => 'https://schema.org',
			'@type'       => 'Product',
			'name'        => $product->get_name(),
			'url'         => get_permalink(),
			'description' => wp_strip_all_tags($product->get_short_description() ?: $product->get_description()),
		];
		$schema_image_id = $product->get_image_id();
		if ($schema_image_id) { $schema['image'] = wp_get_attachment_url($schema_image_id); }
		$schema_sku = $product->get_sku();
		if ($schema_sku) { $schema['sku'] = $schema_sku; }
		if ($brand) { $schema['brand'] = ['@type' => 'Brand', 'name' => $brand->name]; }
		$schema['offers'] = [
			'@type'         => 'Offer',
			'url'           => get_permalink(),
			'priceCurrency' => get_woocommerce_currency(),
			'price'         => $product->get_price(),
			'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
			'itemCondition' => 'https://schema.org/NewCondition',
		];
		if ($product->is_type('variable')) {
			$prices = $product->get_variation_prices(true);
			if (!empty($prices['price'])) {
				$schema['offers']['lowPrice']   = min($prices['price']);
				$schema['offers']['highPrice']  = max($prices['price']);
				$schema['offers']['@type']      = 'AggregateOffer';
				$schema['offers']['offerCount'] = count($prices['price']);
				unset($schema['offers']['price']);
			}
		}
		?>
		<script type="application/ld+json"><?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>

	</main>

	<?php if ($has_multiple): ?>
	<script>
	(function() {
		const gallery = document.querySelector('[data-product-hero-gallery]');
		if (!gallery) return;
		const wrap = gallery.querySelector('[data-hero-image-wrap]');
		const slides = gallery.querySelectorAll('[data-hero-slide]');
		const prev = gallery.querySelector('[data-hero-prev]');
		const next = gallery.querySelector('[data-hero-next]');
		const counter = gallery.querySelector('[data-hero-current]');
		if (!wrap || slides.length < 2) return;
		let current = 0;
		function goTo(idx) {
			current = (idx + slides.length) % slides.length;
			wrap.scrollTo({ left: current * wrap.offsetWidth, behavior: 'smooth' });
			if (counter) counter.textContent = current + 1;
		}
		prev?.addEventListener('click', function() { goTo(current - 1); });
		next?.addEventListener('click', function() { goTo(current + 1); });
		let scrollTimer;
		wrap.addEventListener('scroll', function() {
			clearTimeout(scrollTimer);
			scrollTimer = setTimeout(function() {
				const idx = Math.round(wrap.scrollLeft / wrap.offsetWidth);
				if (idx !== current && idx >= 0 && idx < slides.length) {
					current = idx;
					if (counter) counter.textContent = current + 1;
				}
			}, 100);
		});
	})();
	</script>
	<?php endif; ?>

<?php
endwhile;
get_footer();
?>
