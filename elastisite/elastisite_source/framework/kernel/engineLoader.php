<?php
// var_dump('hello'); exit;
// function startSession($destroyExisting = false)
// {
//     if ($destroyExisting) {

//     }
// }
// include($sourceRelPath.'framework/kernel/error/shutdown.php');
// register_shutdown_function('shutdown');
// echo var_export($basePathConfig, true);exit;
// exit;
include($sourceRelPath.'framework/kernel/utility/FileHandler.php');
include($sourceRelPath.'framework/component/exception/ElastiException.php');
include($sourceRelPath.'framework/component/helper/MathHelper.php');
include($sourceRelPath.'framework/component/helper/StringHelper.php');
include($sourceRelPath.'framework/component/helper/DateUtils.php');
include($sourceRelPath.'framework/component/helper/UrlHelper.php');
include($sourceRelPath.'framework/kernel/utility/BasicUtils.php');

include($sourceRelPath.'framework/kernel/component/Kernel.php');
include($sourceRelPath.'framework/kernel/base/Container.php');
include($sourceRelPath.'framework/component/helper/RouteMapHelper.php');

$absolutePathRoot = realpath(dirname($_SERVER['DOCUMENT_ROOT']));
session_save_path($absolutePathRoot.'/elastisite/elastisite_dynamic/sessions');
// var_dump($absolutePathRoot);exit;

setlocale(LC_ALL, 'hu_HU.UTF-8');
date_default_timezone_set('Europe/Budapest');
ini_set('default_charset', 'UTF-8');
ini_set("session.gc_maxlifetime", 7776000);
session_set_cookie_params(7776000);
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 1000);
ini_set("session.cookie_lifetime", 7776000);
ini_set('max_execution_time', '30');

framework\kernel\base\Container::setSelfObject();
App::setEnv();
$container = framework\kernel\base\Container::getSelfObject();
$container::getSelfObject()->setPathBases($pathBaseConfig, $sourcePath);

include('../elastisite/elastisite_source/framework/kernel/base/Cache.php');
App::$cache = new framework\kernel\base\Cache();

include($sourceRelPath.'framework/kernel/base/Config.php');
$config = new framework\kernel\base\Config();
$container::getSelfObject()->setKernelObject($config);

include($sourceRelPath.'framework/kernel/routing/SetWebProject.php');
new framework\kernel\routing\SetWebProject();

// var_dump(App::getWebProject());exit;

try {
    session_name(App::getWebProject('engineLoader: setting session').'_ElastiSiteSession');
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (!empty($_SESSION['deleted_time']) && $_SESSION['deleted_time'] < time() - 180) {
        session_destroy();
        session_start();
    }
    ob_start();

    ini_set("highlight.comment", "#838383");
    ini_set("highlight.default", "#4994da");
    ini_set("highlight.html", "#ec6484");
    ini_set("highlight.keyword", "#94b6d6; font-weight: bold");
    ini_set("highlight.string", "#57bd48");

    // var_dump(session_id());exit;
    include($sourceRelPath.'framework/kernel/view/ViewRenderer.php');
    include($sourceRelPath.'framework/component/parent/Rendering.php');
    // include($sourceRelPath.'framework/kernel/debug/Dumper/dump.php');
    include($sourceRelPath.'framework/kernel/debug/AdvancedDump/AdvancedDump.php');
    include($sourceRelPath.'framework/kernel/base/Reflector.php');
    include($sourceRelPath.'framework/component/parent/RouteRendering.php');
    include($sourceRelPath.'framework/kernel/base/Includer.php');
    include($sourceRelPath.'framework/kernel/routing/Url.php');
    include($sourceRelPath.'framework/kernel/request/Session.php');
    include($sourceRelPath.'framework/kernel/ClassManager/AutoLoaderFactory.php');
    include($sourceRelPath.'framework/component/parent/Service.php');
    include($sourceRelPath.'framework/kernel/GeoIp/GeoIpTool.php');
    include($sourceRelPath.'framework/kernel/GeoIp/LocationHandler.php');
    include($sourceRelPath.'framework/component/interfaces/UserInterface.php');
    include($sourceRelPath.'framework/component/interfaces/EntityManagerInterface.php');
    include($sourceRelPath.'framework/component/parent/PackageLoader.php');
    include($sourceRelPath.'framework/component/parent/Repository.php');
    include($sourceRelPath.'framework/component/parent/FileBasedStorageRepository.php');
    include($sourceRelPath.'framework/component/parent/TechnicalRepository.php');
    include($sourceRelPath.'framework/component/parent/DbRepository.php');
    include($sourceRelPath.'framework/component/parent/DynamicDbRepository.php');
    include($sourceRelPath.'framework/component/parent/Entity.php');
    include($sourceRelPath.'framework/component/parent/FileBasedStorageEntity.php');
    include($sourceRelPath.'framework/component/parent/DbEntity.php');
    include($sourceRelPath.'framework/component/parent/ProjectUserBase.php');
    include($sourceRelPath.'framework/component/parent/TechnicalEntity.php');
    include($sourceRelPath.'framework/component/parent/DynamicDbEntity.php');
    include($sourceRelPath.'framework/kernel/ClassManager/loader/Loader.php');

} catch(\Exception $e) {
    var_dump($e);exit;
}

