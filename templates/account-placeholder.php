<?php
/**
 * Account placeholder page.
 *
 * Reachable at /mijn-account/. Renders the login + register design and
 * actually submits to WC's form handlers (priority 20 on `template_redirect`,
 * before our placeholder loader at priority 30) — so login / register work
 * end-to-end without a configured WC My Account page.
 *
 * Once you rename the WC My Account page slug to `mijn-account`, the
 * route handler in `inc/placeholder-pages.php` automatically steps aside
 * and WC renders the page directly with our `form-login.php` override.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// Resolve WC My Account dashboard URL once.
$myaccount_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : '';

// Already authenticated → straight to the dashboard. Falls back to home
// when no WC My Account page is configured yet.
if (is_user_logged_in()) {
	wp_safe_redirect($myaccount_url ?: home_url('/'));
	exit();
}

add_filter('pre_get_document_title', static fn() => sprintf(__('Mijn account — %s', 'lenvy'), get_bloginfo('name')));

// After successful auth, land on the WC dashboard. If the WC page isn't
// set up yet, fall back to home / checkout.
$login_redirect    = $myaccount_url ?: home_url('/');
$register_redirect = $myaccount_url ?: (function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/'));

get_header();
?>

<main id="primary" class="lenvy-account" data-account-page>

	<?php get_template_part('template-parts/account/page-head'); ?>

	<div class="lenvy-container">
		<?php
		// WC notice queue — surfaces login / register errors above the form.
		if (function_exists('wc_print_notices') && function_exists('wc_notice_count') && wc_notice_count() > 0) {
			echo '<div class="lenvy-account__notices">';
			wc_print_notices();
			echo '</div>';
		}
		?>

		<div class="lenvy-account__grid">
			<?php get_template_part('template-parts/account/login-form', null, ['wc_mode' => true, 'redirect' => $login_redirect]); ?>
			<div class="lenvy-account__divider" aria-hidden="true"></div>
			<?php get_template_part('template-parts/account/register-form', null, ['wc_mode' => true, 'redirect' => $register_redirect]); ?>
		</div>
	</div>

</main>

<?php
get_footer();
