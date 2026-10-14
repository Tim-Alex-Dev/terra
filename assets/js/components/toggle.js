jQuery(document).ready(function ($) {
	"use strict";

	/**
	 * Toggle: .js-toggle[data-item="id"] shows/hides .js-toggle-content#id
	 * aria-expanded tells screen readers whether the content is open.
	 */
	$('.js-toggle').each(function () {
		$(this).attr({'aria-controls': $(this).data('item'), 'aria-expanded': $(this).hasClass('is-open') ? 'true' : 'false'});
	});

	$(document).on('click', '.js-toggle', function (e) {
		e.preventDefault();
		const item = $(this).data('item');
		if ('' !== item) {
			$(this).toggleClass('is-open').attr('aria-expanded', $(this).hasClass('is-open') ? 'true' : 'false');
			$('#' + item).toggleClass('is-open').slideToggle();
		}
	});

});
