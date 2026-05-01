<?php
/**
 * Cart page placeholder data
 *
 * Used by templates/cart-placeholder.php while there is no real WC cart
 * to read from. Replace with `WC()->cart->get_cart()` once products exist.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

return [
	'free_shipping_threshold' => 50,
	'shipping_cost'           => 4.95,
	'vat_rate'                => 0.21,

	// Items in the cart at page load (mirrors the design's seed of 3).
	'items' => [
		[
			'id'            => 1,
			'brand'         => 'Maison Verdier',
			'name'          => 'Lumière Boisée',
			'variant'       => '50ml · EdP',
			'priceValue'    => 128,
			'qty'           => 1,
			'variant_class' => 'v1',
			'meta'          => ['Houtachtig', 'Unisex', 'Niche'],
		],
		[
			'id'            => 2,
			'brand'         => 'Byredo',
			'name'          => 'Gypsy Water',
			'variant'       => '50ml · EdP',
			'priceValue'    => 165,
			'qty'           => 1,
			'variant_class' => 'v8',
			'meta'          => ['Houtachtig', 'Unisex', 'Bestseller'],
		],
		[
			'id'            => 3,
			'brand'         => 'Diptyque',
			'name'          => 'Philosykos',
			'variant'       => '75ml · EdT',
			'priceValue'    => 145,
			'qty'           => 1,
			'variant_class' => 'v2',
			'meta'          => ['Fris · vijg', 'Unisex'],
		],
	],

	// "Misschien ook interessant" rail — same shape as the placeholder shop.
	'recommendations' => [
		['brand' => 'Maison Verdier', 'name' => 'Jasmin de Nuit', 'variant' => '50ml · EdP', 'price' => '€ 142,00', 'variant_class' => 'v4'],
		['brand' => 'Le Labo',        'name' => 'Santal 33',       'variant' => '50ml · EdP', 'price' => '€ 210,00', 'variant_class' => 'v3'],
		['brand' => 'Aesop',          'name' => 'Hwyl',            'variant' => '50ml · EdP', 'price' => '€ 175,00', 'variant_class' => 'v5'],
		['brand' => 'Diptyque',       'name' => 'Do Son',          'variant' => '75ml · EdT', 'price' => '€ 145,00', 'variant_class' => 'v7'],
	],

	'trust' => [
		[
			'icon'  => 'shield',
			'title' => __('100% origineel', 'lenvy'),
			'desc'  => __('Echtheidsgarantie · rechtstreeks van het huis', 'lenvy'),
		],
		[
			'icon'  => 'truck',
			'title' => __('Morgen in huis', 'lenvy'),
			'desc'  => __('Vóór 22:00 besteld · gratis vanaf €50', 'lenvy'),
		],
		[
			'icon'  => 'return',
			'title' => __('30 dagen retour', 'lenvy'),
			'desc'  => __('Ook voor geopende parfums', 'lenvy'),
		],
		[
			'icon'  => 'chat',
			'title' => __('Persoonlijk advies', 'lenvy'),
			'desc'  => __('Stuur een berichtje · ma–vr 09:00–18:00', 'lenvy'),
		],
	],
];
