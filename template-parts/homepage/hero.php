<?php
/**
 * Homepage hero section.
 *
 * Full-viewport background — image with optional muted video overlay.
 * Text position (left / center) is ACF-controlled.
 * This is the LCP element; hero image gets fetchpriority="high" + loading="eager".
 *
 * ACF fields:
 *   lenvy_hero_image          image array   — background
 *   lenvy_hero_video_url      url           — self-hosted MP4, optional
 *   lenvy_hero_heading        text
 *   lenvy_hero_subheading     textarea
 *   lenvy_hero_cta_label      text          — default "Shop Now"
 *   lenvy_hero_cta_url        url
 *   lenvy_hero_text_position  select        — left | center
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$hero_image  = lenvy_field( 'lenvy_hero_image' );
$hero_video  = lenvy_field( 'lenvy_hero_video_url' );
$heading     = lenvy_field( 'lenvy_hero_heading' );
$subheading  = lenvy_field( 'lenvy_hero_subheading' );
$cta_label   = lenvy_field( 'lenvy_hero_cta_label' ) ?: __( 'Shop Now', 'lenvy' );
$cta_url     = lenvy_field( 'lenvy_hero_cta_url' );
$text_pos    = lenvy_field( 'lenvy_hero_text_position' ) ?: 'left';

// Default shop URL fallback
if ( ! $cta_url ) {
	$cta_url = function_exists( 'wc_get_page_permalink' )
		? wc_get_page_permalink( 'shop' )
		: get_post_type_archive_link( 'product' );
}

$image_id  = is_array( $hero_image ) ? ( $hero_image['ID'] ?? 0 ) : 0;
$image_url = is_array( $hero_image ) ? ( $hero_image['url'] ?? '' ) : '';

$is_center = ( 'center' === $text_pos );
?>

<section
	class="relative flex items-end bg-neutral-950"
	style="min-height: 90svh;"
	aria-label="<?php esc_attr_e( 'Hero', 'lenvy' ); ?>"
>

	<!-- Background image — LCP element: eager + high priority -->
	<?php if ( $image_id ) : ?>
		<?php
		echo wp_get_attachment_image( $image_id, 'full', false, [
			'class'         => 'absolute inset-0 w-full h-full object-cover object-center',
			'fetchpriority' => 'high',
			'loading'       => 'eager',
			'decoding'      => 'async',
			'alt'           => '',
			'aria-hidden'   => 'true',
		] );
		?>
	<?php else : ?>
		<!-- Fallback when no image is uploaded: editorial dark gradient background -->
		<div class="absolute inset-0 bg-gradient-to-br from-neutral-900 via-neutral-800 to-neutral-950" aria-hidden="true"></div>
	<?php endif; ?>

	<!-- Video overlay (plays over the image) -->
	<?php if ( $hero_video ) : ?>
		<video
			class="absolute inset-0 w-full h-full object-cover"
			autoplay
			muted
			loop
			playsinline
			aria-hidden="true"
			<?php if ( $image_url ) : ?>poster="<?php echo esc_url( $image_url ); ?>"<?php endif; ?>
		>
			<source src="<?php echo esc_url( $hero_video ); ?>" type="video/mp4">
		</video>
	<?php endif; ?>

	<!-- Dark gradient overlay — heavier at the bottom for text legibility -->
	<div class="absolute inset-0 bg-gradient-to-t from-neutral-950/80 via-neutral-950/25 to-neutral-950/10" aria-hidden="true"></div>

	<!-- Hero content -->
	<div class="relative z-10 w-full lenvy-container pb-14 lg:pb-24 pt-32">
		<div class="<?php echo $is_center ? 'max-w-2xl mx-auto text-center' : 'max-w-2xl'; ?>">

			<?php if ( $heading ) : ?>
				<h1
					class="font-serif italic text-white leading-[1.05] mb-6"
					style="font-size: clamp(2.75rem, 6vw, 5.25rem);"
				>
					<?php echo esc_html( $heading ); ?>
				</h1>
			<?php endif; ?>

			<?php if ( $subheading ) : ?>
				<p class="text-sm sm:text-base text-white/65 leading-relaxed mb-10 max-w-md<?php echo $is_center ? ' mx-auto' : ''; ?>">
					<?php echo esc_html( $subheading ); ?>
				</p>
			<?php endif; ?>

			<!-- CTA — brand primary on dark backgrounds -->
			<a
				href="<?php echo esc_url( $cta_url ?: home_url( '/shop/' ) ); ?>"
				class="inline-flex items-center gap-2.5 bg-primary text-black font-medium uppercase tracking-widest text-xs px-7 py-3.5 hover:bg-white transition-colors duration-200"
			>
				<span><?php echo esc_html( $cta_label ); ?></span>
				<?php lenvy_icon( 'arrow-right', '', 'sm' ); ?>
			</a>

		</div>
	</div>

</section>
