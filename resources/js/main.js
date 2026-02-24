/**
 * Lenvy — main entry point.
 */

import '../css/tailwind.css';
import '../scss/main.scss';

import { initHeader }   from './modules/header.js';
import { initDrawer }   from './modules/drawer.js';
import { initSearch }   from './modules/search.js';
import { initAccordion } from './modules/accordion.js';

// Reveal page after CSS is injected — prevents FOUC on every page load.
document.documentElement.style.opacity = '1';

document.addEventListener('DOMContentLoaded', () => {
  initHeader();
  const { drawer, closeDrawer } = initDrawer();
  const { overlay, closeSearch } = initSearch();
  initAccordion();

  // ESC closes whichever panel is currently open
  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    if (overlay && !overlay.classList.contains('opacity-0')) closeSearch();
    if (drawer && !drawer.classList.contains('-translate-x-full')) closeDrawer();
  });
});

// ── WooCommerce cart quantity — re-enable update button on input change ────────
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('input.qty').forEach((input) => {
    input.addEventListener('change', () => {
      const form      = input.closest('form.woocommerce-cart-form');
      const updateBtn = form?.querySelector('[name="update_cart"]');
      if (updateBtn) updateBtn.disabled = false;
    });
  });
});
