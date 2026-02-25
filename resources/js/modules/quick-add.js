/**
 * Quick-add to cart — fires AJAX from [data-quick-add] buttons on product cards.
 *
 * Uses event delegation so it works on dynamically replaced grids (AJAX filters).
 */

import { updateCartCount } from './mini-cart.js';

export function initQuickAdd() {
  document.addEventListener('click', handleClick);
}

function handleClick(e) {
  const btn = e.target.closest('[data-quick-add]');
  if (!btn || btn.disabled) return;

  e.preventDefault();

  const productId = btn.dataset.productId;
  if (!productId) return;

  const originalText = btn.textContent.trim();

  // Loading state.
  btn.disabled = true;
  btn.textContent = '\u2026'; // …

  const body = new URLSearchParams({
    action: 'lenvy_add_to_cart',
    nonce: window.lenvyAjax?.nonce ?? '',
    product_id: productId,
    quantity: '1',
  });

  fetch(window.lenvyAjax?.url ?? '', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: body.toString(),
  })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        updateCartCount(data.data.cart_count);

        // Brief ✓ confirmation.
        btn.textContent = '\u2713'; // ✓
        setTimeout(() => {
          btn.textContent = originalText;
          btn.disabled = false;
        }, 1400);
      } else {
        btn.textContent = originalText;
        btn.disabled = false;
      }
    })
    .catch(() => {
      btn.textContent = originalText;
      btn.disabled = false;
    });
}
