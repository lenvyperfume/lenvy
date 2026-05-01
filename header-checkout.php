<?php
/**
 * Minimal header for checkout / cart placeholder pages.
 *
 * Loaded via `get_header('checkout')`. Renders doctype, wp_head, body open,
 * and the minimal logo + secure-badge bar — no nav, no announcement bar.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$logo_id = function_exists('lenvy_field') ? lenvy_field('lenvy_site_logo', 'options') : null;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class('lenvy-checkout-page'); ?>>

<?php wp_body_open(); ?>

<header class="lenvy-checkout__header">
	<div class="lenvy-container">
		<div class="lenvy-checkout__header-row">
			<a href="<?php echo esc_url(home_url('/')); ?>" class="lenvy-checkout__logo" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
				<?php if ($logo_id && function_exists('lenvy_get_image')): ?>
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo lenvy_get_image($logo_id, 'medium', 'lenvy-checkout__logo-img');
					?>
				<?php else: ?>
					<span class="lenvy-checkout__logo-mark">
						<?php bloginfo('name'); ?>
						<span class="lenvy-checkout__logo-dot" aria-hidden="true"></span>
					</span>
				<?php endif; ?>
			</a>
			<span class="lenvy-checkout__secure">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
					<rect x="3" y="11" width="18" height="11" rx="2"/>
					<path d="M7 11V7a5 5 0 0 1 10 0v4"/>
				</svg>
				<?php esc_html_e('Beveiligd afrekenen', 'lenvy'); ?>
			</span>
		</div>
	</div>
</header>
