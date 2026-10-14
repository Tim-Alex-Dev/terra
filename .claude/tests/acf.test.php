<?php
/**
 * Tests inc/acf.php without WordPress: options page, automatic ACFE thumbnail registration.
 */

$fail    = 0;
$hooks   = [];
$options = [];

function check( $name, $ok ) {
	global $fail;
	if ( ! $ok ) {
		$fail ++;
	}
	printf( "%-62s %s\n", $name, $ok ? 'PASS' : 'FAIL' );
}

// WordPress / ACF stubs
function add_action( $hook, $callback ) {
	$GLOBALS['hooks'][ $hook ][] = $callback;
}

function add_filter( $hook, $callback ) {
	$GLOBALS['hooks'][ $hook ][] = $callback;
}

function is_admin() {
	return true;
}

function acf_add_options_page( $title ) {
	$GLOBALS['options'][] = $title;
}

// A temporary theme folder with two thumbnails in dist/
$dir = sys_get_temp_dir() . '/it_theme_' . getmypid();
mkdir( $dir . '/dist/img/acfe-thumbnails', 0777, true );
touch( $dir . '/dist/img/acfe-thumbnails/contact.jpg' );
touch( $dir . '/dist/img/acfe-thumbnails/faq.jpg' );
touch( $dir . '/dist/img/acfe-thumbnails/readme.md' );
define( 'IT_DIR', $dir );
define( 'IT_IMG', 'http://x.test/dist/img/' );

require __DIR__ . '/../../inc/acf.php';

check( 'options page is NOT registered while loading (too early for ACF)', [] === $options );
foreach ( $hooks['acf/init'] ?? [] as $callback ) {
	$callback();
}
check( 'options page "Theme Settings" is registered on acf/init', [ 'Theme Settings' ] === $options );

$layouts = [];
foreach ( array_keys( $hooks ) as $hook ) {
	if ( 0 === strpos( $hook, 'acfe/flexible/thumbnail/layout=' ) ) {
		$layouts[] = substr( $hook, strlen( 'acfe/flexible/thumbnail/layout=' ) );
	}
}
sort( $layouts );
check( 'thumbnails registered from dist .jpg files (contact, faq)', [ 'contact', 'faq' ] === $layouts );
check( 'thumbnail URL points to dist/img/acfe-thumbnails', 'http://x.test/dist/img/acfe-thumbnails/faq.jpg' === it_acf_flexible_layout_thumbnail( '', [], [ 'name' => 'faq' ] ) );

array_map( 'unlink', glob( $dir . '/dist/img/acfe-thumbnails/*' ) );
rmdir( $dir . '/dist/img/acfe-thumbnails' );
rmdir( $dir . '/dist/img' );
rmdir( $dir . '/dist' );
rmdir( $dir );

echo $fail ? "\n$fail FAILED\n" : "\nALL PASS\n";
exit( $fail ? 1 : 0 );
