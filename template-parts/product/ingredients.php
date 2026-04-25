<?php
/**
 * PDP ingredients section — 4 swatches with origin + description.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$product = $args['product'] ?? null;
if (!$product || empty($product['ingredients'])) {
	return;
}
?>

<section class="lenvy-block lenvy-block--alt">
	<div class="lenvy-container">

		<header class="lenvy-pdp__section-head">
			<div>
				<span class="lenvy-pdp__eyebrow"><?php esc_html_e('Belangrijkste grondstoffen', 'lenvy'); ?></span>
				<h2 class="lenvy-pdp__display-m"><?php esc_html_e('Vier materialen die deze geur definiëren', 'lenvy'); ?></h2>
			</div>
		</header>

		<div class="lenvy-pdp-ingr-grid">
			<?php foreach ($product['ingredients'] as $ingr): ?>
				<div class="lenvy-pdp-ingr lenvy-pdp-ingr--<?php echo esc_attr($ingr['class']); ?>">
					<div class="lenvy-pdp-ingr__swatch" aria-hidden="true"></div>
					<div class="lenvy-pdp-ingr__meta">
						<h4><?php echo esc_html($ingr['title']); ?></h4>
						<span class="lenvy-pdp-ingr__origin"><?php echo esc_html($ingr['origin']); ?></span>
					</div>
					<p><?php echo esc_html($ingr['desc']); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
