<?php
/**
 * Product brand archive — banner + ACF brand fields + filter/grid layout.
 *
 * Overrides woocommerce/taxonomy-product_brand.php
 *
 * ACF fields read (attached to the term):
 *   lenvy_brand_banner_image       image   — full-width header banner
 *   lenvy_brand_logo               image   — brand logo shown in header
 *   lenvy_brand_country_of_origin  text    — e.g. "France"
 *   lenvy_brand_website_url        url     — external brand website
 *   lenvy_brand_is_featured        bool    — shows "Featured Brand" chip
 *   lenvy_brand_description        wysiwyg — rendered below the product grid
 *
 * @package Lenvy
 * @see     WC templates/taxonomy-product_cat.php
 */

defined('ABSPATH') || exit();

get_header();

$term = get_queried_object();
$term_id = 'term_' . $term->term_id;

// ── ACF brand fields ───────────────────────────────────────────────────────────
$banner_image = lenvy_field('lenvy_brand_banner_image', $term_id);
$logo_image = lenvy_field('lenvy_brand_logo', $term_id);
$country = lenvy_field('lenvy_brand_country_of_origin', $term_id);
$website_url = lenvy_field('lenvy_brand_website_url', $term_id);
$is_featured = (bool) lenvy_field('lenvy_brand_is_featured', $term_id);
$description = lenvy_field('lenvy_brand_description', $term_id);

$banner_id = is_array($banner_image) ? (int) ($banner_image['ID'] ?? 0) : (int) $banner_image;
$logo_id = is_array($logo_image) ? (int) ($logo_image['ID'] ?? 0) : (int) $logo_image;

$brand_name = esc_html($term->name);
?>

<?php // ── 1. Brand header ────────────────────────────────────────────────────────────
if ($banner_id):
	$banner_img = wp_get_attachment_image($banner_id, 'full', false, [
		'class' => 'w-full h-full object-cover',
		'fetchpriority' => 'high',
		'loading' => 'eager',
		'alt' => esc_attr($term->name),
	]); ?>
	<div class="relative h-48 md:h-64 overflow-hidden bg-neutral-900">
		<?php echo $banner_img;
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
		<!-- gradient: strong at bottom for text legibility, fades to transparent -->
		<div class="absolute inset-0 bg-gradient-to-t from-neutral-950/80 via-neutral-950/30 to-transparent"></div>

		<div class="absolute inset-0 flex items-end">
			<div class="lenvy-container pb-8 flex items-end gap-4">

				<?php if ($logo_id): ?>
					<div class="shrink-0 w-14 h-14 bg-white rounded flex items-center justify-center p-1.5 shadow-sm mb-0.5">
						<?php echo wp_get_attachment_image($logo_id, [56, 56], false, [
      	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
      	'class' => 'w-full h-full object-contain',
      	'alt' => esc_attr($term->name),
      ]); ?>
					</div>
				<?php endif; ?>

				<div>
					<h1 class="text-3xl md:text-4xl font-serif italic text-white leading-tight">
						<?php echo $brand_name;
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — already esc_html'd
	?>
					</h1>

					<div class="flex items-center gap-3 mt-1.5">
						<?php if ($country): ?>
							<span class="text-xs text-white/70">
								<?php echo esc_html($country); ?>
							</span>
						<?php endif; ?>

						<?php if ($is_featured): ?>
							<span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold uppercase tracking-widest bg-white/15 text-white border border-white/30 rounded-full">
								<?php esc_html_e('Featured Brand', 'lenvy'); ?>
							</span>
						<?php endif; ?>
					</div>
				</div>

			</div>
		</div>
	</div>

<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped


