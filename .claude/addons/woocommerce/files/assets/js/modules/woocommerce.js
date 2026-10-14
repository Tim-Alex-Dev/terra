import {throttle} from '../functions/throttle';
import Swiper from 'swiper';
import {Navigation, Pagination, Thumbs, A11y} from 'swiper/modules'; // import only the modules you need, and pass them to `modules` option of the slider
// available Swiper.js modules = [Virtual, Keyboard, Mousewheel, Navigation, Pagination, Scrollbar, Parallax, Zoom, Lazy, Controller, A11y, History, HashNavigation, Autoplay, Thumbs, FreeMode, Grid, Manipulation, EffectFade, EffectCube, EffectFlip, EffectCoverflow, EffectCreative, EffectCards]

jQuery(document).ready(function ($) {
	"use strict";

	/**
	 * Select2
	 */
	if ($('body').hasClass('woocommerce-account')) {
		$('.woocommerce-MyAccount-content select, .woocommerce-form select').select2();
	}

	/**
	 * Nice Select: update after WooCommerce changed the variation
	 */
	$(".variations_form").on("woocommerce_variation_has_changed", function () {
		$('.variations_form select').niceSelect('update');
	});

	/**
	 * Product quantity update
	 */
	$(document).on('click', '.quantity button.btn-qty__plus, .quantity button.btn-qty__minus', function () {
		var that = $(this);
		updateProductQty(that);

		$('button[name="update_cart"]').removeAttr('disabled').attr('aria-disabled', 'false');
	});

	$(document).on('keyup', '.quantity .qty', function () {
		var that = $(this);
		updateProductQty(that);

		$('button[name="update_cart"]').removeAttr('disabled').attr('aria-disabled', 'false');
	});

	const qtyFields = $('.quantity .qty');
	if (qtyFields.length) {
		qtyFields.each(function () {
			updateProductQty($(this));
		});
	}

	function updateProductQty(that) {
		// Get current quantity values
		const max = 9999,
			min = 1,
			step = 1;
		let qty = that.closest('.quantity').find('.qty'),
			val = parseFloat(qty.val()),
			totalQty = val;

		// Change the value if plus or minus
		if (that.is('.btn-qty__plus')) {
			if (max && (max <= val)) {
				qty.val(max);
				totalQty = max;
			} else {
				qty.val(val + step);
				totalQty = val + step;
			}
		} else if (that.is('.btn-qty__minus')) {
			if (min && (min >= val)) {
				qty.val(min);
				totalQty = min;
			} else if (val > 1) {
				qty.val(val - step);
				totalQty = val - step;
			}
		}
		if (1 === totalQty) {
			that.closest('.quantity').find('.btn-qty__minus').addClass('disabled');
		} else {
			that.closest('.quantity').find('.btn-qty__minus').removeClass('disabled');
		}
	}

	// hide both slider arrows if they are not needed
	function toggleSliderButtons(slider) {
		const disabledButtons = slider.querySelectorAll('.swiper-button-disabled');
		if (disabledButtons.length === 2) {
			disabledButtons.forEach(el => el.classList.add('d-none'));
		} else {
			slider.querySelectorAll('.swiper-button-prev, .swiper-button-next').forEach(el => el.classList.remove('d-none'));
		}
	}

	/**
	 * Slider Product Thumbnails
	 */
	const sliderProductThumbs = document.querySelector('.swiper-product-thumbs'),
		sliderProductImage = document.querySelector('.swiper-product-image');
	let swiperProductThumbs,
		swiperProductImage;
	if (sliderProductImage && sliderProductThumbs) {
		swiperProductThumbs = new Swiper(sliderProductThumbs, {
			modules: [Navigation, A11y],
			spaceBetween: 20,
			slidesPerView: 3,
			watchSlidesProgress: true,
			breakpoints: {
				1025: {
					slidesPerView: 4,
				},
			},
			navigation: {
				nextEl: sliderProductThumbs.querySelector('.swiper-button-next'),
				prevEl: sliderProductThumbs.querySelector('.swiper-button-prev'),
			},
			on: {
				init: function () {
					sliderProductThumbs.dataset.slides = sliderProductThumbs.querySelectorAll('.swiper-slide').length;

					toggleSliderButtons(sliderProductThumbs);
				}
			}
		});

		swiperProductImage = new Swiper(sliderProductImage, {
			modules: [Navigation, Thumbs, A11y],
			spaceBetween: 10,
			watchSlidesProgress: true,
			navigation: {
				nextEl: sliderProductImage.querySelector('.swiper-button-next'),
				prevEl: sliderProductImage.querySelector('.swiper-button-prev'),
			},
			thumbs: {
				swiper: swiperProductThumbs,
			},
			on: {
				init: function () {
					sliderProductImage.dataset.slides = sliderProductImage.querySelectorAll('.swiper-slide').length;
				}
			}
		});
	}

	/**
	 * Slider Products (upsells, related, etc)
	 */
	document.querySelectorAll('.swiper-products').forEach(slider => {
		new Swiper(slider, {
			modules: [Navigation, Pagination, A11y],
			spaceBetween: 10,
			breakpoints: {
				576: {
					slidesPerView: 2,
					spaceBetween: 20,
				},
				768: {
					slidesPerView: 3,
					spaceBetween: 30,
				},
				1025: {
					slidesPerView: 4,
				},
			},
			pagination: {
				el: slider.querySelector('.swiper-pagination'),
				clickable: true,
			},
			navigation: {
				nextEl: slider.querySelector('.swiper-button-next'),
				prevEl: slider.querySelector('.swiper-button-prev'),
			},
			on: {
				init: function () {
					slider.dataset.slides = slider.querySelectorAll('.swiper-slide').length;

					toggleSliderButtons(slider);
				}
			}
		});
	});

	window.addEventListener('resize', throttle(() => {
		document.querySelectorAll('.swiper').forEach(slider => toggleSliderButtons(slider));
	}));

	/**
	 * Single Product Variation Change
	 */
	if ($('form.variations_form').length && swiperProductImage && swiperProductThumbs) {
		const firstSlide = swiperProductImage.slides[0],
			firstSlideThumbs = swiperProductThumbs.slides[0],
			firstSlideOriginal = {
				dataImageId: firstSlide.getAttribute('data-image-id'),
				href: firstSlide.querySelector('a').getAttribute('href'),
				src: firstSlide.querySelector('img').getAttribute('src'),
				srcset: firstSlide.querySelector('img').getAttribute('srcset')
			};

		function resetFirstSlideToOriginal() {
			// slider product image:
			firstSlide.setAttribute('data-image-id', firstSlideOriginal.dataImageId);
			firstSlide.querySelector('a').setAttribute('href', firstSlideOriginal.href);
			firstSlide.querySelector('img').setAttribute('src', firstSlideOriginal.src);
			firstSlide.querySelector('img').setAttribute('srcset', firstSlideOriginal.srcset);

			// slider product thumbnails:
			firstSlideThumbs.setAttribute('data-image-id', firstSlideOriginal.dataImageId);
			firstSlideThumbs.querySelector('img').setAttribute('src', firstSlideOriginal.src);
			firstSlideThumbs.querySelector('img').setAttribute('srcset', firstSlideOriginal.srcset);
		}

		$('input.variation_id').on('change', function () {
			if ('' !== $(this).val()) {
				const varID = $(this).val(),
					variations = JSON.parse($('form.variations_form').attr('data-product_variations')),
					filteredData = variations.filter(i => i.variation_id == varID);

				if (Object.keys(filteredData).length !== 0) {
					let imgID = null;
					const varObj = filteredData[0];
					imgID = varObj.image_id;

					const slides = swiperProductImage.slides;
					let slideIndexGoTo = 0;
					slides.forEach((slide, i) => {
						if (slide.dataset.imageId == imgID) {
							slideIndexGoTo = i;
						}
					});

					if (slideIndexGoTo === 0) {
						// slider product image:
						firstSlide.setAttribute('data-image-id', imgID);
						firstSlide.querySelector('a').setAttribute('href', varObj.image.url);
						firstSlide.querySelector('img').setAttribute('src', varObj.image.src);
						firstSlide.querySelector('img').setAttribute('srcset', varObj.image.srcset);

						// slider product thumbnails:
						firstSlideThumbs.setAttribute('data-image-id', imgID);
						firstSlideThumbs.querySelector('img').setAttribute('src', varObj.image.thumb_src);
						firstSlideThumbs.querySelector('img').setAttribute('srcset', varObj.image.srcset);
					}

					// change active slide
					swiperProductImage.slideTo(slideIndexGoTo);
				}
			} else {
				resetFirstSlideToOriginal();
			}
		});

		// reset variations
		$('.reset_variations').on('click', function (e) {
			$('.variations_form select').val('').niceSelect('update');
			if (firstSlide.getAttribute('data-image-id') !== firstSlideOriginal.dataImageId) {
				resetFirstSlideToOriginal();
			}
		});

	}

});
