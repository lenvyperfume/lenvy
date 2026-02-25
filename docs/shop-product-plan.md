# Shop & Product Experience — Technical Plan

> Status: Audit-based planning document — reflects current implementation state and identifies remaining work.
> Theme: Lenvy · WooCommerce perfume store · Dutch market
> Stack: PHP 8.0 · WooCommerce · ACF Pro · Tailwind CSS v4 · Vite v6

---

## 1. Template Hierarchy

### 1.1 WooCommerce Template Overrides (`woocommerce/`)

| Template | Status | Purpose |
|---|---|---|
| `archive-product.php` | ✅ Implemented | Main shop grid — sidebar + product grid, breadcrumb, sort bar |
| `taxonomy-product_cat.php` | ✅ Implemented | Category archive — ACF banner, filtered grid |
| `taxonomy-product_brand.php` | ✅ Implemented | Brand archive — banner, logo, metadata, filtered grid |
| `single-product.php` | ✅ Implemented | Two-column layout: gallery left, details right |
| `content-product.php` | ✅ Implemented | Loop item — delegates directly to `product-card.php` component |
| `single-product/product-image.php` | ✅ Implemented | Custom gallery with thumbnail strip |
| `single-product/related.php` | ✅ Implemented | Related products grid using `product-card.php` |
| `myaccount/myaccount.php` | ✅ Implemented | Branded account layout (lavender band + horizontal tabs) |
| `myaccount/dashboard.php` | ✅ Implemented | Editorial serif card grid |
| `myaccount/form-login.php` | ✅ Implemented | Split login/register layout |
| `loop/no-products-found.php` | ⬜ Pending | Branded empty state with clear-filters CTA |
| `loop/sale-flash.php` | ⬜ Pending | Custom sale badge (currently handled via `onsale` SCSS + `badge.php`) |
| `global/wrapper-start.php` | ⬜ Pending | Custom WC main content wrapper open |
| `global/wrapper-end.php` | ⬜ Pending | Custom WC main content wrapper close |

### 1.2 Theme Template Parts (`template-parts/`)

#### components/

| File | Status | Purpose |
|---|---|---|
| `product-card.php` | ✅ Implemented | Primary product card: image, badge, brand, name, price, quick-add |
| `product-card-mini.php` | ✅ Implemented | Compact card for related/upsell rows |
| `badge.php` | ✅ Implemented | Variants: sale / new / oos / custom |
| `button.php` | ✅ Implemented | Variants: primary / secondary / outline |
| `breadcrumb.php` | ✅ Implemented | WC-aware breadcrumb trail |
| `pagination.php` | ✅ Implemented | Numeric pagination with prev/next |
| `icon.php` | ✅ Implemented | Inline SVG helper — maps name → `assets/icons/{name}.svg` |
| `notice.php` | ✅ Implemented | WC success / error / info notices |

#### shop/

| File | Status | Purpose |
|---|---|---|
| `filter-sidebar.php` | ✅ Implemented | Desktop sticky filter panel |
| `filter-drawer.php` | ✅ Implemented | Mobile full-screen filter drawer |
| `filter-active.php` | ✅ Implemented | Active filter chips row + "Clear all" |
| `filter-accordion.php` | ✅ Implemented | Collapsible filter group block |
| `filter-taxonomy.php` | ✅ Implemented | Checkbox list for any taxonomy or attribute |
| `filter-price.php` | ✅ Implemented | Dual-handle price range slider |
| `sort-bar.php` | ✅ Implemented | Results count + sort dropdown |

### 1.3 Logic in `inc/`

| File | Status | Contents |
|---|---|---|
| `inc/shop.php` | ✅ Implemented | `pre_get_posts` filter logic for all filter vars + sorting |
| `inc/woocommerce.php` | ✅ Implemented | Hook removals, loop config, wrapper overrides, review disabling |
| `inc/helpers.php` | ✅ Implemented | All template utility functions |
| `inc/ajax.php` | ✅ Implemented | `lenvy_ajax_add_to_cart` + `lenvy_ajax_filter_products` handlers |
| `inc/acf.php` | ✅ Implemented | ACF options page + JSON sync |
| `inc/taxonomies.php` | ✅ Implemented | `product_brand` taxonomy registration |
| `inc/admin.php` | ✅ Implemented | Product list columns (thumbnail, brand) |

