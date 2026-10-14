<?php
// Social links from Theme Settings -> Socials (icons: template-parts/svg.php)

// screen reader names (field value "twitter" = X)
$labels = [
	'facebook'  => 'Facebook',
	'instagram' => 'Instagram',
	'linkedin'  => 'LinkedIn',
	'twitter'   => 'X',
	'xing'      => 'XING',
	'youtube'   => 'YouTube',
];
?>

<?php if ( have_rows( 'socials', 'option' ) ) : ?>
	<div class="socials">
		<?php while ( have_rows( 'socials', 'option' ) ) : the_row();
			$name = get_sub_field( 'name' );
			$url  = get_sub_field( 'url' ); ?>

			<?php if ( $name && $url ) : ?>
				<a class="socials__item" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="nofollow noopener" aria-label="<?php echo esc_attr( $labels[ $name ] ?? ucfirst( $name ) ); ?>">
					<svg class="icon-<?php echo esc_attr( $name ); ?>"><use xlink:href="#<?php echo esc_attr( $name ); ?>"></use></svg>
				</a>
			<?php endif; ?>
		<?php endwhile; ?>
	</div>
<?php endif; ?>
