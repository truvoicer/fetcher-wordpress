<?php

/**
 * Fired during plugin activation
 *
 * @link       https://truvoicer.co.uk
 * @since      1.0.0
 *
 * @package    Tru_Fetcher
 * @subpackage Tru_Fetcher/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Tru_Fetcher
 * @subpackage Tru_Fetcher/includes
 * @author     Michael <michael@local.com>
 */
class Tru_Fetcher_GraphQl {

	public function __construct() {
	}

	public function init() {
		add_action( 'graphql_register_types', [$this, "registerTypes"] );
		add_action( 'graphql_register_types', [$this, "registerSidebarField"] );
	}

	public function registerTypes() {
		register_graphql_object_type( 'Widgets', [
			'description' => __( "Site sidebar widget", 'your-textdomain' ),
			'fields' => [
				'name' => [
					'type' => "String",
					'description' => __( 'Sidebar widgets', 'your-textdomain' ),
				],
			],
		] );
		register_graphql_object_type( 'Sidebar', [
			'description' => __( "Site sidebar", 'your-textdomain' ),
			'fields' => [
				'name' => [
					'type' => "String",
					'description' => __( 'Sidebar name', 'your-textdomain' ),
				],
				'widgets' => [
					'type'        => [
						'list_of' => 'String',
					],
					'description' => __( 'Sidebar widgets', 'your-textdomain' ),
				],
			],
		] );
	}

	public function registerSidebarField() {
		register_graphql_field(
			'RootQuery',
			'Sidebar',
			[
				'type'        => 'Sidebar',
				'description' => 'a sidebar',
				'args'        => [
					'slug' => [
						'type' => [
							'non_null' => 'String',
						],
					],
				],
				'resolve' => function ( $source, array $args, $context, $info )  {

					foreach (wp_get_sidebars_widgets() as $key => $sidebar) {
						if ($key == $args['slug']) {
							return [
								"name" => $key,
								'widgets' => $sidebar,
							];
						}
					}
				},
			]
		);
	}
}
