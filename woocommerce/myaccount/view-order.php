<?php
/**
 * My Account — View order.
 *
 * Replaces the default WC status sentence + raw `do_action('woocommerce_view_order')`
 * with a structured layout: header card (number + status + date),
 * order details card (items + totals via the order-details template),
 * billing/shipping address grid, and customer notes if any.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Lenvy
 *
 * @var int      $order_id
 * @var WC_Order $order
 */

defined('ABSPATH') || exit();

$notes  = $order->get_customer_order_notes();
$status = $order->get_status();

$status_tone = static function (string $s): string {
	if (in_array($s, ['processing', 'completed'], true)) return 'ok';
	if (in_array($s, ['cancelled', 'failed', 'refunded'], true)) return 'err';
	return 'muted';
};

$item_count = $order->get_item_count() - $order->get_item_count_refunded();
?>

<div class="lenvy-account-edit lenvy-account-order">

	<a class="lenvy-account-order__back" href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>">
		<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
			<path d="M19 12H5M11 18l-6-6 6-6"/>
		</svg>
		<?php esc_html_e('Terug naar bestellingen', 'lenvy'); ?>
	</a>

	<!-- Status header -->
	<header class="lenvy-account-card lenvy-account-order__head">
		<div class="lenvy-account-order__head-main">
			<span class="lenvy-account-order__num">
				#<?php echo esc_html($order->get_order_number()); ?>
			</span>
			<span class="lenvy-account-order__placed">
				<?php
				/* translators: 1: formatted date, 2: item count */
				printf(
					esc_html__('Geplaatst op %1$s · %2$s', 'lenvy'),
					esc_html(wc_format_datetime($order->get_date_created())),
					esc_html(sprintf(_n('%d artikel', '%d artikelen', $item_count, 'lenvy'), $item_count))
				);
				?>
			</span>
		</div>
		<span class="lenvy-account-badge lenvy-account-badge--<?php echo esc_attr($status_tone($status)); ?>">
			<?php echo esc_html(wc_get_order_status_name($status)); ?>
		</span>
	</header>

	<!-- Order updates / customer notes -->
	<?php if ($notes): ?>
		<section class="lenvy-account-card lenvy-account-order__notes" aria-labelledby="lenvy-order-notes">
			<header class="lenvy-account-card__head">
				<h2 id="lenvy-order-notes" class="lenvy-account-card__title">
					<?php esc_html_e('Updates', 'lenvy'); ?>
				</h2>
			</header>
			<ol class="lenvy-account-order__notes-list">
				<?php foreach ($notes as $note): ?>
					<li class="lenvy-account-order__note">
						<time class="lenvy-account-order__note-date" datetime="<?php echo esc_attr(mysql2date('c', $note->comment_date)); ?>">
							<?php echo esc_html(date_i18n(get_option('date_format') . ' · H:i', strtotime($note->comment_date))); ?>
						</time>
						<div class="lenvy-account-order__note-body">
							<?php echo wp_kses_post(wpautop(wptexturize($note->comment_content))); ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</section>
	<?php endif; ?>

	<!-- Items, totals, addresses — rendered by WC's order-details template,
	     wrapped so our SCSS can style WC's default markup. -->
	<div class="lenvy-account-order__details">
		<?php do_action('woocommerce_view_order', $order_id); ?>
	</div>

</div>
