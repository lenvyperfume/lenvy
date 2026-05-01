<?php
/**
 * Account — login panel.
 *
 * Args:
 *   - wc_mode  (bool)   When true, renders a real <form> with WC nonces +
 *                       submit handler. When false, renders the same
 *                       markup as a non-functional placeholder demo.
 *   - redirect (string) Login redirect URL (only used in wc_mode).
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$wc_mode  = (bool)   ($args['wc_mode']  ?? false);
$redirect = (string) ($args['redirect'] ?? '');

$form_attrs = $wc_mode
	? ' class="lenvy-account__form woocommerce-form woocommerce-form-login login" method="post"'
	: ' class="lenvy-account__form" novalidate';

$lost_url = $wc_mode ? wp_lostpassword_url() : '#';
?>

<section class="lenvy-account__panel" aria-labelledby="lenvy-login-h">
	<h2 id="lenvy-login-h" class="lenvy-account__panel-title"><?php esc_html_e('Inloggen', 'lenvy'); ?></h2>

	<?php if ($wc_mode) {
		do_action('woocommerce_login_form_start');
	} ?>

	<form<?php echo $form_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — static string ?>>

		<div class="lenvy-account__fld">
			<label for="lenvy-login-username">
				<?php esc_html_e('Gebruikersnaam of e-mailadres', 'lenvy'); ?>
			</label>
			<input
				id="lenvy-login-username"
				type="text"
				<?php echo $wc_mode ? 'name="username"' : ''; ?>
				autocomplete="username email"
				<?php if ($wc_mode): ?>
					value="<?php echo esc_attr(wp_unslash($_POST['username'] ?? '')); // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>"
				<?php endif; ?>
				<?php echo $wc_mode ? 'required' : ''; ?>
			/>
		</div>

		<div class="lenvy-account__fld lenvy-account__fld--pw">
			<label for="lenvy-login-password">
				<?php esc_html_e('Wachtwoord', 'lenvy'); ?>
				<a href="<?php echo esc_url($lost_url); ?>" class="lenvy-account__forgot">
					<?php esc_html_e('Wachtwoord vergeten?', 'lenvy'); ?>
				</a>
			</label>
			<input
				id="lenvy-login-password"
				type="password"
				<?php echo $wc_mode ? 'name="password"' : ''; ?>
				autocomplete="current-password"
				<?php echo $wc_mode ? 'required' : ''; ?>
			/>
			<button
				type="button"
				class="lenvy-account__reveal"
				data-pw-toggle="lenvy-login-password"
				aria-label="<?php esc_attr_e('Toon wachtwoord', 'lenvy'); ?>"
			>
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
					<circle cx="12" cy="12" r="3"/>
				</svg>
			</button>
		</div>

		<?php if ($wc_mode) {
			do_action('woocommerce_login_form');
			wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce');
		} ?>

		<?php if ($wc_mode && $redirect): ?>
			<input type="hidden" name="redirect" value="<?php echo esc_url($redirect); ?>" />
		<?php endif; ?>

		<div class="lenvy-account__actions">
			<button class="lenvy-account__submit" type="submit" <?php echo $wc_mode ? 'name="login" value="' . esc_attr__('Inloggen', 'lenvy') . '"' : ''; ?>>
				<?php esc_html_e('Inloggen', 'lenvy'); ?>
				<svg width="14" height="10" viewBox="0 0 14 10" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
					<path d="M1 5h12m0 0L9 1m4 4L9 9"/>
				</svg>
			</button>
			<label class="lenvy-account__checkbox">
				<input type="checkbox" <?php echo $wc_mode ? 'name="rememberme" value="forever"' : ''; ?> />
				<span class="lenvy-account__checkbox-box">
					<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<polyline points="20 6 9 17 4 12"/>
					</svg>
				</span>
				<span class="lenvy-account__checkbox-lbl"><?php esc_html_e('Onthoud mij', 'lenvy'); ?></span>
			</label>
		</div>

	</form>

	<?php if ($wc_mode) {
		do_action('woocommerce_login_form_end');
	} ?>
</section>
