<?php
/**
 * WooCommerce compatibility — hook removals, loop config, wrapper overrides.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// ─── Dutch translations for WooCommerce core strings ─────────────────────────
// WC uses the 'woocommerce' text domain. When the site runs without a full
// nl_NL locale / .mo file we translate the most visible strings here.

add_filter('gettext', function (string $translation, string $text, string $domain): string {
	if ($domain !== 'woocommerce') {
		return $translation;
	}

	static $map = null;
	if ($map === null) {
		$map = [
			// Checkout fields
			'Billing details'                    => 'Factuurgegevens',
			'Ship to a different address?'       => 'Naar een ander adres verzenden?',
			'Shipping details'                   => 'Verzendgegevens',
			'Additional information'             => 'Aanvullende informatie',
			'Order notes'                        => 'Opmerkingen bij bestelling',
			'Notes about your order, e.g. special notes for delivery.' => 'Opmerkingen over je bestelling, bijv. speciale instructies voor bezorging.',
			'First name'                         => 'Voornaam',
			'Last name'                          => 'Achternaam',
			'Company name'                       => 'Bedrijfsnaam',
			'Country / Region'                   => 'Land / Regio',
			'Street address'                     => 'Straatnaam en huisnummer',
			'House number and street name'       => 'Huisnummer en straatnaam',
			'Apartment, suite, unit, etc.'       => 'Appartement, suite, etc.',
			'Apartment, suite, unit, etc. (optional)' => 'Appartement, suite, etc. (optioneel)',
			'Town / City'                        => 'Stad',
			'State / County'                     => 'Provincie',
			'Postcode / ZIP'                     => 'Postcode',
			'Postcode'                           => 'Postcode',
			'Phone'                              => 'Telefoonnummer',
			'Email address'                      => 'E-mailadres',

			// Account
			'Create an account?'                 => 'Account aanmaken?',
			'Create account password'            => 'Wachtwoord voor je account',
			'Returning customer?'                => 'Heb je al een account?',
			'Click here to login'                => 'Klik hier om in te loggen',
			'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.' => 'Als je eerder bij ons hebt gewinkeld, vul dan je gegevens hieronder in. Ben je een nieuwe klant? Ga dan verder naar de factuurgegevens.',
			'Username or email'                  => 'Gebruikersnaam of e-mail',
			'Password'                           => 'Wachtwoord',
			'Login'                              => 'Inloggen',
			'Remember me'                        => 'Onthoud mij',
			'Lost your password?'                => 'Wachtwoord vergeten?',

			// Order / payment
			'Place order'                        => 'Bestelling plaatsen',
			'Your order'                         => 'Jouw bestelling',
			'Product'                            => 'Product',
			'Subtotal'                           => 'Subtotaal',
			'Total'                              => 'Totaal',
			'Shipping'                           => 'Verzending',
			'Free shipping'                      => 'Gratis verzending',
			'Free!'                              => 'Gratis!',
			'Discount'                           => 'Korting',
			'Coupon:'                            => 'Kortingscode:',
			'Coupon code'                        => 'Kortingscode',
			'Apply coupon'                       => 'Toepassen',
			'Remove'                             => 'Verwijderen',
			'Tax'                                => 'BTW',
			'including %s'                       => 'inclusief %s',

			// Cart
			'Cart'                               => 'Winkelwagen',
			'Cart totals'                        => 'Winkelwagen totaal',
			'Proceed to checkout'                => 'Naar afrekenen',
			'Update cart'                        => 'Winkelwagen bijwerken',
			'Your cart is currently empty.'       => 'Je winkelwagen is leeg.',
			'Return to shop'                     => 'Terug naar shop',
			'Quantity'                           => 'Aantal',
			'Price'                              => 'Prijs',

			// My account
			'Dashboard'                          => 'Dashboard',
			'Orders'                             => 'Bestellingen',
			'Downloads'                          => 'Downloads',
			'Addresses'                          => 'Adressen',
			'Account details'                    => 'Accountgegevens',
			'Logout'                             => 'Uitloggen',
			'Log out'                            => 'Uitloggen',
			'Edit your account'                  => 'Account bewerken',
			'Billing address'                    => 'Factuuradres',
			'Shipping address'                   => 'Verzendadres',

			// Notices
			'has been added to your cart.'       => 'is toegevoegd aan je winkelwagen.',
			'View cart'                          => 'Bekijk winkelwagen',
			'Checkout'                           => 'Afrekenen',
			'An account is already registered with your email address. Please log in.' => 'Er bestaat al een account met dit e-mailadres. Log in.',

			// Product
			'Add to cart'                        => 'In winkelwagen',
			'Read more'                          => 'Bekijk product',
			'Select options'                     => 'Bekijk opties',
			'Out of stock'                       => 'Niet op voorraad',
			'In stock'                           => 'Op voorraad',
			'Sale!'                              => 'Sale!',
			'Sale'                               => 'Sale',
			'Description'                        => 'Beschrijving',
			'Additional information'             => 'Aanvullende informatie',
			'Related products'                   => 'Gerelateerde producten',
			'You may also like&hellip;'          => 'Misschien vind je dit ook leuk&hellip;',

			// Sorting
			'Default sorting'                    => 'Standaard sortering',
			'Sort by popularity'                 => 'Sorteer op populariteit',
			'Sort by average rating'             => 'Sorteer op beoordeling',
			'Sort by latest'                     => 'Sorteer op nieuwste',
			'Sort by price: low to high'         => 'Prijs: laag naar hoog',
			'Sort by price: high to low'         => 'Prijs: hoog naar laag',
		];
	}

	return $map[$text] ?? $translation;
}, 10, 3);

add_filter('ngettext', function (string $translation, string $single, string $plural, int $number, string $domain): string {
	if ($domain !== 'woocommerce') {
		return $translation;
	}

	static $map = null;
	if ($map === null) {
		$map = [
			'%1$s has been added to your cart.' => [
				'%1$s is toegevoegd aan je winkelwagen.',
				'%1$s zijn toegevoegd aan je winkelwagen.',
			],
			'%d item' => ['%d artikel', '%d artikelen'],
		];
	}

	if (isset($map[$single])) {
		return $number === 1 ? $map[$single][0] : $map[$single][1];
	}

	return $translation;
}, 10, 5);

// ─── Remove default WC wrappers ───────────────────────────────────────────────

remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

// ─── Remove default sidebar ───────────────────────────────────────────────────

remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// ─── Remove default breadcrumb ────────────────────────────────────────────────

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// ─── Remove default results count and sort bar ────────────────────────────────

remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

// ─── Loop columns ────────────────────────────────────────────────────────────

add_filter('loop_shop_columns', function (): int {
	return 3;
});

// ─── Products per page ────────────────────────────────────────────────────────

add_filter(
	'loop_shop_per_page',
	function (): int {
		return 12;
	},
	20,
);

// ─── Disable product reviews ─────────────────────────────────────────────────

// Remove the Reviews tab from the single product tabs.
add_filter('woocommerce_product_tabs', function (array $tabs): array {
	unset($tabs['reviews']);
	return $tabs;
});

// Disable the WC reviews comment type so the form never appears.
add_filter('woocommerce_product_reviews_enabled', '__return_false');

// ─── Disable product reviews — complete ──────────────────────────────────────

// Remove star rating output from the shop loop.
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

// Disable WC's own reviews toggle (belt-and-suspenders alongside product_tabs filter above).
add_filter( 'woocommerce_enable_reviews', '__return_false' );

// Close comments and pings on all posts so the review form can never appear.
add_filter( 'comments_open', '__return_false', 20, 2 );
add_filter( 'pings_open', '__return_false', 20, 2 );

// ─── Checkout access control ──────────────────────────────────────────────────
// Non-logged-in users who arrive at checkout without ?guest=1 are redirected to
// the login/register page (/winkelwagen/inloggen/) so they must explicitly pick
// Login / Register / Guest.

add_action(
	'template_redirect',
	function (): void {
		// Only act on the checkout page — never on order-received.
		if ( ! is_checkout() || is_order_received_page() ) {
			return;
		}

		// Logged-in users pass through unchecked.
		if ( is_user_logged_in() ) {
			return;
		}

		// Guest users who explicitly chose guest checkout pass through.
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( ! empty( $_GET['guest'] ) ) {
			return;
		}

		// Never redirect admin or AJAX requests.
		if ( is_admin() || wp_doing_ajax() ) {
			return;
		}

		// Don't intercept WooCommerce's own checkout form submission (POST).
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_POST['woocommerce-process-checkout-nonce'] ) ) {
			return;
		}

		wp_safe_redirect( lenvy_get_account_choice_url() );
		exit();
	},
);

// ─── Guest checkout ───────────────────────────────────────────────────────────
// When ?guest=1 is present, registration is not required at checkout.

add_filter(
	'woocommerce_checkout_registration_required',
	function ( bool $required ): bool {
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( ! empty( $_GET['guest'] ) ) {
			return false;
		}
		return true;
	},
);

// Hide the "create account" checkbox at checkout — account creation is handled
// on the login/register page before checkout.
add_filter( 'woocommerce_checkout_registration_enabled', '__return_false' );

// ─── Force classic shortcodes for cart & checkout ────────────────────────────
// WooCommerce 8.3+ uses Gutenberg blocks (wp:woocommerce/cart, wp:woocommerce/checkout)
// by default, which bypass our custom template overrides in woocommerce/cart/ and
// woocommerce/checkout/. Replace block content with classic shortcodes so our
// templates control the layout.

add_filter(
	'the_content',
	function (string $content): string {
		if (function_exists('is_cart') && is_cart()) {
			return '[woocommerce_cart]';
		}
		if (function_exists('is_checkout') && is_checkout() && !is_order_received_page()) {
			return '[woocommerce_checkout]';
		}
		return $content;
	},
	1,
);

// ─── "Incl. BTW" price suffix ────────────────────────────────────────────────

add_filter('woocommerce_get_price_suffix', function (string $suffix, $product): string {
	if (wc_tax_enabled() && 'incl' === get_option('woocommerce_tax_display_shop')) {
		return ' <span class="text-[11px] text-neutral-400">' . esc_html__('Incl. BTW', 'lenvy') . '</span>';
	}
	return $suffix;
}, 10, 2);

// ─── Checkout: remove order notes (additional information) ───────────────────

add_filter('woocommerce_enable_order_notes_field', '__return_false');

// ─── Replace default privacy policy text with custom Dutch copy ──────────────

remove_action('woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20);
remove_action('woocommerce_register_form', 'wc_registration_privacy_policy_text', 20);

add_action('woocommerce_checkout_terms_and_conditions', function () {
	$terms_url   = wc_get_page_permalink('terms');
	$privacy_url = get_privacy_policy_url();

	printf(
		'<p class="text-[11px] leading-relaxed text-neutral-400 mt-4">%s</p>',
		wp_kses_post(sprintf(
			/* translators: 1: terms link open, 2: terms link close, 3: privacy link open, 4: privacy link close */
			__('Door een account aan te maken of een bestelling te plaatsen, accepteert u de %1$sAlgemene Voorwaarden%2$s en stemt u in met de verwerking van uw gegevens, in overeenstemming met het %3$sPrivacybeleid%4$s van Lenvy.', 'lenvy'),
			'<a href="' . esc_url($terms_url) . '" target="_blank" class="underline underline-offset-2 hover:text-neutral-600 transition-colors">',
			'</a>',
			'<a href="' . esc_url($privacy_url) . '" target="_blank" class="underline underline-offset-2 hover:text-neutral-600 transition-colors">',
			'</a>'
		))
	);
}, 20);

