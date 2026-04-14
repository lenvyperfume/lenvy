<?php
/**
 * Homepage hero — split layout (Skins-inspired).
 *
 * Left:  hero image / video (~60 %)
 * Right: heading + description + CTA buttons (~40 %)
 *
 * The section fills the viewport minus the header height so the USP bar
 * sits right at the fold.
 *
 * ACF fields (page or options):
 *   lenvy_hero_image                 image array — left panel (LCP element)
 *   lenvy_hero_video_url             url         — optional self-hosted MP4
 *   lenvy_hero_heading               text        — large serif heading
 *   lenvy_hero_description           textarea    — supporting copy
 *   lenvy_hero_button_text           text        — primary CTA label
 *   lenvy_hero_button_url            url         — primary CTA link
 *   lenvy_hero_button_secondary_text text        — secondary CTA label (optional)
 *   lenvy_hero_button_secondary_url  url         — secondary CTA link (optional)
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$hero_image = lenvy_field( 'lenvy_hero_image' );
$hero_video = lenvy_field( 'lenvy_hero_video_url' );
$heading    = lenvy_field( 'lenvy_hero_heading' ) ?: __( 'Ontdek Jouw Signature Geur', 'lenvy' );
$desc       = lenvy_field( 'lenvy_hero_description' ) ?: __( 'Verken onze exclusieve collectie parfums van de meest iconische merken ter wereld.', 'lenvy' );
$btn_text   = lenvy_field( 'lenvy_hero_button_text' ) ?: __( 'Shop Nu', 'lenvy' );
$btn_url    = lenvy_field( 'lenvy_hero_button_url' ) ?: ( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) );
$btn2_text  = lenvy_field( 'lenvy_hero_button_secondary_text' );
$btn2_url   = lenvy_field( 'lenvy_hero_button_secondary_url' );

$image_id  = is_array( $hero_image ) ? (int) ( $hero_image['ID'] ?? 0 ) : 0;
$image_url = is_array( $hero_image ) ? ( $hero_image['url'] ?? '' ) : '';
?>

<section
	class="relative bg-neutral-950"
	aria-label="<?php esc_attr_e( 'Hero', 'lenvy' ); ?>"
	style="min-height: calc(100svh - var(--header-height, 72px) - var(--usp-bar-height, 48px));"
>
	<div class="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] h-full" style="min-height: inherit;">

		<!-- ── Left: Image / video panel ──────────────────────────────── -->
		<div class="relative overflow-hidden min-h-[350px] sm:min-h-[420px] lg:min-h-0">

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
				<div
					class="absolute inset-0 bg-gradient-to-br from-neutral-800 via-neutral-900 to-neutral-950"
					aria-hidden="true"
				></div>
			<?php endif; ?>

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

		</div>

		<!-- ── Right: Content panel ──────────────────────────────────── -->
		<div class="flex flex-col items-center justify-center px-8 py-16 text-center lg:px-14 xl:px-20 bg-white">

			<div class="max-w-sm lg:max-w-md">

				<h2 class="font-medium text-4xl leading-[1.1] tracking-tight text-neutral-900 sm:text-5xl lg:text-[3.25rem] xl:text-[3.75rem]">
					<?php echo esc_html( $heading ); ?>
				</h2>

				<p class="mt-6 text-sm leading-relaxed text-neutral-500 sm:text-[0.9375rem] lg:text-base lg:leading-relaxed">
					<?php echo esc_html( $desc ); ?>
				</p>

				<div class="mt-10 flex flex-wrap items-center justify-center gap-3">
					<a
						href="<?php echo esc_url( $btn_url ); ?>"
						class="inline-flex items-center justify-center px-8 py-3.5 text-xs font-semibold tracking-[0.15em] bg-black text-white uppercase transition-colors duration-200 hover:bg-neutral-800"
					>
						<?php echo esc_html( $btn_text ); ?>
					</a>

					<?php if ( $btn2_text && $btn2_url ) : ?>
						<a
							href="<?php echo esc_url( $btn2_url ); ?>"
							class="inline-flex items-center justify-center px-8 py-3.5 text-xs font-semibold tracking-[0.15em] border border-neutral-900 text-neutral-900 uppercase transition-colors duration-200 hover:bg-neutral-900 hover:text-white"
						>
							<?php echo esc_html( $btn2_text ); ?>
						</a>
					<?php endif; ?>
				</div>

			</div>

		</div>

	</div>
</section>
