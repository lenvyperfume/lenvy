<?php
/**
 * Checkout — payment methods (iDEAL with bank picker · creditcard).
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$banks = (array) ($args['banks'] ?? []);
?>

<section class="lenvy-checkout__section">
	<div class="lenvy-checkout__section-top">
		<h2 class="lenvy-checkout__section-title"><?php esc_html_e('Hoe wil je betalen?', 'lenvy'); ?></h2>
		<div class="lenvy-checkout__section-aside"><?php esc_html_e('Beveiligd · SSL versleuteld', 'lenvy'); ?></div>
	</div>

	<div class="lenvy-checkout__pay-methods" data-pay-methods>

		<label class="lenvy-checkout__pay-method is-active" data-pm="ideal">
			<input type="radio" name="lc-pm" value="ideal" checked />
			<div class="lenvy-checkout__pay-head">
				<span class="lenvy-checkout__pay-radio"></span>
				<span class="lenvy-checkout__pay-name">
					<?php esc_html_e('iDEAL', 'lenvy'); ?>
					<small><?php esc_html_e('Direct via je eigen bank', 'lenvy'); ?></small>
				</span>
				<span class="lenvy-checkout__pay-logo">
					<span class="lenvy-checkout__pm-logo lenvy-checkout__pm-logo--ideal">iDEAL</span>
				</span>
			</div>
			<div class="lenvy-checkout__pay-body">
				<div class="lenvy-checkout__pay-body-inner">
					<p class="lenvy-checkout__pay-hint">
						<?php esc_html_e('Selecteer je bank — je wordt doorgestuurd om de betaling te bevestigen.', 'lenvy'); ?>
					</p>
					<div class="lenvy-checkout__banks" data-banks>
						<?php foreach ($banks as $i => $b): ?>
							<button type="button" class="<?php echo $i === 0 ? 'is-on' : ''; ?>">
								<span class="lenvy-checkout__bank-mark"><?php echo esc_html($b['code']); ?></span>
								<?php echo esc_html($b['label']); ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</label>

		<label class="lenvy-checkout__pay-method" data-pm="card">
			<input type="radio" name="lc-pm" value="card" />
			<div class="lenvy-checkout__pay-head">
				<span class="lenvy-checkout__pay-radio"></span>
				<span class="lenvy-checkout__pay-name">
					<?php esc_html_e('Creditcard', 'lenvy'); ?>
					<small><?php esc_html_e('Visa · Mastercard', 'lenvy'); ?></small>
				</span>
				<span class="lenvy-checkout__pay-logo">
					<span class="lenvy-checkout__pm-logo lenvy-checkout__pm-logo--visa">VISA</span>
					<span class="lenvy-checkout__pm-logo lenvy-checkout__pm-logo--mc"></span>
				</span>
			</div>
			<div class="lenvy-checkout__pay-body">
				<div class="lenvy-checkout__pay-body-inner">
					<div class="lenvy-checkout__card-form">
						<div class="lenvy-checkout__fld">
							<label for="lc-cc-num"><?php esc_html_e('Kaartnummer', 'lenvy'); ?></label>
							<input id="lc-cc-num" placeholder="1234 5678 9012 3456" inputmode="numeric" autocomplete="cc-number" />
						</div>
						<div class="lenvy-checkout__fld">
							<label for="lc-cc-name"><?php esc_html_e('Naam op kaart', 'lenvy'); ?></label>
							<input id="lc-cc-name" placeholder="Emma de Vries" autocomplete="cc-name" />
						</div>
						<div class="lenvy-checkout__card-row">
							<div class="lenvy-checkout__fld">
								<label for="lc-cc-exp"><?php esc_html_e('Vervaldatum', 'lenvy'); ?></label>
								<input id="lc-cc-exp" placeholder="MM / JJ" inputmode="numeric" autocomplete="cc-exp" />
							</div>
							<div class="lenvy-checkout__fld">
								<label for="lc-cc-cvc"><?php esc_html_e('CVC', 'lenvy'); ?></label>
								<input id="lc-cc-cvc" placeholder="123" inputmode="numeric" maxlength="4" autocomplete="cc-csc" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</label>

	</div>

	<div class="lenvy-checkout__pay-notice">
		<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
			<rect x="3" y="11" width="18" height="11" rx="2"/>
			<path d="M7 11V7a5 5 0 0 1 10 0v4"/>
		</svg>
		<span>
			<?php
			/* translators: %s: payment processor name link */
			printf(
				wp_kses(
					__('Je betaalgegevens worden via een beveiligde verbinding (SSL) verstuurd. Lenvy slaat geen kaartgegevens op — betalingen worden verwerkt door %s.', 'lenvy'),
					['a' => ['href' => true]]
				),
				'<a href="#">Mollie</a>'
			);
			?>
		</span>
	</div>
</section>
