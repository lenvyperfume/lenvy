<?php
/**
 * Promo banner component.
 *
 * Full-bleed image banner with large serif title (top-left) and
 * description + link button (bottom-left). Inspired by editorial
 * "The art of giving" layouts.
 *
 * Usage:
 *   get_template_part('template-parts/components/promo-banner', null, [
 *     'image'       => $attachment_id_or_acf_array,
 *     'title'       => 'The art of giving',
 *     'description' => 'Discover our curated gift sets.',
 *     'link_url'    => '/shop/gift-sets/',
 *     'link_label'  => 'Shop gift sets',
 *     'overlay'     => true,          // dark gradient for text legibility
 *     'aspect'      => '16/9',        // CSS aspect-ratio value
 *   ]);
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$image       = $args['image'] ?? null;
$title       = $args['title'] ?? '';
$description = $args['description'] ?? '';
$link_url    = $args['link_url'] ?? '';
$link_label  = $args['link_label'] ?? '';
$overlay     = $args['overlay'] ?? true;
$aspect      = $args['aspect'] ?? '16/9';

if (!$image && !$title) {
	return;
}

?>

<div
	class="relative block w-full overflow-hidden aspect-[var(--banner-aspect)] max-md:aspect-[4/5]"
	style="--banner-aspect: <?php echo esc_attr($aspect); ?>;"
>
	<?php
	// ── Background image ─────────────────────────────────────────────────
	if ($image) {
		echo lenvy_get_image(
			$image,
			'full',
			'absolute inset-0 w-full h-full object-cover',
		);
	}
	?>

	<?php if ($overlay): ?>
		<!-- Gradient overlay for text legibility -->
		<span class="absolute inset-0 bg-gradient-to-br from-black/30 via-transparent to-black/40 pointer-events-none" aria-hidden="true"></span>
	<?php endif; ?>

	<?php if ($title): ?>
		<!-- Title — top-left, constrained to force line breaks -->
		<span class="absolute top-6 left-6 md:top-10 md:left-10 font-serif italic text-white text-[clamp(2rem,5vw,4rem)] leading-[1.1] max-w-[40%] drop-shadow-md">
			<?php echo esc_html($title); ?>
		</span>
	<?php endif; ?>

	<?php if ($description || $link_label): ?>
		<!-- Description + link — bottom-right, text left-aligned -->
		<div class="absolute bottom-6 right-6 md:bottom-10 md:right-10 text-left max-w-[20rem]">
			<?php if ($description): ?>
				<p class="text-sm text-white/80 leading-relaxed mb-3 drop-shadow-sm">
					<?php echo esc_html($description); ?>
				</p>
			<?php endif; ?>

			<?php if ($link_label && $link_url): ?>
				<a
					href="<?php echo esc_url($link_url); ?>"
					class="inline-block text-sm text-white font-medium underline underline-offset-4 decoration-white/60 hover:decoration-white transition-colors duration-200 drop-shadow-sm"
				>
					<?php echo esc_html($link_label); ?>
				</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

</div>
