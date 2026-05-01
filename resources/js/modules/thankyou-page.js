/**
 * Thank-you placeholder — copy-to-clipboard for the order number.
 *
 * The animated check-mark + ring + timeline pulse are pure CSS, so the
 * only behavioural concern here is the order-number copy interaction.
 */

export function initThankyouPage() {
  const root = document.querySelector('[data-thankyou-page]');
  if (!root) return;

  const btn = root.querySelector('[data-copy-order-num]');
  const numEl = root.querySelector('[data-order-num]');
  if (!btn || !numEl) return;

  const original = btn.innerHTML;
  let resetTimer = null;

  btn.addEventListener('click', async () => {
    const text = numEl.textContent.trim();
    if (!text) return;

    try {
      await navigator.clipboard?.writeText(text);
    } catch {
      // Clipboard blocked (insecure context, denied permission). The
      // visual feedback below still tells the user we tried — fine for
      // a placeholder.
    }

    btn.classList.add('is-copied');
    btn.innerHTML =
      '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>';

    clearTimeout(resetTimer);
    resetTimer = setTimeout(() => {
      btn.classList.remove('is-copied');
      btn.innerHTML = original;
    }, 1400);
  });
}
