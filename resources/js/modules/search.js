/**
 * Search — inline header band (Douglas / Deloox pattern).
 *
 * Open:  overlay fades in, band slides down from above.
 * Close: band slides back up, overlay fades out.
 *        Triggered by [data-search-close] (close btn OR backdrop click) and ESC.
 */
export function initSearch() {
  const toggle  = document.querySelector('[data-search-toggle]');
  const overlay = document.querySelector('[data-search-overlay]');
  const band    = document.querySelector('[data-search-band]');
  const input   = overlay?.querySelector('input[type="search"]');

  function openSearch() {
    if (!overlay) return;
    overlay.classList.remove('opacity-0', 'pointer-events-none');
    overlay.setAttribute('aria-hidden', 'false');
    band?.classList.remove('-translate-y-full');
    document.body.style.overflow = 'hidden';
    setTimeout(() => input?.focus(), 60);
  }

  function closeSearch() {
    if (!overlay) return;
    band?.classList.add('-translate-y-full');
    // Delay overlay fade until band is mostly out of view.
    setTimeout(() => {
      overlay.classList.add('opacity-0', 'pointer-events-none');
      overlay.setAttribute('aria-hidden', 'true');
    }, 150);
    document.body.style.overflow = '';
    toggle?.focus();
  }

  toggle?.addEventListener('click', openSearch);

  // Attach to every [data-search-close] — close button and backdrop.
  overlay?.querySelectorAll('[data-search-close]').forEach((el) => {
    el.addEventListener('click', closeSearch);
  });

  return { overlay, closeSearch };
}
