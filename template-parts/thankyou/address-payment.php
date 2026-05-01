<?php
/**
 * Thank-you — shipping address + payment method recap.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$ship  = (array) ($args['shipping_address'] ?? []);
$order = (array) ($args['order']            ?? []);

$pay_method = (string) ($order['pay_method'] ?? '');
$pay_bank   = (string) ($order['pay_bank']   ?? '');
$pay_total  = (string) ($order['pay_total']  ?? '');
?>

<section class="lenvy-thankyou__card">
	<h2 class="lenvy-thankyou__card-title"><?php esc_html_e('Bezorging & betaling', 'lenvy'); ?></h2>
	<p class="lenvy-thankyou__card-sub">
		<?php esc_html_e('Klopt iets niet? Neem zo snel mogelijk contact op met onze klantenservice.', 'lenvy'); ?>
	</p>

	<div class="lenvy-thankyou__addr-grid">
		<div class="lenvy-thankyou__addr-col">
			<h3><?php esc_html_e('Bezorgadres', 'lenvy'); ?></h3>
			<address class="lenvy-thankyou__address">
				<?php if (!empty($ship['name'])): ?>
					<span class="lenvy-thankyou__addr-name"><?php echo esc_html($ship['name']); ?></span><br>
				<?php endif; ?>
				<?php if (!empty($ship['line1'])): ?>
					<?php echo esc_html($ship['line1']); ?><br>
				<?php endif; ?>
				<?php if (!empty($ship['zip']) || !empty($ship['city'])): ?>
					<?php echo esc_html(trim(($ship['zip'] ?? '') . ' ' . ($ship['city'] ?? ''))); ?><br>
				<?php endif; ?>
				<?php if (!empty($ship['country'])): ?>
					<?php echo esc_html($ship['country']); ?>
				<?php endif; ?>
			</address>
		</div>

		<div class="lenvy-thankyou__addr-col">
			<h3><?php esc_html_e('Betaalmethode', 'lenvy'); ?></h3>
			<?php if ($pay_method): ?>
				<div class="lenvy-thankyou__pay-row">
					<span class="lenvy-checkout__pm-logo lenvy-checkout__pm-logo--ideal"><?php echo esc_html($pay_method); ?></span>
					<?php if ($pay_bank): ?>
						<span><?php echo esc_html($pay_bank); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ($pay_total): ?>
				<div class="lenvy-thankyou__pay-meta">
					<?php
					/* translators: %s: paid total */
					printf(esc_html__('Betaald · %s', 'lenvy'), esc_html($pay_total));
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
