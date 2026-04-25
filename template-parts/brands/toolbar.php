<?php
/**
 * Brands index — sticky toolbar (search + type pills + result count).
 *
 * Filtering / live search is handled client-side by brands-page.js.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$total = (int) ($args['total'] ?? 0);

$pills = [
	'all'      => __('Alle', 'lenvy'),
	'Niche'    => __('Niche', 'lenvy'),
	'Designer' => __('Designer', 'lenvy'),
	'Indie'    => __('Indie', 'lenvy'),
];
?>

<div class="lenvy-brands-toolbar" data-brands-toolbar>
	<div class="lenvy-container lenvy-brands-toolbar__row">

		<div class="lenvy-brands-search" data-brands-search>
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
				<circle cx="11" cy="11" r="7"/>
				<path d="m21 21-4.3-4.3"/>
			</svg>
			<input
				type="text"
				placeholder="<?php esc_attr_e('Zoek een merk…', 'lenvy'); ?>"
				data-brands-search-input
				aria-label="<?php esc_attr_e('Zoek een merk', 'lenvy'); ?>"
			>
			<button
				type="button"
				class="lenvy-brands-search__clear"
				data-brands-search-clear
				aria-label="<?php esc_attr_e('Wissen', 'lenvy'); ?>"
			>
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
					<path d="M18 6 6 18M6 6l12 12"/>
				</svg>
			</button>
		</div>

		<div class="lenvy-brands-pills" data-brands-pills role="tablist" aria-label="<?php esc_attr_e('Type', 'lenvy'); ?>">
			<?php foreach ($pills as $value => $label):
				$is_active = $value === 'all';
			?>
				<button
					type="button"
					class="lenvy-brands-pill<?php echo $is_active ? ' is-active' : ''; ?>"
					data-brands-pill
					data-type="<?php echo esc_attr($value); ?>"
					role="tab"
					aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
				>
					<?php echo esc_html($label); ?>
				</button>
			<?php endforeach; ?>
		</div>

		<p class="lenvy-brands-toolbar__count" data-brands-count>
			<b><?php echo esc_html(number_format_i18n($total)); ?></b>
			<?php echo esc_html(_n('merk', 'merken', $total, 'lenvy')); ?>
		</p>

	</div>
</div>
