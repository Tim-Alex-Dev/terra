// Lightbox (GLightbox): <a data-lightbox="gallery-name">; videos with Plyr from the theme (no CDN)
import GLightbox from 'glightbox';

/* global __webpack_public_path__ */
const dist = __webpack_public_path__ + '../';

// empty data-lightbox = single item (own gallery, no prev / next)
document.querySelectorAll('[data-lightbox]').forEach((link, index) => {
	if (!link.dataset.gallery) {
		link.dataset.gallery = link.dataset.lightbox || `single-${index}`;
	}
});

GLightbox({
	selector: '[data-lightbox]',
	touchNavigation: true,
	loop: true,
	autoplayVideos: true,
	plyr: {
		css: dist + 'css/plyr.css',
		js: dist + 'js/plyr.min.js',
		config: {
			// no third-party requests
			iconUrl: dist + 'img/plyr.svg',
			blankVideo: '',
			urls: {youtube: {api: 'data:,'}},
		},
	},
});
