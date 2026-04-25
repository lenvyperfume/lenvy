<?php
/**
 * PDP FAQ accordion section.
 *
 * Toggle behaviour is handled inline by main.js — clicking a `.lenvy-pdp-faq__q`
 * toggles the `.is-open` class on its parent `.lenvy-pdp-faq__item`.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$product = $args['product'] ?? null;
if (!$product || empty($product['faq'])) {
	return;
}

$f = $product['faq'];
?>

<section class="lenvy-block lenvy-block--alt">
	<div class="lenvy-container">
		<div class="lenvy-pdp-faq">

			<div class="lenvy-pdp-faq__intro">
				<?php if (!empty($f['eyebrow'])): ?>
					<span class="lenvy-pdp__eyebrow"><?php echo esc_html($f['eyebrow']); ?></span>
				<?php endif; ?>
				<h2 class="lenvy-pdp__display-m"><?php echo esc_html($f['title']); ?></h2>
				<?php if (!empty($f['lede'])): ?>
					<p class="lenvy-pdp-faq__lede"><?php echo esc_html($f['lede']); ?></p>
				<?php endif; ?>
			</div>

			<div class="lenvy-pdp-faq__list">
				<?php foreach ($f['items'] as $item):
					$open = !empty($item['open']);
				?>
					<div class="lenvy-pdp-faq__item<?php echo $open ? ' is-open' : ''; ?>" data-pdp-faq>
						<button
							type="button"
							class="lenvy-pdp-faq__q"
							data-pdp-faq-toggle
							aria-expanded="<?php echo $open ? 'true' : 'false'; ?>"
						>
							<span><?php echo esc_html($item['q']); ?></span>
							<span class="lenvy-pdp-faq__plus" aria-hidden="true">+</span>
						</button>
						<div class="lenvy-pdp-faq__a">
							<?php echo esc_html($item['a']); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

		</div>
	</div>
</section>
