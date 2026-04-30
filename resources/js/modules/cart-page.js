/**
 * Cart placeholder page — fully client-side.
 *
 * Manages line-item qty + remove, free-shipping bar, promo code, totals,
 * empty state, header cart badge. State lives in this module only — no
 * persistence, no AJAX. Replace with WC fragments + AJAX once the real
 * cart exists.
 *
 * Promo codes (placeholder):
 *   LENVY15 → 15% off
 *   anything else → 10% off (so the input still feels responsive)
 */
import { updateCartCount } from './mini-cart.js';

const FREE_SHIP_THRESHOLD = 50;
const SHIPPING_COST = 4.95;
const VAT_RATE = 0.21;

export function initCartPage() {
  const root = document.querySelector('[data-cart-page]');
  if (!root) return;

  const itemsEl       = root.querySelector('[data-cart-items]');
  const emptyEl       = root.querySelector('[data-cart-empty]');
  const belowEl       = root.querySelector('[data-cart-below]');
  const summaryEl     = root.querySelector('[data-cart-summary]');
  const itemCountEl   = root.querySelector('[data-cart-item-count]');
  const totalsEl      = root.querySelector('[data-cart-totals]');
  const grandEl       = root.querySelector('[data-cart-grand]');
  const shipBarEl     = root.querySelector('[data-cart-ship-bar]');
  const promoRow      = root.querySelector('[data-cart-promo]');
  const promoInput    = root.querySelector('[data-cart-promo-input]');
  const promoBtn      = root.querySelector('[data-cart-promo-btn]');
  const clearAllBtn   = root.querySelector('[data-cart-clear-all]');

  if (!itemsEl) return;

  // ── State ────────────────────────────────────────────────────────────────
  // Each entry: { row: HTMLElement, id: number, price: number, qty: number }
  const items = Array.from(itemsEl.querySelectorAll('[data-cart-item]')).map((row) => ({
    row,
    id:    parseInt(row.dataset.id || '0', 10),
    price: parseFloat(row.dataset.price || '0'),
    qty:   parseInt(row.querySelector('[data-cart-qty-value]')?.textContent || '1', 10),
  }));

  let promo = null; // { code, percent }

  // ── Helpers ──────────────────────────────────────────────────────────────
  const eur = (n) => '€ ' + n.toFixed(2).replace('.', ',');
  const subtotal = () => items.reduce((s, it) => s + it.price * it.qty, 0);
  const discount = () => (promo ? Math.round((subtotal() * promo.percent) / 100) : 0);
  const shipping = () => (subtotal() - discount() >= FREE_SHIP_THRESHOLD ? 0 : SHIPPING_COST);
  const total    = () => subtotal() - discount() + shipping();
  const itemCount = () => items.reduce((s, it) => s + it.qty, 0);

  // ── Render: line item ─────────────────────────────────────────────────────
  function renderItem(it) {
    const lineTotal = it.price * it.qty;
    const lineEl = it.row.querySelector('[data-cart-line-total]');
    const eachEl = it.row.querySelector('[data-cart-each]');
    const qtyVal = it.row.querySelector('[data-cart-qty-value]');
    if (lineEl) lineEl.textContent = eur(lineTotal);
    if (qtyVal) qtyVal.textContent = String(it.qty);
    if (eachEl) {
      if (it.qty > 1) {
        eachEl.removeAttribute('hidden');
      } else {
        eachEl.setAttribute('hidden', '');
      }
    }
  }

  // ── Render: free-shipping bar ─────────────────────────────────────────────
  function renderShipBar() {
    if (!shipBarEl) return;
    const sub = subtotal() - discount();
    const remaining = FREE_SHIP_THRESHOLD - sub;
    const pct = Math.min(100, (sub / FREE_SHIP_THRESHOLD) * 100);

    if (remaining <= 0) {
      shipBarEl.innerHTML = `
        <p class="msg is-free">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
          <span>Je krijgt <b>gratis verzending</b>.</span>
        </p>
        <div class="bar is-full"><div class="fill" style="width:100%"></div></div>
      `;
    } else {
      shipBarEl.innerHTML = `
        <p class="msg">Nog <b>${eur(remaining)}</b> tot gratis verzending.</p>
        <div class="bar"><div class="fill" style="width:${pct}%"></div></div>
      `;
    }
  }

  // ── Render: totals + grand ────────────────────────────────────────────────
  function renderTotals() {
    if (!totalsEl) return;
    const sub  = subtotal();
    const disc = discount();
    const ship = shipping();
    const grand = total();
    const cnt = itemCount();
    const word = cnt === 1 ? 'artikel' : 'artikelen';
    const btw = ((sub - disc) * VAT_RATE) / (1 + VAT_RATE);

    totalsEl.innerHTML = `
      <div class="lenvy-cart-totals__line">
        <span>Subtotaal (${cnt} ${word})</span>
        <span class="v">${eur(sub)}</span>
      </div>
      ${
        disc > 0
          ? `<div class="lenvy-cart-totals__line is-discount"><span>Korting (${escapeHtml(promo.code)})</span><span>−${eur(disc)}</span></div>`
          : ''
      }
      <div class="lenvy-cart-totals__line">
        <span>Verzending</span>
        <span class="v">${ship === 0 ? 'Gratis' : eur(ship)}</span>
      </div>
      <div class="lenvy-cart-totals__line">
        <span>BTW (21%)</span>
        <span class="v">${eur(btw)}</span>
      </div>
    `;

    if (grandEl) {
      grandEl.innerHTML = `${eur(grand)} <small>incl. btw</small>`;
    }
  }

  // ── Render: promo state on input ──────────────────────────────────────────
  function renderPromoUI() {
    if (!promoRow || !promoInput || !promoBtn) return;
    if (promo) {
      promoRow.classList.add('is-applied');
      promoInput.value = promo.code;
      promoBtn.textContent = 'Verwijder';
    } else {
      promoRow.classList.remove('is-applied');
      promoInput.value = '';
      promoBtn.textContent = 'Toepassen';
    }
  }

  // ── Render: header item count ─────────────────────────────────────────────
  function renderHeader() {
    if (itemCountEl) itemCountEl.textContent = String(itemCount());
    updateCartCount(itemCount());
  }

  // ── Render: empty state toggling ──────────────────────────────────────────
  function renderEmptyState() {
    const isEmpty = items.length === 0;
    if (emptyEl) {
      if (isEmpty) emptyEl.removeAttribute('hidden');
      else emptyEl.setAttribute('hidden', '');
    }
    if (itemsEl)   itemsEl.toggleAttribute('hidden', isEmpty);
    if (belowEl)   belowEl.toggleAttribute('hidden', isEmpty);
    if (summaryEl) summaryEl.toggleAttribute('hidden', isEmpty);
  }

  // ── Top-level render ──────────────────────────────────────────────────────
  function render() {
    items.forEach(renderItem);
    renderShipBar();
    renderTotals();
    renderPromoUI();
    renderHeader();
    renderEmptyState();
  }

  // ── Mutations ─────────────────────────────────────────────────────────────
  function changeQty(id, delta) {
    const idx = items.findIndex((x) => x.id === id);
    if (idx === -1) return;
    const it = items[idx];
    it.qty = Math.max(0, it.qty + delta);
    if (it.qty === 0) removeItem(id);
    else render();
  }

  function removeItem(id) {
    const idx = items.findIndex((x) => x.id === id);
    if (idx === -1) return;
    items[idx].row.remove();
    items.splice(idx, 1);
    render();
  }

  function clearAll() {
    items.forEach((it) => it.row.remove());
    items.length = 0;
    render();
  }

  // ── Event delegation on the items list ────────────────────────────────────
  itemsEl.addEventListener('click', (e) => {
    const row = e.target.closest('[data-cart-item]');
    if (!row) return;
    const id = parseInt(row.dataset.id || '0', 10);

    const qtyAct = e.target.closest('[data-cart-qty-act]');
    if (qtyAct) {
      changeQty(id, qtyAct.dataset.cartQtyAct === 'inc' ? 1 : -1);
      return;
    }

    const act = e.target.closest('[data-cart-act]');
    if (act) {
      e.preventDefault();
      if (act.dataset.cartAct === 'remove') removeItem(id);
      // 'wish' is a no-op until we have a real wishlist backend.
    }
  });

  // ── Promo code apply / remove ─────────────────────────────────────────────
  if (promoBtn) {
    promoBtn.addEventListener('click', () => {
      if (promo) {
        promo = null;
      } else {
        const code = (promoInput?.value || '').trim().toUpperCase();
        if (!code) return;
        promo = code === 'LENVY15'
          ? { code, percent: 15 }
          : { code, percent: 10 };
      }
      render();
    });
  }
  if (promoInput) {
    promoInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        promoBtn?.click();
      }
    });
  }

  // ── Clear all ─────────────────────────────────────────────────────────────
  clearAllBtn?.addEventListener('click', clearAll);

  // Initial render syncs the header badge with the seeded items.
  render();
}

// ── Helpers ──────────────────────────────────────────────────────────────────

function escapeHtml(s) {
  return String(s).replace(/[&<>"']/g, (c) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
  }[c]));
}
