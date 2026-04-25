<?php
/**
 * Homepage — Bestsellers ("Meest geliefd") HARDCODED preview.
 *
 * Mirrors the 8 cards in Homepage.html (BESTSELLERS constant) using
 * gradient placeholders in place of real product imagery. Replace with
 * a WC-backed template once there are real products to show.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');

/* ── Gradient variants (v1–v8) matching the design ─────────────────── */
$variants = [
	'v1' => ['bg' => 'linear-gradient(160deg, #eee1d1, #d8c3a8)',          'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(255,255,255,0.30))'],
	'v2' => ['bg' => 'linear-gradient(160deg, #f0d9e4, #d9b3c7)',          'bottle' => 'linear-gradient(180deg, rgba(240,230,255,0.80), rgba(200,160,220,0.50))'],
	'v3' => ['bg' => 'linear-gradient(160deg, #d7dbe3, #a8b0bf)',          'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.75), rgba(120,130,160,0.40))'],
	'v4' => ['bg' => 'linear-gradient(160deg, #cde0d4, #8fb09c)',          'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(100,140,110,0.40))'],
	'v5' => ['bg' => 'linear-gradient(160deg, #f5e6b8, #d4b56a)',          'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.80), rgba(180,140,50,0.40))'],
	'v6' => ['bg' => 'linear-gradient(160deg, #e5d4f5, #b89be0)',          'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(150,110,200,0.40))'],
	'v7' => ['bg' => 'linear-gradient(160deg, #d1c9b8, #8a7f63)',          'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(80,70,50,0.40))'],
	'v8' => ['bg' => 'linear-gradient(160deg, #e0e0dc, #a8a8a2)',          'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.75), rgba(90,90,85,0.30))'],
];

/* ── Hardcoded product data (from Homepage.html) ───────────────────── */
$bestsellers = [
	['brand' => 'Maison Verdier', 'name' => 'Lumière Boisée', 'variant' => '50 ml · Eau de Parfum', 'price' => '€ 128,00', 'was' => null,      'tag' => null,   'v' => 'v1'],
	['brand' => 'Florière',       'name' => 'Rose Confite',   'variant' => '75 ml · Eau de Parfum', 'price' => '€ 94,00',  'was' => '€ 115,00', 'tag' => 'sale', 'v' => 'v2'],
	['brand' => 'Nocturna',       'name' => "Ambre d'Hiver",  'variant' => '100 ml · Parfum',       'price' => '€ 186,00', 'was' => null,      'tag' => null,   'v' => 'v6'],
	['brand' => 'De Saint',       'name' => 'Salinité',       'variant' => '50 ml · Eau de Parfum', 'price' => '€ 108,00', 'was' => null,      'tag' => 'new',  'v' => 'v3'],
	['brand' => 'Helve',          'name' => 'Thé Fumé',       'variant' => '75 ml · Eau de Parfum', 'price' => '€ 142,00', 'was' => null,      'tag' => null,   'v' => 'v7'],
	['brand' => 'Beaumer',        'name' => 'Fleur Blanche',  'variant' => '50 ml · Eau de Parfum', 'price' => '€ 96,00',  'was' => null,      'tag' => null,   'v' => 'v8'],
	['brand' => 'Orage',          'name' => 'Cuir Noir',      'variant' => '100 ml · Parfum',       'price' => '€ 164,00', 'was' => '€ 198,00', 'tag' => 'sale', 'v' => 'v3'],
	['brand' => 'Lune & Lys',     'name' => 'Vanille Grise',  'variant' => '50 ml · Eau de Parfum', 'price' => '€ 118,00', 'was' => null,      'tag' => null,   'v' => 'v5'],
];
?>

<section class="py-20 lg:py-24">
	<div class="lenvy-container">

		<!-- Section head -->
		<div class="flex items-end justify-between gap-8 mb-12 lg:mb-14">
			<div>
				<p class="text-[11px] uppercase tracking-[0.18em] text-neutral-500 font-medium">
					<?php esc_html_e('Bestsellers', 'lenvy'); ?>
				</p>
				<h2 class="mt-3 font-medium text-neutral-900 leading-[1.08] tracking-[-0.022em] text-[clamp(1.625rem,2.8vw,2.5rem)]">
					<?php esc_html_e('Meest geliefd', 'lenvy'); ?>
				</h2>
			</div>
			<div class="hidden md:flex items-center gap-6 shrink-0">
				<span class="text-[13px] text-neutral-500">
					<?php esc_html_e('Jouw selectie deze maand', 'lenvy'); ?>
				</span>
				<a
					href="<?php echo esc_url(add_query_arg('orderby', 'popularity', $shop_url)); ?>"
					class="inline-flex items-center gap-2 text-[13px] font-medium text-neutral-900 border-b border-transparent hover:border-neutral-900 pb-0.5 transition-colors duration-200"
				>
					<?php esc_html_e('Alles bekijken', 'lenvy'); ?>
					<svg class="w-3.5 h-2.5 shrink-0" viewBox="0 0 14 10" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M1 5h12m0 0L9 1m4 4L9 9"/></svg>
				</a>
			</div>
		</div>

		<!-- Product grid -->
		<div class="lenvy-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
			<?php foreach ($bestsellers as $p): ?>
				<?php get_template_part('template-parts/components/product-card-placeholder', null, [
					'brand'             => $p['brand'],
					'name'              => $p['name'],
					'variant'           => $p['variant'],
					'price'             => $p['price'],
					'was'               => $p['was'],
					'tag'               => $p['tag'],
					'v'                 => $p['v'],
					'variant_gradients' => $variants,
				]); ?>
			<?php endforeach; ?>
		</div>

	</div>
</section>
