<?php
namespace framework\packages\FrontendPackage\controller;

use App;
use framework\component\parent\AccessoryController;
use framework\kernel\utility\FileHandler;
use framework\packages\FrontendPackage\service\Fonts;

class FontController extends AccessoryController
{
    /**
    * Route: [name: font/loader, paramChain: /font/loader]
    */
    public function fontLoaderAction()
    {
        $fonts = App::getContainer()->getKernelObject('Fonts');

        // dump($fonts); exit;

        $view = $this->renderView('framework/packages/FrontendPackage/view/FontLoader/fontLoaderCss.php', [
            // 'container' => $this->getContainer()
            'fonts' => $fonts
        ]);

        // dump($fonts);
        // dump('alma'); exit;

        header("Content-type: text/css");
        echo $view;
    }
}
