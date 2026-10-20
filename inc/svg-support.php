<?php
/**
 * SVG uploads in the Media Library. SVG is XML and can carry scripts, so:
 * - only administrators can upload it (`manage_options`, filter `it_svg_upload_capability`);
 * - every uploaded SVG is sanitized (allow-lists below), unparsable files are rejected.
 */

function it_svg_can_upload() {
	return current_user_can( apply_filters( 'it_svg_upload_capability', 'manage_options' ) );
}

add_filter( 'upload_mimes', 'it_svg_upload_mimes', 99 );
function it_svg_upload_mimes( $mimes = array() ) {

	if ( it_svg_can_upload() ) {
		$mimes['svg'] = 'image/svg+xml';
	}

	return $mimes;
}

add_filter( 'wp_check_filetype_and_ext', 'it_svg_upload_check', 10, 4 );
function it_svg_upload_check( $checked, $file, $filename, $mimes ) {

	if ( ! $checked['type'] ) {

		$check_filetype  = wp_check_filetype( $filename, $mimes );
		$ext             = $check_filetype['ext'];
		$type            = $check_filetype['type'];
		$proper_filename = $filename;

		if ( $type && 0 === strpos( $type, 'image/' ) && $ext !== 'svg' ) {
			$ext = $type = false;
		}

		$checked = compact( 'ext', 'type', 'proper_filename' );
	}

	return $checked;

}

// Sanitized SVG code, or false if it is not a valid SVG
function it_svg_sanitize( $svg ) {

	if ( ! is_string( $svg ) || '' === trim( $svg ) || ! class_exists( 'DOMDocument' ) ) {
		return false;
	}

	// Entity declarations can be used for XML bombs and external entity attacks.
	if ( preg_match( '/<!ENTITY/i', $svg ) ) {
		return false;
	}

	$allowed_tags = array(
		'svg', 'g', 'defs', 'symbol', 'use', 'title', 'desc', 'path', 'rect', 'circle', 'ellipse', 'line', 'polyline', 'polygon',
		'text', 'tspan', 'textpath', 'image', 'lineargradient', 'radialgradient', 'stop', 'pattern', 'clippath', 'mask', 'marker',
		'style', 'filter', 'feblend', 'fecolormatrix', 'fecomponenttransfer', 'fecomposite', 'feconvolvematrix', 'fediffuselighting',
		'fedisplacementmap', 'fedistantlight', 'fedropshadow', 'feflood', 'fefunca', 'fefuncb', 'fefuncg', 'fefuncr',
		'fegaussianblur', 'feimage', 'femerge', 'femergenode', 'femorphology', 'feoffset', 'fepointlight', 'fespecularlighting',
		'fespotlight', 'fetile', 'feturbulence',
	);

	// Matches CSS, which can load external resources or run code.
	$unsafe_css = '/@import|javascript:|expression\s*\(|behavior\s*:|url\s*+\(\s*+[\'"]?+\s*+(?!#|data:image)/i';

	$use_errors = libxml_use_internal_errors( true );
	$dom        = new DOMDocument();
	$loaded     = $dom->loadXML( $svg, LIBXML_NONET | LIBXML_NOBLANKS );
	libxml_clear_errors();
	libxml_use_internal_errors( $use_errors );

	if ( ! $loaded || ! $dom->documentElement || 'svg' !== strtolower( $dom->documentElement->localName ) ) {
		return false;
	}

	// Remove DOCTYPE and processing instructions (e.g. external stylesheets).
	foreach ( iterator_to_array( $dom->childNodes ) as $child ) {
		if ( $child instanceof DOMDocumentType || $child instanceof DOMProcessingInstruction ) {
			$dom->removeChild( $child );
		}
	}

	foreach ( iterator_to_array( $dom->getElementsByTagName( '*' ) ) as $node ) {

		if ( ! in_array( strtolower( $node->localName ), $allowed_tags, true ) ) {
			$node->parentNode->removeChild( $node );
			continue;
		}

		if ( 'style' === strtolower( $node->localName ) && preg_match( $unsafe_css, $node->textContent ) ) {
			$node->parentNode->removeChild( $node );
			continue;
		}

		foreach ( iterator_to_array( $node->attributes ) as $attr ) {
			$name  = strtolower( $attr->nodeName );
			$value = trim( $attr->nodeValue );

			$remove = 0 === strpos( $name, 'on' ) // event handlers.
			          || preg_match( '/javascript:|vbscript:|data:text/i', $value )
			          || ( in_array( $name, array( 'href', 'xlink:href' ), true ) && ! preg_match( '/^#|^data:image\/(png|jpe?g|gif|webp);base64,/i', $value ) ) // only internal links and embedded raster images.
			          || ( 'style' === $name && preg_match( $unsafe_css, $value ) );

			if ( $remove ) {
				$node->removeAttributeNode( $attr );
			}
		}
	}

	return $dom->saveXML( $dom->documentElement ) . "\n";

}

