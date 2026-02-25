/**
 * Filter drawer — mobile filter panel open/close.
 *
 * Mirrors the pattern in drawer.js but uses [data-filter-drawer-*] attributes.
 */
export function initFilterDrawer() {
  const drawer = document.querySelector('[data-filter-drawer]');
  const backdrop = document.querySelector('[data-filter-drawer-backdrop]');

  if (!drawer) return { filterDrawer: null, closeFilterDrawer: () => {} };

  const openFilterDrawer = () => {
    drawer.classList.remove('-translate-x-full');
    drawer.setAttribute('aria-hidden', 'false');
    backdrop?.classList.remove('opacity-0', 'pointer-events-none');
    document.body.style.overflow = 'hidden';
    document.querySelectorAll('[data-filter-drawer-toggle]').forEach((btn) => {
      btn.setAttribute('aria-expanded', 'true');
    });
    // Focus first focusable element inside drawer.
    drawer.querySelector('button, input, select, a[href]')?.focus();
  };

  const closeFilterDrawer = () => {
    drawer.classList.add('-translate-x-full');
    drawer.setAttribute('aria-hidden', 'true');
    backdrop?.classList.add('opacity-0', 'pointer-events-none');
    document.body.style.overflow = '';
    document.querySelectorAll('[data-filter-drawer-toggle]').forEach((btn) => {
      btn.setAttribute('aria-expanded', 'false');
    });
  };

  document.querySelectorAll('[data-filter-drawer-toggle]').forEach((btn) => {
    btn.addEventListener('click', openFilterDrawer);
  });

  document.querySelectorAll('[data-filter-drawer-close]').forEach((btn) => {
    btn.addEventListener('click', closeFilterDrawer);
  });

  backdrop?.addEventListener('click', closeFilterDrawer);

  return { filterDrawer: drawer, closeFilterDrawer };
}
