/**
 * Brands index — live search, type pills, A–Z rail.
 *
 * The full A–Z grid is server-rendered in templates/brands-placeholder.php;
 * this module just toggles `.is-hidden` on tiles + letter blocks based on
 * the active search/filter state. Keeps the markup index-friendly even
 * without JS.
 */
export function initBrandsPage() {
  const root = document.querySelector('[data-brands-page]');
  if (!root) return;

  const input    = root.querySelector('[data-brands-search-input]');
  const search   = root.querySelector('[data-brands-search]');
  const clearBtn = root.querySelector('[data-brands-search-clear]');
  const pills    = root.querySelectorAll('[data-brands-pill]');
  const count    = root.querySelector('[data-brands-count]');
  const empty    = root.querySelector('[data-brands-empty]');
  const reset    = root.querySelector('[data-brands-reset]');
  const tiles    = root.querySelectorAll('.lenvy-brand-tile');
  const blocks   = root.querySelectorAll('[data-brands-letter]');
  const rail     = root.querySelectorAll('[data-brands-rail-letter]');

  if (!tiles.length) return;

  const state = { q: '', type: 'all' };

  // ── Filtering ────────────────────────────────────────────────────────────

  function apply() {
    const q = state.q.trim().toLowerCase();
    const t = state.type;
    let visible = 0;
    const visibleLetters = new Set();
    const blockCounts = {};

    tiles.forEach((tile) => {
      const name  = tile.dataset.name || '';
      const type  = tile.dataset.type || '';
      const lett  = tile.dataset.letter || '';
      const ok = (t === 'all' || type === t) && (!q || name.includes(q));
      tile.classList.toggle('is-hidden', !ok);
      if (ok) {
        visible++;
        visibleLetters.add(lett);
        blockCounts[lett] = (blockCounts[lett] || 0) + 1;
      }
    });

    blocks.forEach((block) => {
      const lett = block.dataset.brandsLetter;
      const has = visibleLetters.has(lett);
      block.classList.toggle('is-hidden', !has);
      if (has) {
        const meta = block.querySelector('[data-brands-letter-meta]');
        if (meta) {
          const n = blockCounts[lett] || 0;
          const tpl = n === 1 ? meta.dataset.template : meta.dataset.templatePlural;
          meta.textContent = (tpl || '%d').replace('%d', n);
        }
      }
    });

    rail.forEach((a) => {
      a.classList.toggle('is-dim', !visibleLetters.has(a.dataset.brandsRailLetter));
    });

    if (count) {
      const word = visible === 1 ? 'merk' : 'merken';
      count.innerHTML = `<b>${visible}</b> ${word}`;
    }
    if (empty) empty.hidden = visible !== 0;
  }

  // ── Wiring ───────────────────────────────────────────────────────────────

  if (input) {
    input.addEventListener('input', () => {
      state.q = input.value || '';
      if (search) search.classList.toggle('has-text', state.q.length > 0);
      apply();
    });
  }

  if (clearBtn && input) {
    clearBtn.addEventListener('click', () => {
      input.value = '';
      state.q = '';
      if (search) search.classList.remove('has-text');
      apply();
      input.focus();
    });
  }

  pills.forEach((p) => {
    p.addEventListener('click', () => {
      pills.forEach((x) => {
        x.classList.remove('is-active');
        x.setAttribute('aria-selected', 'false');
      });
      p.classList.add('is-active');
      p.setAttribute('aria-selected', 'true');
      state.type = p.dataset.type || 'all';
      apply();
    });
  });

  if (reset) {
    reset.addEventListener('click', () => {
      state.q = '';
      state.type = 'all';
      if (input) input.value = '';
      if (search) search.classList.remove('has-text');
      pills.forEach((p) => {
        const isAll = p.dataset.type === 'all';
        p.classList.toggle('is-active', isAll);
        p.setAttribute('aria-selected', isAll ? 'true' : 'false');
      });
      apply();
    });
  }

  // ── Letter rail: jump-to-letter + active highlight on scroll ─────────────

  rail.forEach((a) => {
    a.addEventListener('click', (e) => {
      if (a.classList.contains('is-dim')) {
        e.preventDefault();
        return;
      }
      const target = document.getElementById(a.getAttribute('href').slice(1));
      if (!target) return;
      e.preventDefault();
      const offset = 200;
      const top = target.getBoundingClientRect().top + window.scrollY - offset;
      window.scrollTo({ top, behavior: 'smooth' });
    });
  });

  function updateActiveLetter() {
    const y = window.scrollY + 240;
    let active = null;
    blocks.forEach((b) => {
      if (b.classList.contains('is-hidden')) return;
      if (b.offsetTop <= y) active = b.dataset.brandsLetter;
    });
    rail.forEach((a) => {
      a.classList.toggle('is-active', a.dataset.brandsRailLetter === active);
    });
  }

  let scrollRaf = 0;
  window.addEventListener(
    'scroll',
    () => {
      if (scrollRaf) return;
      scrollRaf = requestAnimationFrame(() => {
        scrollRaf = 0;
        updateActiveLetter();
      });
    },
    { passive: true },
  );

  apply();
  updateActiveLetter();
}
