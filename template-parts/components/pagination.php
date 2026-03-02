<?php
/**
 * Pagination component.
 *
 * Handles both WooCommerce shop/archive pages and standard WordPress archives.
 * Renders nothing when there is only one page.
 *
 * Usage:
 *   get_template_part('template-parts/components/pagination');
 *
 * Or via helper:
 *   lenvy_pagination();
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();

global $wp_query;

// ── Determine total pages and current page ────────────────────────────────────

if (function_exists('wc_get_loop_prop') && wc_get_loop_prop('total_pages')) {
	$total = (int) wc_get_loop_prop('total_pages');
	$current = (int) wc_get_loop_prop('current_page');
} else {
	$total = isset($wp_query->max_num_pages) ? (int) $wp_query->max_num_pages : 1;
	$current = max(1, (int) get_query_var('paged'));
}

if ($total <= 1) {
	return;
}

// ── Generate page links ───────────────────────────────────────────────────────

$links = paginate_links([
	'base' => str_replace(PHP_INT_MAX, '%#%', esc_url(get_pagenum_link(PHP_INT_MAX))),
	'format' => '',
	'current' => $current,
	'total' => $total,
	'prev_text' => false,
	'next_text' => false,
	'type' => 'array',
	'end_size' => 1,
	'mid_size' => 2,
]);

if (empty($links)) {
	return;
}
?>
<nav class="lenvy-pagination" aria-label="<?php esc_attr_e('Paginering', 'lenvy'); ?>">
	<ol class="flex items-center justify-center gap-1">

		<?php if ($current > 1): ?>
			<li>
				<a href="<?php echo esc_url(get_pagenum_link($current - 1)); ?>"
				   class="flex items-center justify-center w-9 h-9 border border-neutral-200 text-neutral-600 hover:border-black hover:text-black transition-colors duration-200"
				   aria-label="<?php esc_attr_e('Vorige pagina', 'lenvy'); ?>">
					<?php get_template_part('template-parts/components/icon', null, ['name' => 'chevron-left', 'size' => 'sm']); ?>
				</a>
			</li>
		<?php endif; ?>

		<?php foreach ($links as $link): ?>
			<?php
   // paginate_links() returns full <a> or <span> HTML — extract href and text.
   preg_match('/href=["\']([^"\']+)["\']/', $link, $href_match);
   preg_match('/>([^<]+)</', $link, $text_match);

   $href = $href_match[1] ?? '';
   $page_text = trim($text_match[1] ?? '');
   $is_dots = '&hellip;' === $page_text || '…' === $page_text;
   $is_active = (int) $page_text === $current;
   ?>

			<?php if ($is_dots): ?>
				<li>
					<span class="flex items-center justify-center w-9 h-9 text-neutral-400 text-sm select-none">
						&hellip;
					</span>
				</li>

			<?php elseif ($is_active): ?>
				<li>
					<span class="flex items-center justify-center w-9 h-9 bg-black text-white text-sm font-medium"
					      aria-current="page">
						<?php echo esc_html($page_text); ?>
					</span>
				</li>

			<?php else: ?>
				<li>
					<a href="<?php echo esc_url($href); ?>"
					   class="flex items-center justify-center w-9 h-9 border border-neutral-200 text-neutral-600 text-sm hover:border-black hover:text-black transition-colors duration-200">
						<?php echo esc_html($page_text); ?>
					</a>
				</li>
			<?php endif; ?>

		<?php endforeach; ?>

		<?php if ($current < $total): ?>
			<li>
				<a href="<?php echo esc_url(get_pagenum_link($current + 1)); ?>"
				   class="flex items-center justify-center w-9 h-9 border border-neutral-200 text-neutral-600 hover:border-black hover:text-black transition-colors duration-200"
				   aria-label="<?php esc_attr_e('Volgende pagina', 'lenvy'); ?>">
					<?php get_template_part('template-parts/components/icon', null, ['name' => 'chevron-right', 'size' => 'sm']); ?>
				</a>
			</li>
		<?php endif; ?>

	</ol>
</nav>
