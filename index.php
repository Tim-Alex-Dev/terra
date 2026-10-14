<?php
get_header();
?>

	<div class="archive-wrapper">
		<div class="container">

			<header class="archive-header">
				<?php if ( is_home() && ! is_front_page() ) : ?>
					<h1 class="page-title"><?php single_post_title(); ?></h1>
				<?php else : ?>
					<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="archive-description">', '</div>' );
					?>
				<?php endif; ?>
			</header>

			<?php if ( have_posts() ) : ?>

				<div class="grid grid-md-2 grid-lg-3">
					<?php while ( have_posts() ) : the_post(); ?>

						<div>
							<?php get_template_part( 'template-parts/post-card', get_post_type() ); ?>
						</div>

					<?php endwhile; ?>
				</div>
				<?php get_template_part( 'template-parts/pagination' ); ?>

			<?php else: ?>

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
