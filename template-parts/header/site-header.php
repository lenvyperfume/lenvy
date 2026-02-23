<?php
/**
 * Site header partial — Douglas-inspired luxury nav.
 *
 * Structure:
 *   1. Sticky main bar  — logo | desktop nav | utility icons
 *   2. Search overlay   — fullscreen, ESC to close
 *   3. Drawer backdrop  — click to close mobile drawer
 *   4. Mobile drawer    — off-canvas from left, accordion sub-menus
 *
 * @package Lenvy
 */
?>

<header id="masthead" class="site-header sticky top-0 z-40 bg-white transition-shadow duration-300" data-header>
	<div class="border-b border-neutral-100">
		<div class="mx-auto px-6 max-w-7xl">
			<div class="flex items-center h-16 md:h-20 gap-6 lg:gap-10">

				<!-- ── Logo ──────────────────────────────────────────────── -->
				<div class="site-branding shrink-0 [&_img]:!h-6 md:[&_img]:!h-8 [&_img]:!w-auto [&_img]:!max-w-none">
					<?php if (has_custom_logo()): ?>
						<?php the_custom_logo(); ?>
					<?php else: ?>
						<a href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
							<img
								src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo.png'); ?>"
								alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
								class="block"
							>
						</a>
					<?php endif; ?>
				</div>

				<!-- ── Desktop nav ───────────────────────────────────────── -->
				<nav
					id="site-navigation"
					class="hidden md:flex items-center flex-1"
					aria-label="<?php esc_attr_e('Primary Navigation', 'lenvy'); ?>"
				>
					<?php wp_nav_menu([
     	'theme_location' => 'primary',
     	'menu_id' => 'primary-menu',
     	'container' => false,
     	'menu_class' => 'flex items-center',
     	'fallback_cb' => false,
     	'walker' => new Lenvy_Nav_Walker(),
     ]); ?>
				</nav>

				<!-- ── Utility icons ─────────────────────────────────────── -->
				<div class="flex items-center gap-0.5 ml-auto md:ml-0 shrink-0">

					<!-- Search -->
					<button
						type="button"
						class="p-3 text-brand-950 hover:text-brand-700 transition-colors"
						data-search-toggle
						aria-label="<?php esc_attr_e('Search', 'lenvy'); ?>"
					>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 0z"/>
						</svg>
					</button>

					<?php if (class_exists('WooCommerce')): ?>

						<!-- Account -->
						<a
							href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
							class="hidden md:flex p-3 text-brand-950 hover:text-brand-700 transition-colors"
							aria-label="<?php esc_attr_e('My Account', 'lenvy'); ?>"
						>
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
							</svg>
						</a>

						<!-- Cart -->
						<?php $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
						<a
							href="<?php echo esc_url(wc_get_cart_url()); ?>"
							class="relative p-3 text-brand-950 hover:text-brand-700 transition-colors"
							aria-label="<?php esc_attr_e('View cart', 'lenvy'); ?>"
						>
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
							</svg>
							<?php if ($cart_count > 0): ?>
								<span class="absolute top-1.5 right-1.5 flex items-center justify-center h-4 w-4 rounded-full bg-brand-700 text-white text-[9px] font-bold leading-none">
									<?php echo esc_html($cart_count); ?>
								</span>
							<?php endif; ?>
						</a>

					<?php endif; ?>

					<!-- Mobile hamburger -->
					<button
						type="button"
						class="md:hidden p-3 text-brand-950 hover:text-brand-700 transition-colors"
						data-drawer-toggle
						aria-controls="mobile-drawer"
						aria-expanded="false"
						aria-label="<?php esc_attr_e('Open menu', 'lenvy'); ?>"
					>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
						</svg>
					</button>

				</div>
			</div>
		</div>
	</div>
</header><!-- #masthead -->

<!-- ── Search overlay ──────────────────────────────────────────────────────── -->
<div
	id="search-overlay"
	class="search-overlay fixed inset-0 z-50 bg-white/95 backdrop-blur-sm flex flex-col items-center pt-24 px-6 opacity-0 pointer-events-none transition-opacity duration-200"
	data-search-overlay
	aria-hidden="true"
	role="dialog"
	aria-label="<?php esc_attr_e('Site search', 'lenvy'); ?>"
>
	<button
		type="button"
		class="absolute top-6 right-6 p-2 text-neutral-400 hover:text-brand-700 transition-colors"
		data-search-close
		aria-label="<?php esc_attr_e('Close search', 'lenvy'); ?>"
	>
		<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
			<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
		</svg>
	</button>

	<p class="text-[10px] uppercase tracking-[0.25em] text-neutral-400 mb-8">
		<?php esc_html_e('What are you looking for?', 'lenvy'); ?>
	</p>

	<div class="w-full max-w-xl">
		<?php get_search_form(); ?>
	</div>

	<p class="text-[10px] text-neutral-300 tracking-widest uppercase mt-6">
		<?php esc_html_e('Press ESC to close', 'lenvy'); ?>
	</p>
</div>

<!-- ── Mobile drawer backdrop ─────────────────────────────────────────────── -->
<div
	id="drawer-backdrop"
	class="fixed inset-0 z-40 bg-brand-950/40 opacity-0 pointer-events-none transition-opacity duration-300"
	data-drawer-backdrop
	aria-hidden="true"
></div>

<!-- ── Mobile off-canvas drawer ──────────────────────────────────────────── -->
<div
	id="mobile-drawer"
	class="fixed top-0 left-0 z-50 h-full w-[85vw] max-w-xs bg-white shadow-2xl flex flex-col -translate-x-full transition-transform duration-300 ease-in-out"
	style="transform:translateX(-100%)"
	data-drawer
	aria-hidden="true"
	role="dialog"
	aria-label="<?php esc_attr_e('Mobile navigation', 'lenvy'); ?>"
>
	<!-- Drawer header -->
	<div class="flex items-center justify-between px-6 py-5 border-b border-neutral-100 shrink-0">
		<a href="<?php echo esc_url(home_url('/')); ?>" rel="home" tabindex="-1">
			<?php if (has_custom_logo()): ?>
				<?php the_custom_logo(); ?>
			<?php else: ?>
				<img
					src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo.png'); ?>"
					alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
					class="h-8 w-auto"
				>
			<?php endif; ?>
		</a>

		<button
			type="button"
			class="p-2 text-neutral-400 hover:text-brand-700 transition-colors"
			data-drawer-close
			aria-label="<?php esc_attr_e('Close menu', 'lenvy'); ?>"
		>
			<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
				<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
			</svg>
		</button>
	</div>

	<!-- Drawer nav -->
	<nav class="flex-1 overflow-y-auto" aria-label="<?php esc_attr_e('Mobile Navigation', 'lenvy'); ?>">
		<?php wp_nav_menu([
  	'theme_location' => 'primary',
  	'menu_id' => 'mobile-primary-menu',
  	'container' => false,
  	'menu_class' => 'divide-y-0',
  	'fallback_cb' => false,
  	'walker' => new Lenvy_Mobile_Nav_Walker(),
  ]); ?>
	</nav>

	<!-- Drawer footer -->
	<?php if (class_exists('WooCommerce')): ?>
		<div class="border-t border-neutral-100 px-6 py-5 shrink-0 flex items-center gap-5">
			<a
				href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
				class="flex items-center gap-2 text-xs uppercase tracking-widest font-medium text-brand-950 hover:text-brand-700 transition-colors"
			>
				<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
				</svg>
				<?php esc_html_e('My Account', 'lenvy'); ?>
			</a>
		</div>
	<?php endif; ?>
</div>
