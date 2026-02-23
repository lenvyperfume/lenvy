<?php
/**
 * Template part for displaying post content (single & loop).
 *
 * @package Lenvy
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(
	is_singular() ? 'prose prose-neutral max-w-none' : 'group flex flex-col',
); ?>>

	<?php if (has_post_thumbnail()): ?>
		<div class="post-thumbnail overflow-hidden rounded-sm <?php echo is_singular()
  	? 'mb-8 aspect-[16/7]'
  	: 'aspect-[4/3] mb-5'; ?>">
			<?php if (!is_singular()): ?>
				<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php endif; ?>
				<?php the_post_thumbnail(is_singular() ? 'full' : 'large', [
    	'class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105',
    ]); ?>
			<?php if (!is_singular()): ?>
				</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<header class="entry-header <?php echo is_singular() ? 'mb-8' : 'mb-4'; ?>">
		<?php if (is_singular()): ?>
			<h1 class="entry-title text-3xl sm:text-4xl font-light tracking-tight text-brand-950 not-prose mb-4">
				<?php the_title(); ?>
			</h1>
		<?php else: ?>
			<h2 class="entry-title text-xl font-semibold text-brand-950 leading-snug">
				<a href="<?php the_permalink(); ?>" class="hover:text-brand-700 transition-colors">
					<?php the_title(); ?>
				</a>
			</h2>
		<?php endif; ?>

		<div class="entry-meta flex flex-wrap gap-x-4 gap-y-1 text-xs text-neutral-400 <?php echo is_singular()
  	? 'not-prose mt-3'
  	: 'mt-2'; ?>">
			<time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
				<?php echo esc_html(get_the_date()); ?>
			</time>
			<?php
   $categories = get_the_category_list(esc_html__(', ', 'lenvy'));
   if ($categories):
   	echo wp_kses_post($categories);
   endif;
   ?>
		</div>
	</header>

	<?php if (!is_singular()): ?>
		<div class="entry-summary text-neutral-600 text-sm leading-relaxed line-clamp-3 flex-1">
			<?php the_excerpt(); ?>
		</div>
		<footer class="entry-footer mt-5">
			<a href="<?php the_permalink(); ?>" class="text-xs font-semibold uppercase tracking-widest text-brand-950 hover:text-brand-700 transition-colors">
				<?php esc_html_e('Read More &rarr;', 'lenvy'); ?>
			</a>
		</footer>
	<?php else: ?>
		<div class="entry-content">
			<?php
   the_content();
   wp_link_pages([
   	'before' => '<div class="page-links not-prose mt-8">' . esc_html__('Pages:', 'lenvy'),
   	'after' => '</div>',
   ]);
   ?>
		</div>
	<?php endif; ?>

</article>
