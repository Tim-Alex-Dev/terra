<?php
// Leave-site notice: shown before a link opens the original WORTMANN websites (assets/js/components/leave-notice.js)
?>

<div class="modal leave-notice" id="leave-notice">
	<div class="modal__overlay"></div>
	<div class="modal__inner">
		<div class="modal__content leave-notice__content" role="dialog" aria-modal="true" aria-labelledby="leave-notice-title" aria-describedby="leave-notice-text">
			<button type="button" class="modal__close js-modal-close" aria-label="<?php esc_attr_e( 'Close', 'terra' ); ?>"><svg><use xlink:href="#close"></use></svg></button>

			<span class="leave-notice__icon"><svg><use xlink:href="#globe"></use></svg></span>

			<h2 id="leave-notice-title" class="leave-notice__title h4">
				<?php esc_html_e( 'You are going to the original WORTMANN website', 'terra' ); ?>
			</h2>

			<p id="leave-notice-text" class="leave-notice__text">
				<?php esc_html_e( 'This link opens the official website of WORTMANN AG, the manufacturer of TERRA. Everything is fine: it is a safe move, and you can come back here at any time.', 'terra' ); ?>
			</p>

			<p class="leave-notice__host">
				<?php esc_html_e( 'Address:', 'terra' ); ?> <strong class="js-leave-host"></strong>
			</p>

			<div class="leave-notice__actions">
				<a class="btn btn-primary js-leave-go" href="#">
					<?php esc_html_e( 'Go to the website', 'terra' ); ?>
				</a>
				<button type="button" class="btn btn-outline btn-primary js-modal-close">
					<?php esc_html_e( 'Stay here', 'terra' ); ?>
				</button>
			</div>
		</div>
	</div>
</div>
