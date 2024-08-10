<?php
namespace TrfRecruit\Includes\Api\Controllers\Protected;

use TrfRecruit\Includes\DB\Model\Trf_Recruit_DB_Model_Skill;
use TrfRecruit\Includes\DB\Repository\Trf_Recruit_DB_Repository_Skill;
use TruFetcher\Includes\Api\Controllers\App\Tru_Fetcher_Api_Controller_Base;
use TruFetcher\Includes\Api\Response\Tru_Fetcher_Api_Response;
use TruFetcher\Includes\Forms\Tru_Fetcher_Api_Form_Handler;

class  Trf_Recruit_Api_Skills_Controller extends Tru_Fetcher_Api_Controller_Base
{

    private Trf_Recruit_DB_Model_Skill $skillModel;
    private Trf_Recruit_DB_Repository_Skill $skillsRepository;
    private Tru_Fetcher_Api_Response $apiResponse;
    private Tru_Fetcher_Api_Form_Handler $apiFormHandler;

    public function __construct()
    {
        parent::__construct();
        $this->apiConfigEndpoints->endpointsInit('/skills');
        $this->skillModel = new Trf_Recruit_DB_Model_Skill();
        $this->apiResponse = new Tru_Fetcher_Api_Response();
        $this->apiFormHandler = new Tru_Fetcher_Api_Form_Handler();
    }
    public function init()
    {
        $this->skillsRepository = new Trf_Recruit_DB_Repository_Skill();
        add_action('rest_api_init', [$this, "register_routes"]);
    }
    public function register_routes()
    {
        register_rest_route($this->apiConfigEndpoints->protectedEndpoint, '/list', array(
            'methods' => \WP_REST_Server::READABLE,
            'callback' => [$this, "skillsSelectData"],
            'permission_callback' => [$this->apiAuthApp, 'protectedTokenRequestHandler']
        ));
    }

    public function skillsSelectData($request)
    {
        $getSkills = $this->skillsRepository->findSkills();
        $buildSelectData = $this->apiFormHandler->buildSelectList($this->skillModel->getAlias(), 'id', "name", "label", $getSkills);
        $this->apiResponse->setData($buildSelectData);
        return $this->controllerHelpers->sendSuccessResponse(
            "Skills list successfully retrieved",
            $this->apiResponse,
        );
    }
}
