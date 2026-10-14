<?php
// Page Builder: loads template-parts/builder/{layout}.php for every module of the page (layouts without a template are skipped)

$index = 1;
while ( have_rows( 'builder' ) ) {
	the_row();
	$layout = sanitize_file_name( get_row_layout() );

	if ( $layout && locate_template( 'template-parts/builder/' . $layout . '.php' ) ) {
		get_template_part( 'template-parts/builder/' . $layout, null, [ 'index' => $index ] );
	}
	$index++;
}
