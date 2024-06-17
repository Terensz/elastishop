<?php
namespace framework\kernel\base;

use App;
use framework\component\exception\ElastiException;
use framework\kernel\DbManager\entity\DbConnection;
use framework\kernel\DbManager\manager\DbManager;
use framework\kernel\request\Request;
use framework\kernel\request\Session;
use framework\kernel\request\Upload;
use framework\kernel\request\UploadRequest;
use framework\kernel\routing\entity\Routing;
use framework\kernel\routing\RoutingHelper;
use framework\kernel\routing\Url;
use framework\kernel\utility\FileHandler;
use framework\kernel\utility\BasicUtils;
use framework\packages\UserPackage\entity\User;

class Container
{
    public $debug;
    private $pagePropertyCache = [];
    private $currentTimestamp;
    private $controllerParent;
    private static $container;
    private $pathBases = array();
    private $kernelObjects;
    private $serviceObjects;
    private $controllerObject;
    // private $postRequests;
    // private $urlRequests;
    private $systemMessages = [];
    private $defaultLocale = 'en';
    private $env;
    private $user;
    private $visitor;
    private $routing;
    // private $website;
    private $failedRoute;
    public $fullRouteMap = [];
    // public $builtInRouteMap = [];
    private $widgetMap = [];
    private $widgets = [];
    private $widgetScripts;
    private $dbConnections = [];
    private $fileMap = [];
    private $entityMap = [];
    private $cache = [];
    private $skinData;

    /**
	* Barmelyik tombnek (a class-ban) visszaadja tombkent az osszes kulcsat.
    */
    // public static function getKeys($varName)
    // {
    //     if (isset(self::getSelfObject()->$varName)) {
    //         $keys = array();
    //         foreach (self::getSelfObject()->$varName as $key => $value) {
    //             $keys[] = $key;
    //         }
    //         return $keys;
    //     } else {
    //         throw new ElastiException('Missing varName: '.$varName);
    //     }
    // }
    public function __construct()
    {
        // App::
        // App::setEnv();
    }

    public static function isAdminPage()
    {
        $pageRoute = App::getContainer()->getRouting()->getPageRoute()->getName();
        $pageRouteParts = explode('_', $pageRoute);
        return $pageRouteParts[0] == 'admin' ? true : false;

        // var_dump(App::getContainer()->getRouting()->getPageRoute()->getName());exit;
        // if (App::getContainer()->getUrl()->getMainRouteRequest() == 'admin' || App::getContainer()->getRouting()->getPageRoute() == 'admin_login') {
        //     return true;
        // }
        // return false;
    }

    public function getPageProperty($key)
    {
        // var_dump(App::getContainer()->getUrl());exit;
        if (isset($this->pagePropertyCache[$key])) {
            return $this->pagePropertyCache[$key];
        }
        if ($key == 'isAdminPage') {
            $isAdminPage = self::isAdminPage();
            $this->pagePropertyCache[$key] = $isAdminPage;

            return $isAdminPage;
        }
        if ($key == 'sheetWidthPercent') {
            $isAdminPage = $this->getPageProperty('isAdminPage');
            // dump($isAdminPage);exit;
            $sheetWidthPercent = $this->getSkinData('sheetWidthPercent');
            if ($isAdminPage) {
                if ($this->getSkinData('adminSheetWidthPercent')) {
                    $sheetWidthPercent = $this->getSkinData('adminSheetWidthPercent');
                }
            }
            $this->pagePropertyCache[$key] = $sheetWidthPercent;

            return $sheetWidthPercent;
        }
        if ($key == 'sheetMaxWidth') {
            $isAdminPage = $this->getPageProperty('isAdminPage');
            if ($isAdminPage) {

                return '';
            }

            return !empty($this->getSkinData('sheetMaxWidth')) ? $this->getSkinData('sheetMaxWidth') : '';
        }

        return null;
    }

    public function setCurrentTimestamp($forceUpdate = false)
    {
        if (!$this->currentTimestamp || $forceUpdate) {
            $this->currentTimestamp = new \DateTime();
        }
    }

