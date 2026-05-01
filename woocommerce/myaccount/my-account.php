<?php
/**
 * My Account — page wrapper.
 *
 * Sticky sidebar (left) + content (right). Crumbs at the top, no big
 * page heading — the dashboard's own greeting carries the page voice.
 * Matches the rest of the account / checkout flow design system.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Lenvy
 */

defined('ABSPATH') || exit();
?>

<main class="lenvy-account lenvy-account--dashboard">
	<div class="lenvy-container">
		<nav class="lenvy-account__crumbs" aria-label="<?php esc_attr_e('Kruimelpad', 'lenvy'); ?>">
			<a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'lenvy'); ?></a>
			<span class="sep" aria-hidden="true">/</span>
			<span aria-current="page"><?php esc_html_e('Mijn account', 'lenvy'); ?></span>
		</nav>

		<div class="lenvy-account__layout">

			<aside class="lenvy-account__sidebar">
				<div class="lenvy-account__sidebar-inner">
					<?php do_action('woocommerce_account_navigation'); ?>
				</div>
			</aside>

			<div class="lenvy-account__content">
				<?php do_action('woocommerce_account_content'); ?>
			</div>

		</div>
	</div>
</main>
