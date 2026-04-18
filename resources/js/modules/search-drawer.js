/**
 * Lenvy — full-screen editorial search drawer.
 * Ported from Claude Design `search-drawer.js`.
 *
 * Usage:
 *   initSearchDrawer({ triggers: '[data-search-trigger]' });
 */

import { SEARCH_DATA, runSearch, highlight } from './search-data.js';

const ICONS = {
  search:
    '<svg class="lv-search-ico" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>',
  trend:
    '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 17l6-6 4 4 7-8"/><path d="M14 7h6v6"/></svg>',
  arr:
    '<svg width="16" height="11" viewBox="0 0 14 10" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><path d="M1 5h12m0 0L9 1m4 4L9 9"/></svg>',
};

function el(tag, attrs = {}, ...children) {
  const n = document.createElement(tag);
  for (const k in attrs) {
    if (k === 'class') n.className = attrs[k];
    else if (k === 'html') n.innerHTML = attrs[k];
    else if (k.startsWith('on')) n.addEventListener(k.slice(2), attrs[k]);
    else n.setAttribute(k, attrs[k]);
  }
  children.flat().forEach((c) => {
    if (c == null || c === false) return;
    n.appendChild(typeof c === 'string' ? document.createTextNode(c) : c);
  });
  return n;
}

class SearchDrawer {
  constructor(opts = {}) {
    this.opts = Object.assign(
      {
        host: document.body,
        triggers: '[data-search-trigger]',
      },
      opts,
    );

    this.state = {
      open: false,
      query: '',
      results: null,
      focus: { group: null, idx: -1 },
    };

    this._init();
  }

