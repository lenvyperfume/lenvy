<?php
/**
 * Homepage — "Shop per stemming" HARDCODED categories grid.
 *
 * Mirrors Homepage.html `.cat-grid` exactly:
 *   grid-cols 2fr 1fr 1fr · auto-rows 360px · gap 16px
 *   card 1 spans both rows (tall hero tile)
 *   cards 2 & 3 row 1 · cards 4 & 5 row 2
 *
 * All cards render as gradient placeholders with a bottom vignette,
 * 28px heading, 12px caps count, and a round arrow pill that nudges
 * right on hover. Replace with real term data / imagery later.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$shop_url = function_exists('wc_get_page_permalink')
	? wc_get_page_permalink('shop')
	: home_url('/shop/');

/* ── Hardcoded scent-family categories (matches design copy) ───────── */
$cats = [
	[
		'label'    => __('Warm & kruidig', 'lenvy'),
		'count'    => __('142 parfums', 'lenvy'),
		'gradient' => 'linear-gradient(160deg, #f4e0c8, #c99b6a)',
		'url'      => add_query_arg('filter_family', 'warm-kruidig', $shop_url),
	],
	[
		'label'    => __('Bloemig', 'lenvy'),
		'count'    => __('310 parfums', 'lenvy'),
		'gradient' => 'linear-gradient(160deg, #dcc3e8, #8c6ec0)',
		'url'      => add_query_arg('filter_family', 'bloemig', $shop_url),
	],
	[
		'label'    => __('Fris & aquatisch', 'lenvy'),
		'count'    => __('98 parfums', 'lenvy'),
		'gradient' => 'linear-gradient(160deg, #c9d2db, #6b788a)',
		'url'      => add_query_arg('filter_family', 'fris-aquatisch', $shop_url),
	],
	[
		'label'    => __('Gourmand', 'lenvy'),
		'count'    => __('76 parfums', 'lenvy'),
		'gradient' => 'linear-gradient(160deg, #e3c3d0, #a26b85)',
		'url'      => add_query_arg('filter_family', 'gourmand', $shop_url),
	],
	[
		'label'    => __('Houtig', 'lenvy'),
		'count'    => __('205 parfums', 'lenvy'),
		'gradient' => 'linear-gradient(160deg, #d2dccb, #7a9070)',
		'url'      => add_query_arg('filter_family', 'houtig', $shop_url),
	],
];

$render_card = static function (array $cat, string $tile_class = ''): void {
	?>
	<a
		href="<?php echo esc_url($cat['url']); ?>"
		class="group relative block overflow-hidden text-white <?php echo esc_attr($tile_class); ?>"
		aria-label="<?php echo esc_attr($cat['label']); ?>"
	>
		<!-- Gradient tile -->
		<span
			aria-hidden="true"
			class="absolute inset-0 transition-transform duration-[600ms] ease-[cubic-bezier(.2,.7,.2,1)] group-hover:scale-[1.04]"
			style="background: <?php echo esc_attr($cat['gradient']); ?>;"
		></span>

		<!-- Bottom vignette (40% → black 55%) -->
		<span
			aria-hidden="true"
			class="absolute inset-0 pointer-events-none"
			style="background: linear-gradient(180deg, rgba(0,0,0,0) 40%, rgba(0,0,0,0.55) 100%);"
		></span>

		<!-- Label row -->
		<div class="absolute inset-x-7 bottom-6 flex items-end justify-between gap-4">
			<div>
				<h3 class="text-white text-[24px] lg:text-[28px] font-medium leading-tight tracking-[-0.02em]">
					<?php echo esc_html($cat['label']); ?>
				</h3>
				<span class="block mt-1 text-[12px] tracking-[0.1em] text-white/75">
					<?php echo esc_html($cat['count']); ?>
				</span>
			</div>
			<span
				class="inline-flex items-center justify-center w-11 h-11 rounded-full bg-white/95 text-black shrink-0 transition-transform duration-300 group-hover:translate-x-1"
				aria-hidden="true"
			>
				<svg class="w-3.5 h-2.5" viewBox="0 0 14 10" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 5h12m0 0L9 1m4 4L9 9"/></svg>
			</span>
		</div>
	</a>
	<?php
};
?>

<section class="py-20 lg:py-24 bg-neutral-50">
	<div class="lenvy-container">

		<!-- Section head -->
		<div class="flex items-end justify-between gap-8 mb-12 lg:mb-14">
			<div>
				<p class="text-[11px] uppercase tracking-[0.18em] text-neutral-500 font-medium">
					<?php esc_html_e('Categorieën', 'lenvy'); ?>
				</p>
				<h2 class="mt-3 font-medium text-neutral-900 leading-[1.08] tracking-[-0.022em] text-[clamp(1.625rem,2.8vw,2.5rem)]">
					<?php esc_html_e('Shop per stemming', 'lenvy'); ?>
				</h2>
			</div>
			<a
				href="<?php echo esc_url($shop_url); ?>"
				class="hidden sm:inline-flex items-center gap-2 text-[13px] font-medium text-neutral-900 border-b border-transparent hover:border-neutral-900 pb-0.5 transition-colors duration-200 shrink-0"
			>
				<?php esc_html_e('Alle categorieën', 'lenvy'); ?>
				<svg class="w-3.5 h-2.5 shrink-0" viewBox="0 0 14 10" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M1 5h12m0 0L9 1m4 4L9 9"/></svg>
			</a>
		</div>

		<!-- Asymmetric 5-card grid: 2fr · 1fr · 1fr, card 1 spans both rows -->
		<div class="grid grid-cols-1 md:grid-cols-[2fr_1fr_1fr] md:auto-rows-[360px] gap-4">
			<?php
			$render_card($cats[0], 'md:row-span-2 aspect-[4/5] md:aspect-auto');
			$render_card($cats[1], 'aspect-[4/5] md:aspect-auto');
			$render_card($cats[2], 'aspect-[4/5] md:aspect-auto');
			$render_card($cats[3], 'aspect-[4/5] md:aspect-auto');
			$render_card($cats[4], 'aspect-[4/5] md:aspect-auto');
			?>
		</div>

	</div>
</section>
