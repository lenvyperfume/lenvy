/**
 * Product image gallery — thumbnail click swaps main image with cross-fade.
 *
 * Targets [data-product-gallery] wrappers rendered by
 * woocommerce/single-product/product-image.php
 */
export function initGallery() {
  document.querySelectorAll('[data-product-gallery]').forEach(initSingle);
}

function initSingle(gallery) {
  const mainImg = gallery.querySelector('[data-gallery-main]');
  const thumbs  = Array.from(gallery.querySelectorAll('[data-gallery-thumb]'));

  if (!mainImg || !thumbs.length) return;

  function activate(thumb) {
    const src = thumb.dataset.src;
    if (!src || mainImg.src === src) return;

    // Cross-fade.
    mainImg.style.opacity    = '0';
    mainImg.style.transition = 'opacity 200ms ease';

    const preload  = new Image();
    preload.onload = () => {
      mainImg.src            = src;
      mainImg.style.opacity  = '1';
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
    const idx     = thumbs.indexOf(focused);
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
}
