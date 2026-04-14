<?php
/**
 * Homepage — SEO content block (collapsible).
 *
 * Gives Google real text to index. Visually minimal — shows first ~3 lines
 * with a "Lees meer" toggle that reveals the rest. Common pattern on
 * Dutch e-commerce sites (bol.com, Coolblue, Skins).
 *
 * ACF fields (options page):
 *   lenvy_seo_heading  text
 *   lenvy_seo_content  wysiwyg
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$heading = lenvy_field('lenvy_seo_heading', 'options') ?: __('Parfum online kopen bij Lenvy', 'lenvy');
$content = lenvy_field('lenvy_seo_content', 'options');

// Fallback content — real, useful text for SEO.
if (empty($content)) {
	$content = '<p>' . esc_html__('Welkom bij Lenvy, jouw online bestemming voor luxe parfums en heerlijke geuren. Of je nu op zoek bent naar een nieuwe signature geur, een cadeau voor iemand speciaal, of gewoon wilt genieten van de beste parfums ter wereld — bij Lenvy ben je aan het juiste adres.', 'lenvy') . '</p>'
		. '<p>' . esc_html__('Ons assortiment omvat parfums van de meest gerenommeerde merken, waaronder zowel tijdloze klassiekers als de nieuwste releases. We bieden uitsluitend 100% originele producten aan, rechtstreeks van de officiële distributeurs. Zo weet je zeker dat je altijd de echte geur ontvangt.', 'lenvy') . '</p>'
		. '<p>' . esc_html__('Bij Lenvy draait alles om de beleving. Daarom ontvang je bij elke bestelling gratis samples, zodat je altijd nieuwe geuren kunt ontdekken. Bestel je voor 17:00 uur? Dan heb je je pakket de volgende dag al in huis. En met gratis verzending vanaf €50 wordt online parfum kopen nog aantrekkelijker.', 'lenvy') . '</p>';
}
?>

<section class="py-12 lg:py-16 border-t border-neutral-100">
	<div class="lenvy-container">

		<h2 class="text-lg font-medium text-neutral-900 mb-4 lg:text-xl">
			<?php echo esc_html($heading); ?>
		</h2>

		<div
			data-seo-block
			class="relative"
		>
			<!-- Content — clamped by default -->
			<div
				data-seo-text
				class="text-sm leading-relaxed text-neutral-500 space-y-3 max-h-[4.5em] overflow-hidden transition-[max-height] duration-300 ease-in-out"
			>
				<?php echo wp_kses_post($content); ?>
			</div>

			<!-- Fade overlay — hidden when expanded -->
			<div
				data-seo-fade
				class="absolute bottom-0 left-0 right-0 h-10 bg-gradient-to-t from-white to-transparent pointer-events-none"
			></div>
		</div>

		<button
			type="button"
			data-seo-toggle
			class="mt-3 text-sm font-medium text-neutral-900 underline underline-offset-4 decoration-neutral-300 hover:decoration-neutral-900 transition-colors duration-200"
		>
			<?php esc_html_e('Lees meer', 'lenvy'); ?>
		</button>

	</div>
</section>

<script>
(function() {
	const block = document.querySelector('[data-seo-block]');
	if (!block) return;

	const text = block.querySelector('[data-seo-text]');
	const fade = block.querySelector('[data-seo-fade]');
	const btn  = document.querySelector('[data-seo-toggle]');
	if (!text || !btn) return;

	let open = false;

	btn.addEventListener('click', function() {
		open = !open;

		if (open) {
			text.style.maxHeight = text.scrollHeight + 'px';
			if (fade) fade.style.opacity = '0';
			btn.textContent = '<?php echo esc_js(__('Lees minder', 'lenvy')); ?>';
		} else {
			text.style.maxHeight = '4.5em';
			if (fade) fade.style.opacity = '1';
			btn.textContent = '<?php echo esc_js(__('Lees meer', 'lenvy')); ?>';
		}
	});
})();
</script>
