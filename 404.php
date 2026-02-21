<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Lenvy
 */

get_header();
?>

<main id="primary" class="site-main py-24">
	<div class="container mx-auto px-4 max-w-screen-xl text-center">
		<p class="text-xs uppercase tracking-[0.3em] text-neutral-400 mb-4">
			<?php esc_html_e( '404 — Page Not Found', 'lenvy' ); ?>
		</p>
		<h1 class="text-5xl sm:text-7xl font-light tracking-tight text-neutral-900 mb-6">
			<?php esc_html_e( 'Oops.', 'lenvy' ); ?>
		</h1>
		<p class="max-w-md mx-auto text-neutral-500 text-lg leading-relaxed mb-12">
			<?php esc_html_e( "The page you're looking for doesn't exist or has been moved.", 'lenvy' ); ?>
		</p>

		<div class="flex flex-col sm:flex-row items-center justify-center gap-4">
			<?php
			get_template_part(
				'template-parts/components/button',
				null,
				[
					'label'   => esc_html__( 'Back to Home', 'lenvy' ),
					'url'     => home_url( '/' ),
					'variant' => 'primary',
				]
			);

			if ( class_exists( 'WooCommerce' ) ) :
				get_template_part(
					'template-parts/components/button',
					null,
					[
						'label'   => esc_html__( 'Shop Fragrances', 'lenvy' ),
						'url'     => wc_get_page_permalink( 'shop' ),
						'variant' => 'outline',
					]
				);
			endif;
			?>
		</div>

		<div class="mt-16 max-w-sm mx-auto">
			<p class="text-sm text-neutral-400 mb-4"><?php esc_html_e( 'Or search for what you need:', 'lenvy' ); ?></p>
			<?php get_search_form(); ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
