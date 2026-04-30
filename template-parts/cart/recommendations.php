<?php
/**
 * Cart page — "Misschien ook interessant" recommendation rail.
 *
 * Reuses the gradient placeholder product card so styling stays in sync
 * with the homepage / shop placeholder cards.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$cart  = (array) ($args['cart'] ?? []);
$items = (array) ($cart['recommendations'] ?? []);

if (!$items) {
	return;
}

// Same gradient palette as the other placeholder card sections.
$variants = [
	'v1' => ['bg' => 'linear-gradient(160deg, #eee1d1, #d8c3a8)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(255,255,255,0.30))'],
	'v2' => ['bg' => 'linear-gradient(160deg, #f0d9e4, #d9b3c7)', 'bottle' => 'linear-gradient(180deg, rgba(240,230,255,0.80), rgba(200,160,220,0.50))'],
	'v3' => ['bg' => 'linear-gradient(160deg, #d7dbe3, #a8b0bf)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.75), rgba(120,130,160,0.40))'],
	'v4' => ['bg' => 'linear-gradient(160deg, #e5d4f5, #b89be0)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(150,110,200,0.40))'],
	'v5' => ['bg' => 'linear-gradient(160deg, #cde0d4, #8fb09c)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(100,140,110,0.40))'],
	'v6' => ['bg' => 'linear-gradient(160deg, #f5e6b8, #d4b56a)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.80), rgba(180,140,50,0.40))'],
	'v7' => ['bg' => 'linear-gradient(160deg, #d1c9b8, #8a7f63)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(80,70,50,0.40))'],
	'v8' => ['bg' => 'linear-gradient(160deg, #e0e0dc, #a8a8a2)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.75), rgba(90,90,85,0.30))'],
];
?>

<section class="lenvy-cart-recco">
	<h2 class="lenvy-cart-recco__title"><?php esc_html_e('Misschien ook interessant', 'lenvy'); ?></h2>
	<div class="lenvy-cart-recco__grid">
		<?php foreach ($items as $i => $p):
			$v_key = (string) ($p['variant_class'] ?? 'v' . (($i % 8) + 1));
		?>
			<?php get_template_part('template-parts/components/product-card-placeholder', null, [
				'brand'             => $p['brand']   ?? '',
				'name'              => $p['name']    ?? '',
				'variant'           => $p['variant'] ?? '',
				'price'             => $p['price']   ?? '',
				'was'               => null,
				'tag'               => null,
				'v'                 => $v_key,
				'variant_gradients' => $variants,
			]); ?>
		<?php endforeach; ?>
	</div>
</section>
