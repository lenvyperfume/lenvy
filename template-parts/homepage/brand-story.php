<?php
/**
 * Homepage — brand story / "Waarom Lenvy" section.
 *
 * Split layout: image left, editorial text right.
 * ACF fields (options page):
 *   lenvy_brand_story_image       image array
 *   lenvy_brand_story_eyebrow     text
 *   lenvy_brand_story_heading     text
 *   lenvy_brand_story_text        textarea
 *   lenvy_brand_story_link_label  text
 *   lenvy_brand_story_link_url    url
 *
 * Falls back to sensible Dutch defaults when ACF fields are empty.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$image    = lenvy_field('lenvy_brand_story_image', 'options');
$eyebrow  = lenvy_field('lenvy_brand_story_eyebrow', 'options') ?: __('Over Lenvy', 'lenvy');
$heading  = lenvy_field('lenvy_brand_story_heading', 'options') ?: __('Jouw bestemming voor exclusieve parfums', 'lenvy');
$text     = lenvy_field('lenvy_brand_story_text', 'options') ?: __('Bij Lenvy geloven we dat de juiste geur je dag compleet maakt. Daarom werken we uitsluitend met gerenommeerde parfumhuizen en bieden we alleen 100% originele producten aan. Van tijdloze klassiekers tot de nieuwste releases — ontdek jouw signature geur met persoonlijk advies en gratis samples bij elke bestelling.', 'lenvy');
$link_label = lenvy_field('lenvy_brand_story_link_label', 'options') ?: __('Meer over ons', 'lenvy');
$link_url   = lenvy_field('lenvy_brand_story_link_url', 'options') ?: home_url('/over-ons/');

$image_id = is_array($image) ? (int) ($image['ID'] ?? 0) : 0;

// Split text into paragraphs on double newlines.
$paragraphs = preg_split('/\n\s*\n/', $text);
if (empty($paragraphs)) {
	$paragraphs = [$text];
}
?>

<section class="py-16 lg:py-24 bg-white">
	<div class="lenvy-section">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center">

			<!-- Image -->
			<div class="relative overflow-hidden aspect-[4/5] lg:aspect-[3/4] bg-neutral-200">
				<?php if ($image_id): ?>
					<?php echo wp_get_attachment_image($image_id, 'large', false, [
						'class'   => 'absolute inset-0 w-full h-full object-cover',
						'loading' => 'lazy',
						'alt'     => esc_attr($heading),
					]); ?>
				<?php else: ?>
					<div class="absolute inset-0 bg-gradient-to-br from-neutral-200 to-neutral-300"></div>
				<?php endif; ?>
			</div>

			<!-- Text -->
			<div class="lg:max-w-lg">

				<p class="text-[11px] uppercase tracking-widest text-neutral-400 mb-4">
					<?php echo esc_html($eyebrow); ?>
				</p>

				<h2 class="text-2xl md:text-3xl lg:text-[2.25rem] font-medium text-neutral-900 leading-tight">
					<?php echo esc_html($heading); ?>
				</h2>

				<div class="mt-6 space-y-4">
					<?php foreach ($paragraphs as $p): ?>
						<p class="text-sm leading-relaxed text-neutral-500 lg:text-[0.9375rem] lg:leading-relaxed">
							<?php echo esc_html(trim($p)); ?>
						</p>
					<?php endforeach; ?>
				</div>

				<?php if ($link_label && $link_url): ?>
					<div class="mt-8">
						<a
							href="<?php echo esc_url($link_url); ?>"
							class="inline-flex items-center gap-2 text-sm font-medium text-neutral-900 underline underline-offset-4 decoration-neutral-300 hover:decoration-neutral-900 transition-colors duration-200"
						>
							<?php echo esc_html($link_label); ?>
							<?php lenvy_icon('arrow-right', '', 'xs'); ?>
						</a>
					</div>
				<?php endif; ?>

			</div>

		</div>
	</div>
</section>
