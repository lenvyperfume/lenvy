# Frontend Rebuild Plan

> Complete rebuild of all WooCommerce-facing templates.
> WooCommerce is treated as a **backend data engine only** — all markup is custom.

---

## Part 1 — Design Analysis: What Makes Premium Fragrance Sites Work

Reference sites: Byredo, Aesop, SSENSE, Nose Paris, Luckyscent.

### Core Principles Extracted

| Principle | How it manifests |
|---|---|
| **Restraint over decoration** | Fewer elements per screen. No borders, no card shadows, no background fills on cards. The product IS the design. |
| **Typography as hierarchy** | One serif for display/editorial, one sans for UI. Size jumps are dramatic (14px body → 48px+ headings). Letter-spacing separates meta from content. |
| **Whitespace as luxury signal** | Section gaps of 80–120px. Product card padding is generous. Grid gaps wider than typical ecommerce (32–48px). |
| **Image dominance** | Product images occupy 60–70% of card area. Portrait ratios (3:4 or 4:5) for bottles. Full-bleed hero images. |
| **Monochrome + one accent** | Black/white/neutral base. Single brand accent used sparingly (one button, one underline, one badge). |
| **Editorial product pages** | 55/45 or 60/40 image-to-content split. Sticky gallery. Content reads like a magazine, not a spec sheet. |
| **Invisible navigation** | Minimal top nav. Categories discoverable but not shouting. Filter UI tucked into drawers or minimal sidebars. |
| **Subtle interaction** | No bounce, no scale-up on hover. Opacity shifts, underline reveals, smooth color transitions. |

---

## Part 2 — New Design System

### 2.1 Typography Scale

Keep Inter (sans) + Playfair Display (serif). Introduce a disciplined type scale:

```
--text-xs:      0.75rem / 1rem      → 12px — badges, meta labels
--text-sm:      0.8125rem / 1.25rem → 13px — card meta, breadcrumbs, filter labels
--text-base:    0.875rem / 1.625rem → 14px — body copy, form inputs, nav links
--text-lg:      1rem / 1.5rem       → 16px — card titles, sidebar headings
--text-xl:      1.125rem / 1.5rem   → 18px — section subtitles
--text-2xl:     1.5rem / 1.875rem   → 24px — section headings
--text-3xl:     2rem / 2.25rem      → 32px — page titles
--text-4xl:     2.75rem / 3rem      → 44px — hero heading
--text-display: clamp(3rem, 6vw, 5rem) — homepage hero only
```

**Rules:**
- `font-serif italic` for: hero h1, section h2, product title on single page
- `font-sans` for: everything else (nav, cards, prices, meta, buttons, filters)
- `uppercase tracking-[0.08em]` for: brand name, category labels, badge text, breadcrumbs
- `tracking-[-0.01em]` for: large serif headings (tighten at scale)
- Body text color: `text-neutral-700` (not 900 — softer, more editorial)
- Meta/secondary text: `text-neutral-400`

### 2.2 Spacing System

Move from tight ecommerce spacing to editorial breathing room:

```
--space-section:  80px (mobile) / 120px (desktop)  → between major page sections
--space-block:    48px (mobile) / 64px (desktop)    → between content blocks within a section
--space-element:  24px (mobile) / 32px (desktop)    → between related elements
--space-tight:    12px / 16px                        → within component internals
```

**Grid gaps:**
- Product grid: `gap-x-6 gap-y-10` on mobile, `gap-x-8 gap-y-14` on desktop
- Card internal: 16px between image and text, 6px between text lines

### 2.3 Color System

No changes to tokens — but strict usage rules:

| Element | Color |
|---|---|
| Page background | `white` |
| Card background | `transparent` (no card backgrounds) |
| Primary text | `neutral-800` |
| Secondary text | `neutral-500` |
| Tertiary/meta text | `neutral-400` |
| Borders (when needed) | `neutral-200` (use sparingly) |
| Dividers | `neutral-100` |
| CTA button bg | `bg-primary` → hover: `bg-primary-hover` |
| CTA button text | `text-black` |
| Secondary button | `bg-black text-white` hover: `bg-neutral-800` |
| Outline button | `border border-neutral-300 text-neutral-700` hover: `border-neutral-900 text-neutral-900` |
| Sale badge | `bg-black text-white` |
| OOS overlay | `bg-white/80 text-neutral-500` |
| Active filter chip | `bg-neutral-100 text-neutral-700` |
| Hover states | opacity transitions, not color changes on cards |

