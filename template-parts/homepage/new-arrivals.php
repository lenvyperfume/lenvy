<?php
/**
 * Homepage — "Vers uit de flacon" new-arrivals carousel (HARDCODED).
 *
 * Mirrors Homepage.html `#new-arrivals` block using the same 8 gradient
 * product placeholders as the bestsellers section. Wired to the existing
 * `[data-product-carousel]` JS module for prev/next & scroll snap.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');

/* ── Gradient variants (reused from bestsellers) ───────────────────── */
$variants = [
	'v1' => ['bg' => 'linear-gradient(160deg, #eee1d1, #d8c3a8)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(255,255,255,0.30))'],
	'v2' => ['bg' => 'linear-gradient(160deg, #f0d9e4, #d9b3c7)', 'bottle' => 'linear-gradient(180deg, rgba(240,230,255,0.80), rgba(200,160,220,0.50))'],
	'v3' => ['bg' => 'linear-gradient(160deg, #d7dbe3, #a8b0bf)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.75), rgba(120,130,160,0.40))'],
	'v4' => ['bg' => 'linear-gradient(160deg, #cde0d4, #8fb09c)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(100,140,110,0.40))'],
	'v5' => ['bg' => 'linear-gradient(160deg, #f5e6b8, #d4b56a)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.80), rgba(180,140,50,0.40))'],
	'v6' => ['bg' => 'linear-gradient(160deg, #e5d4f5, #b89be0)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(150,110,200,0.40))'],
	'v7' => ['bg' => 'linear-gradient(160deg, #d1c9b8, #8a7f63)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(80,70,50,0.40))'],
	'v8' => ['bg' => 'linear-gradient(160deg, #e0e0dc, #a8a8a2)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.75), rgba(90,90,85,0.30))'],
];

/* ── Hardcoded data (from Homepage.html NEW_ARR) ───────────────────── */
$new_arrivals = [
	['brand' => 'Viridis',        'name' => 'Figuier Vert',    'variant' => '75 ml · EDP',    'price' => '€ 132,00', 'v' => 'v4'],
	['brand' => 'Antidote',       'name' => 'Café Brûlé',      'variant' => '50 ml · EDP',    'price' => '€ 98,00',  'v' => 'v7'],
	['brand' => 'Florière',       'name' => 'Iris Pâle',       'variant' => '50 ml · EDP',    'price' => '€ 124,00', 'v' => 'v6'],
	['brand' => 'Nocturna',       'name' => 'Musc Rouge',      'variant' => '100 ml · Parfum','price' => '€ 176,00', 'v' => 'v2'],
	['brand' => 'Helve',          'name' => 'Pin Givré',       'variant' => '75 ml · EDP',    'price' => '€ 148,00', 'v' => 'v4'],
	['brand' => 'De Saint',       'name' => 'Papier & Soie',   'variant' => '50 ml · EDP',    'price' => '€ 114,00', 'v' => 'v8'],
	['brand' => 'Maison Verdier', 'name' => 'Rose de Nuit',    'variant' => '75 ml · Parfum', 'price' => '€ 168,00', 'v' => 'v2'],
];
?>

<section class="py-20 lg:py-24 bg-neutral-50" data-product-carousel>
	<div class="lenvy-container">

		<!-- Section head -->
		<div class="flex items-end justify-between gap-8 mb-12 lg:mb-14">
			<div>
				<p class="text-[11px] uppercase tracking-[0.18em] text-neutral-500 font-medium">
					<?php esc_html_e('Nieuw binnen · Week 16', 'lenvy'); ?>
				</p>
				<h2 class="mt-3 font-medium text-neutral-900 leading-[1.08] tracking-[-0.022em] text-[clamp(1.625rem,2.8vw,2.5rem)]">
					<?php esc_html_e('Vers uit de flacon', 'lenvy'); ?>
				</h2>
			</div>

			<div class="flex items-center gap-6 shrink-0">
				<a
					href="<?php echo esc_url(add_query_arg('orderby', 'date', $shop_url)); ?>"
					class="hidden sm:inline-flex items-center gap-2 text-[13px] font-medium text-neutral-900 border-b border-transparent hover:border-neutral-900 pb-0.5 transition-colors duration-200"
				>
					<?php esc_html_e('Alles bekijken', 'lenvy'); ?>
					<svg class="w-3.5 h-2.5 shrink-0" viewBox="0 0 14 10" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M1 5h12m0 0L9 1m4 4L9 9"/></svg>
				</a>

				<!-- Nav arrows -->
				<div class="hidden md:flex items-center gap-2">
					<button
						type="button"
						data-carousel-prev
						class="lenvy-product-carousel__arrow w-11 h-11"
						aria-label="<?php esc_attr_e('Vorige', 'lenvy'); ?>"
						disabled
					>
						<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
					</button>
					<button
						type="button"
						data-carousel-next
						class="lenvy-product-carousel__arrow w-11 h-11"
						aria-label="<?php esc_attr_e('Volgende', 'lenvy'); ?>"
					>
						<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
					</button>
				</div>
			</div>
		</div>

		<!-- Carousel rail -->
		<div class="lenvy-product-carousel__track scrollbar-hide pb-2" data-carousel-track>
			<?php foreach ($new_arrivals as $p): ?>
				<div class="lenvy-product-carousel__item">
					<?php get_template_part('template-parts/components/product-card-placeholder', null, [
						'brand'             => $p['brand'],
						'name'              => $p['name'],
						'variant'           => $p['variant'],
						'price'             => $p['price'],
						'was'               => null,
						'tag'               => 'new',
						'v'                 => $p['v'],
						'variant_gradients' => $variants,
					]); ?>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
