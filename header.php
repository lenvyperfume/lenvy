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
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<style>html{opacity:0;transition:opacity .15s ease}</style>
	<noscript><style>html{opacity:1}</style></noscript>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#primary">
	<?php esc_html_e( 'Skip to content', 'lenvy' ); ?>
</a>

<?php get_template_part('template-parts/header/site-header'); ?>
