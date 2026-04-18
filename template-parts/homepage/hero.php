<?php
/**
 * Homepage hero — fully HARDCODED placeholder content (matches Homepage.html).
 *
 * LEFT  — eyebrow row w/ leading rule, display heading + muted sub-line,
 *         lede, lavender primary CTA + arrow text-link
 * RIGHT — purple gradient placeholder with decorative bottle cap/body,
 *         floating price tag, 2-line caption, carousel dots
 * FOOTER — 56px band with 4 equal USP columns
 *
 * All copy + imagery is placeholder. Swap in ACF lookups or real data later.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');

$usps = [
	['icon' => 'truck',   'text' => __('Vandaag besteld, morgen in huis', 'lenvy')],
	['icon' => 'refresh', 'text' => __('Gratis retour binnen 30 dagen', 'lenvy')],
	['icon' => 'heart',   'text' => __('Gratis samples bij elke bestelling', 'lenvy')],
	['icon' => 'shield',  'text' => __('100% originele parfums', 'lenvy')],
];

$slide_count = 4;

$render_hero_slide = static function (): void {
	?>
	<div class="lenvy-hero-slide relative shrink-0 grow-0 basis-full min-w-0 overflow-hidden" style="background: linear-gradient(135deg, #e8dbff 0%, #d9bdf6 40%, #bfa3e4 100%);">

		<!-- Radial/linear purple inner tint -->
		<span
			aria-hidden="true"
			class="absolute inset-0 pointer-events-none"
			style="background:
				radial-gradient(ellipse 50% 40% at 50% 60%, rgba(0,0,0,0.15), transparent 70%),
				linear-gradient(160deg, #e8dbff 0%, #cbb0ee 60%, #9f83c9 100%);"
		></span>

		<!-- Bottle cap (decorative) -->
		<span
			aria-hidden="true"
			class="absolute left-1/2 -translate-x-1/2"
			style="
				bottom: calc(10% + 370px);
				width: 80px; height: 50px;
				background: #1a1918;
				border-radius: 4px;
				box-shadow: 0 10px 30px rgba(0,0,0,0.3);
			"
		></span>

		<!-- Bottle body (decorative frosted glass) -->
		<span
			aria-hidden="true"
			class="absolute left-1/2 -translate-x-1/2 bottom-[10%]"
			style="
				width: 220px; height: 380px;
				background: linear-gradient(180deg, rgba(255,255,255,0.85) 0%, rgba(255,255,255,0.55) 30%, rgba(255,255,255,0.25) 100%);
				border-radius: 20px 20px 40px 40px;
				box-shadow:
					inset 0 0 60px rgba(255,255,255,0.4),
					inset 0 -40px 60px rgba(140,100,190,0.2),
					0 40px 80px rgba(60,30,100,0.25);
				backdrop-filter: blur(3px);
				-webkit-backdrop-filter: blur(3px);
			"
		></span>

		<!-- Bottom-left darkening gradient for caption readability -->
		<span
			aria-hidden="true"
			class="absolute inset-0 pointer-events-none"
			style="background: linear-gradient(to top right, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.1) 35%, transparent 60%);"
		></span>

		<!-- Floating price tag — top-right -->
		<div class="absolute right-6 top-6 lg:right-10 lg:top-10 bg-white/95 text-neutral-950 px-4 py-3.5 min-w-[170px] flex flex-col gap-1.5 shadow-sm">
			<span class="text-[11px] tracking-[0.15em] uppercase text-neutral-500">
				<?php esc_html_e('Uitgelicht', 'lenvy'); ?>
			</span>
			<strong class="font-medium text-[14px] tracking-[0.02em]">
				<?php esc_html_e('Lumière Boisée', 'lenvy'); ?>
			</strong>
			<span class="text-[18px] font-medium tracking-[-0.01em]">€ 128,00</span>
			<span class="text-[13px] text-neutral-500">
				<?php esc_html_e('50 ml · Eau de Parfum', 'lenvy'); ?>
			</span>
		</div>

		<!-- Caption — bottom-left, two lines -->
		<div class="absolute bottom-8 left-8 lg:bottom-10 lg:left-10 flex flex-col gap-1 drop-shadow-[0_2px_6px_rgba(0,0,0,0.35)]">
			<span class="text-[11px] tracking-[0.15em] uppercase text-white/85">
				<?php esc_html_e('Maison Verdier · 2026', 'lenvy'); ?>
			</span>
			<span class="text-[14px] tracking-[0.05em] text-white">
				<?php esc_html_e('N°07 — Lumière Boisée', 'lenvy'); ?>
			</span>
		</div>

	</div>
	<?php
};
?>

<section
	class="relative flex flex-col bg-neutral-50 overflow-hidden lg:max-h-[820px] lg:min-h-[600px] lg:h-[calc(100svh-var(--header-height,132px)-42px)]"
	aria-label="<?php esc_attr_e('Hero', 'lenvy'); ?>"
>
	<div class="grid grid-cols-1 lg:grid-cols-[1.05fr_1fr] flex-1 min-h-0">

		<!-- ── LEFT: editorial copy ─────────────────────────────────── -->
		<div class="relative flex flex-col justify-between gap-8 px-6 py-12 sm:px-10 lg:py-12 lg:pr-12 lg:pl-[max(2.5rem,calc((100vw-1440px)/2+2.5rem))]">

			<!-- Eyebrow row with leading rule -->
			<div class="flex items-center gap-4">
				<span aria-hidden="true" class="block w-9 h-px bg-neutral-900/40"></span>
				<span class="text-[11px] tracking-[0.18em] uppercase text-neutral-500 font-medium">
					<?php esc_html_e('Voorjaar · 26 editie', 'lenvy'); ?>
				</span>
			</div>

			<div>
				<h2 class="font-medium text-neutral-900 leading-[1.02] tracking-[-0.035em] text-[clamp(2.25rem,4.6vw,4.5rem)] max-w-[640px]">
					<?php esc_html_e('Een geur', 'lenvy'); ?><br>
					<?php esc_html_e('is een herinnering', 'lenvy'); ?>
					<span class="block font-normal text-neutral-500 tracking-[-0.02em]">
						<?php esc_html_e('— nog niet gemaakt.', 'lenvy'); ?>
					</span>
				</h2>

				<p class="mt-5 max-w-[460px] text-[15px] leading-[1.55] text-neutral-500">
					<?php esc_html_e('Lenvy is je bestemming voor zorgvuldig geselecteerde parfums uit de meest iconische huizen. 100% origineel, altijd met gratis samples.', 'lenvy'); ?>
				</p>

				<div class="mt-5 flex flex-wrap items-center gap-3">
					<a
						href="<?php echo esc_url($shop_url); ?>"
						class="inline-flex items-center gap-2.5 px-7 py-4 bg-primary text-black text-[13px] font-medium tracking-[0.02em] hover:bg-primary-hover transition-colors duration-200"
					>
						<?php esc_html_e('Ontdek de collectie', 'lenvy'); ?>
						<svg class="w-3.5 h-2.5 shrink-0" viewBox="0 0 14 10" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false"><path d="M1 5h12m0 0L9 1m4 4L9 9"/></svg>
					</a>

					<a
						href="<?php echo esc_url(add_query_arg('orderby', 'popularity', $shop_url)); ?>"
						class="inline-flex items-center gap-2 text-[13px] font-medium text-neutral-900 border-b border-transparent hover:border-neutral-900 transition-colors duration-200 pb-0.5"
					>
						<?php esc_html_e('Bekijk bestsellers', 'lenvy'); ?>
						<svg class="w-3.5 h-2.5 shrink-0" viewBox="0 0 14 10" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false"><path d="M1 5h12m0 0L9 1m4 4L9 9"/></svg>
					</a>
				</div>
			</div>

		</div>

		<!-- ── RIGHT: Embla slider ──────────────────────────────────── -->
		<div class="relative overflow-hidden min-h-[380px] sm:min-h-[480px] lg:min-h-0 bg-neutral-200">

			<!-- Embla viewport (+ absolute-fill so dots can layer on top) -->
			<div class="absolute inset-0 overflow-hidden" data-hero-slider>
				<div class="flex h-full touch-pan-y">
					<?php for ($i = 0; $i < $slide_count; $i++) {
						$render_hero_slide();
					} ?>
				</div>
			</div>

			<!-- Carousel dots (buttons) -->
			<div
				class="absolute bottom-10 right-10 hidden sm:flex items-center gap-1.5 z-10"
				data-hero-slider-dots
			>
				<?php for ($i = 0; $i < $slide_count; $i++): ?>
					<button
						type="button"
						class="lenvy-hero-dot w-2 h-2 rounded-full bg-white/40 hover:bg-white/70 transition-colors duration-200"
						aria-label="<?php echo esc_attr(sprintf(__('Ga naar slide %d', 'lenvy'), $i + 1)); ?>"
					></button>
				<?php endfor; ?>
			</div>

		</div>

	</div>

	<!-- ── In-hero USP strip (56px band, 4 equal columns) ──────────── -->
	<div class="shrink-0 border-y border-neutral-200 bg-white">
		<div class="lenvy-container">
			<ul class="grid grid-cols-2 lg:grid-cols-4 items-center lg:h-14 m-0 p-0 list-none">
				<?php foreach ($usps as $usp): ?>
					<li class="flex items-center justify-center gap-3 py-3 lg:py-0 text-[13px] text-neutral-700">
						<span class="shrink-0 text-neutral-500 inline-flex">
							<?php lenvy_icon($usp['icon'], '', 'sm'); ?>
						</span>
						<span class="text-center lg:text-left"><?php echo esc_html($usp['text']); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
