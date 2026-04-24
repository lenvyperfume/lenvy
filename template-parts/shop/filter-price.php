<?php
/**
 * Price range filter — HARDCODED dual-handle slider.
 *
 * Ranges come from placeholder-data.php. Re-wire to WC
 * once real product pricing exists.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$label = (string) ($args['label'] ?? __('Prijs', 'lenvy'));
$open  = (bool)   ($args['open']  ?? true);
$price = (array)  ($args['price'] ?? ['min' => 0, 'max' => 400, 'current' => [0, 400]]);

$global_min  = (int) ($price['min'] ?? 0);
$global_max  = (int) ($price['max'] ?? 400);
$current_min = (int) ($price['current'][0] ?? $global_min);
$current_max = (int) ($price['current'][1] ?? $global_max);

if ($global_min >= $global_max) {
	return;
}

ob_start();
?>
<div
	class="lenvy-price"
	data-price-slider
	data-min="<?php echo esc_attr($global_min); ?>"
	data-max="<?php echo esc_attr($global_max); ?>"
	data-current-min="<?php echo esc_attr($current_min); ?>"
	data-current-max="<?php echo esc_attr($current_max); ?>"
>
	<div class="lenvy-price__track" data-slider-track>
		<div class="lenvy-price__fill" data-slider-range></div>
		<button
			type="button"
			class="lenvy-price__thumb"
			data-slider-thumb="min"
			aria-label="<?php esc_attr_e('Minimale prijs', 'lenvy'); ?>"
			aria-valuemin="<?php echo esc_attr($global_min); ?>"
			aria-valuemax="<?php echo esc_attr($global_max); ?>"
			aria-valuenow="<?php echo esc_attr($current_min); ?>"
			role="slider"
		></button>
		<button
			type="button"
			class="lenvy-price__thumb"
			data-slider-thumb="max"
			aria-label="<?php esc_attr_e('Maximale prijs', 'lenvy'); ?>"
			aria-valuemin="<?php echo esc_attr($global_min); ?>"
			aria-valuemax="<?php echo esc_attr($global_max); ?>"
			aria-valuenow="<?php echo esc_attr($current_max); ?>"
			role="slider"
		></button>
	</div>

	<input type="hidden" name="min_price" value="<?php echo esc_attr($current_min); ?>" data-slider-input="min">
	<input type="hidden" name="max_price" value="<?php echo esc_attr($current_max); ?>" data-slider-input="max">

	<div class="lenvy-price__inputs">
		<div class="lenvy-price__field">
			<span data-slider-label="min">€ <?php echo esc_html(number_format_i18n($current_min, 0)); ?></span>
		</div>
		<span class="lenvy-price__dash">—</span>
		<div class="lenvy-price__field">
			<span data-slider-label="max">€ <?php echo esc_html(number_format_i18n($current_max, 0)); ?></span>
		</div>
	</div>
</div>
<?php
$content = ob_get_clean();

get_template_part(
	'template-parts/shop/filter-accordion',
	null,
	compact('label', 'open', 'content') + ['name' => 'price'],
);