---

## 2. Product Card Component

### 2.1 Data Points

**Always resolved from WooCommerce product object:**

| Field | Source | Notes |
|---|---|---|
| Product ID | `get_the_ID()` or `$args['product_id']` | Required, validated via `wc_get_product()` |
| Permalink | `get_permalink($product_id)` | WPML-safe |
| Name | `$product->get_name()` | Escaped on output |
| Price HTML | `$product->get_price_html()` | WC returns safe HTML; output with `phpcs:ignore` |
| Image ID | `$product->get_image_id()` | Passed to `wp_get_attachment_image()` |
| Sale status | `$product->is_on_sale()` | Badge logic |
| Stock status | `$product->is_in_stock()` | Badge + quick-add gating |
| Is purchasable | `$product->is_purchasable()` | Quick-add gating |
| Product type | `$product->get_type()` | `simple` → quick-add; other → "Select options" link |
| Add-to-cart URL | `$product->add_to_cart_url()` | Used as `data-add-to-cart-url` |
| Add-to-cart text | `$product->add_to_cart_text()` | Button label |

**From `product_brand` taxonomy (optional):**

| Field | Source | Controlled by |
|---|---|---|
| Brand name | `get_the_terms($id, 'product_brand')[0]->name` | `$args['show_brand']` bool (default: true) |

**From ACF (`lenvy_product_badge_text`):**

| Field | Key | Type | Notes |
|---|---|---|---|
| Custom badge | `lenvy_product_badge_text` | text | Overrides auto-badge; e.g. "New", "Bestseller" |

### 2.2 Badge Priority Logic

```
Out of stock  → "Uitverkocht" badge (oos variant — neutral)
Custom badge  → ACF text value (new variant — primary color)
On sale       → "Sale" badge (sale variant — primary color)
None          → no badge
```

### 2.3 Component Signature

```php
get_template_part('template-parts/components/product-card', null, [
    'product_id'  => int,    // required
    'show_brand'  => bool,   // default: true. Set false on brand archive pages.
    'image_size'  => string, // default: 'woocommerce_thumbnail'
]);
```

### 2.4 Quick-Add Strategy

- **Simple products, in stock:** Button slides up from bottom on card hover (`translate-y-full → translate-y-0`). Fires `data-quick-add` handler in `modules/quick-add.js` via event delegation on `document`.
- **Variable products / out of stock:** Link slides up with "Select options" text → goes to product permalink. No AJAX.
- **Event delegation pattern:** `document.addEventListener('click', e => { const btn = e.target.closest('[data-quick-add]'); ... })` — survives AJAX grid replacements.
- **Confirmation:** Button temporarily shows ✓ icon + "Added" text for 1.5s before reverting.

### 2.5 Reusability

Used in:
- `woocommerce/archive-product.php` — main shop loop
- `woocommerce/taxonomy-product_cat.php` — category archive
- `woocommerce/taxonomy-product_brand.php` — brand archive
- `woocommerce/single-product/related.php` — related products
- `template-parts/homepage/featured-products.php` — homepage featured row
- AJAX filter handler (`inc/ajax.php`) — re-renders grid via `get_template_part()`

### 2.6 Performance Considerations

- `wc_get_product()` call is the only database hit per card — WC caches internally
- `lenvy_field()` reads from ACF's internal cache (no extra DB call if fields are warmed)
- `wp_get_attachment_image()` generates `srcset` automatically from WC-registered image sizes
- `loading="lazy"` on all card images; hero/above-fold images should override with `loading="eager"` at the call site
- No N+1: the loop pre-fetches the post objects; product objects are retrieved via WC's internal product cache

---

## 3. Shop Page

### 3.1 Grid Layout

