<?php
/**
 * Generic CMS page template.
 *
 * Used for standalone WordPress pages (About, FAQ, Contact, etc.).
 * WooCommerce cart / checkout / account pages bypass the CMS wrapper so
 * their own templates control the layout and container.
 *
 * @package Lenvy
 */

get_header();

// Cart, checkout, and account pages are shortcode-rendered; they own their layout.
$is_wc_functional = function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() );
?>

<main id="primary" class="site-main<?php echo $is_wc_functional ? '' : ' py-12 lg:py-16'; ?>">

	<?php if ( $is_wc_functional ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>

	<?php else : ?>

		<div class="lenvy-container">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="mb-10 overflow-hidden">
						<?php the_post_thumbnail( 'full', [ 'class' => 'w-full max-h-96 object-cover' ] ); ?>
					</div>
				<?php endif; ?>

				<header class="mb-8 max-w-2xl">
					<?php get_template_part( 'template-parts/components/breadcrumb' ); ?>
					<h1 class="mt-4 text-3xl font-serif italic text-neutral-900">
						<?php the_title(); ?>
					</h1>
				</header>

				<div class="entry-content max-w-2xl">
					<?php the_content(); ?>
				</div>

			<?php endwhile; ?>

		</div>

	<?php endif; ?>

</main>

<?php get_footer(); ?>
