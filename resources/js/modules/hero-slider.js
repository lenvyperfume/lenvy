/**
 * Hero slider — Embla Carousel on the right-hand panel of the homepage hero.
 *
 * Looks for:
 *   [data-hero-slider]           — viewport
 *   [data-hero-slider-dots] > *  — nav dots (must match slide count)
 *
 * Dots click → scrollTo; active dot reflects the current snap index.
 */

import EmblaCarousel from 'embla-carousel';
import Autoplay from 'embla-carousel-autoplay';

export function initHeroSlider() {
  const viewport = document.querySelector('[data-hero-slider]');
  if (!viewport) return;

  const dotsContainer = document.querySelector('[data-hero-slider-dots]');
  const dots = dotsContainer ? [...dotsContainer.children] : [];

  const embla = EmblaCarousel(
    viewport,
    { loop: true, align: 'start', containScroll: false },
    [Autoplay({ delay: 5000, stopOnInteraction: false })],
  );

  const ACTIVE = 'is-active';

  function updateDots() {
    const current = embla.selectedScrollSnap();
    dots.forEach((d, i) => d.classList.toggle(ACTIVE, i === current));
  }

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => embla.scrollTo(i));
  });

  embla.on('select', updateDots);
  embla.on('reInit', updateDots);
  updateDots();
}
