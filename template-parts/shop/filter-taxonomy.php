<?php
/**
 * Filter group — HARDCODED checkbox list with optional inline search.
 *
 * Accepts a flat array of option labels ($args['options']). Counts default
 * to empty (no WC backend yet).
 *
 * Usage:
 *   get_template_part('template-parts/shop/filter-taxonomy', null, [
 *     'name'       => 'brand',          // unique key for accordion panel ID
 *     'label'      => 'Merk',
 *     'options'    => ['Aesop', 'Byredo', ...],
 *     'counts'     => ['Aesop' => 12, ...], // optional
 *     'open'       => true,
 *     'searchable' => true,             // show in-group search field
 *   ]);
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$name       = (string) ($args['name']       ?? 'filter');
$label      = (string) ($args['label']      ?? '');
$options    = (array)  ($args['options']    ?? []);
$counts     = (array)  ($args['counts']     ?? []);
$open       = (bool)   ($args['open']       ?? true);
$searchable = (bool)   ($args['searchable'] ?? false);

if (!$label || !$options) {
	return;
}

ob_start();
?>
<?php if ($searchable): ?>
<div class="lenvy-filter-search">
	<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
		<circle cx="11" cy="11" r="7"/>
		<path d="m21 21-4.3-4.3"/>
	</svg>
	<input
		type="text"
		placeholder="<?php echo esc_attr(sprintf(__('Zoek %s…', 'lenvy'), strtolower($label))); ?>"
		data-filter-search
		aria-label="<?php echo esc_attr(sprintf(__('Zoek %s', 'lenvy'), strtolower($label))); ?>"
	>
</div>
<?php endif; ?>

<ul class="lenvy-filter-opts<?php echo $searchable ? ' lenvy-filter-opts--scroll' : ''; ?>" role="list" data-filter-opts>
	<?php foreach ($options as $opt):
		$label_str = (string) $opt;
		$slug      = sanitize_title($label_str);
		$input_id  = 'filter-' . esc_attr($name) . '-' . esc_attr($slug);
		$count     = $counts[$label_str] ?? null;
	?>
		<li class="lenvy-opt" data-label="<?php echo esc_attr(strtolower($label_str)); ?>">
			<label for="<?php echo esc_attr($input_id); ?>" class="lenvy-opt__label">
				<input
					type="checkbox"
					id="<?php echo esc_attr($input_id); ?>"
					name="filter_<?php echo esc_attr($name); ?>[]"
					value="<?php echo esc_attr($slug); ?>"
					class="lenvy-opt__input"
					data-filter-checkbox
				>
				<span class="lenvy-opt__check" aria-hidden="true"></span>
				<span class="lenvy-opt__name"><?php echo esc_html($label_str); ?></span>
			</label>
			<?php if ($count !== null): ?>
				<span class="lenvy-opt__count"><?php echo esc_html($count); ?></span>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>
<?php
$content = ob_get_clean();

get_template_part(
	'template-parts/shop/filter-accordion',
	null,
	compact('label', 'open', 'content', 'name'),
);
