<?php
function register_listing_post_type()
{
	$labels = array(
		'name'                  => _x( 'Listings', 'Listings', 'text_domain' ),
		'singular_name'         => _x( 'Listing', 'Listing', 'text_domain' ),
		'menu_name'             => __( 'Listings', 'text_domain' ),
		'name_admin_bar'        => __( 'Post Type', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Listing', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
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
	register_post_type( 'listing_post_type', $args );
}
add_action( 'init', "register_listing_post_type" );