<?php
$module_id       = it_id_attr( get_sub_field( 'module_id' ) );
$module_title    = get_sub_field( 'title' ) ?: false;
$module_uptitle  = get_sub_field( 'uptitle' ) ?: false;
$module_subtitle = get_sub_field( 'subtitle' ) ?: false;
$module_headings = $module_title || $module_uptitle || $module_subtitle;
?>

<?php if ( get_sub_field( 'faq_list' ) ) : ?>
	<section<?php echo $module_id; ?> class="module m-faq">
		<div class="container">
			<?php if ( $module_headings ) : ?>
				<?php get_template_part( 'template-parts/builder/components/headings' ); ?>
			<?php endif; ?>

			<div class="m-faq__list js-accordion">
				<?php $faq = []; ?>
				<?php while ( have_rows( 'faq_list' ) ) : the_row();
					$question = wp_strip_all_tags( get_sub_field( 'question' ) );
					$answer   = get_sub_field( 'answer' ); ?>

					<?php $faq[] = [
						'@type'          => 'Question',
						'name'           => $question,
						'acceptedAnswer' => [
							'@type' => 'Answer',
							'text'  => wp_strip_all_tags( $answer ),
						],
					]; ?>

					<div class="m-faq__list-item js-accordion-item">
						<button type="button" class="m-faq__list-item-title h5 js-accordion-title">
							<span><?php echo esc_html( $question ); ?></span>
							<svg><use xlink:href="#angle-down"></use></svg>
						</button>
						<div class="m-faq__list-item-content js-accordion-content">
							<div class="editor">
								<?php echo wp_kses_post( wpautop( $answer ) ); ?>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			</div>

			<script type="application/ld+json">
				{
					"@context": "https://schema.org",
					"@type": "FAQPage",
					"mainEntity": <?php echo wp_json_encode( $faq ); ?>
				}
			</script>
		</div>
	</section>
<?php endif; ?>