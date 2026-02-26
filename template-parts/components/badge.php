<?php
/**
 * Badge component — small label for products and taxonomy terms.
 *
 * Usage:
 *   get_template_part('template-parts/components/badge', null, [
 *     'text'    => 'Sale',
 *     'variant' => 'sale',    // sale|new|oos|custom
 *   ]);
 *
 * Variants:
 *   sale   — black fill, white text
 *   new    — black fill, white text
 *   oos    — text-only, muted (no background)
 *   custom — subtle neutral background
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$text    = $args['text'] ?? '';
$variant = $args['variant'] ?? 'custom';

if (empty($text)) {
	return;
}

$variants = [
	'sale'   => 'bg-black text-white px-2.5 py-1',
	'new'    => 'bg-primary text-black px-2.5 py-1',
	'oos'    => 'text-neutral-400',
	'custom' => 'bg-neutral-100 text-neutral-700 px-2.5 py-1',
];

$variant_class = $variants[$variant] ?? $variants['custom'];
?>
<span class="inline-block text-[10px] font-medium uppercase tracking-widest <?php echo esc_attr($variant_class); ?>">
	<?php echo esc_html($text); ?>
</span>
