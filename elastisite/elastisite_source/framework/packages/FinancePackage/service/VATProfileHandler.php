<?php
namespace framework\packages\FinancePackage\service;

use App;
use framework\component\parent\Service;
use framework\kernel\utility\FileHandler;
use framework\packages\WebshopPackage\service\WebshopService;

/*
1.: Require token from /tokenExchange
*/
class VATProfileHandler extends Service
{
    public $vatProfileName;

    public $invoiceCreation;

    public $taxOffice;

    public $serverConfig;

    public static $configCache = [];

    public static function getPathToConfigFile($vatProfileName, $mode)
    {
        return App::getContainer()->getPathBase('config').'/sysadmin/VATProfiles/'.$vatProfileName.'_'.$mode.'.txt';
    }

    /**
     * What determines if you use prod, test or the example?
     * 
     * Either of those two below:
     * 1.: the "webshopEnabled" parameter of the elastisite_config/projects/[actualProject]/project_config.txt file
     * 2.: Or: the "viewWebshopTesterContent" permission.
     * 
     * You only can earn "viewWebshopTesterContent" permission, when register a user, than log in as administrator, go to "Users", than promote the registered user to "tester".
     * Testers cannot make a real purchase, not their tax will be reported as a real tax. Instead, their tax will be reported as a test purchase.
    */
    public static function getConfig(string $vatProfileName, $forceMode = false)
    {
        if (isset(self::$configCache[$vatProfileName])) {
            return self::$configCache[$vatProfileName];
        }
        $container = App::getContainer();
        $container->wireService('WebshopPackage/service/WebshopService');
        $mode = $forceMode === false ? (WebshopService::isWebshopTestMode() ? 'test' : 'prod') : $forceMode;

        $pathToFile = self::getPathToConfigFile($vatProfileName, $mode);
        // dump($pathToFile);exit;
        if (!FileHandler::fileExists($pathToFile)) {
            $pathToFile = self::getPathToConfigFile($vatProfileName, 'example');
        }

        // dump(FileHandler::fileExists($pathToFile));
        $configReader = App::getConfigReader();
        $config = $configReader->read($pathToFile);
        $config['mode'] = $mode;
        self::$configCache[$vatProfileName] = $config;
        // dump($config);exit;

        return $config;
    }

    public static function getArrangedConfig($taxOfficeName, $forceMode = false, $allowModify = true)
    {
        $config = self::getConfig($taxOfficeName, $forceMode);
        dump($config);exit;
        $settingsArray = [];
        // $settingsArray[] = [
        //     'property' => 'environment',
        //     'title' => trans('software.environment'),
        //     'value' => trans(),
        //     'modifiable' => 'no'
        // ];

        // return [
        //     'allowModify' => $allowModify,
        //     'settingArray' => [
        //         [
        //             'property' => 'environment',
        //             'title' => trans('software.environment'),
        //             'value' => trans($this->getContainer()->getEnv()),
        //             'modifiable' => 'no'
        //         ],
        //         [
        //             'property' => 'taxOfficeName',
        //             'title' => trans('software.environment'),
        //             'value' => trans($this->getContainer()->getEnv()),
        //             'modifiable' => 'no'
        //         ],
        //     ]
        // ];
    }

    public function __construct($vatProfileName)
    {
        $this->vatProfileName = $vatProfileName;
        $vatProfileParts = explode('_', $vatProfileName);
        if (count($vatProfileParts) != 2) {
            throw new \Exception('VATProfile should contain 3 parts. Now it contains '.count($vatProfileParts));
        }
        $this->taxOfficeName = $vatProfileParts[0];
        $this->setServerConfig();
// dump($this);
        $this->setTaxOffice();
// dump($this);
    }

    public function setServerConfig()
    {
        $config = self::getConfig($this->vatProfileName);
        // dump($config);
        $this->serverConfig['APIUrlBase'] = $config['taxOffice.APIUrlBase'];
        // $this->serverConfig['APIRoutes']['tokenExchange'] = $config['server.APIRoutes.tokenExchange'];
    }

    public function setTaxOffice()
    {
        $this->setService('FinancePackage/taxOffices/'.$this->taxOfficeName.'/TaxOffice', 'TaxOffice-'.$this->taxOfficeName);
        $this->taxOffice = $this->getService('TaxOffice-'.$this->taxOfficeName);
        $this->taxOffice->vatProfileHandler = $this;
// dump($this->taxOffice);
// dump('before init');
        $this->taxOffice->init();
// dump('initialized');
    }

    public function process(InvoiceCreator $invoiceCreator)
    {
        return $this->taxOffice->process($invoiceCreator);
    }
}
