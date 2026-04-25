<?php
/**
 * Brands index — page intro (eyebrow + title + lede + stats).
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$stats = (array) ($args['stats'] ?? []);
?>

<section class="lenvy-brands-intro">
	<div class="lenvy-container">
		<span class="lenvy-brands-intro__eyebrow"><?php esc_html_e('Onze huizen', 'lenvy'); ?></span>
		<h1 class="lenvy-brands-intro__title">
			<?php esc_html_e('Alle merken,', 'lenvy'); ?><br>
			<?php esc_html_e('op alfabet.', 'lenvy'); ?>
		</h1>
		<p class="lenvy-brands-intro__lede">
			<?php
			/* translators: %d: total brand count */
			echo esc_html(sprintf(__('Van Parijse maisons met een eeuwenlange traditie tot kleine indie-ateliers die nog handmatig bottelen — bij Lenvy vind je een zorgvuldig samengestelde collectie van %d huizen.', 'lenvy'), (int) ($stats['total'] ?? 0)));
			?>
		</p>
		<div class="lenvy-brands-intro__stats">
			<div>
				<span class="k"><?php esc_html_e('Huizen', 'lenvy'); ?></span>
				<span class="v"><?php echo esc_html(number_format_i18n($stats['total'] ?? 0)); ?></span>
			</div>
			<div>
				<span class="k"><?php esc_html_e('Niche', 'lenvy'); ?></span>
				<span class="v"><?php echo esc_html(number_format_i18n($stats['Niche'] ?? 0)); ?></span>
			</div>
			<div>
				<span class="k"><?php esc_html_e('Designer', 'lenvy'); ?></span>
				<span class="v"><?php echo esc_html(number_format_i18n($stats['Designer'] ?? 0)); ?></span>
			</div>
			<div>
				<span class="k"><?php esc_html_e('Indie', 'lenvy'); ?></span>
				<span class="v"><?php echo esc_html(number_format_i18n($stats['Indie'] ?? 0)); ?></span>
			</div>
		</div>
	</div>
</section>
