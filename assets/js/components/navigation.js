import {minWidth} from '../functions/breakpoint';

jQuery(document).ready(function ($) {
	"use strict";

	// Mobile menu
	$('.icon-burger').on('click', function () {
		$('body').toggleClass('is-menu-open');
	});

	// keep aria-expanded of the burger button in sync with the menu state (the menu can be closed by other scripts too)
	const burger = document.querySelector('.icon-burger');
	if (burger) {
		const syncBurger = () => burger.setAttribute('aria-expanded', document.body.classList.contains('is-menu-open') ? 'true' : 'false');
		syncBurger();
		new MutationObserver(syncBurger).observe(document.body, {attributes: true, attributeFilter: ['class']});
	}

	$('.main-menu li:not(.menu-item-has-children) > a').on('click', function () {
		$('body').removeClass('is-menu-open');
	});

	// autoclose mobile menu when increasing window width
	function closeMobileMenu() {
		$('.main-menu .sub-menu, .footer-links .sub-menu').css('display', '');
		if (minWidth('lg')) {
			if ($('body').hasClass('is-menu-open')) {
				$('.icon-burger').trigger('click');
			}
		}
	}

	$(window).on('resize', function (e) {
		closeMobileMenu();
	});


	// Mobile sub-menus
	$('.main-menu .menu-item-has-children, .footer-links .menu-item-has-children').on('click', function (e) {
		const el = $(this),
			topEl = $(this).parent();
		if (!minWidth('lg')) {
			e.stopPropagation();
			if (el.hasClass('active')) {
				el.removeClass('active');
				el.find('> .sub-menu').slideUp();
			} else {
				topEl.find('> .sub-menu').slideUp();
				topEl.find('.menu-item-has-children').removeClass('active');
				el.addClass('active');
				el.find('> .sub-menu').slideDown();
			}
		}
	});

});
