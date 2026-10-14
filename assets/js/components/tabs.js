jQuery(document).ready(function ($) {
	"use strict";

	/**
	 * Tabs: .js-tabs with button.js-tab-title[data-item="id"] and .js-tab-content#id
	 * The ARIA roles and states are set here, so screen readers announce the tabs and the selected one.
	 */
	$('.js-tabs').each(function () {
		$(this).find('.js-tab-title').each(function () {
			const item = $(this).data('item');
			$(this).attr({role: 'tab', 'aria-controls': item, 'aria-selected': $(this).hasClass('is-active') ? 'true' : 'false'});
			$('#' + item).attr('role', 'tabpanel');
		}).first().parent().attr('role', 'tablist');
	});

	$(document).on('click', '.js-tab-title', function () {
		const tabs = $(this).closest('.js-tabs'),
			item = $(this).data('item');
		if ('' !== item) {
			tabs.find('.js-tab-title').removeClass('is-active').attr('aria-selected', 'false');
			$(this).addClass('is-active').attr('aria-selected', 'true');
			tabs.find('.js-tab-content').removeClass('is-active');
			tabs.find('#' + item).addClass('is-active');
		}
	});

});
