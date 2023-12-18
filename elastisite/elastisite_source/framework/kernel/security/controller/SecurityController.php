<?php
namespace framework\kernel\security\controller;

use framework\kernel\utility\BasicUtils;
use framework\component\parent\PageController;
use framework\component\parent\JsonResponse;
use framework\packages\UserPackage\entity\User;
use framework\packages\UserPackage\Permission;
use framework\component\exception\ElastiException;

class SecurityController extends PageController
{
    public function attackerWarningAction()
    {
        // $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
        // return $this->renderPage(
        //     ['container' => $this->getContainer()],
        //     [],
        //     'framework/kernel/security/view/warning.php'
        // );
        $page = $this->renderPenTesterWarningPage(['container' => $this->getContainer()]);
        // dump($securityEventHandler);
        // renderPage($viewData = [], $ajaxData = [], $skeletonPath = null, $title = null)
        echo $page;
        exit;
    }

    public function attackerBanAction()
    {
        // dump('Banhammer!!!');exit;
        // return $this->renderPage(
        //     ['container' => $this->getContainer()],
        //     [],
        //     'framework/kernel/security/view/warning.php'
        // );
        $page = $this->renderPenTesterBanPage(['container' => $this->getContainer()]);
        // renderPage($viewData = [], $ajaxData = [], $skeletonPath = null, $title = null)
        echo $page;
        exit;
    }
}