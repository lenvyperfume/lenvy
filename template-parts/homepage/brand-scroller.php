<?php
/**
 * Brand logo auto-scroller — pure CSS infinite marquee.
 *
 * Reads logos from the ACF repeater `lenvy_brand_scroller_logos` on the
 * Homepage field group (WP Admin → Homepage → "Brand Scroller" tab).
 *
 * Logos are rendered twice so the CSS translateX(-50%) animation creates
 * a perfectly seamless loop without any JavaScript.
 *
 * Hover pause via CSS: `.lenvy-brand-scroller-section:hover .lenvy-brand-scroller__track`
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$rows = lenvy_field( 'lenvy_brand_scroller_logos' );

if ( empty( $rows ) || ! is_array( $rows ) ) {
	return;
}

// Keep only rows that have a valid image ID.
$logos = array_values( array_filter( $rows, fn( $row ) => ! empty( $row['logo_image']['ID'] ) ) );

if ( empty( $logos ) ) {
	return;
}
?>

<section
	class="lenvy-brand-scroller-section relative bg-white border-b border-neutral-100"
	aria-label="<?php esc_attr_e( 'Our brands', 'lenvy' ); ?>"
>

	<!-- Left/right fade masks -->
	<div
		class="absolute inset-y-0 left-0 w-24 z-10 pointer-events-none"
		style="background: linear-gradient(to right, #ffffff, transparent);"
		aria-hidden="true"
	></div>
	<div
		class="absolute inset-y-0 right-0 w-24 z-10 pointer-events-none"
		style="background: linear-gradient(to left, #ffffff, transparent);"
		aria-hidden="true"
	></div>

	<div class="lenvy-brand-scroller py-8">
		<!--
			Track is rendered TWICE so translateX(-50%) lands exactly on the
			start of the second copy — creating a seamless infinite loop.
		-->
		<div class="lenvy-brand-scroller__track" aria-hidden="false">

			<!-- First copy -->
			<?php foreach ( $logos as $logo ) :
				$img_id  = (int) $logo['logo_image']['ID'];
				$img_alt = $logo['logo_image']['alt'] ?? '';
				$url     = ! empty( $logo['logo_url'] ) ? $logo['logo_url'] : '';
			?>
			<div class="lenvy-brand-scroller__slide">
				<?php if ( $url ) : ?>
				<a href="<?php echo esc_url( $url ); ?>" class="lenvy-brand-scroller__logo">
				<?php else : ?>
				<span class="lenvy-brand-scroller__logo">
				<?php endif; ?>

					<?php echo wp_get_attachment_image(
						$img_id,
						[ 300, 120 ],
						false,
						[
							'style'   => 'width:100%;height:100%;display:block;max-width:none;',
							'loading' => 'eager',
							'alt'     => esc_attr( $img_alt ),
						]
					); ?>

				<?php echo $url ? '</a>' : '</span>'; ?>
			</div>
			<?php endforeach; ?>

			<!-- Second copy — aria-hidden so screen readers skip the duplicate -->
			<?php foreach ( $logos as $logo ) :
				$img_id  = (int) $logo['logo_image']['ID'];
				$url     = ! empty( $logo['logo_url'] ) ? $logo['logo_url'] : '';
			?>
			<div class="lenvy-brand-scroller__slide" aria-hidden="true">
				<?php if ( $url ) : ?>
				<a href="<?php echo esc_url( $url ); ?>" class="lenvy-brand-scroller__logo" tabindex="-1">
				<?php else : ?>
				<span class="lenvy-brand-scroller__logo">
				<?php endif; ?>

					<?php echo wp_get_attachment_image(
						$img_id,
						[ 300, 120 ],
						false,
						[
							'style'   => 'width:100%;height:100%;display:block;max-width:none;',
							'loading' => 'eager',
							'alt'     => '',
						]
					); ?>

				<?php echo $url ? '</a>' : '</span>'; ?>
			</div>
			<?php endforeach; ?>

		</div>
	</div>

</section>