  _init() {
    this.backdrop = el('div', { class: 'lv-backdrop' });
    this.drawer = el('div', {
      class: 'lv-drawer',
      role: 'dialog',
      'aria-modal': 'true',
      'aria-label': 'Zoeken',
    });
    this.opts.host.appendChild(this.backdrop);
    this.opts.host.appendChild(this.drawer);

    this._buildShell();

    document.querySelectorAll(this.opts.triggers).forEach((t) => {
      t.addEventListener('click', (e) => {
        e.preventDefault();
        this.open();
      });
      t.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          this.open();
        }
      });
    });

    this.backdrop.addEventListener('click', () => this.close());

    document.addEventListener('keydown', (e) => {
      if (!this.state.open) return;
      if (e.key === 'Escape') {
        e.preventDefault();
        this.close();
      }
      if ((e.key === 'ArrowDown' || e.key === 'ArrowUp') && this.state.results) {
        e.preventDefault();
        this._moveFocus(e.key === 'ArrowDown' ? 1 : -1);
      }
    });
  }

  _buildShell() {
    this.input = el('input', {
      type: 'text',
      placeholder: 'Zoek naar geuren, merken of geurfamilies…',
      autocomplete: 'off',
      'aria-label': 'Zoeken',
    });
    this.clearBtn = el('button', { class: 'lv-clear', type: 'button' }, 'Wissen');
    this.closeBtn = el(
      'button',
      { class: 'lv-close', type: 'button' },
      'Sluiten',
    );

    const cfgLogo = (window.lenvyAjax && window.lenvyAjax.logo) || {};
    const brand = el('a', {
      class: 'lv-brand',
      href: '/',
      'aria-label': cfgLogo.alt || 'Lenvy',
    });
    if (cfgLogo.url) {
      brand.appendChild(
        el('img', {
          src: cfgLogo.url,
          alt: cfgLogo.alt || '',
          class: 'lv-brand-logo',
        }),
      );
    } else {
      brand.appendChild(document.createTextNode('Lenvy'));
      brand.appendChild(el('span', { class: 'dot' }));
    }

    this.top = el(
      'div',
      { class: 'lv-drawer-top' },
      brand,
      el(
        'div',
        { class: 'lv-input-row' },
        el('span', { html: ICONS.search }).firstChild,
        this.input,
        this.clearBtn,
      ),
      this.closeBtn,
    );

    this.body = el('div', { class: 'lv-body' });

    this.drawer.appendChild(this.top);
    this.drawer.appendChild(this.body);

    this.input.addEventListener('input', (e) => {
      this.state.query = e.target.value;
      this.state.focus = { group: null, idx: -1 };
      this.clearBtn.classList.toggle('on', !!this.state.query);
      this._render();
    });

    this.clearBtn.addEventListener('click', () => {
      this.state.query = '';
      this.input.value = '';
      this.clearBtn.classList.remove('on');
      this._render();
      this.input.focus();
    });

    this.closeBtn.addEventListener('click', () => this.close());
  }

  open() {
    if (this.state.open) return;
    this.state.open = true;
    this.state.query = '';
    this.input.value = '';
    this.clearBtn.classList.remove('on');
    this._render();
    document.body.style.overflow = 'hidden';
    this.backdrop.classList.add('on');
    setTimeout(() => {
      this.drawer.classList.add('open');
      this.input.focus();
    }, 20);
  }

  close() {
    if (!this.state.open) return;
    this.state.open = false;
    this.drawer.classList.remove('open');
    this.backdrop.classList.remove('on');
    document.body.style.overflow = '';
  }

  _render() {
    const s = this.state;
    this.body.innerHTML = '';

    if (!s.query) {
      this.body.classList.remove('single');
      this._renderEmpty();
      return;
    }

    s.results = runSearch(s.query);
    const { products, brands, suggestions } = s.results;

    if (!products.length && !brands.length && !suggestions.length) {
      this.body.classList.add('single');
      this._renderNoResults(s.query);
      return;
    }

    this.body.classList.remove('single');
    this._renderResults();
  }

  _renderEmpty() {
    const left = el('div');
    left.appendChild(el('div', { class: 'lv-sec-label' }, 'Categorieën'));

    const cats = el('div', { class: 'lv-categories' });
    SEARCH_DATA.categories.forEach((c) => {
      const row = el(
        'a',
        { class: 'lv-cat-row', href: '#' },
        el('span', { class: 'n' }, c.name),
        el(
          'span',
          { class: 'side' },
          el('span', { class: 'c' }, c.count + ' geuren'),
          el('span', { class: 'arr', html: ICONS.arr }),
        ),
      );
      row.addEventListener('click', (e) => {
        e.preventDefault();
        this.state.query = c.name;
        this.input.value = c.name;
        this.clearBtn.classList.add('on');
        this._render();
        this.input.focus();
      });
      cats.appendChild(row);
    });
    left.appendChild(cats);

    const right = el('div');
    right.appendChild(el('div', { class: 'lv-sec-label' }, 'Top merken'));
    const grid = el('div', { class: 'lv-brand-grid' });
    SEARCH_DATA.brands.slice(0, 8).forEach((b) => {
      grid.appendChild(
        el(
          'a',
          { class: 'lv-brand-tile', href: '#' },
          el('span', { class: 'n ' + b.style }, b.name),
          el('span', { class: 'm' }, b.meta),
        ),
      );
    });
    right.appendChild(grid);

    this.body.appendChild(left);
    this.body.appendChild(right);
  }

  _renderResults() {
    const s = this.state;
    const { products, brands, suggestions, q } = s.results;

    const left = el('div');

    if (suggestions.length) {
      left.appendChild(el('div', { class: 'lv-sec-label' }, 'Suggesties'));
      const sg = el('div', { class: 'lv-sugg' });
      suggestions.forEach((su, i) => {
        const row = el('a', {
          class:
            'lv-sugg-item' +
            (s.focus.group === 'sugg' && s.focus.idx === i ? ' focused' : ''),
          href: '#',
          'data-g': 'sugg',
          'data-i': i,
          html: ICONS.trend + '<span>' + highlight(su, q) + '</span>',
        });
        row.addEventListener('click', (e) => {
          e.preventDefault();
          this.state.query = su;
          this.input.value = su;
          this.clearBtn.classList.add('on');
          this._render();
        });
        sg.appendChild(row);
      });
      left.appendChild(sg);
    }

    if (brands.length) {
      left.appendChild(el('div', { class: 'lv-sec-label' }, 'Merken'));
      const bm = el('div', { class: 'lv-brand-matches' });
      brands.slice(0, 6).forEach((b, i) => {
        const row = el('a', {
          class:
            'lv-brand-match' +
            (s.focus.group === 'brand' && s.focus.idx === i ? ' focused' : ''),
          href: '#',
          'data-g': 'brand',
          'data-i': i,
          html:
            '<span class="bname">' +
            highlight(b.name, q) +
            '</span><span class="bmeta">' +
            b.meta +
            '</span>',
        });
        bm.appendChild(row);
      });
      left.appendChild(bm);
    }

    const right = el('div');
    right.appendChild(
      el(
        'div',
        { class: 'lv-sec-label' },
        products.length + (products.length === 1 ? ' product gevonden' : ' producten gevonden'),
      ),
    );

    const list = el('div', { class: 'lv-prod-list' });
    products.slice(0, 6).forEach((p, i) => {
      const row = el('a', {
        class:
          'lv-prod-row' +
          (s.focus.group === 'prod' && s.focus.idx === i ? ' focused' : ''),
        href: '#',
        'data-g': 'prod',
        'data-i': i,
      });
      row.appendChild(el('div', { class: 'img' }));
      const info = el('div', { class: 'info' });
      info.innerHTML =
        '<span class="brand">' +
        highlight(p.brand, q) +
        '</span><span class="name">' +
        highlight(p.name, q) +
        '</span><span class="meta">' +
        p.variant +
        '</span>';
      row.appendChild(info);
      row.appendChild(el('span', { class: 'price' }, p.price));
      list.appendChild(row);
    });
    right.appendChild(list);

    if (products.length > 6) {
      right.appendChild(
        el(
          'a',
          { class: 'lv-see-all', href: '#' },
          'Bekijk alle ' + products.length + ' resultaten',
          el('span', { html: ICONS.arr }),
        ),
      );
    }

    this.body.appendChild(left);
    this.body.appendChild(right);
  }

  _renderNoResults(q) {
    const box = el(
      'div',
      { class: 'lv-no-results' },
      el('h3', {}, 'Geen resultaten'),
      el(
        'p',
        {},
        'We vonden niets voor "' + q + '". Probeer een andere term of bekijk de bestsellers.',
      ),
      el(
        'a',
        { class: 'lv-see-all', href: '#' },
        'Ga naar bestsellers',
        el('span', { html: ICONS.arr }),
      ),
    );
    this.body.appendChild(box);
  }

  _moveFocus(dir) {
    const r = this.state.results;
    if (!r) return;

    const groups = [];
    if (r.suggestions.length) groups.push({ g: 'sugg', len: Math.min(r.suggestions.length, 5) });
    if (r.brands.length) groups.push({ g: 'brand', len: Math.min(r.brands.length, 6) });
    if (r.products.length) groups.push({ g: 'prod', len: Math.min(r.products.length, 6) });
    if (!groups.length) return;

    const flat = [];
    groups.forEach((g) => {
      for (let i = 0; i < g.len; i++) flat.push({ g: g.g, i });
    });

    let cur = flat.findIndex(
      (x) => x.g === this.state.focus.group && x.i === this.state.focus.idx,
    );
    cur = (cur + dir + flat.length) % flat.length;
    this.state.focus = { group: flat[cur].g, idx: flat[cur].i };
    this._render();
  }
}

export function initSearchDrawer(opts = {}) {
  return new SearchDrawer(opts);
}