    public function getCurrentTimestamp()
    {
        if (!$this->currentTimestamp) {
            $this->setCurrentTimestamp(true);
        }
        return $this->currentTimestamp;
    }

    public function setControllerParent($controllerParent)
    {
        if (!$this->controllerParent) {
            $this->controllerParent = $controllerParent;
        }
    }

    public function getControllerParent()
    {
        return $this->controllerParent;
    }

    public function setOpenGraphData($openGraphData = false)
    {
        $this->cache['openGraphData']['full'] = $openGraphData;
    }

    public function getOpenGraphData($clearCache = false)
    {
        // dump($this->getRouting()->getPageRoute()->getName());exit;
        $this->setService('FrameworkPackage/service/OpenGraphService');

        // $this->getFromCache('openGraphData'

        $cached = null;
        if ($clearCache) {
            $this->cache['openGraphData']['full'] = null;
        } else {
            $cached = $this->getFromCache('openGraphData', 'full');
        }

        if ($cached) {
            return $cached;
        }
        if ($this->getRouting()->getPageRoute()->getName() == 'setup') {
            return [
                'title' => trans('setup'),
                'description' => trans('setup'),
                'type' => 'website',
                'image' => null,
                'locale' => strtolower($this->getSession()->getLocale()).'-'.strtoupper($this->getSession()->getLocale()),
                'url' => $this->getUrl()->getHttpDomain(),
                'site_name' => $this->getUrl()->getHttpDomain(),
                'updated_time' => null
            ];
        }

        $openGraphService = $this->getService('OpenGraphService');
        $openGraphObject = $openGraphService->getOpenGraphObject($this->getRouting()->getPageRoute()->getName());        
        $todayMorning = new \DateTime(date('Y-m-d').' 06:00:00');
        $yesterdayMorning = $todayMorning->sub(new \DateInterval('PT24H'));
        $OGdata = [
            'title' => $openGraphObject->getTitle(),
            'description' => $openGraphObject->getDescription(),
            'type' => 'website',
            'image' => $openGraphService->getOpenGraphImageLink($openGraphObject),
            'locale' => strtolower($this->getSession()->getLocale()).'-'.strtoupper($this->getSession()->getLocale()),
            'url' => $this->getUrl()->getHttpDomain(),
            'site_name' => $this->getUrl()->getHttpDomain(),
            'updated_time' => $yesterdayMorning->format('Y-m-d H:i:s')
        ];
        $cached = $this->addToCache('openGraphData', 'full', $OGdata);
        // dump($OGdata);exit;
        return $OGdata;
    }

    public function packageInstalled($packageName)
    {
        $bannedPackages = $this->getProjectData('bannedPackages');
        if (!$bannedPackages) {
            $bannedPackages = array();
        }
        if (!is_array($bannedPackages)) {
            $bannedPackages = array($bannedPackages);
        }
        if (in_array($packageName, $bannedPackages)) {
            return false;
        }
        if (!in_array($packageName, $this->getPackageNames())) {
            return false;
        }
        // dump($this->getPackageNames());exit;
        return true;
    }

    public function getPackageNames()
    {
        return FileHandler::getAllDirNames('framework/packages', 'source');
    }

    public function getCompanyData($key = null)
    {
        // dump('getCompanyData!');
        return $this->getKernelObject('Config')->getCompanyData($key);
    }

    public function getProjectData($key = null)
    {
        return $this->getKernelObject('Config')->getProjectData($key);
    }

    public function getSkinData($key = null)
    {
        // return $this->getKernelObject('Config')->getSkinData($key);
        // dump($this->skinData);
        // dump($this->getKernelObject('Config')->getSkinData());
        if (!$key) {
            $result = array();
            foreach ($this->getKernelObject('Config')->getSkinData() as $key => $value) {
                $result[$key] = isset($this->skinData[$key]) ? $this->skinData[$key] : $value;
            }
            return $result;
        } else {
            return isset($this->skinData[$key]) 
			? $this->skinData[$key] 
			: $this->getKernelObject('Config')->getSkinData($key);
        }
    }

    public function setSkinData($key, $value)
    {
        $this->skinData[$key] = $value;
    }

