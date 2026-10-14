<?php
/**
 * ACF Pro + ACF Extended setup (both are required)
 */

// Theme Settings options page. On acf/init: earlier, ACF would load its translations too early (notice since WP 6.7)
add_action( 'acf/init', function () {
	acf_add_options_page( 'Theme Settings' );
} );

// Page Builder layout previews in admin: every dist/img/acfe-thumbnails/{layout name}.jpg (400x320px) is used automatically
if ( is_admin() ) {
	foreach ( (array) glob( IT_DIR . '/dist/img/acfe-thumbnails/*.jpg' ) as $it_thumbnail_file ) {
		add_filter( 'acfe/flexible/thumbnail/layout=' . basename( $it_thumbnail_file, '.jpg' ), 'it_acf_flexible_layout_thumbnail', 10, 3 );
	}
}

function it_acf_flexible_layout_thumbnail( $thumbnail, $field, $layout ) {
	return IT_IMG . 'acfe-thumbnails/' . $layout['name'] . '.jpg';
}

// ACF Extended modules the theme does not use
add_action( 'acfe/init', 'it_acfe_modules' );
function it_acfe_modules() {
	acf_update_setting( 'acfe/modules/ui', false );
	acf_update_setting( 'acfe/modules/taxonomies', false );
	acf_update_setting( 'acfe/modules/post_types', false );
	acf_update_setting( 'acfe/modules/author', false );
	acf_update_setting( 'acfe/modules/block_types', false );
	acf_update_setting( 'acfe/modules/forms', false );
	acf_update_setting( 'acfe/modules/multilang', false );
	acf_update_setting( 'acfe/modules/options', false );
	acf_update_setting( 'acfe/modules/options_pages', false );
	acf_update_setting( 'acfe/modules/categories', false );
}
