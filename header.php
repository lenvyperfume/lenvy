<?php
/**
 * The site header template.
 *
 * @package Lenvy
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'antialiased bg-white text-neutral-900' ); ?>>
<?php wp_body_open(); ?>

<a class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 z-50 bg-black text-white px-4 py-2 text-sm" href="#primary">
	<?php esc_html_e( 'Skip to content', 'lenvy' ); ?>
</a>

<?php get_template_part( 'template-parts/header/site-header' ); ?>
