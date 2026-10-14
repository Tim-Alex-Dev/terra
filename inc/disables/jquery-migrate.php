<?php
/**
 * No jQuery Migrate on the front-end (~10 KB + 1 request). Off by default: old plugins may need it.
 * Enable it in disables.php only when no plugin does, and check the browser console.
 */

add_action( 'wp_default_scripts', function ( $scripts ) {
	if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
		$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, [ 'jquery-migrate' ] );
	}
} );
