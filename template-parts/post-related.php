<?php
// The 3 latest posts of the current post type (single.php)

$query = new WP_Query( [
	'post_type'      => get_post_type(),
	'posts_per_page' => 3,
	'post__not_in'   => [ get_the_ID() ],
	'no_found_rows'  => true,
] );
?>

<?php if ( $query->have_posts() ) : ?>
	<section class="post-related">
		<h2 class="post-related__title">
			<?php esc_html_e( 'Our latest news', 'terra' ); ?>
		</h2>

		<div class="grid grid-md-2 grid-lg-3">
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				<div>
					<?php get_template_part( 'template-parts/post-card', get_post_type(), [ 'title_tag' => 'h3' ] ); ?>
				</div>
			<?php endwhile; ?>
		</div>
	</section>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
