<?php
/**
 * No wp-embed.js (embedding other WordPress posts)
 */

function it_wpembed_deregister_scripts() {
	wp_dequeue_script( 'wp-embed' );
}

add_action( 'wp_footer', 'it_wpembed_deregister_scripts' );
