<?php

/**
 * The main template file — fallback for all unmatched requests.
 *
 * WordPress uses this file when no more-specific template is found.
 * For this theme the real work is done in archive.php, single.php etc.
 *
 * @package Lenvy
 */

get_header(); ?>

<main id="primary" class="site-main py-12">
	<div class="container mx-auto px-4 max-w-7xl">
		<?php if (have_posts()):
  	if (is_home() && !is_front_page()): ?>
				<header class="mb-10">
					<h1 class="text-3xl font-light tracking-tight text-brand-950">
						<?php esc_html_e('Latest Posts', 'lenvy'); ?>
					</h1>
				</header>
			<?php endif;

  	echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">';
  	while (have_posts()):
  		the_post();
  		get_template_part('templates/content', get_post_type());
  	endwhile;
  	echo '</div>';

  	lenvy_pagination();
  else:
  	 ?>
			<p class="text-neutral-500"><?php esc_html_e('No content found.', 'lenvy'); ?></p>
		<?php
  endif; ?>
	</div>
</main>

<?php get_footer(); ?>
