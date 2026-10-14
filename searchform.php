<?php
// get_search_form(). No ids: the form can be on a page more than once

?>

<form class="search-form" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input class="search-form__input" name="s" type="search" value="<?php echo esc_attr( get_search_query( false ) ); ?>" placeholder="<?php esc_attr_e( 'Search', 'terra' ); ?>" aria-label="<?php esc_attr_e( 'Search', 'terra' ); ?>" required>
	<button class="search-form__submit" type="submit" aria-label="<?php esc_attr_e( 'Search', 'terra' ); ?>">
		<svg><use xlink:href="#search"></use></svg>
	</button>
</form>
