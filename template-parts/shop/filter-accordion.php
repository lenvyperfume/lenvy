<?php
/**
 * Filter accordion group — collapsible filter section with chevron rotation.
 *
 * Closed state rotates the chevron -90° and collapses the body. The existing
 * accordion.js (data-filter-accordion-toggle) handles the open/close logic.
 *
 * Usage:
 *   get_template_part('template-parts/shop/filter-accordion', null, [
 *     'label'  => 'Brand',
 *     'name'   => 'brand',
 *     'open'   => true,
 *     'content'=> '<ul>...</ul>',
 *   ]);
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$label   = $args['label'] ?? '';
$name    = $args['name'] ?? 'filter';
$open    = $args['open'] ?? true;
$content = $args['content'] ?? '';

if (empty($label) || empty($content)) {
	return;
}

// Unique IDs per render — the same filter name is used in both the sidebar
// and the mobile drawer, so plain name-based IDs would collide and
// accordion.js would bind one button to the wrong panel.
if (!isset($GLOBALS['lenvy_filter_accordion_seq'])) {
	$GLOBALS['lenvy_filter_accordion_seq'] = 0;
}
$GLOBALS['lenvy_filter_accordion_seq']++;
$uid       = 'lenvy-filter-' . sanitize_html_class($name) . '-' . $GLOBALS['lenvy_filter_accordion_seq'];
$panel_id  = $uid . '-panel';
$toggle_id = $uid . '-toggle';
?>

<div class="lenvy-filter-group<?php echo $open ? '' : ' is-closed'; ?>" data-filter-accordion>
	<button
		id="<?php echo esc_attr($toggle_id); ?>"
		type="button"
		class="lenvy-filter-group__header"
		data-filter-accordion-toggle
		aria-expanded="<?php echo $open ? 'true' : 'false'; ?>"
		aria-controls="<?php echo esc_attr($panel_id); ?>"
	>
		<span class="lenvy-filter-group__title">
			<?php echo esc_html($label); ?>
		</span>
		<svg class="lenvy-filter-group__chev" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
			<path d="M3 5l4 4 4-4"/>
		</svg>
	</button>

	<div
		id="<?php echo esc_attr($panel_id); ?>"
		class="lenvy-filter-group__body"
		role="region"
		aria-labelledby="<?php echo esc_attr($toggle_id); ?>"
		data-filter-accordion-panel
		<?php if (!$open): ?>style="display:none;"<?php endif; ?>
	>
		<div class="lenvy-filter-group__pad">
			<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — caller is responsible for safe HTML
			echo $content; ?>
		</div>
	</div>
</div>
