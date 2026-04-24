<?php
/**
 * Active filter chips — dark pill style, each chip removes its filter.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$active = lenvy_get_active_filters();

if (empty($active)) {
	return;
}

// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
$base_url = strtok((string) $_SERVER['REQUEST_URI'], '?');
?>

<div class="lenvy-chips" data-active-filters>

	<span class="lenvy-chips__label"><?php esc_html_e('Filters', 'lenvy'); ?></span>

	<?php foreach ($active as $filter): ?>
		<?php $remove_url = add_query_arg($filter['remove_args'], $base_url); ?>
		<a
			href="<?php echo esc_url($remove_url); ?>"
			class="lenvy-chip"
			aria-label="<?php echo esc_attr(sprintf(__('Filter verwijderen: %s', 'lenvy'), $filter['label'])); ?>"
		>
			<span><?php echo esc_html($filter['label']); ?></span>
			<svg viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M1 1l8 8M9 1l-8 8"/></svg>
		</a>
	<?php endforeach; ?>

	<a
		href="<?php echo esc_url($base_url); ?>"
		class="lenvy-chip-clear"
	>
		<?php esc_html_e('Alles wissen', 'lenvy'); ?>
	</a>

</div>
