<?php
/**
 * Mobile navigation drawer.
 *
 * Rendered after <header> in site-header.php.
 * JS hooks: [data-drawer-toggle] opens, [data-drawer-close] closes,
 *            [data-drawer-backdrop] closes on click.
 * Accordion JS: [data-mobile-submenu-toggle] handles sub-menus.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit(); ?>

<!-- Backdrop -->
<div
	data-drawer-backdrop
	class="fixed inset-0 z-[45] bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300"
	aria-hidden="true"
></div>

<!-- Slide-in drawer -->
<div
	id="lenvy-mobile-drawer"
	data-drawer
	class="fixed inset-y-0 left-0 z-[50] w-80 max-w-[calc(100vw-3rem)] bg-white overflow-y-auto -translate-x-full transition-transform duration-300 flex flex-col"
	aria-hidden="true"
	role="dialog"
	aria-label="<?php esc_attr_e('Navigation', 'lenvy'); ?>"
>

	<!-- Drawer header: logo + close button -->
	<div class="flex items-center justify-between px-6 h-[68px] border-b border-neutral-100 shrink-0">
		<a
			href="<?php echo esc_url(home_url('/')); ?>"
			class="block"
			aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>"
		>
			<?php
   $logo_id = lenvy_field('lenvy_site_logo', 'options');
   if ($logo_id) {
   	echo lenvy_get_image($logo_id, 'medium', 'block max-h-8 w-auto object-contain');
   } else {
   	echo '<span class="font-serif italic text-xl tracking-tight text-neutral-900">' .
   		esc_html(get_bloginfo('name')) .
   		'</span>';
   }
   ?>
		</a>
		<button
			type="button"
			data-drawer-close
			class="p-2 text-neutral-500 hover:text-black transition-colors duration-200"
			aria-label="<?php esc_attr_e('Close navigation', 'lenvy'); ?>"
		>
			<?php lenvy_icon('close', '', 'md'); ?>
		</button>
	</div>

	<!-- Nav menu -->
	<?php if (has_nav_menu('mobile')): ?>
		<nav class="px-6 py-2 flex-1" aria-label="<?php esc_attr_e('Mobile Navigation', 'lenvy'); ?>">
			<?php wp_nav_menu([
   	'theme_location' => 'mobile',
   	'container' => false,
   	'menu_class' => '',
   	'walker' => new Lenvy_Mobile_Nav_Walker(),
   	'fallback_cb' => false,
   	'depth' => 2,
   ]); ?>
		</nav>
	<?php endif; ?>

	<!-- Shop CTA at bottom -->
	<div class="px-6 py-6 mt-auto border-t border-neutral-100 shrink-0">
		<?php
  $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
  get_template_part('template-parts/components/button', null, [
  	'label' => __('Shop All', 'lenvy'),
  	'url' => $shop_url ?: home_url('/shop/'),
  	'variant' => 'primary',
  	'size' => 'md',
  	'full_width' => true,
  ]);
  ?>
	</div>

</div>
