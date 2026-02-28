/**
 * Variation tiles — syncs visible tile buttons with WC's hidden <select>.
 *
 * Douglas/Deloox pattern: one tile is ALWAYS selected — there is no
 * "unselected" state.  On reset we re-select the first tile.
 *
 * WC's add-to-cart-variation.js uses jQuery event delegation on
 * `.variations select` change events, so we must trigger via jQuery.
 */

export function initVariationTiles() {
  if (typeof jQuery === 'undefined') return;

  const form = document.querySelector('.variations_form');
  if (!form) return;

  const $form = jQuery(form);
  const tiles = form.querySelectorAll('.lenvy-variation-tile');
  if (!tiles.length) return;

  /**
   * Select first in-stock tile in each attribute group.
   */
  function selectFirstTile() {
    form.querySelectorAll('.lenvy-variation-tiles').forEach((group) => {
      const first = group.querySelector('.lenvy-variation-tile:not(.is-oos)');
      if (first) {
        const attrKey = first.dataset.attribute;
        const value = first.dataset.value;
        const $select = jQuery(`select[name="${attrKey}"]`, form);
        $select.val(value).trigger('change');
        setActiveTile(form, attrKey, value);
      }
    });
  }

  // ── Click handler: tile → hidden select (via jQuery .trigger) ─────────────
  form.addEventListener('click', (e) => {
    const tile = e.target.closest('.lenvy-variation-tile');
    if (!tile || tile.classList.contains('is-oos')) return;

    const attrKey = tile.dataset.attribute;
    const value = tile.dataset.value;

    jQuery(`select[name="${attrKey}"]`, form).val(value).trigger('change');
    setActiveTile(form, attrKey, value);
  });

  // On reset (WC fires this during init AND on "Clear" click),
  // always re-select the first tile — never leave tiles unselected.
  $form.on('reset_data', () => {
    setTimeout(selectFirstTile, 0);
  });

  // ── Initial selection ─────────────────────────────────────────────────────
  // setTimeout(0) ensures we run after WC's own DOMContentLoaded init.
  setTimeout(selectFirstTile, 0);
}

/**
 * Mark one tile as active within its attribute group.
 */
function setActiveTile(form, attrKey, value) {
  form.querySelectorAll(`.lenvy-variation-tile[data-attribute="${attrKey}"]`).forEach((t) => {
    const isActive = t.dataset.value === value;
    t.classList.toggle('is-active', isActive);
    t.setAttribute('aria-checked', isActive ? 'true' : 'false');
  });
}
