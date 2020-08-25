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
		add_action( 'rest_api_init', [$this, "register_routes"] );
	}

	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'response/ApiUserResponse.php';
	}

	private function loadResponseObjects() {
		$this->apiUserResponse = new Tru_Fetcher_Api_User_Response();
	}

	public function register_routes() {
		register_rest_route( $this->namespace, '/create', array(
			'methods'  => WP_REST_Server::CREATABLE,
			'callback' => [ $this, "createUser" ],
			'permission_callback' => '__return_true'
		) );
		register_rest_route( $this->namespace, '/update', array(
			'methods'  => WP_REST_Server::CREATABLE,
			'callback' => [ $this, "updateUser" ],
			'permission_callback' => '__return_true'
		) );
	}

	public function createUser($request) {
		$username = $request["username"];
		$email = $request["email"];
		$password = $request["password"];

		$createUser = wp_create_user($username, $password, $email);
		if (is_wp_error($createUser)) {
			return $this->showError($createUser->get_error_code(), $createUser->get_error_message());
		}
		wp_new_user_notification($createUser);

		$getUserData = [
			"username" => $username,
			"email" => $email
		];
		return $this->sendResponse(
			$this->buildResponseObject(self::STATUS_SUCCESS,
				sprintf("Confirmation email has been sent to (%s). Click on the confirmation link in the email to complete registration.", $email),
				$getUserData)
		);
	}

	public function updateUser($request) {
		$username = $request["username"];
		$email = $request["email"];
		$password = $request["password"];

		$createUser = wp_create_user($username, $password, $email);
		if (is_wp_error($createUser)) {
			return $this->showError($createUser->get_error_code(), $createUser->get_error_message());
		}
		wp_new_user_notification($createUser);

		$getUserData = [
			"username" => $username,
			"email" => $email
		];
		return $this->sendResponse(
			$this->buildResponseObject(self::STATUS_SUCCESS,
				sprintf("Confirmation email has been sent to (%s). Click on the confirmation link in the email to complete registration.", $email),
				$getUserData)
		);
	}

	private function buildResponseObject($status, $message, $data) {
		$this->apiUserResponse->setStatus($status);
		$this->apiUserResponse->setMessage($message);
		$this->apiUserResponse->setData($data);
		return $this->apiUserResponse;
	}

	private function sendResponse(Tru_Fetcher_Api_User_Response $api_user_response) {
		return rest_ensure_response( $api_user_response );
	}

	private function showError( $code, $message ) {
		return new WP_Error( $code,
			esc_html__( $message, 'my-text-domain' ),
			array( 'status' => 404 ) );
	}
}
