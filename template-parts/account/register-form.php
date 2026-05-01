<?php
/**
 * Account — register panel.
 *
 * Fields: first name, last name, email, password.
 *
 * First/last name are required by our `woocommerce_register_post`
 * validator in inc/woocommerce.php — registration fails without them —
 * so they live on the register form instead of being deferred to
 * checkout. Field names use `billing_first_name` / `billing_last_name`
 * to match the `woocommerce_created_customer` hook that persists them
 * to user meta.
 *
 * Args:
 *   - wc_mode  (bool)   When true, renders a real <form> with WC nonces +
 *                       submit handler. When false, renders the same
 *                       markup as a non-functional placeholder demo.
 *   - redirect (string) Post-register redirect (only used in wc_mode).
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$wc_mode  = (bool)   ($args['wc_mode']  ?? false);
$redirect = (string) ($args['redirect'] ?? '');

$form_attrs = $wc_mode
	? ' class="lenvy-account__form woocommerce-form woocommerce-form-register register" method="post"'
	: ' class="lenvy-account__form" novalidate';
?>

<section class="lenvy-account__panel" aria-labelledby="lenvy-reg-h">
	<h2 id="lenvy-reg-h" class="lenvy-account__panel-title"><?php esc_html_e('Registreren', 'lenvy'); ?></h2>

	<?php if ($wc_mode) {
		do_action('woocommerce_register_form_start');
	} ?>

	<form<?php echo $form_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — static string ?>>

		<div class="lenvy-account__fld-row">
			<div class="lenvy-account__fld">
				<label for="lenvy-reg-fname"><?php esc_html_e('Voornaam', 'lenvy'); ?></label>
				<input
					id="lenvy-reg-fname"
					type="text"
					<?php echo $wc_mode ? 'name="billing_first_name"' : ''; ?>
					autocomplete="given-name"
					<?php if ($wc_mode): ?>
						value="<?php echo esc_attr(wp_unslash($_POST['billing_first_name'] ?? '')); // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>"
					<?php endif; ?>
					<?php echo $wc_mode ? 'required' : ''; ?>
				/>
			</div>

			<div class="lenvy-account__fld">
				<label for="lenvy-reg-lname"><?php esc_html_e('Achternaam', 'lenvy'); ?></label>
				<input
					id="lenvy-reg-lname"
					type="text"
					<?php echo $wc_mode ? 'name="billing_last_name"' : ''; ?>
					autocomplete="family-name"
					<?php if ($wc_mode): ?>
						value="<?php echo esc_attr(wp_unslash($_POST['billing_last_name'] ?? '')); // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>"
					<?php endif; ?>
					<?php echo $wc_mode ? 'required' : ''; ?>
				/>
			</div>
		</div>

		<div class="lenvy-account__fld">
			<label for="lenvy-reg-email"><?php esc_html_e('E-mailadres', 'lenvy'); ?></label>
			<input
				id="lenvy-reg-email"
				type="email"
				<?php echo $wc_mode ? 'name="email"' : ''; ?>
				autocomplete="email"
				<?php if ($wc_mode): ?>
					value="<?php echo esc_attr(wp_unslash($_POST['email'] ?? '')); // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>"
				<?php endif; ?>
				<?php echo $wc_mode ? 'required' : ''; ?>
			/>
		</div>

		<div class="lenvy-account__fld lenvy-account__fld--pw">
			<label for="lenvy-reg-password"><?php esc_html_e('Wachtwoord', 'lenvy'); ?></label>
			<input
				id="lenvy-reg-password"
				type="password"
				<?php echo $wc_mode ? 'name="password"' : ''; ?>
				autocomplete="new-password"
				<?php echo $wc_mode ? 'required' : ''; ?>
			/>
			<button
				type="button"
				class="lenvy-account__reveal"
				data-pw-toggle="lenvy-reg-password"
				aria-label="<?php esc_attr_e('Toon wachtwoord', 'lenvy'); ?>"
			>
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
					<circle cx="12" cy="12" r="3"/>
				</svg>
			</button>
		</div>

		<?php if ($wc_mode) {
			// WC privacy disclaimer (editable via WP admin → Privacy + WC
			// settings). Renders `<div class="woocommerce-privacy-policy-text">`
			// as a flex child — gets the same 1rem gap above and below as
			// every other block in the form.
			do_action('woocommerce_register_form');
		} ?>

		<?php if ($wc_mode) {
			wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce');
		} ?>

		<?php if ($wc_mode && $redirect): ?>
			<input type="hidden" name="redirect" value="<?php echo esc_url($redirect); ?>" />
		<?php endif; ?>

		<div class="lenvy-account__actions">
			<button class="lenvy-account__submit" type="submit" <?php echo $wc_mode ? 'name="register" value="' . esc_attr__('Registreren', 'lenvy') . '"' : ''; ?>>
				<?php esc_html_e('Registreren', 'lenvy'); ?>
				<svg width="14" height="10" viewBox="0 0 14 10" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
					<path d="M1 5h12m0 0L9 1m4 4L9 9"/>
				</svg>
			</button>
		</div>

	</form>

	<?php if ($wc_mode) {
		do_action('woocommerce_register_form_end');
	} ?>
</section>
