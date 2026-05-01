<?php
/**
 * Checkout placeholder data — mirrors the design's seed.
 *
 * Used by templates/checkout-placeholder.php while there is no real WC
 * cart / checkout flow. Replace with `WC()->cart` reads + WC checkout
 * fields once products exist.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

return [
	'free_shipping_threshold' => 50,
	'shipping_cost'           => 0.0,
	'vat_rate'                => 0.21,

	'items' => [
		[
			'brand'         => 'Maison Verdier',
			'name'          => 'Lumière Boisée',
			'variant'       => '50ml · EdP',
			'priceValue'    => 128,
			'qty'           => 1,
			'variant_class' => 'v1',
		],
		[
			'brand'         => 'Byredo',
			'name'          => 'Gypsy Water',
			'variant'       => '50ml · EdP',
			'priceValue'    => 165,
			'qty'           => 1,
			'variant_class' => 'v8',
		],
		[
			'brand'         => 'Diptyque',
			'name'          => 'Philosykos',
			'variant'       => '75ml · EdT',
			'priceValue'    => 145,
			'qty'           => 1,
			'variant_class' => 'v2',
		],
	],

	'customer' => [
		'email'  => 'emma.devries@gmail.com',
		'fname'  => 'Emma',
		'lname'  => 'de Vries',
		'addr'   => 'Prinsengracht 412',
		'zip'    => '1017 KZ',
		'city'   => 'Amsterdam',
	],

	'countries' => ['Nederland', 'België', 'Duitsland', 'Frankrijk', 'Luxemburg'],

	'banks' => [
		['code' => 'ING',  'label' => 'ING'],
		['code' => 'RABO', 'label' => 'Rabobank'],
		['code' => 'ABN',  'label' => 'ABN AMRO'],
		['code' => 'SNS',  'label' => 'SNS Bank'],
		['code' => 'BUNQ', 'label' => 'bunq'],
		['code' => 'KNAB', 'label' => 'Knab'],
		['code' => 'ASN',  'label' => 'ASN Bank'],
		['code' => 'RGRO', 'label' => 'Regiobank'],
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
	],
];
