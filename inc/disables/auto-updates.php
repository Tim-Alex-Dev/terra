<?php
/**
 * No automatic plugin and theme updates (updates are done by hand, so a client site never breaks unnoticed)
 */

add_filter( 'auto_update_plugin', '__return_false' );
add_filter( 'auto_update_theme', '__return_false' );
