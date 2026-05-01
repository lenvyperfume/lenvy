<?php
/**
 * Checkout — contact section (email + newsletter opt-in).
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$customer = (array) ($args['customer'] ?? []);
$email    = (string) ($customer['email'] ?? '');
?>

<section class="lenvy-checkout__section">
	<div class="lenvy-checkout__section-top">
		<h2 class="lenvy-checkout__section-title"><?php esc_html_e('Hoe kunnen we je bereiken?', 'lenvy'); ?></h2>
		<div class="lenvy-checkout__section-aside">
			<?php $login_url = function_exists('lenvy_account_url') ? lenvy_account_url() : home_url('/mijn-account/'); ?>
			<?php esc_html_e('Heb je een account?', 'lenvy'); ?>
			<a href="<?php echo esc_url($login_url); ?>"><?php esc_html_e('Inloggen', 'lenvy'); ?></a>
		</div>
	</div>

	<div class="lenvy-checkout__fld-grid">
		<div class="lenvy-checkout__fld lenvy-checkout__fld--full<?php echo $email ? ' has-value' : ''; ?>">
			<label for="lc-email"><?php esc_html_e('E-mailadres', 'lenvy'); ?></label>
			<input id="lc-email" type="email" value="<?php echo esc_attr($email); ?>" autocomplete="email" />
		</div>

		<div class="lenvy-checkout__fld lenvy-checkout__fld--full">
			<label class="lenvy-checkout__checkbox" style="padding: 0; margin-top: -4px;">
				<input type="checkbox" />
				<span class="lenvy-checkout__checkbox-box">
					<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<polyline points="20 6 9 17 4 12"/>
					</svg>
				</span>
				<span class="lenvy-checkout__checkbox-lbl">
					<?php esc_html_e('Houd me op de hoogte van nieuwe geuren en aanbiedingen', 'lenvy'); ?>
				</span>
			</label>
		</div>
	</div>
</section>
