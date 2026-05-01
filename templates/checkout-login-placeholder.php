<?php
/**
 * Checkout login interstitial.
 *
 * Reachable at /afrekenen/inloggen/. Guests proceeding from the cart's
 * "Naar betalen" CTA land here to choose: log in, register, or continue
 * as guest. Logged-in users hitting this URL skip straight to checkout.
 *
 * Uses the minimal checkout chrome (header + steps + footer) so the page
 * reads as a sub-step of the checkout funnel, not a detour. The login /
 * register forms are the same partials rendered on /mijn-account/, with
 * `redirect` pointed at /afrekenen/ so post-auth lands on checkout.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// Already authenticated → skip the choice, go straight to checkout.
if (is_user_logged_in()) {
	wp_safe_redirect(function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/afrekenen/'));
	exit();
}

// NOTE: empty-cart guard intentionally omitted while the cart still
// renders from `template-parts/cart/placeholder-data.php` — those items
// don't live in `WC()->cart`, so a real `is_empty()` check would always
// fire and bounce the user back to /winkelwagen/. Re-add once the cart
// is wired to real WC products.

add_filter('pre_get_document_title', static fn() => sprintf(__('Inloggen of doorgaan als gast — %s', 'lenvy'), get_bloginfo('name')));

$checkout_url = function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/afrekenen/');

get_header('checkout');
?>

<?php get_template_part('template-parts/checkout/steps', null, ['current' => 2]); ?>

<main class="lenvy-checkout lenvy-account lenvy-checkout-login" data-account-page>

	<div class="lenvy-container">

		<!-- Compact page head: title on the left, guest CTA on the right —
		     guarantees all three options (login / register / guest) are
		     visible above the fold without scroll. -->
		<header class="lenvy-checkout-login__head">
			<div class="lenvy-checkout-login__head-text">
				<span class="lenvy-checkout__eyebrow"><?php esc_html_e('Stap 2 van 3', 'lenvy'); ?></span>
				<h1 class="lenvy-checkout-login__title"><?php esc_html_e('Inloggen of doorgaan als gast.', 'lenvy'); ?></h1>
				<p class="lenvy-checkout-login__lede">
					<?php esc_html_e('Heb je al een account? Log in voor sneller afrekenen. Of maak er nu een aan — alvast handig voor latere bestellingen.', 'lenvy'); ?>
				</p>
			</div>

			<a class="lenvy-checkout-login__guest-link" href="<?php echo esc_url($checkout_url); ?>">
				<?php esc_html_e('Doorgaan zonder account', 'lenvy'); ?>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
					<path d="M5 12h14M13 6l6 6-6 6"/>
				</svg>
			</a>
		</header>

		<?php
		// WC notice queue — login / register errors surface here.
		if (function_exists('wc_print_notices') && function_exists('wc_notice_count') && wc_notice_count() > 0) {
			echo '<div class="lenvy-account__notices">';
			wc_print_notices();
			echo '</div>';
		}
		?>

		<div class="lenvy-account__grid lenvy-checkout-login__grid">
			<?php get_template_part('template-parts/account/login-form', null, ['wc_mode' => true, 'redirect' => $checkout_url]); ?>
			<div class="lenvy-account__divider" aria-hidden="true"></div>
			<?php get_template_part('template-parts/account/register-form', null, ['wc_mode' => true, 'redirect' => $checkout_url]); ?>
		</div>

	</div>

</main>

<?php
get_footer('checkout');
