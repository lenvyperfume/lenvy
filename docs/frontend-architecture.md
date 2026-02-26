# Lenvy Frontend Architecture Document
**Version 1.0 — February 2026**

---

## Overview

This document defines the complete frontend architecture for the Lenvy perfume ecommerce theme. The backend (WooCommerce, ACF, custom PHP) is fully operational. This plan governs how that backend data is surfaced through a premium, minimal, product-first frontend comparable in quality to Deloox and Douglas.

The existing codebase already has a solid structural foundation: Tailwind v4 with explicit tokens, 11 JS modules, 13 WooCommerce template overrides, and a well-factored `inc/` layer. This plan builds on top of what works and defines the remaining gaps and refinements.

---

## 1. Design System

### 1.1 Color System

All tokens live exclusively in `resources/css/tailwind.css` under `@theme`. No inline hex values in templates. No alpha variants. No automatic shade generation.

| Token | Value | Usage |
|---|---|---|
| `--color-primary` | `#e1c4ff` | CTA buttons (Add to Cart, filters, Shop Now) |
| `--color-primary-hover` | `#cfb3f0` | Hover state on primary buttons |
| `--color-black` | `#0a0a0a` | Secondary actions, overlays, cart badge |
| `--color-white` | `#ffffff` | Page background, card surfaces |
| `--color-neutral-50` | `#fafafa` | Page background alternate (filter sidebar bg) |
| `--color-neutral-100` | `#f5f5f5` | Input backgrounds, skeleton loaders |
| `--color-neutral-200` | `#e5e5e5` | Dividers, borders, card outlines |
| `--color-neutral-300` | `#d4d4d4` | Disabled states, placeholder text |
| `--color-neutral-400` | `#a3a3a3` | Secondary metadata (volume, count) |
| `--color-neutral-500` | `#737373` | Secondary body text |
| `--color-neutral-600` | `#525252` | Body text on light surfaces |
| `--color-neutral-700` | `#404040` | Subheadings |
| `--color-neutral-800` | `#262626` | Body text default |
| `--color-neutral-900` | `#171717` | Strong headings |
| `--color-neutral-950` | `#0a0a0a` | Maximum contrast text |

**Semantic mapping (explicit — no new tokens needed):**
- Page background → `bg-white`
- Surface (cards, panels) → `bg-white` with `border border-neutral-200`
- Muted surface (sidebar, footer strip) → `bg-neutral-50`
- Primary text → `text-neutral-900`
- Secondary text → `text-neutral-500`
- Border → `border-neutral-200`
- Divider → `border-neutral-100`

**Rules enforced project-wide:**
- `bg-primary` is the only CTA background color
- `bg-black` is for secondary buttons and functional indicators only
- Never use `bg-primary/50`, `text-primary/80`, or any opacity variant
- No hex values outside `tailwind.css`

---

### 1.2 Typography System

| Role | Tailwind class | Font | Weight | Notes |
|---|---|---|---|---|
| Display / hero | `font-serif italic` | Playfair Display | 400 italic | Headlines on hero, feature sections |
| Section heading | `font-serif italic` | Playfair Display | 400 italic | H2 equivalents on shop, homepage |
| Page title | `font-serif italic` | Playfair Display | 400 italic | Archive titles, product name |
| UI heading | `font-sans font-semibold` | Inter | 600 | Filter labels, form labels, card brand name |
| Body | `font-sans` | Inter | 400 | Default body text |
| Body small | `font-sans text-sm` | Inter | 400 | Metadata, captions |
| Nav link | `font-sans text-sm tracking-[0.02em]` | Inter | 400 | Header and footer nav |
| Button label | `font-sans text-sm font-medium tracking-wide` | Inter | 500 | All button text |
| Price | `font-sans font-semibold` | Inter | 600 | Product price, always prominent |
| Badge / label | `font-sans text-xs font-medium tracking-widest uppercase` | Inter | 500 | Sale flash, category chips |
| Logo | `font-serif italic text-xl tracking-tight` | Playfair Display | 400 italic | Text fallback only |

**Display size:** `clamp(2.5rem, 6vw, 4.5rem)` via `--text-display` — used only on hero headings.

**Line height conventions:**
- Body: `leading-relaxed` (1.625)
- Headings: `leading-tight` (1.25)
- Product card text: `leading-snug` (1.375) — compact, scannable

**Letter spacing:**
- Nav / badges: slight positive tracking (`tracking-[0.02em]` or `tracking-wide`)
- Hero display: `tracking-tight`
- All-caps labels: `tracking-widest`

---

### 1.3 Spacing System

Tailwind's default scale is used as-is. Conventions for common patterns:

