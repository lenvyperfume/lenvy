/**
 * Placeholder search dataset + matcher.
 *
 * Ported from Claude Design `search-data.js`. Swap with a real AJAX-backed
 * source once WC products / brands / taxonomies are populated.
 */

export const SEARCH_DATA = {
  categories: [
    { name: 'Dames',   count: 412 },
    { name: 'Heren',   count: 286 },
    { name: 'Unisex',  count: 148 },
    { name: 'Niche',   count: 94 },
    { name: 'Samples', count: 520 },
  ],

  brands: [
    { name: 'MAISON VERDIER', style: 's1', meta: 'Parijs · 1912' },
    { name: 'Byredo',         style: 's2', meta: 'Stockholm' },
    { name: 'Aesop',          style: 's1', meta: 'Melbourne' },
    { name: 'Diptyque',       style: 's2', meta: 'Parijs · 1961' },
    { name: 'LE LABO',        style: 's1', meta: 'New York' },
    { name: 'Frédéric Malle', style: 's2', meta: 'Parijs' },
    { name: 'JUSBOX',         style: 's1', meta: 'Milaan' },
    { name: 'Amouage',        style: 's2', meta: 'Oman' },
    { name: 'Nishane',        style: 's2', meta: 'Istanbul' },
    { name: 'ORMONDE JAYNE',  style: 's1', meta: 'Londen' },
    { name: 'Xerjoff',        style: 's2', meta: 'Turijn' },
    { name: "Penhaligon's",   style: 's2', meta: 'Londen · 1870' },
  ],

  products: [
    { brand: 'Maison Verdier', name: 'Lumière Boisée', variant: '50ml · EdP',     price: '€ 128,00', tags: ['hout', 'warm', 'niche'],          categories: ['Unisex', 'Niche', 'Samples'] },
    { brand: 'Byredo',         name: 'Gypsy Water',    variant: '50ml · EdP',     price: '€ 165,00', tags: ['hout', 'fris'],                  categories: ['Unisex', 'Samples'] },
    { brand: 'Diptyque',       name: 'Philosykos',     variant: '75ml · EdT',     price: '€ 145,00', tags: ['vijg', 'groen'],                 categories: ['Unisex'] },
    { brand: 'Le Labo',        name: 'Santal 33',      variant: '50ml · EdP',     price: '€ 210,00', tags: ['hout', 'rokerig'],               categories: ['Unisex', 'Samples'] },
    { brand: 'Maison Verdier', name: 'Jasmin de Nuit', variant: '50ml · EdP',     price: '€ 142,00', tags: ['jasmijn', 'bloemig', 'nacht'],   categories: ['Dames', 'Niche'] },
    { brand: 'Frédéric Malle', name: 'Carnal Flower',  variant: '50ml · EdP',     price: '€ 285,00', tags: ['tuberoos', 'bloemig'],           categories: ['Dames', 'Niche'] },
    { brand: 'Aesop',          name: 'Hwyl',           variant: '50ml · EdP',     price: '€ 175,00', tags: ['hout', 'rokerig'],               categories: ['Heren'] },
    { brand: 'Amouage',        name: 'Interlude Man',  variant: '100ml · EdP',    price: '€ 330,00', tags: ['kruidig', 'rokerig'],            categories: ['Heren', 'Niche'] },
    { brand: 'Nishane',        name: 'Hacivat',        variant: '50ml · Extrait', price: '€ 195,00', tags: ['ananas', 'fris'],                categories: ['Unisex', 'Niche'] },
    { brand: 'Ormonde Jayne',  name: "Ta'if",          variant: '50ml · EdP',     price: '€ 175,00', tags: ['roos', 'kruidig'],               categories: ['Dames', 'Niche'] },
    { brand: 'Xerjoff',        name: 'Naxos',          variant: '50ml · EdP',     price: '€ 295,00', tags: ['tabak', 'zoet'],                 categories: ['Heren', 'Niche'] },
    { brand: 'Diptyque',       name: 'Do Son',         variant: '75ml · EdT',     price: '€ 145,00', tags: ['tuberoos', 'bloemig'],           categories: ['Dames'] },
    { brand: 'Maison Verdier', name: 'Ambre Fumée',    variant: '50ml · EdP',     price: '€ 152,00', tags: ['amber', 'rokerig'],              categories: ['Heren', 'Niche', 'Samples'] },
    { brand: 'Byredo',         name: 'Mojave Ghost',   variant: '50ml · EdP',     price: '€ 175,00', tags: ['hout', 'bloemig'],               categories: ['Unisex'] },
    { brand: 'Le Labo',        name: 'Rose 31',        variant: '50ml · EdP',     price: '€ 210,00', tags: ['roos', 'hout'],                  categories: ['Unisex'] },
    { brand: "Penhaligon's",   name: 'Halfeti',        variant: '75ml · EdP',     price: '€ 210,00', tags: ['roos', 'orientaals'],            categories: ['Unisex'] },
    { brand: 'Jusbox',         name: 'Night Balm',     variant: '78ml · EdP',     price: '€ 185,00', tags: ['amber', 'nacht'],                categories: ['Unisex', 'Niche'] },
    { brand: 'Aesop',          name: 'Rozu',           variant: '50ml · EdP',     price: '€ 175,00', tags: ['roos', 'fris'],                  categories: ['Dames'] },
  ],

  suggestions: [
    'jasmijn',
    'jasmin de nuit',
    'jasmin noir',
    'jasmijn sample',
    'jasmijn bloemig',
  ],
};

export function runSearch(q) {
  const query = (q || '').trim().toLowerCase();
  if (!query) return { q: '', products: [], brands: [], suggestions: [] };

  const products = SEARCH_DATA.products.filter(
    (p) =>
      p.brand.toLowerCase().includes(query) ||
      p.name.toLowerCase().includes(query) ||
      p.tags.some((t) => t.includes(query)) ||
      (p.categories || []).some((c) => c.toLowerCase().includes(query)),
  );

  const brands = SEARCH_DATA.brands.filter((b) => b.name.toLowerCase().includes(query));

  const suggSet = new Set();
  SEARCH_DATA.suggestions.forEach((s) => {
    if (s.toLowerCase().includes(query)) suggSet.add(s);
  });
  SEARCH_DATA.products.forEach((p) => {
    p.tags.forEach((t) => {
      if (t.includes(query)) suggSet.add(t);
    });
    if (p.name.toLowerCase().includes(query)) suggSet.add(p.name.toLowerCase());
  });
  const suggestions = [...suggSet].slice(0, 5);

  return { q: query, products, brands, suggestions };
}

export function highlight(text, q) {
  if (!q) return text;
  const re = new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'ig');
  return text.replace(re, '<mark>$1</mark>');
}
