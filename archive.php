<?php
/**
 * The template for displaying archive pages.
 *
 * @package Lenvy
 */

get_header();
?>

<main id="primary" class="site-main py-12">
	<div class="container mx-auto px-4 max-w-screen-xl">

		<header class="mb-10">
			<h1 class="text-3xl sm:text-4xl font-light tracking-tight text-neutral-900">
				<?php echo wp_kses_post( lenvy_archive_title() ); ?>
			</h1>
			<?php
			$archive_description = get_the_archive_description();
			if ( $archive_description ) :
				?>
				<div class="mt-3 text-neutral-600 leading-relaxed">
					<?php echo wp_kses_post( $archive_description ); ?>
				</div>
			<?php endif; ?>
		</header>

		<?php
		if ( have_posts() ) :
			echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">';
			while ( have_posts() ) :
				the_post();
				get_template_part( 'templates/content', get_post_type() );
			endwhile;
			echo '</div>';

			lenvy_pagination();
		else :
			?>
			<p class="text-neutral-500"><?php esc_html_e( 'No posts found.', 'lenvy' ); ?></p>
			<?php
		endif;
		?>

	</div>
</main>

<?php get_footer(); ?>
