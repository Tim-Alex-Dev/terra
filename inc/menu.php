<?php
// Main menu: a parent without a real link ("#" / empty) is a <button class="menu-toggle"> (navigation.js)
add_filter( 'walker_nav_menu_start_el', 'it_menu_toggle_button', 10, 4 );
function it_menu_toggle_button( $item_output, $item, $depth, $args ) {
	if ( 'main' !== ( $args->theme_location ?? '' ) || ! it_menu_item_is_toggle( $item->url, (array) $item->classes ) ) {
		return $item_output;
	}

	return ( $args->before ?? '' )
		. '<button type="button" class="menu-toggle" aria-expanded="false">'
		. ( $args->link_before ?? '' ) . esc_html( $item->title ) . ( $args->link_after ?? '' )
		. '</button>'
		. ( $args->after ?? '' );
}

// Footer menu: a column title without a real link is a heading
add_filter( 'walker_nav_menu_start_el', 'it_footer_menu_column_title', 10, 4 );
function it_footer_menu_column_title( $item_output, $item, $depth, $args ) {
	if ( 'footer' !== ( $args->theme_location ?? '' ) || 0 !== $depth || ! it_menu_item_is_toggle( $item->url, (array) $item->classes ) ) {
		return $item_output;
	}

	return '<h2 class="footer-links__title">' . esc_html( $item->title ) . '</h2>';
}

function it_menu_item_is_toggle( $url, $classes ) {
	$url = trim( (string) $url );

	return in_array( 'menu-item-has-children', $classes, true ) && ( '' === $url || '#' === $url );
}
