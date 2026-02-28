<?php
/**
 * Inline header search — slides down from the header bar.
 *
 * Pattern: Douglas / Deloox — header-height search band + results panel.
 * Empty state shows popular categories + trending products (server-rendered).
 * Typing 2+ characters triggers live AJAX results.
 *
 * JS hooks:
 *   [data-search-toggle]   — button that opens the search (in site-header.php)
 *   [data-search-band]     — the white band that slides down
 *   [data-search-close]    — any element that closes on click (close btn + backdrop)
 *   [data-search-overlay]  — outer wrapper used by search.js / main.js ESC handler
 *   [data-search-input]    — the search input field
 *   [data-search-panel]    — results/suggestions panel below the input
 *   [data-search-state]    — state container (empty|loading|results|no-results)
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// ── Server-rendered data for empty state ──────────────────────────────────────
$popular_cats     = lenvy_get_filter_terms('product_cat');
$trending_products = lenvy_get_homepage_products('bestsellers', 4);
?>
<div
	data-search-overlay
	class="fixed inset-0 z-[45] flex flex-col opacity-0 pointer-events-none transition-opacity duration-200"
	aria-hidden="true"
	role="dialog"
	aria-label="<?php esc_attr_e('Search', 'lenvy'); ?>"
>

	<!-- ── Search band ─────────────────────────────────────────────────────── -->
	<div
		data-search-band
		class="shrink-0 bg-white border-b border-neutral-100 shadow-sm -translate-y-full transition-transform duration-[250ms] ease-out"
	>
		<!-- Input row -->
		<div class="lenvy-container">
			<div class="flex items-center gap-3 h-[68px]">

				<!-- Close / back -->
				<button
					type="button"
					data-search-close
					class="shrink-0 flex items-center gap-1.5 text-xs font-medium text-neutral-500 hover:text-black transition-colors duration-200 pr-3 border-r border-neutral-200"
					aria-label="<?php esc_attr_e('Close search', 'lenvy'); ?>"
				>
					<?php lenvy_icon('close', '', 'sm'); ?>
					<span class="hidden sm:inline"><?php esc_html_e('Sluiten', 'lenvy'); ?></span>
				</button>

				<!-- Search form -->
				<form
					role="search"
					method="get"
					action="<?php echo esc_url(home_url('/')); ?>"
					class="flex-1 flex items-center gap-3 min-w-0"
				>
					<span class="shrink-0 text-neutral-400" aria-hidden="true">
						<?php lenvy_icon('search', '', 'md'); ?>
					</span>

					<input
						data-search-input
						id="lenvy-search"
						type="search"
						name="s"
						autocomplete="off"
						spellcheck="false"
						placeholder="<?php esc_attr_e('Zoek naar parfums, merken…', 'lenvy'); ?>"
						class="flex-1 min-w-0 bg-transparent text-[15px] text-neutral-900 placeholder:text-neutral-300 outline-none appearance-none [&::-webkit-search-cancel-button]:appearance-none [&::-webkit-search-decoration]:appearance-none"
						value=""
					/>

					<input type="hidden" name="post_type" value="product" />
				</form>

			</div>
		</div>

		<!-- ── Results panel ────────────────────────────────────────────────── -->
		<div
			data-search-panel
			class="border-t border-neutral-100 bg-white overflow-y-auto scrollbar-hide"
			style="max-height: calc(100vh - 68px - 80px);"
		>
			<div class="lenvy-container py-6">

				<!-- Empty state: popular categories + trending -->
				<div data-search-state="empty">
					<div class="grid grid-cols-1 md:grid-cols-2 gap-8">

						<!-- Popular categories -->
						<div>
							<h3 class="text-xs font-semibold uppercase tracking-[0.08em] text-neutral-400 mb-4">
								<?php esc_html_e('Populaire categorieën', 'lenvy'); ?>
							</h3>
							<?php if (!empty($popular_cats)): ?>
							<div class="flex flex-wrap gap-2">
								<?php foreach (array_slice($popular_cats, 0, 8) as $cat): ?>
								<a
									href="<?php echo esc_url(get_term_link($cat)); ?>"
									class="inline-block px-3 py-1.5 text-sm text-neutral-700 bg-neutral-50 hover:bg-neutral-100 transition-colors duration-200"
								>
									<?php echo esc_html($cat->name); ?>
								</a>
								<?php endforeach; ?>
							</div>
							<?php endif; ?>
						</div>

						<!-- Trending products -->
						<div>
							<h3 class="text-xs font-semibold uppercase tracking-[0.08em] text-neutral-400 mb-4">
								<?php esc_html_e('Trending', 'lenvy'); ?>
							</h3>
							<?php if (!empty($trending_products)): ?>
							<div class="space-y-4">
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

				<!-- Loading state: skeleton -->
				<div data-search-state="loading" class="hidden">
					<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
						<div>
							<div class="h-3 w-24 bg-neutral-100 rounded animate-pulse mb-4"></div>
							<div class="space-y-4">
								<?php for ($i = 0; $i < 3; $i++): ?>
								<div class="flex items-center gap-4">
									<div class="shrink-0 w-16 aspect-product bg-neutral-100 rounded animate-pulse"></div>
									<div class="flex-1 space-y-2">
										<div class="h-2.5 w-16 bg-neutral-100 rounded animate-pulse"></div>
										<div class="h-3 w-32 bg-neutral-100 rounded animate-pulse"></div>
										<div class="h-3 w-20 bg-neutral-100 rounded animate-pulse"></div>
									</div>
								</div>
								<?php endfor; ?>
							</div>
						</div>
						<div>
							<div class="h-3 w-20 bg-neutral-100 rounded animate-pulse mb-4"></div>
							<div class="space-y-3">
								<?php for ($i = 0; $i < 3; $i++): ?>
								<div class="h-3 w-40 bg-neutral-100 rounded animate-pulse"></div>
								<?php endfor; ?>
							</div>
						</div>
					</div>
				</div>

				<!-- Results state -->
				<div data-search-state="results" class="hidden">
					<div class="grid grid-cols-1 md:grid-cols-2 gap-8">

						<!-- Products -->
						<div>
							<h3 class="text-xs font-semibold uppercase tracking-[0.08em] text-neutral-400 mb-4">
								<?php esc_html_e('Producten', 'lenvy'); ?>
							</h3>
							<div data-search-products class="space-y-4"></div>
						</div>

						<!-- Brands + Categories -->
						<div class="space-y-6">
							<div data-search-brands-section class="hidden">
								<h3 class="text-xs font-semibold uppercase tracking-[0.08em] text-neutral-400 mb-3">
									<?php esc_html_e('Merken', 'lenvy'); ?>
								</h3>
								<ul data-search-brands class="space-y-2"></ul>
							</div>

							<div data-search-categories-section class="hidden">
								<h3 class="text-xs font-semibold uppercase tracking-[0.08em] text-neutral-400 mb-3">
									<?php esc_html_e('Categorieën', 'lenvy'); ?>
								</h3>
								<ul data-search-categories class="space-y-2"></ul>
							</div>
						</div>

					</div>

					<!-- All results link -->
					<div class="mt-6 pt-4 border-t border-neutral-100">
						<a
							data-search-all-results
							href="#"
							class="inline-flex items-center gap-2 text-sm font-medium text-neutral-800 hover:text-black transition-colors duration-200"
						>
							<span data-search-all-results-text></span>
							<?php lenvy_icon('arrow-right', '', 'sm'); ?>
						</a>
					</div>
				</div>

				<!-- No results state -->
				<div data-search-state="no-results" class="hidden">
					<div class="py-8 text-center">
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

	<!-- ── Backdrop ────────────────────────────────────────────────────────── -->
	<div
		data-search-close
		class="flex-1 bg-neutral-950/30 cursor-pointer"
		aria-hidden="true"
		tabindex="-1"
	></div>

</div>
