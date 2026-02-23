<?php
/**
 * Front page template.
 *
 * @package Lenvy
 */

get_header(); ?>

<main id="primary" class="site-main">

	<!-- ─── Hero ──────────────────────────────────────────────────────── -->
	<section class="hero relative flex items-center justify-center min-h-[80vh] overflow-hidden bg-neutral-950">
		<?php
  $hero_bg = lenvy_field('hero_background_image');
  if ($hero_bg): ?>
			<div
				class="absolute inset-0 bg-cover bg-center"
				style="background-image:url('<?php echo esc_url($hero_bg['url'] ?? ''); ?>');"
				aria-hidden="true"
			></div>
			<div class="absolute inset-0 bg-neutral-950/60" aria-hidden="true"></div>
		<?php endif;
  ?>

		<div class="container mx-auto px-4 max-w-screen-xl relative z-10 text-center text-white py-20">
			<p class="text-xs uppercase tracking-[0.3em] text-neutral-300 mb-6">
				<?php echo esc_html(lenvy_field('hero_eyebrow', __('New Collection', 'lenvy'))); ?>
			</p>
			<h1 class="text-4xl sm:text-6xl lg:text-7xl font-light tracking-tight leading-none mb-8">
				<?php echo esc_html(lenvy_field('hero_title', get_bloginfo('name'))); ?>
			</h1>
			<p class="max-w-xl mx-auto text-neutral-300 text-lg leading-relaxed mb-12">
				<?php echo esc_html(
    	lenvy_field('hero_subtitle', __('Discover rare and exclusive fragrances curated for the discerning.', 'lenvy')),
    ); ?>
			</p>
			<?php
   $hero_cta_url = lenvy_field(
   	'hero_cta_url',
   	class_exists('WooCommerce') ? wc_get_page_permalink('shop') : home_url('/shop'),
   );
   $hero_cta_label = lenvy_field('hero_cta_label', __('Shop Now', 'lenvy'));
   get_template_part('template-parts/components/button', null, [
   	'label' => esc_html($hero_cta_label),
   	'url' => $hero_cta_url,
   	'variant' => 'outline',
   	'classes' => 'border-white text-white hover:bg-white hover:text-brand-950',
   ]);
   ?>
		</div>
	</section>

	<!-- ─── Featured Products ────────────────────────────────────────── -->
	<section class="featured-products py-20 bg-white">
		<div class="container mx-auto px-4 max-w-screen-xl">
			<header class="text-center mb-12">
				<p class="text-xs uppercase tracking-[0.3em] text-neutral-400 mb-3">
					<?php esc_html_e('Curated Selection', 'lenvy'); ?>
				</p>
				<h2 class="text-3xl sm:text-4xl font-light tracking-tight text-brand-950">
					<?php esc_html_e('Featured Fragrances', 'lenvy'); ?>
				</h2>
			</header>

			<?php if (class_exists('WooCommerce')):
   	$featured_args = [
   		'post_type' => 'product',
   		'posts_per_page' => 4,
   		'tax_query' => [
   			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
   			[
   				'taxonomy' => 'product_visibility',
   				'field' => 'name',
   				'terms' => 'featured',
   			],
   		],
   		'orderby' => 'date',
   		'order' => 'DESC',
   	];
   	$featured = new WP_Query($featured_args);

   	if ($featured->have_posts()):
   		echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">';
   		while ($featured->have_posts()):
   			$featured->the_post();
   			get_template_part('templates/content', 'product');
   		endwhile;
   		echo '</div>';
   		wp_reset_postdata();
   	endif;
   endif; ?>

			<div class="text-center mt-12">
				<?php get_template_part('template-parts/components/button', null, [
    	'label' => esc_html__('View All Products', 'lenvy'),
    	'url' => class_exists('WooCommerce') ? wc_get_page_permalink('shop') : home_url('/shop'),
    	'variant' => 'primary',
    ]); ?>
			</div>
		</div>
	</section>

	<!-- ─── Categories ───────────────────────────────────────────────── -->
	<section class="categories py-20 bg-neutral-50">
		<div class="container mx-auto px-4 max-w-screen-xl">
			<header class="text-center mb-12">
				<p class="text-xs uppercase tracking-[0.3em] text-neutral-400 mb-3">
					<?php esc_html_e('Browse By', 'lenvy'); ?>
				</p>
				<h2 class="text-3xl sm:text-4xl font-light tracking-tight text-brand-950">
					<?php esc_html_e('Fragrance Categories', 'lenvy'); ?>
				</h2>
			</header>

			<?php if (class_exists('WooCommerce')):
   	$categories = get_terms([
   		'taxonomy' => 'product_cat',
   		'hide_empty' => true,
   		'number' => 6,
   		'exclude' => [get_option('default_product_cat')],
   	]);

   	if (!is_wp_error($categories) && !empty($categories)):
   		echo '<div class="grid grid-cols-2 sm:grid-cols-3 gap-4">';
   		foreach ($categories as $category):

   			$thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
   			$image = $thumbnail_id ? wp_get_attachment_image_url((int) $thumbnail_id, 'medium_large') : '';
   			$link = get_term_link($category);
   			?>
						<a
							href="<?php echo esc_url(is_wp_error($link) ? '' : $link); ?>"
							class="group relative flex items-end overflow-hidden rounded-sm aspect-[4/5] bg-neutral-200"
						>
							<?php if ($image): ?>
								<img
									src="<?php echo esc_url($image); ?>"
									alt="<?php echo esc_attr($category->name); ?>"
									class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
									loading="lazy"
								>
							<?php endif; ?>
							<div class="absolute inset-0 bg-gradient-to-t from-neutral-950/70 to-transparent"></div>
							<div class="relative z-10 p-5 w-full">
								<h3 class="text-white text-base font-semibold tracking-widest uppercase">
									<?php echo esc_html($category->name); ?>
								</h3>
								<p class="text-neutral-300 text-xs mt-1">
									<?php printf(
         	/* translators: %d: product count */
         	esc_html(_n('%d product', '%d products', $category->count, 'lenvy')),
         	absint($category->count),
         ); ?>
								</p>
							</div>
						</a>
						<?php
   		endforeach;
   		echo '</div>';
   	endif;
   endif; ?>
		</div>
	</section>

</main><!-- #primary -->

<?php get_footer(); ?>
