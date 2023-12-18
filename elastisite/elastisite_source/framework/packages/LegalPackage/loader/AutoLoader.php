<?php
namespace framework\packages\LegalPackage\loader;

use framework\component\parent\PackageLoader;

class AutoLoader extends PackageLoader
{
    const CONFIG = array(
        'dependsFrom' => 'UserPackage'
    );

    public function __construct()
    {
        $this->wireService('LegalPackage/entity/VisitorConsent');
        $this->wireService('LegalPackage/repository/VisitorConsentRepository');
        $this->wireService('LegalPackage/entity/VisitorConsentAcceptance');
        $this->wireService('LegalPackage/repository/VisitorConsentAcceptanceRepository');
    }
}
