<?php
/**
 * Scripts and styles
 */

// Version of a built file (path relative to dist/) = its modification time: browsers reload it only after a new build
function it_asset_ver( $file ) {
	$path = IT_DIR . '/dist/' . ltrim( $file, '/' );

	return file_exists( $path ) ? filemtime( $path ) : wp_get_theme()->get( 'Version' );
}

function it_scripts() {
	wp_enqueue_style( 'theme-styles', IT_CSS . 'main.css', [], it_asset_ver( 'css/main.css' ) );

	wp_enqueue_script( 'theme-js', IT_JS . 'main.js', [ 'jquery' ], it_asset_ver( 'js/main.js' ), true );
	wp_localize_script( 'theme-js', 'itSettings', [
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'ajax-nonce' ),
	] );
}

add_action( 'wp_enqueue_scripts', 'it_scripts' );

add_action( 'admin_enqueue_scripts', function () {
	wp_enqueue_style( 'admin-styles', IT_CSS . 'admin-styles.css', [], it_asset_ver( 'css/admin-styles.css' ) );
	wp_enqueue_script( 'admin-js', IT_JS . 'admin.js', [ 'jquery' ], it_asset_ver( 'js/admin.js' ), true );
	wp_localize_script( 'admin-js', 'wpAdminSettings', [
		'nonce' => wp_create_nonce( 'ajax-admin-nonce' ), // the admin AJAX URL is the global `ajaxurl` of WordPress
	] );
}, 99 );