    public function setFailedRoute($failedRoute)
    {
        $this->failedRoute = $failedRoute;
    }

    public function getFailedRoute()
    {
        return $this->failedRoute;
    }

    public function setPathBases($pathBaseConfig, $sourcePath)
    {
        // dump($pathBaseConfig);
        foreach ($pathBaseConfig as $pathBaseType => $pathBase) {
            $this->pathBases[$pathBaseType] = $pathBase;
        }
        $this->pathBases['source'] = $sourcePath;
        // echo var_export($this->pathBases);exit;
    }

    public function getPathBase($pathBaseName = 'all')
    {
        if ($pathBaseName == 'all') {
            return $this->pathBases; 
        } 
        return $this->pathBases[$pathBaseName];
    }

    public function addPathBase($pathBaseName, $value)
    {
        $this->pathBases[$pathBaseName] = $value;
    }

    public function getLocale()
    {
        return $this->getSession()->getLocale();
    }

    // public function setDefaultLocale($defaultLocale)
    // {
    //     $this->defaultLocale = $defaultLocale;
    // }

    public function getDefaultLocale()
    {
        $autoLocale = $this->getConfig()->getGlobal('website.defaultLocale');

        return $autoLocale ? : $this->defaultLocale;
    }

    public function wrapExceptionParams($placeholderParams = array())
    {
        return array(
            'container' => $this,
            'placeholderParams' => $placeholderParams
        );
    }

    public function getSystemTranslations()
    {
        $this->setService('FrameworkPackage/translation/Translation_'.$this->getSession()->getLocale(), 'systemTransClass');
        $originalSystemTranslations = $this->getService('systemTransClass')->getTranslation();
        $actualizedSystemTranslations = array();
        foreach ($originalSystemTranslations as $key => $value) {
            $actualizedSystemTranslations[$key] = trans($key);
        }
        return $actualizedSystemTranslations;
    }

    public function addDbConnection($dbConnection)
    {
        $this->dbConnections[$dbConnection->getId()] = $dbConnection;
    }

    public function getDbConnection($dbConnectionId = 'default') : ? DbConnection
    {
        return isset($this->dbConnections[$dbConnectionId]) ? $this->dbConnections[$dbConnectionId] : null;
    }

    public function getDbConnections()
    {
        return $this->dbConnections;
    }

    public static function getSelfObject() : Container
    {
        return self::$container;
    }

    public static function setSelfObject()
    {
        if (!self::$container) {
            self::$container = new Container();
        }
    }

    public function addSystemMessage($text, $level, $subject)
    {
        $systemMessage = ['text' => $text, 'level' => $level, 'subject' => $subject];
        self::$container->systemMessages[] = $systemMessage;
    }

    public function getSystemMessages($args = [])
    {
        if ($args == []) {
            return self::$container->systemMessages;
        }
        $args['level'] = (isset($args['level'])) ? $args['level'] : '*all*';
        $args['subject'] = (isset($args['subject'])) ? $args['subject'] : '*general*';

        $returnArray = [];
        foreach (self::$container->systemMessages as $message) {
            if (($message['level'] == $args['level'] || $args['level'] == '*all*')
            && ($message['subject'] == $args['subject'] || $args['subject'] == '*general*'))  {
                $returnArray[] = $message;
            }
        }
        return $returnArray;
    }

    public function getConfig() : Config
    {
        return self::getSelfObject()->getKernelObject('Config');
    }

    public function issetKernelObject($className)
    {
        return isset(self::$container->kernelObjects[$className]) ? true : false;
    }

    public function getDbManager() : DbManager
    {
        return self::getSelfObject()->getKernelObject('DbManager');
    }

    public function getKernelObject($className)
    {
        // dump($className);
        // dump(self::$container->kernelObjects);
        if (isset(self::$container->kernelObjects[$className])) {
            return self::$container->kernelObjects[$className];
        } else {
            throw new ElastiException('Missing kernelObject: '.$className, ElastiException::ERROR_TYPE_SECRET_PROG);
        }
    }

    public function getAllKernelObjects()
    {
        return self::$container->kernelObjects;
    }

