<?php
/**
 * The template for displaying all pages.
 *
 * @package Lenvy
 */

get_header();
?>

<main id="primary" class="site-main py-12">
	<div class="container mx-auto px-4 max-w-screen-xl">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'templates/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
		endwhile;
		?>
	</div>
</main>

<?php get_footer(); ?>
