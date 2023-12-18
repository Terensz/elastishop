<?php
namespace framework\packages\SurveyPackage\menu;

class AdminMenuSection
{
    public function getConfig()
    {
        return [
            'title' => 'survey.administration',
            'items' => [
                [
                    'routeName' => 'admin_survey_surveys',
                    'paramChain' => 'admin/survey/surveys',
                    'title' => 'surveys'
                ]
            ]
        ];
    }
}