add_action('woocommerce_register_form', function () {
	$terms_url   = wc_get_page_permalink('terms');
	$privacy_url = get_privacy_policy_url();

	printf(
		'<p class="text-[11px] leading-relaxed text-neutral-400 mt-4 mb-7">%s</p>',
		wp_kses_post(sprintf(
			/* translators: 1: terms link open, 2: terms link close, 3: privacy link open, 4: privacy link close */
			__('Door een account aan te maken of een bestelling te plaatsen, accepteert u de %1$sAlgemene Voorwaarden%2$s en stemt u in met de verwerking van uw gegevens, in overeenstemming met het %3$sPrivacybeleid%4$s van Lenvy.', 'lenvy'),
			'<a href="' . esc_url($terms_url) . '" target="_blank" class="underline underline-offset-2 hover:text-neutral-600 transition-colors">',
			'</a>',
			'<a href="' . esc_url($privacy_url) . '" target="_blank" class="underline underline-offset-2 hover:text-neutral-600 transition-colors">',
			'</a>'
		))
	);
}, 20);

// ─── Registration: validate and save first/last name ────────────────────────

add_action('woocommerce_register_post', function (string $username, string $email, $errors) {
	if ( empty( $_POST['billing_first_name'] ) ) {
		$errors->add( 'billing_first_name_error', __( 'Voornaam is verplicht.', 'lenvy' ) );
	}
	if ( empty( $_POST['billing_last_name'] ) ) {
		$errors->add( 'billing_last_name_error', __( 'Achternaam is verplicht.', 'lenvy' ) );
	}
}, 10, 3);