    // public function convertNamespaceToPath($namespace)
    // {
    //     $serviceLink = str_replace('\\', '/', $namespace);
    //     $serviceLinkParams = $this->getServiceLinkParams($serviceLink);
    //     dump($serviceLinkParams);exit;
    // }

    public function setKernelObject($object)
    {
        $classNameArray = explode('\\', get_class($object));
        $className = $classNameArray[count($classNameArray) - 1]; # \app|systemFiles\core\Site formatumban adja a get_class.
        self::$container->kernelObjects[$className] = $object;
    }

    public function unsetKernelObject($objectName)
    {
        self::$container->kernelObjects[$objectName] = null;
    }

    public function getSpecificServiceLinkParams(
        $serviceLink,
        $pathBaseName,
        $identifyByFullString = false,
        $identifyByStringEndsWith = false,
        $shortPathPre = '',
        $namespacePre = ''
    )
    {
// $serviceLink = 'AlmaPackage/routeMap/AlmaRouteMap.php';
// $pathBaseName = 'source';
// $identifyByFullString = false;
// $identifyByStringPart = 'Package';
// $shortPathPre = 'framework/packages/';

// $serviceLink = 'kernel/base/routeMap/AlmaRouteMap.php';
// $pathBaseName = 'source';
// $identifyByFullString = 'kernel';
// $identifyByStringPart = false;
// $shortPathPre = 'framework/';

// $serviceLink = 'Atpm/routeMap/AlmaRouteMap.php';
// $pathBaseName = 'projects';
// $identifyByFullString = false;
// $identifyByStringPart = false;
// $shortPathPre = '';
// $namespacePre = 'projects';

        $found = false;
        $serviceLinkParts = explode('/', $serviceLink);
        $shortPath = '';
        for ($i = 0; $i < count($serviceLinkParts); $i++) {
            $identifierFoundInThisLoop = false;
            if ($identifyByStringEndsWith) {
                $identifierFoundInThisLoop = BasicUtils::endsWith($serviceLinkParts[$i], 'Package');
            } elseif ($identifyByFullString) {
                $identifierFoundInThisLoop = $serviceLinkParts[$i] == $identifyByFullString ? true : false;
            }
            // elseif ($pathBaseName = 'projects') {
            //     // $identifierFoundInThisLoop = $serviceLinkParts[$i] == $identifyByFullString ? true : false;
            //     $found = true;
            // } else {
            // }

            if ($identifierFoundInThisLoop) {
                // $identifiedPartIndex = $i;
                $found = true;
                $shortPath .= $serviceLinkParts[$i];
            } else {
                if ($found && $i != count($serviceLinkParts) - 1) {
                    $shortPath .= '/'.$serviceLinkParts[$i];
                }
            }
            if ($i == count($serviceLinkParts) - 1) {
                $objectName = str_replace('.php', '', $serviceLinkParts[$i]);
            }
        }

        if (!$found) {
            return false;
        }

        $shortPath = $shortPathPre.trim($shortPath, '/');
        $fullPath = '/'.trim($this->getPathBase($pathBaseName), '/').'/'.$shortPath;

        $realObjectName = strpos($objectName, '.') === false ? true : false;

        $return = array(
            'serviceLink' => $serviceLink,
            'pathBaseName' => $pathBaseName,
            'shortPath' => $shortPath,
            'fullPath' => $fullPath,
            'objectName' => $objectName,
            'pathToFile' => $fullPath.'/'.$objectName.($realObjectName ? '.php' : ''),
            'objectNamespace' => trim(trim($namespacePre, '\\').'\\'.trim(str_replace('/', '\\', $shortPath.'/'.$objectName), '\\'), '\\')
        );
        // dump($return);//exit;
        return $return;
    }

