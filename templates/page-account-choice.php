<?php
/**
 * Template Name: Account Choice
 *
 * Shown between cart and checkout for non-logged-in users.
 * Offers: Log in · Create account · Continue as guest.
 *
 * @package Lenvy
 */

defined( 'ABSPATH' ) || exit();

get_header();
?>

<main id="primary" class="site-main">

	<div class="lenvy-container py-16 md:py-24">

		<div class="max-w-3xl mx-auto">

			<header class="mb-12 text-center">
				<h1 class="font-serif italic text-3xl sm:text-4xl text-neutral-900 mb-3">
					<?php esc_html_e( 'How would you like to continue?', 'lenvy' ); ?>
				</h1>
				<p class="text-sm text-neutral-500">
					<?php esc_html_e( 'Choose an option below to proceed to checkout.', 'lenvy' ); ?>
				</p>
			</header>

			<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

				<!-- Returning customer — log in -->
				<div class="flex flex-col border border-neutral-200 p-8">
					<h2 class="text-xs font-medium uppercase tracking-widest text-neutral-500 mb-3">
						<?php esc_html_e( 'Returning customer', 'lenvy' ); ?>
					</h2>
					<p class="text-sm text-neutral-700 leading-relaxed mb-6 grow">
						<?php esc_html_e( 'Sign in to your account for a faster checkout experience.', 'lenvy' ); ?>
					</p>
					<a
						href="<?php echo esc_url( wp_login_url( wc_get_checkout_url() ) ); ?>"
						class="block w-full text-center bg-black text-white text-xs font-medium uppercase tracking-widest py-3 px-6 transition-opacity hover:opacity-70"
					>
						<?php esc_html_e( 'Log in', 'lenvy' ); ?>
					</a>
				</div>

				<!-- New customer — register -->
				<div class="flex flex-col border border-neutral-200 p-8">
					<h2 class="text-xs font-medium uppercase tracking-widest text-neutral-500 mb-3">
						<?php esc_html_e( 'New customer', 'lenvy' ); ?>
					</h2>
					<p class="text-sm text-neutral-700 leading-relaxed mb-6 grow">
						<?php esc_html_e( 'Create an account to track orders and save your details for next time.', 'lenvy' ); ?>
					</p>
					<a
						href="<?php echo esc_url( wp_registration_url() ); ?>"
						class="block w-full text-center bg-black text-white text-xs font-medium uppercase tracking-widest py-3 px-6 transition-opacity hover:opacity-70"
					>
						<?php esc_html_e( 'Create account', 'lenvy' ); ?>
					</a>
				</div>

				<!-- Guest checkout -->
				<div class="flex flex-col border border-neutral-200 p-8">
					<h2 class="text-xs font-medium uppercase tracking-widest text-neutral-500 mb-3">
						<?php esc_html_e( 'Guest checkout', 'lenvy' ); ?>
					</h2>
					<p class="text-sm text-neutral-700 leading-relaxed mb-6 grow">
						<?php esc_html_e( 'Continue without creating an account.', 'lenvy' ); ?>
					</p>
					<a
						href="<?php echo esc_url( add_query_arg( 'guest', '1', wc_get_checkout_url() ) ); ?>"
						class="block w-full text-center border border-neutral-900 text-neutral-900 text-xs font-medium uppercase tracking-widest py-3 px-6 transition-colors hover:bg-neutral-900 hover:text-white"
					>
						<?php esc_html_e( 'Continue as guest', 'lenvy' ); ?>
					</a>
				</div>

			</div><!-- .grid -->

		</div><!-- .max-w-3xl -->

	</div><!-- .lenvy-container -->

</main>

<?php get_footer(); ?>
