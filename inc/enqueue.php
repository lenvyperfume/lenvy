<?php
/**
 * Asset enqueueing — Vite + WordPress integration.
 *
 * Dev mode  → `npm run dev` writes `assets/build/hot`.  Assets are served
 *             from the Vite dev server with HMR.
 * Prod mode → `npm run build` writes `assets/build/.vite/manifest.json`.
 *             Assets are served from hashed build files.
 *
 * @package Lenvy
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the Vite dev-server origin (e.g. "http://localhost:5173")
 * or false when not in dev mode.
 *
 * @return string|false
 */
function lenvy_vite_dev_origin(): string|false {
	$hot = get_template_directory() . '/assets/build/hot';

	if ( ! file_exists( $hot ) ) {
		return false;
	}

	return trim( (string) file_get_contents( $hot ) ) ?: false; // phpcs:ignore WordPress.WP.AlternativeFunctions
}

/**
 * Resolves the public URL for a built Vite entry-point asset.
 *
 * @param  string $entry  Manifest key (relative path of the source entry file).
 * @param  string $type   'js' or 'css'.
 * @return string|false
 */
function lenvy_vite_asset( string $entry, string $type = 'js' ): string|false {
	$manifest_path = get_template_directory() . '/assets/build/.vite/manifest.json';

	if ( ! file_exists( $manifest_path ) ) {
		return false;
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions
	$manifest = json_decode( (string) file_get_contents( $manifest_path ), true );

	if ( ! is_array( $manifest ) || ! isset( $manifest[ $entry ] ) ) {
		return false;
	}

	$data = $manifest[ $entry ];

	if ( 'css' === $type ) {
		if ( ! empty( $data['css'][0] ) ) {
			return get_template_directory_uri() . '/assets/build/' . $data['css'][0];
		}
		return false;
	}

	return get_template_directory_uri() . '/assets/build/' . $data['file'];
}

/**
 * Enqueue front-end scripts and styles.
 */
function lenvy_enqueue_assets(): void {
	$entry    = 'resources/js/main.js';
	$theme_v  = wp_get_theme()->get( 'Version' );
	$origin   = lenvy_vite_dev_origin();
	$is_dev   = (bool) $origin;

	if ( $is_dev ) {
		// ── Dev mode ─────────────────────────────────────────────────────────
		// Vite injects CSS via JS — no separate stylesheet needed.
		// Both scripts must be loaded as ES modules.
		add_action(
			'wp_head',
			static function () use ( $origin, $entry ): void {
				printf(
					'<script type="module" src="%s"></script>' . "\n",
					esc_url( $origin . '/@vite/client' )
				);
				printf(
					'<script type="module" src="%s"></script>' . "\n",
					esc_url( $origin . '/' . $entry )
				);
			}
		);
	} else {
		// ── Production mode ───────────────────────────────────────────────────
		$css_url = lenvy_vite_asset( $entry, 'css' );
		if ( $css_url ) {
			wp_enqueue_style( 'lenvy-main', $css_url, [], $theme_v );
		}

		$js_url = lenvy_vite_asset( $entry, 'js' );
		if ( $js_url ) {
			wp_enqueue_script(
				'lenvy-main',
				$js_url,
				[],
				$theme_v,
				[ 'strategy' => 'defer', 'in_footer' => true ]
			);

			// Mark the script as a module so Vite's ES module output works.
			add_filter(
				'script_loader_tag',
				static function ( string $tag, string $handle ) use ( $js_url ): string {
					if ( 'lenvy-main' !== $handle ) {
						return $tag;
					}
					return '<script type="module" src="' . esc_url( $js_url ) . '" defer></script>' . "\n";
				},
				10,
				2
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'lenvy_enqueue_assets' );

/**
 * Dequeue WooCommerce block styles that conflict with Tailwind.
 */
function lenvy_dequeue_wc_block_styles(): void {
	wp_dequeue_style( 'wc-blocks-style' );
}
add_action( 'wp_enqueue_scripts', 'lenvy_dequeue_wc_block_styles', 200 );
