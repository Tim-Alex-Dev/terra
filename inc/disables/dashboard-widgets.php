<?php
/**
 * Dashboard without Quick Draft, WordPress News, Activity, At a Glance and the Welcome panel
 */

function it_remove_dashboard_widgets() {
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	remove_action( 'welcome_panel', 'wp_welcome_panel' );
}

add_action( 'wp_dashboard_setup', 'it_remove_dashboard_widgets' );
