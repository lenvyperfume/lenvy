<?php
/**
 * Front page template.
 *
 * Section order (matches Claude Design Homepage.html):
 *   1. Hero                  — editorial split + in-hero USP strip
 *   2. Bestsellers           — STATIC GRID (4-col, up to 8 products)
 *   3. Brand Spotlight       — two-column editorial brand feature (ACF toggle)
 *   4. Featured categories   — asymmetric "Shop per stemming" grid
 *   5. Brand story           — editorial split + trust pills
 *   6. New arrivals          — product CAROUSEL
 *   7. Brand scroller        — logo marquee
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

get_header();

$shop_url = function_exists('wc_get_page_permalink')
	? wc_get_page_permalink('shop')
	: get_post_type_archive_link('product');

$shop_url = $shop_url ?: home_url('/shop/');
?>

<main id="primary" class="site-main">

	<h1 class="sr-only"><?php echo esc_html(get_bloginfo('name') . ' — ' . get_bloginfo('description')); ?></h1>

	<?php get_template_part('template-parts/homepage/hero'); ?>

	<?php get_template_part('template-parts/homepage/bestsellers'); ?>

	<?php get_template_part('template-parts/homepage/brand-spotlight'); ?>

	<?php get_template_part('template-parts/homepage/featured-categories'); ?>

	<?php get_template_part('template-parts/homepage/brand-story'); ?>

	<?php get_template_part('template-parts/homepage/new-arrivals'); ?>

	<?php get_template_part('template-parts/homepage/brand-scroller'); ?>

</main>

<?php get_footer(); ?>
