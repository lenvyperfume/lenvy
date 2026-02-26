# Premium Modern Luxury Redesign Plan

## Step 1 — Why the Current Design Feels Generic

### 1.1 Everything Lives Inside a 1280px Box

Every section — hero excluded — is wrapped in `.lenvy-container` at `max-width: 1280px`. This creates a narrow corridor of content flanked by dead margins on wide screens. Premium retail sites let key sections breathe edge-to-edge (or close to it), using inner padding rather than a centered box. The current layout reads as "template with a max-width" rather than "designed retail environment."

### 1.2 No Typographic Event Exists on the Page

Every section heading uses the identical pattern: `text-xs font-medium uppercase tracking-widest text-neutral-500` with a small lavender accent line. This creates absolute visual monotony. There is no moment where the eye lands on something dramatic — no large serif statement, no editorial pause, no brand voice in the typography. Luxury sites create at least one typographic event per page that says "this is a brand, not a template."

### 1.3 Product Cards Are Conversion-Optimized, Not Brand-Forward

The full-width lavender quick-add bar that slides up on hover screams "add to cart now." This is appropriate for fast-fashion or marketplace sites. For a premium perfume store, the product card should be quieter — the image is the experience, and the purchase action should be available but not dominant. The `opacity-90` image hover, uppercase badge text, and dense text hierarchy all contribute to an ecommerce-first, brand-second impression.

### 1.4 Cold Neutral Palette

