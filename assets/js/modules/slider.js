import Swiper from 'swiper';
import {Navigation, Pagination, Autoplay, A11y} from 'swiper/modules'; // only the used modules (+ their CSS in 3-vendors/_swiper-bundle.scss)

jQuery(document).ready(function ($) {
	"use strict";

	// .swiper-images: image slider
	document.querySelectorAll('.swiper-images').forEach(slider => {
		new Swiper(slider, {
			modules: [Autoplay, Pagination, Navigation, A11y],
			loop: true,
			autoplay: {
				delay: 2500,
				disableOnInteraction: false,
			},
			pagination: {
				el: slider.querySelector('.swiper-pagination'),
				clickable: true,
			},
			navigation: {
				nextEl: slider.querySelector('.swiper-button-next'),
				prevEl: slider.querySelector('.swiper-button-prev'),
			}
		});
	});

});
