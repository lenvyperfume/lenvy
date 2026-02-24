<?php
/**
 * Container component — standard max-width centered wrapper.
 *
 * Usage:
 *   ob_start();
 *   // ... inner HTML ...
 *   $inner = ob_get_clean();
 *
 *   get_template_part('template-parts/components/container', null, [
 *     'content' => $inner,
 *     'tag'     => 'section', // div|section|main|article|header|footer|aside
 *     'class'   => 'py-16',   // additional classes appended after container base
 *     'id'      => '',        // optional HTML id attribute
 *   ]);
 *
 * The base container class (.lenvy-container) is also available as a standalone
 * CSS utility defined in resources/scss/_components.scss for direct use in
 * template markup without this component.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$content      = $args['content'] ?? '';
$class        = $args['class']   ?? '';
$id           = $args['id']      ?? '';
$allowed_tags = [ 'div', 'section', 'main', 'article', 'header', 'footer', 'aside' ];
$tag          = in_array( $args['tag'] ?? 'div', $allowed_tags, true ) ? ( $args['tag'] ?? 'div' ) : 'div';

$classes = trim( 'lenvy-container ' . $class );
?>
<<?php echo $tag; ?><?php if ( $id ) : ?> id="<?php echo esc_attr( $id ); ?>"<?php endif; ?> class="<?php echo esc_attr( $classes ); ?>">
	<?php
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — content is HTML assembled by trusted template code.
	echo $content;
	?>
</<?php echo $tag; ?>>
