/**
 * Lightbox (GLightbox): <a href="image.webp | YouTube/Vimeo URL | file.mp4" data-lightbox="gallery-name">
 * (same value = one gallery). Videos: Plyr, loaded by GLightbox only when a video opens, from the theme (no CDN):
 * dist/js/plyr.min.js + dist/img/plyr.svg (copies from node_modules/plyr), dist/css/plyr.css.
 */
import GLightbox from 'glightbox';

/* global __webpack_public_path__ */
const dist = __webpack_public_path__ + '../'; // dist/ folder URL (main.js is in dist/js/)

// GLightbox groups galleries by data-gallery: take it from data-lightbox
document.querySelectorAll('[data-lightbox]').forEach(link => {
	if (link.dataset.lightbox && !link.dataset.gallery) {
		link.dataset.gallery = link.dataset.lightbox;
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
			// no third-party requests: icons from the theme, no blank video and no YouTube title lookup from external services
			iconUrl: dist + 'img/plyr.svg',
			blankVideo: '',
			urls: {youtube: {api: 'data:,'}},
		},
	},
});
