<?php
/**
 * My Account — Dashboard.
 *
 * Greeting + recent orders preview + saved addresses + quick links.
 * Replaces the default WC dashboard's intro paragraph and the generic
 * 4-card endpoint grid with content the user actually wants on landing.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$user_id = get_current_user_id();
$user    = wp_get_current_user();

// ─── Recent orders ─────────────────────────────────────────────────────────
$orders = function_exists('wc_get_orders')
	? wc_get_orders([
		'customer_id' => $user_id,
		'limit'       => 3,
		'orderby'     => 'date',
		'order'       => 'DESC',
		'status'      => array_keys(wc_get_order_statuses()),
	])
	: [];

// ─── Address preview ───────────────────────────────────────────────────────
$customer = new WC_Customer($user_id);
$shipping = $customer->get_shipping();
$billing  = $customer->get_billing();

$has_shipping = !empty(array_filter([
	$shipping['address_1'] ?? '',
	$shipping['city']      ?? '',
	$shipping['postcode']  ?? '',
]));
$has_billing = !empty(array_filter([
	$billing['address_1'] ?? '',
	$billing['city']      ?? '',
	$billing['postcode']  ?? '',
]));

$status_label = static function (string $status): string {
	$statuses = wc_get_order_statuses();
	$key      = 'wc-' . $status;
	return $statuses[$key] ?? ucfirst($status);
};

// Map WC statuses to a tone modifier on the badge.
$status_tone = static function (string $status): string {
	if (in_array($status, ['processing', 'completed'], true)) return 'ok';
	if (in_array($status, ['cancelled', 'failed', 'refunded'], true)) return 'err';
	return 'muted';
};
?>

<section class="lenvy-account-dash">

	<header class="lenvy-account-dash__greeting">
		<span class="lenvy-account-dash__eyebrow"><?php esc_html_e('Hallo', 'lenvy'); ?></span>
		<h1 class="lenvy-account-dash__name">
			<?php
			echo esc_html($user->first_name ?: $user->display_name);
			?>.
		</h1>
		<p class="lenvy-account-dash__lede">
			<?php
			$last_login = get_user_meta($user_id, '_lenvy_last_login', true);
			if ($last_login) {
				/* translators: %s: human-readable last-login date */
				printf(
					esc_html__('Welkom terug. Vorige keer ingelogd %s.', 'lenvy'),
					esc_html(human_time_diff((int) $last_login) . ' ' . __('geleden', 'lenvy'))
				);
			} else {
				esc_html_e('Welkom terug.', 'lenvy');
			}
			?>
		</p>
	</header>

	<!-- Recent orders -->
	<section class="lenvy-account-card" aria-labelledby="lenvy-recent-orders">
		<header class="lenvy-account-card__head">
			<h2 id="lenvy-recent-orders" class="lenvy-account-card__title">
				<?php esc_html_e('Recente bestellingen', 'lenvy'); ?>
			</h2>
			<?php if ($orders): ?>
				<a class="lenvy-account-card__head-link" href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>">
					<?php esc_html_e('Bekijk alle', 'lenvy'); ?>
					<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
						<path d="M5 12h14M13 6l6 6-6 6"/>
					</svg>
				</a>
			<?php endif; ?>
		</header>

		<?php if ($orders): ?>
			<ol class="lenvy-account-orders">
				<?php foreach ($orders as $order):
					$item_count = $order->get_item_count();
					$status     = $order->get_status();
				?>
					<li class="lenvy-account-orders__row">
						<div class="lenvy-account-orders__main">
							<span class="lenvy-account-orders__num">
								<?php
								/* translators: %s: order number */
								printf(esc_html__('#%s', 'lenvy'), esc_html($order->get_order_number()));
								?>
							</span>
							<span class="lenvy-account-orders__meta">
								<?php
								echo esc_html(wc_format_datetime($order->get_date_created(), get_option('date_format')));
								echo ' · ';
								/* translators: %d: item count */
								printf(esc_html(_n('%d artikel', '%d artikelen', $item_count, 'lenvy')), (int) $item_count);
								?>
							</span>
						</div>
						<span class="lenvy-account-badge lenvy-account-badge--<?php echo esc_attr($status_tone($status)); ?>">
							<?php echo esc_html($status_label($status)); ?>
						</span>
						<span class="lenvy-account-orders__total">
							<?php echo wp_kses_post($order->get_formatted_order_total()); ?>
						</span>
						<a class="lenvy-account-orders__view" href="<?php echo esc_url($order->get_view_order_url()); ?>" aria-label="<?php echo esc_attr(sprintf(__('Bekijk bestelling %s', 'lenvy'), $order->get_order_number())); ?>">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
								<path d="M5 12h14M13 6l6 6-6 6"/>
							</svg>
						</a>
					</li>
				<?php endforeach; ?>
			</ol>
		<?php else: ?>
			<p class="lenvy-account-card__empty">
				<?php esc_html_e('Je hebt nog geen bestellingen geplaatst.', 'lenvy'); ?>
				<a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Verder winkelen', 'lenvy'); ?></a>
			</p>
		<?php endif; ?>
	</section>

	<!-- Addresses -->
	<div class="lenvy-account-dash__addr-grid">

		<section class="lenvy-account-card" aria-labelledby="lenvy-shipping-addr">
			<header class="lenvy-account-card__head">
				<h2 id="lenvy-shipping-addr" class="lenvy-account-card__title">
					<?php esc_html_e('Bezorgadres', 'lenvy'); ?>
				</h2>
				<a class="lenvy-account-card__head-link" href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'shipping')); ?>">
					<?php echo $has_shipping ? esc_html__('Wijzig', 'lenvy') : esc_html__('Toevoegen', 'lenvy'); ?>
				</a>
			</header>
			<?php if ($has_shipping): ?>
				<address class="lenvy-account-address">
					<?php
					$formatted = WC()->countries->get_formatted_address($shipping);
					echo $formatted ? wp_kses_post($formatted) : esc_html__('Geen bezorgadres ingesteld.', 'lenvy');
					?>
				</address>
			<?php else: ?>
				<p class="lenvy-account-card__empty">
					<?php esc_html_e('Nog geen bezorgadres ingesteld.', 'lenvy'); ?>
				</p>
			<?php endif; ?>
		</section>

		<section class="lenvy-account-card" aria-labelledby="lenvy-billing-addr">
			<header class="lenvy-account-card__head">
				<h2 id="lenvy-billing-addr" class="lenvy-account-card__title">
					<?php esc_html_e('Factuuradres', 'lenvy'); ?>
				</h2>
				<a class="lenvy-account-card__head-link" href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'billing')); ?>">
					<?php echo $has_billing ? esc_html__('Wijzig', 'lenvy') : esc_html__('Toevoegen', 'lenvy'); ?>
				</a>
			</header>
			<?php if ($has_billing): ?>
				<address class="lenvy-account-address">
					<?php
					$formatted = WC()->countries->get_formatted_address($billing);
					echo $formatted ? wp_kses_post($formatted) : esc_html__('Geen factuuradres ingesteld.', 'lenvy');
					?>
				</address>
			<?php else: ?>
				<p class="lenvy-account-card__empty">
					<?php esc_html_e('Nog geen factuuradres ingesteld.', 'lenvy'); ?>
				</p>
			<?php endif; ?>
		</section>

	</div>

	<!-- Quick links -->
	<nav class="lenvy-account-dash__quick" aria-label="<?php esc_attr_e('Account snelkoppelingen', 'lenvy'); ?>">
		<a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>" class="lenvy-account-dash__quick-link">
			<span class="lenvy-account-dash__quick-label"><?php esc_html_e('Accountgegevens', 'lenvy'); ?></span>
			<span class="lenvy-account-dash__quick-sub"><?php esc_html_e('Naam, e-mail en wachtwoord', 'lenvy'); ?></span>
		</a>
		<a href="<?php echo esc_url(wc_get_account_endpoint_url('customer-logout')); ?>" class="lenvy-account-dash__quick-link is-logout">
			<span class="lenvy-account-dash__quick-label"><?php esc_html_e('Uitloggen', 'lenvy'); ?></span>
			<span class="lenvy-account-dash__quick-sub"><?php esc_html_e('Beëindig je sessie op dit apparaat', 'lenvy'); ?></span>
		</a>
	</nav>

</section>
