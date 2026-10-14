<?php
// function it_custom_init() {
// 	/**
// 	 * Register custom post types and taxonomies
// 	 *
// 	 * @link https://developer.wordpress.org/reference/functions/register_post_type/
// 	 * @link https://developer.wordpress.org/reference/functions/register_taxonomy/
// 	 * @link https://developer.wordpress.org/resource/dashicons/
// 	 */

// 	// CPT: Project
// 	$labels = array(
// 		'name'          => __( 'Projects', 'terra' ),
// 		'singular_name' => __( 'Project', 'terra' ),
// 	);

// 	$args = array(
// 		'label'               => __( 'Project', 'terra' ),
// 		'labels'              => $labels,
// 		'description'         => '',
// 		'public'              => true,
// 		'publicly_queryable'  => true,
// 		'show_ui'             => true,
// 		'rest_base'           => '',
// 		'has_archive'         => false,
// 		'menu_icon'           => 'dashicons-portfolio',
// 		'show_in_menu'        => true,
// 		'show_in_nav_menus'   => true,
// 		'show_in_rest'        => true, // enable Gutenberg for this CPT
// 		'exclude_from_search' => false,
// 		'capability_type'     => 'post',
// 		'map_meta_cap'        => true,
// 		'hierarchical'        => false,
// 		'rewrite'             => array( 'slug' => 'project', 'with_front' => true ),
// 		'query_var'           => true,
// 		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
// 		// 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
// 	);

// 	register_post_type( 'project', $args );

// 	// taxonomy: Project Category
// 	register_taxonomy( 'project-category',
// 		array( 'project' ), /* name of CPT */
// 		array(
// 			'labels'            => array(
// 				'name'          => __( 'Project Categories', 'terra' ),
// 				'singular_name' => __( 'Project Category', 'terra' ),
// 				'add_new_item'  => __( 'Add New Category', 'terra' ),
// 			),
// 			'hierarchical'      => true,     /* if this is true, it acts like categories */
// 			'show_admin_column' => true,
// 			'show_ui'           => true,
// 			'query_var'         => true,
// 			'rewrite'           => array( 'slug' => 'project-category' )
// 		)
// 	);

// }

// add_action( 'init', 'it_custom_init' );
