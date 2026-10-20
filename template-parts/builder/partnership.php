<?php
// Partnership: 2 endless logo rows (odd logos top, even bottom), name on hover.
$module_id       = it_id_attr( get_sub_field( 'module_id' ) );
$module_title    = get_sub_field( 'title' ) ?: false;
$module_uptitle  = get_sub_field( 'uptitle' ) ?: false;
$module_subtitle = get_sub_field( 'subtitle' ) ?: false;
$module_headings = $module_title || $module_uptitle || $module_subtitle;
$module_theme    = 'grey' === get_sub_field( 'color_theme' ) ? 'grey' : 'white';
?>

<?php if ( get_sub_field( 'module_gallery' ) ) : ?>
	<section<?php echo $module_id; ?> class="module m-partnership bg-<?php echo esc_attr( $module_theme ); ?>">
		<div class="container">
			<?php if ( $module_headings ) : ?>
				<?php get_template_part( 'template-parts/builder/components/headings' ); ?>
			<?php endif; ?>

			<div class="m-partnership__swipers">
				<?php foreach ( [ 'top' => 1, 'bottom' => 0 ] as $row_name => $row_odd ) : ?>
					<div class="m-partnership__swiper m-partnership__swiper--<?php echo esc_attr( $row_name ); ?> swiper">
						<div class="swiper-wrapper">
							<?php while ( have_rows( 'module_gallery' ) ) : the_row();
								$slide_icon = get_sub_field( 'gallery_icon' );
								$slide_text = get_sub_field( 'gallery_title' );
								$slide_row  = get_row_index() % 2; ?>

								<?php if ( $row_odd === $slide_row && $slide_icon && $slide_text ) : ?>
									<div class="m-partnership__slide swiper-slide">
										<?php echo wp_get_attachment_image( $slide_icon, 'medium', false, [ 'class' => 'm-partnership__slide-icon', 'alt' => '' ] ); ?>

										<span class="m-partnership__slide-text">
											<?php echo esc_html( $slide_text ); ?>
										</span>
									</div>
								<?php endif; ?>
							<?php endwhile; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>
