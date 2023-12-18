<?php
namespace framework\packages\WebshopPackage\loader;

use framework\component\parent\PackageLoader;
use framework\packages\WebshopPackage\service\WebshopCartService;
use framework\packages\WebshopPackage\service\WebshopService;

class LoginAutoLoader extends PackageLoader
{
    const CONFIG = array(
        // 'dependsFrom' => 'UserPackage'
    );

    public function __construct()
    {
        if (!$this->getSession()->get('maintenanceMode')) {
            $this->wireService('WebshopPackage/service/WebshopService');
            $this->wireService('WebshopPackage/service/WebshopCartService');
            // $webshopService = $this->getService('WebshopService');
            if (WebshopService::getSetting('WebshopPackage_removeCartOnLogin') == true) {
                // dump(WebshopService::getSetting('WebshopPackage_removeCartOnLogin'));exit;
                WebshopCartService::removeObsoleteCarts();
            }
            // WebshopService::removeTemporaryAccount();
            WebshopCartService::identifyCart();
        }
    }
}
