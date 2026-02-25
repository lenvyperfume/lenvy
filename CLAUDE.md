# CLAUDE.md — Lenvy Theme

Instructions for Claude Code when working in this repository.

---

## Project

WooCommerce perfume store theme targeting the Dutch market.
**Text domain / theme slug:** `lenvy`
**Language:** Dutch-facing frontend, English code and comments.
**Stack:** PHP 8.0+ · WordPress 6.0+ · WooCommerce · ACF Pro · WPML · Tailwind CSS v4 · Vite v6

---

## Commit Rules

- **NO `Co-Authored-By` lines** in commit messages — ever.
- Write concise, lowercase imperative commit messages (`fix`, `add`, `refactor`, not `Fixed`, `Added`).
- Stage specific files by name. Never `git add -A` or `git add .`.
- Only commit when the user explicitly asks.

---

## Build

```bash
npm run dev      # Vite HMR dev server
npm run build    # Production build → assets/build/
```

- Dev mode detected at runtime via `assets/build/hot` file.
- Production reads `assets/build/.vite/manifest.json`.
- Enqueue logic lives in `inc/enqueue.php`.
- Entry point: `resources/js/main.js` → imports `resources/css/tailwind.css` + `resources/scss/main.scss`.
- **Always run `npm run build` before committing** and include the new build assets in the commit.

---

## Design System

### Color tokens — CRITICAL RULE

`--color-primary: #e1c4ff` (soft lavender) is the **only** brand color token.

- **NEVER** use `primary-500`, `primary/80`, `bg-primary/50`, or any shade/variant.
- Use `bg-primary`, `text-primary`, `border-primary` — nothing else.
- All other colors use the neutral scale (`neutral-50` through `neutral-950`).

### Typography

- **Body / UI:** `font-sans` → Inter
- **Display / headings:** `font-serif` → Playfair Display italic
- Logo text fallback: `font-serif italic text-xl tracking-tight text-neutral-900`
- Nav links: `text-sm tracking-[0.02em]`

### Layout tokens (CSS vars, not Tailwind)

```css
--header-height: 68px   /* defined via $header-height in _variables.scss */
--container-width: 1280px
```

Header uses `grid grid-cols-[1fr_auto_1fr] items-center h-[68px]` — logo (left) · nav (center, auto-width) · actions (right).

### Image sizing in templates

Use `max-h-*` instead of `h-*` on `<img>` elements. The base rule `img { height: auto }` lives in `@layer base` (`_base.scss`), which means Tailwind utilities win — but `max-h-*` is safer than `h-*` for explicit height caps and should be preferred. Logo: `block max-h-10 w-auto object-contain`.

---

## PHP Conventions

- **WordPress coding standards:** tabs (not spaces), space before `(` in control structures.
- Every file starts with `defined('ABSPATH') || exit();`.
- All output escaped: `esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses_post()`.
- No hardcoded URLs — always `home_url()`, `get_permalink()`, `get_term_link()`, `get_post_type_archive_link()`.
- All user-visible strings wrapped in `__('text', 'lenvy')` or `_e('text', 'lenvy')`.
- `phpcs:ignore` comments **must be inside `<?php ?>` tags**, never placed in HTML context after `?>` — they render as literal text.

### ACF

- Images returned by ACF default to array format — always use `lenvy_get_image()` which accepts `int|array|false`.
- ACF field reads: `lenvy_field($key, $post_id)` — never call `get_field()` directly in templates.
- Options page fields: `lenvy_field($key, 'options')`.
- Term fields: `lenvy_field($key, 'term_' . $term->term_id)`.

### Filter query vars

- All `$_GET` filter vars may arrive as PHP arrays (`name="var[]"`) **or** comma-separated strings.
- Always use `lenvy_parse_filter_slugs($var)` — never `sanitize_text_field($_GET[$var])` directly.

### Helper functions (`inc/helpers.php`)

| Function | Purpose |
|---|---|
| `lenvy_field($key, $post_id)` | ACF get_field with null fallback |
| `lenvy_the_field($key, $post_id)` | echo escaped ACF text field |
| `lenvy_get_image($id, $size, $class)` | wp_get_attachment_image, accepts ACF array or int |
| `lenvy_icon($name, $class, $size, $label)` | inline SVG icon |
| `lenvy_pagination()` | renders pagination template part |
| `lenvy_get_breadcrumb_items()` | WC-aware breadcrumb array |
| `lenvy_parse_filter_slugs($var)` | sanitised slug array from $_GET var (array or CSV) |
| `lenvy_get_filter_terms($taxonomy)` | get_terms with 12h object-cache (use instead of bare get_terms) |
| `lenvy_get_min_max_price()` | [min, max] from published products, 6h transient |
| `lenvy_get_active_filters()` | structured array of active filter chips |
| `lenvy_is_filtered()` | bool — any filter active |

Add new utility functions to `inc/helpers.php`. Do not create new `inc/` files unless they have a clearly distinct responsibility.

---

## JavaScript Conventions

