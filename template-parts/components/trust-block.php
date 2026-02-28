<?php
/**
 * Trust block — renders shipping, returns, and payment trust signals.
 *
 * Placed below the ATC form on single product pages.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

?>
<div class="mt-5 border border-primary rounded-lg p-4 space-y-2.5">

	<!-- Shipping -->
	<div class="flex items-center gap-2">
		<?php lenvy_icon('truck', 'text-neutral-400 shrink-0', 'sm'); ?>
		<span class="text-xs text-neutral-600"><?php esc_html_e('Gratis verzending vanaf €50', 'lenvy'); ?></span>
	</div>

	<!-- Returns -->
	<div class="flex items-center gap-2">
		<?php lenvy_icon('refresh', 'text-neutral-400 shrink-0', 'sm'); ?>
		<span class="text-xs text-neutral-600"><?php esc_html_e('30 dagen bedenktijd', 'lenvy'); ?></span>
	</div>

	<!-- Payment methods -->
	<div class="flex items-center gap-2">
		<?php lenvy_icon('check', 'text-neutral-400 shrink-0', 'sm'); ?>
		<span class="text-xs text-neutral-600"><?php esc_html_e('Veilig betalen', 'lenvy'); ?></span>
	</div>

	<!-- Payment icons -->
	<div class="flex items-center pt-1">
		<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/payments/payment-methods.svg'); ?>" alt="<?php esc_attr_e('iDEAL, Maestro, Mastercard, Visa', 'lenvy'); ?>" width="168" height="26" loading="lazy">
	</div>

</div>
