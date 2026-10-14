<?php
/**
 * No block editor (the theme uses the ACF Page Builder) and no block styles on the front-end
 */

add_filter( 'use_block_editor_for_post', '__return_false', 10 );

add_action( 'wp_enqueue_scripts', 'it_remove_block_css', 100 );
function it_remove_block_css() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );
}

// Global styles (theme.json CSS variables): core prints them in the head and in the footer
add_action( 'init', 'it_remove_global_css' );
function it_remove_global_css() {
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
}
