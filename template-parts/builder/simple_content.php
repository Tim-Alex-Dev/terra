<?php
// Simple Content: blocks of text, media (image, video file, video link) or text + media in 2 columns.
$module_id       = it_id_attr( get_sub_field( 'module_id' ) );
$module_title    = get_sub_field( 'title' ) ?: false;
$module_uptitle  = get_sub_field( 'uptitle' ) ?: false;
$module_subtitle = get_sub_field( 'subtitle' ) ?: false;
$module_headings = $module_title || $module_uptitle || $module_subtitle;
$module_theme    = 'grey' === get_sub_field( 'color_theme' ) ? 'grey' : 'white';
$embed_allowed   = [ 'iframe' => [ 'src' => true, 'width' => true, 'height' => true, 'title' => true, 'frameborder' => true, 'allow' => true, 'allowfullscreen' => true, 'referrerpolicy' => true, 'loading' => true ] ];
?>

<?php if ( $module_headings || get_sub_field( 'content_list' ) ) : ?>
	<section<?php echo $module_id; ?> class="module m-simple-content bg-<?php echo esc_attr( $module_theme ); ?>">
		<div class="container">
			<?php if ( $module_headings ) : ?>
				<?php get_template_part( 'template-parts/builder/components/headings' ); ?>
			<?php endif; ?>

			<?php while ( have_rows( 'content_list' ) ) : the_row();
				$content_type = get_sub_field( 'content_type' ) ?: 'mixed';
				$media_type   = get_sub_field( 'media_type' ) ?: 'image';
				$media_side   = 'media-left' === get_sub_field( 'media_orientation' ) ? 'media-left' : 'media-right';
				$has_text     = 'media' !== $content_type;
				$has_media    = 'text' !== $content_type;

				$text_content = $has_text ? get_sub_field( 'text_content' ) : false;
				$text_link    = $has_text ? get_sub_field( 'module_link' ) : false;
				$link_url     = $text_link ? $text_link['url'] : '';
				$link_title   = $text_link ? $text_link['title'] : '';
				$link_target  = $text_link && '_blank' === $text_link['target'] ? ' target="_blank" rel="noopener"' : '';

				$media_image  = $has_media && 'image' === $media_type ? get_sub_field( 'module_image' ) : false;
				$media_video  = $has_media && 'video' === $media_type ? get_sub_field( 'module_video' ) : false;
				$video_banner = $media_video ? get_sub_field( 'video_banner' ) : false;
				$video_title  = $media_video ? get_sub_field( 'video_title' ) : false;
				$video_meta   = $media_video ? wp_get_attachment_metadata( attachment_url_to_postid( $media_video ) ) : false;
				$video_length = $video_meta['length_formatted'] ?? false;
				$video_label  = $video_title ?: __( 'Play video', 'terra' );

				$media_embed = '';
				$embed_html  = $has_media && 'embed' === $media_type ? get_sub_field( 'embed_video' ) : false;
				if ( $embed_html ) {
					$embed = new WP_HTML_Tag_Processor( $embed_html );
					if ( $embed->next_tag( 'iframe' ) ) {
						$embed->set_attribute( 'loading', 'lazy' );
						$embed->set_attribute( 'src', str_replace( '//www.youtube.com/embed/', '//www.youtube-nocookie.com/embed/', (string) $embed->get_attribute( 'src' ) ) );
						$media_embed = $embed->get_updated_html();
					}
				}

				$show_text  = $text_content || $link_url;
				$show_media = $media_image || $media_video || $media_embed;
				$item_type  = $show_text && $show_media ? 'mixed' : ( $show_media ? 'media' : 'text' );
				$image_attr = [ 'class' => 'media' === $item_type ? 'm-simple-content__image' : 'img-cover' ]; ?>

				<?php if ( $show_text || $show_media ) : ?>
					<div class="m-simple-content__item m-simple-content__item--<?php echo esc_attr( $item_type ); ?> m-simple-content__item--<?php echo esc_attr( $media_side ); ?>">
						<?php if ( $show_text ) : ?>
							<div class="m-simple-content__text">
								<?php if ( $text_content ) : ?>
									<div class="editor">
										<?php echo wp_kses_post( $text_content ); ?>
									</div>
								<?php endif; ?>

								<?php if ( $link_url ) : ?>
									<a class="btn btn-primary" href="<?php echo esc_url( $link_url ); ?>"<?php echo $link_target; ?>>
										<?php echo esc_html( $link_title ); ?>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php if ( $media_image ) : ?>
							<div class="m-simple-content__media m-simple-content__media--image">
								<?php echo wp_get_attachment_image( $media_image, 'full', false, $image_attr ); ?>
							</div>
						<?php endif; ?>

						<?php if ( $media_video && $video_banner ) : ?>
							<a class="m-simple-content__media m-simple-content__media--video" href="<?php echo esc_url( $media_video ); ?>" data-lightbox="">
								<?php echo wp_get_attachment_image( $video_banner, 'full', false, [ 'class' => 'img-cover', 'alt' => '' ] ); ?>

								<span class="m-simple-content__play">
									<svg><use xlink:href="#play"></use></svg>
								</span>

								<span class="m-simple-content__video-footer">
									<span class="m-simple-content__video-title">
										<?php echo esc_html( $video_label ); ?>
									</span>

									<?php if ( $video_length ) : ?>
										<span class="m-simple-content__video-duration">
											<?php echo esc_html( $video_length ); ?>
										</span>
									<?php endif; ?>
								</span>
							</a>
						<?php elseif ( $media_video ) : ?>
							<div class="m-simple-content__media m-simple-content__media--video">
								<video class="img-cover" src="<?php echo esc_url( $media_video ); ?>" controls preload="metadata"></video>
							</div>
						<?php endif; ?>

						<?php if ( $media_embed ) : ?>
							<div class="m-simple-content__media m-simple-content__media--embed">
								<?php echo wp_kses( $media_embed, $embed_allowed ); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			<?php endwhile; ?>
		</div>
	</section>
<?php endif; ?>
