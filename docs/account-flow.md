# Customer Account Flow — Lenvy Theme

Implementation plan and reference for the full cart → account choice → checkout flow.

---

## Overview

Conversion-focused account flow modelled after deloox.nl / douglas.nl UX.
Logged-in users skip straight to checkout. Guests must make an explicit choice.

```
Cart
 └→ Checkout (non-logged-in, no ?guest=1)
     └→ /account-choice/
         ├→ Log in          → wp-login → Checkout
         ├→ Create account  → /my-account/#register → Checkout
         └→ Guest checkout  → /checkout/?guest=1
```

---

## Files Created

### `templates/page-account-choice.php`

WordPress page template (`Template Name: Account Choice`).

Three equal cards in a responsive 1→3-column grid:

| Card | Headline | CTA | Link |
|---|---|---|---|
| Returning customer | Sign in for faster checkout | Log in | `wp_login_url(wc_get_checkout_url())` |
| New customer | Create an account to track orders | Create account | `wp_registration_url()` |
| Guest | Continue without an account | Continue as guest | `wc_get_checkout_url() + ?guest=1` |

**How to activate:** Create a WordPress page with any title (e.g. *Account Choice*), set the page template to **Account Choice** in the sidebar, and publish it at the slug `/account-choice/`.

---

### `woocommerce/myaccount/dashboard.php`

Overrides WooCommerce's default dashboard template.

- Greeting: "Welcome back, {display_name}."
- Four navigation cards: Orders · Addresses · Account details · Sign out
- Hover state on sign-out card uses red tones as a soft visual warning
- All URLs via `wc_get_account_endpoint_url()` — WPML-compatible

---

### `woocommerce/myaccount/form-login.php`

Overrides WooCommerce's login/register form.

Two-column split layout on ≥ md breakpoints:

- **Left — Log in:** email, password, remember-me, forgot-password, nonce, hidden redirect, submit
- **Right — Create account:** username (if WC setting requires), email, password (if WC setting requires), nonce, hidden redirect to checkout, submit

All WooCommerce action hooks are preserved:
- `woocommerce_login_form_start` / `woocommerce_login_form` / `woocommerce_login_form_end`
- `woocommerce_register_form_start` / `woocommerce_register_form` / `woocommerce_register_form_end`

---

## Files Modified

### `inc/woocommerce.php` — additions

#### Reviews disabled completely

```php
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
add_filter('woocommerce_enable_reviews', '__return_false');
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
// existing: woocommerce_product_tabs unset + woocommerce_product_reviews_enabled
```

#### Checkout access control (`template_redirect`)

Fires before the checkout template renders. Redirect conditions:

| Condition | Action |
|---|---|
| Not on checkout page | skip |
| User is logged in | skip |
| `?guest=1` present | skip |
| `is_admin()` or `wp_doing_ajax()` | skip |
| `$_POST['woocommerce-process-checkout-nonce']` set | skip (form submission) |
| All others | `wp_safe_redirect(lenvy_get_account_choice_url())` |

#### Guest checkout filter (`woocommerce_checkout_registration_required`)

Returns `false` (registration not required) when `?guest=1` is present.
Returns `true` otherwise — enforcing that only explicitly-chosen guests bypass registration.

---

### `inc/helpers.php` — addition

```php
function lenvy_get_account_choice_url(): string {
    return esc_url(home_url('/account-choice/'));
}
```

Returns the account-choice page URL via `home_url()` so WPML language prefixes are applied automatically.

---

## WordPress Admin Setup

1. **Create the Account Choice page**
   - Pages → Add New
   - Title: e.g. *Account Choice*
   - Slug: `account-choice` (must match `lenvy_get_account_choice_url()`)
   - Page Template: **Account Choice**
   - Publish

2. **WooCommerce settings to verify**
   - WooCommerce → Settings → Accounts & Privacy
   - Guest checkout: can be on or off — our filter controls it via `?guest=1`
   - Account creation at checkout: can be off — managed by this flow

3. **No flush needed** — no custom rewrite rules were added

---

## WPML Compatibility

- All internal URLs use `home_url()`, `wc_get_checkout_url()`, `wc_get_account_endpoint_url()`, `wp_login_url()`, `wp_registration_url()`
- No slugs are hardcoded in hook logic
- `lenvy_get_account_choice_url()` uses `home_url()` which WPML filters per language

---

## Review Disable — Full Matrix

| Location | Method | Status |
|---|---|---|
| Product tabs | `woocommerce_product_tabs` unset | ✓ |
| Product form | `woocommerce_product_reviews_enabled` | ✓ |
| WC toggle | `woocommerce_enable_reviews` | ✓ |
| Shop loop rating | `remove_action …loop_rating` | ✓ |
| Comments open | `comments_open` filter | ✓ |
| Pings open | `pings_open` filter | ✓ |

---

## Security Notes

- All output uses `esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses()`
- Login and register forms use WordPress nonces (`woocommerce-login-nonce`, `woocommerce-register-nonce`)
- Checkout redirect uses `wp_safe_redirect()` + `exit()`
- `$_GET['guest']` is only used as a boolean presence check — never echoed or stored raw
- `$_POST['username']` / `$_POST['email']` values in form fields are escaped with `esc_attr(wp_unslash(...))`
