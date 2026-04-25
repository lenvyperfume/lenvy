<?php
/**
 * PDP scent pyramid section.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$product = $args['product'] ?? null;
if (!$product) {
	return;
}

$p = $product['pyramid'];

$tier_icons = [
	'top'   => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>',
	'heart' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.5" aria-hidden="true"><path d="M12 21c-4-3.4-8-6.7-8-11a5 5 0 0 1 8-4 5 5 0 0 1 8 4c0 4.3-4 7.6-8 11z"/></svg>',
	'base'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 21h18"/><path d="M6 21V10l6-4 6 4v11"/><path d="M10 21v-6h4v6"/></svg>',
];
?>

<section class="lenvy-block lenvy-block--alt">
	<div class="lenvy-container">

		<header class="lenvy-pdp__section-head">
			<div>
				<span class="lenvy-pdp__eyebrow"><?php esc_html_e('Geurpiramide', 'lenvy'); ?></span>
				<h2 class="lenvy-pdp__display-m"><?php
					/* translators: pyramid intro headline */
					echo esc_html(sprintf(__('Zo ontvouwt %s zich', 'lenvy'), $product['name']));
				?></h2>
			</div>
		</header>

		<div class="lenvy-pyramid-wrap">

			<div class="lenvy-pyramid__lede">
				<?php foreach ($p['lede'] as $para): ?>
					<p><?php echo esc_html($para); ?></p>
				<?php endforeach; ?>
				<?php if (!empty($p['quote'])): ?>
					<blockquote class="lenvy-pyramid__q">
						<?php echo esc_html($p['quote']['text']); ?>
						<cite><?php echo esc_html($p['quote']['cite']); ?></cite>
					</blockquote>
				<?php endif; ?>
			</div>

			<div class="lenvy-pyramid">
				<?php foreach ($p['rows'] as $row):
					$tier = (string) $row['tier'];
					$icon = $tier_icons[$tier] ?? '';
				?>
					<div class="lenvy-pyramid__row lenvy-pyramid__row--<?php echo esc_attr($tier); ?>">
						<span class="lenvy-pyramid__ico" aria-hidden="true">
							<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — static SVG ?>
						</span>
						<div>
							<span class="lenvy-pyramid__lbl"><?php echo esc_html($row['eyebrow']); ?></span>
							<h4 class="lenvy-pyramid__title"><?php echo esc_html($row['title']); ?></h4>
							<div class="lenvy-pyramid__notes">
								<?php foreach ($row['notes'] as $note): ?>
									<span><?php echo esc_html($note); ?></span>
								<?php endforeach; ?>
							</div>
							<p class="lenvy-pyramid__desc"><?php echo esc_html($row['desc']); ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

		</div>
	</div>
</section>
