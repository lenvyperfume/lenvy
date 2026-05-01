<?php
/**
 * Thank-you — confirmation hero (animated check + heading + order number).
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$order = (array) ($args['order'] ?? []);
$first = (string) ($order['first_name'] ?? '');
$email = (string) ($order['email']      ?? '');
$num   = (string) ($order['number']     ?? '');
?>

<section class="lenvy-thankyou__hero">
	<div class="lenvy-container">
		<div class="lenvy-thankyou__check" aria-hidden="true">
			<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
				<polyline points="20 6 9 17 4 12"/>
			</svg>
		</div>

		<span class="lenvy-checkout__eyebrow lenvy-thankyou__eyebrow">
			<?php esc_html_e('Bestelling bevestigd', 'lenvy'); ?>
		</span>

		<h1 class="lenvy-thankyou__title">
			<?php
			if ($first) {
				/* translators: %s: customer first name */
				printf(
					wp_kses(
						__('Bedankt, %s — <em>je geur is onderweg.</em>', 'lenvy'),
						['em' => []]
					),
					esc_html($first)
				);
			} else {
				echo wp_kses(
					__('Bedankt — <em>je geur is onderweg.</em>', 'lenvy'),
					['em' => []]
				);
			}
			?>
		</h1>

		<p class="lenvy-thankyou__lede">
			<?php
			/* translators: %s: customer email */
			printf(
				wp_kses(
					__('We hebben je bestelling ontvangen en sturen je een bevestiging naar <strong>%s</strong>. We pakken je parfum vandaag nog met de hand in.', 'lenvy'),
					['strong' => []]
				),
				esc_html($email)
			);
			?>
		</p>

		<?php if ($num): ?>
			<div class="lenvy-thankyou__order-num">
				<span><?php esc_html_e('Bestelnummer', 'lenvy'); ?></span>
				<span data-order-num><?php echo esc_html($num); ?></span>
				<button type="button" data-copy-order-num title="<?php esc_attr_e('Kopieer', 'lenvy'); ?>" aria-label="<?php esc_attr_e('Kopieer bestelnummer', 'lenvy'); ?>">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
						<rect x="9" y="9" width="13" height="13" rx="1"/>
						<path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
					</svg>
				</button>
			</div>
		<?php endif; ?>
	</div>
</section>
