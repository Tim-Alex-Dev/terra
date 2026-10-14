<?php
/**
 * Login screen: logo links to the site, site name as its text, own styles (login.scss)
 */

function it_login_url() {
	return home_url();
}

add_filter( 'login_headerurl', 'it_login_url' );

function it_login_title() {
	return get_option( 'blogname' );
}

add_filter( 'login_headertext', 'it_login_title' );

function it_login_stylesheet() {
	wp_enqueue_style( 'it-login', IT_CSS . 'login.css', [], it_asset_ver( 'css/login.css' ) );
}

add_action( 'login_enqueue_scripts', 'it_login_stylesheet' );
