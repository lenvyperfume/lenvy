/**
 * Header — scroll shadow + announcement bar dismiss.
 */
export function initHeader() {
  // Add shadow-sm to [data-header] when page is scrolled past 8px
  const header = document.querySelector('[data-header]');
  if (header) {
    const onScroll = () => header.classList.toggle('shadow-sm', window.scrollY > 8);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // Dismiss announcement bar
  document.querySelector('[data-dismiss-announcement]')?.addEventListener('click', function () {
    this.closest('[data-announcement]')?.remove();
  });
}
