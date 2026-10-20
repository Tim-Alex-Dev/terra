<?php
// Favicon: theme root favicon.ico on the site, wp-admin, login and /favicon.ico (a Site Icon set in WP admin wins)
add_action( 'wp_head', 'it_favicon_link', 2 );
add_action( 'admin_head', 'it_favicon_link' );
add_action( 'login_head', 'it_favicon_link' );
function it_favicon_link() {
	if ( has_site_icon() ) {
		return;
	}

	echo '<link rel="icon" href="' . esc_url( IT_URL . '/favicon.ico' ) . '" sizes="any">' . "\n";
}

add_action( 'do_favicon', 'it_favicon_redirect', 1 ); // before core's do_favicon()
function it_favicon_redirect() {
	if ( has_site_icon() ) {
		return;
	}

	wp_safe_redirect( IT_URL . '/favicon.ico', 301 );
	exit;
}
