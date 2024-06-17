<?php
namespace projects\elastishop\routeMap;

class ProjectSurveyRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'survey_fillSurvey',
                'paramChains' => array(
                    'kerdoiv-kitoltese/{surveySlug}' => 'hu',
                    'fill-survey/{surveySlug}' => 'en',
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyController',
                'action' => 'fillSurveyAction',
                'permission' => 'viewGuestContent',
                // 'title' => 'survey.answering',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'skinName' => 'ElastiShop',
                'widgetChanges' => array(
                    'mainContent' => 'FinancePackage/view/widget/FillSurveyWidget',
                    // 'mainContent2' => 'SiteBuilderPackage/view/widget/SplashWidget'
                    // 'left1' => 'projects/ElastiShop/view/widget/HomepageSideWidget'
                )
            ),
            array(
                'name' => 'survey_FillSurveyWidget',
                'paramChains' => array(
                    'survey/FillSurveyWidget' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyWidgetController',
                'action' => 'fillSurveyWidgetAction',
                'permission' => 'viewGuestContent'
            )
        );
    }
}
