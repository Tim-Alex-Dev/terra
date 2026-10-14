<?php
/**
 * Theme supports, menus and small front-end filters
 */

function it_setup() {
	// translations: .mo files in the theme's /languages/ folder (e.g. created by Loco Translate)
	load_theme_textdomain( 'terra', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	// custom image sizes: add_image_size( 'name', $width, $height, $crop ); (only if really needed)

	register_nav_menus( [
		'main'   => esc_html__( 'Main Nav', 'terra' ),
		'footer' => esc_html__( 'Footer Nav', 'terra' ),
	] );

	add_theme_support( 'html5', [
		'search-form',
		'gallery',
		'caption',
		'style',
		'script',
	] );
}

add_action( 'after_setup_theme', 'it_setup' );

// Max width of embeds and large images in the content (px)
function it_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'it_content_width', 1130 );
}

add_action( 'after_setup_theme', 'it_content_width', 0 );

// Body class with the post type and slug of the current page, e.g. "page-contact"
function it_slug_body_class( $classes ) {
	if ( is_singular() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			$classes[] = $post->post_type . '-' . $post->post_name;
		}
	}

	return $classes;
}

add_filter( 'body_class', 'it_slug_body_class' );

// Archive titles without "Category:", "Tag:"...
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

// Automatic excerpts: 20 words, no "[...]"
add_filter( 'excerpt_length', function () {
	return 20;
} );

add_filter( 'excerpt_more', '__return_empty_string' );
