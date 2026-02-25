<?php
/**
 * Homepage hero — cinematic banner (image / video only, no text overlay).
 *
 * Stripped-back: just the full-width image/video at a tall-but-not-full-viewport
 * height. No heading, no CTA — the brand lets the image speak.
 *
 * ACF fields:
 *   lenvy_hero_image       image array — background (LCP element)
 *   lenvy_hero_video_url   url         — optional self-hosted MP4
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$hero_image = lenvy_field( 'lenvy_hero_image' );
$hero_video = lenvy_field( 'lenvy_hero_video_url' );

$image_id  = is_array( $hero_image ) ? (int) ( $hero_image['ID'] ?? 0 ) : 0;
$image_url = is_array( $hero_image ) ? ( $hero_image['url'] ?? '' ) : '';
?>

<section
	class="relative overflow-hidden bg-neutral-950"
	style="height: clamp(360px, 62vh, 720px);"
	aria-label="<?php esc_attr_e( 'Hero', 'lenvy' ); ?>"
>

	<!-- Background image — LCP element: eager + high priority -->
	<?php if ( $image_id ) : ?>
		<?php echo wp_get_attachment_image( $image_id, 'full', false, [
			'class'         => 'absolute inset-0 w-full h-full object-cover object-center',
			'fetchpriority' => 'high',
			'loading'       => 'eager',
			'decoding'      => 'async',
			'alt'           => '',
			'aria-hidden'   => 'true',
		] ); ?>
	<?php else : ?>
		<!-- Fallback gradient when no image is uploaded yet -->
		<div
			class="absolute inset-0 bg-gradient-to-br from-neutral-900 via-neutral-800 to-neutral-950"
			aria-hidden="true"
		></div>
	<?php endif; ?>

	<!-- Optional video overlay -->
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

	<!-- Subtle editorial vignette — depth, not text legibility -->
	<div
		class="absolute inset-0 bg-gradient-to-t from-neutral-950/30 via-transparent to-neutral-950/10"
		aria-hidden="true"
	></div>

</section>
