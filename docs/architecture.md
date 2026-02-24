# Lenvy WooCommerce Architecture Plan

> Generated: 2026-02-24
> Status: Awaiting implementation

---

## 1. Overall Architecture Overview

The architecture follows a three-layer model on top of WordPress core:

```
WordPress + WooCommerce (data & commerce layer)
        ↓
ACF Pro (content management layer)
        ↓
Lenvy Theme — Tailwind v4 + Vite (presentation layer)
```

**Core principles:**

- Component-based PHP templates via `get_template_part()` with `$args` passing
- All WooCommerce hooks either removed and replaced or cleanly extended — never patched
- AJAX filter system designed from day one; AJAX calls implemented in a later phase
- WPML compatibility treated as a first-class constraint, not an afterthought
- Single source of truth: ACF JSON in version control, no database-only config
- No theme functions do database writes; mutations only happen via WP/WC APIs

**Design language (deloox.nl / douglas.nl inspiration):**

- White background dominant, primary color used sparingly for CTAs and accents
- Product imagery carries all visual weight — UI gets out of the way
- Announcement bar → sticky header → mega-nav → content → sticky footer
- Product cards: image-dominant, brand name subdued, price prominent, quick-add on hover
- Filter sidebar: sticky on desktop, full-screen drawer on mobile
- Typography: Inter (body, UI) + Playfair Display (hero headings only)

---

## 2. Data Architecture

### 2.1 Custom Taxonomies

| Taxonomy | Key | Type | Hierarchical | Notes |
|---|---|---|---|---|
| Product Brand | `product_brand` | Custom taxonomy | No | Rich brand pages, ACF fields attached |
| Product Category | `product_cat` | WC native | Yes | Already exists |
| Product Tag | `product_tag` | WC native | No | Already exists |

**`product_brand` registration requirements:**
- Registered in new `inc/taxonomies.php`
- Public, has archive (URL: `/brand/{slug}/`)
- Connected to `product` post type
- `show_in_rest: true` for potential future headless
- WPML translatable

### 2.2 WooCommerce Attributes

These are registered in WC Admin (not in code) and documented in `docs/attributes.md`.

| Attribute | Slug | Type | Used for |
|---|---|---|---|
| Volume (ml) | `pa_volume_ml` | Select | 30ml, 50ml, 75ml, 100ml, 200ml |
| Gender | `pa_gender` | Select | For Her, For Him, Unisex |
| Fragrance Family | `pa_fragrance_family` | Select | Floral, Woody, Oriental, Fresh, Aquatic, etc. |
| Concentration | `pa_concentration` | Select | EDT, EDP, Parfum, EDC |
| Occasion | `pa_occasion` | Select | Casual, Formal, Evening, Sport |

Attributes used as product variations: `pa_volume_ml` only. All others are informational/filterable.

### 2.3 ACF Field Groups

| Group | Attachment | Purpose |
|---|---|---|
| `group_theme_settings` | Options page | Logo, announcement bar, header/footer settings, social links |
| `group_homepage` | Front page (page ID check) | Hero, featured categories, featured products |
| `group_product_cat_enhancements` | Taxonomy: `product_cat` | Banner image, heading, custom layout, SEO text |
| `group_product_brand_fields` | Taxonomy: `product_brand` | Brand logo, banner, description, country of origin |
| `group_product_marketing` | Post type: `product` | Badge text, scent notes (repeater), usage tips |

**Naming convention:** All field keys use `lenvy_` prefix (e.g. `lenvy_hero_heading`). Group files named `group_{slug}.json`.

---

## 3. Theme Template Architecture

### 3.1 Root Templates

| File | Role |
|---|---|
| `front-page.php` | Homepage — assembles homepage template parts |
| `header.php` | WP header wrapper, loads `template-parts/header/site-header.php` |
| `footer.php` | WP footer wrapper, loads `template-parts/footer/site-footer.php` |
| `page.php` | Generic CMS page |
| `single.php` | Blog post single |
| `archive.php` | Blog archive |
| `404.php` | Error page (exists) |
| `index.php` | Fallback (exists) |

### 3.2 WooCommerce Template Overrides

Directory: `woocommerce/` at theme root (standard WC override location).

