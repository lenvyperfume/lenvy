<?php
/**
 * Placeholder product card — gradient "bottle" tile for mocked-up pages.
 *
 * Used while the shop is still running on hardcoded data (see
 * template-parts/shop/placeholder-data.php). Replace with the WC-backed
 * product-card.php once real products exist.
 *
 * Usage:
 *   get_template_part('template-parts/components/product-card-placeholder', null, [
 *     'brand'   => 'Byredo',
 *     'name'    => 'Gypsy Water',
 *     'variant' => '50ml · EdP',
 *     'price'   => '€ 165,00',
 *     'was'     => null, // optional — original price if on sale
 *     'tag'     => null, // null | 'new' | 'sale' | 'niche'
 *     'v'       => 'v1', // gradient key
 *     'variant_gradients' => [...], // variants array from placeholder-data
 *   ]);
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$brand    = (string) ($args['brand']   ?? '');
$name     = (string) ($args['name']    ?? '');
$variant  = (string) ($args['variant'] ?? '');
$price    = (string) ($args['price']   ?? '');
$was      = $args['was']   ?? null;
$tag      = $args['tag']   ?? null;
$v_key    = (string) ($args['v']       ?? 'v1');
$variants = $args['variant_gradients'] ?? [];

$v = $variants[$v_key] ?? null;
if (!$v) {
	$v = ['bg' => 'linear-gradient(160deg,#eee,#ddd)', 'bottle' => 'linear-gradient(180deg,#fff,#ccc)'];
}

$tag_labels = [
	'new'   => __('Nieuw', 'lenvy'),
	'sale'  => __('Sale', 'lenvy'),
	'niche' => __('Niche', 'lenvy'),
];
$tag_modifier = in_array($tag, ['new', 'sale'], true) ? $tag : 'new';
?>

<article class="lenvy-card">

	<a href="#" class="lenvy-card__img" tabindex="-1" aria-hidden="true" style="background: <?php echo esc_attr($v['bg']); ?>;">

		<span class="lenvy-card__cap" aria-hidden="true"></span>
		<span
			class="lenvy-card__bottle"
			aria-hidden="true"
			style="background: <?php echo esc_attr($v['bottle']); ?>;"
		></span>

		<?php if ($tag): ?>
		<div class="lenvy-card__tags">
			<span class="lenvy-tag lenvy-tag--<?php echo esc_attr($tag_modifier); ?>">
				<?php echo esc_html($tag_labels[$tag] ?? ucfirst((string) $tag)); ?>
			</span>
		</div>
		<?php endif; ?>

		<button
			type="button"
			class="lenvy-card__wish"
			data-wishlist-toggle
			aria-label="<?php esc_attr_e('Aan verlanglijst toevoegen', 'lenvy'); ?>"
		>
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
		</button>

		<button type="button" class="lenvy-card__quick-add">
			<?php esc_html_e('Snel toevoegen', 'lenvy'); ?>
		</button>
	</a>

	<?php if ($brand): ?>
	<p class="lenvy-card__brand"><?php echo esc_html($brand); ?></p>
	<?php endif; ?>

	<h3 class="lenvy-card__name"><a href="#"><?php echo esc_html($name); ?></a></h3>

	<?php if ($variant): ?>
	<p class="lenvy-card__variant"><?php echo esc_html($variant); ?></p>
	<?php endif; ?>

	<div class="lenvy-card__price">
		<?php if ($was): ?>
			<span class="lenvy-card__price-was"><?php echo esc_html($price); ?></span>
			<s><?php echo esc_html($was); ?></s>
		<?php else: ?>
			<span><?php echo esc_html($price); ?></span>
		<?php endif; ?>
	</div>

</article>