// Sanitize on upload (Media Library) and on sideload (importers, plugins, media_handle_sideload())
add_filter( 'wp_handle_upload_prefilter', 'it_svg_sanitize_upload' );
add_filter( 'wp_handle_sideload_prefilter', 'it_svg_sanitize_upload' );
function it_svg_sanitize_upload( $file ) {

	if ( empty( $file['tmp_name'] ) || empty( $file['name'] ) || 'svg' !== strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) ) ) {
		return $file;
	}

	$clean = it_svg_sanitize( file_get_contents( $file['tmp_name'] ) );

	if ( false === $clean ) {
		$file['error'] = __( 'Sorry, this SVG file is not valid or not safe, so it was rejected.', 'terra' );

		return $file;
	}

	file_put_contents( $file['tmp_name'], $clean );

	return $file;

}

// Media Library (JS): sizes for SVG previews
add_filter( 'wp_prepare_attachment_for_js', 'it_svg_response_for_js', 10, 3 );
function it_svg_response_for_js( $response, $attachment, $meta ) {

	if ( $response['mime'] == 'image/svg+xml' && empty( $response['sizes'] ) ) {

		$svg_path = get_attached_file( $attachment->ID );

		if ( ! $svg_path || ! file_exists( $svg_path ) ) {
			return $response;
		}

		$dimensions = it_svg_get_dimensions( $svg_path );

		$response['sizes'] = array(
			'full' => array(
				'url'         => $response['url'],
				'width'       => $dimensions->width,
				'height'      => $dimensions->height,
				'orientation' => $dimensions->width > $dimensions->height ? 'landscape' : 'portrait',
			),
		);

	}

	return $response;

}

// Width and height of an SVG file (attributes, or the viewBox)
function it_svg_get_dimensions( $svg ) {

	$width  = 0;
	$height = 0;

	$use_errors = libxml_use_internal_errors( true );
	$xml        = is_readable( $svg ) ? simplexml_load_file( $svg, 'SimpleXMLElement', LIBXML_NONET ) : false;
	libxml_clear_errors();
	libxml_use_internal_errors( $use_errors );

	if ( false !== $xml ) {

		$attributes = $xml->attributes();
		$width      = (int) $attributes->width;
		$height     = (int) $attributes->height;

		if ( ( ! $width || ! $height ) && ! empty( $attributes->viewBox ) ) {
			$view_box = preg_split( '/[\s,]+/', trim( (string) $attributes->viewBox ) );
			if ( 4 === count( $view_box ) ) {
				$width  = (int) $view_box[2];
				$height = (int) $view_box[3];
			}
		}
	}

	return (object) array( 'width' => $width, 'height' => $height );

}

// Attachment metadata for SVG (width, height; every image size points to the original file)
add_filter( 'wp_generate_attachment_metadata', 'it_svg_attachment_metadata', 10, 2 );
function it_svg_attachment_metadata( $metadata, $attachment_id ) {

	global $_wp_additional_image_sizes;

	$mime = get_post_mime_type( $attachment_id );

	if ( $mime == 'image/svg+xml' ) {

		$svg_path      = get_attached_file( $attachment_id );
		$relative_path = _wp_relative_upload_path( $svg_path );
		$filename      = basename( $svg_path );

		$dimensions = it_svg_get_dimensions( $svg_path );

		$metadata = array(
			'width'  => intval( $dimensions->width ),
			'height' => intval( $dimensions->height ),
			'file'   => $relative_path,
		);

		$sizes = array();
		foreach ( get_intermediate_image_sizes() as $s ) {
			$sizes[ $s ] = array( 'width' => '', 'height' => '', 'crop' => false );
			if ( isset( $_wp_additional_image_sizes[ $s ]['width'] ) ) {
				$sizes[ $s ]['width'] = intval( $_wp_additional_image_sizes[ $s ]['width'] );
			} else {
				$sizes[ $s ]['width'] = get_option( "{$s}_size_w" );
			}
			if ( isset( $_wp_additional_image_sizes[ $s ]['height'] ) ) {
				$sizes[ $s ]['height'] = intval( $_wp_additional_image_sizes[ $s ]['height'] );
			} else {
				$sizes[ $s ]['height'] = get_option( "{$s}_size_h" );
			}
			if ( isset( $_wp_additional_image_sizes[ $s ]['crop'] ) ) {
				$sizes[ $s ]['crop'] = intval( $_wp_additional_image_sizes[ $s ]['crop'] );
			} else {
				$sizes[ $s ]['crop'] = get_option( "{$s}_crop" );
			}

			// The SVG's own size scaled into the size's box (0 = no limit, never upscaled), not the box itself:
			// a box like "medium 768 x 0" would otherwise print width="768" height="1" in the <img>
			if ( $metadata['width'] && $metadata['height'] ) {
				[ $sizes[ $s ]['width'], $sizes[ $s ]['height'] ] = wp_constrain_dimensions(
					$metadata['width'],
					$metadata['height'],
					(int) $sizes[ $s ]['width'],
					(int) $sizes[ $s ]['height']
				);
			}

			$sizes[ $s ]['file']      = $filename;
			$sizes[ $s ]['mime-type'] = 'image/svg+xml';
		}
		$metadata['sizes'] = $sizes;
	}

	return $metadata;
}

// SVG previews in admin lists and the featured image box
add_action( 'admin_head', 'it_svg_admin_styles' );
function it_svg_admin_styles() {

	?>
	<style>
			.attachment svg, .widget_media_image svg {
				max-width: 100%;
				height: auto
			}
			body #set-post-thumbnail, body #postimagediv .inside img[src$=".svg"] {
				width: 100%
			}
			td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail {
				width: 100% !important;
				height: auto !important
			}
	</style>
	<?php
}
