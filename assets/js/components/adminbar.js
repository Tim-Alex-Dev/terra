jQuery(document).ready(function ($) {
	"use strict";

	// Admin bar on the front-end: hidden above the page (no top margin), opened with a click on the site name
	if ($('body').hasClass('admin-bar')) {
		$('html').css({'cssText': 'margin-top: 0 !important'});

		$('#wp-admin-bar-site-name').on('click', function (e) {
			e.stopPropagation();
			$('#wpadminbar').toggleClass('is-expanded');
		});
	}
});
