<?php
namespace framework\packages\SurveyPackage\routeMap;

class SurveyRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'survey_answerForm',
                'paramChains' => array(
                    'survey/answerForm' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyWidgetController',
                'action' => 'surveyAnswerFormAction',
                'permission' => 'viewProjectAdminContent'
            ),
            /**
             * Survey admin
            */
            array(
                'name' => 'admin_survey_surveys',
                'paramChains' => array(
                    'admin/survey/surveys' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyController',
                'action' => 'basicAdminAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'surveys',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'FinancePackage/view/widget/AdminSurveysWidget'
                )
            ),   
            array(
                'name' => 'admin_survey_surveysWidget',
                'paramChains' => array(
                    'admin/SurveysWidget' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyWidgetController',
                'action' => 'adminSurveysWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_survey_surveysList',
                'paramChains' => array(
                    'admin/SurveysList' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyWidgetController',
                'action' => 'adminSurveysListAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_survey_newSurvey',
                'paramChains' => array(
                    'admin/survey/newSurvey' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyWidgetController',
                'action' => 'adminFinanceNewSurveyAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_survey_editSurvey',
                'paramChains' => array(
                    'admin/survey/editSurvey' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyWidgetController',
                'action' => 'adminEditSurveyAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_survey_getAnswersView',
                'paramChains' => array(
                    'admin/survey/getAnswersView' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyWidgetController',
                'action' => 'adminSurveyGetAnswersViewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_survey_editSurveyFlexibleContent',
                'paramChains' => array(
                    'admin/survey/editSurveyFlexibleContent' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyWidgetController',
                'action' => 'adminEditSurveyFlexibleContentAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_survey_deleteSurvey',
                'paramChains' => array(
                    'admin/survey/deleteSurvey' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyWidgetController',
                'action' => 'adminDeleteSurveyAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_survey_getQuestionList',
                'paramChains' => array(
                    'admin/survey/getQuestionList' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyWidgetController',
                'action' => 'adminGetQuestionListAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_survey_addQuestion',
                'paramChains' => array(
                    'admin/survey/addQuestion' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyWidgetController',
                'action' => 'adminAddQuestionAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_survey_saveQuestion',
                'paramChains' => array(
                    'admin/survey/saveQuestion' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyWidgetController',
                'action' => 'adminSaveQuestionAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_survey_removeQuestion',
                'paramChains' => array(
                    'admin/survey/removeQuestion' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyWidgetController',
                'action' => 'adminRemoveQuestionAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_survey_addOption',
                'paramChains' => array(
                    'admin/survey/addOption' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyWidgetController',
                'action' => 'adminAddOptionAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_survey_removeOption',
                'paramChains' => array(
                    'admin/survey/removeOption' => 'default'
                ),
                'controller' => 'framework/packages/SurveyPackage/controller/SurveyWidgetController',
                'action' => 'adminRemoveOptionAction',
                'permission' => 'viewProjectAdminContent'
            ),
        );
    }
}
