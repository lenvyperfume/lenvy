<?php
/**
 * PDP reviews section — summary + list.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$product = $args['product'] ?? null;
if (!$product || empty($product['reviews'])) {
	return;
}

$r = $product['reviews'];

/**
 * Render N filled stars + (5-N) empty stars (visual only).
 */
$stars = static function (int $n): string {
	$filled = str_repeat('★', max(0, min(5, $n)));
	$empty  = str_repeat('☆', 5 - strlen($filled) / 3); // ★ is 3 bytes UTF-8
	return $filled . $empty;
};
?>

<section class="lenvy-block">
	<div class="lenvy-container">

		<header class="lenvy-pdp__section-head">
			<div>
				<?php if (!empty($r['eyebrow'])): ?>
					<span class="lenvy-pdp__eyebrow"><?php echo esc_html($r['eyebrow']); ?></span>
				<?php endif; ?>
				<h2 class="lenvy-pdp__display-m"><?php echo esc_html($r['title']); ?></h2>
			</div>
			<a href="#" class="lenvy-pdp__section-link">
				<?php esc_html_e('Schrijf een recensie →', 'lenvy'); ?>
			</a>
		</header>

		<div class="lenvy-pdp-reviews">

			<aside class="lenvy-pdp-reviews__summary">
				<div class="lenvy-pdp-reviews__score"><?php echo esc_html($r['score']); ?></div>
				<div class="lenvy-pdp-reviews__stars" aria-hidden="true">★★★★★</div>
				<div class="lenvy-pdp-reviews__count">
					<?php echo esc_html(sprintf(__('Op basis van %d recensies', 'lenvy'), (int) $r['count'])); ?>
				</div>
				<div class="lenvy-pdp-reviews__bars">
					<?php foreach ($r['breakdown'] as $row): ?>
						<div class="lenvy-pdp-rbar">
							<span><?php echo esc_html($row['stars']); ?></span>
							<div class="bar"><i style="width:<?php echo esc_attr((int) $row['pct']); ?>%"></i></div>
							<span class="n"><?php echo esc_html((int) $row['count']); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			</aside>

			<div class="lenvy-pdp-reviews__list">
				<?php foreach ($r['items'] as $item): ?>
					<article class="lenvy-pdp-review">
						<header class="lenvy-pdp-review__head">
							<div class="lenvy-pdp-review__avatar" aria-hidden="true"><?php echo esc_html($item['initials']); ?></div>
							<div>
								<div class="lenvy-pdp-review__name">
									<?php echo esc_html($item['name']); ?>
									<span class="lenvy-pdp-review__verified"><?php esc_html_e('Geverifieerd', 'lenvy'); ?></span>
								</div>
								<div class="lenvy-pdp-review__meta"><?php echo esc_html($item['meta']); ?></div>
							</div>
						</header>
						<div class="lenvy-pdp-review__stars" aria-label="<?php echo esc_attr(sprintf(__('%d van 5 sterren', 'lenvy'), (int) $item['stars'])); ?>"><?php echo esc_html($stars((int) $item['stars'])); ?></div>
						<h5><?php echo esc_html($item['title']); ?></h5>
						<p><?php echo esc_html($item['body']); ?></p>
						<?php if (!empty($item['foot'])): ?>
							<div class="lenvy-pdp-review__foot">
								<?php foreach ($item['foot'] as $foot): ?>
									<span><?php echo esc_html($foot); ?></span>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>

				<div class="lenvy-pdp-reviews__more">
					<button type="button">
						<?php echo esc_html(sprintf(__('Bekijk alle %d recensies →', 'lenvy'), (int) $r['count'])); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</section>
