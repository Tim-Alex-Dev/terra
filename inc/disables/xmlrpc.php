<?php
/**
 * No XML-RPC (a common brute-force target) and no pingback header
 */

add_filter( 'xmlrpc_enabled', '__return_false' );

add_filter( 'wp_headers', function ( $headers ) {
	unset( $headers['X-Pingback'] );

	return $headers;
} );
