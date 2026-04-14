<?php
/**
 * Shop intro — breadcrumb + split layout (image left, heading + SEO text right).
 *
 * Skins-inspired intro section. Uses the existing ACF banner image field
 * alongside heading and description text for SEO.
 *
 * ACF fields (on the WC shop page):
 *   lenvy_shop_banner_image image    — intro image (left panel)
 *   lenvy_shop_heading      text     — custom H1 (falls back to archive title)
 *   lenvy_shop_description  textarea — SEO intro paragraph
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$shop_page_id = wc_get_page_id('shop');
$heading      = lenvy_field('lenvy_shop_heading', $shop_page_id) ?: lenvy_archive_title();
$description  = lenvy_field('lenvy_shop_description', $shop_page_id);
$banner_image = $shop_page_id ? lenvy_field('lenvy_shop_banner_image', $shop_page_id) : null;
$image_id     = is_array($banner_image) ? (int) ($banner_image['ID'] ?? 0) : (int) $banner_image;

if (empty($description)) {
	$description = __('Ontdek ons uitgebreide assortiment parfums van de beste merken. Van tijdloze klassiekers tot de nieuwste releases — filter op merk, prijs, geurfamilie en meer om jouw perfecte geur te vinden.', 'lenvy');
}
?>

<div class="lenvy-container">

	<?php get_template_part('template-parts/components/breadcrumb'); ?>

	<?php if ($image_id): ?>
		<!-- Split layout: image + text -->
		<div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-10 mt-6 mb-8 lg:mb-10 items-center">

			<div class="relative overflow-hidden aspect-[16/10] md:aspect-[4/3]" style="background:#FAF9F8;">
				<?php echo wp_get_attachment_image($image_id, 'large', false, [
					'class'         => 'w-full h-full object-cover',
					'fetchpriority' => 'high',
					'loading'       => 'eager',
					'alt'           => esc_attr(strip_tags($heading)),
				]); ?>
			</div>

			<div>
				<?php if ($heading): ?>
				<h1 class="text-2xl lg:text-4xl font-medium text-neutral-900 leading-tight">
					<?php echo wp_kses_post($heading); ?>
				</h1>
				<?php endif; ?>

				<p class="mt-4 text-sm leading-relaxed text-neutral-500 lg:text-[0.9375rem] lg:leading-relaxed">
					<?php echo esc_html($description); ?>
				</p>
			</div>

		</div>
	<?php else: ?>
		<!-- Text only (no image uploaded) -->
		<div class="mt-3 mb-8 lg:mb-10">
			<?php if ($heading): ?>
			<h1 class="text-2xl lg:text-3xl font-medium text-neutral-900">
				<?php echo wp_kses_post($heading); ?>
			</h1>
			<?php endif; ?>

			<p class="mt-3 max-w-3xl text-sm leading-relaxed text-neutral-500">
				<?php echo esc_html($description); ?>
			</p>
		</div>
	<?php endif; ?>

</div>
