<?php
/**
 * Checkout — 3-step indicator.
 *
 * Args:
 *   - current  (int)  1|2|3 — which step is active. Steps before `current`
 *                     render as completed clickable links; the active step
 *                     is a span; future steps stay as spans.
 *   - complete (bool) When true, the active step also renders the green
 *                     "done" treatment (used on the thank-you page where
 *                     all 3 steps are finished but step 3 is still the
 *                     current one).
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$current  = (int)  ($args['current']  ?? 2);
$current  = max(1, min(3, $current));
$complete = (bool) ($args['complete'] ?? false);

$cart_url     = function_exists('lenvy_placeholder_cart_url')     ? lenvy_placeholder_cart_url()     : '#';
$checkout_url = function_exists('lenvy_placeholder_checkout_url') ? lenvy_placeholder_checkout_url() : '#';

$steps = [
	['n' => 1, 'label' => __('Winkelwagen', 'lenvy'), 'url' => $cart_url],
	['n' => 2, 'label' => __('Afrekenen', 'lenvy'),   'url' => $checkout_url],
	['n' => 3, 'label' => __('Bevestiging', 'lenvy'), 'url' => ''],
];
?>

<div class="lenvy-checkout__steps">
	<div class="lenvy-container">
		<div class="lenvy-checkout__steps-inner">
			<?php foreach ($steps as $s):
				if ($s['n'] < $current) {
					$state = 'done';
				} elseif ($s['n'] === $current) {
					$state = $complete ? 'complete' : 'active';
				} else {
					$state = 'pending';
				}

				$show_check = in_array($state, ['done', 'complete'], true);
				// On the thank-you page (complete=true) the cart and checkout
				// have already been cleared — sending the user back would just
				// land on an empty cart. Render every step as a non-clickable
				// span in that mode.
				$is_link    = $s['url'] && $state === 'done' && !$complete;
				$tag        = $is_link ? 'a' : 'span';
				$attrs      = $is_link ? sprintf(' href="%s"', esc_url($s['url'])) : '';
				if ($state === 'active' || $state === 'complete') {
					$attrs .= ' aria-current="step"';
				}
			?>
				<<?php echo $tag; ?> class="lenvy-checkout__step lenvy-checkout__step--<?php echo esc_attr($state); ?>"<?php echo $attrs; ?>>
					<span class="lenvy-checkout__step-num">
						<?php if ($show_check): ?>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<polyline points="20 6 9 17 4 12"/>
							</svg>
						<?php else: ?>
							<?php echo esc_html((string) $s['n']); ?>
						<?php endif; ?>
					</span>
					<span class="lenvy-checkout__step-label"><?php echo esc_html($s['label']); ?></span>
				</<?php echo $tag; ?>>
			<?php endforeach; ?>
		</div>
	</div>
</div>
