<?php

include($sourceRelPath.'framework/kernel/utility/FileHandler.php');
include($sourceRelPath.'framework/component/exception/ElastiException.php');
include($sourceRelPath.'framework/component/helper/StringHelper.php');
include($sourceRelPath.'framework/kernel/utility/BasicUtils.php');
include($sourceRelPath.'framework/component/helper/DateUtils.php');
include($sourceRelPath.'framework/kernel/component/Kernel.php');
include($sourceRelPath.'framework/component/parent/Rendering.php');
// include($sourceRelPath.'framework/kernel/debug/Dumper/dump.php');
include($sourceRelPath.'framework/kernel/debug/AdvancedDump/AdvancedDump.php');
include($sourceRelPath.'framework/kernel/base/Container.php');
include($sourceRelPath.'framework/kernel/base/Reflector.php');
include($sourceRelPath.'framework/component/parent/RouteRendering.php');
include($sourceRelPath.'framework/kernel/base/Config.php');
include($sourceRelPath.'framework/kernel/base/Includer.php');
include($sourceRelPath.'framework/kernel/routing/Url.php');
include($sourceRelPath.'framework/kernel/routing/SetWebsite.php');
include($sourceRelPath.'framework/kernel/request/Session.php');
include($sourceRelPath.'framework/kernel/ClassManager/AutoLoaderFactory.php');
include($sourceRelPath.'framework/component/parent/Service.php');
include($sourceRelPath.'framework/component/interfaces/UserInterface.php');
include($sourceRelPath.'framework/component/interfaces/EntityManagerInterface.php');
include($sourceRelPath.'framework/component/parent/PackageLoader.php');
include($sourceRelPath.'framework/component/parent/Repository.php');
include($sourceRelPath.'framework/component/parent/FileBasedStorageRepository.php');
include($sourceRelPath.'framework/component/parent/FileBasedStorageEntity.php');
include($sourceRelPath.'framework/component/parent/TechnicalRepository.php');
include($sourceRelPath.'framework/component/parent/TechnicalEntity.php');
include($sourceRelPath.'framework/component/parent/DbRepository.php');
include($sourceRelPath.'framework/component/parent/DbEntity.php');
include($sourceRelPath.'framework/component/parent/DynamicDbRepository.php');
include($sourceRelPath.'framework/component/parent/DynamicDbEntity.php');
include($sourceRelPath.'framework/kernel/ClassManager/loader/Loader.php');
include($sourceRelPath.'framework/kernel/routing/RouteMapper.php');
include($sourceRelPath.'framework/kernel/widget/WidgetMapper.php');
include($sourceRelPath.'framework/kernel/DbManager/loader/Loader.php');
include($sourceRelPath.'framework/kernel/EntityManager/loader/Loader.php');
include($sourceRelPath.'framework/kernel/EntityRelationMapper/loader/Loader.php');
include($sourceRelPath.'framework/kernel/DbSchemaManager/loader/Loader.php');
include($sourceRelPath.'framework/kernel/request/VisitorHandler.php');
include($sourceRelPath.'framework/packages/ToolPackage/service/Uploader.php');
include($sourceRelPath.'framework/component/parent/Response.php');
include($sourceRelPath.'framework/kernel/security/Security.php');
include($sourceRelPath.'framework/kernel/security/SecurityEventHandler.php');
include($sourceRelPath.'framework/kernel/security/RequestSecurity.php');
include($sourceRelPath.'framework/kernel/security/SecurityReporting.php');
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
include($sourceRelPath.'framework/kernel/ClassManager/ControllerLoader.php');
include($sourceRelPath.'framework/kernel/exception/controller/ExceptionController.php');
include($sourceRelPath.'framework/kernel/EntityManager/EntityChecker.php');
include($sourceRelPath.'framework/kernel/operation/OperationSupervisor.php');

set_error_handler(
    function($errno, $errstr, $errfile, $errline, $errcontext) {
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

setlocale(LC_ALL, 'hu_HU.UTF-8');
date_default_timezone_set('Europe/Budapest');
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

framework\kernel\base\Container::setSelfObject();
$container = framework\kernel\base\Container::getSelfObject();
// \App::get()->setContainer($container);

try {
    $container::getSelfObject()->setKernelObject($configReader);
    $container::getSelfObject()->setPathBases($pathBaseConfig, $sourcePath);
    $routing = new framework\kernel\routing\entity\Routing();
    $container::getSelfObject()->setKernelObject($routing);
    $container::getSelfObject()->setRouting($routing);
    $container::getSelfObject()->setKernelObject(new framework\kernel\routing\Url());
    $container::getSelfObject()->setKernelObject(new framework\kernel\base\Config());
    $container::getSelfObject()->setKernelObject(new framework\kernel\base\Reflector());
    $container::getSelfObject()->setKernelObject(new framework\kernel\request\Session());
    $container::getSelfObject()->setKernelObject(new framework\kernel\ClassManager\AutoLoaderFactory());
    $container::getSelfObject()->setKernelObject(new framework\kernel\DbManager\loader\Loader());
    $container::getSelfObject()->setKernelObject(new framework\kernel\EntityManager\loader\Loader());
    $container::getSelfObject()->setKernelObject(new framework\kernel\EntityRelationMapper\loader\Loader());
    $container::getSelfObject()->setKernelObject(new framework\kernel\DbSchemaManager\loader\Loader());
    $container::getSelfObject()->setService('ToolPackage/service/Crypter');
    $container::getSelfObject()->setKernelObject(new framework\kernel\routing\SetWebsite());
    $container::getSelfObject()->setKernelObject(new framework\kernel\ClassManager\loader\Loader());
    $operationSupervisor = new framework\kernel\operation\OperationSupervisor();
    $operationSupervisor->init();
    $container::getSelfObject()->setKernelObject($operationSupervisor);
    $container::getSelfObject()->setKernelObject(new framework\kernel\routing\RouteMapper());
    $container::getSelfObject()->setKernelObject(new framework\kernel\routing\RoutingHelper());
    $container::getSelfObject()->setKernelObject(new framework\kernel\routing\Router());
    $container::getSelfObject()->getKernelObject('Session')->initLocale();

    dump($container::getSelfObject());
}
catch(\Error $e) {
    // dump($e);exit;
    $exceptionController = new framework\kernel\exception\controller\ExceptionController();
    $exceptionController->basicAction($e);
}
catch(framework\component\exception\ElastiException $e) {
    $exceptionController = new framework\kernel\exception\controller\ExceptionController();
    $exceptionController->basicAction($e);
    // dump($e);exit;
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
