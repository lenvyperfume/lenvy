<?php
/**
 * My Account — Addresses.
 *
 * Billing and shipping cards in a responsive 2-column grid.
 * Replaces WC's float-based col2-set layout with clean Tailwind markup.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Lenvy
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __( 'Billing address', 'woocommerce' ),
			'shipping' => __( 'Shipping address', 'woocommerce' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'Billing address', 'woocommerce' ),
		),
		$customer_id
	);
}
?>

<p class="text-sm text-neutral-600 mb-8">
	<?php
	echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		'woocommerce_my_account_my_address_description',
		esc_html__( 'The following addresses will be used on the checkout page by default.', 'woocommerce' )
	);
	?>
</p>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

	<?php foreach ( $get_addresses as $name => $address_title ) : ?>
		<?php $address = wc_get_account_formatted_address( $name ); ?>

		<div class="woocommerce-Address border border-neutral-200 p-6">

			<header class="woocommerce-Address-title title flex items-center justify-between gap-3 mb-4 pb-4 border-b border-neutral-100">
				<h2 class="text-xs font-semibold uppercase tracking-widest text-neutral-800 m-0">
					<?php echo esc_html( $address_title ); ?>
				</h2>
				<a
					href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>"
					class="text-xs text-neutral-500 underline underline-offset-2 hover:text-neutral-900 transition-colors whitespace-nowrap shrink-0"
				>
					<?php
					printf(
						/* translators: %s: Address title */
						$address ? esc_html__( 'Edit %s', 'woocommerce' ) : esc_html__( 'Add %s', 'woocommerce' ),
						esc_html( $address_title )
					);
					?>
				</a>
			</header>

			<address class="not-italic text-sm text-neutral-700 leading-relaxed">
				<?php
				if ( $address ) {
					echo wp_kses_post( $address );
				} else {
					esc_html_e( 'You have not set up this type of address yet.', 'woocommerce' );
				}
				do_action( 'woocommerce_my_account_after_my_address', $name );
				?>
			</address>

		</div>

	<?php endforeach; ?>

</div>
