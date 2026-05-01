<?php
/**
 * Account — breadcrumb only.
 *
 * The eyebrow + page title were dropped — "Inloggen" / "Registreren"
 * panel headings already convey what the page is. Keeping only the
 * breadcrumb for navigation context.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();
?>

<div class="lenvy-container">
	<nav class="lenvy-account__crumbs" aria-label="<?php esc_attr_e('Kruimelpad', 'lenvy'); ?>">
		<a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'lenvy'); ?></a>
		<span class="sep" aria-hidden="true">/</span>
		<span aria-current="page"><?php esc_html_e('Mijn account', 'lenvy'); ?></span>
	</nav>
</div>
