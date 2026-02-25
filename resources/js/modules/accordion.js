/**
 * Accordion — mobile nav submenu expand / collapse.
 *
 * Targets [data-mobile-submenu-toggle] buttons rendered by
 * Lenvy_Mobile_Nav_Walker. Each toggle sits inside a <li> that contains
 * a .mobile-submenu <ul>; max-height animation drives the transition.
 *
 * Also handles [data-filter-accordion-toggle] buttons in filter sidebar/drawer.
 */
export function initAccordion() {
  // ── Mobile nav submenus (max-height animation) ─────────────────────────────
  document.querySelectorAll('[data-mobile-submenu-toggle]').forEach((btn) => {
    const li = btn.closest('li');
    const submenu = li?.querySelector('.mobile-submenu');
    if (!submenu) return;

    btn.addEventListener('click', () => {
      const isOpen = btn.getAttribute('aria-expanded') === 'true';

      if (isOpen) {
        submenu.style.maxHeight = '0';
        submenu.style.opacity = '0';
        submenu.setAttribute('aria-hidden', 'true');
        btn.setAttribute('aria-expanded', 'false');
        btn.querySelector('svg')?.classList.remove('rotate-180');
      } else {
        submenu.style.maxHeight = submenu.scrollHeight + 'px';
        submenu.style.opacity = '1';
        submenu.setAttribute('aria-hidden', 'false');
        btn.setAttribute('aria-expanded', 'true');
        btn.querySelector('svg')?.classList.add('rotate-180');
      }
    });
  });

  // ── Filter accordions (display:none toggle) ────────────────────────────────
  document.querySelectorAll('[data-filter-accordion-toggle]').forEach((btn) => {
    const panelId = btn.getAttribute('aria-controls');
    const panel = panelId ? document.getElementById(panelId) : null;
    if (!panel) return;

    btn.addEventListener('click', () => {
      const isOpen = btn.getAttribute('aria-expanded') === 'true';
      btn.setAttribute('aria-expanded', String(!isOpen));
      panel.style.display = isOpen ? 'none' : '';
      btn.querySelector('svg')?.classList.toggle('rotate-180', !isOpen);
    });
  });
}