| Override | Why |
|---|---|
| `archive-product.php` | Full layout control: sidebar + grid columns |
| `taxonomy-product_cat.php` | Category banner + ACF enhancements |
| `single-product.php` | Custom two-column layout |
| `content-product.php` | Product card in loop (delegates to component) |
| `loop/no-products-found.php` | Branded empty state |
| `loop/add-to-cart.php` | Custom "Add to Cart" button styling |
| `loop/sale-flash.php` | Branded sale badge |
| `global/wrapper-start.php` | Removes WC's default `<div id="primary">` |
| `global/wrapper-end.php` | Closes custom wrapper |
| `single-product/related.php` | Custom related products carousel/grid |
| `single-product/product-image.php` | Custom gallery layout |

### 3.3 Template Parts Structure

```
template-parts/
├── components/
│   ├── button.php              — primary / secondary / outline / ghost / icon-only
│   ├── container.php           — max-w-screen-xl centered wrapper
│   ├── product-card.php        — full card with hover quick-add
│   ├── product-card-mini.php   — compact card for related/upsell rows
│   ├── badge.php               — sale / new / out-of-stock badge
│   ├── breadcrumb.php          — custom breadcrumb wrapper
│   ├── pagination.php          — numeric pagination with prev/next
│   ├── icon.php                — inline SVG helper, $args['name'] lookup
│   └── notice.php              — styled WC success / error / info notices
├── shop/
│   ├── filter-sidebar.php      — desktop sticky left filter panel
│   ├── filter-drawer.php       — mobile full-screen filter drawer
│   ├── filter-active.php       — active filter chips row + "Clear all"
│   ├── filter-accordion.php    — reusable collapsible filter group block
│   ├── filter-price.php        — dual-handle price range slider
│   ├── filter-taxonomy.php     — checkbox list for any taxonomy/attribute
│   ├── sort-bar.php            — results count + sort dropdown
│   └── product-grid.php        — loop grid wrapper (accepts args for columns)
├── header/
│   ├── site-header.php         — full header assembly
│   ├── nav-primary.php         — desktop primary nav (uses Primary walker)
│   ├── nav-mobile.php          — mobile nav accordion
│   └── search-overlay.php      — full-page search panel
├── footer/
│   └── site-footer.php         — footer columns, menus, copyright
└── homepage/
    ├── hero.php                — ACF-driven hero section
    ├── featured-categories.php — ACF-driven category grid
    ├── featured-products.php   — ACF-driven product row
    └── announcement-bar.php    — dismissible announcement strip
```

---

## 4. Shop and Filter Architecture

### 4.1 Filter System Design

**State management:** Filter state lives entirely in URL query vars. This gives:
- Shareable, bookmarkable filtered URLs
- Browser back/forward support for free
- Server-side rendering as baseline (AJAX layered on top)
- SEO-compatible if needed (canonical URLs)

**URL pattern:**
```
/shop/?filter_cat=eau-de-parfum&filter_brand=chanel&min_price=50&max_price=300&filter_gender=for-her&filter_available=1&filter_onsale=1
```

**Rendering flow (Phase 1 — server-side):**
1. WP/WC receives request with filter query vars
2. `inc/shop.php` hooks into `pre_get_posts` to modify the main query
3. Templates render the filtered result
4. Filter sidebar renders with current state highlighted

**Rendering flow (Phase 2 — AJAX layer):**
1. User changes a filter → JS serializes form state → POST to `wp_ajax_nopriv_lenvy_filter_products`
2. PHP handler runs a new WP_Query, renders product grid HTML, returns JSON `{ html, count, pagination }`
3. JS replaces grid, pagination, and active-filters chips
4. Browser URL updated via `history.pushState()`

### 4.2 Filter Groups

