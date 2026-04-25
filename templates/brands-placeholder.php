<?php
/**
 * Brands index placeholder page.
 *
 * Reachable at /merken/. All content is sourced from
 * template-parts/brands/placeholder-data.php; replace with `get_terms()`
 * once the product_brand taxonomy is populated.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$brands = require get_theme_file_path('template-parts/brands/placeholder-data.php');

// Sort alphabetically (case-insensitive).
usort($brands, static fn($a, $b) => strcasecmp($a['name'], $b['name']));

// Bucket by initial letter (uppercase, first char only).
$groups = [];
foreach ($brands as $b) {
	$letter = strtoupper(mb_substr($b['name'], 0, 1, 'UTF-8'));
	$groups[$letter][] = $b;
}
ksort($groups);

// Type counts for the page-intro stats.
$by_type = ['Niche' => 0, 'Designer' => 0, 'Indie' => 0];
foreach ($brands as $b) {
	$type = (string) ($b['type'] ?? '');
	if (isset($by_type[$type])) {
		$by_type[$type]++;
	}
}

$stats = [
	'total'    => count($brands),
	'Niche'    => $by_type['Niche'],
	'Designer' => $by_type['Designer'],
	'Indie'    => $by_type['Indie'],
];

$present_letters = array_keys($groups);

get_header();
?>

<main id="primary" class="lenvy-brands" data-brands-page>

	<?php get_template_part('template-parts/brands/page-intro', null, ['stats' => $stats]); ?>

	<?php get_template_part('template-parts/brands/toolbar', null, ['total' => $stats['total']]); ?>

	<div class="lenvy-container">
		<div class="lenvy-brands-wrap">

			<div class="lenvy-brands-content" data-brands-content>
				<?php foreach ($groups as $letter => $items): ?>
					<section class="lenvy-brands-letter" id="brands-L-<?php echo esc_attr($letter); ?>" data-brands-letter="<?php echo esc_attr($letter); ?>">
						<header class="lenvy-brands-letter__head">
							<span class="lenvy-brands-letter__big"><?php echo esc_html($letter); ?></span>
							<span class="lenvy-brands-letter__meta" data-brands-letter-meta data-template="<?php echo esc_attr__('%d merk', 'lenvy'); ?>" data-template-plural="<?php echo esc_attr__('%d merken', 'lenvy'); ?>">
								<?php echo esc_html(sprintf(_n('%d merk', '%d merken', count($items), 'lenvy'), count($items))); ?>
							</span>
						</header>
						<div class="lenvy-brands-grid">
							<?php foreach ($items as $b): ?>
								<?php get_template_part('template-parts/brands/brand-tile', null, ['brand' => $b]); ?>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endforeach; ?>

				<div class="lenvy-brands-empty" data-brands-empty hidden>
					<h3><?php esc_html_e('Geen merken gevonden', 'lenvy'); ?></h3>
					<p><?php esc_html_e('Probeer een andere zoekterm of reset de filters.', 'lenvy'); ?></p>
					<button type="button" data-brands-reset>
						<?php esc_html_e('Reset filters', 'lenvy'); ?>
					</button>
				</div>
			</div>

			<?php get_template_part('template-parts/brands/letter-rail', null, ['letters' => $present_letters]); ?>

		</div>
	</div>

</main>

<?php
get_footer();
