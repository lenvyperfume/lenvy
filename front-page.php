<?php
/**
 * Front page template.
 *
 * Section order:
 *   1. Hero              — cinematic banner (image / video only, no text overlay)
 *   2. Brand scroller    — infinite auto-scroll strip of brand logos
 *   3. Bestsellers       — product carousel (WC query: popularity)
 *   4. Promo Banners     — editorial image banners (ACF repeater, max 4)
 *   5. Featured cats     — portrait image grid of selected product_cat terms
 *   6. New Arrivals      — product carousel (WC query: date, alternating bg)
 *   7. Sale              — product carousel (conditional — only if sale products exist)
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

	<?php get_template_part('template-parts/homepage/brand-scroller'); ?>

	<?php
	// ── Bestsellers carousel ──────────────────────────────────────────────
	$bestsellers = lenvy_get_homepage_products('bestsellers', 12);
	if ($bestsellers) {
		get_template_part('template-parts/homepage/product-carousel', null, [
			'eyebrow'    => __('Bestsellers', 'lenvy'),
			'title'      => __('Meest Geliefd', 'lenvy'),
			'products'   => $bestsellers,
			'link_url'   => add_query_arg('orderby', 'popularity', $shop_url),
			'link_label' => __('Alles bekijken', 'lenvy'),
		]);
	}
	?>

	<?php
	// ── Promo Banners (ACF repeater) ─────────────────────────────────────
	$promo_banners = lenvy_field('lenvy_promo_banners');
	if ($promo_banners):
	?>
	<section class="space-y-6">
		<?php foreach ($promo_banners as $banner): ?>
			<?php
			get_template_part('template-parts/components/promo-banner', null, [
				'image'       => $banner['banner_image'] ?? null,
				'title'       => $banner['banner_title'] ?? '',
				'description' => $banner['banner_description'] ?? '',
				'link_label'  => $banner['banner_link_label'] ?? '',
				'link_url'    => $banner['banner_link_url'] ?? '',
			]);
			?>
		<?php endforeach; ?>
	</section>
	<?php endif; ?>

	<?php get_template_part('template-parts/homepage/featured-categories'); ?>

	<?php
	// ── New Arrivals carousel ─────────────────────────────────────────────
	$new_arrivals = lenvy_get_homepage_products('new', 12);
	if ($new_arrivals) {
		get_template_part('template-parts/homepage/product-carousel', null, [
			'eyebrow'    => __('Nieuw Binnen', 'lenvy'),
			'title'      => __('Nieuwste Geuren', 'lenvy'),
			'products'   => $new_arrivals,
			'link_url'   => add_query_arg('orderby', 'date', $shop_url),
			'link_label' => __('Alles bekijken', 'lenvy'),
			'bg_class'   => 'bg-neutral-50',
		]);
	}
	?>

	<?php
	// ── Sale carousel (conditional) ───────────────────────────────────────
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

</main>

<?php get_footer(); ?>
