<?php
/**
 * Unneeded tags in <head>: WordPress version, RSD (XML-RPC clients), Windows Live Writer manifest, shortlink
 */

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
