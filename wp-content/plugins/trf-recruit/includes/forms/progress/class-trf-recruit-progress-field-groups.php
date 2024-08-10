<?php

namespace TrfRecruit\Includes\Forms\Progress;

use TruFetcher\Includes\Forms\ProgressGroups\Tru_Fetcher_Progress_Field_Groups;

class Trf_Recruit_Progress_Field_Groups extends Tru_Fetcher_Progress_Field_Groups
{
    public array $experiences;
    public array $education;
    public array $skills;
    public array $cv;

    /**
     * Tfr_Profile_Field_Group constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setCv();
        $this->setEducation();
        $this->setExperiences();
        $this->setSkills();
    }

    /**
     */
    public function setExperiences(): void
    {
        $this->experiences = [
            [
                "name" => "form_experiences",
                "label" => "Work Experiences",
                "incomplete_text" => "Add some of your previous work experiences to your profile."
            ],
        ];
    }

    /**
     */
    public function setEducation(): void
    {
        $this->education = [
            [
                "name" => "form_education",
                "label" => "Education",
                "incomplete_text" => "Add some of your education history to your profile."
            ],
        ];
    }

    /**
     */
    public function setSkills(): void
    {
        $this->skills = [
            [
                "type" => "data_source",
                "name" => "skills",
                "label" => "Skills",
                "incomplete_text" => "Add some skills to your profile."
            ]
        ];
    }

    /**
     */
    public function setCv(): void
    {
        $this->cv = [
            [
                "type" => "file",
                "name" => "cv_file",
                "label" => "CV/Resume",
                "incomplete_text" => "Add your cv/resume to your profile."
            ],
        ];
    }


}
