<?php
/**
 * Notice component — theme-level alert/notice box.
 *
 * For WooCommerce checkout/cart notices use wc_print_notices() — those are
 * styled separately in _woocommerce.scss.
 *
 * Usage:
 *   get_template_part('template-parts/components/notice', null, [
 *     'type'        => 'success',       // success|error|info|warning
 *     'message'     => 'Order placed!', // required — plain text or safe HTML
 *     'dismissible' => true,            // adds a close button (JS-handled)
 *   ]);
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$type = $args['type'] ?? 'info';
$message = $args['message'] ?? '';
$dismissible = !empty($args['dismissible']);

if (empty($message)) {
	return;
}

$allowed_types = ['success', 'error', 'info', 'warning'];
$type = in_array($type, $allowed_types, true) ? $type : 'info';

// Classes — using CSS custom values since error/success/info are not brand tokens.
$styles = [
	'success' => 'bg-[#f0fdf4] border-[#bbf7d0] text-[#14532d]',
	'error' => 'bg-[#fff1f2] border-[#fecdd3] text-[#881337]',
	'info' => 'bg-neutral-50 border-neutral-200 text-neutral-800',
	'warning' => 'bg-[#fffbeb] border-[#fde68a] text-[#78350f]',
];

$icons = [
	'success' => 'check',
	'error' => 'close',
	'info' => 'minus',
	'warning' => 'minus',
];

$style_class = $styles[$type];
$icon_name = $icons[$type];
?>
<div class="flex items-start gap-3 border px-4 py-3 text-sm <?php echo esc_attr($style_class); ?>"
     role="alert">

	<span class="mt-0.5 shrink-0">
		<?php get_template_part('template-parts/components/icon', null, ['name' => $icon_name, 'size' => 'sm']); ?>
	</span>

	<p class="flex-1 leading-relaxed">
		<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — caller is responsible for message escaping; wp_kses_post used below.
  echo wp_kses_post($message); ?>
	</p>

	<?php if ($dismissible): ?>
		<button type="button"
		        class="shrink-0 opacity-60 hover:opacity-100 transition-opacity"
		        aria-label="<?php esc_attr_e('Sluiten', 'lenvy'); ?>"
		        onclick="this.closest('[role=alert]').remove()">
			<?php get_template_part('template-parts/components/icon', null, ['name' => 'close', 'size' => 'sm']); ?>
		</button>
	<?php endif; ?>

</div>
