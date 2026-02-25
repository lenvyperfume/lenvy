# Brand Archive Plan

> Created: 2026-02-25
> Status: Approved — awaiting implementation

---

## Files to Create

| File                                     | Purpose                     |
| ---------------------------------------- | --------------------------- |
| `woocommerce/taxonomy-product_brand.php` | Main brand archive template |

## Files to Modify

| File                                     | Change                                                                      |
| ---------------------------------------- | --------------------------------------------------------------------------- |
| `template-parts/shop/filter-sidebar.php` | Accept `$args['hide_brand_filter']` to suppress Brand filter on brand pages |
| `template-parts/shop/filter-drawer.php`  | Same                                                                        |

## No Changes Needed

- `inc/ajax.php` — `lenvy_build_shop_query_args()` already accepts any `taxonomy`/`term`; AJAX filters work on brand archives without modification
- `inc/shop.php` — `pre_get_posts` already handles `filter_brand`; WP routing handles the `product_brand` archive constraint automatically

---

## Template Structure — `taxonomy-product_brand.php`

```
get_header()

── 1. Brand header ──────────────────────────────────────────────
  Reads ACF: lenvy_brand_banner_image, lenvy_brand_logo,
             lenvy_brand_country_of_origin, lenvy_brand_website_url,
             lenvy_brand_is_featured

  State A — banner image exists:
    Full-width image (h-48 md:h-64) with dark gradient overlay
    Overlaid bottom-left:
      [Brand logo — white bg, 56px, if exists]  [Brand Name (Playfair)]
      [Country of origin — small]  ["Featured Brand" chip — if flag set]

  State B — no banner image:
    Neutral-50 strip (py-10 border-b)
    Inline: [logo — 48px if exists]  [Brand Name]  [Country]  [Featured chip]

── 2. Brand metadata bar (only if country or website URL exists) ──
  Slim neutral-100 strip below header
  Country of origin + external website link ("Visit website →" noopener)
  Hidden if both fields are empty

── 3. Main content ───────────────────────────────────────────────
  <main>
    lenvy-container

    Breadcrumb          (Home > Brand Name)

    <div class="flex gap-8 mt-6">

      Filter sidebar    (hide_brand_filter: true)

      <div class="flex-1">
        Sort bar
        Active filter chips (if filtered)

        Product grid     data-taxonomy="product_brand" data-term="{slug}"
          └─ product-card.php × N
          Empty state if no products (shouldn't happen but guarded)

        Pagination

        Brand description  (below grid — ACF wysiwyg lenvy_brand_description)
          Only rendered if field has content
          Same prose styling as category SEO text
      </div>

    </div>
  </main>

Filter drawer (hide_brand_filter: true)

get_footer()
```

---

## ACF Fields Used

| Field                           | Source      | Notes                                                  |
| ------------------------------- | ----------- | ------------------------------------------------------ |
| `lenvy_brand_banner_image`      | `term_{id}` | Full-width banner; fallback to plain header if empty   |
| `lenvy_brand_logo`              | `term_{id}` | Shown in header overlay or inline strip                |
| `lenvy_brand_country_of_origin` | `term_{id}` | Shown in header + metadata bar                         |
| `lenvy_brand_website_url`       | `term_{id}` | External link, opens in new tab, `noopener noreferrer` |
| `lenvy_brand_is_featured`       | `term_{id}` | Shows "Featured Brand" chip in header if true          |
| `lenvy_brand_description`       | `term_{id}` | WYSIWYG, rendered below product grid for SEO           |

---

## Edge Cases

| Case                           | Handling                                                                            |
| ------------------------------ | ----------------------------------------------------------------------------------- |
| No banner image                | Plain `neutral-50` header strip, same style as category fallback                    |
| No brand logo                  | Skip logo entirely; brand name fills the space                                      |
| No country of origin           | Metadata bar hidden (or shown with just website if URL exists)                      |
| No website URL                 | Metadata bar hidden (or shown with just country if that exists)                     |
| No description                 | Description section below grid not rendered                                         |
| `lenvy_brand_is_featured` true | Small "Featured Brand" chip shown in header, consistent with badge component        |
| Empty product list             | Empty state identical to category archive (guarded but `hide_empty` should prevent) |
| ≤ 12 products (1 page)         | Pagination renders nothing — already handled by `lenvy_pagination()`                |
| AJAX filters                   | `data-taxonomy="product_brand"` on grid — handler already supports this             |
| Category filter on brand page  | Narrows brand products by category via AJAX — works without changes                 |
| Mobile                         | Filter drawer hides brand filter same as desktop sidebar                            |
| Breadcrumb                     | Delegates to `wc_get_breadcrumb()` — produces `Home > Brand Name` automatically     |

---

## Implementation Order

1. Modify `filter-sidebar.php` — add `hide_brand_filter` arg support
2. Modify `filter-drawer.php` — same
3. Build `taxonomy-product_brand.php`
4. Build assets (`npm run build`)
5. Commit
