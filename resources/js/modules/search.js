/**
 * Search — Skins-style header takeover with live results.
 *
 * Open:  overlay shown instantly (no animation), input focused.
 * Close: overlay hidden, input cleared.
 *
 * States: empty → loading → results | no-results
 * Debounced AJAX (300ms) with AbortController for race-condition safety.
 */

/** Escape HTML entities for safe DOM insertion. */
function escHtml(str) {
  const el = document.createElement('span');
  el.textContent = str;
  return el.innerHTML;
}

/** Escape a string for use in HTML attributes. */
function escAttr(str) {
  return str
    .replace(/&/g, '&amp;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
}

/** Inline chevron-right SVG for link lists. */
const chevronSvg =
  '<svg class="w-3.5 h-3.5 shrink-0 text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>';

export function initSearch() {
  const toggle = document.querySelector('[data-search-toggle]');
  const overlay = document.querySelector('[data-search-overlay]');
  const input = overlay?.querySelector('[data-search-input]');
  const clearBtn = overlay?.querySelector('[data-search-clear]');

  // State containers.
  const states = {};
  overlay?.querySelectorAll('[data-search-state]').forEach((el) => {
    states[el.getAttribute('data-search-state')] = el;
  });

  // Result containers.
  const productsContainer = overlay?.querySelector('[data-search-products]');
  const productCount = overlay?.querySelector('[data-search-product-count]');
  const brandsSection = overlay?.querySelector('[data-search-brands-section]');
  const brandsContainer = overlay?.querySelector('[data-search-brands]');
  const categoriesSection = overlay?.querySelector('[data-search-categories-section]');
  const categoriesContainer = overlay?.querySelector('[data-search-categories]');
  const allResultsLink = overlay?.querySelector('[data-search-all-results]');
  const allResultsText = overlay?.querySelector('[data-search-all-results-text]');

  let debounceTimer = null;
  let abortController = null;

  /** Show one state, hide the rest. */
  function showState(name) {
    Object.entries(states).forEach(([key, el]) => {
      el.classList.toggle('hidden', key !== name);
    });
  }

  /** Toggle clear button visibility based on input value. */
  function updateClearBtn() {
    if (!clearBtn) return;
    clearBtn.classList.toggle('hidden', !input?.value.trim());
  }

  /** Fetch live search results. */
  async function fetchResults(query) {
    if (abortController) {
      abortController.abort();
    }
    abortController = new AbortController();

    showState('loading');

    const params = new URLSearchParams({
      action: 'lenvy_live_search',
      nonce: window.lenvyAjax?.nonce || '',
      query,
    });

    try {
      const res = await fetch(`${window.lenvyAjax?.url || '/wp-admin/admin-ajax.php'}?${params}`, {
        signal: abortController.signal,
      });
      const json = await res.json();

      if (!json.success || !json.data) {
        showState('no-results');
        return;
      }

      const { products_html, products_count, brands, categories, results_url, query: q } = json.data;

      if (!products_count && !brands.length && !categories.length) {
        showState('no-results');
        return;
      }

      // Products.
      if (productsContainer) {
        productsContainer.innerHTML = products_html;
      }
      if (productCount) {
        productCount.textContent = `${products_count} producten gevonden`;
      }

      // Brands.
      if (brandsContainer && brandsSection) {
        if (brands.length) {
          brandsSection.classList.remove('hidden');
          brandsContainer.innerHTML = brands
            .map(
              (b) =>
                `<li><a href="${escAttr(b.url)}" class="flex items-center justify-between gap-2 py-1 text-sm text-neutral-600 hover:text-black transition-colors"><span>${escHtml(b.name)}</span>${chevronSvg}</a></li>`,
            )
            .join('');
        } else {
          brandsSection.classList.add('hidden');
        }
      }

      // Categories.
      if (categoriesContainer && categoriesSection) {
        if (categories.length) {
          categoriesSection.classList.remove('hidden');
          categoriesContainer.innerHTML = categories
            .map(
              (c) =>
                `<li><a href="${escAttr(c.url)}" class="flex items-center justify-between gap-2 py-1 text-sm text-neutral-600 hover:text-black transition-colors"><span>${escHtml(c.name)}</span>${chevronSvg}</a></li>`,
            )
            .join('');
        } else {
          categoriesSection.classList.add('hidden');
        }
      }

      // "All results" link.
      if (allResultsLink && allResultsText) {
        allResultsLink.href = results_url;
        allResultsText.textContent = `Bekijk alle resultaten voor "${q}"`;
      }

      showState('results');
    } catch (err) {
      if (err.name === 'AbortError') return;
      showState('no-results');
    }
  }

  /** Handle input changes with debounce. */
  function onInput() {
    const query = (input?.value || '').trim();

    updateClearBtn();
    clearTimeout(debounceTimer);

    if (query.length < 2) {
      showState('empty');
      if (abortController) {
        abortController.abort();
        abortController = null;
      }
      return;
    }

    debounceTimer = setTimeout(() => fetchResults(query), 300);
  }

  function openSearch() {
    if (!overlay) return;
    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
    overlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    input?.focus();
  }

  function closeSearch() {
    if (!overlay) return;
    overlay.classList.add('hidden');
    overlay.classList.remove('flex');
    overlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';

    if (input) {
      input.value = '';
    }
    updateClearBtn();
    clearTimeout(debounceTimer);
    if (abortController) {
      abortController.abort();
      abortController = null;
    }
    showState('empty');
    toggle?.focus();
  }

  // Bind events.
  toggle?.addEventListener('click', openSearch);
  input?.addEventListener('input', onInput);

  // Clear button.
  clearBtn?.addEventListener('click', () => {
    if (input) {
      input.value = '';
      input.focus();
    }
    updateClearBtn();
    showState('empty');
  });

  // Close buttons.
  overlay?.querySelectorAll('[data-search-close]').forEach((el) => {
    el.addEventListener('click', closeSearch);
  });

  return { overlay, closeSearch };
}