    public function getServiceLinkParams($serviceLink)
    {
        $packageServiceLinkParams = $this->getSpecificServiceLinkParams(
            $serviceLink,
            'source',
            false,
            'Package',
            'framework/packages/'
        );
        if ($packageServiceLinkParams) {
            return $packageServiceLinkParams;
        }

        $kernelServiceLinkParams = $this->getSpecificServiceLinkParams(
            $serviceLink,
            'source',
            'kernel',
            false,
            'framework/'
        );
        if ($kernelServiceLinkParams) {
            return $kernelServiceLinkParams;
        }

        $projectServiceLinkParams = $this->getSpecificServiceLinkParams(
            $serviceLink,
            'projects',
            'projects',
            false,
            ''
        );
        if ($projectServiceLinkParams) {
            return $projectServiceLinkParams;
        }

        // $objectName = str_replace('.php', '', BasicUtils::explodeAndGetElement($serviceLink, '/', 'last'));
        // $fullPath = BasicUtils::explodeAndRemoveElement($serviceLink, '/', 'last');
        // $return = array(
        //     'serviceLink' => $serviceLink,
        //     'pathBaseName' => null,
        //     'shortPath' => $fullPath,
        //     'fullPath' => $fullPath,
        //     'objectName' => $objectName,
        //     'pathToFile' => $serviceLink.'/'.$objectName.'.php',
        //     'objectNamespace' => trim(trim($namespacePre, '\\').'\\'.trim(str_replace('/', '\\', $shortPath.'/'.$objectName), '\\'), '\\')
        // );
        // dump($return);exit;
        // return $return;
        throw new ElastiException($this->wrapExceptionParams(array('serviceLink' => $serviceLink)), 1620);
    }

    public function wireService($serviceLink)
    {
        $serviceLinkParams = $this->getServiceLinkParams($serviceLink);
        FileHandler::includeFileOnce($serviceLinkParams['pathToFile']);
        return $serviceLinkParams;
    }

    public function wireServiceDir($serviceLink, $recursive = false)
    {
        $serviceLinkParams = $this->getServiceLinkParams($serviceLink);
        $fullPath = $serviceLinkParams['fullPath'].'/'.$serviceLinkParams['objectName'];

        foreach (FileHandler::getAllFileNames($fullPath) as $file) {
            // $pathToFile = $fullPath.'/'.$file;
            // dump($pathToFile);
            FileHandler::includeFileOnce($fullPath.'/'.$file);
        }

        if ($recursive) {
            foreach (FileHandler::getAllDirNames($fullPath) as $dir) {
                $this->wireServiceDir($fullPath.'/'.$dir);
            }
        }
    }

    public function setService($serviceLink, $alias = null, $constructArgs = null)
    {
        $serviceLinkParams = $this->getServiceLinkParams($serviceLink);

        if (!$alias) {
            $alias = $serviceLinkParams['objectName'];
        }

        if (!isset(self::$container->serviceObjects[$alias])) {
            // dump($serviceLinkParams);
            FileHandler::includeFileOnce($serviceLinkParams['pathToFile']);
            $objectNamespace = $serviceLinkParams['objectNamespace'];
            $service = $constructArgs ? new $objectNamespace($constructArgs) : new $objectNamespace();
            self::$container->serviceObjects[$alias] = $service;
        }
    }

    public function setServiceObject($serviceObject, $alias)
    {
        if (!isset(self::$container->serviceObjects[$alias])) {
            self::$container->serviceObjects[$alias] = $serviceObject;
        }
    }

    public function getService($alias, $exceptionIfNotSet = true)
    {
        if (!isset(self::$container->serviceObjects[$alias])) {
            // dump(self::$container->serviceObjects);
            if ($exceptionIfNotSet) {
                throw new ElastiException($this->wrapExceptionParams(array('service' => $alias)), 1621);
                // throw new ElastiException('Unknown service: '.$alias, ElastiException::ERROR_TYPE_SECRET_PROG);
            } else {
                return null;
            }
        }
        return self::$container->serviceObjects[$alias];
    }

    // dev
    public function dumpServices()
    {
        $ret = array();
        $serviceObjects = self::$container->serviceObjects;
        foreach ($serviceObjects as $key => $serviceObject) {
            $ret[] = $key;
        }
        dump($ret);
    }

    public function getRoutingHelper() : RoutingHelper
    {
        return $this->getKernelObject('RoutingHelper');
    }

    public function getSession() : Session
    {
        // return $this->getKernelObject('Session');
        if (isset(self::$container->kernelObjects['Session'])) {
            return self::$container->kernelObjects['Session'];
        } else {
            return null;
        }
    }

