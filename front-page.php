<?php
/**
 * Front page template.
 *
 * Section order:
 *   1. Hero             — cinematic banner (image / video only, no text overlay)
 *   2. Brand scroller   — infinite auto-scroll strip of brand logos
 *   3. Featured cats    — portrait image grid of selected product_cat terms
 *   4. Featured prods   — 4-col product row
 *   5. Promo sections   — flexible content: text_banner | brand_strip (repeatable, ≤4)
 *
 * All content is ACF-driven from the "Homepage" field group (front_page location).
 *
 * @package Lenvy
 */

get_header(); ?>

<main id="primary" class="site-main">

	<?php get_template_part('template-parts/homepage/hero'); ?>

	<?php get_template_part('template-parts/homepage/brand-scroller'); ?>

	<?php get_template_part('template-parts/homepage/featured-categories'); ?>

	<?php get_template_part('template-parts/homepage/featured-products'); ?>

	<?php get_template_part('template-parts/homepage/promo-sections'); ?>

</main>

<?php get_footer(); ?>
