<?php
/**
 * Breadcrumb component.
 *
 * Uses wc_get_breadcrumb() on WooCommerce pages; falls back to a manual
 * implementation on standard WordPress pages.
 *
 * Usage:
 *   get_template_part('template-parts/components/breadcrumb');
 *
 * Or via helper:
 *   lenvy_breadcrumbs();  // defined in inc/helpers.php
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

$crumbs = lenvy_get_breadcrumb_items();

if (count($crumbs) <= 1) {
	return; // No breadcrumb on the homepage or single-crumb contexts.
}
?>
<nav class="lenvy-breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'lenvy'); ?>">
	<ol class="flex flex-wrap items-center gap-1 text-xs text-neutral-500">

		<?php foreach ($crumbs as $index => $crumb): ?>
			<?php
   $crumb_name = $crumb[0] ?? '';
   $crumb_url = $crumb[1] ?? '';
   $is_last = $index === array_key_last($crumbs);
   ?>

			<li class="flex items-center gap-1">

				<?php if ($index > 0): ?>
					<span class="text-neutral-300" aria-hidden="true">
						<?php get_template_part('template-parts/components/icon', null, ['name' => 'chevron-right', 'size' => 'xs']); ?>
					</span>
				<?php endif; ?>

				<?php if ($is_last || empty($crumb_url)): ?>
					<span class="text-neutral-800 font-medium" aria-current="page">
						<?php echo esc_html($crumb_name); ?>
					</span>
				<?php else: ?>
					<a href="<?php echo esc_url($crumb_url); ?>"
					   class="hover:text-neutral-800 transition-colors duration-200">
						<?php echo esc_html($crumb_name); ?>
					</a>
				<?php endif; ?>

			</li>

		<?php endforeach; ?>

	</ol>
</nav>
<?php
// ── BreadcrumbList JSON-LD ────────────────────────────────────────────────
$json_ld_items = [];
foreach ($crumbs as $position => $crumb) {
	$item = [
		'@type'    => 'ListItem',
		'position' => $position + 1,
		'name'     => $crumb[0] ?? '',
	];

	if (!empty($crumb[1])) {
		$item['item'] = $crumb[1];
	}

	$json_ld_items[] = $item;
}
?>
<script type="application/ld+json"><?php echo wp_json_encode([
	'@context'        => 'https://schema.org',
	'@type'           => 'BreadcrumbList',
	'itemListElement' => $json_ld_items,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
