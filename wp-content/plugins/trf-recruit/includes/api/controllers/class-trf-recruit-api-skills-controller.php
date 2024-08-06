<?php
namespace TrfRecruit\Includes\Api\Controllers;

use TrfRecruit\Includes\DB\Model\Trf_Recruit_DB_Model_Skill;
use TrfRecruit\Includes\DB\Repository\Trf_Recruit_DB_Repository_Skill;
use \TruFetcher\Includes\Api\Controllers\App\Tru_Fetcher_Api_Controller_Base;

class Trf_Recruit_Api_Skills_Controller extends Tru_Fetcher_Api_Controller_Base
{

    private Trf_Recruit_DB_Model_Skill $skillModel;
    private Trf_Recruit_DB_Repository_Skill $skillsRepository;
    public function __construct()
    {
        parent::__construct();
        $this->apiConfigEndpoints->endpointsInit('/skills');
        $this->skillModel = new Trf_Recruit_DB_Model_Skill();
    }
    public function init()
    {
        $this->skillsRepository = new Trf_Recruit_DB_Repository_Skill();
    }
    public function register_routes()
    {
        register_rest_route($this->apiConfigEndpoints->publicEndpoint, '/skills', array(
            'methods' => \WP_REST_Server::READABLE,
            'callback' => [$this, "skillsSelectData"],
            'permission_callback' => [$this->apiAuthApp, 'allowRequest']
        ));
    }

    public function skillsSelectData($request)
    {
        $getSkills = $this->skillsRepository->findSkills();
        $buildSelectData = $this->apiFormHandler->buildSelectList($this->skillModel->getAlias(), 'id', "name", "label", $getSkills);
        $this->apiGeneralResponse->setData($buildSelectData);
        return $this->controllerHelpers->sendSuccessResponse(
            "Skills list successfully retrieved",
            $this->apiGeneralResponse,
        );
    }
}
