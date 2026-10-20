<?php
// Hero: banner slider + link tiles (images shown whole, they carry the text). Headings for screen readers only.
$module_id       = it_id_attr( get_sub_field( 'module_id' ) );
$module_title    = get_sub_field( 'title' ) ?: false;
$module_uptitle  = get_sub_field( 'uptitle' ) ?: false;
$module_subtitle = get_sub_field( 'subtitle' ) ?: false;
$module_headings = $module_title || $module_uptitle || $module_subtitle;
$slides_count    = count( (array) get_sub_field( 'slides' ) );
?>

<?php if ( $slides_count || get_sub_field( 'blocks' ) ) : ?>
	<section<?php echo $module_id; ?> class="module m-hero">
		<div class="container">
			<?php if ( $module_headings ) : ?>
				<div class="screen-reader-text">
					<?php get_template_part( 'template-parts/builder/components/headings' ); ?>
				</div>
			<?php endif; ?>

			<div class="m-hero__inner">
				<?php if ( $slides_count ) : ?>
					<div class="m-hero__slider swiper js-hero-slider" data-slides="<?php echo esc_attr( $slides_count ); ?>">
						<div class="swiper-wrapper">
							<?php while ( have_rows( 'slides' ) ) : the_row();
								$slide_image  = get_sub_field( 'image' );
								$slide_link   = get_sub_field( 'link' ) ?: false;
								$slide_url    = $slide_link ? $slide_link['url'] : '';
								$slide_label  = $slide_link ? $slide_link['title'] : '';
								$slide_target = $slide_link && '_blank' === $slide_link['target'] ? ' target="_blank" rel="noopener"' : '';
								$slide_attr   = [
									'class' => 'm-hero__slider-image img-contain',
									'sizes' => '(min-width: 1400px) 1400px, 100vw',
								];
								if ( 1 === get_row_index() ) { // above the fold
									$slide_attr['loading']       = 'eager';
									$slide_attr['fetchpriority'] = 'high';
								} ?>

								<div class="m-hero__slider-slide swiper-slide">
									<?php if ( $slide_url ) : ?>
										<a class="m-hero__slider-link" href="<?php echo esc_url( $slide_url ); ?>"<?php echo $slide_target; ?>>
											<?php echo wp_get_attachment_image( $slide_image, 'full', false, $slide_attr ); ?>
											<?php if ( $slide_label ) : ?>
												<span class="screen-reader-text"><?php echo esc_html( $slide_label ); ?></span>
											<?php endif; ?>
										</a>
									<?php else : ?>
										<?php echo wp_get_attachment_image( $slide_image, 'full', false, $slide_attr ); ?>
									<?php endif; ?>
								</div>
							<?php endwhile; ?>
						</div>

						<?php if ( $slides_count > 1 ) : ?>
							<button type="button" class="swiper-button-prev"><svg><use xlink:href="#angle-left"></use></svg></button>
							<button type="button" class="swiper-button-next"><svg><use xlink:href="#angle-right"></use></svg></button>
							<div class="swiper-pagination"></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( get_sub_field( 'blocks' ) ) : ?>
					<div class="m-hero__blocks">
						<?php while ( have_rows( 'blocks' ) ) : the_row();
							$block_title = get_sub_field( 'title' );
							$block_image = get_sub_field( 'image' );
							$block_link  = get_sub_field( 'link' ) ?: false;
							$block_size  = 'wide' === get_sub_field( 'size' ) ? 'wide' : 'small';
							$block_url    = $block_link ? $block_link['url'] : '';
							$block_target = $block_link && '_blank' === $block_link['target'] ? ' target="_blank" rel="noopener"' : ''; ?>

							<?php if ( $block_url ) : ?>
								<a class="m-hero__blocks-item m-hero__blocks-item--<?php echo esc_attr( $block_size ); ?>" href="<?php echo esc_url( $block_url ); ?>"<?php echo $block_target; ?>>
									<?php echo wp_get_attachment_image( $block_image, 'large', false, [ 'class' => 'item__image img-contain', 'alt' => '' ] ); ?>
									<span class="item__title screen-reader-text">
										<?php echo esc_html( $block_title ); ?>
									</span>
								</a>
							<?php endif; ?>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>
