<?php
/**
 * Homepage — editorial brand moment.
 *
 * Large serif display heading with subheading and optional CTA.
 * Creates a typographic pause that establishes brand identity between
 * product sections.
 *
 * ACF fields (options page):
 *   lenvy_editorial_heading    text
 *   lenvy_editorial_subheading textarea
 *   lenvy_editorial_cta_label  text (optional)
 *   lenvy_editorial_cta_url    url  (optional)
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$heading    = lenvy_field('lenvy_editorial_heading', 'options');
$subheading = lenvy_field('lenvy_editorial_subheading', 'options');
$cta_label  = lenvy_field('lenvy_editorial_cta_label', 'options');
$cta_url    = lenvy_field('lenvy_editorial_cta_url', 'options');

// Fallback defaults when ACF fields are not yet configured
if (empty($heading)) {
	$heading = __('De Kunst van Geur', 'lenvy');
}

if (empty($subheading)) {
	$subheading = __('Zorgvuldig samengestelde parfums van \'s werelds meest gewaardeerde huizen — voor hem, voor haar, voor iedereen die schoonheid waardeert.', 'lenvy');
}

if (empty($cta_label)) {
	$cta_label = __('Ontdek de Collectie', 'lenvy');
}

if (empty($cta_url)) {
	$cta_url = function_exists('wc_get_page_permalink')
		? wc_get_page_permalink('shop')
		: home_url('/shop/');
}
?>

<section class="py-20 lg:py-32">
	<div class="lenvy-container">
		<div class="max-w-3xl mx-auto text-center">

			<h2
				class="font-serif italic text-neutral-900 leading-[1.1]"
				style="font-size: var(--text-display);"
			>
				<?php echo esc_html($heading); ?>
			</h2>

			<?php if ($subheading): ?>
			<p class="mt-6 lg:mt-8 text-base lg:text-lg text-neutral-500 leading-relaxed max-w-xl mx-auto">
				<?php echo esc_html($subheading); ?>
			</p>
			<?php endif; ?>

			<?php if ($cta_label && $cta_url): ?>
			<a
				href="<?php echo esc_url($cta_url); ?>"
				class="inline-flex items-center gap-2 mt-8 lg:mt-10 text-[13px] font-medium text-neutral-800 border-b border-neutral-300 pb-1 hover:border-neutral-800 hover:text-black transition-colors duration-200"
			>
				<?php echo esc_html($cta_label); ?>
				<?php lenvy_icon('arrow-right', '', 'xs'); ?>
			</a>
			<?php endif; ?>

		</div>
	</div>
</section>
