<?php
/**
 * Variable product add to cart — tile-based variation selector.
 *
 * Replaces WC's default dropdown with horizontal clickable tiles showing
 * size + price. The hidden <select> is kept so WC's add-to-cart-variation.js
 * continues to work unchanged.
 *
 * @package Lenvy
 * @version 9.6.0
 * @see     woocommerce/templates/single-product/add-to-cart/variable.php
 */

defined('ABSPATH') || exit();

global $product;

$attribute_keys  = array_keys($attributes);
$variations_json = wp_json_encode($available_variations);
$variations_attr = function_exists('wc_esc_json')
	? wc_esc_json($variations_json)
	: _wp_specialchars($variations_json, ENT_QUOTES, 'UTF-8', true);

// Build a lookup: attribute_value → { price, price_html, in_stock } per attribute.
// Works for single-attribute variable products (the perfume size use case).
$variation_map = [];
if (is_array($available_variations)) {
	foreach ($available_variations as $variation) {
		foreach ($variation['attributes'] as $attr_key => $attr_value) {
			if (!isset($variation_map[$attr_key])) {
				$variation_map[$attr_key] = [];
			}
			$variation_map[$attr_key][$attr_value] = [
				'price'      => $variation['display_price'],
				'price_html' => $variation['price_html'],
				'in_stock'   => $variation['is_in_stock'],
			];
		}
	}
}

do_action('woocommerce_before_add_to_cart_form');
?>

<form
	class="variations_form cart"
	action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
	method="post"
	enctype="multipart/form-data"
	data-product_id="<?php echo absint($product->get_id()); ?>"
	data-product_variations="<?php echo $variations_attr; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above */ ?>"
>
	<?php do_action('woocommerce_before_variations_form'); ?>

	<?php if (empty($available_variations) && false !== $available_variations): ?>
		<p class="stock out-of-stock">
			<?php echo esc_html(apply_filters('woocommerce_out_of_stock_message', __('This product is currently out of stock and unavailable.', 'woocommerce'))); ?>
		</p>
	<?php else: ?>

		<?php foreach ($attributes as $attribute_name => $options): ?>
			<div class="lenvy-variation-attribute" data-attribute="<?php echo esc_attr(sanitize_title($attribute_name)); ?>">

				<label class="lenvy-variation-label">
					<?php echo wc_attribute_label($attribute_name); /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WC core function */ ?>
				</label>

				<?php // Hidden select — WC JS reads this. ?>
				<div class="variations lenvy-sr-only">
					<?php
					wc_dropdown_variation_attribute_options([
						'options'   => $options,
						'attribute' => $attribute_name,
						'product'   => $product,
					]);
					?>
				</div>

				<?php // Visible tile buttons. ?>
				<div class="lenvy-variation-tiles" role="radiogroup" aria-label="<?php echo esc_attr(wc_attribute_label($attribute_name)); ?>">
					<?php
					$attr_key    = 'attribute_' . sanitize_title($attribute_name);
					$attr_lookup = $variation_map[$attr_key] ?? [];

					// Determine which options are term-based vs custom text.
					$is_taxonomy = taxonomy_exists($attribute_name);

					foreach ($options as $option):
						$term_name = $option;
						if ($is_taxonomy) {
							$term = get_term_by('slug', $option, $attribute_name);
							if ($term) {
								$term_name = $term->name;
							}
						}

						$info     = $attr_lookup[$option] ?? null;
						$in_stock = $info ? $info['in_stock'] : true;
						$price    = $info ? wc_price($info['price']) : '';
						?>
						<button
							type="button"
							class="lenvy-variation-tile<?php echo !$in_stock ? ' is-oos' : ''; ?>"
							data-attribute="<?php echo esc_attr($attr_key); ?>"
							data-value="<?php echo esc_attr($option); ?>"
							role="radio"
							aria-checked="false"
							<?php echo !$in_stock ? 'aria-disabled="true"' : ''; ?>
						>
							<span class="lenvy-variation-tile__label"><?php echo esc_html($term_name); ?></span>
							<?php if ($price): ?>
								<span class="lenvy-variation-tile__price"><?php echo wp_kses_post($price); ?></span>
							<?php endif; ?>
							<?php if (!$in_stock): ?>
								<span class="lenvy-variation-tile__oos"><?php esc_html_e('Uitverkocht', 'lenvy'); ?></span>
							<?php endif; ?>
						</button>
					<?php endforeach; ?>
				</div>

				<?php
				// Reset link after last attribute.
				if (end($attribute_keys) === $attribute_name) {
					echo wp_kses_post(
						apply_filters(
							'woocommerce_reset_variations_link',
							'<a class="reset_variations" href="#" aria-label="' . esc_attr__('Clear options', 'woocommerce') . '">' . esc_html__('Clear', 'woocommerce') . '</a>'
						)
					);
				}
				?>
			</div>
		<?php endforeach; ?>

		<div class="reset_variations_alert screen-reader-text" role="alert" aria-live="polite" aria-relevant="all"></div>

		<?php do_action('woocommerce_after_variations_table'); ?>

		<div class="single_variation_wrap">
			<?php
			do_action('woocommerce_before_single_variation');
			do_action('woocommerce_single_variation');
			do_action('woocommerce_after_single_variation');
			?>
		</div>

	<?php endif; ?>

	<?php do_action('woocommerce_after_variations_form'); ?>
</form>

<?php do_action('woocommerce_after_add_to_cart_form'); ?>
