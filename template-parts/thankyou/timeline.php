<?php
/**
 * Thank-you — simplified order-status timeline.
 *
 * 3 stages, no specific time slots — those would require a real shipping
 * API. Maps loosely to WooCommerce statuses (placed → processing →
 * completed). State is driven by the placeholder; replace with `$order->
 * get_status()` reads in the real implementation.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$order    = (array) ($args['order'] ?? []);
$expected = (string) ($order['expected']  ?? '');
$placed   = (string) ($order['placed_at'] ?? '');
$pay      = (string) ($order['pay_method'] ?? '');

$stages = [
	[
		'state' => 'done',
		'title' => __('Bestelling geplaatst', 'lenvy'),
		'when'  => $placed,
		'desc'  => $pay
			? sprintf(
				/* translators: %s: payment method */
				__('Betaling via %s bevestigd.', 'lenvy'),
				$pay
			)
			: __('Betaling bevestigd.', 'lenvy'),
	],
	[
		'state' => 'now',
		'title' => __('In behandeling', 'lenvy'),
		'when'  => __('Nu', 'lenvy'),
		'desc'  => __('We verpakken je bestelling met de hand.', 'lenvy'),
	],
	[
		'state' => 'pending',
		'title' => __('Bezorgd', 'lenvy'),
		'when'  => $expected
			? sprintf(
				/* translators: %s: expected delivery e.g. "morgen" */
				__('Verwacht %s', 'lenvy'),
				$expected
			)
			: '',
		'desc'  => __('Je ontvangt een track & trace per e-mail zodra je pakket onderweg is.', 'lenvy'),
	],
];
?>

<section class="lenvy-thankyou__card">
	<h2 class="lenvy-thankyou__card-title"><?php esc_html_e('Volg je bestelling', 'lenvy'); ?></h2>
	<p class="lenvy-thankyou__card-sub">
		<?php esc_html_e('We werken deze status bij zodra je pakket de volgende stap bereikt.', 'lenvy'); ?>
	</p>

	<ol class="lenvy-thankyou__timeline">
		<?php foreach ($stages as $s): ?>
			<li class="lenvy-thankyou__tl-step lenvy-thankyou__tl-step--<?php echo esc_attr($s['state']); ?>">
				<span class="lenvy-thankyou__tl-dot" aria-hidden="true">
					<?php if ($s['state'] === 'done' || $s['state'] === 'now'): ?>
						<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="20 6 9 17 4 12"/>
						</svg>
					<?php endif; ?>
				</span>
				<div class="lenvy-thankyou__tl-body">
					<div class="lenvy-thankyou__tl-title">
						<span><?php echo esc_html($s['title']); ?></span>
						<?php if (!empty($s['when'])): ?>
							<span class="lenvy-thankyou__tl-when"><?php echo esc_html($s['when']); ?></span>
						<?php endif; ?>
					</div>
					<?php if (!empty($s['desc'])): ?>
						<p class="lenvy-thankyou__tl-desc"><?php echo esc_html($s['desc']); ?></p>
					<?php endif; ?>
				</div>
			</li>
		<?php endforeach; ?>
	</ol>
</section>
