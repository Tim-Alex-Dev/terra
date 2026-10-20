<?php
// Contacts: contact details in 2 blocks + Google map.
$module_id       = it_id_attr( get_sub_field( 'module_id' ) );
$module_title    = get_sub_field( 'title' ) ?: false;
$module_uptitle  = get_sub_field( 'uptitle' ) ?: false;
$module_subtitle = get_sub_field( 'subtitle' ) ?: false;
$module_headings = $module_title || $module_uptitle || $module_subtitle;
$module_theme    = 'grey' === get_sub_field( 'color_theme' ) ? 'grey' : 'white';
$company         = get_sub_field( 'contact_company' ) ?: false;
$address         = get_sub_field( 'contact_address' ) ?: false;
$hours           = get_sub_field( 'contact_hours' ) ?: false;
$has_phones      = (bool) get_sub_field( 'contact_phones' );
$has_emails      = (bool) get_sub_field( 'contact_emails' );
$has_links       = (bool) get_sub_field( 'contact_links' );
$block_main      = $company || $address || $has_phones || $hours;
$block_online    = $has_emails || $has_links;
$map_url         = (string) get_sub_field( 'map_url' );
$map_url         = str_starts_with( $map_url, 'https://www.google.com/maps/embed' ) ? $map_url : ''; // only Google embeds
$map_title       = $company ?: __( 'Map', 'terra' );
$inner_class     = $map_url ? 'm-contacts__inner m-contacts__inner--map' : 'm-contacts__inner';
?>

<section<?php echo $module_id; ?> class="module m-contacts bg-<?php echo esc_attr( $module_theme ); ?>">
	<div class="container">
		<?php if ( $module_headings ) : ?>
			<?php get_template_part( 'template-parts/builder/components/headings' ); ?>
		<?php endif; ?>

		<div class="<?php echo esc_attr( $inner_class ); ?>">
			<div class="m-contacts__details">
				<?php if ( $block_main ) : ?>
					<ul class="m-contacts__list">
						<?php if ( $company || $address ) : ?>
							<li class="m-contacts__item">
								<svg><use xlink:href="#location"></use></svg>
								<div class="m-contacts__content">
									<?php if ( $company ) : ?>
										<strong class="m-contacts__company">
											<?php echo esc_html( $company ); ?>
										</strong>
									<?php endif; ?>

									<?php if ( $address ) : ?>
										<address class="m-contacts__address">
											<?php echo nl2br( esc_html( $address ) ); ?>
										</address>
									<?php endif; ?>
								</div>
							</li>
						<?php endif; ?>

						<?php if ( $has_phones ) : ?>
							<li class="m-contacts__item">
								<svg><use xlink:href="#phone"></use></svg>
								<div class="m-contacts__content">
									<?php while ( have_rows( 'contact_phones' ) ) : the_row();
										$phone_label = get_sub_field( 'label' );
										$phone       = get_sub_field( 'phone' ); ?>

										<p class="m-contacts__row">
											<?php if ( $phone_label ) : ?>
												<span class="m-contacts__label">
													<?php echo esc_html( $phone_label ); ?>
												</span>
											<?php endif; ?>
											<a href="<?php echo esc_url( 'tel:' . it_phone_cleaner( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
										</p>
									<?php endwhile; ?>
								</div>
							</li>
						<?php endif; ?>

						<?php if ( $hours ) : ?>
							<li class="m-contacts__item">
								<svg><use xlink:href="#clock"></use></svg>
								<div class="m-contacts__content">
									<?php echo esc_html( $hours ); ?>
								</div>
							</li>
						<?php endif; ?>
					</ul>
				<?php endif; ?>

				<?php if ( $block_online ) : ?>
					<ul class="m-contacts__list">
						<?php if ( $has_emails ) : ?>
							<li class="m-contacts__item">
								<svg><use xlink:href="#email"></use></svg>
								<div class="m-contacts__content">
									<?php while ( have_rows( 'contact_emails' ) ) : the_row();
										$email_label = get_sub_field( 'label' );
										$email       = antispambot( (string) get_sub_field( 'email' ) ); ?>

										<p class="m-contacts__row">
											<?php if ( $email_label ) : ?>
												<span class="m-contacts__label">
													<?php echo esc_html( $email_label ); ?>
												</span>
											<?php endif; ?>
											<a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a>
										</p>
									<?php endwhile; ?>
								</div>
							</li>
						<?php endif; ?>

						<?php if ( $has_links ) : ?>
							<li class="m-contacts__item">
								<svg><use xlink:href="#globe"></use></svg>
								<div class="m-contacts__content">
									<?php while ( have_rows( 'contact_links' ) ) : the_row();
										$link        = get_sub_field( 'link' ) ?: false;
										$link_url    = $link ? $link['url'] : '';
										$link_title  = $link && $link['title'] ? $link['title'] : $link_url;
										$link_target = $link && '_blank' === $link['target'] ? ' target="_blank" rel="noopener"' : ''; ?>

										<?php if ( $link_url ) : ?>
											<p class="m-contacts__row">
												<a href="<?php echo esc_url( $link_url ); ?>"<?php echo $link_target; ?>><?php echo esc_html( $link_title ); ?></a>
											</p>
										<?php endif; ?>
									<?php endwhile; ?>
								</div>
							</li>
						<?php endif; ?>
					</ul>
				<?php endif; ?>
			</div>

			<?php if ( $map_url ) : ?>
				<div class="m-contacts__map">
					<iframe src="<?php echo esc_url( $map_url ); ?>" title="<?php echo esc_attr( $map_title ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
