<?php
/**
 * Filter accordion group — collapsible wrapper for a single filter section.
 *
 * Usage:
 *   get_template_part('template-parts/shop/filter-accordion', null, [
 *     'label'  => 'Brand',    // section heading
 *     'name'   => 'brand',    // used for aria IDs; must be unique per page
 *     'open'   => true,       // expanded by default?
 *     'content'=> '<ul>...</ul>', // rendered HTML — use ob_start() at call site
 *   ]);
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$label   = $args['label']   ?? '';
$name    = $args['name']    ?? 'filter';
$open    = $args['open']    ?? true;
$content = $args['content'] ?? '';

if ( empty( $label ) || empty( $content ) ) {
	return;
}

$panel_id  = 'filter-panel-' . esc_attr( $name );
$toggle_id = 'filter-toggle-' . esc_attr( $name );
?>

<div class="border-b border-neutral-100 py-4" data-filter-accordion>
	<button
		id="<?php echo $toggle_id; ?>"
		type="button"
		class="flex items-center justify-between w-full text-left"
		data-filter-accordion-toggle
		aria-expanded="<?php echo $open ? 'true' : 'false'; ?>"
		aria-controls="<?php echo $panel_id; ?>"
	>
		<span class="text-xs font-semibold uppercase tracking-widest text-neutral-800">
			<?php echo esc_html( $label ); ?>
		</span>
		<?php lenvy_icon( 'chevron-down', 'transition-transform duration-200', 'xs' ); ?>
	</button>

	<div
		id="<?php echo $panel_id; ?>"
		role="region"
		aria-labelledby="<?php echo $toggle_id; ?>"
		data-filter-accordion-panel
		<?php if ( ! $open ) : ?>style="display:none;"<?php endif; ?>
	>
		<div class="pt-3">
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — caller is responsible for safe HTML
			echo $content;
			?>
		</div>
	</div>
</div>