| Pattern | Value | Class |
|---|---|---|
| Section vertical padding | 64px / 96px | `py-16` / `py-24` |
| Container horizontal padding | 16px mobile, 24px tablet+ | `px-4 md:px-6` |
| Card internal padding | 16px | `p-4` |
| Grid gap (product) | 24px | `gap-6` |
| Grid gap (tight) | 16px | `gap-4` |
| Stack spacing (form rows) | 16px | `space-y-4` |
| Icon + label gap | 8px | `gap-2` |
| Button horizontal padding | 24px | `px-6` |
| Filter sidebar width | 280px | CSS var or `w-70` |

No custom spacing tokens. Standard Tailwind scale is sufficient.

---

### 1.4 Grid System

**Product grid — the primary layout decision:**

| Breakpoint | Columns | Notes |
|---|---|---|
| Mobile (`< 640px`) | 2 | Always 2 on mobile — mirrors Deloox/Douglas mobile UX |
| Tablet (`640px+`) | 2–3 | 2 when sidebar open, 3 when not |
| Desktop (`1024px+`) | 3 | With sidebar: sidebar (280px) + 3-col grid |
| Wide (`1280px+`) | 3–4 | Shop with sidebar stays at 3; brand/cat full-width uses 4 |

**Sidebar + grid layout:**
On desktop, the shop layout is a two-column CSS grid: `grid-cols-[280px_1fr]`. The product grid inside the right column uses `grid-cols-3`. On mobile, sidebar collapses into a drawer and product grid reverts to `grid-cols-2`.

**Gap:** `gap-x-6 gap-y-8` on product grids (visual breathing room between cards without over-spacing).

---

### 1.5 Container System

A single container class handles all page-level max-width:

- **Max-width:** `1280px` (matches `--container-width`)
- **Padding:** `px-4 md:px-6 lg:px-8`
- **Centering:** `mx-auto`
- **Implementation:** `.lenvy-container` in `_components.scss` — already exists, may need padding-scale refinement

Section-level containers do not need their own class — they inherit from the page container. Full-bleed sections (hero, promotional banners) break out of the container using negative margins or a separate `w-full` wrapper.

---

### 1.6 Responsive Breakpoint Strategy

Tailwind defaults (`sm: 640px`, `md: 768px`, `lg: 1024px`, `xl: 1280px`) — no custom breakpoints.

| Breakpoint | Primary concern |
|---|---|
| Default (mobile) | 2-col product grid, stacked layout, no sidebar |
| `sm: 640px` | Minor spacing adjustments, multi-col forms |
| `md: 768px` | Tablet nav starts, 2–3 col grid transitions |
| `lg: 1024px` | Desktop header with full nav, sidebar appears, 3-col grid |
| `xl: 1280px` | Container caps at 1280px, 4-col possible on full-width grids |

**Mobile-first authoring:** All classes are mobile-default, desktop overrides added with `lg:` prefix. `md:` used sparingly for mid-point adjustments only.

---

### 1.7 Border Radius Standards

| Context | Value | Class |
|---|---|---|
| Buttons | `2px` | `rounded-sm` |
| Inputs / selects | `2px` | `rounded-sm` |
| Product cards | `0px` | No radius — sharp edges reinforce premium editorial feel |
| Badges / chips | `2px` | `rounded-sm` |
| Modals / drawers | `0px` top, `4px` internal panels | Sharp exterior |
| Images | `0px` | No rounding on product images |
| Tooltips | `2px` | `rounded-sm` |

**Rationale:** Sharp edges with minimal or zero radius align with the luxury/editorial perfume category (Douglas, Deloox, Matas all use near-flat edges). Avoid the rounded SaaS aesthetic.

---

### 1.8 Shadow Standards

Shadows are used minimally and only to convey elevation in interactive contexts.

| Context | Shadow | Class |
|---|---|---|
| Default product card | none | — |
| Card hover | subtle, flat | `shadow-sm` (1px diffuse) |
| Dropdowns / nav | directional | `shadow-md` |
| Drawer / overlay | strong | `shadow-xl` |
| Input focus | ring, no shadow | `ring-1 ring-neutral-900` |
| Modals | shadow + overlay | `shadow-2xl` |

**Rule:** Product cards must not have default shadows — clean borders only. Shadow appears on hover as a lightweight depth cue.

---

### 1.9 Interaction States

**Hover:**
- Buttons: background color shift (`bg-primary-hover` or `bg-neutral-800`)
- Links: color shift, no underline in nav; underline in body text
- Product card image: subtle scale (`scale-[1.02]`) with `overflow-hidden`
- Quick-add button: slides up from bottom of card image on card hover

**Focus:**
- All interactive elements: `focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-900 focus-visible:ring-offset-1`
- Inputs: `ring-1 ring-neutral-900` on focus
- No custom focus color — neutral-900 (near-black) is accessible and on-brand