**Anti-patterns (never do):**
- Card backgrounds or shadows
- Colored section backgrounds (except hero)
- Border-radius on product images
- Thick borders anywhere
- Background fills on product cards

### 2.4 Grid System

```
Product grid (shop/archive):
  Mobile:   grid-cols-2   gap-x-4  gap-y-8
  Tablet:   grid-cols-3   gap-x-6  gap-y-10
  Desktop:  grid-cols-3   gap-x-8  gap-y-14
  Wide:     grid-cols-4   gap-x-8  gap-y-14

Related products:
  Mobile:   grid-cols-2   gap-x-4  gap-y-8
  Desktop:  grid-cols-4   gap-x-8  gap-y-14
```

Container: `max-w-[1280px] mx-auto px-5 md:px-8` (wider padding than current `px-4`).

### 2.5 Layout Rules

| Page | Layout |
|---|---|
| Shop archive | Full-width grid. Filters in slide-out drawer (all breakpoints). No permanent sidebar. |
| Single product | Two-column: 58% gallery / 42% details. Sticky gallery on desktop. |
| Cart | Single column, max-w-3xl centered. Summary card as sidebar on desktop. |
| Checkout | Two-column: form (60%) / order summary (40%). |
| Account | Horizontal tab nav (not sidebar). Single column content. |

**Key change: Remove the permanent filter sidebar.** Premium fragrance sites don't show 280px of checkboxes on desktop. Filters go in a slide-out drawer triggered by a "Filter" button in the sort bar. This gives the product grid the full width and creates a cleaner, more editorial layout.

### 2.6 Image Treatment

```
Product card:   aspect-[3/4]  object-cover  bg-neutral-50
Product single: aspect-[3/4]  object-contain bg-neutral-50
Cart thumbnail: aspect-square object-cover  bg-neutral-50
```

- `bg-neutral-50` behind all product images (subtle warm background, makes white-bg product photos pop)
- No border-radius on product images
- Hover: `opacity-90` transition (subtle, not scale)
- Gallery thumbnails: 72×96px, same 3:4 ratio

### 2.7 Button System

Three tiers:

```
Primary CTA:     bg-primary text-black font-medium text-sm tracking-wide
                  h-12 px-8 hover:bg-primary-hover transition-colors
                  USAGE: Add to Cart, Apply Filters, Place Order

Secondary:       bg-black text-white font-medium text-sm tracking-wide
                  h-12 px-8 hover:bg-neutral-800 transition-colors
                  USAGE: Checkout, Account actions

Outline/Ghost:   border border-neutral-300 text-neutral-700 font-medium text-sm
                  h-11 px-6 hover:border-neutral-900 hover:text-neutral-900 transition-colors
                  USAGE: View details, Continue shopping, Clear filters

Text link:       text-neutral-500 text-sm underline-offset-4
                  hover:text-neutral-900 hover:underline transition-colors
                  USAGE: Inline actions, "View all", breadcrumbs
```

No border-radius on buttons. Square/sharp edges = premium.

### 2.8 Interaction Patterns

| Element | Hover | Active |
|---|---|---|
| Product card image | `opacity-90` (200ms ease) | — |
| Product card title | `text-neutral-900` (was 700) | — |
| Quick-add button | revealed below image on hover, slides up | `bg-primary-hover` |
| Nav link | underline reveal (border-bottom transition) | `text-neutral-900` |
| Filter checkbox | custom checkbox with `bg-primary` fill | — |
| Outline button | border darkens, text darkens | slight inset |

---

## Part 3 — Component Rebuild Specifications

### 3.1 Product Card (`template-parts/components/product-card.php`)

**Current:** Card with badge overlay, scale hover, translate-y quick-add.
**New:** Minimal, image-dominant, typography-driven.

