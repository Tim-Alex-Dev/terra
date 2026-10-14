<?php
global $wp_query;

if ( $wp_query->max_num_pages < 2 ) {
	return;
}
?>

<nav class="pagination" aria-label="<?php esc_attr_e( 'Pagination', 'terra' ); ?>">
	<?php
	echo paginate_links( [
		'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
		'total'     => $wp_query->max_num_pages,
		'current'   => max( 1, get_query_var( 'paged' ) ),
		'format'    => '?paged=%#%',
		'type'      => 'list',
		'end_size'  => 2,
		'mid_size'  => 1,
		'prev_text' => esc_html__( '&larr; Prev', 'terra' ),
		'next_text' => esc_html__( 'Next &rarr;', 'terra' ),
	] );
	?>
</nav>
