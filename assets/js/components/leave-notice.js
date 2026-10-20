// Leave-site notice (template-parts/leave-notice.php) before links to the original WORTMANN websites
jQuery(document).ready(function ($) {
	"use strict";

	const originalHosts = ['wortmann.de', 'wortmann.com.ua']; // and their subdomains
	const notice = $('#leave-notice');
	if (!notice.length) {
		return;
	}

	const isOriginal = url => url.hostname !== window.location.hostname
		&& originalHosts.some(host => url.hostname === host || url.hostname.endsWith('.' + host));

	$(document).on('click', 'a[href]', function (e) {
		// new tab / window with a modifier key or the middle button: no notice
		if (e.isDefaultPrevented() || 0 !== e.button || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || this.closest('.modal')) {
			return;
		}
		let url;
		try {
			url = new URL(this.href);
		} catch {
			return;
		}
		if (!/^https?:$/.test(url.protocol) || !isOriginal(url)) {
			return;
		}

		e.preventDefault();
		const go = notice.find('.js-leave-go').attr('href', url.href);
		if ('_blank' === this.target) {
			go.attr({target: '_blank', rel: 'noopener'});
		} else {
			go.removeAttr('target rel');
		}
		notice.find('.js-leave-host').text(url.hostname.replace(/^www\./, ''));
		$(document).trigger('modal:open', ['leave-notice', this]);
	});

	// a new tab opened: close the notice on this page
	notice.on('click', '.js-leave-go', function () {
		if ('_blank' === this.target) {
			setTimeout(() => notice.find('.js-modal-close').first().trigger('click'), 0);
		}
	});

});
