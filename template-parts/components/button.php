<?php
/**
 * Button component.
 *
 * Usage:
 *   get_template_part('template-parts/components/button', null, [
 *     'label'      => 'Shop Now',
 *     'url'        => '/shop/',
 *     'variant'    => 'primary',    // primary|secondary|outline|ghost
 *     'size'       => 'md',         // sm|md|lg
 *     'type'       => 'a',          // a|button|submit|reset
 *     'icon'       => 'arrow-right',
 *     'icon_pos'   => 'right',      // left|right
 *     'full_width' => false,
 *     'attrs'      => '',           // extra HTML attribute string
 *     'aria_label' => '',
 *     'disabled'   => false,
 *   ]);
 *
 * Variants:
 *   primary   — lavender fill, main CTA (Add to Cart, Apply filters)
 *   secondary — black fill, strong secondary (Checkout, Place order)
 *   outline   — light border, subtle secondary (Continue shopping, Clear)
 *   ghost     — text-only with hover underline, editorial links
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$label     = $args['label'] ?? '';
$url       = $args['url'] ?? '';
$variant   = $args['variant'] ?? 'primary';
$size      = $args['size'] ?? 'md';
$type      = $args['type'] ?? 'a';
$icon      = $args['icon'] ?? '';
$icon_pos  = $args['icon_pos'] ?? 'right';
$full_width = !empty($args['full_width']);
$attrs     = $args['attrs'] ?? '';
$aria_label = $args['aria_label'] ?? '';
$disabled  = !empty($args['disabled']);

$icon_only = '' === $label && '' !== $icon;

// ── Variant classes ───────────────────────────────────────────────────────────
$variants = [
	'primary'   => 'bg-primary text-black hover:bg-primary-hover',
	'secondary' => 'bg-black text-white hover:bg-neutral-800',
	'outline'   => 'border border-neutral-300 text-neutral-700 hover:border-neutral-900 hover:text-neutral-900',
	'ghost'     => 'text-neutral-500 underline-offset-4 hover:text-neutral-900 hover:underline',
];

$variant_class = $variants[$variant] ?? $variants['primary'];

// ── Size classes ──────────────────────────────────────────────────────────────
if ($icon_only) {
	$sizes = ['sm' => 'p-2', 'md' => 'p-2.5', 'lg' => 'p-3'];
} else {
	$sizes = [
		'sm' => 'h-10 px-6 text-xs',
		'md' => 'h-12 px-8 text-sm',
		'lg' => 'h-14 px-10 text-sm',
	];
}

$size_class = $sizes[$size] ?? $sizes['md'];

// ── Base classes ──────────────────────────────────────────────────────────────
$base = 'inline-flex items-center justify-center gap-2 font-medium tracking-wide';
$base .= ' transition-colors duration-200 select-none shrink-0';

if ($full_width) {
	$base .= ' w-full';
}

if ($disabled) {
	$base .= ' opacity-50 cursor-not-allowed pointer-events-none';
}

$class = trim("{$base} {$variant_class} {$size_class}");

// ── Element type ──────────────────────────────────────────────────────────────
$allowed_types = ['a', 'button', 'submit', 'reset'];
$type = in_array($type, $allowed_types, true) ? $type : 'a';
$is_link = 'a' === $type;

// ── Accessibility ─────────────────────────────────────────────────────────────
$aria_attr = '';

if ($aria_label) {
	$aria_attr .= ' aria-label="' . esc_attr($aria_label) . '"';
} elseif ($icon_only) {
	$aria_attr .= ' aria-label="' . esc_attr($label ?: __('Button', 'lenvy')) . '"';
}

if ($disabled) {
	$aria_attr .= ' aria-disabled="true"';
}

// ── Icon helper ───────────────────────────────────────────────────────────────
$icon_args = ['name' => $icon, 'size' => 'sm'];
?>

<?php if ($is_link): ?>
<a href="<?php echo esc_url($url ?: '#'); ?>"
   class="<?php echo esc_attr($class); ?>"
   <?php
   // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
   echo $aria_attr;
   if ($attrs) {
   	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
   	echo ' ' . $attrs;
   }
   ?>>
<?php else: ?>
<button type="<?php echo esc_attr(in_array($type, ['submit', 'reset'], true) ? $type : 'button'); ?>"
        class="<?php echo esc_attr($class); ?>"
        <?php
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $aria_attr;
        if ($disabled) {
        	echo ' disabled';
        }
        if ($attrs) {
        	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        	echo ' ' . $attrs;
        }
        ?>>
<?php endif; ?>

	<?php if ($icon && 'left' === $icon_pos): ?>
		<?php get_template_part('template-parts/components/icon', null, $icon_args); ?>
	<?php endif; ?>

	<?php if ($label): ?>
		<span><?php echo esc_html($label); ?></span>
	<?php endif; ?>

	<?php if ($icon && 'right' === $icon_pos): ?>
		<?php get_template_part('template-parts/components/icon', null, $icon_args); ?>
	<?php endif; ?>

<?php if ($is_link): ?></a>
<?php else: ?></button>
<?php endif; ?>
