/**
 * Mini-cart — cart count badge updates.
 *
 * Exports updateCartCount() for use by quick-add.js and any other
 * module that changes the cart. Also hooks into WC's native
 * 'added_to_cart' jQuery event (fired by WC's own product-page AJAX).
 */

export function initMiniCart() {
  // WC fires a jQuery 'added_to_cart' event on the body after its own
  // add-to-cart AJAX completes (e.g. variable products on the product page).
  // jQuery may not be available as an ES module; use the DOM event approach.
  document.body.addEventListener('added_to_cart', (e) => {
    const detail = e.detail ?? {};
    if (typeof detail.cart_count === 'number') {
      updateCartCount(detail.cart_count);
    }
  });

  // WC also triggers a custom event via jQuery — bridge it.
  if (window.jQuery) {
    window.jQuery(document.body).on('added_to_cart', (e, fragments, hash, button) => {
      // WC fragments contain the cart widget HTML; extract count from the
      // .woocommerce-cart-count span if present, otherwise fetch via AJAX.
      refreshCartCount();
    });
  }
}

/**
 * Update the cart count badge(s) in the header.
 *
 * @param {number} count
 */
export function updateCartCount(count) {
  document.querySelectorAll('[data-cart-count]').forEach((el) => {
    if (count > 0) {
      el.textContent = count > 99 ? '99+' : String(count);
      el.style.display = '';
    } else {
      el.style.display = 'none';
    }
  });

  // Update cart link aria-label.
  document.querySelectorAll('[data-cart-link]').forEach((el) => {
    el.setAttribute(
      'aria-label',
      count === 1 ? 'Cart, 1 item' : `Cart, ${count} items`,
    );
  });
}

/**
 * Fetch current cart count from the server and update the badge.
 * Used when we can't determine the new count client-side.
 */
export function refreshCartCount() {
  if (!window.lenvyAjax) return;

  const body = new URLSearchParams({
    action: 'lenvy_add_to_cart',
    nonce:  window.lenvyAjax.nonce,
    product_id: '0', // count-only ping — handler guards against 0
  });

  // Simpler: use WC's built-in session endpoint.
  fetch(window.lenvyAjax.url + '?action=woocommerce_get_refreshed_fragments', {
    method: 'POST',
    credentials: 'same-origin',
  })
    .then((r) => r.json())
    .then((data) => {
      if (data && data.cart_hash !== undefined) {
        // Parse count from fragments if available.
        const match = JSON.stringify(data.fragments ?? {}).match(/"cart_count":(\d+)/);
        if (match) updateCartCount(parseInt(match[1], 10));
      }
    })
    .catch(() => {});
}
