<?php
/**
 * Site footer — dark editorial layout.
 *
 * Structure:
 *   Brand signature  — oversized Playfair italic ghost text
 *   ─────────────────────────────────────────────────────
 *   Grid (4 col)     — brand+social | shop nav | info nav | contact
 *   ─────────────────────────────────────────────────────
 *   Bottom bar       — copyright | legal nav
 *
 * ACF fields consumed (options page):
 *   lenvy_site_logo_light       image (inverted fallback if absent)
 *   lenvy_site_logo             image
 *   lenvy_footer_copyright_text text  ({year} placeholder)
 *   lenvy_footer_social_links   repeater  [platform, url]
 *   lenvy_contact_email         email
 *   lenvy_contact_phone         text
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// ── Gather data ───────────────────────────────────────────────────────────────

$logo_light    = lenvy_field( 'lenvy_site_logo_light', 'options' );
$logo_primary  = lenvy_field( 'lenvy_site_logo', 'options' );
$logo_id       = $logo_light ?: $logo_primary;

$copyright_raw = lenvy_field( 'lenvy_footer_copyright_text', 'options' );
$copyright     = $copyright_raw
	? str_replace( '{year}', date( 'Y' ), $copyright_raw )
	: sprintf(
		/* translators: 1: year, 2: site name */
		__( '&copy; %1$s %2$s. All rights reserved.', 'lenvy' ),
		date( 'Y' ),
		get_bloginfo( 'name' )
	);

$social_links  = lenvy_field( 'lenvy_footer_social_links', 'options' ) ?: [];
$contact_email = lenvy_field( 'lenvy_contact_email', 'options' );
$contact_phone = lenvy_field( 'lenvy_contact_phone', 'options' );

$social_labels = [
	'instagram' => __( 'Instagram', 'lenvy' ),
	'facebook'  => __( 'Facebook', 'lenvy' ),
	'tiktok'    => __( 'TikTok', 'lenvy' ),
	'pinterest' => __( 'Pinterest', 'lenvy' ),
	'youtube'   => __( 'YouTube', 'lenvy' ),
	'x'         => __( 'X (Twitter)', 'lenvy' ),
];
?>