**Active:**
- Buttons: slight opacity reduction `active:opacity-80` for tactile feel
- Filter checkboxes: filled accent (`bg-black` checkmark on `bg-white` box with `border-neutral-900`)

**Disabled:**
- `opacity-50 cursor-not-allowed pointer-events-none`
- Applied on Add-to-Cart when out of stock

**Loading:**
- Buttons in loading state: spinner replaces label text, width held stable
- Product grid refresh: skeleton cards replace live cards during AJAX filter update

**Transitions:**
- Default: `transition-colors duration-150 ease-in-out` (color/bg transitions)
- Slow (drawers, overlays): `transition-transform duration-300 cubic-bezier(0.4, 0, 0.2, 1)`
- Both already defined as `$transition-base` and `$transition-slow` in `_variables.scss`

---

## 2. Component Architecture

All components are PHP partials in `template-parts/`. Each component accepts `$args` via `get_template_part()` — the fifth `$args` parameter available in WP 5.5+.

### 2.1 Layout Components

**Container** (`template-parts/components/container.php`) — _exists_
- Props: `tag` (default `div`), `class` (extra classes), `id`
- Renders `.lenvy-container` wrapper
- Used by every section

**Section** — _new concept, no new file needed_
- Sections are composed inline in page templates using `<section class="py-16 lg:py-24">` + container partial
- No separate section component — avoids over-abstraction

**Grid** — _no dedicated partial_
- Product grids are rendered inline in archive templates with `grid grid-cols-2 md:grid-cols-3 gap-6`
- Grid columns are context-dependent (sidebar open vs. closed) — cannot be abstracted to a static partial

**Stack** — _no dedicated partial_
- Form rows, filter stacks, and navigation lists use `space-y-*` or `flex flex-col gap-*` inline
- Too context-specific to warrant a partial

---

### 2.2 UI Components

**Button** (`template-parts/components/button.php`) — _exists_
- Props: `label`, `variant` (`primary|secondary|outline|ghost`), `href`, `type`, `class`, `icon`, `disabled`, `loading`
- Variants:
  - `primary` → `bg-primary text-black hover:bg-primary-hover`
  - `secondary` → `bg-black text-white hover:bg-neutral-800`
  - `outline` → `border border-neutral-900 bg-transparent hover:bg-neutral-50`
  - `ghost` → `bg-transparent text-neutral-900 hover:bg-neutral-100`
- All variants share: `inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium tracking-wide rounded-sm transition-colors duration-150`

**Input** — _no dedicated partial currently_
- Defined as a CSS utility in `_components.scss` (`input[type="text"]`, etc.)
- Props applied via standard HTML attributes — no PHP partial needed
- Style: `w-full border border-neutral-200 bg-white px-4 py-2.5 text-sm rounded-sm focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 outline-none transition`

**Select** — _same as Input_
- Native `<select>` styled via CSS
- Used in sort-bar, quantity selector (optional), filter options

**Badge** (`template-parts/components/badge.php`) — _exists_
- Props: `label`, `variant` (`sale|new|featured|brand`), `class`
- Renders absolute-positioned chip on product cards and a standalone label elsewhere
- Style: `text-xs font-medium tracking-widest uppercase px-2 py-0.5 rounded-sm`
- Variant colors: `sale` → `bg-black text-white`, `new` → `bg-primary text-black`, `featured` → `bg-neutral-900 text-white`

**Icon** (`template-parts/components/icon.php`) — _exists_
- Props: `name`, `class`, `size` (`sm|md|lg`), `label` (aria)
- Renders inline SVG via file include
- Icons live in `resources/icons/` as individual SVG files

**Icon Button** — _no dedicated partial, composed inline_
- `<button class="flex items-center justify-center w-10 h-10 rounded-sm hover:bg-neutral-100 transition-colors">` + icon partial
- Used for search trigger, cart, wishlist, close buttons

**Notice** (`template-parts/components/notice.php`) — _exists_
- Props: `message`, `type` (`success|error|info|warning`), `dismissible`
- Used for WooCommerce notices, form feedback

---

### 2.3 Ecommerce Components

**ProductCard** (`template-parts/components/product-card.php`) — _exists_
- Props: `product` (WC_Product object), `show_brand` (bool), `class`
- Structure:
  - Image area (aspect-product 3:4) with overlay zone for badges and quick-add
  - Badge slot: Sale flash top-left, custom badge (ACF) top-right
  - Quick-add button: slides up from bottom on card hover, primary button style
  - Brand name: `text-xs tracking-widest uppercase text-neutral-400` (conditionally suppressed on brand archive)
  - Product name: `font-serif italic text-neutral-900 leading-snug line-clamp-2`
  - Product subtitle: `text-sm text-neutral-500` (ACF field — `lenvy_product_subtitle`)
  - Price: `font-semibold text-neutral-900` with sale price crossing out original
