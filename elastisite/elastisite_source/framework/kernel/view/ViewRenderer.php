<?php
namespace framework\kernel\view;

use App;
use framework\component\exception\ElastiException;
use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\packages\UXPackage\service\ViewTools;

class ViewRenderer extends Kernel
{
    public static function renderWidget($widgetName, $viewFilePath, $viewData, $debug = false)
    {
        if (isset($viewData['skinName'])) {
            App::getContainer()->setSkinData('skinName', $viewData['skinName']);
        }
        if (isset($viewData['backgroundColor'])) {
            App::getContainer()->setSkinData('backgroundColor', $viewData['backgroundColor']);
        }
        $containerFound = false;
        $viewToolsFound = false;
        foreach ($viewData as $viewDataKey => $viewDataValue) {
            if ($viewDataKey == 'container') {
                $containerFound = true;
            }
            if ($viewDataKey == 'viewTools') {
                $viewToolsFound = true;
            }
        }
        if (!$containerFound) {
            // $viewData['container'] = $this->getContainer();
        }
        if (!$viewToolsFound) {
            $viewData['viewTools'] = new ViewTools();
        }

        $widgetFileName = BasicUtils::explodeAndGetElement($viewFilePath, '/', 'last');
        $viewSubdirName = BasicUtils::explodeAndGetElement(BasicUtils::explodeAndRemoveElement($viewFilePath, '/', 'last'), '/', 'last');

        # In some situations, widgetController use a widget from a different library, e.g.: grid is defined in 
        # ToolPackage, but can be used by any controllers
        if ($viewSubdirName == $widgetName) {
            $widgetParams = App::getContainer()->getWidgetParams($widgetName);
            if (!$widgetParams) {
                # 'This widget is not on widgetMap: [widgetName]'
                throw new ElastiException(
                    App::getContainer()->wrapExceptionParams(array(
                        'widgetName' => $widgetName
                    )), 1601
                );
            }
            $viewFilePath = App::getContainer()->getWidgetParams($widgetName)['widgetPath'].'/'.$widgetFileName;
        } else {
            $viewFilePath = App::getContainer()->getServiceLinkParams($viewFilePath)['pathToFile'];
            // dump($viewFilePath);
        }

        // dump($this->getContainer()->getWidgetParams($viewSubdirName));
        // dump('/'.$widgetFileName);exit;

        $fileParams = App::getContainer()->getServiceLinkParams($viewFilePath);
        // dump($viewSubdirName.'-'.$widgetName);
        // dump($fileParams['pathToFile']);exit;
        $widget = App::renderView($fileParams['pathToFile'], $viewData);
        $csrfTokenInput = '';
        if (!App::getContainer()->getRequest()->get($widgetName.'_csrfToken')) {
            $csrfToken = App::getContainer()->getSession()->createCsrfToken($widgetName);
            App::getContainer()->getSession()->set($widgetName.'_csrfToken', $csrfToken);
        } else {
            $csrfToken = App::getContainer()->getSession()->get($widgetName.'_csrfToken');
        }
        $csrfTokenInput = '<input type="hidden" id="'.$widgetName.'_csrfToken" name="'.$widgetName.'_csrfToken" value="'.$csrfToken.'" />';
        $widget = \str_replace('{{ csrfTokenInput }}', $csrfTokenInput, $widget);

        return $widget;
    }
}