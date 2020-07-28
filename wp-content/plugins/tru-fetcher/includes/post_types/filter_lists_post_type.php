<?php
function register_filter_lists_post_type()
{
	$labels = array(
		'name'                  => _x( 'Filter Lists', 'Filter Lists', 'text_domain' ),
		'singular_name'         => _x( 'Filter List', 'Filter List', 'text_domain' ),
		'menu_name'             => __( 'Filter Lists', 'text_domain' ),
		'name_admin_bar'        => __( 'Filter Lists', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Filter Lists', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'taxonomies'            => array( ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'lists_post_type', $args );
}
add_action( 'init', "register_filter_lists_post_type" );