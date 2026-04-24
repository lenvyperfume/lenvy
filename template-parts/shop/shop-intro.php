<?php
/**
 * Shop intro — HARDCODED page hero (breadcrumb + big title + lede + meta).
 *
 * Consumes the placeholder-data.php 'totals' block. Will be wired up to
 * WC term counts once real products exist.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$shop_data = $args['shop_data'] ?? null;
if (!$shop_data) {
	$shop_data = require get_theme_file_path('template-parts/shop/placeholder-data.php');
}

$totals = $shop_data['totals'];
?>

<section class="lenvy-page-hero">
	<div class="lenvy-container">

		<?php get_template_part('template-parts/components/breadcrumb'); ?>

		<div class="lenvy-page-hero__grid">

			<h1 class="lenvy-page-hero__title">
				<?php esc_html_e('Alle parfums', 'lenvy'); ?>
				<em><?php esc_html_e('— een collectie voor elk moment.', 'lenvy'); ?></em>
			</h1>

			<div class="lenvy-page-hero__aside">
				<p class="lenvy-page-hero__lede">
					<?php esc_html_e('Onze complete selectie, samengesteld uit de meest iconische huizen. Van frisse citrus tot diepe oriëntaalse accoorden — vind de geur die bij je past.', 'lenvy'); ?>
				</p>
				<div class="lenvy-page-hero__meta">
					<span>
						<b><?php echo esc_html(number_format_i18n($totals['results'])); ?></b>
						<?php esc_html_e('geuren', 'lenvy'); ?>
					</span>
					<span>
						<b><?php echo esc_html(number_format_i18n($totals['houses'])); ?></b>
						<?php esc_html_e('huizen', 'lenvy'); ?>
					</span>
					<span>
						<b><?php echo esc_html(number_format_i18n($totals['families'])); ?></b>
						<?php esc_html_e('geurfamilies', 'lenvy'); ?>
					</span>
				</div>
			</div>

		</div>

	</div>
</section>
