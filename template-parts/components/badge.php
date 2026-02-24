<?php
/**
 * Badge component — small label for products and taxonomy terms.
 *
 * Usage:
 *   get_template_part('template-parts/components/badge', null, [
 *     'text'    => 'Sale',    // required
 *     'variant' => 'sale',    // sale|new|oos|custom
 *   ]);
 *
 * Variants:
 *   sale   — brand primary background (lavender) — used on sale products
 *   new    — black fill — new arrivals
 *   oos    — neutral muted — out of stock
 *   custom — neutral light — any other label
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$text    = $args['text']    ?? '';
$variant = $args['variant'] ?? 'custom';

if ( empty( $text ) ) {
	return;
}

$variants = [
	'sale'   => 'bg-primary text-black',
	'new'    => 'bg-black text-white',
	'oos'    => 'bg-neutral-200 text-neutral-500',
	'custom' => 'bg-neutral-100 text-neutral-700',
];

$variant_class = $variants[ $variant ] ?? $variants['custom'];
?>
<span class="inline-block px-2 py-0.5 text-xs font-medium uppercase tracking-widest <?php echo esc_attr( $variant_class ); ?>">
	<?php echo esc_html( $text ); ?>
</span>
