<?php
/**
 * Site header — announcement bar + sticky header + mobile drawer + search overlay.
 *
 * Structure:
 *   [data-announcement]  — optional, dismissible, scrolls away
 *   <header data-header> — sticky bar; top offset for admin bar via _header.scss
 *     .lenvy-container
 *       logo | primary-nav (desktop) | actions (search + cart + hamburger)
 *   nav-mobile.php       — backdrop + slide-in drawer
 *   search-overlay.php   — full-screen search panel
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$announcement_enabled = lenvy_field('lenvy_announcement_bar_enabled', 'options');
$announcement_text = lenvy_field('lenvy_announcement_bar_text', 'options');
$announcement_link = lenvy_field('lenvy_announcement_bar_link', 'options');
$logo_id = lenvy_field('lenvy_site_logo', 'options');
$cart_count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/');
?>

<?php if ($announcement_enabled && $announcement_text): ?>
<div
	data-announcement
	class="bg-black text-white text-center text-sm font-medium py-2 px-10 relative"
>
	<?php if (!empty($announcement_link['url'])): ?>
		<a
			href="<?php echo esc_url($announcement_link['url']); ?>"
			<?php if (($announcement_link['target'] ?? '') === '_blank'): ?>target="_blank" rel="noopener"<?php endif; ?>
			class="hover:underline underline-offset-2"
		><?php echo esc_html($announcement_text); ?></a>
	<?php else: ?>
		<span><?php echo esc_html($announcement_text); ?></span>
	<?php endif; ?>
	<button
		type="button"
		data-dismiss-announcement
		class="absolute right-4 top-1/2 -translate-y-1/2 text-white/60 hover:text-white transition-colors duration-150"
		aria-label="<?php esc_attr_e('Dismiss announcement', 'lenvy'); ?>"
	>
		<?php lenvy_icon('close', '', 'xs'); ?>
	</button>
</div>
<?php endif; ?>

<header
	data-header
	class="sticky z-[40] bg-white border-b border-neutral-100 transition-shadow duration-300"
>
	<div class="lenvy-container">
		<!--
			Three-column CSS grid: logo (left) | nav (center, auto-width) | actions (right).
			The 1fr / auto / 1fr split guarantees the nav is always truly centred
			regardless of logo or action widths — same pattern as Douglas / Dior.
		-->
		<div class="grid grid-cols-[1fr_auto_1fr] items-center h-[68px]">

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
					<span class="font-serif italic text-xl tracking-tight text-neutral-900">
						<?php bloginfo('name'); ?>
					</span>
				<?php endif; ?>
			</a>

			<!-- Col 2 — Primary navigation (desktop only, always a grid cell) -->
			<div class="flex items-center">
				<?php get_template_part('template-parts/header/nav-primary'); ?>
			</div>

			<!-- Col 3 — Right-side actions -->
			<div class="flex items-center justify-end gap-0.5 sm:gap-1">

				<!-- Search -->
				<button
					type="button"
					data-search-toggle
					class="p-2 text-neutral-500 hover:text-black transition-colors duration-150 rounded"
					aria-label="<?php esc_attr_e('Open search', 'lenvy'); ?>"
					aria-expanded="false"
				>
					<?php lenvy_icon('search', '', 'md'); ?>
				</button>

				<!-- Account -->
				<a
					href="<?php echo is_user_logged_in() ? esc_url( wc_get_account_endpoint_url( 'dashboard' ) ) : esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"
					class="p-2 text-neutral-500 hover:text-black transition-colors duration-150 rounded"
					aria-label="<?php echo is_user_logged_in() ? esc_attr__( 'My account', 'lenvy' ) : esc_attr__( 'Log in', 'lenvy' ); ?>"
				>
					<?php lenvy_icon( 'user', '', 'md' ); ?>
				</a>

				<!-- Cart -->
				<a
					href="<?php echo esc_url($cart_url); ?>"
					data-cart-link
					class="relative p-2 text-neutral-500 hover:text-black transition-colors duration-150 rounded"
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
					class="p-2 text-neutral-500 hover:text-black transition-colors duration-150 rounded lg:hidden"
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

