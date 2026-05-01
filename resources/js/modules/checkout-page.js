/**
 * Checkout placeholder page — fully client-side.
 *
 * Wires up the billing-address toggle, payment-method picker (iDEAL bank
 * grid + collapsible card form), promo code, has-value tracking on inputs,
 * and the place-order pseudo submit. State lives in this module only; no
 * persistence, no AJAX. Replace once real WC checkout is wired up.
 *
 * Promo codes (placeholder):
 *   LENVY15        → 15% off
 *   anything else  → 10% off (so the input still feels responsive)
 */

const VAT_RATE = 0.21;

const eur = (n) => '€ ' + n.toFixed(2).replace('.', ',');

export function initCheckoutPage() {
  const root = document.querySelector('[data-checkout-page]');
  if (!root) return;

  // ── Billing toggle ────────────────────────────────────────────────────────
  const billingCb = root.querySelector('[data-billing-cb]');
  const billingBlock = root.querySelector('[data-billing-block]');
  if (billingCb && billingBlock) {
    billingCb.addEventListener('change', () => {
      billingBlock.classList.toggle('is-open', billingCb.checked);
    });
  }

  // ── Payment method picker ────────────────────────────────────────────────
  const methodsRoot = root.querySelector('[data-pay-methods]');
  const methods = methodsRoot ? Array.from(methodsRoot.querySelectorAll('.lenvy-checkout__pay-method')) : [];
  const placeOrderLabel = root.querySelector('[data-place-order-label]');

  function selectPM(pm) {
    methods.forEach((m) => m.classList.toggle('is-active', m.dataset.pm === pm));
    const radio = methodsRoot?.querySelector(`.lenvy-checkout__pay-method[data-pm="${pm}"] input[type="radio"]`);
    if (radio) radio.checked = true;
  }

  methods.forEach((m) => {
    m.addEventListener('click', (e) => {
      // Don't toggle if click was inside an input or bank button — let those work normally.
      if (e.target.closest('input, [data-banks] button, .lenvy-checkout__card-form')) return;
      selectPM(m.dataset.pm);
    });
  });

  // ── iDEAL bank picker ────────────────────────────────────────────────────
  const banks = root.querySelectorAll('[data-banks] button');
  banks.forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      banks.forEach((b) => b.classList.remove('is-on'));
      btn.classList.add('is-on');
    });
  });

  // ── Totals + promo ───────────────────────────────────────────────────────
  const summaryEl = root.querySelector('[data-checkout-summary]');
  const totalsEl = root.querySelector('[data-totals]');
  const grandEl = root.querySelector('[data-grand]');
  const promoInput = root.querySelector('[data-promo-input]');
  const promoBtn = root.querySelector('[data-promo-btn]');
  const promoRow = root.querySelector('[data-promo-row]');

  const subtotal = parseFloat(summaryEl?.dataset.subtotal || '0');
  const vatRate = parseFloat(summaryEl?.dataset.vatRate || String(VAT_RATE));
  let promo = null; // { code, percent }

  function renderTotals() {
    if (!totalsEl || !grandEl) return;
    const disc = promo ? Math.round((subtotal * promo.percent) / 100) : 0;
    const ship = 0; // free
    const grand = subtotal - disc + ship;
    const btw = (grand * vatRate) / (1 + vatRate);

    totalsEl.innerHTML = `
      <div class="lenvy-checkout__totals-line">
        <span>Subtotaal</span><span class="v">${eur(subtotal)}</span>
      </div>
      ${
        disc > 0
          ? `<div class="lenvy-checkout__totals-line is-discount"><span>Korting (${promo.code})</span><span>−${eur(disc)}</span></div>`
          : ''
      }
      <div class="lenvy-checkout__totals-line">
        <span>Verzending</span><span class="v lenvy-checkout__totals-free">Gratis</span>
      </div>
      <div class="lenvy-checkout__totals-line">
        <span>BTW (21%)</span><span class="v">${eur(btw)}</span>
      </div>
    `;
    grandEl.innerHTML = `${eur(grand)}<small>incl. btw</small>`;
    if (placeOrderLabel) placeOrderLabel.textContent = `Bevestig en betaal · ${eur(grand)}`;
  }

  if (promoBtn && promoInput && promoRow) {
    promoBtn.addEventListener('click', () => {
      if (promo) {
        promo = null;
        promoRow.classList.remove('is-applied');
        promoInput.value = '';
        promoBtn.textContent = 'Toepassen';
      } else {
        const code = promoInput.value.trim().toUpperCase();
        if (!code) return;
        promo = { code, percent: code === 'LENVY15' ? 15 : 10 };
        promoRow.classList.add('is-applied');
        promoInput.value = code;
        promoBtn.textContent = 'Verwijder';
      }
      renderTotals();
    });

    promoInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        promoBtn.click();
      }
    });
  }

  // ── Has-value tracking ───────────────────────────────────────────────────
  root.querySelectorAll('.lenvy-checkout__fld input, .lenvy-checkout__fld select').forEach((el) => {
    const track = () => {
      const fld = el.closest('.lenvy-checkout__fld');
      if (!fld) return;
      const v = (el.value || '').trim();
      fld.classList.toggle('has-value', !!v);
    };
    el.addEventListener('input', track);
    el.addEventListener('change', track);
  });

  // ── Place order (demo) ───────────────────────────────────────────────────
  const placeOrderBtn = root.querySelector('[data-place-order]');
  if (placeOrderBtn && placeOrderLabel) {
    placeOrderBtn.addEventListener('click', () => {
      placeOrderLabel.textContent = 'Verwerken…';
      placeOrderBtn.disabled = true;
      const successUrl = placeOrderBtn.dataset.successUrl;
      setTimeout(() => {
        if (successUrl) {
          window.location.href = successUrl;
        } else {
          placeOrderLabel.textContent = '✓ Bestelling geplaatst';
          placeOrderBtn.disabled = false;
        }
      }, 800);
    });
  }
}
