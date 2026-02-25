<?php
/**
 * My Account — Dashboard.
 *
 * Shows a greeting and navigation cards for account sections.
 *
 * @package Lenvy
 */

defined( 'ABSPATH' ) || exit();

$current_user = wp_get_current_user();
?>

<div class="lenvy-account-dashboard">

	<p class="text-sm text-neutral-600 mb-10">
		<?php
		printf(
			/* translators: %s: user display name */
			wp_kses(
				__( 'Welcome back, <span class="font-medium text-neutral-900">%s</span>.', 'lenvy' ),
				[ 'span' => [ 'class' => [] ] ]
			),
			esc_html( $current_user->display_name )
		);
		?>
	</p>

	<nav
		class="grid grid-cols-1 sm:grid-cols-2 gap-3"
		aria-label="<?php esc_attr_e( 'Account navigation', 'lenvy' ); ?>"
	>

		<a
			href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>"
			class="group flex items-center justify-between border border-neutral-200 p-5 transition-colors hover:border-neutral-900"
		>
			<div>
				<span class="block text-xs font-medium uppercase tracking-widest text-neutral-500 mb-1 group-hover:text-neutral-700 transition-colors">
					<?php esc_html_e( 'My orders', 'lenvy' ); ?>
				</span>
				<span class="text-sm text-neutral-900">
					<?php esc_html_e( 'View and track your orders', 'lenvy' ); ?>
				</span>
			</div>
			<span class="text-neutral-300 group-hover:text-neutral-900 transition-colors" aria-hidden="true">→</span>
		</a>

		<a
			href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>"
			class="group flex items-center justify-between border border-neutral-200 p-5 transition-colors hover:border-neutral-900"
		>
			<div>
				<span class="block text-xs font-medium uppercase tracking-widest text-neutral-500 mb-1 group-hover:text-neutral-700 transition-colors">
					<?php esc_html_e( 'Addresses', 'lenvy' ); ?>
				</span>
				<span class="text-sm text-neutral-900">
					<?php esc_html_e( 'Manage your addresses', 'lenvy' ); ?>
				</span>
			</div>
			<span class="text-neutral-300 group-hover:text-neutral-900 transition-colors" aria-hidden="true">→</span>
		</a>

		<a
			href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>"
			class="group flex items-center justify-between border border-neutral-200 p-5 transition-colors hover:border-neutral-900"
		>
			<div>
				<span class="block text-xs font-medium uppercase tracking-widest text-neutral-500 mb-1 group-hover:text-neutral-700 transition-colors">
					<?php esc_html_e( 'Account details', 'lenvy' ); ?>
				</span>
				<span class="text-sm text-neutral-900">
					<?php esc_html_e( 'Update your personal information', 'lenvy' ); ?>
				</span>
			</div>
			<span class="text-neutral-300 group-hover:text-neutral-900 transition-colors" aria-hidden="true">→</span>
		</a>

		<a
			href="<?php echo esc_url( wc_get_account_endpoint_url( 'customer-logout' ) ); ?>"
			class="group flex items-center justify-between border border-neutral-200 p-5 transition-colors hover:border-red-200"
		>
			<div>
				<span class="block text-xs font-medium uppercase tracking-widest text-neutral-500 mb-1 group-hover:text-red-400 transition-colors">
					<?php esc_html_e( 'Sign out', 'lenvy' ); ?>
				</span>
				<span class="text-sm text-neutral-900 group-hover:text-red-600 transition-colors">
					<?php esc_html_e( 'Log out of your account', 'lenvy' ); ?>
				</span>
			</div>
			<span class="text-neutral-300 group-hover:text-red-300 transition-colors" aria-hidden="true">→</span>
		</a>

	</nav>

</div><!-- .lenvy-account-dashboard -->
