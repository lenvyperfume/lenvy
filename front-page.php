<?php
/**
 * Front page template.
 *
 * Section order:
 *   1. Hero             — full-viewport image/video + ACF heading + CTA
 *   2. Featured cats    — portrait image grid of selected product_cat terms
 *   3. Featured prods   — 4-col product row (inline card; replaced by product-card.php in Phase 7)
 *   4. Promo sections   — flexible content: text_banner | brand_strip (repeatable, ≤4)
 *
 * All content is ACF-driven from the "Homepage" field group (front_page location).
 *
 * @package Lenvy
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php get_template_part( 'template-parts/homepage/hero' ); ?>

	<?php get_template_part( 'template-parts/homepage/featured-categories' ); ?>

	<?php get_template_part( 'template-parts/homepage/featured-products' ); ?>

	<?php get_template_part( 'template-parts/homepage/promo-sections' ); ?>

</main>

<?php get_footer(); ?>
