<?php
/**
 * Announcement bar — site-wide dark strip at the very top of every page.
 *
 * Matches the design's `.usp-top` band: near-black background, small white
 * text, lavender dot before each item, 40px gap between items.
 *
 * Reads the ACF repeater `lenvy_usp_items` (Theme Settings → USP Bar).
 * Falls back to sensible Dutch defaults when the repeater is empty.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$enabled = lenvy_field('lenvy_usp_bar_enabled', 'options');
if ($enabled === false) {
	return;
}

$defaults = [
	['usp_text' => __('Gratis verzending vanaf €50', 'lenvy')],
	['usp_text' => __('Vandaag besteld, morgen in huis', 'lenvy')],
	['usp_text' => __('100% originele parfums · gratis samples', 'lenvy')],
];

$items = lenvy_field('lenvy_usp_items', 'options') ?: $defaults;
$items = array_values(array_filter($items, static fn ($it) => ! empty($it['usp_text'] ?? '')));

if (empty($items)) {
	return;
}
?>

<div class="bg-neutral-950 text-neutral-300 text-[12px] tracking-[0.04em] py-2.5 text-center">
	<div class="lenvy-container">
		<ul class="flex flex-wrap justify-center items-center gap-x-10 gap-y-1 m-0 p-0 list-none">
			<?php foreach ($items as $item): ?>
				<li class="inline-flex items-center opacity-85 whitespace-nowrap">
					<span class="inline-block w-[3px] h-[3px] rounded-full bg-primary mr-2.5 -translate-y-[2px]" aria-hidden="true"></span>
					<?php echo esc_html($item['usp_text']); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
