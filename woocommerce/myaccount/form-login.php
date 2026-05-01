<?php
/**
 * My Account — Login / Register form.
 *
 * Overrides woocommerce/myaccount/form-login.php
 *
 * Two-column layout: Login (left) · Register (right). Renders via the
 * shared `template-parts/account/login-form.php` and `register-form.php`
 * partials so the placeholder route at /mijn-account/ and the live WC
 * page render identical markup. WC form hooks + nonces are wired through
 * via `wc_mode = true`.
 *
 * @package Lenvy
 *
 * @var string $redirect URL to redirect to after login.
 */

defined('ABSPATH') || exit();

if (is_user_logged_in()) {
	wp_safe_redirect(wc_get_page_permalink('myaccount'));
	exit();
}

$redirect = $redirect ?? wc_get_account_endpoint_url('dashboard');
?>

<div class="lenvy-account">
	<?php get_template_part('template-parts/account/page-head'); ?>

	<div class="lenvy-container">
		<div class="lenvy-account__grid">
			<?php get_template_part('template-parts/account/login-form', null, ['wc_mode' => true, 'redirect' => $redirect]); ?>
			<div class="lenvy-account__divider" aria-hidden="true"></div>
			<?php get_template_part('template-parts/account/register-form', null, ['wc_mode' => true, 'redirect' => wc_get_checkout_url()]); ?>
		</div>
	</div>
</div>
