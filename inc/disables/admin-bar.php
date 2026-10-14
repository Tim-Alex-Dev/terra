<?php
/**
 * Admin bar without the WordPress logo menu
 */

add_action( 'wp_before_admin_bar_render', 'it_clear_admin_bar' );
function it_clear_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'wp-logo' );
}
