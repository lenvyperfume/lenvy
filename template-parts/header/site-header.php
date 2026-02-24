<?php
/**
 * Site header — announcement bar + sticky header + mobile drawer + search overlay.
 *
 * Structure:
 *   [data-announcement]  — optional, dismissible, scrolls away
 *   <header data-header> — sticky bar; top offset for admin bar via _header.scss
 *     .lenvy-container
 *       logo | primary-nav (desktop) | actions (search + cart + hamburger)
 *   nav-mobile.php       — backdrop + slide-in drawer
 *   search-overlay.php   — full-screen search panel
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$announcement_enabled = lenvy_field( 'lenvy_announcement_bar_enabled', 'options' );
$announcement_text    = lenvy_field( 'lenvy_announcement_bar_text', 'options' );
$announcement_link    = lenvy_field( 'lenvy_announcement_bar_link', 'options' );
$logo_id              = lenvy_field( 'lenvy_site_logo', 'options' );
$cart_count           = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
$cart_url             = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
?>

<?php if ( $announcement_enabled && $announcement_text ) : ?>
<div
	data-announcement
	class="bg-primary text-black text-center text-xs font-medium py-2.5 px-10 relative"
>
	<?php if ( ! empty( $announcement_link['url'] ) ) : ?>
		<a
			href="<?php echo esc_url( $announcement_link['url'] ); ?>"
			<?php if ( ( $announcement_link['target'] ?? '' ) === '_blank' ) : ?>target="_blank" rel="noopener"<?php endif; ?>
			class="hover:underline underline-offset-2"
		><?php echo esc_html( $announcement_text ); ?></a>
	<?php else : ?>
		<span><?php echo esc_html( $announcement_text ); ?></span>
	<?php endif; ?>
	<button
		type="button"
		data-dismiss-announcement
		class="absolute right-4 top-1/2 -translate-y-1/2 text-black/60 hover:text-black transition-colors duration-150"
		aria-label="<?php esc_attr_e( 'Dismiss announcement', 'lenvy' ); ?>"
	>
		<?php lenvy_icon( 'close', '', 'xs' ); ?>
	</button>
</div>
<?php endif; ?>

<header
	data-header
	class="sticky z-[40] bg-white border-b border-neutral-100 transition-shadow duration-200"
>
	<div class="lenvy-container">
		<div class="flex items-center justify-between h-15 gap-4">

			<!-- Logo -->
			<a
				href="<?php echo esc_url( home_url( '/' ) ); ?>"
				class="shrink-0 flex items-center"
				aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
			>
				<?php if ( $logo_id ) : ?>
					<?php echo lenvy_get_image( $logo_id, 'medium', 'h-8 w-auto object-contain' ); ?>
				<?php else : ?>
					<span class="text-base font-semibold tracking-widest text-neutral-900 uppercase">
						<?php bloginfo( 'name' ); ?>
					</span>
				<?php endif; ?>
			</a>

			<!-- Primary navigation (desktop only) -->
			<?php get_template_part( 'template-parts/header/nav-primary' ); ?>

			<!-- Right-side actions -->
			<div class="flex items-center gap-1 sm:gap-2 shrink-0">

				<!-- Search -->
				<button
					type="button"
					data-search-toggle
					class="p-2 text-neutral-600 hover:text-black transition-colors duration-150"
					aria-label="<?php esc_attr_e( 'Open search', 'lenvy' ); ?>"
					aria-expanded="false"
				>
					<?php lenvy_icon( 'search', '', 'md' ); ?>
				</button>

				<!-- Cart -->
				<a
					href="<?php echo esc_url( $cart_url ); ?>"
					class="relative p-2 text-neutral-600 hover:text-black transition-colors duration-150"
					aria-label="<?php echo esc_attr( sprintf( _n( 'Cart, %d item', 'Cart, %d items', $cart_count, 'lenvy' ), $cart_count ) ); ?>"
				>
					<?php lenvy_icon( 'cart', '', 'md' ); ?>
					<span
						data-cart-count
						class="absolute -top-0.5 -right-0.5 flex items-center justify-center w-4 h-4 bg-black text-white text-[10px] font-semibold rounded-full leading-none"
						aria-hidden="true"
						<?php if ( $cart_count === 0 ) : ?>style="display:none;"<?php endif; ?>
					>
						<?php echo esc_html( $cart_count > 99 ? '99+' : $cart_count ); ?>
					</span>
				</a>

				<!-- Mobile menu toggle (hidden on desktop) -->
				<button
					type="button"
					data-drawer-toggle
					class="p-2 text-neutral-600 hover:text-black transition-colors duration-150 lg:hidden"
					aria-label="<?php esc_attr_e( 'Open navigation menu', 'lenvy' ); ?>"
					aria-expanded="false"
					aria-controls="lenvy-mobile-drawer"
				>
					<?php lenvy_icon( 'menu', '', 'md' ); ?>
				</button>

			</div>
		</div>
	</div>
</header>

<?php
get_template_part( 'template-parts/header/nav-mobile' );
get_template_part( 'template-parts/header/search-overlay' );