<footer class="bg-neutral-950 text-neutral-400 overflow-hidden">

	<!-- ── Brand signature ───────────────────────────────────────────────── -->
	<div class="lenvy-container pt-16 pb-0">
		<p
			class="font-serif italic leading-[0.85] text-primary/15 select-none whitespace-nowrap"
			style="font-size: clamp(4.5rem, 15vw, 11rem);"
			aria-hidden="true"
		>
			<?php bloginfo( 'name' ); ?>
		</p>
	</div>

	<!-- ── Main grid ─────────────────────────────────────────────────────── -->
	<div class="lenvy-container py-14 border-t border-neutral-800">
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-6">

			<!-- Col 1: Brand + social ─────────────────────────────────── -->
			<div class="space-y-6">

				<!-- Logo -->
				<a
					href="<?php echo esc_url( home_url( '/' ) ); ?>"
					aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
					class="inline-block"
				>
					<?php if ( $logo_id ) : ?>
						<?php echo lenvy_get_image( $logo_id, 'medium', 'h-7 w-auto object-contain brightness-0 invert opacity-80' ); ?>
					<?php else : ?>
						<span class="font-serif italic text-xl text-white/80 tracking-tight">
							<?php bloginfo( 'name' ); ?>
						</span>
					<?php endif; ?>
				</a>

				<p class="text-sm text-neutral-500 leading-relaxed">
					<?php esc_html_e( 'Discover your signature scent.', 'lenvy' ); ?>
				</p>

				<!-- Social icons -->
				<?php if ( ! empty( $social_links ) ) : ?>
				<div class="flex items-center gap-5">
					<?php foreach ( $social_links as $social ) :
						$platform = $social['platform'] ?? '';
						$url      = $social['url']      ?? '';
						if ( ! $platform || ! $url ) {
							continue;
						}
					?>
					<a
						href="<?php echo esc_url( $url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						aria-label="<?php echo esc_attr( $social_labels[ $platform ] ?? ucfirst( $platform ) ); ?>"
						class="text-neutral-600 hover:text-primary transition-colors duration-200"
					>
						<?php lenvy_icon( $platform, '', 'sm' ); ?>
					</a>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

			</div>

			<!-- Col 2: Shop nav ───────────────────────────────────────── -->
			<div class="space-y-5">
				<h3 class="text-xs font-medium uppercase tracking-widest text-neutral-600">
					<?php esc_html_e( 'Shop', 'lenvy' ); ?>
				</h3>
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<nav aria-label="<?php esc_attr_e( 'Shop Navigation', 'lenvy' ); ?>">
					<?php
					wp_nav_menu( [
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'space-y-3',
						'walker'         => new Lenvy_Footer_Nav_Walker(),
						'fallback_cb'    => false,
						'depth'          => 1,
					] );
					?>
				</nav>
				<?php endif; ?>
			</div>

			<!-- Col 3: Info nav ───────────────────────────────────────── -->
			<div class="space-y-5">
				<h3 class="text-xs font-medium uppercase tracking-widest text-neutral-600">
					<?php esc_html_e( 'Information', 'lenvy' ); ?>
				</h3>
				<?php if ( has_nav_menu( 'footer-secondary' ) ) : ?>
				<nav aria-label="<?php esc_attr_e( 'Information Navigation', 'lenvy' ); ?>">
					<?php
					wp_nav_menu( [
						'theme_location' => 'footer-secondary',
						'container'      => false,
						'menu_class'     => 'space-y-3',
						'walker'         => new Lenvy_Footer_Nav_Walker(),
						'fallback_cb'    => false,
						'depth'          => 1,
					] );
					?>
				</nav>
				<?php else : ?>
				<!-- Static fallback until footer-secondary menu is assigned in WP admin -->
				<ul class="space-y-3">
					<?php
					$fallback = [
						__( 'About Us', 'lenvy' )    => home_url( '/about/' ),
						__( 'FAQ', 'lenvy' )         => home_url( '/faq/' ),
						__( 'Contact', 'lenvy' )     => home_url( '/contact/' ),
						__( 'Shipping', 'lenvy' )    => home_url( '/shipping/' ),
						__( 'Returns', 'lenvy' )     => home_url( '/returns/' ),
					];
					foreach ( $fallback as $label => $href ) : ?>
					<li>
						<a
							href="<?php echo esc_url( $href ); ?>"
							class="text-sm font-light text-neutral-400 hover:text-white transition-colors duration-200"
						>
							<?php echo esc_html( $label ); ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>

			<!-- Col 4: Contact ────────────────────────────────────────── -->
			<div class="space-y-5">
				<h3 class="text-xs font-medium uppercase tracking-widest text-neutral-600">
					<?php esc_html_e( 'Contact', 'lenvy' ); ?>
				</h3>
				<ul class="space-y-3">
					<?php if ( $contact_email ) : ?>
					<li>
						<a
							href="mailto:<?php echo esc_attr( $contact_email ); ?>"
							class="text-sm font-light text-neutral-400 hover:text-white transition-colors duration-200 break-all"
						>
							<?php echo esc_html( $contact_email ); ?>
						</a>
					</li>
					<?php endif; ?>
					<?php if ( $contact_phone ) : ?>
					<li>
						<a
							href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $contact_phone ) ); ?>"
							class="text-sm font-light text-neutral-400 hover:text-white transition-colors duration-200"
						>
							<?php echo esc_html( $contact_phone ); ?>
						</a>
					</li>
					<?php endif; ?>
					<?php if ( ! $contact_email && ! $contact_phone ) : ?>
					<li>
						<span class="text-sm text-neutral-700 italic">
							<?php esc_html_e( 'Contact details coming soon.', 'lenvy' ); ?>
						</span>
					</li>
					<?php endif; ?>
				</ul>
			</div>

		</div>
	</div>

	<!-- ── Bottom bar ────────────────────────────────────────────────────── -->
	<div class="lenvy-container border-t border-neutral-900 py-6">
		<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">

			<p class="text-xs text-neutral-700">
				<?php echo wp_kses_post( $copyright ); ?>
			</p>

			<!-- Legal quick-links (hardcoded; client creates these pages) -->
			<nav
				class="flex items-center flex-wrap gap-x-5 gap-y-1"
				aria-label="<?php esc_attr_e( 'Legal', 'lenvy' ); ?>"
			>
				<?php
				$legal = [
					__( 'Privacy Policy', 'lenvy' )   => home_url( '/privacy-policy/' ),
					__( 'Terms & Conditions', 'lenvy' ) => home_url( '/terms-conditions/' ),
					__( 'Cookie Policy', 'lenvy' )    => home_url( '/cookie-policy/' ),
				];
				foreach ( $legal as $label => $href ) : ?>
				<a
					href="<?php echo esc_url( $href ); ?>"
					class="text-xs text-neutral-700 hover:text-neutral-400 transition-colors duration-200"
				>
					<?php echo esc_html( $label ); ?>
				</a>
				<?php endforeach; ?>
			</nav>

		</div>
	</div>

</footer>