else:
	 ?>

	<!-- No banner: clean neutral header strip -->
	<div class="py-10 border-b border-neutral-100">
		<div class="lenvy-container flex items-center gap-4">

			<?php if ($logo_id): ?>
				<div class="shrink-0 w-12 h-12 bg-white border border-neutral-200 rounded flex items-center justify-center p-1">
					<?php echo wp_get_attachment_image($logo_id, [48, 48], false, [
     	'class' => 'w-full h-full object-contain',
     	'alt' => esc_attr($term->name),
     ]); ?>
				</div>
			<?php endif; ?>

			<div>
				<h1 class="text-2xl font-serif italic text-neutral-900">
					<?php echo $brand_name;
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
				</h1>

				<div class="flex items-center gap-3 mt-1">
					<?php if ($country): ?>
						<span class="text-xs text-neutral-500">
							<?php echo esc_html($country); ?>
						</span>
					<?php endif; ?>

					<?php if ($is_featured): ?>
						<span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold uppercase tracking-widest bg-neutral-900 text-white rounded-full">
							<?php esc_html_e('Featured Brand', 'lenvy'); ?>
						</span>
					<?php endif; ?>
				</div>
			</div>

		</div>
	</div>

<?php
endif; ?>

<?php // ── 2. Metadata bar (country + website — only if at least one is set) ──────────
if ($website_url || ($country && !$banner_id)):
	// On the banner variant, country is already shown in the overlay.
	// In the metadata bar we only show country when there's no banner.
	$show_country_in_bar = !$banner_id && $country;
	if ($website_url || $show_country_in_bar): ?>
		<div class="bg-neutral-50 border-b border-neutral-100">
			<div class="lenvy-container py-3 flex items-center gap-6 text-xs text-neutral-500">

				<?php if ($show_country_in_bar): ?>
					<span><?php echo esc_html($country); ?></span>
				<?php endif; ?>

				<?php if ($website_url): ?>
					<a
						href="<?php echo esc_url($website_url); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="inline-flex items-center gap-1 hover:text-black transition-colors duration-150"
					>
						<?php esc_html_e('Visit website', 'lenvy'); ?>
						<svg class="w-3 h-3" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
							<path d="M2.5 9.5l7-7M9.5 9.5V2.5H2.5"/>
						</svg>
					</a>
				<?php endif; ?>

			</div>
		</div>
		<?php endif;
endif; ?>

<main id="primary" class="py-8 lg:py-12">
	<div class="lenvy-container">

		<?php get_template_part('template-parts/components/breadcrumb'); ?>

		<div class="flex gap-8 mt-6">

			<?php get_template_part('template-parts/shop/filter-sidebar', null, [
   	'hide_brand_filter' => true,
   ]); ?>

			<div class="flex-1 min-w-0">

				<?php get_template_part('template-parts/shop/sort-bar'); ?>

				<?php if (lenvy_is_filtered()): ?>
					<?php get_template_part('template-parts/shop/filter-active'); ?>
				<?php endif; ?>

				<?php if (woocommerce_product_loop()): ?>

					<?php do_action('woocommerce_before_shop_loop'); ?>

					<div
						class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-8 mt-6"
						data-product-grid
						data-taxonomy="product_brand"
						data-term="<?php echo esc_attr($term->slug); ?>"
					>
						<?php while (have_posts()):
      	the_post(); ?>
							<?php get_template_part('template-parts/components/product-card', null, [
       	'product_id' => get_the_ID(),
       	'show_brand' => false, // brand is already the page context
       ]); ?>
						<?php
      endwhile; ?>
					</div>

					<?php do_action('woocommerce_after_shop_loop'); ?>

					<?php lenvy_pagination(); ?>

				<?php else: ?>

					<?php get_template_part('woocommerce/loop/no-products-found'); ?>

				<?php endif; ?>

				<?php // ── Brand description below grid (SEO + brand storytelling) ───────
    if ($description): ?>
					<div class="mt-16 pt-10 border-t border-neutral-100 prose prose-sm max-w-none text-neutral-600">
						<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — ACF WYSIWYG returns safe HTML.
      echo wp_kses_post($description); ?>
					</div>
				<?php endif; ?>

			</div><!-- .flex-1 -->

		</div><!-- .flex -->

	</div><!-- .lenvy-container -->
</main>

<?php get_template_part('template-parts/shop/filter-drawer', null, [
	'hide_brand_filter' => true,
]); ?>

<?php get_footer(); ?>
