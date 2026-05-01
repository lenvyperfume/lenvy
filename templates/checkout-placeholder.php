<?php
/**
 * Checkout placeholder page.
 *
 * Reachable at /afrekenen/. All content is sourced from
 * template-parts/checkout/placeholder-data.php; replace with WC checkout
 * fields + cart reads once products + checkout flow exist.
 *
 * Uses the minimal `header-checkout.php` / `footer-checkout.php` chrome
 * — no site nav, no announcement bar — to remove distractions during
 * checkout.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$cart      = require get_theme_file_path('template-parts/checkout/placeholder-data.php');
$items     = (array) ($cart['items'] ?? []);
$customer  = (array) ($cart['customer']  ?? []);
$countries = (array) ($cart['countries'] ?? ['Nederland', 'België']);
$banks     = (array) ($cart['banks']     ?? []);

$vat_rate = (float) ($cart['vat_rate'] ?? 0.21);
$subtotal = 0.0;
foreach ($items as $i) {
	$subtotal += (float) ($i['priceValue'] ?? 0) * (int) ($i['qty'] ?? 1);
}
$grand   = $subtotal; // free shipping for now
$grand_f = '€ ' . number_format_i18n($grand, 2);

// Override page title for this placeholder.
add_filter('pre_get_document_title', static fn() => sprintf(__('Afrekenen — %s', 'lenvy'), get_bloginfo('name')));

get_header('checkout');
?>

<main class="lenvy-checkout" data-checkout-page>

	<?php get_template_part('template-parts/checkout/steps', null, ['current' => 2]); ?>

	<div class="lenvy-container">
		<nav class="lenvy-checkout__crumbs" aria-label="<?php esc_attr_e('Kruimelpad', 'lenvy'); ?>">
			<a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'lenvy'); ?></a>
			<span class="sep" aria-hidden="true">/</span>
			<a href="<?php echo esc_url(function_exists('lenvy_placeholder_cart_url') ? lenvy_placeholder_cart_url() : '#'); ?>">
				<?php esc_html_e('Winkelwagen', 'lenvy'); ?>
			</a>
			<span class="sep" aria-hidden="true">/</span>
			<span aria-current="page"><?php esc_html_e('Afrekenen', 'lenvy'); ?></span>
		</nav>

		<header class="lenvy-checkout__page-head">
			<span class="lenvy-checkout__eyebrow"><?php esc_html_e('Stap 2 van 3', 'lenvy'); ?></span>
			<h1 class="lenvy-checkout__title"><?php esc_html_e('Afrekenen.', 'lenvy'); ?></h1>
			<p class="lenvy-checkout__lede">
				<?php esc_html_e('Vul je gegevens in en kies je betaalmethode. We verzenden binnen 24 uur — vóór 22:00 besteld is morgen in huis.', 'lenvy'); ?>
			</p>
		</header>

		<form class="lenvy-checkout__grid" method="post" action="#" novalidate>

			<div class="lenvy-checkout__form-col">
				<?php get_template_part('template-parts/checkout/contact', null, ['customer' => $customer]); ?>
				<?php get_template_part('template-parts/checkout/address', null, ['customer' => $customer, 'countries' => $countries]); ?>
				<?php get_template_part('template-parts/checkout/payment', null, ['banks' => $banks]); ?>

				<section class="lenvy-checkout__section lenvy-checkout__submit-row">
					<?php $thankyou_url = function_exists('lenvy_placeholder_thankyou_url') ? lenvy_placeholder_thankyou_url() : ''; ?>
					<button
						class="lenvy-checkout__place-order"
						type="button"
						data-place-order
						<?php echo $thankyou_url ? 'data-success-url="' . esc_url($thankyou_url) . '"' : ''; ?>
					>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<rect x="3" y="11" width="18" height="11" rx="2"/>
							<path d="M7 11V7a5 5 0 0 1 10 0v4"/>
						</svg>
						<span data-place-order-label>
							<?php
							/* translators: %s: order grand total */
							echo esc_html(sprintf(__('Bevestig en betaal · %s', 'lenvy'), $grand_f));
							?>
						</span>
					</button>
					<p class="lenvy-checkout__submit-meta">
						<?php
						printf(
							wp_kses(
								__('Door te bestellen ga je akkoord met onze %1$s en %2$s.', 'lenvy'),
								['a' => ['href' => true]]
							),
							'<a href="#">' . esc_html__('algemene voorwaarden', 'lenvy') . '</a>',
							'<a href="#">' . esc_html__('privacybeleid', 'lenvy') . '</a>'
						);
						?>
						<br>
						<?php esc_html_e('Verzending binnen 24 uur · 30 dagen retour, ook voor geopende parfums.', 'lenvy'); ?>
					</p>
				</section>
			</div>

			<?php get_template_part('template-parts/checkout/summary', null, ['cart' => $cart]); ?>

		</form>
	</div>

</main>

<?php
get_footer('checkout');
