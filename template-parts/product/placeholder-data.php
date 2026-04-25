<?php
/**
 * Product (PDP) placeholder data — mirrors /docs/design/Product.html.
 *
 * Used by templates/product-placeholder.php while the product detail page is
 * still mocked up. Replace with WC / ACF reads once real products exist.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

return [
	'breadcrumb' => [
		['label' => __('Home', 'lenvy'),           'url' => home_url('/')],
		['label' => __('Heren', 'lenvy'),          'url' => '#'],
		['label' => 'Maison Verdier',              'url' => '#'],
		['label' => 'Lumière Boisée',              'url' => null],
	],

	'brand'       => 'Maison Verdier · Paris',
	'name'        => 'Lumière Boisée',
	'subtitle'    => __('Eau de Parfum · Houtig · Unisex', 'lenvy'),
	'intro'       => __('Een warme, rokerige lichtheid — cederhout en iris verweven met een hint van vanille en amber. Gemaakt door Juliette Verdier in haar atelier aan de Rue de Sèvres.', 'lenvy'),
	'badge_free'  => __('Gratis 2 samples', 'lenvy'),
	'season_tag'  => __('Past bij laat-najaar', 'lenvy'),

	'meters' => [
		[
			'label' => __('Longevity', 'lenvy'),
			'value' => __('8 – 10 uur', 'lenvy'),
			'fill'  => 4, // 0..5
		],
		[
			'label' => __('Sillage', 'lenvy'),
			'value' => __('Matig · zacht', 'lenvy'),
			'fill'  => 3,
		],
	],

	'sizes' => [
		['ml' => 30,  'price' => 78,  'badge' => __('Reisformaat', 'lenvy'),  'on' => false],
		['ml' => 50,  'price' => 108, 'badge' => __('Bestseller', 'lenvy'),    'on' => true],
		['ml' => 100, 'price' => 168, 'badge' => __('Beste waarde', 'lenvy'),  'on' => false],
	],

	'service' => [
		[
			'icon'     => 'truck',
			'title'    => __('Morgen in huis', 'lenvy'),
			'subtitle' => __('Vóór 22:00 besteld', 'lenvy'),
		],
		[
			'icon'     => 'shield',
			'title'    => __('100% origineel', 'lenvy'),
			'subtitle' => __('Echtheidsgarantie', 'lenvy'),
		],
		[
			'icon'     => 'gift',
			'title'    => __('2 gratis samples', 'lenvy'),
			'subtitle' => __('Keuze bij checkout', 'lenvy'),
		],
		[
			'icon'     => 'return',
			'title'    => __('30 dagen retour', 'lenvy'),
			'subtitle' => __('Ook geopend', 'lenvy'),
		],
	],

	'specs' => [
		['label' => __('Concentratie', 'lenvy'), 'value' => 'EDP'],
		['label' => __('Familie', 'lenvy'),      'value' => __('Houtig · Balsem', 'lenvy')],
		['label' => __('Geslacht', 'lenvy'),     'value' => __('Unisex', 'lenvy')],
		['label' => __('Jaar', 'lenvy'),         'value' => '2023'],
	],

	// Gallery — main shot uses 'main_bg', additional shots are styled via CSS classes
	'gallery' => [
		'main_bg' => 'linear-gradient(160deg, #eee1d1, #d8c3a8)',
		'thumbs'  => [
			['key' => 't1', 'on' => true],
			['key' => 't2', 'on' => false],
			['key' => 't3', 'on' => false],
			['key' => 't4', 'on' => false],
		],
		'thumb_bgs' => [
			'linear-gradient(160deg, #eee1d1, #d8c3a8)',
			'linear-gradient(140deg, #2c2217 0%, #5a3f25 100%)',
			'linear-gradient(140deg, #8a7355 0%, #443323 100%)',
			'linear-gradient(140deg, #d4c5aa, #a68b66)',
		],
		'extra' => [
			['class' => 's2', 'caption' => __('Atelier · Rue de Sèvres', 'lenvy')],
			['class' => 's3', 'caption' => __('Grondstoffen · cederhout, vanille, iris', 'lenvy')],
			['class' => 's4', 'caption' => __('Detail · de flacon in licht', 'lenvy')],
		],
		'label_html' => 'Maison Verdier<br>— Lumière Boisée —<br>Paris',
	],

	// Scent pyramid
	'pyramid' => [
		'lede' => [
			__('Een parfum is nooit één geur, maar een traject. Lumière Boisée opent licht en helder — bergamot en roze peper — om daarna via een hart van iris en cederhout te verdiepen in een warme, balsamische basis van vanille en amber.', 'lenvy'),
			__('Een geur die langzaam zijn verhaal vertelt en zich aanpast aan de warmte van je huid.', 'lenvy'),
		],
		'quote' => [
			'text' => __('"Ik wilde een parfum maken dat voelt als een koude wandeling door een park bij zonsondergang — licht in de opening, warm in de huid."', 'lenvy'),
			'cite' => __('— Juliette Verdier, parfumeur', 'lenvy'),
		],
		'rows' => [
			[
				'tier'  => 'top',
				'eyebrow' => __('Kopnoten · eerste 15 min', 'lenvy'),
				'title' => __('Top', 'lenvy'),
				'notes' => [__('Bergamot uit Calabrië', 'lenvy'), __('Roze peper', 'lenvy'), __('Mandarijn', 'lenvy')],
				'desc'  => __('Helder en licht — een korte, sprankelende opening die de kamer binnen loopt zonder hard te zijn.', 'lenvy'),
			],
			[
				'tier'  => 'heart',
				'eyebrow' => __('Hartnoten · 30 min – 2 u', 'lenvy'),
				'title' => __('Hart', 'lenvy'),
				'notes' => ['Iris pallida', __('Violet', 'lenvy'), __('Cederhout', 'lenvy'), __('Pruim', 'lenvy')],
				'desc'  => __('Poederig en rond — de iris opent zich langzaam en legt de houten ruggengraat van het parfum bloot.', 'lenvy'),
			],
			[
				'tier'  => 'base',
				'eyebrow' => __('Basisnoten · 4 u en langer', 'lenvy'),
				'title' => __('Basis', 'lenvy'),
				'notes' => [__('Vanille de Madagascar', 'lenvy'), __('Amber', 'lenvy'), __('Benzoë', 'lenvy'), __('Sandelhout', 'lenvy'), __('Muskus', 'lenvy')],
				'desc'  => __('De warme, balsamische kern — vanille en amber smeulend op de huid, uren na de eerste spuit nog aanwezig.', 'lenvy'),
			],
		],
	],

	// House story
	'story' => [
		'eyebrow' => __('Het huis · Maison Verdier', 'lenvy'),
		'title'   => __('Parfum als geschreven brief.', 'lenvy'),
		'paragraphs' => [
			__('Maison Verdier werd in 2014 gesticht door Juliette Verdier, een derde-generatie parfumeur uit Grasse. Na tien jaar werken voor de grote huizen opende ze haar eigen atelier aan de Rue de Sèvres in Parijs, met één voorwaarde: alleen geuren maken waar ze zelf volledig achter staat.', 'lenvy'),
			__('Elk parfum van Maison Verdier wordt ontwikkeld in kleine batches, met grondstoffen die rechtstreeks van de producent komen — iris uit Pallida, vanille uit Madagascar, cederhout uit de Atlas. Geen haast, geen compromis.', 'lenvy'),
		],
		'meta' => [
			['k' => __('Opgericht', 'lenvy'), 'v' => __('Parijs, 2014', 'lenvy')],
			['k' => __('Parfumeur', 'lenvy'), 'v' => 'Juliette Verdier'],
			['k' => __('Productie', 'lenvy'), 'v' => 'Grasse, FR'],
		],
		'cred' => __('— Atelier, Rue de Sèvres', 'lenvy'),
	],

	'ingredients' => [
		[
			'class'  => 'i1',
			'title'  => 'Bergamot',
			'origin' => 'Calabrië, IT',
			'desc'   => __('Koud geperst uit de schil. Helder, fris, met een licht bittere ondertoon die de opening wakker maakt.', 'lenvy'),
		],
		[
			'class'  => 'i2',
			'title'  => 'Iris pallida',
			'origin' => 'Florence, IT',
			'desc'   => __('Uit wortels die drie jaar rijpen voor ze verwerkt worden. Poederig, violet, licht aardig.', 'lenvy'),
		],
		[
			'class'  => 'i3',
			'title'  => __('Cederhout', 'lenvy'),
			'origin' => 'Atlas, MA',
			'desc'   => __('Gedestilleerd uit oud atlascederhout. Droog, gepolijst, geeft de geur zijn houten ruggengraat.', 'lenvy'),
		],
		[
			'class'  => 'i4',
			'title'  => __('Vanille', 'lenvy'),
			'origin' => 'Madagascar',
			'desc'   => __('Zes maanden macereren in alcohol. Romig en balsamisch — nooit zoet — met een subtiel rokerige kern.', 'lenvy'),
		],
	],

	'reviews' => [
		'score'    => '4,8',
		'count'    => 342,
		'eyebrow'  => __('Recensies · 342 beoordelingen', 'lenvy'),
		'title'    => __('Wat onze klanten vinden', 'lenvy'),
		'breakdown' => [
			['stars' => 5, 'pct' => 78, 'count' => 267],
			['stars' => 4, 'pct' => 16, 'count' => 54],
			['stars' => 3, 'pct' => 4,  'count' => 14],
			['stars' => 2, 'pct' => 1,  'count' => 4],
			['stars' => 1, 'pct' => 1,  'count' => 3],
		],
		'items' => [
			[
				'initials' => 'MS',
				'name'     => 'Marieke S.',
				'meta'     => __('Amsterdam · 12 maart 2026', 'lenvy'),
				'stars'    => 5,
				'title'    => __('Warm en intiem zonder zwaar te worden.', 'lenvy'),
				'body'     => __('Ik draag Lumière Boisée nu een paar maanden en het blijft verrassen. De opening is fris met bergamot, maar langzaam wordt het een zachte, houten gloed die dicht op de huid blijft. Krijg vaak complimenten — meestal van mensen die heel dichtbij komen staan, wat ik juist fijn vind. Niet het type parfum dat door een kamer walmt.', 'lenvy'),
				'foot'     => [__('Huidtype · droog', 'lenvy'), __('Moment · avond', 'lenvy'), __('Was handig? · 34', 'lenvy')],
			],
			[
				'initials' => 'TJ',
				'name'     => 'Thomas J.',
				'meta'     => __('Gent · 28 februari 2026', 'lenvy'),
				'stars'    => 5,
				'title'    => __('Vijf sterren, maar hou rekening met de sillage.', 'lenvy'),
				'body'     => __("Prachtige geur, zeker voor de overgang van herfst naar winter. De iris en cederhout zijn subtiel — voor mij precies goed, maar als je houdt van een parfum dat duidelijk aanwezig is, is deze misschien te zacht. Samples vooraf echt aan te raden. De flacon is trouwens oogstrelend.", 'lenvy'),
				'foot'     => [__('Huidtype · normaal', 'lenvy'), __('Moment · dagelijks', 'lenvy'), __('Was handig? · 22', 'lenvy')],
			],
			[
				'initials' => 'LV',
				'name'     => 'Liesbeth V.',
				'meta'     => __('Utrecht · 14 februari 2026', 'lenvy'),
				'stars'    => 4,
				'title'    => __('Beter dan verwacht voor unisex.', 'lenvy'),
				'body'     => __("Gekocht als cadeau voor mijn partner en hem stiekem zelf een beetje opgedragen. Werkt op beide huidtypes heel anders uit — bij hem wat droger en warmer, bij mij iets bloemiger door de iris. Enige minpunt: de projectie is echt zacht, je moet 'm best genereus opspuiten.", 'lenvy'),
				'foot'     => [__('Huidtype · vet', 'lenvy'), __('Moment · kantoor', 'lenvy'), __('Was handig? · 18', 'lenvy')],
			],
		],
	],

	'faq' => [
		'eyebrow' => __('Veelgestelde vragen', 'lenvy'),
		'title'   => __('Alles wat je wil weten voor je bestelt.', 'lenvy'),
		'lede'    => __('Niet het juiste antwoord gevonden? Onze geuradviseurs zijn bereikbaar via chat of WhatsApp.', 'lenvy'),
		'items'   => [
			['q' => __('Hoe weet ik of dit parfum bij mij past?', 'lenvy'),       'a' => __('Voor alle parfums op Lenvy bieden we samples (2 ml) aan waarmee je de geur in alle rust kunt testen. Een parfum draagt minstens zes uur verschillend — begin je dag met een sample en zie hoe het zich ontwikkelt. Bij Lumière Boisée adviseren we twee dagen proef voor je een volumebeslissing maakt.', 'lenvy'), 'open' => true],
			['q' => __('Is Lumière Boisée geschikt voor de zomer?', 'lenvy'),     'a' => __('Technisch wel — de opening is licht genoeg — maar we adviseren laat-voorjaar tot vroeg-winter. In de hitte kan de amberbasis zwaarder aanvoelen dan bedoeld.', 'lenvy')],
			['q' => __('Hoe bewaar ik mijn parfum?', 'lenvy'),                    'a' => __('Koel, droog en uit direct zonlicht. De originele doos beschermt tegen UV-schade. Niet in de badkamer — de temperatuurwisselingen tasten de geur aan.', 'lenvy')],
			['q' => __('Hoe wordt het pakket verzonden?', 'lenvy'),               'a' => __('PostNL of DPD in Nederland en België. Verzekerd, gevolgd en verpakt in ons recyclebare karton. Besteld vóór 22:00, morgen in huis.', 'lenvy')],
			['q' => __('Kan ik retourneren als het toch niet bevalt?', 'lenvy'),  'a' => __('Ja — binnen 30 dagen, ook als de flacon geopend is. We geloven niet in het verplichten van een aankoop die niet past. Gratis retourlabel inbegrepen.', 'lenvy')],
			['q' => __('Zijn de parfums echt origineel?', 'lenvy'),               'a' => __('Altijd. We kopen uitsluitend rechtstreeks bij het huis of via geautoriseerde distributeurs. Elke flacon wordt voor verzending gecontroleerd door ons team. 100% echtheidsgarantie.', 'lenvy')],
		],
	],
];
