/**
 * Lenvy — main entry point.
 */

import '../css/tailwind.css';
import '../scss/main.scss';

import { initHeader } from './modules/header.js';
import { initDrawer } from './modules/drawer.js';
import { initSearch } from './modules/search.js';
import { initAccordion } from './modules/accordion.js';
import { initFilterDrawer } from './modules/filter-drawer.js';
import { initPriceSlider } from './modules/price-slider.js';
import { initGallery } from './modules/gallery.js';
import { initMiniCart } from './modules/mini-cart.js';
import { initQuickAdd } from './modules/quick-add.js';
import { initAjaxFilters } from './modules/ajax-filters.js';
import { initBrandScroller } from './modules/brand-scroller.js';
import { initProductCarousel } from './modules/product-carousel.js';

// Reveal page after CSS is injected — prevents FOUC on every page load.
document.documentElement.style.opacity = '1';

document.addEventListener('DOMContentLoaded', () => {
  initHeader();
  const { drawer, closeDrawer } = initDrawer();
  const { overlay, closeSearch } = initSearch();
  const { filterDrawer, closeFilterDrawer } = initFilterDrawer();
  initAccordion();
  initPriceSlider();
  initGallery();
  initMiniCart();
  initQuickAdd();
  initAjaxFilters();
  initBrandScroller();
  initProductCarousel();

  // Sort select — navigate via URL so price/filter form inputs are never dragged along.
  document.querySelectorAll('[data-sort-select]').forEach((select) => {
    select.addEventListener('change', () => {
      const url = new URL(window.location.href);
      url.searchParams.set('orderby', select.value);
      window.location.href = url.toString();
    });
  });

  // ESC closes whichever panel is currently open.
  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    if (overlay && !overlay.classList.contains('opacity-0')) closeSearch();
    if (drawer && !drawer.classList.contains('-translate-x-full')) closeDrawer();
    if (filterDrawer && !filterDrawer.classList.contains('-translate-x-full')) closeFilterDrawer();
  });
});

// ── Quantity stepper: −/+ buttons ──────────────────────────────────────────────
document.addEventListener('click', (e) => {
  const btn = e.target.closest('[data-qty-minus], [data-qty-plus]');
  if (!btn) return;

  const wrapper = btn.closest('[data-lenvy-qty]');
  const input = wrapper?.querySelector('input.qty');
  if (!input) return;

  const step = parseFloat(input.step) || 1;
  const min = parseFloat(input.min) || 0;
  const max = parseFloat(input.max) || Infinity;
  let val = parseFloat(input.value) || min;

  if (btn.hasAttribute('data-qty-minus')) {
    val = Math.max(min, val - step);
  } else {
    val = Math.min(max, val + step);
  }

  input.value = val;
  input.dispatchEvent(new Event('change', { bubbles: true }));
});

// ── WooCommerce cart — auto-submit on quantity change ─────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  let debounce = null;
  document.addEventListener('change', (e) => {
    if (!e.target.matches('.woocommerce-cart-form input.qty')) return;
    const form = e.target.closest('form.woocommerce-cart-form');
    const updateBtn = form?.querySelector('[name="update_cart"]');
    if (!form || !updateBtn) return;
    updateBtn.disabled = false;
    clearTimeout(debounce);
    debounce = setTimeout(() => updateBtn.click(), 600);
  });
});
