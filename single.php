<?php
/**
 * Blog post single template.
 *
 * @package Lenvy
 */

get_header(); ?>

<main id="primary" class="site-main py-12 lg:py-16">
	<div class="lenvy-container">
		<div class="max-w-2xl mx-auto">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/components/breadcrumb' ); ?>

				<article <?php post_class( 'mt-6' ); ?>>

					<?php
					// ── Post meta ─────────────────────────────────────────────────────────────
					$categories = get_the_category();
					?>
					<div class="flex items-center gap-3 text-xs uppercase tracking-widest text-neutral-400 mb-6">
						<?php if ( $categories ) : ?>
							<a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"
							   class="text-neutral-800 hover:text-neutral-500 transition-colors duration-150">
								<?php echo esc_html( $categories[0]->name ); ?>
							</a>
							<span class="text-neutral-200" aria-hidden="true">/</span>
						<?php endif; ?>
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
							<?php echo esc_html( get_the_date() ); ?>
						</time>
					</div>

					<?php // ── Title ────────────────────────────────────────────────────────── ?>
					<h1 class="text-3xl lg:text-5xl font-serif italic text-neutral-900 leading-tight mb-8">
						<?php the_title(); ?>
					</h1>

					<?php // ── Featured image ─────────────────────────────────────────────── ?>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="mb-10 overflow-hidden">
							<?php the_post_thumbnail( 'full', [ 'class' => 'w-full max-h-[520px] object-cover' ] ); ?>
						</div>
					<?php endif; ?>

					<?php // ── Content ────────────────────────────────────────────────────── ?>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>

					<?php // ── Post navigation ────────────────────────────────────────────── ?>
					<nav class="mt-12 pt-8 border-t border-neutral-100" aria-label="<?php esc_attr_e( 'Post navigation', 'lenvy' ); ?>">
						<div class="flex justify-between gap-4 text-sm">

							<div class="flex-1">
								<?php $prev_post = get_previous_post(); ?>
								<?php if ( $prev_post ) : ?>
									<a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>"
									   class="group flex flex-col gap-1">
										<span class="text-xs text-neutral-400 uppercase tracking-widest group-hover:text-neutral-600 transition-colors duration-150">
											<?php esc_html_e( 'Previous', 'lenvy' ); ?>
										</span>
										<span class="text-neutral-900 group-hover:text-neutral-600 transition-colors duration-150">
											<?php echo esc_html( $prev_post->post_title ); ?>
										</span>
									</a>
								<?php endif; ?>
							</div>

							<div class="flex-1 text-right">
								<?php $next_post = get_next_post(); ?>
								<?php if ( $next_post ) : ?>
									<a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>"
									   class="group flex flex-col gap-1 items-end">
										<span class="text-xs text-neutral-400 uppercase tracking-widest group-hover:text-neutral-600 transition-colors duration-150">
											<?php esc_html_e( 'Next', 'lenvy' ); ?>
										</span>
										<span class="text-neutral-900 group-hover:text-neutral-600 transition-colors duration-150">
											<?php echo esc_html( $next_post->post_title ); ?>
										</span>
									</a>
								<?php endif; ?>
							</div>

						</div>
					</nav>

				</article>

			<?php endwhile; ?>

		</div>
	</div>
</main>

<?php get_footer(); ?>
