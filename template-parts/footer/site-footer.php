<?php
/**
 * Site footer partial.
 *
 * @package Lenvy
 */
?>
<footer id="colophon" class="site-footer bg-neutral-950 text-neutral-400 mt-auto">
	<div class="container mx-auto px-4 max-w-screen-xl">

		<!-- Top footer -->
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 py-16">

			<!-- Brand column -->
			<div class="col-span-1 lg:col-span-1">
				<?php if ( has_custom_logo() ) : ?>
					<div class="footer-logo mb-4 [&_img]:brightness-0 [&_img]:invert [&_img]:max-h-10 [&_img]:w-auto">
						<?php the_custom_logo(); ?>
					</div>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-white text-lg font-semibold tracking-widest uppercase hover:text-neutral-300 transition-colors" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
				<?php endif; ?>
				<?php
				$description = get_bloginfo( 'description', 'display' );
				if ( $description ) :
					?>
					<p class="mt-3 text-sm leading-relaxed">
						<?php echo esc_html( $description ); ?>
					</p>
				<?php endif; ?>
			</div>

			<!-- Footer navigation -->
			<nav class="col-span-1 lg:col-span-2 flex flex-col sm:flex-row gap-10" aria-label="<?php esc_attr_e( 'Footer Navigation', 'lenvy' ); ?>">
				<?php
				wp_nav_menu(
					[
						'theme_location' => 'footer',
						'menu_id'        => 'footer-menu',
						'container'      => false,
						'menu_class'     => 'flex flex-col gap-2 text-sm',
						'fallback_cb'    => false,
					]
				);
				?>
			</nav>

			<!-- Contact / info -->
			<div class="col-span-1 text-sm space-y-2">
				<h3 class="text-white font-semibold uppercase tracking-widest text-xs mb-4">
					<?php esc_html_e( 'Contact', 'lenvy' ); ?>
				</h3>
				<p><?php echo esc_html( get_option( 'admin_email' ) ); ?></p>
			</div>

		</div><!-- .grid -->

		<!-- Bottom footer -->
		<div class="border-t border-neutral-800 py-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
			<p>
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-white hover:text-neutral-300 transition-colors">
					<?php bloginfo( 'name' ); ?>
				</a>.
				<?php esc_html_e( 'All rights reserved.', 'lenvy' ); ?>
			</p>
			<p>
				<?php
				printf(
					/* translators: %s: WordPress link */
					esc_html__( 'Powered by %s', 'lenvy' ),
					'<a href="https://wordpress.org" class="text-white hover:text-neutral-300 transition-colors" target="_blank" rel="noopener">WordPress</a>'
				);
				?>
			</p>
		</div>

	</div><!-- .container -->
</footer><!-- #colophon -->
