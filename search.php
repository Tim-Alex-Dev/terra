<?php
get_header();
?>

	<div class="archive-wrapper">
		<div class="container">
			<header class="archive-header">
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'terra' ), '<strong>' . get_search_query() . '</strong>' );
					?>
				</h1>
			</header>

			<div class="grid grid-lg-2">
				<div>
					<?php get_search_form(); ?>
				</div>
			</div>

			<?php if ( have_posts() ) : ?>

				<div class="grid grid-md-2 grid-lg-3">
					<?php while ( have_posts() ) : the_post(); ?>

						<div>
							<?php get_template_part( 'template-parts/post-card', get_post_type() ); ?>
						</div>

					<?php endwhile; ?>
				</div>

				<?php get_template_part( 'template-parts/pagination' ); ?>

			<?php else : ?>

				<div class="grid grid-lg-2">
					<div>
						<?php get_template_part( 'template-parts/content-none' ); ?>
					</div>
				</div>

			<?php endif; ?>

		</div>
	</div>

<?php
get_footer();
