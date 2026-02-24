<?php
/**
 * Full-page search overlay.
 *
 * Rendered after <header> in site-header.php.
 * JS hooks: [data-search-toggle] opens, [data-search-close] closes.
 * ESC key also closes (handled by search.js module).
 *
 * @package Lenvy
 */

defined('ABSPATH') || exit();
?>
<div
	data-search-overlay
	class="fixed inset-0 z-[45] bg-white opacity-0 pointer-events-none transition-opacity duration-200 flex flex-col"
	aria-hidden="true"
	role="dialog"
	aria-label="<?php esc_attr_e( 'Search', 'lenvy' ); ?>"
>

	<!-- Close bar -->
	<div class="flex items-center justify-end px-6 h-15 border-b border-neutral-100 shrink-0">
		<button
			type="button"
			data-search-close
			class="flex items-center gap-2 text-sm text-neutral-500 hover:text-black transition-colors duration-150"
			aria-label="<?php esc_attr_e( 'Close search', 'lenvy' ); ?>"
		>
			<?php esc_html_e( 'Close', 'lenvy' ); ?>
			<?php lenvy_icon( 'close', 'ml-1', 'sm' ); ?>
		</button>
	</div>

	<!-- Search form — vertically centred in the remaining space -->
	<div class="flex-1 flex flex-col items-center justify-center px-6 pb-24">
		<form
			role="search"
			method="get"
			action="<?php echo esc_url( home_url( '/' ) ); ?>"
			class="w-full max-w-2xl"
		>
			<label
				for="lenvy-search"
				class="block text-xs font-medium uppercase tracking-widest text-neutral-400 mb-5"
			>
				<?php esc_html_e( 'What are you looking for?', 'lenvy' ); ?>
			</label>
			<div class="flex items-center gap-4 border-b-2 border-black pb-3">
				<input
					id="lenvy-search"
					type="search"
					name="s"
					autocomplete="off"
					spellcheck="false"
					placeholder="<?php esc_attr_e( 'Search for perfumes, brands…', 'lenvy' ); ?>"
					class="flex-1 bg-transparent text-2xl md:text-3xl font-light text-neutral-900 placeholder:text-neutral-300 outline-none"
					value="<?php echo esc_attr( get_search_query() ); ?>"
				/>
				<button
					type="submit"
					class="shrink-0 text-neutral-400 hover:text-black transition-colors duration-150"
					aria-label="<?php esc_attr_e( 'Search', 'lenvy' ); ?>"
				>
					<?php lenvy_icon( 'search', '', 'lg' ); ?>
				</button>
			</div>
		</form>
	</div>

</div>
