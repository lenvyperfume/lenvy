<?php
/**
 * Blog post card — used in archive.php and search.php.
 *
 * Called inside a The Loop; relies on global $post being set.
 *
 * @package Lenvy
 */

defined( 'ABSPATH' ) || exit();

$categories = get_the_category();
?>
<article <?php post_class( 'group' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" class="block overflow-hidden mb-5" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'medium_large', [
				'class' => 'w-full aspect-[4/3] object-cover transition-transform duration-500 group-hover:scale-[1.03]',
			] ); ?>
		</a>
	<?php endif; ?>

	<div class="flex items-center gap-2 text-xs uppercase tracking-widest text-neutral-400 mb-3">
		<?php if ( $categories ) : ?>
			<a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"
			   class="text-neutral-800 hover:text-neutral-500 transition-colors duration-200">
				<?php echo esc_html( $categories[0]->name ); ?>
			</a>
			<span class="text-neutral-200" aria-hidden="true">·</span>
		<?php endif; ?>
		<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
			<?php echo esc_html( get_the_date() ); ?>
		</time>
	</div>

	<h2 class="text-lg font-serif italic text-neutral-900 leading-snug mb-3 group-hover:text-neutral-600 transition-colors duration-200">
		<a href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
		</a>
	</h2>

	<?php $excerpt = get_the_excerpt(); ?>
	<?php if ( $excerpt ) : ?>
		<p class="text-sm text-neutral-500 leading-relaxed line-clamp-2">
			<?php echo esc_html( $excerpt ); ?>
		</p>
	<?php endif; ?>

</article>
