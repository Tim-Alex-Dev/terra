import {throttle} from '../functions/throttle';

jQuery(document).ready(function ($) {
	"use strict";

	// .is-scrolled on <body> after 70px of scrolling
	const body = $('body');

	function checkScrollPosition() {
		if (body.hasClass('is-menu-open')) {
			return;
		}
		body.toggleClass('is-scrolled', window.scrollY > 70);
	}

	checkScrollPosition();
	window.addEventListener('scroll', throttle(checkScrollPosition, 100), {passive: true});

});
