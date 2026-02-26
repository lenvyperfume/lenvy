<?php
/**
 * My Account — page wrapper.
 *
 * Two-column layout: sticky sidebar navigation (left) + content area (right).
 * Replaces the default bare nav + content div with a structured layout.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Lenvy
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="lenvy-container py-12 lg:py-16">
	<div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-start">

		<!-- ── Sidebar navigation ─────────────────────────────────────────── -->
		<div class="w-full lg:w-48 shrink-0 pb-6 border-b border-neutral-100 lg:pb-0 lg:border-b-0">
			<div class="lg:sticky lg:top-[calc(var(--header-height,68px)+2rem)]">
				<?php do_action( 'woocommerce_account_navigation' ); ?>
			</div>
		</div>

		<!-- ── Content ────────────────────────────────────────────────────── -->
		<div class="flex-1 min-w-0">
			<div class="woocommerce-MyAccount-content">
				<?php do_action( 'woocommerce_account_content' ); ?>
			</div>
		</div>

	</div>
</div>