```
Desktop (lg+):  [filter-sidebar ~260px] [product-grid flex-1]
                Grid: 3 columns · gap-x-4 gap-y-10

Tablet (md):    No sidebar · drawer only
                Grid: 2 columns

Mobile (sm):    No sidebar · drawer only
                Grid: 2 columns (tight)
```

Grid classes: `grid grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-10`

Filter sidebar uses `position: sticky; top: var(--header-height)` — stays in view while scrolling the grid.

### 3.2 Pagination

**Chosen approach: standard numeric pagination.**

Rationale:
- Shareable, bookmarkable URLs (`/shop/page/2/`)
- Zero JS complexity in Phase 1
- Works with AJAX filter system (pagination links are re-rendered by the AJAX response)
- Consistent with WooCommerce's own URL structure

Implemented via `lenvy_pagination()` → `template-parts/components/pagination.php`

"Load more" or infinite scroll are **not planned** — they complicate filter state, break URL sharing, and are unnecessary for a boutique perfume store with 12 products per page.

### 3.3 Sorting Options

Registered via `woocommerce_default_catalog_orderby` and handled in `inc/shop.php`:

| Option key | Label | Query strategy |
|---|---|---|
| `menu_order` | Featured | `orderby: menu_order title` |
| `date` | Newest | `orderby: date DESC` |
| `price` | Price: Low to High | `meta_key: _price ASC` |
| `price-desc` | Price: High to Low | `meta_key: _price DESC` |
| `popularity` | Best Sellers | `meta_key: total_sales DESC` |

Sort persists in URL as `?orderby=price` — composable with filter params.

### 3.4 Empty State

Current: inline fallback paragraph in `archive-product.php`.

Planned override: `woocommerce/loop/no-products-found.php`
- Centered, generous whitespace
- "No products found" in neutral-500
- "Clear filters" link if `lenvy_is_filtered()` returns true
- Optionally: link to main shop / featured categories

### 3.5 Performance

- Main query: standard WC `WP_Query` — no raw SQL, WC optimises internally
- Filter terms: `lenvy_get_filter_terms($taxonomy)` uses `wp_cache_get('filter_terms_{tax}', 'lenvy')` + 12h transient fallback; flushed on `created_term` / `edited_term` / `delete_term`
- Price min/max: `lenvy_get_min_max_price()` uses 6h transient via single `$wpdb` MIN/MAX query; flushed on `save_post_product`
- Product query: 12 per page, no `posts_per_page = -1` ever used in archives

---

## 4. Filtering System

### 4.1 Filter Groups

| Filter | Query Var | Taxonomy / Meta | Input Type | Implemented |
|---|---|---|---|---|
| Brand | `filter_brand` | `product_brand` | Checkbox list | ✅ |
| Category | `filter_cat` | `product_cat` | Checkbox list | ✅ |
| Gender | `filter_gender` | `pa_gender` | Checkbox list | ✅ |
| Fragrance Family | `filter_family` | `pa_fragrance_family` | Checkbox list | ✅ |
| Concentration | `filter_conc` | `pa_concentration` | Checkbox list | ✅ |
| Volume | `filter_volume` | `pa_volume_ml` | Checkbox list | ✅ |
| Price Range | `min_price` / `max_price` | `_price` meta | Dual-handle slider | ✅ |
| In Stock | `filter_available` | `_stock_status` meta | Toggle | ✅ |
| On Sale | `filter_onsale` | `wc_get_product_ids_on_sale()` | Toggle | ✅ |

### 4.2 URL Parameter Structure

All filter state lives in the URL as query params. Multiple values for a single filter are comma-separated strings:

```
/shop/?filter_brand=chanel,dior&filter_gender=for-her&min_price=50&max_price=200&filter_available=1
```

**Parsing:** `lenvy_parse_filter_slugs($var)` handles both comma-separated strings and PHP array format (`name="var[]"`). Applies `sanitize_title()` to every slug.

**Composability:** Any combination of filters + sorting + pagination works together:
```
/shop/page/2/?filter_brand=chanel&orderby=price&min_price=50
```

