<?php
/**
 * Price range filter — dual-handle slider.
 *
 * Reads min/max from all published products (cached).
 * Current range read from URL: min_price / max_price query vars.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$label = $args['label'] ?? __( 'Price', 'lenvy' );
$open  = $args['open']  ?? true;

[ $global_min, $global_max ] = lenvy_get_min_max_price();

if ( $global_min >= $global_max ) {
	return;
}

// phpcs:ignore WordPress.Security.NonceVerification
$current_min = isset( $_GET['min_price'] ) ? (float) $_GET['min_price'] : $global_min;
// phpcs:ignore WordPress.Security.NonceVerification
$current_max = isset( $_GET['max_price'] ) ? (float) $_GET['max_price'] : $global_max;

$current_min = max( $global_min, min( $current_min, $global_max ) );
$current_max = min( $global_max, max( $current_max, $global_min ) );

ob_start();
?>
<div
	class="lenvy-price-slider"
	data-price-slider
	data-min="<?php echo esc_attr( $global_min ); ?>"
	data-max="<?php echo esc_attr( $global_max ); ?>"
	data-current-min="<?php echo esc_attr( $current_min ); ?>"
	data-current-max="<?php echo esc_attr( $current_max ); ?>"
>
	<!-- Track -->
	<div class="relative h-1 bg-neutral-200 my-5 mx-2" data-slider-track>
		<div class="absolute h-full bg-black" data-slider-range></div>
		<!-- Min thumb -->
		<button
			type="button"
			class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 w-4 h-4 bg-black rounded-full border-2 border-white shadow focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-1 cursor-grab active:cursor-grabbing"
			data-slider-thumb="min"
			aria-label="<?php esc_attr_e( 'Minimum price', 'lenvy' ); ?>"
			aria-valuemin="<?php echo esc_attr( $global_min ); ?>"
			aria-valuemax="<?php echo esc_attr( $global_max ); ?>"
			aria-valuenow="<?php echo esc_attr( $current_min ); ?>"
			role="slider"
		></button>
		<!-- Max thumb -->
		<button
			type="button"
			class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 w-4 h-4 bg-black rounded-full border-2 border-white shadow focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-1 cursor-grab active:cursor-grabbing"
			data-slider-thumb="max"
			aria-label="<?php esc_attr_e( 'Maximum price', 'lenvy' ); ?>"
			aria-valuemin="<?php echo esc_attr( $global_min ); ?>"
			aria-valuemax="<?php echo esc_attr( $global_max ); ?>"
			aria-valuenow="<?php echo esc_attr( $current_max ); ?>"
			role="slider"
		></button>
	</div>

	<!-- Hidden inputs (submitted with the form) -->
	<input type="hidden" name="min_price" value="<?php echo esc_attr( $current_min ); ?>" data-slider-input="min">
	<input type="hidden" name="max_price" value="<?php echo esc_attr( $current_max ); ?>" data-slider-input="max">

	<!-- Display labels -->
	<div class="flex items-center justify-between text-xs text-neutral-600 mt-1">
		<span data-slider-label="min"><?php echo wp_kses_post( wc_price( $current_min ) ); ?></span>
		<span data-slider-label="max"><?php echo wp_kses_post( wc_price( $current_max ) ); ?></span>
	</div>
</div>
<?php
$content = ob_get_clean();

get_template_part( 'template-parts/shop/filter-accordion', null, compact( 'label', 'open', 'content' ) + [ 'name' => 'price' ] );