add_action('woocommerce_created_customer', function (int $customer_id) {
	if ( ! empty( $_POST['billing_first_name'] ) ) {
		$first = sanitize_text_field( wp_unslash( $_POST['billing_first_name'] ) );
		update_user_meta( $customer_id, 'first_name', $first );
		update_user_meta( $customer_id, 'billing_first_name', $first );
	}
	if ( ! empty( $_POST['billing_last_name'] ) ) {
		$last = sanitize_text_field( wp_unslash( $_POST['billing_last_name'] ) );
		update_user_meta( $customer_id, 'last_name', $last );
		update_user_meta( $customer_id, 'billing_last_name', $last );
	}
});

// ─── Checkout: remove default coupon + login notices, add custom Dutch login ─

remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);

add_action('woocommerce_before_checkout_form', function (): void {
	if (is_user_logged_in()) {
		return;
	}

	$account_url = wc_get_page_permalink('myaccount');
	printf(
		'<div class="mb-8 p-5 border border-neutral-200 text-sm text-neutral-600">' .
		'%s <a href="%s" class="font-medium text-black underline underline-offset-2 hover:no-underline">%s</a> %s ' .
		'<a href="%s" class="font-medium text-black underline underline-offset-2 hover:no-underline">%s</a>.</div>',
		esc_html__('Heb je al een account?', 'lenvy'),
		esc_url($account_url),
		esc_html__('Log in', 'lenvy'),
		esc_html__('of', 'lenvy'),
		esc_url($account_url . '#register'),
		esc_html__('maak een account aan', 'lenvy')
	);
}, 10);

