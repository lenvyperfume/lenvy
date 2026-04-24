<?php
/**
 * Shop placeholder data — mirrors /docs/design/search-data.js.
 *
 * Used by archive-product.php (and friends) while the shop is still
 * mocked up. Replace with WooCommerce queries once real data exists.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

return [
	'collections' => ['Nieuw', 'Bestseller', 'Sale', 'Niche', 'Limited'],
	'genders'     => ['Dames', 'Heren', 'Unisex'],
	'families'    => ['Bloemig', 'Houtachtig', 'Oriëntaals', 'Fris', 'Kruidig', 'Chypre', 'Fougère', 'Gourmand'],
	'sizes'       => [30, 50, 75, 100],
	'brands'      => [
		'Aesop', 'Amouage', 'Byredo', 'Diptyque', 'Frédéric Malle',
		'Jusbox', 'Le Labo', 'Maison Verdier', 'Nishane', 'Ormonde Jayne',
		"Penhaligon's", 'Xerjoff',
	],

	'price' => [
		'min'     => 80,
		'max'     => 400,
		'current' => [80, 400],
	],

	// Counts shown in the page hero + toolbar.
	'totals' => [
		'results'  => 36,
		'houses'   => 12,
		'families' => 8,
	],

	// Gradient variants (v1–v8) matching the design.
	'variants' => [
		'v1' => ['bg' => 'linear-gradient(160deg, #eee1d1, #d8c3a8)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(255,255,255,0.30))'],
		'v2' => ['bg' => 'linear-gradient(160deg, #f0d9e4, #d9b3c7)', 'bottle' => 'linear-gradient(180deg, rgba(240,230,255,0.80), rgba(200,160,220,0.50))'],
		'v3' => ['bg' => 'linear-gradient(160deg, #d7dbe3, #a8b0bf)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.75), rgba(120,130,160,0.40))'],
		'v4' => ['bg' => 'linear-gradient(160deg, #cde0d4, #8fb09c)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(100,140,110,0.40))'],
		'v5' => ['bg' => 'linear-gradient(160deg, #f5e6b8, #d4b56a)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.80), rgba(180,140,50,0.40))'],
		'v6' => ['bg' => 'linear-gradient(160deg, #e5d4f5, #b89be0)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(150,110,200,0.40))'],
		'v7' => ['bg' => 'linear-gradient(160deg, #d1c9b8, #8a7f63)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(80,70,50,0.40))'],
		'v8' => ['bg' => 'linear-gradient(160deg, #e0e0dc, #a8a8a2)', 'bottle' => 'linear-gradient(180deg, rgba(255,255,255,0.75), rgba(90,90,85,0.30))'],
	],

	// id,brand,name,variant,price,was?,tag?, family,gender,size ml
	'products' => [
		['brand' => 'Maison Verdier',  'name' => 'Lumière Boisée',    'variant' => '50ml · EdP',    'price' => '€ 128,00', 'was' => null,      'tag' => 'niche'],
		['brand' => 'Byredo',          'name' => 'Gypsy Water',       'variant' => '50ml · EdP',    'price' => '€ 165,00', 'was' => null,      'tag' => null],
		['brand' => 'Diptyque',        'name' => 'Philosykos',        'variant' => '75ml · EdT',    'price' => '€ 145,00', 'was' => null,      'tag' => null],
		['brand' => 'Le Labo',         'name' => 'Santal 33',         'variant' => '50ml · EdP',    'price' => '€ 210,00', 'was' => null,      'tag' => null],
		['brand' => 'Maison Verdier',  'name' => 'Jasmin de Nuit',    'variant' => '50ml · EdP',    'price' => '€ 142,00', 'was' => null,      'tag' => 'new'],
		['brand' => 'Frédéric Malle',  'name' => 'Carnal Flower',     'variant' => '50ml · EdP',    'price' => '€ 285,00', 'was' => null,      'tag' => null],
		['brand' => 'Aesop',           'name' => 'Hwyl',              'variant' => '50ml · EdP',    'price' => '€ 175,00', 'was' => null,      'tag' => null],
		['brand' => 'Amouage',         'name' => 'Interlude Man',     'variant' => '100ml · EdP',   'price' => '€ 330,00', 'was' => null,      'tag' => null],
		['brand' => 'Nishane',         'name' => 'Hacivat',           'variant' => '50ml · Extrait','price' => '€ 195,00', 'was' => null,      'tag' => null],
		['brand' => 'Ormonde Jayne',   'name' => "Ta'if",             'variant' => '50ml · EdP',    'price' => '€ 175,00', 'was' => null,      'tag' => null],
		['brand' => 'Xerjoff',         'name' => 'Naxos',             'variant' => '50ml · EdP',    'price' => '€ 295,00', 'was' => null,      'tag' => null],
		['brand' => 'Diptyque',        'name' => 'Do Son',            'variant' => '75ml · EdT',    'price' => '€ 145,00', 'was' => null,      'tag' => null],
		['brand' => 'Maison Verdier',  'name' => 'Ambre Fumée',       'variant' => '50ml · EdP',    'price' => '€ 152,00', 'was' => null,      'tag' => null],
		['brand' => 'Byredo',          'name' => 'Mojave Ghost',      'variant' => '50ml · EdP',    'price' => '€ 175,00', 'was' => null,      'tag' => null],
		['brand' => 'Le Labo',         'name' => 'Rose 31',           'variant' => '50ml · EdP',    'price' => '€ 210,00', 'was' => null,      'tag' => null],
		['brand' => "Penhaligon's",    'name' => 'Halfeti',           'variant' => '75ml · EdP',    'price' => '€ 210,00', 'was' => null,      'tag' => null],
		['brand' => 'Jusbox',          'name' => 'Night Balm',        'variant' => '78ml · EdP',    'price' => '€ 185,00', 'was' => null,      'tag' => null],
		['brand' => 'Aesop',           'name' => 'Rozu',              'variant' => '50ml · EdP',    'price' => '€ 175,00', 'was' => null,      'tag' => null],
		['brand' => 'Maison Verdier',  'name' => "Fleur d'Oranger",   'variant' => '50ml · EdP',    'price' => '€ 128,00', 'was' => null,      'tag' => 'new'],
		['brand' => 'Byredo',          'name' => "Bal d'Afrique",     'variant' => '100ml · EdP',   'price' => '€ 230,00', 'was' => null,      'tag' => null],
		['brand' => 'Diptyque',        'name' => 'Eau Rose',          'variant' => '50ml · EdT',    'price' => '€ 89,00',  'was' => '€ 115,00', 'tag' => 'sale'],
		['brand' => 'Le Labo',         'name' => 'Bergamote 22',      'variant' => '50ml · EdP',    'price' => '€ 210,00', 'was' => null,      'tag' => null],
		['brand' => 'Frédéric Malle',  'name' => 'Portrait of a Lady','variant' => '50ml · EdP',    'price' => '€ 295,00', 'was' => null,      'tag' => null],
		['brand' => 'Nishane',         'name' => 'Ani',               'variant' => '50ml · Extrait','price' => '€ 195,00', 'was' => null,      'tag' => null],
		['brand' => 'Ormonde Jayne',   'name' => 'Ormonde Man',       'variant' => '50ml · EdP',    'price' => '€ 165,00', 'was' => null,      'tag' => null],
		['brand' => 'Xerjoff',         'name' => 'Erba Pura',         'variant' => '100ml · EdP',   'price' => '€ 345,00', 'was' => null,      'tag' => null],
		['brand' => 'Amouage',         'name' => 'Reflection Woman',  'variant' => '100ml · EdP',   'price' => '€ 310,00', 'was' => null,      'tag' => null],
		['brand' => "Penhaligon's",    'name' => 'Endymion',          'variant' => '100ml · EdC',   'price' => '€ 195,00', 'was' => null,      'tag' => null],
		['brand' => 'Jusbox',          'name' => 'Cheeky Smile',      'variant' => '78ml · EdP',    'price' => '€ 185,00', 'was' => null,      'tag' => null],
		['brand' => 'Maison Verdier',  'name' => 'Vetiver Noir',      'variant' => '50ml · EdP',    'price' => '€ 138,00', 'was' => null,      'tag' => null],
		['brand' => 'Byredo',          'name' => 'Blanche',           'variant' => '50ml · EdP',    'price' => '€ 165,00', 'was' => null,      'tag' => null],
		['brand' => 'Le Labo',         'name' => 'Another 13',        'variant' => '50ml · EdP',    'price' => '€ 220,00', 'was' => null,      'tag' => null],
		['brand' => 'Diptyque',        'name' => 'Tam Dao',           'variant' => '75ml · EdP',    'price' => '€ 165,00', 'was' => null,      'tag' => null],
		['brand' => 'Aesop',           'name' => 'Tacit',             'variant' => '50ml · EdP',    'price' => '€ 115,00', 'was' => '€ 145,00', 'tag' => 'sale'],
		['brand' => 'Amouage',         'name' => 'Guidance',          'variant' => '100ml · EdP',   'price' => '€ 395,00', 'was' => null,      'tag' => null],
		['brand' => 'Nishane',         'name' => 'Papilefiko',        'variant' => '50ml · Extrait','price' => '€ 175,00', 'was' => null,      'tag' => 'new'],
	],
];
