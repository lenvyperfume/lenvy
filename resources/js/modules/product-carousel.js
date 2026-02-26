/**
 * Product carousel — horizontal scroll with arrow navigation.
 */

export function initProductCarousel() {
  document.querySelectorAll('[data-product-carousel]').forEach((section) => {
    const track = section.querySelector('[data-carousel-track]');
    const prevBtn = section.querySelector('[data-carousel-prev]');
    const nextBtn = section.querySelector('[data-carousel-next]');

    if (!track) return;

    function updateArrows() {
      if (!prevBtn || !nextBtn) return;

      const { scrollLeft, scrollWidth, clientWidth } = track;
      prevBtn.disabled = scrollLeft <= 1;
      nextBtn.disabled = scrollLeft + clientWidth >= scrollWidth - 1;
    }

    function getScrollAmount() {
      const item = track.querySelector('.lenvy-product-carousel__item');
      if (!item) return track.clientWidth;
      const gap = parseFloat(getComputedStyle(track).gap) || 16;
      return (item.offsetWidth + gap) * 2;
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        track.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        track.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
      });
    }

    track.addEventListener('scroll', updateArrows, { passive: true });
    updateArrows();

    // Re-check on resize (debounced).
    let resizeTimer;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(updateArrows, 150);
    });
  });
}
