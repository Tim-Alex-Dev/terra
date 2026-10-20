<?php
// Leave-site notice: shown before a link opens the original WORTMANN websites (assets/js/components/leave-notice.js), texts from Theme Settings > Leave Popup

$title         = get_field( 'leave_title', 'option' ) ?: __( 'You are going to the original WORTMANN website', 'terra' );
$text          = get_field( 'leave_text', 'option' ) ?: __( 'This link opens the official website of WORTMANN AG, the manufacturer of TERRA. Everything is fine: it is a safe move, and you can come back here at any time.', 'terra' );
$address_label = get_field( 'leave_address_label', 'option' ) ?: __( 'Address:', 'terra' );
$go_button     = get_field( 'leave_go_button', 'option' ) ?: __( 'Go to the website', 'terra' );
$stay_button   = get_field( 'leave_stay_button', 'option' ) ?: __( 'Stay here', 'terra' );
?>

<div class="modal leave-notice" id="leave-notice">
	<div class="modal__overlay"></div>
	<div class="modal__inner">
		<div class="modal__content leave-notice__content" role="dialog" aria-modal="true" aria-labelledby="leave-notice-title" aria-describedby="leave-notice-text">
			<button type="button" class="modal__close js-modal-close" aria-label="<?php echo esc_attr( $stay_button ); ?>"><svg><use xlink:href="#close"></use></svg></button>

			<span class="leave-notice__icon"><svg><use xlink:href="#globe"></use></svg></span>

			<h2 id="leave-notice-title" class="leave-notice__title h4">
				<?php echo esc_html( $title ); ?>
			</h2>

			<p id="leave-notice-text" class="leave-notice__text">
				<?php echo esc_html( $text ); ?>
			</p>

			<p class="leave-notice__host">
				<?php echo esc_html( $address_label ); ?> <strong class="js-leave-host"></strong>
			</p>

			<div class="leave-notice__actions">
				<a class="btn btn-primary js-leave-go" href="#">
					<?php echo esc_html( $go_button ); ?>
				</a>
				<button type="button" class="btn btn-outline btn-primary js-modal-close">
					<?php echo esc_html( $stay_button ); ?>
				</button>
			</div>
		</div>
	</div>
</div>