- On hover: image scale `scale-[1.02]`, shadow-sm appears, quick-add slides up

**ProductCardMini** (`template-parts/components/product-card-mini.php`) — _exists_
- Used in mini-cart, related products, search results sidebar
- Props: `product`, `show_remove` (bool)
- Horizontal layout: small square image + name + price + optional remove button

**ProductGrid** — _no dedicated partial_
- Rendered inline in archive templates
- Grid classes applied to `<ul>` or `<div>` containing product card partials
- AJAX-refreshable: outer div carries `data-product-grid` and taxonomy/term data attributes

**ProductGallery** (`woocommerce/single-product/product-image.php`) — _exists as WC override_
- Main image + thumbnail strip
- JS module: `gallery.js` handles thumbnail click → main image swap
- On mobile: full-width main image, thumbnail strip scrolls horizontally below
- On desktop: main image left (sticky), thumbnails column on the left of main image (vertical strip)
- Zoom: `cursor-zoom-in` on main image, lightbox on click (vanilla JS, no library)

**AddToCartSection** — _rendered inside `single-product.php`_
- Volume/size selector: custom radio-button pill UI (not native select) — ACF or WC variations
- Quantity selector: `-` / `[n]` / `+` with accessible labels
- Primary CTA: `bg-primary text-black` full-width on mobile, fixed-width on desktop
- Wishlist icon: secondary icon button adjacent to CTA
- Stock indicator: low-stock text in `text-neutral-500 text-sm` below CTA
- No WooCommerce default form markup — fully custom partial

**PriceDisplay** — _rendered inline in card and single-product templates_
- Regular price: `font-semibold text-neutral-900`
- Sale price: `font-semibold text-neutral-900` + original price `line-through text-neutral-400 text-sm ml-2`
- No WC default price classes used — custom markup only

**FilterSidebar** (`template-parts/shop/filter-sidebar.php`) — _exists_
- Desktop only (`hidden lg:block`)
- Fixed width 280px, sticky to top below header
- Sections: Active Filters, Brand, Category, Gender/Fragrance family, Price range
- Each section: accordion with `filter-accordion.php` partial
- Taxonomy sections: `filter-taxonomy.php` (checkbox list)
- Price section: `filter-price.php` (dual-handle range slider)
- Active filters: `filter-active.php` (dismissible chips)

**FilterDrawer** (`template-parts/shop/filter-drawer.php`) — _exists_
- Mobile/tablet only
- Full-height slide-in from left
- Same content as sidebar, rendered via `filter-drawer.js`
- Trigger: "Filter" button in sort-bar on mobile

**CartIcon** — _rendered in header actions area_
- Icon button with item count badge
- Badge: `bg-black text-white text-xs` positioned absolute top-right of icon
- Opens mini-cart drawer on click (not a page redirect)

**QuantitySelector** — _rendered inside AddToCartSection on single product_
- Three-button component: decrement, input[type=number], increment
- Custom styling: `border border-neutral-200` outer container, borderless inner buttons
- Accessible: buttons have aria-labels, input has aria-label

---

### 2.4 Navigation Components

**Header** (`template-parts/header/site-header.php`) — _exists_
- Layout: `grid grid-cols-[1fr_auto_1fr] items-center h-[68px]`
- Left: hamburger (mobile) / logo (desktop fallback)
- Center: logo (mobile) / primary nav (desktop)
- Right: search icon + account icon + cart icon
- Sticky via `position: sticky; top: 0; z-index: 50`
- Background: `bg-white border-b border-neutral-200`
- Scroll-aware: adds `shadow-sm` after 10px scroll (via `header.js`)

**Primary Nav** (`template-parts/header/nav-primary.php`) — _exists_
- `hidden lg:flex` — desktop only
- Links: `text-sm tracking-[0.02em] text-neutral-700 hover:text-neutral-950 transition-colors`
- Active: `text-neutral-950 font-medium`
- Dropdown (if any): `bg-white border border-neutral-200 shadow-md` with lavender accent on hover items
- Walker: `Lenvy_Nav_Walker` in `inc/nav-walkers.php`

**Mobile Menu** (`template-parts/header/nav-mobile.php`) — _exists_
- Full-screen overlay or side drawer
- Contains: full nav links, account link, search input
- Triggered by hamburger, managed by `drawer.js`
- Close button top-right

**Search Overlay** (`template-parts/header/search-overlay.php`) — _exists_
- Inline header band, slides down from top (Douglas/Deloox pattern)
- Input full-width with immediate results or submit
- Managed by `search.js`

