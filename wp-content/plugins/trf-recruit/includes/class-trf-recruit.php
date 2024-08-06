<?php
namespace TrfRecruit\Includes;

use TrfRecruit\Includes\Api\Controllers\Trf_Recruit_Api_Skills_Controller;
use TrfRecruit\Includes\DB\Model\Trf_Recruit_DB_Model_Skill;
use TrfRecruit\Includes\DB\Model\Trf_Recruit_DB_Model_User_Skill;
use TrfRecruit\Includes\Helpers\Trf_Recruit_Api_Helpers_Skill;
use TruFetcher\Includes\Traits\Tru_Fetcher_Traits_Errors;
use \TruFetcher\Includes\Tru_Fetcher_Filters;

class Trf_Recruit
{
    use Tru_Fetcher_Traits_Errors;
    private Trf_Recruit_Api_Helpers_Skill $skillHelpers;

    public function __construct()
    {
        $this->skillHelpers = new Trf_Recruit_Api_Helpers_Skill();
    }

    public function run() {

        add_filter(Tru_Fetcher_Filters::TRU_FETCHER_FILTER_DB_MODELS, [$this, 'addDbModels']);
        add_filter(Tru_Fetcher_Filters::TRU_FETCHER_FILTER_DB_DATA, [$this, 'addDbData']);
        add_filter(Tru_Fetcher_Filters::TRU_FETCHER_FILTER_API_PUBLIC_CONTROLLERS, [$this, 'publicControllers']);
        add_filter(Tru_Fetcher_Filters::TRU_FETCHER_FILTER_API_ADMIN_CONTROLLERS, [$this, 'adminControllers']);
        add_filter(Tru_Fetcher_Filters::TRU_FETCHER_FILTER_USER_PROFILE_SAVE, [$this, 'adminControllers']);
    }

    public function addDbModels() {
        return [
            new Trf_Recruit_DB_Model_Skill(),
            new Trf_Recruit_DB_Model_User_Skill()
        ];
    }
    public function addDbData() {
        return [];
    }
    public function publicControllers() {
        return [
            Trf_Recruit_Api_Skills_Controller::class
        ];
    }
    public function adminControllers() {
        return [];
    }
    public function userProfileSaveHandler(\WP_User $user, $data) {
        if (empty($data["skills"] && is_array($data["skills"]))) {
            $this->skillHelpers->syncUserSkills($user, $data["skills"]);
        }

        if ($this->skillHelpers->hasErrors()) {
            return $this->skillHelpers->getErrors();
        }
        return true;
    }
}