```
┌─────────────────────────┐
│                         │
│    [3:4 image area]     │  ← bg-neutral-50, no border, no radius
│    aspect-[3/4]         │
│                         │
│  ┌───────────────────┐  │  ← quick-add row, revealed on hover
│  │   + Add to cart    │  │     h-10, bg-primary, text-black, text-xs
│  └───────────────────┘  │     tracking-widest, uppercase
├─────────────────────────┤  ← 16px gap
│ BRAND NAME              │  ← text-[11px] uppercase tracking-[0.1em] text-neutral-400
│ Product Title Goes Here │  ← text-sm font-medium text-neutral-800 line-clamp-2 mt-1
│ €89,00                  │  ← text-sm text-neutral-600 mt-1.5
│ €89,00  €119,00         │  ← sale: current + del (line-through text-neutral-400)
└─────────────────────────┘
```

**Badge logic:** Single badge, top-left of image area:
- OOS: `absolute top-3 left-3 text-[10px] uppercase tracking-widest text-neutral-400`
- Sale: `absolute top-3 left-3 bg-black text-white text-[10px] uppercase tracking-widest px-2 py-1`
- Custom ACF badge: same as sale styling

**Quick-add overlay:**
- Full-width bar at bottom of image container
- `absolute bottom-0 left-0 right-0`
- Hidden by default: `opacity-0 translate-y-2`
- On card hover: `opacity-100 translate-y-0` with `transition-all duration-200`
- For variable products: "Select options" text, links to product page

**Changes from current:**
- Remove `scale-[1.02]` image hover — replace with `opacity-90`
- Remove card borders/shadows
- Brand text smaller and more muted
- Price outside the card body area (tighter to title)
- Subtitle field removed from card (only show on single product page)

### 3.2 Shop Archive (`woocommerce/archive-product.php`)

**Current:** 280px sidebar + 3-col grid.
**New:** Full-width grid with drawer-based filters.

```
┌──────────────────────────────────────────────────────────┐
│ lenvy-container                                          │
│                                                          │
│ Home / Parfum                          ← breadcrumb, text-xs uppercase tracking-widest
│                                                          │
│ Parfum                                 ← h1, font-serif italic text-3xl
│                                                          │
│ ┌──────────────────────────────────────────────────────┐ │
│ │ Filter (3)    ·    128 producten    ·    Sorteer ▾  │ │ ← sort bar
│ └──────────────────────────────────────────────────────┘ │
│                                                          │
│ ┌─ active filter chips (if any) ─────────────────────┐  │
│ │ [Chanel ×]  [€50 – €100 ×]  [Wis alles]           │  │
│ └────────────────────────────────────────────────────┘  │
│                                                          │
│ ┌─────┐ ┌─────┐ ┌─────┐ ┌─────┐                        │
│ │     │ │     │ │     │ │     │   ← 4-col desktop grid  │
│ │     │ │     │ │     │ │     │      (3-col tablet,     │
│ │     │ │     │ │     │ │     │       2-col mobile)     │
│ ├─────┤ ├─────┤ ├─────┤ ├─────┤                        │
│ │text │ │text │ │text │ │text │                         │
│ └─────┘ └─────┘ └─────┘ └─────┘                        │
│                                                          │
│ [pagination]                                             │
│                                                          │
└──────────────────────────────────────────────────────────┘
```

**Sort bar redesign:**
- `flex items-center gap-4 py-4 border-b border-neutral-100`
- Left: "Filter" button with count badge (opens drawer) — `text-sm font-medium flex items-center gap-2`
- Center: result count — `text-sm text-neutral-400`
- Right: sort dropdown — `text-sm text-neutral-600` custom select

**Key change:** Grid goes to **4 columns** on desktop (≥1024px) because the sidebar is removed. This is the standard for full-width premium grids (Byredo, SSENSE).

**Taxonomy archives** (`taxonomy-product_cat.php`, `taxonomy-product_brand.php`) use the same grid layout. Brand archive keeps its banner header but the grid below is full-width.

### 3.3 Product Grid Component (`template-parts/components/product-grid.php`) — NEW

Extract the repeated grid markup into a reusable component:

