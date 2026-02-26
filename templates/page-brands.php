<?php
/**
 * Template Name: Brands
 *
 * Alphabetical A-Z directory of all product brands with instant search,
 * featured brands strip, and sticky letter bar.
 *
 * @package Lenvy
 */

defined( 'ABSPATH' ) || exit();

get_header();

// ── Data ─────────────────────────────────────────────────────────────────────
$brands   = lenvy_get_filter_terms( 'product_brand' );
$featured = [];
$grouped  = [];
$active_letters = [];

// Real product counts — $term->count can be stale for custom taxonomies.
global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery
$count_rows = $wpdb->get_results(
	"SELECT tt.term_id, COUNT(*) AS cnt
	 FROM {$wpdb->term_relationships} tr
	 INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
	 INNER JOIN {$wpdb->posts} p ON tr.object_id = p.ID
	 WHERE tt.taxonomy = 'product_brand'
	   AND p.post_status = 'publish'
	   AND p.post_type = 'product'
	 GROUP BY tt.term_id",
	OBJECT_K,
);
$brand_counts = [];
foreach ( $count_rows as $row ) {
	$brand_counts[ (int) $row->term_id ] = (int) $row->cnt;
}

foreach ( $brands as $term ) {
	$first = mb_strtoupper( mb_substr( $term->name, 0, 1 ) );

	// Non-alpha characters bucket under '#'.
	if ( ! preg_match( '/\p{L}/u', $first ) ) {
		$first = '#';
	}

	$grouped[ $first ][] = $term;
	$active_letters[ $first ] = true;

	if ( lenvy_field( 'lenvy_brand_is_featured', 'term_' . $term->term_id ) ) {
		$featured[] = $term;
	}
}

// Sort letter groups alphabetically, '#' last.
uksort( $grouped, function ( $a, $b ) {
	if ( $a === '#' ) return 1;
	if ( $b === '#' ) return -1;
	return strcmp( $a, $b );
} );

// All possible letters for the A-Z bar.
$all_letters = array_merge( range( 'A', 'Z' ), [ '#' ] );
?>

