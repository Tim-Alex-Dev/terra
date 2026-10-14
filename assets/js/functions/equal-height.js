import vars from '../_vars';

/**
 * Same height for all matched elements: equalHeight('.post-card__title') or equalHeight('h2', '.container')
 * @param name - selector
 * @param container - optional
 * @param breakpoint - breakpoint name from _vars.js ('md', 'lg', 'xl') or a width in px (0 = all screens), from which we start equal height calculation.
 */
export const equalHeight = (element, container = 'body', breakpoint = 'lg') => {
	const minWidth = 'string' === typeof breakpoint ? vars.bp[breakpoint] : parseInt(breakpoint);
	if (null != element) {
		document.querySelectorAll(container).forEach(wrapper => {
			let tallest = 0;
			const els = wrapper.querySelectorAll(element);

			if (window.innerWidth >= minWidth) {
				els.forEach(el => el.style.removeProperty('height'));

				els.forEach(el => {
					const elHeight = el.offsetHeight;
					tallest = elHeight > tallest ? elHeight : tallest;
				});

				els.forEach(el => el.style.height = tallest + 'px');

			} else {
				els.forEach(el => el.style.removeProperty('height'));
			}
		});

	}
};
