<?php
/**
 * Site header — sticky header + mobile drawer + search overlay.
 *
 * Structure:
 *   <header data-header> — sticky bar; top offset for admin bar via _header.scss
 *     .lenvy-container
 *       logo | primary-nav (desktop) | actions (search + cart + hamburger)
 *   nav-mobile.php       — backdrop + slide-in drawer
 *   search-overlay.php   — full-screen search panel
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$logo_id = lenvy_field('lenvy_site_logo', 'options');
$cart_count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/');
?>

<?php get_template_part('template-parts/components/usp-bar'); ?>

<header
	data-header
	class="sticky z-[40] bg-white border-b border-neutral-200 transition-shadow duration-200"
>
	<div class="lenvy-container">
		<!--
			Three-column CSS grid: logo (left) | nav (center, auto-width) | actions (right).
			The 1fr / auto / 1fr split guarantees the nav is always truly centred
			regardless of logo or action widths — same pattern as Douglas / Dior.
		-->
		<div class="grid grid-cols-[1fr_auto_1fr] items-center h-[72px]">

			<!-- Col 1 — Logo -->
			<a
				href="<?php echo esc_url(home_url('/')); ?>"
				class="flex items-center"
				aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>"
			>
				<?php if ($logo_id): ?>
					<?php echo lenvy_get_image($logo_id, 'medium', 'block max-h-10 w-auto object-contain');
    	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    	?>
				<?php else: ?>
					<span class="font-serif italic text-2xl tracking-tight text-neutral-900">
						<?php bloginfo('name'); ?>
					</span>
				<?php endif; ?>
			</a>

			<!-- Col 2 — Primary navigation (desktop only, always a grid cell) -->
			<div class="flex items-center">
				<?php get_template_part('template-parts/header/nav-primary'); ?>
			</div>

			<!-- Col 3 — Right-side actions -->
			<div class="flex items-center justify-end gap-1 sm:gap-2">

				<!-- Search -->
				<button
					type="button"
					data-search-toggle
					class="p-2 text-neutral-500 hover:text-black transition-colors duration-200 rounded"
					aria-label="<?php esc_attr_e('Open search', 'lenvy'); ?>"
					aria-expanded="false"
				>
					<?php lenvy_icon('search', '', 'md'); ?>
				</button>

				<!-- Account -->
				<a
					href="<?php echo is_user_logged_in() ? esc_url( wc_get_account_endpoint_url( 'dashboard' ) ) : esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"
					class="p-2 text-neutral-500 hover:text-black transition-colors duration-200 rounded"
					aria-label="<?php echo is_user_logged_in() ? esc_attr__( 'My account', 'lenvy' ) : esc_attr__( 'Log in', 'lenvy' ); ?>"
				>
					<?php lenvy_icon( 'user', '', 'md' ); ?>
				</a>

				<!-- Cart -->
				<a
					href="<?php echo esc_url($cart_url); ?>"
					data-cart-link
					class="relative p-2 text-neutral-500 hover:text-black transition-colors duration-200 rounded"
					aria-label="<?php echo esc_attr(sprintf(_n('Cart, %d item', 'Cart, %d items', $cart_count, 'lenvy'), $cart_count)); ?>"
				>
					<?php lenvy_icon('cart', '', 'md'); ?>
					<span
						data-cart-count
						class="absolute -top-0.5 -right-0.5 flex items-center justify-center w-4 h-4 bg-black text-white text-[10px] font-semibold rounded-full leading-none"
						aria-hidden="true"
						<?php if ($cart_count === 0): ?>style="display:none;"<?php endif; ?>
					>
						<?php echo esc_html($cart_count > 99 ? '99+' : $cart_count); ?>
					</span>
				</a>

				<!-- Mobile menu toggle (hidden on desktop) -->
				<button
					type="button"
					data-drawer-toggle
					class="p-2 text-neutral-500 hover:text-black transition-colors duration-200 rounded lg:hidden"
					aria-label="<?php esc_attr_e('Open navigation menu', 'lenvy'); ?>"
					aria-expanded="false"
					aria-controls="lenvy-mobile-drawer"
				>
					<?php lenvy_icon('menu', '', 'md'); ?>
				</button>

			</div>
		</div>
	</div>
</header>

<?php
get_template_part('template-parts/header/nav-mobile');
get_template_part('template-parts/header/search-overlay');

