<?php
/**
 * My Account — Orders list.
 *
 * Replaces the default WC `<table>` with a list of order rows that
 * matches the dashboard's "recente bestellingen" pattern.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Lenvy
 *
 * @var stdClass $customer_orders   { orders: WC_Order[]|int[], max_num_pages: int }
 * @var bool     $has_orders
 * @var int      $current_page
 */

defined('ABSPATH') || exit();

do_action('woocommerce_before_account_orders', $has_orders);

$status_label = static fn(string $status): string =>
	wc_get_order_status_name($status);

$status_tone = static function (string $status): string {
	if (in_array($status, ['processing', 'completed'], true)) return 'ok';
	if (in_array($status, ['cancelled', 'failed', 'refunded'], true)) return 'err';
	return 'muted';
};
?>

<div class="lenvy-account-edit">

	<header class="lenvy-account-edit__head">
		<h1 class="lenvy-account-edit__title"><?php esc_html_e('Bestellingen', 'lenvy'); ?></h1>
		<p class="lenvy-account-edit__lede">
			<?php esc_html_e('Een overzicht van je bestellingen. Klik op een bestelling voor details.', 'lenvy'); ?>
		</p>
	</header>

	<?php if ($has_orders): ?>

		<section class="lenvy-account-card">
			<ol class="lenvy-account-orders">
				<?php
				foreach ($customer_orders->orders as $customer_order):
					$order      = wc_get_order($customer_order);
					$item_count = $order->get_item_count() - $order->get_item_count_refunded();
					$status     = $order->get_status();
				?>
					<li class="lenvy-account-orders__row">
						<div class="lenvy-account-orders__main">
							<a class="lenvy-account-orders__num" href="<?php echo esc_url($order->get_view_order_url()); ?>">
								#<?php echo esc_html($order->get_order_number()); ?>
							</a>
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
		</section>

		<?php do_action('woocommerce_before_account_orders_pagination'); ?>

		<?php if ((int) $customer_orders->max_num_pages > 1): ?>
			<nav class="lenvy-account-orders__pager" aria-label="<?php esc_attr_e('Paginering', 'lenvy'); ?>">
				<?php if ((int) $current_page !== 1): ?>
					<a class="lenvy-account-orders__pager-link" href="<?php echo esc_url(wc_get_endpoint_url('orders', $current_page - 1)); ?>" rel="prev">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
							<path d="M19 12H5M11 18l-6-6 6-6"/>
						</svg>
						<?php esc_html_e('Vorige', 'lenvy'); ?>
					</a>
				<?php else: ?>
					<span></span>
				<?php endif; ?>

				<span class="lenvy-account-orders__pager-page">
					<?php
					/* translators: 1: current page, 2: total pages */
					printf(esc_html__('Pagina %1$d van %2$d', 'lenvy'), (int) $current_page, (int) $customer_orders->max_num_pages);
					?>
				</span>

				<?php if ((int) $customer_orders->max_num_pages !== (int) $current_page): ?>
					<a class="lenvy-account-orders__pager-link" href="<?php echo esc_url(wc_get_endpoint_url('orders', $current_page + 1)); ?>" rel="next">
						<?php esc_html_e('Volgende', 'lenvy'); ?>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
							<path d="M5 12h14M13 6l6 6-6 6"/>
						</svg>
					</a>
				<?php else: ?>
					<span></span>
				<?php endif; ?>
			</nav>
		<?php endif; ?>

	<?php else: ?>

		<section class="lenvy-account-card">
			<p class="lenvy-account-card__empty">
				<?php esc_html_e('Je hebt nog geen bestellingen geplaatst.', 'lenvy'); ?>
				<a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
					<?php esc_html_e('Verder winkelen', 'lenvy'); ?>
				</a>
			</p>
		</section>

	<?php endif; ?>

</div>

<?php do_action('woocommerce_after_account_orders', $has_orders); ?>
