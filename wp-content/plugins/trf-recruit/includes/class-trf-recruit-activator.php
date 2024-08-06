<?php
namespace TrfRecruit\Includes;

use \TruFetcher\Includes\Database\Tru_Fetcher_Database;

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
class Trf_Recruit_Activator {

	private $dbClass;

	public function __construct() {
		require_once TRU_FETCHER_PLUGIN_DIR . 'includes/database/class-tru-fetcher-database.php';
		$this->dbClass = new Tru_Fetcher_Database();
	}

	public function activate() {
//		$this->dbClass->createSavedItemsTable();
//		$this->dbClass->createRatingsTable();
//		$this->dbClass->updateVersion();
	}
}
