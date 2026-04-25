<?php
/**
 * Brands index — sticky A–Z letter rail.
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$has_letters = (array) ($args['letters'] ?? []);

$alphabet = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
?>

<nav class="lenvy-brands-rail" aria-label="<?php esc_attr_e('Spring naar letter', 'lenvy'); ?>" data-brands-rail>
	<?php foreach ($alphabet as $letter):
		$present = in_array($letter, $has_letters, true);
	?>
		<a
			href="#brands-L-<?php echo esc_attr($letter); ?>"
			class="lenvy-brands-rail__link<?php echo $present ? '' : ' is-dim'; ?>"
			data-brands-rail-letter="<?php echo esc_attr($letter); ?>"
		>
			<?php echo esc_html($letter); ?>
		</a>
	<?php endforeach; ?>
</nav>
