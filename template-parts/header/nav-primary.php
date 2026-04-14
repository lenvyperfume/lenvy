<?php
/**
 * Desktop primary navigation — full-width centred bar.
 *
 * Sits in its own row below the logo bar (Skins-style).
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
	class="flex items-center justify-center"
	aria-label="<?php esc_attr_e('Hoofdnavigatie', 'lenvy'); ?>"
>
	<?php wp_nav_menu([
		'theme_location' => 'primary',
		'menu_class' => 'flex items-center justify-center gap-8 font-normal',
		'container' => false,
		'walker' => new Lenvy_Primary_Nav_Walker(),
		'fallback_cb' => false,
		'depth' => 2,
	]); ?>
</nav>
