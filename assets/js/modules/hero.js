import Swiper from 'swiper';
import {Navigation, Pagination, Autoplay, EffectFade, A11y} from 'swiper/modules';

jQuery(document).ready(function () {
	"use strict";

	// Hero slider: fade; one slide = no loop / autoplay; no autoplay with reduced motion
	const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	document.querySelectorAll('.js-hero-slider').forEach(slider => {
		const multiple = slider.querySelectorAll('.swiper-slide').length > 1;

		new Swiper(slider, {
			modules: [Navigation, Pagination, Autoplay, EffectFade, A11y],
			effect: 'fade',
			fadeEffect: {
				crossFade: true,
			},
			loop: multiple,
			speed: 800,
			autoplay: multiple && !reducedMotion ? {
				delay: 5000,
				disableOnInteraction: false,
				pauseOnMouseEnter: true,
			} : false,
			pagination: {
				el: slider.querySelector('.swiper-pagination'),
				clickable: true,
			},
			navigation: {
				nextEl: slider.querySelector('.swiper-button-next'),
				prevEl: slider.querySelector('.swiper-button-prev'),
			},
		});
	});

});
