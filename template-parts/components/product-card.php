<?php
/**
 * Product card component — Skins-inspired, clear hierarchy.
 *
 * Visual order: Image → Brand (bold uppercase) → Title → Price
 *
 * Usage:
 *   get_template_part('template-parts/components/product-card', null, [
 *     'product_id' => 42,
 *     'show_brand' => true,
 *     'image_size' => 'woocommerce_thumbnail',
 *   ]);
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$product_id = (int) ($args['product_id'] ?? get_the_ID());
$show_brand = $args['show_brand'] ?? true;
$image_size = $args['image_size'] ?? 'woocommerce_thumbnail';

$product = wc_get_product($product_id);

if (!$product || !$product->is_visible()) {
	return;
}

$permalink  = get_permalink($product_id);
$title      = $product->get_name();
$is_sale    = $product->is_on_sale();
$is_oos     = !$product->is_in_stock();
$price_html = $product->get_price_html();

// ── Badge — OOS takes priority, then custom ACF, then sale ──────────────────
$badge_text    = '';
$badge_variant = 'custom';

$custom_badge = lenvy_field('lenvy_product_badge_text', $product_id);

if ($is_oos) {
	$badge_text    = __('Uitverkocht', 'lenvy');
	$badge_variant = 'oos';
} elseif ($custom_badge) {
	$badge_text    = (string) $custom_badge;
	$badge_variant = 'new';
} elseif ($is_sale) {
	$badge_text    = __('Sale', 'lenvy');
	$badge_variant = 'sale';
}

// ── Brand ────────────────────────────────────────────────────────────────────
$brand_name = '';
if ($show_brand) {
	$brands = get_the_terms($product_id, 'product_brand');
	if ($brands && !is_wp_error($brands)) {
		$brand_name = $brands[0]->name;
	}
}

// ── Concentration ───────────────────────────────────────────────────────────
$concentration = $product->get_attribute('concentration');

// ── Variable product: "Vanaf" price + cheapest size ─────────────────────────
$cheapest_size = '';
$is_variable   = $product->is_type('variable');
if ($is_variable) {
	$prices = $product->get_variation_prices(true);
	if (!empty($prices['price'])) {
		$min_var_id = array_keys($prices['price'])[0];
		$min_price  = $prices['price'][$min_var_id];
		$price_html = wc_price($min_price);
		$min_var    = wc_get_product($min_var_id);
		if ($min_var) {
			$cheapest_size = $min_var->get_attribute('size');
		}
	}
}

// ── Image ────────────────────────────────────────────────────────────────────
$image_id = (int) $product->get_image_id();

$image_html = $image_id
	? wp_get_attachment_image($image_id, $image_size, false, [
		'class'   => 'w-full h-full object-contain p-10 transition-transform duration-300 group-hover:scale-[1.03]',
		'loading' => 'lazy',
		'alt'     => esc_attr($title),
	])
	: wc_placeholder_img($image_size, ['class' => 'w-full h-full object-contain p-10']);

// ── Add-to-cart data ─────────────────────────────────────────────────────────
$is_purchasable = $product->is_purchasable() && $product->is_in_stock();
$is_simple      = 'simple' === $product->get_type();
$atc_url        = $product->add_to_cart_url();
?>

<article
	class="group relative flex flex-col"
	data-product-id="<?php echo esc_attr($product_id); ?>"
>
	<!-- Image -->
	<a
		href="<?php echo esc_url($permalink); ?>"
		class="relative block overflow-hidden aspect-product" style="background:#FAF9F8;"
		tabindex="-1"
		aria-hidden="true"
	>
		<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $image_html; ?>

		<?php if ($badge_text): ?>
		<span class="absolute top-3 left-3 z-10">
			<?php get_template_part('template-parts/components/badge', null, [
				'text'    => $badge_text,
				'variant' => $badge_variant,
			]); ?>
		</span>
		<?php endif; ?>

		<?php if ($is_oos): ?>
		<span class="absolute inset-0 bg-white/50 z-[5]"></span>
		<?php endif; ?>

		<?php if ($is_simple && $is_purchasable): ?>
		<button
			type="button"
			class="absolute bottom-3 right-3 z-10 w-9 h-9 flex items-center justify-center bg-white text-neutral-700 rounded-full opacity-0 translate-y-1 max-lg:opacity-100 max-lg:translate-y-0 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-200 hover:bg-black hover:text-white"
			data-quick-add
			data-product-id="<?php echo esc_attr($product_id); ?>"
			data-add-to-cart-url="<?php echo esc_url($atc_url); ?>"
			aria-label="<?php echo esc_attr(sprintf(__('Voeg %s toe aan winkelwagen', 'lenvy'), $title)); ?>"
		>
			<?php lenvy_icon('cart', '', 'sm'); ?>
		</button>
		<?php elseif (!$is_simple && $is_purchasable): ?>
		<a
			href="<?php echo esc_url($permalink); ?>"
			class="absolute bottom-3 right-3 z-10 w-9 h-9 flex items-center justify-center bg-white text-neutral-700 rounded-full opacity-0 translate-y-1 max-lg:opacity-100 max-lg:translate-y-0 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-200 hover:bg-black hover:text-white"
			aria-label="<?php echo esc_attr(sprintf(__('Bekijk opties voor %s', 'lenvy'), $title)); ?>"
		>
			<?php lenvy_icon('arrow-right', '', 'sm'); ?>
		</a>
		<?php endif; ?>
	</a>

	<!-- Details -->
	<div class="pt-4 flex flex-col gap-1">

		<?php if ($brand_name): ?>
		<span class="text-xs font-semibold uppercase tracking-[0.08em] text-neutral-900 line-clamp-1">
			<?php echo esc_html($brand_name); ?>
		</span>
		<?php endif; ?>

		<a
			href="<?php echo esc_url($permalink); ?>"
			class="text-sm text-neutral-500 leading-snug line-clamp-2 transition-colors duration-200 hover:text-neutral-900"
		>
			<?php echo esc_html($title); ?>
			<?php if ($concentration): ?>
				<span class="text-neutral-400"><?php echo esc_html(' — ' . $concentration); ?></span>
			<?php endif; ?>
		</a>

		<?php if ($price_html): ?>
		<div class="mt-1 flex items-baseline gap-2 lenvy-card-price">
			<span class="text-sm font-semibold text-neutral-900">
				<?php if ($is_variable): ?>
					<?php esc_html_e('Vanaf', 'lenvy'); ?>
				<?php endif; ?>
				<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $price_html; ?>
			</span>
			<?php if ($cheapest_size): ?>
			<span class="text-xs text-neutral-400">
				<?php echo esc_html($cheapest_size); ?>
			</span>
			<?php endif; ?>
		</div>
		<?php endif; ?>

	</div>
</article>
