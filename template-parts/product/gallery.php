<?php
/**
 * PDP gallery column — main shot, thumbnails, additional shots.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$product = $args['product'] ?? null;
if (!$product) {
	return;
}

$g = $product['gallery'];
?>

<div class="lenvy-pdp__gallery">

	<div
		class="lenvy-pdp__shot"
		data-pdp-shot
		style="--shot-bg: <?php echo esc_attr($g['main_bg']); ?>;"
	>
		<div class="lenvy-pdp__shot-cap" aria-hidden="true"></div>
		<div class="lenvy-pdp__shot-bottle" aria-hidden="true"></div>
		<div class="lenvy-pdp__shot-label" aria-hidden="true">
			<?php echo wp_kses_post($g['label_html']); ?>
		</div>
		<?php if ($product['badge_free']): ?>
			<div class="lenvy-pdp__badge-free"><?php echo esc_html($product['badge_free']); ?></div>
		<?php endif; ?>
		<?php if ($product['season_tag']): ?>
			<div class="lenvy-pdp__season">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
					<path d="M12 3v2M12 19v2M3 12h2M19 12h2M5.6 5.6l1.4 1.4M17 17l1.4 1.4M5.6 18.4 7 17M17 7l1.4-1.4"/>
					<circle cx="12" cy="12" r="4"/>
				</svg>
				<?php echo esc_html($product['season_tag']); ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="lenvy-pdp__thumbs" role="tablist" aria-label="<?php esc_attr_e('Galerijweergaven', 'lenvy'); ?>">
		<?php foreach ($g['thumbs'] as $i => $t):
			$bg = $g['thumb_bgs'][$i] ?? '';
		?>
			<button
				type="button"
				class="lenvy-pdp__thumb lenvy-pdp__thumb--<?php echo esc_attr($t['key']); ?><?php echo $t['on'] ? ' is-active' : ''; ?>"
				data-pdp-thumb
				data-bg="<?php echo esc_attr($bg); ?>"
				role="tab"
				aria-selected="<?php echo $t['on'] ? 'true' : 'false'; ?>"
				aria-label="<?php echo esc_attr(sprintf(__('Weergave %d', 'lenvy'), $i + 1)); ?>"
			></button>
		<?php endforeach; ?>
	</div>

	<div class="lenvy-pdp__more">
		<?php foreach ($g['extra'] as $shot):
			$class = (string) ($shot['class'] ?? '');
		?>
			<div class="lenvy-pdp__more-shot lenvy-pdp__more-shot--<?php echo esc_attr($class); ?>">
				<?php if ($class === 's2'): ?>
					<div class="lenvy-pdp__glow"></div>
					<div class="v1"></div><div class="v2"></div><div class="v3"></div>
				<?php elseif ($class === 's3'): ?>
					<div class="m1"></div><div class="m2"></div><div class="m3"></div>
					<div class="m4"></div><div class="m5"></div>
				<?php elseif ($class === 's4'): ?>
					<div class="lenvy-pdp__flare"></div>
					<div class="c"></div><div class="b"></div>
				<?php endif; ?>
				<?php if (!empty($shot['caption'])): ?>
					<div class="lenvy-pdp__more-caption"><?php echo esc_html($shot['caption']); ?></div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

</div>
