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
class Tru_Fetcher_Api_User_Controller {

	const STATUS_SUCCESS = "success";
	private $namespace = "wp/v2/public/users";
	private $apiUserResponse;

	public function __construct() {
	}

	public function init() {
		$this->load_dependencies();
		$this->loadResponseObjects();
		add_action( 'rest_api_init', [ $this, "register_routes" ] );
	}

	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'response/ApiUserResponse.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '../database/class-tru-fetcher-database.php';
	}

	private function loadResponseObjects() {
		$this->apiUserResponse = new Tru_Fetcher_Api_User_Response();
	}

	public function register_routes() {
		register_rest_route( $this->namespace, '/create', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => [ $this, "createUser" ],
			'permission_callback' => '__return_true'
		) );
		register_rest_route( $this->namespace, '/update', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => [ $this, "updateUser" ],
			'permission_callback' => '__return_true'
		) );
		register_rest_route( $this->namespace, '/save-item', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => [ $this, "saveItem" ],
			'permission_callback' => '__return_true'
		) );
		register_rest_route( $this->namespace, '/saved-items-list', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => [ $this, "getSavedItemList" ],
			'permission_callback' => '__return_true'
		) );
	}

	public function createUser( $request ) {
		$username = $request["username"];
		$email    = $request["email"];
		$password = $request["password"];

		$createUser = wp_create_user( $username, $password, $email );
		if ( is_wp_error( $createUser ) ) {
			return $this->showError( $createUser->get_error_code(), $createUser->get_error_message() );
		}
		wp_new_user_notification( $createUser );

		$getUserData = [
			"username" => $username,
			"email"    => $email
		];

		return $this->sendResponse(
			$this->buildResponseObject( self::STATUS_SUCCESS,
				sprintf( "Confirmation email has been sent to (%s). Click on the confirmation link in the email to complete registration.", $email ),
				$getUserData )
		);
	}

	public function updateUser( $request ) {
		$userData                  = [];
		$userData["ID"]            = $request["ID"];
		$userData["user_nicename"] = $request["nicename"];
		$userData["nickname"]      = $request["nickname"];
		$userData["display_name"]  = $request["display_name"];
		$userData["user_email"]    = $request["email"];
		$userData["first_name"]    = $request["first_name"];
		$userData["last_name"]     = $request["last_name"];

		$authenticateUser = wp_authenticate( $userData["user_email"], $request["current_password"] );
		if ( is_wp_error( $authenticateUser ) ) {
			return $this->showError( $authenticateUser->get_error_code(), $authenticateUser->get_error_message() );
		}

		if ( isset( $request["change_password"] ) && $request["change_password"] ) {
			if ( $request["confirm_password"] === $request["new_password"] ) {
				$userData["user_pass"] = $request["new_password"];
			}
		}

		$updateUser = wp_update_user( $userData );
		if ( is_wp_error( $updateUser ) ) {
			return $this->showError( $authenticateUser->get_error_code(), $authenticateUser->get_error_message() );
		}

		return $this->sendResponse(
			$this->buildResponseObject( self::STATUS_SUCCESS,
				sprintf( "User (%s) updated.", $userData["user_nicename"] ),
				$userData )
		);
	}

	public function saveItem( $request ) {
		$date                  = new DateTime();
		$data                  = [];
		$data["provider_name"] = $request["provider_name"];
		$data["user_id"]       = $request["user_id"];
		$data["category"]      = $request["category"];
		$data["item_id"]       = $request["item_id"];
		$data["date_created"]  = $date->format( "Y-m-d H:i:s" );

		$dbClass = new Tru_Fetcher_Database();
		$where = "provider_name=%s AND category=%s AND item_id=%s";
		$getItem = $dbClass->getRow(
			Tru_Fetcher_Database::SAVED_ITEMS_TABLE_NAME,
			$where,
			$data["provider_name"], $data["category"], $data["item_id"]
		);
		if ($getItem === null) {
			$dbClass->insertData( Tru_Fetcher_Database::SAVED_ITEMS_TABLE_NAME, $data );
		} else {
			$dbClass->delete(Tru_Fetcher_Database::SAVED_ITEMS_TABLE_NAME, "item_id=%s", [$data["item_id"]]);
		}

		return $this->sendResponse(
			$this->buildResponseObject( self::STATUS_SUCCESS,
				"",
				true )
		);
	}

	private function getStringCount($array, $string) {
		$str = "";
		foreach ($array as $value) {
			$str .= sprintf("'%s',", $string);
		}
		return rtrim($str, ',');
	}

	public function getSavedItemList($request) {
		$data                  = [];
		$data["provider_name"] = $request["provider_name"];
		$data["category"]      = $request["category"];
		$data["id_list"]       = $request["id_list"];

		$dbClass = new Tru_Fetcher_Database();
		$placeholders = "(" . $this->getStringCount($request["id_list"], "%s") . ")";
		$where = "provider_name=%s AND category=%s AND item_id IN $placeholders";
		$getItem = $dbClass->getResults(
			Tru_Fetcher_Database::SAVED_ITEMS_TABLE_NAME,
			$where,
			$data["provider_name"], $data["category"], ...$data["id_list"]
		);
		return $this->sendResponse(
			$this->buildResponseObject( self::STATUS_SUCCESS,
				"",
				$getItem )
		);
	}

	private function buildResponseObject( $status, $message, $data ) {
		$this->apiUserResponse->setStatus( $status );
		$this->apiUserResponse->setMessage( $message );
		$this->apiUserResponse->setData( $data );

		return $this->apiUserResponse;
	}

	private function sendResponse( Tru_Fetcher_Api_User_Response $api_user_response ) {
		return rest_ensure_response( $api_user_response );
	}

	private function showError( $code, $message ) {
		return new WP_Error( $code,
			esc_html__( $message, 'my-text-domain' ),
			array( 'status' => 404 ) );
	}
}
