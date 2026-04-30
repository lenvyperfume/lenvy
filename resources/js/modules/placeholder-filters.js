/**
 * Placeholder shop — fully client-side filters.
 *
 * Activates only on the placeholder shop archive (`[data-product-grid][data-placeholder-shop]`).
 * Reads facet checkboxes + price slider + sort dropdown, then toggles
 * `.is-hidden` on `[data-product-card]` elements based on the matched set.
 *
 * Filter facet → matches checkbox `name` in the form:
 *   filter_collection[]  → card[data-collection]
 *   filter_gender[]      → card[data-gender]
 *   filter_family[]      → card[data-family]
 *   filter_brand[]       → card[data-brand]
 *   filter_size[]        → card[data-size] (matched as ml number)
 *   min_price/max_price  → card[data-price]
 *
 * When a real WC backend lands the shop archive will drop the
 * `data-placeholder-shop` attribute and ajax-filters.js takes over instead.
 */
export function initPlaceholderFilters() {
  const grid = document.querySelector('[data-product-grid][data-placeholder-shop]');
  if (!grid) return;

  // ── DOM refs ───────────────────────────────────────────────────────────────
  const cards = Array.from(grid.querySelectorAll('[data-product-card]'));
  const forms = Array.from(document.querySelectorAll('[data-filter-form]'));
  const checkboxes = Array.from(document.querySelectorAll('[data-filter-checkbox]'));
  const priceRoots = Array.from(document.querySelectorAll('[data-price-slider]'));
  const sortDropdown = document.querySelector('[data-sort-dropdown]');
  const sortLabel = document.querySelector('[data-sort-label]');
  const resultsCount = document.querySelector('[data-results-count]');
  const chipsRow = document.querySelector('[data-active-filters]');
  const loadMoreCount = document.querySelector('.lenvy-load-more__count');
  const loadMoreFill = document.querySelector('.lenvy-load-more__fill');
  const loadMoreBtn = document.querySelector('.lenvy-load-more__btn');

  // Pre-compute card facets so we don't re-parse on every keystroke.
  const facetIndex = cards.map((card) => ({
    el: card,
    brand:      card.dataset.brand      || '',
    family:     card.dataset.family     || '',
    gender:     card.dataset.gender     || '',
    collection: card.dataset.collection || '',
    size:       parseInt(card.dataset.size || '0', 10),
    price:      parseFloat(card.dataset.price || '0'),
    onSale:     card.dataset.onsale === '1',
  }));

  const TOTAL = cards.length;

  // ── Sort options ──────────────────────────────────────────────────────────
  const SORT_FNS = {
    popular:      (a, b) => 0, // preserve original order
    new:          (a, b) => 0, // simulated — no date in placeholder data
    'price-asc':  (a, b) => a.price - b.price,
    'price-desc': (a, b) => b.price - a.price,
    sale:         (a, b) => (b.onSale ? 1 : 0) - (a.onSale ? 1 : 0),
  };
  let activeSort = 'popular';

  // ── Friendly labels for the chips row (slug → display) ────────────────────
  const labelLookup = buildLabelLookup();

  function buildLabelLookup() {
    // Walk all filter checkboxes and harvest their <label> text per slug.
    const map = {};
    checkboxes.forEach((cb) => {
      // The associated <label> is the visible name span next to the box.
      const label = cb.closest('.lenvy-opt')?.querySelector('.lenvy-opt__name');
      if (!label) return;
      const name = (cb.getAttribute('name') || '').replace(/\[\]$/, '');
      const facet = name.replace(/^filter_/, '');
      const slug = cb.value;
      map[`${facet}:${slug}`] = label.textContent.trim();
    });
    return map;
  }

  // ── Read current state from the DOM ───────────────────────────────────────
  function readState() {
    const state = {
      collection: new Set(),
      gender:     new Set(),
      family:     new Set(),
      brand:      new Set(),
      size:       new Set(), // numeric ml values
      priceMin:   null,
      priceMax:   null,
    };

    checkboxes.forEach((cb) => {
      if (!cb.checked) return;
      const name = (cb.getAttribute('name') || '').replace(/\[\]$/, '');
      const facet = name.replace(/^filter_/, '');
      const value = cb.value;
      if (facet === 'size') {
        // size slug from PHP is "30ml" / "50ml" — extract the integer.
        const n = parseInt(value, 10);
        if (Number.isFinite(n)) state.size.add(n);
      } else if (state[facet]) {
        state[facet].add(value);
      }
    });

    // Read price from the slider's hidden inputs (price-slider.js writes them).
    if (priceRoots.length) {
      const root = priceRoots[0];
      const minInput = root.querySelector('[data-slider-input="min"]');
      const maxInput = root.querySelector('[data-slider-input="max"]');
      const globalMin = parseFloat(root.dataset.min);
      const globalMax = parseFloat(root.dataset.max);
      const min = parseFloat(minInput?.value ?? globalMin);
      const max = parseFloat(maxInput?.value ?? globalMax);
      state.priceMin = Number.isFinite(min) ? min : globalMin;
      state.priceMax = Number.isFinite(max) ? max : globalMax;
      state.priceGlobalMin = globalMin;
      state.priceGlobalMax = globalMax;
    }

    return state;
  }

  // ── Match a single card against the state ─────────────────────────────────
  function matches(facet, state) {
    if (state.collection.size && !state.collection.has(facet.collection)) return false;
    if (state.gender.size     && !state.gender.has(facet.gender))         return false;
    if (state.family.size     && !state.family.has(facet.family))         return false;
    if (state.brand.size      && !state.brand.has(facet.brand))           return false;
    if (state.size.size       && !state.size.has(facet.size))             return false;
    if (state.priceMin !== null && facet.price < state.priceMin) return false;
    if (state.priceMax !== null && facet.price > state.priceMax) return false;
    return true;
  }

  // ── Apply: filter, sort, render side-effects ──────────────────────────────
  function apply() {
    const state = readState();

    // Filter.
    const matched = facetIndex.filter((f) => matches(f, state));

    // Sort the matched set; reorder DOM only when not in default order.
    const sorter = SORT_FNS[activeSort] || SORT_FNS.popular;
    if (activeSort !== 'popular' && activeSort !== 'new') {
      matched.sort(sorter);
    }

    // Hide everyone, then show + reorder the matched set.
    cards.forEach((c) => c.classList.add('is-hidden'));
    matched.forEach((f) => {
      f.el.classList.remove('is-hidden');
      grid.appendChild(f.el); // moving keeps DOM order in sync with sort
    });

    updateCount(matched.length);
    updateChips(state);
    updateLoadMore(matched.length);
    syncUrl(state);
  }

  // Mirror the current filter state back into the URL via replaceState so
  // refreshes / bookmarks / shares preserve the exact state. We use the
  // comma-list form (`?filter_gender=heren,unisex`) for compact, readable URLs;
  // hydrateFromUrl below understands both that and the `[]` array form.
  function syncUrl(state) {
    const params = new URLSearchParams();

    const setMulti = (key, set) => {
      if (set.size) params.set('filter_' + key, [...set].join(','));
    };
    setMulti('collection', state.collection);
    setMulti('gender',     state.gender);
    setMulti('family',     state.family);
    setMulti('brand',      state.brand);
    if (state.size.size) {
      params.set('filter_size', [...state.size].map((n) => `${n}ml`).join(','));
    }
    if (state.priceMin > state.priceGlobalMin) params.set('min_price', String(Math.round(state.priceMin)));
    if (state.priceMax < state.priceGlobalMax) params.set('max_price', String(Math.round(state.priceMax)));

    const query = params.toString();
    const next = window.location.pathname + (query ? '?' + query : '') + window.location.hash;
    if (next !== window.location.pathname + window.location.search + window.location.hash) {
      history.replaceState(null, '', next);
    }
  }

  function updateCount(n) {
    if (!resultsCount) return;
    const word = n === 1 ? 'resultaat' : 'resultaten';
    resultsCount.innerHTML = `<b>${n}</b> ${word}`;
  }

  function updateLoadMore(n) {
    // In placeholder mode the entire dataset is already in the DOM, so the
    // load-more progress bar always reflects "N of N — fully shown".
    if (loadMoreCount) loadMoreCount.innerHTML = `<b>${n}</b> van <b>${n}</b>`;
    if (loadMoreFill) loadMoreFill.style.width = '100%';
    if (loadMoreBtn) loadMoreBtn.disabled = true;
  }

  function updateChips(state) {
    if (!chipsRow) return;
    const chips = [];

    const pushSet = (facet, label) => {
      state[facet].forEach((slug) => {
        chips.push({
          facet,
          slug,
          label: labelLookup[`${facet}:${slug}`] || slug,
        });
      });
    };
    pushSet('collection');
    pushSet('gender');
    pushSet('family');
    pushSet('brand');

    state.size.forEach((n) => {
      chips.push({ facet: 'size', slug: `${n}ml`, label: `${n}ml` });
    });

    if (state.priceMin > state.priceGlobalMin || state.priceMax < state.priceGlobalMax) {
      chips.push({
        facet: 'price',
        slug: '',
        label: `€ ${Math.round(state.priceMin)} – € ${Math.round(state.priceMax)}`,
      });
    }

    if (!chips.length) {
      chipsRow.innerHTML = '';
      chipsRow.hidden = true;
      return;
    }

    chipsRow.hidden = false;
    chipsRow.innerHTML = `
      <span class="lenvy-chips__label">Filters</span>
      ${chips
        .map(
          (c) => `
            <button type="button" class="lenvy-chip" data-facet="${c.facet}" data-slug="${escapeAttr(c.slug)}">
              <span>${escapeHtml(c.label)}</span>
              <svg viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M1 1l8 8M9 1l-8 8"/></svg>
            </button>
          `,
        )
        .join('')}
      <button type="button" class="lenvy-chip-clear" data-clear-all>Alles wissen</button>
    `;

    chipsRow.querySelectorAll('.lenvy-chip').forEach((chip) => {
      chip.addEventListener('click', () => removeChip(chip.dataset.facet, chip.dataset.slug));
    });
    chipsRow.querySelector('[data-clear-all]')?.addEventListener('click', clearAll);
  }

  function removeChip(facet, slug) {
    if (facet === 'price') {
      // Reset slider to its global bounds.
      priceRoots.forEach((root) => {
        const min = parseFloat(root.dataset.min);
        const max = parseFloat(root.dataset.max);
        root.dataset.currentMin = min;
        root.dataset.currentMax = max;
        const minThumb = root.querySelector('[data-slider-thumb="min"]');
        const maxThumb = root.querySelector('[data-slider-thumb="max"]');
        const range = root.querySelector('[data-slider-range]');
        const inputMin = root.querySelector('[data-slider-input="min"]');
        const inputMax = root.querySelector('[data-slider-input="max"]');
        if (minThumb) minThumb.style.left = '0%';
        if (maxThumb) maxThumb.style.left = '100%';
        if (range) {
          range.style.left = '0%';
          range.style.width = '100%';
        }
        if (inputMin) inputMin.value = min;
        if (inputMax) inputMax.value = max;
        const labelMin = root.querySelector('[data-slider-label="min"]');
        const labelMax = root.querySelector('[data-slider-label="max"]');
        if (labelMin) labelMin.textContent = '€ ' + min;
        if (labelMax) labelMax.textContent = '€ ' + max;
      });
      apply();
      return;
    }
    // For size we stored the numeric ml; checkbox value is "30ml" etc.
    checkboxes.forEach((cb) => {
      const name = (cb.getAttribute('name') || '').replace(/\[\]$/, '');
      const cbFacet = name.replace(/^filter_/, '');
      if (cbFacet === facet && cb.value === slug) {
        cb.checked = false;
      }
    });
    apply();
  }

  function clearAll() {
    checkboxes.forEach((cb) => (cb.checked = false));
    removeChip('price'); // also resets price slider
  }

  // ── Wiring ────────────────────────────────────────────────────────────────

  // Prevent any form submit from doing a full reload.
  forms.forEach((f) => f.addEventListener('submit', (e) => e.preventDefault()));

  // Re-filter on every checkbox change.
  checkboxes.forEach((cb) => cb.addEventListener('change', apply));

  // Re-filter when the price slider commits.
  document.addEventListener('lenvy:price-change', apply);

  // Sort dropdown — we hijack the existing buttons before main.js' sort-no-reload
  // handler also runs (that one just updates the label, which is fine).
  if (sortDropdown) {
    sortDropdown.querySelectorAll('[data-sort-value]').forEach((btn) => {
      btn.addEventListener('click', () => {
        activeSort = btn.dataset.sortValue || 'popular';
        if (sortLabel) sortLabel.textContent = btn.querySelector('span')?.textContent?.trim() ?? sortLabel.textContent;
        apply();
      });
    });
  }

  // Hydrate from URL: a link like /shop/?filter_gender=heren should land
  // on the shop with the matching checkbox already ticked.
  hydrateFromUrl();

  // Initial render.
  apply();

  function hydrateFromUrl() {
    const params = new URLSearchParams(window.location.search);
    const hasFilterParam = [...params.keys()].some(
      (k) => k.startsWith('filter_') || k === 'min_price' || k === 'max_price',
    );
    if (!hasFilterParam) return;

    // Checkboxes — accept either ?filter_x=v or ?filter_x[]=v, comma-list ok.
    checkboxes.forEach((cb) => {
      const cbName = (cb.getAttribute('name') || '').replace(/\[\]$/, '');
      const values = [
        ...params.getAll(cbName),
        ...params.getAll(cbName + '[]'),
      ];
      const flat = values.flatMap((v) => v.split(',').map((s) => s.trim()).filter(Boolean));
      if (flat.includes(cb.value)) cb.checked = true;
    });

    // Price slider.
    const minParam = params.get('min_price');
    const maxParam = params.get('max_price');
    if (minParam || maxParam) {
      priceRoots.forEach((root) => {
        const globalMin = parseFloat(root.dataset.min);
        const globalMax = parseFloat(root.dataset.max);
        const span = globalMax - globalMin;
        const minVal = Math.max(globalMin, Math.min(globalMax, parseFloat(minParam ?? globalMin)));
        const maxVal = Math.max(globalMin, Math.min(globalMax, parseFloat(maxParam ?? globalMax)));
        const minPct = ((minVal - globalMin) / span) * 100;
        const maxPct = ((maxVal - globalMin) / span) * 100;

        const minThumb = root.querySelector('[data-slider-thumb="min"]');
        const maxThumb = root.querySelector('[data-slider-thumb="max"]');
        const range    = root.querySelector('[data-slider-range]');
        const minInput = root.querySelector('[data-slider-input="min"]');
        const maxInput = root.querySelector('[data-slider-input="max"]');
        const minLabel = root.querySelector('[data-slider-label="min"]');
        const maxLabel = root.querySelector('[data-slider-label="max"]');

        root.dataset.currentMin = String(minVal);
        root.dataset.currentMax = String(maxVal);
        if (minThumb) minThumb.style.left = minPct + '%';
        if (maxThumb) maxThumb.style.left = maxPct + '%';
        if (range) {
          range.style.left = minPct + '%';
          range.style.width = (maxPct - minPct) + '%';
        }
        if (minInput) {
          minInput.value = minVal;
          minInput.disabled = minVal <= globalMin;
        }
        if (maxInput) {
          maxInput.value = maxVal;
          maxInput.disabled = maxVal >= globalMax;
        }
        if (minLabel) minLabel.textContent = '€ ' + Math.round(minVal);
        if (maxLabel) maxLabel.textContent = '€ ' + Math.round(maxVal);
      });
    }
  }
}

// ── Tiny escaping helpers ────────────────────────────────────────────────────

function escapeHtml(s) {
  return String(s).replace(/[&<>"']/g, (c) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
  }[c]));
}

function escapeAttr(s) {
  return escapeHtml(s);
}
