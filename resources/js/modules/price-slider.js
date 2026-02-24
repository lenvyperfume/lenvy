/**
 * Dual-handle price range slider.
 *
 * Reads configuration from [data-price-slider] attributes.
 * Writes values to hidden inputs [data-slider-input="min|max"].
 * Updates display labels [data-slider-label="min|max"].
 * Submits the parent [data-filter-form] on mouse/touch release.
 */
export function initPriceSlider() {
	document.querySelectorAll('[data-price-slider]').forEach(initSingle);
}

function initSingle(root) {
	const globalMin = parseFloat(root.dataset.min);
	const globalMax = parseFloat(root.dataset.max);

	let currentMin = parseFloat(root.dataset.currentMin);
	let currentMax = parseFloat(root.dataset.currentMax);

	const track     = root.querySelector('[data-slider-track]');
	const range     = root.querySelector('[data-slider-range]');
	const thumbMin  = root.querySelector('[data-slider-thumb="min"]');
	const thumbMax  = root.querySelector('[data-slider-thumb="max"]');
	const inputMin  = root.querySelector('[data-slider-input="min"]');
	const inputMax  = root.querySelector('[data-slider-input="max"]');
	const labelMin  = root.querySelector('[data-slider-label="min"]');
	const labelMax  = root.querySelector('[data-slider-label="max"]');

	if (!track || !thumbMin || !thumbMax) return;

	const span = globalMax - globalMin;

	function pct(val) {
		return ((val - globalMin) / span) * 100;
	}

	function render() {
		const minPct = pct(currentMin);
		const maxPct = pct(currentMax);

		thumbMin.style.left = minPct + '%';
		thumbMax.style.left = maxPct + '%';

		if (range) {
			range.style.left  = minPct + '%';
			range.style.width = (maxPct - minPct) + '%';
		}

		if (inputMin) inputMin.value = currentMin;
		if (inputMax) inputMax.value = currentMax;

		// Update aria values.
		thumbMin.setAttribute('aria-valuenow', currentMin);
		thumbMax.setAttribute('aria-valuenow', currentMax);
	}

	// Update the displayed price labels via WC formatted price if available,
	// otherwise fall back to a simple currency prefix.
	function updateLabels() {
		if (!labelMin || !labelMax) return;
		// Use the already-rendered HTML from PHP for format hints.
		const fmt = (val) => {
			const sym = labelMin.textContent.replace(/[\d.,\s]/g, '').trim() || '€';
			return sym + Math.round(val);
		};
		labelMin.textContent = fmt(currentMin);
		labelMax.textContent = fmt(currentMax);
	}

	render();

	function dragThumb(thumb, isMin) {
		let isDragging = false;

		const start = (e) => {
			e.preventDefault();
			isDragging = true;
			document.addEventListener('mousemove', move);
			document.addEventListener('mouseup', stop);
			document.addEventListener('touchmove', move, { passive: false });
			document.addEventListener('touchend', stop);
		};

		const move = (e) => {
			if (!isDragging) return;
			const rect    = track.getBoundingClientRect();
			const clientX = e.touches ? e.touches[0].clientX : e.clientX;
			const rawPct  = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
			const rawVal  = globalMin + rawPct * span;
			const step    = span >= 1000 ? 10 : (span >= 100 ? 5 : 1);
			const snapped = Math.round(rawVal / step) * step;

			if (isMin) {
				currentMin = Math.max(globalMin, Math.min(snapped, currentMax - step));
			} else {
				currentMax = Math.min(globalMax, Math.max(snapped, currentMin + step));
			}

			render();
			updateLabels();
		};

		const stop = () => {
			if (!isDragging) return;
			isDragging = false;
			document.removeEventListener('mousemove', move);
			document.removeEventListener('mouseup', stop);
			document.removeEventListener('touchmove', move);
			document.removeEventListener('touchend', stop);
		};

		thumb.addEventListener('mousedown', start);
		thumb.addEventListener('touchstart', start, { passive: false });

		// Keyboard support.
		thumb.addEventListener('keydown', (e) => {
			const currentRange = span >= 1000 ? 10 : (span >= 100 ? 5 : 1);
			if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') {
				e.preventDefault();
				if (isMin) currentMin = Math.max(globalMin, currentMin - currentRange);
				else       currentMax = Math.max(currentMin + currentRange, currentMax - currentRange);
				render(); updateLabels();
			} else if (e.key === 'ArrowRight' || e.key === 'ArrowUp') {
				e.preventDefault();
				if (isMin) currentMin = Math.min(currentMax - currentRange, currentMin + currentRange);
				else       currentMax = Math.min(globalMax, currentMax + currentRange);
				render(); updateLabels();
			}
		});
	}

	dragThumb(thumbMin, true);
	dragThumb(thumbMax, false);
}
