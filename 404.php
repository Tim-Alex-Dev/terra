<?php
get_header();
?>

<div class="container">
	<section class="not-found">
		<h1 class="not-found__title">
			<?php esc_html_e( 'Page not found.', 'terra' ); ?>
		</h1>

		<p class="not-found__text">
			<?php esc_html_e( 'Sorry, the page you were looking for doesn\'t exist or has been moved.', 'terra' ); ?>
		</p>

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
			<?php esc_html_e( 'Back to homepage', 'terra' ); ?>
		</a>
	</section>
</div>

<?php
get_footer();
