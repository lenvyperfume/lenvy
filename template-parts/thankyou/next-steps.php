<?php
/**
 * Thank-you — single "what's next" CTA card.
 *
 * Account creation only — the design's fragrance-quiz card was dropped
 * (the quiz doesn't exist yet), and the action buttons (PDF invoice,
 * resend confirmation) were dropped because they need backend support
 * we don't have today.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();
?>

<h2 class="lenvy-thankyou__next-heading"><?php esc_html_e('Tijdens het wachten', 'lenvy'); ?></h2>

<div class="lenvy-thankyou__next-grid">
	<a href="#" class="lenvy-thankyou__next-card">
		<span class="lenvy-thankyou__next-ico" aria-hidden="true">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
				<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
				<circle cx="12" cy="7" r="4"/>
			</svg>
		</span>
		<h4><?php esc_html_e('Maak een account aan', 'lenvy'); ?></h4>
		<p><?php esc_html_e('Sla je adres op en bekijk eerdere bestellingen. Eenmalig instellen, altijd snel afrekenen.', 'lenvy'); ?></p>
		<span class="lenvy-thankyou__next-arr">
			<?php esc_html_e('Account aanmaken', 'lenvy'); ?>
			<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
				<path d="M5 12h14M13 6l6 6-6 6"/>
			</svg>
		</span>
	</a>
</div>
