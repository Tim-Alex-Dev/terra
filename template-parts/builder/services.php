<?php
// Services: text + Contact Form 7 form.
$module_id       = it_id_attr( get_sub_field( 'module_id' ) );
$module_title    = get_sub_field( 'title' ) ?: false;
$module_uptitle  = get_sub_field( 'uptitle' ) ?: false;
$module_subtitle = get_sub_field( 'subtitle' ) ?: false;
$module_headings = $module_title || $module_uptitle || $module_subtitle;
$module_theme    = 'grey' === get_sub_field( 'color_theme' ) ? 'grey' : 'white';
$text_content    = get_sub_field( 'text_content' ) ?: false;
$form_title      = get_sub_field( 'form_title' ) ?: false;
$form_id         = (int) get_sub_field( 'contact_form' );
$form_id         = 'wpcf7_contact_form' === get_post_type( $form_id ) ? $form_id : 0;
?>

<?php if ( $module_headings || $text_content || $form_id ) : ?>
	<section<?php echo $module_id; ?> class="module m-services bg-<?php echo esc_attr( $module_theme ); ?>">
		<div class="container">
			<?php if ( $module_headings ) : ?>
				<?php get_template_part( 'template-parts/builder/components/headings' ); ?>
			<?php endif; ?>

			<?php if ( $text_content || $form_id ) : ?>
				<div class="m-services__inner">
					<?php if ( $text_content ) : ?>
						<div class="m-services__text editor">
							<?php echo wp_kses_post( $text_content ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $form_id ) : ?>
						<div class="m-services__form">
							<?php if ( $form_title ) : ?>
								<h3 class="m-services__form-title h4">
									<?php echo esc_html( $form_title ); ?>
								</h3>
							<?php endif; ?>

							<?php // escaped by CF7 ?>
							<?php echo do_shortcode( '[contact-form-7 id="' . $form_id . '" html_title="' . esc_attr( $form_title ?: get_the_title( $form_id ) ) . '"]' ); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>
