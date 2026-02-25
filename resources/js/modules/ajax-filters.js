/**
 * AJAX filters — intercepts filter form submissions and replaces the product
 * grid without a full page reload.
 *
 * Flow:
 *   1. User submits [data-filter-form] (or sort select navigates).
 *   2. JS serializes the form, pushes new URL via history.pushState.
 *   3. POST to lenvy_filter_products AJAX handler.
 *   4. Grid, pagination, and active-filter chips are swapped in place.
 *
 * Browser back/forward is handled via popstate — the URL already holds the
 * full filter state so we just re-fetch with the current URL's params.
 */

export function initAjaxFilters() {
  const grid = document.querySelector('[data-product-grid]');
  if (!grid) return; // Not a shop/archive page — bail.

  // Attach submit listeners to all filter forms (sidebar + drawer).
  document.querySelectorAll('[data-filter-form]').forEach((form) => {
    form.addEventListener('submit', handleSubmit);
  });

  // Browser back / forward.
  window.addEventListener('popstate', () => {
    fetchFiltered(new URLSearchParams(window.location.search));
  });
}

// ── Form submit ──────────────────────────────────────────────────────────────

function handleSubmit(e) {
  e.preventDefault();

  const formData = new FormData(e.currentTarget);
  const params = new URLSearchParams();

  // FormData → URLSearchParams (handles multiple values for the same key).
  for (const [key, value] of formData.entries()) {
    params.append(key, value);
  }

  // Push the new URL.
  const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
  history.pushState({}, '', newUrl);

  fetchFiltered(params);
}

// ── Fetch + swap ─────────────────────────────────────────────────────────────

function fetchFiltered(params) {
  const grid = document.querySelector('[data-product-grid]');
  if (!grid || !window.lenvyAjax) return;

  // Pass taxonomy/term context from the grid element so the PHP handler
  // knows which archive we're on.
  const taxonomy = grid.dataset.taxonomy ?? '';
  const term = grid.dataset.term ?? '';

  // Loading state.
  grid.style.opacity = '0.4';
  grid.style.pointerEvents = 'none';
  grid.style.transition = 'opacity 200ms ease';

  const body = new URLSearchParams(params);
  body.set('action', 'lenvy_filter_products');
  body.set('nonce', window.lenvyAjax.nonce);
  body.set('taxonomy', taxonomy);
  body.set('term', term);
  body.set('paged', getPagedFromParams(params));

  fetch(window.lenvyAjax.url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: body.toString(),
  })
    .then((r) => r.json())
    .then((data) => {
      if (!data.success) {
        resetGrid(grid);
        return;
      }

      const { html, count, pagination, active } = data.data;

      // Swap grid content.
      grid.innerHTML = html;
      grid.style.opacity = '';
      grid.style.pointerEvents = '';

      // Swap pagination.
      const paginationEl = document.querySelector('.lenvy-pagination');
      if (pagination) {
        if (paginationEl) {
          paginationEl.outerHTML = pagination;
        } else {
          grid.insertAdjacentHTML('afterend', pagination);
        }
      } else if (paginationEl) {
        paginationEl.remove();
      }

      // Swap active-filter chips.
      const activeEl = document.querySelector('[data-active-filters]');
      if (active.trim()) {
        if (activeEl) {
          activeEl.outerHTML = active;
        } else {
          const sortBar =
            document.querySelector('[data-filter-sidebar]')?.nextElementSibling ??
            grid.closest('.flex-1')?.querySelector('[data-active-filters]');
          if (sortBar) sortBar.insertAdjacentHTML('afterend', active);
        }
      } else if (activeEl) {
        activeEl.remove();
      }

      // Update results count label.
      updateResultsCount(count, params);

      // Close mobile filter drawer.
      document.querySelector('[data-filter-drawer-close]')?.click();

      // Scroll to top of grid if it's off-screen.
      const gridTop = grid.getBoundingClientRect().top + window.scrollY;
      if (window.scrollY > gridTop - 80) {
        window.scrollTo({ top: gridTop - 80, behavior: 'smooth' });
      }
    })
    .catch(() => resetGrid(grid));
}

// ── Helpers ──────────────────────────────────────────────────────────────────

function resetGrid(grid) {
  grid.style.opacity = '';
  grid.style.pointerEvents = '';
}

function getPagedFromParams(params) {
  return params.get('paged') ?? '1';
}

function updateResultsCount(count, params) {
  const el = document.querySelector('[data-results-count]');
  if (!el) return;

  const perPage = 12;
  const paged = parseInt(params.get('paged') ?? '1', 10);
  const from = (paged - 1) * perPage + 1;
  const to = Math.min(paged * perPage, count);

  el.textContent = count > 0 ? `Showing ${from}–${to} of ${count} products` : 'No products found';
}
