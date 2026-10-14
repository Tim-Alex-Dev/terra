/**
 * @link https://contactform7.com/dom-events/
 * List of Contact Form 7 Custom DOM Events
 wpcf7invalid — Fires when an Ajax form submission has completed successfully, but mail hasn’t been sent because there are fields with invalid input.
 wpcf7spam — Fires when an Ajax form submission has completed successfully, but mail hasn’t been sent because a possible spam activity has been detected.
 wpcf7mailsent — Fires when an Ajax form submission has completed successfully, and mail has been sent.
 wpcf7mailfailed — Fires when an Ajax form submission has completed successfully, but it has failed in sending mail.
 wpcf7submit — Fires when an Ajax form submission has completed successfully, regardless of other incidents.

 * PROPERTY	DESCRIPTION
 detail.contactFormId	The ID of the contact form.
 detail.pluginVersion	The version of Contact Form 7 plugin.
 detail.contactFormLocale	The locale code of the contact form.
 detail.unitTag	The unit-tag of the contact form.
 detail.containerPostId	The ID of the post that the contact form is placed in.
 */

jQuery(document).ready(function ($) {
	"use strict";

	// Example:
	// document.addEventListener('wpcf7submit', function (event) {
	// 	if ('123' === event.detail.contactFormId) {
	// 		// do something productive
	// 	}
	// }, false);
});
