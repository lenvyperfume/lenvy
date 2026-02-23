/**
 * Lenvy — main entry point.
 */

import '../css/tailwind.css';
import '../scss/main.scss';

// Reveal page after CSS is injected — prevents FOUC on every page load.
document.documentElement.style.opacity = '1';

document.addEventListener('DOMContentLoaded', () => {
  // ── Mobile drawer ────────────────────────────────────────────────────────

  const drawerToggle   = document.querySelector('[data-drawer-toggle]');
  const drawerClose    = document.querySelector('[data-drawer-close]');
  const drawer         = document.querySelector('[data-drawer]');
  const drawerBackdrop = document.querySelector('[data-drawer-backdrop]');

  function openDrawer() {
    if (!drawer) return;
    drawer.classList.remove('-translate-x-full');
    drawer.setAttribute('aria-hidden', 'false');
    drawerBackdrop?.classList.remove('opacity-0', 'pointer-events-none');
    drawerToggle?.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
    // Focus first focusable element inside drawer
    drawer.querySelector('a, button')?.focus();
  }

  function closeDrawer() {
    if (!drawer) return;
    drawer.classList.add('-translate-x-full');
    drawer.setAttribute('aria-hidden', 'true');
    drawerBackdrop?.classList.add('opacity-0', 'pointer-events-none');
    drawerToggle?.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
    drawerToggle?.focus();
  }

  drawerToggle?.addEventListener('click', openDrawer);
  drawerClose?.addEventListener('click', closeDrawer);
  drawerBackdrop?.addEventListener('click', closeDrawer);

  // ── Mobile sub-menu accordion ────────────────────────────────────────────

  document.querySelectorAll('[data-mobile-submenu-toggle]').forEach((btn) => {
    const li      = btn.closest('li');
    const submenu = li?.querySelector('.mobile-submenu');
    if (!submenu) return;

    btn.addEventListener('click', () => {
      const isOpen = btn.getAttribute('aria-expanded') === 'true';

      if (isOpen) {
        submenu.style.maxHeight = '0';
        submenu.style.opacity   = '0';
        submenu.setAttribute('aria-hidden', 'true');
        btn.setAttribute('aria-expanded', 'false');
        btn.querySelector('svg')?.classList.remove('rotate-180');
      } else {
        submenu.style.maxHeight = submenu.scrollHeight + 'px';
        submenu.style.opacity   = '1';
        submenu.setAttribute('aria-hidden', 'false');
        btn.setAttribute('aria-expanded', 'true');
        btn.querySelector('svg')?.classList.add('rotate-180');
      }
    });
  });

  // ── Search overlay ───────────────────────────────────────────────────────

  const searchToggle  = document.querySelector('[data-search-toggle]');
  const searchClose   = document.querySelector('[data-search-close]');
  const searchOverlay = document.querySelector('[data-search-overlay]');
  const searchInput   = searchOverlay?.querySelector('input[type="search"]');

  function openSearch() {
    if (!searchOverlay) return;
    searchOverlay.classList.remove('opacity-0', 'pointer-events-none');
    searchOverlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    setTimeout(() => searchInput?.focus(), 50);
  }

  function closeSearch() {
    if (!searchOverlay) return;
    searchOverlay.classList.add('opacity-0', 'pointer-events-none');
    searchOverlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    searchToggle?.focus();
  }

  searchToggle?.addEventListener('click', openSearch);
  searchClose?.addEventListener('click', closeSearch);

  // ESC closes both overlay and drawer
  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    if (searchOverlay && !searchOverlay.classList.contains('opacity-0')) closeSearch();
    if (drawer && !drawer.classList.contains('-translate-x-full')) closeDrawer();
  });

  // ── Header scroll shadow ─────────────────────────────────────────────────

  const header = document.querySelector('[data-header]');

  if (header) {
    const onScroll = () => {
      if (window.scrollY > 8) {
        header.classList.add('shadow-sm');
      } else {
        header.classList.remove('shadow-sm');
      }
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }
});

// ── WooCommerce cart quantity update ──────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('input.qty').forEach((input) => {
    input.addEventListener('change', () => {
      const form      = input.closest('form.woocommerce-cart-form');
      const updateBtn = form?.querySelector('[name="update_cart"]');
      if (updateBtn) updateBtn.disabled = false;
    });
  });
});