| Filter | Type | Source | Implementation |
|---|---|---|---|
| Category | Checkbox tree | `product_cat` taxonomy | `tax_query` |
| Brand | Checkbox list | `product_brand` taxonomy | `tax_query` |
| Price Range | Dual slider | Post meta `_price` | `meta_query` (min/max) |
| Volume | Checkbox | WC attribute `pa_volume_ml` | `tax_query` |
| Gender | Pill buttons | WC attribute `pa_gender` | `tax_query` |
| Fragrance Family | Checkbox | WC attribute `pa_fragrance_family` | `tax_query` |
| Concentration | Checkbox | WC attribute `pa_concentration` | `tax_query` |
| In Stock Only | Toggle | WC stock status | `meta_query` |
| On Sale | Toggle | WC `_sale_price` | `post__in` from `wc_get_product_ids_on_sale()` |

### 4.3 Filter Sidebar Component Architecture

**Desktop (≥ lg breakpoint):**
- Fixed-width left column (240–280px)
- Sticky inside the shop grid container (`position: sticky; top: var(--header-height)`)
- Each filter group is an accordion panel (open by default for primary filters)
- Filter counts shown as `(12)` next to each option

**Mobile (< lg breakpoint):**
- Sidebar hidden
- "Filter & Sort" button fixed in shop header
- Opens full-screen drawer from bottom or left
- Drawer contains all filter groups + apply button
- Uses existing drawer JS pattern from `main.js`

---

## 5. ACF JSON Architecture Plan

### 5.1 File Structure

```
acf-json/
├── group_theme_settings.json
├── group_homepage.json
├── group_product_cat_enhancements.json
├── group_product_brand_fields.json
└── group_product_marketing.json
```

### 5.2 Field Group Details

**`group_theme_settings`** — Options page:
```
lenvy_site_logo                 image
lenvy_site_logo_light           image        (for dark backgrounds)
lenvy_announcement_bar_enabled  true_false
lenvy_announcement_bar_text     text
lenvy_announcement_bar_link     link
lenvy_header_sticky             true_false
lenvy_footer_copyright_text     text
lenvy_footer_social_links       repeater
  └── platform                  select (Instagram, Facebook, TikTok, Pinterest, YouTube)
  └── url                       url
lenvy_contact_email             email
lenvy_contact_phone             text
```

**`group_homepage`** — Condition: page template = front-page.php:
```
lenvy_hero_image                image
lenvy_hero_video_url            url          (optional)
lenvy_hero_heading              text
lenvy_hero_subheading           textarea
lenvy_hero_cta_label            text
lenvy_hero_cta_url              url
lenvy_hero_text_position        select       (left/center)
lenvy_featured_categories       relationship (taxonomy: product_cat, max 6)
lenvy_featured_products         relationship (post type: product, max 8)
lenvy_promo_section             flexible_content
  layout: text_banner
    └── heading, subheading, background_image, cta
  layout: brand_strip
    └── brands (relationship to product_brand)
```

**`group_product_cat_enhancements`** — Taxonomy: product_cat:
```
lenvy_cat_banner_image          image
lenvy_cat_banner_heading        text         (optional override of term name)
lenvy_cat_layout                select       (grid / featured-first)
lenvy_cat_featured_product      relationship (single product, shown large)
lenvy_cat_seo_text              wysiwyg      (displayed below grid)
```

**`group_product_brand_fields`** — Taxonomy: product_brand:
```
lenvy_brand_logo                image
lenvy_brand_banner_image        image
lenvy_brand_description         wysiwyg
lenvy_brand_country_of_origin   text
lenvy_brand_is_featured         true_false
lenvy_brand_website_url         url
```

**`group_product_marketing`** — Post type: product:
```
lenvy_product_badge_text        text         (e.g. "New", "Bestseller")
lenvy_product_scent_notes       group
  └── top_notes                 text
  └── heart_notes               text
  └── base_notes                text
lenvy_product_inspiration_text  text
lenvy_product_usage_tips        textarea
```

### 5.3 WPML Field Configuration

| Field type | WPML mode |
|---|---|
| text, textarea, wysiwyg | `translate` |
| image (ID) | `copy` (same media, translate alt text separately) |
| url | `translate` |
| relationship | `translate` (point to translated post/term) |
| true_false, select, number | `copy` |

---

## 6. Frontend Component Architecture

### 6.1 Design Tokens (Tailwind CSS v4)

Defined in `resources/css/tailwind.css` via `@theme`:

```css
/* Brand — SINGLE primary color only, no shades */
--color-primary:      [single brand hex];
--color-black:        #0a0a0a;
--color-white:        #ffffff;

/* Neutral scale — explicit values only, no auto-generation */
--color-neutral-50:   #fafafa;
--color-neutral-100:  #f5f5f5;
--color-neutral-200:  #e5e5e5;
--color-neutral-300:  #d4d4d4;
--color-neutral-400:  #a3a3a3;
--color-neutral-500:  #737373;
--color-neutral-600:  #525252;
--color-neutral-700:  #404040;
--color-neutral-800:  #262626;
--color-neutral-900:  #171717;
--color-neutral-950:  #0a0a0a;

/* Typography */
--font-sans:          'Inter', sans-serif;
--font-serif:         'Playfair Display', serif;

/* Layout (CSS vars, not Tailwind tokens) */
--header-height:      60px;
--container-width:    1280px;

/* Z-index scale */
--z-header:           40;
--z-drawer:           50;
--z-overlay:          45;
--z-notice:           60;
```

> **CRITICAL:** Only `primary` used — never `primary-500`, `primary-light`, `primary/80`, or any variation.

### 6.2 Component API

**`button.php`** `$args`:
```php
[
  'label'   => string,   // required
  'url'     => string,   // required unless type=submit
  'variant' => 'primary' | 'secondary' | 'outline' | 'ghost',
  'size'    => 'sm' | 'md' | 'lg',   // default: md
  'type'    => 'a' | 'button' | 'submit',
  'icon'    => string,   // icon name, optional
  'attrs'   => string,   // extra HTML attributes string
]
```

**`product-card.php`** `$args`:
```php
[
  'product_id'   => int,      // required
  'show_brand'   => bool,     // default: true
  'show_excerpt' => bool,     // default: false
  'image_size'   => string,   // default: 'woocommerce_thumbnail'
]
```

**`icon.php`** `$args`:
```php
[
  'name'  => string,   // required — maps to assets/icons/{name}.svg
  'class' => string,   // optional Tailwind classes
  'size'  => 'sm' | 'md' | 'lg',
]
```

### 6.3 JavaScript Module Plan

Modules in `resources/js/modules/`, imported from `main.js`.

| Module | Responsibility |
|---|---|
| `header.js` | Sticky detection, header height CSS var, scroll shadow |
| `drawer.js` | Generic drawer open/close (nav + filter drawers) |
| `search.js` | Search overlay open/close/focus |
| `accordion.js` | Filter accordion expand/collapse |
| `price-slider.js` | Dual-handle vanilla JS range slider (no library) |
| `quick-add.js` | Hover quick-add-to-cart AJAX |
| `ajax-filters.js` | (Phase 2) Filter serialization, fetch, DOM replace |
| `mini-cart.js` | (Phase 2) AJAX cart refresh in header |
| `gallery.js` | Product page image gallery with zoom |

### 6.4 Navigation Walkers

| Class | File | Role |
|---|---|---|
| `Lenvy_Primary_Nav_Walker` | `inc/nav-walkers.php` | Desktop nav, dropdown support |
| `Lenvy_Mobile_Nav_Walker` | `inc/nav-walkers.php` | Mobile accordion with submenu toggles |
| `Lenvy_Footer_Nav_Walker` | `inc/nav-walkers.php` | Footer flat links (already exists) |

---

## 7. Backend Architecture

### 7.1 `inc/` File Plan

| File | Contents |
|---|---|
| `inc/setup.php` | Theme support + menu location registration |
| `inc/enqueue.php` | Vite integration (exists) |
| `inc/acf.php` | Options page + JSON save/load |
| `inc/helpers.php` | All template utility functions |
| `inc/woocommerce.php` | WC hook modifications, loop config |
| `inc/nav-walkers.php` | All walker classes |
| `inc/taxonomies.php` | **(new)** Register `product_brand` |
| `inc/shop.php` | **(new)** Filter query modifications, `pre_get_posts` |
| `inc/ajax.php` | **(new)** `wp_ajax` handlers |
| `inc/admin.php` | **(new)** Admin columns, enhancements |

Menu locations to register in `inc/setup.php`:
- `primary` — desktop header nav
- `mobile` — mobile drawer nav
- `footer` — footer primary links
- `footer-secondary` — footer legal links

