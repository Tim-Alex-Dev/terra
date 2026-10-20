<?php
/**
 * Classic editor (TinyMCE, also in ACF WYSIWYG fields): editor styles, "Formats" menu, text colors
 */

add_action( 'admin_init', 'it_add_editor_styles' );
function it_add_editor_styles() {
	add_editor_style( IT_CSS . 'editor-styles.css' );
}

// "Formats" dropdown with the theme classes below (styles: 1-2-typography/_editor.scss, _lists.scss, 1-3-forms/_buttons.scss)
add_filter( 'mce_buttons_2', 'it_tiny_mce_style_formats' );
function it_tiny_mce_style_formats( $styles ) {
	array_unshift( $styles, 'styleselect' );

	return $styles;
}

add_filter( 'tiny_mce_before_init', 'it_tiny_mce_before_init_formats' );
function it_tiny_mce_before_init_formats( $settings ) {
	$style_formats = [
		[
			'title' => 'Titles',
			'items' => [
				[
					'title'    => 'H1',
					'selector' => 'p,h1,h2,h3,h4,h5,h6',
					'classes'  => 'h1',
				],
				[
					'title'    => 'H2',
					'selector' => 'p,h1,h2,h3,h4,h5,h6',
					'classes'  => 'h2',
				],
				[
					'title'    => 'H3',
					'selector' => 'p,h1,h2,h3,h4,h5,h6',
					'classes'  => 'h3',
				],
				[
					'title'    => 'H4',
					'selector' => 'p,h1,h2,h3,h4,h5,h6',
					'classes'  => 'h4',
				],
				[
					'title'    => 'H5',
					'selector' => 'p,h1,h2,h3,h4,h5,h6',
					'classes'  => 'h5',
				],
				[
					'title'    => 'H6',
					'selector' => 'p,h1,h2,h3,h4,h5,h6',
					'classes'  => 'h6',
				],
			]
		],
		[
			'title' => 'Text',
			'items' => [
				[
					'title'    => 'Text (lg)',
					'selector' => 'p,h1,h2,h3,h4,h5,h6',
					'classes'  => 'text-lg',
				],
				[
					'title'    => 'Text (sm)',
					'selector' => 'p,h1,h2,h3,h4,h5,h6',
					'classes'  => 'text-sm',
				],
			],
		],
		[
			'title' => 'Buttons',
			'items' => [
				[
					'title'    => 'Button (primary)',
					'selector' => 'a',
					'classes'  => 'btn btn-primary',
					'wrapper'  => false,
				],
				[
					'title'    => 'Button (outline primary)',
					'selector' => 'a',
					'classes'  => 'btn btn-outline btn-primary',
					'wrapper'  => false,
				],
				[
					'title'    => 'Button Group',
					'classes'  => 'btn-group', // styles: 1-3-forms/_buttons.scss (front) + editor-styles.scss (editor)
					'selector' => 'p',
				],
			]
		],
		[
			'title' => 'Lists',
			'items' => [
				[
					'title'    => 'List (checked)',
					'classes'  => 'list-check',
					'selector' => 'ul',
					'wrapper'  => false,
				],
				[
					'title'    => 'List (dotted)',
					'classes'  => 'list-dot',
					'selector' => 'ul',
					'wrapper'  => false,
				],
				[
					'title'    => 'List (numbered)',
					'classes'  => 'list-number',
					'selector' => 'ol',
					'wrapper'  => false,
				],
			]
		],
	];

	if ( isset( $settings['style_formats'] ) ) {
		$orig_style_formats = json_decode( $settings['style_formats'], true );
		$style_formats      = array_merge( $orig_style_formats, $style_formats );
	}

	$settings['style_formats'] = wp_json_encode( $style_formats );

	return $settings;
}

// Text colors = the theme palette (keep in sync with assets/scss/0-settings/_!variables.scss)
add_filter( 'tiny_mce_before_init', 'it_tiny_mce_before_init_colors' );
function it_tiny_mce_before_init_colors( $init ) {
	$custom_colors = [
		'Black'   => '000000',
		'White'   => 'ffffff',
		'Text'    => '3c3c3c',
		'Grey'    => 'cccccc',
		'Primary' => '784d96',
	];

	$textcolor_map = [];
	foreach ( $custom_colors as $name => $color ) {
		$textcolor_map[] = "\"$color\", \"$name\"";
	}

	if ( ! empty( $textcolor_map ) ) {
		$init['textcolor_map']  = '[' . implode( ', ', $textcolor_map ) . ']';
		$init['textcolor_rows'] = 6;
	}

	return $init;
}
