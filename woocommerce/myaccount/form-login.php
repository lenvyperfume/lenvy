<?php
/**
 * My Account — Login / Register form.
 *
 * Split two-column layout: Login (left) · Register (right).
 * All WooCommerce form hooks are preserved so plugins can inject fields.
 *
 * @package Lenvy
 *
 * @var string $redirect     URL to redirect to after login.
 * @var bool   $has_account  Whether the email already has a WC account.
 */

defined( 'ABSPATH' ) || exit();

// Redirect already-logged-in users to the account dashboard.
if ( is_user_logged_in() ) {
	wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
	exit();
}

// Ensure $redirect has a sane fallback (WC passes this via template data).
$redirect = $redirect ?? wc_get_account_endpoint_url( 'dashboard' );
?>

<div class="lenvy-account-auth py-12 md:py-20 px-4 sm:px-6">
	<div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16">

		<!-- ── Login ─────────────────────────────────────────────────────────── -->
		<div class="lenvy-account-auth__panel">

			<h2 class="font-serif italic text-2xl text-neutral-900 mb-8">
				<?php esc_html_e( 'Inloggen', 'lenvy' ); ?>
			</h2>

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<form
				class="woocommerce-form woocommerce-form-login login"
				method="post"
			>

				<div class="form-row mb-5">
					<label
						for="username"
						class="block text-xs font-medium uppercase tracking-widest text-neutral-500 mb-2"
					>
						<?php esc_html_e( 'E-mailadres', 'lenvy' ); ?>
						<span class="required text-neutral-400" aria-hidden="true">*</span>
					</label>
					<input
						type="text"
						class="woocommerce-Input woocommerce-Input--text input-text"
						name="username"
						id="username"
						autocomplete="username email"
						value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
						required
					/>
				</div>

				<div class="form-row mb-5">
					<label
						for="password"
						class="block text-xs font-medium uppercase tracking-widest text-neutral-500 mb-2"
					>
						<?php esc_html_e( 'Wachtwoord', 'lenvy' ); ?>
						<span class="required text-neutral-400" aria-hidden="true">*</span>
					</label>
					<input
						type="password"
						class="woocommerce-Input woocommerce-Input--text input-text"
						name="password"
						id="password"
						autocomplete="current-password"
						required
					/>
				</div>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<div class="flex items-center justify-between gap-4 mb-7">
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox flex items-center gap-2 text-sm text-neutral-600 cursor-pointer select-none">
						<input
							class="woocommerce-form__input woocommerce-form__input-checkbox"
							name="rememberme"
							type="checkbox"
							id="rememberme"
							value="forever"
						/>
						<?php esc_html_e( 'Onthoud mij', 'lenvy' ); ?>
					</label>
					<a
						href="<?php echo esc_url( wp_lostpassword_url() ); ?>"
						class="text-xs text-neutral-500 underline underline-offset-2 hover:text-neutral-900 transition-colors"
					>
						<?php esc_html_e( 'Wachtwoord vergeten?', 'lenvy' ); ?>
					</a>
				</div>

				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />

				<button
					type="submit"
					class="woocommerce-button button woocommerce-form-login__submit w-full bg-primary text-black text-xs font-medium uppercase tracking-widest py-4 transition-colors hover:bg-primary-hover cursor-pointer"
					name="login"
					value="<?php esc_attr_e( 'Inloggen', 'lenvy' ); ?>"
				>
					<?php esc_html_e( 'Inloggen', 'lenvy' ); ?>
				</button>

				<?php do_action( 'woocommerce_login_form_end' ); ?>

			</form>

		</div><!-- .lenvy-account-auth__panel (login) -->

		<!-- ── Register ──────────────────────────────────────────────────────── -->
		<div class="lenvy-account-auth__panel border-t border-neutral-100 pt-12 md:border-t-0 md:border-l md:pt-0 md:pl-16">

			<h2 class="font-serif italic text-2xl text-neutral-900 mb-8">
				<?php esc_html_e( 'Account aanmaken', 'lenvy' ); ?>
			</h2>

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<form
				method="post"
				class="woocommerce-form woocommerce-form-register register"
			>

				<div class="grid grid-cols-2 gap-4 mb-5">
					<div class="form-row">
						<label
							for="reg_billing_first_name"
							class="block text-xs font-medium uppercase tracking-widest text-neutral-500 mb-2"
						>
							<?php esc_html_e( 'Voornaam', 'lenvy' ); ?>
							<span class="required text-neutral-400" aria-hidden="true">*</span>
						</label>
						<input
							type="text"
							class="woocommerce-Input woocommerce-Input--text input-text"
							name="billing_first_name"
							id="reg_billing_first_name"
							autocomplete="given-name"
							value="<?php echo ( ! empty( $_POST['billing_first_name'] ) ) ? esc_attr( wp_unslash( $_POST['billing_first_name'] ) ) : ''; ?>"
							required
						/>
					</div>

					<div class="form-row">
						<label
							for="reg_billing_last_name"
							class="block text-xs font-medium uppercase tracking-widest text-neutral-500 mb-2"
						>
							<?php esc_html_e( 'Achternaam', 'lenvy' ); ?>
							<span class="required text-neutral-400" aria-hidden="true">*</span>
						</label>
						<input
							type="text"
							class="woocommerce-Input woocommerce-Input--text input-text"
							name="billing_last_name"
							id="reg_billing_last_name"
							autocomplete="family-name"
							value="<?php echo ( ! empty( $_POST['billing_last_name'] ) ) ? esc_attr( wp_unslash( $_POST['billing_last_name'] ) ) : ''; ?>"
							required
						/>
					</div>
				</div>

				<div class="form-row mb-5">
					<label
						for="reg_email"
						class="block text-xs font-medium uppercase tracking-widest text-neutral-500 mb-2"
					>
						<?php esc_html_e( 'E-mailadres', 'lenvy' ); ?>
						<span class="required text-neutral-400" aria-hidden="true">*</span>
					</label>
					<input
						type="email"
						class="woocommerce-Input woocommerce-Input--text input-text"
						name="email"
						id="reg_email"
						autocomplete="email"
						value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"
						required
					/>
				</div>

				<div class="form-row mb-5">
					<label
						for="reg_password"
						class="block text-xs font-medium uppercase tracking-widest text-neutral-500 mb-2"
					>
						<?php esc_html_e( 'Wachtwoord', 'lenvy' ); ?>
						<span class="required text-neutral-400" aria-hidden="true">*</span>
					</label>
					<input
						type="password"
						class="woocommerce-Input woocommerce-Input--text input-text"
						name="password"
						id="reg_password"
						autocomplete="new-password"
						required
					/>
				</div>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<input type="hidden" name="redirect" value="<?php echo esc_url( wc_get_checkout_url() ); ?>" />

				<button
					type="submit"
					class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit w-full bg-primary text-black text-xs font-medium uppercase tracking-widest py-4 transition-colors hover:bg-primary-hover cursor-pointer"
					name="register"
					value="<?php esc_attr_e( 'Account aanmaken', 'lenvy' ); ?>"
				>
					<?php esc_html_e( 'Account aanmaken', 'lenvy' ); ?>
				</button>

				<?php do_action( 'woocommerce_register_form_end' ); ?>

			</form>

		</div><!-- .lenvy-account-auth__panel (register) -->

	</div><!-- .max-w-4xl -->
</div><!-- .lenvy-account-auth -->
