<?php
// wp-admin color scheme "Theme" (assets/scss/admin-colors.scss), used for every user

function it_admin_color_scheme() {
	wp_admin_css_color(
		'theme',
		esc_html__( 'Theme', 'terra' ),
		add_query_arg( 'ver', it_asset_ver( 'css/admin-colors.css' ), IT_CSS . 'admin-colors.css' ),
		[ '#1e1e1e', '#2a2a29', '#784d96', '#ffffff' ],
		[
			'base'    => '#a7aaad',
			'focus'   => '#ffffff',
			'current' => '#ffffff',
		]
	);
}

add_action( 'admin_init', 'it_admin_color_scheme' );

add_filter( 'get_user_option_admin_color', fn() => 'theme' );