WC gallery theme supports to add in `inc/setup.php`:
```php
add_theme_support('wc-product-gallery-zoom');
add_theme_support('wc-product-gallery-lightbox');
add_theme_support('wc-product-gallery-slider');
```

### 7.2 WooCommerce Hook Strategy

**Remove from WC defaults:**
- `woocommerce_before_main_content` — replace with own wrapper
- `woocommerce_after_main_content` — replace with own wrapper
- `woocommerce_sidebar` — replace with own filter sidebar
- `woocommerce_breadcrumb` — replace with own `breadcrumb.php`
- Default results count + sort bar — replace with `sort-bar.php`

**Loop configuration:**
- Columns: 3 (desktop) via `woocommerce_product_columns` filter
- Per page: 12 via `loop_shop_per_page` filter

### 7.3 Helper Functions (`inc/helpers.php`)

```
lenvy_field($key, $post_id)          get_field() with null fallback
lenvy_the_field($key, $post_id)      echo escaped field value
lenvy_pagination()                   styled numeric pagination
lenvy_archive_title()                clean archive h1 (no "Category:" prefix)
lenvy_get_product_card($id, $args)   renders product-card component
lenvy_get_image($id, $size, $class)  wp_get_attachment_image with fallback
lenvy_breadcrumbs()                  WC-aware breadcrumb array generator
lenvy_get_active_filters()           structured array of current filter state
lenvy_is_filtered()                  boolean, true if any filter is active
lenvy_get_min_max_price()            [min, max] from published products (cached)
lenvy_format_price($price)           WC price formatting wrapper
```

### 7.4 AJAX Endpoints (`inc/ajax.php`)

All handlers verify `wp_verify_nonce()`. Nonce passed via `wp_localize_script()`.

```
wp_ajax_nopriv_lenvy_filter_products   { html, count, pagination, active_filters }
wp_ajax_nopriv_lenvy_add_to_cart       { fragments, cart_count }
wp_ajax_nopriv_lenvy_get_mini_cart     { html }
```

---

## 8. WPML Compatibility Plan

### 8.1 Menu Locations
Four locations registered in `inc/setup.php`. WPML creates per-language assignments automatically.

### 8.2 URL and Link Safety
All URLs use WPML-filterable functions — no hardcoded paths or language slugs:
- `home_url('/')`
- `get_permalink($id)`
- `get_term_link($term)`
- `get_post_type_archive_link('product')`

### 8.3 String Translation
All PHP strings use `__('text', 'lenvy')` or `_e('text', 'lenvy')`. WPML String Translation scans on activation.

### 8.4 ACF + WPML
WPML ACF compatibility plugin handles translation. Field modes per section 5.3. Options page: WPML creates per-language copies automatically; `lenvy_field($key, 'options')` works correctly.

### 8.5 Taxonomy Translation
`product_brand` registered with `'rewrite' => ['slug' => 'brand']`. WPML provides translated slugs per language. WC native taxonomies handled by WooCommerce Multilingual plugin.

### 8.6 Filter System + WPML
Filter query vars are language-neutral. AJAX filter handler includes language context via `do_action('wpml_switch_language', ICL_LANGUAGE_CODE)`.

---

## 9. Performance Plan

### 9.1 Query Strategy
- Product loop: standard WC `WP_Query` — WC optimises internally
- Filter counts: `wp_cache_get('lenvy_filter_count_{hash}', 'lenvy')` + `set_transient()` fallback; invalidated on `save_post_product` and `edited_product_cat`
- `lenvy_get_min_max_price()`: direct `$wpdb` MIN/MAX query, 6-hour transient
- ACF options fields: static variable cache inside `lenvy_field()` for within-request deduplication
- No N+1 queries in product loops

### 9.2 Asset Loading
- Google Fonts: preconnect + `display=swap` (already in enqueue)
- Tailwind CSS v4: utility-first, all unused classes purged at build (~15–40KB final)
- JS: ES modules, deferred
- WC block styles: already dequeued
- Images: `loading="lazy"` on all non-LCP images; LCP image (hero) gets `fetchpriority="high"`
- Product images: WC standard sizes + `srcset` via `wp_get_attachment_image()`

