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
class Tru_Fetcher_Database {

	const DB_VERSION = "1.0";
	const SAVED_ITEMS_TABLE_NAME = 'tru_fetcher_saved_items';

	private $tablePrefix;
	private $charsetCollate;

	public function __construct() {
		global $wpdb;
		$this->tablePrefix = $wpdb->prefix;
		$this->charsetCollate = $wpdb->get_charset_collate();
	}

	public function createTable($sql) {
		require_once( get_home_path() . '/wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	public function createSavedItemsTable() {
		$tableName = $this->tablePrefix . self::SAVED_ITEMS_TABLE_NAME;
		$sql = "CREATE TABLE $tableName (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  user_id bigint(20) unsigned NOT NULL DEFAULT 0,
		  provider_name varchar(255) NOT NULL,
		  category varchar(255) NOT NULL,
		  item_id varchar(255) NOT NULL,
		  date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  PRIMARY KEY  (id)
		) $this->charsetCollate;";

		$this->createTable($sql);
	}

	public function getRow($tableName, $where, ...$parameters) {
		global $wpdb;
		$query = "SELECT * FROM $this->tablePrefix$tableName WHERE $where";
		return $wpdb->get_row($wpdb->prepare($query, $parameters));
	}

	public function getResults($tableName, $where, ...$parameters) {
		global $wpdb;
		$query = "SELECT * FROM $this->tablePrefix$tableName WHERE $where";
		return $wpdb->get_results($wpdb->prepare($query, $parameters));
	}

	public function insertData($tableName, $data, $format = null) {
		global $wpdb;
		return $wpdb->insert( $this->tablePrefix . $tableName, $data, $format);
	}

	public function updateData($tableName, $data, $where, $format = null, $where_format = null) {
		global $wpdb;
		return $wpdb->update( $this->tablePrefix . $tableName, $data, $where, $format = null, $where_format = null );
	}

	public function delete($tableName, $where, $parameters) {
		global $wpdb;
		return $wpdb->query(
			$wpdb->prepare("DELETE FROM $this->tablePrefix$tableName WHERE $where ", $parameters)
		);
	}

	public function updateVersion() {
		add_option( 'tru_fetcher_db_version', self::DB_VERSION );
	}
}
