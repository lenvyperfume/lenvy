/**
 * USP bar — Embla Carousel slider on mobile.
 *
 * Shows 1 item at a time with autoplay on screens < 1024px.
 * On desktop the bar is a static flex row, so this does nothing.
 */

import EmblaCarousel from 'embla-carousel';
import Autoplay from 'embla-carousel-autoplay';

export function initUspSlider() {
  const viewport = document.querySelector('[data-usp-viewport]');
  if (!viewport) return;

  let embla = null;
  const mql = window.matchMedia('(min-width: 1024px)');

  function init() {
    if (mql.matches) {
      // Desktop — destroy slider if active.
      if (embla) {
        embla.destroy();
        embla = null;
      }
      return;
    }

    // Mobile — init slider.
    if (embla) return;

    embla = EmblaCarousel(
      viewport,
      {
        loop: true,
        align: 'center',
        containScroll: false,
      },
      [Autoplay({ delay: 3000, stopOnInteraction: false })],
    );
  }

  init();
  mql.addEventListener('change', init);
}
