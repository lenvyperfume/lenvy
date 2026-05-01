<?php
/**
 * My Account — Addresses listing.
 *
 * Two-column shipping + billing cards matching the dashboard's address
 * preview. Each card deep-links to its `edit-address/{type}` endpoint.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$customer_id = get_current_user_id();

$get_addresses = (!wc_ship_to_billing_address_only() && wc_shipping_enabled())
	? apply_filters('woocommerce_my_account_get_addresses', [
		'shipping' => __('Bezorgadres', 'lenvy'),
		'billing'  => __('Factuuradres', 'lenvy'),
	], $customer_id)
	: apply_filters('woocommerce_my_account_get_addresses', [
		'billing' => __('Factuuradres', 'lenvy'),
	], $customer_id);
?>

<div class="lenvy-account-edit">

	<header class="lenvy-account-edit__head">
		<h1 class="lenvy-account-edit__title"><?php esc_html_e('Adressen', 'lenvy'); ?></h1>
		<p class="lenvy-account-edit__lede">
			<?php
			echo esc_html(apply_filters(
				'woocommerce_my_account_my_address_description',
				__('Deze adressen worden standaard gebruikt bij het afrekenen.', 'lenvy')
			));
			?>
		</p>
	</header>

	<div class="lenvy-account-dash__addr-grid">
		<?php foreach ($get_addresses as $name => $address_title):
			$address = wc_get_account_formatted_address($name);
		?>
			<section class="lenvy-account-card" aria-labelledby="lenvy-addr-<?php echo esc_attr($name); ?>">
				<header class="lenvy-account-card__head">
					<h2 id="lenvy-addr-<?php echo esc_attr($name); ?>" class="lenvy-account-card__title">
						<?php echo esc_html($address_title); ?>
					</h2>
					<a class="lenvy-account-card__head-link" href="<?php echo esc_url(wc_get_endpoint_url('edit-address', $name)); ?>">
						<?php echo $address ? esc_html__('Wijzig', 'lenvy') : esc_html__('Toevoegen', 'lenvy'); ?>
					</a>
				</header>
				<?php if ($address): ?>
					<address class="lenvy-account-address">
						<?php echo wp_kses_post($address); ?>
					</address>
				<?php else: ?>
					<p class="lenvy-account-card__empty">
						<?php esc_html_e('Nog geen adres ingesteld.', 'lenvy'); ?>
					</p>
				<?php endif; ?>
				<?php do_action('woocommerce_my_account_after_my_address', $name); ?>
			</section>
		<?php endforeach; ?>
	</div>

</div>
