<?php
function add_filter() {} function add_shortcode() {}
$GLOBALS['actions'] = []; function add_action( $hook, $cb ) { $GLOBALS['actions'][] = $hook . ':' . $cb; }
function is_admin() { return false; } function wp_json_encode( $v ) { return json_encode( $v ); }
function apply_filters( $t, $v ) { return $v; } function current_user_can() { return true; } function __( $s ) { return $s; }
function esc_html( $s ) { return htmlspecialchars( $s, ENT_QUOTES ); }
function wp_strip_all_tags( $s ) { return trim( strip_tags( $s ) ); }
function esc_url( $s ) { return $s; }
function esc_attr( $s ) { return htmlspecialchars( $s, ENT_QUOTES ); }
function sanitize_title( $s ) { return trim( preg_replace( "/[^a-z0-9]+/", "-", strtolower( strip_tags( $s ) ) ), "-" ); } // simplified core behaviour
$GLOBALS['excerpt'] = '';
function get_the_excerpt() { return $GLOBALS['excerpt']; }
function wp_get_upload_dir() { return [ 'baseurl' => 'http://x.test/wp-content/uploads', 'basedir' => dirname( __DIR__, 2 ) . '/assets/img' ]; }
$GLOBALS['remote'] = [ 'urls' => [], 'body' => '' ];
function wp_remote_get( $u ) { $GLOBALS['remote']['urls'][] = $u; return $GLOBALS['remote']['body']; }
function wp_trim_words( $text, $num, $more ) { $w = preg_split( '/\s+/', wp_strip_all_tags( $text ), -1, PREG_SPLIT_NO_EMPTY ); return count( $w ) > $num ? implode( ' ', array_slice( $w, 0, $num ) ) . $more : implode( ' ', $w ); } // like core
function wp_remote_retrieve_body( $r ) { return $r; }
define( 'IT_URL', 'http://x.test/wp-content/themes/t' ); define( 'IT_DIR', dirname( __DIR__, 2 ) );
require __DIR__ . '/../../inc/help-func.php';
require __DIR__ . '/../../inc/svg-support.php';

$fail = 0;
function check( $name, $ok ) { global $fail; if ( ! $ok ) $fail++; printf( "%-38s %s\n", $name, $ok ? 'PASS' : 'FAIL' ); }

// real theme SVGs survive sanitising and keep their shapes
foreach ( glob( dirname( __DIR__, 2 ) . '/assets/img/*.svg' ) as $f ) {
	$src = file_get_contents( $f ); $out = it_svg_sanitize( $src );
	$n1 = preg_match_all( '/<(path|circle|rect|g|polygon)\b/', $src ); $n2 = is_string( $out ) ? preg_match_all( '/<(path|circle|rect|g|polygon)\b/', $out ) : -1;
	check( 'sanitize ' . basename( $f ) . " ($n1 shapes)", $n1 === $n2 );
}
// dimensions incl. viewBox fallback
$tmp = sys_get_temp_dir() . '/vb.svg'; file_put_contents( $tmp, '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 12"></svg>' );
$d = it_svg_get_dimensions( $tmp ); check( 'viewBox dimensions 24x12', 24 === $d->width && 12 === $d->height );
$tmp2 = sys_get_temp_dir() . '/wh.svg'; file_put_contents( $tmp2, '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="50"></svg>' );
$d = it_svg_get_dimensions( $tmp2 ); check( 'width/height dimensions 100x50', 100 === $d->width && 50 === $d->height );
$d = it_svg_get_dimensions( '/nonexistent.svg' ); check( 'missing file -> 0x0 (no warning)', 0 === $d->width );

// it_excerpt
ob_start(); $GLOBALS['excerpt'] = 'one two three four five six seven'; it_excerpt( 4 ); $o = ob_get_clean(); check( 'excerpt: 4 words -> "one two three four..."', 'one two three four...' === $o );
ob_start(); $GLOBALS['excerpt'] = 'one two'; it_excerpt( 4 ); $o = ob_get_clean(); check( 'excerpt: short text unchanged', 'one two' === $o );
ob_start(); $GLOBALS['excerpt'] = 'a^b <script>x</script>'; it_excerpt( 10 ); $o = ob_get_clean(); check( 'excerpt keeps ^, strips tags', false === strpos( $o, '<' ) && false !== strpos( $o, 'a^b' ) );

// it_inline_svg
$o = it_inline_svg( 'http://x.test/wp-content/uploads/logo.svg?ver=1' ); check( 'inline svg from local uploads', false !== strpos( $o, '<svg' ) && [] === $GLOBALS['remote']['urls'] );
$o = it_inline_svg( 'http://x.test/wp-content/uploads/../../package.json' ); check( 'path traversal not read from disk', '' === $o && 1 === count( $GLOBALS['remote']['urls'] ) );
$o = it_inline_svg( 'http://x.test/wp-content/uploads/../../functions.php' ); check( 'non-svg not read from disk', '' === $o && 2 === count( $GLOBALS['remote']['urls'] ) );
$GLOBALS['remote']['body'] = '<svg xmlns="http://www.w3.org/2000/svg" onload="x()"><script>alert(1)</script><path d="M0 0"/></svg>';
$o = it_inline_svg( 'https://other.test/a.svg' ); check( 'remote svg is sanitized', false !== strpos( $o, '<path' ) && false === strpos( $o, 'script' ) && false === strpos( $o, 'onload' ) );
$GLOBALS['remote']['body'] = '<html>not an svg</html>';
check( 'remote non-svg -> empty', '' === it_inline_svg( 'https://other.test/b.svg' ) );

// it_console_log (current_user_can() is stubbed true = administrator)
it_console_log( [ 'a' => 1 ] ); it_console_log( '</script><b>', false );
check( 'console log: footer action added once', 1 === count( array_filter( $GLOBALS['actions'], function ( $a ) { return 'wp_footer:it_console_log_print' === $a; } ) ) );
ob_start(); it_console_log_print(); $o = ob_get_clean();
check( 'console log: both values, script safe', 2 === substr_count( $o, 'console.log(' ) && 1 === substr_count( $o, '</script>' ) );

// it_id_attr
check( 'id attr from a value', ' id="our-team"' === it_id_attr( 'Our Team' ) );
check( 'id attr: empty / null -> nothing', '' === it_id_attr( '' ) && '' === it_id_attr( null ) && '' === it_id_attr( '!!' ) );
check( 'id attr: no quotes or handlers pass', false === strpos( it_id_attr( 'x" onclick="y' ), '" on' ) );
echo $fail ? "\n$fail FAILED\n" : "\nALL PASS\n";
