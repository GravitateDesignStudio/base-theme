export function enableNavButtons(embla, prevEl, nextEl) {
	if (!embla) {
		return;
	}

	if (Array.isArray(prevEl)) {
		prevEl.forEach((el) => el.addEventListener('click', embla.scrollPrev, false));
	} else {
		prevEl.addEventListener('click', embla.scrollPrev, false);
	}

	if (Array.isArray(nextEl)) {
		nextEl.forEach((el) => el.addEventListener('click', embla.scrollNext, false));
	} else {
		nextEl.addEventListener('click', embla.scrollNext, false);
	}
}

export function enableFadeTransition(embla, emblaRootEl) {
	if (!embla || !emblaRootEl) {
		return;
	}

	embla.dangerouslyGetEngine().translate.toggleActive(false);

	emblaRootEl.classList.add('embla--fade');
}
