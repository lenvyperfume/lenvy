<?php
/**
 * USP / trust bar — sitewide strip of trust signals below the header.
 *
 * Reads the ACF repeater `lenvy_usp_items` from the Theme Settings options
 * page. Falls back to hardcoded Dutch defaults when ACF is empty or inactive.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

// Bail if the bar is explicitly disabled in Theme Settings.
$enabled = lenvy_field('lenvy_usp_bar_enabled', 'options');
if ($enabled === false) {
	return;
}

$defaults = [
	['usp_icon' => 'truck',   'usp_text' => __('Gratis verzending vanaf €50', 'lenvy')],
	['usp_icon' => 'refresh', 'usp_text' => __('30 dagen retour', 'lenvy')],
	['usp_icon' => 'check',   'usp_text' => __('Veilig betalen', 'lenvy')],
];

$items = lenvy_field('lenvy_usp_items', 'options') ?: $defaults;
?>

<div class="bg-primary border-primary-hover">
	<div class="lenvy-container">
		<ul class="flex items-center justify-center gap-8 py-2.5 max-lg:justify-start max-lg:overflow-x-auto max-lg:scrollbar-none max-lg:-mx-4 max-lg:px-4">
			<?php foreach ($items as $item):
				$icon = $item['usp_icon'] ?? 'check';
				$text = $item['usp_text'] ?? '';
				if (empty($text)) {
					continue;
				}
			?>
				<li class="flex items-center gap-2 shrink-0">
					<?php lenvy_icon($icon, 'text-neutral-700 shrink-0', 'sm'); ?>
					<span class="text-xs text-neutral-700 whitespace-nowrap"><?php echo esc_html($text); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
