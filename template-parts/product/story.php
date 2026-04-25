<?php
/**
 * PDP house story section.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$product = $args['product'] ?? null;
if (!$product) {
	return;
}

$s = $product['story'];
?>

<section class="lenvy-block">
	<div class="lenvy-container">
		<div class="lenvy-pdp-story">

			<div class="lenvy-pdp-story__img" aria-hidden="true">
				<?php if (!empty($s['cred'])): ?>
					<span class="lenvy-pdp-story__cred"><?php echo esc_html($s['cred']); ?></span>
				<?php endif; ?>
			</div>

			<div class="lenvy-pdp-story__copy">
				<?php if (!empty($s['eyebrow'])): ?>
					<span class="lenvy-pdp__eyebrow"><?php echo esc_html($s['eyebrow']); ?></span>
				<?php endif; ?>
				<h2 class="lenvy-pdp__display-l"><?php echo esc_html($s['title']); ?></h2>
				<?php foreach ($s['paragraphs'] as $para): ?>
					<p><?php echo esc_html($para); ?></p>
				<?php endforeach; ?>

				<?php if (!empty($s['meta'])): ?>
					<div class="lenvy-pdp-story__meta">
						<?php foreach ($s['meta'] as $m): ?>
							<div>
								<span class="k"><?php echo esc_html($m['k']); ?></span>
								<span class="v"><?php echo esc_html($m['v']); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

		</div>
	</div>
</section>
