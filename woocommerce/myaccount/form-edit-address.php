<?php
/**
 * My Account — Edit address form.
 *
 * Overrides woocommerce/myaccount/form-edit-address.php
 *
 * Wraps WC's `woocommerce_form_field()` calls in our `.lenvy-account__form`
 * container. The fields keep WC's default markup (so plugin hooks like
 * `woocommerce_after_edit_address_form_*` still work and country-specific
 * field overrides apply), and `_account.scss` styles them to match our
 * design system.
 *
 * @package Lenvy
 *
 * @var array  $address      Field args keyed by field id.
 * @var string $load_address 'billing' | 'shipping' | empty (selector page).
 */

defined('ABSPATH') || exit();

$page_title = ('billing' === $load_address)
	? __('Factuuradres', 'lenvy')
	: __('Bezorgadres', 'lenvy');

$page_lede = ('billing' === $load_address)
	? __('Dit adres staat op je facturen.', 'lenvy')
	: __('Hier bezorgen we je bestellingen standaard.', 'lenvy');

do_action('woocommerce_before_edit_account_address_form');
?>

<?php if (!$load_address): ?>
	<?php wc_get_template('myaccount/my-address.php'); ?>
<?php else: ?>

	<div class="lenvy-account-edit">

		<header class="lenvy-account-edit__head">
			<h1 class="lenvy-account-edit__title">
				<?php echo esc_html(apply_filters('woocommerce_my_account_edit_address_title', $page_title, $load_address)); ?>
			</h1>
			<p class="lenvy-account-edit__lede"><?php echo esc_html($page_lede); ?></p>
		</header>

		<form class="lenvy-account__form lenvy-account__form--wide lenvy-account__form--wc-fields" method="post" novalidate>

			<?php do_action("woocommerce_before_edit_address_form_{$load_address}"); ?>

			<div class="woocommerce-address-fields__field-wrapper lenvy-account-edit__wc-fields">
				<?php
				foreach ($address as $key => $field) {
					woocommerce_form_field($key, $field, wc_get_post_data_by_key($key, $field['value']));
				}
				?>
			</div>

			<?php do_action("woocommerce_after_edit_address_form_{$load_address}"); ?>

			<div class="lenvy-account__actions">
				<?php wp_nonce_field('woocommerce-edit_address', 'woocommerce-edit-address-nonce'); ?>
				<input type="hidden" name="action" value="edit_address" />
				<button type="submit" class="lenvy-account__submit" name="save_address" value="<?php esc_attr_e('Adres opslaan', 'lenvy'); ?>">
					<?php esc_html_e('Adres opslaan', 'lenvy'); ?>
					<svg width="14" height="10" viewBox="0 0 14 10" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
						<path d="M1 5h12m0 0L9 1m4 4L9 9"/>
					</svg>
				</button>
			</div>

		</form>

	</div>

<?php endif; ?>

<?php do_action('woocommerce_after_edit_account_address_form'); ?>