**Footer** (`template-parts/footer/site-footer.php`) — _exists_
- Structure: 4-col grid on desktop, 2-col tablet, 1-col mobile
- Columns: About / Navigation / Account / Newsletter signup
- Bottom bar: copyright + secondary nav links
- Background: `bg-neutral-50 border-t border-neutral-200`
- Walker: `Lenvy_Footer_Nav_Walker`

---

### 2.5 Account Components

**Login Form** (`woocommerce/myaccount/form-login.php`) — _exists as WC override_
- Clean two-section layout: Login left, Register right on desktop; stacked on mobile
- Inputs use standard Input styles
- Primary CTA on login: `bg-primary text-black`
- Register CTA: `bg-black text-white`
- Social proof / trust line below form (optional ACF text)

**Account Dashboard** (`woocommerce/myaccount/dashboard.php`) — _exists as WC override_
- Welcome message with customer first name
- Quick-link cards: Orders, Downloads, Addresses, Account Details
- Card style: `border border-neutral-200 p-6` with icon + label + arrow
- No default WooCommerce dashboard markup

**Account Dashboard Layout** — _needs dedicated shell_
- Needs new template override: `woocommerce/myaccount/navigation.php`
- Two-column layout on desktop: sidebar nav left (account sections) + content right
- Sidebar nav: vertical list of account links, active state uses `bg-neutral-100 font-medium`
- This layout wraps all account sub-pages (orders, addresses, etc.)

---

## 3. WooCommerce Integration Strategy

WooCommerce is used exclusively as a **data and transaction layer**. It handles:
- Product data (price, stock, variants, images)
- Cart and session management
- Checkout and payment processing
- Order management and customer accounts
- Tax calculation and shipping logic

WooCommerce outputs **zero frontend HTML** directly to the user. Every template is overridden.

### 3.1 Hook Removal Philosophy

In `inc/woocommerce.php`, WooCommerce action hooks are systematically removed and replaced:

```
remove_action('woocommerce_*', ...)      ← strip default output
add_action('woocommerce_*', ...)         ← add custom partial
```

WooCommerce CSS (`woocommerce.css`) is dequeued entirely. All WC styles live in `resources/scss/_woocommerce.scss`.

### 3.2 Template Override Map

| WC Template | Override Location | Rebuild Strategy |
|---|---|---|
| `archive-product.php` | `woocommerce/archive-product.php` | Full custom layout: header, breadcrumb, sort-bar, sidebar+grid, pagination |
| `taxonomy-product_cat.php` | `woocommerce/taxonomy-product_cat.php` | Same as archive + category banner |
| `taxonomy-product_brand.php` | `woocommerce/taxonomy-product_brand.php` | Brand banner + logo + metadata bar + filtered grid |
| `single-product.php` | `woocommerce/single-product.php` | Custom two-col layout: gallery left, details right |
| `single-product/product-image.php` | `woocommerce/single-product/product-image.php` | Custom gallery partial |
| `single-product/related.php` | `woocommerce/single-product/related.php` | 4-col related products row |
| `content-product.php` | `woocommerce/content-product.php` | Delegates to `product-card.php` component |
| `myaccount/form-login.php` | `woocommerce/myaccount/form-login.php` | Custom two-col login/register |
| `myaccount/dashboard.php` | `woocommerce/myaccount/dashboard.php` | Custom quick-link cards |
| `myaccount/navigation.php` | **needs creation** | Sidebar nav for account section |
| `cart/cart.php` | **needs creation** | Custom cart layout |
| `checkout/form-checkout.php` | **needs creation** | Custom checkout layout |
| `loop/no-products-found.php` | `woocommerce/loop/no-products-found.php` | On-brand empty state |
| `global/wrapper-start.php` | `woocommerce/global/wrapper-start.php` | Product grid container open |
| `global/wrapper-end.php` | `woocommerce/global/wrapper-end.php` | Product grid container close |

### 3.3 WooCommerce Notices

WC notices (add to cart success, error) are intercepted and rendered via the `notice.php` component. The default WC notice markup is suppressed and re-injected via `wc_get_notices()` in custom positions.

### 3.4 Cart and Checkout Architecture

**Cart:** Custom `woocommerce/cart/cart.php`. Two-column on desktop: item list left (2/3 width), order summary right (1/3 width). Each cart row uses `product-card-mini.php`. No WooCommerce default table markup.

**Checkout:** Custom `woocommerce/checkout/form-checkout.php`. Two-column: billing/shipping form left, order summary right. WooCommerce form fields are retained but the surrounding markup and styles are fully custom. WC `woocommerce_checkout_fields` filter controls field order and labels.

