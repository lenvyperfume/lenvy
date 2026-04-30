<?php
/**
 * Cart placeholder page.
 *
 * Reachable at /winkelwagen/. All content is sourced from
 * template-parts/cart/placeholder-data.php; replace with WC cart reads
 * once products + checkout flow exist.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$cart  = require get_theme_file_path('template-parts/cart/placeholder-data.php');
$items = (array) ($cart['items'] ?? []);

$item_count = 0;
foreach ($items as $i) {
	$item_count += (int) ($i['qty'] ?? 1);
}

get_header();
?>

<main id="primary" class="lenvy-cart" data-cart-page>

	<?php get_template_part('template-parts/cart/page-head'); ?>

	<div class="lenvy-container">
		<div class="lenvy-cart__grid">

			<div class="lenvy-cart__items-col">

				<header class="lenvy-cart__items-head">
					<h2 class="lenvy-cart__items-title"><?php esc_html_e('Artikelen', 'lenvy'); ?></h2>
					<span class="lenvy-cart__items-count" data-cart-count-text>
						<b data-cart-item-count><?php echo esc_html((string) $item_count); ?></b>
						<?php esc_html_e('in je winkelwagen', 'lenvy'); ?>
					</span>
				</header>

				<div class="lenvy-cart__items" data-cart-items>
					<?php foreach ($items as $item): ?>
						<?php get_template_part('template-parts/cart/item-row', null, ['item' => $item]); ?>
					<?php endforeach; ?>
				</div>

				<!-- Empty state — shown by JS when items reaches 0 -->
				<div class="lenvy-cart__empty" data-cart-empty hidden>
					<span class="lenvy-cart__empty-icon" aria-hidden="true">
						<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
							<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
							<path d="M3 6h18"/>
							<path d="M16 10a4 4 0 0 1-8 0"/>
						</svg>
					</span>
					<h2><?php esc_html_e('Je winkelwagen is leeg', 'lenvy'); ?></h2>
					<p><?php esc_html_e('Ontdek onze geuren — van klassieke maisons tot indie ateliers.', 'lenvy'); ?></p>
					<a href="<?php echo esc_url(function_exists('lenvy_placeholder_brands_url') ? lenvy_placeholder_brands_url() : home_url('/merken/')); ?>" class="lenvy-cart__empty-cta">
						<?php esc_html_e('Bekijk collectie', 'lenvy'); ?>
					</a>
				</div>

				<div class="lenvy-cart__below" data-cart-below>
					<?php
					$shop_url = function_exists('wc_get_page_permalink')
						? wc_get_page_permalink('shop')
						: home_url('/shop/');
					?>
					<a href="<?php echo esc_url($shop_url); ?>" class="lenvy-cart__continue">
						<svg width="14" height="10" viewBox="0 0 14 10" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
							<path d="M9 1 13 5 9 9M13 5H1"/>
						</svg>
						<?php esc_html_e('Verder winkelen', 'lenvy'); ?>
					</a>
					<button type="button" class="lenvy-cart__clear-all" data-cart-clear-all>
						<?php esc_html_e('Winkelwagen leegmaken', 'lenvy'); ?>
					</button>
				</div>

			</div>

			<?php get_template_part('template-parts/cart/summary', null, ['cart' => $cart]); ?>

		</div>

		<?php get_template_part('template-parts/cart/recommendations', null, ['cart' => $cart]); ?>
	</div>

</main>

<?php
get_footer();
