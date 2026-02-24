<?php
/**
 * Taxonomy / attribute filter — checkbox list.
 *
 * Usage:
 *   get_template_part('template-parts/shop/filter-taxonomy', null, [
 *     'taxonomy'  => 'product_brand',   // taxonomy slug
 *     'query_var' => 'filter_brand',    // URL query var name
 *     'label'     => 'Brand',           // accordion heading
 *     'open'      => true,
 *   ]);
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$taxonomy  = $args['taxonomy']  ?? '';
$query_var = $args['query_var'] ?? '';
$label     = $args['label']     ?? '';
$open      = $args['open']      ?? true;

if ( ! $taxonomy || ! $query_var ) {
	return;
}

$terms = get_terms( [
	'taxonomy'   => $taxonomy,
	'hide_empty' => true,
	'orderby'    => 'name',
	'order'      => 'ASC',
] );

if ( is_wp_error( $terms ) || empty( $terms ) ) {
	return;
}

// Active slugs from URL — comma-separated.
// phpcs:ignore WordPress.Security.NonceVerification
$raw_active   = isset( $_GET[ $query_var ] ) ? sanitize_text_field( wp_unslash( $_GET[ $query_var ] ) ) : '';
$active_slugs = array_filter( array_map( 'trim', explode( ',', $raw_active ) ) );

ob_start();
?>
<ul class="space-y-2.5" role="list">
	<?php foreach ( $terms as $term ) : ?>
		<?php
		$checked  = in_array( $term->slug, $active_slugs, true );
		$input_id = 'filter-' . esc_attr( $query_var ) . '-' . esc_attr( $term->slug );
		?>
		<li class="flex items-center gap-2.5">
			<input
				type="checkbox"
				id="<?php echo $input_id; ?>"
				name="<?php echo esc_attr( $query_var ); ?>[]"
				value="<?php echo esc_attr( $term->slug ); ?>"
				class="w-3.5 h-3.5 border border-neutral-300 accent-black cursor-pointer"
				<?php checked( $checked ); ?>
				data-filter-checkbox
			>
			<label
				for="<?php echo $input_id; ?>"
				class="flex items-center justify-between flex-1 text-sm text-neutral-700 cursor-pointer hover:text-black transition-colors duration-150"
			>
				<span><?php echo esc_html( $term->name ); ?></span>
				<span class="text-xs text-neutral-400">(<?php echo esc_html( $term->count ); ?>)</span>
			</label>
		</li>
	<?php endforeach; ?>
</ul>
<?php
$content = ob_get_clean();

get_template_part( 'template-parts/shop/filter-accordion', null, compact( 'label', 'open', 'content' ) + [ 'name' => $query_var ] );
