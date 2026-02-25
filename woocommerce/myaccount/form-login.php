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

<div class="lenvy-account-auth py-12 md:py-20">
	<div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16">

		<!-- ── Login ─────────────────────────────────────────────────────────── -->
		<div class="lenvy-account-auth__panel">

			<h2 class="font-serif italic text-2xl text-neutral-900 mb-8">
				<?php esc_html_e( 'Log in', 'lenvy' ); ?>
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
						<?php esc_html_e( 'Email address', 'lenvy' ); ?>
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
						<?php esc_html_e( 'Password', 'lenvy' ); ?>
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
						<?php esc_html_e( 'Remember me', 'lenvy' ); ?>
					</label>
					<a
						href="<?php echo esc_url( wp_lostpassword_url() ); ?>"
						class="text-xs text-neutral-500 underline underline-offset-2 hover:text-neutral-900 transition-colors"
					>
						<?php esc_html_e( 'Forgot password?', 'lenvy' ); ?>
					</a>
				</div>

				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />

				<button
					type="submit"
					class="woocommerce-button button woocommerce-form-login__submit w-full bg-black text-white text-xs font-medium uppercase tracking-widest py-4 transition-opacity hover:opacity-70 cursor-pointer"
					name="login"
					value="<?php esc_attr_e( 'Log in', 'lenvy' ); ?>"
				>
					<?php esc_html_e( 'Log in', 'lenvy' ); ?>
				</button>

				<?php do_action( 'woocommerce_login_form_end' ); ?>

			</form>

		</div><!-- .lenvy-account-auth__panel (login) -->

		<!-- ── Register ──────────────────────────────────────────────────────── -->
		<div class="lenvy-account-auth__panel border-t border-neutral-100 pt-12 md:border-t-0 md:border-l md:pt-0 md:pl-16">

			<h2 class="font-serif italic text-2xl text-neutral-900 mb-8">
				<?php esc_html_e( 'Create account', 'lenvy' ); ?>
			</h2>

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<form
				method="post"
				class="woocommerce-form woocommerce-form-register register"
			>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
					<div class="form-row mb-5">
						<label
							for="reg_username"
							class="block text-xs font-medium uppercase tracking-widest text-neutral-500 mb-2"
						>
							<?php esc_html_e( 'Username', 'lenvy' ); ?>
							<span class="required text-neutral-400" aria-hidden="true">*</span>
						</label>
						<input
							type="text"
							class="woocommerce-Input woocommerce-Input--text input-text"
							name="username"
							id="reg_username"
							autocomplete="username"
							value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
							required
						/>
					</div>
				<?php endif; ?>

				<div class="form-row mb-5">
					<label
						for="reg_email"
						class="block text-xs font-medium uppercase tracking-widest text-neutral-500 mb-2"
					>
						<?php esc_html_e( 'Email address', 'lenvy' ); ?>
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

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
					<div class="form-row mb-7">
						<label
							for="reg_password"
							class="block text-xs font-medium uppercase tracking-widest text-neutral-500 mb-2"
						>
							<?php esc_html_e( 'Password', 'lenvy' ); ?>
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
				<?php else : ?>
					<p class="text-sm text-neutral-500 mb-7">
						<?php esc_html_e( 'A password will be sent to your email address.', 'lenvy' ); ?>
					</p>
				<?php endif; ?>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<input type="hidden" name="redirect" value="<?php echo esc_url( wc_get_checkout_url() ); ?>" />

				<button
					type="submit"
					class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit w-full bg-black text-white text-xs font-medium uppercase tracking-widest py-4 transition-opacity hover:opacity-70 cursor-pointer"
					name="register"
					value="<?php esc_attr_e( 'Create account', 'lenvy' ); ?>"
				>
					<?php esc_html_e( 'Create account', 'lenvy' ); ?>
				</button>

				<?php do_action( 'woocommerce_register_form_end' ); ?>

			</form>

		</div><!-- .lenvy-account-auth__panel (register) -->

	</div><!-- .max-w-4xl -->
</div><!-- .lenvy-account-auth -->
