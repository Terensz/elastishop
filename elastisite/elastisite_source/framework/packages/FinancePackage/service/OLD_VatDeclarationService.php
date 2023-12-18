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
    public $taxOfficeName;

    public $taxOffice;

    public $serverConfig;

    public static $configCache = [];

    public static function getPathToConfigFile($mode, $taxOfficeName)
    {
        return App::getContainer()->getPathBase('config').'/sysadmin/taxOffices/'.$mode.'/'.$taxOfficeName.'.txt';
    }

    public static function getConfig($taxOfficeName, $forceMode = false)
    {
        if (isset(self::$configCache[$taxOfficeName])) {
            return self::$configCache[$taxOfficeName];
        }
        $container = App::getContainer();
        $container->wireService('WebshopPackage/service/WebshopService');
        $mode = $forceMode === false ? (WebshopService::isWebshopTestMode() ? 'test' : 'prod') : $forceMode;
        $pathToFile = self::getPathToConfigFile($mode, $taxOfficeName);
        if (!FileHandler::fileExists($pathToFile)) {
            $pathToFile = self::getPathToConfigFile('example', $taxOfficeName);
        }
        // dump(FileHandler::fileExists($pathToFile));
        $configReader = App::$configReader;
        $config = $configReader->read($pathToFile);
        $config['mode'] = $mode;
        self::$configCache[$taxOfficeName] = $config;

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

    public function __construct($taxOfficeName)
    {
        $this->taxOfficeName = $taxOfficeName;
        $this->setServerConfig();
        $this->setTaxOffice();
    }

    public function setServerConfig()
    {
        $config = self::getConfig($this->taxOfficeName);
        // dump($config);
        $this->serverConfig['APIUrlBase'] = $config['taxOffice.APIUrlBase'];
        // $this->serverConfig['APIRoutes']['tokenExchange'] = $config['server.APIRoutes.tokenExchange'];
    }

    public function setTaxOffice()
    {
        $this->setService('FinancePackage/taxOffices/'.$this->taxOfficeName.'/TaxOffice', 'TaxOffice-'.$this->taxOfficeName);
        $this->taxOffice = $this->getService('TaxOffice-'.$this->taxOfficeName);
        $this->taxOffice->vatProfileHandler = $this;
        $this->taxOffice->init();
    }

    public function process(InvoiceCreator $invoiceCreator)
    {
        return $this->taxOffice->process($invoiceCreator);
    }
}