// ─── Dequeue all WC default styles ──────────────────────────────────────────
// The theme provides comprehensive custom styles for every WC element.
// WC's built-in woocommerce-general.css, woocommerce-layout.css and
// woocommerce-smallscreen.css use high-specificity selectors that fight
// our theme styles (e.g. .woocommerce form .form-row input.input-text).

add_filter('woocommerce_enqueue_styles', '__return_empty_array');

add_action(
	'wp_enqueue_scripts',
	function (): void {
		wp_dequeue_style('wc-blocks-style');
	},
	200,
);

// ─── Fix false-positive active nav items on front page ───────────────────────
// WooCommerce marks product category menu items as current-menu-item on the
// static front page. Strip those classes so nothing appears active on the homepage.

add_filter('wp_nav_menu_objects', function (array $items): array {
	if (!is_front_page()) {
		return $items;
	}

	$strip = [
		'current-menu-item',
		'current-menu-ancestor',
		'current-menu-parent',
		'current_page_item',
		'current_page_parent',
		'current_page_ancestor',
	];

	foreach ($items as $item) {
		$item->classes = array_diff($item->classes, $strip);
		$item->current = false;
	}

	return $items;
}, 20);

// ─── Custom wrappers (add_action to match the removed hooks) ─────────────────

add_action(
	'woocommerce_before_main_content',
	function (): void {
		echo '<div id="primary" class="lenvy-wc-main">';
	},
	10,
);

add_action(
	'woocommerce_after_main_content',
	function (): void {
		echo '</div>';
	},
	10,
);