### 4.3 Server-Side Query Strategy (`inc/shop.php`)

Hooks into `pre_get_posts` on the main query only (`!is_admin() && $query->is_main_query()`). Active on:
- `post_type_archive('product')` — main /shop/
- `is_tax('product_cat')`, `is_tax('product_brand')`, and all attribute taxonomies

**Tax query:** One `tax_query` clause per active filter, combined with `relation: AND`. Uses `field: slug`, `operator: IN`.

**Meta query:** Separate clauses for `_price` BETWEEN, `_stock_status`, combined with `relation: AND`.

**On Sale:** `wc_get_product_ids_on_sale()` returns IDs → `post__in`. If empty (no sale products), forces `post__in: [0]` to return zero results correctly.

**Security:** All filter slugs parsed through `sanitize_title()`. Numeric values cast to `float`. No raw `$_GET` values used in queries directly.

### 4.4 AJAX Upgrade Path

Phase 2 AJAX layer is implemented in `inc/ajax.php` (`lenvy_ajax_filter_products`) and `resources/js/modules/ajax-filters.js`.

Flow:
1. Filter form change → `ajax-filters.js` serializes form state
2. POST to `wp_ajax_nopriv_lenvy_filter_products`
3. Handler runs `WP_Query` with same filter logic as `inc/shop.php`
4. Returns `{ html, count, pagination, active_filters }` JSON
5. JS replaces `[data-product-grid]`, pagination, active-filter chips
6. `history.pushState()` updates browser URL

AJAX does NOT change the URL param structure — server and AJAX use identical filter params.

### 4.5 Filter Sidebar Behaviour

**Desktop (≥ lg):**
- Left column ~260px, `position: sticky; top: var(--header-height); max-height: calc(100vh - var(--header-height)); overflow-y: auto`
- Each filter group in an accordion (`filter-accordion.php`) — primary filters (Brand, Category, Gender) open by default
- Checkboxes submit on change (AJAX mode) or link-navigate (Phase 1 server mode)

**Mobile (< lg):**
- Sidebar hidden (`hidden lg:block`)
- "Filter & Sort" button in `sort-bar.php` triggers `[data-filter-drawer-toggle]`
- Full-screen drawer from `filter-drawer.php` — contains all groups + "Apply" button
- Uses same drawer JS as the nav drawer (`drawer.js`)

**Active filter chips (`filter-active.php`):**
- Rendered only when `lenvy_is_filtered()` returns true
- Each chip shows label + × button — clicking navigates to URL with that filter removed
- "Clear all" button strips all filter params

---

## 5. Single Product Page

### 5.1 Layout Structure

```
[breadcrumb]
[grid: 55fr | 45fr]
  LEFT — Gallery
    - Main image (WC product gallery)
    - Thumbnail strip below main image
  RIGHT — Details
    - Brand name (link to brand archive)
    - Product name (Playfair italic h1)
    - Price (WC single price)
    - Short description (prose)
    - Add to cart form (.lenvy-atc-form)
      - Quantity input (inline, bordered)
      - Add to cart button (bg-primary)
      - For variable: variation table above button
    - Custom badge (ACF lenvy_product_badge_text)
    - Scent notes grid (Top / Heart / Base) — ACF
    - Usage tips — ACF italic text
    - Meta: SKU, categories, tags
[tabs section]
  - Description tab
  - Attributes tab
  - Reviews tab REMOVED
[related products row]
```

### 5.2 WooCommerce Hooks — Removed

All standard WC single-product hooks are not called via the default template. Instead, specific functions are called directly in `single-product.php`:

| Removed hook | Replaced with |
|---|---|
| `woocommerce_single_product_summary` (all actions) | Direct function calls in template |
| `woocommerce_product_thumbnails` | Custom `product-image.php` override |
| `woocommerce_output_product_data_tabs` | Called directly (reviews tab removed via filter) |
| `woocommerce_output_related_products` | Called directly |

**Reviews disabled completely:** Via `woocommerce_product_tabs` filter (unset `reviews`), `woocommerce_enable_reviews` filter, `comments_open` filter, `remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5)`.

