<?php
/**
 * Site header — matches Homepage.html design spec.
 *
 * Announcement bar is rendered by `template-parts/components/usp-bar.php`
 * (dark strip with lavender dots) immediately above this element.
 *
 * Desktop row 1 (72px):
 *   grid-cols-[1fr_auto_1fr]
 *   LEFT  — search pill (240px min-width, rounded-full, opens search overlay)
 *   MID   — wordmark "Lenvy" with lavender dot-mark
 *   RIGHT — wishlist · account · cart (40×40 round icon buttons)
 *
 * Desktop row 2:
 *   centred primary nav, 36px gap, lavender active/hover underline,
 *   "Sale" link in burgundy (#b8005a).
 *
 * Mobile:
 *   drawer-toggle · logo · search-icon · cart
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$logo_id     = lenvy_field('lenvy_site_logo', 'options');
$cart_count  = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$cart_url    = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/');
$account_url = is_user_logged_in()
	? ( function_exists('wc_get_account_endpoint_url') ? wc_get_account_endpoint_url('dashboard') : home_url('/my-account/') )
	: ( function_exists('lenvy_get_account_choice_url') ? lenvy_get_account_choice_url() : home_url('/my-account/') );
$wishlist_url = home_url('/verlanglijst/');
?>

<?php get_template_part('template-parts/components/usp-bar'); ?>

<header
	data-header
	class="site-header sticky z-[40] bg-white border-b border-neutral-200 transition-shadow duration-200"
>

	<!-- ── Row 1: search · logo · actions ─────────────────────────── -->
	<div class="lenvy-container">
		<div class="grid grid-cols-[auto_1fr_auto] lg:grid-cols-[1fr_auto_1fr] items-center h-[60px] lg:h-[72px] gap-3">

			<!-- LEFT -->
			<div class="flex items-center gap-5">

				<!-- Mobile drawer toggle -->
				<button
					type="button"
					data-drawer-toggle
					class="lg:hidden p-2 -ml-2 text-neutral-900 hover:text-black"
					aria-label="<?php esc_attr_e('Menu openen', 'lenvy'); ?>"
					aria-expanded="false"
					aria-controls="lenvy-mobile-drawer"
				>
					<?php lenvy_icon('menu', '', 'md'); ?>
				</button>

				<!-- Desktop search pill -->
				<button
					type="button"
					data-search-trigger
					class="hidden lg:inline-flex items-center gap-2.5 px-3.5 py-[9px] min-w-[240px] rounded-full border border-neutral-200 hover:border-neutral-400 text-[13px] text-neutral-500 transition-colors duration-200"
					aria-label="<?php esc_attr_e('Zoeken', 'lenvy'); ?>"
					aria-expanded="false"
				>
					<svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
						<circle cx="11" cy="11" r="7"/>
						<path d="m21 21-4.3-4.3"/>
					</svg>
					<span class="truncate">
						<?php esc_html_e('Zoek naar geuren, merken…', 'lenvy'); ?>
					</span>
				</button>

			</div>

			<!-- MIDDLE — logo -->
			<a
				href="<?php echo esc_url(home_url('/')); ?>"
				class="inline-flex items-center justify-center"
				aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>"
			>
				<?php if ($logo_id): ?>
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo lenvy_get_image($logo_id, 'medium', 'block max-h-10 w-auto object-contain');
					?>
				<?php else: ?>
					<span class="relative inline-flex items-baseline font-medium text-2xl lg:text-[26px] tracking-[-0.04em] text-neutral-900 leading-none pr-3">
						<?php bloginfo('name'); ?>
						<span
							class="absolute right-0 w-1.5 h-1.5 rounded-full bg-primary"
							style="top:-2px;"
							aria-hidden="true"
						></span>
					</span>
				<?php endif; ?>
			</a>

			<!-- RIGHT — actions -->
			<div class="flex items-center justify-end gap-1">

				<!-- Mobile-only search icon -->
				<button
					type="button"
					data-search-trigger
					class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-full text-neutral-900 hover:bg-neutral-50 transition-colors duration-200"
					aria-label="<?php esc_attr_e('Zoeken', 'lenvy'); ?>"
					aria-expanded="false"
				>
					<?php lenvy_icon('search', '', 'md'); ?>
				</button>

				<!-- Wishlist (desktop only) -->
				<a
					href="<?php echo esc_url($wishlist_url); ?>"
					class="hidden lg:inline-flex items-center justify-center w-10 h-10 rounded-full text-neutral-900 hover:bg-neutral-50 transition-colors duration-200"
					aria-label="<?php esc_attr_e('Verlanglijst', 'lenvy'); ?>"
				>
					<?php lenvy_icon('heart', '', 'md'); ?>
				</a>

				<!-- Account (desktop only) -->
				<a
					href="<?php echo esc_url($account_url); ?>"
					class="hidden lg:inline-flex items-center justify-center w-10 h-10 rounded-full text-neutral-900 hover:bg-neutral-50 transition-colors duration-200"
					aria-label="<?php echo is_user_logged_in() ? esc_attr__('Mijn account', 'lenvy') : esc_attr__('Inloggen', 'lenvy'); ?>"
				>
					<?php lenvy_icon('user', '', 'md'); ?>
				</a>

				<!-- Cart -->
				<a
					href="<?php echo esc_url($cart_url); ?>"
					data-cart-link
					class="relative inline-flex items-center justify-center w-10 h-10 rounded-full text-neutral-900 hover:bg-neutral-50 transition-colors duration-200"
					aria-label="<?php echo esc_attr(sprintf(_n('Winkelwagen, %d artikel', 'Winkelwagen, %d artikelen', $cart_count, 'lenvy'), $cart_count)); ?>"
				>
					<?php lenvy_icon('cart', '', 'md'); ?>
					<span
						data-cart-count
						class="absolute top-1.5 right-1.5 min-w-4 h-4 px-1 inline-flex items-center justify-center rounded-full bg-neutral-950 text-white text-[10px] font-semibold leading-none"
						<?php if ($cart_count === 0): ?>style="display:none;"<?php endif; ?>
					>
						<?php echo esc_html($cart_count > 99 ? '99+' : $cart_count); ?>
					</span>
				</a>

			</div>
		</div>
	</div>

	<!-- ── Row 2: primary nav (desktop) ─────────────────────────────── -->
	<div class="hidden lg:block">
		<div class="lenvy-container">
			<?php get_template_part('template-parts/header/nav-primary'); ?>
		</div>
	</div>

</header>

<?php
get_template_part('template-parts/header/nav-mobile');