---

## 4. ACF Usage Strategy

ACF provides **structured editorial content** only — not layout control.

### 4.1 ACF Field Groups and Usage

| Field Group | Post Type / Location | Fields | Usage |
|---|---|---|---|
| Homepage | `page` (front page) | `hero_image`, `hero_heading`, `hero_subheading`, `hero_cta_label`, `hero_cta_url`, `featured_categories` (relationship), `promo_sections` (repeater), `featured_products` (relationship) | Drives all homepage sections |
| Product Extras | `product` | `product_subtitle`, `product_badge_label`, `product_badge_variant`, `product_scent_notes` (text area) | Enriches product card and single page |
| Brand Fields | `product_brand` taxonomy | `brand_banner_image`, `brand_logo`, `brand_country_of_origin`, `brand_website_url`, `brand_is_featured`, `brand_description` | Brand archive page |
| Site Options | ACF Options page | `announcement_bar_text`, `announcement_bar_enabled`, `site_usp_items` (repeater), `footer_tagline`, `newsletter_heading` | Global content slots |

### 4.2 What ACF Does NOT Control

- Layout decisions (column counts, section ordering, spacing)
- Navigation structure (managed via WP Menus)
- Product data (WooCommerce owns price, stock, categories)
- Component variants (these are hard-coded or PHP-prop-driven)

### 4.3 ACF Helpers

All ACF reads in templates must use `lenvy_field()` and never `get_field()` directly. Image fields use `lenvy_get_image()`. This ensures consistent null handling across the codebase.

ACF JSON sync is active — field group JSON files live in `acf-json/` and are the source of truth.

---

## 5. Tailwind Configuration Plan

Tailwind v4 via `@tailwindcss/vite` — no `tailwind.config.js`.

### 5.1 Color Tokens (in `resources/css/tailwind.css`)

All tokens defined under `@theme` as CSS custom properties. Tailwind v4 automatically generates utility classes from these.

- `--color-primary` and `--color-primary-hover` — explicit, no shade generation
- Full neutral scale (`neutral-50` through `neutral-950`) — explicit values, not generated
- `--color-black` and `--color-white` — explicit overrides to ensure consistent rendering

**What is NOT defined:** No `primary-100`, `primary-200`, etc. No `--color-primary/80` usage anywhere.

### 5.2 Font Configuration

```css
@theme {
  --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif;
  --font-serif: 'Playfair Display', ui-serif, Georgia, serif;
}
```

Google Fonts loaded via `inc/enqueue.php` with preconnect hints. Both families already configured and loading.

### 5.3 Spacing Usage Conventions

- Use Tailwind default spacing scale directly (`p-4`, `gap-6`, `py-16`)
- No custom spacing tokens — the default scale covers all cases
- `--header-height: 68px` lives in SCSS only (used for `calc()` in `top` offsets for sticky elements)
- `--container-width: 1280px` used only in `.lenvy-container` definition

### 5.4 Container Configuration

The `.lenvy-container` class in `_components.scss` is the single container implementation:
- `max-width: 1280px`
- `margin: 0 auto`
- `padding-inline: 1rem` (mobile), `1.5rem` (md+), `2rem` (lg+)

No Tailwind `container` utility is used — too opinionated for this design.

### 5.5 Breakpoint Usage

Tailwind defaults: `sm:640`, `md:768`, `lg:1024`, `xl:1280`. No custom breakpoints registered. The `2xl:` breakpoint is ignored — container caps at 1280px.

### 5.6 Plugin Usage

No Tailwind plugins. All custom utilities (`aspect-product`, `scrollbar-hide`, `line-clamp-*`) are defined in `_components.scss` using `@layer utilities` conventions.

---

## 6. File Structure Plan

### 6.1 Current Structure (already correct — maintain this)

```
template-parts/
  components/
    badge.php
    breadcrumb.php
    button.php
    container.php
    icon.php
    notice.php
    pagination.php
    product-card.php
    product-card-mini.php
  header/
    site-header.php
    nav-primary.php
    nav-mobile.php
    search-overlay.php
  footer/
    site-footer.php
  homepage/
    hero.php
    brand-scroller.php
    featured-categories.php
    featured-products.php
    promo-sections.php
  shop/
    sort-bar.php
    filter-sidebar.php
    filter-drawer.php
    filter-active.php
    filter-accordion.php
    filter-taxonomy.php
    filter-price.php
```

### 6.2 Gaps — New Files to Create