### 5.3 Gallery (`single-product/product-image.php`)

- Main image container with `aspect-ratio` constraint
- Thumbnail strip: horizontal flex row, clicking swaps main image
- `data-product-gallery` attribute drives `modules/gallery.js`
- Gallery JS: cross-fade transition, keyboard arrow nav, thumbnail active state

### 5.4 Add to Cart Form (`.lenvy-atc-form`)

Styled in `_woocommerce.scss`:
- Quantity: inline-flex with bordered wrapper, `input.qty` centered, no arrows
- ATC button: `bg-primary text-black hover:bg-primary-hover` — full brand CTA treatment
- Variable products: variation `<select>` with custom chevron SVG, reset link

### 5.5 ACF Fields on Single Product

| Field key | Display location | Condition |
|---|---|---|
| `lenvy_product_badge_text` | Below ATC form | Only if set |
| `lenvy_product_scent_notes` (group: top/heart/base) | 3-col grid below ATC | Only if any note set |
| `lenvy_product_usage_tips` | Italic text after scent notes | Only if set |

### 5.6 Related Products (`single-product/related.php`)

- WC determines related products (same category + same tags)
- Rendered as 4-column grid using `product-card.php` component
- `woocommerce_related_products_limit` filter sets count to 4
- `show_brand: true` — brand shown on related cards

---

## 6. Homepage Product Sections

### 6.1 Featured Products (`template-parts/homepage/featured-products.php`)

- ACF field: `lenvy_featured_products` — relationship field, max 8 products, selected by editor
- Grid: 4 columns desktop, 2 columns mobile
- Uses `product-card.php` with `show_brand: true`
- No WP_Query — products fetched by stored IDs, no cache overhead

### 6.2 Featured Categories (`template-parts/homepage/featured-categories.php`)

- ACF field: `lenvy_featured_categories` — taxonomy relationship, max 6 `product_cat` terms
- Portrait image grid using `lenvy_cat_banner_image` ACF field on the term
- Links to `get_term_link($term, 'product_cat')`
- No product cards — category blocks only

### 6.3 Promo Section (`template-parts/homepage/promo-sections.php`)

Flexible content field `lenvy_promo_section`, max 4 sections, single layout:

**`text_banner`:** Full-width dark editorial section — background image with overlay, Playfair heading, subheading, optional CTA button.

Brand strip layout was removed.

### 6.4 New Arrivals / Bestsellers

**Not yet implemented as dedicated homepage sections.** Future approach:

- "New Arrivals": `WP_Query` with `orderby: date, posts_per_page: 4`
- "Bestsellers": `WP_Query` with `orderby: meta_value_num, meta_key: total_sales, posts_per_page: 4`
- Both would use `product-card.php` and should be added to `front-page.php` as optional ACF-toggled sections

---

## 7. ACF Field Plan

### 7.1 Field Groups — Current State

| Group JSON | Location | Status |
|---|---|---|
| `group_lenvy_homepage.json` | Front page | ✅ In sync |
| `group_lenvy_product_brand.json` | Taxonomy: `product_brand` | ✅ In sync |
| `group_lenvy_product_cat.json` | Taxonomy: `product_cat` | ✅ In sync |
| `group_lenvy_product_marketing.json` | Post type: `product` | ✅ In sync |
| `group_lenvy_theme_settings.json` | Options page | ✅ In sync |

### 7.2 Product Marketing Fields (`group_lenvy_product_marketing`)

| Field key | Type | Used in |
|---|---|---|
| `lenvy_product_badge_text` | text | Product card badge + single product |
| `lenvy_product_scent_notes` | group | Single product scent notes section |
| `lenvy_product_scent_notes.top_notes` | text | Scent notes grid |
| `lenvy_product_scent_notes.heart_notes` | text | Scent notes grid |
| `lenvy_product_scent_notes.base_notes` | text | Scent notes grid |
| `lenvy_product_usage_tips` | textarea | Single product usage tips |

