# WooCommerce Attributes — Setup Reference

These attributes must be registered manually in **WooCommerce → Attributes** in the WordPress admin.
They are intentionally not registered in code so WooCommerce handles the attribute taxonomy creation
correctly (including term ordering and variation support).

---

## Attributes to Register

### 1. Volume (ml)
| Setting | Value |
|---|---|
| Name | Volume (ml) |
| Slug | `volume_ml` ← WC will prefix to `pa_volume_ml` |
| Enable archives | Yes |
| Default sort order | Custom ordering |

**Terms to add:**
- 30ml
- 50ml
- 75ml
- 100ml
- 125ml
- 200ml

---

### 2. Gender
| Setting | Value |
|---|---|
| Name | Gender |
| Slug | `gender` → `pa_gender` |
| Enable archives | Yes |
| Default sort order | Custom ordering |

**Terms to add:**
- For Her
- For Him
- Unisex

---

### 3. Fragrance Family
| Setting | Value |
|---|---|
| Name | Fragrance Family |
| Slug | `fragrance_family` → `pa_fragrance_family` |
| Enable archives | Yes |
| Default sort order | Name |

**Terms to add:**
- Floral
- Woody
- Oriental
- Fresh
- Aquatic
- Citrus
- Gourmand
- Chypre
- Fougère

---

### 4. Concentration
| Setting | Value |
|---|---|
| Name | Concentration |
| Slug | `concentration` → `pa_concentration` |
| Enable archives | Yes |
| Default sort order | Custom ordering |

**Terms to add:**
- Eau de Cologne (EDC)
- Eau de Toilette (EDT)
- Eau de Parfum (EDP)
- Parfum
- Solid Perfume

---

### 5. Occasion
| Setting | Value |
|---|---|
| Name | Occasion |
| Slug | `occasion` → `pa_occasion` |
| Enable archives | No |
| Default sort order | Name |

**Terms to add:**
- Casual
- Formal
- Evening
- Sport
- Office

---

## Notes

- **Variation attribute:** Only `pa_volume_ml` should be used for product variations. All other attributes are informational/filterable only.
- **Assign globally:** When adding attributes to products, use "Select all" to assign terms globally for filter support.
- **Archive pages:** Attributes with "Enable archives" set to Yes will have their own URL (e.g. `/product-attribute/pa_gender/for-her/`) which can be useful for SEO landing pages.
