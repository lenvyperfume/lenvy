<?php
/**
 * Homepage USP bar — horizontal trust signals strip below the hero.
 *
 * Always uses hardcoded defaults — the sitewide USP bar in the header
 * handles ACF-driven items. This one sits at the fold and provides
 * a clean, consistent set of trust signals.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$usps = [
	[
		'icon' => 'truck',
		'text' => __( 'Vandaag besteld, morgen in huis', 'lenvy' ),
	],
	[
		'icon' => 'refresh',
		'text' => __( 'Gratis verzending vanaf €50', 'lenvy' ),
	],
	[
		'icon' => 'heart',
		'text' => __( 'Gratis samples bij elke bestelling', 'lenvy' ),
	],
	[
		'icon' => 'shield',
		'text' => __( '100% originele producten', 'lenvy' ),
	],
];
?>

<div class="border-y border-neutral-200 bg-white">
	<div class="lenvy-container">
		<ul class="flex items-center gap-8 overflow-x-auto py-4 scrollbar-hide sm:justify-between lg:justify-center lg:gap-12 lg:py-5">
			<?php foreach ( $usps as $usp ) : ?>
				<li class="flex shrink-0 items-center gap-2.5">
					<?php lenvy_icon( $usp['icon'], 'text-neutral-400 shrink-0', 'sm' ); ?>
					<span class="whitespace-nowrap text-xs text-neutral-600 sm:text-[0.8125rem]">
						<?php echo esc_html( $usp['text'] ); ?>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