```
template-parts/
  account/                          ← new directory
    dashboard-nav.php               ← account sidebar navigation
    dashboard-welcome.php           ← welcome message + quick-link cards
  product/                          ← new directory
    product-details.php             ← single-product: name, brand, price, ATC area
    product-meta.php                ← single-product: scent notes, brand link, SKU
    quantity-selector.php           ← reusable quantity +/- component
    volume-selector.php             ← size/volume radio pill UI

woocommerce/
  cart/
    cart.php                        ← new: full cart page override
  checkout/
    form-checkout.php               ← new: full checkout page override
  myaccount/
    navigation.php                  ← new: account section sidebar nav
```

### 6.3 File Naming Conventions

- Lowercase, hyphen-separated: `product-card.php`, `filter-sidebar.php`
- Descriptive of function, not visual style: `sort-bar.php` not `top-bar.php`
- WooCommerce overrides must match WC template paths exactly
- No new `inc/` files unless a clearly distinct PHP responsibility arises

---

## 7. Implementation Roadmap

### Phase 1: Design System Foundation
**Goal:** Lock all design tokens. Nothing visual changes — this is infrastructure.

- Audit `resources/css/tailwind.css` — verify all tokens match the spec in this document
- Audit `resources/scss/_variables.scss` — ensure SCSS mirrors are in sync
- Audit `resources/scss/_components.scss` — remove any stale utilities, add missing ones (`aspect-product`, `aspect-banner` if absent)
- Define and document all button variants in `button.php` (primary, secondary, outline, ghost)
- Confirm Inter + Playfair Display load correctly in production build
- Run `npm run build` and verify manifest

**Deliverable:** Locked design token file, verified build.

---

### Phase 2: Layout Primitives
**Goal:** Container, section, and grid patterns work correctly at all breakpoints.

- Refine `.lenvy-container` padding at all breakpoints
- Test grid layouts: 2-col mobile, 3-col desktop, sidebar+grid desktop
- Audit `header-height` usage — ensure sticky elements respect it
- Implement scroll-aware header class in `header.js`
- Verify footer layout at all breakpoints

**Deliverable:** Layout skeleton is pixel-perfect at mobile, tablet, desktop.

---

### Phase 3: Header and Navigation
**Goal:** Premium header that matches the 3-column grid spec.

- Refine `site-header.php` — verify `grid-cols-[1fr_auto_1fr]` layout is correct
- Polish `nav-primary.php` — link styles, hover states, active indicators
- Polish `nav-mobile.php` — drawer animation, full menu, close UX
- Polish `search-overlay.php` — slide-down animation, input focus, close behavior
- Cart icon with live badge count from `mini-cart.js`
- Account icon linking to `lenvy_get_account_choice_url()`
- Announcement bar (if ACF option enabled): `bg-black text-white text-sm py-2 text-center`

**Deliverable:** Header is production-ready, tested on mobile and desktop.

---

### Phase 4: Product Card Component
**Goal:** The most-seen component must be perfect.

- Rebuild `product-card.php` to spec: 3:4 aspect, hover scale, quick-add slide-up
- Badge positioning and variant styling (sale, new, custom ACF badge)
- Brand name (conditional suppression via `show_brand` prop)
- Product name: Playfair italic, line-clamp-2
- Subtitle: ACF `lenvy_product_subtitle` field, conditional render
- Price display: regular vs. sale formatting
- Quick-add integration with `quick-add.js` module
- Test `product-card-mini.php` for cart and search contexts

**Deliverable:** Product card passes visual and functional review at all sizes.

---

### Phase 5: Shop Page Rebuild
**Goal:** Archive and taxonomy pages match Deloox/Douglas quality.

- Rebuild `woocommerce/archive-product.php` — full layout: breadcrumb, sort-bar, sidebar+grid
- Rebuild `woocommerce/taxonomy-product_cat.php` — adds category banner and description
- Verify `woocommerce/taxonomy-product_brand.php` — banner, logo, metadata bar, grid
- Audit `sort-bar.php` — results count, sort dropdown, mobile filter trigger
- Verify `content-product.php` correctly delegates to `product-card.php`
- Test AJAX filter response: grid refresh, active filter chips, URL update
- Empty state: `loop/no-products-found.php` — on-brand empty state with CTA
- Pagination: verify `.lenvy-pagination` styles

**Deliverable:** All shop archive pages are production-ready with working AJAX filters.

---

### Phase 6: Product Page Rebuild
**Goal:** Single product page rivals premium ecommerce sites.

- Rebuild `woocommerce/single-product.php` — two-column layout: gallery left, details right
- Rebuild `woocommerce/single-product/product-image.php` — thumbnail strip + main image + lightbox
- Build `template-parts/product/product-details.php` — name, brand, price, ATC section
- Build `template-parts/product/product-meta.php` — scent notes, brand link, SKU
- Build `template-parts/product/quantity-selector.php`
- Build `template-parts/product/volume-selector.php` (if variations used)
- Rebuild `woocommerce/single-product/related.php` — 4-col row with `product-card.php`
- Breadcrumb: WC-aware via `lenvy_get_breadcrumb_items()`
- Test `gallery.js`: thumbnail click, keyboard navigation, lightbox open/close