### 7.3 ACF Access Pattern

- Never call `get_field()` directly in templates
- Always use `lenvy_field($key, $post_id)` — handles ACF inactive state gracefully
- Term fields: `lenvy_field('lenvy_brand_logo', 'term_' . $term->term_id)`
- Options: `lenvy_field('lenvy_announcement_bar_text', 'options')`

### 7.4 JSON Sync

All ACF JSON stored in `acf-json/`. After any field change in WP Admin:
1. ACF auto-saves to `acf-json/` (configured in `inc/acf.php`)
2. Commit the updated JSON file
3. On other environments: WP Admin → ACF → Tools → Sync

---

## 8. Performance & Scalability Strategy

### 8.1 Query Strategy

| Operation | Approach | Cache |
|---|---|---|
| Shop product loop | Standard `WP_Query` via WC | WC internal object cache |
| Filter term lists | `lenvy_get_filter_terms($tax)` | `wp_cache_get` (12h) + transient fallback |
| Price min/max | Single `$wpdb` MIN/MAX query | 6h transient |
| Featured products (homepage) | Fetch by stored IDs | WC product cache |
| Brand / cat ACF fields | `lenvy_field()` | ACF internal cache |

**Avoid:** `posts_per_page: -1`, `get_posts()` without limits, unindexed meta queries.

**Price filter caveat:** `meta_query` on `_price` is an indexed column in WC — acceptable performance for ≤10,000 products. For very large catalogs, consider WC's native price filter widget instead.

### 8.2 Image Sizing Strategy

WooCommerce registers the following image sizes (configurable in WC Settings → Products → Display):

| Size name | Used in |
|---|---|
| `woocommerce_thumbnail` | Product cards (shop grid) |
| `woocommerce_single` | Single product main image |
| `woocommerce_gallery_thumbnail` | Single product thumbnail strip |
| `full` | Hero, promo banners |
| `medium` | Brand logos, ACF preview |

**Rule:** Use `max-h-*` instead of `h-*` on `<img>` elements — `img { height: auto }` in `@layer base` ensures no distortion.

**Lazy loading:** `loading="lazy"` on all product card images. The hero image and first product in the grid should use `loading="eager" fetchpriority="high"`.

### 8.3 WC Styles Cleanup

Dequeued in `inc/woocommerce.php`:
- `wc-blocks-style` — block-specific styles replaced with custom SCSS

The main `woocommerce.css` is still enqueued but overridden at a higher specificity level using `body` prefix where needed (cart/checkout buttons).

### 8.4 JS Strategy

- No jQuery — vanilla JS only
- All modules in `resources/js/modules/`, imported and tree-shaken by Vite
- Event delegation for dynamically-replaced DOM elements (AJAX grid, quick-add)
- No third-party carousel libraries (removed Embla; brand scroller is pure CSS `@keyframes`)
- Embla removal: ~21KB reduction in JS bundle

### 8.5 Object Cache Compatibility

All custom cache calls use:
```
wp_cache_get($key, 'lenvy')
wp_cache_set($key, $value, 'lenvy', $ttl)
wp_cache_delete($key, 'lenvy')
```
The `lenvy` group works with Redis/Memcached when available; falls back to non-persistent in-request cache.

---

## 9. Folder & File Structure

### 9.1 Current State

