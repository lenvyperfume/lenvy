<?php
/**
 * Blog archive template.
 *
 * Covers category, tag, date, and author archives for the post type.
 * Uses the content-post template part for each post card.
 *
 * @package Lenvy
 */

get_header(); ?>

<main id="primary" class="site-main py-8 lg:py-12">
	<div class="lenvy-container">

		<header class="mb-8 max-w-2xl">
			<?php get_template_part( 'template-parts/components/breadcrumb' ); ?>
			<h1 class="mt-4 text-2xl font-serif italic text-neutral-900">
				<?php echo wp_kses_post( lenvy_archive_title() ); ?>
			</h1>
			<?php
			$archive_description = get_the_archive_description();
			if ( $archive_description ) : ?>
				<div class="mt-3 text-sm text-neutral-500 leading-relaxed max-w-xl">
					<?php echo wp_kses_post( $archive_description ); ?>
				</div>
			<?php endif; ?>
		</header>

		<?php if ( have_posts() ) : ?>

			<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'templates/content-post' ); ?>
				<?php endwhile; ?>
			</div>

			<?php lenvy_pagination(); ?>

		<?php else : ?>

			<div class="py-24 text-center">
				<p class="text-xs font-medium uppercase tracking-[0.15em] text-neutral-400 mb-3">
					<?php esc_html_e( 'No posts found', 'lenvy' ); ?>
				</p>
				<p class="text-sm text-neutral-500">
					<?php esc_html_e( 'Check back later for new articles.', 'lenvy' ); ?>
				</p>
			</div>

		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>