include($sourceRelPath.'framework/kernel/routing/RouteMapper.php');
include($sourceRelPath.'framework/kernel/widget/WidgetMapper.php');
include($sourceRelPath.'framework/kernel/DbManager/loader/Loader.php');
include($sourceRelPath.'framework/kernel/EntityManager/loader/Loader.php');
include($sourceRelPath.'framework/kernel/EntityRelationMapper/loader/Loader.php');
include($sourceRelPath.'framework/kernel/DbSchemaManager/loader/Loader.php');
include($sourceRelPath.'framework/kernel/request/VisitorHandler.php');
include($sourceRelPath.'framework/packages/ToolPackage/service/Uploader.php');
include($sourceRelPath.'framework/component/parent/Response.php');
include($sourceRelPath.'framework/component/parent/JsonResponse.php');
include($sourceRelPath.'framework/component/parent/ImageResponse.php');
include($sourceRelPath.'framework/component/parent/CustomFormValidator.php');
include($sourceRelPath.'framework/component/parent/JavaScript.php');
include($sourceRelPath.'framework/kernel/security/Security.php');
include($sourceRelPath.'framework/kernel/security/SecurityEventHandler.php');
include($sourceRelPath.'framework/kernel/security/RequestSecurity.php');
include($sourceRelPath.'framework/kernel/security/SecurityReporting.php');
include($sourceRelPath.'framework/kernel/request/Upload.php');
include($sourceRelPath.'framework/kernel/request/UploadRequest.php');
include($sourceRelPath.'framework/kernel/request/SetUploadRequests.php');
include($sourceRelPath.'framework/kernel/request/Request.php');
include($sourceRelPath.'framework/kernel/request/SetUrlRequests.php');
include($sourceRelPath.'framework/kernel/request/SetPostRequests.php');
include($sourceRelPath.'framework/component/parent/APIServiceController.php');
include($sourceRelPath.'framework/component/parent/PageController.php');
include($sourceRelPath.'framework/component/entity/Route.php');
include($sourceRelPath.'framework/component/entity/Widget.php');
include($sourceRelPath.'framework/kernel/routing/entity/Routing.php');
include($sourceRelPath.'framework/kernel/routing/RoutingHelper.php');
include($sourceRelPath.'framework/kernel/routing/Router.php');
include($sourceRelPath.'framework/kernel/eventHandling/OnPageLoadEventHandler.php');
include($sourceRelPath.'framework/component/parent/FormSchema.php');
include($sourceRelPath.'framework/component/parent/SimpleController.php');
include($sourceRelPath.'framework/component/parent/WidgetController.php');
include($sourceRelPath.'framework/component/parent/AccessoryController.php');
include($sourceRelPath.'framework/component/parent/BackgroundController.php');
include($sourceRelPath.'framework/packages/FrameworkPackage/service/BasicConstants.php');
include($sourceRelPath.'framework/kernel/ClassManager/ControllerLoader.php');
include($sourceRelPath.'framework/kernel/exception/controller/ExceptionController.php');
include($sourceRelPath.'framework/kernel/EntityManager/EntityChecker.php');
include($sourceRelPath.'framework/kernel/operation/OperationSupervisor.php');
include($sourceRelPath.'framework/component/helper/XMLHelper.php');
include($sourceRelPath.'framework/packages/UXPackage/service/ViewTools.php');
include($sourceRelPath.'framework/component/core/WidgetResponse.php');

