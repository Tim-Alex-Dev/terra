/**
 * Loads modules (dist/js/modules/) only on pages that contain their selector, after the page has loaded:
 * when the browser is idle, or earlier on the first user interaction. loadModules({ '.selector': () => import('../modules/name') })
 */

export const loadModules = (modules) => {
	const events = ['scroll', 'click', 'touchstart', 'keydown'];
	let started = false;

	const start = () => {
		if (started) {
			return;
		}
		started = true;
		events.forEach(event => window.removeEventListener(event, start));

		Object.entries(modules).forEach(([selector, load]) => {
			if (document.querySelector(selector)) {
				load().catch(error => console.error(`Module for "${selector}" failed to load`, error));
			}
		});
	};

	// the first interaction loads the modules at once
	events.forEach(event => window.addEventListener(event, start, {once: true, passive: true}));

	// otherwise: after the "load" event, as soon as the browser is idle
	const whenIdle = () => ('requestIdleCallback' in window ? window.requestIdleCallback(start, {timeout: 2000}) : setTimeout(start, 200));
	if ('complete' === document.readyState) {
		whenIdle();
	} else {
		window.addEventListener('load', whenIdle, {once: true});
	}
};
