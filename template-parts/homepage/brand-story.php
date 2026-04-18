<?php
/**
 * Homepage — "Over Lenvy" brand story section.
 *
 * Mirrors Homepage.html `.story`:
 *   grid 1fr / 1fr · gap 80px · items-center
 *   LEFT  — 4:5 warm gradient tile with a decorative rotated bottle shape
 *   RIGHT — eyebrow · display-l heading (bold line + muted block line) ·
 *           2 body paragraphs (max 480px) · "Ons verhaal" arrow link ·
 *           4 story-pills (rounded outline chips)
 *
 * All copy overridable from Theme Settings → Brand Story; sensible
 * Dutch defaults render without configuration.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$image      = lenvy_field('lenvy_brand_story_image', 'options');
$eyebrow    = lenvy_field('lenvy_brand_story_eyebrow', 'options') ?: __('Over Lenvy', 'lenvy');
$heading    = lenvy_field('lenvy_brand_story_heading', 'options') ?: __('Jouw geur,', 'lenvy');
$subheading = lenvy_field('lenvy_brand_story_subheading', 'options') ?: __('zorgvuldig gekozen.', 'lenvy');
$text       = lenvy_field('lenvy_brand_story_text', 'options') ?: __("Bij Lenvy geloven we dat de juiste geur net zo persoonlijk is als een handschrift. Daarom werken we alleen met gerenommeerde parfumhuizen en zijn we selectief in wat we in onze collectie opnemen.\n\nVan tijdloze klassiekers tot zeldzame niche-releases: wat we verkopen, dragen we zelf. En bij elke bestelling leggen we drie samples uit onze collectie bij — omdat de volgende favoriet misschien in een andere fles zit.", 'lenvy');
$link_label = lenvy_field('lenvy_brand_story_link_label', 'options') ?: __('Ons verhaal', 'lenvy');
$link_url   = lenvy_field('lenvy_brand_story_link_url', 'options')   ?: home_url('/over-ons/');

$pills = lenvy_field('lenvy_brand_story_pills', 'options') ?: [];
if (empty($pills)) {
	$pills = [
		['pill_label' => __('100% origineel',        'lenvy')],
		['pill_label' => __('Verzekerd verzonden',   'lenvy')],
		['pill_label' => __('Persoonlijk advies',    'lenvy')],
		['pill_label' => __('Sinds 2019',            'lenvy')],
	];
}

$image_id = is_array($image) ? (int) ($image['ID'] ?? 0) : 0;

$paragraphs = preg_split('/\n\s*\n/', trim((string) $text)) ?: [$text];
?>

<section class="py-20 lg:py-24">
	<div class="lenvy-container">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

			<!-- ── LEFT: editorial image ─────────────────────────────── -->
			<div
				class="relative overflow-hidden aspect-[4/5]"
				style="background:
					radial-gradient(ellipse at 30% 40%, rgba(225,196,255,0.7), transparent 60%),
					linear-gradient(160deg, #efe7dc, #c2aa88);"
			>
				<?php if ($image_id): ?>
					<?php echo wp_get_attachment_image($image_id, 'large', false, [
						'class'   => 'absolute inset-0 w-full h-full object-cover',
						'loading' => 'lazy',
						'alt'     => esc_attr(wp_strip_all_tags($heading . ' ' . $subheading)),
					]); ?>
				<?php else: ?>
					<!-- Floating rotated bottle shape (decorative) -->
					<span
						aria-hidden="true"
						class="absolute left-1/2 top-1/2"
						style="
							width: 50%; height: 70%;
							transform: translate(-50%, -50%) rotate(-4deg);
							background:
								radial-gradient(ellipse at 40% 30%, rgba(255,255,255,0.7), transparent 60%),
								linear-gradient(180deg, #fff6ed, #d8c09a);
							border-radius: 20px 20px 40px 40px;
							box-shadow: 0 40px 80px rgba(80,60,30,0.25);
						"
					></span>
				<?php endif; ?>
			</div>

			<!-- ── RIGHT: editorial copy ─────────────────────────────── -->
			<div>

				<p class="text-[11px] uppercase tracking-[0.18em] text-neutral-500 font-medium">
					<?php echo esc_html($eyebrow); ?>
				</p>

				<h2 class="mt-8 font-medium text-neutral-900 leading-[1.02] tracking-[-0.028em] text-[clamp(2.25rem,4.5vw,4rem)]">
					<?php echo esc_html($heading); ?>
					<?php if ($subheading): ?>
						<span class="block font-normal text-neutral-500">
							<?php echo esc_html($subheading); ?>
						</span>
					<?php endif; ?>
				</h2>

				<div class="my-8 flex flex-col gap-4 max-w-[480px]">
					<?php foreach ($paragraphs as $p):
						$p = trim((string) $p);
						if ($p === '') {
							continue;
						}
					?>
						<p class="text-[15px] leading-[1.65] text-neutral-500 m-0">
							<?php echo esc_html($p); ?>
						</p>
					<?php endforeach; ?>
				</div>

				<?php if ($link_label && $link_url): ?>
					<a
						href="<?php echo esc_url($link_url); ?>"
						class="inline-flex items-center gap-2 text-[13px] font-medium text-neutral-900 border-b border-transparent hover:border-neutral-900 pb-0.5 transition-colors duration-200"
					>
						<?php echo esc_html($link_label); ?>
						<svg class="w-3.5 h-2.5 shrink-0" viewBox="0 0 14 10" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false"><path d="M1 5h12m0 0L9 1m4 4L9 9"/></svg>
					</a>
				<?php endif; ?>

				<?php if (! empty($pills)): ?>
					<ul class="mt-8 flex flex-wrap gap-2 m-0 p-0 list-none">
						<?php foreach ($pills as $pill):
							$label = isset($pill['pill_label']) ? trim((string) $pill['pill_label']) : '';
							if ($label === '') {
								continue;
							}
						?>
							<li class="inline-flex items-center px-3.5 py-2 rounded-full border border-neutral-200 text-[12px] tracking-[0.04em] text-neutral-900">
								<?php echo esc_html($label); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

			</div>

		</div>
	</div>
</section>