Pure black `#0a0a0a` and pure white `#ffffff` create clinical contrast. The neutral scale (Tailwind's default `neutral`) is cool-zinc-toned. Luxury fragrance brands use warmer, earthier neutrals — softer blacks, warm off-whites, stone-toned grays.

### 1.5 Tight Spacing

Section padding (`py-10 lg:py-14` = 40/56px) stacks content too tightly. Grid gaps (`gap-x-4 gap-y-8`) crowd products together. The card image-to-text transition (`pt-4`) is compressed. There's no room for the eye to rest between sections.

### 1.6 Inconsistent Transition Timing

Durations across the site: 150ms, 200ms, 250ms, 300ms, 500ms, 700ms. Luxury interfaces use 1–2 consistent durations that create a recognizable motion rhythm.

---

## Step 2 — Design System Redefinition

### 2.1 Layout Architecture: Container vs Full-Width

**This is the most important structural change.** The theme needs two distinct layout modes:

#### Standard container (`.lenvy-container`)

Centered, max-width, boxed. Used for: text-heavy content, forms, breadcrumbs, footer grid.

```
max-width: 1440px
padding-inline: 1.5rem (mobile) / 2.5rem (md) / 3rem (lg+)
margin-inline: auto
```

#### Full-width section wrapper (`.lenvy-section`)

No max-width. Content spans the viewport width with generous edge padding. Used for: product grids, carousels, category grids, editorial moments.

```
width: 100%
padding-inline: 1.5rem (mobile) / 2.5rem (md) / 4rem (lg) / clamp(4rem, 5vw, 6rem) (xl)
```

At 1920px: content area ~1800px. At 1440px: content area ~1340px. At 1280px: content area ~1180px. The layout scales with the viewport instead of hitting a wall at a max-width.

#### Edge-to-edge (no wrapper)

Zero padding, full bleed. Used for: hero, promo banners, brand scroller.

#### Which sections use which wrapper:

| Section | Current | New |
|---------|---------|-----|
| **Hero** | Edge-to-edge | Edge-to-edge (keep) |
| **Brand scroller** | `.lenvy-container` | Edge-to-edge (marquee is already full-width visually) |
| **Product carousels** (home) | `.lenvy-container` | **`.lenvy-section`** — track scrolls to edges |
| **Featured categories** (home) | `.lenvy-container` | **`.lenvy-section`** — grid spans wider |
| **Editorial brand moment** (home, new) | _doesn't exist_ | `.lenvy-container` with `max-w-3xl mx-auto` |
| **Promo banners** (home) | Edge-to-edge | Edge-to-edge (keep) |
| **Shop product grid** | `.lenvy-container` | **`.lenvy-section`** — grid expands on wide screens |
| **Shop sort bar + filters** | `.lenvy-container` | `.lenvy-container` (keep — this is UI chrome, not product display) |
| **Single product layout** | `.lenvy-container` | `.lenvy-container` (keep — editorial 2-col needs centering) |
| **Related products** | `.lenvy-container` | **`.lenvy-section`** — matches homepage product display |
| **Header** | `.lenvy-container` | `.lenvy-container` (keep — header needs consistent edges) |
| **Footer** | `.lenvy-container` | `.lenvy-container` (keep — footer is informational) |

#### Implementation in SCSS:

```scss
// _components.scss
.lenvy-section {
  width: 100%;
  padding-inline: 1.5rem;

  @media (min-width: 768px) {
    padding-inline: 2.5rem;
  }

  @media (min-width: 1024px) {
    padding-inline: 4rem;
  }

  @media (min-width: 1280px) {
    padding-inline: clamp(4rem, 5vw, 6rem);
  }
}
```

### 2.2 Typography Scale

**Principle**: Dramatic scale contrast. Large type commands, small type serves.

| Token | Current | New | Usage |
|-------|---------|-----|-------|
| `--text-display` | `clamp(3rem, 6vw, 5rem)` | `clamp(3rem, 5.5vw, 4.5rem)` | Editorial brand moment, promo banners |
| Section heading (h2) | `text-xs uppercase` (12px) | `text-2xl md:text-3xl font-serif italic` | Homepage sections, archive titles |
| Section label (eyebrow) | _not used_ | `text-[11px] uppercase tracking-widest text-neutral-400` | Above the serif heading — "Bestsellers", "Shop by Category" |
| Product title (card) | `text-sm font-medium` (14px) | `text-[13px]` (no font-medium) | Grid cards — quieter, more refined |
| Product title (single) | `text-2xl md:text-3xl` | `text-3xl lg:text-4xl` | Single product page — commanding |
| Brand label (card) | `text-[11px] uppercase tracking-[0.1em]` | `text-[11px] uppercase tracking-[0.12em]` | Keep, refine tracking |
| Price (card) | _inherits from WC_ | `text-[13px] text-neutral-500` | Lighter than title — not competing |
| Body copy | `text-sm` everywhere | `text-sm` for UI, `text-base` for editorial blocks | Contextual sizing |
| Nav links | `text-sm tracking-[0.02em]` | `text-[13px] tracking-[0.02em]` | Slightly smaller, lighter |

**Two-tier section header pattern:**
```
CURRENT:
<h2 class="flex items-center gap-3 text-xs font-medium uppercase tracking-widest text-neutral-500">
  <span class="inline-block w-6 h-0.5 bg-primary" aria-hidden="true"></span>
  Bestsellers
</h2>

NEW:
<div class="mb-10 lg:mb-14">
  <p class="text-[11px] uppercase tracking-widest text-neutral-400 mb-3">
    Bestsellers
  </p>
  <h2 class="text-2xl md:text-3xl font-serif italic text-neutral-900 leading-tight">
    Meest Geliefd
  </h2>
</div>
```

The lavender accent line is removed. The serif italic heading becomes the visual event. The uppercase label becomes a quiet eyebrow.

### 2.3 Color Refinement: Warm Neutral Shift

**Principle**: Soften extremes, warm the midtones. The primary lavender stays untouched.

| Token | Current | New | Note |
|-------|---------|-----|------|
| `--color-black` | `#0a0a0a` | `#111111` | Softer dark |
| `--color-white` | `#ffffff` | `#ffffff` | Keep pure for cards/modals |
| Body background | `#ffffff` (via body bg) | `#fafaf8` | Warm off-white page canvas (direct CSS, not a token) |
| `--color-neutral-50` | `#fafafa` | `#f7f7f5` | Warm tint |
| `--color-neutral-100` | `#f5f5f5` | `#f0f0ee` | Warm tint |
| `--color-neutral-200` | `#e5e5e5` | `#e4e3e0` | Warm tint |
| `--color-neutral-300` | `#d4d4d4` | `#d2d1ce` | Warm tint |
| `--color-neutral-400` | `#a3a3a3` | `#a1a09c` | Warm tint |
| `--color-neutral-500` | `#737373` | `#72716e` | Warm tint |
| `--color-neutral-600` | `#525252` | `#525150` | Subtle warm |
| `--color-neutral-700` | `#404040` | `#403f3e` | Subtle warm |
| `--color-neutral-800` | `#262626` | `#282725` | Subtle warm |
| `--color-neutral-900` | `#171717` | `#1a1918` | Warm dark |
| `--color-neutral-950` | `#0a0a0a` | `#111111` | Match black token |
| `--color-primary` | `#e1c4ff` | `#e1c4ff` | **NO CHANGE** |
| `--color-primary-hover` | `#cfb3f0` | `#cfb3f0` | **NO CHANGE** |

### 2.4 Section Spacing Rules

| Context | Current | New |
|---------|---------|-----|
| Homepage section vertical padding | `py-10 lg:py-14` (40/56px) | `py-16 lg:py-24` (64/96px) |
| Editorial brand moment padding | _n/a_ | `py-20 lg:py-32` (80/128px) |
| Section header bottom margin | `mb-8 lg:mb-12` | `mb-10 lg:mb-14` |
| Product grid `gap-y` | `gap-y-8 md:gap-y-10 lg:gap-y-14` | `gap-y-10 md:gap-y-14 lg:gap-y-16` |
| Product grid `gap-x` | `gap-x-4 md:gap-x-6 lg:gap-x-8` | `gap-x-5 md:gap-x-8 lg:gap-x-10` |
| Card image-to-text gap | `pt-4` | `pt-5` |
| Shop page padding | `py-10 lg:py-16` | `py-12 lg:py-20` |
| Single product padding | `py-10 lg:py-16` | `py-12 lg:py-20` |
| Related products separation | `mt-20 pt-12` | `mt-24 lg:mt-32 pt-14` |
| Footer padding | `py-14` | `py-16 lg:py-20` |

### 2.5 Product Card Philosophy: Quiet Confidence

**Core principle**: The image is the experience. Everything else is supporting information. Purchase actions are available but never dominate.

#### Image Layer
- **Background**: keep `bg-neutral-50` (now warm-tinted via token shift)
- **Hover**: remove `group-hover:opacity-90`. Replace with `group-hover:scale-[1.03]` + `transition-transform duration-500 ease-out` — subtle tactile zoom
- **Overflow**: already `overflow-hidden` on container — clips the scale animation cleanly

#### Quick-Add Interaction (major change)
**Current**: Full-width lavender bar spanning image bottom, uppercase text, slides up on hover.
**New**: Small circle icon button (cart icon) positioned at bottom-right of image. Appears on hover with a fade.

```html
<!-- Replaces the full-width bar -->
<button
  type="button"
  class="absolute bottom-3 right-3 z-10 w-10 h-10 flex items-center justify-center
         bg-white text-neutral-800 rounded-full shadow-sm
         opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0
         transition-all duration-200 hover:bg-primary hover:text-black"
  data-quick-add
>
  <!-- cart icon SVG -->
</button>
```

- Smaller visual footprint — doesn't obscure the product image
- White background with shadow reads as a floating action, not a banner CTA
- Hover on the button itself transitions to lavender — the brand color appears as a reward, not a demand
- For variable products ("Opties bekijken"): same circle button but with an arrow-right icon, linking to PDP

#### Badge
- **Sale**: keep `bg-black text-white` but reduce size: `text-[9px] px-2 py-0.5` — more discreet
- **Nieuw/Custom**: keep `bg-primary text-black` but same size reduction
- **Position**: `top-3 left-3` → `top-4 left-4` — pulled slightly inward, less "stuck on"
- **OOS overlay**: `bg-white/60` → `bg-white/40` — lighter, less heavy-handed

#### Text Area
- **Brand**: `text-[11px] uppercase tracking-[0.12em] text-neutral-400` — quiet anchor
- **Product name**: `text-[13px] text-neutral-800 leading-snug line-clamp-1` — no `font-medium`. Let the typeface weight do the work naturally. Single line clamp for cleaner cards.
- **Price**: `text-[13px] text-neutral-500 mt-2` — deliberately lighter than the product name. Price is information, not the headline. No `font-medium`.
- **Hover on title link**: `hover:text-black` — subtle deepening

The overall effect: Brand (whisper) → Product (statement) → Price (fact). Quiet hierarchy, not a conversion funnel.

### 2.6 Transition Standardization

| Duration | Easing | Usage |
|----------|--------|-------|
| `200ms` | `ease` | Hover states, color transitions, opacity, button interactions |
| `500ms` | `cubic-bezier(0.4, 0, 0.2, 1)` | Transform animations: image scale, drawer slide, section reveals |

All current `150ms` and `300ms` instances → `200ms`. All `700ms` → `500ms`.

SCSS variables update:
```scss
$transition-base: 200ms ease;
$transition-slow: 500ms cubic-bezier(0.4, 0, 0.2, 1);
```

---

## Step 3 — Page-by-Page Redesign

### 3.1 Header

**Current**: 3-column grid, 68px, `border-b border-neutral-100`.

| Change | Current | New |
|--------|---------|-----|
| Height | `68px` / `h-[68px]` | `72px` / `h-[72px]` |
| Border | `border-neutral-100` | `border-neutral-200` — more confident line |
| Logo fallback | `text-xl` | `text-2xl` |
| Nav gap | `gap-7` | `gap-8` |
| Nav link weight | inherits `font-medium` from parent | explicit `font-normal` |
| Action icon gap | `gap-0.5 sm:gap-1` | `gap-1 sm:gap-2` |
| All transitions | mixed `150ms`/`200ms` | `200ms` |

**No structural change.** The 3-column grid layout and sticky behavior are correct. The container wrapper stays — the header needs consistent edge alignment.

**Files**: `template-parts/header/site-header.php`, `template-parts/header/nav-primary.php`, `_variables.scss`, `_base.scss`

### 3.2 Homepage

**New section order and wrapper assignments:**

```
1. Hero                    → edge-to-edge (keep)
2. Brand scroller          → edge-to-edge (keep)
3. Bestsellers carousel    → .lenvy-section (full-width)
4. Editorial brand moment  → .lenvy-container + max-w-3xl (NEW SECTION)
5. Featured categories     → .lenvy-section (full-width)
6. New Arrivals carousel   → .lenvy-section (full-width), bg-neutral-50 alternating
7. Promo banner            → edge-to-edge (keep)
8. Sale carousel           → .lenvy-section (full-width), conditional
```

#### Hero
- Height: `clamp(360px, 62vh, 720px)` → `clamp(400px, 70vh, 800px)` — taller, more cinematic
- Vignette: `from-neutral-950/30` → `from-neutral-950/20` — lighter
- No text overlay — keep this editorial restraint

#### Editorial Brand Moment (new section)

**This is the single most important addition.** A typographic pause that establishes brand identity. Placed between the bestsellers carousel and the category grid — the viewer has seen products, now they see the brand voice.

ACF fields (new field group on options page):
- `lenvy_editorial_heading` — text
- `lenvy_editorial_subheading` — textarea
- `lenvy_editorial_cta_label` — text (optional)
- `lenvy_editorial_cta_url` — url (optional)

Template structure:
```html
<section class="py-20 lg:py-32">
  <div class="lenvy-container">
    <div class="max-w-3xl mx-auto text-center">

      <h2
        class="font-serif italic text-neutral-900 leading-[1.1]"
        style="font-size: var(--text-display);"
      >
        De Kunst van Geur
      </h2>

      <p class="mt-6 lg:mt-8 text-base lg:text-lg text-neutral-500 leading-relaxed max-w-xl mx-auto">
        Zorgvuldig samengestelde parfums van 's werelds
        meest gewaardeerde huizen — voor hem, voor haar,
        voor iedereen die schoonheid waardeert.
      </p>

      <!-- Optional CTA -->
      <a
        href="/shop/"
        class="inline-flex items-center gap-2 mt-8 lg:mt-10
               text-[13px] font-medium text-neutral-800
               border-b border-neutral-300 pb-1
               hover:border-neutral-800 hover:text-black
               transition-colors duration-200"
      >
        Ontdek de Collectie →
      </a>

    </div>
  </div>
</section>
```

**Why this works:**
- `--text-display` at `clamp(3rem, 5.5vw, 4.5rem)` creates the largest type on the page — a true visual event
- `max-w-3xl` (48rem) constrains the heading to a comfortable measure
- `max-w-xl` (36rem) constrains the body text tighter — classic editorial proportion
- `py-20 lg:py-32` (80/128px) gives it dramatically more breathing room than any other section
- The CTA is not a button — it's an underlined text link. Calm, not pushy.
- Serif italic + generous whitespace = this section alone elevates the entire page from "shop" to "brand"

**Files**: new `template-parts/homepage/editorial-moment.php`, `front-page.php` (add section), `inc/acf.php` (register fields)

#### Product Carousels (Bestsellers, New, Sale)
- **Wrapper**: `.lenvy-container` → **`.lenvy-section`** — carousel now spans wider
- **Section header**: two-tier (eyebrow label + serif heading)
- **Carousel track**: already CSS-based horizontal scroll. With `.lenvy-section` padding, the first card aligns with the section heading, and the track scrolls to the viewport edge.
- **Section padding**: `py-10 lg:py-14` → `py-16 lg:py-24`
- **Carousel arrows**: border `neutral-200` → `neutral-100` — quieter

#### Featured Categories
- **Wrapper**: `.lenvy-container` → **`.lenvy-section`**
- **Section header**: two-tier conversion
- **Image hover scale**: `scale-105` → `scale-[1.03]` with `duration-500`
- **Grid gaps**: `gap-3 md:gap-4` → `gap-4 md:gap-5`
- **Product count text**: `text-white/55` → `text-white/50`

#### Alternating Backgrounds
- Bestsellers: page background (`#fafaf8`)
- Editorial moment: page background
- Featured categories: page background
- New Arrivals: `bg-neutral-50` (warm-tinted) — creates a visual band
- Sale: page background

**Files**: `front-page.php`, `template-parts/homepage/hero.php`, `template-parts/homepage/product-carousel.php`, `template-parts/homepage/featured-categories.php`

### 3.3 Shop Page

**Current**: Everything inside `.lenvy-container`. Sort bar, filters, grid, pagination — all boxed at 1280px.

**New structure:**
```
<main>
  <div class="lenvy-container">           ← boxed: breadcrumb + title + sort bar
    breadcrumb
    h1
    sort-bar
    active-filters
  </div>

  <div class="lenvy-section mt-10">       ← FULL-WIDTH: product grid
    product-grid
  </div>

  <div class="lenvy-container">           ← boxed: pagination
    pagination
  </div>
</main>
```

The product grid breaks out of the standard container, spanning wider on desktop screens. The UI chrome (sort, filters, breadcrumbs, pagination) stays in the narrower container — these are functional tools, not the product experience.

| Change | Current | New |
|--------|---------|-----|
| Product grid wrapper | `.lenvy-container` | **`.lenvy-section`** |
| Page padding | `py-10 lg:py-16` | `py-12 lg:py-20` |
| Title | `text-3xl font-serif italic` | `text-3xl lg:text-4xl font-serif italic` |
| Title margin | `mt-2 mb-8` | `mt-3 mb-10` |
| Grid top margin | `mt-8` | `mt-10` |
| Grid gaps | see 2.4 | wider gaps from new spacing rules |

**Files**: `woocommerce/archive-product.php`, `template-parts/components/product-grid.php`, `template-parts/shop/sort-bar.php`

### 3.4 Product Grid Component

| Change | Current | New |
|--------|---------|-----|
| Gaps | `gap-x-4 gap-y-8 md:gap-x-6 md:gap-y-10 lg:gap-x-8 lg:gap-y-14` | `gap-x-5 gap-y-10 md:gap-x-8 md:gap-y-14 lg:gap-x-10 lg:gap-y-16` |
| Columns | `grid-cols-2 md:grid-cols-3 lg:grid-cols-4` | keep (wider wrapper makes each card bigger) |

At 1920px with `.lenvy-section` (5vw padding = ~96px each side), the grid area is ~1728px. With 4 columns and 40px gaps, each card is ~402px wide. At 1280px: each card is ~255px. Significant improvement from current ~270px at 1280px container.

**Files**: `template-parts/components/product-grid.php`

### 3.5 Product Card

Full changes per section 2.5. Summary of file changes:

**`template-parts/components/product-card.php`:**
- Image: remove `group-hover:opacity-90`, add `group-hover:scale-[1.03] transition-transform duration-500 ease-out`
- Quick-add: full-width lavender bar → circular icon button (bottom-right, `w-10 h-10`, white bg, cart icon, hover lavender)
- Variable product link: same circular button, arrow-right icon
- Badge position: `top-3 left-3` → `top-4 left-4`
- OOS overlay: `bg-white/60` → `bg-white/40`
- Text `pt-4` → `pt-5`
- Title: `text-sm font-medium` → `text-[13px]`, `line-clamp-2` → `line-clamp-1`
- Title hover: `hover:text-neutral-900` → `hover:text-black`
- Price: `mt-1.5` → `mt-2`, add `text-[13px] text-neutral-500`

**`template-parts/components/badge.php`:**
- Sale variant: `px-2.5 py-1` → `px-2 py-0.5`
- New variant: same size reduction
- Font: `text-[10px]` → `text-[9px]`

**Files**: `template-parts/components/product-card.php`, `template-parts/components/badge.php`

### 3.6 Single Product Page

**Stays in `.lenvy-container`** — the editorial two-column layout benefits from centering. The wider 1440px container gives it more room naturally.

| Change | Current | New |
|--------|---------|-----|
| Grid columns | `[58fr_42fr]` | `[55fr_45fr]` — more room for details |
| Grid gap | `gap-8 lg:gap-16` | `gap-10 lg:gap-20` |
| Page padding | `py-10 lg:py-16` | `py-12 lg:py-20` |
| Title | `text-2xl md:text-3xl` | `text-3xl lg:text-4xl` |
| Brand link tracking | `tracking-[0.1em]` | `tracking-[0.14em]` |
| Brand margin | `mb-3` | `mb-4` |
| Price | `mt-5 text-xl` | `mt-6 text-2xl` |
| Accordion toggle padding | `py-4` | `py-5` |
| Accordion label | `text-sm font-medium text-neutral-800` | `text-[13px] font-medium text-neutral-700` |
| Thumbnail size | `w-[72px]` | `w-20` (80px) |
| Thumbnail gap | `gap-3 mt-4` | `gap-3 mt-5` |
| Active thumb border | `border-black` | `border-neutral-900` |

**Related products section:**
- Wrapper: `.lenvy-container` → **`.lenvy-section`** — matches homepage carousel width
- Separation: `mt-20 pt-12` → `mt-24 lg:mt-32 pt-14`
- Heading: `text-2xl` → `text-2xl md:text-3xl font-serif italic`

**Files**: `woocommerce/single-product.php`, `woocommerce/single-product/product-image.php`, `woocommerce/single-product/related.php`

### 3.7 Footer

| Change | Current | New |
|--------|---------|-----|
| Padding | `py-14` | `py-16 lg:py-20` |
| Column headings | `text-xs font-medium uppercase tracking-widest text-neutral-500` | `text-[11px] font-semibold uppercase tracking-widest text-neutral-400` |
| Grid gap | `gap-10 lg:gap-6` | `gap-10 lg:gap-8` |
| Bottom bar padding | `py-6` | `py-8` |
| All transitions | mixed | `200ms` |

**Files**: `template-parts/footer/site-footer.php`

---

## Implementation File Map

### Foundation layer (change once, cascades everywhere)

| File | Changes |
|------|---------|
| `resources/css/tailwind.css` | Neutral scale (11 values), `--color-black`, `--text-display` |
| `resources/scss/_variables.scss` | Mirror tokens, `$container-max: 1440px`, `$header-height: 72px`, transition durations |
| `resources/scss/_base.scss` | Body bg `#fafaf8`, `--header-height: 72px`, `--container-width: 1440px` |
| `resources/scss/_components.scss` | `.lenvy-container` padding update, new `.lenvy-section` class, `.lenvy-container-narrow`, transition utilities |

### Template layer

| File | Changes |
|------|---------|
| `template-parts/header/site-header.php` | Height `72px`, icon gap, logo size |
| `template-parts/header/nav-primary.php` | Nav gap, link font-weight |
| `front-page.php` | Section order (insert editorial moment), wrapper changes |
| `template-parts/homepage/hero.php` | Height increase, vignette softening |
| `template-parts/homepage/product-carousel.php` | **`.lenvy-section`** wrapper, two-tier header, spacing |
| `template-parts/homepage/featured-categories.php` | **`.lenvy-section`** wrapper, two-tier header, hover scale, gaps |
| **`template-parts/homepage/editorial-moment.php`** | **NEW FILE** — brand typography section |
| `template-parts/components/product-card.php` | Image hover, quick-add circle button, text sizing, spacing |
| `template-parts/components/product-grid.php` | Grid gap values |
| `template-parts/components/badge.php` | Size reduction |
| `woocommerce/archive-product.php` | Split container/full-width zones, padding, title sizing |
| `woocommerce/single-product.php` | Grid ratio, spacing, title/price sizing |
| `woocommerce/single-product/product-image.php` | Thumbnail size, gallery spacing |
| `woocommerce/single-product/related.php` | **`.lenvy-section`** wrapper, spacing, heading |
| `template-parts/shop/sort-bar.php` | Transition duration |
| `template-parts/footer/site-footer.php` | Padding, heading style, gap |

### ACF / PHP

| File | Changes |
|------|---------|
| `inc/acf.php` | Register editorial moment fields on options page |
| `acf-json/` | New field group JSON |

### SCSS

| File | Changes |
|------|---------|
| `resources/scss/_woocommerce.scss` | Transition durations → `200ms`, button sizing alignment |

---

## Implementation Order

**Phase A — Foundation tokens** (everything downstream depends on this):
1. `tailwind.css` — neutral scale + black + display size
2. `_variables.scss` — mirror + container + header + transitions
3. `_base.scss` — body bg, CSS vars
4. `_components.scss` — container padding + `.lenvy-section` + transition utilities

**Phase B — Product card** (core reusable component):
5. `product-card.php` — image hover, quick-add, text sizing
6. `badge.php` — size reduction
7. `product-grid.php` — gap values

**Phase C — Homepage** (highest-visibility page):
8. `front-page.php` — section order + wrappers
9. `editorial-moment.php` — new file
10. `product-carousel.php` — full-width wrapper, two-tier header
11. `featured-categories.php` — full-width wrapper, two-tier header
12. `hero.php` — height + vignette
13. ACF fields for editorial moment

**Phase D — Shop + Product pages:**
14. `archive-product.php` — split container / full-width
15. `single-product.php` — spacing + sizing
16. `product-image.php` — thumbnail sizing
17. `related.php` — full-width wrapper

**Phase E — Frame (header + footer):**
18. `site-header.php` + `nav-primary.php` — height, gap, logo
19. `site-footer.php` — padding, headings

**Phase F — Polish:**
20. `_woocommerce.scss` — transition alignment
21. `sort-bar.php` — transitions
22. `npm run build` — verify
23. Visual regression check across pages

---

## What Does NOT Change

- WooCommerce backend (cart, checkout, orders, payment gateways)
- AJAX filter system (query logic, drawer UX, filter-active chips)
- ACF field structure for existing fields
- PHP function signatures in `inc/helpers.php`, `inc/ajax.php`
- JavaScript module architecture and event delegation patterns
- Menu walker classes
- WPML compatibility
- Primary/hover brand color tokens (`#e1c4ff` / `#cfb3f0`)
- Mobile drawer behavior
- Search overlay live search functionality
- Breadcrumb component
- Pagination component (only spacing around it changes)
