<?php
// <head>, site header, opens <main class="site-content"> (closed in footer.php)

$logo      = get_field( 'header_logo', 'option' );
$site_name = get_bloginfo( 'name' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> id="top">
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'terra' ); ?></a>

<header class="site-header">
	<div class="site-header__top">
		<div class="container">
			<?php if ( $logo ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" rel="home" aria-label="<?php echo esc_attr( $site_name ); ?>">
					<?php echo wp_get_attachment_image( $logo, 'medium', false, [ 'loading' => 'eager' ] ); ?>
				</a>
			<?php endif; ?>

			<?php get_template_part( 'template-parts/header-buttons', null, [ 'class' => 'header-buttons--top' ] ); ?>

			<button type="button" class="icon-burger" aria-label="<?php esc_attr_e( 'Toggle navigation', 'terra' ); ?>" aria-expanded="false"><i></i></button>
		</div>
	</div>

	<nav class="main-nav" aria-label="<?php esc_attr_e( 'Main menu', 'terra' ); ?>">
		<?php
		wp_nav_menu( [
			'theme_location'  => 'main',
			'container_class' => 'main-menu__container',
			'menu_class'      => 'main-menu',
			'depth'           => 3,
			'fallback_cb'     => false,
		] );
		?>

		<?php get_template_part( 'template-parts/header-buttons', null, [ 'class' => 'header-buttons--menu' ] ); ?>
	</nav>
</header>

<main class="site-content" id="content">
