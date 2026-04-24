<?php
/**
 * Shop archive — HARDCODED PLACEHOLDER version.
 *
 * Mirrors /docs/design/Shop.html using static data from
 * template-parts/shop/placeholder-data.php. Bypasses the WooCommerce loop
 * entirely; to be re-wired to WC once real products exist.
 *
 * Overrides woocommerce/archive-product.php
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

get_header();

$shop_data = require get_theme_file_path('template-parts/shop/placeholder-data.php');

// Pool of gradient keys to cycle through, matching the design.
$gradient_keys = ['v1', 'v2', 'v3', 'v4', 'v5', 'v6', 'v7', 'v8'];
?>

<main id="primary" class="lenvy-shop">

	<?php get_template_part('template-parts/shop/shop-intro', null, ['shop_data' => $shop_data]); ?>

	<?php get_template_part('template-parts/shop/sort-bar', null, ['shop_data' => $shop_data]); ?>

	<div class="lenvy-container">
		<div class="lenvy-shop__layout">

			<?php get_template_part('template-parts/shop/filter-sidebar', null, ['shop_data' => $shop_data]); ?>

			<div class="lenvy-shop__main">

				<div class="lenvy-grid grid grid-cols-2 md:grid-cols-3" data-product-grid>
					<?php foreach ($shop_data['products'] as $i => $p): ?>
						<?php get_template_part('template-parts/components/product-card-placeholder', null, [
							'brand'             => $p['brand'],
							'name'              => $p['name'],
							'variant'           => $p['variant'],
							'price'             => $p['price'],
							'was'               => $p['was'],
							'tag'               => $p['tag'],
							'v'                 => $gradient_keys[$i % count($gradient_keys)],
							'variant_gradients' => $shop_data['variants'],
						]); ?>
					<?php endforeach; ?>
				</div>

				<!-- Load more — static placeholder until WC pagination is wired up -->
				<div class="lenvy-load-more">
					<p class="lenvy-load-more__count">
						<b><?php echo esc_html(number_format_i18n(count($shop_data['products']))); ?></b>
						<?php esc_html_e('van', 'lenvy'); ?>
						<b><?php echo esc_html(number_format_i18n($shop_data['totals']['results'])); ?></b>
					</p>
					<div class="lenvy-load-more__bar" aria-hidden="true">
						<div class="lenvy-load-more__fill" style="width:<?php echo esc_attr(round(count($shop_data['products']) / max(1, $shop_data['totals']['results']) * 100)); ?>%;"></div>
					</div>
					<button type="button" class="lenvy-load-more__btn">
						<?php esc_html_e('Toon meer', 'lenvy'); ?>
					</button>
				</div>

			</div>

		</div>
	</div>

</main>

<?php get_template_part('template-parts/shop/filter-drawer', null, ['shop_data' => $shop_data]); ?>

<?php get_footer(); ?>
