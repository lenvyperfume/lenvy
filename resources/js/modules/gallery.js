/**
 * Product gallery — Embla Carousel powered slider.
 *
 * - Drag/swipe to navigate
 * - Thumbnail + dot sync
 * - Keyboard arrow navigation
 * - Lightbox on click
 */

import EmblaCarousel from 'embla-carousel';

export function initGallery() {
  document.querySelectorAll('[data-gallery-slider]').forEach(initSlider);
}

function initSlider(slider) {
  const viewport = slider.querySelector('[data-gallery-viewport]');
  if (!viewport) return;

  const wrapper = slider.closest('[data-product-gallery]') || slider.parentElement;
  const thumbs = Array.from(wrapper.querySelectorAll('[data-gallery-thumb]'));
  const dots = Array.from(slider.querySelectorAll('[data-gallery-dot]'));

  // ── Init Embla ────────────────────────────────────────────────────────────
  const embla = EmblaCarousel(viewport, {
    loop: false,
    dragFree: false,
    containScroll: 'trimSnaps',
  });

  // ── Sync indicators on slide change ───────────────────────────────────────
  function onSelect() {
    const index = embla.selectedScrollSnap();

    thumbs.forEach((t, i) => {
      const isActive = i === index;
      t.classList.toggle('border-primary', isActive);
      t.classList.toggle('border-transparent', !isActive);
      t.classList.toggle('hover:border-neutral-300', !isActive);
    });

    dots.forEach((d, i) => {
      d.classList.toggle('is-active', i === index);
    });
  }

  embla.on('select', onSelect);
  onSelect(); // sync on init

  // ── Thumbnail clicks ──────────────────────────────────────────────────────
  thumbs.forEach((thumb) => {
    thumb.addEventListener('click', () => {
      const idx = parseInt(thumb.dataset.galleryThumb, 10);
      embla.scrollTo(idx);
    });
  });

  // ── Dot clicks ────────────────────────────────────────────────────────────
  dots.forEach((dot) => {
    dot.addEventListener('click', () => {
      const idx = parseInt(dot.dataset.galleryDot, 10);
      embla.scrollTo(idx);
    });
  });

  // ── Keyboard navigation ───────────────────────────────────────────────────
  slider.setAttribute('tabindex', '0');
  slider.style.outline = 'none';
  slider.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowRight') {
      e.preventDefault();
      embla.scrollNext();
    } else if (e.key === 'ArrowLeft') {
      e.preventDefault();
      embla.scrollPrev();
    }
  });

  // ── Lightbox ──────────────────────────────────────────────────────────────
  initLightbox(slider, embla);
}

// ── Lightbox ─────────────────────────────────────────────────────────────────

function initLightbox(slider, embla) {
  const slides = Array.from(slider.querySelectorAll('[data-gallery-slide] img'));
  if (!slides.length) return;

  let overlay = null;
  let lbIndex = 0;

  function getImages() {
    return slides.map((img) => ({ src: img.src, alt: img.alt }));
  }

  function getOverlay() {
    if (overlay) return overlay;

    overlay = document.createElement('div');
    overlay.setAttribute('role', 'dialog');
    overlay.setAttribute('aria-label', 'Image lightbox');
    overlay.setAttribute('aria-modal', 'true');
    overlay.style.cssText =
      'position:fixed;inset:0;z-index:200;background:rgba(0,0,0,0.92);' +
      'display:none;align-items:center;justify-content:center;padding:1.5rem;' +
      'opacity:0;transition:opacity 200ms ease;';

    overlay.innerHTML =
      '<button type="button" data-lb-close ' +
      'style="position:absolute;top:1rem;right:1rem;width:2.5rem;height:2.5rem;' +
      'display:flex;align-items:center;justify-content:center;' +
      'color:rgba(255,255,255,0.7);background:none;border:none;cursor:pointer;' +
      'transition:color 150ms ease;" aria-label="Close lightbox">' +
      '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">' +
      '<path d="M16 4L4 16M4 4l12 12"/>' +
      '</svg>' +
      '</button>' +
      '<img data-lb-img src="" alt="" style="max-width:100%;max-height:100%;object-fit:contain;user-select:none;">';

    document.body.appendChild(overlay);

    const closeBtn = overlay.querySelector('[data-lb-close]');
    closeBtn.addEventListener('mouseenter', (e) => (e.currentTarget.style.color = '#fff'));
    closeBtn.addEventListener('mouseleave', (e) => (e.currentTarget.style.color = 'rgba(255,255,255,0.7)'));

    overlay.addEventListener('click', (e) => {
      if (e.target === overlay || e.target.closest('[data-lb-close]')) closeLightbox();
    });

    return overlay;
  }

  function showImage(index) {
    const images = getImages();
    if (index < 0 || index >= images.length) return;
    lbIndex = index;
    const el = getOverlay();
    const img = el.querySelector('[data-lb-img]');
    img.src = images[index].src;
    img.alt = images[index].alt;
  }

  function openLightbox() {
    showImage(embla.selectedScrollSnap());
    const el = getOverlay();
    el.style.display = 'flex';
    requestAnimationFrame(() => (el.style.opacity = '1'));
    document.addEventListener('keydown', onKeydown);
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    if (!overlay) return;
    overlay.style.opacity = '0';
    setTimeout(() => (overlay.style.display = 'none'), 200);
    document.removeEventListener('keydown', onKeydown);
    document.body.style.overflow = '';
  }

  function onKeydown(e) {
    const images = getImages();
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowRight' && lbIndex < images.length - 1) showImage(lbIndex + 1);
    if (e.key === 'ArrowLeft' && lbIndex > 0) showImage(lbIndex - 1);
  }

  // Only open lightbox on click (not drag). Embla sets pointer-events
  // during drag, so a click that fires means it was a real tap/click.
  slider.addEventListener('click', (e) => {
    if (e.target.closest('[data-gallery-slide]') && !e.target.closest('button')) {
      openLightbox();
    }
  });
}
