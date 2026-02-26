/**
 * Product image gallery — thumbnail click swaps main image with cross-fade.
 * Clicking the main image opens a lightweight full-screen lightbox.
 *
 * Targets [data-product-gallery] wrappers rendered by
 * woocommerce/single-product/product-image.php
 */
export function initGallery() {
  document.querySelectorAll('[data-product-gallery]').forEach(initSingle);
}

function initSingle(gallery) {
  const mainImg = gallery.querySelector('[data-gallery-main]');
  const thumbs = Array.from(gallery.querySelectorAll('[data-gallery-thumb]'));

  if (!mainImg) return;

  function activate(thumb) {
    const src = thumb.dataset.src;
    if (!src || mainImg.src === src) return;

    // Cross-fade.
    mainImg.style.opacity = '0';
    mainImg.style.transition = 'opacity 200ms ease';

    const preload = new Image();
    preload.onload = () => {
      mainImg.src = src;
      mainImg.style.opacity = '1';
    };
    preload.src = src;

    // Update active thumb border.
    thumbs.forEach((t) => {
      t.classList.remove('border-black');
      t.classList.add('border-transparent');
    });
    thumb.classList.remove('border-transparent');
    thumb.classList.add('border-black');
  }

  thumbs.forEach((thumb) => {
    thumb.addEventListener('click', () => activate(thumb));
  });

  // Keyboard: left/right arrows navigate thumbnails when a thumb is focused.
  gallery.addEventListener('keydown', (e) => {
    const focused = document.activeElement;
    const idx = thumbs.indexOf(focused);
    if (idx === -1) return;

    if (e.key === 'ArrowRight' && idx < thumbs.length - 1) {
      e.preventDefault();
      thumbs[idx + 1].focus();
      activate(thumbs[idx + 1]);
    } else if (e.key === 'ArrowLeft' && idx > 0) {
      e.preventDefault();
      thumbs[idx - 1].focus();
      activate(thumbs[idx - 1]);
    }
  });

  // Lightbox — clicking the main image wrapper opens a full-screen overlay.
  initLightbox(mainImg);
}

// ── Lightbox ─────────────────────────────────────────────────────────────────

function initLightbox(mainImg) {
  const wrapper = mainImg.parentElement;
  if (!wrapper) return;

  wrapper.style.cursor = 'zoom-in';

  let overlay = null;

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

    overlay.querySelector('[data-lb-close]').addEventListener('mouseenter', (e) => {
      e.currentTarget.style.color = '#fff';
    });
    overlay.querySelector('[data-lb-close]').addEventListener('mouseleave', (e) => {
      e.currentTarget.style.color = 'rgba(255,255,255,0.7)';
    });

    overlay.addEventListener('click', (e) => {
      if (e.target === overlay || e.target.closest('[data-lb-close]')) {
        closeLightbox();
      }
    });

    return overlay;
  }

  function openLightbox() {
    const el = getOverlay();
    const img = el.querySelector('[data-lb-img]');
    img.src = mainImg.src;
    img.alt = mainImg.alt;

    el.style.display = 'flex';
    // Allow display to take effect before fading in.
    requestAnimationFrame(() => {
      el.style.opacity = '1';
    });

    document.addEventListener('keydown', onKeydown);
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    if (!overlay) return;
    overlay.style.opacity = '0';
    setTimeout(() => {
      overlay.style.display = 'none';
    }, 200);
    document.removeEventListener('keydown', onKeydown);
    document.body.style.overflow = '';
  }

  function onKeydown(e) {
    if (e.key === 'Escape') closeLightbox();
  }

  wrapper.addEventListener('click', openLightbox);
}