**Deliverable:** Single product page is conversion-optimized and visually premium.

---

### Phase 7: Filters UI
**Goal:** Filter experience is fast, clear, and mobile-friendly.

- Audit all filter partials: `filter-sidebar.php`, `filter-drawer.php`, `filter-accordion.php`, `filter-taxonomy.php`, `filter-price.php`, `filter-active.php`
- Checkbox styling: custom `bg-black` checkmark, accessible labels
- Price slider: dual-handle, `price-slider.js`, currency formatted display
- Active filter chips: `filter-active.php` — dismissible with × button, `bg-neutral-100`
- Filter drawer: slide-in animation from left, `filter-drawer.js`, overlay
- "Apply Filters" CTA in drawer: `bg-primary text-black`
- AJAX connection: `ajax-filters.js` wires all inputs to `lenvy_ajax_filter_products`
- Results count update after filter application

**Deliverable:** Filters work correctly on desktop sidebar and mobile drawer.

---

### Phase 8: Cart and Checkout UI
**Goal:** Cart and checkout are clean and conversion-optimized.

- Create `woocommerce/cart/cart.php` — custom two-column layout
  - Left: cart item list using `product-card-mini.php`, quantity selectors, remove buttons
  - Right: order summary, coupon input, proceed to checkout CTA
- Create `woocommerce/checkout/form-checkout.php` — custom two-column layout
  - Left: billing/shipping fields (WC field callbacks with custom markup wrappers)
  - Right: order summary (sticky on desktop)
- Style WooCommerce form fields consistently with Input styles
- Coupon input: `outline` button variant + text input
- Payment section: WooCommerce payment gateways rendered within custom container
- "Place Order" button: `bg-primary text-black` full-width
- Trust signals below CTA: lock icon + payment method icons

**Deliverable:** Cart and checkout are functional and visually consistent.

---

### Phase 9: Account Pages UI
**Goal:** Account section feels part of the same design system.

- Create `woocommerce/myaccount/navigation.php` — sidebar nav partial
- Create `template-parts/account/dashboard-nav.php` — nav links with active state
- Create `template-parts/account/dashboard-welcome.php` — welcome + quick-link cards
- Refine `woocommerce/myaccount/form-login.php` — split login / register columns
- Refine `woocommerce/myaccount/dashboard.php` — uses dashboard-welcome partial
- Account section wrapper: two-column `grid grid-cols-[220px_1fr]` on desktop
- Sidebar nav active state: `bg-neutral-100 font-medium`
- Style WC account tables (orders list, address display) to match design system

**Deliverable:** Account section is seamlessly integrated with the rest of the theme.

---

### Phase 10: Homepage Assembly
**Goal:** Homepage is the brand's showpiece — editorial, premium, fast.

- Audit `template-parts/homepage/hero.php` — full-bleed image, Playfair heading, primary CTA
- Audit `template-parts/homepage/brand-scroller.php` — CSS marquee, grayscale logos
- Audit `template-parts/homepage/featured-categories.php` — portrait grid, hover effect
- Audit `template-parts/homepage/featured-products.php` — 4-col product row, section heading
- Audit `template-parts/homepage/promo-sections.php` — flexible ACF repeater, editorial blocks
- `front-page.php`: ensure section order, correct partial calls, ACF data flow
- Announcement bar: wire to ACF options field
- Performance: defer non-critical images, lazy-load below-fold sections
- Final `npm run build` — verify all assets in manifest

**Deliverable:** Homepage is launch-ready.

---

## 8. Quality Gates Per Phase

Before marking any phase complete:

1. `npm run build` succeeds without errors
2. No PHP warnings or notices in debug log
3. Component tested at mobile (375px), tablet (768px), and desktop (1280px)
4. Interaction states (hover, focus, active, disabled) are all implemented
5. AJAX-dependent components (filters, quick-add, mini-cart) tested with WooCommerce active
6. No WooCommerce default CSS classes applied to custom markup
7. All user-visible strings wrapped in `__('text', 'lenvy')`
8. All output correctly escaped

---

## 9. What Is Explicitly Out of Scope

- Page builder integration (Elementor, etc.) — not used
- WooCommerce Blocks — not used; classic shortcode/template approach only
- External CSS frameworks beyond Tailwind
- jQuery — all JS is vanilla
- Infinite scroll — pagination is standard, AJAX is filter-only
- Wishlist feature — deferred to post-launch
- Review system — disabled at WooCommerce level
