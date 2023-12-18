<?php
namespace framework\packages\SiteBuilderPackage\form;

use App;
use framework\component\helper\RouteMapHelper;
use framework\component\parent\CustomFormValidator;
use framework\packages\SiteBuilderPackage\service\BuiltPageService;

/**
 * @var bool ruleValue: Desired return
*/
class EditBuiltPageCustomValidator extends CustomFormValidator
{
    public function slugizedRouteName($value, bool $ruleValue, $form)
    {
        $formatOk = preg_match("/^[a-z0-9_-]+$/i", $value);

        if (!$formatOk) {
            return [
                'result' => false,
                'message' => trans('must.be.slugized')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function uniqueRouteName($value, bool $ruleValue, $form)
    {
        $this->getContainer()->wireService('SiteBuilderPackage/service/BuiltPageService');
        $uniqueRouteName = BuiltPageService::uniqueRouteName($value);

        if (!$uniqueRouteName) {
            return [
                'result' => false,
                'message' => trans('name.must.be.unique')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function editableRouteName($value, bool $ruleValue, $form)
    {
        // $allMappedRouteNames = array_keys(App::getContainer()->fullRouteMap);
        $this->getContainer()->wireService('SiteBuilderPackage/service/BuiltPageService');
        $allMappedRouteNamesAndParamChains = RouteMapHelper::getAllMappedRouteNamesAndParamChains();
        $forbiddenRoutes = BuiltPageService::getForbiddenRoutes();

        if (!in_array($value, array_merge($allMappedRouteNamesAndParamChains, $forbiddenRoutes))) {
            return [
                'result' => true,
                'message' => null
            ];
        }

        // dump($form->getEntityCollector()->getCollection());
        $entity = null;
        if ($form->getEntity()) {
            $entity = $form->getEntity();
        }
        if (!$entity) {
            $collection = $form->getEntityCollector()->getCollection();
            foreach ($collection as $collectionElement) {
                if ($collectionElement['collectionKey'] == '0-0') {
                    $entity = $collectionElement['entity'];
                }
            }
        }
        if (!$entity) {
            throw new \Exception('Missing form');
        }

        $entity->setRouteName($value);
        // dump($entity);
        App::getContainer()->wireService('SiteBuilderPackage/service/BuiltPageService');
        $editable = BuiltPageService::checkIfEditable($entity);
        // dump($editable);exit;
        if (!$editable) {
            return [
                'result' => false,
                'message' => trans('string.not.allowed')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }
}