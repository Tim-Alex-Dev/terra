window.addEventListener('load', () => {

	const toTop = document.getElementById('to-top');
	if (toTop) {
		window.addEventListener('scroll', () => {
			if (window.scrollY > 300) {
				toTop.classList.add('show');
			} else {
				toTop.classList.remove('show');
			}
		}, {passive: true});

		toTop.addEventListener('click', () => {
			setTimeout(() => {
				history.replaceState('', document.title, window.location.origin + window.location.pathname + window.location.search);
			}, 5);
		});
	}

});
