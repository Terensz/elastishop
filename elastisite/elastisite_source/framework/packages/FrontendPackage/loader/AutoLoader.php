<?php
namespace framework\packages\FrontendPackage\loader;

use App;
use framework\component\parent\PackageLoader;
use framework\packages\FrontendPackage\service\Fonts;

class AutoLoader extends PackageLoader
{
    const CONFIG = array(
        // 'dependsFrom' => 'TranslatorPackage'
    );

    public function __construct()
    {
        $this->wireService('FrontendPackage/service/Fonts');
        $fonts = new Fonts();
        $fonts->registerFonts();
        App::getContainer()->setKernelObject($fonts);

        $this->wireService('FrontendPackage/service/ResponsivePageService');
    }
}
