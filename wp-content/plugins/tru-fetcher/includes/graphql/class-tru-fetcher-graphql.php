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

	private $listingsClass;

	public function __construct() {
		$this->loadDependencies();
		$this->listingsClass = new Tru_Fetcher_Listings();
	}

	private function loadDependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'listings/class-tru-fetcher-listings.php';
	}

	public function init() {
		add_filter( 'graphql_resolve_field', function( $result, $source, $args, $context, $info, $type_name, $field_key, $field, $field_resolver ) {
			if ( $field_key === 'blocksJSON' ) {
				if (is_array($result)) {
					return json_encode($result);
				}
				$blocksObject = json_decode($result);
				$blocksJson = $this->listingsClass->buildListingsBlock($blocksObject, true);
				return $blocksJson;
			}
			return $result;
		}, 10, 9 );
	}

	public function registerTypes() {

	}

	public function registerSidebarField() {

	}
}
