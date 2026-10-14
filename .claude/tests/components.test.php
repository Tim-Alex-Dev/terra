<?php
/**
 * Tests the builder components without WordPress: template-parts/builder/components/*.php (headings.php)
 * read the sub fields of the current module row (stubbed below) and must escape them.
 */

$fail = 0;

function check( $name, $ok ) {
	global $fail;
	if ( ! $ok ) {
		$fail ++;
	}
	printf( "%-62s %s\n", $name, $ok ? 'PASS' : 'FAIL' );
}

// WordPress / ACF stubs
function esc_attr( $s ) {
	return htmlspecialchars( (string) $s, ENT_QUOTES );
}

function esc_html( $s ) {
	return htmlspecialchars( (string) $s, ENT_QUOTES );
}

function wp_kses_post( $s ) {
	return strip_tags( (string) $s, '<br><strong><em><span>' );
}

$row = []; // sub fields of the current module row
function get_sub_field( $name ) {
	return $GLOBALS['row'][ $name ] ?? null;
}

$row_index = 2; // position of the module on the page (ACF get_row_index(), 1 = first module)
function get_row_index() {
	return $GLOBALS['row_index'];
}

function tag_escape( $tag ) {
	return strtolower( preg_replace( '/[^a-zA-Z0-9-_:]/', '', $tag ) ); // like core
}

// Renders a component like get_template_part( ..., null, $args ) does, inside a module row with the given fields.
// Whitespace between tags is removed, so the checks do not depend on the template's line breaks.
function render( $component, $fields, $args = [] ) {
	$GLOBALS['row'] = $fields;
	ob_start();
	include __DIR__ . '/../../template-parts/builder/components/' . $component . '.php';

	return trim( preg_replace( [ '/>\s+/', '/\s+</' ], [ '>', '<' ], ob_get_clean() ) );
}

// Headings
$html = render( 'headings', [ 'title' => 'Our <strong>services</strong><script>x</script>', 'uptitle' => 'What we do', 'subtitle' => 'Sub <b>text</b>' ] );
check( 'headings: default tag h2, no extra class', false !== strpos( $html, '<h2 class="module-headings__title">' ) );
check( 'headings: allowed HTML kept, script removed', false !== strpos( $html, '<strong>services</strong>' ) && false === strpos( $html, '<script>' ) );
check( 'headings: uptitle and subtitle escaped', false !== strpos( $html, 'module-headings__uptitle">What we do' ) && false !== strpos( $html, 'Sub &lt;b&gt;text&lt;/b&gt;' ) );
check( 'headings: order uptitle, title, subtitle', strpos( $html, '__uptitle' ) < strpos( $html, '__title' ) && strpos( $html, '__title' ) < strpos( $html, '__subtitle' ) );

check( 'headings: wrapper with default alignment left', 0 === strpos( $html, '<div class="module-headings alignment-left">' ) );

$html = render( 'headings', [ 'title' => 'T', 'title_tag' => 'h1', 'title_alignment' => 'center' ], [ 'class' => 'extra' ] );
check( 'headings: tag h1 + extra class from $args', false !== strpos( $html, '<h1 class="module-headings__title extra">T</h1>' ) );
check( 'headings: center alignment on the wrapper', false !== strpos( $html, 'alignment-center' ) );
check( 'headings: no uptitle/subtitle markup when empty', false === strpos( $html, '__uptitle' ) && false === strpos( $html, '__subtitle' ) );

$html = render( 'headings', [ 'title' => 'T', 'title_tag' => 'h3" onclick="x', 'title_alignment' => 'evil" onclick="x' ] );
check( 'headings: tag and alignment escaped', 0 === strpos( $html, '<div class="module-headings alignment-evil&quot;' ) && false === strpos( $html, '" onclick="' ) );

$GLOBALS['row_index'] = 1;
check( 'headings: no tag chosen, first module -> h1', false !== strpos( render( 'headings', [ 'title' => 'T' ] ), '<h1 class="module-headings__title">T</h1>' ) );
check( 'headings: chosen tag wins in the first module', false !== strpos( render( 'headings', [ 'title' => 'T', 'title_tag' => 'h2' ] ), '<h2 ' ) );
$GLOBALS['row_index'] = 2;

check( 'headings: nothing when all fields are empty', '' === render( 'headings', [ 'title' => '', 'uptitle' => null ] ) );

echo $fail ? "\n$fail FAILED\n" : "\nALL PASS\n";
exit( $fail ? 1 : 0 );
