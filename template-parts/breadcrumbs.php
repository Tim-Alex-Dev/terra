<?php
// Yoast breadcrumbs (not used in this project)

if ( function_exists( 'yoast_breadcrumb' ) && ! is_front_page() ) : ?>
	<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumbs', 'terra' ); ?>">
		<div class="container">
			<?php yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' ); ?>
		</div>
	</nav>
<?php endif; ?>
