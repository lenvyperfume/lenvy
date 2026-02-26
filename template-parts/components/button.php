<?php
/**
 * Button component.
 *
 * Usage:
 *   get_template_part('template-parts/components/button', null, [
 *     'label'      => 'Shop Now',   // button text; empty = icon-only
 *     'url'        => '/shop/',     // href (when type = 'a')
 *     'variant'    => 'primary',    // primary|secondary|outline|ghost
 *     'size'       => 'md',         // sm|md|lg
 *     'type'       => 'a',          // a|button|submit|reset
 *     'icon'       => 'arrow-right',// icon name (optional)
 *     'icon_pos'   => 'right',      // left|right
 *     'full_width' => false,        // bool
 *     'attrs'      => '',           // extra HTML attribute string
 *     'aria_label' => '',           // required for icon-only buttons
 *     'disabled'   => false,        // bool
 *   ]);
 *
 * Variants:
 *   primary   — brand primary fill → main CTA (Add to Cart, Shop Now, Apply filters)
 *   secondary — black fill → strong secondary action
 *   outline   — black border → versatile secondary
 *   ghost     — text-only with hover underline → minimal / editorial
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$label = $args['label'] ?? '';
$url = $args['url'] ?? '';
$variant = $args['variant'] ?? 'primary';
$size = $args['size'] ?? 'md';
$type = $args['type'] ?? 'a';
$icon = $args['icon'] ?? '';
$icon_pos = $args['icon_pos'] ?? 'right';
$full_width = !empty($args['full_width']);
$attrs = $args['attrs'] ?? '';
$aria_label = $args['aria_label'] ?? '';
$disabled = !empty($args['disabled']);

$icon_only = '' === $label && '' !== $icon;

// ── Variant classes ───────────────────────────────────────────────────────────
$variants = [
	'primary'   => 'bg-primary text-black hover:bg-primary-hover',
	'secondary' => 'bg-black text-white hover:bg-neutral-800',
	'outline'   => 'border border-neutral-900 bg-transparent text-neutral-900 hover:bg-neutral-50',
	'ghost'     => 'bg-transparent text-neutral-900 hover:bg-neutral-100',
];

$variant_class = $variants[$variant] ?? $variants['primary'];

// ── Size classes ──────────────────────────────────────────────────────────────
if ($icon_only) {
	$sizes = ['sm' => 'p-2', 'md' => 'p-3', 'lg' => 'p-3.5'];
} else {
	$sizes = ['sm' => 'px-5 py-2 text-xs', 'md' => 'px-6 py-2.5 text-sm', 'lg' => 'px-8 py-3.5 text-sm'];
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
<?php
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	else: ?>
<button type="<?php echo esc_attr(in_array($type, ['submit', 'reset'], true) ? $type : 'button'); ?>"
        class="<?php echo esc_attr($class); ?>"
        <?php
        echo $aria_attr;
        if ($disabled) {
        	echo ' disabled';
        }
        if ($attrs) {
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
