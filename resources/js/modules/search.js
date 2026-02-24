/**
 * Search overlay — open / close / auto-focus.
 *
 * Data attribute hooks:
 *   [data-search-toggle]  — button that opens the overlay
 *   [data-search-close]   — button inside the overlay that closes it
 *   [data-search-overlay] — the overlay panel itself
 */
export function initSearch() {
  const toggle  = document.querySelector('[data-search-toggle]');
  const close   = document.querySelector('[data-search-close]');
  const overlay = document.querySelector('[data-search-overlay]');
  const input   = overlay?.querySelector('input[type="search"]');

  function openSearch() {
    if (!overlay) return;
    overlay.classList.remove('opacity-0', 'pointer-events-none');
    overlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    setTimeout(() => input?.focus(), 50);
  }

  function closeSearch() {
    if (!overlay) return;
    overlay.classList.add('opacity-0', 'pointer-events-none');
    overlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    toggle?.focus();
  }

  toggle?.addEventListener('click', openSearch);
  close?.addEventListener('click', closeSearch);

  return { overlay, closeSearch };
}
