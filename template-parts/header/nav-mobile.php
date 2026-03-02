<?php
/**
 * Mobile navigation drawer.
 *
 * Rendered after <header> in site-header.php.
 * JS hooks: [data-drawer-toggle] opens, [data-drawer-close] closes,
 *            [data-drawer-backdrop] closes on click.
 * Accordion JS: [data-mobile-submenu-toggle] handles sub-menus.
 *
 * Falls back to the primary menu when no dedicated mobile menu is assigned.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$logo_id  = lenvy_field('lenvy_site_logo', 'options');
$cart_url  = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/');
$shop_url  = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
$account_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/');

// Determine which menu location to render.
$menu_location = has_nav_menu('mobile') ? 'mobile' : (has_nav_menu('primary') ? 'primary' : '');
?>

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
	class="fixed inset-y-0 left-0 z-[50] w-[360px] max-w-[calc(100vw-3rem)] bg-white overflow-y-auto -translate-x-full transition-transform duration-300 flex flex-col"
	aria-hidden="true"
	role="dialog"
	aria-label="<?php esc_attr_e('Navigation', 'lenvy'); ?>"
>

	<!-- Drawer header -->
	<div class="flex items-center justify-between px-7 h-[72px] border-b border-neutral-100 shrink-0">
		<a
			href="<?php echo esc_url(home_url('/')); ?>"
			class="block"
			aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>"
		>
			<?php if ($logo_id): ?>
				<?php echo lenvy_get_image($logo_id, 'medium', 'block max-h-8 w-auto object-contain');
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			<?php else: ?>
				<span class="font-serif italic text-2xl tracking-tight text-neutral-900">
					<?php bloginfo('name'); ?>
				</span>
			<?php endif; ?>
		</a>
		<button
			type="button"
			data-drawer-close
			class="p-2 -mr-2 text-neutral-400 hover:text-black transition-colors duration-200"
			aria-label="<?php esc_attr_e('Menu sluiten', 'lenvy'); ?>"
		>
			<?php lenvy_icon('close', '', 'md'); ?>
		</button>
	</div>

	<!-- Nav menu -->
	<nav class="px-7 py-4 flex-1" aria-label="<?php esc_attr_e('Mobile Navigation', 'lenvy'); ?>">

		<?php if ($menu_location): ?>
			<?php wp_nav_menu([
				'theme_location' => $menu_location,
				'container'      => false,
				'menu_class'     => '',
				'walker'         => new Lenvy_Mobile_Nav_Walker(),
				'fallback_cb'    => false,
				'depth'          => 2,
			]); ?>
		<?php else: ?>
			<!-- Fallback links when no menu is assigned -->
			<ul>
				<li class="border-b border-neutral-100">
					<a href="<?php echo esc_url($shop_url ?: home_url('/shop/')); ?>" class="block py-4 text-sm font-medium text-neutral-800 hover:text-black transition-colors duration-200">
						<?php esc_html_e('Shop', 'lenvy'); ?>
					</a>
				</li>
				<?php
				$featured_cats = lenvy_field('lenvy_featured_categories');
				if (!empty($featured_cats)):
					foreach ((array) $featured_cats as $cat_id):
						$term = get_term((int) $cat_id, 'product_cat');
						if (!$term || is_wp_error($term)) {
							continue;
						}
						$term_url = get_term_link($term, 'product_cat');
						if (is_wp_error($term_url)) {
							continue;
						}
				?>
				<li class="border-b border-neutral-100">
					<a href="<?php echo esc_url($term_url); ?>" class="block py-4 text-sm font-medium text-neutral-800 hover:text-black transition-colors duration-200">
						<?php echo esc_html($term->name); ?>
					</a>
				</li>
				<?php
					endforeach;
				endif;
				?>
				<li class="border-b border-neutral-100">
					<a href="<?php echo esc_url(add_query_arg('orderby', 'date', $shop_url ?: home_url('/shop/'))); ?>" class="block py-4 text-sm font-medium text-neutral-800 hover:text-black transition-colors duration-200">
						<?php esc_html_e('Nieuw Binnen', 'lenvy'); ?>
					</a>
				</li>
				<li class="border-b border-neutral-100">
					<a href="<?php echo esc_url(add_query_arg('filter_onsale', '1', $shop_url ?: home_url('/shop/'))); ?>" class="block py-4 text-sm font-medium text-neutral-800 hover:text-black transition-colors duration-200">
						<?php esc_html_e('Sale', 'lenvy'); ?>
					</a>
				</li>
			</ul>
		<?php endif; ?>

		<!-- Utility links -->
		<div class="mt-6 pt-6 border-t border-neutral-100 space-y-1">
			<a
				href="<?php echo esc_url($account_url ?: home_url('/my-account/')); ?>"
				class="flex items-center gap-3 py-3 text-sm text-neutral-600 hover:text-black transition-colors duration-200"
			>
				<?php lenvy_icon('user', 'text-neutral-400', 'sm'); ?>
				<?php echo is_user_logged_in()
					? esc_html__('Mijn Account', 'lenvy')
					: esc_html__('Inloggen', 'lenvy'); ?>
			</a>
			<a
				href="<?php echo esc_url($cart_url); ?>"
				class="flex items-center gap-3 py-3 text-sm text-neutral-600 hover:text-black transition-colors duration-200"
			>
				<?php lenvy_icon('cart', 'text-neutral-400', 'sm'); ?>
				<?php esc_html_e('Winkelwagen', 'lenvy'); ?>
			</a>
		</div>

	</nav>

	<!-- Shop CTA at bottom -->
	<div class="px-7 py-6 mt-auto border-t border-neutral-100 shrink-0">
		<?php get_template_part('template-parts/components/button', null, [
			'label'      => __('Bekijk de Collectie', 'lenvy'),
			'url'        => $shop_url ?: home_url('/shop/'),
			'variant'    => 'primary',
			'size'       => 'md',
			'full_width' => true,
		]); ?>
	</div>

</div>