<main id="primary" class="py-12 lg:py-20">
	<div class="lenvy-container">

		<?php get_template_part( 'template-parts/components/breadcrumb' ); ?>

		<!-- Hero -->
		<header class="mt-4 mb-10">
			<span class="block text-[11px] uppercase tracking-widest text-neutral-400 mb-2">
				<?php esc_html_e( 'Merken', 'lenvy' ); ?>
			</span>
			<h1 class="text-2xl md:text-3xl font-serif italic text-neutral-900">
				<?php esc_html_e( 'Alle Merken', 'lenvy' ); ?>
			</h1>
			<?php if ( get_the_content() ): ?>
				<p class="mt-3 text-sm text-neutral-500 max-w-2xl leading-relaxed">
					<?php echo wp_kses_post( get_the_content() ); ?>
				</p>
			<?php endif; ?>
		</header>

		<!-- Search -->
		<div class="mb-10">
			<div class="relative max-w-md">
				<input
					type="search"
					data-brands-search
					placeholder="<?php esc_attr_e( 'Zoek een merk...', 'lenvy' ); ?>"
					class="w-full border border-neutral-200 text-sm text-neutral-800 placeholder:text-neutral-400 py-3 pl-10 pr-4 focus:outline-none focus:border-neutral-400 transition-colors duration-200"
				>
				<span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none">
					<?php lenvy_icon( 'search', '', 'sm' ); ?>
				</span>
			</div>
		</div>

		<!-- No results message (hidden by default) -->
		<p class="hidden text-sm text-neutral-500 py-12 text-center" data-brands-no-results>
			<?php esc_html_e( 'Geen merken gevonden', 'lenvy' ); ?>
		</p>

	</div><!-- .lenvy-container -->

	<?php // ── Featured brands ──────────────────────────────────────────────────
	if ( $featured ): ?>
	<section class="mb-12" data-brands-featured>
		<div class="lenvy-container">
			<h2 class="text-xs font-medium uppercase tracking-widest text-neutral-400 mb-5">
				<?php esc_html_e( 'Uitgelichte merken', 'lenvy' ); ?>
			</h2>
		</div>
		<div class="lenvy-container">
			<div class="flex gap-4 overflow-x-auto scrollbar-hide pb-2 -mb-2">
				<?php foreach ( $featured as $term ):
					$logo = lenvy_field( 'lenvy_brand_logo', 'term_' . $term->term_id );
					$link = get_term_link( $term, 'product_brand' );
					if ( is_wp_error( $link ) ) continue;
				?>
				<a
					href="<?php echo esc_url( $link ); ?>"
					class="group flex flex-col items-center justify-center shrink-0 w-[140px] md:w-[160px] border border-neutral-200 bg-white p-5 transition-all duration-200 hover:border-neutral-300"
				>
					<?php if ( $logo ): ?>
						<div class="h-8 flex items-center justify-center mb-3">
							<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo lenvy_get_image( $logo, 'thumbnail', 'max-h-8 w-auto object-contain grayscale group-hover:grayscale-0 transition-all duration-200' ); ?>
						</div>
					<?php else: ?>
						<div class="h-8 flex items-center justify-center mb-3">
							<span class="text-sm font-medium text-neutral-400 group-hover:text-neutral-800 transition-colors duration-200">
								<?php echo esc_html( $term->name ); ?>
							</span>
						</div>
					<?php endif; ?>
					<span class="text-xs text-neutral-600 group-hover:text-black transition-colors duration-200 text-center leading-tight">
						<?php echo esc_html( $term->name ); ?>
					</span>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php // ── A-Z letter bar ───────────────────────────────────────────────────── ?>
	<nav class="sticky top-[68px] z-20 bg-white border-b border-neutral-100" data-brands-az-bar aria-label="<?php esc_attr_e( 'Letter navigatie', 'lenvy' ); ?>">
		<div class="lenvy-container">
			<div class="flex items-center gap-1 overflow-x-auto scrollbar-hide py-3">
				<?php foreach ( $all_letters as $letter ):
					$is_active = isset( $active_letters[ $letter ] );
				?>
					<?php if ( $is_active ): ?>
					<a
						href="#letter-<?php echo esc_attr( $letter ); ?>"
						class="shrink-0 w-8 h-8 flex items-center justify-center text-sm font-medium text-neutral-800 hover:text-black transition-colors duration-200"
						data-letter-link="<?php echo esc_attr( $letter ); ?>"
					>
						<?php echo esc_html( $letter ); ?>
					</a>
					<?php else: ?>
					<span class="shrink-0 w-8 h-8 flex items-center justify-center text-sm text-neutral-200 cursor-default">
						<?php echo esc_html( $letter ); ?>
					</span>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</nav>

	<?php // ── A-Z directory ────────────────────────────────────────────────────── ?>
	<div class="lenvy-container mt-10" data-brands-directory>

		<?php foreach ( $grouped as $letter => $terms ): ?>
		<div class="mb-12" id="letter-<?php echo esc_attr( $letter ); ?>" data-letter-group="<?php echo esc_attr( $letter ); ?>">
			<h2 class="text-2xl font-serif italic text-neutral-900 border-b border-neutral-100 pb-3 mb-6">
				<?php echo esc_html( $letter ); ?>
			</h2>
			<ul class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-3">
				<?php foreach ( $terms as $term ):
					$link = get_term_link( $term, 'product_brand' );
					if ( is_wp_error( $link ) ) continue;
				?>
				<li data-brand-name="<?php echo esc_attr( mb_strtolower( $term->name ) ); ?>">
					<a
						href="<?php echo esc_url( $link ); ?>"
						class="text-sm text-neutral-700 hover:text-black transition-colors duration-200"
					>
						<?php echo esc_html( $term->name ); ?>
						<span class="text-xs text-neutral-400 ml-1">(<?php echo $brand_counts[ $term->term_id ] ?? 0; ?>)</span>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endforeach; ?>

	</div><!-- [data-brands-directory] -->

	<?php // ── Page content (optional SEO text) ──────────────────────────────────
	$page_content = get_the_content();
	if ( $page_content ): ?>
	<div class="lenvy-container mt-16 pt-12 border-t border-neutral-100">
		<div class="entry-content max-w-3xl">
			<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo apply_filters( 'the_content', $page_content ); ?>
		</div>
	</div>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
