<?php
namespace framework\kernel\eventHandling;

use App;
use framework\component\exception\ElastiException;
use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\kernel\request\entity\Referer;
use framework\kernel\request\entity\Visit;

class OnPageLoadEventHandler extends Kernel
{
    public function __construct()
    {
        try {
            // dump(App::get());
            // dump($this->getContainer()->getRouting());exit;
            // dump($this->getContainer()->getRouting()->getActualRoute());
            $this->getContainer()->setService($this->getContainer()->getRouting()->getActualRoute()->getController());
            $controllerName = BasicUtils::explodeAndGetElement($this->getContainer()->getRouting()->getActualRoute()->getController(), '/', 'last');
            $controllerObject = $this->getContainer()->getService($controllerName);
            $controllerParent = BasicUtils::explodeAndGetElement(get_parent_class(get_class($controllerObject)), '\\', 'last');
            if (!in_array($this->getContainer()->getUrl()->getPageRoute()->getName(), array('setup', 'ElastiTools_js')) && $controllerParent == 'PageController') {
                $this->setService('VisitorPackage/service/VisitorLogService');
                $visitorLogService = $this->getService('VisitorLogService');
                $visitorLogService->init();

                $this->getContainer()->setService('VisitorPackage/service/RefererUrlService');
                $refererUrlService = $this->getContainer()->getService('RefererUrlService');
                $refererUrlService->init();

                /**
                 * @todo iterating on-page-load events comes here
                */
            }
        } catch(ElastiException $e) {
            if ($e->getCode() == 1660) {
                return true;
                // dump($e);exit;
            }
        }
    }
}
