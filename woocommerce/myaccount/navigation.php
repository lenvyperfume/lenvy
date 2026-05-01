<?php
/**
 * My Account — sidebar navigation.
 *
 * Styled to match the rest of the design system: hairline divider above
 * "Uitloggen", lavender left-edge marker on the active item, muted
 * default state with fg-color hover.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Lenvy
 */

defined('ABSPATH') || exit();

do_action('woocommerce_before_account_navigation');

$items = wc_get_account_menu_items();
?>

<nav class="lenvy-account-nav" aria-label="<?php esc_attr_e('Mijn account navigatie', 'lenvy'); ?>">
	<ul class="lenvy-account-nav__list">
		<?php foreach ($items as $endpoint => $label):
			$is_active = wc_is_current_account_menu_item($endpoint);
			$is_logout = 'customer-logout' === $endpoint;
			$item_classes = trim('lenvy-account-nav__item'
				. ($is_active ? ' is-active' : '')
				. ($is_logout ? ' is-logout' : ''));
		?>
			<li class="<?php echo esc_attr($item_classes); ?>">
				<a
					href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"
					class="lenvy-account-nav__link"
					<?php echo $is_active ? 'aria-current="page"' : ''; ?>
				>
					<span class="lenvy-account-nav__marker" aria-hidden="true"></span>
					<span class="lenvy-account-nav__label"><?php echo esc_html($label); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action('woocommerce_after_account_navigation'); ?>
