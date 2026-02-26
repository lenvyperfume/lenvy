<?php
/**
 * My Account — sidebar navigation.
 *
 * Active item gets a black left-edge marker; logout link gets a muted/red tone.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Lenvy
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation" aria-label="<?php esc_html_e( 'Account pages', 'woocommerce' ); ?>">
	<ul>
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<?php
			$classes   = wc_get_account_menu_item_classes( $endpoint );
			$is_active = str_contains( $classes, 'is-active' );
			$is_logout = 'customer-logout' === $endpoint;

			if ( $is_logout ) {
				$link_class = 'text-neutral-400 hover:text-red-500';
			} elseif ( $is_active ) {
				$link_class = 'text-neutral-900';
			} else {
				$link_class = 'text-neutral-500 hover:text-neutral-900';
			}
			?>
			<li class="<?php echo esc_attr( $classes ); ?>">
				<a
					href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"
					class="flex items-center gap-2.5 py-2.5 text-sm transition-colors duration-200 <?php echo esc_attr( $link_class ); ?>"
					<?php echo wc_is_current_account_menu_item( $endpoint ) ? 'aria-current="page"' : ''; ?>
				>
					<span class="w-0.5 h-3.5 shrink-0 <?php echo $is_active ? 'bg-black' : 'bg-transparent'; ?>" aria-hidden="true"></span>
					<?php echo esc_html( $label ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
