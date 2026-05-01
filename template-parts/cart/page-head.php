<?php
/**
 * Cart page — page head (eyebrow + title + lede + 3-step indicator).
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();
?>

<div class="lenvy-container">
	<nav class="lenvy-cart__crumbs" aria-label="<?php esc_attr_e('Kruimelpad', 'lenvy'); ?>">
		<a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'lenvy'); ?></a>
		<span class="sep" aria-hidden="true">/</span>
		<span aria-current="page"><?php esc_html_e('Winkelwagen', 'lenvy'); ?></span>
	</nav>

	<header class="lenvy-cart__head">
		<div>
			<span class="lenvy-cart__eyebrow"><?php esc_html_e('Stap 1 van 3', 'lenvy'); ?></span>
			<h1 class="lenvy-cart__title"><?php esc_html_e('Jouw winkelwagen.', 'lenvy'); ?></h1>
			<p class="lenvy-cart__lede">
				<?php esc_html_e('Even checken voor we naar de kassa gaan. Verzending en btw worden in de volgende stap berekend.', 'lenvy'); ?>
			</p>
		</div>
	</header>
</div>
