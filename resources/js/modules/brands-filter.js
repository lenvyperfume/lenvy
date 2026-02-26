/**
 * Brands page — instant client-side search filter.
 *
 * Filters the A-Z directory by brand name as the user types.
 * Hides featured brands strip and A-Z bar while a search query is active.
 */

export function initBrandsFilter() {
  const input = document.querySelector('[data-brands-search]');
  if (!input) return;

  const featured = document.querySelector('[data-brands-featured]');
  const azBar = document.querySelector('[data-brands-az-bar]');
  const directory = document.querySelector('[data-brands-directory]');
  const noResults = document.querySelector('[data-brands-no-results]');

  if (!directory) return;

  const items = directory.querySelectorAll('[data-brand-name]');
  const groups = directory.querySelectorAll('[data-letter-group]');

  let debounceTimer = null;

  input.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(filter, 150);
  });

  // Smooth-scroll letter links.
  document.addEventListener('click', (e) => {
    const link = e.target.closest('[data-letter-link]');
    if (!link) return;

    e.preventDefault();
    const letter = link.dataset.letterLink;
    const target = document.getElementById('letter-' + letter);
    if (!target) return;

    // Offset for sticky header + letter bar.
    const offset = 68 + 52;
    const top = target.getBoundingClientRect().top + window.scrollY - offset;
    window.scrollTo({ top, behavior: 'smooth' });
  });

  function filter() {
    const query = input.value.toLowerCase().trim();

    if (!query) {
      // Restore everything.
      items.forEach((item) => item.classList.remove('hidden'));
      groups.forEach((group) => group.classList.remove('hidden'));
      if (featured) featured.classList.remove('hidden');
      if (azBar) azBar.classList.remove('hidden');
      if (noResults) noResults.classList.add('hidden');
      return;
    }

    // Hide featured + A-Z bar while searching.
    if (featured) featured.classList.add('hidden');
    if (azBar) azBar.classList.add('hidden');

    let visibleCount = 0;

    items.forEach((item) => {
      const name = item.dataset.brandName || '';
      const match = name.includes(query);
      item.classList.toggle('hidden', !match);
      if (match) visibleCount++;
    });

    // Hide empty letter groups.
    groups.forEach((group) => {
      const hasVisible = group.querySelector('[data-brand-name]:not(.hidden)');
      group.classList.toggle('hidden', !hasVisible);
    });

    // No results message.
    if (noResults) {
      noResults.classList.toggle('hidden', visibleCount > 0);
    }
  }
}
