/**
 * Drawer — slide-in navigation / filter panel.
 *
 * Data attribute hooks:
 *   [data-drawer-toggle]   — button that opens the drawer
 *   [data-drawer-close]    — button inside the drawer that closes it
 *   [data-drawer]          — the drawer panel itself
 *   [data-drawer-backdrop] — semi-transparent overlay behind the drawer
 */
export function initDrawer() {
  const toggle   = document.querySelector('[data-drawer-toggle]');
  const close    = document.querySelector('[data-drawer-close]');
  const drawer   = document.querySelector('[data-drawer]');
  const backdrop = document.querySelector('[data-drawer-backdrop]');

  function openDrawer() {
    if (!drawer) return;
    drawer.classList.remove('-translate-x-full');
    drawer.setAttribute('aria-hidden', 'false');
    backdrop?.classList.remove('opacity-0', 'pointer-events-none');
    toggle?.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
    drawer.querySelector('a, button')?.focus();
  }

  function closeDrawer() {
    if (!drawer) return;
    drawer.classList.add('-translate-x-full');
    drawer.setAttribute('aria-hidden', 'true');
    backdrop?.classList.add('opacity-0', 'pointer-events-none');
    toggle?.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
    toggle?.focus();
  }

  toggle?.addEventListener('click', openDrawer);
  close?.addEventListener('click', closeDrawer);
  backdrop?.addEventListener('click', closeDrawer);

  return { drawer, closeDrawer };
}