- Modules in `resources/js/modules/`, imported and initialised in `main.js`.
- All AJAX calls use `window.lenvyAjax.url` and `window.lenvyAjax.nonce` (injected via `wp_head` in `inc/enqueue.php`).
- Use event delegation (`document.addEventListener('click', ...)` + `closest('[data-attr]')`) for elements that may be replaced by AJAX grid updates.
- No jQuery — vanilla JS only.

---

## WooCommerce

- Custom template overrides live in `woocommerce/` at theme root.
- WC hooks are **removed and replaced** in `inc/woocommerce.php` — never patched inline.
- Loop columns: 3 · Per page: 12.
- Product reviews: disabled (tab removed + `woocommerce_product_reviews_enabled` returns false).
- Product grids that work with AJAX filters **must** carry `data-product-grid data-taxonomy="{tax}" data-term="{slug}"` attributes.
- On brand archive (`taxonomy-product_brand.php`): pass `['hide_brand_filter' => true]` to filter-sidebar and filter-drawer to suppress the redundant Brand filter.
- `show_brand: false` on product cards when the brand is already the page context (brand archive).

### AJAX handlers (`inc/ajax.php`)

- `lenvy_ajax_add_to_cart` — quick add, returns `{ cart_count, notice }`.
- `lenvy_ajax_filter_products` — renders grid HTML + pagination + active filters, returns JSON.
- Both registered for `wp_ajax_*` and `wp_ajax_nopriv_*`.
- All handlers call `check_ajax_referer('lenvy_ajax', 'nonce')`.

---

## WPML

- All URLs use WPML-filterable functions (see PHP Conventions).
- `wpml-config.xml` at theme root predefines field translation modes.
- Field mode rules: `text/textarea/wysiwyg/url/relationship` → `translate` · `image/bool/select/number` → `copy` · `contact fields` → `copy-once`.

---

## Taxonomy: `product_brand`

- Registered in `inc/taxonomies.php`.
- URL slug: `/merk/{slug}/` (Dutch for "brand").
- Template: `woocommerce/taxonomy-product_brand.php`.
- ACF fields: `lenvy_brand_banner_image`, `lenvy_brand_logo`, `lenvy_brand_country_of_origin`, `lenvy_brand_website_url`, `lenvy_brand_is_featured`, `lenvy_brand_description`.
- After any rewrite slug change: remind the user to flush rewrite rules at **WP Admin → Settings → Permalinks → Save**.

---

## Key File Map

```
inc/
  setup.php          — theme support, menu locations, textdomain
  enqueue.php        — Vite dev/prod asset loading + window.lenvyAjax injection
  helpers.php        — all template utility functions
  woocommerce.php    — WC hook modifications
  shop.php           — pre_get_posts filter logic
  ajax.php           — wp_ajax handlers
  admin.php          — product list columns (thumbnail, brand)
  taxonomies.php     — product_brand registration
  nav-walkers.php    — Primary / Mobile / Footer nav walkers
  acf.php            — ACF options page + JSON sync

resources/
  css/tailwind.css   — @theme tokens (ONLY place for design tokens)
  scss/_variables.scss — SCSS mirrors of tokens ($header-height etc.)
  scss/_base.scss    — :root CSS vars, body/typography base rules
  scss/_components.scss — .lenvy-container, .lenvy-pagination, utilities
  scss/_woocommerce.scss — WC-specific styles
  js/main.js         — entry point, imports all modules
  js/modules/        — header, drawer, search, accordion, price-slider,
                       gallery, mini-cart, quick-add, ajax-filters, filter-drawer

template-parts/
  components/        — button, container, icon, badge, breadcrumb,
                       pagination, notice, product-card, product-card-mini
  header/            — site-header, nav-primary, nav-mobile, search-overlay
  footer/            — site-footer
  shop/              — sort-bar, filter-sidebar, filter-drawer, filter-active,
                       filter-accordion, filter-taxonomy, filter-price
  homepage/          — hero, featured-categories, featured-products,
                       promo-sections, announcement-bar

woocommerce/
  archive-product.php
  taxonomy-product_cat.php
  taxonomy-product_brand.php
  single-product.php
  single-product/product-image.php
  single-product/related.php
  content-product.php

docs/
  architecture.md         — full system architecture and roadmap
  brand-archive-plan.md   — brand archive implementation plan
  attributes.md           — WC attribute documentation
```

---

## Completed Phases

All 10 planned phases are complete. Additional work done:
- Brand archive (`taxonomy-product_brand.php`) — banner, logo, metadata bar, description, filtered grid
- Header refinement — 3-column grid layout, Playfair logo fallback, dropdown lavender accent
- Search redesign — inline header band (Douglas/Deloox pattern) with slide-down animation

## Remaining / Future Work

- `page.php` — generic CMS page template
- `single.php` — blog post template
- `archive.php` — blog archive template
- `search.php` — site-wide search results
- `woocommerce/loop/no-products-found.php` — branded empty state
- `woocommerce/loop/sale-flash.php` — custom sale badge
- `woocommerce/global/wrapper-start.php` + `wrapper-end.php`
