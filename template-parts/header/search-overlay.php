<?php
/**
 * Search overlay — Skins-style header takeover.
 *
 * When opened the search replaces the header with:
 *   logo (left) · bordered input (centre) · "Annuleren" cancel (right)
 * Results panel fills below. No slide animation — instant show/hide.
 *
 * JS hooks (unchanged from previous version):
 *   [data-search-toggle]   — button in site-header.php that opens search
 *   [data-search-overlay]  — outer wrapper
 *   [data-search-close]    — any element that closes on click
 *   [data-search-input]    — the search input field
 *   [data-search-panel]    — results/suggestions panel
 *   [data-search-state]    — state container (empty|loading|results|no-results)
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$logo_id           = lenvy_field('lenvy_site_logo', 'options');
$popular_cats      = lenvy_get_filter_terms('product_cat');
$trending_products = lenvy_get_homepage_products('bestsellers', 4);
?>
<div
	data-search-overlay
	class="fixed inset-0 z-[45] hidden flex-col bg-white"
	aria-hidden="true"
	role="dialog"
	aria-label="<?php esc_attr_e('Zoeken', 'lenvy'); ?>"
>

	<!-- ── Search header bar ──────────────────────────────────────────────── -->
	<div class="shrink-0 border-b border-neutral-200" data-search-band>
		<div class="lenvy-container">
			<div class="grid grid-cols-[auto_1fr_auto] items-center gap-6 h-[56px] lg:h-[60px]">

				<!-- Logo -->
				<a
					href="<?php echo esc_url(home_url('/')); ?>"
					class="flex items-center"
					aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>"
				>
					<?php if ($logo_id): ?>
						<?php echo lenvy_get_image($logo_id, 'medium', 'block max-h-9 w-auto object-contain');
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					<?php else: ?>
						<span class="font-medium text-2xl tracking-tight text-neutral-900">
							<?php bloginfo('name'); ?>
						</span>
					<?php endif; ?>
				</a>

				<!-- Search input -->
				<form
					role="search"
					method="get"
					action="<?php echo esc_url(home_url('/')); ?>"
					class="flex items-center w-full max-w-2xl mx-auto gap-2 border border-neutral-300 px-3 h-11"
					style="outline:none;box-shadow:none;"
				>
					<span class="shrink-0 text-neutral-400" aria-hidden="true">
						<?php lenvy_icon('search', '', 'sm'); ?>
					</span>

					<input
						data-search-input
						id="lenvy-search"
						type="text"
						name="s"
						autocomplete="off"
						spellcheck="false"
						placeholder="<?php esc_attr_e('Zoeken…', 'lenvy'); ?>"
						class="lenvy-search-input flex-1 min-w-0 bg-transparent text-sm text-neutral-900 placeholder:text-neutral-400"
						value=""
					/>

					<button
						type="button"
						data-search-clear
						class="shrink-0 text-neutral-400 hover:text-neutral-700 hidden"
						aria-label="<?php esc_attr_e('Zoekopdracht wissen', 'lenvy'); ?>"
					>
						<?php lenvy_icon('close', '', 'sm'); ?>
					</button>

					<input type="hidden" name="post_type" value="product" />
				</form>

				<!-- Cancel -->
				<button
					type="button"
					data-search-close
					class="text-sm text-neutral-500 hover:text-black transition-colors whitespace-nowrap"
				>
					<?php esc_html_e('Annuleren', 'lenvy'); ?>
				</button>

			</div>
		</div>
	</div>

	<!-- ── Results panel ──────────────────────────────────────────────────── -->
	<div
		data-search-panel
		class="flex-1 overflow-y-auto bg-white"
	>
		<div class="lenvy-container py-8">

			<!-- Empty state: popular categories + trending -->
			<div data-search-state="empty">
				<div class="grid grid-cols-1 md:grid-cols-[280px_1fr] gap-10">

					<!-- Popular categories -->
					<div>
						<h3 class="text-xs font-semibold uppercase tracking-[0.1em] text-neutral-900 mb-5">
							<?php esc_html_e('Populaire categorieën', 'lenvy'); ?>
						</h3>
						<?php if (!empty($popular_cats)): ?>
						<div class="flex flex-wrap gap-2">
							<?php foreach (array_slice($popular_cats, 0, 8) as $cat): ?>
							<a
								href="<?php echo esc_url(get_term_link($cat)); ?>"
								class="inline-block px-3.5 py-2 text-xs font-medium text-neutral-700 border border-neutral-200 hover:border-neutral-400 transition-colors"
							>
								<?php echo esc_html($cat->name); ?>
							</a>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>
					</div>

					<!-- Trending products -->
					<div>
						<h3 class="text-xs font-semibold uppercase tracking-[0.1em] text-neutral-900 mb-5">
							<?php esc_html_e('Trending', 'lenvy'); ?>
						</h3>
						<?php if (!empty($trending_products)): ?>
						<div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
							<?php foreach ($trending_products as $product):
								get_template_part('template-parts/components/product-card-mini', null, [
									'product_id' => $product->get_id(),
								]);
							endforeach; ?>
						</div>
						<?php endif; ?>
					</div>

				</div>
			</div>

			<!-- Loading state -->
			<div data-search-state="loading" class="hidden">
				<div class="grid grid-cols-1 md:grid-cols-[280px_1fr] gap-10">
					<div>
						<div class="h-3 w-32 bg-neutral-100 mb-5"></div>
						<div class="space-y-3">
							<?php for ($i = 0; $i < 4; $i++): ?>
							<div class="h-3 bg-neutral-100" style="width: <?php echo esc_attr(rand(40, 80)); ?>%"></div>
							<?php endfor; ?>
						</div>
					</div>
					<div>
						<div class="h-3 w-40 bg-neutral-100 mb-5"></div>
						<div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
							<?php for ($i = 0; $i < 4; $i++): ?>
							<div>
								<div class="aspect-product bg-neutral-100 mb-3"></div>
								<div class="h-2.5 w-16 bg-neutral-100 mb-2"></div>
								<div class="h-3 w-24 bg-neutral-100"></div>
							</div>
							<?php endfor; ?>
						</div>
					</div>
				</div>
			</div>

			<!-- Results state -->
			<div data-search-state="results" class="hidden">
				<div class="grid grid-cols-1 md:grid-cols-[280px_1fr] gap-10">

					<!-- Left: Brands + Categories -->
					<div class="space-y-8">
						<div data-search-brands-section class="hidden">
							<h3 class="text-xs font-semibold uppercase tracking-[0.1em] text-neutral-900 mb-4">
								<?php esc_html_e('Merken', 'lenvy'); ?>
							</h3>
							<ul data-search-brands class="space-y-2.5"></ul>
						</div>

						<div data-search-categories-section class="hidden">
							<h3 class="text-xs font-semibold uppercase tracking-[0.1em] text-neutral-900 mb-4">
								<?php esc_html_e('Categorieën', 'lenvy'); ?>
							</h3>
							<ul data-search-categories class="space-y-2.5"></ul>
						</div>
					</div>

					<!-- Right: Products -->
					<div>
						<h3 class="text-xs font-semibold uppercase tracking-[0.1em] text-neutral-900 mb-5">
							<span data-search-product-count></span>
						</h3>
						<div data-search-products class="grid grid-cols-2 sm:grid-cols-4 gap-4"></div>

						<!-- All results link -->
						<div class="mt-8 pt-5 border-t border-neutral-100">
							<a
								data-search-all-results
								href="#"
								class="inline-flex items-center gap-2 text-sm text-neutral-600 hover:text-black transition-colors"
							>
								<span data-search-all-results-text></span>
								<?php lenvy_icon('arrow-right', '', 'sm'); ?>
							</a>
						</div>
					</div>

				</div>
			</div>

			<!-- No results state -->
			<div data-search-state="no-results" class="hidden">
				<div class="py-12 text-center">
					<p class="text-sm text-neutral-500">
						<?php esc_html_e('Geen resultaten gevonden.', 'lenvy'); ?>
					</p>
					<p class="mt-2 text-xs text-neutral-400">
						<?php esc_html_e('Probeer een andere zoekterm of bekijk onze categorieën.', 'lenvy'); ?>
					</p>
				</div>
			</div>

		</div>
	</div>

</div>
