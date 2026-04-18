<?php
/**
 * Homepage — "Uitgelicht merk" / brand spotlight.
 *
 * Mirrors Homepage.html `.promo-split`:
 *   2 equal columns · min-height 520px · section padding 64px (tight block)
 *   LEFT  — warm radial/linear gradient tile with a blurred organic blob
 *   RIGHT — dark panel · lavender eyebrow · white display heading ·
 *           muted paragraph · primary CTA + white text-link
 *
 * All copy is overridable via ACF (homepage group). Sensible Dutch
 * defaults are baked in so the block renders without configuration.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$eyebrow  = lenvy_field('lenvy_spotlight_eyebrow')        ?: __('Uitgelicht merk', 'lenvy');
$heading  = lenvy_field('lenvy_spotlight_heading')        ?: __("Maison Verdier\nin de spotlight.", 'lenvy');
$desc     = lenvy_field('lenvy_spotlight_description')    ?: __('Een Parijs atelier dat al drie generaties parfums blendt in kleine oplage. Ontdek de volledige collectie — nu compleet op Lenvy, met gratis samples bij elke bestelling.', 'lenvy');
$image    = lenvy_field('lenvy_spotlight_image');

$cta_text = lenvy_field('lenvy_spotlight_primary_text')   ?: __('Shop Maison Verdier', 'lenvy');
$cta_url  = lenvy_field('lenvy_spotlight_primary_url')    ?: home_url('/merk/maison-verdier/');
$sec_text = lenvy_field('lenvy_spotlight_secondary_text') ?: __('Alle merken', 'lenvy');
$sec_url  = lenvy_field('lenvy_spotlight_secondary_url')  ?: home_url('/merken/');

$image_id = is_array($image) ? (int) ($image['ID'] ?? 0) : 0;
?>

<section class="py-16">
	<div class="lenvy-container">
		<div class="grid grid-cols-1 md:grid-cols-2 min-h-[520px]">

			<!-- ── LEFT: image side (warm gradient + blurred blob) ─────── -->
			<div
				class="relative overflow-hidden min-h-[280px] md:min-h-0"
				style="background:
					radial-gradient(ellipse at 70% 30%, rgba(225,196,255,0.5), transparent 60%),
					linear-gradient(160deg, #faf5f0, #e8dbc9);"
			>
				<?php if ($image_id): ?>
					<?php echo wp_get_attachment_image($image_id, 'large', false, [
						'class'   => 'absolute inset-0 w-full h-full object-cover',
						'loading' => 'lazy',
						'alt'     => esc_attr(wp_strip_all_tags($heading)),
					]); ?>
				<?php else: ?>
					<!-- Blurred organic blob placeholder -->
					<span
						aria-hidden="true"
						class="absolute"
						style="
							right: -10%; top: 10%;
							width: 70%; height: 80%;
							background:
								radial-gradient(ellipse at 40% 30%, rgba(255,255,255,0.6), transparent 60%),
								linear-gradient(160deg, #e8cfa9, #b39270);
							border-radius: 40% 60% 50% 50% / 40% 50% 50% 60%;
							filter: blur(20px);
							opacity: 0.8;
						"
					></span>
				<?php endif; ?>
			</div>

			<!-- ── RIGHT: dark text side ──────────────────────────────── -->
			<div class="bg-neutral-950 text-[#f6f5f2] flex flex-col justify-center px-8 py-14 md:p-16">

				<p class="text-[11px] uppercase tracking-[0.18em] font-medium text-primary/90">
					<?php echo esc_html($eyebrow); ?>
				</p>

				<h2 class="mt-8 text-white font-medium leading-[1.08] tracking-[-0.022em] text-[clamp(1.625rem,2.8vw,2.5rem)] max-w-[440px]">
					<?php echo wp_kses(nl2br(esc_html($heading)), ['br' => []]); ?>
				</h2>

				<?php if ($desc): ?>
					<p class="mt-6 mb-8 max-w-[440px] text-[15px] leading-[1.6] text-white/70">
						<?php echo esc_html($desc); ?>
					</p>
				<?php endif; ?>

				<div class="flex flex-wrap items-center gap-3">
					<?php if ($cta_text && $cta_url): ?>
						<a
							href="<?php echo esc_url($cta_url); ?>"
							class="inline-flex items-center gap-2.5 px-7 py-4 bg-primary text-black text-[13px] font-medium tracking-[0.02em] hover:bg-primary-hover transition-colors duration-200"
						>
							<?php echo esc_html($cta_text); ?>
						</a>
					<?php endif; ?>

					<?php if ($sec_text && $sec_url): ?>
						<a
							href="<?php echo esc_url($sec_url); ?>"
							class="inline-flex items-center gap-2 text-[13px] font-medium text-white border-b border-transparent hover:border-white transition-colors duration-200 pb-0.5"
						>
							<?php echo esc_html($sec_text); ?>
						</a>
					<?php endif; ?>
				</div>

			</div>

		</div>
	</div>
</section>
