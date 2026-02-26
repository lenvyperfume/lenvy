<?php
/**
 * 404 — Not Found template.
 *
 * @package Lenvy
 */

get_header(); ?>

<main id="primary" class="site-main py-16 lg:py-24">
	<div class="lenvy-container">
		<div class="max-w-lg mx-auto text-center">

			<p class="text-xs font-medium uppercase tracking-[0.2em] text-neutral-400 mb-4">
				<?php esc_html_e( '404', 'lenvy' ); ?>
			</p>

			<h1 class="text-3xl lg:text-4xl font-serif italic text-neutral-900 mb-5">
				<?php esc_html_e( 'Pagina niet gevonden', 'lenvy' ); ?>
			</h1>

			<p class="text-sm text-neutral-500 leading-relaxed mb-10">
				<?php esc_html_e( 'De pagina die je zoekt bestaat niet meer of is verplaatst. Ga terug naar de winkel of zoek een product.', 'lenvy' ); ?>
			</p>

			<div class="flex flex-col sm:flex-row gap-3 justify-center">
				<a
					href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>"
					class="inline-block bg-primary text-black text-xs font-medium uppercase tracking-widest px-8 py-4 hover:bg-primary-hover transition-colors duration-200"
				>
					<?php esc_html_e( 'Naar de winkel', 'lenvy' ); ?>
				</a>
				<a
					href="<?php echo esc_url( home_url( '/' ) ); ?>"
					class="inline-block border border-neutral-300 text-neutral-700 text-xs font-medium uppercase tracking-widest px-8 py-4 hover:border-neutral-900 hover:text-neutral-900 transition-colors duration-200"
				>
					<?php esc_html_e( 'Terug naar home', 'lenvy' ); ?>
				</a>
			</div>

		</div>
	</div>
</main>

<?php get_footer(); ?>
