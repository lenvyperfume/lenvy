<?php
/**
 * Site header partial.
 *
 * @package Lenvy
 */
?>
<header id="masthead" class="site-header sticky top-0 z-40 bg-white border-b border-neutral-100 shadow-sm">
	<div class="container mx-auto px-4 max-w-screen-xl">
		<div class="flex items-center justify-between h-16 md:h-20">

			<!-- Logo -->
			<div class="site-branding flex-shrink-0">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-xl font-semibold tracking-widest uppercase text-neutral-900 hover:text-neutral-600 transition-colors" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
				<?php endif; ?>
			</div>

			<!-- Primary navigation -->
			<nav id="site-navigation" class="hidden md:flex" aria-label="<?php esc_attr_e( 'Primary Navigation', 'lenvy' ); ?>">
				<?php
				wp_nav_menu(
					[
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						'menu_class'     => 'flex items-center gap-8',
						'fallback_cb'    => false,
					]
				);
				?>
			</nav>

			<!-- Cart + mobile toggle -->
			<div class="flex items-center gap-4">
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="relative flex items-center text-neutral-900 hover:text-neutral-600 transition-colors" aria-label="<?php esc_attr_e( 'View cart', 'lenvy' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
						</svg>
						<?php
						$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
						if ( $cart_count > 0 ) :
							?>
							<span class="absolute -top-2 -right-2 flex items-center justify-center h-4 w-4 rounded-full bg-neutral-900 text-white text-[10px] font-bold leading-none">
								<?php echo esc_html( $cart_count ); ?>
							</span>
						<?php endif; ?>
					</a>
				<?php endif; ?>

				<!-- Mobile hamburger -->
				<button
					type="button"
					class="md:hidden p-2 -mr-2 text-neutral-900 hover:text-neutral-600 transition-colors"
					aria-controls="mobile-menu"
					aria-expanded="false"
					aria-label="<?php esc_attr_e( 'Toggle mobile menu', 'lenvy' ); ?>"
					data-mobile-menu-toggle
				>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 block" id="icon-open" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
					</svg>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" id="icon-close" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
					</svg>
				</button>
			</div>

		</div><!-- .flex -->
	</div><!-- .container -->

	<!-- Mobile menu -->
	<div id="mobile-menu" class="hidden md:hidden border-t border-neutral-100 bg-white" aria-hidden="true">
		<div class="container mx-auto px-4 max-w-screen-xl py-4">
			<?php
			wp_nav_menu(
				[
					'theme_location' => 'primary',
					'menu_id'        => 'mobile-primary-menu',
					'container'      => false,
					'menu_class'     => 'flex flex-col gap-1',
					'fallback_cb'    => false,
				]
			);
			?>
		</div>
	</div><!-- #mobile-menu -->
</header><!-- #masthead -->
