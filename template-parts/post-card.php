<?php
/**
 * Post card (blog, search, related posts). A post type can get its own: template-parts/post-card-{post_type}.php
 * Optional: $args['title_tag'] (default h2; h3 below an h2, e.g. related posts)
 */

$title_tag = tag_escape( $args['title_tag'] ?? 'h2' );
?>

<article class="post-card">
	<?php // repeats the title link: for the mouse only ?>
	<a class="post-card__thumbnail" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large', [ 'class' => 'img-cover' ] ); ?>
		<?php else : ?>
			<?php it_image_placeholder(); ?>
		<?php endif; ?>
	</a>

	<div class="post-card__content">
		<div class="entry-meta">
			<?php it_posted_on(); ?> <?php it_posted_by(); ?>
			<?php it_cat_links(); ?>
			<?php it_tag_links(); ?>
		</div>

		<<?php echo $title_tag; ?> class="post-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</<?php echo $title_tag; ?>>

		<div class="post-card__excerpt">
			<?php it_excerpt( 15 ); ?>
		</div>

		<a class="btn btn-primary post-card__more" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Read more', 'terra' ); ?><span class="screen-reader-text">: <?php the_title(); ?></span>
		</a>
	</div>
</article>
