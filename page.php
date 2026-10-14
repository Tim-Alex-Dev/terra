<?php
get_header();
the_post();
?>

<?php get_template_part( 'template-parts/breadcrumbs' ); ?>

<?php if ( have_rows( 'builder' ) ) : ?>

	<?php get_template_part( 'template-parts/builder' ); ?>

<?php else : ?>

	<div class="container">
		<div class="content-narrow">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</div>
	</div>

<?php endif; ?>

<?php
get_footer();
