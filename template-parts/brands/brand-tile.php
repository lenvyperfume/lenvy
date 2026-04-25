<?php
/**
 * Brands index — single wordmark tile.
 *
 * Usage:
 *   get_template_part('template-parts/brands/brand-tile', null, ['brand' => $b]);
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$b = (array) ($args['brand'] ?? []);
if (empty($b['name'])) {
	return;
}

$name   = (string) $b['name'];
$type   = (string) ($b['type']   ?? '');
$origin = (string) ($b['origin'] ?? '');
$count  = (int)    ($b['count']  ?? 0);
$is_new = (bool)   ($b['isNew']  ?? false);
$style  = (string) ($b['style']  ?? 'serif');

// Link shape: matches the future /merk/{slug}/ archive — non-existent today
// but the right URL once the brand taxonomy is populated.
$slug = sanitize_title($name);
$href = home_url('/merk/' . $slug . '/');
?>

<a
	class="lenvy-brand-tile"
	data-style="<?php echo esc_attr($style); ?>"
	data-type="<?php echo esc_attr($type); ?>"
	data-name="<?php echo esc_attr(strtolower($name)); ?>"
	data-letter="<?php echo esc_attr(strtoupper(mb_substr($name, 0, 1, 'UTF-8'))); ?>"
	href="<?php echo esc_url($href); ?>"
>
	<?php if ($is_new): ?>
		<span class="lenvy-brand-tile__new"><?php esc_html_e('Nieuw', 'lenvy'); ?></span>
	<?php endif; ?>

	<span class="lenvy-brand-tile__wm"><?php echo esc_html($name); ?></span>

	<span class="lenvy-brand-tile__hover" aria-hidden="true">
		<span class="lenvy-brand-tile__hover-name"><?php echo esc_html($name); ?></span>
		<span class="lenvy-brand-tile__hover-info">
			<?php if ($type): ?><span><?php echo esc_html($type); ?></span><?php endif; ?>
			<?php if ($origin): ?><span><?php echo esc_html($origin); ?></span><?php endif; ?>
			<?php if ($count): ?><span><?php echo esc_html(sprintf(_n('%d geur', '%d geuren', $count, 'lenvy'), $count)); ?></span><?php endif; ?>
		</span>
		<span class="lenvy-brand-tile__hover-arrow"><?php esc_html_e('Bekijk huis →', 'lenvy'); ?></span>
	</span>
</a>
