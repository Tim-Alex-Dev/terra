jQuery(document).ready(function ($) {
	"use strict";

	/**
	 * Modal: a.js-modal-open[data-modal="id"] opens div.modal#id.
	 * Closes with button.js-modal-close, a click on the overlay or the Escape key.
	 * Keyboard users: the focus moves into the modal (close button) and back to the opener when it closes.
	 */
	let opener = null;

	const closeModal = () => {
		const modal = $('.modal.is-open');
		if (!modal.length) {
			return;
		}
		modal.removeClass('is-open');
		$('body').removeClass('overflow-hidden');
		if (opener) {
			opener.focus();
			opener = null;
		}
	};

	$(document).on('click', '.js-modal-open', function (e) {
		e.preventDefault();
		const modal = $('#' + $(this).data('modal'));
		if (!modal.length) {
			return;
		}
		opener = this;
		$('body').removeClass('is-menu-open').addClass('overflow-hidden');
		modal.addClass('is-open');
		modal.find('.js-modal-close').first().trigger('focus');
	});

	$(document).on('click', '.js-modal-close, .modal__overlay', closeModal);

	$(document).on('keydown', function (e) {
		if ('Escape' === e.key) {
			closeModal();
		}
	});

});
