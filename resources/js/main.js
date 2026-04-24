/**
 * Lenvy — main entry point.
 */

import '../css/tailwind.css';
import '../scss/main.scss';

import { initHeader } from './modules/header.js';
import { initDrawer } from './modules/drawer.js';
import { initSearchDrawer } from './modules/search-drawer.js';
import { initHeroSlider } from './modules/hero-slider.js';
import { initAccordion } from './modules/accordion.js';
import { initFilterDrawer } from './modules/filter-drawer.js';
import { initPriceSlider } from './modules/price-slider.js';
import { initGallery } from './modules/gallery.js';
import { initMiniCart } from './modules/mini-cart.js';
import { initQuickAdd } from './modules/quick-add.js';
import { initAjaxFilters } from './modules/ajax-filters.js';
import { initBrandScroller } from './modules/brand-scroller.js';
import { initProductCarousel } from './modules/product-carousel.js';
import { initBrandsFilter } from './modules/brands-filter.js';
import { initVariationTiles } from './modules/variation-tiles.js';
import { initUspSlider } from './modules/usp-slider.js';

// Reveal page after CSS is injected — prevents FOUC on every page load.
document.documentElement.style.opacity = '1';

document.addEventListener('DOMContentLoaded', () => {
  initHeader();
  const { drawer, closeDrawer } = initDrawer();
  initSearchDrawer({ triggers: '[data-search-trigger]' });
  initHeroSlider();
  const { filterDrawer, closeFilterDrawer } = initFilterDrawer();
  initAccordion();
  initPriceSlider();
  initGallery();
  initMiniCart();
  initQuickAdd();
  initAjaxFilters();
  initBrandScroller();
  initProductCarousel();
  initBrandsFilter();
  initVariationTiles();
  initUspSlider();

  // Custom sort dropdown.
  document.querySelectorAll('[data-sort-dropdown]').forEach((dropdown) => {
    const trigger = dropdown.querySelector('[data-sort-trigger]');
    const panel = dropdown.querySelector('[data-sort-options]');
    if (!trigger || !panel) return;

    const open = () => {
      trigger.setAttribute('aria-expanded', 'true');
      panel.classList.add('is-open');
      trigger.querySelector('svg')?.classList.add('rotate-180');
    };

    const close = () => {
      trigger.setAttribute('aria-expanded', 'false');
      panel.classList.remove('is-open');
      trigger.querySelector('svg')?.classList.remove('rotate-180');
    };

    trigger.addEventListener('click', (e) => {
      e.stopPropagation();
      trigger.getAttribute('aria-expanded') === 'true' ? close() : open();
    });

    const noReload = dropdown.hasAttribute('data-sort-no-reload');
    const label = dropdown.querySelector('[data-sort-label]');

    dropdown.querySelectorAll('[data-sort-value]').forEach((btn) => {
      btn.addEventListener('click', () => {
        if (noReload) {
          // Placeholder mode — update UI only, no navigation.
          if (label) label.textContent = btn.querySelector('span')?.textContent?.trim() ?? btn.textContent.trim();
          dropdown.querySelectorAll('[data-sort-value]').forEach((o) => {
            const active = o === btn;
            o.classList.toggle('is-active', active);
            if (active) o.setAttribute('aria-selected', 'true');
            else o.removeAttribute('aria-selected');
          });
          close();
          return;
        }
        const url = new URL(window.location.href);
        url.searchParams.set('orderby', btn.dataset.sortValue);
        window.location.href = url.toString();
      });
    });

    document.addEventListener('click', (e) => {
      if (!dropdown.contains(e.target)) close();
    });
  });

  // In-group filter search (brand list).
  document.querySelectorAll('[data-filter-search]').forEach((input) => {
    const group = input.closest('[data-filter-accordion]');
    const opts = group?.querySelectorAll('[data-filter-opts] [data-label]');
    if (!opts) return;

    input.addEventListener('input', () => {
      const q = input.value.toLowerCase().trim();
      opts.forEach((li) => {
        li.style.display = !q || li.dataset.label.includes(q) ? '' : 'none';
      });
    });
  });

  // Product card wishlist toggle (visual only — persistence TBD).
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-wishlist-toggle]');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    btn.classList.toggle('is-active');
  });

  // ESC closes whichever panel is currently open.
  // The search drawer handles its own Esc; the other drawers don't.
  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    if (drawer && !drawer.classList.contains('-translate-x-full')) closeDrawer();
    if (filterDrawer && !filterDrawer.classList.contains('-translate-x-full')) closeFilterDrawer();
    // Close any open sort dropdowns.
    document.querySelectorAll('[data-sort-trigger][aria-expanded="true"]').forEach((t) => t.click());
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
