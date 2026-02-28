# Launch Readiness & CRO Audit

> Audit date: February 2026
> Status: Pre-launch with paid marketing
> Market: Netherlands (Dutch-facing)

---

## Table of Contents

1. [Product Page Optimization](#1-product-page-optimization)
2. [Category Architecture](#2-category-architecture)
3. [Conversion Baselines](#3-conversion-baselines)
4. [Speed & Performance](#4-speed--performance)
5. [Trust & Legitimacy — Netherlands](#5-trust--legitimacy--netherlands)
6. [Pre-Launch QA Checklist](#6-pre-launch-qa-checklist)
7. [USP Structure](#7-usp-structure)
8. [Priority Implementation Order](#8-priority-implementation-order)

---

## 1. Product Page Optimization

### Above-the-fold issues

| Issue | Location | Impact |
|---|---|---|
| No price visible for variable products until tile is clicked | `single-product.php:81` — price hidden with `if (!$product->is_type('variable'))` | High — no price signal on load |
| No trust signals near ATC button | `single-product.php:107-111` — bare WC form, nothing around it | Critical — NL shoppers expect shipping/return/payment info near buy button |
| All accordions collapsed on load | Scent notes, usage tips, description all `aria-expanded="false"` | Medium — scent profile is primary buying decision for perfume |
| No stock urgency signals | OOS overlay exists, but no low-stock message | Medium — "Nog 2 op voorraad" drives urgency |
| No payment icons at point of decision | iDEAL, Klarna, Visa icons absent from PDP | High — iDEAL absence causes NL drop-off |
| No gallery zoom/lightbox | Image is `object-contain` in `aspect-square`, no fullscreen | Low — luxury bottle detail inspection matters |

### Required additions

**Trust block below ATC button:**

```
─────────────────────────────────────
✓ Gratis verzending vanaf €50
✓ 30 dagen bedenktijd
✓ Veilig betalen  [iDEAL] [Visa] [Mastercard] [Klarna]
─────────────────────────────────────
```

**"Vanaf" price for variable products:**
Show `wc_price(min($prices['price']))` with "Vanaf" prefix above the variation form, so users always see a price on first load.

**Stock urgency:**
For products with `stock_quantity < 5`, display "Nog X op voorraad" near the ATC button.

**Open scent notes by default:**
Change "Geurprofiel" accordion to `aria-expanded="true"` with panel visible on load. Scent profile is the #1 decision driver for fragrance.

### A/B test ideas

1. Scent notes open vs. collapsed — measure ATC rate
2. "Vanaf €XX" price shown vs. hidden for variable products
3. Trust bar below ATC vs. no trust bar
4. Product video as first gallery slide vs. static image only

---

## 2. Category Architecture

### Current filter system

Supported filters (`inc/shop.php`): Brand, Category, Gender, Fragrance Family, Concentration, Volume, Price, In Stock, On Sale.

### Recommended nav hierarchy

```
Damesparfum          → gender filter = dames
Herenparfum          → gender filter = heren
Unisex               → gender filter = unisex
Merken               → brand archive index (/merken/)
├── Chanel
├── Dior
└── ...
Sale                 → filter_onsale=1
Cadeausets           → product_cat = cadeausets (add when gift sets exist)
```

### Filter improvements

**Reorder filters for perfume shoppers.**
Current: Brand > Category > Price > Gender > Family > Concentration > Volume.
Recommended: **Gender > Brand > Price > Fragrance Family > Concentration > Volume > Category**.
Gender is the primary decision axis; brand second.

**Add product counts per filter term.**
`lenvy_get_filter_terms()` returns `WP_Term` objects with `$term->count`. Display it: "Chanel (12)". Without counts, users apply a filter and get 0 results — rage-quit moment.

**Fix mobile quick-add visibility.**
`product-card.php:125`: `opacity-0 group-hover:opacity-100` never triggers on touch devices. Mobile users see no ATC affordance in the product grid. Show a persistent small ATC button on mobile (`max-lg:opacity-100`).

**Scalability (50+ products):**
Architecture is sound (AJAX filters + transient-cached price range + object-cached terms). Consider increasing `posts_per_page` to 24 when catalog exceeds 50 products.

---

## 3. Conversion Baselines

Fragrance ecommerce, Dutch market, cold paid traffic, early stage store:

| Metric | Baseline range | Launch target |
|---|---|---|
| Cold traffic conversion rate | 0.8% – 1.5% | 1.0% |
| Add-to-cart rate | 5% – 10% | 7% |
| Checkout initiation rate (of ATC) | 30% – 50% | 35% |
| Cart abandonment rate | 65% – 80% | 70% |
| Returning customer rate (3 months) | 15% – 25% | 18% |
| Average order value (NL fragrance) | €45 – €85 | €55 |
| Revenue per session | €0.40 – €1.20 | €0.55 |

**Without trust signals (current state), expect cold traffic conversion at 0.3–0.5% — ad spend will burn.**

### AOV levers

- Free shipping threshold at €50 (currently no visible threshold)
- Bundle/gift set offerings
- "Frequently bought together" on PDP
- Cross-sell in cart ("Combineer met...")

---

## 4. Speed & Performance

### Core Web Vitals targets

| Metric | Target | Notes |
|---|---|---|
| LCP | < 2.5s | Google "good" threshold |
| CLS | < 0.1 | Layout shifts kill trust |
| TTFB | < 800ms | Use page cache plugin |
| FID / INP | < 200ms | Interaction responsiveness |

### Image optimization

- [ ] Register custom image sizes (`add_image_size`) — portrait ratio (400x530) for product cards, 800x800 for gallery
- [ ] Serve WebP via plugin (ShortPixel, Imagify, or EWWW)
- [ ] Hero images: `loading="eager"` + `fetchpriority="high"` (not lazy)
- [ ] Product card images: `loading="lazy"` (already done)
- [ ] Set explicit `width` and `height` attributes to prevent CLS

### JS / CSS

- [ ] Vite production build handles minification (confirmed)
- [ ] jQuery loaded for WC variation JS — cannot remove, but ensure non-render-blocking
- [ ] Consider dynamic imports for `gallery.js` and `variation-tiles.js` (only needed on single product page)
- [ ] Defer non-critical JS

### Caching

- [ ] Page cache plugin (WP Super Cache, W3 Total Cache, or Cloudflare APO)
- [ ] Existing transients: price range (6h), filter terms (12h) — correct
- [ ] `Cache-Control` headers for static assets (Vite hashed filenames = safe for 1-year cache)
- [ ] Object cache: Redis or Memcached for `wp_cache_*` calls

### Fonts

- [ ] Google Fonts (Inter + Playfair Display) add 2-3 render-blocking requests
- [ ] Consider self-hosting via `@font-face` with `font-display: swap`
- [ ] Preconnect hints already present in `inc/enqueue.php`

---

## 5. Trust & Legitimacy — Netherlands

### Legally required (Dutch law / EU Consumer Rights Directive)

| Requirement | Current status | Priority |
|---|---|---|
| KVK number | Not shown anywhere | **Must fix** |
| BTW number | Not shown anywhere | **Must fix** |
| Company name + address | Footer has ACF address field — fill it in | **Must fix** |
| Prices include BTW | WC handles if configured; add "Incl. BTW" text near prices | **Must fix** |
| 14-day withdrawal period (Wet Koop op Afstand) | No mention anywhere | **Must fix** |
| Shipping costs visible before checkout | Not shown on product page or cart | **Must fix** |
| Privacy policy link at checkout | **Removed** (`remove_action` in `woocommerce.php:146`) | **Critical — re-enable** |
| Terms & conditions checkbox | Handled by WC if page is set | Verify it works |
| Cookie consent banner | Not present | **Must add** (GDPR) |

### Trust signals to add

**1. Payment icons in footer**
iDEAL (non-negotiable for NL), Visa, Mastercard, Klarna, Bancontact. Dutch shoppers scroll to the footer to check payment methods before buying.

**2. USP bar on every page (below header)**

```
✓ Gratis verzending vanaf €50  ·  ✓ 30 dagen retour  ·  ✓ 100% origineel  ·  ✓ Veilig betalen
```

**3. Keurmerk / trust seal**
Consider Thuiswinkel Waarborg or WebwinkelKeur certification. Dutch shoppers actively look for these seals. WebwinkelKeur is cheaper and faster for a new store.

**4. "Incl. BTW" price suffix**
Add via `woocommerce_get_price_suffix` filter or explicit text near price display.

---

## 6. Pre-Launch QA Checklist

### Technical

- [ ] `npm run build` passes without errors
- [ ] All pages load without PHP errors/warnings (`WP_DEBUG_LOG` enabled, check `wp-content/debug.log`)
- [ ] Mobile responsive: test iPhone SE (smallest common), iPhone 14, Samsung Galaxy S23
- [ ] Cross-browser: Chrome, Safari (especially iOS Safari), Firefox
- [ ] HTTPS enforced sitewide (check mixed content)
- [ ] 404 page exists and works
- [ ] Search returns results, handles empty queries gracefully
- [ ] XML sitemap generated (Yoast/Rank Math or WC built-in)
- [ ] `robots.txt` allows indexing
- [ ] Google Analytics / GA4 + ecommerce tracking installed
- [ ] Facebook Pixel / Meta Conversions API installed (if running Meta ads)
- [ ] Google Search Console verified
- [ ] Page cache active in production
- [ ] WooCommerce status page: no critical errors

### Legal (NL-specific)

- [ ] Privacy policy page exists and linked in footer + checkout
- [ ] Terms & conditions page exists and linked
- [ ] Cookie consent banner installed (Complianz, CookieYes, or similar)
- [ ] KVK + BTW number visible (footer or dedicated page)
- [ ] Return/withdrawal policy page (14 dagen bedenktijd)
- [ ] Shipping information page with costs table
- [ ] "Incl. BTW" shown with prices
- [ ] Re-enable privacy policy text at checkout (undo `remove_action` in `woocommerce.php:146`)

### UX / Conversion

- [ ] Add-to-cart works for simple products (quick-add in grid + PDP button)
- [ ] Add-to-cart works for variable products (tiles select correctly, price updates)
- [ ] Cart: update quantity, remove item, apply coupon — all work
- [ ] Guest checkout: complete purchase without registering
- [ ] Logged-in checkout: complete purchase while logged in
- [ ] Mobile: filter drawer opens/closes, filters apply, grid updates
- [ ] Mobile: product gallery swipes, thumbnails work
- [ ] Mobile: ATC button accessible (currently hidden behind hover — must fix)
- [ ] Empty cart state shows shop link
- [ ] Empty search results shows suggestions
- [ ] All announcement bar links work
- [ ] All footer links point to existing pages (check hardcoded fallback links)
- [ ] Social links configured or "Coming soon." removed from footer

### Payment testing

- [ ] Place test order with iDEAL (use payment provider test mode)
- [ ] Place test order with credit card
- [ ] Place test order with Klarna (if enabled)
- [ ] Verify order confirmation email sent to customer
- [ ] Verify order appears in WP Admin > WooCommerce > Orders
- [ ] Test failed payment flow (cancel at payment provider)
- [ ] Test refund flow from admin
- [ ] Verify invoice/receipt content is correct

### Email flows

- [ ] New order confirmation (customer receives)
- [ ] Order processing notification
- [ ] Order completed / shipped notification
- [ ] Failed order notification (admin receives)
- [ ] New account registration email
- [ ] Password reset email works
- [ ] All emails: correct branding, correct links, mobile rendering
- [ ] Set up abandoned cart recovery emails (Klaviyo, AutomateWoo, or Mailchimp)

---

## 7. USP Structure

Generic USPs ("Fast delivery", "Quality products") convert poorly. Specific USPs for a Dutch fragrance store:

| # | USP | Why it works |
|---|---|---|
| 1 | **100% originele parfums — rechtstreeks van erkende leveranciers** | Fake perfume fear is the #1 barrier for online fragrance purchase. Address it head-on. |
| 2 | **Gratis verzending vanaf €50 — bezorgd in 1-2 werkdagen** | Specific threshold + delivery time. Dutch shoppers trained by Bol.com expect fast, free shipping. |
| 3 | **30 dagen bedenktijd — ongeopend retourneren, geen vragen** | Goes beyond legal 14-day minimum. "No questions asked" removes risk. |
| 4 | **Veilig betalen via iDEAL, Klarna & creditcard** | Name the payment methods. iDEAL mention alone lifts NL trust. |
| 5 | **Deskundig advies — neem contact op voor persoonlijk geuradvies** | Differentiator from Douglas/Bol. Small stores win on personal service. Link to WhatsApp or chat. |

### Placement strategy

- **USP bar below header** (all pages) — short version: icons + 1-line text
- **Product page below ATC** — full version with payment icons
- **Cart summary panel** — shipping threshold + return policy
- **Checkout sidebar** — payment icons + return policy
- **Footer** — payment icons row

---

## 8. Priority Implementation Order

Ranked by conversion impact, highest first:

| # | Task | Impact | Effort |
|---|---|---|---|
| 1 | USP/trust bar component — global, every page | Critical | Medium |
| 2 | Trust block below ATC on product page (payment icons, shipping, returns) | Critical | Small |
| 3 | Fix mobile quick-add visibility (persistent ATC icon on touch) | Critical | Small |
| 4 | Payment icons in footer | High | Small |
| 5 | Re-enable privacy policy at checkout (legal risk) | High | Tiny |
| 6 | "Vanaf €XX" price for variable products | High | Small |
| 7 | Open scent notes accordion by default | Medium | Tiny |
| 8 | Add product counts to filter terms | Medium | Small |
| 9 | Free shipping progress bar in cart | Medium | Medium |
| 10 | "Incl. BTW" price suffix | Medium | Tiny |
| 11 | Cookie consent banner (plugin) | High (legal) | Small |
| 12 | KVK/BTW number in footer | High (legal) | Tiny |
| 13 | Stock urgency message on PDP | Medium | Small |
| 14 | Gallery lightbox/zoom | Low | Medium |
| 15 | Abandoned cart email flow | High | Medium (plugin) |

### Codebase gaps summary

| Component | Status |
|---|---|
| USP bar component | Does not exist |
| Trust block (PDP) | Does not exist |
| Payment icons (footer/PDP/checkout) | Does not exist |
| Mini-cart flyout | Does not exist (only badge count updates) |
| Free shipping progress bar | Does not exist |
| Cookie consent | No plugin installed |
| Newsletter signup | Does not exist |
| Wishlist/save-for-later | Does not exist |
| Gallery zoom/lightbox | Does not exist |
| Checkout progress indicator | Does not exist |
| Post-purchase upsell (thank-you page) | Does not exist |
