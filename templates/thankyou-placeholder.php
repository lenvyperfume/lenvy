<?php
/**
 * Thank-you / order-received placeholder page.
 *
 * Reachable at /bedankt/. Uses the minimal checkout chrome (header +
 * steps + footer) for visual continuity with cart and checkout. All
 * content is sourced from template-parts/thankyou/placeholder-data.php;
 * replace with `wc_get_order()` reads once the real WC checkout flow
 * exists.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$data  = require get_theme_file_path('template-parts/thankyou/placeholder-data.php');
$order = (array) ($data['order']            ?? []);
$ship  = (array) ($data['shipping_address'] ?? []);

add_filter('pre_get_document_title', static fn() => sprintf(__('Bedankt voor je bestelling — %s', 'lenvy'), get_bloginfo('name')));

get_header('checkout');
?>

<?php get_template_part('template-parts/checkout/steps', null, ['current' => 3, 'complete' => true]); ?>

<main class="lenvy-checkout lenvy-thankyou" data-thankyou-page>

	<?php get_template_part('template-parts/thankyou/hero', null, ['order' => $order]); ?>

	<div class="lenvy-container">
		<div class="lenvy-thankyou__grid">

			<div class="lenvy-thankyou__main">
				<?php get_template_part('template-parts/thankyou/timeline', null, ['order' => $order]); ?>
				<?php get_template_part('template-parts/thankyou/address-payment', null, ['shipping_address' => $ship, 'order' => $order]); ?>
				<?php get_template_part('template-parts/thankyou/next-steps'); ?>
			</div>

			<?php get_template_part('template-parts/thankyou/summary', null, ['cart' => $data]); ?>

		</div>

		<div class="lenvy-thankyou__continue">
			<a href="<?php echo esc_url(home_url('/')); ?>">
				<?php esc_html_e('Verder shoppen', 'lenvy'); ?>
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
					<path d="M5 12h14M13 6l6 6-6 6"/>
				</svg>
			</a>
		</div>
	</div>

</main>

<?php
get_footer('checkout');