### 9.3 Object Caching Compatibility
All custom cache calls use WP Object Cache API (`wp_cache_get` / `wp_cache_set`, group `lenvy`). Automatically uses Redis/Memcached when available on GoDaddy. Falls back to non-persistent in-request cache otherwise.

### 9.4 Phase 1 is Server-Side
Phase 1 filters are server-rendered — no extra JS weight, no hydration, full SEO value. AJAX is a pure enhancement in Phase 2.

---

## 10. Implementation Roadmap

### Phase 1 — Foundation (backend + data) ✅
- [x] Add `product_brand` taxonomy in new `inc/taxonomies.php`; load in `functions.php`
- [x] Register four menu locations in `inc/setup.php`
- [x] Add WC gallery theme supports in `inc/setup.php`
- [x] Add `inc/shop.php`, `inc/ajax.php`, `inc/admin.php` stubs; load in `functions.php`
- [x] Register ACF options page in `inc/acf.php`
- [x] Create all 5 ACF field groups and commit JSON to `acf-json/`
- [x] Document WC attributes in `docs/attributes.md`

### Phase 2 — Design tokens + base styles ✅
- [x] Define all Tailwind CSS v4 `@theme` tokens in `resources/css/tailwind.css`
- [x] Write base resets and typography rules in `resources/scss/_base.scss`
- [x] Define any custom Tailwind component utilities needed

### Phase 3 — Core components ✅
- [x] `button.php` — all variants
- [x] `icon.php` + `assets/icons/` directory
- [x] `badge.php`
- [x] `notice.php`
- [x] `breadcrumb.php`
- [x] `pagination.php`

### Phase 4 — Header and navigation ✅
- [x] `Lenvy_Primary_Nav_Walker` and `Lenvy_Mobile_Nav_Walker` in `inc/nav-walkers.php`
- [x] `template-parts/header/nav-primary.php`
- [x] `template-parts/header/nav-mobile.php`
- [x] `template-parts/header/search-overlay.php`
- [x] `template-parts/header/site-header.php` (announcement bar + logo + nav + cart icon)
- [x] Refactor `main.js` into `resources/js/modules/` structure

### Phase 5 — Footer ✅
- [x] `template-parts/footer/site-footer.php`

### Phase 6 — Homepage ✅
- [x] `template-parts/homepage/hero.php`
- [x] `template-parts/homepage/featured-categories.php`
- [x] `template-parts/homepage/featured-products.php`
- [x] `template-parts/homepage/promo-sections.php` (text_banner + brand_strip layouts)
- [x] Wire up `front-page.php`

### Phase 7 — Shop and filters ✅
- [x] `product-card.php` component
- [x] `product-card-mini.php` component
- [x] `sort-bar.php`
- [x] `filter-accordion.php`
- [x] `filter-taxonomy.php`
- [x] `filter-price.php` + `modules/price-slider.js`
- [x] `filter-active.php` (chips row)
- [x] `filter-sidebar.php` (desktop)
- [x] `filter-drawer.php` (mobile)
- [x] `inc/shop.php` — `pre_get_posts` filter logic
- [x] `woocommerce/archive-product.php` override
- [x] `woocommerce/taxonomy-product_cat.php` override
- [x] `woocommerce/content-product.php` override

### Phase 8 — Single product page
- [ ] `woocommerce/single-product.php` override
- [ ] Product gallery (`modules/gallery.js`)
- [ ] Style WC form elements (qty, variations, add-to-cart)
- [ ] Related products section

### Phase 9 — AJAX layer
- [ ] Register AJAX endpoints in `inc/ajax.php`
- [ ] `modules/ajax-filters.js`
- [ ] `modules/quick-add.js`
- [ ] `modules/mini-cart.js`

### Phase 10 — WPML preparation + polish
- [ ] Audit all PHP strings for `__()` wrapping
- [ ] Configure WPML ACF field translation settings
- [ ] `inc/admin.php` — product list columns (brand, thumbnail)
- [ ] Performance pass: transient caching for filter counts and price range
- [ ] Cross-browser / mobile QA pass
- [ ] Build + deploy to staging (`npm run build` → git pull on server)
