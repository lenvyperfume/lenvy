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

<div class="flex flex-wrap items-center gap-2 py-3" data-active-filters>

	<?php foreach ($active as $filter): ?>
		<?php // Build the URL that removes only this chip.
  $remove_url = add_query_arg($filter['remove_args'], $base_url); ?>
		<a
			href="<?php echo esc_url($remove_url); ?>"
			class="inline-flex items-center gap-1.5 text-xs font-medium bg-neutral-100 text-neutral-800 hover:bg-neutral-200 px-3 py-1.5 transition-colors duration-150"
			aria-label="<?php echo esc_attr(sprintf(__('Remove filter: %s', 'lenvy'), $filter['label'])); ?>"
		>
			<?php echo esc_html($filter['label']); ?>
			<?php lenvy_icon('close', 'text-neutral-500', 'xs'); ?>
		</a>
	<?php endforeach; ?>

	<a
		href="<?php echo esc_url($base_url); ?>"
		class="text-xs text-neutral-500 hover:text-black underline underline-offset-2 transition-colors duration-150 ml-1"
	>
		<?php esc_html_e('Clear all', 'lenvy'); ?>
	</a>

</div>