set_error_handler(
    function($errno, $errstr, $errfile, $errline) {
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// dump(\App::get()->getContainer());exit;
// \App::get()->setContainer($container);
// dump($container::getSelfObject());
// dump($container::getSelfObject()->getRouting());

// $randomArray = [
//     'alma' => 'Alma',
//     'korte' => array(0 => 'Körte1', 1 => 'Ökörte'),
//     'szilva' => 'Szilva',
//     'entity' => $container
// ];
//
// dump($container::getSelfObject());exit;
try {
    // setcookie("TestCookie", 'alma');
    // dump($_COOKIE);exit;
    // dump($_SERVER['DOCUMENT_ROOT']);
    // echo 'alma';exit;
    // echo var_export($basePathConfig, true);exit;
    // echo var_export($pathBaseConfig, true);exit;
    // $container::getSelfObject()->setKernelObject($configReader);
    $routing = new framework\kernel\routing\entity\Routing();
    $container::getSelfObject()->setKernelObject($routing);
    $container::getSelfObject()->setRouting($routing);
    // echo 'alma';exit;
    // dump('alma');exit;
    $container::getSelfObject()->setKernelObject(new framework\kernel\routing\Url());
    $container::getSelfObject()->setKernelObject(new framework\kernel\base\Reflector());

    // if (!is_writable(session_save_path())) {
    //     throw new framework\component\exception\ElastiException(
    //         framework\kernel\base\Container::getSelfObject()->wrapExceptionParams(
    //             array('sessionSavePath' => session_save_path())
    //         ),
    //         1410
    //     );
    // }
    // dump('alma2');
    // throw new \Exception(
    //     'alma!!!!!'
    // );
    // exit;
    // throw new ElastiException(
    //     'Session path is not writable: '.session_save_path(),
    //     ElastiException::ERROR_TYPE_SECRET_PROG
    // );
    // $container::getSelfObject()->setKernelObject(new framework\kernel\EntityManager\EntityChecker());
    $container::getSelfObject()->setKernelObject(new framework\kernel\request\SetUrlRequests());
    // App::$cache->autoClearCache();
    $container::getSelfObject()->setKernelObject(new framework\kernel\request\Session());
    // $container::getSelfObject()->setKernelObject(new framework\kernel\GeoIp\GeoIpTool());
    $container::getSelfObject()->setKernelObject(new framework\kernel\GeoIp\LocationHandler());
    $container::getSelfObject()->setKernelObject(new framework\kernel\ClassManager\AutoLoaderFactory());
    $container::getSelfObject()->setKernelObject(new framework\kernel\DbManager\loader\Loader());
    $container::getSelfObject()->setKernelObject(new framework\kernel\EntityManager\loader\Loader());
    $container::getSelfObject()->setKernelObject(new framework\kernel\EntityRelationMapper\loader\Loader());
    $container::getSelfObject()->setKernelObject(new framework\kernel\DbSchemaManager\loader\Loader());

    $container::getSelfObject()->setService('ToolPackage/service/Crypter');
    // $container::getSelfObject()->setKernelObject(new framework\kernel\routing\SetWebsite());
    $container::getSelfObject()->setKernelObject(new framework\kernel\security\Security());
    $container::getSelfObject()->setKernelObject(new framework\kernel\security\SecurityEventHandler());

    // dump(App::isWebsiteSet());

    include($sourceRelPath.'framework/kernel/routing/SetWebsite.php');
    new framework\kernel\routing\SetWebsite();

    // include($sourceRelPath.'framework/kernel/base/Config.php');
    // $config = new framework\kernel\base\Config();
    // $container::getSelfObject()->setKernelObject($config);

    // dump(App::getContainer()->getConfig()->getProjectData('VATProfile'));
    // dump(App::getContainer()->getConfig()->getProjectData('VATProfile'));
    // dump(App::getWebsite());
    // dump(App::getWebProject());exit;
    // dump('alma');exit;

    $container::getSelfObject()->setKernelObject(new framework\kernel\ClassManager\loader\Loader());
    $operationSupervisor = new framework\kernel\operation\OperationSupervisor();
    $operationSupervisor->init();
    $container::getSelfObject()->setKernelObject($operationSupervisor);

    //dump($container::getSelfObject()->getSession()->get('maintenanceMode'));
    if (!$container::getSelfObject()->getSession()->get('maintenanceMode')) {
        $container::getSelfObject()->getKernelObject('Security')->preparationSecurity();
    }
    // $container::getSelfObject()->setKernelObject(new framework\kernel\security\Security());
    $container::getSelfObject()->setKernelObject(new framework\kernel\security\RequestSecurity());
    $container::getSelfObject()->setKernelObject(new framework\kernel\request\UploadRequest());
    $container::getSelfObject()->setKernelObject(new framework\kernel\request\SetUploadRequests());
    $container::getSelfObject()->setKernelObject(new framework\kernel\request\Request());
    $container::getSelfObject()->setKernelObject(new framework\kernel\request\SetPostRequests());

    // $container::getSelfObject()->setKernelObject(new framework\kernel\ClassManager\loader\Loader());

    $container::getSelfObject()->setKernelObject(new framework\kernel\routing\RouteMapper());
    // dump($container::getSelfObject()->getFullRouteMap());exit;

    $container::getSelfObject()->setKernelObject(new framework\kernel\widget\WidgetMapper());
    // $container::getSelfObject()->setService('UserPackage/service/UserFactory');
    // $container::getSelfObject()->setKernelObject(new framework\kernel\request\VisitorHandler());
    $container::getSelfObject()->setKernelObject(new framework\kernel\routing\RoutingHelper());
    $container::getSelfObject()->setKernelObject(new framework\kernel\routing\Router());
    // $container::getSelfObject()->getKernelObject('Session')->initLocale();

    // dump($container::getSelfObject()->getRouting()->getActualRoute());exit;

    $container::getSelfObject()->setService('UserPackage/service/UserFactory');
    $container::getSelfObject()->setKernelObject(new framework\kernel\request\VisitorHandler());
    $container::getSelfObject()->setKernelObject(new framework\kernel\eventHandling\OnPageLoadEventHandler());
    // $container::getSelfObject()->setKernelObject(new framework\kernel\ClassManager\loader\Loader());
    // $security = $container::getSelfObject()->getKernelObject('Security');
    // $security->finishingSecurity();
    $container::getSelfObject()->setKernelObject(new framework\kernel\ClassManager\ControllerLoader());





    // $dbm = App::getContainer()->getKernelObject('DbManager');
    // dump($dbm->queries);exit;



}
catch(\Error $e) {
    // dump($e);exit;
    $exceptionController = new framework\kernel\exception\controller\ExceptionController();
    $exceptionController->basicAction($e);
}
catch(framework\component\exception\ElastiException $e) {
    $exceptionController = new framework\kernel\exception\controller\ExceptionController();
    $exceptionController->basicAction($e);
    // dump($e);
    // exit;
}
catch(\Exception $e) {
    // dump($e);exit;
    $exceptionController = new framework\kernel\exception\controller\ExceptionController();
    $exceptionController->basicAction($e);
	// dump($e);exit;
}
catch(\ErrorException $e) {
    // dump($e);exit;
    $exceptionController = new framework\kernel\exception\controller\ExceptionController();
    $exceptionController->basicAction($e);
	// dump($e);exit;
}
catch(\ErrorException $e) {
    // dump($e);exit;
    $exceptionController = new framework\kernel\exception\controller\ExceptionController();
    $exceptionController->basicAction($e);
	// dump($e);exit;
}

function includeConfig($path) {
    // dump($path);exit;
    if (framework\kernel\utility\FileHandler::fileExists($path)) {
        include($path);
    }
}


?>
