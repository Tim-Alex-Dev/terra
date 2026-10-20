/* global acf */
jQuery(document).ready(function ($) {
	"use strict";

	if (typeof window.acf === 'undefined') {
		return;
	}

	// Page Builder title tag: marks the automatic value (first module H1, others H2); nothing is saved
	function markAutoTitleTags() {
		$('.acf-field-flexible-content[data-name="builder"]').each(function () {
			$(this).find('> .acf-input > .acf-flexible-content > .values > .layout').not('.acf-clone').each(function (index) {
				const $field = $(this).find('.acf-field[data-name="title_tag"]').first();
				const $labels = $field.find('.acf-button-group label');
				const auto = 0 === index ? 'h1' : 'h2';
				const chosen = $labels.find('input:checked').length > 0;

				$labels.removeClass('-auto').removeAttr('title');
				if (!chosen) {
					$labels.has('input[value="' + auto + '"]')
						.addClass('-auto')
						.attr('title', 0 === index ? 'Automatic: H1 (first module of the page)' : 'Automatic: H2');
				}
			});
		});
	}

	markAutoTitleTags();
	acf.addAction('append', markAutoTitleTags);
	acf.addAction('sortstop', markAutoTitleTags);
	acf.addAction('remove', function () {
		setTimeout(markAutoTitleTags, 400); // after the remove animation
	});
	$(document).on('change click', '.acf-field[data-name="title_tag"] .acf-button-group', function () {
		setTimeout(markAutoTitleTags, 0);
	});

});
