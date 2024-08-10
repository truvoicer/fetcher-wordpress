<?php
namespace TrfRecruit\Includes;

use TrfRecruit\Includes\Api\Controllers\Protected\Trf_Recruit_Api_Skills_Controller;
use TrfRecruit\Includes\DB\Model\Trf_Recruit_DB_Model_Skill;
use TrfRecruit\Includes\DB\Model\Trf_Recruit_DB_Model_User_Skill;
use TrfRecruit\Includes\Forms\Progress\Trf_Recruit_Progress_Field_Groups;
use TrfRecruit\Includes\Helpers\Trf_Recruit_Api_Helpers_Skill;
use TruFetcher\Includes\Forms\ProgressGroups\Tru_Fetcher_Progress_Field_Groups;
use TruFetcher\Includes\Forms\Tru_Fetcher_Api_Form_Handler;
use TruFetcher\Includes\Forms\Tru_Fetcher_Forms_Helpers;
use TruFetcher\Includes\Traits\Tru_Fetcher_Traits_Errors;
use TruFetcher\Includes\Tru_Fetcher_Filters;

class Trf_Recruit
{
    use Tru_Fetcher_Traits_Errors;
    private Trf_Recruit_Api_Helpers_Skill $skillHelpers;
    private Tru_Fetcher_Api_Form_Handler $apiFormHandler;

    public function __construct()
    {
        $this->skillHelpers = new Trf_Recruit_Api_Helpers_Skill();
        $this->apiFormHandler = new Tru_Fetcher_Api_Form_Handler();
    }

    public function run() {

        add_filter(Tru_Fetcher_Filters::TRU_FETCHER_FILTER_DB_MODELS, [$this, 'addDbModels']);
        add_filter(Tru_Fetcher_Filters::TRU_FETCHER_FILTER_DB_DATA, [$this, 'addDbData']);
        add_filter(Tru_Fetcher_Filters::TRU_FETCHER_FILTER_API_PUBLIC_CONTROLLERS, [$this, 'publicControllers']);
        add_filter(Tru_Fetcher_Filters::TRU_FETCHER_FILTER_API_ADMIN_CONTROLLERS, [$this, 'adminControllers']);
        add_filter(Tru_Fetcher_Filters::TRU_FETCHER_FILTER_USER_PROFILE_SAVE, [$this, 'userProfileSaveHandler'], 10, 2);
        add_filter(Tru_Fetcher_Filters::TRU_FETCHER_FILTER_USER_PROFILE_FETCH, [$this, 'getUserProfileData'], 10, 2);
        add_filter(Tru_Fetcher_Filters::TRU_FETCHER_FILTER_ALLOWED_USER_PROFILE_FIELDS, [$this, 'getAllowedUserProfileFields']);
        add_filter(Tru_Fetcher_Filters::TRU_FETCHER_FILTER_DATA_SOURCE_DATA, [$this, "getDataSourceData"], 10, 2);
        add_filter(Tru_Fetcher_Filters::TRU_FETCHER_FILTER_USER_META_SELECT_DATA_SOURCE, [$this, "filterUserMetaSelectData"], 10, 2);
        add_filter(Tru_Fetcher_Filters::TRU_FETCHER_FILTER_FORM_PROGRESS_FIELD_GROUPS, [$this, "getFormFieldGroupsArray"]);
    }

    public function getUserProfileData(\WP_User $user, array $userMetaData) {
        $userSkills = $this->skillHelpers->getUserSkillRepository()->findUserSkillsByUser($user);
        return [
            ...$userMetaData,
            "skills" => $userSkills
        ];
    }
    public function getFormFieldGroupsArray() {
        return [
            Trf_Recruit_Progress_Field_Groups::class
        ];
    }
    public function getDataSourceData($field, \WP_User $user) {
        switch ($field["name"]) {
            case "skills":
                return $this->skillHelpers->getUserSkillRepository()->findUserSkillsByUser($user);
        }
        return [];
    }

    public function filterUserMetaSelectData($field, \WP_User $user) {
        switch ($field["name"]) {
            case "skills":
            case "skill":
                return $this->apiFormHandler->buildSelectList(
                    $this->skillHelpers->getSkillModel()->getAlias(),
                    'id',
                    "name",
                    "label",
                    $this->skillHelpers->getUserSkillRepository()->findUserSkillsByUser($user)
                );
        }
        return [];
    }

    public function getAllowedUserProfileFields() {
        return [
            "skills"
        ];
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
    public function userProfileSaveHandler(\WP_User $user, array $data) {
        if (empty($data["skills"] || is_array($data["skills"]))) {
            return true;
        }
        $skillModel = new Trf_Recruit_DB_Model_Skill();
        $this->skillHelpers->syncUserSkills(
            $user,
            array_map(function($skill) use ($skillModel) {
                if (!empty($skill["value"])) {
                    $skill[$skillModel->getNameColumn()] = sanitize_text_field($skill["value"]);
                }
                return $skill;
            }, $data["skills"])
        );

        if ($this->skillHelpers->hasErrors()) {
            return $this->skillHelpers->getErrors();
        }
        return true;
    }
}