```
woocommerce/
├── archive-product.php          ✅
├── content-product.php          ✅
├── single-product.php           ✅
├── taxonomy-product_cat.php     ✅
├── taxonomy-product_brand.php   ✅
├── loop/
│   ├── no-products-found.php    ⬜ pending
│   └── sale-flash.php           ⬜ pending
├── single-product/
│   ├── product-image.php        ✅
│   └── related.php              ✅
├── global/
│   ├── wrapper-start.php        ⬜ pending
│   └── wrapper-end.php          ⬜ pending
└── myaccount/
    ├── myaccount.php            ✅
    ├── dashboard.php            ✅
    └── form-login.php           ✅

template-parts/
├── components/
│   ├── badge.php                ✅
│   ├── breadcrumb.php           ✅
│   ├── button.php               ✅
│   ├── container.php            ✅
│   ├── icon.php                 ✅
│   ├── notice.php               ✅
│   ├── pagination.php           ✅
│   ├── product-card.php         ✅
│   └── product-card-mini.php    ✅
├── shop/
│   ├── filter-accordion.php     ✅
│   ├── filter-active.php        ✅
│   ├── filter-drawer.php        ✅
│   ├── filter-price.php         ✅
│   ├── filter-sidebar.php       ✅
│   ├── filter-taxonomy.php      ✅
│   └── sort-bar.php             ✅
├── header/
│   ├── site-header.php          ✅
│   ├── nav-primary.php          ✅
│   ├── nav-mobile.php           ✅
│   └── search-overlay.php       ✅
├── footer/
│   └── site-footer.php          ✅
└── homepage/
    ├── hero.php                 ✅
    ├── brand-scroller.php       ✅
    ├── featured-categories.php  ✅
    ├── featured-products.php    ✅
    └── promo-sections.php       ✅

inc/
├── setup.php                    ✅
├── enqueue.php                  ✅
├── helpers.php                  ✅
├── woocommerce.php              ✅
├── shop.php                     ✅
├── ajax.php                     ✅
├── acf.php                      ✅
├── admin.php                    ✅
├── taxonomies.php               ✅
└── nav-walkers.php              ✅
```

---

## 10. Remaining Work — Prioritised

### Priority 1 — Functional gaps

| Item | File | Notes |
|---|---|---|
| Branded empty state | `woocommerce/loop/no-products-found.php` | Replace inline fallback in archive-product.php |
| Custom sale flash | `woocommerce/loop/sale-flash.php` | WC renders this separately from product card; currently overridden only via SCSS on `span.onsale` |
| WC global wrappers | `woocommerce/global/wrapper-start.php` + `wrapper-end.php` | Prevents WC from outputting its own `<div id="primary">` — currently handled via action hook replace in woocommerce.php |

### Priority 2 — Content sections

| Item | File | Notes |
|---|---|---|
| New arrivals section | `template-parts/homepage/new-arrivals.php` | Optional — `WP_Query` by date |
| Bestsellers section | `template-parts/homepage/bestsellers.php` | Optional — `WP_Query` by `total_sales` meta |
| Generic CMS page | `page.php` | Standard WordPress page template |
| Blog post single | `single.php` | Standard post template |
| Blog archive | `archive.php` | Standard archive template |
| Search results | `search.php` | Site-wide search |

### Priority 3 — QA / Polish

| Item | Notes |
|---|---|
| Cross-browser testing | Safari, Firefox, Chrome — focus on gallery, filters, quick-add |
| Mobile QA | Filter drawer, product grid, single product ATC form on small screens |
| WPML config audit | Verify `wpml-config.xml` covers all translatable strings and ACF fields |
| Image size config | Confirm WC thumbnail dimensions match design (portrait ratio for perfume bottles) |
| Accessibility pass | Focus states, keyboard nav through filters, ARIA on quick-add overlays |

---

## 11. Design Rules Reference

Critical constraints to enforce throughout implementation:

- **Primary color:** `bg-primary` / `text-primary` / `border-primary` only. No `bg-primary/50`, `primary-500`, or opacity variants.
- **Primary hover:** `hover:bg-primary-hover` — the single defined darker lavender.
- **Black CTAs:** Secondary actions use `bg-black text-white hover:bg-neutral-900`.
- **No border-radius:** All UI elements are sharp-cornered (`border-radius: 0`).
- **Typography:** `font-sans` (Inter) for all UI text; `font-serif italic` (Playfair Display) for headings and decorative labels only.
- **Image height:** Use `max-h-*`, never `h-*` fixed on `<img>` tags.
- **Reviews:** Fully disabled — no star ratings, no review forms, no review tab anywhere.
- **Escaping:** All output escaped. `wp_kses_post()` for WC HTML. `esc_html()`, `esc_url()`, `esc_attr()` everywhere else.
