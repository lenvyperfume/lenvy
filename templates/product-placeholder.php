<?php
/**
 * Product placeholder page — wired up via inc/placeholder-pages.php.
 *
 * Reachable at /parfum-voorbeeld/. All content is sourced from the static
 * placeholder-data.php; replace with the real WC single-product flow once
 * actual products exist.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$product = require get_theme_file_path('template-parts/product/placeholder-data.php');

get_header();
?>

<main id="primary" class="lenvy-pdp" data-product-page>

	<div class="lenvy-container">
		<nav class="lenvy-pdp__breadcrumb" aria-label="<?php esc_attr_e('Kruimelpad', 'lenvy'); ?>">
			<?php foreach ($product['breadcrumb'] as $i => $crumb):
				$is_last = !($crumb['url'] ?? null);
			?>
				<?php if ($i > 0): ?><span class="sep" aria-hidden="true">/</span><?php endif; ?>
				<?php if ($is_last): ?>
					<span class="cur" aria-current="page"><?php echo esc_html($crumb['label']); ?></span>
				<?php else: ?>
					<a href="<?php echo esc_url($crumb['url']); ?>"><?php echo esc_html($crumb['label']); ?></a>
				<?php endif; ?>
			<?php endforeach; ?>
		</nav>
	</div>

	<section class="lenvy-pdp__hero">
		<?php get_template_part('template-parts/product/gallery', null, ['product' => $product]); ?>
		<?php get_template_part('template-parts/product/buy', null, ['product' => $product]); ?>
	</section>

	<?php get_template_part('template-parts/product/pyramid', null, ['product' => $product]); ?>
	<?php get_template_part('template-parts/product/story', null, ['product' => $product]); ?>
	<?php get_template_part('template-parts/product/ingredients', null, ['product' => $product]); ?>
	<?php get_template_part('template-parts/product/reviews', null, ['product' => $product]); ?>
	<?php get_template_part('template-parts/product/faq', null, ['product' => $product]); ?>

</main>

<?php
get_footer();
