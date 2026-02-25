<?php
/**
 * Desktop primary navigation.
 *
 * Rendered inside site-header.php. Hidden on mobile (lg:flex).
 * Dropdown submenus are CSS-only via Tailwind group-hover / group-focus-within.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

if (!has_nav_menu('primary')) {
	return;
}
?>
<nav
	class="hidden lg:flex items-center"
	aria-label="<?php esc_attr_e('Primary Navigation', 'lenvy'); ?>"
>
	<?php wp_nav_menu([
 	'theme_location' => 'primary',
 	'menu_class' => 'flex items-center gap-7',
 	'container' => false,
 	'walker' => new Lenvy_Primary_Nav_Walker(),
 	'fallback_cb' => false,
 	'depth' => 2,
 ]); ?>
</nav>
