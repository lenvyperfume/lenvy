<?php
/**
 * Site-wide search results template.
 *
 * Splits results into two sections — Products and Articles — so each
 * type gets its appropriate card. Falls back to a search form when
 * no results are found.
 *
 * @package Lenvy
 */

get_header();

global $wp_query;
?>

<main id="primary" class="site-main py-8 lg:py-12">
	<div class="lenvy-container">

		<?php // ── Search header ──────────────────────────────────────────────────── ?>
		<header class="mb-10">
			<?php get_template_part( 'template-parts/components/breadcrumb' ); ?>
			<h1 class="mt-4 text-2xl font-serif italic text-neutral-900">
				<?php
				printf(
					/* translators: %s: search term wrapped in <em> */
					'%1$s <em class="font-normal text-neutral-500">%2$s</em>',
					esc_html__( 'Results for:', 'lenvy' ),
					esc_html( get_search_query() )
				);
				?>
			</h1>
			<?php if ( $wp_query->found_posts > 0 ) : ?>
				<p class="mt-2 text-sm text-neutral-400">
					<?php
					printf(
						/* translators: %d: total number of results */
						esc_html( _n( '%d result', '%d results', (int) $wp_query->found_posts, 'lenvy' ) ),
						(int) $wp_query->found_posts
					);
					?>
				</p>
			<?php endif; ?>
		</header>

		<?php if ( have_posts() ) : ?>

			<?php
			// ── Partition results by post type ────────────────────────────────────
			$product_ids = [];
			$post_ids    = [];

			while ( have_posts() ) :
				the_post();
				if ( 'product' === get_post_type() ) {
					$product_ids[] = get_the_ID();
				} else {
					$post_ids[] = get_the_ID();
				}
			endwhile;
			?>

			<?php // ── Products section ─────────────────────────────────────────── ?>
			<?php if ( ! empty( $product_ids ) ) : ?>
				<section class="mb-14">
					<h2 class="text-xs font-medium uppercase tracking-[0.15em] text-neutral-400 mb-6 pb-3 border-b border-neutral-100">
						<?php esc_html_e( 'Products', 'lenvy' ); ?>
					</h2>
					<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-10">
						<?php foreach ( $product_ids as $product_id ) : ?>
							<?php get_template_part( 'template-parts/components/product-card', null, [ 'product_id' => $product_id ] ); ?>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endif; ?>

			<?php // ── Articles section ─────────────────────────────────────────── ?>
			<?php if ( ! empty( $post_ids ) ) : ?>
				<section class="mb-14">
					<h2 class="text-xs font-medium uppercase tracking-[0.15em] text-neutral-400 mb-6 pb-3 border-b border-neutral-100">
						<?php esc_html_e( 'Articles', 'lenvy' ); ?>
					</h2>
					<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
						<?php foreach ( $post_ids as $post_id ) : ?>
							<?php
							// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
							setup_postdata( $GLOBALS['post'] = get_post( $post_id ) );
							get_template_part( 'templates/content-post' );
							?>
						<?php endforeach; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</section>
			<?php endif; ?>

			<?php lenvy_pagination(); ?>

		<?php else : ?>

			<?php // ── No results ───────────────────────────────────────────────── ?>
			<div class="py-24 text-center">
				<p class="text-xs font-medium uppercase tracking-[0.15em] text-neutral-400 mb-3">
					<?php esc_html_e( 'No results', 'lenvy' ); ?>
				</p>
				<p class="text-sm text-neutral-500 mb-8 max-w-xs mx-auto leading-relaxed">
					<?php esc_html_e( 'Try a different search term or browse the shop below.', 'lenvy' ); ?>
				</p>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>"
				   class="inline-block text-xs font-medium uppercase tracking-widest border border-neutral-900 text-neutral-900 px-7 py-3 hover:bg-neutral-900 hover:text-white transition-colors duration-150">
					<?php esc_html_e( 'Browse shop', 'lenvy' ); ?>
				</a>
			</div>

		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>
