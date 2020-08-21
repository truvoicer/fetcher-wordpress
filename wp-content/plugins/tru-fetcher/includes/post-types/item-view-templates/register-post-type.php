<?php
function TruFetcherItemViewTemplate()
{
	$labels = array(
		'name'                  => _x( 'Item View Templates', 'Listings Templates', 'text_domain' ),
		'singular_name'         => _x( 'Item View Template', 'Listings Template', 'text_domain' ),
		'menu_name'             => __( 'Item View Templates', 'text_domain' ),
		'name_admin_bar'        => __( 'Item View Templates', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Item View Templates', 'text_domain' ),
		'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats' ),
        'taxonomies'            => array( 'listings_categories' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => false,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
        'show_in_rest'          => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'item_view_templates', $args );
}
add_action( 'init', "TruFetcherItemViewTemplate" );