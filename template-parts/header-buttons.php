<?php
// Header buttons (Theme Settings). Args: [ 'class' => 'header-buttons--top' ]
$class = trim( 'header-buttons ' . ( $args['class'] ?? '' ) );
?>

<?php if ( get_field( 'header_buttons', 'option' ) ) : ?>
	<div class="<?php echo esc_attr( $class ); ?>">
		<?php while ( have_rows( 'header_buttons', 'option' ) ) : the_row();
			$button_link   = get_sub_field( 'link' ) ?: false;
			$button_url    = $button_link ? $button_link['url'] : '';
			$button_title  = $button_link ? $button_link['title'] : '';
			$button_target = $button_link && '_blank' === $button_link['target'] ? ' target="_blank" rel="noopener"' : '';
			$button_icon   = 'windows' === get_sub_field( 'icon' ); ?>

			<?php if ( $button_url && $button_title ) : ?>
				<a class="header-buttons__item" href="<?php echo esc_url( $button_url ); ?>"<?php echo $button_target; ?>>
					<?php if ( $button_icon ) : ?>
						<svg aria-hidden="true"><use xlink:href="#windows"></use></svg>
					<?php endif; ?>
					<span><?php echo esc_html( $button_title ); ?></span>
				</a>
			<?php endif; ?>
		<?php endwhile; ?>
	</div>
<?php endif; ?>
