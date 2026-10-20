import Swiper from 'swiper';
import {Autoplay, A11y} from 'swiper/modules';

// Partnership: endless rows (autoplay without delay + linear easing in the SCSS); reduced motion = swipe only
jQuery(document).ready(function ($) {
	"use strict";

	const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	document.querySelectorAll('.m-partnership__swiper').forEach(slider => {
		new Swiper(slider, {
			modules: [Autoplay, A11y],
			slidesPerView: 'auto',
			spaceBetween: 20,
			loop: true,
			speed: 6000,
			allowTouchMove: reducedMotion,
			autoplay: reducedMotion ? false : {
				delay: 0,
				disableOnInteraction: false,
				pauseOnMouseEnter: false,
			},
		});
	});

});