```php
<?php
// Args: $products (WP_Query or array of IDs), $columns, $show_brand, $data_attrs
// Renders: <div class="grid ..." data-product-grid ...> loop </div>
```

Used by: `archive-product.php`, `taxonomy-product_cat.php`, `taxonomy-product_brand.php`, `single-product/related.php`, AJAX filter response.

Grid classes: `grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-8 md:gap-x-6 md:gap-y-10 lg:gap-x-8 lg:gap-y-14`

Data attributes preserved for AJAX: `data-product-grid`, `data-taxonomy`, `data-term`.

### 3.4 Single Product Page (`woocommerce/single-product.php`)

**Current:** 55/45 split, adequate structure.
**New:** Refined editorial layout with stronger visual hierarchy.

```
┌──────────────────────────────────────────────────────────────┐
│ Home / Parfum / Chanel                    ← breadcrumb       │
│                                                              │
│ ┌────────────────────────┐  ┌─────────────────────────────┐ │
│ │                        │  │ CHANEL                      │ │ ← brand, uppercase
│ │                        │  │                             │ │    tracking-widest
│ │                        │  │ Bleu de Chanel              │ │ ← h1 font-serif
│ │   [main product        │  │ Eau de Parfum               │ │    italic text-3xl
│ │    image, 3:4 ratio,   │  │                             │ │ ← subtitle, italic
│ │    object-contain,     │  │ €135,00                     │ │ ← text-2xl font-medium
│ │    bg-neutral-50]      │  │                             │ │
│ │                        │  │ [short description]         │ │ ← text-sm text-neutral-600
│ │                        │  │ prose, max 3 lines          │ │    leading-relaxed
│ │                        │  │                             │ │
│ │                        │  │ ─────────────────────────── │ │ ← thin divider
│ │                        │  │                             │ │
│ │                        │  │ Qty: [–] 1 [+]             │ │ ← minimal qty control
│ │                        │  │                             │ │
│ │                        │  │ ┌─────────────────────────┐│ │
│ │                        │  │ │    Add to cart           ││ │ ← full-width primary CTA
│ │                        │  │ └─────────────────────────┘│ │    h-14, bg-primary
│ │                        │  │                             │ │
│ │                        │  │ ─────────────────────────── │ │
│ │                        │  │                             │ │
│ │                        │  │ ▸ Geurprofiel              │ │ ← expandable accordion
│ │                        │  │ ▸ Gebruikstips             │ │    sections
│ │                        │  │ ▸ Product details           │ │
│ └────────────────────────┘  └─────────────────────────────┘ │
│ [thumbnails: 4 small]                                        │
│                                                              │
│ ──────────────────────────────────────────────────────────── │
│                                                              │
│ Gerelateerde producten          ← h2 font-serif italic      │
│                                                              │
│ ┌─────┐ ┌─────┐ ┌─────┐ ┌─────┐  ← 4-col product grid     │
│ │     │ │     │ │     │ │     │                              │
│ └─────┘ └─────┘ └─────┘ └─────┘                             │
└──────────────────────────────────────────────────────────────┘
```

**Layout:** `grid grid-cols-1 lg:grid-cols-[58fr_42fr] gap-8 lg:gap-16`

**Gallery changes:**
- Main image: `aspect-[3/4] bg-neutral-50 object-contain` (contain, not cover — show full bottle)
- Thumbnails: horizontal row below main image, `gap-3`, 72×96px each with 3:4 ratio
- Active thumb: `border-b-2 border-black` (not full black border)
- Gallery is `lg:sticky lg:top-[calc(var(--header-height)+2rem)]`

**Details column changes:**
- Remove WC rating stars (reviews already disabled)
- Scent notes and usage tips become **accordion sections** below ATC (not separate blocks)
- Product meta (SKU, categories) moves into an accordion section "Product details"
- Remove the WC tabs completely — all info lives in the right column via accordions
- Add thin `border-t border-neutral-100` dividers between logical groups

**Accordion pattern for details:**
- `border-t border-neutral-100 py-4`
- Toggle: `flex justify-between items-center w-full text-sm font-medium`
- Chevron rotates on open
- Content: `text-sm text-neutral-600 leading-relaxed pt-3 pb-1`

