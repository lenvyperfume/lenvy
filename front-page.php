<?php
/**
 * Front page template.
 *
 * Section order — mixed types for rhythm and variety:
 *   1. Hero              — split layout (image left, text right)
 *   2. USP bar           — trust signals at the fold
 *   3. Bestsellers       — STATIC GRID (4-col, 8 products)
 *   4. Promo banner      — single editorial image (breathing room)
 *   5. Featured cats     — category navigation mid-page
 *   6. Brand story       — editorial split (image + text, "Waarom Lenvy")
 *   7. New Arrivals      — product CAROUSEL
 *   8. Brand scroller    — logo marquee
 *   9. Sale              — product CAROUSEL (conditional)
 *  10. SEO content       — collapsible text block for search engines
 *
 * @package Lenvy
 */

get_header();

$shop_url = function_exists('wc_get_page_permalink')
	? wc_get_page_permalink('shop')
	: get_post_type_archive_link('product');

$shop_url = $shop_url ?: home_url('/shop/');
?>

<main id="primary" class="site-main">

	<h1 class="sr-only"><?php echo esc_html(get_bloginfo('name') . ' — ' . get_bloginfo('description')); ?></h1>

	<?php get_template_part('template-parts/homepage/hero'); ?>

	<?php get_template_part('template-parts/homepage/usp-bar'); ?>

	<?php
	// ── Bestsellers — static grid ────────────────────────────────────────
	$bestsellers = lenvy_get_homepage_products('bestsellers', 8);
	if ($bestsellers) {
		get_template_part('template-parts/homepage/product-grid', null, [
			'eyebrow'    => __('Bestsellers', 'lenvy'),
			'title'      => __('Meest Geliefd', 'lenvy'),
			'products'   => $bestsellers,
			'link_url'   => add_query_arg('orderby', 'popularity', $shop_url),
			'link_label' => __('Alles bekijken', 'lenvy'),
			'columns'    => 4,
		]);
	}
	?>

	<?php
	// ── Promo Banner — editorial break ───────────────────────────────────
	$promo_banners = lenvy_field('lenvy_promo_banners');
	if ($promo_banners):
		$banner = $promo_banners[0];
		get_template_part('template-parts/components/promo-banner', null, [
			'image'       => $banner['banner_image'] ?? null,
			'title'       => $banner['banner_title'] ?? '',
			'description' => $banner['banner_description'] ?? '',
			'link_label'  => $banner['banner_link_label'] ?? '',
			'link_url'    => $banner['banner_link_url'] ?? '',
		]);
	endif;
	?>

	<?php get_template_part('template-parts/homepage/featured-categories'); ?>

	<?php get_template_part('template-parts/homepage/brand-story'); ?>

	<?php
	// ── New Arrivals — carousel ──────────────────────────────────────────
	$new_arrivals = lenvy_get_homepage_products('new', 12);
	if ($new_arrivals) {
		get_template_part('template-parts/homepage/product-carousel', null, [
			'eyebrow'    => __('Nieuw Binnen', 'lenvy'),
			'title'      => __('Nieuwste Geuren', 'lenvy'),
			'products'   => $new_arrivals,
			'link_url'   => add_query_arg('orderby', 'date', $shop_url),
			'link_label' => __('Alles bekijken', 'lenvy'),
			'bg_class'   => '',
		]);
	}
	?>

	<?php get_template_part('template-parts/homepage/brand-scroller'); ?>

	<?php
	// ── Sale — carousel (conditional) ────────────────────────────────────
	$sale_products = lenvy_get_homepage_products('sale', 12);
	if ($sale_products) {
		get_template_part('template-parts/homepage/product-carousel', null, [
			'eyebrow'    => __('Aanbiedingen', 'lenvy'),
			'title'      => __('Sale', 'lenvy'),
			'products'   => $sale_products,
			'link_url'   => add_query_arg('filter_onsale', '1', $shop_url),
			'link_label' => __('Alles bekijken', 'lenvy'),
		]);
	}
	?>

	<?php get_template_part('template-parts/homepage/seo-content'); ?>

</main>

<?php get_footer(); ?>
