<?php
// Minimal WP stubs so the theme file can be loaded outside WordPress.
$GLOBALS['filters'] = [];
function add_filter( $hook, $callback ) { $GLOBALS['filters'][ $hook ][] = $callback; }
function add_action() {}
function apply_filters( $tag, $v ) { return $v; }
function current_user_can() { return true; }
function __( $s ) { return $s; }
require __DIR__ . '/../../inc/svg-support.php';

$cases = [
 'clean icon'      => ['<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><path d="M0 0h10v10z" fill="#f00"/></svg>', 'keep', 'path'],
 'script tag'      => ['<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script><path d="M0 0"/></svg>', 'strip', 'script'],
 'onload attr'     => ['<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"><path d="M0 0"/></svg>', 'strip', 'onload'],
 'js href'         => ['<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><use xlink:href="javascript:alert(1)"/></svg>', 'strip', 'javascript'],
 'external href'   => ['<svg xmlns="http://www.w3.org/2000/svg"><use href="https://evil.example/x.svg#a"/></svg>', 'strip', 'evil.example'],
 'internal use ok' => ['<svg xmlns="http://www.w3.org/2000/svg"><defs><path id="a" d="M0 0"/></defs><use href="#a"/></svg>', 'keep', 'href="#a"'],
 'foreignObject'   => ['<svg xmlns="http://www.w3.org/2000/svg"><foreignObject><iframe src="x"/></foreignObject></svg>', 'strip', 'foreignObject'],
 'animate/set'     => ['<svg xmlns="http://www.w3.org/2000/svg"><a><set attributeName="href" to="javascript:alert(1)"/></a></svg>', 'strip', 'set'],
 'entity (XXE)'    => ['<?xml version="1.0"?><!DOCTYPE svg [<!ENTITY x SYSTEM "file:///etc/passwd">]><svg xmlns="http://www.w3.org/2000/svg"><text>&x;</text></svg>', 'reject', ''],
 'style @import'   => ['<svg xmlns="http://www.w3.org/2000/svg"><style>@import url(https://evil.example/a.css);</style><path d="M0 0"/></svg>', 'strip', 'evil.example'],
 'style ok'        => ['<svg xmlns="http://www.w3.org/2000/svg"><style>.a{fill:#f00}</style><path class="a" d="M0 0"/></svg>', 'keep', '.a{fill:#f00}'],
 'style attr url'  => ['<svg xmlns="http://www.w3.org/2000/svg"><rect style="fill:url(https://evil.example/x)" width="1" height="1"/></svg>', 'strip', 'evil.example'],
 'fill url(#g) ok' => ['<svg xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="g"/></defs><rect fill="url(#g)" width="1" height="1"/></svg>', 'keep', 'url(#g)'],
 'not svg'         => ['<html><body>hi</body></html>', 'reject', ''],
 'garbage'         => ['not xml at all', 'reject', ''],
 'data uri image'  => ['<svg xmlns="http://www.w3.org/2000/svg"><image href="data:image/png;base64,AAAA"/></svg>', 'keep', 'data:image/png'],
 'data uri html'   => ['<svg xmlns="http://www.w3.org/2000/svg"><image href="data:text/html;base64,AAAA"/></svg>', 'strip', 'data:text'],
];
$fail = 0;
foreach ( [ 'wp_handle_upload_prefilter', 'wp_handle_sideload_prefilter' ] as $hook ) {
	$ok = in_array( 'it_svg_sanitize_upload', $GLOBALS['filters'][ $hook ] ?? [], true );
	if ( ! $ok ) { $fail++; }
	printf( "%-16s %s\n", 'hook ' . $hook, $ok ? 'PASS' : 'FAIL' );
}
foreach ( $cases as $name => [ $in, $expect, $needle ] ) {
	$out = it_svg_sanitize( $in );
	if ( 'reject' === $expect ) { $ok = false === $out; }
	elseif ( 'keep' === $expect ) { $ok = is_string( $out ) && false !== strpos( $out, $needle ); }
	else { $ok = is_string( $out ) && false === stripos( $out, $needle ); }
	if ( ! $ok ) { $fail++; }
	printf( "%-16s %s\n", $name, $ok ? 'PASS' : 'FAIL  -> ' . var_export( $out, true ) );
}
echo $fail ? "\n$fail FAILED\n" : "\nALL PASS\n";