### 3.5 Cart Page (`woocommerce/cart/cart.php`)

**Current:** Two-column (flex-1 + w-80).
**New:** Centered single-column with sidebar summary on desktop.

```
┌─────────────────────────────────────────────────────────────┐
│ lenvy-container max-w-5xl                                   │
│                                                             │
│ Winkelwagen (3)                    ← h1 font-serif italic   │
│                                                             │
│ ┌─────────────────────────────────┐ ┌─────────────────────┐ │
│ │                                 │ │ Overzicht           │ │
│ │ ┌─────┬───────────────────────┐ │ │                     │ │
│ │ │     │ CHANEL                │ │ │ Subtotaal   €270,00 │ │
│ │ │ img │ Bleu de Chanel EDP    │ │ │ Verzending  Gratis  │ │
│ │ │96×  │ 100ml                 │ │ │ ─────────────────── │ │
│ │ │128  │                       │ │ │ Totaal      €270,00 │ │
│ │ │     │ [–] 1 [+]    €135,00 │ │ │                     │ │
│ │ └─────┴───────────────────────┘ │ │ ┌─────────────────┐ │ │
│ │ ───────────────────────────────── │ │ │   Afrekenen     │ │ │
│ │ ┌─────┬───────────────────────┐ │ │ └─────────────────┘ │ │
│ │ │     │ DIOR                  │ │ │                     │ │
│ │ │ img │ Sauvage EDT           │ │ │ Kortingscode        │ │
│ │ │     │ 50ml                  │ │ │ [________] Toepassen│ │
│ │ │     │ [–] 1 [+]    €89,00  │ │ └─────────────────────┘ │
│ │ └─────┴───────────────────────┘ │                         │
│ └─────────────────────────────────┘                         │
└─────────────────────────────────────────────────────────────┘
```

**Layout:** `grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-12 lg:gap-16`

**Cart item redesign:**
- Each item: `flex gap-5 py-6 border-b border-neutral-100`
- Thumbnail: `w-24 aspect-[3/4] bg-neutral-50 object-cover shrink-0`
- Brand above name: `text-[11px] uppercase tracking-[0.1em] text-neutral-400`
- Product name: `text-sm font-medium text-neutral-800`
- Variation data: `text-xs text-neutral-400`
- Qty control: inline `flex items-center gap-0 border border-neutral-200 h-9` with `-`/`+` buttons (24px wide)
- Price: `text-sm font-medium text-neutral-800 ml-auto`
- Remove: `text-neutral-300 hover:text-neutral-900` — small × icon, top-right of item

**Summary sidebar:**
- `lg:sticky lg:top-[calc(var(--header-height)+2rem)]`
- `p-6 bg-neutral-50`
- Checkout button: full-width `bg-black text-white h-12 font-medium text-sm tracking-wide`
- Coupon: collapsible section below, `text-sm`

### 3.6 Checkout Page (`woocommerce/checkout/form-checkout.php`)

**Current:** Two-column (flex-1 + w-80).
**New:** Wider, cleaner, editorial checkout.

