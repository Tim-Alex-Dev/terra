<?php
/**
 * Theme constants and modules (inc/)
 */

// Contact Form 7: no automatic <p> tags, no CF7 stylesheet (the theme styles the forms)
add_filter( 'wpcf7_autop_or_not', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );

define( 'IT_DIR', get_template_directory() );
define( 'IT_URL', get_template_directory_uri() );
define( 'IT_CSS', IT_URL . '/dist/css/' );
define( 'IT_JS', IT_URL . '/dist/js/' );
define( 'IT_IMG', IT_URL . '/dist/img/' );

require IT_DIR . '/inc/admin-colors.php';
require IT_DIR . '/inc/after-theme-setup.php';
require IT_DIR . '/inc/acf.php';
require IT_DIR . '/inc/custom-post-type.php';
require IT_DIR . '/inc/disables.php';
require IT_DIR . '/inc/editor.php';
require IT_DIR . '/inc/favicon.php';
require IT_DIR . '/inc/help-func.php';
require IT_DIR . '/inc/login.php';
require IT_DIR . '/inc/menu.php';
require IT_DIR . '/inc/scripts-styles.php';
require IT_DIR . '/inc/svg-support.php';
