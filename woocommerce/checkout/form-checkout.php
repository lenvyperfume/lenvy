<?php
/**
 * Checkout Form — two-column layout.
 *
 * Billing / shipping fields on the left; sticky order review + payment on the right.
 * All WooCommerce hooks preserved for plugin compatibility.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Lenvy
 * @version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
?>

<div class="lenvy-container py-12 lg:py-16">

	<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

	<!-- Page heading -->
	<div class="mb-8">
		<?php get_template_part( 'template-parts/components/breadcrumb' ); ?>
		<h1 class="mt-3 text-2xl font-serif italic text-neutral-900">
			<?php esc_html_e( 'Afrekenen', 'lenvy' ); ?>
		</h1>
	</div>

	<form
		name="checkout"
		method="post"
		class="checkout woocommerce-checkout flex flex-col lg:flex-row gap-12 items-start"
		action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
		enctype="multipart/form-data"
		aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>"
	>

		<!-- ── Left: billing + shipping ──────────────────────────────────── -->
		<div class="flex-1 min-w-0">

			<?php if ( $checkout->get_checkout_fields() ) : ?>

				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

				<div id="customer_details">
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

			<?php endif; ?>

		</div><!-- /left -->

		<!-- ── Right: order review + payment ─────────────────────────────── -->
		<div class="w-full lg:w-80 xl:w-[22rem] shrink-0">
			<div class="lg:sticky lg:top-[calc(var(--header-height,68px)+2rem)]">

				<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

				<h2 class="text-xs font-semibold uppercase tracking-widest text-neutral-800 pb-4 mb-4 border-b border-neutral-100">
					<?php esc_html_e( 'Jouw bestelling', 'lenvy' ); ?>
				</h2>

				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

			</div>
		</div><!-- /right -->

	</form>

	<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

</div><!-- /lenvy-container -->