```
┌─────────────────────────────────────────────────────────────┐
│ lenvy-container max-w-5xl                                   │
│                                                             │
│ Afrekenen                          ← h1 font-serif italic   │
│                                                             │
│ ┌─────────────────────────────────┐ ┌─────────────────────┐ │
│ │                                 │ │ Jouw bestelling     │ │
│ │ Contactgegevens                 │ │                     │ │
│ │ ┌─────────────────────────────┐ │ │ ┌────┬───────┬────┐│ │
│ │ │ E-mailadres                 │ │ │ │img │ Bleu  │€135││ │
│ │ └─────────────────────────────┘ │ │ ├────┼───────┼────┤│ │
│ │                                 │ │ │img │ Sauv  │€89 ││ │
│ │ Verzendadres                    │ │ └────┴───────┴────┘│ │
│ │ ┌────────────┐ ┌──────────────┐ │ │                     │ │
│ │ │ Voornaam   │ │ Achternaam   │ │ │ Subtotaal   €224,00│ │
│ │ └────────────┘ └──────────────┘ │ │ Verzending  Gratis │ │
│ │ ┌─────────────────────────────┐ │ │ ─────────────────── │ │
│ │ │ Adres                       │ │ │ Totaal      €224,00│ │
│ │ └─────────────────────────────┘ │ │                     │ │
│ │ ...                             │ │                     │ │
│ │                                 │ │                     │ │
│ │ Betaalmethode                   │ │                     │ │
│ │ ┌─────────────────────────────┐ │ │                     │ │
│ │ │ ○ iDEAL  ○ Creditcard      │ │ │                     │ │
│ │ └─────────────────────────────┘ │ │                     │ │
│ │                                 │ │                     │ │
│ │ ┌─────────────────────────────┐ │ │                     │ │
│ │ │     Bestelling plaatsen     │ │ │                     │ │
│ │ └─────────────────────────────┘ │ │                     │ │
│ └─────────────────────────────────┘ └─────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

**Layout:** `grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-12 lg:gap-16`

**Form field redesign:**
- Labels: `text-xs uppercase tracking-[0.06em] text-neutral-500 mb-2`
- Inputs: `w-full h-11 px-4 border border-neutral-200 text-sm focus:border-neutral-900 transition-colors` — no border-radius
- Section headings: `text-lg font-medium text-neutral-800 mb-6 mt-10 first:mt-0`

**Order summary (right column):**
- `lg:sticky lg:top-[calc(var(--header-height)+2rem)]`
- `p-6 bg-neutral-50`
- Compact product list: 48×64 thumbnails, name, qty × price
- Place order button: full-width `bg-primary text-black h-14 font-medium text-sm tracking-wide`

### 3.7 Account Dashboard (`woocommerce/myaccount/dashboard.php`)

**Current:** Sidebar nav + 2×2 card grid.
**New:** Horizontal nav + clean single-column dashboard.

```
┌──────────────────────────────────────────────────────────────┐
│ lenvy-container max-w-4xl                                    │
│                                                              │
│ Mijn account                       ← h1 font-serif italic   │
│                                                              │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ Dashboard  Bestellingen  Adressen  Gegevens  Uitloggen  │ │ ← horizontal tab nav
│ └──────────────────────────────────────────────────────────┘ │
│                                                              │
│ Welkom terug, Burak                ← greeting                │
│                                                              │
│ ┌────────────────────┐ ┌────────────────────┐               │
│ │ 📦 3               │ │ 📍 2               │               │
│ │ Bestellingen       │ │ Adressen           │               │
│ │ Bekijk →           │ │ Beheer →           │               │
│ └────────────────────┘ └────────────────────┘               │
│ ┌────────────────────┐ ┌────────────────────┐               │
│ │ 👤 Account         │ │ Laatste bestelling │               │
│ │ gegevens           │ │ #1234 · €135,00   │               │
│ │ Bewerk →           │ │ Bekijk →           │               │
│ └────────────────────┘ └────────────────────┘               │
└──────────────────────────────────────────────────────────────┘
```

**Navigation redesign:**
- `my-account.php`: Remove sidebar layout. Use full-width with horizontal nav.
- Nav: `flex gap-8 border-b border-neutral-200 mb-10`
- Each link: `pb-3 text-sm text-neutral-500 hover:text-neutral-900 transition-colors`
- Active: `text-neutral-900 border-b-2 border-black`
- Logout: `ml-auto text-neutral-400 hover:text-red-500`

**Dashboard cards:**
- `grid grid-cols-2 gap-4`
- Each: `p-6 border border-neutral-200 hover:border-neutral-900 transition-colors group`
- Number/stat: `text-2xl font-medium text-neutral-900`
- Label: `text-sm text-neutral-500 mt-1`
- Link: `text-xs uppercase tracking-widest text-neutral-400 group-hover:text-neutral-900 mt-4 flex items-center gap-1`

---

## Part 4 — Implementation Order

### Phase 1: Foundation (prerequisites)

1. **Update design tokens** — Add new text sizes to `tailwind.css` if needed. No breaking changes.
2. **Create product-grid component** — `template-parts/components/product-grid.php`. Shared grid wrapper.
3. **Update button component** — `template-parts/components/button.php` to match new button system.

### Phase 2: Product Card

4. **Rebuild product-card.php** — Complete rewrite. This is the atomic unit that every other template depends on.
5. **Update product-card-mini.php** — Align with new typography/spacing system.
6. **Update `_woocommerce.scss`** — Remove old card styles, add new `.lenvy-card-price` rules.

### Phase 3: Shop Archive

7. **Rebuild archive-product.php** — Full-width grid, drawer-only filters, new sort bar.
8. **Update sort-bar.php** — New layout with filter button prominent.
9. **Update filter-drawer.php** — Refine styling to match new system (this becomes the only filter UI).
10. **Update filter-sidebar.php** — Convert to a presentation wrapper that the drawer uses internally (or deprecate if drawer is self-contained).
11. **Update filter components** — `filter-taxonomy.php`, `filter-price.php`, `filter-active.php`, `filter-accordion.php` — typography and spacing refinements.
12. **Update taxonomy archives** — `taxonomy-product_cat.php`, `taxonomy-product_brand.php` — adopt same full-width grid layout.

### Phase 4: Single Product

13. **Rebuild single-product.php** — New editorial layout, accordion details, no tabs.
14. **Update product-image.php** — Contain (not cover), refined thumbnail strip.
15. **Update related.php** — Use new product-grid component, 4-col layout.
16. **Clean up `_woocommerce.scss`** — Remove old tab styles, update ATC form styles, update product meta styles.

### Phase 5: Cart

17. **Rebuild cart.php** — New item layout, centered summary.
18. **Update cart-empty.php** — Minimal empty state with "Continue shopping" CTA.
19. **Update `_woocommerce.scss`** — Cart totals, cart item styles.

### Phase 6: Checkout

20. **Rebuild form-checkout.php** — New form field styles, editorial layout.
21. **Update `_woocommerce.scss`** — Checkout form fields, payment section, place order button.

### Phase 7: Account

22. **Rebuild my-account.php** — Horizontal nav, full-width content.
23. **Rebuild navigation.php** — Horizontal tab pattern.
24. **Rebuild dashboard.php** — Stat cards, quick links.
25. **Update my-address.php** — Align with new spacing/typography.
26. **Update form-login.php** — Clean, centered form.

### Phase 8: Polish

27. **SCSS cleanup** — Remove all dead styles from `_woocommerce.scss` and `_components.scss`.
28. **AJAX handler update** — Ensure `lenvy_ajax_filter_products` returns markup matching new grid/card structure.
29. **JS module updates** — Adjust `ajax-filters.js`, `quick-add.js` selectors if data attributes changed.
30. **Build and test** — `npm run build`, cross-browser QA.

---

## Part 5 — Critical Rules During Implementation

1. **WooCommerce functions are tools, not templates.** Use `wc_get_product()`, `$product->get_price_html()`, `WC()->cart->get_cart()`, etc. but write 100% custom markup.

2. **Preserve all AJAX data attributes.** `data-product-grid`, `data-taxonomy`, `data-term`, `data-sort-select`, `data-results-count`, `data-active-filters`, `data-filter-form`, `data-filter-drawer`, `data-gallery-main`, `data-gallery-thumb` — JS modules depend on these.

3. **Preserve WC hooks where needed.** Cart and checkout require certain hooks to fire (`woocommerce_before_cart`, `woocommerce_cart_contents`, `woocommerce_checkout_billing`, `#order_review`, etc.) for payment gateways and plugins to inject their markup.

4. **All output escaped.** Every echo goes through `esc_html()`, `esc_url()`, `esc_attr()`, or `wp_kses_post()`.

5. **No new inc/ files.** All helper changes go in `inc/helpers.php`. All WC hook changes go in `inc/woocommerce.php`.

6. **Class naming:** `lenvy-*` prefix for any custom class that needs SCSS styling. Tailwind utilities for everything else.

7. **No border-radius** on product images, cards, or primary buttons. Square = premium.

8. **Test AJAX filters after Phase 3.** The filter system is the most fragile — the returned HTML must match the new grid structure exactly.
