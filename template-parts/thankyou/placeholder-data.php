<?php
/**
 * Thank-you placeholder data — mirrors the design's seed.
 *
 * Replace with `wc_get_order($order_id)` reads once the real WC checkout
 * flow exists.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

return [
	'order' => [
		'number'      => 'LV-26-0042871',
		'first_name'  => 'Emma',
		'email'       => 'emma.devries@gmail.com',
		'placed_at'   => __('Vandaag', 'lenvy'),
		'expected'    => __('morgen', 'lenvy'),
		'pay_method'  => 'iDEAL',
		'pay_bank'    => 'ING Bank',
		'pay_total'   => '€ 438,00',
	],

	'shipping_address' => [
		'name'    => 'Emma de Vries',
		'line1'   => 'Prinsengracht 412',
		'zip'     => '1017 KZ',
		'city'    => 'Amsterdam',
		'country' => 'Nederland',
	],

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

	'vat_rate' => 0.21,
];
