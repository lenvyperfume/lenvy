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

  // ── Filter accordions (animated height) ───────────────────────────────────
  document.querySelectorAll('[data-filter-accordion-toggle]').forEach((btn) => {
    const panelId = btn.getAttribute('aria-controls');
    const panel = panelId ? document.getElementById(panelId) : null;
    if (!panel) return;

    // Set up initial state based on aria-expanded
    const startOpen = btn.getAttribute('aria-expanded') === 'true';
    panel.style.display = '';
    panel.style.overflow = 'hidden';
    panel.style.height = startOpen ? 'auto' : '0';
    panel.style.opacity = startOpen ? '1' : '0';
    panel.style.transition = 'height 0.3s ease, opacity 0.2s ease';

    btn.addEventListener('click', () => {
      const isOpen = btn.getAttribute('aria-expanded') === 'true';

      if (isOpen) {
        // Collapse: set explicit height first, then animate to 0
        panel.style.height = panel.scrollHeight + 'px';
        requestAnimationFrame(() => {
          panel.style.height = '0';
          panel.style.opacity = '0';
        });
      } else {
        // Expand: animate to scrollHeight, then clear for flexible content
        panel.style.height = panel.scrollHeight + 'px';
        panel.style.opacity = '1';
        const onEnd = () => {
          panel.removeEventListener('transitionend', onEnd);
          if (btn.getAttribute('aria-expanded') === 'true') {
            panel.style.height = 'auto';
          }
        };
        panel.addEventListener('transitionend', onEnd);
      }

      btn.setAttribute('aria-expanded', String(!isOpen));
      btn.querySelector('svg')?.classList.toggle('rotate-180', !isOpen);
    });
  });
}
