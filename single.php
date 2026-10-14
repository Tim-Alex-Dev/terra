<?php
get_header();
the_post();
?>

<?php get_template_part( 'template-parts/breadcrumbs' ); ?>

<div class="container">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<?php the_post_thumbnail( '1536x1536', [ 'loading' => 'eager', 'fetchpriority' => 'high' ] ); // main image of the page: loaded first ?>
		</div>
	<?php endif; ?>

	<div class="content-narrow">
		<header class="post-header">
			<h1 class="post-title"><?php the_title(); ?></h1>
			<div class="entry-meta">
				<?php it_posted_on(); ?><?php it_posted_by(); ?>
				<?php it_cat_links(); ?>
				<?php it_tag_links(); ?>
			</div>
		</header>

		<div class="entry-content">
			<?php
			the_content();

			wp_link_pages( [
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'terra' ),
				'after'  => '</div>',
			] );

			the_post_navigation( [
				'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'terra' ) . '</span> <span class="nav-title">%title</span>',
				'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'terra' ) . '</span> <span class="nav-title">%title</span>',
			] );
			?>
		</div>
	</div>

	<?php get_template_part( 'template-parts/post-related' ); ?>
</div>

<?php
get_footer();
