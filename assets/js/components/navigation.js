import {minWidth} from '../functions/breakpoint';

jQuery(document).ready(function ($) {
	"use strict";

	// Mobile menu
	$('.icon-burger').on('click', function () {
		$('body').toggleClass('is-menu-open');
	});

	const burger = document.querySelector('.icon-burger');
	if (burger) {
		const syncBurger = () => burger.setAttribute('aria-expanded', document.body.classList.contains('is-menu-open') ? 'true' : 'false');
		syncBurger();
		new MutationObserver(syncBurger).observe(document.body, {attributes: true, attributeFilter: ['class']});
	}

	$('.main-menu li:not(.menu-item-has-children) > a').on('click', function () {
		$('body').removeClass('is-menu-open');
	});

	// Sub-menus: desktop level 1 opens on hover / focus (CSS) or click; deeper levels and mobile: accordion
	function setOpen(item, open) {
		item.toggleClass('active', open).children('.menu-toggle').attr('aria-expanded', open ? 'true' : 'false');
	}

	function closeTopLevel() {
		$('.main-menu > li.active').each(function () {
			setOpen($(this), false);
		});
	}

	$('.main-menu .menu-toggle').on('click', function (e) {
		const item = $(this).parent(),
			open = !item.hasClass('active'),
			topLevelDesktop = minWidth('lg') && item.parent().hasClass('main-menu');

		e.stopPropagation();

		item.siblings('.active').each(function () {
			setOpen($(this), false);
			if (!topLevelDesktop) {
				$(this).children('.sub-menu').stop().slideUp();
			}
		});

		if (!topLevelDesktop) {
			item.children('.sub-menu').stop()[open ? 'slideDown' : 'slideUp']();
		}

		setOpen(item, open);
	});

	// Desktop: another item, a click outside or Escape closes the clicked panel
	$('.main-menu > li').on('mouseenter', function () {
		if (minWidth('lg')) {
			$(this).siblings('.active').each(function () {
				setOpen($(this), false);
			});
		}
	});

	$(document).on('click', function (e) {
		if (minWidth('lg') && !$(e.target).closest('.main-menu').length) {
			closeTopLevel();
		}
	}).on('keydown', function (e) {
		if ('Escape' === e.key && minWidth('lg')) {
			closeTopLevel();
		}
	});

	// Reset on a real breakpoint change only (mobile browsers fire resize on scroll)
	let desktop = minWidth('lg');

	$(window).on('resize', function () {
		if (desktop === minWidth('lg')) {
			return;
		}
		desktop = minWidth('lg');

		$('.main-menu .sub-menu').css('display', '');
		$('.main-menu li.active').each(function () {
			setOpen($(this), false);
		});
		if (desktop && $('body').hasClass('is-menu-open')) {
			$('.icon-burger').trigger('click');
		}
	});

});
