<?php
/**
 * The template for displaying single posts.
 *
 * @package Lenvy
 */

get_header();
?>

<main id="primary" class="site-main py-12">
	<div class="container mx-auto px-4 max-w-screen-xl">
		<div class="max-w-3xl mx-auto">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'templates/content', 'post' );

				the_post_navigation(
					[
						'prev_text' => '<span class="nav-subtitle">' . esc_html__( '&larr; Previous', 'lenvy' ) . '</span><span class="nav-title">%title</span>',
						'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next &rarr;', 'lenvy' ) . '</span><span class="nav-title">%title</span>',
					]
				);

				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			endwhile;
			?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
