<?php
/**
 * Template part for displaying page content.
 *
 * @package Lenvy
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'prose prose-neutral max-w-none' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail mb-8 rounded-sm overflow-hidden aspect-[16/7]">
			<?php
			the_post_thumbnail(
				'full',
				[ 'class' => 'w-full h-full object-cover' ]
			);
			?>
		</div>
	<?php endif; ?>

	<header class="entry-header mb-8">
		<?php if ( ! is_front_page() ) : ?>
			<h1 class="entry-title text-3xl sm:text-4xl font-light tracking-tight text-neutral-900 not-prose">
				<?php the_title(); ?>
			</h1>
		<?php endif; ?>
	</header>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			[
				'before' => '<div class="page-links not-prose mt-8">' . esc_html__( 'Pages:', 'lenvy' ),
				'after'  => '</div>',
			]
		);
		?>
	</div>

</article>
