<?php
/**
 * Reusable container component.
 *
 * Pass args via get_template_part():
 *   'callback' (callable) — echoes inner content.
 *   'classes'  (string)   — additional Tailwind classes on the wrapper.
 *
 * @package Lenvy
 */

defined( 'ABSPATH' ) || exit;

$callback = $args['callback'] ?? null;
$classes  = isset( $args['classes'] ) ? ' ' . sanitize_text_field( $args['classes'] ) : '';
?>
<div class="container mx-auto px-4 max-w-screen-xl<?php echo esc_attr( $classes ); ?>">
	<?php
	if ( is_callable( $callback ) ) {
		$callback();
	}
	?>
</div>
