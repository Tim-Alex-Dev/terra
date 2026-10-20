<?php
// Closes <main>, site footer, SVG sprite, to-top button

$logo          = get_field( 'footer_logo', 'option' ) ?: get_field( 'header_logo', 'option' );
$enable_to_top = get_field( 'enable_to_top', 'option' );
?>

</main><!-- /.site-content -->

<footer class="site-footer">
	<?php
	wp_nav_menu( [
		'theme_location'       => 'footer',
		'container'            => 'nav',
		'container_class'      => 'footer-links__container',
		'container_aria_label' => __( 'Footer menu', 'terra' ),
		'menu_class'           => 'footer-links',
		'depth'                => 2,
		'fallback_cb'          => false,
	] );
	?>

	<div class="site-footer__bottom">
		<div class="container">
			<?php if ( $logo ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-footer__logo" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php echo wp_get_attachment_image( $logo, 'medium' ); ?>
				</a>
			<?php endif; ?>

			<div class="site-footer__legal">
				<?php
				wp_nav_menu( [
					'theme_location'       => 'footer-bottom',
					'container'            => 'nav',
					'container_aria_label' => __( 'Legal links', 'terra' ),
					'menu_class'           => 'footer-bottom-links',
					'depth'                => 1,
					'fallback_cb'          => false,
				] );
				?>
				<span class="site-footer__copyright">&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> WORTMANN AG</span>
			</div>
		</div>
	</div>
</footer>

<?php get_template_part( 'template-parts/svg' ); ?>

<?php if ( $enable_to_top && ! is_404() ) : ?>
	<a id="to-top" href="#top">
		<svg><use xlink:href="#angle-up"></use></svg>
		<span class="screen-reader-text"><?php esc_html_e( 'Scroll to top', 'terra' ); ?></span>
	</a>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>
