<?php
/**
 * Site header — two-row Skins-style layout.
 *
 * Row 1 (logo bar): logo left · actions right  — always visible
 * Row 2 (nav bar):  centred primary nav links   — desktop only (hidden < lg)
 *
 * Both rows are wrapped in a single sticky <header>.
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
	class="sticky z-[40] bg-white transition-shadow duration-200"
>

	<!-- ── Row 1: Logo bar ────────────────────────────────────────── -->
	<div class="border-b border-neutral-200">
		<div class="lenvy-container">
			<div class="grid grid-cols-[1fr_auto_1fr] items-center h-[56px] lg:h-[60px]">

				<!-- Spacer (mirrors actions width for true centering) -->
				<div></div>

				<!-- Logo -->
				<a
					href="<?php echo esc_url(home_url('/')); ?>"
					class="flex items-center justify-center"
					aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>"
				>
					<?php if ($logo_id): ?>
						<?php echo lenvy_get_image($logo_id, 'medium', 'block max-h-9 w-auto object-contain');
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					<?php else: ?>
						<span class="font-medium text-2xl tracking-tight text-neutral-900">
							<?php bloginfo('name'); ?>
						</span>
					<?php endif; ?>
				</a>

				<!-- Actions -->
				<div class="flex items-center justify-end gap-1 sm:gap-2">

					<!-- Search -->
					<button
						type="button"
						data-search-toggle
						class="p-2 text-neutral-500 hover:text-black transition-colors duration-200 rounded"
						aria-label="<?php esc_attr_e('Zoeken', 'lenvy'); ?>"
						aria-expanded="false"
					>
						<?php lenvy_icon('search', '', 'md'); ?>
					</button>

					<!-- Account -->
					<a
						href="<?php echo is_user_logged_in() ? esc_url( wc_get_account_endpoint_url( 'dashboard' ) ) : esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"
						class="p-2 text-neutral-500 hover:text-black transition-colors duration-200 rounded"
						aria-label="<?php echo is_user_logged_in() ? esc_attr__( 'Mijn account', 'lenvy' ) : esc_attr__( 'Inloggen', 'lenvy' ); ?>"
					>
						<?php lenvy_icon( 'user', '', 'md' ); ?>
					</a>

					<!-- Cart -->
					<a
						href="<?php echo esc_url($cart_url); ?>"
						data-cart-link
						class="relative p-2 text-neutral-500 hover:text-black transition-colors duration-200 rounded"
						aria-label="<?php echo esc_attr(sprintf(_n('Winkelwagen, %d artikel', 'Winkelwagen, %d artikelen', $cart_count, 'lenvy'), $cart_count)); ?>"
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

					<!-- Mobile menu toggle -->
					<button
						type="button"
						data-drawer-toggle
						class="p-2 text-neutral-500 hover:text-black transition-colors duration-200 rounded lg:hidden"
						aria-label="<?php esc_attr_e('Menu openen', 'lenvy'); ?>"
						aria-expanded="false"
						aria-controls="lenvy-mobile-drawer"
					>
						<?php lenvy_icon('menu', '', 'md'); ?>
					</button>

				</div>
			</div>
		</div>
	</div>

	<!-- ── Row 2: Navigation bar (desktop only) ──────────────────── -->
	<?php if (has_nav_menu('primary')): ?>
		<div class="hidden lg:block border-b border-neutral-100">
			<div class="lenvy-container">
				<?php get_template_part('template-parts/header/nav-primary'); ?>
			</div>
		</div>
	<?php endif; ?>

</header>

<?php
get_template_part('template-parts/header/nav-mobile');
get_template_part('template-parts/header/search-overlay');
