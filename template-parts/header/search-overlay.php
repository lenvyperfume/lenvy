<?php
/**
 * Inline header search — slides down from the header bar.
 *
 * Pattern: Douglas / Deloox — header-height search band + semi-transparent
 * backdrop. Page content stays visible; the band slides in over the header.
 *
 * JS hooks:
 *   [data-search-toggle]   — button that opens the search (in site-header.php)
 *   [data-search-band]     — the white band that slides down
 *   [data-search-close]    — any element that closes on click (close btn + backdrop)
 *   [data-search-overlay]  — outer wrapper used by search.js / main.js ESC handler
 *
 * @package Lenvy
 */

defined( 'ABSPATH' ) || exit();
?>
<div
	data-search-overlay
	class="fixed inset-0 z-[45] flex flex-col opacity-0 pointer-events-none transition-opacity duration-200"
	aria-hidden="true"
	role="dialog"
	aria-label="<?php esc_attr_e( 'Search', 'lenvy' ); ?>"
>

	<!-- ── Search band ─────────────────────────────────────────────────────── -->
	<!-- Slides down from above; sits at the same height as the site header.  -->
	<div
		data-search-band
		class="shrink-0 bg-white border-b border-neutral-100 shadow-sm -translate-y-full transition-transform duration-[250ms] ease-out"
	>
		<div class="lenvy-container">
			<div class="flex items-center gap-3 h-[68px]">

				<!-- Close / back -->
				<button
					type="button"
					data-search-close
					class="shrink-0 flex items-center gap-1.5 text-xs font-medium text-neutral-500 hover:text-black transition-colors duration-150 pr-3 border-r border-neutral-200"
					aria-label="<?php esc_attr_e( 'Close search', 'lenvy' ); ?>"
				>
					<?php lenvy_icon( 'close', '', 'sm' ); ?>
					<span class="hidden sm:inline"><?php esc_html_e( 'Close', 'lenvy' ); ?></span>
				</button>

				<!-- Search form — fills remaining header space -->
				<form
					role="search"
					method="get"
					action="<?php echo esc_url( home_url( '/' ) ); ?>"
					class="flex-1 flex items-center gap-3 min-w-0"
				>
					<!-- Decorative search icon -->
					<span class="shrink-0 text-neutral-400" aria-hidden="true">
						<?php lenvy_icon( 'search', '', 'md' ); ?>
					</span>

					<input
						id="lenvy-search"
						type="search"
						name="s"
						autocomplete="off"
						spellcheck="false"
						placeholder="<?php esc_attr_e( 'Search for perfumes, brands…', 'lenvy' ); ?>"
						class="flex-1 min-w-0 bg-transparent text-[15px] text-neutral-900 placeholder:text-neutral-400 outline-none"
						value="<?php echo esc_attr( get_search_query() ); ?>"
					/>

					<button
						type="submit"
						class="shrink-0 px-4 py-1.5 bg-black text-white text-xs font-medium tracking-wide hover:bg-neutral-800 transition-colors duration-150 hidden sm:block"
						aria-label="<?php esc_attr_e( 'Search', 'lenvy' ); ?>"
					>
						<?php esc_html_e( 'Search', 'lenvy' ); ?>
					</button>
				</form>

			</div>
		</div>
	</div>

	<!-- ── Backdrop ────────────────────────────────────────────────────────── -->
	<!-- Clicking anywhere on the backdrop closes the search panel.           -->
	<div
		data-search-close
		class="flex-1 bg-neutral-950/30 cursor-pointer"
		aria-hidden="true"
		tabindex="-1"
	></div>

</div>