    public function isAjax()
    {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }

    // public function getWebsite()
    // {
    //     return App::getWebsite();
    // }

    // public function setWebsite($website)
    // {
    //     $this->website = $website;
    // }

    public function isGranted($permission, $userObject = null)
    {
        if (!$permission || $permission == '') {
            return true;
        }

        $perm = self::$container->getService('Permission');
        if ($perm) {
            return $perm::check($permission, $userObject);
        }
        return false;
    }

    public function getUrl() : Url
    {
        return self::$container->getKernelObject('Url');
    }

    public function getRequest() : Request
    {
        return self::$container->getKernelObject('Request');
    }

    // public function getUpload() : ? Upload
    // {
    //     return self::$container->getKernelObject('Upload');
    // }

    public function getUploadRequest() : ? UploadRequest
    {
        return self::$container->getKernelObject('UploadRequest');
    }

    // public function setEnv()
	// {
    //     $this->env = 'dev'; return;
    //     if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1') {
    //         $this->env = 'dev';
    //     } else {
    //         $this->env = 'prod';
    //     }
    // }

    public function getEnv()
    {
        return App::getEnv();
    }

    public function getRouting() : Routing
    {
        return $this->routing;
    }

    public function setRouting($routing)
    {
        $this->routing = $routing;
    }

