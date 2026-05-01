<?php
/**
 * Minimal footer for checkout / cart placeholder pages.
 *
 * Loaded via `get_footer('checkout')`.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$year = date_i18n('Y');
?>

<footer class="lenvy-checkout__footer">
	<div class="lenvy-container">
		<div class="lenvy-checkout__footer-row">
			<span>
				<?php
				/* translators: %s: current year */
				printf(esc_html__('© %s Lenvy · KvK 84736291', 'lenvy'), esc_html($year));
				?>
			</span>
			<nav class="lenvy-checkout__footer-nav">
				<a href="#"><?php esc_html_e('Voorwaarden', 'lenvy'); ?></a>
				<a href="#"><?php esc_html_e('Privacy', 'lenvy'); ?></a>
				<a href="#"><?php esc_html_e('Cookies', 'lenvy'); ?></a>
				<a href="#"><?php esc_html_e('Contact', 'lenvy'); ?></a>
			</nav>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
