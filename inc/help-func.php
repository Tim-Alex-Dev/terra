<?php
/**
 * Helper functions used in templates
 */

// Post date (published, and updated if different)
function it_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( DATE_W3C ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
	/* translators: %s: post date. */
		esc_html_x( 'Posted on %s', 'post date', 'terra' ), $time_string
	);

	echo '<span class="posted-on">' . $posted_on . '</span>';
}

// Post author with a link to the author archive
function it_posted_by() {
	$byline = sprintf(
	/* translators: %s: post author. */
		esc_html_x( 'by %s', 'post author', 'terra' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="byline"> ' . $byline . '</span>';
}

// Post categories
function it_cat_links() {
	$categories_list = get_the_category_list( esc_html__( ', ', 'terra' ) );
	if ( $categories_list ) {
		printf( '<div class="cat-links">' . esc_html__( 'Posted in %1$s', 'terra' ) . '</div>', $categories_list );
	}
}

// Post tags
function it_tag_links() {
	$tags_list = get_the_tag_list( '', ', ' );
	if ( $tags_list ) {
		printf( '<div class="tag-links">' . esc_html__( 'Tagged %1$s', 'terra' ) . '</div>', $tags_list );
	}
}

// Excerpt of the current post, limited to $word_limit words ("..." when cut), without HTML
function it_excerpt( $word_limit ) {
	echo esc_html( wp_trim_words( get_the_excerpt(), $word_limit, '...' ) );
}

/**
 * Ready `id` attribute from a user value: ' id="our-team"' for "Our Team", '' when empty.
 * Usage: $module_id = it_id_attr( get_sub_field( 'module_id' ) ); <section<?php echo $module_id; ?> ...>
 */
function it_id_attr( $id ) {
	$id = sanitize_title( (string) $id );

	return $id ? ' id="' . esc_attr( $id ) . '"' : '';
}

// Phone number for tel: links (digits and +)
function it_phone_cleaner( $tel ) {
	return preg_replace( '/[^+\d]+/', '', $tel );
}

// [email]name@example.com[/email]: e-mail link hidden from spam bots
function it_hide_email_shortcode( $atts, $content = null ) {
	if ( ! is_email( $content ) ) {
		return '';
	}

	$email = antispambot( $content );

	return sprintf( '<a href="%s">%s</a>', esc_url( "mailto:$email" ), esc_html( $email ) );
}

add_shortcode( 'email', 'it_hide_email_shortcode' );

// Placeholder for a missing image (styles: 5-components/_image-placeholder.scss)
function it_image_placeholder() {
	echo '<span class="img-placeholder"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm16 336c0 8.822-7.178 16-16 16H48c-8.822 0-16-7.178-16-16V112c0-8.822 7.178-16 16-16h416c8.822 0 16 7.178 16 16v288zM112 232c30.928 0 56-25.072 56-56s-25.072-56-56-56-56 25.072-56 56 25.072 56 56 56zm0-80c13.234 0 24 10.766 24 24s-10.766 24-24 24-24-10.766-24-24 10.766-24 24-24zm207.029 23.029L224 270.059l-31.029-31.029c-9.373-9.373-24.569-9.373-33.941 0l-88 88A23.998 23.998 0 0 0 64 344v28c0 6.627 5.373 12 12 12h360c6.627 0 12-5.373 12-12v-92c0-6.365-2.529-12.47-7.029-16.971l-88-88c-9.373-9.372-24.569-9.372-33.942 0zM416 352H96v-4.686l80-80 48 48 112-112 80 80V352z"/></svg></span>';
}

/**
 * Debug: shows a PHP value in the browser console. $as_text: true = var_export() text, false = JS object.
 * Only for administrators or when WP_DEBUG_DISPLAY is on, so a forgotten call never shows data to visitors.
 */
function it_console_log( $content = null, $as_text = true ) {
	if ( ! current_user_can( 'manage_options' ) && ! ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY ) ) {
		return;
	}

	if ( empty( $GLOBALS['it_console_log'] ) ) {
		add_action( is_admin() ? 'admin_footer' : 'wp_footer', 'it_console_log_print', 100 );
	}

	$GLOBALS['it_console_log'][] = $as_text ? var_export( $content, true ) : $content;
}

// Prints the collected values once, in the footer (wp_json_encode() escapes "</script>")
function it_console_log_print() {
	echo '<script>';
	foreach ( $GLOBALS['it_console_log'] as $value ) {
		echo 'console.log("PHP debug:", ' . wp_json_encode( $value ) . ');';
	}
	echo '</script>';
}

/**
 * Code of an SVG file, to print it inline ('' if it is not a valid SVG).
 * Local files (uploads, theme) are read from disk: uploads are sanitized on upload (svg-support.php), remote files here.
 */
function it_inline_svg( $svg_url ) {
	$svg_url = strtok( (string) $svg_url, '?#' );
	$uploads = wp_get_upload_dir();

	$roots = [
		$uploads['baseurl'] => $uploads['basedir'],
		IT_URL              => IT_DIR,
	];
	foreach ( $roots as $root_url => $root_dir ) {
		if ( 0 !== strpos( $svg_url, $root_url ) ) {
			continue;
		}
		$path = realpath( $root_dir . substr( $svg_url, strlen( $root_url ) ) );
		if ( $path && 0 === strpos( $path, realpath( $root_dir ) ) && 'svg' === strtolower( pathinfo( $path, PATHINFO_EXTENSION ) ) ) {
			return (string) file_get_contents( $path );
		}
	}

	$svg = it_svg_sanitize( wp_remote_retrieve_body( wp_remote_get( $svg_url ) ) );

	return false === $svg ? '' : $svg;
}
