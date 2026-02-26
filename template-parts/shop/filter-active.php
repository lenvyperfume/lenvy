<?php
/**
 * Active filter chips — shows applied filters with remove links + "Clear all".
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$active = lenvy_get_active_filters();

if (empty($active)) {
	return;
}

$base_url = strtok((string) $_SERVER['REQUEST_URI'], '?');

// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
?>

<div class="flex flex-wrap items-center gap-2 mt-4" data-active-filters>

	<?php foreach ($active as $filter): ?>
		<?php $remove_url = add_query_arg($filter['remove_args'], $base_url); ?>
		<a
			href="<?php echo esc_url($remove_url); ?>"
			class="inline-flex items-center gap-1.5 text-[11px] font-medium bg-neutral-100 text-neutral-700 hover:bg-neutral-200 px-3 py-1.5 transition-colors duration-200"
			aria-label="<?php echo esc_attr(sprintf(__('Remove filter: %s', 'lenvy'), $filter['label'])); ?>"
		>
			<?php echo esc_html($filter['label']); ?>
			<?php lenvy_icon('close', 'text-neutral-400', 'xs'); ?>
		</a>
	<?php endforeach; ?>

	<a
		href="<?php echo esc_url($base_url); ?>"
		class="text-[11px] text-neutral-400 hover:text-neutral-900 underline underline-offset-4 transition-colors duration-200 ml-1"
	>
		<?php esc_html_e('Wis alles', 'lenvy'); ?>
	</a>

</div>
