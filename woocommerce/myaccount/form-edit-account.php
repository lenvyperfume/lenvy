<?php
/**
 * My Account — Edit account details.
 *
 * Overrides woocommerce/myaccount/form-edit-account.php
 *
 * Same field treatment as the rest of the account / checkout flow.
 * Field IDs and `name` attributes match WC's expected handler in
 * `WC_Form_Handler::save_account_details()` so the WC nonce + save
 * action keep working untouched.
 *
 * @package Lenvy
 *
 * @var WP_User $user Current user, passed in by WC.
 */

defined('ABSPATH') || exit();

do_action('woocommerce_before_edit_account_form');
?>

<div class="lenvy-account-edit">

	<header class="lenvy-account-edit__head">
		<h1 class="lenvy-account-edit__title"><?php esc_html_e('Accountgegevens', 'lenvy'); ?></h1>
		<p class="lenvy-account-edit__lede">
			<?php esc_html_e('Werk je naam, e-mailadres en wachtwoord bij.', 'lenvy'); ?>
		</p>
	</header>

	<form class="lenvy-account__form lenvy-account__form--wide" action="" method="post" <?php do_action('woocommerce_edit_account_form_tag'); ?>>

		<?php do_action('woocommerce_edit_account_form_start'); ?>

		<section class="lenvy-account-edit__section">
			<h2 class="lenvy-account-edit__section-title"><?php esc_html_e('Persoonlijke gegevens', 'lenvy'); ?></h2>

			<div class="lenvy-account__fld-row">
				<div class="lenvy-account__fld<?php echo $user->first_name ? ' has-value' : ''; ?>">
					<label for="account_first_name"><?php esc_html_e('Voornaam', 'lenvy'); ?></label>
					<input
						id="account_first_name"
						name="account_first_name"
						type="text"
						autocomplete="given-name"
						value="<?php echo esc_attr($user->first_name); ?>"
						required
					/>
				</div>
				<div class="lenvy-account__fld<?php echo $user->last_name ? ' has-value' : ''; ?>">
					<label for="account_last_name"><?php esc_html_e('Achternaam', 'lenvy'); ?></label>
					<input
						id="account_last_name"
						name="account_last_name"
						type="text"
						autocomplete="family-name"
						value="<?php echo esc_attr($user->last_name); ?>"
						required
					/>
				</div>
			</div>

			<div class="lenvy-account__fld<?php echo $user->display_name ? ' has-value' : ''; ?>">
				<label for="account_display_name"><?php esc_html_e('Weergavenaam', 'lenvy'); ?></label>
				<input
					id="account_display_name"
					name="account_display_name"
					type="text"
					value="<?php echo esc_attr($user->display_name); ?>"
					required
				/>
			</div>

			<div class="lenvy-account__fld<?php echo $user->user_email ? ' has-value' : ''; ?>">
				<label for="account_email"><?php esc_html_e('E-mailadres', 'lenvy'); ?></label>
				<input
					id="account_email"
					name="account_email"
					type="email"
					autocomplete="email"
					value="<?php echo esc_attr($user->user_email); ?>"
					required
				/>
			</div>
		</section>

		<?php
		/** @since 8.7.0 */
		do_action('woocommerce_edit_account_form_fields');
		?>

		<section class="lenvy-account-edit__section">
			<h2 class="lenvy-account-edit__section-title"><?php esc_html_e('Wachtwoord wijzigen', 'lenvy'); ?></h2>
			<p class="lenvy-account-edit__section-sub">
				<?php esc_html_e('Laat leeg om je huidige wachtwoord te behouden.', 'lenvy'); ?>
			</p>

			<div class="lenvy-account__fld lenvy-account__fld--pw">
				<label for="password_current"><?php esc_html_e('Huidig wachtwoord', 'lenvy'); ?></label>
				<input id="password_current" name="password_current" type="password" autocomplete="current-password" />
				<button type="button" class="lenvy-account__reveal" data-pw-toggle="password_current" aria-label="<?php esc_attr_e('Toon wachtwoord', 'lenvy'); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
						<circle cx="12" cy="12" r="3"/>
					</svg>
				</button>
			</div>

			<div class="lenvy-account__fld lenvy-account__fld--pw">
				<label for="password_1"><?php esc_html_e('Nieuw wachtwoord', 'lenvy'); ?></label>
				<input id="password_1" name="password_1" type="password" autocomplete="new-password" />
				<button type="button" class="lenvy-account__reveal" data-pw-toggle="password_1" aria-label="<?php esc_attr_e('Toon wachtwoord', 'lenvy'); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
						<circle cx="12" cy="12" r="3"/>
					</svg>
				</button>
			</div>

			<div class="lenvy-account__fld lenvy-account__fld--pw">
				<label for="password_2"><?php esc_html_e('Bevestig nieuw wachtwoord', 'lenvy'); ?></label>
				<input id="password_2" name="password_2" type="password" autocomplete="new-password" />
				<button type="button" class="lenvy-account__reveal" data-pw-toggle="password_2" aria-label="<?php esc_attr_e('Toon wachtwoord', 'lenvy'); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
						<circle cx="12" cy="12" r="3"/>
					</svg>
				</button>
			</div>
		</section>

		<?php
		/** @since 2.6.0 */
		do_action('woocommerce_edit_account_form');
		?>

		<div class="lenvy-account__actions">
			<?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
			<input type="hidden" name="action" value="save_account_details" />
			<button type="submit" class="lenvy-account__submit" name="save_account_details" value="<?php esc_attr_e('Wijzigingen opslaan', 'lenvy'); ?>">
				<?php esc_html_e('Wijzigingen opslaan', 'lenvy'); ?>
				<svg width="14" height="10" viewBox="0 0 14 10" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
					<path d="M1 5h12m0 0L9 1m4 4L9 9"/>
				</svg>
			</button>
		</div>

		<?php do_action('woocommerce_edit_account_form_end'); ?>
	</form>

</div>

<?php do_action('woocommerce_after_edit_account_form'); ?>
