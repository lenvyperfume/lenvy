<?php
/**
 * Trust block — renders shipping, returns, and payment trust signals.
 *
 * Placed below the ATC form on single product pages.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$icon_url = get_template_directory_uri() . '/assets/icons/payments/';
?>
<div class="mt-5 border border-neutral-100 rounded-lg p-4 space-y-2.5">

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
	<div class="flex items-start gap-2">
		<?php lenvy_icon('check', 'text-neutral-400 shrink-0 mt-0.5', 'sm'); ?>
		<div>
			<span class="text-xs text-neutral-600"><?php esc_html_e('Veilig betalen', 'lenvy'); ?></span>
			<div class="flex items-center gap-1.5 mt-1.5">
				<img src="<?php echo esc_url($icon_url . 'ideal.svg'); ?>" alt="iDEAL" width="46" height="28" loading="lazy">
				<img src="<?php echo esc_url($icon_url . 'visa.svg'); ?>" alt="Visa" width="46" height="28" loading="lazy">
				<img src="<?php echo esc_url($icon_url . 'mastercard.svg'); ?>" alt="Mastercard" width="46" height="28" loading="lazy">
				<img src="<?php echo esc_url($icon_url . 'klarna.svg'); ?>" alt="Klarna" width="46" height="28" loading="lazy">
			</div>
		</div>
	</div>

</div>
