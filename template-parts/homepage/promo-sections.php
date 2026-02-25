<?php
/**
 * Homepage — flexible content promo sections.
 *
 * Handles up to 4 flexible content layouts from lenvy_promo_section:
 *
 *   text_banner  — full-width editorial section: background image (or dark fallback),
 *                  Playfair heading, subheading, optional CTA
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$sections = lenvy_field('lenvy_promo_section');

if (empty($sections)) {
	return;
}

foreach ($sections as $section):

	$layout = $section['acf_fc_layout'] ?? '';

	// ── Text banner ──────────────────────────────────────────────────────────

	if ('text_banner' === $layout):

		$bg_image = $section['background_image'] ?? null;
		$heading = $section['heading'] ?? '';
		$subhead = $section['subheading'] ?? '';
		$cta_label = $section['cta_label'] ?? '';
		$cta_url = $section['cta_url'] ?? '';

		$image_id = is_array($bg_image) ? $bg_image['ID'] ?? 0 : 0;
		$has_image = (bool) $image_id;
		?>

		<section class="relative py-24 lg:py-36 flex items-center justify-center text-center overflow-hidden bg-neutral-950">

			<!-- Background image -->
			<?php if ($has_image): ?>
				<?php echo wp_get_attachment_image($image_id, 'full', false, [
    	'class' => 'absolute inset-0 w-full h-full object-cover object-center',
    	'loading' => 'lazy',
    	'alt' => '',
    	'aria-hidden' => 'true',
    ]); ?>
				<div class="absolute inset-0 bg-neutral-950/65" aria-hidden="true"></div>
			<?php else: ?>
				<!-- Decorative subtle grain texture via pseudo-element (CSS) -->
				<div class="absolute inset-0 opacity-5 bg-[url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Cfilter id=%22n%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22200%22 height=%22200%22 filter=%22url(%23n)%22 opacity=%221%22/%3E%3C/svg%3E')]" aria-hidden="true"></div>
			<?php endif; ?>

			<!-- Content -->
			<div class="relative z-10 lenvy-container">
				<div class="max-w-2xl mx-auto space-y-6">

					<?php if ($heading): ?>
						<h2
							class="font-serif italic text-white leading-tight"
							style="font-size: clamp(2.25rem, 5vw, 4rem);"
						>
							<?php echo esc_html($heading); ?>
						</h2>
					<?php endif; ?>

					<?php if ($subhead): ?>
						<p class="text-sm text-white/60 leading-relaxed max-w-lg mx-auto">
							<?php echo esc_html($subhead); ?>
						</p>
					<?php endif; ?>

					<?php if ($cta_label && $cta_url): ?>
						<div class="pt-2">
							<a
								href="<?php echo esc_url($cta_url); ?>"
								class="inline-flex items-center gap-2.5 border border-white/30 text-white font-medium uppercase tracking-widest text-xs px-7 py-3.5 hover:bg-white hover:text-black transition-colors duration-200"
							>
								<span><?php echo esc_html($cta_label); ?></span>
								<?php lenvy_icon('arrow-right', '', 'sm'); ?>
							</a>
						</div>
					<?php endif; ?>

				</div>
			</div>

		</section>

	<?php
	endif; // text_banner

?>

<?php
endforeach; ?>
