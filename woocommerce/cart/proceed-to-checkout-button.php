<?php
/**
 * Proceed to checkout button — black full-width CTA.
 *
 * Overrides woocommerce/cart/proceed-to-checkout-button.php
 *
 * @package Lenvy
 * @version 7.0.1
 */

defined('ABSPATH') || exit();
?>

<a
	href="<?php echo esc_url(wc_get_checkout_url()); ?>"
	class="lenvy-checkout-btn"
>
	<?php esc_html_e('Naar afrekenen', 'lenvy'); ?>
</a>
