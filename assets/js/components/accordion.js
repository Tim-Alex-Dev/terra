jQuery(document).ready(function ($) {
	"use strict";

	/**
	 * Accordion: .js-accordion > .js-accordion-item > button.js-accordion-title + .js-accordion-content
	 * One item open at a time. aria-expanded on the title tells screen readers whether its content is open.
	 */
	$('.js-accordion-title').attr('aria-expanded', function () {
		return $(this).closest('.js-accordion-item').hasClass('is-open') ? 'true' : 'false';
	});

	$(document).on('click', '.js-accordion-title', function () {
		const accordion = $(this).closest('.js-accordion'),
			accordionItem = $(this).closest('.js-accordion-item'),
			wasOpen = accordionItem.hasClass('is-open');

		accordion.find('.js-accordion-item').removeClass('is-open');
		accordion.find('.js-accordion-title').attr('aria-expanded', 'false');
		accordion.find('.js-accordion-content').stop().slideUp();

		if (!wasOpen) {
			accordionItem.addClass('is-open');
			$(this).attr('aria-expanded', 'true');
			accordionItem.find('.js-accordion-content').stop().slideDown();
		}
	});

});
