/**
 * Account / login / register — password reveal + has-value tracking.
 *
 * Runs on both the placeholder route at /mijn-account/ and the live WC
 * `myaccount/form-login.php` template. All hooks are scoped to the
 * `.lenvy-account` root so the script no-ops on other pages.
 */

export function initAccountPage() {
  const root = document.querySelector('.lenvy-account');
  if (!root) return;

  // ── Password reveal toggles ──────────────────────────────────────────────
  root.querySelectorAll('[data-pw-toggle]').forEach((btn) => {
    const target = document.getElementById(btn.dataset.pwToggle);
    if (!target) return;
    btn.addEventListener('click', () => {
      target.type = target.type === 'password' ? 'text' : 'password';
      btn.setAttribute(
        'aria-label',
        target.type === 'password' ? 'Toon wachtwoord' : 'Verberg wachtwoord'
      );
    });
  });

  // ── Has-value tracking ───────────────────────────────────────────────────
  // Toggle a `has-value` class on a wrapper element when the input has
  // any non-whitespace value. Used to lift filled fields from the warm
  // grey empty state to the white-with-hairline-border state. Applied
  // both to our own `.lenvy-account__fld` markup and to WC's default
  // `.form-row` (used by edit-address) so both forms read consistently.
  const trackHasValue = (input, wrapper) => {
    if (!wrapper) return;
    const update = () => wrapper.classList.toggle('has-value', input.value.trim().length > 0);
    input.addEventListener('input', update);
    input.addEventListener('change', update);
    update();
  };

  root.querySelectorAll('.lenvy-account__fld input, .lenvy-account__fld select').forEach((input) => {
    trackHasValue(input, input.closest('.lenvy-account__fld'));
  });

  root
    .querySelectorAll('.lenvy-account__form--wc-fields .form-row input.input-text, .lenvy-account__form--wc-fields .form-row select')
    .forEach((input) => {
      trackHasValue(input, input.closest('.form-row'));
    });
}