    public function getUser() : ? User
    {
        return !$this->user ? null : $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getVisitor()
    {
        return $this->visitor;
    }

    public function setVisitor($visitor)
    {
        $this->visitor = $visitor;
    }

    public function getFullRouteMap()
    {
        return $this->fullRouteMap;
    }

    public function getPermittedFullRouteMap($allowedPermissions = null)
    {
        $perm = self::$container->getService('Permission');
        $return = array();
        foreach ($this->fullRouteMap as $routeMapElement) {
            if ($perm) {
                if ($perm::check($routeMapElement['permission'])) {
                    if (!$allowedPermissions || ($allowedPermissions && is_array($allowedPermissions)
                        && in_array($routeMapElement['permission'], $allowedPermissions))) {
                        $return[] = $routeMapElement;
                    }
                }
            }
        }
        return $return;
    }

    // public function addBuiltInRouteMapElement($routeMapElement)
    // {
    //     if ($routeMapElement['codeLocation'] != 'projects') {
    //         $allowed = $this->getProjectData('allowedBuiltInRoutes');
    //         $banned = $this->getProjectData('bannedBuiltInRoutes');
    //         if (!$banned) {
    //             $banned = [];
    //         }
    //         if (!$allowed || $allowed == 'all' || (is_array($allowed) && in_array($routeMapElement['name'], $allowed))) {
    //             if (is_array($banned) && !in_array($routeMapElement['name'], $banned)) {
    //                 $this->builtInRouteMap[$routeMapElement['name']] = $routeMapElement;
    //             }
    //         }
    //     } else {
    //         $this->builtInRouteMap[$routeMapElement['name']] = $routeMapElement;
    //     }
    // }

    public function resolveCodeLocationConflict($old, $new)
    {
        if ($old == 'projects' && $new == 'packages') {
            return 'old';
        } elseif ($old == 'packages' && $new == 'projects') {
            return 'new';
        } else {
            return null;
        }
    }

    public function getWidgetParams($widgetName)
    {
        // dump($this->widgetMap);
        return isset($this->widgetMap[$widgetName]) ? $this->widgetMap[$widgetName] : null;
    }

    public function addWidgetToMap($widgetName, $path, $widgetLocation, $scriptsLocation = 'SAME_AS_WIDGET_LOCATION')
    {
        # Rules are simple.
        # - If a non-existing widget comes from the packages, this will be mapped.
        # - If an existing widget comes from a project, this will override the one from packages.
        # - If an existing widget comes from a project, and the project does not contain a scripts file,
        # the widget path will be overrided, but the scripts path will remain the one from the packages.
        if (isset($this->widgetMap[$widgetName])) {
            $resolvedLocationConflict = $this->resolveCodeLocationConflict($this->widgetMap[$widgetName]['originalLocation'], $widgetLocation);
            if ($resolvedLocationConflict == 'new') {
                if ($widgetLocation) {
                    $this->widgetMap[$widgetName]['widgetLocation'] = $widgetLocation;
                    $this->widgetMap[$widgetName]['widgetPath'] = $path;
                }
                if ($scriptsLocation) {
                    $this->widgetMap[$widgetName]['scriptsLocation'] = ($scriptsLocation == 'SAME_AS_WIDGET_LOCATION' ? $widgetLocation : $scriptsLocation);
                    $this->widgetMap[$widgetName]['scriptsPath'] = $path;
                }
            } else {
                if (!$this->widgetMap[$widgetName]['widgetPath']) {
                    $this->widgetMap[$widgetName]['widgetPath'] = $path;
                }
                if (!$this->widgetMap[$widgetName]['scriptsPath']) {
                    $this->widgetMap[$widgetName]['scriptsPath'] = $path;
                }
            }
        } else {
            $this->widgetMap[$widgetName]['originalLocation'] = $widgetLocation;
            $this->widgetMap[$widgetName]['widgetLocation'] = $widgetLocation;
            $this->widgetMap[$widgetName]['scriptsLocation'] = ($scriptsLocation == 'SAME_AS_WIDGET_LOCATION' ? $widgetLocation : $scriptsLocation);
            $this->widgetMap[$widgetName]['widgetPath'] = $path;
            $this->widgetMap[$widgetName]['scriptsPath'] = $path;
        }
    }

    public function setWidgetMap($widgetMap)
    {
        $this->widgetMap = $widgetMap;
    }

    public function getWidgetMap()
    {
        return $this->widgetMap;
    }

    public function getWidgets()
    {
        return $this->widgets;
    }

    public function getWidget($widgetName)
    {
        return isset($this->widgets[$widgetName]) ? $this->widgets[$widgetName] : null;
    }

    public function addWidget($widget)
    {
        $this->widgets[$widget->getName()] = $widget;
    }

    public function setWidgets($widgets)
    {
        $this->widgets = $widgets;
    }

    public function getWidgetScripts()
    {
        return $this->widgetScripts;
    }

    public function setWidgetScripts($widgetScripts)
    {
        $this->widgetScripts = $widgetScripts;
    }

    public function searchFileMap($conditions = null)
    {
        if (!$conditions) {
            return $this->fileMap;
        }
        $result = array();
        foreach ($this->fileMap as $fileMapElement) {
            $conditionFailCounter = 0;
            foreach ($conditions as $conditionKey => $conditionValue) {
                if ($fileMapElement[$conditionKey] != $conditionValue) {
                    $conditionFailCounter++;
                }
            }
            if ($conditionFailCounter == 0) {
                $result[] = $fileMapElement;
            }
        }
        return $result;
    }

    public function setFileMap($fileMap)
    {
        $this->fileMap = $fileMap;
    }

    public function getFileMap()
    {
        return $this->fileMap;
    }

    public function addFileMapPart($fileMapPart)
    {
        foreach ($fileMapPart as $fileMapPartElement) {
            $this->fileMap[] = $fileMapPartElement;
        }
    }

    public function getEntityMap()
    {
        if ($this->entityMap) {
            return $this->entityMap;
        }
        $this->setService('framework/kernel/EntityManager/EntityChecker');
        $entityChecker = $this->getService('EntityChecker');
        $this->entityMap = $entityChecker->createEntityMap();
        return $this->entityMap;
    }

    public function addToCache($cacheName, $key, $value)
    {
        if (!isset($this->cache[$cacheName])) {
            $this->cache[$cacheName] = array();
        }
        $this->cache[$cacheName][$key] = $value;
    }

    public function getFromCache($cacheName, $key)
    {
        if (!isset($this->cache[$cacheName])) {
            return null;
        }
        return isset($this->cache[$cacheName][$key]) ? $this->cache[$cacheName][$key] : null;
    }

    // private $associationDetectings = [];

    // public function getPageController()
    // {
    //     return self::getSelfObject()->getKernelObject('PageController');
    // }
}
